@extends('layouts.frontend.main')

@breadcrumbs([data_get($pageConfig, 'seo.title'), url()->current()])

<x-frontend.seo :pageConfig="$pageConfig" />

@section('content')
    <x-frontend.page-header :breadcrumbs="$breadcrumbs">
        @isset($header->title)
            {!! $header->title !!}
        @else
            Laporan <span>Tahunan</span>
        @endisset
    </x-frontend.page-header>

    <div class="page-history content-page">
        @include('contents.frontend.partials.main.profile.yearly-report.about')
        @include('contents.frontend.partials.common.container-divider')
        @include('contents.frontend.partials.main.profile.yearly-report.index')
        @include('contents.frontend.partials.common.container-divider')
        @include('contents.frontend.partials.main.profile.yearly-report.index')
    </div>
@endsection
