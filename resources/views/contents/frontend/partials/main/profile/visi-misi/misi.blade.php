<section class="our-service bg-section pcr-mision-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-5">
                <div class="service-content z-2">
                    <div class="section-title">
                        @if (data_get($content, 'mission.subtitle'))
                            <h3 class="wow fadeInUp">
                                {{ data_get($content, 'mission.subtitle') }}
                            </h3>
                        @endif
                        <h2 class="wow fadeInUp" data-wow-delay="0.25s">
                            <span>{{ data_get($content, 'mission.title') }}</span>
                        </h2>
                        <p class="wow fadeInUp" data-wow-delay="0.5s">
                            {{ data_get($content, 'mission.introduction') }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="service-item-list">
                    @php
                        $missions = data_get($content, 'mission.description', []);
                    @endphp

                    @foreach ($missions as $idx => $mission)
                        <div class="service-item wow fadeInUp">
                            <div class="icon-box">
                                @if ($idx === 0)
                                    <i class="fa-solid fa-graduation-cap"></i>
                                @elseif($idx === 1)
                                    <i class="fa-solid fa-people-group"></i>
                                @elseif($idx === 2)
                                    <i class="fa-solid fa-computer"></i>
                                @else
                                    <i class="fa-solid fa-book"></i>
                                @endif
                            </div>

                            <div class="service-item-content">
                                <p>{{ $mission }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
