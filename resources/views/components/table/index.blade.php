{{-- author : mwy --}}
@props([
    'header' => '',
    'id' => 'tableData',
])

<table class="table bg-light- table-rounded table-striped- table-row-bordered border border-gray-300 rounded-2 fs-7 gy-2 gs-5" id="{{ $id }}">
    <thead class="bg-light text-uppercase fs-7 fw-bold text-gray-500">
        <tr>
            {!! $header != '' ? $header : '' !!}
        </tr>
    </thead>
    <tbody>
        {!! $slot !!}
    </tbody>
</table>
