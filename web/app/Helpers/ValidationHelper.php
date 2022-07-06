<?php

/*
	Graphictoria 2022
	Validation Helper
*/

namespace App\Helpers;

use Illuminate\Validation\Validator;

class ValidationHelper
{
	public static function generateErrorJSON(Validator $validator) {
		$errorModel = [
			'errors' => []
		];
		
		foreach($validator->errors()->all() as $error) {
			array_push($errorModel['errors'], ['code' => 400, 'message' => $error]);
		}
		
		return $errorModel;
	}
}
