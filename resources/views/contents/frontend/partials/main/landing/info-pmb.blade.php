<section class="our-potential pmb-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 order-2 order-lg-1 mt-3 mt-lg-0">
                <div class="our-potential-content">
                    <div class="section-title">
                        <h3 class="wow fadeInUp">
                            {{ data_get($pmbData, 'content.subtitle') }}
                        </h3>
                        <h2 class="wow fadeInUp" data-wow-delay="0.25s">
                            {!! data_get($pmbData, 'content.title') !!}
                        </h2>
                        <p class="wow fadeInUp" data-wow-delay="0.5s">
                            {!! data_get($pmbData, 'content.description') !!}
                        </p>
                    </div>
                    <div class="our-potential-body">
                        <div class="potential-body-list wow fadeInUp" data-wow-delay="0.4s">
                            <ul>
                                @foreach (data_get($pmbData, 'highlights', []) as $highlight)
                                    <li>{{ $highlight }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="our-potential-btn wow fadeInUp" data-wow-delay="0.75s">
                        <a class="{{ data_get($pmbData, 'actions.primary.class') }}" href="{{ data_get($pmbData, 'actions.primary.url') }}">
                            {{ data_get($pmbData, 'actions.primary.text') }}
                        </a>
                    </div>
                    <div class="our-potential-btn wow fadeInUp d-flex flex-wrap" data-wow-delay="0.75s">
                        @foreach (data_get($pmbData, 'actions.downloads', []) as $download)
                            <a class="{{ data_get($download, 'class') }} text-nowrap" href="{{ data_get($download, 'url') }}">
                                {{ data_get($download, 'text') }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-lg-6 order-1 order-lg-2">
                <div class="our-potential-img">
                    <figure class="image-anime reveal">
                        <img src="{{ asset(data_get($pmbData, 'content.image.src')) }}" alt="{{ data_get($pmbData, 'content.image.alt') }}"
                             class="object-fit-contain">
                    </figure>
                </div>
            </div>
        </div>
    </div>
</section>
