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
 * This file: Urdu language data for the front-end (last modified: 2018.06.10).
 */

/** Prevents execution from outside of CIDRAM. */
if (!defined('CIDRAM')) {
    die('[CIDRAM] This should not be accessed directly.');
}

foreach (['IPv4', 'IPv6'] as $CIDRAM['IPvX']) {
    $CIDRAM['Pre'] = 'پہلے سے طے شدہ ' . $CIDRAM['IPvX'] . ' کی دستخط عام طور پر بنیادی پیکیج کے ساتھ شامل تھے. ';
    $CIDRAM['lang']['Extended Description: ' . $CIDRAM['IPvX']] = $CIDRAM['Pre'] . 'بلاکس ناپسندیدہ کلاؤڈ سروسز اور غیر انسانی رسائی پوائنٹس.';
    $CIDRAM['lang']['Extended Description: ' . $CIDRAM['IPvX'] . '-Bogons'] = $CIDRAM['Pre'] . 'بلاکس martian/bogon CIDR.';
    $CIDRAM['lang']['Extended Description: ' . $CIDRAM['IPvX'] . '-ISPs'] = $CIDRAM['Pre'] . 'خطرناک بلاکس اور سپام ISP.';
    $CIDRAM['lang']['Extended Description: ' . $CIDRAM['IPvX'] . '-Other'] = $CIDRAM['Pre'] . 'پراکسی جنگ لڑ، وپ، اور دیگر متفرق ناپسندیدہ خدمات کے لئے CIDR.';
    $CIDRAM['Pre'] = $CIDRAM['IPvX'] . ' کی دستخط کی فائل (%s).';
    $CIDRAM['lang']['Name: ' . $CIDRAM['IPvX']] = sprintf($CIDRAM['Pre'], 'ناپسندیدہ کلاؤڈ سروسز اور غیر انسانی رسائی پوائنٹس');
    $CIDRAM['lang']['Name: ' . $CIDRAM['IPvX'] . '-Bogons'] = sprintf($CIDRAM['Pre'], 'bogon/martian CIDRs');
    $CIDRAM['lang']['Name: ' . $CIDRAM['IPvX'] . '-ISPs'] = sprintf($CIDRAM['Pre'], 'خطرناک اور سپام ISP');
    $CIDRAM['lang']['Name: ' . $CIDRAM['IPvX'] . '-Other'] = sprintf($CIDRAM['Pre'], 'کے لئے پراکسی جنگ لڑ، وپ، اور دیگر متفرق ناپسندیدہ خدمات CIDR');
}
unset($CIDRAM['Pre'], $CIDRAM['IPvX']);

$CIDRAM['lang']['Extended Description: Bypasses'] = 'پہلے سے طے شدہ دستخطی بائی پاس فائلوں کو عام طور پر بنیادی پیکیج کے ساتھ شامل تھے.';
$CIDRAM['lang']['Extended Description: CIDRAM'] = 'مرکزی پیکیج (بغیر دستخط، دستاویزات، اور ترتیب).';
$CIDRAM['lang']['Extended Description: module_badhosts.php'] = 'بلاکس اکثر سپیمرز، ہیکرز، اور دیگر غلط تنظیموں کے ذریعہ استعمال ہوتے ہیں.';
$CIDRAM['lang']['Extended Description: module_badhosts_isps.php'] = 'آئی ایس پیز سے تعلق رکھنے والے بلاکس میزبان اکثر اسپیمرز، ہیکرز، اور دیگر نواحی اداروں کی طرف سے استعمال ہوتے ہیں.';
$CIDRAM['lang']['Extended Description: module_badtlds.php'] = 'عام طور پر اسپیمرز، ہیکرز، اور دیگر نواحی اداروں کے ذریعہ TLDs سے تعلق رکھنے والے بلاکس میزبان.';
$CIDRAM['lang']['Extended Description: module_cookies.php'] = 'خطرناک کوکیز کے خلاف کچھ محدود تحفظ فراہم کرتا ہے.';
$CIDRAM['lang']['Extended Description: module_extras.php'] = 'عام طور پر درخواستوں میں استعمال ہونے والی مختلف حمل ویکٹروں کے خلاف کچھ محدود تحفظ فراہم کرتا ہے.';
$CIDRAM['lang']['Extended Description: module_sfs.php'] = 'SFS سے گنا آئی پی ایس کے خلاف رجسٹریشن اور لاگ ان صفحات کی حفاظت کرتا ہے.';
$CIDRAM['lang']['Name: Bypasses'] = 'پہلے سے طے شدہ دستخطی بائ پاس.';
$CIDRAM['lang']['Name: module_badhosts.php'] = 'برا میزبانوں بلاکر ماڈیول';
$CIDRAM['lang']['Name: module_badhosts_isps.php'] = 'برا میزبانوں بلاکر ماڈیول (ISP)';
$CIDRAM['lang']['Name: module_badtlds.php'] = 'خراب TLD بلاکر ماڈیول';
$CIDRAM['lang']['Name: module_baidublocker.php'] = 'بیدو بلاکر ماڈیول';
$CIDRAM['lang']['Name: module_cookies.php'] = 'اختیاری کوکی سکینر ماڈیول';
$CIDRAM['lang']['Name: module_extras.php'] = 'اختیاری سیکورٹی مع اضافہ جات ماڈیول';
$CIDRAM['lang']['Name: module_sfs.php'] = 'Stop Forum Spam ماڈیول';
$CIDRAM['lang']['Name: module_yandexblocker.php'] = 'Yandex کی بلاکر ماڈیول';
$CIDRAM['lang']['bNav_home_logout'] = '<a href="?">ہوم</a> | <a href="?cidram-page=logout">لاگ آوٹ</a>';
$CIDRAM['lang']['bNav_logout'] = '<a href="?cidram-page=logout">لاگ آوٹ</a>';
$CIDRAM['lang']['config_general_FrontEndLog'] = 'سامنے کے آخر میں لاگ ان کوششوں لاگنگ کے لئے دائر. ایک فائل کا نام کی وضاحت کریں، یا غیر فعال کرنے کو خالی چھوڑ.';
$CIDRAM['lang']['config_general_allow_gethostbyaddr_lookup'] = 'جب UDP دستیاب نہیں ہے تو gethostbyaddr کی تلاش کی اجازت دیں؟ True (سچے) = جی ہاں [پہلے سے طے شدہ]؛ False (جھوٹی) = نہیں.';
$CIDRAM['lang']['config_general_ban_override'] = '"forbid_on_block" کی جگہ لے لے، جب "infraction_limit" حد سے تجاوز کر رہا ہے؟ زیرکر کب: التواء درخواستوں ایک خالی صفحہ (سانچے فائلوں کا استعمال نہیں کر رہے ہیں) واپس جائیں. 200 = جگہ لے لے نہیں ہے [پہلے سے طے شدہ]. دیگر اقدار "forbid_on_block" کے لئے دستیاب اقدار کے طور پر اسی ہیں.';
$CIDRAM['lang']['config_general_default_algo'] = 'اس بات کی وضاحت کرتا ہے جو تمام مستقبل کے پاس ورڈ اور سیشن کے لئے الگورتھم استعمال کرنا ہے. اختیارات: PASSWORD_DEFAULT (ڈیفالٹ), PASSWORD_BCRYPT, PASSWORD_ARGON2I (PHP &gt;= 7.2.0 کی ضرورت ہے).';
$CIDRAM['lang']['config_general_default_dns'] = 'میزبان نام لک اپ کے لئے استعمال کرنے کے لئے DNS سرورز کی کوما ختم ہونے والی فہرست. پہلے سے طے شدہ = "8.8.8.8,8.8.4.4" (Google DNS). انتباہ: جب تک کہ آپ کو پتہ ہے تم کیا کر رہے ہو اس کو تبدیل نہ کریں!';
$CIDRAM['lang']['config_general_disable_cli'] = 'CLI موڈ کو غیر فعال کریں؟ CLI موڈ ڈیفالٹ کی طرف سے چالو حالت میں ہے، لیکن کبھی کبھی بعض جانچ کے آلات (جیسے PHPUnit کے طور پر، مثال کے طور پر) اور دیگر CLI کی بنیاد پر ایپلی کیشنز کے ساتھ مداخلت کر سکتے ہیں. آپ CLI موڈ کو غیر فعال کرنے کی ضرورت نہیں ہے تو، آپ کو اس ہدایت کو نظر انداز کرنا چاہئے. False (جھوٹی) = CLI موڈ [پہلے سے طے شدہ] فعال؛ True (سچے) = غیر فعال CLI موڈ.';
$CIDRAM['lang']['config_general_disable_frontend'] = 'سامنے کے آخر تک رسائی کو غیر فعال کریں؟ سامنے کے آخر میں رسائی CIDRAM زیادہ انتظام بنا سکتے ہیں، لیکن یہ بھی بہت ہے، ایک زبردست حفاظتی خطرہ ہو سکتا ہے. یہ جب بھی ممکن ہو واپس کے آخر کے ذریعے CIDRAM منظم کرنے کی سفارش کی جاتی ہے، لیکن سامنے کے آخر میں رسائی ممکن نہیں ہے جب کے لئے فراہم کی جاتی ہے. تمہیں اس کی ضرورت ہے جب تک کہ اس کو معذور رکھیں. False (جھوٹی) = سامنے کے آخر میں رسائی کو فعال کریں؛ True (سچے) = غیر فعال سامنے کے آخر میں رسائی [پہلے سے طے شدہ].';
$CIDRAM['lang']['config_general_disable_webfonts'] = 'Webfonts کے غیر فعال کریں؟ True (سچے) = جی ہاں [پہلے سے طے شدہ]؛ False (جھوٹی) = کوئی.';
$CIDRAM['lang']['config_general_emailaddr'] = 'اگر آپ چاہتے ہیں، تو آپ صارفین کو جب انہیں بلاک کر رہے ہیں تو دینے کے لئے ای میل ایڈریس کی فراہمی کر سکتے ہیں.وہ اسے استعمال آپ سے رابطہ کرنے کے لئے کر سکتے ہیں اگر وہ غلطی سے بلاک کر رہے ہیں. انتباہ: آپ جو بھی ای میل ایڈریس پر فراہمی کرتے ہیں، وہ یقینی طور پر سپےمبٹس اور کھرچنی کی طرف سے حاصل کئے جائیں گے. اس کی وجہ سے، اس کی سختی سے سفارش کی جاتی ہے کہ آپ ایک ای میل ایڈریس انتخاب کرتے ہیں جو ڈسپوزایبل یا غیر اہم ہے (یعنی.، آپ کی ذاتی یا کاروباری ای میل ایڈریس کا استعمال نہ کریں).';
$CIDRAM['lang']['config_general_emailaddr_display_style'] = 'آپ کو ای میل ایڈریس کو کس طرح صارفین کو پیش کرنا پسند ہے؟';
$CIDRAM['lang']['config_general_forbid_on_block'] = 'درخواستوں کو روکنے پر بھیجنے کے لئے HTTP حیثیت کا پیغام. (مزید معلومات کے لئے دستاویزات کا حوالہ دیتے ہیں).';
$CIDRAM['lang']['config_general_force_hostname_lookup'] = 'تمام درخواستوں کے لئے میزبانی حاصل کریں؟ True (سچے) = جی ہاں؛ False (جھوٹی) = نہیں [پہلے سے طے شدہ]. میزبان نام کی تلاش عام طور پر "ضرورت کی بنیاد" کی بنیاد پر انجام دیا جاتا ہے، لیکن تمام درخواستوں کے لئے مجبور کیا جاسکتا ہے. ایسا کرتے ہوئے لاگ ان میں مزید تفصیلی معلومات فراہم کرنے کے ذریعہ مفید ثابت ہوسکتا ہے، لیکن کارکردگی پر تھوڑا منفی اثر بھی ہوسکتا ہے.';
$CIDRAM['lang']['config_general_hide_version'] = 'لاگ ان اور صفحے کی پیداوار سے ورژن کی معلومات چھپائیں؟ True (سچے) = جی ہاں؛ False (جھوٹی) = نہیں [پہلے سے طے شدہ].';
$CIDRAM['lang']['config_general_ipaddr'] = 'کہاں درخواستوں منسلک کرنے کے IP ایڈریس کو تلاش کرنے کے لئے؟ (جیسا Cloudflare کے طور پر خدمات اور پسند کرتا ہے کے لئے مفید). پہلے سے طے شدہ = REMOTE_ADDR. انتباہ: جب تک کہ آپ کو پتہ ہے تم کیا کر رہے ہو اس کو تبدیل نہ کریں!';
$CIDRAM['lang']['config_general_lang'] = 'CIDRAM لئے پہلے سے طے شدہ زبان کی وضاحت.';
$CIDRAM['lang']['config_general_log_banned_ips'] = 'لاگ مسلیں میں کالعدم IP ایس سے مسدود درخواستوں شامل کریں? True (سچے) = جی ہاں [پہلے سے طے شدہ]; False (جھوٹی) = نہیں.';
$CIDRAM['lang']['config_general_log_rotation_action'] = 'لاگ گرد گردش کسی بھی وقت کسی بھی وقت موجود ہونا لاگ ان کی تعداد محدود کرتا ہے. جب نیا لاگ ان کی تخلیق کی جاتی ہے تو، اگر لاگ ان کی کل تعداد مخصوص حد سے زیادہ ہوتی ہے تو مخصوص کارروائی کی جائے گی. آپ یہاں مطلوبہ کارروائی کی وضاحت کرسکتے ہیں. Delete = قدیم ترین لاگ ان کو حذف کریں، جب تک کہ حد تک زیادہ نہیں ہوسکتی ہے. Archive = سب سے پہلے آرکائیو، اور پھر سب سے پرانی لاگ ان کو حذف کریں، جب تک کہ حد زیادہ نہیں ہوسکتی.';
$CIDRAM['lang']['config_general_log_rotation_limit'] = 'لاگ گرد گردش کسی بھی وقت کسی بھی وقت موجود ہونا لاگ ان کی تعداد محدود کرتا ہے. جب نیا لاگ ان کی تخلیق کی جاتی ہے تو، اگر لاگ ان کی کل تعداد مخصوص حد سے زیادہ ہوتی ہے تو مخصوص کارروائی کی جائے گی. آپ یہاں مطلوبہ حد کی وضاحت کرسکتے ہیں. 0 کی قیمت لاگ گرد گردش کو غیر فعال کرے گی.';
$CIDRAM['lang']['config_general_logfile'] = 'تمام بلاک کر تک رسائی کی کوششوں کو لاگ ان کرنے کے لئے انسانی قابل مطالعہ فائل. ایک فائل کا نام کی وضاحت کریں، یا غیر فعال کرنے کو خالی چھوڑ.';
$CIDRAM['lang']['config_general_logfileApache'] = 'تمام بلاک کر تک رسائی کی کوششوں کو لاگ ان کرنے کے لئے اپاچی طرز فائل. ایک فائل کا نام کی وضاحت کریں، یا غیر فعال کرنے کو خالی چھوڑ.';
$CIDRAM['lang']['config_general_logfileSerialized'] = 'تمام بلاک کر تک رسائی کی کوششوں کو لاگ ان کرنے کے لئے serialized کی فائل. ایک فائل کا نام کی وضاحت کریں، یا غیر فعال کرنے کو خالی چھوڑ.';
$CIDRAM['lang']['config_general_maintenance_mode'] = 'بحالی کا موڈ فعال کریں؟ True (سچے) = جی ہاں؛ False (جھوٹی) = کوئی [پہلے سے طے شدہ]. سامنے کے اختتام کے مقابلے میں سب کچھ غیر فعال کرتا ہے. کبھی کبھی آپ کے CMS، فریم ورک، وغیرہ کو اپ ڈیٹ کرنے کے لئے مفید ہے.';
$CIDRAM['lang']['config_general_max_login_attempts'] = 'لاگ ان کوششوں کی زیادہ سے زیادہ تعداد.';
$CIDRAM['lang']['config_general_numbers'] = 'آپ کس طرح تعداد میں ظاہر کرنے کے لئے پسند کرتے ہیں؟ مثال کے طور پر منتخب کریں جو آپ کے لئے سب سے زیادہ درست نظر آتے ہیں.';
$CIDRAM['lang']['config_general_protect_frontend'] = 'متعین کرتا ہے جو عام طور پر CIDRAM طرف سے فراہم کردہ تحفظات سامنے کے آخر پر لاگو کیا جانا چاہئے کہ آیا. True (سچے) = جی ہاں [پہلے سے طے شدہ]; False (جھوٹی) = نہیں.';
$CIDRAM['lang']['config_general_search_engine_verification'] = 'تلاش کے انجن کی طرف سے درخواستوں کی تصدیق کرنے کی کوشش؟ تلاش کے انجن کی توثیق کرنے سے کہ وہ خلاف ورزی کی حد (ویب سائٹ سے تلاش کے انجن پر پابندی عائد عام طور پر آپ کی تلاش کے انجن کی درجہ بندی، SEO، وغیرہ پر منفی اثر پڑے گا) تجاوز کا ایک نتیجہ کے طور پر پابندی عائد نہیں کیا جائے گا یقینی بناتا ہے. تصدیق کی جب، تلاش کے انجن معمول فی کے طور پر بلاک کیا جا سکتا ہے، لیکن پابندی عائد نہیں کی جائے گی. کی توثیق نہیں کی ہے، تو یہ ان کے لئے خلاف ورزی کی حد سے تجاوز کرنے کے نتیجے کے طور پر پابندی عائد کی جائے کرنے کے لئے ممکن ہے. اس کے علاوہ، تلاش کے انجن کی توثیق کی جعلی تلاش کے انجن کی درخواستوں کے خلاف اور (اس طرح کی درخواستوں کی تلاش کے انجن کی توثیق فعال ہے جب بلاک کر دیا جائے گا) سرچ انجن کے طور پر ویش ممکنہ طور پر بدنیتی پر مبنی اداروں کے خلاف تحفظ فراہم کرتا ہے. True (سچے) = تلاش کے انجن کی توثیق فعال [پہلے سے طے شدہ]; False (جھوٹی) = غیر فعال تلاش کے انجن کی توثیق کی.';
$CIDRAM['lang']['config_general_silent_mode'] = 'خاموشی CIDRAM چاہئے "رسائی نہیں ہوئی" کے صفحے کی نمائش سے بلاک رسائی کی کوششوں کو ری ڈائریکٹ کرنے کے بجائے؟ ہاں تو، کو بلاک کر تک رسائی کی کوششوں کو ری ڈائریکٹ کرنے کے محل وقوع کی وضاحت. کوئی تو اس متغیر خالی چھوڑ.';
$CIDRAM['lang']['config_general_statistics'] = 'CIDRAM استعمال کے اعداد و شمار کو ٹریک کریں؟ True (سچے) = جی ہاں؛ False (جھوٹی) = نہیں [پہلے سے طے شدہ].';
$CIDRAM['lang']['config_general_timeFormat'] = 'CIDRAM کی طرف سے استعمال کی تاریخوں کا فارم. اضافی اختیارات درخواست پر شامل کیا جا سکتا ہے.';
$CIDRAM['lang']['config_general_timeOffset'] = 'ٹائم زون منٹ میں آفسیٹ.';
$CIDRAM['lang']['config_general_timezone'] = 'آپ کے ٹائم زون.';
$CIDRAM['lang']['config_general_truncate'] = 'وہ ایک خاص سائز تک پہنچنے میں جب صاف لاگ مسلیں؟ ویلیو میں B/KB/MB/GB/TB زیادہ سے زیادہ سائز ہے. جب 0KB، وہ غیر معینہ مدت تک ترقی کر سکتا ہے (پہلے سے طے). نوٹ: واحد فائلوں پر لاگو ہوتا ہے! فائلیں اجتماعی غور نہیں کر رہے ہیں.';
$CIDRAM['lang']['config_legal_omit_hostname'] = 'لاگ فائلوں سے میزبانوں کو خارج کردیں؟ True (سچے) = جی ہاں؛ False (جھوٹی) = نہیں [پہلے سے طے شدہ].';
$CIDRAM['lang']['config_legal_omit_ip'] = 'لاگ فائلوں سے IP پتے کو خارج کردیں؟ True (سچے) = جی ہاں؛ False (جھوٹی) = نہیں [پہلے سے طے شدہ]. نوٹ: "pseudonymise_ip_addresses" بے شمار ہو جاتا ہے جب "omit_ip" "true" ہے.';
$CIDRAM['lang']['config_legal_omit_ua'] = 'لاگ فائلوں سے صارف کے ایجنٹوں کو خارج کردیں؟ True (سچے) = جی ہاں؛ False (جھوٹی) = نہیں [پہلے سے طے شدہ].';
$CIDRAM['lang']['config_legal_privacy_policy'] = 'کسی بھی پیدا کردہ صفحات کے فوٹر میں ظاہر ہونے والی متعلقہ رازداری کی پالیسی کا پتہ. ایک URL کی وضاحت کریں، یا غیر فعال کرنے کیلئے خالی چھوڑ دیں.';
$CIDRAM['lang']['config_legal_pseudonymise_ip_addresses'] = 'لاگ ان کرتے وقت پی ایس ڈی نامناسب IP پتے؟ True (سچے) = جی ہاں؛ False (جھوٹی) = نہیں [پہلے سے طے شدہ].';
$CIDRAM['lang']['config_recaptcha_api'] = 'کون سا API استعمال کرنے کے لئے؟ V2 یا Invisible؟';
$CIDRAM['lang']['config_recaptcha_expiry'] = 'گھنٹوں کی تعداد reCAPTCHA کے واقعات کو یاد کرنے.';
$CIDRAM['lang']['config_recaptcha_lockip'] = 'ئی پی ایس کے لئے ہیتی لاک؟';
$CIDRAM['lang']['config_recaptcha_lockuser'] = 'صارفین کے لئے ہیتی لاک؟';
$CIDRAM['lang']['config_recaptcha_logfile'] = 'تمام reCAPTCHA کے کوششوں لاگ؟ اگر ہاں، logfile پر کے لئے استعمال کرنے کا نام کی وضاحت. کوئی تو اس متغیر خالی چھوڑ دیں.';
$CIDRAM['lang']['config_recaptcha_secret'] = '"secret key" کے طور پر ایک ہی ہونا چاہئے. یہ reCAPTCHA ڈیش بورڈ میں پایا جا سکتا ہے.';
$CIDRAM['lang']['config_recaptcha_signature_limit'] = 'reCAPTCHA مثال پیش کرنے کے لئے جب دستخط کی زیادہ سے زیادہ تعداد کی شروعات کی جائے گی. پہلے سے طے شدہ = 1. اگر یہ نمبر کسی بھی مخصوص درخواست کے لئے حد سے تجاوز کردی گئی ہے تو، reCAPTCHA پیش نہیں کیا جائے گا.';
$CIDRAM['lang']['config_recaptcha_sitekey'] = '"site key" کے طور پر ایک ہی ہونا چاہئے. یہ reCAPTCHA ڈیش بورڈ میں پایا جا سکتا ہے.';
$CIDRAM['lang']['config_recaptcha_usemode'] = 'CIDRAM reCAPTCHA کے استعمال کرنا چاہئے کس طرح کی وضاحت (دستاویزات دیکھیں).';
$CIDRAM['lang']['config_signatures_block_bogons'] = 'بلاک bogon/martian کی CIDRs؟ آپ لوکل ہوسٹ سے، یا اپنے LAN سے اپنے مقامی نیٹ ورک کے اندر سے اپنی ویب سائٹ پر کنکشن، توقع ہے، اس ہدایت کے جھوٹے پر مقرر کیا جائے چاہئے. اگر آپ ان میں ایسے کنکشنوں کی توقع نہیں ہے، تو اس ہدایت صحیح پر سیٹ کیا جانا چاہئے.';
$CIDRAM['lang']['config_signatures_block_cloud'] = 'بلاک CIDRs webhosting کے / کلاؤڈ سروسز سے تعلق رکھنے والے کے طور پر شناخت؟ آپ کو آپ کی ویب سائٹ سے ایک API سروس آپریٹ یا اگر آپ دوسری ویب سائٹس کو اپنی ویب سائٹ سے رابطہ قائم کرنے کی توقع ہے تو، تو اس جھوٹے کے لئے مقرر کیا جانا چاہئے. آپ ایسا نہیں کرتے، تو پھر، اس ہدایت صحیح پر سیٹ کیا جانا چاہئے.';
$CIDRAM['lang']['config_signatures_block_generic'] = 'بلاک کرنے کی سفارش کی CIDRs کو بلاک کرنا؟ یہ دیگر زیادہ مخصوص دستخط کی اقسام میں سے کسی کا حصہ ہونے کے طور پر نشان نہیں ہیں کہ کسی بھی دستخطوں پر محیط ہے.';
$CIDRAM['lang']['config_signatures_block_legal'] = 'قانونی ذمہ داریوں کے جواب میں CIDRs کو بلاک کریں؟ یہ ہدایت عام طور پر کسی بھی اثر نہیں ہونا چاہئے، کیونکہ CIDRAM کسی CIDRs کے ساتھ "قانونی ذمہ داریوں" سے متعلق نہیں ہے، لیکن اس کے باوجود موجود ہے کہ کسی بھی اپنی مرضی کے دستخط فائلوں یا ماڈیولز کے فائدہ کے لئے اضافی کنٹرول کی پیمائش کے طور پر جو قانونی وجوہات کی بناء پر موجود ہو.';
$CIDRAM['lang']['config_signatures_block_malware'] = 'میلویئر کے ساتھ منسلک بلاک IPs؟ اس میں C&C سرورز، متاثرہ مشینیں، میلویئر کی تقسیم، وغیرہ میں شامل مشینیں شامل ہیں.';
$CIDRAM['lang']['config_signatures_block_proxies'] = 'پراکسی خدمات یا VPNs سے تعلق رکھنے والے CIDRs کو بلاک کرنا؟ اگر آپ کو ضرورت ہو تو صارفین پراکسی سروسز اور VPNs سے آپ کی ویب سائٹ تک رسائی حاصل کرنے کے قابل ہو جائیں، یہ ہدایت false پر مرتب کرنا چاہئے. دوسری صورت میں، اگر آپ پراکسی خدمات یا VPNs کی ضرورت نہیں ہے، تو یہ ہدایت سیکورٹی کو بہتر بنانے کے ذریعہ true کو مقرر کیا جانا چاہئے.';
$CIDRAM['lang']['config_signatures_block_spam'] = 'بلاک CIDRs سپیم کے لئے اعلی خطرے ہونے کے طور پر شناخت کیا؟ ایسا کرنے جب آپ کو مسائل کا سامنا ہوتا ہے جب تک، عام طور پر، یہ ہمیشہ سچ کے لئے مقرر کیا جانا چاہئے.';
$CIDRAM['lang']['config_signatures_default_tracktime'] = 'ماڈیولز کی طرف سے پابندی لگا دی IP ایس کے ٹریک کرنے سیکنڈ کتنے. پہلے سے طے شدہ = 604800 (1 ہفتہ).';
$CIDRAM['lang']['config_signatures_infraction_limit'] = 'خلاف ورزی کی زیادہ سے زیادہ تعداد ایک IP اس سے پہلے کیا جاتا ہے IP باخبر رہنے کے کی طرف سے پابندی کا اطلاق کرنے کی اجازت ہے. پہلے سے طے شدہ = 10.';
$CIDRAM['lang']['config_signatures_ipv4'] = 'IPv4 کی دستخط کی ایک فہرست فائلوں کہ CIDRAM، کا تجزیہ کرنے کی کوشش کرنا چاہئے کوما سے ختم ہونے والی.';
$CIDRAM['lang']['config_signatures_ipv6'] = 'IPv6 کی دستخط کی ایک فہرست فائلوں کہ CIDRAM، کا تجزیہ کرنے کی کوشش کرنا چاہئے کوما سے ختم ہونے والی.';
$CIDRAM['lang']['config_signatures_modules'] = 'ماڈیول فائلوں کی ایک فہرست کوما سے ختم ہونے والی، IPv4/IPv6 دستخط جانچ پڑتال کے بعد لوڈ کرنے کے لئے.';
$CIDRAM['lang']['config_signatures_track_mode'] = 'خلاف ورزی شمار کب کیا جانا چاہئے؟ False (جھوٹی) = IP ایس کے ماڈیول کی طرف سے بلاک کر رہے ہیں جب. True (سچے) = IP ایس کے کسی بھی وجہ سے بلاک کر رہے ہیں جب.';
$CIDRAM['lang']['config_template_data_Magnification'] = 'فونٹ اضافہ. پہلے سے طے شدہ = 1.';
$CIDRAM['lang']['config_template_data_css_url'] = 'اپنی مرضی کے موضوعات کے لئے سی ایس ایس فائل URL.';
$CIDRAM['lang']['config_template_data_theme'] = 'CIDRAM لئے استعمال کرنے کے لئے مرکزی خیال، موضوع پہلے سے طے شدہ.';
$CIDRAM['lang']['confirm_action'] = 'کیا آپ واقعی "%s" کرنا چاہتے ہیں؟';
$CIDRAM['lang']['field_activate'] = 'فعال کریں';
$CIDRAM['lang']['field_banned'] = 'کالعدم';
$CIDRAM['lang']['field_blocked'] = 'بلاک';
$CIDRAM['lang']['field_clear'] = 'صاف';
$CIDRAM['lang']['field_clear_all'] = 'تمام کو صاف کریں';
$CIDRAM['lang']['field_clickable_link'] = 'کلک کرنے والے لنک';
$CIDRAM['lang']['field_component'] = 'اجزاء';
$CIDRAM['lang']['field_create_new_account'] = 'نیا اکاؤنٹ بنانے';
$CIDRAM['lang']['field_deactivate'] = 'بے عمل';
$CIDRAM['lang']['field_delete_account'] = 'کھاتہ مٹا دو';
$CIDRAM['lang']['field_delete_file'] = 'حذف کریں';
$CIDRAM['lang']['field_download_file'] = 'لوڈ';
$CIDRAM['lang']['field_edit_file'] = 'میں ترمیم کریں';
$CIDRAM['lang']['field_expiry'] = 'ختم ہونے';
$CIDRAM['lang']['field_false'] = 'False (غلط)';
$CIDRAM['lang']['field_file'] = 'فائل';
$CIDRAM['lang']['field_filename'] = 'فائل کا نام: ';
$CIDRAM['lang']['field_filetype_directory'] = 'ڈائریکٹری';
$CIDRAM['lang']['field_filetype_info'] = '{EXT} فائل';
$CIDRAM['lang']['field_filetype_unknown'] = 'نامعلوم';
$CIDRAM['lang']['field_infractions'] = 'خلاف ورزی';
$CIDRAM['lang']['field_install'] = 'انسٹال کریں';
$CIDRAM['lang']['field_ip_address'] = 'IP پتہ';
$CIDRAM['lang']['field_latest_version'] = 'تازہ ترین ورژن';
$CIDRAM['lang']['field_log_in'] = 'لاگ ان';
$CIDRAM['lang']['field_new_name'] = 'نیا نام:';
$CIDRAM['lang']['field_nonclickable_text'] = 'متن جو کلک نہیں کیا جا سکتا';
$CIDRAM['lang']['field_ok'] = 'ٹھیک ہے';
$CIDRAM['lang']['field_options'] = 'اختیارات';
$CIDRAM['lang']['field_password'] = 'پاس ورڈ';
$CIDRAM['lang']['field_permissions'] = 'اجازتیں';
$CIDRAM['lang']['field_range'] = 'رینج (پہلا – آخری)';
$CIDRAM['lang']['field_rename_file'] = 'نام تبدیل کریں';
$CIDRAM['lang']['field_reset'] = 'Reset';
$CIDRAM['lang']['field_set_new_password'] = 'نیا پاس ورڈ مقرر';
$CIDRAM['lang']['field_size'] = 'کل سائز: ';
$CIDRAM['lang']['field_size_GB'] = 'GB';
$CIDRAM['lang']['field_size_KB'] = 'KB';
$CIDRAM['lang']['field_size_MB'] = 'MB';
$CIDRAM['lang']['field_size_TB'] = 'TB';
$CIDRAM['lang']['field_size_bytes'] = 'بائٹس';
$CIDRAM['lang']['field_status'] = 'سٹیٹس';
$CIDRAM['lang']['field_system_timezone'] = 'نظام کو پہلے سے طے شدہ ٹائم زون کا استعمال کریں.';
$CIDRAM['lang']['field_tracking'] = 'ٹریکنگ';
$CIDRAM['lang']['field_true'] = 'True (سچ)';
$CIDRAM['lang']['field_uninstall'] = 'اانسٹال نہیں';
$CIDRAM['lang']['field_update'] = 'اپ ڈیٹ';
$CIDRAM['lang']['field_update_all'] = 'تمام تجدید کریں';
$CIDRAM['lang']['field_upload_file'] = 'نئی فائل اپ لوڈ کریں';
$CIDRAM['lang']['field_username'] = 'صارف کا نام';
$CIDRAM['lang']['field_verify'] = 'تصدیق کریں';
$CIDRAM['lang']['field_verify_all'] = 'سب کی توثیق کریں';
$CIDRAM['lang']['field_your_version'] = 'آپ کے ورژن';
$CIDRAM['lang']['header_login'] = 'جاری رکھنے کے لیے لاگ ان کریں.';
$CIDRAM['lang']['label_active_config_file'] = 'فعال کنفیگریشن فائل: ';
$CIDRAM['lang']['label_actual'] = 'اس وقت';
$CIDRAM['lang']['label_backup_location'] = 'Repository بیک اپ مقامات (ہنگامی حالت میں، یا اگر سب کچھ ناکام ہوجاتا ہے):';
$CIDRAM['lang']['label_banned'] = 'کی درخواستیں کالعدم';
$CIDRAM['lang']['label_blocked'] = 'کی درخواستیں بلاک';
$CIDRAM['lang']['label_branch'] = 'شاخ تازہ ترین مستحکم:';
$CIDRAM['lang']['label_check_modules'] = 'ماڈیولز کے خلاف بھی ٹیسٹ کریں.';
$CIDRAM['lang']['label_cidram'] = 'CIDRAM ورژن استعمال کیا:';
$CIDRAM['lang']['label_clientinfo'] = 'کلائنٹ کی معلومات:';
$CIDRAM['lang']['label_displaying'] = ['<span class="txtRd">%s</span> آئٹم دکھایا.', '<span class="txtRd">%s</span> اشیاء دکھایا.'];
$CIDRAM['lang']['label_displaying_that_cite'] = ['<span class="txtRd">%1$s</span> آئٹم دکھایا کہ "%2$s" کا حوالہ دیتے ہیں.', '<span class="txtRd">%1$s</span> اشیاء دکھایا کہ "%2$s" کا حوالہ دیتے ہیں.'];
$CIDRAM['lang']['label_expected'] = 'متوقع';
$CIDRAM['lang']['label_expires'] = 'ختم ہو جاتی ہے: ';
$CIDRAM['lang']['label_false_positive_risk'] = 'غلط مثبت خطرہ: ';
$CIDRAM['lang']['label_fmgr_cache_data'] = 'کیش کردہ ڈیٹا اور عارضی فائلیں';
$CIDRAM['lang']['label_fmgr_disk_usage'] = 'CIDRAM ڈسک استعمال: ';
$CIDRAM['lang']['label_fmgr_free_space'] = 'مفت ڈسک کی جگہ: ';
$CIDRAM['lang']['label_fmgr_total_disk_usage'] = 'کل ڈسک استعمال: ';
$CIDRAM['lang']['label_fmgr_total_space'] = 'کل ڈسک کی جگہ: ';
$CIDRAM['lang']['label_fmgr_updates_metadata'] = 'اجزاء اپ ڈیٹ میٹا ڈیٹا';
$CIDRAM['lang']['label_hide'] = 'چھپائیں';
$CIDRAM['lang']['label_hide_hash_table'] = 'ہیش ٹیبل چھپائیں';
$CIDRAM['lang']['label_never'] = 'کبھی نہیں';
$CIDRAM['lang']['label_os'] = 'آپریٹنگ سسٹم استعمال کیا:';
$CIDRAM['lang']['label_other'] = 'دیگر';
$CIDRAM['lang']['label_other-ActiveIPv4'] = 'فعال IPv4 دستخط فائلیں';
$CIDRAM['lang']['label_other-ActiveIPv6'] = 'فعال IPv6 دستخط فائلیں';
$CIDRAM['lang']['label_other-ActiveModules'] = 'فعال ماڈیولز';
$CIDRAM['lang']['label_other-Since'] = 'شروع کرنے کی تاریخ';
$CIDRAM['lang']['label_php'] = 'PHP ورژن استعمال کیا:';
$CIDRAM['lang']['label_reCAPTCHA'] = 'کوششیں reCAPTCHA';
$CIDRAM['lang']['label_results'] = 'نتائج (%s ان پٹ – %s مسترد – %s قبول – %s مل گیا – %s پیداوار):';
$CIDRAM['lang']['label_sapi'] = 'SAPI استعمال کیا:';
$CIDRAM['lang']['label_show'] = 'دکھائیں';
$CIDRAM['lang']['label_show_hash_table'] = 'ہیش ٹیبل دکھائیں';
$CIDRAM['lang']['label_signature_type'] = 'دستخط کی قسم:';
$CIDRAM['lang']['label_stable'] = 'تازہ ترین مستحکم:';
$CIDRAM['lang']['label_sysinfo'] = 'سسٹم کی معلومات:';
$CIDRAM['lang']['label_tests'] = 'ٹیسٹ:';
$CIDRAM['lang']['label_total'] = 'تمام';
$CIDRAM['lang']['label_unstable'] = 'تازہ ترین غیر مستحکم:';
$CIDRAM['lang']['label_used_with'] = 'کے ساتھ استعمال کیا جاتا ہے: ';
$CIDRAM['lang']['label_your_ip'] = 'آپ کے IP:';
$CIDRAM['lang']['label_your_ua'] = 'آپ کے UA:';
$CIDRAM['lang']['link_accounts'] = 'اکاؤنٹس';
$CIDRAM['lang']['link_cache_data'] = 'کیش ڈیٹا';
$CIDRAM['lang']['link_cidr_calc'] = 'CIDR کیلکولیٹر';
$CIDRAM['lang']['link_config'] = 'کنفگریشن';
$CIDRAM['lang']['link_documentation'] = 'دستاویزی';
$CIDRAM['lang']['link_file_manager'] = 'فائل منیجر';
$CIDRAM['lang']['link_home'] = 'ہوم';
$CIDRAM['lang']['link_ip_aggregator'] = 'IP مجموعی طور پر';
$CIDRAM['lang']['link_ip_test'] = 'IP ٹیسٹ';
$CIDRAM['lang']['link_ip_tracking'] = 'IP ٹریکنگ';
$CIDRAM['lang']['link_logs'] = 'لاگز';
$CIDRAM['lang']['link_range'] = 'رینج میزیں';
$CIDRAM['lang']['link_sections_list'] = 'حصوں کی فہرست';
$CIDRAM['lang']['link_statistics'] = 'اعداد و شمار';
$CIDRAM['lang']['link_textmode'] = 'ٹیکسٹ فارمیٹنگ: <a href="%1$sfalse%2$s">سادہ</a> – <a href="%1$strue%2$s">خوبصورت</a> – <a href="%1$stally%2$s">ٹیلی</a>';
$CIDRAM['lang']['link_updates'] = 'تازہ ترین معلومات کے';
$CIDRAM['lang']['logs_logfile_doesnt_exist'] = 'منتخب شدہ لاگ فائل موجود نہیں ہے!';
$CIDRAM['lang']['logs_no_logfile_selected'] = 'کوئی لاگ فائل کو منتخب کیا.';
$CIDRAM['lang']['logs_no_logfiles_available'] = 'کوئی لاگ مسلیں دستیاب.';
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
$CIDRAM['lang']['response_checksum_error'] = 'حیض کی خرابی! فائل کو مسترد کر دیا!';
$CIDRAM['lang']['response_component_successfully_installed'] = 'اجزاء کامیابی سے نصب.';
$CIDRAM['lang']['response_component_successfully_uninstalled'] = 'اجزاء کامیابی سے ان انسٹال.';
$CIDRAM['lang']['response_component_successfully_updated'] = 'اجزاء کامیابی سے اپ ڈیٹ.';
$CIDRAM['lang']['response_component_uninstall_error'] = 'جزو انسٹال کرنے کی کوشش کرتے ہوئے ایک خرابی واقع ہوئی.';
$CIDRAM['lang']['response_configuration_updated'] = 'کنفگریشن کامیابی سے اپ ڈیٹ.';
$CIDRAM['lang']['response_deactivated'] = 'کامیابی کے ساتھ غیر فعال.';
$CIDRAM['lang']['response_deactivation_failed'] = 'غیر فعال کرنے میں ناکام ہو گیا!';
$CIDRAM['lang']['response_delete_error'] = 'حذف کرنے میں ناکام!';
$CIDRAM['lang']['response_directory_deleted'] = 'ڈائریکٹری کامیابی سے خارج!';
$CIDRAM['lang']['response_directory_renamed'] = 'ڈائریکٹری کامیابی سے نام تبدیل کر دیا!';
$CIDRAM['lang']['response_error'] = 'خرابی';
$CIDRAM['lang']['response_failed_to_install'] = 'انسٹال کرنے میں ناکام';
$CIDRAM['lang']['response_failed_to_update'] = 'اپ ڈیٹ کرنے میں ناکام';
$CIDRAM['lang']['response_file_deleted'] = 'کامیابی خارج کر دیا فائل!';
$CIDRAM['lang']['response_file_edited'] = 'کامیابی نظر ثانی شدہ فائل!';
$CIDRAM['lang']['response_file_renamed'] = 'کامیابی کا نام دے دیا فائل!';
$CIDRAM['lang']['response_file_uploaded'] = 'کامیابی اپ لوڈ کردہ فائل!';
$CIDRAM['lang']['response_login_invalid_password'] = 'لاگ ان ناکامی! غلط پاسورڈ!';
$CIDRAM['lang']['response_login_invalid_username'] = 'لاگ ان ناکامی! صارف کا نام موجود نہیں ہے!';
$CIDRAM['lang']['response_login_password_field_empty'] = 'پاس ورڈ میدان خالی!';
$CIDRAM['lang']['response_login_username_field_empty'] = 'صارف کا نام فیلڈ کو خالی!';
$CIDRAM['lang']['response_login_wrong_endpoint'] = 'غلط رسائی پوائنٹ!';
$CIDRAM['lang']['response_no'] = 'نہیں';
$CIDRAM['lang']['response_possible_problem_found'] = 'ممکنہ مسئلہ پایا.';
$CIDRAM['lang']['response_rename_error'] = 'نام تبدیل کرنے میں ناکام!';
$CIDRAM['lang']['response_sanity_1'] = 'فائل میں غیر متوقع مواد شامل ہے! فائل کو مسترد کر دیا!';
$CIDRAM['lang']['response_statistics_cleared'] = 'اعداد و شمار صاف ہوگئے.';
$CIDRAM['lang']['response_tracking_cleared'] = 'صاف کر دیا ٹریکنگ.';
$CIDRAM['lang']['response_updates_already_up_to_date'] = 'پہلے سے اپ ڈیٹ.';
$CIDRAM['lang']['response_updates_not_installed'] = 'اجزاء انسٹال نہیں!';
$CIDRAM['lang']['response_updates_not_installed_php'] = 'اجزاء انسٹال نہیں (PHP ضرورت ہوتی {V})!';
$CIDRAM['lang']['response_updates_outdated'] = 'فرسودہ!';
$CIDRAM['lang']['response_updates_outdated_manually'] = 'فرسودہ (دستی طور پر اپ ڈیٹ کریں)!';
$CIDRAM['lang']['response_updates_outdated_php_version'] = 'فرسودہ (درکار PHP &gt;= {V})!';
$CIDRAM['lang']['response_updates_unable_to_determine'] = 'اس بات کا تعین کرنے سے قاصر ہے.';
$CIDRAM['lang']['response_upload_error'] = 'اپ لوڈ کرنے میں ناکام ہو گیا!';
$CIDRAM['lang']['response_verification_failed'] = 'توثیقی ناکام ہوگئی! اجزاء خراب ہوسکتا ہے.';
$CIDRAM['lang']['response_verification_success'] = 'توثیقی کامیابی! کوئی مسئلہ نہیں ملا.';
$CIDRAM['lang']['response_yes'] = 'جی ہاں';
$CIDRAM['lang']['state_async_deny'] = 'اجازت غیر عارضی درخواستوں کو انجام دینے کے لئے کافی نہیں ہے. دوبارہ لاگ ان کرنے کی کوشش کریں.';
$CIDRAM['lang']['state_cache_is_empty'] = 'کیش خالی ہے.';
$CIDRAM['lang']['state_complete_access'] = 'مکمل رسائی';
$CIDRAM['lang']['state_component_is_active'] = 'جزو فعال ہے.';
$CIDRAM['lang']['state_component_is_inactive'] = 'اجزاء غیر فعال ہے.';
$CIDRAM['lang']['state_component_is_provisional'] = 'اجزاء عارضی ہے.';
$CIDRAM['lang']['state_default_password'] = 'انتباہ: ڈیفالٹ پاس ورڈ کو استعمال کرتے ہوئے!';
$CIDRAM['lang']['state_ignored'] = 'نظر انداز';
$CIDRAM['lang']['state_loading'] = 'لوڈ کر رہا ہے ...';
$CIDRAM['lang']['state_loadtime'] = '<span class="txtRd">%s</span> سیکنڈ میں مکمل ہونے والی درخواست کی درخواست.';
$CIDRAM['lang']['state_logged_in'] = 'لاگ.';
$CIDRAM['lang']['state_logs_access_only'] = 'لاگز صرف رسائی';
$CIDRAM['lang']['state_maintenance_mode'] = 'انتباہ: بحالی کا موڈ فعال ہے!';
$CIDRAM['lang']['state_password_not_valid'] = 'انتباہ: یہ اکاؤنٹ ایک درست پاس ورڈ کا استعمال نہیں کر رہا ہے!';
$CIDRAM['lang']['state_risk_high'] = 'اعلی';
$CIDRAM['lang']['state_risk_low'] = 'کم';
$CIDRAM['lang']['state_risk_medium'] = 'درمیانہ';
$CIDRAM['lang']['state_sl_totals'] = 'مجموعی طور پر (دستخط: <span class="txtRd">%s</span> – دستخط حصوں: <span class="txtRd">%s</span> – دستخط فائلیں: <span class="txtRd">%s</span>).';
$CIDRAM['lang']['state_tracking'] = 'فی الحال %s IP ٹریکنگ.';
$CIDRAM['lang']['switch-hide-non-outdated-set-false'] = 'غیر فرسودہ مت چھپاو';
$CIDRAM['lang']['switch-hide-non-outdated-set-true'] = 'غیر فرسودہ چھپائیں';
$CIDRAM['lang']['switch-hide-unused-set-false'] = 'غیر استعمال شدہ مت چھپاو';
$CIDRAM['lang']['switch-hide-unused-set-true'] = 'غیر استعمال شدہ چھپائیں';
$CIDRAM['lang']['switch-tracking-blocked-already-set-false'] = 'دستخط فائلوں کے خلاف چیک نہ کریں';
$CIDRAM['lang']['switch-tracking-blocked-already-set-true'] = 'دستخط فائلوں کے خلاف چیک کریں';
$CIDRAM['lang']['switch-tracking-hide-banned-blocked-set-false'] = 'ممنوعہ/بلاک شدہ IP کو چھپانا مت چھوڑیں';
$CIDRAM['lang']['switch-tracking-hide-banned-blocked-set-true'] = 'ممنوعہ/بلاک شدہ IP چھپائیں';
$CIDRAM['lang']['tip_accounts'] = 'ہیلو، {username}.<br />اکاؤنٹس صفحہ آپ CIDRAM سامنے کے آخر تک رسائی حاصل کر سکتے ہیں جو کنٹرول کرنے کی اجازت دیتا ہے.';
$CIDRAM['lang']['tip_cache_data'] = 'ہیلو، {username}.<br />یہاں آپ کیش کے مندرجات کا جائزہ لے سکتے ہیں.';
$CIDRAM['lang']['tip_cidr_calc'] = 'ہیلو، {username}.<br />CIDR کیلکولیٹر آپ کو ایک IP ایڈریس CIDRs جس کا حساب کرنے کی اجازت دیتا ہے کا ایک عنصر ہے.';
$CIDRAM['lang']['tip_config'] = 'ہیلو، {username}.<br />ترتیب کے صفحے آپ کو سامنے کے آخر میں سے CIDRAM لئے ترتیب میں ترمیم کی اجازت دیتا ہے.';
$CIDRAM['lang']['tip_custom_ua'] = 'یہاں صارف ایجنٹ (user agent) درج کریں (یہ اختیاری ہے).';
$CIDRAM['lang']['tip_donate'] = 'CIDRAM بلا معاوضہ پیش کی جاتی ہے، لیکن آپ کو اس منصوبے کے لئے عطیہ کرنا چاہتے ہیں تو، آپ کو عطیہ کے بٹن پر کلک کر کے ایسا کر سکتے ہیں.';
$CIDRAM['lang']['tip_enter_ip_here'] = 'یہاں IP درج.';
$CIDRAM['lang']['tip_enter_ips_here'] = 'یہاں IP ایس کے درج.';
$CIDRAM['lang']['tip_fe_cookie_warning'] = 'نوٹ: لاگز کی تصدیق کے لئے CIDRAM کوکی کا استعمال کرتا ہے. لاگ ان کرکے، آپ کو اپنے براؤزر کی طرف سے تخلیق اور ذخیرہ کرنے کے لئے ایک کوکی کے لئے آپ کی رضامندی دیتے ہیں.';
$CIDRAM['lang']['tip_file_manager'] = 'ہیلو، {username}.<br />فائل مینیجر آپ کو، کو حذف ترمیم کریں، اپ لوڈ، اور فائلوں کو ڈاؤن لوڈ کرنے کی اجازت دیتا ہے. احتیاط کے ساتھ استعمال کریں (آپ کو اس کے ساتھ آپ کی تنصیب توڑ سکتا ہے).';
$CIDRAM['lang']['tip_home'] = 'ہیلو، {username}.<br />یہ CIDRAM سامنے کے آخر میں کے ہوم پیج ہے. جاری رکھنے کے لئے بائیں طرف نیویگیشن مینو میں سے ایک لنک کو منتخب کریں.';
$CIDRAM['lang']['tip_ip_aggregator'] = 'ہیلو، {username}.<br />IP مجموعی طور پر آپ کو کم سے کم ممکنہ طریقے سے IP اور CIDR کا اظہار کرنے کی اجازت دیتا ہے. جمع کرنے کے لئے اعداد و شمار درج کریں اور "ٹھیک ہے" دبائیں.';
$CIDRAM['lang']['tip_ip_test'] = 'ہیلو، {username}.<br />IP ٹیسٹ کے صفحے آپ کو ٹیسٹ کرنے IP پتوں موجودہ میں انسٹال دستخط کی طرف سے بلاک کر رہے ہیں کہ آیا کی اجازت دیتا ہے.';
$CIDRAM['lang']['tip_ip_test_module_switch'] = '(جب منتخب نہ ہو، صرف دستخط فائلوں کے خلاف ٹیسٹ کیا جائے گا).';
$CIDRAM['lang']['tip_ip_tracking'] = 'ہیلو، {username}.<br />IP باخبر رہنے کے صفحے آپ / ان میں سے جو پابندی عائد کی گئی ہے کو چیک کرنے کے، اور پابندی ہٹانے کی، IP پتوں میں سے باخبر رہنے کے کی حیثیت کی جانچ کرنا اگر آپ ایسا کرنا چاہتے ہیں تو انہیں untrack کی اجازت دیتا ہے.';
$CIDRAM['lang']['tip_login'] = 'پہلے سے طے شدہ صارف نام: <span class="txtRd">admin</span> – ڈیفالٹ پاس ورڈ: <span class="txtRd">password</span>';
$CIDRAM['lang']['tip_logs'] = 'ہیلو، {username}.<br />کہ لاگ فائل کے مواد کو دیکھنے کے لئے ذیل کی فہرست سے ایک لاگ فائل منتخب کریں.';
$CIDRAM['lang']['tip_range'] = 'ہیلو، {username}.<br />یہ صفحہ فی الحال فعال دستخط شدہ فائلوں کی طرف سے احاطہ کرتا IP حدود کے بارے میں کچھ بنیادی اعداد و شمار کی معلومات کو ظاہر کرتا ہے.';
$CIDRAM['lang']['tip_sections_list'] = 'ہیلو، {username}.<br />اس صفحے کی فہرست اس فہرست میں موجود ہے جس میں فی الحال فعال دستخط فائلوں میں موجود ہیں.';
$CIDRAM['lang']['tip_see_the_documentation'] = 'ملاحظہ کریں <a href="https://github.com/CIDRAM/CIDRAM/blob/master/_docs/readme.ur.md#SECTION6">دستاویزی</a> مختلف ترتیب ہدایات اور ان کے مقاصد کے بارے میں معلومات کے لئے.';
$CIDRAM['lang']['tip_statistics'] = 'ہیلو، {username}.<br />یہ صفحہ آپ کے CIDRAM کی تنصیب کے لئے کچھ استعمال کے اعداد و شمار ظاہر کرتا ہے.';
$CIDRAM['lang']['tip_statistics_disabled'] = 'نوٹ: اعداد و شمار کی ٹریکنگ فی الحال غیر فعال ہے، لیکن کنفگریشن کے صفحے کے ذریعہ فعال ہوسکتا ہے.';
$CIDRAM['lang']['tip_updates'] = 'ہیلو، {username}.<br />اپ ڈیٹس صفحہ آپ کو نصب کی اجازت دیتا ہے کے لئے، انسٹال، اور CIDRAM (بنیادی پیکج، دستخط، L10N فائلوں، وغیرہ) کے مختلف اجزاء کو اپ ڈیٹ.';
$CIDRAM['lang']['title_accounts'] = 'CIDRAM – اکاؤنٹس';
$CIDRAM['lang']['title_cache_data'] = 'CIDRAM – کیش ڈیٹا';
$CIDRAM['lang']['title_cidr_calc'] = 'CIDRAM – CIDR کیلکولیٹر';
$CIDRAM['lang']['title_config'] = 'CIDRAM – کنفگریشن';
$CIDRAM['lang']['title_file_manager'] = 'CIDRAM – فائل مینیجر';
$CIDRAM['lang']['title_home'] = 'CIDRAM – ہوم';
$CIDRAM['lang']['title_ip_aggregator'] = 'CIDRAM – IP مجموعی طور پر';
$CIDRAM['lang']['title_ip_test'] = 'CIDRAM – IP ٹیسٹ';
$CIDRAM['lang']['title_ip_tracking'] = 'CIDRAM – IP ٹریکنگ';
$CIDRAM['lang']['title_login'] = 'CIDRAM – لاگ ان';
$CIDRAM['lang']['title_logs'] = 'CIDRAM – لاگز';
$CIDRAM['lang']['title_range'] = 'CIDRAM – رینج میزیں';
$CIDRAM['lang']['title_sections_list'] = 'CIDRAM – حصوں کی فہرست';
$CIDRAM['lang']['title_statistics'] = 'CIDRAM – اعداد و شمار';
$CIDRAM['lang']['title_updates'] = 'CIDRAM – تازہ ترین معلومات کے';
$CIDRAM['lang']['warning'] = 'انتباہ:';
$CIDRAM['lang']['warning_php_1'] = 'آپ کے PHP ورژن اب فعال طور پر معاون نہیں ہے! اپ ڈیٹ کرنے کی سفارش کی گئی ہے!';
$CIDRAM['lang']['warning_php_2'] = 'آپ کے PHP ورژن شدید خطرناک ہے! اپ ڈیٹ کرنا سختی کی سفارش کی جاتی ہے!';
$CIDRAM['lang']['warning_signatures_1'] = 'کوئی دستخط فائلیں فعال نہیں ہیں!';

$CIDRAM['lang']['info_some_useful_links'] = 'کچھ مفید لنکس:<ul>
            <li><a href="https://github.com/CIDRAM/CIDRAM/issues" dir="ltr">CIDRAM Issues @ GitHub</a> – CIDRAM لئے مسائل کا صفحہ (کی حمایت، مدد، وغیرہ).</li>
            <li><a href="https://wordpress.org/plugins/cidram/" dir="ltr">CIDRAM @ WordPress.org</a> – CIDRAM کے لئے ورڈپریس پلگ ان.</li>
            <li><a href="https://websectools.com/" dir="ltr">WebSecTools.com</a> – ویب سائٹس کو محفوظ بنانے کے لئے سادہ ویب ماسٹر ٹولز کا ایک مجموعہ.</li>
            <li><a href="https://bitbucket.org/macmathan/blocklists" dir="ltr">macmathan/blocklists</a> – CIDRAM کے لئے اختیاری بلاسٹ لسٹ اور ماڈیولز پر مشتمل ہے جیسے خطرناک بٹس کو روکنے، ناپسندیدہ ممالک، پرانے براؤزر وغیرہ.</li>
            <li><a href="https://www.facebook.com/groups/2204685680/" dir="ltr">Global PHP Group @ Facebook</a> – PHP سیکھنے کے وسائل اور بحث.</li>
            <li><a href="https://php.earth/" dir="ltr">PHP.earth</a> – PHP سیکھنے کے وسائل اور بحث.</li>
            <li><a href="http://bgp.he.net/" dir="ltr">Hurricane Electric BGP Toolkit</a> –، ASNs سے CIDRs حاصل کریں ASN رشتے کا تعین نیٹ ورک ناموں، وغیرہ کی بنیاد پر ASNs دریافت.</li>
            <li><a href="https://www.stopforumspam.com/forum/" dir="ltr">Forum @ Stop Forum Spam</a> – فورم کے سپیم روکنے کے بارے میں مفید فورم.</li>
            <li><a href="https://radar.qrator.net/" dir="ltr">Radar by Qrator</a> – ASNs کی کنیکٹوٹی کی جانچ پڑتال کے لئے اس کے ساتھ ساتھ ASNs بارے مختلف دیگر معلومات کے لئے مفید آلہ.</li>
            <li><a href="http://www.ipdeny.com/ipblocks/" dir="ltr">IPdeny IP country blocks</a> – ملک بھر دستخط پیدا کرنے کے لئے ایک تصوراتی، بہترین اور درست سروس.</li>
            <li><a href="https://www.google.com/transparencyreport/safebrowsing/malware/" dir="ltr">Google Malware Dashboard</a> – ASNs لئے میلویئر انفیکشن کی شرح کے حوالے سے دکھاتا رپورٹیں.</li>
            <li><a href="https://www.spamhaus.org/statistics/botnet-asn/" dir="ltr">The Spamhaus Project</a> – ASNs لئے کی botnet انفیکشن کی شرح کے حوالے سے دکھاتا رپورٹیں.</li>
            <li><a href="https://www.abuseat.org/public/asn.html" dir="ltr">Abuseat.org\'s Composite Blocking List</a> – ASNs لئے کی botnet انفیکشن کی شرح کے حوالے سے دکھاتا رپورٹیں.</li>
            <li><a href="https://abuseipdb.com/" dir="ltr">AbuseIPDB</a> – نام سے جانا توہین آمیز IP ایس کی ایک ڈیٹا بیس کو برقرار رکھتا ہے؛ IP ایس کی جانچ پڑتال اور رپورٹنگ کے لئے ایک API فراہم.</li>
            <li><a href="https://www.megarbl.net/index.php" dir="ltr">MegaRBL.net</a> – نام سے جانا جاتا ردی باز کو کی لسٹنگ برقرار رکھتا ہے؛ IP / ASN کو فضول سرگرمیوں کی جانچ پڑتال کے لیے مفید.</li>
            <li><a href="https://maikuolan.github.io/Vulnerability-Charts/" dir="ltr">Vulnerability Charts</a> – مختلف پیکجوں کے محفوظ اور غیر محفوظ ورژن لیتے ہیں (HHVM، PHP، phpMyAdmin، Python، وغیرہ).</li>
            <li><a href="https://maikuolan.github.io/Compatibility-Charts/" dir="ltr">Compatibility Charts</a> – مختلف پیکجوں کے لئے مطابقت کی معلومات فہرست (CIDRAM، phpMussel، وغیرہ).</li>
        </ul>';
