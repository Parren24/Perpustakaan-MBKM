<?php

namespace App\Http\View\Composers;

use App\Services\Frontend\SiteIdentityService;
use App\Services\Frontend\SafeDataService;
use Illuminate\View\View;

/**
 * Site Identity View Composer
 * 
 * Composer untuk menyediakan data identitas situs ke semua view
 * 
 * @author wahyudibinsaid
 */
class SiteIdentityComposer
{
    /**
     * Bind data to the view.
     *
     * @param View $view
     * @return void
     */
    public function compose(View $view): void
    {
        $fallbacks = SafeDataService::getLandingFallbacks();

        $siteIdentity = SafeDataService::safeExecute(
            fn() => SiteIdentityService::getFooterData(),
            $fallbacks->site_identity
        );

        $siteMeta = SafeDataService::safeExecute(
            fn() => SiteIdentityService::getSiteMeta(),
            (object) ['title' => 'Politeknik Caltex Riau']
        );

        $view->with([
            'siteIdentity' => $siteIdentity,
            'siteMeta' => $siteMeta
        ]);
    }
}
