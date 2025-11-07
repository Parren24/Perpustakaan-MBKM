<section class="item-peminjaman-section">
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
                        
                        <!-- Tombol untuk memulai peminjaman -->
                        <div class="text-center mb-4">
                            <button type="button" id="startBtn" class="btn btn-primary btn-lg" onclick="startPeminjaman()">
                                <i class="fas fa-qrcode me-2"></i>Mulai Peminjaman
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
    <script>
        let html5QrCode;
        let isScanning = false;

        const userResultDiv = document.getElementById('userResult');
        const qrCodeContainer = document.getElementById('qrCodeContainer');

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
            resetResults();
            
            console.log('Memulai proses peminjaman...');
            
            // Periksa CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (!csrfToken) {
                showError('CSRF token tidak ditemukan. Silakan refresh halaman.');
                return;
            }
            
            console.log('CSRF token ditemukan:', csrfToken.getAttribute('content').substring(0, 10) + '...');
            
            fetch("{{ route('frontend.item.initiate-user-token') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({})
            })
            .then(response => {
                console.log('Response status:', response.status);
                console.log('Response headers:', response.headers.get('content-type'));
                
                if (response.status === 401) {
                    showError('Anda harus login terlebih dahulu untuk meminjam buku.');
                    return null;
                }
                if (response.status === 419) {
                    showError('Session telah expired. Silakan refresh halaman dan coba lagi.');
                    return null;
                }
                if (response.status === 500) {
                    return response.json().then(errorData => {
                        console.error('Server error details:', errorData);
                        if (errorData.message && errorData.message.includes('CSRF')) {
                            showError('Token keamanan tidak valid. Silakan refresh halaman dan coba lagi.');
                        } else {
                            showError('Terjadi kesalahan server. Silakan coba lagi.');
                        }
                        return null;
                    });
                }
                if (!response.ok) {
                    showError(`HTTP Error: ${response.status} ${response.statusText}`);
                    return null;
                }
                return response.json();
            })
            .then(data => {
                if (!data) return; // Skip jika response null
                
                console.log('Response data:', data);
                
                if (data.token && data.expires_in) {
                    userResultDiv.style.display = 'block';
                    generateQRCode(data.token, data.expires_in);
                } else if (data.error) {
                    showError(data.error);
                } else if (data.message) {
                    showError(data.message);
                } else {
                    showError('Terjadi kesalahan saat memproses permintaan.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showError('Terjadi kesalahan jaringan. Silakan coba lagi.');
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

        function generateQRCode(token, expiresIn) {
            // Bersihkan container
            qrCodeContainer.innerHTML = '';
            
            try {
                // Periksa apakah library QRCode tersedia
                if (typeof QRCode === 'undefined') {
                    throw new Error('QRCode library tidak tersedia');
                }
                
                // Buat QR Code
                const qrCode = new QRCode(qrCodeContainer, {
                    text: token,
                    width: 256,
                    height: 256,
                    colorDark: "#000000",
                    colorLight: "#ffffff",
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
                showError('Gagal membuat QR Code. Silakan coba lagi. Error: ' + error.message);
            }
        }

        function startCountdown(duration, displayElement) {
            let timer = duration;
            
            // Clear any existing countdown
            if (window.countdownInterval) {
                clearInterval(window.countdownInterval);
            }
            
            window.countdownInterval = setInterval(() => {
                let minutes = parseInt(timer / 60, 10);
                let seconds = parseInt(timer % 60, 10);
                minutes = minutes < 10 ? "0" + minutes : minutes;
                seconds = seconds < 10 ? "0" + seconds : seconds;

                displayElement.innerHTML = `<i class="fas fa-clock"></i> Kedaluwarsa dalam: <strong>${minutes}:${seconds}</strong>`;

                if (--timer < 0) {
                    clearInterval(window.countdownInterval);
                    resetResults();
                    showError('Session QR Code telah habis. Silakan mulai ulang.');
                }
            }, 1000);
        }
    </script>
</section>