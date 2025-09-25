@extends('layouts.frontend.main')

@section('title', "Jurusan $jurusanTrimmed")

@section('content')
    <x-frontend.page-header :breadcrumbs="$breadcrumbs" :image="$kontenJurusan->tentang->cover">
        Jurusan <span>{{ $jurusanTrimmed }}</span>
    </x-frontend.page-header>

    <div class="jurusan-detail-page content-page">
        @include('contents.frontend.partials.main.academic.jurusan.sambutan')
        @include('contents.frontend.partials.common.container-divider')
        @include('contents.frontend.partials.main.academic.jurusan.about-jurusan')
        @include('contents.frontend.partials.common.container-divider')
        @include('contents.frontend.partials.main.academic.jurusan.lecturers')
        @include('contents.frontend.partials.common.container-divider')
        @include('contents.frontend.partials.main.academic.jurusan.detail')
        {{-- @include('contents.frontend.partials.common.container-divider') --}}
        {{-- @include('contents.frontend.partials.main.academic.jurusan.agenda') --}}
        {{-- @include('contents.frontend.partials.common.container-divider') --}}
        {{-- @include('contents.frontend.partials.main.articles.newest') --}}
    </div>
@endsection
