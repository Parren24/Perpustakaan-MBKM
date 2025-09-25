<?php

/*
 * Author: @wahyudibinsaid
 * Created At: {{currTime}}
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dimension\DmInfografis;
use App\Models\Dimension\DmPegawai;
use App\Models\Dimension\Jurusan;
use App\Models\Dimension\Prodi;
use App\Models\Master\Kontak;
use App\Models\Master\Partner;
use App\Models\Master\SocialMedia;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\DB;

class MasterController extends Controller
{
    protected $sosial_medias = [];
    function __construct()
    {
        /**
         * use this if needed
         */
        $this->activeRoot   = 'master';
        $this->breadCrump[] = ['title' => 'Master', 'link' => url('')];

        $this->sosial_medias = [
            'Instagram' => 'fab fa-instagram',
            'Facebook'  => 'fab fa-facebook',
            'LinkedIn'  => 'fab fa-linkedin',
            'YouTube'   => 'fab fa-youtube',
            'Twitter / X' => 'fab fa-twitter',
            'TikTok'    => 'fab fa-tiktok',
            'WhatsApp'  => 'fab fa-whatsapp',
            'Telegram'  => 'fab fa-telegram',
            'Website'   => 'fas fa-globe'
        ];
    }

    function index()
    {
        // $this->title        = 'Kelola {{classTitle}}';
        // $this->activeMenu   = '{{paramValue}}';
        // $this->breadCrump[] = ['title' => '', 'link' => url()->current()];

        // $builder   = app('datatables.html');
        // $dataTable = $builder->serverSide(true)->ajax(route('app.{{routePrefix}}{{tableName}}.data').'/list')->columns([
        //     Column::make(['width' => '', 'title' => '', 'data' => 'action', 'orderable' => false, 'searchable' => false, 'className' => 'text-nowrap text-end']),
        //     Column::make(['width' => '5%', 'title' => 'No', 'data' => 'no', 'orderable' => false, 'searchable' => false, 'className' => 'text-center']),
        //     {{columnView}}
        // ]);

        // $this->dataView([
        //     'dataTable' => $dataTable
        // ]);

        // return $this->view('{{viewBlade}}');
    }

    public function show($param1 = '', $param2 = '')
    {
        if ($param1 == 'infografis') {
            $this->title        = 'Kelola Data Infografis';
            $this->activeMenu   = 'infografis';
            $this->breadCrump[] = ['title' => 'Infografis', 'link' => url()->current()];

            $builder   = app('datatables.html');
            $dataTable = $builder->paging(false)->serverSide(true)->ajax(route('app.master.data') . '/infografis-list')->columns([
                Column::make(['title' => 'Filetype', 'data' => 'nama_media', 'orderable' => false, 'className' => 'text-center', 'render' => 'card(full)']),
                // Column::make(['width' => '', 'title' => '', 'data' => 'action', 'orderable' => false, 'className' => 'text-nowrap text-end']),
                // Column::make(['width' => '5%', 'title' => 'No', 'data' => 'no', 'orderable' => false, 'className' => 'text-center']),
                // Column::make(['width' => '15%', 'title' => 'Icon', 'data' => 'icon_infografis']),
                // Column::make(['width' => '70%', 'title' => 'Data', 'data' => 'nama_infografis']),
                // Column::make(['width' => '10%', 'title' => 'Value', 'data' => 'value_infografis', 'render' => '`<span class="fw-bolder">${full.value_infografis}</span>`']),
            ]);

            $this->dataView([
                'dataTable' => $dataTable
            ]);

            return $this->view('admin.master.infografis');
        } else if ($param1 == 'partner') {
            $this->title        = 'Kelola Data Partner';
            $this->activeMenu   = 'partner';
            $this->breadCrump[] = ['title' => 'Partner', 'link' => url()->current()];

            $builder   = app('datatables.html');
            $dataTable = $builder->serverSide(true)->ajax(route('app.master.data') . '/partner-list')->columns([
                Column::make(['width' => '', 'title' => '', 'data' => 'action', 'orderable' => false, 'className' => 'text-nowrap text-end']),
                Column::make(['width' => '5%', 'title' => 'No', 'data' => 'no', 'orderable' => false, 'className' => 'text-center']),
                Column::make(['width' => '7%', 'title' => 'Status', 'className' => 'text-end', 'data' => 'status_partner', 'orderable' => true, 'render' => 'renderStatus(full.status_partner)']),
                Column::make(['width' => '80%', 'title' => 'Partner', 'data' => 'nama_partner', 'render' => '`
                <div class="d-flex gap-2">
                    <div class="d-flex align-items-center h-100">
                        <img src="${full.filemedia_partner}" class="w-60px rounded" title="${full.nama_partner}">
                    </div>
                    <div class="d-flex flex-column justify-content-center">
                        <div class="text-primary">${full.jenis_partner}</div>
                        <span class="fw-bold">${full.nama_partner}</span>
                        <span><i class="bi bi bi-link-45deg me-2"></i><a href="${full.url_partner}" target="_blank" class="fs-7">${full.url_partner}</a></span>
                    </div>
                </div>
                `']),
            ]);

            $this->dataView([
                'dataTable' => $dataTable,
            ]);

            return $this->view('admin.master.partner');
        } else if ($param1 == 'social-media') {
            $this->title        = 'Kelola Data Sosial Media';
            $this->activeMenu   = 'social-media';
            $this->breadCrump[] = ['title' => 'Sosial Media', 'link' => url()->current()];

            $builder   = app('datatables.html');
            $dataTable = $builder->serverSide(true)->ajax(route('app.master.data') . '/social-media-list')->columns([
                Column::make(['width' => '', 'title' => '', 'data' => 'action', 'orderable' => false, 'className' => 'text-nowrap text-end']),
                Column::make(['width' => '5%', 'title' => 'No', 'data' => 'no', 'orderable' => false, 'className' => 'text-center']),
                Column::make(['width' => '5%', 'title' => 'Status', 'className' => 'text-end', 'data' => 'status_social_media', 'orderable' => true, 'render' => 'renderStatus(full.status_social_media)']),
                Column::make(['width' => '85%', 'title' => 'Platform', 'data' => 'platform', 'render' => '`
                <div class="d-flex gap-2">
                    <div class="d-flex align-items-center justify-content-center h-100" style="width:50px;">
                        ${full.icon_social_media
                            ? `<i class="${full.icon_social_media} fs-2x text-dark"></i>`
                            : `<i class="fa-solid fa-dash fs-2x text-dark"></i>`}
                    </div>
                    <div class="d-flex flex-column">
                        ${full.platform}
                        <span><i class="bi bi bi-link-45deg me-2"></i><a href="${full.url_social_media}" target="_blank" class="fs-7">${full.url_social_media}</a></span>
                    </div>
                </div>
                `']),
            ]);

            $this->dataView([
                'dataTable' => $dataTable,
                'dataSocialMedia' => $this->sosial_medias
            ]);

            return $this->view('admin.master.social-media');
        } else if ($param1 == 'kontak') {
            $this->title        = 'Kelola Data Kontak';
            $this->activeMenu   = 'kontak';
            $this->breadCrump[] = ['title' => 'Kontak', 'link' => url()->current()];

            $builder   = app('datatables.html');
            $dataTable = $builder->serverSide(true)->ajax(route('app.master.data') . '/kontak-list')->columns([
                Column::make(['width' => '', 'title' => '', 'data' => 'action', 'orderable' => false, 'className' => 'text-nowrap text-end']),
                Column::make(['width' => '5%', 'title' => 'No', 'data' => 'no', 'orderable' => false, 'className' => 'text-center']),
                Column::make(['width' => '5%', 'title' => 'Status', 'className' => 'text-end', 'data' => 'status_kontak', 'orderable' => true, 'render' => 'renderStatus(full.status_kontak)']),
                Column::make(['width' => '85%', 'title' => 'Kontak', 'data' => 'nama_kontak', 'render' => '`
                <div class="d-flex gap-2">
                    <div class="d-flex align-items-center justify-content-center h-100" style="width:50px;">
                        ${full.icon_kontak
                            ? `<i class="${full.icon_kontak} fs-2x text-dark"></i>`
                            : `<i class="fa-solid fa-dash fs-2x text-dark"></i>`}
                    </div>
                    <div class="d-flex flex-column">
                        ${full.nama_kontak}
                        <span><i class="${full.icon_kontak ? full.icon_kontak : `fa-solid fa-dash`} me-2"></i><a href="${full.value_kontak}" target="_blank" class="fs-7">${full.value_kontak}</a></span>
                    </div>
                </div>
                `']),
            ]);

            $this->dataView([
                'dataTable' => $dataTable
            ]);

            return $this->view('admin.master.kontak');
        } else if ($param1 == 'pegawai') {
            $this->title        = 'Kelola Data Pegawai';
            $this->activeMenu   = 'pegawai';
            $this->breadCrump[] = ['title' => 'Pegawai', 'link' => url()->current()];

            $builder   = app('datatables.html');
            $dataTable = $builder->serverSide(true)->ajax(route('app.master.data') . '/pegawai-list')->columns([
                Column::make(['width' => '', 'title' => '', 'data' => 'action', 'orderable' => false, 'className' => 'text-nowrap text-end']),
                Column::make(['width' => '5%', 'title' => 'No', 'data' => 'no', 'orderable' => false, 'className' => 'text-center']),
                Column::make(['width' => '10%', 'title' => 'NIP', 'data' => 'nip_pegawai']),
                Column::make(['width' => '10%', 'title' => 'NIDN', 'data' => 'nidn_pegawai']),
                Column::make(['width' => '10%', 'title' => 'Homebase', 'data' => 'homebase_pegawai']),
                Column::make(['width' => '70%', 'title' => 'Pegawai', 'data' => 'nama_pegawai']),
            ]);

            $this->dataView([
                'dataTable' => $dataTable,
            ]);

            return $this->view('admin.master.pegawai');
        } else if ($param1 == 'jurusan') {
            $this->title        = 'Kelola Data Jurusan';
            $this->activeMenu   = 'jurusan';
            $this->breadCrump[] = ['title' => 'Pegawai', 'link' => url()->current()];

            $builder   = app('datatables.html');
            $dataTable = $builder->serverSide(true)->ajax(route('app.master.data') . '/jurusan-list')->columns([
                Column::make(['width' => '', 'title' => '', 'data' => 'action', 'orderable' => false, 'className' => 'text-nowrap text-end']),
                Column::make(['width' => '5%', 'title' => 'No', 'data' => 'no', 'orderable' => false, 'className' => 'text-center']),
                Column::make(['width' => '10%', 'title' => 'Alias', 'data' => 'alias_jurusan']),
                Column::make(['width' => '15%', 'title' => 'Jurusan', 'data' => 'nama_jurusan']),
                Column::make(['title' => 'Deskripsi Singkat', 'data' => 'deskripsi_jurusan']),
            ]);

            $this->dataView([
                'dataTable' => $dataTable,
            ]);

            return $this->view('admin.master.jurusan');
        } else if ($param1 == 'prodi') {
            $this->title        = 'Kelola Data Program Studi';
            $this->activeMenu   = 'prodi';
            $this->breadCrump[] = ['title' => 'Program Studi', 'link' => url()->current()];

            $builder   = app('datatables.html');
            $dataTable = $builder->serverSide(true)->ajax(route('app.master.data') . '/prodi-list')->columns([
                Column::make(['width' => '', 'title' => '', 'data' => 'action', 'orderable' => false, 'className' => 'text-nowrap text-end']),
                Column::make(['width' => '5%', 'title' => 'No', 'data' => 'no', 'orderable' => false, 'className' => 'text-center']),
                Column::make(['width' => '10%', 'title' => 'Jurusan', 'data' => 'alias_jurusan']),
                Column::make(['width' => '10%', 'title' => 'Alias', 'data' => 'alias_prodi']),
                Column::make(['width' => '15%', 'title' => 'Program Studi', 'data' => 'nama_prodi']),
                Column::make(['title' => 'Deskripsi Singkat', 'data' => 'deskripsi_prodi']),
            ]);

            $this->dataView([
                'dataTable' => $dataTable,
            ]);

            return $this->view('admin.master.prodi');
        } else {
            abort(404, 'Halaman tidak ditemukan');
        }
    }

    function store(Request $req, $param1 = ''): JsonResponse
    {
        if ($param1 == 'partner') {
            validate_and_response([
                'jenis_partner'     => ['Jenis Partner', 'required'],
                'nama_partner'      => ['Nama Partner', 'required'],
                'url_partner'       => ['Link Partner', 'required'],
                'upload_file'       => ['Logo Partner', 'nullable|file|mimes:jpg,png|max:2048']
            ]);

            if ($req->hasFile('upload_file')) {
                $do_upload = uploadMedia('upload_file', 'partner');
                if (!$do_upload['status'])
                    abort(500, 'Update data gagal, ' . $do_upload['message']);

                $data['filemedia_partner']     = $do_upload['data']['filename'];
            }

            // insert data
            $data['jenis_partner']      = clean_post('jenis_partner');
            $data['nama_partner']       = clean_post('nama_partner');
            $data['url_partner']        = clean_post('url_partner');
            $data['deskripsi_partner']  = clean_post('deskripsi_partner') ? clean_post('deskripsi_partner') : null;
            $data['status_partner']     = clean_post('status_partner');

            // Simpan data
            if (Partner::create($data)) {
                return response()->json([
                    'status'  => true,
                    'message' => 'Tambah data berhasil.'
                ]);
            } else {
                abort(500, 'Tambah data gagal, kesalahan database');
            }
        } else if ($param1 == 'social-media') {
            validate_and_response([
                'platform'              => ['Nama Social Media', 'required'],
                'url_social_media'      => ['Link Social Media', 'required'],
                'status_social_media'   => ['Logo Social Media', 'required']
            ]);

            // insert data
            $data['platform']               = clean_post('platform');
            $data['icon_social_media']      = $this->sosial_medias[clean_post('platform')] ?? null;
            $data['url_social_media']       = clean_post('url_social_media');
            $data['deskripsi_social_media'] = clean_post('deskripsi_social_media') ? clean_post('deskripsi_social_media') : null;
            $data['status_social_media']    = clean_post('status_social_media');

            // Simpan data
            if (SocialMedia::create($data)) {
                return response()->json([
                    'status'  => true,
                    'message' => 'Tambah data berhasil.'
                ]);
            } else {
                abort(500, 'Tambah data gagal, kesalahan database');
            }
        } else if ($param1 == 'kontak') {
            validate_and_response([
                'nama_kontak'    => ['Kontak', 'required'],
                'value_kontak'   => ['Informasi Kontak', 'required'],
                'status_kontak'  => ['Status', 'required'],
            ]);

            // insert data
            $data['tipe_kontak']        = clean_post('tipe_kontak');
            $data['icon_kontak']        = clean_post('icon_kontak');
            $data['nama_kontak']        = clean_post('nama_kontak');
            $data['value_kontak']       = clean_post('value_kontak');
            $data['deskripsi_kontak']   = clean_post('deskripsi_kontak') ?? null;
            $data['status_kontak']      = clean_post('status_kontak');

            // Simpan data
            if (Kontak::create($data)) {
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
        if ($param1 == 'infografis') {
            validate_and_response([
                'id' => ['Param Data', 'required'],
                'value_infografis' => ['Param Value', 'required'],
            ]);

            $currData = DmInfografis::findOrFail(decid($req->input('id')));

            // Perbarui data
            $data['value_infografis'] = clean_post('value_infografis');

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
        } else if ($param1 == 'infografis-order') {
            validate_and_response([
                'id' => ['Param Data', 'required'],
            ]);

            DB::beginTransaction();
            try {
                $temp = [];
                foreach ($req->post('id') as $key => $value) {
                    $currData = DmInfografis::findOrFail(decid($value));

                    // Perbarui data
                    $data['seq'] = $key + 1;
                    $temp[] = $data;
                    // Simpan perubahan
                    $currData->update($data);
                }

                DB::commit();
                return response()->json([
                    'status'  => true,
                    'message' => 'Update data berhasil.',
                    'data'    => $temp,
                ]);
            } catch (\Throwable $th) {
                //throw $th;
                DB::rollBack();
                abort(404, 'Update data gagal, ' . $th->getMessage());
            }
        } else if ($param1 == 'partner') {
            validate_and_response([
                'id' => ['Param Data', 'required'],
                'jenis_partner'     => ['Jenis Partner', 'required'],
                'nama_partner'      => ['Nama Partner', 'required'],
                'url_partner'       => ['Link Partner', 'required'],
                'upload_file'       => ['Logo Partner', 'nullable|file|mimes:jpg,png|max:2048']
            ]);

            $currData = Partner::findOrFail(decid($req->input('id')));

            if ($req->hasFile('upload_file')) {
                $do_upload = uploadMedia('upload_file', 'partner');
                if (!$do_upload['status'])
                    abort(500, 'Update data gagal, ' . $do_upload['message']);

                $data['filemedia_partner']     = $do_upload['data']['filename'];
            }

            // Perbarui data
            $data['jenis_partner']      = clean_post('jenis_partner');
            $data['nama_partner']       = clean_post('nama_partner');
            $data['url_partner']        = clean_post('url_partner');
            $data['deskripsi_partner']  = clean_post('deskripsi_partner') ? clean_post('deskripsi_partner') : null;
            $data['status_partner']     = clean_post('status_partner');

            // Simpan perubahan
            if ($currData->update($data)) {
                return response()->json([
                    'status'  => true,
                    'message' => 'Update data berhasil.',
                ]);
            } else {
                abort(500, 'Update data gagal, kesalahan database');
            }
        } else if ($param1 == 'social-media') {
            validate_and_response([
                'id' => ['Param Data', 'required'],
                'platform'              => ['Nama Social Media', 'required'],
                'url_social_media'      => ['Link Social Media', 'required'],
                'status_social_media'   => ['Logo Social Media', 'required']
            ]);

            $currData = SocialMedia::findOrFail(decid($req->input('id')));

            // Perbarui data
            $data['platform']               = clean_post('platform');
            $data['icon_social_media']      = $this->sosial_medias[clean_post('platform')] ?? null;
            $data['url_social_media']       = clean_post('url_social_media');
            $data['deskripsi_social_media'] = clean_post('deskripsi_social_media') ? clean_post('deskripsi_social_media') : null;
            $data['status_social_media']    = clean_post('status_social_media');

            // Simpan perubahan
            if ($currData->update($data)) {
                return response()->json([
                    'status'  => true,
                    'message' => 'Update data berhasil.',
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
        if ($param1 == 'partner') {
            validate_and_response([
                'id' => ['Parameter data', 'required'],
            ]);

            $currData = Partner::findOrFail(decid($req->input('id')));

            $currData->delete();
            return response()->json(['status' => true, 'message' => 'Data berhasil dihapus']);
        } else if ($param1 == 'social-media') {
            validate_and_response([
                'id' => ['Parameter data', 'required'],
            ]);

            $currData = SocialMedia::findOrFail(decid($req->input('id')));

            $currData->delete();
            return response()->json(['status' => true, 'message' => 'Data berhasil dihapus']);
        } else if ($param1 == 'kontak') {
            validate_and_response([
                'id' => ['Parameter data', 'required'],
            ]);

            $currData = Kontak::findOrFail(decid($req->input('id')));

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
        if ($param1 == 'infografis-detail') {
            validate_and_response([
                'id' => ['Parameter data', 'required'],
            ]);

            $currData = DmInfografis::findOrFail(decid($req->input('id')))->makeHidden(DmInfografis::$exceptEdit);

            $currData->id         = $req->input('id');

            return response()->json(['status' => true, 'message' => 'Data loaded', 'data' => $currData]);
        } else if ($param1 == 'partner-detail') {
            validate_and_response([
                'id' => ['Parameter data', 'required'],
            ]);

            $currData = Partner::findOrFail(decid($req->input('id')))->makeHidden(Partner::$exceptEdit);

            $currData->id           = $req->input('id');
            $currData->logo_partner = $currData->media_id_partner ? route('app.media.show', ['id' => encid($currData->media_id_partner)]) : null;

            return response()->json(['status' => true, 'message' => 'Data loaded', 'data' => $currData]);
        } else if ($param1 == 'social-media-detail') {
            validate_and_response([
                'id' => ['Parameter data', 'required'],
            ]);

            $currData = SocialMedia::findOrFail(decid($req->input('id')))->makeHidden(Partner::$exceptEdit);

            $currData->id           = $req->input('id');

            return response()->json(['status' => true, 'message' => 'Data loaded', 'data' => $currData]);
        } else if ($param1 == 'kontak-detail') {
            validate_and_response([
                'id' => ['Parameter data', 'required'],
            ]);

            $currData = Kontak::findOrFail(decid($req->input('id')))->makeHidden(Partner::$exceptEdit);

            $currData->id           = $req->input('id');

            return response()->json(['status' => true, 'message' => 'Data loaded', 'data' => $currData]);
        } else if ($param1 == 'infografis-list') {
            // custom filter
            $filter = [];

            $data = DataTables::of(DmInfografis::getDataDetail($filter, get: false))->toArray();

            $start = $req->input('start');
            $resp  = [];

            usort($data['data'], function ($a, $b) {
                return $a['seq'] <=> $b['seq']; // ascending
            });

            foreach ($data['data'] as $key => $value) {
                $dt = [];

                $dt['no']                    = ++$start;
                $dt['id'] = encid($value['infografis_id']);
                $dt['nama_infografis'] = $value['nama_infografis'];
                $dt['value_infografis'] = $value['value_infografis'] ?? '-';
                $dt['icon_infografis'] = $value['icon_infografis'] ?? '-';
                $dt['seq'] = $value['seq'] ?? '-';

                $id = encid($value['infografis_id']);

                $dataAction = [
                    'id'  => $id,
                    'btn' => [
                        ['action' => 'edit', 'attr' => ['jf-edit' => $id]],
                    ]
                ];

                $dt['action'] = Blade::render('<x-btn.actiontable :id="$id" :btn="$btn"/>', $dataAction);

                $resp[] = $dt;
            }
            $data['data'] = $resp;


            return response()->json($data);
        } else if ($param1 == 'partner-list') {
            // custom filter
            $filter = [];

            $data = DataTables::of(Partner::getDataDetail($filter, get: false))->toArray();

            $start = $req->input('start');
            $resp  = [];

            foreach ($data['data'] as $key => $value) {
                $dt = [];

                $dt['no']                    = ++$start;
                $dt['jenis_partner'] = $value['jenis_partner'];
                $dt['nama_partner'] = $value['nama_partner'];
                $dt['status_partner'] = $value['status_partner'];
                $dt['url_partner'] = $value['url_partner'] ?? '#';
                $dt['filemedia_partner'] = publicMedia($value['filemedia_partner'], 'partner');

                $id = encid($value['partner_id']);

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
        } else if ($param1 == 'social-media-list') {
            // custom filter
            $filter = [];

            $data = DataTables::of(SocialMedia::getDataDetail($filter, get: false))->toArray();

            $start = $req->input('start');
            $resp  = [];

            foreach ($data['data'] as $key => $value) {
                $dt = [];

                $dt['no']                       = ++$start;
                $dt['platform']                 = $value['platform'];
                $dt['icon_social_media']        = $value['icon_social_media'];
                $dt['url_social_media']         = $value['url_social_media'] ?? '#';
                $dt['deskripsi_social_media']   = $value['deskripsi_social_media'] ?? '-';
                $dt['status_social_media']      = $value['status_social_media'] ?? '-';

                $id = encid($value['socialmedia_id']);

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
        } else if ($param1 == 'kontak-list') {
            // custom filter
            $filter = [];

            $data = DataTables::of(Kontak::getDataDetail($filter, get: false))->toArray();

            $start = $req->input('start');
            $resp  = [];

            foreach ($data['data'] as $key => $value) {
                $dt = [];

                $dt['no']                       = ++$start;
                $dt['nama_kontak']              = $value['nama_kontak'];
                $dt['icon_kontak']              = $value['icon_kontak'];
                $dt['tipe_kontak']              = $value['tipe_kontak'];
                $dt['value_kontak']             = $value['value_kontak'] ?? '#';
                $dt['deskripsi_kontak']         = $value['deskripsi_kontak'] ?? '-';
                $dt['status_kontak']            = $value['status_kontak'];

                $id = encid($value['kontak_id']);

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
        } else if ($param1 == 'pegawai-list') {
            // custom filter
            $filter = [];

            $data = DataTables::of(DmPegawai::getDataDetail($filter, get: false))->toArray();

            $start = $req->input('start');
            $resp  = [];

            foreach ($data['data'] as $key => $value) {
                $dt = [];

                $dt['no']                    = ++$start;
                $dt['nip_pegawai']      = $value['nip_pegawai'];
                $dt['nidn_pegawai']     = $value['nidn_pegawai'] ?? '-';
                $dt['homebase_pegawai']     = $value['homebase_pegawai'] ?? '-';
                $dt['nama_pegawai']     = $value['nama_pegawai'];

                $id = $value['nip_pegawai'];

                $dataAction = [
                    'id'  => $id,
                    'btn' => [
                        ['action' => 'edit', 'attr' => ['jf-edit' => $id]],
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

            $data = DataTables::of(Jurusan::getDataDetail($filter, get: false))->toArray();

            $start = $req->input('start');
            $resp  = [];

            foreach ($data['data'] as $key => $value) {
                $dt = [];

                $dt['no']                   = ++$start;
                $dt['alias_jurusan']        = $value['alias_jurusan'] ?? '-';
                $dt['nama_jurusan']         = $value['nama_jurusan'] ?? '-';
                $dt['deskripsi_jurusan']    = $value['deskripsi_jurusan'] ?? '-';

                $id = encid($value['jurusan_id']);

                $dataAction = [
                    'id'  => $id,
                    'btn' => [
                        ['action' => 'edit', 'attr' => ['jf-edit' => $id]],
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

            $data = DataTables::of(Prodi::getDataDetail($filter, get: false))->toArray();

            $start = $req->input('start');
            $resp  = [];

            foreach ($data['data'] as $key => $value) {
                $dt = [];

                $dt['no']                   = ++$start;
                $dt['alias_prodi']        = $value['alias_prodi'] ?? '-';
                $dt['alias_jurusan']         = $value['alias_jurusan'] ?? '-';
                $dt['nama_prodi']    = $value['nama_prodi'] ?? '-';
                $dt['deskripsi_prodi']    = $value['deskripsi_prodi'] ?? '-';

                $id = encid($value['prodi_id']);

                $dataAction = [
                    'id'  => $id,
                    'btn' => [
                        ['action' => 'edit', 'attr' => ['jf-edit' => $id]],
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
