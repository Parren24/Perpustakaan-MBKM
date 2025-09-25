<section class="intro-video bg-section parallaxie virtual-tour-section"
         style="background-image: url('{{ data_get($virtualTourData, 'background.image.src') }}');">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 col-md-8">
                <div class="section-title wow fadeInUp">
                    <h2>
                        {!! data_get($virtualTourData, 'title') !!}
                    </h2>
                </div>
            </div>

            <div class="col-lg-6 col-md-4">
                <div class="intro-video-box">
                    <div class="video-play-button">
                        <a href="{{ data_get($virtualTourData, 'action.url') }}" class="{{ data_get($virtualTourData, 'action.class') }}" data-cursor-text="{{ data_get($virtualTourData, 'action.cursor_text') }}">
                            <i class="{{ data_get($virtualTourData, 'action.icon') }}"></i>
                        </a>
                        <p>
                            {{ data_get($virtualTourData, 'action.button_text') }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-lg-12">
                <div class="intro-video-list wow fadeInUp" data-wow-delay="0.25s">
                    <ul>
                        @foreach (data_get($virtualTourData, 'features') as $feature)
                            <li>{{ $feature }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
