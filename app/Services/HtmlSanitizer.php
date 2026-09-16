<?php
// SPDX-License-Identifier: NCSA

namespace App\Services;

use DOMDocument;
use DOMElement;
use DOMNode;

class HtmlSanitizer
{
    private const ALLOWED_TAGS = ['p', 'br', 'strong', 'em', 'ul', 'ol', 'li', 'h2', 'h3', 'blockquote', 'a', 'img'];

    private const REMOVE_WITH_CONTENT = ['script', 'style', 'iframe', 'object', 'embed', 'form', 'input', 'button', 'svg', 'math'];

    public function sanitize(?string $html): string
    {
        $html = trim((string) $html);
        if ($html === '') {
            return '';
        }

        $document = new DOMDocument('1.0', 'UTF-8');
        $previous = libxml_use_internal_errors(true);
        $document->loadHTML(
            '<!doctype html><html><body><div id="article-root">' . $html . '</div></body></html>',
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NONET
        );
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        $root = $document->getElementById('article-root');
        if (!$root instanceof DOMElement) {
            return '';
        }

        $this->cleanChildren($root);

        $output = '';
        foreach (iterator_to_array($root->childNodes) as $child) {
            $output .= $document->saveHTML($child);
        }

        return trim($output);
    }

    private function cleanChildren(DOMNode $parent): void
    {
        foreach (iterator_to_array($parent->childNodes) as $node) {
            if ($node->nodeType === XML_COMMENT_NODE) {
                $parent->removeChild($node);
                continue;
            }

            if (!$node instanceof DOMElement) {
                continue;
            }

            $tag = strtolower($node->tagName);
            if (in_array($tag, self::REMOVE_WITH_CONTENT, true)) {
                $parent->removeChild($node);
                continue;
            }

            $this->cleanChildren($node);

            if (!in_array($tag, self::ALLOWED_TAGS, true)) {
                $this->unwrap($node);
                continue;
            }

            $this->cleanAttributes($node, $tag);
        }
    }

    private function unwrap(DOMElement $node): void
    {
        $parent = $node->parentNode;
        if (!$parent instanceof DOMNode) {
            return;
        }

        while ($node->firstChild !== null) {
            $parent->insertBefore($node->firstChild, $node);
        }

        $parent->removeChild($node);
    }

    private function cleanAttributes(DOMElement $node, string $tag): void
    {
        $href = trim((string) $node->getAttribute('href'));
        $src = trim((string) $node->getAttribute('src'));
        $alt = trim((string) preg_replace('/\s+/', ' ', strip_tags((string) $node->getAttribute('alt'))));
        $adSlot = (string) $node->getAttribute('data-ad-slot');

        foreach (iterator_to_array($node->attributes ?? []) as $attribute) {
            $node->removeAttribute($attribute->nodeName);
        }

        if ($tag === 'a') {
            if ($this->isSafeUrl($href)) {
                $node->setAttribute('href', $href);
                $node->setAttribute('target', '_blank');
                $node->setAttribute('rel', 'noopener noreferrer nofollow');
            } else {
                $this->unwrap($node);
            }
        }

        if ($tag === 'img') {
            if (!$this->isSafeUrl($src)) {
                $node->parentNode?->removeChild($node);
                return;
            }

            $node->setAttribute('src', $src);
            $node->setAttribute('alt', mb_substr($alt, 0, 250));
            $node->setAttribute('loading', 'lazy');
        }

        if ($tag === 'p' && $adSlot === 'article-inline') {
            $node->setAttribute('data-ad-slot', 'article-inline');
        }
    }

    private function isSafeUrl(string $url): bool
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        $scheme = strtolower((string) parse_url($url, PHP_URL_SCHEME));

        return $scheme === 'https';
    }
}
