{{-- author : mwy --}}
@props([
    'label' => '',
    'value' => 1,
    'name' => '',
    'class' => '',
    'bg' => 'primary',
    'checked' => null,
])

{{--
    component ini hanya dapat berjalan dengan parent yang memiliki attribute
    data-kt-buttons="true"
--}}

<label class="radio-card btn btn-outline d-flex flex-stack- text-start p-3 mb-2 border-active-{{ $bg }} border-hover-{{ $bg }} btn-active-light-{{ $bg }} {{ $class }} {{ $value == $checked ? 'active' : '' }}">
    <div class="d-flex align-items-start me-2">
        <div class="form-check form-check-custom form-check-solid form-check-{{ $bg }} mt-{{ $slot != '' ? '1' : '' }} me-3">
            <input class="form-check-input" type="radio" name="{{ $name }}" value="{{ $value }}" {{ $attributes }} {{ $value == $checked ? 'checked' : '' }} />
        </div>

        <div class="flex-grow-1">
            {{ $slot != '' ? $slot : $label }}
        </div>
    </div>
</label>
