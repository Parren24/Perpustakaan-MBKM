// TAMBAHKAN BARIS INI DI PALING ATAS
document.addEventListener('DOMContentLoaded', (event) => {

    let html5QrCode;
    let isScanning = false;
    let userAuthorized = false;
    let isProcessingScan = false; // Kunci untuk continuous scanning
    let isCheckoutComplete = false; // Flag untuk mencegah clear cart setelah sukses checkout
    let authorizedUserData = null;

    // --- Elemen DOM ---
    const scanModalElement = document.getElementById('scanModal');
    if (!scanModalElement) {
        console.error('Modal element #scanModal not found!');
        return;
    }
    const scanModal = new bootstrap.Modal(scanModalElement);

    // Elemen di dalam Modal
    const readerDiv = document.getElementById('reader');
    const readerContainer = document.getElementById('reader-container');
    const scannerMessage = document.getElementById('scannerMessage');
    const errorResultDiv = document.getElementById('errorResult');
    const errorMessageDiv = document.getElementById('errorMessage');
    const loadingIndicator = document.getElementById('loadingIndicator');

    // Tombol di luar Modal
    const launchScannerBtn = document.getElementById('launchScannerBtn');
    if (!launchScannerBtn) {
        console.error('Button element #launchScannerBtn not found!');
        return;
    }

    // --- Elemen di Halaman Utama (Keranjang) ---
    const mainPageCartContainer = document.getElementById('mainPageCartContainer');
    const mainPageUserInfo = document.getElementById('mainPageUserInfo');
    const mainPageCartList = document.getElementById('mainPageCartList');
    const mainPageCartSummary = document.getElementById('mainPageCartSummary');
    const mainPageAlert = document.getElementById('mainPageAlert');

    // --- Tombol Kontrol Halaman Utama ---
    const mainCheckoutBtn = document.getElementById('mainCheckoutBtn');
    const resetLoanBtn = document.getElementById('resetLoanBtn'); // Tombol Batal
    const scanMoreBtn = document.getElementById('scanMoreBtn'); // Tombol Scan Lagi

    // Header untuk fetch
    const header_data = {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        'Accept': 'application/json'
    }

    // --- Manajemen Modal & Scanner Lifecycle ---

    // 1. Event listener untuk tombol utama di halaman
    launchScannerBtn.addEventListener('click', () => {
        scanModal.show();
    });

    // 2. Saat modal ditampilkan, MULAI scanner
    scanModalElement.addEventListener('shown.bs.modal', () => {
        console.log('Modal shown, starting scanner...');
        isCheckoutComplete = false;
        startScanner();
    });

    // 3. Saat modal ditutup, STOP scanner
    scanModalElement.addEventListener('hidden.bs.modal', () => {
        console.log('Modal hidden, stopping scanner...');
        stopScanner();
        // Tidak ada logika lain, keranjang sudah tampil/update
    });

    // --- Listener untuk Tombol Kontrol di Halaman Utama ---
    if (mainCheckoutBtn) {
        mainCheckoutBtn.addEventListener('click', () => {
            Swal.fire({
                title: "Lakukan peminjaman",
                text: "Apakah kamu yakin ingin melanjutkan ke proses peminjaman?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Ya, Lanjutkan!",
                cancelButtonText: "Batal",
                customClass: {
                    confirmButton: "btn btn-light-primary",
                    cancelButton: "btn btn-light-dark"
                },
                reverseButtons: true
            }).then(function(result) {
                if (result.value) {
                    processFinalCheckoutOnMainPage();
                    
                }
            });
        });
    }

    if (resetLoanBtn) {
        resetLoanBtn.addEventListener('click', () => {
            Swal.fire({
                title: "Batalkan peminjaman?",
                text: "Keranjang akan dikosongkan dan tidak dapat dikembalikan",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Ya, Batalkan!",
                cancelButtonText: "Batal",
                customClass: {
                    confirmButton: "btn btn-light-danger",
                    cancelButton: "btn btn-light-dark"
                },
                reverseButtons: true
            }).then(function(result) {
                if (result.value) {
                    clearCartOnServer();
                    
                }
            });
        });
    }

    if (scanMoreBtn) {
        scanMoreBtn.addEventListener('click', () => {
            scanModal.show();
        });
    }


    // Function called when QR code is successfully scanned
    function onScanSuccess(decodedText, decodedResult) {
        if (isProcessingScan) {
            return;
        }
        // console.log(`Code matched = ${decodedText}`);
        isProcessingScan = true;

        if (!header_data['X-CSRF-TOKEN']) {
            showErrorInModal('CSRF token tidak ditemukan. Refresh halaman dan coba lagi.');
            isProcessingScan = false;
            return;
        }

        hideModalResults();
        showModalLoading(true);

        if (!userAuthorized) {
            // console.log('Authorizing user session with token:', decodedText);
            scannerMessage.textContent = 'Memverifikasi user...';
            authorizeUserSession(decodedText);
        } else {
            console.log('Getting book details for item:', decodedText);
            scannerMessage.textContent = 'Mencari buku...';
            getBookDetailsAndAddToCart(decodedText);
        }
    }

    function onScanFailure(error) {

    }

    // Initialize and start the scanner
    function startScanner() {
        if (isScanning) {
            console.log("Scanner is already running");
            return;
        }

        hideModalResults();
        readerContainer.style.display = 'block';

        // Set pesan awal berdasarkan state
        if (!userAuthorized) {
            document.getElementById('scanModalLabel').textContent = 'Pindai QR Code User';
            scannerMessage.textContent = 'Arahkan kamera ke QR Code Anda untuk memulai...';
        } else {
            document.getElementById('scanModalLabel').textContent = 'Pindai Barcode Buku';
            scannerMessage.textContent = 'Arahkan kamera ke barcode buku...';
        }
        scannerMessage.className = 'alert alert-info mt-2';

        html5QrCode = new Html5Qrcode("reader");

        const config = { fps: 10, qrbox: { width: 250, height: 250 }, aspectRatio: 1.0, rememberLastUsedCamera: true };

        html5QrCode.start({ facingMode: "environment" }, config, onScanSuccess, onScanFailure)
            .then(() => { isScanning = true; console.log("QR Code scanner started (back camera)"); })
            .catch(err => {
                console.error("Unable to start scanning, trying user camera", err);
                html5QrCode.start({ facingMode: "user" }, config, onScanSuccess, onScanFailure)
                    .then(() => { isScanning = true; console.log("QR Code scanner started with front camera"); })
                    .catch(frontErr => {
                        console.error("Unable to start scanning with any camera", frontErr);
                        scannerMessage.textContent = 'Gagal mengakses kamera. Izinkan akses kamera dan coba lagi.';
                        scannerMessage.className = 'alert alert-danger mt-2';
                    });
            });
    }

    // Stop the scanner
    function stopScanner() {
        if (html5QrCode && isScanning) {
            try {
                html5QrCode.stop().then(() => {
                    console.log("QR Code scanning stopped");
                }).catch(err => { console.error("Unable to stop scanning", err); });
            } catch (err) { console.error("Error stopping scanner", err); }
        }
        isScanning = false;
    }

    // Function untuk authorize user session dengan QR token
    // REVISI 1: Fungsi ini sekarang juga menginisiasi keranjang di halaman utama
    function authorizeUserSession(token) {
        fetch('/biblio/authorize-session', {
            method: 'POST',
            headers: header_data,
            body: JSON.stringify({ token: token })
        })
            .then(response => response.json().then(data => ({ ok: response.ok, data })))
            .then(({ ok, data }) => {
                showModalLoading(false);
                if (!ok) {
                    throw new Error(data.message || data.error || 'HTTP error');
                }

                if (data.status) {
                    userAuthorized = true;
                    authorizedUserData = data.data;

                    // --- PERUBAHAN UTAMA (REVISI 1) ---
                    // Panggil inisialisasi cart di halaman utama
                    initializeMainPageCart();
                    // --- AKHIR PERUBAHAN ---

                    // Update UI MODAL
                    document.getElementById('scanModalLabel').textContent = 'Pindai Barcode Buku';
                    showTemporaryScannerMessage(`User ${data.data.member_name} terotorisasi! Silakan scan buku...`, 'success');
                } else {
                    showErrorInModal(data.message || 'Gagal melakukan otorisasi.');
                }
            })
            .catch(error => {
                showModalLoading(false);
                console.error('Error authorizing user:', error);
                showErrorInModal(error.message || 'Terjadi kesalahan saat otorisasi.');
            })
            .finally(() => {
                // Jeda 2 detik sebelum scan berikutnya
                setTimeout(() => {
                    isProcessingScan = false;
                }, 2000);
            });
    }

    // Function untuk get book details dan add to cart
    // REVISI 1: Fungsi ini sekarang me-refresh keranjang di halaman utama
    function getBookDetailsAndAddToCart(itemCode) {
        fetch('/cart-loan/add-to-cart', {
            method: 'POST',
            headers: header_data,
            body: JSON.stringify({ item_code: itemCode })
        })
            .then(response => response.json().then(data => ({ ok: response.ok, data })))
            .then(({ ok, data }) => {
                showModalLoading(false);
                if (!ok) {
                    throw new Error(data.message || data.error || `HTTP error!`);
                }

                if (data.status) {
                    // Tampilkan pesan sukses sementara di modal
                    showTemporaryScannerMessage(`Buku (Kode: ${itemCode}) berhasil ditambahkan!`, 'success');

                    // --- PERUBAHAN UTAMA (REVISI 1) ---
                    // Refresh keranjang di halaman utama (live)
                    refreshMainPageCart();
                    // --- AKHIR PERUBAHAN ---

                } else {
                    console.error('Backend returned error:', data);
                    showErrorInModal(data.message || 'Gagal menambahkan buku ke keranjang.');
                }
            })
            .catch(error => {
                showModalLoading(false);
                console.error('Fetch error details:', error);
                showErrorInModal(error.message || 'Terjadi kesalahan saat menambahkan buku.');
            })
            .finally(() => {
                // Jeda 2 detik sebelum scan berikutnya
                setTimeout(() => {
                    isProcessingScan = false;
                }, 2000);
            });
    }


    // --- FUNGSI UI MODAL (Hanya untuk pesan error/loading) ---

    function showErrorInModal(message) {
        errorMessageDiv.textContent = message;
        errorResultDiv.style.display = 'block';

        scannerMessage.textContent = message;
        scannerMessage.className = 'alert alert-danger mt-2';

        setTimeout(() => {
            if (!userAuthorized) {
                scannerMessage.textContent = 'Arahkan kamera ke QR Code Anda untuk memulai...';
                scannerMessage.className = 'alert alert-info mt-2';
            } else {
                scannerMessage.textContent = 'Silakan scan barcode buku berikutnya...';
                scannerMessage.className = 'alert alert-info mt-2';
            }
            hideModalResults();
        }, 5000);
    }

    function showTemporaryScannerMessage(message, type = 'info') {
        scannerMessage.textContent = message;
        scannerMessage.className = `alert alert-${type} mt-2`;

        setTimeout(() => {
            if (userAuthorized) {
                scannerMessage.textContent = 'Arahkan kamera ke barcode buku...';
                scannerMessage.className = 'alert alert-info mt-2';
            }
        }, 5000);
    }

    function showModalLoading(isLoading) {
        loadingIndicator.style.display = isLoading ? 'block' : 'none';
    }

    function hideModalResults() {
        errorResultDiv.style.display = 'none';
        loadingIndicator.style.display = 'none';
    }

    // --- BARU: Fungsi untuk Halaman Utama (Live Update) ---

    // Dipanggil SEKALI saat user auth berhasil
    function initializeMainPageCart() {
        // Tampilkan info user (sudah ada di global state)
        if (authorizedUserData) {
            mainPageUserInfo.innerHTML = `
                <div class="alert alert-success">
                    <h6 class="mb-2"><i class="fas fa-user-check me-2"></i>User Terotorisasi</h6>
                    <p class="mb-2">Nama: <strong>${authorizedUserData.member_name}</strong></p>
                    <p class="mb-0">Member ID: <strong>${authorizedUserData.nomor_induk || 'N/A'}</strong></p>
                </div>`;
        } else {
            mainPageUserInfo.innerHTML = '';
        }

        // Tampilkan container utama, sembunyikan tombol awal
        mainPageCartContainer.style.display = 'block';
        launchScannerBtn.style.display = 'none';

        // Muat keranjang untuk pertama kali
        refreshMainPageCart();
    }

    // Dipanggil setiap kali ada perubahan keranjang (tambah buku)
    function refreshMainPageCart() {
        let cartIsEmpty = true; // Asumsikan kosong by default
        showMainPageLoading(true); // Tampilkan loading, nonaktifkan SEMUA tombol

        // Hapus alert lama
        mainPageAlert.style.display = 'none';

        fetch('/cart-loan/cart-items', {
            method: 'GET',
            headers: header_data
        })
            .then(response => response.json().then(data => ({ ok: response.ok, data })))
            .then(({ ok, data }) => {
                if (!ok) throw new Error(data.message || data.error || 'HTTP error');
                if (!data.status) throw new Error(data.message || 'Gagal memuat keranjang');

                const cartData = data.data;
                cartIsEmpty = cartData.total_items === 0; // Update status keranjang

                // Tampilkan item keranjang
                let itemsHtml = `<div class="alert alert-light">Keranjang kosong. Silakan scan buku.</div>`;
                if (cartData.cart_items && cartData.cart_items.length > 0) {
                    itemsHtml = `
                        <ul class="list-group">
                            ${cartData.cart_items.map(item => `
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <h3><strong>${item.title}</strong></h3><br>
                                        <small class="text-muted">Kode: ${item.item_code}</small>
                                        ${item.author ? `<br><small class="text-muted">Penulis: ${item.author}</small>` : ''}
                                    </div>
                                    
                                    <button type="button" class="btn btn-sm btn-outline-danger" 
                                            onclick="removeBookFromCart('${item.item_code}')" 
                                            title="Hapus buku ini">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    </li>
                            `).join('')}
                        </ul>`;
                }
                mainPageCartList.innerHTML = itemsHtml;

                // Tampilkan summary
                mainPageCartSummary.innerHTML = `
                    <hr>
                    <div class="text-end">
                        <h5>Total buku: <strong>${cartData.total_items} / 2</strong></h5>
                        <p class="mb-0">Sisa slot: <strong>${cartData.remaining_slots}</strong></p>
                    </div>
                `;
            })
            .catch(error => {
                console.error('Error refreshing cart:', error);
                showMainPageAlert('danger', error.message || 'Gagal memuat keranjang.');
            })
            .finally(() => {
                // Selesai loading, atur ulang status tombol
                showMainPageLoading(false);
                // Nonaktifkan checkout HANYA jika keranjang kosong
                mainCheckoutBtn.disabled = cartIsEmpty;
            });
    }
    window.removeBookFromCart = function (itemCode) {

    Swal.fire({
        title: "Hapus buku ini?",
        text: `Anda yakin ingin menghapus buku (Kode: ${itemCode}) dari keranjang?`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Ya, Hapus!",
        cancelButtonText: "Batal",
        customClass: {
            confirmButton: "btn btn-light-danger",
            cancelButton: "btn btn-light-dark"
        },
        reverseButtons: true
    }).then(function(result) {
        // Hanya jalankan jika user mengklik "Ya, Hapus!"
        if (result.value) { 
            
            // Tampilkan pesan sementara
            showMainPageAlert('info', `Menghapus buku (Kode: ${itemCode})...`);

            fetch('/cart-loan/cart-item', { // Endpoint ini dari file JS asli Anda
                method: 'DELETE',
                headers: header_data,
                body: JSON.stringify({
                    item_code: itemCode
                })
            })
            .then(response => response.json().then(data => ({ ok: response.ok, data })))
            .then(({ ok, data }) => {
                if (!ok) {
                    throw new Error(data.message || data.error || 'HTTP error');
                }
                if (!data.status) {
                    throw new Error(data.message || 'Gagal menghapus buku');
                }

                // Sukses! Beri pesan dan panggil refresh
                console.log('Buku dihapus, me-refresh keranjang...');
                showMainPageAlert('success', `Buku (Kode: ${itemCode}) berhasil dihapus.`);

            })
            .catch(error => {
                console.error('Error removing from cart:', error);
                showMainPageAlert('danger', error.message || 'Terjadi kesalahan saat menghapus buku');
            })
            .finally(() => {
                // Selalu refresh keranjang, baik sukses maupun gagal, untuk sinkronisasi
                // Fungsi refreshMainPageCart akan mengurus loading state-nya sendiri
                refreshMainPageCart();
            });
        }
        // Jika result.value false (klik "Batal"), tidak terjadi apa-apa
    });
}
    // Fungsi untuk checkout DI HALAMAN UTAMA (Sama seperti sebelumnya)
    function processFinalCheckoutOnMainPage() {
        showMainPageLoading(true);

        fetch('/loan/complete-loan', {
            method: 'POST',
            headers: header_data,
            body: JSON.stringify({}) // Body kosong, auth via session
        })
            .then(response => response.json().then(data => ({ ok: response.ok, data })))
            .then(({ ok, data }) => {
                if (!ok) throw new Error(data.message || data.error || 'HTTP error');

                if (data.status) {
                    isCheckoutComplete = true;

                    const successMessage = `Peminjaman Berhasil! Total ${data.data.total_borrowed} buku dipinjam. Batas pengembalian: ${data.data.due_date}. Halaman akan dimuat ulang dalam 5 detik.`;
                    showMainPageAlert('success', successMessage);

                    // Sembunyikan semua tombol kontrol
                    mainCheckoutBtn.style.display = 'none';
                    resetLoanBtn.style.display = 'none';
                    scanMoreBtn.style.display = 'none';

                    setTimeout(() => {
                        location.reload();
                    }, 5000);

                } else {
                    throw new Error(data.message || 'Gagal memproses peminjaman');
                }
            })
            .catch(error => {
                console.error('Error processing checkout:', error);
                showMainPageAlert('danger', error.message || 'Terjadi kesalahan saat memproses peminjaman');
            })
            .finally(() => {
                showMainPageLoading(false);
            });
    }

    // --- Fungsi-fungsi helper Halaman Utama ---

    function showMainPageLoading(isLoading) {
        if (isLoading) {
            mainCheckoutBtn.disabled = true;
            resetLoanBtn.disabled = true;
            scanMoreBtn.disabled = true;
            mainCheckoutBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memproses...';
        } else {
            // Status disabled akan diatur oleh refreshMainPageCart
            resetLoanBtn.disabled = false;
            scanMoreBtn.disabled = false;
            mainCheckoutBtn.innerHTML = '<i class="fas fa-check me-2"></i>Proses Checkout';
        }
    }

    function showMainPageAlert(type, message) {
        mainPageAlert.innerHTML = `<div class="alert alert-${type}">${message}</div>`;
        mainPageAlert.style.display = 'block';
    }


    // --- Fungsi Reset Total (Dipakai tombol Batal) ---
    // function resetFullProcess() {
    //     if (userAuthorized) {
    //         // Hanya clear cart jika user sempat auth
    //         clearCartOnServer();
    //     }

    //     // Reset state global
    //     userAuthorized = false;
    //     authorizedUserData = null;
    //     isCheckoutComplete = false;

    //     // Sembunyikan cart di main page
    //     mainPageCartContainer.style.display = 'none';
    //     mainPageAlert.style.display = 'none';

    //     // Tampilkan lagi tombol "Mulai Peminjaman"
    //     launchScannerBtn.style.display = 'inline-block';

    //     // Tampilkan kembali tombol yang mungkin disembunyikan
    //     mainCheckoutBtn.style.display = 'inline-block';
    //     resetLoanBtn.style.display = 'inline-block';
    //     scanMoreBtn.style.display = 'inline-block';

    //     // Reset UI modal (untuk persiapan jika dibuka lagi)
    //     resetModalToAuth();
    // }

    // // Reset UI modal ke state awal (scan auth)
    // function resetModalToAuth() {
    //     const scanModalLabel = document.getElementById('scanModalLabel');
    //     if (scanModalLabel) scanModalLabel.textContent = 'Pindai QR Code User';

    //     if (readerContainer) readerContainer.style.display = 'block';
    //     scannerMessage.textContent = 'Arahkan kamera ke QR Code Anda untuk memulai...';
    //     scannerMessage.className = 'alert alert-info mt-2';
    // }

    // Function untuk clear cart DI SERVER
    function clearCartOnServer() {
        if (!header_data['X-CSRF-TOKEN']) return;

        fetch('/cart-loan/cart-clear', {
            method: 'DELETE',
            headers: header_data
        })
            .then(response => response.json())
            .then(data => {
                if (data.status) {
                    console.log('Server cart cleared successfully.');
                    location.reload();
                } else {
                    console.error('Failed to clear server cart:', data.message);
                }
            })
            .catch(error => {
                console.error('Error clearing server cart:', error);
            });
    }

    // Clean up
    window.addEventListener('beforeunload', function () {
        stopScanner();
    });
});