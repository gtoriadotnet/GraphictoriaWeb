<?php

namespace App\Http\Middleware;

use App\Helpers\MaintenanceHelper;

use Closure;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

class PreventRequestsDuringMaintenance
{
	public function handle(Request $request, Closure $next)
	{
		if(MaintenanceHelper::isDown($request))
		{
			if(str_starts_with($request->route()->getName(), 'maintenance.'))
				return $next($request);
			
			if(in_array('api', $request->route()->middleware()))
			{
				if($request->route()->getName() == 'content') // cdn.virtubrick.net
					return $next($request);
				
				return response(['errors' => [['code' => 503, 'message' => 'ServiceUnavailable']]], 503)
							->header('Cache-Control', 'private')
							->header('Content-Type', 'application/json; charset=utf-8');
			}
			
			// Not an API route.
			if($request->route()->uri() != 'maintenance')
				return redirect('/maintenance?ReturnUrl=' . urlencode(url()->full()));
		}
		else
		{
			if(str_starts_with($request->route()->getName(), 'maintenance.'))
			{
				$returnUrl = $request->input('ReturnUrl');
				
				if(!$returnUrl)
					$returnUrl = '/';
				
				return redirect($returnUrl);
			}
		}
		
		return $next($request);
	}
}
