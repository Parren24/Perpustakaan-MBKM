<?php

namespace App\Services\Frontend;

use App\Services\Frontend\SiteIdentityService; // Assuming SiteIdentityService is used for general site info

class ContactService
{
    /**
     * Get content for the Contact page.
     *
     * @return array
     */
    public static function getContent(): array
    {
        // Fetch general contact info from SiteIdentityService
        $siteContactInfo = SiteIdentityService::getContactInfo();
        $socialMedia     = SiteIdentityService::getSocialMedia();

        return [
            'header'           => 'Kontak Kami',
            'title'            => 'Hubungi Kami untuk <span>Informasi Lebih Lanjut</span>',
            'description'      => 'Kami siap membantu Anda dengan pertanyaan, saran, atau permintaan informasi lebih lanjut.',
            'contact_sections' => [
                'phone'   => [
                    'title'       => 'Telepon Kami',
                    'description' => 'Jika anda membutuhkan komunikasi melalui jaringan telefon, anda dapat memeilih salah satu dari nomor yang masing-masing bidang yang terkait.',
                    'details'     => [
                        ['label' => 'Umum', 'number' => data_get($siteContactInfo, 'phone.main')],
                        ['label' => 'Bagian Akademik dan Kemahasiswaan', 'number' => data_get($siteContactInfo, 'phone.academic_phone')],
                        ['label' => 'Bagian Penerimaan Mahasiswa Baru', 'number' => data_get($siteContactInfo, 'phone.mobile')],
                        ['label' => 'Bagian Kerja Sama dan Pusat Karir', 'number' => data_get($siteContactInfo, 'phone.cooperation_phone')]
                    ]
                ],
                'email'   => [
                    'title'       => 'Email Kami',
                    'description' => 'Kami juga dapat dihubungi melalui saluran email, apa bila anda membutuhkan informasi yang detail tertulis.',
                    'details'     => [
                        ['label' => 'Umum', 'email' => data_get($siteContactInfo, 'email.main')],
                        ['label' => 'Penerimaan Mahasiswa Baru', 'email' => data_get($siteContactInfo, 'email.admission')],
                        ['label' => 'Akademik', 'email' => data_get($siteContactInfo, 'email.academic')],
                    ]
                ],
                'address' => [
                    'title'         => 'Kunjungi Kami',
                    'description'   => 'Namun jika anda memiliki waktu luang, kami sangat senang jika anda menemui kami secara tatap muka dengan mengunjungi alamat utama kami.',
                    'details'       => [
                        ['label' => 'Alamat Utama', 'address' => data_get($siteContactInfo, 'address.full')],
                    ],
                    'map_embed_url' => data_get($siteContactInfo, 'address.map_embed_url')
                ]
            ],
            'form'             => [
                'title'       => 'Kirim pesan Anda',
                'description' => 'Kami siap membantu Anda dengan pertanyaan, saran, atau permintaan informasi lebih lanjut. Silakan isi formulir di bawah ini, dan tim kami akan segera menghubungi Anda.',
                'action_url'  => route('frontend.information.contact.submit'),
                'fields'      => [
                    'name'    => ['label' => 'Nama', 'placeholder' => 'Nama'],
                    'email'   => ['label' => 'Email', 'placeholder' => 'Email'],
                    'subject' => ['label' => 'Subjek', 'placeholder' => 'Subjek'],
                    'message' => ['label' => 'Pesan', 'placeholder' => 'Pesan'],
                ]
            ],
            'social_media'     => [
                'title'       => 'Social Media Kami',
                'description' => 'Ikuti kami di media sosial untuk mendapatkan informasi terbaru, berita, dan pembaruan mengenai Politeknik Caltex Riau. Kami senang berinteraksi dengan Anda di platform-platform ini!',
                'links'       => $socialMedia
            ]
        ];
    }

    /**
     * Get meta data for Contact page
     *
     * @return array
     */
    public static function getMetaData(): array
    {
        $content = self::getContent();
        return [
            'title'       => data_get($content, 'header'),
            'description' => data_get($content, 'description'),
            'keywords'    => 'kontak, contact, pcr, politeknik caltex riau, alamat, telepon, email',
        ];
    }

    /**
     * Get page configuration for Contact page
     *
     * @return array
     */
    public static function getPageConfig(): array
    {
        $meta = self::getMetaData();
        $bg   = publicMedia('kontak-bg.webp');

        return [
            'background_image' => $bg, // Or a specific image for contact page
            'seo'              => [
                'title'                      => data_get($meta, 'title'),
                'description'                => data_get($meta, 'description'),
                'keywords'                   => data_get($meta, 'keywords'),
                'canonical'                  => route('frontend.information.contact'),
                'og_image'                   => $bg,
                'og_type'                    => 'website',
                'structured_data'            => self::getStructuredData($bg),
                'breadcrumb_structured_data' => self::getBreadcrumbStructuredData()
            ]
        ];
    }

    /**
     * Get structured data for Contact page
     *
     * @return array
     */
    public static function getStructuredData($bg): array
    {
        $identy      = SiteIdentityService::getSiteIdentity();
        $contactInfo = SiteIdentityService::getContactInfo();
        $metaData    = self::getMetaData();

        return [
            '@context'     => 'https://schema.org',
            '@type'        => 'ContactPage',
            'headline'     => $metaData['title'],
            'description'  => $metaData['description'],
            'name'         => $metaData['title'],
            'publisher'    => [
                '@type' => 'Organization',
                'name'  => data_get($identy, 'name'),
                'logo'  => [
                    '@type' => 'ImageObject',
                    'url'   => data_get($identy, 'logo_path')
                ]
            ],
            'image'        => $bg,
            'url'          => url()->current(),
            'contactPoint' => [
                '@type'       => 'ContactPoint',
                'telephone'   => data_get($contactInfo, 'phone.main'),
                'contactType' => 'customer service',
                'email'       => data_get($contactInfo, 'email.main')
            ],
            'address'      => [
                '@type'           => 'PostalAddress',
                'streetAddress'   => data_get($contactInfo, 'address.street'),
                'addressLocality' => data_get($contactInfo, 'address.city'),
                'addressRegion'   => data_get($contactInfo, 'address.province'),
                'postalCode'      => data_get($contactInfo, 'address.postal_code'),
                'addressCountry'  => 'ID'
            ],
            'hasMap'       => data_get($contactInfo, 'address.maps_url'),
            'sameAs'       => array_column(SiteIdentityService::getSocialMedia(), 'url')
        ];
    }

    /**
     * Get breadcrumb structured data for Contact page
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
                    'name'     => 'Informasi',
                    'item'     => route('frontend.home') . '#informasi' // Assuming an anchor or general info page
                ],
                [
                    '@type'    => 'ListItem',
                    'position' => 3,
                    'name'     => 'Kontak Kami',
                    'item'     => url()->current()
                ]
            ]
        ];
    }
}