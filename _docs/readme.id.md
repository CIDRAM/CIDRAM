## Dokumentasi untuk CIDRAM (Bahasa Indonesia).

### Isi
- 1. [SEPATAH KATA](#SECTION1)
- 2. [BAGAIMANA CARA MENGINSTALL](#SECTION2)
- 3. [BAGAIMANA CARA MENGGUNAKAN](#SECTION3)
- 4. [FILE YANG DIIKUTKAN DALAM PAKET INI](#SECTION4)
- 5. [OPSI KONFIGURASI](#SECTION5)
- 6. [FORMAT TANDA TANGAN](#SECTION6)

---


###1. <a name="SECTION1"></a>SEPATAH KATA

CIDRAM (Classless Inter-Domain Routing Access Manager) adalah skrip PHP dirancang untuk melindungi situs oleh memblokir permintaan-permintaan berasal dari alamat IP yang dianggap sumber lalu lintas yang tidak diinginkan, termasuk (tapi tidak terbatas pada) lalu lintas dari jalur akses yang tidak manusia, layanan cloud, spambots, pencakar/scrapers, dll. Hal ini dilakukan melalui menghitung kisaran CIDR alamat IP dipasok dari permintaan dan mencoba untuk mencocokkan ini kisaran CIDR terhadap file tanda tangan (file signature ini berisi daftar CIDR alamat IP dianggap sumber lalu lintas yang tidak diinginkan); Jika dicocokkan, permintaan yang diblokir.

CIDRAM HAK CIPTA 2016 dan di atas GNU/GPLv2 oleh Caleb M (Maikuolan).

Skrip ini adalah perangkat lunak gratis; Anda dapat mendistribusikan kembali dan/atau memodifikasinya dalam batasan dari GNU General Public License, seperti di publikasikan dari Free Software Foundation; baik versi 2 dari License, atau (dalam opsi Anda) versi selanjutnya apapun. Skrip ini didistribusikan untuk harapan dapat digunakan tapi TANPA JAMINAN; tanpa walaupun garansi dari DIPERJUALBELIKAN atau KECOCOKAN UNTUK TUJUAN TERTENTU. Mohon Lihat GNU General Public Licence untuk lebih detail, terletak di file `LICENSE.txt` dan tersedia juga dari:
- <http://www.gnu.org/licenses/>.
- <http://opensource.org/licenses/>.

Dokumen ini dan paket terhubung di dalamnya dapat di unduh secara gratis dari [GitHub](https://github.com/Maikuolan/CIDRAM/).

---


###2. <a name="SECTION2"></a>BAGAIMANA CARA MENGINSTALL

Saya berharap untuk mempersingkat proses ini dengan membuat sebuah installer pada beberapa point di dalam masa depan yang tidak terlalu jauh, tapi kemudian, ikuti instruksi-instruksi ini untuk mendapatkan CIDRAM bekerja pada *banyak sistem dan CMS:

1) Dengan membaca ini, Saya asumsikan Anda telah mengunduh dan menyimpan copy dari skrip, membuka data terkompres dan isinya dan Anda meletakkannya pada mesin komputer lokal Anda. Dari sini, Anda akan latihan dimana di host Anda atau CMS Anda untuk meletakkan isi data terkompres nya. Sebuah direktori seperti `/public_html/cidram/` atau yang lain (walaupun tidak masalah Anda memilih direktori apa, selama dia aman dan dimana pun yang Anda senangi) akan mencukupi. *Sebelum Anda mulai upload, mohon baca dulu..*

2) Secara fakultatif (sangat direkomendasikan untuk user dengan pengalaman lebih lanjut, tapi tidak untuk pemula atau yang tidak berpengalaman), buka `config.ini` (berada di dalam `vault`) - File ini berisikan semua opsi operasional yang tersedia untuk CIDRAM. Di atas tiap opsi seharusnya ada komentar tegas menguraikan tentang apa yang dilakukan dan untuk apa. Atur opsi-opsi ini seperti Anda lihat cocok, seperti apapun yang cocok untuk setup tertentu. Simpan file, tutup.

3) Upload isi (CIDRAM dan file-filenya) ke direktori yang telah kamu putuskan sebelumnya (Anda tidak memerlukan file-file `*.txt`/`*.md`, tapi kebanyakan Anda harus mengupload semuanya).

4) Gunakan perinta CHMOD ke direktori `vault` dengan "777". Direktori utama menyimpan isinya (yang Anda putuskan sebelumnya), umumnya dapat di biarkan sendirian, tapi status perintah "CHMOD" seharusnya di cek jika kamu punya izin di sistem Anda (defaultnya, seperti "755").

5) Selanjutnya Anda perlu menghubungkan CIDRAM ke sistem atau CMS. Ada beberapa cara yang berbeda untuk menghubungkan skrip seperti CIDRAM ke sistem atau CMS, tetapi yang paling mudah adalah memasukkan skrip pada permulaan dari file murni dari sistem atau CMS (satu yang akan secara umum di muat ketika seseorang mengakses halaman apapun pada website) berdasarkan pernyataan `require` atau `include`. Umumnya, ini akan menjadi sesuatu yang disimpan di sebuah direktori seperti `/includes`, `/assets` atau `/functions` dan akan selalu di namai sesuatu seperti `init.php`, `common_functions.php`, `functions.php` atau yang sama. Anda harus bekerja pada file apa untuk situasi ini; Jika Anda mengalami kesulitan dalam menentukan ini untuk diri sendiri, kunjungi isu-isu (issues) halaman CIDRAM di GitHub. Untuk melakukannya [menggunakan `require` atau `include`], sisipkan baris kode dibawah pada file murni, menggantikan kata-kata berisikan didalam tanda kutip dari alamat file `loader.php` (alamat lokal, tidak alamat HTTP; akan terlihat seperti alamat vault yang di bicarakan sebelumnya).

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
/.gitattributes | Sebuah file proyek GitHub (tidak dibutuhkan untuk fungsi teratur dari skrip).
/Changelog.txt | Sebuah rekaman dari perubahan yang dibuat pada skrip ini di antara perbedaan versi (tidak dibutuhkan untuk fungsi teratur dari skrip).
/composer.json | Informasi untuk Composer/Packagist (tidak dibutuhkan untuk fungsi teratur dari skrip).
/LICENSE.txt | Salinan lisensi GNU/GPLv2.
/loader.php | Pemuat/Loader. Ini yang apa Anda ingin masukkan (utama)!
/README.md | Ringkasan informasi proyek.
/web.config | Sebuah file konfigurasi ASP.NET (dalam instansi ini, untuk melindungi direktori `/vault` dari pengaksesan oleh sumber-sumber tidak terauthorisasi dalam kejadian yang mana skrip ini diinstal pada server berbasis teknologi ASP.NET).
/_docs/ | Direktori dokumentasi (berisi bermacam file).
/_docs/readme.de.md | Dokumentasi Bahasa Jerman.
/_docs/readme.en.md | Dokumentasi Bahasa Inggris.
/_docs/readme.es.md | Dokumentasi Bahasa Spanyol.
/_docs/readme.fr.md | Dokumentasi Bahasa Perancis.
/_docs/readme.id.md | Dokumentasi Bahasa Indonesia.
/_docs/readme.it.md | Dokumentasi Bahasa Italia.
/_docs/readme.nl.md | Dokumentasi Bahasa Belanda.
/_docs/readme.pt.md | Dokumentasi Bahasa Portugis.
/_docs/readme.zh-TW.md | Dokumentasi Cina Tradisional.
/_docs/readme.zh.md | Dokumentasi Cina Sederhana.
/vault/ | Direktori Vault (berisikan bermacam file).
/vault/.htaccess | Sebuah file akses hiperteks (pada instansi ini, untuk melindungi file-file sensitif dari skrip untuk diakses dari sumber yang tidak terautorisasi).
/vault/cache.dat | Cache data.
/vault/cli.php | Modul CLI.
/vault/config.ini | File konfigurasi CIDRAM; Berisi semua opsi konfigurasi dari CIDRAM, memberitahukannya apa yang harus dilakukan dan bagaimana mengoperasikannya dengan benar (utama)!
/vault/config.php | Modul konfigurasi.
/vault/functions.php | Modul fungsi (utama).
/vault/ipv4.dat | File tanda tangan IPv4.
/vault/ipv4_custom.dat | File tanda tangan IPv4 disesuaikan.
/vault/ipv6.dat | File tanda tangan IPv6.
/vault/ipv6_custom.dat | File tanda tangan IPv6 disesuaikan.
/vault/lang.php | File bahasa.
/vault/lang/ | Berisikan file bahasa.
/vault/lang/.htaccess | Sebuah file akses hiperteks (pada instansi ini, untuk melindungi file-file sensitif dari skrip untuk diakses dari sumber yang tidak terautorisasi).
/vault/lang/lang.en.php | File Bahasa Inggris.
/vault/lang/lang.es.php | File Bahasa Spanyol.
/vault/lang/lang.fr.php | File Bahasa Perancis.
/vault/lang/lang.id.php | File Bahasa Indonesia.
/vault/lang/lang.it.php | File Bahasa Italia.
/vault/lang/lang.nl.php | File Bahasa Belanda.
/vault/lang/lang.pt.php | File Bahasa Portugis.
/vault/lang/lang.zh-TW.php | File Bahasa Cina tradisional.
/vault/lang/lang.zh.php | File Bahasa Cina sederhana.
/vault/outgen.php | Output generator.
/vault/template.html | File template; File template untuk output diproduksi HTML oleh CIDRAM output generator.
/vault/template_custom.html | File template; File template untuk output diproduksi HTML oleh CIDRAM output generator.
/vault/rules_as6939.php | File aturan disesuaikan untuk AS6939.
/vault/rules_softlayer.php | File aturan disesuaikan untuk Soft Layer.

---


###5. <a name="SECTION5"></a>OPSI KONFIGURASI
Berikut list variabel yang ditemukan pada file konfigurasi CIDRAM `config.ini`, dengan deskripsi dari tujuan dan fungsi.

####"general" (Kategori)
Konfigurasi umum dari CIDRAM.

"logfile"
- Nama file untuk mencatat semua akses upaya yang diblokir. Spesifikasikan nama file, atau biarkan kosong untuk menonaktifkan.

"ipaddr"
- Dimana menemukan alamat IP dari permintaan alamat? (Bergunak untuk pelayanan-pelayanan seperti Cloudflare dan sejenisnya). Default = REMOTE_ADDR. PERINGATAN: Jangan ganti ini kecuali Anda tahu apa yang Anda lakukan!

"forbid_on_block"
- Seharusnya CIDRAM menanggapi dengan 403 header untuk permintaan diblokir, atau cocok dengan 200 OK? False = Tidak (200) [Default]; True = Ya (403).

"lang"
- Tentukan bahasa default untuk CIDRAM.

"emailaddr"
- Jika Anda ingin, Anda dapat menyediakan alamat email sini untuk diberikan kepada pengguna ketika diblokir, bagi mereka untuk menggunakan sebagai metode kontak untuk dukungan dan/atau bantuan untuk dalam hal mereka menjadi diblokir keliru atau diblokir oleh kesalahan. PERINGATAN: Apapun alamat email Anda menyediakan sini akan pasti diperoleh oleh spambots dan pencakar/scrapers ketika digunakan disini, dan karena itu, jika Anda ingin memberikan alamat email disini, itu sangat direkomendasikan Anda memastikan bahwa alamat email yang Anda berikan disini adalah alamat yang dapat dibuang dan/atau adalah alamat Anda tidak keberatan menjadi di-spam (dengan kata lain, Anda mungkin tidak ingin untuk menggunakan Anda alamat email yang personal primer atau bisnis primer).

####"signatures" (Kategori)
Konfigurasi untuk tanda tangan.

"block_cloud"
- Memblokir CIDR yang diidentifikasi sebagai milik webhosting dan/atau layanan cloud? Jika Anda mengoperasikan layanan API dari website Anda atau jika Anda mengharapkan website lain untuk menghubungkan ke website Anda, direktif ini harus didefinisikan untuk false/palsu. Jika Anda tidak, maka, direktif ini harus didefinisikan untuk true/benar.

"block_bogons"
- Memblokir CIDR bogon/martian? Jika Anda mengharapkan koneksi ke website Anda dari dalam jaringan lokal Anda, dari localhost, atau dari LAN Anda, direktif ini harus didefinisikan untuk false/palsu. Jika Anda tidak mengharapkan ini, direktif ini harus didefinisikan untuk true/benar.

"block_generic"
- Memblokir CIDR umumnya direkomendasikan untuk mendaftar hitam / blacklist? Ini mencakup tanda tangan apapun yang tidak ditandai sebagai bagian dari apapun lainnya kategori tanda tangan lebih spesifik.

"block_spam"
- Memblokir CIDR yang diidentifikasi sebagai beresiko tinggi karena spam? Kecuali jika Anda mengalami masalah ketika melakukan itu, umumnya, ini harus selalu didefinisikan untuk true/benar.

---


###6. <a name="SECTION6"></a>FORMAT TANDA TANGAN

Deskripsi untuk format dan struktur digunakan oleh tanda tangan dari CIDRAM dapat ditemukan didokumentasikan dalam teks biasa dalam apapun dari dua file-file tanda tangan kustom. Silakan lihat dokumentasi ini untuk mempelajari lebih tentang format dan struktur digunakan oleh tanda tangan dari CIDRAM.

---


Terakhir Diperbarui: 18 Maret 2016 (2016.03.18).
