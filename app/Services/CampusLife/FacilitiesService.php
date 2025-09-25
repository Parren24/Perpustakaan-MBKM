<?php

namespace App\Services\CampusLife;

use App\Services\CampusLife\VirtualTourService;
use App\Services\Frontend\SiteIdentityService;

class FacilitiesService
{
    /**
     * Get content for Facilities index page
     *
     * @return array
     */
    public static function getIndexContent(): array
    {
        return [
            'header'          => 'Fasilitas',
            'title'           => '<b>Fasilitas Lengkap</b> untuk Masa Depan Gemilang',
            'subtitle'        => 'Tentang Fasilitas',
            'description'     => 'Politeknik Caltex Riau menghadirkan fasilitas modern dan lengkap untuk mendukung proses belajar sekaligus pengembangan diri mahasiswa. Dengan laboratorium canggih, ruang belajar yang nyaman, area terbuka hijau, PCR berkomitmen menciptakan lingkungan akademik yang kondusif serta memberikan pengalaman terbaik bagi seluruh civitas akademika',
            'image'           => [
                'src' => publicMedia('fasilitas.webp'),
                'alt' => 'About Facilities'
            ],
            'action'          => [
                'url'         => VirtualTourService::soureceUrl(),
                'class'       => 'popup-video',
                'cursor_text' => 'Mulai',
                'icon'        => 'fa-solid fa-play',
                'text'        => 'Virtual Tour Fasilitas'
            ],
            'facilities_list' => [
                [
                    'title'       => 'Main Hall',
                    'description' => 'Mainhall berada di pintu utama kampus, cukup luas untuk menampung sekitar 100–200 kursi. Dari sini pengunjung dapat melihat perpustakaan dan auditorium di lantai 2–3 sambil merasakan suasana akademik dan aktivitas mahasiswa di sekitarnya.',
                    'image'       => [
                        [
                            'src' => publicMedia('main-hall.webp', ['media', 'fasilitas']),
                            'alt' => 'Main Hall'
                        ]
                    ]
                ],
                [
                    'title'       => 'Auditorium',
                    'description' => 'Auditorium berkapasitas 200–300 orang dengan panggung, soundsystem, proyektor dan layar di kanan‑kiri; digunakan untuk seminar, workshop, kuliah umum, pelatihan keterampilan, serta kegiatan kemahasiswaan dan rekrutmen dari pihak luar.',
                    'image'       => [
                        [
                            'src' => publicMedia('auditorium.webp', ['media', 'fasilitas']),
                            'alt' => 'Auditorium'
                        ]
                    ]
                ],
                [
                    'title'       => 'Amphi Theater',
                    'description' => 'PCR memiliki dua Amphi Theater: Amphi Luar dengan kapasitas ±300–500 orang untuk upacara dan kegiatan seremonial, serta Amphi Dalam berkapasitas ±75–150 orang yang kerap digunakan untuk kegiatan staf, himpunan mahasiswa/UKM dan akses internet.',
                    'image'       => [
                        [
                            'src' => publicMedia('amphi-theater.webp', ['media', 'fasilitas']),
                            'alt' => 'Amphi Theater'
                        ]
                    ]
                ],
                [
                    'title'       => 'Guest House',
                    'description' => 'Akomodasi tamu bertipe ±100 dengan tiga kamar double bed ber-AC; setiap kamar menampung dua tamu dan dilengkapi ruang tamu, dapur, TV serta akses internet.',
                    'image'       => [
                        [
                            'src' => publicMedia('guest-house.webp', ['media', 'fasilitas']),
                            'alt' => 'Guest House'
                        ]
                    ]
                ],
                [
                    'title'       => 'Ruang Kelas',
                    'description' => 'Terdapat 27 ruang kelas berukuran sekitar 70,56 m² yang masing‑masing berkapasitas 36 kursi. Seluruh ruang dilengkapi AC, proyektor dan layar, tersebar di Gedung Utama dan Gedung Serbaguna.',
                    'image'       => [
                        [
                            'src' => publicMedia('ruang-kelas.webp', ['media', 'fasilitas']),
                            'alt' => 'Ruang Kelas'
                        ]
                    ]
                ],
                [
                    'title'       => 'Laboratorium',
                    'description' => 'Sekitar 32 laboratorium, termasuk studio gambar, studio animasi dan ruang keamanan siber, menopang praktikum dengan kapasitas 30–36 kursi. Laboratorium dikelola oleh berbagai jurusan dan dilengkapi AC, proyektor, layar serta peralatan pendukung.',
                    'image'       => [
                        [
                            'src' => publicMedia('laboratorium.webp', ['media', 'fasilitas']),
                            'alt' => 'Laboratorium'
                        ]
                    ]
                ],
                [
                    'title'       => 'Workshop',
                    'description' => 'Terdapat dua gedung workshop: Workshop Mesin Produksi (beroperasi sejak 2002 dengan mesin CNC, bubut dan milling) dan Mechanical & Electrical Workshop (dengan praktik las, instalasi listrik, fluida, proteksi listrik dan lain‑lain) yang mendukung proses produksi, praktikum dan pembuatan berbagai produk oleh mahasiswa.',
                    'image'       => [
                        [
                            'src' => publicMedia('workshop.webp', ['media', 'fasilitas']),
                            'alt' => 'Workshop'
                        ]
                    ]
                ],
                [
                    'title'       => 'Perpustakaan',
                    'description' => 'Perpustakaan mendukung Tri Dharma dengan koleksi sekitar 3 000 judul buku (±6 000 eksemplar), 400 judul modul (±30 000 eksemplar) dan berbagai jurnal nasional–internasional, proceeding, CD pembelajaran, referensi tugas akhir dan novel. Layanan meliputi silang layanan koleksi, pemesanan buku, diskon penerbit, kelas bahasa Prancis, akses e‑journals, Warung Prancis yang menyediakan informasi budaya Prancis, serta BI Corner yang hadir sejak 2019.',
                    'image'       => [
                        [
                            'src' => publicMedia('perpustakaan.webp', ['media', 'fasilitas']),
                            'alt' => 'Perpustakaan'
                        ]
                    ]
                ],
                [
                    'title'       => 'Dormitory',
                    'description' => 'Asrama dua lantai yang dibangun pada 2007 memiliki 36 kamar (18 per lantai) dan 19 kamar mandi. Fasilitas ini menampung peserta pelatihan Chevron dan Pemda serta peserta kegiatan mahasiswa dari luar kota, seperti seminar dan workshop.',
                    'image'       => [
                        [
                            'src' => publicMedia('dormitory.webp', ['media', 'fasilitas']),
                            'alt' => 'Dormitory'
                        ]
                    ]
                ],
                [
                    'title'       => 'Kantin De‑Pipe',
                    'description' => 'Kantin yang terletak di Student Center sisi timur ini menggunakan tiang dari pipa minyak bekas, menyediakan aneka menu dari sarapan hingga makan siang dengan harga terjangkau. Terdapat sekitar empat vendor makanan sehingga civitas akademika bisa makan di kampus tanpa harus keluar.',
                    'image'       => [
                        [
                            'src' => publicMedia('kantin.webp', ['media', 'fasilitas']),
                            'alt' => 'Kantin De-Pipe'
                        ]
                    ]
                ],
                [
                    'title'       => 'Gedung Serba Guna (GSG)',
                    'description' => 'GSG berada di sisi barat kampus utama dengan kapasitas hall ±1 500–2 000 orang. Fasilitasnya meliputi dua layar proyektor, panggung, AC penuh dan sistem suara. Sejak beroperasi pada 2012, gedung ini digunakan untuk wisuda dan berbagai acara dengan pilihan meja bundar atau meja petak, serta dapat disewa untuk walimahan (resepsi pernikahan).',
                    'image'       => [
                        [
                            'src' => publicMedia('gsg.webp', ['media', 'fasilitas']),
                            'alt' => 'Gedung Serba Guna (GSG)'
                        ]
                    ]
                ],
                [
                    'title'       => 'Sport Hall',
                    'description' => 'Sport Hall mulai beroperasi pada 2017 dan memiliki arena futsal, bola voli, bulutangkis serta bola basket guna mendukung kegiatan olahraga mahasiswa.',
                    'image'       => [
                        [
                            'src' => publicMedia('sport-hall.webp', ['media', 'fasilitas']),
                            'alt' => 'Sport Hall'
                        ]
                    ]
                ],
                [
                    'title'       => "Mesjid Madinatul 'ilm",
                    'description' => 'Masjid berukuran 20×20 m yang dibangun pada 2003 ini terletak di tengah kampus dan dibuat dari pipa‑pipa minyak. Memiliki sisi terbuka seperti pendopo dengan sentuhan arsitektur Melayu. Selain sebagai tempat ibadah, masjid menjadi sekretariat UKMI dan lokasi kegiatan seperti Tabligh Akbar, MTQ mahasiswa, mentoring, tahsin, buka puasa bersama dan penyembelihan Qurban.',
                    'image'       => [
                        [
                            'src' => publicMedia('masjid.webp', ['media', 'fasilitas']),
                            'alt' => "Mesjid Madinatul 'ilm"
                        ]
                    ]
                ],
                [
                    'title'       => 'Fasilitas Pendukung',
                    'description' => 'Fasilitas penunjang meliputi tangga yang ramah disabilitas bagi sivitas akademika, bangku tunggu di lorong‑lorong kelas untuk membaca atau berkumpul, serta ruang diskusi terbuka yang digunakan mahasiswa untuk menyelesaikan tugas, proyek kuliah atau rapat.',
                    'image'       => [
                        [
                            'src' => publicMedia('pendukung.webp', ['media', 'fasilitas']),
                            'alt' => 'Fasilitas Pendukung'
                        ]
                    ]
                ]
            ]
        ];
    }

    /**
     * Get content for Facilities detail page
     *
     * @param int $facilityId
     * @return array
     */
    public static function getDetailContent(int $facilityId): array
    {
        // This is a placeholder. In a real application, you would fetch data from a database
        // based on $facilityId. For now, we'll return a generic detail.
        $facilities = self::getIndexContent()['facilities_list'];
        $facility   = $facilities[$facilityId - 1] ?? null; // Adjust index for 0-based array

        if ($facility) {
            return [
                'header'      => $facility['title'],
                'title'       => $facility['title'],
                'description' => $facility['description'] . ' Detail description for ' . $facility['title'] . '.',
                'images'      => [
                    'main'    => $facility['image'],
                    'gallery' => [
                        // Add more images for gallery if needed
                        $facility['image'],
                        ['src' => publicMedia('placeholder-gallery-1.webp'), 'alt' => $facility['title'] . ' Gallery 1'],
                        ['src' => publicMedia('placeholder-gallery-2.webp'), 'alt' => $facility['title'] . ' Gallery 2'],
                    ]
                ],
                'sections'    => [
                    [
                        'title' => 'Gambaran Umum',
                        'body'  => '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>'
                    ],
                    [
                        'title' => 'Spesifikasi Teknis',
                        'body'  => '<p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>'
                    ]
                ]
            ];
        }

        return [
            'header'      => 'Fasilitas Tidak Ditemukan',
            'title'       => 'Fasilitas Tidak Ditemukan',
            'description' => 'Fasilitas yang Anda cari tidak tersedia.'
        ];
    }

    /**
     * Get meta data for Facilities page
     *
     * @param string $pageType 'index' or 'detail'
     * @param int|null $facilityId
     * @return array
     */
    public static function getMetaData(string $pageType = 'index', ?int $facilityId = null): array
    {
        if ($pageType === 'detail' && $facilityId !== null) {
            $content = self::getDetailContent($facilityId);
        } else {
            $content = self::getIndexContent();
        }

        return [
            'title'       => data_get($content, 'title'),
            'description' => data_get($content, 'description'),
            'keywords'    => 'fasilitas kampus, politeknik caltex riau, pcr, laboratorium, perpustakaan',
        ];
    }

    /**
     * Get page configuration for Facilities page
     *
     * @param string $pageType 'index' or 'detail'
     * @param int|null $facilityId
     * @return array
     */
    public static function getPageConfig(string $pageType = 'index', ?int $facilityId = null): array
    {
        $meta = self::getMetaData($pageType, $facilityId);
        $bg   = publicMedia('fasilitas-bg.webp'); // Generic background for facilities

        return [
            'background_image' => $bg,
            'seo'              => [
                'title'                      => data_get($meta, 'title'),
                'description'                => data_get($meta, 'description'),
                'keywords'                   => data_get($meta, 'keywords'),
                'canonical'                  => ($pageType === 'detail' && $facilityId !== null) ? route('frontend.campus-life.facilities.detail', ['facilityId' => $facilityId]) : route('frontend.campus-life.facilities.index'),
                'og_image'                   => $bg,
                'og_type'                    => 'website',
                'structured_data'            => self::getStructuredData($pageType, $bg, $facilityId),
                'breadcrumb_structured_data' => self::getBreadcrumbStructuredData($pageType, $facilityId)
            ]
        ];
    }

    /**
     * Get structured data for Facilities page
     *
     * @param string $pageType
     * @param string $bg
     * @param int|null $facilityId
     * @return array
     */
    public static function getStructuredData(string $pageType, string $bg, ?int $facilityId = null): array
    {
        $identy   = SiteIdentityService::getSiteIdentity();
        $metaData = self::getMetaData($pageType, $facilityId);

        if ($pageType === 'detail' && $facilityId !== null) {
            $content = self::getDetailContent($facilityId);
            return [
                '@context'    => 'https://schema.org',
                '@type'       => 'Article',
                'headline'    => data_get($content, 'title'),
                'description' => data_get($content, 'description'),
                'author'      => [
                    '@type' => 'Organization',
                    'name'  => data_get($identy, 'name')
                ],
                'publisher'   => [
                    '@type' => 'Organization',
                    'name'  => data_get($identy, 'name'),
                    'logo'  => [
                        '@type' => 'ImageObject',
                        'url'   => data_get($identy, 'logo_path')
                    ]
                ],
                'image'       => data_get($content, 'images.main.src'),
                'url'         => url()->current()
            ];
        }

        return [
            '@context'    => 'https://schema.org',
            '@type'       => 'WebPage',
            'headline'    => $metaData['title'],
            'description' => $metaData['description'],
            'name'        => $metaData['title'],
            'publisher'   => [
                '@type' => 'Organization',
                'name'  => data_get($identy, 'name'),
                'logo'  => [
                    '@type' => 'ImageObject',
                    'url'   => data_get($identy, 'logo_path')
                ]
            ],
            'image'       => $bg,
            'url'         => url()->current()
        ];
    }

    /**
     * Get breadcrumb structured data for Facilities page
     *
     * @param string $pageType
     * @param int|null $facilityId
     * @return array
     */
    public static function getBreadcrumbStructuredData(string $pageType, ?int $facilityId = null): array
    {
        $breadcrumbs = [
            [
                '@type'    => 'ListItem',
                'position' => 1,
                'name'     => 'Beranda',
                'item'     => route('frontend.home')
            ],
            [
                '@type'    => 'ListItem',
                'position' => 2,
                'name'     => 'Kehidupan Kampus',
                'item'     => route('frontend.home') . '#kehidupan-kampus' // Assuming an anchor or a general campus life page
            ],
            [
                '@type'    => 'ListItem',
                'position' => 3,
                'name'     => 'Fasilitas Kampus',
                'item'     => route('frontend.campus-life.facilities.index')
            ]
        ];

        if ($pageType === 'detail' && $facilityId !== null) {
            $content       = self::getDetailContent($facilityId);
            $breadcrumbs[] = [
                '@type'    => 'ListItem',
                'position' => 4,
                'name'     => data_get($content, 'title'),
                'item'     => url()->current()
            ];
        }

        return [
            '@context'        => 'https://schema.org',
            '@type'           => 'BreadcrumbList',
            'itemListElement' => $breadcrumbs
        ];
    }
}