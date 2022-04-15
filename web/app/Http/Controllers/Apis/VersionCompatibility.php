<?php

namespace App\Http\Controllers\Apis;

use Illuminate\Http\Request;

use App\Models\WebsiteConfiguration;
use App\Helpers\GridHelper;
use App\Helpers\ErrorHelper;

use App\Http\Controllers\Controller;

class VersionCompatibility extends Controller
{
	function getVersions(Request $request)
	{
		if(!GridHelper::hasAllAccess($request)) {
			return ErrorHelper::error([
				'code' => 1,
				'message' => 'You do not have access to this resource.'
			], 401);
		}
		
		return Response()->json([
			'data' => [
				explode(';', WebsiteConfiguration::where('name', 'VersionCompatibilityVersions')->first()->value)
			]
		]);
	}
	
	function getMD5Hashes(Request $request)
	{
		if(!GridHelper::hasAllAccess($request)) {
			return ErrorHelper::error([
				'code' => 1,
				'message' => 'You do not have access to this resource.'
			], 401);
		}
		
		return Response()->json([
			'data' => [
				explode(';', WebsiteConfiguration::where('name', 'VersionCompatibilityHashes')->first()->value)
			]
		]);
	}
	
	function getMemHashes(Request $request)
	{
		if(!GridHelper::hasAllAccess($request)) {
			return ErrorHelper::error([
				'code' => 1,
				'message' => 'You do not have access to this resource.'
			], 401);
		}
		
		return Response()->json([
			'data' => [
				//explode(';', WebsiteConfiguration::where('name', 'VersionCompatibilityHashes')->first()->value)
			]
		]);
	}
}
