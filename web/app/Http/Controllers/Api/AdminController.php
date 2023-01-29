<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use App\Helpers\AssetHelper;
use App\Helpers\CdnHelper;
use App\Helpers\GridHelper;
use App\Helpers\ValidationHelper;
use App\Http\Controllers\Controller;
use App\Jobs\AppDeployment;
use App\Models\AssetType;
use App\Models\Deployment;
use App\Models\RobloxAsset;
use App\Rules\AppDeploymentFilenameRule;

class AdminController extends Controller
{
	// Moderator+
	
	
	// Admin+
	function manualAssetUpload(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'asset-type-id' => ['required', 'int'],
			'name' => ['required', 'string'],
			'description' => ['string', 'nullable'],
			'roblox-id' => ['int', 'min:0', 'nullable'],
			'on-sale' => ['required', 'boolean'],
			'price' => ['required_if:on-sale,true', 'int', 'min:0'],
			'content' => ['nullable'],
			'mesh-id' => ['int', 'nullable'],
			'base-id' => ['int', 'nullable'],
			'overlay-id' => ['int', 'nullable'],
		],[
			'asset-type-id.required' => 'An asset type ID must be provided.',
			'roblox-id.integer' => 'Roblox ID must be an integer.'
		]);
		
		if($validator->fails())
			return ValidationHelper::generateValidatorError($validator);
		
		$valid = $validator->valid();
		$isRobloxAsset = ($request->has('roblox-id') && $valid['roblox-id'] > 0);
		
		if($isRobloxAsset)
		{
			$uploadedAsset = RobloxAsset::where('robloxAssetId', $valid['roblox-id'])->first();
			if($uploadedAsset)
			{
				$validator->errors()->add('roblox-id', 'This asset has already been uploaded!');
				return ValidationHelper::generateValidatorError($validator);
			}
		}
		
		$assetType = AssetType::where('id', $valid['asset-type-id'])
								->where('adminCreatable', 1)
								->first();
		if(!$assetType)
		{
			$validator->errors()->add('asset-type-id', 'Invalid asset type for admin upload.');
			return ValidationHelper::generateValidatorError($validator);
		}
		
		$assetFunction = 'Unknown';
		$assetFunctionArgs = [];
		switch($assetType->id)
		{
			case 27: // Torso
			case 28: // Right Arm
			case 29: // Left Arm
			case 30: // Left Leg
			case 31: // Right Leg
				$assetFunctionArgs = [$assetType->id, $valid];
				$assetFunction = 'BodyPart';
				break;
			case 18: // Face
				$assetFunctionArgs = [$validator, $valid];
				$assetFunction = 'Face';
				break;
			default:
				$assetFunctionArgs = [$validator];
				$assetFunction = 'Generic';
				break;
		}
		
		$assetContent = $this->{ 'manualAssetUpload' . $assetFunction }($request, ...$assetFunctionArgs);
		
		$hash = CdnHelper::SaveContent($assetContent, 'application/octet-stream');
		$asset = AssetHelper::newAsset([
			'creatorId' => 1,
			'name' => $valid['name'],
			'description' => $valid['description'],
			'approved' => true,
			'priceInTokens' => $valid['price'],
			'onSale' => $valid['on-sale'] == 1 ? true : false,
			'assetTypeId' => $assetType->id,
			'assetVersionId' => 0
		], $hash);
		$asset->logAdminUpload(Auth::user()->id);
		
		if($isRobloxAsset)
		{
			RobloxAsset::create([
				'robloxAssetId' => $valid['roblox-id'],
				'localAssetId' => $asset->id
			]);
		}
		
		return response([
			'success' => true,
			'message' => 'Your asset has been successfully uploaded!',
			'assetId' => $asset->id
		]);
	}
	
	function manualAssetUploadBodyPart(Request $request, $assetTypeId, $valid)
	{
		$bodyParts = [
			27 => 1, // Torso
			28 => 3, // Right Arm
			29 => 2, // Left Arm
			30 => 4, // Left Leg
			31 => 5  // Right Leg 
		];
		
		$document = simplexml_load_string(GridHelper::getBodyPartXML());
		$document->xpath('//int[@name="BaseTextureId"]')[0][0] = $valid['base-id'] ?: 0;
		$document->xpath('//token[@name="BodyPart"]')[0][0] = $bodyParts[$assetTypeId];
		$document->xpath('//int[@name="MeshId"]')[0][0] = $valid['mesh-id'];
		$document->xpath('//string[@name="Name"]')[0][0] = $valid['name'];
		$document->xpath('//int[@name="OverlayTextureId"]')[0][0] = $valid['overlay-id'] ?: 0;
		
		$domXML = dom_import_simplexml($document);
		$assetContent = $domXML->ownerDocument->saveXML($domXML->ownerDocument->documentElement);
		
		return $assetContent;
	}
	
	function manualAssetUploadFace(Request $request, $validator, $valid)
	{
		if(!$request->has('content'))
		{
			$validator->errors()->add('content', 'Asset content cannot be blank!');
			return ValidationHelper::generateValidatorError($validator);
		}
		
		$hash = CdnHelper::SaveContent(
			file_get_contents($request->file('content')->path()),
			'application/octet-stream'
		);
		$imageAsset = AssetHelper::newAsset([
			'creatorId' => 1,
			'name' => $valid['name'],
			'approved' => true,
			'onSale' => false,
			'assetTypeId' => 1, // Image
			'assetVersionId' => 0
		], $hash);
		$imageAsset->logAdminUpload(Auth::user()->id);
		$imageAssetUrl = route('client.asset', ['id' => $imageAsset->id]);
		
		$document = simplexml_load_string(GridHelper::getFaceXML());
		$document->xpath('//Content[@name="Texture"]')[0][0]->addChild('url', $imageAssetUrl);
		
		$domXML = dom_import_simplexml($document);
		$assetContent = $domXML->ownerDocument->saveXML($domXML->ownerDocument->documentElement);
		
		return $assetContent;
	}
	
	function manualAssetUploadGeneric(Request $request, $validator)
	{
		if(!$request->has('content'))
		{
			$validator->errors()->add('content', 'Asset content cannot be blank!');
			return ValidationHelper::generateValidatorError($validator);
		}
		
		$assetContent = file_get_contents($request->file('content')->path());
		
		return $assetContent;
	}
	
	function manualAssetUploadUnknown(Request $request)
	{
		throw new \BadMethodCallException('Not implemented');
	}
	
	// Owner+
	function deploy(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'version' => ['regex:/version\\-[a-fA-F0-9]{16}/'],
			'type' => ['required_without:version', 'regex:/(Deploy|Revert)/i'],
			'app' => ['required_without:version', 'regex:/(Client|Studio)/i']
		]);
		
		if($validator->fails())
			return ValidationHelper::generateValidatorError($validator);
		
		$valid = $validator->valid();
		
		$response = [
			'status' => 'Loading',
			'version' => null,
			'message' => 'Please wait...',
			'progress' => 0
		];
		
		if(!$request->has('version'))
		{
			$deployment = Deployment::newVersionHash($valid);
			
			$response['version'] = $deployment->version;
			$response['message'] = 'Created deployment.';
			$response['progress'] = 0;
			
			return response($response);
		}
		
		$deployment = Deployment::where('version', $valid['version'])->first();
		if($deployment === null || !$deployment->isValid()) {
			$validator->errors()->add('version', 'Unknown version deployment hash.');
			return ValidationHelper::generateValidatorError($validator);
		}
		
		$response['version'] = $deployment->version;
		
		if($deployment->error != null)
		{
			$response['status'] = 'Error';
			$response['message'] = sprintf('Failed to deploy %s. Error: %s', $deployment->version, $deployment->error);
			$response['progress'] = 1;
			return response($response);
		}
		
		$steps = 5;
		$response['progress'] = $deployment->step/$steps;
		switch($deployment->step)
		{
			case 0:
				$response['message'] = 'Files uploading.';
				break;
			case 1:
				$response['message'] = 'Batching deployment.';
				break;
			case 2:
				$response['message'] = 'Unpacking files.';
				break;
			case 3:
				$response['message'] = 'Updating version security.';
				break;
			case 4:
				$response['message'] = 'Pushing deployment to setup.';
				break;
			case 5:
				$response['status'] = 'Success';
				$response['message'] = sprintf('Deploy completed. Successfully deployed %s %s', $deployment->app, $deployment->version);
				break;
		}
		
		return response($response);
	}
	
	function deployVersion(Request $request, string $version)
	{
		$validator = Validator::make($request->all(), [
			'file.*' => ['required']
		]);
		
		if($validator->fails())
			return ValidationHelper::generateValidatorError($validator);
		
		$valid = $validator->valid();
		
		$deployment = Deployment::where('version', $version)->first();
		if($deployment === null || !$deployment->isValid() || $deployment->step != 0) {
			$validator->errors()->add('version', 'Unknown version deployment hash.');
			return ValidationHelper::generateValidatorError($validator);
		}
		
		$deploymentRule = new AppDeploymentFilenameRule($deployment->app);
		if(!$deploymentRule->passes('file', $request->file('file')))
		{
			$deployment->error = 'Missing files.';
			$deployment->save();
			
			$validator->errors()->add('file', $deployment->error);
			return ValidationHelper::generateValidatorError($validator);
		}
		
		foreach($request->file('file') as $file)
		{
			$file->storeAs(
				'setuptmp',
				sprintf('%s-%s', $version, $file->getClientOriginalName())
			);
		}
		
		$deployment->step = 1; // Batching deployment.
		$deployment->save();
		
		AppDeployment::dispatch($deployment);
	}
	
	// RCC Only
	function uploadRobloxAsset(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'contentId' => ['required', 'int']
		]);
		
		if($validator->fails())
			return ValidationHelper::generateValidatorError($validator);
		
		if(!GridHelper::hasAllAccess())
		{
			$validator->errors()->add('contentId', 'This API can only be called by the web service.');
			return ValidationHelper::generateValidatorError($validator);
		}
		
		$valid = $validator->valid();
		$asset = AssetHelper::uploadRobloxAsset($valid['contentId'], true);
		
		return route('client.asset', ['id' => $asset->id]);
	}
	
	function uploadAsset(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'contentId' => ['required', 'int']
		]);
		
		if($validator->fails())
			return ValidationHelper::generateValidatorError($validator);
		
		if(!GridHelper::hasAllAccess())
		{
			$validator->errors()->add('contentId', 'This API can only be called by the web service.');
			return ValidationHelper::generateValidatorError($validator);
		}
		
		$valid = $validator->valid();
		$asset = AssetHelper::uploadCustomRobloxAsset($valid['contentId'], true, base64_encode($request->getContent()));
		
		return route('client.asset', ['id' => $asset->id]);
	}
}
