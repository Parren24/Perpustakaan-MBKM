<section class="page-diversity-description mt-0">
    <div class="container">
        <div class="row">
            <div class="col-12">
                {{-- Section Title Start --}}
                <div class="section-title">
                    <h3 class="wow fadeInUp">
                        {{ data_get($content, 'subtitle') }}
                    </h3>

                    <h2 class="wow fadeInUp" data-wow-delay="0.25s">
                        {!! data_get($content, 'title') !!}
                    </h2>
                </div>
                <div class="diversity-description-content wow fadeInUp" data-wow-delay="0.5s">
                    @php
                        $desc = data_get($content, 'description', []);
                    @endphp

                    @if (is_array($desc) && count($desc) > 0)
                        @foreach ($desc as $paragraph)
                            <p>{{ (string) $paragraph }}</p>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
