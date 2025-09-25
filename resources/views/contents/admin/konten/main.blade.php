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

        #submit_section {
            position: fixed !important;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 9999;
            /* agar tidak transparan */
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.05);
            /* opsional: bayangan atas */
        }
    </style>


    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-fluid" data-cue="slideInLeft" data-duration="1000"
        data-delay="0">
        <div class="d-flex flex-row">
            <div class="d-lg-flex flex-column flex-lg-row-auto w-lg-225px me-3 d-none d-lg-block">
                {{-- <div class="d-lg-flex flex-column flex-lg-row-auto w-lg-225px me-3" data-kt-drawer="true"
                data-kt-drawer-name="end-sidebar" data-kt-drawer-activate="{default: true, lg: false}"
                data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'150px', '200px': '250px'}"
                data-kt-drawer-direction="end" data-kt-drawer-toggle="#toggleDetails"> --}}
                @foreach ($pageData->dataKonten as $key => $value)
                    <div
                        class="menu menu-column menu-rounded menu-state-bg menu-state-title-primary menu-state-icon-primary menu-state-bullet-primary px-2 py-1 fs-7">
                        <div class="menu-item">
                            <a href="#" class="menu-link side-menu {{ $key == 0 ? 'active' : '' }} text-gray-800"
                                data-id="{{ $key }}">
                                <span class="menu-icon">
                                    <i class="ki-solid ki-arrow-right"></i>
                                </span>
                                <span class="menu-title fw-bold">{{ $value['name'] }}</span>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="w-100 flex-lg-row-fluid h-650px" data-cue="slideInLeft" data-duration="1000" data-delay="0">
                <form id="formData" class="needs-validation">
                    <x-form.input name="id" type="hidden" value="{{ $pageData->dataPage['id'] }}" />
                    <div class="row">
                        {{-- @foreach ($pageData->dataValues as $key => $value) --}}
                        @foreach ($pageData->dataKonten as $key => $value)
                            <div class="col-lg-7 col-12">
                                <div class="{{ $key > 0 ? 'd-none' : '' }}" id="{{ $key }}_form">
                                    @foreach ($value['data'] as $keys => $values)
                                        @if (!empty($values))
                                            <div class="mb-4 h-100">
                                                <div class="d-flex flex-row mb-2 align-items-center">
                                                    <h3 class="flex-grow-1">Form {{ ucwords($keys) }}</h3>
                                                    @if ($value['type'] == 'hero')
                                                        <a href="javascript:;"
                                                            class="btn btn-icon btn-sm mh-25px mw-25px btn-primary act-add"
                                                            data-type="card" data-id="">
                                                            <i class="bi bi-plus fs-3"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                                <div class="separator separator-dashed border-gray-10 my-2"></div>
                                                <div class="h-550px" style="overflow-y: auto;">
                                                    @if ($value['type'] == 'hero')
                                                        <x-customs.konten.heroform :data="$values" :section_name="$value['name']"
                                                            :section_type="$value['type']">
                                                        </x-customs.konten.heroform>
                                                    @else
                                                        <x-customs.konten.formgenerator :data="$values" :section_name="$value['name']"
                                                            :section_type="$value['type']">
                                                        </x-customs.konten.formgenerator>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-lg-5 col-12 d-none d-lg-block">
                                <div class="{{ $key > 0 ? 'd-none' : '' }}" id="{{ $key }}_guide">
                                    <div class="mb-4 h-100">
                                        <div class="d-flex flex-row mb-2 align-items-center">
                                            <h3 class="flex-grow-1">Guideline</h3>
                                        </div>
                                        <div class="separator separator-dashed border-gray-10 my-2"></div>
                                        @if ($value['guide'] && !empty($value['guide']))
                                            <div class="h-450px" style="overflow-y: auto;">
                                                <div class="d-flex justify-content-center w-100 mb-4">
                                                    <img src="{{ asset('theme/media/image/guides/' . $value['guide']['section_image']) }}"
                                                        alt="Guideline" class="w-100 rounded">
                                                </div>
                                                <div class="d-flex flex-column w-100 mb-4 border border-1 p-2 rounded">
                                                    <table class="fs-8">
                                                        @foreach ($value['guide']['section_guide'] as $tag => $guide)
                                                            <tr>
                                                                <td width="75px"><span
                                                                        class="badge badge-light text-primary p-1">{{ $tag }}</span>
                                                                </td>
                                                                <td><span class="">{{ $guide }}</span></td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </div>
                                            </div>
                                        @else
                                            <x-alert type="warning">Guide not available</x-alert>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                </form>
            </div>
            <div class="mb-0 justify-content-end" id="submit_section" style="">
                <div class="p-3">
                    <div class="d-flex w-100 justify-content-end">
                        <x-btn.form action="save" class="act-save" text="Simpan data" />
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
        let cropper;

        $(document).on('click', '.act-add[data-type="card"]', function() {
            var rand = Math.floor(Math.random() * 1000) + 1
            var card = `
            <div class="d-flex flex-row mb-4 bg-theme rounded" id="${rand}_hero_card">
                <input type="hidden" name="hero_key[]" value="${rand}">
                <div class="d-flex align-items-center justify-content-center" style="width: 60px; text-align: center;">
                    <span class="d-none- d-md-inline">
                        <a href="javascript:;" class="btn btn-icon btn-sm mh-25px mw-25px btn-light-danger act-delete " data-id="${rand}_hero_card" title="Hapus data">
                            <i class="ki-outline ki-trash fs-3"></i>
                        </a>
                    </span>
                </div>
                <div class="flex-grow-1 py-4 px-4">
                    <label class="form-label">
                        Title
                        <span class="text-danger">*</span>
                    </label>
                    <div id="title">
                        <div id="${rand}_title_div">
                            <div class="d-flex align-items-center mb-3 gap-2" id="title_hero_${rand}_${rand}">
                                <div class="input-group mb-2 mb-md-0">
                                    <input type="text" class="form-control form-control-sm " placeholder=". . . . . . . . . ." name="title_hero_${rand}[]" required="" value="">

                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="d-none- d-md-inline">
                                        <a href="javascript:;" class="btn btn-icon btn-sm mh-25px mw-25px btn-secondary act-add " data-id="" data-key="${rand}" data-to="${rand}_title_div" data-type="tag" title="Lainnya">
                                            <i class="bi bi-plus text-primary fs-3"></i>
                                        </a>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="media_id">
                        <div id="1_media_id_div">
                            <div class="my-4">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <input type="file" class=" media_cropper d-none" id="${rand}_media_id_${rand}_media" name="" data-width="1920" data-height="1080" data-preview="${rand}_media_id_${rand}_preview" data-hidden_container="${rand}_media_id_${rand}_container" data-append="0" data-name="hero_${rand}[]">
                                        <a href="javascript:;" class="btn text-nowrap btn-sm btn-secondary btn-upload mb-1" data-id="${rand}_media_id_${rand}_media">
                                            <i class="bi bi-upload"></i>
                                            Upload
                                            media
                                        </a>
                                        <div class="fs-9">Media
                                            dapat
                                            berupa
                                            video
                                            atau gambar</div>
                                    </div>
                                    <div class="w-100px">
                                        <small class="text-gray-600">Preview</small>
                                        <div
                                            id="${rand}_media_id_${rand}_preview">
                                            <small class="text-muted">No
                                                Preview</small>
                                        </div>
                                        <div
                                            id="${rand}_media_id_${rand}_container">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pt-2 d-flex align-items-center justify-content-center" style="width: 85px; text-align: center;">
                    <i class="ki-duotone ki-arrow-up-down fs-1 text-dark">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                </div>
            </div>
            `
            $('.sortable').append(card)
        })

        $(document).on('click', '.act-add[data-type="tag"]', function() {
            var id = $(this).data('key')
            var to = $(this).data('to')
            var rand = Math.floor(Math.random() * 1000) + 1
            var inputTag = `
                <div class="d-flex align-items-center mb-3 gap-2"
                    id="${id}_${rand}">
                    <x-form.input
                        name="title_hero_${id}[]"
                        label=""
                        value=""
                        required />
                    <div
                        class="d-flex align-items-center">
                        <span class="d-none- d-md-inline">
                            <a href="javascript:;" class="btn btn-icon btn-sm mh-25px mw-25px btn-light-danger act-delete " data-type="tag" data-id="${id}_${rand}" title="Hapus data" draggable="false">
                                <i class="ki-outline ki-trash fs-3"></i>
                            </a>
                        </span>
                    </div>
                </div>`
            console.log(to)
            $('#' + to).append(inputTag)
        })

        $(document).on('click', '.act-delete', function() {
            // if (hero_main_form_counter() > 1) {
            var id = $(this).data('id')
            $('#' + id).remove()
            // } else {
            //     Swal.fire({
            //         html: 'Data hero minimal harus tersedia 1 data!',
            //         icon: "error",
            //         buttonsStyling: false,
            //         customClass: {
            //             confirmButton: "btn btn-danger"
            //         }
            //     });
            // }
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
                        link: `{{ route('app.konten.konten.update') }}`,
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

        $(document).on('click', '.btn-upload', function() {
            var id = $(this).data('id')
            $('#' + id).trigger('click')
        })

        $(document).ready(function() {
            $('.side-menu').first().trigger('click');
            $('.sortable').sortable({
                group: 'list',
                animation: 200,
                ghostClass: 'ghost',
                // onSort: update_misi_data,
            });
        })
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


        $(document).on('change', '.media_cropper', function(event) {
            $('#cropContainer [name="preview"]').val($(this).data('preview'))
            $('#cropContainer [name="hidden_container"]').val($(this).data('hidden_container'))
            $('#cropContainer [name="is_append"]').val($(this).data('append'))
            $('#cropContainer [name="media_name"]').val($(this).data('name'))

            var width = $(this).data('width')
            var height = $(this).data('height')

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
                            aspectRatio: width / height, // Fixed aspect ratio
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
                } else {
                    console.log('non image go here')
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
    </script>
@endpush
