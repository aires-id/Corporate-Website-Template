{{-- SPDX-License-Identifier: NCSA --}}
<footer class="site-footer corporate-footer">
  <div class="footer-resources">
    <div class="container footer-resources-inner">
      <div class="footer-resource-intro"><span class="footer-kicker">Pusat informasi</span><h2>Kenali kami lebih jauh.</h2></div>
      <a href="{{ $baseUrl }}/tentang/laporan-keuangan"><span><small>Transparansi</small><strong>Laporan keuangan</strong></span><span aria-hidden="true">&nearr;</span></a>
      <a href="{{ $baseUrl }}/hubungan-investor"><span><small>Informasi korporasi</small><strong>Hubungan investor</strong></span><span aria-hidden="true">&nearr;</span></a>
      <a href="{{ $baseUrl }}/kerja-sama"><span><small>Tumbuh bersama</small><strong>Jelajahi kerja sama</strong></span><span aria-hidden="true">&nearr;</span></a>
    </div>
  </div>
  <div class="container footer-directory">
    <div class="footer-identity">
      <a class="footer-brand" href="{{ $baseUrl }}/">
        @if($logoUrl)
          <img src="{{ $logoUrl }}" alt="{{ $site['site_name'] }}" width="180" height="52" loading="lazy">
        @else
          <span class="brand-mark" aria-hidden="true">O</span><span>{{ $site['site_name'] }}</span>
        @endif
      </a>
      <p>Kenali organisasi, ikuti kegiatan terbaru, dan temukan peluang untuk berkolaborasi bersama kami.</p>
      <a class="footer-contact-link" href="{{ $baseUrl }}/kontak">Mari terhubung <span aria-hidden="true">&rarr;</span></a>
    </div>
    <div class="footer-column">
      <h2>Organisasi</h2>
      <ul><li><a href="{{ $baseUrl }}/tentang/organisasi">Tentang kami</a></li><li><a href="{{ $baseUrl }}/tentang/struktur">Struktur organisasi</a></li><li><a href="{{ $baseUrl }}/hubungan-investor">Hubungan investor</a></li><li><a href="{{ $baseUrl }}/tentang/laporan-keuangan">Laporan keuangan</a></li></ul>
    </div>
    <div class="footer-column">
      <h2>Aktivitas &amp; kolaborasi</h2>
      <ul><li><a href="{{ $baseUrl }}/artikel">Artikel &amp; kabar terbaru</a></li><li><a href="{{ $baseUrl }}/project">Project &amp; kegiatan</a></li><li><a href="{{ $baseUrl }}/kerja-sama">Kerja sama</a></li><li><a href="{{ $baseUrl }}/kontak">Sampaikan pertanyaan</a></li></ul>
    </div>
    <div class="footer-column footer-reach">
      <h2>Hubungi kami</h2>
      <address>
        @if(!empty($site['address']) && strpos($site['address'], '[ISI') === false)
          <span class="footer-contact-label">Alamat</span><p>{{ $site['address'] }}</p>
        @endif
        @if($emailHref)
          <span class="footer-contact-label">Email</span><a href="mailto:{{ $emailHref }}">{{ $email }}</a>
        @endif
        @if($phoneHref)
          <span class="footer-contact-label">Telepon</span><a href="tel:{{ $phoneHref }}">{{ $phone }}</a>
        @endif
        @if(!$emailHref && !$phoneHref)
          <p>Pertanyaan umum, informasi organisasi, atau usulan kerja sama? Kirim pesan melalui halaman kontak.</p>
        @endif
      </address>
      <a class="footer-message" href="{{ $baseUrl }}/kontak">Kirim pesan <span aria-hidden="true">&nearr;</span></a>
    </div>
  </div>
  <div class="container footer-legal">
    <p>&copy; {{ date('Y') }} {{ $site['site_name'] }}.</p>
    <ul aria-label="Kebijakan website"><li><a href="{{ $baseUrl }}/privacy-policy">Kebijakan privasi</a></li><li><a href="{{ $baseUrl }}/cookie-policy">Kebijakan cookie</a></li></ul>
    <a class="footer-to-top" href="#page-top">Kembali ke atas <span aria-hidden="true">&uarr;</span></a>
  </div>
</footer>
