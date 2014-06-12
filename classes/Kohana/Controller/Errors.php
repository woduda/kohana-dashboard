<?php defined('SYSPATH') or die('No direct script access.');

class Kohana_Controller_Errors extends Controller_Dashboard_Template {

	public function action_index()
	{
		$this->set_content(View::factory('errors/default'));
	}

	public function action_404()
	{
		$this->set_content(View::factory('errors/404'));
	}

} // End Kohana_Controller_Errors
