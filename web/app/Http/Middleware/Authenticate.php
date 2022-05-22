<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
	/**
	* Handle an unauthenticated user.
	*
	* @param  \Illuminate\Http\Request  $request
	* @param  array  $guards
	* @return void
	*
	* @throws \Illuminate\Auth\AuthenticationException
	*/
	protected function unauthenticated($request, array $guards)
	{
		if(in_array('api', $request->route()->middleware())) {
			// HACK: Couldn't use laravel's response function here. So, we manually do it instead.
			http_response_code(401);
			header('Content-Type: text/plain');
			exit('login required');
		}
		
		throw new \Illuminate\Auth\AuthenticationException(
			'Unauthenticated.', $guards, $this->redirectTo($request)
		);
	}
	
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if(!$request->expectsJson()) {
            return route('auth.login.index');
        }
    }
}
