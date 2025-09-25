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
                <x-table.dttable :builder="$pageData->dataTable" class="align-middle" :responsive="false" jf-data="kontak" jf-list="datatable">
                    @slot('filter')
                        <div class="row">
                        </div>
                    @endslot
                    @slot('action')
                        <x-btn type="primary" class="act-add" jf-add="kontak">
                            <i class="bi bi-plus fs-2"></i> Tambah data
                        </x-btn>
                    @endslot
                </x-table.dttable>
            </div>
        </div>
    </div>

    <x-modal id="modalForm" type="centered" :static="true" size="" jf-modal="kontak" title="Kontak">
        <form id="formData" class="needs-validation" jf-form="kontak">
            <input type="hidden" name="id" value="">
            <label class="form-label">
                Tipe
                <span class="text-danger">*</span>
            </label>
            <select id="kontak" name="tipe_kontak" class="mb-2 form-select form-control-sm" style="width: 300px"
                data-placeholder=". . . . . . . . . .">
                <option value="Telepon" data-icon="bi bi-telephone-fill">Telepon</option>
                <option value="Handphone" data-icon="bi bi-phone-fill">Handphone</option>
                <option value="Email" data-icon="bi bi-envelope-at-fill">Email</option>
                <option value="Whatsapp" data-icon="bi bi-whatsapp">Whatsapp</option>
                <option value="Telegram" data-icon="bi bi-telegram">Telegram</option>
            </select>
            <x-form.input class="mb-2" type="hidden" name="icon_kontak" value=""></x-form.input>
            <x-form.input class="mb-2" type="text" label="Nama Kontak" name="nama_kontak" value=""
                required></x-form.input>
            <x-form.input class="mb-2" type="text" label="Informasi Kontak" name="value_kontak" value=""
                required></x-form.input>
            <x-form.textarea class="mb-2" name="deskripsi_kontak" label="Deskripsi Kontak" value="" />
            <x-form.select class="mb-2" label="Status Kontak" name="status_kontak" required>
                <option value="aktif">Aktif</option>
                <option value="tidak aktif">Tidak Aktif</option>
            </x-form.select>
        </form>
        @slot('action')
            <x-btn.form action="save" class="act-save" jf-save="kontak" />
        @endslot
    </x-modal>
@endsection

@push('scripts')
    <x-script.crud2></x-script.crud2>
    <script>
        let tableId = '{{ $pageData->dataTable->getTableId() }}'

        $('#kontak').select2({
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

        $(document).on('change', '#kontak', function() {
            var value = $(this).find('option:selected').data('icon');
            $('[name="icon_kontak"]').val(value)
        })

        jForm.init({
            name: "kontak",
            url: {
                add: `{{ route('app.master.store', ['param1' => 'kontak']) }}`,
                update: `{{ route('app.master.update', ['param1' => 'kontak']) }}`,
                edit: `{{ route('app.master.data', ['param1' => 'kontak-detail']) }}`,
                delete: `{{ route('app.master.destroy', ['param1' => 'kontak']) }}`,
            },
            onAdd: function() {
                $('[name="tipe_kontak"]').trigger('change')
                $('#formData [name="status_kontak"]').val('aktif').trigger('change')
            },
            onEdit: function(data) {
                $('[name="tipe_kontak"]').trigger('change')
            }
        })

        function renderStatus(data) {
            return `
                <span class="badge badge-secondary text-${data == 'aktif' ? 'success' : 'gray-500'} p-1">${data.toUpperCase()}</span>
            `
        }
    </script>
@endpush
