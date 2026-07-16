document.addEventListener('DOMContentLoaded', (event) => {

    let userAuthorized = false;
    let isProcessingScan = false;
    let authorizedUserData = null;
    let itemToReturn = null; // Menyimpan data { loan_id, item_code, title }
    let pollingInterval = null; 
    let currentSessionId = null;
    let barcodeBuffer = "";
    let barcodeTimeout = null;

    // --- Elemen DOM ---
    const scanModalElement = document.getElementById('scanModal');
    if (!scanModalElement) return;
    const scanModal = new bootstrap.Modal(scanModalElement);

    const kiosQrContainer = document.getElementById('kiosQrContainer');
    const qrImageWrapper = document.getElementById('qrImageWrapper');
    const barcodeInputContainer = document.getElementById('barcode-input-container');
    const barcodeInput = document.getElementById('barcodeInput');
    const scannerMessage = document.getElementById('scannerMessage');
    const loadingIndicator = document.getElementById('loadingIndicator');

    const launchScannerBtn = document.getElementById('launchScannerBtn');
    const aturan = document.getElementById('aturan');

    const mainPageCartContainer = document.getElementById('mainPageCartContainer');
    const mainPageUserInfo = document.getElementById('mainPageUserInfo');
    const mainPageCartList = document.getElementById('mainPageCartList');
    const mainPageCartSummary = document.getElementById('mainPageCartSummary');
    const mainCheckoutBtn = document.getElementById('mainCheckoutBtn');

    const header_data = {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        'Accept': 'application/json'
    }

    if (launchScannerBtn) {
        launchScannerBtn.addEventListener('click', () => {
            itemToReturn = null; 
            scanModal.show();
        });
    }

    window.addEventListener('keypress', function(e) {
        // Abaikan jika user belum login, atau modal scan QR sedang terbuka, atau sistem sedang memproses buku
        if (!userAuthorized || isProcessingScan || scanModalElement.classList.contains('show')) return;

        const mainScannerMessage = document.getElementById('mainScannerMessage');

        // Jika scanner mengirimkan 'Enter' (selesai scan)
        if (e.key === 'Enter') {
            e.preventDefault();
            const scannedText = barcodeBuffer.trim();
            barcodeBuffer = ""; // Reset buffer untuk scan berikutnya
            
            if (scannedText === '') return;
            
            isProcessingScan = true;
            if(mainScannerMessage) {
                mainScannerMessage.innerHTML = `<div class="alert alert-info py-2 mb-0 text-center"><span class="spinner-border spinner-border-sm me-2" role="status"></span>Mencari buku...</div>`;
            }
            
            getBookDetailsAndAddToCart(scannedText); // Fungsi yang sudah kamu sesuaikan sebelumnya
            return;
        }

        // Jika yang diketik adalah karakter normal, masukkan ke buffer
        if (e.key.length === 1) {
            barcodeBuffer += e.key;
        }

        // Trik khusus Kios: Scanner mengetik sangat cepat (biasanya < 30ms per karakter).
        // Jika jeda antar ketikan lebih dari 100ms, kita anggap itu bukan scanner dan buffer dikosongkan.
        // Ini mencegah bug jika ada ketikan acak dari keyboard.
        clearTimeout(barcodeTimeout);
        barcodeTimeout = setTimeout(() => {
            barcodeBuffer = "";
        }, 100); 
    });

    // LOGIKA TRANSISI: Modal Tampil (QR atau Scanner Fisik)
    scanModalElement.addEventListener('shown.bs.modal', () => {
        isProcessingScan = false;
        const scanModalLabel = document.getElementById('scanModalLabel');

        if (!userAuthorized) {
            // --- MODE LOGIN QR ---
            kiosQrContainer.style.display = 'block';
            barcodeInputContainer.style.display = 'none';

            if (scanModalLabel) scanModalLabel.innerHTML = '<i class="fas fa-qrcode me-2"></i> Otorisasi Pengembalian';
            
            generateKiosQr(); 
        } else if (itemToReturn) {
            // --- MODE SCANNER FISIK (KONFIRMASI BUKU) ---
            kiosQrContainer.style.display = 'none';
            barcodeInputContainer.style.display = 'block';

            if (scanModalLabel) scanModalLabel.innerHTML = '<i class="fa-solid fa-barcode me-2"></i> Konfirmasi Barcode';
            scannerMessage.innerHTML = `Gunakan scanner fisik ke barcode buku: <br><b>${itemToReturn.title}</b> (${itemToReturn.item_code})`;
            scannerMessage.className = 'alert alert-light mb-2 text-center';

            if(barcodeInput) {
                barcodeInput.value = '';
                barcodeInput.focus();
            }
        } else {
            showErrorInModal("Mode tidak valid. Silakan tutup dan pilih buku dari daftar.");
            setTimeout(() => { isProcessingScan = false; }, 2000);
        }
    });

    scanModalElement.addEventListener('hidden.bs.modal', () => {
        if (pollingInterval) clearInterval(pollingInterval);
        if(barcodeInput) barcodeInput.value = '';
        itemToReturn = null;
    });

    // Menjaga kursor tetap aktif di field Input saat Scanner Fisik
    if(barcodeInput) {
        // barcodeInput.style.opacity = '0';
        // barcodeInput.style.position = 'absolute';
        // barcodeInput.style.zIndex = '-1';
        // barcodeInput.style.width = '1px'; // Buat sangat kecil
        // barcodeInput.style.height = '1px';

        barcodeInput.addEventListener('blur', () => {
            if (scanModalElement.classList.contains('show') && userAuthorized && itemToReturn) {
                barcodeInput.focus();
            }
        });

        let lastKeyTime = Date.now();

        // 1. Blokir copy-paste manual menggunakan mouse atau shortcut keyboard
        barcodeInput.addEventListener('paste', (e) => {
            e.preventDefault();
        });

        // 2. Hitung kecepatan ketikan
        barcodeInput.addEventListener('keydown', function(e) {
            // Biarkan tombol Enter diproses oleh event 'keypress' di bawah
            if (e.key === 'Enter') return;
            
            // Abaikan tombol modifier (Shift, Ctrl, Alt, CapsLock) agar tidak mengganggu kalkulasi waktu
            if (e.key.length > 1) return; 

            const currentTime = Date.now();
            const timeDiff = currentTime - lastKeyTime;
            lastKeyTime = currentTime;

            // Jika jeda ketikan > 50ms, ini pasti ketikan manusia (atau karakter pertama dari scanner).
            // Kita bersihkan input. Jika manusia mengetik lambat, field akan selalu ter-reset.
            // Jika scanner yang bekerja, karakter pertama me-reset field, karakter selanjutnya (< 50ms) akan lolos.
            if (timeDiff > 50) {
                this.value = ''; 
            }
        });
        // ---------------------------------------------------

        barcodeInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault(); 
                
                const scannedText = this.value.trim();
                this.value = ''; 
                
                if (scannedText === '' || isProcessingScan) return;
                
                isProcessingScan = true;
                hideModalResults();
                showModalLoading(true);

                if (itemToReturn) {
                    if (scannedText === itemToReturn.item_code) {
                        processReturnItem(itemToReturn.loan_id);
                    } else {
                        showErrorInModal(`Barcode Salah!<br>Diharapkan: <b>${itemToReturn.item_code}</b><br>Terbaca: <b>${scannedText}</b>`);
                        setTimeout(() => { isProcessingScan = false; }, 2000);
                    }
                }
            }
        });
    }

    if (mainCheckoutBtn) {
        mainCheckoutBtn.addEventListener('click', () => location.reload());
    }

    // ==========================================
    // LOGIKA GENERATE QR & POLLING KIOS
    // ==========================================

    function generateKiosQr() {
        scannerMessage.textContent = 'Menghasilkan QR Code...';
        scannerMessage.className = 'alert alert-light mb-2 text-center';
        
        fetch('/biblio/kios/generate-qr-ajax', { headers: header_data })
            .then(res => res.json())
            .then(data => {
                if (data.status) {
                    currentSessionId = data.sessionId;
                    qrImageWrapper.innerHTML = data.qrCode; 
                    scannerMessage.textContent = 'Menunggu Anda melakukan scan via HP...';
                    
                    if (pollingInterval) clearInterval(pollingInterval);
                    pollingInterval = setInterval(checkKiosStatus, 2000);
                } else {
                    showErrorInModal('Gagal memuat QR Code.');
                }
            });
    }

    function checkKiosStatus() {
        if (!currentSessionId) return;

        fetch(`/biblio/kios/check-status/${currentSessionId}`)
            .then(res => res.json())
            .then(data => {
                if (data.status === 'scanned') {
                    clearInterval(pollingInterval);
                    scannerMessage.className = 'alert alert-success mb-2 text-center';
                    scannerMessage.textContent = 'Scan berhasil! Menyiapkan sesi...';
                    claimSession();
                } else if (data.status === 'expired') {
                    clearInterval(pollingInterval);
                    showErrorInModal('Waktu QR habis. Silakan tutup dan buka lagi.');
                    qrImageWrapper.innerHTML = '<i class="fas fa-times-circle text-danger fa-4x"></i>';
                }
            });
    }

    function claimSession() {
        fetch('/biblio/kios/claim-session', {
            method: 'POST',
            headers: header_data,
            body: JSON.stringify({ session_id: currentSessionId })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status) {
                userAuthorized = true;
                authorizedUserData = data.data;
                scanModal.hide(); 
                initializeMainPage(); 

                Swal.fire({
                    icon: 'success', title: 'Login Berhasil',
                    text: `Halo, ${data.data.member_name}!`, timer: 1500, showConfirmButton: false
                });
            } else {
                showErrorInModal('Gagal mengklaim sesi transaksi.');
            }
        });
    }

    // ==========================================
    // LOGIKA RENDER & PROSES BUKU
    // ==========================================

    function initializeMainPage() {
        if (authorizedUserData) {
            mainPageUserInfo.innerHTML = `
                <div class="alert alert-light border-0 h-100 shadow-sm d-flex flex-column justify-content-center">
                    <h3 class="fw-bolder">Halo, ${authorizedUserData.member_name}!</h3>
                    <p class="mb-3 text-muted">Sistem siap. Silakan langsung scan barcode fisik buku yang ingin dikembalikan.</p>
                    
                    <div id="mainScannerMessage" class="mt-2 w-100" style="min-height: 40px;"></div>
                </div>`;
        }
        mainPageCartContainer.style.display = 'block';
        if (launchScannerBtn) launchScannerBtn.style.display = 'none';
        if (aturan) aturan.style.display = 'none';

        if (mainCheckoutBtn) mainCheckoutBtn.disabled = false;

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
                    const loans = data.data.active_loans || data.data;
                    renderLoanList(loans);
                } else {
                    mainPageCartList.innerHTML = `<div class="alert alert-warning">${data.message}</div>`;
                }
            })
            .catch(e => {
                showMainPageLoading(false);
                mainPageCartList.innerHTML = `<div class="alert alert-danger">Gagal memuat data.</div>`;
            });
    }

    function renderLoanList(loans) {
        if (!loans || loans.length === 0) {
            mainPageCartList.innerHTML = '<div class="alert alert-secondary text-center">Tidak ada peminjaman aktif.</div>';
            mainPageCartSummary.innerHTML = '';
            return;
        }

        let html = '<ul class="list-group">';
        loans.forEach(loan => {
            const safeTitle = loan.title ? loan.title.replace(/'/g, "\\'").replace(/"/g, "&quot;") : 'Tanpa Judul';

            let formattedDueDate = loan.due_date;
            try {
                const dateObj = new Date(loan.due_date);
                if (!isNaN(dateObj)) {
                    formattedDueDate = dateObj.toLocaleDateString('id-ID', {
                        day: '2-digit', month: 'long', year: 'numeric'
                    });
                }
            } catch (e) {}

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
                    icon: 'success', title: 'Pengembalian Berhasil',
                    text: 'Buku telah berhasil dikembalikan ke perpustakaan.',
                    timer: 2000, showConfirmButton: false
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
        scannerMessage.className = 'alert alert-danger mb-2 text-center';
    }

    function showMainPageLoading(isLoading) {
        if (mainCheckoutBtn) mainCheckoutBtn.disabled = isLoading;
    }
});