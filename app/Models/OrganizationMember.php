<?php
// SPDX-License-Identifier: NCSA

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrganizationMember extends Model
{
    protected $fillable = ['name', 'position', 'photo_url', 'short_description', 'sort_order', 'is_active'];

    protected $casts = [
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];
}
