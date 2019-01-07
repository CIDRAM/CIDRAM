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
 * This file: Thai language data for the front-end (last modified: 2019.01.07).
 */

/** Prevents execution from outside of CIDRAM. */
if (!defined('CIDRAM')) {
    die('[CIDRAM] This should not be accessed directly.');
}

foreach (['IPv4', 'IPv6'] as $CIDRAM['IPvX']) {
    $CIDRAM['Pre'] = 'ลายเซ็น ' . $CIDRAM['IPvX'] . ' เริ่มต้นที่รวมอยู่ในแพคเกจหลัก. ';
    $CIDRAM['lang']['Extended Description: ' . $CIDRAM['IPvX']] = $CIDRAM['Pre'] . 'บล็อกบริการคลาวด์ที่ไม่พึงประสงค์และจุดเชื่อมต่อที่ไม่ใช่ของมนุษย์.';
    $CIDRAM['lang']['Extended Description: ' . $CIDRAM['IPvX'] . '-Bogons'] = $CIDRAM['Pre'] . 'บล็อก bogon/martian CIDR.';
    $CIDRAM['lang']['Extended Description: ' . $CIDRAM['IPvX'] . '-ISPs'] = $CIDRAM['Pre'] . 'บล็อกผู้ให้บริการอินเทอร์เน็ตที่เป็นอันตรายและสแปม.';
    $CIDRAM['lang']['Extended Description: ' . $CIDRAM['IPvX'] . '-Other'] = $CIDRAM['Pre'] . 'CIDR สำหรับผู้รับมอบฉันทะ VPNs และบริการอื่น ๆ ที่ไม่พึงประสงค์.';
    $CIDRAM['Pre'] = 'ไฟล์ลายเซ็น ' . $CIDRAM['IPvX'] . ' (%s).';
    $CIDRAM['lang']['Name: ' . $CIDRAM['IPvX']] = sprintf($CIDRAM['Pre'], 'บริการคลาวด์ที่ไม่พึงประสงค์และจุดเชื่อมต่อที่ไม่ใช่ของมนุษย์');
    $CIDRAM['lang']['Name: ' . $CIDRAM['IPvX'] . '-Bogons'] = sprintf($CIDRAM['Pre'], 'CIDR ที่ bogon/ชาวอังคาร');
    $CIDRAM['lang']['Name: ' . $CIDRAM['IPvX'] . '-ISPs'] = sprintf($CIDRAM['Pre'], 'ISP ที่เป็นอันตรายและสแปม');
    $CIDRAM['lang']['Name: ' . $CIDRAM['IPvX'] . '-Other'] = sprintf($CIDRAM['Pre'], 'CIDR สำหรับผู้รับมอบฉันทะ VPNs และบริการอื่น ๆ ที่ไม่พึงประสงค์');
}
unset($CIDRAM['Pre'], $CIDRAM['IPvX']);

$CIDRAM['lang']['Extended Description: Bypasses'] = 'ไฟล์บายพาสลายเซ็นเริ่มต้นที่รวมอยู่ในแพคเกจหลัก.';
$CIDRAM['lang']['Extended Description: CIDRAM'] = 'แพคเกจหลัก (ไม่รวมลายเซ็น เอกสาร และการกำหนดค่า).';
$CIDRAM['lang']['Extended Description: Chart.js'] = 'เปิดใช้งาน front-end เพื่อสร้างแผนภูมิวงกลม.<br /><a href="https://github.com/chartjs/Chart.js">Chart.js</a> สามารถใช้ได้ผ่านทาง <a href="https://opensource.org/licenses/MIT">MIT license</a>.';
$CIDRAM['lang']['Extended Description: PHPMailer'] = 'จำเป็นสำหรับการใช้ฟังก์ชันใด ๆ ที่เกี่ยวข้องกับการส่งอีเมล.<br /><a href="https://github.com/PHPMailer/PHPMailer">PHPMailer</a> สามารถใช้ได้ผ่านทางใบอนุญาต <a href="https://github.com/PHPMailer/PHPMailer/blob/master/LICENSE">LGPLv2.1</a>.';
$CIDRAM['lang']['Extended Description: module_badhosts.php'] = 'บล็อกโฮสต์ที่มักใช้โดยผู้ส่งสแปม แฮ็กเกอร์ และบุคคลอื่น ๆ ที่ไม่เป็นไปตามหลักเกณฑ์.';
$CIDRAM['lang']['Extended Description: module_badhosts_isps.php'] = 'บล็อกโฮสต์ของ ISP ที่มักใช้โดยผู้ส่งสแปม แฮ็กเกอร์ และบุคคลอื่น ๆ ที่ไม่เป็นไปตามหลักเกณฑ์.';
$CIDRAM['lang']['Extended Description: module_badtlds.php'] = 'บล็อกโฮสต์ของ TLD ที่มักใช้โดยผู้ส่งสแปม แฮ็กเกอร์ และบุคคลอื่น ๆ ที่ไม่เป็นไปตามหลักเกณฑ์.';
$CIDRAM['lang']['Extended Description: module_botua.php'] = 'บล็อกตัวแทนผู้ใช้ที่เกี่ยวข้องกับบอทและกิจกรรมที่ไม่เป็นที่ต้องการ.';
$CIDRAM['lang']['Extended Description: module_cookies.php'] = 'ให้การป้องกันที่ จำกัด สำหรับคุกกี้ที่เป็นอันตราย.';
$CIDRAM['lang']['Extended Description: module_extras.php'] = 'ให้การป้องกันที่ จำกัด กับเวกเตอร์การโจมตีต่างๆที่ใช้ทั่วไปในคำขอ.';
$CIDRAM['lang']['Extended Description: module_sfs.php'] = 'ปกป้องหน้าการลงทะเบียนและเข้าสู่ระบบต่อต้าน IP ที่ระบุโดย SFS.';
$CIDRAM['lang']['Name: Bypasses'] = 'บายพาลายเซ็นที่เป็นค่าเริ่มต้น.';
$CIDRAM['lang']['Name: compat_bunnycdn.php'] = 'โมดูลความเข้ากัน BunnyCDN';
$CIDRAM['lang']['Name: module_badhosts.php'] = 'โมดูลป้องกันโฮสต์ที่เป็นอันตราย';
$CIDRAM['lang']['Name: module_badhosts_isps.php'] = 'โมดูลป้องกันโฮสต์ที่เป็นอันตราย (ผู้ให้บริการอินเทอร์เน็ต)';
$CIDRAM['lang']['Name: module_badtlds.php'] = 'โมดูลป้องกัน TLD ที่เป็นอันตราย';
$CIDRAM['lang']['Name: module_baidublocker.php'] = 'โมดูลป้องกัน Baidu';
$CIDRAM['lang']['Name: module_botua.php'] = 'โมดูลตัวแทนผู้ใช้';
$CIDRAM['lang']['Name: module_cookies.php'] = 'โมดูลสแกนเนอร์คุกกี้ตัวเลือก';
$CIDRAM['lang']['Name: module_extras.php'] = 'โมดูลเสริมความปลอดภัยเพิ่มเติม';
$CIDRAM['lang']['Name: module_sfs.php'] = 'โมดูล Stop Forum Spam';
$CIDRAM['lang']['Name: module_ua.php'] = 'โมดูลตัวบล็อก UA ที่ว่างเปล่า';
$CIDRAM['lang']['Name: module_yandexblocker.php'] = 'โมดูลป้องกัน Yandex';
$CIDRAM['lang']['bNav_home_logout'] = '<a href="?">โฮมเพจ</a> | <a href="?cidram-page=logout">ออกจากระบบ</a>';
$CIDRAM['lang']['bNav_logout'] = '<a href="?cidram-page=logout">ออกจากระบบ</a>';
$CIDRAM['lang']['config_PHPMailer_Enable2FA'] = 'ซึ่งจะกำหนดว่าบัญชีส่วนหน้าควรใช้ 2FA หรือไม่.';
$CIDRAM['lang']['config_PHPMailer_EventLog'] = 'ไฟล์สำหรับบันทึกเหตุการณ์ทั้งหมดที่เกี่ยวข้องกับ PHPMailer. ระบุชื่อไฟล์หรือเว้นว่างไว้เพื่อปิดใช้งาน.';
$CIDRAM['lang']['config_PHPMailer_Host'] = 'โฮสต์ SMTP ที่ใช้สำหรับอีเมลขาออก.';
$CIDRAM['lang']['config_PHPMailer_Password'] = 'รหัสผ่านสำหรับเมื่อส่งอีเมล.';
$CIDRAM['lang']['config_PHPMailer_Port'] = 'หมายเลขพอร์ตที่จะใช้สำหรับอีเมลขาออก. ค่าเริ่มต้น = 587.';
$CIDRAM['lang']['config_PHPMailer_SMTPAuth'] = 'กำหนดว่าเซสชัน SMTP ควรได้รับการตรวจสอบสิทธิ์หรือไม่ (ควรปล่อยให้อยู่คนเดียว).';
$CIDRAM['lang']['config_PHPMailer_SMTPSecure'] = 'โปรโตคอลสำหรับการส่งอีเมล (TLS หรือ SSL).';
$CIDRAM['lang']['config_PHPMailer_SkipAuthProcess'] = 'เมื่อ <code>true</code>, PHPMailer ข้ามขั้นตอนการตรวจสอบสิทธิ์ที่เกิดขึ้นโดยปกติเมื่อส่งอีเมลผ่าน SMTP. นี้ควรหลีกเลี่ยงเนื่องจากข้ามขั้นตอนนี้อาจทำให้อีเมลขาออกอ่อนแอต่อ MITM แต่อาจจำเป็นในกรณีที่กระบวนการนี้ป้องกันไม่ให้ PHPMailer เชื่อมต่อกับเซิร์ฟเวอร์ SMTP.';
$CIDRAM['lang']['config_PHPMailer_Username'] = 'ชื่อผู้ใช้สำหรับเมื่อส่งอีเมล.';
$CIDRAM['lang']['config_PHPMailer_addReplyToAddress'] = 'ที่อยู่ตอบกลับสำหรับเมื่อส่งอีเมล.';
$CIDRAM['lang']['config_PHPMailer_addReplyToName'] = 'ชื่อตอบกลับสำหรับเมื่อส่งอีเมล.';
$CIDRAM['lang']['config_PHPMailer_setFromAddress'] = 'ที่อยู่ผู้ส่งสำหรับเมื่อส่งอีเมล.';
$CIDRAM['lang']['config_PHPMailer_setFromName'] = 'ชื่อผู้ส่งสำหรับเมื่อส่งอีเมล.';
$CIDRAM['lang']['config_experimental'] = 'ไม่เสถียร/ทดลอง!';
$CIDRAM['lang']['config_general_FrontEndLog'] = 'ไฟล์สำหรับบันทึก front-end ความพยายามเข้าสู่ระบบ. ระบุชื่อไฟล์หรือเว้นว่างไว้เพื่อปิดใช้งาน.';
$CIDRAM['lang']['config_general_allow_gethostbyaddr_lookup'] = 'อนุญาตให้มีการค้นหา gethostbyaddr เมื่อ UDP ไม่พร้อมใช้งานหรือไม่? True = หยุดใช้ [ค่าเริ่มต้น]; False = ไม่หยุดใช้.';
$CIDRAM['lang']['config_general_ban_override'] = 'แทนที่ "forbid_on_block" เมื่อ "infraction_limit" ได้รับการเกินหรือไม่? เมื่อแทนที่: คำขอที่ถูกบล็อกกลับหน้าเปล่า (ไฟล์เทมเพลตจะไม่ถูกใช้). 200 = อย่าแทนที่ [ค่าเริ่มต้น]. ค่าอื่น ๆ จะเหมือนกับค่าที่ใช้ได้สำหรับ "forbid_on_block".';
$CIDRAM['lang']['config_general_default_algo'] = 'กำหนดว่าจะใช้อัลกอริทึมใดสำหรับรหัสผ่านและเซสชันในอนาคต. ตัวเลือก: PASSWORD_DEFAULT (ค่าเริ่มต้น), PASSWORD_BCRYPT, PASSWORD_ARGON2I (ต้องการ PHP &gt;= 7.2.0).';
$CIDRAM['lang']['config_general_default_dns'] = 'รายการคั่นด้วยจุลภาคของเซิร์ฟเวอร์ DNS ใช้สำหรับการค้นหาชื่อโฮสต์. ค่าเริ่มต้น = "8.8.8.8,8.8.4.4" (Google DNS). คำเตือน: อย่าเปลี่ยนสิ่งนี้จนกว่าคุณจะรู้ว่าคุณกำลังทำอะไร!';
$CIDRAM['lang']['config_general_disable_cli'] = 'ปิดใช้งานโหมด CLI หรือไม่? โหมด CLI ถูกเปิดใช้งานตามค่าเริ่มต้น แต่บางครั้งอาจรบกวนการทำงานของเครื่องมือทดสอบบางอย่าง (เช่น PHPUnit) และแอพพลิเคชั่น CLI อื่น ๆ. ถ้าคุณไม่จำเป็นต้องปิดใช้งานโหมด CLI คุณควรละเว้นคำสั่งนี้. False = เปิดใช้งานโหมด CLI [ค่าเริ่มต้น]; True = ปิดใช้งานโหมด CLI.';
$CIDRAM['lang']['config_general_disable_frontend'] = 'ปิดใช้งานการเข้าถึง front-end หรือไม่? การเข้าถึง front-end ทำให้ CIDRAM สามารถจัดการได้ดีขึ้น แต่ก็อาจเป็นความเสี่ยงด้านความปลอดภัยที่อาจเกิดขึ้นด้วย. ขอแนะนำให้จัดการ CIDRAM ผ่านทางแบ็คเอนด์เมื่อใดก็ตามที่เป็นไปได้ แต่จะมีการเข้าถึง front-end เมื่อไม่สามารถทำได้. โปรดปิดใช้งานหากคุณไม่ต้องการ. False = เปิดใช้งานการเข้าถึง front-end; True = ปิดการใช้งานการเข้าถึง front-end [ค่าเริ่มต้น].';
$CIDRAM['lang']['config_general_disable_webfonts'] = 'หยุดใช้ webfonts หรือไม่? True = หยุดใช้ [ค่าเริ่มต้น]; False = ไม่หยุดใช้.';
$CIDRAM['lang']['config_general_emailaddr'] = 'หากต้องการคุณสามารถระบุที่อยู่อีเมลเพื่อมอบให้กับผู้ใช้เมื่อมีการปิดกั้น สำหรับพวกเขาที่จะใช้เป็นจุดติดต่อสำหรับการสนับสนุนและให้ความช่วยเหลือเมื่อพวกเขากำลังบล็อกผิดพลาด. คำเตือน: ที่อยู่อีเมลใด ๆ ที่จัดหาให้ในที่นี้จะได้รับจากสแปมบอทและแครปเปอร์ ดังนั้นขอแนะนำให้คุณเลือกที่อยู่อีเมลที่ใช้แล้วทิ้งหรือไม่สำคัญ (นั่นคือ คุณอาจไม่ต้องการใช้ที่อยู่อีเมลธุรกิจหลักหรือส่วนบุคคลหลักของคุณ).';
$CIDRAM['lang']['config_general_emailaddr_display_style'] = 'คุณต้องการที่อยู่อีเมลที่จะนำเสนอต่อผู้ใช้อย่างไร?';
$CIDRAM['lang']['config_general_empty_fields'] = 'CIDRAM ควรจัดการช่องว่างเปล่าเมื่อบันทึกและแสดงข้อมูลเหตุการณ์การบล็อกอย่างไร? "include" = รวมฟิลด์ที่ว่างเปล่า. "omit" = ซ่อนฟิลด์ที่ว่างเปล่า [ค่าเริ่มต้น].';
$CIDRAM['lang']['config_general_forbid_on_block'] = 'ข้อความสถานะ HTTP ที่ CIDRAM ควรส่งเมื่อมีการบล็อกคำขออะไร? (ดูเอกสารประกอบเพิ่มเติมสำหรับข้อมูลเพิ่มเติม).';
$CIDRAM['lang']['config_general_force_hostname_lookup'] = 'บังคับใช้การค้นหาชื่อโฮสต์? True = หยุดใช้; False = ไม่หยุดใช้ [ค่าเริ่มต้น]. การค้นหาชื่อโฮสต์จะดำเนินการตามปกติโดยพิจารณาจากความจำเป็น แต่สามารถบังคับให้คำขอทั้งหมด. นี้อาจเป็นประโยชน์สำหรับการให้ข้อมูลเพิ่มเติมในไฟล์บันทึก แต่อาจมีผลเสียต่อประสิทธิภาพเล็กน้อย.';
$CIDRAM['lang']['config_general_hide_version'] = 'ซ่อนข้อมูลเวอร์ชันจากบันทึกและเอาต์พุตหน้าเว็บ? True = หยุดใช้; False = ไม่หยุดใช้ [ค่าเริ่มต้น].';
$CIDRAM['lang']['config_general_ipaddr'] = 'ตำแหน่งของที่อยู่ IP สำหรับคำขอการเชื่อมต่อ (เป็นประโยชน์สำหรับบริการเช่น Cloudflare ฯลฯ). ค่าเริ่มต้น = REMOTE_ADDR. คำเตือน: อย่าเปลี่ยนสิ่งนี้จนกว่าคุณจะรู้ว่าคุณกำลังทำอะไร!';
$CIDRAM['lang']['config_general_lang'] = 'ระบุภาษาค่าเริ่มต้นสำหรับ CIDRAM.';
$CIDRAM['lang']['config_general_log_banned_ips'] = 'รวมคำขอที่ถูกบล็อกจากที่อยู่ IP ที่ถูกห้ามในไฟล์บันทึกหรือไม่? True = รวม [ค่าเริ่มต้น]; False = ไม่รวม.';
$CIDRAM['lang']['config_general_log_rotation_action'] = 'หมุนเวียนล็อก (log rotation) จำกัด จำนวนล็อกไฟล์ที่ควรมีอยู่ ณ เวลาใดก็ได้. เมื่อล็อกไฟล์ใหม่ถูกสร้างขึ้น ถ้าจำนวนล็อกไฟล์ทั้งหมด เกินขีด จำกัด ที่ระบุ การดำเนินการที่ระบุจะถูกดำเนินการ. คุณสามารถระบุการกระทำที่ต้องการได้ที่นี่. Delete = ลบไฟล์ที่เก่าที่สุด จนกว่าขีด จำกัด จะไม่เกิน. Archive = ประการแรก บีบอัดไฟล์ และหลังจากนั้น ลบไฟล์ที่เก่าที่สุด จำกัด จะไม่เกิน.';
$CIDRAM['lang']['config_general_log_rotation_limit'] = 'หมุนเวียนล็อก (log rotation) จำกัด จำนวนล็อกไฟล์ที่ควรมีอยู่ ณ เวลาใดก็ได้. เมื่อล็อกไฟล์ใหม่ถูกสร้างขึ้น ถ้าจำนวนล็อกไฟล์ทั้งหมด เกินขีด จำกัด ที่ระบุ การดำเนินการที่ระบุจะถูกดำเนินการ. คุณสามารถระบุขีด จำกัด ที่ต้องการได้ที่นี่. ค่า 0 จะปิดใช้งานการหมุนเวียนล็อก (log rotation).';
$CIDRAM['lang']['config_general_logfile'] = 'ไฟล์มนุษย์สามารถอ่านได้สำหรับบันทึกทั้งหมดของพยายามเข้าถึงที่ถูกบล็อก. ระบุชื่อไฟล์หรือเว้นว่างไว้เพื่อปิดใช้งาน.';
$CIDRAM['lang']['config_general_logfileApache'] = 'ไฟล์ในสไตล์ Apache สำหรับบันทึกทั้งหมดของพยายามเข้าถึงที่ถูกบล็อก. ระบุชื่อไฟล์หรือเว้นว่างไว้เพื่อปิดใช้งาน.';
$CIDRAM['lang']['config_general_logfileSerialized'] = 'ไฟล์รูปแบบอนุกรมสำหรับบันทึกทั้งหมดของพยายามเข้าถึงที่ถูกบล็อก. ระบุชื่อไฟล์หรือเว้นว่างไว้เพื่อปิดใช้งาน.';
$CIDRAM['lang']['config_general_maintenance_mode'] = 'เปิดใช้โหมดการบำรุงรักษาหรือไม่? True = เปิดใช้งานได้; False = ไม่เปิดใช้งาน [ค่าเริ่มต้น]. ปิดใช้งานทุกอย่างอื่นที่ไม่ใช่ front-end. บางครั้งมีประโยชน์สำหรับการอัปเดต CMS, framework, ฯลฯ.';
$CIDRAM['lang']['config_general_max_login_attempts'] = 'จำนวนสูงสุดความพยายามเข้าสู่ระบบ.';
$CIDRAM['lang']['config_general_numbers'] = 'คุณต้องการตัวเลขที่จะแสดงอย่างไร? เลือกตัวอย่างที่ดูเหมือนถูกต้องที่สุด.';
$CIDRAM['lang']['config_general_protect_frontend'] = 'ระบุถ้าการป้องกันปกติให้บริการโดย CIDRAM ควรจะเป็นนำมาใช้กับ front-end. True = ใช้ [ค่าเริ่มต้น]; False = ไม่ใช้.';
$CIDRAM['lang']['config_general_search_engine_verification'] = 'ยืนยันคำขอจากเครื่องมือค้นหาหรือไม่? ยืนยันของเครื่องมือค้นหาจะทำให้แน่ใจว่าพวกเขาจะไม่ถูกแบนห้ามเนื่องจากเกินของขีดจำกัดการละเมิด (ห้ามของเครื่องมือค้นหาจากเว็บไซต์ของคุณมักจะมีผลเสียสำหรับการจัดอันดับของเครื่องมือค้นหาของคุณ SEO ฯลฯ). เมื่อได้ยืนยัน เครื่องมือค้นหาสามารถบล็อกตามปกติ แต่จะไม่ถูกห้าม. เมื่อไม่ยืนยัน เป็นไปได้สำหรับพวกเขาเป็นห้ามเนื่องจากเกินขีดจำกัดการละเมิด. นอกจากนี้ การยืนยันเครื่องมือค้นหาให้การป้องกันต่อต้านคำขอเครื่องมือค้นหาของปลอมและต่อต้านองค์กร(คน โปรแกรม ฯลฯ)ที่อาจเป็นอันตรายที่แกล้งทำเป็นว่าเครื่องมือค้นหา (คำขอดังกล่าวจะถูกบล็อกเมื่อการยืนยันเครื่องมือค้นหาเปิดใช้งาน). True = เปิดใช้งานการยืนยันเครื่องมือค้นหา [ค่าเริ่มต้น]; False = ปิดใช้งานการยืนยันเครื่องมือค้นหา.';
$CIDRAM['lang']['config_general_silent_mode'] = 'ควร CIDRAM เปลี่ยนเส้นทางพยายามเข้าถึงที่ถูกบล็อกเงียบค่อนข้างมากกว่าการแสดงหน้า "ปฏิเสธการเข้าใช้" หรือไม่? ถ้าใช่ ระบุตำแหน่งเป้าหมาย. ถ้าไม่ ปล่อยให้ตัวแปรนี้ว่างเปล่า.';
$CIDRAM['lang']['config_general_social_media_verification'] = 'พยายามยืนยันคำขอสื่อสังคมออนไลน์หรือไม่? การตรวจสอบสื่อสังคมออนไลน์จะช่วยป้องกันคำขอสื่อทางสังคมปลอม ๆ (คำขอปลอมเหล่านี้จะถูกบล็อก). True = เปิดใช้งานการยืนยันสื่อสังคมออนไลน์ [ค่าเริ่มต้น]; False = ปิดใช้งานการยืนยันสื่อสังคมออนไลน์.';
$CIDRAM['lang']['config_general_statistics'] = 'ติดตามสถิติการใช้งาน CIDRAM? True = ติดตาม; False = ไม่ติดตาม [ค่าเริ่มต้น].';
$CIDRAM['lang']['config_general_timeFormat'] = 'รูปแบบสัญกรณ์สำหรับวันและเวลาใช้โดย CIDRAM. ตัวเลือกเพิ่มเติมอาจเพิ่มเมื่อมีการร้องขอ.';
$CIDRAM['lang']['config_general_timeOffset'] = 'เขตเวลาชดเชยในนาที.';
$CIDRAM['lang']['config_general_timezone'] = 'โซนเวลาของคุณ.';
$CIDRAM['lang']['config_general_truncate'] = 'ตัดทอนแฟ้มบันทึกเมื่อถึงขนาดที่กำหนดหรือไม่? ค่ามีขนาดสูงสุดในรูปแบบ B/KB/MB/GB/TB ที่แฟ้มบันทึกอาจโตขึ้นก่อนที่จะถูกตัดทอน. ค่าเริ่มต้นของ 0KB ปิดการตัดทอน (แฟ้มบันทึกสามารถเติบโตไปเรื่อย). หมายเหตุ: ถูกใช้ด้วยกับล็อกไฟล์แต่ละไฟล์! ขนาดของไฟล์บันทึกไม่ถือเป็นการรวมกัน.';
$CIDRAM['lang']['config_legal_omit_hostname'] = 'ไม่รวมชื่อโฮสต์ในล็อกไฟล์? True = ทำ; False = ไม่ทำ [ค่าเริ่มต้น].';
$CIDRAM['lang']['config_legal_omit_ip'] = 'ไม่รวมที่อยู่ IP ในล็อกไฟล์? True = ทำ; False = ไม่ทำ [ค่าเริ่มต้น]. หมายเหตุ: "pseudonymise_ip_addresses" กลายเป็นซ้ำซ้อนเมื่อ "omit_ip" เป็น "true".';
$CIDRAM['lang']['config_legal_omit_ua'] = 'ไม่รวม user agent ในล็อกไฟล์? True = ทำ; False = ไม่ทำ [ค่าเริ่มต้น].';
$CIDRAM['lang']['config_legal_privacy_policy'] = 'ที่อยู่ของนโยบายความเป็นส่วนตัวที่เกี่ยวข้องเพื่อแสดงในส่วนท้ายของหน้าเว็บที่สร้างขึ้น. ระบุ URL หรือเว้นว่างไว้เพื่อปิดใช้งาน.';
$CIDRAM['lang']['config_legal_pseudonymise_ip_addresses'] = 'Pseudonymise IP addresses เมื่อเขียนล็อกไฟล์หรือไม่? True = ทำ [ค่าเริ่มต้น]; False = ไม่ทำ.';
$CIDRAM['lang']['config_rate_limiting_allowance_period'] = 'จำนวนชั่วโมงในการติดตามการใช้งาน. ค่าเริ่มต้น = 0.';
$CIDRAM['lang']['config_rate_limiting_max_bandwidth'] = 'จำนวนแบนด์วิดท์สูงสุดที่อนุญาตภายในระยะเวลาที่อนุญาตก่อนที่จะมีการ จำกัด อัตราค่าบริการในอนาคต. ค่า 0 จะปิดใช้งานการ จำกัด อัตราค่าบริการประเภทนี้. ค่าเริ่มต้น = 0KB.';
$CIDRAM['lang']['config_rate_limiting_max_requests'] = 'จำนวนคำขอสูงสุดที่อนุญาตภายในระยะเวลาที่ได้รับอนุญาตก่อนที่อัตราการ จำกัด คำขอในอนาคต. ค่า 0 จะปิดใช้งานการ จำกัด อัตราค่าบริการประเภทนี้. ค่าเริ่มต้น = 0.';
$CIDRAM['lang']['config_rate_limiting_precision_ipv4'] = 'ความแม่นยำในการใช้สำหรับการติดตามการใช้งาน IPv4. ค่าจะสะท้อนขนาดบล็อก CIDR. ตั้งค่าเป็น 32 สำหรับความแม่นยำสูงสุด. ค่าเริ่มต้น = 32.';
$CIDRAM['lang']['config_rate_limiting_precision_ipv6'] = 'ความแม่นยำในการใช้สำหรับการติดตามการใช้งาน IPv6. ค่าจะสะท้อนขนาดบล็อก CIDR. ตั้งค่าเป็น 128 สำหรับความแม่นยำสูงสุด ค่าเริ่มต้น = 128.';
$CIDRAM['lang']['config_recaptcha_api'] = 'API ใดที่จะใช้? V2 หรือ Invisible?';
$CIDRAM['lang']['config_recaptcha_expiry'] = 'จำนวนชั่วโมงที่ต้องจำอินสแตนซ์ reCAPTCHA.';
$CIDRAM['lang']['config_recaptcha_lockip'] = 'ล็อค reCAPTCHA ด้วย IP หรือไม่?';
$CIDRAM['lang']['config_recaptcha_lockuser'] = 'ล็อค reCAPTCHA ด้วยผู้ใช้หรือไม่?';
$CIDRAM['lang']['config_recaptcha_logfile'] = 'บันทึกความพยายาม reCAPTCHA ทั้งหมดหรือไม่? ถ้าใช่ ระบุชื่อที่จะใช้สำหรับไฟล์บันทึก. ถ้าไม่ ปล่อยให้ตัวแปรนี้ว่างเปล่า.';
$CIDRAM['lang']['config_recaptcha_secret'] = 'ค่านี้ควรสอดคล้องกับ "secret key" สำหรับ reCAPTCHA ของคุณ และสามารถดูได้จากหน้าแดชบอร์ด reCAPTCHA.';
$CIDRAM['lang']['config_recaptcha_signature_limit'] = 'จำนวนลายเซ็นสูงสุดที่อนุญาตให้เรียกใช้เมื่อมีการนำเสนอกรณี reCAPTCHA. ค่าเริ่มต้น = 1. หากเกินจำนวนนี้สำหรับคำขอใด ๆ จะไม่มีการนำเสนอกรณี reCAPTCHA.';
$CIDRAM['lang']['config_recaptcha_sitekey'] = 'ค่านี้ควรสอดคล้องกับ "site key" สำหรับ reCAPTCHA ของคุณ และสามารถดูได้จากหน้าแดชบอร์ด reCAPTCHA.';
$CIDRAM['lang']['config_recaptcha_usemode'] = 'กำหนดว่า CIDRAM ควรใช้ reCAPTCHA อย่างไร (ดูที่เอกสาร).';
$CIDRAM['lang']['config_signatures_block_bogons'] = 'ปิดกั้น CIDR bogon/ชาวอังคาร? หากคุณคาดหวังว่าจะเชื่อมต่อกับเว็บไซต์ของคุณจากภายในเครือข่ายท้องถิ่นของคุณ จาก localhost หรือจาก LAN ของคุณ คำสั่งนี้ควรตั้งค่าเป็น false. มิฉะนั้น ถ้าคุณไม่คาดหวังการเชื่อมต่อเหล่านี้ คำสั่งนี้ควรตั้งค่าเป็น true.';
$CIDRAM['lang']['config_signatures_block_cloud'] = 'ปิดกั้น CIDR เป็นของ webhosting หรือบริการคลาวด์? ถ้าคุณทำงานบริการ API จากเว็บไซต์ของคุณหรือถ้าคุณคาดหวังเว็บไซต์อื่นเพื่อเชื่อมต่อกับเว็บไซต์ของคุณ คำสั่งนี้ควรตั้งค่าเป็น false. มิฉะนั้น คำสั่งนี้ควรตั้งค่าเป็น true.';
$CIDRAM['lang']['config_signatures_block_generic'] = 'ปิดกั้น CIDR ที่อยู่ในบัญชีดำ? นี้ครอบคลุมลายเซ็นใดไม่ได้ทำเครื่องหมายของหมวดหมู่ที่เฉพาะเจาะจง.';
$CIDRAM['lang']['config_signatures_block_legal'] = 'ปิดกั้น CIDR เพื่อตอบสนองต่อข้อผูกมัดทางกฎหมายหรือไม่? คำสั่งนี้ไม่ควรได้ตามปกติมีผลใด ๆ เนื่องจาก CIDRAM ไม่ได้เชื่อมโยง CIDR กับ "ข้อผูกมัดทางกฎหมาย" ตามค่าเริ่มต้น แต่ยังคงมีอยู่เป็นมาตรการควบคุมเพิ่มเติมเพื่อประโยชน์ของไฟล์ลายเซ็นที่กำหนดเองหรือโมดูลที่อาจมีเหตุผลทางกฎหมาย.';
$CIDRAM['lang']['config_signatures_block_malware'] = 'ปิดกั้น IP ที่เกี่ยวข้องกับมัลแวร์หรือไม่? ซึ่งรวมถึงเซิร์ฟเวอร์ C&C เครื่องที่ติดเชื้อ เครื่องที่เกี่ยวข้องกับการแจกจ่ายมัลแวร์ ฯลฯ.';
$CIDRAM['lang']['config_signatures_block_proxies'] = 'ปิดกั้น CIDR ที่ระบุว่าเป็นของบริการพร็อกซีหรือ VPN หรือไม่? หากผู้ใช้ของคุณต้องการเข้าถึงเว็บไซต์ของคุณจากบริการพร็อกซีและ VPN ควรตั้งค่าเป็น false. มิฉะนั้น ถ้าคุณไม่ต้องการบริการพร็อกซี่หรือ VPN ควรตั้งค่าเป็น true เพื่อเพิ่มความปลอดภัย.';
$CIDRAM['lang']['config_signatures_block_spam'] = 'ปิดกั้น CIDR ที่เกี่ยวข้องกับสแปมหรือไม่? จนกว่าคุณจะประสบปัญหาเมื่อทำเช่นนั้น โดยทั่วไป ควรตั้งค่าเป็น true เสมอ.';
$CIDRAM['lang']['config_signatures_default_tracktime'] = 'เท่าไหร่วินาทีที่การติดตาม IP ที่ห้ามโดยโมดูล? ค่าเริ่มต้น = 604800 (1 สัปดาห์).';
$CIDRAM['lang']['config_signatures_infraction_limit'] = 'จำนวนสูงสุดของการละเมิดที่ IP ได้รับอนุญาตให้เกิดขึ้นก่อนที่จะถูกห้ามโดยการติดตาม IP. ค่าเริ่มต้น = 10.';
$CIDRAM['lang']['config_signatures_ipv4'] = 'รายการคั่นด้วยจุลภาคของไฟล์ลายเซ็น IPv4 ที่ CIDRAM ควรพยายามแยกวิเคราะห์.';
$CIDRAM['lang']['config_signatures_ipv6'] = 'รายการคั่นด้วยจุลภาคของไฟล์ลายเซ็น IPv6 ที่ CIDRAM ควรพยายามแยกวิเคราะห์.';
$CIDRAM['lang']['config_signatures_modules'] = 'รายการคั่นด้วยจุลภาคของไฟล์โมดูลให้โหลดหลังจากตรวจแยกวิเคราะห์ลายเซ็น IPv4/IPv6.';
$CIDRAM['lang']['config_signatures_track_mode'] = 'เมื่อควรการละเมิดจะนับนับ? False = เมื่อ IP ถูกบล็อกโดยโมดูล. True = เมื่อ IP ถูกบล็อกด้วยเหตุผลใด.';
$CIDRAM['lang']['config_template_data_Magnification'] = 'การขยายตัวอักษร. ค่าเริ่มต้น = 1.';
$CIDRAM['lang']['config_template_data_css_url'] = 'URL ไฟล์ CSS สำหรับธีมที่กำหนดเอง.';
$CIDRAM['lang']['config_template_data_theme'] = 'ธีมเริ่มต้นที่จะใช้สำหรับ CIDRAM.';
$CIDRAM['lang']['confirm_action'] = 'คุณแน่ใจหรือไม่ว่าต้องการ "%s"?';
$CIDRAM['lang']['field_2fa'] = 'โค้ด 2FA';
$CIDRAM['lang']['field_Request_Method'] = 'ขอวิธีการ';
$CIDRAM['lang']['field_activate'] = 'เปิดใช้งาน';
$CIDRAM['lang']['field_add_more_conditions'] = 'เพิ่มเงื่อนไขเพิ่มเติม';
$CIDRAM['lang']['field_banned'] = 'ถูกห้าม';
$CIDRAM['lang']['field_blocked'] = 'ถูกบล็อก';
$CIDRAM['lang']['field_clear'] = 'ล้าง';
$CIDRAM['lang']['field_clear_all'] = 'ล้างทั้งหมด';
$CIDRAM['lang']['field_clickable_link'] = 'ลิงก์คลิกได้';
$CIDRAM['lang']['field_component'] = 'คอมโพเนนต์';
$CIDRAM['lang']['field_confirm'] = 'ยืนยัน';
$CIDRAM['lang']['field_create_new_account'] = 'สร้างบัญชีใหม่';
$CIDRAM['lang']['field_deactivate'] = 'ปิดใช้งาน';
$CIDRAM['lang']['field_delete'] = 'ลบ';
$CIDRAM['lang']['field_delete_account'] = 'ลบบัญชี';
$CIDRAM['lang']['field_download_file'] = 'ดาวน์โหลด';
$CIDRAM['lang']['field_edit_file'] = 'เปลี่ยนแปลง';
$CIDRAM['lang']['field_expiry'] = 'หมดอายุ';
$CIDRAM['lang']['field_false'] = 'False (เท็จ)';
$CIDRAM['lang']['field_file'] = 'ไฟล์';
$CIDRAM['lang']['field_filename'] = 'ชื่อไฟล์: ';
$CIDRAM['lang']['field_filetype_directory'] = 'ไดเรกทอรี';
$CIDRAM['lang']['field_filetype_info'] = 'ไฟล์ {EXT}';
$CIDRAM['lang']['field_filetype_unknown'] = 'ไม่รู้จัก';
$CIDRAM['lang']['field_include'] = 'รวมฟิลด์ที่ว่างเปล่า';
$CIDRAM['lang']['field_infractions'] = 'การละเมิด';
$CIDRAM['lang']['field_install'] = 'ติดตั้ง';
$CIDRAM['lang']['field_ip_address'] = 'ที่อยู่ IP';
$CIDRAM['lang']['field_latest_version'] = 'รุ่นล่าสุด';
$CIDRAM['lang']['field_log_in'] = 'เข้าสู่ระบบ';
$CIDRAM['lang']['field_new_name'] = 'ชื่อใหม่:';
$CIDRAM['lang']['field_nonclickable_text'] = 'ข้อความที่ไม่สามารถคลิกได้';
$CIDRAM['lang']['field_ok'] = 'ตกลง';
$CIDRAM['lang']['field_omit'] = 'ซ่อนฟิลด์ที่ว่างเปล่า';
$CIDRAM['lang']['field_options'] = 'ตัวเลือก';
$CIDRAM['lang']['field_password'] = 'รหัสผ่าน';
$CIDRAM['lang']['field_permissions'] = 'สิทธิ์';
$CIDRAM['lang']['field_range'] = 'พิสัย (เริ่ม – สิ้นสุด)';
$CIDRAM['lang']['field_reasonmessage'] = 'ทำไมถูกบล็อก (ละเอียด)';
$CIDRAM['lang']['field_rename_file'] = 'เปลี่ยนชื่อ';
$CIDRAM['lang']['field_reset'] = 'รีเซ็ต';
$CIDRAM['lang']['field_set_new_password'] = 'ตั้งรหัสผ่านใหม่';
$CIDRAM['lang']['field_size'] = 'ขนาดรวม: ';
$CIDRAM['lang']['field_size_GB'] = 'GB';
$CIDRAM['lang']['field_size_KB'] = 'KB';
$CIDRAM['lang']['field_size_MB'] = 'MB';
$CIDRAM['lang']['field_size_TB'] = 'TB';
$CIDRAM['lang']['field_size_bytes'] = 'ไบต์';
$CIDRAM['lang']['field_status'] = 'สถานะ';
$CIDRAM['lang']['field_system_timezone'] = 'ใช้เขตเวลาเริ่มต้นของระบบ.';
$CIDRAM['lang']['field_tracking'] = 'การติดตาม';
$CIDRAM['lang']['field_true'] = 'True (จริง)';
$CIDRAM['lang']['field_ualc'] = 'ตัวแทนผู้ใช้ (กรณีที่ต่ำกว่า)';
$CIDRAM['lang']['field_uninstall'] = 'ถอนการติดตั้ง';
$CIDRAM['lang']['field_update'] = 'อัปเดต';
$CIDRAM['lang']['field_update_all'] = 'อัพเดททั้งสิ้น';
$CIDRAM['lang']['field_upload_file'] = 'อัปโหลดไฟล์ใหม่';
$CIDRAM['lang']['field_username'] = 'ชื่อผู้ใช้';
$CIDRAM['lang']['field_verify'] = 'ตรวจสอบ';
$CIDRAM['lang']['field_verify_all'] = 'ตรวจสอบทั้งหมด';
$CIDRAM['lang']['field_your_version'] = 'เวอร์ชั่นของคุณ';
$CIDRAM['lang']['header_login'] = 'เข้าสู่ระบบเพื่อดำเนินการต่อ.';
$CIDRAM['lang']['label_active_config_file'] = 'ไฟล์การกำหนดค่าที่ใช้งานอยู่: ';
$CIDRAM['lang']['label_actual'] = 'ปัจจุบัน';
$CIDRAM['lang']['label_aux_actBlk'] = 'ปิดกั้น';
$CIDRAM['lang']['label_aux_actByp'] = 'ข้าม';
$CIDRAM['lang']['label_aux_actGrl'] = 'รายการเทา';
$CIDRAM['lang']['label_aux_actWhl'] = 'รายการขาว';
$CIDRAM['lang']['label_aux_create_new_rule'] = 'สร้างกฎใหม่';
$CIDRAM['lang']['label_aux_logic_all'] = 'เพื่อเรียกกฎ เงื่อนไขทั้งหมดต้องเป็นได้พบ.';
$CIDRAM['lang']['label_aux_logic_any'] = 'เงื่อนไขใด ๆ "เท่ากับ" (=) อาจเรียกใช้กฎได้ตราบใดที่เงื่อนไข "ไม่เท่ากับ" (≠) ทั้งหมดจะได้รับการปฏิบัติตาม.';
$CIDRAM['lang']['label_aux_menu_action'] = 'หากเป็นไปตามเงื่อนไขให้%sคำขอ.';
$CIDRAM['lang']['label_aux_menu_method'] = 'ใช้%sเพื่อทดสอบเงื่อนไข.';
$CIDRAM['lang']['label_aux_mtdReg'] = 'นิพจน์ปกติ';
$CIDRAM['lang']['label_aux_mtdStr'] = 'เปรียบเทียบสตริงโดยตรง';
$CIDRAM['lang']['label_aux_mtdWin'] = 'อักขระตัวแทนในรูปแบบ Windows ';
$CIDRAM['lang']['label_aux_name'] = 'ชื่อกฎใหม่:';
$CIDRAM['lang']['label_aux_reason'] = 'สาเหตุที่ผู้ใช้ถูกบล็อก:';
$CIDRAM['lang']['label_backup_location'] = 'Repository สถานที่สำรอง (ในกรณีฉุกเฉิน หรือถ้าทุกอย่างล้มเหลว):';
$CIDRAM['lang']['label_banned'] = 'คำขอถูกแบน';
$CIDRAM['lang']['label_blocked'] = 'คำขอถูกบล็อก';
$CIDRAM['lang']['label_branch'] = 'สาขาเสถียรล่าสุด:';
$CIDRAM['lang']['label_check_aux'] = 'ทดสอบกับกฎเสริม.';
$CIDRAM['lang']['label_check_modules'] = 'ทดสอบกับโมดูลด้วย.';
$CIDRAM['lang']['label_cidram'] = 'รุ่น CIDRAM ในการใช้งาน:';
$CIDRAM['lang']['label_clientinfo'] = 'ข้อมูลผู้ใช้:';
$CIDRAM['lang']['label_displaying'] = 'แสดง <span class="txtRd">%s</span> รายการ.';
$CIDRAM['lang']['label_displaying_that_cite'] = 'แสดง <span class="txtRd">%1$s</span> รายการที่อ้างอิง "%2$s".';
$CIDRAM['lang']['label_expected'] = 'ที่คาดหวัง';
$CIDRAM['lang']['label_expires'] = 'หมดอายุ: ';
$CIDRAM['lang']['label_false_positive_risk'] = 'ความเสี่ยงสำหรับการบวกเท็จ: ';
$CIDRAM['lang']['label_fmgr_cache_data'] = 'ข้อมูลแคชและไฟล์ชั่วคราว';
$CIDRAM['lang']['label_fmgr_disk_usage'] = 'เนื้อที่ดิสก์ที่ CIDRAM ใช้: ';
$CIDRAM['lang']['label_fmgr_free_space'] = 'พื้นที่ว่างในดิสก์: ';
$CIDRAM['lang']['label_fmgr_other_sig'] = 'กฎและไฟล์ลายเซ็นอื่น ๆ, ฯลฯ';
$CIDRAM['lang']['label_fmgr_safety'] = 'กลไกความปลอดภัย';
$CIDRAM['lang']['label_fmgr_total_disk_usage'] = 'เนื้อที่ดิสก์ที่ใช้ทั้งหมด: ';
$CIDRAM['lang']['label_fmgr_total_space'] = 'เนื้อที่ดิสก์ทั้งหมด: ';
$CIDRAM['lang']['label_fmgr_updates_metadata'] = 'เมตาดาต้าสำหรับอัพเดตคอมโพเนนต์';
$CIDRAM['lang']['label_hide'] = 'ปิดบัง';
$CIDRAM['lang']['label_hide_hash_table'] = 'ซ่อนตารางแฮช';
$CIDRAM['lang']['label_ignore'] = 'ไม่สนใจเรื่องนี้';
$CIDRAM['lang']['label_never'] = 'ไม่เคย';
$CIDRAM['lang']['label_os'] = 'ระบบปฏิบัติการในการใช้งาน:';
$CIDRAM['lang']['label_other'] = 'อื่น ๆ';
$CIDRAM['lang']['label_other-ActiveIPv4'] = 'ไฟล์ลายเซ็นที่ใช้งานอยู่ของ IPv4';
$CIDRAM['lang']['label_other-ActiveIPv6'] = 'ไฟล์ลายเซ็นที่ใช้งานอยู่ของ IPv6';
$CIDRAM['lang']['label_other-ActiveModules'] = 'โมดูลที่ใช้งานอยู่';
$CIDRAM['lang']['label_other-Since'] = 'วันที่เริ่มต้น';
$CIDRAM['lang']['label_php'] = 'รุ่น PHP ในการใช้งาน:';
$CIDRAM['lang']['label_reCAPTCHA'] = 'ความพยายามในการ reCAPTCHA';
$CIDRAM['lang']['label_results'] = 'ผล (%s ใน – %s ปฏิเสธ – %s ได้รับการยอมรับ – %s รวม – %s ออก):';
$CIDRAM['lang']['label_sapi'] = 'SAPI ในการใช้งาน:';
$CIDRAM['lang']['label_show'] = 'แสดง';
$CIDRAM['lang']['label_show_by_origin'] = 'แสดงตามแหล่งที่มา';
$CIDRAM['lang']['label_show_hash_table'] = 'แสดงตารางแฮช';
$CIDRAM['lang']['label_signature_type'] = 'ประเภทลายเซ็น:';
$CIDRAM['lang']['label_stable'] = 'เสถียรล่าสุด:';
$CIDRAM['lang']['label_sysinfo'] = 'ข้อมูลระบบ:';
$CIDRAM['lang']['label_tests'] = 'การทดสอบ:';
$CIDRAM['lang']['label_total'] = 'ทั้งหมด';
$CIDRAM['lang']['label_unignore'] = 'อย่าไม่สนใจเรื่องนี้';
$CIDRAM['lang']['label_unstable'] = 'ไม่เสถียรล่าสุด:';
$CIDRAM['lang']['label_used_with'] = 'ใช้กับ: ';
$CIDRAM['lang']['label_your_ip'] = 'IP ของคุณ:';
$CIDRAM['lang']['label_your_ua'] = 'UA ของคุณ:';
$CIDRAM['lang']['link_accounts'] = 'บัญชี';
$CIDRAM['lang']['link_aux'] = 'กฎเสริม';
$CIDRAM['lang']['link_cache_data'] = 'ข้อมูลแคช';
$CIDRAM['lang']['link_cidr_calc'] = 'เครื่องคิดเลข CIDR';
$CIDRAM['lang']['link_config'] = 'การกำหนดค่า';
$CIDRAM['lang']['link_documentation'] = 'เอกสาร';
$CIDRAM['lang']['link_file_manager'] = 'ตัวจัดการไฟล์';
$CIDRAM['lang']['link_home'] = 'โฮมเพจ';
$CIDRAM['lang']['link_ip_aggregator'] = 'ตัวรวบรวม IP';
$CIDRAM['lang']['link_ip_test'] = 'ทดสอบ IP';
$CIDRAM['lang']['link_ip_tracking'] = 'การติดตาม IP';
$CIDRAM['lang']['link_logs'] = 'บันทึก';
$CIDRAM['lang']['link_range'] = 'ตารางช่วง';
$CIDRAM['lang']['link_sections_list'] = 'รายการส่วน';
$CIDRAM['lang']['link_statistics'] = 'สถิติ';
$CIDRAM['lang']['link_textmode'] = 'การจัดรูปแบบข้อความ: <a href="%1$sfalse%2$s">ง่าย</a> – <a href="%1$strue%2$s">แฟนซี</a> – <a href="%1$stally%2$s">นับ</a>';
$CIDRAM['lang']['link_updates'] = 'อัปเดต';
$CIDRAM['lang']['logs_logfile_doesnt_exist'] = 'ไฟล์บันทึกเลือกไม่มีอยู่จริง!';
$CIDRAM['lang']['logs_no_logfile_selected'] = 'ไม่มีไฟล์บันทึกเลือก.';
$CIDRAM['lang']['logs_no_logfiles_available'] = 'ไม่มีไฟล์บันทึกใช้ได้.';
$CIDRAM['lang']['max_login_attempts_exceeded'] = 'จำนวนสูงสุดความพยายามเข้าสู่ระบบเกิน; ปฏิเสธการเข้าใช้.';
$CIDRAM['lang']['previewer_days'] = 'วัน';
$CIDRAM['lang']['previewer_hours'] = 'ชั่วโมง';
$CIDRAM['lang']['previewer_minutes'] = 'นาที';
$CIDRAM['lang']['previewer_months'] = 'เดือน';
$CIDRAM['lang']['previewer_seconds'] = 'วินาที';
$CIDRAM['lang']['previewer_weeks'] = 'สัปดาห์';
$CIDRAM['lang']['previewer_years'] = 'ปี';
$CIDRAM['lang']['response_2fa_invalid'] = 'ป้อนรหัส 2FA ไม่ถูกต้อง รับรองความถูกต้องล้มเหลว.';
$CIDRAM['lang']['response_2fa_valid'] = 'การตรวจสอบสิทธิ์ทำสำเร็จแล้ว.';
$CIDRAM['lang']['response_accounts_already_exists'] = 'บัญชีด้วยนั่นเองชื่อผู้ใช้มีอยู่แล้ว!';
$CIDRAM['lang']['response_accounts_created'] = 'บัญชีสำเร็จแล้วสร้าง!';
$CIDRAM['lang']['response_accounts_deleted'] = 'บัญชีสำเร็จแล้วลบ!';
$CIDRAM['lang']['response_accounts_doesnt_exist'] = 'บัญชีไม่มีอยู่จริง.';
$CIDRAM['lang']['response_accounts_password_updated'] = 'รหัสผ่านสำเร็จแล้วอัปเดต!';
$CIDRAM['lang']['response_activated'] = 'สำเร็จแล้วเปิดใช้งาน.';
$CIDRAM['lang']['response_activation_failed'] = 'ล้มเหลวเปิดใช้งาน!';
$CIDRAM['lang']['response_aux_none'] = 'ขณะนี้ยังไม่มีกฎเสริมใด ๆ.';
$CIDRAM['lang']['response_aux_rule_created_successfully'] = 'กฎเสริมใหม่ "%s" ถูกสร้าง.';
$CIDRAM['lang']['response_aux_rule_deleted_successfully'] = 'กฎเสริม "%s" ถูกลบแล้ว.';
$CIDRAM['lang']['response_checksum_error'] = 'ข้อผิดพลาด checksum! ไฟล์ถูกปฏิเสธ!';
$CIDRAM['lang']['response_component_successfully_installed'] = 'คอมโพเนนต์สำเร็จแล้วในการติดตั้ง.';
$CIDRAM['lang']['response_component_successfully_uninstalled'] = 'คอมโพเนนต์สำเร็จแล้วถอนการติดตั้ง.';
$CIDRAM['lang']['response_component_successfully_updated'] = 'คอมโพเนนต์สำเร็จแล้วอัปเดต.';
$CIDRAM['lang']['response_component_uninstall_error'] = 'เกิดขึ้นผิดพลาดขณะพยายามถอนการติดตั้งคอมโพเนนต์.';
$CIDRAM['lang']['response_configuration_updated'] = 'การกำหนดค่าสำเร็จแล้วอัปเดต.';
$CIDRAM['lang']['response_deactivated'] = 'สำเร็จแล้วปิดใช้งาน.';
$CIDRAM['lang']['response_deactivation_failed'] = 'ล้มเหลวปิดใช้งาน!';
$CIDRAM['lang']['response_delete_error'] = 'ล้มเหลวลบ!';
$CIDRAM['lang']['response_directory_deleted'] = 'ไดเรกทอรีสำเร็จแล้วลบ!';
$CIDRAM['lang']['response_directory_renamed'] = 'ไดเรกทอรีสำเร็จแล้วเปลี่ยนชื่อ!';
$CIDRAM['lang']['response_error'] = 'ข้อผิดพลาด';
$CIDRAM['lang']['response_failed_to_install'] = 'การติดตั้งล้มเหลว!';
$CIDRAM['lang']['response_failed_to_update'] = 'การอัพเดทล้มเหลว!';
$CIDRAM['lang']['response_file_deleted'] = 'ไฟล์สำเร็จแล้วลบ!';
$CIDRAM['lang']['response_file_edited'] = 'ไฟล์สำเร็จแล้วเปลี่ยนแปลง!';
$CIDRAM['lang']['response_file_renamed'] = 'ไฟล์สำเร็จแล้วเปลี่ยนชื่อ!';
$CIDRAM['lang']['response_file_uploaded'] = 'ไฟล์สำเร็จแล้วอัปโหลด!';
$CIDRAM['lang']['response_login_invalid_password'] = 'ความล้มเหลวในการเข้าสู่ระบบ! รหัสผ่านไม่ถูกต้อง!';
$CIDRAM['lang']['response_login_invalid_username'] = 'ความล้มเหลวในการเข้าสู่ระบบ! ชื่อผู้ใช้ไม่มีอยู่จริง!';
$CIDRAM['lang']['response_login_password_field_empty'] = 'รหัสผ่านฟิลด์ว่างเปล่า!';
$CIDRAM['lang']['response_login_username_field_empty'] = 'ชื่อผู้ใช้ฟิลด์ว่างเปล่า!';
$CIDRAM['lang']['response_login_wrong_endpoint'] = 'จุดเชื่อมต่อ ไม่ถูกต้อง!';
$CIDRAM['lang']['response_no'] = 'ไม่ได้';
$CIDRAM['lang']['response_possible_problem_found'] = 'พบปัญหาที่เป็นไปได้.';
$CIDRAM['lang']['response_rename_error'] = 'ล้มเหลวเปลี่ยนชื่อ!';
$CIDRAM['lang']['response_sanity_1'] = 'ไฟล์มีเนื้อหาที่ไม่คาดคิด! ไฟล์ถูกปฏิเสธ!';
$CIDRAM['lang']['response_statistics_cleared'] = 'สถิติลบแล้ว';
$CIDRAM['lang']['response_tracking_cleared'] = 'การติดตามถูกล้าง.';
$CIDRAM['lang']['response_updates_already_up_to_date'] = 'อัปเดตแล้ว.';
$CIDRAM['lang']['response_updates_not_installed'] = 'คอมโพเนนต์ไม่ได้ติดตั้ง!';
$CIDRAM['lang']['response_updates_not_installed_php'] = 'คอมโพเนนต์ไม่ได้ติดตั้ง (ต้องการ PHP &gt;= {V})!';
$CIDRAM['lang']['response_updates_outdated'] = 'ล้าสมัยแล้ว!';
$CIDRAM['lang']['response_updates_outdated_manually'] = 'ล้าสมัยแล้ว (โปรดอัปเดตด้วยตนเอง)!';
$CIDRAM['lang']['response_updates_outdated_php_version'] = 'ล้าสมัยแล้ว (ต้องการ PHP &gt;= {V})!';
$CIDRAM['lang']['response_updates_unable_to_determine'] = 'ไม่สามารถกำหนดได้.';
$CIDRAM['lang']['response_upload_error'] = 'ล้มเหลวอัปโหลด!';
$CIDRAM['lang']['response_verification_failed'] = 'การตรวจสอบล้มเหลว! คอมโพเนนต์อาจเสียหาย.';
$CIDRAM['lang']['response_verification_success'] = 'การตรวจสอบสำเร็จ! ไม่พบปัญหา.';
$CIDRAM['lang']['response_yes'] = 'ใช่แล้ว';
$CIDRAM['lang']['security_warning'] = 'เกิดปัญหาที่ไม่คาดคิดขณะประมวลผลคำขอของคุณ. กรุณาลองอีกครั้ง. หากปัญหายังคงมีอยู่โปรดติดต่อฝ่ายสนับสนุน.';
$CIDRAM['lang']['state_async_deny'] = 'สิทธิ์ไม่เพียงพอที่จะดำเนินการคำขอแบบอะซิงโครนัส. ลองเข้าสู่ระบบอีกครั้ง.';
$CIDRAM['lang']['state_cache_is_empty'] = 'แคชว่างเปล่า.';
$CIDRAM['lang']['state_complete_access'] = 'เข้าถึงได้อย่างสมบูรณ์';
$CIDRAM['lang']['state_component_is_active'] = 'คอมโพเนนต์ใช้งานอยู่.';
$CIDRAM['lang']['state_component_is_inactive'] = 'คอมโพเนนต์ไม่ใช้งาน.';
$CIDRAM['lang']['state_component_is_provisional'] = 'คอมโพเนนต์เป็นครั้งคราว.';
$CIDRAM['lang']['state_default_password'] = 'คำเตือน: ใช้ค่าเริ่มต้นรหัสผ่าน!';
$CIDRAM['lang']['state_email_sent'] = 'ส่งอีเมลสำเร็จ "%s" แล้ว.';
$CIDRAM['lang']['state_failed_missing'] = 'ภารกิจล้มเหลวเนื่องจากคอมโพเนนต์ที่จำเป็นไม่พร้อมใช้งาน.';
$CIDRAM['lang']['state_ignored'] = 'จะถูกละเลย';
$CIDRAM['lang']['state_loading'] = 'กำลังโหลด ...';
$CIDRAM['lang']['state_loadtime'] = 'คำขอหน้าเสร็จสิ้นภายใน <span class="txtRd">%s</span> วินาที.';
$CIDRAM['lang']['state_logged_in'] = 'เข้าสู่ระบบ.';
$CIDRAM['lang']['state_logged_in_2fa_pending'] = 'เข้าสู่ระบบ + รอดำเนินการ 2FA.';
$CIDRAM['lang']['state_logged_out'] = 'ออกจากระบบ.';
$CIDRAM['lang']['state_logs_access_only'] = 'เข้าถึงบันทึกเท่านั้น';
$CIDRAM['lang']['state_maintenance_mode'] = 'คำเตือน: เปิดใช้งานโหมดการบำรุงรักษา!';
$CIDRAM['lang']['state_password_not_valid'] = 'คำเตือน: บัญชีนี้ไม่ได้ใช้รหัสผ่านถูกต้อง!';
$CIDRAM['lang']['state_risk_high'] = 'สูง';
$CIDRAM['lang']['state_risk_low'] = 'ต่ำ';
$CIDRAM['lang']['state_risk_medium'] = 'ปานกลาง';
$CIDRAM['lang']['state_sl_totals'] = 'ผลรวม (ลายเซ็น: <span class="txtRd">%s</span> – ส่วนลายเซ็น: <span class="txtRd">%s</span> – ไฟล์ลายเซ็น: <span class="txtRd">%s</span> – แท็กส่วนที่ไม่ซ้ำ: <span class="txtRd">%s</span>).';
$CIDRAM['lang']['state_tracking'] = 'ขณะนี้กำลังติดตาม %s IP.';
$CIDRAM['lang']['switch-hide-non-outdated-set-false'] = 'อย่าซ่อนไม่ใช่ล้าสมัย';
$CIDRAM['lang']['switch-hide-non-outdated-set-true'] = 'ซ่อนไม่ใช่ล้าสมัย';
$CIDRAM['lang']['switch-hide-unused-set-false'] = 'อย่าซ่อนไม่ได้ใช้';
$CIDRAM['lang']['switch-hide-unused-set-true'] = 'ซ่อนไม่ได้ใช้';
$CIDRAM['lang']['switch-tracking-aux-set-false'] = 'อย่าตรวจสอบกับกฎเสริม';
$CIDRAM['lang']['switch-tracking-aux-set-true'] = 'ตรวจสอบกับกฎเสริม';
$CIDRAM['lang']['switch-tracking-blocked-already-set-false'] = 'อย่าตรวจสอบกับไฟล์ลายเซ็น';
$CIDRAM['lang']['switch-tracking-blocked-already-set-true'] = 'ตรวจสอบกับไฟล์ลายเซ็น';
$CIDRAM['lang']['switch-tracking-hide-banned-blocked-set-false'] = 'อย่าซ่อน IP ที่ถูกแบน/ถูกบล็อก';
$CIDRAM['lang']['switch-tracking-hide-banned-blocked-set-true'] = 'ซ่อน IP ที่ถูกแบน/ถูกบล็อก';
$CIDRAM['lang']['tip_2fa_sent'] = 'อีเมลที่มีรหัส 2FA ถูกส่งไปยังที่อยู่อีเมลของคุณแล้ว.โปรดยืนยันรหัสนี้ด้านล่างเพื่อเข้าถึง front-end. หากคุณไม่ได้รับอีเมลนี้ ลองออกจากระบบ รอ 10 นาที และลงชื่อเข้าใช้อีกครั้งเพื่อรับอีเมลใหม่ที่มีรหัสใหม่.';
$CIDRAM['lang']['tip_accounts'] = 'สวัสดี {username}.<br />หน้าบัญชีช่วยให้คุณสามารถควบคุมผู้ที่สามารถเข้าถึง front-end ของ CIDRAM.';
$CIDRAM['lang']['tip_aux'] = 'สวัสดี {username}.<br />คุณสามารถใช้เพจนี้เพื่อสร้าง ลบ และแก้ไข กฎเสริมสำหรับ CIDRAM.';
$CIDRAM['lang']['tip_cache_data'] = 'สวัสดี {username}.<br />ที่นี่คุณสามารถตรวจสอบเนื้อหาของแคชได้.';
$CIDRAM['lang']['tip_cidr_calc'] = 'สวัสดี {username}.<br />เครื่องคิดเลข CIDR ช่วยให้คุณสามารถคำนวณค่า CIDR ของที่อยู่ IP.';
$CIDRAM['lang']['tip_condition_placeholder'] = 'ระบุค่าหรือเว้นว่างไว้เพื่อไม่สนใจ.';
$CIDRAM['lang']['tip_config'] = 'สวัสดี {username}.<br />หน้าการกำหนดค่าช่วยให้คุณสามารถแก้ไขการกำหนดค่าสำหรับ CIDRAM จาก front-end.';
$CIDRAM['lang']['tip_custom_ua'] = 'ใส่ user agent ที่นี่ (เป็นตัวเลือก).';
$CIDRAM['lang']['tip_donate'] = 'CIDRAM ให้บริการฟรี แต่ถ้าคุณต้องการบริจาคให้กับโครงการ คุณสามารถทำได้โดยคลิกที่ปุ่มบริจาค.';
$CIDRAM['lang']['tip_enter_ip_here'] = 'ใส่ IP ที่นี่.';
$CIDRAM['lang']['tip_enter_ips_here'] = 'ใส่ IP ที่นี่.';
$CIDRAM['lang']['tip_fe_cookie_warning'] = 'บันทึก: CIDRAM ใช้คุกกี้เพื่อตรวจสอบการเข้าสู่ระบบ. เมื่อคุณลงชื่อเข้าใช้คุณยินยอมให้คุกกี้สร้างขึ้นและจัดเก็บโดยเบราเซอร์ของคุณ.';
$CIDRAM['lang']['tip_file_manager'] = 'สวัสดี {username}.<br />ตัวจัดการไฟล์ ช่วยให้คุณสามารถลบบัญชี แก้ไข อัปโหลด และดาวน์โหลดไฟล์. ใช้ด้วยความระมัดระวัง (คุณสามารถทำลายการติดตั้งของคุณด้วยนี้).';
$CIDRAM['lang']['tip_home'] = 'สวัสดี {username}.<br />นี่คือโฮมเพจสำหรับ front-end ของ CIDRAM. เลือกลิงค์จากเมนูนำทางด้านซ้ายเพื่อดำเนินการต่อ.';
$CIDRAM['lang']['tip_ip_aggregator'] = 'สวัสดี {username}.<br />ตัวรวบรวม IP ช่วยให้คุณสามารถแสดง IP และ CIDR ในลักษณะที่เล็กที่สุดได้. ป้อนข้อมูลที่จะรวมและกด "ตกลง".';
$CIDRAM['lang']['tip_ip_test'] = 'สวัสดี {username}.<br />หน้าทดสอบ IP ช่วยให้คุณสามารถทดสอบว่ามีการบล็อกที่อยู่ IP หรือไม่โดยลายเซ็นที่ติดตั้งไว้ในปัจจุบัน.';
$CIDRAM['lang']['tip_ip_test_switches'] = '(เมื่อไม่ได้เลือก เฉพาะไฟล์ลายเซ็นจะได้รับการทดสอบ).';
$CIDRAM['lang']['tip_ip_tracking'] = 'สวัสดี {username}.<br />หน้าการติดตาม IPช่วยให้คุณสามารถตรวจสอบสถานะการติดตามของที่อยู่ IP เพื่อตรวจสอบว่าพวกเขาถูกห้ามหรือไม่ และลบล้างถูกห้าม/การติดตามถ้าคุณต้องการทำเช่นนั้น.';
$CIDRAM['lang']['tip_login'] = 'ค่าเริ่มต้นชื่อผู้ใช้: <span class="txtRd">admin</span> – ค่าเริ่มต้นรหัสผ่าน: <span class="txtRd">password</span>';
$CIDRAM['lang']['tip_logs'] = 'สวัสดี {username}.<br />เลือกไฟล์บันทึกจากรายการด้านล่างเพื่อดูเนื้อหาของไฟล์บันทึกนั้น.';
$CIDRAM['lang']['tip_pie_html'] = 'คลิกที่ชื่อองค์ประกอบเพื่อดูรายละเอียดเพิ่มเติม.';
$CIDRAM['lang']['tip_range'] = 'สวัสดี {username}.<br />หน้านี้แสดงข้อมูลสถิติพื้นฐานบางอย่างเกี่ยวกับช่วง IP ที่ครอบคลุมโดยไฟล์ลายเซ็นที่ใช้งานอยู่ในปัจจุบัน.';
$CIDRAM['lang']['tip_sections_list'] = 'สวัสดี {username}.<br />หน้านี้แสดงรายการส่วนที่มีอยู่ในไฟล์ลายเซ็นที่ใช้งานอยู่ในปัจจุบัน.';
$CIDRAM['lang']['tip_see_the_documentation'] = 'ดูที่<a href="https://github.com/CIDRAM/CIDRAM/blob/master/_docs/readme.en.md#SECTION6">เอกสาร</a>สำหรับข้อมูลเกี่ยวกับคำสั่งการกำหนดค่าต่างๆและวัตถุประสงค์ของพวกเขา.';
$CIDRAM['lang']['tip_statistics'] = 'สวัสดี {username}.<br />หน้านี้แสดงสถิติการใช้งานขั้นพื้นฐานเกี่ยวกับการติดตั้ง CIDRAM ของคุณ.';
$CIDRAM['lang']['tip_statistics_disabled'] = 'บันทึก: ขณะนี้การติดตามผลสถิติถูกปิดใช้งาน แต่สามารถเปิดใช้งานได้ผ่านทางหน้าการกำหนดค่า.';
$CIDRAM['lang']['tip_updates'] = 'สวัสดี {username}.<br />หน้าอัปเดตช่วยให้คุณสามารถติดตั้ง ถอนการติดตั้ง และอัปเดตคอมโพเนนต์ต่างๆของ CIDRAM (แพคเกจหลัก ลายเซ็น ไฟล์การแปล ฯลฯ).';
$CIDRAM['lang']['title_login'] = 'เข้าสู่ระบบ';
$CIDRAM['lang']['warning'] = 'คำเตือน:';
$CIDRAM['lang']['warning_php_1'] = 'เวอร์ชัน PHP ของคุณไม่ได้รับการสนับสนุนอีกต่อไป! ปรับปรุงขอแนะนำ!';
$CIDRAM['lang']['warning_php_2'] = 'เวอร์ชัน PHP ของคุณมีความเสี่ยงสูง! ปรับปรุงขอแนะนำ!';
$CIDRAM['lang']['warning_signatures_1'] = 'ไม่มีไฟล์ลายเซ็นที่ใช้งานอยู่!';

$CIDRAM['lang']['info_some_useful_links'] = 'ลิงก์ที่เป็นประโยชน์:<ul>
      <li><a href="https://github.com/CIDRAM/CIDRAM/issues">ปัญหา CIDRAM @ GitHub</a> – หน้าปัญหาสำหรับ CIDRAM (สนับสนุน ความช่วยเหลือ ฯลฯ).</li>
      <li><a href="https://wordpress.org/plugins/cidram/">CIDRAM @ WordPress.org</a> – ปลั๊กอิน WordPress สำหรับ CIDRAM.</li>
      <li><a href="https://bitbucket.org/macmathan/blocklists">macmathan/blocklists</a> – ประกอบด้วยรายการบล็อกและโมดูลที่เป็นตัวเลือกสำหรับ CIDRAM สำหรับสิ่งต่างๆเช่นการปิดกั้นบอทที่เป็นอันตราย ประเทศที่ไม่ต้องการ เบราว์เซอร์ที่ล้าสมัย ฯลฯ.</li>
      <li><a href="https://www.facebook.com/groups/2204685680/">Global PHP Group @ Facebook</a> – แหล่งเรียนรู้ PHP และการสนทนา.</li>
      <li><a href="https://php.earth/">PHP.earth</a> – แหล่งเรียนรู้ PHP และการสนทนา.</li>
      <li><a href="https://bgp.he.net/">Hurricane Electric BGP Toolkit</a> – รับ CIDR จาก ASN ยืนยันความสัมพันธ์ ASN ค้นพบ ASN ตามชื่อเครือข่าย ฯลฯ.</li>
      <li><a href="https://www.stopforumspam.com/forum/">ฟอรัม @ Stop Forum Spam</a> – ฟอรั่มการอภิปรายมีประโยชน์เกี่ยวกับหยุดสแปมในฟอรัม.</li>
      <li><a href="https://radar.qrator.net/">Radar โดย Qrator</a> – เครื่องมือมีประโยชน์สำหรับตรวจสอบการเชื่อมต่อ ASN รวมทั้งข้อมูลอื่น ๆ เกี่ยวกับ ASN.</li>
      <li><a href="http://www.ipdeny.com/ipblocks/">บล็อกประเทศ IP @ IPdeny</a> – บริการที่ยอดเยี่ยมและถูกต้องสำหรับการสร้างลายเซ็นสำหรับประเทศ.</li>
      <li><a href="https://www.google.com/transparencyreport/safebrowsing/malware/">Google Malware Dashboard</a> – แสดงรายงานเกี่ยวกับอัตราการติดเชื้อของมัลแวร์ใน ASN.</li>
      <li><a href="https://www.spamhaus.org/statistics/botnet-asn/">โครงการ Spamhaus</a> – แสดงรายงานเกี่ยวกับอัตราการติดเชื้อของ botnet ใน ASN.</li>
      <li><a href="https://www.abuseat.org/public/asn.html">รายการการปิดกั้นแบบคอมโพสิต @ Abuseat.org</a> – แสดงรายงานเกี่ยวกับอัตราการติดเชื้อของ botnet ใน ASN.</li>
      <li><a href="https://abuseipdb.com/">AbuseIPDB</a> – รักษาฐานข้อมูล IP เป็นอันตราย; จัดเตรียม API สำหรับตรวจสอบและรายงาน IP.</li>
      <li><a href="https://www.megarbl.net/index.php">MegaRBL.net</a> – เก็บรายชื่อผู้ส่งสแปมที่รู้จัก; มีประโยชน์สำหรับตรวจสอบกิจกรรมสแปม IP/ASN.</li>
      <li><a href="https://maikuolan.github.io/Vulnerability-Charts/">Vulnerability Charts (ชาร์ตเสี่ยง)</a> – แสดงรายการเวอร์ชันต่างๆแพคเกจที่ปลอดภัย/ไม่ปลอดภัย (HHVM, PHP, phpMyAdmin, Python, ฯลฯ).</li>
      <li><a href="https://maikuolan.github.io/Compatibility-Charts/">Compatibility Charts (ชาร์ตความเข้ากันได้)</a> – แสดงข้อมูลความเข้ากันได้ของแพคเกจต่างๆ (CIDRAM, phpMussel, ฯลฯ).</li>
    </ul>';

$CIDRAM['lang']['msg_template_2fa'] = '<center><p>สวัสดี %1$s.<br />
<br />
รหัส 2FA ของคุณสำหรับการลงชื่อเข้าใช้ CIDRAM front-end:</p>
<h1>%2$s</h1>
<p>รหัสนี้หมดอายุใน 10 นาที.</p></center>';
$CIDRAM['lang']['msg_subject_2fa'] = '2FA';
