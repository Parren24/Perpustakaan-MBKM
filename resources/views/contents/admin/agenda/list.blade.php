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
        @include('contents.admin.agenda.tabs')
        <div class="row">
            <div class="col-md-8">
                <x-table.dttable :builder="$pageData->dataTable" class="align-middle" :responsive="false" jf-data="agenda" jf-list="datatable">
                    @slot('action')
                        <x-btn type="primary" class="act-add w-100 w-md-auto" jf-add="agenda">
                            <i class="bi bi-plus fs-2"></i> Tambah data
                        </x-btn>
                    @endslot
                </x-table.dttable>
            </div>
        </div>
    </div>

    <x-modal id="modalForm" type="centered" :static="true" size="" jf-modal="agenda" title="Agenda">
        <form id="formData" class="needs-validation" jf-form="agenda">
            <input type="hidden" name="id" value="">
            <div class="row">
                <div class="col-md-6">
                    <x-form.select class="mb-2" label="Situs" name="level" value="" :search="false" required>
                        <option value="main-site">Situs
                            Utama
                        </option>
                        <option value="jurusan-site">
                            Situs Jurusan</option>
                        <option value="prodi-site">
                            Situs Prodi</option>
                    </x-form.select>
                </div>
                <div class="col-md-6">
                    <x-form.select class="mb-2" name="level_id" placeholder="Tentukan prodi/jurusan situs"
                        label="Pilihan Situs" :search="true" :clear="false" required>
                    </x-form.select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <x-form.select name="agendakategori_id" class="mb-2" label="Kategori" required>
                        @foreach ($pageData->dataKategori as $row)
                            <option value="{{ $row['id'] }}">
                                {{ $row['text'] }}
                            </option>
                        @endforeach
                    </x-form.select>
                </div>
                <div class="col-md-6">

                    <x-form.select class="mb-2" label="Status" name="status_agenda" value="" :search="false"
                        required>
                        <option value="draft">Draft
                        </option>
                        <option value="published">
                            Published</option>
                        <option value="archived">
                            Archived</option>
                    </x-form.select>
                </div>
            </div>
            <x-form.textarea name="nama_agenda" class="mb-2" label="Agenda" value="" />
            <div class="row">
                <div class="col-md-6">
                    <x-form.input type="date" class="mb-2" name="tanggal_start_agenda" label="Tanggal Mulai"
                        required />
                </div>
                <div class="col-md-6">
                    <x-form.input type="date" class="mb-2" name="tanggal_end_agenda" label="Tanggal Selesai" />
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <x-form.input type="time" class="mb-2" name="waktu_start_agenda" label="Waktu Mulai" />
                </div>
                <div class="col-md-6">
                    <x-form.input type="time" class="mb-2" name="waktu_end_agenda" label="Waktu Selesai"
                        label="Waktu Selesai" />
                </div>
            </div>
            <x-form.input type="text" :icon_start="'bi bi-geo-alt-fill'" class="mb-2" name="lokasi_agenda" label="Lokasi Agenda" />
            <x-form.input type="text" :icon_start="'bi bi-geo-link-45deg'" class="mb-2" name="url_agenda" label="Link Agenda" />
            <x-form.textarea name="deskripsi_agenda" class="mb-2" label="Deskripsi Agenda" rows="4" />
        </form>
        @slot('action')
            <x-btn.form action="save" class="act-save" jf-save="agenda" />
        @endslot
    </x-modal>
@endsection

@push('scripts')
    <x-script.crud2></x-script.crud2>
    <script>
        var dataProdi = @json($pageData->dataProdi);
        var dataJurusan = @json($pageData->dataJurusan);

        jForm.init({
            name: "agenda",
            base_url: `{{ route('app.agenda.index') }}`
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

        function renderKategori(data) {
            var text = ''
            $.each(data, function(index, item) {
                text +=
                    `<span class="badge badge-light-danger p-1 me-1" title="${item.nama_kategori}">${item.nama_kategori}</span>`
            });

            return text
        }
    </script>
@endpush
