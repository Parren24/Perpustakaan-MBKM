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
            <label class="form-label">
                Platform
                <span class="text-danger">*</span>
            </label>
            <select id="sosmed" name="platform" class="mb-2 form-select form-control-sm" style="width: 300px"
                data-placeholder=". . . . . . . . . .">
                @foreach ($pageData->dataSocialMedia as $key => $value)
                    <option value="{{ $key }}" data-icon="{{ $value }}">{{ $key }}
                    </option>
                @endforeach
            </select>
            <x-form.input class="mb-2" type="text" label="Url Sosial Media" name="url_social_media" value=""
                required></x-form.input>
            <x-form.textarea class="mb-2" name="deskripsi_social_media" label="Deskripsi Sosial Media" value="" />
            <x-form.select class="mb-2" label="Status Sosial Media" name="status_social_media" required>
                <option value="aktif">Aktif</option>
                <option value="tidak aktif">Tidak Aktif</option>
            </x-form.select>
        </form>
        @slot('action')
            <x-btn.form action="save" class="act-save" jf-save="partner" />
        @endslot
    </x-modal>
@endsection

@push('scripts')
    <x-script.crud2></x-script.crud2>
    <script>
        let tableId = '{{ $pageData->dataTable->getTableId() }}'

        $('#sosmed').select2({
            templateResult: function(option) {
                if (!option.id) return option.text;
                var icon = $(option.element).data('icon');
                return $('<span><i class="' + icon + ' me-2"></i>' + option.text + '</span>');
            },
            templateSelection: function(option) {
                if (!option.id) return option.text;
                var icon = $(option.element).data('icon');
                return $('<span><i class="' + icon + ' me-2"></i>' + option.text + '</span>');
            }
        });

        jForm.init({
            name: "partner",
            url: {
                add: `{{ route('app.master.store', ['param1' => 'social-media']) }}`,
                update: `{{ route('app.master.update', ['param1' => 'social-media']) }}`,
                edit: `{{ route('app.master.data', ['param1' => 'social-media-detail']) }}`,
                delete: `{{ route('app.master.destroy', ['param1' => 'social-media']) }}`,
            },
            onAdd: function() {
                $('#formData [name="status_partner"]').val('aktif').trigger('change')
                $('#coverContent').find('img').remove();
            },
            onEdit: function(data) {
                $('[name="platform"]').trigger('change')
            }
        })

        function renderStatus(data) {
            return `
                <span class="badge badge-secondary text-${data == 'aktif' ? 'success' : 'gray-500'} p-1">${data.toUpperCase()}</span>
            `
        }

        $(document).on('click', '#coverContent', function() {
            $('#formData [name="upload_file"]').trigger('click')
        })

        $(document).on('change', '#formData [name="upload_file"]', function(e) {
            let file = e.target.files[0];
            if (file) {
                let reader = new FileReader();
                reader.onload = function(event) {
                    $('#coverContent').find('img').remove();
                    $('#coverContent') // remove the "Logo Partner" text
                        .append(
                            `<img src="${event.target.result}" class="img-fluid" style="max-height: 150px; object-fit: contain;">`
                        );
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
@endpush
