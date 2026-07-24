<x-frontend.seo :pageConfig="$pageConfig" />

<section class="fact-statistics-section layanan-section flex-grow-1 d-flex align-items-center" >
    <div class="container pt-5 pb-5">
        <div class="row justify-content-center " id="aturan">
            <div class="col">
                <div class=" mb-3 lg-4 pb-2 rounded bg-opacity-75 wow fadeInUp" data-wow-delay="0.5s">
                    <div class="card-body d-flex justify-content-center align-items-center flex-column">
                        <div class="p-4 align-items-center">
                            <H4 class="text-center">Aturan Pengembalian buku</H4>
                            <div class="p-5">
                                <li>Buku yang dikembalikan harus dalam kondisi baik dan tidak rusak.</li>
                                <li>Keterlambatan pengembalian akan dikenakan denda sesuai ketentuan perpustakaan.</li>
                                <li>Pembayaran denda dilakukan dengan pustakawan.</li>
                                <li>Pastikan pembayaran denda sudah dikonfirmasi pada sistem.</li>
                            </div>
                        </div>
                        <div class="pengembalian-form text-center">
                            <button type="button" id="launchScannerBtn" class="btn-default wow fadeInUp" data-wow-delay="0.5s">
                                <i class="fas fa-qrcode me-2"></i> Mulai Sesi Pengembalian
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="mainPageCartContainer" style="display: none;">
            <div class="row align-items-stretch">
                <div class="col-lg-4 col-md-6 col-12 mb-3">
                    <div id="mainPageUserInfo" class="h-100"></div>
                </div>
                <div class="col-lg-8 col-md-6 col-12">
                    <div class="card border-1 ">
                        <div class="card-header text-white">
                            <h5 class="mb-0"><i class="fas fa-list me-2"></i>Daftar Buku Yang Dipinjam</h5>
                        </div>
                        <div class="card-body flex-column d-flex">
                            <div class="overflow-y-auto pe-2" style="max-height: 250px;">
                                <div id="mainPageCartList">
                                </div>
                            </div>

                            <div id="mainPageCartSummary" class="mt-3"></div>

                            <div id="mainPageAlert" class="mt-3" style="display: none;"></div>

                            <div class="d-flex justify-content-end align-items-center mt-4 flex-wrap">
                                <button type="button" class="btn btn-danger rounded-pill px-4 " id="mainCheckoutBtn" disabled>
                                    <i class="fas fa-times me-2"></i>Tutup Sesi
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="scanModal" tabindex="-1" aria-labelledby="scanModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="scanModalLabel"> <i class="fas fa-qrcode me-2"></i> Otorisasi Pengembalian</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12" id="reader-container">
                        <div id="scannerMessage" class="mb-2 w-100 text-center text-muted">
                            Menghasilkan QR Code...
                        </div>
                        
                        <div id="kiosQrContainer" class="text-center mb-3" style="display: none;">
                            <div id="qrImageWrapper"></div> 
                            <p class="text-muted small mt-2">Scan QR Code ini menggunakan HP Anda</p>
                        </div>

                        <div id="barcode-input-container" style="display: none;" class="mt-3 px-3">
                            <input type="text" id="barcodeInput" class="form-control visually-hidden form-control-lg text-center" placeholder="Gunakan scanner fisik ke barcode buku..." autocomplete="off">
                        </div>
                    </div>

                    <div class="col-12 mt-3" id="cart-container">
                        <div id="loadingIndicator" class="mt-3 text-center" style="display: none;">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2">Memproses...</p>
                        </div>

                        <div id="bookResult" class="mt-3" style="display: none;">
                            <div id="bookDetails"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" id="modal-footer-controls">
                <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('theme/js/ReturnLoan.js') }}?v={{ time() }}"></script>
<script src="{{ asset('theme/js/ShutKeyboard.js') }}?v={{ time() }}"></script>
<script src="/path/to/your/theme/assets/plugins/sweetalert2/sweetalert2.all.min.js"></script>