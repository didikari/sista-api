<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StudyProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('study_programs')->insert([
            ['id' => Str::uuid(), 'department_name' => 'Agribisnis', 'created_at' => now(), 'updated_at' => now()],
            ['id' => Str::uuid(), 'department_name' => 'Budi Daya Tanaman Hortikultura', 'created_at' => now(), 'updated_at' => now()],
            ['id' => Str::uuid(), 'department_name' => 'Budi Daya Ternak', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
