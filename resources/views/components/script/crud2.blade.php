<script>
    var jForm = {
        init: function(options) {
            // Lakukan inisialisasi proses CRUD dengan nama yang diberikan
            var name = options.name;
            var param = options.param;
            var base_url = options.base_url || '';
            var onAdd = options.onAdd || function() {}
            var onEdit = options.onEdit || function() {}
            var onDelete = options.onDelete || function() {}
            var onSave = options.onSave || function() {}
            var beforeSave = options.beforeSave || function() {}
            var url = {
                add: options.url && options.url.add ? options.url.add : base_url + '/store' + (param ? '/' +
                    param.toLowerCase() : ''),
                edit: options.url && options.url.edit ? options.url.edit : base_url + '/data/detail' + (
                    param ? '/' + param.toLowerCase() : ''),
                update: options.url && options.url.update ? options.url.update : base_url + '/update' + (
                    param ? '/' + param.toLowerCase() : ''),
                delete: options.url && options.url.delete ? options.url.delete : base_url + '/destroy' + (
                    param ? '/' + param.toLowerCase() : ''),
                filemanager: options.url && options.url.filemanager ? options.url.filemanager : ''
            };
            // Menyalin properti URL tambahan yang mungkin dikirimkan
            for (var key in options.url) {
                if (options.url.hasOwnProperty(key) && !url.hasOwnProperty(key)) {
                    url[key] = options.url[key];
                }
            }

            var refreshData = function() {
                var typeList = $('[jf-data="' + name + '"]').attr('jf-list');

                if (typeList == 'datatable') {
                    $('table[jf-data="' + name + '"]').DataTable().ajax.reload(null, false)
                } else {
                    if (typeList) dataList()
                }
            }
            var refreshUiInput = function(type) {
                var modalName = $('[jf-modal="' + name + '"]');
                var tabName = $('[jf-tab="' + name + '"]');

                // Periksa apakah modal atau tab ada, lalu tampilkan sesuai kondisi
                if (modalName.length > 0) {
                    // Tampilkan modal
                    if (type == 'show') modalName.modal('show');
                    else modalName.modal('hide');
                } else if (tabName.length > 0) {
                    // Tampilkan tab
                    if (type == 'show') tabName.removeClass('d-none');
                    else tabName.addClass('d-none');
                }
            }
            var pupulateForm = function(formData) {
                $('[jf-form="' + name + '"]').find(':input, textarea, select').each(function() {
                    var elementName = $(this).attr('name');
                    // console.log(elementName.replace(/[\[\]]/g, '') + '--')

                    // Periksa apakah ada data yang sesuai dalam respons AJAX
                    if (elementName && (formData.hasOwnProperty(elementName) || formData
                            .hasOwnProperty(elementName.replace(/[\[\]]/g, '')))) {
                        var value = formData[elementName];

                        // Tentukan jenis elemen formulir
                        var elementType = $(this).prop('type');

                        // Atur nilai sesuai dengan jenis elemen
                        if (elementType === 'checkbox' || elementType === 'radio') {
                            // Untuk checkbox dan radio button, periksa apakah nilai cocok dan set properti 'checked'
                            $(this).prop('checked', value == $(this).val());
                        } else if ($(this).is('select[multiple]')) {
                            // Untuk select dengan opsi ganda, atur nilai opsi sesuai dengan array nilai
                            if (Array.isArray(formData[elementName])) {
                                $(this).val(value);
                            }
                            if (Array.isArray(formData[elementName.replace(/[\[\]]/g, '')])) {
                                $(this).val(formData[elementName.replace(/[\[\]]/g, '')]);
                            }
                        } else if (elementType == 'file') {} else {
                            // Untuk elemen lainnya, gunakan .val()
                            $(this).val(value);
                        }


                        if ($(this).data('tinymce')) {
                            tinymce.editors.forEach(editor => {
                                if (editor.settings.selector === '[jf-form="' + name +
                                    '"] [data-tinymce="simple"][name="' + elementName + '"]'
                                ) {
                                    editor.setContent(value || '');
                                }
                                if (editor.settings.selector === '[jf-form="' + name +
                                    '"] [data-tinymce="advance"][name="' + elementName +
                                    '"]') {
                                    editor.setContent(value || '');
                                }
                            });
                        }
                    }
                });
            }

            // Tambahkan event handler untuk tombol tambah
            $(document).on('click', '[jf-add="' + name + '"]', function() {
                var form = $('[jf-form="' + name + '"]').attr('id')
                $('[jf-form="' + name + '"]').attr('action', url.add)
                resetForm(form)
                refreshTinyMCE()
                refreshCustomStyle()
                onAdd()
                refreshUiInput('show')
            });

            // Tambahkan event handler untuk tombol edit
            $(document).on('click', '[jf-data="' + name + '"] [jf-edit]', function() {
                var form = $('[jf-form="' + name + '"]').attr('id')
                $('[jf-form="' + name + '"]').attr('action', url.update)
                resetForm(form)
                refreshTinyMCE()

                var editId = $(this).attr('jf-edit');
                var paramData = {}

                const attributes = $(this).data();
                for (const key in attributes) {
                    if (Object.hasOwnProperty.call(attributes, key)) {
                        paramData[key] = attributes[key];
                    }
                }

                paramData['id'] = editId

                ajaxRequest({
                    link: url.edit,
                    data: paramData,
                    callback: function(origin, resp) {
                        data = resp.data
                        refreshUiInput('show')
                        pupulateForm(data)
                        refreshCustomStyle()
                        onEdit(data)
                    }
                })
            });

            // Tambahkan event handler untuk tombol hapus
            $(document).on('click', '[jf-data="' + name + '"] [jf-delete]', function() {
                var deleteId = $(this).attr('jf-delete');
                var paramData = {}

                const attributes = $(this).data();
                for (const key in attributes) {
                    if (Object.hasOwnProperty.call(attributes, key)) {
                        paramData[key] = attributes[key];
                    }
                }

                paramData['id'] = deleteId

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
                            link: url.delete,
                            data: paramData,
                            callback: function(origin, resp) {
                                refreshData()
                                onDelete(resp.data)
                            }
                        })
                    }
                });
            });

            // Tambahkan event handler untuk tombol simpan
            $(document).on('click', '[jf-save="' + name + '"]', function() {
                console.log(123)
                var form = $('[jf-form="' + name + '"]');
                var confirm = $(this).data('confirmation')
                var message = $(this).data('message')

                beforeSave()

                if (form.length > 0) {
                    if (confirm) {
                        Swal.fire({
                            title: "Penting !",
                            text: message ||
                                "Pastikan seluruh data isian pada formulir sudah sesuai sebelum proses dilanjutkan.",
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonText: "Ya, Lanjutkan!",
                            cancelButtonText: "Batal",
                            customClass: {
                                confirmButton: "btn btn-light-danger",
                                cancelButton: "btn btn-secondary"
                            },
                            reverseButtons: true
                        }).then(function(result) {
                            if (result.value) {
                                form.submit()
                            }
                        });
                    } else {
                        form.submit()
                    }
                }
            });

            $(document).on('submit', '[jf-form="' + name + '"]', function(e) {
                e.preventDefault()

                syncTinyMCEContent()
                var form = $('[jf-form="' + name + '"]');
                var formData = [new FormData(form[0])];

                ajaxRequest({
                    link: form.attr('action'),
                    data: formData,
                    callback: function(origin, resp) {
                        if (resp.status) {
                            refreshData()
                            refreshUiInput('hide')
                            onSave(resp.data)
                        }
                    },
                    swal_success: true
                })
            })

            function refreshCustomStyle() {
                // Seleksi elemen-elemen yang ingin diinisialisasi ulang
                var elements = document.querySelectorAll('[jf-form="' + name +
                    '"] [data-control="select2"], [jf-form="' + name + '"] [data-kt-select2="true"]');

                // Loop melalui setiap elemen dan inisialisasi ulang
                elements.forEach(function(e) {
                    // Hancurkan instans Select2 yang ada
                    if ($(e).data('select2')) {
                        $(e).select2('destroy');
                    }

                    // Opsi untuk Select2
                    var t = {
                        dir: document.body.getAttribute("direction")
                    };

                    // Sembunyikan pencarian jika diperlukan
                    if ("true" == e.getAttribute("data-hide-search")) {
                        t.minimumResultsForSearch = Infinity;
                    }

                    // Inisialisasi ulang Select2 dengan opsi
                    $(e).select2(t);

                    // Pengaturan tambahan jika elemen memiliki data-dropdown-parent dan multiple
                    if (e.hasAttribute("data-dropdown-parent") && e.hasAttribute("multiple")) {
                        var n = document.querySelector(e.getAttribute("data-dropdown-parent"));
                        if (n && n.hasAttribute("data-kt-menu")) {
                            var i = KTMenu.getInstance(n);
                            if (!i) {
                                i = new KTMenu(n);
                            }
                            if (i) {
                                $(e).on("select2:unselect", function(t) {
                                    e.setAttribute("data-multiple-unselect", "1");
                                });
                                i.on("kt.menu.dropdown.hide", function(t) {
                                    if ("1" === e.getAttribute("data-multiple-unselect")) {
                                        e.removeAttribute("data-multiple-unselect");
                                        return false;
                                    }
                                });
                            }
                        }
                    }

                    // Tandai elemen sebagai diinisialisasi
                    e.setAttribute("data-kt-initialized", "1");
                });

                // kt button
                // Seleksi semua elemen dengan atribut [data-kt-buttons="true"]
                const ktButtons = document.querySelectorAll('[data-kt-buttons="true"]');

                // Iterasi setiap elemen yang ditemukan
                ktButtons.forEach(buttonGroup => {
                    // Seleksi target yang akan diberi class 'active'
                    const targetSelector = buttonGroup.hasAttribute('data-kt-buttons-target') ?
                        buttonGroup.getAttribute('data-kt-buttons-target') : '.btn';
                    const targetElements = buttonGroup.querySelectorAll(targetSelector);

                    // Iterasi setiap target element di dalam buttonGroup
                    targetElements.forEach(targetElement => {
                        // Seleksi semua input radio di dalam elemen targetElement
                        const radioButtons = targetElement.querySelectorAll(
                            'input[type="radio"]');

                        // Fungsi untuk mengecek apakah ada radio button yang checked
                        const checkActiveRadio = () => {
                            let isActive = false;
                            radioButtons.forEach(radio => {
                                if (radio.checked) {
                                    isActive = true;
                                }
                            });

                            // Tambahkan atau hapus class 'active' berdasarkan status radio button
                            if (isActive) {
                                targetElement.classList.add('active');
                            } else {
                                targetElement.classList.remove('active');
                            }
                        };

                        // Tambahkan event listener ke setiap radio button
                        radioButtons.forEach(radio => {
                            radio.addEventListener('change', checkActiveRadio);
                        });

                        // Cek status awal ketika halaman dimuat
                        checkActiveRadio();
                    });
                });
            }

            function refreshTinyMCE() {
                var selector = `[jf-form="${name}"] [data-tinymce="true"]`;
                var elements = document.querySelectorAll(selector);

                elements.forEach(element => {
                    const fieldName = element.getAttribute('name');
                    if (fieldName) {
                        var tinyMCESelector =
                            `[jf-form="${name}"] [data-tinymce="true"][name="${fieldName}"]`;

                        var options = {
                            selector: tinyMCESelector,
                            menubar: false,
                            width: '100%',
                            height: '340',
                            toolbar: ["bold italic underline | bullist numlist | link"],
                            plugins: "advlist link lists charmap table"
                        };

                        if (KTThemeMode.getMode() === "dark") {
                            options["skin"] = "oxide-dark";
                            options["content_css"] = "dark";
                        } else {
                            options["skin"] = "oxide";
                            options["content_css"] = "default";
                        }

                        tinymce.init(options);
                    }
                });

                selector = `[jf-form="${name}"] [data-tinymce="simple"]`;
                elements = document.querySelectorAll(selector);

                elements.forEach(element => {
                    const fieldName = element.getAttribute('name');
                    if (fieldName) {
                        var tinyMCESelector =
                            `[jf-form="${name}"] [data-tinymce="simple"][name="${fieldName}"]`;

                        var options = {
                            selector: tinyMCESelector,
                            menubar: false,
                            width: '100%',
                            height: '340',
                            toolbar: [
                                "bold italic underline | bullist numlist | outdent indent | alignleft aligncenter alignright alignjustify | table link"
                            ],
                            plugins: "advlist link lists charmap table"
                        };

                        if (KTThemeMode.getMode() === "dark") {
                            options["skin"] = "oxide-dark";
                            options["content_css"] = "dark";
                        } else {
                            options["skin"] = "oxide";
                            options["content_css"] = "default";
                        }

                        tinymce.init(options);
                    }
                });

                selector = `[jf-form="${name}"] [data-tinymce="advance"]`;
                elements = document.querySelectorAll(selector);

                elements.forEach(element => {
                    const fieldName = element.getAttribute('name');
                    if (fieldName) {
                        var tinyMCESelector =
                            `[jf-form="${name}"] [data-tinymce="advance"][name="${fieldName}"]`;

                        var options = {
                            selector: tinyMCESelector,
                            menubar: false,
                            width: '100%',
                            height: '340',
                            toolbar: [
                                "undo redo | formatselect fontselect fontsizeselect | bold italic forecolor backcolor | bullist numlist | outdent indent | alignleft aligncenter alignright alignjustify | table link image media | code removeformat"
                            ],
                            plugins: "advlist link lists charmap table image media code"
                        };

                        if (url.filemanager != '') {
                            options['path_absolute'] = url.filemanager;
                            options['relative_urls'] = false;
                            options['file_picker_callback'] = function(callback, value, meta) {
                                var x = window.innerWidth || document.documentElement.clientWidth ||
                                    document.getElementsByTagName('body')[0].clientWidth;
                                var y = window.innerHeight || document.documentElement
                                    .clientHeight || document.getElementsByTagName('body')[0]
                                    .clientHeight;

                                var cmsURL = options.path_absolute + '&editor=' + meta.fieldname;

                                tinyMCE.activeEditor.windowManager.openUrl({
                                    url: cmsURL,
                                    title: 'File Manager',
                                    width: x * 0.8,
                                    height: y * 0.8,
                                    close_previous: 'no',
                                    onMessage: (api, message) => {
                                        callback(message.content);
                                    }
                                });
                            }
                        }

                        if (KTThemeMode.getMode() === "dark") {
                            options["skin"] = "oxide-dark";
                            options["content_css"] = "dark";
                        } else {
                            options["skin"] = "oxide";
                            options["content_css"] = "default";
                        }

                        tinymce.init(options);
                    }
                });

                selector = `[jf-form="${name}"] [data-tinymce]`;
                elements = document.querySelectorAll(selector);

                elements.forEach(element => {
                    const editor = tinymce.get(element.id);
                    if (editor) {
                        editor.setContent('');
                    }
                });
            }

            function syncTinyMCEContent() {
                const selector = `[jf-form="${name}"] [data-tinymce]`;
                const elements = document.querySelectorAll(selector);

                elements.forEach(element => {
                    const editor = tinymce.get(element.id);
                    if (editor) {
                        element.value = editor.getContent();
                    }
                });
            }

            refreshCustomStyle()
            refreshTinyMCE()
            syncTinyMCEContent()

            return {
                refreshStyle: function() {
                    refreshCustomStyle()
                }
            }
        }
    };
</script>
