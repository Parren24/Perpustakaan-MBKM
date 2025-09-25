@extends(request()->query('snap') == true ? 'layouts.snap' : 'layouts.apps')
@section('toolbar')
    <x-theme.toolbar :breadCrump="$pageData->breadCrump" :title="$pageData->title">
        <x-slot:tools>
        </x-slot:tools>
    </x-theme.toolbar>
@endsection


@section('content')
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-fluid" data-cue="slideInLeft" data-duration="1000"
        data-delay="0">
        <div class="row">
            <div class="col-md-8">
                <x-table.dttable :builder="$pageData->dataTable" class="align-middle" :responsive="false" jf-data="partner"
                    jf-list="datatable">
                    @slot('filter')
                        <div class="row">
                        </div>
                    @endslot
                    @slot('action')
                        <x-btn type="primary" class="act-add" jf-add="partner">
                            <i class="bi bi-plus fs-2"></i> Tambah data
                        </x-btn>
                    @endslot
                </x-table.dttable>
            </div>
        </div>
    </div>

    <x-modal id="modalForm" type="centered" :static="true" size="" jf-modal="partner" title="Partner">
        <form id="formData" class="needs-validation" jf-form="partner">
            <input type="hidden" name="id" value="">
            <x-form.select class="mb-2" name="jenis_partner" label="Jenis Partner" data-placeholder=". . . . . . . . . .">
                <option value="Industri">Industri</option>
                <option value="Instansi">Instansi</option>
                <option value="Institusi">Institusi</option>
            </x-form.select>
            <x-form.input class="mb-2" type="text" label="Nama Partner" name="nama_partner" value=""
                required></x-form.input>
            <x-form.input type="text" class="mb-2" name="url_partner" label="Link Partner" required />
            <x-form.input class="mb-2" type="file" label="" name="upload_file" value=""
                class="d-none"></x-form.input>
            <x-form.textarea class="mb-2" name="deskripsi_partner" label="Deskripsi Partner" value="" />
            <x-form.select class="mb-2" label="Status Partner" name="status_partner" required>
                <option value="aktif">Aktif</option>
                <option value="tidak aktif">Tidak Aktif</option>
            </x-form.select>
            <a href="javascript:;"
                class="d-flex rounded border-2 border-dashed border-gray-300 w-100 min-h-200px my-2 justify-content-center align-items-center position-relative"
                id="coverContent">
                <span
                    class="text-gray-600 p-2 px-3 bg-gray-100 bg-opacity-20 rounded position-absolute top-50 start-50 translate-middle">
                    Logo Partner
                </span>
            </a>
        </form>
        @slot('action')
            <x-btn.form action="save" class="act-save" jf-save="partner" />
        @endslot
    </x-modal>

    <x-modal id="modalCropper" type="centered" :static="true" size="lg" title="Pilih Cover Konten">
        <form class="needs-validation">
            <div class="form-group mb-2">
                <div class="input-group mb-2 mb-md-0">
                    <input type="file" class="form-control" id="image-input" accept="image/*">
                    <span class="input-group-text fs-8 fw-bold">
                        PNG | MAX 5 Mb
                    </span>
                </div>
            </div>

            <div class="form-group mb-2">
                <div class="d-flex w-100 h-450px border rounded border-gray-300 justify-content-center">
                    <img id="image" class="h-100 w-auto">
                </div>
            </div>
        </form>
        @slot('action')
            <x-btn.form action="save" class="act-save" text="Set Cover" />
        @endslot
    </x-modal>
@endsection

@push('scripts')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

    <x-script.crud2></x-script.crud2>
    <script>
        var cropper;
        var image = document.getElementById('image');
        var imageInput = document.getElementById('image-input');
        let tableId = '{{ $pageData->dataTable->getTableId() }}'

        jForm.init({
            name: "partner",
            url: {
                add: `{{ route('app.master.store', ['param1' => 'partner']) }}`,
                update: `{{ route('app.master.update', ['param1' => 'partner']) }}`,
                edit: `{{ route('app.master.data', ['param1' => 'partner-detail']) }}`,
                delete: `{{ route('app.master.destroy', ['param1' => 'partner']) }}`,
            },
            onAdd: function() {
                $('#formData [name="status_partner"]').val('aktif').trigger('change')
                $('#coverContent').find('img').remove();
            },
            onEdit: function(data) {
                $('#coverContent').find('img').remove();
                if (data.logo_partner)
                    $('#coverContent')
                    .append(
                        `<img src="${data.logo_partner}" class="img-fluid" style="max-height: 150px; object-fit: contain;">`
                    );
            }
        })

        function renderStatus(data) {
            return `
                <span class="badge badge-secondary text-${data == 'aktif' ? 'success' : 'gray-500'} p-1">${data.toUpperCase()}</span>
            `
        }

        $(document).on('click', '#coverContent', function() {
            if (cropper) {
                cropper.destroy();
            }
            $('[id="image-input"]').val('')
            image.src = '';
            $('#modalCropper').modal('show')
        })

        imageInput.addEventListener('change', function(e) {
            var files = e.target.files;
            var done = function(url) {
                // Jika cropper sudah ada, hancurkan terlebih dahulu
                if (cropper) {
                    cropper.destroy();
                }

                // Set gambar baru
                image.src = url;

                // Initialize cropper
                cropper = new Cropper(image, {
                    aspectRatio: 1,
                    preview: '.preview',
                    cropBoxResizable: false, // Mencegah crop box di-resize
                    cropBoxMovable: true, // Memungkinkan crop box dipindahkan
                    dragMode: 'move', // Mode drag untuk memindahkan gambar
                });
            };

            var reader;
            var file;
            if (files && files.length > 0) {
                file = files[0];
                if (URL) {
                    done(URL.createObjectURL(file));
                } else if (FileReader) {
                    reader = new FileReader();
                    reader.onload = function(e) {
                        done(reader.result);
                    };
                    reader.readAsDataURL(file);
                }
            }
        });

        $(document).on('click', '.act-save', function() {
            if (cropper) {
                var mimeType = "image/jpeg"; // default jpg
                var fillColor = "#fff";

                // Coba cek dari input file
                var fileInput = document.getElementsByName('upload_file')[0];
                if (fileInput && fileInput.files.length > 0) {
                    var originalType = fileInput.files[0].type;
                    if (originalType === "image/png") {
                        mimeType = "image/png";
                        fillColor = null; // biarkan transparan
                    }
                } else {
                    // fallback: cek src cropper (misalnya .png / .jpg di URL)
                    var src = cropper.image.src;
                    if (src.match(/\.png$/i)) {
                        mimeType = "image/png";
                        fillColor = null;
                    }
                }

                // Preview
                var dataURL = cropper.getCroppedCanvas({
                    width: 150,
                    height: 150,
                    fillColor: fillColor
                }).toDataURL(mimeType);

                $('#coverContent > img').remove();
                $('#coverContent').append(`<img src="${dataURL}" class="h-auto rounded">`);

                // Blob untuk simpan file
                cropper.getCroppedCanvas({
                    width: 150,
                    height: 150,
                    fillColor: fillColor
                }).toBlob(function(blob) {
                    var file = new File([blob], 'cover' + (mimeType === "image/png" ? ".png" : ".jpg"), {
                        type: mimeType
                    });

                    // hanya set ke input kalau memang ada input upload_file
                    if (fileInput) {
                        var dataTransfer = new DataTransfer();
                        dataTransfer.items.add(file);
                        fileInput.files = dataTransfer.files;
                    }
                }, mimeType);
            }
        });
    </script>
@endpush
