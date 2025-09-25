@extends('layouts.frontend.main')

@section('title', 'Organisasi Mahasiswa')

@breadcrumbs(['Organisasi Mahasiswa', route('frontend.campus-life.student-organization.index')])

@section('content')
    <x-frontend.page-header :breadcrumbs="$breadcrumbs" image="theme/frontend/images/background/beasiswa.png">
        <span>Organisasi</span> Mahasiswa
    </x-frontend.page-header>

    <div class="activity-page content-page">
        @include('contents.frontend.partials.main.campus-life.student-organization.about')
        @include('contents.frontend.partials.common.container-divider')
        @include('contents.frontend.partials.main.campus-life.student-organization.list')
    </div>
@endsection
