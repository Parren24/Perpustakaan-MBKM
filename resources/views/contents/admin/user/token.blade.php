@extends(request()->query('snap') == true ? 'layouts.snap' : 'layouts.apps')

@section('head')
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
@endsection

@section('toolbar')
<div class="toolbar" id="kt_toolbar">
    <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
        <div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
            <h1 class="d-flex text-dark fw-bolder fs-3 align-items-center my-1">Otorisasi Peminjaman</h1>
        </div>
    </div>
</div>
@endsection

@section('content')
<div id="kt_app_content_container" class="app-container container-fluid" data-cue="slideInLeft" data-duration="1000" data-delay="0">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card h-100">
                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                    <div class="peminjaman-form w-100">
                        
                        <div class="text-center" id="startBtnContainer">
                            <div class="mb-4">
                                <i class="fas fa-qrcode fs-3x text-primary mb-3"></i>
                                <h3 class="fw-bold text-dark">Transaksi Peminjaman</h3>
                                <p class="text-muted">Arahkan kamera HP Anda ke layar Kios Perpustakaan untuk memulai sesi.</p>
                            </div>
                            <button type="button" class="btn btn-success btn-lg px-5 mt-2" onclick="startScannerKios()">
                                <i class="fas fa-camera me-2"></i>Mulai Scan Kios
                            </button>
                        </div>

                        <div id="scannerContainer" class="mt-4 text-center" style="display: none;">
                            <h5 class="fw-bold mb-3">Arahkan ke QR Code Kios</h5>
                            <div id="reader" style="width: 100%; max-width: 400px; min-height: 250px; margin: auto; border-radius: 8px; overflow: hidden; border: 2px dashed #ccc;"></div>
                            
                            <button type="button" class="btn btn-danger mt-4" onclick="stopScannerKios()">
                                <i class="fas fa-times me-2"></i>Batal Scan
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-7 mt-4 mt-md-0">
            <div class="row mb-4">
                <div class="col-6">
                    <div class="card bg-primary bg-opacity-10 border-0 h-100">
                        <div class="card-body d-flex align-items-center p-4">
                            <span class="symbol symbol-50px me-3">
                                <span class="symbol-label bg-primary text-inverse-primary fs-2 fw-bold"><i class="fas fa-book" style="color: white;"></i></span>
                            </span>
                            <div class="d-flex flex-column">
                                <span class="fs-2 fw-bold text-dark" id="totalLoansCount">-</span>
                                <span class="text-muted fs-7">Peminjaman Aktif</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card bg-danger bg-opacity-10 border-0 h-100">
                        <div class="card-body d-flex align-items-center p-4">
                            <span class="symbol symbol-50px me-3">
                                <span class="symbol-label bg-danger text-inverse-danger fs-2 fw-bold white"><i class="fas fa-exclamation-triangle" style="color: white;"></i></span>
                            </span>
                            <div class="d-flex flex-column">
                                <span class="fs-2 fw-bold text-dark" id="totalPenaltiesCount">-</span>
                                <span class="text-muted fs-7">Penalti Peminjaman</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold text-dark">Riwayat Terakhir</span>
                        <span class="text-muted mt-1 fw-semibold fs-7">Daftar buku yang sedang/pernah dipinjam</span>
                    </h3>
                    <div class="card-toolbar">
                        <button type="button" class="btn btn-sm btn-light-primary" onclick="historyLoan()">
                            <i class="fas fa-sync-alt me-1"></i> Refresh
                        </button>
                    </div>
                </div>
                <div class="card-body pt-3">
                    <div id="loanHistoryContainer">
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                historyLoan();
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
    var TokenDashboardConfig = {
        routes: {
            loanHistory: "{{ route('user.loan-history') }}"
        },
        assets: {
            emptyIllustration: "{{ asset('theme/media/illustrations/sketchy-1/16.png') }}"
        }
    };
</script>
<script src="{{ asset('theme/js/TokenDashboard.js') }}?v={{ time() }}"></script>
@endpush