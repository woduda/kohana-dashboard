<?php defined('SYSPATH') or die('No direct script access.');

Route::set('login', 'login')
	->defaults(array(
		'controller' => 'Dashboard',
		'action'     => 'login',
	));

Route::set('logout', 'logout')
	->defaults(array(
		'controller' => 'Dashboard',
		'action'     => 'logout',
	));
