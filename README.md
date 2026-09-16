# Corporate Website Template

Website organisasi/perusahaan berbasis **Lumen 10**, **PHP 8.1**, **MySQL**, **Pico CSS lokal**, dan JavaScript vanilla. Aplikasi ini sengaja tidak memakai frontend framework berat agar ringan, mudah dipindahkan ke shared hosting, dan mudah diuji dengan USBWebserver.

Template komunitas untuk dipelajari dan disesuaikan, bukan layanan yang berafiliasi dengan bank tertentu. Konten contoh dan kebijakan hukum masih berupa template. Lakukan audit keamanan, pembaruan stack, dan peninjauan kebijakan sebelum produksi.

## Mulai cepat

Siapkan PHP dan extension di bawah, Composer 2, serta database MySQL kosong. Tidak perlu Node.js atau build frontend.

```sh
git clone https://github.com/aires-id/Corporate-Website-Template.git
cd Corporate-Website-Template
composer install
```

Salin `.env.example` ke `.env` (`Copy-Item .env.example .env` di PowerShell atau `cp .env.example .env` di Linux/macOS). Isi `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`, ubah kredensial admin seed, dan set `APP_URL=http://127.0.0.1:8000`. Buat kunci dengan perintah berikut dan salin hasilnya ke `APP_KEY`:

```sh
php -r "echo 'base64:' . base64_encode(random_bytes(32)) . PHP_EOL;"
php artisan migrate --seed
php -S 127.0.0.1:8000 -t public public/index.php
```

Buka `http://127.0.0.1:8000`; login admin melalui `/admin/login` menggunakan kredensial yang Anda tentukan. Jalankan seeder hanya pada database baru karena seeder dapat memperbarui akun dan konten contoh yang sudah ada. Server bawaan PHP hanya untuk pengembangan.

Repositori menyertakan source, aset yang dirujuk, migration, seed contoh, tes, dan `composer.lock`. Dependensi `vendor/`, `.env`, database, upload pengguna, cache, log, dan USBWebserver tidak disertakan. Direktori runtime yang dilacak berisi `.gitignore` agar tersedia setelah clone.

## Lisensi

Kode asli dan perubahan khusus Organization Portal menggunakan **University of Illinois/NCSA Open Source License** (SPDX: `NCSA`). Teks lengkap tersedia di [LICENSE](LICENSE).

Copyright (c) 2026 aires-id. Pertahankan pemberitahuan hak cipta dan lisensi saat membagikan salinan atau turunan project.

Lisensi ini mengizinkan penggunaan, modifikasi, distribusi, dan penggunaan komersial. Distribusi kode sumber harus mempertahankan pemberitahuan hak cipta, ketentuan, dan disclaimer; distribusi biner harus menyertakannya dalam dokumentasi atau materi distribusi. Nama pemegang hak cipta dan kontributor tidak boleh dipakai untuk endorsement tanpa izin tertulis. Software diberikan tanpa jaminan. NCSA tidak melarang penjualan ulang atau mewajibkan perubahan kode dibuka ke publik.

Lisensi pihak ketiga tetap berlaku untuk kode Lumen/Laravel, dependensi `vendor/`, Pico CSS, dan komponen lainnya. NCSA tidak menggantikan lisensi komponen tersebut atau otomatis mencakup logo, foto, dokumen unggahan, dan konten milik pihak lain. Pertahankan file lisensi dan pemberitahuan asli ketika mendistribusikan project. Lihat [THIRD_PARTY_NOTICES.md](THIRD_PARTY_NOTICES.md).

Referensi: [teks resmi NCSA di Open Source Initiative](https://opensource.org/license/ncsa).

### Penanda SPDX pada source

Source khusus portal ditandai `SPDX-License-Identifier: NCSA` pada komentar PHP, Blade, CSS, atau JavaScript. Penanda ini merujuk ke `LICENSE`, bukan pengganti teks lisensi dan pemberitahuan hak cipta.

- Ditandai: `app/Services/`, model selain `User.php`, controller di `Admin/`, `Public/`, dan `Auth/`, middleware `VerifyCsrfToken`, `StartSecureSession`, `SecurityHeaders`, dan `RequireAdmin`, `database/migrations/`, `routes/web.php`, `resources/views/`, `tests/HtmlSanitizerTest.php`, serta `site.css`, `corporate.css`, dan `site.js` di `public/assets/`.
- File bawaan atau campuran turunan Lumen (termasuk `User.php`, base controller, provider, bootstrap, konfigurasi, dan seeder) tidak diberi label NCSA tunggal; ketentuan upstream tetap berlaku. Lihat `THIRD_PARTY_NOTICES.md`.
- `vendor/`, Pico CSS, file hasil generate/cache, dan aset gambar tidak diubah atau diberi penanda NCSA.

Untuk file baru yang sepenuhnya merupakan source asli project, gunakan `// SPDX-License-Identifier: NCSA` setelah `<?php`, `{{-- SPDX-License-Identifier: NCSA --}}` pada Blade, atau `/* SPDX-License-Identifier: NCSA */` pada CSS/JS. Jangan menyalin penanda NCSA ke kode pihak ketiga tanpa memeriksa lisensinya.

## Fitur utama

### Template kebijakan privasi dan cookie

Konten draf ada di `config/policies.php`, dengan tampilan `resources/views/public/policy.blade.php` dan stylesheet `public/assets/css/policy.css`. Kedua halaman diberi `noindex, follow` selama masih berupa template; ini tidak membuat halaman privat.

Sebelum digunakan sebagai kebijakan resmi, lengkapi seluruh penanda kurung siku: identitas pengendali, kontak privasi, dasar pemrosesan, penyedia dan lokasi data, retensi/cadangan, prosedur hak subjek data, insiden, serta tanggal berlaku. Tinjau bersama penasihat hukum dan penanggung jawab operasional. Jangan hanya menghapus label draf tanpa memvalidasi praktik sebenarnya.

Catatan implementasi: cookie consent berlaku satu tahun tetapi salinan local storage belum memiliki expiry; hit artikel berbasis sesi berjalan terlepas dari consent iklan; penghapusan otomatis menyeluruh untuk pesan dan hit artikel belum tersedia. Inventaris pihak ketiga perlu diaudit pada produksi sebelum iklan diaktifkan. Perubahan ini hanya menyusun template, bukan menambah mekanisme consent atau retensi.

Referensi struktur (bukan salinan atau afiliasi): [Hana Bank](https://myhana.co.id/gibPT/intn/info/privacyCookies). Acuan peninjauan hukum: [UU 27/2022 pada JDIH](https://peraturan.go.id/id/uu-no-27-tahun-2022). Setelah persetujuan resmi, perbarui status/versi/tanggal pada view, konten, dan keputusan indeksasi di `SiteController::policyPage`.

- Beranda organisasi yang responsif, navbar desktop yang dapat digulir, dan side navigation mobile dengan overlay, tombol tutup, klik-area-luar, serta tombol Escape.
- Halaman tentang, struktur organisasi, laporan keuangan PDF, artikel, kerja sama, project, hubungan investor, kontak, kebijakan cookie, dan kebijakan privasi.
- Cookie consent tersimpan di `localStorage` dan cookie. Google Ads hanya dimuat jika `GOOGLE_ADS_CLIENT_ID` tersedia dan pengunjung memilih **Terima**.
- Artikel publik dengan pencarian, filter tanggal, pagination, meta SEO, canonical URL, Open Graph, JSON-LD artikel, dan pembatasan hit per sesi per hari.
- Area `/admin` dengan login, timeout sesi, pembatasan percobaan login, dashboard, artikel + editor ringan + preview, impor DOCX, laporan PDF, project, partner, struktur organisasi, akun, dan pesan kontak.
- Sanitasi HTML artikel berbasis whitelist, CSRF untuk semua mutasi, validasi server-side, password hashing, query Eloquent, PDF MIME/signature validation, nama upload acak, dan header keamanan dasar.
- `/sitemap.xml`, `/sitemap-pages.xml`, `/sitemap-articles.xml` (otomatis dipisah per 50.000 URL bila diperlukan), dan `/robots.txt` berbasis `APP_URL`.

## Kebutuhan server

- PHP 8.1.x
- MySQL 5.7+ / MariaDB yang kompatibel
- Composer 2
- PHP extension: `openssl`, `pdo_mysql`, `mbstring`, `fileinfo`, `dom`, dan `zip`
- Apache `mod_rewrite` dan `AllowOverride All`

Lumen 10 adalah target yang kompatibel dengan PHP 8.1. Karena PHP 8.1 sudah melewati masa dukungan upstream, perlakukan versi ini sebagai target kompatibilitas dari brief; untuk production, gunakan pengecualian keamanan yang disetujui organisasi atau rencanakan upgrade stack ketika kebijakan hosting memungkinkan.

## Instalasi lokal dengan USBWebserver

1. Pastikan USBWebserver memakai PHP 8.1 dan MySQL berjalan.
2. Sediakan USBWebserver sendiri (tidak disertakan dalam repositori). Pastikan path Apache/PHP sesuai folder instalasi Anda, `php.ini` mengarah ke folder extension yang benar, dan extension yang disebut di atas aktif.
3. Salin `.env.example` menjadi `.env`, lalu isi database lokal dan URL yang benar.

   ```env
   APP_NAME="[ISI NAMA]"
   APP_ENV=local
   APP_DEBUG=true
   APP_URL=http://localhost
   APP_KEY=base64:ISI_DENGAN_KUNCI_ACAK

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=nama_database_lokal
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. Buat `APP_KEY` acak satu kali, lalu masukkan hasilnya ke `.env`.

   ```powershell
   php -r "echo 'base64:' . base64_encode(random_bytes(32)) . PHP_EOL;"
   ```

5. Buat database MySQL kosong melalui phpMyAdmin, lalu jalankan:

   ```powershell
   composer install
   php artisan migrate --seed
   ```

6. Arahkan **DocumentRoot Apache ke folder `public/`** di proyek ini, bukan ke root proyek. Contoh tujuan: `C:/Sites/Corporate-Website-Template/public`.
7. Buka `APP_URL` pada browser. Deep link seperti `/artikel` dan `/sitemap.xml` harus tetap bekerja bila `mod_rewrite` aktif.

Untuk menjalankan tanpa Apache saat pemeriksaan cepat, gunakan router PHP bawaan dari root proyek:

```powershell
php -S 127.0.0.1:8091 -t public public/index.php
```

## Akun admin seed

Seeder membuat satu akun uji yang dikendalikan environment:

```env
ADMIN_SEED_EMAIL=admin@example.test
ADMIN_SEED_PASSWORD=GantiPasswordSebelumProduksi!
```

Ubah kedua nilai tersebut **sebelum** menjalankan `php artisan migrate --seed` di lingkungan selain lokal. Pada `APP_ENV=production`, seeder sengaja berhenti bila salah satu nilai default masih digunakan.

## Struktur penting

```text
app/
  Http/Controllers/     # publik, autentikasi, dan admin
  Http/Middleware/      # sesi, CSRF, header, akses admin
  Models/
  Services/             # SEO, sanitasi, DOCX, upload, rate limit
database/
  migrations/
  seeders/
public/
  assets/css/           # Pico CSS lokal + custom CSS
  assets/js/            # vanilla JavaScript
  assets/images/
  assets/logos/
  uploads/
resources/views/
storage/app/private/reports/  # PDF, di luar web root
```

Migrations adalah sumber kebenaran skema MySQL: `users`, `articles`, `article_views`, `financial_reports`, `projects`, `partners`, `contact_messages`, `settings`, dan `organization_members`.

## Mengganti informasi organisasi, warna, dan logo

Setelah menjalankan seed, buka tabel `settings` di phpMyAdmin dan ganti nilai berikut:

| Key | Kegunaan |
| --- | --- |
| `site_name`, `site_description` | nama dan deskripsi organisasi |
| `address`, `email`, `phone`, `business_hours` | detail kontak publik |
| `primary_color`, `secondary_color`, `text_color`, `footer_color` | warna hex enam digit, mis. `#2F6D78` |
| `logo_svg`, `logo_png` | path relatif di `public/`, mis. `assets/logos/logo.svg` |

Simpan file logo di `public/assets/logos/` (buat folder bila belum ada). Foto hero memakai `public/assets/images/hero-team-collaboration.jpg`; PNG juga disertakan karena dirujuk metadata Open Graph. Sesuaikan hak penggunaan aset sebelum publikasi. Tampilan merah bawaan diatur oleh `public/assets/css/corporate.css` dan menimpa sejumlah warna dasar settings; edit stylesheet tersebut untuk mengganti tema.

## Artikel, PDF, dan DOCX

- Artikel draft tidak tersedia di route publik, sitemap, atau search engine. Preview admin memakai `noindex, nofollow` dan tidak menambah jumlah klik.
- Editor hanya menyimpan tag `p`, `br`, `strong`, `em`, `ul`, `ol`, `li`, `h2`, `h3`, `blockquote`, `a`, dan `img`. Link/gambar eksternal harus URL `https://`; event handler, script, iframe, dan JavaScript URL dibuang.
- DOCX dibaca dari `word/document.xml` saat impor. Isi masuk ke editor untuk ditinjau sebelum publish; file sementara tidak disimpan sebagai aset publik. Selain `MAX_DOCX_SIZE`, atur `MAX_DOCX_XML_SIZE` dan `MAX_DOCX_COMPRESSION_RATIO` bila kebijakan organisasi membutuhkan batas lebih ketat.
- PDF diperiksa berdasarkan extension, MIME, signature `%PDF-`, dan batas `MAX_PDF_SIZE`. Berkas disimpan dengan nama acak di `storage/app/private/reports` lalu dilayani lewat route terkontrol.

## Deployment shared hosting

1. Upload semua file proyek **di luar** folder web publik hosting jika panel mengizinkannya.
2. Set document root domain/subdomain ke `public/`.
3. Jalankan `composer install --no-dev --optimize-autoloader` dari root proyek. Jika Composer tidak tersedia di hosting, jalankan di mesin dengan OS/PHP kompatibel lalu upload `vendor/` sesuai kebijakan hosting.
4. Buat `.env` production. Jangan upload atau commit `.env`.
5. Ubah minimal:

   ```env
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://domain-anda.com
   SESSION_SECURE_COOKIE=true
   DB_CONNECTION=mysql
   ADMIN_SEED_EMAIL=admin@domain-anda.com
   ADMIN_SEED_PASSWORD=buat-password-unik-yang-panjang
   GOOGLE_ADS_CLIENT_ID=
   ```

6. Beri izin tulis hanya pada `storage/` dan `bootstrap/cache/` sesuai user Apache/PHP hosting. Pastikan folder laporan private tidak bisa diakses langsung dari web.
7. Jalankan `php artisan migrate --force --seed` hanya untuk database baru. Untuk deployment berikutnya gunakan `php artisan migrate --force`.
8. Aktifkan HTTPS. File `.htaccess` di `public/` sudah menangani rewrite, cache aset statis, dan kompresi Apache bila modul tersedia.

## Google Search Console dan sitemap

1. Pastikan `APP_URL` memakai URL canonical HTTPS yang sebenarnya.
2. Buka `https://domain-anda.com/robots.txt` dan pastikan baris Sitemap menunjuk ke domain yang benar.
3. Buka `https://domain-anda.com/sitemap.xml`; response harus `application/xml` dan mengarah ke sitemap halaman serta artikel.
4. Verifikasi kepemilikan domain di Google Search Console menggunakan metode yang disetujui organisasi.
5. Kirim `https://domain-anda.com/sitemap.xml` melalui menu **Sitemaps** di Search Console.
6. Gunakan **URL Inspection** untuk memeriksa indeks halaman publik. Jangan kirim `/admin/*` atau artikel draft karena keduanya `noindex` dan tidak masuk sitemap.

## Checklist pengujian

- `php -l` untuk file PHP dan `composer validate`
- `php artisan migrate --seed` pada database lokal kosong
- form kontak: valid, email salah, pesan > 5000 karakter, CSRF kosong, dan limit
- login: password salah berulang, akun nonaktif, timeout sesi, logout
- upload: PDF valid, ekstensi palsu, MIME palsu, file terlalu besar; impor DOCX valid/tidak valid
- artikel: draft tidak terbuka publik, preview tidak menaikkan klik, refresh dalam sesi yang sama tidak menaikkan klik lebih dari sekali per hari
- `/sitemap.xml`, `/sitemap-pages.xml`, `/sitemap-articles.xml`, `/robots.txt` (termasuk `?page=2` bila index sitemap menampilkan pecahan artikel berikutnya)
- mobile 320/375/425 px, tablet, laptop, dan desktop besar; pastikan drawer dapat ditutup melalui X, overlay, dan Escape serta tidak ada horizontal scrollbar.
