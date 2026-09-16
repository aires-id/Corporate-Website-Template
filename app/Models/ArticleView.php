<?php
// SPDX-License-Identifier: NCSA

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleView extends Model
{
    protected $fillable = ['article_id', 'viewer_hash', 'viewed_on'];

    protected $casts = ['viewed_on' => 'date'];
}
