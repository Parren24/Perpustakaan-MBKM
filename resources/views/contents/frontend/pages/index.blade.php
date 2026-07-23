@extends('layouts.frontend.main')

<x-frontend.seo :pageConfig="$pageConfig" />
@section('header')
{{-- sengaja dikosongkan, supaya navbar tidak muncul di halaman ini --}}
@endsection

@section('content')
    <!-- @include('contents.frontend.partials.main.landing.hero', ['heroData' => $heroData]) -->

     <div class="d-flex align-items-center justify-content-center" style="min-height: 80vh;">
        <div class="text-center" style="max-width: 500px;">
            <h2 class="fw-bold mb-2">Selamat Datang</h2>
            <p class="text-muted mb-5">Silakan pilih jenis akses yang sesuai</p>

            <div class="d-flex flex-column gap-3">
                <a href="{{ route('app.user.show', ['param1' => 'token']) }}"
                class="btn btn-primary btn-lg py-3 rounded-4 shadow-sm">
                    <i class="fas fa-user me-2"></i> Sebagai Peminjam
                </a>

                <a href="{{ route('frontend.biblio.index') }}"
                class="btn btn-outline-primary btn-lg py-3 rounded-4 shadow-sm">
                    <i class="fas fa-desktop me-2"></i> Kios Perpustakaan
                </a>
            </div>
        </div>
    </div>

@endsection
