<?php

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
	 * Register the exception handling callbacks for the application.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->renderable(function (BadRequestHttpException $e, $request) {
			return response()->view('errors.400', [], 400);
		});
		
		$this->renderable(function (UnauthorizedHttpException $e, $request) {
			return response()->view('errors.401', [], 401);
		});
		
		$this->renderable(function (AccessDeniedHttpException $e, $request) {
			return response()->view('errors.403', [], 403);
		});
		
		/*
		// Moved to route fallback
		$this->renderable(function (NotFoundHttpException $e, $request) {
			return response()->view('errors.404', [], 404);
		});
		
		// Moved to middleware
		$this->renderable(function (\ErrorException $e, $request) {
			return response()->view('errors.500', ['stack' => $e->getTraceAsString()], 500);
		});*/
	}
}
