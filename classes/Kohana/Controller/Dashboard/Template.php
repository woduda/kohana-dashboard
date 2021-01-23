<?php defined('SYSPATH') OR die('No direct access allowed.');

class Kohana_Controller_Dashboard_Template extends Controller_Dashboard_Base {

	const ALERT_SUCCESS = 'success';
	const ALERT_INFO = 'info';
	const ALERT_WARNING = 'warning';
	const ALERT_DANGER = 'danger';

	/**
	 * @var boolean Turns auto-rendering on or off
	 **/
	public $auto_render = TRUE;

	/**
	 * @var string Page template
	 */
	public $template = NULL;

	/**
	 * @var string Page template filename
	 */
	public $_template = 'templates/dashboard';

	/**
	 * @var class of <body> tag
	 */
	protected $body_class = NULL;

	/**
	 * @var string Header inline JavaScript snippets
	 */
	private $header_script = NULL;

	/**
	 * @var string Footer inline JavaScript snippets
	 */
	private $footer_script = NULL;

	/**
	 * Name of choosen menu item
	 * @var string
	 */
	protected $active_menu_item = NULL;

	/**
	 * Logged user object
	 * @var Model_User
	 */
	protected $user = NULL;

	/**
	 * User roles saved there for futher has_role($role) check
	 * @var array
	 */
	protected $user_roles = array();

	/**
	 * Array of alerts to display
	 * @var array
	 */
	private $alerts = array();

	/**
	 * Kohana controller before() method that checks:
	 * * if user must be logged to perform this request (require_login() method)
	 * * if it is "remote" request
	 * Collects user object and creates template View
	 *
	 * @see Kohana_Controller_Dashboard_Base::before()
	 */
	public function before()
	{
		// Run anything that need to run before this.
		parent::before();

		$logged = Auth::instance()->logged_in();
		if ( ! $logged AND $this->require_login())
		{
			$this->redirect(Route::get('login')->uri());
		}

		$this->user = $this->logged_user();

		// If the request is a remote call (AJAX or HMVC), turn off autorendering
		if ($this->is_remote())
		{
			$this->auto_render = FALSE;
		}

		if ($this->auto_render)
		{
			// Create template View
			$this->template = View::factory();
			$this->add_body_class('no-js')
				->add_body_class($logged ? "logged" : "not-logged");

			// Get alerts from session
			$this->alerts = Session::instance()->get('alerts', array());
		}
	}

	/**
	 * Loads user and all user related data (roles)
	 * @return Model_User - logged user object
	 */
	protected function logged_user()
	{
		$user = Auth::instance()->get_user();
		if ( ! empty($user) AND $user->loaded())
		{
			$this->user_roles = $user->roles
				->find_all()
				->as_array('name');
		}

		return $user;
	}

	/**
	 * Checks if logged user has role or all of roles specifed by $role
	 * @param string|array $role - one role string or array of roles
	 * @return boolean TRUE if user has all roles
	 */
	protected function has_role($role, $any = FALSE)
	{
		if (empty($role))
			return TRUE;

		if (is_array($role))
		{
			foreach ($role as $r)
			{
				$has = array_key_exists($r, $this->user_roles);
				if ($any)
				{
					if ($has)
						return TRUE;
				}
				elseif ( ! $has)
					return FALSE;
			}
			return FALSE;
		}
		else
			return array_key_exists($role, $this->user_roles);
	}

	/**
	 * This function is used to determine if user must be logged
	 * and will be redirected to login page if is not.
	 * @see: Kohana_Controller_Dashboard_Template::before()
	 * @return boolean
	 */
	protected function require_login()
	{
		return TRUE;
	}

	/**
	 * Filters menu items according to auth roles
	 * @param array $items items array from config
	 * @return array filtered items
	 */
	protected function menu_items(array $items)
	{
		$_items = array();
		foreach ($items as $id => $item)
		{
			$role = Arr::get($item, 'role');
			if ( ! $this->has_role($role, TRUE))
				continue;

			$_items[$id] = $item;
		}

		return $_items;
	}

	protected function menu()
	{
		$_menu = $this->menu_items($this->dashboard_config->get('top_menu'));

		$top_menu = View::factory('dashboard/top_menu')
			->set('data', $_menu)
			->bind('active_menu_item', $this->active_menu_item);

		$_menu = $this->menu_items($this->dashboard_config->get('side_menu'));
		$side_menu = View::factory('dashboard/side_menu')
			->set('data', $_menu)
			->bind('active_menu_item', $this->active_menu_item);

		$this->template->bind('top_menu', $top_menu)
			->bind('side_menu', $side_menu);
	}

	/**
	 * Fill in default values for our properties before rendering the output.
	 */
	public function after()
	{
		if ($this->auto_render)
		{
			$this->template
				->bind('body_class', $this->body_class)
				->bind('content', $this->content)
				->bind('header_script', $this->header_script)
				->bind('footer_script', $this->footer_script);

			if (Auth::instance()->logged_in())
			{
				if ($this->content instanceof View)
				{
					$this->content->bind("logged_user", $this->user);
				}

				$this->menu();

				$alerts = View::factory('dashboard/alerts')
					->bind('alerts', $this->alerts);

				$this->template
					->bind('alerts', $alerts);

				Session::instance()->delete('alerts');
			}

			$this->template->set_filename($this->_template);

			$this->response->body($this->template);
		}

		// Run anything that needs to run after this.
		return parent::after();
	}

	protected function set_body_class($css_class)
	{
		$this->body_class = $css_class;

		return $this;
	}

	protected function add_body_class($css_class)
	{
		$this->body_class = ( ! empty($this->body_class) ? $this->body_class." " : "").$css_class;

		return $this;
	}

	protected function add_header_script($javascript)
	{
		$this->header_script = ( ! empty($this->header_script) ? $this->header_script."\n" : "").$javascript;

		return $this;
	}

	protected function set_header_script($javascript)
	{
		$this->header_script = $javascript;

		return $this;
	}

	protected function add_footer_script($javascript)
	{
		$this->footer_script = ( ! empty($this->footer_script) ? $this->footer_script."\n" : "").$javascript;

		return $this;
	}

	protected function set_footer_script($javascript)
	{
		$this->footer_script = $javascript;

		return $this;
	}

	protected function set_template($view)
	{
		if ($view instanceof View)
		{
			$this->_template = $view->get_filename();
		}
		else
		{
			$this->_template = (string) $view;
		}
		return $this;
	}

	protected function set_active_menu_item($item)
	{
		$this->active_menu_item = $item;

		return $this;
	}

	protected function add_alert($level, $message)
	{
		if ( ! array_key_exists($level, $this->alerts))
		{
			$this->alerts[$level] = array();
		}
		$this->alerts[$level][] = $message;

		Session::instance()->set('alerts', $this->alerts);

		return $this;
	}

	public function add_alert_success($message)
	{
		$this->add_alert(self::ALERT_SUCCESS, $message);

		return $this;
	}

	public function add_alert_info($message)
	{
		$this->add_alert(self::ALERT_INFO, $message);

		return $this;
	}

	public function add_alert_warning($message)
	{
		$this->add_alert(self::ALERT_WARNING, $message);

		return $this;
	}

	public function add_alert_danger($message)
	{
		$this->add_alert(self::ALERT_DANGER, $message);

		return $this;
	}

} // End Kohana_Controller_Dashboard_Template
