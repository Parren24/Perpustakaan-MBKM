<section class="bg-slider-section bg-section">
    <div class="container ">
        <div class="section-title">
            <h2 class="wow fadeInUp" data-wow-delay="0.2s">
                Fasilitas di <span>Politeknik Caltex Riau</span>
            </h2>
            <p class="wow fadeInUp" data-wow-delay="0.4s">
                Politeknik Caltex Riau menyediakan berbagai fasilitas modern untuk mendukung proses belajar mengajar
                mahasiswa internasional. Dari ruang kelas yang dilengkapi teknologi mutakhir hingga laboratorium praktis
                yang lengkap, kami memastikan setiap mahasiswa memiliki akses ke sumber daya yang diperlukan untuk
                mencapai kesuksesan akademis dan profesional.
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
                                <span>Ruang Kelas Modern</span>
                            </h3>
                        </div>
                        <p class="wow fadeInUp" data-wow-delay="0.2s">
                            Ruang kelas kami dilengkapi dengan teknologi terkini untuk mendukung proses belajar mengajar
                            yang
                            interaktif dan efektif.
                        </p>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="overlay"></div>
                    <img src="{{ asset('theme/frontend/images/placeholders/16x9.png') }}" alt="Facilities Image 1">
                    <div class="container">
                        <div class="section-title">
                            <h3 class="wow fadeInUp">
                                <span>Ruang Kelas Modern</span>
                            </h3>
                        </div>
                        <p class="wow fadeInUp" data-wow-delay="0.2s">
                            Ruang kelas kami dilengkapi dengan teknologi terkini untuk mendukung proses belajar mengajar
                            yang
                            interaktif dan efektif.
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
