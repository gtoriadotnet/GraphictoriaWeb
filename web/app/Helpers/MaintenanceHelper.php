<?php

/*
	Graphictoria 2022
	Maintenance helper
*/

namespace App\Helpers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\MaintenanceModeBypassCookie;

class MaintenanceHelper
{
	public static function isDown(Request $request)
	{
		$data = json_decode(file_get_contents(storage_path('framework/down')), true);
		
		return (app()->isDownForMaintenance() && !MaintenanceHelper::hasValidBypassCookie($request, $data));
	}
	
	protected static function hasValidBypassCookie(Request $request, array $data)
    {
        return isset($data['secret']) &&
                $request->cookie('gt_constraint') &&
                MaintenanceModeBypassCookie::isValid(
                    $request->cookie('gt_constraint'),
                    $data['secret']
                );
    }
}
