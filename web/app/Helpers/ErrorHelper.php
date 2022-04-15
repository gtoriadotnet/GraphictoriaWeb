<?php

/*
	Graphictoria 2022
	Error helper
*/

namespace App\Helpers;

class ErrorHelper
{
	/**
     * Returns a JSON array with the error code and message.
     *
     * @return Response
     */
	public static function error($data, $code = 400)
	{
		return response(['errors' => [$data]], 400);
	}
}
