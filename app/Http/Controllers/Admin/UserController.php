<?php

/*
 * Author: @wahyudibinsaid
 * Created At: {{currTime}}
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Blade;
use Yajra\DataTables\DataTables;
use App\Models\Biblio\Biblio;
use Yajra\DataTables\Html\Column;
use App\Models\User;
use App\Models\Biblio\Member;
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
use App\Models\Biblio\Loan;

class UserController extends Controller
{
    function __construct()
    {
        /**
         * use this if needed
         */
        $this->activeRoot   = '';
        $this->breadCrump[] = ['title' => '', 'link' => url('')];
        // $this->middleware('permission:user-list', ['only' => ['index']]);
        // $this->middleware('permission:user-create', ['only' => ['store']]);
        // $this->middleware('permission:user-edit', ['only' => ['update']]);
        // $this->middleware('permission:user-delete', ['only' => ['destroy']]);
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

        abort(404, 'Halaman tidak ditemukan');
    }

    // public function syncAPIMahasiswa(Request $req): JsonResponse
    // {
    //     // 1. Validasi Input
    //     $angkatan = $req->input('angkatan');
    //     $prodi    = $req->input('prodi');
    //     $apiKey = trim(config('services.pcr.key'));

    //     // 2. Tembak API
    //     $response = Http::withHeaders([
    //         'apikey' => $apiKey,
    //         'Accept' => 'application/json'
    //     ])->withQueryParameters([
    //         // Ini sesuai tab Params di image pertama
    //         'collection' => 'angkatan-prodi',
    //         'angkatan'   => $angkatan,
    //         'prodi'      => $prodi,
    //     ])->post('https://v2.api.pcr.ac.id/api/akademik-mahasiswa');

    //     if ($response->successful()) {
    //         $mahasiswaList = $response->json('items') ?? $response->json('data') ?? [];

    //         if (empty($mahasiswaList)) {
    //             return response()->json(['status' => false, 'message' => 'Tidak ada data ditemukan. Response: ' . $response->body()], 404);
    //         }

    //         $dataToSync = [];
    //         $allNims    = [];
    //         // Optimization: Calculate password hash once
    //         $defaultPassword = Hash::make('pcr123');

    //         foreach ($mahasiswaList as $mahasiswa) {

    //             $nim = $mahasiswa['nim'];
    //             $allNims[] = $nim;

    //             $dataToSync[] = [
    //                 'nomor_induk' => $nim,
    //                 'name'        => $mahasiswa['nama'],
    //                 'email'       => $mahasiswa['email'],
    //                 'prodi'       => $prodi, // Dinamis berdasarkan input
    //                 'posisi'      => 'Mahasiswa', // Tetap mahasiswa
    //                 'password'    => $defaultPassword,
    //                 'created_at'  => now(),
    //                 'updated_at'  => now(),
    //             ];
    //         }

    //         // 3. UPSERT: Update jika NIM sudah ada, Create jika belum ada.
    //         User::upsert($dataToSync, ['nomor_induk'], ['name', 'email', 'prodi', 'posisi', 'updated_at']);

    //         // 4. Assign Roles & Sync to OPAC
    //         // Optimization: Fetch only affected users in one query
    //         $users = User::whereIn('nomor_induk', $allNims)->get();

    //         foreach ($users as $user) {
    //             $user->assignRole('member'); // Assign role member

    //             // Sync to OPAC
    //             try {
    //                 $this->insertToOpac([
    //                     'nomor_induk' => $user->nomor_induk,
    //                     'email'       => $user->email,
    //                     'password'    => $user->password, // Already hashed
    //                 ]);
    //             } catch (\Throwable $e) {
    //                 Log::error("Gagal sync ke OPAC untuk NIM {$user->nomor_induk}: " . $e->getMessage());
    //             }
    //         }

    //         return response()->json([
    //             'status' => true,
    //             'message' => "Berhasil sinkronisasi " . count($dataToSync) . " mahasiswa."
    //         ]);
    //     }

    //     return response()->json([
    //         'status' => false,
    //         'message' => 'Gagal terhubung ke API PCR (Mahasiswa): ' . $response->body()
    //     ], 500);
    // }

    // public function syncAPIPegawai(): JsonResponse
    // {
    //     // 1. Tembak API
    //     $response = Http::withHeaders([
    //         'apikey' => config('services.pcr.key'),
    //         'Accept' => 'application/json'

    //     ])->withQueryParameters([
    //         // Ini sesuai tab Params di image pertama
    //         'collection' => 'pegawai-aktif'
    //     ])
    //         ->post('https://v2.api.pcr.ac.id/api/pegawai', [
    //             'collection' => 'pegawai-aktif',
    //             'pagesize' => 500
    //         ]);

    //     if ($response->successful()) {
    //         $pegawaiList = $response->json('items') ?? $response->json('data') ?? [];

    //         if (empty($pegawaiList)) {
    //             return response()->json(['status' => false, 'message' => 'Tidak ada data ditemukan. Response: ' . $response->body()], 404);
    //         }

    //         $dataToSync = [];
    //         $allNips    = [];
    //         // Optimization: Calculate password hash once
    //         $defaultPassword = Hash::make('pcr123');

    //         foreach ($pegawaiList as $pegawai) {
    //             if (isset($pegawai['posisi']) && strtolower($pegawai['posisi']) === 'dosen') {
    //                 $nip = $pegawai['nip'];
    //                 $allNips[] = $nip;
    //                 $user = User::where('nomor_induk', $nip)->first();
    //                 if ($user) {
    //                     $user->assignRole('member');
    //                 }

    //                 $dataToSync[] = [
    //                     'nomor_induk' => $nip,
    //                     'name'        => $pegawai['nama'],
    //                     'inisial'     => $pegawai['inisial'],
    //                     'email'       => $pegawai['email'],
    //                     'posisi'      => $pegawai['posisi'], // Dinamis berdasarkan data API
    //                     'password'    => $defaultPassword,
    //                     'created_at'  => now(),
    //                     'updated_at'  => now(),
    //                 ];
    //             }
    //         }

    //         if (empty($dataToSync)) {
    //             return response()->json(['status' => false, 'message' => 'Tidak ada data dosen ditemukan. Response: ' . $response->body()], 404);
    //         }

    //         // Upsert User
    //         User::upsert($dataToSync, ['nomor_induk'], ['name', 'email', 'inisial', 'posisi', 'updated_at']);

    //         // 4. Assign Roles & Sync to OPAC
    //         // Optimization: Fetch only affected users in one query
    //         $users = User::whereIn('nomor_induk', $allNips)->get();

    //         foreach ($users as $user) {
    //             // Sync to OPAC
    //             try {
    //                 $this->insertToOpac([
    //                     'nomor_induk' => $user->nomor_induk,
    //                     'email'       => $user->email,
    //                     'password'    => $user->password,
    //                 ]);
    //             } catch (\Throwable $e) {
    //                 Log::error("Gagal sync ke OPAC untuk NIP {$user->nomor_induk}: " . $e->getMessage());
    //             }
    //         }

    //         return response()->json([
    //             'status' => true,
    //             'message' => "Berhasil sinkronisasi " . count($dataToSync) . " pegawai."
    //         ]);
    //     }

    //     return response()->json([
    //         'status' => false,
    //         'message' => 'Gagal terhubung ke API PCR (Pegawai): ' . $response->body()
    //     ], 500);
    // }

    function store(Request $req): JsonResponse
    {
        try {
            validate_and_response([
                'email'    => ['Email', 'required|string|email|max:255|unique:users'],
                'role'     => ['Role', 'nullable', Rule::in(Role::pluck('name')->toArray())],
                'nomor_induk' => ['Nomor Induk', 'nullable|string|max:100']
            ]);

            DB::beginTransaction();
            try {
                // Create user with array data
                $userData = [
                    'nomor_induk' => $req->nomor_induk,
                    'name'     => $req->email,
                    'email'    => $req->email,
                    'password' => Hash::make(uniqid()), // Random password
                ];

                $inserted = User::create($userData);

                // Assign Spatie role
                $inserted->assignRole($req->role);

                // Create Member in OPAC if nomor_induk is present
                // if (!empty($req->nomor_induk)) {
                //     try {
                //         $this->insertToOpac($userData);
                //     } catch (\Throwable $e) {
                //         Log::error('Gagal memasukkan member ke OPAC: ' . $e->getMessage(), ['user_id' => $inserted->id]);
                //     }
                // }

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

    // function insertToOpac($userData)
    // {
    //     $existingMember = Member::where('member_id', $userData['nomor_induk'])->first();
    //     if (!$existingMember) {
    //         Member::create([
    //             'member_id' => $userData['nomor_induk'],
    //             'member_email' => $userData['email'],
    //             'member_name' => $userData['email'], // Default name same as email initially
    //             'gender' => 0,
    //             'birth_date' => null,
    //             'member_type_id' => 1,
    //             'member_mail_address' => null,
    //             'mpasswd' => $userData['password'],
    //             'postal_code' => null,
    //             'inst_name' => null,
    //             'is_new' => 1,
    //             'member_image' => null,
    //             'pin' => null,
    //             'member_phone' => null,
    //             'member_fax' => null,
    //             'member_since_date' => now()->toDateString(),
    //             'register_date' => now()->toDateString(),
    //             'expire_date' => now()->addYear()->toDateString(),
    //             'member_notes' => null,
    //             'is_pending' => 0,
    //             'input_date' => now()->toDateString(),
    //             'last_update' => now()->toDateString()
    //         ]);
    //     }
    // }



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
            'user_name' => $user->member_name,
            'member_id' => $user->member_id
        ]);

        // Pastikan user punya member_id
        if (empty($user->member_id)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Nomor induk user belum diatur. Hubungi administrator.'
            ], 400);
        }

        // Generate unique token
        $token = 'TRX'.random_int(100000, 999999);

        // Simpan data sesi di cache selama 10 menit
        $cacheKey = 'user_token_' . $token;

        while (Cache::has($cacheKey)) {
            $token = random_int(100000, 999999);
            $cacheKey = 'user_token_' . $token;
        }

        Cache::put($cacheKey, [
            'user_id'   => $user->id,
            'member_name'      => $user->member_name,
            'member_id' => $user->member_id
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
            'token' => $token,
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

    // App\Http\Controllers\Admin\UserController.php

    public function mahasiswaScanQr($sessionId)
    {
        if (!Cache::has('kios_'.$sessionId)) {
            return response()->json(['status' => false, 'message' => 'Kedaluwarsa'], 404);
        }

        $user = Auth::user();
        $member = Biblio::getMemberId($user->member_id);
        

        Cache::put('kios_'.$sessionId, [
            'status' => 'scanned',
            'member_name' => $user->member_name,
            'member_id' => $user->member_id
        ], now()->addMinutes(10));

        // Berikan respons OK ke HP Mahasiswa
        return response()->json(['status' => true, 'message' => 'Berhasil']);
    }

    public function LoanHistory()
    {
        $user = Auth::user();

        $data = User::getLatestItemLoanedByUser($user->member_id);

        $activeLoans = Loan::activeLoanCount($user->member_id);
        $overdueLoans = Loan::where('member_id', $user->member_id)
            ->overdue()
            ->count();

        return response()->json([
            'history' => $data ?? [],
            'summary' => [
                'active_loans' => $activeLoans,
                'overdue_loans' => $overdueLoans
            ]
        ]);
    }
}
/* This controller generate by @wahyudibinsaid laravel best practices snippets */