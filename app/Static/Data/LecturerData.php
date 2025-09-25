<?php

namespace App\Static\Data;

class LecturerData
{
    public static function all()
    {
        return collect([
            (object) [
                'id' => 'd1',
                'prodi_id' => 'ti',
                'name' => 'Nama Dosen 1',
                'occupation' => 'Dosen Tetap',
                'email' => 'dosen1@example.com',
                'room' => 'Ruang A101',
                'image' => asset('theme/frontend/images/placeholders/3x4.png'),
                'profile' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
                'publications' => collect([
                    (object) [
                        'id' => 'pub1',
                        'title' => 'Publikasi 1',
                        'publishers' => collect(['Penerbit A', 'Penerbit B']),
                    ],
                    (object) [
                        'id' => 'pub2',
                        'title' => 'Publikasi 2',
                        'publishers' => collect(['Penerbit C']),
                    ],
                ]),
                'research' => collect([
                    (object) [
                        'id' => 'res1',
                        'title' => 'Penelitian 1',
                        'publishers' => collect(['Penerbit D', 'Penerbit E']),
                    ],
                    (object) [
                        'id' => 'res2',
                        'title' => 'Penelitian 2',
                        'publishers' => collect(['Penerbit F']),
                    ],
                ]),
                'community_service' => collect([
                    (object) [
                        'id' => 'cs1',
                        'title' => 'Pengabdian Masyarakat 1',
                        'publishers' => collect(['Penerbit G']),
                    ],
                    (object) [
                        'id' => 'cs2',
                        'title' => 'Pengabdian Masyarakat 2',
                        'publishers' => collect(['Penerbit H']),
                    ],
                ]),
                'subjects' => collect([
                    (object) [
                        'id' => 'sub1',
                        'code' => 'MK001',
                        'name' => 'Mata Kuliah 1',
                    ],
                    (object) [
                        'id' => 'sub2',
                        'code' => 'MK002',
                        'name' => 'Mata Kuliah 2',
                    ],
                ])
            ]
        ]);
    }
}
