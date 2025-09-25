<section class="page-jurusan-image-gallery jurusan-gallery-section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-title">
                    <h3 class="wow fadeInUp">
                        {{ data_get($content, 'subtitle') }}
                    </h3>
                    <h2 class="wow fadeInUp" data-wow-delay="0.25s">
                        @foreach (smartSplit(data_get($content, 'title'), 50, [',']) as $splitTitle)
                            {!! $splitTitle !!}
                            @if (!$loop->last)
                                <br>
                            @endif
                        @endforeach
                    </h2>
                </div>
            </div>
            @forelse (data_get($content,'jurusan') as $jurusan)
                <div class="col-lg-4 col-md-6">
                    <a href="{{ data_get($jurusan, 'url') }}" data-cursor-text="Lihat"
                        class="jurusan-item wow fadeInUp hoverable-card" data-wow-delay="0.4s">
                        <figure class="image-anime">
                            <img src="{{ data_get($jurusan, 'images.src') }}"
                                alt="{{ data_get($jurusan, 'images.alt') }}">
                        </figure>
                        <div class="jurusan-content">
                            <h3>
                                {{ data_get($jurusan, 'title') }}
                            </h3>
                            <p>
                                @if (data_get($jurusan, 'description'))
                                    {{ data_get($jurusan, 'description') }}
                                @else
                                    <span class="font-italic">- Deskripsi jurusan belum diatur -</span>
                                @endif
                            </p>
                        </div>
                    </a>
                </div>
            @empty
                <div class="row">
                    <div class="col-lg-12">
                        <div class="alert alert-warning">
                            <strong>Data tidak ditemukan.</strong>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</section>
