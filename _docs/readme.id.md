## Dokumentasi untuk CIDRAM (Bahasa Indonesia).

### Isi
- 1. [SEPATAH KATA](#SECTION1)
- 2. [BAGAIMANA CARA MENGINSTALL](#SECTION2)
- 3. [BAGAIMANA CARA MENGGUNAKAN](#SECTION3)
- 4. [FILE YANG DIIKUTKAN DALAM PAKET INI](#SECTION4)
- 5. [OPSI KONFIGURASI](#SECTION5)
- 6. [FORMAT TANDA TANGAN](#SECTION6)
- 7. [PERTANYAAN YANG SERING DIAJUKAN (FAQ)](#SECTION7)

---


###1. <a name="SECTION1"></a>SEPATAH KATA

CIDRAM (Classless Inter-Domain Routing Access Manager) adalah skrip PHP dirancang untuk melindungi situs oleh memblokir permintaan-permintaan berasal dari alamat IP yang dianggap sumber lalu lintas yang tidak diinginkan, termasuk (tapi tidak terbatas pada) lalu lintas dari jalur akses yang tidak manusia, layanan cloud, spambots, pencakar/scrapers, dll. Hal ini dilakukan melalui menghitung kisaran CIDR alamat IP dipasok dari permintaan dan mencoba untuk mencocokkan ini kisaran CIDR terhadap file tanda tangan (file tanda tangan ini berisi daftar CIDR alamat IP dianggap sumber lalu lintas yang tidak diinginkan); Jika dicocokkan, permintaan yang diblokir.

CIDRAM HAK CIPTA 2016 dan di atas GNU/GPLv2 oleh Caleb M (Maikuolan).

Skrip ini adalah perangkat lunak gratis; Anda dapat mendistribusikan kembali dan/atau memodifikasinya dalam batasan dari GNU General Public License, seperti di publikasikan dari Free Software Foundation; baik versi 2 dari License, atau (dalam opsi Anda) versi selanjutnya apapun. Skrip ini didistribusikan untuk harapan dapat digunakan tapi TANPA JAMINAN; tanpa walaupun garansi dari DIPERJUALBELIKAN atau KECOCOKAN UNTUK TUJUAN TERTENTU. Mohon Lihat GNU General Public Licence untuk lebih detail, terletak di file `LICENSE.txt` dan tersedia juga dari:
- <http://www.gnu.org/licenses/>.
- <http://opensource.org/licenses/>.

Dokumen ini dan paket terhubung di dalamnya dapat di unduh secara gratis dari [GitHub](https://github.com/Maikuolan/CIDRAM/).

---


###2. <a name="SECTION2"></a>BAGAIMANA CARA MENGINSTALL

Saya berharap untuk mempersingkat proses ini dengan membuat sebuah installer pada beberapa point di dalam masa depan yang tidak terlalu jauh, tapi kemudian, ikuti instruksi-instruksi ini untuk mendapatkan CIDRAM bekerja pada *banyak sistem dan CMS:

1) Dengan membaca ini, Saya asumsikan Anda telah mengunduh dan menyimpan copy dari skrip, membuka data terkompres dan isinya dan Anda meletakkannya pada mesin komputer lokal Anda. Dari sini, Anda akan latihan dimana di host Anda atau CMS Anda untuk meletakkan isi data terkompres nya. Sebuah direktori seperti `/public_html/cidram/` atau yang lain (walaupun tidak masalah Anda memilih direktori apa, selama dia aman dan dimana pun yang Anda senangi) akan mencukupi. *Sebelum Anda mulai upload, mohon baca dulu..*

2) Mengubah file nama `config.ini.RenameMe` ke `config.ini` (berada di dalam `vault`), dan secara fakultatif (sangat direkomendasikan untuk user dengan pengalaman lebih lanjut, tapi tidak untuk pemula atau yang tidak berpengalaman), membukanya (file ini berisikan semua opsi operasional yang tersedia untuk CIDRAM; di atas tiap opsi seharusnya ada komentar tegas menguraikan tentang apa yang dilakukan dan untuk apa). Atur opsi-opsi ini seperti Anda lihat cocok, seperti apapun yang cocok untuk setup tertentu. Simpan file, menutupnya.

3) Upload isi (CIDRAM dan file-filenya) ke direktori yang telah kamu putuskan sebelumnya (Anda tidak memerlukan file-file `*.txt`/`*.md`, tapi kebanyakan Anda harus mengupload semuanya).

4) Gunakan perinta CHMOD ke direktori `vault` dengan "755" (jika ada masalah, Anda dapat mencoba "777", tapi ini kurang aman). Direktori utama menyimpan isinya (yang Anda putuskan sebelumnya), umumnya dapat di biarkan sendirian, tapi status perintah "CHMOD" seharusnya di cek jika kamu punya izin di sistem Anda (defaultnya, seperti "755").

5) Selanjutnya Anda perlu menghubungkan CIDRAM ke sistem atau CMS. Ada beberapa cara yang berbeda untuk menghubungkan skrip seperti CIDRAM ke sistem atau CMS, tapi yang paling mudah adalah memasukkan skrip pada permulaan dari file murni dari sistem atau CMS (satu yang akan secara umum di muat ketika seseorang mengakses halaman apapun pada website) berdasarkan pernyataan `require` atau `include`. Umumnya, ini akan menjadi sesuatu yang disimpan di sebuah direktori seperti `/includes`, `/assets` atau `/functions` dan akan selalu di namai sesuatu seperti `init.php`, `common_functions.php`, `functions.php` atau yang sama. Anda harus bekerja pada file apa untuk situasi ini; Jika Anda mengalami kesulitan dalam menentukan ini untuk diri sendiri, kunjungi halaman isu-isu (issues) CIDRAM di Github untuk bantuan. Untuk melakukannya [menggunakan `require` atau `include`], sisipkan baris kode dibawah pada file murni, menggantikan kata-kata berisikan didalam tanda kutip dari alamat file `loader.php` (alamat lokal, tidak alamat HTTP; akan terlihat seperti alamat vault yang di bicarakan sebelumnya).

`<?php require '/user_name/public_html/cidram/loader.php'; ?>`

Simpan file dan tutup. Upload kembali.

-- ATAU ALTERNATIF --

Jika Anda menggunakan webserver Apache dan jika Anda memiliki akses ke `php.ini`, Anda dapat menggunakan `auto_prepend_file` direktif untuk tambahkan CIDRAM setiap kali ada permintaan PHP dibuat. Sesuatu seperti:

`auto_prepend_file = "/user_name/public_html/cidram/loader.php"`

Atau ini di file `.htaccess`:

`php_value auto_prepend_file "/user_name/public_html/cidram/loader.php"`

6) Itu semuanya! :-)

---


###3. <a name="SECTION3"></a>BAGAIMANA CARA MENGGUNAKAN

CIDRAM harus secara otomatis memblokir permintaan yang tidak diinginkan ke website Anda tanpa memerlukan bantuan manual, selain dari instalasi.

Update dilakukan manual, dan Anda dapat menyesuaikan konfigurasi Anda dan menyesuaikan apa CIDRs diblokir oleh memodifikasi file konfigurasi Anda dan/atau file tanda tangan Anda.

Jika Anda menemukan positif palsu, tolong hubungi saya untuk membiarkan saya tahu tentang hal itu.

---


###4. <a name="SECTION4"></a>FILE YANG DIIKUTKAN DALAM PAKET INI

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
/_docs/readme.nl.md | Dokumentasi Bahasa Belanda.
/_docs/readme.pt.md | Dokumentasi Bahasa Portugis.
/_docs/readme.ru.md | Dokumentasi Bahasa Rusia.
/_docs/readme.vi.md | Dokumentasi Bahasa Vietnam.
/_docs/readme.zh-TW.md | Dokumentasi Cina tradisional.
/_docs/readme.zh.md | Dokumentasi Cina sederhana.
/vault/ | Direktori Vault (berisikan bermacam file).
/vault/fe_assets/ | Data untuk akses bagian depan.
/vault/fe_assets/.htaccess | File akses hiperteks (pada instansi ini, untuk melindungi file-file sensitif dari skrip untuk diakses dari sumber yang tidak terautorisasi).
/vault/fe_assets/_accounts.html | Template HTML untuk akses bagian depan halaman akun.
/vault/fe_assets/_accounts_row.html | Template HTML untuk akses bagian depan halaman akun.
/vault/fe_assets/_config.html | Template HTML untuk akses bagian depan halaman konfigurasi.
/vault/fe_assets/_home.html | Template HTML untuk akses bagian depan halaman utama.
/vault/fe_assets/_login.html | Template HTML untuk akses bagian depan halaman masuk.
/vault/fe_assets/_logs.html | Template HTML untuk akses bagian depan halaman log.
/vault/fe_assets/_nav_complete_access.html | Template HTML untuk akses bagian depan link navigasi, untuk mereka yang memiliki akses lengkap.
/vault/fe_assets/_nav_logs_access_only.html | Template HTML untuk akses bagian depan link navigasi, untuk mereka yang memiliki akses ke log hanya.
/vault/fe_assets/_updates.html | Template HTML untuk akses bagian depan halaman pembaruan.
/vault/fe_assets/_updates_row.html | Template HTML untuk akses bagian depan halaman pembaruan.
/vault/fe_assets/frontend.css | Style-sheet CSS untuk akses bagian depan.
/vault/fe_assets/frontend.dat | Database untuk akses bagian depan (berisi informasi akun, informasi sesi, dan cache; hanya dihasilkan jika akses bagian depan diaktifkan dan digunakan).
/vault/fe_assets/frontend.html | Template HTML utama untuk akses bagian depan.
/vault/lang/ | Berisikan file bahasa.
/vault/lang/.htaccess | File akses hiperteks (pada instansi ini, untuk melindungi file-file sensitif dari skrip untuk diakses dari sumber yang tidak terautorisasi).
/vault/lang/lang.ar.cli.php | File Bahasa Arab untuk CLI.
/vault/lang/lang.ar.fe.php | File Bahasa Arab untuk front-end.
/vault/lang/lang.ar.php | File Bahasa Arab.
/vault/lang/lang.de.cli.php | File Bahasa Jerman untuk CLI.
/vault/lang/lang.de.fe.php | File Bahasa Jerman untuk front-end.
/vault/lang/lang.de.php | File Bahasa Jerman.
/vault/lang/lang.en.cli.php | File Bahasa Inggris untuk CLI.
/vault/lang/lang.en.fe.php | File Bahasa Inggris untuk front-end.
/vault/lang/lang.en.php | File Bahasa Inggris.
/vault/lang/lang.es.cli.php | File Bahasa Spanyol untuk CLI.
/vault/lang/lang.es.fe.php | File Bahasa Spanyol untuk front-end.
/vault/lang/lang.es.php | File Bahasa Spanyol.
/vault/lang/lang.fr.cli.php | File Bahasa Perancis untuk CLI.
/vault/lang/lang.fr.fe.php | File Bahasa Perancis untuk front-end.
/vault/lang/lang.fr.php | File Bahasa Perancis.
/vault/lang/lang.id.cli.php | File Bahasa Indonesia untuk CLI.
/vault/lang/lang.id.fe.php | File Bahasa Indonesia untuk front-end.
/vault/lang/lang.id.php | File Bahasa Indonesia.
/vault/lang/lang.it.cli.php | File Bahasa Italia untuk CLI.
/vault/lang/lang.it.fe.php | File Bahasa Italia untuk front-end.
/vault/lang/lang.it.php | File Bahasa Italia.
/vault/lang/lang.ja.cli.php | File Bahasa Jepang untuk CLI.
/vault/lang/lang.ja.fe.php | File Bahasa Jepang untuk front-end.
/vault/lang/lang.ja.php | File Bahasa Jepang.
/vault/lang/lang.nl.cli.php | File Bahasa Belanda untuk CLI.
/vault/lang/lang.nl.fe.php | File Bahasa Belanda untuk front-end.
/vault/lang/lang.nl.php | File Bahasa Belanda.
/vault/lang/lang.pt.cli.php | File Bahasa Portugis untuk CLI.
/vault/lang/lang.pt.fe.php | File Bahasa Portugis untuk front-end.
/vault/lang/lang.pt.php | File Bahasa Portugis.
/vault/lang/lang.ru.cli.php | File Bahasa Rusia untuk CLI.
/vault/lang/lang.ru.fe.php | File Bahasa Rusia untuk front-end.
/vault/lang/lang.ru.php | File Bahasa Rusia.
/vault/lang/lang.vi.cli.php | File Bahasa Vietnam untuk CLI.
/vault/lang/lang.vi.fe.php | File Bahasa Vietnam untuk front-end.
/vault/lang/lang.vi.php | File Bahasa Vietnam.
/vault/lang/lang.zh-tw.cli.php | File Bahasa Cina tradisional untuk CLI.
/vault/lang/lang.zh-tw.fe.php | File Bahasa Cina tradisional untuk front-end.
/vault/lang/lang.zh-tw.php | File Bahasa Cina tradisional.
/vault/lang/lang.zh.cli.php | File Bahasa Cina sederhana untuk CLI.
/vault/lang/lang.zh.fe.php | File Bahasa Cina sederhana untuk front-end.
/vault/lang/lang.zh.php | File Bahasa Cina sederhana.
/vault/.htaccess | File akses hiperteks (pada instansi ini, untuk melindungi file-file sensitif dari skrip untuk diakses dari sumber yang tidak terautorisasi).
/vault/cache.dat | Cache data.
/vault/cli.php | Modul CLI.
/vault/components.dat | Berisi informasi yang berkaitan dengan berbagai komponen CIDRAM; Digunakan oleh fitur pembaruan disediakan oleh akses bagian depan.
/vault/config.ini.RenameMe | File konfigurasi CIDRAM; Berisi semua opsi konfigurasi dari CIDRAM, memberitahukannya apa yang harus dilakukan dan bagaimana mengoperasikannya dengan benar (mengubah nama untuk mengaktifkan).
/vault/config.php | Modul konfigurasi.
/vault/frontend.php | Modul untuk akses bagian depan.
/vault/functions.php | Modul fungsi (utama).
/vault/hashes.dat | Berisi daftar hash diterima (berkaitan dengan fitur reCAPTCHA; hanya dihasilkan jika fitur reCAPTCHA diaktifkan).
/vault/ignore.dat | File abaikan (digunakan untuk menentukan bagian tanda tangan CIDRAM harus mengabaikan).
/vault/ipbypass.dat | Berisi daftar bypass IP (berkaitan dengan fitur reCAPTCHA; hanya dihasilkan jika fitur reCAPTCHA diaktifkan).
/vault/ipv4.dat | File tanda tangan IPv4.
/vault/ipv4_custom.dat.RenameMe | File tanda tangan IPv4 disesuaikan (mengubah nama untuk mengaktifkan).
/vault/ipv6.dat | File tanda tangan IPv6.
/vault/ipv6_custom.dat.RenameMe | File tanda tangan IPv6 disesuaikan (mengubah nama untuk mengaktifkan).
/vault/lang.php | File bahasa.
/vault/outgen.php | Output Generator.
/vault/recaptcha.php | Modul reCAPTCHA.
/vault/rules_as6939.php | File aturan disesuaikan untuk AS6939.
/vault/rules_softlayer.php | File aturan disesuaikan untuk Soft Layer.
/vault/rules_specific.php | File aturan disesuaikan untuk beberapa CIDR spesifik.
/vault/salt.dat | File garam (digunakan oleh beberapa fungsi periferal; hanya dihasilkan jika diperlukan).
/vault/template.html | File template; File template untuk output diproduksi HTML oleh CIDRAM output generator.
/vault/template_custom.html | File template; File template untuk output diproduksi HTML oleh CIDRAM output generator.
/.gitattributes | Sebuah file proyek GitHub (tidak dibutuhkan untuk fungsi teratur dari skrip).
/Changelog.txt | Sebuah rekaman dari perubahan yang dibuat pada skrip ini di antara perbedaan versi (tidak dibutuhkan untuk fungsi teratur dari skrip).
/composer.json | Informasi untuk Composer/Packagist (tidak dibutuhkan untuk fungsi teratur dari skrip).
/LICENSE.txt | Salinan lisensi GNU/GPLv2 (tidak dibutuhkan untuk fungsi teratur dari skrip).
/loader.php | Pemuat/Loader. Ini yang apa Anda ingin masukkan (utama)!
/README.md | Ringkasan informasi proyek.
/web.config | Sebuah file konfigurasi ASP.NET (dalam instansi ini, untuk melindungi direktori `/vault` dari pengaksesan oleh sumber-sumber tidak terauthorisasi dalam kejadian yang mana skrip ini diinstal pada server berbasis teknologi ASP.NET).

---


###5. <a name="SECTION5"></a>OPSI KONFIGURASI
Berikut list variabel yang ditemukan pada file konfigurasi CIDRAM `config.ini`, dengan deskripsi dari tujuan dan fungsi.

####"general" (Kategori)
Konfigurasi umum dari CIDRAM.

"logfile"
- File yang dibaca oleh manusia untuk mencatat semua upaya akses diblokir. Spesifikasikan nama file, atau biarkan kosong untuk menonaktifkan.

"logfileApache"
- File yang dalam gaya Apache untuk mencatat semua upaya akses diblokir. Spesifikasikan nama file, atau biarkan kosong untuk menonaktifkan.

"logfileSerialized"
- File serial untuk mencatat semua upaya akses diblokir. Spesifikasikan nama file, atau biarkan kosong untuk menonaktifkan.

*Tip berguna: Jika Anda mau, Anda dapat menambahkan informasi tanggal/waktu untuk nama-nama file log Anda oleh termasuk ini dalam nama: `{yyyy}` untuk tahun lengkap, `{yy}` untuk tahun disingkat, `{mm}` untuk bulan, `{dd}` untuk hari, `{hh}` untuk jam.*

*Contoh:*
- *`logfile='logfile.{yyyy}-{mm}-{dd}-{hh}.txt'`*
- *`logfileApache='access.{yyyy}-{mm}-{dd}-{hh}.txt'`*
- *`logfileSerialized='serial.{yyyy}-{mm}-{dd}-{hh}.txt'`*

"timeOffset"
- Jika waktu server Anda tidak cocok waktu lokal Anda, Anda dapat menentukan offset sini untuk menyesuaikan informasi tanggal/waktu dihasilkan oleh CIDRAM sesuai dengan kebutuhan Anda. Ini umumnya direkomendasikan sebagai gantinya untuk menyesuaikan direktif zona waktu dalam file `php.ini` Anda, tapi terkadang (seperti ketika bekerja dengan terbatas penyedia shared hosting) ini tidak selalu mungkin untuk melakukan, dan demikian, opsi ini disediakan disini. Offset adalah dalam menit.
- Contoh (untuk menambahkan satu jam): `timeOffset=60`

"ipaddr"
- Dimana menemukan alamat IP dari permintaan alamat? (Bergunak untuk pelayanan-pelayanan seperti Cloudflare dan sejenisnya). Default = REMOTE_ADDR. PERINGATAN: Jangan ganti ini kecuali Anda tahu apa yang Anda lakukan!

"forbid_on_block"
- Apa header harus CIDRAM merespon dengan ketika memblokir permintaan? False/200 = 200 OK (Baik) [Default]; True = 403 Forbidden (Terlarang); 503 = 503 Service unavailable (Layanan tidak tersedia).

"silent_mode"
- Seharusnya CIDRAM diam-diam mengarahkan diblokir upaya akses bukannya menampilkan halaman "Akses Ditolak"? Jika ya, menentukan lokasi untuk mengarahkan diblokir upaya akses. Jika tidak, kosongkan variabel ini.

"lang"
- Tentukan bahasa default untuk CIDRAM.

"emailaddr"
- Jika Anda ingin, Anda dapat menyediakan alamat email sini untuk diberikan kepada pengguna ketika diblokir, bagi mereka untuk menggunakan sebagai metode kontak untuk dukungan dan/atau bantuan untuk dalam hal mereka menjadi diblokir keliru atau diblokir oleh kesalahan. PERINGATAN: Apapun alamat email Anda menyediakan sini akan pasti diperoleh oleh spambots dan pencakar/scrapers ketika digunakan disini, dan karena itu, jika Anda ingin memberikan alamat email disini, itu sangat direkomendasikan Anda memastikan bahwa alamat email yang Anda berikan disini adalah alamat yang dapat dibuang dan/atau adalah alamat Anda tidak keberatan menjadi di-spam (dengan kata lain, Anda mungkin tidak ingin untuk menggunakan Anda alamat email yang personal primer atau bisnis primer).

"disable_cli"
- Menonaktifkan modus CLI? Modus CLI diaktifkan secara default, tapi kadang-kadang dapat mengganggu alat pengujian tertentu (seperti PHPUnit, sebagai contoh) dan aplikasi CLI berbasis lainnya. Jika Anda tidak perlu menonaktifkan modus CLI, Anda harus mengabaikan direktif ini. False = Mengaktifkan modus CLI [Default]; True = Menonaktifkan modus CLI.

"disable_frontend"
- Menonaktifkan akses bagian depan? Akses bagian depan dapat membuat CIDRAM lebih mudah dikelola, tapi juga dapat menjadi potensial resiko keamanan. Itu direkomendasi untuk mengelola CIDRAM melalui bagian belakang bila mungkin, tapi akses bagian depan yang disediakan untuk saat itu tidak mungkin. Memilikinya dinonaktifkan kecuali jika Anda membutuhkannya. False = Mengaktifkan akses bagian depan; True = Menonaktifkan akses bagian depan [Default].

####"signatures" (Kategori)
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
- Memblokir CIDR umumnya direkomendasikan untuk mendaftar hitam / blacklist? Ini mencakup tanda tangan apapun yang tidak ditandai sebagai bagian dari apapun lainnya kategori tanda tangan lebih spesifik.

"block_proxies"
- Memblokir CIDR yang diidentifikasi sebagai milik layanan proxy? Jika Anda membutuhkan bahwa pengguna dapat mengakses situs web Anda dari layanan proxy anonymous, direktif ini harus didefinisikan untuk false/palsu. Jika Anda tidak membutuhkannya, direktif ini harus didefinisikan untuk true/benar sebagai sarana untuk meningkatkan keamanan.

"block_spam"
- Memblokir CIDR yang diidentifikasi sebagai beresiko tinggi karena spam? Kecuali jika Anda mengalami masalah ketika melakukan itu, umumnya, ini harus selalu didefinisikan untuk true/benar.

####"recaptcha" (Kategori)
Jika Anda ingin, Anda dapat memberikan pengguna dengan cara untuk memotong halaman "Akses Ditolak" dengan cara menyelesaikan instansi reCAPTCHA. Ini dapat membantu untuk mengurangi beberapa risiko terkait dengan positif palsu dalam situasi dimana kita tidak sepenuhnya yakin apakah permintaan telah berasal dari mesin atau manusia.

Karena risiko yang terkait dengan menyediakan cara bagi pengguna untuk melewati halaman "Akses Ditolak", umumnya, saya tidak akan menyarankan mengaktifkan fitur ini kecuali jika Anda merasa itu perlu untuk melakukannya. Situasi dimana bisa jadi diperlukan: Jika situs Web Anda memiliki pelanggan/pengguna yang perlu memiliki akses ke situs web Anda, dan jika ini adalah sesuatu yang tidak bisa dikompromikan, tapi jika pelanggan/pengguna Anda menghubungkan dari jaringan bermusuhan yang berpotensi juga membawa lalu lintas yang tidak diinginkan, dan memblokir lalu lintas yang tidak diinginkan ini juga sesuatu yang tidak bisa dikompromikan, pada mereka khususnya situasi tidak-menang, fitur reCAPTCHA ini bisa berguna sebagai sarana yang memungkinkan pelanggan/pengguna diinginkan, sambil menjaga keluar lalu lintas yang tidak diinginkan dari jaringan sama. Yang menyatakan meskipun, mengingat bahwa tujuan yang dimaksudkan dari CAPTCHA adalah untuk membedakan antara manusia dan non-manusia, fitur reCAPTCHA ini hanya akan membantu dalam situasi tidak-menang ini jika kita berasumsi bahwa lalu lintas yang tidak diinginkan ini adalah non-manusia (misalnya, spambot, scraper, alat peretas, lalu lintas otomatis), bukannya lalu lintas manusia yang tidak diinginkan (seperti spammer manusia, hacker, dan lain-lain).

Untuk mendapatkan "site key" dan "secret key" (diperlukan untuk menggunakan reCAPTCHA), silahkan ke: [https://developers.google.com/recaptcha/](https://developers.google.com/recaptcha/)

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

####"template_data" (Kategori)
Direktif-direktif dan variabel-variabel untuk template-template dan tema-tema.

Berkaitan dengan HTML digunakan untuk menghasilkan halaman "Akses Ditolak". Jika Anda menggunakan tema kustom untuk CIDRAM, HTML diproduksi yang bersumber dari file `template_custom.html`, dan sebaliknya, HTML diproduksi yang bersumber dari file `template.html`. Variabel ditulis untuk file konfigurasi bagian ini yang diurai untuk HTML diproduksi dengan cara mengganti nama-nama variabel dikelilingi dengan kurung keriting ditemukan dalam HTML diproduksi dengan file variabel sesuai. Sebagai contoh, dimana `foo="bar"`, setiap terjadinya `<p>{foo}</p>` ditemukan dalam HTML diproduksi akan menjadi `<p>bar</p>`.

"css_url"
- File template untuk tema kustom menggunakan properti CSS eksternal, sedangkan file template untuk tema default menggunakan properti CSS internal. Untuk menginstruksikan CIDRAM menggunakan file template untuk tema kustom, menentukan alamat HTTP publik file CSS tema kustom Anda menggunakan variable `css_url`. Jika Anda biarkan kosong variabel ini, CIDRAM akan menggunakan file template untuk tema default.

---


###6. <a name="SECTION6"></a>FORMAT TANDA TANGAN

####6.0 DASAR-DASAR

Deskripsi untuk format dan struktur digunakan oleh tanda tangan dari CIDRAM dapat ditemukan didokumentasikan dalam teks biasa dalam apapun dari dua file-file tanda tangan kustom. Silakan lihat dokumentasi ini untuk mempelajari lebih tentang format dan struktur digunakan oleh tanda tangan dari CIDRAM.

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

####6.1 TAG

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

Jika Anda ingin tanda tangan untuk berakhir setelah beberapa waktu, dengan cara yang sama untuk tag bagian, Anda dapat menggunakan "tag kadaluarsa" untuk menentukan kapan tanda tangan harus berhenti menjadi valid. Tag kadaluarsa menggunakan format "TTTT.BB.HH" (lihat contoh dibawah ini).

```
# Bagian 1.
1.2.3.4/32 Deny Generic
2.3.4.5/32 Deny Generic
Expires: 2016.12.31
```

Tag bagian dan tag kadaluarsa dapat digunakan bersama, dan mereka berdua opsional (lihat contoh dibawah ini).

```
# Contoh Bagian.
1.2.3.4/32 Deny Generic
Tag: Contoh Bagian
Expires: 2016.12.31
```

####6.2 YAML

#####6.2.0 DASAR-DASAR YAML

Sebuah bentuk sederhana YAML markup dapat digunakan dalam file tanda tangan untuk tujuan perilaku mendefinisikan dan direktif spesifik untuk bagian tanda tangan individu. Ini mungkin berguna jika Anda ingin nilai direktif konfigurasi berbeda atas dasar tanda tangan individu dan bagian tanda tangan (sebagai contoh; jika Anda ingin memberikan alamat email untuk tiket dukungan untuk setiap pengguna diblokir oleh satu tanda tangan tertentu, tapi tidak ingin memberikan alamat email untuk tiket dukungan untuk pengguna diblokir oleh tanda tangan lain; jika Anda ingin beberapa tanda tangan spesifik untuk memicu halaman redireksi; jika Anda ingin menandai bagian tanda tangan untuk digunakan dengan reCAPTCHA; jika Anda ingin merekam diblokir upaya akses untuk memisahkan file berdasarkan tanda tangan individu dan/atau bagian tanda tangan).

Penggunaan YAML markup dalam file tanda tangan sepenuhnya opsional (yaitu, Anda dapat menggunakannya jika Anda ingin melakukannya, tapi Anda tidak diharuskan untuk melakukannya), dan mampu memanfaatkan kebanyakan (tapi tidak semua) direktif konfigurasi.

Catat: Implementasi markup YAML di CIDRAM sangat sederhana dan sangat terbatas; Hal ini dimaksudkan untuk memenuhi kebutuhan spesifik untuk CIDRAM dengan cara yang memiliki keakraban dari YAML markup, tapi tidak mengikuti dengan spesifikasi resmi (dan karena itu tidak akan berperilaku dalam cara sama seperti implementasi yang lebih menyeluruh di tempat lain, dan mungkin tidak sesuai untuk proyek-proyek lain di tempat lain).

Dalam CIDRAM, segmen markup YAML diidentifikasi untuk script oleh tiga tanda hubung ("---"), dan mengakhiri dengan mengandung bagian tanda tangan mereka oleh dua jeda baris. Segmen markup YAML dalam bagian tanda tangan terdiri dari tiga tanda hubung pada baris segera setelah daftar CIDRs dan apapun tag, diikuti dengan daftar dua dimensi dari pasangan kunci-nilai (dimensi pertama, kategori direktif konfigurasi; dimensi kedua, direktif konfigurasi) untuk mana direktif konfigurasi yang harus dimodifikasi (dan yang nilai-nilai) setiap kali tanda tangan dalam yang bagian tanda tangan dipicu (lihat contoh dibawah ini).

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

#####6.2.1 BAGAIMANA "KHUSUS MENANDAI" BAGIAN TANDA TANGAN UNTUK DIGUNAKAN DENGAN reCAPTCHA

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

Catat: Sebuah instansi reCAPTCHA akan HANYA ditawarkan kepada pengguna jika reCAPTCHA diaktifkan (dengan "usemode" sebagai 1, atau "usemode" sebagai 2 dengan "enabled" sebagai true), dan jika tepat SATU tanda tangan dipicu (tidak lebih, tidak kurang; jika kelipatan tanda tangan dipicu, instansi reCAPTCHA TIDAK akan ditawarkan).

####6.3 INFORMASI TAMBAHAN

Juga, jika Anda ingin CIDRAM untuk sama sekali mengabaikan beberapa bagian tertentu dalam salah satu file tanda tangan, Anda dapat menggunakan file `ignore.dat` untuk menentukan bagian untuk mengabaikan. Pada baris baru, menulis `Ignore`, diikuti dengan spasi, diikuti dengan nama bagian yang Anda ingin CIDRAM untuk mengabaikan (lihat contoh dibawah ini).

```
Ignore Bagian 1
```

Mengacu pada file tanda tangan kustom untuk informasi lebih lanjut.

---


###7. <a name="SECTION7"></a>PERTANYAAN YANG SERING DIAJUKAN (FAQ)

####Apa yang dimaksud dengan "positif palsu"?

Istilah "positif palsu" (*alternatif: "kesalahan positif palsu"; "alarm palsu"*; Bahasa Inggris: *false positive*; *false positive error*; *false alarm*), dijelaskan dengan sangat sederhana, dan dalam konteks umum, digunakan saat pengujian untuk kondisi, untuk merujuk pada hasil tes, ketika hasilnya positif (yaitu, kondisi adalah dianggap untuk menjadi "positif", atau "benar"), namun diharapkan (atau seharusnya) menjadi negatif (yaitu, kondisi ini, pada kenyataannya, adalah "negatif", atau "palsu"). Sebuah "positif palsu" bisa dianggap analog dengan "menangis serigala" (dimana kondisi dites adalah apakah ada serigala di dekat kawanan, kondisi adalah "palsu" di bahwa tidak ada serigala di dekat kawanan, dan kondisi ini dilaporkan sebagai "positif" oleh gembala dengan cara memanggil "serigala, serigala"), atau analog dengan situasi dalam pengujian medis dimana seorang pasien didiagnosis sebagai memiliki beberapa penyakit, ketika pada kenyataannya, mereka tidak memiliki penyakit tersebut.

Hasil terkait ketika pengujian untuk kondisi dapat digambarkan menggunakan istilah "positif benar", "negatif benar" dan "negatif palsu". Sebuah "positif benar" mengacu pada saat hasil tes dan keadaan sebenarnya dari kondisi adalah keduanya benar (atau "positif"), dan sebuah "negatif benar" mengacu pada saat hasil tes dan keadaan sebenarnya dari kondisi adalah keduanya palsu (atau "negatif"); Sebuah "positif benar" atau "negatif benar" adalah dianggap untuk menjadi sebuah "inferensi benar". Antitesis dari "positif palsu" adalah sebuah "negatif palsu"; Sebuah "negatif palsu" mengacu pada saat hasil tes are negatif (yaitu, kondisi adalah dianggap untuk menjadi "negatif", atau "palsu"), namun diharapkan (atau seharusnya) menjadi positif (yaitu, kondisi ini, pada kenyataannya, adalah "positif", atau "benar").

Dalam konteks CIDRAM, istilah-istilah ini mengacu pada tanda tangan dari CIDRAM dan apa/siapa mereka memblokir. Ketika CIDRAM blok alamat IP karena buruk, usang atau salah tanda tangan, tapi seharusnya tidak melakukannya, atau ketika melakukannya untuk alasan salah, kita menyebut acara ini sebuah "positif palsu". Ketika CIDRAM gagal untuk memblokir alamat IP yang seharusnya diblokir, karena ancaman tak terduga, hilang tanda tangan atau kekurangan dalam tanda tangan nya, kita menyebut acara ini sebuah "deteksi terjawab" atau "missing detection" (ini analog dengan sebuah "negatif palsu").

Ini dapat diringkas dengan tabel di bawah:

&nbsp; | CIDRAM seharusnya *TIDAK* memblokir alamat IP | CIDRAM *SEHARUSNYA* memblokir alamat IP
---|---|---
CIDRAM *TIDAK* memblokir alamat IP | Negatif benar (inferensi benar) | Deteksi terjawab (analog dengan negatif palsu)
CIDRAM memblokir alamat IP | __Positif palsu__ | Positif benar (inferensi benar)

---


Terakhir Diperbarui: 5 November 2016 (2016.11.05).
