{{-- author : mwy --}}
@props([
    'target' => 'modal',
    'class' => '',
])

<span data-bs-toggle="modal" data-bs-target="#{{ $target }}">
    {{ $slot }}
</span>
