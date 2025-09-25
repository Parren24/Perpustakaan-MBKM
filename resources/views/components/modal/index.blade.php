{{-- author : mwy --}}
@props([
    'id' => 'modal',
    'title' => '',
    'body' => '',
    'action' => '',
    'type' => 'top',
    'size' => '', // xl/lg/sm
    'static' => false,
    'class' => '',
    'header' => true,
])

<div class="modal fade zoom" id="{{ $id }}" {!! $static ? 'data-bs-backdrop="static"' : '' !!} {{ $attributes }} tabindex="-1" aria-labelledby="{{ $id }}Label" aria-hidden="true">
    <div class="modal-dialog {{ $type == 'centered' ? 'modal-dialog-centered' : '' }} modal-{{ $size }}">
        <div class="modal-content">
            @if ($header)
                <div class="modal-header py-5">
                    <h5 class="modal-title" id="{{ $id }}-title">{{ $title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            @endif
            <div class="modal-body {{ $class }}">
                {{ $slot != '' ? $slot : '' }}
            </div>
            @if ($action != '')
                <div class="modal-footer p-2">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                    {!! $action === true ? '' : $action !!}
                </div>
            @endif
        </div>
    </div>
</div>
