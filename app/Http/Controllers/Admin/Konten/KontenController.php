<?php

namespace App\Http\Controllers\Admin\Konten;

use App\Http\Controllers\Controller;
use App\Models\Konten;
use Illuminate\Http\Request;

class KontenController extends Controller
{
    protected $guides = [];
    function __construct()
    {
        /**
         * use this if needed
         */
        $this->activeRoot   = 'konten-main';
        $this->breadCrump[] = ['title' => 'Situs Utama', 'link' => url('')];

        $this->guides = [
            'hero' => [
                'section_image' => 'hero_guide.png',
                'section_guide' => [
                    'Title' => 'Merupakan judul yang menjadi highlight dari hero section website utama',
                    'Media' => 'Berupa gambar atau video dengan resolusi yang tinggi dan menjadi highlight utama saat website dibuka'
                ]
            ],
            'infografis' => [
                'section_image' => 'infografis_guide.png',
                'section_guide' => [
                    'Title' => 'Merupakan judul utama dari section infografis website utama',
                    'Deskripsi' => 'Deskripsi singkat terkait bagian infografis',
                    'Media' => 'Berupa gambar dengan background transparent'
                ]
            ],
            'jurusan' => [
                'section_image' => 'jurusan_guide.png',
                'section_guide' => [
                    'Title' => 'Merupakan judul utama dari section jurusan website utama',
                    'Deskripsi' => 'Deskripsi singkat terkait bagian jurusan',
                ]
            ],
            'pmb' => [
                'section_image' => 'pmb_guide.png',
                'section_guide' => [
                    'Title' => 'Merupakan judul utama dari section PMB website utama',
                    'Deskripsi' => 'Deskripsi singkat terkait bagian PMB',
                    'Media' => 'Berupa gambar dengan background transparent'
                ]
            ],
            'rekan' => [
                'section_image' => 'rekan_guide.png',
                'section_guide' => [
                    'Title' => 'Merupakan judul utama dari section rekan kerja sama PCR website utama',
                    'Deskripsi' => 'Deskripsi singkat terkait bagian rekan kerja sama',
                ]
            ]
        ];
    }

    function index()
    {
        $this->title        = 'Kelola Landing Page';
        $this->activeMenu   = 'konten-main';
        $this->breadCrump[] = ['title' => 'Landing Page', 'link' => url()->current()];

        $getKonten = Konten::getDataDetail(['page_name' => 'landing'], get: false)->first();

        $dataPage = [
            'id' => encid($getKonten->konten_id)
        ];
        // dd([json_decode($getKonten->data_specification),json_decode($getKonten->data_values)]);
        $dataKonten = [];
        $dataMedia = [];
        $i = 0;
        foreach (json_decode($getKonten->data_specification) as $key => $value) {
            $temp = [];
            $temps = [];
            $temp['name'] = $value->name;
            $temp['type'] = $value->type;

            $dataValue = json_decode($getKonten->data_values);
            $tempData = [];
            foreach ($value->data as $section => $tag) {
                if ($value->type == 'hero') {
                    if (!empty($tag)) {
                        $tempValue = [];
                        foreach ($dataValue[$i]->$section as $data) {
                            $tempSubvalue = [];
                            foreach ($data as $key => $subdata) {
                                foreach ($subdata as $item) {
                                    $tempSubvalue[$key][] = ($key == 'media_id' && $item != '' ? encid($item) : $item);
                                    if ($key == 'media_id' && $item != '')
                                        $tempSubvalue['media'][] = serveMediaBase64($item);
                                }
                            }
                            $tempValue[] = $tempSubvalue;
                        }

                        $tempData[$section] = $tempValue;
                    } else {
                        $tempData[$section] = [];
                    }
                } else {
                    if (!empty($tag)) {
                        $tempTags = [];
                        foreach ($tag as $row) {
                            foreach ($dataValue[$i]->$section->$row as $data) {
                                $tempTags[$row][] = ($row == 'media_id' && $data != '' ? encid($data) : $data);
                                if ($row == 'media_id' && $data != '')
                                    $tempTags['media'][] = serveMediaBase64($data);
                            }
                        }

                        $tempData[$section] = $tempTags;
                    } else {
                        $tempData[$section] = [];
                    }
                }
            }
            $temp['data'] = $tempData;
            $temp['guide'] = isset($this->guides[strtolower($value->name)]) ? $this->guides[strtolower($value->name)] : [];

            $dataKonten[] = $temp;
            $i++;
        }

        $this->dataView([
            'dataKonten' => $dataKonten,
            'dataPage' => $dataPage
        ]);

        return $this->view('admin.konten.main');
    }

    function update(Request $req, $param1 = '', $param2 = '')
    {
        if ($param1 == '') {
            validate_and_response([
                'id' => ['Param Data', 'required'],
                // 'title_main' => ['Title Hero', 'required']
            ]);

            $currData = Konten::findOrFail(decid($req->input('id')));

            // if (count($req->post('title_main')) != count($req->post('media_id_hero')))
            //     abort(500, 'Update data gagal, seluruh isian harus diisi');

            // Perbarui data
            // Data section hero main-site
            $temp = [];
            $dataHero = [];
            foreach ($req->post('hero_key') as $index) {
                $tempTitle = [];
                $tempHero = [];
                foreach ($req->post('title_hero_' . $index) as $key => $value) {
                    $tempTitle[] = $value ? $value : '';
                }
                $tempHero['title'] = $tempTitle;
                $tempHero['media_id'][] = (isset($req->post('media_id_hero_' . $index)[0]) ? decid($req->post('media_id_hero_' . $index)[0]) : '123');

                $dataHero[] = $tempHero;
            }

            $temp[] = [
                'header' => [],
                'section' => $dataHero,
                'footer' => [],
            ];

            // Data section infografis main-site
            $dataInfo = [];
            foreach ($req->post('title_infografis') as $key => $value) {
                $dataInfo['title'][] = $value ? $value : '';
                $dataInfo['deskripsi'][] = $req->post('deskripsi_infografis')[$key] ? $req->post('deskripsi_infografis')[$key] : '';
                $dataInfo['media_id'][] = $req->post('media_id_infografis')[$key] ? decid($req->post('media_id_infografis')[$key]) : '';
            }

            $temp[] = [
                'header' => [],
                'section' => $dataInfo,
                'footer' => [],
            ];

            // Data section infografis main-site
            $dataJurusan = [];
            foreach ($req->post('title_jurusan') as $key => $value) {
                $dataJurusan['title'][] = $value ? $value : '';
                $dataJurusan['deskripsi'][] = $req->post('deskripsi_jurusan')[$key] ? $req->post('deskripsi_jurusan')[$key] : '';
            }

            $temp[] = [
                'header' => [],
                'section' => $dataJurusan,
                'footer' => [],
            ];

            // Data section pmb main-site
            $dataPmb = [];
            foreach ($req->post('title_pmb') as $key => $value) {
                $dataPmb['title'][] = $value ? $value : '';
                $dataPmb['deskripsi'][] = $req->post('deskripsi_pmb')[$key] ? $req->post('deskripsi_pmb')[$key] : '';
                $dataPmb['media_id'][] = $req->post('media_id_pmb')[$key] ? decid($req->post('media_id_pmb')[$key]) : '';
            }

            $temp[] = [
                'header' => [],
                'section' => $dataPmb,
                'footer' => [],
            ];

            // Data section partner main-site
            $dataRekan = [];
            foreach ($req->post('title_rekan') as $key => $value) {
                $dataRekan['title'][] = $value ? $value : '';
                $dataRekan['deskripsi'][] = $req->post('deskripsi_rekan')[$key] ? $req->post('deskripsi_rekan')[$key] : '';
            }

            $temp[] = [
                'header' => [],
                'section' => $dataRekan,
                'footer' => [],
            ];

            $data['data_values'] = $temp;

            // Simpan perubahan
            if ($currData->update($data)) {
                return response()->json([
                    'status'  => true,
                    'message' => 'Update data berhasil.',
                    'data'    => null,
                ]);
            } else {
                abort(500, 'Update data gagal, kesalahan database');
            }
        }

        // default
        else {
            abort(404, 'Halaman tidak ditemukan');
        }
    }
}
