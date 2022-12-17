<?php

namespace App\Providers;

use Illuminate\Routing\Route as IlluminateRoute;
use Illuminate\Routing\Matching\UriValidator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

use App\Validators\CaseInsensitiveUriValidator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
		URL::forceScheme('https');
		
		$validators = IlluminateRoute::getValidators();
		$validators[] = new CaseInsensitiveUriValidator;
		IlluminateRoute::$validators = array_filter($validators, function($validator) { 
			return get_class($validator) != UriValidator::class;
		});
		
		Blade::directive('owner', function() {
            return '<?php if(Auth::check() && Auth::user()->hasRoleset(\'Owner\')): ?>';
        });
		
		Blade::directive('endowner', function() {
            return '<?php endif; ?>';
        });
		
		Blade::directive('admin', function() {
            return '<?php if(Auth::check() && Auth::user()->hasRoleset(\'Administrator\')): ?>';
        });
		
		Blade::directive('endadmin', function() {
            return '<?php endif; ?>';
        });
		
		Blade::directive('moderator', function() {
            return '<?php if(Auth::check() && Auth::user()->hasRoleset(\'Moderator\')): ?>';
        });
		
		Blade::directive('endmoderator', function() {
            return '<?php endif; ?>';
        });
    }
}
