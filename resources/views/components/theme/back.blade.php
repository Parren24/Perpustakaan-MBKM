@props(['link' => ''])
<x-btn :link="$link" type="secondary" class="{{ $link == '' ? 'act-back' : '' }}">
    <i class="bi bi-arrow-left fs-3 me-2"></i> Back
</x-btn>

@push('scripts')
    @if ($link == '')
        <script>
            $(document).on('click', '.act-back', () => {
                window.history.back();
            })
        </script>
    @endif
@endpush
