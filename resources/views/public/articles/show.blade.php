{{-- SPDX-License-Identifier: NCSA --}}
@extends('layouts.public')

@section('content')
  <section class="section"><div class="container article-layout">
    <article class="article-page">
      @if($preview)<div class="preview-notice"><strong>PREVIEW</strong> — Tampilan ini tidak menambah jumlah klik dan tidak dapat diindeks.</div>@endif
      <header>
        <span class="eyebrow">{{ $preview ? 'Pratinjau Artikel' : 'Artikel' }}</span>
        <h1>{{ $article->title }}</h1>
        <div class="article-meta"><span>{{ $article->author?->name ?: '—' }}</span><span>{{ $article->published_at ? $article->published_at->format('d M Y') : 'Belum diterbitkan' }}</span><span>{{ $preview ? 'Preview tidak menghitung klik' : number_format($article->views_count) . ' klik' }}</span></div>
      </header>
      @if($article->thumbnail_url)<img class="article-feature" src="{{ $article->thumbnail_url }}" alt="Gambar utama artikel: {{ $article->title }}" loading="lazy">@endif
      <div class="article-body">{!! $article->body_html !!}</div>
      <div class="ad-slot" data-ad-slot="article-bottom">Slot iklan bawah artikel hanya aktif setelah cookie iklan diterima.</div>
      @if($preview)
        <footer class="admin-card"><h2>Meta information</h2><dl><dt>Slug</dt><dd>{{ $article->slug }}</dd><dt>Meta description</dt><dd>{{ $article->meta_description }}</dd><dt>Status</dt><dd>{{ $article->status }}</dd></dl></footer>
      @endif
    </article>
    <aside class="article-sidebar"><div class="ad-slot" data-ad-slot="article-sidebar">Slot iklan sidebar hanya aktif setelah cookie iklan diterima.</div></aside>
  </div></section>
@endsection
