<?php defined('SYSPATH') OR die('No direct access allowed.');

class Kohana_Controller_Dashboard_Base extends Controller {

	/**
	 * @var  string  dashboard configuration data
	 */
	public $dashboard_config = 'dashboard';

	/**
	 * @var mixed Body of the content, to be eventually included in the template and finally displayed
	 */
	protected $content = NULL;

	protected $log_path = '';

	/**
	 * Initialize properties before running the controller methods (actions),
	 * so they are available to our action.
	 */
	public function before()
	{
		// Run anything that need ot run before this.
		parent::before();

		// Loading dashboard config
		if ( ! $this->dashboard_config instanceof Kohana_Config)
		{
			$this->dashboard_config = Kohana::$config->load($this->dashboard_config);
			View::set_global('dashboard_config', $this->dashboard_config);
		}
		$this->log_path = sprintf('[%15s] ', Request::$client_ip).$this->request->uri().' -> ';
	}

	/**
	 * Fill in default values for our properties before rendering the output.
	 */
	public function after()
	{
		// New things go here:

		// Run anything that needs to run after this.
		parent::after();
	}

	public function is_remote()
	{
		if ($this->request->controller() == 'Errors')
			return FALSE;

		return ( ! $this->request->is_initial() || $this->request->is_ajax());
	}

	protected function set_content($view)
	{
		if ($view instanceof View)
		{
			$this->content = $view;
		}
		else if (is_string($view))
		{
			if (Kohana::find_file('views', $view))
			{
				$this->content = View::factory($view);
			}
			else
			{
				$this->content = $view;
			}
		}
		else
		{
			throw new Kohana_Exception('Could not set the page content ('.(string) $view.')');
		}
	}

	protected function add_to_content($view)
	{
		$this->content .= ($view instanceof View ? $view : View::factory($view));
	}

	protected function log($value, $level = Log::DEBUG)
	{
		if (is_scalar($value))
		{
			Kohana::$log->add($level, $this->log_path.$value);
		}
		else
		{
			Kohana::$log->add($level, $this->log_path.print_r($value, TRUE));
		}
	}

	protected function set_dashboard_config($config)
	{
		$this->dashboard_config = $config;
	}

	protected function debug($value)
	{
		$this->log($value, Log::DEBUG);
	}
	protected function error($value)
	{
		$this->log($value, Log::ERROR);
	}
	protected function warn($value)
	{
		$this->log($value, Log::WARNING);
	}
	protected function info($value)
	{
		$this->log($value, Log::INFO);
	}

} // End Kohana_Controller_Dashboard_Base
