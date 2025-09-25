<section class="announcement-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="section-title">
                    <h2 class="wow fadeInUp" data-wow-delay="0.2s">
                        Pengumuman
                    </h2>
                </div>

                <div class="announcement-wrapper">
                    @forelse ($announcements as $i => $announcement)
                        <div class="announcement-item wow fadeInUp" data-wow-delay="{{ $i * 0.1 }}s">
                            <a href="{{ route('frontend.articles.show', ['articlesSlug' => $announcement->slug_post]) }}">
                                <h3>
                                    <span>{{ $announcement->judul_post }}</span>
                                </h3>
                            </a>
                            <small>{{ tanggal($announcement->tanggal_post) }}</small>
                        </div>
                    @empty
                        <div class="announcement-item wow fadeInUp" data-wow-delay="0.2s">
                            <h3>Tidak ada pengumuman saat ini</h3>
                            <small>Belum ada pengumuman yang dibuat.</small>
                        </div>
                    @endforelse
                </div>
            </div>
            <div class="col-lg-6">
                <div class="section-title">
                    <h2 class="wow fadeInUp" data-wow-delay="0.2s">
                        Agenda
                    </h2>
                </div>

                <div class="announcement-wrapper">
                    @forelse ($agendas as $i => $agenda)
                        <div class="announcement-item wow fadeInUp" data-wow-delay="{{ $i * 0.1 }}s">
                            <h3>{{ $agenda->nama_agenda }}</h3>
                            <small>
                                {{ tanggal($agenda->tanggal_start_agenda) . ($agenda->tanggal_end_agenda ? ' s/d ' . tanggal($agenda->tanggal_end_agenda) : '') }}
                            </small>
                        </div>
                    @empty
                        <div class="announcement-item wow fadeInUp" data-wow-delay="0.2s">
                            <h3>Tidak ada agenda saat ini</h3>
                            <small>Belum ada agenda yang dijadwalkan.</small>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>
