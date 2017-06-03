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
 * This file: Hindi language data for CLI (last modified: 2017.06.03).
 */

/** Prevents execution from outside of CIDRAM. */
if (!defined('CIDRAM')) {
    die('[CIDRAM] This should not be accessed directly.');
}

$CIDRAM['lang']['CLI_H'] = "
 CIDRAM CLI-मोड सहायता।

 कैसे इस्तेमाल करे:
 /पथ/के/लिए/php/php.exe /पथ/के/लिए/cidram/loader.php -फ्लैग (इनपुट)

 फ्लैग:
    -h  यह सहायता जानकारी प्रदर्शित करें।
    -c  जांचें कि क्या आईपी पता CIDRAM हस्ताक्षर फाइलों द्वारा अवरुद्ध है।
    -g  एक आईपी पते से CIDR उत्पन्न करें।

 इनपुट: कोई मान्य IPv4 या IPv6 आईपी पता हो सकता है।

 उदाहरण:
        -c  192.168.0.0/16
        -c  127.0.0.1/32
        -c  2001:db8::/32
        -c  2002::1/128

";

$CIDRAM['lang']['CLI_Bad_IP'] = ' निर्दिष्ट आईपी पता, "{IP}", एक मान्य IPv4 या IPv6 आईपी पता नहीं है!';
$CIDRAM['lang']['CLI_IP_Blocked'] = ' निर्दिष्ट आईपी पता, "{IP}", को CIDRAM हस्ताक्षर फाइलों द्वारा अवरुद्ध किया गया है।';
$CIDRAM['lang']['CLI_IP_Not_Blocked'] = ' निर्दिष्ट आईपी पता, "{IP}", को CIDRAM हस्ताक्षर फाइलों द्वारा अवरुद्ध नहीं है।';

$CIDRAM['lang']['CLI_F_Finished'] = 'हस्ताक्षर फिक्सर समाप्त हो गया है, %2$s आपरेशनों से %1$s परिवर्तन किए गए हैं (%3$s)।';
$CIDRAM['lang']['CLI_F_Started'] = 'हस्ताक्षर फिक्सर शुरू कर दिया है (%s)।';
$CIDRAM['lang']['CLI_VF_Empty'] = 'निर्दिष्ट हस्ताक्षर फाइल खाली है या मौजूद नहीं है।';
$CIDRAM['lang']['CLI_VF_Level_0'] = 'नोटिस';
$CIDRAM['lang']['CLI_VF_Level_1'] = 'चेतावनी';
$CIDRAM['lang']['CLI_VF_Level_2'] = 'त्रुटि';
$CIDRAM['lang']['CLI_VF_Level_3'] = 'घातक त्रुटि';

$CIDRAM['lang']['CLI_V_CRLF'] = 'हस्ताक्षर फाइल में CR/CRLF का पता लगाया गया; ये अनुमत हैं और समस्याएं पैदा नहीं करेंगे, लेकिन LF बेहतर है।';
$CIDRAM['lang']['CLI_V_Finished'] = 'हस्ताक्षर सत्यापनकर्ता समाप्त हो गया है (%s)। अगर कोई चेतावनियां या त्रुटियां दिखाई नहीं देती हैं, आपकी हस्ताक्षर फाइल शायद ठीक है। :-)';
$CIDRAM['lang']['CLI_V_LineByLine'] = 'लाइन-बाय-लाइन सत्यापन शुरू हो गया है।';
$CIDRAM['lang']['CLI_V_Started'] = 'हस्ताक्षर सत्यापनकर्ता शुरू कर दिया है (%s)।';
$CIDRAM['lang']['CLI_V_Terminal_LF'] = 'हस्ताक्षर फाइलें एक LF लाइनब्रेक से समाप्त होनी चाहिए।';

$CIDRAM['lang']['CLI_VL_CC'] = 'पंक्ति %s: नियंत्रण चरित्र का पता चला; इससे भ्रष्टाचार का संकेत हो सकता है और इसकी जांच होनी चाहिए।';
$CIDRAM['lang']['CLI_VL_Duplicated'] = 'पंक्ति %s: हस्ताक्षर "%s" डुप्लिकेट है (%s गिनती है)!';
$CIDRAM['lang']['CLI_VL_Expiry'] = 'पंक्ति %s: समाप्ति टैग में एक मान्य आईएसओ 8601 तिथि/समय नहीं है!';
$CIDRAM['lang']['CLI_VL_Invalid'] = 'पंक्ति %s: "%s" एक मान्य IPv4 या IPv6 पता नहीं है!';
$CIDRAM['lang']['CLI_VL_L120'] = 'पंक्ति %s: रेखा की लंबाई 120 बाइट्स से अधिक है; इष्टतम पठनीयता के लिए रेखा की लंबाई 120 बाइट तक सीमित होनी चाहिए।';
$CIDRAM['lang']['CLI_VL_Mergeable'] = 'L%s और L%s समान हैं, और विलय कर सकते हैं।';
$CIDRAM['lang']['CLI_VL_Missing'] = 'पंक्ति %s: गायब [फ़ंक्शन]; हस्ताक्षर अपूर्ण प्रतीत होता है।';
$CIDRAM['lang']['CLI_VL_Nontriggerable'] = 'पंक्ति %s: "%s" ट्रिगर नहीं किया जा सकता है! इसका आधार इसकी सीमा की शुरुआत से मेल नहीं खाता! इसे "%s" के साथ बदलने की कोशिश करें।';
$CIDRAM['lang']['CLI_VL_Nontriggerable_Range'] = 'पंक्ति %s: "%s" ट्रिगर नहीं किया जा सकता है! "%s" एक मान्य सीमा नहीं है!';
$CIDRAM['lang']['CLI_VL_Subordinate'] = 'पंक्ति %s: "%s" हस्ताक्षर "%s" को अधीनस्थ है।';
$CIDRAM['lang']['CLI_VL_Superset'] = 'पंक्ति %s: "%s" हस्ताक्षर "%s" का एक सुपरसेट है।';
$CIDRAM['lang']['CLI_VL_Syntax'] = 'पंक्ति %s: वाक्यविन्यास रूप से सटीक नहीं।';
$CIDRAM['lang']['CLI_VL_Tabs'] = 'पंक्ति %s: टैब्स का पता चला; इष्टतम पठनीयता के लिए टैब से अधिक स्थान पसंद है।';
$CIDRAM['lang']['CLI_VL_Tags'] = 'पंक्ति %s: सेक्शन टैग 20 बाइट्स से अधिक है; सेक्शन टैग स्पष्ट और संक्षिप्त होना चाहिए।';
$CIDRAM['lang']['CLI_VL_Unrecognised'] = 'पंक्ति %s: पहचानने अयोग्य [फ़ंक्शन]; हस्ताक्षर टूट सकता है।';
$CIDRAM['lang']['CLI_VL_Whitespace'] = 'पंक्ति %s: इस लाइन पर अत्यधिक श्वेत स्थान का पता चला है।';
$CIDRAM['lang']['CLI_VL_YAML'] = 'पंक्ति %s: YAML डेटा का पता चला है, लेकिन इसे संसाधित नहीं किया जा सका।';
