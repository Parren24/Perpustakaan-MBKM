<?php

namespace App\Services\CampusLife;

use App\Services\Frontend\SiteIdentityService;

/**
 * Virtual Tour Service
 *
 * Service untuk mengelola data dan logic terkait Virtual Tour kampus
 *
 * @author wahyudibinsaid
 */
class VirtualTourService
{

    public static function soureceUrl()
    {
        return asset('uploads/virtualtour/index.htm');
    }

    /**
     * Get content for Virtual Tour
     *
     * @return array
     */
    public static function getContent(): array
    {
        return [
            'header'      => 'Virtual Tour',
            'title'       => 'Virtual Tour Politeknik Caltex Riau',
            'subtitle'    => '',
            'description' => 'Selamat datang di Virtual Tour Politeknik Caltex Riau! Di sini, Anda dapat menjelajahi kampus kami secara virtual, melihat fasilitas, ruang kelas, laboratorium, dan area lainnya yang mendukung proses pembelajaran. Nikmati pengalaman berkeliling kampus tanpa harus meninggalkan rumah.',
            'source'      => self::soureceUrl(),
        ];
    }

    public static function getCallout(): array
    {
        return [
            'title'       => 'Jelajahi Kampus PCR',
            'subtitle'    => '',
            'description' => '',
            'features'    => [
                'Tur Kampus 3D',
                'Eksplorasi Virtual',
                'Akses Mudah',
                'Kenali Kampus Lebih Baik',
            ],
            'action'      => [
                'url'         => self::soureceUrl(),
                'class'       => 'popup-video',
                'cursor_text' => 'Mulai',
                'icon'        => 'fa-solid fa-play',
                'button_text' => 'Mulai Tur Virtual'
            ],
            'background'  => [
                'image'  => [
                    'src' => publicMedia('jelajah-kampus.webp'),
                    'alt' => ''
                ],
                'styles' => [
                    'background-size'       => 'cover',
                    'background-repeat'     => 'no-repeat',
                    'background-attachment' => 'fixed',
                    'background-position'   => 'center 32.8359px'
                ]
            ]
        ];
    }

    /**
     * Get meta data for Virtual Tour page
     *
     * @return array
     */
    public static function getMetaData(): array
    {
        return [
            'title'       => data_get(self::getContent(), 'title'),
            'description' => data_get(self::getContent(), 'description'),
            'keywords'    => 'virtual tour, politeknik caltex riau, pcr, kampus virtual',
        ];
    }

    /**
     * Get page configuration for Virtual Tour page
     *
     * @return array
     */
    public static function getPageConfig(): array
    {
        $meta = self::getMetaData();
        $bg   = publicMedia('jelajah-kampus.webp');

        return [
            'background_image' => $bg, // No specific background image for this page as per current view
            'seo'              => [
                'title'                      => data_get($meta, 'title'),
                'description'                => data_get($meta, 'description'),
                'keywords'                   => data_get($meta, 'keywords'),
                'canonical'                  => route('frontend.campus-life.virtual-tour'),
                'og_image'                   => $bg,
                'og_type'                    => 'website',
                'structured_data'            => self::getStructuredData($bg),
                'breadcrumb_structured_data' => self::getBreadcrumbStructuredData()
            ]
        ];
    }

    /**
     * Get structured data for Virtual Tour page
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
     * Get breadcrumb structured data for Virtual Tour page
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
                    'name'     => 'Kehidupan Kampus',
                    'item'     => route('frontend.home') . '#kehidupan-kampus' // Assuming an anchor or a general campus life page
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
