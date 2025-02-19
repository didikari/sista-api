<?php

namespace App\Services\Seminar\Validators;

interface SeminarValidatorInterface
{
    public function validate(array $data): array;
}
