<?php

namespace App\Services\Profile;

use App\Services\Frontend\SiteIdentityService;

class WelcomeYpcrService
{
    /**
     * Get content for Sambutan YPCR
     *
     * @return array
     */
    public static function getContent(): array
    {
        return [
            'header'     => 'Sambutan YPCR',
            'title'      => 'Sambutan Yayasan Politeknik Caltex Riau',
            'ketua_ypcr' => '<b>Ir. Akson</b> Brahmantyo',
            'subtitle'   => 'Ketua Umum YPCR',
            'greeting'   => [
                'paragraphs' => [
                    'Politeknik Caltex Riau (PCR) memulai operasi sejak tahun 2001. Beragam terobosan telah dilakukan mulai daripembenahan sarana belajar, penambahan gedung perkuliahan, serta peningkatan kualitas tenaga pengajar. Seluruhnya merupakan komitmen PCR untuk memberikan yang terbaik.',
                    'Menjadi politeknik yang unggul adalah visi Politeknik Caltex Riau. Visi yang kami sadari harus dibarengi dengan misi yang sejalan dengan komitmen Yayasan Politeknik Chevron Riau (YPCR) dan harus dilaksanakan secara konsisten. Meski tertatih di tahun-tahun pertama, namu kini PCR telah menjadi perguruan tinggi yang mampu bersaing dengan perguruan tinggi lainnya.',
                    'Berawal dari kegundahan akan kurangnya Sumber Daya Manusia yang handal dan skillable di Riau untuk memenuhi kebutuhan industri yang berkembang pesat, PT. Chevron Pacific Indonesia dan Pemerintah Daerah Provinsi Riau berkerjasama menginisiasi berdirinya PCR. Diawali dengan ide yang dicetuskan oleh Gubernur Riau saat itu yaitu Bapak Saleh Djasit dan mantan Presiden Direktur PT. Caltex Pacific Indonesia Bapak Baihaki Hakim, maka PCR dibangun pada tahun 2000 dan memulai kegiatan akademik pada tahun 2001.',
                    'Yayasan Politeknik Chevron Riau memang sedari awal mempersiapkan PCR untuk menjadi institusi pendidikan yang profesional dan mandiri. Dengan menerapkan sistem tata kelola yang profesional dan akuntabel maka cita-cita untuk menjadi institusi yang mandiri terwujud sejak tahun 2007 dimana pada tahun tersebut PT. Chevron Pacific Indonesia mulai menghentikan bantuan biaya operasional kepada PCR. Tidak hanya menjadi institusi mandiri, YPCR sebagai badan penyelenggara PCR juga mengembangkan PCR dengan penambahan berbagai sarana dan prasaran. Dengan adanya penambahan sarana dan prasarana tersebut maka peningkatan jumlah jumlah mahasiswa dapat dilakukan.',
                    'Lebih dari 5129 alumni telah dihasilkan dan terbukti mampu bersaing di dunia industri. Banyak dari mereka yang berkarir dan berkembang di sejumlah perusahaan multinasional dan nasional. Hal itu menjadi penyemangat bagi Politeknik Caltex Riau untuk berkiprah lebih tinggi lagi.'
                ]
            ],
            'images'     => [
                'main'  => [
                    'src' => publicMedia('yayasan.webp'),
                    'alt' => ''
                ],
                'thumb' => [
                    'src' => publicMedia('yayasan-2.webp'),
                    'alt' => ''
                ],
            ]
        ];
    }

    public static function getMetaData(): array
    {
        return [
            'title'       => data_get(self::getContent(), 'title'),
            'description' => 'Sambutan dari Yayasan Politeknik Caltex Riau mengenai visi dukungan pendidikan vokasi.',
            'keywords'    => 'sambutan, yayasan, pcr',
        ];
    }

    public static function getPageConfig(): array
    {
        $meta = self::getMetaData();
        $bg   = publicMedia('yayasan-bg.webp');

        return [
            'background_image' => $bg,
            'seo'              => [
                'title'                      => data_get($meta, 'title'),
                'description'                => data_get($meta, 'description'),
                'keywords'                   => data_get($meta, 'keywords'),
                'canonical'                  => route('frontend.profile.welcome-ypcr'),
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
