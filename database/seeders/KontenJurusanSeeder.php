<?php

namespace Database\Seeders;

use App\Models\View\Jurusan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KontenJurusanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contents = [];
        $get_jurusan = Jurusan::all();

        foreach ($get_jurusan as $row) {
            $contents[] =
                [
                    'alias_jurusan' => $row->alias,
                    'header_jurusan' => json_encode([
                        'media' => '' //media_id
                    ]),
                    'sambutan_jurusan' => json_encode([
                        'title' => '',
                        'media_id' => '',
                        'sambutan' => '',
                        'pemberi_sambutan' => '',
                        'jabatan_sambutan' => '',
                    ]),
                    'tentang_jurusan' => json_encode([
                        'title' => '',
                        'deskripsi' => ''
                    ]),
                    'prodi_jurusan' => json_encode([
                        'title' => '',
                        'deskripsi' => ''
                    ]),
                    'created_by' => 'DEV',
                    'updated_by' => null,
                    'deleted_by' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
        }

        DB::table('konten_jurusan')->insert($contents);
    }
}
