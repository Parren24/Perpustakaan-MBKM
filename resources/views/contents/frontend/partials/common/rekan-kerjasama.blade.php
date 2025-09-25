@props([
    'renderAbout' => true,
    'renderTitle' => true,
    'renderSlides' => true,
    'noPadding' => false,
])

<section class="rekan-section">
    @if ($renderAbout)
        @include('contents.frontend.partials.common.mitra-kerjasama.content', [
            'noPadding' => $noPadding,
            'content' => data_get($partnershipData, 'content'),
        ])
    @endif

    @if ($renderTitle)
        @include('contents.frontend.partials.common.mitra-kerjasama.title', [
            'content' => data_get($partnershipData, 'content'),
        ])
    @endif

    @if ($renderSlides)
        @include('contents.frontend.partials.common.mitra-kerjasama.sliders', [
            'partners' => data_get($partnershipData, 'partners'),
            'image_config' => data_get($partnershipData, 'image_config'),
            'swiper_config_json' => data_get($partnershipData, 'swiper_config_json'),
        ])
    @endif
</section>
