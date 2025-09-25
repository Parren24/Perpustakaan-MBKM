<a href="{{ data_get($item, 'url', '#') }}" class="wow fadeInUp achievements-grid-item hoverable-card" data-wow-delay="0.2s"
   data-cursor-text="Lihat">
    <div class="achievements-grid-image image-anime">
        <img src="{{ data_get($item, 'image.src') }}" alt="{{ data_get($item, 'image.alt') }}">
    </div>
    <div class="achievements-grid-content">
        <h3>{{ data_get($item, 'title') }}</h3>
        <div class="achievements-grid-date">
            {{ time_history(data_get($item, 'date')) }}
        </div>
    </div>
</a>
