<?php defined('SYSPATH') or die('No direct script access.');

return array(
		'username' => array(
			'unique' => 'User with the same name already exists',
		),
		'email' => array(
			'unique' => 'User with the same e-mail already exists',
		),
		'current_password' => array(
			'check_password' => 'Enter the current password',
		),
	);
