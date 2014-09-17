<?php defined('SYSPATH') OR die('No direct script access.');

class Valid extends Kohana_Valid {

	public static function each_in_array($values, $array)
	{
		$array = (array) $array;

		foreach ((array) $values as $value)
		{
			if ( ! in_array($value, $array))
				return FALSE;
		}

		return TRUE;
	}
} // End Valid
