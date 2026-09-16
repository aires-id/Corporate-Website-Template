<?php
// SPDX-License-Identifier: NCSA

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Carbon\CarbonInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Throwable;

class SitemapController extends Controller
{
    private const ARTICLES_PER_SITEMAP = 50000;

    public function index()
    {
        $base = rtrim((string) config('app.url'), '/');
        $articleCount = $this->publishedArticleCount();
        $articleSitemaps = max(1, (int) ceil($articleCount / self::ARTICLES_PER_SITEMAP));
        $articleModified = $this->latestArticleUpdate() ?: Carbon::now();
        $xml = $this->xmlDeclaration() . "\n<sitemapindex xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
        $xml .= $this->sitemapTag($base . '/sitemap-pages.xml', Carbon::now());
        for ($page = 1; $page <= $articleSitemaps; $page++) {
            $suffix = $page === 1 ? '' : '?page=' . $page;
            $xml .= $this->sitemapTag($base . '/sitemap-articles.xml' . $suffix, $articleModified);
        }
        $xml .= "</sitemapindex>\n";

        return $this->xml($xml);
    }

    public function pages()
    {
        $urls = [
            ['/', 'weekly', '1.0'],
            ['/tentang', 'monthly', '0.8'],
            ['/tentang/organisasi', 'monthly', '0.8'],
            ['/tentang/struktur', 'monthly', '0.7'],
            ['/tentang/laporan-keuangan', 'monthly', '0.8'],
            ['/komunitas', 'weekly', '0.7'],
            ['/artikel', 'daily', '0.9'],
            ['/kerja-sama', 'monthly', '0.7'],
            ['/project', 'weekly', '0.7'],
            ['/hubungan-investor', 'monthly', '0.8'],
            ['/kontak', 'monthly', '0.6'],
            ['/cookie-policy', 'yearly', '0.3'],
            ['/privacy-policy', 'yearly', '0.3'],
        ];
        $xml = $this->xmlDeclaration() . "\n<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";

        foreach ($urls as [$path, $frequency, $priority]) {
            $xml .= $this->urlTag($this->absolute($path), Carbon::now(), $frequency, $priority);
        }

        $xml .= "</urlset>\n";

        return $this->xml($xml);
    }

    public function articles(Request $request)
    {
        $page = max(1, (int) $request->query('page', 1));
        $xml = $this->xmlDeclaration() . "\n<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";

        try {
            $articles = Article::published()->orderBy('id')->forPage($page, self::ARTICLES_PER_SITEMAP)->get();
        } catch (Throwable) {
            $articles = collect();
        }

        foreach ($articles as $article) {
            $xml .= $this->urlTag(
                $this->absolute('/artikel/' . rawurlencode($article->slug)),
                $article->updated_at,
                'monthly',
                '0.7'
            );
        }

        $xml .= "</urlset>\n";

        return $this->xml($xml);
    }

    public function robots()
    {
        $body = implode("\n", [
            'User-agent: *',
            'Allow: /',
            '',
            'Disallow: /admin/',
            'Disallow: /storage/',
            'Disallow: /uploads/private/',
            '',
            'Sitemap: ' . $this->absolute('/sitemap.xml'),
        ]) . "\n";

        return response($body, 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }

    private function xml(string $body)
    {
        return response($body, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }

    private function xmlDeclaration(): string
    {
        return '<?xml version="1.0" encoding="UTF-8"?>';
    }

    private function sitemapTag(string $location, CarbonInterface $lastModified): string
    {
        return "  <sitemap><loc>" . $this->escape($location) . "</loc><lastmod>" . $lastModified->toAtomString() . "</lastmod></sitemap>\n";
    }

    private function urlTag(string $location, CarbonInterface $lastModified, string $frequency, string $priority): string
    {
        return "  <url><loc>" . $this->escape($location) . "</loc><lastmod>" . $lastModified->toAtomString() . "</lastmod><changefreq>" . $frequency . "</changefreq><priority>" . $priority . "</priority></url>\n";
    }

    private function absolute(string $path): string
    {
        return rtrim((string) config('app.url'), '/') . '/' . ltrim($path, '/');
    }

    private function escape(string $value): string
    {
        return htmlspecialchars($value, ENT_XML1 | ENT_COMPAT, 'UTF-8');
    }

    private function publishedArticleCount(): int
    {
        try {
            return Article::published()->count();
        } catch (Throwable) {
            return 0;
        }
    }

    private function latestArticleUpdate(): ?CarbonInterface
    {
        try {
            $latest = Article::published()->max('updated_at');

            return $latest ? Carbon::parse($latest) : null;
        } catch (Throwable) {
            return null;
        }
    }
}
