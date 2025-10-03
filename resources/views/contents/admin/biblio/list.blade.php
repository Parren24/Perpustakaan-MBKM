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
    <x-table.dttable :builder="$pageData->dataTable" class="align-middle table-row-dashed" :responsive="true" jf-data="biblio" jf-list="datatable">
        @slot('action')
        {{-- Only Administrator and Admin can create --}}
        @can('biblio-create')
        <x-btn type="primary" jf-add="biblio">
            <i class="bi bi-plus fs-2"></i> Tambah Buku
        </x-btn>
        @endcan

        @endslot
    </x-table.dttable>
</div>


<x-modal id="modalForm" type="centered" :static="true" size="lg" jf-modal="biblio" title="Data Buku">
    <form id="formData" class="needs-validation" jf-form="biblio">
        <input type="hidden" name="id" value="">
        <div class="mb-4">
            <x-form.input name="title" label="Judul Buku" value="" required />
        </div>
        <div class="mb-4">
            <x-form.input name="author" label="Penulis" value="" required />
        </div>
        <div class="mb-4">
            <x-form.input name="publisher" label="Penerbit" value="" required />
        </div>
        <div class="mb-4">
            <x-form.input name="year" label="Tahun Terbit" type="number" value="" required />
        </div>
        <div class="mb-4">
            <x-form.input name="stock" label="Stok" type="number" value="" required />
        </div>
        <div class="mb-4">
            <x-form.textarea name="description" label="Deskripsi" value="" rows="3" />
        </div>
    </form>
    @slot('action')
    <x-btn.form action="save" class="act-save" jf-save="biblio" />
    @endslot
</x-modal>
@endsection

@push('scripts')
<x-script.crud2></x-script.crud2>
<script>
    jForm.init({
        name: "biblio",
        url: {
            add: `{{ route('app.biblio.store') }}`,
            delete: `{{ route('app.biblio.destroy') }}`
        },
    });
</script>
@endpush