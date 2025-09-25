<?php

/*
 * Author: @wahyudibinsaid
 * Created At: {{currTime}}
 */

namespace App\Http\Controllers\Admin\Konten;

use App\Http\Controllers\Controller;
use App\Models\Konten\KontenJurusan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Column;
use App\Models\Konten\KontenPage;
use App\Models\Konten\KontenProdi;
use App\Models\View\Jurusan;
use App\Models\View\Prodi;

class KontenPageController extends Controller
{
    function __construct()
    {
        /**
         * use this if needed
         */
    }

    function index()
    {
        abort(404, 'Halaman tidak ditemukan');
    }

    public function show($param1 = '', $param2 = '')
    {
        $allowedParam = ['main-site', 'jurusan-site', 'prodi-site'];

        if (in_array($param1, $allowedParam)) {
            $dataPage = [];

            if ($param1 == 'main-site') {
                if ($param2 != '')
                    abort(404, 'Halaman tidak ditemukan');

                $this->activeRoot   = 'konten-main';
                $this->activeMenu   = 'page';

                $this->breadCrump[] = ['title' => 'Situs Utama', 'link' => url('')];
                $this->title        = 'Kelola Halaman Statis Situs Utama';
                $this->breadCrump[] = ['title' => 'Halaman Statis', 'link' => url()->current()];

                $dataPage['pageName'] = 'Halaman Statis Situs Utama';
                $dataPage['level'] = $param1;
                $dataPage['level_id'] = '';
            } else {
                if ($param1 == 'jurusan-site') {
                    $getJurusan = Jurusan::find(decid($param2));

                    $this->activeRoot   = 'konten-jurusan';
                    $this->activeMenu   = 'static-page';
                    $this->title        = 'Kelola Halaman Statis ' . $getJurusan->jurusan;

                    $this->breadCrump[] = ['title' => 'Situs Jurusan', 'link' => url('admin/konten-jurusan')];
                    $this->breadCrump[] = ['title' => 'Halaman Statis', 'link' => url()->current()];

                    $dataPage['pageName'] = 'Halaman Statis ' . $getJurusan->jurusan;
                    $dataPage['targetPage'] = $param1;
                    $dataPage['scopeTargetPage'] = $param2;
                } else if ($param1 == 'prodi-site') {
                    $getProdi = Prodi::find(decid($param2));

                    $this->activeRoot   = 'konten-prodi';
                    $this->activeMenu   = 'static-page';
                    $this->title        = 'Kelola Halaman Statis ' . $getProdi->jenjang_pendidikan . '-' . $getProdi->nama_prodi;

                    $this->breadCrump[] = ['title' => 'Situs Program Studi', 'link' => url('admin/konten-prodi')];
                    $this->breadCrump[] = ['title' => 'Halaman Statis', 'link' => url()->current()];

                    $dataPage['pageName'] = 'Halaman Statis ' . $getProdi->jenjang_pendidikan . '-' . $getProdi->nama_prodi;
                    $dataPage['targetPage'] = $param1;
                    $dataPage['scopeTargetPage'] = $param2;
                }
            }

            if ($param1 == 'main-site' || ($param1 != 'main-site' && $param2 != '')) {
                $builder   = app('datatables.html');
                $dataTable = $builder->serverSide(true)->ajax(route('app.konten.konten-page.data') . '/list')->columns([
                    Column::make(['width' => '', 'title' => '', 'data' => 'action', 'orderable' => false, 'className' => 'text-nowrap text-end']),
                    Column::make(['width' => '5%', 'title' => 'No', 'data' => 'no', 'orderable' => false, 'className' => 'text-center']),
                    Column::make(['width' => '7%', 'title' => 'Source', 'data' => 'level', 'orderable' => true, 'render' => '`
                    <div class="d-flex flex-column">
                        <small class="fw-bold text-primary">${full.level}</small>
                        <small>${full.level_id ? full.level_id : `-`}</small>
                    </div>
                `']),
                    Column::make(['width' => '10%', 'title' => 'Status', 'data' => 'created_by', 'orderable' => true, 'render' => '`
                    <div class="d-flex align-items-center">
                        <div class="d-flex justify-content-start flex-column fs-8">
                            <div>${renderStatus(full.status_page)}</div>
                            <a href="' . url('') . '/${full.kode_tipe}" target="_blank">
                                <div class="d-flex flex-row align-items-center">
                                    go to page<i class="bi bi-box-arrow-up-right fs-9 ms-1 text-primary"></i>
                                </div>
                            </a>
                        </div>
                    </div>
                `']),
                    Column::make(['width' => '60%', 'title' => 'Judul', 'data' => 'judul_post', 'orderable' => true, 'render' => '`
                    <div class="d-flex align-items-center">
                        <div class="d-flex justify-content-start flex-column">
                            ${full.title_page}
                            <small class="fst-italic">${full.subtitle_page}</small>
                        </div>
                    </div>
                `']),
                    Column::make(['width' => '15%', 'title' => 'Last Modified', 'data' => 'updated_at', 'orderable' => true, 'render' => '`
                    <div class="d-flex align-items-center">
                        <div class="d-flex justify-content-start flex-column fs-8">
                            ${full.updated_at}
                            <span>Last update by : ${full.updated_by}</span>
                        </div>
                    </div>
                `']),
                ]);

                $this->dataView([
                    'dataPage'      => $dataPage,
                    'dataTable'     => $dataTable,
                ]);

                return $this->view('admin.konten.page');
            } else {
                abort(404, 'Halaman tidak ditemukan');
            }
        } else if ($currData = KontenPage::find(decid($param1))) {

            $this->activeRoot   = 'konten-main';
            $this->activeMenu   = 'page';

            $this->breadCrump[] = ['title' => 'Situs Utama', 'link' => url('')];
            $this->title        = 'Kelola Halaman ' . $currData->title_page;
            $this->breadCrump[] = ['title' => 'Konten Static', 'link' => route('app.konten.konten-page.show', ['param1' => $currData->level])];
            $this->breadCrump[] = ['title' => $currData->title_page, 'link' => url()->current()];

            $dataKonten['pageName'] = 'Halaman ' . $currData->title_page;
            $dataKonten['level'] = $currData->level;
            $dataKonten['level_id'] = '';

            $this->dataView([
                'dataKonten'      => $dataKonten,
            ]);

            return $this->view('admin.konten.page_form');
        } else {
            abort(404, 'Halaman tidak ditemukan');
        }
    }

    function store(Request $req, $param1 = ''): JsonResponse
    {
        abort(404, 'Halaman tidak ditemukan');
    }

    function update(Request $req, $param1 = '', $param2 = ''): JsonResponse
    {
        if ($param1 == '') {
            validate_and_response([
                'id' => ['Param Data', 'required'],
                'title_page' => ['Judul', 'required'],
                'konten_page' => ['Konten', 'required'],
                'meta_keyword_page' => ['Meta Keywords', 'required'],
                'meta_desc_page' => ['Meta Description', 'required'],
            ]);

            $currData = KontenPage::findOrFail(decid($req->input('id')));

            // Perbarui data

            // $data['title_page']         = clean_post('title_page');
            $data['subtitle_page']      = clean_post('subtitle_page');
            $data['konten_page']        = clean_post('konten_page');
            $data['meta_keyword_page']  = clean_post('meta_keyword_page');
            $data['meta_desc_page']     = clean_post('meta_desc_page');
            $data['status_page']        = clean_post('status_page') ? clean_post('status_page') : 'published';

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

    //         $currData = KontenPage::findOrFail(decid($req->input('id')));

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

            $currData = KontenPage::findOrFail(decid($req->input('id')))->makeHidden(KontenPage::$exceptEdit);

            $currData->id         = $req->input('id');

            return response()->json(['status' => true, 'message' => 'Data loaded', 'data' => $currData]);
        } else if ($param1 == 'list') {
            // custom filter
            $filter = [];

            if ($req->has('level') && $req->level != '')
                $filter['level'] = clean_post('level');

            if ($req->has('level_id') && $req->level_id != '')
                $filter['level_id'] = $req->level_id;

            $data = DataTables::of(KontenPage::getDataDetail($filter, get: false))->toArray();

            $start = $req->input('start');
            $resp  = [];
            foreach ($data['data'] as $key => $value) {
                $dt = [];

                $dt['no']                    = ++$start;
                $dt['level']                 = $value['level'];
                $dt['level_id']              = $value['level_id'];
                $dt['kode_tipe']             = $value['kode_tipe'];
                $dt['nama_tipe']             = $value['nama_tipe'];
                $dt['title_page']            = $value['title_page'];
                $dt['subtitle_page']         = $value['subtitle_page'] ? $value['subtitle_page'] : '-';
                $dt['status_page']           = $value['status_page'] ? $value['status_page'] : '-';
                $dt['created_by']            = $value['created_by'];
                $dt['updated_by']            = $value['updated_by'] ? $value['updated_by'] : $value['created_by'];
                $dt['updated_at']            = $value['updated_at'] ? tanggal($value['updated_at'], ' ', true) : '-';

                $id = encid($value['kontenpage_id']);

                $dataAction = [
                    'id'  => $id,
                    'btn' => [
                        ['action' => 'edit', 'link' => route('app.konten.konten-page.show', ['param1' => $id])],
                        // ['action' => 'delete', 'attr' => ['jf-delete' => $id]],
                    ]
                ];

                $dt['action'] = Blade::render('<x-btn.actiontable :id="$id" :btn="$btn"/>', $dataAction);

                $resp[] = $dt;
            }
            $data['data'] = $resp;


            return response()->json($data);
        } else if ($param1 == 'jurusan-list') {
            // custom filter
            $filter = [];

            $data = DataTables::of(KontenJurusan::getDataDetail($filter, get: false))->toArray();

            $start = $req->input('start');
            $resp  = [];
            foreach ($data['data'] as $key => $value) {
                $dt = [];

                $dt['no']                    = ++$start;
                $dt['jurusan']               = $value['jurusan'];
                $dt['alias_jurusan']         = $value['alias'];
                $dt['url_jurusan']           = 'https://' . strtolower($value['alias']) . '.pcr.ac.id';
                $dt['updated_at']            = $value['updated_at'] ? dateTime($value['updated_at']) : '-';

                $id = encid($value['kontenjurusan_id']);

                $dataAction = [
                    'id'  => $id,
                    'btn' => [
                        ['action' => 'edit', 'link' => url('admin/konten-page/jurusan-site/' . $id)],
                    ]
                ];

                $dt['action'] = Blade::render('<x-btn.actiontable :id="$id" :btn="$btn"/>', $dataAction);

                $resp[] = $dt;
            }
            $data['data'] = $resp;


            return response()->json($data);
        } else if ($param1 == 'prodi-list') {
            // custom filter
            $filter = [];

            $data = DataTables::of(KontenProdi::getDataDetail($filter, false))->toArray();

            $start = $req->input('start');
            $resp  = [];
            foreach ($data['data'] as $key => $value) {
                $dt = [];

                $dt['no']                    = ++$start;
                $dt['nama_prodi']            = $value['nama_prodi'];
                $dt['jenjang_pendidikan']    = $value['jenjang_pendidikan'];
                $dt['alias_prodi']           = $value['alias'];
                $dt['url_prodi']             = 'https://' . strtolower($value['alias']) . '.pcr.ac.id';
                $dt['updated_at']            = $value['updated_at'] ? dateTime($value['updated_at']) : '-';

                $id = encid($value['kontenprodi_id']);

                $dataAction = [
                    'id'  => $id,
                    'btn' => [
                        ['action' => 'edit', 'link' => url('admin/konten-page/prodi-site/' . $id)],
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
