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
 * This file: Russian language data for CLI (last modified: 2016.07.26).
 *
 * @todo (This is incomplete).
 */

/** Prevents execution from outside of CIDRAM. */
if (!defined('CIDRAM')) {
    die('[CIDRAM] This should not be accessed directly.');
}

$CIDRAM['lang']['CLI_H'] = "
 CIDRAM CLI-модус справки.

 Применение:
 /путь/к/php/php.exe /путь/к/cidram/loader.php -Флаг (Ввод)

 Флаги: -h  Покажи помощь информации.
        -c  Проверьте если IP-адрес заблокирован из CIDRAM файлы сигнатур.
        -g  Генерировать CIDR из IP-адреса.

 Ввод: Может быть любым допустимым IPv4 или IPv6 IP-адрес.

 Примеры:
        -c  192.168.0.0/16
        -c  127.0.0.1/32
        -c  2001:db8::/32
        -c  2002::1/128

";

$CIDRAM['lang']['CLI_Bad_IP'] = ' Указанный IP-адрес, "{IP}", не является допустимым IP-адрес!';
$CIDRAM['lang']['CLI_IP_Blocked'] = ' Указанный IP-адрес, "{IP}", заблокирован одним или несколькими из CIDRAM файлы сигнатур.';
$CIDRAM['lang']['CLI_IP_Not_Blocked'] = ' Указанный IP-адрес, "{IP}", *НЕ* заблокирован одним или несколькими из CIDRAM файлы сигнатур.';

$CIDRAM['lang']['CLI_F_Finished'] = 'Сигнатур закрепитель закончил, с %s изменения, сделанные в течение %s операций (%s).';
$CIDRAM['lang']['CLI_F_Started'] = 'Сигнатур закрепитель начал (%s).';
$CIDRAM['lang']['CLI_VF_Empty'] = 'Указанный файл сигнатур пуста или не существует.';
$CIDRAM['lang']['CLI_VF_Level_0'] = 'Уведомление';
$CIDRAM['lang']['CLI_VF_Level_1'] = 'Предупреждение';
$CIDRAM['lang']['CLI_VF_Level_2'] = 'Ошибка';
$CIDRAM['lang']['CLI_VF_Level_3'] = 'Фатальная Ошибка';

$CIDRAM['lang']['CLI_V_CRLF'] = 'Detected CR/CRLF in signature file; These are permissible and won\'t cause problems, but LF is preferable.';
$CIDRAM['lang']['CLI_V_Finished'] = 'Signature validator has finished (%s). If no warnings or errors have appeared, your signature file is *probably* okay. :-)';
$CIDRAM['lang']['CLI_V_LineByLine'] = 'Line-by-line validation has started.';
$CIDRAM['lang']['CLI_V_Started'] = 'Signature validator has started (%s).';
$CIDRAM['lang']['CLI_V_Terminal_LF'] = 'Signature files should terminate with an LF linebreak.';

$CIDRAM['lang']['CLI_VL_CC'] = 'Л%s: Control characters detected; This could indicate corruption and should be investigated.';
$CIDRAM['lang']['CLI_VL_Duplicated'] = 'Л%s: Signature "%s" is duplicated (%s counts)!';
$CIDRAM['lang']['CLI_VL_Expiry'] = 'Л%s: Expiry tag doesn\'t contain a valid ISO 8601 date/time!';
$CIDRAM['lang']['CLI_VL_Invalid'] = 'Л%s: "%s" is *NOT* a valid IPv4 or IPv6 address!';
$CIDRAM['lang']['CLI_VL_L120'] = 'Л%s: Line length is greater than 120 bytes; Line length should be limited to 120 bytes for optimal readability.';
$CIDRAM['lang']['CLI_VL_Mergeable'] = 'Л%s and Л%s are identical, and thus, mergeable.';
$CIDRAM['lang']['CLI_VL_Missing'] = 'Л%s: Missing [Function]; Signature appears to be incomplete.';
$CIDRAM['lang']['CLI_VL_Nontriggerable'] = 'Л%s: "%s" is non-triggerable! Its base doesn\'t match the beginning of its range! Try replacing it with "%s".';
$CIDRAM['lang']['CLI_VL_Nontriggerable_Range'] = 'Л%s: "%s" is non-triggerable! "%s" is not a valid range!';
$CIDRAM['lang']['CLI_VL_Subordinate'] = 'Л%s: "%s" is subordinate to the already existing "%s" signature.';
$CIDRAM['lang']['CLI_VL_Superset'] = 'Л%s: "%s" is a superset to the already existing "%s" signature.';
$CIDRAM['lang']['CLI_VL_Syntax'] = 'Л%s: Не синтаксически точно.';
$CIDRAM['lang']['CLI_VL_Tabs'] = 'Л%s: Tabs detected; Spaces are preferred over tabs for optimal readability.';
$CIDRAM['lang']['CLI_VL_Tags'] = 'Л%s: Section tag is greater than 20 bytes; Section tags should be clear and concise.';
$CIDRAM['lang']['CLI_VL_Unrecognised'] = 'Л%s: Непризнанная [Function]; Подпись может быть нарушена.';
$CIDRAM['lang']['CLI_VL_Whitespace'] = 'Л%s: Excess trailing whitespace detected on this line.';
$CIDRAM['lang']['CLI_VL_YAML'] = 'Л%s: YAML-подобные данные обнаруженные, но не может обработать его.';
