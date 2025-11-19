@props(['menu' => $menu ?? [], 'jurusanList' => $jurusanList ?? []])
@php
    // dd(data_get($siteIdentity, 'identity.logo_path'));
@endphp

<header class="main-header">
    <div class="header-sticky">
        <nav class="navbar navbar-expand-xxl" {{ Route::currentRouteName() == 'frontend.home' ? 'data-is-home' : '' }}>
            <div class="container-fluid">
                <a class="navbar-brand mt-md-2" href="{{ route('frontend.home') }}">
                    <img src="{{ data_get($siteIdentity, 'identity.logo_path') }}" alt="Logo" class="navbar-logo">
                </a>

                <div class="collapse navbar-collapse main-menu">
                    <div class="nav-menu-wrapper">
                        <ul class="navbar-nav mr-auto" id="menu">
                            <li class="nav-item toggle-menu">
                                <div class="navbar-brand">
                                    <img src="{{ data_get($siteIdentity, 'identity.logo_path') }}" alt="Logo"
                                         class="navbar-logo">
                                </div>

                                <div class="navbar-toggle navbar-toggle-responsive"></div>
                            </li>
                            @foreach ($menu as $menu_item)
                                @php
                                    $class = 'nav-item';
                                    $route = $menu_item['route'] ?? 'javascript:;';

                                    if ($menu_item['children'] ?? false) {
                                        $class .= ' submenu';
                                    }

                                    if ($menu_item['hide_xxl'] ?? false) {
                                        $class .= ' hide-xxl';
                                    }
                                @endphp
                                <li class="{{ $class }}">
                                    <a class="nav-link" href="{{ $route }}">
                                        {{ $menu_item['name'] }}
                                    </a>
                                    @if (isset($menu_item['children']))
                                        <ul>
                                            @foreach ($menu_item['children'] as $child)
                                                @php
                                                    $child_class = 'nav-item';
                                                    $child_route = $child['route'] ?? 'javascript:;';

                                                    if (isset($child['children'])) {
                                                        $child_class .= ' submenu';
                                                    }
                                                @endphp
                                                <li class="{{ $child_class }}">
                                                    <a class="nav-link" href="{{ $child_route }}">
                                                        {{ $child['name'] }}
                                                    </a>
                                                    @if (isset($child['children']))
                                                        <ul>
                                                            @foreach ($child['children'] as $sub_child)
                                                                @php
                                                                    $sub_child_route = $sub_child['route'] ?? 'javascript:;';
                                                                @endphp

                                                                <li class="nav-item">
                                                                    <a class="nav-link" href="{{ $sub_child_route }}">
                                                                        {{ $sub_child['name'] }}
                                                                    </a>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="navbar-toggle"></div>
            </div>
        </nav>
        <div class="responsive-menu slicknav_state"></div>
    </div>
</header>
