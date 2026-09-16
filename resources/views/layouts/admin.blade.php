{{-- SPDX-License-Identifier: NCSA --}}
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $site['site_name'] }} — Admin</title>
  <meta name="robots" content="noindex, nofollow">
  <link rel="stylesheet" href="{{ $baseUrl }}/assets/css/pico.min.css">
  <link rel="stylesheet" href="{{ $baseUrl }}/assets/css/site.css?v=20260915-6">
</head>
<body class="admin-body">
  <header class="admin-header">
    <div class="container admin-topbar">
      <a class="brand" href="{{ $baseUrl }}/admin/dashboard"><span class="brand-mark" aria-hidden="true">O</span><span>{{ $site['site_name'] }} <small>Admin</small></span></a>
      @if(isset($user))
        <div class="admin-account"><span>{{ $user->name }} · {{ ucfirst($user->role) }}</span><form method="post" action="{{ $baseUrl }}/admin/logout"><input type="hidden" name="_token" value="{{ $_SESSION['csrf_token'] ?? '' }}"><button type="submit" class="secondary outline">Keluar</button></form></div>
      @endif
    </div>
  </header>
  @if(isset($user))
    <nav class="admin-nav" aria-label="Navigasi admin"><div class="container">
      <a href="{{ $baseUrl }}/admin/dashboard">Dashboard</a>
      <a href="{{ $baseUrl }}/admin/articles">Artikel</a>
      <a href="{{ $baseUrl }}/admin/reports">Laporan</a>
      <a href="{{ $baseUrl }}/admin/projects">Project</a>
      <a href="{{ $baseUrl }}/admin/partners">Kerja Sama</a>
      <a href="{{ $baseUrl }}/admin/members">Struktur</a>
      @if($user->role === 'admin')<a href="{{ $baseUrl }}/admin/users">Akun</a>@endif
      <a href="{{ $baseUrl }}/admin/messages">Pesan</a>
    </div></nav>
  @endif
  @if(!empty($_SESSION['flash']))
    <div class="container flash-wrap"><article class="flash flash-{{ $_SESSION['flash']['type'] }}">{{ $_SESSION['flash']['message'] }}</article></div>
  @endif
  @if(!empty($_SESSION['form_errors']))
    <div class="container flash-wrap"><article id="form-errors" class="flash flash-error" role="alert" aria-live="assertive" tabindex="-1"><strong>Mohon periksa:</strong><ul>@foreach($_SESSION['form_errors'] as $error)<li>{{ $error }}</li>@endforeach</ul></article></div>
  @endif
  <main class="container admin-main">@yield('content')</main>
  <script defer src="{{ $baseUrl }}/assets/js/site.js?v=20260915-4"></script>
</body>
</html>
