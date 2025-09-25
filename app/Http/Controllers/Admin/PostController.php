<?php

/*
 * Author: @wahyudibinsaid
 * Created At: 2024-06-24 10:12:11
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post\Post;
use App\Models\Post\PostHasKategori;
use App\Models\Post\PostHasLabel;
use App\Models\Post\PostKategori;
use App\Models\Post\PostLabel;
use App\Models\Post\PostProgres;
use App\Models\Post\PostSlugRedirect;
use App\Models\View\Jurusan;
use App\Models\View\Prodi;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    function __construct()
    {
        /**
         * use this if needed
         */
        $this->activeRoot = 'konten';
        $this->breadCrump[] = ['title' => 'Konten', 'link' => url('#')];
    }

    function index()
    {
        return redirect()->route('app.post.show', ['param1' => 'berita']);
    }

    public function show($param1 = '', $param2 = '')
    {
        /**
         * Get all availble kategories for tabs and select options if needed
         */
        $temp = [];
        foreach (PostKategori::all() as $row) {
            $temp[] = [
                'id' => encid($row->postkategori_id),
                'text' => $row->nama_kategori,
                'kode' => $row->kode_kategori
            ];
        }
        $dataKategori = $temp;

        if ($currKategori = PostKategori::where('kode_kategori', strtolower($param1))->first()) {
            $this->title = 'Kelola Post ' . $currKategori->nama_kategori;
            $this->activeMenu = 'post-kat-' . $param1;
            $this->breadCrump[] = ['title' => 'Post', 'link' => url()->current()];

            $builder = app('datatables.html');
            $dataTable = $builder->serverSide(true)->ajax(route('app.post.data') . '/list')->columns([
                Column::make(['width' => '', 'title' => '', 'data' => 'action', 'orderable' => false, 'className' => 'text-nowrap text-end']),
                Column::make(['width' => '5%', 'title' => 'No', 'data' => 'no', 'orderable' => false, 'className' => 'text-center']),
                Column::make(['width' => '10%', 'title' => 'Tgl Post', 'className' => 'text-end', 'data' => 'tanggal_post', 'orderable' => true]),
                Column::make(['width' => '5%', 'title' => 'Status', 'className' => 'text-end', 'data' => 'status_post', 'orderable' => true, 'render' => 'renderStatus(full.status_post)']),
                Column::make([
                    'width' => '',
                    'title' => 'Judul',
                    'data' => 'judul_post',
                    'orderable' => true,
                    'render' => '`
                <div class="d-flex align-items-center">
                    <div class="d-flex justify-content-start flex-column">
                        <img src="${full.filename_post}" class="w-100px rounded me-6"/>
                    </div>
                    <div class="d-flex justify-content-start flex-column">
                        <small class="d-flex align-items-center flex-row">
                            <div class="badge badge-light-primary">${full.nama_kategori}</div>
                        </small>
                        <div>${full.judul_post}</div>
                        <small class="d-flex align-items-center flex-row">
                            <i class="bi bi-tag-fill me-2 fs-8" title="Label Post"></i>
                            <div>${renderLabel(full.post_label)}</div>
                        </small>
                        <small class="d-flex align-items-center flex-row fst-italic fs-9 text-muted">
                            <span><i class="bi bi bi-link-45deg me-2"></i><a href="${full.url_post}" target="_blank" class="fs-7">${full.slug_post}</a></span>
                        </small>
                    </div>
                </div>
            `'
                ]),
            ]);

            $temp = [];
            foreach (PostLabel::all() as $row) {
                $temp[] = [
                    'id' => encid($row->postlabel_id),
                    'text' => $row->nama_label
                ];
            }
            $dataLabel = $temp;

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
                'dataTable' => $dataTable,
                'postkategori_id' => encid($currKategori->postkategori_id),
                'dataKategori' => $dataKategori,
                'dataLabel' => $dataLabel,
                'dataProdi' => $dataProdi,
                'dataJurusan' => $dataJurusan
            ]);

            return $this->view('admin.post.list');
        } else if ($param1 == 'form') {
            $this->title = 'Kelola Post';
            $this->activeMenu = 'post';
            $this->breadCrump[] = ['title' => 'Form', 'link' => url()->current()];

            $filter['p.post_id'] = decid($param2);

            $get_post = Post::getDataDetail($filter, false)->first();

            //get post label
            $post_label = [];
            if ($get_post->post_label) {
                foreach (json_decode($get_post->post_label) as $row) {
                    $post_label[] = encid($row->postlabel_id);
                }
            }

            $temp = [];
            foreach (PostKategori::where('postkategori_id', $get_post->postkategori_id)->get() as $row) {
                $temp[] = [
                    'id' => encid($row->postkategori_id),
                    'text' => $row->nama_kategori
                ];
            }
            $dataKategori = $temp;

            $temp = [];
            foreach (PostLabel::where('postkategori_id', $get_post->postkategori_id)->get() as $row) {
                $temp[] = [
                    'id' => encid($row->postlabel_id),
                    'text' => $row->nama_label
                ];
            }
            $dataLabel = $temp;

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

            $dataPost = [
                'id' => encid($get_post->post_id),
                'level' => $get_post->level,
                'level_id' => $get_post->level_id ? encid($get_post->level_id) : null,
                'judul_post' => $get_post->judul_post,
                'isi_post' => $get_post->isi_post,
                'tanggal_post' => date('Y-m-d\TH:i', strtotime($get_post->tanggal_post)),
                'user_id_author' => $get_post->user_id_author,
                'status_post' => $get_post->status_post,
                'meta_desc_post' => $get_post->meta_desc_post,
                'meta_keyword_post' => $get_post->meta_keyword_post,
                'media_cover' => publicMedia($get_post->filename_post, 'artikel'),
                // 'is_editable'           => $is_editable,
                // 'latest_status_progres' => $latest_status_progres,

                'post_label' => $post_label,
                'postkategori_id' => encid($get_post->postkategori_id),
                'kode_kategori' => $get_post->kode_kategori,
            ];

            $this->dataView([
                'dataPost' => $dataPost,
                'dataKategori' => $dataKategori,
                'dataLabel' => $dataLabel,
                'dataProdi' => $dataProdi,
                'dataJurusan' => $dataJurusan
            ]);

            return $this->view('admin.post.form');
        } else if ($param1 == 'label') {
            $this->title = 'Kelola Label Post';
            $this->activeMenu = 'post-label';
            $this->breadCrump[] = ['title' => 'Label', 'link' => url()->current()];

            $builder = app('datatables.html');
            $dataTable = $builder->serverSide(true)->ajax(route('app.post.data') . '/label-list')->columns([
                Column::make(['width' => '', 'title' => '', 'data' => 'action', 'orderable' => false, 'className' => 'text-nowrap text-end']),
                Column::make(['width' => '5%', 'title' => 'No', 'data' => 'no', 'orderable' => false, 'className' => 'text-center']),
                Column::make(['width' => '10%', 'title' => 'Slug', 'data' => 'kode_label', 'orderable' => true]),
                Column::make(['width' => '40%', 'title' => 'Label', 'data' => 'nama_label', 'orderable' => true]),
                Column::make(['width' => '40%', 'title' => 'Deskripsi', 'data' => 'deskripsi_label', 'orderable' => true]),
                // Column::make(['width' => '40%', 'title' => 'Label EN', 'data' => 'nama_label_en', 'orderable' => true]),
            ]);

            $this->dataView([
                'dataTable' => $dataTable,
                'dataKategori' => $dataKategori
            ]);

            return $this->view('admin.post.post-label');
        } else if ($param1 == 'kategori') {
            $this->title = 'Kelola Kategori Post';
            $this->activeMenu = 'post-kategori';
            // $this->breadCrump[] = ['title' => 'Label', 'link' => url()->current()];

            $builder = app('datatables.html');
            $dataTable = $builder->serverSide(true)->ajax(route('app.post.data') . '/kategori-list')->columns([
                Column::make(['width' => '', 'title' => '', 'data' => 'action', 'orderable' => false, 'className' => 'text-nowrap text-end']),
                Column::make(['width' => '5%', 'title' => 'No', 'data' => 'no', 'orderable' => false, 'className' => 'text-center']),
                Column::make(['width' => '10%', 'title' => 'Slug', 'data' => 'kode_kategori', 'orderable' => true]),
                Column::make(['width' => '40%', 'title' => 'Kategori', 'data' => 'nama_kategori', 'orderable' => true]),
                Column::make(['width' => '40%', 'title' => 'Deskripsi', 'data' => 'deskripsi_kategori', 'orderable' => true]),
                // Column::make(['width' => '40%', 'title' => 'Kategori EN', 'data' => 'nama_kategori_en', 'orderable' => true]),
            ]);

            $this->dataView([
                'dataKategori' => $dataKategori,
                'dataTable' => $dataTable
            ]);

            return $this->view('admin.post.post-kategori');
        } else {
            abort(404, 'Halaman tidak ditemukan');
        }
    }

    function store(Request $req, $param1 = ''): JsonResponse
    {
        if ($param1 == '') {
            validate_and_response([
                'judul_post' => ['Judul', 'required'],
                'postkategori_id' => ['Kategori Post', 'required'],
            ]);

            $slug_post = createSlug(clean_post('judul_post'));

            // insert data
            // $data['bahasa']            = 'ID';
            $data['judul_post'] = clean_post('judul_post');
            $data['level'] = 'main-site';
            $data['status_post'] = 'draft';
            $data['slug_post'] = $slug_post;
            $data['postkategori_id'] = decid($req->post('postkategori_id'));

            // Simpan data
            DB::beginTransaction();
            try {
                $inserted = Post::create($data);

                DB::commit();
                return response()->json([
                    'status' => true,
                    'message' => 'Tambah data berhasil.',
                    'data' => ['id' => encid($inserted->post_id)]
                ]);
            } catch (\Throwable $th) {
                DB::rollBack();
                abort(404, 'Tambah data gagal, ' . $th->getMessage());
            }
        } else if ($param1 == 'label') {
            validate_and_response([
                'postkategori_id' => ['Kategori', 'required'],
                'nama_label' => ['Nama Label ID', 'required'],
                // 'nama_label_en'     => ['Nama Label EN', 'required'],

            ]);

            // insert data
            $data['postkategori_id'] = decid($req->post('postkategori_id'));
            $data['nama_label'] = clean_post('nama_label');
            $data['kode_label'] = createSlug($data['nama_label']);
            $data['deskripsi_label'] = clean_post('deskripsi_label');


            // Simpan data
            if (PostLabel::create($data)) {
                return response()->json([
                    'status' => true,
                    'message' => 'Tambah data berhasil.'
                ]);
            } else {
                abort(500, 'Tambah data gagal, kesalahan database');
            }
        } else if ($param1 == 'kategori') {
            validate_and_response([
                'kode_kategori' => ['Kode Label', 'required'],
                'nama_kategori' => ['Nama Label ID', 'required'],
                // 'nama_kategori_en'     => ['Nama Label EN', 'required'],

            ]);

            // insert data
            $data['kode_kategori'] = strtoupper(clean_post('kode_kategori'));
            $data['nama_kategori'] = clean_post('nama_kategori');
            // $data['nama_kategori_en'] = clean_post('nama_kategori_en');
            $data['deskripsi_kategori'] = clean_post('deskripsi_kategori');


            // Simpan data
            if (PostKategori::create($data)) {
                return response()->json([
                    'status' => true,
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
                'level' => ['Param data', 'required'],
                'judul_post' => ['Judul', 'required'],
                'isi_post' => ['Isi Post', 'required'],
                'meta_keyword_post' => ['Meta Keywords', 'required'],
                'meta_desc_post' => ['Meta Description', 'required'],
                'status_post' => ['Status Post', 'required'],
                'postkategori_id' => ['Kategori Post', 'required'],
                'upload_file' => ['Gambar utama', 'nullable|file|mimes:jpg,png|max:2048']
            ]);

            $id = decid($req->input('id'));
            $currData = Post::findOrFail(decid($req->input('id')));

            if (!$req->has('post_label'))
                abort(422, 'Kolom Label harus diisi');

            // if (!$req->has('post_kategori'))
            //     abort(422, 'Kolom Kategori harus diisi');

            $level = clean_post('level');
            if ($level == 'main-site') {
                $level_id = null;
            } else {
                validate_and_response([
                    'level_id' => ['Param data', 'required'],
                ]);

                $level_id = decid($req->level_id);
            }

            if ($req->hasFile('upload_file')) {
                $do_upload = uploadMedia('upload_file', 'artikel');
                if (!$do_upload['status'])
                    abort(500, 'Update data gagal, ' . $do_upload['message']);

                $data['filename_post'] = $do_upload['data']['filename'];
            }

            $slug_post = createSlug(clean_post('judul_post'));

            // update data
            $data['level'] = $level;
            $data['level_id'] = $level_id;
            $data['judul_post'] = clean_post('judul_post');
            $data['slug_post'] = $slug_post;
            $data['isi_post'] = $req->isi_post;
            $data['tanggal_post'] = clean_post('tanggal_post') ? date('Y-m-d H:i:s', strtotime(clean_post('tanggal_post'))) : null;
            $data['user_id_author'] = null; //clean_post('user_id_author');
            $data['status_post'] = clean_post('status_post');
            $data['postkategori_id'] = decid($req->post('postkategori_id'));

            $data['meta_desc_post'] = clean_post('meta_desc_post');
            $data['meta_keyword_post'] = clean_post('meta_keyword_post');

            // Simpan data
            DB::beginTransaction();
            try {
                if ($slug_post != $currData->slug_post) {
                    $slug_redirect = [];
                    $slug_redirect['post_id'] = $currData->post_id;
                    $slug_redirect['old_slug'] = $currData->slug_post;

                    PostSlugRedirect::create($slug_redirect);
                }

                $currData->update($data);

                PostHasLabel::where('post_id', $id)->forceDelete();
                foreach ($req->post_label as $row) {
                    $data = [];
                    $data['post_id'] = $id;
                    $data['postlabel_id'] = decid($row);

                    PostHasLabel::create($data);
                }

                $data = [];
                $data['post_id'] = $id;
                $data['status_progres'] = clean_post('status_post'); //review responded
                $data['keterangan_progres'] = ''; //$this->generateKeterangan($is_draft ? 'draft' : 'waiting for review');
                $data['catatan_progres'] = null;
                $data['user_id_progres'] = 1;

                PostProgres::create($data);

                DB::commit();
                return response()->json([
                    'status' => true,
                    'message' => 'Update data berhasil.',
                    'data' => ['id' => encid($id)]
                ]);
            } catch (\Throwable $th) {
                DB::rollBack();
                abort(404, 'Update data gagal, ' . $th->getMessage());
            }
        } else if ($param1 == 'label') {
            validate_and_response([
                'id' => ['Param Data', 'required'],
                'kode_label' => ['Kode Label', 'required'],
                'nama_label' => ['Nama Label ID', 'required'],
                // 'nama_label_en'      => ['Nama Label EN', 'required'],

            ]);

            $currData = PostLabel::findOrFail(decid($req->input('id')));

            // Perbarui data
            $data['kode_label'] = strtoupper(clean_post('kode_label'));
            $data['nama_label'] = clean_post('nama_label');
            // $data['nama_label_en'] = clean_post('nama_label_en');
            $data['deskripsi_label'] = clean_post('deskripsi_label');


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
        } else if ($param1 == 'kategori') {
            validate_and_response([
                'id' => ['Param Data', 'required'],
                'kode_kategori' => ['Kode Label', 'required'],
                'nama_kategori' => ['Nama Label ID', 'required'],
                // 'nama_kategori_en'      => ['Nama Label EN', 'required'],

            ]);

            $currData = PostKategori::findOrFail(decid($req->input('id')));

            // Perbarui data
            $data['kode_kategori'] = strtoupper(clean_post('kode_kategori'));
            $data['nama_kategori'] = clean_post('nama_kategori');
            // $data['nama_kategori_en'] = clean_post('nama_kategori_en');
            $data['deskripsi_kategori'] = clean_post('deskripsi_kategori');


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

            $currData = Post::findOrFail(decid($req->input('id')));

            DB::beginTransaction();
            try {

                $data = [];
                $data['post_id'] = $currData->post_id;
                $data['status_progres'] = 'deleted';
                $data['keterangan_progres'] = null;
                $data['catatan_progres'] = null;
                $data['user_id_progres'] = 1;

                PostProgres::create($data);

                $currData->delete();

                DB::commit();
                return response()->json(['status' => true, 'message' => 'Data berhasil dihapus']);
            } catch (\Throwable $th) {
                DB::rollBack();
                abort(404, 'Hapus data gagal, ' . $th->getMessage());
            }
        } else if ($param1 == 'label') {
            validate_and_response([
                'id' => ['Parameter data', 'required'],
            ]);

            $currData = PostLabel::findOrFail(decid($req->input('id')));

            $currData->delete();
            return response()->json(['status' => true, 'message' => 'Data berhasil dihapus']);
        } else if ($param1 == 'kategori') {
            validate_and_response([
                'id' => ['Parameter data', 'required'],
            ]);

            $currData = PostKategori::findOrFail(decid($req->input('id')));

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

            $currData = Post::findOrFail(decid($req->input('id')))->makeHidden(PostKategori::$exceptEdit);

            $currData->id = $req->input('id');
            $currData->scope_target_post = $currData->scope_target_post ? encid($currData->scope_target_post) : null;
            // $currData->scope_target_post1 = encid($currData->scope_target_post);

            $getLabel = PostHasLabel::where('post_id', '=', decid($currData->id))->get();
            $temp = [];

            foreach ($getLabel as $row) {
                $temp[] = encid($row->postlabel_id);
            }
            $currData->post_label = $temp;

            $getKategori = PostHasKategori::where('post_id', '=', decid($currData->id))->get();
            $temp = [];

            foreach ($getKategori as $row) {
                $temp[] = encid($row->postkategori_id);
            }
            $currData->post_kategori = $temp;

            return response()->json(['status' => true, 'message' => 'Data loaded', 'data' => $currData]);
        } else if ($param1 == 'label-detail') {
            validate_and_response([
                'id' => ['Parameter data', 'required'],
            ]);

            $currData = PostLabel::findOrFail(decid($req->input('id')))->makeHidden(PostLabel::$exceptEdit);

            $currData->id = $req->input('id');

            return response()->json(['status' => true, 'message' => 'Data loaded', 'data' => $currData]);
        } else if ($param1 == 'kategori-detail') {
            validate_and_response([
                'id' => ['Parameter data', 'required'],
            ]);

            $currData = PostKategori::findOrFail(decid($req->input('id')))->makeHidden(PostKategori::$exceptEdit);

            $currData->id = $req->input('id');

            return response()->json(['status' => true, 'message' => 'Data loaded', 'data' => $currData]);
        } else if ($param1 == 'list') {
            // custom filter
            $filter = [];
            $filter['p.postkategori_id'] = decid($req->post('postkategori_id'));

            $data = DataTables::of(Post::getDataDetail($filter, false))->rawColumns(['post_label'])->toArray();

            $start = $req->input('start');
            $resp = [];
            foreach ($data['data'] as $key => $value) {
                $dt = [];

                $dt['no'] = ++$start;
                $dt['level'] = $value['level'] ?? '-';
                $dt['level_id'] = $value['level_id'] ?? '-';
                $dt['level_alias'] = $value['level_alias'] ?? '-';
                $dt['judul_post'] = $value['judul_post'] ?? '-';
                $dt['tanggal_post'] = $value['tanggal_post'] ? tanggal($value['tanggal_post']) : '-';
                $dt['updated_at'] = $value['updated_at'] ? tanggal($value['updated_at'], ' ', true) : tanggal($value['created_at'], ' ', true);
                $dt['updated_by'] = $value['updated_by'] ? $value['updated_by'] : $value['created_by'];
                $dt['author'] = $value['user_id_author'] ?? '-';
                $dt['status_post'] = $value['status_post'] ?? '-';
                $dt['slug_post'] = $value['slug_post'] ?? '#';
                $dt['url_post'] = $value['slug_post'] ? url('berita/' . $value['slug_post']) : '#';
                $dt['post_label'] = $value['post_label'] ? json_decode($value['post_label']) : null;
                $dt['nama_kategori'] = $value['nama_kategori'];
                $dt['filename_post'] = publicMedia($value['filename_post'], 'artikel');


                $id = encid($value['post_id']);

                $dataAction = [
                    'id' => $id,
                    'btn' => [
                        ['action' => 'edit', 'link' => route('app.post.show', ['param1' => 'form', 'param2' => $id])],
                        ['action' => 'delete', 'attr' => ['jf-delete' => $id]],
                    ]
                ];

                $dt['action'] = Blade::render('<x-btn.actiontable :id="$id" :btn="$btn"/>', $dataAction);

                $resp[] = $dt;
            }
            $data['data'] = $resp;


            return response()->json($data);
        } else if ($param1 == 'label-list') {
            // custom filter
            $filter = [];

            $data = DataTables::of(PostLabel::where($filter))->toArray();

            $start = $req->input('start');
            $resp = [];
            foreach ($data['data'] as $key => $value) {
                $dt = [];

                $dt['no'] = ++$start;
                $dt['kode_label'] = $value['kode_label'];
                $dt['kode_label'] = $value['kode_label'] ?? '-';
                $dt['nama_label'] = $value['nama_label'] ?? '-';
                $dt['deskripsi_label'] = $value['deskripsi_label'] ?? '-';
                $dt['nama_label_en'] = $value['nama_label_en'] ?? '-';
                $dt['deskripsi_label_en'] = $value['deskripsi_label_en'] ?? '-';


                $id = encid($value['postlabel_id']);

                $dataAction = [
                    'id' => $id,
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

            $data = DataTables::of(PostKategori::where($filter))->toArray();

            $start = $req->input('start');
            $resp = [];
            foreach ($data['data'] as $key => $value) {
                $dt = [];

                $dt['no'] = ++$start;
                $dt['kode_kategori'] = $value['kode_kategori'] ?? '-';
                $dt['nama_kategori'] = $value['nama_kategori'] ?? '-';
                $dt['deskripsi_kategori'] = $value['deskripsi_kategori'] ?? '-';
                $dt['nama_kategori_en'] = $value['nama_kategori_en'] ?? '-';
                $dt['deskripsi_kategori_en'] = $value['deskripsi_kategori_en'] ?? '-';


                $id = encid($value['postkategori_id']);

                $dataAction = [
                    'id' => $id,
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

    function generateKeterangan($status = 'waiting for review')
    {
        if ($status == 'draft') {
            return 'John Doe' . ' menyimpan post ke draft';
        } else if ($status == 'waiting for review') {
            return 'John Doe' . ' melakuan submit post baru, menunggu proses review';
        } else if ($status == 'reviewing') {
            return 'Post saat ini dalam proses review';
        } else if ($status == 'review responded') {
            return 'John Doe' . ' memberikan respon';
        } else if ($status == 'approved') {
            return 'Post disetujui';
        } else if ($status == 'rejected') {
            return 'Post ditolak';
        } else if ($status == 'restore') {
            return 'Post restored';
        } else {
            return false;
        }
    }
}
/* This controller generate by @wahyudibinsaid laravel best practices snippets */
