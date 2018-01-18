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
 * This file: Chinese (traditional) language data for CLI (last modified: 2018.01.18).
 */

/** Prevents execution from outside of CIDRAM. */
if (!defined('CIDRAM')) {
    die('[CIDRAM] This should not be accessed directly.');
}

$CIDRAM['lang']['CLI_H'] = "
 CIDRAM CLI模式輔助。

 用法：
 /路徑到PHP/php.exe /路徑到CIDRAM/loader.php -鍵 （輸入）

 鍵：
    -h  顯示此幫助信息。
    -c  檢查如果一個IP地址被阻止由CIDRAM簽名文件。
    -g  生成CIDR從一個IP地址。

 輸入：可以是任何有效的地址（IPv4/IPv6）。

 例子：
        -c  192.168.0.0/16
        -c  127.0.0.1/32
        -c  2001:db8::/32
        -c  2002::1/128

";

$CIDRAM['lang']['CLI_Bad_IP'] = ' 指定的IP地址，​『{IP}』，​不是有效地址（IPv4/IPv6）！';
$CIDRAM['lang']['CLI_IP_Blocked'] = ' 指定的IP地址，​『{IP}』，​是阻塞由一個或多簽名文件。';
$CIDRAM['lang']['CLI_IP_Not_Blocked'] = ' 指定的IP地址，​『{IP}』，​不是阻塞由任何簽名文件。';

$CIDRAM['lang']['CLI_F_Finished'] = '簽名定影已完成。​%s變化取得通過%s操作（%s）。';
$CIDRAM['lang']['CLI_F_Started'] = '簽名定影已開始（%s）。';
$CIDRAM['lang']['CLI_VF_Empty'] = '指定的簽名文件為空或不存在的。';
$CIDRAM['lang']['CLI_VF_Level_0'] = '通知';
$CIDRAM['lang']['CLI_VF_Level_1'] = '警告';
$CIDRAM['lang']['CLI_VF_Level_2'] = '錯誤';
$CIDRAM['lang']['CLI_VF_Level_3'] = '致命錯誤';

$CIDRAM['lang']['CLI_V_CRLF'] = 'CR/CRLF檢測在簽名文件；這些都是允許的和不會產生問題，​但LF是最好。';
$CIDRAM['lang']['CLI_V_Finished'] = '簽名驗證已完成（%s）。​如果沒有警告或錯誤已出現，​您的簽名文件是最有可能的好。';
$CIDRAM['lang']['CLI_V_LineByLine'] = '線由行驗證已開始。';
$CIDRAM['lang']['CLI_V_Started'] = '簽名驗證已開始（%s）。';
$CIDRAM['lang']['CLI_V_Terminal_LF'] = '簽名文件應終止通過一個LF換行符。';

$CIDRAM['lang']['CLI_VL_CC'] = '線%s：控製字符檢測；這可能表明腐敗和應進行調查。';
$CIDRAM['lang']['CLI_VL_Duplicated'] = '線%s：簽名『%s』是一個賦值（%s計數）！';
$CIDRAM['lang']['CLI_VL_Expiry'] = '線%s：到期標籤不包含有效的ISO8601日期/時間！';
$CIDRAM['lang']['CLI_VL_Invalid'] = '線%s：『%s』不是一個有效的IPv4或IPv6地址！';
$CIDRAM['lang']['CLI_VL_L120'] = '線%s：線路長度大於120字節；線路長度應限制在120字節為最佳可讀性。';
$CIDRAM['lang']['CLI_VL_Mergeable'] = '線%s和線%s是相同的，​和因此，​可合併。';
$CIDRAM['lang']['CLI_VL_Missing'] = '線%s：【Function】失踪；簽名似乎是不完整。';
$CIDRAM['lang']['CLI_VL_Nontriggerable'] = '線%s：『%s』不可觸發！​其基不匹配開始其範圍內！​試圖取代它以『%s』。';
$CIDRAM['lang']['CLI_VL_Nontriggerable_Range'] = '線%s：『%s』不可觸發！​『%s』不是一個有效的範圍內！';
$CIDRAM['lang']['CLI_VL_Origin'] = '線%s：原產標籤不包含有效的ISO 3166-1 二位字母代碼！';
$CIDRAM['lang']['CLI_VL_Subordinate'] = '線%s：『%s』是從屬於現有簽名『%s』。';
$CIDRAM['lang']['CLI_VL_Superset'] = '線%s：『%s』是一個超集現有簽名『%s』。';
$CIDRAM['lang']['CLI_VL_Syntax'] = '線%s：語法上不准確的。';
$CIDRAM['lang']['CLI_VL_Tabs'] = '線%s：製表是檢測；空間是首選到製表為最佳可讀性。';
$CIDRAM['lang']['CLI_VL_Tags'] = '線%s：章節標籤大於20字節；章節標籤應該清晰和簡明。';
$CIDRAM['lang']['CLI_VL_Unrecognised'] = '線%s：【Function】未知；簽名可能被打破。';
$CIDRAM['lang']['CLI_VL_Whitespace'] = '線%s：多餘的尾隨空白在這個線是檢測。';
$CIDRAM['lang']['CLI_VL_YAML'] = '線%s：YAML樣數據是檢測，​但無法處理它。';
