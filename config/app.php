<?php

return [
    'name' => env('APP_NAME', '[ISI NAMA]'),
    'env' => env('APP_ENV', 'production'),
    'debug' => (bool) env('APP_DEBUG', false),
    'url' => rtrim(env('APP_URL', 'http://localhost'), '/'),
    'key' => env('APP_KEY', ''),
    'cipher' => 'AES-256-CBC',
    'timezone' => env('APP_TIMEZONE', 'Asia/Jakarta'),
    'locale' => 'id',
    'fallback_locale' => 'id',
];
