@extends(request()->query('snap') == true ? 'layouts.snap' : 'layouts.apps')

@section('toolbar')
<x-theme.toolbar :breadCrump="$pageData->breadCrump" :title="$pageData->title">
    <x-slot:tools>
    </x-slot:tools>
</x-theme.toolbar>
@endsection

@section('content')
<div id="kt_app_content_container" class="app-container container-fluid" data-cue="slideInLeft" data-duration="1000"
    data-delay="0">
    <x-table.dttable :builder="$pageData->dataTable" class="align-middle table-row-dashed" :responsive="true" jf-data="fines" jf-list="datatable">
        </x-table.dttable>
</div>

{{-- Modal Form Edit Status --}}
<x-modal id="modalForm" type="centered" :static="true" size="lg" jf-modal="fines" title="Detail Denda">
    <form id="formData" class="needs-validation" jf-form="fines">
        <input type="hidden" name="id" value="">

        <div class="row">
            <div class="col-md-6 mb-4">
                <x-form.input name="member_name" label="Nama Member" value="" readonly class="form-control-solid" />
            </div>
            <div class="col-md-6 mb-4">
                <x-form.input name="item_code" label="Kode Buku" value="" readonly class="form-control-solid" />
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-4">
                <x-form.input name="loan_date" label="Tanggal Pinjam" value="" readonly class="form-control-solid" />
            </div>
            <div class="col-md-6 mb-4">
                <x-form.input name="due_date" label="Tenggat Waktu" value="" readonly class="form-control-solid" />
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-4">
                <x-form.input name="debet" label="Total Denda (Rp)" value="" readonly class="form-control-solid" />
            </div>
            <div class="col-md-6 mb-4">
                <x-form.input name="credit" label="Telah Dibayar (Rp)" value="" readonly class="form-control-solid" />
            </div>
        </div>

        <div class="mb-4">
            <label class="form-label required">Status Pembayaran</label>
            <select name="status" class="form-control form-select" data-control="select2" data-hide-search="true">
                <option value="PENDING">PENDING (Belum Lunas)</option>
                <option value="PAID">PAID (Lunas)</option>
            </select>
            <div class="text-muted fs-7 mt-2">
                <i class="fas fa-info-circle me-1"></i>
                Mengubah status menjadi <strong>PAID</strong> akan otomatis mencatat pengembalian buku di sistem.
            </div>
        </div>

    </form>
    @slot('action')
    <x-btn.form action="save" class="act-save" jf-save="fines" label="Simpan Perubahan" />
    @endslot
</x-modal>
@endsection

@push('scripts')
<x-script.crud2></x-script.crud2>
<script>
    jForm.init({
        name: "fines",
        url: {
            update: `{{ route('app.fines.update') }}`,
            edit: `{{ route('app.fines.data', ['param1' => 'detail']) }}`,
        },
    });
</script>
@endpush