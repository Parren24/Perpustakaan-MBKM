<?php

/*
 * Author: @wahyudibinsaid
 * Created At: {{currTime}}
 */

namespace App\Http\Controllers\Admin\Konten;

use App\Http\Controllers\Controller;
use App\Models\Konten\KontenJurusan;
use App\Models\Konten\KontenProdi;
use App\Models\View\Prodi;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Column;

class KontenProdiController extends Controller
{
    function __construct()
    {
        /**
         * use this if needed
         */
        $this->activeRoot   = 'konten-prodi';
        $this->breadCrump[] = ['title' => 'Situs Prodi', 'link' => url('admin/konten-prodi')];
    }

    function index()
    {
        $this->title        = 'Kelola Situs Program Studi';
        $this->activeMenu   = 'konten-prodi';
        // $this->breadCrump[] = ['title' => '', 'link' => url()->current()];

        $builder   = app('datatables.html');
        $dataTable = $builder->pageLength(20)->serverSide(true)->ajax(url('admin/konten-prodi') . '/data/list')->columns([
            Column::make(['title' => 'Program Studi', 'data' => 'nama_prodi', 'orderable' => true, 'render' => 'card(full)']),
        ]);

        $this->dataView([
            'dataTable' => $dataTable,
        ]);

        return $this->view('admin.konten.prodi.list');
    }

    public function show($param1 = '', $param2 = '')
    {
        if ($param1 != '') {
            $get_konten = KontenProdi::getDataDetail(['kontenprodi_id' => decid($param1)], false)->first();

            $this->title        = 'Kelola Situs Program Studi';
            $this->activeMenu   = 'konten-prodi';
            $this->breadCrump[] = ['title' => $get_konten->jenjang_pendidikan . '-' . $get_konten->nama_prodi, 'link' => url()->current()];

            $dataForm = [];
            $dataForm['id'] = $param1;

            foreach (json_decode($get_konten->header_prodi) as $key => $value) {
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
                    $dataForm['media_header'] = $temp_media;
                } else {
                    $dataForm[$key . '_header'] = $value;
                }
            }

            foreach (json_decode($get_konten->sambutan_prodi) as $key => $value) {
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

            foreach (json_decode($get_konten->akreditasi_prodi) as $key => $value) {
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
                    $dataForm['media_akreditasi'] = $temp_media;
                } else {
                    $dataForm[$key . '_akreditasi'] = $value;
                }
            }

            $temp = [];
            foreach (json_decode($get_konten->prospek_karir_prodi) as $row) {
                $temp[] = $row;
            }
            $dataForm['prospek_karir'] = $temp;

            $temp = [];
            foreach (json_decode($get_konten->milestone_prodi) as $row) {
                $temp[] = [
                    'tahun_milestone' => $row->tahun,
                    'konten_milestone' => $row->konten,
                ];
            }
            $dataForm['milestone_prodi'] = $temp;

            $dataForm['visi_prodi'] = $get_konten->visi_prodi;

            $temp = [];
            foreach (json_decode($get_konten->misi_prodi) as $row) {
                $temp[] = [
                    'icon_misi_prodi' => $row->icon,
                    'misi_prodi' => $row->misi,
                ];
            }
            $dataForm['misi_prodi'] = $temp;

            $temp = [];
            foreach (json_decode($get_konten->tujuan_prodi) as $row) {
                $temp[] = $row;
            }
            $dataForm['tujuan_prodi'] = $temp;

            // dd($dataForm);
            $this->dataView([
                'dataForm' => $dataForm
            ]);

            return $this->view('admin.konten.prodi.form');
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
                // 'id' => ['Param Data', 'required'],
                'judul_sambutan' => ['Judul Sambutan', 'required'],
                'isi_sambutan' => ['Isi Sambutan', 'required'],
                'akreditasi' => ['Akreditasi', 'required'],
                'no_sk_akreditasi' => ['No. SK Prodi', 'required'],
                'url_akreditasi' => ['Url Dok Akreditasi', 'required'],
                'visi_prodi' => ['Visi Prodi', 'required'],
                'misi_prodi' => ['Misi Prodi', 'required'],
                'konten_milestones' => ['Konten Milestones', 'required'],
            ]);

            // if(!$req->has('misi_prodi') || !$req->has('tahun_milstones') || !$req->has('konten_milestones'))

            $currData =  KontenProdi::findOrFail(decid($req->input('id')));

            $judul_sambutan = clean_post('judul_sambutan');
            $isi_sambutan = clean_post('isi_sambutan');
            $akreditasi = clean_post('akreditasi');
            $no_sk_akreditasi = clean_post('no_sk_akreditasi');
            $url_akreditasi = clean_post('url_akreditasi');
            $visi_prodi = clean_post('visi_prodi');

            $media_id_header = $req->input('media_id_header');
            $delete_media_id_header = $req->input('delete_media_id_header');

            $media_id_sambutan = $req->input('media_id_sambutan');
            $delete_media_id_sambutan = $req->input('delete_media_id_sambutan');

            $prospek_karir = $req->input('propek_karir');

            $tahun_milestones = $req->input('tahun_milestones');
            $konten_milestones = $req->input('konten_milestones');

            $misi_prodi = $req->input('misi_prodi');

            $tujuan_prodi = $req->input('tujuan_prodi');

            $temp = [];
            foreach ($media_id_header as $row) {
                $temp[] = decid($row);
            }
            $media_id_header = $temp;

            if ($delete_media_id_header) {
                foreach ($delete_media_id_header as $row) {
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

            $data['header_prodi'] = json_encode([
                'media_id' => $media_id_header
            ]);

            $data['sambutan_prodi'] = json_encode([
                'judul' => $judul_sambutan,
                'isi' => $isi_sambutan,
                'media_id' => $media_id_sambutan
            ]);

            $data['akreditasi_prodi'] = json_encode([
                'akreditasi' => $akreditasi,
                'no_sk' => $no_sk_akreditasi,
                'url' => $url_akreditasi
            ]);

            $temp = [];
            foreach ($prospek_karir as $row) {
                $temp[] = strtoupper($row);
            }

            $data['prospek_karir_prodi'] = json_encode($temp);

            $temp = [];
            $i = 0;
            foreach ($tahun_milestones as $row) {
                $temp[] = [
                    'tahun' => $row,
                    'konten' => $konten_milestones[$i]
                ];

                $i++;
            }

            $data['milestone_prodi'] = json_encode($temp);

            $data['visi_prodi'] = $visi_prodi;

            $temp = [];
            foreach ($misi_prodi as $row) {
                $temp[] = [
                    'icon' => 'bi bi-pencil',
                    'misi' => $row
                ];
            }

            $data['misi_prodi'] = json_encode($temp);

            $temp = [];
            foreach ($tujuan_prodi as $row) {
                $temp[] = $row;
            }

            $data['tujuan_prodi'] = json_encode($temp);

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

            $data = DataTables::of(KontenProdi::getDataDetail($filter, false))->toArray();

            $start = $req->input('start');
            $resp  = [];
            foreach ($data['data'] as $key => $value) {
                $dt = [];

                $dt['no']                    = ++$start;
                $dt['id']                    = encid($value['kontenprodi_id']);
                $dt['nama_prodi']            = $value['nama_prodi'];
                $dt['jenjang_pendidikan']    = $value['jenjang_pendidikan'];
                $dt['alias_prodi']           = $value['alias'];
                $dt['url_prodi']             = 'https://' . strtolower($value['alias']) . '.pcr.ac.id';
                $dt['updated_at']            = $value['updated_at'] ? dateTime($value['updated_at']) : '-';

                $id = encid($value['kontenprodi_id']);

                $dataAction = [
                    'id'  => $id,
                    'btn' => [
                        ['action' => 'edit', 'link' => url('admin/konten-prodi/' . $id)],
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
