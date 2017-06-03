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
 * This file: Turkish language data for CLI (last modified: 2017.06.03).
 */

/** Prevents execution from outside of CIDRAM. */
if (!defined('CIDRAM')) {
    die('[CIDRAM] This should not be accessed directly.');
}

$CIDRAM['lang']['CLI_H'] = "
 CIDRAM CLI mod yardımı

 Kullanım:
 /klasör/yolu/php/php.exe /klasör/yolu/cidram/loader.php -Bayraklar (Giriş)

 Bayraklar: -h  Bu yardım bilgisini görüntüleyin.
            -c  IP adresinin CIDRAM imza dosyaları tarafından engellenip
                engellenmediğini kontrol edin.
            -g  IP adresinden CIDR'ler üretin.

 Giriş: Herhangi bir geçerli IPv4 veya IPv6 IP adresi olabilir.

 Örnekler:
        -c  192.168.0.0/16
        -c  127.0.0.1/32
        -c  2001:db8::/32
        -c  2002::1/128

";

$CIDRAM['lang']['CLI_Bad_IP'] = ' Belirtilen IP adresi "{IP}", geçerli bir IPv4 veya IPv6 IP adresi değil!';
$CIDRAM['lang']['CLI_IP_Blocked'] = ' Belirtilen IP adresi "{IP}", bir veya daha fazla CIDRAM imza dosyası tarafından *ENGELLENMİŞTİR*.';
$CIDRAM['lang']['CLI_IP_Not_Blocked'] = ' Belirtilen IP adresi "{IP}", hiçbir CIDRAM imza dosyası tarafından *ENGELLENMEMİŞTİR*.';

$CIDRAM['lang']['CLI_F_Finished'] = '%2$s işlemleri üzerinden yapılan %1$s değişiklikler ile (%3$s), imza düzeltici tamamlandı.';
$CIDRAM['lang']['CLI_F_Started'] = 'İmza düzeltici başlatıldı (%s).';
$CIDRAM['lang']['CLI_VF_Empty'] = 'Belirtilen imza dosyası boş veya mevcut değil.';
$CIDRAM['lang']['CLI_VF_Level_0'] = 'Not';
$CIDRAM['lang']['CLI_VF_Level_1'] = 'Uyarı';
$CIDRAM['lang']['CLI_VF_Level_2'] = 'Hata';
$CIDRAM['lang']['CLI_VF_Level_3'] = 'Ölümcül Hata';

$CIDRAM['lang']['CLI_V_CRLF'] = 'İmza dosyasında CR/CRLF tespit edildi; Bunlara izin verilebilir ve soruna neden olmaz, ancak LF tercih edilir.';
$CIDRAM['lang']['CLI_V_Finished'] = 'İmza doğrulayıcı bitti (%s). Hiçbir uyarı veya hata ortaya çıkmadıysa, imza dosyanız *muhtemelen* tamamdır. :-)';
$CIDRAM['lang']['CLI_V_LineByLine'] = 'Satır satır doğrulama başladı.';
$CIDRAM['lang']['CLI_V_Started'] = 'İmza doğrulayıcı başladı (%s).';
$CIDRAM['lang']['CLI_V_Terminal_LF'] = 'İmza dosyaları bir LF satır sonu ile sonlanmalı.';

$CIDRAM['lang']['CLI_VL_CC'] = 'L%s: Kontrol karakterleri bulundu; Bu bozulma anlamına gelebilir ve araştırılmalıdır.';
$CIDRAM['lang']['CLI_VL_Duplicated'] = 'L%s: İmza "%s" kopyalanmış (%s sayıda)!';
$CIDRAM['lang']['CLI_VL_Expiry'] = 'L%s: Süre bitim etiketi geçerli bir ISO 8601 tarih/saatini içermiyor!';
$CIDRAM['lang']['CLI_VL_Invalid'] = 'L%s: "%s", geçerli bir IPv4 veya IPv6 adresi *DEĞİL*!';
$CIDRAM['lang']['CLI_VL_L120'] = 'L%s: Satır uzunluğu 120 bayttan büyük; Eniyi okunabilirlik için satır uzunluğu 120 bayt ile sınırlandırılmalıdır.';
$CIDRAM['lang']['CLI_VL_Mergeable'] = 'L%s ve L%s aynıdır ve bu yüzden birleştirilebilir.';
$CIDRAM['lang']['CLI_VL_Missing'] = 'L%s: Eksik [İşlev]; İmza eksik görünüyor.';
$CIDRAM['lang']['CLI_VL_Nontriggerable'] = 'L%s: "%s" tetiklenemez! Tabanı, aralığının başlangıcıyla eşleşmiyor! "%s" ile değiştirmeyi deneyin.';
$CIDRAM['lang']['CLI_VL_Nontriggerable_Range'] = 'L%s: "%s" tetiklenemez! "%s" geçerli bir aralık değil!';
$CIDRAM['lang']['CLI_VL_Subordinate'] = 'L%s: "%s", mevcut "%s" imzasının emri altındadır.';
$CIDRAM['lang']['CLI_VL_Superset'] = 'L%s: "%s", zaten var olan "%s" imzasının üst kümesidir.';
$CIDRAM['lang']['CLI_VL_Syntax'] = 'L%s: Sözdizimsel olarak kesin değil.';
$CIDRAM['lang']['CLI_VL_Tabs'] = 'L%s: Sekmeler bulundu; Eniyi okunabilirlik için sekmeler üzerinde boşluklar tercih edilir.';
$CIDRAM['lang']['CLI_VL_Tags'] = 'L%s: Bölüm etiketi 20 bayttan büyük; Bölüm etiketleri açık ve öz olmalıdır.';
$CIDRAM['lang']['CLI_VL_Unrecognised'] = 'L%s: Tanınmayan [İşlev]; İmza bozuk olabilir.';
$CIDRAM['lang']['CLI_VL_Whitespace'] = 'L%s: Bu satırda kalan fazla beyaz boşluk bulundu.';
$CIDRAM['lang']['CLI_VL_YAML'] = 'L%s: YAML benzeri veri tespit edildi, ancak işlemi yapılamadı.';
