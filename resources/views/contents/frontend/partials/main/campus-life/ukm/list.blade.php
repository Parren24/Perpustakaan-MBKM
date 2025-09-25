<section class="activity-gallery-section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-title">
                    <h3 class="wow fadeInUp">
                        Daftar UKM
                    </h3>
                    <h2 class="wow fadeInUp" data-wow-delay="0.25s">
                        Temukan UKM yang Sesuai dengan <span>Minat</span> dan <span>Bakat</span> Anda
                    </h2>
                    <p class="wow fadeInUp" data-wow-delay="0.5s">
                        Jika Anda mencari UKM, Politeknik Caltex Riau menawarkan berbagai organisasi kemahasiswaan yang
                        dapat membantu Anda mengembangkan minat, bakat, dan keterampilan. UKM kami mencakup berbagai
                        bidang seperti seni, olahraga, teknologi, dan sosial kemasyarakatan, yang bertujuan untuk
                        meningkatkan kualitas diri mahasiswa serta memperkuat rasa kebersamaan dan kepedulian sosial.
                    </p>
                </div>
            </div>

            @for ($i = 0; $i < 9; $i++)
                <div class="col-lg-4 col-md-6 mb-4">
                    <a href="{{ route('frontend.campus-life.ukm.detail', ['ukmId' => $i]) }}" class="wow fadeInUp activity-item hoverable-card" data-wow-delay="0.4s" data-cursor-text="Lihat Detail">
                        <figure class="image-anime">
                            <img src="{{ asset('theme/frontend/images/placeholders/16x9.png') }}" alt="ukm Image">
                        </figure>
                        <div class="activity-content">
                            <h3>
                                ukm Politeknik Caltex Riau
                            </h3>
                            <p>
                                Lorem ipsum dolor sit amet consectetur adipisicing elit. Similique fugiat maiores
                                ipsa
                                sequi fugit ut rem tenetur veritatis nulla quaerat.
                            </p>

                        </div>
                    </a>
                </div>
            @endfor
        </div>
    </div>
</section>
