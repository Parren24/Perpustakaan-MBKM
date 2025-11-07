<?php

namespace App\Services\Frontend;

use Illuminate\Support\Facades\Log;

/**
 * Safe Data Service
 *
 * Service untuk handling data dengan aman dan fallback values
 *
 * @author wahyudibinsaid
 */
class SafeDataService
{
    /**
     * Safely get nested object/array value with fallback
     *
     * @param mixed $data
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get($data, string $key, $default = null)
    {
        return data_get($data, $key, $default);
    }

    /**
     * Safely get object property with fallback
     *
     * @param object|null $object
     * @param string $property
     * @param mixed $default
     * @return mixed
     */
    public static function getProperty($object, string $property, $default = null)
    {
        if (!is_object($object)) {
            return $default;
        }

        return property_exists($object, $property) ? $object->{$property} : $default;
    }

    /**
     * Safely get array value with fallback
     *
     * @param array|null $array
     * @param string|int $key
     * @param mixed $default
     * @return mixed
     */
    public static function getArrayValue($array, $key, $default = null)
    {
        if (!is_array($array)) {
            return $default;
        }

        return array_key_exists($key, $array) ? $array[$key] : $default;
    }

    /**
     * Ensure data is object, return empty object if not
     *
     * @param mixed $data
     * @return object
     */
    public static function ensureObject($data): object
    {
        return is_object($data) ? $data : new \stdClass();
    }

    /**
     * Ensure data is array, return empty array if not
     *
     * @param mixed $data
     * @return array
     */
    public static function ensureArray($data): array
    {
        return is_array($data) ? $data : [];
    }

    /**
     * Safely execute service method with fallback
     *
     * Supports:
     * - callable
     * - 'Class::method' string
     * - [ClassOrObject, 'method'] array
     *
     * @param callable|string|array $callback
     * @param mixed $fallback
     * @return mixed
     */
    public static function safeExecute($callback, $fallback = null)
    {
        try {
            // normalize supported callback types
            if (is_callable($callback)) {
                $result = call_user_func($callback);
            } elseif (is_string($callback) && strpos($callback, '::') !== false) {
                [$class, $method] = explode('::', $callback, 2);
                if (class_exists($class) && method_exists($class, $method)) {
                    $result = call_user_func([$class, $method]);
                } else {
                    return $fallback;
                }
            } elseif (is_array($callback) && count($callback) === 2) {
                [$classOrObject, $method] = $callback;
                if ((is_string($classOrObject) && class_exists($classOrObject) && method_exists($classOrObject, $method)) || (is_object($classOrObject) && method_exists($classOrObject, $method))) {
                    $result = call_user_func($callback);
                } else {
                    return $fallback;
                }
            } else {
                // unsupported callback type
                return $fallback;
            }

            return $result !== null ? $result : $fallback;
        } catch (\Throwable $e) {
            Log::warning('SafeDataService: Service method failed', [
                'error'    => $e->getMessage(),
                'callback' => is_string($callback) ? $callback : (is_array($callback) ? json_encode($callback) : (is_object($callback) ? get_class($callback) : gettype($callback))),
                'trace'    => $e->getTraceAsString()
            ]);

            return $fallback;
        }
    }

    /**
     * Create safe data structure with fallbacks
     *
     * @param array $structure
     * @return object
     */
    public static function createSafeStructure(array $structure): object
    {
        $result = new \stdClass();

        foreach ($structure as $key => $value) {
            if (is_array($value)) {
                $result->{$key} = self::createSafeStructure($value);
            } else {
                $result->{$key} = $value;
            }
        }

        return $result;
    }

    /**
     * Get safe fallback data for history page
     *
     * @return object
     */
    public static function getMetaDataFallbacks(): object
    {
        return self::createSafeStructure([
            'title'       => '',
            'description' => '',
            'keywords'    => '',
        ]);
    }

    /**
     * Get safe fallback data for history page
     *
     * @return object
     */
    public static function getPageConfigFallbacks(): object
    {
        return self::createSafeStructure([
            'background_image' => '',
            'seo'              => [
                'title'                      => '',
                'description'                => '',
                'keywords'                   => '',
                'canonical'                  => '',
                'og_image'                   => '',
                'og_type'                    => '',
                'structured_data'            => '',
                'breadcrumb_structured_data' => ''
            ]
        ]);
    }

    /**
     * Get safe fallback data for landing page
     *
     * @return object
     */
    public static function getLandingFallbacks(): object
    {
        return self::createSafeStructure([
            'hero'             => [
                'content'         => [
                    'titles'   => [['Politeknik Caltex Riau']],
                    'subtitle' => 'Selamat Datang di Politeknik Caltex Riau'
                ],
                'media'           => ['type' => 'video'],
                'processed_media' => []
            ],
            'statistics'       => [],
            'jurusan_list'     => [],
            'pmb_data'         => [
                'content'    => [
                    'title'       => 'Informasi PMB',
                    'description' => 'Informasi penerimaan mahasiswa baru.'
                ],
                'highlights' => [],
                'actions'    => ['primary' => ['url' => '#', 'text' => 'Info Lebih Lanjut']]
            ],
            'virtual_tour'     => data_get(self::getVirtualTourFallbacks(), 'callout'),
            'infografis_image' => '', // Added fallback for infografis image
            'sdg'              => [
                'content' => ['title' => 'SDG'],
                'images'  => ['main' => ['src' => '', 'alt' => 'SDG']],
                'goals'   => []
            ],
            'partnership'      => [
                'content'    => ['title' => 'Partnership'],
                'statistics' => [],
                'partners'   => ['institutions' => [], 'instances' => [], 'industries' => []]
            ],
            'site_identity'    => [
                'identity'      => ['tagline' => 'Politeknik Caltex Riau'],
                'contact'       => [
                    'address'     => ['full' => '', 'maps_url' => '#'],
                    'phone'       => ['main' => '', 'mobile' => '', 'formatted_main' => '', 'formatted_mobile' => ''],
                    'description' => 'Politeknik Caltex Riau berlokasi strategis di kawasan pendidikan Kota Pekanbaru.'
                ],
                'social_media'  => [],
                'menus'         => ['services' => [], 'academic' => []],
                'copyright'     => ['full_text' => '© Politeknik Caltex Riau'],
                'hero_cta'      => [
                    ['text' => 'Profil PCR', 'route' => '#', 'class' => 'btn-default'],
                    ['text' => 'Daftar Sekarang', 'url' => '#', 'class' => 'btn-default']
                ],
                'hints_section' => [
                    'title'    => 'Petunjuk',
                    'subtitle' => 'Ingin <span>mudah menemukan</span> lokasi Politeknik Caltex Riau?',
                    'intro'    => 'Politeknik Caltex Riau memiliki lokasi yang strategis dan mudah diakses. Berikut adalah beberapa petunjuk untuk membantu Anda menemukan kampus kami dengan mudah.'
                ]
            ],
            /**
             * Added by DZB for tinta-kammpus section
             */
            'articles'         => [
                'content'      => ['title' => 'Artikel', 'subtitle' => '', 'description' => ''],
                'highlighted'  => [
                    [
                        'title'     => '',
                        'timestamp' => '',
                        'url'       => '',
                        'images'    => [
                            'main' => [
                                'src' => '',
                                'alt' => ''
                            ]
                        ]
                    ]
                ],
                'newest'       => [
                    'title'     => '',
                    'timestamp' => '',
                    'url'       => '',
                    'images'    => [
                        'main' => [
                            'src' => '',
                            'alt' => ''
                        ]
                    ]
                ],
                'achievements' => [
                    'title'     => '',
                    'timestamp' => '',
                    'url'       => '',
                    'images'    => [
                        'main' => [
                            'src' => '',
                            'alt' => ''
                        ]
                    ]
                ],
                'researches'   => [
                    'title'     => '',
                    'timestamp' => '',
                    'url'       => '',
                    'images'    => [
                        'main' => [
                            'src' => '',
                            'alt' => ''
                        ]
                    ]
                ]
            ],
        ]);
    }

    /**
     * Get safe fallback data for history page
     *
     * @return object
     */
    public static function getHistoryFallbacks(): object
    {
        return self::createSafeStructure([
            'header'            => '',
            'title'             => '',
            'subtitle'          => '',
            'timeline'          => [
                [
                    'id'      => '',
                    'title'   => '',
                    'year'    => '',
                    'content' => [],
                    'images'  => [
                        [
                            'src' => '',
                            'alt' => ''
                        ]
                    ]
                ]
            ],
            'table_of_contents' => []
        ]);
    }

    /**
     * Get safe fallback data for visi dan misi page
     *
     * @return object
     */
    public static function getVisiMisiFallbacks(): object
    {
        return self::createSafeStructure([
            'header'   => '',
            'title'    => '',
            'subtitle' => '',

            'vision'   => [
                'title'        => '',
                'subtitle'     => '',
                'introduction' => '',
                'description'  => '',
                'images'       => [
                    'main'  => [
                        'src' => '',
                        'alt' => ''
                    ],
                    'thumb' => [
                        'src' => '',
                        'alt' => ''
                    ]
                ]
            ],
            'mission'  => [
                'title'        => '',
                'subtitle'     => '',
                'introduction' => '',
                'description'  => []
            ],
            'values'   => [
                'title'        => '',
                'subtitle'     => '',
                'introduction' => '',
                'items'        => [
                    [
                        'short'       => '',
                        'title'       => '',
                        'meaning'     => '',
                        'icon'        => '',
                        'description' => '',
                    ]
                ]
            ]
        ]);
    }

    /**
     * Get safe fallback data for diversity page
     *
     * @return object
     */
    public static function getDiversityFallbacks(): object
    {
        return self::createSafeStructure([
            'header'      => '',
            'title'       => '',
            'subtitle'    => '',
            'description' => [],
            'deiversity'  => [
                [
                    'title' => '',
                    'text'  => '',
                    'image' => [
                        'src' => '',
                        'alt' => ''
                    ]
                ]
            ]
        ]);
    }

    /**
     * Get safe fallback data for Sambutan YPCR page
     *
     * @return object
     */
    public static function getWelcomeYpcrFallbacks(): object
    {
        return self::createSafeStructure([
            'header'     => '',
            'title'      => '',
            'ketua_ypcr' => '',
            'subtitle'   => '',
            'greeting'   => [
                'paragraphs' => []
            ],
            'images'     => [
                'main'  => [
                    'src' => '',
                    'alt' => ''
                ],
                'thumb' => [
                    'src' => '',
                    'alt' => ''
                ]
            ]
        ]);
    }

    /**
     * Get safe fallback data for Sambutan Direktur page
     *
     * @return object
     */
    public static function getWelcomeDirectorFallbacks(): object
    {
        return self::createSafeStructure([
            'header'   => '',
            'title'    => '',
            'director' => '',
            'subtitle' => '',
            'greeting' => [
                'paragraphs' => []
            ],
            'images'   => [
                'main'  => [
                    'src' => '',
                    'alt' => ''
                ],
                'thumb' => [
                    'src' => '',
                    'alt' => ''
                ],
            ]
        ]);
    }

    /**
     * Get safe fallback data for Accreditation page
     *
     * @return object
     */
    public static function getAccreditationFallbacks(): object
    {
        return self::createSafeStructure([
            'header'       => '',
            'title'        => '',
            'subtitle'     => '',
            'description'  => [],
            'grid'         => [
                'title'    => '',
                'subtitle' => ''
            ],
            'certificates' => [
                [
                    'title' => '',
                    'date'  => '',
                    'image' => [
                        'src' => '',
                        'alt' => ''
                    ]
                ]
            ]
        ]);
    }

    /**
     * Get safe fallback data for Location page
     *
     * @return object
     */
    public static function getLocationFallbacks(): object
    {
        return self::createSafeStructure([
            'header'        => '',
            'title'         => '',
            'subtitle'      => '',
            'address'       => '',
            'description'   => [],
            'map_embed_url' => '',
            'hints'         => [
                ['title' => '', 'body' => ''],
            ],
            'hints_section' => [
                'title'        => '',
                'subtitle'     => '',
                'introduction' => ''
            ]
        ]);
    }
    /**
     * Get safe fallback data for Identity page
     *
     * @return object
     */
    public static function getIdentityFallbacks(): object
    {
        return self::createSafeStructure([
            'header'         => '',
            'title'          => '',
            'subtitle'       => '',
            'description'    => [],
            'image'          => [
                'src' => '',
                'alt' => ''
            ],
            'identity_guide' => [
                [
                    'id'          => '',
                    'title'       => '',
                    'description' => [],
                    'images'      => [
                        ['src' => '', 'alt' => ''],
                    ],
                    'video'       => '',
                    'links'       => [
                        ['text' => '', 'url' => '', 'class' => '', 'target' => ''],
                    ],
                ]
            ],
        ]);
    }
    /**
     * Get safe fallback data for Achievements page
     *
     * @return object
     */
    public static function getAchievementsFallbacks(): object
    {
        return self::createSafeStructure([
            'header'                  => '',
            'title'                   => '',
            'subtitle'                => '',
            'description'             => [],
            'achievements_categories' => [
                [
                    'id'           => '',
                    'title'        => '',
                    'subtitle'     => '',
                    'description'  => '',
                    'achievements' => [
                        [
                            'title'       => '',
                            'description' => '',
                            'image'       => ['src' => '', 'alt' => ''],
                            'date'        => '',
                            'url'         => '',
                        ],
                    ],
                ],
            ],
        ]);
    }
    /**
     * Get safe fallback data for Organization page
     *
     * @return object
     */
    public static function getOrganizationFallbacks(): object
    {
        return self::createSafeStructure([
            'header'              => '',
            'title'               => '',
            'subtitle'            => '',
            'description'         => [],
            'image'               => [
                'src' => '',
                'alt' => '',
            ],
            'struktur_organisasi' => [
                [
                    'id'      => '',
                    'jabatan' => '',
                    'pejabat' => '',
                    'profil'  => [],
                    'image'   => [
                        'src' => '',
                        'alt' => '',
                    ],
                    'link'    => [
                        [
                            'text' => '',
                            'url'  => '',
                        ]
                    ]
                ]
            ],
        ]);
    }

    /**
     * Get safe fallback data for virtual tour page
     *
     * @return object
     */
    public static function getVirtualTourFallbacks(): object
    {
        return self::createSafeStructure([
            'content' => [
                'header'      => '',
                'title'       => '',
                'subtitle'    => '',
                'description' => '',
                'source'      => ''
            ],
            'callout' => [
                'title'       => '',
                'subtitle'    => '',
                'description' => '',
                'features'    => [],
                'action'      => [
                    'url'         => '',
                    'class'       => '',
                    'cursor_text' => '',
                    'icon'        => '',
                    'button_text' => '',
                ],
                'background'  => [
                    'image'  => [
                        'src' => '',
                        'alt' => ''
                    ],
                    'styles' => [
                        'background-size'       => '',
                        'background-repeat'     => '',
                        'background-attachment' => '',
                        'background-position'   => '',
                    ]
                ]
            ]
        ]);
    }

    /**
     * Get safe fallback data for academic scholarship page
     *
     * @return object
     */
    public static function getAcademicScholarshipFallbacks(): object
    {
        return self::createSafeStructure([
            'content' => [
                'header'            => '',
                'title'             => '',
                'description'       => [''],
                'about_scholarship' => [
                    'title'       => '',
                    'description' => '',
                    'image'       => [
                        'src' => '',
                        'alt' => ''
                    ]
                ],
                'scholarship_list'  => []
            ]
        ]);
    }

    /**
     * Get safe fallback data for PCR Squad page
     *
     * @return object
     */
    public static function getPCRSquadFallbacks(): object
    {
        return self::createSafeStructure([
            'content' => [
                'header'      => '',
                'title'       => '',
                'description' => '',
                'pmb'         => [
                    'title'       => '',
                    'subtitle'    => '',
                    'description' => '',
                    'images'      => [
                        'main'  => [
                            'src' => '',
                            'alt' => ''
                        ],
                        'thumb' => [
                            'src' => '',
                            'alt' => ''
                        ],
                    ],
                    'link'        => [
                        [
                            'url'  => '',
                            'text' => ''
                        ]
                    ],
                ],
                'recruitment' => [
                    'title'       => '',
                    'subtitle'    => '',
                    'description' => '',
                    'image'       => [
                        'src' => '',
                        'alt' => ''
                    ],
                    'link'        => [
                        [
                            'url'  => '',
                            'text' => ''
                        ]
                    ],
                ],
                'why_pcr'     => [
                    'title'       => '',
                    'subtitle'    => '',
                    'description' => '',
                    'items'       => ''
                ]
            ]
        ]);
    }

    /**
     * Get safe fallback data for facilities page
     *
     * @param string $pageType 'index' or 'detail'
     * @return object
     */
    public static function getFacilitiesFallbacks(string $pageType): object
    {
        if ($pageType === 'detail') {
            return self::createSafeStructure([
                'content' => [
                    'header'      => '',
                    'title'       => '',
                    'description' => '',
                    'images'      => [
                        'main'    => ['src' => '', 'alt' => ''],
                        'gallery' => []
                    ],
                    'sections'    => []
                ]
            ]);
        }

        return self::createSafeStructure([
            'content' => [
                'header'          => '',
                'title'           => '',
                'subtitle'        => '',
                'description'     => '',
                'image'           => [
                    'src' => '',
                    'alt' => ''
                ],
                'action'          => [
                    'url'         => '',
                    'class'       => '',
                    'cursor_text' => '',
                    'icon'        => '',
                    'text'        => ''
                ],
                'facilities_list' => [
                    [
                        'title'       => '',
                        'description' => '',
                        'image'       => [
                            [
                                'src' => '',
                                'alt' => ''
                            ]
                        ]
                    ],
                ]
            ]
        ]);
    }

    /**
     * Get safe fallback data for artikel page
     *
     * @return object
     */
    public static function getArticleFallbacks(): object
    {
        return self::createSafeStructure([
            'header'            => '',
            'title'             => '',
            'subtitle'          => '',

            'highlighted'       => [
                'title'     => '',
                'timestamp' => '',
                'url'       => '',
                'images'    => [
                    'main' => [
                        'src' => '',
                        'alt' => ''
                    ]
                ]
            ],
            'newest'            => [
                'title'     => '',
                'timestamp' => '',
                'url'       => '',
                'images'    => [
                    'main' => [
                        'src' => '',
                        'alt' => ''
                    ]
                ]
            ],
            'achievements'      => [
                'title'     => '',
                'timestamp' => '',
                'url'       => '',
                'images'    => [
                    'main' => [
                        'src' => '',
                        'alt' => ''
                    ]
                ]
            ],
            'best-research'     => [
                'title'     => '',
                'timestamp' => '',
                'url'       => '',
                'images'    => [
                    'main' => [
                        'src' => '',
                        'alt' => ''
                    ]
                ]
            ],
            'research-activity' => [
                'title'     => '',
                'timestamp' => '',
                'url'       => '',
                'images'    => [
                    'main' => [
                        'src' => '',
                        'alt' => ''
                    ]
                ]
            ],
        ]);
    }


    /**
     * Get safe fallback data for artikel show page
     *
     * @return object
     */
    public static function getArticleShowFallbacks(): object
    {
        return self::createSafeStructure([
            'header'      => '',
            'title'       => '',
            'url'         => '',
            'categories'  => [
                ['title' => '', 'url' => '']
            ],
            'latest_news' => [
                [
                    'title'     => '',
                    'timestamp' => '',
                    'url'       => '',
                    'images'    => [
                        'main' => [
                            'src' => '',
                            'alt' => ''
                        ]
                    ]
                ]
            ],
            'content'     => [
                'body'      => '',
                'timestamp' => '',
                'author'    => '',
                'labels'    => [
                    [
                        'label' => '',
                        'url'   => '',
                    ]
                ],
                'images'    => [
                    'main' => [
                        'src' => '',
                        'alt' => ''
                    ]
                ]
            ],
        ]);
    }

    /**
     * Get safe fallback data for artikel archive page
     *
     * @return object
     */
    public static function getArticleArchiveFallbacks(): object
    {
        return self::createSafeStructure([
            'header'       => '',
            'title'        => '',
            'url'          => '',
            'categories'   => [
                ['title' => '', 'url' => '']
            ],
            'labels'       => [
                ['title' => '', 'url' => '']
            ],
            'articles'     => [
                [
                    'title'     => '',
                    'timestamp' => '',
                    'url'       => '',
                    'images'    => [
                        'main' => [
                            'src' => '',
                            'alt' => ''
                        ]
                    ]
                ]
            ],
            'articlesMeta' => []
        ]);
    }

    /**
     * Get safe fallback data for Contact page
     *
     * @return object
     */
    public static function getContactFallbacks(): object
    {
        return self::createSafeStructure([
            'content' => [
                'header'           => '',
                'title'            => '',
                'description'      => '',
                'contact_sections' => [
                    'phone'   => [
                        'title'       => '',
                        'description' => '',
                        'details'     => []
                    ],
                    'email'   => [
                        'title'       => '',
                        'description' => '',
                        'details'     => []
                    ],
                    'address' => [
                        'title'         => '',
                        'description'   => '',
                        'details'       => [],
                        'map_embed_url' => ''
                    ]
                ],
                'form'             => [
                    'title'       => '',
                    'description' => '',
                    'action_url'  => '',
                    'fields'      => [
                        'name'    => ['label' => '', 'placeholder' => ''],
                        'email'   => ['label' => '', 'placeholder' => ''],
                        'subject' => ['label' => '', 'placeholder' => ''],
                        'message' => ['label' => '', 'placeholder' => ''],
                    ]
                ],
                'social_media'     => [
                    'title'       => '',
                    'description' => '',
                    'links'       => []
                ]
            ]
        ]);
    }

    public static function getPeminjamanFallbacks(): object
    {
        return self::createSafeStructure([
            'content' => [
                'title'       => 'Peminjaman',
                'description' => 'Informasi mengenai peminjaman fasilitas.',
                // Add more fields as necessary
            ]
        ]);
    }

    public static function getItemFallbacks(): object
    {
        return self::createSafeStructure([
            'content' => [
                'title'       => 'Item',
                'description' => 'Informasi mengenai item.',
                // Add more fields as necessary
            ]
        ]);
    }

    /**
     * Get safe fallback data for artikel archive page
     *
     * @return object
     */
    public static function getJurusanFallbacks(): object
    {
        return self::createSafeStructure([
            'header'     => '',
            'title'      => '',
            'subtitle'   => '',
            'url'        => '',
            'jurusan'   => [
                [
                    'title'     => '',
                    'description' => '',
                    'url'       => '',
                    'images'    => [
                        'src' => '',
                        'alt' => ''
                    ]
                ]
            ]
        ]);
    }
}
