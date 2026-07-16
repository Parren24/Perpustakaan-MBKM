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
        <x-btn type="primary" class="ms-2" data-bs-toggle="modal" data-bs-target="#modalSyncMahasiswa">
            <i class="bi bi-arrow-repeat fs-2"></i> Sync Mahasiswa
        </x-btn>
        <x-btn type="primary" class="ms-2" id="btnSyncPegawai">
            <i class="bi bi-arrow-repeat fs-2"></i> Sync Pegawai
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
            <!-- <x-form.select name="role" label="Role" required>
                <option value="" disabled selected>Pilih Role</option>
                @foreach($pageData->roles as $roleName)
                <option value="{{ $roleName }}">{{ $roleName }}</option>
                @endforeach
            </x-form.select> -->
        </div>
        <div class="mb-4">
            <x-form.input name="nomor_induk" label="Nomor Induk" value="" />
        </div>
    </form>
    @slot('action')
    <x-btn.form action="save" class="act-save" jf-save="user" />
    @endslot
</x-modal>

<x-modal id="modalSyncMahasiswa" type="centered" :static="true" size="lg" title="Sync Data Mahasiswa">
    <form id="formSyncMahasiswa" class="needs-validation">
        <div class="mb-4">
            <x-form.input name="angkatan" label="Tahun Angkatan" type="number" placeholder="Contoh: 2023" required />
        </div>
        <div class="mb-4">
            <x-form.select name="prodi" label="Program Studi" required>
                <option value="" disabled selected>Pilih Program Studi</option>
                <option value="SI">Sistem Informasi</option>
                <option value="TI">Teknik Informatika</option>
                <option value="TRK">Teknik Komputer</option>
                <option value="TET">Teknik Listrik</option>
            </x-form.select>
        </div>
    </form>
    @slot('action')
    <x-btn.form action="save" label="Sync Sekarang" class="act-save" onclick="submitSyncMahasiswa()" />
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

    function submitSyncMahasiswa() {
        const form = document.getElementById('formSyncMahasiswa');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        const formData = new FormData(form);
        const submitBtn = document.querySelector('#modalSyncMahasiswa .act-save');

        // Show loading state
        submitBtn.setAttribute('data-kt-indicator', 'on');
        submitBtn.disabled = true;

        axios.post(`{{ route('user.sync.mahasiswa') }}`, formData)
            .then(response => {
                if (response.data.status) {
                    toastr.success(response.data.message);
                    $('#modalSyncMahasiswa').modal('hide');
                    jForm.reload();
                } else {
                    toastr.error(response.data.message);
                }
            })
            .catch(error => {
               
            })
            .finally(() => {
                submitBtn.removeAttribute('data-kt-indicator');
                submitBtn.disabled = false;
            });
    }

    document.getElementById('btnSyncPegawai').addEventListener('click', function() {
        Swal.fire({
            title: 'Konfirmasi Sync',
            text: "Apakah Anda yakin ingin melakukan sinkronisasi data pegawai?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Sync Sekarang!'
        }).then((result) => {
            if (result.isConfirmed) {
                const btn = this;
                btn.setAttribute('data-kt-indicator', 'on');
                btn.disabled = true;

                axios.post(`{{ route('user.sync.pegawai') }}`)
                    .then(response => {
                        if (response.data.status) {
                            toastr.success(response.data.message);
                            jForm.reload();
                        } else {
                            toastr.error(response.data.message);
                        }
                    })
                    .catch(error => {
                        
                    })
                    .finally(() => {
                        btn.removeAttribute('data-kt-indicator');
                        btn.disabled = false;
                    });
            }
        });
    });
</script>
@endpush