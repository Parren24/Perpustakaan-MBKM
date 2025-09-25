@extends(request()->query('snap') == true ? 'layouts.snap' : 'layouts.apps')
@section('toolbar')
    <x-theme.toolbar :breadCrump="$pageData->breadCrump" :title="$pageData->title">
        <x-slot:tools>
        </x-slot:tools>
    </x-theme.toolbar>
@endsection


@section('content')
    <!--begin::Content container-->
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
            z-index: 1057;
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

        .container {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .top {
            display: flex;
            gap: 10px;
        }

        .mobile,
        .tablet {
            flex: 1;
            position: relative;
        }

        .bottom {
            display: flex;
            justify-content: center;
        }

        .desktop {
            width: 100%;
            position: relative;
        }

        .img-preview-data {
            width: 100%;
            height: auto;
            display: block;
        }

        .img-label {
            position: absolute;
            top: 0;
            left: 0;
            background: rgba(0, 0, 0, 0.6);
            color: white;
            padding: 5px;
            border: 1px solid black;
        }

        .card-overlay {
            position: absolute;
            bottom: 0;
            width: 100%;
            background: rgba(0, 0, 0, 0.6);
            color: white;
            padding: 10px;
            transition: background 0.3s ease, opacity 0.3s ease;
            opacity: 1;
        }

        .portfolio-card:hover .card-overlay {
            background: rgba(0, 0, 0, 0.8);
            opacity: 1;
        }

        .overlay-buttons {
            position: absolute;
            bottom: 10px;
            right: 10px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .portfolio-card:hover .overlay-buttons {
            opacity: 1;
        }

        .image-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }

        .image-modal img {
            max-width: 90%;
            max-height: 80%;
        }

        .image-modal.show {
            opacity: 1;
            visibility: visible;
        }
    </style>

    <div id="kt_app_content_container" class="app-container container-fluid" data-cue="slideInLeft" data-duration="1000"
        data-delay="0">
        <x-table.dttable-card-left :builder="$pageData->dataTable" :table_card="true" :covered="false" :responsive="false" draw_callback=""
            :order="false" :export="false" jf-data="media" jf-list="datatable">
            @slot('filter')
                <div class="row">
                    <div class="col-md-3">
                        <x-form.select name="status_slide" placeholder="Seluruh Status" required>
                            <option value="aktif" selected>Slide Aktif</option>
                            <option value="tidak aktif">Slide Tidak Aktif</option>
                        </x-form.select>
                    </div>
                </div>
            @endslot
            @slot('action')
            <x-btn type="secondary" class="act-sequence p-1 btn-icon" title="Konfig urutan slide">
                <i class="bi bi-list-stars fs-2"></i>
            </x-btn>
                <x-btn type="primary" class="act-add w-100 w-md-auto" jf-add="media">
                    <i class="bi bi-plus fs-2"></i> Tambah data
                </x-btn>
            @endslot
        </x-table.dttable-card-left>
    </div>

    <x-modal id="modalForm" type="centered" :static="true" size="" jf-modal="media" title="Konten Slide">
        <form id="formData" class="needs-validation" jf-form="media">
            <input type="hidden" name="preview" value="">
            <input type="hidden" name="hidden_container" value="">
            <input type="hidden" name="is_append" value="">
            <input type="hidden" name="media_name" value="">
            <input type="hidden" name="id" value="">
            <div class="mb-4">
                <x-form.input name="judul_slide" label="Judul Slide Utama" value="" required />
            </div>
            <div class="mb-4">
                <label class="form-label">
                    Media Slide Utama
                    <span class="text-danger">*</span>
                </label>
                <div class="d-flex w-100 justify-content-center">
                    <div class="portfolio-card position-relative p-4 rounded rounded-4 border border-2 w-md-350px">
                        <div class="container">
                            <div class="top">
                                <div class="mobile image-container"
                                    onclick="document.querySelector('input[type=file][data-name=mobile]').click()">
                                    <span class="img-label">Mobile</span>
                                    <img class="img-preview-data rounded rounded-4" id="media_mobile_preview"
                                        src="https://placehold.co/225x400?text=9:16" alt="Mobile Placeholder">
                                    <div class="action-buttons"><a href="javascript:;"
                                            class="btn btn-icon btn-sm mh-25px mw-25px btn-light act-upload"
                                            title="Hapus data"><i class="bi bi-upload fs-4"></i></a></div>
                                </div>
                                <div class="tablet image-container"
                                    onclick="document.querySelector('input[type=file][data-name=tablet]').click()">
                                    <span class="img-label">Tablet</span>
                                    <img class="img-preview-data rounded rounded-4" id="media_tablet_preview"
                                        src="https://placehold.co/250x340?text=9:10" alt="Tablet Placeholder">
                                    <div class="action-buttons"><a href="javascript:;"
                                            class="btn btn-icon btn-sm mh-25px mw-25px btn-light act-upload"
                                            title="Hapus data"><i class="bi bi-upload fs-4"></i></a></div>
                                </div>
                            </div>
                            <div class="bottom">
                                <div class="desktop image-container"
                                    onclick="document.querySelector('input[type=file][data-name=desktop]').click()">
                                    <span class="img-label">Desktop</span>
                                    <img class="img-preview-data rounded rounded-4" id="media_desktop_preview"
                                        src="https://placehold.co/400x225?text=16:9" alt="Desktop Placeholder">
                                    <div class="action-buttons"><a href="javascript:;"
                                            class="btn btn-icon btn-sm mh-25px mw-25px btn-light act-upload"
                                            title="Hapus data"><i class="bi bi-upload fs-4"></i></a></div>
                                    {{-- <div class="d-flex align-items-center flex-row flex-wrap mt-4"
                                            id="media_desktop_preview">
                                        </div> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mb-4 d-none">
                <x-form.input type="file" label="Slide Mode Desktop" class="media_cropper"
                    data-preview="media_desktop_preview" data-hidden_container="hidden_inputs_desktop" data-append="0"
                    data-name="desktop" />
                {{-- <div class="d-flex align-items-center flex-row flex-wrap mt-4" id="media_desktop_preview">
                    </div> --}}
                <div id="hidden_inputs_desktop"></div>
                <x-form.input type="file" label="Slide Mode Tablet" class="media_cropper"
                    data-preview="media_tablet_preview" data-hidden_container="hidden_inputs_tablet" data-append="0"
                    data-name="tablet" />
                {{-- <div class="d-flex align-items-center flex-row flex-wrap mt-4" id="media_tablet_preview">
                    </div> --}}
                <div id="hidden_inputs_tablet"></div>
                <x-form.input type="file" label="Slide Mode Mobile" class="media_cropper"
                    data-preview="media_mobile_preview" data-hidden_container="hidden_inputs_mobile" data-append="0"
                    data-name="mobile" />
                {{-- <div class="d-flex align-items-center flex-row flex-wrap mt-4" id="media_mobile_preview">
                    </div> --}}
                <div id="hidden_inputs_mobile"></div>
            </div>
        </form>
        @slot('action')
            <x-btn.form action="save" class="act-save" jf-save="media" />
        @endslot
    </x-modal>

    <x-modal id="modalSequence" type="centered" :static="true" size="" title="Urutan Slide Utama"
        jf-modal="sequence">
        <form id="formData" class="needs-validation" jf-form="sequence">
            <div id="items-sequence" class="list-group col rounded-2 bg-theme">
            </div>
        </form>
        @slot('action')
            <x-btn.form action="save" class="act-save" jf-save="sequence" />
        @endslot
    </x-modal>

    <div id="cropModal" class="bg-light rounded rounded-4"
        style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 1056; padding: 20px; box-shadow: 0 4px 8px rgba(0,0,0,0.2);">
        <div id="cropContainer" class="p-2">
            <img id="cropImage" style="max-height: 500px;" />
        </div>
        <div class="d-flex justify-content-end w-100">
            <button id="cropCancel" type="button" class="btn btn-sm btn-secondary">Close</button>
            <x-btn type="primary" id="cropSave">Crop & Save</x-btn>
        </div>
    </div>

    <div class="image-modal" id="imageModal" onclick="closeModal()">
        <img src="" alt="Full Image" id="imageFull">
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <script src="https://unpkg.com/sortablejs-make/Sortable.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-sortablejs@latest/jquery-sortable.js"></script>

    <x-script.crud2></x-script.crud2>
    <script>
        let tableId = '{{ $pageData->dataTable->getTableId() }}'
        const cropModal = $('#cropModal');
        const cropImage = $('#cropImage');
        const media_mobile_preview = 'https://placehold.co/225x400?text=9:16'
        const media_tablet_preview = 'https://placehold.co/250x340?text=9:10'
        const media_desktop_preview = 'https://placehold.co/400x225?text=16:9'
        let cropper;

        function openModal(filepath_media) {
            imageFull.src = ''
            setTimeout(() => { // Small delay to ensure loading screen shows
                imageFull.src = filepath_media + '?' + new Date().getTime();
                document.getElementById('imageModal').classList.add('show');
            }, 100);
        }

        function closeModal() {
            document.getElementById('imageModal').classList.remove('show');
        }

        jForm.init({
            name: "media",
            url: {
                add: `{{ url('admin/konten-slide/store') }}`,
                edit: `{{ url('admin/konten-slide/data/detail') }}`,
                update: `{{ url('admin/konten-slide/update') }}`,
                delete: `{{ url('admin/konten-slide/destroy') }}`
            },
            onAdd: function(data) {
                $('#media_mobile_preview').attr('src', media_mobile_preview)
                $('#media_tablet_preview').attr('src', media_tablet_preview)
                $('#media_desktop_preview').attr('src', media_desktop_preview)
            },
        })

        $(document).on('click', '.act-save[jf-save="sequence"]', function() {
            var sort = $('#items-sequence').sortable('toArray');
            var formData = new FormData();
            $.each(sort, function(k, v) {
                formData.append('id[]', v)
            })

            ajaxRequest({
                link: `{{ url('admin/konten-slide/update/status-sequence') }}`,
                data: [formData],
                block: true,
                swal_success: true,
                callback: function(origin, resp) {
                    if (resp.status) {
                        $('#modalSequence').modal('hide')
                        $('#' + tableId).DataTable().ajax.reload(null, false)
                    }
                }
            })
        })

        $(document).on('click', '.act-sequence', function() {
            ajaxRequest({
                link: `{{ url('admin/konten-slide/data/slide-aktif-detail') }}`,
                callback: function(origin, resp) {
                    if (resp.status) {
                        seq = ''

                        $.each(resp.data, function(index, item) {
                            seq += `<div id="item-${item.id}" data-id="${item.id}" class="d-flex flex-row p-4 gap-1">
                                        <div class="d-flex flex-grow-1">
                                            ${item.judul_slide}
                                        </div>
                                        <div class="d-flex flex-row">
                                            <i class="ki-solid ki-arrow-up-down d-flex me-4 align-items-center fs-4"></i>
                                        </div>
                                    </div>`
                        })
                        $('#items-sequence').html(seq).sortable({
                            group: 'list',
                            animation: 200,
                            ghostClass: 'ghost',
                        });

                        $('#modalSequence').modal('show')
                    }
                }
            })
        })

        $(document).on('click', '.act-status_slide', function() {
            id = $(this).data('id')
            status_slide = $(this).data('status_slide')

            Swal.fire({
                title: "Anda yakin ?",
                text: "Data yang telah dinonaktifkan dapat dikembalikan lagi nanti",
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
                        link: `{{ url('admin/konten-slide/update/status-slide') }}`,
                        data: {
                            id: id,
                            status_slide: status_slide,
                        },
                        callback: function(origin, resp) {
                            if (resp.status) {
                                $('#' + tableId).DataTable().ajax.reload(null, false)
                            }
                        }
                    })
                }
            });
        })

        function openModal(filepath_media) {
            // var url = element.querySelector('img').getAttribute('data-url');
            // var imageFull = document.getElementById('imageFull');
            imageFull.src = ''
            setTimeout(() => { // Small delay to ensure loading screen shows
                imageFull.src = filepath_media + '?' + new Date().getTime();
                document.getElementById('imageModal').classList.add('show');
            }, 100);
        }

        function card(full) {
            return `<div class="col-md-2 mb-6">
            <div class="portfolio-card position-relative p-4 rounded rounded-4 border border-2">
                <div class="container">
                    <div class="top">
                        <div class="mobile">
                            <span class="img-label">Mobile</span>
                            <img class="img-preview-data rounded rounded-4" src="${full.media_mobile}" alt="${full.judul_slide+' mobile'}" onclick="openModal('${full.media_mobile}')">
                        </div>
                        <div class="tablet">
                            <span class="img-label">Tablet</span>
                            <img class="img-preview-data rounded rounded-4" src="${full.media_tablet}" alt="${full.judul_slide+' tablet'}" onclick="openModal('${full.media_tablet}')">
                        </div>
                    </div>
                    <div class="bottom">
                        <div class="desktop">
                            <span class="img-label">Desktop</span>
                            <img class="img-preview-data rounded rounded-4" src="${full.media_desktop}" alt="${full.judul_slide+' desktop'}" onclick="openModal('${full.media_desktop}')">
                        </div>
                    </div>
                </div>
                <div class="card-overlay d-flex justify-content-between align-items-center">
                    <div class="d-flex flex-row w-100">${full.seq ? `
                                                                                                                <span class="symbol symbol-20px bg-primary h-20px w-20px me-3 d-flex align-items-center justify-content-center fs-8">
                                                                                                                    #${full.seq}
                                                                                                                </span>` : ''}
                        <span>
                            ${full.judul_slide}
                        </span>
                    </div>
                    <div class="overlay-buttons">
                        <a class="btn btn-sm mh-25px btn-${full.status_slide == 'aktif' ? 'light-primary' : 'secondary'} act-status_slide me-1 fs-8 px-4 py-1"
                            data-id="${full.id}" data-status_slide="${full.status_slide =='aktif' ? 'tidak aktif':'aktif'}">${full.status_slide}</a>
                        <a class="btn btn-icon btn-sm mh-25px mw-25px btn-light-danger act-delete me-1"
                            jf-delete="${full.id}"><i class="ki-outline ki-trash fs-3"></i></a>
                    </div>
                </div>
            </div>
            `
        }

        $('.media_cropper').on('change', function(event) {
            name = $(this).data('name');

            $('#formData [name="preview"]').val($(this).data('preview'));
            $('#formData [name="hidden_container"]').val($(this).data('hidden_container'));
            $('#formData [name="is_append"]').val($(this).data('append'));
            $('#formData [name="media_name"]').val(name);

            if (cropper) {
                cropper.destroy();
            }

            const files = event.target.files;
            let aspectRatio;

            // Set aspect ratio based on name
            switch (name) {
                case 'tablet':
                    aspectRatio = 9 / 12;
                    break;
                case 'mobile':
                    aspectRatio = 9 / 16;
                    break;
                case 'desktop':
                    aspectRatio = 16 / 9;
                    break;
                default:
                    aspectRatio = 1; // Default to square if no match
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
            var id = $('#formData [name="id"]').val();
            var preview = $('#formData [name="preview"]').val();
            var hidden_container = $('#formData [name="hidden_container"]').val();
            var is_append = $('#formData [name="is_append"]').val();
            var media_name = $('#formData [name="media_name"]').val();

            if (cropper) {
                // Set dynamic resolution while keeping the aspect ratio fixed
                const croppedCanvas = cropper.getCroppedCanvas();

                const croppedImage = croppedCanvas.toDataURL('image/png');

                ajaxRequest({
                    link: `{{ url('admin/media/store') }}`,
                    data: {
                        upload_file: croppedImage,
                    },
                    callback: function(origin, resp) {
                        if (resp.status) {
                            // Add cropped image to the preview
                            add_to_preview(resp.data.media_id, croppedImage, preview, hidden_container,
                                media_name, (is_append == 1 ? true : false));

                            cropper.destroy();
                            cropModal.hide();
                        }
                    }
                });
            }
        });

        $('#cropCancel').on('click', function() {
            if (cropper) {
                cropper.destroy();
                cropModal.hide();
            }
        });

        function add_to_preview(media_id, croppedImage, previewContainerId, hiddenInputsContainerId, media_name = "",
            is_append = true) {
            const previewContainer = $('#' + previewContainerId);
            const hiddenInputsContainer = $('#' + hiddenInputsContainerId);

            // Ensure previewContainer is an <img> element
            if (previewContainer.is('img')) {
                // Force image reload by replacing the element
                let newImg = $('<img>')
                    .attr('src', croppedImage)
                    .addClass('img-preview-data rounded rounded-4')
                    .attr('id', previewContainerId) // Keep same ID
                    .css({
                        'width': '100%', // Example style
                    });

                previewContainer.replaceWith(newImg);
            } else {
                // If previewContainer is a div, append or replace an image inside it
                let img = $('<img>').attr('src', croppedImage).addClass('preview-image');

                if (!is_append) {
                    previewContainer.html(img); // Replace existing image
                } else {
                    previewContainer.append(img); // Append new image
                }
            }

            document.querySelectorAll('input[type="hidden"][name="media_id_' + media_name + '[]"]').forEach(input => {
                input.name = 'delete_media_id_' + media_name + '[]';
            });

            // Create hidden input to store the media ID
            let hiddenInput = $('<input>')
                .attr('type', 'hidden')
                .attr('name', 'media_id_' + media_name + '[]')
                .val(media_id);

            hiddenInputsContainer.append(hiddenInput);
        }
    </script>
@endpush
