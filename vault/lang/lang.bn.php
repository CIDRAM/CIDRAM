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
 * This file: Bangla language data (last modified: 2018.05.04).
 */

/** Prevents execution from outside of CIDRAM. */
if (!defined('CIDRAM')) {
    die('[CIDRAM] This should not be accessed directly.');
}

$CIDRAM['lang']['Error_WriteCache'] = 'ক্যাশে লিখতে অক্ষম! আপনার CHMOD ফাইল অনুমতি চেক করুন!';
$CIDRAM['lang']['MoreInfo'] = 'আরও তথ্যের জন্য:';
$CIDRAM['lang']['ReasonMessage_BadIP'] = 'এই পৃষ্ঠাটিতে আপনার প্রবেশাধিকার অস্বীকার করা হয়েছে কারণ আপনি একটি অবৈধ IP ঠিকানা ব্যবহার করে এই পৃষ্ঠাটি অ্যাক্সেস করার চেষ্টা করেছেন।';
$CIDRAM['lang']['ReasonMessage_Banned'] = 'আপনার IP ঠিকানা থেকে আগের খারাপ আচরণের কারণে এই পৃষ্ঠাটিতে আপনার অ্যাক্সেস অস্বীকৃত হয়েছে।';
$CIDRAM['lang']['ReasonMessage_Bogon'] = 'এই পৃষ্ঠায় আপনার অ্যাক্সেসটি অস্বীকার করা হয়েছিল কারণ আপনার IP ঠিকানা একটি bogon ঠিকানা হিসাবে স্বীকৃত, এবং ওয়েবসাইটের মালিক bogon IP অ্যাড্রেস এই ওয়েবসাইটে সংযোগ করতে অনুমতি দেয় না।';
$CIDRAM['lang']['ReasonMessage_Cloud'] = 'এই পৃষ্ঠায় আপনার অ্যাক্সেসটি অস্বীকার করা হয়েছিল কারণ আপনার IP ঠিকানাটি ক্লাউড সার্ভিসের সাথে সম্পর্কিত হিসাবে স্বীকৃত, এবং ক্লাউড পরিষেবা এই ওয়েবসাইট থেকে সংযোগ করার অনুমতি নেই।';
$CIDRAM['lang']['ReasonMessage_Generic'] = 'এই পৃষ্ঠায় আপনার অ্যাক্সেসটি অস্বীকার করা হয়েছিল কারণ আপনার IP ঠিকানা এই ওয়েবসাইট দ্বারা ব্যবহৃত ব্ল্যাকলিস্টে তালিকাভুক্ত একটি নেটওয়ার্কের সাথে সম্পর্কিত।';
$CIDRAM['lang']['ReasonMessage_Legal'] = 'এই পৃষ্ঠাতে আপনার অ্যাক্সেস আইনি বাধ্যবাধকতা কারণে অস্বীকার করা হয়েছিল।';
$CIDRAM['lang']['ReasonMessage_Malware'] = 'আপনার IP ঠিকানা সম্পর্কিত ম্যালওয়ারের উদ্বেগের কারণে এই পৃষ্ঠায় আপনার অ্যাক্সেস অস্বীকৃত হয়েছে।';
$CIDRAM['lang']['ReasonMessage_Proxy'] = 'এই পৃষ্ঠায় আপনার অ্যাক্সেসটি অস্বীকার করা হয়েছিল কারণ আপনার IP ঠিকানাটি প্রক্সি পরিষেবার বা VPN অন্তর্গত হিসাবে স্বীকৃত, এবং প্রক্সি পরিষেবা বা VPN এই ওয়েবসাইট থেকে সংযোগ করার অনুমতি নেই।';
$CIDRAM['lang']['ReasonMessage_Spam'] = 'এই পৃষ্ঠায় আপনার অ্যাক্সেসটি অস্বীকার করা হয়েছিল কারণ আপনার IP ঠিকানা স্প্যামারদের সাথে যুক্ত একটি নেটওয়ার্কের সাথে সম্পর্কিত।';
$CIDRAM['lang']['Short_BadIP'] = 'অবৈধ IP';
$CIDRAM['lang']['Short_Banned'] = 'নিষিদ্ধ';
$CIDRAM['lang']['Short_Bogon'] = 'Bogon IP';
$CIDRAM['lang']['Short_Cloud'] = 'মেঘ পরিষেবা';
$CIDRAM['lang']['Short_Generic'] = 'জাতিবাচক';
$CIDRAM['lang']['Short_Legal'] = 'আইনি';
$CIDRAM['lang']['Short_Malware'] = 'ম্যালওয়ারের';
$CIDRAM['lang']['Short_Proxy'] = 'প্রক্সি';
$CIDRAM['lang']['Short_Spam'] = 'স্প্যাম ঝুঁকি';
$CIDRAM['lang']['Support_Email'] = 'যদি এটি একটি ত্রুটি, বা সহায়তার সন্ধান, {ClickHereLink} এই ওয়েবসাইটের ওয়েবমাস্টার একটি ইমেইল সমর্থন টিকেট পাঠাতে (দয়া করে ইমেলের প্রস্তাবনা বা বিষয় লাইন পরিবর্তন করবেন না)।';
$CIDRAM['lang']['Support_Email_2'] = 'আপনি যদি বিশ্বাস করেন এটি একটি ত্রুটি, তাহলে সাহায্যের জন্য {EmailAddr} এ একটি ইমেল পাঠান।';
$CIDRAM['lang']['click_here'] = 'এখানে ক্লিক করুন';
$CIDRAM['lang']['denied'] = 'প্রবেশাধিকার অস্বীকার!';
$CIDRAM['lang']['fake_ua'] = 'জাল {ua}';
$CIDRAM['lang']['field_datetime'] = 'তারিখ/সময়: ';
$CIDRAM['lang']['field_hostname'] = 'হোস্টনাম: ';
$CIDRAM['lang']['field_id'] = 'ID: ';
$CIDRAM['lang']['field_ipaddr'] = 'IP ঠিকানা: ';
$CIDRAM['lang']['field_ipaddr_resolved'] = 'IP ঠিকানা (স্থিরপ্রতিজ্ঞ): ';
$CIDRAM['lang']['field_query'] = 'প্রশ্ন: ';
$CIDRAM['lang']['field_rURI'] = 'পুনর্নির্মাণ URL: ';
$CIDRAM['lang']['field_reCAPTCHA_state'] = 'reCAPTCHA অবস্থা: ';
$CIDRAM['lang']['field_referrer'] = 'রেফারার: ';
$CIDRAM['lang']['field_scriptversion'] = 'স্ক্রিপ্ট সংস্করণ: ';
$CIDRAM['lang']['field_sigcount'] = 'স্বাক্ষর গণনা: ';
$CIDRAM['lang']['field_sigref'] = 'স্বাক্ষর রেফারেন্স: ';
$CIDRAM['lang']['field_ua'] = 'User Agent: ';
$CIDRAM['lang']['field_whyreason'] = 'কেন অবরুদ্ধ: ';
$CIDRAM['lang']['generated_by'] = 'জেনারেটর:';
$CIDRAM['lang']['preamble'] = '-- প্রস্তাবনা শেষ। এই লাইন পরে আপনার প্রশ্ন বা মন্তব্য যোগ করুন। --';
$CIDRAM['lang']['recaptcha_cookie_warning'] = 'দয়া করে নোট করুন: CIDRAM ব্যবহারকারীদের CAPTCHA সম্পূর্ণ করার সময় মনে রাখার জন্য একটি কুকি ব্যবহার করে। CAPTCHA সম্পূর্ণ করে, আপনি আপনার ব্রাউজার দ্বারা তৈরি এবং সংরক্ষণ করা একটি কুকি জন্য আপনার অনুমতি দেয়।';
$CIDRAM['lang']['recaptcha_disabled'] = 'অফলাইন।';
$CIDRAM['lang']['recaptcha_enabled'] = 'অনলাইন।';
$CIDRAM['lang']['recaptcha_failed'] = 'ব্যর্থ!';
$CIDRAM['lang']['recaptcha_message'] = 'এই পৃষ্ঠায় অ্যাক্সেস পুনরায় পেতে, দয়া করে নীচে দেওয়া CAPTCHA পূরণ করুন এবং জমা বোতামটি টিপুন।';
$CIDRAM['lang']['recaptcha_message_invisible'] = 'বেশিরভাগ ব্যবহারকারীর জন্য, পৃষ্ঠাটি স্বাভাবিক অ্যাক্সেস রিফ্রেশ এবং পুনঃস্থাপন করা উচিত। কিছু ক্ষেত্রে, আপনি একটি CAPTCHA চ্যালেঞ্জ সম্পূর্ণ করতে প্রয়োজন হতে পারে।';
$CIDRAM['lang']['recaptcha_passed'] = 'পাস!';
$CIDRAM['lang']['recaptcha_submit'] = 'জমা দিন';
