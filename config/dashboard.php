<?php defined('SYSPATH') or die('No direct script access.');

return array(
		'name' => 'Kohana Dashboard',	// Project name
		'top_menu' => array(
			'users_changepassword' => array(
				'name' => 'Zmień hasło',
				'description' => 'Zmiana hasła aktualnie zalogowanego użytkownika',
				'route' => 'default',	// not required (defaults to 'default')
				'controller' => 'users',
				'action' => 'changepassword',
				'role' => 'login',	// displayed only if logged user has this role; can be array of roles (all roles required to display); role is not checked if this option is not set or is empty
			),
		),
		'side_menu' => array(
		),
		'per_page' => 10,
	);
