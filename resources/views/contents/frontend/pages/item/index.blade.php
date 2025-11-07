@extends('layouts.frontend.main')

@breadcrumbs([data_get($pageConfig, 'seo.title'), url()->current()])

<x-frontend.seo :pageConfig="$pageConfig" />

@push('styles')
<style>
    .scanner-controls {
        margin-top: 1rem;
    }
    
    #reader {
        position: relative;
        border: 2px dashed #ddd !important;
        border-radius: 10px !important;
        padding: 20px !important;
    }
</style>
@endpush

@section('content')
    <x-frontend.page-header :breadcrumbs="$breadcrumbs" :image="data_get($pageConfig, 'background_image')">
        {{ data_get($content, 'header') }}
    </x-frontend.page-header>

    <div class="peminjaman-page content-page">
        @include('contents.frontend.partials.main.item.peminjaman', ['content' => $content])
    </div>
@endsection