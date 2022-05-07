<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckBan
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
		if(Auth::check() && Auth::user()->banId != null) {
			if($request->route()->getName() != 'moderation.notice' && $request->route()->getName() != 'logout') {
				return redirect()
							->to(route('moderation.notice', [], 302));
			}
		} else {
			return redirect('/', 302);
		}
		
        return $next($request);
    }
}
