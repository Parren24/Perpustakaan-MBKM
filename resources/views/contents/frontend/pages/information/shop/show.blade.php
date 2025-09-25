@extends('layouts.frontend.main')

@section('title', 'Shop Detail')

@breadcrumbs(['Shop', route('frontend.information.shop.index')])
@breadcrumbs(['Detail', route('frontend.information.shop.show', ['id' => $id])])

@section('content')
    <x-frontend.page-header :breadcrumbs="$breadcrumbs">
        Detail Shop
    </x-frontend.page-header>

    <div class="shop-detail-page content-page">
        @include('contents.frontend.partials.main.information.shop.detail')
    </div>
@endsection
