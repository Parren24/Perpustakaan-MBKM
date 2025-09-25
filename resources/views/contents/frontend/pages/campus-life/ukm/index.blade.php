@extends('layouts.frontend.main')

@section('title', 'Unit Kegiatan Mahasiswa')

@breadcrumbs(['Unit Kegiatan Mahasiswa', route('frontend.campus-life.ukm.index')])

@section('content')
    <x-frontend.page-header :breadcrumbs="$breadcrumbs" image="theme/frontend/images/background/beasiswa.png">
        <span>Unit Kegiatan</span> Mahasiswa
    </x-frontend.page-header>

    <div class="activity-page content-page">
        @include('contents.frontend.partials.main.campus-life.ukm.about')
        <div class="container">
            <div class="divider-dark-lg"></div>
        </div>
        @include('contents.frontend.partials.main.campus-life.ukm.list')
    </div>

@endsection
