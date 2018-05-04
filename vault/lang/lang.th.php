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
 * This file: Thai language data (last modified: 2018.05.04).
 */

/** Prevents execution from outside of CIDRAM. */
if (!defined('CIDRAM')) {
    die('[CIDRAM] This should not be accessed directly.');
}

$CIDRAM['lang']['Error_WriteCache'] = 'ไม่สามารถเขียนลงในแคช! โปรดตรวจสอบสิทธิ์ของไฟล์ CHMOD!';
$CIDRAM['lang']['MoreInfo'] = 'สำหรับข้อมูลเพิ่มเติม:';
$CIDRAM['lang']['ReasonMessage_BadIP'] = 'การเข้าถึงหน้าเว็บนี้ถูกปฏิเสธเนื่องจากคุณพยายามเข้าถึงหน้านี้ใช้ที่อยู่ IP ที่ไม่ถูกต้อง.';
$CIDRAM['lang']['ReasonMessage_Banned'] = 'การเข้าถึงหน้าเว็บนี้ถูกปฏิเสธเนื่องจากพฤติกรรมที่ไม่ดีก่อนหน้านี้จากที่อยู่ IP ของคุณ.';
$CIDRAM['lang']['ReasonMessage_Bogon'] = 'การเข้าถึงหน้าเว็บนี้ถูกปฏิเสธเนื่องจากที่อยู่ IP ของคุณเป็นที่อยู่ของ bogon. เว็บไซต์นี้ไม่อนุญาตการเชื่อมต่อจากที่อยู่ IP bogon.';
$CIDRAM['lang']['ReasonMessage_Cloud'] = 'การเข้าถึงหน้าเว็บนี้ถูกปฏิเสธเนื่องจากที่อยู่ IP ของคุณเป็นของบริการระบบคลาวด์. เว็บไซต์นี้ไม่อนุญาตการเชื่อมต่อจากบริการคลาวด์.';
$CIDRAM['lang']['ReasonMessage_Generic'] = 'การเข้าถึงหน้าเว็บนี้ถูกปฏิเสธเนื่องจากที่อยู่ IP ของคุณอยู่ในบัญชีดำโดยเว็บไซต์นี้.';
$CIDRAM['lang']['ReasonMessage_Legal'] = 'การเข้าถึงหน้าเว็บนี้ถูกปฏิเสธเนื่องจากข้อผูกมัดทางกฎหมาย.';
$CIDRAM['lang']['ReasonMessage_Malware'] = 'การเข้าถึงหน้าเว็บนี้ถูกปฏิเสธเนื่องจากมีข้อกังวลเกี่ยวกับมัลแวร์เกี่ยวกับที่อยู่ IP ของคุณ.';
$CIDRAM['lang']['ReasonMessage_Proxy'] = 'การเข้าถึงหน้าเว็บนี้ถูกปฏิเสธเนื่องจากคุณได้เชื่อมต่อผ่านบริการพร็อกซี่หรือ VPN. เว็บไซต์นี้ไม่อนุญาตการเชื่อมต่อจากบริการพร็อกซีหรือ VPN.';
$CIDRAM['lang']['ReasonMessage_Spam'] = 'การเข้าถึงหน้าเว็บนี้ถูกปฏิเสธเนื่องจากที่อยู่ IP ของคุณอยู่ในเครือข่ายที่เกี่ยวข้องกับกิจกรรมสแปม.';
$CIDRAM['lang']['Short_BadIP'] = 'IP ไม่ถูกต้อง';
$CIDRAM['lang']['Short_Banned'] = 'ถูกห้าม';
$CIDRAM['lang']['Short_Bogon'] = 'IP Bogon';
$CIDRAM['lang']['Short_Cloud'] = 'บริการคลาวด์';
$CIDRAM['lang']['Short_Generic'] = 'ทั่วไป';
$CIDRAM['lang']['Short_Legal'] = 'ทางกฎหมาย';
$CIDRAM['lang']['Short_Malware'] = 'มัลแวร์';
$CIDRAM['lang']['Short_Proxy'] = 'ผู้รับมอบฉันทะ';
$CIDRAM['lang']['Short_Spam'] = 'ความเสี่ยงของสแปม';
$CIDRAM['lang']['Support_Email'] = 'หากคุณเชื่อว่านี่เป็นข้อผิดพลาด, หรือเพื่อขอความช่วยเหลือ, {ClickHereLink}เพื่อส่งอีเมลสนับสนุนไปยังเว็บมาสเตอร์ของเว็บไซต์นี้ (โปรดอย่าเปลี่ยนบรรทัดคำนำหรือหัวเรื่องของอีเมล).';
$CIDRAM['lang']['Support_Email_2'] = 'หากคุณเชื่อว่านี่เป็นข้อผิดพลาด, ส่งอีเมลไปที่ {EmailAddr} เพื่อขอความช่วยเหลือ.';
$CIDRAM['lang']['click_here'] = 'คลิกที่นี่';
$CIDRAM['lang']['denied'] = 'ปฏิเสธการเข้าใช้!';
$CIDRAM['lang']['fake_ua'] = '{ua} ปลอม';
$CIDRAM['lang']['field_datetime'] = 'วันเวลา: ';
$CIDRAM['lang']['field_hostname'] = 'ชื่อโฮสต์: ';
$CIDRAM['lang']['field_id'] = 'ID: ';
$CIDRAM['lang']['field_ipaddr'] = 'ที่อยู่ IP: ';
$CIDRAM['lang']['field_ipaddr_resolved'] = 'ที่อยู่ IP (แก้ไข): ';
$CIDRAM['lang']['field_query'] = 'ข้อความค้นหา: ';
$CIDRAM['lang']['field_rURI'] = 'URI ที่สร้างขึ้นใหม่: ';
$CIDRAM['lang']['field_reCAPTCHA_state'] = 'สภาพ reCAPTCHA: ';
$CIDRAM['lang']['field_referrer'] = 'ผู้อ้างอิง: ';
$CIDRAM['lang']['field_scriptversion'] = 'เวอร์ชันสคริปต์: ';
$CIDRAM['lang']['field_sigcount'] = 'ลายเซ็นนับ: ';
$CIDRAM['lang']['field_sigref'] = 'การอ้างอิงลายเซ็น: ';
$CIDRAM['lang']['field_ua'] = 'ตัวแทนผู้ใช้: ';
$CIDRAM['lang']['field_whyreason'] = 'ทำไมถูกบล็อก: ';
$CIDRAM['lang']['generated_by'] = 'สร้างขึ้นโดย';
$CIDRAM['lang']['preamble'] = '-- จุดสิ้นสุดของคำนำ. เพิ่มคำถามหรือความคิดเห็นของคุณหลังจากบรรทัดนี้ --';
$CIDRAM['lang']['recaptcha_cookie_warning'] = 'บันทึก: CIDRAM ใช้คุกกี้เพื่อจดจำเมื่อผู้ใช้กรอก CAPTCHA. เมื่อกรอก CAPTCHA เสร็จสิ้นคุณจะให้ความยินยอมสำหรับคุกกี้ที่จะสร้างและจัดเก็บโดยเบราเซอร์ของคุณ.';
$CIDRAM['lang']['recaptcha_disabled'] = 'ไม่ใช้งาน.';
$CIDRAM['lang']['recaptcha_enabled'] = 'ใช้งานอยู่.';
$CIDRAM['lang']['recaptcha_failed'] = 'ล้มเหลว!';
$CIDRAM['lang']['recaptcha_message'] = 'เพื่อให้สามารถเข้าถึงหน้านี้ได้อีกครั้ง, โปรดกรอกข้อมูล CAPTCHA ด้านล่างนี้และกดปุ่มส่ง.';
$CIDRAM['lang']['recaptcha_message_invisible'] = 'ในกรณีส่วนใหญ่ หน้าเว็บควรโหลดและกู้คืนการเข้าถึงตามปกติ. ในบางกรณี คุณอาจต้องกรอกข้อมูล CAPTCHA.';
$CIDRAM['lang']['recaptcha_passed'] = 'ผ่าน!';
$CIDRAM['lang']['recaptcha_submit'] = 'ส่ง';
