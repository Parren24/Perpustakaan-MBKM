{{-- author : mwy --}}
@props([
    'label' => '',
    'type' => 'text',
    'name' => '',
    'icon_start' => '',
    'icon_end' => '',
    'class' => '',
    'required' => '',
    'placeholder' => '',
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
    @if ($icon_start != '')
        <span class="input-group-text">
            <i class="{{ $icon_start }}"></i>
        </span>
    @endif
    <textarea type="{{ $type }}" class="form-control form-control-sm {{ $class }}" placeholder="{{ $label == '' && $placeholder != '' ? $placeholder : '. . . . . . . . . .' }}" name="{{ $name }}" {{ $required ? 'required' : '' }} {{ $attributes }}>{!! $slot !!}</textarea>
    @if ($icon_end != '')
        <span class="input-group-text">
            <i class="{{ $icon_end }}"></i>
        </span>
    @endif
</div>
