{{-- author : mwy --}}
@props([
    'link' => '',
    'type' => 'default',
    'class' => 'px-4',
])

<a href="{{ $link != '' ? $link : 'javascript:;' }}" class="btn btn-flex btn-{{ $type }} {{ $class }}" {{ $attributes }}>
    {{ $slot }}
</a>
