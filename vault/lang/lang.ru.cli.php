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
 * This file: Russian language data for CLI (last modified: 2017.08.10).
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

$CIDRAM['lang']['CLI_Bad_IP'] = ' Указанный IP-адрес, «{IP}», не является допустимым IP-адрес!';
$CIDRAM['lang']['CLI_IP_Blocked'] = ' Указанный IP-адрес, «{IP}», заблокирован одним или несколькими из CIDRAM файлы сигнатур.';
$CIDRAM['lang']['CLI_IP_Not_Blocked'] = ' Указанный IP-адрес, «{IP}», *НЕ* заблокирован одним или несколькими из CIDRAM файлы сигнатур.';

$CIDRAM['lang']['CLI_F_Finished'] = 'Сигнатур закрепитель закончил, с %s изменения, сделанные в течение %s операций (%s).';
$CIDRAM['lang']['CLI_F_Started'] = 'Сигнатур закрепитель начал (%s).';
$CIDRAM['lang']['CLI_VF_Empty'] = 'Указанный файл сигнатур пуста или не существует.';
$CIDRAM['lang']['CLI_VF_Level_0'] = 'Уведомление';
$CIDRAM['lang']['CLI_VF_Level_1'] = 'Предупреждение';
$CIDRAM['lang']['CLI_VF_Level_2'] = 'Ошибка';
$CIDRAM['lang']['CLI_VF_Level_3'] = 'Фатальная Ошибка';

$CIDRAM['lang']['CLI_V_CRLF'] = 'Обнаружен CR/CRLF(ВК/ВКПТ) в файле подписи; Это допустимо и не вызовет проблем, но LF(ПТ) предпочтительнее.';
$CIDRAM['lang']['CLI_V_Finished'] = 'Проверка подписи завершена (%s). Если не предупреждений и не сообщений появилось, ваш файл подписи вероятно хорошо. :-)';
$CIDRAM['lang']['CLI_V_LineByLine'] = 'Линия за линией проверки начала.';
$CIDRAM['lang']['CLI_V_Started'] = 'Проверка подписи началась (%s).';
$CIDRAM['lang']['CLI_V_Terminal_LF'] = 'Подпись файлы должны завершаться с LF(ПТ) разрыв строки.';

$CIDRAM['lang']['CLI_VL_CC'] = 'Л%s: Управляющие символы обнаружены; Это может свидетельствовать о коррупции и должны быть исследованы.';
$CIDRAM['lang']['CLI_VL_Duplicated'] = 'Л%s: Подпись «%s» дублируется (%s счетчики)!';
$CIDRAM['lang']['CLI_VL_Expiry'] = 'Л%s: Истекло тег не содержит действительный ISO 8601 дата/время!';
$CIDRAM['lang']['CLI_VL_Invalid'] = 'Л%s: «%s» НЕ является допустимым IPv4 или IPv6 IP-адрес!';
$CIDRAM['lang']['CLI_VL_L120'] = 'Л%s: Длина линии превышает 120 байт; Длина линии должна быть ограничена до 120 байт для оптимальной читаемости.';
$CIDRAM['lang']['CLI_VL_Mergeable'] = 'Л%s и Л%s идентичны, и поэтому, объединяемы.';
$CIDRAM['lang']['CLI_VL_Missing'] = 'Л%s: Отсутствует [Функция]; Подпись, как представляется, будет неполным.';
$CIDRAM['lang']['CLI_VL_Nontriggerable'] = 'Л%s: «%s» не может быть активирован! Его база не соответствует началу своего диапазона! Попробуйте заменить его с «%s».';
$CIDRAM['lang']['CLI_VL_Nontriggerable_Range'] = 'Л%s: «%s» не может быть активирован! «%s» не допустимый диапазон!';
$CIDRAM['lang']['CLI_VL_Subordinate'] = 'Л%s: «%s» подчиняется уже существующей подписи «%s».';
$CIDRAM['lang']['CLI_VL_Superset'] = 'Л%s: «%s» является надстройкой к уже существующей подписи «%s».';
$CIDRAM['lang']['CLI_VL_Syntax'] = 'Л%s: Не синтаксически точно.';
$CIDRAM['lang']['CLI_VL_Tabs'] = 'Л%s: Вкладки обнаружены; Пробелы предпочтительнее Вкладки для оптимальной читаемости.';
$CIDRAM['lang']['CLI_VL_Tags'] = 'Л%s: Секция тег больше 20 байт; Секция теги должны быть четкими и краткими.';
$CIDRAM['lang']['CLI_VL_Unrecognised'] = 'Л%s: Непризнанная [Функция]; Подпись может быть нарушена.';
$CIDRAM['lang']['CLI_VL_Whitespace'] = 'Л%s: Избыточный заканчивая пробельные обнаружены на этой линии.';
$CIDRAM['lang']['CLI_VL_YAML'] = 'Л%s: YAML-подобные данные обнаруженные, но не может обработать его.';
