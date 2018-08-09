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
 * This file: Hindi language data (last modified: 2018.08.10).
 */

/** Prevents execution from outside of CIDRAM. */
if (!defined('CIDRAM')) {
    die('[CIDRAM] This should not be accessed directly.');
}

$CIDRAM['lang']['Error_MissingRequire'] = 'आवश्यक फाइलें गायब हैं! जारी नहीं रख सकते!';
$CIDRAM['lang']['Error_WriteCache'] = 'कैश में लिखने में असमर्थ! कृपया अपने CHMOD अनुमतियों की जांच करें!';
$CIDRAM['lang']['MoreInfo'] = 'अधिक जानकारी के लिए:';
$CIDRAM['lang']['PrivacyPolicy'] = 'गोपनीयता नीति';
$CIDRAM['lang']['ReasonMessage_BadIP'] = 'इस पृष्ठ पर प्रवेश से इंकार किया गया था। आपका आईपी पता मान्य नहीं है।';
$CIDRAM['lang']['ReasonMessage_Banned'] = 'आपके आईपी पते से पिछले दुर्व्यवहार के कारण, इस पृष्ठ पर प्रवेश से इंकार किया गया था।';
$CIDRAM['lang']['ReasonMessage_Bogon'] = 'इस पृष्ठ पर प्रवेश से इंकार किया गया था। आपका आईपी पता एक बोगोन पता के रूप में पहचाना जाता है, और बोगोनों से कनेक्ट करना वेबसाइट के स्वामी द्वारा अनुमति नहीं है।';
$CIDRAM['lang']['ReasonMessage_Cloud'] = 'इस पृष्ठ पर प्रवेश से इंकार किया गया था। आपका आईपी पता एक क्लाउड सर्विस का हिस्सा है, और क्लाउड सेवाओं से कनेक्ट करना वेबसाइट के स्वामी द्वारा अनुमति नहीं है।';
$CIDRAM['lang']['ReasonMessage_Generic'] = 'इस पृष्ठ पर प्रवेश से इंकार किया गया था। आपका आईपी पता इस वेबसाइट द्वारा उपयोग की गई एक ब्लैकलिस्ट पर है।';
$CIDRAM['lang']['ReasonMessage_Legal'] = 'कानूनी दायित्वों के कारण इस पृष्ठ तक आपकी पहुंच अस्वीकार कर दी गई थी।';
$CIDRAM['lang']['ReasonMessage_Malware'] = 'आपके IP पते से संबंधित मैलवेयर चिंताओं के कारण इस पृष्ठ तक आपकी पहुंच अस्वीकार कर दी गई थी।';
$CIDRAM['lang']['ReasonMessage_Proxy'] = 'इस पृष्ठ पर प्रवेश से इंकार किया गया था। आपका आईपी पता प्रॉक्सी सेवा से संबंधित है, और प्रॉक्सी सेवाओं से कनेक्ट करना वेबसाइट के स्वामी द्वारा अनुमति नहीं है।';
$CIDRAM['lang']['ReasonMessage_Spam'] = 'इस पृष्ठ पर प्रवेश से इंकार किया गया था। आपका आईपी पता एक स्पैमर-अनुकूल नेटवर्क से संबंधित है।';
$CIDRAM['lang']['Short_BadIP'] = 'अमान्य आईपी';
$CIDRAM['lang']['Short_Banned'] = 'प्रतिबंधित';
$CIDRAM['lang']['Short_Bogon'] = 'बोगोन आईपी';
$CIDRAM['lang']['Short_Cloud'] = 'क्लाउड सेवा';
$CIDRAM['lang']['Short_Generic'] = 'सामान्य';
$CIDRAM['lang']['Short_Legal'] = 'कानूनी';
$CIDRAM['lang']['Short_Malware'] = 'मैलवेयर';
$CIDRAM['lang']['Short_Proxy'] = 'प्रॉक्सी';
$CIDRAM['lang']['Short_Spam'] = 'स्पैम जोखिम';
$CIDRAM['lang']['Support_Email'] = 'यदि आपको लगता है कि यह त्रुटि है, या सहायता प्राप्त करने के लिए, इस वेबसाइट के वेबमास्टर को ईमेल समर्थन टिकट भेजने के लिए {ClickHereLink} (कृपया प्रस्तावना या विषय रेखा को न बदलें)।';
$CIDRAM['lang']['Support_Email_2'] = 'यदि आपको लगता है कि यह त्रुटि है, सहायता प्राप्त करने के लिए {EmailAddr} पर एक ईमेल भेजें।';
$CIDRAM['lang']['click_here'] = 'यहां क्लिक करें';
$CIDRAM['lang']['denied'] = 'पहुंच अस्वीकृत!';
$CIDRAM['lang']['fake_ua'] = 'नकली {ua}';
$CIDRAM['lang']['field_datetime'] = 'दिनांक/समय: ';
$CIDRAM['lang']['field_hostname'] = 'होस्ट का नाम: ';
$CIDRAM['lang']['field_id'] = 'आईडी: ';
$CIDRAM['lang']['field_ipaddr'] = 'आईपी पता: ';
$CIDRAM['lang']['field_ipaddr_resolved'] = 'आईपी पता (संकल्प लिया): ';
$CIDRAM['lang']['field_query'] = 'क्वेरी: ';
$CIDRAM['lang']['field_rURI'] = 'पुनर्निर्माण यूआरएल: ';
$CIDRAM['lang']['field_reCAPTCHA_state'] = 'रीकैपचा स्थिति: ';
$CIDRAM['lang']['field_referrer'] = 'रेफरर: ';
$CIDRAM['lang']['field_scriptversion'] = 'स्क्रिप्ट संस्करण: ';
$CIDRAM['lang']['field_sigcount'] = 'हस्ताक्षर की संख्या: ';
$CIDRAM['lang']['field_sigref'] = 'हस्ताक्षर संदर्भ: ';
$CIDRAM['lang']['field_ua'] = 'उपभोक्ता अभिकर्ता: ';
$CIDRAM['lang']['field_whyreason'] = 'क्यों अवरुद्ध: ';
$CIDRAM['lang']['generated_by'] = 'जेनरेटर:';
$CIDRAM['lang']['preamble'] = '-- प्रस्तावना का अंत। इस पंक्ति के बाद अपने प्रश्न या टिप्पणियां जोड़ें। --';
$CIDRAM['lang']['recaptcha_cookie_warning'] = 'ध्यान दें: CIDRAM जब कैप्चा को पूरा करता है तो याद रखने के लिए एक कुकी का उपयोग करता है। कैप्चा को पूरा करके, आप सहमति देते हैं कि आपके ब्राउज़र द्वारा एक कुकी संग्रहीत होगी।';
$CIDRAM['lang']['recaptcha_disabled'] = 'निष्क्रिय।';
$CIDRAM['lang']['recaptcha_enabled'] = 'सक्रिय।';
$CIDRAM['lang']['recaptcha_failed'] = 'अनुत्तीर्ण होना!';
$CIDRAM['lang']['recaptcha_message'] = 'इस पृष्ठ पर पहुंच हासिल करने के लिए, कृपया नीचे दिए गए कैप्चा को पूरा करें और सबमिट बटन दबाएं।';
$CIDRAM['lang']['recaptcha_message_invisible'] = 'अधिकतर उपयोगकर्ताओं के लिए, पृष्ठ को सामान्य पहुंच को रीफ्रेश और पुनर्स्थापित करना चाहिए। कुछ मामलों में, आपको CAPTCHA चुनौती को पूरा करना पड़ सकता है।';
$CIDRAM['lang']['recaptcha_passed'] = 'बीतने के!';
$CIDRAM['lang']['recaptcha_submit'] = 'जमा करें';
