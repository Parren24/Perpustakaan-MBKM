{{-- author : mwy --}}
@props([
    'title' => 'Data',
    'table' => 'table',
    'form' => 'formData',
    'modal' => 'modalForm',
    'url_add' => '',
    'url_edit' => '',
    'url_update' => '',
    'url_delete' => '',
])

@push('scripts')
    <script>
        // ------------------------------------------------------------------------------------------------------------------------------------------------------------
        @if ($url_add)
            $(document).on('click', `.act-add`, function(e) {
                $('#{{ $modal }}-title').html('Tambah {{ $title }}')
                $('#{{ $form }}').attr('action', '{{ $url_add }}')
                resetForm('{{ $form }}')
                $('#{{ $modal }}').modal('show')
            })
        @endif

        @if ($url_edit)
            $(document).on('click', `#{{ $table }} .act-edit`, function(e) {
                $('#{{ $modal }}-title').html('Edit {{ $title }}')
                $('#{{ $form }}').attr('action', '{{ $url_update }}')
                resetForm('{{ $form }}')

                id = $(this).data('id')
                ajaxRequest({
                    link: '{{ $url_edit }}',
                    data: {
                        id: id
                    },
                    callback: function(origin, resp) {
                        data = resp.data

                        {!! $slot !!}

                        $('#{{ $modal }}').modal('show')
                    }
                })
            })
        @endif

        @if ($url_delete)
            $(document).on('click', `#{{ $table }} .act-delete`, function(e) {
                let data_id = $(this).data('id')
                let data = {
                    id: data_id
                }

                Swal.fire({
                    title: "Hapus data ?",
                    text: "Data yang sudah dihapus tidak dapat dikembalikan, pastikan data yang akan di hapus sudah sesuai",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Ya, Lanjutkan!",
                    cancelButtonText: "Batal",
                    customClass: {
                        confirmButton: "btn btn-light-danger",
                        cancelButton: "btn btn-light-dark"
                    },
                    reverseButtons: true
                }).then(function(result) {
                    if (result.value) {
                        ajaxRequest({
                            link: `{{ $url_delete }}`,
                            data: data,
                            swal_success: true,
                            callback: function cb(obj_origin, resp) {
                                if (resp.status) {
                                    $(`#{{ $table }}`).DataTable().ajax.reload(null, false)
                                }
                            }
                        })
                    }
                });
            })
        @endif

        @if ($url_update || $url_add)
            $(document).on('click', `#{{ $modal }} .act-save`, function(e) {
                $('#{{ $form }}').submit()
            })
        @endif

        @if ($url_update || $url_add)
            $(document).on('submit', `#{{ $form }}`, function(e) {
                e.preventDefault()

                ajaxRequest({
                    link: $('#{{ $form }}').attr('action'),
                    data: $('#{{ $form }}').serialize(),
                    callback: function(origin, resp) {
                        if (resp.status) {
                            $(`#{{ $table }}`).DataTable().ajax.reload(null, false)
                            $('#{{ $modal }}').modal('hide')
                        }
                    },
                    swal_success: true
                })
            })
        @endif
    </script>
@endpush
