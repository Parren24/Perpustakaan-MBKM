<section class="activity-gallery-section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-title">
                    <h3 class="wow fadeInUp">
                        Daftar Organisasi
                    </h3>
                    <h2 class="wow fadeInUp" data-wow-delay="0.25s">
                        Organisasi Kemahasiswaan di Politeknik Caltex Riau
                    </h2>
                </div>
            </div>

            @for ($i = 0; $i < 3; $i++)
                <div class="col-lg-4 col-md-6 mb-4">
                    <a href="{{ route('frontend.campus-life.student-organization.detail', ['organizationId' => $i]) }}"
                        class="wow fadeInUp activity-item hoverable-card" data-wow-delay="0.4s"
                        data-cursor-text="Lihat Detail">
                        <figure class="image-anime">
                            <img src="{{ asset('theme/frontend/images/placeholders/16x9.png') }}" alt="ukm Image">
                        </figure>
                        <div class="activity-content">
                            <h3>
                                Badan Eksekutif Mahasiswa
                            </h3>
                            <p>
                                Lorem ipsum dolor sit amet consectetur adipisicing elit. Deleniti optio ullam mollitia
                                sint eos quisquam? Atque recusandae nisi ea velit!
                            </p>
                        </div>
                    </a>
                </div>
            @endfor
        </div>
    </div>
</section>
