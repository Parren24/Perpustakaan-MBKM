<div class="location-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-5">
                <div class="map-container wow fadeInUp" data-wow-delay="0.25s">
                    @php
                        $embed = data_get($content ?? null, 'map_embed_url', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1488.38854983942!2d101.42593720683222!3d0.570365708891641!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31d5ab67086f2e89%3A0x65a24264fec306bb!2sPoliteknik%20Caltex%20Riau!5e0!3m2!1sen!2sid!4v1748221520927!5m2!1sen!2sid');
                    @endphp

                    <iframe style="width: 100%; height: 100%;"
                            src="{{ $embed }}"
                            width="1920" height="1080" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="section-title map-title">
                    <h3 class="wow fadeInUp">
                        {{ data_get($content, 'subtitle') }}
                    </h3>
                    <h2 class="wow fadeInUp" data-wow-delay="0.25s">
                        {!! data_get($content, 'title') !!}
                    </h2>

                    <div class="location-detail wow fadeInUp" data-wow-delay="0.25s">
                        <i class="fa-solid fa-location-dot"></i>
                        <span>
                            {{ data_get($content ?? null, 'address', 'Jln. Umbansari No. 1, Umban Sari, Kec. Rumbai, Kota Pekanbaru, Riau 28265') }}
                        </span>
                    </div>

                    @php $descs = data_get($content ?? null, 'description', []); @endphp
                    @if (is_array($descs) && count($descs) > 0)
                        @foreach ($descs as $d)
                            <p class="wow fadeInUp" data-wow-delay="0.5s">{{ $d }}</p>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
