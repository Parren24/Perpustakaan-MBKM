<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Str;
use App\Models\Dimension\Jurusan;

class HeaderMenuComposer
{
    public function compose(View $view)
    {
        // Build the menu here so blade stays clean
        $jurusanList = Jurusan::inRandomOrder()->get()->map(function ($item) {
            return (object) [
                'id'         => $item->kontenjurusan_id,
                'alias'      => Str::lower($item->alias_jurusan),
                'name'       => $item->nama_jurusan,
                'slicedName' => Str::trim(Str::replace('Jurusan', '', $item->nama_jurusan)),
            ];
        });

        $menu = [
            [
                'name'  => 'Beranda',
                'route' => route('frontend.home'),
            ],
            [
                'name'     => 'Profil',
                'children' => [
                    ['name' => 'Sejarah', 'route' => route('frontend.profile.history')],
                    ['name' => 'Visi dan Misi', 'route' => route('frontend.profile.visi-misi')],
                    ['name' => 'Keberagaman', 'route' => route('frontend.profile.diversity')],
                    ['name' => 'Sambutan YPCR', 'route' => route('frontend.profile.welcome-ypcr')],
                    ['name' => 'Sambutan Direktur', 'route' => route('frontend.profile.welcome-director')],
                    ['name' => 'Organisasi dan Profil Pimpinan', 'route' => route('frontend.profile.organization')],
                    ['name' => 'Panduan Identitas PCR', 'route' => route('frontend.profile.identity')],
                    ['name' => 'Lokasi', 'route' => route('frontend.profile.location')],
                    ['name' => 'Akreditasi', 'route' => route('frontend.profile.accreditation')],
                    ['name' => 'Prestasi dan Penghargaan', 'route' => route('frontend.profile.achievements')],
                ]
            ],
            [
                'name'     => 'Akademik',
                'children' => [
                    [
                        'name'     => 'Jurusan',
                        'route'    => route('frontend.academic.jurusan.index'),
                        'children' => $jurusanList->map(function ($jurusan) {
                            return [
                                'name'  => $jurusan->slicedName,
                                'route' => route('frontend.academic.jurusan.show', ['jurusanAlias' => $jurusan->alias]),
                            ];
                        })->toArray(),
                    ],
                    ['name' => 'Perpustakaan', 'route' => 'https://lib.pcr.ac.id/'],
                    ['name' => 'Kalender Akademik', 'route' => 'https://baak.pcr.ac.id/kalender-akademik/'],
                    ['name' => 'Beasiswa', 'route' => route('frontend.academic.scholarship')],
                    [
                        'name'     => 'Alumni dan Pusat Karir',
                        'children' => [
                            ['name' => 'Tracer Study', 'route' => 'https://tracer.pcr.ac.id/'],
                            ['name' => 'Pusat Karir', 'route' => 'https://scc.pcr.ac.id'],
                        ]
                    ],
                ],
            ],
            [
                'name'     => 'Layanan',
                'hide_xxl' => true,
                'children' => [
                    ['name' => 'Sistem dan Teknologi Informasi', 'route' => 'https://bsti.pcr.ac.id'],
                    [
                        'name'     => 'Akademik dan Kemahasiswaan',
                        'children' => [
                            ['name' => 'BAAK', 'route' => 'https://baak.pcr.ac.id'],
                            ['name' => 'Mahasiswa', 'route' => 'https://mahasiswa.pcr.ac.id'],
                            ['name' => 'Orangtua', 'route' => 'https://orangtua.pcr.ac.id'],
                        ]
                    ],
                    [
                        'name'     => 'Penjaminan Mutu',
                        'children' => [
                            ['name' => 'SPMI', 'route' => 'https://spmi.pcr.ac.id'],
                            ['name' => 'Survei', 'route' => 'https://survey.pcr.ac.id'],
                        ]
                    ],
                    ['name' => 'Kemitraan', 'route' => 'https://kbp.pcr.ac.id'],
                    ['name' => 'Penelitian dan PKM', 'route' => 'https://bp2m.pcr.ac.id'],
                    [
                        'name'     => 'Alumni dan Pusat Karir',
                        'children' => [
                            ['name' => 'Tracer Study', 'route' => 'https://tracer.pcr.ac.id/'],
                            ['name' => 'Pusat Karir', 'route' => 'https://scc.pcr.ac.id'],
                        ]
                    ],
                ],
            ],
        ];

        $view->with('menu', $menu);
    //                 ['name' => 'Sejarah', 'route' => route('frontend.profile.history')],
    //                 ['name' => 'Visi dan Misi', 'route' => route('frontend.profile.visi-misi')],
    //                 ['name' => 'Keberagaman', 'route' => route('frontend.profile.diversity')],
    //                 ['name' => 'Sambutan YPCR', 'route' => route('frontend.profile.welcome-ypcr')],
    //                 ['name' => 'Sambutan Direktur', 'route' => route('frontend.profile.welcome-director')],
    //                 ['name' => 'Organisasi dan Profil Pimpinan', 'route' => route('frontend.profile.organization')],
    //                 ['name' => 'Panduan Identitas PCR', 'route' => route('frontend.profile.identity')],
    //                 ['name' => 'Lokasi', 'route' => route('frontend.profile.location')],
    //                 ['name' => 'Akreditasi', 'route' => route('frontend.profile.accreditation')],
    //                 ['name' => 'Prestasi dan Penghargaan', 'route' => route('frontend.profile.achievements')],
    //                 // ['name' => 'Laporan Tahunan', 'route' => route('frontend.profile.yearly-report')],
    //             ],
    //         ],
    //         [
    //             'name'     => 'Akademik',
    //             'children' => [
    //                 [
    //                     'name'     => 'Jurusan',
    //                     'route'    => route('frontend.academic.jurusan.index'),
    //                     'children' => $jurusanList->map(function ($jurusan) {
    //                         return [
    //                             'name'  => $jurusan->slicedName,
    //                             'route' => route('frontend.academic.jurusan.show', ['jurusanAlias' => $jurusan->alias]),
    //                         ];
    //                     })->toArray(),
    //                 ],
    //                 ['name' => 'Perpustakaan', 'route' => 'https://lib.pcr.ac.id/'],
    //                 ['name' => 'Kalender Akademik', 'route' => 'https://baak.pcr.ac.id/kalender-akademik/'],
    //                 ['name' => 'Beasiswa', 'route' => route('frontend.academic.scholarship')],
    //                 [
    //                     'name'     => 'Alumni dan Pusat Karir',
    //                     'children' => [
    //                         ['name' => 'Tracer Study', 'route' => 'https://tracer.pcr.ac.id/'],
    //                         ['name' => 'Pusat Karir', 'route' => 'https://scc.pcr.ac.id'],
    //                     ]
    //                 ],
    //             ],
    //         ],
    //         [
    //             'name'     => 'Layanan',
    //             'hide_xxl' => true,
    //             'children' => [
    //                 ['name' => 'Sistem dan Teknologi Informasi', 'route' => 'https://bsti.pcr.ac.id'],
    //                 [
    //                     'name'     => 'Akademik dan Kemahasiswaan',
    //                     'children' => [
    //                         ['name' => 'BAAK', 'route' => 'https://baak.pcr.ac.id'],
    //                         ['name' => 'Mahasiswa', 'route' => 'https://mahasiswa.pcr.ac.id'],
    //                         ['name' => 'Orangtua', 'route' => 'https://orangtua.pcr.ac.id'],
    //                     ]
    //                 ],
    //                 [
    //                     'name'     => 'Penjaminan Mutu',
    //                     'children' => [
    //                         ['name' => 'SPMI', 'route' => 'https://spmi.pcr.ac.id'],
    //                         ['name' => 'Survei', 'route' => 'https://survey.pcr.ac.id'],
    //                     ]
    //                 ],
    //                 ['name' => 'Kemitraan', 'route' => 'https://kbp.pcr.ac.id'],
    //                 ['name' => 'Penelitian dan PKM', 'route' => 'https://bp2m.pcr.ac.id'],
    //                 [
    //                     'name'     => 'Alumni dan Pusat Karir',
    //                     'children' => [
    //                         ['name' => 'Tracer Study', 'route' => 'https://tracer.pcr.ac.id/'],
    //                         ['name' => 'Pusat Karir', 'route' => 'https://scc.pcr.ac.id'],
    //                     ]
    //                 ],
    //                 // ['name' => 'Informasi Publik dan Pengaduan', 'route' => route('frontend.service.information-and-complaints')],
    //             ],
    //         ],
    //         // [
    //         //     'name'     => 'Riset Terapan',
    //         //     'hide_xxl' => 'true',
    //         //     'route'    => route('frontend.research.index'),
    //         // ],
    //         [
    //             'name'  => 'PCR Squad',
    //             'route' => route('frontend.pcr-squad.index'),
    //         ],
    //         [
    //             'name'     => 'Kehidupan Kampus',
    //             'hide_xxl' => true,
    //             'children' => [
    //                 // [
    //                 //     'name'     => 'Aktivitas Mahasiswa',
    //                 //     'children' => [
    //                 //         ['name' => 'Organisasi Mahasiswa', 'route' => route('frontend.campus-life.student-organization.index')],
    //                 //         ['name' => 'Himpunan Mahasiswa', 'route' => route('frontend.campus-life.himpunan.index')],
    //                 //         ['name' => 'Unit Kegiatan Mahasiswa', 'route' => route('frontend.campus-life.ukm.index')],
    //                 //     ]
    //                 // ],
    //                 // ['name' => 'Jelajahi Pekanbaru', 'route' => route('frontend.campus-life.explore-pekanbaru')],
    //                 // ['name' => 'Kost dan Sewa Rumah', 'route' => route('frontend.campus-life.rental.index')],
    //                 ['name' => 'Jelajahi Virtual Kampus PCR', 'route' => route('frontend.campus-life.virtual-tour')],
    //                 ['name' => 'Fasilitas', 'route' => route('frontend.campus-life.facilities.index')],
    //             ],
    //         ],
    //         ['name' => 'Artikel', 'route' => route('frontend.articles.index')],
    //         // [
    //         //     'name'     => 'Informasi',
    //         //     'children' => [
    //         //         ['name' => 'Kontak', 'route' => route('frontend.information.contact')],
    //         //         ['name' => 'FAQ', 'route' => route('frontend.information.faq')],
    //         //         ['name' => 'Shop', 'route' => route('frontend.information.shop.index')],
    //         //     ],
    //         // ],
    //     ];

    //     $view->with('menu', $menu);
    }
}
