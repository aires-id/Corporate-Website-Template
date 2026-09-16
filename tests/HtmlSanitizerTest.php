<?php
// SPDX-License-Identifier: NCSA

namespace Tests;

use App\Services\HtmlSanitizer;
use PHPUnit\Framework\TestCase;

class HtmlSanitizerTest extends TestCase
{
    public function test_it_keeps_only_safe_article_html(): void
    {
        $html = '<p>Aman</p><script>alert(1)</script><a href="javascript:alert(1)">Buruk</a><img src="https://example.test/image.png" onerror="alert(1)">';
        $result = (new HtmlSanitizer())->sanitize($html);

        $this->assertSame('<p>Aman</p>Buruk<img src="https://example.test/image.png" alt="" loading="lazy">', $result);
    }

    public function test_it_preserves_safe_image_alt_text_and_rejects_http_urls(): void
    {
        $result = (new HtmlSanitizer())->sanitize('<img src="https://example.test/image.png" alt="Tim &amp; kolaborasi"><img src="http://example.test/insecure.png" alt="Tidak dipakai">');

        $this->assertSame('<img src="https://example.test/image.png" alt="Tim &amp; kolaborasi" loading="lazy">', $result);
    }
}
