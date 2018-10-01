<?php
/**
 * This file is a part of the CIDRAM package.
 * Homepage: https://cidram.github.io/
 *
 * CIDRAM COPYRIGHT 2016 and beyond by Caleb Mazalevskis (Maikuolan).
 *
 * License: GNU/GPLv2
 * @see LICENSE.txt
 *
 * This file: Indonesian language data for the front-end (last modified: 2018.09.30).
 */

/** Prevents execution from outside of CIDRAM. */
if (!defined('CIDRAM')) {
    die('[CIDRAM] This should not be accessed directly.');
}

foreach (['IPv4', 'IPv6'] as $CIDRAM['IPvX']) {
    $CIDRAM['Pre'] = 'Tanda tangan ' . $CIDRAM['IPvX'] . ' standar biasanya disertakan dengan paket utama. ';
    $CIDRAM['lang']['Extended Description: ' . $CIDRAM['IPvX']] = $CIDRAM['Pre'] . 'Memblokir layanan cloud tidak diinginkan dan jalur akses non-manusia.';
    $CIDRAM['lang']['Extended Description: ' . $CIDRAM['IPvX'] . '-Bogons'] = $CIDRAM['Pre'] . 'Memblokir CIDR bogon/martian.';
    $CIDRAM['lang']['Extended Description: ' . $CIDRAM['IPvX'] . '-ISPs'] = $CIDRAM['Pre'] . 'Memblokir ISP berbahaya dan spam rawan.';
    $CIDRAM['lang']['Extended Description: ' . $CIDRAM['IPvX'] . '-Other'] = $CIDRAM['Pre'] . 'Memblokir CIDR untuk proxy, VPN, dan layanan lain-lain tidak diinginkan.';
    $CIDRAM['Pre'] = 'File tanda tangan ' . $CIDRAM['IPvX'] . ' (%s).';
    $CIDRAM['lang']['Name: ' . $CIDRAM['IPvX']] = sprintf($CIDRAM['Pre'], 'layanan cloud tidak diinginkan dan jalur akses non-manusia');
    $CIDRAM['lang']['Name: ' . $CIDRAM['IPvX'] . '-Bogons'] = sprintf($CIDRAM['Pre'], 'CIDR bogon/martian');
    $CIDRAM['lang']['Name: ' . $CIDRAM['IPvX'] . '-ISPs'] = sprintf($CIDRAM['Pre'], 'ISP berbahaya dan spam rawan');
    $CIDRAM['lang']['Name: ' . $CIDRAM['IPvX'] . '-Other'] = sprintf($CIDRAM['Pre'], 'CIDR untuk proxy, VPN, dan layanan lain-lain tidak diinginkan');
}
unset($CIDRAM['Pre'], $CIDRAM['IPvX']);

$CIDRAM['lang']['Extended Description: Bypasses'] = 'File untuk bypass tanda tangan standar yang biasanya disertakan dengan paket utama.';
$CIDRAM['lang']['Extended Description: CIDRAM'] = 'Paket utama (tanpa tanda tangan, dokumentasi, konfigurasi).';
$CIDRAM['lang']['Extended Description: Chart.js'] = 'Memungkinkan bagian depan untuk menghasilkan bagan pai.<br /><a href="https://github.com/chartjs/Chart.js">Chart.js</a> tersedia melalui <a href="https://opensource.org/licenses/MIT">MIT license</a>.';
$CIDRAM['lang']['Extended Description: PHPMailer'] = 'Diperlukan untuk menggunakan fungsionalitas apapun yang melibatkan pengiriman email.<br /><a href="https://github.com/PHPMailer/PHPMailer">PHPMailer</a> tersedia melalui lisensi <a href="https://github.com/PHPMailer/PHPMailer/blob/master/LICENSE">LGPLv2.1</a>.';
$CIDRAM['lang']['Extended Description: module_badhosts.php'] = 'Memblokir host yang sering digunakan oleh spammer, peretas, dan entitas jahat lainnya.';
$CIDRAM['lang']['Extended Description: module_badhosts_isps.php'] = 'Memblokir host yang milik ISP, yang sering digunakan oleh spammer, peretas, dan entitas jahat lainnya.';
$CIDRAM['lang']['Extended Description: module_badtlds.php'] = 'Memblokir host yang milik TLD, yang sering digunakan oleh spammer, peretas, dan entitas jahat lainnya.';
$CIDRAM['lang']['Extended Description: module_botua.php'] = 'Memblokir agen pengguna yang terkait dengan bot yang tidak diinginkan dan aktivitas jahat.';
$CIDRAM['lang']['Extended Description: module_cookies.php'] = 'Menyediakan beberapa perlindungan terbatas terhadap cookie berbahaya.';
$CIDRAM['lang']['Extended Description: module_extras.php'] = 'Menyediakan beberapa perlindungan terbatas terhadap berbagai vektor serangan umum digunakan dalam permintaan.';
$CIDRAM['lang']['Extended Description: module_sfs.php'] = 'Melindungi halaman pendaftaran dan login terhadap IP yang terdaftar oleh SFS.';
$CIDRAM['lang']['Name: Bypasses'] = 'Bypass tanda tangan standar.';
$CIDRAM['lang']['Name: compat_bunnycdn.php'] = 'Modul kompatibilitas BunnyCDN';
$CIDRAM['lang']['Name: module_badhosts.php'] = 'Modul pemblokir untuk host buruk';
$CIDRAM['lang']['Name: module_badhosts_isps.php'] = 'Modul pemblokir untuk host buruk (ISP)';
$CIDRAM['lang']['Name: module_badtlds.php'] = 'Modul pemblokir TLD buruk';
$CIDRAM['lang']['Name: module_baidublocker.php'] = 'Modul pemblokir untuk Baidu';
$CIDRAM['lang']['Name: module_botua.php'] = 'Modul agen pengguna opsional';
$CIDRAM['lang']['Name: module_cookies.php'] = 'Modul scanner cookie opsional';
$CIDRAM['lang']['Name: module_extras.php'] = 'Modul tambahan keamanan opsional';
$CIDRAM['lang']['Name: module_sfs.php'] = 'Modul Stop Forum Spam';
$CIDRAM['lang']['Name: module_ua.php'] = 'Modul pemblokir UA kosong';
$CIDRAM['lang']['Name: module_yandexblocker.php'] = 'Modul pemblokir untuk Yandex';
$CIDRAM['lang']['bNav_home_logout'] = '<a href="?">Halaman Utama</a> | <a href="?cidram-page=logout">Keluar</a>';
$CIDRAM['lang']['bNav_logout'] = '<a href="?cidram-page=logout">Keluar</a>';
$CIDRAM['lang']['config_PHPMailer_Enable2FA'] = 'Direktif ini menentukan apakah akan menggunakan 2FA untuk akun depan.';
$CIDRAM['lang']['config_PHPMailer_EventLog'] = 'File untuk mencatat semua kejadian yang terkait dengan PHPMailer. Spesifikasikan nama file, atau biarkan kosong untuk menonaktifkan.';
$CIDRAM['lang']['config_PHPMailer_Host'] = 'Host SMTP yang digunakan untuk email keluar.';
$CIDRAM['lang']['config_PHPMailer_Password'] = 'Kata sandi yang digunakan saat mengirim email melalui SMTP.';
$CIDRAM['lang']['config_PHPMailer_Port'] = 'Nomor port yang digunakan untuk email keluar. Default = 587.';
$CIDRAM['lang']['config_PHPMailer_SMTPAuth'] = 'Direktif ini menentukan apakah akan mengotentikasi sesi SMTP (biasanya harus dibiarkan sendiri).';
$CIDRAM['lang']['config_PHPMailer_SMTPSecure'] = 'Protokol yang digunakan saat mengirim email melalui SMTP (TLS atau SSL).';
$CIDRAM['lang']['config_PHPMailer_SkipAuthProcess'] = 'Pengaturan direktif ini ke `true` menginstruksikan PHPMailer untuk melewati proses otentikasi normal yang biasanya terjadi ketika mengirim email melalui SMTP. Ini harus dihindari, karena melewatkan proses ini dapat mengekspos email keluar ke serangan MITM, tetapi mungkin diperlukan dalam kasus dimana proses ini mencegah PHPMailer menghubungkan ke server SMTP.';
$CIDRAM['lang']['config_PHPMailer_Username'] = 'Nama pengguna yang digunakan saat mengirim email melalui SMTP.';
$CIDRAM['lang']['config_PHPMailer_addReplyToAddress'] = 'Alamat balasan yang dikutip saat mengirim email melalui SMTP.';
$CIDRAM['lang']['config_PHPMailer_addReplyToName'] = 'Nama balasan yang dikutip saat mengirim email melalui SMTP.';
$CIDRAM['lang']['config_PHPMailer_setFromAddress'] = 'Alamat pengirim yang dikutip saat mengirim email melalui SMTP.';
$CIDRAM['lang']['config_PHPMailer_setFromName'] = 'Nama pengirim yang dikutip saat mengirim email melalui SMTP.';
$CIDRAM['lang']['config_experimental'] = 'Tidak stabil / Eksperimental!';
$CIDRAM['lang']['config_general_FrontEndLog'] = 'File untuk mencatat upaya masuk bagian depan. Spesifikasikan nama file, atau biarkan kosong untuk menonaktifkan.';
$CIDRAM['lang']['config_general_allow_gethostbyaddr_lookup'] = 'Izinkan menggunakan gethostbyaddr saat UDP tidak tersedia? True = Ya [Default]; False = Tidak.';
$CIDRAM['lang']['config_general_ban_override'] = 'Mengesampingkan "forbid_on_block" ketika "infraction_limit" adalah melampaui? Ketika mengesampingkan: Permintaan diblokir menghasilkan halaman kosong (file template tidak digunakan). 200 = Jangan mengesampingkan [Default]. Nilai lainnya sama dengan nilai yang tersedia untuk "forbid_on_block".';
$CIDRAM['lang']['config_general_default_algo'] = 'Mendefinisikan algoritma mana yang akan digunakan untuk semua password dan sesi di masa depan. Opsi: PASSWORD_DEFAULT (default), PASSWORD_BCRYPT, PASSWORD_ARGON2I (membutuhkan PHP &gt;= 7.2.0).';
$CIDRAM['lang']['config_general_default_dns'] = 'Sebuah daftar dipisahkan dengan koma dari server DNS yang digunakan untuk pencarian nama host. Default = "8.8.8.8,8.8.4.4" (Google DNS). PERINGATAN: Jangan ganti ini kecuali Anda tahu apa yang Anda lakukan!';
$CIDRAM['lang']['config_general_disable_cli'] = 'Menonaktifkan modus CLI? Modus CLI diaktifkan secara default, tapi kadang-kadang dapat mengganggu alat pengujian tertentu (seperti PHPUnit, sebagai contoh) dan aplikasi CLI berbasis lainnya. Jika Anda tidak perlu menonaktifkan modus CLI, Anda harus mengabaikan direktif ini. False = Mengaktifkan modus CLI [Default]; True = Menonaktifkan modus CLI.';
$CIDRAM['lang']['config_general_disable_frontend'] = 'Menonaktifkan akses bagian depan? Akses bagian depan dapat membuat CIDRAM lebih mudah dikelola, tapi juga dapat menjadi potensial resiko keamanan. Itu direkomendasi untuk mengelola CIDRAM melalui bagian belakang bila mungkin, tapi akses bagian depan yang disediakan untuk saat itu tidak mungkin. Memilikinya dinonaktifkan kecuali jika Anda membutuhkannya. False = Mengaktifkan akses bagian depan; True = Menonaktifkan akses bagian depan [Default].';
$CIDRAM['lang']['config_general_disable_webfonts'] = 'Menonaktifkan webfonts? True = Ya [Default]; False = Tidak.';
$CIDRAM['lang']['config_general_emailaddr'] = 'Jika Anda ingin, Anda dapat menyediakan alamat email sini untuk diberikan kepada pengguna ketika diblokir, bagi mereka untuk menggunakan sebagai metode kontak untuk dukungan dan/atau bantuan untuk dalam hal mereka menjadi diblokir keliru atau diblokir oleh kesalahan. PERINGATAN: Apapun alamat email Anda menyediakan sini akan pasti diperoleh oleh spambots dan pencakar/scrapers ketika digunakan disini, dan karena itu, jika Anda ingin memberikan alamat email disini, itu sangat direkomendasikan Anda memastikan bahwa alamat email yang Anda berikan disini adalah alamat yang dapat dibuang dan/atau adalah alamat Anda tidak keberatan menjadi di-spam (dengan kata lain, Anda mungkin tidak ingin untuk menggunakan Anda alamat email yang personal primer atau bisnis primer).';
$CIDRAM['lang']['config_general_emailaddr_display_style'] = 'Bagaimana Anda lebih suka alamat email yang akan disajikan kepada pengguna?';
$CIDRAM['lang']['config_general_empty_fields'] = 'Bagaimana seharusnya CIDRAM menangani bidang kosong saat membuat log dan menampilkan informasi kejadian blokir? "include" = Sertakan bidang kosong. "omit" = Hilangkan bidang kosong [default].';
$CIDRAM['lang']['config_general_forbid_on_block'] = 'Pesan status HTTP mana yang harus dikirim oleh CIDRAM ketika memblokir permintaan? (Lihat dokumentasi untuk informasi lebih lanjut).';
$CIDRAM['lang']['config_general_force_hostname_lookup'] = 'Memaksa periksa untuk nama host? True = Ya; False = Tidak [Default]. Periksa untuk nama host biasanya dilakukan pada dasar "sesuai kebutuhan", tapi bisa dipaksakan untuk semua permintaan. Melakukan hal tersebut mungkin berguna sebagai sarana untuk memberikan informasi lebih rinci di log, tapi mungkin juga memiliki sedikit efek negatif pada kinerja.';
$CIDRAM['lang']['config_general_hide_version'] = 'Sembunyikan informasi versi dari log dan output halaman? True = Ya; False = Tidak [Default].';
$CIDRAM['lang']['config_general_ipaddr'] = 'Dimana menemukan alamat IP dari permintaan alamat? (Bergunak untuk pelayanan-pelayanan seperti Cloudflare dan sejenisnya). Default = REMOTE_ADDR. PERINGATAN: Jangan ganti ini kecuali Anda tahu apa yang Anda lakukan!';
$CIDRAM['lang']['config_general_lang'] = 'Tentukan bahasa default untuk CIDRAM.';
$CIDRAM['lang']['config_general_log_banned_ips'] = 'Termasuk permintaan diblokir dari IP dilarang dalam file log? True = Ya [Default]; False = Tidak.';
$CIDRAM['lang']['config_general_log_rotation_action'] = 'Rotasi log membatasi jumlah file log yang seharusnya ada pada satu waktu. Ketika file log baru dibuat, jika jumlah total file log melebihi batas yang ditentukan, tindakan yang ditentukan akan dilakukan. Anda dapat menentukan tindakan yang diinginkan disini. Delete = Hapus file log tertua, hingga batasnya tidak lagi terlampaui. Archive = Pertama arsipkan, lalu hapus file log tertua, hingga batasnya tidak lagi terlampaui.';
$CIDRAM['lang']['config_general_log_rotation_limit'] = 'Rotasi log membatasi jumlah file log yang seharusnya ada pada satu waktu. Ketika file log baru dibuat, jika jumlah total file log melebihi batas yang ditentukan, tindakan yang ditentukan akan dilakukan. Anda dapat menentukan batas yang diinginkan disini. Nilai 0 akan menonaktifkan rotasi log.';
$CIDRAM['lang']['config_general_logfile'] = 'File yang dapat dibaca oleh manusia untuk mencatat semua upaya akses diblokir. Spesifikasikan nama file, atau biarkan kosong untuk menonaktifkan.';
$CIDRAM['lang']['config_general_logfileApache'] = 'File yang dalam gaya Apache untuk mencatat semua upaya akses diblokir. Spesifikasikan nama file, atau biarkan kosong untuk menonaktifkan.';
$CIDRAM['lang']['config_general_logfileSerialized'] = 'File serial untuk mencatat semua upaya akses diblokir. Spesifikasikan nama file, atau biarkan kosong untuk menonaktifkan.';
$CIDRAM['lang']['config_general_maintenance_mode'] = 'Aktifkan modus perawatan? True = Ya; False = Tidak [Default]. Nonaktifkan semuanya selain bagian depan. Terkadang berguna saat memperbarui CMS, kerangka kerja, dll.';
$CIDRAM['lang']['config_general_max_login_attempts'] = 'Jumlah maksimum upaya untuk memasukkan.';
$CIDRAM['lang']['config_general_numbers'] = 'Cara apa yang kamu suka nomor menjadi ditampilkan? Pilih contoh yang paling sesuai untuk Anda.';
$CIDRAM['lang']['config_general_protect_frontend'] = 'Menentukan apakah perlindungan biasanya disediakan oleh CIDRAM harus diterapkan pada bagian depan. True = Ya [Default]; False = Tidak.';
$CIDRAM['lang']['config_general_search_engine_verification'] = 'Mencoba memverifikasi permintaan dari mesin pencari? Verifikasi mesin pencari memastikan bahwa mereka tidak akan dilarang sebagai akibat dari melebihi batas pelanggaran (melarang mesin pencari dari situs web Anda biasanya akan memiliki efek negatif pada peringkat mesin pencari Anda, SEO, dll). Ketika diverifikasi, mesin pencari dapat diblokir seperti biasa, tapi tidak akan dilarang. Ketika tidak diverifikasi, itu mungkin bagi mereka untuk dilarang sebagai akibat dari melebihi batas pelanggaran. Juga, verifikasi mesin pencari memberikan proteksi terhadap permintaan mesin pencari palsu dan terhadap entitas yang berpotensi berbahaya yang menyamar sebagai mesin pencari (permintaan tersebut akan diblokir ketika verifikasi mesin pencari diaktifkan). True = Mengaktifkan verifikasi mesin pencari [Default]; False = Menonaktifkan verifikasi mesin pencari.';
$CIDRAM['lang']['config_general_silent_mode'] = 'Seharusnya CIDRAM diam-diam mengarahkan diblokir upaya akses bukannya menampilkan halaman "Akses Ditolak"? Jika ya, menentukan lokasi untuk mengarahkan diblokir upaya akses. Jika tidak, kosongkan variabel ini.';
$CIDRAM['lang']['config_general_social_media_verification'] = 'Mencoba memverifikasi permintaan media sosial? Verifikasi media sosial memberikan perlindungan terhadap permintaan media sosial palsu (permintaan semacam ini akan diblokir). True = Mengaktifkan verifikasi media sosial [Default]; False = Mengaktifkan verifikasi media sosial.';
$CIDRAM['lang']['config_general_statistics'] = 'Lacak statistik penggunaan CIDRAM? True = Ya; False = Tidak [Default].';
$CIDRAM['lang']['config_general_timeFormat'] = 'Format notasi tanggal/waktu yang digunakan oleh CIDRAM. Opsi tambahan dapat ditambahkan atas permintaan.';
$CIDRAM['lang']['config_general_timeOffset'] = 'Offset zona waktu dalam hitungan menit.';
$CIDRAM['lang']['config_general_timezone'] = 'Zona waktu Anda.';
$CIDRAM['lang']['config_general_truncate'] = 'Memotong file log ketika mereka mencapai ukuran tertentu? Nilai adalah ukuran maksimum dalam B/KB/MB/GB/TB yang bisa ditambahkan untuk file log sebelum dipotong. Nilai default 0KB menonaktifkan pemotongan (file log dapat tumbuh tanpa batas waktu). Catat: Berlaku untuk file log individu! Ukuran file log tidak dianggap secara kolektif.';
$CIDRAM['lang']['config_legal_omit_hostname'] = 'Jangan memasukkan nama host di log? True = Ya; False = Tidak [Default].';
$CIDRAM['lang']['config_legal_omit_ip'] = 'Jangan memasukkan alamat IP di log? True = Ya; False = Tidak [Default]. Catat: "pseudonymise_ip_addresses" menjadi tidak perlu ketika "omit_ip" adalah "true".';
$CIDRAM['lang']['config_legal_omit_ua'] = 'Jangan memasukkan agen pengguna di log? True = Ya; False = Tidak [Default].';
$CIDRAM['lang']['config_legal_privacy_policy'] = 'Alamat dari kebijakan privasi yang relevan untuk ditampilkan di footer dari setiap halaman yang dihasilkan. Spesifikasikan URL, atau biarkan kosong untuk menonaktifkan.';
$CIDRAM['lang']['config_legal_pseudonymise_ip_addresses'] = 'Pseudonymise alamat IP saat menulis file log? True = Ya; False = Tidak [Default].';
$CIDRAM['lang']['config_recaptcha_api'] = 'API mana yang akan digunakan? V2 atau Invisible?';
$CIDRAM['lang']['config_recaptcha_expiry'] = 'Jumlah jam untuk mengingat instansi reCAPTCHA.';
$CIDRAM['lang']['config_recaptcha_lockip'] = 'Kunci reCAPTCHA ke IP?';
$CIDRAM['lang']['config_recaptcha_lockuser'] = 'Kunci reCAPTCHA ke pengguna?';
$CIDRAM['lang']['config_recaptcha_logfile'] = 'Mencatat hasil semua instansi reCAPTCHA? Jika ya, tentukan nama untuk menggunakan untuk file catatan. Jika tidak, variabel ini harus kosong.';
$CIDRAM['lang']['config_recaptcha_secret'] = 'Nilai ini harus sesuai dengan "secret key" untuk reCAPTCHA Anda, yang dapat ditemukan dalam dashboard reCAPTCHA.';
$CIDRAM['lang']['config_recaptcha_signature_limit'] = 'Jumlah maksimum tanda tangan diizinkan untuk dipicu saat instansi reCAPTCHA harus ditawarkan. Default = 1. Jika nomor ini terlampaui untuk permintaan tertentu, instansi reCAPTCHA tidak akan ditawarkan.';
$CIDRAM['lang']['config_recaptcha_sitekey'] = 'Nilai ini harus sesuai dengan "site key" untuk reCAPTCHA Anda, yang dapat ditemukan dalam dashboard reCAPTCHA.';
$CIDRAM['lang']['config_recaptcha_usemode'] = 'Mendefinisikan bagaimana CIDRAM harus menggunakan reCAPTCHA.';
$CIDRAM['lang']['config_signatures_block_bogons'] = 'Memblokir CIDR bogon/martian? Jika Anda mengharapkan koneksi ke website Anda dari dalam jaringan lokal Anda, dari localhost, atau dari LAN Anda, direktif ini harus didefinisikan untuk false/palsu. Jika Anda tidak mengharapkan ini, direktif ini harus didefinisikan untuk true/benar.';
$CIDRAM['lang']['config_signatures_block_cloud'] = 'Memblokir CIDR yang diidentifikasi sebagai milik webhosting dan/atau layanan cloud? Jika Anda mengoperasikan layanan API dari website Anda atau jika Anda mengharapkan website lain untuk menghubungkan ke website Anda, direktif ini harus didefinisikan untuk false/palsu. Jika Anda tidak, maka, direktif ini harus didefinisikan untuk true/benar.';
$CIDRAM['lang']['config_signatures_block_generic'] = 'Memblokir CIDR umumnya direkomendasikan untuk mendaftar hitam? Ini mencakup tanda tangan apapun yang tidak ditandai sebagai bagian dari apapun lainnya kategori tanda tangan lebih spesifik.';
$CIDRAM['lang']['config_signatures_block_legal'] = 'Memblokir CIDR sebagai respons terhadap kewajiban hukum? Direktif ini seharusnya tidak memiliki efek apapun, karena CIDRAM tidak menghubungkan CIDR apapun dengan "kewajiban hukum" secara default, tetapi tetap ada sebagai ukuran kontrol tambahan untuk kepentingan file tanda tangan atau modul dipersonalisasi yang mungkin ada karena alasan hukum.';
$CIDRAM['lang']['config_signatures_block_malware'] = 'Memblokir IP yang terkait dengan malware? Ini termasuk server C&C, mesin yang terinfeksi, mesin yang terlibat dalam distribusi malware, dll.';
$CIDRAM['lang']['config_signatures_block_proxies'] = 'Memblokir CIDR yang diidentifikasi sebagai milik layanan proxy atau VPN? Jika Anda membutuhkan bahwa pengguna dapat mengakses situs web Anda dari layanan proxy atau VPN, direktif ini harus didefinisikan untuk false/palsu. Jika Anda tidak membutuhkannya, direktif ini harus didefinisikan untuk true/benar sebagai sarana untuk meningkatkan keamanan.';
$CIDRAM['lang']['config_signatures_block_spam'] = 'Memblokir CIDR yang diidentifikasi sebagai beresiko tinggi karena spam? Kecuali jika Anda mengalami masalah ketika melakukan itu, umumnya, ini harus selalu didefinisikan untuk true/benar.';
$CIDRAM['lang']['config_signatures_default_tracktime'] = 'Berapa banyak detik untuk melacak IP dilarang oleh modul. Default = 604800 (1 seminggu).';
$CIDRAM['lang']['config_signatures_infraction_limit'] = 'Jumlah maksimum pelanggaran IP diperbolehkan untuk dikenakan sebelum dilarang oleh pelacakan IP. Default = 10.';
$CIDRAM['lang']['config_signatures_ipv4'] = 'Daftar file tanda tangan IPv4 yang CIDRAM harus berusaha untuk menggunakan, dipisahkan dengan koma.';
$CIDRAM['lang']['config_signatures_ipv6'] = 'Daftar file tanda tangan IPv6 yang CIDRAM harus berusaha untuk menggunakan, dipisahkan dengan koma.';
$CIDRAM['lang']['config_signatures_modules'] = 'Daftar file modul untuk memuat setelah memeriksa tanda tangan IPv4/IPv6, dipisahkan dengan koma.';
$CIDRAM['lang']['config_signatures_track_mode'] = 'Kapan sebaiknya pelanggaran dihitung? False = Ketika IP adalah diblokir oleh modul. True = Ketika IP adalah diblokir untuk alasan apapun.';
$CIDRAM['lang']['config_template_data_Magnification'] = 'Perbesaran font. Default = 1.';
$CIDRAM['lang']['config_template_data_css_url'] = 'URL file CSS untuk tema kustom.';
$CIDRAM['lang']['config_template_data_theme'] = 'Tema default untuk CIDRAM.';
$CIDRAM['lang']['confirm_action'] = 'Anda yakin ingin "%s"?';
$CIDRAM['lang']['field_2fa'] = 'Kode 2FA';
$CIDRAM['lang']['field_Request_Method'] = 'Metode permintaan';
$CIDRAM['lang']['field_activate'] = 'Mengaktifkan';
$CIDRAM['lang']['field_add_more_conditions'] = 'Tambahkan lebih banyak kondisi';
$CIDRAM['lang']['field_banned'] = 'Dilarang';
$CIDRAM['lang']['field_blocked'] = 'Diblokir';
$CIDRAM['lang']['field_clear'] = 'Cabut';
$CIDRAM['lang']['field_clear_all'] = 'Cabut semua';
$CIDRAM['lang']['field_clickable_link'] = 'Link yang dapat diklik';
$CIDRAM['lang']['field_component'] = 'Komponen';
$CIDRAM['lang']['field_confirm'] = 'Konfirmasikan';
$CIDRAM['lang']['field_create_new_account'] = 'Buat Akun Baru';
$CIDRAM['lang']['field_deactivate'] = 'Menonaktifkan';
$CIDRAM['lang']['field_delete'] = 'Menghapus';
$CIDRAM['lang']['field_delete_account'] = 'Hapus Akun';
$CIDRAM['lang']['field_download_file'] = 'Mendownload';
$CIDRAM['lang']['field_edit_file'] = 'Mengedit';
$CIDRAM['lang']['field_expiry'] = 'Kadaluarsa';
$CIDRAM['lang']['field_false'] = 'False (Palsu)';
$CIDRAM['lang']['field_file'] = 'File';
$CIDRAM['lang']['field_filename'] = 'Nama file: ';
$CIDRAM['lang']['field_filetype_directory'] = 'Direktori';
$CIDRAM['lang']['field_filetype_info'] = 'File {EXT}';
$CIDRAM['lang']['field_filetype_unknown'] = 'Tidak Diketahui';
$CIDRAM['lang']['field_include'] = 'Sertakan bidang kosong';
$CIDRAM['lang']['field_infractions'] = 'Pelanggaran';
$CIDRAM['lang']['field_install'] = 'Instal';
$CIDRAM['lang']['field_ip_address'] = 'Alamat IP';
$CIDRAM['lang']['field_latest_version'] = 'Versi Terbaru';
$CIDRAM['lang']['field_log_in'] = 'Masuk';
$CIDRAM['lang']['field_new_name'] = 'Nama baru:';
$CIDRAM['lang']['field_nonclickable_text'] = 'Teks yang tidak dapat diklik';
$CIDRAM['lang']['field_ok'] = 'OK';
$CIDRAM['lang']['field_omit'] = 'Hilangkan bidang kosong';
$CIDRAM['lang']['field_options'] = 'Opsi';
$CIDRAM['lang']['field_password'] = 'Kata Sandi';
$CIDRAM['lang']['field_permissions'] = 'Izin';
$CIDRAM['lang']['field_range'] = 'Jangkauan (Pertama – Terakhir)';
$CIDRAM['lang']['field_reasonmessage'] = 'Mengapa Diblokir (terperinci)';
$CIDRAM['lang']['field_rename_file'] = 'Memodifikasi nama';
$CIDRAM['lang']['field_reset'] = 'Mengatur Kembali';
$CIDRAM['lang']['field_set_new_password'] = 'Buat Baru Kata Sandi';
$CIDRAM['lang']['field_size'] = 'Ukuran Total: ';
$CIDRAM['lang']['field_size_GB'] = 'GB';
$CIDRAM['lang']['field_size_KB'] = 'KB';
$CIDRAM['lang']['field_size_MB'] = 'MB';
$CIDRAM['lang']['field_size_TB'] = 'TB';
$CIDRAM['lang']['field_size_bytes'] = 'byte';
$CIDRAM['lang']['field_status'] = 'Status';
$CIDRAM['lang']['field_system_timezone'] = 'Gunakan zona waktu default sistem.';
$CIDRAM['lang']['field_tracking'] = 'Pelacakan';
$CIDRAM['lang']['field_true'] = 'True (Benar)';
$CIDRAM['lang']['field_ualc'] = 'Agen Pengguna (huruf kecil)';
$CIDRAM['lang']['field_uninstall'] = 'Uninstal';
$CIDRAM['lang']['field_update'] = 'Perbarui';
$CIDRAM['lang']['field_update_all'] = 'Memperbarui semua';
$CIDRAM['lang']['field_upload_file'] = 'Mengupload file baru';
$CIDRAM['lang']['field_username'] = 'Nama Pengguna';
$CIDRAM['lang']['field_verify'] = 'Memverifikasi';
$CIDRAM['lang']['field_verify_all'] = 'Memverifikasi semua';
$CIDRAM['lang']['field_your_version'] = 'Versi Anda';
$CIDRAM['lang']['header_login'] = 'Silahkan masuk untuk melanjutkan.';
$CIDRAM['lang']['label_active_config_file'] = 'File konfigurasi aktif: ';
$CIDRAM['lang']['label_actual'] = 'Sebenarnya';
$CIDRAM['lang']['label_aux_actBlk'] = 'blokir';
$CIDRAM['lang']['label_aux_actByp'] = 'bypass';
$CIDRAM['lang']['label_aux_actGrl'] = 'daftar abu-abu';
$CIDRAM['lang']['label_aux_actWhl'] = 'daftar putih';
$CIDRAM['lang']['label_aux_create_new_rule'] = 'Buat aturan baru';
$CIDRAM['lang']['label_aux_logic_all'] = 'Untuk memicu aturan, semua kondisi harus dipenuhi.';
$CIDRAM['lang']['label_aux_logic_any'] = 'Setiap "sama" (=) kondisi dapat memicu aturan, selama semua "tidak sama" (≠) kondisi juga dipenuhi.';
$CIDRAM['lang']['label_aux_menu_action'] = 'Jika kondisi berikut dipenuhi, %s permintaan.';
$CIDRAM['lang']['label_aux_menu_method'] = 'Gunakan %s untuk menguji kondisi.';
$CIDRAM['lang']['label_aux_mtdReg'] = 'ekspresi reguler';
$CIDRAM['lang']['label_aux_mtdStr'] = 'perbandingan string langsung';
$CIDRAM['lang']['label_aux_mtdWin'] = 'wildcard bergaya Windows';
$CIDRAM['lang']['label_aux_name'] = 'Nama untuk aturan baru:';
$CIDRAM['lang']['label_aux_reason'] = 'Alasan diberikan kepada pengguna saat diblokir:';
$CIDRAM['lang']['label_backup_location'] = 'Lokasi cadangan repositori (dalam keadaan darurat, atau jika semuanya gagal):';
$CIDRAM['lang']['label_banned'] = 'Permintaan dilarang';
$CIDRAM['lang']['label_blocked'] = 'Permintaan diblokir';
$CIDRAM['lang']['label_branch'] = 'Cabang terbaru stabil:';
$CIDRAM['lang']['label_check_aux'] = 'Juga menguji terhadap aturan tambahan.';
$CIDRAM['lang']['label_check_modules'] = 'Juga menguji terhadap modul.';
$CIDRAM['lang']['label_cidram'] = 'Versi CIDRAM digunakan:';
$CIDRAM['lang']['label_clientinfo'] = 'Informasi klien:';
$CIDRAM['lang']['label_displaying'] = 'Menampilkan <span class="txtRd">%s</span> entri.';
$CIDRAM['lang']['label_displaying_that_cite'] = 'Menampilkan <span class="txtRd">%1$s</span> entri yang berisi "%2$s".';
$CIDRAM['lang']['label_expected'] = 'Diharapkan';
$CIDRAM['lang']['label_expires'] = 'Kedaluwarsa: ';
$CIDRAM['lang']['label_false_positive_risk'] = 'Risiko positif palsu: ';
$CIDRAM['lang']['label_fmgr_cache_data'] = 'Data cache dan file sementara';
$CIDRAM['lang']['label_fmgr_disk_usage'] = 'Penggunaan disk CIDRAM: ';
$CIDRAM['lang']['label_fmgr_free_space'] = 'Ruang disk kosong: ';
$CIDRAM['lang']['label_fmgr_total_disk_usage'] = 'Penggunaan disk total: ';
$CIDRAM['lang']['label_fmgr_total_space'] = 'Ruang disk total: ';
$CIDRAM['lang']['label_fmgr_updates_metadata'] = 'Komponen memperbarui metadata';
$CIDRAM['lang']['label_hide'] = 'Menyembunyikan';
$CIDRAM['lang']['label_hide_hash_table'] = 'Sembunyikan tabel hash';
$CIDRAM['lang']['label_ignore'] = 'Abaikan ini';
$CIDRAM['lang']['label_never'] = 'Tak pernah';
$CIDRAM['lang']['label_os'] = 'Sistem operasi digunakan:';
$CIDRAM['lang']['label_other'] = 'Lain';
$CIDRAM['lang']['label_other-ActiveIPv4'] = 'File tanda tangan IPv4 aktif';
$CIDRAM['lang']['label_other-ActiveIPv6'] = 'File tanda tangan IPv6 aktif';
$CIDRAM['lang']['label_other-ActiveModules'] = 'Modul aktif';
$CIDRAM['lang']['label_other-Since'] = 'Mulai tanggal';
$CIDRAM['lang']['label_php'] = 'Versi PHP digunakan:';
$CIDRAM['lang']['label_reCAPTCHA'] = 'Upaya reCAPTCHA';
$CIDRAM['lang']['label_results'] = 'Hasil (%s dimasukkan – %s ditolak – %s diterima – %s digabungkan – %s keluaran):';
$CIDRAM['lang']['label_sapi'] = 'SAPI digunakan:';
$CIDRAM['lang']['label_show'] = 'Menunjukkan';
$CIDRAM['lang']['label_show_by_origin'] = 'Tampilkan asal';
$CIDRAM['lang']['label_show_hash_table'] = 'Tampilkan tabel hash';
$CIDRAM['lang']['label_signature_type'] = 'Jenis tanda tangan:';
$CIDRAM['lang']['label_stable'] = 'Terbaru stabil:';
$CIDRAM['lang']['label_sysinfo'] = 'Informasi sistem:';
$CIDRAM['lang']['label_tests'] = 'Pengujian:';
$CIDRAM['lang']['label_total'] = 'Total';
$CIDRAM['lang']['label_unignore'] = 'Jangan abaikan ini';
$CIDRAM['lang']['label_unstable'] = 'Terbaru tidak stabil:';
$CIDRAM['lang']['label_used_with'] = 'Digunakan dengan: ';
$CIDRAM['lang']['label_your_ip'] = 'IP Anda:';
$CIDRAM['lang']['label_your_ua'] = 'UA Anda:';
$CIDRAM['lang']['link_accounts'] = 'Akun';
$CIDRAM['lang']['link_aux'] = 'Aturan Tambahan';
$CIDRAM['lang']['link_cache_data'] = 'Data Cache';
$CIDRAM['lang']['link_cidr_calc'] = 'Kalkulator CIDR';
$CIDRAM['lang']['link_config'] = 'Konfigurasi';
$CIDRAM['lang']['link_documentation'] = 'Dokumentasi';
$CIDRAM['lang']['link_file_manager'] = 'File Manager';
$CIDRAM['lang']['link_home'] = 'Halaman Utama';
$CIDRAM['lang']['link_ip_aggregator'] = 'Agregator IP';
$CIDRAM['lang']['link_ip_test'] = 'IP Test';
$CIDRAM['lang']['link_ip_tracking'] = 'Pelacakan IP';
$CIDRAM['lang']['link_logs'] = 'Log';
$CIDRAM['lang']['link_range'] = 'Tabel Rentang';
$CIDRAM['lang']['link_sections_list'] = 'Daftar Bagian';
$CIDRAM['lang']['link_statistics'] = 'Statistik';
$CIDRAM['lang']['link_textmode'] = 'Format teks: <a href="%1$sfalse%2$s">Sederhana</a> – <a href="%1$strue%2$s">Terformat</a> – <a href="%1$stally%2$s">Penghitungannya</a>';
$CIDRAM['lang']['link_updates'] = 'Pembaruan';
$CIDRAM['lang']['logs_logfile_doesnt_exist'] = 'Log yang dipilih tidak ada!';
$CIDRAM['lang']['logs_no_logfile_selected'] = 'Tidak ada log dipilih.';
$CIDRAM['lang']['logs_no_logfiles_available'] = 'Tidak ada log tersedia.';
$CIDRAM['lang']['max_login_attempts_exceeded'] = 'Jumlah maksimum upaya untuk memasukkan tercapai; Akses ditolak.';
$CIDRAM['lang']['previewer_days'] = 'Hari';
$CIDRAM['lang']['previewer_hours'] = 'Jam';
$CIDRAM['lang']['previewer_minutes'] = 'Menit';
$CIDRAM['lang']['previewer_months'] = 'Bulan';
$CIDRAM['lang']['previewer_seconds'] = 'Detik';
$CIDRAM['lang']['previewer_weeks'] = 'Minggu';
$CIDRAM['lang']['previewer_years'] = 'Tahun';
$CIDRAM['lang']['response_2fa_invalid'] = 'Kode 2FA salah dimasukkan. Otentikasi gagal.';
$CIDRAM['lang']['response_2fa_valid'] = 'Berhasil dikonfirmasi.';
$CIDRAM['lang']['response_accounts_already_exists'] = 'Akun dengan nama pengguna ini sudah ada!';
$CIDRAM['lang']['response_accounts_created'] = 'Akun berhasil dibuat!';
$CIDRAM['lang']['response_accounts_deleted'] = 'Akun berhasil dihapus!';
$CIDRAM['lang']['response_accounts_doesnt_exist'] = 'Akun ini tidak ada.';
$CIDRAM['lang']['response_accounts_password_updated'] = 'Kata sandi berhasil diperbarui!';
$CIDRAM['lang']['response_activated'] = 'Berhasil diaktifkan.';
$CIDRAM['lang']['response_activation_failed'] = 'Kegagalan pengaktifan!';
$CIDRAM['lang']['response_aux_none'] = 'Saat ini tidak ada aturan tambahan.';
$CIDRAM['lang']['response_aux_rule_created_successfully'] = 'Aturan tambahan baru, "%s", berhasil dibuat.';
$CIDRAM['lang']['response_aux_rule_deleted_successfully'] = 'Aturan tambahan, "%s", berhasil dihapus.';
$CIDRAM['lang']['response_checksum_error'] = 'Kesalahan checksum! File ditolak!';
$CIDRAM['lang']['response_component_successfully_installed'] = 'Komponen berhasil diinstal.';
$CIDRAM['lang']['response_component_successfully_uninstalled'] = 'Komponen berhasil diuninstal.';
$CIDRAM['lang']['response_component_successfully_updated'] = 'Komponen berhasil diperbarui.';
$CIDRAM['lang']['response_component_uninstall_error'] = 'Terjadi kesalahan saat mencoba untuk menguninstal komponen ini.';
$CIDRAM['lang']['response_configuration_updated'] = 'Konfigurasi berhasil diperbarui.';
$CIDRAM['lang']['response_deactivated'] = 'Berhasil dinonaktifkan.';
$CIDRAM['lang']['response_deactivation_failed'] = 'Kegagalan penonaktifan!';
$CIDRAM['lang']['response_delete_error'] = 'Gagal menghapus!';
$CIDRAM['lang']['response_directory_deleted'] = 'Direktori berhasil dihapus!';
$CIDRAM['lang']['response_directory_renamed'] = 'Nama direktori berhasil dimodifikasi!';
$CIDRAM['lang']['response_error'] = 'Kesalahan';
$CIDRAM['lang']['response_failed_to_install'] = 'Gagal menginstal!';
$CIDRAM['lang']['response_failed_to_update'] = 'Gagal memperbarui!';
$CIDRAM['lang']['response_file_deleted'] = 'File berhasil dihapus!';
$CIDRAM['lang']['response_file_edited'] = 'File berhasil diubah!';
$CIDRAM['lang']['response_file_renamed'] = 'Nama file berhasil dimodifikasi!';
$CIDRAM['lang']['response_file_uploaded'] = 'File berhasil diupload!';
$CIDRAM['lang']['response_login_invalid_password'] = 'Kegagalan masuk! Kata sandi salah!';
$CIDRAM['lang']['response_login_invalid_username'] = 'Kegagalan masuk! Nama pengguna tidak ada!';
$CIDRAM['lang']['response_login_password_field_empty'] = 'Kata sandi yang kosong!';
$CIDRAM['lang']['response_login_username_field_empty'] = 'Nama pengguna yang kosong!';
$CIDRAM['lang']['response_login_wrong_endpoint'] = 'Jalur akses salah!';
$CIDRAM['lang']['response_no'] = 'Tidak';
$CIDRAM['lang']['response_possible_problem_found'] = 'Kemungkinan masalah ditemukan.';
$CIDRAM['lang']['response_rename_error'] = 'Gagal memodifikasi nama!';
$CIDRAM['lang']['response_sanity_1'] = 'File berisi konten yang tidak diharapkan! File ditolak!';
$CIDRAM['lang']['response_statistics_cleared'] = 'Statistik dicabut';
$CIDRAM['lang']['response_tracking_cleared'] = 'Pelacakan dicabut.';
$CIDRAM['lang']['response_updates_already_up_to_date'] = 'Sudah yang terbaru.';
$CIDRAM['lang']['response_updates_not_installed'] = 'Komponen tidak diinstal!';
$CIDRAM['lang']['response_updates_not_installed_php'] = 'Komponen tidak diinstal (membutuhkan PHP &gt;= {V})!';
$CIDRAM['lang']['response_updates_outdated'] = 'Tidak yang terbaru!';
$CIDRAM['lang']['response_updates_outdated_manually'] = 'Tidak yang terbaru (silahkan perbarui secara manual)!';
$CIDRAM['lang']['response_updates_outdated_php_version'] = 'Tidak yang terbaru (membutuhkan PHP &gt;= {V})!';
$CIDRAM['lang']['response_updates_unable_to_determine'] = 'Tidak dapat menentukan.';
$CIDRAM['lang']['response_upload_error'] = 'Gagal mengupload!';
$CIDRAM['lang']['response_verification_failed'] = 'Verifikasi gagal! Komponen mungkin rusak.';
$CIDRAM['lang']['response_verification_success'] = 'Verifikasi sukses! Tidak ada masalah ditemukan.';
$CIDRAM['lang']['response_yes'] = 'Ya';
$CIDRAM['lang']['security_warning'] = 'Masalah yang tidak diharapkan terjadi saat memproses permintaan Anda. Silahkan coba lagi. Jika masalah terus berlanjut, hubungi dukungan.';
$CIDRAM['lang']['state_async_deny'] = 'Izin tidak memadai untuk melakukan permintaan asinkron. Coba masuk lagi.';
$CIDRAM['lang']['state_cache_is_empty'] = 'Cache kosong.';
$CIDRAM['lang']['state_complete_access'] = 'Akses lengkap';
$CIDRAM['lang']['state_component_is_active'] = 'Komponen ini aktif.';
$CIDRAM['lang']['state_component_is_inactive'] = 'Komponen ini non-aktif.';
$CIDRAM['lang']['state_component_is_provisional'] = 'Komponen ini kadang-kadang aktif.';
$CIDRAM['lang']['state_default_password'] = 'Peringatan: Menggunakan kata sandi standar!';
$CIDRAM['lang']['state_email_sent'] = 'Email berhasil dikirim ke "%s".';
$CIDRAM['lang']['state_failed_missing'] = 'Tugas gagal karena komponen yang diperlukan tidak tersedia.';
$CIDRAM['lang']['state_ignored'] = 'Diabaikan';
$CIDRAM['lang']['state_loading'] = 'Pemuatan...';
$CIDRAM['lang']['state_loadtime'] = 'Permintaan halaman selesai dalam <span class="txtRd">%s</span> detik.';
$CIDRAM['lang']['state_logged_in'] = 'Dimasuk.';
$CIDRAM['lang']['state_logged_in_2fa_pending'] = 'Dimasuk + 2FA sedang menunggu.';
$CIDRAM['lang']['state_logged_out'] = 'Dikeluar.';
$CIDRAM['lang']['state_logs_access_only'] = 'Akses ke log hanya';
$CIDRAM['lang']['state_maintenance_mode'] = 'Peringatan: Modus perawatan diaktifkan!';
$CIDRAM['lang']['state_password_not_valid'] = 'Peringatan: Akun ini tidak menggunakan kata sandi yang valid!';
$CIDRAM['lang']['state_risk_high'] = 'Tinggi';
$CIDRAM['lang']['state_risk_low'] = 'Rendah';
$CIDRAM['lang']['state_risk_medium'] = 'Menengah';
$CIDRAM['lang']['state_sl_totals'] = 'Total (Tanda tangan: <span class="txtRd">%s</span> – Bagian tanda tangan: <span class="txtRd">%s</span> – File tanda tangan: <span class="txtRd">%s</span> – Tag bagian unik: <span class="txtRd">%s</span>).';
$CIDRAM['lang']['state_tracking'] = 'Saat ini melacak %s IP.';
$CIDRAM['lang']['switch-hide-non-outdated-set-false'] = 'Tidak menyembunyikan terbaru';
$CIDRAM['lang']['switch-hide-non-outdated-set-true'] = 'Menyembunyikan terbaru';
$CIDRAM['lang']['switch-hide-unused-set-false'] = 'Tidak menyembunyikan non-digunakan';
$CIDRAM['lang']['switch-hide-unused-set-true'] = 'Menyembunyikan non-digunakan';
$CIDRAM['lang']['switch-tracking-blocked-already-set-false'] = 'Jangan cek terhadap file tanda tangan';
$CIDRAM['lang']['switch-tracking-blocked-already-set-true'] = 'Cek terhadap file tanda tangan';
$CIDRAM['lang']['switch-tracking-hide-banned-blocked-set-false'] = 'Jangan sembunyikan IP yang dilarang/diblokir';
$CIDRAM['lang']['switch-tracking-hide-banned-blocked-set-true'] = 'Sembunyikan IP yang dilarang/diblokir';
$CIDRAM['lang']['tip_2fa_sent'] = 'Sebuah email yang berisi kode otentikasi dua-faktor telah dikirim ke alamat email Anda. Silahkan mengkonfirmasikan kode ini dibawah untuk mendapatkan akses ke bagian depan. Jika Anda tidak menerima email ini, coba keluar, tunggu 10 menit, dan masuk lagi untuk menerima email baru yang berisi kode baru.';
$CIDRAM['lang']['tip_accounts'] = 'Salam, {username}.<br />Halaman akun memungkinkan Anda untuk mengontrol siapa dapat mengakses bagian depan CIDRAM.';
$CIDRAM['lang']['tip_aux'] = 'Salam, {username}.<br />Anda dapat menggunakan halaman ini untuk membuat, menghapus, dan mengubah aturan tambahan untuk CIDRAM.';
$CIDRAM['lang']['tip_cache_data'] = 'Salam, {username}.<br />Disini Anda bisa meninjau isi cache.';
$CIDRAM['lang']['tip_cidr_calc'] = 'Salam, {username}.<br />Kalkulator CIDR memungkinkan Anda untuk menghitung yang CIDR alamat IP adalah faktor.';
$CIDRAM['lang']['tip_condition_placeholder'] = 'Tentukan nilai, atau biarkan kosong untuk diabaikan.';
$CIDRAM['lang']['tip_config'] = 'Salam, {username}.<br />Halaman konfigurasi memungkinkan Anda untuk memodifikasi konfigurasi untuk CIDRAM dari bagian depan.';
$CIDRAM['lang']['tip_custom_ua'] = 'Masukkan agen pengguna (user agent) disini (opsional).';
$CIDRAM['lang']['tip_donate'] = 'CIDRAM ditawarkan gratis, tapi jika Anda ingin menyumbang untuk proyek, Anda dapat melakukannya dengan mengklik menyumbangkan tombol.';
$CIDRAM['lang']['tip_enter_ip_here'] = 'Masukkan IP disini.';
$CIDRAM['lang']['tip_enter_ips_here'] = 'Masukkan IP disini.';
$CIDRAM['lang']['tip_fe_cookie_warning'] = 'Catat: CIDRAM menggunakan cookie untuk mengotentikasi semua login. Saat kamu masuk, Anda memberikan izin agar cookie dibuat dan disimpan oleh browser Anda.';
$CIDRAM['lang']['tip_file_manager'] = 'Salam, {username}.<br />File manager memungkinkan Anda untuk menghapus, mengedit, mengupload, dan mendownload file. Gunakan dengan hati-hati (Anda bisa istirahat instalasi Anda dengan ini).';
$CIDRAM['lang']['tip_home'] = 'Salam, {username}.<br />Ini adalah halaman utama untuk CIDRAM bagian depan. Pilih link dari menu navigasi di sisi kiri untuk melanjutkan.';
$CIDRAM['lang']['tip_ip_aggregator'] = 'Salam, {username}.<br />Agregator IP memungkinkan Anda untuk mengekspresikan IP dan CIDR dengan cara sekecil mungkin. Masukkan data yang akan digabungkan dan tekan "OK".';
$CIDRAM['lang']['tip_ip_test'] = 'Salam, {username}.<br />Halaman IP test memungkinkan Anda untuk mengetes apakah alamat IP yang diblokir dengan tanda tangan yang saat ini diinstal.';
$CIDRAM['lang']['tip_ip_test_switches'] = '(Bila tidak dipilih, hanya file tanda tangan akan diuji).';
$CIDRAM['lang']['tip_ip_tracking'] = 'Salam, {username}.<br />Halaman pelacakan IP memungkinkan Anda untuk memeriksa status pelacakan alamat IP, untuk memeriksa yang mereka telah dilarang, dan mencabut pelacakan untuk mereka jika Anda ingin melakukan.';
$CIDRAM['lang']['tip_login'] = 'Nama pengguna standar: <span class="txtRd">admin</span> – Kata sandi standar: <span class="txtRd">password</span>';
$CIDRAM['lang']['tip_logs'] = 'Salam, {username}.<br />Pilih log dari daftar dibawah untuk melihat isi log.';
$CIDRAM['lang']['tip_range'] = 'Salam, {username}.<br />Halaman ini menunjukkan beberapa informasi statistik dasar tentang rentang IP yang dicakup oleh file tanda tangan yang saat ini aktif.';
$CIDRAM['lang']['tip_sections_list'] = 'Salam, {username}.<br />Halaman ini mencantumkan bagian yang ada di file tanda tangan yang aktif saat ini.';
$CIDRAM['lang']['tip_see_the_documentation'] = 'Lihat <a href="https://github.com/CIDRAM/CIDRAM/blob/master/_docs/readme.id.md#SECTION6">dokumentasi</a> untuk informasi tentang berbagai direktif konfigurasi dan tujuan mereka.';
$CIDRAM['lang']['tip_statistics'] = 'Salam, {username}.<br />Halaman ini menunjukkan beberapa statistik penggunaan dasar mengenai instalasi CIDRAM Anda.';
$CIDRAM['lang']['tip_statistics_disabled'] = 'Catat: Pelacakan statistik saat ini dinonaktifkan, namun dapat diaktifkan melalui halaman konfigurasi.';
$CIDRAM['lang']['tip_updates'] = 'Salam, {username}.<br />Halaman pembaruan memungkinkan Anda untuk menginstal, menguninstal, dan memperbarui berbagai komponen CIDRAM (paket inti, tanda tangan, file L10N, dll).';
$CIDRAM['lang']['title_login'] = 'Masuk';
$CIDRAM['lang']['warning'] = 'Peringatan:';
$CIDRAM['lang']['warning_php_1'] = 'Versi PHP Anda tidak aktif didukung lagi! Memperbarui dianjurkan!';
$CIDRAM['lang']['warning_php_2'] = 'Versi PHP Anda sangat rentan! Memperbarui sangat dianjurkan!';
$CIDRAM['lang']['warning_signatures_1'] = 'Tidak ada file tanda tangan yang aktif!';

$CIDRAM['lang']['info_some_useful_links'] = 'Beberapa link yang berguna:<ul>
      <li><a href="https://github.com/CIDRAM/CIDRAM/issues">Masalah CIDRAM @ GitHub</a> – Halaman masalah untuk CIDRAM (dukungan, bantuan, dll).</li>
      <li><a href="https://wordpress.org/plugins/cidram/">CIDRAM @ WordPress.org</a> – Plugin WordPress untuk CIDRAM.</li>
      <li><a href="https://bitbucket.org/macmathan/blocklists">macmathan/blocklists</a> – Berisi daftar blok opsional dan modul untuk CIDRAM seperti untuk memblokir bot berbahaya, negara yang tidak diinginkan, peramban yang ketinggalan jaman, dll.</li>
      <li><a href="https://www.facebook.com/groups/2204685680/">Global PHP Group @ Facebook</a> – Sumber belajar dan diskusi PHP.</li>
      <li><a href="https://php.earth/">PHP.earth</a> – Sumber belajar dan diskusi PHP.</li>
      <li><a href="https://bgp.he.net/">Hurricane Electric BGP Toolkit</a> – Dapatkan CIDRs dari ASN, menentukan hubungan ASN, menemukan ASN berdasarkan nama jaringan, dll.</li>
      <li><a href="https://www.stopforumspam.com/forum/">Forum @ Stop Forum Spam</a> – Forum diskusi yang berguna tentang menghentikan spam forum.</li>
      <li><a href="https://radar.qrator.net/">Radar oleh Qrator</a> – Alat yang berguna untuk memeriksa konektivitas ASN serta berbagai informasi lainnya tentang ASN.</li>
      <li><a href="http://www.ipdeny.com/ipblocks/">Blok negara IP dari IPdeny</a> – Sebuah layanan yang fantastis dan akurat untuk menghasilkan tanda tangan negara-lebar.</li>
      <li><a href="https://www.google.com/transparencyreport/safebrowsing/malware/">Google Malware Dashboard</a> – Menampilkan laporan tentang tingkat infeksi malware untuk ASN.</li>
      <li><a href="https://www.spamhaus.org/statistics/botnet-asn/">Proyek Spamhaus</a> – Menampilkan laporan tentang tingkat infeksi botnet untuk ASN.</li>
      <li><a href="https://www.abuseat.org/public/asn.html">Daftar Pemblokiran Komposit dari Abuseat.org</a> – Menampilkan laporan tentang tingkat infeksi botnet untuk ASN.</li>
      <li><a href="https://abuseipdb.com/">AbuseIPDB</a> – Mempertahankan database IP berbahaya dikenal; Menyediakan API untuk memeriksa dan melaporkan IP.</li>
      <li><a href="https://www.megarbl.net/index.php">MegaRBL.net</a> – Mempertahankan daftar spammer dikenal; Berguna untuk memeriksa aktivitas spam dari IP/ASN.</li>
      <li><a href="https://maikuolan.github.io/Vulnerability-Charts/">Tabel Kerentanan</a> – Mencantumkan berbagai versi dari paket-paket yang aman dan tidak aman (HHVM, PHP, phpMyAdmin, Python, dll).</li>
      <li><a href="https://maikuolan.github.io/Compatibility-Charts/">Tabel Kompatibilitas</a> – Mencantumkan informasi kompatibilitas untuk berbagai paket (CIDRAM, phpMussel, dll).</li>
    </ul>';

$CIDRAM['lang']['msg_template_2fa'] = '<center><p>Salam, %1$s.<br />
<br />
Kode otentikasi dua-faktor Anda untuk masuk ke depan CIDRAM:</p>
<h1>%2$s</h1>
<p>Kode ini berakhir dalam 10 menit.</p></center>';
$CIDRAM['lang']['msg_subject_2fa'] = 'Otentikasi Dua-Faktor';
