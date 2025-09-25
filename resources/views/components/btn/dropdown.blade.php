@props([
    'title' => '',
    'class' => '',
    'type' => 'primary',
    'attr' => '',
    'items' => [],
])

<span class="d-inline ms-2">
    <a href="#" class="menu-link btn btn-sm h-25px pt-1 align-center btn-{{ $type }} {{ $class }}"
       data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start"
       {{ $attributes->merge(isset($item['attr']) && $item['attr'] ? $item['attr'] : []) }}
       title="{{ $title != '' ? $title : 'Lainnya' }}">
        {!! $title != '' ? $title : $slot !!}
    </a>
    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 py-4 min-w-150px mw-300px w-auto" data-kt-menu="true">
        @foreach ($items as $item)
            <x-btn.menuitem action="{{ isset($item['action']) && $item['action'] != '' ? $item['action'] : '' }}"
                            link="{{ isset($item['link']) && $item['link'] != '' ? $item['link'] : '' }}"
                            icon="{{ isset($item['icon']) && $item['icon'] != '' ? $item['icon'] : '' }}"
                            title="{{ isset($item['title']) && $item['title'] != '' ? $item['title'] : '' }}"
                            {{ $attributes->merge(isset($item['attr']) && $item['attr'] ? $item['attr'] : []) }} />
        @endforeach
    </div>
</span>
