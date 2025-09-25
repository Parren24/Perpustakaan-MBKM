<footer class="main-footer bg-section">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="about-footer">
                    <div class="section-title">
                        <h2>
                            {!! data_get($siteIdentity, 'identity.tagline', 'Politeknik Caltex Riau') !!}
                        </h2>
                    </div>
                </div>
                <div class="footer-social-links">
                    <ul>
                        @foreach (data_get($siteIdentity, 'social_media', []) as $social)
                            <li>
                                <a href="{{ $social['url'] }}" target="_blank" title="{{ $social['name'] }}">
                                    <i class="{{ $social['icon'] }}"></i>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <a href="{{ data_get($siteIdentity, 'contact.address.maps_url', '#') }}" target="_blank" class="footer-location-text">
                    <i class="fa-solid fa-location-dot"></i>
                    <span>{{ data_get($siteIdentity, 'contact.address.full', 'Alamat tidak tersedia') }}</span>
                </a>

                <div class="footer-contact">
                    <div class="footer-contact-info">
                        <i class="fa-solid fa-phone"></i>
                        <span>{{ data_get($siteIdentity, 'contact.phone.main', '-') }}</span>
                    </div>
                    <span>atau</span>
                    <div class="footer-contact-info">
                        <i class="fa-solid fa-mobile"></i>
                        <span>{{ data_get($siteIdentity, 'contact.phone.mobile', '-') }}</span>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mt-4 mt-md-0">
                <div class="footer-menu">
                    <h3>Layanan</h3>
                    <ul>
                        @foreach (data_get($siteIdentity, 'menus.services', []) as $service)
                            <li>
                                <a href="{{ $service['url'] }}" @if($service['external']) target="_blank" @endif>
                                    {{ $service['title'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="col-md-3 mt-4 mt-md-0">
                <div class="footer-menu">
                    <h3>Akademik</h3>
                    <ul>
                        @foreach (data_get($siteIdentity, 'menus.academic', []) as $academic)
                            <li>
                                <a href="{{ $academic['url'] }}" @if($academic['external']) target="_blank" @endif>
                                    {{ $academic['title'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <p class="footer-copyright-text">
        {!! data_get($siteIdentity, 'copyright.full_text', '© Politeknik Caltex Riau') !!}
    </p>
</footer>
