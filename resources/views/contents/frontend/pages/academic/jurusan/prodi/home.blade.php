@extends('layouts.frontend.main')

@section('title', "Program Studi $prodi->nama_prodi")

@section('header')
    @include('contents.frontend.partials.main.header', [
        'menu' => [
            [
                'name' => 'Beranda',
                'route' => route('frontend.academic.prodi.show', ['prodiAlias' => Str::lower($prodi->alias)]),
            ],
        ],
    ])

@endsection

@section('content')
    <x-frontend.hero>
        <x-slot:subtitle>
            Selamat datang di Program Studi <br class="d-none d-md-block"> {{ $prodi->nama_prodi }}
        </x-slot:subtitle>
        <x-slot:titles>
            <h1 class="split" data-title-index="0">
                <div class="line">Lorem Ipsum <span>Dolor</span></div>
                <div class="line">Si Amet</div>
            </h1>
        </x-slot:titles>
    </x-frontend.hero>

    <div class="prodi-landing-page content-page">
        @include('contents.frontend.partials.main.academic.jurusan.prodi.home.sambutan')
        @include('contents.frontend.partials.common.container-divider')
        @include('contents.frontend.partials.main.landing.fakta-statistik', ['noPadding' => true])
        @include('contents.frontend.partials.common.container-divider')
        @include('contents.frontend.partials.main.landing.sdg', ['noPadding' => true])
        @include('contents.frontend.partials.common.rekan-kerjasama', [
            'renderTitle' => false,
        ])
    </div>
@endsection
