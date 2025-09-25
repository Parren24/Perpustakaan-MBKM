<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostLabelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $labels = [
            [
                'postkategori_id' => '1',
                'kode_label' => 'kehidupan-kampus',
                'nama_label' => 'Kehidupan Kampus',
                'created_by' => 'DZB',
                'updated_by' => null,
                'deleted_by' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'postkategori_id' => '3',
                'kode_label' => 'riset-inovasi',
                'nama_label' => 'Riset dan Inovasi',
                'created_by' => 'DZB',
                'updated_by' => null,
                'deleted_by' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'postkategori_id' => '1',
                'kode_label' => 'kegiatan-ukm',
                'nama_label' => 'Kegiatan UKM',
                'created_by' => 'DZB',
                'updated_by' => null,
                'deleted_by' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'postkategori_id' => '1',
                'kode_label' => 'sekilas-alumni',
                'nama_label' => 'Sekilas Alumni',
                'created_by' => 'DZB',
                'updated_by' => null,
                'deleted_by' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'postkategori_id' => '3',
                'kode_label' => 'pengabidan-masyarakat',
                'nama_label' => 'Pengabdian Masyarakat',
                'created_by' => 'DZB',
                'updated_by' => null,
                'deleted_by' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'postkategori_id' => '1',
                'kode_label' => 'sekilas-pcr',
                'nama_label' => 'Sekilas PCR',
                'created_by' => 'DZB',
                'updated_by' => null,
                'deleted_by' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'postkategori_id' => '1',
                'kode_label' => 'kerjasama-pemasaran',
                'nama_label' => 'Kerjasama dan Pemasaran',
                'created_by' => 'DZB',
                'updated_by' => null,
                'deleted_by' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'postkategori_id' => '4',
                'kode_label' => 'prestasi-institusi',
                'nama_label' => 'Prestasi Institusi',
                'created_by' => 'DZB',
                'updated_by' => null,
                'deleted_by' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'postkategori_id' => '4',
                'kode_label' => 'prestasi-mahasiswa',
                'nama_label' => 'Prestasi Mahasiswa',
                'created_by' => 'DZB',
                'updated_by' => null,
                'deleted_by' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'postkategori_id' => '4',
                'kode_label' => 'prestasi-dosen-tendik',
                'nama_label' => 'Prestasi Dosen dan Tenaga Pendidik',
                'created_by' => 'DZB',
                'updated_by' => null,
                'deleted_by' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('post_label')->insert($labels);
    }
}
