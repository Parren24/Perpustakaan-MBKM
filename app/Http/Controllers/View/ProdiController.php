<?php

/*
 * Author: @wahyudibinsaid
 * Created At: {{currTime}}
 */

namespace App\Http\Controllers\View;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Column;
use App\Models\View\Prodi;

class ProdiController extends Controller
{
    function __construct()
    {
        /**
         * use this if needed
         */
        $this->activeRoot   = '{{routePrefix}}';
        $this->breadCrump[] = ['title' => '', 'link' => url('')];
    }

    function index()
    {
        abort(404, 'Halaman tidak ditemukan');
    }

    public function show($param1 = '', $param2 = '')
    {
        abort(404, 'Halaman tidak ditemukan');
    }

    function store(Request $req, $param1 = ''): JsonResponse
    {
        abort(404, 'Halaman tidak ditemukan');
    }

    function update(Request $req, $param1 = '', $param2 = ''): JsonResponse
    {
        abort(404, 'Halaman tidak ditemukan');
    }

    function destroy(Request $req, $param1 = ''): JsonResponse
    {
        abort(404, 'Halaman tidak ditemukan');
    }

    function data(Request $req, $param1 = '', $param2 = ''): JsonResponse
    {
        if ($param1 == 'detail') {
            validate_and_response([
                'id' => ['Parameter data', 'required'],
            ]);

            $currData = Prodi::findOrFail(decid($req->input('id')))->makeHidden(Prodi::$exceptEdit);

            $currData->id         = $req->input('id');

            return response()->json(['status' => true, 'message' => 'Data loaded', 'data' => $currData]);
        } else if ($param1 == 'list') {
            // custom filter
            $filter = [];

            $data = DataTables::of(Prodi::getDataDetail($filter, get: false))->toArray();

            $start = $req->input('start');
            $resp  = [];
            foreach ($data['data'] as $key => $value) {
                $dt = [];

                $dt['no']                    = ++$start;

                $id = encid($value['prodi_id']);

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
