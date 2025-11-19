<div id="kt_app_sidebar_wrapper" class="app-sidebar-wrapper hover-scroll-y my-5 my-lg-2" data-kt-scroll="true"
    data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-height="auto"
    data-kt-scroll-dependencies="#kt_app_header" data-kt-scroll-wrappers="#kt_app_sidebar_wrapper"
    data-kt-scroll-offset="5px">
    <div id="#kt_app_sidebar_menu" data-kt-menu="true" data-kt-menu-expand="false"
        class="app-sidebar-menu-primary menu menu-column menu-rounded menu-sub-indention menu-state-bullet-primary px-6 mb-5">

        <!-- admin dashboard -->
        <x-theme.menu link="{{ route('app.dashboard.index') }}" text="Dashboard" icon="ki-outline ki-graph-up" :active="$pageData->activeMenu == 'dashboard'" />
        <!-- mahasiswa dashboard -->
        <x-theme.menu link="{{ route('app.user.show', ['param1' => 'token']) }}" text="Dashboard" icon="ki-outline ki-graph-up" :active="$pageData->activeMenu == 'user-token'" /> 
        
        <x-theme.menu link="{{ route('app.biblio.index') }}" text="Biblio" icon="ki-outline ki-book" :active="$pageData->activeMenu == 'biblio'" />
        <x-theme.menu link="{{ route('app.loan.index') }}" text="Loans" icon="ki-outline ki-folder-open" :active="$pageData->activeMenu == 'loan'" />
        
        <x-theme.menu text="User Management" icon="ki-outline ki-user" :active="in_array($pageData->activeMenu, ['user', 'user-token'])">
            <x-theme.submenu link="{{ route('app.user.index') }}" text="Kelola User" :active="$pageData->activeMenu == 'user'" />
           
        </x-theme.menu>
        
        <x-theme.menu link="{{ route('app.roles.index') }}" text="Roles & Permissions" icon="ki-outline ki-lock" :active="$pageData->activeMenu == 'roles'" />

        <div class="separator separator-dashed border-gray-10 my-2"></div>

    

        <div class="separator separator-dashed border-gray-10 my-2"></div>

   

     </div>
</div>