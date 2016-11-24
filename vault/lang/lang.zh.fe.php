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
 * This file: Chinese (simplified) language data for the front-end (last modified: 2016.11.25).
 */

/** Prevents execution from outside of CIDRAM. */
if (!defined('CIDRAM')) {
    die('[CIDRAM] This should not be accessed directly.');
}

$CIDRAM['lang']['bNav_home_logout'] = '<a href="?">主页</a> | <a href="?cidram-page=logout">登出</a>';
$CIDRAM['lang']['bNav_logout'] = '<a href="?cidram-page=logout">登出</a>';
$CIDRAM['lang']['config_general_disable_cli'] = '关闭CLI模式吗？';
$CIDRAM['lang']['config_general_disable_frontend'] = '关闭前端访问吗？';
$CIDRAM['lang']['config_general_emailaddr'] = '支持/援助电子邮件地址。';
$CIDRAM['lang']['config_general_forbid_on_block'] = '什么头CIDRAM应该应对当申请是拒绝？';
$CIDRAM['lang']['config_general_ipaddr'] = '在哪里可以找到连接请求IP地址？';
$CIDRAM['lang']['config_general_lang'] = '指定标准CIDRAM语言。';
$CIDRAM['lang']['config_general_logfile'] = '人类可读文件用于记录所有被拦截的访问。指定一个文件名，或留空以禁用。';
$CIDRAM['lang']['config_general_logfileApache'] = 'Apache风格文件用于记录所有被拦截的访问。指定一个文件名，或留空以禁用。';
$CIDRAM['lang']['config_general_logfileSerialized'] = '连载的文件用于记录所有被拦截的访问。指定一个文件名，或留空以禁用。';
$CIDRAM['lang']['config_general_silent_mode'] = 'CIDRAM应该默默重定向被拦截的访问而不是显示该“拒绝访问”页吗？指定位置至重定向被拦截的访问，或让它空将其禁用。';
$CIDRAM['lang']['config_general_timeOffset'] = '时区偏移量（分钟）。';
$CIDRAM['lang']['config_recaptcha_expiry'] = '记得reCAPTCHA多少小时？';
$CIDRAM['lang']['config_recaptcha_lockip'] = '应该reCAPTCHA锁定到IP？';
$CIDRAM['lang']['config_recaptcha_lockuser'] = '应该reCAPTCHA锁定到用户？';
$CIDRAM['lang']['config_recaptcha_logfile'] = '记录所有的reCAPTCHA的尝试？要做到这一点，指定一个文件名到使用。如果不，离开这个变量为空白。';
$CIDRAM['lang']['config_recaptcha_secret'] = '该值应该对应于“secret key”为您的reCAPTCHA，该可以发现在reCAPTCHA的仪表板。';
$CIDRAM['lang']['config_recaptcha_sitekey'] = '该值应该对应于“site key”为您的reCAPTCHA，该可以发现在reCAPTCHA的仪表板。';
$CIDRAM['lang']['config_recaptcha_usemode'] = '它定义了如何CIDRAM应该使用reCAPTCHA（请参阅文档）。';
$CIDRAM['lang']['config_signatures_block_bogons'] = '阻止bogon(“ㄅㄡㄍㄛㄋ”)/martian（“火星”）CIDR吗？如果您希望连接到您的网站从您的本地网络/本地主机/localhost/LAN/等等，这应该被设置为“false”（假）。如果不，这应该被设置为“true”（真）。';
$CIDRAM['lang']['config_signatures_block_cloud'] = '阻止CIDR认定为属于虚拟主机或云服务吗？如果您操作一个API服务从您的网站或如果您预计其他网站连接到您的网站，这应该被设置为“false”（假）。如果不，这应该被设置为“true”（真）。';
$CIDRAM['lang']['config_signatures_block_generic'] = '阻止CIDR一般建议对于黑名单吗？这包括签名不标记为的一章节任何其他更具体签名类别。';
$CIDRAM['lang']['config_signatures_block_proxies'] = '阻止CIDR认定为属于代理服务吗？如果您需要该用户可以访问您的网站从匿名代理服务，这应该被设置为“false”（假）。除此以外，如果您不需要匿名代理服务，这应该被设置为“true”（真）作为一个方式以提高安全性。';
$CIDRAM['lang']['config_signatures_block_spam'] = '阻止高风险垃圾邮件CIDR吗？除非您遇到问题当这样做，通常，这应该被设置为“true”（真）。';
$CIDRAM['lang']['config_signatures_ipv4'] = '列表的IPv4签名文件，CIDRAM应该尝试使用，用逗号分隔。';
$CIDRAM['lang']['config_signatures_ipv6'] = '列表的IPv6签名文件，CIDRAM应该尝试使用，用逗号分隔。';
$CIDRAM['lang']['config_template_data_css_url'] = '自定义主题的CSS文件URL。';
$CIDRAM['lang']['field_blocked'] = '已阻止';
$CIDRAM['lang']['field_component'] = '组件';
$CIDRAM['lang']['field_create_new_account'] = '创建新账户';
$CIDRAM['lang']['field_delete_account'] = '删除账户';
$CIDRAM['lang']['field_delete_file'] = '删除';
$CIDRAM['lang']['field_download_file'] = '下载';
$CIDRAM['lang']['field_edit_file'] = '编辑';
$CIDRAM['lang']['field_file'] = '文件';
$CIDRAM['lang']['field_filename'] = '文件名：';
$CIDRAM['lang']['field_filetype_directory'] = '目录';
$CIDRAM['lang']['field_filetype_info'] = '{EXT}文件';
$CIDRAM['lang']['field_filetype_unknown'] = '未知';
$CIDRAM['lang']['field_install'] = '安装';
$CIDRAM['lang']['field_ip_address'] = 'IP地址';
$CIDRAM['lang']['field_latest_version'] = '最新版本';
$CIDRAM['lang']['field_log_in'] = '登录';
$CIDRAM['lang']['field_ok'] = 'OK';
$CIDRAM['lang']['field_options'] = '选项';
$CIDRAM['lang']['field_password'] = '密码';
$CIDRAM['lang']['field_permissions'] = '权限';
$CIDRAM['lang']['field_reset'] = '重启';
$CIDRAM['lang']['field_set_new_password'] = '保存新密码';
$CIDRAM['lang']['field_size'] = '总大小：';
$CIDRAM['lang']['field_size_bytes'] = '字节';
$CIDRAM['lang']['field_size_GB'] = 'GB';
$CIDRAM['lang']['field_size_KB'] = 'KB';
$CIDRAM['lang']['field_size_MB'] = 'MB';
$CIDRAM['lang']['field_size_TB'] = 'TB';
$CIDRAM['lang']['field_status'] = '状态';
$CIDRAM['lang']['field_uninstall'] = '卸载';
$CIDRAM['lang']['field_update'] = '更新';
$CIDRAM['lang']['field_upload_file'] = '上传新文件';
$CIDRAM['lang']['field_username'] = '用户名';
$CIDRAM['lang']['field_your_version'] = '您的版本';
$CIDRAM['lang']['header_login'] = '请登录以继续。';
$CIDRAM['lang']['link_accounts'] = '账户';
$CIDRAM['lang']['link_config'] = '配置';
$CIDRAM['lang']['link_documentation'] = '文档';
$CIDRAM['lang']['link_file_manager'] = '文件管理器';
$CIDRAM['lang']['link_home'] = '主页';
$CIDRAM['lang']['link_ip_test'] = 'IP测试';
$CIDRAM['lang']['link_logs'] = '日志';
$CIDRAM['lang']['link_updates'] = '更新';
$CIDRAM['lang']['logs_logfile_doesnt_exist'] = '选择的日志不存在！';
$CIDRAM['lang']['logs_no_logfiles_available'] = '没有日志可用。';
$CIDRAM['lang']['logs_no_logfile_selected'] = '没有选择的日志。';
$CIDRAM['lang']['response_accounts_already_exists'] = '一个账户与那个用户名已经存在！';
$CIDRAM['lang']['response_accounts_created'] = '账户成功创建！';
$CIDRAM['lang']['response_accounts_deleted'] = '账户成功删除！';
$CIDRAM['lang']['response_accounts_doesnt_exist'] = '那个账户不存在。';
$CIDRAM['lang']['response_accounts_password_updated'] = '密码成功更新！';
$CIDRAM['lang']['response_component_successfully_installed'] = '组件成功安装。';
$CIDRAM['lang']['response_component_successfully_uninstalled'] = '组件成功卸载。';
$CIDRAM['lang']['response_component_successfully_updated'] = '组件成功更新。';
$CIDRAM['lang']['response_component_uninstall_error'] = '一个错误发生当尝试卸载组件。';
$CIDRAM['lang']['response_component_update_error'] = '一个错误发生当尝试更新组件。';
$CIDRAM['lang']['response_configuration_updated'] = '配置成功更新。';
$CIDRAM['lang']['response_error'] = '错误';
$CIDRAM['lang']['response_file_deleted'] = '文件成功删除！';
$CIDRAM['lang']['response_file_edited'] = '文件成功改性！';
$CIDRAM['lang']['response_file_uploaded'] = '文件成功上传！';
$CIDRAM['lang']['response_login_invalid_password'] = '登录失败！密码无效！';
$CIDRAM['lang']['response_login_invalid_username'] = '登录失败！用户名不存在！';
$CIDRAM['lang']['response_login_password_field_empty'] = '密码输入是空的！';
$CIDRAM['lang']['response_login_username_field_empty'] = '用户名输入是空的！';
$CIDRAM['lang']['response_no'] = '不是';
$CIDRAM['lang']['response_updates_already_up_to_date'] = '已经更新。';
$CIDRAM['lang']['response_updates_not_installed'] = '组件不安装！';
$CIDRAM['lang']['response_updates_outdated'] = '过时！';
$CIDRAM['lang']['response_updates_outdated_manually'] = '过时（请更新手动）！';
$CIDRAM['lang']['response_updates_unable_to_determine'] = '无法确定。';
$CIDRAM['lang']['response_yes'] = '是';
$CIDRAM['lang']['state_complete_access'] = '完全访问';
$CIDRAM['lang']['state_component_is_active'] = '组件是活性。';
$CIDRAM['lang']['state_component_is_inactive'] = '组件是非活性。';
$CIDRAM['lang']['state_component_is_provisional'] = '组件是有时活性。';
$CIDRAM['lang']['state_default_password'] = '警告：它使用标准密码！';
$CIDRAM['lang']['state_logged_in'] = '目前在线';
$CIDRAM['lang']['state_logs_access_only'] = '仅日志访问';
$CIDRAM['lang']['state_password_not_valid'] = '警告：此账户不​​使用有效的密码！';
$CIDRAM['lang']['switch-hide-non-outdated-set-false'] = '不要隐藏非过时';
$CIDRAM['lang']['switch-hide-non-outdated-set-true'] = '隐藏非过时';
$CIDRAM['lang']['switch-hide-unused-set-false'] = '不要隐藏非用过';
$CIDRAM['lang']['switch-hide-unused-set-true'] = '隐藏非用过';
$CIDRAM['lang']['tip_accounts'] = '你好，{username}。<br />账户页面允许您控制谁可以访问CIDRAM前端。';
$CIDRAM['lang']['tip_config'] = '你好，{username}。<br />配置页面允许您修改CIDRAM配置从前端。';
$CIDRAM['lang']['tip_donate'] = 'CIDRAM是免费提供的，但如果您想捐赠给项目，您可以通过点击捐赠按钮这样做。';
$CIDRAM['lang']['tip_enter_ips_here'] = '在这里输入IP。';
$CIDRAM['lang']['tip_file_manager'] = '你好，{username}。<br />文件管理器允许您删除，编辑，上传和下载文件。小心使用（您可以用这个破坏您的安装）。';
$CIDRAM['lang']['tip_home'] = '你好，{username}。<br />这是CIDRAM的前端主页。从左侧的导航菜单中选择一个链接以继续。';
$CIDRAM['lang']['tip_ip_test'] = '你好，{username}。<br />IP测试页面允许您测试是否IP地址被阻止通过当前安装的签名。';
$CIDRAM['lang']['tip_login'] = '标准用户名： <span class="txtRd">admin</span> – 标准密码： <span class="txtRd">password</span>';
$CIDRAM['lang']['tip_logs'] = '你好，{username}。<br />选择一个日志从下面的列表以查看那个日志的内容。';
$CIDRAM['lang']['tip_see_the_documentation'] = '请参阅<a href="https://github.com/Maikuolan/CIDRAM/blob/master/_docs/readme.zh.md#SECTION6">文档</a>以获取有关各种配置指令的信息和他们的目的。';
$CIDRAM['lang']['tip_updates'] = '你好，{username}。<br />更新页面允许您安装，卸载，和更新CIDRAM的各种组件（核心包，签名，L10N文件，等等）。';
$CIDRAM['lang']['title_accounts'] = 'CIDRAM – 账户';
$CIDRAM['lang']['title_config'] = 'CIDRAM – 配置';
$CIDRAM['lang']['title_file_manager'] = 'CIDRAM – 文件管理器';
$CIDRAM['lang']['title_home'] = 'CIDRAM – 主页';
$CIDRAM['lang']['title_ip_test'] = 'CIDRAM – IP测试';
$CIDRAM['lang']['title_login'] = 'CIDRAM – 登录';
$CIDRAM['lang']['title_logs'] = 'CIDRAM – 日志';
$CIDRAM['lang']['title_updates'] = 'CIDRAM – 更新';

$CIDRAM['lang']['info_some_useful_links'] = '一些有用的链接：<ul>
            <li><a href="https://github.com/Maikuolan/CIDRAM/issues">CIDRAM Issues @ GitHub</a> – CIDRAM问题页面（支持，协助，等等）。</li>
            <li><a href="http://www.spambotsecurity.com/forum/viewforum.php?f=61">CIDRAM @ Spambot Security</a> – CIDRAM讨论论坛（支持，协助，等等）。</li>
            <li><a href="https://wordpress.org/plugins/cidram/">CIDRAM @ Wordpress.org</a> – CIDRAM Wordpress插件。</li>
            <li><a href="https://www.oschina.net/p/CIDRAM">CIDRAM＠开源中国社区</a> – CIDRAM页面托管在开源中国社区。</li>
            <li><a href="https://sourceforge.net/projects/cidram/">CIDRAM @ SourceForge</a> – CIDRAM替代下载镜像。</li>
            <li><a href="https://websectools.com/">WebSecTools.com</a> – 简单网站管理员工具集合为保护网站。</li>
            <li><a href="https://macmathan.info/zbblock-range-blocks">MacMathan.info Range Blocks</a> – 包含可选阻止名单，可以添加的在CIDRAM，用于阻止任何不需要的国家访问您的网站。</li>
            <li><a href="https://www.facebook.com/groups/2204685680/">International PHP Group @ Facebook</a> – PHP学习资源和讨论。</li>
            <li><a href="https://wwphp-fb.github.io/">International PHP Group @ GitHub</a> – PHP学习资源和讨论。</li>
            <li><a href="http://bgp.he.net/">Hurricane Electric BGP Toolkit</a> – 从ASN获取CIDR，确定ASN关系，基于网络名称发现ASN，等等。</li>
            <li><a href="https://www.stopforumspam.com/forum/">Forum @ Stop Forum Spam</a> – 有用的讨论论坛关于停止论坛垃圾邮件。</li>
            <li><a href="https://www.stopforumspam.com/aggregate">IP Aggregator @ Stop Forum Spam</a> – 有用的IPv4聚合工具。</li>
            <li><a href="https://radar.qrator.net/">Radar by Qrator</a> – 检查ASN连接的有用工具，以及关于ASN的各种其他信息。</li>
            <li><a href="http://www.ipdeny.com/ipblocks/">IPdeny IP country blocks</a> – 一个梦幻般和准确的服务，产生国家的签名。</li>
            <li><a href="https://www.google.com/transparencyreport/safebrowsing/malware/">Google Malware Dashboard</a> – 显示有关ASN恶意软件感染率的报告。</li>
            <li><a href="https://www.spamhaus.org/statistics/botnet-asn/">The Spamhaus Project</a> – 显示有关ASN僵尸网络感染率的报告。</li>
            <li><a href="http://www.abuseat.org/asn.html">Abuseat.org\'s Composite Blocking List</a> – 显示有关ASN僵尸网络感染率的报告。</li>
        </ul>';
