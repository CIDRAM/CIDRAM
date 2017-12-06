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
 * This file: Thai language data for the front-end (last modified: 2017.12.06).
 */

/** Prevents execution from outside of CIDRAM. */
if (!defined('CIDRAM')) {
    die('[CIDRAM] This should not be accessed directly.');
}

$CIDRAM['lang']['bNav_home_logout'] = '<a href="?">โฮมเพจ</a> | <a href="?cidram-page=logout">ออกจากระบบ</a>';
$CIDRAM['lang']['bNav_logout'] = '<a href="?cidram-page=logout">ออกจากระบบ</a>';
$CIDRAM['lang']['config_general_ban_override'] = 'แทนที่ "forbid_on_block" เมื่อ "infraction_limit" ได้รับการเกินหรือไม่? เมื่อแทนที่: คำขอที่ถูกบล็อกกลับหน้าเปล่า (ไฟล์เทมเพลตจะไม่ถูกใช้). 200 = อย่าแทนที่ [ค่าเริ่มต้น]; 403 = อย่าแทนที่ด้วย "403 Forbidden"; 503 = อย่าแทนที่ด้วย "503 Service unavailable".';
$CIDRAM['lang']['config_general_default_algo'] = 'กำหนดว่าจะใช้อัลกอริทึมใดสำหรับรหัสผ่านและเซสชันในอนาคต. ตัวเลือก: PASSWORD_DEFAULT (ค่าเริ่มต้น), PASSWORD_BCRYPT, PASSWORD_ARGON2I (ต้องการ PHP >= 7.2.0).';
$CIDRAM['lang']['config_general_default_dns'] = 'รายการคั่นด้วยจุลภาคของเซิร์ฟเวอร์ DNS ใช้สำหรับการค้นหาชื่อโฮสต์. ค่าเริ่มต้น = "8.8.8.8,8.8.4.4" (Google DNS). คำเตือน: อย่าเปลี่ยนสิ่งนี้จนกว่าคุณจะรู้ว่าคุณกำลังทำอะไร!';
$CIDRAM['lang']['config_general_disable_cli'] = 'ปิดใช้งานโหมด CLI หรือไม่? โหมด CLI ถูกเปิดใช้งานตามค่าเริ่มต้น, แต่บางครั้งอาจรบกวนการทำงานของเครื่องมือทดสอบบางอย่าง (เช่น PHPUnit) และแอพพลิเคชั่น CLI อื่น ๆ. ถ้าคุณไม่จำเป็นต้องปิดใช้งานโหมด CLI คุณควรละเว้นคำสั่งนี้. False = เปิดใช้งานโหมด CLI [ค่าเริ่มต้น]; True = ปิดใช้งานโหมด CLI.';
$CIDRAM['lang']['config_general_disable_frontend'] = 'ปิดใช้งานการเข้าถึง front-end หรือไม่? การเข้าถึง front-end ทำให้ CIDRAM สามารถจัดการได้ดีขึ้น แต่ก็อาจเป็นความเสี่ยงด้านความปลอดภัยที่อาจเกิดขึ้นด้วย. ขอแนะนำให้จัดการ CIDRAM ผ่านทางแบ็คเอนด์เมื่อใดก็ตามที่เป็นไปได้ แต่จะมีการเข้าถึง front-end เมื่อไม่สามารถทำได้. โปรดปิดใช้งานหากคุณไม่ต้องการ. False = เปิดใช้งานการเข้าถึง front-end; True = ปิดการใช้งานการเข้าถึง front-end [ค่าเริ่มต้น].';
$CIDRAM['lang']['config_general_disable_webfonts'] = 'หยุดใช้ webfonts หรือไม่? True = หยุดใช้; False = ไม่หยุดใช้ [ค่าเริ่มต้น].';
$CIDRAM['lang']['config_general_emailaddr'] = 'หากต้องการคุณสามารถระบุที่อยู่อีเมลเพื่อมอบให้กับผู้ใช้เมื่อมีการปิดกั้น, สำหรับพวกเขาที่จะใช้เป็นจุดติดต่อสำหรับการสนับสนุนและให้ความช่วยเหลือเมื่อพวกเขากำลังบล็อกผิดพลาด. คำเตือน: ที่อยู่อีเมลใด ๆ ที่จัดหาให้ในที่นี้จะได้รับจากสแปมบอทและแครปเปอร์, ดังนั้นขอแนะนำให้คุณเลือกที่อยู่อีเมลที่ใช้แล้วทิ้งหรือไม่สำคัญ (นั่นคือ, คุณอาจไม่ต้องการใช้ที่อยู่อีเมลธุรกิจหลักหรือส่วนบุคคลหลักของคุณ).';
$CIDRAM['lang']['config_general_emailaddr_display_style'] = 'คุณต้องการที่อยู่อีเมลที่จะนำเสนอต่อผู้ใช้อย่างไร?';
$CIDRAM['lang']['config_general_forbid_on_block'] = 'ระบุส่วนหัวสำหรับ CIDRAM ที่จะตอบด้วยเมื่อบล็อกคำขอ.';
$CIDRAM['lang']['config_general_force_hostname_lookup'] = 'บังคับใช้การค้นหาชื่อโฮสต์? True = หยุดใช้; False = ไม่หยุดใช้ [ค่าเริ่มต้น]. การค้นหาชื่อโฮสต์จะดำเนินการตามปกติโดยพิจารณาจากความจำเป็น แต่สามารถบังคับให้คำขอทั้งหมด. นี้อาจเป็นประโยชน์สำหรับการให้ข้อมูลเพิ่มเติมในไฟล์บันทึก แต่อาจมีผลเสียต่อประสิทธิภาพเล็กน้อย.';
$CIDRAM['lang']['config_general_FrontEndLog'] = 'ไฟล์สำหรับบันทึก front-end ความพยายามเข้าสู่ระบบ. ระบุชื่อไฟล์หรือเว้นว่างไว้เพื่อปิดใช้งาน.';
$CIDRAM['lang']['config_general_ipaddr'] = 'ตำแหน่งของที่อยู่ IP สำหรับคำขอการเชื่อมต่อ (เป็นประโยชน์สำหรับบริการเช่น Cloudflare, ฯลฯ). ค่าเริ่มต้น = REMOTE_ADDR. คำเตือน: อย่าเปลี่ยนสิ่งนี้จนกว่าคุณจะรู้ว่าคุณกำลังทำอะไร!';
$CIDRAM['lang']['config_general_lang'] = 'ระบุภาษาค่าเริ่มต้นสำหรับ CIDRAM.';
$CIDRAM['lang']['config_general_logfile'] = 'ไฟล์มนุษย์สามารถอ่านได้สำหรับบันทึกทั้งหมดของพยายามเข้าถึงที่ถูกบล็อก. ระบุชื่อไฟล์หรือเว้นว่างไว้เพื่อปิดใช้งาน.';
$CIDRAM['lang']['config_general_logfileApache'] = 'ไฟล์ในสไตล์ Apache สำหรับบันทึกทั้งหมดของพยายามเข้าถึงที่ถูกบล็อก. ระบุชื่อไฟล์หรือเว้นว่างไว้เพื่อปิดใช้งาน.';
$CIDRAM['lang']['config_general_logfileSerialized'] = 'ไฟล์รูปแบบอนุกรมสำหรับบันทึกทั้งหมดของพยายามเข้าถึงที่ถูกบล็อก. ระบุชื่อไฟล์หรือเว้นว่างไว้เพื่อปิดใช้งาน.';
$CIDRAM['lang']['config_general_log_banned_ips'] = 'รวมคำขอที่ถูกบล็อกจากที่อยู่ IP ที่ถูกห้ามในไฟล์บันทึกหรือไม่? True = รวม [ค่าเริ่มต้น]; False = ไม่รวม.';
$CIDRAM['lang']['config_general_maintenance_mode'] = 'เปิดใช้โหมดการบำรุงรักษาหรือไม่? True = เปิดใช้งานได้; False = ไม่เปิดใช้งาน [ค่าเริ่มต้น]. ปิดใช้งานทุกอย่างอื่นที่ไม่ใช่ front-end. บางครั้งมีประโยชน์สำหรับการอัปเดต CMS, framework, ฯลฯ.';
$CIDRAM['lang']['config_general_max_login_attempts'] = 'จำนวนสูงสุดความพยายามเข้าสู่ระบบ.';
$CIDRAM['lang']['config_general_numbers'] = 'คุณต้องการตัวเลขที่จะแสดงอย่างไร? เลือกตัวอย่างที่ดูเหมือนถูกต้องที่สุด.';
$CIDRAM['lang']['config_general_protect_frontend'] = 'ระบุถ้าการป้องกันปกติให้บริการโดย CIDRAM ควรจะเป็นนำมาใช้กับ front-end. True = ใช้ [ค่าเริ่มต้น]; False = ไม่ใช้.';
$CIDRAM['lang']['config_general_search_engine_verification'] = 'ยืนยันคำขอจากเครื่องมือค้นหาหรือไม่? ยืนยันของเครื่องมือค้นหาจะทำให้แน่ใจว่าพวกเขาจะไม่ถูกแบนห้ามเนื่องจากเกินของขีดจำกัดการละเมิด (ห้ามของเครื่องมือค้นหาจากเว็บไซต์ของคุณมักจะมีผลเสียสำหรับการจัดอันดับของเครื่องมือค้นหาของคุณ, SEO, ฯลฯ). เมื่อได้ยืนยัน, เครื่องมือค้นหาสามารถบล็อกตามปกติ, แต่จะไม่ถูกห้าม. เมื่อไม่ยืนยัน, เป็นไปได้สำหรับพวกเขาเป็นห้ามเนื่องจากเกินขีดจำกัดการละเมิด. นอกจากนี้, การยืนยันเครื่องมือค้นหาให้การป้องกันต่อต้านคำขอเครื่องมือค้นหาของปลอมและต่อต้านองค์กร(คน, โปรแกรม, ฯลฯ)ที่อาจเป็นอันตรายที่แกล้งทำเป็นว่าเครื่องมือค้นหา (คำขอดังกล่าวจะถูกบล็อกเมื่อการยืนยันเครื่องมือค้นหาเปิดใช้งาน). True = เปิดใช้งานการยืนยันเครื่องมือค้นหา [ค่าเริ่มต้น]; False = ปิดใช้งานการยืนยันเครื่องมือค้นหา.';
$CIDRAM['lang']['config_general_silent_mode'] = 'ควร CIDRAM เปลี่ยนเส้นทางพยายามเข้าถึงที่ถูกบล็อกเงียบค่อนข้างมากกว่าการแสดงหน้า "ปฏิเสธการเข้าใช้" หรือไม่? ถ้าใช่, ระบุตำแหน่งเป้าหมาย. ถ้าไม่, ปล่อยให้ตัวแปรนี้ว่างเปล่า.';
$CIDRAM['lang']['config_general_statistics'] = 'ติดตามสถิติการใช้งาน CIDRAM? True = ติดตาม; False = ไม่ติดตาม [ค่าเริ่มต้น].';
$CIDRAM['lang']['config_general_timeFormat'] = 'รูปแบบสัญกรณ์สำหรับวันและเวลาใช้โดย CIDRAM. ตัวเลือกเพิ่มเติมอาจเพิ่มเมื่อมีการร้องขอ.';
$CIDRAM['lang']['config_general_timeOffset'] = 'เขตเวลาชดเชยในนาที.';
$CIDRAM['lang']['config_general_timezone'] = 'โซนเวลาของคุณ.';
$CIDRAM['lang']['config_general_truncate'] = 'ตัดทอนแฟ้มบันทึกเมื่อถึงขนาดที่กำหนดหรือไม่? ค่ามีขนาดสูงสุดในรูปแบบ B/KB/MB/GB/TB ที่แฟ้มบันทึกอาจโตขึ้นก่อนที่จะถูกตัดทอน. ค่าเริ่มต้นของ 0KB ปิดการตัดทอน (แฟ้มบันทึกสามารถเติบโตไปเรื่อย). หมายเหตุ: ถูกใช้ด้วยกับล็อกไฟล์แต่ละไฟล์! ขนาดของไฟล์บันทึกไม่ถือเป็นการรวมกัน.';
$CIDRAM['lang']['config_recaptcha_expiry'] = 'จำนวนชั่วโมงที่ต้องจำอินสแตนซ์ reCAPTCHA.';
$CIDRAM['lang']['config_recaptcha_lockip'] = 'ล็อค reCAPTCHA ด้วย IP หรือไม่?';
$CIDRAM['lang']['config_recaptcha_lockuser'] = 'ล็อค reCAPTCHA ด้วยผู้ใช้หรือไม่?';
$CIDRAM['lang']['config_recaptcha_logfile'] = 'บันทึกความพยายาม reCAPTCHA ทั้งหมดหรือไม่? ถ้าใช่, ระบุชื่อที่จะใช้สำหรับไฟล์บันทึก. ถ้าไม่, ปล่อยให้ตัวแปรนี้ว่างเปล่า.';
$CIDRAM['lang']['config_recaptcha_secret'] = 'ค่านี้ควรสอดคล้องกับ "secret key" สำหรับ reCAPTCHA ของคุณ, และสามารถดูได้จากหน้าแดชบอร์ด reCAPTCHA.';
$CIDRAM['lang']['config_recaptcha_signature_limit'] = 'จำนวนลายเซ็นสูงสุดที่อนุญาตให้เรียกใช้เมื่อมีการนำเสนอกรณี reCAPTCHA. ค่าเริ่มต้น = 1. หากเกินจำนวนนี้สำหรับคำขอใด ๆ จะไม่มีการนำเสนอกรณี reCAPTCHA.';
$CIDRAM['lang']['config_recaptcha_sitekey'] = 'ค่านี้ควรสอดคล้องกับ "site key" สำหรับ reCAPTCHA ของคุณ, และสามารถดูได้จากหน้าแดชบอร์ด reCAPTCHA.';
$CIDRAM['lang']['config_recaptcha_usemode'] = 'กำหนดว่า CIDRAM ควรใช้ reCAPTCHA อย่างไร (ดูที่เอกสาร).';
$CIDRAM['lang']['config_signatures_block_bogons'] = 'บล็อก CIDR bogon/ชาวอังคาร? หากคุณคาดหวังว่าจะเชื่อมต่อกับเว็บไซต์ของคุณจากภายในเครือข่ายท้องถิ่นของคุณ, จาก localhost, หรือจาก LAN ของคุณ, คำสั่งนี้ควรตั้งค่าเป็น false. มิฉะนั้น, ถ้าคุณไม่คาดหวังการเชื่อมต่อเหล่านี้, คำสั่งนี้ควรตั้งค่าเป็น true.';
$CIDRAM['lang']['config_signatures_block_cloud'] = 'บล็อก CIDR เป็นของ webhosting หรือบริการคลาวด์? ถ้าคุณทำงานบริการ API จากเว็บไซต์ของคุณหรือถ้าคุณคาดหวังเว็บไซต์อื่นเพื่อเชื่อมต่อกับเว็บไซต์ของคุณ, คำสั่งนี้ควรตั้งค่าเป็น false. มิฉะนั้น, คำสั่งนี้ควรตั้งค่าเป็น true.';
$CIDRAM['lang']['config_signatures_block_generic'] = 'บล็อก CIDR ที่อยู่ในบัญชีดำ? นี้ครอบคลุมลายเซ็นใดไม่ได้ทำเครื่องหมายของหมวดหมู่ที่เฉพาะเจาะจง.';
$CIDRAM['lang']['config_signatures_block_proxies'] = 'บล็อก CIDR ที่ระบุว่าเป็นของบริการพร็อกซีหรือไม่? หากผู้ใช้ของคุณต้องการเข้าถึงเว็บไซต์ของคุณจากบริการพร็อกซีแบบไม่ระบุตัวตน, ควรตั้งค่าเป็น false. มิฉะนั้น, ถ้าคุณไม่ต้องการบริการพร็อกซี่, ควรตั้งค่าเป็น true เพื่อเพิ่มความปลอดภัย.';
$CIDRAM['lang']['config_signatures_block_spam'] = 'บล็อก CIDR ที่เกี่ยวข้องกับสแปมหรือไม่? จนกว่าคุณจะประสบปัญหาเมื่อทำเช่นนั้น, โดยทั่วไป, ควรตั้งค่าเป็น true เสมอ.';
$CIDRAM['lang']['config_signatures_default_tracktime'] = 'เท่าไหร่วินาทีที่การติดตาม IP ที่ห้ามโดยโมดูล? ค่าเริ่มต้น = 604800 (1 สัปดาห์).';
$CIDRAM['lang']['config_signatures_infraction_limit'] = 'จำนวนสูงสุดของการละเมิดที่ IP ได้รับอนุญาตให้เกิดขึ้นก่อนที่จะถูกห้ามโดยการติดตาม IP. ค่าเริ่มต้น = 10.';
$CIDRAM['lang']['config_signatures_ipv4'] = 'รายการคั่นด้วยจุลภาคของไฟล์ลายเซ็น IPv4 ที่ CIDRAM ควรพยายามแยกวิเคราะห์.';
$CIDRAM['lang']['config_signatures_ipv6'] = 'รายการคั่นด้วยจุลภาคของไฟล์ลายเซ็น IPv6 ที่ CIDRAM ควรพยายามแยกวิเคราะห์.';
$CIDRAM['lang']['config_signatures_modules'] = 'รายการคั่นด้วยจุลภาคของไฟล์โมดูลให้โหลดหลังจากตรวจแยกวิเคราะห์ลายเซ็น IPv4/IPv6.';
$CIDRAM['lang']['config_signatures_track_mode'] = 'เมื่อควรการละเมิดจะนับนับ? False = เมื่อ IP ถูกบล็อกโดยโมดูล. True = เมื่อ IP ถูกบล็อกด้วยเหตุผลใด.';
$CIDRAM['lang']['config_template_data_css_url'] = 'URL ไฟล์ CSS สำหรับธีมที่กำหนดเอง.';
$CIDRAM['lang']['config_template_data_Magnification'] = 'การขยายตัวอักษร. ค่าเริ่มต้น = 1.';
$CIDRAM['lang']['config_template_data_theme'] = 'ธีมเริ่มต้นที่จะใช้สำหรับ CIDRAM.';
$CIDRAM['lang']['Extended Description: Bypasses'] = 'ไฟล์บายพาสลายเซ็นเริ่มต้นที่รวมอยู่ในแพคเกจหลัก.';
$CIDRAM['lang']['Extended Description: CIDRAM'] = 'แพคเกจหลัก (ไม่รวมลายเซ็น, เอกสาร, และการกำหนดค่า).';
$CIDRAM['lang']['field_activate'] = 'เปิดใช้งาน';
$CIDRAM['lang']['field_banned'] = 'ถูกห้าม';
$CIDRAM['lang']['field_blocked'] = 'ถูกบล็อก';
$CIDRAM['lang']['field_clear'] = 'ล้าง';
$CIDRAM['lang']['field_clear_all'] = 'ล้างทั้งหมด';
$CIDRAM['lang']['field_clickable_link'] = 'ลิงก์คลิกได้';
$CIDRAM['lang']['field_component'] = 'คอมโพเนนต์';
$CIDRAM['lang']['field_create_new_account'] = 'สร้างบัญชีใหม่';
$CIDRAM['lang']['field_deactivate'] = 'ปิดใช้งาน';
$CIDRAM['lang']['field_delete_account'] = 'ลบบัญชี';
$CIDRAM['lang']['field_delete_file'] = 'ลบ';
$CIDRAM['lang']['field_download_file'] = 'ดาวน์โหลด';
$CIDRAM['lang']['field_edit_file'] = 'เปลี่ยนแปลง';
$CIDRAM['lang']['field_expiry'] = 'หมดอายุ';
$CIDRAM['lang']['field_false'] = 'False (เท็จ)';
$CIDRAM['lang']['field_file'] = 'ไฟล์';
$CIDRAM['lang']['field_filename'] = 'ชื่อไฟล์: ';
$CIDRAM['lang']['field_filetype_directory'] = 'ไดเรกทอรี';
$CIDRAM['lang']['field_filetype_info'] = 'ไฟล์ {EXT}';
$CIDRAM['lang']['field_filetype_unknown'] = 'ไม่รู้จัก';
$CIDRAM['lang']['field_first_seen'] = 'เมื่อเห็นครั้งแรก';
$CIDRAM['lang']['field_infractions'] = 'การละเมิด';
$CIDRAM['lang']['field_install'] = 'ติดตั้ง';
$CIDRAM['lang']['field_ip_address'] = 'ที่อยู่ IP';
$CIDRAM['lang']['field_latest_version'] = 'รุ่นล่าสุด';
$CIDRAM['lang']['field_log_in'] = 'เข้าสู่ระบบ';
$CIDRAM['lang']['field_new_name'] = 'ชื่อใหม่:';
$CIDRAM['lang']['field_nonclickable_text'] = 'ข้อความที่ไม่สามารถคลิกได้';
$CIDRAM['lang']['field_ok'] = 'ตกลง';
$CIDRAM['lang']['field_options'] = 'ตัวเลือก';
$CIDRAM['lang']['field_password'] = 'รหัสผ่าน';
$CIDRAM['lang']['field_permissions'] = 'สิทธิ์';
$CIDRAM['lang']['field_range'] = 'พิสัย (เริ่ม – สิ้นสุด)';
$CIDRAM['lang']['field_rename_file'] = 'เปลี่ยนชื่อ';
$CIDRAM['lang']['field_reset'] = 'รีเซ็ต';
$CIDRAM['lang']['field_set_new_password'] = 'ตั้งรหัสผ่านใหม่';
$CIDRAM['lang']['field_size'] = 'ขนาดรวม: ';
$CIDRAM['lang']['field_size_bytes'] = 'ไบต์';
$CIDRAM['lang']['field_size_GB'] = 'GB';
$CIDRAM['lang']['field_size_KB'] = 'KB';
$CIDRAM['lang']['field_size_MB'] = 'MB';
$CIDRAM['lang']['field_size_TB'] = 'TB';
$CIDRAM['lang']['field_status'] = 'สถานะ';
$CIDRAM['lang']['field_system_timezone'] = 'ใช้เขตเวลาเริ่มต้นของระบบ.';
$CIDRAM['lang']['field_tracking'] = 'การติดตาม';
$CIDRAM['lang']['field_true'] = 'True (จริง)';
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
$CIDRAM['lang']['label_banned'] = 'คำขอถูกแบน';
$CIDRAM['lang']['label_blocked'] = 'คำขอถูกบล็อก';
$CIDRAM['lang']['label_branch'] = 'สาขาเสถียรล่าสุด:';
$CIDRAM['lang']['label_check_modules'] = 'ทดสอบกับโมดูลด้วย.';
$CIDRAM['lang']['label_cidram'] = 'รุ่น CIDRAM ในการใช้งาน:';
$CIDRAM['lang']['label_false_positive_risk'] = 'ความเสี่ยงสำหรับการบวกเท็จ: ';
$CIDRAM['lang']['label_fmgr_cache_data'] = 'ข้อมูลแคชและไฟล์ชั่วคราว';
$CIDRAM['lang']['label_fmgr_disk_usage'] = 'เนื้อที่ดิสก์ที่ CIDRAM ใช้: ';
$CIDRAM['lang']['label_fmgr_free_space'] = 'พื้นที่ว่างในดิสก์: ';
$CIDRAM['lang']['label_fmgr_total_disk_usage'] = 'เนื้อที่ดิสก์ที่ใช้ทั้งหมด: ';
$CIDRAM['lang']['label_fmgr_total_space'] = 'เนื้อที่ดิสก์ทั้งหมด: ';
$CIDRAM['lang']['label_fmgr_updates_metadata'] = 'เมตาดาต้าสำหรับอัพเดตคอมโพเนนต์';
$CIDRAM['lang']['label_hide'] = 'ปิดบัง';
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
$CIDRAM['lang']['label_stable'] = 'เสถียรล่าสุด:';
$CIDRAM['lang']['label_sysinfo'] = 'ข้อมูลระบบ:';
$CIDRAM['lang']['label_tests'] = 'การทดสอบ:';
$CIDRAM['lang']['label_total'] = 'ทั้งหมด';
$CIDRAM['lang']['label_unstable'] = 'ไม่เสถียรล่าสุด:';
$CIDRAM['lang']['link_accounts'] = 'บัญชี';
$CIDRAM['lang']['link_cidr_calc'] = 'เครื่องคิดเลข CIDR';
$CIDRAM['lang']['link_config'] = 'การกำหนดค่า';
$CIDRAM['lang']['link_documentation'] = 'เอกสาร';
$CIDRAM['lang']['link_file_manager'] = 'ตัวจัดการไฟล์';
$CIDRAM['lang']['link_home'] = 'โฮมเพจ';
$CIDRAM['lang']['link_ip_aggregator'] = 'ตัวรวบรวม IP';
$CIDRAM['lang']['link_ip_test'] = 'ทดสอบ IP';
$CIDRAM['lang']['link_ip_tracking'] = 'การติดตาม IP';
$CIDRAM['lang']['link_logs'] = 'บันทึก';
$CIDRAM['lang']['link_statistics'] = 'สถิติ';
$CIDRAM['lang']['link_textmode'] = 'การจัดรูปแบบข้อความ: <a href="%1$sfalse">ง่าย</a> – <a href="%1$strue">แฟนซี</a>';
$CIDRAM['lang']['link_updates'] = 'อัปเดต';
$CIDRAM['lang']['logs_logfile_doesnt_exist'] = 'ไฟล์บันทึกเลือกไม่มีอยู่จริง!';
$CIDRAM['lang']['logs_no_logfiles_available'] = 'ไม่มีไฟล์บันทึกใช้ได้.';
$CIDRAM['lang']['logs_no_logfile_selected'] = 'ไม่มีไฟล์บันทึกเลือก.';
$CIDRAM['lang']['max_login_attempts_exceeded'] = 'จำนวนสูงสุดความพยายามเข้าสู่ระบบเกิน; ปฏิเสธการเข้าใช้.';
$CIDRAM['lang']['Name: Bypasses'] = 'บายพาลายเซ็นที่เป็นค่าเริ่มต้น.';
$CIDRAM['lang']['Name: IPv4'] = 'ไฟล์ลายเซ็น IPv4 (บริการคลาวด์ที่ไม่พึงประสงค์และจุดเชื่อมต่อที่ไม่ใช่ของมนุษย์).';
$CIDRAM['lang']['Name: IPv4-Bogons'] = 'ไฟล์ลายเซ็น IPv4 (CIDR ที่ bogon/ชาวอังคาร).';
$CIDRAM['lang']['Name: IPv4-ISPs'] = 'ไฟล์ลายเซ็น IPv4 (ISP ที่เป็นอันตรายและสแปม).';
$CIDRAM['lang']['Name: IPv4-Other'] = 'ไฟล์ลายเซ็น IPv4 (CIDR สำหรับผู้รับมอบฉันทะ VPNs และบริการอื่น ๆ ที่ไม่พึงประสงค์).';
$CIDRAM['lang']['Name: IPv6'] = 'ไฟล์ลายเซ็น IPv6 (บริการคลาวด์ที่ไม่พึงประสงค์และจุดเชื่อมต่อที่ไม่ใช่ของมนุษย์).';
$CIDRAM['lang']['Name: IPv6-Bogons'] = 'ไฟล์ลายเซ็น IPv6 (CIDR ที่ bogon/ชาวอังคาร).';
$CIDRAM['lang']['Name: IPv6-ISPs'] = 'ไฟล์ลายเซ็น IPv6 (ISP ที่เป็นอันตรายและสแปม).';
$CIDRAM['lang']['Name: IPv6-Other'] = 'ไฟล์ลายเซ็น IPv6 (CIDR สำหรับผู้รับมอบฉันทะ VPNs และบริการอื่น ๆ ที่ไม่พึงประสงค์).';
$CIDRAM['lang']['previewer_days'] = 'วัน';
$CIDRAM['lang']['previewer_hours'] = 'ชั่วโมง';
$CIDRAM['lang']['previewer_minutes'] = 'นาที';
$CIDRAM['lang']['previewer_months'] = 'เดือน';
$CIDRAM['lang']['previewer_seconds'] = 'วินาที';
$CIDRAM['lang']['previewer_weeks'] = 'สัปดาห์';
$CIDRAM['lang']['previewer_years'] = 'ปี';
$CIDRAM['lang']['response_accounts_already_exists'] = 'บัญชีด้วยนั่นเองชื่อผู้ใช้มีอยู่แล้ว!';
$CIDRAM['lang']['response_accounts_created'] = 'บัญชีสำเร็จแล้วสร้าง!';
$CIDRAM['lang']['response_accounts_deleted'] = 'บัญชีสำเร็จแล้วลบ!';
$CIDRAM['lang']['response_accounts_doesnt_exist'] = 'บัญชีไม่มีอยู่จริง.';
$CIDRAM['lang']['response_accounts_password_updated'] = 'รหัสผ่านสำเร็จแล้วอัปเดต!';
$CIDRAM['lang']['response_activated'] = 'สำเร็จแล้วเปิดใช้งาน.';
$CIDRAM['lang']['response_activation_failed'] = 'ล้มเหลวเปิดใช้งาน!';
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
$CIDRAM['lang']['response_statistics_cleared'] = 'สถิติลบแล้ว';
$CIDRAM['lang']['response_tracking_cleared'] = 'การติดตามถูกล้าง.';
$CIDRAM['lang']['response_updates_already_up_to_date'] = 'อัปเดตแล้ว.';
$CIDRAM['lang']['response_updates_not_installed'] = 'คอมโพเนนต์ไม่ได้ติดตั้ง!';
$CIDRAM['lang']['response_updates_not_installed_php'] = 'คอมโพเนนต์ไม่ได้ติดตั้ง (ต้องการ PHP {V})!';
$CIDRAM['lang']['response_updates_outdated'] = 'ล้าสมัยแล้ว!';
$CIDRAM['lang']['response_updates_outdated_manually'] = 'ล้าสมัยแล้ว (โปรดอัปเดตด้วยตนเอง)!';
$CIDRAM['lang']['response_updates_outdated_php_version'] = 'ล้าสมัยแล้ว (ต้องการ PHP {V})!';
$CIDRAM['lang']['response_updates_unable_to_determine'] = 'ไม่สามารถกำหนดได้.';
$CIDRAM['lang']['response_upload_error'] = 'ล้มเหลวอัปโหลด!';
$CIDRAM['lang']['response_verification_failed'] = 'การตรวจสอบล้มเหลว! คอมโพเนนต์อาจเสียหาย.';
$CIDRAM['lang']['response_verification_success'] = 'การตรวจสอบสำเร็จ! ไม่พบปัญหา.';
$CIDRAM['lang']['response_yes'] = 'ใช่แล้ว';
$CIDRAM['lang']['state_complete_access'] = 'เข้าถึงได้อย่างสมบูรณ์';
$CIDRAM['lang']['state_component_is_active'] = 'คอมโพเนนต์ใช้งานอยู่.';
$CIDRAM['lang']['state_component_is_inactive'] = 'คอมโพเนนต์ไม่ใช้งาน.';
$CIDRAM['lang']['state_component_is_provisional'] = 'คอมโพเนนต์เป็นครั้งคราว.';
$CIDRAM['lang']['state_default_password'] = 'คำเตือน: ใช้ค่าเริ่มต้นรหัสผ่าน!';
$CIDRAM['lang']['state_loadtime'] = 'คำขอหน้าเสร็จสิ้นภายใน <span class="txtRd">%s</span> วินาที.';
$CIDRAM['lang']['state_logged_in'] = 'เข้าสู่ระบบ.';
$CIDRAM['lang']['state_logs_access_only'] = 'เข้าถึงบันทึกเท่านั้น';
$CIDRAM['lang']['state_maintenance_mode'] = 'คำเตือน: เปิดใช้งานโหมดการบำรุงรักษา!';
$CIDRAM['lang']['state_password_not_valid'] = 'คำเตือน: บัญชีนี้ไม่ได้ใช้รหัสผ่านถูกต้อง!';
$CIDRAM['lang']['state_risk_high'] = 'สูง';
$CIDRAM['lang']['state_risk_low'] = 'ต่ำ';
$CIDRAM['lang']['state_risk_medium'] = 'ปานกลาง';
$CIDRAM['lang']['state_tracking'] = 'ขณะนี้กำลังติดตาม %s IP.';
$CIDRAM['lang']['switch-hide-non-outdated-set-false'] = 'อย่าซ่อนไม่ใช่ล้าสมัย';
$CIDRAM['lang']['switch-hide-non-outdated-set-true'] = 'ซ่อนไม่ใช่ล้าสมัย';
$CIDRAM['lang']['switch-hide-unused-set-false'] = 'อย่าซ่อนไม่ได้ใช้';
$CIDRAM['lang']['switch-hide-unused-set-true'] = 'ซ่อนไม่ได้ใช้';
$CIDRAM['lang']['switch-tracking-blocked-already-set-false'] = 'อย่าตรวจสอบกับไฟล์ลายเซ็น';
$CIDRAM['lang']['switch-tracking-blocked-already-set-true'] = 'ตรวจสอบกับไฟล์ลายเซ็น';
$CIDRAM['lang']['switch-tracking-hide-banned-blocked-set-false'] = 'อย่าซ่อน IP ที่ถูกแบน/ถูกบล็อก';
$CIDRAM['lang']['switch-tracking-hide-banned-blocked-set-true'] = 'ซ่อน IP ที่ถูกแบน/ถูกบล็อก';
$CIDRAM['lang']['tip_accounts'] = 'สวัสดี, {username}.<br />หน้าบัญชีช่วยให้คุณสามารถควบคุมผู้ที่สามารถเข้าถึง front-end ของ CIDRAM.';
$CIDRAM['lang']['tip_cidr_calc'] = 'สวัสดี, {username}.<br />เครื่องคิดเลข CIDR ช่วยให้คุณสามารถคำนวณค่า CIDR ของที่อยู่ IP.';
$CIDRAM['lang']['tip_config'] = 'สวัสดี, {username}.<br />หน้าการกำหนดค่าช่วยให้คุณสามารถแก้ไขการกำหนดค่าสำหรับ CIDRAM จาก front-end.';
$CIDRAM['lang']['tip_custom_ua'] = 'ใส่ user agent ที่นี่ (เป็นตัวเลือก).';
$CIDRAM['lang']['tip_donate'] = 'CIDRAM ให้บริการฟรี, แต่ถ้าคุณต้องการบริจาคให้กับโครงการ, คุณสามารถทำได้โดยคลิกที่ปุ่มบริจาค.';
$CIDRAM['lang']['tip_enter_ips_here'] = 'ใส่ IP ที่นี่.';
$CIDRAM['lang']['tip_enter_ip_here'] = 'ใส่ IP ที่นี่.';
$CIDRAM['lang']['tip_fe_cookie_warning'] = 'บันทึก: CIDRAM ใช้คุกกี้เพื่อตรวจสอบการเข้าสู่ระบบ. เมื่อคุณลงชื่อเข้าใช้คุณยินยอมให้คุกกี้สร้างขึ้นและจัดเก็บโดยเบราเซอร์ของคุณ.';
$CIDRAM['lang']['tip_file_manager'] = 'สวัสดี, {username}.<br />ตัวจัดการไฟล์ ช่วยให้คุณสามารถลบบัญชี, แก้ไข, อัปโหลด, และดาวน์โหลดไฟล์. ใช้ด้วยความระมัดระวัง (คุณสามารถทำลายการติดตั้งของคุณด้วยนี้).';
$CIDRAM['lang']['tip_home'] = 'สวัสดี, {username}.<br />นี่คือโฮมเพจสำหรับ front-end ของ CIDRAM. เลือกลิงค์จากเมนูนำทางด้านซ้ายเพื่อดำเนินการต่อ.';
$CIDRAM['lang']['tip_ip_aggregator'] = 'สวัสดี, {username}.<br />ตัวรวบรวม IP ช่วยให้คุณสามารถแสดง IP และ CIDR ในลักษณะที่เล็กที่สุดได้. ป้อนข้อมูลที่จะรวมและกด "ตกลง".';
$CIDRAM['lang']['tip_ip_test'] = 'สวัสดี, {username}.<br />หน้าทดสอบ IP ช่วยให้คุณสามารถทดสอบว่ามีการบล็อกที่อยู่ IP หรือไม่โดยลายเซ็นที่ติดตั้งไว้ในปัจจุบัน.';
$CIDRAM['lang']['tip_ip_test_module_switch'] = '(เมื่อไม่ได้เลือก เฉพาะไฟล์ลายเซ็นจะได้รับการทดสอบ).';
$CIDRAM['lang']['tip_ip_tracking'] = 'สวัสดี, {username}.<br />หน้าการติดตาม IPช่วยให้คุณสามารถตรวจสอบสถานะการติดตามของที่อยู่ IP, เพื่อตรวจสอบว่าพวกเขาถูกห้ามหรือไม่, และลบล้างถูกห้าม/การติดตามถ้าคุณต้องการทำเช่นนั้น.';
$CIDRAM['lang']['tip_login'] = 'ค่าเริ่มต้นชื่อผู้ใช้: <span class="txtRd">admin</span> – ค่าเริ่มต้นรหัสผ่าน: <span class="txtRd">password</span>';
$CIDRAM['lang']['tip_logs'] = 'สวัสดี, {username}.<br />เลือกไฟล์บันทึกจากรายการด้านล่างเพื่อดูเนื้อหาของไฟล์บันทึกนั้น.';
$CIDRAM['lang']['tip_see_the_documentation'] = 'ดูที่<a href="https://github.com/CIDRAM/CIDRAM/blob/master/_docs/readme.en.md#SECTION6">เอกสาร</a>สำหรับข้อมูลเกี่ยวกับคำสั่งการกำหนดค่าต่างๆและวัตถุประสงค์ของพวกเขา.';
$CIDRAM['lang']['tip_statistics'] = 'สวัสดี, {username}.<br />หน้านี้แสดงสถิติการใช้งานขั้นพื้นฐานเกี่ยวกับการติดตั้ง CIDRAM ของคุณ.';
$CIDRAM['lang']['tip_statistics_disabled'] = 'บันทึก: ขณะนี้การติดตามผลสถิติถูกปิดใช้งาน แต่สามารถเปิดใช้งานได้ผ่านทางหน้าการกำหนดค่า.';
$CIDRAM['lang']['tip_updates'] = 'สวัสดี, {username}.<br />หน้าอัปเดตช่วยให้คุณสามารถติดตั้ง, ถอนการติดตั้ง, และอัปเดตคอมโพเนนต์ต่างๆของ CIDRAM (แพคเกจหลัก, ลายเซ็น, ไฟล์การแปล, ฯลฯ).';
$CIDRAM['lang']['title_accounts'] = 'CIDRAM – บัญชี';
$CIDRAM['lang']['title_cidr_calc'] = 'CIDRAM – เครื่องคิดเลข CIDR';
$CIDRAM['lang']['title_config'] = 'CIDRAM – การกำหนดค่า';
$CIDRAM['lang']['title_file_manager'] = 'CIDRAM – ตัวจัดการไฟล์';
$CIDRAM['lang']['title_home'] = 'CIDRAM – โฮมเพจ';
$CIDRAM['lang']['title_ip_aggregator'] = 'CIDRAM – ตัวรวบรวม IP';
$CIDRAM['lang']['title_ip_test'] = 'CIDRAM – ทดสอบ IP';
$CIDRAM['lang']['title_ip_tracking'] = 'CIDRAM – การติดตาม IP';
$CIDRAM['lang']['title_login'] = 'CIDRAM – เข้าสู่ระบบ';
$CIDRAM['lang']['title_logs'] = 'CIDRAM – บันทึก';
$CIDRAM['lang']['title_statistics'] = 'CIDRAM – สถิติ';
$CIDRAM['lang']['title_updates'] = 'CIDRAM – อัปเดต';
$CIDRAM['lang']['warning'] = 'คำเตือน:';
$CIDRAM['lang']['warning_php_1'] = 'เวอร์ชัน PHP ของคุณไม่ได้รับการสนับสนุนอีกต่อไป! ปรับปรุงขอแนะนำ!';
$CIDRAM['lang']['warning_php_2'] = 'เวอร์ชัน PHP ของคุณมีความเสี่ยงสูง! ปรับปรุงขอแนะนำ!';
$CIDRAM['lang']['warning_signatures_1'] = 'ไม่มีไฟล์ลายเซ็นที่ใช้งานอยู่!';

$CIDRAM['lang']['info_some_useful_links'] = 'ลิงก์ที่เป็นประโยชน์:<ul>
            <li><a href="https://github.com/CIDRAM/CIDRAM/issues">ปัญหา CIDRAM @ GitHub</a> – หน้าปัญหาสำหรับ CIDRAM (สนับสนุน, ความช่วยเหลือ, ฯลฯ).</li>
            <li><a href="http://www.spambotsecurity.com/forum/viewforum.php?f=61">CIDRAM @ Spambot Security</a> – ฟอรั่มการอภิปรายสำหรับ CIDRAM (สนับสนุน, ความช่วยเหลือ, ฯลฯ).</li>
            <li><a href="https://wordpress.org/plugins/cidram/">CIDRAM @ WordPress.org</a> – ปลั๊กอิน WordPress สำหรับ CIDRAM.</li>
            <li><a href="https://sourceforge.net/projects/cidram/">CIDRAM @ SourceForge</a> – สถานที่ดาวน์โหลดอื่นสำหรับ CIDRAM.</li>
            <li><a href="https://websectools.com/">WebSecTools.com</a> – คอลเลกชันเครื่องมือเว็บมาสเตอร์ง่ายสำหรับการรักษาความปลอดภัยเว็บไซต์.</li>
            <li><a href="https://macmathan.info/blocklists">ช่วงบล็อก @ MacMathan.info</a> – มีบล็อคช่วงที่ไม่จำเป็นซึ่งสามารถเพิ่มลงใน CIDRAM เพื่อบล็อกประเทศที่ไม่ต้องการจากการเข้าถึงเว็บไซต์ของคุณ.</li>
            <li><a href="https://www.facebook.com/groups/2204685680/">Global PHP Group @ Facebook</a> – แหล่งเรียนรู้ PHP และการสนทนา.</li>
            <li><a href="https://php.earth/">PHP.earth</a> – แหล่งเรียนรู้ PHP และการสนทนา.</li>
            <li><a href="http://bgp.he.net/">Hurricane Electric BGP Toolkit</a> – รับ CIDR จาก ASN, ยืนยันความสัมพันธ์ ASN, ค้นพบ ASN ตามชื่อเครือข่าย, ฯลฯ.</li>
            <li><a href="https://www.stopforumspam.com/forum/">ฟอรัม @ Stop Forum Spam</a> – ฟอรั่มการอภิปรายมีประโยชน์เกี่ยวกับหยุดสแปมในฟอรัม.</li>
            <li><a href="https://radar.qrator.net/">Radar โดย Qrator</a> – เครื่องมือมีประโยชน์สำหรับตรวจสอบการเชื่อมต่อ ASN รวมทั้งข้อมูลอื่น ๆ เกี่ยวกับ ASN.</li>
            <li><a href="http://www.ipdeny.com/ipblocks/">บล็อกประเทศ IP @ IPdeny</a> – บริการที่ยอดเยี่ยมและถูกต้องสำหรับการสร้างลายเซ็นสำหรับประเทศ.</li>
            <li><a href="https://www.google.com/transparencyreport/safebrowsing/malware/">Google Malware Dashboard</a> – แสดงรายงานเกี่ยวกับอัตราการติดเชื้อของมัลแวร์ใน ASN.</li>
            <li><a href="https://www.spamhaus.org/statistics/botnet-asn/">โครงการ Spamhaus</a> – แสดงรายงานเกี่ยวกับอัตราการติดเชื้อของ botnet ใน ASN.</li>
            <li><a href="https://www.abuseat.org/public/asn.html">รายการการปิดกั้นแบบคอมโพสิต @ Abuseat.org</a> – แสดงรายงานเกี่ยวกับอัตราการติดเชื้อของ botnet ใน ASN.</li>
            <li><a href="https://abuseipdb.com/">AbuseIPDB</a> – รักษาฐานข้อมูล IP เป็นอันตราย; จัดเตรียม API สำหรับตรวจสอบและรายงาน IP.</li>
            <li><a href="https://www.megarbl.net/index.php">MegaRBL.net</a> – เก็บรายชื่อผู้ส่งสแปมที่รู้จัก; มีประโยชน์สำหรับตรวจสอบกิจกรรมสแปม IP/ASN.</li>
            <li><a href="https://maikuolan.github.io/Vulnerability-Charts/">Vulnerability Charts (ชาร์ตเสี่ยง)</a> – แสดงรายการเวอร์ชันต่างๆแพคเกจที่ปลอดภัย/ไม่ปลอดภัย (PHP, HHVM, ฯลฯ).</li>
            <li><a href="https://maikuolan.github.io/Compatibility-Charts/">Compatibility Charts (ชาร์ตความเข้ากันได้)</a> – แสดงข้อมูลความเข้ากันได้ของแพคเกจต่างๆ (CIDRAM, phpMussel, ฯลฯ).</li>
        </ul>';
