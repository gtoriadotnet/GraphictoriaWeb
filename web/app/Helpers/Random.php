<?php

/*
	Graphictoria 2022
	Random generator
*/

namespace App\Helpers;

class Random
{
	/**
     * Creates a random string of the specified length.
     *
     * @return String
     */
	public static function Str($length) {
		// https://stackoverflow.com/questions/4356289/php-random-string-generator
		
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
}
