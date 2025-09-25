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
            :order="false" :export="false" jf-data="event-kategori" jf-list="datatable">
        </x-table.dttable-card-left>
    </div>
@endsection

@push('scripts')
    <script>
        function card(full) {
            return `
                <div class="col-xl-3 col-md-6 col-12 mb-6">
                    <div class="d-flex w-100 flex-column rounded-4 border border-1 p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div
                                class="symbol symbol-50px bg-primary text-light h-50px w-50px me-3 d-flex align-items-center justify-content-center fw-bolder fs-5">
                                ${full.alias_jurusan}
                            </div>
                            <div class="d-flex align-items-center justify-content-end my-6 gap-1">
                                <a class="btn btn-icon btn-sm mh-25px mw-25px btn-light-primary act-delete me-1" href="{{ url('admin/konten-jurusan') }}/${full.id}"" title="Kelola Konten Situs"><i class="ki-outline ki-icon fs-3"></i></a>
                                <a class="btn btn-icon btn-sm mh-25px mw-25px btn-light-primary act-delete me-1" href="="{{ url('admin/konten-page/jurusan-site/') }}/${full.id}"" title="Kelola Halaman Statis"><i class="ki-outline ki-menu fs-3"></i></a>
                            </div>
                        </div>
                        <div class="d-flex justify-content-start flex-column flex-grow-1 mt-4 fw-bold fs-5">
                            ${full.jurusan}
                            <div class="d-flex flex-row fs-8 align-items-center">
                                <a href="${full.url_jurusan}" target="_blank">
                                    <div class="d-flex flex-row align-items-center">
                                        ${full.url_jurusan}<i class="bi bi-box-arrow-up-right fs-9 ms-1 text-primary"></i>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="separator separator-dashed border-gray-10 my-4"></div>
                        <div class="d-flex align-items-center">
                            <div class="d-flex justify-content-center flex-column border border-1 p-2 w-100px rounded-2 text-gray-700 border-secondary me-4">
                                <small class="fw-bolder">Post</small>
                                <div class="d-flex flex-row">
                                    0 <i class="ki-outline ki-pin text-gray-700 fs-4 ms-3"></i>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center flex-column border border-1 p-2 w-100px rounded-2 text-gray-700 border-secondary me-4">
                                <small class="fw-bolder">Agenda</small>
                                <div class="d-flex flex-row">
                                    0 <i class="ki-outline ki-calendar-2 text-gray-700 fs-4 ms-3"></i>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center flex-column border border-1 p-2 w-100px rounded-2 text-gray-700 border-secondary me-4">
                                <small class="fw-bolder">Testi Alumni</small>
                                <div class="d-flex flex-row">
                                    0 <i class="ki-outline ki-teacher text-gray-700 fs-4 ms-3"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `
        }
    </script>
@endpush
{{-- <div class="d-flex align-items-center justify-content-end my-6 gap-1">
    <x-btn type="secondary" link="{{ url('admin/konten-jurusan') }}/${full.id}">
        <i class="bi bi-globe fs-3"></i> Konten Situs
    </x-btn>
    <x-btn type="secondary" link="{{ url('admin/konten-page/jurusan-site/') }}/${full.id}">
        <i class="bi bi-layers-fill fs-3"></i> Halaman Statis
    </x-btn>
</div> --}}
