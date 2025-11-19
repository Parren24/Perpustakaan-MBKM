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
                Column::make(['width' => '80px', 'title' => '', 'data' => 'action', 'orderable' => false, 'searchable' => false, 'className' => 'text-center']),
                Column::make(['title' => 'Kode Item', 'data' => 'item_code']),
                Column::make(['title' => 'Tanggal Pinjam', 'data' => 'loan_date']),
                Column::make(['title' => 'Tanggal Jatuh Tempo', 'data' => 'due_date']),
                Column::make(['title' => 'Tanggal Kembali', 'data' => 'return_date']),

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
            ->rawColumns(['action'])
            ->make(true);
    }
}
