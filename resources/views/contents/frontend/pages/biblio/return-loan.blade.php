@extends('layouts.frontend.main')

<x-frontend.seo :pageConfig="$pageConfig" />
@section('content')

        @include('contents.frontend.partials.main.biblio.pengembalian', ['content' => $content])
    
@endsection