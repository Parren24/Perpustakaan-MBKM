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
            <div class="col-xl-8 col-12">
                <x-table.dttable :builder="$pageData->dataTable" class="align-middle" :responsive="false" jf-data="agenda-kategori"
                    jf-list="datatable">
                    @slot('action')
                        <x-btn type="primary" class="act-add w-100 w-md-auto" jf-add="agenda-kategori">
                            <i class="bi bi-plus fs-2"></i> Tambah data
                        </x-btn>
                    @endslot
                </x-table.dttable>
            </div>
        </div>
    </div>

    <x-modal id="modalForm" type="centered" :static="true" size="" jf-modal="agenda-kategori"
        title="Post Kategori">
        <form id="formData" class="needs-validation" jf-form="agenda-kategori">
            <input type="hidden" name="id" value="">
            <div class="mb-4">
                <x-form.input name="nama_kategori" label="Nama Kategori ID" value="" required />
            </div>
            <div class="mb-4">
                {{-- <x-form.input name="nama_kategori_en" label="Nama Kategori EN" value="" required /> --}}
                <x-form.textarea name="deskripsi_kategori" label="Deskripsi Kategori" value="" />
            </div>
        </form>
        @slot('action')
            <x-btn.form action="save" class="act-save" jf-save="agenda-kategori" />
        @endslot
    </x-modal>
@endsection

@push('scripts')
    <x-script.crud2></x-script.crud2>
    <script>
        jForm.init({
            name: "agenda-kategori",
            url: {
                add: `{{ route('app.agenda.store', ['param1' => 'kategori']) }}`,
                edit: `{{ route('app.agenda.data', ['param1' => 'kategori-detail']) }}`,
                update: `{{ route('app.agenda.update', ['param1' => 'kategori']) }}`,
                delete: `{{ route('app.agenda.destroy', ['param1' => 'kategori']) }}`,
            }
        })
    </script>
@endpush
