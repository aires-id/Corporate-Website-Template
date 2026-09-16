<?php
// SPDX-License-Identifier: NCSA

namespace App\Services;

use Illuminate\Http\UploadedFile;
use RuntimeException;

class FileUploadService
{
    public function storePdf(UploadedFile $file): array
    {
        $maxSize = (int) env('MAX_PDF_SIZE', 10485760);
        if (!$file->isValid() || (int) $file->getSize() < 1 || (int) $file->getSize() > $maxSize) {
            throw new RuntimeException('File PDF tidak valid atau melebihi batas ukuran.');
        }

        if (strtolower((string) $file->getClientOriginalExtension()) !== 'pdf') {
            throw new RuntimeException('Hanya file PDF yang diizinkan.');
        }

        $size = (int) $file->getSize();
        $temporaryPath = (string) $file->getRealPath();
        $mime = (new \finfo(FILEINFO_MIME_TYPE))->file($temporaryPath);
        $signature = file_get_contents($temporaryPath, false, null, 0, 5);
        if ($mime !== 'application/pdf' || $signature !== '%PDF-') {
            throw new RuntimeException('Konten file bukan PDF yang valid.');
        }

        $directory = storage_path('app/private/reports');
        if (!is_dir($directory) && !mkdir($directory, 0755, true) && !is_dir($directory)) {
            throw new RuntimeException('Folder penyimpanan laporan tidak dapat dibuat.');
        }

        $filename = bin2hex(random_bytes(20)) . '.pdf';
        $file->move($directory, $filename);

        return [
            'stored_path' => 'reports/' . $filename,
            'display_name' => $this->safeDisplayName((string) $file->getClientOriginalName()),
            'mime' => 'application/pdf',
            'size' => $size,
        ];
    }

    private function safeDisplayName(string $name): string
    {
        $name = preg_replace('/[^A-Za-z0-9._ -]/', '', $name) ?: 'laporan.pdf';
        $name = trim($name);

        return str_ends_with(strtolower($name), '.pdf') ? $name : $name . '.pdf';
    }
}
