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
 * This file: Arabic language data for the front-end (last modified: 2017.08.16).
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
$CIDRAM['lang']['config_general_disable_webfonts'] = 'هل تريد تعطيل ويبفونتس؟ True = نعم؛ False = لا [افتراضي].';
$CIDRAM['lang']['config_general_emailaddr'] = 'لو كنت تريد، يمكنك توفير عنوان البريد الإلكتروني هنا أن تعطى للمستخدمين عند أنها ممنوعة، بالنسبة لهم لاستخدامها كنقطة اتصال للحصول على الدعم والمساعدة لفي حال منهم سدت طريق الخطأ أو في ضلال. تحذير: أي عنوان البريد الإلكتروني الذي تزويد هنا وبالتأكيد سيتم شراؤها من قبل المتطفلين و برامج التطفل وكاشطات خلال المستخدمة هنا، و حينئذ، انها المستحسن أن إذا اخترت توفير عنوان البريد الإلكتروني هنا، يمكنك التأكد من أن عنوان البريد الإلكتروني الذي نورد هنا يمكن التخلص منها و/أو عنوان أنك لا تمانع في أن محتوى غير مرغوب فيه (بعبارات أخرى، وربما كنت لا تريد استخدام الرئيسية عناوين البريد الإلكتروني التجارية أو العناوين الشخصية الرئيسية الخاصة بك).';
$CIDRAM['lang']['config_general_emailaddr_display_style'] = 'كيف تفضل أن يتم تقديم عنوان البريد الإلكتروني إلى المستخدمين؟';
$CIDRAM['lang']['config_general_forbid_on_block'] = 'الذي رؤوس ينبغي CIDRAM الرد عندما حظر طلبات؟';
$CIDRAM['lang']['config_general_FrontEndLog'] = 'ملف لتسجيل محاولات الدخول الأمامية. تحديد اسم الملف، أو اتركه فارغا لتعطيل.';
$CIDRAM['lang']['config_general_ipaddr'] = 'أين يمكن العثور على عنوان IP لربط الطلبات؟ (مفيدة للخدمات مثل لايتكلاود و مثلها). الافتراضي = REMOTE_ADDR. تحذير: لا تغير هذا إلا إذا كنت تعرف ما تفعلونه!';
$CIDRAM['lang']['config_general_lang'] = 'تحديد اللغة الافتراضية الخاصة بـ CIDRAM.';
$CIDRAM['lang']['config_general_logfile'] = 'ملف يمكن قراءته بالعين لتسجيل كل محاولات الوصول سدت. تحديد اسم الملف، أو اتركه فارغا لتعطيل.';
$CIDRAM['lang']['config_general_logfileApache'] = 'ملف على غرار أباتشي لتسجيل كل محاولات الوصول سدت. تحديد اسم الملف، أو اتركه فارغا لتعطيل.';
$CIDRAM['lang']['config_general_logfileSerialized'] = 'ملف تسلسل لتسجيل كل محاولات الوصول سدت. تحديد اسم الملف، أو اتركه فارغا لتعطيل.';
$CIDRAM['lang']['config_general_log_banned_ips'] = 'من IP المحظورة في ملفات السجل؟ True = نعم [افتراضي]؛ False = لا.';
$CIDRAM['lang']['config_general_max_login_attempts'] = 'الحد الأقصى لعدد محاولات تسجيل الدخول.';
$CIDRAM['lang']['config_general_numbers'] = 'كيف تفضل الأرقام ليتم عرضها؟ حدد المثال الذي يبدو أكثر صحيح لك.';
$CIDRAM['lang']['config_general_protect_frontend'] = 'يحدد ما إذا كانت الحماية التي توفرها عادة CIDRAM يجب أن تطبق الfront-end. True = نعم [افتراضي]؛ False = لا.';
$CIDRAM['lang']['config_general_search_engine_verification'] = 'محاولة للتحقق من طلبات من محركات البحث؟ التحقق من محركات البحث يضمن أنها لن تكون محظورة نتيجة لتجاوز الحد مخالفة (منع محركات البحث من موقع الويب الخاص بك عادة ما يكون لها تأثير سلبي على محرك البحث الترتيب، كبار المسئولين الاقتصاديين، إلخ). عند تمكين التحقق، محركات البحث يمكن أن يكون قد تم حظره، ولكن ليس محظورة. عند تعطيل التحقق، أنها يمكن أن تكون محظورة إذا تجاوزت الحد مخالفة. بالإضافة إلى، التحقق محرك البحث يحمي ضد الكيانات الخبيثة يتنكر في محركات البحث (سيتم حجب هذه الطلبات). True = تمكين التحقق محرك البحث [افتراضي]؛ False = تعطيل التحقق محرك البحث.';
$CIDRAM['lang']['config_general_silent_mode'] = 'يجب CIDRAM إعادة توجيه بصمت محاولات وصول مرفوض بدلا من عرض الصفحة "تم رفض الوصول"؟ اذا نعم، تحديد الموقع لإعادة توجيه محاولات وصول مرفوض. ان لم، ترك هذا الحقل فارغا.';
$CIDRAM['lang']['config_general_timeFormat'] = 'شكل التواريخ المستخدم من قبل CIDRAM. ويمكن إضافة خيارات إضافية عند الطلب.';
$CIDRAM['lang']['config_general_timeOffset'] = 'المنطقة الزمنية تعويض في غضون دقائق.';
$CIDRAM['lang']['config_general_timezone'] = 'المنطقة الزمنية.';
$CIDRAM['lang']['config_general_truncate'] = 'اقتطاع ملفات السجل عندما تصل إلى حجم معين؟ القيمة هي الحجم الأقصى في بايت/كيلوبايت/ميغابايت/غيغابايت/تيرابايت الذي قد ينمو ملفات السجل إلى قبل اقتطاعه. القيمة الافتراضية 0KB تعطيل اقتطاع (ملفات السجل يمكن أن تنمو إلى أجل غير مسمى). ملاحظة: ينطبق على ملفات السجل الفردية! ولا يعتبر حجمها جماعيا.';
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
$CIDRAM['lang']['config_template_data_Magnification'] = 'تكبير الخط. افتراضي = 1.';
$CIDRAM['lang']['config_template_data_theme'] = 'الموضوع الافتراضي لاستخدام CIDRAM.';
$CIDRAM['lang']['field_activate'] = 'جعله نشطة';
$CIDRAM['lang']['field_banned'] = 'محظور';
$CIDRAM['lang']['field_blocked'] = 'مسدود';
$CIDRAM['lang']['field_clear'] = 'إلغاء';
$CIDRAM['lang']['field_clickable_link'] = 'رابط قابل للنقر';
$CIDRAM['lang']['field_component'] = 'وحدة';
$CIDRAM['lang']['field_create_new_account'] = 'إنشاء حساب جديد';
$CIDRAM['lang']['field_deactivate'] = 'جعلها غير نشطة';
$CIDRAM['lang']['field_delete_account'] = 'حذف حساب';
$CIDRAM['lang']['field_delete_file'] = 'حذف';
$CIDRAM['lang']['field_download_file'] = 'تحميل';
$CIDRAM['lang']['field_edit_file'] = 'تحرير';
$CIDRAM['lang']['field_expiry'] = 'انقضاء';
$CIDRAM['lang']['field_false'] = 'False (خاطئة)';
$CIDRAM['lang']['field_file'] = 'ملف';
$CIDRAM['lang']['field_filename'] = 'اسم الملف: ';
$CIDRAM['lang']['field_filetype_directory'] = 'مجلد';
$CIDRAM['lang']['field_filetype_info'] = 'ملف {EXT}';
$CIDRAM['lang']['field_filetype_unknown'] = 'غير معروف';
$CIDRAM['lang']['field_first_seen'] = 'الروية الأولى';
$CIDRAM['lang']['field_infractions'] = 'مخالفات';
$CIDRAM['lang']['field_install'] = 'تثبيت';
$CIDRAM['lang']['field_ip_address'] = 'عنوان IP';
$CIDRAM['lang']['field_latest_version'] = 'احدث اصدار';
$CIDRAM['lang']['field_log_in'] = 'تسجيل الدخول';
$CIDRAM['lang']['field_new_name'] = 'اسم جديد:';
$CIDRAM['lang']['field_nonclickable_text'] = 'نص غير قابل للنقر';
$CIDRAM['lang']['field_ok'] = 'حسنا';
$CIDRAM['lang']['field_options'] = 'خيارات';
$CIDRAM['lang']['field_password'] = 'كلمه السر';
$CIDRAM['lang']['field_permissions'] = 'أذونات';
$CIDRAM['lang']['field_range'] = 'نطاق (الأول – الاخير)';
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
$CIDRAM['lang']['field_system_timezone'] = 'استخدام المنطقة الزمنية الافتراضية للنظام.';
$CIDRAM['lang']['field_tracking'] = 'التتبع';
$CIDRAM['lang']['field_true'] = 'True (صحيح)';
$CIDRAM['lang']['field_uninstall'] = 'الغاء التثبيت';
$CIDRAM['lang']['field_update'] = 'تحديث';
$CIDRAM['lang']['field_update_all'] = 'تحديث الجميع';
$CIDRAM['lang']['field_upload_file'] = 'تحميل ملف جديد';
$CIDRAM['lang']['field_username'] = 'اسم المستخدم';
$CIDRAM['lang']['field_your_version'] = 'الإصدار الخاص بك';
$CIDRAM['lang']['header_login'] = 'الرجاء تسجيل الدخول للمتابعة.';
$CIDRAM['lang']['label_active_config_file'] = 'ملف التكوين النشط: ';
$CIDRAM['lang']['label_branch'] = 'فرع أحدث مستقرة:';
$CIDRAM['lang']['label_cidram'] = 'النسخة CIDRAM المستخدمة:';
$CIDRAM['lang']['label_false_positive_risk'] = 'خطر إيجابية كاذبة: ';
$CIDRAM['lang']['label_os'] = 'نظام التشغيل المستخدمة:';
$CIDRAM['lang']['label_php'] = 'النسخة PHP المستخدمة:';
$CIDRAM['lang']['label_sapi'] = 'SAPI المستخدمة:';
$CIDRAM['lang']['label_stable'] = 'أحدث مستقرة:';
$CIDRAM['lang']['label_sysinfo'] = 'معلومات النظام:';
$CIDRAM['lang']['label_unstable'] = 'أحدث غير مستقرة:';
$CIDRAM['lang']['link_accounts'] = 'حسابات';
$CIDRAM['lang']['link_cidr_calc'] = 'CIDR حاسبة';
$CIDRAM['lang']['link_config'] = 'التكوين';
$CIDRAM['lang']['link_documentation'] = 'توثيق';
$CIDRAM['lang']['link_file_manager'] = 'مدير الملفات';
$CIDRAM['lang']['link_home'] = 'الرئيسية';
$CIDRAM['lang']['link_ip_test'] = 'اختبار IP';
$CIDRAM['lang']['link_ip_tracking'] = 'التتبع IP';
$CIDRAM['lang']['link_logs'] = 'سجلات';
$CIDRAM['lang']['link_updates'] = 'التحديثات';
$CIDRAM['lang']['logs_logfile_doesnt_exist'] = 'هذا سجل غير موجود!';
$CIDRAM['lang']['logs_no_logfiles_available'] = 'لا سجلات متاح.';
$CIDRAM['lang']['logs_no_logfile_selected'] = 'لا سجلات مختار.';
$CIDRAM['lang']['max_login_attempts_exceeded'] = 'تجاوز الحد الأقصى لعدد محاولات تسجيل الدخول؛ تم رفض الوصول.';
$CIDRAM['lang']['previewer_days'] = 'أيام';
$CIDRAM['lang']['previewer_hours'] = 'ساعات';
$CIDRAM['lang']['previewer_minutes'] = 'الدقائق';
$CIDRAM['lang']['previewer_months'] = 'الشهور';
$CIDRAM['lang']['previewer_seconds'] = 'ثواني';
$CIDRAM['lang']['previewer_weeks'] = 'أسابيع';
$CIDRAM['lang']['previewer_years'] = 'سنوات';
$CIDRAM['lang']['response_accounts_already_exists'] = 'اسم المستخدم موجود بالفعل!';
$CIDRAM['lang']['response_accounts_created'] = 'تم انشاء الحساب بنجاح!';
$CIDRAM['lang']['response_accounts_deleted'] = 'تم حذف الحساب بنجاح!';
$CIDRAM['lang']['response_accounts_doesnt_exist'] = 'حساب غير موجود.';
$CIDRAM['lang']['response_accounts_password_updated'] = 'تم تحديث كلمه السر بنجاح!';
$CIDRAM['lang']['response_activated'] = 'نجحت في جعل نشطة';
$CIDRAM['lang']['response_activation_failed'] = 'فشلت في جعله نشطة!';
$CIDRAM['lang']['response_checksum_error'] = 'خطأ أختباري! تم رفض الملف!';
$CIDRAM['lang']['response_component_successfully_installed'] = 'تم تثبيت الوحدة بنجاح';
$CIDRAM['lang']['response_component_successfully_uninstalled'] = 'تم الغاء تثبيت الوحدة بنجاح';
$CIDRAM['lang']['response_component_successfully_updated'] = 'تم تحديث الوحدة بنجاح';
$CIDRAM['lang']['response_component_uninstall_error'] = 'حدث خطأ أثناء محاولة إلغاء تثبيت الوحدة.';
$CIDRAM['lang']['response_component_update_error'] = 'حدث خطأ أثناء محاولة تحديث الوحدة.';
$CIDRAM['lang']['response_configuration_updated'] = 'تم تحديث التكوين بنجاح';
$CIDRAM['lang']['response_deactivated'] = 'نجحت في جعل غير نشطة';
$CIDRAM['lang']['response_deactivation_failed'] = 'فشلت في جعله غير نشطة!';
$CIDRAM['lang']['response_delete_error'] = 'فشلت في حذف!';
$CIDRAM['lang']['response_directory_deleted'] = 'تم حذف الدليل بنجاح!';
$CIDRAM['lang']['response_directory_renamed'] = 'تم اعادة تسمية الدليل بنجاح!';
$CIDRAM['lang']['response_error'] = 'خطأ';
$CIDRAM['lang']['response_file_deleted'] = 'ملف حذف بنجاح!';
$CIDRAM['lang']['response_file_edited'] = 'ملف تعديل بنجاح!';
$CIDRAM['lang']['response_file_renamed'] = 'ملف إعادة تسمية بنجاح!';
$CIDRAM['lang']['response_file_uploaded'] = 'ملف تحميلها بنجاح!';
$CIDRAM['lang']['response_login_invalid_password'] = 'فشل تسجيل الدخول! كلمة السر غير صالحة!';
$CIDRAM['lang']['response_login_invalid_username'] = 'فشل تسجيل الدخول! اسم المستخدم غير موجود!';
$CIDRAM['lang']['response_login_password_field_empty'] = 'كلمه السر حقل فارغ!';
$CIDRAM['lang']['response_login_username_field_empty'] = 'اسم المستخدم حقل فارغ!';
$CIDRAM['lang']['response_no'] = 'لا';
$CIDRAM['lang']['response_rename_error'] = 'فشل في إعادة تسمية!';
$CIDRAM['lang']['response_tracking_cleared'] = 'التتبع ألغيت.';
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
$CIDRAM['lang']['state_component_is_active'] = 'وحدة نشطة.';
$CIDRAM['lang']['state_component_is_inactive'] = 'وحدة غير نشطة.';
$CIDRAM['lang']['state_component_is_provisional'] = 'وحدة نشطة جزئيا.';
$CIDRAM['lang']['state_default_password'] = 'تحذير: يستخدم الافتراضي كلمه السر!';
$CIDRAM['lang']['state_loadtime'] = 'اكتمل طلب الصفحة خلال <span class="txtRd">%s</span> ثوان.';
$CIDRAM['lang']['state_logged_in'] = 'حاليا على.';
$CIDRAM['lang']['state_logs_access_only'] = 'سجلات الوصول فقط';
$CIDRAM['lang']['state_password_not_valid'] = ' تحذير: هذا الحساب لا يستخدم كلمه السر صالحة !';
$CIDRAM['lang']['state_risk_high'] = 'عالية';
$CIDRAM['lang']['state_risk_low'] = 'قليل';
$CIDRAM['lang']['state_risk_medium'] = 'متوسطة';
$CIDRAM['lang']['state_tracking'] = 'تتبع حاليا <span class="txtRd">%s</span> عناوين IP.';
$CIDRAM['lang']['switch-hide-non-outdated-set-false'] = 'لا يخفون غير عفا عليها الزمن';
$CIDRAM['lang']['switch-hide-non-outdated-set-true'] = 'يخفون غير عفا عليها الزمن';
$CIDRAM['lang']['switch-hide-unused-set-false'] = 'لا يخفون غير مستعمل';
$CIDRAM['lang']['switch-hide-unused-set-true'] = 'يخفون غير مستعمل';
$CIDRAM['lang']['tip_accounts'] = 'مرحبا، {username}.<br />الصفحة حسابات يسمح لك للسيطرة على الذي يمكن الوصول ألfront-end CIDRAM.';
$CIDRAM['lang']['tip_cidr_calc'] = 'مرحبا، {username}.<br />آلة حاسبة CIDR يسمح لك لحساب CIDRs حيث عنوان IP هو عامل.';
$CIDRAM['lang']['tip_config'] = 'مرحبا، {username}.<br />الصفحة التكوين يسمح لك لتعديل التكوين CIDRAM عن طريق ألfront-end.';
$CIDRAM['lang']['tip_donate'] = 'CIDRAM يتم توفير مجانا، ولكن إذا كنت تريد التبرع للمشروع، يمكنك القيام بذلك عن طريق النقر على زر التبرع.';
$CIDRAM['lang']['tip_enter_ips_here'] = 'أدخل IPs هنا.';
$CIDRAM['lang']['tip_enter_ip_here'] = 'أدخل IP هنا.';
$CIDRAM['lang']['tip_file_manager'] = 'مرحبا، {username}.<br />مدير الملفات يسمح لك لحذف، تعديل، وتحميل الملفات. استخدام بحذر (هل يمكن كسر التثبيت مع هذا).';
$CIDRAM['lang']['tip_home'] = 'مرحبا، {username}.<br />هذا هو الصفحة رئيسية ألfront-end CIDRAM. اختر ارتباط من قائمة التنقل على اليمين للمتابعة.';
$CIDRAM['lang']['tip_ip_test'] = 'مرحبا، {username}.<br />الصفحة اختبار IP يسمح لك لاختبار سواء عناوين IP مسدودة من التوقيعات المثبتة حاليا.';
$CIDRAM['lang']['tip_ip_tracking'] = 'مرحبا، {username}.<br />التتبع IP يسمح لك للتحقق من حالة تتبع عناوين IP، تحقق محظورة، و إلغاء تتبع إذا كنت تريد أن تفعل ذلك.';
$CIDRAM['lang']['tip_login'] = 'الافتراضي اسم المستخدم: <span class="txtRd">admin</span> – الافتراضي كلمه السر: <span class="txtRd">password</span>';
$CIDRAM['lang']['tip_logs'] = 'مرحبا، {username}.<br />اختار سجلات من القائمة أدناه لعرضها.';
$CIDRAM['lang']['tip_see_the_documentation'] = 'راجع <a href="https://github.com/CIDRAM/CIDRAM/blob/master/_docs/readme.ar.md#SECTION6">وثائق</a> للحصول على معلومات حول مختلف توجيهات التكوين ونيتهم.';
$CIDRAM['lang']['tip_updates'] = 'مرحبا، {username}.<br />الصفحة تحديثات يسمح لك لتثبيت، إلغاء، ولتحديث المكونات المختلفة CIDRAM (حزمة الأساسية، التوقيعات، الملفات L10N، إلخ).';
$CIDRAM['lang']['title_accounts'] = 'CIDRAM – حسابات';
$CIDRAM['lang']['title_cidr_calc'] = 'CIDRAM – CIDR حاسبة';
$CIDRAM['lang']['title_config'] = 'CIDRAM – التكوين';
$CIDRAM['lang']['title_file_manager'] = 'CIDRAM – مدير الملفات';
$CIDRAM['lang']['title_home'] = 'CIDRAM – الرئيسية';
$CIDRAM['lang']['title_ip_test'] = 'CIDRAM – اختبار IP';
$CIDRAM['lang']['title_ip_tracking'] = 'CIDRAM – التتبع IP';
$CIDRAM['lang']['title_login'] = 'CIDRAM – تسجيل الدخول';
$CIDRAM['lang']['title_logs'] = 'CIDRAM – سجلات';
$CIDRAM['lang']['title_updates'] = 'CIDRAM – التحديثات';
$CIDRAM['lang']['warning'] = 'تحذيرات:';
$CIDRAM['lang']['warning_php_1'] = 'لم يتم دعم إصدار PHP الخاص بك بشكل نشط بعد الآن! يوصى بالتحديث!';
$CIDRAM['lang']['warning_php_2'] = 'إصدار PHP الخاص بك معرض للخطر بشدة! ينصح بشدة تحديث!';
$CIDRAM['lang']['warning_signatures_1'] = 'لا ملفات التوقيع نشطة!';

$CIDRAM['lang']['info_some_useful_links'] = 'بعض الروابط المفيدة:<ul>
            <li><a href="https://github.com/CIDRAM/CIDRAM/issues" dir="ltr">CIDRAM Issues @ GitHub</a> – صفحة المشكلات لCIDRAM (الدعم والمساعدة، الخ).</li>
            <li><a href="http://www.spambotsecurity.com/forum/viewforum.php?f=61" dir="ltr">CIDRAM @ Spambot Security</a> – منتدى للنقاش ل CIDRAM (الدعم والمساعدة، الخ).</li>
            <li><a href="https://wordpress.org/plugins/cidram/" dir="ltr">CIDRAM @ WordPress.org</a> – WordPress البرنامج المساعد ل CIDRAM.</li>
            <li><a href="https://sourceforge.net/projects/cidram/" dir="ltr">CIDRAM @ SourceForge</a> – بديلة حمل مرآة للCIDRAM.</li>
            <li><a href="https://websectools.com/" dir="ltr">WebSecTools.com</a> – بعض الأدوات البسيطة ل جعل المواقع آمنة.</li>
            <li><a href="https://macmathan.info/blocklists" dir="ltr">MacMathan.info Range Blocks</a> – يحتوي على كتل مجموعة اختيارية التي يمكن أن تضاف إلى CIDRAM لمنع أي بلد غير المرغوب فيها من الوصول إلى موقع الويب الخاص بك.</li>
            <li><a href="https://www.facebook.com/groups/2204685680/" dir="ltr">Global PHP Group @ Facebook</a> – PHP مصادر التعلم والمناقشة.</li>
            <li><a href="https://php.earth/" dir="ltr">PHP.earth</a> – PHP مصادر التعلم والمناقشة.</li>
            <li><a href="http://bgp.he.net/" dir="ltr">Hurricane Electric BGP Toolkit</a> – الحصول على CIDRs من ل ASNs، تحديد العلاقات ASN، اكتشف ل ASNs استنادا إلى أسماء الشبكات، إلخ.</li>
            <li><a href="https://www.stopforumspam.com/forum/" dir="ltr">Forum @ Stop Forum Spam</a> – منتدى للنقاش مفيد حول وقف منتدى المزعج.</li>
            <li><a href="https://www.stopforumspam.com/aggregate" dir="ltr">IP Aggregator @ Stop Forum Spam</a> – أداة مفيدة لتجميع عناوين IPv4.</li>
            <li><a href="https://radar.qrator.net/" dir="ltr">Radar by Qrator</a> – أداة مفيدة للتحقق من الاتصال من ل ASNs فضلا عن العديد من المعلومات الأخرى حول ل ASNs.</li>
            <li><a href="http://www.ipdeny.com/ipblocks/" dir="ltr">IPdeny IP country blocks</a> – خدمة لتوليد التواقيع في جميع أنحاء البلاد.</li>
            <li><a href="https://www.google.com/transparencyreport/safebrowsing/malware/" dir="ltr">Google Malware Dashboard</a> – تقارير يعرض بخصوص معدلات الإصابة الخبيثة أجل ASNs.</li>
            <li><a href="https://www.spamhaus.org/statistics/botnet-asn/" dir="ltr">The Spamhaus Project</a> – تقارير يعرض بخصوص معدلات الإصابة الروبوتات أجل ASNs.</li>
            <li><a href="https://www.abuseat.org/public/asn.html" dir="ltr">Abuseat.org\'s Composite Blocking List</a> – تقارير يعرض بخصوص معدلات الإصابة الروبوتات أجل ASNs.</li>
            <li><a href="https://abuseipdb.com/" dir="ltr">AbuseIPDB</a> – تحتفظ بقاعدة بيانات من عناوين IP المسيئة المعروفة؛ يوفر API لفحص والإبلاغ عناوين IP.</li>
            <li><a href="https://www.megarbl.net/index.php" dir="ltr">MegaRBL.net</a> – يحافظ المعروضة من الاطر المعروفة؛ مفيدة لفحص أنشطة ASN/IP البريد المزعج.</li>
        </ul>';
