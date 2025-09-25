{{-- author : mwy --}}
@props([
    'item' => [],
])
<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
    <li class="breadcrumb-item text-muted">
        <a href="{{ url('/') }}" class="text-muted text-hover-primary">Home</a>
    </li>
    @forelse ($item as $key => $list)
        <li class="breadcrumb-item">
            <span class="bullet bg-gray-400 w-5px h-2px"></span>
        </li>
        <a href="{{ $list['link'] }}" class="text-muted text-hover-primary ms-2">{{ $list['title'] }}</a>
    @empty
    @endforelse
</ul>
