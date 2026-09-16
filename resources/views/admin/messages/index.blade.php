{{-- SPDX-License-Identifier: NCSA --}}
@extends('layouts.admin')

@section('content')
  <header class="admin-page-head"><div><h1>Pesan Kontak</h1><p>Pesan yang dikirim melalui formulir publik.</p></div></header>
  <form class="filter-form" method="get" action="{{ $baseUrl }}/admin/messages"><label>Status<select name="status"><option value="">Semua status</option><option value="new" @selected($filters['status'] === 'new')>Baru</option><option value="read" @selected($filters['status'] === 'read')>Dibaca</option><option value="archived" @selected($filters['status'] === 'archived')>Arsip</option></select></label><span></span><button type="submit">Terapkan</button></form>
  <div class="responsive-table" role="region" aria-label="Tabel pesan kontak" tabindex="0"><table><thead><tr><th>Pengirim</th><th>Subjek &amp; Pesan</th><th>Dikirim</th><th>Status</th><th>Aksi</th></tr></thead><tbody>
    @forelse($messages as $message)<tr><td><strong>{{ $message->name }}</strong><br><a href="mailto:{{ $message->email }}">{{ $message->email }}</a></td><td><strong>{{ $message->subject }}</strong><br><small>{{ $message->message }}</small></td><td>{{ $message->created_at ? $message->created_at->format('d M Y H:i') : '—' }}</td><td><span class="status status-{{ $message->status }}">{{ $message->status }}</span></td><td><form class="table-actions" method="post" action="{{ $baseUrl }}/admin/messages/{{ $message->id }}"><input type="hidden" name="_token" value="{{ $_SESSION['csrf_token'] ?? '' }}"><select name="status"><option value="new" @selected($message->status === 'new')>Baru</option><option value="read" @selected($message->status === 'read')>Dibaca</option><option value="archived" @selected($message->status === 'archived')>Arsip</option></select><button type="submit" class="secondary outline">Simpan</button></form></td></tr>@empty<tr><td colspan="5">Belum ada pesan.</td></tr>@endforelse
  </tbody></table></div>
  @include('partials.pagination', ['paginator' => $messages])
@endsection
