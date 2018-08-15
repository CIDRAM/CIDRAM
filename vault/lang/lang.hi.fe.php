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
 * This file: Hindi language data for the front-end (last modified: 2018.08.13).
 */

/** Prevents execution from outside of CIDRAM. */
if (!defined('CIDRAM')) {
    die('[CIDRAM] This should not be accessed directly.');
}

foreach (['IPv4', 'IPv6'] as $CIDRAM['IPvX']) {
    $CIDRAM['Pre'] = 'डिफ़ॉल्ट ' . $CIDRAM['IPvX'] . ' हस्ताक्षर आमतौर पर मुख्य पैकेज के साथ शामिल थे। ';
    $CIDRAM['lang']['Extended Description: ' . $CIDRAM['IPvX']] = $CIDRAM['Pre'] . 'अवांछित क्लाउड सेवाओं और गैर-मानव समापन बिंदुओं को ब्लॉक करता है।';
    $CIDRAM['lang']['Extended Description: ' . $CIDRAM['IPvX'] . '-Bogons'] = $CIDRAM['Pre'] . 'ब्लॉक बोगोन/मार्टिन CIDR।';
    $CIDRAM['lang']['Extended Description: ' . $CIDRAM['IPvX'] . '-ISPs'] = $CIDRAM['Pre'] . 'खतरनाक और स्पैम आईएसपी ब्लॉक करता है।';
    $CIDRAM['lang']['Extended Description: ' . $CIDRAM['IPvX'] . '-Other'] = $CIDRAM['Pre'] . 'प्रॉक्सी, वीपीएन और अन्य विविध अवांछित सेवाओं के लिए सीआईडीआर ब्लॉक करता है।';
    $CIDRAM['Pre'] = $CIDRAM['IPvX'] . ' हस्ताक्षर फ़ाइल (%s)।';
    $CIDRAM['lang']['Name: ' . $CIDRAM['IPvX']] = sprintf($CIDRAM['Pre'], 'अवांछित क्लाउड सेवाओं और गैर-मानव समापन बिंदुएं');
    $CIDRAM['lang']['Name: ' . $CIDRAM['IPvX'] . '-Bogons'] = sprintf($CIDRAM['Pre'], 'बोगोन/मार्टिन CIDR');
    $CIDRAM['lang']['Name: ' . $CIDRAM['IPvX'] . '-ISPs'] = sprintf($CIDRAM['Pre'], 'खतरनाक और स्पैम आईएसपी');
    $CIDRAM['lang']['Name: ' . $CIDRAM['IPvX'] . '-Other'] = sprintf($CIDRAM['Pre'], 'प्रॉक्सी, वीपीएन और अन्य विविध अवांछित सेवाओं के लिए CIDR');
}
unset($CIDRAM['Pre'], $CIDRAM['IPvX']);

$CIDRAM['lang']['Extended Description: Bypasses'] = 'डिफ़ॉल्ट हस्ताक्षर बाईपास फ़ाइलों को सामान्यतः मुख्य पैकेज के साथ शामिल किया गया था।';
$CIDRAM['lang']['Extended Description: CIDRAM'] = 'मुख्य पैकेज (हस्ताक्षर, डॉक्यूमेंटेशन, और कॉन्फ़िगरेशन के बिना)।';
$CIDRAM['lang']['Extended Description: Chart.js'] = 'पाई चार्ट उत्पन्न करने के लिए सामने के अंत सक्षम करता है।<br /><a href="https://github.com/chartjs/Chart.js">Chart.js</a> <a href="https://opensource.org/licenses/MIT">MIT license</a> के माध्यम से उपलब्ध है।';
$CIDRAM['lang']['Extended Description: PHPMailer'] = 'ईमेल भेजने में शामिल किसी भी कार्यक्षमता का उपयोग करने के लिए आवश्यक है।<br /><a href="https://github.com/PHPMailer/PHPMailer">PHPMailer</a> <a href="https://github.com/PHPMailer/PHPMailer/blob/master/LICENSE">LGPLv2.1 </a> लाइसेंस के माध्यम से उपलब्ध है।';
$CIDRAM['lang']['Extended Description: module_badhosts.php'] = 'यह ब्लॉक होस्ट करता है, अक्सर स्पैमर, हैकर्स, और अन्य नेफ़ायस संस्थाओं द्वारा उपयोग किया जाता है।';
$CIDRAM['lang']['Extended Description: module_badhosts_isps.php'] = 'यह ब्लॉक ISP के स्वामित्व वाली होस्ट करता है, अक्सर स्पैमर, हैकर्स, और अन्य नेफ़ायस संस्थाओं द्वारा उपयोग किया जाता है।';
$CIDRAM['lang']['Extended Description: module_badtlds.php'] = 'यह ब्लॉक TLD के स्वामित्व वाली होस्ट करता है, अक्सर स्पैमर, हैकर्स, और अन्य नेफ़ायस संस्थाओं द्वारा उपयोग किया जाता है।';
$CIDRAM['lang']['Extended Description: module_botua.php'] = 'अवांछित बॉट और घृणित गतिविधि से जुड़े उपयोगकर्ता एजेंट ब्लॉक।';
$CIDRAM['lang']['Extended Description: module_cookies.php'] = 'खतरनाक कुकीज़ के विरुद्ध कुछ सीमित सुरक्षा प्रदान करता है।';
$CIDRAM['lang']['Extended Description: module_extras.php'] = 'आम तौर पर अनुरोधों में उपयोग किए गए विभिन्न हमले वाले वैक्टर के खिलाफ कुछ सीमित सुरक्षा प्रदान करता है।';
$CIDRAM['lang']['Extended Description: module_sfs.php'] = 'एसएफएस द्वारा सूचीबद्ध IP पते से पंजीकरण और लॉगिन पृष्ठ की सुरक्षा करता है।';
$CIDRAM['lang']['Name: Bypasses'] = 'डिफ़ॉल्ट हस्ताक्षर बायपास।';
$CIDRAM['lang']['Name: compat_bunnycdn.php'] = 'BunnyCDN संगतता मॉड्यूल';
$CIDRAM['lang']['Name: module_badhosts.php'] = 'खराब होस्ट ब्लॉकर मॉड्यूल';
$CIDRAM['lang']['Name: module_badhosts_isps.php'] = 'खराब होस्ट ब्लॉकर मॉड्यूल (इंटरनेट सेवा प्रदाताओं)';
$CIDRAM['lang']['Name: module_badtlds.php'] = 'खराब TLD ब्लॉकर मॉड्यूल';
$CIDRAM['lang']['Name: module_baidublocker.php'] = 'Baidu अवरोधक मॉड्यूल';
$CIDRAM['lang']['Name: module_botua.php'] = 'वैकल्पिक उपयोगकर्ता एजेंट मॉड्यूल';
$CIDRAM['lang']['Name: module_cookies.php'] = 'वैकल्पिक कुकी स्कैनर मॉड्यूल';
$CIDRAM['lang']['Name: module_extras.php'] = 'वैकल्पिक सुरक्षा एक्स्ट्रा मॉड्यूल';
$CIDRAM['lang']['Name: module_sfs.php'] = 'Stop Forum Spam मॉड्यूल';
$CIDRAM['lang']['Name: module_ua.php'] = 'खाली UA अवरोधक मॉड्यूल';
$CIDRAM['lang']['Name: module_yandexblocker.php'] = 'Yandex अवरोधक मॉड्यूल';
$CIDRAM['lang']['bNav_home_logout'] = '<a href="?">होमपेज</a> | <a href="?cidram-page=logout">लोग आउट</a>';
$CIDRAM['lang']['bNav_logout'] = '<a href="?cidram-page=logout">लोग आउट</a>';
$CIDRAM['lang']['config_PHPMailer'] = 'इन कॉन्फ़िगरेशन निर्देशों को कार्यक्षमता के लिए आवश्यक हो सकता है जिसमें ईमेल भेजना शामिल है। कृपया अधिक जानकारी और अनुशंसित मानों के लिए प्रलेखन देखें।';
$CIDRAM['lang']['config_experimental'] = 'अस्थिर/प्रायोगिक!';
$CIDRAM['lang']['config_general_FrontEndLog'] = 'सामने के अंत में प्रवेश प्रयासों प्रवेश के लिए दायर। एक फाइल नाम निर्दिष्ट करें, या निष्क्रिय करने के लिए खाली छोड़।';
$CIDRAM['lang']['config_general_allow_gethostbyaddr_lookup'] = 'जब UDP अनुपलब्ध हो तो gethostbyaddr लुकअप की अनुमति दें? True(सच्चे) = हाँ [डिफ़ॉल्ट]; False(झूठी) = नहीं।';
$CIDRAM['lang']['config_general_ban_override'] = 'विलोपन "forbid_on_block" कब "infraction_limit" पार कर रहा है? अधिभावी कब: विचाराधीन अनुरोध एक खाली पेज वापस (टेम्पलेट फाइलों का उपयोग नहीं कर रहे हैं)। 200 = ओवरराइड न करें [डिफ़ॉल्ट]। अन्य मान "forbid_on_block" के लिए उपलब्ध मानों के समान हैं।';
$CIDRAM['lang']['config_general_default_algo'] = 'परिभाषित करता है कि भविष्य के सभी पासवर्ड और सत्रों के लिए किस एल्गोरिथम का उपयोग करना है। विकल्प: PASSWORD_DEFAULT (डिफ़ॉल्ट), PASSWORD_BCRYPT, PASSWORD_ARGON2I (PHP &gt;= 7.2.0 की आवश्यकता है)।';
$CIDRAM['lang']['config_general_default_dns'] = 'होस्ट नाम लुकअप के लिए उपयोग करने के लिए DNS सर्वर की अल्पविराम सीमांकित सूची। डिफ़ॉल्ट = "8.8.8.8,8.8.4.4" (Google DNS)। चेतावनी: जब तक कि आप को पता है तुम क्या कर रहे हो उसे बदल नहीं!';
$CIDRAM['lang']['config_general_disable_cli'] = 'अक्षम CLI मोड? CLI मोड डिफ़ॉल्ट रूप से सक्षम होता है, लेकिन कभी-कभी कुछ परीक्षण टूल (जैसे कि PHPUnit, उदाहरण के लिए) और अन्य CLI-आधारित अनुप्रयोगों में हस्तक्षेप कर सकता है। यदि आपको CLI मोड को अक्षम करने की आवश्यकता नहीं है, तो आपको इस निर्देश को अनदेखा करना चाहिए। False(झूठी) = CLI मोड सक्षम करें [डिफ़ॉल्ट]; True(सच्चे) = CLI मोड को अक्षम करें।';
$CIDRAM['lang']['config_general_disable_frontend'] = 'सामने के अंत पहुँच अक्षम? सामने के अंत पहुंच CIDRAM को और अधिक प्रबंधनीय बना सकता है, लेकिन यह भी एक संभावित सुरक्षा जोखिम भी हो सकता है। जब भी संभव हो, बैक-एंड के माध्यम से CIDRAM का प्रबंधन करने की सिफारिश की जाती है, लेकिन सुविधा के लिए सामने के अंत पहुँच भी प्रदान किया जाता है। इसे तब तक अक्षम रखें जब तक आपको इसकी आवश्यकता न हो। False(झूठी) = सामने के अंत पहुँच सक्षम करें; True(सच्चे) = सामने के अंत पहुँच अक्षम करें [डिफ़ॉल्ट]।';
$CIDRAM['lang']['config_general_disable_webfonts'] = 'निष्क्रिय वेब फोंट? True(सच्चे) = हाँ [डिफ़ॉल्ट]; False(झूठी) = नहीं।';
$CIDRAM['lang']['config_general_emailaddr'] = 'अगर आप चाहते हैं, तो आप उपयोगकर्ताओं को जब उन्हें अवरुद्ध कर रहे हैं तो देने के लिए ईमेल पते की आपूर्ति कर सकते हैं। वे इसका इस्तेमाल आपसे संपर्क करने के लिए कर सकते हैं यदि वे गलती से अवरुद्ध कर रहे हैं। चेतावनी: आप जो भी ईमेल पते पर आपूर्ति करते हैं, वे निश्चित रूप से स्पैम्बट्स और स्क्रेपर द्वारा प्राप्त किए जाएंगे। इस वजह से, यह दृढ़ता से अनुशंसा की जाती है कि आप एक ईमेल पता चुनते हैं जो डिस्पोजेबल या महत्वहीन है (अर्थात।, अपने व्यक्तिगत या व्यावसायिक ईमेल पते का उपयोग न करें)।';
$CIDRAM['lang']['config_general_emailaddr_display_style'] = 'उपयोगकर्ताओं को प्रस्तुत करने के लिए आप ईमेल पते को कैसे पसंद करेंगे?';
$CIDRAM['lang']['config_general_forbid_on_block'] = 'अनुरोधों को अवरुद्ध करते समय CIDRAM को कौन सी HTTP स्थिति संदेश भेजना चाहिए? (अधिक जानकारी के लिए डॉक्यूमेंटेशन का संदर्भ लें)।';
$CIDRAM['lang']['config_general_force_hostname_lookup'] = 'होस्ट नाम लुकअप के लिए मजबूर? True(सच्चे) = हाँ; False(झूठी) = नहीं [डिफ़ॉल्ट]। होस्ट नाम लुकअप सामान्य रूप से "आवश्यकतानुसार" आधार पर किया जाता है, लेकिन इसे सभी अनुरोधों के लिए मजबूर किया जा सकता है। ऐसा करना लॉगफाइल में अधिक विस्तृत जानकारी प्रदान करने के साधन के रूप में उपयोगी हो सकता है, लेकिन प्रदर्शन पर इसका थोड़ा नकारात्मक प्रभाव हो सकता है।';
$CIDRAM['lang']['config_general_hide_version'] = 'लॉग्स और पेज आउटपुट से संस्करण जानकारी छुपाएं? True(सच्चे) = हाँ; False(झूठी) = नहीं [डिफ़ॉल्ट]।';
$CIDRAM['lang']['config_general_ipaddr'] = 'कहां अनुरोध जोड़ने के IP पते खोजने के लिए? (जैसा Cloudflare के रूप में सेवाओं और पसंद के लिए उपयोगी)। डिफ़ॉल्ट = REMOTE_ADDR। चेतावनी: जब तक कि आप को पता है तुम क्या कर रहे हो उसे बदल नहीं!';
$CIDRAM['lang']['config_general_lang'] = 'CIDRAM लिए डिफ़ॉल्ट भाषा निर्दिष्ट।';
$CIDRAM['lang']['config_general_log_banned_ips'] = 'प्रवेश फाइलें में प्रतिबंधित IP से अवरुद्ध अनुरोध जोड़ें? True(सच्चे) = हाँ [डिफ़ॉल्ट]; False(झूठी) = नहीं।';
$CIDRAM['lang']['config_general_log_rotation_action'] = 'लॉग रोटेशन लॉगफाइल की संख्या को सीमित करता है जो कि किसी भी समय मौजूद होना चाहिए। जब नई लॉगफाइल बनाई जाती हैं, यदि लॉगफाइल की कुल संख्या निर्दिष्ट सीमा से अधिक है, तो निर्दिष्ट कार्रवाई की जाएगी। आप यहां वांछित कार्रवाई निर्दिष्ट कर सकते हैं। Delete = सबसे पुरानी लॉगफाइल हटाएं, जब तक कि सीमा अब पार न हो जाए। Archive = सबसे पहले संग्रहित करें, और तब सबसे पुरानी लॉगफाइल हटाएं, जब तक कि सीमा अब पार न हो जाए।';
$CIDRAM['lang']['config_general_log_rotation_limit'] = 'लॉग रोटेशन लॉगफाइल की संख्या को सीमित करता है जो कि किसी भी समय मौजूद होना चाहिए। जब नई लॉगफाइल बनाई जाती हैं, यदि लॉगफाइल की कुल संख्या निर्दिष्ट सीमा से अधिक है, तो निर्दिष्ट कार्रवाई की जाएगी। आप यहां वांछित सीमा निर्दिष्ट कर सकते हैं। 0 का मान लॉग रोटेशन अक्षम कर देगा।';
$CIDRAM['lang']['config_general_logfile'] = 'सभी अवरुद्ध पहुँच प्रयासों को लॉग इन करने के लिए मानव पठनीय फाइल। एक फाइल नाम निर्दिष्ट करें, या निष्क्रिय करने के लिए खाली छोड़।';
$CIDRAM['lang']['config_general_logfileApache'] = 'सभी अवरुद्ध पहुँच प्रयासों को लॉग इन करने के लिए अपाचे शैली फाइल। एक फाइल नाम निर्दिष्ट करें, या निष्क्रिय करने के लिए खाली छोड़।';
$CIDRAM['lang']['config_general_logfileSerialized'] = 'सभी अवरुद्ध पहुँच प्रयासों को लॉग इन करने के लिए धारावाहिक फाइल। एक फाइल नाम निर्दिष्ट करें, या निष्क्रिय करने के लिए खाली छोड़।';
$CIDRAM['lang']['config_general_maintenance_mode'] = 'रखरखाव मोड सक्षम करें? True(सच्चे) = हाँ; False(झूठी) = नहीं [डिफ़ॉल्ट]। सामने के अंत के अलावा अन्य सभी को अक्षम करता है। आपके CMS, फ़्रेमवर्क, आदि को अपडेट करने के लिए कभी-कभी उपयोगी।';
$CIDRAM['lang']['config_general_max_login_attempts'] = 'लॉगिन प्रयासों की अधिकतम संख्या।';
$CIDRAM['lang']['config_general_numbers'] = 'आप संख्याओं को प्रदर्शित करने के लिए कैसे पसंद करते हैं? उदाहरण का चयन करें जो आपके लिए सबसे सही लग रहा है।';
$CIDRAM['lang']['config_general_protect_frontend'] = 'निर्धारित करता है जो आमतौर पर CIDRAM द्वारा प्रदत्त आरक्षण सामने के छोर पर लागू किया जाना चाहिए कि क्या। True(सच्चे) = हाँ [डिफ़ॉल्ट]; False(झूठी) = नहीं।';
$CIDRAM['lang']['config_general_search_engine_verification'] = 'खोज इंजन द्वारा अनुरोध की पुष्टि करने की कोशिश? खोज इंजन की पुष्टि करने के लिए कि वह उल्लंघन सीमा (वेबसाइट खोज इंजन पर प्रतिबंध आमतौर पर आप खोज इंजन रैंकिंग, SEO, आदि पर प्रतिकूल असर होगा) पार का एक परिणाम के रूप प्रतिबंध नहीं किया जाएगा सुनिश्चित करता है। पुष्टि जब खोज इंजन सामान्य प्रति के रूप में अवरुद्ध किया जा सकता है, लेकिन प्रतिबंध नहीं लगाया जाएगा। की पुष्टि नहीं की है, तो यह उनके लिए उल्लंघन सीमा पार करने के नतीजतन प्रतिबंधित होने के लिए संभव है। इसके अलावा, खोज इंजन पुष्टि नकली खोज इंजन अनुरोध के खिलाफ और (ऐसे अनुरोधों खोज इंजन प्रमाणीकरण सक्षम है जब अवरुद्ध कर दिया जाएगा) सर्च इंजन के रूप में विष संभावित दुर्भावनापूर्ण आधारित संस्थाओं के खिलाफ सुरक्षा प्रदान करता है। True(सच्चे) = खोज इंजन पुष्टि सक्रिय [डिफ़ॉल्ट]; False(झूठी) = निष्क्रिय खोज इंजन पुष्टि।';
$CIDRAM['lang']['config_general_silent_mode'] = 'चुपचाप CIDRAM चाहिए "पहुँच नहीं हुई" पेज प्रदर्शित ब्लॉक पहुँच प्रयासों को दिशानिर्देश देने के बजाय? हाँ तो, अवरुद्ध पहुँच प्रयासों को दिशानिर्देश देने के स्थान विवरण। कोई तो चर खाली छोड़।';
$CIDRAM['lang']['config_general_social_media_verification'] = 'सोशल मीडिया अनुरोधों को सत्यापित करने का प्रयास? सोशल मीडिया सत्यापन नकली सोशल मीडिया अनुरोधों के खिलाफ सुरक्षा प्रदान करता है (ऐसे अनुरोध अवरुद्ध किए जाएंगे)। True(सच्चे) = सोशल मीडिया सत्यापन सक्षम करें [डिफ़ॉल्ट]; False(झूठी) = सोशल मीडिया सत्यापन अक्षम करें।';
$CIDRAM['lang']['config_general_statistics'] = 'CIDRAM उपयोग के सांख्यिकी ट्रैक करें? True(सच्चे) = हाँ; False(झूठी) = नहीं [डिफ़ॉल्ट]।';
$CIDRAM['lang']['config_general_timeFormat'] = 'CIDRAM द्वारा इस्तेमाल की तिथियाँ प्रपत्र। अतिरिक्त विकल्प आवेदन शामिल किया जा सकता है।';
$CIDRAM['lang']['config_general_timeOffset'] = 'समय क्षेत्र मिनट में ऑफसेट।';
$CIDRAM['lang']['config_general_timezone'] = 'अपने समय क्षेत्र।';
$CIDRAM['lang']['config_general_truncate'] = 'वह एक विशेष आकार तक पहुँचने में जब साफ प्रवेश फाइलें? मूल्य में B/KB/MB/GB/TB अधिकतम आकार है। जब 0KB, वे अनिश्चित काल तक बढ़ सकता है (डिफ़ॉल्ट)। नोट: एकल फाइल पर लागू होता है! फाइलें सामूहिक विचार नहीं कर रहे हैं।';
$CIDRAM['lang']['config_legal_omit_hostname'] = 'लॉग से होस्टनाम छोड़ दें? True(सच्चे) = हाँ; False(झूठी) = नहीं [डिफ़ॉल्ट]।';
$CIDRAM['lang']['config_legal_omit_ip'] = 'लॉग से IP पते छोड़ दें? True(सच्चे) = हाँ; False(झूठी) = नहीं [डिफ़ॉल्ट]। नोट: "omit_ip" "true" होने पर "pseudonymise_ip_addresses" अनावश्यक हो जाता है।';
$CIDRAM['lang']['config_legal_omit_ua'] = 'लॉग से उपयोगकर्ता एजेंटों को छोड़ दें? True(सच्चे) = हाँ; False(झूठी) = नहीं [डिफ़ॉल्ट]।';
$CIDRAM['lang']['config_legal_privacy_policy'] = 'किसी भी जेनरेट किए गए पृष्ठों के पाद लेख में प्रदर्शित होने वाली प्रासंगिक गोपनीयता नीति का पता। एक URL निर्दिष्ट करें, या अक्षम करने के लिए खाली छोड़ दें।';
$CIDRAM['lang']['config_legal_pseudonymise_ip_addresses'] = 'लॉग फाइल लिखते समय pseudonymize IP पते? True(सच्चे) = हाँ; False(झूठी) = नहीं [डिफ़ॉल्ट]।';
$CIDRAM['lang']['config_recaptcha_api'] = 'किस API का उपयोग करना है? V2 या Invisible?';
$CIDRAM['lang']['config_recaptcha_expiry'] = 'घंटों की संख्या reCAPTCHA की घटनाओं को याद करने के लिए।';
$CIDRAM['lang']['config_recaptcha_lockip'] = 'कोई पी एस के लिए हीती लॉक?';
$CIDRAM['lang']['config_recaptcha_lockuser'] = 'उपभोक्ताओं के लिए हीती लॉक?';
$CIDRAM['lang']['config_recaptcha_logfile'] = 'सभी reCAPTCHA के प्रयासों प्रवेश? यदि हाँ, लॉग फाइल पर के लिए उपयोग करने के लिए नाम निर्दिष्ट। कोई तो चर खाली छोड़ दें।';
$CIDRAM['lang']['config_recaptcha_secret'] = '"secret key" मान reCAPTCHA डैशबोर्ड में पाया जा सकता है।';
$CIDRAM['lang']['config_recaptcha_signature_limit'] = 'जब एक reCAPTCHA की पेशकश की जाती है तो अधिकतम हस्ताक्षर किए जाने की अनुमति दी जाती है। डिफ़ॉल्ट = 1। अगर यह संख्या किसी विशेष अनुरोध के लिए पार कर दी गई है, तो reCAPTCHA पेशकश नहीं किया जाएगा।';
$CIDRAM['lang']['config_recaptcha_sitekey'] = '"site key" मान reCAPTCHA डैशबोर्ड में पाया जा सकता है।';
$CIDRAM['lang']['config_recaptcha_usemode'] = 'CIDRAM reCAPTCHA का उपयोग करना चाहिए कैसे विवरण (दस्तावेज़ देखें)।';
$CIDRAM['lang']['config_signatures_block_bogons'] = 'अवरुद्ध bogon/martian की CIDRs? आप स्थानीय होस्ट, या अपने LAN अपने स्थानीय नेटवर्क के भीतर से अपनी वेबसाइट पर कनेक्शन, उम्मीद है, यह नुस्खा के गलत पर सेट किया जाना चाहिए। आप उनमें ऐसे कनेक्शनों की उम्मीद नहीं है, तो यह नुस्खा सही पर सेट किया जाना चाहिए।';
$CIDRAM['lang']['config_signatures_block_cloud'] = 'अवरुद्ध CIDRs webhosting के/बादल सेवाओं से संबंधित के रूप में पहचान? आप अपनी वेबसाइट से एक API सेवा संचालित या आप अन्य वेब साइटों को अपनी वेबसाइट से कनेक्ट करने की उम्मीद है, तो इस झूठ के लिए निर्धारित किया जाना चाहिए। आप ऐसा नहीं करते, तो, यह नुस्खा सही पर सेट किया जाना चाहिए।';
$CIDRAM['lang']['config_signatures_block_generic'] = 'अवरुद्ध CIDRs आमतौर पर काली सूची सिफारिश? यह अन्य अधिक विशिष्ट हस्ताक्षर प्रकार से किसी का हिस्सा होने के रूप में चिह्नित नहीं है कि किसी भी हस्ताक्षर के कवर।';
$CIDRAM['lang']['config_signatures_block_legal'] = 'कानूनी दायित्वों के जवाब में CIDRs ब्लॉक करें? इस निर्देश का सामान्य रूप से कोई प्रभाव नहीं होना चाहिए, क्योंकि CIDRAM किसी भी CIDRs को डिफ़ॉल्ट रूप से "कानूनी दायित्वों" से संबद्ध नहीं करता है, लेकिन यह कानूनी कारणों से मौजूद किसी भी कस्टम हस्ताक्षर फ़ाइलों या मॉड्यूल के लिए अतिरिक्त नियंत्रण उपाय के रूप में वैसे भी मौजूद है।';
$CIDRAM['lang']['config_signatures_block_malware'] = 'मैलवेयर से जुड़े ब्लॉक IP? इसमें C&C सर्वर, संक्रमित मशीनें, मैलवेयर वितरण आदि में शामिल मशीनें शामिल हैं।';
$CIDRAM['lang']['config_signatures_block_proxies'] = 'अवरुद्ध CIDRs प्रॉक्सी सेवाओं या VPN से संबंधित के रूप में पहचान? अपने उपयोगकर्ताओं प्रॉक्सी सेवाओं या VPN आप साइट तक पहुँचने के लिए सक्षम होने की आवश्यकता है, तो उसके गलत पर सेट किया जाना चाहिए। अन्यथा, अगर आपको प्रॉक्सी सेवाओं या VPN की आवश्यकता नहीं है, यह विकल्प सच सुधार सुरक्षा का एक साधन के रूप में सेट किया जाना चाहिए।';
$CIDRAM['lang']['config_signatures_block_spam'] = 'अवरुद्ध CIDRs स्पैम के लिए उच्च जोखिम के रूप में पहचाना? ऐसा करने जब आप समस्याओं का सामना होता है जब तक आम तौर पर, यह हमेशा सच के लिए निर्धारित किया जाना चाहिए।';
$CIDRAM['lang']['config_signatures_default_tracktime'] = 'मॉड्यूल द्वारा प्रतिबंधित IP ट्रैक करने सेकंड कितने। डिफ़ॉल्ट = 604800 (1 सप्ताह)।';
$CIDRAM['lang']['config_signatures_infraction_limit'] = 'मिथकों की अधिकतम संख्या एक IP इससे पहले किया जाता है IP ट्रैकिंग से प्रतिबंध लागू करने की अनुमति है। डिफ़ॉल्ट = 10।';
$CIDRAM['lang']['config_signatures_ipv4'] = 'IPv4 हस्ताक्षर फाइलों की सूची जिसमें CIDRAM को इस्तेमाल करना चाहिए, कॉमा द्वारा अलग।';
$CIDRAM['lang']['config_signatures_ipv6'] = 'IPv6 हस्ताक्षर फाइलों की सूची जिसमें CIDRAM को इस्तेमाल करना चाहिए, कॉमा द्वारा अलग।';
$CIDRAM['lang']['config_signatures_modules'] = 'IPv4/IPv6 हस्ताक्षर की जाँच के बाद लोड करने के लिए मॉड्यूल फाइलें, कॉमा द्वारा अलग।';
$CIDRAM['lang']['config_signatures_track_mode'] = 'मिथकों गिनती कब किया जाना चाहिए? False(झूठी) = IP मॉड्यूल द्वारा अवरुद्ध कर रहे हैं जब। सच्चा = IP किसी भी कारण से अवरुद्ध कर रहे हैं जब।';
$CIDRAM['lang']['config_template_data_Magnification'] = 'फ़ॉन्ट बढ़ाई। डिफ़ॉल्ट = 1।';
$CIDRAM['lang']['config_template_data_css_url'] = 'कस्टम थीम के लिए CSS फाइल URL।';
$CIDRAM['lang']['config_template_data_theme'] = 'CIDRAM के इस्तेमाल के लिए डिफ़ॉल्ट थीम।';
$CIDRAM['lang']['confirm_action'] = 'क्या आप वाकई "%s" चाहते हैं?';
$CIDRAM['lang']['field_2fa'] = '2FA कोड';
$CIDRAM['lang']['field_activate'] = 'सक्रिय करें';
$CIDRAM['lang']['field_banned'] = 'प्रतिबंधित';
$CIDRAM['lang']['field_blocked'] = 'अवरुद्ध';
$CIDRAM['lang']['field_clear'] = 'साफ करो';
$CIDRAM['lang']['field_clear_all'] = 'सभी साफ करें';
$CIDRAM['lang']['field_clickable_link'] = 'क्लिक करने योग्य लिंक';
$CIDRAM['lang']['field_component'] = 'घटक';
$CIDRAM['lang']['field_confirm'] = 'की पुष्टि करें';
$CIDRAM['lang']['field_create_new_account'] = 'नया खाता बनाएँ';
$CIDRAM['lang']['field_deactivate'] = 'निष्क्रिय करें';
$CIDRAM['lang']['field_delete_account'] = 'खाता हटाएं';
$CIDRAM['lang']['field_delete_file'] = 'हटाएं';
$CIDRAM['lang']['field_download_file'] = 'डाउनलोड';
$CIDRAM['lang']['field_edit_file'] = 'संपादित करें';
$CIDRAM['lang']['field_expiry'] = 'समाप्ति';
$CIDRAM['lang']['field_false'] = 'False (असत्य)';
$CIDRAM['lang']['field_file'] = 'फाइल';
$CIDRAM['lang']['field_filename'] = 'फाइल का नाम: ';
$CIDRAM['lang']['field_filetype_directory'] = 'निर्देशिका';
$CIDRAM['lang']['field_filetype_info'] = '{EXT} फाइल';
$CIDRAM['lang']['field_filetype_unknown'] = 'अनजान';
$CIDRAM['lang']['field_infractions'] = 'उल्लंघन';
$CIDRAM['lang']['field_install'] = 'इंस्टॉल करें';
$CIDRAM['lang']['field_ip_address'] = 'IP पता';
$CIDRAM['lang']['field_latest_version'] = 'नवीनतम संस्करण';
$CIDRAM['lang']['field_log_in'] = 'लॉग इन करें';
$CIDRAM['lang']['field_new_name'] = 'नया नाम:';
$CIDRAM['lang']['field_nonclickable_text'] = 'बिना क्लिक करने योग्य पाठ';
$CIDRAM['lang']['field_ok'] = 'ओके';
$CIDRAM['lang']['field_options'] = 'विकल्प';
$CIDRAM['lang']['field_password'] = 'पासवर्ड';
$CIDRAM['lang']['field_permissions'] = 'अनुमतियां';
$CIDRAM['lang']['field_range'] = 'रेंज (प्रथम – अंतिम)';
$CIDRAM['lang']['field_rename_file'] = 'नाम बदलें';
$CIDRAM['lang']['field_reset'] = 'रीसेट';
$CIDRAM['lang']['field_set_new_password'] = 'नया पासवर्ड बनाएं';
$CIDRAM['lang']['field_size'] = 'कुल आकार: ';
$CIDRAM['lang']['field_size_GB'] = 'GB';
$CIDRAM['lang']['field_size_KB'] = 'KB';
$CIDRAM['lang']['field_size_MB'] = 'MB';
$CIDRAM['lang']['field_size_TB'] = 'TB';
$CIDRAM['lang']['field_size_bytes'] = ['बाइट', 'बाइट्स'];
$CIDRAM['lang']['field_status'] = 'अवस्था';
$CIDRAM['lang']['field_system_timezone'] = 'सिस्टम डिफ़ॉल्ट समयक्षेत्र का उपयोग करें।';
$CIDRAM['lang']['field_tracking'] = 'ट्रैकिंग';
$CIDRAM['lang']['field_true'] = 'True (सच)';
$CIDRAM['lang']['field_uninstall'] = 'अनइंस्टॉल करें';
$CIDRAM['lang']['field_update'] = 'अपडेट करो';
$CIDRAM['lang']['field_update_all'] = 'सब कुछ अपडेट करें';
$CIDRAM['lang']['field_upload_file'] = 'नई फाइल अपलोड करें';
$CIDRAM['lang']['field_username'] = 'उपयोगकर्ता नाम';
$CIDRAM['lang']['field_verify'] = 'सत्यापित करें';
$CIDRAM['lang']['field_verify_all'] = 'सभी को सत्यापित करें';
$CIDRAM['lang']['field_your_version'] = 'आपका संस्करण';
$CIDRAM['lang']['header_login'] = 'जारी रखने के लिए कृपया लॉग इन करें।';
$CIDRAM['lang']['label_active_config_file'] = 'सक्रिय कॉन्फ़िगरेशन फाइल: ';
$CIDRAM['lang']['label_actual'] = 'वास्तविक';
$CIDRAM['lang']['label_backup_location'] = 'रिपोजिटरी बैकअप स्थानों (आपात स्थिति के मामले में, या यदि सब कुछ विफल रहता है):';
$CIDRAM['lang']['label_banned'] = 'अनुरोध प्रतिबंधित';
$CIDRAM['lang']['label_blocked'] = 'अनुरोध अवरुद्ध';
$CIDRAM['lang']['label_branch'] = 'शाखा नवीनतम स्थिर:';
$CIDRAM['lang']['label_check_modules'] = 'भी मॉड्यूल के खिलाफ परीक्षण।';
$CIDRAM['lang']['label_cidram'] = 'CIDRAM संस्करण का उपयोग:';
$CIDRAM['lang']['label_clientinfo'] = 'क्लाइंट की सूचना:';
$CIDRAM['lang']['label_displaying'] = ['<span class="txtRd">%s</span> प्रविष्टि प्रदर्शित है।', '<span class="txtRd">%s</span> प्रविष्टियां प्रदर्शित है।'];
$CIDRAM['lang']['label_displaying_that_cite'] = ['<span class="txtRd">%1$s</span> प्रविष्टि प्रदर्शित है जो "%2$s" का हवाला देते हैं।', '<span class="txtRd">%1$s</span> प्रविष्टियां प्रदर्शित है जो "%2$s" का हवाला देते हैं।'];
$CIDRAM['lang']['label_expected'] = 'अपेक्षित होना';
$CIDRAM['lang']['label_expires'] = 'समय समाप्ति: ';
$CIDRAM['lang']['label_false_positive_risk'] = 'झूठी सकारात्मक जोखिम: ';
$CIDRAM['lang']['label_fmgr_cache_data'] = 'कैश डेटा और अस्थायी फ़ाइलें';
$CIDRAM['lang']['label_fmgr_disk_usage'] = 'CIDRAM डिस्क उपयोग: ';
$CIDRAM['lang']['label_fmgr_free_space'] = 'खाली डिस्क स्पेस: ';
$CIDRAM['lang']['label_fmgr_total_disk_usage'] = 'कुल डिस्क उपयोग: ';
$CIDRAM['lang']['label_fmgr_total_space'] = 'कुल डिस्क स्पेस: ';
$CIDRAM['lang']['label_fmgr_updates_metadata'] = 'घटक अद्यतन मेटाडेटा';
$CIDRAM['lang']['label_hide'] = 'छिपाना';
$CIDRAM['lang']['label_hide_hash_table'] = 'हैश टेबल छुपाएं';
$CIDRAM['lang']['label_never'] = 'कभी नहीँ';
$CIDRAM['lang']['label_os'] = 'ऑपरेटिंग सिस्टम का इस्तेमाल किया:';
$CIDRAM['lang']['label_other'] = 'अन्य';
$CIDRAM['lang']['label_other-ActiveIPv4'] = 'सक्रिय IPv4 हस्ताक्षर फ़ाइलें';
$CIDRAM['lang']['label_other-ActiveIPv6'] = 'सक्रिय IPv6 हस्ताक्षर फ़ाइलें';
$CIDRAM['lang']['label_other-ActiveModules'] = 'सक्रिय मॉड्यूल';
$CIDRAM['lang']['label_other-Since'] = 'आरंभ करने की तिथि';
$CIDRAM['lang']['label_php'] = 'PHP संस्करण का इस्तेमाल किया:';
$CIDRAM['lang']['label_reCAPTCHA'] = 'reCAPTCHA प्रयास';
$CIDRAM['lang']['label_results'] = 'परिणाम (%s में प्रवेश किया – %s अस्वीकृत – %s स्वीकृत – %s मर्ज किए गए – %s आउटपुट किया):';
$CIDRAM['lang']['label_sapi'] = 'SAPI का इस्तेमाल किया:';
$CIDRAM['lang']['label_show'] = 'दिखाना';
$CIDRAM['lang']['label_show_hash_table'] = 'हैश टेबल दिखाएं';
$CIDRAM['lang']['label_signature_type'] = 'हस्ताक्षर प्रकार:';
$CIDRAM['lang']['label_stable'] = 'नवीनतम स्थिर:';
$CIDRAM['lang']['label_sysinfo'] = 'प्रणाली की जानकारी:';
$CIDRAM['lang']['label_tests'] = 'परीक्षण:';
$CIDRAM['lang']['label_total'] = 'कुल';
$CIDRAM['lang']['label_unstable'] = 'नवीनतम अस्थिर:';
$CIDRAM['lang']['label_used_with'] = 'साथ उपयोग करना: ';
$CIDRAM['lang']['label_your_ip'] = 'तुम्हारी IP:';
$CIDRAM['lang']['label_your_ua'] = 'तुम्हारी UA:';
$CIDRAM['lang']['link_accounts'] = 'खातों';
$CIDRAM['lang']['link_cache_data'] = 'कैश डेटा';
$CIDRAM['lang']['link_cidr_calc'] = 'CIDR कैलक्यूलेटर';
$CIDRAM['lang']['link_config'] = 'कॉन्फ़िगरेशन';
$CIDRAM['lang']['link_documentation'] = 'डॉक्यूमेंटेशन';
$CIDRAM['lang']['link_file_manager'] = 'फाइल प्रबंधक';
$CIDRAM['lang']['link_home'] = 'होमपेज';
$CIDRAM['lang']['link_ip_aggregator'] = 'IP एग्रीगेटर';
$CIDRAM['lang']['link_ip_test'] = 'IP परीक्षण';
$CIDRAM['lang']['link_ip_tracking'] = 'IP ट्रैकिंग';
$CIDRAM['lang']['link_logs'] = 'लॉग फाइलें';
$CIDRAM['lang']['link_range'] = 'रेंज टेबल्स';
$CIDRAM['lang']['link_sections_list'] = 'अनुभाग सूची';
$CIDRAM['lang']['link_statistics'] = 'सांख्यिकी';
$CIDRAM['lang']['link_textmode'] = 'पाठ स्वरूपण: <a href="%1$sfalse%2$s">बुनियादी</a> – <a href="%1$strue%2$s">स्वरूपित</a> – <a href="%1$stally%2$s">मिलान</a>';
$CIDRAM['lang']['link_updates'] = 'अपडेट';
$CIDRAM['lang']['logs_logfile_doesnt_exist'] = 'चयनित लॉग फाइल मौजूद नहीं है!';
$CIDRAM['lang']['logs_no_logfile_selected'] = 'कोई लॉग फाइल चयनित नहीं।';
$CIDRAM['lang']['logs_no_logfiles_available'] = 'कोई लॉग फाइल उपलब्ध नहीं।';
$CIDRAM['lang']['max_login_attempts_exceeded'] = 'लॉगिन प्रयासों की अधिकतम संख्या पार हो गई; पहुंच अस्वीकृत।';
$CIDRAM['lang']['previewer_days'] = 'दिन';
$CIDRAM['lang']['previewer_hours'] = 'घंटे';
$CIDRAM['lang']['previewer_minutes'] = 'मिनट';
$CIDRAM['lang']['previewer_months'] = 'महीने';
$CIDRAM['lang']['previewer_seconds'] = 'सेकंड';
$CIDRAM['lang']['previewer_weeks'] = 'सप्ताह';
$CIDRAM['lang']['previewer_years'] = 'वर्षों';
$CIDRAM['lang']['response_2fa_invalid'] = 'गलत 2FA कोड दर्ज किया गया। प्रमाणीकरण विफल होना।';
$CIDRAM['lang']['response_2fa_valid'] = 'सफलतापूर्वक प्रमाणीकृत।';
$CIDRAM['lang']['response_accounts_already_exists'] = 'उस उपयोगकर्ता नाम के साथ एक खाता पहले से मौजूद है!';
$CIDRAM['lang']['response_accounts_created'] = 'खाता सफलतापूर्वक बनाया गया!';
$CIDRAM['lang']['response_accounts_deleted'] = 'खाता सफलतापूर्वक हटाया गया!';
$CIDRAM['lang']['response_accounts_doesnt_exist'] = 'वह खाता मौजूद नहीं है।';
$CIDRAM['lang']['response_accounts_password_updated'] = 'पासवर्ड सफलतापूर्वक अपडेट किया गया!';
$CIDRAM['lang']['response_activated'] = 'सफलतापूर्वक सक्रियण।';
$CIDRAM['lang']['response_activation_failed'] = 'सक्रिय करने में विफल!';
$CIDRAM['lang']['response_checksum_error'] = 'कुछ त्रुटियों की जांच करें! फ़ाइल अस्वीकृत!';
$CIDRAM['lang']['response_component_successfully_installed'] = 'घटक सफलतापूर्वक इंस्टॉल किया गया।';
$CIDRAM['lang']['response_component_successfully_uninstalled'] = 'घटक सफलतापूर्वक अनइंस्टॉल किया गया।';
$CIDRAM['lang']['response_component_successfully_updated'] = 'घटक सफलतापूर्वक अपडेट किया गया';
$CIDRAM['lang']['response_component_uninstall_error'] = 'घटक को अनइंस्टॉल करते समय त्रुटि।';
$CIDRAM['lang']['response_configuration_updated'] = 'कॉन्फ़िगरेशन सफलतापूर्वक अपडेट किया गया।';
$CIDRAM['lang']['response_deactivated'] = 'सफलतापूर्वक निष्क्रिय।';
$CIDRAM['lang']['response_deactivation_failed'] = 'निष्क्रिय करने में विफल!';
$CIDRAM['lang']['response_delete_error'] = 'हटाने में विफल!';
$CIDRAM['lang']['response_directory_deleted'] = 'निर्देशिका को सफलतापूर्वक हटाया गया!';
$CIDRAM['lang']['response_directory_renamed'] = 'निर्देशिका को सफलतापूर्वक नाम दिया गया!';
$CIDRAM['lang']['response_error'] = 'त्रुटि';
$CIDRAM['lang']['response_failed_to_install'] = 'इनस्टॉल करने में विफल!';
$CIDRAM['lang']['response_failed_to_update'] = 'अपडेट करने में विफल!';
$CIDRAM['lang']['response_file_deleted'] = 'सफलतापूर्वक फाइल हटाया गया!';
$CIDRAM['lang']['response_file_edited'] = 'सफलतापूर्वक फाइल संशोधित किया गया!';
$CIDRAM['lang']['response_file_renamed'] = 'सफलतापूर्वक फाइल नाम दिया गया!';
$CIDRAM['lang']['response_file_uploaded'] = 'सफलतापूर्वक फाइल अपलोड की गई!';
$CIDRAM['lang']['response_login_invalid_password'] = 'लॉगिन विफलता! अवैध पासवर्ड!';
$CIDRAM['lang']['response_login_invalid_username'] = 'लॉगिन विफलता! उपयोगकर्ता नाम मौजूद नहीं!';
$CIDRAM['lang']['response_login_password_field_empty'] = 'पासवर्ड फ़ील्ड खाली है!';
$CIDRAM['lang']['response_login_username_field_empty'] = 'उपयोगकर्ता नाम फ़ील्ड खाली!';
$CIDRAM['lang']['response_login_wrong_endpoint'] = 'गलत समापन बिंदु!';
$CIDRAM['lang']['response_no'] = 'नहीं';
$CIDRAM['lang']['response_possible_problem_found'] = 'संभव समस्या मिली।';
$CIDRAM['lang']['response_rename_error'] = 'नाम बदलने में विफल!';
$CIDRAM['lang']['response_sanity_1'] = 'फ़ाइल में अप्रत्याशित सामग्री है! फ़ाइल अस्वीकृत!';
$CIDRAM['lang']['response_statistics_cleared'] = 'सांख्यिकी साफ है।';
$CIDRAM['lang']['response_tracking_cleared'] = 'ट्रैकिंग साफ है।';
$CIDRAM['lang']['response_updates_already_up_to_date'] = 'पहले से ही आधुनिक।';
$CIDRAM['lang']['response_updates_not_installed'] = 'घटक इंस्टॉल नहीं है!';
$CIDRAM['lang']['response_updates_not_installed_php'] = 'घटक इंस्टॉल नहीं है (PHP &gt;= {V} की आवश्यकता है)!';
$CIDRAM['lang']['response_updates_outdated'] = 'पदावनत!';
$CIDRAM['lang']['response_updates_outdated_manually'] = 'पदावनत (कृपया मैन्युअल अपडेट करें)!';
$CIDRAM['lang']['response_updates_outdated_php_version'] = 'पदावनत (PHP &gt;= {V} की आवश्यकता है)!';
$CIDRAM['lang']['response_updates_unable_to_determine'] = 'निर्धारित करने में असमर्थ।';
$CIDRAM['lang']['response_upload_error'] = 'अपलोड करने में विफल!';
$CIDRAM['lang']['response_verification_failed'] = 'सत्यापन असफल! घटक भ्रष्ट हो सकता है।';
$CIDRAM['lang']['response_verification_success'] = 'सत्यापन सफलता! कोई समस्या नहीं मिली।';
$CIDRAM['lang']['response_yes'] = 'हाँ';
$CIDRAM['lang']['state_async_deny'] = 'अनुमतियाँ अतुल्यकालिक अनुरोध करने के लिए पर्याप्त नहीं हैं। फिर से लॉग इन करने का प्रयास करें';
$CIDRAM['lang']['state_cache_is_empty'] = 'कैश खाली है।';
$CIDRAM['lang']['state_complete_access'] = 'पूरा पहुंच';
$CIDRAM['lang']['state_component_is_active'] = 'घटक सक्रिय है।';
$CIDRAM['lang']['state_component_is_inactive'] = 'घटक निष्क्रिय है।';
$CIDRAM['lang']['state_component_is_provisional'] = 'घटक अस्थायी है।';
$CIDRAM['lang']['state_default_password'] = 'चेतावनी: डिफ़ॉल्ट पासवर्ड का उपयोग करना!';
$CIDRAM['lang']['state_email_sent'] = 'ईमेल सफलतापूर्वक "%s" को भेजा गया।';
$CIDRAM['lang']['state_failed_missing'] = 'कार्य विफल रहा क्योंकि एक आवश्यक घटक अनुपलब्ध है।';
$CIDRAM['lang']['state_ignored'] = 'अवहेलना करना';
$CIDRAM['lang']['state_loading'] = 'लोड हो रहा है...';
$CIDRAM['lang']['state_loadtime'] = 'पृष्ठ अनुरोध <span class="txtRd">%s</span> सेकंड में पूरा हुआ।';
$CIDRAM['lang']['state_logged_in'] = 'लॉग इन किया है।';
$CIDRAM['lang']['state_logged_in_2fa_pending'] = 'लॉग इन किया है + 2FA लंबित है।';
$CIDRAM['lang']['state_logged_out'] = 'लॉग आउट किया है।';
$CIDRAM['lang']['state_logs_access_only'] = 'लॉग फाइल का उपयोग केवल';
$CIDRAM['lang']['state_maintenance_mode'] = 'चेतावनी: रखरखाव मोड सक्षम है!';
$CIDRAM['lang']['state_password_not_valid'] = 'चेतावनी: यह खाता किसी मान्य पासवर्ड का उपयोग नहीं कर रहा है!';
$CIDRAM['lang']['state_risk_high'] = 'भारी';
$CIDRAM['lang']['state_risk_low'] = 'कम';
$CIDRAM['lang']['state_risk_medium'] = 'मध्यम';
$CIDRAM['lang']['state_sl_totals'] = 'समूचा (हस्ताक्षर: <span class="txtRd">%s</span> – हस्ताक्षर अनुभाग: <span class="txtRd">%s</span> – हस्ताक्षर फ़ाइलें: <span class="txtRd">%s</span>)।';
$CIDRAM['lang']['state_tracking'] = ['वर्तमान में %s IP पता ट्रैकिंग।', 'वर्तमान में %s IP पते ट्रैकिंग'];
$CIDRAM['lang']['switch-hide-non-outdated-set-false'] = 'गैर पदावनत को छुपाएं न करें';
$CIDRAM['lang']['switch-hide-non-outdated-set-true'] = 'गैर पदावनत को छिपाना';
$CIDRAM['lang']['switch-hide-unused-set-false'] = 'अप्रयुक्त को छुपाएं न करें';
$CIDRAM['lang']['switch-hide-unused-set-true'] = 'अप्रयुक्त को छिपाना';
$CIDRAM['lang']['switch-tracking-blocked-already-set-false'] = 'हस्ताक्षर फ़ाइलों के खिलाफ जांच न करें';
$CIDRAM['lang']['switch-tracking-blocked-already-set-true'] = 'हस्ताक्षर फ़ाइलों के खिलाफ जांचें';
$CIDRAM['lang']['switch-tracking-hide-banned-blocked-set-false'] = 'प्रतिबंधित/अवरुद्ध IP छिपाएं नहीं';
$CIDRAM['lang']['switch-tracking-hide-banned-blocked-set-true'] = 'प्रतिबंधित/अवरुद्ध IP छिपाएं';
$CIDRAM['lang']['tip_2fa_sent'] = 'एक ईमेल में दो तरीकों से प्रमाणीकरण कोड वाला ईमेल आपके ईमेल पते पर भेजा गया है। सामने के अंत तक पहुंच प्राप्त करने के लिए कृपया नीचे दिए गए कोड की पुष्टि करें। अगर आपको यह ईमेल प्राप्त नहीं हुआ है, तो लॉग आउट करने का प्रयास करें, 10 मिनट का इंतजार करें, और एक नया कोड प्राप्त करने के लिए एक नया ईमेल प्राप्त करने के लिए फिर से लॉग इन करें।';
$CIDRAM['lang']['tip_accounts'] = 'हैलो, {username}।<br />खाता पृष्ठ आपको यह नियंत्रित करने की अनुमति देता है कि कौन CIDRAM सामने के अंत तक पहुंच सकता है।';
$CIDRAM['lang']['tip_cache_data'] = 'हैलो, {username}।<br />यहां आप कैश की सामग्रियों की समीक्षा कर सकते हैं।';
$CIDRAM['lang']['tip_cidr_calc'] = 'हैलो, {username}।<br />CIDR कैलकुलेटर आपको यह निर्धारित करने की अनुमति देता है कि कौन सी CIDR IP पता का कारक है।';
$CIDRAM['lang']['tip_config'] = 'हैलो, {username}।<br />कॉन्फ़िगरेशन पेज आपको सामने के अंत से CIDRAM के लिए कॉन्फ़िगरेशन को संशोधित करने की अनुमति देता है।';
$CIDRAM['lang']['tip_custom_ua'] = 'उपयोगकर्ता एजेंट (user agent) यहाँ दर्ज करें (यह वैकल्पिक है).';
$CIDRAM['lang']['tip_donate'] = 'CIDRAM मुफ्त पेशकश की जाती है, लेकिन अगर आप इस परियोजना के लिए दान करना चाहते हैं, आप दान बटन पर क्लिक करके ऐसा कर सकते हैं।';
$CIDRAM['lang']['tip_enter_ip_here'] = 'यहां IP दर्ज करें।';
$CIDRAM['lang']['tip_enter_ips_here'] = 'यहां IP दर्ज करें।';
$CIDRAM['lang']['tip_fe_cookie_warning'] = 'नोट: CIDRAM लॉगिन प्रमाणित करने के लिए कुकी का उपयोग करता है। लॉगिन करके, आप अपने ब्राउज़र द्वारा बनाई गई और संग्रहीत की जाने वाली कुकी के लिए आपकी सहमति देते हैं।';
$CIDRAM['lang']['tip_file_manager'] = 'हैलो, {username}।<br />फाइल प्रबंधक आपको फाइलों को हटाने, संपादित करने, अपलोड करने और डाउनलोड करने की अनुमति देता है। सावधानी से प्रयोग करें (आप इस के साथ अपनी इंस्टॉल को तोड़ सकते हैं)।';
$CIDRAM['lang']['tip_home'] = 'हैलो, {username}।<br />यह CIDRAM सामने के अंत के होमपेज है। जारी रखने के लिए बाईं ओर नेविगेशन मेनू से एक लिंक का चयन करें।';
$CIDRAM['lang']['tip_ip_aggregator'] = 'हैलो, {username}।<br />IP एग्रीगेटर आपको कम से कम संभव तरीके से IP और CIDR को व्यक्त करने की अनुमति देता है। इकट्ठा करने के लिए डेटा दर्ज करें और "ओके" दबाएं।';
$CIDRAM['lang']['tip_ip_test'] = 'हैलो, {username}।<br />IP परीक्षण पृष्ठ आपको यह जांचने की अनुमति देता है कि वर्तमान में स्थापित हस्ताक्षरों द्वारा IP पते ब्लॉक किए गए हैं या नहीं।';
$CIDRAM['lang']['tip_ip_test_module_switch'] = '(चयनित न होने पर, केवल हस्ताक्षर फ़ाइलों को इसके खिलाफ परीक्षण किया जाएगा)।';
$CIDRAM['lang']['tip_ip_tracking'] = 'हैलो, {username}।<br />IP ट्रैकिंग पृष्ठ आपको IP पते की ट्रैकिंग स्थिति की जांच करने की अनुमति देता है। आप जांच सकते हैं कि किस पर प्रतिबंध लगा दिया गया है, और जब आवश्यक हो, आप प्रतिबंधों को रद्द कर सकते हैं।';
$CIDRAM['lang']['tip_login'] = 'डिफ़ॉल्ट उपयोगकर्ता नाम: <span class="txtRd">admin</span> – डिफ़ॉल्ट पासवर्ड: <span class="txtRd">password</span>';
$CIDRAM['lang']['tip_logs'] = 'हैलो, {username}।<br />इसकी सामग्री देखने के लिए नीचे दी गई सूची से एक लॉग फाइल चुनें।';
$CIDRAM['lang']['tip_range'] = 'हैलो, {username}।<br />यह पृष्ठ वर्तमान में सक्रिय हस्ताक्षर फ़ाइलों द्वारा कवर किए गए IP रेंज के बारे में कुछ बुनियादी सांख्यिकीय जानकारी दिखाता है।';
$CIDRAM['lang']['tip_sections_list'] = 'हैलो, {username}।<br />यह पृष्ठ वर्तमान सक्रिय हस्ताक्षर फ़ाइलों में मौजूद अनुभागों को सूचीबद्ध करता है।';
$CIDRAM['lang']['tip_see_the_documentation'] = 'विभिन्न विन्यास निर्देशों और उनके उद्देश्यों के बारे में जानकारी के लिए <a href="https://github.com/CIDRAM/CIDRAM/blob/master/_docs/readme.en.md#SECTION6">डॉक्यूमेंटेशन</a> देखें।';
$CIDRAM['lang']['tip_statistics'] = 'हैलो, {username}।<br />यह पृष्ठ आपके CIDRAM स्थापना के बारे में कुछ बुनियादी उपयोग सांख्यिकी दिखाता है।';
$CIDRAM['lang']['tip_statistics_disabled'] = 'नोट: सांख्यिकी ट्रैकिंग वर्तमान में अक्षम है, लेकिन कॉन्फ़िगरेशन पृष्ठ के माध्यम से सक्षम किया जा।';
$CIDRAM['lang']['tip_updates'] = 'हैलो, {username}।<br />अपडेट पेज आपको CIDRAM के विभिन्न घटकों को इंस्टॉल, अनइंस्टॉल और अद्यतन करने की अनुमति देता है (मुख्य पैकेज, हस्ताक्षर, स्थानीयकरण फाइलें, आदि)।';
$CIDRAM['lang']['title_accounts'] = 'CIDRAM – खातों';
$CIDRAM['lang']['title_cache_data'] = 'CIDRAM – कैश डेटा';
$CIDRAM['lang']['title_cidr_calc'] = 'CIDRAM – CIDR कैलक्यूलेटर';
$CIDRAM['lang']['title_config'] = 'CIDRAM – कॉन्फ़िगरेशन';
$CIDRAM['lang']['title_file_manager'] = 'CIDRAM – फाइल प्रबंधक';
$CIDRAM['lang']['title_home'] = 'CIDRAM – होमपेज';
$CIDRAM['lang']['title_ip_aggregator'] = 'CIDRAM – IP एग्रीगेटर';
$CIDRAM['lang']['title_ip_test'] = 'CIDRAM – IP परीक्षण';
$CIDRAM['lang']['title_ip_tracking'] = 'CIDRAM – IP ट्रैकिंग';
$CIDRAM['lang']['title_login'] = 'CIDRAM – लॉग इन करें';
$CIDRAM['lang']['title_logs'] = 'CIDRAM – लॉग फाइलें';
$CIDRAM['lang']['title_range'] = 'CIDRAM – रेंज टेबल्स';
$CIDRAM['lang']['title_sections_list'] = 'CIDRAM – अनुभाग सूची';
$CIDRAM['lang']['title_statistics'] = 'CIDRAM – सांख्यिकी';
$CIDRAM['lang']['title_updates'] = 'CIDRAM – अपडेट';
$CIDRAM['lang']['warning'] = 'चेतावनी:';
$CIDRAM['lang']['warning_php_1'] = 'आपका PHP संस्करण सक्रिय रूप से अब समर्थित नहीं है! अद्यतन की सिफारिश की है!';
$CIDRAM['lang']['warning_php_2'] = 'आपका PHP संस्करण गंभीर रूप से कमजोर है! अद्यतन की जोरदार सिफारिश की है!';
$CIDRAM['lang']['warning_signatures_1'] = 'कोई हस्ताक्षर फ़ाइलें सक्रिय नहीं हैं!';

$CIDRAM['lang']['info_some_useful_links'] = 'कुछ उपयोगी लिंक:<ul>
            <li><a href="https://github.com/CIDRAM/CIDRAM/issues">CIDRAM के समस्याएं @ GitHub</a> – CIDRAM के लिए समस्याएं पृष्ठ (सहायता के लिए, आदि)।</li>
            <li><a href="https://wordpress.org/plugins/cidram/">CIDRAM @ WordPress.org</a> – CIDRAM के लिए WordPress प्लगइन।</li>
             <li><a href="https://bitbucket.org/macmathan/blocklists">macmathan/blocklists</a> – CIDRAM के लिए वैकल्पिक ब्लॉकलिस्ट और मॉड्यूल शामिल हैं जैसे खतरनाक बॉट, अवांछित देशों, पुराने ब्राउज़र, आदि को अवरुद्ध करने के लिए।</li>
            <li><a href="https://www.facebook.com/groups/2204685680/">वैश्विक PHP समूह @ Facebook</a> – PHP सीखने संसाधन और चर्चा।</li>
            <li><a href="https://php.earth/">PHP.earth</a> – PHP सीखने संसाधन और चर्चा।</li>
            <li><a href="http://bgp.he.net/">Hurricane Electric BGP टूलकिट</a> – ASN से CIDR प्राप्त करें, ASN संबंधों को निर्धारित करें, नेटवर्क नामों पर आधारित ASN की खोज करें, आदि।</li>
            <li><a href="https://www.stopforumspam.com/forum/">मंच @ Stop Forum Spam</a> – मंच स्पैम को रोकने के बारे में उपयोगी चर्चा मंच।</li>
            <li><a href="https://radar.qrator.net/">Radar द्वारा Qrator</a> – ASN कनेक्टिविटी की जांच के लिए उपयोगी उपकरण, साथ ही साथ ASN के बारे में विभिन्न अन्य सूचनाओं के लिए।</li>
            <li><a href="http://www.ipdeny.com/ipblocks/">IPdeny IP देश के ब्लॉक</a> – देशव्यापी हस्ताक्षर बनाने के लिए एक शानदार और सटीक सेवा।</li>
            <li><a href="https://www.google.com/transparencyreport/safebrowsing/malware/">Google मैलवेयर डैशबोर्ड</a> – ASN मैलवेयर संक्रमण दर के बारे में रिपोर्ट प्रदर्शित करता है।</li>
            <li><a href="https://www.spamhaus.org/statistics/botnet-asn/">Spamhaus प्रोजेक्ट</a> – ASN बोटनेट संक्रमण दरों के बारे में रिपोर्ट प्रदर्शित करता है।</li>
            <li><a href="https://www.abuseat.org/public/asn.html">Abuseat.org की समग्र ब्लॉकिंग सूची</a> – ASN बोटनेट संक्रमण दरों के बारे में रिपोर्ट प्रदर्शित करता है।</li>
            <li><a href="https://abuseipdb.com/">AbuseIPDB</a> – ज्ञात अपमानजनक IP के डेटाबेस बनाए रखता है; IP जांच और रिपोर्ट करने के लिए API प्रदान करता है।</li>
            <li><a href="https://www.megarbl.net/index.php">MegaRBL.net</a> – ज्ञात स्पैमर की सूची बनाए रखता है; IP/ASN स्पैम गतिविधियों को देखने के लिए उपयोगी।</li>
            <li><a href="https://maikuolan.github.io/Vulnerability-Charts/">Vulnerability Charts (भेद्यता चार्ट)</a> – विभिन्न पैकेजों के सुरक्षित/असुरक्षित संस्करणों की सूची (HHVM, PHP, phpMyAdmin, Python, आदि)।</li>
            <li><a href="https://maikuolan.github.io/Compatibility-Charts/">Compatibility Charts (संगतता चार्ट)</a> – विभिन्न पैकेजों के लिए सुसंगतता सूचियों की सूची (CIDRAM, phpMussel, आदि)।</li>
        </ul>';

$CIDRAM['lang']['msg_template_2fa'] = '<center><p>हैलो, %1$s।<br />
<br />
CIDRAM सामने के अंत में लॉग इन करने के लिए आपका दो तरीकों से प्रमाणीकरण कोड:</p>
<h1>%2$s</h1>
<p>यह कोड 10 मिनट में समाप्त हो जाता है।</p></center>';
$CIDRAM['lang']['msg_subject_2fa'] = 'दो तरीकों से प्रमाणीकरण';
