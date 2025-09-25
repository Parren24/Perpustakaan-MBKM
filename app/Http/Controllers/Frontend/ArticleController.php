<?php

namespace App\Http\Controllers\Frontend;

use App\Services\Frontend\ArticleService;
use App\Services\Frontend\SafeDataService;
use App\Static\Data\NewsData;
use App\Http\Controllers\Controller;
use App\Models\Post\PostKategori;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index()
    {
        $fallbacks = SafeDataService::getArticleFallbacks();

        $content = SafeDataService::safeExecute(
            fn() => ArticleService::getContent(),
            $fallbacks
        );

        $pageConfig = SafeDataService::safeExecute(
            fn() => ArticleService::getPageConfig(),
            SafeDataService::getPageConfigFallbacks()
        );

        return view('contents.frontend.pages.articles.index', compact(
            'content',
            'pageConfig'
        ));
    }

    public function show($articleSlug)
    {
        $fallbacks = SafeDataService::getArticleShowFallbacks();

        $content = SafeDataService::safeExecute(
            fn() => ArticleService::getContent($articleSlug),
            $fallbacks
        );

        $pageConfig = SafeDataService::safeExecute(
            fn() => ArticleService::getPageConfig($content),
            SafeDataService::getPageConfigFallbacks()
        );

        return view('contents.frontend.pages.articles.show', compact(
            'content',
            'pageConfig'
        ));
    }

    public function byKategori($categoriesCode = 'berita')
    {
        $page = request()->get('page') ?? 1;

        $fallbacks = SafeDataService::getArticleArchiveFallbacks();
        ArticleService::getArchiveContent('category', $categoriesCode, $page);

        $content = SafeDataService::safeExecute(
            fn() => ArticleService::getArchiveContent('category', $categoriesCode, $page),
            $fallbacks
        );

        $pageConfig = SafeDataService::safeExecute(
            fn() => ArticleService::getPageConfig($content),
            SafeDataService::getPageConfigFallbacks()
        );

        return view('contents.frontend.pages.articles.archive', compact(
            'content',
            'pageConfig'
        ));
    }

    public function byLabel($labelCode = 'prestasi', $paging = 1)
    {
        $page = request()->get('page') ?? 1;

        $fallbacks = SafeDataService::getArticleArchiveFallbacks();
        ArticleService::getArchiveContent('label', $labelCode, $page);

        $content = SafeDataService::safeExecute(
            fn() => ArticleService::getArchiveContent('label', $labelCode, $page),
            $fallbacks
        );

        $pageConfig = SafeDataService::safeExecute(
            fn() => ArticleService::getPageConfig($content),
            SafeDataService::getPageConfigFallbacks()
        );

        return view('contents.frontend.pages.articles.archive', compact(
            'content',
            'pageConfig'
        ));
    }
}
