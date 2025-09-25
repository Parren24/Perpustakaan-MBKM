<section class="agenda-section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-title">
                    <h2 class="wow fadeInOut">Agenda</h2>
                </div>
            </div>
            <div class="col-12">
                <div class="swiper agenda-slider image-gallery-with-description">
                    <div class="swiper-wrapper">
                        @for ($i = 0; $i < 7; $i++)
                            <div class="swiper-slide">
                                <div class="item-wrapper">
                                    <a class="agenda-item image-item hoverable-card" href="#">
                                        {{-- <figure class="image-anime">
                                            <img src="{{ asset('theme/frontend/images/placeholders/16x9.png') }}"
                                                alt="Agenda {{ $i + 1 }}">
                                        </figure> --}}
                                        <div class="agenda-content image-content">
                                            <h3 class="bg-primary rounded rounded-4 p-2">Technopreneurship</h3>
                                            <div class="agenda-meta">
                                                <span class="date">12 Desember 2023</span>
                                                <span class="time">12:00 - 13:00</span>
                                                <span class="location">Auditorium</span>
                                            </div>
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
            const swiper = new Swiper('.swiper.agenda-slider', {
                slidesPerView: 2,
                spaceBetween: 0,
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                breakpoints: {
                    767: {
                        slidesPerView: 3,
                    },
                    991: {
                        slidesPerView: 4,
                    },
                    1280: {
                        slidesPerView: 5,
                    },
                }
            });
        })
    </script>
@endpush
