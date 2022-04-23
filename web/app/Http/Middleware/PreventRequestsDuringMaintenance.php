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
		if(MaintenanceHelper::isDown($request)) {
			if(in_array('web', $request->route()->middleware()))
			{
				if($request->route()->uri() != 'maintenance')
					return redirect('/maintenance?ReturnUrl=' . urlencode(url()->full()));
			}
			else
			{
				if(in_array('api', $request->route()->middleware()) && str_starts_with($request->route()->uri(), 'v1/maintenance'))
					return $next($request);
				
				return response(['errors' => [['code' => 503, 'message' => 'ServiceUnavailable']]], 503)
						->header('Cache-Control', 'private')
						->header('Content-Type', 'application/json; charset=utf-8');
			}
		}
		else
		{
			if($request->route()->uri() == 'maintenance')
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
