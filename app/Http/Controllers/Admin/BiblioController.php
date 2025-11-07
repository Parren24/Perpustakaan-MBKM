<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Biblio\Biblio;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BiblioController extends Controller
{
    function __construct()
    {
        $this->activeRoot   = 'biblio';
        $this->breadCrump[] = ['title' => 'Biblio', 'link' => url('#')];
        $this->middleware('permission:biblio-list', ['only' => ['index', 'data']]);
        $this->middleware('permission:biblio-create', ['only' => ['store']]);
        $this->middleware('permission:biblio-edit', ['only' => ['update']]);
        $this->middleware('permission:biblio-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    function index()
    {
        
        $this->title        = 'Kelola Biblio';
        $this->activeMenu   = 'biblio';
        $this->breadCrump[] = ['title' => 'Biblio', 'link' => url()->current()];

        $builder = app('datatables.html');
        $dataTable = $builder
            ->ajax([
                'url' => route('app.biblio.data', ['param1' => 'list']),
                'type' => 'GET',
                'data' => 'function(d) { }'
            ])
            ->serverSide(true)
            ->processing(true)
            ->pageLength(10)
            ->dom('Bfrtip')
            ->orderBy(1, 'asc')
            ->columns([
                Column::make(['width' => '80px', 'title' => '', 'data' => 'action', 'orderable' => false, 'searchable' => false, 'className' => 'text-center']),
                Column::make(['width' => '50px', 'title' => 'No', 'data' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false, 'className' => 'text-center']),
                Column::make(['title' => 'Judul', 'data' => 'title']),
                Column::make(['title' => 'Penulis', 'data' => 'author_name']),
                Column::make(['width' => '100px', 'title' => 'Tahun', 'data' => 'publish_year', 'className' => 'text-center']),
                Column::make(['title' => 'Penerbit', 'data' => 'publisher_name']),
                Column::make(['width' => '80px', 'title' => 'Stok', 'data' => 'total_items', 'className' => 'text-center']),
            ]);

        $this->dataView([
            'dataTable' => $dataTable
        ]);

        return $this->view('admin.biblio.list');
    }

    /**
     * Store a newly created resource in storage.
     */
    function store(Request $req, $param1 = ''): JsonResponse
    {
        // Check permission


        if ($param1 == '') {
            validate_and_response([
                'title' => ['Judul', 'required|max:255'],
                'author' => ['Penulis', 'required|max:255'],
                'description' => ['Deskripsi', 'nullable'],
                'year' => ['Tahun', 'required|integer'],
                'publisher' => ['Penerbit', 'required|max:255'],
                'stock' => ['Stok', 'required|integer'],
            ]);

            $data['title'] = clean_post('title');
            $data['author'] = clean_post('author');
            $data['description'] = clean_post('description');
            $data['year'] = clean_post('year');
            $data['publisher'] = clean_post('publisher');
            $data['stock'] = clean_post('stock');

            DB::beginTransaction();
            try {
                $inserted = Biblio::create($data);
                DB::commit();
                return response()->json([
                    'status'  => true,
                    'message' => 'Tambah data berhasil.',
                    'data'    => ['id' => encid($inserted->biblio_id)]
                ]);
            } catch (\Throwable $e) {
                DB::rollBack();
                abort(500, 'Tambah data gagal, kesalahan database: ' . $e->getMessage());
            }
        } else {
            abort(404, 'Halaman tidak ditemukan');
        }
    }

    // Hapus parameter $param1, $param2 dan blok if/else
    function update(Request $req): JsonResponse
    {
        try {
            // 1. Jalankan validasi di dalam blok 'try'.
            // Jika validasi gagal, kode ini akan "melemparkan" error dan langsung loncat ke blok 'catch'.
            validate_and_response([
                'id'          => ['Parameter data', 'required'],
                'title'       => ['Judul', 'required|max:255'],
                'author'      => ['Penulis', 'required|max:255'],
                'description' => ['Deskripsi', 'nullable'],
                'year'        => ['Tahun', 'required|integer'],
                'publisher'   => ['Penerbit', 'required|max:255'],
                'stock'       => ['Stok', 'required|integer'],
            ]);

            // 2. Kode di bawah ini HANYA akan berjalan jika validasi BERHASIL.
            $data = [
                'title'       => clean_post('title'),
                'author'      => clean_post('author'),
                'description' => clean_post('description'),
                'year'        => clean_post('year'),
                'publisher'   => clean_post('publisher'),
                'stock'       => clean_post('stock'),
            ];

            $id = decid($req->input('id'));
            $currData = Biblio::findOrFail($id);

            DB::beginTransaction();
            try {
                $currData->update($data);
                DB::commit();

                return response()->json([
                    'status'  => true,
                    'message' => 'Ubah data berhasil.',
                    'data'    => ['id' => encid($id)]
                ]);
            } catch (\Throwable $e) {
                DB::rollBack();
                abort(500, 'Ubah data gagal, kesalahan database: ' . $e->getMessage());
            }
        } catch (ValidationException $e) {
            // 3. Tangkap error validasi di sini.
            // Ubah error tersebut menjadi respons JSON yang benar.
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    public function show($param1 = '', $param2 = '')
    {
        if ($param1 == 'form') {
            // Check permission for edit


            $this->title        = 'Form Biblio';
            $this->activeMenu   = 'biblio';
            $this->breadCrump[] = ['title' => 'Form', 'link' => url()->current()];

            $get_biblio = Biblio::getDataDetail(['biblio_id' => decid($param2)])->first();

            $dataBiblio = [
                'id' => encid($get_biblio->biblio_id),
                'title' => $get_biblio->title,
                'author' => $get_biblio->author,
                'description' => $get_biblio->description,
                'year' => $get_biblio->year,
                'publisher' => $get_biblio->publisher,
                'stock' => $get_biblio->stock,
            ];


            $this->dataView([
                'dataBiblio' => $dataBiblio,
            ]);

            return $this->view('admin.biblio.form');
        }
    }

    // /**
    //  * Update the specified resource in storage.
    //  */
    // function update(Request $req, $param1 = ''): JsonResponse
    // {
    //     // Check permission


    //     if ($param1 == '') {
    //         validate_and_response([
    //             'id' => ['ID', 'required'],
    //             'title' => ['Judul', 'required|max:255'],
    //             'author' => ['Penulis', 'required|max:255'],
    //             'description' => ['Deskripsi', 'nullable'],
    //             'year' => ['Tahun', 'required|integer'],
    //             'publisher' => ['Penerbit', 'required|max:255'],
    //             'stock' => ['Stok', 'required|integer'],
    //         ]);

    //         $biblio = Biblio::findOrFail(decid($req->id));

    //         $data = [
    //             'title' => clean_post('title'),
    //             'author' => clean_post('author'),
    //             'description' => clean_post('description'),
    //             'year' => clean_post('year'),
    //             'publisher' => clean_post('publisher'),
    //             'stock' => clean_post('stock'),
    //         ];

    //         if ($biblio->update($data)) {
    //             return response()->json([
    //                 'status'  => true,
    //                 'message' => 'Update data berhasil.',
    //                 'data'    => ['id' => encid($biblio->biblio_id)]
    //             ]);
    //         } else {
    //             abort(500, 'Update data gagal, kesalahan database');
    //         }
    //     }

    //     abort(404, 'Halaman tidak ditemukan');
    // }

    /**
     * Remove the specified resource from storage.
     */



    function destroy(Request $req, $param1 = ''): JsonResponse
    {

        if ($param1 == '') {
            validate_and_response([
                'id' => ['Parameter data', 'required'],
            ]);

            $id = decid($req->input('id'));
            $currData = Biblio::findOrFail($id);

            DB::beginTransaction();
            try {
                $currData->delete();
                DB::commit();

                return response()->json([
                    'status'  => true,
                    'message' => 'Hapus data berhasil.',
                    'data'    => ['id' => encid($id)]
                ]);
            } catch (\Throwable $e) {
                DB::rollBack();
                abort(500, 'Hapus data gagal, kesalahan database: ' . $e->getMessage());
            }
        } else {
            abort(404, 'Halaman tidak ditemukan');
        }
    }

    function data(Request $req, $param1 = ''): JsonResponse
    {
        // Check permission
        if ($param1 == 'list') {
            // Pass 'false' to getDataDetail to get the query builder instance, not the collection.
            $query = Biblio::getDataDetail([], [], false);

            return DataTables::of($query)
                // Creates the 'no' column automatically, handling pagination.
                ->addIndexColumn()

                 ->addColumn('author_name', function ($row) {
                    return $row->authors->pluck('author_name')->implode(', ');
                })
                // Add publisher_name column
                ->addColumn('publisher_name', function ($row) {
                    return $row->publisher ? $row->publisher->publisher_name : '-';
                })
                
                // Creates the 'action' column.
                ->addColumn('action', function ($row) {
                    $id = encid($row->biblio_id);
                    $dataAction = [
                        'id' => $id,
                        'btn' => [
                            ['action' => 'edit', 'link' => route('app.biblio.show', ['param1' => 'form', 'param2' => $id])],
                            ['action' => 'delete', 'attr' => ['jf-delete' => $id]],
                        ]
                    ];
                    // Render the button view component for each row.
                    return view('components.btn.actiontable', $dataAction)->render();
                })
                // Tells DataTables that the 'action' column contains HTML and should not be escaped.
                ->rawColumns(['action'])
                // This is the most important part: it builds and returns the final, correctly formatted JSON response.
                ->make(true);
        } else if ($param1 == 'detail') {
            validate_and_response([
                'id' => ['Parameter data', 'required'],
            ]);

            $currData = Biblio::findOrFail(decid($req->input('id')))->makeHidden(Biblio::$exceptEdit);
            $currData->id = $req->input('id');

            return response()->json(['status' => true, 'message' => 'Data loaded', 'data' => $currData]);
        }

        abort(404, 'Data tidak ditemukan');
    }
}
