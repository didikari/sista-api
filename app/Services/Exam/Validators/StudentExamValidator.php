<?php

namespace App\Services\Exam\Validators;

use Illuminate\Support\Facades\Validator;

class StudentExamValidator implements ExamValidatorInterface
{
    public function validate(array $data): array
    {
        return Validator::make($data, [
            'supervisor_id' => 'required|exists:lecturers,id',
            'examiner_id' => 'required|exists:lecturers,id',
            'exam_file' => 'required|file|mimes:pdf,docx|max:2048',
        ])->validated();
    }
}
