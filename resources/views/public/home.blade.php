{{-- SPDX-License-Identifier: NCSA --}}
@extends('layouts.public')

@section('content')
  <section class="hero">
    <div class="container hero-grid">
      <div class="hero-copy">
        <span class="eyebrow">{{ $site['site_name'] }}</span>
        <h1>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</h1>
        <p>{{ $site['site_description'] }}</p>
        <div class="action-row">
          <a href="{{ $baseUrl }}/tentang" role="button">Tentang Kami</a>
          <a href="{{ $baseUrl }}/kontak" role="button" class="secondary outline">Hubungi Kami</a>
        </div>
      </div>
      <div class="hero-visual">
        <picture><source srcset="{{ $baseUrl }}/assets/images/hero-team-collaboration.jpg" type="image/jpeg"><img src="{{ $baseUrl }}/assets/images/hero-team-collaboration.png" width="720" height="720" alt="Kolaborasi tim organisasi" fetchpriority="high" decoding="async"></picture>
      </div>
    </div>
  </section>

  <section class="section">
    <div class="container split-copy">
      <div class="section-heading">
        <div><h2>Tentang Kami</h2><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p></div>
      </div>
      <a href="{{ $baseUrl }}/tentang" role="button" class="secondary outline">Selengkapnya</a>
    </div>
  </section>

  <section class="section section-muted">
    <div class="container">
      <div class="section-heading"><div><h2>Program &amp; Aktivitas</h2><p>Informasi utama disusun ringkas agar mudah ditemukan.</p></div></div>
      <div class="card-grid">
        @foreach ([['Organisasi', 'Profil, arah, dan struktur organisasi.'], ['Komunitas', 'Artikel dan informasi komunitas.'], ['Kerja Sama', 'Daftar inisiatif bersama partner.'], ['Project', 'Status dan ringkasan project.']] as [$title, $copy])
          <article class="card"><span class="card-icon" aria-hidden="true">{{ strtoupper(substr($title, 0, 1)) }}</span><h3>{{ $title }}</h3><p>{{ $copy }}</p></article>
        @endforeach
      </div>
    </div>
  </section>

  <section class="section">
    <div class="container">
      <div class="section-heading"><div><h2>Artikel Terbaru</h2><p>Informasi dan pembaruan yang telah diterbitkan.</p></div><a href="{{ $baseUrl }}/artikel">Lihat semua artikel</a></div>
      <div class="article-grid">
        @forelse($articles as $article)
          <article class="article-card">
            @if($article->thumbnail_url)<img class="article-thumb" src="{{ $article->thumbnail_url }}" alt="Thumbnail artikel: {{ $article->title }}" loading="lazy">@else<div class="article-thumb article-thumb-placeholder" aria-hidden="true">Artikel</div>@endif
            <h3>{{ $article->title }}</h3>
            <p>{{ $article->excerpt }}</p>
            <div class="article-meta"><span>{{ $article->author?->name ?: '—' }}</span><span>{{ $article->published_at ? $article->published_at->format('d M Y') : '—' }}</span><span>{{ number_format($article->views_count) }} klik</span></div>
            <footer><a href="{{ $baseUrl }}/artikel/{{ rawurlencode($article->slug) }}" role="button" class="secondary outline">Baca Selengkapnya</a></footer>
          </article>
        @empty
          <div class="empty-state">Belum ada artikel yang diterbitkan.</div>
        @endforelse
      </div>
    </div>
  </section>

  <section class="section section-muted">
    <div class="container investor-panel">
      <div><h2>Hubungan Investor</h2><p>Temukan informasi organisasi, laporan keuangan, dokumen publik, dan informasi investor dalam satu area yang ringkas.</p></div>
      <div class="action-row"><a href="{{ $baseUrl }}/hubungan-investor" role="button">Hubungan Investor</a></div>
    </div>
  </section>

  <section class="section">
    <div class="container cta"><h2>Ingin bekerja sama dengan kami?</h2><p>Silakan kirimkan pesan melalui formulir kontak untuk memulai percakapan.</p><a href="{{ $baseUrl }}/kontak" role="button" class="secondary">Hubungi Kami</a></div>
  </section>
@endsection
