{{-- SPDX-License-Identifier: NCSA --}}
<!doctype html>
<html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="robots" content="noindex, nofollow"><title>{{ $status }} — {{ config('app.name') }}</title><link rel="stylesheet" href="{{ $baseUrl }}/assets/css/pico.min.css"><link rel="stylesheet" href="{{ $baseUrl }}/assets/css/site.css"></head>
<body><main class="container" style="padding:12vh 0;max-width:45rem;"><article class="admin-card"><span class="eyebrow">{{ $status }}</span><h1>@yield('title')</h1><p>{{ $message }}</p><a href="{{ $baseUrl }}/" role="button">Kembali ke Beranda</a></article></main></body></html>
