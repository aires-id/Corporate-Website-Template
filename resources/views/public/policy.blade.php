{{-- SPDX-License-Identifier: NCSA --}}
@extends('layouts.public')
@section('content')
<section class="page-hero policy-hero"><div class="container">
  <div class="breadcrumb"><a href="{{ $baseUrl }}/">Beranda</a><span>/</span><span>Privasi &amp; informasi</span></div>
  <span class="eyebrow">{{ $site['site_name'] }}</span>
  <h1>{{ $title }}</h1><p>{{ $policy['summary'] }}</p>
  <div class="policy-meta"><span>Versi template 1.0</span><span>Disusun 16 September 2026</span><span>Belum berlaku &middot; draf peninjauan</span></div>
</div></section>
<div class="container policy-tabs" aria-label="Dokumen kebijakan">
  <a href="{{ $baseUrl }}/privacy-policy" @if($kind === 'privacy') aria-current="page" @endif>Kebijakan privasi</a>
  <a href="{{ $baseUrl }}/cookie-policy" @if($kind === 'cookie') aria-current="page" @endif>Kebijakan cookie</a>
</div>
<section class="section"><div class="container policy-layout">
  <aside class="policy-index"><h2>Dalam dokumen ini</h2><ol>
    @foreach($policy['sections'] as $section)<li><a href="#ketentuan-{{ $loop->iteration }}">{{ $section['heading'] }}</a></li>@endforeach
  </ol><a class="text-link" href="{{ $baseUrl }}/kontak">Hubungi pengelola &rarr;</a></aside>
  <div class="policy-content">
    <div class="policy-draft" role="note"><strong>Template untuk ditinjau sebelum publikasi</strong><p>Bagian bertanda kurung siku harus dilengkapi dan diverifikasi. Dokumen ini bukan kebijakan final atau pernyataan kepatuhan; pengelola perlu menyesuaikannya dengan praktik operasional dan meninjau aspek hukumnya.</p></div>
    @foreach($policy['sections'] as $section)
      <section id="ketentuan-{{ $loop->iteration }}" class="policy-section">
        <span class="policy-number">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
        <h2>{{ $section['heading'] }}</h2>
        @foreach($section['paragraphs'] as $paragraph)<p>{{ $paragraph }}</p>@endforeach
        @if(!empty($section['inventory']))
          <div class="policy-table-wrap" tabindex="0" role="region" aria-label="Inventaris penyimpanan browser">
            <table><caption>Inventaris penyimpanan pihak pertama</caption><thead><tr><th scope="col">Nama &amp; teknologi</th><th scope="col">Tujuan</th><th scope="col">Masa simpan</th></tr></thead><tbody>
              <tr><th scope="row"><code>organization_portal_session</code><small>Cookie pihak pertama</small></th><td>Sesi, autentikasi dan keamanan formulir; sesi juga menjadi sumber pengenal turunan untuk hit artikel.</td><td>Sesi browser, bergantung pada perilaku pemulihan sesi.</td></tr>
              <tr><th scope="row"><code>organization_portal_cookie_consent</code><small>Cookie pihak pertama</small></th><td>Menyimpan pilihan Terima atau Tolak.</td><td>1 tahun sejak pilihan disimpan.</td></tr>
              <tr><th scope="row"><code>organization_portal_cookie_consent</code><small>Local storage</small></th><td>Salinan pilihan consent yang dibaca lebih dahulu.</td><td>Tanpa kedaluwarsa otomatis; sampai dihapus oleh pengguna atau browser.</td></tr>
            </tbody></table>
          </div>
        @endif
      </section>
    @endforeach
    <div class="policy-help"><h2>Pertanyaan tentang privasi?</h2><p>Sampaikan permintaan melalui saluran kontak organisasi. Jangan sertakan kata sandi, PIN, atau kode OTP.</p><a href="{{ $baseUrl }}/kontak" role="button">Hubungi pengelola</a></div>
  </div>
</div></section>
@endsection
