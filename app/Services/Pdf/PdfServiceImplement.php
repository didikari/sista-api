<?php

namespace App\Services\Pdf;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use LaravelEasyRepository\ServiceApi;

class PdfServiceImplement extends ServiceApi implements PdfService
{
    public function generate(string $type, string $id): string
    {
        $generator = PdfFactory::create($type);
        $data = $generator->getData($id);
        $view = $generator->getView();
        $filename = "pdfs/" . $generator->getFilename($id);

        $pdf = Pdf::loadView($view, $data)->output();

        Storage::disk('public')->put($filename, $pdf);

        return Storage::disk('public')->path($filename);
    }

    public function stream(string $type, string $id)
    {
        $generator = PdfFactory::create($type);
        $data = $generator->getData($id);
        $view = $generator->getView();

        return Pdf::loadView($view, $data)->stream();
    }
}
