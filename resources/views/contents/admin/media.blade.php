@extends(request()->query('snap') == true ? 'layouts.snap' : 'layouts.apps')
@section('toolbar')
    <x-theme.toolbar :breadCrump="$pageData->breadCrump" :title="$pageData->title">
        <x-slot:tools>
        </x-slot:tools>
    </x-theme.toolbar>
@endsection


@section('content')
    <!--begin::Content container-->
    <style>
        .portfolio-card {
            position: relative;
            overflow: hidden;
            border-radius: 10px;
            cursor: pointer;
        }

        .portfolio-card img {
            width: 100%;
            display: block;
            transition: transform 0.3s ease;
        }

        .portfolio-card:hover img {
            transform: scale(1.05);
        }

        .card-overlay {
            position: absolute;
            bottom: 0;
            width: 100%;
            background: rgba(0, 0, 0, 0.6);
            color: white;
            padding: 10px;
            transition: background 0.3s ease, opacity 0.3s ease;
            opacity: 0;
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
            @slot('action')
                <x-btn type="primary" class="act-add w-100 w-md-auto" jf-add="media">
                    <i class="bi bi-upload fs-2"></i> Upload data
                </x-btn>
            @endslot
        </x-table.dttable-card-left>
    </div>

    <x-modal id="modalForm" type="centered" :static="true" size="" jf-modal="media" title="Media">
        <form id="formData" class="needs-validation" jf-form="media">
            <input type="hidden" name="id" value="">
            <div class="mb-4" id="upload_file_input">
                <x-form.input type="file" name="upload_file" label="Upload" required />
            </div>
            <div class="mb-4">
                <x-form.input name="nama_media" label="Nama Media" value="" />
                <small class="help-block">Jika dikosongkan, nama file akan digunakan sebagai nama media</small>
            </div>
        </form>
        @slot('action')
            <x-btn.form action="save" class="act-save" jf-save="media" />
        @endslot
    </x-modal>

    <div class="image-modal" id="imageModal" onclick="closeModal()">
        <img src="" alt="Full Image" id="imageFull">
    </div>
@endsection

@push('scripts')
    <x-script.crud2></x-script.crud2>
    <script>
        function openModal(filepath_media) {
            // var url = element.querySelector('img').getAttribute('data-url');
            // var imageFull = document.getElementById('imageFull');
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
                add: `{{ url('admin/media/store') }}`,
                edit: `{{ url('admin/media/data/detail') }}`,
                update: `{{ url('admin/media/update') }}`,
                delete: `{{ url('admin/media/destroy') }}`
            },
            onAdd: function(data) {
                $('#upload_file_input').removeClass('d-none')
            },
            onEdit: function(data) {
                $('#upload_file_input').addClass('d-none')
            },
        })

        function card(full) {
            return `
                <div class="col-md-2 mb-6">
                    <div class="portfolio-card position-relative">
                        <img src="${full.thumb_media}" alt="Portfolio Image" data-url="${full.filepath_media}" onclick="openModal('${full.filepath_media}')">
                        <div class="card-overlay d-flex justify-content-between align-items-center">
                            <span>${full.nama_media}</span>
                            <div class="overlay-buttons">
                                <a class="btn btn-icon btn-sm mh-25px mw-25px btn-light-danger act-delete me-1" jf-delete="${full.id}"><i class="ki-outline ki-trash fs-3"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            `
        }

        $(document).on('click', '.act-clipboard', function() {
            const url = $(this).data('url');
            navigator.clipboard.writeText(url)
                .then(() => {
                    toastr.success('URL copied to clipboard!')
                })
                .catch(err => {
                    toastr.danger('Failed to copy URL!')
                });
        });

        function renderPlaceholder(ext_media) {
            switch (ext_media) {
                case 'pdf':
                    color = 'danger'
                    break;
                case 'xls':
                case 'xlsx':
                    color = 'success'
                    break;
                case 'doc':
                case 'docx':
                    color = 'primary'
                    break;
                default:
                    color = 'gray-800'
            }
            return `<div class="symbol symbol-50px bg-secondary h-50px w-50px me-3 d-flex align-items-center justify-content-center">
                        <i class="bi bi-filetype-${ext_media} text-${color} fs-2qx"></i>
                    </div>`
        }
    </script>
@endpush
