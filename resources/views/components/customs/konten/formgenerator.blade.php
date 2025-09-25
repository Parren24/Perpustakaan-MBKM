{{-- author : dzb --}}
@props([
    'section_name' => '',
    'section_type' => '',
    'data' => [],
])

@foreach ($data as $index => $value)
    @foreach ($value as $i => $item)
        @if ($index == 'title')
            <x-form.input name="{{ $index }}_{{ strtolower($section_name) }}[]" label="{{ ucwords($index) }}"
                value="{{ $item }}" required />
        @elseif($index == 'media_id')
            <div class="my-4">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <input type="file" class=" media_cropper d-none"
                            id="{{ $i }}_{{ $index }}_{{ strtolower($section_name) }}_media"
                            name="" data-width="{{ $section_type == 'infografis' ? '500' : '523' }}"
                            data-height="{{ $section_type == 'infografis' ? '400' : '715' }}"
                            data-preview="{{ $i }}_{{ $index }}_{{ strtolower($section_name) }}_preview"
                            data-hidden_container="{{ $i }}_{{ $index }}_{{ strtolower($section_name) }}_container"
                            data-append="0" data-name="{{ strtolower($section_name) }}[]" />
                        <x-btn type="secondary" class="btn-upload mb-1"
                            data-id="{{ $i }}_{{ $index }}_{{ strtolower($section_name) }}_media">
                            <i class="bi bi-upload"></i>
                            Upload
                            media
                        </x-btn>
                        <div class="fs-9">Media dapat
                            berupa
                            video
                            atau gambar</div>
                    </div>
                    <div class="w-100px">
                        <small>Preview</small>
                        <div id="{{ $i }}_{{ $index }}_{{ strtolower($section_name) }}_preview">
                            <small class="text-muted">No
                                Preview</small>
                        </div>
                        <div id="{{ $i }}_{{ $index }}_{{ strtolower($section_name) }}_container">
                        </div>
                    </div>
                </div>
            </div>
            @if ($value != '')
                <script>
                    window.addEventListener('DOMContentLoaded', function() {
                        add_to_preview('{{ $value[0] }}',
                            document.getElementById(
                                '{{ $i }}_media_{{ strtolower($section_name) }}_media').value,
                            '{{ $i }}_{{ $index }}_{{ strtolower($section_name) }}_preview',
                            '{{ $i }}_{{ $index }}_{{ strtolower($section_name) }}_container',
                            '{{ strtolower($section_name) }}[]',
                            false);
                    });
                </script>
            @endif
        @elseif($index == 'media' && $value != '')
            <x-form.input type="hidden"
                id="{{ $i }}_{{ $index }}_{{ strtolower($section_name) }}_media"
                value="{{ $value[0] }}" />
        @else
            <x-form.textarea name="{{ $index }}_{{ strtolower($section_name) }}[]" class="editor"
                label="{{ ucwords($index) }}" rows="7" required>{{ $item }}</x-form.textarea>
        @endif
    @endforeach
@endforeach
