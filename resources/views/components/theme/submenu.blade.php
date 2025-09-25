{{-- author : mwy --}}
@props([
    'text' => '',
    'active' => false,
    'link' => '',
])

<div class="menu-item">
    <a class="menu-link py-1 me-0 {{ $active ? 'active' : '' }}" href="{{ $link }}" title="{{ $text }}" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
        <span class="menu-bullet">
            <span class="bullet bullet-dot"></span>
        </span>
        <span class="menu-title fs-7">{{ $text }}</span>
    </a>
</div>
