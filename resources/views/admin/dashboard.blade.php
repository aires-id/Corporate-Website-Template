{{-- SPDX-License-Identifier: NCSA --}}
@extends('layouts.admin')

@section('content')
  <header class="admin-page-head"><div><h1>Dashboard</h1><p>Ringkasan data website dan aktivitas publik.</p></div></header>
  <section class="stats-grid">
    @foreach([
      ['Jumlah akun', $stats['accounts']], ['Jumlah artikel', $stats['articles']], ['Artikel published', $stats['published']],
      ['Artikel draft', $stats['drafts']], ['Laporan keuangan', $stats['reports']], ['Jumlah project', $stats['projects']],
      ['Jumlah kerja sama', $stats['partners']], ['Pesan kontak', $stats['messages']], ['Total klik artikel', $stats['article_views']],
    ] as [$label, $value])
      <article class="stat"><small>{{ $label }}</small><strong>{{ number_format($value) }}</strong></article>
    @endforeach
  </section>
  <article class="admin-card"><header><h2>Akses Cepat</h2></header><div class="action-row"><a href="{{ $baseUrl }}/admin/articles/create" role="button">Buat Artikel</a><a href="{{ $baseUrl }}/admin/reports/create" role="button" class="secondary outline">Tambah Laporan</a><a href="{{ $baseUrl }}/admin/messages" role="button" class="secondary outline">Cek Pesan</a></div></article>
@endsection
