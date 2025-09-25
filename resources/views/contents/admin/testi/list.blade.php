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
        @include('contents.admin.testi.tabs')
        <x-table.dttable :builder="$pageData->dataTable" class="align-middle" :responsive="false" jf-data="testi" jf-list="datatable">
            @slot('filter')
                <div class="row">
                    <div class="col-md-3">
                        <x-form.select name="prodi_id" placeholder="Seluruh Data Program Studi" :search="true" required>
                            @foreach ($pageData->dataProdi as $row)
                                <option value="{{ $row['id'] }}">{{ $row['text'] }}
                                </option>
                            @endforeach
                        </x-form.select>
                    </div>
                </div>
            @endslot
            @slot('action')
                <x-btn type="primary" jf-add="testi">
                    <i class="bi bi-plus fs-2"></i> Tambah data
                </x-btn>
            @endslot
        </x-table.dttable>
    </div>

    <x-modal id="modalForm" type="centered" :static="true" size="" jf-modal="testi" title="Testimoni Alumni">
        <form id="formData" class="needs-validation" jf-form="testi">
            <input type="hidden" name="id" value="">
            <x-form.input class="mb-2" type="text" label="Nama Alumni" name="nama_alumni" value=""
                required></x-form.input>
            <x-form.select class="mb-2" label="Angkatan" name="angkatan" value="" :search="true" required>
                @for ($i = 2001; $i <= date('Y'); $i++)
                    <option value="{{ $i }}">{{ $i }}</option>
                @endfor
            </x-form.select>
            <x-form.select class="mb-2" label="Program Studi" name="prodi_id" value="" :search="true" required>
                @foreach ($pageData->dataProdi as $row)
                    <option value="{{ $row['id'] }}">{{ $row['text'] }}
                    </option>
                @endforeach
            </x-form.select>
        </form>
        @slot('action')
            <x-btn.form action="save" class="act-save" jf-save="testi" />
        @endslot
    </x-modal>
@endsection

@push('scripts')
    <x-script.crud2></x-script.crud2>
    <script>
        jForm.init({
            name: "testi",
            base_url: `{{ route('app.testi.index') }}`,
            onSave: function(data) {
                if (data) location.href = `{{ route('app.testi.show') . '/form' }}/` + data.id
            }
        })

        function renderStatus(data) {
            return `
                <span class="badge badge-secondary text-${data == 'published' ? 'success' : (data == 'draft' ? 'gray-500' : (data == 'rejected' ? 'danger' : (data == 'reviewing' ? 'info' : 'gray-800')))} p-1">${data.toUpperCase()}</span>
            `
        }
    </script>
@endpush
