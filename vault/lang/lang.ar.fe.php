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
 * This file: Arabic language data for the front-end (last modified: 2017.01.23).
 */

/** Prevents execution from outside of CIDRAM. */
if (!defined('CIDRAM')) {
    die('[CIDRAM] This should not be accessed directly.');
}

$CIDRAM['lang']['bNav_home_logout'] = '<a href="?">الرئيسية</a> | <a href="?cidram-page=logout">خروج</a>';
$CIDRAM['lang']['bNav_logout'] = '<a href="?cidram-page=logout">خروج</a>';
$CIDRAM['lang']['config_general_ban_override'] = 'تجاوز "forbid_on_block" متى "infraction_limit" تجاوزت؟ عندما تجاوز: الطلبات الممنوعة بإرجاع صفحة فارغة (لا يتم استخدام ملفات قالب). 200 = لا تجاوز [الافتراضي]؛ 403 = تجاوز مع "403 Forbidden"; 503 = تجاوز مع "503 Service unavailable".';
$CIDRAM['lang']['config_general_default_dns'] = 'قائمة بفواصل من خوادم DNS لاستخدامها في عمليات البحث عن اسم المضيف. الافتراضي = "8.8.8.8,8.8.4.4" (Google DNS). تحذير: لا تغير هذا إلا إذا كنت تعرف ما تفعلونه!';
$CIDRAM['lang']['config_general_disable_cli'] = 'وضع تعطيل CLI؟';
$CIDRAM['lang']['config_general_disable_frontend'] = 'تعطيل وصول front-end؟';
$CIDRAM['lang']['config_general_emailaddr'] = 'عنوان البريد الإلكتروني للحصول على الدعم.';
$CIDRAM['lang']['config_general_forbid_on_block'] = 'الذي رؤوس ينبغي CIDRAM الرد عندما حظر طلبات؟';
$CIDRAM['lang']['config_general_FrontEndLog'] = 'ملف لتسجيل محاولات الدخول الأمامية. تحديد اسم الملف، أو اتركه فارغا لتعطيل.';
$CIDRAM['lang']['config_general_ipaddr'] = 'أين يمكن العثور على عنوان IP لربط الطلبات؟ (مفيدة للخدمات مثل لايتكلاود و مثلها). الافتراضي = REMOTE_ADDR. تحذير: لا تغير هذا إلا إذا كنت تعرف ما تفعلونه!';
$CIDRAM['lang']['config_general_lang'] = 'تحديد اللغة الافتراضية الخاصة بـ CIDRAM.';
$CIDRAM['lang']['config_general_logfile'] = 'ملف يمكن قراءته بالعين لتسجيل كل محاولات الوصول سدت. تحديد اسم الملف، أو اتركه فارغا لتعطيل.';
$CIDRAM['lang']['config_general_logfileApache'] = 'ملف على غرار أباتشي لتسجيل كل محاولات الوصول سدت. تحديد اسم الملف، أو اتركه فارغا لتعطيل.';
$CIDRAM['lang']['config_general_logfileSerialized'] = 'ملف تسلسل لتسجيل كل محاولات الوصول سدت. تحديد اسم الملف، أو اتركه فارغا لتعطيل.';
$CIDRAM['lang']['config_general_log_banned_ips'] = 'من IP المحظورة في ملفات السجل؟ True = نعم [افتراضي]; False = لا.';
$CIDRAM['lang']['config_general_max_login_attempts'] = 'الحد الأقصى لعدد محاولات تسجيل الدخول.';
$CIDRAM['lang']['config_general_silent_mode'] = 'يجب CIDRAM إعادة توجيه بصمت محاولات وصول مرفوض بدلا من عرض الصفحة "تم رفض الوصول"؟ اذا نعم، تحديد الموقع لإعادة توجيه محاولات وصول مرفوض. ان لم، ترك هذا الحقل فارغا.';
$CIDRAM['lang']['config_general_timeOffset'] = 'المنطقة الزمنية تعويض في غضون دقائق.';
$CIDRAM['lang']['config_recaptcha_expiry'] = 'عدد الساعات لنتذكر حالات اختبار reCAPTCHA.';
$CIDRAM['lang']['config_recaptcha_lockip'] = 'القفل reCAPTCHA إلى IP؟';
$CIDRAM['lang']['config_recaptcha_lockuser'] = 'القفل reCAPTCHA إلى المستخدمين؟';
$CIDRAM['lang']['config_recaptcha_logfile'] = 'تسجيل جميع محاولات اختبار reCAPTCHA؟ إذا كانت الإجابة بنعم، حدد اسم لاستخدامه في ملف السجل. ان لم، ترك هذا الحقل فارغا.';
$CIDRAM['lang']['config_recaptcha_secret'] = 'يجب أن تتطابق هذه القيمة إلى "secret key" لاختبار reCAPTCHA الخاص بك، التي يمكن العثور عليها داخل لوحة التحكم اختبار reCAPTCHA.';
$CIDRAM['lang']['config_recaptcha_sitekey'] = 'يجب أن تتطابق هذه القيمة إلى "site key" لاختبار reCAPTCHA الخاص بك، التي يمكن العثور عليها داخل لوحة التحكم اختبار reCAPTCHA.';
$CIDRAM['lang']['config_recaptcha_usemode'] = 'هذا ويعرف كيفية CIDRAM يجب استخدام اختبار reCAPTCHA (راجع وثائق).';
$CIDRAM['lang']['config_signatures_block_bogons'] = 'منع CIDRs المريخ/bogon؟ إذا كنت تتوقع اتصالات إلى موقع الويب الخاص بك من خلال الشبكة المحلية، هذا يجب أن يتم تعيين إلى false. ان لم، هذا يجب أن يتم تعيين إلى true.';
$CIDRAM['lang']['config_signatures_block_cloud'] = 'منع CIDRs التي تم تحديدها على أنها تنتمي إلى خدمات سحابية/الاستضافة؟ إذا كنت تعمل على خدمة API من موقع الويب الخاص بك، أو إذا كنت تتوقع مواقع أخرى للاتصال موقع الويب الخاص بك، هذا يجب أن يتم تعيين إلى false. إذا لم تقم بذلك، ثم، فإنه يجب تعيين إلى true.';
$CIDRAM['lang']['config_signatures_block_generic'] = 'منع CIDRs الموصى بها عموما للالقائمة السوداء؟ وهذا يشمل أي التوقيعات التي ليست جزءا من الفئات الأخرى.';
$CIDRAM['lang']['config_signatures_block_proxies'] = 'منع CIDRs التي تم تحديدها على أنها تنتمي إلى خدمات وكيل؟ إذا كنت تحتاج إلى أن يكون المستخدمون قادرين على الوصول إلى موقع الويب الخاص بك من خدمات بروكسي مجهول، هذا يجب أن يتم تعيين إلى false. ان لم، هذا يجب تعيين إلى true كوسيلة لتحسين الأمن.';
$CIDRAM['lang']['config_signatures_block_spam'] = 'منع CIDRs التي تم تحديدها على أنها مخاطر البريد المزعج؟ عندما يكون ذلك ممكنا، عموما، وهذا ينبغي دائما أن يتم تعيين إلى true.';
$CIDRAM['lang']['config_signatures_default_tracktime'] = 'كم ثانية لتعقب IP حظرت من قبل وحدات. افتراضي = 604800 (1 أسبوع).';
$CIDRAM['lang']['config_signatures_infraction_limit'] = 'يسمح الحد الأقصى لعدد المخالفات IP يمكن أن تتكبد قبل أن يتم حظره من قبل تتبع IP. افتراضي = 10.';
$CIDRAM['lang']['config_signatures_ipv4'] = 'وهناك قائمة من الملفات توقيع عناوين IPv4 التي CIDRAM يجب أن تحاول معالجة، مفصولة بفواصل.';
$CIDRAM['lang']['config_signatures_ipv6'] = 'وهناك قائمة من الملفات توقيع عناوين IPv6 التي CIDRAM يجب أن تحاول معالجة، مفصولة بفواصل.';
$CIDRAM['lang']['config_signatures_modules'] = 'قائمة الملفات وحدة لتحميل بعد التحقق من التوقيعات IPv4/IPv6، مفصولة بفواصل.';
$CIDRAM['lang']['config_signatures_track_mode'] = 'متى يجب أن تحسب المخالفات؟ False = عندما IP تم حظره من قبل وحدات. True = عندما IP يتم حظر لأي سبب من الأسباب.';
$CIDRAM['lang']['config_template_data_css_url'] = 'URL ملف CSS لمواضيع مخصصة.';
$CIDRAM['lang']['field_blocked'] = 'مسدود';
$CIDRAM['lang']['field_component'] = 'وحدة';
$CIDRAM['lang']['field_create_new_account'] = 'خلق جديد حساب';
$CIDRAM['lang']['field_delete_account'] = 'حذف حساب';
$CIDRAM['lang']['field_delete_file'] = 'حذف';
$CIDRAM['lang']['field_download_file'] = 'تحميل';
$CIDRAM['lang']['field_edit_file'] = 'تحرير';
$CIDRAM['lang']['field_file'] = 'ملف';
$CIDRAM['lang']['field_filename'] = 'اسم الملف: ';
$CIDRAM['lang']['field_filetype_directory'] = 'مجلد';
$CIDRAM['lang']['field_filetype_info'] = 'ملف {EXT}';
$CIDRAM['lang']['field_filetype_unknown'] = 'غير معروف';
$CIDRAM['lang']['field_install'] = 'تثبيت';
$CIDRAM['lang']['field_ip_address'] = 'عنوان IP';
$CIDRAM['lang']['field_latest_version'] = 'احدث اصدار';
$CIDRAM['lang']['field_log_in'] = 'تسجيل الدخول';
$CIDRAM['lang']['field_new_name'] = 'اسم جديد:';
$CIDRAM['lang']['field_ok'] = 'حسنا';
$CIDRAM['lang']['field_options'] = 'خيارات';
$CIDRAM['lang']['field_password'] = 'كلمه السر';
$CIDRAM['lang']['field_permissions'] = 'أذونات';
$CIDRAM['lang']['field_rename_file'] = 'إعادة تسمية';
$CIDRAM['lang']['field_reset'] = 'إعادة تعيين';
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
$CIDRAM['lang']['field_upload_file'] = 'تحميل ملف جديد';
$CIDRAM['lang']['field_username'] = 'اسم المستخدم';
$CIDRAM['lang']['field_your_version'] = 'الإصدار الخاص بك';
$CIDRAM['lang']['header_login'] = 'الرجاء تسجيل الدخول للمتابعة.';
$CIDRAM['lang']['link_accounts'] = 'حسابات';
$CIDRAM['lang']['link_config'] = 'التكوين';
$CIDRAM['lang']['link_documentation'] = 'توثيق';
$CIDRAM['lang']['link_file_manager'] = 'مدير الملفات';
$CIDRAM['lang']['link_home'] = 'الرئيسية';
$CIDRAM['lang']['link_ip_test'] = 'اختبار IP';
$CIDRAM['lang']['link_logs'] = 'سجلات';
$CIDRAM['lang']['link_updates'] = 'التحديثات';
$CIDRAM['lang']['logs_logfile_doesnt_exist'] = 'هذا سجل غير موجود!';
$CIDRAM['lang']['logs_no_logfiles_available'] = 'لا سجلات متاح.';
$CIDRAM['lang']['logs_no_logfile_selected'] = 'لا سجلات مختار.';
$CIDRAM['lang']['max_login_attempts_exceeded'] = 'تجاوز الحد الأقصى لعدد محاولات تسجيل الدخول؛ تم رفض الوصول.';
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
$CIDRAM['lang']['response_delete_error'] = 'فشلت في حذف!';
$CIDRAM['lang']['response_directory_deleted'] = 'دليل حذف بنجاح!';
$CIDRAM['lang']['response_directory_renamed'] = 'الدليل إعادة تسمية بنجاح!';
$CIDRAM['lang']['response_error'] = 'خطأ';
$CIDRAM['lang']['response_file_deleted'] = 'ملف حذف بنجاح!';
$CIDRAM['lang']['response_file_edited'] = 'ملف تعديل بنجاح!';
$CIDRAM['lang']['response_file_renamed'] = 'ملف إعادة تسمية بنجاح!';
$CIDRAM['lang']['response_file_uploaded'] = 'ملف تحميلها بنجاح!';
$CIDRAM['lang']['response_login_invalid_password'] = 'فشل تسجيل الدخول! غير صالحة كلمه السر!';
$CIDRAM['lang']['response_login_invalid_username'] = 'فشل تسجيل الدخول! اسم المستخدم غير موجود!';
$CIDRAM['lang']['response_login_password_field_empty'] = 'كلمه السر حقل فارغ!';
$CIDRAM['lang']['response_login_username_field_empty'] = 'اسم المستخدم حقل فارغ!';
$CIDRAM['lang']['response_no'] = 'لا';
$CIDRAM['lang']['response_rename_error'] = 'فشل في إعادة تسمية!';
$CIDRAM['lang']['response_updates_already_up_to_date'] = 'تحديث غير مطلوب.';
$CIDRAM['lang']['response_updates_not_installed'] = 'وحدة غير مثبت!';
$CIDRAM['lang']['response_updates_not_installed_php'] = 'وحدة غير مثبت (يتطلب PHP {V})!';
$CIDRAM['lang']['response_updates_outdated'] = 'عفا عليها الزمن!';
$CIDRAM['lang']['response_updates_outdated_manually'] = 'عفا عليها الزمن (يرجى تحديث يدويا)!';
$CIDRAM['lang']['response_updates_outdated_php_version'] = 'عفا عليها الزمن (يتطلب PHP {V})!';
$CIDRAM['lang']['response_updates_unable_to_determine'] = 'غير قادر على تحديد.';
$CIDRAM['lang']['response_upload_error'] = 'فشل لتحميل!';
$CIDRAM['lang']['response_yes'] = 'نعم';
$CIDRAM['lang']['state_complete_access'] = 'الوصول كامل';
$CIDRAM['lang']['state_component_is_active'] = 'وحدة هو نشطا.';
$CIDRAM['lang']['state_component_is_inactive'] = 'وحدة هو غير نشط.';
$CIDRAM['lang']['state_component_is_provisional'] = 'وحدة هو جزئيا نشطا.';
$CIDRAM['lang']['state_default_password'] = 'تحذير: يستخدم الافتراضي كلمه السر!';
$CIDRAM['lang']['state_logged_in'] = 'حاليا على.';
$CIDRAM['lang']['state_logs_access_only'] = 'سجلات الوصول فقط';
$CIDRAM['lang']['state_password_not_valid'] = 'تحذير: هذا الحساب لا يستخدم صالحة كلمه السر!';
$CIDRAM['lang']['switch-hide-non-outdated-set-false'] = 'لا يخفون غير عفا عليها الزمن';
$CIDRAM['lang']['switch-hide-non-outdated-set-true'] = 'يخفون غير عفا عليها الزمن';
$CIDRAM['lang']['switch-hide-unused-set-false'] = 'لا يخفون غير مستعمل';
$CIDRAM['lang']['switch-hide-unused-set-true'] = 'يخفون غير مستعمل';
$CIDRAM['lang']['tip_accounts'] = 'مرحبا، {username}.<br />الصفحة حسابات يسمح لك للسيطرة على الذي يمكن الوصول ألfront-end CIDRAM.';
$CIDRAM['lang']['tip_config'] = 'مرحبا، {username}.<br />الصفحة التكوين يسمح لك لتعديل التكوين CIDRAM عن طريق ألfront-end.';
$CIDRAM['lang']['tip_donate'] = 'CIDRAM يتم توفير مجانا، ولكن إذا كنت تريد التبرع للمشروع، يمكنك القيام بذلك عن طريق النقر على زر التبرع.';
$CIDRAM['lang']['tip_enter_ips_here'] = 'أدخل IPs هنا.';
$CIDRAM['lang']['tip_file_manager'] = 'مرحبا، {username}.<br />مدير الملفات يسمح لك لحذف، تعديل، وتحميل الملفات. استخدام بحذر (هل يمكن كسر التثبيت مع هذا).';
$CIDRAM['lang']['tip_home'] = 'مرحبا، {username}.<br />هذا هو الصفحة رئيسية ألfront-end CIDRAM. اختر ارتباط من قائمة التنقل على اليسار للمتابعة.';
$CIDRAM['lang']['tip_ip_test'] = 'مرحبا، {username}.<br />الصفحة اختبار IP يسمح لك لاختبار سواء عناوين IP مسدودة من التوقيعات المثبتة حاليا.';
$CIDRAM['lang']['tip_login'] = 'الافتراضي اسم المستخدم: <span class="txtRd">admin</span> – الافتراضي كلمه السر: <span class="txtRd">password</span>';
$CIDRAM['lang']['tip_logs'] = 'مرحبا، {username}.<br />اختار سجلات من القائمة أدناه لعرضها.';
$CIDRAM['lang']['tip_see_the_documentation'] = 'راجع <a href="https://github.com/Maikuolan/CIDRAM/blob/master/_docs/readme.ar.md#SECTION6">وثائق</a> للحصول على معلومات حول مختلف توجيهات التكوين ونيتهم.';
$CIDRAM['lang']['tip_updates'] = 'مرحبا، {username}.<br />الصفحة تحديثات يسمح لك لتثبيت، إلغاء، ولتحديث المكونات المختلفة CIDRAM (حزمة الأساسية، التوقيعات، الملفات L10N، إلخ).';
$CIDRAM['lang']['title_accounts'] = 'CIDRAM – حسابات';
$CIDRAM['lang']['title_config'] = 'CIDRAM – التكوين';
$CIDRAM['lang']['title_file_manager'] = 'CIDRAM – مدير الملفات';
$CIDRAM['lang']['title_home'] = 'CIDRAM – الرئيسية';
$CIDRAM['lang']['title_ip_test'] = 'CIDRAM – اختبار IP';
$CIDRAM['lang']['title_login'] = 'CIDRAM – تسجيل الدخول';
$CIDRAM['lang']['title_logs'] = 'CIDRAM – سجلات';
$CIDRAM['lang']['title_updates'] = 'CIDRAM – التحديثات';

$CIDRAM['lang']['info_some_useful_links'] = 'بعض الروابط المفيدة:<ul>
            <li><a href="https://github.com/Maikuolan/CIDRAM/issues" dir="ltr">CIDRAM Issues @ GitHub</a> – صفحة المشكلات لCIDRAM (الدعم والمساعدة، الخ).</li>
            <li><a href="http://www.spambotsecurity.com/forum/viewforum.php?f=61" dir="ltr">CIDRAM @ Spambot Security</a> – منتدى للنقاش ل CIDRAM (الدعم والمساعدة، الخ).</li>
            <li><a href="https://wordpress.org/plugins/cidram/" dir="ltr">CIDRAM @ Wordpress.org</a> – Wordpress البرنامج المساعد ل CIDRAM.</li>
            <li><a href="https://sourceforge.net/projects/cidram/" dir="ltr">CIDRAM @ SourceForge</a> – بديلة حمل مرآة للCIDRAM.</li>
            <li><a href="https://websectools.com/" dir="ltr">WebSecTools.com</a> – بعض الأدوات البسيطة ل جعل المواقع آمنة.</li>
            <li><a href="https://macmathan.info/blocklists" dir="ltr">MacMathan.info Range Blocks</a> – يحتوي على كتل مجموعة اختيارية التي يمكن أن تضاف إلى CIDRAM لمنع أي بلد غير المرغوب فيها من الوصول إلى موقع الويب الخاص بك.</li>
            <li><a href="https://www.facebook.com/groups/2204685680/" dir="ltr">International PHP Group @ Facebook</a> – PHP مصادر التعلم والمناقشة.</li>
            <li><a href="https://wwphp-fb.github.io/" dir="ltr">International PHP Group @ GitHub</a> – PHP مصادر التعلم والمناقشة.</li>
            <li><a href="http://bgp.he.net/" dir="ltr">Hurricane Electric BGP Toolkit</a> – الحصول على CIDRs من ل ASNs، تحديد العلاقات ASN، اكتشف ل ASNs استنادا إلى أسماء الشبكات، إلخ.</li>
            <li><a href="https://www.stopforumspam.com/forum/" dir="ltr">Forum @ Stop Forum Spam</a> – منتدى للنقاش مفيد حول وقف منتدى المزعج.</li>
            <li><a href="https://www.stopforumspam.com/aggregate" dir="ltr">IP Aggregator @ Stop Forum Spam</a> – أداة مفيدة لتجميع عناوين IPv4.</li>
            <li><a href="https://radar.qrator.net/" dir="ltr">Radar by Qrator</a> – أداة مفيدة للتحقق من الاتصال من ل ASNs فضلا عن العديد من المعلومات الأخرى حول ل ASNs.</li>
            <li><a href="http://www.ipdeny.com/ipblocks/" dir="ltr">IPdeny IP country blocks</a> – خدمة لتوليد التواقيع في جميع أنحاء البلاد.</li>
            <li><a href="https://www.google.com/transparencyreport/safebrowsing/malware/" dir="ltr">Google Malware Dashboard</a> – تقارير يعرض بخصوص معدلات الإصابة الخبيثة أجل ASNs.</li>
            <li><a href="https://www.spamhaus.org/statistics/botnet-asn/" dir="ltr">The Spamhaus Project</a> – تقارير يعرض بخصوص معدلات الإصابة الروبوتات أجل ASNs.</li>
            <li><a href="http://www.abuseat.org/asn.html" dir="ltr">Abuseat.org\'s Composite Blocking List</a> – تقارير يعرض بخصوص معدلات الإصابة الروبوتات أجل ASNs.</li>
            <li><a href="https://abuseipdb.com/">AbuseIPDB</a> – تحتفظ بقاعدة بيانات من عناوين IP المسيئة المعروفة؛ يوفر API لفحص والإبلاغ عناوين IP.</li>
            <li><a href="https://www.megarbl.net/index.php">MegaRBL.net</a> – يحافظ المعروضة من الاطر المعروفة؛ مفيدة لفحص أنشطة ASN/IP البريد المزعج.</li>
        </ul>';
