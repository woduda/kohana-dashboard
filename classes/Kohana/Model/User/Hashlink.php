<?php defined('SYSPATH') OR die('No direct access allowed.');

class Kohana_Model_User_Hashlink extends ORM {

	// Relationships
	protected $_belongs_to = array(
		'user' => array('model' => 'User'),
	);

} // End Kohana_Model_User_Hashlink
