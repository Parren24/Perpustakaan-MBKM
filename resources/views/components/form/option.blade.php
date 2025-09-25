{{-- author : mwy --}}
@props([
    'label' => '',
    'type' => 'checkbox', // row, column
    'name' => '',
    'value' => '',
    'class' => '',
    'required' => '',
    'disabled' => false,
    'size' => 'sm',
    'checked' => null,
])

<div class="form-check form-check-custom form-check-solid form-check-sm">
    <input class="form-check-input {{ $class }}" type="{{ $type }}" value="{{ $value }}" id="opt-{{ str_replace(' ', '-', $name) }}-{{ str_replace(' ', '-', $value) }}" name="{{ $name }}" {{ $required ? 'required' : '' }} {{ $disabled ? 'disabled' : '' }} {{ $attributes }} {{ $value == $checked ? 'checked' : '' }} />
    <label class="form-check-label" for="opt-{{ str_replace(' ', '-', $name) }}-{{ str_replace(' ', '-', $value) }}">
        {{ $label }}
    </label>
</div>
