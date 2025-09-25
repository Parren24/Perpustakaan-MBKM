<?php

namespace App\Static\Data;

class AccociatesData
{
    public static function all()
    {
        $associates_institution = collect([
            (object) [
                'id' => '1',
                'name' => 'Universiti Tun Hussein Onn Malaysia',
            ],
            (object) [
                'id' => '2',
                'name' => 'Politeknik Mersing, Johor Malaysia',
            ],
            (object) [
                'id' => '3',
                'name' => 'Politeknik Sultan Salahudin Abdul Aziz Shah',
            ],
            (object) [
                'id' => '4',
                'name' => 'Politeknik Bengkalis',
            ],
            (object) [
                'id' => '5',
                'name' => 'Politeknik Negeri Batam',
            ],
            (object) [
                'id' => '5',
                'name' => 'Beberapa sekolah (SMA, SMK, MA dan SLB)',
            ],
            (object) [
                'id' => '6',
                'name' => 'Perpustakaan UNRI, UMRI, dan UNILAK',
            ]
        ]);

        $associates_instance = collect([
            (object) [
                'id' => '7',
                'name' => 'Pemerintah Provinsi Riau',
            ],
            (object) [
                'id' => '8',
                'name' => 'Pemerintah Kota Dumai',
            ],
            (object) [
                'id' => '9',
                'name' => 'Pemerintah Kabupaten Siak',
            ],
            (object) [
                'id' => '10',
                'name' => 'Pemerintah Kabupaten Rokan Hilir (ROHIL)',
            ],
            (object) [
                'id' => '11',
                'name' => 'Kementerian Pendayagunaan Aparatur Negara dan Reformasi Birokrasi',
            ],
        ]);

        $associates_industry = collect([
            (object) [
                'id' => '12',
                'name' => 'PT. Chevron Pacific Indonesia',
            ],
            (object) [
                'id' => '13',
                'name' => 'PT Pertamina Hulu Rokan',
            ],
            (object) [
                'id' => '14',
                'name' => 'PT Schneider Indonesia',
            ],
            (object) [
                'id' => '15',
                'name' => 'PT PCI Elektronik Internasional',
            ],
            (object) [
                'id' => '16',
                'name' => 'PT Metrodata Electronics',
            ],
            (object) [
                'id' => '17',
                'name' => 'Lorem Ipsum Dolor Sit Amet',
            ],
        ]);

        return (object) [
            'institutions' => $associates_institution,
            'instances' => $associates_instance,
            'industries' => $associates_industry,
        ];
    }
}
