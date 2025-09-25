<section class="research-section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-title">
                    <h2 class="wow fadeInUp" data-wow-delay="0.1s">
                        <span>Kolaborasi</span> Riset
                    </h2>
                </div>
            </div>
        </div>
        <div class="row small-grid-card-gallery">
            @for ($i = 0; $i < 6; $i++)
                <div class="col-lg-4 col-md-6">
                    <a href="#" class="small-grid-card-item hoverable-card hoverable-card-sm wow fadeInUp"
                        data-cursor-text="Lihat" data-wow-delay="{{ $i * 0.1 + 0.2 }}s">
                        <div class="small-grid-card-image image-anime">
                            <img src="{{ asset('theme/frontend/images/examples/berita-1.png') }}" alt="Berita">
                        </div>
                        <div class="small-grid-card-content">
                            <h3 class="small-grid-card-title">
                                Lorem ipsum dolor sit amet consectetur adipisicing elit. Vel
                                nostrum
                                quibusdam corrupti doloribus ullam? Accusantium culpa cum nostrum nam dolorum corporis
                                quasi,
                                qui iure nesciunt! Ducimus dicta velit unde in.
                            </h3>
                            <span class="small-grid-card-date">{{ now()->addDays(-8)->diffForHumans() }}</span>
                        </div>
                    </a>
                </div>
            @endfor
        </div>
    </div>
</div>
