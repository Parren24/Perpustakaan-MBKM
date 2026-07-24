<!-- 
@extends('layouts.frontend.main') -->

@section('content')
<style>
    .pin-container {
        max-width: 480px;
        width: 100%;
    }

    /* Indikator Titik PIN */
    .pin-display {
        display: flex;
        justify-content: center;
        gap: 12px;
        margin-bottom: 20px;
    }

    .pin-dot {
        width: 18px;
        height: 18px;
        border: 2px solid #ccc;
        border-radius: 50%;
        transition: all 0.2s ease;
    }

    .pin-dot.filled {
        background-color: #eb0000;
        /* Ubah ke warna brand Anda */
        border-color: #eb0000;
        transform: scale(1.1);
    }

    /* Keypad Grid Layout */
    .keypad-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
        padding: 0 10px;
    }

    .keypad-btn {
        height: 50px;
        font-size: 1.5rem;
        font-weight: 600;
        border-radius: 12px;
        border: 1px solid #e0e0e0;
        background-color: #f8f9fa;
        color: #333;
        transition: all 0.15s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        user-select: none;
        cursor: pointer;
    }

    .keypad-btn:active {
        background-color: #e2e6ea;
        transform: scale(0.96);
    }

    .keypad-btn.action-btn {
        font-size: 1rem;
        font-weight: 500;
        background-color: #f1f3f5;
        color: #495057;
    }
</style>


<section class="fact-statistics-section layanan-section">
    <div class="container ">
        <div class="row justify-content-center ">
            <div class="d-flex align-items-center justify-content-center" style="min-height:75vh;">
                <div class="col-12 col-md-6 col-lg-5 col-xl-4">
                    <div class="card border border-1 lg-4 pb-1 rounded bg-opacity-75 wow fadeInUp" data-wow-delay="0.5s">
                        <div class="card-body d-flex justify-content-center align-items-center flex-column">

                            <div class="text-center">
                                <h4 class="fw-bold mb-1">Aktivasi Kios</h4>
                                <p class="text-muted small">Masukkan PIN Keamanan</p>
                                <div class="col">
                                    <div class="text-center">
                                        <span id="pinError" class="text-danger small"></span>
                                    </div>
                                </div>
                            </div>


                            <!-- Hidden input untuk menerima fokus keyboard fisik & menyimpan value -->
                            <input type="password" id="kiosPinInput" style="opacity: 0; position: absolute; pointer-events: none;" maxlength="6" inputmode="numeric" autofocus>
                            <div class="card border-0 pin-container ">
                                <!-- Dynamic PIN Dots Display (Misal: 6 Digit) -->
                                <div class="pin-display" id="pinDisplay">
                                    <div class="pin-dot"></div>
                                    <div class="pin-dot"></div>
                                    <div class="pin-dot"></div>
                                    <div class="pin-dot"></div>
                                    <div class="pin-dot"></div>
                                    <div class="pin-dot"></div>
                                </div>

                                <!-- On-Screen Keypad -->
                                <div class="keypad-grid mb-3">
                                    <button class="keypad-btn" data-value="1">1</button>
                                    <button class="keypad-btn" data-value="2">2</button>
                                    <button class="keypad-btn" data-value="3">3</button>
                                    <button class="keypad-btn" data-value="4">4</button>
                                    <button class="keypad-btn" data-value="5">5</button>
                                    <button class="keypad-btn" data-value="6">6</button>
                                    <button class="keypad-btn" data-value="7">7</button>
                                    <button class="keypad-btn" data-value="8">8</button>
                                    <button class="keypad-btn" data-value="9">9</button>
                                    <button class="keypad-btn action-btn" id="clearBtn">C</button>
                                    <button class="keypad-btn" data-value="0">0</button>
                                    <button class="keypad-btn action-btn" id="backspaceBtn">⌫</button>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const pinInput = document.getElementById('kiosPinInput');
        const dots = document.querySelectorAll('.pin-dot');
        const pinError = document.getElementById('pinError');

        const maxDigits = dots.length; // Mengikuti jumlah elemen .pin-dot (6 digit)

        // Update tampilan titik-titik indikator PIN
        function updateDots(clearError = true) {
            const val = pinInput.value;
            dots.forEach((dot, index) => {
                if (index < val.length) {
                    dot.classList.add('filled');
                } else {
                    dot.classList.remove('filled');
                }
            });

            if (clearError && pinError.textContent !== '') {
                pinError.textContent = '';
            }

            if (val.length === maxDigits) {
                submitPin();
            }
        }

        // Event Listener Keypad On-Screen
        document.querySelectorAll('.keypad-btn[data-value]').forEach(btn => {
            btn.addEventListener('click', () => {
                if (pinInput.value.length < maxDigits) {
                    pinInput.value += btn.getAttribute('data-value');
                    updateDots();
                }
            });
        });

        // Tombol Backspace
        document.getElementById('backspaceBtn').addEventListener('click', () => {
            pinInput.value = pinInput.value.slice(0, -1);
            updateDots();
        });

        // Tombol Clear (Reset)
        document.getElementById('clearBtn').addEventListener('click', () => {
            pinInput.value = '';
            updateDots();
        });

        // Tetap mengizinkan input dari Keyboard Fisik
        document.addEventListener('keydown', (e) => {
            if (e.key >= '0' && e.key <= '9') {
                if (pinInput.value.length < maxDigits) {
                    pinInput.value += e.key;
                    updateDots();
                }
            } else if (e.key === 'Backspace') {
                pinInput.value = pinInput.value.slice(0, -1);
                updateDots();
            } else if (e.key === 'Enter') {
                submitPin();
            }
        });

        // Fokus kembali ke input tersembunyi jika layar diklik
        document.addEventListener('click', () => pinInput.focus());

        // Submit PIN ke backend
        function submitPin() {
            const pinValue = pinInput.value;

            if (!pinValue) {
                pinError.textContent = 'Silakan masukkan PIN.';
                return;
            }

            fetch('{{ route("frontend.biblio.kios.unlock.submit") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        pin: pinValue
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status) {
                        window.location.href = data.redirect;
                    } else {
                        pinError.textContent = data.message || 'PIN salah, silakan coba lagi.';
                        pinInput.value = '';
                        updateDots(false);
                    }
                })
                .catch(err => {
                    pinError.textContent = 'Terjadi kesalahan jaringan.';
                });
        }


    });
</script>
@endsection