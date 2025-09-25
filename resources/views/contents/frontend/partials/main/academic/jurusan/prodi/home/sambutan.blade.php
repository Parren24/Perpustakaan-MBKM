<section class="about-us sambutan-prodi-section p-0">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="about-us-images">
                    <div class="about-us-img-1">
                        <figure class="image-anime">
                            <img src="{{ asset('theme/frontend/images/placeholders/3x4.png') }}" alt="">
                        </figure>
                    </div>

                    <div class="about-us-img-2">
                        <figure class="image-anime">
                            <img src="{{ asset('theme/frontend/images/placeholders/1x1.png') }}" alt="">
                        </figure>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="about-us-content">
                    <div class="section-title">
                        <h3 class="wow fadeInUp">Kata Sambutan</h3>
                        <h2 class="wow fadeInUp" data-wow-delay="0.2s">
                            Sambutan dari Ketua Program Studi <span>{{ $prodi->nama_prodi }}</span>
                        </h2>

                    </div>

                    <div class="client-testimonial-item wow fadeInUp" data-wow-delay="0.2s">
                        <div class="client-testimonial-content">
                            <p>
                                Christopher Benjamin is a dynamic Business Manager with expertise in driving
                                operational efficiency and fostering client relationships. With a keen eye for market
                                trends,
                                she strategically plans and implements initiatives that enhance profitability and team
                                performance.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
