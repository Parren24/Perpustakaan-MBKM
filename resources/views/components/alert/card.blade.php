{{-- author : mwy --}}
@props([
    'title' => '',
    'text' => '',
    'type' => 'info',
    'class' => 'mb-4 mb-md-8',
    'align' => 'center', // start, center, end
    'dismiss' => true,
])
@php
    $icon = [
        'info' => 'info-circle',
        'success' => 'check-circle',
        'warning' => 'exclamation-circle',
        'danger' => 'x-circle',
    ];
@endphp

<div class="alert alert-dismissible bg-light-{{ $type }} d-flex flex-{{ $align }} flex-column p-4 p-md-10 {{ $class }}" {{ $attributes }}>
    @if ($dismiss)
        <button type="button" class="position-absolute top-0 end-0 m-2 btn btn-icon btn-icon-{{ $type }}" data-bs-dismiss="alert">
            <i class="bi bi-x-lg fs-1"><span class="path1"></span><span class="path2"></span></i>
        </button>
    @endif

    <i class="bi bi-{{ $icon[$type] }} fs-5tx text-{{ $type }} mb-5"></i>

    <div class="text-{{ $align }}">
        <h1 class="fw-bold mb-5">{{ $title }}</h1>

        <div class="separator separator-dashed border-{{ $type }} opacity-25 mb-5"></div>

        {{ $slot != '' ? $slot : $text }}
    </div>
</div>
