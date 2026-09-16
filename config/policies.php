<?php
// SPDX-License-Identifier: NCSA

return [
    'privacy' => [
        'summary' => 'Informasi mengenai data yang diproses melalui portal, tujuan penggunaannya, serta cara menyampaikan permintaan terkait privasi.',
        'sections' => [
            ['heading' => 'Identitas pengelola dan ruang lingkup', 'paragraphs' => [
                'Kebijakan ini menjelaskan pemrosesan data pada website organisasi, termasuk halaman informasi, formulir kontak, dan area administrasi. Kebijakan ini tidak mengatur layanan perbankan, rekening, atau transaksi keuangan.',
                'Pengendali data pribadi: [ISI NAMA BADAN HUKUM]. Alamat korespondensi: [ISI ALAMAT RESMI]. Kontak privasi: [ISI EMAIL PRIVASI]. Identitas ini wajib diverifikasi oleh pengelola sebelum kebijakan diberlakukan.',
            ]],
            ['heading' => 'Kategori dan sumber data', 'paragraphs' => [
                'Formulir kontak meminta nama, alamat email, subjek, dan isi pesan yang Anda kirimkan secara langsung. Hindari mencantumkan kata sandi, PIN, kode OTP, data rekening, nomor identitas, atau informasi sensitif yang tidak diperlukan untuk pertanyaan Anda.',
                'Untuk administrator, sistem mengelola identitas akun, email, peran, status akun, kata sandi dalam bentuk hash, dan waktu login terakhir. Pengunjung publik tidak perlu membuat akun untuk membaca konten.',
                'Sistem menggunakan pengenal sesi dan pilihan cookie. Penghitungan kunjungan artikel mencatat pengenal sesi yang diolah dengan HMAC, artikel yang dibaca, dan tanggal kunjungan. Pembatasan permintaan menggunakan nilai turunan alamat IP dan, bila tersedia, email. Nilai tersebut tidak dinyatakan sebagai data anonim.',
                'Server atau penyedia hosting dapat memiliki log akses dan kesalahan. [KONFIRMASI jenis log, data perangkat yang tercatat, serta konfigurasi penyedia hosting sebelum publikasi].',
            ]],
            ['heading' => 'Tujuan dan dasar pemrosesan', 'paragraphs' => [
                'Data pesan digunakan untuk menerima, memeriksa, dan menindaklanjuti pertanyaan atau usulan kerja sama. Data akun dan sesi digunakan untuk autentikasi, pengaturan akses, validasi formulir, dan menjaga kesinambungan layanan. Catatan kunjungan digunakan untuk menghitung pembacaan artikel tanpa menghitung sesi yang sama berulang kali pada hari yang sama.',
                'Pilihan persetujuan digunakan untuk menentukan apakah pemuatan iklan opsional diizinkan. Membaca website tidak dengan sendirinya merupakan persetujuan terhadap seluruh bentuk pemrosesan data.',
                '[TETAPKAN dasar pemrosesan untuk setiap tujuan, termasuk penilaian kepentingan yang sah bila relevan, kewajiban hukum yang benar-benar berlaku, serta mekanisme persetujuan yang diperlukan]. Acuan peninjauan adalah UU Nomor 27 Tahun 2022 tentang Pelindungan Data Pribadi dan ketentuan terkait yang berlaku.',
            ]],
            ['heading' => 'Akses, penerima, dan transfer data', 'paragraphs' => [
                'Pesan yang disimpan dapat diakses melalui area administrasi oleh akun yang memiliki kewenangan sesuai konfigurasi portal. Pengelola perlu membatasi pemberian akun dan meninjau hak akses secara berkala.',
                '[ISI daftar penyedia hosting, pemeliharaan, dan penerima data lainnya; tujuan akses; lokasi pemrosesan; serta ketentuan pengamanan yang disepakati]. Jangan menyatakan data hanya berada di Indonesia sebelum lokasi server dan cadangan dikonfirmasi.',
                'Apabila layanan Google untuk iklan diaktifkan dan Anda menerima cookie opsional, browser memuat skrip pihak ketiga yang dapat memproses informasi perangkat atau jaringan. Pengelola wajib meninjau penerima data, transfer lintas negara, dan pengamanan yang diperlukan sebelum mengaktifkan layanan tersebut.',
            ]],
            ['heading' => 'Penyimpanan dan penghapusan', 'paragraphs' => [
                'Masa penyimpanan harus ditentukan berdasarkan tujuan, kebutuhan penyelesaian permintaan, dan kewajiban hukum yang berlaku. [ISI masa retensi pesan kontak, akun administrator, catatan kunjungan, log server, dan cadangan, beserta dasar penetapannya].',
                'Versi portal ini belum menerapkan penghapusan otomatis menyeluruh untuk pesan kontak dan catatan kunjungan. Pengelola perlu menetapkan penanggung jawab dan prosedur penghapusan, termasuk penanganan salinan cadangan. Jangan menganggap penutupan browser menghapus data yang telah tersimpan di server.',
            ]],
            ['heading' => 'Keamanan dan penanganan insiden', 'paragraphs' => [
                'Aplikasi memiliki autentikasi administrator, hashing kata sandi, validasi input, perlindungan CSRF, serta pembatasan permintaan tertentu. Keamanan juga bergantung pada pengaturan server, pembaruan perangkat lunak, pengelolaan akun, dan penggunaan HTTPS pada lingkungan produksi. Tidak ada sistem yang dapat menjamin keamanan mutlak.',
                '[LENGKAPI prosedur insiden, kontak pelaporan, penilaian dampak, dan mekanisme pemberitahuan kepada pihak yang berhak sesuai kewajiban hukum]. Jangan mengirim informasi rahasia melalui pesan pelaporan awal.',
            ]],
            ['heading' => 'Hak dan permintaan terkait data', 'paragraphs' => [
                'Anda dapat menghubungi pengelola untuk meminta informasi mengenai pemrosesan data, akses atau salinan data, pembaruan atau koreksi, serta pengakhiran pemrosesan atau penghapusan sesuai ketentuan yang berlaku. Penarikan persetujuan, pembatasan pemrosesan, portabilitas, dan keberatan atas keputusan otomatis berlaku dengan syarat serta pengecualian yang ditetapkan hukum.',
                'Sampaikan jenis permintaan dan informasi yang cukup untuk menemukan catatan terkait melalui halaman kontak dengan subjek “Permintaan Privasi”. Pengelola dapat meminta verifikasi yang proporsional; jangan mengirim dokumen identitas sebelum memperoleh petunjuk melalui saluran yang aman.',
                '[TETAPKAN prosedur verifikasi, pencatatan permintaan, tenggat sesuai hukum, dan jalur eskalasi pengaduan]. Permintaan tertentu dapat dibatasi oleh kewajiban penyimpanan atau hak pihak lain; alasan penanganannya perlu dijelaskan kepada pemohon.',
            ]],
            ['heading' => 'Anak dan informasi pihak lain', 'paragraphs' => [
                'Portal ini bukan layanan yang secara khusus ditujukan kepada anak. Jika kegiatan organisasi melibatkan data anak, pengelola wajib menyiapkan mekanisme pemrosesan dan persetujuan orang tua atau wali yang sesuai sebelum pengumpulan dilakukan.',
                'Jangan mengirimkan data pribadi orang lain tanpa kewenangan yang sesuai. Hubungi pengelola apabila Anda mengetahui adanya informasi yang terkirim secara keliru.',
            ]],
            ['heading' => 'Tautan eksternal dan perubahan kebijakan', 'paragraphs' => [
                'Website pihak ketiga yang ditautkan memiliki kebijakan sendiri. Periksa kebijakannya sebelum memberikan data. Tautan tidak berarti pengelola mengendalikan seluruh praktik pihak ketiga tersebut.',
                'Perubahan material atas tujuan, penerima, atau cara pemrosesan perlu diinformasikan melalui kebijakan yang diperbarui dan, jika diperlukan, persetujuan baru. [ISI tanggal berlaku, penanggung jawab persetujuan, dan arsip versi kebijakan].',
            ]],
        ],
    ],
    'cookie' => [
        'summary' => 'Penjelasan penyimpanan pada browser, teknologi sesi, pilihan iklan opsional, dan cara mengelola preferensi Anda.',
        'sections' => [
            ['heading' => 'Tentang cookie dan penyimpanan browser', 'paragraphs' => [
                'Cookie adalah informasi kecil yang disimpan browser dan dikirim kembali ke website sesuai cakupannya. Local storage menyimpan informasi pada browser untuk asal website yang sama dan tidak otomatis dikirim dalam setiap permintaan.',
                'Portal menggunakan keduanya untuk sesi dan pencatatan pilihan cookie. Kebijakan ini juga menjelaskan layanan pihak ketiga yang dapat dimuat setelah Anda memberikan persetujuan.',
            ]],
            ['heading' => 'Penyimpanan yang digunakan portal', 'paragraphs' => [
                'Inventaris berikut mengikuti kode portal saat template disusun. Pengelola perlu memeriksa ulang pada domain produksi, termasuk cookie yang mungkin ditambahkan oleh hosting, proksi, atau layanan eksternal.',
            ], 'inventory' => true],
            ['heading' => 'Cookie esensial dan pencatatan kunjungan', 'paragraphs' => [
                'Sesi mendukung autentikasi administrator, token keamanan formulir, dan pesan hasil pengiriman. Memblokir cookie sesi dapat mengganggu login atau pengiriman formulir.',
                'Pengenal sesi juga digunakan untuk membuat nilai HMAC pada catatan kunjungan artikel. Pencatatan ini berlangsung pada server dan tidak bergantung pada pilihan cookie iklan. Kebutuhan mekanisme persetujuan untuk tujuan statistik ini perlu ditinjau oleh pengelola; menolak iklan tidak menghentikannya pada implementasi sekarang.',
            ]],
            ['heading' => 'Layanan iklan pihak ketiga', 'paragraphs' => [
                'Skrip iklan Google hanya dimuat ketika konfigurasi penerbit tersedia dan pilihan tersimpan adalah “Terima”. Tanpa kedua kondisi tersebut, portal tidak memuat skrip iklan itu melalui mekanisme consent yang tersedia.',
                'Nama cookie, tujuan rinci, penerima, serta durasi layanan pihak ketiga bergantung pada konfigurasi dan perilaku penyedia. [LENGKAPI inventaris produksi sebelum iklan diaktifkan]. Template ini tidak menyatakan inventaris cookie pihak ketiga sudah lengkap atau platform consent sudah tersertifikasi.',
            ]],
            ['heading' => 'Mengelola dan menarik pilihan', 'paragraphs' => [
                'Pada banner, pilih “Terima” untuk mengizinkan pemuatan iklan opsional atau “Tolak” untuk menolaknya. Pilihan ini tidak menghalangi akses membaca konten publik.',
                'Untuk mengubah pilihan pada versi portal ini, hapus cookie dan local storage untuk website ini melalui pengaturan data situs di browser, lalu muat ulang halaman. Keduanya perlu dihapus karena pilihan dibaca dari local storage terlebih dahulu. Setelah banner muncul kembali, tentukan pilihan baru.',
                'Menghapus cookie saja belum tentu menghapus pilihan yang ada di local storage. Penghapusan data situs juga dapat mengakhiri sesi login. Cookie pihak ketiga perlu dikelola secara terpisah melalui browser atau pengaturan penyedianya; penarikan pilihan tidak membatalkan pemrosesan yang telah terjadi.',
            ]],
            ['heading' => 'Masa simpan dan perangkat berbeda', 'paragraphs' => [
                'Cookie preferensi diberi masa simpan satu tahun saat pilihan dibuat. Salinan pilihan pada local storage belum memiliki kedaluwarsa otomatis dan dapat tetap tersimpan setelah cookie berakhir, hingga dihapus oleh Anda atau browser. Pengelola perlu menyelaraskan masa simpan keduanya sebelum produksi bila menetapkan batas consent tertentu.',
                'Pilihan berlaku pada browser dan asal website tempat pilihan disimpan, bukan otomatis untuk seluruh perangkat. Masa cookie sesi mengikuti sesi browser; fitur pemulihan sesi pada browser dapat memengaruhi kapan cookie dibuang.',
            ]],
            ['heading' => 'Pembaruan dan kontak', 'paragraphs' => [
                'Pengelola perlu memperbarui inventaris jika menambahkan analitik, iklan, video tertanam, atau teknologi penyimpanan lain. [ISI tanggal berlaku dan proses peninjauan berkala].',
                'Untuk pertanyaan mengenai cookie atau privasi, gunakan halaman kontak dengan subjek “Privasi dan Cookie”. Informasi mengenai data yang disimpan pada server dijelaskan dalam Kebijakan Privasi.',
            ]],
        ],
    ],
];
