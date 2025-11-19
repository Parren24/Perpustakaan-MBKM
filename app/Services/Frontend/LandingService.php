<?php

namespace App\Services\Frontend;

use App\Models\Dimension\Jurusan;
use App\Models\Konten\Konten;

class LandingService
{
    /**
     * Get content data for landing page sections
     *
     * @return object|null
     */
    public static function getContent(): array
    {
        return [
            'header'      => '',
            'title'       => 'Beranda',
            'subtitle'    => '',
            'description' => 'Lakukan PEminjaman Buku dengan mudah melalui Aplikasi Perpustakaan Politeknik Caltex Riau',
        ];
    }

    /**
     * Get infografis image path for landing page
     *
     * @return string
     */
    public static function getInfografisImage(): string
    {
        return publicMedia('info-grafis.jpg');
    }

    /**
     * Get facts and statistics data for landing page
     *
     * @return array Array of statistics with icon, value, label, and animation properties
     */
    public static function getFactsAndStatisticsCallout(): array
    {
        return [
            'title'       => '<b>Infografis</b> Politeknik Caltex Riau',
            'subtitle'    => 'Fakta dan Statistik',
            'description' => 'Politeknik Caltex Riau merupakan salah satu Politeknik Swasta Terbaik di Indonesia yang berada di Kota Pekanbaru Provinsi Riau. Politeknik Caltex Riau memiliki budaya disiplin, kebersamaan dan cinta lingkungan.',
            'image'       => [
                'src' => publicMedia('info-grafis.webp'),
                'alt' => ''
            ],
            'data'        => [
                // Row 1 - Academic Excellence
                [
                    'icon'      => 'fa-solid fa-medal',
                    'value'     => 'Unggul',
                    'label'     => 'Akreditasi Prodi',
                    'important' => true,
                    'delay'     => '0.1s'
                ],
                [
                    'icon'      => 'fa-solid fa-book',
                    'value'     => '13',
                    'label'     => 'Program Studi',
                    'important' => false,
                    'delay'     => '0.2s'
                ],
                [
                    'icon'      => 'fa-solid fa-users',
                    'value'     => '3400',
                    'label'     => 'Mahasiswa Aktif',
                    'important' => false,
                    'delay'     => '0.9s',
                    'counter'   => true,
                    'suffix'    => '+'
                ],
                [
                    'icon'      => 'fa-solid fa-flask',
                    'value'     => '60',
                    'label'     => 'Praktikum',
                    'important' => false,
                    'delay'     => '1.3s',
                    'counter'   => true,
                    'suffix'    => '%'
                ],

                // Row 2 - Partnership dan Faculty
                [
                    'icon'      => 'fa-solid fa-handshake',
                    'value'     => '50',
                    'label'     => 'Mitra Kerjasama',
                    'important' => true,
                    'delay'     => '0.8s',
                    'suffix'    => '+'
                ],
                [
                    'icon'      => 'fa-solid fa-chalkboard-user',
                    'value'     => '97',
                    'label'     => 'Dosen',
                    'important' => false,
                    'delay'     => '1.1s',
                    'counter'   => true,
                ],
                [
                    'icon'      => 'fa-solid fa-user-graduate',
                    'value'     => 'Satu',
                    'label'     => 'Guru Besar',
                    'important' => false,
                    'delay'     => '1.2s',
                ],
                [
                    'icon'      => 'fa-solid fa-heart',
                    'value'     => '100',
                    'label'     => 'Mahasiswa Diasuransikan',
                    'important' => false,
                    'delay'     => '1.6s',
                    'counter'   => true,
                    'suffix'    => '%'
                ],

                // Row 3 - Employment dan Performance
                [
                    'icon'      => 'fa-solid fa-earth-americas',
                    'value'     => '15',
                    'label'     => 'Sebaran Alumni',
                    'important' => true,
                    'delay'     => '0.3s',
                    'suffix'    => 'Negara'
                ],
                [
                    'icon'      => 'fa-solid fa-briefcase',
                    'value'     => '40',
                    'label'     => 'Bekerja Sebelum Lulus',
                    'important' => false,
                    'delay'     => '0.5s',
                    'counter'   => true,
                    'suffix'    => '%'
                ],
                [
                    'icon'      => 'fa-solid fa-clock',
                    'value'     => '< 2',
                    'label'     => 'Masa Tunggu Lulusan',
                    'important' => false,
                    'delay'     => '0.4s',
                    'suffix'    => ' Bulan'
                ],
                [
                    'icon'      => 'fa-solid fa-users-line',
                    'value'     => '5000',
                    'label'     => 'Alumni',
                    'important' => false,
                    'delay'     => '1.0s',
                    'counter'   => true,
                    'suffix'    => '+'
                ],

                // Row 4 - Certification dan Facilities
                [
                    'icon'      => 'fa-solid fa-graduation-cap',
                    'value'     => '100',
                    'label'     => 'Lulusan Bersertifikasi',
                    'important' => true,
                    'delay'     => '0.7s',
                    'counter'   => true,
                    'suffix'    => '%'
                ],
                [
                    'icon'      => 'fa-solid fa-file-invoice',
                    'value'     => '400',
                    'label'     => 'Penerima Beasiswa',
                    'important' => false,
                    'delay'     => '0.6s',
                    'counter'   => true,
                    'suffix'    => '+'
                ],
                [
                    'icon'      => 'fa-solid fa-building',
                    'value'     => '100',
                    'label'     => 'Fasilitas Modern',
                    'important' => false,
                    'delay'     => '1.4s',
                    'counter'   => true,
                    'suffix'    => '%'
                ],
                [
                    'icon'      => 'fa-solid fa-screwdriver-wrench',
                    'value'     => '30',
                    'label'     => 'Lab Modern',
                    'important' => false,
                    'delay'     => '1.5s',
                    'counter'   => true,
                    'suffix'    => '+'
                ]
            ]
        ];
    }

    /**
     * Get meta data for Landing page
     *
     * @return array
     */
    public static function getMetaData(): array
    {
        return [
            'title'       => data_get(self::getContent(), 'title'),
            'description' => 'Politeknik Caltex Riau (PCR) adalah perguruan tinggi di Riau yang didirikan atas kerja sama Pemerintah Provinsi Riau dengan PT Chevron Pacific Indonesia',
            'keywords'    => 'PCR,Politeknik,Caltex,Riau,Mahasiswa,Politeknik Riau,Penerimaan Mahasiswa,Politeknik Caltex',
        ];
    }

    /**
     * Get page configuration for Landing page
     *
     * @return array
     */
    public static function getPageConfig(): array
    {
        $meta = self::getMetaData();

        return [
            'background_image' => null, // No specific background image for this page
            'seo'              => [
                'title'                      => data_get($meta, 'title'),
                'description'                => data_get($meta, 'description'),
                'keywords'                   => data_get($meta, 'keywords'),
                'canonical'                  => route('frontend.home'),
                'og_image'                   => data_get(SiteIdentityService::getSiteIdentity(), 'logo_path'),
                'og_type'                    => 'website',
                'structured_data'            => self::getStructuredData(),
                'breadcrumb_structured_data' => self::getBreadcrumbStructuredData()
            ]
        ];
    }

    /**
     * Get structured data for Landing page
     *
     * @return array
     */
    public static function getStructuredData(): array
    {
        $identy   = SiteIdentityService::getSiteIdentity();
        $metaData = self::getMetaData();

        return [
            '@context'    => 'https://schema.org',
            '@type'       => 'WebSite',
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
            'url'         => url()->current()
        ];
    }

    /**
     * Get breadcrumb structured data for Landing page
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
                ]
            ]
        ];
    }
}
