<section class="explore-food-section bg-slider-section bg-section">
    <div class="container ">
        <div class="section-title">
            <h2 class="wow fadeInUp" data-wow-delay="0.2s">
                Kuliner Khas yang <span>Menggugah Selera</span>
            </h2>
            <p class="wow fadeInUp" data-wow-delay="0.4s">
                Berbicara tentang keunggulan Pekanbaru tak lengkap tanpa menyebutkan kuliner khasnya. Di kawasan Rumbai,
                Anda dapat menemukan berbagai makanan lokal.
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
                                <span>Asam Pedas Ikan Patin</span>
                            </h3>
                        </div>
                        <p class="wow fadeInUp" data-wow-delay="0.2s">
                            Sajian khas Riau dengan rasa pedas dan asam yang segar, sangat cocok dinikmati dengan nasi
                            hangat.
                        </p>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="overlay"></div>
                    <img src="{{ asset('theme/frontend/images/placeholders/16x9.png') }}" alt="Facilities Image 1">
                    <div class="container">
                        <div class="section-title">
                            <h3 class="wow fadeInUp">
                                <span>Roti Jala</span>
                            </h3>
                        </div>
                        <p class="wow fadeInUp" data-wow-delay="0.2s">
                            Camilan berbentuk jala yang biasanya disajikan dengan kari daging yang kaya rempah.
                        </p>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="overlay"></div>
                    <img src="{{ asset('theme/frontend/images/placeholders/16x9.png') }}" alt="Facilities Image 1">
                    <div class="container">
                        <div class="section-title">
                            <h3 class="wow fadeInUp">
                                <span>Kopi Melayu</span>
                            </h3>
                        </div>
                        <p class="wow fadeInUp" data-wow-delay="0.2s">
                            Nikmati cita rasa kopi khas Melayu di warung-warung lokal sambil bersantai menikmati suasana sekitar.
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
