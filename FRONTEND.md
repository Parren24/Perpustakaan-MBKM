# Dokumentasi Frontend Web PCR

---

## Developer
- Muhammad Arief Rahman

---

## Struktur Direktori
Deskripsi singkat mengenai struktur direktori yang digunakan pada bagian frontend dari proyek ini.
```
.
├── app
│   ├── Http
│   │   └── Controllers
│   │       └── Frontend [1]
│   ├── Models [2]
│   ├── Providers [3]
│   ├── Services
│   │   └── Frontend [4]
│   └── Helpers
│       └── Frontend [9]
│   ...
|
├── public
│   │
│   ├── theme
│   │   └── frontend [5]
│   ...
|
├── resources
│   └── views
│       ├── contents
│       │   └── frontend [6]
│       ...
│       └── layouts [7]
├── routes
│   └── web-frontend.php [8]
...

```

### Penjelasan Struktur Direktori
1. **app/Http/Controllers/Frontend**
   
   Berisi semua controller yang digunakan pada frontend. Penamaaman dan pengelompokan controller disesuaikan dengan [docs](https://docs.google.com/document/d/1wyzhiSGhDfXisVKFqjnTZ-HqVmRlJpi6-1Mglxdd_hE/edit?tab=t.0) rancangan konten yang telah dibuat.

2. **app/Models**
   
   Berisi model-model yang digunakan pada frontend. Model ini berfungsi untuk mengelola data yang ditampilkan di frontend, seperti `KontenJurusan`, `Prodi`, dan lainnya. Model frontend menyesuaikan dengan model yang telah dibuat, dan dengan penambahan penggunakan relasi Eloquent.

3. **app/Providers**

    Berisi service provider yang digunakan untuk mengatur berbagai hal pada frontend.
    - [ComponentServiceProvider.php](app/Providers/Frontend/ComponentServiceProvider.php): Provider untuk memberikan data kepada komponen frontend seperti header.
    - [DirectivesProvider.php](app/Providers/Frontend/DirectivesProvider.php): Provider untuk mengatur direktif Blade yang digunakan pada frontend.
    - [AppServiceProvider.php](app/Providers/AppServiceProvider.php): Terdapat konfigurasi untuk tunneling ngrok pada environment lokal.

4. **app/Services/Frontend**

    Berisi service yang digunakan untuk mengelompokkan data-fetching pada frontend.

5. **public/theme/frontend**
   
    Berisi semua aset frontend seperti CSS, JavaScript, dan gambar.

6. **resources/views/contents/frontend**

    Berisi semua view yang digunakan pada frontend. Secara keseluruhan dipecah menjadi folder.
    - **pages**: Berisi file halaman yang akan dipanggil pada controller. Biasanya berisikan kumpulan file parsial yaitu section konten yang akan ditampilkan. Pengelompokan juga mengikuti fitur mayor yang ada.
    - **partials**: Berisi file parsial yang digunakan pada halaman. File ini berisi bagian-bagian kecil dari halaman yang dapat digunakan kembali di berbagai tempat. Terdiri dari beberapa subfolder seperti:
      - **common**: Komponen yang memiliki kegunaan umum seperti header halaman, divider halaman, dsb.
      - **main**: Komponen yang lebih spesifik seperti "section" pada sebuah halaman tertentu.

7. **resources/views/layouts**

    Layout tampilan frontend yang terdiri dari file:
    - **base.blade.php**: Layout dasar yang digunakan untuk semua halaman frontend yang.
    - **frontend.blade.php**: Layout utama yang digunakan untuk halaman-halaman frontend.
    
    *Note: sturuktur awal ini dibuat dengan asumsi layout frontend dan backend akan mengikuti dasar yang sama*

8. **routes/web-frontend.php**

    Routing untuk frontend. Dengan secara umum mengikuti aturan pengelompokan berikut:

    ```
    Prefix (Opsional) -> name (Wajib) -> Controller (Apabila akan langsung menggunakan controller) -> Group -> Route -> name (Wajib)
    ```

9. **app/Helpers/Frontend**

    Berisi helper yang digunakan pada frontend. Helper ini berfungsi untuk menyediakan fungsi-fungsi yang dapat digunakan di berbagai tempat dalam aplikasi frontend, seperti pemecah string, format data konten, mapping data konten, dan lainnya.

---

## Catatan
- Ada route development `/dev/changelog` yang menampilkan perubahan yang tercatat selema pengembangan frontend. File changelog ada pada [storage\app\private\frontend-changelog.md](storage\app\private\frontend-changelog.md)
