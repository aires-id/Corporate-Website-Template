{{-- SPDX-License-Identifier: NCSA --}}
@extends('layouts.admin')

@section('content')
  <div class="admin-card" style="max-width:30rem;margin:3rem auto;">
    <header><h1>Masuk Admin</h1></header>
    <p>Gunakan akun administrator atau editor yang aktif.</p>
    <form method="post" action="{{ $baseUrl }}/admin/login">
      <input type="hidden" name="_token" value="{{ $_SESSION['csrf_token'] ?? '' }}">
      <label>Email<input type="email" name="email" autocomplete="email" required value="{{ $_SESSION['old']['email'] ?? '' }}"></label>
      <label>Kata sandi<input type="password" name="password" autocomplete="current-password" required></label>
      <button type="submit">Masuk</button>
    </form>
  </div>
@endsection
