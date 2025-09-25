{{-- author : mwy --}}
@props([
    'link' => '',
    'type' => 'default',
    'class' => '',
    'icon' => '',
])

<a href="{{ $link != '' ? $link : 'javascript:;' }}" class="btn btn-icon btn-sm btn-{{ $type }} {{ $class }}" {{ $attributes }}>
    @if ($slot != '')
        {!! $slot !!}
    @else
        <i class="{{ $icon }} fs-2"></i>
    @endif
</a>
