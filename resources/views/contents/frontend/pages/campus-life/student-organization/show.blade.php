@extends('layouts.frontend.main')

@section('title', 'Badan Eksekutif Mahasiswa')

@breadcrumbs(['Organisasi Mahasiswa', route('frontend.campus-life.student-organization.index')])
@breadcrumbs(['Badan Eksekutif Mahasiswa', route('frontend.campus-life.student-organization.detail', ['organizationId' => $organizationId])])

@section('content')
    <x-frontend.page-header :breadcrumbs="$breadcrumbs" image="theme/frontend/images/background/beasiswa.png">
        Badan Eksekutif Mahasiswa
    </x-frontend.page-header>

    <div class="activity-page content-page">
        @include('contents.frontend.partials.main.campus-life.student-organization.detail-about')
        @include('contents.frontend.partials.common.container-divider')
        @include('contents.frontend.partials.main.campus-life.student-organization.detail-list')
        @include('contents.frontend.partials.common.container-divider')
        @include('contents.frontend.partials.main.campus-life.student-organization.contact')
    </div>
@endsection
