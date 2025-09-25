@extends(request()->query('snap') == true ? 'layouts.snap' : 'layouts.apps')
@section('toolbar')
    <x-theme.toolbar :breadCrump="$pageData->breadCrump" :title="$pageData->title">
        <x-slot:tools>
            <x-theme.back link="{{ url('admin/konten-prodi') }}" />
        </x-slot:tools>
    </x-theme.toolbar>
@endsection


@section('content')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet" />
    <style>
        .image-container {
            position: relative;
            display: inline-block;
        }

        .image-container img {
            transition: 0.3s ease;
        }

        .action-buttons {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            display: none;
            gap: 8px;
            /* Space between buttons */
        }

        .image-container:hover img {
            filter: blur(5px);
        }

        .image-container:hover .action-buttons {
            display: flex;
        }

        .btn-light {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .btn-light:hover {
            transform: scale(1.1);
        }
    </style>
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-fluid" data-cue="slideInLeft" data-duration="1000"
        data-delay="0">
        <div class="row">
            <div class="col-xl-5 col-md-6 col-12">
                <form id="formData" class="needs-validation">
                    <div class="accordion accordion-icon-toggle mb-4" id="accordionForm">
                        <div class="mb-1">
                            <div class="accordion-header p-2 rounded bg-light rounded-2 d-flex" data-bs-toggle="collapse"
                                data-bs-target="#accordion_item_header">
                                <span class="accordion-icon">
                                    <i class="ki-duotone ki-arrow-right fs-7 p-1"><span class="path1"></span><span
                                            class="path2"></span></i>
                                </span>
                                <span class="fs-7 fw-semibold mb-0 ms-4">Section Header
                                </span>
                            </div>
                            <div id="accordion_item_header"
                                class="fs-8 collapse hover-div border border-secondary rounded-4 p-4 collapse border border-1 show"
                                data-bs-parent="#accordionForm">
                                <input type="hidden" name="id" value="{{ $pageData->dataForm['id'] }}">
                                <input type="hidden" name="preview" value="">
                                <input type="hidden" name="hidden_container" value="">
                                <input type="hidden" name="is_append" value="">
                                <input type="hidden" name="media_name" value="">

                                <div class="mb-4">
                                    <x-form.input type="file" label="Header Prodi" class="media_cropper"
                                        data-preview="media_header_preview" data-hidden_container="hidden_inputs_header"
                                        data-append="0" data-name="header" />
                                    <div class="d-flex align-items-center flex-row flex-wrap mt-4"
                                        id="media_header_preview">
                                    </div>
                                    <div id="hidden_inputs_header"></div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-1">
                            <div class="accordion-header p-2 rounded bg-light rounded-2 d-flex collapse collapsed"
                                data-bs-toggle="collapse" data-bs-target="#accordion_item_sambutan">
                                <span class="accordion-icon">
                                    <i class="ki-duotone ki-arrow-right fs-7 p-1"><span class="path1"></span><span
                                            class="path2"></span></i>
                                </span>
                                <span class="fs-7 fw-semibold mb-0 ms-4">Section Sambutan Ketua Program Studi
                                </span>
                            </div>
                            <div id="accordion_item_sambutan"
                                class="fs-8 collapse hover-div border border-secondary rounded-4 p-4 collapse border border-1"
                                data-bs-parent="#accordionForm">
                                <div class="mb-4">
                                    <x-form.input name="judul_sambutan" label="Judul Sambutan Ketua Program Studi"
                                        value="{{ $pageData->dataForm['judul_sambutan'] }}" required />
                                </div>
                                <div class="mb-4">
                                    <x-form.textarea name="isi_sambutan" label="Isi Sambutan Ketua Program Studi"
                                        rows="5" required>{{ $pageData->dataForm['isi_sambutan'] }}
                                    </x-form.textarea>
                                </div>
                                <div class="mb-4">
                                    <x-form.input type="file" label="Foto Ketua Program Studi" class="media_cropper"
                                        data-preview="media_sambutan_preview" data-hidden_container="hidden_inputs_sambutan"
                                        data-append="0" data-name="sambutan" />
                                    <div class="d-flex align-items-center flex-row flex-wrap mt-4"
                                        id="media_sambutan_preview">
                                    </div>
                                    <div id="hidden_inputs_sambutan"></div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-1">
                            <div class="accordion-header p-2 rounded bg-light rounded-2 d-flex collapse collapsed"
                                data-bs-toggle="collapse" data-bs-target="#accordion_item_akreditasi">
                                <span class="accordion-icon">
                                    <i class="ki-duotone ki-arrow-right fs-7 p-1"><span class="path1"></span><span
                                            class="path2"></span></i>
                                </span>
                                <span class="fs-7 fw-semibold mb-0 ms-4">Section Akreditasi
                                </span>
                            </div>
                            <div id="accordion_item_akreditasi"
                                class="fs-8 collapse hover-div border border-secondary rounded-4 p-4 collapse border border-1"
                                data-bs-parent="#accordionForm">
                                <div class="mb-4">
                                    <x-form.select name="akreditasi" label="Akreditasi" :search="true" required>
                                        <option {{ $pageData->dataForm['akreditasi_akreditasi'] == 'A' ? 'selected' : '' }}
                                            value="A">A</option>
                                        <option {{ $pageData->dataForm['akreditasi_akreditasi'] == 'B' ? 'selected' : '' }}
                                            value="B">B</option>
                                        <option {{ $pageData->dataForm['akreditasi_akreditasi'] == 'C' ? 'selected' : '' }}
                                            value="C">C</option>
                                        <option
                                            {{ $pageData->dataForm['akreditasi_akreditasi'] == 'Unggul' ? 'selected' : '' }}
                                            value="Unggul">Unggul</option>
                                        <option
                                            {{ $pageData->dataForm['akreditasi_akreditasi'] == 'Baik Sekali' ? 'selected' : '' }}
                                            value="Baik Sekali">Baik Sekali</option>
                                        <option
                                            {{ $pageData->dataForm['akreditasi_akreditasi'] == 'Baik' ? 'selected' : '' }}
                                            value="Baik">Baik</option>
                                        <option {{ $pageData->dataForm['akreditasi_akreditasi'] == '-' ? 'selected' : '' }}
                                            value="-">-</option>
                                    </x-form.select>
                                </div>
                                <div class="mb-4">
                                    <x-form.input name="no_sk_akreditasi" label="No SK" id="no_sk_akreditasi"
                                        class="" value="{{ $pageData->dataForm['no_sk_akreditasi'] }}" />
                                </div>
                                <div class="mb-4">
                                    <x-form.input name="url_akreditasi" label="Url Dokumen" id="url_akreditasi"
                                        class="" value="{{ $pageData->dataForm['url_akreditasi'] }}" />
                                </div>
                            </div>
                        </div>
                        <div class="mb-1">
                            <div class="accordion-header p-2 rounded bg-light rounded-2 d-flex collapse collapsed"
                                data-bs-toggle="collapse" data-bs-target="#accordion_item_karir">
                                <span class="accordion-icon">
                                    <i class="ki-duotone ki-arrow-right fs-7 p-1"><span class="path1"></span><span
                                            class="path2"></span></i>
                                </span>
                                <span class="fs-7 fw-semibold mb-0 ms-4">Section Prospek Karir
                                </span>
                            </div>
                            <div id="accordion_item_karir"
                                class="fs-8 collapse hover-div border border-secondary rounded-4 p-4 collapse border border-1"
                                data-bs-parent="#accordionForm">
                                <div class="mb-4" id="form_prospek_karir">
                                    <label class="form-label">
                                        Prospek Karir
                                    </label>
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="flex-grow-1 me-2">
                                            <x-form.input name="propek_karir[]"
                                                value="{{ !empty($pageData->dataForm['prospek_karir']) ? $pageData->dataForm['prospek_karir'][0] : '' }}" />
                                        </div>
                                        <a href="javascript:;"
                                            class="btn btn-icon btn-sm mh-40px mw-40px btn-primary act-add"
                                            title="Tambah data" data-type="prospek">
                                            <i class="bi bi-plus fs-3"></i>
                                        </a>
                                    </div>
                                    @if (!empty($pageData->dataForm['prospek_karir']) && count($pageData->dataForm['prospek_karir']) > 1)
                                        @for ($i = 1; $i < count($pageData->dataForm['prospek_karir']); $i++)
                                            <div class="d-flex align-items-center mb-2"
                                                id="dynamic_input_prospek_{{ $i }}">
                                                <div class="flex-grow-1 me-2">
                                                    <x-form.input name="propek_karir[]"
                                                        value="{{ $pageData->dataForm['prospek_karir'][$i] }}" />
                                                </div>
                                                <a href="javascript:;"
                                                    class="btn btn-icon btn-sm mh-40px mw-40px btn-light-danger remove-input"
                                                    data-id="{{ $i }}" data-type="prospek"
                                                    title="Hapus data">
                                                    <i class="ki-outline ki-trash fs-3"></i>
                                                </a>
                                            </div>
                                        @endfor
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="mb-1">
                            <div class="accordion-header p-2 rounded bg-light rounded-2 d-flex collapse collapsed"
                                data-bs-toggle="collapse" data-bs-target="#accordion_item_milestones">
                                <span class="accordion-icon">
                                    <i class="ki-duotone ki-arrow-right fs-7 p-1"><span class="path1"></span><span
                                            class="path2"></span></i>
                                </span>
                                <span class="fs-7 fw-semibold mb-0 ms-4">Section Milestones Program Studi
                                </span>
                            </div>
                            <div id="accordion_item_milestones"
                                class="fs-8 collapse hover-div border border-secondary rounded-4 p-4 collapse border border-1"
                                data-bs-parent="#accordionForm">
                                <div class="mb-4" id="form_milestones">
                                    <label class="form-label">
                                        Milestones Program Studi
                                    </label>
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="flex-grow-1 me-2">
                                            <x-form.select name="tahun_milestones[]" placeholder="Tahun" class="mb-2">
                                                @for ($i = date('Y'); $i >= 2000; $i--)
                                                    <option value="{{ $i }}"
                                                        {{ !empty($pageData->dataForm['milestone_prodi']) && $pageData->dataForm['milestone_prodi'][0]['tahun_milestone'] == $i ? 'selected' : '' }}>
                                                        {{ $i }}</option>
                                                @endfor
                                            </x-form.select>
                                            <x-form.textarea name="konten_milestones[]" placeholder="Konten milestones"
                                                class="mb-2">
                                                {{ !empty($pageData->dataForm['milestone_prodi']) ? $pageData->dataForm['milestone_prodi'][0]['konten_milestone'] : '' }}
                                            </x-form.textarea>
                                        </div>
                                        <a href="javascript:;"
                                            class="btn btn-icon btn-sm mh-40px mw-40px btn-primary act-add"
                                            title="Tambah data" data-type="milestones">
                                            <i class="bi bi-plus fs-3"></i>
                                        </a>
                                    </div>
                                    @if (!empty($pageData->dataForm['milestone_prodi']) && count($pageData->dataForm['milestone_prodi']) > 1)
                                        @for ($index = 1; $index < count($pageData->dataForm['milestone_prodi']); $index++)
                                            <div id="dynamic_input_milestones_{{ $i }}">
                                                <div class="separator separator-dashed my-3"></div>
                                                <div class="d-flex align-items-center mb-2">
                                                    <div class="flex-grow-1 me-2">
                                                        <x-form.select name="tahun_milestones[]" placeholder="Tahun"
                                                            class="mb-2">
                                                            @for ($i = date('Y'); $i >= 2000; $i--)
                                                                <option value="{{ $i }}"
                                                                    {{ !empty($pageData->dataForm['milestone_prodi']) && $pageData->dataForm['milestone_prodi'][$index]['tahun_milestone'] == $i ? 'selected' : '' }}>
                                                                    {{ $i }}
                                                                </option>
                                                            @endfor
                                                        </x-form.select>
                                                        <x-form.textarea name="konten_milestones[]"
                                                            placeholder="Konten milestones" class="mb-2">
                                                            {{ !empty($pageData->dataForm['milestone_prodi']) ? $pageData->dataForm['milestone_prodi'][$index]['konten_milestone'] : '' }}
                                                        </x-form.textarea>
                                                    </div>
                                                    <a href="javascript:;"
                                                        class="btn btn-icon btn-sm mh-40px mw-40px btn-light-danger remove-input"
                                                        data-id="{{ $i }}" data-type="milestones"
                                                        title="Hapus data">
                                                        <i class="ki-outline ki-trash fs-3"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        @endfor
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="mb-1">
                            <div class="accordion-header p-2 rounded bg-light rounded-2 d-flex collapse collapsed"
                                data-bs-toggle="collapse" data-bs-target="#accordion_item_visi">
                                <span class="accordion-icon">
                                    <i class="ki-duotone ki-arrow-right fs-7 p-1"><span class="path1"></span><span
                                            class="path2"></span></i>
                                </span>
                                <span class="fs-7 fw-semibold mb-0 ms-4">Section Visi Program Studi
                                </span>
                            </div>
                            <div id="accordion_item_visi"
                                class="fs-8 collapse hover-div border border-secondary rounded-4 p-4 collapse border border-1"
                                data-bs-parent="#accordionForm">
                                <div class="mb-4">
                                    <x-form.input name="visi_prodi" label="Visi Prodi" id="" class=""
                                        value="{{ $pageData->dataForm['visi_prodi'] }}" />
                                </div>
                            </div>
                        </div>
                        <div class="mb-1">
                            <div class="accordion-header p-2 rounded bg-light rounded-2 d-flex collapse collapsed"
                                data-bs-toggle="collapse" data-bs-target="#accordion_items_misi">
                                <span class="accordion-icon">
                                    <i class="ki-duotone ki-arrow-right fs-7 p-1"><span class="path1"></span><span
                                            class="path2"></span></i>
                                </span>
                                <span class="fs-7 fw-semibold mb-0 ms-4">Section Misi Program Studi
                                </span>
                            </div>
                            <div id="accordion_items_misi"
                                class="fs-8 collapse hover-div border border-secondary rounded-4 p-4 collapse border border-1"
                                data-bs-parent="#accordionForm">
                                <div class="mb-4" id="form_misi">
                                    <label class="form-label">
                                        Misi Program Studi
                                    </label>
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="flex-grow-1 me-2">
                                            <x-form.textarea name="misi_prodi[]" placeholder="Konten misi program studi"
                                                class="mb-2">{{ !empty($pageData->dataForm['misi_prodi']) ? $pageData->dataForm['misi_prodi'][0]['misi_prodi'] : '' }}</x-form.textarea>
                                        </div>
                                        <a href="javascript:;"
                                            class="btn btn-icon btn-sm mh-40px mw-40px btn-primary act-add"
                                            title="Tambah data" data-type="misi">
                                            <i class="bi bi-plus fs-3"></i>
                                        </a>
                                    </div>
                                    @if (!empty($pageData->dataForm['misi_prodi']) && count($pageData->dataForm['misi_prodi']) > 1)
                                        @for ($i = 1; $i < count($pageData->dataForm['misi_prodi']); $i++)
                                            <div class="d-flex align-items-center mb-2"
                                                id="dynamic_input_misi_{{ $i }}">
                                                <div class="flex-grow-1 me-2">
                                                    <x-form.textarea name="misi_prodi[]"
                                                        placeholder="Konten misi program studi"
                                                        class="mb-2">{{ $pageData->dataForm['misi_prodi'][$i]['misi_prodi'] }}</x-form.textarea>
                                                </div>
                                                <a href="javascript:;"
                                                    class="btn btn-icon btn-sm mh-40px mw-40px btn-light-danger remove-input"
                                                    data-id="{{ $i }}" data-type="misi" title="Hapus data">
                                                    <i class="ki-outline ki-trash fs-3"></i>
                                                </a>
                                            </div>
                                        @endfor
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="mb-1">
                            <div class="accordion-header p-2 rounded bg-light rounded-2 d-flex collapse collapsed"
                                data-bs-toggle="collapse" data-bs-target="#accordion_items_tujuan">
                                <span class="accordion-icon">
                                    <i class="ki-duotone ki-arrow-right fs-7 p-1"><span class="path1"></span><span
                                            class="path2"></span></i>
                                </span>
                                <span class="fs-7 fw-semibold mb-0 ms-4">Section Tujuan Program Studi
                                </span>
                            </div>
                            <div id="accordion_items_tujuan"
                                class="fs-8 collapse hover-div border border-secondary rounded-4 p-4 collapse border border-1"
                                data-bs-parent="#accordionForm">
                                <div class="mb-4" id="form_tujuan">
                                    <label class="form-label">
                                        Tujuan Program Studi
                                    </label>
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="flex-grow-1 me-2">
                                            <x-form.textarea name="tujuan_prodi[]"
                                                placeholder="Konten tujuan program studi"
                                                class="mb-2">{{ !empty($pageData->dataForm['tujuan_prodi']) ? $pageData->dataForm['tujuan_prodi'][0] : '' }}</x-form.textarea>
                                        </div>
                                        <a href="javascript:;"
                                            class="btn btn-icon btn-sm mh-40px mw-40px btn-primary act-add"
                                            title="Tambah data" data-type="tujuan">
                                            <i class="bi bi-plus fs-3"></i>
                                        </a>
                                    </div>
                                    @if (!empty($pageData->dataForm['tujuan_prodi']) && count($pageData->dataForm['tujuan_prodi']) > 1)
                                        @for ($i = 1; $i < count($pageData->dataForm['tujuan_prodi']); $i++)
                                            <div class="d-flex align-items-center mb-2"
                                                id="dynamic_input_tujuan_{{ $i }}">
                                                <div class="flex-grow-1 me-2">
                                                    <x-form.textarea name="tujuan_prodi[]"
                                                        placeholder="Konten tujuan program studi" class="mb-2">
                                                        {{ $pageData->dataForm['tujuan_prodi'][$i] }}
                                                    </x-form.textarea>
                                                </div>
                                                <a href="javascript:;"
                                                    class="btn btn-icon btn-sm mh-40px mw-40px btn-light-danger remove-input"
                                                    data-id="{{ $i }}" data-type="tujuan" title="Hapus data">
                                                    <i class="ki-outline ki-trash fs-3"></i>
                                                </a>
                                            </div>
                                        @endfor
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="my-4">
                        <div class="d-flex justify-content-end">
                            <x-btn.form action="save" class="act-save" />
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-xl-7 col-md-6 col-12">
                <x-card.compact class="h-100">
                    <div class="d-flex align-items-center justify-content-center h-600px fs-6 text-gray-400">No Preview
                        Available
                    </div>
                </x-card.compact>
            </div>
        </div>
    </div>

    <div id="cropModal" class="bg-light rounded rounded-4"
        style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 1050; padding: 20px; box-shadow: 0 4px 8px rgba(0,0,0,0.2);">
        <div id="cropContainer" class="p-2">
            <img id="cropImage" style="max-height: 500px;" />
        </div>
        <div class="d-flex justify-content-end w-100">
            <button id="cropCancel" type="button" class="btn btn-sm btn-secondary">Close</button>
            <x-btn type="primary" id="cropSave">Crop & Save</x-btn>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

    <x-script.crud2></x-script.crud2>
    <script>
        let inputIndexProspek =
            {{ !empty($pageData->dataForm['prospek_karir']) && count($pageData->dataForm['prospek_karir']) > 1 ? count($pageData->dataForm['prospek_karir']) : 1 }};
        let inputIndexMilestones = 1;
        let inputIndexMisi = 1;
        let inputIndexTujuan = 1;

        const cropModal = $('#cropModal');
        const cropImage = $('#cropImage');
        let cropper;


        $(document).on('click', '.act-save', function() {
            var form = document.getElementById('formData')
            var formData = new FormData(form);

            Swal.fire({
                title: "Anda yakin ?",
                text: "Data yang telah dihapus saat proses perubahan tidak dapat dikembalikan",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Ya, Lanjutkan!",
                cancelButtonText: "Batal",
                customClass: {
                    confirmButton: "btn btn-light-danger",
                    cancelButton: "btn btn-light-dark"
                },
                reverseButtons: true
            }).then(function(result) {
                if (result.value) {
                    ajaxRequest({
                        link: `{{ url('admin/konten-prodi/update') }}`,
                        data: [formData],
                        block: true,
                        swal_success: true,
                        callback: function(origin, resp) {
                            if (resp.status) {
                                location.reload()
                            }
                        }
                    })
                }
            });
        })

        $(document).ready(function() {
            @if ($pageData->dataForm['media_header'])
                @foreach ($pageData->dataForm['media_header'] as $row)
                    add_to_preview(`{{ $row['id'] }}`, `{{ $row['base64'] }}`, 'media_header_preview',
                        'hidden_inputs_header', 'header')
                @endforeach
            @endif
            @if ($pageData->dataForm['media_sambutan'])
                @foreach ($pageData->dataForm['media_sambutan'] as $row)
                    add_to_preview(`{{ $row['id'] }}`, `{{ $row['base64'] }}`, 'media_sambutan_preview',
                        'hidden_inputs_sambutan', 'sambutan')
                @endforeach
            @endif
        })

        $(document).on('click', '.remove-input', function(e) {
            e.preventDefault();
            if ($(this).data('type') == 'prospek') {
                const inputId = $(this).data('id');
                $(`#dynamic_input_prospek_${inputId}`).remove();
            } else if ($(this).data('type') == 'milestones') {
                const inputId = $(this).data('id');
                $(`#dynamic_input_milestones_${inputId}`).remove();
            } else if ($(this).data('type') == 'misi') {
                const inputId = $(this).data('id');
                $(`#dynamic_input_misi_${inputId}`).remove();
            } else if ($(this).data('type') == 'tujuan') {
                const inputId = $(this).data('id');
                $(`#dynamic_input_tujuan_${inputId}`).remove();
            }
        });

        $('.act-add').on('click', function(e) {
            e.preventDefault();
            if ($(this).data('type') == 'prospek') {
                $('#form_prospek_karir').append(`
                <div class="d-flex align-items-center mb-2" id="dynamic_input_prospek_${inputIndexProspek}">
                    <div class="flex-grow-1 me-2">
                        <x-form.input name="propek_karir[]" />
                    </div>
                    <a href="javascript:;"
                    class="btn btn-icon btn-sm mh-40px mw-40px btn-light-danger remove-input"
                    data-id="${inputIndexProspek}" data-type="prospek"
                    title="Hapus data">
                        <i class="ki-outline ki-trash fs-3"></i>
                    </a>
                </div>
        `);

                inputIndexProspek++;
            } else if ($(this).data('type') == 'milestones') {
                $('#form_milestones').append(`
                <div id="dynamic_input_milestones_${inputIndexProspek}">
                    <div class="separator separator-dashed my-3"></div>
                    <div class="d-flex align-items-center mb-2">
                        <div class="flex-grow-1 me-2">
                            <x-form.select name="tahun_milestones[]" placeholder="Tahun" class="mb-2">
                                @for ($i = date('Y'); $i >= 2000; $i--)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </x-form.select>
                            <x-form.textarea name="konten_milestones[]" placeholder="Konten milestones"
                                class="mb-2"></x-form.textarea>
                        </div>
                        <a href="javascript:;"
                        class="btn btn-icon btn-sm mh-40px mw-40px btn-light-danger remove-input"
                        data-id="${inputIndexProspek}" data-type="milestones"
                        title="Hapus data">
                            <i class="ki-outline ki-trash fs-3"></i>
                        </a>
                    </div>
                </div>
        `);

                inputIndexMilestones++;
            } else if ($(this).data('type') == 'misi') {
                $('#form_misi').append(`
                <div class="d-flex align-items-center mb-2" id="dynamic_input_misi_${inputIndexMisi}">
                    <div class="flex-grow-1 me-2">
                        <x-form.textarea name="misi_prodi[]" placeholder="Konten misi program studi"
                            class="mb-2"></x-form.textarea>
                    </div>
                    <a href="javascript:;"
                    class="btn btn-icon btn-sm mh-40px mw-40px btn-light-danger remove-input"
                    data-id="${inputIndexMisi}" data-type="misi"
                    title="Hapus data">
                        <i class="ki-outline ki-trash fs-3"></i>
                    </a>
                </div>
        `);

                inputIndexMisi++;
            } else if ($(this).data('type') == 'tujuan') {
                $('#form_tujuan').append(`
                <div class="d-flex align-items-center mb-2" id="dynamic_input_tujuan_${inputIndexTujuan}">
                    <div class="flex-grow-1 me-2">
                        <x-form.textarea name="tujuan_prodi[]" placeholder="Konten tujuan program studi"
                            class="mb-2"></x-form.textarea>
                    </div>
                    <a href="javascript:;"
                    class="btn btn-icon btn-sm mh-40px mw-40px btn-light-danger remove-input"
                    data-id="${inputIndexTujuan}" data-type="tujuan"
                    title="Hapus data">
                        <i class="ki-outline ki-trash fs-3"></i>
                    </a>
                </div>
        `);

                inputIndexTujuan++;
            }
        });

        $('.media_cropper').on('change', function(event) {
            name = $(this).data('name')

            $('#formData [name="preview"]').val($(this).data('preview'))
            $('#formData [name="hidden_container"]').val($(this).data(
                'hidden_container'))
            $('#formData [name="is_append"]').val($(this).data('append'))
            $('#formData [name="media_name"]').val(name)

            if (cropper) {
                cropper.destroy();
            }
            const files = event.target.files;
            if (name === 'header') {
                aspectRatio = 16 / 5;
            } else {
                aspectRatio = 1;
            }
            if (files.length > 0) {
                const file = files[0];
                if (file.type.startsWith('image/')) { // Ensure the file is an image
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        cropImage.attr('src', e.target.result);
                        cropModal.show();
                        cropper = new Cropper(cropImage[0], {
                            aspectRatio: aspectRatio, // Fixed aspect ratio
                            viewMode: 1,
                            dragMode: 'move', // Enable moving the image
                            scalable: true, // Enable resizing the image
                            zoomable: true, // Enable zooming
                            movable: true, // Enable moving
                        });
                    };

                    reader.readAsDataURL(file);
                }
            }

            // Clear the input value
            $(this).val('');
        });

        $('#cropSave').on('click', function() {
            var id = $('#formData [name="id"]').val()
            var preview = $('#formData [name="preview"]').val()
            var hidden_container = $('#formData [name="hidden_container"]').val()
            var is_append = $('#formData [name="is_append"]').val()
            var media_name = $('#formData [name="media_name"]').val()

            if (cropper) {
                const croppedCanvas = cropper.getCroppedCanvas({
                    width: 300, // Fixed size
                    height: 300
                });
                const croppedImage = croppedCanvas.toDataURL('image/png');

                // Create hidden input to store the cropped image

                ajaxRequest({
                    link: `{{ url('admin/media/store') }}`,
                    data: {
                        upload_file: croppedImage,
                    },
                    callback: function(origin, resp) {
                        if (resp.status) {
                            // Add cropped image to the preview
                            add_to_preview(resp.data.media_id, croppedImage, preview, hidden_container,
                                media_name,
                                (is_append == 1 ? true : false))

                            cropper.destroy();
                            cropModal.hide();
                        }
                    }
                })
            }
        });

        $('#cropCancel').on('click', function() {
            if (cropper) {
                cropper.destroy();
                cropModal.hide();
            }
        });

        function add_to_preview(media_id, croppedImage, previewContainerId, hiddenInputsContainerId, media_name = "",
            is_append =
            true) {
            const previewContainer = $('#' + previewContainerId);
            const hiddenInputsContainer = $('#' + hiddenInputsContainerId);

            const container = $('<div>')
                .addClass('image-container me-2 mb-2');

            const img = $('<img>')
                .attr('src', croppedImage)
                .addClass('rounded rounded-2 h-100px w-100px')
                .attr('alt', 'Cropped Image');

            const actionButtons = $('<div>')
                .addClass('action-buttons');

            const deleteButton = $('<a>')
                .attr('href', 'javascript:;')
                .addClass(
                    'btn btn-icon btn-sm mh-25px mw-25px btn-light-danger act-delete')
                .attr('title', 'Hapus data');

            const icon = $('<i>')
                .addClass('ki-outline ki-trash fs-3');

            deleteButton.append(icon);
            deleteButton.on('click', function() {
                container.remove();
                deleteId = hiddenInput.val();
                var hiddenInputDelete = $('<input>')
                    .attr('type', 'hidden')
                    .attr('name', 'delete_media_id_' + media_name + '[]')
                    .val(deleteId); // Base64 data
                hiddenInputsContainer.append(hiddenInputDelete);
                hiddenInput.remove();
            });

            actionButtons.append(deleteButton);
            container.append(img).append(actionButtons);
            if (is_append) {
                previewContainer.append(container);
            } else {
                previewContainer.html(container);
            }

            var hiddenInput = $('<input>')
                .attr('type', 'hidden')
                .attr('name', 'media_id_' + media_name + '[]')
                .val(media_id); // Base64 data

            hiddenInputsContainer.append(hiddenInput);
        }
    </script>
@endpush
