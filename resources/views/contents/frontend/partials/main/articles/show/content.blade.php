<section class="news-content">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 order-lg-0 order-1">
                <div class="mt-3">
                    @include('contents.frontend.partials.main.articles.show.categories')
                    @include('contents.frontend.partials.main.articles.show.latest')
                </div>
            </div>
            <div class="col-lg-9 mt-lg-0 mt-3 order-lg-1 order-0">
                <div class="ps-lg-3 ps-0">
                    <div class="mt-3 news-content-text">
                        {!! data_get($content, 'content.body') !!}
                    </div>

                    <div class="news-category-data">
                        <div class="news-category-meta">
                            <span class="news-category-date">
                                <i class="fas fa-calendar-alt data-icon"></i>
                                {{ data_get($content, 'content.timestamp') }}
                            </span>
                            <span class="news-category-author">
                                <i class="fas fa-user data-icon"></i>
                                {{ data_get($content, 'content.author') }}
                            </span>
                        </div>

                        <div class="news-category-badges">
                            @if ($labels = data_get($content, 'content.labels'))
                                @foreach ($labels as $label)
                                    <span><a
                                            href="{{ data_get($label, 'url') }}">{{ data_get($label, 'label') }}</a></span>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
