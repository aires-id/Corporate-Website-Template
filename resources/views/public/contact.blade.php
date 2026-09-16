{{-- SPDX-License-Identifier: NCSA --}}
@extends('layouts.public')

@section('content')
  @php
    $email = trim((string) ($site['email'] ?? ''));
    $emailHref = filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : null;
    $phone = trim((string) ($site['phone'] ?? ''));
    $phoneHref = preg_replace('/[^0-9+]/', '', $phone);
    $phoneHref = preg_match('/^\+?[0-9]{7,15}$/', $phoneHref) ? $phoneHref : null;
    $address = trim((string) ($site['address'] ?? ''));
    $hasAddress = $address !== '' && strpos($address, '[ISI') === false;
    $hours = trim((string) ($site['business_hours'] ?? ''));
  @endphp
  <section class="contact-intro">
    <div class="container">
      <div class="breadcrumb"><a href="{{ $baseUrl }}/">Beranda</a><span>/</span><span>Hubungi kami</span></div>
      <div class="contact-intro-grid">
        <div><span class="eyebrow">Hubungi kami</span><h1>Percakapan baik.<br><em>Langkah baru.</em></h1></div>
        <div class="contact-intro-copy"><p>Ada pertanyaan tentang organisasi, informasi publik, atau peluang kerja sama?</p><p>Sampaikan melalui formulir di bawah. Sertakan konteks yang cukup agar pesan Anda dapat ditindaklanjuti.</p></div>
      </div>
    </div>
  </section>
  <section class="contact-workspace">
    <div class="container contact-layout">
      <div class="contact-form-panel">
        <div class="contact-section-heading"><span class="eyebrow">Tulis kepada kami</span><h2>Apa yang bisa kami bantu?</h2><p>Semua kolom wajib diisi. Gunakan email yang dapat dihubungi.</p></div>
        <form class="contact-form" method="post" action="{{ $baseUrl }}/kontak">
          <input type="hidden" name="_token" value="{{ $_SESSION['csrf_token'] ?? '' }}">
          <div class="contact-form-row">
            <label for="contact-name">Nama lengkap<input id="contact-name" name="name" autocomplete="name" maxlength="150" placeholder="Nama Anda" required value="{{ $_SESSION['old']['name'] ?? '' }}"></label>
            <label for="contact-email">Alamat email<input id="contact-email" type="email" name="email" autocomplete="email" maxlength="191" placeholder="nama@contoh.com" required value="{{ $_SESSION['old']['email'] ?? '' }}"></label>
          </div>
          <label for="contact-subject">Subjek<input id="contact-subject" name="subject" maxlength="191" placeholder="Contoh: Usulan kerja sama kegiatan" required value="{{ $_SESSION['old']['subject'] ?? '' }}"></label>
          <label for="contact-message">Pesan<textarea id="contact-message" name="message" maxlength="5000" rows="6" required placeholder="Ceritakan kebutuhan atau pertanyaan Anda…" aria-describedby="contact-message-help">{{ $_SESSION['old']['message'] ?? '' }}</textarea></label>
          <p id="contact-message-help" class="contact-field-help">Maksimal 5.000 karakter. Jangan sertakan kata sandi, PIN, OTP, atau data sensitif.</p>
          <div class="contact-submit-row"><p>Pelajari bagaimana data Anda diproses dalam <a href="{{ $baseUrl }}/privacy-policy">Kebijakan Privasi</a>.</p><button type="submit">Kirim pesan <span aria-hidden="true">&rarr;</span></button></div>
        </form>
      </div>
      <aside class="contact-sidebar" aria-label="Saluran kontak dan informasi">
        <div class="contact-direct">
          <div class="contact-direct-head"><span class="eyebrow">Saluran komunikasi</span><h2>Mari terhubung.</h2><p>{{ $site['site_name'] }}</p></div>
          <div class="contact-direct-body">
            @if($emailHref || $phoneHref || $hasAddress)
              <dl>
                @if($emailHref)<div><dt>Email</dt><dd><a href="mailto:{{ $emailHref }}">{{ $email }} <span aria-hidden="true">&nearr;</span></a></dd></div>@endif
                @if($phoneHref)<div><dt>Telepon</dt><dd><a href="tel:{{ $phoneHref }}">{{ $phone }} <span aria-hidden="true">&nearr;</span></a></dd></div>@endif
                @if($hasAddress)<div><dt>Alamat korespondensi</dt><dd>{{ $address }}</dd></div>@endif
              </dl>
            @else
              <h3>Mulai dari pesan Anda</h3><p>Gunakan formulir di halaman ini untuk mengirim pertanyaan langsung kepada pengelola organisasi.</p>
            @endif
            @if($hours !== '' && strpos($hours, '[ISI') === false)<div class="contact-hours"><span>Jam operasional</span><p>{{ $hours }}</p></div>@endif
          </div>
        </div>
        <div class="contact-useful"><h2>Temukan informasi lebih cepat</h2><a href="{{ $baseUrl }}/tentang/laporan-keuangan"><span><strong>Laporan keuangan</strong><small>Telusuri dokumen yang diterbitkan</small></span><span aria-hidden="true">&rarr;</span></a><a href="{{ $baseUrl }}/kerja-sama"><span><strong>Kerja sama</strong><small>Kenali partner dan inisiatif bersama</small></span><span aria-hidden="true">&rarr;</span></a><a href="{{ $baseUrl }}/hubungan-investor"><span><strong>Hubungan investor</strong><small>Akses informasi organisasi</small></span><span aria-hidden="true">&rarr;</span></a></div>
      </aside>
    </div>
  </section>
@endsection
