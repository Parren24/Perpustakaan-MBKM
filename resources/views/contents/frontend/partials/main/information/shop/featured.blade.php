<section class="featured-items-section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-title">
                    <h2 class="wow fadeInUp" data-wow-delay="0.1s">
                        Barang <span>Pilihan</span>
                    </h2>
                    <p class="wow fadeInUp" data-wow-delay="0.2s">
                        Berikut adalah beberapa barang pilihan yang tersedia di toko kami.
                    </p>
                </div>
            </div>
        </div>
        <div class="row">
            {{-- <div class="col-12">
                <div class="swiper featured-items-swiper">
                    <div class="swiper-wrapper">
                        @for ($i = 0; $i < 4; $i++)
                            <div class="swiper-slide">
                                <div class="featured-item-wrapper ">
                                    <a href="#" class="featured-item hoverable-card"
                                        data-cursor-text="Detail Item">
                                        <figure class="image-anime">
                                            <img src="{{ asset('theme/frontend/images/placeholders/1x1.png') }}"
                                                alt="Featured Item-1" class="reveal">
                                        </figure>
                                        <div class="featured-item-content">
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
            </div> --}}

            @for ($i = 0; $i < 6; $i++)
                <div class="col-lg-4 col-md-6 mb-4">
                    <a href="{{ route('frontend.information.shop.show', ["id" => "ID"]) }}" class="featured-item hoverable-card" data-cursor-text="Detail Item">
                        <figure class="image-anime">
                            <img src="{{ asset('theme/frontend/images/placeholders/1x1.png') }}" alt="Featured Item-1"
                                class="reveal">
                        </figure>
                        <div class="featured-item-content">
                            <h3>
                                Lorem, ipsum.
                            </h3>
                            <span>
                                Rp. {{ number_format(100000 + $i * 50000, 0, ',', '.') }}
                            </span>
                        </div>
                    </a>
                </div>
            @endfor
        </div>
    </div>
</section>

{{-- @push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const featuredItemsSwiper = new Swiper('.featured-items-swiper', {
                slidesPerView: 1,
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                breakpoints: {
                    480: {
                        slidesPerView: 2,
                    },
                    1024: {
                        slidesPerView: 3,
                    },
                }
            });
        })
    </script>
@endpush --}}
