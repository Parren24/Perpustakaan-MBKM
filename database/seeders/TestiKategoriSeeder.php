<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestiKategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'kode_kategori' => 'BUMN',
                'nama_kategori' => 'BUMN',
                'created_by' => 'DZB',
                'updated_by' => null,
                'deleted_by' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_kategori' => 'BUMD',
                'nama_kategori' => 'BUMD',
                'created_by' => 'DZB',
                'updated_by' => null,
                'deleted_by' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_kategori' => 'OIG',
                'nama_kategori' => 'Oil and Gas',
                'created_by' => 'DZB',
                'updated_by' => null,
                'deleted_by' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_kategori' => 'GOV',
                'nama_kategori' => 'Pegawai Pemerintahan',
                'created_by' => 'DZB',
                'updated_by' => null,
                'deleted_by' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_kategori' => 'PVT',
                'nama_kategori' => 'Pegawai Swasta',
                'created_by' => 'DZB',
                'updated_by' => null,
                'deleted_by' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        DB::table('testi_kategori')->insert($categories);
    }
}
