@extends('layouts.frontend.main')

@section('title', 'HIMASISTIFO')

@breadcrumbs(['Himpunan Mahasiswa', route('frontend.campus-life.himpunan.index')])
@breadcrumbs(['HIMASISTIFO', route('frontend.campus-life.himpunan.detail', ['himpunanId' => $himpunanId])])

@section('content')
    <x-frontend.page-header :breadcrumbs="$breadcrumbs" image="theme/frontend/images/background/beasiswa.png">
        HIMASISTIFO
    </x-frontend.page-header>

    <div class="activity-page content-page">
        @include('contents.frontend.partials.main.campus-life.himpunan.detail-about')
        @include('contents.frontend.partials.common.container-divider')
        @include('contents.frontend.partials.main.campus-life.himpunan.detail-list')
        @include('contents.frontend.partials.common.container-divider')
        @include('contents.frontend.partials.main.campus-life.himpunan.contact')
    </div>
@endsection
