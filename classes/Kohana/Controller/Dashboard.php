<?php defined('SYSPATH') or die('No direct script access.');

class Kohana_Controller_Dashboard extends Controller_Dashboard_Template {

	protected function require_login()
	{
		return ( ! in_array($this->request->action(), array('login', 'logout')));
	}

	public function action_index()
	{
		$this->set_content(View::factory('index'));
	}

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
			$login = $this->request->post('login');
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
				$this->content->set('error', 'Błędny login lub hasło');
			}
		}
		$this->add_body_class('login');
	}

	public function action_logout()
	{
		Auth::instance()->logout();
		Session::instance()->destroy();

		//	$this->content = View::factory('admin/login');
		$this->redirect(Route::get('login')->uri());
	}

} // End Kohana_Controller_Dashboard
