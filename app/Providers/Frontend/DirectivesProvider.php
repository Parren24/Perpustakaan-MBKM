<?php

namespace App\Providers\Frontend;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class DirectivesProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Blade::directive('breadcrumbs', function ($expression) {
            return "<?php \$breadcrumbs[] = ['name' => ($expression)[0], 'url' => ($expression)[1]]; ?>";
        });

        // Safe data access directive
        Blade::directive('safeGet', function ($expression) {
            return "<?php echo \App\Services\Frontend\SafeDataService::get($expression); ?>";
        });

        // Safe property access directive
        Blade::directive('safeProp', function ($expression) {
            return "<?php echo \App\Services\Frontend\SafeDataService::getProperty($expression); ?>";
        });

        // Safe array access directive
        Blade::directive('safeArray', function ($expression) {
            return "<?php echo \App\Services\Frontend\SafeDataService::getArrayValue($expression); ?>";
        });
    }
}
