<?php

/*
 * Author: @wahyudibinsaid
 * Created At: 2024-06-24 10:12:11
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event\EventKategori;
use App\Models\Media;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    function __construct()
    {
        /**
         * use this if needed
         */
        $this->activeRoot = 'media';
        $this->breadCrump[] = ['title' => 'Media', 'link' => url()->current()];
    }

    function index()
    {
        $this->title = 'Kelola Media';
        $this->activeMenu = 'media';
        // $this->breadCrump[] = ['title' => 'Label', 'link' => url()->current()];

        $builder = app('datatables.html');
        $dataTable = $builder->serverSide(true)->ajax(url('admin/media') . '/data/list')->columns([
            Column::make(['title' => 'Media', 'data' => 'nama_media', 'orderable' => false, 'className' => 'text-center', 'render' => 'card(full)']),
        ]);

        $this->dataView([
            'dataTable' => $dataTable
        ]);

        return $this->view('admin.media');
    }

    public function show($param1 = '', $param2 = '')
    {
        abort(404, 'Halaman tidak ditemukan');
    }

    function store(Request $req, $param1 = ''): JsonResponse
    {
        if ($param1 == '') {
            validate_and_response([
                'upload_file' => ['Media', 'required|file|max:2048']
            ]);

            $data['nama_media'] = clean_post('nama_media') ? clean_post('nama_media') : null;
            $data['alt_text_media'] = clean_post('alt_text_media') ? clean_post('alt_text_media') : null;

            $do_upload = uploadMedia('upload_file', 'media');

            // Simpan data
            if ($do_upload['status']) {
                return response()->json([
                    'status' => true,
                    'message' => 'Tambah data berhasil.',
                    'data' => $do_upload['data']
                ]);
            } else {
                abort(500, 'Tambah data gagal, ' . $do_upload['message']);
            }
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

            ]);

            $currData = Media::findOrFail(decid($req->input('id')));

            $info_media = json_decode($currData->info_media);

            $nama_media = clean_post('nama_media');
            $nama_media = ($nama_media == '' ? ($info_media['original_name'] ? $info_media['original_name'] : str_replace('media/', '', $currData->filepath_media)) : $nama_media);

            // Perbarui data
            $data['nama_media'] = $nama_media;

            // Simpan perubahan
            if ($currData->update($data)) {
                return response()->json([
                    'status' => true,
                    'message' => 'Update data berhasil.',
                    'data' => null,
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

    function destroy(Request $req, $param1 = ''): JsonResponse
    {
        if ($param1 == '') {
            validate_and_response([
                'id' => ['Parameter data', 'required'],
            ]);

            $currData = Media::findOrFail(decid($req->input('id')));
            unlink(storage_path('app/' . $currData->filepath_media));
            if ($currData->thumb_media)
                unlink(storage_path('app/' . $currData->thumb_media));

            $currData->delete();
            return response()->json(['status' => true, 'message' => 'Data berhasil dihapus']);
        }

        // default
        else {
            abort(404, 'Halaman tidak ditemukan');
        }
    }

    function data(Request $req, $param1 = '', $param2 = ''): JsonResponse
    {
        if ($param1 == 'detail') {
            validate_and_response([
                'id' => ['Parameter data', 'required'],
            ]);

            $currData = Media::findOrFail(decid($req->input('id')))->makeHidden(Media::$exceptEdit);

            $currData->id = $req->input('id');

            return response()->json(['status' => true, 'message' => 'Data loaded', 'data' => $currData]);
        } else if ($param1 == 'list') {
            // custom filter
            $filter = [];

            $data = DataTables::of(Media::where($filter))->rawColumns(columns: ['info_media'])->toArray();

            $start = $req->input('start');
            $resp = [];
            foreach ($data['data'] as $key => $value) {
                $dt = [];

                $dt['no'] = ++$start;
                $dt['id'] = encid($value['media_id']);
                $dt['nama_media'] = $value['nama_media'] ?? '-';
                $dt['filesize_media'] = convertFileSize($value['filesize_media']);
                $dt['ext_media'] = $value['ext_media'] ?? '-';
                $dt['filepath_media'] = $value['filepath_media'] ? route('app.media.show', ['id' => encid($value['media_id'])]) : null;
                $dt['thumb_media'] = $value['thumb_media'] ? route('app.media.thumb', ['id' => encid($value['media_id'])]) : null;
                $dt['created_at'] = tanggal($value['created_at']) . ' ' . date('H:i:s', strtotime($value['created_at']));
                $dt['updated_at'] = tanggal($value['updated_at']) . ' ' . date('H:i:s', strtotime($value['updated_at']));
                $dt['info_media'] = $value['info_media'] ? json_decode($value['info_media']) : '-';


                $id = encid($value['media_id']);

                $btnActions = [];
                $btnActions[] = ['action' => 'edit', 'attr' => ['jf-edit' => $id]];
                $btnActions[] = ['action' => 'delete', 'attr' => ['jf-delete' => $id]];
                $btnActions[] = [
                    'icon' => 'ki-outline ki-cloud-download text-primary',
                    'title' => 'Download media',
                    'attr' => [
                        'class' => 'btn-primary'
                    ],
                    'link' => $dt['filepath_media'] . '?disposition=attachment'
                ];

                $dataAction = [
                    'id' => $id,
                    'btn' => $btnActions
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
