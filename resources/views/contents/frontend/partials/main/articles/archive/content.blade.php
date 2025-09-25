<section class="news-content">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 order-lg-0 order-1">
                <div class="mt-3">
                    @include('contents.frontend.partials.main.articles.archive.navigation')
                </div>
            </div>
            <div class="col-lg-9 mt-lg-0 mt-3 order-lg-1 order-0">
                <section class="newest-section">
                    <div class="row newest-grid">
                        @foreach (data_get($content, 'articles') as $index => $article)
                            <div class="col-lg-4 col-md-6 mb-4">
                                <a href="{{ data_get($article, 'url') }}"
                                    class="wow fadeInUp newest-grid-item hoverable-card" data-wow-delay="0.1s"
                                    data-cursor-text="Lihat">
                                    <div class="image-anime newest-grid-image">
                                        <img src="{{ data_get($article, 'images.src') }}"
                                            alt="{{ data_get($article, 'images.alt') }}">
                                    </div>
                                    <div class="newest-grid-content">
                                        <h3 class="newest-grid-title">
                                            {{ data_get($article, 'title') }}
                                        </h3>
                                        <span class="newest-grid-date">{{ data_get($article, 'timestamp') }}</span>
                                    </div>
                                </a>
                            </div>
                            @php $lastDelay = $index * 1.2; @endphp
                        @endforeach

                        <div class="col-12">
                            @include('contents.frontend.partials.main.articles.archive.pagination')
                            {{-- {{ $content['articlesMeta']->onEachSide(1)->links('contents.frontend.partials.main.articles.archive.pagination') }} --}}
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</section>
