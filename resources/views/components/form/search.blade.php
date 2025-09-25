{{-- author : mwy --}}
@props([
    'class' => 'w-250px',
    'text' => 'Search',
])

<div class="d-flex align-items-center position-relative w-100-">
    <i class="bi bi-search fs-3 position-absolute ms-4"></i>
    <input type="text" data-kt-customer-table-filter="search" class="form-control border border-gray-200 form-control-solid ps-12 {{ $class }}" {{ $attributes }} placeholder="{{ $text }}">
</div>
