<?php
// SPDX-License-Identifier: NCSA

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = ['name', 'description', 'image_url', 'status', 'year', 'related_url'];

    protected $casts = ['year' => 'integer'];
}
