<?php

namespace App\Services\Profile;

use App\Services\Frontend\SiteIdentityService;

class OrganizationService
{
    /**
     * Page content for Organization
     *
     * @return array
     */
    public static function getContent(): array
    {
        return [
            'header'              => 'Organisasi dan Profil Pimpinan',
            'title'               => 'Organisasi dan Profil Pimpinan',
            'subtitle'            => '', // Can be filled later if needed
            'description'         => [
                'Struktur organisasi Politeknik Caltex Riau dirancang untuk menghadirkan tata kelola yang kuat, layanan akademik bermutu, dan pengalaman kemahasiswaan yang berdampak. Direktur menetapkan arah strategis institusi, sementara para Wakil Direktur memimpin beberapa cakupan layanan diantaranya pengembangan akademik dan inovasi pembelajaran, pengelolaan sumber daya dan infrastruktur, penguatan keuanganperencanaan kelembagaan serta kemahasiswaan, pemasaran dan kemitraan.',
            ],
            'image'               => [
                'src' => publicMedia('organisasi.svg'), // Placeholder for main featured image
                'alt' => 'Organization Structure',
            ],
            'struktur_organisasi' => [
                [
                    'id'      => '000',
                    'jabatan' => 'Direktur',
                    'pejabat' => 'Dr. Dadang Syarif Sihabudin Sahid, S.Si., M.Sc.',
                    'profil'  => [
                        'Memimpin penyelenggaraan Tridharma Perguruan Tinggi serta tata kelola institusi secara menyeluruh. Menetapkan arah strategis, memastikan kepatuhan regulasi dan akreditasi, membangun kemitraan nasional–internasional, serta mengawasi kinerja organisasi dan pemanfaatan sumber daya secara akuntabel dan berkelanjutan.',
                    ],
                    'image'   => [
                        'src' => publicMedia('DDS.webp', ['jurusan', 'dosen']),
                        'alt' => 'Dr. Dadang Syarif Sihabudin Sahid',
                    ],
                    'link'    => [
                        [
                            'url'  => route('frontend.academic.lecturer-profile', ['dadang-syarif-sihabudin-sahid']),
                            'text' => 'Lihat Profil'
                        ]
                    ],
                ],
                [
                    'id'      => '001',
                    'jabatan' => 'Wakil Direktur Bidang Akdemik dan Inovasi Pembelajaran',
                    'pejabat' => 'Made Rahmawaty, S.T., M.Eng.',
                    'profil'  => [
                        'Bertanggung jawab atas mutu akademik: kurikulum, kalender dan proses pembelajaran, evaluasi belajar, serta pengembangan dosen. Mendorong inovasi pedagogi (OBE, pembelajaran digital/MBKM, micro-credential), penjaminan mutu akademik, dan pembaruan program studi agar relevan dengan kebutuhan industri dan perkembangan ilmu.',
                    ],
                    'image'   => [
                        'src' => publicMedia('MDR.webp', ['jurusan', 'dosen']),
                        'alt' => 'Made Rahmawaty',
                    ],
                    'link'    => [
                        [
                            'url'  => route('frontend.academic.lecturer-profile', ['made-rahmawaty']),
                            'text' => 'Lihat Profil'
                        ]
                    ],
                ],
                [
                    'id'      => '002',
                    'jabatan' => 'Wakil Direktur Bidang Sumber Daya',
                    'pejabat' => 'Istianah Muslim, S.T., M.T.',
                    'profil'  => [
                        'Mengelola SDM (rekrutmen, pengembangan kompetensi, penilaian kinerja) serta sarana-prasarana dan sistem layanan internal. Menjamin ketersediaan, pemeliharaan, dan keamanan aset kampus, termasuk TIK dan K3L, untuk mendukung operasional yang efisien, inklusif, dan berorientasi pada keberlanjutan.',
                    ],
                    'image'   => [
                        'src' => publicMedia('ISM.webp', ['jurusan', 'dosen']),
                        'alt' => 'Istianah Muslim',
                    ],
                    'link'    => [
                        [
                            'url'  => route('frontend.academic.lecturer-profile', ['istianah-muslim']),
                            'text' => 'Lihat Profil'
                        ]
                    ],
                ],
                [
                    'id'      => '003',
                    'jabatan' => 'Wakil Direktur Bidang Keuangan, Perencanaan dan Kelembagaan',
                    'pejabat' => 'Erwin Setyo Nugroho, S.T., M.Eng.',
                    'profil'  => [
                        'Mengkoordinasikan perencanaan strategis dan penyusunan anggaran (RKAT), pengelolaan keuangan berbasis kinerja, pelaporan dan audit. Memimpin pengembangan kelembagaan dan tata kelola, manajemen risiko, serta proses akreditasi institusi dan program studi guna memastikan akuntabilitas dan daya saing.',
                    ],
                    'image'   => [
                        'src' => publicMedia('ESN.webp', ['jurusan', 'dosen']),
                        'alt' => 'Erwin Setyo Nugroho',
                    ],
                    'link'    => [
                        [
                            'url'  => route('frontend.academic.lecturer-profile', ['erwin-setyo-nugroho']),
                            'text' => 'Lihat Profil'
                        ]
                    ],
                ],
                [
                    'id'      => '004',
                    'jabatan' => 'Wakil Direktur Bidang Kemahasiswaan, Pemasaran dan Kemitraan',
                    'pejabat' => 'Zainal Arifin Renaldo, S.S., M.Hum.',
                    'profil'  => [
                        'Mengampu layanan kemahasiswaan, pembinaan organisasi/UKM, karier dan tracer study. Mengelola promosi dan penerimaan mahasiswa baru serta penguatan brand. Membangun kemitraan strategis dengan industri, pemerintah, dan alumni untuk magang, proyek kolaboratif, dan penyerapan lulusan.',
                    ],
                    'image'   => [
                        'src' => publicMedia('JAY.webp', ['jurusan', 'dosen']),
                        'alt' => 'Zainal Arifin Renaldo',
                    ],
                    'link'    => [
                        [
                            'url'  => route('frontend.academic.lecturer-profile', ['zainal-arifin-renaldo']),
                            'text' => 'Lihat Profil'
                        ]
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
            'description' => 'Informasi mengenai struktur organisasi dan profil pimpinan Politeknik Caltex Riau.',
            'keywords'    => 'struktur organisasi PCR, profil pimpinan PCR, Politeknik Caltex Riau',
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
        $bg   = publicMedia('organisasi-bg.webp'); // Placeholder background image

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