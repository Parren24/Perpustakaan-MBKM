@extends('layouts.frontend.main')

@breadcrumbs([data_get($pageConfig, 'seo.title'), url()->current()])

<x-frontend.seo :pageConfig="$pageConfig" />

@section('content')
    <x-frontend.page-header :breadcrumbs="$breadcrumbs" :image="data_get($pageConfig, 'background_image')">
        {{ data_get($content, 'header') }}
    </x-frontend.page-header>

    <div class="scholarship-page content-page">
        @include('contents.frontend.partials.main.academic.scholarship.index')
        @include('contents.frontend.partials.main.academic.scholarship.list')
    </div>
@endsection
