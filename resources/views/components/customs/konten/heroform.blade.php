{{-- author : dzb --}}
@props([
    'section_name' => '',
    'section_type' => '',
    'data' => [],
])

<div class="sortable">
    @php $flagCards=0; @endphp
    @foreach ($data as $cards)
        <div class="d-flex flex-row mb-4 bg-theme rounded" id="{{ $flagCards }}_{{ strtolower($section_name) }}_card">
            <input type="hidden" name="{{ strtolower($section_name) }}_key[]" value="{{ $flagCards }}" />
            <div class="d-flex align-items-center justify-content-center" style="width: 60px; text-align: center;">
                @php
                    $dataAction = [];
                    $dataAction[] = [
                        'action' => 'delete',
                        'attr' => [
                            'data-id' => '',
                        ],
                    ];
                @endphp
                <x-btn.actiontable :id="1" :btn="$dataAction" />
            </div>
            <div class="flex-grow-1 py-4 px-4">
                @foreach ($cards as $index => $value)
                    @if ($index == 'title')
                        <label class="form-label">
                            Title
                            <span class="text-danger">*</span>
                        </label>
                    @endif
                    <div id="{{ $index }}">
                        <div id="{{ $flagCards }}_{{ $index }}_div">
                            @foreach ($value as $i => $item)
                                @if ($index == 'title')
                                    @php $rand = rand(0, max: 1000);@endphp
                                    <div class="d-flex align-items-center mb-3 gap-2"
                                        id="{{ $index }}_{{ strtolower($section_name) }}_{{ $flagCards }}_{{ $rand }}">
                                        <x-form.input
                                            name="{{ $index }}_{{ strtolower($section_name) }}_{{ $flagCards }}[]"
                                            value="{{ $item }}" required />
                                        @php
                                            $arrayAction = [];
                                            if ($i == 0) {
                                                $arrayAction[] = [
                                                    'action' => 'add',
                                                    'icon' => 'bi bi-plus text-primary',
                                                    'attr' => [
                                                        'data-key' => $flagCards,
                                                        'data-to' => $flagCards . '_' . $index . '_div',
                                                        'data-type' => 'tag',
                                                    ],
                                                ];
                                            } else {
                                                $arrayAction[] = [
                                                    'action' => 'delete',
                                                    'attr' => [
                                                        'data-id' =>
                                                            $index .
                                                            '_' .
                                                            strtolower($section_name) .
                                                            '_' .
                                                            $flagCards .
                                                            '_' .
                                                            $rand,
                                                    ],
                                                ];
                                            }
                                        @endphp
                                        <div class="d-flex align-items-center">
                                            <x-btn.actiontable :id="''" :btn="$arrayAction" />
                                        </div>
                                    </div>
                                @elseif($index == 'media_id')
                                    <div class="my-4">
                                        <div class="d-flex">
                                            <div class="flex-grow-1">
                                                <input type="file" class=" media_cropper d-none"
                                                    id="{{ $i }}_{{ $index }}_{{ $flagCards }}_media"
                                                    name="" data-width="1920" data-height="1080"
                                                    data-preview="{{ $i }}_{{ $index }}_{{ $flagCards }}_preview"
                                                    data-hidden_container="{{ $i }}_{{ $index }}_{{ $flagCards }}_container"
                                                    data-append="0"
                                                    data-name="{{ strtolower($section_name) }}_{{ $flagCards }}[]" />
                                                <x-btn type="secondary" class="btn-upload mb-1"
                                                    data-id="{{ $i }}_{{ $index }}_{{ $flagCards }}_media">
                                                    <i class="bi bi-upload"></i>
                                                    Upload
                                                    media
                                                </x-btn>
                                                <div class="fs-9">Media
                                                    dapat
                                                    berupa
                                                    video
                                                    atau gambar</div>
                                            </div>
                                            <div class="w-100px">
                                                <small class="text-gray-600">Preview</small>
                                                <div
                                                    id="{{ $i }}_{{ $index }}_{{ $flagCards }}_preview">
                                                    <small class="text-muted">No
                                                        Preview</small>
                                                </div>
                                                <div
                                                    id="{{ $i }}_{{ $index }}_{{ $flagCards }}_container">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @if ($value != '')
                                        <script>
                                            window.addEventListener('DOMContentLoaded', function() {
                                                add_to_preview('{{ $value[0] }}',
                                                    document.getElementById('{{ $i }}_media_{{ $flagCards }}_media').value,
                                                    '{{ $i }}_{{ $index }}_{{ $flagCards }}_preview',
                                                    '{{ $i }}_{{ $index }}_{{ $flagCards }}_container',
                                                    '{{ strtolower($section_name) }}_{{ $flagCards }}[]',
                                                    false);
                                            });
                                        </script>
                                    @endif
                                @elseif($index == 'media' && $value != '')
                                    <x-form.input type="hidden"
                                        id="{{ $i }}_media_{{ $flagCards }}_media"
                                        value="{{ $item }}" />
                                @else
                                    <x-form.textarea name="{{ $index }}_{{ $section_name }}" class="editor"
                                        label="{{ ucwords($index) }}" rows="7"
                                        required>{{ $item }}</x-form.textarea>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="pt-2 d-flex align-items-center justify-content-center" style="width: 85px; text-align: center;">
                <i class="ki-duotone ki-arrow-up-down fs-1 text-dark">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
            </div>
        </div>
        @php $flagCards++; @endphp
    @endforeach
</div>
