<?php

namespace Database\Seeders;

use App\Models\Lecturer;
use App\Models\PersonalInformation;
use App\Models\Student;
use App\Models\StudyProgram;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $studyPrograms = StudyProgram::all();

        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
        ]);
        $admin->assignRole('admin');

        $staff = User::factory()->create([
            'name' => 'Staff User',
            'email' => 'staff@example.com',
        ]);
        $staff->assignRole('staff');

        foreach ($studyPrograms as $index => $studyProgram) {
            $user = User::factory()->create([
                'name' => 'Mahasiswa ' . $studyProgram->department_name,
                'email' => 'mahasiswa' . ($index + 1) . '@example.com',
            ]);
            $user->assignRole('mahasiswa');

            Student::create([
                'user_id' => $user->id,
                'study_program_id' => $studyProgram->id,
                'nim' => '12345678' . ($index + 1),
            ]);

            PersonalInformation::create([
                'user_id' => $user->id,
                'address' => 'Alamat Mahasiswa ' . ($index + 1),
                'phone' => '08123456789',
            ]);
        }

        foreach ($studyPrograms as $index => $studyProgram) {
            $user = User::factory()->create([
                'name' => 'Dosen ' . $studyProgram->department_name,
                'email' => 'dosen' . ($index + 1) . '@example.com',
            ]);
            $user->assignRole('dosen');

            Lecturer::create([
                'user_id' => $user->id,
                'nidn' => '98765432' . ($index + 1),
                'study_program_id' => $studyProgram->id,
            ]);

            PersonalInformation::create([
                'user_id' => $user->id,
                'address' => 'Alamat Dosen ' . ($index + 1),
                'phone' => '08123451234',
            ]);
        }

        foreach ($studyPrograms as $index => $studyProgram) {
            $user = User::factory()->create([
                'name' => 'Kaprodi ' . $studyProgram->department_name,
                'email' => 'kaprodi' .  ($index + 1) . '@example.com',
            ]);
            $user->assignRole('kaprodi');

            Lecturer::create([
                'user_id' => $user->id,
                'nidn' => '998877' . ($index + 1),
                'study_program_id' => $studyProgram->id,
            ]);

            PersonalInformation::create([
                'user_id' => $user->id,
                'address' => 'Alamat Kaprodi ' . ($index + 1),
                'phone' => '08123451234',
            ]);
        }
    }
}
