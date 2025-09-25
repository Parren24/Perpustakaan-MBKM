@extends(request()->query('snap') == true ? 'layouts.snap' : 'layouts.apps')
@section('toolbar')
    <x-theme.toolbar :breadCrump="$pageData->breadCrump" :title="$pageData->title">
        <x-slot:tools>
        </x-slot:tools>
    </x-theme.toolbar>
@endsection


@section('content')
    <style>
        .portfolio-card {
            position: relative;
            overflow: hidden;
            border-radius: 10px;
            cursor: pointer;
        }

        .portfolio-card img {
            width: 100%;
            display: block;
            transition: transform 0.3s ease;
        }

        .portfolio-card:hover img {
            transform: scale(1.05);
        }

        .card-overlay {
            position: absolute;
            bottom: 0;
            width: 100%;
            background: rgba(0, 0, 0, 0.6);
            color: white;
            padding: 10px;
            transition: background 0.3s ease, opacity 0.3s ease;
            opacity: 0;
        }

        .portfolio-card .card-overlay {
            background: rgba(0, 0, 0, 0.8);
            opacity: 1;
        }

        .overlay-buttons {
            position: absolute;
            bottom: 10px;
            right: 10px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .portfolio-card .overlay-buttons {
            opacity: 1;
        }
    </style>
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-fluid" data-cue="slideInLeft" data-duration="1000"
        data-delay="0">
        <x-table.dttable-card-left :builder="$pageData->dataTable" :table_card="true" :covered="false" :responsive="false" draw_callback=""
            :order="false" :export="false" jf-data="infografis" jf-list="datatable">
            @slot('filter')
                <div class="row">
                </div>
            @endslot
            @slot('action')
                <x-btn type="secondary" class="act-order w-100 w-md-auto">
                    <i class="bi bi-arrow-down-up fs-5"></i> Reorder data
                </x-btn>
            @endslot
        </x-table.dttable-card-left>
    </div>

    <x-modal id="modalForm" type="centered" :static="true" size="" jf-modal="infografis" title="Infografis">
        <form id="formData" class="needs-validation" jf-form="infografis">
            <input type="hidden" name="id" value="">
            <x-form.input class="mb-2" type="text" label="Data" name="nama_infografis" value=""
                readonly></x-form.input>
            <x-form.input class="mb-2" type="text" label="Value" name="value_infografis" value=""
                required></x-form.input>
        </form>
        @slot('action')
            <x-btn.form action="save" class="act-save" jf-save="infografis" />
        @endslot
    </x-modal>

    <x-modal id="modalOrder" type="centered" :static="true" size="" title="Reorder data infografis">
        <form id="formOrder" action="{{ route('app.master.update', ['param1' => 'infografis-order']) }}"
            class="needs-validation">
            <div class="rounded border border-4 border-warning pt-4">
                <h4 class="fw-bold text-warning text-center fs-2">Infografis Spotlight</h4>
                <div id="sortable-highlight" class="connectedSortable mx-0 p-4">
                </div>
            </div>
            <div id="sortable" class="connectedSortable mx-0 p-4">
            </div>
        </form>
        @slot('action')
            <x-btn.form action="save" class="act-save" jf-save="order" />
        @endslot
    </x-modal>
@endsection

@push('scripts')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <x-script.crud2></x-script.crud2>
    <script>
        let tableId = '{{ $pageData->dataTable->getTableId() }}'

        jForm.init({
            name: "infografis",
            url: {
                add: ``,
                update: `{{ route('app.master.update', ['param1' => 'infografis']) }}`,
                edit: `{{ route('app.master.data', ['param1' => 'infografis-detail']) }}`,
                delete: `{{ route('app.master.destroy') }}`,
            }
        })

        $(document).on('click', '.act-order', function() {
            var data = $(`#${tableId}`).DataTable().rows().data()

            $('#sortable-highlight').html('')
            $('#sortable').html('')

            $.each(data, function(index, item) {
                if (index <= 3) {
                    $('#sortable-highlight').append(`
                                <div class="d-flex justify-content-between rounded bg-secondary p-2 mb-2" data-id="${item.id}">
                                    <i class="bi bi-arrow-down-up fs-5 me-2"></i>${item.nama_infografis}
                                    <span class="fw-bold">${item.value_infografis}</span>
                                </div>
                            `)
                } else {
                    $('#sortable').append(`
                                <div class="d-flex justify-content-between rounded bg-secondary p-2 mb-2" data-id="${item.id}">
                                    <i class="bi bi-arrow-down-up fs-5 me-2"></i>${item.nama_infografis}
                                    <span class="fw-bold">${item.value_infografis}</span>
                                </div>
                            `)
                }
            });

            $('#modalOrder').modal('show')
            $("#sortable, #sortable-highlight").sortable({
                connectWith: ".connectedSortable",
                receive: function(event, ui) {
                    if ($(this).attr("id") === "sortable-highlight") {
                        let $items = $(this).children();
                        if ($items.length > 4) {
                            console.log('overflow')
                            let $overflow = $items.slice(4);
                            $overflow.prependTo("#sortable");
                        }
                    }
                }
            }).disableSelection();
        })

        $(document).on('click', '.act-save[jf-save="order"]', function() {
            var orderHighlight = $('#sortable-highlight').sortable('toArray', {
                attribute: 'data-id'
            });

            var orderNormal = $('#sortable').sortable('toArray', {
                attribute: 'data-id'
            });

            var order = orderHighlight.concat(orderNormal);

            ajaxRequest({
                link: `{{ route('app.master.update', ['param1' => 'infografis-order']) }}`,
                data: {
                    'id[]': order
                },
                block: true,
                swal_success: true,
                callback: function(origin, resp) {
                    if (resp.status) {
                        $('#modalOrder').modal('hide')
                        $('#' + tableId).DataTable().ajax.reload(null, false)
                    }
                }
            })
        })

        function card(full) {
            return `
                <div class="col-md-2 mb-6">
                    <div class="portfolio-card position-relative h-150px border ${full.seq <= 4 ? 'border-4 border-warning' :''}">
                        <div class="position-absolute top-0 end-0 ${full.seq <= 4 ? 'bg-warning text-white' :'bg-theme text-dark'} fw-bold px-2 py-1 rounded-start">
                            ${full.value_infografis}
                        </div>
                        <div class="d-flex w-100 h-100 justify-content-center mt-5"><i class="bi bi-${full.icon_infografis} fs-5hx"></i></div>
                        <div class="card-overlay d-flex justify-content-between align-items-center ${full.seq <= 4 ? 'bg-warning text-white' :'bg-theme text-dark'} ${full.seq <= 4 ? 'fw-bolder' :''}">
                            <div class="d=flex align-items-center justify-content-center w-100 h-100">${full.seq <= 4 ? '<i class="bi bi-star-fill fs-2 text-white me-2"></i>' :''}${full.nama_infografis}</div>
                            <div class="overlay-buttons">
                                <a class="btn btn-icon btn-sm mh-25px mw-25px btn-light-warning act-edit me-1" jf-edit="${full.id}"><i class="ki-outline ki-notepad-edit fs-3"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            `
        }
    </script>
@endpush
