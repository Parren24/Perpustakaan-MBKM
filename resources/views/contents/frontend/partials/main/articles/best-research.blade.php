@php
    $bestResearches = data_get($content, 'best-research');
@endphp
<section class="research-section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-title">
                    <h2 class="wow fadeInUp" data-wow-delay="0.1s">
                        Riset <span>Unggulan</span>
                    </h2>
                </div>
            </div>
        </div>
        <div class="row small-grid-card-gallery">
            @foreach ($bestResearches as $index => $bestResearch)
                <div class="col-lg-4 col-md-6">
                    <a href="{{ data_get($bestResearch, 'url') }}"
                        class="small-grid-card-item hoverable-card hoverable-card-sm wow fadeInUp"
                        data-cursor-text="Lihat" data-wow-delay="{{ $index * 0.1 + 0.2 }}s">
                        <div class="small-grid-card-image image-anime">
                            <img src="{{ data_get($bestResearch, 'images.src') }}"
                                alt="{{ data_get($bestResearch, 'images.alt') }}">
                        </div>
                        <div class="small-grid-card-content">
                            <h3 class="small-grid-card-title">
                                {{ data_get($bestResearch, 'title') }}
                            </h3>
                            <span class="small-grid-card-date">{{ data_get($bestResearch, 'timestamp') }}</span>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>
