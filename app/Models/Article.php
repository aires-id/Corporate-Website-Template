<?php
// SPDX-License-Identifier: NCSA

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Article extends Model
{
    protected $fillable = [
        'author_id', 'title', 'slug', 'excerpt', 'thumbnail_url', 'body_html',
        'meta_description', 'status', 'published_at', 'views_count',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'views_count' => 'integer',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', Carbon::now());
    }
}
