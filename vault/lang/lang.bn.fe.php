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
 * This file: Bangla language data for the front-end (last modified: 2018.08.13).
 */

/** Prevents execution from outside of CIDRAM. */
if (!defined('CIDRAM')) {
    die('[CIDRAM] This should not be accessed directly.');
}

foreach (['IPv4', 'IPv6'] as $CIDRAM['IPvX']) {
    $CIDRAM['Pre'] = 'ডিফল্ট ' . $CIDRAM['IPvX'] . ' স্বাক্ষর সাধারণত প্রধান প্যাকেজ সহ অন্তর্ভুক্ত। ';
    $CIDRAM['lang']['Extended Description: ' . $CIDRAM['IPvX']] = $CIDRAM['Pre'] . 'অবাঞ্ছিত ক্লাউড পরিষেবা এবং অ মানবিক বিন্দুগুলি ব্লক করে।';
    $CIDRAM['lang']['Extended Description: ' . $CIDRAM['IPvX'] . '-Bogons'] = $CIDRAM['Pre'] . 'ব্লক bogon/মঙ্গল CIDRগুলি।';
    $CIDRAM['lang']['Extended Description: ' . $CIDRAM['IPvX'] . '-ISPs'] = $CIDRAM['Pre'] . 'বিপজ্জনক এবং স্প্যামবাই ISPগুলি ব্লক করে।';
    $CIDRAM['lang']['Extended Description: ' . $CIDRAM['IPvX'] . '-Other'] = $CIDRAM['Pre'] . 'প্রক্সি, VPNগুলি এবং অন্যান্য অপ্রয়োজনীয় অযাচিত পরিষেবাগুলির জন্য CIDRগুলি ব্লক করে।';
    $CIDRAM['Pre'] = $CIDRAM['IPvX'] . ' স্বাক্ষর ফাইল (%s)।';
    $CIDRAM['lang']['Name: ' . $CIDRAM['IPvX']] = sprintf($CIDRAM['Pre'], 'অবাঞ্ছিত মেঘ সেবা এবং অ মানব শেষ বিন্দু');
    $CIDRAM['lang']['Name: ' . $CIDRAM['IPvX'] . '-Bogons'] = sprintf($CIDRAM['Pre'], 'bogon/মঙ্গল CIDRগুলি');
    $CIDRAM['lang']['Name: ' . $CIDRAM['IPvX'] . '-ISPs'] = sprintf($CIDRAM['Pre'], 'বিপজ্জনক এবং স্প্যামযুক্ত ISPগুলি');
    $CIDRAM['lang']['Name: ' . $CIDRAM['IPvX'] . '-Other'] = sprintf($CIDRAM['Pre'], 'প্রক্সি, VPNগুলি এবং অন্যান্য বিবিধ অবাঞ্ছিত সেবাগুলির জন্য CIDRগুলি');
}
unset($CIDRAM['Pre'], $CIDRAM['IPvX']);

$CIDRAM['lang']['Extended Description: Bypasses'] = 'ডিফল্ট স্বাক্ষর বাইপাস ফাইল সাধারণত প্রধান প্যাকেজ সহ অন্তর্ভুক্ত।';
$CIDRAM['lang']['Extended Description: CIDRAM'] = 'প্রধান প্যাকেজ (স্বাক্ষর, ডকুমেন্টেশন এবং কনফিগারেশন সহ না)।';
$CIDRAM['lang']['Extended Description: Chart.js'] = 'পাওয়ার চার্ট তৈরি করার জন্য সামনে-শেষ সক্ষম করে।<br /><a href="https://github.com/chartjs/Chart.js">Chart.js</a> <a href="https://opensource.org/licenses/MIT">MIT license</a> মাধ্যমে উপলব্ধ।';
$CIDRAM['lang']['Extended Description: PHPMailer'] = 'ইমেল কার্যকারিতা পাঠানোর জন্য প্রয়োজনীয়।<br /><a href="https://github.com/PHPMailer/PHPMailer">PHPMailer</a> <a href="https://github.com/PHPMailer/PHPMailer/blob/master/LICENSE">LGPLv2.1</a> লাইসেন্সের মাধ্যমে উপলব্ধ।';
$CIDRAM['lang']['Extended Description: module_badhosts.php'] = 'ব্লক হোস্ট ঘন ঘন স্প্যামার, হ্যাকার, এবং অন্যান্য নৃশংস সত্ত্বা দ্বারা ব্যবহৃত।';
$CIDRAM['lang']['Extended Description: module_badhosts_isps.php'] = 'ISPগুলি সাথে যুক্ত ব্লক হোস্ট ঘন ঘন স্প্যামার, হ্যাকার, এবং অন্যান্য নৃশংস সত্ত্বা দ্বারা ব্যবহৃত।';
$CIDRAM['lang']['Extended Description: module_badtlds.php'] = 'TLDগুলি সাথে যুক্ত ব্লক হোস্ট ঘন ঘন স্প্যামার, হ্যাকার, এবং অন্যান্য নৃশংস সত্ত্বা দ্বারা ব্যবহৃত।';
$CIDRAM['lang']['Extended Description: module_botua.php'] = 'অবাঞ্ছিত বট এবং নৃশংস কার্যকলাপের সাথে যুক্ত ব্যবহারকারী এজেন্টগুলিকে ব্লক করে।';
$CIDRAM['lang']['Extended Description: module_cookies.php'] = 'বিপজ্জনক কুকিজগুলির বিরুদ্ধে কিছু সীমিত সুরক্ষা প্রদান করে।';
$CIDRAM['lang']['Extended Description: module_extras.php'] = 'অনুরোধগুলি সাধারণত ব্যবহৃত বিভিন্ন আক্রমণ ভেক্টরগুলির বিরুদ্ধে কিছু সীমিত সুরক্ষা প্রদান করে।';
$CIDRAM['lang']['Extended Description: module_sfs.php'] = 'SFS দ্বারা তালিকাভুক্ত IPগুলির বিরুদ্ধে রেজিস্ট্রেশন এবং লগইন পেজ রক্ষা করে।';
$CIDRAM['lang']['Name: Bypasses'] = 'ডিফল্ট স্বাক্ষর বাইপাস।';
$CIDRAM['lang']['Name: compat_bunnycdn.php'] = 'BunnyCDN সামঞ্জস্য মডিউল';
$CIDRAM['lang']['Name: module_badhosts.php'] = 'খারাপ হোস্ট অবরোধকারী মডিউল';
$CIDRAM['lang']['Name: module_badhosts_isps.php'] = 'খারাপ হোস্ট অবরোধকারী মডিউল (ISPগুলি)';
$CIDRAM['lang']['Name: module_badtlds.php'] = 'খারাপ TLDগুলি ব্লকার মডিউল';
$CIDRAM['lang']['Name: module_baidublocker.php'] = 'বাইডু ব্লকার মডিউল';
$CIDRAM['lang']['Name: module_botua.php'] = 'ঐচ্ছিক ব্যবহারকারী এজেন্ট মডিউল';
$CIDRAM['lang']['Name: module_cookies.php'] = 'ঐচ্ছিক কুকি স্ক্যানার মডিউল';
$CIDRAM['lang']['Name: module_extras.php'] = 'ঐচ্ছিক নিরাপত্তা অতিরিক্ত মডিউল';
$CIDRAM['lang']['Name: module_sfs.php'] = 'Stop Forum Spam মডিউল';
$CIDRAM['lang']['Name: module_ua.php'] = 'খালি UA ব্লককারী মডিউল';
$CIDRAM['lang']['Name: module_yandexblocker.php'] = 'ইয়্যান্ডেক্স ব্লকার মডিউল';
$CIDRAM['lang']['bNav_home_logout'] = '<a href="?">হোম পেজ</a> | <a href="?cidram-page=logout">প্রস্থান</a>';
$CIDRAM['lang']['bNav_logout'] = '<a href="?cidram-page=logout">প্রস্থান</a>';
$CIDRAM['lang']['config_PHPMailer'] = 'ইমেল পাঠানোর কার্যকারিতা জন্য এই কনফিগারেশন নির্দেশাবলী প্রয়োজন হতে পারে। আরো তথ্য এবং প্রস্তাবিত মান জন্য ডকুমেন্টেশন পড়ুন।';
$CIDRAM['lang']['config_experimental'] = 'অস্থায়ী/পরীক্ষামূলক!';
$CIDRAM['lang']['config_general_FrontEndLog'] = 'ফ্রন্ট-এন্ড লগইন প্রচেষ্টা রেকর্ড করার জন্য ফাইল। ফাইলের নাম উল্লেখ করুন, অথবা নিষ্ক্রিয় করতে ফাঁকা রাখুন।';
$CIDRAM['lang']['config_general_allow_gethostbyaddr_lookup'] = 'যখন UDP অনুপলব্ধ হয় তখন gethostbyaddr সন্ধানগুলি মঞ্জুর করুন? True = হাঁ [ডিফল্ট]; False = না।';
$CIDRAM['lang']['config_general_ban_override'] = 'যখন "infraction_limit" অতিক্রম করা হয় তখন "forbid_on_block" ওভাররাইড করে? ওভাররাইড করার সময়: ব্লক করা অনুরোধগুলি একটি ফাঁকা পৃষ্ঠাটি ফেরত পাঠায় (টেমপ্লেট ফাইল ব্যবহার করা হয় না)। 200 = ওভাররাইড করবেন না [ডিফল্ট]। অন্যান্য মানগুলি "forbid_on_block" এর জন্য উপলব্ধ মানগুলির মতই।';
$CIDRAM['lang']['config_general_default_algo'] = 'সব ভবিষ্যত পাসওয়ার্ড এবং সেশন জন্য ব্যবহার করে অ্যালগরিদম সংজ্ঞায়িত করে। বিকল্প: PASSWORD_DEFAULT (ডিফল্ট), PASSWORD_BCRYPT, PASSWORD_ARGON2I (PHP &gt;= 7.2.0 প্রয়োজন)।';
$CIDRAM['lang']['config_general_default_dns'] = 'হোস্টনামগুলি দেখার জন্য ডিএনএস সার্ভারগুলির একটি কমা দ্বারা পৃথক তালিকা। ডিফল্ট = "8.8.8.8,8.8.4.4" (Google DNS)। সতর্কতা: আপনি কি করছেন তা না জানলে তা পরিবর্তন করবেন না!';
$CIDRAM['lang']['config_general_disable_cli'] = 'CLI মোড অক্ষম? CLI মোড ডিফল্টরূপে সক্ষম করা হয়, কিন্তু কখনও কখনও নির্দিষ্ট পরীক্ষার সরঞ্জামগুলিতে (যেমন PHPUnit হিসাবে, উদাহরণস্বরূপ) এবং অন্যান্য CLI-ভিত্তিক অ্যাপ্লিকেশন হস্তক্ষেপ করতে পারে। যদি আপনি CLI মোড অক্ষম করতে না চান, তাহলে আপনাকে এই নির্দেশকে উপেক্ষা করা উচিত। False = CLI মোড সক্ষম করুন [ডিফল্ট]; True = CLI মোড অক্ষম করুন।';
$CIDRAM['lang']['config_general_disable_frontend'] = 'ফ্রন্ট-এন্ড অ্যাক্সেস অক্ষম করুন? ফ্রন্ট-এন্ড অ্যাক্সেস CIDRAM আরও পরিচালনযোগ্য করতে পারে, কিন্তু এটি একটি সম্ভাব্য নিরাপত্তার ঝুঁকিও হতে পারে। এটি সম্ভব হলে ব্যাক-এন্ডের মাধ্যমে CIDRAM পরিচালনার জন্য সুপারিশ করা হয়, তবে সুবিধার জন্য ফ্রন্ট-এন্ড অ্যাক্সেসও প্রদান করা হয়। যদি এটির প্রয়োজন হয় না তবে এটি অক্ষম রাখুন। False = ফ্রন্ট-এন্ড অ্যাক্সেস সক্ষম করুন; True = ফ্রন্ট-এন্ড অ্যাক্সেস অক্ষম করুন [ডিফল্ট]।';
$CIDRAM['lang']['config_general_disable_webfonts'] = 'ওয়েবফটগুলি অক্ষম করবেন? True = হাঁ [ডিফল্ট]; False = না।';
$CIDRAM['lang']['config_general_emailaddr'] = 'তুমি যদি চাও, আপনি ব্যবহারকারীদের জন্য একটি ইমেল ঠিকানা সরবরাহ করতে পারেন, তারা অবরুদ্ধ যখন প্রদর্শিত, ত্রুটির কারণে ব্লক হওয়ার সময় তাদের সহায়তার জন্য যোগাযোগের একটি বিন্দু হিসাবে ব্যবহার করার জন্য। সতর্কতা: আপনি যে ইমেল ঠিকানাটি সরবরাহ করেন তা অবশ্যই স্প্যামবট এবং স্ক্রাপারের মাধ্যমে অবশ্যই ব্যবহার করা হবে, এবং তাই, যদি আপনি এখানে একটি ইমেইল ঠিকানা সরবরাহ করার সিদ্ধান্ত নেন, এটি দৃঢ়ভাবে সুপারিশ করা হয় যে আপনি নিশ্চিত করেন যে আপনি যে ইমেল ঠিকানাটি সরবরাহ করেছেন তা একটি ডিসপোজেবল ঠিকানা অথবা এমন কোনও ঠিকানা যা আপনি স্প্যাম করার ব্যাপারে উদাসীন নন (অন্য কথায়, আপনি সম্ভবত আপনার প্রাথমিক ব্যক্তিগত বা প্রাথমিক ব্যবসা ইমেল ঠিকানা ব্যবহার করতে চান না)।';
$CIDRAM['lang']['config_general_emailaddr_display_style'] = 'ব্যবহারকারীদের কাছে ইমেল ঠিকানা কীভাবে উপস্থাপন করা উচিত?';
$CIDRAM['lang']['config_general_forbid_on_block'] = 'অনুরোধগুলি ব্লক করার সময় HTTP স্থিতি বার্তা CIDRAM পাঠাতে হবে? (আরও তথ্যের জন্য ডকুমেন্টেশন দেখুন).';
$CIDRAM['lang']['config_general_force_hostname_lookup'] = 'হোস্টনাম সন্ধান করার জন্য ফোর্স? True = হাঁ; False = না [ডিফল্ট]। হোস্টনাম সন্ধানগুলি সাধারণভাবে একটি "প্রয়োজনীয়" ভিত্তিতে সঞ্চালিত হয়, কিন্তু সব অনুরোধের জন্য বাধ্য করা যেতে পারে। লগ ফাইলে আরো বিস্তারিত তথ্য প্রদানের একটি উপায় হিসাবে এটি করা দরকারী হতে পারে, কিন্তু এটি কর্মক্ষমতা উপর সামান্য নেতিবাচক প্রভাব থাকতে পারে।';
$CIDRAM['lang']['config_general_hide_version'] = 'লগ এবং পৃষ্ঠা আউটপুট থেকে সংস্করণ তথ্য লুকান? True = হাঁ; False = না [ডিফল্ট]।';
$CIDRAM['lang']['config_general_ipaddr'] = 'ইনবাউন্ড অনুরোধের আইপি ঠিকানা কোথায় পাওয়া যায়? (যেমন Cloudflare এবং ইত্যাদি সেবা জন্য দরকারী)। ডিফল্ট = REMOTE_ADDR। সতর্কতা: আপনি কি করছেন তা না জানলে তা পরিবর্তন করবেন না!';
$CIDRAM['lang']['config_general_lang'] = 'CIDRAM এর জন্য ডিফল্ট ভাষা নির্দিষ্ট করুন।';
$CIDRAM['lang']['config_general_log_banned_ips'] = 'লগ ফাইলগুলিতে নিষিদ্ধ IP ঠিকানাগুলি থেকে অবরুদ্ধ অনুরোধ অন্তর্ভুক্ত করুন? True = হাঁ [ডিফল্ট]; False = না।';
$CIDRAM['lang']['config_general_log_rotation_action'] = 'লগ আবর্তন যে কোন এক সময়ে উপস্থিত থাকা লগ ফাইলর সংখ্যা সীমাবদ্ধ করে। যখন নতুন লগ ফাইলগুলি তৈরি করা হয়, লগ ফাইলের মোট সংখ্যা নির্দিষ্ট সীমা অতিক্রম করে, তখন নির্দিষ্ট অ্যাকশনটি সঞ্চালিত হবে। আপনি এখানে পছন্দসই কর্ম উল্লেখ করতে পারেন। Delete = সীমা অতিক্রম না হওয়া পর্যন্ত, প্রাচীনতম লগ ফাইলগুলি মুছুন। Archive = প্রথমত, লগ ফাইলগুলিকে আর্কাইভ করুন, এবং তারপর, সীমা অতিক্রম না হওয়া পর্যন্ত, প্রাচীনতম লগ ফাইলগুলি মুছুন।';
$CIDRAM['lang']['config_general_log_rotation_limit'] = 'লগ আবর্তন যে কোন এক সময়ে উপস্থিত থাকা লগ ফাইলর সংখ্যা সীমাবদ্ধ করে। যখন নতুন লগ ফাইলগুলি তৈরি করা হয়, লগ ফাইলের মোট সংখ্যা নির্দিষ্ট সীমা অতিক্রম করে, তখন নির্দিষ্ট অ্যাকশনটি সঞ্চালিত হবে। আপনি এখানে পছন্দসই সীমা নির্ধারণ করতে পারেন। 0 এর মান লগ আবর্তন নিষ্ক্রিয় করবে।';
$CIDRAM['lang']['config_general_logfile'] = 'সব অবরুদ্ধ অ্যাক্সেস প্রচেষ্টা রেকর্ড করার জন্য মানব পাঠযোগ্য ফাইল। ফাইলের নাম উল্লেখ করুন, অথবা নিষ্ক্রিয় করতে ফাঁকা রাখুন।';
$CIDRAM['lang']['config_general_logfileApache'] = 'সব অবরুদ্ধ অ্যাক্সেস প্রচেষ্টা রেকর্ড করার জন্য Apache-শৈলী ফাইল। ফাইলের নাম উল্লেখ করুন, অথবা নিষ্ক্রিয় করতে ফাঁকা রাখুন।';
$CIDRAM['lang']['config_general_logfileSerialized'] = 'সব অবরুদ্ধ অ্যাক্সেস প্রচেষ্টা রেকর্ড করার জন্য serialized ফাইল। ফাইলের নাম উল্লেখ করুন, অথবা নিষ্ক্রিয় করতে ফাঁকা রাখুন।';
$CIDRAM['lang']['config_general_maintenance_mode'] = 'রক্ষণাবেক্ষণ মোড সক্ষম করবেন? True = হাঁ; False = না [ডিফল্ট]। ফ্রন্ট-এন্ড ছাড়া অন্য সব কিছু অক্ষম করে। আপনার CMS, ফ্রেমওয়ার্ক, ইত্যাদি আপডেট করার সময় কখনও কখনও দরকারী।';
$CIDRAM['lang']['config_general_max_login_attempts'] = 'লগইন প্রচেষ্টা সর্বাধিক সংখ্যা। ডিফল্ট = 5।';
$CIDRAM['lang']['config_general_numbers'] = 'কিভাবে সংখ্যা প্রদর্শন করা উচিত? উদাহরণটি নির্বাচন করুন যা আপনার কাছে সবচেয়ে সঠিক দেখায়।';
$CIDRAM['lang']['config_general_protect_frontend'] = 'CIDRAM কর্তৃক প্রদত্ত সুরক্ষাগুলি ফ্রন্ট-এ-তে প্রয়োগ করা হবে কিনা তা উল্লেখ করে। True = হাঁ [ডিফল্ট]; False = না।';
$CIDRAM['lang']['config_general_search_engine_verification'] = 'অনুসন্ধান ইঞ্জিন থেকে অনুরোধ যাচাই করার চেষ্টা? সার্চ ইঞ্জিনগুলি যাচাই করা নিশ্চিত করে যে তারা ইনফেকশন সীমা অতিক্রম করার ফলে তাদের নিষিদ্ধ করা হবে না (সার্চ ইঞ্জিনগুলি নিষিদ্ধ করা সাধারণত আপনার সার্চ ইঞ্জিন র্যাংকিং, SEO, ইত্যাদি একটি নেতিবাচক ভাবে প্রভাবিত করবে)। যাচাই করা হলে, অনুসন্ধান ইঞ্জিন স্বাভাবিক হিসাবে ব্লক করা যেতে পারে, কিন্তু নিষিদ্ধ করা হবে না। যাচাই না করা হলে, তাদের জন্য নিষ্ক্রিয়তা সীমা অতিক্রম করা হলে এটি তাদের জন্য নিষিদ্ধ করা সম্ভব। উপরন্তু, অনুসন্ধান ইঞ্জিন যাচাই জাল সার্চ ইঞ্জিন অনুরোধের বিরুদ্ধে সুরক্ষা প্রদান করে (অনুসন্ধান ইঞ্জিন যাচাইকরণ সক্ষম করা হলে এই জাল অনুরোধগুলিকে অবরুদ্ধ করা হবে)। True = সার্চ ইঞ্জিন যাচাইকরণ সক্ষম করুন [ডিফল্ট]; False = অনুসন্ধান ইঞ্জিন যাচাইকরণ অক্ষম করুন।';
$CIDRAM['lang']['config_general_silent_mode'] = 'অবরুদ্ধ অ্যাক্সেসের প্রচেষ্টাগুলি পুনঃনির্দেশ করুন ("প্রবেশাধিকার অস্বীকার" পৃষ্ঠাটি প্রদর্শন করার পরিবর্তে)? যদি তাই, ব্লক অ্যাক্সেসের প্রচেষ্টাগুলি পুনর্নির্দেশ করতে অবস্থানটি নির্দিষ্ট করুন। যদি না, এই পরিবর্তনশীল খালি রাখুন।';
$CIDRAM['lang']['config_general_social_media_verification'] = 'সামাজিক মিডিয়া অনুরোধ যাচাই করার চেষ্টা? সামাজিক মিডিয়া যাচাইকরণটি জাল সামাজিক মিডিয়ার অনুরোধের বিরুদ্ধে সুরক্ষা দেয় (যেমন অনুরোধগুলি অবরুদ্ধ করা হবে)। True = সামাজিক মিডিয়া যাচাইকরণ সক্ষম করুন [ডিফল্ট]; False = সামাজিক মিডিয়া যাচাইকরণ অক্ষম করুন।';
$CIDRAM['lang']['config_general_statistics'] = 'CIDRAM ব্যবহারের পরিসংখ্যান ট্র্যাক? True = হাঁ; False = না [ডিফল্ট]।';
$CIDRAM['lang']['config_general_timeFormat'] = 'CIDRAM দ্বারা ব্যবহৃত তারিখ/সময় উল্লেখ ফরম্যাট। অনুরোধ উপর অতিরিক্ত বিকল্প যোগ করা যেতে পারে।';
$CIDRAM['lang']['config_general_timeOffset'] = 'টাইমজোন মিনিটে অফসেট।';
$CIDRAM['lang']['config_general_timezone'] = 'আপনার টাইমজোন।';
$CIDRAM['lang']['config_general_truncate'] = 'একটি নির্দিষ্ট আকারে পৌঁছানোর সময় লগ ফাইলগুলি কেটে ফেলা হবে? লগ ফাইলগুলির জন্য B/KB/MB/GB/TB এ মান সর্বাধিক অনুমোদিত আকার (এই আকারের বাইরে, লগ ফাইলগুলি ছোট করা হবে)। ডিফল্ট মান 0KB ট্রুনাকশন নিষ্ক্রিয় করবে (লগ ফাইলগুলি অনির্দিষ্টকালের জন্য বাড়তে পারে)। দয়া করে নোট করুন: এটি স্বতন্ত্র লগ ফাইলগুলিতে প্রযোজ্য! লগ ফাইলের আকার সমষ্টিগতভাবে বিবেচনা করা হয় না।';
$CIDRAM['lang']['config_legal_omit_hostname'] = 'লগগুলি থেকে হোস্টনামগুলি বাদ দিন? True = হাঁ; False = না [ডিফল্ট]।';
$CIDRAM['lang']['config_legal_omit_ip'] = 'লগগুলি থেকে IP ঠিকানাগুলি বাদ দিন? True = হাঁ; False = না [ডিফল্ট]। দ্রষ্টব্য: "pseudonymise_ip_addresses" অপ্রয়োজনীয় হয় যখন "omit_ip" হল "true"।';
$CIDRAM['lang']['config_legal_omit_ua'] = 'লগগুলি থেকে ব্যবহারকারী এজেন্ট বাদ দিন? True = হাঁ; False = না [ডিফল্ট]।';
$CIDRAM['lang']['config_legal_privacy_policy'] = 'কোন প্রযোজ্য পৃষ্ঠাগুলির পাদলেখ প্রদর্শিত একটি প্রাসঙ্গিক গোপনীয়তা নীতির ঠিকানা। একটি URL উল্লেখ করুন, বা অক্ষম করতে ফাঁকা রাখুন।';
$CIDRAM['lang']['config_legal_pseudonymise_ip_addresses'] = 'লগ লেখার সময় IP ঠিকানাগুলি pseudonymize? True = হাঁ; False = না [ডিফল্ট]।';
$CIDRAM['lang']['config_recaptcha_api'] = 'API ব্যবহার করতে? V2 বা Invisible?';
$CIDRAM['lang']['config_recaptcha_expiry'] = 'reCAPTCHA দৃষ্টান্তগুলি মনে রাখার জন্য ঘন্টাগুলির সংখ্যা।';
$CIDRAM['lang']['config_recaptcha_lockip'] = 'reCAPTCHA IP অ্যাড্রেসগুলি লক করুন?';
$CIDRAM['lang']['config_recaptcha_lockuser'] = 'reCAPTCHA ব্যবহারকারীদের লক করুন?';
$CIDRAM['lang']['config_recaptcha_logfile'] = 'সব reCAPTCHA প্রচেষ্টা লগ ইন করুন? যদি তাই, লগ ফাইলে ব্যবহারের জন্য নামটি উল্লেখ করুন। যদি না, এই পরিবর্তনশীল খালি রাখুন।';
$CIDRAM['lang']['config_recaptcha_secret'] = 'এই মান আপনার reCAPTCHA জন্য "secret key" অনুরূপ করা উচিত (reCAPTCHA ড্যাশবোর্ডের মধ্যে পাওয়া যেতে পারে)।';
$CIDRAM['lang']['config_recaptcha_signature_limit'] = 'একটি reCAPTCHA উদাহরণ প্রস্তাব করা হয় যখন সর্বাধিক স্বাক্ষর আরম্ভ করা অনুমোদিত। ডিফল্ট = 1। যদি এই নম্বরটি কোনো নির্দিষ্ট অনুরোধের জন্য অতিক্রম করা হয়, তাহলে একটি reCAPTCHA উদাহরণ দেওয়া হবে না।';
$CIDRAM['lang']['config_recaptcha_sitekey'] = 'এই মান আপনার reCAPTCHA জন্য "site key" অনুরূপ করা উচিত (reCAPTCHA ড্যাশবোর্ডের মধ্যে পাওয়া যেতে পারে)।';
$CIDRAM['lang']['config_recaptcha_usemode'] = 'কিভাবে CIDRAM reCAPTCHA ব্যবহার করা উচিত তা নির্ধারণ করে (ডকুমেন্টেশন দেখুন)।';
$CIDRAM['lang']['config_signatures_block_bogons'] = 'bogon/মঙ্গল CIDRগুলি ব্লক কর? আপনার স্থানীয় নেটওয়ার্কে, localhost, LAN, ইত্যাদি থেকে আপনার ওয়েবসাইট সংযোগ, এই নির্দেশটি false হিসাবে সেট করা উচিত। আপনি যদি এই ধরনের সংযোগগুলি আশা না করেন, এই নির্দেশটি true হিসাবে সেট করা উচিত।';
$CIDRAM['lang']['config_signatures_block_cloud'] = 'ওয়েব হোস্টিং এবং ক্লাউড পরিষেবাগুলির সাথে সম্পর্কিত হিসাবে CIDRগুলি ব্লক কর? আপনি যদি আপনার ওয়েবসাইট থেকে একটি API সেবা পরিচালনা করেন, অথবা যদি আপনি অন্য ওয়েবসাইটে আপনার ওয়েবসাইট সংযোগ করতে আশা, এই false সেট করা উচিত। অন্যভাবে, যদি না, এই true সেট করা উচিত।';
$CIDRAM['lang']['config_signatures_block_generic'] = 'ব্ল্যাকলিস্টের জন্য CIDRগুলি সুপারিশ করেছে ব্লক কর? এটি কোন স্বাক্ষর বিবেচনা করে যা অন্য কোনও অংশ হিসাবে চিহ্নিত করা হয় না।';
$CIDRAM['lang']['config_signatures_block_legal'] = 'আইনি বাধ্যবাধকতা প্রতিক্রিয়া হিসেবে CIDRগুলি ব্লক কর? এই নির্দেশটি সাধারণত কোন প্রভাব না থাকা উচিত, কারণ CIDRAM ডিফল্ট হিসাবে "আইনি বাধ্যবাধকতা" সঙ্গে কোনো CIDR সংযুক্ত না, কিন্তু এটি কোনও স্বনির্ধারিত স্বাক্ষর ফাইল বা মডিউলগুলির সুবিধা জন্য একটি অতিরিক্ত নিয়ন্ত্রণ পরিমাপ হিসাবে বিদ্যমান যা আইনি কারণগুলির জন্য বিদ্যমান হতে পারে।';
$CIDRAM['lang']['config_signatures_block_malware'] = 'ম্যালওয়ারের সাথে সংযুক্ত IPগুলি ব্লক কর? এটি C&C সার্ভার, সংক্রমিত মেশিন, ম্যালওয়্যার বিতরণ ইত্যাদি জড়িত মেশিনগুলি অন্তর্ভুক্ত করে।';
$CIDRAM['lang']['config_signatures_block_proxies'] = 'প্রক্সি পরিষেবাগুলির বা VPNগুলির সাথে সম্পর্কিত হিসাবে চিহ্নিত CIDRগুলি ব্লক কর? আপনি ব্যবহারকারীদের পরিষেবাগুলি এবং VPNগুলি থেকে আপনার ওয়েবসাইট অ্যাক্সেস করতে সক্ষম হবে যদি প্রয়োজন, এই false সেট করা উচিত। অন্যভাবে, যদি না, এই true সেট করা উচিত (যদি true নির্ধারণ করা হয়, তাহলে নিরাপত্তা আরো ভালো হবে)।';
$CIDRAM['lang']['config_signatures_block_spam'] = 'স্প্যামাররা সাথে যুক্ত CIDRগুলি ব্লক কর? যখন সম্ভব, এবং যদি কোনো সমস্যা না ঘটে, এই true সেট করা উচিত।';
$CIDRAM['lang']['config_signatures_default_tracktime'] = 'মডিউল দ্বারা নিষিদ্ধ IP ঠিকানাগুলি কতগুলি সেকেন্ডের ট্র্যাক করা উচিত। ডিফল্ট = 604800 (1 সপ্তাহ)।';
$CIDRAM['lang']['config_signatures_infraction_limit'] = 'IP ট্র্যাকিং দ্বারা এটি নিষিদ্ধ হওয়ার আগে একটি আইপি দ্বারা অনুমোদিত লঙ্ঘনটিকে সর্বাধিক সংখ্যা। ডিফল্ট = 10।';
$CIDRAM['lang']['config_signatures_ipv4'] = 'IPv4 স্বাক্ষর ফাইলগুলির একটি তালিকা যা CIDRAM প্রক্রিয়া করার চেষ্টা করা উচিত, কমা দ্বারা বিভক্ত।';
$CIDRAM['lang']['config_signatures_ipv6'] = 'IPv6 স্বাক্ষর ফাইলগুলির একটি তালিকা যা CIDRAM প্রক্রিয়া করার চেষ্টা করা উচিত, কমা দ্বারা বিভক্ত।';
$CIDRAM['lang']['config_signatures_modules'] = 'IPv4/IPv6 স্বাক্ষর পরীক্ষা করার পরে লোড করার জন্য মডিউল ফাইলগুলির তালিকা, কমা দ্বারা বিভক্ত।';
$CIDRAM['lang']['config_signatures_track_mode'] = 'যখন লঙ্ঘন গণনা করা উচিত? False = যখন IP মডিউল দ্বারা ব্লক করা হয়। True = যখন IP কোন কারণের জন্য ব্লক করা হয়।';
$CIDRAM['lang']['config_template_data_Magnification'] = 'ফন্ট বৃহত্তরীকরণ। ডিফল্ট = 1।';
$CIDRAM['lang']['config_template_data_css_url'] = 'কাস্টম থিমগুলির জন্য CSS ফাইল URL।';
$CIDRAM['lang']['config_template_data_theme'] = 'CIDRAM এর জন্য ডিফল্ট থিম ব্যবহার করুন।';
$CIDRAM['lang']['confirm_action'] = 'আপনি কি "%s" চান?';
$CIDRAM['lang']['field_2fa'] = '2FA কোড';
$CIDRAM['lang']['field_activate'] = 'সক্রিয় করা';
$CIDRAM['lang']['field_banned'] = 'নিষিদ্ধ';
$CIDRAM['lang']['field_blocked'] = 'ব্লক করা আছে';
$CIDRAM['lang']['field_clear'] = 'পরিষ্কার করুন';
$CIDRAM['lang']['field_clear_all'] = 'সব পরিষ্কার করুন';
$CIDRAM['lang']['field_clickable_link'] = 'ক্লিকযোগ্য লিঙ্ক';
$CIDRAM['lang']['field_component'] = 'কম্পোনেন্ট';
$CIDRAM['lang']['field_confirm'] = 'নিশ্চিত করা';
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
$CIDRAM['lang']['field_infractions'] = 'লঙ্ঘনটিকে';
$CIDRAM['lang']['field_install'] = 'ইনস্টল করুন';
$CIDRAM['lang']['field_ip_address'] = 'IP ঠিকানা';
$CIDRAM['lang']['field_latest_version'] = 'সবচেয়ে সাম্প্রতিক সংস্করণ';
$CIDRAM['lang']['field_log_in'] = 'লগ ইন করুন';
$CIDRAM['lang']['field_new_name'] = 'নতুন নাম:';
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
$CIDRAM['lang']['field_size_GB'] = 'GB';
$CIDRAM['lang']['field_size_KB'] = 'KB';
$CIDRAM['lang']['field_size_MB'] = 'MB';
$CIDRAM['lang']['field_size_TB'] = 'TB';
$CIDRAM['lang']['field_size_bytes'] = 'বাইট';
$CIDRAM['lang']['field_status'] = 'অবস্থা';
$CIDRAM['lang']['field_system_timezone'] = 'সিস্টেম ডিফল্ট টাইমজোন ব্যবহার করুন।';
$CIDRAM['lang']['field_tracking'] = 'পর্যবেক্ষণ';
$CIDRAM['lang']['field_true'] = 'True (সত্য)';
$CIDRAM['lang']['field_uninstall'] = 'আনইনস্টল করুন';
$CIDRAM['lang']['field_update'] = 'আপডেট করুন';
$CIDRAM['lang']['field_update_all'] = 'সব আপডেট করুন';
$CIDRAM['lang']['field_upload_file'] = 'নতুন ফাইল আপলোড করুন';
$CIDRAM['lang']['field_username'] = 'ব্যবহারকারীর নাম';
$CIDRAM['lang']['field_verify'] = 'যাচাই করুন';
$CIDRAM['lang']['field_verify_all'] = 'সব যাচাই করুন';
$CIDRAM['lang']['field_your_version'] = 'আপনার সংস্করণ';
$CIDRAM['lang']['header_login'] = 'চালিয়ে যেতে দয়া করে লগ ইন করুন।';
$CIDRAM['lang']['label_active_config_file'] = 'সক্রিয় কনফিগারেশন ফাইল: ';
$CIDRAM['lang']['label_actual'] = 'আসল';
$CIDRAM['lang']['label_backup_location'] = 'সংগ্রহস্থল ব্যাকআপ অবস্থানগুলি (জরুরী ক্ষেত্রে, বা অন্য সব ব্যর্থ হলে):';
$CIDRAM['lang']['label_banned'] = 'অনুরোধ নিষিদ্ধ';
$CIDRAM['lang']['label_blocked'] = 'অনুরোধ অবরুদ্ধ';
$CIDRAM['lang']['label_branch'] = 'শাখা সর্বশেষ স্থিতিশীল:';
$CIDRAM['lang']['label_check_modules'] = 'এছাড়াও মডিউলগুলির বিরুদ্ধে পরীক্ষা করুন।';
$CIDRAM['lang']['label_cidram'] = 'CIDRAM সংস্করণ ব্যবহৃত:';
$CIDRAM['lang']['label_clientinfo'] = 'ক্লায়েন্ট তথ্য:';
$CIDRAM['lang']['label_displaying'] = '<span class="txtRd">%s</span> এন্ট্রি প্রদর্শন করা হচ্ছে।';
$CIDRAM['lang']['label_displaying_that_cite'] = '"%2$s" উল্লেখ করে <span class="txtRd">%1$s</span> এন্ট্রি প্রদর্শন করা হচ্ছে।';
$CIDRAM['lang']['label_expected'] = 'প্রত্যাশিত';
$CIDRAM['lang']['label_expires'] = 'মেয়াদ শেষ: ';
$CIDRAM['lang']['label_false_positive_risk'] = 'ঝুঁকিতে ইতিবাচক মিথ্যা: ';
$CIDRAM['lang']['label_fmgr_cache_data'] = 'ক্যাশ ডেটা এবং অস্থায়ী ফাইল';
$CIDRAM['lang']['label_fmgr_disk_usage'] = 'CIDRAM ডিস্ক ব্যবহার: ';
$CIDRAM['lang']['label_fmgr_free_space'] = 'বিনামূল্যে ডিস্ক স্থান: ';
$CIDRAM['lang']['label_fmgr_total_disk_usage'] = 'মোট ডিস্ক ব্যবহার: ';
$CIDRAM['lang']['label_fmgr_total_space'] = 'মোট ডিস্ক স্থান: ';
$CIDRAM['lang']['label_fmgr_updates_metadata'] = 'কম্পোনেন্ট আপডেট মেটাডেটা';
$CIDRAM['lang']['label_hide'] = 'লুকান';
$CIDRAM['lang']['label_hide_hash_table'] = 'হ্যাশ টেবিল লুকান';
$CIDRAM['lang']['label_never'] = 'না';
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
$CIDRAM['lang']['label_show_hash_table'] = 'হ্যাশ টেবিল দেখান';
$CIDRAM['lang']['label_signature_type'] = 'স্বাক্ষর প্রকার:';
$CIDRAM['lang']['label_stable'] = 'সর্বশেষ স্থিতিশীল:';
$CIDRAM['lang']['label_sysinfo'] = 'সিস্টেম তথ্য:';
$CIDRAM['lang']['label_tests'] = 'পরীক্ষাগুলি:';
$CIDRAM['lang']['label_total'] = 'মোট';
$CIDRAM['lang']['label_unstable'] = 'সর্বশেষ অস্থিতিশীল:';
$CIDRAM['lang']['label_used_with'] = 'সঙ্গে ব্যবহৃত: ';
$CIDRAM['lang']['label_your_ip'] = 'আপনার IP:';
$CIDRAM['lang']['label_your_ua'] = 'আপনার UA:';
$CIDRAM['lang']['link_accounts'] = 'অ্যাকাউন্ট';
$CIDRAM['lang']['link_cache_data'] = 'ক্যাশ ডেটা';
$CIDRAM['lang']['link_cidr_calc'] = 'CIDR ক্যালকুলেটর';
$CIDRAM['lang']['link_config'] = 'কনফিগারেশন';
$CIDRAM['lang']['link_documentation'] = 'ডকুমেন্টেশনটি';
$CIDRAM['lang']['link_file_manager'] = 'ফাইল ম্যানেজার';
$CIDRAM['lang']['link_home'] = 'হোম পেজ';
$CIDRAM['lang']['link_ip_aggregator'] = 'IP এগ্রিগেটর';
$CIDRAM['lang']['link_ip_test'] = 'IP টেস্ট';
$CIDRAM['lang']['link_ip_tracking'] = 'IP পর্যবেক্ষণ';
$CIDRAM['lang']['link_logs'] = 'লগ ফাইল';
$CIDRAM['lang']['link_range'] = 'রেঞ্জ টেবিল';
$CIDRAM['lang']['link_sections_list'] = 'বিভাগ তালিকা';
$CIDRAM['lang']['link_statistics'] = 'পরিসংখ্যান';
$CIDRAM['lang']['link_textmode'] = 'পাঠ্য বিন্যাস: <a href="%1$sfalse%2$s">সহজ</a> – <a href="%1$strue%2$s">অভিনব</a> – <a href="%1$stally%2$s">তালি</a>';
$CIDRAM['lang']['link_updates'] = 'আপডেট';
$CIDRAM['lang']['logs_logfile_doesnt_exist'] = 'নির্বাচিত লগ ফাইল বিদ্যমান নেই!';
$CIDRAM['lang']['logs_no_logfile_selected'] = 'কোনও লগ ফাইল নির্বাচিত নেই।';
$CIDRAM['lang']['logs_no_logfiles_available'] = 'কোন লগ ফাইল উপলব্ধ।';
$CIDRAM['lang']['max_login_attempts_exceeded'] = 'লগইন প্রচেষ্টা সর্বাধিক সংখ্যা অতিক্রম করেছে; প্রবেশাধিকার অস্বীকার।';
$CIDRAM['lang']['previewer_days'] = 'দিন';
$CIDRAM['lang']['previewer_hours'] = 'ঘন্টার';
$CIDRAM['lang']['previewer_minutes'] = 'মিনিট';
$CIDRAM['lang']['previewer_months'] = 'মাস';
$CIDRAM['lang']['previewer_seconds'] = 'সেকেন্ড';
$CIDRAM['lang']['previewer_weeks'] = 'সপ্তাহ';
$CIDRAM['lang']['previewer_years'] = 'বছর';
$CIDRAM['lang']['response_2fa_invalid'] = 'ভুল 2FA কোড প্রবেশ করানো হয়েছে। প্রমাণীকরণ ব্যর্থ হয়েছে।';
$CIDRAM['lang']['response_2fa_valid'] = 'সফলভাবে প্রমাণিত।';
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
$CIDRAM['lang']['response_login_wrong_endpoint'] = 'ভুল শেষপ্রান্ত!';
$CIDRAM['lang']['response_no'] = 'না';
$CIDRAM['lang']['response_possible_problem_found'] = 'সম্ভাব্য সমস্যা পাওয়া যায়।';
$CIDRAM['lang']['response_rename_error'] = 'নামান্তর করতে ব্যর্থ!';
$CIDRAM['lang']['response_sanity_1'] = 'ফাইলটিতে অপ্রত্যাশিত সামগ্রী রয়েছে! ফাইল প্রত্যাখ্যাত!';
$CIDRAM['lang']['response_statistics_cleared'] = 'পরিসংখ্যান পরিস্কার।';
$CIDRAM['lang']['response_tracking_cleared'] = 'পর্যবেক্ষণ মুছে ফেলা হয়েছে।';
$CIDRAM['lang']['response_updates_already_up_to_date'] = 'ইতিমধ্যে আপ-টু-ডেট।';
$CIDRAM['lang']['response_updates_not_installed'] = 'কম্পোনেন্ট ইনস্টল করা নেই!';
$CIDRAM['lang']['response_updates_not_installed_php'] = 'কম্পোনেন্ট ইনস্টল করা নেই (PHP &gt;= {V} প্রয়োজন)!';
$CIDRAM['lang']['response_updates_outdated'] = 'আউটডেটেড!';
$CIDRAM['lang']['response_updates_outdated_manually'] = 'আউটডেটেড (দয়া করে ম্যানুয়ালি আপডেট করুন)!';
$CIDRAM['lang']['response_updates_outdated_php_version'] = 'আউটডেটেড (PHP &gt;= {V} প্রয়োজন)!';
$CIDRAM['lang']['response_updates_unable_to_determine'] = 'নির্ধারণ করতে অক্ষম।';
$CIDRAM['lang']['response_upload_error'] = 'আপলোড করতে ব্যর্থ!';
$CIDRAM['lang']['response_verification_failed'] = 'যাচাইয়ের ব্যর্থ! কম্পোনেন্ট ভাঙ্গা হতে পারে।';
$CIDRAM['lang']['response_verification_success'] = 'যাচাইয়ের সাফল্য! কোন সমস্যা খুঁজে পাওয়া যায় নি।';
$CIDRAM['lang']['response_yes'] = 'হাঁ';
$CIDRAM['lang']['state_async_deny'] = 'অনুমতিগুলি অ্যাসিঙ্ক্রোনাস অনুরোধগুলি সঞ্চালনের পর্যাপ্ত নয়। আবার চেষ্টা করুন লগ ইন।';
$CIDRAM['lang']['state_cache_is_empty'] = 'ক্যাশ খালি আছে।';
$CIDRAM['lang']['state_complete_access'] = 'সম্পূর্ণ প্রবেশাধিকার';
$CIDRAM['lang']['state_component_is_active'] = 'কম্পোনেন্ট সক্রিয়।';
$CIDRAM['lang']['state_component_is_inactive'] = 'কম্পোনেন্ট নিষ্ক্রিয়।';
$CIDRAM['lang']['state_component_is_provisional'] = 'কম্পোনেন্ট অস্থায়ী।';
$CIDRAM['lang']['state_default_password'] = 'সতর্কতা: ডিফল্ট পাসওয়ার্ড ব্যবহার করে!';
$CIDRAM['lang']['state_email_sent'] = 'ইমেল সফলভাবে "%s" এ পাঠানো হয়েছে।';
$CIDRAM['lang']['state_failed_missing'] = 'কার্য ব্যর্থ হয়েছে কারণ একটি প্রয়োজনীয় উপাদান অনুপলব্ধ।';
$CIDRAM['lang']['state_ignored'] = 'উপেক্ষিত';
$CIDRAM['lang']['state_loading'] = 'লোড হচ্ছে ...';
$CIDRAM['lang']['state_loadtime'] = 'পৃষ্ঠা অনুরোধ সম্পন্ন <span class="txtRd">%s</span> সেকেন্ড।';
$CIDRAM['lang']['state_logged_in'] = 'লগ ইন হয়।';
$CIDRAM['lang']['state_logged_in_2fa_pending'] = 'লগ ইন হয় + 2FA মুলতুবি।';
$CIDRAM['lang']['state_logged_out'] = 'লগ আউট হয়।';
$CIDRAM['lang']['state_logs_access_only'] = 'লগ প্রবেশাধিকার শুধুমাত্র';
$CIDRAM['lang']['state_maintenance_mode'] = 'সতর্কতা: রক্ষণাবেক্ষণ মোড সক্রিয় করা হয়!';
$CIDRAM['lang']['state_password_not_valid'] = 'সতর্কতা: এই অ্যাকাউন্টটি একটি বৈধ পাসওয়ার্ড ব্যবহার করছে না!';
$CIDRAM['lang']['state_risk_high'] = 'উচ্চ';
$CIDRAM['lang']['state_risk_low'] = 'কম';
$CIDRAM['lang']['state_risk_medium'] = 'মধ্যম';
$CIDRAM['lang']['state_sl_totals'] = 'মোট (স্বাক্ষর: <span class="txtRd">%s</span> – স্বাক্ষর বিভাগ: <span class="txtRd">%s</span> – স্বাক্ষর ফাইল: <span class="txtRd">%s</span>)।';
$CIDRAM['lang']['state_tracking'] = 'বর্তমানে %s IP ঠিকানাগুলি পর্যবেক্ষণ করছে।';
$CIDRAM['lang']['switch-hide-non-outdated-set-false'] = 'অ-আউটডেটেড লুকান না';
$CIDRAM['lang']['switch-hide-non-outdated-set-true'] = 'অ-আউটডেটেড লুকান';
$CIDRAM['lang']['switch-hide-unused-set-false'] = 'অব্যবহৃত লুকান না';
$CIDRAM['lang']['switch-hide-unused-set-true'] = 'অব্যবহৃত লুকান';
$CIDRAM['lang']['switch-tracking-blocked-already-set-false'] = 'স্বাক্ষর ফাইলগুলির বিরুদ্ধে পরীক্ষা করুন না';
$CIDRAM['lang']['switch-tracking-blocked-already-set-true'] = 'স্বাক্ষর ফাইলগুলির বিরুদ্ধে পরীক্ষা করুন';
$CIDRAM['lang']['switch-tracking-hide-banned-blocked-set-false'] = 'নিষিদ্ধ/ব্লক করা আছে IP ঠিকানা লুকান না';
$CIDRAM['lang']['switch-tracking-hide-banned-blocked-set-true'] = 'নিষিদ্ধ/ব্লক করা আছে IP ঠিকানা লুকান';
$CIDRAM['lang']['tip_2fa_sent'] = 'একটি দুই-ফ্যাক্টর প্রমাণীকরণ কোড ধারণকারী একটি ইমেল আপনার ইমেল ঠিকানা পাঠানো হয়েছে। ফ্রন্ট-এন্ড পৌঁছানোর জন্য দয়া করে নীচের এই কোডটি নিশ্চিত করুন। যদি আপনি এই ইমেলটি না পান, তবে 10 মিনিটের জন্য অপেক্ষা করে লগ আউট করে চেষ্টা করুন এবং নতুন কোড সহ একটি নতুন ইমেল পাওয়ার জন্য আবার লগ ইন করুন।';
$CIDRAM['lang']['tip_accounts'] = 'হ্যালো, {username}।<br />আপনি এই পৃষ্ঠাতে অ্যাকাউন্টগুলি তৈরি করতে, মুছে ফেলতে এবং সংশোধন করতে পারেন (CIDRAM ফ্রন্ট-এন্ড কে প্রবেশাধিকার করতে পারে তা নির্ধারণ করে)।';
$CIDRAM['lang']['tip_cache_data'] = 'হ্যালো, {username}।<br />এখানে আপনি ক্যাশের বিষয়বস্তু পর্যালোচনা করতে পারেন।';
$CIDRAM['lang']['tip_cidr_calc'] = 'হ্যালো, {username}।<br />আপনি এখানে IP অ্যাড্রেসকে গুণনীয়ক নির্ণয় করা করতে পারেন (যে CIDRগুলি IP অ্যাড্রেস এর সদস্য)।';
$CIDRAM['lang']['tip_config'] = 'হ্যালো, {username}।<br />আপনি এই পৃষ্ঠা থেকে CIDRAM কনফিগারেশন পরিবর্তন করতে।';
$CIDRAM['lang']['tip_custom_ua'] = 'এখানে ব্যবহারকারী এজেন্ট (user agent) লিখুন (ঐচ্ছিক)।';
$CIDRAM['lang']['tip_donate'] = 'CIDRAM বিনামূল্যে প্রদান করা হয়, কিন্তু যদি আপনি এই প্রকল্পের জন্য দান করতে চান, আপনি দান বোতাম ক্লিক করে তা করতে পারেন।';
$CIDRAM['lang']['tip_enter_ip_here'] = 'IP এখানে প্রবেশ করান।';
$CIDRAM['lang']['tip_enter_ips_here'] = 'IPগুলি এখানে প্রবেশ করান।';
$CIDRAM['lang']['tip_fe_cookie_warning'] = 'নোট: লগইনগুলির প্রমাণীকরণের জন্য CIDRAM একটি কুকি ব্যবহার করে। লগ ইন করে, আপনি আপনার ব্রাউজার দ্বারা তৈরি এবং সংরক্ষিত একটি কুকি জন্য আপনার অনুমতি দিন।';
$CIDRAM['lang']['tip_file_manager'] = 'হ্যালো, {username}।<br />ফাইল ম্যানেজার ব্যবহার করে আপনি ফাইলগুলি মুছে, সম্পাদনা, আপলোড এবং ডাউনলোড করতে পারেন। সতর্কতার সাথে ব্যবহার করুন (আপনি এই সঙ্গে আপনার ইনস্টলেশন বিরতি হতে পারে)।';
$CIDRAM['lang']['tip_home'] = 'হ্যালো, {username}।<br />এটা CIDRAM ফ্রন্ট-এন্ড হোম পেজ হয়। চালিয়ে যেতে বাম দিকে নেভিগেশন মেনু থেকে একটি লিঙ্ক নির্বাচন করুন।';
$CIDRAM['lang']['tip_ip_aggregator'] = 'হ্যালো, {username}।<br />IP এগ্রিগেটর আপনাকে ক্ষুদ্রতম সম্ভাব্য পদ্ধতিতে IPগুলি এবং CIDRগুলি প্রকাশ করতে দেয়। একত্রিত হওয়ার জন্য তথ্য সন্নিবেশ করান এবং "OK" টিপুন।';
$CIDRAM['lang']['tip_ip_test'] = 'হ্যালো, {username}।<br />IP পরীক্ষা পৃষ্ঠা আপনাকে বর্তমানে ইনস্টল করা স্বাক্ষর দ্বারা আইপি অ্যাড্রেসগুলিকে ব্লক করে কিনা তা পরীক্ষা করতে দেয়।';
$CIDRAM['lang']['tip_ip_test_module_switch'] = '(নির্বাচিত না হলে শুধুমাত্র স্বাক্ষর ফাইলগুলি পরীক্ষা করা হবে)।';
$CIDRAM['lang']['tip_ip_tracking'] = 'হ্যালো, {username}।<br />IP পর্যবেক্ষণ পৃষ্ঠা আপনাকে IP ঠিকানাগুলির নিরীক্ষণ করা হচ্ছে তা পরীক্ষা করার অনুমতি দেয়, যাদেরকে নিষিদ্ধ করা হয়েছে তা দেখতে, এবং যদি আপনি চান, নিষিদ্ধ অপসারণ।';
$CIDRAM['lang']['tip_login'] = 'ডিফল্ট ব্যবহারকারীর নাম: <span class="txtRd">admin</span> – ডিফল্ট পাসওয়ার্ড: <span class="txtRd">password</span>';
$CIDRAM['lang']['tip_logs'] = 'হ্যালো, {username}।<br />যে লগ ফাইল এর বিষয়বস্তু দেখতে নীচের তালিকা থেকে একটি লগ ফাইল নির্বাচন করুন।';
$CIDRAM['lang']['tip_range'] = 'হ্যালো, {username}।<br />এই পৃষ্ঠাটি বর্তমানে সক্রিয় স্বাক্ষর ফাইলগুলির দ্বারা আবৃত IP রেঞ্জ সম্পর্কে কিছু মৌলিক পরিসংখ্যানগত তথ্য দেখায়।';
$CIDRAM['lang']['tip_sections_list'] = 'হ্যালো, {username}।<br />এই পৃষ্ঠাটি বর্তমানে সক্রিয় স্বাক্ষর ফাইলগুলির মধ্যে কোন অংশগুলি বিদ্যমান।';
$CIDRAM['lang']['tip_see_the_documentation'] = 'বিভিন্ন কনফিগারেশন নির্দেশাবলী এবং তাদের উদ্দেশ্য সম্পর্কে আরও তথ্যের জন্য <a href="https://github.com/CIDRAM/CIDRAM/blob/master/_docs/readme.en.md#SECTION6">ডকুমেন্টেশনটি</a> দেখুন।';
$CIDRAM['lang']['tip_statistics'] = 'হ্যালো, {username}।<br />এই পৃষ্ঠাটি আপনার CIDRAM ইনস্টলেশনের সম্পর্কিত কিছু মৌলিক ব্যবহারের পরিসংখ্যান দেখায়।';
$CIDRAM['lang']['tip_statistics_disabled'] = 'নোট: পরিসংখ্যান ট্র্যাকিং বর্তমানে অক্ষম আছে, তবে কনফিগারেশন পৃষ্ঠার মাধ্যমে সক্ষম করা যেতে পারে।';
$CIDRAM['lang']['tip_updates'] = 'হ্যালো, {username}।<br />আপডেট পৃষ্ঠাটি আপনাকে CIDRAM কম্পোনেন্ট ইনস্টল, আনইনস্টল এবং আপডেট করতে দেয় (কোর প্যাকেজ, স্বাক্ষর, L10N ফাইল, ইত্যাদি)।';
$CIDRAM['lang']['title_accounts'] = 'CIDRAM – অ্যাকাউন্ট';
$CIDRAM['lang']['title_cache_data'] = 'CIDRAM – ক্যাশ ডেটা';
$CIDRAM['lang']['title_cidr_calc'] = 'CIDRAM – CIDR ক্যালকুলেটর';
$CIDRAM['lang']['title_config'] = 'CIDRAM – কনফিগারেশন';
$CIDRAM['lang']['title_file_manager'] = 'CIDRAM – ফাইল ম্যানেজার';
$CIDRAM['lang']['title_home'] = 'CIDRAM – হোম পেজ';
$CIDRAM['lang']['title_ip_aggregator'] = 'CIDRAM – IP এগ্রিগেটর';
$CIDRAM['lang']['title_ip_test'] = 'CIDRAM – IP টেস্ট';
$CIDRAM['lang']['title_ip_tracking'] = 'CIDRAM – IP পর্যবেক্ষণ';
$CIDRAM['lang']['title_login'] = 'CIDRAM – লগ ইন';
$CIDRAM['lang']['title_logs'] = 'CIDRAM – লগ ফাইল';
$CIDRAM['lang']['title_range'] = 'CIDRAM – রেঞ্জ টেবিল';
$CIDRAM['lang']['title_sections_list'] = 'CIDRAM – বিভাগ তালিকা';
$CIDRAM['lang']['title_statistics'] = 'CIDRAM – পরিসংখ্যান';
$CIDRAM['lang']['title_updates'] = 'CIDRAM – আপডেট';
$CIDRAM['lang']['warning'] = 'সতর্কবাণী:';
$CIDRAM['lang']['warning_php_1'] = 'আপনার PHP সংস্করণ সক্রিয়ভাবে আর সমর্থিত হয় না! আপডেট করা হচ্ছে সুপারিশ!';
$CIDRAM['lang']['warning_php_2'] = 'আপনার PHP সংস্করণ গুরুতরভাবে ঝুঁকিপূর্ণ! আপডেট করা অত্যন্ত দৃঢ়ভাবে সুপারিশ করা হয়!';
$CIDRAM['lang']['warning_signatures_1'] = 'কোন স্বাক্ষর ফাইল সক্রিয় নেই!';

$CIDRAM['lang']['info_some_useful_links'] = 'কিছু দরকারী লিঙ্ক:<ul>
            <li><a href="https://github.com/CIDRAM/CIDRAM/issues">CIDRAM সমর্থন @ GitHub</a> – CIDRAM সমর্থন পৃষ্ঠা (সহায়তা, সহায়তা, ইত্যাদির জন্য)।</li>
            <li><a href="https://wordpress.org/plugins/cidram/">CIDRAM @ WordPress.org</a> – CIDRAM জন্য WordPress প্লাগইন।</li>
            <li><a href="https://bitbucket.org/macmathan/blocklists">macmathan/blocklists</a> – CIDRAM এর জন্য ঐচ্ছিক ব্লক-লিস্ট এবং মডিউল রয়েছে যেমন বিপজ্জনক বট, অবাঞ্ছিত দেশ, পুরনো ব্রাউজার ইত্যাদি অবরুদ্ধ করা।</li>
            <li><a href="https://www.facebook.com/groups/2204685680/">Global PHP Group @ Facebook</a> – PHP লার্নিং সম্পদ এবং আলোচনা।</li>
            <li><a href="https://php.earth/">PHP.earth</a> – PHP লার্নিং সম্পদ এবং আলোচনা।</li>
            <li><a href="http://bgp.he.net/">Hurricane Electric BGP টুলকিট</a> – ASNগুলি থেকে CIDRগুলি পান, ASN সম্পর্কগুলি নির্ধারণ করুন, নেটওয়ার্ক নাম উপর ভিত্তি করে ASNগুলি আবিষ্কার, ইত্যাদি।</li>
            <li><a href="https://www.stopforumspam.com/forum/">ফোরাম @ Stop Forum Spam</a> – ফোরাম স্প্যাম বন্ধ করার জন্য দরকারী আলোচনা ফোরাম।</li>
            <li><a href="https://radar.qrator.net/">Qrator Radar</a> – দরকারী ASN সংযোগ পরীক্ষা সরঞ্জাম।</li>
            <li><a href="http://www.ipdeny.com/ipblocks/">IPdeny IP দেশ ব্লক</a> – দেশব্যাপী স্বাক্ষর তৈরির জন্য একটি চমত্কার এবং সঠিক পরিষেবা।</li>
            <li><a href="https://www.google.com/transparencyreport/safebrowsing/malware/">Google ম্যালওয়ারের ড্যাশবোর্ড</a> – ASNগুলি জন্য ম্যালওয়্যার সংক্রমণের হার রিপোর্ট।</li>
            <li><a href="https://www.spamhaus.org/statistics/botnet-asn/">The Spamhaus Project</a> – ASNগুলি এর জন্য ম্যালওয়ার সংক্রমণের হার সংক্রান্ত প্রতিবেদনগুলি প্রদর্শন করে।</li>
            <li><a href="https://www.abuseat.org/public/asn.html">Abuseat.org কম্পোজিট ব্লকিং তালিকা</a> – ASNগুলি এর জন্য ম্যালওয়ার সংক্রমণের হার সংক্রান্ত প্রতিবেদনগুলি প্রদর্শন করে।</li>
            <li><a href="https://abuseipdb.com/">AbuseIPDB</a> – একটি পরিচিত অপমানজনক IPগুলি ডাটাবেস বজায় রাখে; IPগুলি চেক এবং রিপোর্ট করার জন্য একটি API উপলব্ধ করে।</li>
            <li><a href="https://www.megarbl.net/index.php">MegaRBL.net</a> – পরিচিত স্প্যামারদের তালিকা বজায় রাখে; IP/ASN স্প্যাম ক্রিয়াকলাপ পরীক্ষা করার জন্য দরকারী।</li>
            <li><a href="https://maikuolan.github.io/Vulnerability-Charts/">Vulnerability Charts (দুর্বলতা চার্ট)</a> – বিভিন্ন প্যাকেজের নিরাপদ/অনিরাপদ সংস্করণের তালিকা (HHVM, PHP, phpMyAdmin, Python, ইত্যাদি)।</li>
            <li><a href="https://maikuolan.github.io/Compatibility-Charts/">Compatibility Charts (সামঞ্জস্যের চার্ট)</a> – বিভিন্ন প্যাকেজের জন্য সামঞ্জস্য তথ্য তালিকা (CIDRAM, phpMussel, ইত্যাদি)।</li>
        </ul>';

$CIDRAM['lang']['msg_template_2fa'] = '<center><p>হ্যালো, %1$s।<br />
<br />
CIDRAM ফ্রন্ট-এন্ড প্রবেশের জন্য আপনার দুই-ফ্যাক্টর প্রমাণীকরণ কোড:</p>
<h1>%2$s</h1>
<p>এই কোডটি 10 মিনিটের মধ্যে মেয়াদ শেষ হয়ে যায়।</p></center>';
$CIDRAM['lang']['msg_subject_2fa'] = 'দুই-ফ্যাক্টর প্রমাণীকরণ';
