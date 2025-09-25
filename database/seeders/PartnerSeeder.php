<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class PartnerSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'jenis_partner' => 'Industri',
                'nama_partner' => 'Zahir Online',
                // 'logo_partner' => 'https://pcr.ac.id/assets/media/small_pcr_media20240521110718.png',
                'url_partner' => 'https://www.zahironline.com/',
                'status_partner' => 'aktif'
            ],
            [
                'jenis_partner' => 'Industri',
                'nama_partner' => 'Oracle Academy',
                // 'logo_partner' => 'https://pcr.ac.id/assets/media/small_pcr_media20240521110524.png',
                'url_partner' => 'https://academy.oracle.com/en/oa-web-overview.html',
                'status_partner' => 'aktif'
            ],
            [
                'jenis_partner' => 'Industri',
                'nama_partner' => 'MikroTik Academy',
                // 'logo_partner' => 'https://pcr.ac.id/assets/media/small_pcr_media20240521110337.png',
                'url_partner' => 'https://mikrotik.com/training/academy',
                'status_partner' => 'aktif'
            ],
            [
                'jenis_partner' => 'Industri',
                'nama_partner' => 'Red Hat Academy',
                // 'logo_partner' => 'https://pcr.ac.id/assets/media/small_pcr_media20240521110242.png',
                'url_partner' => 'https://www.redhat.com/en/services/training/red-hat-academy',
                'status_partner' => 'aktif'
            ],
            [
                'jenis_partner' => 'Industri',
                'nama_partner' => 'EC-Council Academia',
                // 'logo_partner' => 'https://pcr.ac.id/assets/media/small_pcr_media20240521110216.png',
                'url_partner' => 'https://www.eccouncil.org/academia/',
                'status_partner' => 'aktif'
            ],
            [
                'jenis_partner' => 'Industri',
                'nama_partner' => 'Cisco Networking Academy',
                // 'logo_partner' => 'https://pcr.ac.id/assets/media/small_pcr_media20240521110144.png',
                'url_partner' => 'https://www.netacad.com/',
                'status_partner' => 'aktif'
            ],
            [
                'jenis_partner' => 'Industri',
                'nama_partner' => 'Advance Pact',
                // 'logo_partner' => 'https://pcr.ac.id/assets/media/small_pcr_media20231204081953.png',
                'url_partner' => 'https://advancepact.com/',
                'status_partner' => 'aktif'
            ],
            [
                'jenis_partner' => 'Industri',
                'nama_partner' => 'Pertamina Hulu Rokan',
                // 'logo_partner' => 'https://pcr.ac.id/assets/media/small_pcr_media20231204081502.png',
                'url_partner' => 'https://phr.pertamina.com/',
                'status_partner' => 'aktif'
            ],
            [
                'jenis_partner' => 'Institusi',
                'nama_partner' => 'Politeknik Sultan Salahuddin Abdul Aziz Shah',
                // 'logo_partner' => 'https://pcr.ac.id/assets/media/small_pcr_media20190704115509.png',
                'url_partner' => 'https://www.psa.edu.my/ms/',
                'status_partner' => 'aktif'
            ],
            [
                'jenis_partner' => 'Industri',
                'nama_partner' => 'IAIAI',
                // 'logo_partner' => 'https://pcr.ac.id/assets/media/small_pcr_media20190704115401.png',
                'url_partner' => 'http://www.iaiai.org/top/',
                'status_partner' => 'aktif'
            ],
            [
                'jenis_partner' => 'Industri',
                'nama_partner' => 'Oracle Academy',
                // 'logo_partner' => 'https://pcr.ac.id/assets/media/small_pcr_media20170505022836.jpg',
                'url_partner' => 'https://academy.oracle.com/en/oa-web-overview.html',
                'status_partner' => 'aktif'
            ],
            [
                'jenis_partner' => 'Industri',
                'nama_partner' => 'Chevron Pacific Indonesia',
                // 'logo_partner' => 'https://pcr.ac.id/assets/media/small_pcr_media20170302073608.jpg',
                'url_partner' => 'http://www.chevronindonesia.com/',
                'status_partner' => 'aktif'
            ],
        ];

        foreach ($data as $item) {
            DB::table('mst_partner')->insert(array_merge($item, [
                'created_by' => 'DZB',
                'updated_by' => null,
                'deleted_by' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'deleted_at' => null,
            ]));
        }
    }
}
