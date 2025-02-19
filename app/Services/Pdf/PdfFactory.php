<?php

namespace App\Services\Pdf;

use App\Services\Pdf\Generators\SeminarPdfGenerator;
use App\Services\Pdf\Generators\ExamPdfGenerator;
use App\Services\Pdf\Generators\PreSeminarPdfGenerator;
use InvalidArgumentException;

class PdfFactory
{
    public static function create(string $type): PdfGeneratorInterface
    {
        return match ($type) {
            'seminar' => resolve(SeminarPdfGenerator::class),
            'pre-seminar' => resolve(PreSeminarPdfGenerator::class),
            'exam' => resolve(ExamPdfGenerator::class),
            default => throw new InvalidArgumentException("Jenis laporan tidak dikenali: {$type}"),
        };
    }
}
