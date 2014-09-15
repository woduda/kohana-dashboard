<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Basic dashboard controller that supplies with login and logout action
 * This controller should be set as default controler for default route
 * @author wduda
 */

class Kohana_Controller_Dashboard extends Controller_Dashboard_Template {

	/**
	 * Returns TRUE for request to actions other than login an logout
	 * @see Kohana_Controller_Dashboard_Template::require_login()
	 */
	protected function require_login()
	{
		return ( ! in_array($this->request->action(), array('login', 'logout')));
	}

	/**
	 * Action to show some kind of "homepage" after login
	 */
	public function action_index()
	{
		$this->set_content(View::factory('index'));
	}

	/**
	 * Basic login action
	 */
	public function action_login()
	{
		if (Auth::instance()->logged_in())
		{
			$this->redirect(Route::get('default')->uri());
			// End
		}
		$this->content = View::factory('login');
		if ($this->request->method() == "POST")
		{
			$login = $this->request->post('username');
			$password = $this->request->post('password');

			$success = Auth::instance()->login($login, $password);

			if ($success)
			{
				$this->redirect(Route::get('default')->uri());
				// End
			}
			else
			{
				$this->info(Auth::instance()->hash_password($password));
				$this->content->set('error', __('Wrong username or password'));
			}
		}
		$this->add_body_class('login');
	}

	/**
	 * Basic logout action
	 */
	public function action_logout()
	{
		Auth::instance()->logout();
		Session::instance()->destroy();

		//	$this->content = View::factory('admin/login');
		$this->redirect(Route::get('login')->uri());
	}

} // End Kohana_Controller_Dashboard
