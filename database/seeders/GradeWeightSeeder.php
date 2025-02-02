<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GradeWeightSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('grade_weights')->insert([
            [
                'id' => Str::uuid(),
                'role' => 'supervisor',
                'a1_weight' => 0.20,
                'a2_weight' => 0.15,
                'a3_weight' => 0.15,
                'a4_weight' => 0.20,
                'a5_weight' => 0.15,
                'a6_weight' => 0.15,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'role' => 'examiner',
                'a1_weight' => 0.15,
                'a2_weight' => 0.15,
                'a3_weight' => 0.20,
                'a4_weight' => 0.20,
                'a5_weight' => 0.15,
                'a6_weight' => 0.15,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
