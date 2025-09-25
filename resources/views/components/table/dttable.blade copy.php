{{-- author : mwy --}}
@props([
    'builder' => null,
    'export' => 'external', // local or external
    'filter' => '',
    'order' => true,
    'search' => true,
    'default_filter' => null,
    'draw_callback' => '',
    'checkbox_action' => '',
    'checkbox' => false,
    'responsive' => true,
    'paging' => '',
    'scrollY' => '450px',
    'title_export' => '',
    'table_card' => false,
])

@php
    $drawCallback = 'KTMenu.createInstances();';
    $drawCallback .= "$('.dataTables_scrollHead').addClass('bg-light rounded-top-2 py-1');";
    $drawCallback .= $draw_callback;

    if ($builder) {
        // enable checkbox
        if ($checkbox_action != '' || $checkbox) {
            $builder->select([
                'style' => 'multi',
                'selector' => 'td input.checkable',
            ]);
            $drawCallback .= '$(".' . $builder->getTableId() . '-check_all").prop("checked", false).trigger("change");';

            $render = Yajra\DataTables\Html\Column::make([
                'render' => '`<div class="pt-1">' . Blade::render('<x-form.option type="checkbox" label="" class="dttable-check checkable me-0" value=""></x-form.option>') . '</div>`',
            ]);
            $builder->addColumnBefore([
                'title' => Blade::render('<x-form.option type="checkbox" label="" class="dttable-check ' . $builder->getTableId() . '-check_all" value="1"></x-form.option>'),
                'data' => null,
                'orderable' => false,
                'defaultContent' => '',
                'className' => 'text-start w-5px pe-0 text-nowrap',
                'render' => $render->render,
            ]);
        }

        // add column for accordion responsive

        if ($responsive === true) {
            $builder->addColumnBefore(['data' => null, 'width' => '5px', 'title' => '', 'defaultContent' => '&nbsp;', 'className' => 'd-md-none px-0 pt-3 mw-20px text-nowrap', 'orderable' => false]);
            $builder->responsive($responsive);
        }

        if ($paging === false) {
            $builder->paging(false)->scrollY($scrollY)->scrollCollapse(true);
        }

        $domRow = "
                    <'row'<'ps-1 mb-2 col-sm-12 col-md-5 d-flex align-items-center justify-content-md-start dt-custom_filter'><'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end d-none'B>>
                        <'row'<'col-sm-12 bg-body- z-index-3'<'border border-gray-300 rounded-2 position-relative'<'position-absolute w-100 bg-light h-20px rounded-2 z-index-n1'>tr>>>
                    <'row'<'mt-4 col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start gap-2'li><'mt-4 col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>>
                ";
        $domCard = "
                    <'row'<'ps-1 mb-2 col-sm-12 col-md-5 d-flex align-items-center justify-content-md-start dt-custom_filter'><'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end d-none'B>>
                        <'row'<'col-sm-12 z-index-3'<'z-index-n1'tr>>>
                    <'row'<'mt-2 col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'li><'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>>
                ";

        $builder
            ->ajax([
                'url' => $builder->getAjaxUrl(),
                'type' => 'POST',
                'data' => "function(d) { return $.extend({}, d, $(`#" . $builder->getTableId() . '`).dtTableLaraComp().getDataFilter()); }',
            ])
            ->lengthMenu([5, 10, 15, 25, 50, 100, 1000])
            ->parameters([
                'aaSorting' => [],
            ])
            ->pageLength(15)
            ->drawCallback('function() {' . $drawCallback . '}')
            ->language(['search' => '', 'searchPlaceholder' => 'Search...'])
            ->dom($table_card ? $domCard : $domRow);
    }
@endphp

<div class="row mb-2 {{ !$search && !$order && !$filter && !$export ? 'd-none' : '' }}">
    <div class="col-12 col-md-5 d-flex mb-2 mb-md-0 align-items-center justify-content-md-start table-search {{ !$search ? 'd-none' : '' }}">
        <x-form.search id="customSearch-{{ $builder->getTableId() }}" class="w-100 w-md-350px form-control-sm" />
    </div>
    <div class="col-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end table-tools gap-2">
        @if ($order != '')
            <x-btn type="light" class="btn-outline btn-sm text-nowrap w-100 w-md-auto text-gray-700 {{ $builder->getTableId() }}-trigger_order">
                <i class="bi bi-sort-alpha-down fs-3 text-gray-700"></i> Order <span id="order-count"></span>
            </x-btn>
        @endif
        @if ($filter != '')
            <x-btn type="light" class="btn-outline btn-sm text-nowrap w-100 w-md-auto text-gray-700 {{ $builder->getTableId() }}-trigger_filter">
                <i class="bi bi-filter fs-3 text-gray-700"></i> Filter <span id="filter-count"></span>
            </x-btn>
        @endif

        @if ($export == 'local')
            <x-btn type="light" class="btn-outline btn-sm text-success text-nowrap w-100 w-md-auto {{ $builder->getTableId() }}-export-excel">
                <i class="bi bi-file-earmark-spreadsheet fs-3 text-success"></i> Export
            </x-btn>
        @elseif ($export == 'external')
            <x-btn type="light" class="btn-outline btn-sm text-success text-nowrap w-100 w-md-auto {{ $builder->getTableId() }}-export-excel_external">
                <i class="bi bi-file-earmark-excel fs-3 text-success"></i> Export
            </x-btn>
        @endif
    </div>
</div>

<div class="w-100 extra-tools">
    @if ($filter != '')
        <x-card.compact class="bg-light mb-1 border border-gray-300" id="{{ $builder->getTableId() }}-filter" style="display:none;">
            <form id="{{ $builder->getTableId() }}-filter_form">
                {{ $filter }}
            </form>
            <div class="separator separator-dashed my-4"></div>
            <div class="d-flex w-100 justify-content-end">
                <x-btn type="light btn-outline" text="Reset Filter" class="act-filter_reset btn-sm me-2" data-table="{{ $builder->getTableId() }}" />
                <x-btn type="light-primary btn-outline" text="Terapkan Filter" class="act-filter_applay btn-sm" data-table="{{ $builder->getTableId() }}" />
            </div>
        </x-card.compact>
    @endif

    @if ($order != '')
        <x-card.compact class="bg-light mb-1 border border-gray-300" id="{{ $builder->getTableId() }}-order" style="display:none;">
            <form id="{{ $builder->getTableId() }}-order_form">
                <div class="w-100" id="{{ $builder->getTableId() }}-order_list"></div>
                <div class="row">
                    <div class="col-6 col-md-6">
                        <x-form.select placeholder="Kolom Data" name="order_kolom" search="true">
                            @foreach ($builder->getColumns() as $key => $item)
                                @if ($item->orderable)
                                    <option value="{{ $key }}">{{ $item->title }}</option>
                                @endif
                            @endforeach
                        </x-form.select>
                    </div>
                    <div class="col-3 col-md-2">
                        <x-form.select placeholder="Order" name="order_type">
                            <option value="asc">Asc</option>
                            <option value="desc">Desc</option>
                        </x-form.select>
                    </div>
                    <div class="col-3 col-md-2">
                        <x-btn type="light" class="btn-sm btn-outline add-custom_order" text="add" />
                    </div>
                </div>
            </form>
            <div class="separator separator-dashed my-4"></div>
            <div class="d-flex w-100 justify-content-end">
                <x-btn type="light btn-outline" text="Reset Order" class="act-order_reset btn-sm me-2" data-table="{{ $builder->getTableId() }}" />
                <x-btn type="light-primary btn-outline" text="Terapkan Order" class="act-order_applay btn-sm" data-table="{{ $builder->getTableId() }}" />
            </div>
        </x-card.compact>
    @endif

    @if ($checkbox_action != '')
        <x-card.compact class="bg-light- mb-1 border border-gray-300" id="{{ $builder->getTableId() }}-checkbox_action" style="display:none;">
            <div class="d-flex flex-row">
                <div class="d-flex me-2">
                    <x-btn type=" bg-light" class="btn-sm">
                        Data terpilih : <x-badge type="primary" id="checked_row-count"></x-badge>
                    </x-btn>
                </div>
                <div class="d-flex grow-1">
                    <div class="row">
                        <div class="col-12">
                            {{ $checkbox_action }}
                        </div>
                    </div>
                </div>
            </div>
        </x-card.compact>
    @endif
</div>

<div class="table-responsive-">
    @if ($table_card)
        <style>
            .table-card-data> :not(caption)>*>* {
                padding: 0px !important;
            }

            .dataTables_scrollHead {
                display: none;
            }
        </style>
    @endif
    <table class="table {{ $table_card ? 'table-card-data' : 'table-row-bordered gs-4' }} rounded-2 fs-7 gy-2" id="{{ $builder->getTableId() }}">
        <thead class="bg-light text-uppercase fs-7 fw-bold text-gray-500 {{ $table_card ? 'd-none' : '' }}">
            <tr>
                @foreach ($builder->getColumns() as $key => $item)
                    <th class="{{ $paging !== false ? 'py-2' : '' }} bg-light text-nowrap rounded-top-2"></th>
                @endforeach
            </tr>
        </thead>
        <tbody class="{{ $table_card ? 'min-h-250px' : 'text-gray-700' }}"></tbody>
    </table>
</div>

@push('scripts')
    {{ $builder->scripts() }}
    <script>
        $(`#{{ $builder->getTableId() }}`).dtTableLaraComp({
            filter: {{ $filter != '' ? true : 0 }},
            order: {{ $order != '' ? true : 0 }},
            checkbox: {{ $checkbox_action != '' || $checkbox ? true : 0 }},
            checkbox_action: {{ $checkbox_action != '' ? true : 0 }},
            default_filter: {!! json_encode($default_filter) !!}
        }).setTitleExport('{{ $title_export }}')
    </script>
@endpush
