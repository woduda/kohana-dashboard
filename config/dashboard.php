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
			),
		),
		'side_menu' => array(
		),
		'per_page' => 10,
	);
