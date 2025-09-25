{{-- author : mwy --}}
@props([
    'title' => '',
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
    'action' => '',
    'class' => '',
    'covered' => true,
])

@php
    $drawCallback = 'KTMenu.createInstances();longText.init();';
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
                'render' => '`<div class="">' . Blade::render('<x-form.option type="checkbox" label="" class="dttable-check checkable me-0" value=""></x-form.option>') . '</div>`',
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
        } else {
            $builder->scrollX(true)->scrollCollapse(true);
        }

        if ($paging === false) {
            $builder->paging(false)->scrollY($scrollY)->scrollCollapse(true);
        }

        $domRow = " <'row'<'col-sm-12 px-0'<'z-index-n1'tr>>>
                    <'d-flex gap-4 justify-content-center flex-wrap'<'mt-4 d-flex align-items-center justify-content-center justify-content-md-start gap-2'li><'mt-4 d-flex align-items-center justify-content-center justify-content-md-end flex-grow-1-'p>>
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
            ->serverSide(false)
            ->pageLength(10)
            ->drawCallback('function() {' . ($table_card ? 'tableCard' . $builder->getTableId() . '();' : '') . '' . $drawCallback . '}')
            ->language(['search' => '', 'searchPlaceholder' => 'Search...'])
            ->dom($table_card ? $domCard : $domRow);
    }
@endphp
<div class="{{ $covered ? 'border border-gray-200 rounded-2 p-3' : '' }}">
    @if ($title != '')
        <div class="d-flex w-100 rounded-1 rounded-bottom-0">
            {!! $title !!}
        </div>
        <div class="separator separator-dashed mb-3 my-2"></div>
    @endif
    <div class="d-flex flex-wrap gap-2 mb-3 {{ !$search && !$order && !$filter && !$export ? 'd-none' : '' }}">
        <div class="flex-grow-1 d-flex align-items-center justify-content-md-start table-search gap-2">
            <div class="d-flex align-items-center position-relative w-100 mw-250px {{ !$search ? 'd-none' : '' }}">
                <i class="ki-outline ki-magnifier fs-3 position-absolute ms-4"></i>
                <input type="text" class="form-control border border-gray-200 form-control-solid ps-12 w-100 mw-250px form-control-sm" id="customSearch-{{ $builder->getTableId() }}" placeholder="Cari..">
            </div>
            @if ($order != '')
                <x-btn type="secondary" class="px-3 btn-sm text-nowrap border border-gray-200 text-gray-700 {{ $builder->getTableId() }}-trigger_order" title="Order Data">
                    <i class="bi bi-sort-alpha-down fs-3 text-gray-700 pe-0"></i><span id="order-count"></span>
                </x-btn>
            @endif
            @if ($filter != '')
                <x-btn type="secondary" class="px-3 btn-sm text-nowrap border border-gray-200 text-gray-700 {{ $builder->getTableId() }}-trigger_filter" title="Filter Data">
                    <i class="bi bi-filter fs-3 text-gray-700 pe-0"></i><span id="filter-count"></span>
                </x-btn>
            @endif

            @if ($export == 'local')
                <x-btn type="light-success" class="btn-icon btn-sm text-nowrap border border-gray-200 {{ $builder->getTableId() }}-export-excel" title="Download Data">
                    <i class="bi bi-file-earmark-spreadsheet fs-3 pe-0"></i>
                </x-btn>
            @elseif ($export == 'external')
                <x-btn type="light-success" class="px-3 btn-sm text-nowrap border border-gray-200 {{ $builder->getTableId() }}-export-excel_external" title="Download Data">
                    <i class="bi bi-file-earmark-excel fs-3 pe-0"></i>
                </x-btn>
            @endif
        </div>
        <div class="d-flex align-items-center justify-content-end gap-2 w-100 w-md-auto">
            @if ($action != '')
                {!! $action !!}
            @endif
        </div>
    </div>

    <div class="w-100 extra-tools">
        @if ($filter != '')
            <x-card.compact class="bg-light border-0 mb-2 rounded-1" id="{{ $builder->getTableId() }}-filter" style="display:none;">
                <form id="{{ $builder->getTableId() }}-filter_form">
                    {{ $filter }}
                </form>
                <div class="separator separator-dashed my-4"></div>
                <div class="d-flex w-100 justify-content-end">
                    <x-btn type="secondary" text="Reset Filter" class="act-filter_reset btn-sm me-2" data-table="{{ $builder->getTableId() }}" />
                    <x-btn type="light-primary" text="Terapkan Filter" class="act-filter_applay btn-sm" data-table="{{ $builder->getTableId() }}" />
                </div>
            </x-card.compact>
        @endif

        @if ($order != '')
            <x-card.compact class="bg-light mb-2 border-0 rounded-1" id="{{ $builder->getTableId() }}-order" style="display:none;">
                <form id="{{ $builder->getTableId() }}-order_form">
                    <div class="w-100" id="{{ $builder->getTableId() }}-order_list"></div>
                    <div class="row">
                        <div class="col-6 col-md-6">
                            <x-form.select placeholder="Kolom Data" name="order_kolom" search="true" class="border-0">
                                @foreach ($builder->getColumns() as $key => $item)
                                    @if ($item->orderable)
                                        <option value="{{ $key }}">{{ $item->title }}</option>
                                    @endif
                                @endforeach
                            </x-form.select>
                        </div>
                        <div class="col-3 col-md-2">
                            <x-form.select placeholder="Order" name="order_type" class="border-0">
                                <option value="asc">Asc</option>
                                <option value="desc">Desc</option>
                            </x-form.select>
                        </div>
                        <div class="col-3 col-md-2">
                            <x-btn type="light" class="btn-sm btn-secondary add-custom_order" text="add" />
                        </div>
                    </div>
                </form>
                <div class="separator separator-dashed my-4"></div>
                <div class="d-flex w-100 justify-content-end">
                    <x-btn type="secondary" text="Reset Order" class="act-order_reset btn-sm me-2" data-table="{{ $builder->getTableId() }}" />
                    <x-btn type="light-primary" text="Terapkan Order" class="act-order_applay btn-sm" data-table="{{ $builder->getTableId() }}" />
                </div>
            </x-card.compact>
        @endif

        @if ($checkbox_action != '')
            <x-card.compact class="bg-light mb-2 border-0 rounded-1" id="{{ $builder->getTableId() }}-checkbox_action" style="display:none;">
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

    <div class="mt-3 {{ $responsive !== true ? 'table-responsive-' : '' }}">
        <style>
            .dataTables_length {
                padding: 0px !important;
            }

            .dataTables_paginate {
                padding: 0px !important;
            }

            .dataTables_info {
                padding: 0px !important;
            }
        </style>
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
        <div id="tableCard{{ $builder->getTableId() }}" class="row table-card {{ $class }}" {{ $attributes }}></div>
        <table class="table {{ $table_card ? 'd-none' : '' }} {{ $class }} {{ $table_card ? 'table-card-data' : 'table-row-dashed gs-4 table-striped border- border-start-0 border-end-0' }} fs-7 gy-2" id="{{ $builder->getTableId() }}" {{ $attributes }}>
            <thead class="text-uppercase fs-7 fw-bold bg-gray-200- bg-opacity-50- text-gray-500 border-0 {{ $table_card ? 'd-none' : '' }}">
            </thead>
            <tbody class="text-gray-700"></tbody>
        </table>
    </div>
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

        @if ($table_card)
            let tableCard{{ $builder->getTableId() }} = function() {
                let content = ''
                el = $('#{{ $builder->getTableId() }} tbody tr td')
                $.each(el, function(i, val) {
                    content += $(val).html().replace('in table', '')
                })
                $(`#tableCard{{ $builder->getTableId() }}`).html(content)
            }
            tableCard{{ $builder->getTableId() }}();
        @endif
    </script>
@endpush
