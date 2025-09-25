@php
    $researches = data_get($content, 'research-activity');
@endphp
<section class="achievement-section research-activity-section">
    <div class="container">
        <div class="col-12">
            <div class="section-title">
                <h2 class="wow fadeInUp" data-wow-delay="0.1s">
                    <span>Kegiatan Penelitian</span>
                </h2>
            </div>
        </div>
        <div class="col-12">
            <div class="achievement-grid">
                @foreach ($researches as $index => $research)
                    @if ($index === 0)
                        <a href="{{ data_get($research, 'url') }}"
                            class="achievement-grid-main-item hoverable-card wow fadeInUp" data-wow-delay="0.1s"
                            data-cursor-text="Lihat">
                            <div class="main-item-img image-anime">
                                <img src="{{ data_get($research, 'images.src') }}"
                                    alt="{{ data_get($research, 'images.alt') }}">

                                <div class="main-item-img-overlay"></div>
                            </div>

                            <div class="achievement-grid-content">
                                <h2 class="achievement-grid-title">
                                    {{ data_get($research, 'title') }}
                                </h2>
                                <span class="achievement-grid-date">{{ data_get($research, 'timestamp') }}</span>
                            </div>
                        </a>
                    @else
                        @php
                            $gridArea = match ($index) {
                                1 => 'tl',
                                2 => 'tr',
                                3 => 'bl',
                                4 => 'br',
                                default => 'tl',
                            };
                        @endphp

                        <a href="{{ data_get($research, 'url') }}" data-cursor-text="Lihat"
                            class="achievement-grid-item hoverable-card wow fadeInUp"
                            style="grid-area: {{ $gridArea }};" data-wow-delay="{{ $index * 0.1 + 0.2 }}s">
                            <div class="item-img image-anime">
                                <img src="{{ data_get($research, 'images.src') }}"
                                    alt="{{ data_get($research, 'images.alt') }}">
                            </div>
                            <h3 class="achievement-grid-title">
                                {{ data_get($research, 'title') }}
                            </h3>
                            <span class="achievement-grid-date">{{ data_get($research, 'timestamp') }}</span>
                        </a>
                    @endif
                @endforeach

            </div>
        </div>
    </div>
</section>
