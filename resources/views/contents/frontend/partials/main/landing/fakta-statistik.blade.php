@props([
    'noPadding' => false,
])
@php
    $data = data_get($statisticsData, 'data', []);
@endphp

<section class="company-growth fact-statistics-section bg-section {{ $noPadding ? 'p-0' : '' }}" id="fact-and-statistics">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-5">
                <div class="company-growth-image">
                    <figure class="image-anime reveal">
                        <img src="{{ data_get($statisticsData, 'image.src') }}" alt="{{ data_get($statisticsData, 'image.alt') }}">
                    </figure>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="company-growth-content">
                    <div class="section-title">
                        <h3 class="wow fadeInUp">
                            {{ data_get($statisticsData, 'subtitle') }}
                        </h3>
                        <h2 class="wow fadeInUp" data-wow-delay="0.25s">
                            {!! data_get($statisticsData, 'title') !!}
                        </h2>
                        <p class="wow fadeInUp" data-wow-delay="0.5s">
                            {!! data_get($statisticsData, key: 'description') !!}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row fact-statistics-items wow fadeInUp" data-wow-delay="0.25s">
            @if (is_array($data) && count($data) > 0)
                @php
                    $chunks = array_chunk($data, 4);
                @endphp

                @foreach ($chunks as $chunk)
                    <div class="col-xl-3 col-6">
                        <div class="row">
                            @foreach ($chunk as $stat)
                                <div class="col-12">
                                    <div class="fact-statistics-item {{ $stat['important'] ? 'important' : '' }} wow fadeInUp" data-wow-delay="{{ $stat['delay'] }}">
                                        <div class="fact-statistics-icon">
                                            <i class="{{ $stat['icon'] }}"></i>
                                        </div>
                                        <div class="fact-statistics-content">
                                            <h2>
                                                @if (isset($stat['counter']) && $stat['counter'])
                                                    <span class="counter">{{ $stat['value'] }}</span>
                                                @else
                                                    {{ $stat['value'] }}
                                                @endif
                                                {{ $stat['suffix'] ?? '' }}
                                            </h2>
                                            <p>{{ $stat['label'] }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            @else
                {{-- Fallback jika data tidak tersedia --}}
                <div class="col-12">
                    <div class="text-center">
                        <p>Data statistik sedang tidak tersedia.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>
