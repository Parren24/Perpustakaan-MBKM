<?php

namespace App\Services\Academic;

use App\Models\Dimension\DmPegawai;
use App\Models\Dimension\Jurusan;
use App\Models\Dimension\Prodi;
use App\Models\Konten\Konten;
use App\Services\Frontend\SiteIdentityService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class JurusanService
{

    /**
     * Return artikel content for the frontend Artikel page.
     * In future this can fetch from DB or CMS.
     *
     * @return array|object
     */
    public static function getContent($jurusanAlias = null)
    {
        return $jurusanAlias ? self::getShowContent($jurusanAlias) : self::getIndexContent();
    }

    public static function getIndexContent()
    {
        $jurusan     = [];
        $jurusanList = Jurusan::inRandomOrder()->get();
        foreach ($jurusanList as $index => $data) {
            $jurusan[] = [
                'title'       => $data->nama_jurusan,
                'description' => $data->deskripsi_jurusan,
                'url'         => route('frontend.academic.jurusan.show', ['jurusanAlias' => Str::lower($data->alias_jurusan)]),
                'images'      => [
                    'src' => publicMedia($data->filename_jurusan, 'jurusan'),
                    'alt' => 'Cover ' . $data->nama_jurusan
                ]
            ];
        }

        return [
            'header'   => 'Jurusan',
            'title'    => 'Temukan <b>Jurusan</b> Tepat untuk Masa Depanmu',
            'subtitle' => 'Jurusan',
            'url'      => '#',
            'jurusan'  => $jurusan
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
            'title'       => 'Jurusan Politeknik Caltex Riau',
            'description' => 'Temukan informasi lengkap tentang jurusan dan program studi di Politeknik Caltex Riau. Dari Teknologi Informasi, Bisnis, hingga Teknik Industri, PCR menyiapkan mahasiswa menjadi profesional kompeten di tingkat nasional dan global.',
            'keywords'    => 'Jurusan Politeknik Caltex Riau, Program Studi PCR, Kuliah Riau, Jurusan Teknologi Informasi PCR, Jurusan Bisnis dan Komunikasi PCR, Jurusan Teknologi Industri PCR, Politeknik di Pekanbaru'
        ];
    }

    public static function getShowContent($jurusanAlias)
    {
        try {
            // $jurusanValues = Konten::getSectionValues('jurusan_page', strtoupper($jurusanAlias));

            $jurusanValues = json_decode(json_encode(self::getDefaultJurusanContent(Str::lower($jurusanAlias))));

            $sambutanValues = $jurusanValues->{"sambutan ketua jurusan"};

            $kontenSambutan = (object) [
                'title'            => $sambutanValues->title ?? null,
                'sambutan'         => $sambutanValues->sambutan ?? null,
                'pemberi_sambutan' => $sambutanValues->pemberi_sambutan ?? null,
                'jabatan_sambutan' => $sambutanValues->jabatan_sambutan ?? null,
                'foto_sambutan'    => $sambutanValues->foto_sambutan ?? null,
            ];

            $tentangValues = $jurusanValues->{"tentang jurusan"};
            $kontenTentang = (object) [
                'title'            => $tentangValues->title ?? null,
                'deskripsi'        => $tentangValues->deskripsi ?? null,
                'cover'            => $tentangValues->cover ?? null,
                'jumlah_mahasiswa' => $tentangValues->jumlah_mahasiswa ?? null,
            ];

            $jurusanValues = $jurusanValues->{"jurusan"};
            $kontenJurusan = (object) [
                'title'     => $jurusanValues->title ?? null,
                'deskripsi' => $jurusanValues->deskripsi ?? null,
            ];

            return (object) [
                'sambutan'      => $kontenSambutan,
                'tentang'       => $kontenTentang,
                'prodi_jurusan' => $kontenJurusan,
                'jurusan'       => $jurusanValues,
            ];
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get default hero slide (main slide)
     *
     * @return array
     */
    public static function getDefaultJurusanContent($jurusanAlias): array
    {
        $content = [
            'jbk'  => [
                'sambutan ketua jurusan' => [
                    'title'            => 'Selamat datang di Jurusan Bisnis dan Komunikasi Politeknik Caltex Riau',
                    'sambutan'         => '
                        Jurusan Bisnis dan Komunikasi Politeknik Caltex Riau hadir sebagai pusat pengembangan talenta muda yang profesional, kreatif, dan kompetitif di era digital. Jurusan ini membina tiga program studi unggulan: Akuntansi Perpajakan, Bisnis Digital, dan Hubungan Masyarakat & Komunikasi Digital.
                        Dengan fasilitas modern, kurikulum berbasis industri, serta kolaborasi erat bersama perusahaan nasional, multinasional, dan praktisi profesional, kami berkomitmen mencetak lulusan unggul, adaptif, dan siap bersaing di pasar global. Nilai-nilai Integrity, Dignity, Excellence, Agility, dan Loyalty menjadi landasan utama kami dalam mendidik mahasiswa agar tidak hanya cerdas secara akademik, tetapi juga berkarakter kuat.
                    ',
                    'pemberi_sambutan' => 'Meliza Putriyanti Zifi, S.E., M.Acc.',
                    'jabatan_sambutan' => 'Ketua Jurusan Bisnis dan Komunikasi',
                    'foto_sambutan'    => publicMedia('MPZ.png', 'jurusan/dosen')
                ],
                'tentang jurusan'        => [
                    'title'            => 'Bisnis dan Komunikasi untuk Masa Depan Global',
                    'deskripsi'        => '
                        Lulusan Jurusan Bisnis dan Komunikasi dibekali kompetensi yang menyeluruh di bidang akuntansi, perpajakan, bisnis digital, serta hubungan masyarakat dan komunikasi. Mereka menguasai akuntansi keuangan, perpajakan, auditing, serta akuntansi khusus seperti perkebunan dan migas; terampil dalam manajemen public relations digital, media relations, public speaking, CSR, hingga produksi konten kreatif dan penyiaran digital; serta inovatif dalam pemasaran digital, analitis dalam pengolahan data bisnis, dan strategis dalam merespons peluang maupun risiko usaha. Dengan kombinasi keahlian tersebut, lulusan siap menjadi profesional adaptif, kreatif, dan kompetitif di berbagai sektor industri modern.
                    ',
                    'cover'            => publicMedia('jbk.jpg', 'jurusan'),
                    'jumlah_mahasiswa' => '450+',
                ],
                'jurusan'                => [
                    'title'     => 'Program Studi',
                    'deskripsi' => 'Lorem ipsum dolor sit, amet consectetur adipisicing elit. Ullam, ut illum, vitae ea suscipit praesentium quas alias ad sunt sint dolores necessitatibus, modi incidunt inventore culpa asperiores tenetur. Molestiae mollitia commodi, debitis alias tenetur esse architecto. Laborum quisquam molestiae amet incidunt. Labore, deleniti quia placeat a ullam ipsa unde quod possimus quam ducimus beatae nemo, nisi corporis minima veritatis! Cumque non ut obcaecati quaerat velit culpa voluptatum beatae rerum sit perferendis delectus, pariatur et amet explicabo modi hic qui, esse eveniet a minus. In a et maxime illo nesciunt accusantium facere totam architecto quae, pariatur dolorem natus odio consectetur deleniti?',
                ]
            ],
            'jti'  => [
                'sambutan ketua jurusan' => [
                    'title'            => 'Selamat datang di Jurusan Teknologi Informasi Politeknik Caltex Riau',
                    'sambutan'         => '
                    Jurusan ini membina 4 program studi unggulan di bidang teknologi, yaitu Teknik Informatika, Sistem Informasi, Teknologi Rekayasa Komputer, dan S2 Terapan Teknik Komputer. Dengan semangat nilai-nilai Integrity, Dignity, Excellence, Agility, dan Loyalty, kami berkomitmen mencetak lulusan unggul, inovatif, dan adaptif menghadapi perkembangan dunia digital.
                    Alumni Jurusan Teknologi Informasi telah tersebar di berbagai perusahaan nasional, multinasional, bahkan di berbagai negara, menjadi bukti kualitas lulusan kami yang mampu bersaing di tingkat global.
                    Didukung fasilitas modern, kurikulum terkini, serta pembelajaran berbasis kebutuhan dunia kerja dan industri, kami siap mencetak generasi profesional di bidang teknologi informasi. Mari bergabung bersama kami dan wujudkan masa depan cemerlang Anda di dunia teknologi!
                    ',
                    'pemberi_sambutan' => 'Satria Perdana Arifin, S.T., M.T.I',
                    'jabatan_sambutan' => 'Ketua Jurusan Teknologi Informasi',
                    'foto_sambutan'    => publicMedia('SPA.png', 'jurusan/dosen')
                ],
                'tentang jurusan'        => [
                    'title'            => 'Melahirkan Profesional IT yang Kompeten dan Adaptif',
                    'deskripsi'        => '
                            Jurusan Informasi dan Teknologi hadir untuk menjawab tantangan perkembangan pesat dunia digital dengan menyiapkan lulusan yang unggul di bidang rekayasa perangkat lunak, keamanan siber, jaringan komputer, Internet of Things, serta sistem informasi. Melalui tiga program sarjana terapan—Teknik Informatika, Teknologi Rekayasa Komputer, dan Sistem Informasi—mahasiswa dibekali keterampilan software engineering, data engineering, smart system, cyber security, computer network, hingga manajemen data terintegrasi.
                            Jurusan ini juga membuka jenjang Magister Teknik Komputer yang berfokus pada penguasaan teori dan praktik di bidang cyber security, data science, machine learning, big data, IoT, serta integrasi sistem cerdas. Lulusan diproyeksikan menjadi tenaga profesional yang adaptif, inovatif, dan siap berperan sebagai software engineer, data scientist, system analyst, cyber security specialist, maupun application developer di berbagai sektor industri.
                    ',
                    'cover'            => publicMedia('jti.jpg', 'jurusan'),
                    'jumlah_mahasiswa' => '1500+',
                ],
                'jurusan'                => [
                    'title'     => 'Program Studi',
                    'deskripsi' => 'Lorem ipsum dolor sit, amet consectetur adipisicing elit. Ullam, ut illum, vitae ea suscipit praesentium quas alias ad sunt sint dolores necessitatibus, modi incidunt inventore culpa asperiores tenetur. Molestiae mollitia commodi, debitis alias tenetur esse architecto. Laborum quisquam molestiae amet incidunt. Labore, deleniti quia placeat a ullam ipsa unde quod possimus quam ducimus beatae nemo, nisi corporis minima veritatis! Cumque non ut obcaecati quaerat velit culpa voluptatum beatae rerum sit perferendis delectus, pariatur et amet explicabo modi hic qui, esse eveniet a minus. In a et maxime illo nesciunt accusantium facere totam architecto quae, pariatur dolorem natus odio consectetur deleniti?',
                ]
            ],
            'jtin' => [
                'sambutan ketua jurusan' => [
                    'title'            => 'Selamat datang di Jurusan Teknologi Industri Politeknik Caltex Riau.',
                    'sambutan'         => '
                    Kami membina 6 program studi unggulan di bidang teknologi terapan dengan lebih dari 83% program studi terakreditasi A dan Unggul. Dengan semangat Integrity, Dignity, Excellence, Agility, dan Loyalty, kami berkomitmen mencetak lulusan yang kompeten, inovatif, dan siap bersaing di dunia industri.
                    Bersama dosen berpengalaman, fasilitas modern, dan kurikulum berbasis industri, mari bergabung dan wujudkan masa depan Anda bersama kami!
                    ',
                    'pemberi_sambutan' => 'Dr. Ir. Emansa Hasri Putra, S.T., M.Eng.',
                    'jabatan_sambutan' => 'Ketua Jurusan Teknologi Industri',
                    'foto_sambutan'    => publicMedia('EHP.png', 'jurusan/dosen')
                ],
                'tentang jurusan'        => [
                    'title'            => 'Mewujudkan Profesional Industri Masa Depan',
                    'deskripsi'        => '
                        Jurusan Teknologi Industri berfokus pada pengembangan keahlian di bidang elektronika, telekomunikasi, mesin, mekatronika, dan kelistrikan yang terintegrasi dengan teknologi mutakhir. Melalui berbagai program studi—Teknologi Rekayasa Sistem Elektronika, Teknologi Rekayasa Jaringan Telekomunikasi, Teknik Elektronika Telekomunikasi, Teknik Mesin, Teknologi Rekayasa Mekatronika, dan Teknik Listrik—mahasiswa dibekali kompetensi mulai dari desain rekayasa, operasi dan pemeliharaan sistem, pengembangan IoT dan embedded system, jaringan fiber optic dan telekomunikasi seluler, hingga perancangan mekanik, robotika, otomasi, dan energi terbarukan.
                        Dengan kurikulum yang menekankan keterampilan praktis, analitis, serta soft skills, jurusan ini mencetak lulusan yang mampu merancang, mengembangkan, dan mengoptimalkan berbagai sistem industri modern. Lulusan siap berkarier sebagai engineer, teknisi ahli, perancang sistem, maupun inovator di bidang manufaktur, energi, telekomunikasi, dan otomasi industri, serta mampu menjawab tantangan industri 4.0 dan transisi energi berkelanjutan.
                    ',
                    'cover'            => publicMedia('jtin.jpg', 'jurusan'),
                    'jumlah_mahasiswa' => '1100+',
                ],
                'jurusan'                => [
                    'title'     => 'Program Studi',
                    'deskripsi' => 'Lorem ipsum dolor sit, amet consectetur adipisicing elit. Ullam, ut illum, vitae ea suscipit praesentium quas alias ad sunt sint dolores necessitatibus, modi incidunt inventore culpa asperiores tenetur. Molestiae mollitia commodi, debitis alias tenetur esse architecto. Laborum quisquam molestiae amet incidunt. Labore, deleniti quia placeat a ullam ipsa unde quod possimus quam ducimus beatae nemo, nisi corporis minima veritatis! Cumque non ut obcaecati quaerat velit culpa voluptatum beatae rerum sit perferendis delectus, pariatur et amet explicabo modi hic qui, esse eveniet a minus. In a et maxime illo nesciunt accusantium facere totam architecto quae, pariatur dolorem natus odio consectetur deleniti?',
                ]
            ]
        ];

        return array_key_exists($jurusanAlias, $content) ? $content[$jurusanAlias] : null;
    }

    public static function getJurusan($jurusanAlias)
    {
        try {
            return Jurusan::where('alias_jurusan', $jurusanAlias)->firstOrFail();
        } catch (\Exception $e) {
            return null;
        }
    }

    public static function getJurusanHeader($jurusanAlias)
    {
        try {
            $jurusanHeader = Konten::getHeader('jurusan_page');
            $jurusanHeader = $jurusanHeader[0][0][0];
            return $jurusanHeader;
        } catch (\Exception $e) {
            return null;
        }
    }

    public static function getProdiListGroup($jurusanAlias)
    {

        $jenjangOrder = ['D3' => 1, 'D4' => 2, 'S2' => 3];

        $jurusan = Prodi::with('source')
            ->where('alias_jurusan', $jurusanAlias)
            ->inRandomOrder()
            // Group by jenjang pendidikan
            ->get()
            ->groupBy(fn($prodi) => $prodi->source->jenjang_pendidikan)
            ->sortBy(fn($group, $jenjang) => $jenjangOrder[$jenjang] ?? 999); // Default to a high number if not found

        $jenjangMap = [
            'D4' => 'Sarjana Terapan',
            'S2' => 'Magister Terapan',
        ];

        // Map jurusan keys to their full names
        $jurusan = $jurusan->mapWithKeys(fn($group, $jenjang) => [
            $jenjangMap[$jenjang] ?? $jenjang => $group,
        ]);

        return $jurusan;
    }

    public static function trimJurusanName($jurusanName)
    {
        return Str::trim(Str::replace('Jurusan', '', $jurusanName));
    }

    public static function getJurusanBreadcrumbs($jurusan = null, $prodi = null, $lecturer = null)
    {
        if (!$jurusan) {
            return [
                ['name' => 'Jurusan', 'url' => route('frontend.academic.jurusan.index')],
            ];
        }

        $jurusanExtraText = self::trimJurusanName($jurusan->nama_jurusan);

        $breadcrumbs = [
            [
                'name' => "Jurusan $jurusanExtraText",
                'url'  => route('frontend.academic.jurusan.show', [
                    'jurusanAlias' => Str::lower($jurusan->alias_jurusan)
                ])
            ],
        ];

        if ($prodi) {
            $breadcrumbs[] = [
                'name' => "Program Studi $prodi->nama_prodi",
                'url'  => route(
                    'frontend.academic.prodi.show',
                    [
                        'jurusanAlias' => Str::lower($jurusan->alias_jurusan),
                        'prodiAlias'   => Str::lower($prodi->alias)
                    ]
                )
            ];
        }

        if ($lecturer) {
            $breadcrumbs[] = [
                'name' => "Profil $lecturer->name",
                'url'  => route(
                    'frontend.academic.jurusan.lecturer-profile',
                    [
                        'jurusanAlias' => Str::lower($jurusan->alias_jurusan),
                        'slugLecturer' => $lecturer->slug_pegawai
                    ]
                )
            ];
        }

        return $breadcrumbs;
    }

    /**
     * Get jurusan list in random order
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getJurusanRandom()
    {
        return Jurusan::inRandomOrder()->get();
    }

    /**
     * Get icon class for jurusan based on alias
     *
     * @param string $jurusanAlias
     * @return string
     */
    public static function getJurusanIcon(string $jurusanAlias): string
    {
        $iconMap = [
            'JTI'  => 'fa-solid fa-computer',
            'JBK'  => 'fa-solid fa-comments',
            'JTIN' => 'fa-solid fa-screwdriver-wrench',
        ];

        return $iconMap[$jurusanAlias] ?? 'fa-solid fa-graduation-cap';
    }

    /**
     * Get all jurusan with their corresponding icons
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getJurusanWithIcons()
    {
        $jurusanList = self::getJurusanRandom();

        return $jurusanList->map(function ($jurusan) {
            $jurusan->icon = self::getJurusanIcon($jurusan->alias_jurusan);
            return $jurusan;
        });
    }

    public static function getJurusanCallout()
    {
        return [
            'title'       => 'Langsung Kerja, <u>Siap Berkarya</u>',
            'subtitle'    => 'Pendidikan Vokasi',
            'description' => 'Melalui pendidikan vokasi, Politeknik Caltex Riau membekali mahasiswa dengan keterampilan praktis dan pengetahuan aplikatif. Kurikulum dirancang untuk menjawab kebutuhan dunia industri, menjadikan lulusan siap kerja dan kompeten dibidangnya',
            'list'        => self::getJurusanWithIcons()
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
        $bg   = $postContent ? data_get($postContent, 'content.images.src') : publicMedia('jurusan.webp');

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
}
