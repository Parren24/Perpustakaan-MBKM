@extends(request()->query('snap') == true ? 'layouts.snap' : 'layouts.apps')
@section('toolbar')
    <x-theme.toolbar :breadCrump="$pageData->breadCrump" :title="$pageData->title">
        <x-slot:tools>
            {{-- <x-theme.back link="{{ url('admin') . '/konten-' . explode('-', $pageData->dataPage['targetPage'])[0] }}" /> --}}
        </x-slot:tools>
    </x-theme.toolbar>
@endsection


@section('content')
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-fluid" data-cue="slideInLeft" data-duration="1000"
        data-delay="0">
        <x-table.dttable :builder="$pageData->dataTable" class="align-middle" :responsive="false" jf-data="konten-page" jf-list="datatable"
            :default_filter="[
                'level' => $pageData->dataPage['level'],
                'level_id' => $pageData->dataPage['level_id'],
            ]">
        </x-table.dttable>
    </div>

    <x-modal id="modalForm" type="centered" :static="true" size="lg" jf-modal="konten-page"
        title="{{ str_replace('Kelola ', '', $pageData->title) }}">
        <form id="formData" class="needs-validation" jf-form="konten-page">
            <input type="hidden" name="id" value="">
            <div class="row">
                <div class="col-12 mb-4">
                    <x-form.input type="text" name="title_page" label="Judul" required readonly />
                </div>
                <div class="col-12 mb-4">
                    <x-form.input type="text" name="subtitle_page" label="Subjudul" />
                </div>
                <div class="col-12 mb-4">
                    <x-form.textarea name="konten_page" label="Konten" class="h-300px" data-tinymce="simple" required />
                </div>
                <div class="col-12 mb-4">
                    <x-form.input name="meta_keyword_page" label="Meta Keywords" required />
                </div>
                <div class="col-12 mb-4">
                    <x-form.textarea name="meta_desc_page" label="Meta Description" rows="5" required>
                    </x-form.textarea>
                </div>
            </div>
        </form>
        @slot('action')
            <x-btn.form action="save" class="act-save" jf-save="konten-page" />
        @endslot
    </x-modal>
@endsection

@push('scripts')
    <x-script.crud2></x-script.crud2>
    <script>
        jForm.init({
            name: "konten-page",
            url: {
                add: ``,
                edit: `{{ url('admin/konten-page/data/detail') }}`,
                update: `{{ url('admin/konten-page/update') }}`,
                delete: `{{ url('admin/konten-page/destroy') }}`
            },
        })

        function renderStatus(data) {
            return `
                <span class="badge badge-secondary text-${data == 'published' ? 'success' : (data == 'draft' ? 'gray-800' : (data == 'rejected' ? 'danger' : 'warning'))} p-1">${data.toUpperCase()}</span>
            `
        }
    </script>
@endpush
