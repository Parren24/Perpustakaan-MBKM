<?php

namespace App\Services\CampusLife;

use App\Services\Frontend\PMBService;
use App\Services\Frontend\SiteIdentityService;

class PCRSquadService
{
    /**
     * Get content for PCR Squad page
     *
     * @return array
     */
    public static function getContent(): array
    {
        $pmb = PMBService::getPMBData();


        return [
            'header'      => 'PCR Squad',
            'title'       => 'Bergabung Menjadi Bagian PCR Squad',
            'description' => 'Bergabunglah dengan PCR Squad! Temukan informasi lengkap mengenai pendaftaran mahasiswa baru, syarat dan ketentuan, serta berbagai program unggulan di Politeknik Caltex Riau.',
            'pmb'         => [
                'title'       => 'Gabung Jadi <b>Squad Mahasiswa</b>',
                'subtitle'    => 'Penerimaan Mahasiswa Baru',
                'description' => data_get($pmb, 'content.description'),
                'images'      => [
                    'main'  => [
                        'src' => publicMedia('pcr-squad-main.webp'),
                        'alt' => ''
                    ],
                    'thumb' => [
                        'src' => publicMedia('pcr-squad-thumb.webp'),
                        'alt' => ''
                    ],
                ],
                'link'        => [
                    [
                        'url'  => data_get($pmb, 'actions.primary.url'),
                        'text' => 'Gabung Sekarang'
                    ]
                ],
            ],
            'recruitment' => [
                'title'       => 'Gabung Jadi <br><b>Squad Karyawan</b>',
                'subtitle'    => 'Rekrutmen Karyawawan',
                'description' => 'Untuk mendukung kemajuan pendidikan dan memenuhi kepercayaan masyarakat yang terus meningkat pada kualitas pendidikan di Politeknik Caltex Riau, 
                                kami berkomitmen membuka peluang bagi siapapun untuk bergabung menjadi bagian dari keluarga besar Politeknik Caltex Riau.',
                'image'       => [
                    'src' => publicMedia('pcr-squad-staf.webp'),
                    'alt' => 'PCR Squad Recruitment'
                ],
                'link'        => [
                    [
                        'url'  => 'https://scc.pcr.ac.id',
                        'text' => 'Lihat Lowongan Sekarang'
                    ]
                ],
            ],
            'why_pcr'     => [
                'title'       => '<u>Mengapa</u> Memilih PCR?',
                'subtitle'    => 'PCR Unggul',
                'description' => 'Ada banyak alasan kenapa kamu harus memeprtimbangkan untuk bergabung menjadi bagian dari PCR Squad',
                'items'       => data_get($pmb, 'why_pcr')
            ]
        ];
    }

    /**
     * Get meta data for PCR Squad page
     *
     * @return array
     */
    public static function getMetaData(): array
    {
        return [
            'title'       => data_get(self::getContent(), 'title'),
            'description' => data_get(self::getContent(), 'description'),
            'keywords'    => 'pcr squad, pmb, penerimaan mahasiswa baru, politeknik caltex riau, pendaftaran',
        ];
    }

    /**
     * Get page configuration for PCR Squad page
     *
     * @return array
     */
    public static function getPageConfig(): array
    {
        $meta = self::getMetaData();
        $bg   = publicMedia('pcr-squad.webp');

        return [
            'background_image' => $bg, // No specific background image for this page as per current view
            'seo'              => [
                'title'                      => data_get($meta, 'title'),
                'description'                => data_get($meta, 'description'),
                'keywords'                   => data_get($meta, 'keywords'),
                'canonical'                  => route('frontend.pcr-squad.index'),
                'og_image'                   => $bg,
                'og_type'                    => 'website',
                'structured_data'            => self::getStructuredData($bg),
                'breadcrumb_structured_data' => self::getBreadcrumbStructuredData()
            ]
        ];
    }

    /**
     * Get structured data for PCR Squad page
     *
     * @return array
     */
    public static function getStructuredData($bg): array
    {
        $identy   = SiteIdentityService::getSiteIdentity();
        $metaData = self::getMetaData();

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
     * Get breadcrumb structured data for PCR Squad page
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
                    'name'     => 'PCR Squad',
                    'item'     => url()->current()
                ]
            ]
        ];
    }
}