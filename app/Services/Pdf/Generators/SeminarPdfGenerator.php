<?php

namespace App\Services\Pdf\Generators;

use App\Repositories\Seminar\SeminarRepository;

class SeminarPdfGenerator extends BasePdfGenerator
{
    public function __construct(SeminarRepository $repository)
    {
        parent::__construct($repository);
    }

    public function getView(): string
    {
        return 'pdf.report';
    }

    protected function getActivityType(): string
    {
        return 'seminar';
    }
}
