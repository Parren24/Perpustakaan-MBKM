{{-- author : mwy --}}
@props([
    'text' => '',
    'icon' => '',
    'active' => false,
    'link' => '',
])

<div {!! $slot != '' ? 'data-kt-menu-trigger="click"' : '' !!} class="menu-item {{ $active ? 'here show' : '' }} {{ $slot != '' ? 'menu-sub-indention menu-accordion' : '' }}">
    <a href="{{ $link ? $link : '#' }}" class="menu-link {{ $active && $slot == '' ? 'active' : '' }}">
        @if ($icon)
            <span class="menu-icon">
                <i class="{{ $icon }} fs-2"></i>
            </span>
        @else
            <span class="menu-bullet">
                <span class="bullet bullet-dot"></span>
            </span>
        @endif
        <span class="menu-title fs-6">{{ $text }}</span>
        @if ($slot != '')
            <span class="menu-arrow"></span>
        @endif
    </a>
    @if ($slot != '')
        <div class="menu-sub menu-sub-accordion menu-active-bg">
            {!! $slot !!}
        </div>
    @endif
</div>
