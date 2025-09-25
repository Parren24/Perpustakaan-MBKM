<?php

namespace App\Services\Academic;

use App\Models\Dimension\Prodi;
use App\Models\Konten\Konten;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProdiService
{
    /**
     * Get default hero slide (main slide)
     *
     * @return array
     */
    public static function getDefaultProdiContent($jurusanAlias): array
    {
        $content = [
            'TRK' => [
                'tentang program studi' => [
                    [
                        'deskripsi' => [
                            '
                            <p>Program Studi Teknologi Rekayasa Komputer (PSTRK) merupakan transformasi dari Program Studi D3 Teknik Komputer yang didirikan tahun 2001, Program Studi D4 Teknologi Rekayasa Komputer (PSTRK) mengambil tempat dalam arus perkembangan Industri 4.0 menjadi Society 5.0 di bidang kecerdasan buatan, big data, dan cyber security.</p>
                            <p>PSTRK membekali mahasiswanya dengan kemampuan di bidang Embedded System yang cerdas dan adaptif serta kemampuan Jaringan Komputer dan Cyber Security yang mendukung penerapan Big Data dan Internet of Things (IoT). Kurikulum yang disusun dengan melibatkan perwakilan IDUKA, alumni dan pakar kurikulum memastikan relevansi materi perkuliahan untuk mendukung mahasiswa menjadi dua profil lulusan: Engineer of Embedded System (ES), dan Engineer of Computer Network and Security System (CNSS).Mahasiswa juga diberi kesempatan untuk mengikuti berbagai program Merdeka Belajar Kampus Merdeka untuk mendapatkan kompetensi tambahan melalui kegiatan dan pengalaman belajar di program studi lain, institusi lain, dan IDUKA.</p>'
                        ],
                        'filemedia' => [publicMedia('trk.jpg', 'jurusan')],
                    ]
                ],
                'prospek karir' => [
                    [
                        'title' => ['Prospek Karir'],
                        'deskripsi' => [
                            'Lulusan Teknologi Rekayasa Komputer memiliki kompetensi dalam perancangan, implementasi, dan pengelolaan sistem berbasis komputer baik perangkat keras maupun perangkat lunak. Peluang karir terbuka di bidang industri teknologi, manufaktur, telekomunikasi, hingga start-up berbasis teknologi. Lulusan dapat berprofesi sebagai engineer, system designer, maupun technopreneur yang siap menghadapi tantangan era transformasi digital.'
                        ],
                    ],
                ],
                'daftar prospek karir' => [
                    [
                        'prospek_karir' => [
                            'Computer Network and Security System Engineer'
                        ],
                        'icon' => [
                            'ri-shield-keyhole-line'
                        ]
                    ],
                    [
                        'prospek_karir' => [
                            'Embedded System Engineer'
                        ],
                        'icon' => [
                            'ri-cpu-line'
                        ]
                    ],
                ],
                'sejarah' => [
                    [
                        'tahun' => [
                            '2001'
                        ],
                        'deskripsi' => [
                            'Merupakan salah satu dari 3 program studi yang pertama dibuka di Politeknik Caltex Riau'
                        ]
                    ],
                    [
                        'tahun' => [
                            '2022'
                        ],
                        'deskripsi' => [
                            'Pada Maret 2022 PCR mengumumkan bahwa beberapa prodi Ahli Madya (D3), termasuk D3 Teknik Komputer, diubah menjadi program D4 (Sarjana Terapan) — bernama Teknologi Rekayasa Komputer. Pengumuman resmi internal PCR dan pemberitaan media menyebut konversi ini dilakukan sebagai upaya peningkatan kualitas dan pemenuhan kebutuhan industri'
                        ]
                    ],
                    [
                        'tahun' => [
                            '2024'
                        ],
                        'deskripsi' => [
                            'Pada Agustus 2024, Program Studi Teknologi Rekayasa Komputer secara resmi mendapatkan akreditasi Unggul dari LAM Infokom'
                        ]
                    ],
                ],
                'visi' => [
                    [
                        'visi' => ['Diakui Sebagai Program Studi Unggul Yang Mampu Bersaing dalam Bidang Teknologi Komputer Pada Tingkat Nasional maupun ASEAN pada Tahun 2031']
                    ],
                ],
                'misi' => [
                    [
                        'misi' => [
                            'Menyelenggarakan sistem pendidikan vokasi di bidang Teknologi Komputer yang profesional, berkualitas, serta relevan dengan tantangan nasional ataupun ASEAN.'
                        ],
                        'icon' => [
                            'ri-lightbulb-line'
                        ]
                    ],
                    [
                        'misi' => [
                            'Menciptakan budaya akademik dan budaya organisasi yang nyaman, berkarakter dan bermartabat kepada mahasiswa.'
                        ],
                        'icon' => [
                            'ri-lightbulb-line'
                        ]
                    ],
                    [
                        'misi' => [
                            'Menghasilkan lulusan sarjana terapan yang memiliki keunggulan profesional dan terampil di bidang Teknologi Rekayasa Komputer, memiliki softskill yang baik, berpikiran terbuka, serta mampu bersaing dalam pasar nasional maupun ASEAN.'
                        ],
                        'icon' => [
                            'ri-lightbulb-line'
                        ]
                    ],
                    [
                        'misi' => [
                            'Menyelenggarakan dan mengembangkan penelitian terapan serta menyebarluaskan hasilnya untuk pengembangan inovasi ataupun menyelesaikan masalah-masalah di bidang Komputer sesuai dengan kebutuhan industri serta permasalahan nasional dan global.'
                        ],
                        'icon' => [
                            'ri-lightbulb-line'
                        ]
                    ],
                    [
                        'misi' => [
                            'Menyelenggarakan program pengabdian kepada masyarakat untuk menggali dan membantu pertumbuhan potensi masyarakat yang meliputi kebutuhan Sumber Daya Manusia, barang, ataupun jasa.'
                        ],
                        'icon' => [
                            'ri-lightbulb-line'
                        ]
                    ],
                    [
                        'misi' => [
                            'Ikut berperan aktif dalam asosiasi profesi yang menunjang pengembangan kegiatan akademik dan kelancaran hubungan dengan industri dan pemerintah.'
                        ],
                        'icon' => [
                            'ri-lightbulb-line'
                        ]
                    ]

                ],
            ],
            'SI' => [
                'tentang program studi' => [
                    [
                        'deskripsi' => [
                            '
                            <p>Program Studi Sistem Informasi  Diploma IV merupakan salah satu prodi di jurusan Komputer. Tujuan dari pendirian  program studi ini adalah untuk memenuhi permintaan pasar kerja akan tenaga kerja di bidang Teknologi Informasi dan Komputer yang semakin berkembang dengan pesat dan juga semakin berkembangnya kebutuhan tenaga pengajar untuk institusi pendidikan vokasi.</p>
                            <p>Prodi ini merupakan program sarjana sains terapan (D-IV) pertama se-Sumatera. Pada Program Studi Sistem Informasi ini, mahasiswa akan menguasai program aplikasi bisnis, pemanfaatan media informasi dalam bentuk sistem informasi , data warehouse, manajemen yang dibutuhkan perusahaan (Enterprise Resource Planning, Customer Relationship Management, dan Supply Chain Management.</p>'
                        ],
                        'filemedia' => [publicMedia('si.jpg', 'jurusan')],
                    ]
                ],
                'prospek karir' => [
                    [
                        'title' => ['Prospek Karir'],
                        'deskripsi' => [
                            'Lulusan Sistem Informasi memiliki peluang berkarir sebagai profesional yang mampu menjembatani kebutuhan bisnis dengan teknologi informasi. Kompetensi yang dikuasai mencakup analisis sistem, manajemen data, enterprise system, hingga pengembangan solusi digital. Lulusan siap berkontribusi di sektor perbankan, pemerintahan, manufaktur, start-up digital, maupun technopreneur di bidang sistem informasi.'
                        ],
                    ],
                ],
                'daftar prospek karir' => [
                    [
                        'prospek_karir' => [
                            'System Analyst & Software Developer'
                        ],
                        'icon' => [
                            'ri-terminal-box-line' // tersedia, pas untuk coding & development
                        ]
                    ],
                    [
                        'prospek_karir' => [
                            'IT Consultant & IT Auditor'
                        ],
                        'icon' => [
                            'ri-briefcase-line' // tersedia, melambangkan pekerjaan profesional
                        ]
                    ],
                    [
                        'prospek_karir' => [
                            'IT Project Manager'
                        ],
                        'icon' => [
                            'ri-task-line' // tersedia, cocok untuk manajemen tugas/proyek
                        ]
                    ],
                    [
                        'prospek_karir' => [
                            'IS Technopreneur'
                        ],
                        'icon' => [
                            'ri-lightbulb-line' // tersedia, ide & inovasi → technopreneurship
                        ]
                    ],
                    [
                        'prospek_karir' => [
                            'Data Analyst'
                        ],
                        'icon' => [
                            'ri-bar-chart-2-line' // tersedia, grafik → analisis data
                        ]
                    ],
                ],

                'sejarah' => [
                    [
                        'tahun' => [
                            '2007'
                        ],
                        'deskripsi' => [
                            'Program Studi Sistem Informasi berdiri pada tahun 2007 sebagai salah satu upaya PCR untuk menjawab kebutuhan industri akan tenaga profesional di bidang pengelolaan sistem informasi dan teknologi bisnis.'
                        ]
                    ],
                    [
                        'tahun' => [
                            '2015'
                        ],
                        'deskripsi' => [
                            'Pada tahun 2015, Prodi Sistem Informasi berhasil mendapatkan akreditasi “B” dari BAN-PT, yang menunjukkan komitmen dalam menjaga mutu pendidikan.'
                        ]
                    ],
                    [
                        'tahun' => [
                            '2021'
                        ],
                        'deskripsi' => [
                            'Pada 2021, Prodi Sistem Informasi mendapatkan akreditasi A dari BAN-PT, sebagai pengakuan atas kualitas pembelajaran dan kontribusinya dalam bidang sistem informasi.'
                        ]
                    ]
                ],

                'visi' => [
                    [
                        'visi' => ['Diakui Sebagai Program Studi Unggul dalam Bidang Teknologi Sistem Informasi pada Tingkat Nasional Maupun ASEAN pada Tahun 2031']
                    ],
                ],
                'misi' => [
                    [
                        'misi' => [
                            'Menyelenggarakan sistem pendidikan vokasi bidang teknologi sistem informasi yang relevan dengan kebutuhan dunia industri baik tingkat Nasional maupun ASEAN.'
                        ],
                        'icon' => [
                            'ri-lightbulb-line'
                        ]
                    ],
                    [
                        'misi' => [
                            'Menciptakan budaya akademik dan budaya organisasi yang berkarakter dan bermartabat.'
                        ],
                        'icon' => [
                            'ri-lightbulb-line'
                        ]
                    ],
                    [
                        'misi' => [
                            'Melaksanakan riset terapan di bidang teknologi sistem informasi dan mengimplementasikannya bagi masyarakat.'
                        ],
                        'icon' => [
                            'ri-lightbulb-line'
                        ]
                    ],
                    [
                        'misi' => [
                            'Melaksanakan pengabdian kepada masyarakat dengan menyebarluaskan ilmu pengetahuan, teknologi khususnya bidang teknologi sistem informasi industri, dan budaya organisasi.'
                        ],
                        'icon' => [
                            'ri-lightbulb-line'
                        ]
                    ]
                ],

            ],
            'TI' => [
                'tentang program studi' => [
                    [
                        'deskripsi' => [
                            '
                            <p>Program Studi Teknik Informatika Diploma IV (Sarjana Terapan) merupakan salah satu prodi unggulan di Politeknik Caltex Riau. Prodi ini dirancang untuk menghasilkan tenaga ahli yang kompeten di bidang rekayasa perangkat lunak, kecerdasan buatan, keamanan siber, dan teknologi berbasis data, sesuai dengan kebutuhan industri nasional maupun global.</p>
                            <p>Melalui kurikulum terapan, mahasiswa dibekali kemampuan analisis, perancangan, implementasi, dan pengelolaan sistem berbasis komputer yang inovatif. Lulusan Teknik Informatika PCR diharapkan mampu bersaing di tingkat ASEAN dengan kompetensi teknis dan softskill yang kuat.</p>
                        '
                        ],
                        'filemedia' => [publicMedia('ti.jpg', 'jurusan')],
                    ]
                ],

                'prospek karir' => [
                    [
                        'title' => ['Prospek Karir'],
                        'deskripsi' => [
                            'Lulusan Program Studi Teknik Informatika memiliki peluang karir yang luas di berbagai sektor industri, mulai dari perusahaan teknologi, perbankan, manufaktur, hingga start-up digital. Dengan penguasaan rekayasa perangkat lunak, keamanan jaringan, data science, serta kecerdasan buatan, lulusan siap bekerja sebagai profesional maupun technopreneur.'
                        ],
                    ],
                ],
                'daftar prospek karir' => [
                    [
                        'prospek_karir' => ['Software Developer'],
                        'icon' => ['ri-terminal-box-line'] // tersedia, pas untuk coding & development
                    ],
                    [
                        'prospek_karir' => ['Multimedia Developer'],
                        'icon' => ['ri-movie-line'] // tersedia, representasi multimedia (video/media)
                    ],
                    [
                        'prospek_karir' => ['IT Infrastructure Engineering'],
                        'icon' => ['ri-router-line'] // tersedia, melambangkan jaringan & infrastruktur IT
                    ],
                ],


                'sejarah' => [
                    [
                        'tahun' => ['2001'],
                        'deskripsi' => [
                            'Program Studi Teknik Informatika merupakan salah satu prodi pertama yang didirikan di Politeknik Caltex Riau pada tahun 2001.'
                        ]
                    ],
                    [
                        'tahun' => ['2015'],
                        'deskripsi' => [
                            'Pada tahun 2015, Prodi Teknik Informatika berhasil memperoleh akreditasi “B” dari BAN-PT.'
                        ]
                    ],
                    [
                        'tahun' => ['2021'],
                        'deskripsi' => [
                            'Pada tahun 2021, Prodi Teknik Informatika mendapatkan akreditasi “Unggul” dari LAM Infokom, menegaskan kualitas pendidikan yang diberikan.'
                        ]
                    ],
                ],

                'visi' => [
                    [
                        'visi' => ['Menjadi Program Studi Teknik Informatika yang unggul di bidang rekayasa perangkat lunak, kecerdasan buatan, dan keamanan siber pada tingkat Nasional dan ASEAN pada tahun 2031.']
                    ],
                ],

                'misi' => [
                    [
                        'misi' => [
                            'Menyelenggarakan pendidikan vokasi bidang Teknik Informatika yang sesuai dengan perkembangan ilmu pengetahuan, teknologi, serta kebutuhan industri nasional maupun ASEAN.'
                        ],
                        'icon' => ['ri-lightbulb-line']
                    ],
                    [
                        'misi' => [
                            'Menciptakan budaya akademik yang berkarakter, inovatif, dan mendukung pengembangan teknologi informatika.'
                        ],
                        'icon' => ['ri-lightbulb-line']
                    ],
                    [
                        'misi' => [
                            'Melaksanakan penelitian terapan di bidang rekayasa perangkat lunak, kecerdasan buatan, dan keamanan siber yang dapat memberikan solusi nyata bagi industri dan masyarakat.'
                        ],
                        'icon' => ['ri-lightbulb-line']
                    ],
                    [
                        'misi' => [
                            'Melaksanakan pengabdian kepada masyarakat melalui penerapan teknologi informatika untuk meningkatkan kualitas hidup dan mendukung transformasi digital.'
                        ],
                        'icon' => ['ri-lightbulb-line']
                    ],
                ],
            ],
            'MTTK' => [
                'tentang program studi' => [
                    [
                        'deskripsi' => [
                            '
                            <p>Program Studi Magister Terapan Teknik Komputer (MTTK) merupakan jenjang pendidikan pascasarjana di Politeknik Caltex Riau yang dirancang untuk menghasilkan lulusan dengan keahlian tingkat lanjut di bidang teknologi komputer. Program ini fokus pada penerapan teknologi rekayasa komputer, kecerdasan buatan, keamanan siber, jaringan, serta sistem terdistribusi.</p>
                            <p>MTTK menekankan pada riset terapan, inovasi teknologi, serta pengembangan solusi yang berorientasi pada kebutuhan industri dan masyarakat. Lulusan diharapkan mampu menjadi problem solver, peneliti terapan, maupun technopreneur di bidang teknik komputer.</p>
                        '
                        ],
                        'filemedia' => [publicMedia( 'mttk.jpg', 'jurusan')],
                    ]
                ],

                'prospek karir' => [
                    [
                        'title' => ['Prospek Karir'],
                        'deskripsi' => [
                            'Lulusan Magister Terapan Teknik Komputer memiliki peluang berkarir sebagai ahli dan pemimpin di bidang teknologi komputer tingkat lanjut, baik di industri, lembaga riset, maupun pendidikan tinggi. Kompetensi yang dimiliki mencakup riset terapan, pengembangan solusi berbasis teknologi, serta kepemimpinan dalam transformasi digital.'
                        ],
                    ],
                ],
                'daftar prospek karir' => [
                    [
                        'prospek_karir' => ['Research & Development Engineer'],
                        'icon' => ['ri-flask-line'] // penelitian & eksperimen
                    ],
                    [
                        'prospek_karir' => ['IT Architect & System Designer'],
                        'icon' => ['ri-macbook-line'] // representasi desain sistem / arsitektur IT
                    ],
                    [
                        'prospek_karir' => ['Cyber Security Specialist'],
                        'icon' => ['ri-shield-keyhole-line'] // keamanan & proteksi data
                    ],
                    [
                        'prospek_karir' => ['Data Scientist & AI Engineer'],
                        'icon' => ['ri-brain-line'] // AI & data intelligence
                    ],
                    [
                        'prospek_karir' => ['Academic Lecturer & Researcher'],
                        'icon' => ['ri-book-2-line'] // akademisi & penelitian
                    ],
                ],


                'sejarah' => [
                    [
                        'tahun' => ['2023'],
                        'deskripsi' => [
                            'Program Studi Magister Terapan Teknik Komputer resmi dibuka pada tahun 2023 sebagai program pascasarjana pertama di Politeknik Caltex Riau.'
                        ]
                    ],
                    [
                        'tahun' => ['2024'],
                        'deskripsi' => [
                            'Pada tahun 2024, MTTK mulai menerima angkatan mahasiswa pertama dengan fokus riset terapan di bidang rekayasa komputer.'
                        ]
                    ],
                ],

                'visi' => [
                    [
                        'visi' => ['Menjadi Program Studi Magister Terapan Teknik Komputer yang unggul dalam riset terapan dan inovasi teknologi di tingkat Nasional maupun ASEAN pada tahun 2035.']
                    ],
                ],

                'misi' => [
                    [
                        'misi' => [
                            'Menyelenggarakan pendidikan magister terapan di bidang teknik komputer yang profesional, berkualitas, dan relevan dengan kebutuhan industri serta masyarakat.'
                        ],
                        'icon' => ['ri-lightbulb-line']
                    ],
                    [
                        'misi' => [
                            'Mengembangkan penelitian terapan dan inovasi teknologi untuk mendukung transformasi digital serta kemajuan ilmu pengetahuan.'
                        ],
                        'icon' => ['ri-lightbulb-line']
                    ],
                    [
                        'misi' => [
                            'Menyelenggarakan pengabdian kepada masyarakat melalui penerapan teknologi komputer yang memberikan solusi nyata bagi berbagai sektor.'
                        ],
                        'icon' => ['ri-lightbulb-line']
                    ],
                    [
                        'misi' => [
                            'Membangun jejaring kerjasama dengan industri, pemerintah, dan institusi pendidikan tinggi baik nasional maupun internasional.'
                        ],
                        'icon' => ['ri-lightbulb-line']
                    ],
                ],
            ],
            'TET' => [
                'tentang program studi' => [
                    [
                        'deskripsi' => [
                            '
                    <p>Program Studi Teknik Elektronika membekali mahasiswa dengan kemampuan dalam perancangan, implementasi, dan pengelolaan sistem elektronika modern. Fokus pembelajaran meliputi sistem kendali, instrumentasi, serta perangkat elektronika industri dan konsumen.</p>
                '
                        ],
                        'filemedia' => [publicMedia( 'tet.jpg', 'jurusan')],
                    ]
                ],
                'prospek karir' => [
                    [
                        'title' => ['Prospek Karir'],
                        'deskripsi' => [
                            'Lulusan Teknik Elektronika berpeluang bekerja di industri manufaktur, energi, otomasi, hingga telekomunikasi dengan keahlian dalam perancangan dan pemeliharaan sistem elektronika.'
                        ],
                    ],
                ],
                'daftar prospek karir' => [
                    [
                        'prospek_karir' => ['Electronics Engineer'],
                        'icon' => ['ri-cpu-line']
                    ],
                    [
                        'prospek_karir' => ['Control & Instrumentation Engineer'],
                        'icon' => ['ri-settings-3-line']
                    ],
                    [
                        'prospek_karir' => ['Maintenance Engineer'],
                        'icon' => ['ri-tools-line']
                    ],
                ],
                'sejarah' => [
                ],
                'visi' => [
                    [
                        'visi' => ['Menjadi program studi unggul dalam bidang elektronika industri dan sistem kendali di tingkat nasional pada tahun 2031.']
                    ],
                ],
                'misi' => [
                    [
                        'misi' => ['Menyelenggarakan pendidikan vokasi yang berkualitas di bidang teknik elektronika.'],
                        'icon' => ['ri-lightbulb-line']
                    ],
                    [
                        'misi' => ['Mengembangkan penelitian terapan di bidang sistem kendali dan instrumentasi.'],
                        'icon' => ['ri-lightbulb-line']
                    ],
                    [
                        'misi' => ['Menjalin kerjasama dengan industri dalam bidang teknologi elektronika.'],
                        'icon' => ['ri-lightbulb-line']
                    ],
                ],
            ],
            'TL' => [
                'tentang program studi' => [
                    [
                        'deskripsi' => [
                            '
                    <p>Program Studi Teknik Listrik membekali mahasiswa dengan kompetensi di bidang sistem tenaga listrik, instalasi, distribusi, dan pemanfaatan energi listrik untuk berbagai kebutuhan industri dan masyarakat.</p>
                '
                        ],
                        'filemedia' => [publicMedia( 'tl.jpg', 'jurusan')],
                    ]
                ],
                'prospek karir' => [
                    [
                        'title' => ['Prospek Karir'],
                        'deskripsi' => [
                            'Lulusan Teknik Listrik dapat bekerja sebagai tenaga ahli di bidang sistem tenaga listrik, otomasi, pembangkitan, hingga energi terbarukan.'
                        ],
                    ],
                ],
                'daftar prospek karir' => [
                    [
                        'prospek_karir' => ['Electrical Engineer'],
                        'icon' => ['ri-flashlight-line']
                    ],
                    [
                        'prospek_karir' => ['Power System Engineer'],
                        'icon' => ['ri-battery-2-charge-line']
                    ],
                    [
                        'prospek_karir' => ['Renewable Energy Specialist'],
                        'icon' => ['ri-sun-line']
                    ],
                ],
                'sejarah' => [
                    [
                        'tahun' => ['2015'],
                        'deskripsi' => ['Peroleh izin operasional program studi Teknik Listrik.']
                    ],
                    [
                        'tahun' => ['2016'],
                        'deskripsi' => ['Kegiatan akademik program studi Teknik Listrik dimulai.']
                    ],
                ],
                'visi' => [
                    [
                        'visi' => ['Menjadi program studi unggul dalam bidang ketenagalistrikan dan energi terbarukan di tingkat nasional pada tahun 2031.']
                    ],
                ],
                'misi' => [
                    [
                        'misi' => ['Menyelenggarakan pendidikan vokasi profesional di bidang ketenagalistrikan.'],
                        'icon' => ['ri-lightbulb-line']
                    ],
                    [
                        'misi' => ['Mengembangkan penelitian terapan di bidang energi listrik dan energi terbarukan.'],
                        'icon' => ['ri-lightbulb-line']
                    ],
                    [
                        'misi' => ['Menjalin kerjasama dengan industri energi dan kelistrikan.'],
                        'icon' => ['ri-lightbulb-line']
                    ],
                ],
            ],
            'MS' => [
                'tentang program studi' => [
                    [
                        'deskripsi' => [
                            '
                    <p>Program Studi Teknik Mesin berfokus pada perancangan, manufaktur, dan pemeliharaan mesin serta sistem mekanis. Mahasiswa dibekali kemampuan praktis dalam desain, produksi, dan otomasi industri.</p>
                '
                        ],
                        'filemedia' => [publicMedia( 'tms.jpg', 'jurusan')],
                    ]
                ],
                'prospek karir' => [
                    [
                        'title' => ['Prospek Karir'],
                        'deskripsi' => [
                            'Lulusan Teknik Mesin berpeluang bekerja di industri manufaktur, energi, otomotif, dan perancangan produk dengan kompetensi mekanik dan rekayasa.'
                        ],
                    ],
                ],
                'daftar prospek karir' => [
                    [
                        'prospek_karir' => ['Mechanical Engineer'],
                        'icon' => ['ri-tools-line']
                    ],
                    [
                        'prospek_karir' => ['Design Engineer'],
                        'icon' => ['ri-draft-line']
                    ],
                    [
                        'prospek_karir' => ['Maintenance & Production Engineer'],
                        'icon' => ['ri-building-4-line']
                    ],
                ],
                'sejarah' => [
                    [
                        'tahun' => ['2015'],
                        'deskripsi' => ['Izin operasional teknis diperoleh pada tahun 2015.']
                    ],
                    [
                        'tahun' => ['2016'],
                        'deskripsi' => ['Memulai kegiatan akademik pada tahun 2016.']
                    ],
                ],
                'visi' => [
                    [
                        'visi' => ['Menjadi program studi unggul di bidang teknik mesin terapan yang mampu bersaing di tingkat nasional dan ASEAN pada tahun 2031.']
                    ],
                ],
                'misi' => [
                    [
                        'misi' => ['Menyelenggarakan pendidikan vokasi unggul dalam bidang teknik mesin.'],
                        'icon' => ['ri-lightbulb-line']
                    ],
                    [
                        'misi' => ['Mengembangkan riset terapan dalam bidang manufaktur dan otomasi.'],
                        'icon' => ['ri-lightbulb-line']
                    ],
                    [
                        'misi' => ['Menjalin kerjasama dengan industri manufaktur dan energi.'],
                        'icon' => ['ri-lightbulb-line']
                    ],
                ],
            ],
            'TRJT' => [
                'tentang program studi' => [
                    [
                        'deskripsi' => [
                            '
                    <p>Program Studi Teknologi Rekayasa Jaringan Telekomunikasi mempersiapkan mahasiswa dengan keahlian dalam perancangan, implementasi, dan pengelolaan infrastruktur jaringan telekomunikasi modern, termasuk fiber optic, wireless, dan jaringan data.</p>
                '
                        ],
                        'filemedia' => [publicMedia( 'trjt.jpg', 'jurusan')],
                    ]
                ],
                'prospek karir' => [
                    [
                        'title' => ['Prospek Karir'],
                        'deskripsi' => [
                            'Lulusan TRJT memiliki peluang karir di bidang telekomunikasi, jaringan internet, operator seluler, serta penyedia layanan digital.'
                        ],
                    ],
                ],
                'daftar prospek karir' => [
                    [
                        'prospek_karir' => ['Network Engineer'],
                        'icon' => ['ri-router-line']
                    ],
                    [
                        'prospek_karir' => ['Telecommunication Engineer'],
                        'icon' => ['ri-router-line']
                    ],
                    [
                        'prospek_karir' => ['Wireless System Specialist'],
                        'icon' => ['ri-wifi-line']
                    ],
                ],
                'sejarah' => [
                ],
                'visi' => [
                    [
                        'visi' => ['Menjadi program studi unggul dalam bidang jaringan dan telekomunikasi modern di tingkat nasional pada tahun 2031.']
                    ],
                ],
                'misi' => [
                    [
                        'misi' => ['Menyelenggarakan pendidikan vokasi yang berkualitas di bidang jaringan telekomunikasi.'],
                        'icon' => ['ri-lightbulb-line']
                    ],
                    [
                        'misi' => ['Mengembangkan penelitian terapan dalam bidang teknologi jaringan.'],
                        'icon' => ['ri-lightbulb-line']
                    ],
                    [
                        'misi' => ['Menjalin kerjasama dengan industri telekomunikasi nasional dan global.'],
                        'icon' => ['ri-lightbulb-line']
                    ],
                ],
            ],
            'AKTP' => [
                'tentang program studi' => [
                    [
                        'deskripsi' => [
                            '
                    <p>Program Studi Akuntansi Perpajakan dirancang untuk menghasilkan tenaga ahli dalam bidang akuntansi, audit, dan perpajakan dengan kemampuan analisis dan penerapan regulasi keuangan secara profesional.</p>
                '
                        ],
                        'filemedia' => [publicMedia( 'aktp.jpg', 'jurusan')],
                    ]
                ],
                'prospek karir' => [
                    [
                        'title' => ['Prospek Karir'],
                        'deskripsi' => [
                            'Lulusan AKTP berpeluang bekerja sebagai akuntan, auditor, konsultan pajak, maupun analis keuangan di berbagai sektor.'
                        ],
                    ],
                ],
                'daftar prospek karir' => [
                    [
                        'prospek_karir' => ['Accountant'],
                        'icon' => ['ri-bank-line']
                    ],
                    [
                        'prospek_karir' => ['Tax Consultant'],
                        'icon' => ['ri-money-dollar-circle-line']
                    ],
                    [
                        'prospek_karir' => ['Auditor'],
                        'icon' => ['ri-file-list-3-line']
                    ],
                ],
                'sejarah' => [
                    [
                        'tahun' => ['2003'],
                        'deskripsi' => ['Prodi D3 Akuntansi (sekarang AKTP) dibuka pada tahun 2003.']
                    ],
                ],
                'visi' => [
                    [
                        'visi' => ['Diakui sebagai program studi unggulan di bidang akuntansi perpajakan yang dapat bersaing secara ASEAN Tahun 2031']
                    ],
                ],
                'misi' => [
                    [
                        'misi' => ['Menyelenggarakan pendidikan vokasi profesional di bidang akuntansi dan perpajakan.'],
                        'icon' => ['ri-lightbulb-line']
                    ],
                    [
                        'misi' => ['Mengembangkan riset terapan di bidang akuntansi dan perpajakan.'],
                        'icon' => ['ri-lightbulb-line']
                    ],
                    [
                        'misi' => ['Menjalin kerjasama dengan dunia usaha dan dunia industri terkait akuntansi dan perpajakan.'],
                        'icon' => ['ri-lightbulb-line']
                    ],
                ],
            ],
            'TRSE' => [
                'tentang program studi' => [
                    [
                        'deskripsi' => [
                            '
                    <p>Program Studi Teknologi Rekayasa Sistem Elektronika berfokus pada perancangan, pengembangan, dan penerapan sistem elektronika cerdas yang mendukung industri otomasi, IoT, dan teknologi digital.</p>
                '
                        ],
                        'filemedia' => [publicMedia( 'trse.jpg', 'jurusan')],
                    ]
                ],
                'prospek karir' => [
                    [
                        'title' => ['Prospek Karir'],
                        'deskripsi' => [
                            'Lulusan TRSE dapat bekerja di industri otomasi, IoT, manufaktur, hingga bidang penelitian dan pengembangan elektronika cerdas.'
                        ],
                    ],
                ],
                'daftar prospek karir' => [
                    [
                        'prospek_karir' => ['IoT Engineer'],
                        'icon' => ['ri-wireless-charging-line']
                    ],
                    [
                        'prospek_karir' => ['Automation Engineer'],
                        'icon' => ['ri-robot-line']
                    ],
                    [
                        'prospek_karir' => ['Embedded System Developer'],
                        'icon' => ['ri-robot-line']
                    ],
                ],
                'sejarah' => [
                    [
                        'tahun' => ['2001'],
                        'deskripsi' => ['Berdiri sebagai DIII Teknik Elektronika sejak berdirinya Politeknik Caltex Riau.']
                    ],
                    [
                        'tahun' => ['2022'],
                        'deskripsi' => ['Tertransformasi menjadi D4 Teknologi Rekayasa Sistem Elektronika.']
                    ],
                ],
                'visi' => [
                    [
                        'visi' => ['Menjadi program studi unggul dalam bidang sistem elektronika cerdas dan otomasi di tingkat nasional pada tahun 2031.']
                    ],
                ],
                'misi' => [
                    [
                        'misi' => ['Menyelenggarakan pendidikan vokasi unggul dalam bidang sistem elektronika.'],
                        'icon' => ['ri-lightbulb-line']
                    ],
                    [
                        'misi' => ['Mengembangkan penelitian di bidang IoT dan otomasi industri.'],
                        'icon' => ['ri-lightbulb-line']
                    ],
                    [
                        'misi' => ['Menjalin kerjasama dengan industri teknologi dan manufaktur.'],
                        'icon' => ['ri-lightbulb-line']
                    ],
                ],
            ],
            'TRM' => [
                'tentang program studi' => [
                    [
                        'deskripsi' => [
                            '
                    <p>Program Studi Teknologi Rekayasa Mekatronika mengintegrasikan bidang mekanik, elektronika, dan sistem kendali untuk membekali mahasiswa dalam merancang dan mengembangkan sistem otomasi modern.</p>
                '
                        ],
                        'filemedia' => [publicMedia( 'trm.jpg', 'jurusan')],
                    ]
                ],
                'prospek karir' => [
                    [
                        'title' => ['Prospek Karir'],
                        'deskripsi' => [
                            'Lulusan TRM berpeluang bekerja sebagai engineer dalam bidang otomasi, manufaktur, robotika, dan sistem mekatronika terapan.'
                        ],
                    ],
                ],
                'daftar prospek karir' => [
                    [
                        'prospek_karir' => ['Mechatronics Engineer'],
                        'icon' => ['ri-robot-line']
                    ],
                    [
                        'prospek_karir' => ['Automation System Designer'],
                        'icon' => ['ri-settings-5-line']
                    ],
                    [
                        'prospek_karir' => ['Robotics Engineer'],
                        'icon' => ['ri-magic-line']
                    ],
                ],
                'sejarah' => [
                    [
                        'tahun' => ['2003'],
                        'deskripsi' => ['Prodi D3 Mekatronika dibuka pada tahun 2003.']
                    ],
                ],
                'visi' => [
                    [
                        'visi' => ['Menjadi program studi unggul di bidang teknologi mekatronika terapan di tingkat nasional pada tahun 2031.']
                    ],
                ],
                'misi' => [
                    [
                        'misi' => ['Menyelenggarakan pendidikan vokasi berkualitas di bidang mekatronika.'],
                        'icon' => ['ri-lightbulb-line']
                    ],
                    [
                        'misi' => ['Mengembangkan penelitian terapan dalam bidang robotika dan otomasi.'],
                        'icon' => ['ri-lightbulb-line']
                    ],
                    [
                        'misi' => ['Menjalin kerjasama dengan industri otomasi dan manufaktur.'],
                        'icon' => ['ri-lightbulb-line']
                    ],
                ],
            ],
            'BD' => [
                'tentang program studi' => [
                    [
                        'deskripsi' => [
                            '
                    <p>Program Studi Bisnis Digital membekali mahasiswa dengan keterampilan di bidang manajemen bisnis berbasis teknologi digital, pemasaran online, e-commerce, dan analisis data bisnis.</p>
                '
                        ],
                        'filemedia' => [publicMedia( 'bd.jpg', 'jurusan')],
                    ]
                ],
                'prospek karir' => [
                    [
                        'title' => ['Prospek Karir'],
                        'deskripsi' => [
                            'Lulusan BD berpeluang bekerja di bidang startup digital, e-commerce, digital marketing, dan manajemen data bisnis.'
                        ],
                    ],
                ],
                'daftar prospek karir' => [
                    [
                        'prospek_karir' => ['Digital Marketing Specialist'],
                        'icon' => ['ri-megaphone-line']
                    ],
                    [
                        'prospek_karir' => ['Business Analyst'],
                        'icon' => ['ri-bar-chart-grouped-line']
                    ],
                    [
                        'prospek_karir' => ['E-Commerce Manager'],
                        'icon' => ['ri-shopping-cart-2-line']
                    ],
                ],
                'sejarah' => [],
                'visi' => [
                    [
                        'visi' => ['Menjadi program studi unggul dalam bidang bisnis digital dan kewirausahaan berbasis teknologi di tingkat nasional pada tahun 2031.']
                    ],
                ],
                'misi' => [
                    [
                        'misi' => ['Menyelenggarakan pendidikan vokasi profesional di bidang bisnis digital.'],
                        'icon' => ['ri-lightbulb-line']
                    ],
                    [
                        'misi' => ['Mengembangkan penelitian terapan dalam transformasi digital dan kewirausahaan.'],
                        'icon' => ['ri-lightbulb-line']
                    ],
                    [
                        'misi' => ['Menjalin kerjasama dengan industri bisnis digital dan startup.'],
                        'icon' => ['ri-lightbulb-line']
                    ],
                ],
            ],
            'HMKD' => [
                'tentang program studi' => [
                    [
                        'deskripsi' => [
                            '
                    <p>Program Studi Hubungan Masyarakat dan Komunikasi Digital menyiapkan mahasiswa dengan keterampilan komunikasi publik, manajemen reputasi, serta strategi komunikasi digital di era informasi.</p>
                '
                        ],
                        'filemedia' => [publicMedia( 'hmkd.jpg', 'jurusan')],
                    ]
                ],
                'prospek karir' => [
                    [
                        'title' => ['Prospek Karir'],
                        'deskripsi' => [
                            'Lulusan HMKD memiliki prospek kerja di bidang PR, manajemen komunikasi, media digital, hingga kehumasan korporasi dan pemerintahan.'
                        ],
                    ],
                ],
                'daftar prospek karir' => [
                    [
                        'prospek_karir' => ['Public Relations Specialist'],
                        'icon' => ['ri-chat-3-line']
                    ],
                    [
                        'prospek_karir' => ['Content Creator & Media Specialist'],
                        'icon' => ['ri-quill-pen-line']
                    ],
                    [
                        'prospek_karir' => ['Corporate Communication Officer'],
                        'icon' => ['ri-building-2-line']
                    ],
                ],
                'sejarah' => [],
                'visi' => [
                    [
                        'visi' => ['Menjadi program studi unggul dalam bidang hubungan masyarakat dan komunikasi digital di tingkat nasional pada tahun 2031.']
                    ],
                ],
                'misi' => [
                    [
                        'misi' => ['Menyelenggarakan pendidikan vokasi berkualitas di bidang komunikasi digital dan PR.'],
                        'icon' => ['ri-lightbulb-line']
                    ],
                    [
                        'misi' => ['Mengembangkan riset terapan dalam komunikasi publik dan digital.'],
                        'icon' => ['ri-lightbulb-line']
                    ],
                    [
                        'misi' => ['Menjalin kerjasama dengan industri media, PR, dan lembaga komunikasi.'],
                        'icon' => ['ri-lightbulb-line']
                    ],
                ],
            ],
        ];

        return array_key_exists($jurusanAlias, $content) ? $content[$jurusanAlias] : null;
    }

    public static function getKonten($alias)
    {
        // $sectionValues = Konten::getSectionValues('prodi_page', Str::upper($alias));
        $sectionValues = json_decode(json_encode(self::getDefaultProdiContent(Str::upper($alias))));
        // dd($sectionValues);
        if (!$sectionValues) {
            return null;
        }

        $konten = new \stdClass();

        $konten = kontenMapping($konten, $sectionValues, [
            'tentang program studi' => 'tentang',
            'prospek karir' => 'prospek',
            'visi' => 'visi',
        ]);

        $konten = kontenMappingMany($konten, $sectionValues, [
            'daftar prospek karir' => 'daftar_prospek',
            'sejarah' => 'sejarah',
            'misi' => 'misi',
        ]);

        // d($konten)

        return $konten;
    }

    public static function getProdi($alias)
    {
        return Prodi::with('jurusan')->where('alias_prodi', Str::upper($alias))->first();
    }
}
