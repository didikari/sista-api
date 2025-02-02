<?php

namespace Database\Seeders;

use App\Enums\TitleStatus;
use App\Models\Title;
use App\Models\User;
use Illuminate\Database\Seeder;

class TitleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = User::role('mahasiswa')->with('student.studyProgram')->get();

        $lecturers = User::role('dosen')->with('lecturer.studyProgram')->get();

        $lecturersByProgram = $lecturers->groupBy(fn($lecturer) => $lecturer->lecturer->studyProgram->id ?? null);

        foreach ($students as $student) {
            $studentModel = $student->student;
            $studyProgramId = $studentModel?->studyProgram?->id;

            if ($studentModel && $studyProgramId && $lecturersByProgram->has($studyProgramId)) {
                $availableLecturers = $lecturersByProgram[$studyProgramId];
                $assignedLecturer = $availableLecturers->random();

                Title::create([
                    'student_id' => $studentModel->id,
                    'title' => 'Judul Proposal ' . $student->name,
                    'abstract' => 'Ini adalah abstrak untuk mahasiswa ' . $student->name,
                    'proposal_file' => $student->name . '_file_proposal.pdf',
                    'status' => TitleStatus::Draft,
                    'supervisor_id' => $assignedLecturer->lecturer->id,
                ]);
            }
        }
    }
}
