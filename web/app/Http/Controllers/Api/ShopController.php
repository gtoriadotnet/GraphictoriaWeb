<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ValidationHelper;
use App\Http\Controllers\Controller;
use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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
	
	protected static function getAssets($assetTypeIds, $gearGenre=null)
	{
		// TODO: XlXi: Group owned assets
		return Asset::where('approved', true)
					->where('moderated', false)
					->where('onSale', true)
					->where(function($query) use($assetTypeIds, $gearGenre) {
						$query->whereIn('assetTypeId', explode(',', $assetTypeIds));
						
						if ($gearGenre != null)
							$query->whereIn('assetAttributeId', explode(',', $gearGenre));
					});
	}
	
    protected function listJson(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'assetTypeId' => ['required', 'regex:/^\\d(,?\\d)*$/i'],
			'gearGenreId' => ['regex:/^\\d(,?\\d)*$/i']
		]);
		
		if($validator->fails()) {
			return ValidationHelper::generateValidatorError($validator);
		}
		
		$valid = $validator->valid();
		
		foreach(explode(',', $valid['assetTypeId']) as $assetTypeId) {
			if(!in_array($assetTypeId, $this->validAssetTypeIds)) {
				$validator->errors()->add('assetTypeId', 'Invalid assetTypeId supplied.');
				return ValidationHelper::generateValidatorError($validator);
			}
		}
		
		if($valid['assetTypeId'] != '19' && isset($valid['gearGenreId'])) {
			$validator->errors()->add('gearGenreId', 'gearGenreId can only be used with assetTypeId 19.');
			return ValidationHelper::generateValidatorError($validator);
		}
		
		/* */
		
		$assets = self::getAssets($valid['assetTypeId'], (isset($valid['gearGenreId']) ? $valid['gearGenreId'] : null));
		$assets = $assets->orderByDesc('created_at')
							->paginate(30);
		
		$data = [];
		foreach($assets as $asset) {
			$creator = $asset->user;
			
			array_push($data, [
				'Name' => $asset->name,
				'Creator' => [
					'Name' => $creator->username,
					'Url' => $creator->getProfileUrl()
				],
				'Thumbnail' => $asset->getThumbnail(),
				'OnSale' => $asset->onSale,
				'Price' => $asset->priceInTokens,
				'Url' => route('shop.asset', ['asset' => $asset->id, 'assetName' => Str::slug($asset->name, '-')])
			]);
		}
		
		return response([
			'pages' => ($assets->hasPages() ? $assets->lastPage() : 1),
			'data' => $data
		]);
	}
}
