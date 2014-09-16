<?php defined('SYSPATH') OR die('No direct access allowed.');

class Kohana_Model_User_Hashlink extends ORM {

	// Relationships
	protected $_belongs_to = array(
		'user' => array('model' => 'User'),
	);

	public function save(Validation $validation = NULL)
	{
		if ( ! $this->loaded())
		{
			$this->hash = Text::random('alnum', rand(24, 32));
			$this->created = time();
		}

		return parent::save($validation);
	}

} // End Kohana_Model_User_Hashlink
