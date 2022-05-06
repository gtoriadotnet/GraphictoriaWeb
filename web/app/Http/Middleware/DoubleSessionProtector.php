<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Session;

class DoubleSessionProtector
{
	protected function handlePage(Request $request, Closure $next) {
		if($request->route()->getName() != 'ddos.bypass' && !$request->isMethod('post')) {
			return redirect()
					->to(route('ddos.bypass', ['ReturnUrl' => urlencode('/'.$request->path())]), 302); 
		}
		
		return $next($request);
	}
	
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
		$record = Session::where('id', session()->getId())->where('bypass_block_screen', true)->first();
		if($record) {
			if($request->route()->getName() == 'ddos.bypass') {
				return redirect('/', 302);
			}
			
			return $next($request);
		}
		
		/* */
		
		$record = Session::where('ip_address', $request->ip());
		if($record->exists()) {
			foreach($record->get() as $session) {
				if($session->id != session()->getId())
					return $this->handlePage($request, $next);
			}
		}
		
		if($request->route()->getName() == 'ddos.bypass') {
			$returnUrl = $request->input('ReturnUrl');
			
			if(!$returnUrl)
				$returnUrl = '/';
			
			return redirect('/', 302);
		}
		
        return $next($request);
    }
}
