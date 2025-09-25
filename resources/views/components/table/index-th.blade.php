{{-- author : mwy --}}
@props([
    'title' => '',
    'class' => '',
])

<th class="py-2 bg-light text-nowrap rounded-top-2 {{ $class }}">{{ $slot != '' ? $slot : $title }}</th>
