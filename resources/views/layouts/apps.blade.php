<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->

<head>
    <title>{{ config('app.name', 'Laravel') }}</title>

    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta property="og:locale" content="" />
    <meta property="og:type" content="" />
    <meta property="og:title" content="" />
    <meta property="og:url" content="" />
    <meta property="og:site_name" content="" />
    <link rel="canonical" href="" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('layouts.inc.css')
    <style>
        .bg-theme {
            /* background-color: #e5f6fe !important; */
            background-color: #E6F4F7 !important;
        }

        [data-bs-theme=dark] .bg-theme {
            background-color: #00111a !important;
        }
    </style>
    @include('layouts.inc.js')
</head>
<!--end::Head-->
<!--begin::Body-->

<body id="kt_app_body" data-kt-app-header-fixed="true" data-kt-app-header-fixed-mobile="true"
    data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true"
    data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true"
    data-kt-app-aside-enabled="true" data-kt-app-aside-fixed="true" data-kt-app-aside-push-toolbar="true"
    data-kt-app-aside-push-footer="true" class="app-default bgi-size-cover bgi-no-repeat bg-theme">

    @yield('prejs')

    <!--begin::App-->
    <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
        <!--begin::Page-->
        <div class="app-page flex-column flex-column-fluid" id="kt_app_page">

            <!--begin::Header-->
            <div id="kt_app_header" class="app-header d-flex flex-column flex-stack bg-theme">
                @include('layouts.inc.header')
            </div>
            <!--end::Header-->

            <!--begin::Wrapper-->
            <div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">

                <!--begin::Sidebar-->
                <div id="kt_app_sidebar" class="app-sidebar flex-column bg-theme" data-kt-drawer="true"
                    data-kt-drawer-name="app-sidebar" data-kt-drawer-activate="{default: true, lg: false}"
                    data-kt-drawer-overlay="true" data-kt-drawer-width="250px" data-kt-drawer-direction="start"
                    data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
                    @include('layouts.inc.sidebar')
                </div>
                <!--end::Sidebar-->

                <!--begin::Main-->
                <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                    <!--begin::Content wrapper-->
                    <div class="d-flex flex-column flex-column-fluid">
                        <!--begin::Toolbar-->
                        <div id="kt_app_toolbar" class="app-toolbar pt-6 pt-lg-1 pb-2">
                            <!--begin::Toolbar container-->
                            <div id="kt_app_toolbar_container"
                                class="app-container container-fluid d-flex align-items-stretch">
                                @yield('toolbar')
                            </div>
                            <!--end::Toolbar container-->
                        </div>
                        <!--end::Toolbar-->
                        <!--begin::Content-->
                        <div id="kt_app_content" class="app-content flex-column-fluid py-2">
                            @yield('content')
                        </div>
                        <!--end::Content-->
                    </div>
                    <!--end::Content wrapper-->

                    <!--begin::Footer-->
                    <div id="kt_app_footer" class="app-footer">
                        @include('layouts.inc.footer')
                    </div>
                    <!--end::Footer-->
                </div>
                <!--end:::Main-->

                <!--begin::aside-->
                <div id="kt_app_aside" class="app-aside flex-column bg-theme" data-kt-drawer="true"
                    data-kt-drawer-name="app-aside" data-kt-drawer-activate="{default: true, lg: false}"
                    data-kt-drawer-overlay="true" data-kt-drawer-width="auto" data-kt-drawer-direction="end"
                    data-kt-drawer-toggle="#kt_app_aside_mobile_toggle">
                    @include('layouts.inc.aside')
                </div>
                <!--end::aside-->
            </div>
            <!--end::Wrapper-->
        </div>
        <!--end::Page-->
    </div>
    <!--end::App-->

    @stack('drawer')

    <!--begin::Scrolltop-->
    <div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
        <i class="ki-outline ki-arrow-up"></i>
    </div>
    <!--end::Scrolltop-->

    {{-- snap modal --}}
    <x-modal id="modalSnap" type="centered" :static="true" :action="true" size="xl"
        class="p-0 h-100 bg-body">
        <div id="modalSnap-body">
            <div id="loadingIndicator" class="text-center">
                <div class="progress" style="height: 2px;">
                    <div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated"
                        role="progressbar" style="width: 0%"></div>
                </div>
            </div>
        </div>
    </x-modal>
    {{-- snap modal --}}

    <!--begin::Javascript-->
    @stack('scripts')
    <script>
        function add_to_preview(media_id, croppedImage, previewContainerId, hiddenInputsContainerId, media_name = "",
            is_append =
            true) {
            const previewContainer = $('#' + previewContainerId);
            const hiddenInputsContainer = $('#' + hiddenInputsContainerId);

            const container = $('<div>')
                .addClass('image-container me-2 mb-2');

            const img = $('<img>')
                .attr('src', croppedImage)
                .addClass('rounded rounded-2 h-50px')
                // .addClass('rounded rounded-2 h-100px w-100px')
                .attr('alt', 'Cropped Image');

            const actionButtons = $('<div>')
                .addClass('action-buttons');

            // const deleteButton = $('<a>')
            //     .attr('href', 'javascript:;')
            //     .addClass(
            //         'btn btn-icon btn-sm mh-25px mw-25px btn-light-danger act-delete')
            //     .attr('title', 'Hapus data');

            // const icon = $('<i>')
            //     .addClass('ki-outline ki-trash fs-3');

            // deleteButton.append(icon);
            // deleteButton.on('click', function() {
            //     container.remove();
            //     deleteId = hiddenInput.val();
            //     var hiddenInputDelete = $('<input>')
            //         .attr('type', 'hidden')
            //         .attr('name', 'delete_media_id_' + media_name + '[]')
            //         .val(deleteId); // Base64 data
            //     hiddenInputsContainer.append(hiddenInputDelete);
            //     hiddenInput.remove();
            // });

            // actionButtons.append(deleteButton);

            const detailButton = $('<a>')
                .attr('href', 'javascript:;')
                .addClass(
                    'btn btn-icon btn-sm mh-25px mw-25px btn-light-primary act-show')
                .attr('title', 'Lihat data');

            const icon = $('<i>')
                .addClass('ki-outline ki-magnifier fs-3');

            detailButton.append(icon);
            detailButton.on('click', function() {
                container.remove();
                detail_id = hiddenInput.val();
                // var hiddenInputDelete = $('<input>')
                //     .attr('type', 'hidden')
                //     .attr('name', 'delete_media_id_' + media_name + '[]')
                //     .val(detail_id); // Base64 data
                // hiddenInputsContainer.append(hiddenInputDelete);
                // hiddenInput.remove();
            });

            actionButtons.append(detailButton);
            container.append(img).append(actionButtons);

            var hiddenInput = $('<input>')
                .attr('type', 'hidden')
                .attr('name', 'media_id_' + media_name)
                .val(media_id); // Base64 data

            if (is_append) {
                previewContainer.append(container);
                hiddenInputsContainer.append(hiddenInput);
            } else {
                previewContainer.html(container);
                hiddenInputsContainer.html(hiddenInput);
            }

        }
    </script>
    <!--end::Javascript-->
</body>
<!--end::Body-->

</html>