<?php

namespace Database\Seeders;

use App\Models\Konten\KontenMain;
use App\Models\View\Jurusan;
use App\Models\View\Prodi;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KontenMainSeeder extends Seeder
{
    public function run(): void
    {
        $get_jurusan = Jurusan::all();
        $get_prodi = Prodi::whereIn('jenjang_pendidikan', ['D4', 'S2'])->get();

        KontenMain::create([
            'level' => 'main-site',
            'level_id' => null,
            'hero_main' => [[
                'title' => '',
                'media_type' => '', //video/image
                'media_id' => ''
            ]],
            'infografis_main' => [
                'title' => '',
                'deskripsi' => '',
                'media_id' => ''
            ],
            'jurusan_main' => [
                'title' => '',
                'deskripsi' => ''
            ],
            'pmb_main' => [
                'title' => '',
                'deskripsi' => '',
                'media_id' => ''
            ],
            'partner_main' => [
                'title' => '',
                'deskripsi' => ''
            ],
            'created_by' => 'DZB',
            'updated_by' => null,
            'deleted_by' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'deleted_at' => null,
        ]);

        foreach ($get_jurusan as $rows) {
            KontenMain::create([
                'level' => 'jurusan-site',
                'level_id' => $rows->alias,
                'hero_main' => [[
                    'title' => '',
                    'media_type' => '', //video/image
                    'media_id' => ''
                ]],
                'infografis_main' => [
                    'title' => '',
                    'deskripsi' => '',
                    'media_id' => ''
                ],
                'jurusan_main' => [
                    'title' => '',
                    'deskripsi' => ''
                ],
                'pmb_main' => [
                    'title' => '',
                    'deskripsi' => '',
                    'media_id' => ''
                ],
                'partner_main' => [
                    'title' => '',
                    'deskripsi' => ''
                ],
                'created_by' => 'DZB',
                'updated_by' => null,
                'deleted_by' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'deleted_at' => null,
            ]);
        }

        foreach ($get_prodi as $rows) {
            KontenMain::create([
                'level' => 'prodi-site',
                'level_id' => $rows->alias,
                'hero_main' => [[
                    'title' => '',
                    'media_type' => '', //video/image
                    'media_id' => ''
                ]],
                'infografis_main' => [
                    'title' => '',
                    'deskripsi' => '',
                    'media_id' => ''
                ],
                'jurusan_main' => [
                    'title' => '',
                    'deskripsi' => ''
                ],
                'pmb_main' => [
                    'title' => '',
                    'deskripsi' => '',
                    'media_id' => ''
                ],
                'partner_main' => [
                    'title' => '',
                    'deskripsi' => ''
                ],
                'created_by' => 'DZB',
                'updated_by' => null,
                'deleted_by' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'deleted_at' => null,
            ]);
        }
    }
}
