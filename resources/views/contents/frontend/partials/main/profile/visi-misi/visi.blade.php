<section class="about-us pcr-vision-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                {{-- About Us Image Start --}}
                <div class="about-us-images">
                    {{-- About Us Img Start --}}
                    <div class="about-us-img-1">
                        <figure class="image-anime">
                            <img src="{{ data_get($content, 'vision.images.main.src') }}" alt="">
                        </figure>
                    </div>
                    {{-- About Us Img End --}}

                    {{-- About Us Img Start --}}
                    <div class="about-us-img-2">
                        <figure class="image-anime">
                            <img src="{{ data_get($content, 'vision.images.thumb.src') }}" alt="">
                        </figure>
                    </div>
                    {{-- About Us Img End --}}

                    {{-- About Experience Box Start --}}
                    <div class="about-experience-box">
                        <div class="about-experience-content">
                            <h3><span class="counter">{{ date('Y') - data_get($siteIdentity, 'identity.established_year', 0) }}</span>+ tahun kontribusi</h3>
                        </div>
                    </div>
                    {{-- About Experience Box End --}}
                </div>
                {{-- About Us Image End --}}
            </div>

            <div class="col-lg-6">
                {{-- About Us Content Start --}}
                <div class="about-us-content">
                    {{-- Section Title Start --}}
                    <div class="section-title">
                        @if (data_get($content, 'vision.subtitle'))
                            <h3 class="wow fadeInUp">
                                {{ data_get($content, 'vision.subtitle') }}
                            </h3>
                        @endif
                        <h2 class="wow fadeInUp" data-wow-delay="0.2s">
                            <span>{{ data_get($content, 'vision.title') }}</span>
                        </h2>
                        <p>
                            {{ data_get($content, 'vision.introduction') }}
                        </p>
                    </div>
                    {{-- Section Title End --}}

                    {{-- About Us List Start --}}
                    <div class="about-us-list wow fadeInUp" data-wow-delay="0.6s">
                        <ul>
                            <li>{{ data_get($content, 'vision.description', 'Diakui Sebagai Politeknik Unggul yang Mampu Bersaing Dalam Bidang Teknologi dan Bisnis Terapan pada Tingkat Nasional Maupun ASEAN Tahun 2031') }}</li>
                        </ul>
                    </div>
                    {{-- About Us List End --}}
                </div>
                {{-- About Us Content End --}}
            </div>
        </div>
    </div>
</section>
