<?php defined('SYSPATH') or die('No direct script access.');

return array(
		'name' => 'Kohana Dashboard',	// Project name
		'email' => 'dashboard@dashboard.com',	// emails sender address
		'password_min_length' => 8,
		'top_menu' => array(
			'users_changepassword' => array(
				'name' => __('Change password'),
				'description' => __('Allows to change password of logged user'),
				'route' => 'dashboard',	// not required (defaults to 'dashboard')
				'controller' => 'users',
				'action' => 'changepassword',
				'role' => 'login',	// displayed only if logged user has this role; can be array of roles (all roles required to display); role is not checked if this option is not set or is empty
			),
		),
		'side_menu' => array(
			'users' => array(
				'name' => __("Users"),
				'description' => __('Users management'),
				'controller' => 'users',
				'action' => 'index',
				'role' => 'admin',
			),
		),
		'per_page' => 10,
	);
