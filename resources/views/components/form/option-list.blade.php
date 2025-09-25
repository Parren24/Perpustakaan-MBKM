{{-- author : mwy --}}
@props([
    'label' => '',
    'type' => 'checkbox', // row, column
    'flex' => 'row', // row, column
    'name' => '',
    'class' => 'mb-2 mb-md-0',
    'required' => '',
    'disabled' => false,
    'size' => 'sm',
    'option' => null,
    'checked' => null,
])

@if ($label != '')
    <label class="form-label">
        {{ $label }}
        @if ($required)
            <span class="text-danger">*</span>
        @endif
    </label>
@endif

<div class="input-group {{ $class }}">
    <div class="d-flex flex-{{ $flex }} {{ $size != 'xs' ? 'gap-3' : '' }}">
        @foreach ($option as $key => $item)
            <div class="form-check form-check-custom form-check-solid form-check-{{ $size }}">
                <input class="form-check-input" type="{{ $type }}" id="radio-{{ str_replace(' ', '-', $key) }}-{{ $name }}" value="{{ $key }}" name="{{ $name }}" {{ $required ? 'required' : '' }} {{ $disabled ? 'disabled' : '' }} {{ $attributes }} {{ $key == $checked ? 'checked' : '' }}
                       {!! $size == 'xs' ? 'style="height: 1.15rem;width: 1.15rem;"' : '' !!} />
                <label class="form-check-label {{ $size == 'xs' ? 'fs-8' : 'fs-6' }}" for="radio-{{ str_replace(' ', '-', $key) }}-{{ $name }}">
                    {{ $item }}
                </label>
            </div>
        @endforeach
    </div>
</div>
