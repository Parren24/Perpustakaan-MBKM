@extends('layouts.frontend.main')

@breadcrumbs([data_get($pageConfig, 'seo.title'), url()->current()])

<x-frontend.seo :pageConfig="$pageConfig" />

@section('content')
    <x-frontend.page-header :breadcrumbs="$breadcrumbs" :image="data_get($pageConfig, 'background_image')">
        {{ data_get($content, 'header') }}
    </x-frontend.page-header>

    <div class="page-service-single" id="identity-and-guidelines">
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    @include('contents.frontend.partials.main.profile.identity.sidebar')
                </div>

                <div class="col-lg-8">
                    {{-- Service Single Content Start --}}
                    <div class="service-single-content">
                        {{-- Service Entry Start --}}
                        @foreach (data_get($content, 'identity_guide', []) as $item)
                            @include('contents.frontend.partials.main.profile.identity.identity_guide_item', ['item' => $item])
                        @endforeach
                        {{-- Service Entry End --}}

                    </div>
                    {{-- Service Single Content End --}}
                </div>
            </div>
        </div>
    </div>
@endsection
