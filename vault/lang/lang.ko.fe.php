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
 * This file: Korean language data for the front-end (last modified: 2017.03.27).
 */

/** Prevents execution from outside of CIDRAM. */
if (!defined('CIDRAM')) {
    die('[CIDRAM] This should not be accessed directly.');
}

$CIDRAM['lang']['bNav_home_logout'] = '<a href="?">홈</a> | <a href="?cidram-page=logout">로그 아웃</a>';
$CIDRAM['lang']['bNav_logout'] = '<a href="?cidram-page=logout">로그 아웃</a>';
$CIDRAM['lang']['config_general_ban_override'] = '"infraction_limit"를 초과하면 "forbid_on_block"를 덮어 쓰시겠습니까? 덮어 쓸 때: 차단 된 요청은 빈 페이지를 반환합니다 (템플릿 파일은 사용되지 않습니다). 200 = 덮어 쓰지 (Default / 기본값); 403 = "403 Forbidden"로 덮어; 503 = "503 Service unavailable"로 덮어한다.';
$CIDRAM['lang']['config_general_default_dns'] = '호스트 이름 검색에 사용하는 DNS (도메인 이름 시스템) 서버의 쉼표로 구분 된 목록입니다. Default (기본 설정) = "8.8.8.8,8.8.4.4" (Google DNS). 주의: 당신이 무엇을하고 있는지 모르는 한이를 변경하지 마십시오.';
$CIDRAM['lang']['config_general_disable_cli'] = 'CLI 모드를 해제 하는가?';
$CIDRAM['lang']['config_general_disable_frontend'] = '프론트 엔드에 대한 액세스를 비활성화하거나?';
$CIDRAM['lang']['config_general_disable_webfonts'] = '웹 글꼴을 사용하지 않도록 설정 하시겠습니까? True = 예; False = 아니오 (Default / 기본 설정).';
$CIDRAM['lang']['config_general_emailaddr'] = '지원을위한 이메일 주소입니다.';
$CIDRAM['lang']['config_general_forbid_on_block'] = '무엇 헤더 사용해야합니까 (요청을 차단했을 때)?';
$CIDRAM['lang']['config_general_FrontEndLog'] = '프론트 엔드 로그인 시도를 기록하는 파일. 파일 이름 지정하거나 해제하려면 비워하십시오.';
$CIDRAM['lang']['config_general_ipaddr'] = '연결 요청의 IP 주소를 어디에서 찾을 것인가에 대해 (Cloudflare 같은 서비스에 대해 유효). Default (기본 설정) = REMOTE_ADDR. 주의: 당신이 무엇을하고 있는지 모르는 한이를 변경하지 마십시오.';
$CIDRAM['lang']['config_general_lang'] = 'CIDRAM의 기본 언어를 설정합니다.';
$CIDRAM['lang']['config_general_logfile'] = '액세스 시도 저지를 기록, 인간에 의해 읽기 가능. 파일 이름 지정하거나 해제하려면 비워하십시오.';
$CIDRAM['lang']['config_general_logfileApache'] = '액세스 시도 저지를 기록, Apache 스타일. 파일 이름 지정하거나 해제하려면 비워하십시오.';
$CIDRAM['lang']['config_general_logfileSerialized'] = '액세스 시도 저지를 기록 직렬화되었습니다. 파일 이름 지정하거나 해제하려면 비워하십시오.';
$CIDRAM['lang']['config_general_log_banned_ips'] = '금지 된 IP에서 차단 된 요청을 로그 파일에 포함됩니까? True = 예 (Default / 기본값); False = 아니오.';
$CIDRAM['lang']['config_general_max_login_attempts'] = '로그인 시도 최대 횟수입니다.';
$CIDRAM['lang']['config_general_protect_frontend'] = 'CIDRAM 의해 보통 제공되는 보호를 프론트 엔드에 적용할지 여부를 지정합니다. True = 예 (Default / 기본값); False = 아니오.';
$CIDRAM['lang']['config_general_search_engine_verification'] = '검색 엔진의 요청을 확인해야합니까? 검색 엔진을 확인하여, 위반의 최대 수를 초과했기 때문에 검색 엔진이 금지되지 않는 것이 보증됩니다 (검색 엔진을 금지하는 것은 일반적으로 검색 엔진 순위의, SEO 등에 악영향을 미칩니다). 확인되면, 검색 엔진이 차단 될 수 있지만, 그러나 금지되지 않습니다. 검증되지 않은 경우는, 위반의 최대를 초과 한 결과, 금지 될 수 있습니다. 또한 검색 엔진의 검증은 사칭 된 검색 엔진으로부터 보호합니다 (이러한 요청은 차단됩니다). True = 검색 엔진의 검증을 활성화한다 (Default/기본 설정); False = 검색 엔진의 검증을 무효로한다.';
$CIDRAM['lang']['config_general_silent_mode'] = '"액세스 거부" 페이지를 표시하는 대신 CIDRAM는 차단 된 액세스 시도를 자동으로 리디렉션해야합니까? 그렇다면 리디렉션 위치를 지정합니다. 아니오의 경우이 변수를 비워 둡니다.';
$CIDRAM['lang']['config_general_timeOffset'] = '시간대 오프셋 (분).';
$CIDRAM['lang']['config_recaptcha_expiry'] = 'reCAPTCHA 인스턴스를 기억 시간.';
$CIDRAM['lang']['config_recaptcha_lockip'] = 'reCAPTCHA를 IP로 잠금 하시겠습니까?';
$CIDRAM['lang']['config_recaptcha_lockuser'] = 'reCAPTCHA를 사용자에 잠금 하시겠습니까?';
$CIDRAM['lang']['config_recaptcha_logfile'] = 'reCAPTCHA 시도 기록. 파일 이름 지정하거나 해제하려면 비워하십시오.';
$CIDRAM['lang']['config_recaptcha_secret'] = '이 값은 당신의 reCAPTCHA에 대한 "secret key" 에 대응하고있을 필요가 있습니다; 이것은 reCAPTCHA 대시 보드에서 찾을 수 있습니다.';
$CIDRAM['lang']['config_recaptcha_sitekey'] = '이 값은 당신의 reCAPTCHA에 대한 "site key" 에 대응하고있을 필요가 있습니다; 이것은 reCAPTCHA 대시 보드에서 찾을 수 있습니다.';
$CIDRAM['lang']['config_recaptcha_usemode'] = 'reCAPTCHA를 CIDRAM에서 사용하는 방법 (문서를 참조하십시오).';
$CIDRAM['lang']['config_signatures_block_bogons'] = '화성\ぼごんから의 CIDR 차단해야합니까? 당신은 로컬 호스트에서 또는 귀하의 LAN에서 로컬 네트워크에서 연결을 수신 한 경우, 이것은 false로 설정해야합니다. 없는 경우에는이를 true로 설정해야합니다.';
$CIDRAM['lang']['config_signatures_block_cloud'] = '클라우드 서비스에서 CIDR 차단해야합니까? 당신의 웹 사이트에서 API 서비스를 운영하거나 당신이 웹 사이트 간 연결이 예상되는 경우, 이것은 false로 설정해야합니다. 없는 경우에는이를 true로 설정해야합니다.';
$CIDRAM['lang']['config_signatures_block_generic'] = '일반적인 CIDR 차단해야합니까? (다른 옵션과 관련되지 않은).';
$CIDRAM['lang']['config_signatures_block_proxies'] = '프록시 서비스에서 CIDR 차단해야합니까? 익명 프록시 서비스가 필요한 경우는 false로 설정해야합니다. 없는 경우에는 보안을 향상시키기 위해이를 true로 설정해야합니다.';
$CIDRAM['lang']['config_signatures_block_spam'] = '스팸 때문에 CIDR 차단해야합니까? 문제가있는 경우를 제외하고 일반적으로이를 true로 설정해야합니다.';
$CIDRAM['lang']['config_signatures_default_tracktime'] = '모듈에 의해 금지 된 IP를 추적하는 초. Default (기본값) = 604800 (1 주).';
$CIDRAM['lang']['config_signatures_infraction_limit'] = 'IP가 IP 추적에 의해 금지되기 전에 발생하는 것이 허용된다 위반의 최대 수. Default (기본값) = 10.';
$CIDRAM['lang']['config_signatures_ipv4'] = 'IPv4의 서명 파일 목록 (CIDRAM는 이것을 사용합니다). 이것은 쉼표로 구분되어 있습니다.';
$CIDRAM['lang']['config_signatures_ipv6'] = 'IPv6의 서명 파일 목록 (CIDRAM는 이것을 사용합니다). 이것은 쉼표로 구분되어 있습니다.';
$CIDRAM['lang']['config_signatures_modules'] = 'IPv4/IPv6 서명을 체크 한 후로드 모듈 파일의 목록입니다. 이것은 쉼표로 구분되어 있습니다.';
$CIDRAM['lang']['config_signatures_track_mode'] = '위반은 언제 계산해야합니까? False = IP가 모듈에 의해 차단되는 경우. True = 뭐든지 이유로 IP가 차단 된 경우.';
$CIDRAM['lang']['config_template_data_css_url'] = '사용자 정의 테마의 CSS 파일 URL입니다.';
$CIDRAM['lang']['field_activate'] = '활성화';
$CIDRAM['lang']['field_banned'] = '금지 된';
$CIDRAM['lang']['field_blocked'] = '차단 된셨습니까?';
$CIDRAM['lang']['field_clear'] = '취소';
$CIDRAM['lang']['field_component'] = '구성 요소';
$CIDRAM['lang']['field_create_new_account'] = '새로운 계정 만들기';
$CIDRAM['lang']['field_deactivate'] = '비활성화';
$CIDRAM['lang']['field_delete_account'] = '계정 삭제';
$CIDRAM['lang']['field_delete_file'] = '삭제';
$CIDRAM['lang']['field_download_file'] = '다운로드';
$CIDRAM['lang']['field_edit_file'] = '편집';
$CIDRAM['lang']['field_expiry'] = '만료';
$CIDRAM['lang']['field_file'] = '파일';
$CIDRAM['lang']['field_filename'] = '파일 이름: ';
$CIDRAM['lang']['field_filetype_directory'] = '디렉토리';
$CIDRAM['lang']['field_filetype_info'] = '{EXT} 파일';
$CIDRAM['lang']['field_filetype_unknown'] = '알 수없는';
$CIDRAM['lang']['field_infractions'] = '위반';
$CIDRAM['lang']['field_install'] = '설치';
$CIDRAM['lang']['field_ip_address'] = 'IP 주소';
$CIDRAM['lang']['field_latest_version'] = '최신 버전';
$CIDRAM['lang']['field_log_in'] = '로그인';
$CIDRAM['lang']['field_new_name'] = '새 이름:';
$CIDRAM['lang']['field_ok'] = '승인';
$CIDRAM['lang']['field_options'] = '옵션';
$CIDRAM['lang']['field_password'] = '비밀번호';
$CIDRAM['lang']['field_permissions'] = '권한';
$CIDRAM['lang']['field_range'] = '범위 (처음 – 마지막)';
$CIDRAM['lang']['field_rename_file'] = '이름을 변경하려면';
$CIDRAM['lang']['field_reset'] = '재설정';
$CIDRAM['lang']['field_set_new_password'] = '새 암호를 설정합니다';
$CIDRAM['lang']['field_size'] = '전체 크기: ';
$CIDRAM['lang']['field_size_bytes'] = '바이트';
$CIDRAM['lang']['field_size_GB'] = 'GB';
$CIDRAM['lang']['field_size_KB'] = 'KB';
$CIDRAM['lang']['field_size_MB'] = 'MB';
$CIDRAM['lang']['field_size_TB'] = 'TB';
$CIDRAM['lang']['field_status'] = '상태';
$CIDRAM['lang']['field_tracking'] = '추적';
$CIDRAM['lang']['field_uninstall'] = '제거';
$CIDRAM['lang']['field_update'] = '업데이트';
$CIDRAM['lang']['field_upload_file'] = '새로운 파일을 업로드하기';
$CIDRAM['lang']['field_username'] = '사용자 이름';
$CIDRAM['lang']['field_your_version'] = '사용 버전';
$CIDRAM['lang']['header_login'] = '계속하려면 로그인하십시오.';
$CIDRAM['lang']['link_accounts'] = '계정';
$CIDRAM['lang']['link_cidr_calc'] = 'CIDR 계산기';
$CIDRAM['lang']['link_config'] = '구성';
$CIDRAM['lang']['link_documentation'] = '문서';
$CIDRAM['lang']['link_file_manager'] = '파일 관리자';
$CIDRAM['lang']['link_home'] = '홈';
$CIDRAM['lang']['link_ip_test'] = 'IP 테스트';
$CIDRAM['lang']['link_ip_tracking'] = 'IP 추적';
$CIDRAM['lang']['link_logs'] = '로고스';
$CIDRAM['lang']['link_updates'] = '업데이트';
$CIDRAM['lang']['logs_logfile_doesnt_exist'] = '선택한 로그는 존재하지 않습니다!';
$CIDRAM['lang']['logs_no_logfiles_available'] = '아니 로그를 사용할 수 있습니다.';
$CIDRAM['lang']['logs_no_logfile_selected'] = '로그가 선택되어 있지 않습니다.';
$CIDRAM['lang']['max_login_attempts_exceeded'] = '로그인 시도 횟수를 초과했습니다; 액세스 거부.';
$CIDRAM['lang']['response_accounts_already_exists'] = '계정이 이미 존재합니다!';
$CIDRAM['lang']['response_accounts_created'] = '계정 만들기에 성공했습니다!';
$CIDRAM['lang']['response_accounts_deleted'] = '계정 삭제가 성공했습니다!';
$CIDRAM['lang']['response_accounts_doesnt_exist'] = '계정이 존재하지 않습니다.';
$CIDRAM['lang']['response_accounts_password_updated'] = '암호 업데이트가 성공했습니다!';
$CIDRAM['lang']['response_activated'] = '활성화했습니다.';
$CIDRAM['lang']['response_activation_failed'] = '활성화에 실패했습니다!';
$CIDRAM['lang']['response_component_successfully_installed'] = '구성 요소의 설치에 성공했습니다.';
$CIDRAM['lang']['response_component_successfully_uninstalled'] = '구성 요소의 제거는 성공했습니다.';
$CIDRAM['lang']['response_component_successfully_updated'] = '구성 요소의 업데이트에 성공했습니다!';
$CIDRAM['lang']['response_component_uninstall_error'] = '구성 요소 제거하는 동안 오류가 발생했습니다.';
$CIDRAM['lang']['response_component_update_error'] = '구성 요소를 업데이트하는 동안 오류가 발생했습니다.';
$CIDRAM['lang']['response_configuration_updated'] = '구성 업데이트가 성공했습니다.';
$CIDRAM['lang']['response_deactivated'] = '비활성화했습니다.';
$CIDRAM['lang']['response_deactivation_failed'] = '비활성화에 실패했습니다!';
$CIDRAM['lang']['response_delete_error'] = '삭제에 실패했습니다!';
$CIDRAM['lang']['response_directory_deleted'] = '디렉토리가 성공적으로 삭제되었습니다!';
$CIDRAM['lang']['response_directory_renamed'] = '디렉토리의 이름이 변경되었습니다!';
$CIDRAM['lang']['response_error'] = '오류';
$CIDRAM['lang']['response_file_deleted'] = '파일 삭제가 성공했습니다!';
$CIDRAM['lang']['response_file_edited'] = '파일이 성공적으로 변경되었습니다!';
$CIDRAM['lang']['response_file_renamed'] = '파일 이름이 변경되었습니다!';
$CIDRAM['lang']['response_file_uploaded'] = '파일이 성공적으로 업로드되었습니다!';
$CIDRAM['lang']['response_login_invalid_password'] = '로그인 실패! 잘못된 암호!';
$CIDRAM['lang']['response_login_invalid_username'] = '로그인 실패! 사용자 이름은 존재하지 않습니다!';
$CIDRAM['lang']['response_login_password_field_empty'] = '암호가 비어 있습니다!';
$CIDRAM['lang']['response_login_username_field_empty'] = '사용자 이름 입력이 비어 있습니다!';
$CIDRAM['lang']['response_no'] = '아니오';
$CIDRAM['lang']['response_rename_error'] = '이름을 변경할 수 없습니다!';
$CIDRAM['lang']['response_tracking_cleared'] = '추적이 취소되었습니다.';
$CIDRAM['lang']['response_updates_already_up_to_date'] = '이미 최신 상태입니다.';
$CIDRAM['lang']['response_updates_not_installed'] = '구성 요소 설치되어 있지 않습니다!';
$CIDRAM['lang']['response_updates_not_installed_php'] = '구성 요소 설치되어 있지 않습니다 (PHP {V}가 필요합니다)!';
$CIDRAM['lang']['response_updates_outdated'] = '구식입니다!';
$CIDRAM['lang']['response_updates_outdated_manually'] = '구식입니다 (수동으로 업데이트하십시오)!';
$CIDRAM['lang']['response_updates_outdated_php_version'] = '구식입니다 (PHP {V}가 필요합니다)!';
$CIDRAM['lang']['response_updates_unable_to_determine'] = '결정 수 없습니다.';
$CIDRAM['lang']['response_upload_error'] = '업로드에 실패했습니다!';
$CIDRAM['lang']['response_yes'] = '예';
$CIDRAM['lang']['state_complete_access'] = '전체 액세스';
$CIDRAM['lang']['state_component_is_active'] = '구성 요소가 활성화됩니다.';
$CIDRAM['lang']['state_component_is_inactive'] = '구성 요소가 비활성 상태입니다.';
$CIDRAM['lang']['state_component_is_provisional'] = '구성 요소가 잠정입니다.';
$CIDRAM['lang']['state_default_password'] = '경고: 기본 암호를 사용하여!';
$CIDRAM['lang']['state_logged_in'] = '로그인 있습니다.';
$CIDRAM['lang']['state_logs_access_only'] = '로그에만 액세스';
$CIDRAM['lang']['state_password_not_valid'] = '경고: 이 계정은 올바른 암호를 사용하지 않습니다!';
$CIDRAM['lang']['switch-hide-non-outdated-set-false'] = '비 구형을 숨기지 않고';
$CIDRAM['lang']['switch-hide-non-outdated-set-true'] = '비 구식 숨기기';
$CIDRAM['lang']['switch-hide-unused-set-false'] = '미사용을 숨기지 않고';
$CIDRAM['lang']['switch-hide-unused-set-true'] = '미사용 숨기기';
$CIDRAM['lang']['tip_accounts'] = '안녕하세요, {username}.<br />계정 페이지는 CIDRAM 프론트 엔드에 액세스 할 수있는 사용자를 제어 할 수 있습니다.';
$CIDRAM['lang']['tip_cidr_calc'] = '안녕하세요, {username}.<br />CIDR 계산기는 IP 주소가 어떻게 CIDR에 속해 있는지를 계산할 수 있습니다.';
$CIDRAM['lang']['tip_config'] = '안녕하세요, {username}.<br />구성 페이지는 프론트 엔드에서 CIDRAM의 설정을 변경할 수 있습니다.';
$CIDRAM['lang']['tip_donate'] = 'CIDRAM는 무료로 제공되고 있습니다, 하지만 당신이 원한다면 기부 버튼을 클릭하면 프로젝트에 기부 할 수 있습니다.';
$CIDRAM['lang']['tip_enter_ips_here'] = '여기에 IP를 입력하십시오.';
$CIDRAM['lang']['tip_enter_ip_here'] = '여기에 IP를 입력하십시오.';
$CIDRAM['lang']['tip_file_manager'] = '안녕하세요, {username}.<br />파일 관리자를 사용하여 파일을 삭제, 편집, 업로드, 다운로드 할 수 있습니다. 신중하게 사용하는 (이것을 사용하여 설치를 끊을 수 있습니다).';
$CIDRAM['lang']['tip_home'] = '안녕하세요, {username}.<br />이것은 CIDRAM 프론트 엔드의 홈페이지입니다. 계속하려면 왼쪽 탐색 메뉴에서 링크를 선택합니다.';
$CIDRAM['lang']['tip_ip_test'] = '안녕하세요, {username}.<br />IP 테스트 페이지는 IP 주소가 차단되어 있는지를 테스트 할 수 있습니다.';
$CIDRAM['lang']['tip_ip_tracking'] = '안녕하세요, {username}.<br />IP 추적 페이지에서 IP 주소 추적 상태를 확인할 수 있습니다. 당신이 금지되어있는 것을 확인 할 수 있으며, 원한다면 당신은 추적을 취소 할 수 있습니다.';
$CIDRAM['lang']['tip_login'] = '기본 사용자 이름: <span class="txtRd">admin</span> – 기본 암호: <span class="txtRd">password</span>';
$CIDRAM['lang']['tip_logs'] = '안녕하세요, {username}.<br />로그의 내용을 보려면 다음 목록에서 로그를 선택합니다.';
$CIDRAM['lang']['tip_see_the_documentation'] = '설정 지시어에 대한 자세한 내용은 <a href="https://github.com/Maikuolan/CIDRAM/blob/master/_docs/readme.ko.md#SECTION6">문서를</a> 참조하십시오.';
$CIDRAM['lang']['tip_updates'] = '안녕하세요, {username}.<br />업데이트 페이지는 CIDRAM의 다양한 구성 요소를 설치, 제거, 업데이트 할 수 있습니다 (코어 패키지, 서명, L10N 파일 등).';
$CIDRAM['lang']['title_accounts'] = 'CIDRAM – 계정';
$CIDRAM['lang']['title_cidr_calc'] = 'CIDRAM – CIDR 계산기';
$CIDRAM['lang']['title_config'] = 'CIDRAM – 구성';
$CIDRAM['lang']['title_file_manager'] = 'CIDRAM – 파일 관리자';
$CIDRAM['lang']['title_home'] = 'CIDRAM – 홈';
$CIDRAM['lang']['title_ip_test'] = 'CIDRAM – IP 테스트';
$CIDRAM['lang']['title_ip_tracking'] = 'CIDRAM – IP 추적';
$CIDRAM['lang']['title_login'] = 'CIDRAM – 로그인';
$CIDRAM['lang']['title_logs'] = 'CIDRAM – 로고스';
$CIDRAM['lang']['title_updates'] = 'CIDRAM – 업데이트';

$CIDRAM['lang']['info_some_useful_links'] = '유용한 링크:<ul>
            <li><a href="https://github.com/Maikuolan/CIDRAM/issues">CIDRAM Issues @ GitHub</a> – CIDRAM 문제 페이지 (지원, 원조 등).</li>
            <li><a href="http://www.spambotsecurity.com/forum/viewforum.php?f=61">CIDRAM @ Spambot Security</a> – CIDRAM 토론 포럼 (지원, 원조 등).</li>
            <li><a href="https://wordpress.org/plugins/cidram/">CIDRAM @ Wordpress.org</a> – CIDRAM 자료 Wordpress 플러그인.</li>
            <li><a href="https://sourceforge.net/projects/cidram/">CIDRAM @ SourceForge</a> – CIDRAM 대체 다운로드 거울.</li>
            <li><a href="https://websectools.com/">WebSecTools.com</a> – 웹 사이트를 보호하기 위해 간단한 웹 마스터 도구 모음.</li>
            <li><a href="https://macmathan.info/blocklists">MacMathan.info Range Blocks</a> – 불필요한 국가가 당신의 웹 사이트에 액세스하는 것을 차단하기 위해 CIDRAM에 추가 할 수있는 옵션의 범위 블록이 포함되어 있습니다.</li>
            <li><a href="https://www.facebook.com/groups/2204685680/">International PHP Group @ Facebook</a> – PHP 학습 자원과 토론.</li>
            <li><a href="https://wwphp-fb.github.io/">International PHP Group @ GitHub</a> – PHP 학습 자원과 토론.</li>
            <li><a href="http://bgp.he.net/">Hurricane Electric BGP Toolkit</a> – ASN에서 CIDR을 취득하는 ASN 관계를 결정하는 네트워크 이름에 따라 ASN을 감지, 등등.</li>
            <li><a href="https://www.stopforumspam.com/forum/">Forum @ Stop Forum Spam</a> – 포럼 스팸 정지에 관한 유용한 토론 포럼.</li>
            <li><a href="https://www.stopforumspam.com/aggregate">IP Aggregator @ Stop Forum Spam</a> – IPv4 IP에 대한 유용한 통합 도구.</li>
            <li><a href="https://radar.qrator.net/">Radar by Qrator</a> – ASN의 연결을 확인하는 데 유용한 도구; ASN에 관한 기타 다양한 정보.</li>
            <li><a href="http://www.ipdeny.com/ipblocks/">IPdeny IP country blocks</a> – 국가 전체의 서명을 생성하기위한 훌륭한 정확한 서비스.</li>
            <li><a href="https://www.google.com/transparencyreport/safebrowsing/malware/">Google Malware Dashboard</a> – ASN의 악성 코드 감염률에 대한 보고서를 표시합니다.</li>
            <li><a href="https://www.spamhaus.org/statistics/botnet-asn/">The Spamhaus Project</a> – ASN의 봇넷 감염 속도에 대한 보고서를 표시합니다.</li>
            <li><a href="http://www.abuseat.org/asn.html">Abuseat.org\'s Composite Blocking List</a> – ASN의 봇넷 감염 속도에 대한 보고서를 표시합니다.</li>
            <li><a href="https://abuseipdb.com/">AbuseIPDB</a> – 알려진 위험한 IP 주소의 데이터베이스를 유지합니다; IP 주소를 확인하고보고하기위한 API를 제공합니다.</li>
            <li><a href="https://www.megarbl.net/index.php">MegaRBL.net</a> – 알려진 스패머 목록을 유지하는; IP/ASN 스팸 활동의 확인에 도움이됩니다.</li>
        </ul>';
