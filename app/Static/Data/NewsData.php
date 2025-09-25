<?php

namespace App\Static\Data;

class NewsData
{
    public static function all()
    {
        return collect([
            [
                'id' => "1",
                'title' => "3 Mahasiswa PCR Berhasil Raih Medali di Kejuaraan Pencak Silat Internasional lorem Lorem ipsum
                        dolor sit amet.",
                'image' => asset('theme/frontend/images/examples/berita-1.png'),
                'date' => now()->addDays(-10)->diffForHumans()
            ],
            [
                'id' => "2",
                'title' => "Mahasiswa PCR Raih Juara 1 Lomba Desain Poster Nasional",
                'image' => asset('theme/frontend/images/examples/berita-1.png'),
                'date' => now()->addDays(-5)->diffForHumans()
            ],
            [
                'id' => "3",
                'title' => "PCR Gelar Seminar Nasional tentang Teknologi Terbaru di Bidang Informatika",
                'image' => asset('theme/frontend/images/examples/berita-1.png'),
                'date' => now()->diffForHumans()
            ],
            [
                'id' => "4",
                'title' => "Dosen PCR Menerima Penghargaan Internasional atas Penelitian Inovatif",
                'image' => asset('theme/frontend/images/examples/berita-1.png'),
                'date' => now()->addDays(2)->diffForHumans()
            ],
            [
                'id' => "5",
                'title' => "Mahasiswa PCR Raih Juara 1 Lomba Cipta Puisi Tingkat Nasional",
                'image' => asset('theme/frontend/images/examples/berita-1.png'),
                'date' => now()->addDays(5)->diffForHumans()
            ],
            [
                'id' => "6",
                'title' => "Dosen PCR Menerima Penghargaan Internasional atas Penelitian Inovatif",
                'image' => asset('theme/frontend/images/examples/berita-1.png'),
                'date' => now()->addDays(2)->diffForHumans()
            ]
        ])->map(fn($item) => (object) $item);
    }

    public static function allAnnouncement()
    {
        return collect(
            [
                [
                    'id' => "1",
                    'title' => "Pengumuman: Jadwal Ujian Akhir Semester Genap 2023/2024",
                    'date' => now()->addDays(-3)->diffForHumans(),
                ],
                [
                    'id' => "2",
                    'title' => "Pengumuman: Perpanjangan Pendaftaran Beasiswa Mahasiswa Baru 2023/2024",
                    'date' => now()->addDays(-1)->diffForHumans(),
                ],
                [
                    'id' => "3",
                    'title' => "Pengumuman: Libur Nasional dan Cuti Bersama Tahun Baru 2024",
                    'date' => now()->diffForHumans(),
                ],
                [
                    'id' => "4",
                    'title' => "Pengumuman: Pembukaan Pendaftaran Program Magang Mahasiswa 2024",
                    'date' => now()->addDays(1)->diffForHumans(),
                ]
            ]
        )->map(fn($item) => (object) $item);
    }
}
