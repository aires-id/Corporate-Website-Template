<?php
// SPDX-License-Identifier: NCSA

namespace App\Services;

use App\Models\Article;
use App\Models\ArticleView;
use Illuminate\Database\QueryException;
use Illuminate\Support\Carbon;

class ArticleViewService
{
    public function record(Article $article): void
    {
        $sessionId = session_id();
        if ($sessionId === '') {
            return;
        }

        $key = (string) config('app.key');
        if (str_starts_with($key, 'base64:')) {
            $decoded = base64_decode(substr($key, 7), true);
            $key = $decoded !== false ? $decoded : $key;
        }

        $viewerHash = hash_hmac('sha256', $sessionId, $key ?: 'local-fallback-key');

        try {
            $view = ArticleView::query()->firstOrCreate([
                'article_id' => $article->id,
                'viewer_hash' => $viewerHash,
                'viewed_on' => Carbon::now()->toDateString(),
            ]);

            if ($view->wasRecentlyCreated) {
                Article::query()->whereKey($article->id)->increment('views_count');
                $article->views_count++;
            }
        } catch (QueryException) {
            // A concurrent unique-key insert means this session has already been counted today.
        }
    }
}
