<section class="pmb-content pmb-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                {{-- About Us Image Start --}}
                <div class="about-us-images">
                    {{-- About Us Img Start --}}
                    <div class="about-us-img-1">
                        <figure class="image-anime">
                            <img src="{{ data_get($content, 'images.main.src') }}" alt="{{ data_get($content, 'images.main.alt') }}">
                        </figure>
                    </div>
                    {{-- About Us Img End --}}

                    {{-- About Us Img Start --}}
                    <div class="about-us-img-2">
                        <figure class="image-anime bg-light shadow rounded">
                            <img src="{{ data_get($content, 'images.thumb.src') }}" alt="{{ data_get($content, 'images.thumb.alt') }}" class="rounded-4">
                        </figure>
                    </div>
                    {{-- About Us Img End --}}
                </div>
                {{-- About Us Image End --}}
            </div>

            <div class="col-lg-6">
                {{-- About Us Content Start --}}
                <div class="about-us-content">
                    {{-- Section Title Start --}}
                    <div class="section-title">
                        <h3 class="wow fadeInUp">{{ data_get($content, 'subtitle') }}</h3>
                        <h2 class="wow fadeInUp" data-wow-delay="0.2s">
                            {!! data_get($content, 'title') !!}
                        </h2>
                        <p class="wow fadeInUp" data-wow-delay="0.4s">
                            {{ data_get($content, 'description') }}
                        </p>
                        {{-- About Us List End --}}
                    </div>

                    {{-- About Us Body Start --}}
                    <div class="pmb-cta">
                        <a class="btn btn-default" href="{{ data_get($content, 'link.0.url') }}" target="_blank">
                            {{ data_get($content, 'link.0.text') }}
                        </a>
                    </div>
                </div>
                {{-- About Us Content End --}}
            </div>
        </div>
    </div>

</section>
