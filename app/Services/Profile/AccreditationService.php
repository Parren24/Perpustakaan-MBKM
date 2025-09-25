<?php

namespace App\Services\Profile;

use App\Services\Frontend\SiteIdentityService;

class AccreditationService
{
    public static function getContent(): array
    {
        return [
            'header'       => 'Akreditasi',
            'title'        => 'Politeknik Caltex Riau Terakreditasi <b>Unggul</b>',
            'subtitle'     => 'Akreditasi Institusi',
            'description'  => [
                'Politeknik Caltex Riau telah terakreditasi Unggul oleh BAN-PT. Pencapaian ini menjadikan Politeknik Caltex Riau satu-satunya Politeknik Swasta dengan Akreditasi Unggul di Indonesia.',
            ],
            'grid'         => [
                'title'    => '<span>Riwayat</span> Akreditasi Institusi',
                'subtitle' => 'Sejak PCR Berdiri'
            ],
            'certificates' => [
                [
                    'title' => 'Sertifikat Akreditasi Institusi Unggul',
                    'date'  => '2025-03-11',
                    'image' => [
                        'src' => publicMedia('akreditasi-pt-2025.webp'),
                        'alt' => ''
                    ]
                ],
                [
                    'title' => 'Sertifikat Akreditasi Institusi B',
                    'date'  => '2020-07-5',
                    'image' => [
                        'src' => publicMedia('akreditasi-pt-2020.webp'),
                        'alt' => ''
                    ]
                ]
            ]
        ];
    }

    public static function getMetaData(): array
    {
        return [
            'title'       => 'Akreditasi - Politeknik Caltex Riau',
            'description' => 'Informasi akreditasi dan sertifikat Politeknik Caltex Riau.',
            'keywords'    => 'akreditasi, sertifikat, politeknik caltex riau'
        ];
    }

    public static function getPageConfig(): array
    {
        $meta = self::getMetaData();
        $bg   = function_exists('publicMedia') ? publicMedia('akreditasi-bg.webp') : '/uploads/placeholder/akreditasi-bg.webp';

        return [
            'background_image' => $bg,
            'seo'              => [
                'title'                      => data_get($meta, 'title'),
                'description'                => data_get($meta, 'description'),
                'keywords'                   => data_get($meta, 'keywords'),
                'canonical'                  => route('frontend.profile.accreditation'),
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
