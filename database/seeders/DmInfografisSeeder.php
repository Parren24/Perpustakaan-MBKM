<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DmInfografisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'icon_infografis' => 'graduation-cap',
                'value_infografis' => 'Unggul',
                'nama_infografis' => 'Akreditasi Prodi',
                'seq' => 1,
            ],
            [
                'icon_infografis' => 'handshake',
                'value_infografis' => '15',
                'nama_infografis' => 'Mitra Kerjasama',
                'seq' => 2,
            ],
            [
                'icon_infografis' => 'briefcase',
                'value_infografis' => '20%',
                'nama_infografis' => 'Lulusan Kerja < 6 Bulan',
                'seq' => 3,
            ],
            [
                'icon_infografis' => 'certificate',
                'value_infografis' => '100%',
                'nama_infografis' => 'Lulusan Bersertifikasi',
                'seq' => 4,
            ],
            [
                'icon_infografis' => 'book',
                'value_infografis' => '18',
                'nama_infografis' => 'Program Studi',
                'seq' => 5,
            ],
            [
                'icon_infografis' => 'user-group',
                'value_infografis' => '2000+',
                'nama_infografis' => 'Mahasiswa Aktif',
                'seq' => 6,
            ],
            [
                'icon_infografis' => 'users',
                'value_infografis' => '3000+',
                'nama_infografis' => 'Alumni',
                'seq' => 7,
            ],
            [
                'icon_infografis' => 'chalkboard-teacher',
                'value_infografis' => '40+',
                'nama_infografis' => 'Dosen',
                'seq' => 8,
            ],
            [
                'icon_infografis' => 'user-tie',
                'value_infografis' => '10+',
                'nama_infografis' => 'Guru Besar',
                'seq' => 9,
            ],
            [
                'icon_infografis' => 'first-aid',
                'value_infografis' => '20',
                'nama_infografis' => 'Fasilitas Asuransi Mahasiswa',
                'seq' => 10,
            ],
            [
                'icon_infografis' => 'clock',
                'value_infografis' => '< 2 Bulan',
                'nama_infografis' => 'Masa Tunggu Lulusan',
                'seq' => 11,
            ],
            [
                'icon_infografis' => 'chart-line',
                'value_infografis' => '82,4%',
                'nama_infografis' => 'Animo Mahasiswa',
                'seq' => 12,
            ],
            [
                'icon_infografis' => 'flask',
                'value_infografis' => '52%',
                'nama_infografis' => 'Praktikum',
                'seq' => 13,
            ],
            [
                'icon_infografis' => 'file-alt',
                'value_infografis' => '42',
                'nama_infografis' => 'Penerima Beasiswa',
                'seq' => 14,
            ],
            [
                'icon_infografis' => 'tools',
                'value_infografis' => '90+',
                'nama_infografis' => 'Fasilitas Modern',
                'seq' => 15,
            ],
            [
                'icon_infografis' => 'vial',
                'value_infografis' => '40+',
                'nama_infografis' => 'Lab Tersertifikasi',
                'seq' => 16,
            ],
        ];

        foreach ($data as $item) {
            DB::table('dm_infografis')->insert(array_merge($item, [
                'sync_url' => '',
                'sync_log' => json_encode([
                    'synced_by' => 'DEV',
                    'synced_at' => now(),
                ]),
                'created_by' => 'DZB',
                'updated_by' => null,
                'deleted_by' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'deleted_at' => null,
            ]));
        }
    }
}
