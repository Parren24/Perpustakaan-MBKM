<section class="page-diversity-image-gallery">
    <div class="container">
        <div class="row">
            @php
                $gallery = data_get($content, 'deiversity', []);
            @endphp

            @forelse($gallery as $idx => $item)
                @php
                    $delay = $idx > 0 ? $idx * 0.2 . 's' : null;
                    $src = data_get($item, 'image.src');
                @endphp

                <div class="col-lg-4 col-md-6">
                    {{-- Team Member Item Start --}}
                    <div class="team-member-item wow fadeInUp" @if ($delay) data-wow-delay="{{ $delay }}" @endif>
                        {{-- Team Image Start --}}
                        <div class="team-image">
                            <figure class="image-anime">
                                <img
                                     src="{{ data_get($item, 'image.src') }}"
                                     alt="{{ data_get($item, 'image.alt') }}">
                            </figure>
                        </div>
                        {{-- Team Image End --}}

                        {{-- Team Content Start --}}
                        <div class="team-content">
                            <h3>{{ data_get($item, 'title') }}</h3>
                            <p>{{ data_get($item, 'description') }}</p>
                        </div>
                        {{-- Team Content End --}}
                    </div>
                    {{-- Team Member Item End --}}
                </div>
            @empty
            @endforelse

        </div>
    </div>
</section>
