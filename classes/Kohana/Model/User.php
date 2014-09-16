<?php defined('SYSPATH') OR die('No direct access allowed.');

class Kohana_Model_User extends Model_Auth_User {

	const LOGIN_ROLE_ID = 1;

	/**
	 * Sets or gets if user is active.
	 * Active means: "can login" = has login role
	 * @param boolean $active
	 * @throws Kohana_Exception
	 * @return boolean|Kohana_Model_User
	 */
	public function active($active = NULL)
	{
		if ( ! $this->loaded())
			throw new Kohana_Exception("Can't operatore on unloaded Model_User");

		$has = $this->has_login_role();

		// Getter:
		if ($active === NULL)
			return $has;

		// Setter:
		if ($active AND ! $has)
		{
			// activate (add login role)
			DB::insert("roles_users", array("user_id", "role_id"))
				->values(array($this->id, Kohana_Model_User::LOGIN_ROLE_ID))
				->execute($this->_db);
		}
		if ( ! $active AND $has)
		{
			// deactivate (remove login role)
			DB::delete("roles_users")
				->where("user_id", '=', $this->id)
				->where("role_id", '=', Kohana_Model_User::LOGIN_ROLE_ID)
				->execute($this->_db);
		}

		return $this;
	}

	public function has_login_role()
	{
		if ( ! $this->loaded())
			throw new Kohana_Exception("Can't operatore on unloaded Model_User");

		return (bool) DB::select(array(DB::expr('COUNT(*)'), 'total_count'))
			->from("roles_users")
			->where("user_id", '=', $this->id)
			->where("role_id", '=', Kohana_Model_User::LOGIN_ROLE_ID)
			->execute($this->_db)
			->get('total_count');
	}

} // End Kohana_Model_User
