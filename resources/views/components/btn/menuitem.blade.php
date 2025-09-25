{{-- author : mwy --}}
@props([
    'link' => '',
    'action' => '',
    'class' => '',
    'icon' => '',
    'title' => '',
])

@if ($action == 'edit')
    <div class="menu-item px-3">
        <a href="{{ $link != '' ? $link : '#' }}" class="menu-link align-items-start px-3 text-gray-700 act-{{ str_replace(' ', '_', $action) }} {{ $class }}" {{ $attributes }} title="{{ $title != '' ? $title : 'edit data' }}">
            <i class="{{ $icon != '' ? $icon : 'ki-outline ki-notepad-edit' }} fs-3 me-2"></i>

            {{ $title != '' ? $title : 'Edit data' }}
        </a>
    </div>
@elseif ($action == 'delete')
    <div class="menu-item px-3">
        <a href="{{ $link != '' ? $link : '#' }}" class="menu-link align-items-start px-3 text-gray-700 act-{{ str_replace(' ', '_', $action) }} {{ $class }}" {{ $attributes }} title="{{ $title != '' ? $title : 'hapus data' }}">
            <i class="{{ $icon != '' ? $icon : 'ki-outline ki-trash' }} fs-3 me-2"></i>

            {{ $title != '' ? $title : 'Hapus data' }}
        </a>
    </div>
@elseif ($action == 'detail')
    <div class="menu-item px-3">
        <a href="{{ $link != '' ? $link : '#' }}" class="menu-link align-items-start px-3 text-gray-700 act-{{ str_replace(' ', '_', $action) }} {{ $class }}" {{ $attributes }} title="{{ $title != '' ? $title : 'detail data' }}">
            <i class="{{ $icon != '' ? $icon : 'ki-outline ki-magnifier' }} fs-3 me-2"></i>

            {{ $title != '' ? $title : 'Detail data' }}
        </a>
    </div>
@else
    <div class="menu-item px-3">
        <a href="{{ $link != '' ? $link : '#' }}" class="menu-link align-items-start px-3 text-gray-700 act-{{ str_replace(' ', '_', $action) }} {{ $class }}" {{ $attributes }} title="{{ $title != '' ? $title : 'lainnya' }}">
            <i class="{{ $icon != '' ? $icon : 'ki-outline ki-abstract-14' }} fs-3 me-2"></i>

            {{ $title != '' ? $title : 'Lainnya' }}
        </a>
    </div>
@endif
