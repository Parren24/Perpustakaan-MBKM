<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}"> {{-- Added for AJAX forms --}}
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    @php
        $defaultMetadata = [
            'title' => 'Politeknik Caltex Riau',
            'description' => 'Politeknik Caltex Riau (PCR) adalah perguruan tinggi di Riau yang didirikan atas kerja sama Pemerintah Provinsi Riau dengan PT Chevron Pacific Indonesia',
            'keywords' => 'PCR,Politeknik,Caltex,Riau,Mahasiswa,Politeknik Riau,Penerimaan Mahasiswa,Politeknik Caltex',
            'author' => 'Politeknik Caltex Riau',
            'robots' => 'index, follow',
        ];
    @endphp

    <link rel="canonical" href="@yield('meta.canonical', url()->current())">
    <link rel="shortlink" href="@yield('meta.shortlink', url()->current())">

    <meta name="description" content="@yield('meta.description', $defaultMetadata['description'])">
    <meta name="keywords" content="@yield('meta.keywords', $defaultMetadata['keywords'])">
    <meta name="author" content="@yield('meta.author', $defaultMetadata['author'])">
    <meta name="robots" content="@yield('meta.robots', $defaultMetadata['robots'])">

    <meta name="og:title" content="@yield('meta.og.title', $defaultMetadata['title'])">
    <meta name="og:description" content="@yield('meta.og.description', $defaultMetadata['description'])">
    <meta name="og:image" content="@yield('meta.og.image', asset('theme/frontend/images/caltex_logo.png'))">
    <meta name="og:url" content="@yield('meta.og.url', url()->current())">
    <meta name="og:type" content="@yield('meta.og.type', 'website')">
    <meta name="og:site_name" content="@yield('meta.og.site_name', $defaultMetadata['title'])">
    <meta name="og:locale" content="@yield('meta.og.locale', app()->getLocale())">

    {{-- <meta name="twitter:card" content="@yield('meta.twitter.card', 'summary_large_image')">
    <meta name="twitter:title" content="@yield('meta.twitter.title', $defaultMetadata['title'])">
    <meta name="twitter:description" content="@yield('meta.twitter.description', $defaultMetadata['description'])">
    <meta name="twitter:image" content="@yield('meta.twitter.image', asset('theme/frontend/images/caltex_logo.png'))">
    <meta name="twitter:site" content="@yield('meta.twitter.site', '@PoliteknikCaltex')">
    <meta name="twitter:creator" content="@yield('meta.twitter.creator', '@PoliteknikCaltex')">
    <meta name="twitter:url" content="@yield('meta.twitter.url', url()->current())">
    <meta name="twitter:domain" content="@yield('meta.twitter.domain', request()->getHost())"> --}}


    <title>
        @hasSection('title')
            {{ config('app.name') }} | @yield('title')
        @else
            {{ config('app.name') }} | Politeknik Caltex Riau
        @endif
    </title>

    @stack('head')
</head>

<body>
    @yield('base-content')

    @stack('script')
</body>

</html>
