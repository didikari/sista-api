<?php

namespace App\Services\Pdf;

use LaravelEasyRepository\BaseService;

interface PdfService extends BaseService
{
    public function generate(string $type, string $id): string;
    public function stream(string $type, string $id);
}
