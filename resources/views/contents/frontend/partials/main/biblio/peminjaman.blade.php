<section class="biblio-section">
    <div class="container">
        <div class="row section-row align-items-center">
            <div class="col-lg-7 col-md-9">
                <div class="section-title">
                    <h2 class="wow fadeInUp">
                        {{data_get($content, 'title')}}
                    </h2>
                    <p class="wow fadeInUp" data-wow-delay="0.25s">
                        {{data_get($content, 'description')}}
                    </p>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="peminjaman-form">
                    <form id="peminjamanForm" class="wow fadeInUp" data-wow-delay="0.5s" onsubmit="return false;">
                        <div id="reader" style="width: 50%; margin: auto; border: 1px; border-radius: 5px;"></div>

                        <div class="mt-3 text-center scanner-controls">
                            <button type="button" id="startBtn" class="btn btn-success me-2" onclick="startScanner()">Mulai Scan QR User</button>
                            <button type="button" id="resetBtn" class="btn btn-secondary ms-2" onclick="resetResults()" style="display: none;">Reset & Scan User Lagi</button>
                        </div>

                        <input type="hidden" id="barcodeResult" readonly class="form-control mt-3" placeholder="Hasil scan akan muncul di sini...">

                        <!-- Area untuk menampilkan hasil pencarian buku -->
                        <div id="bookResult" class="mt-3" style="display: none;">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Hasil Scan Buku</h5>
                                </div>
                                <div class="card-body" id="bookDetails">
                                    <!-- Hasil detail buku akan ditampilkan di sini -->
                                </div>
                            </div>
                        </div>

                        <!-- Member ID Form sudah tidak diperlukan karena user sudah ter-authorize dari QR -->

                        <!-- Area untuk menampilkan pesan error -->
                        <div id="errorResult" class="mt-3" style="display: none;">
                            <div class="alert alert-danger" id="errorMessage">
                                <!-- Pesan error akan ditampilkan di sini -->
                            </div>
                        </div>

                        <!-- Loading indicator -->
                        <div id="loadingIndicator" class="mt-3 text-center" style="display: none;">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2">Mencari data buku...</p>
                        </div>
                    </form>
                </div>

            </div>
        </div>




    </div>
    <script src="{{ asset('theme/js/LoanBiblio.js') }}"></script>
</section>