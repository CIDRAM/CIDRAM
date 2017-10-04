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
 * This file: Bangla language data for the front-end (last modified: 2017.10.03).
 */

/** Prevents execution from outside of CIDRAM. */
if (!defined('CIDRAM')) {
    die('[CIDRAM] This should not be accessed directly.');
}

$CIDRAM['lang']['bNav_home_logout'] = '<a href="?">হোম পেজ</a> | <a href="?cidram-page=logout">প্রস্থান</a>';
$CIDRAM['lang']['bNav_logout'] = '<a href="?cidram-page=logout">প্রস্থান</a>';
$CIDRAM['lang']['config_general_ban_override'] = 'যখন "infraction_limit" অতিক্রম করা হয় তখন "forbid_on_block" ওভাররাইড করে? ওভাররাইড করার সময়: ব্লক করা অনুরোধগুলি একটি ফাঁকা পৃষ্ঠাটি ফেরত পাঠায় (টেমপ্লেট ফাইল ব্যবহার করা হয় না)। 200 = ওভাররাইড করবেন না [ডিফল্ট]; 403 = "403 Forbidden" এর সাথে ওভাররাইড করুন; 503 = "503 Service unavailable" এর সাথে ওভাররাইড করুন।';
$CIDRAM['lang']['config_general_default_algo'] = 'সব ভবিষ্যত পাসওয়ার্ড এবং সেশন জন্য ব্যবহার করে অ্যালগরিদম সংজ্ঞায়িত করে। বিকল্প: PASSWORD_DEFAULT (ডিফল্ট), PASSWORD_BCRYPT, PASSWORD_ARGON2I (PHP >= 7.2.0 প্রয়োজন)।';
$CIDRAM['lang']['config_general_default_dns'] = 'হোস্টনামগুলি দেখার জন্য ডিএনএস সার্ভারগুলির একটি কমা দ্বারা পৃথক তালিকা। ডিফল্ট = "8.8.8.8,8.8.4.4" (Google DNS)। সতর্কতা: আপনি কি করছেন তা না জানলে তা পরিবর্তন করবেন না!';
$CIDRAM['lang']['config_general_disable_cli'] = 'CLI মোড অক্ষম?';
$CIDRAM['lang']['config_general_disable_frontend'] = 'ফ্রন্ট-এন্ড অ্যাক্সেস অক্ষম করুন?';
$CIDRAM['lang']['config_general_disable_webfonts'] = 'ওয়েবফটগুলি অক্ষম করবেন? True = হাঁ; False = না [ডিফল্ট]।';
$CIDRAM['lang']['config_general_emailaddr'] = 'তুমি যদি চাও, আপনি ব্যবহারকারীদের জন্য একটি ইমেল ঠিকানা সরবরাহ করতে পারেন, তারা অবরুদ্ধ যখন প্রদর্শিত, ত্রুটির কারণে ব্লক হওয়ার সময় তাদের সহায়তার জন্য যোগাযোগের একটি বিন্দু হিসাবে ব্যবহার করার জন্য। সতর্কতা: আপনি যে ইমেল ঠিকানাটি সরবরাহ করেন তা অবশ্যই স্প্যামবট এবং স্ক্রাপারের মাধ্যমে অবশ্যই ব্যবহার করা হবে, এবং তাই, যদি আপনি এখানে একটি ইমেইল ঠিকানা সরবরাহ করার সিদ্ধান্ত নেন, এটি দৃঢ়ভাবে সুপারিশ করা হয় যে আপনি নিশ্চিত করেন যে আপনি যে ইমেল ঠিকানাটি সরবরাহ করেছেন তা একটি ডিসপোজেবল ঠিকানা অথবা এমন কোনও ঠিকানা যা আপনি স্প্যাম করার ব্যাপারে উদাসীন নন (অন্য কথায়, আপনি সম্ভবত আপনার প্রাথমিক ব্যক্তিগত বা প্রাথমিক ব্যবসা ইমেল ঠিকানা ব্যবহার করতে চান না)।';
$CIDRAM['lang']['config_general_emailaddr_display_style'] = 'ব্যবহারকারীদের কাছে ইমেল ঠিকানা কীভাবে উপস্থাপন করা উচিত?';
$CIDRAM['lang']['config_general_forbid_on_block'] = 'অনুরোধগুলি ব্লক করার সময় কোন হেডার ব্যবহার করা হবে?';
$CIDRAM['lang']['config_general_FrontEndLog'] = 'ফ্রন্ট-এন্ড লগইন প্রচেষ্টা রেকর্ড করার জন্য ফাইল। ফাইলের নাম উল্লেখ করুন, অথবা নিষ্ক্রিয় করতে ফাঁকা রাখুন।';
$CIDRAM['lang']['config_general_ipaddr'] = 'ইনবাউন্ড অনুরোধের আইপি ঠিকানা কোথায় পাওয়া যায়? (যেমন Cloudflare এবং ইত্যাদি সেবা জন্য দরকারী)। ডিফল্ট = REMOTE_ADDR। সতর্কতা: আপনি কি করছেন তা না জানলে তা পরিবর্তন করবেন না!';
$CIDRAM['lang']['config_general_lang'] = 'CIDRAM এর জন্য ডিফল্ট ভাষা নির্দিষ্ট করুন।';
$CIDRAM['lang']['config_general_logfile'] = 'সব অবরুদ্ধ অ্যাক্সেস প্রচেষ্টা রেকর্ড করার জন্য মানব পাঠযোগ্য ফাইল। ফাইলের নাম উল্লেখ করুন, অথবা নিষ্ক্রিয় করতে ফাঁকা রাখুন।';
$CIDRAM['lang']['config_general_logfileApache'] = 'সব অবরুদ্ধ অ্যাক্সেস প্রচেষ্টা রেকর্ড করার জন্য Apache-শৈলী ফাইল। ফাইলের নাম উল্লেখ করুন, অথবা নিষ্ক্রিয় করতে ফাঁকা রাখুন।';
$CIDRAM['lang']['config_general_logfileSerialized'] = 'সব অবরুদ্ধ অ্যাক্সেস প্রচেষ্টা রেকর্ড করার জন্য serialized ফাইল। ফাইলের নাম উল্লেখ করুন, অথবা নিষ্ক্রিয় করতে ফাঁকা রাখুন।';
$CIDRAM['lang']['config_general_log_banned_ips'] = 'লগ ফাইলগুলিতে নিষিদ্ধ IP ঠিকানাগুলি থেকে অবরুদ্ধ অনুরোধ অন্তর্ভুক্ত করুন? True = হাঁ [ডিফল্ট]; False = না।';
$CIDRAM['lang']['config_general_maintenance_mode'] = 'রক্ষণাবেক্ষণ মোড সক্ষম করবেন? True = হাঁ; False = না [ডিফল্ট]। ফ্রন্ট-এন্ড ছাড়া অন্য সব কিছু অক্ষম করে। আপনার CMS, ফ্রেমওয়ার্ক, ইত্যাদি আপডেট করার সময় কখনও কখনও দরকারী।';
$CIDRAM['lang']['config_general_max_login_attempts'] = 'লগইন প্রচেষ্টা সর্বাধিক সংখ্যা।';
$CIDRAM['lang']['config_general_numbers'] = 'কিভাবে সংখ্যা প্রদর্শন করা উচিত? উদাহরণটি নির্বাচন করুন যা আপনার কাছে সবচেয়ে সঠিক দেখায়।';
$CIDRAM['lang']['config_general_protect_frontend'] = 'CIDRAM কর্তৃক প্রদত্ত সুরক্ষাগুলি ফ্রন্ট-এ-তে প্রয়োগ করা হবে কিনা তা উল্লেখ করে। True = হাঁ [ডিফল্ট]; False = না।';
$CIDRAM['lang']['config_general_search_engine_verification'] = 'অনুসন্ধান ইঞ্জিন থেকে অনুরোধ যাচাই করার চেষ্টা? সার্চ ইঞ্জিনগুলি যাচাই করা নিশ্চিত করে যে তারা ইনফেকশন সীমা অতিক্রম করার ফলে তাদের নিষিদ্ধ করা হবে না (সার্চ ইঞ্জিনগুলি নিষিদ্ধ করা সাধারণত আপনার সার্চ ইঞ্জিন র্যাংকিং, SEO, ইত্যাদি একটি নেতিবাচক ভাবে প্রভাবিত করবে)। যাচাই করা হলে, অনুসন্ধান ইঞ্জিন স্বাভাবিক হিসাবে ব্লক করা যেতে পারে, কিন্তু নিষিদ্ধ করা হবে না। যাচাই না করা হলে, তাদের জন্য নিষ্ক্রিয়তা সীমা অতিক্রম করা হলে এটি তাদের জন্য নিষিদ্ধ করা সম্ভব। উপরন্তু, অনুসন্ধান ইঞ্জিন যাচাই জাল সার্চ ইঞ্জিন অনুরোধের বিরুদ্ধে সুরক্ষা প্রদান করে (অনুসন্ধান ইঞ্জিন যাচাইকরণ সক্ষম করা হলে এই জাল অনুরোধগুলিকে অবরুদ্ধ করা হবে)। True = সার্চ ইঞ্জিন যাচাইকরণ সক্ষম করুন [ডিফল্ট]; False = অনুসন্ধান ইঞ্জিন যাচাইকরণ অক্ষম করুন।';
$CIDRAM['lang']['config_general_silent_mode'] = 'অবরুদ্ধ অ্যাক্সেসের প্রচেষ্টাগুলি পুনঃনির্দেশ করুন ("প্রবেশাধিকার অস্বীকার" পৃষ্ঠাটি প্রদর্শন করার পরিবর্তে)? যদি তাই, ব্লক অ্যাক্সেসের প্রচেষ্টাগুলি পুনর্নির্দেশ করতে অবস্থানটি নির্দিষ্ট করুন। যদি না, এই পরিবর্তনশীল খালি রাখুন।';
$CIDRAM['lang']['config_general_statistics'] = 'CIDRAM ব্যবহারের পরিসংখ্যান ট্র্যাক? True = হাঁ; False = না [ডিফল্ট]।';
$CIDRAM['lang']['config_general_timeFormat'] = 'CIDRAM দ্বারা ব্যবহৃত তারিখ/সময় উল্লেখ ফরম্যাট। অনুরোধ উপর অতিরিক্ত বিকল্প যোগ করা যেতে পারে।';
$CIDRAM['lang']['config_general_timeOffset'] = 'টাইমজোন মিনিটে অফসেট।';
$CIDRAM['lang']['config_general_timezone'] = 'আপনার টাইমজোন।';
$CIDRAM['lang']['config_general_truncate'] = 'একটি নির্দিষ্ট আকারে পৌঁছানোর সময় লগ ফাইলগুলি কেটে ফেলা হবে? লগ ফাইলগুলির জন্য B/KB/MB/GB/TB এ মান সর্বাধিক অনুমোদিত আকার (এই আকারের বাইরে, লগ ফাইলগুলি ছোট করা হবে)। ডিফল্ট মান 0KB ট্রুনাকশন নিষ্ক্রিয় করবে (লগ ফাইলগুলি অনির্দিষ্টকালের জন্য বাড়তে পারে)। দয়া করে নোট করুন: এটি স্বতন্ত্র লগ ফাইলগুলিতে প্রযোজ্য! লগ ফাইলের আকার সমষ্টিগতভাবে বিবেচনা করা হয় না।';
$CIDRAM['lang']['config_recaptcha_expiry'] = 'reCAPTCHA দৃষ্টান্তগুলি মনে রাখার জন্য ঘন্টাগুলির সংখ্যা।';
$CIDRAM['lang']['config_recaptcha_lockip'] = 'reCAPTCHA IP অ্যাড্রেসগুলি লক করুন?';
$CIDRAM['lang']['config_recaptcha_lockuser'] = 'reCAPTCHA ব্যবহারকারীদের লক করুন?';
$CIDRAM['lang']['config_recaptcha_logfile'] = 'সব reCAPTCHA প্রচেষ্টা লগ ইন করুন? যদি তাই, লগ ফাইলে ব্যবহারের জন্য নামটি উল্লেখ করুন। যদি না, এই পরিবর্তনশীল খালি রাখুন।';
$CIDRAM['lang']['config_recaptcha_secret'] = 'এই মান আপনার reCAPTCHA জন্য "secret key" অনুরূপ করা উচিত (reCAPTCHA ড্যাশবোর্ডের মধ্যে পাওয়া যেতে পারে)।';
$CIDRAM['lang']['config_recaptcha_sitekey'] = 'এই মান আপনার reCAPTCHA জন্য "site key" অনুরূপ করা উচিত (reCAPTCHA ড্যাশবোর্ডের মধ্যে পাওয়া যেতে পারে)।';
$CIDRAM['lang']['config_recaptcha_usemode'] = 'কিভাবে CIDRAM reCAPTCHA ব্যবহার করা উচিত তা নির্ধারণ করে (ডকুমেন্টেশন দেখুন)।';
$CIDRAM['lang']['config_signatures_block_bogons'] = 'bogon/মঙ্গল CIDRগুলি ব্লক কর? আপনার স্থানীয় নেটওয়ার্কে, localhost, LAN, ইত্যাদি থেকে আপনার ওয়েবসাইট সংযোগ, এই নির্দেশটি false হিসাবে সেট করা উচিত। আপনি যদি এই ধরনের সংযোগগুলি আশা না করেন, এই নির্দেশটি true হিসাবে সেট করা উচিত।';
$CIDRAM['lang']['config_signatures_block_cloud'] = 'ওয়েব হোস্টিং এবং ক্লাউড পরিষেবাগুলির সাথে সম্পর্কিত হিসাবে CIDRগুলি ব্লক কর? আপনি যদি আপনার ওয়েবসাইট থেকে একটি API সেবা পরিচালনা করেন, অথবা যদি আপনি অন্য ওয়েবসাইটে আপনার ওয়েবসাইট সংযোগ করতে আশা, এই false সেট করা উচিত। অন্যভাবে, যদি না, এই true সেট করা উচিত।';
$CIDRAM['lang']['config_signatures_block_generic'] = 'ব্ল্যাকলিস্টের জন্য CIDRগুলি সুপারিশ করেছে ব্লক কর? এটি কোন স্বাক্ষর বিবেচনা করে যা অন্য কোনও অংশ হিসাবে চিহ্নিত করা হয় না।';
$CIDRAM['lang']['config_signatures_block_proxies'] = 'প্রক্সি পরিষেবাগুলির সাথে সম্পর্কিত হিসাবে চিহ্নিত CIDRগুলি ব্লক কর? আপনি ব্যবহারকারীদের বেনামী প্রক্সি পরিষেবাগুলি থেকে আপনার ওয়েবসাইট অ্যাক্সেস করতে সক্ষম হবে যদি প্রয়োজন, এই false সেট করা উচিত। অন্যভাবে, যদি না, এই true সেট করা উচিত (যদি true নির্ধারণ করা হয়, তাহলে নিরাপত্তা আরো ভালো হবে)।';
$CIDRAM['lang']['config_signatures_block_spam'] = 'স্প্যামাররা সাথে যুক্ত CIDRগুলি ব্লক কর? যখন সম্ভব, এবং যদি কোনো সমস্যা না ঘটে, এই true সেট করা উচিত।';
$CIDRAM['lang']['config_signatures_default_tracktime'] = 'মডিউল দ্বারা নিষিদ্ধ IP ঠিকানাগুলি কতগুলি সেকেন্ডের ট্র্যাক করা উচিত। ডিফল্ট = 604800 (1 সপ্তাহ)।';
$CIDRAM['lang']['config_signatures_infraction_limit'] = 'IP ট্র্যাকিং দ্বারা এটি নিষিদ্ধ হওয়ার আগে একটি আইপি দ্বারা অনুমোদিত লঙ্ঘনটিকে সর্বাধিক সংখ্যা। ডিফল্ট = 10।';
$CIDRAM['lang']['config_signatures_ipv4'] = 'IPv4 স্বাক্ষর ফাইলগুলির একটি তালিকা যা CIDRAM প্রক্রিয়া করার চেষ্টা করা উচিত, কমা দ্বারা বিভক্ত।';
$CIDRAM['lang']['config_signatures_ipv6'] = 'IPv6 স্বাক্ষর ফাইলগুলির একটি তালিকা যা CIDRAM প্রক্রিয়া করার চেষ্টা করা উচিত, কমা দ্বারা বিভক্ত।';
$CIDRAM['lang']['config_signatures_modules'] = 'IPv4/IPv6 স্বাক্ষর পরীক্ষা করার পরে লোড করার জন্য মডিউল ফাইলগুলির তালিকা, কমা দ্বারা বিভক্ত।';
$CIDRAM['lang']['config_signatures_track_mode'] = 'যখন লঙ্ঘন গণনা করা উচিত? False = যখন IP মডিউল দ্বারা ব্লক করা হয়। True = যখন IP কোন কারণের জন্য ব্লক করা হয়।';
$CIDRAM['lang']['config_template_data_css_url'] = 'কাস্টম থিমগুলির জন্য CSS ফাইল URL।';
$CIDRAM['lang']['config_template_data_Magnification'] = 'ফন্ট বৃহত্তরীকরণ। ডিফল্ট = 1।';
$CIDRAM['lang']['config_template_data_theme'] = 'CIDRAM এর জন্য ডিফল্ট থিম ব্যবহার করুন।';
$CIDRAM['lang']['field_activate'] = 'সক্রিয় করা';
$CIDRAM['lang']['field_banned'] = 'নিষিদ্ধ';
$CIDRAM['lang']['field_blocked'] = 'ব্লক করা আছে';
$CIDRAM['lang']['field_clear'] = 'পরিষ্কার করুন';
$CIDRAM['lang']['field_clear_all'] = 'সব পরিষ্কার করুন';
$CIDRAM['lang']['field_clickable_link'] = 'ক্লিকযোগ্য লিঙ্ক';
$CIDRAM['lang']['field_component'] = 'কম্পোনেন্ট';
$CIDRAM['lang']['field_create_new_account'] = 'নতুন অ্যাকাউন্ট তৈরি';
$CIDRAM['lang']['field_deactivate'] = 'নিষ্ক্রিয় করা';
$CIDRAM['lang']['field_delete_account'] = 'অ্যাকাউন্ট মুছে ফেলা';
$CIDRAM['lang']['field_delete_file'] = 'মুছে ফেলা';
$CIDRAM['lang']['field_download_file'] = 'ডাউনলোড';
$CIDRAM['lang']['field_edit_file'] = 'সম্পাদন করা';
$CIDRAM['lang']['field_expiry'] = 'মেয়াদ শেষের';
$CIDRAM['lang']['field_false'] = 'False (মিথ্যা)';
$CIDRAM['lang']['field_file'] = 'ফাইল';
$CIDRAM['lang']['field_filename'] = 'ফাইলের নাম: ';
$CIDRAM['lang']['field_filetype_directory'] = 'ডিরেক্টরি';
$CIDRAM['lang']['field_filetype_info'] = '{EXT} ফাইল';
$CIDRAM['lang']['field_filetype_unknown'] = 'অজানা';
$CIDRAM['lang']['field_first_seen'] = 'প্রথম দেখা';
$CIDRAM['lang']['field_infractions'] = 'লঙ্ঘনটিকে';
$CIDRAM['lang']['field_install'] = 'ইনস্টল করুন';
$CIDRAM['lang']['field_ip_address'] = 'IP ঠিকানা';
$CIDRAM['lang']['field_latest_version'] = 'সবচেয়ে সাম্প্রতিক সংস্করণ';
$CIDRAM['lang']['field_log_in'] = 'লগ ইন করুন';
$CIDRAM['lang']['field_new_name'] = 'New name:';
$CIDRAM['lang']['field_nonclickable_text'] = 'অ-ক্লিকযোগ্য পাঠ্য';
$CIDRAM['lang']['field_ok'] = 'OK';
$CIDRAM['lang']['field_options'] = 'বিকল্প';
$CIDRAM['lang']['field_password'] = 'পাসওয়ার্ড';
$CIDRAM['lang']['field_permissions'] = 'অনুমতিসমূহ';
$CIDRAM['lang']['field_range'] = 'রেঞ্জ (প্রথম – চূড়ান্ত)';
$CIDRAM['lang']['field_rename_file'] = 'পুনঃনামকরণ';
$CIDRAM['lang']['field_reset'] = 'রিসেট';
$CIDRAM['lang']['field_set_new_password'] = 'নতুন পাসওয়ার্ড তৈরি করুন';
$CIDRAM['lang']['field_size'] = 'মোট আকার: ';
$CIDRAM['lang']['field_size_bytes'] = 'বাইট';
$CIDRAM['lang']['field_size_GB'] = 'GB';
$CIDRAM['lang']['field_size_KB'] = 'KB';
$CIDRAM['lang']['field_size_MB'] = 'MB';
$CIDRAM['lang']['field_size_TB'] = 'TB';
$CIDRAM['lang']['field_status'] = 'অবস্থা';
$CIDRAM['lang']['field_system_timezone'] = 'সিস্টেম ডিফল্ট টাইমজোন ব্যবহার করুন।';
$CIDRAM['lang']['field_tracking'] = 'পর্যবেক্ষণ';
$CIDRAM['lang']['field_true'] = 'True (সত্য)';
$CIDRAM['lang']['field_uninstall'] = 'আনইনস্টল করুন';
$CIDRAM['lang']['field_update'] = 'আপডেট করুন';
$CIDRAM['lang']['field_update_all'] = 'সব আপডেট করুন';
$CIDRAM['lang']['field_upload_file'] = 'নতুন ফাইল আপলোড করুন';
$CIDRAM['lang']['field_username'] = 'ব্যবহারকারীর নাম';
$CIDRAM['lang']['field_your_version'] = 'আপনার সংস্করণ';
$CIDRAM['lang']['header_login'] = 'চালিয়ে যেতে দয়া করে লগ ইন করুন।';
$CIDRAM['lang']['label_active_config_file'] = 'সক্রিয় কনফিগারেশন ফাইল: ';
$CIDRAM['lang']['label_banned'] = 'অনুরোধ নিষিদ্ধ';
$CIDRAM['lang']['label_blocked'] = 'অনুরোধ অবরুদ্ধ';
$CIDRAM['lang']['label_branch'] = 'শাখা সর্বশেষ স্থিতিশীল:';
$CIDRAM['lang']['label_cidram'] = 'CIDRAM সংস্করণ ব্যবহৃত:';
$CIDRAM['lang']['label_false_positive_risk'] = 'ঝুঁকিতে ইতিবাচক মিথ্যা: ';
$CIDRAM['lang']['label_fmgr_cache_data'] = 'ক্যাশ ডেটা এবং অস্থায়ী ফাইল';
$CIDRAM['lang']['label_fmgr_disk_usage'] = 'CIDRAM ডিস্ক ব্যবহার: ';
$CIDRAM['lang']['label_fmgr_free_space'] = 'বিনামূল্যে ডিস্ক স্থান: ';
$CIDRAM['lang']['label_fmgr_total_disk_usage'] = 'মোট ডিস্ক ব্যবহার: ';
$CIDRAM['lang']['label_fmgr_total_space'] = 'মোট ডিস্ক স্থান: ';
$CIDRAM['lang']['label_fmgr_updates_metadata'] = 'কম্পোনেন্ট আপডেট মেটাডেটা';
$CIDRAM['lang']['label_hide'] = 'লুকান';
$CIDRAM['lang']['label_os'] = 'অপারেটিং সিস্টেম ব্যবহৃত:';
$CIDRAM['lang']['label_other'] = 'অন্যান্য';
$CIDRAM['lang']['label_other-ActiveIPv4'] = 'সক্রিয় IPv4 স্বাক্ষর ফাইল';
$CIDRAM['lang']['label_other-ActiveIPv6'] = 'সক্রিয় IPv6 স্বাক্ষর ফাইল';
$CIDRAM['lang']['label_other-ActiveModules'] = 'সক্রিয় মডিউল';
$CIDRAM['lang']['label_other-Since'] = 'শুরুর তারিখ';
$CIDRAM['lang']['label_php'] = 'PHP সংস্করণ ব্যবহৃত:';
$CIDRAM['lang']['label_reCAPTCHA'] = 'reCAPTCHA প্রচেষ্টা';
$CIDRAM['lang']['label_results'] = 'ফলাফল (%s ইনপুট – %s প্রত্যাখ্যাত – %s গৃহীত – %s মার্জ করা – %s আউটপুট):';
$CIDRAM['lang']['label_sapi'] = 'SAPI ব্যবহৃত:';
$CIDRAM['lang']['label_show'] = 'দেখাও';
$CIDRAM['lang']['label_stable'] = 'সর্বশেষ স্থিতিশীল:';
$CIDRAM['lang']['label_sysinfo'] = 'সিস্টেম তথ্য:';
$CIDRAM['lang']['label_total'] = 'মোট';
$CIDRAM['lang']['label_unstable'] = 'সর্বশেষ অস্থিতিশীল:';
$CIDRAM['lang']['link_accounts'] = 'অ্যাকাউন্ট';
$CIDRAM['lang']['link_cidr_calc'] = 'CIDR ক্যালকুলেটর';
$CIDRAM['lang']['link_config'] = 'কনফিগারেশন';
$CIDRAM['lang']['link_documentation'] = 'ডকুমেন্টেশনটি';
$CIDRAM['lang']['link_file_manager'] = 'ফাইল ম্যানেজার';
$CIDRAM['lang']['link_home'] = 'হোম পেজ';
$CIDRAM['lang']['link_ip_aggregator'] = 'IP এগ্রিগেটর';
$CIDRAM['lang']['link_ip_test'] = 'IP টেস্ট';
$CIDRAM['lang']['link_ip_tracking'] = 'IP পর্যবেক্ষণ';
$CIDRAM['lang']['link_logs'] = 'লগ ফাইল';
$CIDRAM['lang']['link_statistics'] = 'পরিসংখ্যান';
$CIDRAM['lang']['link_textmode'] = 'পাঠ্য বিন্যাস: <a href="%1$sfalse">সহজ</a> – <a href="%1$strue">অভিনব</a>';
$CIDRAM['lang']['link_updates'] = 'আপডেট';
$CIDRAM['lang']['logs_logfile_doesnt_exist'] = 'নির্বাচিত লগ ফাইল বিদ্যমান নেই!';
$CIDRAM['lang']['logs_no_logfiles_available'] = 'কোন লগ ফাইল উপলব্ধ।';
$CIDRAM['lang']['logs_no_logfile_selected'] = 'কোনও লগ ফাইল নির্বাচিত নেই।';
$CIDRAM['lang']['max_login_attempts_exceeded'] = 'লগইন প্রচেষ্টা সর্বাধিক সংখ্যা অতিক্রম করেছে; প্রবেশাধিকার অস্বীকার।';
$CIDRAM['lang']['previewer_days'] = 'দিন';
$CIDRAM['lang']['previewer_hours'] = 'ঘন্টার';
$CIDRAM['lang']['previewer_minutes'] = 'মিনিট';
$CIDRAM['lang']['previewer_months'] = 'মাস';
$CIDRAM['lang']['previewer_seconds'] = 'সেকেন্ড';
$CIDRAM['lang']['previewer_weeks'] = 'সপ্তাহ';
$CIDRAM['lang']['previewer_years'] = 'বছর';
$CIDRAM['lang']['response_accounts_already_exists'] = 'এই ব্যবহারকারীর নামটি ইতিমধ্যেই বিদ্যমান রয়েছে!';
$CIDRAM['lang']['response_accounts_created'] = 'অ্যাকাউন্ট সফলভাবে তৈরি!';
$CIDRAM['lang']['response_accounts_deleted'] = 'অ্যাকাউন্ট সফলভাবে মোছা হয়েছে!';
$CIDRAM['lang']['response_accounts_doesnt_exist'] = 'সেই অ্যাকাউন্টটি বিদ্যমান নেই।';
$CIDRAM['lang']['response_accounts_password_updated'] = 'পাসওয়ার্ড সফলভাবে আপডেট করা হয়েছে!';
$CIDRAM['lang']['response_activated'] = 'সফলভাবে সক্রিয়।';
$CIDRAM['lang']['response_activation_failed'] = 'সক্রিয় করতে ব্যর্থ হয়েছে!';
$CIDRAM['lang']['response_checksum_error'] = 'চেকসাম ত্রুটি! ফাইল প্রত্যাখ্যাত!';
$CIDRAM['lang']['response_component_successfully_installed'] = 'কম্পোনেন্ট সফলভাবে ইনস্টল।';
$CIDRAM['lang']['response_component_successfully_uninstalled'] = 'কম্পোনেন্ট সফলভাবে আনইনস্টল।';
$CIDRAM['lang']['response_component_successfully_updated'] = 'কম্পোনেন্ট সফলভাবে আপডেট।';
$CIDRAM['lang']['response_component_uninstall_error'] = 'কম্পোনেন্ট আনইনস্টল করার চেষ্টা করার সময় একটি ত্রুটি ঘটেছে।';
$CIDRAM['lang']['response_configuration_updated'] = 'কনফিগারেশন সফলভাবে আপডেট করা।';
$CIDRAM['lang']['response_deactivated'] = 'সফলভাবে নিষ্ক্রিয়।';
$CIDRAM['lang']['response_deactivation_failed'] = 'নিষ্ক্রিয় করতে ব্যর্থ হয়েছে!';
$CIDRAM['lang']['response_delete_error'] = 'মুছে ফেলতে ব্যর্থ হয়েছে!';
$CIDRAM['lang']['response_directory_deleted'] = 'ডিরেক্টরি সফলভাবে মুছে ফেলা!';
$CIDRAM['lang']['response_directory_renamed'] = 'ডিরেক্টরি সফলভাবে পুনরায় নামকরণ করা হয়েছে!';
$CIDRAM['lang']['response_error'] = 'ত্রুটি';
$CIDRAM['lang']['response_failed_to_install'] = 'ইনস্টল করতে ব্যর্থ হয়েছে!';
$CIDRAM['lang']['response_failed_to_update'] = 'আপডেট করতে ব্যর্থ হয়েছে!';
$CIDRAM['lang']['response_file_deleted'] = 'ফাইল সফলভাবে মোছা হয়েছে!';
$CIDRAM['lang']['response_file_edited'] = 'ফাইল সফলভাবে সম্পাদিত!';
$CIDRAM['lang']['response_file_renamed'] = 'ফাইল সফলভাবে পুনরায় নামকরণ করা হয়েছে!';
$CIDRAM['lang']['response_file_uploaded'] = 'ফাইল সফলভাবে আপলোড করা হয়েছে!';
$CIDRAM['lang']['response_login_invalid_password'] = 'লগইন ব্যর্থতা! অবৈধ পাসওয়ার্ড!';
$CIDRAM['lang']['response_login_invalid_username'] = 'লগইন ব্যর্থতা! ব্যবহারকারীর নাম অস্তিত্বহীন!';
$CIDRAM['lang']['response_login_password_field_empty'] = 'পাসওয়ার্ড ক্ষেত্র খালি!';
$CIDRAM['lang']['response_login_username_field_empty'] = 'ব্যবহারকারীর নাম ক্ষেত্র খালি!';
$CIDRAM['lang']['response_no'] = 'না';
$CIDRAM['lang']['response_rename_error'] = 'নামান্তর করতে ব্যর্থ!';
$CIDRAM['lang']['response_statistics_cleared'] = 'পরিসংখ্যান পরিস্কার।';
$CIDRAM['lang']['response_tracking_cleared'] = 'পর্যবেক্ষণ মুছে ফেলা হয়েছে।';
$CIDRAM['lang']['response_updates_already_up_to_date'] = 'ইতিমধ্যে আপ-টু-ডেট।';
$CIDRAM['lang']['response_updates_not_installed'] = 'কম্পোনেন্ট ইনস্টল করা নেই!';
$CIDRAM['lang']['response_updates_not_installed_php'] = 'কম্পোনেন্ট ইনস্টল করা নেই (PHP {V} প্রয়োজন)!';
$CIDRAM['lang']['response_updates_outdated'] = 'আউটডেটেড!';
$CIDRAM['lang']['response_updates_outdated_manually'] = 'আউটডেটেড (দয়া করে ম্যানুয়ালি আপডেট করুন)!';
$CIDRAM['lang']['response_updates_outdated_php_version'] = 'আউটডেটেড (PHP {V} প্রয়োজন)!';
$CIDRAM['lang']['response_updates_unable_to_determine'] = 'নির্ধারণ করতে অক্ষম।';
$CIDRAM['lang']['response_upload_error'] = 'আপলোড করতে ব্যর্থ!';
$CIDRAM['lang']['response_yes'] = 'হাঁ';
$CIDRAM['lang']['state_complete_access'] = 'সম্পূর্ণ প্রবেশাধিকার';
$CIDRAM['lang']['state_component_is_active'] = 'কম্পোনেন্ট সক্রিয়।';
$CIDRAM['lang']['state_component_is_inactive'] = 'কম্পোনেন্ট নিষ্ক্রিয়।';
$CIDRAM['lang']['state_component_is_provisional'] = 'কম্পোনেন্ট অস্থায়ী।';
$CIDRAM['lang']['state_default_password'] = 'সতর্কতা: ডিফল্ট পাসওয়ার্ড ব্যবহার করে!';
$CIDRAM['lang']['state_loadtime'] = 'পৃষ্ঠা অনুরোধ সম্পন্ন <span class="txtRd">%s</span> সেকেন্ড।';
$CIDRAM['lang']['state_logged_in'] = 'লগ ইন আছে।';
$CIDRAM['lang']['state_logs_access_only'] = 'লগ প্রবেশাধিকার শুধুমাত্র';
$CIDRAM['lang']['state_maintenance_mode'] = 'সতর্কতা: রক্ষণাবেক্ষণ মোড সক্রিয় করা হয়!';
$CIDRAM['lang']['state_password_not_valid'] = 'সতর্কতা: এই অ্যাকাউন্টটি একটি বৈধ পাসওয়ার্ড ব্যবহার করছে না!';
$CIDRAM['lang']['state_risk_high'] = 'উচ্চ';
$CIDRAM['lang']['state_risk_low'] = 'কম';
$CIDRAM['lang']['state_risk_medium'] = 'মধ্যম';
$CIDRAM['lang']['state_tracking'] = 'বর্তমানে <span class="txtRd">%s</span> IP ঠিকানাগুলি পর্যবেক্ষণ করছে।';
$CIDRAM['lang']['switch-hide-non-outdated-set-false'] = 'অ-আউটডেটেড লুকান না';
$CIDRAM['lang']['switch-hide-non-outdated-set-true'] = 'অ-আউটডেটেড লুকান';
$CIDRAM['lang']['switch-hide-unused-set-false'] = 'অব্যবহৃত লুকান না';
$CIDRAM['lang']['switch-hide-unused-set-true'] = 'অব্যবহৃত লুকান';
$CIDRAM['lang']['switch-tracking-blocked-already-set-false'] = 'স্বাক্ষর ফাইলগুলির বিরুদ্ধে পরীক্ষা করুন না';
$CIDRAM['lang']['switch-tracking-blocked-already-set-true'] = 'স্বাক্ষর ফাইলগুলির বিরুদ্ধে পরীক্ষা করুন';
$CIDRAM['lang']['switch-tracking-hide-banned-blocked-set-false'] = 'নিষিদ্ধ/ব্লক করা আছে IP ঠিকানা লুকান না';
$CIDRAM['lang']['switch-tracking-hide-banned-blocked-set-true'] = 'নিষিদ্ধ/ব্লক করা আছে IP ঠিকানা লুকান';
$CIDRAM['lang']['tip_accounts'] = 'হ্যালো, {username}।<br />আপনি এই পৃষ্ঠাতে অ্যাকাউন্টগুলি তৈরি করতে, মুছে ফেলতে এবং সংশোধন করতে পারেন (CIDRAM ফ্রন্ট-এন্ড কে প্রবেশাধিকার করতে পারে তা নির্ধারণ করে)।';
$CIDRAM['lang']['tip_cidr_calc'] = 'হ্যালো, {username}।<br />আপনি এখানে IP অ্যাড্রেসকে গুণনীয়ক নির্ণয় করা করতে পারেন (যে CIDRগুলি IP অ্যাড্রেস এর সদস্য)।';
$CIDRAM['lang']['tip_config'] = 'হ্যালো, {username}।<br />আপনি এই পৃষ্ঠা থেকে CIDRAM কনফিগারেশন পরিবর্তন করতে।';
$CIDRAM['lang']['tip_donate'] = 'CIDRAM বিনামূল্যে প্রদান করা হয়, কিন্তু যদি আপনি এই প্রকল্পের জন্য দান করতে চান, আপনি দান বোতাম ক্লিক করে তা করতে পারেন।';
$CIDRAM['lang']['tip_enter_ips_here'] = 'IPগুলি এখানে প্রবেশ করান।';
$CIDRAM['lang']['tip_enter_ip_here'] = 'IP এখানে প্রবেশ করান।';
$CIDRAM['lang']['tip_file_manager'] = 'হ্যালো, {username}।<br />ফাইল ম্যানেজার ব্যবহার করে আপনি ফাইলগুলি মুছে, সম্পাদনা, আপলোড এবং ডাউনলোড করতে পারেন। সতর্কতার সাথে ব্যবহার করুন (আপনি এই সঙ্গে আপনার ইনস্টলেশন বিরতি হতে পারে)।';
$CIDRAM['lang']['tip_home'] = 'হ্যালো, {username}।<br />এটা CIDRAM ফ্রন্ট-এন্ড হোম পেজ হয়। চালিয়ে যেতে বাম দিকে নেভিগেশন মেনু থেকে একটি লিঙ্ক নির্বাচন করুন।';
$CIDRAM['lang']['tip_ip_aggregator'] = 'হ্যালো, {username}।<br />IP এগ্রিগেটর আপনাকে ক্ষুদ্রতম সম্ভাব্য পদ্ধতিতে IPগুলি এবং CIDRগুলি প্রকাশ করতে দেয়। একত্রিত হওয়ার জন্য তথ্য সন্নিবেশ করান এবং "OK" টিপুন।';
$CIDRAM['lang']['tip_ip_test'] = 'হ্যালো, {username}।<br />IP পরীক্ষা পৃষ্ঠা আপনাকে বর্তমানে ইনস্টল করা স্বাক্ষর দ্বারা আইপি অ্যাড্রেসগুলিকে ব্লক করে কিনা তা পরীক্ষা করতে দেয়।';
$CIDRAM['lang']['tip_ip_tracking'] = 'হ্যালো, {username}।<br />IP পর্যবেক্ষণ পৃষ্ঠা আপনাকে IP ঠিকানাগুলির নিরীক্ষণ করা হচ্ছে তা পরীক্ষা করার অনুমতি দেয়, যাদেরকে নিষিদ্ধ করা হয়েছে তা দেখতে, এবং যদি আপনি চান, নিষিদ্ধ অপসারণ।';
$CIDRAM['lang']['tip_login'] = 'ডিফল্ট ব্যবহারকারীর নাম: <span class="txtRd">admin</span> – ডিফল্ট পাসওয়ার্ড: <span class="txtRd">password</span>';
$CIDRAM['lang']['tip_logs'] = 'হ্যালো, {username}।<br />যে লগ ফাইল এর বিষয়বস্তু দেখতে নীচের তালিকা থেকে একটি লগ ফাইল নির্বাচন করুন।';
$CIDRAM['lang']['tip_see_the_documentation'] = 'বিভিন্ন কনফিগারেশন নির্দেশাবলী এবং তাদের উদ্দেশ্য সম্পর্কে আরও তথ্যের জন্য <a href="https://github.com/CIDRAM/CIDRAM/blob/master/_docs/readme.en.md#SECTION6">ডকুমেন্টেশনটি</a> দেখুন।';
$CIDRAM['lang']['tip_statistics'] = 'হ্যালো, {username}।<br />এই পৃষ্ঠাটি আপনার CIDRAM ইনস্টলেশনের সম্পর্কিত কিছু মৌলিক ব্যবহারের পরিসংখ্যান দেখায়।';
$CIDRAM['lang']['tip_statistics_disabled'] = 'নোট: পরিসংখ্যান ট্র্যাকিং বর্তমানে অক্ষম আছে, তবে কনফিগারেশন পৃষ্ঠার মাধ্যমে সক্ষম করা যেতে পারে।';
$CIDRAM['lang']['tip_updates'] = 'হ্যালো, {username}।<br />আপডেট পৃষ্ঠাটি আপনাকে CIDRAM কম্পোনেন্ট ইনস্টল, আনইনস্টল এবং আপডেট করতে দেয় (কোর প্যাকেজ, স্বাক্ষর, L10N ফাইল, ইত্যাদি)।';
$CIDRAM['lang']['title_accounts'] = 'CIDRAM – অ্যাকাউন্ট';
$CIDRAM['lang']['title_cidr_calc'] = 'CIDRAM – CIDR ক্যালকুলেটর';
$CIDRAM['lang']['title_config'] = 'CIDRAM – কনফিগারেশন';
$CIDRAM['lang']['title_file_manager'] = 'CIDRAM – ফাইল ম্যানেজার';
$CIDRAM['lang']['title_home'] = 'CIDRAM – হোম পেজ';
$CIDRAM['lang']['title_ip_aggregator'] = 'CIDRAM – IP এগ্রিগেটর';
$CIDRAM['lang']['title_ip_test'] = 'CIDRAM – IP টেস্ট';
$CIDRAM['lang']['title_ip_tracking'] = 'CIDRAM – IP পর্যবেক্ষণ';
$CIDRAM['lang']['title_login'] = 'CIDRAM – লগ ইন';
$CIDRAM['lang']['title_logs'] = 'CIDRAM – লগ ফাইল';
$CIDRAM['lang']['title_statistics'] = 'CIDRAM – পরিসংখ্যান';
$CIDRAM['lang']['title_updates'] = 'CIDRAM – আপডেট';
$CIDRAM['lang']['warning'] = 'সতর্কবাণী:';
$CIDRAM['lang']['warning_php_1'] = 'আপনার PHP সংস্করণ সক্রিয়ভাবে আর সমর্থিত হয় না! আপডেট করা হচ্ছে সুপারিশ!';
$CIDRAM['lang']['warning_php_2'] = 'আপনার PHP সংস্করণ গুরুতরভাবে ঝুঁকিপূর্ণ! আপডেট করা অত্যন্ত দৃঢ়ভাবে সুপারিশ করা হয়!';
$CIDRAM['lang']['warning_signatures_1'] = 'কোন স্বাক্ষর ফাইল সক্রিয় নেই!';

$CIDRAM['lang']['info_some_useful_links'] = 'কিছু দরকারী লিঙ্ক:<ul>
            <li><a href="https://github.com/CIDRAM/CIDRAM/issues">CIDRAM সমর্থন @ GitHub</a> – CIDRAM সমর্থন পৃষ্ঠা (সহায়তা, সহায়তা, ইত্যাদির জন্য)।</li>
            <li><a href="http://www.spambotsecurity.com/forum/viewforum.php?f=61">CIDRAM @ Spambot Security</a> – CIDRAM জন্য আলোচনা ফোরাম (সহায়তা, সহায়তা, ইত্যাদির জন্য)।</li>
            <li><a href="https://wordpress.org/plugins/cidram/">CIDRAM @ WordPress.org</a> – CIDRAM জন্য WordPress প্লাগইন।</li>
            <li><a href="https://sourceforge.net/projects/cidram/">CIDRAM @ SourceForge</a> – CIDRAM জন্য বিকল্প ডাউনলোড আয়না।</li>
            <li><a href="https://websectools.com/">WebSecTools.com</a> – ওয়েবসাইটগুলি সুরক্ষিত করার জন্য সহজ ওয়েবমাস্টার সরঞ্জামগুলির একটি সংগ্রহ।</li>
            <li><a href="https://macmathan.info/blocklists">MacMathan.info রেঞ্জ ব্লক</a> – ঐচ্ছিক রেঞ্জ ব্লক যা আপনার ওয়েবসাইট থেকে অযাচিত দেশগুলি দূরে রাখার জন্য CIDRAM এ যোগ করা যেতে পারে।</li>
            <li><a href="https://www.facebook.com/groups/2204685680/">Global PHP Group @ Facebook</a> – PHP লার্নিং সম্পদ এবং আলোচনা।</li>
            <li><a href="https://php.earth/">PHP.earth</a> – PHP লার্নিং সম্পদ এবং আলোচনা।</li>
            <li><a href="http://bgp.he.net/">Hurricane Electric BGP টুলকিট</a> – ASNগুলি থেকে CIDRগুলি পান, ASN সম্পর্কগুলি নির্ধারণ করুন, নেটওয়ার্ক নাম উপর ভিত্তি করে ASNগুলি আবিষ্কার, ইত্যাদি।</li>
            <li><a href="https://www.stopforumspam.com/forum/">ফোরাম @ Stop Forum Spam</a> – ফোরাম স্প্যাম বন্ধ করার জন্য দরকারী আলোচনা ফোরাম।</li>
            <li><a href="https://radar.qrator.net/">Qrator Radar</a> – দরকারী ASN সংযোগ পরীক্ষা সরঞ্জাম।</li>
            <li><a href="http://www.ipdeny.com/ipblocks/">IPdeny IP দেশ ব্লক</a> – দেশব্যাপী স্বাক্ষর তৈরির জন্য একটি চমত্কার এবং সঠিক পরিষেবা।</li>
            <li><a href="https://www.google.com/transparencyreport/safebrowsing/malware/">Google Malware ড্যাশবোর্ড</a> – ASNগুলি জন্য ম্যালওয়্যার সংক্রমণের হার রিপোর্ট।</li>
            <li><a href="https://www.spamhaus.org/statistics/botnet-asn/">The Spamhaus Project</a> – ASNগুলি এর জন্য ম্যালওয়ার সংক্রমণের হার সংক্রান্ত প্রতিবেদনগুলি প্রদর্শন করে।</li>
            <li><a href="https://www.abuseat.org/public/asn.html">Abuseat.org কম্পোজিট ব্লকিং তালিকা</a> – ASNগুলি এর জন্য ম্যালওয়ার সংক্রমণের হার সংক্রান্ত প্রতিবেদনগুলি প্রদর্শন করে।</li>
            <li><a href="https://abuseipdb.com/">AbuseIPDB</a> – একটি পরিচিত অপমানজনক IPগুলি ডাটাবেস বজায় রাখে; IPগুলি চেক এবং রিপোর্ট করার জন্য একটি API উপলব্ধ করে।</li>
            <li><a href="https://www.megarbl.net/index.php">MegaRBL.net</a> – পরিচিত স্প্যামারদের তালিকা বজায় রাখে; IP/ASN স্প্যাম ক্রিয়াকলাপ পরীক্ষা করার জন্য দরকারী।</li>
            <li><a href="https://maikuolan.github.io/Vulnerability-Charts/">Vulnerability Charts (দুর্বলতা চার্ট)</a> – বিভিন্ন প্যাকেজের নিরাপদ/অনিরাপদ সংস্করণের তালিকা (PHP, HHVM, ইত্যাদি).</li>
            <li><a href="https://maikuolan.github.io/Compatibility-Charts/">Compatibility Charts (সামঞ্জস্যের চার্ট)</a> – বিভিন্ন প্যাকেজের জন্য সামঞ্জস্য তথ্য তালিকা (CIDRAM, phpMussel, ইত্যাদি).</li>
        </ul>';
