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
 * This file: English language data for the front-end (last modified: 2018.06.09).
 */

/** Prevents execution from outside of CIDRAM. */
if (!defined('CIDRAM')) {
    die('[CIDRAM] This should not be accessed directly.');
}

foreach (['IPv4', 'IPv6'] as $CIDRAM['IPvX']) {
    $CIDRAM['Pre'] = 'Default ' . $CIDRAM['IPvX'] . ' signatures normally included with the main package. ';
    $CIDRAM['lang']['Extended Description: ' . $CIDRAM['IPvX']] = $CIDRAM['Pre'] . 'Blocks unwanted cloud services and non-human endpoints.';
    $CIDRAM['lang']['Extended Description: ' . $CIDRAM['IPvX'] . '-Bogons'] = $CIDRAM['Pre'] . 'Blocks bogon/martian CIDRs.';
    $CIDRAM['lang']['Extended Description: ' . $CIDRAM['IPvX'] . '-ISPs'] = $CIDRAM['Pre'] . 'Blocks dangerous and spammy ISPs.';
    $CIDRAM['lang']['Extended Description: ' . $CIDRAM['IPvX'] . '-Other'] = $CIDRAM['Pre'] . 'Blocks CIDRs for proxies, VPNs, and other miscellaneous unwanted services.';
    $CIDRAM['Pre'] = $CIDRAM['IPvX'] . ' signatures file (%s).';
    $CIDRAM['lang']['Name: ' . $CIDRAM['IPvX']] = sprintf($CIDRAM['Pre'], 'unwanted cloud services and non-human endpoints');
    $CIDRAM['lang']['Name: ' . $CIDRAM['IPvX'] . '-Bogons'] = sprintf($CIDRAM['Pre'], 'bogon/martian CIDRs');
    $CIDRAM['lang']['Name: ' . $CIDRAM['IPvX'] . '-ISPs'] = sprintf($CIDRAM['Pre'], 'dangerous and spammy ISPs');
    $CIDRAM['lang']['Name: ' . $CIDRAM['IPvX'] . '-Other'] = sprintf($CIDRAM['Pre'], 'CIDRs for proxies, VPNs, and other miscellaneous unwanted services');
}
unset($CIDRAM['Pre'], $CIDRAM['IPvX']);

$CIDRAM['lang']['Extended Description: Bypasses'] = 'The default signature bypass files normally included with the main package.';
$CIDRAM['lang']['Extended Description: CIDRAM'] = 'The main package (minus the signatures, documentation, and configuration).';
$CIDRAM['lang']['Extended Description: module_badhosts.php'] = 'Blocks hosts frequently used by spammers, hackers, and other nefarious entities.';
$CIDRAM['lang']['Extended Description: module_badhosts_isps.php'] = 'Blocks hosts belonging to ISPs frequently used by spammers, hackers, and other nefarious entities.';
$CIDRAM['lang']['Extended Description: module_badtlds.php'] = 'Blocks hosts belonging to TLDs frequently used by spammers, hackers, and other nefarious entities.';
$CIDRAM['lang']['Extended Description: module_cookies.php'] = 'Provides some limited protections against dangerous cookies.';
$CIDRAM['lang']['Extended Description: module_extras.php'] = 'Provides some limited protections against various attack vectors commonly used in requests.';
$CIDRAM['lang']['Extended Description: module_sfs.php'] = 'Protects registration and login pages against IPs listed by SFS.';
$CIDRAM['lang']['Name: Bypasses'] = 'Default signature bypasses.';
$CIDRAM['lang']['Name: module_badhosts.php'] = 'Bad hosts blocker module';
$CIDRAM['lang']['Name: module_badhosts_isps.php'] = 'Bad hosts blocker module (ISPs)';
$CIDRAM['lang']['Name: module_badtlds.php'] = 'Bad TLDs blocker module';
$CIDRAM['lang']['Name: module_baidublocker.php'] = 'Baidu blocker module';
$CIDRAM['lang']['Name: module_cookies.php'] = 'Optional cookie scanner module';
$CIDRAM['lang']['Name: module_extras.php'] = 'Optional security extras module';
$CIDRAM['lang']['Name: module_sfs.php'] = 'Stop Forum Spam module';
$CIDRAM['lang']['Name: module_yandexblocker.php'] = 'Yandex blocker module';
$CIDRAM['lang']['bNav_home_logout'] = '<a href="?">Home</a> | <a href="?cidram-page=logout">Log Out</a>';
$CIDRAM['lang']['bNav_logout'] = '<a href="?cidram-page=logout">Log Out</a>';
$CIDRAM['lang']['config_general_FrontEndLog'] = 'File for logging front-end login attempts. Specify a filename, or leave blank to disable.';
$CIDRAM['lang']['config_general_allow_gethostbyaddr_lookup'] = 'Allow gethostbyaddr lookups when UDP is unavailable? True = Yes [Default]; False = No.';
$CIDRAM['lang']['config_general_ban_override'] = 'Override "forbid_on_block" when "infraction_limit" is exceeded? When overriding: Blocked requests return a blank page (template files aren\'t used). 200 = Don\'t override [Default]. Other values are the same as the available values for "forbid_on_block".';
$CIDRAM['lang']['config_general_default_algo'] = 'Defines which algorithm to use for all future passwords and sessions. Options: PASSWORD_DEFAULT (default), PASSWORD_BCRYPT, PASSWORD_ARGON2I (requires PHP &gt;= 7.2.0).';
$CIDRAM['lang']['config_general_default_dns'] = 'A comma delimited list of DNS servers to use for hostname lookups. Default = "8.8.8.8,8.8.4.4" (Google DNS). WARNING: Don\'t change this unless you know what you\'re doing!';
$CIDRAM['lang']['config_general_disable_cli'] = 'Disable CLI mode? CLI mode is enabled by default, but can sometimes interfere with certain testing tools (such as PHPUnit, for example) and other CLI-based applications. If you don\'t need to disable CLI mode, you should ignore this directive. False = Enable CLI mode [Default]; True = Disable CLI mode.';
$CIDRAM['lang']['config_general_disable_frontend'] = 'Disable front-end access? Front-end access can make CIDRAM more manageable, but can also be a potential security risk, too. It\'s recommended to manage CIDRAM via the back-end whenever possible, but front-end access is provided for when it isn\'t possible. Keep it disabled unless you need it. False = Enable front-end access; True = Disable front-end access [Default].';
$CIDRAM['lang']['config_general_disable_webfonts'] = 'Disable webfonts? True = Yes [Default]; False = No.';
$CIDRAM['lang']['config_general_emailaddr'] = 'If you wish, you can supply an email address here to be given to users when they\'re blocked, for them to use as a point of contact for support and/or assistance for in the event of them being blocked mistakenly or in error. WARNING: Whatever email address you supply here will most certainly be acquired by spambots and scrapers during the course of its being used here, and so, it\'s strongly recommended that if you choose to supply an email address here, that you ensure that the email address you supply here is a disposable address and/or an address that you don\'t mind being spammed (in other words, you probably don\'t want to use your primary personal or primary business email addresses).';
$CIDRAM['lang']['config_general_emailaddr_display_style'] = 'How would you prefer the email address to be presented to users?';
$CIDRAM['lang']['config_general_forbid_on_block'] = 'Which HTTP status message should CIDRAM send when blocking requests? (Refer to the documentation for more information).';
$CIDRAM['lang']['config_general_force_hostname_lookup'] = 'Force hostname lookups? True = Yes; False = No [Default]. Hostname lookups are normally performed on an "as needed" basis, but can be forced for all requests. Doing so may be useful as a means of providing more detailed information in the logfiles, but may also have a slightly negative effect on performance.';
$CIDRAM['lang']['config_general_hide_version'] = 'Hide version information from logs and page output? True = Yes; False = No [Default].';
$CIDRAM['lang']['config_general_ipaddr'] = 'Where to find the IP address of connecting requests? (Useful for services such as Cloudflare and the likes). Default = REMOTE_ADDR. WARNING: Don\'t change this unless you know what you\'re doing!';
$CIDRAM['lang']['config_general_lang'] = 'Specify the default language for CIDRAM.';
$CIDRAM['lang']['config_general_log_banned_ips'] = 'Include blocked requests from banned IPs in the logfiles? True = Yes [Default]; False = No.';
$CIDRAM['lang']['config_general_log_rotation_action'] = 'Log rotation limits the number of logfiles that should exist at any one time. When new logfiles are created, if the total number of logfiles exceeds the specified limit, the specified action will be performed. You can specify the desired action here. Delete = Delete the oldest logfiles, until the limit is no longer exceeded. Archive = Firstly archive, and then delete the oldest logfiles, until the limit is no longer exceeded.';
$CIDRAM['lang']['config_general_log_rotation_limit'] = 'Log rotation limits the number of logfiles that should exist at any one time. When new logfiles are created, if the total number of logfiles exceeds the specified limit, the specified action will be performed. You can specify the desired limit here. A value of 0 will disable log rotation.';
$CIDRAM['lang']['config_general_logfile'] = 'Human readable file for logging all blocked access attempts. Specify a filename, or leave blank to disable.';
$CIDRAM['lang']['config_general_logfileApache'] = 'Apache-style file for logging all blocked access attempts. Specify a filename, or leave blank to disable.';
$CIDRAM['lang']['config_general_logfileSerialized'] = 'Serialised file for logging all blocked access attempts. Specify a filename, or leave blank to disable.';
$CIDRAM['lang']['config_general_maintenance_mode'] = 'Enable maintenance mode? True = Yes; False = No [Default]. Disables everything other than the front-end. Sometimes useful for when updating your CMS, frameworks, etc.';
$CIDRAM['lang']['config_general_max_login_attempts'] = 'Maximum number of login attempts.';
$CIDRAM['lang']['config_general_numbers'] = 'How do you prefer numbers to be displayed? Select the example that looks the most correct to you.';
$CIDRAM['lang']['config_general_protect_frontend'] = 'Specifies whether the protections normally provided by CIDRAM should be applied to the front-end. True = Yes [Default]; False = No.';
$CIDRAM['lang']['config_general_search_engine_verification'] = 'Attempt to verify requests from search engines? Verifying search engines ensures that they won\'t be banned as a result of exceeding the infraction limit (banning search engines from your website will usually have a negative effect upon your search engine ranking, SEO, etc). When verified, search engines can be blocked as per normal, but won\'t be banned. When not verified, it\'s possible for them to be banned as a result of exceeding the infraction limit. Additionally, search engine verification provides protection against fake search engine requests and against potentially malicious entities masquerading as search engines (such requests will be blocked when search engine verification is enabled). True = Enable search engine verification [Default]; False = Disable search engine verification.';
$CIDRAM['lang']['config_general_silent_mode'] = 'Should CIDRAM silently redirect blocked access attempts instead of displaying the "Access Denied" page? If yes, specify the location to redirect blocked access attempts to. If no, leave this variable blank.';
$CIDRAM['lang']['config_general_statistics'] = 'Track CIDRAM usage statistics? True = Yes; False = No [Default].';
$CIDRAM['lang']['config_general_timeFormat'] = 'The date/time notation format used by CIDRAM. Additional options may be added upon request.';
$CIDRAM['lang']['config_general_timeOffset'] = 'Timezone offset in minutes.';
$CIDRAM['lang']['config_general_timezone'] = 'Your timezone.';
$CIDRAM['lang']['config_general_truncate'] = 'Truncate logfiles when they reach a certain size? Value is the maximum size in B/KB/MB/GB/TB that a logfile may grow to before being truncated. The default value of 0KB disables truncation (logfiles can grow indefinitely). Note: Applies to individual logfiles! The size of logfiles is not considered collectively.';
$CIDRAM['lang']['config_legal_omit_hostname'] = 'Omit hostnames from logs? True = Yes; False = No [Default].';
$CIDRAM['lang']['config_legal_omit_ip'] = 'Omit IP addresses from logs? True = Yes; False = No [Default]. Note: "pseudonymise_ip_addresses" becomes redundant when "omit_ip" is "true".';
$CIDRAM['lang']['config_legal_omit_ua'] = 'Omit user agents from logs? True = Yes; False = No [Default].';
$CIDRAM['lang']['config_legal_privacy_policy'] = 'The address of a relevant privacy policy to be displayed in the footer of any generated pages. Specify a URL, or leave blank to disable.';
$CIDRAM['lang']['config_legal_pseudonymise_ip_addresses'] = 'Pseudonymise IP addresses when logging? True = Yes; False = No [Default].';
$CIDRAM['lang']['config_recaptcha_api'] = 'Which API to use? V2 or Invisible?';
$CIDRAM['lang']['config_recaptcha_expiry'] = 'Number of hours to remember reCAPTCHA instances.';
$CIDRAM['lang']['config_recaptcha_lockip'] = 'Lock reCAPTCHA to IPs?';
$CIDRAM['lang']['config_recaptcha_lockuser'] = 'Lock reCAPTCHA to users?';
$CIDRAM['lang']['config_recaptcha_logfile'] = 'Log all reCAPTCHA attempts? If yes, specify the name to use for the logfile. If no, leave this variable blank.';
$CIDRAM['lang']['config_recaptcha_secret'] = 'This value should correspond to the "secret key" for your reCAPTCHA, which can be found within the reCAPTCHA dashboard.';
$CIDRAM['lang']['config_recaptcha_signature_limit'] = 'Maximum number of signatures allowed to be triggered when a reCAPTCHA instance is to be offered. Default = 1. If this number is exceeded for any particular request, a reCAPTCHA instance won\'t be offered.';
$CIDRAM['lang']['config_recaptcha_sitekey'] = 'This value should correspond to the "site key" for your reCAPTCHA, which can be found within the reCAPTCHA dashboard.';
$CIDRAM['lang']['config_recaptcha_usemode'] = 'Defines how CIDRAM should use reCAPTCHA (see documentation).';
$CIDRAM['lang']['config_signatures_block_bogons'] = 'Block bogon/martian CIDRs? If you expect connections to your website from within your local network, from localhost, or from your LAN, this directive should be set to false. If you don\'t expect these such connections, this directive should be set to true.';
$CIDRAM['lang']['config_signatures_block_cloud'] = 'Block CIDRs identified as belonging to webhosting/cloud services? If you operate an API service from your website or if you expect other websites to connect to your website, this should be set to false. If you don\'t, then, this directive should be set to true.';
$CIDRAM['lang']['config_signatures_block_generic'] = 'Block CIDRs generally recommended for blacklisting? This covers any signatures that aren\'t marked as being part of any of the other more specific signature categories.';
$CIDRAM['lang']['config_signatures_block_legal'] = 'Block CIDRs in response to legal obligations? This directive shouldn\'t normally have any effect, because CIDRAM doesn\'t associate any CIDRs with "legal obligations" by default, but it exists nonetheless as an additional control measure for the benefit of any custom signature files or modules that might exist for legal reasons.';
$CIDRAM['lang']['config_signatures_block_malware'] = 'Block IPs associated with malware? This includes C&C servers, infected machines, machines involved in malware distribution, etc.';
$CIDRAM['lang']['config_signatures_block_proxies'] = 'Block CIDRs identified as belonging to proxy services or VPNs? If you require that users be able to access your website from proxy services and VPNs, this directive should be set to false. Otherwise, if you don\'t require proxy services or VPNs, this directive should be set to true as a means of improving security.';
$CIDRAM['lang']['config_signatures_block_spam'] = 'Block CIDRs identified as being high-risk for spam? Unless you experience problems when doing so, generally, this should always be set to true.';
$CIDRAM['lang']['config_signatures_default_tracktime'] = 'How many seconds to track IPs banned by modules. Default = 604800 (1 week).';
$CIDRAM['lang']['config_signatures_infraction_limit'] = 'Maximum number of infractions an IP is allowed to incur before it is banned by IP tracking. Default = 10.';
$CIDRAM['lang']['config_signatures_ipv4'] = 'A list of the IPv4 signature files that CIDRAM should attempt to parse, delimited by commas.';
$CIDRAM['lang']['config_signatures_ipv6'] = 'A list of the IPv6 signature files that CIDRAM should attempt to parse, delimited by commas.';
$CIDRAM['lang']['config_signatures_modules'] = 'A list of module files to load after checking the IPv4/IPv6 signatures, delimited by commas.';
$CIDRAM['lang']['config_signatures_track_mode'] = 'When should infractions be counted? False = When IPs are blocked by modules. True = When IPs are blocked for any reason.';
$CIDRAM['lang']['config_template_data_Magnification'] = 'Font magnification. Default = 1.';
$CIDRAM['lang']['config_template_data_css_url'] = 'CSS file URL for custom themes.';
$CIDRAM['lang']['config_template_data_theme'] = 'Default theme to use for CIDRAM.';
$CIDRAM['lang']['confirm_action'] = 'Are you sure you want to "%s"?';
$CIDRAM['lang']['field_activate'] = 'Activate';
$CIDRAM['lang']['field_banned'] = 'Banned';
$CIDRAM['lang']['field_blocked'] = 'Blocked';
$CIDRAM['lang']['field_clear'] = 'Clear';
$CIDRAM['lang']['field_clear_all'] = 'Clear all';
$CIDRAM['lang']['field_clickable_link'] = 'Clickable link';
$CIDRAM['lang']['field_component'] = 'Component';
$CIDRAM['lang']['field_create_new_account'] = 'Create new account';
$CIDRAM['lang']['field_deactivate'] = 'Deactivate';
$CIDRAM['lang']['field_delete_account'] = 'Delete account';
$CIDRAM['lang']['field_delete_file'] = 'Delete';
$CIDRAM['lang']['field_download_file'] = 'Download';
$CIDRAM['lang']['field_edit_file'] = 'Edit';
$CIDRAM['lang']['field_expiry'] = 'Expiry';
$CIDRAM['lang']['field_false'] = 'False';
$CIDRAM['lang']['field_file'] = 'File';
$CIDRAM['lang']['field_filename'] = 'Filename: ';
$CIDRAM['lang']['field_filetype_directory'] = 'Directory';
$CIDRAM['lang']['field_filetype_info'] = '{EXT} File';
$CIDRAM['lang']['field_filetype_unknown'] = 'Unknown';
$CIDRAM['lang']['field_infractions'] = 'Infractions';
$CIDRAM['lang']['field_install'] = 'Install';
$CIDRAM['lang']['field_ip_address'] = 'IP Address';
$CIDRAM['lang']['field_latest_version'] = 'Latest Version';
$CIDRAM['lang']['field_log_in'] = 'Log In';
$CIDRAM['lang']['field_new_name'] = 'New name:';
$CIDRAM['lang']['field_nonclickable_text'] = 'Non-clickable text';
$CIDRAM['lang']['field_ok'] = 'OK';
$CIDRAM['lang']['field_options'] = 'Options';
$CIDRAM['lang']['field_password'] = 'Password';
$CIDRAM['lang']['field_permissions'] = 'Permissions';
$CIDRAM['lang']['field_range'] = 'Range (First – Last)';
$CIDRAM['lang']['field_rename_file'] = 'Rename';
$CIDRAM['lang']['field_reset'] = 'Reset';
$CIDRAM['lang']['field_set_new_password'] = 'Set new password';
$CIDRAM['lang']['field_size'] = 'Total Size: ';
$CIDRAM['lang']['field_size_GB'] = 'GB';
$CIDRAM['lang']['field_size_KB'] = 'KB';
$CIDRAM['lang']['field_size_MB'] = 'MB';
$CIDRAM['lang']['field_size_TB'] = 'TB';
$CIDRAM['lang']['field_size_bytes'] = ['byte', 'bytes'];
$CIDRAM['lang']['field_status'] = 'Status';
$CIDRAM['lang']['field_system_timezone'] = 'Use system default timezone.';
$CIDRAM['lang']['field_tracking'] = 'Tracking';
$CIDRAM['lang']['field_true'] = 'True';
$CIDRAM['lang']['field_uninstall'] = 'Uninstall';
$CIDRAM['lang']['field_update'] = 'Update';
$CIDRAM['lang']['field_update_all'] = 'Update all';
$CIDRAM['lang']['field_upload_file'] = 'Upload new file';
$CIDRAM['lang']['field_username'] = 'Username';
$CIDRAM['lang']['field_verify'] = 'Verify';
$CIDRAM['lang']['field_verify_all'] = 'Verify all';
$CIDRAM['lang']['field_your_version'] = 'Your Version';
$CIDRAM['lang']['header_login'] = 'Please log in to continue.';
$CIDRAM['lang']['label_active_config_file'] = 'Active configuration file: ';
$CIDRAM['lang']['label_backup_location'] = 'Repository backup locations (in case of emergency, or if all else fails):';
$CIDRAM['lang']['label_banned'] = 'Requests banned';
$CIDRAM['lang']['label_blocked'] = 'Requests blocked';
$CIDRAM['lang']['label_branch'] = 'Branch latest stable:';
$CIDRAM['lang']['label_check_modules'] = 'Also test against modules.';
$CIDRAM['lang']['label_cidram'] = 'CIDRAM version used:';
$CIDRAM['lang']['label_clientinfo'] = 'Client information:';
$CIDRAM['lang']['label_displaying'] = ['Displaying <span class="txtRd">%s</span> entry.', 'Displaying <span class="txtRd">%s</span> entries.'];
$CIDRAM['lang']['label_displaying_that_cite'] = ['Displaying <span class="txtRd">%1$s</span> entry that cites "%2$s".', 'Displaying <span class="txtRd">%1$s</span> entries that cite "%2$s".'];
$CIDRAM['lang']['label_expires'] = 'Expires: ';
$CIDRAM['lang']['label_false_positive_risk'] = 'False positive risk: ';
$CIDRAM['lang']['label_fmgr_cache_data'] = 'Cache data and temporary files';
$CIDRAM['lang']['label_fmgr_disk_usage'] = 'CIDRAM disk usage: ';
$CIDRAM['lang']['label_fmgr_free_space'] = 'Free disk space: ';
$CIDRAM['lang']['label_fmgr_total_disk_usage'] = 'Total disk usage: ';
$CIDRAM['lang']['label_fmgr_total_space'] = 'Total disk space: ';
$CIDRAM['lang']['label_fmgr_updates_metadata'] = 'Component updates metadata';
$CIDRAM['lang']['label_hide'] = 'Hide';
$CIDRAM['lang']['label_never'] = 'Never';
$CIDRAM['lang']['label_os'] = 'Operating system used:';
$CIDRAM['lang']['label_other'] = 'Other';
$CIDRAM['lang']['label_other-ActiveIPv4'] = 'Active IPv4 signature files';
$CIDRAM['lang']['label_other-ActiveIPv6'] = 'Active IPv6 signature files';
$CIDRAM['lang']['label_other-ActiveModules'] = 'Active modules';
$CIDRAM['lang']['label_other-Since'] = 'Start date';
$CIDRAM['lang']['label_php'] = 'PHP version used:';
$CIDRAM['lang']['label_reCAPTCHA'] = 'reCAPTCHA attempts';
$CIDRAM['lang']['label_results'] = 'Results (%s in – %s rejected – %s accepted – %s merged – %s out):';
$CIDRAM['lang']['label_sapi'] = 'SAPI used:';
$CIDRAM['lang']['label_show'] = 'Show';
$CIDRAM['lang']['label_signature_type'] = 'Signature type:';
$CIDRAM['lang']['label_stable'] = 'Latest stable:';
$CIDRAM['lang']['label_sysinfo'] = 'System information:';
$CIDRAM['lang']['label_tests'] = 'Tests:';
$CIDRAM['lang']['label_total'] = 'Total';
$CIDRAM['lang']['label_unstable'] = 'Latest unstable:';
$CIDRAM['lang']['label_used_with'] = 'Used with: ';
$CIDRAM['lang']['label_your_ip'] = 'Your IP:';
$CIDRAM['lang']['label_your_ua'] = 'Your UA:';
$CIDRAM['lang']['link_accounts'] = 'Accounts';
$CIDRAM['lang']['link_cache_data'] = 'Cache Data';
$CIDRAM['lang']['link_cidr_calc'] = 'CIDR Calculator';
$CIDRAM['lang']['link_config'] = 'Configuration';
$CIDRAM['lang']['link_documentation'] = 'Documentation';
$CIDRAM['lang']['link_file_manager'] = 'File Manager';
$CIDRAM['lang']['link_home'] = 'Home';
$CIDRAM['lang']['link_ip_aggregator'] = 'IP Aggregator';
$CIDRAM['lang']['link_ip_test'] = 'IP Test';
$CIDRAM['lang']['link_ip_tracking'] = 'IP Tracking';
$CIDRAM['lang']['link_logs'] = 'Logs';
$CIDRAM['lang']['link_range'] = 'Range Tables';
$CIDRAM['lang']['link_sections_list'] = 'Sections List';
$CIDRAM['lang']['link_statistics'] = 'Statistics';
$CIDRAM['lang']['link_textmode'] = 'Text formatting: <a href="%1$sfalse%2$s">Simple</a> – <a href="%1$strue%2$s">Fancy</a> – <a href="%1$stally%2$s">Tally</a>';
$CIDRAM['lang']['link_updates'] = 'Updates';
$CIDRAM['lang']['logs_logfile_doesnt_exist'] = 'Selected logfile doesn\'t exist!';
$CIDRAM['lang']['logs_no_logfile_selected'] = 'No logfile selected.';
$CIDRAM['lang']['logs_no_logfiles_available'] = 'No logfiles available.';
$CIDRAM['lang']['max_login_attempts_exceeded'] = 'Maximum number of login attempts exceeded; Access denied.';
$CIDRAM['lang']['previewer_days'] = 'Days';
$CIDRAM['lang']['previewer_hours'] = 'Hours';
$CIDRAM['lang']['previewer_minutes'] = 'Minutes';
$CIDRAM['lang']['previewer_months'] = 'Months';
$CIDRAM['lang']['previewer_seconds'] = 'Seconds';
$CIDRAM['lang']['previewer_weeks'] = 'Weeks';
$CIDRAM['lang']['previewer_years'] = 'Years';
$CIDRAM['lang']['response_accounts_already_exists'] = 'An account with that username already exists!';
$CIDRAM['lang']['response_accounts_created'] = 'Account successfully created!';
$CIDRAM['lang']['response_accounts_deleted'] = 'Account successfully deleted!';
$CIDRAM['lang']['response_accounts_doesnt_exist'] = 'That account doesn\'t exist.';
$CIDRAM['lang']['response_accounts_password_updated'] = 'Password successfully updated!';
$CIDRAM['lang']['response_activated'] = 'Successfully activated.';
$CIDRAM['lang']['response_activation_failed'] = 'Failed to activate!';
$CIDRAM['lang']['response_checksum_error'] = 'Checksum error! File rejected!';
$CIDRAM['lang']['response_component_successfully_installed'] = 'Component successfully installed.';
$CIDRAM['lang']['response_component_successfully_uninstalled'] = 'Component successfully uninstalled.';
$CIDRAM['lang']['response_component_successfully_updated'] = 'Component successfully updated.';
$CIDRAM['lang']['response_component_uninstall_error'] = 'An error occurred while attempting to uninstall the component.';
$CIDRAM['lang']['response_configuration_updated'] = 'Configuration successfully updated.';
$CIDRAM['lang']['response_deactivated'] = 'Successfully deactivated.';
$CIDRAM['lang']['response_deactivation_failed'] = 'Failed to deactivate!';
$CIDRAM['lang']['response_delete_error'] = 'Failed to delete!';
$CIDRAM['lang']['response_directory_deleted'] = 'Directory successfully deleted!';
$CIDRAM['lang']['response_directory_renamed'] = 'Directory successfully renamed!';
$CIDRAM['lang']['response_error'] = 'Error';
$CIDRAM['lang']['response_failed_to_install'] = 'Failed to install!';
$CIDRAM['lang']['response_failed_to_update'] = 'Failed to update!';
$CIDRAM['lang']['response_file_deleted'] = 'File successfully deleted!';
$CIDRAM['lang']['response_file_edited'] = 'File successfully modified!';
$CIDRAM['lang']['response_file_renamed'] = 'File successfully renamed!';
$CIDRAM['lang']['response_file_uploaded'] = 'File successfully uploaded!';
$CIDRAM['lang']['response_login_invalid_password'] = 'Login failure! Invalid password!';
$CIDRAM['lang']['response_login_invalid_username'] = 'Login failure! Username doesn\'t exist!';
$CIDRAM['lang']['response_login_password_field_empty'] = 'Password field empty!';
$CIDRAM['lang']['response_login_username_field_empty'] = 'Username field empty!';
$CIDRAM['lang']['response_login_wrong_endpoint'] = 'Wrong endpoint!';
$CIDRAM['lang']['response_no'] = 'No';
$CIDRAM['lang']['response_possible_problem_found'] = 'Possible problem found.';
$CIDRAM['lang']['response_rename_error'] = 'Failed to rename!';
$CIDRAM['lang']['response_sanity_1'] = 'File contains unexpected content! File rejected!';
$CIDRAM['lang']['response_statistics_cleared'] = 'Statistics cleared.';
$CIDRAM['lang']['response_tracking_cleared'] = 'Tracking cleared.';
$CIDRAM['lang']['response_updates_already_up_to_date'] = 'Already up-to-date.';
$CIDRAM['lang']['response_updates_not_installed'] = 'Component not installed!';
$CIDRAM['lang']['response_updates_not_installed_php'] = 'Component not installed (requires PHP &gt;= {V})!';
$CIDRAM['lang']['response_updates_outdated'] = 'Outdated!';
$CIDRAM['lang']['response_updates_outdated_manually'] = 'Outdated (please update manually)!';
$CIDRAM['lang']['response_updates_outdated_php_version'] = 'Outdated (requires PHP &gt;= {V})!';
$CIDRAM['lang']['response_updates_unable_to_determine'] = 'Unable to determine.';
$CIDRAM['lang']['response_upload_error'] = 'Failed to upload!';
$CIDRAM['lang']['response_verification_failed'] = 'Verification failed! Component could be corrupted.';
$CIDRAM['lang']['response_verification_success'] = 'Verification success! No problems found.';
$CIDRAM['lang']['response_yes'] = 'Yes';
$CIDRAM['lang']['state_async_deny'] = 'Permissions not adequate to perform asynchronous requests. Try logging in again.';
$CIDRAM['lang']['state_cache_is_empty'] = 'The cache is empty.';
$CIDRAM['lang']['state_complete_access'] = 'Complete access';
$CIDRAM['lang']['state_component_is_active'] = 'Component is active.';
$CIDRAM['lang']['state_component_is_inactive'] = 'Component is inactive.';
$CIDRAM['lang']['state_component_is_provisional'] = 'Component is provisional.';
$CIDRAM['lang']['state_default_password'] = 'Warning: Using default password!';
$CIDRAM['lang']['state_ignored'] = 'Ignored';
$CIDRAM['lang']['state_loading'] = 'Loading...';
$CIDRAM['lang']['state_loadtime'] = 'Page request completed in <span class="txtRd">%s</span> seconds.';
$CIDRAM['lang']['state_logged_in'] = 'Logged in.';
$CIDRAM['lang']['state_logs_access_only'] = 'Logs access only';
$CIDRAM['lang']['state_maintenance_mode'] = 'Warning: Maintenance mode is enabled!';
$CIDRAM['lang']['state_password_not_valid'] = 'Warning: This account is not using a valid password!';
$CIDRAM['lang']['state_risk_high'] = 'High';
$CIDRAM['lang']['state_risk_low'] = 'Low';
$CIDRAM['lang']['state_risk_medium'] = 'Medium';
$CIDRAM['lang']['state_sl_totals'] = 'Totals (Signatures: <span class="txtRd">%s</span> – Signature sections: <span class="txtRd">%s</span> – Signature files: <span class="txtRd">%s</span>).';
$CIDRAM['lang']['state_tracking'] = ['Currently tracking %s IP.', 'Currently tracking %s IPs.'];
$CIDRAM['lang']['switch-hide-non-outdated-set-false'] = 'Don\'t hide non-outdated';
$CIDRAM['lang']['switch-hide-non-outdated-set-true'] = 'Hide non-outdated';
$CIDRAM['lang']['switch-hide-unused-set-false'] = 'Don\'t hide unused';
$CIDRAM['lang']['switch-hide-unused-set-true'] = 'Hide unused';
$CIDRAM['lang']['switch-tracking-blocked-already-set-false'] = 'Don\'t check against signature files';
$CIDRAM['lang']['switch-tracking-blocked-already-set-true'] = 'Check against signature files';
$CIDRAM['lang']['switch-tracking-hide-banned-blocked-set-false'] = 'Don\'t hide banned/blocked IPs';
$CIDRAM['lang']['switch-tracking-hide-banned-blocked-set-true'] = 'Hide banned/blocked IPs';
$CIDRAM['lang']['tip_accounts'] = 'Hello, {username}.<br />The accounts page allows you to control who can access the CIDRAM front-end.';
$CIDRAM['lang']['tip_cache_data'] = 'Hello, {username}.<br />Here you can review the contents of the cache.';
$CIDRAM['lang']['tip_cidr_calc'] = 'Hello, {username}.<br />The CIDR calculator allows you to calculate which CIDRs an IP address is a factor of.';
$CIDRAM['lang']['tip_config'] = 'Hello, {username}.<br />The configuration page allows you to modify the configuration for CIDRAM from the front-end.';
$CIDRAM['lang']['tip_custom_ua'] = 'Enter user agent here (optional).';
$CIDRAM['lang']['tip_donate'] = 'CIDRAM is offered free of charge, but if you want to donate to the project, you can do so by clicking the donate button.';
$CIDRAM['lang']['tip_enter_ip_here'] = 'Enter IP here.';
$CIDRAM['lang']['tip_enter_ips_here'] = 'Enter IPs here.';
$CIDRAM['lang']['tip_fe_cookie_warning'] = 'Note: CIDRAM uses a cookie to authenticate logins. By logging in, you give your consent for a cookie to be created and stored by your browser.';
$CIDRAM['lang']['tip_file_manager'] = 'Hello, {username}.<br />The file manager allows you to delete, edit, upload, and download files. Use with caution (you could break your installation with this).';
$CIDRAM['lang']['tip_home'] = 'Hello, {username}.<br />This is the homepage for the CIDRAM front-end. Select a link from the navigation menu on the left to continue.';
$CIDRAM['lang']['tip_ip_aggregator'] = 'Hello, {username}.<br />The IP aggregator allows you to express IPs and CIDRs in the smallest possible way. Enter the data to be aggregated and press "OK".';
$CIDRAM['lang']['tip_ip_test'] = 'Hello, {username}.<br />The IP test page allows you to test whether IP addresses are blocked by the currently installed signatures.';
$CIDRAM['lang']['tip_ip_test_module_switch'] = '(When not selected, only the signature files will be tested against).';
$CIDRAM['lang']['tip_ip_tracking'] = 'Hello, {username}.<br />The IP tracking page allows you to check the tracking status of IP addresses, to check which of them have been banned, and to unban/untrack them if you want to do so.';
$CIDRAM['lang']['tip_login'] = 'Default username: <span class="txtRd">admin</span> – Default password: <span class="txtRd">password</span>';
$CIDRAM['lang']['tip_logs'] = 'Hello, {username}.<br />Select a logfile from the list below to view the contents of that logfile.';
$CIDRAM['lang']['tip_range'] = 'Hello, {username}.<br />This page shows some basic statistical information about the IP ranges covered by the currently active signature files.';
$CIDRAM['lang']['tip_sections_list'] = 'Hello, {username}.<br />This page lists which sections exist in the currently active signature files.';
$CIDRAM['lang']['tip_see_the_documentation'] = 'See the <a href="https://github.com/CIDRAM/CIDRAM/blob/master/_docs/readme.en.md#SECTION6">documentation</a> for information about the various configuration directives and their purposes.';
$CIDRAM['lang']['tip_statistics'] = 'Hello, {username}.<br />This page shows some basic usage statistics regarding your CIDRAM installation.';
$CIDRAM['lang']['tip_statistics_disabled'] = 'Note: Statistics tracking is currently disabled, but can be enabled via the configuration page.';
$CIDRAM['lang']['tip_updates'] = 'Hello, {username}.<br />The updates page allows you to install, uninstall, and update the various components of CIDRAM (the core package, signatures, L10N files, etc).';
$CIDRAM['lang']['title_accounts'] = 'CIDRAM – Accounts';
$CIDRAM['lang']['title_cache_data'] = 'CIDRAM – Cache Data';
$CIDRAM['lang']['title_cidr_calc'] = 'CIDRAM – CIDR Calculator';
$CIDRAM['lang']['title_config'] = 'CIDRAM – Configuration';
$CIDRAM['lang']['title_file_manager'] = 'CIDRAM – File Manager';
$CIDRAM['lang']['title_home'] = 'CIDRAM – Home';
$CIDRAM['lang']['title_ip_aggregator'] = 'CIDRAM – IP Aggregator';
$CIDRAM['lang']['title_ip_test'] = 'CIDRAM – IP Test';
$CIDRAM['lang']['title_ip_tracking'] = 'CIDRAM – IP Tracking';
$CIDRAM['lang']['title_login'] = 'CIDRAM – Login';
$CIDRAM['lang']['title_logs'] = 'CIDRAM – Logs';
$CIDRAM['lang']['title_range'] = 'CIDRAM – Range Tables';
$CIDRAM['lang']['title_sections_list'] = 'CIDRAM – Sections List';
$CIDRAM['lang']['title_statistics'] = 'CIDRAM – Statistics';
$CIDRAM['lang']['title_updates'] = 'CIDRAM – Updates';
$CIDRAM['lang']['warning'] = 'Warnings:';
$CIDRAM['lang']['warning_php_1'] = 'Your PHP version is not actively supported anymore! Updating is recommended!';
$CIDRAM['lang']['warning_php_2'] = 'Your PHP version is severely vulnerable! Updating is strongly recommended!';
$CIDRAM['lang']['warning_signatures_1'] = 'No signature files are active!';

$CIDRAM['lang']['info_some_useful_links'] = 'Some useful links:<ul>
            <li><a href="https://github.com/CIDRAM/CIDRAM/issues">CIDRAM Issues @ GitHub</a> – Issues page for CIDRAM (support, assistance, etc).</li>
            <li><a href="https://wordpress.org/plugins/cidram/">CIDRAM @ WordPress.org</a> – WordPress plugin for CIDRAM.</li>
            <li><a href="https://websectools.com/">WebSecTools.com</a> – A collection of simple webmaster tools to secure websites.</li>
            <li><a href="https://bitbucket.org/macmathan/blocklists">macmathan/blocklists</a> – Contains optional blocklists and modules for CIDRAM such as for blocking dangerous bots, unwanted countries, outdated browsers, etc.</li>
            <li><a href="https://www.facebook.com/groups/2204685680/">Global PHP Group @ Facebook</a> – PHP learning resources and discussion.</li>
            <li><a href="https://php.earth/">PHP.earth</a> – PHP learning resources and discussion.</li>
            <li><a href="http://bgp.he.net/">Hurricane Electric BGP Toolkit</a> – Get CIDRs from ASNs, determine ASN relationships, discover ASNs based upon network names, etc.</li>
            <li><a href="https://www.stopforumspam.com/forum/">Forum @ Stop Forum Spam</a> – Useful discussion forum about stopping forum spam.</li>
            <li><a href="https://radar.qrator.net/">Radar by Qrator</a> – Useful tool for checking the connectivity of ASNs as well as for various other information about ASNs.</li>
            <li><a href="http://www.ipdeny.com/ipblocks/">IPdeny IP country blocks</a> – A fantastic and accurate service for generating country-wide signatures.</li>
            <li><a href="https://www.google.com/transparencyreport/safebrowsing/malware/">Google Malware Dashboard</a> – Displays reports regarding malware infection rates for ASNs.</li>
            <li><a href="https://www.spamhaus.org/statistics/botnet-asn/">The Spamhaus Project</a> – Displays reports regarding botnet infection rates for ASNs.</li>
            <li><a href="https://www.abuseat.org/public/asn.html">Abuseat.org\'s Composite Blocking List</a> – Displays reports regarding botnet infection rates for ASNs.</li>
            <li><a href="https://abuseipdb.com/">AbuseIPDB</a> – Maintains a database of known abusive IPs; Provides an API for checking and reporting IPs.</li>
            <li><a href="https://www.megarbl.net/index.php">MegaRBL.net</a> – Maintains listings of known spammers; Useful for checking IP/ASN spam activities.</li>
            <li><a href="https://maikuolan.github.io/Vulnerability-Charts/">Vulnerability Charts</a> – Lists safe/unsafe versions of various packages (HHVM, PHP, phpMyAdmin, Python, etc).</li>
            <li><a href="https://maikuolan.github.io/Compatibility-Charts/">Compatibility Charts</a> – Lists compatibility information for various packages (CIDRAM, phpMussel, etc).</li>
        </ul>';
