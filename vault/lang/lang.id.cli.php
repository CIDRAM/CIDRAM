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
 * This file: Indonesian language data for CLI (last modified: 2016.07.26).
 */

/** Prevents execution from outside of CIDRAM. */
if (!defined('CIDRAM')) {
    die('[CIDRAM] This should not be accessed directly.');
}

$CIDRAM['lang']['CLI_H'] = "
 CIDRAM modus CLI bantuan.

 Menggunakan:
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

$CIDRAM['lang']['CLI_Bad_IP'] = ' Alamat IP yang ditetapkan, "{IP}", adalah tidak valid!';
$CIDRAM['lang']['CLI_IP_Blocked'] = ' Alamat IP yang ditetapkan, "{IP}", *ADALAH* diblokir oleh satu atau lebih tanda tangan.';
$CIDRAM['lang']['CLI_IP_Not_Blocked'] = ' Alamat IP yang ditetapkan, "{IP}", adalah *TIDAK* diblokir oleh apapun tanda tangan.';

$CIDRAM['lang']['CLI_F_Finished'] = 'Fixer tanda tangan telah selesai, dengan %s perubahan dilakukan melalui %s operasi (%s).';
$CIDRAM['lang']['CLI_F_Started'] = 'Fixer tanda tangan telah dimulai (%s).';
$CIDRAM['lang']['CLI_VF_Empty'] = 'File tanda tangan ditentukan kosong atau tidak ada.';
$CIDRAM['lang']['CLI_VF_Level_0'] = 'Pemberitahuan';
$CIDRAM['lang']['CLI_VF_Level_1'] = 'Peringatan';
$CIDRAM['lang']['CLI_VF_Level_2'] = 'Kesalahan';
$CIDRAM['lang']['CLI_VF_Level_3'] = 'Kesalahan Fatal';

$CIDRAM['lang']['CLI_V_CRLF'] = 'Terdeteksi CR/CRLF dalam file tanda tangan; Ini adalah dibolehkan dan tidak akan menimbulkan masalah, tapi LF adalah direkomendasikan.';
$CIDRAM['lang']['CLI_V_Finished'] = 'Validator tanda tangan telah selesai (%s). Jika tidak ada peringatan atau kesalahan, file tanda tangan Anda kemungkinan besar baik-baik. :-)';
$CIDRAM['lang']['CLI_V_LineByLine'] = 'Validator baris demi baris telah dimulai.';
$CIDRAM['lang']['CLI_V_Started'] = 'Validator tanda tangan telah dimulai (%s).';
$CIDRAM['lang']['CLI_V_Terminal_LF'] = 'File tanda tangan harus mengakhiri dengan jeda baris LF.';

$CIDRAM['lang']['CLI_VL_CC'] = 'L%s: Karakter kontrol terdeteksi; Ini dapat mengindikasikan korupsi dan harus diselidiki.';
$CIDRAM['lang']['CLI_VL_Duplicated'] = 'L%s: Tanda tangan "%s" diduplikasi (%s jumlah)!';
$CIDRAM['lang']['CLI_VL_Expiry'] = 'L%s: Tag kadaluwarsa tidak berisi tanggal/waktu ISO 8601 valid!';
$CIDRAM['lang']['CLI_VL_Invalid'] = 'L%s: "%s" adalah *TIDAK* alamat IPv4 atau IPv6 valid!';
$CIDRAM['lang']['CLI_VL_L120'] = 'L%s: Ukuran baris adalah lebih besar dari 120 bytes; Ukuran baris harus dibatasi 120 bytes untuk dibaca optimal.';
$CIDRAM['lang']['CLI_VL_Mergeable'] = 'L%s dan L%s adalah identik, dan demikian, dapat digabungkan.';
$CIDRAM['lang']['CLI_VL_Missing'] = 'L%s: [Function] tidak ada; Tanda tangan tampaknya tidak lengkap.';
$CIDRAM['lang']['CLI_VL_Nontriggerable'] = 'L%s: "%s" tidak dapat dipicu! Basisnya tidak cocok awal untuk jangkauannya! Cobalah mengganti dengan "%s".';
$CIDRAM['lang']['CLI_VL_Nontriggerable_Range'] = 'L%s: "%s" tidak dapat dipicu! "%s" tidak rentang valid!';
$CIDRAM['lang']['CLI_VL_Subordinate'] = 'L%s: "%s" adalah bawahan untuk tanda tangan sudah ada "%s".';
$CIDRAM['lang']['CLI_VL_Superset'] = 'L%s: "%s" adalah superset untuk tanda tangan sudah ada "%s".';
$CIDRAM['lang']['CLI_VL_Syntax'] = 'L%s: Sintaksis tidak tepat.';
$CIDRAM['lang']['CLI_VL_Tabs'] = 'L%s: Tab terdeteksi; Spasi adalah direkomendasikan daripada tab untuk dibaca optimal.';
$CIDRAM['lang']['CLI_VL_Tags'] = 'L%s: Tag untuk bagian adalah lebih besar dari 20 bytes; Tag untuk bagian harus jelas dan ringkas.';
$CIDRAM['lang']['CLI_VL_Unrecognised'] = 'L%s: [Function] tidak diketahui; Tanda tangan mungkin rusak.';
$CIDRAM['lang']['CLI_VL_Whitespace'] = 'L%s: Mubazir spasi terdeteksi pada akhir baris ini.';
$CIDRAM['lang']['CLI_VL_YAML'] = 'L%s: Data seperti YAML terdeteksi, tapi tidak bisa memprosesnya.';
