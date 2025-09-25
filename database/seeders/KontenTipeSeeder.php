<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KontenTipeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tipe = [
            [
                'kode_tipe' => 'sejarah',
                'nama_tipe' => 'Sejarah',
                'deskripsi_tipe' => '',
                'component_tipe' => json_encode([
                    'milestone'
                ]),
                'created_by' => 'DZB',
                'created_at' => now(),
            ],
            [
                'kode_tipe' => 'visimisi',
                'nama_tipe' => 'Visi dan Misi',
                'deskripsi_tipe' => '',
                'created_by' => 'DZB',
                'created_at' => now(),
            ],
            [
                'kode_tipe' => 'diversitas',
                'nama_tipe' => 'Diversitas',
                'deskripsi_tipe' => '',
                'created_by' => 'DZB',
                'created_at' => now(),
            ],
            [
                'kode_tipe' => 'ypcr',
                'nama_tipe' => 'Sambutan Yayasan',
                'deskripsi_tipe' => '',
                'component_tipe' => json_encode([
                    'media_main',
                    'media_sub'
                ]),
                'created_by' => 'DZB',
                'created_at' => now(),
            ],
            [
                'kode_tipe' => 'direktur',
                'nama_tipe' => 'Sambutan Direktur',
                'deskripsi_tipe' => '',
                'component_tipe' => json_encode([
                    'media_main',
                    'media_sub'
                ]),
                'created_by' => 'DZB',
                'created_at' => now(),
            ]
        ];

        DB::table('konten_tipe')->insert($tipe);
    }
}
