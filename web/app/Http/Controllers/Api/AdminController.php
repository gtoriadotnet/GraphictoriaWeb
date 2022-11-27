<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Helpers\ValidationHelper;
use App\Http\Controllers\Controller;
use App\Jobs\AppDeployment;
use App\Models\Deployment;
use App\Rules\AppDeploymentFilenameRule;

class AdminController extends Controller
{
	// Moderator+
	
	
	// Admin+
	
	
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
}
