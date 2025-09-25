<section class="scholarship-about-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-5 d-flex align-items-center">
                <figure class="image-anime">
                    <img src="{{ data_get($content, 'image.src') }}" alt="{{ data_get($content, 'image.alt') }}"
                         class="img-fluid">
                </figure>
            </div>
            <div class="col-lg-7">
                <div class="scholarship-about-content">
                    <div class="section-title">
                        <h3 class="wow fadeInUp">
                            {{ data_get($content, 'subtitle') }}
                        </h3>
                        <h2 class="wow fadeInUp" data-wow-delay="0.25s">
                            {!! data_get($content, 'title') !!}
                        </h2>
                        @foreach (data_get($content, 'description', []) as $item)
                            <p class="wow fadeInUp" data-wow-delay="0.5s">
                                {!! $item !!}
                            </p>
                        @endforeach
                    </div>
                    {{-- Remove static counters if not dynamic --}}
                    {{--
                    <div class="mb-3">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="wow fadeInUp data-info-item" data-wow-delay="0.75s">
                                        <h4><span class="counter">701</span></h4>
                                        <div>Penerima Beasiswa Semester Ini</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="wow fadeInUp data-info-item" data-wow-delay="0.75s">
                                        <h4><span class="counter">302</span></h4>
                                        <div>Penerima Beasiswa Swasta</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="wow fadeInUp data-info-item" data-wow-delay="0.75s">
                                        <h4><span class="counter">420</span></h4>
                                        <div>Penerima Beasiswa Pemerintah</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    --}}
                    {{-- Assuming there might be a CTA in the future, keep the structure --}}
                    {{--
                    <div class="scholarship-about-cta wow fadeInUp" data-wow-delay="1s">
                        <a href="#" class="btn-default btn-highlighted btn-download">
                            Unduh Panduan Beasiswa
                        </a>
                    </div>
                    --}}
                </div>
            </div>
        </div>
    </div>
    </div>
</section>
