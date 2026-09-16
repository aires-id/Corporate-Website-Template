{{-- SPDX-License-Identifier: NCSA --}}
@extends('layouts.admin')

@section('content')
  <header class="admin-page-head"><div><h1>Struktur Organisasi</h1><p>Kelola anggota yang ditampilkan pada halaman struktur.</p></div><a href="{{ $baseUrl }}/admin/members/create" role="button">Tambah Anggota</a></header>
  <div class="responsive-table" role="region" aria-label="Tabel struktur organisasi" tabindex="0"><table><thead><tr><th>Nama</th><th>Jabatan</th><th>Urutan</th><th>Status</th><th>Aksi</th></tr></thead><tbody>
    @forelse($members as $member)<tr><td><strong>{{ $member->name }}</strong><br><small>{{ $member->short_description }}</small></td><td>{{ $member->position }}</td><td>{{ $member->sort_order }}</td><td><span class="status status-{{ $member->is_active ? 'active' : 'inactive' }}">{{ $member->is_active ? 'Aktif' : 'Nonaktif' }}</span></td><td><div class="table-actions"><a href="{{ $baseUrl }}/admin/members/{{ $member->id }}/edit" role="button" class="secondary outline">Edit</a><form method="post" action="{{ $baseUrl }}/admin/members/{{ $member->id }}/delete"><input type="hidden" name="_token" value="{{ $_SESSION['csrf_token'] ?? '' }}"><button type="submit" class="contrast">Hapus</button></form></div></td></tr>@empty<tr><td colspan="5">Belum ada anggota struktur.</td></tr>@endforelse
  </tbody></table></div>
  @include('partials.pagination', ['paginator' => $members])
@endsection
