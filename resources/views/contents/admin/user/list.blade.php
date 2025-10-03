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
    <x-table.dttable :builder="$pageData->dataTable" class="align-middle table-row-dashed" :responsive="true" jf-data="user" jf-list="datatable">
        @slot('action')
        <x-btn type="primary" jf-add="user">
            <i class="bi bi-plus fs-2"></i> Tambah User
        </x-btn>
        @endslot
    </x-table.dttable>
</div>

<x-modal id="modalForm" type="centered" :static="true" size="lg" jf-modal="user" title="Data User">
    <form id="formData" class="needs-validation" jf-form="user">
        <input type="hidden" name="id" value="">
        <div class="mb-4">
            <x-form.input name="email" label="Email Pengguna" value="" required />
        </div>
        <div class="mb-4">
            {{-- PERBAIKAN: Bagian ini sekarang akan berfungsi karena variabel $roles sudah dikirim dari controller --}}
            <x-form.select name="role" label="Role" required>
                <option value="" disabled selected>Pilih Role</option>
                @foreach($pageData->roles as $roleName)
                <option value="{{ $roleName }}">{{ $roleName }}</option>
                @endforeach
            </x-form.select>
        </div>
    </form>
    @slot('action')
    <x-btn.form action="save" class="act-save" jf-save="user" />
    @endslot
</x-modal>
@endsection

@push('scripts')
<x-script.crud2></x-script.crud2>
<script>
    jForm.init({
        name: "user",
        url: {
            add: `{{ route('app.user.store') }}`,
            delete: `{{ route('app.user.destroy') }}`
        },
    });
</script>
@endpush