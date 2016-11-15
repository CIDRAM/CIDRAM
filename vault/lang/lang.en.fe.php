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
 * This file: English language data for the front-end (last modified: 2016.11.15).
 */

/** Prevents execution from outside of CIDRAM. */
if (!defined('CIDRAM')) {
    die('[CIDRAM] This should not be accessed directly.');
}

$CIDRAM['lang']['bNav_home_logout'] = '<a href="?">Home</a> | <a href="?cidram-page=logout">Log Out</a>';
$CIDRAM['lang']['bNav_logout'] = '<a href="?cidram-page=logout">Log Out</a>';
$CIDRAM['lang']['field_blocked'] = 'Blocked';
$CIDRAM['lang']['field_component'] = 'Component';
$CIDRAM['lang']['field_create_new_account'] = 'Create New Account';
$CIDRAM['lang']['field_delete_account'] = 'Delete Account';
$CIDRAM['lang']['field_install'] = 'Install';
$CIDRAM['lang']['field_ip_address'] = 'IP Address';
$CIDRAM['lang']['field_latest_version'] = 'Latest Version';
$CIDRAM['lang']['field_log_in'] = 'Log In';
$CIDRAM['lang']['field_ok'] = 'OK';
$CIDRAM['lang']['field_options'] = 'Options';
$CIDRAM['lang']['field_password'] = 'Password';
$CIDRAM['lang']['field_permissions'] = 'Permissions';
$CIDRAM['lang']['field_set_new_password'] = 'Set New Password';
$CIDRAM['lang']['field_size'] = 'Total Size: ';
$CIDRAM['lang']['field_size_bytes'] = 'bytes';
$CIDRAM['lang']['field_size_GB'] = 'GB';
$CIDRAM['lang']['field_size_KB'] = 'KB';
$CIDRAM['lang']['field_size_MB'] = 'MB';
$CIDRAM['lang']['field_size_TB'] = 'TB';
$CIDRAM['lang']['field_status'] = 'Status';
$CIDRAM['lang']['field_uninstall'] = 'Uninstall';
$CIDRAM['lang']['field_update'] = 'Update';
$CIDRAM['lang']['field_username'] = 'Username';
$CIDRAM['lang']['field_your_version'] = 'Your Version';
$CIDRAM['lang']['header_login'] = 'Please log in to continue.';
$CIDRAM['lang']['link_accounts'] = 'Accounts';
$CIDRAM['lang']['link_config'] = 'Configuration';
$CIDRAM['lang']['link_documentation'] = 'Documentation';
$CIDRAM['lang']['link_home'] = 'Home';
$CIDRAM['lang']['link_ip_test'] = 'IP Test';
$CIDRAM['lang']['link_logs'] = 'Logs';
$CIDRAM['lang']['link_updates'] = 'Updates';
$CIDRAM['lang']['logs_logfile_doesnt_exist'] = 'Selected logfile doesn\'t exist!';
$CIDRAM['lang']['logs_no_logfiles_available'] = 'No logfiles available.';
$CIDRAM['lang']['logs_no_logfile_selected'] = 'No logfile selected.';
$CIDRAM['lang']['response_accounts_already_exists'] = 'An account with that username already exists!';
$CIDRAM['lang']['response_accounts_created'] = 'Account successfully created!';
$CIDRAM['lang']['response_accounts_deleted'] = 'Account successfully deleted!';
$CIDRAM['lang']['response_accounts_doesnt_exist'] = 'That account doesn\'t exist.';
$CIDRAM['lang']['response_accounts_password_updated'] = 'Password successfully updated!';
$CIDRAM['lang']['response_component_successfully_installed'] = 'Component successfully installed.';
$CIDRAM['lang']['response_component_successfully_uninstalled'] = 'Component successfully uninstalled.';
$CIDRAM['lang']['response_component_successfully_updated'] = 'Component successfully updated.';
$CIDRAM['lang']['response_component_uninstall_error'] = 'An error occurred while attempting to uninstall the component.';
$CIDRAM['lang']['response_component_update_error'] = 'An error occurred while attempting to update the component.';
$CIDRAM['lang']['response_error'] = 'Error';
$CIDRAM['lang']['response_login_invalid_password'] = 'Login failure! Invalid password!';
$CIDRAM['lang']['response_login_invalid_username'] = 'Login failure! Username doesn\'t exist!';
$CIDRAM['lang']['response_login_password_field_empty'] = 'Password field empty!';
$CIDRAM['lang']['response_login_username_field_empty'] = 'Username field empty!';
$CIDRAM['lang']['response_no'] = 'No';
$CIDRAM['lang']['response_updates_already_up_to_date'] = 'Already up-to-date.';
$CIDRAM['lang']['response_updates_not_installed'] = 'Component not installed!';
$CIDRAM['lang']['response_updates_outdated'] = 'Outdated!';
$CIDRAM['lang']['response_updates_outdated_manually'] = 'Outdated (please update manually)!';
$CIDRAM['lang']['response_updates_unable_to_determine'] = 'Unable to determine.';
$CIDRAM['lang']['response_yes'] = 'Yes';
$CIDRAM['lang']['state_complete_access'] = 'Complete access';
$CIDRAM['lang']['state_component_is_active'] = 'Component is active.';
$CIDRAM['lang']['state_component_is_inactive'] = 'Component is inactive.';
$CIDRAM['lang']['state_component_is_provisional'] = 'Component is provisional.';
$CIDRAM['lang']['state_default_password'] = 'Warning: Using default password!';
$CIDRAM['lang']['state_logged_in'] = 'Logged in';
$CIDRAM['lang']['state_logs_access_only'] = 'Logs access only';
$CIDRAM['lang']['state_password_not_valid'] = 'Warning: This account is not using a valid password!';
$CIDRAM['lang']['switch-hide-non-outdated-set-false'] = 'Don\'t hide non-outdated';
$CIDRAM['lang']['switch-hide-non-outdated-set-true'] = 'Hide non-outdated';
$CIDRAM['lang']['switch-hide-unused-set-false'] = 'Don\'t hide unused';
$CIDRAM['lang']['switch-hide-unused-set-true'] = 'Hide unused';
$CIDRAM['lang']['tip_accounts'] = 'Hello, {username}.<br />The accounts page allows you to control who can access the CIDRAM front-end.';
$CIDRAM['lang']['tip_config'] = 'Hello, {username}.<br />The configuration page allows you to modify the configuration for CIDRAM from the front-end.';
$CIDRAM['lang']['tip_enter_ips_here'] = 'Enter IPs here.';
$CIDRAM['lang']['tip_home'] = 'Hello, {username}.<br />This is the homepage for the CIDRAM front-end. Select a link from the navigation menu on the left to continue.';
$CIDRAM['lang']['tip_ip_test'] = 'Hello, {username}.<br />The IP test page allows you to test whether IP addresses are blocked by the currently installed signatures.';
$CIDRAM['lang']['tip_login'] = 'Default username: <span class="txtRd">admin</span> – Default password: <span class="txtRd">password</span>';
$CIDRAM['lang']['tip_logs'] = 'Hello, {username}.<br />Select a logfile from the list below to view the contents of that logfile.';
$CIDRAM['lang']['tip_updates'] = 'Hello, {username}.<br />The updates page allows you to install, uninstall, and update the various components of CIDRAM (the core package, signatures, L10N files, etc).';
$CIDRAM['lang']['title_accounts'] = 'CIDRAM – Accounts';
$CIDRAM['lang']['title_config'] = 'CIDRAM – Configuration';
$CIDRAM['lang']['title_home'] = 'CIDRAM – Home';
$CIDRAM['lang']['title_ip_test'] = 'CIDRAM – IP Test';
$CIDRAM['lang']['title_login'] = 'CIDRAM – Login';
$CIDRAM['lang']['title_logs'] = 'CIDRAM – Logs';
$CIDRAM['lang']['title_updates'] = 'CIDRAM – Updates';
