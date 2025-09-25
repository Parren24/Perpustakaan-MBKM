<?php

namespace App\Services\Profile;

use App\Services\Frontend\SiteIdentityService;

class LocationService
{
    public static function getContent(): array
    {
        $contact = SiteIdentityService::getContactInfo();

        return [
            'header'        => 'Lokasi',
            'title'         => '<b>Lokasi</b> Politeknik Caltex Riau',
            'subtitle'      => 'Informasi Lokasi',
            'address'       => data_get($contact, 'address.full', 'Jln. Umbansari No. 1, Umban Sari, Kec. Rumbai, Kota Pekanbaru, Riau 28265'),
            'description'   => [
                'Politeknik Caltex Riau berlokasi strategis di kawasan pendidikan Kota Pekanbaru, tepatnya di ' . data_get($contact, 'address.street') . '. Kampus ini mudah diakses dari berbagai arah, baik menggunakan kendaraan pribadi maupun transportasi umum.',
                'Lingkungan sekitar kampus dikelilingi oleh fasilitas pendukung seperti perumahan, pusat perbelanjaan, rumah sakit, dan tempat ibadah, sehingga memudahkan mahasiswa dalam menjalani aktivitas sehari-hari'
            ],
            'map_embed_url' => data_get($contact, 'address.map_embed_url'),
            'hints'         => [
                ['title' => 'Dari Stadion Kaharuddin Nasution', 'body' => 'Keluar kompleks stadion ke Jl. Yos Sudarso, lanjutkan ke arah Simpang Lampu Merah Umban Sari. Masuk ke Jl. Umban Sari, gerbang utama PCR berada tidak jauh dari simpang tersebut.'],
                ['title' => 'Dari Jembatan Siak I (Leighton)', 'body' => 'Menyeberang dari sisi kota ke Rumbai via Jl. Yos Sudarso. Ikuti jalan utama hingga Simpang Lampu Merah Umban Sari, lalu belok ke Jl. Umban Sari. Terus ± beberapa ratus meter, kampus ada di sisi koridor utama Umban Sari.'],
                ['title' => 'Dari Camp Pertamina Rumbai', 'body' => 'Keluar gerbang camp menuju Jl. Paus. Setibanya di Simpang Umban Sari, lurus ambil Jl. Umban Sari, kampus PCR berada di koridor utama jalan itu.']
            ],
            'hints_section' => [
                'title'    => 'Petunjuk',
                'subtitle' => 'Ingin <span>mudah menemukan</span> lokasi Politeknik Caltex Riau?',
                'intro'    => 'Politeknik Caltex Riau memiliki lokasi yang strategis dan mudah diakses. Berikut adalah beberapa petunjuk untuk membantu Anda menemukan kampus kami dengan mudah.'
            ]
        ];
    }

    public static function getMetaData(): array
    {
        return [
            'title'       => 'Lokasi - Politeknik Caltex Riau',
            'description' => 'Informasi lokasi Politeknik Caltex Riau beserta petunjuk akses.',
            'keywords'    => 'lokasi, alamat, pcr'
        ];
    }

    public static function getPageConfig(): array
    {
        $meta = self::getMetaData();
        $bg   = publicMedia('lokasi-bg.webp');

        return [
            // Use publicMedia helper so media URL generation matches other services
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

    public static function getStructuredData($bg): array
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
