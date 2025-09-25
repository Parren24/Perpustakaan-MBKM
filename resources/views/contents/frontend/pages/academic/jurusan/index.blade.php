@extends('layouts.frontend.main')

@breadcrumbs([data_get($content, 'header'), data_get($content, 'url')])

<x-frontend.seo :pageConfig="$pageConfig" />

@section('content')
    <x-frontend.page-header :breadcrumbs="$breadcrumbs" :image="data_get($pageConfig, 'background_image')">
        {{ data_get($content, 'header') }}
    </x-frontend.page-header>

    <div class="jurusan-page content-page">
        {{-- @include('contents.frontend.partials.main.academic.jurusan.about-all-jurusan') --}}
        {{-- @include('contents.frontend.partials.common.container-divider') --}}
        @include('contents.frontend.partials.main.academic.jurusan.index')
    </div>
@endsection
