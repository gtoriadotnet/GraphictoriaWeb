<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UserPunishmentMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
		$isPunishmentRoute = str_starts_with($request->route()->getName(), 'punishment.');
		if(Auth::user() && Auth::user()->hasActivePunishment())
		{
			if($isPunishmentRoute || $request->route()->getName() == 'auth.logout')
				return $next($request);
			
			if(in_array('api', $request->route()->middleware()))
			{
				if($request->route()->getName() == 'content') // cdn.virtubrick.net
					return $next($request);
				
				return response(['errors' => [['code' => 0, 'message' => 'User is moderated']]], 403)
							->header('Cache-Control', 'private')
							->header('Content-Type', 'application/json; charset=utf-8');
			}
			
			// Not an API route.
			if(!$isPunishmentRoute)
				return redirect()->route('punishment.notice', ['ReturnUrl' => url()->full()]);
		}
		elseif($isPunishmentRoute)
		{
			$returnUrl = $request->input('ReturnUrl');
			
			if(!$returnUrl)
				$returnUrl = '/';
			
			return redirect($returnUrl);
		}
		
        return $next($request);
    }
}
