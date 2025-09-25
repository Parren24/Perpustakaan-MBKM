<?php

namespace App\Services\Academic;

use App\Services\Frontend\SiteIdentityService;

class ScholarshipService
{
    /**
     * Get content for Scholarship page
     *
     * @return array
     */
    public static function getContent(): array
    {
        return [
            'header'      => 'Beasiswa',
            'title'       => 'Program <b>Beasiswa Pendidikan</b> di Politeknik Caltex Riau',
            'subtitle'    => 'Tentang Beasiswa',
            'description' => [
                'Berangkat dari komitmen terhadap akses dan pemerataan, Politeknik Caltex Riau menyediakan beragam skema beasiswa untuk mendukung mahasiswa berprestasi 
                sekaligus mereka yang membutuhkan dukungan finansial. Tujuannya agar setiap talenta memiliki kesempatan yang sama untuk menempuh pendidikan berkualitas, 
                berkembang secara akademik maupun karakter, dan siap bersaing di dunia kerja. Dukungan beasiswa tidak hanya mencakup keringanan biaya pendidikan, 
                tetapi juga dapat mencakup bantuan biaya hidup, sehingga mahasiswa dapat fokus pada proses belajar tanpa terbebani kendala ekonomi.'
            ],
            'image'       => [
                'src' => publicMedia('beasiswa.webp'),
                'alt' => 'About Scholarship'
            ],
            'scholarship' => [
                'title'       => 'Temukan Program <b>Beasiswa yang Sesuai</b>',
                'subtitle'    => 'Jenis Beasiswa',
                'description' => 'Kamu dapat menemukan berbagai jenis beasiswa yang kami tawarkan, mulai dari beasiswa berprestasi, beasiswa keluarga kurang mampu, hingga beasiswa kerja sama khusus dari berbagai institusi.',
                'list'        => [
                    [
                        'beasiswa'    => 'Beasiswa Yayasan PCR',
                        'program'     => 'Internal',
                        'description' => 'Beasiswa yang bersumber dari Yayasan Politeknik Chevron Riau ini diberikan kepada calon mahasiswa yang berprestasi namun kurang mampu secara finansial',
                        'image'       => [
                            'src' => publicMedia('beasiswa-ypcr.webp'),
                            'alt' => 'Beasiswa Yayasan PCR'
                        ],
                        'link'        => [],
                    ],
                    [
                        'beasiswa'    => 'Beasiswa Pemerintah Daerah',
                        'program'     => 'Pemerintah',
                        'description' => 'Beasiswa yang bersumber dari Pemerintah Provinsi Riau dan Kabupaten Kota diberikan kepada mahasiswa aktif Politeknik Calex Riau yang berprestasi dan berasal dari tamatan Provinsi Riau, terdapat juga beasiswa pendidikan dari kabupaten Rokan Hilir, Siak dan Kota Dumai yang diperuntukkan bagi putera/puteri dari daerah tersebut.',
                        'image'       => [
                            'src' => publicMedia('beasiswa-daerah.webp'),
                            'alt' => 'Beasiswa Pemerintah Daerah'
                        ],
                        'link'        => [],
                    ],
                    [
                        'beasiswa'    => 'Beasiswa KIPK',
                        'program'     => 'Pemerintah',
                        'description' => 'Beasiswa yang bersumber dari Kemenristekdikti ini diberikan kepada lulusan SMA/SMK/MA berprestasi, namun kurang mampu secara finansial untuk menempuh studi di Politeknik Caltex Riau. Beasiswa ini meliputi biaya SPP dan biaya hidup selama mengikuti perkuliahan di PCR.',
                        'image'       => [
                            'src' => publicMedia('beasiswa-kip.webp'),
                            'alt' => ''
                        ],
                        'link'        => [],
                    ],
                    [
                        'beasiswa'    => 'Beasiswa SDM Sawit',
                        'program'     => 'Lembaga',
                        'description' => 'Beasiswa yang bersumber dari Direktorat Jenderal Perkebunan dengan dukungan pendanaan dari Badan Pengelola Dana Perkebunan Kelapa Sawit (BPDPKS). Beasiswa ini meliputi biaya SPP dan biaya hidup selama mengikuti perkuliahan di PCR.',
                        'image'       => [
                            'src' => publicMedia('beasiswa-sawit.webp'),
                            'alt' => ''
                        ],
                        'link'        => [],
                    ],
                    [
                        'beasiswa'    => 'Beasiswa Alumni',
                        'program'     => 'Internal',
                        'description' => 'Bersumber dari Ikatan Keluarga Alumni Politeknik Caltex Riau ini diberikan kepada mahasiswa yang berprestasi namun kurang mampu secara finansial',
                        'image'       => [
                            'src' => publicMedia('beasiswa-ika.webp'),
                            'alt' => ''
                        ],
                        'link'        => [],
                    ]
                ]
            ]
        ];
    }

    /**
     * Get meta data for Scholarship page
     *
     * @return array
     */
    public static function getMetaData(): array
    {
        return [
            'title'       => data_get(self::getContent(), 'title'),
            'description' => data_get(self::getContent(), 'description.0'),
            'keywords'    => 'beasiswa, politeknik caltex riau, pcr, bantuan pendidikan',
        ];
    }

    /**
     * Get page configuration for Scholarship page
     *
     * @return array
     */
    public static function getPageConfig(): array
    {
        $meta = self::getMetaData();
        $bg   = publicMedia('beasiswa-bg.webp');

        return [
            'background_image' => $bg,
            'seo'              => [
                'title'                      => data_get($meta, 'title'),
                'description'                => data_get($meta, 'description'),
                'keywords'                   => data_get($meta, 'keywords'),
                'canonical'                  => route('frontend.academic.scholarship'),
                'og_image'                   => function_exists('publicMedia') ? publicMedia('scholarship-og.webp') : '/uploads/placeholder/scholarship-og.webp',
                'og_type'                    => 'article',
                'structured_data'            => self::getStructuredData($bg),
                'breadcrumb_structured_data' => self::getBreadcrumbStructuredData()
            ]
        ];
    }

    /**
     * Get structured data for Scholarship page
     *
     * @param string $bg
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
     * Get breadcrumb structured data for Scholarship page
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
                    'name'     => 'Akademik',
                    'item'     => route('frontend.home') . '#akademik' // Assuming an anchor or a general academic page
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