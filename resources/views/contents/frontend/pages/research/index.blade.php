@extends('layouts.frontend.main')

@section('title', 'Riset Terapan')

@breadcrumbs(['Riset Terapan', route('frontend.research.index')])

@section('content')
    <x-frontend.page-header :breadcrumbs="$breadcrumbs">
        Riset <span>Terapan</span>
    </x-frontend.page-header>

    <div class="research-page content-page">
        @include('contents.frontend.partials.main.research.statistic')
        @include('contents.frontend.partials.common.container-divider')
        @include('contents.frontend.partials.main.research.student-research')
        @include('contents.frontend.partials.common.container-divider')
        @include('contents.frontend.partials.main.research.pkm')
        @include('contents.frontend.partials.common.container-divider')
        @include('contents.frontend.partials.main.research.collaboration')
    </div>
@endsection
