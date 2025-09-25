<div class="service-entry divider-dark-lg">
    <h2 class="wow fadeInUp" id="{{ data_get($item, 'id') }}">
        @if (Str::contains(data_get($item, 'title'), ' '))
            @php
                $words = explode(' ', data_get($item, 'title'));
                $lastWord = array_pop($words);
            @endphp
            <span>{{ implode(' ', $words) }}</span> {{ $lastWord }}
        @else
            <span>{{ data_get($item, 'title') }}</span>
        @endif
    </h2>

    @if (data_get($item, 'description'))
        @foreach (data_get($item, 'description') as $paragraph)
            <p class="wow fadeInUp">
                {!! $paragraph !!}
            </p>
        @endforeach
    @endif

    @if (data_get($item, 'images'))
        @foreach (data_get($item, 'images') as $image)
            <img src="{{ data_get($image, 'src') }}" alt="{{ data_get($image, 'alt') }}" class="wow fadeInUp {{ data_get($image, 'class', '') }}">
        @endforeach
    @endif

    @if (data_get($item, 'video'))
        <div class="service-entry-video">
            <video autoplay muted loop>
                <source src="{{ data_get($item, 'video') }}" type="video/mp4">
            </video>
        </div>
    @endif

    @if (data_get($item, 'links'))
        <div class="our-potential-btn wow fadeInUp" data-wow-delay="0.75s">
            @foreach (data_get($item, 'links') as $link)
                <a class="{{ data_get($link, 'class', 'btn-default') }}" href="{{ data_get($link, 'url', '#') }}" @if (data_get($link, 'target')) target="{{ data_get($link, '_blank') }}" @endif>
                    {{ data_get($link, 'text', 'Button') }}
                </a>
            @endforeach
        </div>
    @endif
</div>
