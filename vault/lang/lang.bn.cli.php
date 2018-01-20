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
 * This file: Bangla language data for CLI (last modified: 2018.01.20).
 */

/** Prevents execution from outside of CIDRAM. */
if (!defined('CIDRAM')) {
    die('[CIDRAM] This should not be accessed directly.');
}

$CIDRAM['lang']['CLI_H'] = "
 CIDRAM CLI- মোড সাহায্য।

 ব্যবহার:
 php.exe /cidram/loader.php -Flag (ইনপুট)

 Flags: -h এই সহায়তা তথ্য প্রদর্শন করুন।
        -c CIDRAM স্বাক্ষর ফাইলগুলি দ্বারা IP ঠিকানাটি আবদ্ধ হলে দেখুন।
        -g একটি IP ঠিকানা থেকে CIDR তৈরি করুন।
        -v একটি স্বাক্ষর ফাইল যাচাই করুন।
        -f একটি স্বাক্ষর ফাইল ঠিক করুন।

 উদাহরণ:
 php.exe /cidram/loader.php -c 192.168.0.0
 php.exe /cidram/loader.php -c 2001:db8::
 php.exe /cidram/loader.php -g 1.2.3.4
 php.exe /cidram/loader.php -g ::1
 php.exe /cidram/loader.php -f signatures.dat
 php.exe /cidram/loader.php -v signatures.dat

";

$CIDRAM['lang']['CLI_Bad_IP'] = ' নির্দিষ্ট IP ঠিকানা, "{IP}", একটি বৈধ IPv4 বা IPv6 IP ঠিকানা নয়!';
$CIDRAM['lang']['CLI_IP_Blocked'] = ' নির্দিষ্ট IP ঠিকানা, "{IP}", স্বাক্ষর ফাইল দ্বারা ব্লক করা হয়।';
$CIDRAM['lang']['CLI_IP_Not_Blocked'] = ' নির্দিষ্ট IP ঠিকানা, "{IP}", স্বাক্ষর ফাইল দ্বারা ব্লক করা হয় না।';

$CIDRAM['lang']['CLI_F_Finished'] = 'স্বাক্ষর মেরামতকারী শেষ হয়েছে। %s পরিবর্তনগুলি, %s অপারেশন (%s)।';
$CIDRAM['lang']['CLI_F_Started'] = 'স্বাক্ষর মেরামতকারী শুরু হয়েছে (%s)।';
$CIDRAM['lang']['CLI_VF_Empty'] = 'নির্দিষ্ট স্বাক্ষর ফাইল খালি আছে বা অস্তিত্ব নেই।';
$CIDRAM['lang']['CLI_VF_Level_0'] = 'বিজ্ঞপ্তি';
$CIDRAM['lang']['CLI_VF_Level_1'] = 'সতর্কতা';
$CIDRAM['lang']['CLI_VF_Level_2'] = 'ত্রুটি';
$CIDRAM['lang']['CLI_VF_Level_3'] = 'মারাত্মক ত্রুটি';

$CIDRAM['lang']['CLI_V_CRLF'] = 'স্বাক্ষর ফাইলের মধ্যে CR/CRLF সনাক্ত করা হয়েছে; এটি অনুমোদিত, কিন্তু LF পছন্দ করা হয়।';
$CIDRAM['lang']['CLI_V_Finished'] = 'স্বাক্ষর যাচাইকারী সমাপ্ত হয়েছে (%s)। কোন সতর্কতা বা ত্রুটি না থাকলে, আপনার স্বাক্ষর ফাইল সম্ভবত ঠিক আছে। :-)';
$CIDRAM['lang']['CLI_V_LineByLine'] = 'লাইন-দ্বারা-লাইন বৈধতা শুরু হয়েছে।';
$CIDRAM['lang']['CLI_V_Started'] = 'স্বাক্ষর যাচাইকারী শুরু হয়েছে (%s)।';
$CIDRAM['lang']['CLI_V_Terminal_LF'] = 'স্বাক্ষর ফাইলগুলি একটি LF দিয়ে শেষ হওয়া উচিত।';

$CIDRAM['lang']['CLI_VL_CC'] = 'লাইন %s: কন্ট্রোল অক্ষর সনাক্ত; এই দুর্নীতির কথা বলতে পারে এবং তদন্ত করা উচিত।';
$CIDRAM['lang']['CLI_VL_Duplicated'] = 'লাইন %s: স্বাক্ষর "%s" ডুপ্লিকেট করা হয় (%s ঘটনা)!';
$CIDRAM['lang']['CLI_VL_Expiry'] = 'লাইন %s: মেয়াদপূর্তি ট্যাগের একটি বৈধ ISO 8601 তারিখ/সময় নেই!';
$CIDRAM['lang']['CLI_VL_Invalid'] = 'লাইন %s: "%s" একটি বৈধ IPv4 বা IPv6 ঠিকানা নয়!';
$CIDRAM['lang']['CLI_VL_L120'] = 'লাইন %s: রেখা দৈর্ঘ্য 120 বাইটের চেয়ে বড়; সেরা পঠনযোগ্যতার জন্য রেখা দৈর্ঘ্য 120 বাইট পর্যন্ত সীমাবদ্ধ হওয়া উচিত।';
$CIDRAM['lang']['CLI_VL_Mergeable'] = 'লাইন %s এবং লাইন %s এবং অভিন্ন, এবং তারা মার্জ করা যেতে পারে।';
$CIDRAM['lang']['CLI_VL_Missing'] = 'লাইন %s: [Function] অনুপস্থিত; স্বাক্ষর অসম্পূর্ণ বলে মনে হচ্ছে।';
$CIDRAM['lang']['CLI_VL_Nontriggerable'] = 'লাইন %s: "%s" চালিত হতে পারে না! এর বেস তার রেঞ্জ প্রারম্ভে মেলে না! "%s" এ পরিবর্তন করার চেষ্টা করুন।';
$CIDRAM['lang']['CLI_VL_Nontriggerable_Range'] = 'লাইন %s: "%s" চালিত হতে পারে না! "%s" একটি বৈধ রেঞ্জ নয়!';
$CIDRAM['lang']['CLI_VL_Origin'] = 'লাইন %s: মাত্রিভূমি ট্যাগ একটি বৈধ ISO 3166-1 Alpha-2 কোড ধারণ করে না!';
$CIDRAM['lang']['CLI_VL_Subordinate'] = 'লাইন %s: "%s" হল "%s" এর একটি সদস্য।';
$CIDRAM['lang']['CLI_VL_Superset'] = 'লাইন %s: "%s" হল "%s" এর একটি সুপারসেট।';
$CIDRAM['lang']['CLI_VL_Syntax'] = 'লাইন %s: সিনট্যাক্টিকভাবে সুনির্দিষ্ট নয়।';
$CIDRAM['lang']['CLI_VL_Tabs'] = 'লাইন %s: ট্যাব সনাক্ত; সেরা পঠনযোগ্যতা জন্য ট্যাব পরিবর্তে ফাঁকা স্থান ব্যবহার করুন।';
$CIDRAM['lang']['CLI_VL_Tags'] = 'লাইন %s: বিভাগ ট্যাগ 20 বাইটের চেয়ে বড়; বিভাগ ট্যাগ পরিষ্কার এবং সংক্ষিপ্ত হওয়া উচিত।';
$CIDRAM['lang']['CLI_VL_Unrecognised'] = 'লাইন %s: অচেনা [Function]; স্বাক্ষর ভাঙ্গা হতে পারে।';
$CIDRAM['lang']['CLI_VL_Whitespace'] = 'লাইন %s: বহিঃস্থ স্থান এই লাইন উপর শনাক্ত।';
$CIDRAM['lang']['CLI_VL_YAML'] = 'লাইন %s: YAML-র মত ডেটা সনাক্ত হয়েছে, কিন্তু এটি প্রক্রিয়া করতে পারেনি।';
