@extends('layouts.frontend.main')

@breadcrumbs([data_get($pageConfig, 'seo.title'), url()->current()])

<x-frontend.seo :pageConfig="$pageConfig" />

@section('content')
    <x-frontend.page-header :breadcrumbs="$breadcrumbs" :image="data_get($pageConfig, 'background_image')">
        {{ data_get($content, 'header') }}
    </x-frontend.page-header>

    <div class="virtual-tour-page content-page">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-center">
                        <div class="section-title">
                            <h2 class="wow fadeInUp" data-wow-delay="0.25s">
                                {!! data_get($content, 'title') !!}
                            </h2>
                        </div>
                        <div class="virtual-tour-content">
                            <p class="wow fadeInUp" data-wow-delay="0.5s">
                                {{ data_get($content, 'description') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex bg-light rounded-5 p-3">
                <iframe src="{{ data_get($content, 'source') }}" frameborder="0" class="w-100 rounded-4 ratio-19by9"></iframe>
            </div>
        </div>
    </div>
@endsection
