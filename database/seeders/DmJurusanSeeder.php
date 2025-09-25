<?php

namespace Database\Seeders;

use App\Models\View\Jurusan;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DmJurusanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $get_jurusan = Jurusan::all();

        foreach ($get_jurusan as $row) {
            $contents[] = [
                'alias_jurusan' => $row->alias,
                'nama_jurusan' => $row->jurusan,
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
            ];
        }

        DB::table('dm_jurusan')->insert($contents);
    }
}
