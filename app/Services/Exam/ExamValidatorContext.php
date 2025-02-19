<?php

namespace App\Services\Exam;

use App\Services\Exam\Validators\ExamValidatorInterface;

class ExamValidatorContext
{
    protected $validator;

    public function __construct(ExamValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function validate(array $data): array
    {
        return $this->validator->validate($data);
    }
}
