<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Controller class for all user management tasks
 * @author wduda
 *
 */
class Kohana_Controller_Users extends Controller_Dashboard_Template {

	public function before()
	{
		parent::before();

	}

	public function after()
	{

		parent::after();
	}

	/**
	 * Changing password for logged user
	 */
	public function action_changepassword()
	{
		$this->set_active_menu_item('users_changepassword');

		$data = array();
		$errors = array();
		if ($this->request->method() == "POST")
		{
			$data = $this->request->post();

			$valid = Validation::factory($data)
				->rules('current_password', array(
					array('not_empty'),
					array(array(Auth::instance(), 'check_password'))
				))
				->rules('new_password', array(
					array('not_empty'),
					array('min_length', array(':value', 8)),
				))
				->rules('repeat_password', array(
					array('matches', array(':validation', ':field', 'new_password')),
				));

			if ($valid->check())
			{
				$this->user->password = Arr::get($data, 'new_password');
				$this->user->save();
				$this->add_alert_success("Hasło zostało zmienione");
				$data = array();
			}
			else
			{
				$errors = $valid->errors('forms/user');
				$data['new_password'] = $data['repeat_password'] = '';
			}
		}
$this->debug($errors);
		$this->set_content(
			View::factory('users/changepassword')
				->bind('data', $data)
				->bind('errors', $errors)
			);
	}

} // End Kohana_Controller_Users
