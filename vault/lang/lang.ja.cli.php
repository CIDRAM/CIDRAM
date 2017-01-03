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
 * This file: Japanese language data for CLI (last modified: 2017.01.03).
 */

/** Prevents execution from outside of CIDRAM. */
if (!defined('CIDRAM')) {
    die('[CIDRAM] This should not be accessed directly.');
}

$CIDRAM['lang']['CLI_H'] = "
 CIDRAM CLIモードのヘルプ。

 使用法：
 /PHPへのパス/php.exe /CIDRAMへのパス/loader.php -フラグ：（入力）

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

$CIDRAM['lang']['CLI_Bad_IP'] = ' 「{IP}」は、有効なIPv4/IPv6アドレスではありません！';
$CIDRAM['lang']['CLI_IP_Blocked'] = ' 「{IP}」には、署名ファイルによってブロックされています。';
$CIDRAM['lang']['CLI_IP_Not_Blocked'] = ' 「{IP}」には、署名ファイルによってブロックされていません。';

$CIDRAM['lang']['CLI_F_Finished'] = '署名フィクサーが完了しました；変更：%s；オペレーション：%s（%s）。';
$CIDRAM['lang']['CLI_F_Started'] = '署名フィクサーが始まりました（%s）。';
$CIDRAM['lang']['CLI_VF_Empty'] = '存在指定された署名ファイルが空であるか存在しません。';
$CIDRAM['lang']['CLI_VF_Level_0'] = '通知';
$CIDRAM['lang']['CLI_VF_Level_1'] = '警告';
$CIDRAM['lang']['CLI_VF_Level_2'] = 'エラー';
$CIDRAM['lang']['CLI_VF_Level_3'] = '致命的なエラー';

$CIDRAM['lang']['CLI_V_CRLF'] = '署名ファイルで、「CR/CRLF」は検出されました；これは大丈夫です、しかし、「LF」は好ましいです。';
$CIDRAM['lang']['CLI_V_Finished'] = '署名バリデータが完了しました（%s）。何の警告やエラーが現れなかった場合は、あなたの署名ファイルは、おそらく大丈夫です。';
$CIDRAM['lang']['CLI_V_LineByLine'] = 'ライン・バイ・ラインの検証が始まりました。';
$CIDRAM['lang']['CLI_V_Started'] = '署名バリデータが始まりました（%s）。';
$CIDRAM['lang']['CLI_V_Terminal_LF'] = '「LF」によって、署名ファイルが終了する必要があります。';

$CIDRAM['lang']['CLI_VL_CC'] = 'L%s： 制御文字が検出されました；破損している可能性があり；調査が必要です。';
$CIDRAM['lang']['CLI_VL_Duplicated'] = 'L%s： 署名「%s」は重複しています（%s回）！';
$CIDRAM['lang']['CLI_VL_Expiry'] = 'L%s： 有効期限タグは、有効な日時（ＩＳＯ８６０１）が含まれていません！';
$CIDRAM['lang']['CLI_VL_Invalid'] = 'L%s： 「%s」は、有効なIPv4/IPv6アドレスではありません！';
$CIDRAM['lang']['CLI_VL_L120'] = 'L%s： 行の長さが１２０バイト以上です；最適な読みやすさのために、行の長さは１２０バイトに制限されるべきです。';
$CIDRAM['lang']['CLI_VL_Mergeable'] = 'L%sとL%sは同じです、そのため、はマージすることができます。';
$CIDRAM['lang']['CLI_VL_Missing'] = 'L%s： 「機能」は存在しません；署名は不完全であることが表示されます。';
$CIDRAM['lang']['CLI_VL_Nontriggerable'] = 'L%s： 「%s」は、活性化することはできません！ベースと範囲の始まりは一致していません！「%s」とそれを交換してみてください。';
$CIDRAM['lang']['CLI_VL_Nontriggerable_Range'] = 'L%s： 「%s」は、活性化することはできません！「%s」は有効な範囲ではありません！';
$CIDRAM['lang']['CLI_VL_Subordinate'] = 'L%s： 「%s」は「%s」（これはすでに存在します）に従属します。';
$CIDRAM['lang']['CLI_VL_Superset'] = 'L%s： 「%s」は「%s」（これはすでに存在します）にスーパーセットです。';
$CIDRAM['lang']['CLI_VL_Syntax'] = 'L%s： 文法的に正確ではありません。';
$CIDRAM['lang']['CLI_VL_Tabs'] = 'L%s： タブが検出されました；最適な読みやすさのために、スペースが好ましいです。';
$CIDRAM['lang']['CLI_VL_Tags'] = 'L%s： セクションタグが２０バイト以上です；セクションタグは、明確かつ簡潔でなければなりません。';
$CIDRAM['lang']['CLI_VL_Unrecognised'] = 'L%s： 「機能」が認識されません；署名が壊れている可能性があります。';
$CIDRAM['lang']['CLI_VL_Whitespace'] = 'L%s： 末尾の空白が、この回線で検出されました。';
$CIDRAM['lang']['CLI_VL_YAML'] = 'L%s： YAMLのようなデータが検出されたが、それを処理できませんでした。';
