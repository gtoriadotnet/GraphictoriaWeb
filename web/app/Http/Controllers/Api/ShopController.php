<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

use App\Helpers\ValidationHelper;
use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\Transaction;
use App\Models\TransactionType;
use App\Models\UserAsset;

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
							->paginate(35);
		
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
				'Url' => $asset->getShopUrl()
			]);
		}
		
		return response([
			'pages' => ($assets->hasPages() ? $assets->lastPage() : 1),
			'data' => $data
		]);
	}
	
	protected function purchase(Request $request, Asset $asset)
	{
		// TODO: XlXi: limiteds
		$validator = Validator::make($request->all(), [
			'expectedPrice' => ['int']
		]);
		
		if($validator->fails()) {
			return ValidationHelper::generateValidatorError($validator);
		}
		
		$valid = $validator->valid();
		
		$result = [
			'success' => false,
			'userFacingMessage' => null,
			'priceInTokens' => $asset->priceInTokens
		];
		$price = $asset->priceInTokens;
		$user = Auth::user();
		
		if($asset->assetType->locked)
		{
			$result['userFacingMessage'] = 'This asset cannot be purchased.';
			$result['priceInTokens'] = null;
			return response($result);
		}
		
		if($user->hasAsset($asset->id))
		{
			$result['userFacingMessage'] = 'You already own this item.';
			return response($result);
		}
		
		if($valid['expectedPrice'] != $price)
			return response($result);
		
		if($asset->priceInTokens > $user->tokens)
		{
			$result['userFacingMessage'] = 'You can\'t afford this item.';
			return response($result);
		}
		
		$result['success'] = true;
		
		Transaction::createAssetSale($user, $asset);
		$user->removeFunds($price);
		$asset->user->addFunds($price * (1-.3)); // XlXi: 30% tax
		UserAsset::createSerialed($user->id, $asset->id);
		
		return response($result);
	}
}
