{{-- SPDX-License-Identifier: NCSA --}}
@extends('layouts.public')

@section('content')
  <section class="page-hero"><div class="container"><h1>Laporan Keuangan Bulanan</h1><p>Daftar laporan keuangan yang dapat dilihat atau diunduh dalam format PDF.</p></div></section>
  <section class="section"><div class="container">
    <div class="responsive-table" role="region" aria-label="Tabel laporan keuangan" tabindex="0"><table>
      <thead><tr><th>Judul Laporan</th><th>Bulan</th><th>Tahun</th><th>Tanggal Upload</th><th>Aksi</th></tr></thead>
      <tbody>
      @forelse($reports as $report)
        <tr><td><strong>{{ $report->title }}</strong><br><small>{{ $report->description }}</small></td><td>{{ $report->month }}</td><td>{{ $report->year }}</td><td>{{ $report->uploaded_at ? $report->uploaded_at->format('d M Y') : '—' }}</td><td><div class="table-actions"><a href="{{ $baseUrl }}/laporan/{{ $report->id }}/view" target="_blank" rel="noopener" role="button" class="secondary outline">View</a><a href="{{ $baseUrl }}/laporan/{{ $report->id }}/download" role="button">Download</a></div></td></tr>
      @empty
        <tr><td colspan="5">Belum ada laporan keuangan yang tersedia.</td></tr>
      @endforelse
      </tbody>
    </table></div>
    @include('partials.pagination', ['paginator' => $reports])
  </div></section>
@endsection
