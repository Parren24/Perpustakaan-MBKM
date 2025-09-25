<section class="activity-gallery-section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-title">
                    <h3 class="wow fadeInUp">
                        Kegiatan
                    </h3>
                    <h2 class="wow fadeInUp" data-wow-delay="0.25s">
                        <span>Galeri</span> HIMASISTIFO
                    </h2>
                </div>
            </div>
        </div>
        <div class="row image-gallery-with-description">
            @for ($i = 0; $i < 6; $i++)
                <div class="col-lg-4 col-md-6 mb-4">
                    <a href="#" class="wow fadeInUp image-item hoverable-card" data-wow-delay="0.4s" data-cursor-text="Lihat">
                        <figure class="image-anime">
                            <img src="{{ asset('theme/frontend/images/placeholders/16x9.png') }}" alt="ukm Image">
                        </figure>
                    </a>
                </div>
            @endfor
        </div>
    </div>
</section>
