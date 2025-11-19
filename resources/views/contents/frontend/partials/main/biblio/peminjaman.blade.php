<x-frontend.seo :pageConfig="$pageConfig" />


<div class="peminjaman-page content-page">
    <section class="biblio-section">
        <div class="container">
            <div class="row section-row align-items-center">
                <div class="col-lg-7 col-md-9">
                    <div class="section-title">
                        <h2 class="wow fadeInUp">
                            {{ data_get($content, 'title') }}
                        </h2>
                        <p class="wow fadeInUp" data-wow-delay="0.25s">
                            {{ data_get($content, 'description') }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="peminjaman-form text-center">
                        <button type="button" id="launchScannerBtn" class="btn btn-primary btn-lg wow fadeInUp" data-wow-delay="0.5s">
                            <i class="fas fa-qrcode me-2"></i> Mulai Peminjaman
                        </button>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center align-items-center mt-4" id="mainPageCartContainer" style="display: none;">
                <div class="col">
                    <div class="card shadow-sm wow fadeInUp">
                        <div class="card-header text-white">
                            <h5 class="mb-0"><i class="fas fa-list me-2"></i>Daftar Buku untuk Dipinjam</h5>
                        </div>
                        <div class="card-body flex-column d-flex">
                            <div id="mainPageUserInfo" class="mb-3"></div>

                            <div id="mainPageCartList">
                            </div>

                            <div id="mainPageCartSummary" class="mt-3"></div>

                            <div id="mainPageAlert" class="mt-3" style="display: none;"></div>

                            <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap">
                                <button type="button" class="btn btn-danger" id="resetLoanBtn">
                                    <i class="fas fa-times me-2"></i>Batal Peminjaman
                                </button>

                                <div class="mt-2 mt-md-0">
                                    <button type="button" class="btn btn-info" id="scanMoreBtn">
                                        <i class="fas fa-qrcode me-2"></i>Scan Lagi
                                    </button>
                                    <button type="button" class="btn btn-success " id="mainCheckoutBtn" disabled>
                                        <i class="fas fa-check me-2"></i>Proses Checkout
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="scanModal" tabindex="-1" aria-labelledby="scanModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="scanModalLabel">Pindai QR Code Peminjaman</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12" id="reader-container">
                                <div id="reader" style="width: 100%; max-width: 500px; margin: auto; border: 1px solid #ddd; border-radius: 5px;"></div>
                                <div id="scannerMessage" class="alert alert-info mt-2">
                                    Arahkan kamera ke QR Code Anda untuk memulai...
                                </div>
                            </div>

                            <div class="col-12 mt-3" id="cart-container">
                                <div id="loadingIndicator" class="mt-3 text-center" style="display: none;">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <p class="mt-2">Memproses...</p>
                                </div>

                                <div id="errorResult" class="mt-3" style="display: none;">
                                    <div class="alert alert-danger" id="errorMessage">
                                    </div>
                                </div>

                                <div id="bookResult" class="mt-3" style="display: none;">
                                    <div id="bookDetails">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" id="modal-footer-controls">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>

        <script src="{{ asset('theme/js/TestLoan.js') }}"></script>
        <script src="/path/to/your/theme/assets/plugins/sweetalert2/sweetalert2.all.min.js"></script>
    </section>
</div>