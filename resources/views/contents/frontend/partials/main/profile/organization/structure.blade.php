<section class="page-single-post organization-structure p-0">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                {{-- Post Featured Image Start --}}
                <figure class="image-anime reveal mb-5">
                    <img src="{{ data_get($content, 'image.src') }}" alt="{{ data_get($content, 'image.alt') }}">
                </figure>
                {{-- Post Featured Image Start --}}

                {{-- Post Single Content Start --}}
                <div class="post-content mt-5">
                    {{-- Post Entry Start --}}
                    <div class="post-entry mb-0">
                        @foreach (data_get($content, 'description', []) as $paragraph)
                            <p class="wow fadeInUp text-center">{{ $paragraph }}</p>
                        @endforeach
                    </div>
                    {{-- Post Entry End --}}
                </div>
                {{-- Post Single Content End --}}
            </div>
        </div>
    </div>
</section>
