@extends('layouts.frontend.main')

@breadcrumbs([data_get($pageConfig, 'seo.title'), url()->current()])

<x-frontend.seo :pageConfig="$pageConfig" />

@section('content')
    <x-frontend.page-header :breadcrumbs="$breadcrumbs" :image="data_get($pageConfig, 'background_image')">
        {{ data_get($content, 'header') }}
    </x-frontend.page-header>

    <div class="facilities-page content-page">
        @include('contents.frontend.partials.main.campus-life.facilities.about')
        <div class="container">
            <div class="divider-dark-lg"></div>
        </div>
        @include('contents.frontend.partials.main.campus-life.facilities.facilities-list', ['facilities_list' => data_get($content, 'facilities_list')])
    </div>
@endsection
