<?php

namespace App\Services\Pdf\Generators;

use App\Repositories\Exam\ExamRepository;

class ExamPdfGenerator extends BasePdfGenerator
{
    public function __construct(ExamRepository $repository)
    {
        parent::__construct($repository);
    }

    public function getView(): string
    {
        return 'pdf.report';
    }

    protected function getActivityType(): string
    {
        return 'exam';
    }
}
