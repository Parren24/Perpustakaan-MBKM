{{-- author : mwy --}}
@props([
    'label' => '',
    'type' => 'text',
    'name' => '',
    'icon_start' => '',
    'icon_end' => '',
    'start' => '',
    'end' => '',
    'class' => '',
    'required' => '',
    'placeholder' => '',
    'size' => 'sm',
])
@if ($label != '')
    <label class="form-label">
        {{ $label }}
        @if ($required)
            <span class="text-danger">*</span>
        @endif
    </label>
@endif
<div class="input-group mb-2 mb-md-0">
    @if ($icon_start != '' || $start != '')
        <span class="input-group-text">
            @if ($icon_start != '')
                <i class="{{ $icon_start }}"></i>
            @else
                {!! $start !!}
            @endif
        </span>
    @endif
    <input type="{{ $type }}" class="form-control form-control-{{ $size }} {{ $class }}" placeholder="{{ $label == '' && $placeholder != '' ? $placeholder : '. . . . . . . . . .' }}" name="{{ $name }}" {{ $required ? 'required' : '' }} {{ $attributes }} />
    @if ($icon_end != '' || $end != '')
        <span class="input-group-text">
            @if ($icon_end != '')
                <i class="{{ $icon_end }}"></i>
            @else
                {!! $end !!}
            @endif
        </span>
    @endif
    {{ $slot }}
</div>
