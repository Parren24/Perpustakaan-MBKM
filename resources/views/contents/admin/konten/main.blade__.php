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
        <x-table.dttable-card-left :builder="$pageData->dataTable" :table_card="true" :covered="false" :responsive="false" draw_callback=""
            :order="false" :export="false" jf-data="konten-main" jf-list="datatable">
        </x-table.dttable-card-left>
    </div>

    <x-modal id="modalForm" type="centered" :static="true" size="lg" jf-modal="konten-main" title="Konten Main">
        <form id="formData" class="needs-validation" jf-form="konten-main">
            <input type="hidden" name="id" value="">
        </form>
        @slot('action')
            <x-btn.form action="save" class="act-save" jf-save="konten-main" />
        @endslot
    </x-modal>
@endsection

@push('scripts')
    <x-script.crud2></x-script.crud2>
    <script>
        function generateForm(data) {
            var form = ''
            if (data.section == 'hero_main') {
                $.each(data.data, function(k, v) {
                    form += `
                    <div class="d-flex flex-row mb-4 border border-2 rounded rounded-4 me-2">
                        <div class="pt-2 d-flex align-items-center justify-content-center"
                            style="width: 50px; text-align: center;">
                            <i class="ki-duotone ki-arrow-up-down fs-1 text-dark">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </div>
                        <div class="flex-grow-1 py-4 pe-4">
                            <input type="hidden" name="id" value="">
                            <input type="hidden" name="seq" value="">
                            <div class="row">
                                <div class="col-xl-6 col-12 mb-2">
                                <x-form.input name="title_main[]" label="Title"
                                    value="" required />
                                    <small class="fs-8">Title utama yang menjadi highlight header</small>
                                </div>
                                <div class="col-xl-6 col-12 mb-2">
                                    <x-form.input type="file" label="Media" class="media_cropper"
                                        data-preview="media_hero_preview"
                                        data-hidden_container="hidden_inputs_hero" data-append="1"
                                        data-name="hero" />
                                    <small class="fs-8">Download</small>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-center"
                            style="width: 50px; text-align: center;">
                        </div>
                    </div>`
                })
            } else if (data.section == 'infografis_main') {
                form = `
                    <div class="mb-2">
                        <x-form.input name="title" label="Title" value="" required />
                    </div>
                    <div class="mb-2">
                        <x-form.textarea name="deskripsi" label="Deskripsi" rows="5" value="" required />
                    </div>
                    <div class="mb-2">
                        <x-form.input type="file" label="Media" class="media_cropper"
                            data-preview="media_hero_preview"
                            data-hidden_container="hidden_inputs_hero" data-append="1"
                            data-name="hero" />
                    </div>`
            } else if (data.section == 'jurusan_main') {
                form = `
                    <div class="mb-2">
                        <x-form.input name="title" label="Title" value="" required />
                    </div>
                    <div class="mb-2">
                        <x-form.textarea name="deskripsi" label="Deskripsi" rows="5" value="" required />
                    </div>`
            } else if (data.section == 'pmb_main') {
                form = `
                    <div class="mb-2">
                        <x-form.input name="title" label="Title" value="" required />
                    </div>
                    <div class="mb-2">
                        <x-form.textarea name="deskripsi" label="Deskripsi" rows="5" value="" required />
                    </div>
                    <div class="mb-2">
                        <x-form.input type="file" label="Media" class="media_cropper"
                            data-preview="media_hero_preview"
                            data-hidden_container="hidden_inputs_hero" data-append="1"
                            data-name="hero" />
                    </div>`
            } else if (data.section == 'partner_main') {
                form = `
                    <div class="mb-2">
                        <x-form.input name="title" label="Title" value="" required />
                    </div>
                    <div class="mb-2">
                        <x-form.textarea name="deskripsi" label="Deskripsi" rows="5" value="" required />
                    </div>`
            }

            return form
        }

        jForm.init({
            name: "konten-main",
            url: {
                add: `{{ url('admin/konten-main/store') }}`,
                edit: `{{ url('admin/konten-main/data/detail') }}`,
                update: `{{ url('admin/konten-main/update') }}`,
                delete: `{{ url('admin/konten-main/destroy') }}`
            },
            onEdit: function(data) {
                $('#modalForm-title').html('Konten ' + data.section_title)
                var form = generateForm(data)

                $('#formData').html(form)
            },
        })

        function card(full) {
            return `
                <div class="col-xl-4 col-md-6 col-12 mb-6">
                    <div class="d-flex w-100 flex-column rounded-4 bg-light shadow p-4">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="d-flex justify-content-center align-items-start flex-column flex-grow-1 fw-bold fs-4">
                                <small class="text-gray-600 fs-9">section</small>
                                ${full.config_title}
                            </div>
                            <div class="d-flex align-items-center justify-content-end gap-1">
                                ${full.action}
                            </div>
                        </div>
                        <div class="separator separator-dashed border-gray-10 my-2"></div>
                        <div class="d-flex align-items-center">
                            ${full.config_desc ?? '<small class="fst-italic text-muted">No description available</small>' }
                        </div>
                    </div>
                </div>
            `
        }
    </script>
@endpush
