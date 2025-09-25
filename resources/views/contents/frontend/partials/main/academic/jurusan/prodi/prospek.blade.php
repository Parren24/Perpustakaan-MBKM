<section class="service-entry divider-dark-lg prospect-section">
    <h2 class="wow fadeInUp" data-wow-delay="0.4s" id="prospek-karir">
        @if ($title = $kontenProdi->prospek->title)
            {!! $title !!}
        @else
            <span>Prospek Karir</span>
        @endif
    </h2>

    <p class="wow fadeInUp" data-wow-delay="0.2s">
        @if ($deskripsi = $kontenProdi->prospek->deskripsi)
            {!! $deskripsi !!}
        @else
            <span class="font-italic">- Deskripsi prospek karir belum diatur -</span>
        @endif
    </p>

    <div class="row wow fadeInUp mb-3" data-wow-delay="0.4s">
        @forelse ($kontenProdi->daftar_prospek as $prospek)
            <div class="col-12">
                <div class="prospect-item">
                    <h4>
                        {{-- @if ($prospek->icon)
                            @svg($prospek->icon)
                        @endif --}}
                        <i class="fa-solid fa-rocket"></i>
                        <span>
                            {{ $prospek->prospek_karir }}
                        </span>
                    </h4>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="prospect-item">
                    <h4>
                        <span>
                            Tidak ada data prospek karir yang tersedia.
                        </span>
                    </h4>
                </div>
            </div>
        @endforelse
    </div>
</section>
