<?php

namespace App\Services\Frontend;

use App\Models\Agenda\Agenda;
use App\Models\Post\Post;
use App\Models\Post\PostKategori;
use App\Models\Post\PostLabel;
use Illuminate\Support\Facades\Storage; // Added this line

class ArticleService
{
    /**
     * Return artikel content for the frontend Artikel page.
     * In future this can fetch from DB or CMS.
     *
     * @return array|object
     */
    public static function getContent($articleSlug = null)
    {
        return $articleSlug ? self::getShowContent($articleSlug) : self::getIndexContent();
    }

    /**
     * Return index article content for the frontend Artikel content.
     *
     * @return array|object
     */
    public static function getIndexContent()
    {

        $highlighted        = [];
        $highlightedRecords = self::getHighlightedArticles();

        foreach ($highlightedRecords as $index => $highlight) {
            $highlighted[] = [
                'title'     => $highlight->judul_post,
                'timestamp' => $highlight->tanggal_post->diffForHumans(),
                'url'       => route('frontend.articles.show', ['articlesSlug' => $highlight->slug_post]),
                'images'    => [
                    'src' => publicMedia($highlight->filename_post, 'artikel'),
                    'alt' => 'Cover ' . $highlight->judul_post,
                ]
            ];
        }

        $newest        = [];
        $newestRecords = self::getNews();

        foreach ($newestRecords as $index => $news) {
            $newest[] = [
                'title'     => $news->judul_post,
                'timestamp' => $news->tanggal_post->diffForHumans(),
                'url'       => route('frontend.articles.show', ['articlesSlug' => $news->slug_post]),
                'images'    => [
                    'src' => publicMedia($news->filename_post, 'artikel'),
                    'alt' => 'Cover ' . $news->judul_post,
                ]
            ];
        }

        $achievements        = [];
        $achievementsRecords = self::getAchievements();

        foreach ($achievementsRecords as $index => $achievement) {
            $achievements[] = [
                'title'     => $achievement->judul_post,
                'timestamp' => $achievement->tanggal_post->diffForHumans(),
                'url'       => route('frontend.articles.show', ['articlesSlug' => $achievement->slug_post]),
                'images'    => [
                    'src' => publicMedia($achievement->filename_post, 'artikel'),
                    'alt' => 'Cover ' . $achievement->judul_post,
                ]
            ];
        }

        $bestResearches        = [];
        $bestResearchesRecords = self::getBestResearches();

        foreach ($bestResearchesRecords as $index => $bestResearch) {
            $bestResearches[] = [
                'title'     => $bestResearch->judul_post,
                'timestamp' => $bestResearch->tanggal_post->diffForHumans(),
                'url'       => route('frontend.articles.show', ['articlesSlug' => $bestResearch->slug_post]),
                'images'    => [
                    'src' => publicMedia($bestResearch->filename_post, 'artikel'),
                    'alt' => 'Cover ' . $bestResearch->judul_post,
                ]
            ];
        }

        $researches        = [];
        $researchesRecords = self::getBestResearches();

        foreach ($researchesRecords as $index => $research) {
            $researches[] = [
                'title'     => $research->judul_post,
                'timestamp' => $research->tanggal_post->diffForHumans(),
                'url'       => route('frontend.articles.show', ['articlesSlug' => $research->slug_post]),
                'images'    => [
                    'src' => publicMedia($research->filename_post, 'artikel'),
                    'alt' => 'Cover ' . $research->judul_post,
                ]
            ];
        }

        return [
            'header'            => 'Artikel',
            'title'             => 'Artikel Politeknik Caltex Riau',
            'subtitle'          => '',

            'highlighted'       => $highlighted,
            'newest'            => $newest,
            'achievements'      => $achievements,
            'best-research'     => $bestResearches,
            'research-activity' => $researches,
        ];
    }

    /**
     * Return show article content for the frontend Artikel Read content.
     *
     * @return array|object
     */
    public static function getShowContent($articleSlug)
    {
        $content         = [];
        $selectedArticle = self::getPost($articleSlug);

        $selectedLabels = $selectedArticle->postLabels;

        $labels = [];
        foreach ($selectedLabels as $index => $label) {
            $labels[] = [
                'label' => $label->nama_label,
                'url'   => route('frontend.articles.labels', ['labelCode' => $label->kode_label]),
            ];
        }
        $categories        = [];
        $categoriesRecords = self::getCategories();
        foreach ($categoriesRecords as $category) {
            $categories[] = [
                'title' => $category->nama_kategori,
                'url'   => route('frontend.articles.categories', ['categoriesCode' => $category->kode_kategori])
            ];
        }

        $latest        = [];
        $latestRecords = self::getNews();

        foreach ($latestRecords as $index => $value) {
            $latest[] = [
                'title'     => $value->judul_post,
                'timestamp' => $value->tanggal_post->diffForHumans(),
                'url'       => route('frontend.articles.show', ['articlesSlug' => $value->slug_post]),
                'images'    => [
                    'src' => publicMedia($value->filename_post, 'artikel'),
                    'alt' => 'Cover ' . $value->judul_post,
                ]
            ];
        }

        return [
            'header'        => $selectedArticle->judul_post,
            'title'         => $selectedArticle->judul_post,
            'url'           => route('frontend.articles.show', ['articlesSlug' => $selectedArticle->slug_post]),
            'meta_desc'     => $selectedArticle->meta_desc_post,
            'meta_keywords' => $selectedArticle->meta_keyword_post,
            'categories'    => $categories,
            'latest_news'   => $latest,
            'content'       => [
                'body'      => $selectedArticle->isi_post,
                'timestamp' => $selectedArticle->tanggal_post->diffForHumans(),
                'author'    => 'Administrator',
                'labels'    => $labels,
                'images'    => [
                    'src' => publicMedia($selectedArticle->filename_post, 'artikel'),
                    'alt' => 'Cover ' . $selectedArticle->judul_post,
                ]
            ]
        ];
    }

    /**
     * Return show article content for the frontend Artikel Read content.
     *
     * @return array|object
     */
    public static function getArchiveContent($type = 'category', $archiveSlug = 'berita', $page = 1): array|null
    {
        $content = [];

        $categories        = [];
        $categoriesRecords = self::getCategories();
        foreach ($categoriesRecords as $category) {
            $categories[] = [
                'title' => $category->nama_kategori,
                'url'   => route('frontend.articles.categories', ['categoriesCode' => $category->kode_kategori])
            ];
        }

        $labels        = [];
        $labelsRecords = self::getLabels();
        foreach ($labelsRecords as $label) {
            $labels[] = [
                'title' => $label->nama_label,
                'url'   => route('frontend.articles.labels', ['labelCode' => $label->kode_label])
            ];
        }

        $articles = [];
        if ($type == 'category') {
            $category        = PostKategori::where('kode_kategori', $archiveSlug)->first();
            $articlesRecords = self::getArticleByCategory($category->postkategori_id, page: $page);
            $titlePage       = $category->nama_kategori;
        } else if ($type == 'label') {
            $label           = PostLabel::where('kode_label', $archiveSlug)->first();
            $articlesRecords = self::getArticleByLabel($label->postlabel_id, page: $page);
            $titlePage       = $label->nama_label;
        } else {
            return null;
        }

        foreach ($articlesRecords['data'] as $index => $value) {
            $articles[] = [
                'title'     => $value->judul_post,
                'timestamp' => $value->tanggal_post->diffForHumans(),
                'url'       => route('frontend.articles.show', ['articlesSlug' => $value->slug_post]),
                'images'    => [
                    'src' => publicMedia($value->filename_post, 'artikel'),
                    'alt' => 'Cover ' . $value->judul_post,
                ]
            ];
        }

        return [
            'header'        => ucwords($titlePage),
            'title'         => ucwords($titlePage) . ' Halaman ' . $page,
            'url'           => route('frontend.articles.' . ($type == 'category' ? 'categories' : 'labels'), [($type == 'category' ? 'categories' : 'label') . 'Code' => $archiveSlug]),
            'navCategories' => $categories,
            'navLabels'     => $labels,
            'category'      => $category,
            'label'         => $label,
            'articles'      => $articles,
            'articlesMeta'  => $articlesRecords['meta']
        ];
    }

    /**
     * Optional metadata for the Artikel page (SEO)
     *
     * @return array
     */
    public static function getMetaData()
    {
        return [
            'title'       => 'Artikel Politeknik Caltex Riau',
            'description' => 'Kumpulan artikel terbaru Politeknik Caltex Riau seputar berita kampus, penelitian, kegiatan mahasiswa, dan informasi menarik lainnya.',
            'keywords'    => 'Artikel, Berita, Penelitian, Riset Unggulan, Pengumuman, Agenda,  Politeknik Caltex Riau'
        ];
    }

    /**
     * Optional page config including background image and SEO structure
     *
     * @return array
     */
    public static function getPageConfig($postContent = null)
    {
        $meta = self::getMetaData();
        $bg   = $postContent ? data_get($postContent, 'content.images.src') : publicMedia('artikel.webp');

        return [
            // Use publicMedia helper so media URL generation matches other services
            'background_image' => $bg,
            'seo'              => [
                'title'                      => $postContent ? data_get($postContent, 'title') : data_get($meta, 'title'),
                'description'                => $postContent ? data_get($postContent, 'meta_desc') : data_get($meta, 'description'),
                'keywords'                   => $postContent ? data_get($postContent, 'meta_keywords') : data_get($meta, 'keywords'),
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

    public static function getCategories()
    {
        try {
            return PostKategori::all()->except(PostKategori::$exceptEdit);
        } catch (\Exception $e) {
            return null;
        }
    }

    public static function getLabels()
    {
        try {
            return PostLabel::all()->except(PostLabel::$exceptEdit);
        } catch (\Exception $e) {
            return null;
        }
    }

    public static function getPost($postSlug)
    {
        try {
            return Post::where('slug_post', $postSlug)->first();
        } catch (\Exception $e) {
            return null;
        }
    }

    public static function getHighlightedArticles($dataCount = 3)
    {
        try {
            return Post::orderBy('tanggal_post', 'DESC')->limit($dataCount)->get()->except(Post::$exceptEdit);
        } catch (\Exception $e) {
            return null;
        }
    }

    public static function getNews($dataCount = 3)
    {
        try {
            return Post::where('postkategori_id', 1)->orderBy('tanggal_post', 'DESC')->limit($dataCount)->get()->except(Post::$exceptEdit);
        } catch (\Exception $e) {
            return null;
        }
    }

    public static function getAchievements($dataCount = 5)
    {
        try {
            return Post::where('postkategori_id', 4)->orderBy('tanggal_post', 'DESC')->limit($dataCount)->get()->except(Post::$exceptEdit);
        } catch (\Exception $e) {
            return null;
        }
    }

    public static function getAnnouncements($dataCount = 5)
    {
        try {
            return Post::where('postkategori_id', 2)->orderBy('tanggal_post', 'DESC')->limit($dataCount)->get()->except(Post::$exceptEdit);
        } catch (\Exception $e) {
            return null;
        }
    }

    public static function getResearch($dataCount = 6)
    {
        try {
            return Post::where('postkategori_id', 3)->orderBy('tanggal_post', 'DESC')->limit($dataCount)->get()->except(Post::$exceptEdit);
        } catch (\Exception $e) {
            return null;
        }
    }

    public static function getBestResearches($dataCount = 5)
    {
        try {
            return Post::where('postkategori_id', operator: 5)->orderBy('tanggal_post', 'DESC')->limit($dataCount)->get()->except(Post::$exceptEdit);
        } catch (\Exception $e) {
            return null;
        }
    }

    public static function getAgenda($dataCount = 5)
    {
        try {
            return Agenda::orderBy('tanggal_start_agenda', 'DESC')->limit($dataCount)->get()->except(Post::$exceptEdit);
        } catch (\Exception $e) {
            return null;
        }
    }

    public static function getArticleByCategory($postkategori_id, $paging = 9, $page = 1)
    {
        try {
            $paginator = Post::where('postkategori_id', $postkategori_id)
                ->orderBy('tanggal_post', 'DESC')
                ->paginate($paging, ['*'], 'page', $page);

            return [
                'data' => $paginator->items(),   // hanya artikel
                'meta' => [
                    'current_page' => $paginator->currentPage(),
                    'per_page'     => $paginator->perPage(),
                    'total'        => $paginator->total(),
                    'last_page'    => $paginator->lastPage(),
                    'path'         => $paginator->path(),
                ],
            ];
        } catch (\Exception $e) {
            return null;
        }
    }

    public static function getArticleByLabel($postlabel_id, $paging = 9, $page = 1)
    {
        try {
            $paginator = Post::whereHas('postLabels', function ($query) use ($postlabel_id) {
                $query->where('post_has_label.postlabel_id', $postlabel_id);
            })
                ->orderBy('tanggal_post', 'DESC')
                ->paginate($paging, ['*'], 'page', $page);
            return [
                'data' => $paginator->items(),   // hanya artikel
                'meta' => [
                    'current_page' => $paginator->currentPage(),
                    'per_page'     => $paginator->perPage(),
                    'total'        => $paginator->total(),
                    'last_page'    => $paginator->lastPage(),
                    'path'         => $paginator->path(),
                ],
            ];
        } catch (\Exception $e) {
            return null;
        }
    }

    public static function getAchievementByLabel(int $limit = 4, ?string $label = null)
    {
        try {
            $query = Post::with(['post_kategori', 'postLabels'])
                ->whereHas('post_kategori', function ($q) {
                    $q->where('kode_kategori', 'prestasi');
                })
                ->orderBy('tanggal_post', 'DESC')
                ->limit($limit);

            if ($label) {
                $query->whereHas('postLabels', function ($q) use ($label) {
                    $q->where('kode_label', $label);
                });
            }

            return $query->get()->except(Post::$exceptEdit);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get complete articles data
     *
     * @param object|null $cmsContent
     * @return object
     */
    public static function getArticlesForHero(): object
    {
        $highlighted        = [];
        $highlightedRecords = self::getHighlightedArticles();

        foreach ($highlightedRecords as $index => $highlight) {
            $highlighted[] = [
                'title'     => $highlight->judul_post,
                'timestamp' => $highlight->tanggal_post->diffForHumans(),
                'url'       => route('frontend.articles.show', ['articlesSlug' => $highlight->slug_post]),
                'images'    => [
                    'src' => publicMedia($highlight->filename_post, 'artikel'),
                    'alt' => 'Cover ' . $highlight->judul_post,
                ]
            ];
        }

        $newest        = [];
        $newestRecords = self::getNews(1);

        foreach ($newestRecords as $index => $news) {
            $newest = [
                'title'     => $news->judul_post,
                'timestamp' => $news->tanggal_post->diffForHumans(),
                'url'       => route('frontend.articles.show', ['articlesSlug' => $news->slug_post]),
                'images'    => [
                    'src' => publicMedia($news->filename_post, 'artikel'),
                    'alt' => 'Cover ' . $news->judul_post,
                ]
            ];
        }

        $achievements        = [];
        $achievementsRecords = self::getAchievements(1);

        foreach ($achievementsRecords as $index => $achievement) {
            $achievements = [
                'title'     => $achievement->judul_post,
                'timestamp' => $achievement->tanggal_post->diffForHumans(),
                'url'       => route('frontend.articles.show', ['articlesSlug' => $achievement->slug_post]),
                'images'    => [
                    'src' => publicMedia($achievement->filename_post, 'artikel'),
                    'alt' => 'Cover ' . $achievement->judul_post,
                ]
            ];
        }

        $researches        = [];
        $researchesRecords = self::getBestResearches(1);

        foreach ($researchesRecords as $index => $research) {
            $researches = [
                'title'     => $research->judul_post,
                'timestamp' => $research->tanggal_post->diffForHumans(),
                'url'       => route('frontend.articles.show', ['articlesSlug' => $research->slug_post]),
                'images'    => [
                    'src' => publicMedia($research->filename_post, 'artikel'),
                    'alt' => 'Cover ' . $research->judul_post,
                ]
            ];
        }

        return (object) [
            'content'      => (object) [
                'subtitle'    => 'Tinta Kampus',
                'title'       => 'Satu Wadah, <u>Banyak Cerita</u>',
                'description' => 'Kehidupan kampus penuh warna. Tinta Kampus menjadi satu wadah yang merangkum cerita, kabar, dan inspirasi dari setiap sudut kampus',
                'sections'    => [
                    'newest_title'       => 'Berita terbaru',
                    'achievements_title' => 'Prestasi terbaru',
                    'researches_title'   => 'Penelitian terbaru',
                ]
            ],
            'highlighted'  => $highlighted,
            'newest'       => $newest,
            'achievements' => $achievements,
            'researches'   => $researches,
        ];
    }

    /**
     * Generate the URL for a given article post.
     *
     * @param \App\Models\Post\Post $post
     * @return string
     */
    public static function generateArticleLink(Post $post): string
    {
        return route('frontend.articles.show', ['articlesSlug' => $post->slug_post]);
    }

    /**
     * Generate the image URL for a given article post.
     *
     * @param \App\Models\Post\Post $post
     * @return string
     */
    public static function generateArticleImageUrl(Post $post): string
    {
        return publicMedia($post->filename_post, 'artikel');
    }
}
