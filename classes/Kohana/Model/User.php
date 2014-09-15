<?php defined('SYSPATH') OR die('No direct access allowed.');

class Kohana_Model_User extends Model_Auth_User {

	const LOGIN_ROLE_ID = 1;

	public function active($active = NULL)
	{
		$login_role = ORM::factory('Role', array('name' => 'login'));

		// Getter:
		if ($active === NULL)
		{
			return $this->has('roles', $login_role);
		}

		// Setter:
		if ($active AND ! $this->has('roles', $login_role))
		{
			$this->add('roles', $login_role);
		}
		if ( ! $active AND $this->has('roles', $login_role))
		{
			$this->remove('roles', $login_role);
		}

		return $this;
	}

} // End Kohana_Model_User
