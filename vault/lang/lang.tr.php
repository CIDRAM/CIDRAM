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
 * This file: Turkish language data (last modified: 2017.12.21).
 */

/** Prevents execution from outside of CIDRAM. */
if (!defined('CIDRAM')) {
    die('[CIDRAM] This should not be accessed directly.');
}

/** Language plurality rule. */
$CIDRAM['Plural-Rule'] = function($Num) {
    return $Num <= 1 ? 0 : 1;
};

$CIDRAM['lang']['click_here'] = 'buraya tıklayın';
$CIDRAM['lang']['denied'] = 'Erişim Reddedildi!';
$CIDRAM['lang']['Error_WriteCache'] = 'Önbelleğe yazılamıyor! Lütfen CHMOD dosya izinlerinizi kontrol edin!';
$CIDRAM['lang']['fake_ua'] = 'Sahte {ua}';
$CIDRAM['lang']['field_datetime'] = 'Tarih/Saat: ';
$CIDRAM['lang']['field_hostname'] = 'Hostname: ';
$CIDRAM['lang']['field_id'] = 'İD: ';
$CIDRAM['lang']['field_ipaddr'] = 'İP Adresi: ';
$CIDRAM['lang']['field_ipaddr_resolved'] = 'İP Adresi (Kararlı): ';
$CIDRAM['lang']['field_query'] = 'Sorgu: ';
$CIDRAM['lang']['field_reCAPTCHA_state'] = 'reCAPTCHA durumu: ';
$CIDRAM['lang']['field_referrer'] = 'Yönlendiren: ';
$CIDRAM['lang']['field_rURI'] = 'Yeniden yapılandırılmış URI: ';
$CIDRAM['lang']['field_scriptversion'] = 'Komut Dosyası (Script) Sürümü: ';
$CIDRAM['lang']['field_sigcount'] = 'İmza Sayısı: ';
$CIDRAM['lang']['field_sigref'] = 'İmza Referansı: ';
$CIDRAM['lang']['field_ua'] = 'Kullanıcı Aracısı: ';
$CIDRAM['lang']['field_whyreason'] = 'Neden Engellendi: ';
$CIDRAM['lang']['generated_by'] = 'Üreten';
$CIDRAM['lang']['preamble'] = '-- Başlama eki sonu. Bu satırdan sonra sorularınızı veya yorumlarınızı ekleyin. --';
$CIDRAM['lang']['ReasonMessage_BadIP'] = 'Geçersiz bir IP adresi kullanarak bu sayfaya erişmeye çalıştığınızdan bu sayfaya erişiminiz reddedildi.';
$CIDRAM['lang']['ReasonMessage_Banned'] = 'IP adresinizden önceki kötü davranış nedeniyle bu sayfaya erişiminiz reddedildi.';
$CIDRAM['lang']['ReasonMessage_Bogon'] = 'IP adresiniz bir bogon adresi olarak tanındığı ve bu web sitesine bogonlardan başlanmanın web sitesi sahibi tarafından izin verilmemesi nedeniyle bu sayfaya erişiminiz reddedildi.';
$CIDRAM['lang']['ReasonMessage_Cloud'] = 'IP adresiniz bir bulut hizmetine ait olduğu ve bulut servislerinden bu web sitesine bağlanmaya web sitesi sahibi tarafından izin verilmemesi nedeniyle bu sayfaya erişiminiz reddedildi.';
$CIDRAM['lang']['ReasonMessage_Generic'] = 'IP adresiniz, bu web sitesi tarafından kullanılan bir kara listede listelenen bir ağa ait olduğu için, bu sayfaya erişiminiz reddedildi.';
$CIDRAM['lang']['ReasonMessage_Proxy'] = 'IP adresinizin bir aracı site (proxy) hizmetine ait olduğu kabul edildiğinden ve bu web sitesine aracı site hizmetleri aracılığıyla bağlanmaya web sitesi sahibi tarafından izin verilmemesi nedeniyle bu sayfaya erişiminiz reddedildi.';
$CIDRAM['lang']['ReasonMessage_Spam'] = 'IP adresiniz istenmeyen eposta için yüksek riskli olduğu düşünülen bir ağa ait olduğu için bu sayfaya erişiminiz reddedildi.';
$CIDRAM['lang']['recaptcha_cookie_warning'] = 'Not: Kullanıcılar CAPTCHA\'yı tamamladıkları zaman CIDRAM hatırlamak için çerez kullanır. CAPTCHA\'yi tamamlayarak, tarayıcınız tarafından oluşturulacak ve depolanacak bir çerez için onay vermiş olursunuz.';
$CIDRAM['lang']['recaptcha_disabled'] = 'Devre dışı.';
$CIDRAM['lang']['recaptcha_enabled'] = 'Etkinleştirildi.';
$CIDRAM['lang']['recaptcha_failed'] = 'Başarısız oldu!';
$CIDRAM['lang']['recaptcha_message'] = 'Bu sayfaya tekrar erişmek için lütfen aşağıda verilen CAPTCHA\'yi doldurun ve gönderme düğmesine basın.';
$CIDRAM['lang']['recaptcha_message_invisible'] = 'Çoğu kullanıcı için, sayfa yenilenmeli ve normal erişime geri dönülmelidir. Bazı durumlarda, bir CAPTCHA zorluğu doldurmanız istenebilir.';
$CIDRAM['lang']['recaptcha_passed'] = 'Geçildi!';
$CIDRAM['lang']['recaptcha_submit'] = 'Gönder';
$CIDRAM['lang']['Short_BadIP'] = 'Geçersiz IP';
$CIDRAM['lang']['Short_Banned'] = 'Yasaklandı';
$CIDRAM['lang']['Short_Bogon'] = 'Bogon IP';
$CIDRAM['lang']['Short_Cloud'] = 'Bulut hizmeti';
$CIDRAM['lang']['Short_Generic'] = 'Genel';
$CIDRAM['lang']['Short_Proxy'] = 'Aracı site (proxy)';
$CIDRAM['lang']['Short_Spam'] = 'İstenmeyen eposta riski';
$CIDRAM['lang']['Support_Email'] = 'Bunun yanlış olduğuna inanıyorsanız veya yardım almak için, bu sitesinin yöneticisine bir e-posta destek bileti göndermek için {ClickHereLink} (e-postanın önsözünü veya başlama ekini değiştirmeyin).';
$CIDRAM['lang']['Support_Email_2'] = 'Bunun yanlış olduğuna inanıyorsanız, yardım istemek için {EmailAddr} ya bir e-posta gönderin.';
