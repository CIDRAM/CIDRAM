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
 * This file: Vietnamese language data for the front-end (last modified: 2017.08.17).
 */

/** Prevents execution from outside of CIDRAM. */
if (!defined('CIDRAM')) {
    die('[CIDRAM] This should not be accessed directly.');
}

$CIDRAM['lang']['bNav_home_logout'] = '<a href="?">Trang Chủ</a> | <a href="?cidram-page=logout">Đăng Xuất</a>';
$CIDRAM['lang']['bNav_logout'] = '<a href="?cidram-page=logout">Đăng Xuất</a>';
$CIDRAM['lang']['config_general_ban_override'] = 'Ghi đè "forbid_on_block" khi "infraction_limit" bị vượt quá? Khi ghi đè: Các yêu cầu bị chặn sản xuất một trang trống (tập tin mẫu không được sử dụng). 200 = Không ghi đè [Mặc định]; 403 = Ghi đè với "403 Forbidden"; 503 = Ghi đè với "503 Service unavailable".';
$CIDRAM['lang']['config_general_default_dns'] = 'Một dấu phẩy phân cách danh sách các máy chủ DNS để sử dụng cho tra cứu tên máy. Mặc định = "8.8.8.8,8.8.4.4" (Google DNS). CẢNH BÁO: Không thay đổi này, trừ khi bạn biết những gì bạn đang làm!';
$CIDRAM['lang']['config_general_disable_cli'] = 'Vô hiệu hóa chế độ CLI?';
$CIDRAM['lang']['config_general_disable_frontend'] = 'Vô hiệu hóa truy cập front-end?';
$CIDRAM['lang']['config_general_disable_webfonts'] = 'Vô hiệu hóa webfonts? True = Vâng; False = Không [Mặc định].';
$CIDRAM['lang']['config_general_emailaddr'] = 'Nếu bạn muốn, bạn có thể cung cấp một địa chỉ email ở đây để được trao cho người dùng khi họ đang bị chặn, cho họ để sử dụng như một điểm tiếp xúc cho hỗ trợ hay giúp đở cho trong trường hợp họ bị chặn bởi nhầm hay lỗi. CẢNH BÁO: Bất kỳ địa chỉ email mà bạn cung cấp ở đây sẽ chắc chắn nhất được mua lại bởi chương trình thư rác và cái nạo trong quá trình con của nó được sử dụng ở đây, và như vậy, nó khuyên rằng nếu bạn chọn để cung cấp một địa chỉ email ở đây, mà bạn đảm bảo rằng địa chỉ email bạn cung cấp ở đây là một địa chỉ dùng một lần hay một địa chỉ mà bạn không nhớ được thư rác (nói cách khác, có thể bạn không muốn sử dụng một cá nhân chính hay kinh doanh chính địa chỉ email).';
$CIDRAM['lang']['config_general_emailaddr_display_style'] = 'Bạn muốn địa chỉ email được trình bày như thế nào với người dùng?';
$CIDRAM['lang']['config_general_forbid_on_block'] = 'Cái nào tiêu đề nên CIDRAM phản ứng với khi các yêu cầu được bị chặn?';
$CIDRAM['lang']['config_general_FrontEndLog'] = 'Tập tin cho ghi cố gắng đăng nhập front-end. Chỉ định một tên tập tin, hoặc để trống để vô hiệu hóa.';
$CIDRAM['lang']['config_general_ipaddr'] = 'Nơi để tìm địa chỉ IP của các yêu cầu kết nối? (Hữu ích cho các dịch vụ như CloudFlare và vv). Mặc định = REMOTE_ADDR. CẢNH BÁO: Không thay đổi này, trừ khi bạn biết những gì bạn đang làm!';
$CIDRAM['lang']['config_general_lang'] = 'Xác định tiếng mặc định cho CIDRAM.';
$CIDRAM['lang']['config_general_logfile'] = 'Tập tin có thể đọc con người cho ghi tất cả các nỗ lực truy cập bị chặn. Chỉ định một tên tập tin, hoặc để trống để vô hiệu hóa.';
$CIDRAM['lang']['config_general_logfileApache'] = 'Tập tin Apache phong cách cho ghi tất cả các nỗ lực truy cập bị chặn. Chỉ định một tên tập tin, hoặc để trống để vô hiệu hóa.';
$CIDRAM['lang']['config_general_logfileSerialized'] = 'Tập tin tuần tự cho ghi tất cả các nỗ lực truy cập bị chặn. Chỉ định một tên tập tin, hoặc để trống để vô hiệu hóa.';
$CIDRAM['lang']['config_general_log_banned_ips'] = 'Bao gồm các yêu cầu bị chặn từ các IP bị cấm trong các tập tin đăng nhập? True = Vâng [Mặc định]; False = Không.';
$CIDRAM['lang']['config_general_maintenance_mode'] = 'Bật chế độ bảo trì? True = Vâng; False = Không [Mặc định]. Vô hiệu hoá mọi thứ khác ngoài các front-end. Đôi khi hữu ích khi cập nhật CMS, framework của bạn, vv.';
$CIDRAM['lang']['config_general_max_login_attempts'] = 'Số lượng tối đa cố gắng đăng nhập.';
$CIDRAM['lang']['config_general_numbers'] = 'Làm thế nào để bạn thích số được hiển thị? Chọn ví dụ có vẻ chính xác nhất cho bạn.';
$CIDRAM['lang']['config_general_protect_frontend'] = 'Chỉ định liệu các bảo vệ thường được cung cấp bởi CIDRAM nên được áp dụng cho các front-end. True = Vâng [Mặc định]; False = Không.';
$CIDRAM['lang']['config_general_search_engine_verification'] = 'Cố gắng xác minh các yêu cầu từ các máy tìm kiếm? Xác minh máy tìm kiếm đảm bảo rằng họ sẽ không bị cấm là kết quả của vượt quá giới các hạn vi phạm (cấm các máy tìm kiếm từ trang web của bạn thường sẽ có một tác động tiêu cực đến các xếp hạng máy tìm kiếm của bạn, SEO, vv). Khi xác minh được kích hoạt, các máy tìm kiếm có thể bị chặn như bình thường, nhưng sẽ không bị cấm. Khi xác minh không được kích hoạt, họ có thể bị cấm như là kết quả của vượt quá giới các hạn vi phạm. Ngoài ra, xác minh máy tìm kiếm cung cấp bảo vệ chống lại các yêu cầu giả máy tìm kiếm và chống lại các thực thể rằng là khả năng độc hại được giả mạo như là các máy tìm kiếm (những yêu cầu này sẽ bị chặn khi xác minh máy tìm kiếm được kích hoạt). True = Kích hoạt xác minh máy tìm kiếm [Mặc định]; False = Vô hiệu hóa xác minh máy tìm kiếm.';
$CIDRAM['lang']['config_general_silent_mode'] = 'CIDRAM nên âm thầm chuyển hướng cố gắng truy cập bị chặn thay vì hiển thị trang "Truy cập bị từ chối"? Nếu vâng, xác định vị trí để chuyển hướng cố gắng truy cập bị chặn để. Nếu không, để cho biến này được trống.';
$CIDRAM['lang']['config_general_timeFormat'] = 'Định dạng ngày tháng sử dụng bởi CIDRAM. Các tùy chọn bổ sung có thể được bổ sung theo yêu cầu.';
$CIDRAM['lang']['config_general_timeOffset'] = 'Múi giờ bù đắp trong phút.';
$CIDRAM['lang']['config_general_timezone'] = 'Múi giờ của bạn.';
$CIDRAM['lang']['config_general_truncate'] = 'Dọn dẹp các bản ghi khi họ được một kích thước nhất định? Giá trị là kích thước tối đa bằng B/KB/MB/GB/TB mà một tập tin bản ghi có thể tăng lên trước khi bị dọn dẹp. Giá trị mặc định 0KB sẽ vô hiệu hoá dọn dẹp (các bản ghi có thể tăng lên vô hạn). Lưu ý: Áp dụng cho tập tin riêng biệt! Kích thước tập tin bản ghi không được coi là tập thể.';
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
$CIDRAM['lang']['config_signatures_default_tracktime'] = 'Có bao nhiêu giây để giám sát các IP bị cấm bởi các mô-đun. Mặc định = 604800 (1 tuần).';
$CIDRAM['lang']['config_signatures_infraction_limit'] = 'Số lượng tối đa các vi phạm một IP được phép chịu trước khi nó bị cấm bởi các giám sát IP. Mặc định = 10.';
$CIDRAM['lang']['config_signatures_ipv4'] = 'Một danh sách các tập tin chữ ký IPv4 mà CIDRAM nên cố gắng để phân tích, ngăn cách bởi dấu phẩy.';
$CIDRAM['lang']['config_signatures_ipv6'] = 'Một danh sách các tập tin chữ ký IPv6 mà CIDRAM nên cố gắng để phân tích, ngăn cách bởi dấu phẩy.';
$CIDRAM['lang']['config_signatures_modules'] = 'Một danh sách các tập tin mô-đun để tải sau khi kiểm tra các chữ ký IPv4/IPv6, ngăn cách bởi dấu phẩy.';
$CIDRAM['lang']['config_signatures_track_mode'] = 'Khi vi phạm cần được tính? False = Khi IP bị chặn bởi các mô-đun. True = Khi IP bị chặn vì lý do bất kỳ.';
$CIDRAM['lang']['config_template_data_css_url'] = 'URL của tập tin CSS cho các chủ đề tùy chỉnh.';
$CIDRAM['lang']['config_template_data_Magnification'] = 'Phóng to chữ. Mặc định = 1.';
$CIDRAM['lang']['config_template_data_theme'] = 'Chủ đề mặc định để sử dụng cho CIDRAM.';
$CIDRAM['lang']['field_activate'] = 'Kích hoạt';
$CIDRAM['lang']['field_banned'] = 'Bị cấm';
$CIDRAM['lang']['field_blocked'] = 'Bị Chặn';
$CIDRAM['lang']['field_clear'] = 'Hủy bỏ';
$CIDRAM['lang']['field_clickable_link'] = 'Liên kết có thể nhấp';
$CIDRAM['lang']['field_component'] = 'Gói';
$CIDRAM['lang']['field_create_new_account'] = 'Tạo ra tài khoản mới';
$CIDRAM['lang']['field_deactivate'] = 'Vô hiệu hóa';
$CIDRAM['lang']['field_delete_account'] = 'Xóa tài khoản';
$CIDRAM['lang']['field_delete_file'] = 'Xóa Bỏ';
$CIDRAM['lang']['field_download_file'] = 'Tải Về';
$CIDRAM['lang']['field_edit_file'] = 'Chỉnh Sửa';
$CIDRAM['lang']['field_expiry'] = 'Hết hạn';
$CIDRAM['lang']['field_false'] = 'False (Sai)';
$CIDRAM['lang']['field_file'] = 'Tập Tin';
$CIDRAM['lang']['field_filename'] = 'Tên tập tin: ';
$CIDRAM['lang']['field_filetype_directory'] = 'Thư Mục';
$CIDRAM['lang']['field_filetype_info'] = 'Tập Tin {EXT}';
$CIDRAM['lang']['field_filetype_unknown'] = 'Không Xác Định';
$CIDRAM['lang']['field_first_seen'] = 'Lần đầu tiên nhìn thấy';
$CIDRAM['lang']['field_infractions'] = 'Vi phạm';
$CIDRAM['lang']['field_install'] = 'Cài Đặt';
$CIDRAM['lang']['field_ip_address'] = 'Địa Chỉ IP';
$CIDRAM['lang']['field_latest_version'] = 'Phiên bản mới nhất';
$CIDRAM['lang']['field_log_in'] = 'Đăng Nhập';
$CIDRAM['lang']['field_new_name'] = 'Tên mới:';
$CIDRAM['lang']['field_nonclickable_text'] = 'Văn bản không thể nhấp';
$CIDRAM['lang']['field_ok'] = 'OK';
$CIDRAM['lang']['field_options'] = 'Tùy Chọn';
$CIDRAM['lang']['field_password'] = 'Mật Khẩu';
$CIDRAM['lang']['field_permissions'] = 'Quyền';
$CIDRAM['lang']['field_range'] = 'Phạm vi (Đầu tiên – Cuối cùng)';
$CIDRAM['lang']['field_rename_file'] = 'Đổi tên';
$CIDRAM['lang']['field_reset'] = 'Thiết Lập Lại';
$CIDRAM['lang']['field_set_new_password'] = 'Đặt mật khẩu mới';
$CIDRAM['lang']['field_size'] = 'Kích thước tổng: ';
$CIDRAM['lang']['field_size_bytes'] = 'byte';
$CIDRAM['lang']['field_size_GB'] = 'GB';
$CIDRAM['lang']['field_size_KB'] = 'KB';
$CIDRAM['lang']['field_size_MB'] = 'MB';
$CIDRAM['lang']['field_size_TB'] = 'TB';
$CIDRAM['lang']['field_status'] = 'Tình Trạng';
$CIDRAM['lang']['field_system_timezone'] = 'Sử dụng múi giờ mặc định của hệ thống.';
$CIDRAM['lang']['field_tracking'] = 'Giám sát';
$CIDRAM['lang']['field_true'] = 'True (Đúng)';
$CIDRAM['lang']['field_uninstall'] = 'Gỡ Bỏ Cài Đặt';
$CIDRAM['lang']['field_update'] = 'Cập Nhật';
$CIDRAM['lang']['field_update_all'] = 'Cập Nhật Tất Cả';
$CIDRAM['lang']['field_upload_file'] = 'Tải lên tập tin mới';
$CIDRAM['lang']['field_username'] = 'Tên Người Dùng';
$CIDRAM['lang']['field_your_version'] = 'Phiên bản của bạn';
$CIDRAM['lang']['header_login'] = 'Vui lòng đăng nhập để tiếp tục.';
$CIDRAM['lang']['label_active_config_file'] = 'Tập tin cấu hình kích hoạt: ';
$CIDRAM['lang']['label_branch'] = 'Chi nhánh ổn định mới nhất:';
$CIDRAM['lang']['label_cidram'] = 'Phiên bản CIDRAM đang được dùng:';
$CIDRAM['lang']['label_false_positive_risk'] = 'Nguy cơ sai tích cực: ';
$CIDRAM['lang']['label_os'] = 'Hệ điều hành đang được dùng:';
$CIDRAM['lang']['label_php'] = 'Phiên bản PHP đang được dùng:';
$CIDRAM['lang']['label_sapi'] = 'SAPI đang được dùng:';
$CIDRAM['lang']['label_stable'] = 'Ổn định mới nhất:';
$CIDRAM['lang']['label_sysinfo'] = 'Thông tin hệ thống:';
$CIDRAM['lang']['label_unstable'] = 'Không ổn định mới nhất:';
$CIDRAM['lang']['link_accounts'] = 'Tài Khoản';
$CIDRAM['lang']['link_cidr_calc'] = 'Máy Tính CIDR';
$CIDRAM['lang']['link_config'] = 'Cấu Hình';
$CIDRAM['lang']['link_documentation'] = 'Tài Liệu';
$CIDRAM['lang']['link_file_manager'] = 'Quản Lý Tập Tin';
$CIDRAM['lang']['link_home'] = 'Trang Chủ';
$CIDRAM['lang']['link_ip_test'] = 'Kiểm Tra IP';
$CIDRAM['lang']['link_ip_tracking'] = 'Giám sát IP';
$CIDRAM['lang']['link_logs'] = 'Bản Ghi';
$CIDRAM['lang']['link_updates'] = 'Cập Nhật';
$CIDRAM['lang']['logs_logfile_doesnt_exist'] = 'Bản ghi đã chọn không tồn tại!';
$CIDRAM['lang']['logs_no_logfiles_available'] = 'Không có bản ghi có sẵn.';
$CIDRAM['lang']['logs_no_logfile_selected'] = 'Không có bản ghi được chọn.';
$CIDRAM['lang']['max_login_attempts_exceeded'] = 'Số lượng tối đa cố gắng đăng nhập đã bị vượt quá; Truy cập bị từ chối.';
$CIDRAM['lang']['previewer_days'] = 'Ngày';
$CIDRAM['lang']['previewer_hours'] = 'Giờ';
$CIDRAM['lang']['previewer_minutes'] = 'Phút';
$CIDRAM['lang']['previewer_months'] = 'Tháng';
$CIDRAM['lang']['previewer_seconds'] = 'Giây';
$CIDRAM['lang']['previewer_weeks'] = 'Tuần';
$CIDRAM['lang']['previewer_years'] = 'Năm';
$CIDRAM['lang']['response_accounts_already_exists'] = 'Một tài khoản với tên người dùng này đã tồn tại!';
$CIDRAM['lang']['response_accounts_created'] = 'Tài khoản tạo ra thành công!';
$CIDRAM['lang']['response_accounts_deleted'] = 'Tài khoản xóa thành công!';
$CIDRAM['lang']['response_accounts_doesnt_exist'] = 'Tài khoản này không tồn tại.';
$CIDRAM['lang']['response_accounts_password_updated'] = 'Mật khẩu cập nhật thành công!';
$CIDRAM['lang']['response_activated'] = 'Kích hoạt thành công.';
$CIDRAM['lang']['response_activation_failed'] = 'Không thể kích hoạt!';
$CIDRAM['lang']['response_checksum_error'] = 'Kiểm tra lỗi! Tập tin bị từ chối!';
$CIDRAM['lang']['response_component_successfully_installed'] = 'Gói cài đặt thành công.';
$CIDRAM['lang']['response_component_successfully_uninstalled'] = 'Gói gỡ bỏ cài đặt thành công.';
$CIDRAM['lang']['response_component_successfully_updated'] = 'Gói cập nhật thành công.';
$CIDRAM['lang']['response_component_uninstall_error'] = 'Có lỗi xảy ra trong khi cố gắng để gỡ bỏ cài đặt các gói.';
$CIDRAM['lang']['response_component_update_error'] = 'Có lỗi xảy ra trong khi cố gắng để cập nhật các gói.';
$CIDRAM['lang']['response_configuration_updated'] = 'Cấu hình cập nhật thành công.';
$CIDRAM['lang']['response_deactivated'] = 'Vô hiệu hóa thành công.';
$CIDRAM['lang']['response_deactivation_failed'] = 'Không thể vô hiệu hóa!';
$CIDRAM['lang']['response_delete_error'] = 'Không thể xóa!';
$CIDRAM['lang']['response_directory_deleted'] = 'Thư mục xóa thành công!';
$CIDRAM['lang']['response_directory_renamed'] = 'Đổi tên thư mục thành công!';
$CIDRAM['lang']['response_error'] = 'Lỗi';
$CIDRAM['lang']['response_file_deleted'] = 'Tập tin xóa thành công!';
$CIDRAM['lang']['response_file_edited'] = 'Tập tin sửa đổi thành công!';
$CIDRAM['lang']['response_file_renamed'] = 'Đổi tên tập tin thành công!';
$CIDRAM['lang']['response_file_uploaded'] = 'Tập tin tải lên thành công!';
$CIDRAM['lang']['response_login_invalid_password'] = 'Thất bại đăng nhập! Mật khẩu không hợp lệ!';
$CIDRAM['lang']['response_login_invalid_username'] = 'Thất bại đăng nhập! Tên người dùng không tồn tại!';
$CIDRAM['lang']['response_login_password_field_empty'] = 'Mật khẩu là trống!';
$CIDRAM['lang']['response_login_username_field_empty'] = 'Tên người dùng là trống!';
$CIDRAM['lang']['response_no'] = 'Không';
$CIDRAM['lang']['response_rename_error'] = 'Không thể đổi tên!';
$CIDRAM['lang']['response_tracking_cleared'] = 'Giám sát được hủy bỏ.';
$CIDRAM['lang']['response_updates_already_up_to_date'] = 'Đã cập nhật.';
$CIDRAM['lang']['response_updates_not_installed'] = 'Gói không được cài đặt!';
$CIDRAM['lang']['response_updates_not_installed_php'] = 'Gói không được cài đặt (đòi hỏi PHP {V})!';
$CIDRAM['lang']['response_updates_outdated'] = 'Hết hạn!';
$CIDRAM['lang']['response_updates_outdated_manually'] = 'Hết hạn (vui lòng cập nhật bằng tay)!';
$CIDRAM['lang']['response_updates_outdated_php_version'] = 'Hết hạn (đòi hỏi PHP {V})!';
$CIDRAM['lang']['response_updates_unable_to_determine'] = 'Không thể xác định.';
$CIDRAM['lang']['response_upload_error'] = 'Không thể tải lên!';
$CIDRAM['lang']['response_yes'] = 'Vâng';
$CIDRAM['lang']['state_complete_access'] = 'Truy cập đầy đủ';
$CIDRAM['lang']['state_component_is_active'] = 'Gói này đang kích hoạt.';
$CIDRAM['lang']['state_component_is_inactive'] = 'Gói này đang vô hiệu hóa.';
$CIDRAM['lang']['state_component_is_provisional'] = 'Gói này đang thỉnh thoảng hoạt động.';
$CIDRAM['lang']['state_default_password'] = 'Cảnh báo: Nó là sử dụng mật khẩu mặc định!';
$CIDRAM['lang']['state_loadtime'] = 'Yêu cầu trang hoàn thành trong <span class="txtRd">%s</span> giây.';
$CIDRAM['lang']['state_logged_in'] = 'Được đăng nhập.';
$CIDRAM['lang']['state_logs_access_only'] = 'Bản ghi truy cập chỉ';
$CIDRAM['lang']['state_password_not_valid'] = 'Cảnh báo: Tài khoản này không được sử dụng một mật khẩu hợp lệ!';
$CIDRAM['lang']['state_risk_high'] = 'Cao';
$CIDRAM['lang']['state_risk_low'] = 'Thấp';
$CIDRAM['lang']['state_risk_medium'] = 'Trung bình';
$CIDRAM['lang']['state_tracking'] = 'Hiện đang giám sát <span class="txtRd">%s</span> IP.';
$CIDRAM['lang']['switch-hide-non-outdated-set-false'] = 'Đừng ẩn các không hết hạn';
$CIDRAM['lang']['switch-hide-non-outdated-set-true'] = 'Ẩn các không hết hạn';
$CIDRAM['lang']['switch-hide-unused-set-false'] = 'Đừng ẩn các không cài đặt';
$CIDRAM['lang']['switch-hide-unused-set-true'] = 'Ẩn các không cài đặt';
$CIDRAM['lang']['tip_accounts'] = 'Xin chào, {username}.<br />Trang tài khoản cho phép bạn kiểm soát những người có thể truy cập các front-end CIDRAM.';
$CIDRAM['lang']['tip_cidr_calc'] = 'Xin chào, {username}.<br />Máy tính CIDR cho phép bạn để tính toán mà các CIDR một địa chỉ IP thuộc về.';
$CIDRAM['lang']['tip_config'] = 'Xin chào, {username}.<br />Trang cấu hình cho phép bạn chỉnh sửa các cấu hình CIDRAM từ các front-end.';
$CIDRAM['lang']['tip_donate'] = 'CIDRAM được cung cấp miễn phí, nhưng nếu bạn muốn đóng góp cho dự án, bạn có thể làm như vậy bằng cách nhấn vào nút tặng.';
$CIDRAM['lang']['tip_enter_ips_here'] = 'Nhập IP ở đây.';
$CIDRAM['lang']['tip_enter_ip_here'] = 'Nhập IP ở đây.';
$CIDRAM['lang']['tip_file_manager'] = 'Xin chào, {username}.<br />Quản lý tập tin cho phép bạn xóa bỏ, chỉnh sửa, tải lên, và tải về các tập tin. Sử dụng thận trọng (bạn có thể phá vỡ cài đặt của bạn với điều này).';
$CIDRAM['lang']['tip_home'] = 'Xin chào, {username}.<br />Đây là trang chủ cho các front-end CIDRAM. Chọn một liên kết từ thực đơn bên trái để tiếp tục.';
$CIDRAM['lang']['tip_ip_test'] = 'Xin chào, {username}.<br />Trang kiểm tra IP cho phép bạn kiểm tra nếu địa chỉ IP bị chặn bằng các chữ ký hiện đang được cài đặt.';
$CIDRAM['lang']['tip_ip_tracking'] = 'Xin chào, {username}.<br />Các trang cho giám sát IP cho phép bạn kiểm tra tình trạng giám sát các địa chỉ IP, để kiểm tra mà trong số họ đã bị cấm, và hủy bỏ giám sát họ nếu bạn muốn làm như vậy.';
$CIDRAM['lang']['tip_login'] = 'Tên người dùng mặc định: <span class="txtRd">admin</span> – Mật khẩu mặc định: <span class="txtRd">password</span>';
$CIDRAM['lang']['tip_logs'] = 'Xin chào, {username}.<br />Chọn một bản ghi từ danh sách dưới đây để xem nội dung của bản ghi này.';
$CIDRAM['lang']['tip_see_the_documentation'] = 'Xem <a href="https://github.com/CIDRAM/CIDRAM/blob/master/_docs/readme.vi.md#SECTION6">tài liệu</a> để biết thông tin về các chỉ thị cấu hình khác nhau và mục đích của họ.';
$CIDRAM['lang']['tip_updates'] = 'Xin chào, {username}.<br />Trang cập nhật cho phép bạn cài đặt, gỡ bỏ cài đặt, và cập nhật các gói khác nhau cho CIDRAM (các gói cốt lõi, chữ ký, bổ sung, các tập tin L10N, vv).';
$CIDRAM['lang']['title_accounts'] = 'CIDRAM – Tài Khoản';
$CIDRAM['lang']['title_cidr_calc'] = 'CIDRAM – Máy Tính CIDR';
$CIDRAM['lang']['title_config'] = 'CIDRAM – Cấu Hình';
$CIDRAM['lang']['title_file_manager'] = 'CIDRAM – Quản Lý Tập Tin';
$CIDRAM['lang']['title_home'] = 'CIDRAM – Trang Chủ';
$CIDRAM['lang']['title_ip_test'] = 'CIDRAM – Kiểm Tra IP';
$CIDRAM['lang']['title_ip_tracking'] = 'CIDRAM – Giám sát IP';
$CIDRAM['lang']['title_login'] = 'CIDRAM – Đăng Nhập';
$CIDRAM['lang']['title_logs'] = 'CIDRAM – Bản Ghi';
$CIDRAM['lang']['title_updates'] = 'CIDRAM – Cập Nhật';
$CIDRAM['lang']['warning'] = 'Cảnh báo:';
$CIDRAM['lang']['warning_php_1'] = 'Phiên bản PHP của bạn không được hỗ trợ tích cực nữa! Đang cập nhật được khuyến khích!';
$CIDRAM['lang']['warning_php_2'] = 'Phiên bản PHP của bạn rất dễ bị tổn thương! Đang cập nhật được khuyến khích mạnh mẽ!';
$CIDRAM['lang']['warning_signatures_1'] = 'Không có tập tin chữ ký nào đang hoạt động!';

$CIDRAM['lang']['info_some_useful_links'] = 'Một số liên kết hữu ích:<ul>
            <li><a href="https://github.com/CIDRAM/CIDRAM/issues">Vấn đề cho CIDRAM @ GitHub</a> – Trang các vấn đề cho CIDRAM (hỗ trợ, vv).</li>
            <li><a href="http://www.spambotsecurity.com/forum/viewforum.php?f=61">CIDRAM @ Spambot Security</a> – Diễn đàn thảo luận cho CIDRAM (hỗ trợ, vv).</li>
            <li><a href="https://wordpress.org/plugins/cidram/">CIDRAM @ WordPress.org</a> – Plugin WordPress cho CIDRAM.</li>
            <li><a href="https://sourceforge.net/projects/cidram/">CIDRAM @ SourceForge</a> – Tải về gương thay thế cho CIDRAM.</li>
            <li><a href="https://websectools.com/">WebSecTools.com</a> – Một bộ sưu tập các công cụ quản trị trang web đơn giản để bảo vệ các trang web.</li>
            <li><a href="https://macmathan.info/blocklists">Danh sách chặn tùy chọn @ MacMathan.info</a> – Chứa các danh sách chặn tùy chọn mà có thể được thêm vào CIDRAM để chặn bất kỳ quốc gia không mong muốn từ truy cập vào trang web của bạn.</li>
            <li><a href="https://www.facebook.com/groups/2204685680/">Global PHP Group @ Facebook</a> – PHP tài nguyên học tập và thảo luận.</li>
            <li><a href="https://php.earth/">PHP.earth</a> – PHP tài nguyên học tập và thảo luận.</li>
            <li><a href="http://bgp.he.net/">Hurricane Electric BGP Toolkit</a> – Nhận các CIDR từ các ASN, xác định các mối quan hệ các ASN, khám phá vùng các ASN dựa trên các tên mạng, vv.</li>
            <li><a href="https://www.stopforumspam.com/forum/">Diễn đàn @ Stop Forum Spam</a> – Diễn đàn thảo luận hữu ích về việc ngưng diễn đàn thư rác.</li>
            <li><a href="https://www.stopforumspam.com/aggregate">Tập hợp IP @ Stop Forum Spam</a> – Công cụ hữu ích cho kết hợp các IP IPv4.</li>
            <li><a href="https://radar.qrator.net/">Radar bởi Qrator</a> – Công cụ hữu ích để kiểm tra kết nối ASN cũng như cho nhiều thông tin khác về ASN.</li>
            <li><a href="http://www.ipdeny.com/ipblocks/">Chặn quốc gia IP @ IPdeny</a> – Một dịch vụ tuyệt vời và chính xác để tạo ra chữ ký cho các nước.</li>
            <li><a href="https://www.google.com/transparencyreport/safebrowsing/malware/">Google Malware Dashboard</a> – Hiển thị báo cáo về tỷ lệ lây nhiễm phần mềm độc hại cho các ASN.</li>
            <li><a href="https://www.spamhaus.org/statistics/botnet-asn/">Dự án Spamhaus</a> – Hiển thị báo cáo về tỷ lệ lây nhiễm chương trình thư rác cho các ASN.</li>
            <li><a href="https://www.abuseat.org/public/asn.html">Danh sách chặn hỗn hợp @ Abuseat.org</a> – Hiển thị báo cáo về tỷ lệ lây nhiễm chương trình thư rác cho các ASN.</li>
            <li><a href="https://abuseipdb.com/">AbuseIPDB</a> – Duy trì một cơ sở dữ liệu của các IP mà lạm dụng và biết; Cung cấp một API để kiểm tra và báo cáo các IP.</li>
            <li><a href="https://www.megarbl.net/index.php">MegaRBL.net</a> – Duy trì danh sách các spammer được biết; Hữu ích cho việc kiểm tra các hoạt động thư rác từ các IP/ASN.</li>
        </ul>';
