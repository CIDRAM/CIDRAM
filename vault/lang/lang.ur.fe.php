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
 * This file: Urdu language data for the front-end (last modified: 2017.05.19).
 */

/** Prevents execution from outside of CIDRAM. */
if (!defined('CIDRAM')) {
    die('[CIDRAM] This should not be accessed directly.');
}

$CIDRAM['lang']['bNav_home_logout'] = '<a href="?">ہوم</a> | <a href="?cidram-page=logout">لاگ آوٹ</a>';
$CIDRAM['lang']['bNav_logout'] = '<a href="?cidram-page=logout">لاگ آوٹ</a>';
$CIDRAM['lang']['config_general_ban_override'] = 'کی منسوخی "forbid_on_block" کب "infraction_limit" حد سے تجاوز کر رہا ہے? زیرکر کب: التواء درخواستوں ایک خالی صفحے کو واپس (سانچے فائلوں کا استعمال نہیں کر رہے ہیں). 200 = کی جگہ لے لے نہیں ہے [طے شدہ]؛ کے ساتھ "403 حرام" 403 = جگہ لے لے؛ کے ساتھ "503 سروس دستیاب نہیں 503 = زیر کریں".';
$CIDRAM['lang']['config_general_default_dns'] = 'میزبان نام لک اپ کے لئے استعمال کرنے کے لئے DNS سرورز کی کوما ختم ہونے والی فہرست. پہلے سے طے شدہ = "8.8.8.8,8.8.4.4" (Google DNS). انتباہ: جب تک کہ آپ کو پتہ ہے تم کیا کر رہے ہو اس کو تبدیل نہ کریں!';
$CIDRAM['lang']['config_general_disable_cli'] = 'غیر فعال کریں CLI موڈ?';
$CIDRAM['lang']['config_general_disable_frontend'] = 'سامنے کے آخر تک رسائی کو غیر فعال کریں?';
$CIDRAM['lang']['config_general_disable_webfonts'] = 'غیر فعال ویب فونٹس? سچے = جی ہاں; جھوٹی = نہیں [پہلے سے طے شدہ].';
$CIDRAM['lang']['config_general_emailaddr'] = 'کی حمایت کے لئے ای میل ایڈریس.';
$CIDRAM['lang']['config_general_forbid_on_block'] = 'کون ہیڈرز جب مسدود کرنے کی درخواستوں کے ساتھ جواب CIDRAM چاہئے?';
$CIDRAM['lang']['config_general_FrontEndLog'] = 'سامنے کے آخر میں لاگ ان کوششوں لاگنگ کے لئے دائر. ایک فائل کا نام کی وضاحت کریں، یا غیر فعال کرنے کو خالی چھوڑ.';
$CIDRAM['lang']['config_general_ipaddr'] = 'کہاں درخواستوں منسلک کرنے کے IP ایڈریس کو تلاش کرنے کے لئے؟ (جیسا CloudFlare کے طور پر خدمات اور پسند کرتا ہے کے لئے مفید). پہلے سے طے شدہ = REMOTE_ADDR. WARNING: جب تک کہ آپ کو پتہ ہے تم کیا کر رہے ہو اس کو تبدیل نہ کریں!';
$CIDRAM['lang']['config_general_lang'] = 'CIDRAM لئے پہلے سے طے شدہ زبان کی وضاحت.';
$CIDRAM['lang']['config_general_logfile'] = 'تمام بلاک کر تک رسائی کی کوششوں کو لاگ ان کرنے کے لئے انسانی قابل مطالعہ فائل. ایک فائل کا نام کی وضاحت کریں، یا غیر فعال کرنے کو خالی چھوڑ.';
$CIDRAM['lang']['config_general_logfileApache'] = 'تمام بلاک کر تک رسائی کی کوششوں کو لاگ ان کرنے کے لئے اپاچی طرز فائل. ایک فائل کا نام کی وضاحت کریں، یا غیر فعال کرنے کو خالی چھوڑ.';
$CIDRAM['lang']['config_general_logfileSerialized'] = 'تمام بلاک کر تک رسائی کی کوششوں کو لاگ ان کرنے کے لئے serialized کی فائل. ایک فائل کا نام کی وضاحت کریں، یا غیر فعال کرنے کو خالی چھوڑ.';
$CIDRAM['lang']['config_general_log_banned_ips'] = 'لاگ مسلیں میں کالعدم آئی پی ایس سے مسدود درخواستوں شامل کریں? سچے = جی ہاں [پہلے سے طے شدہ]; جھوٹی = نہیں.';
$CIDRAM['lang']['config_general_max_login_attempts'] = 'لاگ ان کوششوں کی زیادہ سے زیادہ تعداد.';
$CIDRAM['lang']['config_general_protect_frontend'] = 'متعین کرتا ہے جو عام طور پر CIDRAM طرف سے فراہم کردہ تحفظات سامنے کے آخر پر لاگو کیا جانا چاہئے کہ آیا. سچے = جی ہاں [پہلے سے طے شدہ]; جھوٹی = نہیں.';
$CIDRAM['lang']['config_general_search_engine_verification'] = 'تلاش کے انجن کی طرف سے درخواستوں کی تصدیق کرنے کی کوشش؟ تلاش کے انجن کی توثیق کرنے سے کہ وہ خلاف ورزی کی حد (ویب سائٹ سے تلاش کے انجن پر پابندی عائد عام طور پر آپ کی تلاش کے انجن کی درجہ بندی، SEO، وغیرہ پر منفی اثر پڑے گا) تجاوز کا ایک نتیجہ کے طور پر پابندی عائد نہیں کیا جائے گا یقینی بناتا ہے. تصدیق کی جب، تلاش کے انجن معمول فی کے طور پر بلاک کیا جا سکتا ہے، لیکن پابندی عائد نہیں کی جائے گی. کی توثیق نہیں کی ہے، تو یہ ان کے لئے خلاف ورزی کی حد سے تجاوز کرنے کے نتیجے کے طور پر پابندی عائد کی جائے کرنے کے لئے ممکن ہے. اس کے علاوہ، تلاش کے انجن کی توثیق کی جعلی تلاش کے انجن کی درخواستوں کے خلاف اور (اس طرح کی درخواستوں کی تلاش کے انجن کی توثیق فعال ہے جب بلاک کر دیا جائے گا) سرچ انجن کے طور پر ویش ممکنہ طور پر بدنیتی پر مبنی اداروں کے خلاف تحفظ فراہم کرتا ہے. سچے = تلاش کے انجن کی توثیق فعال [پہلے سے طے شدہ]; جھوٹی = غیر فعال تلاش کے انجن کی توثیق کی.';
$CIDRAM['lang']['config_general_silent_mode'] = 'خاموشی CIDRAM چاہئے "رسائی نہیں ہوئی" کے صفحے کی نمائش سے بلاک رسائی کی کوششوں کو ری ڈائریکٹ کرنے کے بجائے؟ ہاں تو، کو بلاک کر تک رسائی کی کوششوں کو ری ڈائریکٹ کرنے کے محل وقوع کی وضاحت. کوئی تو اس متغیر خالی چھوڑ.';
$CIDRAM['lang']['config_general_timeFormat'] = 'CIDRAM کی طرف سے استعمال کی تاریخوں کا فارم. اضافی اختیارات درخواست پر شامل کیا جا سکتا ہے.';
$CIDRAM['lang']['config_general_timeOffset'] = 'ٹائم زون منٹ میں آفسیٹ.';
$CIDRAM['lang']['config_general_timezone'] = 'آپ کے ٹائم زون.';
$CIDRAM['lang']['config_general_truncate'] = 'وہ ایک خاص سائز تک پہنچنے میں جب صاف لاگ مسلیں؟ ویلیو میں B/KB/MB/GB/TB زیادہ سے زیادہ سائز ہے. جب 0KB، وہ غیر معینہ مدت تک ترقی کر سکتا ہے (پہلے سے طے). نوٹ: واحد فائلوں پر لاگو ہوتا ہے! فائلیں اجتماعی غور نہیں کر رہے ہیں.';
$CIDRAM['lang']['config_recaptcha_expiry'] = 'گھنٹوں کی تعداد reCAPTCHA کے واقعات کو یاد کرنے.';
$CIDRAM['lang']['config_recaptcha_lockip'] = 'ئی پی ایس کے لئے ہیتی لاک؟';
$CIDRAM['lang']['config_recaptcha_lockuser'] = 'صارفین کے لئے ہیتی لاک؟';
$CIDRAM['lang']['config_recaptcha_logfile'] = 'تمام reCAPTCHA کے کوششوں لاگ؟ اگر ہاں، logfile پر کے لئے استعمال کرنے کا نام کی وضاحت. کوئی تو اس متغیر خالی چھوڑ دیں.';
$CIDRAM['lang']['config_recaptcha_secret'] = '"secret key" کے طور پر ایک ہی ہونا چاہئے. یہ reCAPTCHA ڈیش بورڈ میں پایا جا سکتا ہے.';
$CIDRAM['lang']['config_recaptcha_sitekey'] = '"site key" کے طور پر ایک ہی ہونا چاہئے. یہ reCAPTCHA ڈیش بورڈ میں پایا جا سکتا ہے.';
$CIDRAM['lang']['config_recaptcha_usemode'] = 'CIDRAM reCAPTCHA کے استعمال کرنا چاہئے کس طرح کی وضاحت (دستاویزات دیکھیں).';
$CIDRAM['lang']['config_signatures_block_bogons'] = 'بلاک bogon/martian کی CIDRs؟ آپ لوکل ہوسٹ سے، یا اپنے LAN سے اپنے مقامی نیٹ ورک کے اندر سے اپنی ویب سائٹ پر کنکشن، توقع ہے، اس ہدایت کے جھوٹے پر مقرر کیا جائے چاہئے. اگر آپ ان میں ایسے کنکشنوں کی توقع نہیں ہے، تو اس ہدایت صحیح پر سیٹ کیا جانا چاہئے.';
$CIDRAM['lang']['config_signatures_block_cloud'] = 'بلاک CIDRs webhosting کے / کلاؤڈ سروسز سے تعلق رکھنے والے کے طور پر شناخت؟ آپ کو آپ کی ویب سائٹ سے ایک API سروس آپریٹ یا اگر آپ دوسری ویب سائٹس کو اپنی ویب سائٹ سے رابطہ قائم کرنے کی توقع ہے تو، تو اس جھوٹے کے لئے مقرر کیا جانا چاہئے. آپ ایسا نہیں کرتے، تو پھر، اس ہدایت صحیح پر سیٹ کیا جانا چاہئے.';
$CIDRAM['lang']['config_signatures_block_generic'] = 'بلاک CIDRs عام طور پر کی blacklisting لئے سفارش؟ یہ دیگر زیادہ مخصوص دستخط کی اقسام میں سے کسی کا حصہ ہونے کے طور پر نشان نہیں ہیں کہ کسی بھی دستخطوں کا احاطہ کرتا ہے.';
$CIDRAM['lang']['config_signatures_block_proxies'] = 'بلاک CIDRs پراکسی خدمات سے تعلق رکھنے والے کے طور پر شناخت؟ آپ صارفین گمنام پراکسی خدمات سے آپ کی ویب سائٹ تک رسائی حاصل کرنے کے قابل ہو جائے کی ضرورت ہوتی ہے تو، اس کے جھوٹے پر مقرر کیا جائے چاہئے. دوسری صورت میں، آپ کو گمنام پراکسی جنگ لڑ کی ضرورت نہیں ہے تو، اس ہدایت سچ کو بہتر بنانے کی سیکورٹی کا ایک ذریعہ کے طور پر مقرر کیا جائے چاہئے.';
$CIDRAM['lang']['config_signatures_block_spam'] = 'بلاک CIDRs سپیم کے لئے اعلی خطرے ہونے کے طور پر شناخت کیا؟ ایسا کرنے جب آپ کو مسائل کا سامنا ہوتا ہے جب تک، عام طور پر، یہ ہمیشہ سچ کے لئے مقرر کیا جانا چاہئے.';
$CIDRAM['lang']['config_signatures_default_tracktime'] = 'ماڈیولز کی طرف سے پابندی لگا دی آئی پی ایس کے ٹریک کرنے سیکنڈ کتنے. پہلے سے طے شدہ = 604800 (1 ہفتہ).';
$CIDRAM['lang']['config_signatures_infraction_limit'] = 'انحرافات کی زیادہ سے زیادہ تعداد ایک IP اس سے پہلے کیا جاتا ہے IP باخبر رہنے کے کی طرف سے پابندی کا اطلاق کرنے کی اجازت ہے. پہلے سے طے شدہ = 10.';
$CIDRAM['lang']['config_signatures_ipv4'] = 'IPv4 کی دستخط کی ایک فہرست فائلوں کہ CIDRAM، کا تجزیہ کرنے کی کوشش کرنا چاہئے کوما سے ختم ہونے والی.';
$CIDRAM['lang']['config_signatures_ipv6'] = 'IPv6 کی دستخط کی ایک فہرست فائلوں کہ CIDRAM، کا تجزیہ کرنے کی کوشش کرنا چاہئے کوما سے ختم ہونے والی.';
$CIDRAM['lang']['config_signatures_modules'] = 'ماڈیول فائلوں کی ایک فہرست کوما سے ختم ہونے والی، IPv4/IPv6 دستخط جانچ پڑتال کے بعد لوڈ کرنے کے لئے.';
$CIDRAM['lang']['config_signatures_track_mode'] = 'انحرافات شمار کب کیا جانا چاہئے؟ جھوٹی = آئی پی ایس کے ماڈیول کی طرف سے بلاک کر رہے ہیں جب. سچا = آئی پی ایس کے کسی بھی وجہ سے بلاک کر رہے ہیں جب.';
$CIDRAM['lang']['config_template_data_css_url'] = 'اپنی مرضی کے موضوعات کے لئے سی ایس ایس فائل URL.';
$CIDRAM['lang']['config_template_data_theme'] = 'CIDRAM لئے استعمال کرنے کے لئے مرکزی خیال، موضوع پہلے سے طے شدہ.';
$CIDRAM['lang']['field_activate'] = 'فعال کریں';
$CIDRAM['lang']['field_banned'] = 'کالعدم';
$CIDRAM['lang']['field_blocked'] = 'بلاک';
$CIDRAM['lang']['field_clear'] = 'صاف';
$CIDRAM['lang']['field_component'] = 'اجزاء';
$CIDRAM['lang']['field_create_new_account'] = 'نیا اکاؤنٹ بنانے';
$CIDRAM['lang']['field_deactivate'] = 'بے عمل';
$CIDRAM['lang']['field_delete_account'] = 'کھاتہ مٹا دو';
$CIDRAM['lang']['field_delete_file'] = 'حذف کریں';
$CIDRAM['lang']['field_download_file'] = 'لوڈ';
$CIDRAM['lang']['field_edit_file'] = 'میں ترمیم کریں';
$CIDRAM['lang']['field_expiry'] = 'ختم ہونے';
$CIDRAM['lang']['field_file'] = 'فائل';
$CIDRAM['lang']['field_filename'] = 'فائل کا نام: ';
$CIDRAM['lang']['field_filetype_directory'] = 'ڈائریکٹری';
$CIDRAM['lang']['field_filetype_info'] = '{EXT} فائل';
$CIDRAM['lang']['field_filetype_unknown'] = 'نامعلوم';
$CIDRAM['lang']['field_first_seen'] = 'سب سے پہلے دیکھا';
$CIDRAM['lang']['field_infractions'] = 'انحرافات';
$CIDRAM['lang']['field_install'] = 'انسٹال کریں';
$CIDRAM['lang']['field_ip_address'] = 'IP پتہ';
$CIDRAM['lang']['field_latest_version'] = 'تازہ ترین ورژن';
$CIDRAM['lang']['field_log_in'] = 'لاگ ان';
$CIDRAM['lang']['field_new_name'] = 'نیا نام:';
$CIDRAM['lang']['field_ok'] = 'ٹھیک ہے';
$CIDRAM['lang']['field_options'] = 'اختیارات';
$CIDRAM['lang']['field_password'] = 'پاس ورڈ';
$CIDRAM['lang']['field_permissions'] = 'اجازتیں';
$CIDRAM['lang']['field_range'] = 'رینج (پہلا – آخری)';
$CIDRAM['lang']['field_rename_file'] = 'نام تبدیل کریں';
$CIDRAM['lang']['field_reset'] = 'Reset';
$CIDRAM['lang']['field_set_new_password'] = 'نیا پاس ورڈ مقرر';
$CIDRAM['lang']['field_size'] = 'کل سائز: ';
$CIDRAM['lang']['field_size_bytes'] = 'بائٹس';
$CIDRAM['lang']['field_size_GB'] = 'GB';
$CIDRAM['lang']['field_size_KB'] = 'KB';
$CIDRAM['lang']['field_size_MB'] = 'MB';
$CIDRAM['lang']['field_size_TB'] = 'TB';
$CIDRAM['lang']['field_status'] = 'سٹیٹس';
$CIDRAM['lang']['field_system_timezone'] = 'نظام کو پہلے سے طے شدہ ٹائم زون کا استعمال کریں.';
$CIDRAM['lang']['field_tracking'] = 'ٹریکنگ';
$CIDRAM['lang']['field_uninstall'] = 'اانسٹال نہیں';
$CIDRAM['lang']['field_update'] = 'اپ ڈیٹ';
$CIDRAM['lang']['field_update_all'] = 'تمام تجدید کریں';
$CIDRAM['lang']['field_upload_file'] = 'نئی فائل اپ لوڈ کریں';
$CIDRAM['lang']['field_username'] = 'صارف کا نام';
$CIDRAM['lang']['field_your_version'] = 'آپ کے ورژن';
$CIDRAM['lang']['header_login'] = 'جاری رکھنے کے لیے لاگ ان کریں.';
$CIDRAM['lang']['label_active_config_file'] = 'فعال کنفیگریشن فائل: ';
$CIDRAM['lang']['label_cidram'] = 'CIDRAM ورژن استعمال کیا:';
$CIDRAM['lang']['label_os'] = 'آپریٹنگ سسٹم استعمال کیا:';
$CIDRAM['lang']['label_php'] = 'PHP ورژن استعمال کیا:';
$CIDRAM['lang']['label_sapi'] = 'SAPI استعمال کیا:';
$CIDRAM['lang']['label_sysinfo'] = 'سسٹم کی معلومات:';
$CIDRAM['lang']['link_accounts'] = 'اکاؤنٹس';
$CIDRAM['lang']['link_cidr_calc'] = 'CIDR کیلکولیٹر';
$CIDRAM['lang']['link_config'] = 'کنفگریشن';
$CIDRAM['lang']['link_documentation'] = 'دستاویزی';
$CIDRAM['lang']['link_file_manager'] = 'فائل منیجر';
$CIDRAM['lang']['link_home'] = 'ہوم';
$CIDRAM['lang']['link_ip_test'] = 'IP ٹیسٹ';
$CIDRAM['lang']['link_ip_tracking'] = 'IP ٹریکنگ';
$CIDRAM['lang']['link_logs'] = 'لاگز';
$CIDRAM['lang']['link_updates'] = 'تازہ ترین معلومات کے';
$CIDRAM['lang']['logs_logfile_doesnt_exist'] = 'منتخب شدہ لاگ فائل موجود نہیں ہے!';
$CIDRAM['lang']['logs_no_logfiles_available'] = 'کوئی لاگ مسلیں دستیاب.';
$CIDRAM['lang']['logs_no_logfile_selected'] = 'کوئی لاگ فائل کو منتخب کیا.';
$CIDRAM['lang']['max_login_attempts_exceeded'] = 'لاگ ان کوششوں کی زیادہ سے زیادہ تعداد سے تجاوز کر گئی. رسائی مسترد کر دی.';
$CIDRAM['lang']['previewer_days'] = 'دن';
$CIDRAM['lang']['previewer_hours'] = 'گھنٹے';
$CIDRAM['lang']['previewer_minutes'] = 'منٹس';
$CIDRAM['lang']['previewer_months'] = 'مہینے';
$CIDRAM['lang']['previewer_seconds'] = 'سیکنڈ';
$CIDRAM['lang']['previewer_weeks'] = 'ہفتے';
$CIDRAM['lang']['previewer_years'] = 'سال';
$CIDRAM['lang']['response_accounts_already_exists'] = 'وہ صارف نام کے ساتھ ایک اکاؤنٹ پہلے سے موجود ہے!';
$CIDRAM['lang']['response_accounts_created'] = 'کاؤنٹ کامیابی سے تشکیل!';
$CIDRAM['lang']['response_accounts_deleted'] = 'اکاؤنٹ کامیابی سے خارج!';
$CIDRAM['lang']['response_accounts_doesnt_exist'] = 'اس اکاؤنٹ کا کوئی وجود نہیں.';
$CIDRAM['lang']['response_accounts_password_updated'] = 'پاس ورڈ کامیابی سے اپ ڈیٹ!';
$CIDRAM['lang']['response_activated'] = 'کامیابی کے ساتھ فعال.';
$CIDRAM['lang']['response_activation_failed'] = 'چالو کرنے میں ناکام!';
$CIDRAM['lang']['response_component_successfully_installed'] = 'اجزاء کامیابی سے نصب.';
$CIDRAM['lang']['response_component_successfully_uninstalled'] = 'اجزاء کامیابی سے ان انسٹال.';
$CIDRAM['lang']['response_component_successfully_updated'] = 'اجزاء کامیابی سے اپ ڈیٹ.';
$CIDRAM['lang']['response_component_uninstall_error'] = 'جزو انسٹال کرنے کی کوشش کرتے ہوئے ایک خرابی واقع ہوئی.';
$CIDRAM['lang']['response_component_update_error'] = 'جزو کو اپ ڈیٹ کرنے کی کوشش کرتے ہوئے ایک خرابی واقع ہوئی.';
$CIDRAM['lang']['response_configuration_updated'] = 'کنفگریشن کامیابی سے اپ ڈیٹ.';
$CIDRAM['lang']['response_deactivated'] = 'کامیابی کے ساتھ غیر فعال.';
$CIDRAM['lang']['response_deactivation_failed'] = 'غیر فعال کرنے میں ناکام ہو گیا!';
$CIDRAM['lang']['response_delete_error'] = 'حذف کرنے میں ناکام!';
$CIDRAM['lang']['response_directory_deleted'] = 'ڈائریکٹری کامیابی سے خارج!';
$CIDRAM['lang']['response_directory_renamed'] = 'ڈائریکٹری کامیابی سے نام تبدیل کر دیا!';
$CIDRAM['lang']['response_error'] = 'خرابی';
$CIDRAM['lang']['response_file_deleted'] = 'کامیابی خارج کر دیا فائل!';
$CIDRAM['lang']['response_file_edited'] = 'کامیابی نظر ثانی شدہ فائل!';
$CIDRAM['lang']['response_file_renamed'] = 'کامیابی کا نام دے دیا فائل!';
$CIDRAM['lang']['response_file_uploaded'] = 'کامیابی اپ لوڈ کردہ فائل!';
$CIDRAM['lang']['response_login_invalid_password'] = 'لاگ ان ناکامی! غلط پاسورڈ!';
$CIDRAM['lang']['response_login_invalid_username'] = 'لاگ ان ناکامی! صارف کا نام موجود نہیں ہے!';
$CIDRAM['lang']['response_login_password_field_empty'] = 'پاس ورڈ میدان خالی!';
$CIDRAM['lang']['response_login_username_field_empty'] = 'صارف کا نام فیلڈ کو خالی!';
$CIDRAM['lang']['response_no'] = 'نہیں';
$CIDRAM['lang']['response_rename_error'] = 'نام تبدیل کرنے میں ناکام!';
$CIDRAM['lang']['response_tracking_cleared'] = 'صاف کر دیا ٹریکنگ.';
$CIDRAM['lang']['response_updates_already_up_to_date'] = 'پہلے سے اپ ڈیٹ.';
$CIDRAM['lang']['response_updates_not_installed'] = 'اجزاء انسٹال نہیں!';
$CIDRAM['lang']['response_updates_not_installed_php'] = 'اجزاء انسٹال نہیں (PHP ضرورت ہوتی {V})!';
$CIDRAM['lang']['response_updates_outdated'] = 'فرسودہ!';
$CIDRAM['lang']['response_updates_outdated_manually'] = 'فرسودہ (دستی طور پر اپ ڈیٹ کریں)!';
$CIDRAM['lang']['response_updates_outdated_php_version'] = 'فرسودہ (درکار PHP {V})!';
$CIDRAM['lang']['response_updates_unable_to_determine'] = 'اس بات کا تعین کرنے سے قاصر ہے.';
$CIDRAM['lang']['response_upload_error'] = 'اپ لوڈ کرنے میں ناکام ہو گیا!';
$CIDRAM['lang']['response_yes'] = 'جی ہاں';
$CIDRAM['lang']['state_complete_access'] = 'مکمل رسائی';
$CIDRAM['lang']['state_component_is_active'] = 'جزو فعال ہے.';
$CIDRAM['lang']['state_component_is_inactive'] = 'اجزاء غیر فعال ہے.';
$CIDRAM['lang']['state_component_is_provisional'] = 'اجزاء عارضی ہے.';
$CIDRAM['lang']['state_default_password'] = 'انتباہ: ڈیفالٹ پاس ورڈ کو استعمال کرتے ہوئے!';
$CIDRAM['lang']['state_logged_in'] = 'لاگ.';
$CIDRAM['lang']['state_logs_access_only'] = 'لاگز صرف رسائی';
$CIDRAM['lang']['state_password_not_valid'] = 'انتباہ: یہ اکاؤنٹ ایک درست پاس ورڈ کا استعمال نہیں کر رہا ہے!';
$CIDRAM['lang']['switch-hide-non-outdated-set-false'] = 'غیر فرسودہ مت چھپاو';
$CIDRAM['lang']['switch-hide-non-outdated-set-true'] = 'غیر فرسودہ چھپائیں';
$CIDRAM['lang']['switch-hide-unused-set-false'] = 'غیر استعمال شدہ مت چھپاو';
$CIDRAM['lang']['switch-hide-unused-set-true'] = 'غیر استعمال شدہ چھپائیں';
$CIDRAM['lang']['tip_accounts'] = 'ہیلو، {username}.<br />اکاؤنٹس صفحہ آپ CIDRAM سامنے کے آخر تک رسائی حاصل کر سکتے ہیں جو کنٹرول کرنے کی اجازت دیتا ہے.';
$CIDRAM['lang']['tip_cidr_calc'] = 'ہیلو، {username}.<br />CIDR کیلکولیٹر آپ کو ایک IP ایڈریس CIDRs جس کا حساب کرنے کی اجازت دیتا ہے کا ایک عنصر ہے.';
$CIDRAM['lang']['tip_config'] = 'ہیلو، {username}.<br />ترتیب کے صفحے آپ کو سامنے کے آخر میں سے CIDRAM لئے ترتیب میں ترمیم کی اجازت دیتا ہے.';
$CIDRAM['lang']['tip_donate'] = 'CIDRAM بلا معاوضہ پیش کی جاتی ہے، لیکن آپ کو اس منصوبے کے لئے عطیہ کرنا چاہتے ہیں تو، آپ کو عطیہ کے بٹن پر کلک کر کے ایسا کر سکتے ہیں.';
$CIDRAM['lang']['tip_enter_ips_here'] = 'یہاں آئی پی ایس کے درج.';
$CIDRAM['lang']['tip_enter_ip_here'] = 'یہاں IP درج.';
$CIDRAM['lang']['tip_file_manager'] = 'ہیلو، {username}.<br />فائل مینیجر آپ کو، کو حذف ترمیم کریں، اپ لوڈ، اور فائلوں کو ڈاؤن لوڈ کرنے کی اجازت دیتا ہے. احتیاط کے ساتھ استعمال کریں (آپ کو اس کے ساتھ آپ کی تنصیب توڑ سکتا ہے).';
$CIDRAM['lang']['tip_home'] = 'ہیلو، {username}.<br />یہ CIDRAM سامنے کے آخر میں کے ہوم پیج ہے. جاری رکھنے کے لئے بائیں طرف نیویگیشن مینو میں سے ایک لنک کو منتخب کریں.';
$CIDRAM['lang']['tip_ip_test'] = 'ہیلو، {username}.<br />IP ٹیسٹ کے صفحے آپ کو ٹیسٹ کرنے IP پتوں موجودہ میں انسٹال دستخط کی طرف سے بلاک کر رہے ہیں کہ آیا کی اجازت دیتا ہے.';
$CIDRAM['lang']['tip_ip_tracking'] = 'ہیلو، {username}.<br />IP باخبر رہنے کے صفحے آپ / ان میں سے جو پابندی عائد کی گئی ہے کو چیک کرنے کے، اور پابندی ہٹانے کی، IP پتوں میں سے باخبر رہنے کے کی حیثیت کی جانچ کرنا اگر آپ ایسا کرنا چاہتے ہیں تو انہیں untrack کی اجازت دیتا ہے.';
$CIDRAM['lang']['tip_login'] = 'پہلے سے طے شدہ صارف نام: <span class="txtRd">admin</span> – ڈیفالٹ پاس ورڈ: <span class="txtRd">password</span>';
$CIDRAM['lang']['tip_logs'] = 'ہیلو، {username}.<br />کہ لاگ فائل کے مواد کو دیکھنے کے لئے ذیل کی فہرست سے ایک لاگ فائل منتخب کریں.';
$CIDRAM['lang']['tip_see_the_documentation'] = 'ملاحظہ کریں <a href="https://github.com/Maikuolan/CIDRAM/blob/master/_docs/readme.ur.md#SECTION6">دستاویزی</a> مختلف ترتیب ہدایات اور ان کے مقاصد کے بارے میں معلومات کے لئے.';
$CIDRAM['lang']['tip_updates'] = 'ہیلو، {username}.<br />اپ ڈیٹس صفحہ آپ کو نصب کی اجازت دیتا ہے کے لئے، انسٹال، اور CIDRAM (بنیادی پیکج، دستخط، L10N فائلوں، وغیرہ) کے مختلف اجزاء کو اپ ڈیٹ.';
$CIDRAM['lang']['title_accounts'] = 'CIDRAM – اکاؤنٹس';
$CIDRAM['lang']['title_cidr_calc'] = 'CIDRAM – CIDR کیلکولیٹر';
$CIDRAM['lang']['title_config'] = 'CIDRAM – کنفگریشن';
$CIDRAM['lang']['title_file_manager'] = 'CIDRAM – فائل مینیجر';
$CIDRAM['lang']['title_home'] = 'CIDRAM – ہوم';
$CIDRAM['lang']['title_ip_test'] = 'CIDRAM – IP ٹیسٹ';
$CIDRAM['lang']['title_ip_tracking'] = 'CIDRAM – IP ٹریکنگ';
$CIDRAM['lang']['title_login'] = 'CIDRAM – لاگ ان';
$CIDRAM['lang']['title_logs'] = 'CIDRAM – لاگز';
$CIDRAM['lang']['title_updates'] = 'CIDRAM – تازہ ترین معلومات کے';

$CIDRAM['lang']['info_some_useful_links'] = 'کچھ مفید لنکس:<ul>
            <li><a href="https://github.com/Maikuolan/CIDRAM/issues" dir="ltr">CIDRAM Issues @ GitHub</a> – CIDRAM لئے مسائل کا صفحہ (کی حمایت، مدد، وغیرہ).</li>
            <li><a href="http://www.spambotsecurity.com/forum/viewforum.php?f=61" dir="ltr">CIDRAM @ Spambot Security</a> – CIDRAM لئے فورم (کی حمایت، مدد، وغیرہ).</li>
            <li><a href="https://wordpress.org/plugins/cidram/" dir="ltr">CIDRAM @ WordPress.org</a> – CIDRAM کے لئے ورڈپریس پلگ ان.</li>
            <li><a href="https://sourceforge.net/projects/cidram/" dir="ltr">CIDRAM @ SourceForge</a> – متبادل ڈاؤن آئینے CIDRAM لئے.</li>
            <li><a href="https://websectools.com/" dir="ltr">WebSecTools.com</a> – ویب سائٹس کو محفوظ بنانے کے لئے سادہ ویب ماسٹر ٹولز کا ایک مجموعہ.</li>
            <li><a href="https://macmathan.info/blocklists" dir="ltr">MacMathan.info Range Blocks</a> – آپ کی ویب سائٹ تک رسائی حاصل کرنے کی کوئی ناپسندیدہ ملکوں کو بلاک کرنے CIDRAM میں شامل کیا جا سکتا ہے کہ اختیاری رینج بلاکس پر مشتمل ہے.</li>
            <li><a href="https://www.facebook.com/groups/2204685680/" dir="ltr">Global PHP Group @ Facebook</a> – PHP سیکھنے کے وسائل اور بحث.</li>
            <li><a href="https://php.earth/" dir="ltr">PHP.earth</a> – PHP سیکھنے کے وسائل اور بحث.</li>
            <li><a href="http://bgp.he.net/" dir="ltr">Hurricane Electric BGP Toolkit</a> – ، ASNs سے CIDRs حاصل کریں ASN رشتے کا تعین نیٹ ورک ناموں، وغیرہ کی بنیاد پر ASNs دریافت.</li>
            <li><a href="https://www.stopforumspam.com/forum/" dir="ltr">Forum @ Stop Forum Spam</a> – فورم کے سپیم روکنے کے بارے میں مفید فورم.</li>
            <li><a href="https://www.stopforumspam.com/aggregate" dir="ltr">IP Aggregator @ Stop Forum Spam</a> – IPv4 کی آئی پی ایس کے لئے مفید یکتریقرن آلے.</li>
            <li><a href="https://radar.qrator.net/" dir="ltr">Radar by Qrator</a> – ASNs کی کنیکٹوٹی کی جانچ پڑتال کے لئے اس کے ساتھ ساتھ ASNs بارے مختلف دیگر معلومات کے لئے مفید آلہ.</li>
            <li><a href="http://www.ipdeny.com/ipblocks/" dir="ltr">IPdeny IP country blocks</a> – ملک بھر دستخط پیدا کرنے کے لئے ایک تصوراتی، بہترین اور درست سروس.</li>
            <li><a href="https://www.google.com/transparencyreport/safebrowsing/malware/" dir="ltr">Google Malware Dashboard</a> – ASNs لئے میلویئر انفیکشن کی شرح کے حوالے سے دکھاتا رپورٹیں.</li>
            <li><a href="https://www.spamhaus.org/statistics/botnet-asn/" dir="ltr">The Spamhaus Project</a> – ASNs لئے کی botnet انفیکشن کی شرح کے حوالے سے دکھاتا رپورٹیں.</li>
            <li><a href="http://www.abuseat.org/asn.html" dir="ltr">Abuseat.org\'s Composite Blocking List</a> – ASNs لئے کی botnet انفیکشن کی شرح کے حوالے سے دکھاتا رپورٹیں.</li>
            <li><a href="https://abuseipdb.com/" dir="ltr">AbuseIPDB</a> – نام سے جانا توہین آمیز آئی پی ایس کی ایک ڈیٹا بیس کو برقرار رکھتا ہے؛ آئی پی ایس کی جانچ پڑتال اور رپورٹنگ کے لئے ایک API فراہم.</li>
            <li><a href="https://www.megarbl.net/index.php" dir="ltr">MegaRBL.net</a> – نام سے جانا جاتا ردی باز کو کی لسٹنگ برقرار رکھتا ہے؛ آئی پی / ASN کو فضول سرگرمیوں کی جانچ پڑتال کے لیے مفید.</li>
        </ul>';
