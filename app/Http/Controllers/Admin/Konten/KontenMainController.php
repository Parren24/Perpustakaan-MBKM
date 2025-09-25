<?php

/*
 * Author: @wahyudibinsaid
 * Created At: {{currTime}}
 */

namespace App\Http\Controllers\Admin\Konten;

use App\Http\Controllers\Controller;
use App\Models\Konten\KontenConfig;
use App\Models\Konten\KontenMain;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Column;

class KontenMainController extends Controller
{
    protected $configAttr = [];

    function __construct()
    {
        /**
         * use this if needed
         */
        $this->activeRoot   = 'konten-main';
        $this->breadCrump[] = ['title' => '', 'link' => url('')];

        $this->configAttr = [
            'hero_main' => [
                'title' => 'Hero',
                'desc' => null
            ],
            'infografis_main' => [
                'title' => 'Infografis',
                'desc' => null
            ],
            'jurusan_main' => [
                'title' => 'Jurusan',
                'desc' => null
            ],
            'pmb_main' => [
                'title' => 'Penerimaan Mahasiswa',
                'desc' => null
            ],
            'partner_main' => [
                'title' => 'Rekan',
                'desc' => null
            ],
        ];
    }

    function index()
    {
        $this->title        = 'Kelola Main Site';
        $this->activeMenu   = 'konten-main';
        $this->breadCrump[] = ['title' => '', 'link' => url()->current()];

        $getConfig = KontenConfig::getDataDetail(['level' => 'main-site'], get: false)->first();
        $dataConfig = $getConfig && $getConfig->sequence_konten ? json_decode($getConfig->sequence_konten) : [];

        $dataSection = [];

        $temp = [];
        foreach ($dataConfig as $row) {
            $temp[] = [
                'config' => $row,
                'title' => $this->configAttr[$row]['title'],
                'desc' => $this->configAttr[$row]['desc']
            ];
        }
        $dataSection = $temp;

        $currData = KontenMain::getDataDetail(['level' => 'main-site'])->first();

        $dataPage = [
            'id' => encid($currData->kontenmain_id)
        ];

        $decoded = json_decode($currData->hero_main, true);
        // if (is_string($decoded)) {
        //     $decoded = json_decode($decoded, true);
        // }

        $temp = [];
        if (is_array($decoded)) {
            foreach ($decoded as $row) {
                $temp[] = [
                    'title' => $row['title'] ?? '',
                    'media' => $row['media_id'] && $row['media_id'] != '' ? serveMediaBase64($row['media_id']) : null,
                    'media_id' => $row['media_id'] && $row['media_id'] != '' ? encid($row['media_id']) : '',
                ];
            }
        }
        $dataKonten['hero_main'] = $temp;

        // section infografis
        $decoded = json_decode($currData->infografis_main, true);

        $temp = [];
        $temp['title'] = $decoded['title'] ?? '';
        $temp['deskripsi'] = $decoded['deskripsi'] ?? '';
        $temp['media'] = $decoded['media_id'] && $decoded['media_id'] ? serveMediaBase64($decoded['media_id']) : null;
        $temp['media_id'] = $decoded['media_id'] && $decoded['media_id'] ? encid($decoded['media_id']) : '';
        $dataKonten['infografis_main'] = $temp;

        //section jurusan
        $decoded = json_decode($currData->jurusan_main, true);

        $temp = [];
        $temp['title'] = $decoded['title'] ?? '';
        $temp['deskripsi'] = $decoded['deskripsi'] ?? '';
        $dataKonten['jurusan_main'] = $temp;

        //section pmb
        $decoded = json_decode($currData->pmb_main, true);

        $temp = [];
        $temp['title'] = $decoded['title'] ?? '';
        $temp['deskripsi'] = $decoded['deskripsi'] ?? '';
        $temp['media'] = $decoded['media_id'] && $decoded['media_id'] ? serveMediaBase64($decoded['media_id']) : null;
        $temp['media_id'] = $decoded['media_id'] && $decoded['media_id'] ? encid($decoded['media_id']) : null;
        $dataKonten['pmb_main'] = $temp;

        //section jurusan
        $decoded = json_decode($currData->partner_main, true);

        $temp = [];
        $temp['title'] = $decoded['title'] ?? '';
        $temp['deskripsi'] = $decoded['deskripsi'] ?? '';
        $dataKonten['partner_main'] = $temp;

        $this->dataView([
            'dataSection' => $dataSection,
            'dataPage' => $dataPage,
            'dataKonten' => $dataKonten,
        ]);

        return $this->view('admin.konten.main');
    }

    // public function show($param1 = '', $param2 = '')
    // {
    //     abort(404, 'Halaman tidak ditemukan');
    // }

    // function store(Request $req, $param1 = ''): JsonResponse
    // {
    //     if ($param1 == '') {
    //         validate_and_response([
    //             {{validationField}}
    //         ]);

    //         // insert data
    //         {{dataStore}}

    //         // Simpan data
    //         if ({{modelName}}::create($data)) {
    //             return response()->json([
    //                 'status'  => true,
    //                 'message' => 'Tambah data berhasil.'
    //             ]);
    //         } else {
    //             abort(500, 'Tambah data gagal, kesalahan database');
    //         }
    //     }

    //     // default
    //     else {
    //         abort(404, 'Halaman tidak ditemukan');
    //     }
    // }

    function update(Request $req, $param1 = '', $param2 = ''): JsonResponse
    {
        if ($param1 == '') {
            validate_and_response([
                'id' => ['Param Data', 'required'],
                'title_main' => ['Title Hero', 'required']
            ]);

            $currData = KontenMain::findOrFail(decid($req->input('id')));

            if (count($req->post('title_main')) != count($req->post('media_id_hero')))
                abort(500, 'Update data gagal, seluruh isian harus diisi');

            // Perbarui data
            // Data section hero main-site
            $temp = [];
            $i = 0;
            foreach ($req->post('title_main') as $key => $value) {
                $temp[] = [
                    'title' => $value,
                    'media_type' => 'image',
                    'media_id' => decid($req->post('media_id_hero')[$i])
                ];

                $i++;
            }
            $data['hero_main'] = $temp;

            // Data section infografis main-site
            $temp = [
                'title' => $req->post('title_infografis'),
                'deskripsi' => $req->post('deskripsi_infografis'),
                'media_id' => decid($req->post('media_id_infografis'))
            ];

            $data['infografis_main'] = $temp;

            // Data section infografis main-site
            $temp = [
                'title' => $req->post('title_jurusan'),
                'deskripsi' => $req->post('deskripsi_jurusan'),
            ];

            $data['jurusan_main'] = $temp;

            // Data section pmb main-site
            $temp = [
                'title' => $req->post('title_pmb'),
                'deskripsi' => $req->post('deskripsi_pmb'),
                'media_id' => decid($req->post('media_id_pmb'))
            ];

            $data['pmb_main'] = $temp;

            // Data section partner main-site
            $temp = [];
            $temp = [
                'title' => $req->post('title_partner'),
                'deskripsi' => $req->post('deskripsi_partner'),
            ];

            $data['partner_main'] = $temp;

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

    // function destroy(Request $req, $param1 = ''): JsonResponse
    // {
    //     if ($param1 == '') {
    //         validate_and_response([
    //             'id' => ['Parameter data', 'required'],
    //         ]);

    //         $currData = {{modelName}}::findOrFail(decid($req->input('id')));

    //         $currData->delete();
    //         return response()->json(['status' => true, 'message' => 'Data berhasil dihapus']);
    //     }

    //     // default
    //      else {
    //         abort(404, 'Halaman tidak ditemukan');
    //     }
    // }

    function data(Request $req, $param1 = '', $param2 = ''): JsonResponse
    {
        if ($param1 == 'detail') {
            validate_and_response([
                'id' => ['Parameter data', 'required'],
            ]);

            $temp = [];
            $column = $req->post('id');

            $currData = KontenMain::getDataDetail(['level' => 'main-site'])->first();
            $getIndexes = json_decode($currData->$column);

            $temp['id']             = encid($currData->kontenmain_id);
            $temp['section']        = $column;
            $temp['section_title']  = $this->configAttr[$column]['title'];
            $temp['data']           = $getIndexes;
            // foreach ($getIndexes as $key => $value) {
            //     $temp[$key] = $value;
            // }

            return response()->json(['status' => true, 'message' => 'Data loaded', 'data' => $temp]);
        } else if ($param1 == 'list') {
            // custom filter

            $filter = [];

            $getConfig = KontenConfig::getDataDetail(['level' => 'main-site'], get: false)->first();
            $dataConfig = $getConfig && $getConfig->sequence_konten ? json_decode($getConfig->sequence_konten) : [];
            $temp = [];

            foreach ($dataConfig as $row) {
                $temp[] = [
                    'config' => $row
                ];
            }
            $dataConfig = $temp;

            $data = DataTables::of($dataConfig)->toArray();

            $start = $req->input('start');
            $resp  = [];
            foreach ($data['data'] as $key => $value) {
                $dt = [];

                $dt['no']                    = ++$start;
                $dt['id']                    = $value['config'];
                $dt['config']                = $value['config'];
                $dt['config_title']          = $this->configAttr[$value['config']]['title'];
                $dt['config_desc']           = $this->configAttr[$value['config']]['desc'];

                $id = $value['config'];

                $dataAction = [
                    'id'  => $id,
                    'btn' => [
                        ['action' => 'edit', 'attr' => ['jf-edit' => $id]],
                        // ['action' => 'delete', 'attr' => ['jf-delete' => $id]],
                    ]
                ];

                $dt['action'] = Blade::render('<x-btn.actiontable :id="$id" :btn="$btn"/>', $dataAction);

                $resp[] = $dt;
            }
            $data['data'] = $resp;


            return response()->json($data);
        }

        // default
        else {
            abort(404, 'Halaman tidak ditemukan');
        }
    }
}
/* This controller generate by @wahyudibinsaid laravel best practices snippets */
