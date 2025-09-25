@extends('layouts.frontend.main')

@section('title', 'Shop')

@breadcrumbs(['Shop', route('frontend.information.shop.index')])

@section('content')
    <x-frontend.page-header :breadcrumbs="$breadcrumbs">
        Shop
    </x-frontend.page-header>

    <div class="shop-page content-page">
        @include('contents.frontend.partials.main.information.shop.slider')
        @include('contents.frontend.partials.main.information.shop.featured')
    </div>
@endsection
