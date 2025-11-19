@extends(request()->query('snap') == true ? 'layouts.snap' : 'layouts.apps')

@section('head')
<script src="{{ asset('theme/frontend/js/qrcode.min.js') }}"></script>
@endsection

@section('toolbar')
    {{-- <x-theme.toolbar :breadCrump="$pageData->breadCrump" :title="$pageData->title">
        <x-slot:tools>
        </x-slot:tools>
    </x-theme.toolbar> --}}
    <div class="toolbar" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                <h1 class="d-flex text-dark fw-bolder fs-3 align-items-center my-1">Token Peminjaman</h1>
            </div>
        </div>
    </div>
@endsection

@section('content')
<div id="kt_app_content_container" class="app-container container-fluid" data-cue="slideInLeft" data-duration="1000"
    data-delay="0">
    <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="peminjaman-form">
                    <form id="peminjamanForm" class="wow fadeInUp" data-wow-delay="0.5s" onsubmit="return false;">
                        
                        <!-- Tombol untuk memulai peminjaman -->
                        <div class="text-center mb-4">
                            <button type="button" id="startBtn" class="btn btn-primary btn-lg" onclick="startPeminjaman()">
                                <i class="fas fa-qrcode me-2"></i>Buat Token Peminjaman
                            </button>
                        </div>
                        
                        <div id="userResult" class="mt-3" style="display: none;">
                            <div class="card">
                                <div class="card-header text-center">
                                    <h5>QR Code Peminjaman</h5>
                                    <p class="mb-0">Scan QR Code ini untuk melakukan peminjaman</p>
                                </div>
                                <div class="card-body">
                                    <div id="qrCodeContainer" class="mt-2 d-flex flex-column justify-content-center align-items-center"></div>
                                </div>
                            </div>
                        </div>
                        <div id="errorResult" class="mt-3" style="display: none;">
                            <div class="alert alert-danger" id="errorMessage">
                                <!-- Pesan error akan ditampilkan di sini -->
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
</div>
@endsection

@push('scripts')
<script>
        let QRCodeLib;
        let html5QrCode;
        let isScanning = false;

        const userResultDiv = document.getElementById('userResult');
        const qrCodeContainer = document.getElementById('qrCodeContainer');

        function loadQRCodeLibrary() {
            return new Promise((resolve, reject) => {
                if (typeof QRCode !== 'undefined') {
                    console.log('QRCode library sudah tersedia');
                    resolve();
                    return;
                }

                console.log('Loading QRCode library...');
                const script = document.createElement('script');
                script.src = '{{ asset('theme/frontend/js/qrcode.min.js') }}';
                script.onload = () => {
                    if (typeof QRCode !== 'undefined') {
                        console.log('QRCode library berhasil dimuat');
                        resolve();
                    } else {
                        console.error('QRCode library gagal dimuat dari file lokal');
                        // Coba CDN sebagai fallback
                        const cdnScript = document.createElement('script');
                        cdnScript.src = 'https://cdnjs.cloudflare.com/ajax/libs/qrcode/1.5.3/qrcode.min.js';
                        cdnScript.onload = () => {
                            if (typeof QRCode !== 'undefined') {
                                console.log('QRCode library berhasil dimuat dari CDN');
                                resolve();
                            } else {
                                reject(new Error('Gagal memuat QRCode library dari CDN'));
                            }
                        };
                        cdnScript.onerror = () => reject(new Error('Gagal memuat QRCode library dari CDN'));
                        document.head.appendChild(cdnScript);
                    }
                };
                script.onerror = () => {
                    console.error('QRCode library gagal dimuat dari file lokal');
                    // Coba CDN sebagai fallback
                    const cdnScript = document.createElement('script');
                    cdnScript.src = 'https://cdnjs.cloudflare.com/ajax/libs/qrcode/1.5.3/qrcode.min.js';
                    cdnScript.onload = () => {
                        if (typeof QRCode !== 'undefined') {
                            console.log('QRCode library berhasil dimuat dari CDN');
                            resolve();
                        } else {
                            reject(new Error('Gagal memuat QRCode library dari CDN'));
                        }
                    };
                    cdnScript.onerror = () => reject(new Error('Gagal memuat QRCode library dari CDN'));
                    document.head.appendChild(cdnScript);
                };
                document.head.appendChild(script);
            });
        }

        // Pastikan library QRCode sudah dimuat
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM Content Loaded');
            loadQRCodeLibrary().then(() => {
                console.log('QRCode library siap digunakan');
            }).catch(error => {
                console.error('Error loading QRCode library:', error);
                showError('Gagal memuat QRCode library. Pastikan koneksi internet tersedia.');
            });
        });

        function startPeminjaman() {
            const startBtn = document.getElementById('startBtn');
            
            // Disable button dan tampilkan loading
            startBtn.disabled = true;
            startBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memuat...';
            
            initiateUserToken();
        }

        function enableStartButton() {
            const startBtn = document.getElementById('startBtn');
            startBtn.disabled = false;
            startBtn.innerHTML = '<i class="fas fa-qrcode me-2"></i>Mulai Peminjaman';
        }

        function initiateUserToken() {
            fetch('{{ route('generate-token') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // Tampilkan hasil dan buat QR Code
                    userResultDiv.style.display = 'block';
                    generateQRCode(data.token, data.expires_in);
                } else {
                    showError(data.message || 'Terjadi kesalahan saat generate token');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showError('Terjadi kesalahan saat mengirim permintaan');
            })
            .finally(() => {
                enableStartButton();
            });
        }

        function showError(message) {
            const errorResultDiv = document.getElementById('errorResult');
            const errorMessageDiv = document.getElementById('errorMessage');
            errorMessageDiv.textContent = message;
            errorResultDiv.style.display = 'block';
            
            // Enable button kembali ketika error
            enableStartButton();
        }

        function resetResults() {
            userResultDiv.style.display = 'none';
            qrCodeContainer.innerHTML = '';
            document.getElementById('errorResult').style.display = 'none';
            
            // Enable button kembali ketika reset
            enableStartButton();
        }

        async function generateQRCode(token, expiresIn) {
            // Bersihkan container
            qrCodeContainer.innerHTML = '';
            
            try {
                // Pastikan QRCode library dimuat terlebih dahulu
                await loadQRCodeLibrary();
                
                // Buat container div untuk QR Code
                const qrContainer = document.createElement('div');
                qrContainer.style.display = 'flex';
                qrContainer.style.justifyContent = 'center';
                qrContainer.style.alignItems = 'center';
                qrContainer.style.padding = '20px';
                qrCodeContainer.appendChild(qrContainer);
                
                // Generate QR Code menggunakan library yang sudah ada
                const qrCode = new QRCode(qrContainer, {
                    text: token,
                    width: 256,
                    height: 256,
                    colorDark: '#000000',
                    colorLight: '#FFFFFF',
                    correctLevel: QRCode.CorrectLevel.H
                });

                // Tampilkan informasi token
                const tokenInfo = document.createElement('div');
                tokenInfo.className = 'mt-3 text-center';
                tokenInfo.innerHTML = `
                    <p class="mb-1"><strong>Token:</strong></p>
                    <code class="bg-light px-2 py-1 rounded">${token}</code>
                `;
                qrCodeContainer.appendChild(tokenInfo);

                // Tambahkan countdown
                const countdownDisplay = document.createElement('div');
                countdownDisplay.id = 'countdownDisplay';
                countdownDisplay.className = 'mt-3 text-center alert alert-info';
                qrCodeContainer.appendChild(countdownDisplay);

                startCountdown(expiresIn, countdownDisplay);
                
                console.log('QR Code berhasil dibuat dengan token:', token);
            } catch (error) {
                console.error('Error creating QR Code:', error);
                showError('Gagal membuat QR Code. Error: ' + error.message);
            }
        }

        function startCountdown(duration, displayElement) {
        // 1. Tentukan timestamp kapan QR code akan kedaluwarsa
        //    Date.now() adalah waktu saat ini dalam milidetik
        //    duration adalah dalam detik, jadi kita kalikan 1000
        const expirationTimestamp = Date.now() + (duration * 1000);

        // Clear any existing countdown
        if (window.countdownInterval) {
            clearInterval(window.countdownInterval);
        }

        function updateTimer() {
            // 2. Dapatkan sisa waktu dalam milidetik
            const remainingMilliseconds = expirationTimestamp - Date.now();

            // 3. Ubah ke total detik
            //    Kita gunakan Math.max(0, ...) agar tidak menampilkan angka negatif
            const totalSeconds = Math.max(0, Math.floor(remainingMilliseconds / 1000));

            let minutes = parseInt(totalSeconds / 60, 10);
            let seconds = parseInt(totalSeconds % 60, 10);
            minutes = minutes < 10 ? "0" + minutes : minutes;
            seconds = seconds < 10 ? "0" + seconds : seconds;

            displayElement.innerHTML = `<i class="fas fa-clock"></i> Kedaluwarsa dalam: <strong>${minutes}:${seconds}</strong>`;

            // 4. Periksa apakah waktu sudah habis
            if (totalSeconds <= 0) {
                clearInterval(window.countdownInterval);
                resetResults();
                showError('Session QR Code telah habis. Silakan mulai ulang.');
            }
        }

        // Panggil updateTimer() sekali agar tampilan langsung muncul (tidak menunggu 1 detik)
        updateTimer(); 
        
        // Atur interval untuk memperbarui timer setiap detik
        window.countdownInterval = setInterval(updateTimer, 1000);
    }
    </script>
@endpush