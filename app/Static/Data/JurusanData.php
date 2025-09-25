<?php

namespace App\Static\Data;

class JurusanData
{
    public static function all()
    {
        return collect([
            (object) [
                'id' => 'jti',
                'title' => 'Jurusan Teknologi Informasi',
                'name' => 'JTI',
                'prodi' => ProdiData::all()->where('jurusan_id', 'jti'),
                'image' => asset('theme/frontend/images/placeholders/16x9.png'),
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
            ],
            (object) [
                'id' => 'jkb',
                'title' => 'Jurusan Komunikasi dan Bisnis',
                'name' => 'JKB',
                'prodi' => ProdiData::all()->where('jurusan_id', 'jkb'),
                'image' => asset('theme/frontend/images/placeholders/16x9.png'),
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
            ],
            (object) [
                'id' => 'jtin',
                'title' => 'Jurusan Teknologi Industri',
                'name' => 'JTIN',
                'prodi' => ProdiData::all()->where('jurusan_id', 'jtin'),
                'image' => asset('theme/frontend/images/placeholders/16x9.png'),
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
            ],
        ]);
    }


}
