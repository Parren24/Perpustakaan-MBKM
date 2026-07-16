<?php

/*
 * Author: @wahyudibinsaid
 * Modified For: Fines Management (Native Fines Table)
 * Created At: {{currTime}}
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Column;
use App\Models\Biblio\Fine;
use App\Models\Biblio\Loan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class FinesController extends Controller
{
    function __construct()
    {
        $this->activeRoot   = '';
    }

    function index()
    {
        $this->title        = 'Kelola Denda';
        $this->activeMenu   = 'fines';
        $this->breadCrump[] = ['title' => 'Denda', 'link' => url()->current()];

        $builder   = app('datatables.html');
        $dataTable = $builder
        ->serverSide(true)
        ->ajax(route('app.fines.data', ['param1' => 'list']))
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
            Column::make(['width' => '', 'title' => 'Total Denda', 'data' => 'debet', 'className' => '']),
            Column::make(['width' => '', 'title' => 'Dibayar', 'data' => 'credit', 'className' => '']),
            Column::make(['width' => '', 'title' => 'Status', 'data' => 'status', 'className' => 'text-center']),
        ]);

        $this->dataView([
            'dataTable' => $dataTable
        ]);

        return $this->view('admin.fines.list');
    }

    public function show($param1 = '', $param2 = '')
    {
        if ($param1 == 'form') {
            $this->title        = 'Form Denda';
            $this->activeMenu   = 'fines';
            $this->breadCrump[] = ['title' => 'Denda', 'link' => route('app.fines.index')];
            $this->breadCrump[] = ['title' => 'Form', 'link' => url()->current()];

            $dataForm = null;
            if ($param2 != '') {
                $id       = decid($param2);
                $dataForm = Fine::findOrFail($id);
            }

            $this->dataView([
                'dataForm' => $dataForm,
            ]);

            return $this->view('admin.fines.form');
        } else {
            abort(404, 'Halaman tidak ditemukan');
        }
    }

    function update(Request $req, $param1 = '', $param2 = ''): JsonResponse
    {
        if ($param1 == '') {
            validate_and_response([
                'id' => ['Param Data', 'required'],
                'status' => ['Status', 'required', 'in:PENDING,PAID'] // Form frontend tetap mengirim ini
            ]);

            $currData = Fine::findOrFail(decid($req->input('id')));

            // Cek status lunas berdasarkan native kolom: credit >= debet
            if ($currData->credit >= $currData->debet) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data ini sudah LUNAS (PAID) dan tidak dapat diedit.'
                ]);
            }

            $isPaidInput = $req->input('status') === 'PAID';

            if ($isPaidInput) {
                // Set lunas dengan menyamakan nilai credit dengan debet
                $currData->credit = $currData->debet;
                
                // Update data Loan
                $loan = Loan::where('loan_id', $currData->loan_id)->first();
                if ($loan && $loan->is_return == 0) {
                    $loan->is_return = 1;
                    $loan->return_date = Carbon::now();
                    $loan->save();
                }
            } else {
                // Set pending (reset credit jika diperlukan, atau bisa dibiarkan)
                $currData->credit = 0;
            }

            // Simpan perubahan
            if ($currData->save()) {
                return response()->json([
                    'status'  => true,
                    'message' => 'Update data denda berhasil.',
                    'data'    => null,
                ]);
            } else {
                abort(500, 'Update data gagal, kesalahan database');
            }
        } 
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

            $id = decid($req->input('id'));
            
            $databasePeminjaman = config('database.connections.mysql_primary.database');
            $currData = DB::connection('mysql_opac')
            ->table('fines')
            ->select(
                'fines.*',
                // Pastikan menggunakan alias 'member_name' agar sesuai dengan <input name="member_name">
                'm.member_name as member_name', 
                // Tarik data dari loan untuk ditampilkan di modal
                'l.item_code',
                'l.loan_date',
                'l.due_date'
            )
            ->join('member as m', 'fines.member_id', '=', 'm.member_id')
            // Tambahkan join ke tabel loan
            ->leftJoin('loan as l', 'fines.loan_id', '=', 'l.loan_id')
            ->where('fines.fines_id', $id)
            ->first();

            // Format tanggal agar lebih rapi di form (Opsional, tergantung kebutuhan JForm Anda)
            if ($currData) {
                if ($currData->loan_date) {
                    $currData->loan_date = Carbon::parse($currData->loan_date)->format('Y-m-d');
                }
                if ($currData->due_date) {
                    $currData->due_date = Carbon::parse($currData->due_date)->format('Y-m-d');
                }
            }

            if (!$currData) {
               abort(404, 'Data tidak ditemukan');
            }

            $currData->id = $req->input('id'); 
            // Tambahkan virtual status untuk detail response
            $currData->status = ($currData->credit >= $currData->debet) ? 'PAID' : 'PENDING';

            return response()->json(['status' => true, 'message' => 'Data loaded', 'data' => $currData]);
            
        } else if ($param1 == 'list') {
            // custom filter
            $query = Fine::getDataDetail([], [], false);

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('name', function ($row) {
                    $name = $row->name ?? '-';
                    // Catatan: Pastikan kolom ID yang Anda panggil benar.
                    // Jika dari tabel fines adanya member_id, gunakan $row->member_id
                    $id = $row->member_id ?? ($row->id ?? '-'); 
                    return '<strong>' . e($name)  . '</strong><br><small>' . e($id) . '</small>';
                })
                ->editColumn('loan_date', function ($row) {
                    return $row->loan_date ? Carbon::parse($row->loan_date)->format('d/m/Y') : '-';
                })
                ->editColumn('due_date', function ($row) {
                    return $row->due_date ? Carbon::parse($row->due_date)->format('d/m/Y') : '-';
                })
                ->editColumn('debet', function ($row) {
                    return 'Rp ' . number_format($row->debet, 0, ',', '.');
                })
                ->editColumn('credit', function ($row) {
                    return 'Rp ' . number_format($row->credit, 0, ',', '.');
                })
                ->editColumn('status', function ($row) {
                    // Penentuan status dinamis murni dari kalkulasi credit vs debet
                    $status = ($row->credit >= $row->debet) ? 'PAID' : 'PENDING';
                    $badgeClass = $status == 'PAID' ? 'badge-success' : 'badge-warning';
                    return '<span class="badge ' . $badgeClass . '">' . $status . '</span>';
                })
                ->editColumn('item_code', function ($row) {
                    $itemCode = $row->item_code ?? '-';
                    $biblioTitle = isset($row->biblio_title) ? $row->biblio_title : 'Unknown Title';
                    return '<strong>' . e($biblioTitle)  . '</strong><br><small>' . e($itemCode) . '</small>';
                })
                ->addColumn('action', function ($row) {
                    $id = encid($row->fines_id); 
                    
                    $btn = [];
                    // Hanya tampilkan tombol edit jika belum lunas
                    if ($row->credit < $row->debet) {
                        $btn[] = ['action' => 'edit', 'attr' => ['jf-edit' => $id]];
                    }

                    $dataAction = [
                        'id' => $id,
                        'btn' => $btn
                    ];
                    return view('components.btn.actiontable', $dataAction)->render();
                })
                ->rawColumns(['action', 'status', 'item_code', 'name'])
                ->make(true);
        } 
        else {
            abort(404, 'Halaman tidak ditemukan');
        }
    }
}