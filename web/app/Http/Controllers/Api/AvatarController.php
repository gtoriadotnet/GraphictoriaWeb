<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use App\Helpers\BrickColorHelper;
use App\Helpers\ValidationHelper;
use App\Http\Controllers\Controller;
use App\Models\AvatarAsset;
use App\Models\AvatarColor;
use App\Models\UserAsset;

class AvatarController extends Controller
{
	protected $validAssetTypeIds = [
		'17', // Heads
		'18', // Faces
		'8',  // Hats
		'2',  // T-Shirts
		'11', // Shirts
		'12', // Pants
		'19', // Gear
		'27', // Torsos
		'29', // Left Arms
		'28', // Right Arms
		'30', // Left Legs
		'31', // Right Legs
		'32'  // Packages
	];
	
	public function redrawUser()
	{
		Auth::user()->redraw();
		
		return response(['success' => true]);
	}
	
	public static function GetUserAssets($userId)
	{
		return UserAsset::where('owner_id', $userId)
							->whereRelation('asset', 'moderated', false)
							->orderByDesc('id');
	}
	
	public function listAssets(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'assetTypeId' => ['required', 'int']
		]);
		
		if($validator->fails()) {
			return ValidationHelper::generateValidatorError($validator);
		}
		
		$valid = $validator->valid();
		
		if(!in_array($valid['assetTypeId'], $this->validAssetTypeIds)) {
			$validator->errors()->add('assetTypeId', 'Invalid assetTypeId supplied.');
			return ValidationHelper::generateValidatorError($validator);
		}
		
		$userAssets = self::GetUserAssets(Auth::user()->id)
							->whereRelation('asset', 'assetTypeId', $valid['assetTypeId'])
							->groupBy('asset_id')
							->paginate(12);
		$data = [];
		
		foreach($userAssets as $userAsset)
		{
			$asset = $userAsset->asset;
			
			array_push($data, [
				'id' => $asset->id,
				'Url' => $asset->getShopUrl(),
				'Thumbnail' => $asset->getThumbnail(),
				'Name' => $asset->name,
				'Wearing' => Auth::user()->isWearing($asset->id)
			]);
		}
		
		return response([
			'data' => $data,
			'pages' => ($userAssets->hasPages() ? $userAssets->lastPage() : 1)
		]);
	}
	
	public function listWearing(Request $request)
	{
		$avatarAssets = AvatarAsset::where('owner_id', Auth::user()->id)->get();
		$data = [];
		
		foreach($avatarAssets as $avatarAsset)
		{
			$asset = $avatarAsset->asset;
			
			array_push($data, [
				'id' => $asset->id,
				'Url' => $asset->getShopUrl(),
				'Thumbnail' => $asset->getThumbnail(),
				'Name' => $asset->name,
				'Wearing' => true
			]);
		}
		
		return response([
			'data' => $data
		]);
	}
	
    public function wearAsset(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'id' => [
				'required',
				Rule::exists('App\Models\Asset', 'id')->where(function($query) {
					return $query->where('moderated', false);
				})
			]
		]);
		
		if($validator->fails()) {
			return ValidationHelper::generateValidatorError($validator);
		}
		
		$valid = $validator->valid();
		
		$userAsset = self::GetUserAssets(Auth::user()->id)
							->where('asset_id', $valid['id'])
							->first();
		
		if(!$userAsset) {
			$validator->errors()->add('id', 'User does not own asset.');
			return ValidationHelper::generateValidatorError($validator);
		}
		
		if(Auth::user()->isWearing($valid['id']) && $userAsset->asset->assetTypeId == 8) { // 8 = hat
			$validator->errors()->add('id', 'User is already wearing asset.');
			return ValidationHelper::generateValidatorError($validator);
		}
		
		if(!in_array($userAsset->asset->assetTypeId, $this->validAssetTypeIds)) {
			$validator->errors()->add('id', 'This asset cannot be worn.');
			return ValidationHelper::generateValidatorError($validator);
		}
		
		$wornItems = AvatarAsset::where('owner_id', Auth::user()->id)
								->whereRelation('asset', 'assetTypeId', $userAsset->asset->assetTypeId);
		if($userAsset->asset->assetTypeId != 8 && $wornItems->exists()) // 8 = hat
		{
			$wornItems->delete();
		}
		elseif($userAsset->asset->assetTypeId == 8 && $wornItems->count() >= 10)
		{
			$validator->errors()->add('id', 'User has hit the wearing limit on this asset type.');
			return ValidationHelper::generateValidatorError($validator);
		}
		
		Auth::user()->wearAsset($valid['id']);
		Auth::user()->redraw();
		
		return response(['success' => true]);
	}
	
	public function removeAsset(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'id' => [
				'required',
				Rule::exists('App\Models\Asset', 'id')->where(function($query) {
					return $query->where('moderated', false);
				})
			]
		]);
		
		if($validator->fails()) {
			return ValidationHelper::generateValidatorError($validator);
		}
		
		$valid = $validator->valid();
		
		if(!Auth::user()->isWearing($valid['id'])) {
			$validator->errors()->add('id', 'User is not wearing asset.');
			return ValidationHelper::generateValidatorError($validator);
		}
		
		AvatarAsset::where('owner_id', Auth::user()->id)
					->where('asset_id', $valid['id'])
					->delete();
		
		Auth::user()->redraw();
		
		return response(['success' => true]);
	}
	
	public function setBodyColor(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'part' => ['required', 'regex:/(Head|Torso|LeftArm|RightArm|LeftLeg|RightLeg)/i'],
			'color' => ['required', 'int']
		]);
		
		if($validator->fails()) {
			return ValidationHelper::generateValidatorError($validator);
		}
		
		$valid = $validator->valid();
		
		if(!BrickColorHelper::isValidColor($valid['color'])) {
			$validator->errors()->add('color', 'Invalid color id.');
			return ValidationHelper::generateValidatorError($validator);
		}
		
		$part = strtolower($valid['part']);
		switch($part)
		{
			case 'leftarm':
				$part = 'leftArm';
				break;
			case 'rightarm':
				$part = 'rightArm';
				break;
			case 'leftleg':
				$part = 'leftLeg';
				break;
			case 'rightleg':
				$part = 'rightLeg';
				break;
		}
		
		$bodyColors = Auth::user()->getBodyColors();
		$bodyColors->{$part} = $valid['color'];
		$bodyColors->save();
		
		Auth::user()->redraw();
		
		return response(['success' => true]);
	}
	
	public function getBodyColors(Request $request)
	{
		$bodyColors = Auth::user()->getBodyColors();
		
		return response([
			'data' => [
				'Head' => $bodyColors->head,
				'Torso' => $bodyColors->torso,
				'RightArm' => $bodyColors->rightArm,
				'LeftArm' => $bodyColors->leftArm,
				'RightLeg' => $bodyColors->rightLeg,
				'LeftLeg' => $bodyColors->leftLeg
			]
		]);
	}
}
