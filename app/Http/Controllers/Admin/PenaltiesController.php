<?php

/*
 * Author: @wahyudibinsaid
 * Created At: {{currTime}}
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Column;
use App\Models\Penalties;
use App\Models\Biblio\Loan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Services\DataTable;

class PenaltiesController extends Controller
{
    function __construct()
    {
        /**
         * use this if needed
         */
         $this->activeRoot   = '';
         
    }

    function index()
    {
        $this->title        = 'Kelola Penalti';
        $this->activeMenu   = 'penalties';
        $this->breadCrump[] = ['title' => 'Penalti', 'link' => url()->current()];

        $builder   = app('datatables.html');
        $dataTable = $builder
        ->serverSide(true)
        ->ajax(route('app.penalties.data', ['param1' => 'list']))
        ->processing(true)
        ->pageLength(10)
        ->dom('Bfrtip')
        ->orderBy(1, 'desc')
        ->columns([
            Column::make(['width' => '', 'title' => '', 'data' => 'action', 'orderable' => false, 'searchable' => false, 'className' => 'text-nowrap text-end']),
            Column::make(['width' => '', 'title' => 'Nama', 'data' => 'name', 'className' => '']),
            Column::make(['width' => '', 'title' => 'Item Code', 'data' => 'item_code', 'className' => '']),
            Column::make(['width' => '', 'title' => 'Loan Date', 'data' => 'loan_date', 'className' => '']),
            Column::make(['width' => '', 'title' => 'Due Date', 'data' => 'due_date', 'className' => '']),
            Column::make(['width' => '', 'title' => 'Amount', 'data' => 'amount', 'className' => '']),
            Column::make(['width' => '', 'title' => 'Status', 'data' => 'status', 'className' => '']),
            Column::make(['width' => '', 'title' => 'Approve By', 'data' => 'approve_by', 'className' => '']),
            Column::make(['width' => '', 'title' => 'Approve At', 'data' => 'approve_at', 'className' => '']),
        ]);

        $this->dataView([
            'dataTable' => $dataTable
        ]);

        return $this->view('admin.penalties.list');
    }

    public function show($param1 = '', $param2 = '')
    {
        if ($param1 == 'form') {
            $this->title        = 'Form Penalti';
            $this->activeMenu   = 'penalties';
            $this->breadCrump[] = ['title' => 'Penalti', 'link' => route('app.penalties.index')];
            $this->breadCrump[] = ['title' => 'Form', 'link' => url()->current()];

            $dataForm = null;
            if ($param2 != '') {
                $id       = decid($param2);
                $dataForm = Penalties::findOrFail($id);
            }

            $this->dataView([
                'dataForm' => $dataForm,
            ]);

            return $this->view('admin.penalties.form');
        } else {
            abort(404, 'Halaman tidak ditemukan');
        }
    }

    // function store(Request $req, $param1 = ''): JsonResponse
    // {
    //     if ($param1 == '') {
    //         validate_and_response([
    //             {{validationField}}
    //         ]);

    //         // insert data
    //         {{dataStore}}

    //         // Simpan data
    //         if ({{modelName}}::create($data)) {
    //             return response()->json([
    //                 'status'  => true,
    //                 'message' => 'Tambah data berhasil.'
    //             ]);
    //         } else {
    //             abort(500, 'Tambah data gagal, kesalahan database');
    //         }
    //     } 

    //     // default
    //     else {
    //         abort(404, 'Halaman tidak ditemukan');
    //     }
    // }

    

    function update(Request $req, $param1 = '', $param2 = ''): JsonResponse
    {
        if ($param1 == '') {
            validate_and_response([
                'id' => ['Param Data', 'required'],
                'status' => ['Status', 'required', 'in:PENDING,PAID']
            ]);

            $currData = Penalties::findOrFail(decid($req->input('id')));

            if ($currData->status === 'PAID') {
                return response()->json([
                    'status' => false,
                    'message' => 'Data dengan status PAID tidak dapat diedit.'
                ]);
            }

            $oldStatus = $currData->status;

            // Perbarui data
            $currData->status = $req->input('status');
            $currData->approve_by = auth()->user()->name;
            $currData->approve_at = Carbon::now();
            
            // Jika status berubah menjadi PAID, update data Loan
            if ($oldStatus !== 'PAID' && $currData->status === 'PAID') {
                $loan = Loan::where('loan_id', $currData->loan_id)->first();
                if ($loan) {
                    $loan->is_return = 1;
                    $loan->return_date = Carbon::now();
                    $loan->save();
                }
            }

            // Simpan perubahan
            if ($currData->save()) {
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
            validate_and_response([
                'id' => ['Parameter data', 'required'],
            ]);

            $id = decid($req->input('id'));
            
            // Menggunakan getDataDetail untuk mendapatkan data lengkap termasuk nama user
            $currData = DB::connection('mysql_primary')
            ->table('penalties')
            ->select('u.name as nama', 'u.name as member_name', 'penalties.*') // select nama as member_name for displaying
            ->join('users as u', 'penalties.user_id', '=', 'u.id')
            ->where('penalties.penalty_id', $id)
            ->first();

            if (!$currData) {
               abort(404, 'Data tidak ditemukan');
            }

            $currData->id = $req->input('id'); 

            return response()->json(['status' => true, 'message' => 'Data loaded', 'data' => $currData]);
        } else if ($param1 == 'list') {
            // custom filter
            $query = Penalties::getDataDetail([], [], false);

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('loan_date', function ($row) {
                    return $row->loan_date ? Carbon::parse($row->loan_date)->format('d/m/Y') : '-';
                })
                ->editColumn('due_date', function ($row) {
                    return $row->due_date ? Carbon::parse($row->due_date)->format('d/m/Y') : '-';
                })
                ->editColumn('amount', function ($row) {
                    return 'Rp ' . number_format($row->amount, 0, ',', '.');
                })
                ->editColumn('status', function ($row) {
                    $badgeClass = $row->status == 'PAID' ? 'badge-success' : 'badge-warning';
                    return '<span class="badge ' . $badgeClass . '">' . $row->status . '</span>';
                })
                ->editColumn('item_code', function ($row) {
                    $itemCode = $row->item_code;
                    $biblioTitle = isset($row->biblio_title) ? $row->biblio_title : 'Unknown Title';
                    return '<strong>' . e($biblioTitle)  . '</strong>' . '<br>' . ' <small>' . e($itemCode) . '</small>';
                })
                ->addColumn('action', function ($row) {
                    $id = encid($row->penalty_id);
                    
                    $btn = [];
                    // Hanya tampilkan tombol edit jika status belum PAID
                    if ($row->status !== 'PAID') {
                        $btn[] = ['action' => 'edit', 'attr' => ['jf-edit' => $id]];
                    }

                    $dataAction = [
                        'id' => $id,
                        'btn' => $btn
                    ];
                    return view('components.btn.actiontable', $dataAction)->render();
                })
                ->rawColumns(['action', 'status', 'item_code'])
                ->make(true);

            
        } 

        // default
         else {
            abort(404, 'Halaman tidak ditemukan');
        }
    }
}
/* This controller generate by @wahyudibinsaid laravel best practices snippets */