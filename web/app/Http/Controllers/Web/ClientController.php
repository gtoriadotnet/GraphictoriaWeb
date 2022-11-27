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
	
    function asset(Request $request)
	{
		// TODO: XlXi: userAssetId (owned asset)
		$reqData = array_change_key_case($request->all());
		
		$validatorRuleSet = 'assetRegularValidator';
		if(array_key_exists('assetversionid', $reqData))
			$validatorRuleSet = 'assetVersionValidator';
		elseif(array_key_exists('userassetid', $reqData))
			return response('todo');
		
		$validator = Validator::make($reqData, $this->{$validatorRuleSet}());
		
		if($validator->fails())
			return ValidationHelper::generateValidatorError($validator);
		
		$valid = $validator->valid();
		$asset = null;
		
		if(array_key_exists('assetversionid', $reqData)) {
			$assetVersion = AssetVersion::where('id', $valid['assetversionid'])->first();
			$asset = $assetVersion->asset;
			
			$valid['version'] = $assetVersion->localVersion;
		} else {
			$asset = Asset::where('id', $valid['id'])->first();
			
			if(!array_key_exists('version', $valid))
				$valid['version'] = 0;
		}
		
		if($asset == null) {
			$validator->errors()->add('version', 'Unknown asset version.');
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
