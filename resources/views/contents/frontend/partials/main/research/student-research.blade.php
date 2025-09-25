<section class=" research-activity-section">
    <div class="container">
        <div class="col-12">
            <div class="section-title">
                <h2 class="wow fadeInUp" data-wow-delay="0.1s">
                    Penelitian <span>Mahasiswa</span>
                </h2>
            </div>
        </div>
        <div class="col-12">
            <div class="card-grid-gallery">
                @for ($index = 0; $index < 5; $index++)
                    @if ($index === 0)
                        <a href="#" class="card-grid-gallery-main-item hoverable-card wow fadeInUp" data-wow-delay="0.1s"
                            data-cursor-text="Lihat">
                            <div class="main-item-img image-anime">
                                <img src="{{ asset('theme/frontend/images/examples/berita-1.png') }}" alt="Berita">

                                <div class="main-item-img-overlay"></div>
                            </div>

                            <div class="card-grid-gallery-content">
                                <h2 class="card-grid-gallery-title">
                                    2 Dosen PCR Raih 3 Penghargaan Pada Program VALERIA
                                </h2>
                                <span class="card-grid-gallery-date">{{ now()->addDays(-8)->diffForHumans() }}</span>
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

                        <a href="#" data-cursor-text="Lihat" class="card-grid-gallery-item hoverable-card wow fadeInUp"
                            style="grid-area: {{ $gridArea }};" data-wow-delay="{{ $index * 0.1 + 0.2 }}s">
                            <div class="item-img image-anime">
                                <img src="{{ asset('theme/frontend/images/examples/berita-1.png') }}" alt="Berita">
                            </div>
                            <h3 class="card-grid-gallery-title">
                                3 Mahasiswa PCR Berhasil Raih Medali di Kejuaraan Pencak Silat Internasional
                            </h3>
                            <span class="card-grid-gallery-date">{{ now()->addDays(-8)->diffForHumans() }}</span>
                        </a>
                    @endif
                @endfor

            </div>
        </div>
    </div>
</section>
