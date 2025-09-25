@extends('layouts.frontend.main')

@breadcrumbs([data_get($pageConfig, 'seo.title'), url()->current()])

<x-frontend.seo :pageConfig="$pageConfig" />

@section('content')
    <x-frontend.page-header :breadcrumbs="$breadcrumbs" :image="data_get($pageConfig, 'background_image')">
        {!! data_get($content, 'header') !!}
    </x-frontend.page-header>

    <div class="pcr-squad-page content-page">
        <div class="pmb-page content-page" style="padding: 0;">
            @include('contents.frontend.partials.main.pcr-squad.pmb.about', ['content' => data_get($content, 'pmb')])
            @include('contents.frontend.partials.main.pcr-squad.pmb.why-pcr', ['content' => data_get($content, 'why_pcr')])
        </div>

        {{-- <div class="international-student-page content-page" style="padding: 0;">
            @include('contents.frontend.partials.main.pcr-squad.international-student.welcome')
            @include('contents.frontend.partials.main.pcr-squad.international-student.prerequisite')
        </div> --}}

        <div class="recruitment-page content-page">
            @include('contents.frontend.partials.main.pcr-squad.recruitment.about', ['content' => data_get($content, 'recruitment')])
        </div>
    </div>
@endsection
