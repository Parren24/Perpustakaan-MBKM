<!--begin::Header main-->
<div class="d-flex flex-stack flex-grow-1">
    <div class="app-header-logo d-flex align-items-center ps-lg-9" id="kt_app_header_logo">
        <!--begin::Sidebar toggle-->
        <div id="kt_app_sidebar_toggle" class="app-sidebar-toggle btn btn-sm btn-icon bg-body btn-color-gray-500 btn-active-color-primary w-30px h-30px ms-n2 me-4 d-none d-lg-flex" data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body" data-kt-toggle-name="app-sidebar-minimize">
            <i class="ki-duotone ki-abstract-14 fs-3 mt-1">
                <span class="path1"></span>
                <span class="path2"></span>
            </i>
        </div>
        <!--end::Sidebar toggle-->
        <!--begin::Sidebar mobile toggle-->
        <div class="btn btn-icon btn-active-color-primary w-35px h-35px ms-3 me-2 d-flex d-lg-none" id="kt_app_sidebar_mobile_toggle">
            <i class="ki-duotone ki-abstract-14 fs-2">
                <span class="path1"></span>
                <span class="path2"></span>
            </i>
        </div>
        <!--end::Sidebar mobile toggle-->
        <!--begin::Logo-->
        <a href="{{ url('/') }}" class="app-sidebar-logo">
            <img alt="Logo" src="{{ asset('theme') }}/media/logos/default.png" class="h-25px h-md-30px theme-light-show" />
            <img alt="Logo" src="{{ asset('theme') }}/media/logos/default-dark.png" class="h-25px h-md-30px theme-dark-show" />
        </a>
        <!--end::Logo-->
    </div>
    <!--begin::Navbar-->
    <div class="app-navbar flex-grow-1 justify-content-end" id="kt_app_header_navbar">
        <div class="app-navbar-item d-flex align-items-stretch flex-lg-grow-1">
            @include('layouts.inc.header_app')
        </div>



        @include('layouts.inc.header_notif')

        <!--begin::User menu-->
        <div class="app-navbar-item" id="kt_header_user_menu_toggle">
            <!--begin::Menu wrapper-->
            <div class="cursor-pointer symbol symbol-circle symbol-25px symbol-lg-35px p-1 bg-white" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
                <img src="{{ asset('theme') }}/media/svg/avatars/001-boy.svg" alt="user" />
            </div>
            <!--begin::User account menu-->
            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px" data-kt-menu="true">
                <!--begin::Menu item-->
                <div class="menu-item px-3">
                    <div class="menu-content d-flex align-items-center px-3">
                        <!--begin::Avatar-->
                        <div class="symbol symbol-50px me-5">
                            <img alt="Logo" src="{{ asset('theme') }}/media/svg/avatars/001-boy.svg" />
                        </div>
                        <!--end::Avatar-->
                        <!--begin::Username-->
                        <div class="d-flex flex-column">
                            <div class="fw-bold d-flex align-items-center fs-5">
                                {{ Auth::user()->name }}
                            </div>
                            <span class="fw-semibold text-muted fs-7">{{ Auth::user()->email }}</span>
                        </div>
                        <!--end::Username-->
                    </div>
                </div>
                <!--end::Menu item-->
                <!--begin::Menu separator-->
                <div class="separator my-2"></div>
                <!--end::Menu separator-->
                <!--begin::Menu item-->
                <div class="menu-item px-5" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="left-start" data-kt-menu-offset="-15px, 0">
                    <a href="#" class="menu-link px-5">
                        <span class="menu-title position-relative">Mode
                            <span class="ms-5 position-absolute translate-middle-y top-50 end-0">
                                <i class="ki-outline ki-night-day theme-light-show fs-2"></i>
                                <i class="ki-outline ki-moon theme-dark-show fs-2"></i>
                            </span></span>
                    </a>
                    <!--begin::Menu-->
                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-title-gray-700 menu-icon-gray-500 menu-active-bg menu-state-color fw-semibold py-4 fs-base w-150px" data-kt-menu="true" data-kt-element="theme-mode-menu">
                        <!--begin::Menu item-->
                        <div class="menu-item px-3 my-0">
                            <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="light">
                                <span class="menu-icon" data-kt-element="icon">
                                    <i class="ki-outline ki-night-day fs-2"></i>
                                </span>
                                <span class="menu-title">Light</span>
                            </a>
                        </div>
                        <!--end::Menu item-->
                        <!--begin::Menu item-->
                        <div class="menu-item px-3 my-0">
                            <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="dark">
                                <span class="menu-icon" data-kt-element="icon">
                                    <i class="ki-outline ki-moon fs-2"></i>
                                </span>
                                <span class="menu-title">Dark</span>
                            </a>
                        </div>
                        <!--end::Menu item-->
                        <!--begin::Menu item-->
                        <div class="menu-item px-3 my-0">
                            <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="system">
                                <span class="menu-icon" data-kt-element="icon">
                                    <i class="ki-outline ki-screen fs-2"></i>
                                </span>
                                <span class="menu-title">System</span>
                            </a>
                        </div>
                        <!--end::Menu item-->
                    </div>
                    <!--end::Menu-->
                </div>
                <!--end::Menu item-->
                <!--begin::Menu item-->
                <div class="menu-item px-5">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" 
                            class="menu-link px-5 btn btn-light-danger text-danger text-hover-white w-100 text-start">
                            Sign Out
                        </button>
                    </form>
                </div>
                <!--end::Menu item-->
            </div>
            <!--end::User account menu-->
            <!--end::Menu wrapper-->
        </div>
        <!--end::User menu-->
        <!--begin::Action-->
        <div class="d-none d-md-inline app-navbar-item ms-2 mx-lg-2">
            <!--begin::Link-->
            <a href="{{ asset('demo39/dist/authentication/layouts/corporate/sign-in.html') }}" class="btn btn-icon btn-custom btn-color-gray-600 btn-active-color-primary w-35px h-35px w-md-40px h-md-40px">
                <i class="ki-outline ki-exit-right fs-1"></i>
            </a>
            <!--end::Link-->
        </div>
        <!--end::Action-->

        <!--begin::Header menu toggle-->
        <div class="app-navbar-item ms-3 ms-lg-6 me-3 d-flex d-lg-none">
            <div class="btn btn-icon btn-custom btn-color-gray-600 btn-active-color-primary w-35px h-35px w-md-40px h-md-40px" id="kt_app_aside_mobile_toggle">
                <i class="ki-duotone ki-tablet-book fs-3x"><i class="path1"></i><i class="path2"></i></i>
            </div>
        </div>
        <!--end::Header menu toggle-->
    </div>
    <!--end::Navbar-->
</div>
<!--end::Header main-->
<!--begin::Separator-->
<div class="app-header-separator border-0"></div>
<!--end::Separator-->
