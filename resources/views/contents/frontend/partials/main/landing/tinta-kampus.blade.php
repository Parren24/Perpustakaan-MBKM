<section class="our-service bg-section tinta-kampus-section">
    <div class="container z-2 position-relative">
        <div class="row">
            <div class="col-lg-4">
                <div class="service-content">
                    <div class="section-title">
                        <h3 class="wow fadeInUp">
                            {{ $articlesData->content->subtitle }}
                        </h3>
                        <h2 class="wow fadeInUp" data-wow-delay="0.25s">
                            {!! data_get($articlesData, 'content.title') !!}
                        </h2>
                        <p class="wow fadeInUp" data-wow-delay="0.5s">
                            {{ data_get($articlesData, 'content.description') }}
                        </p>

                        <div class="service-btn mt-3">
                            <a href="{{ route('frontend.articles.index') }}" class="btn-default btn-highlighted btn-sm">
                                Cerita lainnya
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <section class="newest-section">
                    <div class="swiper" id="newest-swiper">
                        <div class="swiper-wrapper" style="height: 550px;">
                            @foreach ($articlesData->highlighted as $highlight)
                                <div class="swiper-slide">
                                    <a href="{{ data_get($highlight, 'url') }}" data-cursor-text="Lihat">
                                        <div class="newest-slider-img">
                                            <img src="{{ data_get($highlight, 'images.src') }}"
                                                 alt="{{ data_get($highlight, 'images.alt') }}">
                                        </div>
                                        <div class="newest-slider-content">
                                            <h2>{{ data_get($highlight, 'title') }}</h2>
                                            <span>{{ data_get($highlight, 'timestamp') }}</span>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                        <div class="swiper-pagination"></div>
                    </div>
                </section>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <section class="newest-section">
                    <div class="row newest-grid">
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="section-title mb-0">
                                <h3 class="wow fadeInUp" style="color:white;">
                                    {{ data_get($articlesData, 'content.sections.newest_title') }}
                                </h3>
                            </div>
                            <a href="{{ data_get($articlesData->newest, 'url') }}"
                               class="wow fadeInUp newest-grid-item hoverable-card" data-wow-delay="0.1s"
                               data-cursor-text="Lihat">
                                <div class="image-anime newest-grid-image">
                                    <img src="{{ data_get($articlesData->newest, 'images.src') }}"
                                         alt="{{ data_get($articlesData->newest, 'images.alt') }}">
                                </div>
                                <div class="newest-grid-content">
                                    <div class="newest-grid-title" style="color:white;">
                                        {{ data_get($articlesData->newest, 'title') }}
                                    </div>
                                    <span class="newest-grid-date"
                                          style="color:white;">{{ data_get($articlesData->newest, 'timestamp') }}</span>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="section-title mb-0">
                                <h3 class="wow fadeInUp" style="color:white;">
                                    {{ data_get($articlesData, 'content.sections.achievements_title') }}
                                </h3>
                            </div>
                            <a href="{{ data_get($articlesData->achievements, 'url') }}"
                               class="wow fadeInUp newest-grid-item hoverable-card" data-wow-delay="0.1s"
                               data-cursor-text="Lihat">
                                <div class="image-anime newest-grid-image">
                                    <img src="{{ data_get($articlesData->achievements, 'images.src') }}"
                                         alt="{{ data_get($articlesData->achievements, 'images.alt') }}">
                                </div>
                                <div class="newest-grid-content">
                                    <div class="newest-grid-title" style="color:white;">
                                        {{ data_get($articlesData->achievements, 'title') }}
                                    </div>
                                    <span class="newest-grid-date"
                                          style="color:white;">{{ data_get($articlesData->achievements, 'timestamp') }}</span>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="section-title mb-0">
                                <h3 class="wow fadeInUp" style="color:white;">
                                    {{ data_get($articlesData, 'content.sections.researches_title') }}
                                </h3>
                            </div>
                            <a href="{{ data_get($articlesData->researches, 'url') }}"
                               class="wow fadeInUp newest-grid-item hoverable-card" data-wow-delay="0.1s"
                               data-cursor-text="Lihat">
                                <div class="image-anime newest-grid-image">
                                    <img src="{{ data_get($articlesData->researches, 'images.src') }}"
                                         alt="{{ data_get($articlesData->researches, 'images.alt') }}">
                                </div>
                                <div class="newest-grid-content">
                                    <div class="newest-grid-title" style="color:white;">
                                        {{ data_get($articlesData->researches, 'title') }}
                                    </div>
                                    <span class="newest-grid-date"
                                          style="color:white;">{{ data_get($articlesData->researches, 'timestamp') }}</span>
                                </div>
                            </a>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</section>

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const swiper = new Swiper('#newest-swiper', {
                slidesPerView: 1,
                speed: 2500,
                loop: true,
                spaceBetween: 20,
                autoplay: {
                    delay: 5000,
                    pauseOnMouseEnter: true,
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
            })
        })
    </script>
@endpush
