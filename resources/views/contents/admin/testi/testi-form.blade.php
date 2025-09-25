@extends(request()->query('snap') == true ? 'layouts.snap' : 'layouts.apps')
@section('toolbar')
    <x-theme.toolbar :breadCrump="$pageData->breadCrump" :title="$pageData->title">
        <x-slot:tools>
            <x-theme.back link="{{ route('app.testi.index') }}"></x-theme.back>
        </x-slot:tools>
    </x-theme.toolbar>
@endsection


@section('content')
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-fluid" data-cue="slideInLeft" data-duration="1000"
        data-delay="0">
        <form id="formData" action="{{ route('app.testi.update') }}" class="needs-validation" jf-form="testi">
            <div class="row" data-cue="slideInLeft" data-duration="1000" data-delay="0">
                <div class="col-md-7">
                    <x-card>
                        <x-form.input class="mb-2" type="hidden" name="id"
                            value="{{ $pageData->dataTesti['id'] }}"></x-form.input>
                        <x-form.textarea class="mb-2" type="text" label="Nama Alumni" name="nama_alumni" required>
                            {{ $pageData->dataTesti['nama_alumni'] }}
                        </x-form.textarea>
                        <div class="row">
                            <div class="col-md-4">
                                <x-form.select class="mb-2" label="Status" name="status_testi" value=""
                                    :search="false" required>
                                    <option value="draft"
                                        {{ $pageData->dataTesti['status_testi'] == 'draft' ? 'selected' : '' }}>Draft
                                    </option>
                                    <option value="published"
                                        {{ $pageData->dataTesti['status_testi'] == 'published' ? 'selected' : '' }}>
                                        Published</option>
                                    <option value="archived"
                                        {{ $pageData->dataTesti['status_testi'] == 'archived' ? 'selected' : '' }}>
                                        Archived</option>
                                </x-form.select>
                            </div>
                            <div class="col-md-4">
                                <x-form.select class="mb-2" label="Angkatan" name="angkatan" value=""
                                    :search="true" required>
                                    @for ($i = 2001; $i <= date('Y'); $i++)
                                        <option value="{{ $i }}"
                                            {{ $pageData->dataTesti['angkatan'] == $i ? 'selected' : '' }}>
                                            {{ $i }}</option>
                                    @endfor
                                </x-form.select>
                            </div>

                            <div class="col-md-4">
                                <x-form.select class="mb-2" label="Program Studi" name="prodi_id" value=""
                                    :search="true" required>
                                    @foreach ($pageData->dataProdi as $row)
                                        <option value="{{ $row['id'] }}"
                                            {{ $pageData->dataTesti['prodi_id'] == $row['id'] ? 'selected' : '' }}>
                                            {{ $row['text'] }}
                                        </option>
                                    @endforeach
                                </x-form.select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <x-form.select name="testi_kategori[]" class="mb-2" label="Kategori" required>
                                    @foreach ($pageData->dataKategori as $row)
                                        <option value="{{ $row['id'] }}"
                                            {{ $pageData->dataTesti['testi_kategori'] && $pageData->dataTesti['testi_kategori'][0] == $row['id'] ? 'selected' : '' }}>
                                            {{ $row['text'] }}
                                        </option>
                                    @endforeach
                                </x-form.select>
                            </div>
                            <div class="col-md-4">
                                <x-form.input class="mb-2" type="text" label="Tempat Kerja" name="tempat_kerja_alumni"
                                    value="{{ $pageData->dataTesti['tempat_kerja_alumni'] }}" required></x-form.input>
                            </div>

                            <div class="col-md-4">
                                <x-form.input class="mb-2" type="text" label="Posisi Kerja" name="posisi_alumni"
                                    value="{{ $pageData->dataTesti['posisi_alumni'] }}" required></x-form.input>
                            </div>
                        </div>
                        <x-form.textarea class="mb-2" data-tinymce="advance" rows="25" label="Isi Testi"
                            name="isi_testi" value="">{{ $pageData->dataTesti['isi_testi'] }}</x-form.textarea>
                    </x-card>
                </div>
                <div class="col-md-5">
                    <x-card>
                        <a href="javascript:;"
                            class="d-flex rounded border-2 border-dashed border-gray-300 w-100 min-h-200px mb-2 justify-content-center align-items-center position-relative"
                            id="fotoContent">
                            @if ($pageData->dataTesti['media_cover'])
                                <img src="{{ $pageData->dataTesti['media_cover'] }}" class="w-100 h-auto rounded">
                            @endif
                            <span
                                class="text-gray-600 p-2 px-3 bg-gray-100 bg-opacity-20 rounded position-absolute top-50 start-50 translate-middle">Foto
                                Alumni</span>
                        </a>
                        <x-form.input class="mb-2" type="file" label="" name="upload_file" value=""
                            class="d-none"></x-form.input>
                        <x-btn.form action="save" class="w-100 mt-6" jf-save="testi"></x-btn.form>
                    </x-card>
                </div>
            </div>
        </form>
    </div>

    <x-modal id="modalForm" type="centered" :static="true" size="lg" title="Pilih Cover Konten">
        <form class="needs-validation">
            <div class="form-group mb-2">
                <div class="input-group mb-2 mb-md-0">
                    <input type="file" class="form-control" id="image-input" accept="image/*">
                    <span class="input-group-text fs-8 fw-bold">
                        JPG,PNG | MAX 5 Mb
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

        jForm.init({
            name: "testi",
            base_url: `{{ route('app.testi.index') }}`,
            onSave: function(data) {
                if (data) location.reload()
            }
        })

        $(document).on('change', '[name="level"]', function() {
            $('[name="level_id"]').html('')
            if ($(this).val() == 'main-site') {
                $('[name="level_id').prop('disabled', true);
            } else {
                $('[name="level_id').prop('disabled', false);
                if ($(this).val() == 'jurusan-site') {
                    $.each(dataJurusan, function(index, item) {
                        var option = $('<option></option>')
                            .attr('value', item.id)
                            .text(item.text);

                        $('[name="level_id"]').append(option);
                    });
                } else if ($(this).val() == 'prodi-site') {
                    $.each(dataProdi, function(index, item) {
                        var option = $('<option></option>')
                            .attr('value', item.id)
                            .text(item.text);

                        $('[name="level_id"]').append(option);
                    });
                }
            }

            $('[name="level_id"]').val('').trigger('change');
        });

        $(document).on('click', '#fotoContent', function() {
            if (cropper) {
                cropper.destroy();
            }
            $('[id="image-input"]').val('')
            image.src = '';
            $('#modalForm').modal('show')
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
                    aspectRatio: 9 / 16,
                    preview: '.preview',
                    cropBoxResizable: true, // Mencegah crop box di-resize
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
                dataURL = cropper.getCroppedCanvas({
                    width: 720,
                    height: 400
                }).toDataURL('image/jpeg');

                $('#fotoContent > img').remove()
                $('#fotoContent').append(`<img src="${dataURL}" class="w-100 h-auto rounded">`)

                cropper.getCroppedCanvas({
                    width: 720,
                    height: 400
                }).toBlob(function(blob) {
                    var file = new File([blob], 'cover.jpg', {
                        type: 'image/jpeg'
                    });

                    // Set the File object to the input element
                    var fileInput = document.getElementsByName('upload_file')[0];
                    var dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    fileInput.files = dataTransfer.files;
                }, 'image/jpeg');
            }
        })
    </script>
@endpush
