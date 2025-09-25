@php
    $sambutan = $kontenJurusan->sambutan ?? null;
@endphp

<section class="sambutan-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-4">
                <figure class="image-anime sambutan-image ">
                    <img src="{{ $sambutan->foto_sambutan }}"
                        alt="Kepala Jurusan {{ $kontenJurusan->jurusan->nama_jurusan ?? '' }}" class="reveal">
                </figure>
            </div>
            <div class="col-lg-8 ml-0 ml-lg-3">
                <div class="section-title">
                    <h3 class="wow fadeInOut" data-wow-delay="0.1s">Kata Sambutan</h3>
                    <h2 class="wow fadeInOut" data-wow-delay="0.2s">
                        @if (isset($sambutan->title))
                            {!! $sambutan->title !!}
                        @else
                            <span class="font-italic">- Judul sambutan belum diatur -</span>
                        @endif
                    </h2>
                </div>
                <div class="sambutan-content">
                    <p class="wow fadeInOut" data-wow-delay="0.3s">
                        @if (isset($sambutan->sambutan))
                            {{ $sambutan->sambutan }}
                        @else
                            <span class="font-italic">- Sambutan belum diatur -</span>
                        @endif
                    </p>
                    <div class="sambutan-author wow fadeInOut" data-wow-delay="0.4s">
                        @if (isset($sambutan->pemberi_sambutan))
                            {{ $sambutan->pemberi_sambutan }}
                            {{ isset($sambutan->jabatan_sambutan) ? ', ' . $sambutan->jabatan_sambutan : '' }}
                        @else
                            <span class="font-italic">- Pemberi Sambutan belum diatur -</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
