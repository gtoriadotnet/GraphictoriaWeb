<?php

namespace App\Http\Middleware\Exception;

use Closure;

class WebException
{
	public function handle($request, Closure $next)
	{
		$response = $next($request);
		
		if ($response->exception) {
			return response()->view('errors.500', ['stack' => $response->exception], 500);
		}
		
        return $response;
	}
}
