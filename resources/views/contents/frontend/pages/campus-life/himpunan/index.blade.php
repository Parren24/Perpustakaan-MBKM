@extends('layouts.frontend.main')

@section('title', 'Himpunan Mahasiswa')

@breadcrumbs(['Himpunan Mahasiswa', route('frontend.campus-life.himpunan.index')])

@section('content')
    <x-frontend.page-header :breadcrumbs="$breadcrumbs" image="theme/frontend/images/background/beasiswa.png">
        <span>Himpunan</span> Mahasiswa
    </x-frontend.page-header>

    <div class="activity-page content-page">
        @include('contents.frontend.partials.main.campus-life.himpunan.about')
        @include('contents.frontend.partials.common.container-divider')
        @include('contents.frontend.partials.main.campus-life.himpunan.list')
    </div>
@endsection
