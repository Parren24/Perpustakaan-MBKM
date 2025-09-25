<?php

/*
 * Author: @wahyudibinsaid
 * Created At: {{currTime}}
 */

namespace App\Http\Controllers\Admin\Konten;

use App\Http\Controllers\Controller;
use App\Models\Konten\KontenJurusan;
use App\Models\Konten\KontenProdi;
use App\Models\Konten\KontenSlide;
use App\Models\View\Prodi;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\DB;

class KontenSlideController extends Controller
{
    function __construct()
    {
        /**
         * use this if needed
         */
        $this->activeRoot   = 'konten-main';
        $this->breadCrump[] = ['title' => 'Slide Utama', 'link' => url('admin/konten-main')];
    }

    function index()
    {
        $this->title        = 'Kelola Slide Utama';
        $this->activeMenu   = 'konten-slide';
        // $this->breadCrump[] = ['title' => '', 'link' => url()->current()];

        $builder   = app('datatables.html');
        $dataTable = $builder->pageLength(value: 126)->serverSide(true)->ajax([
            'url' => url('admin/konten-slide') . '/data/list',
            'order' => [[0, 'asc']]
        ])->columns([
            Column::make(['title' => 'Seq', 'data' => 'seq', 'orderable' => true, 'visible' => false]),
            Column::make(['title' => 'Slide', 'data' => 'judul_slide', 'orderable' => true, 'render' => 'card(full)']),
        ]);

        $this->dataView([
            'dataTable' => $dataTable,
        ]);

        return $this->view('admin.konten.slide.list');
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
        if ($param1 == '') {
            validate_and_response([
                'judul_slide' => ['Judul slide utama', 'required'],
            ]);

            $media_id_desktop = $req->input('media_id_desktop');
            $delete_media_id_desktop = $req->input('delete_media_id_desktop');

            $media_id_tablet = $req->input('media_id_tablet');
            $delete_media_id_tablet = $req->input('delete_media_id_tablet');

            $media_id_mobile = $req->input('media_id_mobile');
            $delete_media_id_mobile = $req->input('delete_media_id_mobile');

            $temp = [];
            foreach ($media_id_desktop as $row) {
                $temp = decid($row);
            }
            $media_id_desktop = $temp;

            if (!empty($delete_media_id_desktop)) {
                foreach ($delete_media_id_desktop as $row) {
                    if ($row) {
                        destroyMedia(decid($row), true);
                    }
                }
            }

            $temp = [];
            foreach ($media_id_tablet as $row) {
                $temp = decid($row);
            }
            $media_id_tablet = $temp;

            if (!empty($delete_media_id_tablet)) {
                foreach ($delete_media_id_tablet as $row) {
                    if ($row) {
                        destroyMedia(decid($row), true);
                    }
                }
            }

            $temp = [];
            foreach ($media_id_mobile as $row) {
                $temp = decid($row);
            }
            $media_id_mobile = $temp;

            if (!empty($delete_media_id_mobile)) {
                foreach ($delete_media_id_mobile as $row) {
                    if ($row) {
                        destroyMedia(decid($row), true);
                    }
                }
            }

            $seq = count(KontenSlide::where('status_slide', '=', 'aktif')->get());

            $data['judul_slide'] = clean_post('judul_slide');
            $data['seq'] = $seq + 1;
            $data['media_id_desktop'] = $media_id_desktop;
            $data['media_id_tablet'] = $media_id_tablet;
            $data['media_id_mobile'] = $media_id_mobile;
            $data['status_slide'] = 'aktif';

            KontenSlide::create($data);

            return response()->json([
                'status'  => true,
                'message' => 'Tambah data berhasil.'
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
        } else if ($param1 == 'status-slide') {
            validate_and_response([
                'id' => ['Param Data', 'required'],
                'status_slide' => ['Status Slide', 'required'],
            ]);

            $currData = KontenSlide::findOrFail(decid($req->input('id')));

            DB::beginTransaction();
            try {
                if (strtolower(clean_post('status_slide')) == 'aktif') {
                    $seq = count(KontenSlide::where('status_slide', '=', 'aktif')->get());
                    $seq = $seq + 1;
                } else {
                    $dataSlide = KontenSlide::where('status_slide', '=', 'aktif')
                        ->orderBy('seq', 'ASC')
                        ->where('kontenslide_id', '!=', decid($req->input('id')))
                        ->get();
                    $i = 1;

                    foreach ($dataSlide as $record) {
                        $record->update([
                            'seq' => $i
                        ]);

                        $i++;
                    }

                    $seq = null;
                }

                $data['status_slide'] = strtolower(clean_post('status_slide'));
                $data['seq'] = $seq;

                // Simpan data
                $currData->update($data);

                DB::commit();
                return response()->json([
                    'status'  => true,
                    'message' => 'Update data berhasil.',
                    'data'    => null,
                ]);
            } catch (\Throwable $th) {
                DB::rollBack();
                abort(500, 'Update data gagal, kesalahan database' . $th->getMessage());
            }
        } else if ($param1 == 'status-sequence') {
            $data = [];

            foreach ($req->id as $row) {
                $slide_id[] = decid($row);
            }

            if (empty($slide_id))
                abort(404, 'Param data tidak ditemukan');

            DB::beginTransaction();
            try {
                //Update all seq to NULL
                KontenSlide::query()->update(['seq' => null]);

                $seq = 1;
                $data['status_slide'] = 'aktif';

                foreach ($slide_id as $row) {
                    $currData = KontenSlide::findOrFail($row);

                    $data['seq'] = $seq;

                    $currData->update($data);
                    $seq++;
                }

                DB::commit();
                return response()->json([
                    'status'  => true,
                    'message' => 'Update data berhasil.',
                    'data'    => null,
                ]);
            } catch (\Throwable $th) {
                DB::rollBack();
                abort(500, 'Update data gagal, kesalahan database' . $th->getMessage());
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

            $currData = KontenSlide::findOrFail(decid($req->input('id')));

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
            // validate_and_response([
            //     'id' => ['Parameter data', 'required'],
            // ]);

            // $currData = KontenSlide::findOrFail(decid($req->input('id')))->makeHidden(KontenSlide::$exceptEdit);

            // $currData->id         = $req->input('id');

            // return response()->json(['status' => true, 'message' => 'Data loaded', 'data' => $currData]);
        } else if ($param1 == 'slide-aktif-detail') {
            // custom filter
            $filter = [];
            $filter['status_slide'] = 'aktif';

            $getData = KontenSlide::getDataDetail($filter, get: false)->orderBy('seq', 'asc')->get();

            $resp = [];
            foreach ($getData as $row) {
                $resp[] = [
                    'id' => encid($row->kontenslide_id),
                    'judul_slide' => $row->judul_slide,
                    'seq' => $row->seq,
                ];
            }

            return response()->json(['status' => true, 'message' => 'Data loaded', 'data' => $resp]);
        } else if ($param1 == 'list') {
            // custom filter
            $filter = [];

            if ($req->has('status_slide') && $req->status_slide != '')
                $filter['status_slide'] = clean_post('status_slide');

            $data = DataTables::of(KontenSlide::getDataDetail($filter, false))->toArray();

            $start = $req->input('start');
            $resp  = [];
            foreach ($data['data'] as $key => $value) {
                $dt = [];

                $dt['no']                   = ++$start;
                $dt['id']                   = encid($value['kontenslide_id']);
                $dt['judul_slide']          = $value['judul_slide'];
                $dt['seq']                  = $value['seq'];
                $dt['status_slide']         = $value['status_slide'];
                $dt['media_id_desktop']     = encid($value['media_id_desktop']);
                $dt['media_id_tablet']      = encid($value['media_id_tablet']);
                $dt['media_id_mobile']      = encid($value['media_id_mobile']);
                $dt['media_desktop']        = route('app.media.show', ['id' => encid($value['media_id_desktop'])]);
                $dt['media_tablet']         = route('app.media.show', ['id' => encid($value['media_id_tablet'])]);
                $dt['media_mobile']         = route('app.media.show', ['id' => encid($value['media_id_mobile'])]);

                $id = encid($value['kontenslide_id']);

                $dataAction = [
                    'id'  => $id,
                    'btn' => []
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
