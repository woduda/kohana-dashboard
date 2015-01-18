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

/* Route below must be placed just before 'default' route definition:
Route::set('dashboard', '(<controller>(/<action>(/<id>)))')
	->defaults(array(
		'controller' => 'Dashboard',
		'action'     => 'index',
	));
*/
