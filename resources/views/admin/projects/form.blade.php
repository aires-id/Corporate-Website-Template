{{-- SPDX-License-Identifier: NCSA --}}
@extends('layouts.admin')

@section('content')
  @php($old = $_SESSION['old'] ?? [])
  <header class="admin-page-head"><div><h1>{{ $pageTitle }}</h1></div></header>
  <form method="post" action="{{ $project->exists ? $baseUrl . '/admin/projects/' . $project->id : $baseUrl . '/admin/projects' }}"><input type="hidden" name="_token" value="{{ $_SESSION['csrf_token'] ?? '' }}">
    <div class="form-grid">
      <label>Nama project<input name="name" maxlength="191" required value="{{ $old['name'] ?? $project->name }}"></label>
      <label>Status<select name="status">@foreach(['planned' => 'Planned', 'ongoing' => 'Ongoing', 'completed' => 'Completed'] as $value => $label)<option value="{{ $value }}" @selected(($old['status'] ?? $project->status ?? 'planned') === $value)>{{ $label }}</option>@endforeach</select></label>
      <label>Tahun<input type="number" name="year" min="2000" max="2100" value="{{ $old['year'] ?? $project->year }}"></label>
      <label>Gambar URL<input type="url" name="image_url" maxlength="2048" value="{{ $old['image_url'] ?? $project->image_url }}" placeholder="https://..."></label>
      <label class="full">Deskripsi<textarea name="description" maxlength="5000" rows="5">{{ $old['description'] ?? $project->description }}</textarea></label>
      <label class="full">Link terkait<input type="url" name="related_url" maxlength="2048" value="{{ $old['related_url'] ?? $project->related_url }}" placeholder="https://..."></label>
    </div><div class="form-actions"><button type="submit">Simpan Project</button><a href="{{ $baseUrl }}/admin/projects" role="button" class="secondary outline">Kembali</a></div>
  </form>
@endsection
