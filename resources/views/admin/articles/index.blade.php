{{-- SPDX-License-Identifier: NCSA --}}
@extends('layouts.admin')

@section('content')
  <header class="admin-page-head"><div><h1>Artikel</h1><p>Kelola draft, artikel terbit, dan pratinjau artikel.</p></div><a href="{{ $baseUrl }}/admin/articles/create" role="button">Buat Artikel</a></header>
  <form class="filter-form" method="get" action="{{ $baseUrl }}/admin/articles">
    <label>Cari artikel<input type="search" name="q" value="{{ $filters['q'] }}" placeholder="Judul atau slug"></label>
    <label>Status<select name="status"><option value="">Semua status</option><option value="draft" @selected($filters['status'] === 'draft')>Draft</option><option value="published" @selected($filters['status'] === 'published')>Published</option></select></label>
    <button type="submit">Terapkan</button>
  </form>
  <div class="responsive-table" role="region" aria-label="Tabel artikel" tabindex="0"><table>
    <thead><tr><th>Judul</th><th>Penulis</th><th>Status</th><th>Diperbarui</th><th>Klik</th><th>Aksi</th></tr></thead>
    <tbody>@forelse($articles as $article)
      <tr><td><strong>{{ $article->title }}</strong><br><small>/artikel/{{ $article->slug }}</small></td><td>{{ $article->author?->name ?: '—' }}</td><td><span class="status status-{{ $article->status }}">{{ $article->status }}</span></td><td>{{ $article->updated_at ? $article->updated_at->format('d M Y H:i') : '—' }}</td><td>{{ number_format($article->views_count) }}</td><td><div class="table-actions"><a href="{{ $baseUrl }}/admin/articles/{{ $article->id }}/preview" target="_blank" rel="noopener" role="button" class="secondary outline">Preview</a><a href="{{ $baseUrl }}/admin/articles/{{ $article->id }}/edit" role="button" class="secondary outline">Edit</a><form method="post" action="{{ $baseUrl }}/admin/articles/{{ $article->id }}/delete"><input type="hidden" name="_token" value="{{ $_SESSION['csrf_token'] ?? '' }}"><button type="submit" class="contrast">Hapus</button></form></div></td></tr>
    @empty<tr><td colspan="6">Belum ada artikel.</td></tr>@endforelse</tbody>
  </table></div>
  @include('partials.pagination', ['paginator' => $articles])
@endsection
