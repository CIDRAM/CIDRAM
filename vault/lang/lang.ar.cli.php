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
 * This file: Arabic language data for CLI (last modified: 2017.08.14).
 */

/** Prevents execution from outside of CIDRAM. */
if (!defined('CIDRAM')) {
    die('[CIDRAM] This should not be accessed directly.');
}

$CIDRAM['lang']['CLI_H'] = "
 CIDRAM CLI-mode help.

 Usage:
 /path/to/php/php.exe /path/to/cidram/loader.php -Flag (Input)

 Flags: -h  Display this help information.
        -c  Check if an IP address is blocked by the CIDRAM signature files.
        -g  Generate CIDRs from an IP address.

 Input: Can be any valid IPv4 or IPv6 IP address.

 Examples:
        -c  192.168.0.0/16
        -c  127.0.0.1/32
        -c  2001:db8::/32
        -c  2002::1/128

";

$CIDRAM['lang']['CLI_Bad_IP'] = ' عنوان IP، "{IP}"، ليس عنوانا صالحا IPv4 أو IPv6!';
$CIDRAM['lang']['CLI_IP_Blocked'] = ' هذا العنوان , "{IP}", فد تم حظره بواسطه CIDRAM';
$CIDRAM['lang']['CLI_IP_Not_Blocked'] = ' هذا العنوان , "{IP}", غير محظور بواسطة CIDRAM';

$CIDRAM['lang']['CLI_F_Finished'] = 'مصحح التوقيع قد انتهي مع %s تغييرات تمت علي %s عمليات (%s)';
$CIDRAM['lang']['CLI_F_Started'] = 'لقد بدء مصحح التوقيع';
$CIDRAM['lang']['CLI_VF_Empty'] = 'ملف التوقيع فارغ أو غير موجود.';
$CIDRAM['lang']['CLI_VF_Level_0'] = 'ملاحظة';
$CIDRAM['lang']['CLI_VF_Level_1'] = 'تحذير';
$CIDRAM['lang']['CLI_VF_Level_2'] = 'خطأ';
$CIDRAM['lang']['CLI_VF_Level_3'] = 'خطأ في النظام';

$CIDRAM['lang']['CLI_V_CRLF'] = 'تم العثور علي CR/CRLF في ملف التوقيعات , ان لك تصريح ولن يتسبب في الأخطاء ولكن يفضل ان يكون LF';
$CIDRAM['lang']['CLI_V_Finished'] = 'لقد انتهي التأكد من صحة التوقيع (%s). اذا لم يظهر لك اي تحذيرات او أخطا * قمن الممكن * ان يكون التوقيع صحيحا :-)';
$CIDRAM['lang']['CLI_V_LineByLine'] = 'لقد بدأ التأكد من صحه سطر تلو سطر';
$CIDRAM['lang']['CLI_V_Started'] = 'لقد بدأت أداة التأكد من صحة التوقيع (%s).';
$CIDRAM['lang']['CLI_V_Terminal_LF'] = 'ملفات التوقيعات يجب ان تنتهي ب LF .';

$CIDRAM['lang']['CLI_VL_CC'] = 'L%s: تم اكتشاف أحرف التحكم. وهذا يمكن أن يشير إلى الفساد وينبغي التحقيق فيه.';
$CIDRAM['lang']['CLI_VL_Duplicated'] = 'L%s: "%s" توقيعات مكرره ( %s مرات ) !';
$CIDRAM['lang']['CLI_VL_Expiry'] = 'L%s: وسم الانتهاء لا يحتوي علي ترميز ISO 8601 تاريخ / وقت صالح';
$CIDRAM['lang']['CLI_VL_Invalid'] = 'L%s: "%s" ليس عنوان IPv4 أو IPv6 صالح !';
$CIDRAM['lang']['CLI_VL_L120'] = 'L%s: طول السطر اطول من 120 bytes ; طول السطر يجب الا يتجاوز 120 bytes حتي يكون صالح للقراءة.';
$CIDRAM['lang']['CLI_VL_Mergeable'] = 'L%s و L%s متطابقين ويمكن دمجهم معا';
$CIDRAM['lang']['CLI_VL_Missing'] = 'L%s: ينقصه [Function]; يبدو ان ملف التوقيع غير مكتمل.';
$CIDRAM['lang']['CLI_VL_Nontriggerable'] = 'L%s: "%s" غير قابله للاستدعاء ! أساسها لا يوافق بدايه مداها ! حاول استبدالها ب "%s".';
$CIDRAM['lang']['CLI_VL_Nontriggerable_Range'] = 'L%s: "%s" غير قابله  للاستدعاء "%s" مدي غير صالح !';
$CIDRAM['lang']['CLI_VL_Subordinate'] = 'L%s: "%s" is subordinate to the already existing "%s" signature.';
$CIDRAM['lang']['CLI_VL_Superset'] = 'L%s: "%s" is a superset to the already existing "%s" signature.';
$CIDRAM['lang']['CLI_VL_Syntax'] = 'L%s: ليس دقيقا من حيث التركيب.';
$CIDRAM['lang']['CLI_VL_Tabs'] = 'L%s: تم العقور علي تبويبات ; ان المسافات مفضله عن التبويبات لتحسين القدرة علي القراءة.';
$CIDRAM['lang']['CLI_VL_Tags'] = 'L%s: وسم Section أكبر من 20 bytes ; وسم Section يجب ان يكون واضح و مختصرا';
$CIDRAM['lang']['CLI_VL_Unrecognised'] = 'L%s: [Function]; غير متعرف عليها ; من الممكن ان يكون التوقيع قد تم كسره';
$CIDRAM['lang']['CLI_VL_Whitespace'] = 'L%s: يوجد مسافات فارغة زائدة في هذا السطر.';
$CIDRAM['lang']['CLI_VL_YAML'] = 'L%s: تم العثور علي بيانات YAML-like ولكن غير قادر علي معالجتها';
