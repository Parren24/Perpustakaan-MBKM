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
        <div class="row">
            <div class="col-xl-6 col-12">
                <x-table.dttable :builder="$pageData->dataTable" class="align-middle" :responsive="false" jf-data="post-kategori"
                    jf-list="datatable">
                    @slot('action')
                        <x-btn type="primary" class="act-add w-100 w-md-auto" jf-add="post-kategori">
                            <i class="bi bi-plus fs-2"></i> Tambah data
                        </x-btn>
                    @endslot
                </x-table.dttable>
            </div>
        </div>
    </div>

    <x-modal id="modalForm" type="centered" :static="true" size="" jf-modal="post-kategori" title="Post Kategori">
        <form id="formData" class="needs-validation" jf-form="post-kategori">
            <input type="hidden" name="id" value="">
            <div class="mb-4">
                <x-form.input name="kode_kategori" label="Kode Kategori" icon_start="fa fa-tag" value="" required />
            </div>
            <div class="mb-4">
                <x-form.input name="nama_kategori" label="Nama Kategori" value="" required />
            </div>
            <div class="mb-4">
                {{-- <x-form.input name="nama_kategori_en" label="Nama Kategori EN" value="" required /> --}}
                <x-form.textarea name="deskripsi_kategori" label="Deskripsi Kategori" value="" />
            </div>
        </form>
        @slot('action')
            <x-btn.form action="save" class="act-save" jf-save="post-kategori" />
        @endslot
    </x-modal>
@endsection

@push('scripts')
    <x-script.crud2></x-script.crud2>
    <script>
        jForm.init({
            name: "post-kategori",
            url: {
                add: `{{ url('admin/testi-kategori/store') }}`,
                edit: `{{ url('admin/testi-kategori/data/detail') }}`,
                update: `{{ url('admin/testi-kategori/update') }}`,
                delete: `{{ url('admin/testi-kategori/destroy') }}`
            },
        })
    </script>
@endpush
