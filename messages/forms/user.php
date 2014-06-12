<?php defined('SYSPATH') or die('No direct script access.');

return array(
		'current_password' => array(
			'not_empty' => 'Proszę wpisać aktualne hasło',
			'check_password' => 'Proszę wpisać aktualne hasło',
		),
		'new_password' => array(
			'not_empty' => 'Proszę wpisać nowe hasło',
			'min_length' => 'Nowe hasło musi składać się z co najmniej :param2 znaków',
		),
		'repeat_password' => array(
			'matches' => 'Powtórzone hasło musi być takie same jak nowe hasło',
		),
	);
