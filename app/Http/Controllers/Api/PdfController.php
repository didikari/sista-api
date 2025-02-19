<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Pdf\PdfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class PdfController extends Controller
{
    protected PdfService $pdfService;

    public function __construct(PdfService $pdfService)
    {
        $this->pdfService = $pdfService;
    }

    /**
     * Generate and download the PDF file.
     */
    public function generate(Request $request, string $type, string $id)
    {
        $path = $this->pdfService->generate($type, $id);

        if (!$path || !file_exists($path)) {
            return response()->json(['message' => 'Laporan tidak ditemukan'], 404);
        }

        return Response::download($path, basename($path));
    }

    /**
     * Stream the PDF directly in the browser.
     */
    public function preview(Request $request, string $type, string $id)
    {
        return $this->pdfService->stream($type, $id);
    }
}
