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
        <div class="row">
            <div class="col-xl-8 col-12">
                <x-table.dttable :builder="$pageData->dataTable" class="align-middle" :responsive="false" jf-data="post-label"
                    jf-list="datatable">
                    @slot('action')
                        <x-btn type="primary" class="act-add w-100 w-md-auto" jf-add="post-label">
                            <i class="bi bi-plus fs-2"></i> Tambah data
                        </x-btn>
                    @endslot
                </x-table.dttable>
            </div>
        </div>
    </div>

    <x-modal id="modalForm" type="centered" :static="true" size="" jf-modal="post-label" title="Post Label">
        <form id="formData" class="needs-validation" jf-form="post-label">
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
                <x-form.input name="nama_label" label="Nama Label" value="" required />
            </div>
            <div class="mb-4">
                {{-- <x-form.input name="nama_label_en" label="Nama Label EN" value="" required /> --}}
                <x-form.textarea name="deskripsi_label" label="Deskripsi Label" value="" />
            </div>
        </form>
        @slot('action')
            <x-btn.form action="save" class="act-save" jf-save="post-label" />
        @endslot
    </x-modal>
@endsection

@push('scripts')
    <x-script.crud2></x-script.crud2>
    <script>
        jForm.init({
            name: "post-label",
            url: {
                add: `{{ route('app.post.store', ['param1' => 'label']) }}`,
                edit: `{{ route('app.post.data', ['param1' => 'label-detail']) }}`,
                update: `{{ route('app.post.update', ['param1' => 'label']) }}`,
                delete: `{{ route('app.post.destroy', ['param1' => 'label']) }}`
            },
        })
    </script>
@endpush
