<?php

namespace App\Services\PreSeminar\Validators;

interface PreSeminarValidatorInterface
{
    public function validate(array $data): array;
}
