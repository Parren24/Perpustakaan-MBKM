document.addEventListener('keydown', function(event) {
        // 1. Blokir semua kombinasi yang menggunakan tombol Ctrl (Ctrl+P, Ctrl+S, Ctrl+U, dll)
        if (event.ctrlKey) {
            event.preventDefault();
            console.log('Fungsi Control dinonaktifkan.');
            return false;
        }

        // 2. Blokir tombol F12 (Inspect Element / Developer Tools)
        if (event.key === 'F12' || event.keyCode === 123) {
            event.preventDefault();
            return false;
        }

        // 3. Blokir tombol F5 (Refresh halaman) -> Opsional, aktifkan jika tidak ingin user refresh manual
        // if (event.key === 'F5' || event.keyCode === 116) {
        //     event.preventDefault();
        //     return false;
        // }
    });

    // Mencegah Klik Kanan (Context Menu) agar user tidak bisa Inspect atau Print via Mouse
    document.addEventListener('contextmenu', function(event) {
        event.preventDefault();
    });