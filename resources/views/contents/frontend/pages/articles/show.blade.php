@extends('layouts.frontend.main')

@breadcrumbs(['Artikel', route('frontend.articles.index')])
@breadcrumbs([data_get($content, 'title', ''), data_get($content, 'url', '')])

<x-frontend.seo :pageConfig="$pageConfig" />

@section('content')
    <x-frontend.page-header :breadcrumbs="$breadcrumbs" :image="data_get($pageConfig, 'background_image')">
        {{ data_get($content, 'header', '') }}
    </x-frontend.page-header>

    <div class="articles-page content-page">
        @include('contents.frontend.partials.main.articles.show.content')
    </div>
@endsection
