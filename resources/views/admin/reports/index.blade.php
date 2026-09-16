{{-- SPDX-License-Identifier: NCSA --}}
@extends('layouts.admin')

@section('content')
  <header class="admin-page-head"><div><h1>Laporan Keuangan</h1><p>File disimpan di luar web root dan hanya PDF yang diterima.</p></div><a href="{{ $baseUrl }}/admin/reports/create" role="button">Tambah Laporan</a></header>
  <div class="responsive-table" role="region" aria-label="Tabel laporan keuangan" tabindex="0"><table>
    <thead><tr><th>Judul</th><th>Periode</th><th>File</th><th>Diunggah</th><th>Aksi</th></tr></thead>
    <tbody>@forelse($reports as $report)
      <tr><td><strong>{{ $report->title }}</strong><br><small>{{ $report->description }}</small></td><td>{{ $report->month }}/{{ $report->year }}</td><td>{{ $report->display_name }}<br><small>{{ number_format($report->size / 1024, 1) }} KB</small></td><td>{{ $report->uploaded_at ? $report->uploaded_at->format('d M Y H:i') : '—' }}</td><td><div class="table-actions"><a href="{{ $baseUrl }}/laporan/{{ $report->id }}/view" target="_blank" rel="noopener" role="button" class="secondary outline">View</a><a href="{{ $baseUrl }}/admin/reports/{{ $report->id }}/edit" role="button" class="secondary outline">Edit</a><form method="post" action="{{ $baseUrl }}/admin/reports/{{ $report->id }}/delete"><input type="hidden" name="_token" value="{{ $_SESSION['csrf_token'] ?? '' }}"><button type="submit" class="contrast">Hapus</button></form></div></td></tr>
    @empty<tr><td colspan="5">Belum ada laporan.</td></tr>@endforelse</tbody>
  </table></div>
  @include('partials.pagination', ['paginator' => $reports])
@endsection
