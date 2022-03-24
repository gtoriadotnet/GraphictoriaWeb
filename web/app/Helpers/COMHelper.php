<?php

/*
	Graphictoria 2022
	JSON Pretty Printer
*/

namespace App\Helpers;

class COMHelper
{
	public static function isCOM() {
		return (php_sapi_name() === 'cli');
	}
}
