<?php

/*
	Graphictoria 2022
	JSON Pretty Printer
*/

namespace App\Helpers;

use App\Helpers\COMHelper;

use App\Models\WebsiteConfiguration;

class GridHelper
{
	public static function isIpWhitelisted($request) {
		$ip = $request->ip();
		$whitelistedIps = explode(';', WebsiteConfiguration::where('name', 'WhitelistedIPs')->first()->value);
		
		return in_array($ip, $whitelistedIps);
	}
	
	public static function isAccessKeyValid($request) {
		$accessKey = WebsiteConfiguration::where('name', 'ComputeServiceAccessKey')->first()->value;
		
		return ($request->header('AccessKey') == $accessKey);
	}
	
	public static function hasAllAccess($request) {
		if(COMHelper::isCOM()) return true;
		if(GridHelper::isIpWhitelisted($request) && GridHelper::isAccessKeyValid($request)) return true;
		
		return false;
	}
}
