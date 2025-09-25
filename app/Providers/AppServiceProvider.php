<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Force HTTPS for tunneling in local environment
        if (
            request()->server('HTTP_X_FORWARDED_PROTO') === 'https' ||
            request()->server('HTTP_X_FORWARDED_SSL') === 'on' ||
            request()->server('HTTPS') === 'on' ||
            request()->server('SERVER_PORT') == 443
        ) {
            URL::forceScheme(scheme: 'https');
        }

        if (env(key: 'APP_ENV') === 'local') {
            Http::globalOptions([
                'verify' => false,
            ]);
        }
    }
}
