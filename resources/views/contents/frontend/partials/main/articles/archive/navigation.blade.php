<section class="news-categories divider-dark-lg">
    <h3 class="news-side-title wow fadeInUp" data-wow-delay="0.1s">
        Kategori Lainnya
    </h3>

    <div class="news-category-container">
        @foreach (data_get($content, 'navCategories') as $index => $category)
            <a href="{{ data_get($category, 'url') }}" class="news-category-item wow fadeInUp"
                data-wow-delay="0.{{ $index + 1 }}s">
                {{ data_get($category, 'title') }}
            </a>
        @endforeach
    </div>
</section>
<section class="latest-news-section">
    <h3 class="news-side-title wow fadeInUp" data-wow-delay="0.1s">
        Label Lainnya
    </h3>

    <div class="news-category-badges">
        @if ($labels = data_get($content, 'navLabels'))
            @foreach ($labels as $label)
                <span><a href="{{ data_get($label, 'url') }}">{{ data_get($label, 'title') }}</a></span>
            @endforeach
        @endif
    </div>
</section>
