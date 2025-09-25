<?php

/*
 * Author: @wahyudibinsaid
 * Created At: 2024-06-24 10:12:11
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\Blade;
use App\Models\Master\KaryaJenis;

class DashboardController extends Controller
{
    function __construct()
    {
        /**
         * use this if needed
         */
        $this->activeRoot   = '';
        // $this->breadCrump[] = ['title' => 'Dashboard', 'link' => url('')];
    }

    function index()
    {
        $this->title        = 'Dashboard';
        $this->activeMenu   = 'dashboard';
        $this->breadCrump[] = ['title' => 'Dashboard', 'link' => url()->current()];

        // $builder   = app('datatables.html');
        // $dataTable = $builder->serverSide(true)->ajax(route('app.karya-jenis.data') . '/list')->columns([
        //     Column::make(['width' => '', 'title' => '', 'data' => 'action', 'orderable' => false, 'className' => 'text-nowrap text-end']),
        //     Column::make(['width' => '5%', 'title' => 'No', 'data' => 'no', 'orderable' => false, 'className' => 'text-center']),
        //     Column::make(['width' => '', 'title' => 'Kode Karya', 'data' => 'alias', 'orderable' => true]),
        //     Column::make(['width' => '', 'title' => 'Jenis Karya', 'data' => 'jenis_karya', 'orderable' => true]),
        //     Column::make(['width' => '', 'title' => 'Jenjang Pendidikan', 'data' => 'jenjang_pendidikan', 'orderable' => true]),

        // ]);

        $this->dataView([
            // 'dataTable' => $dataTable
        ]);

        return $this->view('admin.dashboard');
    }

    public function show($param1 = '', $param2 = '')
    {
        abort(404, 'Halaman tidak ditemukan');
    }

    function store(Request $req, $param1 = ''): JsonResponse
    {
        if ($param1 == '') {
            validate_and_response([
                'alias'              => ['Kode Karya', 'required'],
                'jenis_karya'        => ['Jenis Karya', 'required'],
                'jenjang_pendidikan' => ['Jenjang Pendidikan', 'required'],

            ]);

            // insert data
            $data['alias']              = clean_post('alias');
            $data['jenis_karya']        = clean_post('jenis_karya');
            $data['jenjang_pendidikan'] = clean_post('jenjang_pendidikan');
            $data['created_by']         = clean_post('created_by');
            $data['updated_by']         = clean_post('updated_by');
            $data['deleted_by']         = clean_post('deleted_by');


            // Simpan data
            if (KaryaJenis::create($data)) {
                return response()->json([
                    'status'  => true,
                    'message' => 'Tambah data berhasil.'
                ]);
            } else {
                abort(500, 'Tambah data gagal, kesalahan database');
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
                'id'                 => ['Param Data', 'required'],
                'alias'              => ['Kode Karya', 'required'],
                'jenis_karya'        => ['Jenis Karya', 'required'],
                'jenjang_pendidikan' => ['Jenjang Pendidikan', 'required'],

            ]);

            $currData = KaryaJenis::findOrFail(decid($req->input('id')));

            // Perbarui data
            $data['alias']              = clean_post('alias');
            $data['jenis_karya']        = clean_post('jenis_karya');
            $data['jenjang_pendidikan'] = clean_post('jenjang_pendidikan');
            $data['created_by']         = clean_post('created_by');
            $data['updated_by']         = clean_post('updated_by');
            $data['deleted_by']         = clean_post('deleted_by');


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

    function destroy(Request $req, $param1 = ''): JsonResponse
    {
        if ($param1 == '') {
            validate_and_response([
                'id' => ['Parameter data', 'required'],
            ]);

            $currData = KaryaJenis::findOrFail(decid($req->input('id')));

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

            $currData = KaryaJenis::findOrFail(decid($req->input('id')))->makeHidden(KaryaJenis::$exceptEdit);

            $currData->id = $req->input('id');

            return response()->json(['status' => true, 'message' => 'Data loaded', 'data' => $currData]);
        } else if ($param1 == 'list') {
            // custom filter
            $filter = [];

            $data = DataTables::of(KaryaJenis::getDataDetail($filter, mode: 'datatable'))->toArray();

            $start = $req->input('start');
            $resp  = [];
            foreach ($data['data'] as $key => $value) {
                $dt = [];

                $dt['no']                 = ++$start;
                $dt['alias']              = $value['alias'];
                $dt['jenis_karya']        = $value['jenis_karya'];
                $dt['jenjang_pendidikan'] = $value['jenjang_pendidikan'];


                $id = encid($value['']);

                $dataAction = [
                    'id'  => $id,
                    'btn' => [
                        ['action' => 'edit', 'attr' => ['jf-edit' => $id]],
                        ['action' => 'delete', 'attr' => ['jf-delete' => $id]],
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
