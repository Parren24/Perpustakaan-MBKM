@props(['entry'])

<h2 class="wow fadeInUp" data-wow-delay="0.4s" id="{{ data_get($entry, 'id') }}">
    {{ data_get($entry, 'title') }}
    @if (data_get($entry, 'year'))
        <br><span class="history-year">({{ data_get($entry, 'year') }})</span>
    @endif
</h2>

<div class="history-description wow fadeInUp mb-5" data-wow-delay="0.4s">
    @foreach (data_get($entry, 'content', []) as $paragraph)
        <p class="wow fadeInUp" data-wow-delay="0.2s">
            {{ $paragraph }}
        </p>
    @endforeach

    @if (!empty(data_get($entry, 'images')))
        @foreach (data_get($entry, 'images') as $image)
            <div class="history-entry-img">
                <figure class="image-anime reveal">
                    <img src="{{ asset(data_get($image, 'src')) }}" alt="{{ data_get($image, 'alt') }}">
                </figure>
            </div>
        @endforeach
    @endif
</div>
