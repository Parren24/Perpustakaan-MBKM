<div class="rekan-slider-section">
    <div class="row">
        <div class="col-12">
            <div class="swiper rekan-slider-1">
                {{-- Additional required wrapper --}}
                <div class="swiper-wrapper">
                    {{-- Slides --}}
                    @for ($i = 0; $i < 3; $i++)
                        @foreach (data_get($partners, 'institutions', []) as $j => $item)
                            <div class="swiper-slide" id="{{ data_get($item, 'id') }}-institutions-{{ $i }}">
                                <div class="rekan-slider-item">
                                    <div class="d-flex justify-content-center align-items-center {{ data_get($image_config, 'class') }}">
                                        <img src="{{ data_get($item, 'image.src') }}" alt="{{ str_replace('{name}', data_get($item, 'name'), data_get($image_config, 'alt_template')) }}"
                                             class="img-max-60">
                                    </div>
                                    <span class="rekan-slider-name">
                                        {{ data_get($item, 'name') }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    @endfor
                </div>
            </div>
        </div>
        <div class="col-12 mt-4">
            <div class="swiper rekan-slider-2">
                {{-- Additional required wrapper --}}
                <div class="swiper-wrapper">
                    {{-- Slides --}}
                    @for ($i = 0; $i < 3; $i++)
                        @foreach (data_get($partners, 'instances', []) as $j => $item)
                            <div class="swiper-slide" id="{{ data_get($item, 'id') }}-instances-{{ $i }}">
                                <div class="rekan-slider-item">
                                    <div class="d-flex justify-content-center align-items-center {{ data_get($image_config, 'class') }}">
                                        <img src="{{ data_get($item, 'image.src') }}" alt="{{ str_replace('{name}', data_get($item, 'name'), data_get($image_config, 'alt_template')) }}"
                                             class="img-max-60">
                                    </div>
                                    <span class="rekan-slider-name">
                                        {{ data_get($item, 'name') }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    @endfor
                </div>
            </div>
        </div>
        <div class="col-12 mt-4">
            <div class="swiper rekan-slider-3">
                <div class="swiper-wrapper">
                    @for ($i = 0; $i < 3; $i++)
                        @foreach (data_get($partners, 'industries', []) as $j => $item)
                            <div class="swiper-slide" id="{{ data_get($item, 'id') }}-industries-{{ $i }}">
                                <div class="rekan-slider-item">
                                    <div class="d-flex justify-content-center align-items-center {{ data_get($image_config, 'class') }}">
                                        <img src="{{ data_get($item, 'image.src') }}" alt="{{ str_replace('{name}', data_get($item, 'name'), data_get($image_config, 'alt_template')) }}"
                                             class="img-max-60">
                                    </div>
                                    <span class="rekan-slider-name">
                                        {{ data_get($item, 'name') }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    @endfor
                </div>
            </div>
        </div>
    </div>
</div>

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const baseConfig = {!! $swiper_config_json !!};

            const createOptions = (reverse = false) => ({
                ...baseConfig,
                autoplay: {
                    ...baseConfig.autoplay,
                    reverseDirection: reverse,
                },
                speed: Math.floor(Math.random() * 500) + baseConfig.speed_range[0],
            });

            const swiper1 = new Swiper('.rekan-slider-1', createOptions());
            const swiper2 = new Swiper('.rekan-slider-2', createOptions());
            const swiper3 = new Swiper('.rekan-slider-3', createOptions());
        })
    </script>
@endpush
