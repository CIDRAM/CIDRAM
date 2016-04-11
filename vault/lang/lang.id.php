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
 * This file: Indonesian language data (last modified: 2016.04.11).
 */

/** Prevents execution from outside of CIDRAM. */
if (!defined('CIDRAM')) {
    die('[CIDRAM] This should not be accessed directly.');
}

$CIDRAM['lang']['click_here'] = 'klik disini';
$CIDRAM['lang']['denied'] = 'Akses Ditolak!';
$CIDRAM['lang']['Error_WriteCache'] = 'Tidak dapat menulis ke cache! Silakan periksa hak akses file CHMOD Anda!';
$CIDRAM['lang']['field_datetime'] = 'Tanggal/Waktu: ';
$CIDRAM['lang']['field_id'] = 'ID: ';
$CIDRAM['lang']['field_ipaddr'] = 'Alamat IP: ';
$CIDRAM['lang']['field_query'] = 'Kueri: ';
$CIDRAM['lang']['field_referrer'] = 'Halaman Mengacu: ';
$CIDRAM['lang']['field_rURI'] = 'Direkonstruksi URI: ';
$CIDRAM['lang']['field_scriptversion'] = 'Versi Skrip: ';
$CIDRAM['lang']['field_sigcount'] = 'Menghitung Tanda Tangan: ';
$CIDRAM['lang']['field_sigref'] = 'Tanda Tangan Referensi: ';
$CIDRAM['lang']['field_ua'] = 'Agen Pengguna: ';
$CIDRAM['lang']['field_whyreason'] = 'Mengapa Diblokir: ';
$CIDRAM['lang']['generated_by'] = 'Dihasilkan oleh';
$CIDRAM['lang']['preamble'] = '-- Akhir pembukaan. Tambah pertanyaan atau komentar setelah baris ini. --';
$CIDRAM['lang']['ReasonMessage_BadIP'] = 'Akses Anda ke halaman ini ditolak karena Anda mencoba untuk mengakses halaman ini menggunakan alamat IP yang tidak valid.';
$CIDRAM['lang']['ReasonMessage_Bogon'] = 'Akses Anda ke halaman ini ditolak karena alamat IP Anda diakui sebagai alamat bogon, dan menghubungkan dari bogon ke situs ini tidak diizinkan oleh pemilik situs.';
$CIDRAM['lang']['ReasonMessage_Cloud'] = 'Akses Anda ke halaman ini ditolak karena alamat IP Anda diakui sebagai milik dari layanan komputasi awan, dan menghubungkan dari layanan komputasi awan ke situs ini tidak diizinkan oleh pemilik situs.';
$CIDRAM['lang']['ReasonMessage_Generic'] = 'Akses Anda ke halaman ini ditolak karena alamat IP Anda milik dari jaringan terdaftar pada daftar hitam yang digunakan oleh situs ini.';
$CIDRAM['lang']['ReasonMessage_Spam'] = 'Akses Anda ke halaman ini ditolak karena alamat IP Anda milik dari jaringan dianggap berisiko tinggi untuk spam.';
$CIDRAM['lang']['Short_BadIP'] = 'IP tidak valid!';
$CIDRAM['lang']['Short_Bogon'] = 'IP yang bogon';
$CIDRAM['lang']['Short_Cloud'] = 'Layanan komputasi awan';
$CIDRAM['lang']['Short_Generic'] = 'Umum';
$CIDRAM['lang']['Short_Spam'] = 'Risiko spam';
$CIDRAM['lang']['Support_Email'] = 'Jika Anda yakin ini adalah kesalahan, atau untuk mencari bantuan, {ClickHereLink} untuk mengirim tiket dukungan email ke webmaster dari situs ini (silahkan jangan mengubah pembukaan atau baris subjek email).';

$CIDRAM['lang']['CLI_H'] = "
 CIDRAM CLI-mode help.

 Usage:
 /jalan/ke/php/php.exe /jalan/ke/cidram/loader.php -Flag (Masukkan)

 Flag:  -h  Menampilkan informasi bantuan ini.
        -c  Periksa apakah alamat IP diblokir dengan file tanda tangan CIDRAM.
        -g  Menghasilkan CIDR dari alamat IP.

 Masukkan:  Dapat menjadi apa-apa alamat IPv4 atau IPv6 valid.

 Contoh:
        -c  192.168.0.0/16
        -c  127.0.0.1/32
        -c  2001:db8::/32
        -c  2002::1/128

";

$CIDRAM['lang']['CLI_Bad_IP'] = ' Alamat IP yang ditetapkan, "{IP}", yang tidak valid!';
$CIDRAM['lang']['CLI_IP_Blocked'] = ' Alamat IP yang ditetapkan, "{IP}", *YANG* diblokir oleh satu atau lebih tanda tangan.';
$CIDRAM['lang']['CLI_IP_Not_Blocked'] = ' Alamat IP yang ditetapkan, "{IP}", yang *TIDAK* diblokir oleh apapun tanda tangan.';

$CIDRAM['lang']['CLI_F_Finished'] = 'Signature fixer has finished, with %s changes made over %s operations (%s).';
$CIDRAM['lang']['CLI_F_Started'] = 'Signature fixer has started (%s).';
$CIDRAM['lang']['CLI_VF_Empty'] = 'Specified signature file is empty or doesn\'t exist.';
$CIDRAM['lang']['CLI_VF_Level_0'] = 'Pemberitahuan';
$CIDRAM['lang']['CLI_VF_Level_1'] = 'Peringatan';
$CIDRAM['lang']['CLI_VF_Level_2'] = 'Kesalahan';
$CIDRAM['lang']['CLI_VF_Level_3'] = 'Kesalahan Fatal';

$CIDRAM['lang']['CLI_V_CRLF'] = 'Detected CR/CRLF in signature file; These are permissible and won\'t cause problems, but LF is preferable.';
$CIDRAM['lang']['CLI_V_Finished'] = 'Signature validator has finished (%s). If no warnings or errors have appeared, your signature file is *probably* okay. :-)';
$CIDRAM['lang']['CLI_V_LineByLine'] = 'Line-by-line validation has started.';
$CIDRAM['lang']['CLI_V_Started'] = 'Signature validator has started (%s).';
$CIDRAM['lang']['CLI_V_Terminal_LF'] = 'Signature files should terminate with an LF linebreak.';

$CIDRAM['lang']['CLI_VL_CC'] = 'L%s: Control characters detected; This could indicate corruption and should be investigated.';
$CIDRAM['lang']['CLI_VL_Expiry'] = 'L%s: Expiry tag doesn\'t contain a valid ISO 8601 date/time!';
$CIDRAM['lang']['CLI_VL_L120'] = 'L%s: Line length is greater than 120 bytes; Line length should be limited to 120 bytes for optimal readability.';
$CIDRAM['lang']['CLI_VL_Mergeable'] = 'L%s and L%s are identical, and thus, mergeable.';
$CIDRAM['lang']['CLI_VL_Syntax'] = 'L%s: Not syntactically precise.';
$CIDRAM['lang']['CLI_VL_Tabs'] = 'L%s: Tabs detected; Spaces are preferred over tabs for optimal readability.';
$CIDRAM['lang']['CLI_VL_Tags'] = 'L%s: Section tag is greater than 20 bytes; Section tags should be clear and concise.';
$CIDRAM['lang']['CLI_VL_Whitespace'] = 'L%s: Excess trailing whitespace detected on this line.';
