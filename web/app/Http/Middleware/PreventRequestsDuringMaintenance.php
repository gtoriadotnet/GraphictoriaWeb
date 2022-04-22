<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Route;

class PreventRequestsDuringMaintenance
{
	public function handle($request, Closure $next)
	{
		if(app()->isDownForMaintenance()) {
			if(in_array('web', $request->route()->middleware()))
			{
				if($request->route()->uri() != 'maintenance')
					return redirect('/maintenance?ReturnUrl=' . urlencode(url()->full()));
			}
			else
			{
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
