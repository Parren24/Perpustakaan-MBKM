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
        <div class="card-header bg-light p-3 min-h-1px">
            <h3 class="card-title m-0 text-gray-600">{{ $title }}</h3>
            <div class="card-toolbar m-0 gap-2">
                {{ $tools }}
            </div>
        </div>
    @endif
    <div class="card-body p-3">
        {{ $slot != '' ? $slot : $body }}
    </div>
</div>
