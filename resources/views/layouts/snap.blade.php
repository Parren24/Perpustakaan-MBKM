<meta name="csrf-token" content="{{ csrf_token() }}">
@include('layouts.inc.css')
@include('layouts.inc.js')
@yield('prejs')

<body class="bg-body">
    <div id="wrapper-snap">
        <div class="app-container container-fluid py-4" data-cue="slideInLeft" data-duration="1000" data-delay="0">
            @yield('toolbar')
        </div>

        @yield('content')
    </div>
</body>
@stack('scripts')
