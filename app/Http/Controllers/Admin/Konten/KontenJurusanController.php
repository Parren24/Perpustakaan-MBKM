<?php

/*
 * Author: @wahyudibinsaid
 * Created At: {{currTime}}
 */

namespace App\Http\Controllers\Admin\Konten;

use App\Http\Controllers\Controller;
use App\Models\Konten\KontenJurusan;
use App\Models\Media;
use App\Models\View\Jurusan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Column;

class KontenJurusanController extends Controller
{
    function __construct()
    {
        /**
         * use this if needed
         */
        $this->activeRoot   = 'konten-jurusan';
        $this->breadCrump[] = ['title' => 'Situs Jurusan', 'link' => url('admin/konten-jurusan')];
    }

    function index()
    {
        $this->title        = 'Kelola Situs Jurusan';
        $this->activeMenu   = 'konten-jurusan';
        // $this->breadCrump[] = ['title' => '', 'link' => url()->current()];

        $builder   = app('datatables.html');

        $dataTable = $builder->serverSide(true)->ajax(url('admin/konten-jurusan') . '/data/list')->columns([
            Column::make(['title' => 'Jurusan', 'data' => 'jurusan', 'orderable' => true, 'render' => 'card(full)']),
        ]);

        $this->dataView([
            'dataTable' => $dataTable,
        ]);

        return $this->view('admin.konten.jurusan.list');
    }

    public function show($param1 = '', $param2 = '')
    {
        if ($param1 != '') {
            $get_konten = KontenJurusan::getDataDetail(['kontenjurusan_id' => decid($param1)], false)->first();

            $this->title        = 'Kelola Situs ' . $get_konten->jurusan;
            $this->activeMenu   = 'konten-jurusan';
            $this->breadCrump[] = ['title' => $get_konten->jurusan, 'link' => url()->current()];

            $dataForm = [];
            $dataForm['id'] = $param1;

            foreach (json_decode($get_konten->hero_jurusan) as $key => $value) {
                if ($key == 'media_id') {
                    $temp_media = [];
                    if ($value) {
                        foreach ($value as $rows) {
                            if ($get_media = serveMediaBase64($rows)) {
                                $temp_media[] = [
                                    'id' => encid($rows),
                                    'base64' => $get_media
                                ];
                            }
                        }
                    }
                    $dataForm['media_hero'] = $temp_media;
                } else {
                    $dataForm[$key . '_hero'] = $value;
                }
            }

            foreach (json_decode($get_konten->sambutan_jurusan) as $key => $value) {
                if ($key == 'media_id') {
                    $temp_media = [];
                    if ($value) {
                        foreach ($value as $rows) {
                            if ($get_media = serveMediaBase64($rows)) {
                                $temp_media[] = [
                                    'id' => encid($rows),
                                    'base64' => $get_media
                                ];
                            }
                        }
                    }
                    $dataForm['media_sambutan'] = $temp_media;
                } else {
                    $dataForm[$key . '_sambutan'] = $value;
                }
            }

            foreach (json_decode($get_konten->tentang_jurusan) as $key => $value) {
                if ($key == 'media_id') {
                    $temp_media = [];
                    if ($value) {
                        foreach ($value as $rows) {
                            if ($get_media = serveMediaBase64($rows)) {
                                $temp_media[] = [
                                    'id' => encid($rows),
                                    'base64' => $get_media
                                ];
                            }
                        }
                    }
                    $dataForm['media_tentang'] = $temp_media;
                } else {
                    $dataForm[$key . '_tentang'] = $value;
                }
            }

            // dd($dataForm);
            $this->dataView([
                'dataForm' => $dataForm
            ]);

            return $this->view('admin.konten.jurusan.form');
        }

        // default
        else {
            abort(404, 'Halaman tidak ditemukan');
        }
    }

    function store(Request $req, $param1 = ''): JsonResponse
    {
        if ($param1 == 'media') {
            validate_and_response([
                'id' => ['Param Data', 'required'],
            ]);

            $uploadedFiles = $req->input('uploaded_files'); // Array of base64 strings
            $imagePaths = [];

            // Validate that the string is a valid base64 image
            $do_upload = uploadMediaBase64($uploadedFiles);

            // Simpan data
            if (!$do_upload['status']) {
                abort(500, 'Tambah data gagal, ' . $do_upload['message']);
            }

            return response()->json([
                'status'  => true,
                'message' => 'Tambah data berhasil.',
                'data' => $do_upload['data']
            ]);
        }

        // default
        else {
            abort(404, 'Halaman tidak ditemukan');
        }
    }

    function update(Request $req, $param1 = '', $param2 = ''): JsonResponse
    {
        if ($param1 == '') {
            validate_and_response([
                'id' => ['Param Data', 'required'],
                'moto_hero' => ['Hero', 'required'],
                'deskripsi_hero' => ['Hero', 'required'],
                'judul_sambutan' => ['Hero', 'required'],
                'isi_sambutan' => ['Hero', 'required'],
                'isi_tentang' => ['Hero', 'required'],
            ]);

            $currData =  KontenJurusan::findOrFail(decid($req->input('id')));

            $moto_hero = clean_post('moto_hero');
            $deskripsi_hero = clean_post('deskripsi_hero');
            $judul_sambutan = clean_post('judul_sambutan');
            $isi_sambutan = clean_post('isi_sambutan');
            $isi_tentang = clean_post('isi_tentang');

            $media_id_hero = $req->input('media_id_hero');
            $delete_media_id_hero = $req->input('delete_media_id_hero');

            $media_id_sambutan = $req->input('media_id_sambutan');
            $delete_media_id_sambutan = $req->input('delete_media_id_sambutan');

            $media_id_tentang = $req->input('media_id_tentang');
            $delete_media_id_tentang = $req->input('delete_media_id_tentang');

            $temp = [];
            foreach ($media_id_hero as $row) {
                $temp[] = decid($row);
            }
            $media_id_hero = $temp;

            if ($delete_media_id_hero) {
                foreach ($delete_media_id_hero as $row) {
                    destroyMedia(decid($row), true);
                }
            }

            $temp = [];
            foreach ($media_id_sambutan as $row) {
                $temp[] = decid($row);
            }
            $media_id_sambutan = $temp;

            if ($delete_media_id_sambutan) {
                foreach ($delete_media_id_sambutan as $row) {
                    destroyMedia(decid($row), true);
                }
            }

            $temp = [];
            foreach ($media_id_tentang as $row) {
                $temp[] = decid($row);
            }
            $media_id_tentang = $temp;

            if ($delete_media_id_tentang) {
                foreach ($delete_media_id_tentang as $row) {
                    destroyMedia(decid($row), true);
                }
            }

            $data['hero_jurusan'] = json_encode([
                'moto' => $moto_hero,
                'deskripsi' => $deskripsi_hero,
                'media_id' => $media_id_hero
            ]);
            $data['sambutan_jurusan'] = json_encode([
                'judul' => $judul_sambutan,
                'isi' => $isi_sambutan,
                'media_id' => $media_id_sambutan
            ]);
            $data['tentang_jurusan'] = json_encode([
                'isi' => $isi_tentang,
                'media_id' => $media_id_tentang
            ]);

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
            // validate_and_response([
            //     'id' => ['Parameter data', 'required'],
            // ]);

            // $currData = {{modelName}}::findOrFail(decid($req->input('id')))->makeHidden({{modelName}}::$exceptEdit);

            // $currData->id         = $req->input('id');

            // return response()->json(['status' => true, 'message' => 'Data loaded', 'data' => $currData]);
        } else if ($param1 == 'list') {
            // custom filter
            $filter = [];

            $data = DataTables::of(KontenJurusan::getDataDetail($filter, get: false))->toArray();

            $start = $req->input('start');
            $resp  = [];
            foreach ($data['data'] as $key => $value) {
                $dt = [];

                $dt['no']                    = ++$start;
                $dt['id']                    = encid($value['kontenjurusan_id']);
                $dt['jurusan']               = $value['jurusan'];
                $dt['alias_jurusan']         = $value['alias'];
                $dt['url_jurusan']           = 'https://' . strtolower($value['alias']) . '.pcr.ac.id';
                $dt['updated_at']            = $value['updated_at'] ? dateTime($value['updated_at']) : '-';

                $id = encid($value['kontenjurusan_id']);

                $dataAction = [
                    'id'  => $id,
                    'btn' => [
                        ['action' => 'edit', 'link' => url('admin/konten-jurusan/' . $id)],
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
