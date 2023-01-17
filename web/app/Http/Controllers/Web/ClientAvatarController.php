<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use App\Helpers\GridHelper;
use App\Helpers\ValidationHelper;
use App\Http\Controllers\Controller;
use App\Models\AvatarAsset;
use App\Models\User;

class ClientAvatarController extends Controller
{
	public function bodyColors(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'userId' => [
				'required',
				Rule::exists('App\Models\User', 'id')
			]
		]);
		
		if($validator->fails()) {
			return ValidationHelper::generateValidatorError($validator);
		}
		
		$valid = $validator->valid();
		$user = User::where('id', $valid['userId'])->first();
		
		if($user->hasActivePunishment() && $user->getPunishment()->isDeletion()) {
			$validator->errors()->add('id', 'User is moderated.');
			return ValidationHelper::generateValidatorError($validator);
		}
		
		$document = simplexml_load_string(GridHelper::getBodyColorsXML());
		$bodyColors = $user->getBodyColors();
		
		$document->xpath('//int[@name="HeadColor"]')[0][0] = $bodyColors->head;
		$document->xpath('//int[@name="TorsoColor"]')[0][0] = $bodyColors->torso;
		$document->xpath('//int[@name="LeftArmColor"]')[0][0] = $bodyColors->leftArm;
		$document->xpath('//int[@name="LeftLegColor"]')[0][0] = $bodyColors->leftLeg;
		$document->xpath('//int[@name="RightArmColor"]')[0][0] = $bodyColors->rightArm;
		$document->xpath('//int[@name="RightLegColor"]')[0][0] = $bodyColors->rightLeg;
		
		return response($document->asXML())
						->header('Content-Type', 'application/xml');
	}
	
    public function characterFetch(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'userId' => [
				'required',
				Rule::exists('App\Models\User', 'id')
			]
		]);
		
		if($validator->fails()) {
			return ValidationHelper::generateValidatorError($validator);
		}
		
		$valid = $validator->valid();
		$user = User::where('id', $valid['userId'])->first();
		
		if($user->hasActivePunishment() && $user->getPunishment()->isDeletion()) {
			$validator->errors()->add('id', 'User is moderated.');
			return ValidationHelper::generateValidatorError($validator);
		}
		
		$charApp = '';
		$charApp .= route('client.bodyColors', ['userId' => $user->id]);
		
		foreach($user->getWearing()->get() as $avatarAsset)
		{
			$charApp .= ';' . route('client.asset', ['id' => $avatarAsset->asset->id]);
			
			if($avatarAsset->asset->assetTypeId == 19) // Gear
				$charApp .= '&equipped=1';
		}
		
		return response($charApp)
						->header('Content-Type', 'text/plain');
	}
}
