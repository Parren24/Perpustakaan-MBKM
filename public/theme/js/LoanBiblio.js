
let html5QrCode;
let isScanning = false;
let scanMode = 'user'; // 'user' untuk scan QR user, 'book' untuk scan barcode buku
let userAuthorized = false;

// --- Elemen DOM ---
const readerDiv = document.getElementById('reader');
const startBtn = document.getElementById('startBtn');
const resetBtn = document.getElementById('resetBtn');
const barcodeResultInput = document.getElementById('barcodeResult');
const bookResultDiv = document.getElementById('bookResult');
const errorResultDiv = document.getElementById('errorResult');
const loadingIndicator = document.getElementById('loadingIndicator');
const bookDetailsDiv = document.getElementById('bookDetails');
const errorMessageDiv = document.getElementById('errorMessage');

// Function called when QR code is successfully scanned
function onScanSuccess(decodedText, decodedResult) {

    console.log(`Code matched = ${decodedText}`, decodedResult);
    barcodeResultInput.value = decodedText;

    // Validate CSRF token exists
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        showError('CSRF token tidak ditemukan. Refresh halaman dan coba lagi.');
        return;
    }

    // Optional: Stop scanning after successful scan
    stopScanner();

    // Hide previous results
    hideAllResults();

    // Show loading indicator
    showLoading();

    // Hide start button, show reset button
    // document.getElementById('startBtn').style.display = 'none';
    // document.getElementById('resetBtn').style.display = 'inline-block';

    // Handle berbeda berdasarkan mode scan
    if (scanMode === 'user') {
        // Mode scan QR user untuk authorize session
        console.log('Authorizing user session with token:', decodedText);
        authorizeUserSession(decodedText); // Fungsi ini akan fetch ke /authorize-session

    } else if (scanMode === 'book') {
        // Mode scan barcode buku
        console.log('Getting book details for item:', decodedText);
        getBookDetailsAndAddToCart(decodedText); // Fungsi ini akan fetch ke /item/... dan /add-to-cart

    } else {
        // Mode tidak jelas
        hideLoading();
        showError('Scan mode tidak diketahui. Silakan reset.');
    }
}

// Step 1: Get book details first
fetch(`/biblio/item/${decodedText}`, {
    method: 'GET',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
        'Accept': 'application/json'
    }
})
    .then(response => response.json())
    .then(data => {
        console.log('Book details response:', data);

        if (data.success) {
            // Show book details
            showBookDetails(data.data);
            bookResultDiv.style.display = 'block';

            // Step 2: Langsung tambahkan ke cart tanpa generate QR
            if (data.data.is_available) {
                console.log('Adding book to cart:', decodedText);
                return fetch(`/biblio/add-to-cart`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        item_code: decodedText
                    })
                });
            } else {
                hideLoading();
                showError('Buku tidak tersedia untuk dipinjam.');
                throw new Error('Book not available');
            }
        } else {
            hideLoading();
            showError(data.message || 'Buku tidak ditemukan');
            throw new Error('Book not found');
        }
    })
    .then(response => {
        if (!response) return; // Skip if book not available

        console.log('Add to cart response status:', response.status);

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        return response.json();
    })
    .then(data => {
        if (!data) return; // Skip if previous step failed

        console.log('Add to cart response data:', data);
        hideLoading();

        if (data.success) {
            // Tampilkan pesan sukses dan info cart
            showCartSuccess(data.data, decodedText);
        } else {
            console.error('Backend returned error:', data);
            showError(data.error || 'Gagal menambahkan buku ke keranjang.');
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Fetch error details:', error);

        if (error.name === 'TypeError' && error.message.includes('Failed to fetch')) {
            showError('Tidak dapat terhubung ke server. Periksa koneksi internet Anda.');
        } else if (error.message.includes('HTTP error!')) {
            showError(`Server error: ${error.message}`);
        } else {
            showError(`Terjadi kesalahan: ${error.message}`);
        }
    });


// Function untuk authorize user session dengan QR token
function authorizeUserSession(token) {
    fetch('/biblio/authorize-session', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            token: token
        })
    })
        .then(response => {
            console.log('Authorization response status:', response.status);
            // Selalu parse JSON dulu, baik success maupun error
            return response.json().then(data => {
                if (!response.ok) {
                    // Jika response tidak OK, lempar error dengan pesan dari backend
                    throw new Error(data.error || data.message || `HTTP error! status: ${response.status}`);
                }
                return data;
            });
        })
        .then(data => {
            hideLoading();

            console.log('Authorization response:', data);

            if (data.success) {
                userAuthorized = true;
                scanMode = 'book';

                // Update UI untuk menunjukkan user sudah ter-authorize
                showUserAuthorized(data.data);

                // Update tombol untuk scan buku
                updateUIForBookScan();

                // Load dan tampilkan cart existing (jika ada)
                loadAndDisplayCart();
            } else {
                console.error('Authorization failed:', data);
                showError(data.error || 'Gagal melakukan otorisasi. Silakan scan ulang QR code.');
            }
        })
        .catch(error => {
            hideLoading();
            console.error('Error authorizing user:', error);
            // Tampilkan pesan error yang spesifik dari backend
            showError(error.message || 'Terjadi kesalahan saat otorisasi. Silahkan Generate ulang QR Code Anda.');
        });
}

// Function untuk get book details dan add to cart
function getBookDetailsAndAddToCart(itemCode) {
    // Step 1: Get book details first
    fetch(`/biblio/item/${itemCode}`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
        .then(response => response.json())
        .then(data => {
            console.log('Book details response:', data);

            if (data.success) {
                // Show book details
                showBookDetails(data.data);
                bookResultDiv.style.display = 'block';

                // Step 2: Langsung tambahkan ke cart
                if (data.data.is_available) {
                    console.log('Adding book to cart:', itemCode);
                    return fetch('/biblio/add-to-cart', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            item_code: itemCode
                        })
                    });
                } else {
                    hideLoading();
                    showError('Buku tidak tersedia untuk dipinjam.');
                    throw new Error('Book not available');
                }
            } else {
                hideLoading();
                showError(data.message || 'Buku tidak ditemukan');
                throw new Error('Book not found');
            }
        })
        .then(response => {
            if (!response) return;

            console.log('Add to cart response status:', response.status);
            // Parse JSON terlebih dahulu untuk mendapatkan pesan error dari backend
            return response.json().then(data => {
                if (!response.ok) {
                    // Jika response tidak OK, lempar error dengan pesan dari backend
                    throw new Error(data.error || data.message || `HTTP error! status: ${response.status}`);
                }
                return data;
            });
        })
        .then(data => {
            if (!data) return;

            console.log('Add to cart response data:', data);
            hideLoading();

            if (data.success) {
                // Show success message and updated cart
                showCartSuccess(data.data, itemCode);

                // Auto-hide success message after 5 seconds and show continuing scan interface
                setTimeout(() => {
                    if (data.data.remaining_slots > 0) {
                        continueScanningBooks();
                    }
                }, 5000);
            } else {
                console.error('Backend returned error:', data);
                showError(data.error || 'Gagal menambahkan buku ke keranjang.');
            }
        })
        .catch(error => {
            hideLoading();
            console.error('Fetch error details:', error);
            // Tampilkan pesan error yang spesifik dari backend
            showError(error.message || 'Terjadi kesalahan saat menambahkan buku ke keranjang.');
        });
}

function onScanFailure(error) {
    // Handle scan failure (not all failures are errors, some are just "no QR code found")
    // console.warn(`Code scan error = ${error}`);
}

// Initialize and start the scanner
function startScanner() {
    if (isScanning) {
        console.log("Scanner is already running");
        updateScannerStatus("Already running");
        return;
    }

    // Hide reset button when starting scanner
    document.getElementById('resetBtn').style.display = 'none';
    hideAllResults();

    document.getElementById('reader').innerHTML = '<div class="alert alert-info">Memulai scanner...</div>';
    updateScannerStatus("Starting...");

    html5QrCode = new Html5Qrcode("reader");

    const config = {
        fps: 10,
        qrbox: {
            width: 250,
            height: 250
        },
        aspectRatio: 1.0
    };

    // Start scanning
    html5QrCode.start({
        facingMode: "environment"
    }, // Use back camera
        config,
        onScanSuccess,
        onScanFailure
    ).then(() => {
        isScanning = true;
        updateScannerStatus("Running (back camera)");
        console.log("QR Code scanner started successfully");
    }).catch(err => {
        console.error("Unable to start scanning", err);
        updateScannerStatus("Trying alternative camera...");

        // Try with front camera if back camera fails
        html5QrCode.start({
            facingMode: "user"
        },
            config,
            onScanSuccess,
            onScanFailure
        ).then(() => {
            isScanning = true;
            updateScannerStatus("Running (front camera)");
            console.log("QR Code scanner started with front camera");
        }).catch(frontErr => {
            console.error("Unable to start scanning with front camera", frontErr);
            updateScannerStatus("Camera access failed");
            // Just show a simple message without technical error details
            document.getElementById('reader').innerHTML =
                '<div class="alert alert-info">' +
                'Scanner tidak dapat dimulai. Silakan coba lagi.' +
                '</div>';
        });
    });
}

// Stop the scanner
function stopScanner() {
    if (html5QrCode && isScanning) {
        html5QrCode.stop().then(() => {
            console.log("QR Code scanning stopped");
            isScanning = false;
            updateScannerStatus("Stopped");
            document.getElementById('reader').innerHTML =
                '<div class="alert alert-info">Scanner dihentikan. Klik "Mulai Scan" untuk memulai kembali.</div>';
        }).catch(err => {
            console.error("Unable to stop scanning", err);
            updateScannerStatus("Error stopping - " + err.message);
        });
    } else {
        updateScannerStatus("Not running");
        console.log("Scanner is not running");
    }
}

// Update scanner status in debug info
function updateScannerStatus(status) {
    const statusElement = document.getElementById('scannerStatus');
    if (statusElement) {
        statusElement.textContent = status;
    }
}

// Function to show book details
function showBookDetails(bookData) {
    // Validate bookData parameter
    if (!bookData || typeof bookData !== 'object') {
        console.error('showBookDetails: Invalid bookData parameter', bookData);
        showError('Data buku tidak valid atau tidak tersedia.');
        return;
    }

    console.log('Displaying book details:', bookData);

    const bookDetailsElement = document.getElementById('bookDetails');
    const bookResultElement = document.getElementById('bookResult');

    // Format the book details
    let bookInfo = `
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Kode Item:</strong> ${bookData.item_code || 'N/A'}
                        </div>
                        <div class="col-md-6">
                            <strong>Status:</strong> 
                            <span class="badge ${getStatusBadgeClass(bookData.loan_status)}">
                                ${getStatusText(bookData.loan_status)}
                            </span>
                        </div>
                    </div>
                `;

    // Tambahkan informasi biblio jika ada
    if (bookData.biblio) {
        bookInfo += `
                        <div class="row mt-3">
                            <div class="col-12">
                                <h6><strong>Informasi Buku:</strong></h6>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <strong>Judul:</strong> ${bookData.biblio.title || 'N/A'}
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <strong>Penulis:</strong> ${bookData.biblio.author || 'N/A'}
                            </div>
                            <div class="col-md-6">
                                <strong>Penerbit:</strong> ${bookData.biblio.publisher || 'N/A'}
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <strong>ISBN/ISSN:</strong> ${bookData.biblio.isbn_issn || 'N/A'}
                            </div>
                            <div class="col-md-6">
                                <strong>Tahun Terbit:</strong> ${bookData.biblio.publish_year || 'N/A'}
                            </div>
                        </div>
                    `;
    }

    // Tambahkan informasi ketersediaan
    bookInfo += `
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="alert ${bookData.is_available ? 'alert-success' : 'alert-warning'}" role="alert">
                                <strong>${bookData.is_available ? '✓ Buku Tersedia' : '⚠ Buku Tidak Tersedia'}</strong><br>
                                Status: ${bookData.status_name || getStatusText(bookData.loan_status)}
                                ${!bookData.is_available ? '<br><small>Buku sedang tidak dapat dipinjam saat ini.</small>' : ''}
                            </div>
                        </div>
                    </div>
                `;

    bookDetailsElement.innerHTML = bookInfo;
    bookResultElement.style.display = 'block';
}

// Function to load and display cart after authorization
function loadAndDisplayCart() {
    showLoading();

    fetch('/biblio/cart-items', {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
        .then(response => {
            // Parse JSON terlebih dahulu untuk mendapatkan pesan error dari backend
            return response.json().then(data => {
                if (!response.ok) {
                    // Jika response tidak OK, lempar error dengan pesan dari backend
                    throw new Error(data.error || data.message || `HTTP error! status: ${response.status}`);
                }
                return data;
            });
        })
        .then(data => {
            hideLoading();

            if (data.success) {
                // Display cart even if empty
                displayCartOnly(data.data);
            } else {
                console.error('Error loading cart:', data);
                showError(data.error || 'Gagal memuat keranjang');
            }
        })
        .catch(error => {
            hideLoading();
            console.error('Error loading cart:', error);
            // Tampilkan pesan error yang spesifik dari backend atau fallback ke cart kosong
            if (error.message.includes('Sesi') || error.message.includes('Session') || error.message.includes('401')) {
                showError(error.message || 'Session tidak valid. Silakan scan ulang QR code user.');
            } else {
                // Show empty cart if error lainnya
                displayCartOnly({
                    cart_items: [],
                    total_items: 0,
                    remaining_slots: 2,
                    can_add_more: true
                });
            }
        });
}

// Function to display cart only (without success message for specific item)
function displayCartOnly(cartData) {
    const bookDetailsElement = document.getElementById('bookDetails');
    const bookResultElement = document.getElementById('bookResult');

    // Clear existing content
    bookDetailsElement.innerHTML = '';

    // Show cart status
    const cartStatusElement = document.createElement('div');
    cartStatusElement.className = 'alert alert-info';
    cartStatusElement.innerHTML = `
                <h6 class="mb-2"><i class="fas fa-shopping-cart me-2"></i>Keranjang Peminjaman</h6>
                <p class="mb-2">Total buku dalam keranjang: <strong>${cartData.total_items}</strong> dari 2 maksimal</p>
                <p class="mb-0">Sisa slot: <strong>${cartData.remaining_slots}</strong></p>
                <hr class="my-2">
                <p class="mb-0 text-muted">Silakan scan barcode buku yang ingin dipinjam.</p>
            `;
    bookDetailsElement.appendChild(cartStatusElement);

    // Show cart contents if any
    if (cartData.cart_items && cartData.cart_items.length > 0) {
        const cartItemsElement = document.createElement('div');
        cartItemsElement.className = 'mt-3';
        cartItemsElement.innerHTML = `
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
        bookDetailsElement.appendChild(cartItemsElement);
    }

    // Add action buttons
    if (cartData.total_items > 0) {
        const actionButtons = document.createElement('div');
        actionButtons.className = 'mt-3 text-center';
        actionButtons.innerHTML = `
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            ${cartData.remaining_slots > 0 ?
                `<button type="button" class="btn btn-outline-success me-2" onclick="continueScanningBooks()">
                                    <i class="fas fa-plus me-2"></i>Tambah Buku Lagi (${cartData.remaining_slots} slot tersisa)
                                </button>` : ''
            }
                            <button type="button" class="btn btn-success" onclick="proceedToCheckout()">
                                <i class="fas fa-check me-2"></i>Lanjutkan Pinjam (${cartData.total_items} buku)
                            </button>
                        </div>
                    </div>
                `;
        bookDetailsElement.appendChild(actionButtons);
    }

    bookResultElement.style.display = 'block';
}

// Function to show cart success (when item is added)
function showCartSuccess(cartData, itemCode) {
    const bookDetailsElement = document.getElementById('bookDetails');

    // Clear existing content
    bookDetailsElement.innerHTML = '';

    // Add success message for added item
    if (itemCode) {
        const successInfo = document.createElement('div');
        successInfo.className = 'alert alert-success';
        successInfo.innerHTML = `
                    <h6 class="mb-2"><i class="fas fa-check-circle me-2"></i>Buku Berhasil Ditambahkan!</h6>
                    <p class="mb-2">Kode: <strong>${itemCode}</strong></p>
                    <p class="mb-0">Total buku dalam keranjang: <strong>${cartData.total_items}</strong> dari 2 maksimal</p>
                `;
        bookDetailsElement.appendChild(successInfo);
    }

    // Show updated cart contents
    if (cartData.cart_items && cartData.cart_items.length > 0) {
        const cartItemsElement = document.createElement('div');
        cartItemsElement.className = 'mt-3';
        cartItemsElement.innerHTML = `
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="fas fa-list me-2"></i>Semua Buku dalam Keranjang</h6>
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
        bookDetailsElement.appendChild(cartItemsElement);
    }

    // Add action buttons
    const actionButtons = document.createElement('div');
    actionButtons.className = 'mt-3 text-center';
    actionButtons.innerHTML = `
                <div class="row">
                    <div class="col-md-12 mb-2">
                        <button type="button" class="btn btn-success" onclick="proceedToCheckout()">
                            <i class="fas fa-check me-2"></i>Lanjutkan Pinjam (${cartData.total_items} buku)
                        </button>
                    </div>
                </div>
            `;
    bookDetailsElement.appendChild(actionButtons);

}

// Function to continue scanning books (reset scanner for next book)
function continueScanningBooks() {
    // Clear book details but keep cart display
    const bookDetailsElement = document.getElementById('bookDetails');

    // Just reload and display cart again
    loadAndDisplayCart();

    // Reset scanner message
    readerDiv.innerHTML = '<div class="alert alert-info">Silakan scan barcode buku berikutnya</div>';

    // Show start button
    startBtn.style.display = 'inline-block';
}

// Function to show user authorized
function showUserAuthorized(userData) {
    const authorizeInfo = document.createElement('div');
    authorizeInfo.className = 'alert alert-success mt-3';
    authorizeInfo.id = 'userAuthorizeInfo';
    authorizeInfo.innerHTML = `
                <h6 class="mb-2"><i class="fas fa-user-check me-2"></i>User Terotorisasi!</h6>
                <p class="mb-2">Nama: <strong>${userData.member_name}</strong></p>
                <p class="mb-0">Member ID: <strong>${userData.nomor_induk || 'N/A'}</strong></p>
                <p class="mb-0 mt-2 text-muted">Sekarang silakan scan barcode buku yang ingin dipinjam.</p>
            `;

    // Insert after reader div
    readerDiv.parentNode.insertBefore(authorizeInfo, readerDiv.nextSibling);
}

// Function to update UI for book scanning mode
function updateUIForBookScan() {
    // Update button text
    startBtn.textContent = 'Scan Barcode Buku';
    resetBtn.textContent = 'Reset & Scan User Lagi';

    // Update reader message
    readerDiv.innerHTML = '<div class="alert alert-info">Silakan scan barcode buku yang ingin dipinjam</div>';

    // Show start button again
    startBtn.style.display = 'inline-block';
    resetBtn.style.display = 'none';
}

// Function to show error message
function showError(message) {
    errorMessageDiv.textContent = message;
    errorResultDiv.style.display = 'block';
}
// Function to show loading indicator
function showLoading() {
    loadingIndicator.style.display = 'block';
}
// Function to hide loading indicator
function hideLoading() {
    document.getElementById('loadingIndicator').style.display = 'none';
}

// Function to hide all result elements
function hideAllResults() {
    bookResultDiv.style.display = 'none';
    errorResultDiv.style.display = 'none';
    loadingIndicator.style.display = 'none';
}

// Helper function to get status badge class
function getStatusBadgeClass(status) {
    // Handle both integer 0 and string '0' as available status
    if (status === 0 || status === '0' || status === null || status === '') {
        return 'bg-success';
    }

    switch (status) {
        case 'R':
            return 'bg-info';
        case 'NL':
            return 'bg-dark';
        case 'MIS':
            return 'bg-secondary';
        default:
            return 'bg-secondary';
    }
}

// Helper function to get status text
function getStatusText(status) {
    // Handle both integer 0 and string '0' as available status
    if (status === 0 || status === '0' || status === null || status === '') {
        return 'Tersedia';
    }

    switch (status) {
        case 'R':
            return 'Diperbaiki';
        case 'NL':
            return 'Tidak Untuk Dipinjam';
        case 'MIS':
            return 'Hilang';
        default:
            return 'Status Tidak Diketahui';
    }
}

// Function to reset results and allow new scan
function resetResults() {
    hideAllResults();
    barcodeResultInput.value = '';

    // Reset to user scan mode
    scanMode = 'user';
    userAuthorized = false;

    // Remove user authorize info if exists
    const authorizeInfo = document.getElementById('userAuthorizeInfo');
    if (authorizeInfo) {
        authorizeInfo.remove();
    }

    // Reset button states
    startBtn.textContent = 'Mulai Scan QR User';
    resetBtn.style.display = 'none';
    startBtn.style.display = 'inline-block';

    // Reset reader message
    readerDiv.innerHTML = '<div class="alert alert-light">Klik "Mulai Scan QR User" untuk memulai proses peminjaman.</div>';
}

// Helper function to format date
function formatDate(dateString) {
    if (!dateString) return 'N/A';

    try {
        const date = new Date(dateString);
        return date.toLocaleDateString('id-ID', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    } catch (error) {
        return dateString;
    }
}

// Start scanner when page loads
document.addEventListener('DOMContentLoaded', function () {
    // Initialize scanner interface for user scan first
    document.getElementById('reader').innerHTML =
        '<div class="alert alert-info">Scanner siap! Klik "Mulai Scan QR User" untuk memulai proses peminjaman.</div>';

    // Set initial button text
    startBtn.textContent = 'Mulai Scan QR User';
});

// Clean up when page unloads
window.addEventListener('beforeunload', function () {
    stopScanner();
});

// Cart action functions (cleaned up)

function proceedToCheckout() {
    // Langsung ke proses peminjaman
    if (confirm('Lanjutkan ke proses peminjaman?')) {
        processFinalCheckout();
    }
}

function removeFromCartKiosk(itemCode) {
    if (!confirm('Hapus buku ini dari keranjang?')) return;

    showLoading();

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    fetch('/biblio/cart-item', {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
            item_code: itemCode
        })
    })
        .then(response => {
            // Parse JSON terlebih dahulu untuk mendapatkan pesan error dari backend
            return response.json().then(data => {
                if (!response.ok) {
                    // Jika response tidak OK, lempar error dengan pesan dari backend
                    throw new Error(data.error || data.message || `HTTP error! status: ${response.status}`);
                }
                return data;
            });
        })
        .then(data => {
            hideLoading();

            if (data.success) {
                if (data.data.total_items > 0) {
                    // Still have items, reload cart display
                    displayCartOnly(data.data);

                    // Show temporary success message
                    const tempAlert = document.createElement('div');
                    tempAlert.className = 'alert alert-warning alert-dismissible fade show';
                    tempAlert.innerHTML = `
                                <i class="fas fa-info-circle me-2"></i>Buku berhasil dihapus dari keranjang.
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            `;
                    document.getElementById('bookDetails').insertBefore(tempAlert, document.getElementById('bookDetails').firstChild);

                    // Auto dismiss after 3 seconds
                    setTimeout(() => {
                        if (tempAlert.parentNode) {
                            tempAlert.remove();
                        }
                    }, 3000);
                } else {
                    // Cart empty, show empty cart state
                    displayCartOnly(data.data);
                }
            } else {
                showError(data.error || 'Gagal menghapus buku dari keranjang');
            }
        })
        .catch(error => {
            hideLoading();
            console.error('Error removing from cart:', error);
            // Tampilkan pesan error yang spesifik dari backend
            showError(error.message || 'Terjadi kesalahan saat menghapus buku');
        });
}

function processFinalCheckout() {
    showLoading();

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    fetch('/biblio/complete-loan', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({})
    })
        .then(response => {
            // Parse JSON terlebih dahulu untuk mendapatkan pesan error dari backend
            return response.json().then(data => {
                if (!response.ok) {
                    // Jika response tidak OK, lempar error dengan pesan dari backend
                    throw new Error(data.error || data.message || `HTTP error! status: ${response.status}`);
                }
                return data;
            });
        })
        .then(data => {
            hideLoading();

            if (data.success) {
                showFinalSuccess(data.data);
            } else {
                showError(data.error || 'Gagal memproses peminjaman');
            }
        })
        .catch(error => {
            hideLoading();
            console.error('Error processing checkout:', error);
            // Tampilkan pesan error yang spesifik dari backend
            showError(error.message || 'Terjadi kesalahan saat memproses peminjaman');
        });
}

function showFinalSuccess(loanData) {
    const bookDetailsElement = document.getElementById('bookDetails');

    bookDetailsElement.innerHTML = `
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
                                <div class="card-body p-2">
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

function finishTransaction() {
    // Reset semua dan kembali ke halaman awal
    resetResults();
    alert('Terima kasih! Transaksi peminjaman telah selesai.');
}

