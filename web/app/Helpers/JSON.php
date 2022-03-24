<?php

/*
	Graphictoria 2022
	JSON Pretty Printer
*/

namespace App\Helpers;

class JSON
{
	public static function EncodeResponse($array) {
		$json = json_encode(
			$array,
			JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_LINE_TERMINATORS
		);
		
		return response($json)
			->header('Content-Type', 'application/json');
	}
}
