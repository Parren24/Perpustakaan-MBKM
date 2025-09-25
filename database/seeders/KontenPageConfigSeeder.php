<?php

namespace Database\Seeders;

use App\Models\Konten\KontenTipe;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KontenPageConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $getTipe = KontenTipe::all();
        // foreach ($getTipe as $row) {
        //     $config = [
        //         'target_config' => 'main-site',
        //         'kontentipe_id' => $row->kontentipe_id
        //     ];

        //     DB::table('konten_page_config')->insert($config);
        // }

        // $getConfig = DB::table('konten_page_config')->get();

        foreach ($getTipe as $rows) {
            $static_page = [
                'kontentipe_id' => $rows->kontentipe_id,
                'level' => 'main-site',
                'level_id' => null,
                'title_page' => $rows->nama_tipe,
                'status_page' => 'draft',
                'created_by' => 'DZB',
                'updated_by' => null,
                'deleted_by' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            DB::table('konten_page')->insert($static_page);
        }
    }
}
