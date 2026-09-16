{{-- SPDX-License-Identifier: NCSA --}}
@extends('layouts.admin')

@section('content')
  @php($old = $_SESSION['old'] ?? [])
  <header class="admin-page-head"><div><h1>{{ $pageTitle }}</h1></div></header>
  <form method="post" action="{{ $partner->exists ? $baseUrl . '/admin/partners/' . $partner->id : $baseUrl . '/admin/partners' }}"><input type="hidden" name="_token" value="{{ $_SESSION['csrf_token'] ?? '' }}">
    <div class="form-grid">
      <label>Nama partner<input name="name" maxlength="191" required value="{{ $old['name'] ?? $partner->name }}"></label>
      <label>Tahun kerja sama<input type="number" name="cooperation_year" min="2000" max="2100" value="{{ $old['cooperation_year'] ?? $partner->cooperation_year }}"></label>
      <label class="full">Logo URL<input type="url" name="logo_url" maxlength="2048" value="{{ $old['logo_url'] ?? $partner->logo_url }}" placeholder="https://..."></label>
      <label class="full">Deskripsi<textarea name="description" maxlength="5000" rows="5">{{ $old['description'] ?? $partner->description }}</textarea></label>
      <label class="full">Link website<input type="url" name="website_url" maxlength="2048" value="{{ $old['website_url'] ?? $partner->website_url }}" placeholder="https://..."></label>
    </div><div class="form-actions"><button type="submit">Simpan Kerja Sama</button><a href="{{ $baseUrl }}/admin/partners" role="button" class="secondary outline">Kembali</a></div>
  </form>
@endsection
