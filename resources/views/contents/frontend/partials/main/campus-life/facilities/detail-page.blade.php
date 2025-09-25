<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="section-title">
                <h2 class="wow fadeInUp" data-wow-delay="0.25s">
                    {!! data_get($content, 'title') !!}
                </h2>
                <p class="wow fadeInUp" data-wow-delay="0.5s">
                    {{ data_get($content, 'description') }}
                </p>
            </div>
            @if (data_get($content, 'images.main.src'))
                <div class="facility-main-image">
                    <figure class="image-anime reveal">
                        <img src="{{ data_get($content, 'images.main.src') }}" alt="{{ data_get($content, 'images.main.alt') }}" class="img-fluid">
                    </figure>
                </div>
            @endif

            @if (data_get($content, 'sections'))
                @foreach (data_get($content, 'sections') as $section)
                    <div class="facility-section mt-5">
                        <h3 class="wow fadeInUp">{{ data_get($section, 'title') }}</h3>
                        <div class="wow fadeInUp" data-wow-delay="0.2s">
                            {!! data_get($section, 'body') !!}
                        </div>
                    </div>
                @endforeach
            @endif

            @if (data_get($content, 'images.gallery'))
                <div class="facility-gallery mt-5">
                    <h3 class="wow fadeInUp">Galeri</h3>
                    <div class="row">
                        @foreach (data_get($content, 'images.gallery') as $galleryImage)
                            <div class="col-md-4 mb-4">
                                <figure class="image-anime reveal">
                                    <img src="{{ data_get($galleryImage, 'src') }}" alt="{{ data_get($galleryImage, 'alt') }}" class="img-fluid">
                                </figure>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
