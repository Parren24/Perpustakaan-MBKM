<?php

namespace App\Http\Controllers\Frontend;

use App\Services\Frontend\NewsService;
use App\Services\Frontend\SafeDataService;
use App\Static\Data\NewsData;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index()
    {
        $pageConfig = SafeDataService::safeExecute(
            fn() => NewsService::getMetaData()
        );

        $news  = NewsService::getNews();

        $achievements = NewsService::getAchievements();

        $announcements = NewsService::getAnnouncements();

        $researches = NewsService::getResearch();

        $bestResearches = NewsService::getBestResearch();

        $agendas = NewsService::getAgenda();

        return view('contents.frontend.pages.news.index', compact('news', 'achievements', 'announcements', 'agendas', 'researches', 'bestResearches', 'pageConfig'));
    }

    public function show($postSlug)
    {
        $post = NewsService::getPost($postSlug);
        if (!$post)
            abort(404);

        $news = NewsService::getNews();

        $categories = NewsService::getCategories();


        return view('contents.frontend.pages.news.show', compact('post', 'categories', 'news'));
    }
}
