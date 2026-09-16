{{-- SPDX-License-Identifier: NCSA --}}
@extends('layouts.public')

@section('content')
  <section class="page-hero"><div class="container"><h1>Struktur Organisasi</h1><p>Data anggota dapat diperbarui melalui database atau area admin.</p></div></section>
  <section class="section"><div class="container">
    <div class="member-grid">
      @forelse($members as $member)
        <article class="member-card">
          @if($member->photo_url)<img class="member-photo" src="{{ $member->photo_url }}" alt="Foto {{ $member->name }}" loading="lazy">@else<div class="member-placeholder" aria-hidden="true">{{ strtoupper(substr($member->name, 0, 1)) }}</div>@endif
          <div><h2>{{ $member->name }}</h2><strong>{{ $member->position }}</strong><p>{{ $member->short_description }}</p></div>
        </article>
      @empty
        <div class="empty-state">Belum ada data anggota struktur. Tambahkan melalui database atau area admin.</div>
      @endforelse
    </div>
    @include('partials.pagination', ['paginator' => $members])
  </div></section>
@endsection
