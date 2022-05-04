<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DailyReward
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
		if (Auth::check() && Carbon::now()->gte(Auth::user()->next_reward)) {
            $user = Auth::user();
			
            $user->tokens += 15;
            $user->next_reward = Carbon::now()->addHours(24);
            $user->save();
        }
		
        return $next($request);
    }
}
