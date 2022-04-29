<?php

/*
	Graphictoria 2022
	Grid helper
*/

namespace App\Helpers;

use Illuminate\Http\Request;

use App\Helpers\COMHelper;
use App\Models\WebsiteConfiguration;

class GridHelper
{
	public static function isIpWhitelisted() {
		$ip = request()->ip();
		$whitelistedIps = explode(';', WebsiteConfiguration::where('name', 'WhitelistedIPs')->first()->value);
		
		return in_array($ip, $whitelistedIps);
	}
	
	public static function isAccessKeyValid() {
		$accessKey = WebsiteConfiguration::where('name', 'ComputeServiceAccessKey')->first()->value;
		
		return (request()->header('AccessKey') == $accessKey);
	}
	
	public static function hasAllAccess() {
		if(COMHelper::isCOM()) return true;
		if(GridHelper::isIpWhitelisted() && GridHelper::isAccessKeyValid()) return true;
		
		return false;
	}
}
