{{-- SPDX-License-Identifier: NCSA --}}
@extends('layouts.public')

@section('content')
  <section class="page-hero"><div class="container"><h1>Kerja Sama</h1><p>Daftar kerja sama dan partner organisasi.</p></div></section>
  <section class="section"><div class="container"><div class="card-grid">
    @forelse($partners as $partner)
      <article class="card">
        @if($partner->logo_url)<img class="article-thumb" src="{{ $partner->logo_url }}" alt="Logo {{ $partner->name }}" loading="lazy">@endif
        <h2>{{ $partner->name }}</h2><p>{{ $partner->description }}</p><small>Tahun kerja sama: {{ $partner->cooperation_year ?: '—' }}</small>
        @if($partner->website_url)<p><a href="{{ $partner->website_url }}" target="_blank" rel="noopener noreferrer">Kunjungi website</a></p>@endif
      </article>
    @empty
      <div class="empty-state">Belum ada data kerja sama yang ditampilkan.</div>
    @endforelse
  </div>@include('partials.pagination', ['paginator' => $partners])</div></section>
@endsection
