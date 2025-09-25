<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KontenConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contents = [];

        $contents[] = [
            'level' => 'main-site',
            'level_id' => null,
            'sequence_konten' => json_encode([
                'hero_main',
                'infografis_main',
                'jurusan_main',
                'pmb_main',
                'partner_main',
            ]),
            'created_by' => 'DEV',
            'updated_by' => null,
            'deleted_by' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        DB::table('konten_config')->insert($contents);
    }
}
