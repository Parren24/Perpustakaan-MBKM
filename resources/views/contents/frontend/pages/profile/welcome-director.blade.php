@extends('layouts.frontend.main')

@breadcrumbs([data_get($pageConfig, 'seo.title'), url()->current()])

<x-frontend.seo :pageConfig="$pageConfig" />

@section('content')
    <x-frontend.page-header :breadcrumbs="$breadcrumbs" :image="data_get($pageConfig, 'background_image')">
        {{ data_get($content, 'header') }}
    </x-frontend.page-header>

    <div class="about-us">
        <div class="container">
            <div class="row align-items-start">
                <div class="col-lg-6">
                    <div class="about-us-images">
                        <div class="about-us-img-1">
                            <figure class="image-anime">
                                <img src="{{ data_get($content, 'images.main.src') }}" alt="{{ data_get($content, 'images.main.alt') }}">
                            </figure>
                        </div>

                        <div class="about-us-img-2">
                            <figure class="image-anime">
                                <img src="{{ data_get($content, 'images.thumb.src') }}" alt="{{ data_get($content, 'images.thumb.alt') }}">
                            </figure>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="about-us-content">
                        <div class="section-title">
                            <h3 class="wow fadeInUp">{{ data_get($content, 'subtitle') }}</h3>
                            <h2 class="wow fadeInUp" data-wow-delay="0.2s">
                                {!! data_get($content, 'director') !!}
                            </h2>

                        </div>

                        <div class="client-testimonial-item wow fadeInUp" data-wow-delay="0.2s">
                            <div class="client-testimonial-content">
                                @php
                                    $paras = data_get($content, 'greeting.paragraphs');
                                @endphp

                                @if (is_array($paras) && count($paras) > 0)
                                    @foreach ($paras as $p)
                                        <p class="mb-2 text-justify">{{ $p }}</p>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
