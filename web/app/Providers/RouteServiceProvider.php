<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/home';
	
	protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //$this->configureRateLimiting();

        $this->routes(function () {
            Route::domain('apis.' . env('APP_URL'))
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/apis.php'));
				
			Route::domain('api.' . env('APP_URL'))
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/clientapis.php'));
				
			Route::domain('assetgame.' . env('APP_URL'))
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/assetgame.php'));
				
			Route::domain('clientsettings.api.' . env('APP_URL'))
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/appsettings.php'));
				
			Route::domain('versioncompatibility.api.' . env('APP_URL'))
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/versioncompatibility.php'));
				
			Route::domain('impulse.' . env('APP_URL'))
                ->middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/admin.php'));
			
			Route::domain('www.' . env('APP_URL'))
				->middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));
				
			Route::domain(env('APP_URL'))
				->middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));
        });
    }
}
