{{-- SPDX-License-Identifier: NCSA --}}
@extends('layouts.public')

@section('content')
  <section class="page-hero"><div class="container"><h1>Project</h1><p>Daftar project dan status pelaksanaannya.</p></div></section>
  <section class="section"><div class="container"><div class="article-grid">
    @forelse($projects as $project)
      <article class="article-card">
        @if($project->image_url)<img class="article-thumb" src="{{ $project->image_url }}" alt="Gambar project: {{ $project->name }}" loading="lazy">@endif
        <h2>{{ $project->name }}</h2><p>{{ $project->description }}</p>
        <div class="article-meta"><span class="status status-{{ $project->status }}">{{ $project->status }}</span><span>{{ $project->year ?: '—' }}</span></div>
        @if($project->related_url)<footer><a href="{{ $project->related_url }}" target="_blank" rel="noopener noreferrer" role="button" class="secondary outline">Link terkait</a></footer>@endif
      </article>
    @empty
      <div class="empty-state">Belum ada project yang ditampilkan.</div>
    @endforelse
  </div>@include('partials.pagination', ['paginator' => $projects])</div></section>
@endsection
