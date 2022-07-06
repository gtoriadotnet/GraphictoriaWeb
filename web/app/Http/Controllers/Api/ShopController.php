<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ValidationHelper;
use App\Http\Controllers\Controller;
use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShopController extends Controller
{
	protected static function generateValidatorError($validator) {
		return response(ValidationHelper::generateErrorJSON($validator), 400);
	}
	
    protected function listjson(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'assetTypeId' => ['required', 'regex:/^\\d(,?\\d)*$/i'],
			'gearGenreId' => ['regex:/^\\d(,?\\d)*$/i']
		]);
		
		if($validator->fails()) {
			return ShopController::generateValidatorError($validator);
		}
		
		$valid = $validator->valid();
		
		if($valid['assetTypeId'] != '19' && isset($valid['gearGenreId'])) {
			$validator->errors()->add('gearGenreId', 'gearGenreId can only be used with typeId 19.');
			return ShopController::generateValidatorError($validator);
		}
		
		return response([
			'data' => [],
			'next_cursor' => null,
			'prev_cursor' => null
		]);
	}
}
