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
 * This file: Vietnamese language data for CLI (last modified: 2016.07.26).
 */

/** Prevents execution from outside of CIDRAM. */
if (!defined('CIDRAM')) {
    die('[CIDRAM] This should not be accessed directly.');
}

$CIDRAM['lang']['CLI_H'] = "
 Trợ giúp cho chế độ CLI của CIDRAM.

 Sử dụng:
 /đường/dẫn/đến/php/php.exe /đường/dẫn/đến/cidram/loader.php -Dấu (Đầu vào)

 Dấu: -h    Hiển thị thông tin trợ giúp này.
      -c    Kiểm tra nếu một địa chỉ IP bị chặn bởi các tập tin chữ ký của
            CIDRAM.
      -g    Tạo ra CIDR từ một địa chỉ IP.

 Đầu vào: Có thể là bất kỳ IPv4 hoặc IPv6 địa chỉ IP hợp lệ.

 Các ví dụ:
        -c  192.168.0.0/16
        -c  127.0.0.1/32
        -c  2001:db8::/32
        -c  2002::1/128

";

$CIDRAM['lang']['CLI_Bad_IP'] = ' Các địa chỉ IP được chỉ định, "{IP}", không phải là một địa chỉ IPv4 hoặc IPv6 hợp lệ!';
$CIDRAM['lang']['CLI_IP_Blocked'] = ' Các địa chỉ IP được chỉ định, "{IP}", *LÀ* bị chặn bởi một hay nhiều tập tin chữ ký của CIDRAM.';
$CIDRAM['lang']['CLI_IP_Not_Blocked'] = ' Các địa chỉ IP được chỉ định, "{IP}", *KHÔNG* bị chặn bởi một hay nhiều tập tin chữ ký của CIDRAM.';

$CIDRAM['lang']['CLI_F_Finished'] = 'Công cụ cho sửa chửa chữ ký đã hoàn thành xong, với %s thay đổi trong %s hoạt động (%s).';
$CIDRAM['lang']['CLI_F_Started'] = 'Công cụ cho sửa chửa chữ ký đã bắt đầu (%s).';
$CIDRAM['lang']['CLI_VF_Empty'] = 'Tập tin chữ ký xác định là rỗng hoặc không tồn tại.';
$CIDRAM['lang']['CLI_VF_Level_0'] = 'Thông báo';
$CIDRAM['lang']['CLI_VF_Level_1'] = 'Cảnh báo';
$CIDRAM['lang']['CLI_VF_Level_2'] = 'Lỗi';
$CIDRAM['lang']['CLI_VF_Level_3'] = 'Lỗi nghiêm trọng';

$CIDRAM['lang']['CLI_V_CRLF'] = 'CR/CRLF phát hiện trong tập tin chữ ký; Đây là phép và sẽ không gây ra vấn đề, nhưng LF được ưa thích.';
$CIDRAM['lang']['CLI_V_Finished'] = 'Công cụ cho xác nhận chữ ký đã hoàn thành xong (%s). Nếu không cảnh báo hay lỗi đã xuất hiện, tập tin chữ ký của bạn có lẽ là tốt. :-)';
$CIDRAM['lang']['CLI_V_LineByLine'] = 'Công cụ cho xác nhận từng dòng đã bắt đầu.';
$CIDRAM['lang']['CLI_V_Started'] = 'Công cụ cho xác nhận chữ ký đã bắt đầu (%s).';
$CIDRAM['lang']['CLI_V_Terminal_LF'] = 'Tập tin chữ ký nên chấm dứt với một ngắt dòng LF.';

$CIDRAM['lang']['CLI_VL_CC'] = 'L%s: Điều khiển nhân vật được phát hiện; Điều này có thể chỉ ra tham nhũng và nên được điều tra.';
$CIDRAM['lang']['CLI_VL_Duplicated'] = 'L%s: Chữ ký "%s" được nhân đôi (%s đếm)!';
$CIDRAM['lang']['CLI_VL_Expiry'] = 'L%s: Chỉ số hết hạn không chứa đựng một ngày hay thời gian ISO 8601 hợp lệ!';
$CIDRAM['lang']['CLI_VL_Invalid'] = 'L%s: "%s" *KHÔNG* phải là một địa chỉ IPv4 hay IPv6 hợp lệ!';
$CIDRAM['lang']['CLI_VL_L120'] = 'L%s: Chiều dài dòng là lớn hơn 120 byte; Chiều dài dòng nên được giới hạn đến 120 byte cho dễ đọc tối ưu.';
$CIDRAM['lang']['CLI_VL_Mergeable'] = 'L%s và L%s là giống hệt nhau, và như vậy, có thể hợp nhất.';
$CIDRAM['lang']['CLI_VL_Missing'] = 'L%s: [Function] vắng mặt; Chữ ký dường như là không đầy đủ.';
$CIDRAM['lang']['CLI_VL_Nontriggerable'] = 'L%s: "%s" không thể được kích hoạt! Cơ sở của nó không phù hợp đầu phạm vi của nó! Hãy thử thay thế nó bằng "%s".';
$CIDRAM['lang']['CLI_VL_Nontriggerable_Range'] = 'L%s: "%s" không thể được kích hoạt! "%s" không phải là một phạm vi hợp lệ!';
$CIDRAM['lang']['CLI_VL_Subordinate'] = 'L%s: "%s" là cấp dưới của một chữ ký đã tồn tại "%s".';
$CIDRAM['lang']['CLI_VL_Superset'] = 'L%s: "%s" là cấp trên của một chữ ký đã tồn tại "%s".';
$CIDRAM['lang']['CLI_VL_Syntax'] = 'L%s: Không cú pháp chính xác.';
$CIDRAM['lang']['CLI_VL_Tabs'] = 'L%s: Tab được phát hiện; Không gian được ưa thích hơn các tab cho dễ đọc tối ưu.';
$CIDRAM['lang']['CLI_VL_Tags'] = 'L%s: Chỉ số phần lớn hơn 20 byte; Chỉ số phần phải rõ ràng và súc tích.';
$CIDRAM['lang']['CLI_VL_Unrecognised'] = 'L%s: [Function] không xác định; Chữ ký có thể bị phá vỡ.';
$CIDRAM['lang']['CLI_VL_Whitespace'] = 'L%s: Khoảng trắng dư thừa dấu phát hiện trên dòng này.';
$CIDRAM['lang']['CLI_VL_YAML'] = 'L%s: Dữ liệu tương tự như YAML đã được phát hiện, nhưng không thể xử lý.';
