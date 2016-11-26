<?php
/**
 * This file is a part of the CIDRAM package, and can be downloaded for free
 * from {@link https://github.com/Maikuolan/CIDRAM/ GitHub}.
 *
 * CIDRAM COPYRIGHT 2016 and beyond by Caleb Mazalevskis (Maikuolan).
 *
 * License: GNU/GPLv2
 * @see LICENSE.txt
 *
 * This file: Indonesian language data for the front-end (last modified: 2016.11.26).
 */

/** Prevents execution from outside of CIDRAM. */
if (!defined('CIDRAM')) {
    die('[CIDRAM] This should not be accessed directly.');
}

$CIDRAM['lang']['bNav_home_logout'] = '<a href="?">Halaman Utama</a> | <a href="?cidram-page=logout">Keluar</a>';
$CIDRAM['lang']['bNav_logout'] = '<a href="?cidram-page=logout">Keluar</a>';
$CIDRAM['lang']['config_general_disable_cli'] = 'Menonaktifkan modus CLI?';
$CIDRAM['lang']['config_general_disable_frontend'] = 'Menonaktifkan akses bagian depan?';
$CIDRAM['lang']['config_general_emailaddr'] = 'Alamat email untuk dukungan.';
$CIDRAM['lang']['config_general_forbid_on_block'] = 'Apa header harus CIDRAM merespon dengan ketika memblokir permintaan?';
$CIDRAM['lang']['config_general_ipaddr'] = 'Dimana menemukan alamat IP dari permintaan alamat?';
$CIDRAM['lang']['config_general_lang'] = 'Tentukan bahasa default untuk CIDRAM.';
$CIDRAM['lang']['config_general_logfile'] = 'File yang dibaca oleh manusia untuk mencatat semua upaya akses diblokir. Spesifikasikan nama file, atau biarkan kosong untuk menonaktifkan.';
$CIDRAM['lang']['config_general_logfileApache'] = 'File yang dalam gaya Apache untuk mencatat semua upaya akses diblokir. Spesifikasikan nama file, atau biarkan kosong untuk menonaktifkan.';
$CIDRAM['lang']['config_general_logfileSerialized'] = 'File serial untuk mencatat semua upaya akses diblokir. Spesifikasikan nama file, atau biarkan kosong untuk menonaktifkan.';
$CIDRAM['lang']['config_general_silent_mode'] = 'Seharusnya CIDRAM diam-diam mengarahkan diblokir upaya akses bukannya menampilkan halaman "Akses Ditolak"? Jika ya, menentukan lokasi untuk mengarahkan diblokir upaya akses. Jika tidak, kosongkan variabel ini.';
$CIDRAM['lang']['config_general_timeOffset'] = 'Offset zona waktu dalam hitungan menit.';
$CIDRAM['lang']['config_recaptcha_expiry'] = 'Jumlah jam untuk mengingat instansi reCAPTCHA.';
$CIDRAM['lang']['config_recaptcha_lockip'] = 'Kunci reCAPTCHA ke IP?';
$CIDRAM['lang']['config_recaptcha_lockuser'] = 'Kunci reCAPTCHA ke pengguna?';
$CIDRAM['lang']['config_recaptcha_logfile'] = 'Mencatat hasil semua instansi reCAPTCHA? Jika ya, tentukan nama untuk menggunakan untuk file catatan. Jika tidak, variabel ini harus kosong.';
$CIDRAM['lang']['config_recaptcha_secret'] = 'Nilai ini harus sesuai dengan "secret key" untuk reCAPTCHA Anda, yang dapat ditemukan dalam dashboard reCAPTCHA.';
$CIDRAM['lang']['config_recaptcha_sitekey'] = 'Nilai ini harus sesuai dengan "site key" untuk reCAPTCHA Anda, yang dapat ditemukan dalam dashboard reCAPTCHA.';
$CIDRAM['lang']['config_recaptcha_usemode'] = 'Mendefinisikan bagaimana CIDRAM harus menggunakan reCAPTCHA.';
$CIDRAM['lang']['config_signatures_block_bogons'] = 'Memblokir CIDR bogon/martian? Jika Anda mengharapkan koneksi ke website Anda dari dalam jaringan lokal Anda, dari localhost, atau dari LAN Anda, direktif ini harus didefinisikan untuk false/palsu. Jika Anda tidak mengharapkan ini, direktif ini harus didefinisikan untuk true/benar.';
$CIDRAM['lang']['config_signatures_block_cloud'] = 'Memblokir CIDR yang diidentifikasi sebagai milik webhosting dan/atau layanan cloud? Jika Anda mengoperasikan layanan API dari website Anda atau jika Anda mengharapkan website lain untuk menghubungkan ke website Anda, direktif ini harus didefinisikan untuk false/palsu. Jika Anda tidak, maka, direktif ini harus didefinisikan untuk true/benar.';
$CIDRAM['lang']['config_signatures_block_generic'] = 'Memblokir CIDR umumnya direkomendasikan untuk mendaftar hitam / blacklist? Ini mencakup tanda tangan apapun yang tidak ditandai sebagai bagian dari apapun lainnya kategori tanda tangan lebih spesifik.';
$CIDRAM['lang']['config_signatures_block_proxies'] = 'Memblokir CIDR yang diidentifikasi sebagai milik layanan proxy? Jika Anda membutuhkan bahwa pengguna dapat mengakses situs web Anda dari layanan proxy anonymous, direktif ini harus didefinisikan untuk false/palsu. Jika Anda tidak membutuhkannya, direktif ini harus didefinisikan untuk true/benar sebagai sarana untuk meningkatkan keamanan.';
$CIDRAM['lang']['config_signatures_block_spam'] = 'Memblokir CIDR yang diidentifikasi sebagai beresiko tinggi karena spam? Kecuali jika Anda mengalami masalah ketika melakukan itu, umumnya, ini harus selalu didefinisikan untuk true/benar.';
$CIDRAM['lang']['config_signatures_ipv4'] = 'Daftar file tanda tangan IPv4 yang CIDRAM harus berusaha untuk menggunakan, dipisahkan dengan koma.';
$CIDRAM['lang']['config_signatures_ipv6'] = 'Daftar file tanda tangan IPv6 yang CIDRAM harus berusaha untuk menggunakan, dipisahkan dengan koma.';
$CIDRAM['lang']['config_template_data_css_url'] = 'URL file CSS untuk tema kustom.';
$CIDRAM['lang']['field_blocked'] = 'Diblokir';
$CIDRAM['lang']['field_component'] = 'Komponen';
$CIDRAM['lang']['field_create_new_account'] = 'Buat Akun Baru';
$CIDRAM['lang']['field_delete_account'] = 'Hapus Akun';
$CIDRAM['lang']['field_delete_file'] = 'Menghapus';
$CIDRAM['lang']['field_download_file'] = 'Mendownload';
$CIDRAM['lang']['field_edit_file'] = 'Mengedit';
$CIDRAM['lang']['field_file'] = 'File';
$CIDRAM['lang']['field_filename'] = 'Nama file: ';
$CIDRAM['lang']['field_filetype_directory'] = 'Direktori';
$CIDRAM['lang']['field_filetype_info'] = 'File {EXT}';
$CIDRAM['lang']['field_filetype_unknown'] = 'Tidak Diketahui';
$CIDRAM['lang']['field_install'] = 'Instal';
$CIDRAM['lang']['field_ip_address'] = 'Alamat IP';
$CIDRAM['lang']['field_latest_version'] = 'Versi Terbaru';
$CIDRAM['lang']['field_log_in'] = 'Masuk';
$CIDRAM['lang']['field_new_name'] = 'Nama baru:';
$CIDRAM['lang']['field_ok'] = 'OK';
$CIDRAM['lang']['field_options'] = 'Opsi';
$CIDRAM['lang']['field_password'] = 'Kata Sandi';
$CIDRAM['lang']['field_permissions'] = 'Izin';
$CIDRAM['lang']['field_rename_file'] = 'Memodifikasi nama';
$CIDRAM['lang']['field_reset'] = 'Mengatur Kembali';
$CIDRAM['lang']['field_set_new_password'] = 'Buat Baru Kata Sandi';
$CIDRAM['lang']['field_size'] = 'Ukuran Total: ';
$CIDRAM['lang']['field_size_bytes'] = 'byte';
$CIDRAM['lang']['field_size_GB'] = 'GB';
$CIDRAM['lang']['field_size_KB'] = 'KB';
$CIDRAM['lang']['field_size_MB'] = 'MB';
$CIDRAM['lang']['field_size_TB'] = 'TB';
$CIDRAM['lang']['field_status'] = 'Status';
$CIDRAM['lang']['field_uninstall'] = 'Uninstal';
$CIDRAM['lang']['field_update'] = 'Perbarui';
$CIDRAM['lang']['field_upload_file'] = 'Mengupload file baru';
$CIDRAM['lang']['field_username'] = 'Nama Pengguna';
$CIDRAM['lang']['field_your_version'] = 'Versi Anda';
$CIDRAM['lang']['header_login'] = 'Silahkan masuk untuk melanjutkan.';
$CIDRAM['lang']['link_accounts'] = 'Akun';
$CIDRAM['lang']['link_config'] = 'Konfigurasi';
$CIDRAM['lang']['link_documentation'] = 'Dokumentasi';
$CIDRAM['lang']['link_file_manager'] = 'File Manager';
$CIDRAM['lang']['link_home'] = 'Halaman Utama';
$CIDRAM['lang']['link_ip_test'] = 'IP Test';
$CIDRAM['lang']['link_logs'] = 'Log';
$CIDRAM['lang']['link_updates'] = 'Pembaruan';
$CIDRAM['lang']['logs_logfile_doesnt_exist'] = 'Log yang dipilih tidak ada!';
$CIDRAM['lang']['logs_no_logfiles_available'] = 'Tidak ada log tersedia.';
$CIDRAM['lang']['logs_no_logfile_selected'] = 'Tidak ada log dipilih.';
$CIDRAM['lang']['response_accounts_already_exists'] = 'Akun dengan nama pengguna ini sudah ada!';
$CIDRAM['lang']['response_accounts_created'] = 'Akun berhasil dibuat!';
$CIDRAM['lang']['response_accounts_deleted'] = 'Akun berhasil dihapus!';
$CIDRAM['lang']['response_accounts_doesnt_exist'] = 'Akun ini tidak ada.';
$CIDRAM['lang']['response_accounts_password_updated'] = 'Kata sandi berhasil diperbarui!';
$CIDRAM['lang']['response_component_successfully_installed'] = 'Komponen berhasil diinstal.';
$CIDRAM['lang']['response_component_successfully_uninstalled'] = 'Komponen berhasil diuninstal.';
$CIDRAM['lang']['response_component_successfully_updated'] = 'Komponen berhasil diperbarui.';
$CIDRAM['lang']['response_component_uninstall_error'] = 'Terjadi kesalahan saat mencoba untuk menguninstal komponen ini.';
$CIDRAM['lang']['response_component_update_error'] = 'Terjadi kesalahan saat mencoba untuk memperbarui komponen ini.';
$CIDRAM['lang']['response_configuration_updated'] = 'Konfigurasi berhasil diperbarui.';
$CIDRAM['lang']['response_delete_error'] = 'Gagal menghapus!';
$CIDRAM['lang']['response_directory_deleted'] = 'Direktori berhasil dihapus!';
$CIDRAM['lang']['response_directory_renamed'] = 'Nama direktori berhasil dimodifikasi!';
$CIDRAM['lang']['response_error'] = 'Kesalahan';
$CIDRAM['lang']['response_file_deleted'] = 'File berhasil dihapus!';
$CIDRAM['lang']['response_file_edited'] = 'File berhasil diubah!';
$CIDRAM['lang']['response_file_renamed'] = 'Nama file berhasil dimodifikasi!';
$CIDRAM['lang']['response_file_uploaded'] = 'File berhasil diupload!';
$CIDRAM['lang']['response_login_invalid_password'] = 'Kegagalan masuk! Kata sandi salah!';
$CIDRAM['lang']['response_login_invalid_username'] = 'Kegagalan masuk! Nama pengguna tidak ada!';
$CIDRAM['lang']['response_login_password_field_empty'] = 'Kata sandi yang kosong!';
$CIDRAM['lang']['response_login_username_field_empty'] = 'Nama pengguna yang kosong!';
$CIDRAM['lang']['response_no'] = 'Tidak';
$CIDRAM['lang']['response_rename_error'] = 'Gagal memodifikasi nama!';
$CIDRAM['lang']['response_updates_already_up_to_date'] = 'Sudah yang terbaru.';
$CIDRAM['lang']['response_updates_not_installed'] = 'Komponen tidak diinstal!';
$CIDRAM['lang']['response_updates_outdated'] = 'Tidak yang terbaru!';
$CIDRAM['lang']['response_updates_outdated_manually'] = 'Tidak yang terbaru (silahkan perbarui secara manual)!';
$CIDRAM['lang']['response_updates_unable_to_determine'] = 'Tidak dapat menentukan.';
$CIDRAM['lang']['response_upload_error'] = 'Gagal mengupload!';
$CIDRAM['lang']['response_yes'] = 'Ya';
$CIDRAM['lang']['state_complete_access'] = 'Akses lengkap';
$CIDRAM['lang']['state_component_is_active'] = 'Komponen ini aktif.';
$CIDRAM['lang']['state_component_is_inactive'] = 'Komponen ini non-aktif.';
$CIDRAM['lang']['state_component_is_provisional'] = 'Komponen ini kadang-kadang aktif.';
$CIDRAM['lang']['state_default_password'] = 'Peringatan: Menggunakan kata sandi standar!';
$CIDRAM['lang']['state_logged_in'] = 'Pengguna yang online';
$CIDRAM['lang']['state_logs_access_only'] = 'Akses ke log hanya';
$CIDRAM['lang']['state_password_not_valid'] = 'Peringatan: Akun ini tidak menggunakan kata sandi yang valid!';
$CIDRAM['lang']['switch-hide-non-outdated-set-false'] = 'Tidak menyembunyikan terbaru';
$CIDRAM['lang']['switch-hide-non-outdated-set-true'] = 'Menyembunyikan terbaru';
$CIDRAM['lang']['switch-hide-unused-set-false'] = 'Tidak menyembunyikan non-digunakan';
$CIDRAM['lang']['switch-hide-unused-set-true'] = 'Menyembunyikan non-digunakan';
$CIDRAM['lang']['tip_accounts'] = 'Salam, {username}.<br />Halaman akun memungkinkan Anda untuk mengontrol siapa dapat mengakses bagian depan CIDRAM.';
$CIDRAM['lang']['tip_config'] = 'Salam, {username}.<br />Halaman konfigurasi memungkinkan Anda untuk memodifikasi konfigurasi untuk CIDRAM dari bagian depan.';
$CIDRAM['lang']['tip_donate'] = 'CIDRAM ditawarkan gratis, tapi jika Anda ingin menyumbang untuk proyek, Anda dapat melakukannya dengan mengklik menyumbangkan tombol.';
$CIDRAM['lang']['tip_enter_ips_here'] = 'Masukkan IP disini.';
$CIDRAM['lang']['tip_file_manager'] = 'Salam, {username}.<br />File manager memungkinkan Anda untuk menghapus, mengedit, mengupload, dan mendownload file. Gunakan dengan hati-hati (Anda bisa istirahat instalasi Anda dengan ini).';
$CIDRAM['lang']['tip_home'] = 'Salam, {username}.<br />Ini adalah halaman utama untuk CIDRAM bagian depan. Pilih link dari menu navigasi di sisi kiri untuk melanjutkan.';
$CIDRAM['lang']['tip_ip_test'] = 'Salam, {username}.<br />Halaman IP test memungkinkan Anda untuk mengetes apakah alamat IP yang diblokir dengan tanda tangan yang saat ini diinstal.';
$CIDRAM['lang']['tip_login'] = 'Nama pengguna standar: <span class="txtRd">admin</span> – Kata sandi standar: <span class="txtRd">password</span>';
$CIDRAM['lang']['tip_logs'] = 'Salam, {username}.<br />Pilih log dari daftar dibawah untuk melihat isi log.';
$CIDRAM['lang']['tip_see_the_documentation'] = 'Lihat <a href="https://github.com/Maikuolan/CIDRAM/blob/master/_docs/readme.id.md#SECTION6">dokumentasi</a> untuk informasi tentang berbagai direktif konfigurasi dan tujuan mereka.';
$CIDRAM['lang']['tip_updates'] = 'Salam, {username}.<br />Halaman pembaruan memungkinkan Anda untuk menginstal, menguninstal, dan memperbarui berbagai komponen CIDRAM (paket inti, tanda tangan, file L10N, dll).';
$CIDRAM['lang']['title_accounts'] = 'CIDRAM – Akun';
$CIDRAM['lang']['title_config'] = 'CIDRAM – Konfigurasi';
$CIDRAM['lang']['title_file_manager'] = 'CIDRAM – File Manager';
$CIDRAM['lang']['title_home'] = 'CIDRAM – Halaman Utama';
$CIDRAM['lang']['title_ip_test'] = 'CIDRAM – IP Test';
$CIDRAM['lang']['title_login'] = 'CIDRAM – Masuk';
$CIDRAM['lang']['title_logs'] = 'CIDRAM – Log';
$CIDRAM['lang']['title_updates'] = 'CIDRAM – Pembaruan';

$CIDRAM['lang']['info_some_useful_links'] = 'Beberapa link yang berguna:<ul>
            <li><a href="https://github.com/Maikuolan/CIDRAM/issues">CIDRAM Issues @ GitHub</a> – Halaman masalah untuk CIDRAM (dukungan, bantuan, dll).</li>
            <li><a href="http://www.spambotsecurity.com/forum/viewforum.php?f=61">CIDRAM @ Spambot Security</a> – Forum diskusi untuk CIDRAM (dukungan, bantuan, dll).</li>
            <li><a href="https://wordpress.org/plugins/cidram/">CIDRAM @ Wordpress.org</a> – Plugin Wordpress untuk CIDRAM.</li>
            <li><a href="https://sourceforge.net/projects/cidram/">CIDRAM @ SourceForge</a> – Cermin download alternatif untuk CIDRAM.</li>
            <li><a href="https://websectools.com/">WebSecTools.com</a> – Sebuah kumpulan alat webmaster sederhana untuk mengamankan situs web.</li>
            <li><a href="https://macmathan.info/zbblock-range-blocks">MacMathan.info Range Blocks</a> – Berisi berbagai blok opsional yang dapat ditambahkan ke CIDRAM untuk memblokir setiap negara yang tidak diinginkan dari mengakses situs Anda.</li>
            <li><a href="https://www.facebook.com/groups/2204685680/">International PHP Group @ Facebook</a> – Sumber belajar dan diskusi PHP.</li>
            <li><a href="https://wwphp-fb.github.io/">International PHP Group @ GitHub</a> – Sumber belajar dan diskusi PHP.</li>
            <li><a href="http://bgp.he.net/">Hurricane Electric BGP Toolkit</a> – Dapatkan CIDRs dari ASN, menentukan hubungan ASN, menemukan ASN berdasarkan nama jaringan, dll.</li>
            <li><a href="https://www.stopforumspam.com/forum/">Forum @ Stop Forum Spam</a> – Forum diskusi yang berguna tentang menghentikan spam forum.</li>
            <li><a href="https://www.stopforumspam.com/aggregate">IP Aggregator @ Stop Forum Spam</a> – Alat agregasi berguna untuk IP IPv4.</li>
            <li><a href="https://radar.qrator.net/">Radar by Qrator</a> – Alat yang berguna untuk memeriksa konektivitas ASN serta berbagai informasi lainnya tentang ASN.</li>
            <li><a href="http://www.ipdeny.com/ipblocks/">IPdeny IP country blocks</a> – Sebuah layanan yang fantastis dan akurat untuk menghasilkan tanda tangan negara-lebar.</li>
            <li><a href="https://www.google.com/transparencyreport/safebrowsing/malware/">Google Malware Dashboard</a> – Menampilkan laporan tentang tingkat infeksi malware untuk ASN.</li>
            <li><a href="https://www.spamhaus.org/statistics/botnet-asn/">The Spamhaus Project</a> – Menampilkan laporan tentang tingkat infeksi botnet untuk ASN.</li>
            <li><a href="http://www.abuseat.org/asn.html">Abuseat.org\'s Composite Blocking List</a> – Menampilkan laporan tentang tingkat infeksi botnet untuk ASN.</li>
        </ul>';
