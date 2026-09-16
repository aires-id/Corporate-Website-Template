{{-- SPDX-License-Identifier: NCSA --}}
@extends('layouts.admin')

@section('content')
  @php($old = $_SESSION['old'] ?? [])
  @php($selectedMonth = (int) ($old['month'] ?? $report->month ?? 1))
  <header class="admin-page-head"><div><h1>{{ $pageTitle }}</h1><p>Hanya PDF valid dengan nama penyimpanan acak yang akan diterima.</p></div></header>
  <form method="post" enctype="multipart/form-data" action="{{ $report->exists ? $baseUrl . '/admin/reports/' . $report->id : $baseUrl . '/admin/reports' }}">
    <input type="hidden" name="_token" value="{{ $_SESSION['csrf_token'] ?? '' }}">
    <div class="form-grid">
      <label>Judul<input name="title" maxlength="191" required value="{{ $old['title'] ?? $report->title }}"></label>
      <label>Bulan<select name="month" required>@for($month = 1; $month <= 12; $month++)<option value="{{ $month }}" @selected($selectedMonth === $month)>{{ $month }}</option>@endfor</select></label>
      <label>Tahun<input type="number" name="year" min="2000" max="2100" required value="{{ $old['year'] ?? $report->year ?? date('Y') }}"></label>
      <label>File PDF<input type="file" name="pdf" accept="application/pdf,.pdf" {{ $report->exists ? '' : 'required' }}><small>{{ $report->exists ? 'Kosongkan jika tidak mengganti file.' : 'Maksimal sesuai MAX_PDF_SIZE.' }}</small></label>
      <label class="full">Deskripsi<textarea name="description" maxlength="5000" rows="5">{{ $old['description'] ?? $report->description }}</textarea></label>
    </div>
    <div class="form-actions"><button type="submit">Simpan Laporan</button><a href="{{ $baseUrl }}/admin/reports" role="button" class="secondary outline">Kembali</a></div>
  </form>
@endsection
