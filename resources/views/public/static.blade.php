{{-- SPDX-License-Identifier: NCSA --}}
@extends('layouts.public')

@section('content')
  @if(in_array($title, ['Tentang Kami', 'Organisasi', 'Hubungan Investor']))
    @php($isInvestor = $title === 'Hubungan Investor')
    <section class="corporate-hero interior-cover"><div class="container corporate-hero-grid">
      <div class="corporate-hero-copy"><div class="breadcrumb"><a href="{{ $baseUrl }}/">Beranda</a><span>/</span><span>{{ $title }}</span></div><span class="eyebrow">{{ $site['site_name'] }}</span><h1>{{ $title }}<em>{{ $isInvestor ? 'Informasi yang menghubungkan.' : 'Lebih dekat dengan kami.' }}</em></h1><p>{{ $isInvestor ? 'Temukan profil organisasi, akses laporan keuangan, dan informasi untuk investor.' : 'Mengenal perjalanan, tujuan, dan orang-orang di balik organisasi.' }}</p><a class="text-link" href="{{ $isInvestor ? '#laporan' : '#profil' }}">{{ $isInvestor ? 'Jelajahi laporan' : 'Jelajahi profil' }} &darr;</a></div>
      <figure class="corporate-hero-photo"><img src="{{ $baseUrl }}/assets/images/hero-team-collaboration.jpg" width="720" height="720" alt="Ilustrasi kolaborasi organisasi" fetchpriority="high"><figcaption>{{ $isInvestor ? 'Hubungan investor' : 'Tentang organisasi' }}</figcaption></figure>
    </div></section>
    @if($isInvestor)
      <nav class="container shortcut-bar" aria-label="Informasi investor"><a href="#laporan">Laporan keuangan <span>&darr;</span></a><a href="#dokumen">Dokumen publik <span>&darr;</span></a><a href="{{ $baseUrl }}/tentang/organisasi">Profil organisasi <span>&rarr;</span></a><a href="{{ $baseUrl }}/kontak">Kontak investor <span>&rarr;</span></a></nav>
      <section class="section" id="laporan"><div class="container editorial-intro"><div><span class="eyebrow">Pusat laporan</span><h2>Laporan keuangan</h2><p>Telusuri laporan bulanan yang telah diterbitkan. Dokumen tersedia dalam format PDF.</p><a class="text-link" href="{{ $baseUrl }}/tentang/laporan-keuangan">Semua laporan &rarr;</a></div><div class="report-list">
        @forelse($reports as $report)<div class="report-row"><span class="document-label">PDF</span><div><small>{{ $report->month }} / {{ $report->year }}</small><h3>{{ $report->title }}</h3><div class="report-actions"><a href="{{ $baseUrl }}/laporan/{{ $report->id }}/view" target="_blank" rel="noopener">Lihat laporan</a><a href="{{ $baseUrl }}/laporan/{{ $report->id }}/download">Unduh &darr;</a></div></div></div>@empty<div class="publication-empty"><span class="document-label">PDF</span><h3>Laporan akan tersedia di sini.</h3><p>Belum ada laporan keuangan yang diterbitkan.</p><a class="text-link" href="{{ $baseUrl }}/kontak">Tanyakan informasi laporan &rarr;</a></div>@endforelse
      </div></div></section>
      <section class="section section-muted" id="dokumen"><div class="container"><div class="section-heading"><div><span class="eyebrow">Informasi publik</span><h2>Dokumen &amp; informasi organisasi</h2></div></div><div class="document-grid"><article><span class="document-label">TAHUNAN</span><h3>Laporan tahunan</h3><p>Dokumen laporan tahunan belum tersedia.</p><a class="text-link" href="{{ $baseUrl }}/kontak">Hubungi kami &rarr;</a></article><article><span class="document-label">PUBLIK</span><h3>Dokumen publik</h3><p>Untuk permintaan dokumen dan informasi lebih lanjut, silakan hubungi organisasi.</p><a class="text-link" href="{{ $baseUrl }}/kontak">Permintaan informasi &rarr;</a></article><article><span class="document-label">ORGANISASI</span><h3>Kenali organisasi</h3><p>Pelajari profil, arah, dan struktur organisasi kami.</p><a class="text-link" href="{{ $baseUrl }}/tentang/organisasi">Lihat profil &rarr;</a></article></div></div></section>
    @else
      <section class="section" id="profil"><div class="container profile-layout"><nav class="profile-nav" aria-label="Bagian profil"><strong>Kenali kami</strong>@foreach($sections as $section)<a href="#profil-{{ $loop->index }}">{{ $section['heading'] }}</a>@endforeach<a href="{{ $baseUrl }}/tentang/struktur">Struktur organisasi &rarr;</a></nav><div class="profile-content">@foreach($sections as $section)<section id="profil-{{ $loop->index }}" class="profile-section"><span class="profile-number" aria-hidden="true">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span><div><h2>{{ $section['heading'] }}</h2><p>{{ $section['content'] }}</p></div></section>@endforeach</div></div></section>
      <section class="investor-feature"><div class="container editorial-intro"><div><span class="eyebrow">Orang &amp; organisasi</span><h2>Mengenal peran<br>di balik organisasi.</h2></div><div><p>Temukan susunan anggota dan tanggung jawabnya melalui struktur organisasi.</p><a href="{{ $baseUrl }}/tentang/struktur" class="text-link">Lihat struktur organisasi &rarr;</a></div></div></section>
    @endif
    @include('partials.corporate-contact')
  @else
    <section class="page-hero"><div class="container"><h1>{{ $title }}</h1><p>{{ $description }}</p></div></section>
    <section class="section"><div class="container content-stack">@foreach($sections as $section)<article><h2>{{ $section['heading'] }}</h2><p>{{ $section['content'] }}</p></article>@endforeach</div></section>
  @endif
@endsection
