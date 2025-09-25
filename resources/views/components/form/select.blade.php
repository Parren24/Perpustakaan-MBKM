{{-- author : mwy --}}
@props([
    'label' => '',
    'search' => false,
    'name' => '',
    'icon_start' => '',
    'icon_end' => '',
    'class' => '',
    'required' => '',
    'placeholder' => '',
    'multiple' => false,
    'size' => 'sm',
    'clear' => true,
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
    <select class="form-select form-select-{{ $size }} {{ $class }}" data-placeholder="{{ $placeholder != '' ? $placeholder : '. . . . . . . . . .' }}" name="{{ $name }}" data-control="select2" data-allow-clear="{{ $clear }}" data-size="5" data-hide-search="{{ $search ? 'false' : 'true' }}" {{ $multiple ? 'multiple' : '' }} {{ $required ? 'required' : '' }} {{ $attributes }}>
        @if (!$multiple)
            <option value=""></option>
        @endif
        {{ $slot }}
    </select>
    @if ($icon_end != '')
        <span class="input-group-text">
            <i class="{{ $icon_end }}"></i>
        </span>
    @endif
</div>
