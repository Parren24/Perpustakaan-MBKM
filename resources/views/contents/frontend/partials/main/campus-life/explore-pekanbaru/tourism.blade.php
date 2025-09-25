<section class="bg-slider-section bg-section">
    <div class="container ">
        <div class="section-title">
            <h2 class="wow fadeInUp" data-wow-delay="0.2s">
                Wisata Alam yang <span>Memukau</span>
            </h2>
            <p class="wow fadeInUp" data-wow-delay="0.4s">
                Kecamatan Rumbai menawarkan pesona alam yang memikat hati, cocok bagi Anda yang mencari ketenangan di
                tengah kesibukan kota.
            </p>
        </div>
    </div>

    <div class=" bg-swiper">
        <div class="swiper swiper-container">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <div class="overlay"></div>
                    <img src="{{ asset('theme/frontend/images/placeholders/16x9.png') }}" alt="Facilities Image 1">
                    <div class="container">
                        <div class="section-title">
                            <h3 class="wow fadeInUp">
                                <span>Hutan Kota Pekanbaru</span>
                            </h3>
                        </div>
                        <p class="wow fadeInUp" data-wow-delay="0.2s">
                            Destinasi favorit bagi pecinta alam, dengan jalur trekking, spot foto alami, dan udara
                            segar. Cocok untuk rekreasi bersama keluarga atau sahabat.
                        </p>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="overlay"></div>
                    <img src="{{ asset('theme/frontend/images/placeholders/16x9.png') }}" alt="Facilities Image 1">
                    <div class="container">
                        <div class="section-title">
                            <h3 class="wow fadeInUp">
                                <span>Jembatan dan Sungai Siak</span>
                            </h3>
                        </div>
                        <p class="wow fadeInUp" data-wow-delay="0.2s">
                            Sungai yang membentang di sekitar Rumbai, menawarkan pemandangan matahari terbenam yang
                            indah dengan aktivitas air yang menarik.
                        </p>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="overlay"></div>
                    <img src="{{ asset('theme/frontend/images/placeholders/16x9.png') }}" alt="Facilities Image 1">
                    <div class="container">
                        <div class="section-title">
                            <h3 class="wow fadeInUp">
                                <span>Danau Buatan Rumbai</span>
                            </h3>
                        </div>
                        <p class="wow fadeInUp" data-wow-delay="0.2s">
                            Tempat sempurna untuk menikmati waktu luang dengan suasana tenang, lengkap dengan fasilitas
                            rekreasi seperti perahu dan area piknik.
                        </p>
                    </div>
                </div>
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
</section>

@push('script')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const swiper = new Swiper('.swiper-container', {
                slidesPerView: 1,
                spaceBetween: 10,
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
                loop: true,
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
            });
        })
    </script>
@endpush
