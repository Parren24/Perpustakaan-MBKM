<?php

namespace App\Services\Profile;

use App\Services\Frontend\SiteIdentityService;

/**
 * Hero Service
 * 
 * Service untuk mengelola data dan logic terkait history page
 * 
 * @author wahyudibinsaid
 */
class HistoryService
{
    /**
     * Get content for history page
     * In the future, this can be moved to database or external API
     *
     * @return array
     */
    public static function getContent()
    {
        return [
            'header'   => 'Sejarah',
            'title'    => 'Sejarah Politeknik Caltex Riau',
            'subtitle' => 'Sejak tahun 2000',
            'timeline' => [
                [
                    'id'      => 'pendirian-awal-operasional',
                    'title'   => 'Pendirian dan Awal Operasional',
                    'year'    => '2000 – 2005',
                    'content' => [
                        'Tahun 2000 menjadi tonggak sejarah berdirinya Politeknik Caltex Riau (PCR). Lembaga ini lahir atas inisiatif Pemerintah Provinsi Riau dan PT Caltex Pacific Indonesia (CPI) sebagai wujud kolaborasi strategis antara pemerintah dan industri untuk menghadirkan pendidikan vokasi berkualitas di Riau.',
                        'Memulai operasional pada tahun 2001, PCR didukung penuh oleh PT CPI, membuka tiga program studi Diploma 3 (D3). Dua tahun kemudian, tepatnya 2003, PCR menambah dua program studi D3 baru, memperluas pilihan pendidikan bagi calon mahasiswa.'
                    ],
                    'images'  => [
                        [
                            'src' => publicMedia('sejarah-2.webp'),
                            'alt' => 'Pendirian Politeknik Caltex Riau tahun 2000'
                        ],
                        [
                            'src' => publicMedia('sejarah-3.webp'),
                            'alt' => 'Pendirian Politeknik Caltex Riau tahun 2000'
                        ],
                        [
                            'src' => publicMedia('sejarah-4.webp'),
                            'alt' => 'Pendirian Politeknik Caltex Riau tahun 2000'
                        ],
                    ]
                ],
                [
                    'id'      => 'transisi-kemandirian',
                    'title'   => 'Transisi dan Kemandirian',
                    'year'    => '2005 – 2007',
                    'content' => [
                        'Memasuki 2005, dukungan PT CPI mulai bersifat parsial. Meskipun demikian, PCR tetap menunjukkan perkembangan positif. Tahun 2006 menjadi momentum penting dengan dibukanya tiga program studi Diploma 4 (D4).',
                        'Setahun setelahnya, pada 2007, PCR resmi mandiri secara finansial. Langkah ini menandai kematangan institusi untuk mengelola sumber daya dan keberlanjutan operasionalnya secara independen.'
                    ],
                    'images'  => [
                        [
                            'src' => publicMedia('sejarah-5.webp'),
                            'alt' => 'Pendirian Politeknik Caltex Riau tahun 2000'
                        ],
                        [
                            'src' => publicMedia('sejarah-6.webp'),
                            'alt' => 'Pendirian Politeknik Caltex Riau tahun 2000'
                        ],
                    ]
                ],
                [
                    'id'      => 'penguatan-organisasi-reputasi',
                    'title'   => 'Penguatan Organisasi dan Reputasi',
                    'year'    => '2012 – 2016',
                    'content' => [
                        'Tahun 2012, PCR mencatat sejarah baru dengan dilantiknya direktur pertama yang berasal dari kalangan dosen internal. Pada periode ini, PCR juga semakin kokoh secara finansial.',
                        'Tiga tahun kemudian, 2015, PCR berhasil meraih akreditasi institusi pertama dengan predikat "B" dari Badan Akreditasi Nasional Perguruan Tinggi (BAN-PT), sebuah pengakuan atas mutu penyelenggaraan pendidikan.',
                        'Di tahun 2016, PCR kembali mengembangkan kapasitas akademiknya dengan menambah dua program studi D4.'
                    ],
                    'images'  => []
                ],
                [
                    'id'      => 'prestasi-ekspansi-akademik',
                    'title'   => 'Prestasi dan Ekspansi Akademik',
                    'year'    => '2018 – 2022',
                    'content' => [
                        'Komitmen terhadap mutu mulai terlihat nyata pada 2018, ketika 50% program studi PCR telah meraih akreditasi A.',
                        'Dua tahun kemudian, pada 2020, PCR membuka program studi Magister Terapan (S2) pertama, sekaligus meningkatkan persentase akreditasi A menjadi 60%.',
                        'Tahun 2022 menjadi babak baru, ketika seluruh program studi D3 resmi bertransformasi menjadi program studi D4, selaras dengan kebijakan pendidikan vokasi nasional.'
                    ],
                    'images'  => []
                ],
                [
                    'id'      => 'peningkatan-mutu-keunggulan',
                    'title'   => 'Peningkatan Mutu dan Keunggulan',
                    'year'    => '2024 – 2025',
                    'content' => [
                        'PCR terus bergerak maju. Tahun 2024, dua program studi D4 baru dibuka dan akreditasi A meningkat menjadi 64%.',
                        'Puncak capaian hadir pada 2025, ketika PCR meraih predikat Akreditasi Institusi Unggul, mengukuhkan posisinya sebagai salah satu perguruan tinggi vokasi terbaik di Indonesia.'
                    ],
                    'images'  => [
                        [
                            'src' => publicMedia('sejarah-unggul.webp'),
                            'alt' => 'PCR meraih Akreditasi Institusi Unggul tahun 2025'
                        ]
                    ]
                ]
            ]
        ];
    }

    /**
     * Get table of contents for history page
     *
     * @return array
     */
    /**
     * Get table of contents for history page
     *
     * @return array
     */
    public static function getTableOfContents()
    {
        $content = self::getContent();
        $entries = data_get($content, 'timeline', []);

        return array_map(function ($entry) {
            return [
                'id'    => $entry['id'],
                'title' => $entry['title']
            ];
        }, $entries);
    }

    /**
     * Get specific history entry by ID
     *
     * @param string $id
     * @return array|null
     */
    public static function getEntry(string $id)
    {
        $content = self::getContent();
        $entries = data_get($content, 'timeline', []);

        foreach ($entries as $entry) {
            if (($entry['id'] ?? null) === $id) {
                return $entry;
            }
        }

        return null;
    }

    /**
     * Search history entries by keyword
     *
     * @param string $keyword
     * @return array
     */
    public static function search(string $keyword): array
    {
        $content = self::getContent();
        $entries = data_get($content, 'timeline', []);
        $results = [];

        foreach ($entries as $entry) {
            if (isset($entry['title']) && stripos($entry['title'], $keyword) !== false) {
                $results[] = $entry;
                continue;
            }

            foreach ($entry['content'] ?? [] as $paragraph) {
                if (stripos($paragraph, $keyword) !== false) {
                    $results[] = $entry;
                    break;
                }
            }
        }

        return $results;
    }

    /**
     * Get formatted history data for SEO/meta purposes
     *
     * @return array
     */
    public static function getMetaData(): array
    {
        return [
            'title'       => data_get(self::getContent(), 'title'),
            'description' => 'Pelajari perjalanan sejarah Politeknik Caltex Riau dari tahun 2000 hingga meraih Akreditasi Institusi Unggul pada 2025.',
            'keywords'    => 'sejarah PCR, politeknik caltex riau, akreditasi unggul, pendidikan vokasi',
        ];
    }

    /**
     * Get page configuration for history page
     *
     * @return array
     */
    public static function getPageConfig(): array
    {
        $meta = self::getMetaData();
        $bg   = publicMedia('sejarah-bg.webp');

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
     * Get structured data for SEO
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
     * Get breadcrumb structured data for SEO
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
