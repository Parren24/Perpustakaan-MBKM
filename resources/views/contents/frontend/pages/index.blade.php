@extends('layouts.frontend.main')

<x-frontend.seo :pageConfig="$pageConfig" />

@section('content')
    @include('contents.frontend.partials.main.landing.hero', ['heroData' => $heroData])
    @include('contents.frontend.partials.main.landing.fakta-statistik', ['statisticsData' => $statisticsData])
    @include('contents.frontend.partials.main.landing.info-jurusan', ['jurusanData' => $jurusanData])
    @include('contents.frontend.partials.main.landing.info-pmb', ['pmbData' => $pmbData])
    @include('contents.frontend.partials.main.landing.cta-virtual-tour', ['virtualTourData' => $virtualTourData])
    @include('contents.frontend.partials.main.landing.sdg', ['sdgData' => $sdgData])
    @include('contents.frontend.partials.main.landing.tinta-kampus', ['articlesData' => $articlesData])
    @include('contents.frontend.partials.common.rekan-kerjasama', [
        'renderTitle' => false,
        'partnershipData' => $partnershipData,
    ])
@endsection
