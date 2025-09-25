<?php

namespace Database\Seeders;

use App\Models\Konten;
use App\Models\View\Jurusan;
use App\Models\View\Prodi;
use Illuminate\Database\Seeder;

class KontenSeeder extends Seeder
{
    public function run(): void
    {
        $content = [];

        // Landing Page Spec & Values
        $header_spec = [];
        $section_spec = [
            [
                'name' => 'Hero',
                'type' => 'hero',
                'is_multiple' => true,
                'data' => [
                    ['name' => 'title', 'label' => 'Judul', 'input_type' => 'text', 'multiple' => true],
                    ['name' => 'media_id', 'label' => 'Media', 'input_type' => 'file', 'allowed_file_types' => ['jpg', 'png'], 'multiple' => false]
                ]
            ],
            [
                'name' => 'Infografis',
                'type' => 'form-data',
                'is_multiple' => false,
                'source_model' => 'App\\Models\\Dimension\\DmInfografis',
                'source_id' => '',
                'data' => [
                    ['name' => 'title', 'label' => 'Judul', 'input_type' => 'text', 'multiple' => false],
                    ['name' => 'deskripsi', 'label' => 'Deskripsi', 'input_type' => 'textarea', 'multiple' => false],
                    ['name' => 'media_id', 'label' => 'Media', 'input_type' => 'file', 'allowed_file_types' => ['jpg', 'png'], 'multiple' => false]
                ]
            ],
            [
                'name' => 'Jurusan',
                'type' => 'form-data',
                'is_multiple' => false,
                'source_model' => 'App\\Models\\Dimension\\DmJurusan',
                'source_id' => '',
                'data' => [
                    ['name' => 'title', 'label' => 'Judul', 'input_type' => 'text', 'multiple' => false],
                    ['name' => 'deskripsi', 'label' => 'Deskripsi', 'input_type' => 'textarea', 'multiple' => false]
                ]
            ],
            [
                'name' => 'PMB',
                'type' => 'form-data',
                'is_multiple' => false,
                'data' => [
                    ['name' => 'title', 'label' => 'Judul', 'input_type' => 'text', 'multiple' => false],
                    ['name' => 'deskripsi', 'label' => 'Deskripsi', 'input_type' => 'textarea', 'multiple' => false],
                    ['name' => 'media_id', 'label' => 'Media', 'input_type' => 'file', 'allowed_file_types' => ['jpg', 'png'], 'multiple' => false]
                ]
            ],
            [
                'name' => 'Partner',
                'type' => 'form-data',
                'is_multiple' => false,
                'source_model' => 'App\\Models\\Dimension\\DmPartner',
                'source_id' => '',
                'data' => [
                    ['name' => 'title', 'label' => 'Judul', 'input_type' => 'text', 'multiple' => false],
                    ['name' => 'deskripsi', 'label' => 'Deskripsi', 'input_type' => 'textarea', 'multiple' => false]
                ]
            ]
        ];
        $footer_spec = [];

        $header_values = [];
        $section_values = [
            [
                ['title' => ['Kampus <i>IDEAL</i> untuk Masa Depan Gemilang', 'Kampus Bebas Asap Rokok, Ramah Lingkungan, Tertib Lalu Lintas'], 'media_id' => [1]],
                ['title' => ['Menjadi Pilar Masa Depan, Dimulai di Sini'], 'media_id' => [2]]
            ],
            [
                ['title' => ['<b>Infografis</b> Politeknik Caltex Riau'], 'deskripsi' => ['Politeknik Caltex Riau merupakan salah satu Politeknik Swasta Terbaik di Indonesia yang berada di Kota Pekanbaru Provinsi Riau. Politeknik Caltex Riau memiliki budaya disiplin, kebersamaan dan cinta lingkungan'], 'media_id' => [3]]
            ],
            [
                ['title' => ['Pendidikan Vokasi: <u> Langsung Kerja, Siap Berkarya</u>'], 'deskripsi' => ['Melalui pendidikan vokasi, Politeknik Caltex Riau membekali mahasiswa dengan keterampilan praktis dan pengetahuan aplikatif. Kurikulum dirancang untuk menjawab kebutuhan dunia industri, menjadikan lulusan siap kerja dan kompeten di bidangnya']]
            ],
            [
                ['title' => ['Gabung Bersama Kami, <b>Wujudkan Masa Depanmu!</b>'], 'deskripsi' => ['Politeknik Caltex Riau membuka kesempatan emas bagi kamu yang ingin meniti karier melalui pendidikan vokasi unggulan. Pilih jurusan yang sesuai dengan passion-mu, nikmati fasilitas modern, dan raih kesuksesan bersama kami. Jadilah bagian dari generasi inovatif!'], 'media_id' => [4]]
            ],
            [
                ['title' => ['<b>Sinergi Kuat, Jaringan Luas</b> Rekan Kerja Sama Politeknik Caltex Riau'], 'deskripsi' => ['Politeknik Caltex Riau menjalin kemitraan strategis dengan berbagai instansi, institusi, dan industri. Kerja sama ini memperkuat jaringan, meningkatkan kualitas pendidikan, dan membuka peluang bagi mahasiswa untuk berkarier di dunia profesional']]
            ]
        ];
        $footer_values = [];


        $content[] = [
            'page_name' => 'landing',
            'page_id' => null,
            'data_specification' => json_encode([
                'header' => $header_spec,
                'section' => $section_spec,
                'footer' => $footer_spec,
            ]),
            'data_values' => json_encode([
                'header' => $header_values,
                'section' => $section_values,
                'footer' => $footer_values,
            ])
        ];


        // Jurusan Pages
        $jurusanList = Jurusan::all();
        foreach ($jurusanList as $jurusan) {

            // Add extra sections for jurusan
            $header_spec = [
                [
                    'name' => 'Header',
                    'type' => 'form',
                    'is_multiple' => false,
                    'data' => [
                        ['name' => 'media_id', 'label' => 'Media', 'input_type' => 'file', 'allowed_file_types' => ['jpg', 'png'], 'multiple' => false]
                    ]
                ]
            ];
            $section_spec = [
                [
                    'name' => 'Sambutan Ketua Jurusan',
                    'type' => 'form',
                    'is_multiple' => false,
                    'data' => [
                        ['name' => 'title', 'label' => 'Judul', 'input_type' => 'text', 'multiple' => false],
                        ['name' => 'deskripsi', 'label' => 'Deskripsi', 'input_type' => 'textarea', 'multiple' => false],
                        ['name' => 'kajur', 'label' => 'Nama Ketua Jurusan', 'input_type' => 'text', 'multiple' => false],
                        ['name' => 'media_id', 'label' => 'Foto Ketua Jurusan', 'input_type' => 'file', 'allowed_file_types' => ['jpg', 'png'], 'multiple' => false]
                    ]
                ],
                [
                    'name' => 'Tentang Jurusan',
                    'type' => 'form',
                    'is_multiple' => false,
                    'data' => [
                        ['name' => 'title', 'label' => 'Judul', 'input_type' => 'text', 'multiple' => false],
                        ['name' => 'deskripsi', 'label' => 'Deskripsi', 'input_type' => 'textarea', 'multiple' => false],
                        ['name' => 'media_id', 'label' => 'Media', 'input_type' => 'file', 'allowed_file_types' => ['jpg', 'png'], 'multiple' => false]
                    ]
                ],
                [
                    'name' => 'Profil Dosen',
                    'type' => 'data',
                    'is_multiple' => false,
                    'source_model' => 'App\\Models\\Dimension\\DmDosen',
                    'source_id' => $jurusan->alias,
                    'data' => []
                ],
                [
                    'name' => 'Program Studi',
                    'type' => 'data',
                    'is_multiple' => false,
                    'source_model' => 'App\\Models\\Dimension\\DmProdi',
                    'source_id' => $jurusan->alias,
                    'data' => []
                ],
                [
                    'name' => 'Agenda',
                    'type' => 'data',
                    'is_multiple' => false,
                    'source_model' => 'App\\Models\\Agenda',
                    'source_id' => $jurusan->alias,
                    'data' => []
                ],
                [
                    'name' => 'Berita',
                    'type' => 'data',
                    'is_multiple' => false,
                    'source_model' => 'App\\Models\\Berita',
                    'source_id' => $jurusan->alias,
                    'data' => []
                ]
            ];
            $footer_spec = [];

            // Values
            $header_values[] = [
                [
                    ['title' => [$jurusan->nama], 'media_id' => ['']]
                ]
            ];
            $section_values = [
                [
                    ['title' => ['Sambutan Kajur' . $jurusan->nama], 'deskripsi' => ['...'], 'media_id' => ['']]
                ],
                [
                    ['title' => ['Tentang ' . $jurusan->nama], 'deskripsi' => ['...'], 'media_id' => ['']]
                ],
                [[]],
                [[]],
                [[]],
                [[]]
            ]; // berita

            $content[] = [
                'page_name' => 'jurusan_page',
                'page_id' => $jurusan->alias,
                'data_specification' => json_encode([
                    'header' => $header_spec,
                    'section' => $section_spec,
                    'footer' => $footer_spec,
                ]),
                'data_values' => json_encode([
                    'header' => $header_values,
                    'section' => $section_values,
                    'footer' => $footer_values,
                ])
            ];
        }

        // Prodi Pages
        $prodiList = Prodi::all();
        foreach ($prodiList as $prodi) {
            $header_spec = [
                [
                    'name' => 'Header',
                    'type' => 'form',
                    'is_multiple' => false,
                    'data' => [
                        ['name' => 'media_id', 'label' => 'Media', 'input_type' => 'file', 'allowed_file_types' => ['jpg', 'png'], 'multiple' => false]
                    ]
                ]
            ];
            $section_spec = [
                [
                    'name' => 'Tentang Program Studi',
                    'type' => 'form',
                    'is_multiple' => false,
                    'data' => [
                        ['name' => 'title', 'label' => 'Judul', 'input_type' => 'text', 'multiple' => false],
                        ['name' => 'media_id', 'label' => 'Media', 'input_type' => 'file', 'allowed_file_types' => ['jpg', 'png'], 'multiple' => false]
                    ]
                ],
                [
                    'name' => 'Prospek Karir',
                    'type' => 'form',
                    'is_multiple' => false,
                    'data' => [
                        ['name' => 'title', 'label' => 'Judul', 'input_type' => 'text', 'multiple' => false],
                        ['name' => 'deskripsi', 'label' => 'Deskripsi', 'input_type' => 'textarea', 'multiple' => false]
                    ]
                ],
                [
                    'name' => 'Daftar Prospek Karir',
                    'type' => 'form',
                    'is_multiple' => true,
                    'data' => [
                        ['name' => 'prospek_karir', 'label' => 'Prospek Karir', 'input_type' => 'text', 'multiple' => false],
                        ['name' => 'icon', 'label' => 'Icon', 'input_type' => 'text', 'multiple' => false]
                    ]
                ],
                [
                    'name' => 'Sejarah',
                    'type' => 'milestone',
                    'is_multiple' => true,
                    'data' => [
                        ['name' => 'tahun', 'label' => 'Tahun', 'input_type' => 'number', 'multiple' => false],
                        ['name' => 'deskripsi', 'label' => 'Deskripsi', 'input_type' => 'textarea', 'multiple' => false],
                        ['name' => 'media_id', 'label' => 'Media', 'input_type' => 'file', 'allowed_file_types' => ['jpg', 'png'], 'multiple' => false]
                    ]
                ],
                [
                    'name' => 'Visi',
                    'type' => 'form',
                    'is_multiple' => false,
                    'data' => [
                        ['name' => 'visi', 'label' => 'Visi', 'input_type' => 'text', 'multiple' => false]
                    ]
                ],
                [
                    'name' => 'Misi',
                    'type' => 'form',
                    'is_multiple' => true,
                    'data' => [
                        ['name' => 'misi', 'label' => 'Misi', 'input_type' => 'text', 'multiple' => false],
                        ['name' => 'icon', 'label' => 'Icon', 'input_type' => 'text', 'multiple' => false]
                    ]
                ]
            ];
            $footer_spec = [];

            $header_values = [
                [
                    ['media_id' => ['']]
                ]
            ];
            $section_values = [
                [
                    ['title' => ['Tentang ' . $prodi->nama], 'media_id' => ['']]
                ],
                [
                    ['title' => ['Prospek Karir ' . $prodi->nama], 'deskripsi' => ['...']]
                ],
                [
                    ['prospek_karir' => ['Frontend Developer'], 'icon' => ['ri-code-s-slash-line']],
                    ['prospek_karir' => ['Backend Developer'], 'icon' => ['ri-code-s-slash-line']]
                ],
                [
                    ['tahun' => ['2020'], 'deskripsi' => ['Awal berdiri'], 'media_id' => ['']]
                ],
                [
                    ['visi' => ['Menjadi prodi unggul...']]
                ],
                [
                    ['misi' => ['Meningkatkan kualitas...'], 'icon' => ['ri-graduation-cap-line']]
                ]
            ];
            $footer_values = [];

            $content[] = [
                'page_name' => 'prodi_page',
                'page_id' => $prodi->alias,
                'data_specification' => json_encode([
                    'header' => $header_spec,
                    'section' => $section_spec,
                    'footer' => $footer_spec,
                ]),
                'data_values' => json_encode([
                    'header' => $header_values,
                    'section' => $section_values,
                    'footer' => $footer_values,
                ])
            ];
        }

        // Halaman Profil
        $profilPages = [
            'sejarah',
            'visi-misi',
            'diversitas',
            'sambutan-ypcr',
            'sambutan-direktur',
            'organisasi',
            'panduan-identitas',
            'lokasi',
            'akreditasi',
            'prestasi-dan-penghargaan',
            'laporan-tahunan'
        ];

        foreach ($profilPages as $pageId) {
            $header_spec = [
                [
                    'name' => 'Header',
                    'type' => 'form',
                    'is_multiple' => false,
                    'data' => [
                        ['name' => 'title', 'label' => 'Judul', 'input_type' => 'text', 'multiple' => false],
                        ['name' => 'media_id', 'label' => 'Media', 'input_type' => 'file', 'allowed_file_types' => ['jpg', 'png'], 'multiple' => false]
                    ]
                ]
            ];
            $section_spec = [];
            $footer_spec = [];

            $header_values = [
                [
                    ['title' => [ucwords(str_replace('-', ' ', $pageId))], 'media_id' => ['']]
                ]
            ];
            $section_values = [];
            $footer_values = [];

            $content[] = [
                'page_name' => 'profil',
                'page_id' => $pageId,
                'data_specification' => json_encode([
                    'header' => $header_spec,
                    'section' => $section_spec,
                    'footer' => $footer_spec,
                ]),
                'data_values' => json_encode([
                    'header' => $header_values,
                    'section' => $section_values,
                    'footer' => $footer_values,
                ])
            ];
        }

        Konten::insert($content);
    }
}
