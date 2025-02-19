<?php

namespace App\Services\PreSeminar\Validators;

use Illuminate\Support\Facades\Validator;

class StudentPreSeminarValidator implements PreSeminarValidatorInterface
{
    public function validate(array $data): array
    {
        return Validator::make($data, [
            'supervisor_id' => 'required|exists:lecturers,id',
            'examiner_id' => 'required|exists:lecturers,id',
            'pre_seminar_file' => 'required|file|mimes:pdf,docx|max:2048',
        ])->validated();
    }
}
