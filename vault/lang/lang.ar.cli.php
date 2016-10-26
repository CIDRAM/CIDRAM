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
 * This file: Arabic language data for CLI (last modified: 2016.10.26).
 */

/** Prevents execution from outside of CIDRAM. */
if (!defined('CIDRAM')) {
    die('[CIDRAM] This should not be accessed directly.');
}

$CIDRAM['lang']['CLI_H'] = "
 CIDRAM CLI المساعدة.

 استعمال:
 /الطريق/إلى/php/php.exe /الطريق/إلى/cidram/loader.php -علامة (إدخال)

 علامة:
    -h  عرض هذه المعلومات المساعدة.
    -c  تحقق إذا تم حظر عنوان IP.
    -g  توليد CIDR من عنوان IP.

 إدخال: يمكن أن يكون أي عنوان IPv4 أو IPv6 صالح.

 أمثلة:
        -c  192.168.0.0/16
        -c  127.0.0.1/32
        -c  2001:db8::/32
        -c  2002::1/128

";

$CIDRAM['lang']['CLI_Bad_IP'] = ' عنوان IP، "{IP}"، ليس عنوانا صالحا IPv4 أو IPv6!';
$CIDRAM['lang']['CLI_IP_Blocked'] = ' عنوان IP، "{IP}"، يتم حظر.';
$CIDRAM['lang']['CLI_IP_Not_Blocked'] = ' عنوان IP، "{IP}"، لا يتم حظر.';

$CIDRAM['lang']['CLI_F_Finished'] = 'التوقيع مصحح انتهى، مع %s تغيرات بواسطة %s عمليات (%s).';
$CIDRAM['lang']['CLI_F_Started'] = 'التوقيع مصحح بدأت (%s).';
$CIDRAM['lang']['CLI_VF_Empty'] = 'ملف التوقيع فارغ أو غير موجود.';
$CIDRAM['lang']['CLI_VF_Level_0'] = 'إشعار';
$CIDRAM['lang']['CLI_VF_Level_1'] = 'تحذير';
$CIDRAM['lang']['CLI_VF_Level_2'] = 'خطأ';
$CIDRAM['lang']['CLI_VF_Level_3'] = 'خطأ فادح';

$CIDRAM['lang']['CLI_V_CRLF'] = 'الكشف عن CR/CRLF في ملف التوقيع؛ هذه هي المسموح بها ولن يسبب مشاكل، لكن LF هو الأفضل.';
$CIDRAM['lang']['CLI_V_Finished'] = 'التوقيع مدقق انتهى (%s). إذا لم تكن هناك أية تحذيرات أو أخطاء، ملف التوقيع الخاص بك هو على الأرجح بخير.';
$CIDRAM['lang']['CLI_V_LineByLine'] = 'سطرا سطرا مدقق بدأت.';
$CIDRAM['lang']['CLI_V_Started'] = 'التوقيع مدقق بدأت (%s).';
$CIDRAM['lang']['CLI_V_Terminal_LF'] = 'يجب إنهاء الملفات التوقيع مع مع LF.';

$CIDRAM['lang']['CLI_VL_CC'] = 'L%s: أحرف التحكم الكشف؛ هذا يمكن أن يشير إلى الفساد وينبغي التحقيق فيها.';
$CIDRAM['lang']['CLI_VL_Duplicated'] = 'L%s: التوقيع "%s" وتكرر ذلك (%s التهم)!';
$CIDRAM['lang']['CLI_VL_Expiry'] = 'L%s: العلامة انتهاء لا يحتوي على ISO 8601 التاريخ والوقت صحيح!';
$CIDRAM['lang']['CLI_VL_Invalid'] = 'L%s: "%s" هو ليس عنوانا صالحا IPv4 أو IPv6!';
$CIDRAM['lang']['CLI_VL_L120'] = 'L%s: طول الخط أكبر من 120 بايت; طول الخط ينبغي أن يقتصر على 120 بايت من أجل قراءة أفضل.';
$CIDRAM['lang']['CLI_VL_Mergeable'] = 'L%s و L%s متشابهون، و بالتالي، ويمكن الجمع بين.';
$CIDRAM['lang']['CLI_VL_Missing'] = 'L%s: مفقود [Function]؛ التوقيع على ما يبدو غير مكتملة.';
$CIDRAM['lang']['CLI_VL_Nontriggerable'] = 'L%s: "%s" لا يمكن تفعيلها! عنوان الأولي لا تتوافق مع ألنطاق! محاولة استبدالها مع "%s".';
$CIDRAM['lang']['CLI_VL_Nontriggerable_Range'] = 'L%s: "%s" لا يمكن تفعيلها! "%s" ليس نطاق صالح!';
$CIDRAM['lang']['CLI_VL_Subordinate'] = 'L%s: "%s" هو المرؤوس إلى التوقيع "%s".';
$CIDRAM['lang']['CLI_VL_Superset'] = 'L%s: "%s" هو مجموعة شاملة من التوقيع "%s".';
$CIDRAM['lang']['CLI_VL_Syntax'] = 'L%s: ليس دقيقا من حيث التركيب.';
$CIDRAM['lang']['CLI_VL_Tabs'] = 'L%s: علامات التبويب الكشف؛ ويفضل المساحات لأفضل قراءة.';
$CIDRAM['lang']['CLI_VL_Tags'] = 'L%s: العلامة القسم أكبر من 20 بايت؛ علامات القسم يجب أن تكون واضحة وموجزة.';
$CIDRAM['lang']['CLI_VL_Unrecognised'] = 'L%s: غير معترف بها [Function]؛ التوقيع ربما كسر.';
$CIDRAM['lang']['CLI_VL_Whitespace'] = 'L%s: زائدة بيضاء الكشف على هذا الخط.';
$CIDRAM['lang']['CLI_VL_YAML'] = 'L%s: البيانات YAML مثل الكشف عن، ولكن لا يمكن معالجته.';
