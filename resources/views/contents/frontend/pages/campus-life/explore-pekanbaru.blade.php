@extends('layouts.frontend.main')

@section('title', 'Jelajahi Pekanbaru')

@breadcrumbs(['Jelajahi Pekanbaru', route('frontend.campus-life.explore-pekanbaru')])

@section('content')
    <x-frontend.page-header :breadcrumbs="$breadcrumbs">
        Jelajahi <span>Pekanbaru</span>
    </x-frontend.page-header>

    <div class="explore-pekanbaru-page content-page">
        @include('contents.frontend.partials.main.campus-life.explore-pekanbaru.about')
        @include('contents.frontend.partials.main.campus-life.explore-pekanbaru.tourism')
        @include('contents.frontend.partials.main.campus-life.explore-pekanbaru.melayu-culture')
        @include('contents.frontend.partials.common.container-divider')
        @include('contents.frontend.partials.main.campus-life.explore-pekanbaru.history')
        @include('contents.frontend.partials.main.campus-life.explore-pekanbaru.food')
        @include('contents.frontend.partials.main.campus-life.explore-pekanbaru.strategic')
    </div>
@endsection
