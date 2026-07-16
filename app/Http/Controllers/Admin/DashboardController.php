<?php

/*
 * Author: @wahyudibinsaid
 * Created At: 2024-06-24 10:12:11
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\Blade;
use App\Models\Master\KaryaJenis;
use App\Models\Biblio\Loan;
use App\Models\Penalties;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    function __construct()
    {
        /**
         * use this if needed
         */
        $this->activeRoot   = '';
        // $this->breadCrump[] = ['title' => 'Dashboard', 'link' => url('')];
        // $this->middleware('permission:dashboard-view', ['only' => ['index', 'data']]);
    }

    function index()
    {
        $this->title        = 'Dashboard';
        $this->activeMenu   = 'dashboard';
        $this->breadCrump[] = ['title' => 'Dashboard', 'link' => url()->current()];

        // Determine target year from latest data or current year
        $latestLoan = Loan::selectRaw('MAX(YEAR(loan_date)) as year')->value('year');
        $targetYear = $latestLoan ? $latestLoan : date('Y');

        // Data Chart 1: Peminjaman per Bulan
        $loansData = Loan::selectRaw('MONTH(loan_date) as month, COUNT(*) as total')
            ->whereYear('loan_date', $targetYear)
            ->groupBy(DB::raw('MONTH(loan_date)'))
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        // Data Chart 2: Peminjaman Belum Kembali per Bulan (Origin Loan Date)
        $unreturnedData = Loan::selectRaw('MONTH(loan_date) as month, COUNT(*) as total')
            ->whereYear('loan_date', $targetYear)
            ->where('is_return', 0)
            ->groupBy(DB::raw('MONTH(loan_date)'))
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        // Data Chart 3: Total Denda per Bulan (Using same target year for consistency)
        $penaltyData = Penalties::selectRaw('MONTH(created_at) as month, SUM(amount) as total')
            ->whereYear('created_at', $targetYear)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        // Prepare data for charts (filling missing months with 0)
        $months = range(1, 12);
        $chartLoans = [];
        $chartUnreturned = [];
        $chartPenalties = [];
        $chartMonths = [];

        foreach ($months as $m) {
            $chartLoans[] = $loansData[$m] ?? 0;
            $chartUnreturned[] = $unreturnedData[$m] ?? 0;
            $chartPenalties[] = $penaltyData[$m] ?? 0;
            $chartMonths[] = date("F", mktime(0, 0, 0, $m, 1));
        }

        $this->dataView([
            'chartYear' => $targetYear,
            'chartLoans' => $chartLoans,
            'chartUnreturned' => $chartUnreturned,
            'chartPenalties' => $chartPenalties,
            'chartMonths' => $chartMonths
        ]);

        return $this->view('admin.dashboard');
    }

    public function show($param1 = '', $param2 = '')
    {
        abort(404, 'Halaman tidak ditemukan');
    }

    function store(Request $req, $param1 = ''): JsonResponse
    {
        if ($param1 == '') {
            validate_and_response([
                'alias'              => ['Kode Karya', 'required'],
                'jenis_karya'        => ['Jenis Karya', 'required'],
                'jenjang_pendidikan' => ['Jenjang Pendidikan', 'required'],

            ]);

            // insert data
            $data['alias']              = clean_post('alias');
            $data['jenis_karya']        = clean_post('jenis_karya');
            $data['jenjang_pendidikan'] = clean_post('jenjang_pendidikan');
            $data['created_by']         = clean_post('created_by');
            $data['updated_by']         = clean_post('updated_by');
            $data['deleted_by']         = clean_post('deleted_by');


            // Simpan data
            if (KaryaJenis::create($data)) {
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
                'id'                 => ['Param Data', 'required'],
                'alias'              => ['Kode Karya', 'required'],
                'jenis_karya'        => ['Jenis Karya', 'required'],
                'jenjang_pendidikan' => ['Jenjang Pendidikan', 'required'],

            ]);

            $currData = KaryaJenis::findOrFail(decid($req->input('id')));

            // Perbarui data
            $data['alias']              = clean_post('alias');
            $data['jenis_karya']        = clean_post('jenis_karya');
            $data['jenjang_pendidikan'] = clean_post('jenjang_pendidikan');
            $data['created_by']         = clean_post('created_by');
            $data['updated_by']         = clean_post('updated_by');
            $data['deleted_by']         = clean_post('deleted_by');


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

            $currData = KaryaJenis::findOrFail(decid($req->input('id')));

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

            $currData = KaryaJenis::findOrFail(decid($req->input('id')))->makeHidden(KaryaJenis::$exceptEdit);

            $currData->id = $req->input('id');

            return response()->json(['status' => true, 'message' => 'Data loaded', 'data' => $currData]);
        } else if ($param1 == 'list') {
            // custom filter
            $filter = [];

            $data = DataTables::of(KaryaJenis::getDataDetail($filter, mode: 'datatable'))->toArray();

            $start = $req->input('start');
            $resp  = [];
            foreach ($data['data'] as $key => $value) {
                $dt = [];

                $dt['no']                 = ++$start;
                $dt['alias']              = $value['alias'];
                $dt['jenis_karya']        = $value['jenis_karya'];
                $dt['jenjang_pendidikan'] = $value['jenjang_pendidikan'];


                $id = encid($value['']);

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
