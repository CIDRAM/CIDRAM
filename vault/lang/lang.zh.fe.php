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
 * This file: Chinese (simplified) language data for the front-end (last modified: 2018.05.16).
 */

/** Prevents execution from outside of CIDRAM. */
if (!defined('CIDRAM')) {
    die('[CIDRAM] This should not be accessed directly.');
}

foreach (['IPv4', 'IPv6'] as $CIDRAM['IPvX']) {
    $CIDRAM['Pre'] = '标准' . $CIDRAM['IPvX'] . '签名通常包括在主包。​';
    $CIDRAM['lang']['Extended Description: ' . $CIDRAM['IPvX']] = $CIDRAM['Pre'] . '阻止不想要的云服务和非人终端。';
    $CIDRAM['lang']['Extended Description: ' . $CIDRAM['IPvX'] . '-Bogons'] = $CIDRAM['Pre'] . '阻止bogon/火星CIDR。';
    $CIDRAM['lang']['Extended Description: ' . $CIDRAM['IPvX'] . '-ISPs'] = $CIDRAM['Pre'] . '阻止危险和垃圾容易ISP。';
    $CIDRAM['lang']['Extended Description: ' . $CIDRAM['IPvX'] . '-Other'] = $CIDRAM['Pre'] . '阻止CIDR从代理，VPN和其他不需要服务。';
    $CIDRAM['Pre'] = $CIDRAM['IPvX'] . '签名文件（%s）。';
    $CIDRAM['lang']['Name: ' . $CIDRAM['IPvX']] = sprintf($CIDRAM['Pre'], '不想要的云服务和非人端点');
    $CIDRAM['lang']['Name: ' . $CIDRAM['IPvX'] . '-Bogons'] = sprintf($CIDRAM['Pre'], 'bogon/火星CIDR');
    $CIDRAM['lang']['Name: ' . $CIDRAM['IPvX'] . '-ISPs'] = sprintf($CIDRAM['Pre'], '危险和垃圾容易ISP');
    $CIDRAM['lang']['Name: ' . $CIDRAM['IPvX'] . '-Other'] = sprintf($CIDRAM['Pre'], 'CIDR从代理，VPN和其他不需要服务');
}
unset($CIDRAM['Pre'], $CIDRAM['IPvX']);

$CIDRAM['lang']['Extended Description: Bypasses'] = '标准签名旁路文件通常包括在主包。';
$CIDRAM['lang']['Extended Description: CIDRAM'] = '主包（没有签名文件，文档，和配置）。';
$CIDRAM['lang']['Extended Description: module_badhosts.php'] = '阻止垃圾邮件发送者，黑客，和其他恶意实体经常使用的主机。';
$CIDRAM['lang']['Extended Description: module_badhosts_isps.php'] = '阻止垃圾邮件发送者，黑客，和其他恶意实体经常使用的ISP拥有的主机。';
$CIDRAM['lang']['Extended Description: module_badtlds.php'] = '阻止垃圾邮件发送者，黑客，和其他恶意实体经常使用的主机的TLD。';
$CIDRAM['lang']['Extended Description: module_cookies.php'] = '提供一些针对危险cookie的有限保护。';
$CIDRAM['lang']['Extended Description: module_extras.php'] = '提供一些有限的保护针对各种攻击向量常用于请求。';
$CIDRAM['lang']['Extended Description: module_sfs.php'] = '防止通过SFS列出的IP地址访问注册和登录页面。';
$CIDRAM['lang']['Name: Bypasses'] = '标准签名旁路。';
$CIDRAM['lang']['Name: module_badhosts.php'] = '坏主机阻塞模块';
$CIDRAM['lang']['Name: module_badhosts_isps.php'] = '坏主机阻塞模块（ISP）';
$CIDRAM['lang']['Name: module_badtlds.php'] = '坏TLD阻塞模块';
$CIDRAM['lang']['Name: module_baidublocker.php'] = '百度阻塞模块';
$CIDRAM['lang']['Name: module_cookies.php'] = '可选cookie扫描器模块';
$CIDRAM['lang']['Name: module_extras.php'] = '可选安全附加模块';
$CIDRAM['lang']['Name: module_sfs.php'] = 'Stop Forum Spam 模块';
$CIDRAM['lang']['Name: module_yandexblocker.php'] = 'Yandex阻塞模块';
$CIDRAM['lang']['bNav_home_logout'] = '<a href="?">主页</a> | <a href="?cidram-page=logout">登出</a>';
$CIDRAM['lang']['bNav_logout'] = '<a href="?cidram-page=logout">登出</a>';
$CIDRAM['lang']['config_general_FrontEndLog'] = '前端登录尝试的录音文件。​指定一个文件名，​或留空以禁用。';
$CIDRAM['lang']['config_general_allow_gethostbyaddr_lookup'] = '当UDP不可用时允许gethostbyaddr查找？​True（真）=允许【标准】；False（假）=不允许。';
$CIDRAM['lang']['config_general_ban_override'] = '覆盖“forbid_on_block”当“infraction_limit”已被超过？​当覆盖：已阻止的请求返回一个空白页（不使用模板文件）。​200 = 不要覆盖【标准】； 403 = 使用“403 Forbidden”覆盖； 503 = 使用“503 Service unavailable”覆盖。';
$CIDRAM['lang']['config_general_default_algo'] = '定义要用于所有未来密码和会话的算法。​选项：​​PASSWORD_DEFAULT（标准），​PASSWORD_BCRYPT，​PASSWORD_ARGON2I（需要PHP &gt;= 7.2.0）。';
$CIDRAM['lang']['config_general_default_dns'] = '以逗号分隔的DNS服务器列表，​用于主机名查找。​标准 = “8.8.8.8,8.8.4.4” (Google DNS)。​警告：不要修改此除非您知道什么您做着！';
$CIDRAM['lang']['config_general_disable_cli'] = '关闭CLI模式吗？​CLI模式是按说激活作为标准，​但可以有时干扰某些测试工具（例如PHPUnit，​为例子）和其他基于CLI应用。​如果您没有需要关闭CLI模式，​您应该忽略这个指令。​False（假）=激活CLI模式【标准】；True（真）=关闭CLI模式。';
$CIDRAM['lang']['config_general_disable_frontend'] = '关闭前端访问吗？​前端访问可以使CIDRAM更易于管理，​但也可能是潜在的安全风险。​建议管理CIDRAM通过后端只要有可能，​但前端访问提供当不可能。​保持关闭除非您需要它。​False（假）=激活前端访问；True（真）=关闭前端访问【标准】。';
$CIDRAM['lang']['config_general_disable_webfonts'] = '关闭网络字体吗？​True（真）=关闭【标准】；False（假）=不关闭。';
$CIDRAM['lang']['config_general_emailaddr'] = '如果您希望，​您可以提供电子邮件地址这里要给予用户当他们被阻止，​他们使用作为接触点为支持和/或帮助在的情况下他们错误地阻止。​警告:您提供的任何电子邮件地址，​它肯定会被获得通过垃圾邮件机器人和铲运机，​所以，​它强烈推荐如果选择提供一个电子邮件地址这里，​您保证它是一次性的和/或不是很重要（换一种说法，​您可能不希望使用您的主电子邮件地址或您的企业电子邮件地址）。';
$CIDRAM['lang']['config_general_emailaddr_display_style'] = '您希望如何将电子邮件地址呈现给用户？';
$CIDRAM['lang']['config_general_forbid_on_block'] = '什么头CIDRAM应该应对当申请是拒绝？';
$CIDRAM['lang']['config_general_force_hostname_lookup'] = '强制主机名查找？​True（真）=跟踪；False（假）=不跟踪【标准】。​主机名查询通常在“根据需要”的基础上执行，但可以在所有请求上强制。​这可能会有助于提供日志文件中更详细的信息，但也可能会对性能产生轻微的负面影响。';
$CIDRAM['lang']['config_general_hide_version'] = '从日志和页面输出中隐藏版本信息吗？​True（真）=关闭；False（假）=不关闭【标准】。';
$CIDRAM['lang']['config_general_ipaddr'] = '在哪里可以找到连接请求IP地址？​（可以使用为服务例如Cloudflare和类似）。​标准 = REMOTE_ADDR。​警告：不要修改此除非您知道什么您做着！';
$CIDRAM['lang']['config_general_lang'] = '指定标准CIDRAM语言。';
$CIDRAM['lang']['config_general_log_banned_ips'] = '包括IP禁止从阻止请求在日志文件吗？​True（真）=是【标准】；False（假）=不是。';
$CIDRAM['lang']['config_general_log_rotation_action'] = '日志轮转限制了任何时候应该存在的日志文件的数量。​当新的日志文件被创建时，如果日志文件的指定的最大数量已经超过，将执行指定的操作。​您可以在此处指定所需的操作。​“Delete”=删除最旧的日志文件，直到不再超出限制。​“Archive”=首先归档，然后删除最旧的日志文件，直到不再超出限制。';
$CIDRAM['lang']['config_general_log_rotation_limit'] = '日志轮转限制了任何时候应该存在的日志文件的数量。​当新的日志文件被创建时，如果日志文件的指定的最大数量已经超过，将执行指定的操作。​您可以在此指定所需的限制。​值为“0”将禁用日志轮转。';
$CIDRAM['lang']['config_general_logfile'] = '人类可读文件用于记录所有被拦截的访问。​指定一个文件名，​或留空以禁用。';
$CIDRAM['lang']['config_general_logfileApache'] = 'Apache风格文件用于记录所有被拦截的访问。​指定一个文件名，​或留空以禁用。';
$CIDRAM['lang']['config_general_logfileSerialized'] = '连载的文件用于记录所有被拦截的访问。​指定一个文件名，​或留空以禁用。';
$CIDRAM['lang']['config_general_maintenance_mode'] = '启用维护模式？​True（真）=关闭；​False（假）=不关闭【标准】。​它停用一切以外前端。​有时候在更新CMS，框架，等时有用。';
$CIDRAM['lang']['config_general_max_login_attempts'] = '最大登录尝试次数。';
$CIDRAM['lang']['config_general_numbers'] = '您如何喜欢显示数字？​选择最适合示例。';
$CIDRAM['lang']['config_general_protect_frontend'] = '指定是否应将CIDRAM通常提供的保护应用于前端。​True（真）=是【标准】；False（假）=不是。';
$CIDRAM['lang']['config_general_search_engine_verification'] = '尝试验证来自搜索引擎的请求？​验证搜索引擎确保他们不会因超过违规限制而被禁止 （禁止在您的网站上使用搜索引擎通常会有产生负面影响对您的搜索引擎排名，​SEO，​等等）。​当被验证，​搜索引擎可以被阻止，​但不会被禁止。​当不被验证，​他们可以由于超过违规限制而被禁止。​另外，​搜索引擎验证提供保护针对假搜索引擎请求和针对潜在的恶意实体伪装成搜索引擎（当搜索引擎验证是启用，​这些请求将被阻止）。​True（真）=搜索引擎验证是启用【标准】；False（假）=搜索引擎验证是禁用。';
$CIDRAM['lang']['config_general_silent_mode'] = 'CIDRAM应该默默重定向被拦截的访问而不是显示该“拒绝访问”页吗？​指定位置至重定向被拦截的访问，​或让它空将其禁用。';
$CIDRAM['lang']['config_general_statistics'] = '跟踪CIDRAM使用情况统计？​True（真）=跟踪；False（假）=不跟踪【标准】。';
$CIDRAM['lang']['config_general_timeFormat'] = 'CIDRAM使用的日期符号格式。​可根据要求增加附加选项。';
$CIDRAM['lang']['config_general_timeOffset'] = '时区偏移量（分钟）。';
$CIDRAM['lang']['config_general_timezone'] = '您的时区。';
$CIDRAM['lang']['config_general_truncate'] = '截断日志文件当他们达到一定的大小吗？​值是在B/KB/MB/GB/TB，​是日志文件允许的最大大小直到它被截断。​默认值为“0KB”将禁用截断（日志文件可以无限成长）。​注意：适用于单个日志文件！​日志文件大小不被算集体的。';
$CIDRAM['lang']['config_legal_pseudonymise_ip_addresses'] = '编写日志文件时使用假名的IP地址吗？​True（真）=使用假名；False（假）=不使用假名【标准】。';
$CIDRAM['lang']['config_recaptcha_api'] = '使用哪个API？V2或Invisible？';
$CIDRAM['lang']['config_recaptcha_expiry'] = '记得reCAPTCHA多少小时？';
$CIDRAM['lang']['config_recaptcha_lockip'] = '应该reCAPTCHA锁定到IP？';
$CIDRAM['lang']['config_recaptcha_lockuser'] = '应该reCAPTCHA锁定到用户？';
$CIDRAM['lang']['config_recaptcha_logfile'] = '记录所有的reCAPTCHA的尝试？​要做到这一点，​指定一个文件名到使用。​如果不，​离开这个变量为空白。';
$CIDRAM['lang']['config_recaptcha_secret'] = '该值应该对应于“secret key”为您的reCAPTCHA，​该可以发现在reCAPTCHA的仪表板。';
$CIDRAM['lang']['config_recaptcha_signature_limit'] = '当提供reCAPTCHA实例时，允许触发最大签名数量。​标准 = 1。​如果这个数字超过了任何特定的请求，一个reCAPTCHA实例将不会被提供。';
$CIDRAM['lang']['config_recaptcha_sitekey'] = '该值应该对应于“site key”为您的reCAPTCHA，​该可以发现在reCAPTCHA的仪表板。';
$CIDRAM['lang']['config_recaptcha_usemode'] = '它定义了如何CIDRAM应该使用reCAPTCHA（请参阅文档）。';
$CIDRAM['lang']['config_signatures_block_bogons'] = '阻止bogon(“ㄅㄡㄍㄛㄋ”)/martian（“火星”）CIDR吗？​如果您希望连接到您的网站从您的本地网络/本地主机/localhost/LAN/等等，​这应该被设置为“false”（假）。​如果不，​这应该被设置为“true”（真）。';
$CIDRAM['lang']['config_signatures_block_cloud'] = '阻止CIDR认定为属于虚拟主机或云服务吗？​如果您操作一个API服务从您的网站或如果您预计其他网站连接到您的网站，​这应该被设置为“false”（假）。​如果不，​这应该被设置为“true”（真）。';
$CIDRAM['lang']['config_signatures_block_generic'] = '阻止CIDR一般建议对于黑名单吗？​这包括签名不标记为的一章节任何其他更具体签名类别。';
$CIDRAM['lang']['config_signatures_block_legal'] = '阻止CIDR因为法律义务吗？​这个指令通常不应该有任何作用，因为CIDRAM默认情况下不会将任何CIDR与“法律义务”相关联，​但它作为一个额外的控制措施存在，以利于任何可能因法律原因而存在的自定义签名文件或模块。';
$CIDRAM['lang']['config_signatures_block_malware'] = '阻止与恶意软件相关的IP？​这包括C＆C服务器，受感染的机器，涉及恶意软件分发的机器，等等。';
$CIDRAM['lang']['config_signatures_block_proxies'] = '阻止CIDR认定为属于代理服务或VPN吗？​如果您需要该用户可以访问您的网站从代理服务和VPN，​这应该被设置为“false”（假）。​除此以外，​如果您不需要代理服务或VPN，​这应该被设置为“true”（真）作为一个方式以提高安全性。';
$CIDRAM['lang']['config_signatures_block_spam'] = '阻止高风险垃圾邮件CIDR吗？​除非您遇到问题当这样做，​通常，​这应该被设置为“true”（真）。';
$CIDRAM['lang']['config_signatures_default_tracktime'] = '多少秒钟来跟踪模块禁止的IP。​标准 = 604800 （1周）。';
$CIDRAM['lang']['config_signatures_infraction_limit'] = '从IP最大允许违规数量之前它被禁止。​标准=10。';
$CIDRAM['lang']['config_signatures_ipv4'] = '列表的IPv4签名文件，​CIDRAM应该尝试使用，​用逗号分隔。';
$CIDRAM['lang']['config_signatures_ipv6'] = '列表的IPv6签名文件，​CIDRAM应该尝试使用，​用逗号分隔。';
$CIDRAM['lang']['config_signatures_modules'] = '模块文件要加载的列表以后检查签名IPv4/IPv6，​用逗号分隔。';
$CIDRAM['lang']['config_signatures_track_mode'] = '何时应该记录违规？​False（假）=当IP被模块阻止时。​True（真）=当IP由于任何原因阻止时。';
$CIDRAM['lang']['config_template_data_Magnification'] = '字体放大。​标准 = 1。';
$CIDRAM['lang']['config_template_data_css_url'] = '自定义主题的CSS文件URL。';
$CIDRAM['lang']['config_template_data_theme'] = '用于CIDRAM的默认主题。';
$CIDRAM['lang']['field_activate'] = '启用';
$CIDRAM['lang']['field_banned'] = '禁止';
$CIDRAM['lang']['field_blocked'] = '已阻止';
$CIDRAM['lang']['field_clear'] = '撤销';
$CIDRAM['lang']['field_clear_all'] = '撤销所有';
$CIDRAM['lang']['field_clickable_link'] = '可点击的链接';
$CIDRAM['lang']['field_component'] = '组件';
$CIDRAM['lang']['field_create_new_account'] = '创建新账户';
$CIDRAM['lang']['field_deactivate'] = '停用';
$CIDRAM['lang']['field_delete_account'] = '删除账户';
$CIDRAM['lang']['field_delete_file'] = '删除';
$CIDRAM['lang']['field_download_file'] = '下载';
$CIDRAM['lang']['field_edit_file'] = '编辑';
$CIDRAM['lang']['field_expiry'] = '到期';
$CIDRAM['lang']['field_false'] = 'False（假）';
$CIDRAM['lang']['field_file'] = '文件';
$CIDRAM['lang']['field_filename'] = '文件名：';
$CIDRAM['lang']['field_filetype_directory'] = '文件夹';
$CIDRAM['lang']['field_filetype_info'] = '{EXT}文件';
$CIDRAM['lang']['field_filetype_unknown'] = '未知';
$CIDRAM['lang']['field_infractions'] = '违规';
$CIDRAM['lang']['field_install'] = '安装';
$CIDRAM['lang']['field_ip_address'] = 'IP地址';
$CIDRAM['lang']['field_latest_version'] = '最新版本';
$CIDRAM['lang']['field_log_in'] = '登录';
$CIDRAM['lang']['field_new_name'] = '新名称：';
$CIDRAM['lang']['field_nonclickable_text'] = '不可点击的文字';
$CIDRAM['lang']['field_ok'] = 'OK';
$CIDRAM['lang']['field_options'] = '选项';
$CIDRAM['lang']['field_password'] = '密码';
$CIDRAM['lang']['field_permissions'] = '权限';
$CIDRAM['lang']['field_range'] = '范围 （初始 – 最后）';
$CIDRAM['lang']['field_rename_file'] = '改名';
$CIDRAM['lang']['field_reset'] = '重启';
$CIDRAM['lang']['field_set_new_password'] = '保存新密码';
$CIDRAM['lang']['field_size'] = '总大小：';
$CIDRAM['lang']['field_size_GB'] = 'GB';
$CIDRAM['lang']['field_size_KB'] = 'KB';
$CIDRAM['lang']['field_size_MB'] = 'MB';
$CIDRAM['lang']['field_size_TB'] = 'TB';
$CIDRAM['lang']['field_size_bytes'] = '字节';
$CIDRAM['lang']['field_status'] = '状态';
$CIDRAM['lang']['field_system_timezone'] = '使用系统默认时区。';
$CIDRAM['lang']['field_tracking'] = '跟踪';
$CIDRAM['lang']['field_true'] = 'True（真）';
$CIDRAM['lang']['field_uninstall'] = '卸载';
$CIDRAM['lang']['field_update'] = '更新';
$CIDRAM['lang']['field_update_all'] = '更新一切';
$CIDRAM['lang']['field_upload_file'] = '上传新文件';
$CIDRAM['lang']['field_username'] = '用户名';
$CIDRAM['lang']['field_verify'] = '验证';
$CIDRAM['lang']['field_verify_all'] = '验证全部';
$CIDRAM['lang']['field_your_version'] = '您的版本';
$CIDRAM['lang']['header_login'] = '请登录以继续。';
$CIDRAM['lang']['label_active_config_file'] = '活动配置文件：';
$CIDRAM['lang']['label_banned'] = '请求已禁止';
$CIDRAM['lang']['label_blocked'] = '请求已阻止';
$CIDRAM['lang']['label_branch'] = '分支最新稳定：';
$CIDRAM['lang']['label_check_modules'] = '也用模块进行测试。';
$CIDRAM['lang']['label_cidram'] = '目前使用CIDRAM版本：';
$CIDRAM['lang']['label_clientinfo'] = '客户信息：';
$CIDRAM['lang']['label_displaying'] = '显示<span class="txtRd">%1$s</span>个项目。';
$CIDRAM['lang']['label_displaying_that_cite'] = '显示<span class="txtRd">%1$s</span>个包含“%2$s”的项目。';
$CIDRAM['lang']['label_expires'] = '过期： ';
$CIDRAM['lang']['label_false_positive_risk'] = '假阳性风险：';
$CIDRAM['lang']['label_fmgr_cache_data'] = '缓存数据和临时文件';
$CIDRAM['lang']['label_fmgr_disk_usage'] = 'CIDRAM使用的磁盘空间： ';
$CIDRAM['lang']['label_fmgr_free_space'] = '可用磁盘空间： ';
$CIDRAM['lang']['label_fmgr_total_disk_usage'] = '总共使用的磁盘空间： ';
$CIDRAM['lang']['label_fmgr_total_space'] = '总磁盘空间： ';
$CIDRAM['lang']['label_fmgr_updates_metadata'] = '组件更新元数据';
$CIDRAM['lang']['label_hide'] = '隐藏';
$CIDRAM['lang']['label_never'] = '决不';
$CIDRAM['lang']['label_os'] = '目前使用操作系统：';
$CIDRAM['lang']['label_other'] = '其他';
$CIDRAM['lang']['label_other-ActiveIPv4'] = '活动IPv4签名文件';
$CIDRAM['lang']['label_other-ActiveIPv6'] = '活动IPv6签名文件';
$CIDRAM['lang']['label_other-ActiveModules'] = '活动模块';
$CIDRAM['lang']['label_other-Since'] = '开始日期';
$CIDRAM['lang']['label_php'] = '目前使用PHP版本：';
$CIDRAM['lang']['label_reCAPTCHA'] = 'reCAPTCHA尝试';
$CIDRAM['lang']['label_results'] = '结果 （%s 输入 – %s 拒绝 – %s 公认 – %s 合并 – %s 产量）：';
$CIDRAM['lang']['label_sapi'] = '目前使用SAPI：';
$CIDRAM['lang']['label_show'] = '显示';
$CIDRAM['lang']['label_signature_type'] = '签名类型：';
$CIDRAM['lang']['label_stable'] = '最新稳定：';
$CIDRAM['lang']['label_sysinfo'] = '系统信息：';
$CIDRAM['lang']['label_tests'] = '测试：';
$CIDRAM['lang']['label_total'] = '总';
$CIDRAM['lang']['label_unstable'] = '最新不稳定：';
$CIDRAM['lang']['label_used_with'] = '用于：';
$CIDRAM['lang']['label_your_ip'] = '您的IP：';
$CIDRAM['lang']['label_your_ua'] = '您的UA：';
$CIDRAM['lang']['link_accounts'] = '账户';
$CIDRAM['lang']['link_cache_data'] = '缓存数据';
$CIDRAM['lang']['link_cidr_calc'] = 'CIDR计算器';
$CIDRAM['lang']['link_config'] = '配置';
$CIDRAM['lang']['link_documentation'] = '文档';
$CIDRAM['lang']['link_file_manager'] = '文件管理器';
$CIDRAM['lang']['link_home'] = '主页';
$CIDRAM['lang']['link_ip_aggregator'] = 'IP聚合器';
$CIDRAM['lang']['link_ip_test'] = 'IP测试';
$CIDRAM['lang']['link_ip_tracking'] = 'IP跟踪';
$CIDRAM['lang']['link_logs'] = '日志';
$CIDRAM['lang']['link_range'] = '范围表';
$CIDRAM['lang']['link_sections_list'] = '章节列表';
$CIDRAM['lang']['link_statistics'] = '统计';
$CIDRAM['lang']['link_textmode'] = '文字格式： <a href="%1$sfalse%2$s">简单</a> – <a href="%1$strue%2$s">漂亮</a> – <a href="%1$stally%2$s">理货</a>';
$CIDRAM['lang']['link_updates'] = '更新';
$CIDRAM['lang']['logs_logfile_doesnt_exist'] = '选择的日志不存在！';
$CIDRAM['lang']['logs_no_logfile_selected'] = '没有选择的日志。';
$CIDRAM['lang']['logs_no_logfiles_available'] = '没有日志可用。';
$CIDRAM['lang']['max_login_attempts_exceeded'] = '最大登录尝试次数已经超过；拒绝访问。';
$CIDRAM['lang']['previewer_days'] = '天';
$CIDRAM['lang']['previewer_hours'] = '小时';
$CIDRAM['lang']['previewer_minutes'] = '分';
$CIDRAM['lang']['previewer_months'] = '月';
$CIDRAM['lang']['previewer_seconds'] = '秒';
$CIDRAM['lang']['previewer_weeks'] = '周';
$CIDRAM['lang']['previewer_years'] = '年';
$CIDRAM['lang']['response_accounts_already_exists'] = '一个账户与那个用户名已经存在！';
$CIDRAM['lang']['response_accounts_created'] = '账户成功创建！';
$CIDRAM['lang']['response_accounts_deleted'] = '账户成功删除！';
$CIDRAM['lang']['response_accounts_doesnt_exist'] = '那个账户不存在。';
$CIDRAM['lang']['response_accounts_password_updated'] = '密码成功更新！';
$CIDRAM['lang']['response_activated'] = '已成功启用。';
$CIDRAM['lang']['response_activation_failed'] = '无法启用！';
$CIDRAM['lang']['response_checksum_error'] = '校验和错误！​文件拒绝！';
$CIDRAM['lang']['response_component_successfully_installed'] = '组件成功安装。';
$CIDRAM['lang']['response_component_successfully_uninstalled'] = '组件成功卸载。';
$CIDRAM['lang']['response_component_successfully_updated'] = '组件成功更新。';
$CIDRAM['lang']['response_component_uninstall_error'] = '一个错误发生当尝试卸载组件。';
$CIDRAM['lang']['response_configuration_updated'] = '配置成功更新。';
$CIDRAM['lang']['response_deactivated'] = '已成功停用。';
$CIDRAM['lang']['response_deactivation_failed'] = '无法停用！';
$CIDRAM['lang']['response_delete_error'] = '无法删除！';
$CIDRAM['lang']['response_directory_deleted'] = '文件夹成功删除！';
$CIDRAM['lang']['response_directory_renamed'] = '文件夹成功改名！';
$CIDRAM['lang']['response_error'] = '错误';
$CIDRAM['lang']['response_failed_to_install'] = '无法安装！';
$CIDRAM['lang']['response_failed_to_update'] = '无法更新！';
$CIDRAM['lang']['response_file_deleted'] = '文件成功删除！';
$CIDRAM['lang']['response_file_edited'] = '文件成功改性！';
$CIDRAM['lang']['response_file_renamed'] = '文件成功改名！';
$CIDRAM['lang']['response_file_uploaded'] = '文件成功上传！';
$CIDRAM['lang']['response_login_invalid_password'] = '登录失败！​密码无效！';
$CIDRAM['lang']['response_login_invalid_username'] = '登录失败！​用户名不存在！';
$CIDRAM['lang']['response_login_password_field_empty'] = '密码输入是空的！';
$CIDRAM['lang']['response_login_username_field_empty'] = '用户名输入是空的！';
$CIDRAM['lang']['response_login_wrong_endpoint'] = '错误的端点！';
$CIDRAM['lang']['response_no'] = '不是';
$CIDRAM['lang']['response_possible_problem_found'] = '可能的问题发现。';
$CIDRAM['lang']['response_rename_error'] = '无法改名！';
$CIDRAM['lang']['response_statistics_cleared'] = '统计删除。';
$CIDRAM['lang']['response_tracking_cleared'] = '已撤消跟踪。';
$CIDRAM['lang']['response_updates_already_up_to_date'] = '已经更新。';
$CIDRAM['lang']['response_updates_not_installed'] = '组件不安装！';
$CIDRAM['lang']['response_updates_not_installed_php'] = '组件不安装（它需要PHP &gt;= {V}）！';
$CIDRAM['lang']['response_updates_outdated'] = '过时！';
$CIDRAM['lang']['response_updates_outdated_manually'] = '过时（请更新手动）！';
$CIDRAM['lang']['response_updates_outdated_php_version'] = '过时（它需要PHP &gt;= {V}）！';
$CIDRAM['lang']['response_updates_unable_to_determine'] = '无法确定。';
$CIDRAM['lang']['response_upload_error'] = '无法上传！';
$CIDRAM['lang']['response_verification_failed'] = '验证失败！组件可能已损坏。';
$CIDRAM['lang']['response_verification_success'] = '验证成功！没有发现问题。';
$CIDRAM['lang']['response_yes'] = '是';
$CIDRAM['lang']['state_async_deny'] = '权限不足以执行异步请求。尝试再次登录。';
$CIDRAM['lang']['state_cache_is_empty'] = '缓存是空的。';
$CIDRAM['lang']['state_complete_access'] = '完全访问';
$CIDRAM['lang']['state_component_is_active'] = '组件是活性。';
$CIDRAM['lang']['state_component_is_inactive'] = '组件是非活性。';
$CIDRAM['lang']['state_component_is_provisional'] = '组件是有时活性。';
$CIDRAM['lang']['state_default_password'] = '警告：它使用标准密码！';
$CIDRAM['lang']['state_ignored'] = '忽略了';
$CIDRAM['lang']['state_loading'] = '载入中...';
$CIDRAM['lang']['state_loadtime'] = '页面请求在<span class="txtRd">%s</span>秒内完成。';
$CIDRAM['lang']['state_logged_in'] = '目前在线。';
$CIDRAM['lang']['state_logs_access_only'] = '仅日志访问';
$CIDRAM['lang']['state_maintenance_mode'] = '警告：维护模式是启用！';
$CIDRAM['lang']['state_password_not_valid'] = '警告：此账户不​使用有效的密码！';
$CIDRAM['lang']['state_risk_high'] = '高';
$CIDRAM['lang']['state_risk_low'] = '低';
$CIDRAM['lang']['state_risk_medium'] = '中等';
$CIDRAM['lang']['state_sl_totals'] = '汇总（签名： <span class="txtRd">%s</span> – 签名章节： <span class="txtRd">%s</span> – 签名文件： <span class="txtRd">%s</span>）。';
$CIDRAM['lang']['state_tracking'] = '目前正在跟踪%s个IP。';
$CIDRAM['lang']['switch-hide-non-outdated-set-false'] = '不要隐藏非过时';
$CIDRAM['lang']['switch-hide-non-outdated-set-true'] = '隐藏非过时';
$CIDRAM['lang']['switch-hide-unused-set-false'] = '不要隐藏非用过';
$CIDRAM['lang']['switch-hide-unused-set-true'] = '隐藏非用过';
$CIDRAM['lang']['switch-tracking-blocked-already-set-false'] = '不要检查签名文件';
$CIDRAM['lang']['switch-tracking-blocked-already-set-true'] = '检查签名文件';
$CIDRAM['lang']['switch-tracking-hide-banned-blocked-set-false'] = '不要隐藏被禁止/阻止的IP';
$CIDRAM['lang']['switch-tracking-hide-banned-blocked-set-true'] = '隐藏被禁止/阻止的IP';
$CIDRAM['lang']['tip_accounts'] = '你好，​{username}。​<br />账户页面允许您控制谁可以访问CIDRAM前端。';
$CIDRAM['lang']['tip_cache_data'] = '你好，​{username}。​<br />在这里您可以查看缓存的内容。';
$CIDRAM['lang']['tip_cidr_calc'] = '你好，​{username}。​<br />CIDR计算器允许您计算IP地址属于哪个CIDR。';
$CIDRAM['lang']['tip_config'] = '你好，​{username}。​<br />配置页面允许您修改CIDRAM配置从前端。';
$CIDRAM['lang']['tip_custom_ua'] = '在这里输入用户代理【user agent】（可选的）。';
$CIDRAM['lang']['tip_donate'] = 'CIDRAM是免费提供的，​但如果您想捐赠给项目，​您可以通过点击捐赠按钮这样做。';
$CIDRAM['lang']['tip_enter_ip_here'] = '在这里输入IP。';
$CIDRAM['lang']['tip_enter_ips_here'] = '在这里输入IP。';
$CIDRAM['lang']['tip_fe_cookie_warning'] = '注意：CIDRAM使用cookie来验证登录。​通过登录，您同意您的浏览器创建和存储cookie。';
$CIDRAM['lang']['tip_file_manager'] = '你好，​{username}。​<br />文件管理器允许您删除，​编辑，​上传和下载文件。​小心使用（您可以用这个破坏您的安装）。';
$CIDRAM['lang']['tip_home'] = '你好，​{username}。​<br />这是CIDRAM的前端主页。​从左侧的导航菜单中选择一个链接以继续。';
$CIDRAM['lang']['tip_ip_aggregator'] = '你好，​{username}。​<br />IP聚合器允许您以最小的可能方式表达IP和CIDR。​输入要聚合的数据，然后按“OK”。';
$CIDRAM['lang']['tip_ip_test'] = '你好，​{username}。​<br />IP测试页面允许您测试是否IP地址被阻止通过当前安装的签名。';
$CIDRAM['lang']['tip_ip_test_module_switch'] = '（如果未选中的，只有签名文件将被测试）。';
$CIDRAM['lang']['tip_ip_tracking'] = '你好，​{username}。​<br />IP跟踪页面允许您检查IP地址跟踪状态，​检查哪些IP已被禁止，​而如果您想这样做，​对撤消他们的跟踪。';
$CIDRAM['lang']['tip_login'] = '标准用户名：<span class="txtRd">admin</span> – 标准密码：<span class="txtRd">password</span>';
$CIDRAM['lang']['tip_logs'] = '你好，​{username}。​<br />选择一个日志从下面的列表以查看那个日志的内容。';
$CIDRAM['lang']['tip_range'] = '你好，​{username}。<br />本页面显示了有关当前活性签名文件覆盖的IP范围的一些基本统计信息。';
$CIDRAM['lang']['tip_sections_list'] = '你好，​{username}。<br />此页面列出当前活动签名文件中存在哪些章节。';
$CIDRAM['lang']['tip_see_the_documentation'] = '请参阅<a href="https://github.com/CIDRAM/CIDRAM/blob/master/_docs/readme.zh.md#SECTION6">文档</a>以获取有关各种配置指令的信息和他们的目的。';
$CIDRAM['lang']['tip_statistics'] = '你好，​{username}。​<br />此页面显示了有关CIDRAM安装的一些基本使用统计信息。';
$CIDRAM['lang']['tip_statistics_disabled'] = '注意：统计跟踪目前已被禁用，但可以通过配置页面启用。';
$CIDRAM['lang']['tip_updates'] = '你好，​{username}。​<br />更新页面允许您安装，​卸载，​和更新CIDRAM的各种组件（核心包，​签名，​L10N文件，​等等）。';
$CIDRAM['lang']['title_accounts'] = 'CIDRAM – 账户';
$CIDRAM['lang']['title_cache_data'] = 'CIDRAM – 缓存数据';
$CIDRAM['lang']['title_cidr_calc'] = 'CIDRAM – CIDR计算器';
$CIDRAM['lang']['title_config'] = 'CIDRAM – 配置';
$CIDRAM['lang']['title_file_manager'] = 'CIDRAM – 文件管理器';
$CIDRAM['lang']['title_home'] = 'CIDRAM – 主页';
$CIDRAM['lang']['title_ip_aggregator'] = 'CIDRAM – IP聚合器';
$CIDRAM['lang']['title_ip_test'] = 'CIDRAM – IP测试';
$CIDRAM['lang']['title_ip_tracking'] = 'CIDRAM – IP跟踪';
$CIDRAM['lang']['title_login'] = 'CIDRAM – 登录';
$CIDRAM['lang']['title_logs'] = 'CIDRAM – 日志';
$CIDRAM['lang']['title_range'] = 'CIDRAM – 范围表';
$CIDRAM['lang']['title_sections_list'] = 'CIDRAM – 章节列表';
$CIDRAM['lang']['title_statistics'] = 'CIDRAM – 统计';
$CIDRAM['lang']['title_updates'] = 'CIDRAM – 更新';
$CIDRAM['lang']['warning'] = '警告：';
$CIDRAM['lang']['warning_php_1'] = '您的PHP版本不再被积极支持！​推荐更新！';
$CIDRAM['lang']['warning_php_2'] = '您的PHP版本非常脆弱！​强烈推荐更新！';
$CIDRAM['lang']['warning_signatures_1'] = '没有签名文件是活动的！';

$CIDRAM['lang']['info_some_useful_links'] = '一些有用的链接：<ul>
            <li><a href="https://github.com/CIDRAM/CIDRAM/issues">CIDRAM问题＠GitHub</a> – CIDRAM问题页面（支持，​协助，​等等）。​</li>
            <li><a href="https://wordpress.org/plugins/cidram/">CIDRAM＠WordPress.org</a> – CIDRAM WordPress插件。​</li>
            <li><a href="https://www.oschina.net/p/CIDRAM">CIDRAM＠开源中国社区</a> – CIDRAM页面托管在开源中国社区。​</li>
            <li><a href="https://sourceforge.net/projects/cidram/">CIDRAM＠SourceForge</a> – CIDRAM替代下载镜像。​</li>
            <li><a href="https://websectools.com/">WebSecTools.com</a> – 简单网站管理员工具集合为保护网站。​</li>
            <li><a href="https://macmathan.info/blocklists">范围阻止名单＠MacMathan.info</a> – 包含可选阻止名单，​可以添加的在CIDRAM，​用于阻止任何不需要的国家访问您的网站。​</li>
            <li><a href="https://www.facebook.com/groups/2204685680/">Global PHP Group ＠ Facebook</a> – PHP学习资源和讨论。​</li>
            <li><a href="https://php.earth/">PHP.earth</a> – PHP学习资源和讨论。​</li>
            <li><a href="http://bgp.he.net/">Hurricane Electric BGP Toolkit</a> – 从ASN获取CIDR，​确定ASN关系，​基于网络名称发现ASN，​等等。​</li>
            <li><a href="https://www.stopforumspam.com/forum/">论坛 ＠ Stop Forum Spam</a> – 有用的讨论论坛关于停止论坛垃圾邮件。​</li>
            <li><a href="https://radar.qrator.net/">Qrator的Radar</a> – 检查ASN连接的有用工具，​以及关于ASN的各种其他信息。​</li>
            <li><a href="http://www.ipdeny.com/ipblocks/">IP国家阻止＠IPdeny</a> – 一个梦幻般和准确的服务，​产生国家的签名。​</li>
            <li><a href="https://www.google.com/transparencyreport/safebrowsing/malware/">Google Malware Dashboard</a> – 显示有关ASN恶意软件感染率的报告。​</li>
            <li><a href="https://www.spamhaus.org/statistics/botnet-asn/">Spamhaus项目</a> – 显示有关ASN僵尸网络感染率的报告。​</li>
            <li><a href="https://www.abuseat.org/public/asn.html">Abuseat.org的复合阻止列表</a> – 显示有关ASN僵尸网络感染率的报告。​</li>
            <li><a href="https://abuseipdb.com/">AbuseIPDB</a> – 维护已知的滥用IP数据库；它为IP检查和报告提供了一个API。​</li>
            <li><a href="https://www.megarbl.net/index.php">MegaRBL.net</a> – 维护已知垃圾邮件发送者的列表；有用为检查IP/ASN垃圾邮件活动。​</li>
            <li><a href="https://maikuolan.github.io/Vulnerability-Charts/">脆弱性图表</a> – 列出各种软件包的安全/不安全版本（HHVM，PHP，phpMyAdmin，Python等等）。</li>
            <li><a href="https://maikuolan.github.io/Compatibility-Charts/">兼容性图表</a> – 列出各种软件包的兼容性信息（CIDRAM，phpMussel，等等）。</li>
        </ul>';
