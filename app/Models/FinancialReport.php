<?php
// SPDX-License-Identifier: NCSA

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinancialReport extends Model
{
    protected $fillable = [
        'title', 'month', 'year', 'stored_path', 'display_name', 'mime', 'size',
        'description', 'uploaded_by', 'uploaded_at',
    ];

    protected $casts = [
        'uploaded_at' => 'datetime',
        'size' => 'integer',
        'month' => 'integer',
        'year' => 'integer',
    ];

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function absolutePath(): string
    {
        return storage_path('app/private/' . ltrim($this->stored_path, '/\\'));
    }
}
