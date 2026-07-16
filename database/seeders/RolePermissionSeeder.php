<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Buat semua permission yang dibutuhkan aplikasi
        $permissions = [
            'dashboard-view',
            // User Management
            'user-list',
            'user-create',
            'user-edit',
            'user-delete',
            // Role Management
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',
            // Biblio Management
            'biblio-list',
            'biblio-create',
            'biblio-edit',
            'biblio-delete',
            'biblio-export',
            // Loans
            'loan-list',
            'loan-create',
            'loan-edit',
            'loan-delete',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
        $this->command->info('Permissions created successfully.');

        // 2. Buat Roles dan berikan permissions
        // Role 'super-admin' -> bisa melakukan segalanya
        $administratorRole = Role::firstOrCreate(['name' => 'super-admin']);
        $administratorRole->givePermissionTo(Permission::all());

        // Role 'pustakawan' -> Dashboard, Biblio, Member, Role
        $pustakawanRole = Role::firstOrCreate(['name' => 'pustakawan']);
        $pustakawanRole->givePermissionTo([
            'dashboard-view',
            'biblio-list', 'biblio-create', 'biblio-edit', 'biblio-delete', 'biblio-export',
            'user-list', 'user-create', 'user-edit', 'user-delete',
            'role-list', 'role-create', 'role-edit', 'role-delete',
        ]);

        // Role 'member' -> /generate-token, /user/loan-history, Loans
        $memberRole = Role::firstOrCreate(['name' => 'member']);
        // Member permissions might be handled by logic or basic auth, but giving them specific permissions helps future proofing.
        // If 'Loans' page for member requires a permission, add it. Assuming no specific permission needed for now based on request.
        
        $this->command->info('Roles created and permissions assigned successfully.');

        // 3. Buat atau update user utama dan berikan role 'Super Admin'
        $adminUser = User::firstOrCreate(
            ['email' => 'varrent22si@mahasiswa.pcr.ac.id'], // Cari berdasarkan email
            [
                'name' => 'Varren (Admin)',
                'password' => Hash::make('password123'), // Atur password default yang kuat
            ]
        );
        $adminUser->assignRole('super-admin');
        $this->command->info('Super Admin role assigned to ' . $adminUser->email);
    }
}
