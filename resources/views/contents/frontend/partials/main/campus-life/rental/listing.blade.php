<section class="rental-listing-section">
    <div class="container">
        <div class="row">
            @for ($i = 0; $i < 7; $i++)
                <div class="col-md-6">
                    <div class="rental-item-wrapper">
                        <a class="rental-item wow fadeInUp hoverable-card" data-wow-delay="{{ $i * 0.1 }}s"
                            href="#">
                            <figure class="rental-image image-anime">
                                <img src="{{ asset('theme/frontend/images/placeholders/16x9.png') }}" alt="Rental Image">
                            </figure>
                            <div class="rental-content">
                                <div class="rental-heading">
                                    <div class="rental-title">
                                        <h4>Kos Merah</h4>
                                        <p>(Kos Campur)</p>
                                    </div>
                                    <span class="rental-type">
                                        Kos
                                    </span>
                                </div>
                                <div class="rental-info">
                                    <div class="row">
                                        <div class="col-lg-9">
                                            <h5>Alamat</h5>
                                            <p>Jl. Intisari No.6A, Umban Sari, Kec. Rumbai, Kota Pekanbaru, Riau 28266
                                            </p>
                                        </div>
                                        <div class="col-lg-3 mt-3 mt-lg-0">
                                            <h5>Kontak</h5>
                                            <p>08123456789</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="rental-data">
                                    <div class="row mb-3">
                                        <div class="col-6">
                                            <h5>Lokasi</h5>
                                            <p>5 Menit dari Kampus</p>
                                            <span>Lokasi Strategis</span>
                                        </div>
                                        <div class="col-6">
                                            <h5>Harga</h5>
                                            <p>Rp. 400.000 - Rp. 1.000.000</p>
                                            <span>Tanpa biaya tambahan</span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <h5>Fasilitas</h5>
                                            <ul class="mb-0">
                                                <li>Tempat Tidur</li>
                                                <li>Kamar Mandi Dalam</li>
                                                <li>Parkiran Luas</li>
                                                <li>Include Wi-Fi</li>
                                            </ul>
                                        </div>
                                        <div class="col-6">
                                            <h5>Keamanan</h5>
                                            <p>CCTV 24 Jam</p>
                                            <span>Ada Penjaga Kos</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            @endfor
        </div>
    </div>
</section>
