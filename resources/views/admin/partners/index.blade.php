{{-- SPDX-License-Identifier: NCSA --}}
@extends('layouts.admin')

@section('content')
  <header class="admin-page-head"><div><h1>Kerja Sama</h1><p>Kelola daftar partner organisasi.</p></div><a href="{{ $baseUrl }}/admin/partners/create" role="button">Tambah Kerja Sama</a></header>
  <div class="responsive-table" role="region" aria-label="Tabel kerja sama" tabindex="0"><table><thead><tr><th>Nama</th><th>Tahun</th><th>Website</th><th>Aksi</th></tr></thead><tbody>
    @forelse($partners as $partner)<tr><td><strong>{{ $partner->name }}</strong><br><small>{{ $partner->description }}</small></td><td>{{ $partner->cooperation_year ?: '—' }}</td><td>@if($partner->website_url)<a href="{{ $partner->website_url }}" target="_blank" rel="noopener">Buka</a>@else—@endif</td><td><div class="table-actions"><a href="{{ $baseUrl }}/admin/partners/{{ $partner->id }}/edit" role="button" class="secondary outline">Edit</a><form method="post" action="{{ $baseUrl }}/admin/partners/{{ $partner->id }}/delete"><input type="hidden" name="_token" value="{{ $_SESSION['csrf_token'] ?? '' }}"><button class="contrast" type="submit">Hapus</button></form></div></td></tr>@empty<tr><td colspan="4">Belum ada data kerja sama.</td></tr>@endforelse
  </tbody></table></div>
  @include('partials.pagination', ['paginator' => $partners])
@endsection
