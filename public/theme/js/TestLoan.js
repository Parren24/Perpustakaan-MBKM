document.addEventListener('DOMContentLoaded', (event) => {
    let userAuthorized = false;
    let isProcessingScan = false;
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

    const launchScannerBtn = document.getElementById('launchScannerBtn');
    const aturan = document.getElementById('aturan');

    const mainPageCartContainer = document.getElementById('mainPageCartContainer');
    const mainPageUserInfo = document.getElementById('mainPageUserInfo');
    const mainPageCartList = document.getElementById('mainPageCartList');
    const mainPageCartSummary = document.getElementById('mainPageCartSummary');
    const mainCheckoutBtn = document.getElementById('mainCheckoutBtn');
    const resetLoanBtn = document.getElementById('resetLoanBtn');

    const header_data = {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        'Accept': 'application/json'
    }

    launchScannerBtn.addEventListener('click', () => {
        scanModal.show();
    });
    window.addEventListener('keypress', function (e) {
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
            if (mainScannerMessage) {
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

    scanModalElement.addEventListener('shown.bs.modal', () => {
        isProcessingScan = false;
        const scanModalLabel = document.getElementById('scanModalLabel');

        if (!userAuthorized) {
            // MODE TAMPILKAN QR KIOS
            kiosQrContainer.style.display = 'block';
            barcodeInputContainer.style.display = 'none';
            if (scanModalLabel) scanModalLabel.innerHTML = '<i class="fas fa-qrcode me-2"></i> Pindai QR Code';

            generateKiosQr();
        } else {
            // MODE SCANNER FISIK BUKU
            kiosQrContainer.style.display = 'none';
            barcodeInputContainer.style.display = 'block';
            if (scanModalLabel) scanModalLabel.innerHTML = '<i class="fas fa-qrcode me-2"></i> Lakukan peminjaman';

            scannerMessage.textContent = 'Gunakan mesin scanner fisik ke barcode buku...';
            scannerMessage.className = 'alert alert-info mb-2 text-center';
            barcodeInput.value = '';
            barcodeInput.focus();
        }
    });

    scanModalElement.addEventListener('hidden.bs.modal', () => {
        if (pollingInterval) clearInterval(pollingInterval);
        barcodeInput.value = '';
    });

    barcodeInput.addEventListener('blur', () => {
        if (scanModalElement.classList.contains('show') && userAuthorized) {
            barcodeInput.focus();
        }
    });

    function handleApiResponse(data) {
            isProcessingScan = false;

            Swal.fire({
                icon: 'warning',
                title: 'Sesi Berakhir',
                text: data.message || 'Sesi Anda telah berakhir. Silakan scan QR code lagi.',
                confirmButtonText: 'OK'
            }).then(() => {
                // Reset state kios ke kondisi awal (belum login)
                userAuthorized = false;
                authorizedUserData = null;
                currentSessionId = null;

                mainPageCartContainer.style.display = 'none';
                if (launchScannerBtn) launchScannerBtn.style.display = 'block';
                if (aturan) aturan.style.display = 'block';

                scanModal.show(); // buka lagi modal QR
                generateKiosQr(); // Generate ulang QR saat modal terbuka kembali
            });
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
                    scannerMessage.textContent = 'Gagal memuat QR Code.';
                    scannerMessage.className = 'alert alert-danger mb-2 text-center';
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
                    scannerMessage.textContent = 'Waktu QR habis. Silakan tutup dan buka lagi.';
                    scannerMessage.className = 'alert alert-danger mb-2 text-center';
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
                    scannerMessage.textContent = 'Gagal mengklaim sesi transaksi.';
                    scannerMessage.className = 'alert alert-danger mb-2 text-center';
                }
            });
    }

    // ==========================================
    // LOGIKA SCANNER FISIK BUKU
    // ==========================================
    barcodeInput.addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            const scannedText = this.value.trim();
            this.value = '';

            if (scannedText === '' || isProcessingScan) return;

            isProcessingScan = true;
            loadingIndicator.style.display = 'block';
            scannerMessage.textContent = 'Mencari buku...';
            scannerMessage.className = 'alert alert-light mb-2 text-center';

            getBookDetailsAndAddToCart(scannedText);
        }
    });

    function getBookDetailsAndAddToCart(itemCode) {
        const msgDiv = document.getElementById('mainScannerMessage'); // Targetkan div alert yang baru

        fetch('/cart-loan/add-to-cart', {
            method: 'POST', headers: header_data,
            body: JSON.stringify({ item_code: itemCode })
        })
            .then(res => res.json())
            .then(data => {
                if (data.expired === true) {
                    return handleApiResponse(data);
                }
                if (data.status) {
                    msgDiv.innerHTML = `<div class="alert alert-success py-2 mb-0 text-center"><i class="fas fa-check-circle me-2"></i>Buku berhasil ditambahkan!</div>`;
                    refreshMainPageCart(); // Update UI Keranjang
                } else {
                    msgDiv.innerHTML = `<div class="alert alert-danger py-2 mb-0 text-center"><i class="fas fa-exclamation-circle me-2"></i>${data.message || 'Gagal menambahkan buku.'}</div>`;
                }

                // Bersihkan pesan error/sukses setelah 2 detik dan pastikan fokus kembali ke input
                setTimeout(() => {
                    isProcessingScan = false;
                    if (userAuthorized) {
                        msgDiv.innerHTML = '';
                        const mainBarcodeInput = document.getElementById('mainBarcodeInput');
                        if (mainBarcodeInput) mainBarcodeInput.focus();
                    }
                }, 5500);
            })
            .catch(err => {
                isProcessingScan = false;
                if (msgDiv) {
                    msgDiv.innerHTML = `<div class="alert alert-danger py-2 mb-0 text-center">Terjadi kesalahan sistem saat menghubungi server.</div>`;
                }
                console.error("Error Add Cart:", err);
            });
    }

    // ==========================================
    // FUNGSI RENDER KERANJANG
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
        mainPageCartList.innerHTML = '<div class="text-center p-3"><div class="spinner-border text-primary"></div></div>';

        fetch('/cart-loan/cart-items', { method: 'GET', headers: header_data })
            .then(res => res.json())
            .then(data => {
                if (data.expired === true) {
                    return handleApiResponse(data);
                }
                if (!data.status) {
                    mainPageCartList.innerHTML = `<div class="alert alert-danger">${data.message || 'Gagal memuat keranjang'}</div>`;
                    return;
                }

                // Mengambil object di dalam data.data
                const cartData = data.data;
                mainCheckoutBtn.disabled = cartData.total_items === 0;

                if (cartData.total_items > 0) {
                    let itemsHtml = '<ul class="list-group shadow-sm">';
                    cartData.cart_items.forEach(item => {
                        const safeTitle = item.title ? item.title.replace(/'/g, "\\'").replace(/"/g, "&quot;") : 'Tanpa Judul';
                        itemsHtml += `
                        <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                            <div>
                                <h5 class="mb-1 fw-bold text-dark">${item.title}</h5>
                                <div class="text-muted small">Kode Buku: <span class="badge bg-primary">${item.item_code}</span></div>
                            </div>
                            <button class="btn btn-sm btn-light-danger px-3 rounded-pill" onclick="removeBookFromCart('${item.item_code}', '${safeTitle}')">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </li>
                    `;
                    });
                    itemsHtml += '</ul>';
                    mainPageCartList.innerHTML = itemsHtml;

                    mainPageCartSummary.innerHTML = `
                    <div class="d-flex justify-content-end align-items-center mt-4 p-3 bg-light rounded">
                        <h5 class="mb-0">Total: <strong>${cartData.total_items}</strong> Buku di Keranjang</h5>
                    </div>
                `;
                } else {
                    mainPageCartList.innerHTML = `<div class="alert text-center p-4"><i class="fas fa-box-open fa-3x text-muted"></i><br>Keranjang kosong. Silakan scan barcode buku.</div>`;
                    mainPageCartSummary.innerHTML = '';
                }
            })
            .catch(err => {
                console.error("Fetch Cart Error:", err);
                mainPageCartList.innerHTML = `<div class="alert alert-danger">Terjadi kesalahan jaringan saat memuat keranjang.</div>`;
            });
    }

    // Fungsi untuk memicu cetak struk setelah transaksi sukses
    function triggerAutoPrint(loanData) {
        // Ambil data buku dan info member yang terotorisasi
        const items = loanData.print_receipts || loanData.borrowed_items || [];
        const memberName = authorizedUserData ? authorizedUserData.member_name : 'Anggota';
        const memberId = authorizedUserData ? authorizedUserData.member_id : '';

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

    window.removeBookFromCart = function (itemCode, itemTitle) {
        Swal.fire({
            title: "Hapus buku?",
            text: `Keluarkan "${itemTitle}" dari keranjang?`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Ya, Hapus!",
            cancelButtonText: "Batal",
            customClass: { confirmButton: "btn btn-danger", cancelButton: "btn btn-secondary" }
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('/cart-loan/cart-item', {
                    method: 'DELETE', headers: header_data,
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
    mainCheckoutBtn.addEventListener('click', () => {
        Swal.fire({
            title: "Lakukan peminjaman",
            text: "Apakah kamu yakin ingin menyelesaikan proses peminjaman?",
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "Ya, Selesaikan!",
            cancelButtonText: "Batal",
            customClass: { confirmButton: "btn btn-success", cancelButton: "btn btn-secondary" }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({ title: 'Memproses...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });

                fetch('/loan/complete-loan', { method: 'POST', headers: header_data })
                    .then(res => res.json())
                    .then(data => {
                        if (data.expired === true) {
                            return handleApiResponse(data);
                        }
                        if (data.status) {
                            triggerAutoPrint(data.data);
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: 'Buku berhasil dipinjam. Struk sedang dicetak...',
                                timer: 3000,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('Gagal', data.message, 'error');
                        }
                    })
                    .catch(err => Swal.fire('Error', 'Terjadi kesalahan sistem.', 'error'));
            }
        });
    });

    resetLoanBtn.addEventListener('click', () => {
        Swal.fire({
            title: "Tutup Sesi?",
            text: "Keranjang akan dikosongkan dan sesi Anda akan ditutup.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Ya, Tutup",
            cancelButtonText: "Batal",
            customClass: { confirmButton: "btn btn-danger", cancelButton: "btn btn-secondary" }
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('/cart-loan/cart-clear', { method: 'DELETE', headers: header_data })
                    .then(() => location.reload())
                    .catch(() => location.reload());
            }
        });
    });
});