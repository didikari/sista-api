<?php

namespace App\Services\Seminar\Validators;

use App\Enums\EventStatus;
use Illuminate\Support\Facades\Validator;

class KaprodiSeminarValidator implements SeminarValidatorInterface
{
    public function validate(array $data): array
    {
        return  Validator::make($data, [
            'supervisor_id' => 'required|exists:lecturers,id',
            'examiner_id' => 'required|exists:lecturers,id',
            'seminar_date' => 'required|date',
            'status' => 'required|in:' . implode(',', array_map(fn($status) => $status->value, EventStatus::cases())),
        ])->validated();
    }
}
