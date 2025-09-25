@php
    $prodiJurusan = $kontenJurusan->prodi_jurusan ?? null;
@endphp

<section class="page-jurusan-image-gallery jurusan-gallery-section">
    <div class="container">
        {{-- <div class="row">
            <div class="col-12">
                <div class="section-title">
                    <h3 class="wow fadeInUp">
                        {{ $kontenJurusan->jurusan->nama_jurusan ?? 'Detail Jurusan' }}
                    </h3>
                    <h2 class="wow fadeInUp " data-wow-delay="0.25s">
                        @if (isset($prodiJurusan->title))
                            {{ $prodiJurusan->title }}
                        @else
                            <span class="font-italic">- Judul belum diatur -</span>
                        @endif
                    </h2>
                    <p class="wow fadeInUp" data-wow-delay="0.5s">
                        @if (isset($prodiJurusan->deskripsi))
                            {{ $prodiJurusan->deskripsi }}
                        @else
                            <span class="font-italic">- Deskripsi belum diatur -</span>
                        @endif
                    </p>
                </div>
            </div>
        </div> --}}
        @forelse ($prodiListGroup ?? [] as $jenjangPendidikan => $prodiList)
            @php
                $isLast = $loop->last;
            @endphp
            <div class="row {{ !$isLast ? 'divider-dark-lg' : '' }}">
                <div class="col-12 my-5">
                    <div class="section-title mb-0">
                        <h2 class="wow fadeInUp">
                            {{ $jenjangPendidikan }}
                        </h2>
                    </div>
                </div>

                @forelse ($prodiList as $prodi)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <a href="{{ route('frontend.academic.prodi.show', [
                            'prodiAlias' => Str::lower($prodi->alias_prodi),
                        ]) }}"
                            data-cursor-text="Lihat" class="jurusan-item hoverable-card wow fadeInUp"
                            data-wow-delay="0.4s">
                            <div class="jurusan-image">
                                <figure class="image-anime">
                                    <img src="{{ publicMedia($prodi->filemedia_prodi, 'jurusan') }}"
                                        alt="{{ $prodi->nama_prodi }}">
                                </figure>
                            </div>
                            <div class="jurusan-content">
                                <h3>
                                    {{ $prodi->nama_prodi }}
                                </h3>
                                <p>
                                    @if ($prodi->deskripsi_prodi)
                                        {{ $prodi->deskripsi_prodi }}
                                    @else
                                        <span class="font-italic">- Deskripsi program studi belum diatur -</span>
                                    @endif
                                </p>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-lg-12">
                        <div class="alert alert-warning text-center">
                            <strong>Data program studi tidak ditemukan.</strong>
                        </div>
                    </div>
                @endforelse
            </div>
        @empty
            <div class="row">
                <div class="col-lg-12">
                    <div class="alert alert-warning text-center">
                        <strong>Data tidak ditemukan.</strong>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
</section>
