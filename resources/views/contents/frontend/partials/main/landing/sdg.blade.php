@props([
    'noPadding' => false,
])

<section class="sdg-section {{ $noPadding ? 'p-0' : '' }}">
    <div class="sdg-content wow fadeInUp">
        <div class="container">
            <div class="row">
                <div class="col-lg-5">
                    <div class="company-growth-image">
                        <figure class="image-anime reveal">
                            <img style="{{ $sdgData->images->main['style'] }}" src="{{ asset($sdgData->images->main['src']) }}"
                                alt="{{ $sdgData->images->main['alt'] }}">
                        </figure>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="company-growth-content">
                        <div class="section-title">
                            <h3 class="wow fadeInUp">
                                {{ $sdgData->content->subtitle }}
                            </h3>
                            <h2 class="wow fadeInUp" data-wow-delay="0.25s">
                                {!! $sdgData->content->title !!}
                            </h2>
                            <p class="wow fadeInUp" data-wow-delay="0.5s">
                                {!! $sdgData->content->description !!}
                            </p>
                        </div>
                        @isset($sdgData->content->cta)
                            <a href="{{ $sdgData->content->cta['url'] }}" class="{{ $sdgData->content->cta['class'] }}">{{ $sdgData->content->cta['text'] }}</a>
                        @endisset
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="sdg-icons">
        <div class="row">
            <div class="col-12">
                <div class="swiper sdg-swiper">
                    <div class="swiper-wrapper">
                        @foreach ($sdgData->goals as $goalNumber)
                            <div class="swiper-slide">
                                <img src="{{ asset(\App\Services\Frontend\SDGService::getSDGGoalImagePath($goalNumber)) }}"
                                    alt="{{ \App\Services\Frontend\SDGService::getSDGGoalAltText($goalNumber) }}" 
                                    class="{{ $sdgData->images->goals['class'] }}" 
                                    loading="{{ $sdgData->images->goals['loading'] }}">
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="divider-dark-lg"></div>
    </div>
</section>

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const swiperConfig = {!! $sdgData->swiper_config_json !!};
            const swiper = new Swiper('.sdg-swiper', swiperConfig);
        })
    </script>
@endpush
