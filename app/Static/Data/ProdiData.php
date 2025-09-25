<?php

namespace App\Static\Data;

class ProdiData
{
    public static function all()
    {
        return collect([
            (object) [
                'id' => 'kb',
                'jurusan_id' => 'jkb',
                'image' => asset('theme/frontend/images/placeholders/3x4.png'),
                'name' => 'Bisnis Digital',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
            ],
            (object) [
                'id' => 'ti',
                'jurusan_id' => 'jti',
                'image' => asset('theme/frontend/images/placeholders/3x4.png'),
                'name' => 'Teknik Informatika',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
            ]
        ]);
    }
}
