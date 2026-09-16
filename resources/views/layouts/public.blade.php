{{-- SPDX-License-Identifier: NCSA --}}
<!doctype html>
<html lang="id" data-theme="light">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $seo['title'] }}</title>
  <meta name="description" content="{{ $seo['description'] }}">
  <meta name="robots" content="{{ $seo['robots'] }}">
  <link rel="canonical" href="{{ $seo['canonical'] }}">
  <meta property="og:type" content="{{ $seo['og_type'] ?? 'website' }}">
  <meta property="og:title" content="{{ $seo['title'] }}">
  <meta property="og:description" content="{{ $seo['description'] }}">
  <meta property="og:url" content="{{ $seo['canonical'] }}">
  @if(!empty($seo['og_image']))
    <meta property="og:image" content="{{ $seo['og_image'] }}">
  @endif
  @if(!empty($seo['json_ld']))
    <script type="application/ld+json">{!! json_encode($seo['json_ld'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}</script>
  @endif
  <link rel="stylesheet" href="{{ $baseUrl }}/assets/css/pico.min.css">
  <link rel="stylesheet" href="{{ $baseUrl }}/assets/css/site.css?v=20260915-6">
  @php
    $primary = preg_match('/^#[0-9A-Fa-f]{6}$/', $site['primary_color'] ?? '') ? $site['primary_color'] : '#2F6D78';
    $secondary = preg_match('/^#[0-9A-Fa-f]{6}$/', $site['secondary_color'] ?? '') ? $site['secondary_color'] : '#DCEFEB';
    $textColor = preg_match('/^#[0-9A-Fa-f]{6}$/', $site['text_color'] ?? '') ? $site['text_color'] : '#18323F';
    $footerColor = preg_match('/^#[0-9A-Fa-f]{6}$/', $site['footer_color'] ?? '') ? $site['footer_color'] : '#112A38';
    $logoValue = trim((string) (($site['logo_svg'] ?? '') ?: ($site['logo_png'] ?? '')));
    $logoUrl = preg_match('#^https://#i', $logoValue) ? $logoValue : ($logoValue !== '' ? $baseUrl . '/' . ltrim($logoValue, '/') : '');
    $email = trim((string) ($site['email'] ?? ''));
    $emailHref = filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : null;
    $phone = trim((string) ($site['phone'] ?? ''));
    $phoneHref = preg_replace('/[^0-9+]/', '', $phone);
    $phoneHref = preg_match('/^\+?[0-9]{7,15}$/', $phoneHref) ? $phoneHref : null;
  @endphp
  <style>:root{--brand:{{ $primary }};--brand-soft:{{ $secondary }};--ink:{{ $textColor }};--footer:{{ $footerColor }};--pico-primary:{{ $primary }};--pico-primary-hover:{{ $textColor }};--pico-primary-focus:rgba(47,109,120,.2);}</style>
  <link rel="stylesheet" href="{{ $baseUrl }}/assets/css/corporate.css?v={{ filemtime(base_path('public/assets/css/corporate.css')) }}">
  <link rel="stylesheet" href="{{ $baseUrl }}/assets/css/footer.css?v={{ filemtime(base_path('public/assets/css/footer.css')) }}">
  @if(isset($policy))
    <link rel="stylesheet" href="{{ $baseUrl }}/assets/css/policy.css?v={{ filemtime(base_path('public/assets/css/policy.css')) }}">
  @endif
</head>
<body id="page-top" data-ads-client="{{ trim((string) env('GOOGLE_ADS_CLIENT_ID', '')) }}">
  <header class="site-header">
    <nav class="container topbar" aria-label="Navigasi utama">
      <a class="brand" href="{{ $baseUrl }}/" aria-label="{{ $site['site_name'] }} beranda">
        @if($logoUrl)
          <img src="{{ $logoUrl }}" alt="{{ $site['site_name'] }}" class="brand-logo">
        @else
          <span class="brand-mark" aria-hidden="true">O</span>
          <span>{{ $site['site_name'] }}</span>
        @endif
      </a>
      <div class="desktop-nav" aria-label="Menu desktop">
        <a href="{{ $baseUrl }}/">Beranda</a>
        <details class="nav-dropdown">
          <summary>Tentang Kami</summary>
          <ul>
            <li><a href="{{ $baseUrl }}/tentang/organisasi">Organisasi</a></li>
            <li><a href="{{ $baseUrl }}/tentang/struktur">Struktur Organisasi</a></li>
            <li><a href="{{ $baseUrl }}/tentang/laporan-keuangan">Laporan Keuangan Bulanan</a></li>
          </ul>
        </details>
        <details class="nav-dropdown">
          <summary>Komunitas</summary>
          <ul>
            <li><a href="{{ $baseUrl }}/artikel">Artikel</a></li>
            <li><a href="{{ $baseUrl }}/kerja-sama">Kerja Sama</a></li>
            <li><a href="{{ $baseUrl }}/project">Project</a></li>
          </ul>
        </details>
        <a href="{{ $baseUrl }}/hubungan-investor">Hubungan Investor</a>
        <a href="{{ $baseUrl }}/kontak">Hubungi Kami</a>
      </div>
      <button class="menu-toggle" type="button" data-menu-toggle aria-controls="mobile-drawer" aria-expanded="false">
        <span class="visually-hidden">Buka menu</span><span aria-hidden="true">☰</span>
      </button>
    </nav>
  </header>

  <div class="drawer-overlay" data-drawer-overlay hidden></div>
  <aside id="mobile-drawer" class="mobile-drawer" role="dialog" aria-label="Navigasi mobile" aria-modal="true" aria-hidden="true" inert>
    <div class="drawer-head">
      <span class="drawer-title">Menu</span>
      <button type="button" class="drawer-close" data-menu-close aria-label="Tutup menu"><span class="drawer-close-icon" aria-hidden="true"></span></button>
    </div>
    <nav class="drawer-nav">
      <a href="{{ $baseUrl }}/">Beranda</a>
      <span class="drawer-group">Tentang Kami</span>
      <a href="{{ $baseUrl }}/tentang/organisasi">Organisasi</a>
      <a href="{{ $baseUrl }}/tentang/struktur">Struktur Organisasi</a>
      <a href="{{ $baseUrl }}/tentang/laporan-keuangan">Laporan Keuangan Bulanan</a>
      <span class="drawer-group">Komunitas</span>
      <a href="{{ $baseUrl }}/artikel">Artikel</a>
      <a href="{{ $baseUrl }}/kerja-sama">Kerja Sama</a>
      <a href="{{ $baseUrl }}/project">Project</a>
      <a href="{{ $baseUrl }}/hubungan-investor">Hubungan Investor</a>
      <a href="{{ $baseUrl }}/kontak">Hubungi Kami</a>
    </nav>
  </aside>

  @if(!empty($_SESSION['flash']))
    <div class="container flash-wrap"><article class="flash flash-{{ $_SESSION['flash']['type'] }}">{{ $_SESSION['flash']['message'] }}</article></div>
  @endif
  @if(!empty($_SESSION['form_errors']))
    <div class="container flash-wrap"><article id="form-errors" class="flash flash-error" role="alert" aria-live="assertive" tabindex="-1"><strong>Mohon periksa:</strong><ul>@foreach($_SESSION['form_errors'] as $error)<li>{{ $error }}</li>@endforeach</ul></article></div>
  @endif

  <main>
    @yield('content')
  </main>

  @include('partials.corporate-footer')

  <section id="cookie-banner" class="cookie-popup" role="dialog" aria-live="polite" aria-label="Pilihan cookie" hidden>
    <strong>Kami menggunakan cookie untuk meningkatkan pengalaman pengguna.</strong>
    <p>Anda dapat menerima atau menolak cookie non-esensial kapan saja melalui pengaturan browser.</p>
    <div class="cookie-actions">
      <button type="button" data-cookie-choice="accepted">Terima</button>
      <button type="button" class="secondary outline" data-cookie-choice="rejected">Tolak</button>
    </div>
  </section>
  <script defer src="{{ $baseUrl }}/assets/js/site.js?v={{ filemtime(base_path('public/assets/js/site.js')) }}"></script>
</body>
</html>
