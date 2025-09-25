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
        @include('contents.admin.post.tabs')
        <x-table.dttable :builder="$pageData->dataTable" class="align-middle" :responsive="false" jf-data="post" jf-list="datatable"
            :default_filter="[
                'postkategori_id' => $pageData->postkategori_id,
            ]">
            @slot('action')
                <x-btn type="primary" class="act-add" jf-add="post">
                    <i class="bi bi-plus fs-2"></i> Tambah data
                </x-btn>
            @endslot
        </x-table.dttable>
    </div>

    <x-modal id="modalForm" type="centered" :static="true" size="" jf-modal="post" title="Post">
        <form id="formData" class="needs-validation" jf-form="post">
            <input type="hidden" name="id" value="">
            <div class="mb-4">
                <x-form.select name="postkategori_id" label="Kategori" required>
                    @foreach ($pageData->dataKategori as $row)
                        <option value="{{ $row['id'] }}">
                            {{ $row['text'] }}
                        </option>
                    @endforeach
                </x-form.select>
            </div>
            <div class="mb-4">
                <x-form.input class="" type="text" label="Judul" name="judul_post" value=""
                    required></x-form.input>
            </div>
        </form>
        @slot('action')
            <x-btn.form action="save" class="act-save" jf-save="post" />
        @endslot
    </x-modal>
@endsection

@push('scripts')
    <x-script.crud2></x-script.crud2>
    <script>
        var dataProdi = @json($pageData->dataProdi);
        var dataJurusan = @json($pageData->dataJurusan);

        jForm.init({
            name: "post",
            base_url: `{{ route('app.post.index') }}`,
            onSave: function(data) {
                if (data) location.href = `{{ route('app.post.show') . '/form' }}/` + data.id
            }
        })

        function renderLabel(data) {
            var text = ''
            $.each(data, function(index, item) {
                text +=
                    `<span class="badge badge-light-primary p-1 me-1" title="${item.nama_label}">${item.kode_label}</span>`
            });

            return text
        }

        function renderKategori(data) {
            var text = ''
            $.each(data, function(index, item) {
                text +=
                    `<span class="badge badge-light-danger p-1 me-1" title="${item.nama_kategori}">${item.nama_kategori}</span>`
            });

            return text
        }

        function renderStatus(data) {
            return `
                <span class="badge badge-secondary text-${data == 'published' ? 'success' : (data == 'draft' ? 'gray-500' : (data == 'rejected' ? 'danger' : (data == 'reviewing' ? 'info' : 'gray-800')))} p-1">${data.toUpperCase()}</span>
            `
        }
    </script>
@endpush
