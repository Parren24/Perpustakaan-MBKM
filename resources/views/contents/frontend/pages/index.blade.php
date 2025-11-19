@extends('layouts.frontend.main')

<x-frontend.seo :pageConfig="$pageConfig" />

@section('content')
    @include('contents.frontend.partials.main.landing.hero', ['heroData' => $heroData])
    @include('contents.frontend.partials.main.biblio.peminjaman', ['content' => $content])

@endsection
