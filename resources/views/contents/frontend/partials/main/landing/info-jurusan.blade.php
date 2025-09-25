<section class="our-service bg-section jurusan-section">
    <div class="container z-2 position-relative">
        <div class="row">
            <div class="col-lg-5">
                <div class="service-content">
                    <div class="section-title">
                        <h3 class="wow fadeInUp">
                            {{ data_get($jurusanData, 'subtitle') }}
                        </h3>
                        <h2 class="wow fadeInUp" data-wow-delay="0.25s">
                            {!! data_get($jurusanData, 'title') !!}
                        </h2>
                        <p class="wow fadeInUp" data-wow-delay="0.5s">
                            {!! data_get($jurusanData, 'description') !!}
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="service-item-list">
                    @forelse (data_get($jurusanData, 'list', []) as $jurusan)
                        <div class="service-item wow fadeInUp">
                            <div class="service-item-container">
                                <div class="icon-box text-white">
                                    <i class="{{ $jurusan->icon }}"></i>
                                </div>

                                <div class="service-item-content">
                                    <h3>
                                        {{ $jurusan->nama_jurusan }}
                                    </h3>
                                    <p>
                                        {{ $jurusan->deskripsi_jurusan ?? 'Deskripsi jurusan tidak tersedia.' }}
                                    </p>

                                    <div class="service-btn mt-3">
                                        <a href="{{ route('frontend.academic.jurusan.show', [
                                            'jurusanAlias' => Str::lower($jurusan->alias_jurusan),
                                        ]) }}"
                                           class="btn-default btn-highlighted btn-sm">
                                            Lihat Program Studi {{ $jurusan->alias_jurusan }}
                                        </a>
                                    </div>

                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="service-item wow fadeInUp">
                            <div class="service-item-container">
                                <div class="icon-box text-white">
                                    <i class="fa-solid fa-graduation-cap"></i>
                                </div>

                                <div class="service-item-content">
                                    <h3>
                                        Jurusan Tidak Tersedia
                                    </h3>
                                    <p>
                                        Informasi jurusan sedang tidak tersedia saat ini.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>
                {{-- <div class="service-footer wow fadeInUp" data-wow-delay="0.25s">
                    <p>
                        <span>
                            "Empowers You to Global Competition"
                        </span>
                    </p>
                </div> --}}
            </div>
        </div>
    </div>
</section>
