<?php

namespace App\Providers;

use App\Http\View\Composers\SiteIdentityComposer;
use App\Http\View\Composers\HeaderMenuComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

/**
 * View Service Provider
 * 
 * Service provider untuk mendaftarkan view composers
 * 
 * @author wahyudibinsaid
 */
class ViewServiceProvider extends ServiceProvider
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
        // Register Site Identity Composer for frontend layouts
        View::composer([
            'layouts.frontend.*',
            'contents.frontend.*'
        ], SiteIdentityComposer::class);

        // Register Header Menu Composer for header partial
        View::composer('contents.frontend.partials.common.header', HeaderMenuComposer::class);
    }
}
