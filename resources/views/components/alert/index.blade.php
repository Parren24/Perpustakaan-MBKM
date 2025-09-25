{{-- author : mwy --}}
@props([
    'type' => 'info',
    'text' => '',
    'class' => 'p-2 px-3 p-md-4 mb-2 mb-md-4',
    'align' => 'center', // start, center, end
])

<div class="alert alert-{{ $type }} d-flex align-items-center border-0 {{ $class }}" {{ $attributes }}>
    <div class="d-flex flex-column">
        <span>
            {{ $slot != '' ? $slot : $text }}
        </span>
    </div>
</div>
