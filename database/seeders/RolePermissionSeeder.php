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
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
        $this->command->info('Permissions created successfully.');

        // 2. Buat Roles dan berikan permissions
        // Role 'Administrator' -> bisa melakukan segalanya
        $administratorRole = Role::firstOrCreate(['name' => 'administrator']);
        $administratorRole->givePermissionTo(Permission::all());

        // Role 'Admin' -> bisa mengelola user dan biblio
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->givePermissionTo([
            'dashboard-view',
            'user-list',
            'user-create',
            'user-edit',
            'user-delete',
            'biblio-list',
            'biblio-create',
            'biblio-edit',
            'biblio-delete',
            'biblio-export',
        ]);

        // Role 'User' -> hanya bisa melihat biblio dan export
        $userRole = Role::firstOrCreate(['name' => 'user']);
        $userRole->givePermissionTo([
            'dashboard-view',
            'biblio-list',
            'biblio-export',
        ]);
        $this->command->info('Roles created and permissions assigned successfully.');

        // 3. Buat atau update user utama dan berikan role 'Administrator'
        $adminUser = User::firstOrCreate(
            ['email' => 'varrent22si@mahasiswa.pcr.ac.id'], // Cari berdasarkan email
            [
                'name' => 'Varren (Admin)',
                'password' => Hash::make('password123'), // Atur password default yang kuat
            ]
        );
        $adminUser->assignRole('Administrator');
        $this->command->info('Administrator role assigned to ' . $adminUser->email);
    }
}
