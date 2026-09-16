{{-- SPDX-License-Identifier: NCSA --}}
@extends('layouts.admin')

@section('content')
  <header class="admin-page-head"><div><h1>Cek Akun</h1><p>Kelola role dan status aktif akun admin/editor.</p></div></header>
  <form class="filter-form" method="get" action="{{ $baseUrl }}/admin/users"><label>Cari akun<input type="search" name="q" value="{{ $filters['q'] }}" placeholder="Nama atau email"></label><label>Role<select name="role"><option value="">Semua role</option><option value="admin" @selected($filters['role'] === 'admin')>Admin</option><option value="editor" @selected($filters['role'] === 'editor')>Editor</option></select></label><button type="submit">Terapkan</button></form>
  <div class="responsive-table" role="region" aria-label="Tabel akun" tabindex="0"><table><thead><tr><th>ID</th><th>Nama</th><th>Email</th><th>Role</th><th>Dibuat</th><th>Status</th><th>Aksi</th></tr></thead><tbody>
    @forelse($accounts as $account)<tr><td>{{ $account->id }}</td><td>{{ $account->name }}</td><td>{{ $account->email }}</td><td>{{ ucfirst($account->role) }}</td><td>{{ $account->created_at ? $account->created_at->format('d M Y') : '—' }}</td><td><span class="status status-{{ $account->is_active ? 'active' : 'inactive' }}">{{ $account->is_active ? 'Aktif' : 'Nonaktif' }}</span></td><td><form class="table-actions" method="post" action="{{ $baseUrl }}/admin/users/{{ $account->id }}"><input type="hidden" name="_token" value="{{ $_SESSION['csrf_token'] ?? '' }}"><select name="role"><option value="admin" @selected($account->role === 'admin')>Admin</option><option value="editor" @selected($account->role === 'editor')>Editor</option></select><select name="is_active"><option value="1" @selected($account->is_active)>Aktif</option><option value="0" @selected(!$account->is_active)>Nonaktif</option></select><button type="submit" class="secondary outline">Simpan</button></form></td></tr>@empty<tr><td colspan="7">Tidak ada akun.</td></tr>@endforelse
  </tbody></table></div>
  @include('partials.pagination', ['paginator' => $accounts])
@endsection
