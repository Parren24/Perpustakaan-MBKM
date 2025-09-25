<section class="about-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-5">
                <div class="about-image image-anime reveal">
                    <img src="{{ data_get($content, 'image.src') }}" alt="{{ data_get($content, 'image.alt') }}"
                         class="img-fluid">
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

                        <div class="our-potential-btn wow fadeInUp" data-wow-delay="0.75s">
                            @if (data_get($content, 'action', []))
                                <a class="btn-default {{ data_get($content, 'action.class') }}"
                                   href="{{ data_get($content, 'action.url', '#') }}"
                                   @if (data_get($content, 'action.target')) target="{{ data_get($content, '_blank') }}" @endif>
                                    {{ data_get($content, 'action.text', 'Button') }}
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
