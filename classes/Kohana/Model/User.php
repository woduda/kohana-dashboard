<?php defined('SYSPATH') OR die('No direct access allowed.');

class Kohana_Model_User extends Model_Auth_User {

	/**
	 * User initial status after creation.
	 * User has no login role until his status changes to CONFIRMED
	 * @var int
	 */
	const STATUS_CREATED = 0;

	/**
	 * User status after confirming by clicking
	 * verification link from e-mail and after setting account password
	 * @var int
	 */
	const STATUS_CONFIRMED = 1;

	const LOGIN_ROLE_ID = 1;

	protected $_has_many = array(
		'user_tokens' => array('model' => 'User_Token'),
		'hashlinks'   => array('model' => 'User_Hashlink'),
		'roles'       => array('model' => 'Role', 'through' => 'roles_users'),
	);

	public function rules()
	{
		$rules = parent::rules();

		$rules['password'] = array();	// empty rules for password
		$rules['username'][] = array('alpha_numeric');

		// new rules
		$_rules = array(
			'first_name' => array(
				array('not_empty'),
				array('max_length', array(":value", 254)),
			),
			'last_name' => array(
				array('not_empty'),
				array('max_length', array(":value", 254)),
			),
		);

		return $rules + $_rules;
	}

	public function labels()
	{
		return array(
			'username'         => 'Username',
			'email'            => 'E-mail',
			'password'         => 'Password',
			'first_name'       => 'First name',
			'last_name'        => 'Last name',
		);
	}

	public function save(Validation $validation = NULL)
	{
		if ( ! $this->loaded())
		{
			$this->created = time();
		}

		return parent::save($validation);
	}

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
			throw new Kohana_Exception("Can't operate on unloaded Model_User");

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
		return $this->has_role(Kohana_Model_User::LOGIN_ROLE_ID);
	}

	public function has_role($role_id)
	{
		if ( ! $this->loaded())
			throw new Kohana_Exception("Can't operate on unloaded Model_User");

		return (bool) DB::select(array(DB::expr('COUNT(*)'), 'total_count'))
			->from("roles_users")
			->where("user_id", '=', $this->id)
			->where("role_id", '=', $role_id)
			->execute($this->_db)
			->get('total_count');
	}

	/**
	 * Creates new haslink for user
	 * @param email - email from user will be used if this param not set
	 * @throws Kohana_Exception
	 * @return Model_User_Hashlink $hashlink new ORM object
	 */
	public function hashlink($email = NULL)
	{
		if ( ! $this->loaded())
			throw new Kohana_Exception("Can't operate on unloaded Model_User");

		// create new hash:
		$hashlink = ORM::factory('User_Hashlink');
		$hashlink->user = $this;
		$hashlink->disabled = 0;
		$hashlink->email = ($email === NULL ? $this->email : $email);

		return $hashlink->save();
	}

	/**
	 * Disable all not disabled hashlinks
	 * @param string $except_emails
	 * @return Kohana_Model_User
	 */
	public function disable_hashlinks($except_emails = NULL)
	{
		$query = DB::update('user_hashlinks')
			->where('user_id', '=', $this->id)
			->where('disabled', '=', 0)
			->set(array(
				'disabled' => time(),
			));

		if ($except_emails !== NULL)
		{
			$query->where('email', 'IN', (array) $except_emails);
		}
		$query->execute($this->_db);

		return $this;
	}

} // End Kohana_Model_User
