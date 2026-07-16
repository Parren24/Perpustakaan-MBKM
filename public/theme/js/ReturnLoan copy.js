// TAMBAHKAN BARIS INI DI PALING ATAS
document.addEventListener('DOMContentLoaded', (event) => {

    let html5QrCode;
    let isScanning = false;
    let userAuthorized = false;
    let isProcessingScan = false;
    let authorizedUserData = null;
    let itemToReturn = null; // { loan_id, item_code, title }

    // --- Elemen DOM ---
    const scanModalElement = document.getElementById('scanModal');
    if (!scanModalElement) {
        console.error('Modal element #scanModal not found!');
        return;
    }
    const scanModal = new bootstrap.Modal(scanModalElement);

    const readerContainer = document.getElementById('reader-container');
    const scannerMessage = document.getElementById('scannerMessage');
    const loadingIndicator = document.getElementById('loadingIndicator');

    const launchScannerBtn = document.getElementById('launchScannerBtn');
    const aturan = document.getElementById('aturan');

    const mainPageCartContainer = document.getElementById('mainPageCartContainer');
    const mainPageUserInfo = document.getElementById('mainPageUserInfo');
    const mainPageCartList = document.getElementById('mainPageCartList');
    const mainPageCartSummary = document.getElementById('mainPageCartSummary');
    const mainPageAlert = document.getElementById('mainPageAlert');

    const mainCheckoutBtn = document.getElementById('mainCheckoutBtn');
    const resetLoanBtn = document.getElementById('resetLoanBtn');


    const header_data = {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        'Accept': 'application/json'
    }

    // --- Event Listeners ---

    if (launchScannerBtn) {
        launchScannerBtn.addEventListener('click', () => {
            itemToReturn = null; // Reset item saat buka dari tombol utama (Login mode)
            scanModal.show();
        });
    }

    scanModalElement.addEventListener('shown.bs.modal', () => {
        isProcessingScan = false;
        startScanner();
    });

    scanModalElement.addEventListener('hidden.bs.modal', () => {
        stopScanner();
        itemToReturn = null;
    });

    if (mainCheckoutBtn) {
        mainCheckoutBtn.addEventListener('click', () => {
            location.reload(); // Selesai = Refresh/Logout session
        });
    }

    if (resetLoanBtn) {
        resetLoanBtn.addEventListener('click', () => {
            location.reload(); // Batal = Refresh
        });
    }

    // --- Scanner Logic ---

    function onScanSuccess(decodedText, decodedResult) {
        if (isProcessingScan) return;
        isProcessingScan = true;

        if (!header_data['X-CSRF-TOKEN']) {
            showErrorInModal('CSRF token tidak ditemukan.');
            return;
        }

        hideModalResults();
        showModalLoading(true);

        if (!userAuthorized) {
            authorizeUserSession(decodedText);
        } else {
            // Mode Pengembalian
            if (itemToReturn) {
                console.log(`Verifying return: Scanned ${decodedText} vs Expected ${itemToReturn.item_code}`);
                if (decodedText === itemToReturn.item_code) {
                    processReturnItem(itemToReturn.loan_id);
                } else {
                    showErrorInModal(`Barcode Salah!<br>Harapkan: <b>${itemToReturn.item_code}</b><br>Terbaca: <b>${decodedText}</b>`);
                    setTimeout(() => { isProcessingScan = false; }, 2000);
                }
            } else {
                showErrorInModal("Mode tidak valid. Silakan tutup dan pilih buku dari daftar.");
                setTimeout(() => { isProcessingScan = false; }, 2000);
            }
        }
    }

    function onScanFailure(error) { }

    // Fungsi Izin Kamera (Sama seperti TestLoan.js)
    function requestCameraPermission() {
        if (location.protocol !== 'https:' && location.hostname !== 'localhost' && location.hostname !== '127.0.0.1') {
            showErrorInModal("Akses kamera memerlukan koneksi HTTPS.");
            return;
        }

        Html5Qrcode.getCameras().then(devices => {
            if (devices && devices.length) {
                startScanner();
            } else {
                showErrorInModal("Tidak ada kamera yang terdeteksi.");
            }
        }).catch(err => {
            if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                navigator.mediaDevices.getUserMedia({ video: true })
                    .then(function (stream) {
                        stream.getTracks().forEach(track => track.stop());
                        startScanner();
                    })
                    .catch(function (err2) {
                        showErrorInModal("Akses kamera ditolak. Periksa izin browser.");
                    });
            } else {
                showErrorInModal("Browser tidak mendukung akses kamera.");
            }
        });
    }

    function startScanner() {
        if (isScanning) return;

        hideModalResults();
        readerContainer.style.display = 'block';

        const scanModalLabel = document.getElementById('scanModalLabel');

        if (!userAuthorized) {
            if (scanModalLabel) scanModalLabel.textContent = 'Login Anggota';
            scannerMessage.textContent = 'Scan QR Code Token Anda';
            scannerMessage.className = 'alert alert-light mb-2';
        } else if (itemToReturn) {
            if (scanModalLabel) scanModalLabel.textContent = 'Konfirmasi Pengembalian';
            scannerMessage.innerHTML = `Scan Barcode Buku: <b>${itemToReturn.title}</b> (${itemToReturn.item_code})`;
            scannerMessage.className = 'alert alert-light mb-2';
        } else {
            scannerMessage.textContent = 'Siap memindai...';
        }

        html5QrCode = new Html5Qrcode("reader");
        const qrboxFunction = (viewfinderWidth, viewfinderHeight) => {
            const minEdge = Math.min(viewfinderWidth, viewfinderHeight);
            return {
                width: Math.floor(minEdge * 0.8), // 80% dari area kamera
                height: Math.floor(minEdge * 0.8)
            };
        };

        const config = {
            fps: 10,
            qrbox: qrboxFunction,
            aspectRatio: 1.0,
            rememberLastUsedCamera: true
        };


        html5QrCode.start({ facingMode: "environment" }, config, onScanSuccess, onScanFailure)
            .then(() => { isScanning = true; })
            .catch(err => {
                html5QrCode.start({ facingMode: "user" }, config, onScanSuccess, onScanFailure)
                    .then(() => { isScanning = true; })
                    .catch(frontErr => {
                        scannerMessage.className = 'alert alert-danger mb-2';
                        scannerMessage.innerHTML = `Gagal mengakses kamera. <br><button type="button" id="btnPermitCamera" class="btn btn-sm btn-light mt-2">Izinkan Kamera</button>`;
                        const btnPermit = document.getElementById('btnPermitCamera');
                        if (btnPermit) btnPermit.addEventListener('click', requestCameraPermission);
                    });
            });
    }

    function stopScanner() {
        if (html5QrCode && isScanning) {
            html5QrCode.stop().catch(err => console.error(err));
        }
        isScanning = false;
    }

    // --- API Calls ---

    function authorizeUserSession(token) {
        fetch('/biblio/authorize-session', {
            method: 'POST',
            headers: header_data,
            body: JSON.stringify({ token: token })
        })
            .then(r => r.json())
            .then(data => {
                showModalLoading(false);
                if (data.status) {
                    userAuthorized = true;
                    authorizedUserData = data.data;
                    scanModal.hide();
                    initializeMainPage();
                    Swal.fire({ icon: 'success', title: 'Login Berhasil', text: `Halo, ${data.data.member_name}`, timer: 1500, showConfirmButton: false, buttonsStyling: false, });
                } else {
                    showErrorInModal(data.message || 'Login Gagal');
                    setTimeout(() => { isProcessingScan = false; }, 2000);
                }
            })
            .catch(e => {
                showModalLoading(false);
                showErrorInModal('Error: ' + e.message);
            });
    }
    {/* <p class="mb-0">Member ID: <strong>${authorizedUserData.nomor_induk || 'N/A'}</strong></p> */ }
    function initializeMainPage() {
        if (authorizedUserData) {
            mainPageUserInfo.innerHTML = `
                <div class="alert alert-light border-0 h-100">
                    <h3>Halo, ${authorizedUserData.member_name}</h3>
                    <p>Silakan pilih buku yang ingin Anda kembalikan dari daftar peminjaman mu.</p>
                </div>`;
        }
        mainPageCartContainer.style.display = 'block';
        launchScannerBtn.style.display = 'none';
        aturan.style.display = 'none';

        // Enable buttons
        mainCheckoutBtn.disabled = false;

        refreshActiveLoans();
    }

    function refreshActiveLoans() {
        showMainPageLoading(true);
        mainPageCartList.innerHTML = '<div class="text-center p-3"><div class="spinner-border text-primary"></div></div>';

        fetch('/loan/active-loans', { headers: header_data })
            .then(r => r.json())
            .then(data => {
                showMainPageLoading(false);
                if (data.status) {
                    // Perbaikan: Ambil array dari data.data.active_loans jika ada, atau fallback ke data.data
                    const loans = data.data.active_loans || data.data;
                    renderLoanList(loans);
                } else {
                    mainPageCartList.innerHTML = `<div class="alert alert-warning">${data.message}</div>`;
                }
            })
            .catch(e => {
                showMainPageLoading(false);
                mainPageCartList.innerHTML = `<div class="alert alert-danger">Gagal memuat data: ${e.message}</div>`;
            });
    }

    function renderLoanList(loans) {
        if (!loans || loans.length === 0) {
            mainPageCartList.innerHTML = '<div class="alert alert-info">Tidak ada peminjaman aktif.</div>';
            mainPageCartSummary.innerHTML = '';
            return;
        }

        let html = '<ul class="list-group">';
        loans.forEach(loan => {
            // Pastikan field sesuai dengan response API active-loans
            // Contoh: loan_id, item_code, title, due_date
            const safeTitle = loan.title ? loan.title.replace(/'/g, "\\'").replace(/"/g, "&quot;") : 'Tanpa Judul';

            // Format tanggal Indonesia: 01 Januari 2025
            let formattedDueDate = loan.due_date;
            try {
                const dateObj = new Date(loan.due_date);
                if (!isNaN(dateObj)) {
                    formattedDueDate = dateObj.toLocaleDateString('id-ID', {
                        day: '2-digit',
                        month: 'long',
                        year: 'numeric'
                    });
                }
            } catch (e) {
                console.error('Date formatting error', e);
            }

            html += `
                <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                    <div>
                        <h5 class="mb-1"><strong>${loan.title}</strong></h5>
                        <div class="text-muted small">Kode: ${loan.item_code}</div>
                        <div class="text-danger small"><i class="far fa-clock me-1"></i>Batas Akhir Pengembalian: ${formattedDueDate}</div>
                    </div>
                    <button class="btn btn-outline-secondary rounded-pill px-4" onclick="initiateReturn('${loan.loan_id}', '${loan.item_code}', '${safeTitle}')">
                        <i class="fas fa-undo me-1"></i> Kembalikan
                    </button>
                </li>
            `;
        });
        html += '</ul>';
        mainPageCartList.innerHTML = html;

        mainPageCartSummary.innerHTML = `<div class="text-end mt-3"><h5>Total Dipinjam: <strong>${loans.length}</strong></h5></div>`;
    }

    // Global function agar bisa dipanggil dari onclick HTML
    window.initiateReturn = function (loanId, itemCode, title) {
        itemToReturn = { loan_id: loanId, item_code: itemCode, title: title };
        scanModal.show();
    }

    function processReturnItem(loanId) {
        fetch('/loan/return-item', {
            method: 'POST',
            headers: header_data,
            body: JSON.stringify({ loan_id: loanId })
        })
            .then(r => r.json())
            .then(data => {
                showModalLoading(false);
                if (data.status) {
                    scanModal.hide();
                    Swal.fire({
                        icon: 'success',
                        title: 'Pengembalian Berhasil',
                        text: 'Buku telah berhasil dikembalikan.',
                        timer: 2000,
                        showConfirmButton: false,
                        buttonsStyling: false,
                    });
                    refreshActiveLoans();
                } else {
                    showErrorInModal(data.message || 'Gagal mengembalikan buku.');
                    setTimeout(() => { isProcessingScan = false; }, 2000);
                }
            })
            .catch(e => {
                showModalLoading(false);
                showErrorInModal('Error: ' + e.message);
            });
    }

    // --- Helpers ---

    function showModalLoading(isLoading) {
        loadingIndicator.style.display = isLoading ? 'block' : 'none';
    }

    function hideModalResults() {
        loadingIndicator.style.display = 'none';
    }

    function showErrorInModal(message) {
        scannerMessage.innerHTML = message;
        scannerMessage.className = 'alert alert-danger mb-2';
    }

    function showMainPageLoading(isLoading) {
        if (isLoading) {
            if (mainCheckoutBtn) mainCheckoutBtn.disabled = true;
        } else {
            if (mainCheckoutBtn) mainCheckoutBtn.disabled = false;
        }
    }

    // Clean up
    window.addEventListener('beforeunload', function () {
        stopScanner();
    });
});