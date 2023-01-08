<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use App\Helpers\GridHelper;
use App\Helpers\ValidationHelper;
use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\AssetVersion;
use App\Models\RobloxAsset;
use App\Models\UserAsset;

class ClientController extends Controller
{
	function assetRegularValidator()
	{
		return [
			'id' => [
				'required',
				Rule::exists('App\Models\Asset', 'id')->where(function($query) {
					return $query->where('moderated', false)
									->where('approved', true);
				})
			],
			'version' => [
				'sometimes',
				'numeric'
			]
		];
	}
	
	function assetVersionValidator()
	{
		return [
			'assetversionid' => [
				'required',
				Rule::exists('App\Models\AssetVersion', 'id')
			]
		];
	}
	
	function userAssetValidator()
	{
		return [
			'userassetid' => [
				'required',
				Rule::exists('App\Models\UserAsset', 'id')
			]
		];
	}
	
    function asset(Request $request)
	{
		$reqData = array_change_key_case($request->all());
		$isVersionIdRequest = array_key_exists('assetversionid', $reqData);
		$isUserAssetIdRequest = array_key_exists('userassetid', $reqData);
		
		$validatorRuleSet = 'assetRegularValidator';
		if($isVersionIdRequest)
			$validatorRuleSet = 'assetVersionValidator';
		elseif($isUserAssetIdRequest)
			$validatorRuleSet = 'userAssetValidator';
		
		$validator = Validator::make($reqData, $this->{$validatorRuleSet}());
		
		if($validator->fails())
		{
			$rbxAsset = RobloxAsset::where('robloxAssetId', $request->get('id'))->first();
			if($rbxAsset)
				return redirect()->route('client.asset', ['id' => $rbxAsset->localAssetId]);
			
			return redirect('https://assetdelivery.roblox.com/v1/asset?id=' . ($request->get('id') ?: 0));//return ValidationHelper::generateValidatorError($validator);
		}
		
		$valid = $validator->valid();
		$asset = null;
		
		if($isVersionIdRequest) {
			$assetVersion = AssetVersion::where('id', $valid['assetversionid'])->first();
			$asset = $assetVersion->asset;
			
			$valid['version'] = $assetVersion->localVersion;
		} elseif($isUserAssetIdRequest) {
			$userAsset = UserAsset::where('id', $valid['userassetid'])->first();
			$asset = $userAsset->asset;
		} else {
			$asset = Asset::where('id', $valid['id'])->first();
		}
		
		if(!$isVersionIdRequest && !array_key_exists('version', $valid))
			$valid['version'] = 0;
		
		if($asset == null) {
			$validator->errors()->add('version', 'Unknown asset' . ($isVersionIdRequest ? ' version' : null) . '.');
			return ValidationHelper::generateValidatorError($validator);
		}
		
		if(
			!($asset->onSale || (Auth::check() && Auth::user()->id == $asset->creatorId)) // not on sale and not the creator
			&&
			!($asset->copyable()) // asset isn't defaulted to open source
			&&
			!GridHelper::hasAllAccess() // not grid
		) {
			$validator->errors()->add('id', 'You do not have access to this asset.');
			return ValidationHelper::generateValidatorError($validator);
		}
		
		$contentHash = $asset->getContent($valid['version']);
		if(!$contentHash) {
			$validator->errors()->add('version', 'Unknown asset version.');
			return ValidationHelper::generateValidatorError($validator);
		}
		
		return redirect(route('content', $contentHash));
	}
}
