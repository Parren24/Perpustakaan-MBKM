/*
    author : @wahyudibinsaid
    desc : script datatable common tools for datatable component in laravel
*/
"use strict";
let cachedTables = {}

$.fn.dtTableLaraComp = function (param = {}){
    var attr = {}
    attr['order'] = param.hasOwnProperty('order') ? param.order : true; 
    attr['filter'] = param.hasOwnProperty('filter') ? param.filter : true; 
    attr['default_filter'] = param.hasOwnProperty('default_filter') ? param.default_filter : {}; 
    attr['checkbox'] = param.hasOwnProperty('checkbox') ? param.checkbox : false; 
    attr['checkbox_action'] = param.hasOwnProperty('checkbox_action') ? param.checkbox_action : false; 
    attr['export'] = param.hasOwnProperty('export') ? param.export : true; 
    attr['table_id'] = this.attr('id');

    if (cachedTables[attr['table_id']]) {
        return cachedTables[attr['table_id']]; // Mengembalikan objek yang sudah ada
    }

    var tools = CustomToolsLaravelDatatable(attr['table_id']).init(attr)

    cachedTables[attr['table_id']] = tools;

    return tools;
};

var CustomToolsLaravelDatatable = function (tableId) {
    // Shared variables
    var table = tableId;
    var datatable;
    var defaultFilter;
    var titleExport;

    // Private functions
    var initTable = function () {
        datatable = $(`#${table}`)
    }

    // Search Datatable
    var handleSearch = () => {
        let customSearch = document.querySelector(`#customSearch-${table}`);
        customSearch.addEventListener('keyup', function(e) {
            datatable.DataTable().search(e.target.value).draw();
        });
    }

    // handle filter
    var handleFilter = () => {
        $(document).on('click', `.act-filter_reset[data-table="${table}"], .act-filter_applay[data-table="${table}"]`, function() {
            if ($(this).hasClass('act-filter_reset')) resetForm(`${table}-filter_form`)
            $(`#${table}`).DataTable().ajax.reload(null, false)
        })

        $(document).on('click', `.${table}-trigger_filter`, function() {
            $(`#${table}-filter`).toggle('fast', 'swing')
        })
    }

    var customFilter = function() {
        let formData = {}

        if (document.getElementById(`${table}-filter_form`)) {
            // var formElements = document.getElementById(`${table}-filter_form`)
            // let inputData =  $(formElements).serializeArray()
            formData = $(`#${table}-filter_form`).serializeJson()

            let countFilter = 0
            for (const property in formData)
                if (formData[property] != '') countFilter++
            $(`.${table}-trigger_filter #filter-count`).html(`${countFilter > 0 ? '('+countFilter+')' : ''}`)
        }
        // add daefault filter
        if (defaultFilter) {
            for (const key in defaultFilter) {
                if (Object.hasOwnProperty.call(defaultFilter, key)) {
                    formData[key] = defaultFilter[key];
                }
            }
        }

        return formData;
    }

    var setDefaultFilter = function (filter) {
        defaultFilter = filter
    }

    var handleOrder = function () {
        $(document).on('click', `.${table}-trigger_order`, function() {
            $(`#${table}-order`).toggle('fast', 'swing')
        })

        $(document).on('click', `#${table}-order_form .add-custom_order`, function() {
            var order_kolom = $(`#${table}-order_form [name="order_kolom"]`).val()
            var order_type = $(`#${table}-order_form [name="order_type"]`).val()

            if (order_kolom && order_type) {
                var text_order_kolom = $(`#${table}-order_form [name="order_kolom"]`).find('option:selected').text()
                var text_order_type = $(`#${table}-order_form [name="order_type"]`).find('option:selected').text()

                $(`#${table}-order_list`).append(`
                    <div class="row mb-2">
                        <input class="form-control form-control-sm ${table}-custom-order" type="hidden" name="${order_kolom}" value="${order_type}" />
                        <div class="col-6 col-md-6">
                            <input class="form-control bg-body bg-opacity-15 text-gray-800 form-control-sm" type="text" value="${text_order_kolom}" disabled/>
                        </div>
                        <div class="col-3 col-md-2">
                            <input class="form-control bg-body bg-opacity-15 text-gray-800 form-control-sm" type="text" value="${text_order_type}" disabled/>
                        </div>
                        <div class="col-3 col-md-2">
                            <a href="javascript:;" type="light" class="btn btn-icon btn-sm btn-outline" onclick="return $(this).closest('.row').remove()" /> X </a>
                        </div>
                    </div>
                `);

                $(`#${table}-order_form [name="order_kolom"]`).find('option[value=""]').prop('selected', true).trigger('change')
                $(`#${table}-order_form [name="order_type"]`).find('option[value=""]').prop('selected', true).trigger('change')
            }
        })

        $(document).on('click', `.act-order_applay[data-table="${table}"], .act-order_reset[data-table="${table}"]`, function() {
            if ($(this).hasClass('act-order_reset')) {
                $(`.${table}-trigger_order #order-count`).html('')
                $(`#${table}-order_list`).html('')
                resetForm(`${table}-order_form`)
            }
            var formElements = document.getElementsByClassName(`${table}-custom-order`);
            var dataOrder = []
            for (var i = 0; i < formElements.length; i++) {
                var element = formElements[i];
                if (element.name && element.value && element.value != '0') dataOrder.push([element.name, element.value])
            }

            $(`.${table}-trigger_order #order-count`).html(`${dataOrder.length > 0 ? '('+dataOrder.length+')' : ''}`)
            $(`#${table}`).DataTable().order(dataOrder).draw();
        })
    }

    var handleCheckBox = function () {
        $(document).on('change', `.${table}-check_all`, function() {
            var set = $(`#${table} .checkable`);
            var checked = $(this).is(':checked');

            $(set).each(function() {
                if (checked) {
                    $(this).prop('checked', true);
                    $(`#${table}`).DataTable().rows($(this).closest('tr')).select();
                } else {
                    $(this).prop('checked', false);
                    $(`#${table}`).DataTable().rows($(this).closest('tr')).deselect();
                }
            });
        });

        $(document).on('change', `#${table} .checkable`, function() {
            var selected = $(`#${table} .checkable:checked`).length

            if (selected <= 0) {
                $(`.${table}-check_all`).prop('checked', false)
            }
        });
    }

    var checkboxAction = function () {
        $(document).on('change', `#${table} .checkable, .${table}-check_all`, function() {
            var checkboxAction = $(`#${table}-checkbox_action`)
            var selected = $(`#${table} .checkable:checked`).length
            $(`#${table}-checkbox_action #checked_row-count`).html(selected + ' data')

            var isHidden = checkboxAction.is(':hidden');

            if (selected > 0 && isHidden) {
                checkboxAction.attr('style', '').fadeIn('fast', 'swing');
            } else if (selected <= 0) {
                checkboxAction.attr('style', 'display:none').fadeOut('fast', 'swing');
            }
        });
    }

    var handleExport = function() {
        titleExport = document.title

        $(document).on('click', `.${table}-export-excel`, function() {
            $(`#${table}_wrapper .buttons-excel`).trigger('click')
        })

        $(document).on('click', `.${table}-export-excel_external`, function() {
            var pageInfo = datatable.DataTable().page.info()
            var dataFilter = customFilter()
            var dataUrl = datatable.DataTable().ajax.url() + `?export=true`

            exportXlsExternal({
                page_info : pageInfo,
                data_filter : dataFilter,
                data_url : dataUrl
            })
        })
    }

    var exportXlsExternal = function(param) {
        blockUI.block();

        let pageInfo = param.page_info
        let maxPerPage = 30000
        let data = param.data_filter

        let maxLoop = Math.ceil(pageInfo.recordsTotal / maxPerPage)
        var currLoop = 1
        var alldata = []
        loadData(0)

        function loadData(start) {
            data['start'] = start
            data['length'] = maxPerPage
            if (currLoop <= maxLoop) {
                ajaxRequest({
                    link: param.data_url,
                    data: data,
                    callback: function(origin, resp) {
                        if (resp.hasOwnProperty('data')) {
                            alldata = [...alldata, ...resp.data]
                            currLoop++;
                            loadData(maxPerPage * currLoop + 1)
                        }
                    },
                    swal_success: false,
                    block: false
                })
            } else {
                var filename = titleExport+'.xlsx';
                var ws = XLSX.utils.json_to_sheet(alldata);
                var wb = XLSX.utils.book_new();
                XLSX.utils.book_append_sheet(wb, ws, "data");
                XLSX.writeFile(wb, filename);
                setTimeout(blockUI.release(), 5000);
            }
        }
    }

    // Public methods
    return {
        init: function (attr) {
            if ( !document.querySelector(`#${table}`) ) {
                return;
            }

            initTable();
            handleSearch();
            if (attr.filter) handleFilter();
            if (attr.order) handleOrder();
            if (attr.checkbox || attr.checkbox_action) handleCheckBox();
            if (attr.checkbox_action) checkboxAction();
            if (attr.default_filter) setDefaultFilter(attr.default_filter)
            if (attr.export) handleExport()

            return {
                getDataFilter : function() {
                    return customFilter()
                },
                setDefaultFilter : function(filter) {
                    setDefaultFilter(filter)
                },
                getSelectedData() {
                    var result = []
                    var selectedData = $(`#${table}`).DataTable().rows({
                        selected: true
                    }).data()
                    for (let i = 0; i < selectedData.length; i++) {
                        result.push(selectedData[i])
                    }

                    return result
                },
                clearSelectedData() {
                    $(`#${table}`).DataTable().rows().deselect();
                    $(`#${ table } .checkable`).prop('checked', false).trigger('change')
                    $(`.${ table }-check_all`).prop('checked', false).trigger('change')
                },
                setTitleExport(title) {
                    title != '' ? titleExport = title : false
                }
            }
        }
    };
};