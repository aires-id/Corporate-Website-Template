{{-- SPDX-License-Identifier: NCSA --}}
@extends('layouts.admin')

@section('content')
  <header class="admin-page-head"><div><h1>Project</h1><p>Kelola nama, status, tahun, gambar, dan link terkait.</p></div><a href="{{ $baseUrl }}/admin/projects/create" role="button">Tambah Project</a></header>
  <div class="responsive-table" role="region" aria-label="Tabel project" tabindex="0"><table><thead><tr><th>Nama</th><th>Status</th><th>Tahun</th><th>Aksi</th></tr></thead><tbody>
    @forelse($projects as $project)<tr><td><strong>{{ $project->name }}</strong><br><small>{{ $project->description }}</small></td><td><span class="status status-{{ $project->status }}">{{ $project->status }}</span></td><td>{{ $project->year ?: '—' }}</td><td><div class="table-actions"><a href="{{ $baseUrl }}/admin/projects/{{ $project->id }}/edit" role="button" class="secondary outline">Edit</a><form method="post" action="{{ $baseUrl }}/admin/projects/{{ $project->id }}/delete"><input type="hidden" name="_token" value="{{ $_SESSION['csrf_token'] ?? '' }}"><button class="contrast" type="submit">Hapus</button></form></div></td></tr>@empty<tr><td colspan="4">Belum ada project.</td></tr>@endforelse
  </tbody></table></div>
  @include('partials.pagination', ['paginator' => $projects])
@endsection
