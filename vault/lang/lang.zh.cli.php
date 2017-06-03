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
 * This file: Chinese (simplified) language data for CLI (last modified: 2016.07.26).
 */

/** Prevents execution from outside of CIDRAM. */
if (!defined('CIDRAM')) {
    die('[CIDRAM] This should not be accessed directly.');
}

$CIDRAM['lang']['CLI_H'] = "
 CIDRAM CLI模式辅助。

 用法：
 /路径到PHP/php.exe /路径到CIDRAM/loader.php -键 （输入）

 键：
    -h  显示此帮助信息。
    -c  检查如果一个IP地址被阻止由CIDRAM签名文件。
    -g  生成CIDR从一个IP地址。

 输入： 可以是任何有效的地址（IPv4/IPv6）。

 例子：
        -c  192.168.0.0/16
        -c  127.0.0.1/32
        -c  2001:db8::/32
        -c  2002::1/128

";

$CIDRAM['lang']['CLI_Bad_IP'] = ' 指定的IP地址，“{IP}”，不是有效地址（IPv4/IPv6）！';
$CIDRAM['lang']['CLI_IP_Blocked'] = ' 指定的IP地址，“{IP}”，是阻塞由一个或多签名文件。';
$CIDRAM['lang']['CLI_IP_Not_Blocked'] = ' 指定的IP地址，“{IP}”，不是阻塞由任何签名文件。';

$CIDRAM['lang']['CLI_F_Finished'] = '签名定影已完成。%s变化取得通过%s操作（%s）。';
$CIDRAM['lang']['CLI_F_Started'] = '签名定影已开始（%s）。';
$CIDRAM['lang']['CLI_VF_Empty'] = '指定的签名文件为空或不存在的。';
$CIDRAM['lang']['CLI_VF_Level_0'] = '通知';
$CIDRAM['lang']['CLI_VF_Level_1'] = '警告';
$CIDRAM['lang']['CLI_VF_Level_2'] = '错误';
$CIDRAM['lang']['CLI_VF_Level_3'] = '致命错误';

$CIDRAM['lang']['CLI_V_CRLF'] = 'CR/CRLF检测在签名文件；这些都是允许的和不会产生问题，但LF是最好。';
$CIDRAM['lang']['CLI_V_Finished'] = '签名验证已完成（%s）。如果没有警告或错误已出现，您的签名文件是最有可能的好。';
$CIDRAM['lang']['CLI_V_LineByLine'] = '线由行验证已开始。';
$CIDRAM['lang']['CLI_V_Started'] = '签名验证已开始（%s）。';
$CIDRAM['lang']['CLI_V_Terminal_LF'] = '签名文件应终止通过一个LF换行符。';

$CIDRAM['lang']['CLI_VL_CC'] = '线%s：控制字符检测；这可能表明腐败和应进行调查。';
$CIDRAM['lang']['CLI_VL_Duplicated'] = '线%s：签名“%s”是一个复制（%s计数）！';
$CIDRAM['lang']['CLI_VL_Expiry'] = '线%s：到期标签不包含有效的ISO8601日期/时间！';
$CIDRAM['lang']['CLI_VL_Invalid'] = '线%s：“%s”不是一个有效的IPv4或IPv6地址！';
$CIDRAM['lang']['CLI_VL_L120'] = '线%s：线路长度大于120字节；线路长度应限制在120字节为最佳可读性。';
$CIDRAM['lang']['CLI_VL_Mergeable'] = '线%s和线%s是相同的，和因此，可合并。';
$CIDRAM['lang']['CLI_VL_Missing'] = '线%s：【Function】失踪；签名似乎是不完整。';
$CIDRAM['lang']['CLI_VL_Nontriggerable'] = '线%s：“%s”不可触发！其基不匹配开始其范围内！试图取代它以“%s”。';
$CIDRAM['lang']['CLI_VL_Nontriggerable_Range'] = '线%s：“%s”不可触发！“%s”不是一个有效的范围内！';
$CIDRAM['lang']['CLI_VL_Subordinate'] = '线%s：“%s”是从属于现有签名“%s”。';
$CIDRAM['lang']['CLI_VL_Superset'] = '线%s：“%s”是一个超集现有签名“%s”。';
$CIDRAM['lang']['CLI_VL_Syntax'] = '线%s：语法上不准确的。';
$CIDRAM['lang']['CLI_VL_Tabs'] = '线%s：制表是检测；空间是首选到制表为最佳可读性。';
$CIDRAM['lang']['CLI_VL_Tags'] = '线%s：章节标签大于20字节；章节标签应该清晰和简明。';
$CIDRAM['lang']['CLI_VL_Unrecognised'] = '线%s：【Function】未知；签名可能被打破。';
$CIDRAM['lang']['CLI_VL_Whitespace'] = '线%s：多余的尾随空白在这个线是检测。';
$CIDRAM['lang']['CLI_VL_YAML'] = '线%s：YAML样数据是检测，但无法处理它。';
