{{-- SPDX-License-Identifier: NCSA --}}
@extends('layouts.admin')

@section('content')
  @php($old = $_SESSION['old'] ?? [])
  <header class="admin-page-head"><div><h1>{{ $pageTitle }}</h1><p>Konten disanitasi saat disimpan. Gunakan Preview sebelum menerbitkan.</p></div>@if($article->exists)<a href="{{ $baseUrl }}/admin/articles/{{ $article->id }}/preview" target="_blank" rel="noopener" role="button" class="secondary outline">PREVIEW</a>@endif</header>
  @if(!$article->exists)
    <form class="upload-inline" method="post" action="{{ $baseUrl }}/admin/articles/import-docx" enctype="multipart/form-data"><input type="hidden" name="_token" value="{{ $_SESSION['csrf_token'] ?? '' }}"><label>Impor DOCX<input type="file" name="docx" accept=".docx,application/vnd.openxmlformats-officedocument.wordprocessingml.document" required></label><button type="submit" class="secondary outline">Impor isi DOCX</button></form>
  @endif
  <form method="post" action="{{ $article->exists ? $baseUrl . '/admin/articles/' . $article->id : $baseUrl . '/admin/articles' }}">
    <input type="hidden" name="_token" value="{{ $_SESSION['csrf_token'] ?? '' }}">
    <div class="form-grid">
      <label>Judul<input name="title" maxlength="191" required value="{{ $old['title'] ?? $article->title }}"></label>
      <label>Slug<input name="slug" maxlength="191" value="{{ $old['slug'] ?? $article->slug }}" placeholder="Dibuat otomatis jika kosong"></label>
      <label class="full">Ringkasan<textarea name="excerpt" maxlength="2000" rows="3">{{ $old['excerpt'] ?? $article->excerpt }}</textarea></label>
      <label class="full">Thumbnail URL<input type="url" name="thumbnail_url" maxlength="2048" value="{{ $old['thumbnail_url'] ?? $article->thumbnail_url }}" placeholder="https://..."></label>
      <div class="full"><span id="article-editor-label">Isi Artikel</span><div class="editor-toolbar" role="toolbar" aria-label="Toolbar editor artikel"><button type="button" data-editor-command="bold" aria-label="Tebal" title="Tebal"><strong>B</strong></button><button type="button" data-editor-command="italic" aria-label="Miring" title="Miring"><em>I</em></button><button type="button" data-editor-command="insertOrderedList" aria-label="Daftar bernomor">1. List</button><button type="button" data-editor-command="insertUnorderedList" aria-label="Daftar berpoin">• List</button><button type="button" data-editor-command="h2" aria-label="Heading">Heading</button><button type="button" data-editor-command="p" aria-label="Paragraf">Paragraph</button><button type="button" data-editor-command="link" aria-label="Masukkan tautan">Link</button><button type="button" data-editor-command="image" aria-label="Masukkan gambar melalui URL">Image URL</button><button type="button" data-editor-command="ad" aria-label="Masukkan placeholder Google Ads">Google Ads</button></div><div class="rich-editor" contenteditable="true" data-rich-editor role="textbox" aria-multiline="true" aria-labelledby="article-editor-label">{!! $old['body_html'] ?? $importedBody ?? $article->body_html !!}</div><textarea name="body_html" data-editor-input hidden>{{ $old['body_html'] ?? $importedBody ?? $article->body_html }}</textarea></div>
      <label>Meta description<textarea name="meta_description" maxlength="300" rows="3">{{ $old['meta_description'] ?? $article->meta_description }}</textarea></label>
      <label>Status<select name="status"><option value="draft" @selected(($old['status'] ?? $article->status ?? 'draft') === 'draft')>Draft</option><option value="published" @selected(($old['status'] ?? $article->status) === 'published')>Published</option></select></label>
    </div>
    <div class="form-actions"><button type="submit">Simpan Artikel</button><a href="{{ $baseUrl }}/admin/articles" role="button" class="secondary outline">Kembali</a></div>
  </form>
@endsection
