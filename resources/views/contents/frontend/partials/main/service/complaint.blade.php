<section class="complaint-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="section-title">
                    <h3 class="wow fadeInUp">
                        Pengaduan
                    </h3>
                    <h2 class="wow fadeInUp" data-wow-delay="0.25s">
                        Layanan <span>Pengaduan</span>
                    </h2>
                    <p class="wow fadeInUp" data-wow-delay="0.5s">
                        Ada masalah atau keluhan terkait layanan kampus? Kami siap membantu Anda. Silakan ajukan
                        pengaduan Anda melalui formulir berikut. Tim kami akan segera menindaklanjuti.
                    </p>
                </div>
            </div>
            <div class="col-md-6 mt-3 mt-md-0">
                <div class="contact-us-form">
                    <!-- Contact Us Title Start -->
                    {{-- <div class="contact-us-title">
                        <h3 class="wow fadeInUp">
                            Kirim pesan Anda
                        </h3>
                        <p class="wow fadeInUp" data-wow-delay="0.2s">
                            Kami siap membantu Anda dengan pertanyaan, saran, atau permintaan informasi lebih lanjut.
                            Silakan
                            isi formulir di bawah ini, dan tim kami akan segera menghubungi Anda.
                        </p>
                    </div> --}}
                    <!-- Contact Us Title End -->

                    <!-- Contact Us Form Start -->
                    <form id="contactForm" action="#" method="POST" data-toggle="validator" class="wow fadeInUp"
                        data-wow-delay="0.4s">
                        <div class="row">
                            <div class="form-group col-md-12 mb-4">
                                <input type="text" name="name" class="form-control" id="name"
                                    placeholder="Nama" required>
                                <div class="help-block with-errors"></div>
                            </div>

                            <div class="form-group col-md-12 mb-4">
                                <input type="email" name="email" class="form-control" id="email"
                                    placeholder="Email" required>
                                <div class="help-block with-errors"></div>
                            </div>

                            <div class="form-group col-md-12 mb-5">
                                <textarea name="message" class="form-control" id="message" rows="4" placeholder="Pesan"></textarea>
                                <div class="help-block with-errors"></div>
                            </div>

                            <div class="col-lg-12">
                                <div class="contact-form-btn">
                                    <button type="submit" class="btn-default">Kirim Pesan</button>
                                    <div id="msgSubmit" class="h3 hidden"></div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!-- Contact Us Form End -->
                </div>
            </div>
        </div>
    </div>
</section>
