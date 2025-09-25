<?php

namespace App\Services\Frontend;

/**
 * Partnership Service
 * 
 * Service untuk mengelola data dan logic terkait kemitraan/rekan kerja sama
 * 
 * @author wahyudibinsaid
 */
class PartnershipService
{
    /**
     * Get partnership content data
     * 
     * @param object|null $cmsContent
     * @return object
     */
    public static function getContent(): object
    {
        return (object) [
            'subtitle'    => 'Kerja Sama',
            'title'       => '<span>Sinergi Kuat, Jaringan Luas</span> Mitra Politeknik Caltex Riau',
            'description' => 'Politeknik Caltex Riau menjalin kemitraan strategis dengan berbagai instansi, institusi, dan industri. Kerja sama ini memperkuat jaringan, meningkatkan kualitas pendidikan, dan membuka peluang bagi mahasiswa untuk berkarier di dunia profesional.',
            'statistics'  => self::getStatistics()
        ];
    }

    /**
     * Get institutions partnership data
     * 
     * @return array
     */
    public static function getInstitutions(): array
    {
        return [
            ['id' => '1', 'name' => 'Management And Science University Malaysia', 'image' => ['src' => publicMedia('Management_And_Science_University_Malaysia.webp', ['partner']), 'alt' => '']],
            ['id' => '2', 'name' => 'Politeknik Sultan Salahuddin Abdul Aziz Shah', 'image' => ['src' => publicMedia('Politeknik_Sultan_Salahuddin_Abdul_Aziz_Shah.webp', ['partner']), 'alt' => '']],
            ['id' => '3', 'name' => 'Universiti Tun Hussein Onn Malaysia', 'image' => ['src' => publicMedia('Universiti_Tun_Hussein_Onn_Malaysia.webp', ['partner']), 'alt' => '']],
            ['id' => '4', 'name' => 'Politeknik Mersing Malaysia', 'image' => ['src' => publicMedia('Politeknik_Mersing_Malaysia.webp', ['partner']), 'alt' => '']],
        ];
    }

    /**
     * Get government instances partnership data
     * 
     * @return array
     */
    public static function getInstances(): array
    {
        return [
            ['id' => '8', 'name' => 'Badan Pengelola Dana Perkebunan Kelapa Sawit', 'image' => ['src' => publicMedia('Badan_Pengelola_Dana_Perkebunan_Kelapa_Sawit.webp', ['partner']), 'alt' => '']],
            ['id' => '9', 'name' => 'Pemerintah Provinsi Riau', 'image' => ['src' => publicMedia('Pemerintah_Provinsi_Riau.webp', ['partner']), 'alt' => '']],
            ['id' => '10', 'name' => 'Pemerintah Kota Dumai', 'image' => ['src' => publicMedia('Pemerintah_Kota_Dumai.webp', ['partner']), 'alt' => '']],
            ['id' => '11', 'name' => 'Pemerintah Kabupaten Siak', 'image' => ['src' => publicMedia('Pemerintah_Kabupaten_Siak.webp', ['partner']), 'alt' => '']],
            ['id' => '12', 'name' => 'Pemerintah Kabupaten Rokan Hilir', 'image' => ['src' => publicMedia('Pemerintah_Kabupaten_Rokan_Hilir.webp', ['partner']), 'alt' => '']],
            ['id' => '13', 'name' => 'Pemerintah Kabupaten Kampar', 'image' => ['src' => publicMedia('Pemerintah_Kabupaten_Kampar.webp', ['partner']), 'alt' => '']],
            ['id' => '14', 'name' => 'Dinas Tenaga Kerja dan Transmigrasi Provinsi Riau', 'image' => ['src' => publicMedia('Dinas_Tenaga_Kerja_dan_Transmigrasi_Provinsi_Riau.webp', ['partner']), 'alt' => '']],
            ['id' => '15', 'name' => 'Dinas Pendidikan Provinsi Riau', 'image' => ['src' => publicMedia('Dinas_Pendidikan_Provinsi_Riau.webp', ['partner']), 'alt' => '']],
        ];
    }

    /**
     * Get industry partnership data
     * 
     * @return array
     */
    public static function getIndustries(): array
    {
        return [
            ['id' => '13', 'name' => 'PT Chevron Pacific Indonesia', 'image' => ['src' => publicMedia('PT_Chevron_Pacific_Indonesia.webp', ['partner']), 'alt' => '']],
            ['id' => '14', 'name' => 'PT SLB Indonesia', 'image' => ['src' => publicMedia('PT_SLB_Indonesia.webp', ['partner']), 'alt' => '']],
            ['id' => '15', 'name' => 'PT Pertamina Hulu Rokan', 'image' => ['src' => publicMedia('PT_Pertamina_Hulu_Rokan.webp', ['partner']), 'alt' => '']],
            ['id' => '16', 'name' => 'PT P&G Operations Indonesia', 'image' => ['src' => publicMedia('PT_PG_Operations_Indonesia.webp', ['partner']), 'alt' => '']],
            ['id' => '17', 'name' => 'PT Wings Surya', 'image' => ['src' => publicMedia('PT_Wings_Surya.webp', ['partner']), 'alt' => '']],
            ['id' => '18', 'name' => 'PT Perkebunan Nusantara IV Regional III', 'image' => ['src' => publicMedia('PT_Perkebunan_Nusantara_IV_Regional_III.webp', ['partner']), 'alt' => '']],
            ['id' => '19', 'name' => 'PT Usaha Saudara Mandiri', 'image' => ['src' => publicMedia('PT_Usaha_Saudara_Mandiri.webp', ['partner']), 'alt' => '']],
            ['id' => '20', 'name' => 'PT Indah Kiat Pulp and Paper', 'image' => ['src' => publicMedia('PT_Indah_Kiat_Pulp_and_Paper.webp', ['partner']), 'alt' => '']],
            ['id' => '21', 'name' => 'PT Arara Abadi', 'image' => ['src' => publicMedia('PT_Arara_Abadi.webp', ['partner']), 'alt' => '']],
            ['id' => '22', 'name' => 'PT. PCI Elektronik Internasional', 'image' => ['src' => publicMedia('PT_PCI_Elektronik_Internasional.webp', ['partner']), 'alt' => '']],
            ['id' => '23', 'name' => 'PT Amber Karya', 'image' => ['src' => publicMedia('PT_Amber_Karya.webp', ['partner']), 'alt' => '']],
            ['id' => '24', 'name' => 'PT Bakti Timah Medika Pangkal Pinang', 'image' => ['src' => publicMedia('PT_Bakti_Timah_Medika_Pangkal_Pinang.webp', ['partner']), 'alt' => '']],
            ['id' => '25', 'name' => 'PT. Sangnila Interaktif Media dan Teknologi', 'image' => ['src' => publicMedia('PT_Sangnila_Interaktif_Media_dan_Teknologi.webp', ['partner']), 'alt' => '']],
            ['id' => '26', 'name' => 'PT Bank Riau Kepri Syariah (Perseroda)', 'image' => ['src' => publicMedia('PT_Bank_Riau_Kepri_Syariah.webp', ['partner']), 'alt' => '']],
        ];
    }

    /**
     * Get partnership statistics
     * 
     * @return array
     */
    public static function getStatistics(): array
    {
        $institutions = self::getInstitutions();
        $instances    = self::getInstances();
        $industries   = self::getIndustries();

        return [
            [
                // 'count'   => count($instances),
                'count'   => 35,
                'label'   => 'Instansi',
                'counter' => true
            ],
            [
                // 'count'   => count($institutions),
                'count'   => 23,
                'label'   => 'Institusi',
                'counter' => true
            ],
            [
                // 'count'   => count($industries),
                'count'   => 48,
                'label'   => 'Industri',
                'counter' => true
            ]
        ];
    }

    /**
     * Get swiper configuration for partnership sliders
     * 
     * @return array
     */
    public static function getSwiperConfig(): array
    {
        return [
            'slidesPerView' => 'auto',
            'spaceBetween'  => 16,
            'loop'          => true,
            'freeMode'      => [
                'enabled'  => true,
                'momentum' => false,
            ],
            'autoplay'      => [
                'delay'                => 0,
                'pauseOnMouseEnter'    => true,
                'disableOnInteraction' => false,
            ],
            'speed_range'   => [7500, 8000] // Min and max speed
        ];
    }

    /**
     * Get image configuration for partnerships
     * 
     * @return object
     */
    public static function getImageConfig(): object
    {
        return (object) [
            'placeholder'  => 'https://placehold.co/150',
            'alt_template' => 'Gambar {name}',
            'class'        => 'rekan-slider-image'
        ];
    }

    /**
     * Shuffle partnership data
     * 
     * @param array $data
     * @return array
     */
    public static function shuffleData(array $data): array
    {
        shuffle($data);
        return $data;
    }

    /**
     * Get complete partnership data
     * 
     * @param object|null $cmsContent
     * @return object
     */
    public static function getPartnershipData(): object
    {
        return (object) [
            'content'            => self::getContent(),
            'partners'           => (object) [
                'institutions' => self::shuffleData(self::getInstitutions()),
                'instances'    => self::shuffleData(self::getInstances()),
                'industries'   => self::shuffleData(self::getIndustries()),
            ],
            'swiper_config'      => self::getSwiperConfig(),
            'swiper_config_json' => json_encode(self::getSwiperConfig()),
            'image_config'       => self::getImageConfig()
        ];
    }
}
