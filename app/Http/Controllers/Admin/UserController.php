<?php

/*
 * Author: @wahyudibinsaid
 * Created At: {{currTime}}
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Column;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    function __construct()
    {
        /**
         * use this if needed
         */
        $this->activeRoot   = '';
        $this->breadCrump[] = ['title' => '', 'link' => url('')];
        $this->middleware('permission:user-list', ['only' => ['index']]);
        $this->middleware('permission:user-create', ['only' => ['store']]);
        $this->middleware('permission:user-edit', ['only' => ['update']]);
        $this->middleware('permission:user-delete', ['only' => ['destroy']]);
    }

    function index()
    {
        $this->title        = 'Kelola User';
        $this->activeMenu   = 'user';
        $this->breadCrump[] = ['title' => 'Daftar Pengguna', 'link' => url()->current()];

        $builder   = app('datatables.html');
        $dataTable = $builder
            ->ajax([
                'url' => route('app.user.data', ['param1' => 'list']),
                'type' => 'GET',
                'data' => 'function(d) { }'
            ])
            ->serverSide(true)
            ->processing(true)
            ->pageLength(10)
            ->orderBy(1, 'asc')
            ->columns([
                Column::make(['width' => '80px', 'title' => 'Aksi', 'data' => 'action', 'orderable' => false, 'searchable' => false, 'className' => 'text-center']),
                Column::make(['width' => '50px', 'title' => 'No', 'data' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false, 'className' => 'text-center']),
                Column::make(['title' => 'Nama', 'data' => 'name']),
                Column::make(['title' => 'Email', 'data' => 'email']),
                Column::make(['title' => 'Role', 'data' => 'role', 'className' => 'text-center']),
            ]);

        $roles = \Spatie\Permission\Models\Role::pluck('name', 'name')->all();

        // 2. Kirim variabel $roles ke view melalui method dataView().
        $this->dataView([
            'dataTable' => $dataTable,
            'roles' => $roles // <-- Baris ini yang memperbaiki error
        ]);

        return $this->view('admin.user.list');
    }

    public function show($param1 = '', $param2 = '')
    {
        if ($param1 == 'token') {
            $this->title        = 'Token Peminjaman';
            $this->activeMenu   = 'user-token';
            $this->breadCrump[] = ['title' => 'Token Peminjaman', 'link' => url()->current()];

            $this->dataView([]);
            return $this->view('admin.user.token');
        }

        // if ($param1 == 'form') {
        //     $this->title        = 'Form Pengguna';
        //     $this->activeMenu   = 'user';
        //     $this->breadCrump[] = ['title' => 'Form', 'link' => url()->current()];

        //     $dataUser = null;
        //     if ($param2) {
        //         $dataUser = User::with('roles')->findOrFail(decid($param2));
        //     }

        //     $roles = Role::pluck('name', 'id');

        //     $this->dataView(['dataUser' => $dataUser, 'roles' => $roles]);
        //     return $this->view('admin.user.form');
        // }

        abort(404, 'Halaman tidak ditemukan');
    }

    function store(Request $req): JsonResponse
    {
        try {
            validate_and_response([
                'email'    => ['Email', 'required|string|email|max:255|unique:users'],
                'role'     => ['Role', 'required|string', Rule::in(Role::pluck('name')->toArray())],
            ]);

            DB::beginTransaction();
            try {
                // Create user with array data
                $userData = [
                    'name'     => $req->email,
                    'email'    => $req->email,
                    'password' => Hash::make(uniqid()), // Random password
                ];

                $inserted = User::create($userData);

                // Assign Spatie role
                $inserted->assignRole($req->role);

                DB::commit();

                return response()->json([
                    'status'  => true,
                    'message' => 'Email pengguna berhasil didaftarkan.',
                    'data'    => ['id' => encid($inserted->id)]
                ]);
            } catch (\Throwable $e) {
                DB::rollBack();
                abort(500, 'Pendaftaran gagal, terjadi kesalahan pada database: ' . $e->getMessage());
            }
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $e->errors(),
            ], 422);
        }
    }



    function destroy(Request $req): JsonResponse
    {
        try {
            validate_and_response(['id' => ['ID', 'required']]);

            $id = decid($req->input('id'));
            $user = User::findOrFail($id);
            $user->delete();

            return response()->json(['status' => true, 'message' => 'Pengguna berhasil dihapus.']);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    function data(Request $req, $param1 = ''): JsonResponse
    {
        if ($param1 == 'initiate-token') {
            return $this->initiateUserToken();
        }

        if ($param1 == 'list') {
            $query = User::with('roles');

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $id = encid($row->id);
                    $dataAction = [
                        'id' => $id,
                        'btn' => [
                            ['action' => 'delete', 'attr' => ['jf-delete' => $id]]
                        ]
                    ];
                    return view('components.btn.actiontable', $dataAction)->render();
                })
                ->addColumn('role', function ($user) {
                    return $user->roles->first()->name ?? 'N/A';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        if ($param1 == 'detail') {
            try {
                validate_and_response(['id' => ['ID', 'required']]);

                $id = decid($req->input('id'));
                $user = User::findOrFail($id);
                $user->id = encid($user->id); // Enkripsi kembali ID untuk form

                return response()->json(['status' => true, 'message' => 'Data loaded', 'data' => $user]);
            } catch (ValidationException $e) {
                return response()->json([
                    'message' => 'The given data was invalid.',
                    'errors' => $e->errors(),
                ], 422);
            }
        }

        if ($param1 == 'initiate-token') {
            return $this->initiateUserToken();
        }

        abort(404, 'Data tidak ditemukan');
    }

    public function initiateUserToken()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda harus login terlebih dahulu untuk meminjam buku.'
            ], 401);
        }

        // Debug info
        Log::info('initiateUserToken called', [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'nomor_induk' => $user->nomor_induk
        ]);

        // Pastikan user punya nomor_induk
        if (empty($user->nomor_induk)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Nomor induk user belum diatur. Hubungi administrator.'
            ], 400);
        }

        // Generate unique token
        $token = Str::uuid()->toString();

        // Simpan data sesi di cache selama 10 menit
        $cacheKey = 'user_token_' . $token;
        Cache::put($cacheKey, [
            'user_id'   => $user->id,
            'name'      => $user->name,
            'nomor_induk' => $user->nomor_induk
        ], now()->addMinutes(10));

        $verifyToken = cache()->get($cacheKey);
        if (!$verifyToken) {
            Log::error('Failed to store token in cache', ['token' => $token]);
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyimpan token. Silakan coba lagi.'
            ], 500);
        }

        Log::info('Token generated successfully', [
            'token' => substr($token, 0, 10) . '...',
            'cache_key' => $cacheKey
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Token peminjaman berhasil dibuat.',
            'token' => $token,
            'expires_in' => 600, // 10 menit dalam detik
            'expiration' => now()->addMinutes(10)->toDateTimeString()
        ], 200);
    }

    

}
/* This controller generate by @wahyudibinsaid laravel best practices snippets */