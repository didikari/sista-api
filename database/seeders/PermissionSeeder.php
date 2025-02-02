<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name' => 'manage_users']);
        Permission::create(['name' => 'view_reports']);
        Permission::create(['name' => 'approve_titles']);
        Permission::create(['name' => 'view_student']);
        Permission::create(['name' => 'view_staff']);

        Role::findByName('admin')->givePermissionTo(['manage_users', 'view_reports', 'approve_titles', 'view_student', 'view_staff']);
        Role::findByName('kaprodi')->givePermissionTo(['view_reports', 'approve_titles']);
        Role::findByName('dosen')->givePermissionTo(['approve_titles']);
        Role::findByName('mahasiswa')->givePermissionTo(['view_student']);
        Role::findByName('staff')->givePermissionTo(['view_staff']);
    }
}
