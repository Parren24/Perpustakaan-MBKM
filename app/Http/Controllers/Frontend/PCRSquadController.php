<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Frontend\SafeDataService;
use App\Services\CampusLife\PCRSquadService;

class PCRSquadController extends Controller
{
    public function index()
    {
        $fallbacks = SafeDataService::getPCRSquadFallbacks(); // Need to add this method in SafeDataService

        $content = SafeDataService::safeExecute(
            fn() => PCRSquadService::getContent(),
            $fallbacks->content
        );

        $pageConfig = SafeDataService::safeExecute(
            fn() => PCRSquadService::getPageConfig(),
            SafeDataService::getPageConfigFallbacks()
        );

        return view('contents.frontend.pages.pcr-squad.index', compact(
            'content',
            'pageConfig'
        ));
    }
}
