{{-- SPDX-License-Identifier: NCSA --}}
@extends('layouts.public')

@section('content')
  <section class="page-hero"><div class="container"><h1>Artikel</h1><p>Gunakan pencarian atau filter tanggal untuk menemukan artikel yang diterbitkan.</p></div></section>
  <section class="section"><div class="container">
    <form class="filter-form article-filters" method="get" action="{{ $baseUrl }}/artikel">
      <label>Cari artikel<input type="search" name="q" value="{{ $filters['q'] }}" placeholder="Judul atau ringkasan"></label>
      <label>Tanggal terbit<input type="date" name="date" value="{{ $filters['date'] }}"></label>
      <button type="submit">Terapkan</button>
    </form>
    <div class="article-grid">
      @forelse($articles as $article)
        <article class="article-card">
          @if($article->thumbnail_url)<img class="article-thumb" src="{{ $article->thumbnail_url }}" alt="Thumbnail artikel: {{ $article->title }}" loading="lazy">@else<div class="article-thumb article-thumb-placeholder" aria-hidden="true">Artikel</div>@endif
          <h2>{{ $article->title }}</h2><p>{{ $article->excerpt }}</p>
          <div class="article-meta"><span>{{ $article->author?->name ?: '—' }}</span><span>{{ $article->published_at ? $article->published_at->format('d M Y') : '—' }}</span><span>{{ number_format($article->views_count) }} klik</span></div>
          <footer><a href="{{ $baseUrl }}/artikel/{{ rawurlencode($article->slug) }}" role="button" class="secondary outline">Baca Selengkapnya</a></footer>
        </article>
      @empty
        <div class="empty-state">Belum ada artikel yang sesuai.</div>
      @endforelse
    </div>
    @include('partials.pagination', ['paginator' => $articles])
  </div></section>
@endsection
