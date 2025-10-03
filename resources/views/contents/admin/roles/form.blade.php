@extends(request()->query('snap') == true ? 'layouts.snap' : 'layouts.apps')
@section('toolbar')
<x-theme.toolbar :breadCrump="$pageData->breadCrump" :title="$pageData->title">
    <x-slot:tools>
    </x-slot:tools>
</x-theme.toolbar>
@endsection

@section('content')
<div class="container">
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
    
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    <div class="card">
        <div class="card-body">
            <form id="formData" class="form" jf-form="roles"
                action="{{ $pageData->dataRole ? route('app.roles.update', encid($pageData->dataRole->id)) : route('app.roles.store') }}"
                method="POST">
                @csrf
                @if($pageData->dataRole)
                    @method('PUT')
                @endif
                <input type="hidden" name="id" value="{{ $pageData->dataRole ? encid($pageData->dataRole->id) : '' }}">

                <div class="mb-5">
                    <label class="form-label required">Nama Role</label>
                    <input type="text" name="name" class="form-control" placeholder="Masukkan nama role..."
                        value="{{ $pageData->dataRole->name ?? '' }}" required>
                </div>

                <div class="mb-2 d-flex justify-content-between align-items-center">
                    <label class="form-label required">Hak Akses (Permissions)</label>
                    <div>
                        <button type="button" class="btn btn-sm btn-secondary me-2" onclick="selectAllPermissions()">
                            <i class="bi bi-check-all"></i> Select All
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="deselectAllPermissions()">
                            <i class="bi bi-x"></i> Clear All
                        </button>
                    </div>
                </div>

                @php
                $rolePermissions = $pageData->dataRole ? $pageData->dataRole->permissions->pluck('name')->all() : [];
                @endphp

                <div class="row">
                    @foreach ($pageData->permissions as $group => $permissionList)
                    <div class="col-md-4 mb-5">
                        <div class="card card-body h-100">
                            <h6 class="text-capitalize border-bottom pb-3 mb-3">{{ $group }}</h6>
                            @foreach ($permissionList as $permission)
                            <div class="form-check form-check-custom form-check-solid mb-2">
                                <input class="form-check-input" type="checkbox" name="permissions[]"
                                    value="{{ $permission->name }}" id="perm_{{ $permission->id }}"
                                    {{ in_array($permission->name, $rolePermissions) ? 'checked' : '' }} />
                                <label class="form-check-label" for="perm_{{ $permission->id }}">
                                    {{ $permission->name }}
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="text-end">
                    <a href="{{ route('app.roles.index') }}" class="btn btn-light me-3">Batal</a>
                    <button type="submit" class="btn btn-primary">
                        <span class="indicator-label">Simpan</span>
                        <span class="indicator-progress">Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Debug: Log current user permissions
console.log('Form loaded. Checking permissions...');

// Tambah event listener untuk form submission
document.getElementById('formData').addEventListener('submit', function(e) {
    console.log('Form submitting...');
    
    // Check if any permissions are selected
    const selectedPermissions = document.querySelectorAll('input[name="permissions[]"]:checked');
    console.log('Selected permissions:', selectedPermissions.length);
    
    if (selectedPermissions.length === 0) {
        alert('Silakan pilih minimal satu permission.');
        e.preventDefault();
        return false;
    }
});

// Tambah tombol Select All / Deselect All
function selectAllPermissions() {
    document.querySelectorAll('input[name="permissions[]"]').forEach(cb => cb.checked = true);
}

function deselectAllPermissions() {
    document.querySelectorAll('input[name="permissions[]"]').forEach(cb => cb.checked = false);
}
</script>
@endpush