<?php
// SPDX-License-Identifier: NCSA

namespace App\Services;

class SeoService
{
    public function page(array $site, string $title, string $description, string $path, string $robots = 'index, follow', ?string $image = null, ?array $jsonLd = null): array
    {
        $siteName = $site['site_name'] ?? '[ISI NAMA]';
        $canonical = $this->absolute($path);

        return [
            'title' => $title === $siteName ? $siteName : $title . ' | ' . $siteName,
            'description' => trim($description),
            'canonical' => $canonical,
            'robots' => $robots,
            'og_type' => 'website',
            'og_image' => $image ?: $this->absolute('/assets/images/hero-team-collaboration.png'),
            'json_ld' => $jsonLd,
        ];
    }

    public function article(array $site, object $article, bool $preview = false): array
    {
        $seo = $this->page(
            $site,
            (string) $article->title,
            (string) ($article->meta_description ?: $article->excerpt),
            '/artikel/' . rawurlencode((string) $article->slug),
            $preview ? 'noindex, nofollow' : 'index, follow',
            $article->thumbnail_url ?: null
        );
        $seo['og_type'] = 'article';

        if (!$preview) {
            $seo['json_ld'] = [
                '@context' => 'https://schema.org',
                '@type' => 'Article',
                'headline' => (string) $article->title,
                'description' => (string) ($article->meta_description ?: $article->excerpt),
                'datePublished' => $article->published_at?->toAtomString(),
                'dateModified' => $article->updated_at?->toAtomString(),
                'mainEntityOfPage' => $seo['canonical'],
                'author' => [
                    '@type' => 'Person',
                    'name' => (string) ($article->author?->name ?? ''),
                ],
            ];
        }

        return $seo;
    }

    public function organization(array $site): ?array
    {
        $name = trim((string) ($site['site_name'] ?? ''));
        if ($name === '' || str_starts_with($name, '[')) {
            return null;
        }

        return array_filter([
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => $name,
            'url' => $this->absolute('/'),
            'email' => $site['email'] ?? null,
            'telephone' => $site['phone'] ?? null,
            'address' => $site['address'] ?? null,
        ], static fn ($value) => $value !== null && $value !== '');
    }

    public function absolute(string $path): string
    {
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }

        return rtrim((string) config('app.url'), '/') . '/' . ltrim($path, '/');
    }
}
