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
    <x-table.dttable :builder="$pageData->dataTable" class="align-middle table-row-dashed" :responsive="true" jf-data="loan" jf-list="datatable">
    </x-table.dttable>
</div>
@endsection


@push('scripts')
<x-script.crud2></x-script.crud2>
<script>
    jForm.init({
        name: "loan",
        url: {
            delete: `{{ route('app.loan.destroy') }}`
        },
    });
</script>
@endpush