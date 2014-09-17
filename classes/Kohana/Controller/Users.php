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
		if ($this->auto_render)
		{
			$this->set_active_menu_item('users');
		}

		parent::after();
	}

	protected function require_login()
	{
		return ($this->request->action() != 'hash');
	}

	/**
	 * Simple list of registered users
	 */
	public function action_index()
	{
		$users = ORM::factory("User")
			->order_by("id", "DESC")
			->find_all();

		$statuses = array(
			Model_User::STATUS_CREATED => array(FALSE => __('Created not confirmed')),
			Model_User::STATUS_CONFIRMED => array(FALSE => __('Locked'), TRUE => __('Confirmed and active')),
		);
		$this->content = View::factory("users")
			->bind("users", $users)
			->bind("statuses", $statuses);
	}

	protected function check_user_id()
	{
		$user_id = $this->request->param("id");

		$user = ORM::factory('User', $user_id);

		if ( ! $user->loaded())
		{
			$this->redirect(Route::get('default')->uri(array("controller" => 'users')));
			// End
		}

		return $user;
	}

	public function action_add()
	{
		$user = ORM::factory('User');

		if ($this->add_edit($user))
		{
			$this->add_alert_success(__('New username :username was created.', array('username' => $user->username)));
			$this->redirect(Route::get('default')->uri(array("controller" => 'users')));
			// End
		}
	}

	public function action_edit()
	{
		$user = $this->check_user_id();

		if ($this->add_edit($user))
		{
			$this->add_alert_success(__('Changes to user :username were saved.', array('username' => $user->username)));
			$this->redirect(Route::get('default')->uri(array("controller" => 'users')));
			// End
		}
	}

	protected function add_edit(Model_User & $user)
	{
		$errors = array();

		if ($this->request->method() == Request::POST)
		{
			$data = $this->request->post();
			//
			return TRUE;
		}
		else if ($user->loaded())
		{
			$data = $user->as_array();
		}
		else // defaults
		{
			$data = array();
		}

		$this->content = View::factory("users/edit")
			->bind("user", $user)
			->bind("data", $data)
			->bind("errors", $errors);
	}

	public function action_on()
	{
		$user = $this->check_user_id();
		if ($user->id != $this->user->id) // ignore for logged in user
		{
			$user->active(TRUE);

			$this->add_alert_success(__('Access for username :username was unlocked.', array(":username" => $user->username)));
		}
		$this->redirect(Route::get('default')->uri(array("controller" => 'users')));
		// End
	}

	public function action_off()
	{
		$user = $this->check_user_id();
		if ($user->id != $this->user->id) // ignore for logged in user
		{
			$user->active(FALSE);

			$this->add_alert_success(__('Access for username :username was locked.', array(":username" => $user->username)));
		}
		$this->redirect(Route::get('default')->uri(array("controller" => 'users')));
		// End
	}

	public function action_sendactivationlink()
	{
		$user = $this->check_user_id();
		if ($user->id != $this->user->id AND $user->status == Model_User::STATUS_CREATED)
		{
			$name = $this->dashboard_config->get('name');

			// Search for not disabled hashlink for
			// this user and his current email
			$hashlink = $user->hashlinks
				->where('email', '=', $user->email)
				->where('disabled', '=', 0)
				->order_by('id', 'DESC')
				->limit(1)
				->find();

			if ( ! $hashlink->loaded())
			{
				// Disable hashlinks for this user with other emails and create new one:
				$hashlink = $user->disable_hashlinks()
					->hashlink();
			}

			$message = View::factory('emails/users/activation')
				->bind('user', $user)
				->bind('hashlink', $hashlink);

			Email::factory(__('Account activation at :name', array(":name" => $name)))
				->message($message)
				->from($this->dashboard_config->get('email'), $name)
				->to($user->email)
				->send();

			$this->add_alert_success(__('Activation link was sent to :email.', array(":email" => $user->email)));
		}

		$this->redirect(Route::get('default')->uri(array("controller" => 'users')));
		// End
	}

	public function action_hash()
	{
		$hash = $this->request->param('id');

		if (empty($hash))
			throw new HTTP_Exception_404();

		$hashlink = ORM::factory('User_Hashlink', array('hash' => $hash));

		if ( ! $hashlink->loaded())
			throw new HTTP_Exception_404();

		if ( ! empty($hashlink->disabled))
		{
			$this->content = View::factory('users/hashlink/expired');
			return;
			// End
		}

		$done = FALSE;
		$errors = array();
		$user = $hashlink->user;

		if ($this->request->method() == "POST")
		{
			$data = $this->request->post();

			$valid = Validation::factory($data)
				->rules('password', array(
					array('not_empty'),
					array('min_length', array(':value', $this->dashboard_config->get('password_min_length'))),
				))
				->rules('repeat_password', array(
					array('matches', array(':validation', ':field', 'password')),
				));

			if ($valid->check())
			{
				$user->password = Arr::get($data, 'password');
				$user->email = $hashlink->email;
				if ($user->status == Model_User::STATUS_CREATED)
				{
					$user->status = Model_User::STATUS_CONFIRMED;
					$user->save()
						->active(TRUE)
						->disable_hashlinks();
				}
				else
				{
					$user->save()
						->disable_hashlinks();
				}
				$done = TRUE;
			}
			else
			{
				$errors = $valid->errors('forms/user');
			}
		}

		$this->content = View::factory($done ? 'users/hashlink/changed' : 'users/hashlink')
			->bind('user', $user)
			->bind('errors', $errors)
			->bind('done', $done);
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
					array('min_length', array(':value', $this->dashboard_config->get('password_min_length'))),
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
