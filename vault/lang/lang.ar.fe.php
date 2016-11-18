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
 * This file: Arabic language data for the front-end (last modified: 2016.11.18).
 */

/** Prevents execution from outside of CIDRAM. */
if (!defined('CIDRAM')) {
    die('[CIDRAM] This should not be accessed directly.');
}

$CIDRAM['lang']['bNav_home_logout'] = '<a href="?">الرئيسية</a> | <a href="?cidram-page=logout">خروج</a>';
$CIDRAM['lang']['bNav_logout'] = '<a href="?cidram-page=logout">خروج</a>';
$CIDRAM['lang']['config_general_disable_cli'] = 'وضع تعطيل CLI؟';
$CIDRAM['lang']['config_general_disable_frontend'] = 'تعطيل وصول front-end؟';
$CIDRAM['lang']['config_general_emailaddr'] = 'عنوان البريد الإلكتروني للحصول على الدعم.';
$CIDRAM['lang']['config_general_forbid_on_block'] = 'الذي رؤوس ينبغي CIDRAM الرد عندما حظر طلبات؟';
$CIDRAM['lang']['config_general_ipaddr'] = 'أين يمكن العثور على عنوان IP لربط الطلبات؟';
$CIDRAM['lang']['config_general_lang'] = 'تحديد اللغة الافتراضية الخاصة بـ CIDRAM.';
$CIDRAM['lang']['config_general_logfile'] = 'ملف يمكن قراءته بالعين لتسجيل كل محاولات الوصول سدت. تحديد اسم الملف، أو اتركه فارغا لتعطيل.';
$CIDRAM['lang']['config_general_logfileApache'] = 'ملف على غرار أباتشي لتسجيل كل محاولات الوصول سدت. تحديد اسم الملف، أو اتركه فارغا لتعطيل.';
$CIDRAM['lang']['config_general_logfileSerialized'] = 'ملف تسلسل لتسجيل كل محاولات الوصول سدت. تحديد اسم الملف، أو اتركه فارغا لتعطيل.';
$CIDRAM['lang']['config_general_silent_mode'] = 'يجب CIDRAM إعادة توجيه بصمت محاولات وصول مرفوض بدلا من عرض الصفحة "تم رفض الوصول"؟ اذا نعم، تحديد الموقع لإعادة توجيه محاولات وصول مرفوض. ان لم، ترك هذا الحقل فارغا.';
$CIDRAM['lang']['config_general_timeOffset'] = 'المنطقة الزمنية تعويض في غضون دقائق.';
$CIDRAM['lang']['config_recaptcha_expiry'] = 'عدد الساعات لنتذكر حالات اختبار reCAPTCHA.';
$CIDRAM['lang']['config_recaptcha_lockip'] = 'القفل reCAPTCHA إلى IP؟';
$CIDRAM['lang']['config_recaptcha_lockuser'] = 'القفل reCAPTCHA إلى المستخدمين؟';
$CIDRAM['lang']['config_recaptcha_logfile'] = 'Log all reCAPTCHA attempts? If yes, specify the name to use for the logfile. If no, leave this variable blank.'; // @TranslateMe@
$CIDRAM['lang']['config_recaptcha_secret'] = 'This value should correspond to the "secret key" for your reCAPTCHA, which can be found within the reCAPTCHA dashboard.'; // @TranslateMe@
$CIDRAM['lang']['config_recaptcha_sitekey'] = 'This value should correspond to the "site key" for your reCAPTCHA, which can be found within the reCAPTCHA dashboard.'; // @TranslateMe@
$CIDRAM['lang']['config_recaptcha_usemode'] = 'Defines how CIDRAM should use reCAPTCHA (see documentation).'; // @TranslateMe@
$CIDRAM['lang']['config_signatures_block_bogons'] = 'Block bogon/martian CIDRs? If you expect connections to your website from within your local network, from localhost, or from your LAN, this directive should be set to false. If you don\'t expect these such connections, this directive should be set to true.'; // @TranslateMe@
$CIDRAM['lang']['config_signatures_block_cloud'] = 'Block CIDRs identified as belonging to webhosting/cloud services? If you operate an API service from your website or if you expect other websites to connect to your website, this should be set to false. If you don\'t, then, this directive should be set to true.'; // @TranslateMe@
$CIDRAM['lang']['config_signatures_block_generic'] = 'Block CIDRs generally recommended for blacklisting? This covers any signatures that aren\'t marked as being part of any of the other more specific signature categories.'; // @TranslateMe@
$CIDRAM['lang']['config_signatures_block_proxies'] = 'Block CIDRs identified as belonging to proxy services? If you require that users be able to access your website from anonymous proxy services, this should be set to false. Otherwise, if you don\'t require anonymous proxies, this directive should be set to true as a means of improving security.'; // @TranslateMe@
$CIDRAM['lang']['config_signatures_block_spam'] = 'Block CIDRs identified as being high-risk for spam? Unless you experience problems when doing so, generally, this should always be set to true.'; // @TranslateMe@
$CIDRAM['lang']['config_signatures_ipv4'] = 'وهناك قائمة من الملفات توقيع عناوين IPv4 التي CIDRAM يجب أن تحاول معالجة، مفصولة بفواصل.';
$CIDRAM['lang']['config_signatures_ipv6'] = 'وهناك قائمة من الملفات توقيع عناوين IPv6 التي CIDRAM يجب أن تحاول معالجة، مفصولة بفواصل.';
$CIDRAM['lang']['config_template_data_css_url'] = 'URL ملف CSS لمواضيع مخصصة.';
$CIDRAM['lang']['field_blocked'] = 'مسدود';
$CIDRAM['lang']['field_component'] = 'وحدة';
$CIDRAM['lang']['field_create_new_account'] = 'خلق جديد حساب';
$CIDRAM['lang']['field_delete_account'] = 'حذف حساب';
$CIDRAM['lang']['field_filename'] = 'اسم الملف: ';
$CIDRAM['lang']['field_install'] = 'تثبيت';
$CIDRAM['lang']['field_ip_address'] = 'عنوان IP';
$CIDRAM['lang']['field_latest_version'] = 'احدث اصدار';
$CIDRAM['lang']['field_log_in'] = 'تسجيل الدخول';
$CIDRAM['lang']['field_ok'] = 'حسنا';
$CIDRAM['lang']['field_options'] = 'خيارات';
$CIDRAM['lang']['field_password'] = 'كلمه السر';
$CIDRAM['lang']['field_permissions'] = 'أذونات';
$CIDRAM['lang']['field_set_new_password'] = 'تحديد جديد كلمه السر';
$CIDRAM['lang']['field_size'] = 'الحجم الإجمالي: ';
$CIDRAM['lang']['field_size_bytes'] = 'بايت';
$CIDRAM['lang']['field_size_GB'] = 'GB';
$CIDRAM['lang']['field_size_KB'] = 'KB';
$CIDRAM['lang']['field_size_MB'] = 'MB';
$CIDRAM['lang']['field_size_TB'] = 'TB';
$CIDRAM['lang']['field_status'] = 'الحالة';
$CIDRAM['lang']['field_uninstall'] = 'الغاء التثبيت';
$CIDRAM['lang']['field_update'] = 'تحديث';
$CIDRAM['lang']['field_username'] = 'اسم المستخدم';
$CIDRAM['lang']['field_your_version'] = 'الإصدار الخاص بك';
$CIDRAM['lang']['header_login'] = 'الرجاء تسجيل الدخول للمتابعة.';
$CIDRAM['lang']['link_accounts'] = 'حسابات';
$CIDRAM['lang']['link_config'] = 'التكوين';
$CIDRAM['lang']['link_documentation'] = 'توثيق';
$CIDRAM['lang']['link_home'] = 'الرئيسية';
$CIDRAM['lang']['link_ip_test'] = 'اختبار IP';
$CIDRAM['lang']['link_logs'] = 'سجلات';
$CIDRAM['lang']['link_updates'] = 'التحديثات';
$CIDRAM['lang']['logs_logfile_doesnt_exist'] = 'هذا سجل غير موجود!';
$CIDRAM['lang']['logs_no_logfiles_available'] = 'لا سجلات متاح.';
$CIDRAM['lang']['logs_no_logfile_selected'] = 'لا سجلات مختار.';
$CIDRAM['lang']['response_accounts_already_exists'] = 'حساب اسم المستخدم موجود بالفعل!';
$CIDRAM['lang']['response_accounts_created'] = 'حساب إنشاء بنجاح!';
$CIDRAM['lang']['response_accounts_deleted'] = 'حساب حذف بنجاح!';
$CIDRAM['lang']['response_accounts_doesnt_exist'] = 'حساب غير موجود.';
$CIDRAM['lang']['response_accounts_password_updated'] = 'كلمه السر التحديث بنجاح!';
$CIDRAM['lang']['response_component_successfully_installed'] = 'وحدة تم التثبيت بنجاح.';
$CIDRAM['lang']['response_component_successfully_uninstalled'] = 'وحدة إلغاء تثبيت بنجاح.';
$CIDRAM['lang']['response_component_successfully_updated'] = 'وحدة تم التحديث بنجاح.';
$CIDRAM['lang']['response_component_uninstall_error'] = 'حدث خطأ أثناء محاولة إلغاء تثبيت الوحدة.';
$CIDRAM['lang']['response_component_update_error'] = 'حدث خطأ أثناء محاولة تحديث الوحدة.';
$CIDRAM['lang']['response_configuration_updated'] = 'التكوين تحديثها بنجاح.';
$CIDRAM['lang']['response_error'] = 'خطأ';
$CIDRAM['lang']['response_login_invalid_password'] = 'فشل تسجيل الدخول! غير صالحة كلمه السر!';
$CIDRAM['lang']['response_login_invalid_username'] = 'فشل تسجيل الدخول! اسم المستخدم غير موجود!';
$CIDRAM['lang']['response_login_password_field_empty'] = 'كلمه السر حقل فارغ!';
$CIDRAM['lang']['response_login_username_field_empty'] = 'اسم المستخدم حقل فارغ!';
$CIDRAM['lang']['response_no'] = 'لا';
$CIDRAM['lang']['response_updates_already_up_to_date'] = 'تحديث غير مطلوب.';
$CIDRAM['lang']['response_updates_not_installed'] = 'وحدة غير مثبت!';
$CIDRAM['lang']['response_updates_outdated'] = 'عفا عليها الزمن!';
$CIDRAM['lang']['response_updates_outdated_manually'] = 'عفا عليها الزمن (يرجى تحديث يدويا)!';
$CIDRAM['lang']['response_updates_unable_to_determine'] = 'غير قادر على تحديد.';
$CIDRAM['lang']['response_yes'] = 'نعم';
$CIDRAM['lang']['state_complete_access'] = 'الوصول كامل';
$CIDRAM['lang']['state_component_is_active'] = 'وحدة هو نشطا.';
$CIDRAM['lang']['state_component_is_inactive'] = 'وحدة هو غير نشط.';
$CIDRAM['lang']['state_component_is_provisional'] = 'وحدة هو جزئيا نشطا.';
$CIDRAM['lang']['state_default_password'] = 'تحذير: يستخدم الافتراضي كلمه السر!';
$CIDRAM['lang']['state_logged_in'] = 'حاليا على';
$CIDRAM['lang']['state_logs_access_only'] = 'سجلات الوصول فقط';
$CIDRAM['lang']['state_password_not_valid'] = 'تحذير: هذا الحساب لا يستخدم صالحة كلمه السر!';
$CIDRAM['lang']['switch-hide-non-outdated-set-false'] = 'لا يخفون غير عفا عليها الزمن';
$CIDRAM['lang']['switch-hide-non-outdated-set-true'] = 'يخفون غير عفا عليها الزمن';
$CIDRAM['lang']['switch-hide-unused-set-false'] = 'لا يخفون غير مستعمل';
$CIDRAM['lang']['switch-hide-unused-set-true'] = 'يخفون غير مستعمل';
$CIDRAM['lang']['tip_accounts'] = 'مرحبا، {username}.<br />الصفحة حسابات يسمح لك للسيطرة على الذي يمكن الوصول ألfront-end CIDRAM.';
$CIDRAM['lang']['tip_config'] = 'مرحبا، {username}.<br />الصفحة التكوين يسمح لك لتعديل التكوين CIDRAM عن طريق ألfront-end.';
$CIDRAM['lang']['tip_enter_ips_here'] = 'أدخل IPs هنا.';
$CIDRAM['lang']['tip_home'] = 'مرحبا، {username}.<br />هذا هو الصفحة رئيسية ألfront-end CIDRAM. اختر ارتباط من قائمة التنقل على اليسار للمتابعة.';
$CIDRAM['lang']['tip_ip_test'] = 'مرحبا، {username}.<br />الصفحة اختبار IP يسمح لك لاختبار سواء عناوين IP مسدودة من التوقيعات المثبتة حاليا.';
$CIDRAM['lang']['tip_login'] = 'الافتراضي اسم المستخدم: <span class="txtRd">admin</span> – الافتراضي كلمه السر: <span class="txtRd">password</span>';
$CIDRAM['lang']['tip_logs'] = 'مرحبا، {username}.<br />اختار سجلات من القائمة أدناه لعرضها.';
$CIDRAM['lang']['tip_see_the_documentation'] = 'راجع <a href="https://github.com/Maikuolan/CIDRAM/blob/master/_docs/readme.ar.md#SECTION6">وثائق</a> للحصول على معلومات حول مختلف توجيهات التكوين ونيتهم.';
$CIDRAM['lang']['tip_updates'] = 'مرحبا، {username}.<br />الصفحة تحديثات يسمح لك لتثبيت، إلغاء، ولتحديث المكونات المختلفة CIDRAM (حزمة الأساسية، التوقيعات، الملفات L10N، إلخ).';
$CIDRAM['lang']['title_accounts'] = 'CIDRAM – حسابات';
$CIDRAM['lang']['title_config'] = 'CIDRAM – التكوين';
$CIDRAM['lang']['title_home'] = 'CIDRAM – الرئيسية';
$CIDRAM['lang']['title_ip_test'] = 'CIDRAM – اختبار IP';
$CIDRAM['lang']['title_login'] = 'CIDRAM – تسجيل الدخول';
$CIDRAM['lang']['title_logs'] = 'CIDRAM – سجلات';
$CIDRAM['lang']['title_updates'] = 'CIDRAM – التحديثات';
