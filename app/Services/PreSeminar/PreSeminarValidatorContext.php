<?php

namespace App\Services\PreSeminar;

use App\Services\PreSeminar\Validators\PreSeminarValidatorInterface;

class PreSeminarValidatorContext
{
    protected $validator;

    public function __construct(PreSeminarValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function validate(array $data): array
    {
        return $this->validator->validate($data);
    }
}
