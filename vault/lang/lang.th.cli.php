<?php
/**
 * This file is a part of the CIDRAM package, and can be downloaded for free
 * from {@link https://github.com/Maikuolan/CIDRAM/ GitHub}.
 *
 * CIDRAM COPYRIGHT 2016 and beyond by Caleb Mazalevskis (Maikuolan).
 *
 * License: GNU/GPLv2
 * @see LICENSE.txt
 *
 * This file: Thai language data for CLI (last modified: 2016.04.22).
 */

/** Prevents execution from outside of CIDRAM. */
if (!defined('CIDRAM')) {
    die('[CIDRAM] This should not be accessed directly.');
}

$CIDRAM['lang']['CLI_H'] = "
 ช่วยสำหรับโหมด CLI ของ CIDRAM.

 การใช้งาน:
 /เส้นทาง/ไป/php/php.exe /เส้นทาง/ไป/cidram/loader.php -ธง (ใส่ข้อมูล)

 ธง:
    -h  แสดงข้อมูลความช่วยเหลือนี้.
    -c  ตรวจสอบว่าที่อยู่ IP ถูกบล็อกโดยไฟล์ลายเซ็นของ CIDRAM.
    -g  สร้าง CIDR จากที่อยู่ IP.

 ใส่ข้อมูล: มันอาจจะใด ๆ ที่อยู่ IP ถูกต้อง IPv4 หรือ IPv6.

 ตัวอย่าง:
        -c  192.168.0.0/16
        -c  127.0.0.1/32
        -c  2001:db8::/32
        -c  2002::1/128

";

$CIDRAM['lang']['CLI_Bad_IP'] = ' ที่อยู่ IP ที่ระบุ, "{IP}", ไม่ใช่ที่อยู่ที่ IPv4 หรือ IPv6 ถูกต้อง!';
$CIDRAM['lang']['CLI_IP_Blocked'] = ' ที่อยู่ IP ที่ระบุ, "{IP}", ถูกบล็อกโดยหนึ่งหรือมากกว่าของไฟล์ลายเซ็นสำหรับ CIDRAM.';
$CIDRAM['lang']['CLI_IP_Not_Blocked'] = ' ที่อยู่ IP ที่ระบุ, "{IP}", ไม่ถูกบล็อกโดยใดของไฟล์ลายเซ็นสำหรับ CIDRAM.';

$CIDRAM['lang']['CLI_F_Finished'] = 'การแก้ไขลายเซ็นเสร็จแล้ว, และมีอยู่อยู่ %s เปลี่ยนแปลงทำด้วย %s การดำเนินงาน (%s).';
$CIDRAM['lang']['CLI_F_Started'] = 'การแก้ไขลายเซ็นได้เริ่มต้นแล้ว (%s).';
$CIDRAM['lang']['CLI_VF_Empty'] = 'ไฟล์ลายเซ็นที่ระบุว่างเปล่าหรือไม่มีอยู่.';
$CIDRAM['lang']['CLI_VF_Level_0'] = 'ประกาศ';
$CIDRAM['lang']['CLI_VF_Level_1'] = 'คำเตือน';
$CIDRAM['lang']['CLI_VF_Level_2'] = 'ข้อผิดพลาด';
$CIDRAM['lang']['CLI_VF_Level_3'] = 'ข้อผิดพลาดร้ายแรง';

$CIDRAM['lang']['CLI_V_CRLF'] = 'CR/CRLF ตรวจพบแล้วในไฟล์ลายเซ็น; เหล่านี้เป็นที่ยอมรับและจะไม่ทำให้เกิดปัญหา, แต่ LF เป็นที่ชอบกว่า.';
$CIDRAM['lang']['CLI_V_Finished'] = 'เครื่องมือยืนยันลายเซ็นเสร็จแล้ว (%s). หากไม่มีคำเตือนหรือข้อผิดพลาดได้ปรากฏตัวขึ้น, ไฟล์ลายเซ็นของคุณน่าจะเป็นโอเค. :-)';
$CIDRAM['lang']['CLI_V_LineByLine'] = 'ยืนยันบรรทัดโดยบรรทัดได้เริ่มต้นแล้ว.';
$CIDRAM['lang']['CLI_V_Started'] = 'เครื่องมือยืนยันลายเซ็นได้เริ่มต้นแล้ว (%s).';
$CIDRAM['lang']['CLI_V_Terminal_LF'] = 'ไฟล์ลายเซ็นควรเสร็จสิ้นด้วยแบ่งบรรทัด LF.';

$CIDRAM['lang']['CLI_VL_CC'] = 'L%s: อักขระควบคุมตรวจพบแล้ว; นี้อาจบ่งบอกคอรัปชั่นและควรได้รับการตรวจสอบ.';
$CIDRAM['lang']['CLI_VL_Duplicated'] = 'L%s: ลายเซ็น "%s" ถูกทำซ้ำ (%s นับ)!';
$CIDRAM['lang']['CLI_VL_Expiry'] = 'L%s: แท็กหมดอายุไม่มีวันเวลา ISO 8601 ถูกต้อง!';
$CIDRAM['lang']['CLI_VL_Invalid'] = 'L%s: "%s" ไม่ใช่ที่อยู่ IPv4 หรือ IPv6 ถูกต้อง!';
$CIDRAM['lang']['CLI_VL_L120'] = 'L%s: ความยาวสายสูงกว่า 120 ไบต์; ความยาวสายควรจำกัดไว้ที่ 120 ไบต์สำหรับการอ่านที่ดีที่สุด.';
$CIDRAM['lang']['CLI_VL_Mergeable'] = 'L%s และ L%s เหมือนกัน, และดังนั้น, สามารถผสานได้.';
$CIDRAM['lang']['CLI_VL_Missing'] = 'L%s: [Function] หายไป; ลายเซ็นดูเหมือนจะไม่สมบูรณ์.';
$CIDRAM['lang']['CLI_VL_Nontriggerable'] = 'L%s: "%s" ไม่สามารถเรียกใช้ได้! ฐานของมันไม่ตรงกันจุดเริ่มต้นของช่วง! ลองแทนที่ด้วย "%s".';
$CIDRAM['lang']['CLI_VL_Nontriggerable_Range'] = 'L%s: "%s" ไม่สามารถเรียกใช้ได้! "%s" ไม่ใช่ช่วงที่ถูกต้อง!';
$CIDRAM['lang']['CLI_VL_Subordinate'] = 'L%s: "%s" เป็นเซตย่อยของลายเซ็นที่มีอยู่แล้ว "%s".';
$CIDRAM['lang']['CLI_VL_Superset'] = 'L%s: "%s" เป็น superset ของลายเซ็นที่มีอยู่แล้ว "%s".';
$CIDRAM['lang']['CLI_VL_Syntax'] = 'L%s: มีไวยากรณ์ที่ไม่ถูกต้อง.';
$CIDRAM['lang']['CLI_VL_Tabs'] = 'L%s: แท็บตรวจพบแล้ว; สเปซบาร์เป็นที่ชอบมากกว่าที่แท็บสำหรับการอ่านที่ดีที่สุด.';
$CIDRAM['lang']['CLI_VL_Tags'] = 'L%s: แท็กมาตราสูงกว่า 20 ไบต์; แท็กมาตราควรมีความชัดเจนและรัดกุม.';
$CIDRAM['lang']['CLI_VL_Unrecognised'] = 'L%s: [Function] ไม่รู้จัก; ลายเซ็นอาจจะแตกหัก.';
$CIDRAM['lang']['CLI_VL_Whitespace'] = 'L%s: ช่องว่างต่อท้ายส่วนเกินตรวจพบแล้วในบรรทัดนี้.';
$CIDRAM['lang']['CLI_VL_YAML'] = 'L%s: ข้อมูลเหมือน YAML ตรวจพบแล้ว, แต่ไม่สามารถดำเนินการได้.';
