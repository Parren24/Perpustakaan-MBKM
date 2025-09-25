{{-- author : mwy --}}
@props([
    'title' => '',
    'tools' => '',
    'body' => '',
    'class' => 'mb-4 mb-md-8',
    'height' => '',
])

<div class="card {{ 'card-stretch' . ($height != '' ? '-' . $height : '') }} {{ $class }}" {{ $attributes }}>
    @if ($title != '' || $tools != '')
        <div class="card-header p-4 py-0 p-md-6 py-md-4">
            <h3 class="card-title fw-bold text-gray-700">{{ $title }}</h3>
            <div class="card-toolbar">
                {{ $tools }}
            </div>
        </div>
    @endif
    <div class="card-body p-4 p-md-6">
        {{ $slot != '' ? $slot : $body }}
    </div>
</div>
