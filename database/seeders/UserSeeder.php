<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = ['admin', 'mahasiswa', 'dosen', 'kaprodi', 'staff'];

        // Generate satu user untuk setiap role
        foreach ($roles as $role) {
            $user = User::factory()->create([
                'name' => ucfirst($role) . ' User',
                'email' => $role . '@example.com',
            ]);

            $user->assignRole($role);
        }
    }
}
