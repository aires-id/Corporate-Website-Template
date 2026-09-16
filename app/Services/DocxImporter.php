<?php
// SPDX-License-Identifier: NCSA

namespace App\Services;

use DOMDocument;
use DOMXPath;
use Illuminate\Http\UploadedFile;
use RuntimeException;

class DocxImporter
{
    public function __construct(private HtmlSanitizer $sanitizer)
    {
    }

    public function import(UploadedFile $file): string
    {
        $maxSize = (int) env('MAX_DOCX_SIZE', 5242880);
        if (!$file->isValid() || (int) $file->getSize() > $maxSize) {
            throw new RuntimeException('File DOCX tidak valid atau melebihi batas ukuran.');
        }

        $extension = strtolower((string) $file->getClientOriginalExtension());
        if ($extension !== 'docx') {
            throw new RuntimeException('Hanya file .docx yang dapat diimpor.');
        }

        $mime = (new \finfo(FILEINFO_MIME_TYPE))->file((string) $file->getRealPath());
        if (!in_array($mime, [
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/zip',
        ], true)) {
            throw new RuntimeException('MIME type DOCX tidak valid.');
        }

        $zip = new \ZipArchive();
        if ($zip->open((string) $file->getRealPath()) !== true) {
            throw new RuntimeException('File DOCX tidak dapat dibaca.');
        }

        $entry = $zip->statName('word/document.xml');
        $maxXmlSize = max(1024, (int) env('MAX_DOCX_XML_SIZE', 10485760));
        $maxCompressionRatio = max(1, (int) env('MAX_DOCX_COMPRESSION_RATIO', 100));
        if (!is_array($entry)) {
            $zip->close();
            throw new RuntimeException('Konten dokumen tidak ditemukan.');
        }

        $uncompressedSize = (int) ($entry['size'] ?? 0);
        $compressedSize = max(1, (int) ($entry['comp_size'] ?? 0));
        if ($uncompressedSize < 1 || $uncompressedSize > $maxXmlSize || ($uncompressedSize / $compressedSize) > $maxCompressionRatio) {
            $zip->close();
            throw new RuntimeException('Ukuran konten DOCX tidak aman untuk diproses.');
        }

        $xml = $zip->getFromName('word/document.xml');
        $zip->close();

        if (!is_string($xml) || $xml === '') {
            throw new RuntimeException('Konten dokumen tidak ditemukan.');
        }

        $document = new DOMDocument();
        $previous = libxml_use_internal_errors(true);
        $loaded = $document->loadXML($xml, LIBXML_NONET | LIBXML_NOERROR | LIBXML_NOWARNING);
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        if (!$loaded) {
            throw new RuntimeException('Struktur DOCX tidak valid.');
        }

        $xpath = new DOMXPath($document);
        $xpath->registerNamespace('w', 'http://schemas.openxmlformats.org/wordprocessingml/2006/main');
        $paragraphs = [];

        foreach ($xpath->query('//w:body/w:p') ?: [] as $paragraph) {
            $parts = [];
            foreach ($xpath->query('.//w:t', $paragraph) ?: [] as $text) {
                $parts[] = $text->textContent;
            }

            $line = trim(implode('', $parts));
            if ($line !== '') {
                $paragraphs[] = '<p>' . htmlspecialchars($line, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '</p>';
            }
        }

        return $this->sanitizer->sanitize(implode("\n", $paragraphs));
    }
}
