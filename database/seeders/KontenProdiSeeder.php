<?php

namespace Database\Seeders;

use App\Models\View\Prodi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KontenProdiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contents = [];
        $get_prodi = Prodi::whereIn('jenjang_pendidikan', ['D4', 'S2'])->get();

        foreach ($get_prodi as $row) {
            $contents[] = [
                'alias_prodi' => $row->alias,
                'header_prodi' => json_encode([
                    'media_id' => ''
                ]),
                'tentang_prodi' => json_encode([
                    'deskripsi' => '',
                ]),
                'prospek_karir_prodi' => json_encode([
                    'keterangan' => '',
                    'prospek_karir' => []
                ]),
                'milestone_prodi' => json_encode([
                    [
                        'keterangan' => '',
                        'milestones' => [
                            'tahun' => '',
                            'keterangan' => ''
                        ]
                    ]
                ]),
                'visi_prodi' => '',
                'misi_prodi' =>  json_encode([
                    [
                        'icon' => '',
                        'misi' => ''
                    ]
                ]),
                'created_by' => 'DEV',
                'updated_by' => null,
                'deleted_by' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('konten_prodi')->insert($contents);
    }
}
