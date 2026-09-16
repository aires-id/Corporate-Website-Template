<?php
// SPDX-License-Identifier: NCSA

namespace App\Services;

use App\Models\Setting;
use Throwable;

class SettingsService
{
    private const CACHE_KEY = 'site-settings:v1';

    private const DEFAULTS = [
        'site_name' => '[ISI NAMA]',
        'site_description' => '[ISI DESKRIPSI]',
        'address' => '[ISI ALAMAT]',
        'email' => '[ISI EMAIL]',
        'phone' => '[ISI NOMOR KONTAK]',
        'business_hours' => 'Senin–Jumat, 09.00–17.00 WIB',
        'primary_color' => '#2F6D78',
        'secondary_color' => '#DCEFEB',
        'text_color' => '#18323F',
        'footer_color' => '#112A38',
        'logo_svg' => '',
        'logo_png' => '',
    ];

    public function all(): array
    {
        try {
            return app('cache')->remember(self::CACHE_KEY, 300, function (): array {
                $stored = Setting::query()->pluck('value', 'key')->all();

                return array_replace(self::DEFAULTS, $stored);
            });
        } catch (Throwable) {
            return self::DEFAULTS;
        }
    }

    public function forget(): void
    {
        try {
            app('cache')->forget(self::CACHE_KEY);
        } catch (Throwable) {
            // The application should remain readable if the cache is unavailable.
        }
    }
}
