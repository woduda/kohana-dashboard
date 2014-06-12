<?php defined('SYSPATH') or die('No direct script access.');

return array(
		'top_menu' => array(
			'users_changepassword' => array(
				'name' => 'Zmień hasło',
				'description' => 'Zmiana hasła aktualnie zalogowanego użytkownika',
				'controller' => 'users',
				'action' => 'changepassword',
			),
		),
		'side_menu' => array(
		),
		'per_page' => 10,
	);
