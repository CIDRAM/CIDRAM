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
 * This file: Vietnamese language data for the front-end (last modified: 2016.11.18).
 */

/** Prevents execution from outside of CIDRAM. */
if (!defined('CIDRAM')) {
    die('[CIDRAM] This should not be accessed directly.');
}

$CIDRAM['lang']['bNav_home_logout'] = '<a href="?">Trang Chủ</a> | <a href="?cidram-page=logout">Đăng Xuất</a>';
$CIDRAM['lang']['bNav_logout'] = '<a href="?cidram-page=logout">Đăng Xuất</a>';
$CIDRAM['lang']['config_general_disable_cli'] = 'Vô hiệu hóa chế độ CLI?';
$CIDRAM['lang']['config_general_disable_frontend'] = 'Vô hiệu hóa truy cập front-end?';
$CIDRAM['lang']['config_general_emailaddr'] = 'Địa chỉ email cho hỗ trợ.';
$CIDRAM['lang']['config_general_forbid_on_block'] = 'Cái nào tiêu đề nên CIDRAM phản ứng với khi các yêu cầu được bị chặn?';
$CIDRAM['lang']['config_general_ipaddr'] = 'Nơi để tìm địa chỉ IP của các yêu cầu kết nối?';
$CIDRAM['lang']['config_general_lang'] = 'Xác định tiếng mặc định cho CIDRAM.';
$CIDRAM['lang']['config_general_logfile'] = 'Tập tin có thể đọc con người cho ghi tất cả các nỗ lực truy cập bị chặn. Chỉ định một tên tập tin, hoặc để trống để vô hiệu hóa.';
$CIDRAM['lang']['config_general_logfileApache'] = 'Tập tin Apache phong cách cho ghi tất cả các nỗ lực truy cập bị chặn. Chỉ định một tên tập tin, hoặc để trống để vô hiệu hóa.';
$CIDRAM['lang']['config_general_logfileSerialized'] = 'Tập tin tuần tự cho ghi tất cả các nỗ lực truy cập bị chặn. Chỉ định một tên tập tin, hoặc để trống để vô hiệu hóa.';
$CIDRAM['lang']['config_general_silent_mode'] = 'CIDRAM nên âm thầm chuyển hướng cố gắng truy cập bị chặn thay vì hiển thị trang "Truy cập bị từ chối"? Nếu vâng, xác định vị trí để chuyển hướng cố gắng truy cập bị chặn để. Nếu không, để cho biến này được trống.';
$CIDRAM['lang']['config_general_timeOffset'] = 'Múi giờ bù đắp trong phút.';
$CIDRAM['lang']['config_recaptcha_expiry'] = 'Số giờ để nhớ reCAPTCHA.';
$CIDRAM['lang']['config_recaptcha_lockip'] = 'Khóa reCAPTCHA để IP?';
$CIDRAM['lang']['config_recaptcha_lockuser'] = 'Khóa reCAPTCHA để người dùng?';
$CIDRAM['lang']['config_recaptcha_logfile'] = 'Đăng nhập tất cả các nỗ lực cho reCAPTCHA? Nếu có, ghi rõ tên để sử dụng cho các tập tin đăng nhập. Nếu không, đốn biến này.';
$CIDRAM['lang']['config_recaptcha_secret'] = 'Giá trị này nên tương ứng với "secret key" cho reCAPTCHA của bạn, tìm thấy trong bảng điều khiển của reCAPTCHA.';
$CIDRAM['lang']['config_recaptcha_sitekey'] = 'Giá trị này nên tương ứng với "site key" cho reCAPTCHA của bạn, tìm thấy trong bảng điều khiển của reCAPTCHA.';
$CIDRAM['lang']['config_recaptcha_usemode'] = 'Định nghĩa thế nào CIDRAM nên sử dụng reCAPTCHA (xem tài liệu).';
$CIDRAM['lang']['config_signatures_block_bogons'] = 'Chặn CIDR bogon/martian? Nếu bạn mong đợi các kết nối đến trang mạng của bạn từ bên trong mạng nội bộ của bạn, từ localhost, hay từ LAN của bạn, tùy chọn này cần được thiết lập để false. Nếu bạn không mong đợi những kết nối như vậy, tùy chọn này cần được thiết lập để true.';
$CIDRAM['lang']['config_signatures_block_cloud'] = 'Chặn CIDR xác định là thuộc về các dịch vụ lưu trữ mạng hay dịch vụ điện toán đám mây? Nếu bạn điều hành một dịch vụ API từ trang mạng của bạn hay nếu bạn mong đợi các trang mạng khác để kết nối với trang mạng của bạn, điều này cần được thiết lập để false. Nếu bạn không, sau đó, tùy chọn này cần được thiết lập để true.';
$CIDRAM['lang']['config_signatures_block_generic'] = 'Chặn CIDR thường được khuyến cáo cho danh sách đen? Điều này bao gồm bất kỳ chữ ký không được đánh dấu như một phần của bất kỳ các loại chữ ký cụ thể khác.';
$CIDRAM['lang']['config_signatures_block_proxies'] = 'Chặn CIDR xác định là thuộc về các dịch vụ proxy? Nếu bạn yêu cầu mà người dùng có thể truy cập trang mạng của bạn từ các dịch vụ proxy ẩn danh, điều này cần được thiết lập để false. Nếu không thì, nếu bạn không yêu cầu proxy vô danh, tùy chọn này cần được thiết lập để true như một phương tiện để cải thiện an ninh.';
$CIDRAM['lang']['config_signatures_block_spam'] = 'Chặn CIDR xác định như có nguy cơ cao đối được thư rác? Trừ khi bạn gặp vấn đề khi làm như vậy, nói chung, điều này cần phải luôn được true.';
$CIDRAM['lang']['config_signatures_ipv4'] = 'Một danh sách các tập tin chữ ký IPv4 mà CIDRAM nên cố gắng để phân tích, ngăn cách bởi dấu phẩy.';
$CIDRAM['lang']['config_signatures_ipv6'] = 'Một danh sách các tập tin chữ ký IPv6 mà CIDRAM nên cố gắng để phân tích, ngăn cách bởi dấu phẩy.';
$CIDRAM['lang']['config_template_data_css_url'] = 'URL của tập tin CSS cho các chủ đề tùy chỉnh.';
$CIDRAM['lang']['field_blocked'] = 'Bị Chặn';
$CIDRAM['lang']['field_component'] = 'Gói';
$CIDRAM['lang']['field_create_new_account'] = 'Tạo ra tài khoản mới';
$CIDRAM['lang']['field_delete_account'] = 'Xóa tài khoản';
$CIDRAM['lang']['field_filename'] = 'Tên tập tin: ';
$CIDRAM['lang']['field_install'] = 'Cài Đặt';
$CIDRAM['lang']['field_ip_address'] = 'Địa Chỉ IP';
$CIDRAM['lang']['field_latest_version'] = 'Phiên bản mới nhất';
$CIDRAM['lang']['field_log_in'] = 'Đăng Nhập';
$CIDRAM['lang']['field_ok'] = 'OK';
$CIDRAM['lang']['field_options'] = 'Tùy Chọn';
$CIDRAM['lang']['field_password'] = 'Mật Khẩu';
$CIDRAM['lang']['field_permissions'] = 'Quyền';
$CIDRAM['lang']['field_set_new_password'] = 'Đặt mật khẩu mới';
$CIDRAM['lang']['field_size'] = 'Kích thước tổng: ';
$CIDRAM['lang']['field_size_bytes'] = 'byte';
$CIDRAM['lang']['field_size_GB'] = 'GB';
$CIDRAM['lang']['field_size_KB'] = 'KB';
$CIDRAM['lang']['field_size_MB'] = 'MB';
$CIDRAM['lang']['field_size_TB'] = 'TB';
$CIDRAM['lang']['field_status'] = 'Tình Trạng';
$CIDRAM['lang']['field_uninstall'] = 'Gỡ Bỏ Cài Đặt';
$CIDRAM['lang']['field_update'] = 'Cập Nhật';
$CIDRAM['lang']['field_username'] = 'Tên Người Dùng';
$CIDRAM['lang']['field_your_version'] = 'Phiên bản của bạn';
$CIDRAM['lang']['header_login'] = 'Vui lòng đăng nhập để tiếp tục.';
$CIDRAM['lang']['link_accounts'] = 'Tài Khoản';
$CIDRAM['lang']['link_config'] = 'Cấu Hình';
$CIDRAM['lang']['link_documentation'] = 'Tài Liệu';
$CIDRAM['lang']['link_home'] = 'Trang Chủ';
$CIDRAM['lang']['link_ip_test'] = 'Kiểm Tra IP';
$CIDRAM['lang']['link_logs'] = 'Bản Ghi';
$CIDRAM['lang']['link_updates'] = 'Cập Nhật';
$CIDRAM['lang']['logs_logfile_doesnt_exist'] = 'Bản ghi đã chọn không tồn tại!';
$CIDRAM['lang']['logs_no_logfiles_available'] = 'Không có bản ghi có sẵn.';
$CIDRAM['lang']['logs_no_logfile_selected'] = 'Không có bản ghi được chọn.';
$CIDRAM['lang']['response_accounts_already_exists'] = 'Một tài khoản với tên người dùng này đã tồn tại!';
$CIDRAM['lang']['response_accounts_created'] = 'Tài khoản tạo ra thành công!';
$CIDRAM['lang']['response_accounts_deleted'] = 'Tài khoản xóa thành công!';
$CIDRAM['lang']['response_accounts_doesnt_exist'] = 'Tài khoản này không tồn tại.';
$CIDRAM['lang']['response_accounts_password_updated'] = 'Mật khẩu cập nhật thành công!';
$CIDRAM['lang']['response_component_successfully_installed'] = 'Gói cài đặt thành công.';
$CIDRAM['lang']['response_component_successfully_uninstalled'] = 'Gói gỡ bỏ cài đặt thành công.';
$CIDRAM['lang']['response_component_successfully_updated'] = 'Gói cập nhật thành công.';
$CIDRAM['lang']['response_component_uninstall_error'] = 'Có lỗi xảy ra trong khi cố gắng để gỡ bỏ cài đặt các gói.';
$CIDRAM['lang']['response_component_update_error'] = 'Có lỗi xảy ra trong khi cố gắng để cập nhật các gói.';
$CIDRAM['lang']['response_configuration_updated'] = 'Cấu hình cập nhật thành công.';
$CIDRAM['lang']['response_error'] = 'Lỗi';
$CIDRAM['lang']['response_login_invalid_password'] = 'Thất bại đăng nhập! Mật khẩu không hợp lệ!';
$CIDRAM['lang']['response_login_invalid_username'] = 'Thất bại đăng nhập! Tên người dùng không tồn tại!';
$CIDRAM['lang']['response_login_password_field_empty'] = 'Mật khẩu là trống!';
$CIDRAM['lang']['response_login_username_field_empty'] = 'Tên người dùng là trống!';
$CIDRAM['lang']['response_no'] = 'Không';
$CIDRAM['lang']['response_updates_already_up_to_date'] = 'Đã cập nhật.';
$CIDRAM['lang']['response_updates_not_installed'] = 'Gói không được cài đặt!';
$CIDRAM['lang']['response_updates_outdated'] = 'Hết hạn!';
$CIDRAM['lang']['response_updates_outdated_manually'] = 'Hết hạn (vui lòng cập nhật bằng tay)!';
$CIDRAM['lang']['response_updates_unable_to_determine'] = 'Không thể xác định.';
$CIDRAM['lang']['response_yes'] = 'Vâng';
$CIDRAM['lang']['state_complete_access'] = 'Truy cập đầy đủ';
$CIDRAM['lang']['state_component_is_active'] = 'Gói này đang hoạt động.';
$CIDRAM['lang']['state_component_is_inactive'] = 'Gói này không đang hoạt động.';
$CIDRAM['lang']['state_component_is_provisional'] = 'Gói này đang thỉnh thoảng hoạt động.';
$CIDRAM['lang']['state_default_password'] = 'Cảnh báo: Nó là sử dụng mật khẩu mặc định!';
$CIDRAM['lang']['state_logged_in'] = 'Được đăng nhập';
$CIDRAM['lang']['state_logs_access_only'] = 'Bản ghi truy cập chỉ';
$CIDRAM['lang']['state_password_not_valid'] = 'Cảnh báo: Tài khoản này không được sử dụng một mật khẩu hợp lệ!';
$CIDRAM['lang']['switch-hide-non-outdated-set-false'] = 'Đừng ẩn các không hết hạn';
$CIDRAM['lang']['switch-hide-non-outdated-set-true'] = 'Ẩn các không hết hạn';
$CIDRAM['lang']['switch-hide-unused-set-false'] = 'Đừng ẩn các không cài đặt';
$CIDRAM['lang']['switch-hide-unused-set-true'] = 'Ẩn các không cài đặt';
$CIDRAM['lang']['tip_accounts'] = 'Xin chào, {username}.<br />Trang tài khoản cho phép bạn kiểm soát những người có thể truy cập các front-end CIDRAM.';
$CIDRAM['lang']['tip_config'] = 'Xin chào, {username}.<br />Trang cấu hình cho phép bạn chỉnh sửa các cấu hình CIDRAM từ các front-end.';
$CIDRAM['lang']['tip_enter_ips_here'] = 'Nhập IP ở đây.';
$CIDRAM['lang']['tip_home'] = 'Xin chào, {username}.<br />Đây là trang chủ cho các front-end CIDRAM. Chọn một liên kết từ thực đơn bên trái để tiếp tục.';
$CIDRAM['lang']['tip_ip_test'] = 'Xin chào, {username}.<br />Trang kiểm tra IP cho phép bạn kiểm tra nếu địa chỉ IP bị chặn bằng các chữ ký hiện đang được cài đặt.';
$CIDRAM['lang']['tip_login'] = 'Tên người dùng mặc định: <span class="txtRd">admin</span> – Mật khẩu mặc định: <span class="txtRd">password</span>';
$CIDRAM['lang']['tip_logs'] = 'Xin chào, {username}.<br />Chọn một bản ghi từ danh sách dưới đây để xem nội dung của bản ghi này.';
$CIDRAM['lang']['tip_see_the_documentation'] = 'Xem <a href="https://github.com/Maikuolan/CIDRAM/blob/master/_docs/readme.vi.md#SECTION6">tài liệu</a> để biết thông tin về các chỉ thị cấu hình khác nhau và mục đích của họ.';
$CIDRAM['lang']['tip_updates'] = 'Xin chào, {username}.<br />Trang cập nhật cho phép bạn cài đặt, gỡ bỏ cài đặt, và cập nhật các gói khác nhau cho CIDRAM (các gói cốt lõi, chữ ký, bổ sung, các tập tin L10N, vv).';
$CIDRAM['lang']['title_accounts'] = 'CIDRAM – Tài Khoản';
$CIDRAM['lang']['title_config'] = 'CIDRAM – Cấu Hình';
$CIDRAM['lang']['title_home'] = 'CIDRAM – Trang Chủ';
$CIDRAM['lang']['title_ip_test'] = 'CIDRAM – Kiểm Tra IP';
$CIDRAM['lang']['title_login'] = 'CIDRAM – Đăng Nhập';
$CIDRAM['lang']['title_logs'] = 'CIDRAM – Bản Ghi';
$CIDRAM['lang']['title_updates'] = 'CIDRAM – Cập Nhật';
