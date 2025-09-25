    @section('prejs')
        <!--begin::Theme mode setup on page load-->
        <script>
            var defaultThemeMode = "light";
            var themeMode;
            if (document.documentElement) {
                if (document.documentElement.hasAttribute("data-bs-theme-mode")) {
                    themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
                } else {
                    if (localStorage.getItem("data-bs-theme") !== null) {
                        themeMode = localStorage.getItem("data-bs-theme");
                    } else {
                        themeMode = defaultThemeMode;
                    }
                }
                if (themeMode === "system") {
                    themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
                }
                document.documentElement.setAttribute("data-bs-theme", themeMode);
            }
        </script>
        <!--end::Theme mode setup on page load-->
    @endsection
    @prepend('scripts')
        <script>
            var hostUrl = "theme/";
            const base_url = `{{ url('') }}`
        </script>
        <!--begin::Global Javascript Bundle(mandatory for all pages)-->
        <script src="{{ asset('theme/plugins/global/plugins.bundle.js') }}"></script>
        <script src="{{ asset('theme/js/admin/scripts.bundle.js') }}"></script>
        <script src="{{ asset('theme/js/common.js') }}"></script>
        <script src="{{ asset('theme/plugins/custom/xlsx/xlsx.min.js') }}"></script>
        <script src="{{ asset('theme/plugins/custom/tinymce/tinymce.bundle.js') }}"></script>
        <script src="{{ asset('theme/plugins/custom/dual-listbox/dual-listbox.js') }}"></script>

        <!--end::Global Javascript Bundle-->
        <!--begin::Vendors Javascript(used for this page only)-->
        <script src="{{ asset('theme/plugins/custom/datatables/datatables.bundle.js') }}"></script>
        <!--end::Vendors Javascript-->
        <script src="{{ asset('theme/plugins/custom/scroll-cue/scrollCue.min.js') }}"></script>
        <script src="{{ asset('theme/js/datatable-laracomp.js') }}"></script>
        <script src="{{ asset('theme/js/custom.js') }}"></script>
        <script>
            document.documentElement.style.setProperty('--bs-warning', '#fd7e14');
            document.documentElement.style.setProperty('--bs-warning-rgb', '253, 126, 20');
            document.documentElement.style.setProperty('--bs-text-warning', '#fd7e14');
            scrollCue.init();
        </script>
        <script>
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $(document).on('click', '[data-snap="true"]', function() {
                snapUrl = $(this).data('snap-url') + '?snap=true'
                snapTitle = $(this).data('snap-title')

                $('#modalSnap-body').html(``)
                $('#modalSnap-body').html('Loading Page')
                $('#modalSnap-body').html(`
                    <div id="loadingIndicator" class="text-center">
                        <div class="progress" style="height: 2px;">
                            <div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%"></div>
                        </div>
                    </div>
                    <iframe id="frameSnap" src="${snapUrl}" class="w-100 min-h-450px min-h-md-650px" frameborder="0"></iframe>
                `)
                $('#modalSnap').modal('show')
                $('#modalSnap-title').html(snapTitle)

                var progressBar = $('#progressBar');
                var width = 0;
                var interval = setInterval(function() {
                    if (width >= 90) {
                        clearInterval(interval);
                    } else {
                        width += 10;
                        progressBar.css('width', width + '%');
                    }
                }, 100);

                $('#frameSnap').on('load', function() {
                    clearInterval(interval);
                    progressBar.css('width', '100%');
                    $('#loadingIndicator').fadeOut('slow', function() {
                        $(this).remove();
                    });
                    $(this).fadeIn('slow');
                });
            })
        </script>
    @endprepend
