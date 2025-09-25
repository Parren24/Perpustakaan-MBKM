<?php

namespace App\Services\Frontend;

/**
 * PMB (Penerimaan Mahasiswa Baru) Service
 * 
 * Service untuk mengelola data dan logic terkait Penerimaan Mahasiswa Baru
 * 
 * @author wahyudibinsaid
 */
class PMBService
{
    /**
     * Get PMB highlights/benefits for landing page
     * 
     * @return array
     */
    public static function getPMBHighlights(): array
    {
        return [
            'Praktik langsung, siap masuk dunia kerja',
            'Kurikulum sesuai kebutuhan industri',
            'Dosen berpengalaman dan profesional',
            'Fasilitas modern mendukung pembelajaran',
            'Lulusan kompeten, cepat dapat pekerjaan',
        ];
    }

    public static function whyPCR(): array
    {
        return [
            [
                'title'       => 'Politeknik Swasta Terbaik',
                'description' => 'Politeknik Caltex Riau Merupakan Perguruan Tinggi Vokasi Swasta Terbaik di Indonesia Berdasarkan SK Kemenristek Dikti Tahun 2015, 2017, dan 2019',
                'icon'        => 'fa fa-star'
            ],

            [
                'title'       => 'Terakreditasi Unggul',
                'description' => 'PCR telah Terakeditasi Institusi Unggul oleh BAN-PT (Badan Akreditasi Nasional Perguruan Tinggi). Hal ini menunjukan tatakelola institusi yang baik telah dilaksanakan di Politeknik Caltex Riau',
                'icon'        => 'fa fa-trophy'
            ],

            [
                'title'       => 'Pengajar Berkompeten',
                'description' => 'Staf pengajar PCR adalah lulusan dari berbagai kampus unggulan di dalam dan luar negeri.',
                'icon'        => 'fa fa-users'
            ],

            [
                'title'       => 'Program Beasiswa',
                'description' => 'Politeknik Caltex Riau memberikan kesempatan bagi calon mahasiswa ataupun mahasiswa yang berpotensi secara akademik tetapi kurang didukung secara ekonomi melalui Program Beasiswa.',
                'icon'        => 'fa fa-credit-card'
            ],

            [
                'title'       => 'Fasilitas Modern',
                'description' => 'Laboratorium dan Fasilitas yang dimiliki PCR selalui diperbaharui secara berkala agar sesuai dengan kebutuhan Industri.',
                'icon'        => 'fa fa-university'
            ],

            [
                'title'       => 'Kurikulum Siap Kerja',
                'description' => 'Kurikulum yang digunakan di PCR merupakan kurikulum yang telah diselaraskan dengan kebutuhan industri.',
                'icon'        => 'fa fa-graduation-cap'
            ],
        ];

    }

    /**
     * Get PMB action buttons data
     * 
     * @return array
     */
    public static function getPMBActions(): array
    {
        return [
            'primary'   => [
                'text'  => 'Pelajari Lebih Lanjut',
                'url'   => 'https://pmb.pcr.ac.id',
                'class' => 'btn-default'
            ],
            'downloads' => [
                [
                    'text'  => 'Brosur Sarjana Terapan',
                    'url'   => 'https://pmb.pcr.ac.id/brosur/file', // TODO: Add actual download URL
                    'class' => 'btn-default btn-highlighted btn-download'
                ],
                [
                    'text'  => 'Brosur Magister Terapan',
                    'url'   => 'https://magister.pcr.ac.id/assets/brosur/filebrosur20250108110339.pdf', // TODO: Add actual download URL
                    'class' => 'btn-default btn-highlighted btn-download'
                ]
            ]
        ];
    }

    /**
     * Get PMB section default content
     * 
     * @return object
     */
    public static function getDefaultContent(): object
    {
        return (object) [
            'subtitle'    => 'Penerimaan Mahasiswa Baru',
            'title'       => 'Gabung Bersama Kami, <b>Wujudkan Masa Depanmu!</b>',
            'description' => 'Politeknik Caltex Riau membuka kesempatan emas bagi kamu yang ingin meniti karier melalui pendidikan vokasi unggulan. Pilih jurusan yang sesuai dengan passion-mu, nikmati fasilitas modern, dan raih kesuksesan bersama kami. Jadilah bagian dari generasi inovatif!',
            'image'       => [
                'src' => publicMedia('pmb.webp'),
                'alt' => 'PMB Background'
            ]
        ];
    }

    /**
     * Get complete PMB data for landing page
     * 
     * @param object|null $cmsContent
     * @return object
     */
    public static function getPMBData(): object
    {
        $defaultContent = self::getDefaultContent();

        return (object) [
            'content'    => (object) [
                'subtitle'    => $defaultContent->subtitle,
                'title'       => $defaultContent->title,
                'description' => $defaultContent->description,
                'image'       => $defaultContent->image
            ],
            'highlights' => self::getPMBHighlights(),
            'actions'    => self::getPMBActions(),
            'why_pcr'    => self::whyPCR()
        ];
    }

}
