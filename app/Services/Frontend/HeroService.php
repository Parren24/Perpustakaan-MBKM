<?php

namespace App\Services\Frontend;

/**
 * Hero Service
 * 
 * Service untuk mengelola data dan logic terkait hero section
 * 
 * @author wahyudibinsaid
 */
class HeroService
{
    /**
     * 
     * Get complete hero data
     * 
     * @return object
     */
    public static function getHeroData(): object
    {
        $identity        = SiteIdentityService::getSiteIdentity();
        $defaultSlide    = self::getDefaultSlide();
        $additionalSlide = self::getAdditionalSlide();

        $slides = array_merge(data_get($identity, 'hero_settings.show_default_slide', false) ? $defaultSlide : [], $additionalSlide);

        // Calculate durations for each slide
        $slideDurations = self::calculateSlideDurations($slides);

        // Process slides with CTA, social media logic, and media URLs
        $processedSlides = [];
        foreach ($slides as $index => $slide) {
            $media     = data_get($slide, 'media', []);
            $mediaType = data_get($media, 'type', 'image');
            $mediaUrl  = data_get($media, 'src', '');

            // Get slide duration data (single source of truth)
            $slideDurationData = $slideDurations[$index] ?? [];

            // Determine social media visibility
            $showSocialMedia = data_get($slide, 'social_media', true);

            $processedSlides[] = [
                'titles'            => data_get($slide, 'titles', []),
                'subtitle'          => data_get($slide, 'subtitle', []),
                'media'             => [
                    'type'     => $mediaType,
                    'src'      => $mediaUrl,
                    'duration' => data_get($media, 'duration')
                ],
                'cta_buttons'       => data_get($slide, 'cta', []),
                'show_social_media' => $showSocialMedia,
                'durations'         => $slideDurationData, // All timing data from single source
            ];
        }

        return (object) [
            'content'         => (object) [
                'subtitle'               => 'Selamat Datang di <br class="d-md-none"> Politeknik Caltex Riau',
                'default_title_duration' => 5 // seconds per title if image
            ],
            'slides'          => $processedSlides,
            'slide_durations' => $slideDurations,
            'slider_config'   => self::getSliderConfig()
        ];
    }

    public static function getDefaultCTA()
    {
        return [
            [
                'text'   => 'Profil PCR',
                'url'    => 'https://youtu.be/D4HdqnHSQ0o?si=_Aw2ZtYIXAxTV_Ua',
                'class'  => 'btn-default btn-highlighted btn-play',
                'target' => '_blank',
                'type'   => 'video'
            ],
            [
                'text'   => 'Daftar Sekarang',
                'url'    => 'https://pmb.pcr.ac.id',
                'class'  => 'btn-default btn-highlighted',
                'target' => '_blank',
                'type'   => 'cta'
            ]
        ];
    }

    public static function getDefaultSlide()
    {
        return [
            [
                'subtitle'     => 'Selamat Datang di Politeknik Caltex Riau',
                'titles'       => [
                    'Kampus <b>IDEAL</b> untuk Masa Depan Gemilang',
                    'Kampus bebas Asap Rokok, Ramah Lingkungan dan Tertib Lalu Lintas'
                ],
                'media'        => [
                    'type'     => 'video',
                    'src'      => publicMedia('profil-pcr.webm'),
                    'duration' => 120
                ],
                'cta'          => self::getDefaultCTA(),
                'social_media' => true
            ]
        ];
    }

    /**
     * Get raw hero slides data.
     * This data can eventually come from a database.
     *
     * @return array
     */
    private static function getAdditionalSlide(): array
    {
        return [
            [
                'subtitle'     => 'Politeknik Swasta Terbaik',
                'titles'       => [
                    'Pendidikan Vokasi Berkualitas',
                    'Siap Kerja, Siap Bersaing Global'
                ],
                'media'        => [
                    'type'     => 'image',
                    'src'      => publicMedia('welcome-smile.webp'),
                    'duration' => 12 // 12 seconds total, 6 seconds per title
                ],
                'cta'          => self::getDefaultCTA(),
                'social_media' => true
            ],
            [
                'subtitle'     => 'Bersinergi menjadi Kampus Berdampak',
                'titles'       => [
                    'Inovasi dan Teknologi Unggul',
                    'Riset Terapan untuk Masa Depan Berdampak'
                ],
                'media'        => [
                    'type'     => 'image',
                    'src'      => publicMedia('welcome-riset.webp'),
                    'duration' => 24 // 24 seconds video, 8 seconds per title
                ],
                'cta'          => [],
                'social_media' => true
            ]
        ];
    }

    /**
     * Get hero slider configuration
     * 
     * @return array
     */
    public static function getSliderConfig(): array
    {
        return [
            'slidesPerView' => 1,
            'speed'         => 1000,
            'spaceBetween'  => 0,
            'loop'          => true,
            'autoplay'      => [
                'delay'                => 8000,
                'disableOnInteraction' => false,
            ],
            'pagination'    => [
                'el'        => '.hero-pagination',
                'clickable' => true,
            ],
            'effect'        => 'fade',
            'fadeEffect'    => [
                'crossFade' => true
            ]
        ];
    }


    /**
     * Calculate slide and title durations based on media type
     * SINGLE SOURCE OF TRUTH for all timing calculations
     * 
     * @param array $slides
     * @return array
     */
    public static function calculateSlideDurations(array $slides): array
    {
        $slideDurations            = [];
        $defaultTitleCycleDuration = 10; // seconds per title for cycling

        foreach ($slides as $slide) {
            $mediaType  = data_get($slide, 'media.type', 'image');
            $titles     = data_get($slide, 'titles', []);
            $titleCount = count($titles);

            // CONSISTENT LOGIC: Always use 5 seconds per title for cycling
            $slideDuration = data_get($slide, 'media.duration', 0);

            // Ensure slide duration is long enough for all titles to cycle
            $minimumSlideDuration = 1.5 + ($titleCount * $defaultTitleCycleDuration) + 1;
            $slideDuration        = max($slideDuration, $minimumSlideDuration);

            $slideDurations[] = [
                'slide_duration' => $slideDuration * 1000, // ms
                'title_count'    => $titleCount,
                'media_type'     => $mediaType
            ];
        }

        return $slideDurations;
    }
}
