<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Biblio\Biblio;
use App\Models\Biblio\Item;
use App\Models\Biblio\Loan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class LoanController extends Controller
{
    function __construct() {}

    function index()
    {
        $this->title = 'Data Peminjaman Saya';
        $this->breadCrump[] = ['title' => 'Penalti', 'link' => url()->current()];
        $this->activeMenu = 'loan';

        $builder = app('datatables.html');
        $dataTable = $builder
            ->ajax([
                'url' => route('app.loan.data'),
                'type' => 'GET',
                'data' => 'function(d) { }'
            ])
            ->serverSide(true)
            ->processing(true)
            ->pageLength(10)
            ->dom('Bfrtip')
            ->orderBy(1, 'asc')
            
            ->columns([
                // Column::make(['width' => '80px', 'title' => '', 'data' => 'action', 'orderable' => false, 'searchable' => false, 'className' => 'text-center']),
                Column::make(['title' => 'No ', 'data' => 'DT_RowIndex', 'searchable' => false])->addClass('text-center'),
                Column::make(['title' => 'Kode Item', 'data' => 'item_code']),
                Column::make(['title' => 'Tanggal Pinjam', 'data' => 'loan_date'])->addClass('text-center'),
                Column::make(['title' => 'Jatuh Tempo', 'data' => 'due_date'])->addClass('text-center'),
                Column::make(['title' => 'Sisa Hari', 'data' => 'days_remaining', 'searchable' => false, 'orderable' => false])->addClass('text-center'),
                Column::make(['title' => 'Status', 'data' => 'status_return', 'searchable' => false, 'orderable' => false])->addClass('text-center'),
                Column::make(['title' => 'Tanggal Kembali', 'data' => 'return_date'])->addClass('text-center'),
                Column::make(['title' => 'Penalti', 'data' => 'penalty_status', 'searchable' => false, 'orderable' => false])->addClass('text-center'),

            ]);

        $this->dataView([
            'dataTable' => $dataTable
        ]);

        return $this->view('admin.loan.list');
    }

    function destroy(Request $request): JsonResponse
    {
        validate_and_response([
            'id' => ['Parameter Data', 'required']
        ]);

        $id = decid($request->input('id'));

        // Gunakan connection yang sama seperti di model
        $currData = Loan::on('mysql_opac')->findOrFail($id);

        DB::connection('mysql_opac')->beginTransaction();
        try {
            $currData->delete();
            DB::connection('mysql_opac')->commit();
            return response()->json([
                'status'  => true,
                'message' => 'Hapus data berhasil.',
                'data'    => ['id' => encid($id)]
            ]);
        } catch (\Throwable $e) {
            DB::connection('mysql_opac')->rollBack();
            abort(500, 'Hapus data gagal, kesalahan database: ' . $e->getMessage());
        }
    }

    function data(): JsonResponse
    {
        $user = Auth::user();

        $query = Loan::getDataDetail(['l.member_id' => $user->nomor_induk], false);

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('loan_date', function ($row) {
                return $row->loan_date ? \Carbon\Carbon::parse($row->loan_date)->format('d/m/Y') : '-';
            })
            ->editColumn('due_date', function ($row) {
                return $row->due_date ? \Carbon\Carbon::parse($row->due_date)->format('d/m/Y') : '-';
            })
            ->editColumn('return_date', function ($row) {
                return $row->return_date ? \Carbon\Carbon::parse($row->return_date)->format('d/m/Y') : '-';
            })
            ->editColumn('item_code', function ($row) {
                $itemCode = $row->item_code;
                $biblioTitle = isset($row->title) ? $row->title : 'Unknown Title';
                return '<strong>' . e($biblioTitle)  . '</strong>' . '<br>' . ' <small>' . e($itemCode) . '</small>';
            })
            ->addColumn('days_remaining', function ($row) {
                if ($row->is_return == 1) {
                    return '-';
                }
                $dueDate = \Carbon\Carbon::parse($row->due_date);
                $now = \Carbon\Carbon::now();

                if ($now->gt($dueDate)) {
                    return '<span class="badge badge-danger">Terlambat ' . intval($now->diffInDays($dueDate)) . ' hari</span>';
                }
                return '<span class="badge badge-warning">' . intval($now->diffInDays($dueDate)) . ' hari lagi</span>';
            })
            ->addColumn('status_return', function ($row) {
                $statusClass = $row->is_return == 1 ? 'Sudah Dikembalikan' : 'Belum Dikembalikan';
                $badgeClass = $row->is_return == 1 ? 'badge-success' : 'badge-warning';
                return '<span class="badge ' . $badgeClass . '">' . $statusClass . '</span>';
            })
            ->addColumn('penalty_status', function ($row) {
                if ($row-> penalty_id) {
                    return '<span class="badge badge-danger">Terkena Penalti</span>';
                }
                return '-';
            })
            ->addColumn('action', function ($row) {
                $id = encid($row->loan_id);
                $dataAction = [
                    'id' => $id,
                    'btn' => [
                        ['action' => 'delete', 'attr' => ['jf-delete' => $id]],
                    ]
                ];
                return view('components.btn.actiontable', $dataAction)->render();
            })
            ->rawColumns(['action', 'status_return', 'days_remaining', 'penalty_status', 'item_code'])
            ->make(true);
    }
}
