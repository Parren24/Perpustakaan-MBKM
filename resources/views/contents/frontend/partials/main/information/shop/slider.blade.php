<section class="shop-slider">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="swiper shop-slider-swiper">
                    <div class="swiper-wrapper">
                        @for ($i = 0; $i < 4; $i++)
                            <div class="swiper-slide">
                                <div class="shop-slider-wrapper">
                                    <a href="#" class="shop-slider-item" data-cursor-text="Detail Item">
                                        <figure class="image-anime">
                                            <img src="{{ asset('theme/frontend/images/placeholders/1x1.png') }}"
                                                alt="Featured Item-1" class="reveal">
                                        </figure>
                                        <div class="shop-slider-content">
                                            <h3>
                                                Lorem, ipsum.
                                            </h3>
                                            <span>
                                                Rp. {{ number_format(100000 + $i * 50000, 0, ',', '.') }}
                                            </span>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        @endfor
                    </div>

                    <div class="swiper-pagination"></div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const shopSliderSwiper = new Swiper('.shop-slider-swiper', {
                slidesPerView: 1,
                spaceBetween: 20,
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
            });
        })
    </script>
@endpush
