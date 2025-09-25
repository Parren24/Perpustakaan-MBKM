@extends('layouts.frontend.main')

@section('title', 'FAQ')

@breadcrumbs(['FAQ', route('frontend.information.faq')])

@section('content')
    <x-frontend.page-header :breadcrumbs="$breadcrumbs">
        FAQ
    </x-frontend.page-header>

    <div class="faq-page content-page">
        @include('contents.frontend.partials.main.information.faq.index')
    </div>
@endsection
