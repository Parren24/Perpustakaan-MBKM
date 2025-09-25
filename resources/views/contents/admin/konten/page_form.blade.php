@extends(request()->query('snap') == true ? 'layouts.snap' : 'layouts.apps')
@section('toolbar')
    <x-theme.toolbar :breadCrump="$pageData->breadCrump" :title="$pageData->title">
        <x-slot:tools>
            <x-theme.back link="{{ route('app.konten.konten-page.show', ['param1' => $pageData->dataKonten['level']]) }}" />
        </x-slot:tools>
    </x-theme.toolbar>
@endsection


@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css" />
    <style>
        .tagify__input {
            border: none !important;
            /* jika ingin hilangkan total */
            box-shadow: none !important;
            /* hilangkan efek putih glow */
            outline: none !important;
            background-color: transparent !important;
            /* opsional jika warnanya beda */
        }

        .tagify {
            border: 1px solid #92929F !important;
            /* warna sesuai tema dark */
            border-radius: 4px;
            /* opsional */
        }
    </style>
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-fluid" data-cue="slideInLeft" data-duration="1000"
        data-delay="0">
        <form id="formData" class="needs-validation" jf-form="post">
            <div class="row">
                <div class="col-xl-8 col-12">
                    <div class="mb-4">
                        <x-form.input name="title_page" label="Judul" value="" required />
                    </div>
                    <div class="mb-4">
                        <x-form.input name="subtitle_page" label="SubJudul" value="" required />
                    </div>
                    <div class="mb-4">
                        <x-form.textarea name="konten_page" label="Konten" rows="6" id="konten_page" required />
                    </div>
                    <div class="separator separator-dashed border-gray-10 my-2"></div>
                    <div class="mb-4">
                        <h3>Komponen Halaman</h3>
                        <x-card.compact>
                            <input type="file" class="d-none media_cropper" id="media_main" name="media_main[]"
                                data-preview="media_main_preview" data-hidden_container="hidden_inputs_hero" data-append="0"
                                data-name="hero[]" />
                            <x-btn type="secondary" class="btn-upload mb-1" data-id="media_main">
                                <i class="bi bi-upload"></i> Upload media
                            </x-btn>
                            <x-btn type="light-primary" class="btn-search mb-1" data-id="media_main">
                                <i class="bi bi-search"></i> Library media
                            </x-btn>
                            <div class="fs-9">Media dapat berupa video atau gambar</div>
                        </x-card.compact>
                    </div>
                </div>
                <div class="col-xl-4 col-12">
                    <div class="mb-4">
                        <x-form.input name="meta_keyword_page" label="Meta Keywords" value="" required />
                    </div>
                    <div class="mb-4">
                        <x-form.textarea name="meta_desc_page" label="Meta Descriptions" rows="6" id="konten_page"
                            required />
                    </div>
                    <div class="separator separator-dashed border-gray-10 my-2"></div>
                    <h3>Referensi Media</h3>
                    <x-card.compact>
                        <input type="file" class="d-none media_cropper" id="media_main" name="media_main[]"
                            data-preview="media_main_preview" data-hidden_container="hidden_inputs_hero" data-append="0"
                            data-name="hero[]" />
                        <x-btn type="secondary" class="btn-upload mb-1" data-id="media_main">
                            <i class="bi bi-upload"></i> Upload media
                        </x-btn>
                        <x-btn type="light-primary" class="btn-search mb-1" data-id="media_main">
                            <i class="bi bi-search"></i> Library media
                        </x-btn>
                        <div class="fs-9">Media dapat berupa video atau gambar</div>
                    </x-card.compact>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
    <x-script.crud2></x-script.crud2>
    <script>
        // var inputFontFamily = $('#inputField').css('font-family');
        var inputFontSize = $('#inputField').css('font-size');

        $(document).ready(function() {
            var input = document.querySelector('input[name=meta_keyword_page]');

            const tagify = new Tagify(input, {
                delimiters: ",",
                maxTags: 20,
                dropdown: {
                    enabled: 0
                }
            });

            // HINDARI AUTO-FOCUS
            tagify.DOM.input.blur(); // ini akan menghilangkan fokus dari input

            // if (KTThemeMode.getMode() === "dark") {
            //     tagify.DOM.scope.style.border = "1px solid #444";
            //     tagify.DOM.scope.style.borderRadius = "0.375rem";
            //     tagify.DOM.scope.style.backgroundColor = "#1e1e2d";
            //     tagify.DOM.scope.style.color = "#f1f1f1";
            // } else {
            //     tagify.DOM.scope.style.border = "1px solid #ced4da"; // sama seperti textarea bootstrap
            //     tagify.DOM.scope.style.borderRadius = "0.375rem"; // agar sudutnya sama
            //     tagify.DOM.scope.style.backgroundColor = "#fff";
            //     tagify.DOM.scope.style.color = "#212529";
            // }

            var options = {
                selector: '#konten_page',
                menubar: false,
                width: '100%',
                height: '500',
                toolbar: [
                    "undo redo | formatselect fontselect fontsizeselect | bold italic forecolor backcolor | bullist numlist | outdent indent | alignleft aligncenter alignright alignjustify | table link image media | code removeformat"
                ],
                plugins: "advlist link lists charmap table image media code",
                content_style: `
                    body {
                        font-size: ${inputFontSize};
                    }
                `,
            };


            if (KTThemeMode.getMode() === "dark") {
                options["skin"] = "oxide-dark";
                options["content_css"] = "dark";
            }

            tinymce.init(options);
        })
    </script>
@endpush
