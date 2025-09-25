<?php

namespace App\Http\Controllers\Frontend\Academic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Frontend\SafeDataService;
use App\Services\Academic\ScholarshipService;

class MainController extends Controller
{
    public function scholarship()
    {
        $fallbacks = SafeDataService::getAcademicScholarshipFallbacks(); // Need to add this method in SafeDataService

        $content = SafeDataService::safeExecute(
            fn() => ScholarshipService::getContent(),
            $fallbacks->content
        );

        $pageConfig = SafeDataService::safeExecute(
            fn() => ScholarshipService::getPageConfig(),
            SafeDataService::getPageConfigFallbacks()
        );

        return view('contents.frontend.pages.academic.scholarship', compact(
            'content',
            'pageConfig'
        ));
    }
}
