@php
    $highlighted = data_get($content, 'highlighted');
    $newest = data_get($content, 'newest');
@endphp
<section class="newest-section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                @if ($highlighted)
                    <div class="swiper" id="newest-swiper">
                        <div class="swiper-wrapper">
                            @foreach ($highlighted as $highlight)
                                <div class="swiper-slide">
                                    <a href="{{ data_get($highlight, 'url') }}" data-cursor-text="Lihat">
                                        <div class="newest-slider-img image-anime">
                                            <img src="{{ data_get($highlight, 'images.src') }}"
                                                alt="{{ data_get($highlight, 'images.alt') }}">

                                            <div class="newest-slider-overlay"></div>
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
                @else
                    <span class="font-italic">- Deskripsi tentang belum diatur -</span>
                @endif
            </div>
        </div>
        <div class="row newest-grid">
            <div class="col-12">
                <div class="section-title">
                    <h2 class="wow fadeInUp" data-wow-delay="0.1s">
                        Berita <span>Terkini</span>
                    </h2>
                </div>
            </div>
            @if ($newest)
                @foreach ($newest as $news)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <a href="{{ data_get($news, 'url') }}" class="wow fadeInUp newest-grid-item hoverable-card"
                            data-wow-delay="0.1s" data-cursor-text="Lihat">
                            <div class="image-anime newest-grid-image">
                                <img src="{{ data_get($news, 'images.src') }}"
                                    alt="{{ data_get($news, 'images.alt') }}">
                            </div>
                            <div class="newest-grid-content">
                                <h3 class="newest-grid-title">
                                    {{ data_get($news, 'title') }}
                                </h3>
                                <span class="newest-grid-date">{{ data_get($news, 'timestamp') }}</span>
                            </div>
                        </a>
                    </div>
                @endforeach
            @else
                <span class="font-italic">- Deskripsi tentang belum diatur -</span>
            @endif
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
