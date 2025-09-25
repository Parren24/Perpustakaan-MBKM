<section class="our-service bg-section why-pcr-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-5">
                <!-- Service Content Start -->
                <div class="service-content">
                    <!-- Section Title Start -->
                    <div class="section-title">
                        <h3 class="wow fadeInUp">{{ data_get($content, 'subtitle') }}</h3>
                        <h2 class="wow fadeInUp" data-wow-delay="0.25s">
                            {!! data_get($content, 'title') !!}
                        </h2>
                        <p class="wow fadeInUp" data-wow-delay="0.5s">
                            {{ data_get($content, 'description') }}
                        </p>
                    </div>
                    <!-- Section Title End -->
                </div>
                <!-- Service Content End -->
            </div>

            <div class="col-lg-7">
                <!-- Service Item List Start -->
                <div class="service-item-list">
                    <!-- Service Item Start -->
                    @foreach (data_get($content, 'items', []) as $item)
                        <div class="service-item wow fadeInUp" data-wow-delay="0.{{ $loop->index + 1 }}s">
                            <div class="icon-box">
                                <i class="{{ data_get($item, 'icon') }}"></i>
                            </div>

                            <div class="service-item-content">
                                <h3>{{ data_get($item, 'title') }}</h3>
                                <p>{{ data_get($item, 'description') }}</p>
                            </div>
                        </div>
                    @endforeach
                    <!-- Service Item Start -->
                </div>
                <!-- Service Item List End -->
            </div>
        </div>
    </div>
</section>
