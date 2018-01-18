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
 * This file: Urdu language data for CLI (last modified: 2018.01.18).
 */

/** Prevents execution from outside of CIDRAM. */
if (!defined('CIDRAM')) {
    die('[CIDRAM] This should not be accessed directly.');
}

$CIDRAM['lang']['CLI_H'] = "
 CIDRAM CLI امداد.

 استعمال کریں:
 /راہ/کرنا/php/php.exe /راہ/کرنا/cidram/loader.php -پرچم (ان پٹ)

 پرچم:
    -h  اس مدد کی معلومات دکھائیں.
    -c  چیک کریں اگر ایک IP ایڈریس CIDRAM دستخط کی فائلوں کی طرف سے بلاک کیا جاتا ہے.
    -g  ایک IP ایڈریس سے CIDR پیدا.

 ان پٹ: یہ کسی بھی IPv4 یا IPv6 ایڈریس درست ہے ہو سکتا ہے.

 مثال کے طور پر:
        -c  192.168.0.0/16
        -c  127.0.0.1/32
        -c  2001:db8::/32
        -c  2002::1/128

";

$CIDRAM['lang']['CLI_Bad_IP'] = ' اختصاصی IP ایڈریس، "{IP}" ایک درست IPv4 یا IPv6 IP پتہ نہیں ہے!';
$CIDRAM['lang']['CLI_IP_Blocked'] = ' اختصاصی IP ایڈریس، "{IP}"، ایک یا ایک سے CIDRAM دستخط کی فائلوں میں سے زیادہ کی طرف سے بلاک کیا جاتا ہے.';
$CIDRAM['lang']['CLI_IP_Not_Blocked'] = ' اختصاصی IP ایڈریس، "{IP}"، CIDRAM دستخط کی فائلوں میں سے کسی کی طرف سے بلاک نہیں ہے.';

$CIDRAM['lang']['CLI_F_Finished'] = 'دستخط فکسر "%s" کی کارروائیوں کے دوران کی گئی "%s" کی تبدیلیوں کے ساتھ، ختم ہو گیا ہے (%s).';
$CIDRAM['lang']['CLI_F_Started'] = 'دستخط فکسر شروع کر دیا ہے (%s).';
$CIDRAM['lang']['CLI_VF_Empty'] = 'واضع دستخط کی فائل خالی ہے یا موجود نہیں ہے.';
$CIDRAM['lang']['CLI_VF_Level_0'] = 'نوٹس';
$CIDRAM['lang']['CLI_VF_Level_1'] = 'انتباہ';
$CIDRAM['lang']['CLI_VF_Level_2'] = 'خرابی';
$CIDRAM['lang']['CLI_VF_Level_3'] = 'مہلک غلطی';

$CIDRAM['lang']['CLI_V_CRLF'] = 'دستخط کی فائل میں پایا CR/CRLF؛ یہ جائز ہیں اور مسائل پیدا نہیں کرے گا، لیکن LF افضل ہے.';
$CIDRAM['lang']['CLI_V_Finished'] = 'دستخط جوازدہندہ (%s) ختم ہو گیا ہے. انتباہ یا غلطیوں کو کوئی ظاہر ہوا ہے تو، آپ کے دستخط کی فائل *شاید ہے* ٹھیک. :-)';
$CIDRAM['lang']['CLI_V_LineByLine'] = 'سطر بہ سطر کی توثیق شروع کر دیا ہے.';
$CIDRAM['lang']['CLI_V_Started'] = 'دستخط جوازدہندہ شروع کر دیا ہے (%s).';
$CIDRAM['lang']['CLI_V_Terminal_LF'] = 'دستخط فائلوں کو ایک LF لکیر وقفے کے ساتھ ختم کرنا چاہئے.';

$CIDRAM['lang']['CLI_VL_CC'] = 'L%s: کنٹرول حروف کا پتہ چلا؛ یہ کرپشن کی نشاندہی کر سکتا ہے اور تحقیقات کی جانی چاہئے.';
$CIDRAM['lang']['CLI_VL_Duplicated'] = 'L%s: دستخط "%s" کو دوہرایا گیا ہے ( "%s" کو شمار)!';
$CIDRAM['lang']['CLI_VL_Expiry'] = 'L%s: ختم ہونے ٹیگ موزوں ISO 8601 تاریخ / وقت پر مشتمل نہیں ہے!';
$CIDRAM['lang']['CLI_VL_Invalid'] = 'L%s: "%s" کو * نہیں * ایک درست IPv4 یا IPv6 ایڈریس ہے!';
$CIDRAM['lang']['CLI_VL_L120'] = 'L%s: لائن کی لمبائی 120 بائٹس سے زیادہ ہے؛ لائن کی لمبائی زیادہ سے زیادہ پڑھنے کی اہلیت کے لئے 120 بائٹس تک محدود کیا جانا چاہئے.';
$CIDRAM['lang']['CLI_VL_Mergeable'] = 'L%s اور L%s کو ایک جیسی ہیں، اور اس طرح، قابل ضم.';
$CIDRAM['lang']['CLI_VL_Missing'] = 'L%s: لاپتہ (فنکشن)؛ دستخط نامکمل ہونا ظاہر ہوتا ہے.';
$CIDRAM['lang']['CLI_VL_Nontriggerable'] = 'L%s: "%s" غیر ٹرگر کے قابل اس کی بنیاد اس کی رینج کے آغاز سے مطابقت نہیں رکھتا ہے! ساتھ اس کی جگہ کوشش "%s".';
$CIDRAM['lang']['CLI_VL_Nontriggerable_Range'] = 'L%s: "%s" غیر ٹرگر کے قابل "%s" کو ایک درست رینج نہیں ہے!';
$CIDRAM['lang']['CLI_VL_Origin'] = 'L%s: اصل ٹیگ میں ایک درست آیزو 3166-1 کوڈ نہیں ہے!';
$CIDRAM['lang']['CLI_VL_Subordinate'] = 'L%s: "%s" پہلے سے موجود "%s" کو دستخطی کے ماتحت ہے.';
$CIDRAM['lang']['CLI_VL_Superset'] = 'L%s: "%s" یک سپر پہلے سے موجود "%s" کو دستخط کے لئے مقرر کیا جاتا ہے.';
$CIDRAM['lang']['CLI_VL_Syntax'] = 'L%s: syntactically ہے عین مطابق نہیں.';
$CIDRAM['lang']['CLI_VL_Tabs'] = 'L%s: ٹیبز کے پتہ؛ خالی جگہوں کو زیادہ سے زیادہ پڑھنے کی اہلیت کے لئے ٹیبز سے زیادہ ترجیح دی جاتی ہے.';
$CIDRAM['lang']['CLI_VL_Tags'] = 'L%s: دفعہ ٹیگ 20 بائٹس سے زیادہ ہے؛ دفعہ ٹیگز واضح اور جامع ہونا چاہئے.';
$CIDRAM['lang']['CLI_VL_Unrecognised'] = 'L%s: غیر شناخت شدہ (فنکشن)؛ دستخط توڑا جا سکتا تھا.';
$CIDRAM['lang']['CLI_VL_Whitespace'] = 'L%s: اضافی پیچھے سفید فاصلہ اس لائن پر پتہ لگایا.';
$CIDRAM['lang']['CLI_VL_YAML'] = 'L%s: YAML نما ​​کے اعداد و شمار سے پتہ چلا، لیکن یہ کارروائی نہیں کر سکے.';
