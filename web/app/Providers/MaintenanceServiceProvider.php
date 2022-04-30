<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class MaintenanceServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
		Blade::directive('live', function() {
            return '<?php if(!\App\Helpers\MaintenanceHelper::isDown()): ?>';
        });
		
		Blade::directive('endlive', function() {
            return '<?php endif; ?>';
        });
    }
}
