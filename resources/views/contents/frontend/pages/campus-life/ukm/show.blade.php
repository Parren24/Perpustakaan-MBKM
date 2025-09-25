@extends('layouts.frontend.main')

@section('title', 'Unit Kegiatan Mahasiswa')

@breadcrumbs(['Unit Kegiatan Mahasiswa', route('frontend.campus-life.ukm.index')])
@breadcrumbs(['Basket', route('frontend.campus-life.ukm.detail', ['ukmId' => $ukmId])])

@section('content')
    <x-frontend.page-header :breadcrumbs="$breadcrumbs" image="theme/frontend/images/background/beasiswa.png">
        Basket
    </x-frontend.page-header>

    <div class="ukm-page content-page">
        @include('contents.frontend.partials.main.campus-life.ukm.detail-about')
        @include('contents.frontend.partials.common.container-divider')
        @include('contents.frontend.partials.main.campus-life.ukm.detail-list')
        @include('contents.frontend.partials.common.container-divider')
        @include('contents.frontend.partials.main.campus-life.ukm.contact')
    </div>

@endsection
