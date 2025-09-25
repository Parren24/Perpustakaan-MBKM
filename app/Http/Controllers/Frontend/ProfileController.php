<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Profile\HistoryService;
use App\Services\Profile\AchievementsService;
use App\Services\Profile\IdentityService;
use App\Services\Profile\LocationService;
use App\Services\Profile\OrganizationService;
use App\Services\Frontend\SafeDataService;
use App\Services\Profile\VisiMisiService;
use App\Services\Profile\DiversityService;
use App\Services\Profile\WelcomeYpcrService;
use App\Services\Profile\AccreditationService;
use App\Services\Profile\WelcomeDirectorService;

class ProfileController extends Controller
{
    public function history()
    {
        $fallbacks = SafeDataService::getHistoryFallbacks();

        $content = SafeDataService::safeExecute(
            fn() => HistoryService::getContent(),
            $fallbacks
        );

        $tableOfContents = SafeDataService::safeExecute(
            fn() => HistoryService::getTableOfContents(),
            $fallbacks->table_of_contents
        );

        $pageConfig = SafeDataService::safeExecute(
            fn() => HistoryService::getPageConfig(),
            SafeDataService::getPageConfigFallbacks()
        );

        return view('contents.frontend.pages.profile.history', compact(
            'content',
            'tableOfContents',
            'pageConfig'
        ));
    }

    public function visiMisi()
    {
        $fallbacks = SafeDataService::getVisiMisiFallbacks();

        $content = SafeDataService::safeExecute(
            fn() => VisiMisiService::getContent(),
            $fallbacks
        );

        $pageConfig = SafeDataService::safeExecute(
            fn() => VisiMisiService::getPageConfig(),
            SafeDataService::getPageConfigFallbacks()
        );

        return view('contents.frontend.pages.profile.visi-misi', compact(
            'content',
            'pageConfig'
        ));
    }

    public function welcomeYpcr()
    {
        $fallbacks = SafeDataService::getWelcomeYpcrFallbacks();

        $content = SafeDataService::safeExecute(
            fn() => WelcomeYpcrService::getContent(),
            $fallbacks
        );

        $pageConfig = SafeDataService::safeExecute(
            fn() => WelcomeYpcrService::getPageConfig(),
            SafeDataService::getPageConfigFallbacks()
        );

        return view('contents.frontend.pages.profile.welcome-ypcr', compact(
            'content',
            'pageConfig'
        ));
    }

    public function welcomeDirector()
    {
        $fallbacks = SafeDataService::getWelcomeDirectorFallbacks();
        $content   = SafeDataService::safeExecute(
            fn() => WelcomeDirectorService::getContent(),
            $fallbacks
        );

        $pageConfig = SafeDataService::safeExecute(
            fn() => WelcomeDirectorService::getPageConfig(),
            SafeDataService::getPageConfigFallbacks()
        );

        return view('contents.frontend.pages.profile.welcome-director', compact(
            'content',
            'pageConfig'
        ));
    }

    public function diversity()
    {
        $fallbacks = SafeDataService::getDiversityFallbacks();
        $content   = SafeDataService::safeExecute(
            fn() => DiversityService::getContent(),
            $fallbacks
        );

        $pageConfig = SafeDataService::safeExecute(
            fn() => DiversityService::getPageConfig(),
            SafeDataService::getPageConfigFallbacks()
        );

        return view('contents.frontend.pages.profile.diversity', compact(
            'content',
            'pageConfig'
        ));
    }

    public function organization()
    {
        $fallbacks = SafeDataService::getOrganizationFallbacks();

        $content = SafeDataService::safeExecute(
            fn() => OrganizationService::getContent(),
            $fallbacks
        );

        $pageConfig = SafeDataService::safeExecute(
            fn() => OrganizationService::getPageConfig(),
            SafeDataService::getPageConfigFallbacks()
        );

        return view('contents.frontend.pages.profile.organization', compact(
            'content',
            'pageConfig'
        ));
    }

    public function identity()
    {
        $fallbacks = SafeDataService::getIdentityFallbacks();

        $content = SafeDataService::safeExecute(
            fn() => IdentityService::getContent(),
            $fallbacks
        );

        $pageConfig = SafeDataService::safeExecute(
            fn() => IdentityService::getPageConfig(),
            SafeDataService::getPageConfigFallbacks()
        );

        return view('contents.frontend.pages.profile.identity', compact(
            'content',
            'pageConfig'
        ));
    }

    public function location()
    {
        $fallbacks = SafeDataService::getLocationFallbacks();
        $content   = SafeDataService::safeExecute(
            fn() => LocationService::getContent(),
            $fallbacks
        );

        $pageConfig = SafeDataService::safeExecute(
            fn() => LocationService::getPageConfig(),
            SafeDataService::getPageConfigFallbacks()
        );

        return view('contents.frontend.pages.profile.location', compact(
            'content',
            'pageConfig'
        ));
    }

    public function accreditation()
    {
        $fallbacks = SafeDataService::getAccreditationFallbacks();
        $content   = SafeDataService::safeExecute(
            fn() => AccreditationService::getContent(),
            $fallbacks
        );

        $pageConfig = SafeDataService::safeExecute(
            fn() => AccreditationService::getPageConfig(),
            SafeDataService::getPageConfigFallbacks()
        );

        return view('contents.frontend.pages.profile.accreditation', compact(
            'content',
            'pageConfig'
        ));
    }

    public function achievements()
    {
        $fallbacks = SafeDataService::getAchievementsFallbacks();

        $content = SafeDataService::safeExecute(
            fn() => AchievementsService::getContent(),
            $fallbacks
        );

        $pageConfig = SafeDataService::safeExecute(
            fn() => AchievementsService::getPageConfig(),
            SafeDataService::getPageConfigFallbacks()
        );

        return view('contents.frontend.pages.profile.achievements', compact(
            'content',
            'pageConfig'
        ));
    }

    public function yearlyReport()
    {
        return view('contents.frontend.pages.profile.yearly-report', compact('header'));
    }
}
