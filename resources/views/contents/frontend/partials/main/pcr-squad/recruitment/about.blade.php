<section class="about-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-5">
                <div class="about-image image-anime ">
                    <img src="{{ data_get($content, 'image.src') }}" alt="{{ data_get($content, 'image.alt') }}"
                         class="reveal">
                </div>
            </div>
            <div class="col-lg-7">
                <div class="about-content">
                    <div class="section-title">
                        <h3 class="wow fadeInUp" data-wow-delay="0.1s">
                            {{ data_get($content, 'subtitle') }}
                        </h3>
                        <h2 class="wow fadeInUp" data-wow-delay="0.3s">
                            {!! data_get($content, 'title') !!}
                        </h2>
                        <p class="wow fadeInUp" data-wow-delay="0.5s">
                            {{ data_get($content, 'description') }}
                        </p>
                    </div>

                    <div class="about-cta">
                        <a class="btn btn-default" href="{{ data_get($content, 'link.0.url') }}" target="_blank">
                            {{ data_get($content, 'link.0.text') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
