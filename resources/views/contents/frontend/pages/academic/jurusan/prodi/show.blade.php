@extends('layouts.frontend.main')

@section('title', 'Program Studi ' . $prodi->nama_prodi)

@breadcrumbs([$prodi->jurusan->nama_jurusan, route('frontend.academic.jurusan.show', ['jurusanAlias' => Str::lower($prodi->jurusan->alias_jurusan)])])
@breadcrumbs(['Program Studi ' . $prodi->nama_prodi, route('frontend.academic.prodi.show', ['jurusanAlias' => Str::lower($prodi->jurusan->alias_jurusan), 'prodiAlias' => Str::lower($prodi->alias_prodi)])])

@section('content')
    <x-frontend.page-header :breadcrumbs="$breadcrumbs" :image="$kontenProdi->tentang->filemedia">
        Program Studi <span>{{ $prodi->nama_prodi }}</span>

        {{-- <x-slot:extra_content>
            <a href="{{ route('frontend.academic.prodi.home', ['prodiAlias' => Str::lower($kontenProdi->alias_prodi)]) }}"
                class="btn btn-default btn-highlighted">
                <span>Lihat Halaman</span>
            </a>
        </x-slot:extra_content> --}}
    </x-frontend.page-header>

    <div class="page-service-single prodi-detail-page" id="identity-and-guidelines">
        @include('contents.frontend.partials.main.academic.jurusan.prodi.index')
    </div>
@endsection
