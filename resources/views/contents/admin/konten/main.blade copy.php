@extends(request()->query('snap') == true ? 'layouts.snap' : 'layouts.apps')
@section('toolbar')
    <x-theme.toolbar :breadCrump="$pageData->breadCrump" :title="$pageData->title">
        <x-slot:tools>
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

        @keyframes slideInLeftSmooth {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .animate-slide-in-left {
            animation: slideInLeftSmooth 0.4s ease-out;
        }
    </style>


    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-fluid" data-cue="slideInLeft" data-duration="1000"
        data-delay="0">
        <div class="d-flex flex-row">
            <div class="d-lg-flex flex-column flex-lg-row-auto w-lg-275px me-3" data-kt-drawer="true"
                data-kt-drawer-name="end-sidebar" data-kt-drawer-activate="{default: true, lg: false}"
                data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'200px', '250px': '300px'}"
                data-kt-drawer-direction="end" data-kt-drawer-toggle="#toggleDetails">
                @foreach ($pageData->dataSection as $key => $value)
                    <div
                        class="menu menu-column menu-rounded menu-state-bg menu-state-title-primary menu-state-icon-primary menu-state-bullet-primary px-2 py-1 fs-7">
                        <div class="menu-item">
                            <a href="#" class="menu-link side-menu text-gray-800" data-id="{{ $value['config'] }}">
                                <span class="menu-icon">
                                    <i class="ki-solid ki-arrow-right"></i>
                                </span>
                                <span class="menu-title fw-bold">{{ $value['title'] }}</span>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="w-100 flex-lg-row-fluid h-650px" data-cue="slideInLeft" data-duration="1000" data-delay="0">
                <x-card.compact class="mb-4 h-100">
                    <form id="formData" class="needs-validation">
                        <div class="row pe-1">
                            <div class="col-xl-7 col-12">
                                <input type="hidden" name="id" value="{{ $pageData->dataPage['id'] }}">
                                <div class="d-none" id="hero_main_form" style="height:630px;overflow: auto;">
                                    <div class="d-flex w-100 justify-content-end mb-4">
                                        <x-btn type="primary" class="act-add w-100 w-md-auto">
                                            <i class="bi bi-plus fs-2"></i> Tambah data
                                        </x-btn>
                                    </div>
                                    @foreach ($pageData->dataKonten['hero_main'] as $key => $value)
                                        <div class="d-flex flex-row mb-4 bg-light rounded hero_main_component"
                                            id="media_main_card_{{ $key }}">
                                            <div class="d-flex align-items-center justify-content-center"
                                                style="width: 60px; text-align: center;">
                                                @php
                                                    $dataAction = [];
                                                    $dataAction[] = [
                                                        'action' => 'delete',
                                                        'attr' => [
                                                            'data-id' => $key,
                                                        ],
                                                    ];
                                                @endphp
                                                <x-btn.actiontable :id="$key" :btn="$dataAction" />
                                            </div>
                                            <div class="flex-grow-1 py-4 px-4">
                                                <div class="mb-4">
                                                    <x-form.input name="title_main[]" label="Title"
                                                        value="{{ $value['title'] }}" required />
                                                </div>
                                                <div class="mb-4">
                                                    <div class="d-flex">
                                                        <div class="flex-grow-1">
                                                            <input type="file" class="d-none media_cropper"
                                                                id="media_main_{{ $key }}" name="media_main[]"
                                                                data-preview="media_main_{{ $key }}_preview"
                                                                data-hidden_container="hidden_inputs_hero_{{ $key }}"
                                                                data-append="0" data-name="hero[]" />
                                                            <x-btn type="secondary" class="btn-upload mb-1"
                                                                data-id="media_main_{{ $key }}">
                                                                <i class="bi bi-upload"></i> Upload media
                                                            </x-btn>
                                                            <div class="fs-9">Media dapat berupa video atau gambar</div>
                                                        </div>
                                                        <div class="w-100px">
                                                            <small>Preview</small>
                                                            <div id="media_main_{{ $key }}_preview">
                                                                <small class="text-muted">No Preview</small>
                                                            </div>
                                                            <div id="hidden_inputs_hero_{{ $key }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="pt-2 d-flex align-items-center justify-content-center"
                                                style="width: 85px; text-align: center;">
                                                <i class="ki-duotone ki-arrow-up-down fs-1 text-dark">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="d-none" id="infografis_main_form">
                                    <div class="mb-4">
                                        <x-form.input name="title_infografis" label="Title"
                                            value="{{ $pageData->dataKonten['infografis_main']['title'] }}" required />
                                    </div>
                                    <div class="mb-4">
                                        <x-form.textarea name="deskripsi_infografis" class="editor" label="Deskripsi"
                                            rows="5"
                                            required>{{ $pageData->dataKonten['infografis_main']['deskripsi'] }}</x-form.textarea>
                                    </div>
                                    <div class="mb-4">
                                        <div class="d-flex">
                                            <div class="flex-grow-1">
                                                <input type="file" class="d-none media_cropper" id="media_infografis"
                                                    name="media_infografis" data-preview="media_infografis_preview"
                                                    data-hidden_container="hidden_input_infografis" data-append="0"
                                                    data-name="infografis" />
                                                <x-btn type="secondary" class="btn-upload mb-1"
                                                    data-id="media_infografis">
                                                    <i class="bi bi-upload"></i> Upload media
                                                </x-btn>
                                                <div class="fs-9">Media berupa gambar</div>
                                            </div>
                                            <div class="w-100px">
                                                <small>Preview</small>
                                                <div id="media_infografis_preview">
                                                    <small class="text-muted">No Preview</small>
                                                </div>
                                                <div id="hidden_input_infografis"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-none" id="jurusan_main_form">
                                    <div class="mb-4">
                                        <x-form.input name="title_jurusan" label="Title"
                                            value="{{ $pageData->dataKonten['jurusan_main']['title'] }}" required />
                                    </div>
                                    <div class="mb-4">
                                        <x-form.textarea name="deskripsi_jurusan" class="editor" label="Deskripsi"
                                            rows="7" value=""
                                            required>{{ $pageData->dataKonten['jurusan_main']['deskripsi'] }}</x-form.textarea>
                                    </div>
                                </div>
                                <div class="d-none" id="pmb_main_form">
                                    <div class="mb-4">
                                        <x-form.input name="title_pmb" label="Title"
                                            value="{{ $pageData->dataKonten['pmb_main']['title'] }}" required />
                                    </div>
                                    <div class="mb-4">
                                        <x-form.textarea name="deskripsi_pmb" class="editor" label="Deskripsi"
                                            rows="7"
                                            required>{{ $pageData->dataKonten['pmb_main']['deskripsi'] }}</x-form.textarea>
                                    </div>
                                    <div class="mb-4">
                                        <div class="d-flex">
                                            <div class="flex-grow-1">
                                                <input type="file" class="d-none media_cropper" id="media_pmb"
                                                    name="media_pmb" data-preview="media_pmb_preview"
                                                    data-hidden_container="hidden_input_pmb" data-append="0"
                                                    data-name="pmb" />
                                                <x-btn type="secondary" class="btn-upload mb-1" data-id="media_pmb">
                                                    <i class="bi bi-upload"></i> Upload media
                                                </x-btn>
                                                <div class="fs-9">Media berupa gambar</div>
                                            </div>
                                            <div class="w-100px">
                                                <small>Preview</small>
                                                <div id="media_pmb_preview">
                                                    <small class="text-muted">No Preview</small>
                                                </div>
                                                <div id="hidden_input_pmb"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-none" id="partner_main_form">
                                    <div class="mb-4">
                                        <x-form.input name="title_partner" label="Title"
                                            value="{{ $pageData->dataKonten['partner_main']['title'] }}" required />
                                    </div>
                                    <div class="mb-4">
                                        <x-form.textarea name="deskripsi_partner" class="editor" label="Deskripsi"
                                            rows="7"
                                            required>{{ $pageData->dataKonten['partner_main']['deskripsi'] }}</x-form.textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-5 col-12 bg-light rounded rounded-4 h-100 p-4 d-none d-xl-block">
                                <h3>Guide</h3>
                                <div class="d-none" id="hero_main_guide">
                                    <div class="d-flex justify-content-center w-100 mb-4">
                                        <img src="{{ asset('theme/media/image/guides/hero_main_guide.png') }}"
                                            alt="Guide Hero Main Section" class="w-400px rounded">
                                    </div>
                                    <ol>
                                        <li><span class="fw-bold">Title</span> -> merupakan judul yang menjadi highlight
                                            dari
                                            hero section website utama</li>
                                        <li><span class="fw-bold">Media</span> -> berupa gambar atau video dengan resolusi
                                            yang
                                            tinggi dan menjadi highlight
                                            utama saat website dibuka</li>
                                    </ol>
                                </div>
                                <div class="d-none" id="infografis_main_guide">
                                    <div class="d-flex justify-content-center w-100 mb-4">
                                        <img src="{{ asset('theme/media/image/guides/infografis_main_guide.png') }}"
                                            alt="Guide Hero Main Section" class="w-400px rounded">
                                    </div>
                                    <ol>
                                        <li><span class="fw-bold">Deskripsi</span> -> deskripsi singkat terkait bagian
                                            infografis</li>
                                        <li><span class="fw-bold">Media</span> -> berupa gambar dengan background
                                            transparent
                                        </li>
                                    </ol>
                                </div>
                                <div class="d-none" id="jurusan_main_guide">
                                    <div class="d-flex justify-content-center w-100 mb-4">
                                        <img src="{{ asset('theme/media/image/guides/jurusan_main_guide.png') }}"
                                            alt="Guide Hero Main Section" class="w-400px rounded">
                                    </div>
                                    <ol>
                                        <li><span class="fw-bold">Deskripsi</span> -> deskripsi singkat terkait bagian
                                            jurusan</li>
                                    </ol>
                                </div>
                                <div class="d-none" id="pmb_main_guide">
                                    <div class="d-flex justify-content-center w-100 mb-4">
                                        <img src="{{ asset('theme/media/image/guides/pmb_main_guide.png') }}"
                                            alt="Guide Hero Main Section" class="w-400px rounded">
                                    </div>
                                    <ol>
                                        <li><span class="fw-bold">Deskripsi</span> -> deskripsi singkat terkait bagian
                                            penerimaan mahasiswa baru</li>
                                        <li><span class="fw-bold">Media</span> -> berupa gambar dengan background
                                            transparent
                                        </li>
                                    </ol>
                                </div>
                                <div class="d-none" id="partner_main_guide">
                                    <div class="d-flex justify-content-center w-100 mb-4">
                                        <img src="{{ asset('theme/media/image/guides/partner_main_guide.png') }}"
                                            alt="Guide Hero Main Section" class="w-400px rounded">
                                    </div>
                                    <ol>
                                        <li><span class="fw-bold">Deskripsi</span> -> deskripsi singkat terkait bagian
                                            partner</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </form>
                </x-card.compact>

                <div class="mb-0 justify-content-end"
                    style="position: -webkit-sticky; position: sticky !important; bottom: 0px; z-index: 9999;">
                    <div class="card-body p-3">
                        <div class="d-flex w-100 justify-content-end">
                            <x-btn.form action="save" class="act-save" text="Simpan data" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="cropModal" class="bg-light rounded rounded-4"
        style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 1050; padding: 20px; box-shadow: 0 4px 8px rgba(0,0,0,0.2);">
        <div id="cropContainer" class="p-2">
            <input type="hidden" name="preview" value="">
            <input type="hidden" name="hidden_container" value="">
            <input type="hidden" name="is_append" value="">
            <input type="hidden" name="media_name" value="">
            <img id="cropImage" style="max-width: 100%; max-height: 80vh; display: block; margin: 0 auto;" />
        </div>
        <div class="d-flex justify-content-end w-100">
            <button id="crop-cancel" type="button" class="btn btn-sm btn-secondary">Close</button>
            <x-btn type="primary" id="crop-save">Crop & Save</x-btn>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <script src="https://unpkg.com/sortablejs-make/Sortable.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-sortablejs@latest/jquery-sortable.js"></script>

    <x-script.crud2></x-script.crud2>
    <script>
        const cropModal = $('#cropModal');
        const cropImage = $('#cropImage');
        var countHeroMain = {{ count($pageData->dataKonten['hero_main']) }}
        let cropper;

        $('.side-menu.active').trigger('click')
        $('.side-menu').on('click', function(e) {
            e.preventDefault();

            const targetId = $(this).data('id');
            const targetFormId = `#${targetId}_form`;
            const targetGuideId = `#${targetId}_guide`;

            // Ubah tab active
            $('.side-menu').removeClass('active');
            $(this).addClass('active').addClass('text-gray-800');

            // Sembunyikan semua form
            $('[id$="_form"]').addClass('d-none').removeClass('animate-slide-in-left');
            $('[id$="_guide"]').addClass('d-none').removeClass('animate-slide-in-left');

            // Tampilkan form yang sesuai dengan animasi
            const $targetForm = $(targetFormId);
            const $targetGuide = $(targetGuideId);
            $targetForm.removeClass('d-none').addClass('animate-slide-in-left');
            $targetGuide.removeClass('d-none').addClass('animate-slide-in-left');
        });

        $(document).ready(function() {
            $('.side-menu').first().trigger('click');
            $('#hero_main_form').sortable({
                group: 'list',
                animation: 200,
                ghostClass: 'ghost',
                // onSort: update_misi_data,
            });

            @foreach ($pageData->dataKonten['hero_main'] as $key => $value)
                @if ($value['media_id'] && $value['media'])
                    add_to_preview(`{{ $value['media_id'] }}`, `{{ $value['media'] }}`,
                        'media_main_{{ $key }}_preview',
                        'hidden_inputs_hero_{{ $key }}',
                        'hero[]', false)
                @endif
            @endforeach
            @if ($pageData->dataKonten['infografis_main']['media_id'] && $pageData->dataKonten['infografis_main']['media'])
                add_to_preview(`{{ $pageData->dataKonten['infografis_main']['media_id'] }}`,
                    `{{ $pageData->dataKonten['infografis_main']['media'] }}`,
                    'media_infografis_preview',
                    'hidden_input_infografis',
                    'infografis', false)
            @endif
            @if ($pageData->dataKonten['pmb_main']['media_id'] && $pageData->dataKonten['pmb_main']['media'])
                add_to_preview(`{{ $pageData->dataKonten['pmb_main']['media_id'] }}`,
                    `{{ $pageData->dataKonten['pmb_main']['media'] }}`,
                    'media_pmb_preview',
                    'hidden_input_pmb',
                    'pmb', false)
            @endif
        });

        $(document).on('change', '.media_cropper', function(event) {
            $('#cropContainer [name="preview"]').val($(this).data('preview'))
            $('#cropContainer [name="hidden_container"]').val($(this).data('hidden_container'))
            $('#cropContainer [name="is_append"]').val($(this).data('append'))
            $('#cropContainer [name="media_name"]').val($(this).data('name'))

            if (cropper) {
                cropper.destroy();
            }
            const files = event.target.files;

            if (files.length > 0) {
                const file = files[0];
                if (file.type.startsWith('image/')) { // Ensure the file is an image
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        cropImage.attr('src', e.target.result);
                        cropModal.show();
                        cropper = new Cropper(cropImage[0], {
                            aspectRatio: 1920 / 1080, // Fixed aspect ratio
                            viewMode: 1,
                            dragMode: 'move', // Enable moving the image
                            scalable: false, // Enable resizing the image
                            zoomable: true, // Enable zooming
                            movable: true, // Enable moving
                            cropBoxResizable: false,
                            autoCropArea: 1,
                        });
                    };

                    reader.readAsDataURL(file);
                }
            }

            // Clear the input value
            $(this).val('');
        });

        $('#crop-save').on('click', function() {
            var id = $('#formData [name="id"]').val()
            var preview = $('#cropContainer [name="preview"]').val()
            var hidden_container = $('#cropContainer [name="hidden_container"]').val()
            var is_append = $('#cropContainer [name="is_append"]').val()
            var media_name = $('#cropContainer [name="media_name"]').val()

            if (cropper) {
                const croppedCanvas = cropper.getCroppedCanvas({
                    width: 1920,
                    height: 1080
                });
                const croppedImage = croppedCanvas.toDataURL('image/png');

                // Create hidden input to store the cropped image

                ajaxRequest({
                    link: `{{ url('admin/media/store') }}`,
                    data: {
                        id: id,
                        upload_file: croppedImage,
                    },
                    callback: function(origin, resp) {
                        if (resp.status) {
                            // Add cropped image to the preview
                            add_to_preview(resp.data.media_id, croppedImage, preview,
                                hidden_container,
                                media_name,
                                (is_append == 1 ? true : false))

                            cropper.destroy();
                            cropModal.hide();
                        }
                    }
                })
            }
        });

        $('#crop-cancel').on('click', function() {
            if (cropper) {
                cropper.destroy();
                cropModal.hide();
            }
        });

        $(document).on('click', '.btn-upload', function() {
            var id = $(this).data('id')
            $('#' + id).trigger('click')
        })

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
                        link: `{{ url('admin/konten-main/update') }}`,
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

        $(document).on('click', '.act-add', function() {
            var randomGenerate = Math.floor(Math.random() * 1000) + 1
            $('#hero_main_form').append(hero_main_form_generator(randomGenerate))
        })

        $(document).on('click', '.act-delete', function() {
            if (hero_main_form_counter() > 1) {
                var id = $(this).data('id')
                $('#media_main_card_' + id).remove()
            } else {
                Swal.fire({
                    html: 'Data hero minimal harus tersedia 1 data!',
                    icon: "error",
                    buttonsStyling: false,
                    customClass: {
                        confirmButton: "btn btn-danger"
                    }
                });
            }
        })

        function hero_main_form_counter() {
            return document.querySelectorAll('.hero_main_component').length
        }

        function hero_main_form_generator(seq) {
            return `<div class="d-flex flex-row mb-4 bg-light rounded hero_main_component" id="media_main_card_${seq}">
                        <div class="d-flex align-items-center justify-content-center"
                            style="width: 60px; text-align: center;">
                            <div class="d-flex align-items-center justify-content-center" style="width: 60px; text-align: center;">
                                <span class="d-none- d-md-inline">
                                    <a href="javascript:;" class="btn btn-icon btn-sm mh-25px mw-25px btn-light-danger act-delete " data-id="${seq}" jf-delete="${seq}" title="Hapus data">
                                        <i class="ki-outline ki-trash fs-3"></i>
                                    </a>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 py-4 px-4">
                            <div class="mb-4">
                                <x-form.input name="title_main[]" label="Title" value=""
                                    required />
                            </div>
                            <div class="mb-4">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <input type="file" class="d-none media_cropper"
                                            id="media_main_${seq}" name="media_main[]"
                                            data-preview="media_main_${seq}_preview"
                                            data-hidden_container="hidden_inputs_hero_${seq}"
                                            data-append="0" data-name="hero[]" />
                                        <x-btn type="secondary" class="btn-upload mb-1"
                                            data-id="media_main_${seq}">
                                            <i class="bi bi-upload"></i> Upload media
                                        </x-btn>
                                        <div class="fs-9">Media dapat berupa video atau gambar</div>
                                    </div>
                                    <div class="w-100px">
                                        <small>Preview</small>
                                        <div id="media_main_${seq}_preview">
                                            <small class="text-muted">No Preview</small>
                                        </div>
                                        <div id="hidden_inputs_hero_${seq}"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="pt-2 d-flex align-items-center justify-content-center"
                            style="width: 85px; text-align: center;">
                            <i class="ki-duotone ki-arrow-up-down fs-1 text-dark">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </div>
                    </div>`
        }
    </script>
@endpush
