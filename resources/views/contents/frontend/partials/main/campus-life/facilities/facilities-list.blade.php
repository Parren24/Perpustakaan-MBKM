<section class="facilities-list-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-4">
                {{-- Service Sidebar Start --}}
                <div class="service-sidebar">
                    {{-- Service Category List Start --}}
                    <div class="service-catagery-list wow fadeInUp">
                        <h3>Fasilitas</h3>
                        <ul>
                            @foreach ($facilities_list as $facility)
                                <li><a href="#{{ Str::slug(data_get($facility, 'title')) }}">{{ data_get($facility, 'title') }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                    {{-- Service Category List End --}}
                </div>
                {{-- Service Sidebar End --}}
            </div>

            <div class="col-lg-8">
                {{-- Service Single Content Start --}}
                <div class="service-single-content">
                    @foreach ($facilities_list as $facility)
                        <div class="service-entry divider-dark-lg">
                            <h2 class="wow fadeInUp" data-wow-delay="0.4s" id="{{ Str::slug(data_get($facility, 'title')) }}">
                                <span>{{ data_get($facility, 'title') }}</span>
                            </h2>

                            <p class="wow fadeInUp" data-wow-delay="0.2s">
                                {{ data_get($facility, 'description') }}
                            </p>

                            @if (data_get($facility, 'image'))
                                {{-- <div class="service-entry-img"> --}}
                                @foreach (data_get($facility, 'image', []) as $item)
                                    <figure class="image-anime reveal w-100">
                                        <img src="{{ data_get($item, 'src') }}" alt="{{ data_get($item, 'alt') }}" class="rounded-4 w-100">
                                    </figure>
                                @endforeach
                                {{-- </div> --}}
                            @endif
                        </div>
                    @endforeach
                    {{-- Service Entry End --}}
                </div>
                {{-- Service Single Content End --}}
            </div>
        </div>
    </div>
</section>
