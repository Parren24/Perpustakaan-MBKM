<section class="accreditation-content">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <img src="{{ data_get($content, 'certificates.0.image.src') }}" alt="data_get($content, 'certificates.0.image.alt')" class="section-img">
            </div>
            <div class="col-lg-6">
                <div class="section-title">
                    @if (data_get($content, 'subtitle'))
                        <h3 class="wow fadeInUp">
                            {{ data_get($content, 'subtitle') }}
                        </h3>
                    @endif
                    <h2 class="wow fadeInUp" data-wow-delay="0.25s">
                        {!! data_get($content, 'title') !!}
                    </h2>
                    @foreach (data_get($content, 'description', []) as $p)
                        <p class="wow fadeInUp" data-wow-delay="0.5s">{{ $p }}</p>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
