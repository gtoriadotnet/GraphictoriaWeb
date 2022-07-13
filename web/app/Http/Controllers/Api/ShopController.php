<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ValidationHelper;
use App\Http\Controllers\Controller;
use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShopController extends Controller
{
	protected $validAssetTypeIds = [
		'2',  // T-Shirts
		'8',  // Hats
		'11', // Shirts
		'12', // Pants
		'17', // Heads
		'18', // Faces
		'19', // Gear
		'32'  // Packages
	];
	
	protected static function generateValidatorError($validator) {
		return response(ValidationHelper::generateErrorJSON($validator), 400);
	}
	
	protected function getAssets($assetTypeIds, $gearGenre=null)
	{
		// TODO: XlXi: IMPORTANT!! Do not return raw DB response, return only needed values.
		return Asset::where('approved', true)
					->where('moderated', false)
					->where('onSale', true)
					->where(function($query) use($assetTypeIds, $gearGenre) {
						$query->whereIn('assetTypeId', explode(',', $assetTypeIds));
						
						if ($gearGenre != null)
							$query->whereIn('assetAttributeId', explode(',', $gearGenre));
					})
					->get();
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
		
		foreach(explode(',', $valid['assetTypeId']) as $assetTypeId) {
			if(!in_array($assetTypeId, $this->validAssetTypeIds)) {
				$validator->errors()->add('assetTypeId', 'Invalid assetTypeId supplied.');
				return ShopController::generateValidatorError($validator);
			}
		}
		
		if($valid['assetTypeId'] != '19' && isset($valid['gearGenreId'])) {
			$validator->errors()->add('gearGenreId', 'gearGenreId can only be used with assetTypeId 19.');
			return ShopController::generateValidatorError($validator);
		}
		
		$assets = $this->getAssets($valid['assetTypeId'], (isset($valid['gearGenreId']) ? $valid['gearGenreId'] : null));
		
		return response([
			'pages' => 123,
			'data' => $assets,
			'next_cursor' => null,
			'prev_cursor' => null
		]);
	}
}
