<section class="service-entry divider-dark-lg">
    <h2 class="wow fadeInUp" id="tentang-program-studi">
        Program Studi <span>{{ $prodi->nama_prodi }}</span>
    </h2>

    <div class="wow-generate">
        @if ($deskripsi= $kontenProdi->tentang->deskripsi)
            {!! $deskripsi !!}
        @else
            <span class="font-italic">- Deskripsi tentang program studi belum diatur -</span>
        @endif
    </div>

</section>
