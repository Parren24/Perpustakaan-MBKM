<?php

namespace App\Services\Profile;

use App\Services\Frontend\ArticleService;
use App\Services\Frontend\SiteIdentityService;

class AchievementsService
{
    /**
     * Page content for Achievements
     *
     * @return array
     */
    public static function getContent(): array
    {
        $institusiAchievements   = ArticleService::getAchievementByLabel(6, 'prestasi-institusi');
        $dosenTendikAchievements = ArticleService::getAchievementByLabel(6, 'prestasi-dosen-tendik');
        $mahasiswaAchievements   = ArticleService::getAchievementByLabel(6, 'prestasi-mahasiswa');

        // Helper to map Post model data to the desired achievement structure
        $mapAchievementData = function ($post) {
            return [
                'title'       => data_get($post, 'judul_post'),
                'description' => data_get($post, 'isi_post'),
                'image'       => [
                    'src' => ArticleService::generateArticleImageUrl($post),
                    'alt' => data_get($post, 'judul_post'),
                ],
                'date'        => data_get($post, 'tanggal_post') ? date('d M Y', strtotime(data_get($post, 'tanggal_post'))) : '',
                'url'         => ArticleService::generateArticleLink($post),
            ];
        };

        return [
            'header'                  => 'Prestasi dan Penghargaan',
            'title'                   => 'Prestasi dan Penghargaan Politeknik Caltex Riau',
            'subtitle'                => '', // Can be filled later if needed
            'description'             => [
                // General description for the page if needed, otherwise empty
            ],
            'achievements_categories' => [
                [
                    'id'           => 'prestasi-institusi',
                    'title'        => 'Data <b>Prestasi Institusi</b>',
                    'subtitle'     => 'Prestasi Terbaru',
                    'description'  => 'Politeknik Caltex Riau telah meraih berbagai penghargaan dan prestasi di tingkat nasional maupun internasional, mencerminkan komitmen kami terhadap keunggulan akademik dan inovasi.',
                    'achievements' => $institusiAchievements->map($mapAchievementData)->toArray(),
                ],
                [
                    'id'           => 'prestasi-dosen-tendik',
                    'title'        => 'Data <b>Prestasi Dosen</b> dan Tenaga Kependidikan',
                    'subtitle'     => 'Prestasi Terbaru',
                    'description'  => 'Dosen dan tenaga kependidikan kami telah berkontribusi dalam berbagai penelitian, publikasi, dan penghargaan yang menunjukkan dedikasi mereka terhadap pengembangan ilmu pengetahuan dan teknologi.',
                    'achievements' => $dosenTendikAchievements->map($mapAchievementData)->toArray(),
                ],
                [
                    'id'           => 'prestasi-mahasiswa',
                    'title'        => 'Data <b>Prestasi Mahasiswa</b>',
                    'subtitle'     => 'Prestasi Terbaru',
                    'description'  => 'Mahasiswa kami telah berprestasi dalam berbagai kompetisi akademik, seni, dan olahraga, menunjukkan semangat dan kemampuan mereka dalam berbagai bidang.',
                    'achievements' => $mahasiswaAchievements->map($mapAchievementData)->toArray(),
                ],
            ],
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
            'description' => 'Berbagai prestasi dan penghargaan yang telah diraih oleh Politeknik Caltex Riau, dosen, dan mahasiswa.',
            'keywords'    => 'prestasi PCR, penghargaan PCR, prestasi mahasiswa, prestasi dosen, Politeknik Caltex Riau',
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
        $bg   = publicMedia('prestasi-bg.webp'); // Placeholder background image

        return [
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