@extends('layouts.frontend.main')

@breadcrumbs(['Artikel', route('frontend.articles.index')])

<x-frontend.seo :pageConfig="$pageConfig" />

@section('content')
    <x-frontend.page-header :breadcrumbs="$breadcrumbs" :image="data_get($pageConfig, 'background_image')">
        {{ data_get($content, 'header', '') }}
    </x-frontend.page-header>

    <div class="news-page content-page">
        @include('contents.frontend.partials.main.articles.newest')
        @include('contents.frontend.partials.common.container-divider')
        @include('contents.frontend.partials.main.articles.achievement')
        @include('contents.frontend.partials.common.container-divider')
        @include('contents.frontend.partials.main.articles.best-research')
        @include('contents.frontend.partials.common.container-divider')
        @include('contents.frontend.partials.main.articles.research-activity')
        {{-- @include('contents.frontend.partials.common.container-divider') --}}
        {{-- @include('contents.frontend.partials.main.articles.announcement') --}}
    </div>
@endsection
