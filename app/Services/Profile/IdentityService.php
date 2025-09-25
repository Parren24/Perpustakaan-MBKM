<?php

namespace App\Services\Profile;

use App\Services\Frontend\SiteIdentityService;

class IdentityService
{
    /**
     * Page content for Identity
     *
     * @return array
     */
    public static function getContent(): array
    {
        return [
            'header'         => 'Panduan Identitas',
            'title'          => 'Panduan Identitas Politeknik Caltex Riau',
            'subtitle'       => 'Identitas Visual',
            'description'    => [], // General description, can be left empty
            'image'          => [
                'src' => publicMedia('identitas.webp'), // Placeholder for main featured image
                'alt' => 'Featured Image',
            ],
            'identity_guide' => [
                [
                    'id'          => 'panduan-dan-identitas',
                    'title'       => 'Panduan dan Identitas',
                    'description' => [
                        'Identitas Politeknik Caltex Riau merupakan wajah yang mencerminkan karakter, profesionalitas, dan nilai yang dipegang kampus. Agar citra tersebut semakin kuat, diperlukan keseragaman dalam penggunaan elemen visual di berbagai media komunikasi. Keseragaman ini bukan hanya soal tampilan, tetapi juga bagaimana PCR hadir sebagai institusi pendidikan yang modern, terpercaya, dan berdaya saing global.',
                        'Dokumen panduan ini disiapkan untuk menjadi acuan bagi seluruh civitas akademika dalam menggunakan identitas visual PCR. Tujuannya agar setiap pesan yang disampaikan tampil konsisten, mudah dikenali, serta mendukung reputasi PCR di tingkat nasional maupun internasional.',
                        '<ol>
                            <li>
                                <b>Status</b><br>
                                Politeknik Caltex Riau yang selanjutnya disingkat PCR adalah perguruan tinggi swasta di bidang pendidikan vokasi yang diselenggarakan oleh Yayasan Politeknik Chevron Riau yang selanjutnya disingkat YPCR (BAB I - Pasal 1)
                            </li>
                            <li>
                                <b>Kedudukan</b><br>
                                Politeknik Caltex Riau yang selanjutnya disingkat PCR adalah perguruan tinggi swasta di bidang pendidikan vokasi yang diselenggarakan oleh Yayasan Politeknik Chevron Riau yang selanjutnya disingkat YPCR (BAB II – Pasal 7)
                            </li>
                            <li>
                                <b>Hari Jadi</b><br>
                                Tanggal 31 Agustus 2001 merupakan hari pertama pelaksanaan kegiatan akademik di PCR yang ditandai dengan pembukaan orientasi studi mahasiswa angkatan pertama. Tanggal tersebut diperingati sebagai hari lahir (dies natalis) PCR (BAB III – Pasal 7)
                            </li>
                        </ol>',
                    ],
                    'images'      => [],
                    'links'       => [
                        ['text' => 'Unduh Panduan Lengkap', 'url' => 'https://drive.google.com/file/d/1ieQo5jFZXE4QqxeK6cT5HvJIb22l8iea/view'],
                    ],
                ],
                [
                    'id'          => 'logo-pcr',
                    'title'       => 'Logo PCR',
                    'description' => [
                        'Berdasarkan Statuta Politeknik Caltex Riau NOMOR: 066/YPCR/2023 BAB III Pasal 8. Ditentukan sebagai berikut :',
                        '<ol>
                            <li>Lambang PCR adalah sebagai berikut:</li>
                            <li>Lambang PCR digunakan pada bangunan, cap, ijazah dan segala bentuk atribut formal yang digunakan di dalam maupun luar lingkungan Kampus</li>
                            <li>Lambang PCR adalah lambang bertipe typetext yang merupakan satu kesatuan yang padu dan tidak dapat dipisah antar kata, serta memiliki bentuk dan warna yang telah diatur sebagai berikut:
                                <ol type="a">
                                    <li>
                                        Warna Midnight Green (Hex: #004B5F, RGB: (0, 75, 95)) 
                                        pada tulisan Politeknik Caltex Riau melambangkan kehormatan, kejujuran, kecerdasan dan keunggulan
                                    </li>
                                    <li>
                                        Lintasan orbit berwarna Pigment Red (Hex: #EE152A, RGB: (238, 21, 24))
                                        melambangkan kesiapan bersaing secara global
                                    </li>
                                </ol>
                            </li>
                        </ol>',
                    ],
                    'images'      => [['src' => publicMedia('logo-utama.webp'), 'alt' => 'Logo PCR', 'class' => 'w-50']], // Placeholder image
                    'links'       => [
                        ['text' => 'Unduh Logo', 'url' => 'https://drive.google.com/drive/folders/1YlvHkJhNVNqHKR_634wvE1H3L_G9OfVh'],
                    ],
                ],
                [
                    'id'          => 'lagu',
                    'title'       => 'Mars dan Hymne',
                    'description' => [
                        'Mars dan Hymne Politeknik Caltex Riau merupakan cerminan semangat, jati diri, serta kebanggaan sivitas akademika PCR. 
                        Melalui lantunan Mars, tergambar tekad dan motivasi untuk terus maju, berprestasi, serta berkontribusi bagi bangsa. 
                        Sementara itu, Hymne menghadirkan nuansa khidmat yang merefleksikan rasa syukur, kebersamaan, dan pengabdian. 
                        Keduanya menjadi simbol pemersatu dan penguat identitas seluruh keluarga besar Politeknik Caltex Riau.',
                        '
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="d-flex flex-column p-4 bg-light rounded-5 mt-3">
                                    <h5>Mars Politeknik Caltex Riau</h5><br>
                                    SEMANGAT BERGERAK MAJU BERSAMA <br>
                                    POLITEKNIK CALTEX RIAU<br>
                                    TINGKATKAN SUMBER DAYA MANUSIA INDONESIA <br>
                                    DALAM TEKNOLOGI DAN BISNIS TERAPAN<br>
                                    <br>
                                    BERSINAR DI KANCAH NASIONAL DAN DUNIA <br>
                                    DENGAN KARAKTER UNGGUL BERMARTABAT<br>
                                    DISIPLIN, CERDAS DAN BERINTEGRITAS, <br>
                                    PRIBADI PENUH PERCAYA DIRI<br>
                                    <br>
                                    ABDIKAN DIRI TUK MASYARAKAT <br>
                                    DENGAN ILMU, TEKNOLOGI DAN BUDAYA<br>
                                    SEBARKAN KARYA PENELITIAN <br>
                                    TUK KEMAJUAN PERADABAN BANGSA <br>
                                    <br>
                                    POLITEKNIK CALTEX RIAU<br>
                                    BERSAMA MENATAP MASA DEPAN<br>
                                    POLITEKNIK CALTEX RIAU<br>
                                    BERKARYA TUK INDONESIA<br>
                                    JAYA ALMAMATER KITA.<br>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex flex-column p-4 bg-light rounded-5 mt-3">
                                    <h5>Hymne Politeknik Caltex Riau</h5>
                                    POLITEKNIK CALTEX RIAU<br>
                                    WAHANA PENDIDIK PUTRA PUTRI BANGSA<br>
                                    UNTUK MENCAPAI CITA-CITA MULIA<br>
                                    DEMI MASA DEPAN CEMERLANG<br>
                                    <br>
                                    TUNJUKKAN KARYA BAKTIMU<br>
                                    WUJUD NYATA CINTA PADA NEGERIMU<br>
                                    DENGAN LANDASAN IMAN DAN TAKWA<br>
                                    DENGAN NILAI LUHUR BERBUDAYA<br>
                                    <br>
                                    POLITEKNIK CALTEX RIAU<br>
                                    BERKREASI MENGEMBANGKAN DIRI<br>
                                    MEMBANGUN GENERASI TERKINI<br>
                                    JADI INSAN YANG TERPUJI<br>
                                    <br>
                                    POLITEKNIK CALTEX RIAU<br>
                                    TINGKATKAN SUMBER DAYA MANUSIA<br>
                                    POLITEKNIK CALTEX RIAU<br>
                                    KEBANGGAN KITA SEMUA<br>
                                </div>
                            </div>

                        </div>
                        '
                    ],
                    'images'      => [],
                    'links'       => [
                        ['text' => 'Dengarkan Mars', 'url' => 'https://www.youtube.com/watch?v=fkfSThykNSE&list=RDfkfSThykNSE&start_radio=1'],
                        ['text' => 'Dengarkan Hymne', 'url' => 'https://www.youtube.com/watch?v=DsGulPJgFP4&list=RDDsGulPJgFP4&start_radio=1'],
                    ],
                ],
                [
                    'id'          => 'video-profil',
                    'title'       => 'Video Profil',
                    'description' => [
                        'Video Profil Politeknik Caltex Riau menampilkan perjalanan, pencapaian, serta komitmen PCR dalam mencetak generasi unggul yang berdaya saing global. Melalui tayangan ini, tersaji gambaran mengenai lingkungan akademik, fasilitas, serta nilai-nilai yang menjadi dasar pengabdian PCR bagi pendidikan, industri, dan masyarakat.',
                    ],
                    'images'      => [],
                    'video'       => asset('theme/frontend/videos/profil-pcr.mp4'),
                    'links'       => [
                        ['text' => 'Liht Video Profile', 'url' => 'https://youtu.be/D4HdqnHSQ0o?si=dXoTsDvn3GWJcqgZ']
                    ],
                ],
                [
                    'id'          => 'jenis-huruf',
                    'title'       => 'Jenis Huruf',
                    'description' => [
                        'Dalam sistem komunikasi visual Politeknik Caltex Riau, tipografi memiliki peran penting untuk menjaga konsistensi dan identitas. Jenis huruf utama yang digunakan adalah Barlow, dipilih karena tampilannya modern, sederhana, serta mudah dibaca di berbagai media. Penggunaan Barlow mendukung citra PCR yang profesional sekaligus dinamis, sehingga dapat diterapkan pada berbagai kebutuhan komunikasi, baik formal maupun non-formal. Dengan variasi ukuran dan ketebalan yang tersedia, tipografi ini membantu menyampaikan pesan dengan jelas dan tetap seragam.',
                    ],
                    'images'      => [],
                    'links'       => [
                        ['text' => 'Unduh Huruf', 'url' => 'https://drive.google.com/drive/folders/1wQ-D9NQMaQ4qBKti1AMLs21BbYLpr6An?usp=drive_link'],
                    ],
                ],
                [
                    'id'          => 'elemen-grafis',
                    'title'       => 'Elemen Grafis',
                    'description' => [
                        'Perancangan elemen grafis dalam komunikasi visual Politeknik Caltex Riau bertujuan untuk memperkuat identitas kampus sekaligus menghadirkan karakter yang membedakan PCR dari institusi lain. Elemen grafis tersebut dibentuk menjadi 4 motif melayu yang dapat digabungkan menjadi 1 floral. Setiap elemen visual dirancang agar mampu mencerminkan nilai-nilai yang dijunjung, serta memberikan kesan yang khas dalam setiap bentuk komunikasi.',
                        'Konsep perancangan disusun terdari 4 nilai, yaitu :
                        <ol>
                            <li>TEACHING UNIVERSITY</li>
                            <li>PRAKTIKUM</li>
                            <li>DIGITALISASI</li>
                            <li>ROBOTIKA</li>
                        </ol>',
                    ],
                    'images'      => [],
                    'links'       => [
                        ['text' => 'Unduh Elemen Grafis', 'url' => 'https://drive.google.com/drive/folders/1hRsn91wvCmZOnptvcCoXskd7CqgNeWX3?usp=drive_link'],
                    ],
                ],
                [
                    'id'          => 'media-promosi',
                    'title'       => 'Media Promosi',
                    'description' => [
                        'Media promosi Politeknik Caltex Riau hadir sebagai sarana untuk memperkenalkan identitas, keunggulan, serta prestasi kampus kepada masyarakat luas. Melalui berbagai bentuk publikasi, PCR meneguhkan komitmen dalam menghadirkan pendidikan berkualitas, membangun citra positif, dan memperkuat hubungan dengan dunia industri maupun masyarakat',
                    ],
                    'images'      => [],
                    'links'       => [
                        ['text' => 'Download Media Promosi', 'url' => 'https://drive.google.com/drive/folders/1AunPdH6YOnSzYWP2jNalRtiw_mcLpr_f?usp=sharing'],
                    ],
                ],
                [
                    'id'          => 'template-resmi-pcr',
                    'title'       => 'Template Resmi Politeknik Caltex Riau',
                    'description' => [
                        'Template Resmi Politeknik Caltex Riau disediakan sebagai panduan standar dalam setiap kebutuhan desain dan komunikasi visual kampus. Berbagai format tersedia, mulai dari sertifikat, presentasi (PPT), kop surat, amplop, folder, backdrop, baliho, spanduk, hingga aset elemen grafis PCR. Kehadiran template ini bertujuan untuk menjaga konsistensi identitas visual, memperkuat citra institusi, serta memudahkan sivitas akademika dalam menghasilkan media komunikasi yang seragam, profesional, dan representatif.',
                    ],
                    'images'      => [],
                    'links'       => [
                        ['text' => 'Unduh Template Resmi', 'url' => 'https://drive.google.com/drive/folders/103TwahyG_2QBcYL3PLdkVnhMkuvLUlBi?usp=drive_link'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Meta data for SEO
     *
     * @return array
     */
    public static function getMetaData(): array
    {
        return [
            'title'       => data_get(self::getContent(), 'title'),
            'description' => 'Panduan lengkap mengenai identitas Politeknik Caltex Riau, termasuk logo, lagu, jenis huruf, dan media promosi.',
            'keywords'    => 'identitas PCR, panduan identitas, logo PCR, Politeknik Caltex Riau',
        ];
    }

    /**
     * Page configuration including SEO structure
     *
     * @return array
     */
    public static function getPageConfig(): array
    {
        $meta = self::getMetaData();
        $bg   = publicMedia('identitas.webp'); // Placeholder background image

        return [
            'background_image' => $bg,
            'seo'              => [
                'title'                      => data_get($meta, 'title'),
                'description'                => data_get($meta, 'description'),
                'keywords'                   => data_get($meta, 'keywords'),
                'canonical'                  => data_get($meta, 'canonical'),
                'og_image'                   => $bg,
                'og_type'                    => 'article',
                'structured_data'            => self::getStructuredData($bg),
                'breadcrumb_structured_data' => self::getBreadcrumbStructuredData()
            ]
        ];
    }

    /**
     * Structured data (JSON-LD) for this page
     *
     * @return array
     */
    public static function getStructuredData(string $bg): array
    {
        $identy   = SiteIdentityService::getSiteIdentity();
        $metaData = self::getMetaData();

        return [
            '@context'    => 'https://schema.org',
            '@type'       => 'Article',
            'headline'    => $metaData['title'],
            'description' => $metaData['description'],
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
            'image'       => $bg,
            'url'         => url()->current()
        ];
    }

    /**
     * Breadcrumb structured data
     *
     * @return array
     */
    public static function getBreadcrumbStructuredData(): array
    {
        return [
            '@context'        => 'https://schema.org',
            '@type'           => 'BreadcrumbList',
            'itemListElement' => [
                [
                    '@type'    => 'ListItem',
                    'position' => 1,
                    'name'     => 'Beranda',
                    'item'     => route('frontend.home')
                ],
                [
                    '@type'    => 'ListItem',
                    'position' => 2,
                    'name'     => 'Profil',
                    'item'     => route('frontend.home') . '#profil'
                ],
                [
                    '@type'    => 'ListItem',
                    'position' => 3,
                    'name'     => data_get(self::getContent(), 'title'),
                    'item'     => url()->current()
                ]
            ]
        ];
    }
}