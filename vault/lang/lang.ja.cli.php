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
 * This file: Japanese language data for CLI (last modified: 2016.08.21).
 *
 * @todo (This is incomplete).
 */

/** Prevents execution from outside of CIDRAM. */
if (!defined('CIDRAM')) {
    die('[CIDRAM] This should not be accessed directly.');
}

$CIDRAM['lang']['CLI_H'] = "
 CIDRAM CLIモードのヘルプ。

 使用法：
 /PHPへのパス/php.exe /CIDRAMへのパス/loader.php -フラグ： （入力）

 フラグ：
    -h  このヘルプ情報を表示します。
    -c  IPアドレスを確認してください、かどうかはCIDRAMによってブロックされています。
    -g  IPアドレスからCIDRを生成します。

 入力： 任意の有効なアドレス（IPv4/IPv6）を指定できます。

 例：
        -c  192.168.0.0/16
        -c  127.0.0.1/32
        -c  2001:db8::/32
        -c  2002::1/128

";

$CIDRAM['lang']['CLI_Bad_IP'] = ' The specified IP address, "{IP}", is not a valid IPv4 or IPv6 IP address!';
$CIDRAM['lang']['CLI_IP_Blocked'] = ' The specified IP address, "{IP}", *IS* blocked by one or more of the CIDRAM signature files.';
$CIDRAM['lang']['CLI_IP_Not_Blocked'] = ' The specified IP address, "{IP}", is *NOT* blocked by any of the CIDRAM signature files.';

$CIDRAM['lang']['CLI_F_Finished'] = '署名フィクサーが完了しました； 変更：%s； オペレーション：%s （%s）。';
$CIDRAM['lang']['CLI_F_Started'] = '署名フィクサーが始まりました（%s）。';
$CIDRAM['lang']['CLI_VF_Empty'] = '存在指定された署名ファイルが空であるか存在しません。';
$CIDRAM['lang']['CLI_VF_Level_0'] = '通知';
$CIDRAM['lang']['CLI_VF_Level_1'] = '警告';
$CIDRAM['lang']['CLI_VF_Level_2'] = 'エラー';
$CIDRAM['lang']['CLI_VF_Level_3'] = '致命的なエラー';

$CIDRAM['lang']['CLI_V_CRLF'] = 'Detected CR/CRLF in signature file; These are permissible and won\'t cause problems, but LF is preferable.';
$CIDRAM['lang']['CLI_V_Finished'] = '署名バリデータが完了しました（%s）。何の警告やエラーが現れなかった場合は、あなたの署名ファイルは、おそらく大丈夫です。';
$CIDRAM['lang']['CLI_V_LineByLine'] = 'ライン・バイ・ラインの検証が始まりました。';
$CIDRAM['lang']['CLI_V_Started'] = '署名バリデータが始まりました（%s）。';
$CIDRAM['lang']['CLI_V_Terminal_LF'] = 'Signature files should terminate with an LF linebreak.';

$CIDRAM['lang']['CLI_VL_CC'] = 'L%s: Control characters detected; This could indicate corruption and should be investigated.';
$CIDRAM['lang']['CLI_VL_Duplicated'] = 'L%s: Signature "%s" is duplicated (%s counts)!';
$CIDRAM['lang']['CLI_VL_Expiry'] = 'L%s: Expiry tag doesn\'t contain a valid ISO 8601 date/time!';
$CIDRAM['lang']['CLI_VL_Invalid'] = 'L%s: "%s" is *NOT* a valid IPv4 or IPv6 address!';
$CIDRAM['lang']['CLI_VL_L120'] = 'L%s: Line length is greater than 120 bytes; Line length should be limited to 120 bytes for optimal readability.';
$CIDRAM['lang']['CLI_VL_Mergeable'] = 'L%s and L%s are identical, and thus, mergeable.';
$CIDRAM['lang']['CLI_VL_Missing'] = 'L%s： 「Function」は存在しません； 署名は不完全であることが表示されます。';
$CIDRAM['lang']['CLI_VL_Nontriggerable'] = 'L%s: "%s" is non-triggerable! Its base doesn\'t match the beginning of its range! Try replacing it with "%s".';
$CIDRAM['lang']['CLI_VL_Nontriggerable_Range'] = 'L%s: "%s" is non-triggerable! "%s" is not a valid range!';
$CIDRAM['lang']['CLI_VL_Subordinate'] = 'L%s: "%s" is subordinate to the already existing "%s" signature.';
$CIDRAM['lang']['CLI_VL_Superset'] = 'L%s: "%s" is a superset to the already existing "%s" signature.';
$CIDRAM['lang']['CLI_VL_Syntax'] = 'L%s： 文法的に正確ではありません。';
$CIDRAM['lang']['CLI_VL_Tabs'] = 'L%s: Tabs detected; Spaces are preferred over tabs for optimal readability.';
$CIDRAM['lang']['CLI_VL_Tags'] = 'L%s: Section tag is greater than 20 bytes; Section tags should be clear and concise.';
$CIDRAM['lang']['CLI_VL_Unrecognised'] = 'L%s: 「Function」が認識されません； 署名が壊れている可能性があります。';
$CIDRAM['lang']['CLI_VL_Whitespace'] = 'L%s: Excess trailing whitespace detected on this line.';
$CIDRAM['lang']['CLI_VL_YAML'] = 'L%s： YAMLのようなデータが検出されたが、それを処理できませんでした。';
