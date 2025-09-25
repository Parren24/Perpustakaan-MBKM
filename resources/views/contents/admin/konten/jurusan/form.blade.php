@extends(request()->query('snap') == true ? 'layouts.snap' : 'layouts.apps')
@section('toolbar')
    <x-theme.toolbar :breadCrump="$pageData->breadCrump" :title="$pageData->title">
        <x-slot:tools>
            <x-theme.back link="{{ url('admin/konten-jurusan') }}" />
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
            <div class="col-xl-4 col-12">
                <form id="formData" class="needs-validation">
                    <div class="accordion accordion-icon-toggle mb-4" id="accordionForm">
                        <div class="mb-1">
                            <div class="accordion-header p-2 rounded bg-light rounded-2 d-flex" data-bs-toggle="collapse"
                                data-bs-target="#accordion_item_hero">
                                <span class="accordion-icon">
                                    <i class="ki-duotone ki-arrow-right fs-7 p-1"><span class="path1"></span><span
                                            class="path2"></span></i>
                                </span>
                                <span class="fs-7 fw-semibold mb-0 ms-4">Section Hero Page
                                </span>
                            </div>
                            <div id="accordion_item_hero"
                                class="fs-8 collapse hover-div border border-secondary rounded-4 p-4 collapse border border-1 show"
                                data-bs-parent="#accordionForm">
                                <input type="hidden" name="id" value="{{ $pageData->dataForm['id'] }}">
                                <input type="hidden" name="preview" value="">
                                <input type="hidden" name="hidden_container" value="">
                                <input type="hidden" name="is_append" value="">
                                <input type="hidden" name="media_name" value="">

                                <div class="mb-4">
                                    <x-form.input name="moto_hero" label="Moto Jurusan"
                                        value="{{ $pageData->dataForm['moto_hero'] ?? '' }}" required />
                                </div>
                                <div class="mb-4">
                                    <x-form.textarea name="deskripsi_hero" label="Deskripsi Jurusan" value=""
                                        rows="5" required>
                                        {{ $pageData->dataForm['deskripsi_hero'] ?? '' }}
                                    </x-form.textarea>
                                </div>
                                <div class="mb-4">
                                    <x-form.input type="file" label="Gambar Grid Hero" class="media_cropper"
                                        data-preview="media_hero_preview" data-hidden_container="hidden_inputs_hero"
                                        data-append="1" data-name="hero" />
                                    <div class="d-flex align-items-center flex-row flex-wrap mt-4" id="media_hero_preview">
                                    </div>
                                    <div id="hidden_inputs_hero"></div>
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
                                <span class="fs-7 fw-semibold mb-0 ms-4">Section Sambutan Ketua Jurusan
                                </span>
                            </div>
                            <div id="accordion_item_sambutan"
                                class="fs-8 collapse hover-div border border-secondary rounded-4 p-4 collapse border border-1"
                                data-bs-parent="#accordionForm">
                                <div class="mb-4">
                                    <x-form.input name="judul_sambutan" label="Judul Sambutan Ketua Jurusan"
                                        value="{{ $pageData->dataForm['judul_sambutan'] ?? '' }}" required />
                                </div>
                                <div class="mb-4">
                                    <x-form.textarea name="isi_sambutan" label="Isi Sambutan Ketua Jurusan" rows="5"
                                        required>
                                        {{ $pageData->dataForm['isi_sambutan'] ?? '' }}
                                    </x-form.textarea>
                                </div>
                                <div class="mb-4">
                                    <x-form.input type="file" label="Foto Ketua Jurusan" class="media_cropper"
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
                                data-bs-toggle="collapse" data-bs-target="#accordion_item_tentang">
                                <span class="accordion-icon">
                                    <i class="ki-duotone ki-arrow-right fs-7 p-1"><span class="path1"></span><span
                                            class="path2"></span></i>
                                </span>
                                <span class="fs-7 fw-semibold mb-0 ms-4">Section Tentang Jurusan
                                </span>
                            </div>
                            <div id="accordion_item_tentang"
                                class="fs-8 collapse hover-div border border-secondary rounded-4 p-4 collapse border border-1"
                                data-bs-parent="#accordionForm">
                                <div class="mb-4">
                                    <x-form.textarea name="isi_tentang" label="Deskripsi Singkat Jurusan" rows="5"
                                        required>
                                        {{ $pageData->dataForm['isi_tentang'] ?? '' }}
                                    </x-form.textarea>
                                </div>
                                <div class="mb-4">
                                    <x-form.input type="file" label="Gambar Jurusan" class="media_cropper"
                                        data-preview="media_tentang_preview" data-hidden_container="hidden_inputs_tentang"
                                        data-append="0" data-name="tentang" />
                                    <div class="d-flex align-items-center flex-row flex-wrap mt-4"
                                        id="media_tentang_preview">
                                    </div>
                                    <div id="hidden_inputs_tentang"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="my-4">
                    <div class="d-flex justify-content-end">
                        <x-btn.form action="save" class="act-save" jf-save="post" />
                    </div>
                </div>
            </div>
            <div class="col-xl-8 col-12 d-none d-xl-block">
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
                        link: `{{ url('admin/konten-jurusan/update') }}`,
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
            @if ($pageData->dataForm['media_hero'])
                @foreach ($pageData->dataForm['media_hero'] as $row)
                    add_to_preview(`{{ $row['id'] }}`, `{{ $row['base64'] }}`, 'media_hero_preview',
                        'hidden_inputs_hero', 'hero')
                @endforeach
            @endif
            @if ($pageData->dataForm['media_sambutan'])
                @foreach ($pageData->dataForm['media_sambutan'] as $row)
                    add_to_preview(`{{ $row['id'] }}`, `{{ $row['base64'] }}`, 'media_sambutan_preview',
                        'hidden_inputs_sambutan',
                        'sambutan', false)
                @endforeach
            @endif
            @if ($pageData->dataForm['media_tentang'])
                @foreach ($pageData->dataForm['media_tentang'] as $row)
                    add_to_preview(`{{ $row['id'] }}`, `{{ $row['base64'] }}`, 'media_tentang_preview',
                        'hidden_inputs_tentang',
                        'tentang', false)
                @endforeach
            @endif
        })

        $('.media_cropper').on('change', function(event) {
            $('#formData [name="preview"]').val($(this).data('preview'))
            $('#formData [name="hidden_container"]').val($(this).data(
                'hidden_container'))
            $('#formData [name="is_append"]').val($(this).data('append'))
            $('#formData [name="media_name"]').val($(this).data('name'))

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
                            aspectRatio: 1, // Fixed aspect ratio
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
                    link: `{{ url('admin/konten-jurusan/store/media') }}`,
                    data: {
                        id: id,
                        uploaded_files: croppedImage,
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
