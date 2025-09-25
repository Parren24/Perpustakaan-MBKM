<section class="accreditation-grid certificate-grid">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-title">
                    @if (data_get($content, 'grid.subtitle'))
                        <h3 class="wow fadeInUp">
                            {{ data_get($content, 'grid.subtitle') }}
                        </h3>
                    @endif
                    <h2 class="wow fadeInUp" data-wow-delay="0.25s">
                        {!! data_get($content, 'grid.title') !!}
                    </h2>
                </div>
            </div>
            @foreach (data_get($content, 'certificates', []) as $cert)
                <div class="col-lg-4 col-6">
                    <div class="team-member-item wow fadeInUp">
                        <div class="team-image">
                            <a href="#" data-cursor-text="View">
                                <figure class="image-anime">
                                    <img src="{{ data_get($cert, 'image.src', asset('theme/frontend/images/examples/sertifikat-akreditasi.jpeg')) }}" alt="{{ data_get($cert, 'title', 'Sertifikat Akreditasi') }}">
                                </figure>
                            </a>
                        </div>

                        <div class="team-content">
                            <h3>{{ data_get($cert, 'title', 'Terakreditasi') }}</h3>
                            <p>{{ isset($cert['date']) ? \Carbon\Carbon::parse($cert['date'])->translatedFormat('d F Y') : '' }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
