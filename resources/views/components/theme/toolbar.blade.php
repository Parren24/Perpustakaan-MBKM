{{-- author : mwy --}}
@props([
    'title' => '',
    'breadCrump' => '',
    'tools' => '',
])
<div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
    <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
        <h1 class="page-heading d-flex flex-column justify-content-center {{ isSnap() ? 'text-gray-600 fw-light fs-4' : 'text-gray-800 fw-bold fs-4' }} m-0">{{ $title }}</h1>
        <x-theme.breadcrump :item="$breadCrump"></x-theme.breadcrump>
    </div>
    <div class="d-flex align-items-center gap-2 gap-lg-3">
        {{ isSnap() ? '' : $tools }}
    </div>
</div>
