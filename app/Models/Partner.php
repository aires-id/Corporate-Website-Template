<?php
// SPDX-License-Identifier: NCSA

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    protected $fillable = ['name', 'logo_url', 'description', 'website_url', 'cooperation_year'];

    protected $casts = ['cooperation_year' => 'integer'];
}
