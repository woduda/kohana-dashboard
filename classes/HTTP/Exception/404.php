<?php defined('SYSPATH') or die('No direct script access.');

class HTTP_Exception_404 extends Kohana_HTTP_Exception_404 {

	/**
	 * Generate a Response for the 404 Exception.
	 *
	 * The user should be shown a nice 404 page.
	 *
	 * @return Response
	 */
	public function get_response()
	{
		Kohana_Exception::log($this);
		$response = Request::factory(Route::get('default')->uri(array('controller' => 'Errors', 'action' => '404')))->execute();

		$response->status(404);

		return $response;
	}
}