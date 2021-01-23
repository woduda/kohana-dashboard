<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Controller class for errors serving
 * @author wduda
 *
 */
class Kohana_Controller_Dashboard_Errors extends Controller_Dashboard_Template {

	protected function require_login()
	{
		return FALSE;
	}

	public function action_index()
	{
		$this->set_content(View::factory('dashboard/errors/default'));
	}

	public function action_404()
	{
		$this->set_content(View::factory('dashboard/errors/404'));
	}

} // End Kohana_Controller_Dashboard_Errors
