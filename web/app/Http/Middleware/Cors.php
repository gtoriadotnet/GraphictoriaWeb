<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Cors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
		$trustedHosts = explode(',', env('TRUSTED_HOSTS'));
		$origin = parse_url($request->headers->get('origin'),  PHP_URL_HOST);
		$passCheck = false;
		
		foreach($trustedHosts as &$host)
		{
			if(str_ends_with($origin, $host))
				$passCheck = true;
		}
		
		$allowedOrigin = ('http' . ($request->secure() ? 's' : null) . '://' . $origin);
		
		if($passCheck && $request->getMethod() === 'OPTIONS' && $request->headers->has('Access-Control-Request-Method'))
		{
			return response('')
					->setStatusCode(204)
					->header('Access-Control-Allow-Origin', $allowedOrigin)
					->header('Access-Control-Allow-Methods', '*')
					->header('Access-Control-Max-Age', '86400');
		}
		
		$nextClosure = $next($request);
		
		if($passCheck)
		{
			$nextClosure
				->header('Access-Control-Allow-Origin', $allowedOrigin)
				->header('Vary', 'origin');
		}
		
        return $nextClosure;
    }
}
