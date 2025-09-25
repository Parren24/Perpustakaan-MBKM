{{-- author : mwy --}}
@props([
    'id' => '',
    'btn' => [],
])


@if ($btn && is_array($btn))
    <span class="d-none{{ count($btn) == 1 ? '-' : '' }} d-md-inline">
        @foreach ($btn as $item)
            <x-btn.action action="{{ isset($item['action']) && $item['action'] != '' ? $item['action'] : '' }}"
                          link="{{ isset($item['link']) && $item['link'] != '' ? $item['link'] : '' }}"
                          icon="{{ isset($item['icon']) && $item['icon'] != '' ? $item['icon'] : '' }}"
                          title="{{ isset($item['title']) && $item['title'] != '' ? $item['title'] : '' }}"
                          data-id="{{ $id }}" {{ $attributes->merge(isset($item['attr']) && $item['attr'] ? $item['attr'] : []) }} />
        @endforeach
    </span>
    @if (count($btn) > 1)
        <span class="d-inline d-md-none ms-2">
            <x-btn.action link="#" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start" />
            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 py-4 min-w-150px mw-300px w-auto" data-kt-menu="true">
                @foreach ($btn as $item)
                    <x-btn.menuitem action="{{ isset($item['action']) && $item['action'] != '' ? $item['action'] : '' }}"
                                    link="{{ isset($item['link']) && $item['link'] != '' ? $item['link'] : '' }}"
                                    icon="{{ isset($item['icon']) && $item['icon'] != '' ? $item['icon'] : '' }}"
                                    title="{{ isset($item['title']) && $item['title'] != '' ? $item['title'] : '' }}"
                                    data-id="{{ $id }}" {{ $attributes->merge(isset($item['attr']) && $item['attr'] ? $item['attr'] : []) }} />
                @endforeach
            </div>
        </span>
    @endif
@endif
