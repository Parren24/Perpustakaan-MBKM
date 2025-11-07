<?php

namespace App\Services\Frontend;

/**
 * SDG (Sustainable Development Goals) Service
 * 
 * Service untuk mengelola data dan logic terkait SDG (Sustainable Development Goals)
 * 
 * @author wahyudibinsaid
 */
class SDGService
{
    /**
     * Get SDG content data
     * 
     * @return object
     */
    public static function getContent(): object
    {
        return (object) [
            'subtitle'    => 'SDGS',
            'title'       => 'Bersama untuk Masa Depan yang <b>Berkelanjutan</b>',
            'description' => 'Politeknik Caltex Riau berkomitmen untuk mendukung pembangunan berkelanjutan melalui pendidikan inovatif, riset unggulan, dan praktik ramah lingkungan. Kami percaya bahwa kolaborasi antara ilmu pengetahuan, teknologi, dan kesadaran sosial dapat menciptakan masa depan yang lebih hijau dan inklusif. Mari bersama-sama membangun dunia yang lebih baik!',
            // 'cta' => [
            //     'text' => 'Pelajari Lebih Lanjut',
            //     'url' => '', // TODO: Add actual URL
            //     'class' => 'btn-default'
            // ]
        ];
    }

    /**
     * Get SDG images configuration
     * 
     * @return object
     */
    public static function getImages(): object
    {
        return (object) [
            'main'  => [
                'src'   => asset('theme/frontend/images/sdg/SDG.avif'),
                'alt'   => 'SDG Square',
                'style' => 'object-fit: contain;'
            ],
            'goals' => [
                'path_template' => asset('theme/frontend/images/sdg/E-WEB-Goal-{goal}.avif'),
                'alt_template'  => 'sdg-{goal}',
                'class'         => 'sdg-image',
                'loading'       => 'lazy'
            ]
        ];
    }

    /**
     * Get SDG goals configuration
     * 
     * @return array
     */
    public static function getSDGGoals(): array
    {
        return range(1, 17); // 17 SDG Goals
    }

    /**
     * Get Swiper configuration for SDG carousel
     * 
     * @return array
     */
    public static function getSwiperConfig(): array
    {
        return [
            'slidesPerView' => 'auto',
            'speed'         => 2500,
            'spaceBetween'  => 16,
            'loop'          => true,
            'freeMode'      => [
                'enabled'  => true,
                'momentum' => false,
            ],
            'autoplay'      => [
                'delay'                => 0,
                'pauseOnMouseEnter'    => true,
                'reverseDirection'     => false,
                'disableOnInteraction' => false,
            ]
        ];
    }

    /**
     * Generate SDG goal image path
     * 
     * @param int $goalNumber
     * @return string
     */
    public static function getSDGGoalImagePath(int $goalNumber): string
    {
        $images = self::getImages();
        return str_replace('{goal}', $goalNumber, $images->goals['path_template']);
    }

    /**
     * Generate SDG goal alt text
     * 
     * @param int $goalNumber
     * @return string
     */
    public static function getSDGGoalAltText(int $goalNumber): string
    {
        $images = self::getImages();
        return str_replace('{goal}', $goalNumber, $images->goals['alt_template']);
    }

    /**
     * Get complete SDG data
     * 
     * @return object
     */
    public static function getSDGData(): object
    {
        return (object) [
            'content'            => self::getContent(),
            'images'             => self::getImages(),
            'goals'              => self::getSDGGoals(),
            'swiper_config'      => self::getSwiperConfig(),
            'swiper_config_json' => json_encode(self::getSwiperConfig())
        ];
    }
}
