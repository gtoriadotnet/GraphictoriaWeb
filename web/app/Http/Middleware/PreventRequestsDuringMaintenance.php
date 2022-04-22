<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Route;
use Illuminate\Contracts\Foundation\Application;

class PreventRequestsDuringMaintenance
{
	protected $app;
	public function __construct(Application $app)
    {
        $this->app = $app;
    }
	
	public function handle($request, Closure $next)
	{
		if($this->app->isDownForMaintenance()) {
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
		
		return $next($request);
	}
}
