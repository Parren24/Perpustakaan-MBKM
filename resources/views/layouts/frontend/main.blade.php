@extends('layouts.frontend.base')

@push('head')
    @include('contents.frontend.partials.common.head-data')
@endpush

@section('base-content')
    {{-- @include('contents.frontend.partials.common.preloader') --}}

    @hasSection('header')
        @yield('header')
    @else
        @include('contents.frontend.partials.common.header')
    @endif

    <main>
        @yield('content')
    </main>

    @push('head')
        <style>
            :root {
                --primary-main-rgb: var(--primary-rgb-950) !important;
                --primary-main: var(--primary-950) !important;
                --secondary-main: var(--secondary-600) !important;
            }
        </style>
    @endpush

    {{-- @include('contents.frontend.partials.common.prototype-btns', [
        'buttons' => env('APP_ENV') !== 'production',
    ]) --}}

    @include('contents.frontend.partials.common.footer')
    @include('contents.frontend.partials.common.scroll-to-top')
    @include('contents.frontend.partials.common.script-data')
@endsection
