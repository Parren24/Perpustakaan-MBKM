<?php

namespace App\Services\Profile;

use App\Services\Frontend\SiteIdentityService;

class DiversityService
{
    /**
     * Page content for Diversity
     *
     * @return array
     */
    public static function getContent(): array
    {
        return [
            'header'      => 'Keberagaman',
            'title'       => '<b>Keberagaman</b> di Politeknik Caltex Riau',
            'subtitle'    => 'Nilai Keberagaman',
            'description' => [
                'Politeknik Caltex Riau (PCR) adalah kampus yang mencerminkan keberagaman dalam harmoni dan mengedepankan kolaborasi tanpa diskriminasi. Mahasiswa berasal dari berbagai latar belakang budaya, suku, agama, status sosial ekonomi dan daerah di Indonesia.',
                'Dosen dan Tenaga Kependidikan berasal dari berbagai latar belakang pendidikan, pengalaman profesional, dan wilayah, baik lokal maupun internasional. Lingkungan kampus yang multikultural mendorong kolaborasi dan toleransi, menciptakan suasana akademik yang dinamis dan penuh semangat.',
                'PCR juga menyediakan fasilitas yang ramah bagi semua, termasuk penyandang disabilitas. Selain itu, PCR juga mendukung keberagaman minat dan bakat melalui berbagai organisasi dan kegiatan mahasiswa, seperti seni, olahraga, teknologi, dan komunitas budaya dan agama.',
                'Multidisiplin keilmuan juga memperkaya keberagaman yang ada di PCR. Keberagaman ini menjadi kekuatan PCR dalam membentuk lulusan yang tidak hanya unggul secara teknis, tetapi juga mampu bekerja dalam tim lintas budaya di era globalisasi.'
            ],
            'deiversity'  => [
                [
                    'title'       => 'Identitas dan Latar Mahasiswa',
                    'description' => 'Mahasiswa PCR datang dari beragam budaya, suku, agama, daerah, dan latar sosial-ekonomi. Perbedaan ini dirayakan sebagai kekuatan yang memperkaya dialog, cara pandang, dan pengalaman belajar bersama',
                    'image'       => [
                        'src' => publicMedia('diversity-etnis.webp'),
                        'alt' => ''
                    ]
                ],
                [
                    'title'       => 'Talenta Dosen dan Tendik',
                    'description' => 'Dosen dan tenaga kependidikan berasal dari berbagai disiplin, jenjang pendidikan, serta pengalaman industri dan riset lokal maupun internasional. Mereka menjadi mitra belajar yang kolaboratif, relevan dengan kebutuhan dunia kerja',
                    'image'       => [
                        'src' => publicMedia('diversity-dosen.webp'),
                        'alt' => ''
                    ]
                ],
                [
                    'title'       => 'Lingkungan Inklusif dan Aksesibilitas',
                    'description' => 'PCR menumbuhkan budaya saling menghargai dan anti-diskriminasi, dengan fasilitas dan layanan yang ramah disabilitas. Setiap warga kampus merasa aman bersuara dan mendapat kesempatan yang setara untuk berkembang',
                    'image'       => [
                        'src' => publicMedia('diversity-disabilitas.webp'),
                        'alt' => ''
                    ]
                ],
                [
                    'title'       => 'Minat, Bakat dan Ekosistem Organisasi',
                    'description' => 'Beragam UKM dan komunitas seni, olahraga, teknologi, budaya, dan keagamaan—memberi ruang aktualisasi diri. Kegiatan yang terstruktur mendorong kepemimpinan, jejaring, dan karakter kolaboratif mahasiswa',
                    'image'       => [
                        'src' => publicMedia('diversity-aktivitas.webp'),
                        'alt' => ''
                    ]
                ],
                [
                    'title'       => 'Keilmuan dan Kolaborasi Multidisiplin',
                    'description' => 'Pembelajaran terapan mendorong pertemuan lintas program studi dalam riset dan proyek nyata. Kolaborasi multidisiplin melatih pemecahan masalah komprehensif dan inovasi yang berdampak',
                    'image'       => [
                        'src' => publicMedia('diversity-ilmu.webp'),
                        'alt' => ''
                    ]
                ],
                [
                    'title'       => 'Kapabilitas Lintas Budaya',
                    'description' => 'Eksposur internasional dan soft skills kolaborasi lintas budaya dipupuk sejak dini. Lulusan disiapkan untuk bekerja efektif dalam tim global, adaptif terhadap dinamika industri dan masyarakat dunia',
                    'image'       => [
                        'src' => publicMedia('diversity-budaya.webp'),
                        'alt' => ''
                    ]
                ]
            ]
        ];
    }

    /**
     * Meta data for SEO
     *
     * @return array
     */
    public static function getMetaData(): array
    {
        return [
            'title'       => data_get(self::getContent(), 'title'),
            'description' => 'Informasi mengenai program, kebijakan, dan inisiatif diversitas di Politeknik Caltex Riau.',
            'keywords'    => 'diversitas, inklusi, politeknik caltex riau',
        ];
    }

    /**
     * Page configuration including SEO structure
     *
     * @return array
     */
    public static function getPageConfig(): array
    {
        $meta = self::getMetaData();
        $bg   = publicMedia('diversity-bg.webp');

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
