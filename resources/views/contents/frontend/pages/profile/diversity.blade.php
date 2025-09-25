@extends('layouts.frontend.main')

@breadcrumbs([data_get($pageConfig, 'seo.title'), url()->current()])

<x-frontend.seo :pageConfig="$pageConfig" />

@section('content')
    <x-frontend.page-header :breadcrumbs="$breadcrumbs" :image="data_get($pageConfig, 'background_image')">
        {{ data_get($content, 'header') }}
    </x-frontend.page-header>

    <div class="diversity-page content-page">
        @include('contents.frontend.partials.main.profile.diversity.description')
        {{-- @include('contents.frontend.partials.common.container-divider') --}}
        {{-- @include('contents.frontend.partials.main.profile.diversity.image-galery') --}}
    </div>
@endsection
