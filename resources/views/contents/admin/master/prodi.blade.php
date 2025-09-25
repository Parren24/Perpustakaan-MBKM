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
        <x-table.dttable :builder="$pageData->dataTable" class="align-middle" :responsive="false" jf-data="infografis" jf-list="datatable">
            @slot('filter')
                <div class="row">
                </div>
            @endslot
            @slot('action')
                <x-btn type="primary" class="act-add w-100 w-md-auto" jf-add="agenda">
                    <i class="bi bi-arrow-repeat fs-2"></i> Sync data
                </x-btn>
            @endslot
        </x-table.dttable>
    </div>

    <x-modal id="modalForm" type="centered" :static="true" size="" jf-modal="infografis" title="Infografis">
        <form id="formData" class="needs-validation" jf-form="infografis">
            <input type="hidden" name="id" value="">
            <x-form.input class="mb-2" type="text" label="Data" name="nama_infografis" value=""
                readonly></x-form.input>
            <x-form.input class="mb-2" type="text" label="Nama Alumni" name="value_infografis" value=""
                required></x-form.input>
        </form>
        @slot('action')
            <x-btn.form action="save" class="act-save" jf-save="infografis" />
        @endslot
    </x-modal>
@endsection

@push('scripts')
    <x-script.crud2></x-script.crud2>
    <script>
        jForm.init({
            name: "infografis",
            url: {
                add: `{{ route('app.master.store') }}`,
                update: `{{ route('app.master.update', ['param1' => 'infografis']) }}`,
                edit: `{{ route('app.master.data', ['param1' => 'infografis-detail']) }}`,
                delete: `{{ route('app.master.destroy') }}`,
            }
        })
    </script>
@endpush
