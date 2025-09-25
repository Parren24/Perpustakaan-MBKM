<section class="visi-misi-section service-entry divider-dark-lg">
    <div class="about-us-content">
        <div class="section-title">
            <h3 class="wow fadeInUp">
                Visi Misi
            </h3>
            <h2 class="wow fadeInUp" data-wow-delay="0.2s" id="visi-misi">
                <span>Visi</span>
            </h2>
        </div>

        <div class="about-us-list wow fadeInUp" data-wow-delay="0.6s">
            @if ($visi = $kontenProdi->visi->visi)
                {!! $visi !!}
            @else
                <span class="font-italic">- Visi program studi belum diatur -</span>
            @endif
        </div>
    </div>

    <div class="our-service bg-section misi-section">
        <div class="section-title">
            <h2 class="wow fadeInUp" data-wow-delay="0.25s">
                <span>Misi</span>
            </h2>
        </div>

        <div class="service-item-list">
            @forelse ($kontenProdi->misi  as $misi)
                <div class="service-item wow fadeInUp">
                    <div class="icon-box">
                        <i class="fa-solid fa-lightbulb"></i>
                        {{-- @if ($misiIcon = $misi->icon)
                            @svg($misiIcon)
                        @else
                            <i class="fa-solid fa-check"></i>
                        @endif --}}
                    </div>

                    <div class="service-item-content">
                        <p>
                            @if ($misiText = $misi->misi)
                                {{ $misiText }}
                            @else
                                <span class="font-italic">- Misi program studi belum diatur -</span>
                            @endif
                        </p>
                    </div>
                </div>
            @empty
                <div class="service-item wow fadeInUp">
                    <div class="icon-box">
                        <i class="fa-solid fa-close"></i>
                    </div>

                    <div class="service-item-content">
                        <p>
                            <span class="font-italic">- Misi program studi belum diatur -</span>
                        </p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</section>
