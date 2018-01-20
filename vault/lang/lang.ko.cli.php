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
 * This file: Korean language data (last modified: 2018.01.20).
 */

/** Prevents execution from outside of CIDRAM. */
if (!defined('CIDRAM')) {
    die('[CIDRAM] This should not be accessed directly.');
}

$CIDRAM['lang']['CLI_H'] = "
 CIDRAM CLI 모드 도와주세요.

 사용법:
 php.exe /cidram/loader.php -플래그 (입력)

 플래그:
 -h 이 도움말 정보를 표시합니다.
 -c IP 주소를 확인하십시오 여부는 CIDRAM에 의해 차단되어 있습니다.
 -g IP 주소에서 CIDR을 생성합니다.
 -v 서명 파일의 유효성을 검사한다.
 -f 서명 파일을 고치다.

 예:
 php.exe /cidram/loader.php -c 192.168.0.0
 php.exe /cidram/loader.php -c 2001:db8::
 php.exe /cidram/loader.php -g 1.2.3.4
 php.exe /cidram/loader.php -g ::1
 php.exe /cidram/loader.php -f signatures.dat
 php.exe /cidram/loader.php -v signatures.dat

";

$CIDRAM['lang']['CLI_Bad_IP'] = ' "{IP}"는 유효한 IPv4/IPv6 주소가 없습니다!';
$CIDRAM['lang']['CLI_IP_Blocked'] = ' "{IP}"에 서명 파일에 의해 차단되어 있습니다.';
$CIDRAM['lang']['CLI_IP_Not_Blocked'] = ' "{IP}"에 서명 파일에 의해 차단되지 않습니다.';

$CIDRAM['lang']['CLI_F_Finished'] = '서명 해결사가 완료되었습니다; 변경 : %s; 작업 : %s (%s).';
$CIDRAM['lang']['CLI_F_Started'] = '서명 해결사가 시작되었습니다 (%s).';
$CIDRAM['lang']['CLI_VF_Empty'] = '존재 지정된 서명 파일이 비어 있거나 존재하지 않습니다.';
$CIDRAM['lang']['CLI_VF_Level_0'] = '통지';
$CIDRAM['lang']['CLI_VF_Level_1'] = '경고';
$CIDRAM['lang']['CLI_VF_Level_2'] = '오류';
$CIDRAM['lang']['CLI_VF_Level_3'] = '치명적인 오류';

$CIDRAM['lang']['CLI_V_CRLF'] = '서명 파일에서 "CR/CRLF"는 감지되었습니다; 이것은 괜찮아요, 하지만 "LF" 바람직합니다.';
$CIDRAM['lang']['CLI_V_Finished'] = '서명 검사기가 완료되었습니다(%s). 아무런 경고 나 오류가 나타나지 않으면, 당신의 서명 파일은 아마 괜찮습니다.';
$CIDRAM['lang']['CLI_V_LineByLine'] = '라인 바이 라인의 검증이 시작되었습니다.';
$CIDRAM['lang']['CLI_V_Started'] = '서명 검사기가 시작되었습니다 (%s).';
$CIDRAM['lang']['CLI_V_Terminal_LF'] = '"LF"에 의해 서명 파일을 종료해야합니다.';

$CIDRAM['lang']['CLI_VL_CC'] = 'L%s : 제어 문자가 발견되었습니다; 손상된 수 있습니다; 조사가 필요합니다.';
$CIDRAM['lang']['CLI_VL_Duplicated'] = 'L%s : 서명 "%s"는 중복입니다 (%s 시간)!';
$CIDRAM['lang']['CLI_VL_Expiry'] = 'L%s : 만기 태그는 유효한 날짜 (ISO8601)가 포함되어 있지 않습니다!';
$CIDRAM['lang']['CLI_VL_Invalid'] = 'L%s : "%s"는 유효한 IPv4/IPv6 주소가 없습니다!';
$CIDRAM['lang']['CLI_VL_L120'] = 'L%s : 행의 길이가 120 바이트 이상입니다; 최적의 가독성을 위해 행의 길이는 120 바이트로 제한되어야합니다.';
$CIDRAM['lang']['CLI_VL_Mergeable'] = 'L%s와 L%s는 동일합니다, 따라서 병합 할 수 있습니다.';
$CIDRAM['lang']['CLI_VL_Missing'] = 'L%s : "기능"은 존재하지 않습니다; 서명은 완전하지가 표시됩니다.';
$CIDRAM['lang']['CLI_VL_Nontriggerable'] = 'L%s : "%s"는 활성화 될 수 없습니다! 베이스와 범위의 시작은 일치하지 않습니다! "%s"와 그것을 교체하려고합니다.';
$CIDRAM['lang']['CLI_VL_Nontriggerable_Range'] = 'L%s : "%s"는 활성화 될 수 없습니다! "%s"는 유효한 범위에 없습니다!';
$CIDRAM['lang']['CLI_VL_Origin'] = 'L%s : 원산지 태그에 올바른 ISO 3166-1 Alpha-2 코드가 없습니다!';
$CIDRAM['lang']['CLI_VL_Subordinate'] = 'L%s : "%s"는 "%s" (이것은 이미 존재합니다)에 종속합니다.';
$CIDRAM['lang']['CLI_VL_Superset'] = 'L%s : "%s"는 "%s" (이것은 이미 존재합니다) 슈퍼 세트입니다.';
$CIDRAM['lang']['CLI_VL_Syntax'] = 'L%s : 문법적으로 정확하지는 않습니다.';
$CIDRAM['lang']['CLI_VL_Tabs'] = 'L%s : 탭이 감지되었습니다; 최적의 가독성을 위해 공간을 선호합니다.';
$CIDRAM['lang']['CLI_VL_Tags'] = 'L%s : 섹션 태그가 20 바이트 이상입니다; 섹션 태그는 명확하고 간결해야합니다.';
$CIDRAM['lang']['CLI_VL_Unrecognised'] = 'L%s : "기능"이 인식되지 않습니다; 서명이 손상되었을 가능성이 있습니다.';
$CIDRAM['lang']['CLI_VL_Whitespace'] = 'L%s : 후행 공백이이 회선에서 발견되었습니다.';
$CIDRAM['lang']['CLI_VL_YAML'] = 'L%s : YAML과 같은 데이터가 검출되었지만, 그것을 처리 할 수 없습니다.';
