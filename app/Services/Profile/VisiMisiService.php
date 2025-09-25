<?php

namespace App\Services\Profile;

use App\Services\Frontend\SiteIdentityService;

class VisiMisiService
{
    /**
     * Return visi dan misi content for the frontend Visi Misi page.
     * In future this can fetch from DB or CMS.
     *
     * @return array|object
     */
    public static function getContent()
    {
        return [
            'header'   => 'Visi dan Misi',
            'title'    => 'Visi dan Misi Politeknik Caltex Riau',
            'subtitle' => '',

            'vision'   => [
                'title'        => 'Visi ',
                'subtitle'     => '2015 - Sekarang',
                'introduction' => 'Politeknik Caltex Riau dalam setiap langkahnya berpedoman pada visi berikut:',
                'description'  => 'Diakui Sebagai Politeknik Unggul yang Mampu Bersaing dalam Bidang Teknologi dan Bisnis Terapan pada Tingkat Nasional Maupun ASEAN Tahun 2031',
                'images'       => [
                    'main'  => [
                        'src' => publicMedia('visi1.webp'),
                        'alt' => ''
                    ],
                    'thumb' => [
                        'src' => publicMedia('visi2.webp'),
                        'alt' => ''
                    ]
                ]
            ],
            'mission'  => [
                'title'        => 'Misi',
                'subtitle'     => 'Misi yang dijalankan',
                'introduction' => 'Untuk mewujudkan visi di atas, maka misi yang dijalankan oleh Politeknik Caltex Riau adalah:',
                'description'  => [
                    'Menyelenggarakan Sistem Pendidikan Vokasi bidang Teknologi dan Bisnis yang berkualitas serta relevan dengan tantangan Nasional maupun ASEAN',
                    'Menciptakan budaya akademik dan budaya organisasi yang berkarakter dan bermartabat',
                    'Melaksanakan penelitian dan menyebarluaskan hasilnya untuk pengembangan bidang teknologi dan bisnis terapan',
                    'Melaksanakan pengabdian kepada masyarakat dengan menyebarluaskan ilmu pengetahuan, teknologi, dan budaya organisasi'
                ]
            ],
            'values'   => [
                'title'        => 'Nilai',
                'subtitle'     => 'Nilai Pedoman',
                'introduction' => 'PCR memiliki nilai-nilai dengan singkatan IDEAL, yaitu',
                'items'        => [
                    [
                        'short'       => 'I',
                        'title'       => 'Integrity',
                        'meaning'     => 'Integritas',
                        'icon'        => 'fa-solid fa-shield',
                        'description' => 'Mencerminkan komitmen PCR terhadap kejujuran, etika, dan transparansi dalam setiap aspek, mulai dari proses akademik hingga pengelolaan institusi, komitmen pada nilai dan prinsip, tanggung jawab terhadap tugas dan fungsi, transparansi dalam pengambilan keputusan, konsistensi terhadap penegakan aturan, penghormatan terhadap hak intelektual, dan menjunjung tinggi profesionalisme dan keberlanjutan moral',
                    ],
                    [
                        'short'       => 'D',
                        'title'       => 'Dignity',
                        'meaning'     => 'Kehormatan',
                        'icon'        => 'fa-solid fa-crown',
                        'description' => 'Mencerminkan komitmen PCR terhadap penghormatan terhadap martabat setiap individu dalam komunitas akademik, menekankan pentingnya perlakuan adil, inklusivitas, dan penghargaan terhadap keberagaman, mendorong pengembangan potensi individu tanpa diskriminasi, menanamkan rasa tanggung jawab untuk menghormati orang lain',
                    ],
                    [
                        'short'       => 'E',
                        'title'       => 'Excellence',
                        'meaning'     => 'Keunggulan',
                        'icon'        => 'fa-solid fa-star',
                        'description' => 'Mencerminkan komitmen PCR untuk mencapai keunggulan di setiap aspek, menerapkan standar tinggi dalam kualitas pendidikan, inovasi dalam ilmu pengetahuan, serta pengembangan potensi mahasiswa, dosen, dan tenaga kependidikan',
                    ],
                    [
                        'short'       => 'A',
                        'title'       => 'Agility',
                        'meaning'     => 'Kelincahan dan Ketangkasan',
                        'icon'        => 'fa-solid fa-bolt',
                        'description' => 'Mencerminkan komitmen PCR terhadap kemampuan institusi dan seluruh sivitas akademika untuk beradaptasi dengan cepat terhadap perubahan lingkungan, teknologi, dan kebutuhan masyarakat, menciptakan solusi kreatif, dan memanfaatkan peluang untuk mencapai keunggulan',
                    ],
                    [
                        'short'       => 'L',
                        'title'       => 'Loyalty',
                        'meaning'     => 'Kepatuhan atau Kesetiaan',
                        'icon'        => 'fa-solid fa-handshake',
                        'description' => 'Mencerminkan komitmen PCR terhadap kesetiaan dan komitmen seluruh sivitas akademika terhadap visi, misi, dan tujuan institusi, mendorong rasa kebanggaan terhadap institusi, kepatuhan terhadap aturan yang berlaku, serta partisipasi aktif dalam membangun budaya kampus yang harmonis',
                    ]
                ]
            ]
        ];
    }

    /**
     * Optional metadata for the Visi Misi page (SEO)
     *
     * @return array
     */
    public static function getMetaData()
    {
        return [
            'title'       => data_get(self::getContent(), 'title'),
            'description' => 'Visi dan misi Politeknik Caltex Riau sebagai pedoman pengembangan pendidikan vokasi.',
            'keywords'    => 'Visi, Misi, Politeknik Caltex Riau'
        ];
    }

    /**
     * Optional page config including background image and SEO structure
     *
     * @return array
     */
    public static function getPageConfig()
    {
        $meta = self::getMetaData();
        $bg   = publicMedia('visi-misi.webp');

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
     * Structured data for SEO (JSON-LD)
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
     * Breadcrumb structured data for SEO
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
