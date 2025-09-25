@if (data_get($content, 'struktur_organisasi'))
    @foreach (data_get($content, 'struktur_organisasi', []) as $item)
        <div class="organization-profile mb-0 pb-0">
            <div class="container bg-light rounded-5 wow fadeInUp">
                <div class="row">
                    <div class="col-lg-3">
                        {{-- Team Single Image Start --}}
                        {{-- <div class="team-single-image"> --}}
                        <div class="d-flex h-100 flex-column py-5 ps-4">
                            <div class="d-flex border- rounded-4 w-100 justify-content-center bg-body">
                                <figure class="image-anime reveal w-100 justify-content-center">
                                    <img src="{{ data_get($item, 'image.src') }}" alt="{{ data_get($item, 'image.alt') }}" class="w-75">
                                </figure>
                            </div>
                        </div>
                        {{-- </div> --}}
                        {{-- Team Single Image End --}}
                    </div>

                    <div class="col-lg-9">
                        {{-- Team Single Content Start --}}
                        <div class="team-single-content py-5 align-items-center">
                            {{-- Team Member Information Start --}}
                            <div class="team-member-info mb-2">
                                {{-- Team Info Header Start --}}
                                <div class="team-info-header">
                                    <h3 class="wow fadeInUp"><i class="fa fa-book-reader me-2"></i> {{ data_get($item, 'jabatan') }}</h3>
                                    <h2 class="wow fadeInUp" data-wow-delay="0.25s">{{ data_get($item, 'pejabat') }}</h2>
                                </div>
                                {{-- Team Info Header End --}}

                                {{-- Team Info Body Start --}}
                                <div class="team-info-body mb-2">
                                    @foreach (data_get($item, 'profil', []) as $paragraph)
                                        <p class="wow fadeInUp" data-wow-delay="0.5s">
                                            {{ $paragraph }}
                                        </p>
                                    @endforeach
                                </div>
                                {{-- Team Info Body End --}}

                            </div>
                            {{-- Team Member Information End --}}

                            @if (data_get($item, 'link'))
                                <div class="our-potential-btn wow fadeInUp" data-wow-delay="0.75s">
                                    @foreach (data_get($item, 'link', []) as $link)
                                        <a class="{{ data_get($link, 'class', 'btn-default') }}" href="{{ data_get($link, 'url', '#') }}" @if (data_get($link, 'target')) target="{{ data_get($link, '_blank') }}" @endif>
                                            {{ data_get($link, 'text', 'Button') }}
                                        </a>
                                    @endforeach
                                </div>
                            @endif


                        </div>
                        {{-- Team Single Content End --}}
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endif
