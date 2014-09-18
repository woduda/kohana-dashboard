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
			$this->add_alert_success(__('New username :username was created.', array(':username' => $user->username)));
			$this->redirect(Route::get('default')->uri(array("controller" => 'users')));
			// End
		}
	}

	public function action_edit()
	{
		$user = $this->check_user_id();

		if ($this->add_edit($user))
		{
			$this->add_alert_success(__('Changes to user :username were saved.', array(':username' => $user->username)));
			$this->redirect(Route::get('default')->uri(array("controller" => 'users')));
			// End
		}
	}

	protected function add_edit(Model_User & $user)
	{
		$errors = array();

		$roles = ORM::factory('Role')
			->where('id', '!=', Model_User::LOGIN_ROLE_ID)
			->order_by('id')
			->find_all()
			->as_array('id');

		if ($this->request->method() == Request::POST)
		{
			$data = $this->request->post();
			$_data = $data;	// operate on copy: $_data
			$email = $user->email; // keep email in the case od validation exception to restore this value in $user

			$external_validation = Validation::factory($_data)
				->labels(array(
					'repeat_email' => 'Repeat e-mail',
				))
				->rules('roles', array(
					array('each_in_array', array(':value', array_keys($roles))),
				))
				->rules('repeat_email', array(
					array('matches', array(':validation', ':field', 'email')),
				));

			if ($user->loaded())
			{
				if (empty($_data['email']))
				{
					// no email while editing means: no changing but ORM model need email value to be not empty
					$_data['email'] = $user->email;
				}
			}
			else
			{
				$_data['password'] = Text::random('alnum', 14);	// set random password for new user
			}

			try
			{
				$user->values($_data)
					->save($external_validation);
			}
			catch (ORM_Validation_Exception $vex)
			{
				$errors = $vex->errors('orm');
				$user->email = $email;	// restore original email value
			}

			// Manage roles for user:
			if (empty($errors))
			{
				$user_roles = (array) Arr::get($data, 'roles', array());
				foreach ($roles as $role)
				{
					// Adding:
					if (in_array($role->id, $user_roles) AND ! $user->has_role($role->id))
					{
						$user->add('roles', ORM::factory('Role', $role->id));
					}
					// Removing:
					if ( ! in_array($role->id, $user_roles) AND $user->has_role($role->id))
					{
						$user->remove('roles', ORM::factory('Role', $role->id));
					}
				}
				if ( ! empty($data['send_hashlink']))
				{
					$this->send_hashlink($user);
				}
				// finish saving
				return TRUE;
			}
		}
		else if ($user->loaded())
		{
			$data = $user->as_array();
			$data['email'] = '';
			$data['roles'] = array();
			foreach ($roles as $role)
			{
				if ($user->has_role($role->id))
				{
					$data['roles'][] = $role->id;
				}
			}
		}
		else // defaults for adding
		{
			$data = array(
				'send_hashlink' => '1',
			);
		}

		$this->content = View::factory("users/edit")
			->bind("user", $user)
			->bind("roles", $roles)
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
			$this->send_hashlink($user, FALSE);
		}

		$this->redirect(Route::get('default')->uri(array("controller" => 'users')));
		// End
	}

	protected function send_hashlink(Model_User & $user, $initial = TRUE)
	{
		$name = $this->dashboard_config->get('name');

		if ($initial)
		{
			// Disable all hashlinks for email
			// even if it's initial hashlink for new created user account
			// but the same email could be used in past for another account
			$hashlink = $user->disable_hashlinks()
				->hashlink();
		}
		else
		{
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
				->labels(array(
					'password' => 'Password',
				))
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
				$errors = $valid->errors('orm/user');
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
				->labels(array(
					'current_password' => 'Current password',
					'new_password' => 'New password',
					'repeat_password' => 'Repeat password',
				))
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

				$this->add_alert_success(__('Password has been changed'));
				$data = array();
			}
			else
			{
				$errors = $valid->errors('orm/user');
				$data['new_password'] = $data['repeat_password'] = '';
			}
		}

		$this->set_content(
			View::factory('users/changepassword')
				->bind('data', $data)
				->bind('errors', $errors)
			);
	}

} // End Kohana_Controller_Users
