<?php

namespace App\Services\Profile;

use App\Services\Frontend\SiteIdentityService;

class WelcomeDirectorService
{
    public static function getContent(): array
    {
        return [
            'header'   => 'Sambutan Direktur',
            'title'    => 'Sambutan Direktur Politeknik Caltex Riau',
            'director' => '<b>Dr. Dadang</b> Syarif Sihabudin Sahid, S.Si., M.Sc.',
            'subtitle' => 'Direktur Politeknik Caltex Riau',
            'greeting' => [
                'paragraphs' => [
                    'Sejak awal berdiri, Politeknik Caltex Riau tidak hanya menjadi tempat untuk mendidik dan mengembangkan pengetahuan para mahasiswa. Lebih dari itu, perguruan tinggi ini bertekad untuk me-reinternalisasi nilai-nilai karakter. Pembentukan kepribadian melalui penanaman nilai-nilai integritas, kredibilitas, kejujuran, kedisiplinan dan semangat untuk tidak pantang menyerah adalah bagian yang mendapat perhatian khusus. Dalam bentuk riil, pengembangan nilai-nilai tersebut  disampaikan dalam mata kuliah khusus enabling skill.',
                    'Namun demikian, di dalam materi ajar perkuliahan, nilai-nilai tersebut juga diterapkan dengan ketat. Seperti, etika terhadap tenaga pengajar dan sesama mahasiswa, cara berpakaian dan kerapian. Serta aturan-aturan lainnya. Kita berharap, lewat pembentukan mental dan karakter, para mahasiswa yang menyelesaikan studinya di PCR, siap secara intelektual dan mental. Mereka terjun ke tengah-tengah masyarakat dengan nilai-nilai profesionalitas yang teruji.',
                    'Selain itu, dalam konteks keunggulan sarana dan prasarana belajar, kampus ini didukung oleh tenaga pengajar yang memiliki kompetensi di bidangnya. Kita mewajibkan, dosen mengantongi sertifkasi atas bidang yang mereka ajarkan. Sertifkasi tersebut harus diberikan oleh lembaga independen yang memiliki otoritas atas bidang tertentu. Misalnya, dosen pengajar jaringan komputer, dia mesti memiliki sertifkasi dari CISCO atau dari lembaga independen lainnya.Begitu pula dosen di progam studi Akutansi.',
                    'Mereka harus memiliki sertifkasi MYOB atau sertifkasi lainnya yang menyangkut kompetensi di bidang akuntansi. Kedepan, kita juga berencana, pemenuhan sertifkasi itu juga menjadi keharusan bagi mahasiswa. Sehingga, bila mereka terjun ke dunia kerja, kompetensi lulusan PCR tidak perlu diragukan lagi. Keberadaan PCR sendiri sebagai sebuah perguruan tinggi terbilang memiliki prestasi. Beberapa tahun silam, seluruh program studi di PCR mendapat nilai B dari Badan Akreditasi Nasional.',
                    'Dengan prestasi itu, PCR pun mendapat alokasi beasiswa bidik dari pemerintah. pada tahun 2011, sebanyak 36 mahasiswa mendapat beasiswa itu. Sedangkan pada tahun 2012, jumlahnya meningkat menjadi 64 orang. Selain itu, PCR juga menjadi politeknik model oleh Direktorat Pendidikan Tinggi. Bahkan sejak tahun 2011, perguruan tinggi ini menjadi benchmarking bagi politeknik di seluruh Indonesia. Dalam kurun 2011-2012 tak kurang dari 15 politeknik se-Indonesia menjadikan PCR sebagai tempat untuk melakukan workshop. Sejumlah prestasi dan kepercayaan pemerintah itu, tentunya makin melecut semangat kita, untuk terus memajukan PCR dan membawa institusi ini mampu bersaing di kancah global. Amin.'
                ]
            ],
            'images'   => [
                'main'  => [
                    'src' => publicMedia('direktur.webp'),
                    'alt' => ''
                ],
                'thumb' => [
                    'src' => publicMedia('direktur-2.webp'),
                    'alt' => ''
                ],
            ]
        ];
    }

    public static function getMetaData(): array
    {
        return [
            'title'       => data_get(self::getContent(), 'title'),
            'description' => 'Sambutan dari Direktur Politeknik Caltex Riau mengenai komitmen pendidikan vokasi.',
            'keywords'    => 'sambutan, direktur, pcr',
        ];
    }

    public static function getPageConfig(): array
    {
        $meta = self::getMetaData();
        $bg   = publicMedia('direktur-bg.webp');

        return [
            'background_image' => $bg,
            'seo'              => [
                'title'                      => data_get($meta, 'title'),
                'description'                => data_get($meta, 'description'),
                'keywords'                   => data_get($meta, 'keywords'),
                'canonical'                  => route('frontend.profile.welcome-director'),
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
