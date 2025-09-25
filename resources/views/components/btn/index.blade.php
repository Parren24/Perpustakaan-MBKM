{{-- author : mwy --}}
@props([
    'link' => '',
    'type' => 'default',
    'class' => '',
    'text' => '',
    'size' => 'sm',
])

@php
    if ($size == 'xs') {
        $size = 'sm px-2 py-1';
    }
@endphp

<a href="{{ $link != '' ? $link : 'javascript:;' }}" class="btn text-nowrap btn-{{ $size }} btn-{{ $type }} {{ $class }}" {{ $attributes }}>
    {{ $slot != '' ? $slot : $text }}
</a>
