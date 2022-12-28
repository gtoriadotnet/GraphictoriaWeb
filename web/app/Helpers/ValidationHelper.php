<?php

/*
	XlXi 2022
	Validation Helper
*/

namespace App\Helpers;

use Illuminate\Validation\Validator;

class ValidationHelper
{
	public static function generateValidatorError($validator) {
		return response(self::generateErrorJSON($validator), 400);
	}
	
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
