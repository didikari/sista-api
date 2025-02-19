<?php

namespace App\Services\Pdf\Generators;

use App\Repositories\PreSeminar\PreSeminarRepository;

class PreSeminarPdfGenerator extends BasePdfGenerator
{
    public function __construct(PreSeminarRepository $repository)
    {
        parent::__construct($repository);
    }

    public function getView(): string
    {
        return 'pdf.report';
    }

    protected function getActivityType(): string
    {
        return 'pre-seminar';
    }
}
