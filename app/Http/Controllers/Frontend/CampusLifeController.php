<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Frontend\SafeDataService;
use App\Services\CampusLife\VirtualTourService;
use App\Services\CampusLife\FacilitiesService;

class CampusLifeController extends Controller
{
    public function facilities()
    {
        $fallbacks = SafeDataService::getFacilitiesFallbacks('index'); // Need to add this method in SafeDataService

        $content = SafeDataService::safeExecute(
            fn() => FacilitiesService::getIndexContent(),
            $fallbacks->content
        );

        $pageConfig = SafeDataService::safeExecute(
            fn() => FacilitiesService::getPageConfig('index'),
            SafeDataService::getPageConfigFallbacks()
        );

        return view('contents.frontend.pages.campus-life.facilities.index', compact(
            'content',
            'pageConfig'
        ));
    }

    public function facilityDetail($facilityId)
    {
        $fallbacks = SafeDataService::getFacilitiesFallbacks('detail'); // Need to add this method in SafeDataService

        $content = SafeDataService::safeExecute(
            fn() => FacilitiesService::getDetailContent($facilityId),
            $fallbacks->content
        );

        $pageConfig = SafeDataService::safeExecute(
            fn() => FacilitiesService::getPageConfig('detail', $facilityId),
            SafeDataService::getPageConfigFallbacks()
        );

        return view('contents.frontend.pages.campus-life.facilities.show', compact(
            'content',
            'pageConfig',
            'facilityId' // Keep for breadcrumbs or other specific uses if needed in the view
        ));
    }

    public function himpunan()
    {
        return view('contents.frontend.pages.campus-life.himpunan.index');
    }

    public function himpunanDetail($himpunanId)
    {
        return view('contents.frontend.pages.campus-life.himpunan.show', compact('himpunanId'));
    }

    public function studentOrganization()
    {
        return view('contents.frontend.pages.campus-life.student-organization.index');
    }

    public function studentOrganizationDetail($organizationId)
    {
        return view('contents.frontend.pages.campus-life.student-organization.show', compact('organizationId'));
    }

    public function ukm()
    {
        return view('contents.frontend.pages.campus-life.ukm.index');
    }

    public function ukmDetail($ukmId)
    {
        return view('contents.frontend.pages.campus-life.ukm.show', compact('ukmId'));
    }

    public function rental()
    {
        return view('contents.frontend.pages.campus-life.rental.index');
    }

    public function explorePekanbaru()
    {
        return view('contents.frontend.pages.campus-life.explore-pekanbaru');
    }

    public function virtualTour()
    {
        $fallbacks = SafeDataService::getVirtualTourFallbacks();

        $content = SafeDataService::safeExecute(
            fn() => VirtualTourService::getContent(),
            $fallbacks->content
        );

        $pageConfig = SafeDataService::safeExecute(
            fn() => VirtualTourService::getPageConfig(),
            SafeDataService::getPageConfigFallbacks()
        );

        return view('contents.frontend.pages.campus-life.virtual-tour', compact(
            'content',
            'pageConfig'
        ));
    }
}
