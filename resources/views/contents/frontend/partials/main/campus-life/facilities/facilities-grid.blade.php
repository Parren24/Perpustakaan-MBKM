<div class="facilities-grid-section">
    <div class="container">
        <div class="row">
            @for ($i = 0; $i < 6; $i++)
                <div class="col-lg-4 col-md-6">
                    <a href="{{ route('frontend.campus-life.facilities.detail', [
                        'facilityId' => $i + 1,
                    ]) }}"
                        class="facility-item wow fadeInUp" data-cursor-text="Lihat" data-wow-delay="{{ $i * 0.1 }}s">
                        <div class="facility-image image-anime">
                            <img src="{{ asset('theme/frontend/images/placeholders/16x9.png') }}"
                                alt="Fasilitas {{ $i + 1 }}" class="img-fluid">
                        </div>
                        <div class="facility-content">
                            <h4>Fasilitas {{ $i + 1 }}</h4>
                            <p>Deskripsi singkat tentang fasilitas ini yang mendukung pembelajaran dan kehidupan kampus.
                            </p>
                        </div>
                    </a>
                </div>
            @endfor
        </div>
    </div>
</div>
