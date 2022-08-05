<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

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
