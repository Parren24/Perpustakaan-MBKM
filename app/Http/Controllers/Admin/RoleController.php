<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    function __construct()
    {
        $this->activeRoot   = 'user-management';
        $this->breadCrump[] = ['title' => 'User Management', 'link' => '#'];

        // Melindungi controller dengan middleware permission
        $this->middleware('permission:role-list', ['only' => ['index', 'data']]);
        $this->middleware('permission:role-create', ['only' => ['store']]);
        $this->middleware('permission:role-edit', ['only' => ['show', 'update']]);
        $this->middleware('permission:role-delete', ['only' => ['destroy']]);
    }

    /**
     * Menampilkan halaman daftar role.
     */
    public function index()
    {
        $this->title        = 'Kelola Role';
        $this->activeMenu   = 'roles';
        $this->breadCrump[] = ['title' => 'Daftar Role', 'link' => route('app.roles.index')];

        $builder = app('datatables.html');
        $dataTable = $builder
            ->ajax(route('app.roles.data', ['param1' => 'list']))
            ->serverSide(true)
            ->processing(true)
            ->pageLength(10)
            ->columns([
                Column::make(['width' => '80px', 'title' => 'Aksi', 'data' => 'action', 'orderable' => false, 'searchable' => false, 'className' => 'text-center']),
                Column::make(['width' => '50px', 'title' => 'No', 'data' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false, 'className' => 'text-center']),
                Column::make(['title' => 'Nama Role', 'data' => 'name']),
                Column::make(['title' => 'Jumlah Hak Akses', 'data' => 'permissions_count', 'className' => 'text-center']),
                Column::make(['title' => 'Dibuat Pada', 'data' => 'created_at']),
            ]);

        // Get all permissions for modal form
        $permissions = Permission::all()->map(function ($permission) {
            return [
                'id' => $permission->id,
                'name' => $permission->name,
                'selected' => false
            ];
        });

        $this->dataView([
            'dataTable' => $dataTable,
            'permissions' => $permissions
        ]);
        return $this->view('admin.roles.list');
    }

    /**
     * Menampilkan form untuk menambah/mengedit role.
     */
    public function show($param1 = '', $param2 = '')
    {
        if ($param1 == 'form') {
            $this->title        = 'Form Role & Hak Akses';
            $this->activeMenu   = 'roles';
            $this->breadCrump[] = ['title' => 'Form', 'link' => '#'];

            $dataRole = null;
            if ($param2) {
                $dataRole = Role::with('permissions')->findOrFail(decid($param2));
            }

            // Ambil semua permission untuk ditampilkan di form
            $permissions = Permission::all()->groupBy(function ($item) {
                // Mengelompokkan permission berdasarkan nama fitur (e.g., 'user', 'biblio')
                return explode('-', $item->name)[0];
            });

            $this->dataView([
                'dataRole' => $dataRole,
                'permissions' => $permissions
            ]);
            return $this->view('admin.roles.form');
        }
        abort(404);
    }

    /**
     * Menyimpan role baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', Rule::unique((new Role)->getTable())], // <-- PERBAIKAN
            'permissions' => 'array'
        ]);

        DB::beginTransaction();
        try {
            $role = Role::create(['name' => $request->name]);
            $role->syncPermissions($request->permissions ?? []); // Memberikan hak akses
            DB::commit();

            if ($request->ajax()) {
                return response()->json(['status' => true, 'message' => 'Role berhasil dibuat.']);
            }
            
            return redirect()->route('app.roles.index')->with('success', 'Role berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->ajax()) {
                return response()->json(['status' => false, 'message' => 'Gagal membuat role: ' . $e->getMessage()]);
            }
            return redirect()->back()->with('error', 'Gagal membuat role: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Memperbarui role yang sudah ada.
     */
    public function update(Request $request)
    {
        $id = decid($request->id);
        $request->validate([
            'id'   => 'required',
            'name' => ['required', 'string', Rule::unique((new Role)->getTable())->ignore($id)],
            'permissions' => 'array'
        ]);

        DB::beginTransaction();
        try {
            $role = Role::findOrFail($id);
            $role->name = $request->name;
            $role->save();

            $role->syncPermissions($request->permissions ?? []); // Menyesuaikan hak akses
            DB::commit();

            if ($request->ajax()) {
                return response()->json(['status' => true, 'message' => 'Role berhasil diperbarui.']);
            }
            
            return redirect()->route('app.roles.index')->with('success', 'Role berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->ajax()) {
                return response()->json(['status' => false, 'message' => 'Gagal memperbarui role: ' . $e->getMessage()]);
            }
            return redirect()->back()->with('error', 'Gagal memperbarui role: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Menghapus role.
     */
    public function destroy(Request $request): JsonResponse
    {
        $request->validate(['id' => 'required']);
        $id = decid($request->input('id'));

        Role::findOrFail($id)->delete();

        return response()->json(['status' => true, 'message' => 'Role berhasil dihapus.']);
    }

    /**
     * Menyediakan data untuk DataTable.
     */
    public function data(Request $req, $param1 = ''): JsonResponse
    {
        if ($param1 == 'list') {
            $query = Role::withCount('permissions');

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $id = encid($row->id);
                    $dataAction = [
                        'id' => $id,
                        'btn' => [
                            ['action' => 'edit', 'link' => route('app.roles.show', ['param1' => 'form', 'param2' => $id])],
                            ['action' => 'delete', 'attr' => ['jf-delete' => $id]],
                        ]
                    ];
                    return view('components.btn.actiontable', $dataAction)->render();
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('d/m/Y H:i');
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        abort(404);
    }
}
