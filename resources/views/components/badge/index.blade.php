{{-- author : mwy --}}
@props([
    'type' => 'info',
    'text' => '',
    'class' => '',
    'align' => 'center', // start, center, end
])

<span class="badge badge-light-{{ $type }} {{ $class }}" {{ $attributes }}>{{ $slot != '' ? $slot : $text }}</span>
