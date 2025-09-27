<div id="kt_app_sidebar_wrapper" class="app-sidebar-wrapper hover-scroll-y my-5 my-lg-2" data-kt-scroll="true"
    data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-height="auto"
    data-kt-scroll-dependencies="#kt_app_header" data-kt-scroll-wrappers="#kt_app_sidebar_wrapper"
    data-kt-scroll-offset="5px">
    <div id="#kt_app_sidebar_menu" data-kt-menu="true" data-kt-menu-expand="false"
        class="app-sidebar-menu-primary menu menu-column menu-rounded menu-sub-indention menu-state-bullet-primary px-6 mb-5">

        <x-theme.menu link="{{ url('dashboard') }}" text="Dashboard" icon="ki-outline ki-graph-up" :active="$pageData->activeMenu == 'dashboard'" />
       
        <x-theme.menu link="{{ route('app.biblio.index') }}" text="Biblio" icon="ki-outline ki-book" :active="$pageData->activeMenu == 'biblio'" />
        <div class="separator separator-dashed border-gray-10 my-2"></div>

        {{-- <x-theme.menu text="Master Data" icon="ki-outline ki-abstract-26" :active="$pageData->activeRoot == 'master'">
            <x-theme.submenu link="{{ route('app.master.show', ['param1' => 'infografis']) }}" text="Infografis"
                :active="$pageData->activeMenu == 'infografis'" />
            <x-theme.submenu link="{{ route('app.master.show', ['param1' => 'partner']) }}" text="Partner"
                :active="$pageData->activeMenu == 'partner'" />
            <x-theme.submenu link="{{ route('app.master.show', ['param1' => 'social-media']) }}" text="Social Media"
                :active="$pageData->activeMenu == 'social-media'" />
            <x-theme.submenu link="{{ route('app.master.show', ['param1' => 'kontak']) }}" text="Kontak"
                :active="$pageData->activeMenu == 'kontak'" />
            <x-theme.submenu link="{{ route('app.master.show', ['param1' => 'pegawai']) }}" text="Pegawai"
                :active="$pageData->activeMenu == 'pegawai'" />
            <x-theme.submenu link="{{ route('app.master.show', ['param1' => 'jurusan']) }}" text="Jurusan"
                :active="$pageData->activeMenu == 'jurusan'" />
            <x-theme.submenu link="{{ route('app.master.show', ['param1' => 'prodi']) }}" text="Program Studi"
                :active="$pageData->activeMenu == 'prodi'" />
        </x-theme.menu>

        <div class="separator separator-dashed border-gray-10 my-2"></div>

        <x-theme.menu text="Main & Microsite" icon="ki-outline ki-abstract-35" :active="$pageData->activeRoot == 'sites'">
            <x-theme.submenu link="{{ url('admin/konten-main') }}" text="Main Landing" :active="$pageData->activeMenu == 'section'" />
            <x-theme.submenu link="{{ url('admin/konten-main') }}" text="Microsite Jurusan" :active="$pageData->activeMenu == 'section'" />
            <x-theme.submenu link="{{ url('admin/konten-main') }}" text="Microsite Prodi" :active="$pageData->activeMenu == 'section'" />
        </x-theme.menu>

        <x-theme.menu text="Halaman Statis" icon="ki-outline ki-abstract-42" :active="$pageData->activeRoot == 'konten-statis'">
            <x-theme.submenu link="#" text="Sejarah" :active="$pageData->activeMenu == 'konten-main'" />
            <x-theme.submenu link="#" text="Visi Misi" :active="$pageData->activeMenu == 'konten-main'" />
            <x-theme.submenu link="#" text="Diversitas" :active="$pageData->activeMenu == 'konten-main'" />
            <x-theme.submenu link="#" text="Sambutan YPCR" :active="$pageData->activeMenu == 'konten-main'" />
            <x-theme.submenu link="#" text="Sambutan Direktur" :active="$pageData->activeMenu == 'konten-main'" />
            <x-theme.submenu link="#" text="Org & Profil Pimpinan" :active="$pageData->activeMenu == 'konten-main'" />
            <x-theme.submenu link="#" text="Panduan Identitas" :active="$pageData->activeMenu == 'konten-main'" />
            <x-theme.submenu link="#" text="Lokasi" :active="$pageData->activeMenu == 'konten-main'" />
            <x-theme.submenu link="#" text="Akreditasi" :active="$pageData->activeMenu == 'konten-main'" />
            <x-theme.submenu link="#" text="Prestasi" :active="$pageData->activeMenu == 'konten-main'" />
            <x-theme.submenu link="#" text="Laporan Tahunan" :active="$pageData->activeMenu == 'konten-main'" />
        </x-theme.menu> --}}

        <x-theme.menu text="Konten" icon="ki-outline ki-pin" :active="$pageData->activeRoot == 'konten'">
            <x-theme.submenu link="{{ route('app.agenda.index') }}" text="Agenda" :active="$pageData->activeMenu == 'agenda' || $pageData->activeMenu == 'agenda-kategori'" />
            <x-theme.submenu link="{{ route('app.post.index') }}" text="Post" :active="preg_match('/^post-kat-/', $pageData->activeMenu) ||
                $pageData->activeMenu == 'post-kategori' ||
                $pageData->activeMenu == 'post-label'" />
            <x-theme.submenu link="{{ route('app.testi.index') }}" text="Testimoni" :active="$pageData->activeMenu == 'testi' || $pageData->activeMenu == 'testi-kategori'" />
        </x-theme.menu>

        {{-- <x-theme.menu link="{{ route('app.media.index') }}" text="Media" icon="ki-outline ki-external-drive"
            :active="$pageData->activeMenu == 'media'" />

        <div class="separator separator-dashed border-gray-10 my-2"></div>

        <x-theme.menu link="{{ url('dashboard') }}" text="Statistik" icon="ki-outline ki-graph-3" :active="$pageData->activeMenu == 'config'" />

        <x-theme.menu text="Settings" icon="ki-outline ki-setting-3" :active="$pageData->activeRoot == 'config'">
            <x-theme.submenu link="{{ url('#') }}" text="Pengguna Sistem" :active="$pageData->activeMenu == 'error_log'" />
            <x-theme.submenu link="{{ url('roles') }}" text="Roles and Permissions" :active="$pageData->activeMenu == 'roles'" />
            <x-theme.submenu link="{{ url('config') }}" text="System Config" :active="$pageData->activeMenu == 'config'" />
        </x-theme.menu>
        <x-theme.menu text="Log" icon="ki-outline ki-mouse-square" :active="$pageData->activeRoot == 'log'">
            <x-theme.submenu link="{{ url('#') }}" text="System Log" :active="$pageData->activeMenu == 'log'" />
            <x-theme.submenu link="{{ url('#') }}" text="Error Log" :active="$pageData->activeMenu == 'error_log'" />
        </x-theme.menu> --}}
    </div>
</div>
