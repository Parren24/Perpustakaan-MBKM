@php
    $tentang = $kontenJurusan->tentang ?? null;
@endphp

<section class="about-jurusan-section about-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-5 order-lg-2">
                <figure class="image-anime about-image ">
                    <img src="{{ $tentang->cover }}" alt="About Jurusan" class="reveal">
                </figure>
            </div>
            <div class="col-lg-7 order-lg-1">
                <div class="about-content m-0">
                    <div class="section-title">
                        <h3 class="wow fadeInUp" data-wow-delay="0.1s">
                            Tentang
                        </h3>
                        <h2 class="wow fadeInUp" data-wow-delay="0.3s">
                            @if (isset($tentang->title))
                                {!! $tentang->title !!}
                            @else
                                <span class="font-italic">- Judul tentang belum diatur -</span>
                            @endif
                        </h2>
                        <p class="wow fadeInUp" data-wow-delay="0.5s">
                            @if (isset($tentang->deskripsi))
                                {{ $tentang->deskripsi }}
                            @else
                                <span class="font-italic">- Deskripsi tentang belum diatur -</span>
                            @endif
                        </p>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-6">
                            <div class="wow fadeInUp data-info-item" data-wow-delay="0.75s">
                                <h4>{{ $tentang->jumlah_mahasiswa }}</h4>
                                <div>Mahasiswa Aktif</div>
                            </div>
                        </div>
                        <div class="col-md-4 col-6">
                            <div class="wow fadeInUp data-info-item" data-wow-delay="0.75s">
                                <h4><span class="counter">{{ $countProdi }}</span></h4>
                                <div>Program Studi</div>
                            </div>
                        </div>
                        <div class="col-md-4 col-6">
                            <div class="wow fadeInUp data-info-item" data-wow-delay="0.75s">
                                <h4><span class="counter">{{ count($dosenListGroup) }}</span></h4>
                                <div>Dosen</div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
