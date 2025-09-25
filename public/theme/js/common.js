var target = document.querySelector("body");
var blockUI = new KTBlockUI(target, {
    message: '<div class="blockui-message bg-body"><span class="spinner-border text-primary"></span> Loading...</div>',
});

$('.modal').on('show.bs.modal', function () {
    $('body').attr('style', '"overflow: hidden; padding-right: 17px;"')
});

$('[data-control="select2"]').each(function () {
    var modalParent = $(this).closest('.modal');
    if (modalParent.length > 0) $(this).attr('data-dropdown-parent', '#' + modalParent.attr('id'));
});

function ajaxRequest(param = {}) {
    // link, data, callback, object_origin = false, swal_success = false
    // alert(Array.isArray(data) )
    if (typeof param.block === 'undefined') {
        param.block = true
    }
    isBlocked = $('.blockui-overlay:visible').length > 0
    if (param.block && !isBlocked) {
        blockUI.block();
    }
    $.ajax({
        url: param.link,
        method: param.method ? param.method : 'POST',
        data: Array.isArray(param.data) ? param.data[0] : param.data,
        processData: Array.isArray(param.data) ? false : true,
        contentType: Array.isArray(param.data) ? false : "application/x-www-form-urlencoded; charset=UTF-8",
        dataType: 'json',
        success: function (resp) {
            if (resp.status || !resp.hasOwnProperty('status')) {
                if (param.swal_success) {
                    Swal.fire({
                        html: resp.message,
                        icon: "success",
                        buttonsStyling: false,
                        showConfirmButton: false,
                        timer: 1500,
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    });
                }
                if (param.callback) param.callback(param.object_origin, resp);
                if (param.block) blockUI.release();
            } else {
                Swal.fire({
                    html: resp.message,
                    icon: "error",
                    buttonsStyling: false,
                    customClass: {
                        confirmButton: "btn btn-danger"
                    }
                });
                if (param.callback) param.callback(param.object_origin, resp);
                if (param.block) blockUI.release();
            }

            if (param.block) blockUI.release();
        },
        error: function (xhr, textStatus, errorThrown) {
            if (xhr.status === 422) {
                // Tangani kesalahan validasi Laravel
                var errors = xhr.responseJSON.errors;
                var errorMessages = [];

                var errorMessage = xhr.responseJSON && typeof xhr.responseJSON.message !== 'undefined'
                    ? xhr.responseJSON.message
                    : 'Internal Error, Failed processing data!';

                errorMessages.push(errorMessage);

                for (var key in errors) {
                    if (errors.hasOwnProperty(key)) {
                        errorMessages.push(errors[key][0]);
                    }
                }
                Swal.fire({
                    html: errorMessages.join('<br>'),
                    icon: "error",
                    buttonsStyling: false,
                    customClass: {
                        confirmButton: "btn btn-danger"
                    }
                });
            } else {
                // Tangani kesalahan umum lainnya
                var errorMessage = typeof xhr.responseJSON.message !== 'undefined' ? xhr.responseJSON.message : 'Internal Error, Failed precessing data!';
                Swal.fire({
                    html: errorMessage,
                    icon: "error",
                    buttonsStyling: false,
                    customClass: {
                        confirmButton: "btn btn-danger"
                    }
                });
            }


            resp = { status: false, data: false }
            if (param.callback && param.object_origin) param.callback(param.object_origin, resp);
            if (param.block) blockUI.release();
        }
    })
}

function number_format(number, decimals = 0, decPoint = ',', thousandsSep = '.') {
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '')
    var n = !isFinite(+number) ? 0 : +number
    var prec = !isFinite(+decimals) ? 0 : Math.abs(decimals)
    var sep = (typeof thousandsSep === 'undefined') ? ',' : thousandsSep
    var dec = (typeof decPoint === 'undefined') ? '.' : decPoint
    var s = ''

    var toFixedFix = function (n, prec) {
        var k = Math.pow(10, prec)
        return '' + (Math.round(n * k) / k)
            .toFixed(prec)
    }

    // @todo: for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.')
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep)
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || ''
        s[1] += new Array(prec - s[1].length + 1).join('0')
    }

    return s.join(dec)
}

function resetForm(parameter) {
    thisForm = null;
    if (typeof parameter === 'string') {
        thisForm = $('#' + parameter)
    } else if (parameter instanceof jQuery) {
        thisForm = parameter
    } else {
        return
    }

    thisForm.find(':input').each(function () {
        var elementType = $(this).prop('nodeName').toLowerCase();
        $(this).closest('.active').removeClass('active')

        switch (elementType) {
            case 'input':
                var inputType = $(this).attr('type');
                if (inputType === 'checkbox' || inputType === 'radio') {
                    $(this).prop('checked', false).trigger('change');
                } else if (inputType === 'file') {
                    $(this).val(null).trigger('change');
                } else if (inputType === 'date' || inputType === 'email') {
                    $(this).val('').trigger('change');
                } else {
                    $(this).val('').trigger('change');
                }
                break;

            case 'select':
                $(this).val('').trigger('change');
                break;

            case 'textarea':
                $(this).val('').trigger('change');
                break;
        }
    });
}

async function confirm(title, desc) {
    return new Promise((resolve) => {
        Swal.fire({
            title: title,
            text: desc,
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Ya, Lanjutkan!",
            cancelButtonText: "Batal",
            customClass: {
                confirmButton: "btn btn-light-danger",
                cancelButton: "btn btn-light-dark"
            },
            reverseButtons: true
        }).then(function (result) {
            if (result.isConfirmed) {
                resolve(true); // Mengembalikan true jika tombol "Confirm" ditekan
            } else {
                resolve(false); // Mengembalikan false jika tombol "Cancel" ditekan atau SweetAlert ditutup
            }
        });
    })
}

function confirmHapus(callbackTrue, callbackFalse) {
    (async function () {
        const confirmed = await confirm('Hapus data ?', 'data yang sudah di hapus tidak dapat dikembalikan');
        if (confirmed) {
            if (callbackTrue) callbackTrue()
        } else {
            if (callbackFalse) callbackFalse()
        }
    })()
}

function confirmSimpan(callbackTrue, callbackFalse) {
    (async function () {
        const confirmed = await confirm('Simpan data ?', 'pastikan data yang akan disimpan sudah sesuai');
        if (confirmed) {
            if (callbackTrue) callbackTrue()
        } else {
            if (callbackFalse) callbackFalse()
        }
    })()
}

function confirmAction(question, desc, callbackTrue, callbackFalse) {
    (async function () {
        const confirmed = await confirm(question, desc);
        if (confirmed) {
            if (callbackTrue) callbackTrue()
        } else {
            if (callbackFalse) callbackFalse()
        }
    })()
}

// ----- SPESIALIZE TIME
function formatDate(UNIX_timestamp) {
    var a = new Date(UNIX_timestamp * 1000);
    var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    var year = a.getFullYear();
    var month = months[a.getMonth()];
    var date = a.getDate();
    var hour = a.getHours();
    var min = a.getMinutes();
    var sec = a.getSeconds();
    var time = date + ' ' + month + ' ' + year + (hour > 0 ? ' ' + hour + ':' + min : '');
    return time;
}

function timeZone() {
    dif = ((- new Date().getTimezoneOffset()) / 60)
    return `GMT ${dif > 0 ? '+' + dif : dif}`
}

function toTimestamp(strDate) {
    strDate = moment(strDate).format('MM/DD/yyyy HH:mm')
    var datum = Date.parse(strDate);
    return datum / 1000;
}

function currDate() {
    var currentdate = new Date();
    var datetime = currentdate.getDate() + "/"
        + (currentdate.getMonth() + 1) + "/"
        + currentdate.getFullYear() + " "
        + currentdate.getHours() + ":"
        + currentdate.getMinutes() + ":"
        + currentdate.getSeconds();
    return datetime;
}


$.fn.countDown = function (msgEnd = '00:00:00') {
    return this.each(function () {
        var timerElement = $(this);
        var endDateTimeString = timerElement.data('end-time');

        function updateTimer() {
            var currentTime = new Date();
            var endTime = new Date(endDateTimeString);

            var timeDifference = endTime - currentTime;

            if (timeDifference > 0) {
                var hours = Math.floor(timeDifference / (1000 * 60 * 60));
                var minutes = Math.floor((timeDifference % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((timeDifference % (1000 * 60)) / 1000);

                hours = hours < 10 ? "0" + hours : hours;
                minutes = minutes < 10 ? "0" + minutes : minutes;
                seconds = seconds < 10 ? "0" + seconds : seconds;

                timerElement.text(hours + ":" + minutes + ":" + seconds);
            } else {
                clearInterval(timerInterval);
                timerElement.text(msgEnd);
            }
        }

        updateTimer(); // Panggil pertama kali untuk menampilkan waktu awal

        var timerInterval = setInterval(updateTimer, 1000);
    });
};

// -----

$.fn.serializeJson = function () {
    var element = $(this);

    // Check if the element is a form
    if (element.is("form")) {
        let formData = {}
        let inputData = element.serializeArray()

        for (var i = 0; i < inputData.length; i++) {
            var item = inputData[i];
            var itemName = item.name.endsWith("[]") ? item.name.slice(0, -2) : item.name;
            var itemValue = item.value;

            if (!formData.hasOwnProperty(itemName)) {
                formData[itemName] = item.name.endsWith("[]") ? [itemValue] : itemValue;
            } else {
                formData[itemName].push(itemValue);
            }
        }

        return formData
    } else {
        return []
    }
}

Array.prototype.ajaxReqLoop = function a(options) {
    const data = this;

    if (!options) return;

    const title = options.title ? options.title : ''
    const message_log = options.message_log ? options.message_log : null
    const data_post = options.data ? options.data : null
    const finish = options.finish ? options.finish : null
    const url = options.url ? options.url : ''

    if (!url) alert('request url has not been specified')

    const logModalId = 'logModal';
    const progressBarId = 'progressBar';
    const logTableId = 'logTable';
    const downloadBtnId = 'downloadBtn';
    const csvDelimiter = ';';
    const batchSize = options.batch_size ? options.batch_size : 1; // Jumlah data yang akan dikirimkan sekaligus

    // Create the log modal
    const createLogModal = () => {
        modal = document.getElementById(logModalId)
        if (modal) document.body.removeChild(modal);

        const logModal = document.createElement('div');
        logModal.id = logModalId;
        logModal.className = 'modal fade';
        logModal.setAttribute('data-bs-backdrop', 'static');
        logModal.innerHTML = `
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Request Log ${title ? ' - ' + title : ''}</h5>
                    </div>
                    <div class="progress rounded-0">
                        <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="modal-body">
                        <div class="row data-log mh-400px overflow-auto table-responsive">
                            <table id="${logTableId}" class="table table-row-bordered border rounded-2 fs-7 gy-2 gs-4">
                                <thead class="bg-light text-uppercase fs-7 fw-bold text-gray-500">
                                    <tr>
                                        <th width="10%">Status</th>
                                        <th width="90%">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" id="${downloadBtnId}" disabled>Download Log</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(logModal);
    };

    // Initialize log data
    const logData = [];

    // Function to add log entry
    const addLogEntry = (index, log, status, resp_msg) => {
        const tbody = document.querySelector(`#${logTableId} tbody`);
        const row = document.createElement('tr');

        const cell2 = document.createElement('td');
        const statusDiv = document.createElement('div');
        statusDiv.innerHTML = `<span class="text-${status ? 'success' : 'danger'}">${status ? 'SUKSES' : 'GAGAL'}</span>`;
        cell2.appendChild(statusDiv);

        const cell3 = document.createElement('td');
        const ketDiv = document.createElement('div');
        ketDiv.innerHTML = `${log}` + (!status && resp_msg ? '<br>message : ' + resp_msg : '')
        cell3.appendChild(ketDiv);

        row.appendChild(cell2);
        row.appendChild(cell3);
        tbody.appendChild(row);

        const container = document.querySelector(`#${logModalId} .data-log`);
        container.scrollTop = container.scrollHeight;
    };

    // Function to download log as CSV
    const downloadLog = () => {
        const csvContent = logData.map((entry) => Object.values(entry).join(csvDelimiter)).join('\n');
        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.style.display = 'none';
        a.href = url;
        a.download = `Request Log - ${title}.csv`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
    };

    // Create the log modal and progress bar
    createLogModal();

    // Show the log modal
    $(`#${logModalId}`).modal('show');

    // Process data in batches
    const processBatch = (batchIndex) => {
        const batchStartIndex = batchIndex * batchSize;
        const batchEndIndex = Math.min((batchIndex + 1) * batchSize, data.length);

        const batchPromises = [];

        for (let i = batchStartIndex; i < batchEndIndex; i++) {
            const item = data[i];

            const promise = new Promise((resolve, reject) => {
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: data_post(item),
                    success: (response) => {
                        logData.push({ No: (i + 1), Log: (message_log ? message_log(item) : `Proses data ke ${i + 1}`), Status: 'Success', Message: '' });
                        addLogEntry(i, (message_log ? message_log(item) : `Proses data ke ${i + 1}`), true, response.hasOwnProperty('message') ? response.message : null);
                        resolve();
                    },
                    error: (xhr, textStatus, errorThrown) => {
                        const res = typeof xhr.responseJSON.message !== 'undefined' ? xhr.responseJSON.message : 'Internal Error, Failed processing data!'
                        logData.push({ No: (i + 1), Log: (message_log ? message_log(item) : `Proses data ke ${i + 1}`), Status: 'Error', Message: res });
                        addLogEntry(i, (message_log ? message_log(item) : `Proses data ke ${i + 1}`), false, res);
                        resolve();
                    },
                });
            });

            batchPromises.push(promise);
        }

        var startTime = new Date().getTime();
        // Wait for all promises in the batch to resolve
        Promise.all(batchPromises).then(() => {
            var endTime = new Date().getTime();
            var duration = (endTime - startTime) / 1000;

            // Calculate progress for the entire batch
            const progress = ((batchEndIndex) / data.length) * 100;

            const progressBar = document.querySelector(`#${logModalId} .progress-bar`);
            progressBar.style.width = `${progress}%`;
            progressBar.textContent = `${batchEndIndex}/${data.length} Selesai (${number_format((data.length - batchEndIndex) * duration, 2)} s left)`;
            progressBar.setAttribute('aria-valuenow', progress);

            // Process next batch if available
            if (batchEndIndex < data.length) {
                processBatch(batchIndex + 1);
            } else {
                progressBar.classList.remove('progress-bar-striped');
                progressBar.textContent = 'Selesai'
                finish(true);
                // All data processed, enable the download button
                $(`#${downloadBtnId}`).prop('disabled', false);
                $(`#${downloadBtnId}`).addClass('btn-light-success');
                blockUI.release();
                Swal.fire({
                    html: `Proses ${title} selesai`,
                    icon: "success",
                    buttonsStyling: false,
                    showConfirmButton: false,
                    timer: 1500,
                    customClass: {
                        confirmButton: "btn btn-primary"
                    }
                });
            }
        });
    };

    // Start processing data with the first batch
    blockUI.block();
    processBatch(0);

    // Handle download button click
    $(`#${downloadBtnId}`).click(() => {
        downloadLog();
    });
};


