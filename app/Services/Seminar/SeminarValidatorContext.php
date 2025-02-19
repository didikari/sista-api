<?php

namespace App\Services\Seminar;

use App\Services\Seminar\Validators\SeminarValidatorInterface;

class SeminarValidatorContext
{
    protected $validator;

    public function __construct(SeminarValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function validate(array $data): array
    {
        return $this->validator->validate($data);
    }
}
