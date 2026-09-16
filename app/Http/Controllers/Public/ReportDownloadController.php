<?php
// SPDX-License-Identifier: NCSA

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\FinancialReport;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ReportDownloadController extends Controller
{
    public function view(int $id): BinaryFileResponse
    {
        return $this->respond($id, ResponseHeaderBag::DISPOSITION_INLINE);
    }

    public function download(int $id): BinaryFileResponse
    {
        return $this->respond($id, ResponseHeaderBag::DISPOSITION_ATTACHMENT);
    }

    private function respond(int $id, string $disposition): BinaryFileResponse
    {
        $report = FinancialReport::query()->findOrFail($id);
        $path = $report->absolutePath();
        if (!is_file($path)) {
            throw new NotFoundHttpException();
        }

        $response = new BinaryFileResponse($path, 200, [
            'Content-Type' => 'application/pdf',
            'X-Content-Type-Options' => 'nosniff',
        ]);
        $response->setContentDisposition($disposition, $report->display_name);

        return $response;
    }
}
