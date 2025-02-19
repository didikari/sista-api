<?php

namespace App\Services\Exam\Validators;

interface ExamValidatorInterface
{
    public function validate(array $data): array;
}
