<?php

namespace App\Services\Pdf;

interface PdfGeneratorInterface
{
    public function getView(): string;
    public function getData(string $id): array;
    public function getFilename(string $id): string;
}
