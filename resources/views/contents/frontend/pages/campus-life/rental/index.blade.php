@extends('layouts.frontend.main')

@section('title', 'Kost dan Sewa Rumah')

@breadcrumbs(['Kost dan Sewa Rumah', route('frontend.campus-life.rental.index')])

@section('content')
    <x-frontend.page-header :breadcrumbs="$breadcrumbs">
        <span>Kost</span> dan <span>Sewa Rumah</span>
    </x-frontend.page-header>

    <div class="rental-page content-page">
        @include('contents.frontend.partials.main.campus-life.rental.about')
        @include('contents.frontend.partials.common.container-divider')
        @include('contents.frontend.partials.main.campus-life.rental.listing')
    </div>
@endsection
