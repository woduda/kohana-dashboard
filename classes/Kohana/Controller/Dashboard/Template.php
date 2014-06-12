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
	public $_template = 'templates/default';

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
	private $active_menu_item = NULL;

	/**
	 * Logged user object
	 * @var Model_User
	 */
	protected $user = NULL;

	/**
	 * Array of alerts to display
	 * @var array
	 */
	private $alerts = array();

	public function before()
	{
		// Run anything that need to run before this.
		parent::before();

		$logged = Auth::instance()->logged_in();
		if ( ! $logged AND $this->require_login())
		{
			$this->redirect(Route::get('login')->uri());
		}

		$this->user = Auth::instance()->get_user();

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

	protected function require_login()
	{
		return TRUE;
	}

	/**
	 * Fill in default values for our properties before rendering the output.
	 */
	public function after()
	{
		if ($this->auto_render)
		{
			if ($this->content instanceof View)
			{
				$this->content->set('logged', Auth::instance()->logged_in());
			}
			$this->template
				->bind('body_class', $this->body_class)
				->bind('content', $this->content)
				->bind('header_script', $this->header_script)
				->bind('footer_script', $this->footer_script);

			if (Auth::instance()->logged_in())
			{
				$this->set_active_menu_item(strtolower($this->request->controller()."_".$this->request->action()));

				$top_menu = View::factory('top_menu')
					->set('data', $this->dashboard_config->get('top_menu'))
					->bind('active_menu_item', $this->active_menu_item);

				$side_menu = View::factory('side_menu')
					->set('data', $this->dashboard_config->get('side_menu'))
					->bind('active_menu_item', $this->active_menu_item);

				$alerts = View::factory('alerts')
					->bind('alerts', $this->alerts);

				$this->template->bind('top_menu', $top_menu)
					->bind('side_menu', $side_menu)
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