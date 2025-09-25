{{-- author : mwy --}}
@props([
    'title' => '',
    'breadCrump' => [],
])

<x-theme.breadcrump :item="$breadCrump"></x-theme.breadcrump>
<div class="app-page-entry d-flex align-items-center">
    <h1 class="page-heading d-flex text-gray-700 fw-bolder fs-2x flex-column justify-content-center my-0">{{ $title }}</h1>
</div>
