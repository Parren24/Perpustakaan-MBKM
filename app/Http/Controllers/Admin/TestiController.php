<?php

/*
 * Author: @wahyudibinsaid
 * Created At: 2024-06-24 10:12:11
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testi\Testi;
use App\Models\Testi\TestiHasKategori;
use App\Models\Testi\TestiKategori;
use App\Models\View\Jurusan;
use App\Models\View\Prodi;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;

class TestiController extends Controller
{
    function __construct()
    {
        /**
         * use this if needed
         */
        $this->activeRoot   = 'konten';
        $this->breadCrump[] = ['title' => 'Konten', 'link' => url('#')];
    }

    function index()
    {
        $this->title        = 'Kelola Testimoni Alumni';
        $this->activeMenu   = 'testi';
        $this->breadCrump[] = ['title' => 'Testimoni', 'link' => url()->current()];

        $builder   = app('datatables.html');
        $dataTable = $builder->serverSide(true)->ajax(route('app.testi.data') . '/list')->columns([
            Column::make(['width' => '', 'title' => '', 'data' => 'action', 'orderable' => false, 'className' => 'text-nowrap text-end']),
            Column::make(['width' => '5%', 'title' => 'No', 'data' => 'no', 'orderable' => false, 'className' => 'text-center']),
            Column::make(['width' => '5%', 'title' => 'Status', 'className' => 'text-end', 'data' => 'status_testi', 'orderable' => true, 'render' => 'renderStatus(full.status_testi)']),
            Column::make(['width' => '5%', 'title' => 'Angkatan', 'data' => 'angkatan']),
            Column::make(['width' => '10%', 'title' => 'Program Studi', 'data' => 'prodi']),
            Column::make(['width' => '40%', 'title' => 'Nama Alumni', 'data' => 'nama_alumni']),
            Column::make(['width' => '15%', 'title' => 'Tempat Kerja', 'data' => 'tempat_kerja_alumni']),
            Column::make(['width' => '15%', 'title' => 'P{osisi', 'data' => 'posisi_alumni']),
        ]);

        $temp = [];
        $filter = [];
        foreach (Prodi::where($filter)->get() as $row) {
            $temp[] = [
                'id' => encid($row->prodi_id),
                'text' => $row->jenjang_pendidikan . '-' . $row->nama_prodi . ($row->konsentrasi ? ' ' . $row->konsentrasi : '')
            ];
        }
        $dataProdi = $temp;

        $this->dataView([
            'dataTable' => $dataTable,
            'dataProdi'     => $dataProdi,
        ]);

        return $this->view('admin.testi.list');
    }

    public function show($param1 = '', $param2 = '')
    {
        if ($param1 == 'form') {
            $this->title        = 'Kelola Testimoni Alumni';
            $this->activeMenu   = 'testi';
            $this->breadCrump[] = ['title' => 'Form', 'link' => url()->current()];

            $get_testi = Testi::getDataDetail(['testi_id' => decid($param2)])->first();

            $temp = [];
            $filter = [];
            foreach (Prodi::where($filter)->get() as $row) {
                $temp[] = [
                    'id' => encid($row->prodi_id),
                    'text' => $row->jenjang_pendidikan . '-' . $row->nama_prodi . ($row->konsentrasi ? ' ' . $row->konsentrasi : '')
                ];
            }
            $dataProdi = $temp;

            $temp = [];
            foreach (TestiKategori::all() as $row) {
                $temp[] = [
                    'id'    => encid($row->testikategori_id),
                    'text'  => $row->nama_kategori
                ];
            }
            $dataKategori = $temp;

            //get testi kategori
            $testi_kategori = [];
            if ($get_testi->testi_kategori) {
                foreach (json_decode($get_testi->testi_kategori) as $row) {
                    $testi_kategori[] = encid($row->testikategori_id);
                }
            }

            $dataTesti = [
                'id'                    => encid($get_testi->testi_id),
                'nama_alumni'           => $get_testi->nama_alumni,
                'angkatan'              => $get_testi->angkatan,
                'prodi_id'              => encid($get_testi->prodi_id),
                'isi_testi'             => $get_testi->isi_testi,
                'status_testi'          => $get_testi->status_testi,
                'posisi_alumni'         => $get_testi->posisi_alumni,
                'tempat_kerja_alumni'   => $get_testi->tempat_kerja_alumni,
                'media_id_alumni'       => $get_testi->media_id_alumni ? encid($get_testi->media_id_alumni) : null,
                'media_cover'           => $get_testi->media_id_alumni ? route('app.media.show', ['id' => encid($get_testi->media_id_alumni)]) : null,
                'testi_kategori'        => $testi_kategori
            ];

            $this->dataView([
                'dataKategori'  => $dataKategori,
                'dataTesti'     => $dataTesti,
                'dataProdi'     => $dataProdi,
            ]);

            return $this->view('admin.testi.testi-form');
        } else if ($param1 == 'kategori') {
            $this->title        = 'Kelola Kategori Testi Alumni';
            $this->activeMenu   = 'testi-kategori';
            $this->breadCrump[] = ['title' => 'Kategori', 'link' => url()->current()];

            $builder   = app('datatables.html');
            $dataTable = $builder->serverSide(true)->ajax(route('app.testi.data') . '/kategori-list')->columns([
                Column::make(['width' => '', 'title' => '', 'data' => 'action', 'orderable' => false, 'className' => 'text-nowrap text-end']),
                Column::make(['width' => '5%', 'title' => 'No', 'data' => 'no', 'orderable' => false, 'className' => 'text-center']),
                Column::make(['width' => '7%', 'title' => 'Kode', 'data' => 'kode_kategori', 'orderable' => true]),
                Column::make(['width' => '40%', 'title' => 'Kategori', 'data' => 'nama_kategori', 'orderable' => true]),
                Column::make(['width' => '40%', 'title' => 'Deskripsi', 'data' => 'deskripsi_kategori', 'orderable' => true]),
                // Column::make(['width' => '40%', 'title' => 'Kategori EN', 'data' => 'nama_kategori_en', 'orderable' => true]),
            ]);

            $this->dataView([
                'dataTable' => $dataTable
            ]);

            return $this->view('admin.testi.testi-kategori');
        }

        // default
        else {
            abort(404, 'Halaman tidak ditemukan');
        }
    }

    function store(Request $req, $param1 = ''): JsonResponse
    {
        if ($param1 == '') {
            validate_and_response([
                'prodi_id'  => ['Prodi', 'required'],
                'nama_alumni'  => ['Alumni', 'required'],
                'angkatan'   => ['Angkatan', 'required'],

            ]);

            // insert data
            $data['prodi_id'] = decid($req->post('prodi_id'));
            $data['nama_alumni'] = clean_post('nama_alumni');
            $data['angkatan'] = clean_post('angkatan');
            $data['status_testi'] = 'draft';

            // Simpan data
            if ($inserted = Testi::create($data)) {
                return response()->json([
                    'status'  => true,
                    'message' => 'Tambah data berhasil.',
                    'data'    => ['id' => encid($inserted->testi_id)]
                ]);
            } else {
                abort(500, 'Tambah data gagal, kesalahan database');
            }
        } else if ($param1 == 'kategori') {
            validate_and_response([
                'kode_kategori'        => ['Kode Label', 'required'],
                'nama_kategori'        => ['Nama Label ID', 'required'],
                // 'nama_kategori_en'     => ['Nama Label EN', 'required'],

            ]);

            // insert data
            $data['kode_kategori']    = strtoupper(clean_post('kode_kategori'));
            $data['nama_kategori']    = clean_post('nama_kategori');
            // $data['nama_kategori_en'] = clean_post('nama_kategori_en');
            $data['deskripsi_kategori'] = clean_post('deskripsi_kategori');


            // Simpan data
            if (TestiKategori::create($data)) {
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
                'id'                  => ['Param Data', 'required'],
                'nama_alumni'         => ['Alumni', 'required'],
                'status_testi'        => ['Status', 'required'],
                'angkatan'            => ['Angkatan', 'required'],
                'prodi_id'            => ['Prodi', 'required'],
                'isi_testi'           => ['Isi', 'required'],
                'tempat_kerja_alumni' => ['Tempat Kerja', 'required'],
                'posisi_alumni'       => ['Posisi', 'required'],

            ]);

            if (!$req->has('testi_kategori'))
                abort(422, 'Kolom Kategori harus diisi');

            $currData = Testi::findOrFail(decid($req->input('id')));

            if ($req->hasFile('upload_file')) {
                $do_upload = uploadMedia('upload_file');
                if (!$do_upload['status'])
                    abort(500, 'Update data gagal, ' . $do_upload['message']);

                $data['media_id_alumni']     = decid($do_upload['data']['media_id']);
            }

            // insert data
            $data['nama_alumni']         = clean_post('nama_alumni');
            $data['status_testi']        = clean_post('status_testi');
            $data['angkatan']            = clean_post('angkatan');
            $data['prodi_id']            = decid($req->post('prodi_id'));
            $data['isi_testi']           = clean_post('isi_testi');
            $data['tempat_kerja_alumni'] = clean_post('tempat_kerja_alumni');
            $data['posisi_alumni']       = clean_post('posisi_alumni');

            // Simpan data
            DB::beginTransaction();
            try {
                $currData->update($data);

                TestiHasKategori::where('testi_id', $currData->testi_id)->forceDelete();
                foreach ($req->testi_kategori as $row) {
                    $data = [];
                    $data['testi_id'] = $currData->testi_id;
                    $data['testikategori_id'] = decid($row);

                    TestiHasKategori::create($data);
                }

                DB::commit();
                return response()->json([
                    'status'  => true,
                    'message' => 'Update data berhasil.',
                    'data'    => ['id' => encid($currData->testi_id)]
                ]);
            } catch (\Throwable $th) {
                DB::rollBack();
                abort(404, 'Update data gagal, ' . $th->getMessage());
            }
            if ($currData->update($data)) {
                return response()->json([
                    'status'  => true,
                    'message' => 'Update data berhasil.',
                    'data'    => ['id' => encid($currData->testi_id)]
                ]);
            } else {
                abort(500, 'Update data gagal, kesalahan database');
            }
        } else if ($param1 == 'kategori') {
            validate_and_response([
                'id'                 => ['Param Data', 'required'],
                'kode_kategori'         => ['Kode Label', 'required'],
                'nama_kategori'         => ['Nama Label ID', 'required'],
                // 'nama_kategori_en'      => ['Nama Label EN', 'required'],

            ]);

            $currData = TestiKategori::findOrFail(decid($req->input('id')));

            // Perbarui data
            $data['kode_kategori']    = strtoupper(clean_post('kode_kategori'));
            $data['nama_kategori']    = clean_post('nama_kategori');
            // $data['nama_kategori_en'] = clean_post('nama_kategori_en');
            $data['deskripsi_kategori'] = clean_post('deskripsi_kategori');


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

            $currData = Testi::findOrFail(decid($req->input('id')));

            DB::beginTransaction();
            try {
                TestiHasKategori::where('testi_id', $currData->testi_id)->forceDelete();

                $currData->delete();

                DB::commit();
                return response()->json(['status' => true, 'message' => 'Data berhasil dihapus']);
            } catch (\Throwable $th) {
                DB::rollBack();
                abort(404, 'Hapus data gagal, ' . $th->getMessage());
            }
            $currData->delete();
            return response()->json(['status' => true, 'message' => 'Data berhasil dihapus']);
        } else if ($param1 == 'kategori') {
            validate_and_response([
                'id' => ['Parameter data', 'required'],
            ]);

            $currData = TestiKategori::findOrFail(decid($req->input('id')));

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

            $currData = Testi::findOrFail(decid($req->input('id')))->makeHidden(Testi::$exceptEdit);

            $currData->id = $req->input('id');
            $currData->testikategori_id = encid($currData->testikategori_id);

            return response()->json(['status' => true, 'message' => 'Data loaded', 'data' => $currData]);
        } else if ($param1 == 'kategori-detail') {
            validate_and_response([
                'id' => ['Parameter data', 'required'],
            ]);

            $currData = TestiKategori::findOrFail(decid($req->input('id')))->makeHidden(TestiKategori::$exceptEdit);

            $currData->id = $req->input('id');

            return response()->json(['status' => true, 'message' => 'Data loaded', 'data' => $currData]);
        } else if ($param1 == 'list') {
            // custom filter
            $filter = [];

            $data = DataTables::of(Testi::getDataDetail($filter))->toArray();

            $start = $req->input('start');
            $resp  = [];
            foreach ($data['data'] as $key => $value) {
                $dt = [];

                $dt['no']                   = ++$start;
                $dt['nama_alumni']          = $value['nama_alumni'];
                $dt['status_testi']         = $value['status_testi'];
                $dt['tempat_kerja_alumni']  = $value['tempat_kerja_alumni'] ?? '-';
                $dt['posisi_alumni']        = $value['posisi_alumni'] ?? '-';
                $dt['angkatan']             = $value['angkatan'] ?? '-';
                $dt['prodi']                = $value['nama_prodi'] ? $value['jenjang_pendidikan'] . '-' . $value['nama_prodi'] : '-';


                $id = encid($value['testi_id']);

                $dataAction = [
                    'id'  => $id,
                    'btn' => [
                        ['action' => 'edit', 'link' => route('app.testi.show', ['param1' => 'form', 'param2' => $id])],
                        ['action' => 'delete', 'attr' => ['jf-delete' => $id]],
                    ]
                ];

                $dt['action'] = Blade::render('<x-btn.actiontable :id="$id" :btn="$btn"/>', $dataAction);

                $resp[] = $dt;
            }
            $data['data'] = $resp;


            return response()->json($data);
        } else if ($param1 == 'kategori-list') {
            // custom filter
            $filter = [];

            $data = DataTables::of(TestiKategori::where($filter))->toArray();

            $start = $req->input('start');
            $resp  = [];
            foreach ($data['data'] as $key => $value) {
                $dt = [];

                $dt['no']                 = ++$start;
                $dt['kode_kategori']         = $value['kode_kategori'] ?? '-';
                $dt['nama_kategori']         = $value['nama_kategori'] ?? '-';
                $dt['deskripsi_kategori']    = $value['deskripsi_kategori'] ?? '-';
                $dt['nama_kategori_en']      = $value['nama_kategori_en'] ?? '-';
                $dt['deskripsi_kategori_en'] = $value['deskripsi_kategori_en'] ?? '-';


                $id = encid($value['testikategori_id']);

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
