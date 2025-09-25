<section class="pkm-section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-title">
                    <h3 class="wow fadeInUp" data-wow-delay="0.1s">
                        Fasilitas Penelitian dan PKM
                    </h3>
                    <h2 class="wow fadeInUp" data-wow-delay="0.2s">
                        Alat Siap Mendukung <span>Kegiatan Akademik</span> dan <span>Inovasi</span> Anda
                    </h2>
                </div>
            </div>
            <div class="col-12">
                <div class="swiper pkm-swiper">
                    <div class="swiper-wrapper">
                        @for ($i = 0; $i < 5; $i++)
                            <div class="swiper-slide">
                                <div class="pkm-wrapper">
                                    <a href="#" class=" hoverable-card">
                                        <div class="pkm-content"
                                            style="background: linear-gradient(to top, rgb(var(--primary-main-rgb)) -20%, transparent 80%), url('{{ asset('theme/frontend/images/examples/riset-1.webp') }}') center / cover no-repeat; background-color: rgb(var(--primary-main-rgb), 0.1);">
                                            <h3 class="pkm-title">Laboratorium Mobile Application Development</h3>
                                            <div class="pkm-date">
                                                6 jam yang lalu
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
            const swiper = new Swiper('.pkm-swiper', {
                slidesPerView: 1,
                spaceBetween: 0,
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                breakpoints: {
                    768: {
                        slidesPerView: 2,
                    },
                }
            });
        });
    </script>
@endpush
