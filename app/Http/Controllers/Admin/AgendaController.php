<?php

/*
 * Author: @wahyudibinsaid
 * Created At: {{currTime}}
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Column;
use App\Models\Agenda\Agenda;
use App\Models\Agenda\AgendaHasKategori;
use App\Models\Agenda\AgendaKategori;
use App\Models\View\Jurusan;
use App\Models\View\Prodi;
use Illuminate\Support\Facades\DB;

class AgendaController extends Controller
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
        $this->title        = 'Kelola Agenda';
        $this->activeMenu   = 'agenda';
        $this->breadCrump[] = ['title' => 'Agenda', 'link' => url()->current()];

        $builder   = app('datatables.html');
        $dataTable = $builder->serverSide(true)->ajax(route('app.agenda.data') . '/list')->columns([
            Column::make(['width' => '', 'title' => '', 'data' => 'action', 'orderable' => false, 'searchable' => false, 'className' => 'text-nowrap text-end']),
            Column::make(['width' => '5%', 'title' => 'No', 'data' => 'no', 'orderable' => false, 'searchable' => false, 'className' => 'text-center']),
            Column::make(['width' => '70%', 'title' => 'Agenda', 'data' => 'nama_agenda', 'render' => '`
            <div class="d-flex justify-content-start flex-column">
                <small class="d-flex align-items-center flex-row">
                    <div>${renderKategori(full.agenda_kategori)}</div>
                </small>
                <div>${full.nama_agenda}</div>
                <small class="d-flex align-items-center flex-row">
                    <i class="bi bi-geo-alt-fill text-danger me-2"></i>
                    ${full.lokasi_agenda}
                </small>
            </div>`']),
            Column::make(['width' => '15%', 'title' => 'Tanggal', 'data' => 'nama_agenda', 'className' => 'text-start', 'render' => '`
                <small>
                    ${full.tanggal_start_agenda}
                    ${full.tanggal_end_agenda ? `<br>` + full.tanggal_end_agenda : ``}
                </small>
            `']),
            Column::make(['width' => '10%', 'title' => 'Waktu', 'data' => 'nama_agenda', 'className' => 'text-start', 'render' => '`
                ${full.waktu_start_agenda}
                ${full.waktu_end_agenda ? `-` + full.waktu_end_agenda : ``}
            `']),
        ]);

        $temp = [];
        foreach (AgendaKategori::all() as $row) {
            $temp[] = [
                'id'    => encid($row->agendakategori_id),
                'text'  => $row->nama_kategori
            ];
        }
        $dataKategori = $temp;

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
        $filter = [];
        foreach (Jurusan::where($filter)->get() as $row) {
            $temp[] = [
                'id' => encid($row->jurusan_id),
                'text' => $row->jurusan
            ];
        }
        $dataJurusan = $temp;


        $this->dataView([
            'dataTable'     => $dataTable,
            'dataKategori'  => $dataKategori,
            'dataProdi'     => $dataProdi,
            'dataJurusan'   => $dataJurusan
        ]);

        return $this->view('admin.agenda.list');
    }

    public function show($param1 = '', $param2 = '')
    {
        if ($param1 == 'kategori') {
            $this->title        = 'Kelola Kategori Agenda';
            $this->activeMenu   = 'agenda-kategori';
            $this->breadCrump[] = ['title' => 'Kategori Agenda', 'link' => url()->current()];

            $builder   = app('datatables.html');
            $dataTable = $builder->serverSide(true)->ajax(route('app.agenda.data') . '/kategori-list')->columns([
                Column::make(['width' => '', 'title' => '', 'data' => 'action', 'orderable' => false, 'searchable' => false, 'className' => 'text-nowrap text-end']),
                Column::make(['width' => '5%', 'title' => 'No', 'data' => 'no', 'orderable' => false, 'searchable' => false, 'className' => 'text-center']),
                Column::make(['width' => '15%', 'title' => 'Kode', 'data' => 'kode_kategori', 'orderable' => true, 'className' => 'text-gray-600 fw-bold']),
                Column::make(['width' => '40%', 'title' => 'Kategori', 'data' => 'nama_kategori', 'orderable' => true]),
                Column::make(['width' => '40%', 'title' => 'Deskripsi', 'data' => 'deskripsi_kategori', 'orderable' => true]),
            ]);

            $this->dataView([
                'dataTable' => $dataTable
            ]);

            return $this->view('admin.agenda.agenda-kategori');
        } else {
            abort(404, 'Halaman tidak ditemukan');
        }
    }

    function store(Request $req, $param1 = ''): JsonResponse
    {
        if ($param1 == '') {
            validate_and_response([
                'level'  => ['Peruntukan Situs', 'required'],
                'nama_agenda'  => ['Agenda', 'required'],
                'tanggal_start_agenda'  => ['Tanggal Agenda', 'required'],
                'status_agenda'  => ['Status', 'required'],
                'agendakategori_id'  => ['Kategori', 'required'],
            ]);

            // insert data
            $data['level'] = clean_post('level');
            $data['nama_agenda'] = clean_post('nama_agenda');
            $data['tanggal_start_agenda'] = date('Y-m-d', strtotime(clean_post('tanggal_start_agenda')));
            $data['tanggal_end_agenda'] = clean_post('tanggal_end_agenda') == '' ? date('Y-m-d', strtotime(clean_post('tanggal_end_agenda'))) : null;
            $data['waktu_start_agenda'] = clean_post('waktu_start_agenda') == '' ? date('Y-m-d', strtotime(clean_post('waktu_start_agenda'))) : null;
            $data['waktu_end_agenda'] = clean_post('waktu_end_agenda') == '' ? date('Y-m-d', strtotime(clean_post('waktu_end_agenda'))) : null;
            $data['deskripsi_agenda'] = clean_post('deskripsi_agenda') ?? null;
            $data['lokasi_agenda'] = clean_post('lokasi_agenda') ?? null;
            $data['url_agenda'] = clean_post('url_agenda') ?? null;
            $data['status_agenda'] = clean_post('status_agenda') ?? 'draft';
            $data['agendakategori_id'] = decid($req->post('agendakategori_id'));

            // Simpan data
            DB::beginTransaction();
            try {
                $inserted = Agenda::create($data);

                DB::commit();
                return response()->json([
                    'status'  => true,
                    'message' => 'Tambah data berhasil.',
                    'data'    => ['id' => encid($inserted->agenda_id)]
                ]);
            } catch (\Throwable $th) {
                DB::rollBack();
                abort(404, 'Tambah data gagal, ' . $th->getMessage());
            }
        } else if ($param1 == 'kategori') {
            validate_and_response([
                'nama_kategori'        => ['Nama Label ID', 'required'],
            ]);

            // insert data
            $data['nama_kategori'] = clean_post('nama_kategori');
            $data['kode_kategori'] = Str::slug($data['nama_kategori'], '-');
            $data['deskripsi_kategori'] = clean_post('deskripsi_kategori');

            // Simpan data
            if (AgendaKategori::create($data)) {
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
                'id' => ['Param Data', 'required'],
                'level'  => ['Peruntukan Situs', 'required'],
                'nama_agenda'  => ['Agenda', 'required'],
                'tanggal_start_agenda'  => ['Tanggal Agenda', 'required'],
                'status_agenda'  => ['Status', 'required'],
                'agendakategori_id'  => ['Kategori', 'required'],
            ]);

            $currData = Agenda::findOrFail(decid($req->input('id')));

            // Perbarui data
            $data['level'] = clean_post('level');
            $data['nama_agenda'] = clean_post('nama_agenda');
            $data['tanggal_start_agenda'] = date('Y-m-d', strtotime(clean_post('tanggal_start_agenda')));
            $data['tanggal_end_agenda'] = clean_post('tanggal_end_agenda') != '' ? date('Y-m-d', strtotime(clean_post('tanggal_end_agenda'))) : null;
            $data['waktu_start_agenda'] = clean_post('waktu_start_agenda') != '' ? date('H:i', strtotime(clean_post('waktu_start_agenda'))) : null;
            $data['waktu_end_agenda'] = clean_post('waktu_end_agenda') != '' ? date('H:i', strtotime(clean_post('waktu_end_agenda'))) : null;
            $data['deskripsi_agenda'] = clean_post('deskripsi_agenda') ?? null;
            $data['lokasi_agenda'] = clean_post('lokasi_agenda') ?? null;
            $data['url_agenda'] = clean_post('url_agenda') ?? null;
            $data['status_agenda'] = clean_post('status_agenda') ?? 'draft';
            $data['agendakategori_id'] = decid($req->post('agendakategori_id'));

            // Simpan perubahan
            DB::beginTransaction();
            try {
                $currData->update($data);

                DB::commit();
                return response()->json([
                    'status'  => true,
                    'message' => 'Update data berhasil.',
                    'data'    => ['id' => encid($currData->agenda_id)]
                ]);
            } catch (\Throwable $th) {
                DB::rollBack();
                abort(404, 'Update data gagal, ' . $th->getMessage());
            }
        } else if ($param1 == 'kategori') {
            validate_and_response([
                'id'                 => ['Param Data', 'required'],
                'nama_kategori'         => ['Nama Label ID', 'required'],
                // 'nama_kategori_en'      => ['Nama Label EN', 'required'],

            ]);

            $currData = AgendaKategori::findOrFail(decid($req->input('id')));

            // Perbarui data
            $data['nama_kategori']    = clean_post('nama_kategori');
            $data['kode_kategori']    = Str::slug($data['nama_kategori'], '-');
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

            $currData = Agenda::findOrFail(decid($req->input('id')));

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

            $currData = Agenda::findOrFail(decid($req->input('id')))->makeHidden(Agenda::$exceptEdit);

            $currData->id         = $req->input('id');

            return response()->json(['status' => true, 'message' => 'Data loaded', 'data' => $currData]);
        } else if ($param1 == 'kategori-detail') {
            validate_and_response([
                'id' => ['Parameter data', 'required'],
            ]);

            $currData = AgendaKategori::findOrFail(decid($req->input('id')))->makeHidden(AgendaKategori::$exceptEdit);

            $currData->id = $req->input('id');

            return response()->json(['status' => true, 'message' => 'Data loaded', 'data' => $currData]);
        } else if ($param1 == 'list') {
            // custom filter
            $filter = [];

            $data = DataTables::of(Agenda::getDataDetail($filter, get: false))->rawColumns(['agenda_kategori'])->toArray();

            $start = $req->input('start');
            $resp  = [];
            foreach ($data['data'] as $key => $value) {
                $dt = [];

                $dt['no']                       = ++$start;
                $dt['level']                    = $value['level'];
                $dt['level_id']                 = $value['level_id'] ?? null;
                $dt['level_alias']              = $value['level_alias'] ?? '-';
                $dt['nama_agenda']              = $value['nama_agenda'] ?? '-';
                $dt['deskripsi_agenda']         = $value['deskripsi_agenda'] ?? '-';
                $dt['tanggal_start_agenda']     = $value['tanggal_start_agenda'] ? tanggal($value['tanggal_start_agenda']) : '-';
                $dt['tanggal_end_agenda']       = $value['tanggal_end_agenda'] ? tanggal($value['tanggal_end_agenda']) : null;
                $dt['waktu_start_agenda']       = $value['waktu_start_agenda'] ? date('H:i', strtotime($value['waktu_start_agenda'])) . ' WIB' : null;
                $dt['waktu_end_agenda']         = $value['waktu_end_agenda'] ? date('H:i', strtotime($value['waktu_end_agenda'])) . ' WIB' : null;
                $dt['lokasi_agenda']            = $value['lokasi_agenda'] ?? '-';
                $dt['url_agenda']               = $value['url_agenda'] ?? '-';
                $dt['status_agenda']            = $value['status_agenda'] ?? '-';
                $dt['agenda_kategori']          = $value['agenda_kategori'] ? json_decode($value['agenda_kategori']) : null;

                $id = encid($value['agenda_id']);

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
        } else if ($param1 == 'kategori-list') {
            // custom filter
            $filter = [];

            $data = DataTables::of(AgendaKategori::where($filter))->toArray();

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


                $id = encid($value['agendakategori_id']);

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
