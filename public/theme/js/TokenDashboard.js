let html5QrCode = null;
let isScanning = false;

// ==========================================
// FUNGSI RIWAYAT PEMINJAMAN
// ==========================================
function historyLoan() {
    const container = document.getElementById('loanHistoryContainer');

    fetch(TokenDashboardConfig.routes.loanHistory, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        let items = [];

        if (data.history) {
            items = data.history;
            if (data.summary) {
                const totalLoansEl = document.getElementById('totalLoansCount');
                const totalPenaltiesEl = document.getElementById('totalPenaltiesCount');
                
                if (totalLoansEl) totalLoansEl.innerText = data.summary.active_loans;
                if (totalPenaltiesEl) totalPenaltiesEl.innerText = data.summary.overdue_loans;
            }
        } else {
            items = Array.isArray(data) ? data : (data && Object.keys(data).length > 0 ? [data] : []);
        }

        if (items.length > 0) {
            let htmlContent = '';
            items.forEach(item => {
                const options = { year: 'numeric', month: 'long', day: 'numeric' };
                const formattedDate = new Date(item.loan_date).toLocaleDateString('id-ID', options);
                const formattedDue = new Date(item.due_date).toLocaleDateString('id-ID', options);

                htmlContent += `
                <div class="d-flex align-items-center mb-7">
                    <div class="symbol symbol-50px me-5">
                        <span class="symbol-label bg-light-primary">
                            <i class="fas fa-book text-primary fs-2x"></i>
                        </span>
                    </div>
                    <div class="d-flex flex-column">
                        <a href="#" class="text-dark text-hover-primary fs-6 fw-bold">${item.title}</a> <br/>
                        <span class="text-muted fw-semibold d-block fs-7"> Pinjam: ${formattedDate}</span> <br/>
                        <span class="text-danger fw-semibold d-block fs-7">Jatuh Tempo: ${formattedDue}</span>
                    </div>
                </div>`;
            });
            container.innerHTML = htmlContent;
        } else {
            container.innerHTML = `
            <div class="text-center py-5">
                <img src="${TokenDashboardConfig.assets.emptyIllustration}" class="w-150px mb-3" alt="" /> 
                <p class="text-muted fw-bold">Belum ada riwayat peminjaman.</p>
            </div>`;
        }
    })
    .catch(error => {
        console.error('Error fetching loan history:', error);
        container.innerHTML = '<div class="alert alert-danger small">Gagal memuat history.</div>';
    });
}

// ==========================================
// FUNGSI SCANNER KAMERA KIOS
// ==========================================

function startScannerKios() {
    document.getElementById('startBtnContainer').style.display = 'none';
    document.getElementById('scannerContainer').style.display = 'block';

    if (!html5QrCode) {
        html5QrCode = new Html5Qrcode("reader");
    }

    const config = { fps: 10, qrbox: { width: 250, height: 250 } };

    // Buka kamera (prioritaskan kamera belakang / environment)
    html5QrCode.start({ facingMode: "environment" }, config, onScanSuccess)
    .then(() => {
        isScanning = true;
    })
    .catch(err => {
        console.error("Camera Error:", err);
        
        // Deteksi jika browser memblokir karena HTTP biasa
        let errorMsg = 'Tidak dapat mengakses kamera. Pastikan izin kamera diberikan.';
        if (location.protocol !== 'https:' && location.hostname !== 'localhost') {
            errorMsg = 'Akses kamera diblokir karena Anda tidak menggunakan HTTPS. Akses melalui HTTPS atau Localhost agar kamera dapat terbuka.';
        }
        
        Swal.fire('Kamera Diblokir', errorMsg, 'error');
        stopScannerKios();
    });
}

function stopScannerKios() {
    if (html5QrCode && isScanning) {
        html5QrCode.stop().then(() => {
            isScanning = false;
            resetScannerUI();
        }).catch(err => {
            console.error("Failed to stop scanner", err);
            resetScannerUI();
        });
    } else {
        resetScannerUI();
    }
}

function resetScannerUI() {
    document.getElementById('scannerContainer').style.display = 'none';
    document.getElementById('startBtnContainer').style.display = 'block';
}

function onScanSuccess(decodedText, decodedResult) {
    // 1. Matikan scanner segera agar tidak menembak endpoint berkali-kali
    if (html5QrCode && isScanning) {
        html5QrCode.stop().then(() => {
            isScanning = false;
            resetScannerUI();
            
            // 2. Lanjutkan proses penembakan API dari hasil URL QR Kios
            processKiosUrl(decodedText);
        }).catch(err => console.error(err));
    }
}

function processKiosUrl(url) {
    Swal.fire({
        title: 'Memproses...',
        text: 'Menghubungkan ke layar Kios Perpustakaan...',
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading(); }
    });

    fetch(url, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if(data.status === true) {
            Swal.fire('Berhasil!', 'Identitas Anda telah terverifikasi. Silakan lanjutkan scan buku di layar Kios.', 'success');
        } else {
            Swal.fire('Gagal', data.message || 'QR Code Kios tidak valid atau sudah kedaluwarsa.', 'error');
        }
    })
    .catch(err => {
        console.error(err);
        Swal.fire('Error', 'Terjadi gangguan jaringan saat memverifikasi QR Code.', 'error');
    });
}