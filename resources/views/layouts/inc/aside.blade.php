<!--begin::Wrapper-->
<div id="kt_app_aside_wrapper" class="d-flex flex-column align-items-center hover-scroll-y mt-lg-n3 py-5 py-lg-0 gap-4" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_app_header" data-kt-scroll-wrappers="#kt_app_aside_wrapper" data-kt-scroll-offset="5px">
    <button class="btn btn-icon btn-color-primary bg-hover-body h-45px w-45px flex-shrink-0" data-bs-toggle="tooltip" title="Bantuan" data-bs-custom-class="tooltip-inverse" id="aside_help_toggle">
        <i class="bi bi-question-circle fs-2x"></i>
    </button>
</div>
<!--end::Wrapper-->

@push('drawer')
    <div id="aside_help" class="bg-body" data-kt-drawer="true" data-kt-drawer-activate="true" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'300px', 'lg': '380px'}" data-kt-drawer-direction="end" data-kt-drawer-toggle="#aside_help_toggle" data-kt-drawer-close=".aside_help_close">
        <div class="card border-0 shadow-none rounded-0 w-100">
            <div class="card-header bg-light-primary rounded-0 border-0 py-4" id="aside_help_header">
                <h3 class="card-title fs-3 fw-bold text-primary flex-column m-0">
                    Bantuan Sistem
                    <small class="text-primary opacity-50 fs-7 fw-semibold pt-1">
                        penjelasan tujuan dan penggunaan fitur
                    </small>
                </h3>
                <div class="card-toolbar">
                    <button type="button" class="btn btn-sm btn-icon btn-color-primary p-0 w-20px h-20px rounded-1 aside_help_close" id="aside_help_close">
                        <i class="ki-duotone ki-cross-square fs-2"><span class="path1"></span><span class="path2"></span></i>
                    </button>
                </div>
            </div>
            <div class="card-body position-relative p-0" id="aside_help_body">
                <div class="position-relative scroll-y me-n5 pe-5" data-kt-scroll="true" data-kt-scroll-height="auto" data-kt-scroll-wrappers="#aside_help_body" data-kt-scroll-dependencies="#aside_help_header, #aside_help_footer" data-kt-scroll-offset="5px">

                    @yield('aside_help')

                </div>
            </div>

            <div class="card-footer border-0 d-flex gap-3 pb-9 pt-0" id="aside_help_footer">
                <button type="button" class="btn btn-light flex-grow-1 fw-semibold aside_help_close">
                    <i class="bi bi-check2-circle fs-2"></i> Oke mengerti
                </button>
            </div>
        </div>
    </div>
@endpush
