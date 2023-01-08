<?php

/*
	XlXi 2022
	Number helper.
*/

namespace App\Helpers;

class NumberHelper
{
	public static function Abbreviate($number)
	{
		$divisors = array(
			pow(1000, 0) => '', // 1000^0 == 1
			pow(1000, 1) => 'K+', // Thousand
			pow(1000, 2) => 'M+', // Million
			pow(1000, 3) => 'B+', // Billion
			pow(1000, 4) => 'T+', // Trillion
			pow(1000, 5) => 'Qa+', // Quadrillion
			pow(1000, 6) => 'Qi+', // Quintillion
		);
		
		foreach ($divisors as $divisor => $shorthand) {
			if (abs($number) < ($divisor * 1000))
				break;
		}
		
		return number_format(floor($number / $divisor), 0) . $shorthand;
	}
}
