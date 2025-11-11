// TAMBAHKAN BARIS INI DI PALING ATAS
document.addEventListener('DOMContentLoaded', (event) => {

    let html5QrCode;
    let isScanning = false;
    let userAuthorized = false;
    let isProcessingScan = false; // Kunci untuk continuous scanning
    let isCheckoutComplete = false; // Flag untuk mencegah clear cart setelah sukses checkout

    // --- Elemen DOM ---
    // Ambil elemen modal dari Bootstrap
    const scanModalElement = document.getElementById('scanModal');
    // Cek jika elemen ada sebelum membuat modal
    if (!scanModalElement) {
        console.error('Modal element #scanModal not found!');
        return;
    }
    const scanModal = new bootstrap.Modal(scanModalElement);

    // Elemen di dalam Modal
    const readerDiv = document.getElementById('reader');
    const readerContainer = document.getElementById('reader-container');
    const scannerMessage = document.getElementById('scannerMessage');
    const cartContainer = document.getElementById('cart-container');
    const bookResultDiv = document.getElementById('bookResult');
    const bookDetailsDiv = document.getElementById('bookDetails');
    const errorResultDiv = document.getElementById('errorResult');
    const errorMessageDiv = document.getElementById('errorMessage');
    const loadingIndicator = document.getElementById('loadingIndicator');
    const modalFooterControls = document.getElementById('modal-footer-controls');
    const checkoutButton = document.getElementById('checkoutButton');

    // Tombol di luar Modal
    const launchScannerBtn = document.getElementById('launchScannerBtn');
    // Cek jika tombol ada sebelum menambah listener
    if (!launchScannerBtn) {
        console.error('Button element #launchScannerBtn not found!');
        return;
    }

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
        isCheckoutComplete = false; // Reset status checkout
        startScanner();
    });

    // 3. Saat modal ditutup, STOP scanner dan RESET
    scanModalElement.addEventListener('hidden.bs.modal', () => {
        console.log('Modal hidden, stopping scanner...');
        stopScanner();
        resetResults(); // Reset UI dan state

        // Jika ditutup TANPA checkout, bersihkan cart di server
        if (!isCheckoutComplete) {
            console.log('Modal closed without checkout, clearing server cart.');
            clearCartOnServer();
        }
    });

    // Function called when QR code is successfully scanned
    function onScanSuccess(decodedText, decodedResult) {
        // Kebutuhan 3: Continuous Scanning
        // Jika scan sebelumnya masih diproses, abaikan scan baru ini
        if (isProcessingScan) {
            console.log('Scan in progress, ignoring new scan...');
            return;
        }

        console.log(`Code matched = ${decodedText}`);
        isProcessingScan = true; // Kunci proses

        // Validate CSRF token exists
        if (!header_data['X-CSRF-TOKEN']) {
            showError('CSRF token tidak ditemukan. Refresh halaman dan coba lagi.');
            isProcessingScan = false;
            return;
        }

        // PENTING: Jangan panggil stopScanner() di sini untuk continuous scanning

        hideAllResults();
        showLoading();

        // Handle berbeda berdasarkan mode scan
        if (!userAuthorized) {
            // Mode scan QR user untuk authorize session
            console.log('Authorizing user session with token:', decodedText);
            scannerMessage.textContent = 'Memverifikasi user...';
            authorizeUserSession(decodedText);

        } else if (userAuthorized) {
            // Mode scan barcode buku
            console.log('Getting book details for item:', decodedText);
            scannerMessage.textContent = 'Mencari buku...';
            getBookDetailsAndAddToCart(decodedText);

        } else {
            hideLoading();
            showError('Scan mode tidak diketahui. Silakan reset.');
            isProcessingScan = false;
        }
    }

    function onScanFailure(error) {
        // Biarkan, tidak perlu spam console
    }

    // Initialize and start the scanner
    function startScanner() {
        if (isScanning) {
            console.log("Scanner is already running");
            return;
        }

        // Sembunyikan hasil sebelumnya, tampilkan scanner
        hideAllResults();
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

        const config = {
            fps: 10,
            qrbox: {
                width: 250,
                height: 250
            },
            aspectRatio: 1.0,
            rememberLastUsedCamera: true
        };

        html5QrCode.start({
            facingMode: "environment"
        },
            config,
            onScanSuccess,
            onScanFailure
        ).then(() => {
            isScanning = true;
            console.log("QR Code scanner started successfully (back camera)");
        }).catch(err => {
            console.error("Unable to start scanning, trying user camera", err);
            // Coba kamera depan jika gagal
            html5QrCode.start({
                facingMode: "user"
            },
                config,
                onScanSuccess,
                onScanFailure
            ).then(() => {
                isScanning = true;
                console.log("QR Code scanner started with front camera");
            }).catch(frontErr => {
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
                    isScanning = false;
                }).catch(err => {
                    console.error("Unable to stop scanning", err);
                });
            } catch (err) {
                console.error("Error stopping scanner (already stopped?)", err);
            }
        }
        isScanning = false;
    }

    // Function untuk authorize user session dengan QR token
    function authorizeUserSession(token) {
        fetch('/biblio/authorize-session', {
            method: 'POST',
            headers: header_data,
            body: JSON.stringify({
                token: token
            })
        })
            .then(response => response.json().then(data => ({ ok: response.ok, data })))
            .then(({ ok, data }) => {
                hideLoading();
                if (!ok) {
                    throw new Error(data.message || data.error || 'HTTP error');
                }

                console.log('Authorization response:', data);
                if (data.status) {
                    userAuthorized = true;
                    // Update UI untuk menunjukkan user sudah ter-authorize
                    showUserAuthorized(data.data);
                    // Load cart yang mungkin sudah ada
                    loadAndDisplayCart();
                    // Update UI untuk mode scan buku
                    updateUIForBookScan();
                } else {
                    showError(data.message || 'Gagal melakukan otorisasi.');
                }
            })
            .catch(error => {
                hideLoading();
                console.error('Error authorizing user:', error);
                showError(error.message || 'Terjadi kesalahan saat otorisasi. Silahkan Generate ulang QR Code Anda.');
            })
            .finally(() => {
                // JANGAN langsung buka kunci
                // isProcessingScan = false; 

                // Mulai jeda (cooldown)
                console.log('Scan berhasil diproses. Memulai jeda 2 detik...');

                // Kita juga bisa beri feedback visual selama jeda
                scannerMessage.textContent = 'Scan berhasil! Menyiapkan scanner...';
                scannerMessage.className = 'alert alert-light mt-2'; // Warna netral

                setTimeout(() => {
                    isProcessingScan = false; // Buka kunci scan SETELAH jeda
                    console.log('Jeda selesai. Siap untuk scan berikutnya.');

                    // Kembalikan pesan scanner ke normal (jika user masih auth)
                    if (userAuthorized) {
                        // Cek sisa slot dari tombol checkout
                        const totalBukuText = checkoutButton.textContent.match(/\((\d+)\s/);
                        const totalBuku = totalBukuText ? parseInt(totalBukuText[1]) : 0;

                        if (totalBuku < 2) {
                            scannerMessage.textContent = 'Silakan scan barcode buku berikutnya...';
                            scannerMessage.className = 'alert alert-info mt-2';
                        } else {
                            scannerMessage.textContent = 'Batas maksimal 2 buku tercapai.';
                            scannerMessage.className = 'alert alert-warning mt-2';
                        }
                    }
                }, 5000); // 5000ms = 5 detik jeda. Anda bisa ubah angka ini.
            });
    }

    // Function untuk get book details dan add to cart
    function getBookDetailsAndAddToCart(itemCode) {
        // Kita tidak perlu getBookDetails terpisah karena addBookToCartLoan sudah
        // melakukan semua validasi (ketersediaan, duplikat, dll) di backend
        // Ini lebih efisien, 1 request network, bukan 2

        fetch('/biblio/add-to-cart', {
            method: 'POST',
            headers: header_data,
            body: JSON.stringify({
                item_code: itemCode
            })
        })
            .then(response => response.json().then(data => ({ ok: response.ok, data })))
            .then(({ ok, data }) => {
                hideLoading();
                if (!ok) {
                    // Jika response tidak OK, lempar error dengan pesan dari backend
                    throw new Error(data.message || data.error || `HTTP error!`);
                }

                console.log('Add to cart response data:', data);
                if (data.status) {
                    // Tampilkan pesan sukses dan info cart
                    showCartSuccess(data.data, itemCode);
                    // Update cart (loadAndDisplayCart akan dipanggil di dalam showCartSuccess)
                } else {
                    console.error('Backend returned error:', data);
                    showError(data.message || 'Gagal menambahkan buku ke keranjang.');
                }
            })
            .catch(error => {
                hideLoading();
                console.error('Fetch error details:', error);
                showError(error.message || 'Terjadi kesalahan saat menambahkan buku.');
            })
            .finally(() => {
                isProcessingScan = false; // Buka kunci scan
            });
    }


    // --- Fungsi UI (Semua menargetkan #cart-container di modal) ---

    // Menampilkan info user setelah auth sukses
    function showUserAuthorized(userData) {
        // Buat elemen info user
        const authorizeInfo = document.createElement('div');
        authorizeInfo.className = 'alert alert-success';
        authorizeInfo.id = 'userAuthorizeInfo';
        authorizeInfo.innerHTML = `
            <h6 class="mb-2"><i class="fas fa-user-check me-2"></i>User Terotorisasi!</h6>
            <p class="mb-2">Nama: <strong>${userData.member_name}</strong></p>
            <p class="mb-0">Member ID: <strong>${userData.nomor_induk || 'N/A'}</strong></p>
        `;

        // Tampilkan di cart container
        cartContainer.prepend(authorizeInfo); // prepend agar selalu di atas
        bookResultDiv.style.display = 'block';
    }

    // Update UI ke mode scan buku
    function updateUIForBookScan() {
        document.getElementById('scanModalLabel').textContent = 'Pindai Barcode Buku';
        scannerMessage.textContent = 'Silakan scan barcode buku yang ingin dipinjam...';
        scannerMessage.className = 'alert alert-info mt-2';

        // Tampilkan footer modal dengan tombol checkout
        modalFooterControls.style.display = 'flex';
    }

    // Function untuk load dan tampilkan cart
    function loadAndDisplayCart() {
        showLoading();

        fetch('/biblio/cart-items', {
            method: 'GET',
            headers: header_data
        })
            .then(response => response.json().then(data => ({ ok: response.ok, data })))
            .then(({ ok, data }) => {
                hideLoading();
                if (!ok) {
                    throw new Error(data.message || data.error || 'HTTP error');
                }

                if (data.status) {
                    displayCartOnly(data.data);
                } else {
                    showError(data.message || 'Gagal memuat keranjang');
                }
            })
            .catch(error => {
                hideLoading();
                console.error('Error loading cart:', error);
                showError(error.message || 'Gagal memuat keranjang.');
            });
    }

    // Function HANYA untuk render UI cart
    function displayCartOnly(cartData) {
        // Hapus pesan sukses item sebelumnya (jika ada)
        const oldSuccess = document.getElementById('itemSuccessAlert');
        if (oldSuccess) oldSuccess.remove();

        let cartItemsHtml = '';

        if (cartData.cart_items && cartData.cart_items.length > 0) {
            cartItemsHtml = `
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-list me-2"></i>Buku dalam Keranjang</h6>
                    </div>
                    <div class="card-body">
                        ${cartData.cart_items.map((item, index) => `
                            <div class="d-flex justify-content-between align-items-center ${index > 0 ? 'mt-2 pt-2 border-top' : ''}">
                                <div>
                                    <strong>${item.title || item.item_code}</strong><br>
                                    <small class="text-muted">Kode: ${item.item_code}</small>
                                    ${item.author ? `<br><small class="text-muted">Penulis: ${item.author}</small>` : ''}
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeFromCartKiosk('${item.item_code}')">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        `).join('')}
                    </div>
                </div>
            `;
        } else {
            cartItemsHtml = `
                <div class="alert alert-light text-center">
                    Keranjang masih kosong.
                </div>
            `;
        }

        // Update cart status
        const cartStatusHtml = `
            <div class="alert alert-secondary mt-2">
                <h6 class="mb-2"><i class="fas fa-shopping-cart me-2"></i>Status Keranjang</h6>
                <p class="mb-1">Total buku: <strong>${cartData.total_items} / 2</strong></p>
                <p class="mb-0">Sisa slot: <strong>${cartData.remaining_slots}</strong></p>
            </div>
        `;

        // Gabungkan semua ke bookDetailsDiv
        bookDetailsDiv.innerHTML = cartItemsHtml + cartStatusHtml;
        bookResultDiv.style.display = 'block';

        // Update tombol checkout
        checkoutButton.textContent = `Lanjutkan Pinjam (${cartData.total_items} buku)`;
        if (cartData.total_items > 0) {
            checkoutButton.disabled = false;
        } else {
            checkoutButton.disabled = true;
        }

        // Atur pesan scanner
        if (cartData.remaining_slots > 0) {
            scannerMessage.textContent = 'Silakan scan barcode buku berikutnya...';
            scannerMessage.className = 'alert alert-info mt-2';
        } else {
            scannerMessage.textContent = 'Batas maksimal 2 buku tercapai. Silakan lanjutkan checkout.';
            scannerMessage.className = 'alert alert-warning mt-2';
        }
    }

    // Function untuk show cart success (ketika item berhasil ditambahkan)
    function showCartSuccess(cartData, itemCode) {
        // Hapus pesan sukses item sebelumnya (jika ada)
        const oldSuccess = document.getElementById('itemSuccessAlert');
        if (oldSuccess) oldSuccess.remove();

        // Tampilkan pesan sukses sementara untuk item yang baru ditambahkan
        const successInfo = document.createElement('div');
        successInfo.className = 'alert alert-success';
        successInfo.id = 'itemSuccessAlert';
        successInfo.innerHTML = `
            <i class="fas fa-check-circle me-2"></i>Buku (Kode: <strong>${itemCode}</strong>) berhasil ditambahkan!
        `;
        bookDetailsDiv.prepend(successInfo); // Tampilkan di atas cart

        // Muat ulang tampilan cart
        displayCartOnly(cartData);

        // Atur pesan scanner
        scannerMessage.textContent = 'Buku ditambahkan! Scan buku berikutnya...';
        scannerMessage.className = 'alert alert-success mt-2';

        // Hapus pesan sukses setelah beberapa detik
        setTimeout(() => {
            if (successInfo) successInfo.remove();
            // Kembalikan pesan scanner ke normal
            if (cartData.remaining_slots > 0) {
                scannerMessage.textContent = 'Silakan scan barcode buku berikutnya...';
                scannerMessage.className = 'alert alert-info mt-2';
            }
        }, 3000); // 3 detik
    }

    function showError(message) {
        errorMessageDiv.textContent = message;
        errorResultDiv.style.display = 'block';

        // Juga tampilkan pesan error di area scanner
        scannerMessage.textContent = message;
        scannerMessage.className = 'alert alert-danger mt-2';

        // Sembunyikan pesan error setelah 5 detik
        setTimeout(() => {
            if (!userAuthorized) {
                scannerMessage.textContent = 'Arahkan kamera ke QR Code Anda untuk memulai...';
                scannerMessage.className = 'alert alert-info mt-2';
            } else {
                scannerMessage.textContent = 'Silakan scan barcode buku berikutnya...';
                scannerMessage.className = 'alert alert-info mt-2';
            }
            hideAllResults();
            // Tampilkan kembali cart jika sudah auth
            if (userAuthorized) {
                loadAndDisplayCart();
            }
        }, 5000); // 5 detik
    }

    function showLoading() {
        loadingIndicator.style.display = 'block';
        // Disable tombol checkout saat loading
        if (checkoutButton) checkoutButton.disabled = true;
    }

    function hideLoading() {
        loadingIndicator.style.display = 'none';
        // Enable tombol checkout jika ada item
        // (Akan di-handle oleh displayCartOnly)
    }

    function hideAllResults() {
        // Jangan sembunyikan bookResultDiv jika user sudah auth
        if (userAuthorized) {
            errorResultDiv.style.display = 'none';
        } else {
            bookResultDiv.style.display = 'none';
            errorResultDiv.style.display = 'none';
        }
        loadingIndicator.style.display = 'none';
    }

    // Reset UI dan state
    function resetResults() {
        hideAllResults();

        // Reset state
        userAuthorized = false;
        isProcessingScan = false;
        isCheckoutComplete = false;

        // Hapus info user
        const authorizeInfo = document.getElementById('userAuthorizeInfo');
        if (authorizeInfo) {
            authorizeInfo.remove();
        }

        // Kosongkan cart
        bookDetailsDiv.innerHTML = '';

        // Reset footer
        modalFooterControls.style.display = 'none';
        checkoutButton.disabled = true;
        checkoutButton.textContent = 'Lanjutkan Pinjam (0 buku)';

        // Reset header
        document.getElementById('scanModalLabel').textContent = 'Pindai QR Code User';

        // Tampilkan reader
        readerContainer.style.display = 'block';
        scannerMessage.textContent = 'Arahkan kamera ke QR Code Anda untuk memulai...';
        scannerMessage.className = 'alert alert-info mt-2';
    }

    // Function untuk clear cart DI SERVER
    function clearCartOnServer() {
        fetch('/biblio/cart-clear', {
            method: 'DELETE',
            headers: header_data
        })
            .then(response => response.json())
            .then(data => {
                if (data.status) {
                    console.log('Server cart cleared successfully.');
                } else {
                    console.error('Failed to clear server cart:', data.message);
                }
            })
            .catch(error => {
                console.error('Error clearing server cart:', error);
            });
    }


    // --- Alur Checkout ---

    // NOTE: Make functions global if called from HTML onclick
    // Tapi karena kita di dalam DOMContentLoaded, kita harus attach listener
    // secara dinamis atau membuat fungsi ini global.
    // Cara mudah: buat global
    window.proceedToCheckout = function () {
        if (confirm('Lanjutkan ke proses peminjaman?')) {
            processFinalCheckout();
        }
    }

    window.removeFromCartKiosk = function (itemCode) {
        if (!confirm('Hapus buku ini dari keranjang?')) return;

        showLoading();

        fetch('/biblio/cart-item', {
            method: 'DELETE',
            headers: header_data,
            body: JSON.stringify({
                item_code: itemCode
            })
        })
            .then(response => response.json().then(data => ({ ok: response.ok, data })))
            .then(({ ok, data }) => {
                hideLoading();
                if (!ok) {
                    throw new Error(data.message || data.error || 'HTTP error');
                }

                if (data.status) {
                    // Muat ulang cart
                    displayCartOnly(data.data);
                } else {
                    showError(data.message || 'Gagal menghapus buku dari keranjang');
                }
            })
            .catch(error => {
                hideLoading();
                console.error('Error removing from cart:', error);
                showError(error.message || 'Terjadi kesalahan saat menghapus buku');
            });
    }

    function processFinalCheckout() {
        showLoading();

        fetch('/biblio/complete-loan', {
            method: 'POST',
            headers: header_data,
            body: JSON.stringify({}) // Body kosong, auth via session
        })
            .then(response => response.json().then(data => ({ ok: response.ok, data })))
            .then(({ ok, data }) => {
                hideLoading();
                if (!ok) {
                    throw new Error(data.message || data.error || 'HTTP error');
                }

                if (data.status) {
                    isCheckoutComplete = true; // Tandai checkout berhasil
                    showFinalSuccess(data.data);
                } else {
                    showError(data.message || 'Gagal memproses peminjaman');
                }
            })
            .catch(error => {
                hideLoading();
                console.error('Error processing checkout:', error);
                showError(error.message || 'Terjadi kesalahan saat memproses peminjaman');
            });
    }

    // Kebutuhan 4: Tampilkan Final Checkout
    function showFinalSuccess(loanData) {
        // Sembunyikan kamera
        readerContainer.style.display = 'none';

        // Sembunyikan footer
        modalFooterControls.style.display = 'none';

        // Update header
        document.getElementById('scanModalLabel').textContent = 'Peminjaman Berhasil';

        // Tampilkan pesan sukses di cart container
        cartContainer.innerHTML = `
            <div class="text-center">
                <div class="mb-4">
                    <i class="fas fa-check-circle fa-4x text-success"></i>
                </div>
                <h4 class="text-success mb-3">Peminjaman Berhasil!</h4>
                <div class="alert alert-success" role="alert">
                    <strong>Total buku dipinjam: ${loanData.total_borrowed}</strong><br>
                    <small>Batas pengembalian: ${loanData.due_date}</small>
                </div>
                
                <div class="mt-4">
                    <h6>Buku yang Dipinjam:</h6>
                    ${loanData.borrowed_items.map(item => `
                        <div class="card mb-2">
                            <div class="card-body p-2 text-start">
                                <small class="text-muted">Kode: ${item.item_code}</small><br>
                                <strong>${item.title}</strong><br>
                                <small class="text-success">Batas kembali: ${item.due_date}</small>
                            </div>
                        </div>
                    `).join('')}
                </div>
                
                <div class="alert alert-warning mt-3" role="alert">
                    <strong>Penting:</strong> ${loanData.return_reminder}
                </div>
                
                <button type="button" class="btn btn-primary w-100 mt-3" onclick="finishTransaction()">
                    <i class="fas fa-home me-2"></i>Selesai
                </button>
            </div>
        `;
    }

    window.finishTransaction = function () {
        // Tutup modal. Event 'hidden.bs.modal' akan menangani reset
        alert('Terima kasih! Transaksi peminjaman telah selesai.');
        location.reload();
    }

    // Clean up (jika diperlukan)
    window.addEventListener('beforeunload', function () {
        stopScanner();
    });

    // TUTUP BARIS INI DI PALING AKHIR
});