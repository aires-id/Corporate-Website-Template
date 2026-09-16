{{-- SPDX-License-Identifier: NCSA --}}
@extends('layouts.admin')

@section('content')
  @php($old = $_SESSION['old'] ?? [])
  <header class="admin-page-head"><div><h1>{{ $pageTitle }}</h1></div></header>
  <form method="post" action="{{ $member->exists ? $baseUrl . '/admin/members/' . $member->id : $baseUrl . '/admin/members' }}"><input type="hidden" name="_token" value="{{ $_SESSION['csrf_token'] ?? '' }}">
    <div class="form-grid">
      <label>Nama<input name="name" maxlength="150" required value="{{ $old['name'] ?? $member->name }}"></label>
      <label>Jabatan<input name="position" maxlength="150" required value="{{ $old['position'] ?? $member->position }}"></label>
      <label class="full">Foto URL<input type="url" name="photo_url" maxlength="2048" value="{{ $old['photo_url'] ?? $member->photo_url }}" placeholder="https://..."></label>
      <label>Urutan tampil<input type="number" name="sort_order" min="0" max="9999" value="{{ $old['sort_order'] ?? $member->sort_order ?? 0 }}"></label>
      <label><input type="hidden" name="is_active" value="0"><input type="checkbox" name="is_active" value="1" @checked((bool)($old['is_active'] ?? $member->is_active ?? true))> Tampilkan di halaman publik</label>
      <label class="full">Deskripsi singkat<textarea name="short_description" maxlength="1000" rows="4">{{ $old['short_description'] ?? $member->short_description }}</textarea></label>
    </div><div class="form-actions"><button type="submit">Simpan Anggota</button><a href="{{ $baseUrl }}/admin/members" role="button" class="secondary outline">Kembali</a></div>
  </form>
@endsection
