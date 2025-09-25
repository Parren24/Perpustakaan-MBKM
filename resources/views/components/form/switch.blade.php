{{-- author : mwy --}}
@props([
    'label' => '',
    'value' => 1,
    'name' => '',
    'class' => '',
    'checked' => null,
])

<div class="form-check form-switch mb-2" {{ $attributes }}>
    <input class="form-check-input" type="checkbox" role="switch" class="{{ $class }}" name="{{ $name }}" value="{{ $value }}" id="{{ $name . '-' . $value }}" {{ $value == $checked ? 'checked' : '' }} />
    <label class="form-check-label text-gray-800" for="{{ $name . '-' . $value }}">{{ $label }}</label>
</div>
