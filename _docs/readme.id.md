## Dokumentasi untuk CIDRAM (Bahasa Indonesia).

### Isi
- 1. [SEPATAH KATA](#SECTION1)
- 2. [BAGAIMANA CARA MENGINSTAL](#SECTION2)
- 3. [BAGAIMANA CARA MENGGUNAKAN](#SECTION3)
- 4. [MANAJEMEN BAGIAN DEPAN](#SECTION4)
- 5. [FILE YANG DIIKUTKAN DALAM PAKET INI](#SECTION5)
- 6. [OPSI KONFIGURASI](#SECTION6)
- 7. [FORMAT TANDA TANGAN](#SECTION7)
- 8. [MASALAH KOMPATIBILITAS DIKETAHUI](#SECTION8)
- 9. [PERTANYAAN YANG SERING DIAJUKAN (FAQ)](#SECTION9)
- 10. *Dicadangkan untuk penambahan dokumentasi di masa mendatang.*
- 11. [INFORMASI HUKUM](#SECTION11)

*Catatan tentang terjemahan: Dalam hal kesalahan (misalnya, perbedaan antara terjemahan, kesalahan cetak, dll), versi bahasa Inggris dari README dianggap versi asli dan berwibawa. Jika Anda menemukan kesalahan, bantuan Anda dalam mengoreksi mereka akan disambut.*

---


### 1. <a name="SECTION1"></a>SEPATAH KATA

CIDRAM (Classless Inter-Domain Routing Access Manager) adalah skrip PHP dirancang untuk melindungi situs oleh memblokir permintaan-permintaan berasal dari alamat IP yang dianggap sumber lalu lintas yang tidak diinginkan, termasuk (tapi tidak terbatas pada) lalu lintas dari jalur akses yang tidak manusia, layanan cloud, spambots, pencakar/scrapers, dll. Hal ini dilakukan melalui menghitung kisaran CIDR alamat IP dipasok dari permintaan dan mencoba untuk mencocokkan ini kisaran CIDR terhadap file tanda tangan (file tanda tangan ini berisi daftar CIDR alamat IP dianggap sumber lalu lintas yang tidak diinginkan); Jika dicocokkan, permintaan yang diblokir.

*(Lihat: [Apa yang "CIDR"?](#WHAT_IS_A_CIDR)).*

CIDRAM HAK CIPTA 2016 dan di atas GNU/GPLv2 oleh Caleb M (Maikuolan).

Skrip ini adalah perangkat lunak gratis; Anda dapat mendistribusikan kembali dan/atau memodifikasinya dalam batasan dari GNU General Public License, seperti di publikasikan dari Free Software Foundation; baik versi 2 dari License, atau (dalam opsi Anda) versi selanjutnya apapun. Skrip ini didistribusikan untuk harapan dapat digunakan tapi TANPA JAMINAN; tanpa walaupun garansi dari DIPERJUALBELIKAN atau KECOCOKAN UNTUK TUJUAN TERTENTU. Mohon Lihat GNU General Public Licence untuk lebih detail, terletak di file `LICENSE.txt` dan tersedia juga dari:
- <http://www.gnu.org/licenses/>.
- <http://opensource.org/licenses/>.

Dokumen ini dan paket terhubung di dalamnya dapat di unduh secara gratis dari [GitHub](https://cidram.github.io/).

---


### 2. <a name="SECTION2"></a>BAGAIMANA CARA MENGINSTAL

#### 2.0 MENGINSTAL SECARA MANUAL

1) Dengan membaca ini, Saya asumsikan Anda telah mengunduh dan menyimpan copy dari skrip, membuka data terkompres dan isinya dan Anda meletakkannya pada mesin komputer lokal Anda. Dari sini, Anda akan latihan dimana di host Anda atau CMS Anda untuk meletakkan isi data terkompres nya. Sebuah direktori seperti `/public_html/cidram/` atau yang lain (walaupun tidak masalah Anda memilih direktori apa, selama dia aman dan dimana pun yang Anda senangi) akan mencukupi. *Sebelum Anda mulai upload, mohon baca dulu..*

2) Mengubah file nama `config.ini.RenameMe` ke `config.ini` (berada di dalam `vault`), dan secara fakultatif (sangat direkomendasikan untuk user dengan pengalaman lebih lanjut, tapi tidak untuk pemula atau yang tidak berpengalaman), membukanya (file ini berisikan semua opsi operasional yang tersedia untuk CIDRAM; di atas tiap opsi seharusnya ada komentar tegas menguraikan tentang apa yang dilakukan dan untuk apa). Atur opsi-opsi ini seperti Anda lihat cocok, seperti apapun yang cocok untuk setup tertentu. Simpan file, menutupnya.

3) Upload isi (CIDRAM dan file-filenya) ke direktori yang telah kamu putuskan sebelumnya (Anda tidak memerlukan file-file `*.txt`/`*.md`, tapi kebanyakan Anda harus mengupload semuanya).

4) Gunakan perinta CHMOD ke direktori `vault` dengan "755" (jika ada masalah, Anda dapat mencoba "777", tapi ini kurang aman). Direktori utama menyimpan isinya (yang Anda putuskan sebelumnya), umumnya dapat di biarkan sendirian, tapi status perintah "CHMOD" seharusnya di cek jika kamu punya izin di sistem Anda (defaultnya, seperti "755").

5) Selanjutnya Anda perlu menghubungkan CIDRAM ke sistem atau CMS. Ada beberapa cara yang berbeda untuk menghubungkan skrip seperti CIDRAM ke sistem atau CMS, tapi yang paling mudah adalah memasukkan skrip pada permulaan dari file murni dari sistem atau CMS (satu yang akan secara umum di muat ketika seseorang mengakses halaman apapun pada situs web) berdasarkan pernyataan `require` atau `include`. Umumnya, ini akan menjadi sesuatu yang disimpan di sebuah direktori seperti `/includes`, `/assets` atau `/functions` dan akan selalu di namai sesuatu seperti `init.php`, `common_functions.php`, `functions.php` atau yang sama. Anda harus bekerja pada file apa untuk situasi ini; Jika Anda mengalami kesulitan dalam menentukan ini untuk diri sendiri, kunjungi halaman issues (issues) CIDRAM di GitHub untuk bantuan. Untuk melakukannya [menggunakan `require` atau `include`], sisipkan baris kode dibawah pada file murni, menggantikan kata-kata berisikan didalam tanda kutip dari alamat file `loader.php` (alamat lokal, tidak alamat HTTP; akan terlihat seperti alamat vault yang di bicarakan sebelumnya).

`<?php require '/user_name/public_html/cidram/loader.php'; ?>`

Simpan file dan tutup. Upload kembali.

-- ATAU ALTERNATIF --

Jika Anda menggunakan webserver Apache dan jika Anda memiliki akses ke `php.ini`, Anda dapat menggunakan `auto_prepend_file` direktif untuk tambahkan CIDRAM setiap kali ada permintaan PHP dibuat. Sesuatu seperti:

`auto_prepend_file = "/user_name/public_html/cidram/loader.php"`

Atau ini di file `.htaccess`:

`php_value auto_prepend_file "/user_name/public_html/cidram/loader.php"`

6) Itu semuanya! :-)

#### 2.1 MENGINSTAL DENGAN COMPOSER

[CIDRAM terdaftar dengan Packagist](https://packagist.org/packages/cidram/cidram). Jika Anda akrab dengan Composer, Anda dapat menggunakan Composer untuk menginstal CIDRAM (Anda masih perlu mempersiapkan konfigurasi dan kait meskipun; melihat "menginstal secara manual" langkah 2 dan 5).

`composer require cidram/cidram`

#### 2.2 MENGINSTAL UNTUK WORDPRESS

Jika Anda ingin menggunakan CIDRAM dengan WordPress, Anda dapat mengabaikan semua petunjuk di atas. [CIDRAM terdaftar sebagai plugin dengan database plugin WordPress](https://wordpress.org/plugins/cidram/), dan Anda dapat menginstal CIDRAM langsung dari plugin dashboard. Anda dapat menginstalnya dengan cara yang sama seperti plugin lainnya, dan tidak ada langkah-langkah selain diperlukan. Sama seperti dengan metode instalasi lain, Anda dapat menyesuaikan instalasi Anda dengan memodifikasi isi file `config.ini` atau dengan menggunakan akses bagian depan halaman konfigurasi. Jika Anda mengaktifkan bagian depan CIDRAM dan memperbarui CIDRAM menggunakan akses bagian depan halaman pembaruan, ini secara otomatis akan sinkron dengan informasi versi plugin ditampilkan di plugin dashboard.

*Peringatan: Memperbarui CIDRAM melalui dashboard plugin menghasilkan instalasi yang bersih! Jika Anda telah menyesuaikan instalasi Anda (mengubah konfigurasi, modul terinstal, dll), kustomisasi ini akan hilang saat memperbarui melalui dashboard plugin! File log juga akan hilang saat memperbarui melalui dashboard plugin! Untuk menyimpan file log dan kustomisasi, perbarui melalui halaman pembaruan bagian depan CIDRAM.*

---


### 3. <a name="SECTION3"></a>BAGAIMANA CARA MENGGUNAKAN

CIDRAM harus secara otomatis memblokir permintaan yang tidak diinginkan ke website Anda tanpa memerlukan bantuan manual, selain dari instalasi.

Anda dapat menyesuaikan konfigurasi Anda dan menyesuaikan apa CIDRs diblokir oleh memodifikasi file konfigurasi Anda dan/atau file tanda tangan Anda.

Jika Anda menemukan positif palsu, tolong hubungi saya untuk membiarkan saya tahu tentang hal itu. *(Lihat: [Apa yang dimaksud dengan "positif palsu"?](#WHAT_IS_A_FALSE_POSITIVE)).*

CIDRAM dapat diperbarui secara manual atau melalui bagian depan. CIDRAM juga bisa diperbarui via Composer atau WordPress, jika sudah diinstal dengan cara ini.

---


### 4. <a name="SECTION4"></a>MANAJEMEN BAGIAN DEPAN

#### 4.0 APA YANG MANAJEMEN BAGIAN DEPAN.

Manajemen bagian depan menyediakan cara yang nyaman dan mudah untuk mempertahankan, mengelola, dan memperbarui instalasi CIDRAM Anda. Anda dapat melihat, berbagi, dan download file log melalui halaman log, Anda dapat mengubah konfigurasi melalui halaman konfigurasi, Anda dapat instal dan uninstal/hapus komponen melalui halaman pembaruan, dan Anda dapat upload, download, dan memodifikasi file dalam vault Anda melalui file manager.

Bagian depan adalah dinonaktifkan secara default untuk mencegah akses yang tidak sah (akses yang tidak sah bisa memiliki konsekuensi yang signifikan untuk website Anda dan keamanannya). Instruksi untuk mengaktifkannya termasuk dibawah paragraf ini.

#### 4.1 BAGAIMANA CARA MENGAKTIFKAN MANAJEMEN BAGIAN DEPAN.

1) Menemukan direktif `disable_frontend` dalam `config.ini`, dan mengaturnya untuk `false` (akan menjadi `true` secara default).

2) Mengakses `loader.php` dari browser Anda (misalnya, `http://localhost/cidram/loader.php`).

3) Masuk dengan nama pengguna dan kata sandi default (admin/password).

Catat: Setelah Anda dimasukkan untuk pertama kalinya, untuk mencegah akses tidak sah ke manajemen bagian depan, Anda harus segera mengubah nama pengguna dan kata sandi Anda! Ini sangat penting, karena itu mungkin untuk meng-upload kode PHP sewenang-wenang untuk situs web Anda melalui bagian depan.

#### 4.2 BAGAIMANA CARA MENGGUNAKAN MANAJEMEN BAGIAN DEPAN.

Instruksi disediakan pada setiap halaman dari manajemen bagian depan, untuk menjelaskan cara yang benar untuk menggunakannya dan tujuan yang telah ditetapkan. Jika Anda membutuhkan penjelasan lebih lanjut atau bantuan khusus, silahkan hubungi dukungan, atau sebagai pilihan lain, ada beberapa video yang tersedia di YouTube yang dapat membantu dengan cara demonstrasi.


---


### 5. <a name="SECTION5"></a>FILE YANG DIIKUTKAN DALAM PAKET INI

Berikut list dari semua file yang diikutkan di dalam kopi skrip yang dikompres ketika Anda mendownloadnya, setiap file-file yang secara potensial diciptakan sebagai hasil dari menggunakan skrip ini, sejalan dengan deskripsi singkat dari untuk apa file-file ini.

Data | Deskripsi
----|----
/_docs/ | Direktori dokumentasi (berisi bermacam file).
/_docs/readme.ar.md | Dokumentasi Bahasa Arab.
/_docs/readme.de.md | Dokumentasi Bahasa Jerman.
/_docs/readme.en.md | Dokumentasi Bahasa Inggris.
/_docs/readme.es.md | Dokumentasi Bahasa Spanyol.
/_docs/readme.fr.md | Dokumentasi Bahasa Perancis.
/_docs/readme.id.md | Dokumentasi Bahasa Indonesia.
/_docs/readme.it.md | Dokumentasi Bahasa Italia.
/_docs/readme.ja.md | Dokumentasi Bahasa Jepang.
/_docs/readme.ko.md | Dokumentasi Bahasa Korea.
/_docs/readme.nl.md | Dokumentasi Bahasa Belanda.
/_docs/readme.pt.md | Dokumentasi Bahasa Portugis.
/_docs/readme.ru.md | Dokumentasi Bahasa Rusia.
/_docs/readme.ur.md | Dokumentasi Bahasa Urdu.
/_docs/readme.vi.md | Dokumentasi Bahasa Vietnam.
/_docs/readme.zh-TW.md | Dokumentasi Cina tradisional.
/_docs/readme.zh.md | Dokumentasi Cina sederhana.
/vault/ | Direktori Vault (berisikan bermacam file).
/vault/fe_assets/ | Data untuk akses bagian depan.
/vault/fe_assets/.htaccess | File akses hiperteks (pada instansi ini, untuk melindungi file-file sensitif dari skrip untuk diakses dari sumber yang tidak terautorisasi).
/vault/fe_assets/_accounts.html | Template HTML untuk halaman akun.
/vault/fe_assets/_accounts_row.html | Template HTML untuk halaman akun.
/vault/fe_assets/_cache.html | Template HTML untuk halaman data cache.
/vault/fe_assets/_cidr_calc.html | Template HTML untuk kalkulator CIDR.
/vault/fe_assets/_cidr_calc_row.html | Template HTML untuk kalkulator CIDR.
/vault/fe_assets/_config.html | Template HTML untuk halaman konfigurasi.
/vault/fe_assets/_config_row.html | Template HTML untuk halaman konfigurasi.
/vault/fe_assets/_files.html | Template HTML untuk file manager.
/vault/fe_assets/_files_edit.html | Template HTML untuk file manager.
/vault/fe_assets/_files_rename.html | Template HTML untuk file manager.
/vault/fe_assets/_files_row.html | Template HTML untuk file manager.
/vault/fe_assets/_home.html | Template HTML untuk halaman utama.
/vault/fe_assets/_ip_aggregator.html | Template HTML untuk agregator IP.
/vault/fe_assets/_ip_test.html | Template HTML untuk halaman test IP.
/vault/fe_assets/_ip_test_row.html | Template HTML untuk halaman test IP.
/vault/fe_assets/_ip_tracking.html | Template HTML untuk halaman pelacakan IP.
/vault/fe_assets/_ip_tracking_row.html | Template HTML untuk halaman pelacakan IP.
/vault/fe_assets/_login.html | Template HTML untuk halaman masuk.
/vault/fe_assets/_logs.html | Template HTML untuk halaman log.
/vault/fe_assets/_nav_complete_access.html | Template HTML untuk link navigasi, untuk mereka yang memiliki akses lengkap.
/vault/fe_assets/_nav_logs_access_only.html | Template HTML untuk link navigasi, untuk mereka yang memiliki akses ke halaman log hanya.
/vault/fe_assets/_range.html | Template HTML untuk tabel rentang.
/vault/fe_assets/_range_row.html | Template HTML untuk tabel rentang.
/vault/fe_assets/_sections.html | Template HTML untuk daftar bagian.
/vault/fe_assets/_sections_row.html | Template HTML untuk daftar bagian.
/vault/fe_assets/_statistics.html | Template HTML untuk halaman statistik.
/vault/fe_assets/_updates.html | Template HTML untuk halaman pembaruan.
/vault/fe_assets/_updates_row.html | Template HTML untuk halaman pembaruan.
/vault/fe_assets/frontend.css | Style-sheet CSS untuk akses bagian depan.
/vault/fe_assets/frontend.dat | Database untuk akses bagian depan (berisi informasi akun, informasi sesi, dan cache; hanya dihasilkan jika akses bagian depan diaktifkan dan digunakan).
/vault/fe_assets/frontend.html | Template HTML utama untuk akses bagian depan.
/vault/fe_assets/icons.php | File ikon (digunakan oleh file manager bagian depan).
/vault/fe_assets/pips.php | File pip (digunakan oleh file manager bagian depan).
/vault/fe_assets/scripts.js | Berisi data JavaScript bagian depan.
/vault/lang/ | Berisikan file bahasa.
/vault/lang/.htaccess | File akses hiperteks (pada instansi ini, untuk melindungi file-file sensitif dari skrip untuk diakses dari sumber yang tidak terautorisasi).
/vault/lang/lang.ar.cli.php | File Bahasa Arab untuk CLI.
/vault/lang/lang.ar.fe.php | File Bahasa Arab untuk bagian depan.
/vault/lang/lang.ar.php | File Bahasa Arab.
/vault/lang/lang.bn.cli.php | File Bahasa Benggala untuk CLI.
/vault/lang/lang.bn.fe.php | File Bahasa Benggala untuk bagian depan.
/vault/lang/lang.bn.php | File Bahasa Benggala.
/vault/lang/lang.de.cli.php | File Bahasa Jerman untuk CLI.
/vault/lang/lang.de.fe.php | File Bahasa Jerman untuk bagian depan.
/vault/lang/lang.de.php | File Bahasa Jerman.
/vault/lang/lang.en.cli.php | File Bahasa Inggris untuk CLI.
/vault/lang/lang.en.fe.php | File Bahasa Inggris untuk bagian depan.
/vault/lang/lang.en.php | File Bahasa Inggris.
/vault/lang/lang.es.cli.php | File Bahasa Spanyol untuk CLI.
/vault/lang/lang.es.fe.php | File Bahasa Spanyol untuk bagian depan.
/vault/lang/lang.es.php | File Bahasa Spanyol.
/vault/lang/lang.fr.cli.php | File Bahasa Perancis untuk CLI.
/vault/lang/lang.fr.fe.php | File Bahasa Perancis untuk bagian depan.
/vault/lang/lang.fr.php | File Bahasa Perancis.
/vault/lang/lang.hi.cli.php | File Bahasa Hindi untuk CLI.
/vault/lang/lang.hi.fe.php | File Bahasa Hindi untuk bagian depan.
/vault/lang/lang.hi.php | File Bahasa Hindi.
/vault/lang/lang.id.cli.php | File Bahasa Indonesia untuk CLI.
/vault/lang/lang.id.fe.php | File Bahasa Indonesia untuk bagian depan.
/vault/lang/lang.id.php | File Bahasa Indonesia.
/vault/lang/lang.it.cli.php | File Bahasa Italia untuk CLI.
/vault/lang/lang.it.fe.php | File Bahasa Italia untuk bagian depan.
/vault/lang/lang.it.php | File Bahasa Italia.
/vault/lang/lang.ja.cli.php | File Bahasa Jepang untuk CLI.
/vault/lang/lang.ja.fe.php | File Bahasa Jepang untuk bagian depan.
/vault/lang/lang.ja.php | File Bahasa Jepang.
/vault/lang/lang.ko.cli.php | File Bahasa Korea untuk CLI.
/vault/lang/lang.ko.fe.php | File Bahasa Korea untuk bagian depan.
/vault/lang/lang.ko.php | File Bahasa Korea.
/vault/lang/lang.nl.cli.php | File Bahasa Belanda untuk CLI.
/vault/lang/lang.nl.fe.php | File Bahasa Belanda untuk bagian depan.
/vault/lang/lang.nl.php | File Bahasa Belanda.
/vault/lang/lang.no.cli.php | File Bahasa Norwegia untuk CLI.
/vault/lang/lang.no.fe.php | File Bahasa Norwegia untuk bagian depan.
/vault/lang/lang.no.php | File Bahasa Norwegia.
/vault/lang/lang.pt.cli.php | File Bahasa Portugis untuk CLI.
/vault/lang/lang.pt.fe.php | File Bahasa Portugis untuk bagian depan.
/vault/lang/lang.pt.php | File Bahasa Portugis.
/vault/lang/lang.ru.cli.php | File Bahasa Rusia untuk CLI.
/vault/lang/lang.ru.fe.php | File Bahasa Rusia untuk bagian depan.
/vault/lang/lang.ru.php | File Bahasa Rusia.
/vault/lang/lang.sv.cli.php | File Bahasa Swedia untuk CLI.
/vault/lang/lang.sv.fe.php | File Bahasa Swedia untuk bagian depan.
/vault/lang/lang.sv.php | File Bahasa Swedia.
/vault/lang/lang.th.cli.php | File Bahasa Thailand untuk CLI.
/vault/lang/lang.th.fe.php | File Bahasa Thailand untuk bagian depan.
/vault/lang/lang.th.php | File Bahasa Thailand.
/vault/lang/lang.tr.cli.php | File Bahasa Turki untuk CLI.
/vault/lang/lang.tr.fe.php | File Bahasa Turki untuk bagian depan.
/vault/lang/lang.tr.php | File Bahasa Turki.
/vault/lang/lang.ur.cli.php | File Bahasa Urdu untuk CLI.
/vault/lang/lang.ur.fe.php | File Bahasa Urdu untuk bagian depan.
/vault/lang/lang.ur.php | File Bahasa Urdu.
/vault/lang/lang.vi.cli.php | File Bahasa Vietnam untuk CLI.
/vault/lang/lang.vi.fe.php | File Bahasa Vietnam untuk bagian depan.
/vault/lang/lang.vi.php | File Bahasa Vietnam.
/vault/lang/lang.zh-tw.cli.php | File Bahasa Cina tradisional untuk CLI.
/vault/lang/lang.zh-tw.fe.php | File Bahasa Cina tradisional untuk bagian depan.
/vault/lang/lang.zh-tw.php | File Bahasa Cina tradisional.
/vault/lang/lang.zh.cli.php | File Bahasa Cina sederhana untuk CLI.
/vault/lang/lang.zh.fe.php | File Bahasa Cina sederhana untuk bagian depan.
/vault/lang/lang.zh.php | File Bahasa Cina sederhana.
/vault/.htaccess | File akses hiperteks (pada instansi ini, untuk melindungi file-file sensitif dari skrip untuk diakses dari sumber yang tidak terautorisasi).
/vault/.travis.php | Digunakan oleh Travis CI untuk pengujian (tidak dibutuhkan untuk fungsi teratur dari skrip).
/vault/.travis.yml | Digunakan oleh Travis CI untuk pengujian (tidak dibutuhkan untuk fungsi teratur dari skrip).
/vault/aggregator.php | Agregator IP.
/vault/cache.dat | Cache data.
/vault/cidramblocklists.dat | File metadata untuk daftar-daftar blokir yang opsional dari Macmathan; Digunakan oleh halaman pembaruan untuk bagian depan.
/vault/cli.php | Modul CLI.
/vault/components.dat | File metadata komponen; Digunakan oleh halaman pembaruan untuk bagian depan.
/vault/config.ini.RenameMe | File konfigurasi CIDRAM; Berisi semua opsi konfigurasi dari CIDRAM, memberitahukannya apa yang harus dilakukan dan bagaimana mengoperasikannya dengan benar (mengubah nama untuk mengaktifkan).
/vault/config.php | Modul konfigurasi.
/vault/config.yaml | File default konfigurasi; Berisi nilai konfigurasi default untuk CIDRAM.
/vault/frontend.php | Modul untuk akses bagian depan.
/vault/frontend_functions.php | Modul untuk fungsi akses bagian depan.
/vault/functions.php | Modul fungsi (utama).
/vault/hashes.dat | Berisi daftar hash diterima (berkaitan dengan fitur reCAPTCHA; hanya dihasilkan jika fitur reCAPTCHA diaktifkan).
/vault/ignore.dat | File abaikan (digunakan untuk menentukan bagian tanda tangan CIDRAM harus mengabaikan).
/vault/ipbypass.dat | Berisi daftar bypass IP (berkaitan dengan fitur reCAPTCHA; hanya dihasilkan jika fitur reCAPTCHA diaktifkan).
/vault/ipv4.dat | File tanda tangan IPv4 (layanan cloud tidak diinginkan dan jalur akses non-manusia).
/vault/ipv4_bogons.dat | File tanda tangan IPv4 (CIDR bogon/martian).
/vault/ipv4_custom.dat.RenameMe | File tanda tangan IPv4 disesuaikan (mengubah nama untuk mengaktifkan).
/vault/ipv4_isps.dat | File tanda tangan IPv4 (ISP berbahaya dan spam rawan).
/vault/ipv4_other.dat | File tanda tangan IPv4 (CIDR untuk proxy, VPN, dan layanan lain-lain tidak diinginkan).
/vault/ipv6.dat | File tanda tangan IPv6 (layanan cloud tidak diinginkan dan jalur akses non-manusia).
/vault/ipv6_bogons.dat | File tanda tangan IPv6 (CIDR bogon/martian).
/vault/ipv6_custom.dat.RenameMe | File tanda tangan IPv6 disesuaikan (mengubah nama untuk mengaktifkan).
/vault/ipv6_isps.dat | File tanda tangan IPv6 (ISP berbahaya dan spam rawan).
/vault/ipv6_other.dat | File tanda tangan IPv6 (CIDR untuk proxy, VPN, dan layanan lain-lain tidak diinginkan).
/vault/lang.php | File bahasa.
/vault/modules.dat | File metadata modul; Digunakan oleh halaman pembaruan untuk bagian depan.
/vault/outgen.php | Output Generator.
/vault/php5.4.x.php | Polyfill untuk PHP 5.4.X (diperlukan untuk kompatibilitas mundur PHP 5.4.X; aman untuk menghapus selama versi PHP yang lebih baru).
/vault/recaptcha.php | Modul reCAPTCHA.
/vault/rules_as6939.php | File aturan disesuaikan untuk AS6939.
/vault/rules_softlayer.php | File aturan disesuaikan untuk Soft Layer.
/vault/rules_specific.php | File aturan disesuaikan untuk beberapa CIDR spesifik.
/vault/salt.dat | File garam (digunakan oleh beberapa fungsi periferal; hanya dihasilkan jika diperlukan).
/vault/template_custom.html | File template; File template untuk output diproduksi HTML oleh CIDRAM output generator.
/vault/template_default.html | File template; File template untuk output diproduksi HTML oleh CIDRAM output generator.
/vault/themes.dat | File metadata tema; Digunakan oleh halaman pembaruan untuk bagian depan.
/.gitattributes | Sebuah file proyek GitHub (tidak dibutuhkan untuk fungsi teratur dari skrip).
/Changelog.txt | Sebuah rekaman dari perubahan yang dibuat pada skrip ini di antara perbedaan versi (tidak dibutuhkan untuk fungsi teratur dari skrip).
/composer.json | Informasi untuk Composer/Packagist (tidak dibutuhkan untuk fungsi teratur dari skrip).
/CONTRIBUTING.md | Informasi tentang cara berkontribusi pada proyek.
/LICENSE.txt | Salinan lisensi GNU/GPLv2 (tidak dibutuhkan untuk fungsi teratur dari skrip).
/loader.php | Pemuat/Loader. Ini yang apa Anda ingin masukkan (utama)!
/README.md | Ringkasan informasi proyek.
/web.config | Sebuah file konfigurasi ASP.NET (dalam instansi ini, untuk melindungi direktori `/vault` dari pengaksesan oleh sumber-sumber tidak terauthorisasi dalam kejadian yang mana skrip ini diinstal pada server berbasis teknologi ASP.NET).

---


### 6. <a name="SECTION6"></a>OPSI KONFIGURASI
Berikut list variabel yang ditemukan pada file konfigurasi CIDRAM `config.ini`, dengan deskripsi dari tujuan dan fungsi.

#### "general" (Kategori)
Konfigurasi umum dari CIDRAM.

"logfile"
- File yang dapat dibaca oleh manusia untuk mencatat semua upaya akses diblokir. Spesifikasikan nama file, atau biarkan kosong untuk menonaktifkan.

"logfileApache"
- File yang dalam gaya Apache untuk mencatat semua upaya akses diblokir. Spesifikasikan nama file, atau biarkan kosong untuk menonaktifkan.

"logfileSerialized"
- File serial untuk mencatat semua upaya akses diblokir. Spesifikasikan nama file, atau biarkan kosong untuk menonaktifkan.

*Tip berguna: Jika Anda mau, Anda dapat menambahkan informasi tanggal/waktu untuk nama-nama file log Anda oleh termasuk ini dalam nama: `{yyyy}` untuk tahun lengkap, `{yy}` untuk tahun disingkat, `{mm}` untuk bulan, `{dd}` untuk hari, `{hh}` untuk jam.*

*Contoh:*
- *`logfile='logfile.{yyyy}-{mm}-{dd}-{hh}.txt'`*
- *`logfileApache='access.{yyyy}-{mm}-{dd}-{hh}.txt'`*
- *`logfileSerialized='serial.{yyyy}-{mm}-{dd}-{hh}.txt'`*

"truncate"
- Memotong file log ketika mereka mencapai ukuran tertentu? Nilai adalah ukuran maksimum dalam B/KB/MB/GB/TB yang bisa ditambahkan untuk file log sebelum dipotong. Nilai default 0KB menonaktifkan pemotongan (file log dapat tumbuh tanpa batas waktu). Catat: Berlaku untuk file log individu! Ukuran file log tidak dianggap secara kolektif.

"log_rotation_limit"
- Rotasi log membatasi jumlah file log yang seharusnya ada pada satu waktu. Ketika file log baru dibuat, jika jumlah total file log melebihi batas yang ditentukan, tindakan yang ditentukan akan dilakukan. Anda dapat menentukan batas yang diinginkan disini. Nilai 0 akan menonaktifkan rotasi log.

"log_rotation_action"
- Rotasi log membatasi jumlah file log yang seharusnya ada pada satu waktu. Ketika file log baru dibuat, jika jumlah total file log melebihi batas yang ditentukan, tindakan yang ditentukan akan dilakukan. Anda dapat menentukan tindakan yang diinginkan disini. Delete = Hapus file log tertua, hingga batasnya tidak lagi terlampaui. Archive = Pertama arsipkan, lalu hapus file log tertua, hingga batasnya tidak lagi terlampaui.

*Klarifikasi teknis: Dalam konteks ini, "tertua" berarti tidak dimodifikasi baru-baru.*

"timeOffset"
- Jika waktu server Anda tidak cocok waktu lokal Anda, Anda dapat menentukan offset sini untuk menyesuaikan informasi tanggal/waktu dihasilkan oleh CIDRAM sesuai dengan kebutuhan Anda. Ini umumnya direkomendasikan sebagai gantinya untuk menyesuaikan direktif zona waktu dalam file `php.ini` Anda, tapi terkadang (seperti ketika bekerja dengan terbatas penyedia shared hosting) ini tidak selalu mungkin untuk melakukan, dan demikian, opsi ini disediakan disini. Offset adalah dalam menit.
- Contoh (untuk menambahkan satu jam): `timeOffset=60`

"timeFormat"
- Format notasi tanggal/waktu yang digunakan oleh CIDRAM. Default = `{Day}, {dd} {Mon} {yyyy} {hh}:{ii}:{ss} {tz}`.

"ipaddr"
- Dimana menemukan alamat IP dari permintaan alamat? (Bergunak untuk pelayanan-pelayanan seperti Cloudflare dan sejenisnya). Default = REMOTE_ADDR. PERINGATAN: Jangan ganti ini kecuali Anda tahu apa yang Anda lakukan!

Nilai yang disarankan untuk "ipaddr":

Nilai | Menggunakan
---|---
`HTTP_INCAP_CLIENT_IP` | Incapsula reverse proxy.
`HTTP_CF_CONNECTING_IP` | Cloudflare reverse proxy.
`CF-Connecting-IP` | Cloudflare reverse proxy (alternatif; jika di atas tidak bekerja).
`HTTP_X_FORWARDED_FOR` | Cloudbric reverse proxy.
`X-Forwarded-For` | [Squid reverse proxy](http://www.squid-cache.org/Doc/config/forwarded_for/).
*Ditetapkan oleh konfigurasi server.* | [Nginx reverse proxy](https://www.nginx.com/resources/admin-guide/reverse-proxy/).
`REMOTE_ADDR` | Tidak ada reverse proxy (nilai default).

"forbid_on_block"
- Pesan status HTTP mana yang harus dikirim oleh CIDRAM ketika memblokir permintaan?

Nilai yang didukung saat ini:

Kode status | Pesan status | Deskripsi
---|---|---
`200` | `200 OK` | Nilai default. Paling tidak kuat, tetapi paling ramah kepada pengguna.
`403` | `403 Forbidden` | Lebih kuat, tetapi kurang ramah kepada pengguna.
`410` | `410 Gone` | Dapat menyebabkan masalah ketika mencoba menyelesaikan kesalahan positif, karena beberapa browser akan menyimpan pesan status ini dan tidak mengirim permintaan lagi, bahkan setelah membuka blokir pengguna. Mungkin lebih berguna daripada opsi lain untuk mengurangi permintaan dari jenis bot tertentu yang sangat spesifik.
`418` | `418 I'm a teapot` | Sebenarnya referensi lelucon "bodoh april" [[RFC 2324](https://tools.ietf.org/html/rfc2324#section-6.5.14)] dan tidak mungkin dipahami oleh klien. Disediakan untuk hiburan dan kenyamanan, tetapi tidak direkomendasikan secara umum.
`451` | `Unavailable For Legal Reasons` | Sesuai untuk konteks ketika permintaan diblokir terutama karena alasan hukum. Tidak direkomendasikan dalam konteks lain.
`503` | `Service Unavailable` | Paling kuat, tetapi paling tidak ramah kepada pengguna.

"silent_mode"
- Seharusnya CIDRAM diam-diam mengarahkan diblokir upaya akses bukannya menampilkan halaman "Akses Ditolak"? Jika ya, menentukan lokasi untuk mengarahkan diblokir upaya akses. Jika tidak, kosongkan variabel ini.

"lang"
- Tentukan bahasa default untuk CIDRAM.

"numbers"
- Menentukan bagaimana menampilkan nomor-nomor.

Nilai yang didukung saat ini:

Nilai | Menghasilkan | Deskripsi
---|---|---
`NoSep-1` | `1234567.89`
`NoSep-2` | `1234567,89`
`Latin-1` | `1,234,567.89` | Nilai default.
`Latin-2` | `1 234 567.89`
`Latin-3` | `1.234.567,89`
`Latin-4` | `1 234 567,89`
`Latin-5` | `1,234,567·89`
`China-1` | `123,4567.89`
`India-1` | `12,34,567.89`
`India-2` | `१२,३४,५६७.८९`
`Bengali-1` | `১২,৩৪,৫৬৭.৮৯`
`Arabic-1` | `١٢٣٤٥٦٧٫٨٩`
`Arabic-2` | `١٬٢٣٤٬٥٦٧٫٨٩`
`Thai-1` | `๑,๒๓๔,๕๖๗.๘๙`

*Catat: Nilai-nilai ini tidak terstandardisasi dimana pun, dan mungkin tidak akan relevan di luar paket. Juga, nilai yang didukung dapat berubah di masa depan.*

"emailaddr"
- Jika Anda ingin, Anda dapat menyediakan alamat email sini untuk diberikan kepada pengguna ketika diblokir, bagi mereka untuk menggunakan sebagai metode kontak untuk dukungan dan/atau bantuan untuk dalam hal mereka menjadi diblokir keliru atau diblokir oleh kesalahan. PERINGATAN: Apapun alamat email Anda menyediakan sini akan pasti diperoleh oleh spambots dan pencakar/scrapers ketika digunakan disini, dan karena itu, jika Anda ingin memberikan alamat email disini, itu sangat direkomendasikan Anda memastikan bahwa alamat email yang Anda berikan disini adalah alamat yang dapat dibuang dan/atau adalah alamat Anda tidak keberatan menjadi di-spam (dengan kata lain, Anda mungkin tidak ingin untuk menggunakan Anda alamat email yang personal primer atau bisnis primer).

"emailaddr_display_style"
- Bagaimana Anda lebih suka alamat email yang akan disajikan kepada pengguna? "default" = Link yang dapat diklik. "noclick" = Teks yang tidak dapat diklik.

"disable_cli"
- Menonaktifkan modus CLI? Modus CLI diaktifkan secara default, tapi kadang-kadang dapat mengganggu alat pengujian tertentu (seperti PHPUnit, sebagai contoh) dan aplikasi CLI berbasis lainnya. Jika Anda tidak perlu menonaktifkan modus CLI, Anda harus mengabaikan direktif ini. False = Mengaktifkan modus CLI [Default]; True = Menonaktifkan modus CLI.

"disable_frontend"
- Menonaktifkan akses bagian depan? Akses bagian depan dapat membuat CIDRAM lebih mudah dikelola, tapi juga dapat menjadi potensial resiko keamanan. Itu direkomendasi untuk mengelola CIDRAM melalui bagian belakang bila mungkin, tapi akses bagian depan yang disediakan untuk saat itu tidak mungkin. Memilikinya dinonaktifkan kecuali jika Anda membutuhkannya. False = Mengaktifkan akses bagian depan; True = Menonaktifkan akses bagian depan [Default].

"max_login_attempts"
- Jumlah maksimum upaya untuk memasukkan (bagian depan). Default = 5.

"FrontEndLog"
- File untuk mencatat upaya login untuk bagian depan. Spesifikasikan nama file, atau biarkan kosong untuk menonaktifkan.

"ban_override"
- Mengesampingkan "forbid_on_block" ketika "infraction_limit" adalah melampaui? Ketika mengesampingkan: Permintaan diblokir menghasilkan halaman kosong (file template tidak digunakan). 200 = Jangan mengesampingkan [Default]. Nilai lainnya sama dengan nilai yang tersedia untuk "forbid_on_block".

"log_banned_ips"
- Termasuk permintaan diblokir dari IP dilarang dalam file log? True = Ya [Default]; False = Tidak.

"default_dns"
- Sebuah daftar dipisahkan dengan koma dari server DNS yang digunakan untuk pencarian nama host. Default = "8.8.8.8,8.8.4.4" (Google DNS). PERINGATAN: Jangan ganti ini kecuali Anda tahu apa yang Anda lakukan!

*Lihat juga: [Apa yang bisa saya gunakan untuk "default_dns"?](#WHAT_CAN_I_USE_FOR_DEFAULT_DNS)*

"search_engine_verification"
- Mencoba memverifikasi permintaan dari mesin pencari? Verifikasi mesin pencari memastikan bahwa mereka tidak akan dilarang sebagai akibat dari melebihi batas pelanggaran (melarang mesin pencari dari situs web Anda biasanya akan memiliki efek negatif pada peringkat mesin pencari Anda, SEO, dll). Ketika diverifikasi, mesin pencari dapat diblokir seperti biasa, tapi tidak akan dilarang. Ketika tidak diverifikasi, itu mungkin bagi mereka untuk dilarang sebagai akibat dari melebihi batas pelanggaran. Juga, verifikasi mesin pencari memberikan proteksi terhadap permintaan mesin pencari palsu dan terhadap entitas yang berpotensi berbahaya yang menyamar sebagai mesin pencari (permintaan tersebut akan diblokir ketika verifikasi mesin pencari diaktifkan). True = Mengaktifkan verifikasi mesin pencari [Default]; False = Menonaktifkan verifikasi mesin pencari.

Didukung sekarang:
- [Google](https://support.google.com/webmasters/answer/80553?hl=en)
- [Bing](https://blogs.bing.com/webmaster/2012/08/31/how-to-verify-that-bingbot-is-bingbot)
- Yahoo
- [Baidu (百度)](https://help.baidu.com/question?prod_en=master&class=Baiduspider)
- [Yandex (Яндекс)](https://yandex.com/support/webmaster/robot-workings/check-yandex-robots.xml)
- [DuckDuckGo](https://duckduckgo.com/duckduckbot)

"social_media_verification"
- Mencoba memverifikasi permintaan media sosial? Verifikasi media sosial memberikan perlindungan terhadap permintaan media sosial palsu (permintaan semacam ini akan diblokir). True = Mengaktifkan verifikasi media sosial [Default]; False = Mengaktifkan verifikasi media sosial.

Didukung sekarang:
- [Pinterest](https://help.pinterest.com/en/articles/about-pinterest-crawler-0)
- Embedly
- [Grapeshot](https://www.grapeshot.com/crawler/)

"protect_frontend"
- Menentukan apakah perlindungan biasanya disediakan oleh CIDRAM harus diterapkan pada bagian depan. True = Ya [Default]; False = Tidak.

"disable_webfonts"
- Menonaktifkan webfonts? True = Ya [Default]; False = Tidak.

"maintenance_mode"
- Aktifkan modus perawatan? True = Ya; False = Tidak [Default]. Nonaktifkan semuanya selain bagian depan. Terkadang berguna saat memperbarui CMS, kerangka kerja, dll.

"default_algo"
- Mendefinisikan algoritma mana yang akan digunakan untuk semua password dan sesi di masa depan. Opsi: PASSWORD_DEFAULT (default), PASSWORD_BCRYPT, PASSWORD_ARGON2I (membutuhkan PHP >= 7.2.0).

"statistics"
- Lacak statistik penggunaan CIDRAM? True = Ya; False = Tidak [Default].

"force_hostname_lookup"
- Memaksa periksa untuk nama host? True = Ya; False = Tidak [Default]. Periksa untuk nama host biasanya dilakukan pada dasar "sesuai kebutuhan", tapi bisa dipaksakan untuk semua permintaan. Melakukan hal tersebut mungkin berguna sebagai sarana untuk memberikan informasi lebih rinci di log, tapi mungkin juga memiliki sedikit efek negatif pada kinerja.

"allow_gethostbyaddr_lookup"
- Izinkan menggunakan gethostbyaddr saat UDP tidak tersedia? True = Ya [Default]; False = Tidak.
- *Catat: Pencarian IPv6 mungkin tidak bekerja dengan benar pada beberapa sistem 32-bit.*

"hide_version"
- Sembunyikan informasi versi dari log dan output halaman? True = Ya; False = Tidak [Default].

#### "signatures" (Kategori)
Konfigurasi untuk tanda tangan.

"ipv4"
- Daftar file tanda tangan IPv4 yang CIDRAM harus berusaha untuk menggunakan, dipisahkan dengan koma. Anda dapat menambahkan entri disini jika Anda ingin memasukkan file-file tambahan untuk CIDRAM.

"ipv6"
- Daftar file tanda tangan IPv6 yang CIDRAM harus berusaha untuk menggunakan, dipisahkan dengan koma. Anda dapat menambahkan entri disini jika Anda ingin memasukkan file-file tambahan untuk CIDRAM.

"block_cloud"
- Memblokir CIDR yang diidentifikasi sebagai milik webhosting dan/atau layanan cloud? Jika Anda mengoperasikan layanan API dari website Anda atau jika Anda mengharapkan website lain untuk menghubungkan ke website Anda, direktif ini harus didefinisikan untuk false/palsu. Jika Anda tidak, maka, direktif ini harus didefinisikan untuk true/benar.

"block_bogons"
- Memblokir CIDR bogon/martian? Jika Anda mengharapkan koneksi ke website Anda dari dalam jaringan lokal Anda, dari localhost, atau dari LAN Anda, direktif ini harus didefinisikan untuk false/palsu. Jika Anda tidak mengharapkan ini, direktif ini harus didefinisikan untuk true/benar.

"block_generic"
- Memblokir CIDR umumnya direkomendasikan untuk mendaftar hitam? Ini mencakup tanda tangan apapun yang tidak ditandai sebagai bagian dari apapun lainnya kategori tanda tangan lebih spesifik.

"block_legal"
- Memblokir CIDR sebagai respons terhadap kewajiban hukum? Direktif ini seharusnya tidak memiliki efek apapun, karena CIDRAM tidak menghubungkan CIDR apapun dengan "kewajiban hukum" secara default, tetapi tetap ada sebagai ukuran kontrol tambahan untuk kepentingan file tanda tangan atau modul dipersonalisasi yang mungkin ada karena alasan hukum.

"block_malware"
- Memblokir IP yang terkait dengan malware? Ini termasuk server C&C, mesin yang terinfeksi, mesin yang terlibat dalam distribusi malware, dll.

"block_proxies"
- Memblokir CIDR yang diidentifikasi sebagai milik layanan proxy atau VPN? Jika Anda membutuhkan bahwa pengguna dapat mengakses situs web Anda dari layanan proxy atau VPN, direktif ini harus didefinisikan untuk false/palsu. Jika Anda tidak membutuhkannya, direktif ini harus didefinisikan untuk true/benar sebagai sarana untuk meningkatkan keamanan.

"block_spam"
- Memblokir CIDR yang diidentifikasi sebagai beresiko tinggi karena spam? Kecuali jika Anda mengalami masalah ketika melakukan itu, umumnya, ini harus selalu didefinisikan untuk true/benar.

"modules"
- Daftar file modul untuk memuat setelah memeriksa tanda tangan IPv4/IPv6, dipisahkan dengan koma.

"default_tracktime"
- Berapa banyak detik untuk melacak IP dilarang oleh modul. Default = 604800 (1 seminggu).

"infraction_limit"
- Jumlah maksimum pelanggaran IP diperbolehkan untuk dikenakan sebelum dilarang oleh pelacakan IP. Default = 10.

"track_mode"
- Kapan sebaiknya pelanggaran dihitung? False = Ketika IP adalah diblokir oleh modul. True = Ketika IP adalah diblokir untuk alasan apapun.

#### "recaptcha" (Kategori)
Jika Anda ingin, Anda dapat memberikan pengguna dengan cara untuk memotong halaman "Akses Ditolak" dengan cara menyelesaikan instansi reCAPTCHA. Ini dapat membantu untuk mengurangi beberapa risiko terkait dengan positif palsu dalam situasi dimana kita tidak sepenuhnya yakin apakah permintaan telah berasal dari mesin atau manusia.

Karena risiko yang terkait dengan menyediakan cara bagi pengguna untuk melewati halaman "Akses Ditolak", umumnya, saya tidak akan menyarankan mengaktifkan fitur ini kecuali jika Anda merasa itu perlu untuk melakukannya. Situasi dimana bisa jadi diperlukan: Jika situs Web Anda memiliki pelanggan/pengguna yang perlu memiliki akses ke situs web Anda, dan jika ini adalah sesuatu yang tidak bisa dikompromikan, tapi jika pelanggan/pengguna Anda menghubungkan dari jaringan bermusuhan yang berpotensi juga membawa lalu lintas yang tidak diinginkan, dan memblokir lalu lintas yang tidak diinginkan ini juga sesuatu yang tidak bisa dikompromikan, pada mereka khususnya situasi tidak-menang, fitur reCAPTCHA ini bisa berguna sebagai sarana yang memungkinkan pelanggan/pengguna diinginkan, sambil menjaga keluar lalu lintas yang tidak diinginkan dari jaringan sama. Yang menyatakan meskipun, mengingat bahwa tujuan yang dimaksudkan dari CAPTCHA adalah untuk membedakan antara manusia dan non-manusia, fitur reCAPTCHA ini hanya akan membantu dalam situasi tidak-menang ini jika kita berasumsi bahwa lalu lintas yang tidak diinginkan ini adalah non-manusia (misalnya, spambot, scraper, alat peretas, lalu lintas otomatis), bukannya lalu lintas manusia yang tidak diinginkan (seperti spammer manusia, hacker, dan lain-lain).

Untuk mendapatkan "site key" dan "secret key" (diperlukan untuk menggunakan reCAPTCHA), silahkan pergi ke: [https://developers.google.com/recaptcha/](https://developers.google.com/recaptcha/)

"usemode"
- Mendefinisikan bagaimana CIDRAM harus menggunakan reCAPTCHA.
- 0 = reCAPTCHA benar-benar dinonaktifkan (default).
- 1 = reCAPTCHA diaktifkan untuk semua tanda tangan.
- 2 = reCAPTCHA diaktifkan hanya untuk tanda tangan bagian yang khusus ditandai sebagai diaktifkan untuk reCAPTCHA dalam file tanda tangan.
- (Nilai lain akan diperlakukan dengan cara sama sebagai 0).

"lockip"
- Menyatakan apakah hash harus dikunci untuk IP spesifik. False = Cookie dan hash DAPAT digunakan oleh banyak IP (default). True = Cookie dan hash TIDAK dapat digunakan oleh banyak IP (cookie/hash adalah terkunci ke IP).
- Catat: Nilai "lockip" diabaikan ketika "lockuser" adalah false, karena mekanisme untuk mengingat "pengguna" berbeda tergantung pada nilai ini.

"lockuser"
- Menyatakan apakah berhasil menyelesaikan instansi reCAPTCHA harus dikunci untuk pengguna spesifik. False = Berhasil menyelesaikan instansi reCAPTCHA akan memberikan akses ke semua permintaan yang berasal dari IP digunakan oleh pengguna yang menyelesaikan instansi reCAPTCHA; Cookie dan hash tidak digunakan; Sebaliknya, sebuah daftar putih IP akan digunakan. True = Berhasil menyelesaikan instansi reCAPTCHA hanya akan memberikan akses kepada pengguna yang menyelesaikan instansi reCAPTCHA; Cookie dan hash digunakan untuk mengingat pengguna; Daftar putih IP tidak digunakan (default).

"sitekey"
- Nilai ini harus sesuai dengan "site key" untuk reCAPTCHA Anda, yang dapat ditemukan dalam dashboard reCAPTCHA.

"secret"
- Nilai ini harus sesuai dengan "secret key" untuk reCAPTCHA Anda, yang dapat ditemukan dalam dashboard reCAPTCHA.

"expiry"
- Ketika "lockuser" digunakan (default), untuk mengingat ketika pengguna telah berhasil melewati instansi reCAPTCHA, untuk permintaan halaman dalam masa depan, CIDRAM akan menghasilkan cookie HTTP standar yang berisi hash yang sesuai dengan catatan internal yang yang berisi hash yang sama; permintaan halaman dalam masa depan akan menggunakan hash ini yang sesuai untuk mengotentikasi bahwa pengguna sebelumnya sudah berhasil melewati instansi reCAPTCHA. Ketika "lockuser" adalah false, sebuah daftar putih IP digunakan untuk menentukan apakah permintaan harus diizinkan dari IP dari permintaan masuk; Entri ditambahkan ke daftar putih ini ketika instansi reCAPTCHA adalah berhasil melewati. Untuk berapa jam harus cookie, hash dan entri daftar putih ini tetap berlaku? Default = 720 (1 bulan).

"logfile"
- Mencatat hasil semua instansi reCAPTCHA? Jika ya, tentukan nama untuk menggunakan untuk file catatan. Jika tidak, variabel ini harus kosong.

*Tip berguna: Jika Anda mau, Anda dapat menambahkan informasi tanggal/waktu untuk nama-nama file log Anda oleh termasuk ini dalam nama: `{yyyy}` untuk tahun lengkap, `{yy}` untuk tahun disingkat, `{mm}` untuk bulan, `{dd}` untuk hari, `{hh}` untuk jam.*

*Contoh:*
- *`logfile='recaptcha.{yyyy}-{mm}-{dd}-{hh}.txt'`*

"signature_limit"
- Jumlah maksimum tanda tangan diizinkan untuk dipicu saat instansi reCAPTCHA harus ditawarkan. Default = 1. Jika nomor ini terlampaui untuk permintaan tertentu, instansi reCAPTCHA tidak akan ditawarkan.

"api"
- API mana yang akan digunakan? V2 atau Invisible?

*Catat untuk pengguna di Uni Eropa: Saat CIDRAM dikonfigurasi untuk menggunakan cookie (mis., ketika "lockuser" true/benar), peringatan cookie ditampilkan secara mencolok di halaman sesuai persyaratan [hukum-hukum cookie UE](http://ec.europa.eu/ipg/basics/legal/cookies/index_en.htm). Namun, saat menggunakan API invisible, CIDRAM berupaya menyelesaikan reCAPTCHA untuk pengguna secara otomatis, dan bila berhasil, ini bisa mengakibatkan halaman menjadi reload dan cookie dibuat tanpa pengguna diberi waktu yang cukup untuk benar-benar melihat peringatan cookie. Jika ini menimbulkan risiko hukum bagi Anda, mungkin lebih baik menggunakan API V2 dan bukan API invisible (API V2 tidak otomatis, dan mengharuskan pengguna menyelesaikan tantangan reCAPTCHA sendiri, sehingga memberikan kesempatan untuk melihat peringatan cookie).*

#### "legal" (Kategori)
Konfigurasi yang berkaitan dengan persyaratan hukum.

*Untuk informasi lebih lanjut tentang persyaratan hukum dan bagaimana ini dapat mempengaruhi persyaratan konfigurasi Anda, silahkan lihat bagian "[LEGAL INFORMATION](#SECTION11)" pada dokumentasi.*

"pseudonymise_ip_addresses"
- Pseudonymise alamat IP saat menulis file log? True = Ya; False = Tidak [Default].

"omit_ip"
- Jangan memasukkan alamat IP di log? True = Ya; False = Tidak [Default]. Catat: "pseudonymise_ip_addresses" menjadi tidak perlu ketika "omit_ip" adalah "true".

"omit_hostname"
- Jangan memasukkan nama host di log? True = Ya; False = Tidak [Default].

"omit_ua"
- Jangan memasukkan agen pengguna di log? True = Ya; False = Tidak [Default].

"privacy_policy"
- Alamat dari kebijakan privasi yang relevan untuk ditampilkan di footer dari setiap halaman yang dihasilkan. Spesifikasikan URL, atau biarkan kosong untuk menonaktifkan.

#### "template_data" (Kategori)
Direktif-direktif dan variabel-variabel untuk template-template dan tema-tema.

Berkaitan dengan HTML digunakan untuk menghasilkan halaman "Akses Ditolak". Jika Anda menggunakan tema kustom untuk CIDRAM, HTML diproduksi yang bersumber dari file `template_custom.html`, dan sebaliknya, HTML diproduksi yang bersumber dari file `template.html`. Variabel ditulis untuk file konfigurasi bagian ini yang diurai untuk HTML diproduksi dengan cara mengganti nama-nama variabel dikelilingi dengan kurung keriting ditemukan dalam HTML diproduksi dengan file variabel sesuai. Sebagai contoh, dimana `foo="bar"`, setiap terjadinya `<p>{foo}</p>` ditemukan dalam HTML diproduksi akan menjadi `<p>bar</p>`.

"theme"
- Tema default untuk CIDRAM.

"Magnification"
- Perbesaran font. Default = 1.

"css_url"
- File template untuk tema kustom menggunakan properti CSS eksternal, sedangkan file template untuk tema default menggunakan properti CSS internal. Untuk menginstruksikan CIDRAM menggunakan file template untuk tema kustom, menentukan alamat HTTP publik file CSS tema kustom Anda menggunakan variable `css_url`. Jika Anda biarkan kosong variabel ini, CIDRAM akan menggunakan file template untuk tema default.

---


### 7. <a name="SECTION7"></a>FORMAT TANDA TANGAN

*Lihat juga:*
- *[Apa yang "tanda tangan"?](#WHAT_IS_A_SIGNATURE)*

#### 7.0 DASAR-DASAR (UNTUK FILE TANDA TANGAN)

Deskripsi untuk format dan struktur digunakan oleh tanda tangan dari CIDRAM dapat ditemukan didokumentasikan dalam teks biasa dalam apapun dari dua file-file tanda tangan kustom. Silahkan lihat dokumentasi ini untuk mempelajari lebih tentang format dan struktur digunakan oleh tanda tangan dari CIDRAM.

Semua tanda tangan IPv4 mengikuti format: `xxx.xxx.xxx.xxx/yy [Function] [Param]`.
- `xxx.xxx.xxx.xxx` merupakan awal dari blok CIDR (oktet dari alamat IP pertama dalam blok).
- `yy` merupakan ukuran dari blok CIDR [1-32].
- `[Function]` menginstruksikan skrip apa yang harus dilakukan dengan tanda tangan (bagaimana tanda tangan harus dianggap).
- `[Param]` merupakan apapun informasi tambahan mungkin diperlukan oleh `[Function]`.

Semua tanda tangan IPv6 mengikuti format: `xxxx:xxxx:xxxx:xxxx::xxxx/yy [Function] [Param]`.
- `xxxx:xxxx:xxxx:xxxx::xxxx` merupakan awal dari blok CIDR (oktet dari alamat IP pertama dalam blok). Notasi lengkap dan notasi disingkat keduanya diterima (dan masing-masing HARUS mengikuti standar tepat dan relevan dari notasi IPv6, tapi dengan satu pengecualian: alamat IPv6 tidak pernah dapat dimulai dengan singkatan bila digunakan dalam tanda tangan untuk skrip ini, karena cara dimana CIDR-CIDR direkonstruksi oleh skrip ini; Sebagai contoh, `::1/128` harus diungkapkan, bila digunakan dalam tanda tangan, sebagai `0::1/128`, dan `::0/128` diungkapkan sebagai `0::/128`).
- `yy` merupakan ukuran dari blok CIDR [1-128].
- `[Function]` menginstruksikan skrip apa yang harus dilakukan dengan tanda tangan (bagaimana tanda tangan harus dianggap).
- `[Param]` merupakan apapun informasi tambahan mungkin diperlukan oleh `[Function]`.

Jeda baris yang gaya Unix (`%0A`, or `\n`) DIREKOMENDASIKAN untuk file tanda tangan dalam CIDRAM. Jenis/Gaya lain (misalnya, jeda baris yang gaya Windows `%0D%0A` atau `\r\n`, jeda baris yang gaya Mac `%0D` atau `\r`, dll) MUNGKIN digunakan, tapi TIDAK disukai. Setiap jeda baris yang tidak gaya Unix akan dinormalisasi untuk jeda baris yang gaya unix oleh skrip ini.

Notasi CIDR tepat dan benar diperlukan; Jika tidak digunakan, skrip TIDAK akan mengenali tanda tangan. Selain itu, semua tanda tangan CIDR dari skrip ini HARUS dimulai dengan alamat IP yang nomor IP dapat membagi secara merata ke divisi blok diwakili oleh ukuran dari blok CIDR-nya (misalnya, jika Anda ingin memblokir semua IP dari `10.128.0.0` ke `11.127.255.255`, `10.128.0.0/8` akan TIDAK diakui oleh skrip ini, tapi `10.128.0.0/9` dan `11.0.0.0/9` digunakan bersama, AKAN diakui oleh skrip ini).

Apapun dalam file tanda tangan tidak diakui sebagai tanda tangan atau sebagai sintaks terkait tanda tangan oleh skrip ini akan DIABAIKAN, oleh karena itu berarti Anda dapat dengan aman menempatkan setiap data lain yang Anda inginkan ke dalam file tanda tangan tanpa melanggar mereka dan tanpa melanggar skrip. Komentar yang diterima dalam file tanda tangan, dan tidak ada format khusus diperlukan untuk mereka. Hash yang gaya Shell untuk komentar lebih disukai, tapi tidak ditegakkan; Fungsional, tidak ada bedanya untuk skrip apakah Anda memilih menggunakan hash gaya Shell untuk komentar, tapi menggunakan hash yang gaya Shell membantu IDE dan editor teks biasa untuk benar menyoroti berbagai bagian dari file tanda tangan (dan sebagainya, hash yang gaya Shell dapat membantu sebagai bantuan visual saat mengedit).

Kemungkinan nilai-nilai `[Function]` adalah sebagai berikut:
- Run
- Whitelist
- Greylist
- Deny

Jika "Run" digunakan, ketika tanda tangan dipicu, skrip akan mencoba untuk mengeksekusi (menggunakan `require_once` pernyataan) eksternal skrip PHP, ditentukan oleh nilai dari `[Param]` (direktori kerja harus direktori "/vault/" skrip ini).

Contoh: `127.0.0.0/8 Run example.php`

Hal ini dapat berguna jika Anda ingin mengeksekusi beberapa kode PHP yang spesifik untuk beberapa IP dan/atau CIDR tertentu.

Jika "Whitelist" digunakan, ketika tanda tangan dipicu, skrip akan mengatur ulang semua pendeteksian (jika sudah ada setiap pendeteksian) dan istirahat fungsi tes. `[Param]` diabaikan. Fungsi ini setara dengan membolehkan akses untuk IP tertentu atau CIDR.

Contoh: `127.0.0.1/32 Whitelist`

Jika "Greylist" digunakan, ketika tanda tangan dipicu, skrip akan mengatur ulang semua pendeteksian (jika sudah ada setiap pendeteksian) dan melompat ke file tanda tangan berikutnya untuk melanjutkan proses. `[Param]` diabaikan.

Contoh: `127.0.0.1/32 Greylist`

Jika "Deny" digunakan, ketika tanda tangan dipicu, dengan asumsi tidak ada tanda tangan daftar putih telah memicu untuk alamat IP yang diberikan dan/atau CIDR yang diberikan, akses ke halaman dilindungi akan ditolak. "Deny" adalah apa yang akan Anda ingin menggunakan untuk benar-benar memblokir alamat IP dan/atau CIDR. Ketika setiap tanda tangan dipicu yang memanfaatkan "Deny", halaman skrip "Akses Ditolak" akan dihasilkan dan permintaan untuk halaman dilindungi akan dihentikan.

Nilai `[Param]` diterima oleh "Deny" akan diurai ke output halaman "Akses Ditolak", dipasok ke klien/pengguna sebagai alasan dikutip untuk akses mereka ke halaman yang diminta ditolak. Ini bisa menjadi kalimat pendek dan sederhana, menjelaskan mengapa Anda memilih untuk memblokir mereka (apapun harus cukup, bahkan pesan sederhana "Saya tidak ingin Anda di website saya"), atau satu dari segelintir kecil kata-kata pendek disediakan oleh skrip, bahwa jika digunakan, akan digantikan oleh skrip dengan penjelasan pra-siap mengapa klien/pengguna diblokir.

Penjelasan pra-siap memiliki dukungan L10N dan dapat diterjemahkan oleh skrip berdasarkan bahasa yang Anda tentukan untuk direktif `lang` dari konfigurasi skrip. Selain itu, Anda dapat menginstruksikan skrip untuk mengabaikan tanda tangan "Deny" berdasarkan mereka nilai dari `[Param]` (jika mereka menggunakan kata-kata singkat) melalui direktif-direktif yang ditentukan oleh konfigurasi skrip (setiap kata singkat memiliki direktif yang sesuai untuk memproses sesuai tanda tangan atau mengabaikannya). Nilai dari `[Param]` yang tidak menggunakan kata-kata singkat, namun, tidak memiliki dukungan L10N dan karena itu TIDAK akan diterjemahkan oleh skrip ini, dan tambahan, tidak bisa langsung dikontrol oleh konfigurasi skrip.

Kata-kata singkat yang tersedia adalah:
- Bogon
- Cloud
- Generic
- Proxy
- Spam
- Legal
- Malware

#### 7.1 TAG

##### 7.1.0 TAG BAGIAN

Jika Anda ingin membagi tanda tangan kustom Anda ke bagian individual, Anda dapat mengidentifikasi bagian individual untuk skrip dengan menambahkan "tag bagian" segera setelah tanda tangan dari setiap bagian, bersama dengan nama bagian tanda tangan Anda (lihat contoh dibawah ini).

```
# Bagian 1.
1.2.3.4/32 Deny Bogon
2.3.4.5/32 Deny Cloud
4.5.6.7/32 Deny Generic
5.6.7.8/32 Deny Spam
6.7.8.9/32 Deny Proxy
Tag: Bagian 1
```

Untuk mematahkan tag bagian dan untuk memastikan bahwa tag tidak salah mengidentifikasi untuk bagian tanda tangan dari sebelumnya dalam file tanda tangan, hanya memastikan bahwa setidaknya ada dua jeda baris berturut-turut antara tag Anda dan bagian tanda tangan sebelumnya Anda. Apapun tanda tangan tidak di-tag akan default untuk "IPv4" atau "IPv6" (tergantung pada jenis tanda tangan yang dipicu).

```
1.2.3.4/32 Deny Bogon
2.3.4.5/32 Deny Cloud

4.5.6.7/32 Deny Generic
5.6.7.8/32 Deny Spam
Tag: Bagian 1
```

Dalam contoh di atas `1.2.3.4/32` dan `2.3.4.5/32` akan di-tag sebagai "IPv4", sedangkan `4.5.6.7/32` dan `5.6.7.8/32` akan di-tag sebagai "Bagian 1".

Logika sama ini dapat diterapkan untuk memisahkan jenis tag lainnya juga.

Secara khusus, tag bagian bisa sangat berguna untuk debugging bila terjadi positif palsu, dengan menyediakan sarana mudah untuk menemukan sumber masalah yang tepat, dan bisa sangat berguna untuk menyaring entri file log saat melihat file log melalui halaman log depan (nama bagian dapat diklik melalui halaman log depan dan dapat digunakan sebagai kriteria penyaringan). Jika tag bagian diabaikan untuk beberapa tanda tangan tertentu, saat tanda tangan dipicu, CIDRAM menggunakan nama file tanda tangan beserta jenis alamat IP yang diblokir (IPv4 atau IPv6) sebagai fallback, dan demikian, tag bagian sepenuhnya opsional. Mereka mungkin akan merekomendasikan dalam beberapa kasus meskipun, seperti ketika file tanda tangan diberi nama samar-samar atau bila mungkin sulit untuk secara jelas mengidentifikasi sumber tanda tangan yang menyebabkan permintaan diblokir.

##### 7.1.1 TAG KADALUARSA

Jika Anda ingin tanda tangan untuk berakhir setelah beberapa waktu, dengan cara yang sama untuk tag bagian, Anda dapat menggunakan "tag kadaluarsa" untuk menentukan kapan tanda tangan harus berhenti menjadi valid. Tag kadaluarsa menggunakan format "TTTT.BB.HH" (lihat contoh dibawah ini).

```
# Bagian 1.
1.2.3.4/32 Deny Generic
2.3.4.5/32 Deny Generic
Expires: 2016.12.31
```

Tanda tangan kadaluarsa tidak akan pernah dipicu pada permintaan apapun, tidak masalah apa.

##### 7.1.2 TAG ASAL

Jika Anda ingin menentukan negara asal untuk beberapa tanda tangan tertentu, Anda dapat melakukannya dengan menggunakan "tag asal". Tag asal menerima kode "[ISO 3166-1 Alpha-2](https://id.wikipedia.org/wiki/ISO_3166-1)" yang sesuai dengan negara asal untuk tanda tangan yang berlaku. Kode ini harus ditulis dalam huruf besar (huruf kecil atau huruf campuran tidak akan ditampilkan dengan benar). Saat tag asal digunakan, ditambahkan ke entri lapangan log "Mengapa Diblokir" untuk setiap permintaan yang diblokir akibat tanda tangan yang diterapkan tag.

Jika komponen "flags CSS" opsional diinstal, saat melihat logfile melalui halaman log depan, informasi yang ditambahkan oleh tag asal akan diganti dengan bendera negara yang sesuai dengan informasi tersebut. Informasi ini, apakah dalam bentuk mentah atau sebagai bendera negara, dapat diklik, dan saat diklik, akan menyaring entri log dengan cara entri serupa lainnya (secara efektif membiarkan mereka mengakses halaman log untuk menyaring dengan cara negara asal).

Catat: Secara teknis, ini bukan geolokasi, karena ini tidak melibatkan pencarian informasi spesifik terkait dengan IP, namun hanya memungkinkan kita untuk secara eksplisit menyatakan negara asal untuk setiap permintaan yang diblokir oleh tanda tangan tertentu. Lebih dari satu tag asal diperbolehkan di bagian tanda tangan sama.

Contoh hipotetis:

```
1.2.3.4/32 Deny Generic
Origin: CN
2.3.4.5/32 Deny Generic
Origin: FR
4.5.6.7/32 Deny Generic
Origin: DE
6.7.8.9/32 Deny Generic
Origin: US
Tag: Foobar
```

Tag apapun dapat digunakan bersamaan, dan semua tag bersifat opsional (lihat contoh dibawah ini).

```
# Contoh Bagian.
1.2.3.4/32 Deny Generic
Origin: US
Tag: Contoh Bagian
Expires: 2016.12.31
```

#### 7.2 YAML

##### 7.2.0 DASAR-DASAR YAML

Sebuah bentuk sederhana YAML markup dapat digunakan dalam file tanda tangan untuk tujuan perilaku mendefinisikan dan direktif spesifik untuk bagian tanda tangan individu. Ini mungkin berguna jika Anda ingin nilai direktif konfigurasi berbeda atas dasar tanda tangan individu dan bagian tanda tangan (sebagai contoh; jika Anda ingin memberikan alamat email untuk tiket dukungan untuk setiap pengguna diblokir oleh satu tanda tangan tertentu, tapi tidak ingin memberikan alamat email untuk tiket dukungan untuk pengguna diblokir oleh tanda tangan lain; jika Anda ingin beberapa tanda tangan spesifik untuk memicu halaman redireksi; jika Anda ingin menandai bagian tanda tangan untuk digunakan dengan reCAPTCHA; jika Anda ingin merekam diblokir upaya akses untuk memisahkan file berdasarkan tanda tangan individu dan/atau bagian tanda tangan).

Penggunaan YAML markup dalam file tanda tangan sepenuhnya opsional (yaitu, Anda dapat menggunakannya jika Anda ingin melakukannya, tapi Anda tidak diharuskan untuk melakukannya), dan mampu memanfaatkan kebanyakan (tapi tidak semua) direktif konfigurasi.

Catat: Implementasi markup YAML di CIDRAM sangat sederhana dan sangat terbatas; Hal ini dimaksudkan untuk memenuhi kebutuhan spesifik untuk CIDRAM dengan cara yang memiliki keakraban dari YAML markup, tapi tidak mengikuti dengan spesifikasi resmi (dan karena itu tidak akan berperilaku dalam cara sama seperti implementasi yang lebih menyeluruh di tempat lain, dan mungkin tidak sesuai untuk proyek-proyek lain di tempat lain).

Dalam CIDRAM, segmen markup YAML diidentifikasi untuk skrip oleh tiga tanda hubung ("---"), dan mengakhiri dengan mengandung bagian tanda tangan mereka oleh dua jeda baris. Segmen markup YAML dalam bagian tanda tangan terdiri dari tiga tanda hubung pada baris segera setelah daftar CIDRs dan apapun tag, diikuti dengan daftar dua dimensi dari pasangan kunci-nilai (dimensi pertama, kategori direktif konfigurasi; dimensi kedua, direktif konfigurasi) untuk mana direktif konfigurasi yang harus dimodifikasi (dan yang nilai-nilai) setiap kali tanda tangan dalam yang bagian tanda tangan dipicu (lihat contoh dibawah ini).

```
# Foobar 1.
1.2.3.4/32 Deny Generic
2.3.4.5/32 Deny Generic
4.5.6.7/32 Deny Generic
Tag: Foobar 1
---
general:
 logfile: logfile.{yyyy}-{mm}-{dd}.txt
 logfileApache: access.{yyyy}-{mm}-{dd}.txt
 logfileSerialized: serial.{yyyy}-{mm}-{dd}.txt
 forbid_on_block: false
 emailaddr: username@domain.tld
recaptcha:
 lockip: false
 lockuser: true
 expiry: 720
 logfile: recaptcha.{yyyy}-{mm}-{dd}.txt
 enabled: true
template_data:
 css_url: http://domain.tld/cidram.css

# Foobar 2.
1.2.3.4/32 Deny Generic
2.3.4.5/32 Deny Generic
4.5.6.7/32 Deny Generic
Tag: Foobar 2
---
general:
 logfile: "logfile.Foobar2.{yyyy}-{mm}-{dd}.txt"
 logfileApache: "access.Foobar2.{yyyy}-{mm}-{dd}.txt"
 logfileSerialized: "serial.Foobar2.{yyyy}-{mm}-{dd}.txt"
 forbid_on_block: 503

# Foobar 3.
1.2.3.4/32 Deny Generic
2.3.4.5/32 Deny Generic
4.5.6.7/32 Deny Generic
Tag: Foobar 3
---
general:
 forbid_on_block: 403
 silent_mode: "http://127.0.0.1/"
```

##### 7.2.1 BAGAIMANA "KHUSUS MENANDAI" BAGIAN TANDA TANGAN UNTUK DIGUNAKAN DENGAN reCAPTCHA

Ketika "usemode" 0 atau 1, bagian tanda tangan tidak perlu "khusus ditandai" untuk digunakan dengan reCAPTCHA (karena mereka telah akan atau tidak akan menggunakan reCAPTCHA, tergantung pada pengaturan ini).

Ketika "usemode" 2, untuk "khusus menandai" bagian tanda tangan untuk digunakan dengan reCAPTCHA, entri termasuk dalam segmen YAML untuk bagian tanda tangan (lihat contoh dibawah ini).

```
# Bagian ini akan menggunakan reCAPTCHA.
1.2.3.4/32 Deny Generic
2.3.4.5/32 Deny Generic
Tag: reCAPTCHA-Enabled
---
recaptcha:
 enabled: true
```

*Catat: Secara default, instansi reCAPTCHA akan HANYA ditawarkan kepada pengguna jika reCAPTCHA diaktifkan (dengan "usemode" sebagai 1, atau "usemode" sebagai 2 dengan "enabled" sebagai true), dan jika tepat SATU tanda tangan dipicu (tidak lebih, tidak kurang; jika kelipatan tanda tangan dipicu, instansi reCAPTCHA TIDAK akan ditawarkan). Namun, perilaku ini dapat dimodifikasi melalui direktif "signature_limit".*

#### 7.3 INFORMASI TAMBAHAN

Juga, jika Anda ingin CIDRAM untuk sama sekali mengabaikan beberapa bagian tertentu dalam salah satu file tanda tangan, Anda dapat menggunakan file `ignore.dat` untuk menentukan bagian untuk mengabaikan. Pada baris baru, menulis `Ignore`, diikuti dengan spasi, diikuti dengan nama bagian yang Anda ingin CIDRAM untuk mengabaikan (lihat contoh dibawah ini).

```
Ignore Bagian 1
```

#### 7.4 <a name="MODULE_BASICS"></a>DASAR-DASAR (UNTUK MODUL)

Modul dapat digunakan untuk memperluas fungsionalitas CIDRAM, melakukan tugas tambahan, atau memproses logika tambahan. Biasanya, mereka digunakan saat perlu memblokir permintaan untuk alasan selain alamat IP (dan dengan demikian, ketika tanda tangan CIDR tidak cukup untuk memblokir permintaan). Modul ditulis sebagai file PHP, dan dengan demikian, biasanya, tanda tangan modul ditulis sebagai kode PHP.

Beberapa contoh bagus untuk modul CIDRAM dapat ditemukan disini:
- https://github.com/CIDRAM/CIDRAM-Extras/tree/master/modules

Template untuk menulis modul baru dapat ditemukan disini:
- https://github.com/CIDRAM/CIDRAM-Extras/blob/master/modules/module_template.php

Karena modul ditulis sebagai file PHP, jika Anda cukup mengenal basis kode CIDRAM, Anda dapat menyusun modul Anda namun Anda inginkan, dan menulis tanda tangan modul Anda namun Anda inginkan (dalam batasan untuk apa yang mungkin di PHP). Namun, untuk kenyamanan Anda sendiri, dan demi saling pengertian antara modul yang ada dan modul Anda sendiri, menganalisis template yang terhubung di atas direkomendasikan, agar bisa menggunakan struktur dan format yang diberikannya.

*Catat: Jika Anda tidak nyaman bekerja dengan kode PHP, menulis modul Anda sendiri tidak disarankan.*

Beberapa fungsi disediakan oleh CIDRAM yang dapat digunakan oleh modul, yang seharusnya membuatnya lebih sederhana dan mudah untuk menulis modul Anda sendiri. Informasi tentang fungsi ini dijelaskan dibawah ini.

#### 7.5 FUNGSIONALITAS MODUL

##### 7.5.0 "$Trigger"

Tanda tangan modul biasanya ditulis dengan "$Trigger". Dalam kebanyakan kasus, closure ini akan lebih penting daripada hal lain untuk tujuan penulisan modul.

"$Trigger" menerima 4 parameter: "$Condition", "$ReasonShort", "$ReasonLong" (opsional), dan "$DefineOptions" (opsional).

Kebenaran dari "$Condition" dievaluasi, dan jika true/benar, tanda tangan "dipicu". Jika false/salah, tanda tangan *tidak* "dipicu". "$Condition" biasanya berisi kode PHP untuk mengevaluasi suatu kondisi yang harus menyebabkan permintaan diblokir.

"$ReasonShort" dikutip di bidang "Mengapa Diblokir" saat tanda tangan "dipicu".

"$ReasonLong" adalah pesan opsional yang akan ditampilkan kepada pengguna/klien saat mereka diblokir, untuk menjelaskan mengapa mereka diblokir. Default ke pesan "Akses Ditolak" standar saat dihilangkan.

"$DefineOptions" adalah array opsional yang berisi pasangan kunci/nilai, digunakan untuk menentukan opsi konfigurasi yang spesifik untuk instance permintaan. Opsi konfigurasi akan diterapkan saat tanda tangan "dipicu".

"$Trigger" kembali true/benar saat tanda tangan "dipicu", dan false/salah saat tidak.

Untuk menggunakan closure ini di modul Anda, ingat dulu untuk mewarisi dari lingkup luar:
```PHP
$Trigger = $CIDRAM['Trigger'];
```

##### 7.5.1 "$Bypass"

Tanda tangan bypass biasanya ditulis dengan "$Bypass".

"$Bypass" menerima 3 parameter: "$Condition", "$ReasonShort", dan "$DefineOptions" (opsional).

Kebenaran dari "$Condition" dievaluasi, dan jika true/benar, bypass "dipicu". Jika false/salah, bypass *tidak* "dipicu". "$Condition" biasanya berisi kode PHP untuk mengevaluasi suatu kondisi yang harus *tidak* menyebabkan permintaan diblokir.

"$ReasonShort" dikutip di bidang "Mengapa Diblokir" saat bypass "dipicu".

"$DefineOptions" adalah array opsional yang berisi pasangan kunci/nilai, digunakan untuk menentukan opsi konfigurasi yang spesifik untuk instance permintaan. Opsi konfigurasi akan diterapkan saat bypass "dipicu".

"$Bypass" kembali true/benar saat bypass "dipicu", dan false/salah saat tidak.

Untuk menggunakan closure ini di modul Anda, ingat dulu untuk mewarisi dari lingkup luar:
```PHP
$Bypass = $CIDRAM['Bypass'];
```

##### 7.5.2 "$CIDRAM['DNS-Reverse']"

Ini bisa digunakan untuk mengambil nama host dari alamat IP. Jika Anda ingin membuat modul untuk memblokir nama host, closure ini bisa bermanfaat.

Contoh:
```PHP
<?php
/** Inherit trigger closure (see functions.php). */
$Trigger = $CIDRAM['Trigger'];

/** Fetch hostname. */
if (empty($CIDRAM['Hostname'])) {
    $CIDRAM['Hostname'] = $CIDRAM['DNS-Reverse']($CIDRAM['BlockInfo']['IPAddr']);
}

/** Example signature. */
if ($CIDRAM['Hostname'] && $CIDRAM['Hostname'] !== $CIDRAM['BlockInfo']['IPAddr']) {
    $Trigger($CIDRAM['Hostname'] === 'www.foobar.tld', 'Foobar.tld', 'Hostname Foobar.tld is not allowed.');
}
```

#### 7.6 MODUL VARIABEL

Modul mengeksekusi dalam lingkup mereka sendiri, dan variabel apapun yang ditentukan oleh modul, tidak akan dapat diakses ke modul lain, atau ke skrip utama, kecuali jika disimpan dalam array "$CIDRAM" (segala sesuatu yang lain dibuang setelah eksekusi modul selesai).

Tercantum dibawah ini adalah beberapa variabel umum yang mungkin berguna untuk modul Anda:

Variabel | Deskripsi
----|----
`$CIDRAM['BlockInfo']['DateTime']` | Tanggal dan waktu sekarang.
`$CIDRAM['BlockInfo']['IPAddr']` | Alamat IP untuk permintaan saat ini.
`$CIDRAM['BlockInfo']['ScriptIdent']` | Versi skrip CIDRAM.
`$CIDRAM['BlockInfo']['Query']` | "Query" untuk permintaan saat ini.
`$CIDRAM['BlockInfo']['Referrer']` | Perujuk untuk permintaan saat ini (jika ada).
`$CIDRAM['BlockInfo']['UA']` | Agen pengguna (user agent) untuk permintaan saat ini.
`$CIDRAM['BlockInfo']['UALC']` | Agen pengguna (user agent) untuk permintaan saat ini (di huruf kecil).
`$CIDRAM['BlockInfo']['ReasonMessage']` | Pesan yang akan ditampilkan ke pengguna/klien untuk permintaan saat ini jika diblokir.
`$CIDRAM['BlockInfo']['SignatureCount']` | Jumlah tanda tangan dipicu untuk permintaan saat ini.
`$CIDRAM['BlockInfo']['Signatures']` | Informasi referensi untuk tanda tangan yang dipicu untuk permintaan saat ini.
`$CIDRAM['BlockInfo']['WhyReason']` | Informasi referensi untuk tanda tangan yang dipicu untuk permintaan saat ini.

---


### 8. <a name="SECTION8"></a>MASALAH KOMPATIBILITAS DIKETAHUI

Paket dan produk berikut telah ditemukan tidak bisa dioperasikan dengan CIDRAM:
- __[Endurance Page Cache](https://github.com/CIDRAM/CIDRAM/issues/52)__

Modul telah tersedia untuk memastikan bahwa paket dan produk berikut akan kompatibel dengan CIDRAM:
- __[BunnyCDN](https://github.com/CIDRAM/CIDRAM/issues/56)__

*Lihat juga: [Bagan Kompatibilitas](https://maikuolan.github.io/Compatibility-Charts/).*

---


### 9. <a name="SECTION9"></a>PERTANYAAN YANG SERING DIAJUKAN (FAQ)

- [Apa yang "tanda tangan"?](#WHAT_IS_A_SIGNATURE)
- [Apa yang "CIDR"?](#WHAT_IS_A_CIDR)
- [Apa yang dimaksud dengan "positif palsu"?](#WHAT_IS_A_FALSE_POSITIVE)
- [Dapat CIDRAM blok seluruh negara?](#BLOCK_ENTIRE_COUNTRIES)
- [Seberapa sering tanda tangan diperbarui?](#SIGNATURE_UPDATE_FREQUENCY)
- [Saya mengalami masalah ketika menggunakan CIDRAM dan saya tidak tahu apa saya harus lakukan! Tolong bantu!](#ENCOUNTERED_PROBLEM_WHAT_TO_DO)
- [Saya diblokir oleh CIDRAM dari situs web yang saya ingin mengunjungi! Tolong bantu!](#BLOCKED_WHAT_TO_DO)
- [Saya ingin menggunakan CIDRAM dengan versi PHP yang lebih tua dari 5.4.0; Anda dapat membantu?](#MINIMUM_PHP_VERSION)
- [Dapatkah saya menggunakan satu instalasi CIDRAM untuk melindungi beberapa domain?](#PROTECT_MULTIPLE_DOMAINS)
- [Saya tidak ingin membuang waktu dengan menginstal ini dan membuatnya bekerja dengan situs web saya; Bisakah saya membayar Anda untuk melakukan semuanya untuk saya?](#PAY_YOU_TO_DO_IT)
- [Dapatkah saya mempekerjakan Anda atau pengembang proyek ini untuk pekerjaan pribadi?](#HIRE_FOR_PRIVATE_WORK)
- [Saya perlu modifikasi khusus, customisasi, dll; Apakah kamu bisa membantu?](#SPECIALIST_MODIFICATIONS)
- [Saya seorang pengembang, perancang situs web, atau programmer. Dapatkah saya menerima atau menawarkan pekerjaan yang berkaitan dengan proyek ini?](#ACCEPT_OR_OFFER_WORK)
- [Saya ingin berkontribusi pada proyek ini; Dapatkah saya melakukan ini?](#WANT_TO_CONTRIBUTE)
- [Dapatkah saya menggunakan cron untuk mengupdate secara otomatis?](#CRON_TO_UPDATE_AUTOMATICALLY)
- [Apa "pelanggaran"?](#WHAT_ARE_INFRACTIONS)
- [Dapatkah CIDRAM memblokir nama host?](#BLOCK_HOSTNAMES)
- [Apa yang bisa saya gunakan untuk "default_dns"?](#WHAT_CAN_I_USE_FOR_DEFAULT_DNS)
- [Dapatkah saya menggunakan CIDRAM untuk melindungi hal-hal selain daripada situs web (misalnya, server email, FTP, SSH, IRC, dll)?](#PROTECT_OTHER_THINGS)
- [Akankah masalah terjadi jika saya menggunakan CIDRAM pada saat yang sama dengan menggunakan layanan CDN atau cache?](#CDN_CACHING_PROBLEMS)
- [Akankah CIDRAM melindungi situs web saya dari serangan DDoS?](#DDOS_ATTACKS)
- [Ketika saya mengaktifkan atau menonaktifkan modul atau file tanda tangan melalui halaman pembaruan, itu memilah mereka secara alfanumerik dalam konfigurasi. Bisakah saya mengubah cara mereka disortir?](#CHANGE_COMPONENT_SORT_ORDER)

#### <a name="WHAT_IS_A_SIGNATURE"></a>Apa yang "tanda tangan"?

Dalam konteks CIDRAM, "tanda tangan" mengacu pada data yang bertindak sebagai indikator/pengenal untuk sesuatu spesifik yang kita mencari, biasanya alamat IP atau CIDR, dan termasuk beberapa instruksi untuk CIDRAM, mengatakannya cara terbaik untuk menanggapi saat menemukan apa yang kita mencari. Tanda tangan khas untuk CIDRAM terlihat seperti ini:

Untuk "file tanda tangan":

`1.2.3.4/32 Deny Generic`

Untuk "modul":

```PHP
$Trigger(strpos($CIDRAM['BlockInfo']['UA'], 'Foobar') !== false, 'Foobar-UA', 'User agent "Foobar" not allowed.');
```

*Catat: Tanda tangan untuk "file tanda tangan", dan tanda tangan untuk "modul", bukanlah hal yang sama.*

Sering (tapi tidak selalu), tanda tangan akan digabungkan dalam grup-grup, Membentuk "bagian tanda tangan", sering disertai dengan komentar, markup, dan/atau metadata terkait yang bisa digunakan untuk memberikan konteks tambahan untuk tanda tangan dan/atau instruksi tambahan.

#### <a name="WHAT_IS_A_CIDR"></a>Apa yang "CIDR"?

"CIDR" adalah akronim untuk "Classless Inter-Domain Routing" *[[1](https://id.wikipedia.org/wiki/CIDR), [2](http://whatismyipaddress.com/cidr)]*, dan akronim inilah yang digunakan sebagai bagian dari nama paket ini, "CIDRAM", yang merupakan akronim untuk "Classless Inter-Domain Routing Access Manager".

Namun, dalam konteks CIDRAM (seperti, dalam dokumentasi ini, dalam diskusi yang berkaitan dengan CIDRAM, atau dalam data bahasa CIDRAM), kapanpun "CIDR" (tunggal) atau "CIDRs" (jamak) yang disebutkan (dan dengan demikian kita menggunakan kata-kata ini sebagai kata benda dalam hak mereka sendiri, daripada akronim), arti dimaksud dengan ini adalah subnet, dinyatakan menggunakan notasi CIDR. Alasan untuk CIDR (atau CIDRs) adalah digunakan daripada subnet adalah untuk memperjelas bahwa kita mengacu pada subnet yang menggunakan notasi CIDR (karena notasi CIDR hanyalah satu dari sekian banyak cara yang subnet bisa diekspresikan). CIDRAM bisa, oleh karena itu, dianggap sebagai "manajer akses untuk subnet".

Meskipun arti ganda ini untuk "CIDR" mungkin menghadirkan beberapa ambiguitas dalam beberapa kasus, penjelasan ini, bersama dengan konteks yang disediakan, harus membantu menyelesaikan ambiguitas ini.

#### <a name="WHAT_IS_A_FALSE_POSITIVE"></a>Apa yang dimaksud dengan "positif palsu"?

Istilah "positif palsu" (*alternatif: "kesalahan positif palsu"; "alarm palsu"*; Bahasa Inggris: *false positive*; *false positive error*; *false alarm*), dijelaskan dengan sangat sederhana, dan dalam konteks umum, digunakan saat pengujian untuk kondisi, untuk merujuk pada hasil tes, ketika hasilnya positif (yaitu, kondisi adalah dianggap untuk menjadi "positif", atau "benar"), namun diharapkan (atau seharusnya) menjadi negatif (yaitu, kondisi ini, pada kenyataannya, adalah "negatif", atau "palsu"). Sebuah "positif palsu" bisa dianggap analog dengan "menangis serigala" (dimana kondisi dites adalah apakah ada serigala di dekat kawanan, kondisi adalah "palsu" di bahwa tidak ada serigala di dekat kawanan, dan kondisi ini dilaporkan sebagai "positif" oleh gembala dengan cara memanggil "serigala, serigala"), atau analog dengan situasi dalam pengujian medis dimana seorang pasien didiagnosis sebagai memiliki beberapa penyakit, ketika pada kenyataannya, mereka tidak memiliki penyakit tersebut.

Hasil terkait ketika pengujian untuk kondisi dapat digambarkan menggunakan istilah "positif benar", "negatif benar" dan "negatif palsu". Sebuah "positif benar" mengacu pada saat hasil tes dan keadaan sebenarnya dari kondisi adalah keduanya benar (atau "positif"), dan sebuah "negatif benar" mengacu pada saat hasil tes dan keadaan sebenarnya dari kondisi adalah keduanya palsu (atau "negatif"); Sebuah "positif benar" atau "negatif benar" adalah dianggap untuk menjadi sebuah "inferensi benar". Antitesis dari "positif palsu" adalah sebuah "negatif palsu"; Sebuah "negatif palsu" mengacu pada saat hasil tes are negatif (yaitu, kondisi adalah dianggap untuk menjadi "negatif", atau "palsu"), namun diharapkan (atau seharusnya) menjadi positif (yaitu, kondisi ini, pada kenyataannya, adalah "positif", atau "benar").

Dalam konteks CIDRAM, istilah-istilah ini mengacu pada tanda tangan dari CIDRAM dan apa/siapa mereka memblokir. Ketika CIDRAM blok alamat IP karena buruk, usang atau salah tanda tangan, tapi seharusnya tidak melakukannya, atau ketika melakukannya untuk alasan salah, kita menyebut acara ini sebuah "positif palsu". Ketika CIDRAM gagal untuk memblokir alamat IP yang seharusnya diblokir, karena ancaman tak terduga, hilang tanda tangan atau kekurangan dalam tanda tangan nya, kita menyebut acara ini sebuah "deteksi terjawab" atau "missing detection" (ini analog dengan sebuah "negatif palsu").

Ini dapat diringkas dengan tabel dibawah:

&nbsp; | CIDRAM seharusnya *TIDAK* memblokir alamat IP | CIDRAM *SEHARUSNYA* memblokir alamat IP
---|---|---
CIDRAM *TIDAK* memblokir alamat IP | Negatif benar (inferensi benar) | Deteksi terjawab (analog dengan negatif palsu)
CIDRAM memblokir alamat IP | __Positif palsu__ | Positif benar (inferensi benar)

#### <a name="BLOCK_ENTIRE_COUNTRIES"></a>Dapat CIDRAM blok seluruh negara?

Ya. Cara termudah untuk mencapai hal ini adalah untuk menginstal beberapa daftar blokir negara opsional disediakan oleh Macmathan. Ini dapat dilakukan dengan beberapa klik mudah langsung dari halaman pembaruan dalam akses bagian depan, atau, jika Anda lebih memilih akses ke bagian depan tetap dinonaktifkan, dengan men-download langsung dari **[daftar blokir opsional halaman download](https://macmathan.info/blocklists)**, meng-upload ke vault, dan mengutip nama mereka dalam direktif konfigurasi yang relevan.

#### <a name="SIGNATURE_UPDATE_FREQUENCY"></a>Seberapa sering tanda tangan diperbarui?

Frekuensi pembaruan bervariasi tergantung pada file tanda tangan. Semua penulis bagi file tanda tangan CIDRAM umumnya mencoba untuk menjaga tanda tangan mereka sebagai diperbarui sebanyak mungkin, tapi karena semua dari kita memiliki komitmen lainnya, kehidupan kita di luar proyek, dan karena kita tidak dikompensasi finansial (yaitu, dibayar) untuk upaya kami pada proyek, jadwal pembaruan yang tepat tidak dapat dijamin. Umumnya, tanda tangan diperbarui ketika ada cukup waktu untuk memperbaruinya, dan umumnya, penulis mencoba untuk memprioritaskan berdasarkan kebutuhan dan frekuensi berbagai perubahan dalam rentang. Bantuan selalu dihargai jika Anda bersedia untuk menawarkan.

#### <a name="ENCOUNTERED_PROBLEM_WHAT_TO_DO"></a>Saya mengalami masalah ketika menggunakan CIDRAM dan saya tidak tahu apa saya harus lakukan! Tolong bantu!

- Apakah Anda menggunakan versi terbaru bagi perangkat lunak? Apakah Anda menggunakan versi terbaru bagi file tanda tangan Anda? Jika jawaban untuk salah satu dari dua pertanyaan ini adalah tidak, mencoba untuk memperbarui segala sesuatu pertama, dan memeriksa apakah masalah terus berlanjut. Jika terus berlanjut, lanjutkan membaca.
- Apakah Anda memeriksa semua dokumentasi? Jika tidak, silahkan melakukannya. Jika masalah tidak dapat diselesaikan dengan menggunakan dokumentasi, lanjutkan membaca.
- Apakah Anda memeriksa **[halaman issues](https://github.com/CIDRAM/CIDRAM/issues)**, untuk melihat apakah masalah telah disebutkan sebelumnya? Jika sudah disebutkan sebelumnya, memeriksa apakah ada saran, ide, dan/atau solusi yang tersedia, dan ikuti sesuai yang diperlukan untuk mencoba untuk menyelesaikan masalah.
- Jika masalah masih berlanjut, silahkan mencari bantuan dengan membuat issue baru di halaman issues.

#### <a name="BLOCKED_WHAT_TO_DO"></a>Saya diblokir oleh CIDRAM dari situs web yang saya ingin mengunjungi! Tolong bantu!

CIDRAM menyediakan sarana bagi pemilik situs web untuk memblokir lalu lintas yang tidak diinginkan, tapi pemilik situs web bertanggung jawab untuk memutuskan bagaimana mereka ingin menggunakan CIDRAM. Dalam kasus positif palsu yang berkaitan dengan file tanda tangan yang biasanya disertakan dengan CIDRAM, koreksi dapat dibuat, tetapi dalam hal yang tidak terblokir dari situs web tertentu, Anda harus menghubungi pemilik dari situs yang bersangkutan. Dalam kasus dimana koreksi dibuat, setidaknya, mereka harus memperbarui file tanda tangan mereka dan/atau memperbarui instalasi mereka, dan dalam kasus lain (seperti, misalnya, ketika mereka diubah instalasi mereka, membuat tanda tangan kustom, dll), tanggung jawab untuk memecahkan masalah Anda sepenuhnya milik mereka, dan sepenuhnya di luar kendali kita.

#### <a name="MINIMUM_PHP_VERSION"></a>Saya ingin menggunakan CIDRAM dengan versi PHP yang lebih tua dari 5.4.0; Anda dapat membantu?

Tidak. PHP 5.4.0 mencapai EoL ("End of Life", atau Akhir Hidup) resmi pada tahun 2014, dan dukungan keamanan diperpanjang dihentikan pada tahun 2015. Sebagai menulis ini, itu adalah 2017, dan PHP 7.1.0 sudah tersedia. Pada saat ini, dukungan disediakan untuk menggunakan CIDRAM dengan PHP 5.4.0 dan semua tersedia versi PHP yang lebih baru, tapi jika Anda mencoba untuk menggunakan CIDRAM dengan versi PHP yang lebih tua, dukungan tidak akan diberikan.

*Lihat juga: [Bagan Kompatibilitas](https://maikuolan.github.io/Compatibility-Charts/).*

#### <a name="PROTECT_MULTIPLE_DOMAINS"></a>Dapatkah saya menggunakan satu instalasi CIDRAM untuk melindungi beberapa domain?

Ya. Instalasi CIDRAM tidak secara alami terkunci pada domain tertentu, dan dengan demikian dapat digunakan untuk melindungi beberapa domain. Umumnya, kami mengacu pada instalasi CIDRAM yang hanya melindungi satu domain as "instalasi domain tunggal" ("single-domain installations"), dan kami mengacu pada instalasi CIDRAM yang melindungi beberapa domain dan/atau sub-domain sebagai "instalasi domain beberapa" ("multi-domain installations"). Jika Anda mengoperasikan instalasi domain beberapa dan perlu menggunakan berbagai kumpulan file tanda tangan untuk berbagai domain, atau perlu CIDRAM untuk dikonfigurasi secara berbeda untuk domain berbeda, kamu bisa melakukan ini. Setelah memuat file konfigurasi (`config.ini`), CIDRAM akan memeriksa adanya "file untuk pengganti konfigurasi" spesifik untuk domain (atau sub-domain) yang diminta (`domain-yang-diminta.tld.config.ini`), dan jika ditemukan, setiap nilai konfigurasi yang ditentukan oleh file untuk pengganti konfigurasi akan digunakan untuk instance eksekusi daripada nilai konfigurasi yang ditentukan oleh file konfigurasi. File untuk pengganti konfigurasi identik dengan file konfigurasi, dan atas kebijaksanaan Anda, dapat berisi keseluruhan semua konfigurasi yang tersedia untuk CIDRAM, atau apapun bagian kecil yang dibutuhkan yang berbeda dari nilai yang biasanya ditentukan oleh file konfigurasi. File untuk pengganti konfigurasi diberi nama sesuai dengan domain yang mereka inginkan (jadi, misalnya, jika Anda memerlukan file untuk pengganti konfigurasi untuk domain, `http://www.some-domain.tld/`, file untuk pengganti konfigurasi harus diberi nama sebagai `some-domain.tld.config.ini`, dan harus ditempatkan di dalam vault bersama file konfigurasi, `config.ini`). Nama domain untuk instance eksekusi berasal dari header permintaan `HTTP_HOST`; "www" diabaikan.

#### <a name="PAY_YOU_TO_DO_IT"></a>Saya tidak ingin membuang waktu dengan menginstal ini dan membuatnya bekerja dengan situs web saya; Bisakah saya membayar Anda untuk melakukan semuanya untuk saya?

Mungkin. Ini dipertimbangkan berdasarkan kasus per kasus. Beritahu kami apa yang Anda butuhkan, apa yang Anda tawarkan, dan kami akan memberitahu Anda jika kami dapat membantu.

#### <a name="HIRE_FOR_PRIVATE_WORK"></a>Dapatkah saya mempekerjakan Anda atau pengembang proyek ini untuk pekerjaan pribadi?

*Lihat di atas.*

#### <a name="SPECIALIST_MODIFICATIONS"></a>Saya perlu modifikasi khusus, customisasi, dll; Apakah kamu bisa membantu?

*Lihat di atas.*

#### <a name="ACCEPT_OR_OFFER_WORK"></a>Saya seorang pengembang, perancang situs web, atau programmer. Dapatkah saya menerima atau menawarkan pekerjaan yang berkaitan dengan proyek ini?

Ya. Lisensi kami tidak melarang hal ini.

#### <a name="WANT_TO_CONTRIBUTE"></a>Saya ingin berkontribusi pada proyek ini; Dapatkah saya melakukan ini?

Ya. Kontribusi untuk proyek ini sangat disambut baik. Silahkan lihat "CONTRIBUTING.md" untuk informasi lebih lanjut.

#### <a name="CRON_TO_UPDATE_AUTOMATICALLY"></a>Dapatkah saya menggunakan cron untuk mengupdate secara otomatis?

Ya. API dibangun dalam bagian depan untuk berinteraksi dengan halaman pembaruan melalui skrip eksternal. Skrip terpisah, "[Cronable](https://github.com/Maikuolan/Cronable)", tersedia, dan dapat digunakan oleh cron manager atau cron scheduler untuk mengupdate paket ini dan paket didukung lainnya secara otomatis (script ini menyediakan dokumentasi sendiri).

#### <a name="WHAT_ARE_INFRACTIONS"></a>Apa "pelanggaran"?

"Pelanggaran" menentukan kapan IP yang belum diblokir oleh file tanda tangan tertentu seharusnya mulai diblokir untuk permintaan di masa mendatang, dan mereka terkait erat dengan pelacakan IP. Beberapa fungsi dan modul ada yang memungkinkan permintaan menjadi diblokir karena alasan selain IP asal (seperti kehadiran agen pengguna [user agent] yang sesuai dengan spambot atau hacktool, permintaan berbahaya, DNS ditempa dan seterusnya), dan kapan ini terjadi, "pelanggaran" dapat terjadi. Mereka menyediakan cara untuk mengidentifikasi alamat IP yang sesuai dengan permintaan yang tidak diinginkan yang mungkin belum terhalang oleh file tanda tangan tertentu. Pelanggaran biasanya sesuai 1-ke-1 dengan berapa kali IP diblokir, namun tidak selalu (kejadian blokir yang parah dapat menimbulkan nilai pelanggaran lebih besar dari satu, dan jika "track_mode" false, pelanggaran tidak akan terjadi karena kejadian blokir yang hanya dipicu oleh file tanda tangan).

#### <a name="BLOCK_HOSTNAMES"></a>Dapatkah CIDRAM memblokir nama host?

Ya. Untuk melakukan ini, Anda harus membuat file modul disesuaikan. *Lihat: [DASAR-DASAR (UNTUK MODUL)](#MODULE_BASICS)*.

#### <a name="WHAT_CAN_I_USE_FOR_DEFAULT_DNS"></a>Apa yang bisa saya gunakan untuk "default_dns"?

Jika Anda mencari saran, [public-dns.info](https://public-dns.info/) dan [OpenNIC](https://servers.opennic.org/) berikan daftar ekstensif dari server DNS publik yang dikenal. Atau, lihat tabel di bawah ini:

IP | Operator
---|---
`1.1.1.1` | [Cloudflare](https://www.cloudflare.com/learning/dns/what-is-1.1.1.1/)
`4.2.2.1`<br />`4.2.2.2`<br />`209.244.0.3`<br />`209.244.0.4` | [Level3](https://www.level3.com/en/)
`8.8.4.4`<br />`8.8.8.8`<br />`2001:4860:4860::8844`<br />`2001:4860:4860::8888` | [Google Public DNS](https://developers.google.com/speed/public-dns/)
`9.9.9.9`<br />`149.112.112.112` | [Quad9 DNS](https://www.quad9.net/)
`84.200.69.80`<br />`84.200.70.40`<br />`2001:1608:10:25::1c04:b12f`<br />`2001:1608:10:25::9249:d69b` | [DNS.WATCH](https://dns.watch/index)
`208.67.220.220`<br />`208.67.222.220`<br />`208.67.222.222` | [OpenDNS Home](https://www.opendns.com/)
`77.88.8.1`<br />`77.88.8.8`<br />`2a02:6b8::feed:0ff`<br />`2a02:6b8:0:1::feed:0ff` | [Yandex.DNS](https://dns.yandex.com/advanced/)
`8.20.247.20`<br />`8.26.56.26` | [Comodo Secure DNS](https://www.comodo.com/secure-dns/)
`216.146.35.35`<br />`216.146.36.36` | [Dyn](https://help.dyn.com/internet-guide-setup/)
`64.6.64.6`<br />`64.6.65.6` | [Verisign Public DNS](https://www.verisign.com/en_US/security-services/public-dns/index.xhtml)
`37.235.1.174`<br />`37.235.1.177` | [FreeDNS](https://freedns.zone/en/)
`156.154.70.1`<br />`156.154.71.1`<br />`2610:a1:1018::1`<br />`2610:a1:1019::1` | [Neustar Security](https://www.security.neustar/dns-services/free-recursive-dns-service)
`45.32.36.36`<br />`45.77.165.194`<br />`179.43.139.226` | [Fourth Estate](https://dns.fourthestate.co/)
`74.82.42.42` | [Hurricane Electric](https://dns.he.net/)
`195.46.39.39`<br />`195.46.39.40` | [SafeDNS](https://www.safedns.com/en/features/)
`81.218.119.11`<br />`209.88.198.133` | [GreenTeam Internet](http://www.greentm.co.uk/)
`89.233.43.71`<br />`91.239.100.100 `<br />`2001:67c:28a4::`<br />`2a01:3a0:53:53::` | [UncensoredDNS](https://blog.uncensoreddns.org/)
`208.76.50.50`<br />`208.76.51.51` | [SmartViper](http://www.markosweb.com/free-dns/)

*Catat: Saya tidak membuat klaim atau jaminan berkenaan dengan praktik privasi, keamanan, keampuhan, atau keandalan untuk layanan DNS apapun, apakah terdaftar atau sebaliknya. Silahkan lakukan penelitian Anda sendiri ketika membuat keputusan tentang mereka.*

#### <a name="PROTECT_OTHER_THINGS"></a>Dapatkah saya menggunakan CIDRAM untuk melindungi hal-hal selain daripada situs web (misalnya, server email, FTP, SSH, IRC, dll)?

Anda dapat (dalam pengertian hukum), tetapi Anda tidak seharusnya (dalam pengertian teknis dan praktis). Lisensi kami tidak membatasi teknologi mana yang implementasikan CIDRAM, tetapi CIDRAM adalah WAF (Aplikasi Web Firewall) dan selalu dimaksudkan untuk melindungi situs web. Karena itu tidak dirancang dengan teknologi lain dalam pikiran, kemungkinan besar itu tidak akan efektif atau memberikan perlindungan diandalkan untuk teknologi lain, implementasi bisa sulit, dan risiko positif palsu dan deteksi terjawab akan sangat tinggi.

#### <a name="CDN_CACHING_PROBLEMS"></a>Akankah masalah terjadi jika saya menggunakan CIDRAM pada saat yang sama dengan menggunakan layanan CDN atau cache?

Mungkin. Ini tergantung pada sifat layanan yang dipermasalahkan, dan bagaimana Anda menggunakannya. Umumnya, jika Anda hanya menyimpan aset statis dalam cache (gambar, CSS, dll; apapun yang umumnya tidak berubah seiring waktu), seharusnya tidak ada masalah. Namun, mungkin ada masalah, jika Anda menyimpan data dalam cache yang biasanya akan dihasilkan secara dinamis saat diminta, atau jika Anda menyimpan hasil dari permintaan POST (ini pada dasarnya akan membuat situs web Anda dan lingkungannya sebagai statis wajib, dan CIDRAM tidak akan memberikan manfaat yang berarti dalam lingkungan statis wajib). Mungkin juga ada persyaratan konfigurasi khusus untuk CIDRAM, tergantung pada layanan CDN atau cache yang Anda gunakan (Anda harus memastikan bahwa CIDRAM dikonfigurasi benar untuk layanan CDN atau cache spesifik yang Anda gunakan). Kegagalan untuk mengkonfigurasi CIDRAM benar dapat menyebabkan masalah positif palsu dan deteksi terjawab.

#### <a name="DDOS_ATTACKS"></a>Akankah CIDRAM melindungi situs web saya dari serangan DDoS?

Jawaban singkat: Tidak.

Jawaban sedikit lebih panjang: CIDRAM akan membantu mengurangi dampak yang dapat disebabkan oleh lalu lintas yang tidak diinginkan di situs web Anda (sehingga mengurangi biaya bandwidth situs web Anda), akan membantu mengurangi dampak yang dapat disebabkan oleh lalu lintas yang tidak diinginkan pada perangkat keras Anda (misalnya, kemampuan server Anda untuk memproses dan melayani permintaan), dan dapat membantu mengurangi berbagai efek negatif potensial lainnya yang terkait dengan lalu lintas yang tidak diinginkan. Namun, ada dua hal penting yang harus diingat untuk memahami pertanyaan ini.

Pertama, CIDRAM adalah paket PHP, dan karena itu beroperasi di mesin tempat PHP diinstal. Ini berarti bahwa CIDRAM hanya dapat melihat dan memblokir permintaan *setelah* server telah menerimanya. Kedua, mitigasi DDoS yang efektif harus menyaring permintaan *sebelum* mereka mencapai server yang ditargetkan oleh serangan DDoS. Idealnya, serangan DDoS harus dideteksi dan dimitigasi oleh solusi yang mampu menjatuhkan atau merutekan di tempat lain lalu lintas yang terkait dengan serangan, sebelum mencapai server yang ditargetkan di tempat pertama.

Ini dapat diimplementasikan dengan menggunakan solusi perangkat keras on-premise berdedikasi, dan/atau solusi berbasis cloud seperti layanan mitigasi DDoS berdedikasi, dengan merutekan DNS domain melalui jaringan yang dapat menahan serangan DDoS, dengan pemfilteran berbasis cloud, atau dengan beberapa kombinasinya. Bagaimanapun juga, subjek ini agak terlalu kompleks untuk dijelaskan secara menyeluruh hanya dengan satu atau dua paragraf, jadi saya akan merekomendasikan melakukan penelitian lebih lanjut jika ini adalah subjek yang ingin Anda kejar. Ketika sifat serangan DDoS benar dipahami, jawaban ini akan lebih masuk akal.

#### <a name="CHANGE_COMPONENT_SORT_ORDER"></a>Ketika saya mengaktifkan atau menonaktifkan modul atau file tanda tangan melalui halaman pembaruan, itu memilah mereka secara alfanumerik dalam konfigurasi. Bisakah saya mengubah cara mereka disortir?

Ya. Jika Anda perlu memaksa beberapa file untuk dieksekusikan dalam urutan tertentu, Anda dapat menambahkan beberapa data sewenang-wenang sebelum nama mereka di direktif konfigurasi dimana mereka terdaftar, dipisahkan oleh titik dua. Ketika halaman pembaruan selanjutnya mengurutkan file lagi, data tambahan ini akan mempengaruhi urutan, menyebabkan mereka secara konsekuen mengeksekusi dalam urutan yang Anda inginkan, tanpa perlu mengganti nama mereka.

Misalnya, dengan asumsi direktif konfigurasi dengan file yang tercantum sebagai berikut:

`file1.php,file2.php,file3.php,file4.php,file5.php`

Jika Anda ingin `file3.php` untuk mengeksekusi terlebih dahulu, Anda bisa menambahkan sesuatu seperti `aaa:` sebelum nama file:

`file1.php,file2.php,aaa:file3.php,file4.php,file5.php`

Kemudian, jika file baru, `file6.php`, diaktifkan, ketika halaman pembaruan mengurutkan semuanya lagi, itu akan berakhir seperti ini:

`aaa:file3.php,file1.php,file2.php,file4.php,file5.php,file6.php`

Situasi adalah sama ketika file dinonaktifkan. Sebaliknya, jika Anda ingin file dieksekusi terakhir, Anda bisa menambahkan sesuatu seperti `zzz:` sebelum nama file. Dalam hal apapun, Anda tidak perlu mengganti nama file yang dimaksud.

---


### 11. <a name="SECTION11"></a>INFORMASI HUKUM

#### 11.0 PENGANTAR BAGIAN

Bagian dokumentasi ini dimaksudkan untuk menjelaskan kemungkinan pertimbangan hukum mengenai penggunaan dan implementasi paket, dan untuk memberikan beberapa informasi dasar terkait. Ini mungkin penting bagi beberapa pengguna sebagai sarana untuk memastikan kepatuhan dengan persyaratan hukum yang mungkin ada di negara tempat mereka beroperasi, dan beberapa pengguna mungkin perlu menyesuaikan kebijakan situs web mereka sesuai dengan informasi ini.

Pertama dan terutama, harap menyadari bahwa saya (penulis paket) bukan seorang pengacara, atau profesional hukum yang berkualitas dalam bentuk apapun. Oleh karena itu, saya secara hukum tidak memenuhi syarat untuk memberikan nasihat hukum. Juga, dalam beberapa kasus, persyaratan hukum yang tepat dapat bervariasi antara negara dan yurisdiksi yang berbeda, dan berbagai persyaratan hukum ini terkadang dapat menimbulkan konflik (seperti, misalnya, dalam kasus negara-negara yang mendukung hak privasi dan hak untuk dilupakan, versus negara-negara yang mendukung retensi data diperpanjang). Pertimbangkan juga bahwa akses ke paket tidak terbatas pada negara atau yurisdiksi tertentu, dan oleh karena itu, pengguna paket cenderung ke geografis yang beragam. Poin-poin ini dianggap, saya tidak dalam posisi untuk menyatakan apa artinya "mematuhi hukum" untuk semua pengguna, dalam semua hal. Namun, saya berharap informasi disini akan membantu Anda untuk mengambil keputusan sendiri mengenai apa yang Anda harus lakukan agar tetap mematuhi hukum dalam konteks paket. Jika Anda memiliki keraguan atau kekhawatiran mengenai informasi disini, atau jika Anda membutuhkan bantuan dan saran tambahan dari perspektif hukum, saya merekomendasikan konsultasi dengan profesional hukum yang berkualitas.

#### 11.1 TANGGUNG JAWAB DAN KEWAJIBAN HUKUM

Seperti yang telah dinyatakan oleh lisensi paket, paket ini disediakan tanpa jaminan apapun. Ini termasuk (tetapi tidak terbatas pada) semua lingkup kewajiban hukum. Paket ini diberikan kepada Anda untuk kenyamanan Anda, dengan harapan itu akan berguna, dan itu akan memberikan beberapa manfaat bagi Anda. Namun, apakah Anda menggunakan atau mengimplementasikan paket ini, adalah pilihan Anda sendiri. Anda tidak dipaksa untuk menggunakan atau mengimplementasikan paket ini, tetapi ketika Anda melakukannya, Anda bertanggung jawab atas keputusan itu. Bukan saya, dan tidak ada kontributor lain untuk paket ini, bertanggung jawab secara hukum atas konsekuensi keputusan yang Anda buat, terlepas dari apakah langsung, tidak langsung, tersirat, atau sebaliknya.

#### 11.2 PIHAK KETIGA

Tergantung pada konfigurasi dan implementasinya yang tepat, paket dapat berkomunikasi dan berbagi informasi dengan pihak ketiga dalam beberapa kasus. Informasi ini dapat didefinisikan sebagai "informasi identitas pribadi" (PII) dalam beberapa konteks, oleh beberapa yurisdiksi.

Bagaimana informasi ini dapat digunakan oleh pihak ketiga ini, tunduk pada berbagai kebijakan yang ditetapkan oleh pihak ketiga ini, dan berada di luar ruang lingkup dokumentasi ini. Namun, dalam semua kasus tersebut, berbagi informasi dengan pihak ketiga ini dapat dinonaktifkan. Dalam semua kasus semacam itu, jika Anda memilih untuk mengaktifkannya, Anda bertanggung jawab untuk meneliti setiap kekhawatiran yang mungkin Anda miliki tentang privasi, keamanan, dan penggunaan PII oleh pihak ketiga ini. Jika ada keraguan, atau jika Anda tidak puas dengan perilaku pihak ketiga ini sehubungan dengan PII, mungkin terbaik adalah menonaktifkan semua pembagian informasi dengan pihak ketiga ini.

Untuk tujuan transparansi, jenis informasi yang dibagikan, dan dengan siapa, dijelaskan di bawah ini.

##### 11.2.0 PENCARIAN NAMA HOST

Jika Anda menggunakan fitur atau modul yang dimaksudkan untuk bekerja dengan nama host (seperti "modul pemblokir untuk host buruk", "tor project exit nodes block module", atau "verifikasi mesin pencari", misalnya), CIDRAM harus dapat memperoleh nama host dari permintaan masuk entah bagaimana. Biasanya, ini dilakukan dengan meminta nama host dari alamat IP permintaan masuk dari server DNS, atau dengan meminta informasi melalui fungsionalitas yang disediakan oleh sistem tempat CIDRAM diinstal (ini biasanya disebut sebagai "pencarian nama host"). Server DNS yang ditentukan secara default adalah milik layanan [Google DNS](https://dns.google.com/) (tetapi ini dapat dengan mudah diubah melalui konfigurasi). Layanan yang tepat yang dikomunikasikan dapat dikonfigurasi, dan tergantung pada cara Anda mengonfigurasi paket. Dalam kasus menggunakan fungsionalitas yang disediakan oleh sistem tempat CIDRAM diinstal, Anda harus menghubungi administrator sistem Anda untuk menentukan rute mana yang digunakan oleh pencarian nama host. Pencarian nama host dapat dicegah dalam CIDRAM dengan menghindari modul yang terpengaruh atau dengan memodifikasi konfigurasi paket sesuai dengan kebutuhan Anda.

*Direktif konfigurasi yang relevan:*
- `general` -> `default_dns`
- `general` -> `search_engine_verification`
- `general` -> `social_media_verification`
- `general` -> `force_hostname_lookup`
- `general` -> `allow_gethostbyaddr_lookup`

##### 11.2.1 FONT WEB

Beberapa tema kustom, serta UI standar ("antarmuka pengguna") untuk halaman bagian depan CIDRAM dan halaman "Akses Ditolak", dapat menggunakan font web untuk alasan estetika. Font web dinonaktifkan secara default, tetapi ketika diaktifkan, komunikasi langsung antara browser pengguna dan layanan hosting font web terjadi. Ini mungkin melibatkan informasi komunikasi seperti alamat IP pengguna, agen pengguna, sistem operasi, dan detail lainnya yang tersedia untuk permintaan tersebut. Sebagian besar font web ini dihosting oleh layanan [Google Fonts](https://fonts.google.com/).

*Direktif konfigurasi yang relevan:*
- `general` -> `disable_webfonts`

##### 11.2.2 VERIFIKASI MESIN PENCARI

Ketika verifikasi mesin pencari diaktifkan, CIDRAM mencoba melakukan "pencarian DNS ke depan" untuk memverifikasi apakah permintaan yang mengaku berasal dari mesin pencari adalah asli. Untuk melakukan ini, verifikasi mesin pencari menggunakan layanan [Google DNS](https://dns.google.com/) untuk mencoba menyelesaikan alamat IP dari nama host dari permintaan masuk ini (dalam proses ini, nama host dari permintaan masuk ini dibagikan dengan layanan).

*Direktif konfigurasi yang relevan:*
- `general` -> `search_engine_verification`

##### 11.2.3 GOOGLE reCAPTCHA

CIDRAM secara opsional mendukung [Google reCAPTCHA](https://www.google.com/recaptcha/), menyediakan sarana bagi pengguna untuk melewati halaman "Akses Ditolak" dengan menyelesaikan instance reCAPTCHA (informasi lebih lanjut tentang fitur ini dijelaskan sebelumnya dalam dokumentasi, terutama di bagian konfigurasi). Google reCAPTCHA membutuhkan kunci API agar berfungsi dengan benar, dan karenanya dinonaktifkan secara default. Ini dapat diaktifkan dengan menentukan kunci API yang diperlukan dalam konfigurasi paket. Ketika diaktifkan, komunikasi langsung antara browser pengguna dan layanan reCAPTCHA terjadi. Ini mungkin melibatkan mengkomunikasikan informasi seperti alamat IP pengguna, agen pengguna, sistem operasi, dan detail lainnya yang tersedia untuk permintaan tersebut. Alamat IP pengguna juga dapat dibagi dalam komunikasi antara CIDRAM dan layanan reCAPTCHA ketika memverifikasi validitas instance reCAPTCHA dan memverifikasi apakah itu berhasil diselesaikan.

*Direktif konfigurasi yang relevan: Apapun yang tercantum di bawah kategori konfigurasi "recaptcha".*

##### 11.2.4 STOP FORUM SPAM

[Stop Forum Spam](https://www.stopforumspam.com/) adalah layanan fantastis, tersedia secara bebas yang dapat membantu melindungi forum, blog, dan situs web dari spammer. Ini dilakukan dengan menyediakan database spammer yang dikenal, dan API yang dapat dimanfaatkan untuk memeriksa apakah alamat IP, nama pengguna, atau alamat email tercantum dalam database-nya.

CIDRAM menyediakan modul opsional yang memanfaatkan API ini untuk memeriksa apakah alamat IP dari permintaan masuk milik seorang spammer yang dicurigai. Modul ini tidak diinstal secara default, tetapi jika Anda memilih untuk menginstalnya, alamat IP pengguna dapat dibagikan dengan API Stop Forum Spam sesuai dengan tujuan yang dimaksudkan dari modul. Ketika modul diinstal, CIDRAM berkomunikasi dengan API ini setiap kali permintaan masuk meminta sumber halaman yang diakui oleh CIDRAM sebagai jenis sumber halaman yang sering ditargetkan oleh pelaku spam (seperti halaman login, halaman registrasi, halaman verifikasi email, formulir komentar, dll).

#### 11.3 PENCATATAN

Pencatatan adalah bagian penting dari CIDRAM karena sejumlah alasan. Mungkin sulit untuk mendiagnosis dan menyelesaikan kesalahan positif ketika kejadian blokir yang menyebabkan mereka tidak dicatat. Tanpa mencatat kejadian blokir, mungkin sulit untuk memastikan secara akurat seberapa baik kinerja CIDRAM dalam konteks tertentu, dan mungkin sulit untuk menentukan dimana kekurangannya, dan perubahan apa yang mungkin diperlukan untuk konfigurasi atau tanda tangan yang sesuai, agar terus berfungsi sebagaimana dimaksud. Apapun, pencatatan mungkin tidak diinginkan untuk semua pengguna, dan tetap sepenuhnya opsional. Di CIDRAM, pencatatan dinonaktifkan secara default. Untuk mengaktifkannya, CIDRAM harus dikonfigurasi dengan benar.

Juga, apakah pencatatan diizinkan secara hukum, dan sejauh diizinkan secara hukum (misalnya, jenis informasi yang dapat dicatat, untuk berapa lama, dan dalam keadaan apa), dapat bervariasi, tergantung pada yurisdiksi dan pada konteks dimana CIDRAM diimplementasikan (misalnya, apakah Anda beroperasi sebagai individu, sebagai entitas perusahaan, dan apakah secara komersial atau non-komersial). Jadi, mungkin berguna bagi Anda untuk membaca bagian ini dengan seksama.

Ada beberapa jenis pencatatan yang dapat dilakukan oleh CIDRAM. Berbagai jenis pencatatan melibatkan berbagai jenis informasi, untuk berbagai alasan.

##### 11.3.0 KEJADIAN BLOKIR

Jenis utama dari pencatatan yang dapat dilakukan oleh CIDRAM berhubungan dengan "kejadian blokir". Jenis pencatatan ini terkait dengan kapan CIDRAM memblokir permintaan, dan dapat diberikan dalam tiga format berbeda:
- File log yang dapat dibaca oleh manusia.
- File log gaya Apache.
- File log dalam format serial.

Kejadian blokir, yang dicatat ke file log yang dapat dibaca oleh manusia, biasanya terlihat seperti ini (sebagai contoh):

```
ID: 1234
Versi Skrip: CIDRAM v1.6.0
Tanggal/Waktu: Day, dd Mon 20xx hh:ii:ss +0000
Alamat IP: x.x.x.x
Nama host: dns.hostname.tld
Menghitung Tanda Tangan: 1
Tanda Tangan Referensi: x.x.x.x/xx
Mengapa Diblokir: Layanan komputasi awan ("Nama Jaringan", Lxx:Fx, [XX])!
Agen Pengguna: Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.181 Safari/537.36
Direkonstruksi URI: http://your-site.tld/index.php
Status reCAPTCHA: Diaktifkan.
```

Kejadian blokir yang sama ini, yang dicatat ke file log dalam gaya Apache, akan terlihat seperti ini:

```
x.x.x.x - - [Day, dd Mon 20xx hh:ii:ss +0000] "GET /index.php HTTP/1.1" 200 xxxx "-" "Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.181 Safari/537.36"
```

Kejadian blokir yang dicatat biasanya mencakup informasi berikut:
- Nomor ID yang mereferensi pada kejadian blokir.
- Versi CIDRAM sedang digunakan.
- Tanggal dan waktu saat kejadian blokir terjadi.
- Alamat IP dari permintaan yang diblokir.
- Nama host dari alamat IP dari permintaan yang diblokir (bila tersedia).
- Jumlah tanda tangan yang dipicu oleh permintaan.
- Referensi untuk tanda tangan dipicu.
- Referensi ke alasan untuk kejadian blokir dan beberapa informasi debug dasar yang terkait.
- Agen pengguna dari permintaan yang diblokir (yaitu, bagaimana entitas yang meminta mengidentifikasi dirinya untuk permintaan tersebut).
- Rekonstruksi pengidentifikasi untuk sumber yang awalnya diminta.
- Status reCAPTCHA untuk permintaan saat ini (bila relevan).

Direktif konfigurasi yang bertanggung jawab untuk jenis pencatatan ini, dan untuk masing-masing dari tiga format yang tersedia, adalah:
- `general` -> `logfile`
- `general` -> `logfileApache`
- `general` -> `logfileSerialized`

Ketika direktif ini dibiarkan kosong, jenis pencatatan ini akan tetap dinonaktifkan.

##### 11.3.1 LOG reCAPTCHA

Jenis pencatatan ini secara khusus berkaitan dengan instance reCAPTCHA, dan hanya terjadi ketika pengguna mencoba menyelesaikan instance reCAPTCHA.

Entri log reCAPTCHA berisi alamat IP pengguna yang berusaha menyelesaikan instance reCAPTCHA, tanggal dan waktu ketika itu terjadi, dan status reCAPTCHA. Entri log reCAPTCHA biasanya terlihat seperti ini (sebagai contoh):

```
Alamat IP: x.x.x.x - Tanggal/Waktu: Day, dd Mon 20xx hh:ii:ss +0000 - Status reCAPTCHA: Lulus!
```

Direktif konfigurasi yang bertanggung jawab untuk pencatatan reCAPTCHA adalah:
- `recaptcha` -> `logfile`

##### 11.3.2 LOG BAGIAN DEPAN

Jenis pencatatan ini berhubungan dengan upaya masuk bagian depan, dan hanya terjadi ketika pengguna mencoba masuk ke bagian depan (dengan asumsi akses bagian depan diaktifkan).

Entri log untuk bagian depan berisi alamat IP pengguna yang mencoba masuk, tanggal dan waktu ketika itu terjadi, dan hasil dari upaya tersebut (berhasil masuk, atau gagal masuk). Entri log untuk bagian depan biasanya terlihat seperti ini (sebagai contoh):

```
x.x.x.x - Day, dd Mon 20xx hh:ii:ss +0000 - "admin" - Pengguna yang online.
```

Direktif konfigurasi yang bertanggung jawab untuk pencatatan bagian depan adalah:
- `general` -> `FrontEndLog`

##### 11.3.3 ROTASI LOG

Anda mungkin ingin membersihkan log setelah jangka waktu tertentu, atau mungkin diminta untuk melakukannya oleh hukum (yaitu, jumlah waktu yang diizinkan secara hukum bagi Anda untuk mempertahankan log mungkin dibatasi oleh hukum). Anda dapat mencapai ini dengan menyertakan penanda tanggal/waktu dalam nama-nama file log Anda sesuai yang ditentukan oleh konfigurasi paket Anda (misalnya, `{yyyy}-{mm}-{dd}.log`), dan kemudian mengaktifkan rotasi log (rotasi log memungkinkan Anda untuk melakukan beberapa tindakan pada file log ketika batas yang ditentukan terlampaui).

Sebagai contoh: Jika saya secara hukum diminta untuk menghapus log setelah 30 hari, saya bisa menentukan `{dd}.log` dalam nama file log saya (`{dd}` represents days), atur nilai `log_rotation_limit` ke 30, dan atur nilai `log_rotation_action` ke `Delete`.

Sebaliknya, jika Anda diminta untuk menyimpan log untuk jangka waktu yang panjang, Anda bisa memilih untuk tidak menggunakan rotasi log sama sekali, atau Anda bisa mengatur nilai `log_rotation_action` ke `Archive`, untuk mengompres file log, sehingga mengurangi jumlah total ruang disk yang mereka tempati.

*Direktif konfigurasi yang relevan:*
- `general` -> `log_rotation_limit`
- `general` -> `log_rotation_action`

##### 11.3.4 PEMOTONGAN LOG

Ini juga memungkinkan untuk memotong file log individu ketika mereka melebihi ukuran tertentu, jika ini adalah sesuatu yang mungkin Anda butuhkan atau ingin lakukan.

*Direktif konfigurasi yang relevan:*
- `general` -> `truncate`

##### 11.3.5 PSEUDONIMISASI ALAMAT IP

Pertama, jika Anda tidak akrab dengan istilah ini, "pseudonimisasi" mengacu pada memproses data pribadi sedemikian rupa sehingga tidak dapat diidentifikasi ke subjek data tertentu lagi tanpa beberapa informasi tambahan, dan dengan ketentuan bahwa informasi tambahan tersebut dipelihara secara terpisah dan tunduk pada tindakan teknis dan organisasi untuk memastikan bahwa data pribadi tidak dapat diidentifikasi kepada orang alami.

Dalam beberapa keadaan, Anda mungkin diperlukan secara hukum untuk membuat dianonimisasi atau dipseudonimisasi setiap PII dikumpulkan, diproses, atau disimpan. Meskipun konsep ini telah ada untuk beberapa waktu sekarang, GDPR/DSGVO terutama menyebutkan, dan secara khusus mendorong "pseudonimisasi".

CIDRAM mampu mem-pseudonimisasi alamat IP ketika melakukan pencatatan, jika ini adalah sesuatu yang mungkin Anda butuhkan atau ingin lakukan. Ketika CIDRAM mem-pseudonimkan alamat IP, saat dicatat, oktet terakhir dari alamat IPv4, dan semuanya setelah bagian kedua dari alamat IPv6 diwakili oleh "x" (efektif membulatkan alamat IPv4 ke alamat awal dari subnet ke-24 dari faktor dimana mereka dimasukkan, dan alamat IPv6 ke alamat awal dari subnet ke-32 dari faktor dimana mereka dimasukkan).

*Catat: Proses pseudonimisasi alamat IP untuk CIDRAM tidak mempengaruhi fitur pelacakan IP untuk CIDRAM. Jika ini masalah bagi Anda, mungkin yang terbaik adalah menonaktifkan pelacakan IP sepenuhnya. Ini dapat dicapai dengan mengatur `track_mode` ke `false` dan dengan menghindari modul apapun.*

*Direktif konfigurasi yang relevan:*
- `signatures` -> `track_mode`
- `legal` -> `pseudonymise_ip_addresses`

##### 11.3.6 MENGHILANGKAN INFORMASI LOG

Jika Anda ingin melangkah lebih jauh dengan mencegah jenis informasi tertentu sedang dicatat sepenuhnya, ini juga mungkin dilakukan. CIDRAM menyediakan direktif-direktif konfigurasi untuk mengontrol apakah alamat IP, nama host, dan agen pengguna termasuk dalam log. Secara default, ketiga titik data ini termasuk dalam log bila tersedia. Menetapkan apapun direktif-direktif konfigurasi ini ke `true` akan menghilangkan informasi terkait dari log.

*Catat: Tidak ada alasan untuk menggunakan pseudonim dengan alamat IP ketika menghilangkannya dari log sepenuhnya.*

*Direktif konfigurasi yang relevan:*
- `legal` -> `omit_ip`
- `legal` -> `omit_hostname`
- `legal` -> `omit_ua`

##### 11.3.7 STATISTIK

CIDRAM secara opsional dapat melacak statistik seperti jumlah total kejadian blokir atau instance reCAPTCHA yang telah terjadi sejak beberapa titik waktu tertentu. Fitur ini dinonaktifkan secara default, tetapi dapat diaktifkan melalui konfigurasi paket. Fitur ini hanya melacak jumlah total kejadian yang terjadi, dan tidak termasuk informasi apapun tentang kejadian tertentu (dan dengan demikian, tidak boleh dianggap sebagai PII).

*Direktif konfigurasi yang relevan:*
- `general` -> `statistics`

##### 11.3.8 ENKRIPSI

CIDRAM tidak mengenkripsi cache atau informasi log apapun. [Enkripsi](https://id.wikipedia.org/wiki/Enkripsi) cache dan log dapat diperkenalkan di masa depan, tetapi tidak ada rencana khusus untuk itu saat ini. Jika Anda khawatir tentang pihak ketiga yang tidak sah mendapatkan akses ke bagian depan dari CIDRAM yang mungkin berisi PII atau informasi sensitif seperti cache atau log-nya, saya akan merekomendasikan bahwa CIDRAM tidak diinstal di lokasi yang dapat diakses publik (misalnya, instal CIDRAM di luar direktori `public_html` standar atau yang setara dengan yang tersedia untuk sebagian besar web server standar) dan bahwa perizinan restriktif yang tepat diberlakukan untuk direktori tempat ia tinggal (khususnya, untuk direktori vault). Jika itu tidak cukup untuk mengatasi masalah Anda, konfigurasikan CIDRAM sedemikian rupa sehingga jenis informasi yang menyebabkan kekhawatiran Anda tidak akan dikumpulkan atau dicatat di tempat pertama (seperti, dengan menonaktifkan pencatatan).

#### 11.4 COOKIE

CIDRAM menetapkan [cookie](https://id.wikipedia.org/wiki/Kuki_HTTP) pada dua titik dalam basis kodenya. Pertama, ketika pengguna berhasil menyelesaikan instance reCAPTCHA (dan mengasumsikan bahwa `lockuser` diatur ke `true`), CIDRAM menetapkan cookie agar dapat mengingat untuk permintaan berikutnya bahwa pengguna telah menyelesaikan instance reCAPTCHA, sehingga tidak perlu meminta pengguna untuk menyelesaikan instance reCAPTCHA pada permintaan berikutnya. Kedua, ketika pengguna berhasil masuk ke akses bagian depan, CIDRAM menetapkan cookie agar dapat mengingat pengguna untuk permintaan berikutnya (yaitu, cookie digunakan untuk mengautentikasi pengguna ke sesi masuk).

Dalam kedua kasus, peringatan cookie ditampilkan dengan jelas (bila berlaku), memperingatkan pengguna bahwa cookie akan diatur jika mereka terlibat dalam tindakan yang relevan. Cookie tidak diatur dalam titik lain di basis kode.

*Catat: Implementasi spesifik CIDRAM dari "invisible" API untuk reCAPTCHA mungkin tidak kompatibel dengan hukum cookie di beberapa yurisdiksi, dan harus dihindari oleh situs web apapun yang tunduk pada hukum-hukum tersebut. Memilih untuk menggunakan "V2" API sebagai gantinya, atau hanya menonaktifkan reCAPTCHA sepenuhnya, mungkin lebih baik.*

*Direktif konfigurasi yang relevan:*
- `general` -> `disable_frontend`
- `recaptcha` -> `lockuser`
- `recaptcha` -> `api`

#### 11.5 PEMASARAN DAN PERIKLANAN

CIDRAM tidak mengumpulkan atau memproses informasi apapun untuk tujuan pemasaran atau periklanan, dan tidak menjual atau memperoleh keuntungan dari informasi yang dikumpulkan atau dicatat. CIDRAM bukan perusahaan komersial, juga tidak terkait dengan kepentingan komersial, sehingga melakukan hal-hal ini tidak akan masuk akal. Ini telah terjadi sejak awal proyek, dan terus menjadi kasus hari ini. Juga, melakukan hal-hal ini akan menjadi kontra-produktif terhadap semangat dan tujuan yang dimaksudkan dari proyek secara keseluruhan, dan selama saya terus mempertahankan proyek, tidak akan pernah terjadi.

#### 11.6 KEBIJAKAN PRIVASI

Dalam beberapa keadaan, Anda mungkin diharuskan secara hukum untuk secara jelas menampilkan tautan ke kebijakan privasi Anda pada semua halaman dan bagian dari situs web Anda. Ini mungkin penting sebagai sarana untuk memastikan bahwa pengguna mendapat informasi yang jelas tentang praktik privasi Anda, jenis PII yang Anda kumpulkan, dan bagaimana Anda akan menggunakannya. Agar dapat menyertakan tautan di halaman "Akses Ditolak" CIDRAM, direktif konfigurasi disediakan untuk menentukan URL ke kebijakan privasi Anda.

*Catat: Sangat disarankan agar halaman kebijakan privasi Anda tidak ditempatkan di belakang perlindungan CIDRAM. Jika CIDRAM melindungi halaman kebijakan privasi Anda, dan pengguna yang diblokir oleh CIDRAM mengklik tautan ke kebijakan privasi Anda, mereka akan diblokir lagi, dan tidak akan dapat melihat kebijakan privasi Anda. Idealnya, Anda harus menautkan ke salinan statis dari kebijakan privasi Anda, seperti halaman HTML atau file teks biasa yang tidak dilindungi oleh CIDRAM.*

*Direktif konfigurasi yang relevan:*
- `legal` -> `privacy_policy`

#### 11.7 GDPR/DSGVO

Regulasi Perlindungan Data Umum (GDPR) adalah regulasi dari Uni Eropa, yang mulai berlaku pada 25 Mei 2018. Tujuan utama dari regulasi ini adalah untuk memberikan kontrol kepada warga dan penduduk negara Uni Eropa mengenai data pribadi mereka sendiri, dan untuk menyatukan regulasi di Uni Eropa terkait privasi dan data pribadi.

Regulasi ini tersebut berisi ketentuan khusus yang berkaitan dengan pemrosesan "informasi identitas pribadi" (PII) dari setiap "subjek data" (setiap orang alami yang teridentifikasi atau dapat diidentifikasi) dari atau di dalam Uni Eropa. Agar sesuai dengan regulasi, "perusahaan" atau "enterprise" (sesuai yang didefinisikan oleh regulasi), dan sistem dan proses yang relevan harus mengimplementasikan "privasi berdasarkan desain" secara default, harus menggunakan pengaturan privasi setinggi mungkin, harus mengimplementasikan pengamanan yang diperlukan untuk informasi yang disimpan atau diproses (termasuk, tetapi tidak terbatas pada, mengimplementasikan pseudonimisasi atau anonimisasi yang penuh untuk data), harus jelas dan tidak ambigu menyatakan jenis data yang mereka kumpulkan, bagaimana mereka memprosesnya, untuk alasan apa, untuk berapa lama mereka menyimpannya, dan apakah mereka membagikan data ini dengan pihak ketiga manapun, jenis data yang dibagikan dengan pihak ketiga, bagaimana, mengapa, dan sebagainya.

Data tidak dapat diproses kecuali jika ada dasar yang sah untuk melakukannya, sesuai yang didefinisikan oleh regulasi. Umumnya, ini berarti bahwa untuk memproses data data subjek secara sah, itu harus dilakukan sesuai dengan kewajiban hukum, atau dilakukan hanya setelah persetujuan eksplisit, terinformasi dengan baik, dan tidak ambigu diperoleh dari subjek data.

Karena aspek regulasi dapat berevolusi dalam waktu, untuk menghindari penyebaran informasi yang ketinggalan jaman, mungkin lebih baik untuk belajar tentang regulasi dari sumber yang berwenang, dibandingkan dengan hanya memasukkan informasi yang relevan disini dalam dokumentasi paket (yang akhirnya bisa menjadi usang seiring berkembangnya regulasi).

Beberapa sumber bacaan yang direkomendasikan untuk mempelajari informasi lebih lanjut:
- [REGULATION (EU) 2016/679 OF THE EUROPEAN PARLIAMENT AND OF THE COUNCIL](https://eur-lex.europa.eu/legal-content/EN/TXT/?uri=celex:32016R0679)
- [Regulasi Perlindungan Data](https://id.wikipedia.org/wiki/Regulasi_Perlindungan_Data)

---


Terakhir Diperbarui: 9 Juli 2018 (2018.07.09).
