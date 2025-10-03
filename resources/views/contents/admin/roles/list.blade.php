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

    {{-- Success/Error Messages --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif
    <x-table.dttable :builder="$pageData->dataTable" class="align-middle table-row-dashed" :responsive="true" jf-data="roles" jf-list="datatable">
        @slot('action')
        @hasrole('administrator')
        <x-btn type="primary" jf-add="roles">
            <i class="bi bi-plus fs-2"></i> Tambah Role
        </x-btn>
        @endhasrole
        @endslot
    </x-table.dttable>
</div>

<x-modal id="modalForm" type="centered" :static="true" size="lg" jf-modal="roles" title="Data Role">
    <form id="formData" class="needs-validation" jf-form="roles">
        <input type="hidden" name="id" value="">
        <div class="mb-4">
            <x-form.input name="name" label="Nama Role" value="" required />
        </div>
        <div class="mb-4">
            <label class="form-label">Permissions</label>
            <div class="row">
                @if(isset($pageData->permissions))
                @foreach($pageData->permissions as $permission)
                <div class="col-md-4 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="permissions[]"
                            value="{{ $permission['name'] }}" id="permission_{{ $permission['id'] }}">
                        <label class="form-check-label" for="permission_{{ $permission['id'] }}">
                            {{ $permission['name'] }}
                        </label>
                    </div>
                </div>
                @endforeach
                @else
                <p class="text-muted">No permissions available</p>
                @endif
            </div>
        </div>
    </form>
    @slot('action')
    <x-btn.form action="save" class="act-save" jf-save="roles" />
    @endslot
</x-modal>
@endsection

@push('scripts')
<x-script.crud2></x-script.crud2>
<script>
    jForm.init({
        name: "roles",
        url: {
            add: `{{ route('app.roles.store') }}`,
            edit: `{{ route('app.roles.data', ['param1' => 'detail']) }}`,
            update: `{{ route('app.roles.update') }}`,
            delete: `{{ route('app.roles.destroy') }}`
        },
        success: {
            redirect: `{{ route('app.roles.index') }}`
        }
    });
</script>
@endpush