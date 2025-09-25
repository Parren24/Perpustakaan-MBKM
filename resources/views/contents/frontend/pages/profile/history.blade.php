@extends('layouts.frontend.main')

@breadcrumbs([data_get($pageConfig, 'seo.title'), url()->current()])

<x-frontend.seo :pageConfig="$pageConfig" />

@section('content')
    <x-frontend.page-header :breadcrumbs="$breadcrumbs" :image="data_get($pageConfig, 'background_image')">
        {{ data_get($content, 'header') }}
    </x-frontend.page-header>

    <div class="page-history">
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <x-frontend.table-of-contents :items="$tableOfContents" />
                </div>

                <div class="col-lg-8">
                    <div class="history-content">
                        <div class="history-entry">
                            @foreach (data_get($content, 'timeline', []) as $entry)
                                <x-frontend.history-entry :entry="$entry" />
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
