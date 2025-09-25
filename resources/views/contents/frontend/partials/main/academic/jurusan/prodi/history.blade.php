<section class="service-entry divider-dark-lg">
    <h2 class="wow fadeInUp" data-wow-delay="0.4s" id="sejarah">
        <span>Sejarah</span>
    </h2>

    <div class="history-content">
        <div class="history-entry">
            @foreach ($kontenProdi->sejarah as $item)
                <h2 class="wow fadeInUp" data-wow-delay="0.4s">
                    @if ($tahun = $item->tahun)
                        {{ $tahun }}
                    @else
                        <span class="font-italic">- Tahun sejarah belum diatur -</span>
                    @endif
                </h2>

                <div class="history-description wow fadeInUp" data-wow-delay="0.4s">
                    <div class="wow-generate">
                        @if ($deskripsi = $item->deskripsi)
                            {!! $deskripsi !!}
                        @else
                            <span class="font-italic">- Keterangan sejarah belum diatur -</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
