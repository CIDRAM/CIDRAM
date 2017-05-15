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
 * This file: Chinese (traditional) language data for the front-end (last modified: 2017.05.15).
 */

/** Prevents execution from outside of CIDRAM. */
if (!defined('CIDRAM')) {
    die('[CIDRAM] This should not be accessed directly.');
}

$CIDRAM['lang']['bNav_home_logout'] = '<a href="?">主頁</a> | <a href="?cidram-page=logout">登出</a>';
$CIDRAM['lang']['bNav_logout'] = '<a href="?cidram-page=logout">登出</a>';
$CIDRAM['lang']['config_general_ban_override'] = '覆蓋“forbid_on_block”當“infraction_limit”已被超過？ 當覆蓋：已阻止的請求返回一個空白頁（不使用模板文件）。 200 = 不要覆蓋【標準】； 403 = 使用“403 Forbidden”覆蓋； 503 = 使用“503 Service unavailable”覆蓋。';
$CIDRAM['lang']['config_general_default_dns'] = '以逗號分隔的DNS服務器列表，用於主機名查找。 標準 = “8.8.8.8,8.8.4.4” (Google DNS)。 警告： 不要修改此除非您知道什麼您做著！';
$CIDRAM['lang']['config_general_disable_cli'] = '關閉CLI模式嗎？';
$CIDRAM['lang']['config_general_disable_frontend'] = '關閉前端訪問嗎？';
$CIDRAM['lang']['config_general_disable_webfonts'] = '關閉網絡字體嗎？ True = 關閉； False = 不關閉【標準】。';
$CIDRAM['lang']['config_general_emailaddr'] = '支持/援助電子郵件地址。';
$CIDRAM['lang']['config_general_forbid_on_block'] = '什麼頭CIDRAM應該應對當申請是拒絕？';
$CIDRAM['lang']['config_general_FrontEndLog'] = '前端登錄嘗試的錄音文件。指定一個文件名，或留空以禁用。';
$CIDRAM['lang']['config_general_ipaddr'] = '在哪裡可以找到連接請求IP地址？ （可以使用為服務例如Cloudflare和類似）。 標準 = REMOTE_ADDR。 警告： 不要修改此除非您知道什麼您做著！';
$CIDRAM['lang']['config_general_lang'] = '指定標準CIDRAM語言。';
$CIDRAM['lang']['config_general_logfile'] = '人類可讀文件用於記錄所有被攔截的訪問。指定一個文件名，或留空以禁用。';
$CIDRAM['lang']['config_general_logfileApache'] = 'Apache風格文件用於記錄所有被攔截的訪問。指定一個文件名，或留空以禁用。';
$CIDRAM['lang']['config_general_logfileSerialized'] = '連載的文件用於記錄所有被攔截的訪問。指定一個文件名，或留空以禁用。';
$CIDRAM['lang']['config_general_log_banned_ips'] = '包括IP禁止從阻止請求在日誌文件嗎？ True = 是【標準】； False = 不是。';
$CIDRAM['lang']['config_general_max_login_attempts'] = '最大登錄嘗試次數。';
$CIDRAM['lang']['config_general_protect_frontend'] = '指定是否應將CIDRAM通常提供的保護應用於前端。 True = 是【標準】； False = 不是。';
$CIDRAM['lang']['config_general_search_engine_verification'] = '嘗試驗證來自搜索引擎的請求？ 驗證搜索引擎確保他們不會因超過違規限製而被禁止 （禁止在您的網站上使用搜索引擎通常會有產生負面影響對您的搜索引擎排名，SEO，等等）。 當被驗證，搜索引擎可以被阻止，但不會被禁止。 當不被驗證，他們可以由於超過違規限製而被禁止。 另外，搜索引擎驗證提供保護針對假搜索引擎請求和針對潛在的惡意實體偽裝成搜索引擎（當搜索引擎驗證是啟用，這些請求將被阻止）。 True = 搜索引擎驗證是啟用【標準】； False = 搜索引擎驗證是禁用。';
$CIDRAM['lang']['config_general_silent_mode'] = 'CIDRAM應該默默重定向被攔截的訪問而不是顯示該“拒絕訪問”頁嗎？指定位置至重定向被攔截的訪問，或讓它空將其禁用。';
$CIDRAM['lang']['config_general_timeFormat'] = 'CIDRAM使用的日期符號格式。可根據要求增加附加選項。';
$CIDRAM['lang']['config_general_timeOffset'] = '時區偏移量（分鐘）。';
$CIDRAM['lang']['config_general_timezone'] = '您的時區。';
$CIDRAM['lang']['config_general_truncate'] = '截斷日誌文件當他們達到一定的大小嗎？ 值是在B/KB/MB/GB/TB，是日誌文件允許的最大大小直到它被截斷。 默認值為“0KB”將禁用截斷（日誌文件可以無限成長）。 注意：適用於單個日誌文件！日誌文件大小不被算集體的。';
$CIDRAM['lang']['config_recaptcha_expiry'] = '記得reCAPTCHA多少小時？';
$CIDRAM['lang']['config_recaptcha_lockip'] = '應該reCAPTCHA鎖定到IP？';
$CIDRAM['lang']['config_recaptcha_lockuser'] = '應該reCAPTCHA鎖定到用戶？';
$CIDRAM['lang']['config_recaptcha_logfile'] = '記錄所有的reCAPTCHA的嘗試？要做到這一點，指定一個文件名到使用。如果不，離開這個變量為空白。';
$CIDRAM['lang']['config_recaptcha_secret'] = '該值應該對應於“secret key”為您的reCAPTCHA，該可以發現在reCAPTCHA的儀表板。';
$CIDRAM['lang']['config_recaptcha_sitekey'] = '該值應該對應於“site key”為您的reCAPTCHA，該可以發現在reCAPTCHA的儀表板。';
$CIDRAM['lang']['config_recaptcha_usemode'] = '它定義瞭如何CIDRAM應該使用reCAPTCHA（請參閱文檔）。';
$CIDRAM['lang']['config_signatures_block_bogons'] = '阻止bogon(“ㄅㄡㄍㄛㄋ”)/martian（“火星”）CIDR嗎？如果您希望連接到您的網站從您的本地網絡/本地主機/localhost/LAN/等等，這應該被設置為“false”（假）。如果不，這應該被設置為“true”（真）。';
$CIDRAM['lang']['config_signatures_block_cloud'] = '阻止CIDR認定為屬於虛擬主機或云服務嗎？如果您操作一個API服務從您的網站或如果您預計其他網站連接到您的網站，這應該被設置為“false”（假）。如果不，這應該被設置為“true”（真）。';
$CIDRAM['lang']['config_signatures_block_generic'] = '阻止CIDR一般建議對於黑名單嗎？這包括簽名不標記為的一章節任何其他更具體簽名類別。';
$CIDRAM['lang']['config_signatures_block_proxies'] = '阻止CIDR認定為屬於代理服務嗎？如果您需要該用戶可以訪問您的網站從匿名代理服務，這應該被設置為“false”（假）。除此以外，如果您不需要匿名代理服務，這應該被設置為“true”（真）作為一個方式以提高安全性。';
$CIDRAM['lang']['config_signatures_block_spam'] = '阻止高風險垃圾郵件CIDR嗎？除非您遇到問題當這樣做，通常，這應該被設置為“true”（真）。';
$CIDRAM['lang']['config_signatures_default_tracktime'] = '多少秒鐘來跟踪模塊禁止的IP。 標準 = 604800 （1週）。';
$CIDRAM['lang']['config_signatures_infraction_limit'] = '從IP最大允許違規數量之前它被禁止。 標準 = 10。';
$CIDRAM['lang']['config_signatures_ipv4'] = '列表的IPv4簽名文件，CIDRAM應該嘗試使用，用逗號分隔。';
$CIDRAM['lang']['config_signatures_ipv6'] = '列表的IPv6簽名文件，CIDRAM應該嘗試使用，用逗號分隔。';
$CIDRAM['lang']['config_signatures_modules'] = '模塊文件要加載的列表以後檢查簽名IPv4/IPv6，用逗號分隔。';
$CIDRAM['lang']['config_signatures_track_mode'] = '什麼時候應該對違規行為進行計數？ False = 當IP被模塊阻止時。 True = 當IP由於任何原因阻止時。';
$CIDRAM['lang']['config_template_data_css_url'] = '自定義主題的CSS文件URL。';
$CIDRAM['lang']['field_activate'] = '啟用';
$CIDRAM['lang']['field_banned'] = '禁止';
$CIDRAM['lang']['field_blocked'] = '已阻止';
$CIDRAM['lang']['field_clear'] = '撤消';
$CIDRAM['lang']['field_component'] = '組件';
$CIDRAM['lang']['field_create_new_account'] = '創建新賬戶';
$CIDRAM['lang']['field_deactivate'] = '停用';
$CIDRAM['lang']['field_delete_account'] = '刪除賬戶';
$CIDRAM['lang']['field_delete_file'] = '刪除';
$CIDRAM['lang']['field_download_file'] = '下載';
$CIDRAM['lang']['field_edit_file'] = '編輯';
$CIDRAM['lang']['field_expiry'] = '到期';
$CIDRAM['lang']['field_file'] = '文件';
$CIDRAM['lang']['field_filename'] = '文件名：';
$CIDRAM['lang']['field_filetype_directory'] = '文件夾';
$CIDRAM['lang']['field_filetype_info'] = '{EXT}文件';
$CIDRAM['lang']['field_filetype_unknown'] = '未知';
$CIDRAM['lang']['field_first_seen'] = '當第一次遇到';
$CIDRAM['lang']['field_infractions'] = '違規';
$CIDRAM['lang']['field_install'] = '安裝';
$CIDRAM['lang']['field_ip_address'] = 'IP地址';
$CIDRAM['lang']['field_latest_version'] = '最新版本';
$CIDRAM['lang']['field_log_in'] = '登錄';
$CIDRAM['lang']['field_new_name'] = '新名稱：';
$CIDRAM['lang']['field_ok'] = 'OK';
$CIDRAM['lang']['field_options'] = '選項';
$CIDRAM['lang']['field_password'] = '密碼';
$CIDRAM['lang']['field_permissions'] = '權限';
$CIDRAM['lang']['field_range'] = '範圍 （初始 – 最後）';
$CIDRAM['lang']['field_rename_file'] = '改名';
$CIDRAM['lang']['field_reset'] = '重啟';
$CIDRAM['lang']['field_set_new_password'] = '保存新密碼';
$CIDRAM['lang']['field_size'] = '總大小：';
$CIDRAM['lang']['field_size_bytes'] = '字節';
$CIDRAM['lang']['field_size_GB'] = 'GB';
$CIDRAM['lang']['field_size_KB'] = 'KB';
$CIDRAM['lang']['field_size_MB'] = 'MB';
$CIDRAM['lang']['field_size_TB'] = 'TB';
$CIDRAM['lang']['field_status'] = '狀態';
$CIDRAM['lang']['field_system_timezone'] = '使用系統默認時區。';
$CIDRAM['lang']['field_tracking'] = '跟踪';
$CIDRAM['lang']['field_uninstall'] = '卸載';
$CIDRAM['lang']['field_update'] = '更新';
$CIDRAM['lang']['field_update_all'] = '更新一切';
$CIDRAM['lang']['field_upload_file'] = '上傳新文件';
$CIDRAM['lang']['field_username'] = '用戶名';
$CIDRAM['lang']['field_your_version'] = '您的版本';
$CIDRAM['lang']['header_login'] = '請登錄以繼續。';
$CIDRAM['lang']['label_active_config_file'] = '活動配置文件：';
$CIDRAM['lang']['label_cidram'] = '目前使用CIDRAM版本：';
$CIDRAM['lang']['label_os'] = '目前使用操作系統：';
$CIDRAM['lang']['label_php'] = '目前使用PHP版本：';
$CIDRAM['lang']['label_sapi'] = '目前使用SAPI：';
$CIDRAM['lang']['label_sysinfo'] = '系統信息：';
$CIDRAM['lang']['link_accounts'] = '賬戶';
$CIDRAM['lang']['link_cidr_calc'] = 'CIDR計算器';
$CIDRAM['lang']['link_config'] = '配置';
$CIDRAM['lang']['link_documentation'] = '文檔';
$CIDRAM['lang']['link_file_manager'] = '文件管理器';
$CIDRAM['lang']['link_home'] = '主頁';
$CIDRAM['lang']['link_ip_test'] = 'IP測試';
$CIDRAM['lang']['link_ip_tracking'] = 'IP跟踪';
$CIDRAM['lang']['link_logs'] = '日誌';
$CIDRAM['lang']['link_updates'] = '更新';
$CIDRAM['lang']['logs_logfile_doesnt_exist'] = '選擇的日誌不存在！';
$CIDRAM['lang']['logs_no_logfiles_available'] = '沒有日誌可用。';
$CIDRAM['lang']['logs_no_logfile_selected'] = '沒有選擇的日誌。';
$CIDRAM['lang']['max_login_attempts_exceeded'] = '最大登錄嘗試次數已經超過；拒絕訪問。';
$CIDRAM['lang']['previewer_days'] = '天';
$CIDRAM['lang']['previewer_hours'] = '小時';
$CIDRAM['lang']['previewer_minutes'] = '分';
$CIDRAM['lang']['previewer_months'] = '月';
$CIDRAM['lang']['previewer_seconds'] = '秒';
$CIDRAM['lang']['previewer_weeks'] = '週';
$CIDRAM['lang']['previewer_years'] = '年';
$CIDRAM['lang']['response_accounts_already_exists'] = '一個賬戶與那個用戶名已經存在！';
$CIDRAM['lang']['response_accounts_created'] = '帳戶成功創建！';
$CIDRAM['lang']['response_accounts_deleted'] = '帳戶成功刪除！';
$CIDRAM['lang']['response_accounts_doesnt_exist'] = '那個帳戶不存在。';
$CIDRAM['lang']['response_accounts_password_updated'] = '密碼成功更新！';
$CIDRAM['lang']['response_activated'] = '已成功啟用。';
$CIDRAM['lang']['response_activation_failed'] = '無法啟用！';
$CIDRAM['lang']['response_component_successfully_installed'] = '組件成功安裝。';
$CIDRAM['lang']['response_component_successfully_uninstalled'] = '組件成功卸載。';
$CIDRAM['lang']['response_component_successfully_updated'] = '組件成功更新。';
$CIDRAM['lang']['response_component_uninstall_error'] = '一個錯誤發生當嘗試卸載組件。';
$CIDRAM['lang']['response_component_update_error'] = '一個錯誤發生當嘗試更新組件。';
$CIDRAM['lang']['response_configuration_updated'] = '配置成功更新。';
$CIDRAM['lang']['response_deactivated'] = '已成功停用。';
$CIDRAM['lang']['response_deactivation_failed'] = '無法停用！';
$CIDRAM['lang']['response_delete_error'] = '無法刪除！';
$CIDRAM['lang']['response_directory_deleted'] = '文件夾成功刪除！';
$CIDRAM['lang']['response_directory_renamed'] = '文件夾成功改名！';
$CIDRAM['lang']['response_error'] = '錯誤';
$CIDRAM['lang']['response_file_deleted'] = '文件成功刪除！';
$CIDRAM['lang']['response_file_edited'] = '文件成功改性！';
$CIDRAM['lang']['response_file_renamed'] = '文件成功改名！';
$CIDRAM['lang']['response_file_uploaded'] = '文件成功上傳！';
$CIDRAM['lang']['response_login_invalid_password'] = '登錄失敗！密碼無效！';
$CIDRAM['lang']['response_login_invalid_username'] = '登錄失敗！用戶名不存在！';
$CIDRAM['lang']['response_login_password_field_empty'] = '密碼輸入是空的！';
$CIDRAM['lang']['response_login_username_field_empty'] = '用戶名輸入是空的！';
$CIDRAM['lang']['response_no'] = '不是';
$CIDRAM['lang']['response_rename_error'] = '無法改名！';
$CIDRAM['lang']['response_tracking_cleared'] = '已撤消跟踪。';
$CIDRAM['lang']['response_updates_already_up_to_date'] = '已經更新。';
$CIDRAM['lang']['response_updates_not_installed'] = '組件不安裝！';
$CIDRAM['lang']['response_updates_not_installed_php'] = '組件不安裝（它需要PHP {V}）！';
$CIDRAM['lang']['response_updates_outdated'] = '過時！';
$CIDRAM['lang']['response_updates_outdated_manually'] = '過時（請更新手動）！';
$CIDRAM['lang']['response_updates_outdated_php_version'] = '過時（它需要PHP {V}）！';
$CIDRAM['lang']['response_updates_unable_to_determine'] = '無法確定。';
$CIDRAM['lang']['response_upload_error'] = '無法上傳！';
$CIDRAM['lang']['response_yes'] = '是';
$CIDRAM['lang']['state_complete_access'] = '完全訪問';
$CIDRAM['lang']['state_component_is_active'] = '組件是活性。';
$CIDRAM['lang']['state_component_is_inactive'] = '組件是非活性。';
$CIDRAM['lang']['state_component_is_provisional'] = '組件是有時活性。';
$CIDRAM['lang']['state_default_password'] = '警告：它使用標準密碼！';
$CIDRAM['lang']['state_logged_in'] = '目前在線。';
$CIDRAM['lang']['state_logs_access_only'] = '僅日誌訪問';
$CIDRAM['lang']['state_password_not_valid'] = '警告：此帳戶不​​使用有效的密碼！';
$CIDRAM['lang']['switch-hide-non-outdated-set-false'] = '不要隱藏非過時';
$CIDRAM['lang']['switch-hide-non-outdated-set-true'] = '隱藏非過時';
$CIDRAM['lang']['switch-hide-unused-set-false'] = '不要隱藏非用過';
$CIDRAM['lang']['switch-hide-unused-set-true'] = '隱藏非用過';
$CIDRAM['lang']['tip_accounts'] = '你好，{username}。<br />賬戶頁面允許您控制誰可以訪問CIDRAM前端。';
$CIDRAM['lang']['tip_cidr_calc'] = '你好，{username}。<br />CIDR計算器允許您計算IP地址屬於哪個CIDR。';
$CIDRAM['lang']['tip_config'] = '你好，{username}。<br />配置頁面允許您修改CIDRAM配置從前端。';
$CIDRAM['lang']['tip_donate'] = 'CIDRAM是免費提供的，但如果您想捐贈給項目，您可以通過點擊捐贈按鈕這樣做。';
$CIDRAM['lang']['tip_enter_ips_here'] = '在這裡輸入IP。';
$CIDRAM['lang']['tip_enter_ip_here'] = '在這裡輸入IP。';
$CIDRAM['lang']['tip_file_manager'] = '你好，{username}。<br />文件管理器允許您刪除，編輯，上傳和下載文件。小心使用（您可以用這個破壞您的安裝）。';
$CIDRAM['lang']['tip_home'] = '你好，{username}。<br />這是CIDRAM的前端主頁。從左側的導航菜單中選擇一個鏈接以繼續。';
$CIDRAM['lang']['tip_ip_test'] = '你好，{username}。<br />IP測試頁面允許您測試是否IP地址被阻止通過當前安裝的簽名。';
$CIDRAM['lang']['tip_ip_tracking'] = '你好，{username}。<br />IP跟踪頁面允許您檢查IP地址跟踪狀態，檢查哪些IP已被禁止，而如果您想這樣做，對撤消他們的跟踪。';
$CIDRAM['lang']['tip_login'] = '標準用戶名： <span class="txtRd">admin</span> – 標準密碼： <span class="txtRd">password</span>';
$CIDRAM['lang']['tip_logs'] = '你好，{username}。<br />選擇一個日誌從下面的列表以查看那個日誌的內容。';
$CIDRAM['lang']['tip_see_the_documentation'] = '請參閱<a href="https://github.com/Maikuolan/CIDRAM/blob/master/_docs/readme.zh-TW.md#SECTION6">文檔</a>以獲取有關各種配置指令的信息和他們的目的。';
$CIDRAM['lang']['tip_updates'] = '你好，{username}。<br />更新頁面允許您安裝，卸載，和更新CIDRAM的各種組件（核心包，簽名，L10N文件，等等）。';
$CIDRAM['lang']['title_accounts'] = 'CIDRAM – 帳戶';
$CIDRAM['lang']['title_cidr_calc'] = 'CIDRAM – CIDR計算器';
$CIDRAM['lang']['title_config'] = 'CIDRAM – 配置';
$CIDRAM['lang']['title_file_manager'] = 'CIDRAM – 文件管理器';
$CIDRAM['lang']['title_home'] = 'CIDRAM – 主頁';
$CIDRAM['lang']['title_ip_test'] = 'CIDRAM – IP測試';
$CIDRAM['lang']['title_ip_tracking'] = 'CIDRAM – IP跟踪';
$CIDRAM['lang']['title_login'] = 'CIDRAM – 登錄';
$CIDRAM['lang']['title_logs'] = 'CIDRAM – 日誌';
$CIDRAM['lang']['title_updates'] = 'CIDRAM – 更新';

$CIDRAM['lang']['info_some_useful_links'] = '一些有用的链接：<ul>
            <li><a href="https://github.com/Maikuolan/CIDRAM/issues">CIDRAM問題＠GitHub</a> – CIDRAM問題頁面（支持，協助，等等）。</li>
            <li><a href="http://www.spambotsecurity.com/forum/viewforum.php?f=61">CIDRAM ＠ Spambot Security</a> – CIDRAM討論論壇（支持，協助，等等）。</li>
            <li><a href="https://wordpress.org/plugins/cidram/">CIDRAM＠WordPress.org</a> – CIDRAM WordPress插件。</li>
            <li><a href="https://www.oschina.net/p/CIDRAM">CIDRAM＠開源中國社區</a> – CIDRAM頁面託管在開源中國社區。</li>
            <li><a href="https://sourceforge.net/projects/cidram/">CIDRAM＠SourceForge</a> – CIDRAM替代下載鏡像。</li>
            <li><a href="https://websectools.com/">WebSecTools.com</a> – 簡單網站管理員工具集合為保護網站。</li>
            <li><a href="https://macmathan.info/blocklists">範圍阻止名單＠MacMathan.info</a> – 包含可選阻止名單，可以添加的在CIDRAM，用於阻止任何不需要的國家訪問您的網站。</li>
            <li><a href="https://www.facebook.com/groups/2204685680/">Global PHP Group ＠ Facebook</a> – PHP學習資源和討論。</li>
            <li><a href="https://php.earth/">PHP.earth</a> – PHP學習資源和討論。</li>
            <li><a href="http://bgp.he.net/">Hurricane Electric BGP Toolkit</a> – 從ASN獲取CIDR，確定ASN關係，基於網絡名稱發現ASN，等等。</li>
            <li><a href="https://www.stopforumspam.com/forum/">論壇 ＠ Stop Forum Spam</a> – 有用的討論論壇關於停止論壇垃圾郵件。</li>
            <li><a href="https://www.stopforumspam.com/aggregate">IP聚合器 ＠ Stop Forum Spam</a> – 有用的IPv4聚合工具。</li>
            <li><a href="https://radar.qrator.net/">Qrator的Radar</a> – 檢查ASN連接的有用工具，以及關於ASN的各種其他信息。</li>
            <li><a href="http://www.ipdeny.com/ipblocks/">IP國家阻止＠IPdeny</a> – 一個夢幻般和準確的服務，產生國家的簽名。</li>
            <li><a href="https://www.google.com/transparencyreport/safebrowsing/malware/">Google Malware Dashboard</a> – 顯示有​​關ASN惡意軟件感染率的報告。</li>
            <li><a href="https://www.spamhaus.org/statistics/botnet-asn/">Spamhaus項目</a> – 顯示有​​關ASN殭屍網絡感染率的報告。</li>
            <li><a href="http://www.abuseat.org/asn.html">Abuseat.org的複合阻止列表</a> – 顯示有​​關ASN殭屍網絡感染率的報告。</li>
            <li><a href="https://abuseipdb.com/">AbuseIPDB</a> – 維護已知的濫用IP數據庫；它為IP檢查和報告提供了一個API。</li>
            <li><a href="https://www.megarbl.net/index.php">MegaRBL.net</a> – 維護已知垃圾郵件發送者的列表；有用為檢查IP/ASN垃圾郵件活動。</li>
        </ul>';
