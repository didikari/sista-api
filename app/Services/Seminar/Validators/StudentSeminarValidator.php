<?php

namespace App\Services\Seminar\Validators;

use Illuminate\Support\Facades\Validator;

class StudentSeminarValidator implements SeminarValidatorInterface
{
    public function validate(array $data): array
    {
        return Validator::make($data, [
            'supervisor_id' => 'required|exists:lecturers,id',
            'examiner_id' => 'required|exists:lecturers,id',
            'seminar_file' => 'required|file|mimes:pdf,docx|max:2048',
        ])->validated();
    }
}
