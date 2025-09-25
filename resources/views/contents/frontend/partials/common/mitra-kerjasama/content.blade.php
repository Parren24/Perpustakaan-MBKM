@props([
    'noPadding' => false,
])

<div class="our-expertise rekan-content-section {{ $noPadding ? 'p-0' : '' }}">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="our-expertise-content">
                    <div class="section-title">
                        <h3 class="wow fadeInUp">
                            {{ data_get($content, 'subtitle') }}
                        </h3>
                        <h2 class="wow fadeInUp" data-wow-delay="0.25s">
                            {!! data_get($content, 'title') !!}
                        </h2>
                        <p class="wow fadeInUp" data-wow-delay="0.5s">
                            {!! data_get($content, 'description') !!}
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="expertise-list">
                    @foreach (data_get($content, 'statistics', []) as $stat)
                        <div class="expertise-item wow fadeInUp">
                            <div class="rekan-amount">
                                @if (data_get($stat, 'counter'))
                                    <span class="counter">{{ data_get($stat, 'count') }}</span>
                                @else
                                    <span>{{ data_get($stat, 'count') }}</span>
                                @endif
                            </div>
                            <div class="rekan-text">
                                {{ data_get($stat, 'label') }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
