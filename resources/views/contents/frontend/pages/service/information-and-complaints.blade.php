@extends('layouts.frontend.main')

@section('title', 'Informasi Publik dan Pengaduan')

@breadcrumbs(['Informasi Publik dan Pengaduan', route('frontend.service.information-and-complaints')])

@section('content')
    <x-frontend.page-header :breadcrumbs="$breadcrumbs" image="theme/frontend/images/background/informasi-publik.png">
        <span>Informasi Publik</span> dan <span>Pengaduan</span>
    </x-frontend.page-header>

    <div class="information-and-complaints-page content-page">
        @include('contents.frontend.partials.main.service.complaint')
    </div>
@endsection
