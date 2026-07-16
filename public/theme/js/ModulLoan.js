document.addEventListener('DOMContentLoaded', (event) => {

    let userAuthorized = false;
    let isProcessingScan = false;
    let isCheckoutComplete = false;
    let authorizedUserData = null;
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

    // Tombol di luar Modal
    const launchScannerBtn = document.getElementById('launchScannerBtn');
    const aturan = document.getElementById('aturan');

    // --- Elemen di Halaman Utama (Keranjang) ---
    const mainPageCartContainer = document.getElementById('mainPageCartContainer');
    const mainPageUserInfo = document.getElementById('mainPageUserInfo');
    const mainPageCartList = document.getElementById('mainPageCartList');
    const mainPageCartSummary = document.getElementById('mainPageCartSummary');
    const mainPageAlert = document.getElementById('mainPageAlert');

    // --- Tombol Kontrol Halaman Utama ---
    const mainCheckoutBtn = document.getElementById('mainCheckoutBtn');
    const resetLoanBtn = document.getElementById('resetLoanBtn');

    const header_data = {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        'Accept': 'application/json'
    }

    if (launchScannerBtn) {
        launchScannerBtn.addEventListener('click', () => scanModal.show());
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

    // ==========================================
    // LOGIKA TRANSISI MODAL
    // ==========================================
    scanModalElement.addEventListener('shown.bs.modal', () => {
        isProcessingScan = false;
        const scanModalLabel = document.getElementById('scanModalLabel');

        if (!userAuthorized) {
            // --- MODE TAMPILKAN QR KIOS ---
            kiosQrContainer.style.display = 'block';
            barcodeInputContainer.style.display = 'none';
            if (scanModalLabel) scanModalLabel.innerHTML = '<i class="fas fa-qrcode me-2"></i> Pindai QR Code';
            
            generateKiosQr(); 
        } else {
            // --- MODE SCANNER FISIK MODUL ---
            kiosQrContainer.style.display = 'none';
            barcodeInputContainer.style.display = 'block';
            
            if (scanModalLabel) scanModalLabel.innerHTML = '<i class="fas fa-qrcode me-2"></i> Lakukan Peminjaman';
            
            scannerMessage.textContent = 'Gunakan mesin scanner fisik ke barcode modul...';
            scannerMessage.className = 'alert alert-info mb-2 text-center';
            barcodeInput.value = '';
            barcodeInput.focus();
        }
    });

    scanModalElement.addEventListener('hidden.bs.modal', () => {
        if (pollingInterval) clearInterval(pollingInterval);
        if(barcodeInput) barcodeInput.value = '';
    });

    barcodeInput.addEventListener('blur', () => {
        if (scanModalElement.classList.contains('show') && userAuthorized) {
            barcodeInput.focus();
        }
    });

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
            })
            .catch(err => console.error("Error Generate QR:", err));
    }

    function checkKiosStatus() {
        if (!currentSessionId) return;

        fetch(`/biblio/kios/check-status/${currentSessionId}`)
            .then(res => res.json())
            .then(data => {
                if (data.status === 'scanned') {
                    clearInterval(pollingInterval);
                    scannerMessage.textContent = 'Scan berhasil! Menyiapkan sesi...';
                    scannerMessage.className = 'alert alert-success mb-2 text-center';
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
                
                Swal.fire({
                    icon: 'success', title: 'Otorisasi Berhasil',
                    text: `Halo, ${data.data.member_name}!`, timer: 1500, showConfirmButton: false
                }).then(() => {
                    initializeMainPageCart(); 
                });
            } else {
                showErrorInModal('Gagal mengklaim sesi transaksi.');
            }
        });
    }

    // ==========================================
    // LOGIKA SCANNER FISIK MODUL
    // ==========================================
    barcodeInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault(); 
            const scannedText = this.value.trim();
            this.value = ''; 
            
            if (scannedText === '' || isProcessingScan) return;
            
            isProcessingScan = true;
            hideModalResults();
            showModalLoading(true);

            scannerMessage.textContent = 'Mencari modul...';
            scannerMessage.className = 'alert alert-light mb-2 text-center';
            getBookDetailsAndAddToCart(scannedText);
        }
    });

    function getBookDetailsAndAddToCart(itemCode) {
        const msgDiv = document.getElementById('mainScannerMessage');
        fetch('/cart-loan/add-modul-to-cart', {
            method: 'POST', headers: header_data,
            body: JSON.stringify({ item_code: itemCode })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status) {
                msgDiv.innerHTML = `<div class="alert alert-success py-2 mb-0 text-center"><i class="fas fa-check-circle me-2"></i>Buku berhasil ditambahkan!</div>`;
                refreshMainPageCart(); // Update UI Keranjang
            } else {
                msgDiv.innerHTML = `<div class="alert alert-danger py-2 mb-0 text-center"><i class="fas fa-exclamation-circle me-2"></i>${data.message || 'Gagal menambahkan buku.'}</div>`;
            }
            
            // Bersihkan pesan error/sukses setelah 2 detik dan pastikan fokus kembali ke input
            setTimeout(() => { 
                isProcessingScan = false; 
                if(userAuthorized) {
                    msgDiv.innerHTML = '';
                    const mainBarcodeInput = document.getElementById('mainBarcodeInput');
                    if(mainBarcodeInput) mainBarcodeInput.focus();
                }
            }, 2500);
        })
        .catch(err => {
            isProcessingScan = false;
            if(msgDiv) {
                msgDiv.innerHTML = `<div class="alert alert-danger py-2 mb-0 text-center">Terjadi kesalahan sistem saat menghubungi server.</div>`;
            }
            console.error("Error Add Cart:", err);
        });
    }

    // ==========================================
    // FUNGSI RENDER KERANJANG MODUL
    // ==========================================
    function initializeMainPageCart() {
        if (authorizedUserData) {
            mainPageUserInfo.innerHTML = `
                <div class="alert alert-light border-0 h-100 shadow-sm d-flex flex-column justify-content-center">
                    <h3 class="fw-bolder">Halo, ${authorizedUserData.member_name}!</h3>
                    <p class="mb-3 text-muted">Sistem siap. Silakan langsung scan barcode fisik buku Anda.</p>
                    
                    <div id="mainScannerMessage" class="mt-2 w-100" style="min-height: 40px;"></div>
                </div>`;
        }
        
        mainPageCartContainer.style.display = 'block';
        if (launchScannerBtn) launchScannerBtn.style.display = 'none';
        if (aturan) aturan.style.display = 'none';
        
        refreshMainPageCart();
    }

    function refreshMainPageCart() {
        let cartIsEmpty = true; 
        showMainPageLoading(true); 

        mainPageAlert.style.display = 'none';

        fetch('/cart-loan/cart-modul-items', { method: 'GET', headers: header_data })
            .then(res => res.json())
            .then(data => {
                if (!data.status) throw new Error(data.message || 'Gagal memuat keranjang');

                const cartData = data.data;
                cartIsEmpty = cartData.total_items === 0; 

                let itemsHtml = `<div class="alert alert-light text-center p-4"><i class="fas fa-box-open fa-3x mb-3 text-muted"></i><br>Keranjang kosong. Silakan scan modul.</div>`;
                if (cartData.cart_items && cartData.cart_items.length > 0) {
                    itemsHtml = `<ul class="list-group shadow-sm">
                            ${cartData.cart_items.map(item => `
                                <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                                    <div>
                                        <h5 class="mb-1 fw-bold text-dark">${item.title}</h5>
                                        <div class="text-muted small">Kode Modul: <span class="badge badge-light-primary">${item.item_code}</span></div>
                                        ${item.author ? `<div class="text-muted small mt-1">Penulis: ${item.author}</div>` : ''}
                                    </div>
                                    <button type="button" class="btn btn-sm btn-light-danger px-3 rounded-pill" 
                                            onclick="removeModulFromCart('${item.item_code}', '${item.title.replace(/'/g, "\\'").replace(/"/g, "&quot;")}')" 
                                            title="Hapus modul ini">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </li>
                            `).join('')}
                        </ul>`;
                }
                mainPageCartList.innerHTML = itemsHtml;

                mainPageCartSummary.innerHTML = cartIsEmpty ? '' : `
                    <div class="d-flex justify-content-end align-items-center mt-4 p-3 bg-light rounded">
                        <h5 class="mb-0">Total Modul: <strong>${cartData.total_items}</strong></h5>
                    </div>
                `;
            })
            .catch(error => {
                showMainPageAlert('danger', error.message || 'Gagal memuat keranjang.');
            })
            .finally(() => {
                showMainPageLoading(false);
                mainCheckoutBtn.disabled = cartIsEmpty;
            });
    }

    window.removeModulFromCart = function (itemCode, itemTitle) {
        Swal.fire({
            title: "Hapus modul ini?",
            text: `Anda yakin ingin menghapus "${itemTitle}" dari keranjang?`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Ya, Hapus!",
            cancelButtonText: "Batal",
            customClass: { confirmButton: "btn btn-danger", cancelButton: "btn btn-secondary" }
        }).then(function (result) {
            if (result.isConfirmed) {
                fetch('/cart-loan/cart-item', {  // Menggunakan rute universal delete cart
                    method: 'DELETE',
                    headers: header_data,
                    body: JSON.stringify({ item_code: itemCode })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status) {
                        refreshMainPageCart();
                    } else {
                        Swal.fire('Gagal', data.message, 'error');
                    }
                });
            }
        });
    }

    // ==========================================
    // LOGIKA CHECKOUT & BATAL
    // ==========================================
    if (mainCheckoutBtn) {
        mainCheckoutBtn.addEventListener('click', () => {
            Swal.fire({
                title: "Lakukan peminjaman",
                text: "Apakah kamu yakin ingin menyelesaikan proses peminjaman modul?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Ya, Selesaikan!",
                cancelButtonText: "Batal",
                customClass: { confirmButton: "btn btn-success", cancelButton: "btn btn-secondary" }
            }).then(function (result) {
                if (result.isConfirmed) {
                    processFinalCheckoutOnMainPage();
                }
            });
        });
    }

    if (resetLoanBtn) {
        resetLoanBtn.addEventListener('click', () => {
            Swal.fire({
                title: "Tutup Sesi?",
                text: "Keranjang akan dikosongkan dan tidak dapat dikembalikan",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Ya, Tutup",
                cancelButtonText: "Batal",
                customClass: { confirmButton: "btn btn-danger", cancelButton: "btn btn-secondary" }
            }).then(function (result) {
                if (result.isConfirmed) {
                    clearCartOnServer();
                }
            });
        });
    }

    function processFinalCheckoutOnMainPage() {
        showMainPageLoading(true);

        fetch('/loan/complete-loan', {
            method: 'POST', headers: header_data, body: JSON.stringify({}) 
        })
        .then(res => res.json())
        .then(data => {
            if (data.status) {
                isCheckoutComplete = true;
                triggerAutoPrint(data.data);
                showFinalSuccess(data.data);
                setTimeout(() => { location.reload(); }, 7000);
            } else {
                Swal.fire('Gagal', data.message || 'Gagal memproses peminjaman', 'error');
            }
        })
        .catch(error => {
            showMainPageAlert('danger', 'Terjadi kesalahan sistem saat memproses peminjaman');
        })
        .finally(() => {
            showMainPageLoading(false);
        });
    }

    function showFinalSuccess(loanData) {
        mainPageCartContainer.innerHTML = `
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8 col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-4">
                            <div class="text-center">
                                <div class="mb-4">
                                    <i class="fas fa-check-circle fa-4x text-success"></i>
                                </div>
                                <h4 class="text-success mb-3">Peminjaman Berhasil!</h4>
                                <div class="alert alert-success" role="alert">
                                    <strong>Total modul dipinjam: ${loanData.total_borrowed}</strong><br>
                                </div>
                                <div class="mt-4 text-start">
                                    <h6>Modul yang Dipinjam:</h6>
                                    ${(loanData.borrowed_items || []).map(item => `
                                        <div class="card mb-2">
                                            <div class="card-body p-2">
                                                <small class="text-muted">Kode: ${item.item_code}</small><br>
                                                <strong>${item.title}</strong><br>
                                                <small class="text-success"><i class="fas fa-clock me-1"></i>Batas kembali: ${item.due_date}</small>
                                            </div>
                                        </div>
                                    `).join('')}
                                </div>
                                <button type="button" class="btn btn-primary rounded-pill px-4 mt-3" onclick="finishTransaction()">
                                    <i class="fas fa-home me-2"></i>Selesai
                                </button>
                                <p class="text-muted mt-2 small">Halaman akan dimuat ulang otomatis dalam 7 detik...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        mainPageCartContainer.scrollIntoView({ behavior: 'smooth' });
    }

    window.finishTransaction = function () {
        location.reload();
    }

    // --- FUNGSI UI ---
    function showErrorInModal(message) {
        scannerMessage.textContent = message;
        scannerMessage.className = 'alert alert-danger mb-2 text-center';
        hideModalResults();
    }

    function showTemporaryScannerMessage(message, type = 'info') {
        scannerMessage.textContent = message;
        scannerMessage.className = `alert alert-${type} mb-2 text-center`;
    }

    function showModalLoading(isLoading) {
        loadingIndicator.style.display = isLoading ? 'block' : 'none';
    }

    function hideModalResults() {
        loadingIndicator.style.display = 'none';
    }

    function showMainPageLoading(isLoading) {
        if (isLoading) {
            mainCheckoutBtn.disabled = true;
            resetLoanBtn.disabled = true;
            const scanBtn = document.getElementById('scanMoreBtn');
            if (scanBtn) scanBtn.disabled = true;
            mainCheckoutBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memproses...';
        } else {
            resetLoanBtn.disabled = false;
            const scanBtn = document.getElementById('scanMoreBtn');
            if (scanBtn) scanBtn.disabled = false;
            mainCheckoutBtn.innerHTML = '<i class="fas fa-check me-2"></i> Proses Checkout';
        }
    }

    function showMainPageAlert(type, message) {
        mainPageAlert.innerHTML = `<div class="alert alert-${type}">${message}</div>`;
        mainPageAlert.style.display = 'block';
    }

    function clearCartOnServer() {
        if (!header_data['X-CSRF-TOKEN']) return;
        fetch('/cart-loan/cart-clear', { method: 'DELETE', headers: header_data })
            .then(() => location.reload())
            .catch(() => location.reload());
    }

    function triggerAutoPrint(loanData) {
        // Ambil data buku dan info member yang terotorisasi
        const items = loanData.print_receipts || loanData.borrowed_items || [];
        const memberName = authorizedUserData ? authorizedUserData.member_name : 'Anggota';
        const memberId = authorizedUserData ? authorizedUserData.nomor_induk : '';
        
        // Susun URL printer ke Route khusus dengan query parameter data struk
        const printUrl = `/print/struk?data=${encodeURIComponent(JSON.stringify(items))}&member_name=${encodeURIComponent(memberName)}&member_id=${encodeURIComponent(memberId)}`;
        
        // Buka jendela/pop-up kecil baru di latar belakang untuk melakukan cetak otomatis
        let iframe = document.getElementById('silent-print-iframe');
        if (!iframe) {
            iframe = document.createElement('iframe');
            iframe.id = 'silent-print-iframe';
            iframe.style.position = 'absolute';
            iframe.style.width = '0px';
            iframe.style.height = '0px';
            iframe.style.border = 'none';
            document.body.appendChild(iframe);
        }
        
        // Masukkan URL struk ke dalam iframe tersebut
        iframe.src = printUrl;
    }

});