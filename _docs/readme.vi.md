## Tài liệu của CIDRAM (Tiếng Việt).

### Nội dung
- 1. [LỜI GIỚI THIỆU](#SECTION1)
- 2. [CÁCH CÀI ĐẶT](#SECTION2)
- 3. [CÁCH SỬ DỤNG](#SECTION3)
- 4. [QUẢN LÝ FRONT-END](#SECTION4)
- 5. [TẬP TIN BAO GỒM TRONG GÓI NÀY](#SECTION5)
- 6. [TÙY CHỌN CHO CẤU HÌNH](#SECTION6)
- 7. [ĐỊNH DẠNG CỦA CHỬ KÝ](#SECTION7)
- 8. [NHỮNG VẤN ĐỀ HỢP TƯƠNG TÍCH](#SECTION8)
- 9. [NHỮNG CÂU HỎI THƯỜNG GẶP (FAQ)](#SECTION9)
- 10. *Dành riêng cho các bổ sung trong tương lai cho tài liệu.*
- 11. [THÔNG TIN HỢP PHÁP](#SECTION11)

*Lưu ý về bản dịch: Trong trường hợp có sai sót (ví dụ, sự khác biệt giữa bản dịch, lỗi chính tả, vv), phiên bản tiếng Anh của README được coi là phiên bản gốc và có thẩm quyền. Nếu bạn tìm thấy bất kỳ lỗi, giúp đỡ của bạn trong việc điều chỉnh họ sẽ được hoan nghênh.*

---


### 1. <a name="SECTION1"></a>LỜI GIỚI THIỆU

CIDRAM (Classless Inter-Domain Routing Access Manager) là một kịch bản PHP được thiết kế để bảo vệ các trang mạng bằng cách ngăn chặn các yêu cầu có nguồn gốc từ các địa chỉ IP coi như là nguồn của lưu lượng không mong muốn, bao gồm (nhưng không giới hạn) giao thông từ thiết bị đầu cuối truy cập không phải con người, dịch vụ điện toán đám mây, chương trình gửi thư rác, công cụ cào, vv. Nó làm điều này bằng cách tính toán CIDR có thể các địa chỉ IP cung cấp từ các yêu cầu gửi đến và sau đó cố gắng để phù hợp với những CIDR có thể chống lại các tập tin chữ ký của nó (các tập tin chữ ký chứa danh sách các CIDR các địa chỉ IP coi như là nguồn của lưu lượng không mong muốn); Nếu trận đấu được tìm thấy, các yêu cầu được chặn.

*(Xem: ["CIDR" là gì?](#WHAT_IS_A_CIDR)).*

BẢN QUYỀN CIDRAM 2016 và hơn GNU/GPLv2 by Caleb M (Maikuolan).

Bản này là chương trình miễn phí; bạn có thể phân phối lại hoạc sửa đổi dưới điều kiện của GNU Giấy Phép Công Cộng xuất bản bởi Free Software Foundation; một trong giấy phép phần hai, hoạc (tùy theo sự lựa chọn của bạn) bất kỳ phiên bản nào sau này. Bản này được phân phối với hy vọng rằng nó sẽ có hữu ích, nhưng mà KHÔNG CÓ BẢO HÀNH; ngay cả những bảo đảm ngụ ý KHẢ NĂNG BÁN HÀNG hoạc PHÙ HỢP VỚI MỤC ĐÍT VÀO. Hảy xem GNU Giấy Phép Công Cộng để biết them chi tiết, nằm trong tập tin `LICENSE.txt`, và kho chứa của tập tin này có thể tiềm đước tại:
- <http://www.gnu.org/licenses/>.
- <http://opensource.org/licenses/>.

Tài liệu này và các gói liên quan của nó có thể được tải về miễn phí từ [GitHub](https://cidram.github.io/).

---


### 2. <a name="SECTION2"></a>CÁCH CÀI ĐẶT

#### 2.0 CÀI ĐẶT THỦ CÔNG

1) Nếu bạn đang đọc cái này thì tôi hy vọng là bạn đã tải về một bản sao kho lưu trữ của bản, giải nén nội dung của nó và nó đang nằm ở một nơi nào đó trên máy tính của bạn. Từ đây, bạn sẽ muốn đặt nội dung ở một nơi trên máy chủ hoặc CMS của bạn. Một thư mục chẳng hạn như `/public_html/cidram/` hay tương tự (mặc dù sự lựa chọn của bạn không quan trọng, miễn là nó an toàn và bạn hài lòng với sự lựa chọn) sẽ đủ.. *Trước khi bạn bắt đầu tải lên, hảy tiếp tục đọc..*

2) Đổi tên `config.ini.RenameMe` đến `config.ini` (nằm bên trong `vault`), và nếu bạn muốn (đề nghị mạnh mẽ cho người dùng cao cấp, nhưng không đề nghị cho người mới bắt đầu hay cho người thiếu kinh nghiệm), mở nó (tập tin này bao gồm tất cả các tùy chọn có sẵn cho CIDRAM; trên mỗi tùy chọn nên có một nhận xét ngắn gọn mô tả những gì nó làm và những gì nó cho). Điều chỉnh các tùy chọn như bạn thấy phù hợp, theo bất cứ điều gì là thích hợp cho tập hợp cụ thể của bạn lên. Lưu tập tin, đóng.

3) Tải nội dung lên (CIDRAM và tập tin của nó) vào thư mục bạn đã chọn trước (bạn không cần phải dùng tập tin `*.txt`/`*.md`, nhưng chủ yếu, bạn nên tải lên tất cả mọi thứ).

4) CHMOD thư mục `vault` thành "755" (nếu có vấn đề, bạn có thể thử "777", mặc dù này là kém an toàn). Các thư mục chính kho lưu trữ các nội dung (một trong những cái bạn đã chọn trước), bình thường, có thể riêng, nhưng tình hình CHMOD nên kiểm tra, nếu bạn đã có vấn đề cho phép trong quá khứ về hệ thống của bạn (theo mặc định, nên giống như "755").

5) Tiếp theo, bạn sẽ cần "nối" CIDRAM vào hệ thống của bạn hay CMS. Có một số cách mà bạn có thể "nối" bản chẳng hạn như CIDRAM vào hệ thống hoạc CMS, nhưng cách đơn giản nhất là cần có bản vào cốt lõi ở đầu của tập tin hoạc hệ thống hay CMS của bạn (một mà thường sẽ luôn luôn được nạp khi ai đó truy cập bất kỳ trang nào trên trang mạng của bạn) bằng cách sử dụng một lời chỉ thị `require` hoạc `include`. Thường, cái nàu sẽ được lưu trong một thư mục như `/includes`, `/assets` hoạc `/functions`, và sẽ thường được gọi là `init.php`, `common_functions.php`, `functions.php` hoạc tương tự. Bạn sẽ cần tiềm ra tập tin nào cho trường hợp của bạn; Nếu bạn gặp khó khăn trong việc này ra cho chính mình, hãy truy các trang issues (các vấn đề) của CIDRAM và cho chúng tôi biêt. Để làm chuyện này [sử dụng `require` họac `include`], đánh các dòng mã sao đây vào đầu của cốt lõi của tập tin, thay thế các dây chứa bên trong các dấu ngoặc kép với địa chỉ chính xác của tập tin `loader.php` (địa chỉ địa phương, chứ không phải địa chỉ HTTP; nó sẽ nhình gióng địa chỉ kho nói ở trên).

`<?php require '/user_name/public_html/cidram/loader.php'; ?>`

Lưu tập tin, đóng lại, tải lên lại.

-- CÁCH KHÁC --

Nếu bạn đang sử dụng trang chủ Apache và nếu bạn có thể truy cập `php.ini`, bạn có thể sử dụng `auto_prepend_file` chỉ thị để thêm vào trước CIDRAM bất cứ khi nào bất kỳ yêu cầu PHP được xin. Gióng như:

`auto_prepend_file = "/user_name/public_html/cidram/loader.php"`

Hoạc cái này trong tập tin `.htaccess`:

`php_value auto_prepend_file "/user_name/public_html/cidram/loader.php"`

6) Đó là tất cả mọi thứ! :-)

#### 2.1 CÀI ĐẶT VỚI COMPOSER

[CIDRAM được đăng ký với Packagist](https://packagist.org/packages/cidram/cidram), và như vậy, nếu bạn đã quen với Composer, bạn có thể sử dụng Composer để cài đặt CIDRAM (bạn vẫn cần phải chuẩn bị cấu hình và kết nối; xem "cài đặt thủ công" bước 2 và 5).

`composer require cidram/cidram`

#### 2.2 CÀI ĐẶT CHO WORDPRESS

Nếu bạn muốn sử dụng CIDRAM với WordPress, bạn có thể bỏ qua tất cả các hướng dẫn ở trên. [CIDRAM được đăng ký như một plugin với cơ sở dữ liệu plugin của WordPress](https://wordpress.org/plugins/cidram/), và bạn có thể cài đặt CIDRAM trực tiếp từ các bảng điều khiển plugin. Bạn có thể cài đặt nó theo cách tương tự như các plugin khác, và không có bước bổ sung được yêu cầu. Giống như với các phương pháp cài đặt khác, bạn có thể tùy chỉnh cài đặt của bạn bằng cách sửa đổi nội dung của tập tin `config.ini` hay bằng cách sử dụng trang cấu hình của front-end. Nếu bạn kích hoạt front-end của CIDRAM và cập nhật CIDRAM bằng cách sử dụng trang cập nhật của front-end, điều này sẽ tự động đồng bộ các thông tin phiên bản plugin với thông tin được hiển thị trong các bảng điều khiển plugin.

*Cảnh báo: Đang nhật CIDRAM qua bảng điều khiển plugin kết quả trong một cài đặt sạch sẽ! Nếu bạn đã tùy chỉnh cài đặt (thay đổi cấu hình của bạn, cài đặt các mô-đun, vv), những tuỳ chỉnh này sẽ bị mất khi đang nhật thông qua bảng điều khiển plugin! Các tập tin đăng nhập cũng sẽ bị mất khi đang nhật thông qua bảng điều khiển plugin! Để bảo vệ các tập tin đăng nhập và tùy chỉnh, đang nhật thông qua trang đang nhật front-end CIDRAM.*

---


### 3. <a name="SECTION3"></a>CÁCH SỬ DỤNG

CIDRAM nên tự động chặn các yêu cầu không mong muốn để trang mạng của bạn mà không cần bất kỳ hỗ trợ bằng tay, trừ cài đặt.

Bạn có thể tùy chỉnh cấu hình của bạn và tùy chỉnh mà CIDR bị chặn bằng cách sửa đổi tập tin cấu hình hay tập tin chữ ký của bạn.

Nếu bạn gặp bất kỳ sai tích cực, xin vui lòng liên hệ với tôi để cho tôi biết về nó. *(Xem: ["Sai tích cực" là gì?](#WHAT_IS_A_FALSE_POSITIVE)).*

CIDRAM có thể được cập nhật bằng tay hoặc thông qua front-end. CIDRAM cũng có thể được cập nhật qua Composer hoặc WordPress, nếu ban đầu được cài đặt qua các phương tiện đó.

---


### 4. <a name="SECTION4"></a>QUẢN LÝ FRONT-END

#### 4.0 FRONT-END LÀ GÌ.

Các front-end cung cấp một cách thuận tiện và dễ dàng để duy trì, quản lý và cập nhật cài đặt CIDRAM của bạn. Bạn có thể xem, chia sẻ và tải về các tập tin bản ghi thông qua các trang bản ghi, bạn có thể sửa đổi cấu hình thông qua các trang cấu hình, bạn có thể cài đặt và gỡ bỏ cài đặt các thành phần thông qua các trang cập nhật, và bạn có thể tải lên, tải về, và sửa đổi các tập tin trong vault của bạn thông qua các quản lý tập tin.

Các front-end được tắt theo mặc định để ngăn chặn truy cập trái phép (truy cập trái phép có thể có hậu quả đáng kể cho trang web của bạn và an ninh của mình). Hướng dẫn cho phép nó được bao gồm bên dưới đoạn này.

#### 4.1 LÀM THẾ NÀO ĐỂ KÍCH HOẠT FRONT-END.

1) Xác định vị trí các chỉ thị `disable_frontend` bên trong `config.ini`, và đặt nó vào `false` (nó sẽ là `true` bởi mặc định).

2) Truy cập `loader.php` từ trình duyệt của bạn (ví dụ, `http://localhost/cidram/loader.php`).

3) Đăng nhập với tên người dùng và mật khẩu mặc định (admin/password).

Chú thích: Sau khi bạn đã đăng nhập lần đầu tiên, để ngăn chặn truy cập trái phép vào các front-end, bạn phải ngay lập tức thay đổi tên người dùng và mật khẩu của bạn! Điều này là rất quan trọng, bởi vì nó có thể tải lên các mã PHP tùy ý để trang web của bạn thông qua các front-end.

#### 4.2 LÀM THẾ NÀO ĐỂ SỬ DỤNG FRONT-END.

Các hướng dẫn được cung cấp trên mỗi trang của front-end, để giải thích một cách chính xác để sử dụng nó và mục đích của nó. Nếu bạn cần giải thích thêm hay bất kỳ sự hỗ trợ đặc biệt, vui lòng liên hệ hỗ trợ. Cũng thế, có một số video trên YouTube có thể giúp bằng cách viện trợ trực quan.


---


### 5. <a name="SECTION5"></a>TẬP TIN BAO GỒM TRONG GÓI NÀY

Sau đây là một danh sách tất cả các tập tin mà cần phải có được bao gồm trong bản sao lưu của kịch bản này khi bạn tải về nó, cùng với một mô tả ngắn cho những gì tất cả những tập tin này là dành cho.

Tập tin | Chi tiết
----|----
/_docs/ | Thư mực cho tài liệu.
/_docs/readme.ar.md | Tài liệu tiếng Ả Rập.
/_docs/readme.de.md | Tài liệu tiếng Đức.
/_docs/readme.en.md | Tài liệu tiếng Anh.
/_docs/readme.es.md | Tài liệu tiếng Tây Ban Nha.
/_docs/readme.fr.md | Tài liệu tiếng Pháp.
/_docs/readme.id.md | Tài liệu tiếng Indonesia.
/_docs/readme.it.md | Tài liệu tiếng Ý.
/_docs/readme.ja.md | Tài liệu tiếng Nhật.
/_docs/readme.ko.md | Tài liệu tiếng Hàn.
/_docs/readme.nl.md | Tài liệu tiếng Hà Lan.
/_docs/readme.pt.md | Tài liệu tiếng Bồ Đào Nha.
/_docs/readme.ru.md | Tài liệu tiếng Nga.
/_docs/readme.ur.md | Tài liệu tiếng Urdu.
/_docs/readme.vi.md | Tài liệu tiếng Việt.
/_docs/readme.zh-TW.md | Tài liệu tiếng Trung Quốc (truyền thống).
/_docs/readme.zh.md | Tài liệu tiếng Trung Quốc (giản thể).
/vault/ | Vault thư mục (chứa các tập tin khác nhau).
/vault/fe_assets/ | Các tài sản front-end.
/vault/fe_assets/.htaccess | Tập tin "hypertext access" / tập tin truy cập siêu văn bản (bảo vệ tập tin bí mật khỏi bị truy cập bởi nguồn không được ủy quyền).
/vault/fe_assets/_accounts.html | Tập tin mẫu HTML cho trang tài khoản của front-end.
/vault/fe_assets/_accounts_row.html | Tập tin mẫu HTML cho trang tài khoản của front-end.
/vault/fe_assets/_cache.html | Tập tin mẫu HTML cho trang dữ liệu cache của front-end.
/vault/fe_assets/_cidr_calc.html | Tập tin mẫu HTML cho máy tính CIDR.
/vault/fe_assets/_cidr_calc_row.html | Tập tin mẫu HTML cho máy tính CIDR.
/vault/fe_assets/_config.html | Tập tin mẫu HTML cho trang cấu hình của front-end.
/vault/fe_assets/_config_row.html | Tập tin mẫu HTML cho trang cấu hình của front-end.
/vault/fe_assets/_files.html | Tập tin mẫu HTML cho quản lý tập tin.
/vault/fe_assets/_files_edit.html | Tập tin mẫu HTML cho quản lý tập tin.
/vault/fe_assets/_files_rename.html | Tập tin mẫu HTML cho quản lý tập tin.
/vault/fe_assets/_files_row.html | Tập tin mẫu HTML cho quản lý tập tin.
/vault/fe_assets/_home.html | Tập tin mẫu HTML cho trang chủ của front-end.
/vault/fe_assets/_ip_aggregator.html | Tập tin mẫu HTML cho tập hợp IP.
/vault/fe_assets/_ip_test.html | Tập tin mẫu HTML cho trang kiểm tra IP.
/vault/fe_assets/_ip_test_row.html | Tập tin mẫu HTML cho trang kiểm tra IP.
/vault/fe_assets/_ip_tracking.html | Tập tin mẫu HTML cho trang giám sát IP.
/vault/fe_assets/_ip_tracking_row.html | Tập tin mẫu HTML cho trang giám sát IP.
/vault/fe_assets/_login.html | Tập tin mẫu HTML cho đăng nhập front-end.
/vault/fe_assets/_logs.html | Tập tin mẫu HTML cho trang bản ghi của front-end.
/vault/fe_assets/_nav_complete_access.html | Tập tin mẫu HTML cho các liên kết điều hướng của front-end, cho những người có quyền truy cập đầy đủ.
/vault/fe_assets/_nav_logs_access_only.html | Tập tin mẫu HTML cho các liên kết điều hướng của front-end, cho những người có quyền bản ghi truy cập chỉ.
/vault/fe_assets/_range.html | Tập tin mẫu HTML cho bảng dãy.
/vault/fe_assets/_range_row.html | Tập tin mẫu HTML cho bảng dãy.
/vault/fe_assets/_sections.html | Tập tin mẫu HTML cho danh sách phần.
/vault/fe_assets/_sections_row.html | Tập tin mẫu HTML cho danh sách phần.
/vault/fe_assets/_statistics.html | Tập tin mẫu HTML cho trang thống kê của front-end.
/vault/fe_assets/_updates.html | Tập tin mẫu HTML cho trang cập nhật của front-end.
/vault/fe_assets/_updates_row.html | Tập tin mẫu HTML cho trang cập nhật của front-end.
/vault/fe_assets/frontend.css | CSS định kiểu cho các front-end.
/vault/fe_assets/frontend.dat | Cơ sở dữ liệu cho các front-end (chứa thông tin tài khoản, thông tin phiên, và bộ nhớ cache; chỉ tạo ra nếu front-end được kích hoạt và sử dụng).
/vault/fe_assets/frontend.html | Các chính tập tin mẫu HTML cho các front-end.
/vault/fe_assets/icons.php | Tập tin cho các biểu tượng (được sử dụng bởi các quản lý tập tin front-end).
/vault/fe_assets/pips.php | Tập tin cho các pip (được sử dụng bởi các quản lý tập tin front-end).
/vault/fe_assets/scripts.js | Chứa dữ liệu JavaScript cho front-end.
/vault/lang/ | Chứa dữ liệu tiếng cho CIDRAM.
/vault/lang/.htaccess | Tập tin "hypertext access" / tập tin truy cập siêu văn bản (bảo vệ tập tin bí mật khỏi bị truy cập bởi nguồn không được ủy quyền).
/vault/lang/lang.ar.cli.php | Dữ liệu tiếng Ả Rập cho CLI.
/vault/lang/lang.ar.fe.php | Dữ liệu tiếng Ả Rập cho các front-end.
/vault/lang/lang.ar.php | Dữ liệu tiếng Ả Rập.
/vault/lang/lang.bn.cli.php | Dữ liệu tiếng Bengal cho CLI.
/vault/lang/lang.bn.fe.php | Dữ liệu tiếng Bengal cho các front-end.
/vault/lang/lang.bn.php | Dữ liệu tiếng Bengal.
/vault/lang/lang.de.cli.php | Dữ liệu tiếng Đức cho CLI.
/vault/lang/lang.de.fe.php | Dữ liệu tiếng Đức cho các front-end.
/vault/lang/lang.de.php | Dữ liệu tiếng Đức.
/vault/lang/lang.en.cli.php | Dữ liệu tiếng Anh cho CLI.
/vault/lang/lang.en.fe.php | Dữ liệu tiếng Anh cho các front-end.
/vault/lang/lang.en.php | Dữ liệu tiếng Anh.
/vault/lang/lang.es.cli.php | Dữ liệu tiếng Tây Ban Nha cho CLI.
/vault/lang/lang.es.fe.php | Dữ liệu tiếng Tây Ban Nha cho các front-end.
/vault/lang/lang.es.php | Dữ liệu tiếng Tây Ban Nha.
/vault/lang/lang.fr.cli.php | Dữ liệu tiếng Pháp cho CLI.
/vault/lang/lang.fr.fe.php | Dữ liệu tiếng Pháp cho các front-end.
/vault/lang/lang.fr.php | Dữ liệu tiếng Pháp.
/vault/lang/lang.hi.cli.php | Dữ liệu tiếng Hindi cho CLI.
/vault/lang/lang.hi.fe.php | Dữ liệu tiếng Hindi cho các front-end.
/vault/lang/lang.hi.php | Dữ liệu tiếng Hindi.
/vault/lang/lang.id.cli.php | Dữ liệu tiếng Indonesia cho CLI.
/vault/lang/lang.id.fe.php | Dữ liệu tiếng Indonesia cho các front-end.
/vault/lang/lang.id.php | Dữ liệu tiếng Indonesia.
/vault/lang/lang.it.cli.php | Dữ liệu tiếng Ý cho CLI.
/vault/lang/lang.it.fe.php | Dữ liệu tiếng Ý cho các front-end.
/vault/lang/lang.it.php | Dữ liệu tiếng Ý.
/vault/lang/lang.ja.cli.php | Dữ liệu tiếng Nhật cho CLI.
/vault/lang/lang.ja.fe.php | Dữ liệu tiếng Nhật cho các front-end.
/vault/lang/lang.ja.php | Dữ liệu tiếng Nhật.
/vault/lang/lang.ko.cli.php | Dữ liệu tiếng Hàn cho CLI.
/vault/lang/lang.ko.fe.php | Dữ liệu tiếng Hàn cho các front-end.
/vault/lang/lang.ko.php | Dữ liệu tiếng Hàn.
/vault/lang/lang.nl.cli.php | Dữ liệu tiếng Hà Lan cho CLI.
/vault/lang/lang.nl.fe.php | Dữ liệu tiếng Hà Lan cho các front-end.
/vault/lang/lang.nl.php | Dữ liệu tiếng Hà Lan.
/vault/lang/lang.no.cli.php | Dữ liệu tiếng Na Uy cho CLI.
/vault/lang/lang.no.fe.php | Dữ liệu tiếng Na Uy cho các front-end.
/vault/lang/lang.no.php | Dữ liệu tiếng Na Uy.
/vault/lang/lang.pt.cli.php | Dữ liệu tiếng Bồ Đào Nha cho CLI.
/vault/lang/lang.pt.fe.php | Dữ liệu tiếng Bồ Đào Nha cho các front-end.
/vault/lang/lang.pt.php | Dữ liệu tiếng Bồ Đào Nha.
/vault/lang/lang.ru.cli.php | Dữ liệu tiếng Nga cho CLI.
/vault/lang/lang.ru.fe.php | Dữ liệu tiếng Nga cho các front-end.
/vault/lang/lang.ru.php | Dữ liệu tiếng Nga.
/vault/lang/lang.sv.cli.php | Dữ liệu tiếng Thụy Điển cho CLI.
/vault/lang/lang.sv.fe.php | Dữ liệu tiếng Thụy Điển cho các front-end.
/vault/lang/lang.sv.php | Dữ liệu tiếng Thụy Điển.
/vault/lang/lang.th.cli.php | Dữ liệu tiếng Thái Lan cho CLI.
/vault/lang/lang.th.fe.php | Dữ liệu tiếng Thái Lan cho các front-end.
/vault/lang/lang.th.php | Dữ liệu tiếng Thái Lan.
/vault/lang/lang.tr.cli.php | Dữ liệu tiếng Thổ Nhĩ Kỳ cho CLI.
/vault/lang/lang.tr.fe.php | Dữ liệu tiếng Thổ Nhĩ Kỳ cho các front-end.
/vault/lang/lang.tr.php | Dữ liệu tiếng Thổ Nhĩ Kỳ.
/vault/lang/lang.ur.cli.php | Dữ liệu tiếng Urdu cho CLI.
/vault/lang/lang.ur.fe.php | Dữ liệu tiếng Urdu cho các front-end.
/vault/lang/lang.ur.php | Dữ liệu tiếng Urdu.
/vault/lang/lang.vi.cli.php | Dữ liệu tiếng Việt cho CLI.
/vault/lang/lang.vi.fe.php | Dữ liệu tiếng Việt cho các front-end.
/vault/lang/lang.vi.php | Dữ liệu tiếng Việt.
/vault/lang/lang.zh-tw.cli.php | Dữ liệu tiếng Trung Quốc (truyền thống) cho CLI.
/vault/lang/lang.zh-tw.fe.php | Dữ liệu tiếng Trung Quốc (truyền thống) cho các front-end.
/vault/lang/lang.zh-tw.php | Dữ liệu tiếng Trung Quốc (truyền thống).
/vault/lang/lang.zh.cli.php | Dữ liệu tiếng Trung Quốc (giản thể) cho CLI.
/vault/lang/lang.zh.fe.php | Dữ liệu tiếng Trung Quốc (giản thể) cho các front-end.
/vault/lang/lang.zh.php | Dữ liệu tiếng Trung Quốc (giản thể).
/vault/.htaccess | Tập tin "hypertext access" / tập tin truy cập siêu văn bản (bảo vệ tập tin bí mật khỏi bị truy cập bởi nguồn không được ủy quyền).
/vault/.travis.php | Được sử dụng bởi Travis CI để thử nghiệm (không cần thiết cho chức năng phù hợp của kịch bản).
/vault/.travis.yml | Được sử dụng bởi Travis CI để thử nghiệm (không cần thiết cho chức năng phù hợp của kịch bản).
/vault/aggregator.php | Tập hợp IP.
/vault/cache.dat | Dữ liệu bộ nhớ cache.
/vault/cidramblocklists.dat | Tập tin siêu dữ liệu cho danh sách chặn tùy chọn của Macmathan; Được sử dụng bởi trang cập nhật front-end.
/vault/cli.php | Tập tin cho xử lý CLI.
/vault/components.dat | Tập tin siêu dữ liệu thành phần; Được sử dụng bởi trang cập nhật front-end.
/vault/config.ini.RenameMe | Tập tin cho cấu hình; Chứa tất cả các tùy chọn cho cấu hình của CIDRAM, nói cho nó biết phải làm gì và làm thế nào để hoạt động (đổi tên để kích hoạt).
/vault/config.php | Tập tin cho xử lý cấu hình.
/vault/config.yaml | Tập tin cho cấu hình mặc định; Chứa giá trị cấu hình mặc định cho CIDRAM.
/vault/frontend.php | Tập tin cho xử lý front-end.
/vault/frontend_functions.php | Tập tin cho chức năng front-end.
/vault/functions.php | Tập tin cho chức năng.
/vault/hashes.dat | Danh sách các giá trị băm được chấp nhận (thích hợp với các tính năng reCAPTCHA; chỉ tạo ra nếu tính năng reCAPTCHA được kích hoạt).
/vault/ignore.dat | Tập tin các bỏ qua (được sử dụng để xác định mà phần chữ ký CIDRAM nên bỏ qua).
/vault/ipbypass.dat | Danh sách các đường tránh IP (thích hợp với các tính năng reCAPTCHA; chỉ tạo ra nếu tính năng reCAPTCHA được kích hoạt).
/vault/ipv4.dat | Tập tin chữ ký IPv4 (dịch vụ điện toán đám mây không mong muốn và thiết bị đầu cuối không phải con người).
/vault/ipv4_bogons.dat | Tập tin chữ ký IPv4 (CIDR bogon/martian).
/vault/ipv4_custom.dat.RenameMe | Tập tin chữ ký IPv4 tùy chỉnh (đổi tên để kích hoạt).
/vault/ipv4_isps.dat | Tập tin chữ ký IPv4 (ISP nguy hiểm và gửi thư rác).
/vault/ipv4_other.dat | Tập tin chữ ký IPv4 (CIDR cho proxy, VPN, và các dịch vụ khác mà không mong muốn).
/vault/ipv6.dat | Tập tin chữ ký IPv6 (dịch vụ điện toán đám mây không mong muốn và thiết bị đầu cuối không phải con người).
/vault/ipv6_bogons.dat | Tập tin chữ ký IPv6 (CIDR bogon/martian).
/vault/ipv6_custom.dat.RenameMe | Tập tin chữ ký IPv6 tùy chỉnh (đổi tên để kích hoạt).
/vault/ipv6_isps.dat | Tập tin chữ ký IPv6 (ISP nguy hiểm và gửi thư rác).
/vault/ipv6_other.dat | Tập tin chữ ký IPv6 (CIDR cho proxy, VPN, và các dịch vụ khác mà không mong muốn).
/vault/lang.php | Dữ liệu tiếng.
/vault/modules.dat | Tập tin siêu dữ liệu mô-đun; Được sử dụng bởi trang cập nhật front-end.
/vault/outgen.php | Máy phát đầu ra.
/vault/php5.4.x.php | Polyfills cho PHP 5.4.X (cần cho khả năng tương thích ngược PHP 5.4.X; an toàn để xóa cho các phiên bản PHP mới hơn).
/vault/recaptcha.php | reCAPTCHA mô-đun.
/vault/rules_as6939.php | Tập tin quy tắc tùy chỉnh cho AS6939.
/vault/rules_softlayer.php | Tập tin quy tắc tùy chỉnh cho Soft Layer.
/vault/rules_specific.php | Tập tin quy tắc tùy chỉnh cho một số CIDR cụ thể.
/vault/salt.dat | Tập tin muối (được sử dụng bởi một số chức năng ngoại vi; chỉ tạo ra nếu cần thiết).
/vault/template_custom.html | Tập tin mẫu; Mẫu cho HTML sản xuất bởi các máy phát đầu ra của CIDRAM.
/vault/template_default.html | Tập tin mẫu; Mẫu cho HTML sản xuất bởi các máy phát đầu ra của CIDRAM.
/vault/themes.dat | Tập tin siêu dữ liệu chủ đề; Được sử dụng bởi trang cập nhật front-end.
/vault/verification.yaml | Dữ liệu xác minh cho máy tìm kiếm và truyền thông xã hội.
/.gitattributes | Tập tin dự án cho GitHub (không cần thiết cho chức năng phù hợp của kịch bản).
/Changelog.txt | Kỷ lục của những sự thay đổi được thực hiện cho các kịch bản khác nhau giữa các phiên bản (không cần thiết cho chức năng phù hợp của kịch bản).
/composer.json | Thông tin về dự án cho Composer/Packagist (không cần thiết cho chức năng phù hợp của kịch bản).
/CONTRIBUTING.md | Thông tin về làm thế nào để đóng góp cho dự án.
/LICENSE.txt | Bản sao của giấy phép GNU/GPLv2 (không cần thiết cho chức năng phù hợp của kịch bản).
/loader.php | Tập tin cho tải. Đây là điều bạn cần nối vào (cần thiết)!
/README.md | Thông tin tóm tắt dự án.
/web.config | Tập tin cấu hình của ASP.NET (trong trường hợp này, để bảo vệ `/vault` thư mực khỏi bị truy cập bởi những nguồn không có quền trong trường hợp bản được cài trên serever chạy trên công nghệ ASP.NET).

---


### 6. <a name="SECTION6"></a>TÙY CHỌN CHO CẤU HÌNH
Sau đây là danh sách các biến tìm thấy trong tập tin cấu hình cho CIDRAM `config.ini`, cùng với một mô tả về mục đích và chức năng của chúng.

#### "general" (Thể loại)
Cấu hình chung cho CIDRAM.

"logfile"
- Tập tin có thể đọc con người cho ghi tất cả các nỗ lực truy cập bị chặn. Chỉ định một tên tập tin, hoặc để trống để vô hiệu hóa.

"logfileApache"
- Tập tin Apache phong cách cho ghi tất cả các nỗ lực truy cập bị chặn. Chỉ định một tên tập tin, hoặc để trống để vô hiệu hóa.

"logfileSerialized"
- Tập tin tuần tự cho ghi tất cả các nỗ lực truy cập bị chặn. Chỉ định một tên tập tin, hoặc để trống để vô hiệu hóa.

*Mẹo hữu ích: Nếu bạn muốn, bạn có thể thêm thông tin ngày/giờ trong tên các tập tin ghi của bạn bằng cách bao gồm những trong tên: `{yyyy}` cho năm đầy, `{yy}` cho năm viết tắt, `{mm}` cho tháng, `{dd}` cho ngày, `{hh}` cho giờ.*

*Các ví dụ:*
- *`logfile='logfile.{yyyy}-{mm}-{dd}-{hh}.txt'`*
- *`logfileApache='access.{yyyy}-{mm}-{dd}-{hh}.txt'`*
- *`logfileSerialized='serial.{yyyy}-{mm}-{dd}-{hh}.txt'`*

"truncate"
- Dọn dẹp các bản ghi khi họ được một kích thước nhất định? Giá trị là kích thước tối đa bằng B/KB/MB/GB/TB mà một tập tin bản ghi có thể tăng lên trước khi bị dọn dẹp. Giá trị mặc định 0KB sẽ vô hiệu hoá dọn dẹp (các bản ghi có thể tăng lên vô hạn). Lưu ý: Áp dụng cho tập tin riêng biệt! Kích thước tập tin bản ghi không được coi là tập thể.

"log_rotation_limit"
- Xoay vòng nhật ký giới hạn số lượng của tập tin nhật ký có cần tồn tại cùng một lúc. Khi các tập tin nhật ký mới được tạo, nếu tổng số lượng tập tin nhật ký vượt quá giới hạn được chỉ định, hành động được chỉ định sẽ được thực hiện. Bạn có thể chỉ định giới hạn mong muốn tại đây. Giá trị 0 sẽ vô hiệu hóa xoay vòng nhật ký.

"log_rotation_action"
- Xoay vòng nhật ký giới hạn số lượng của tập tin nhật ký có cần tồn tại cùng một lúc. Khi các tập tin nhật ký mới được tạo, nếu tổng số lượng tập tin nhật ký vượt quá giới hạn được chỉ định, hành động được chỉ định sẽ được thực hiện. Bạn có thể chỉ định hành động mong muốn tại đây. Delete = Xóa các tập tin nhật ký cũ nhất, cho đến khi giới hạn không còn vượt quá. Archive = Trước tiên lưu trữ, và sau đó xóa các tập tin nhật ký cũ nhất, cho đến khi giới hạn không còn vượt quá.

*Làm rõ kỹ thuật: Trong ngữ cảnh này, "cũ nhất" có nghĩa là không được sửa đổi gần đây.*

"timeOffset"
- Nếu thời gian máy chủ của bạn không phù hợp với thời gian địa phương của bạn, bạn có thể chỉ định một bù đắp đây để điều chỉnh thông tin ngày/giờ được tạo ra bởi CIDRAM theo yêu cầu của bạn. Nó thường được đề nghị thay vì để điều chỉnh các chỉ thị múi giờ trong tập tin `php.ini` của bạn, nhưng đôi khi (như ví dụ, khi làm việc với giới hạn cung cấp lưu trữ chia sẻ) đây không phải là luôn luôn có thể làm, và như vậy, tùy chọn này được cung cấp ở đây. Bù đắp được đo bằng phút.
- Ví dụ (để thêm một giờ): `timeOffset=60`

"timeFormat"
- Định dạng ngày tháng sử dụng bởi CIDRAM. Mặc định = `{Day}, {dd} {Mon} {yyyy} {hh}:{ii}:{ss} {tz}`.

"ipaddr"
- Nơi để tìm địa chỉ IP của các yêu cầu kết nối? (Hữu ích cho các dịch vụ như Cloudflare và vv). Mặc định = REMOTE_ADDR. CẢNH BÁO: Không thay đổi này, trừ khi bạn biết những gì bạn đang làm!

Giá trị được đề xuất cho "ipaddr":

Giá trị | Sử dụng
---|---
`HTTP_INCAP_CLIENT_IP` | Proxy reverse Incapsula.
`HTTP_CF_CONNECTING_IP` | Proxy reverse Cloudflare.
`CF-Connecting-IP` | Proxy reverse Cloudflare (một sự thay thế; nếu ở trên không hoạt động).
`HTTP_X_FORWARDED_FOR` | Proxy reverse Cloudbric.
`X-Forwarded-For` | [Proxy reverse Squid](http://www.squid-cache.org/Doc/config/forwarded_for/).
*Xác định bởi cấu hình máy chủ.* | [Proxy reverse Nginx](https://www.nginx.com/resources/admin-guide/reverse-proxy/).
`REMOTE_ADDR` | Không có proxy reverse (giá trị mặc định).

"forbid_on_block"
- Những gì thông báo trạng thái HTTP mà CIDRAM nên gửi khi yêu cầu bị chặn?

Giá trị hiện được hỗ trợ:

Mã trạng thái | Thông thái trạng thái | Chi tiết
---|---|---
`200` | `200 OK` | Giá trị mặc định. Không phải là rất mạnh mẽ, nhưng thân thiện với người dùng.
`403` | `403 Forbidden` | Hơi mạnh mẽ, và thân thiện với người dùng.
`410` | `410 Gone` | Có thể gây ra sự cố khi cố gắng giải quyết các sai tích cực, bởi vì một số trình duyệt sẽ lưu trữ thông thái trạng thái này và không gửi lại yêu cầu, ngay cả sau khi bỏ chặn người dùng. Có thể hữu ích hơn các tùy chọn khác để giảm yêu cầu từ một số loại bot cụ thể.
`418` | `418 I'm a teapot` | Điều này thực sự ám chỉ đến một trò đùa của April Fools [[RFC 2324](https://tools.ietf.org/html/rfc2324#section-6.5.14)] và có lẽ khách hàng sẽ không hiểu. Cung cấp cho vui chơi giải trí và thuận tiện, nhưng không thường được đề nghị.
`451` | `Unavailable For Legal Reasons` | Thích hợp cho các ngữ cảnh khi các yêu cầu bị chặn chủ yếu vì lý do pháp lý. Không được đề xuất trong các ngữ cảnh khác.
`503` | `Service Unavailable` | Không phải là rất thân thiện với người dùng, nhưng mạnh mẽ.

"silent_mode"
- CIDRAM nên âm thầm chuyển hướng cố gắng truy cập bị chặn thay vì hiển thị trang "Truy cập đã bị từ chối"? Nếu vâng, xác định vị trí để chuyển hướng cố gắng truy cập bị chặn để. Nếu không, để cho biến này được trống.

"lang"
- Xác định tiếng mặc định cho CIDRAM.

"numbers"
- Chỉ định cách hiển thị số.

Giá trị hiện được hỗ trợ:

Giá trị | Nó tạo ra | Chi tiết
---|---|---
`NoSep-1` | `1234567.89`
`NoSep-2` | `1234567,89`
`Latin-1` | `1,234,567.89` | Giá trị mặc định.
`Latin-2` | `1 234 567.89`
`Latin-3` | `1.234.567,89`
`Latin-4` | `1 234 567,89`
`Latin-5` | `1,234,567·89`
`China-1` | `123,4567.89`
`India-1` | `12,34,567.89`
`India-2` | `१२,३४,५६७.८९`
`Bengali-1` | `১২,৩৪,৫৬৭.৮৯`
`Arabic-1` | `١٢٣٤٥٦٧٫٨٩`
`Arabic-2` | `١٬٢٣٤٬٥٦٧٫٨٩`
`Thai-1` | `๑,๒๓๔,๕๖๗.๘๙`

*Chú thích: Các giá trị này không được chuẩn hóa ở bất kỳ đâu, và có thể sẽ không liên quan ngoài gói. Ngoài ra, các giá trị được hỗ trợ có thể thay đổi trong tương lai.*

"emailaddr"
- Nếu bạn muốn, bạn có thể cung cấp một địa chỉ email ở đây để được trao cho người dùng khi họ đang bị chặn, cho họ để sử dụng như một điểm tiếp xúc cho hỗ trợ hay giúp đở cho trong trường hợp họ bị chặn bởi nhầm hay lỗi. CẢNH BÁO: Bất kỳ địa chỉ email mà bạn cung cấp ở đây sẽ chắc chắn nhất được mua lại bởi chương trình thư rác và cái nạo trong quá trình con của nó được sử dụng ở đây, và như vậy, nó khuyên rằng nếu bạn chọn để cung cấp một địa chỉ email ở đây, mà bạn đảm bảo rằng địa chỉ email bạn cung cấp ở đây là một địa chỉ dùng một lần hay một địa chỉ mà bạn không nhớ được thư rác (nói cách khác, có thể bạn không muốn sử dụng một cá nhân chính hay kinh doanh chính địa chỉ email).

"emailaddr_display_style"
- Bạn muốn địa chỉ email được trình bày như thế nào với người dùng? "default" = Liên kết có thể nhấp. "noclick" = Văn bản không thể nhấp.

"disable_cli"
- Vô hiệu hóa chế độ CLI? Chế độ CLI được kích hoạt theo mặc định, nhưng đôi khi có thể gây trở ngại cho công cụ kiểm tra nhất định (như PHPUnit, cho ví dụ) và khác ứng dụng mà CLI dựa trên. Nếu bạn không cần phải vô hiệu hóa chế độ CLI, bạn nên bỏ qua tùy chọn này. False = Kích hoạt chế độ CLI [Mặc định]; True = Vô hiệu hóa chế độ CLI.

"disable_frontend"
- Vô hiệu hóa truy cập front-end? Truy cập front-end có thể làm cho CIDRAM dễ quản lý hơn, nhưng cũng có thể là một nguy cơ bảo mật tiềm năng. Đó là khuyến cáo để quản lý CIDRAM từ các back-end bất cứ khi nào có thể, nhưng truy cập front-end là cung cấp khi nó không phải là có thể. Giữ nó vô hiệu hóa trừ khi bạn cần nó. False = Kích hoạt truy cập front-end; True = Vô hiệu hóa truy cập front-end [Mặc định].

"max_login_attempts"
- Số lượng tối đa cố gắng đăng nhập (front-end). Mặc định = 5.

"FrontEndLog"
- Tập tin cho ghi cố gắng đăng nhập front-end. Chỉ định một tên tập tin, hoặc để trống để vô hiệu hóa.

"ban_override"
- Ghi đè "forbid_on_block" khi "infraction_limit" bị vượt quá? Khi ghi đè: Các yêu cầu bị chặn sản xuất một trang trống (tập tin mẫu không được sử dụng). 200 = Không ghi đè [Mặc định]. Các giá trị khác giống với các giá trị có sẵn cho "forbid_on_block".

"log_banned_ips"
- Bao gồm các yêu cầu bị chặn từ các IP bị cấm trong các tập tin đăng nhập? True = Vâng [Mặc định]; False = Không.

"default_dns"
- Một dấu phẩy phân cách danh sách các máy chủ DNS để sử dụng cho tra cứu tên máy. Mặc định = "8.8.8.8,8.8.4.4" (Google DNS). CẢNH BÁO: Không thay đổi này, trừ khi bạn biết những gì bạn đang làm!

*Xem thêm: [Những gì tôi có thể sử dụng cho "default_dns"?](#WHAT_CAN_I_USE_FOR_DEFAULT_DNS)*

"search_engine_verification"
- Cố gắng xác minh các yêu cầu từ các máy tìm kiếm? Xác minh máy tìm kiếm đảm bảo rằng họ sẽ không bị cấm là kết quả của vượt quá giới các hạn vi phạm (cấm các máy tìm kiếm từ trang web của bạn thường sẽ có một tác động tiêu cực đến các xếp hạng máy tìm kiếm của bạn, SEO, vv). Khi xác minh được kích hoạt, các máy tìm kiếm có thể bị chặn như bình thường, nhưng sẽ không bị cấm. Khi xác minh không được kích hoạt, họ có thể bị cấm như là kết quả của vượt quá giới các hạn vi phạm. Ngoài ra, xác minh máy tìm kiếm cung cấp bảo vệ chống lại các yêu cầu giả máy tìm kiếm và chống lại các thực thể rằng là khả năng độc hại được giả mạo như là các máy tìm kiếm (những yêu cầu này sẽ bị chặn khi xác minh máy tìm kiếm được kích hoạt). True = Kích hoạt xác minh máy tìm kiếm [Mặc định]; False = Vô hiệu hóa xác minh máy tìm kiếm.

Được hỗ trợ hiện tại:
- [Google](https://support.google.com/webmasters/answer/80553?hl=en)
- [Bing](https://blogs.bing.com/webmaster/2012/08/31/how-to-verify-that-bingbot-is-bingbot)
- Yahoo
- [Baidu (百度)](https://help.baidu.com/question?prod_en=master&class=Baiduspider)
- [Yandex (Яндекс)](https://yandex.com/support/webmaster/robot-workings/check-yandex-robots.xml)
- [DuckDuckGo](https://duckduckgo.com/duckduckbot)

"social_media_verification"
- Cố gắng xác minh yêu cầu truyền thông xã hội? Xác minh truyền thông xã hội cung cấp sự bảo vệ chống lại các yêu cầu truyền thông xã hội giả mạo (các yêu cầu như vậy sẽ bị chặn). True = Kích hoạt xác minh truyền thông xã hội [Mặc định]; False = Vô hiệu hóa xác minh truyền thông xã hội.

Được hỗ trợ hiện tại:
- [Pinterest](https://help.pinterest.com/en/articles/about-pinterest-crawler-0)
- Embedly
- [Grapeshot](https://www.grapeshot.com/crawler/)

"protect_frontend"
- Chỉ định liệu các bảo vệ thường được cung cấp bởi CIDRAM nên được áp dụng cho các front-end. True = Vâng [Mặc định]; False = Không.

"disable_webfonts"
- Vô hiệu hóa các webfont? True = Vâng [Mặc định]; False = Không.

"maintenance_mode"
- Bật chế độ bảo trì? True = Vâng; False = Không [Mặc định]. Vô hiệu hoá mọi thứ khác ngoài các front-end. Đôi khi hữu ích khi cập nhật CMS, framework của bạn, vv.

"default_algo"
- Xác định thuật toán nào sẽ sử dụng cho tất cả các mật khẩu và phiên trong tương lai. Tùy chọn: PASSWORD_DEFAULT (mặc định), PASSWORD_BCRYPT, PASSWORD_ARGON2I (yêu cầu PHP >= 7.2.0).

"statistics"
- Giám sát thống kê sử dụng CIDRAM? True = Vâng; False = Không [Mặc định].

"force_hostname_lookup"
- Thực hiện tìm kiếm tên máy chủ cho tất cả các yêu cầu? True = Vâng; False = Không [Mặc định]. Tìm kiếm tên máy chủ thường được thực hiện trên cơ sở cần thiết, nhưng có thể được thực hiện cho tất cả các yêu cầu. Điều này có thể hữu ích như một phương tiện cung cấp thông tin chi tiết hơn trong các tập tin đăng nhập, nhưng cũng có thể có tác động tiêu cực đến hiệu suất.

"allow_gethostbyaddr_lookup"
- Cho phép tra cứu gethostbyaddr khi UDP không khả dụng? True = Vâng [Mặc định]; False = Không.
- *Lưu ý: Tra cứu IPv6 có thể không hoạt động chính xác trên một số hệ thống 32-bit.*

"hide_version"
- Ẩn thông tin phiên bản từ nhật ký và đầu ra của trang? True = Vâng; False = Không [Mặc định].

#### "signatures" (Thể loại)
Cấu hình cho chữ ký.

"ipv4"
- Một danh sách các tập tin chữ ký IPv4 mà CIDRAM nên cố gắng để phân tích, ngăn cách bởi dấu phẩy. Bạn có thể thêm các mục ở đây nếu bạn muốn bao gồm thêm các tập tin chữ ký IPv4 trong CIDRAM.

"ipv6"
- Một danh sách các tập tin chữ ký IPv6 mà CIDRAM nên cố gắng để phân tích, ngăn cách bởi dấu phẩy. Bạn có thể thêm các mục ở đây nếu bạn muốn bao gồm thêm các tập tin chữ ký IPv6 trong CIDRAM.

"block_cloud"
- Chặn CIDR xác định là thuộc về các dịch vụ lưu trữ mạng hay dịch vụ điện toán đám mây? Nếu bạn điều hành một dịch vụ API từ trang mạng của bạn hay nếu bạn mong đợi các trang mạng khác để kết nối với trang mạng của bạn, điều này cần được thiết lập để false. Nếu bạn không, sau đó, tùy chọn này cần được thiết lập để true.

"block_bogons"
- Chặn CIDR bogon/martian? Nếu bạn mong đợi các kết nối đến trang mạng của bạn từ bên trong mạng nội bộ của bạn, từ localhost, hay từ LAN của bạn, tùy chọn này cần được thiết lập để false. Nếu bạn không mong đợi những kết nối như vậy, tùy chọn này cần được thiết lập để true.

"block_generic"
- Chặn CIDR thường được khuyến cáo cho danh sách đen? Điều này bao gồm bất kỳ chữ ký không được đánh dấu như một phần của bất kỳ các loại chữ ký cụ thể khác.

"block_legal"
- Chặn CIDR theo các nghĩa vụ hợp pháp? Chỉ thị này thường không có bất kỳ hiệu lực, vì CIDRAM không liên kết bất kỳ CIDR nào với "nghĩa vụ hợp pháp" theo mặc định, nhưng nó vẫn tồn tại tuy nhiên như một biện pháp kiểm soát bổ sung vì lợi ích của bất kỳ tập tin chữ ký hay mô-đun tùy chỉnh nào có thể tồn tại vì lý do hợp pháp.

"block_malware"
- Chặn IP liên quan đến phần mềm độc hại? Điều này bao gồm các máy chủ C&C, máy chủ bị nhiễm, máy chủ liên quan đến phân phối phần mềm độc hại, vv.

"block_proxies"
- Chặn CIDR xác định là thuộc về các dịch vụ proxy hay VPN? Nếu bạn yêu cầu mà người dùng có thể truy cập trang mạng của bạn từ các dịch vụ proxy hay VPN, điều này cần được thiết lập để false. Nếu không thì, nếu bạn không yêu cầu các dịch vụ proxy hay VPN, tùy chọn này cần được thiết lập để true như một phương tiện để cải thiện an ninh.

"block_spam"
- Chặn CIDR xác định như có nguy cơ cao đối được thư rác? Trừ khi bạn gặp vấn đề khi làm như vậy, nói chung, điều này cần phải luôn được true.

"modules"
- Một danh sách các tập tin mô-đun để tải sau khi kiểm tra các chữ ký IPv4/IPv6, ngăn cách bởi dấu phẩy.

"default_tracktime"
- Có bao nhiêu giây để giám sát các IP bị cấm bởi các mô-đun. Mặc định = 604800 (1 tuần).

"infraction_limit"
- Số lượng tối đa các vi phạm một IP được phép chịu trước khi nó bị cấm bởi các giám sát IP. Mặc định = 10.

"track_mode"
- Khi vi phạm cần được tính? False = Khi IP bị chặn bởi các mô-đun. True = Khi IP bị chặn vì lý do bất kỳ.

#### "recaptcha" (Thể loại)
Nếu bạn muốn, bạn có thể cung cấp cho người dùng một cách để vượt qua các trang "Truy cập đã bị từ chối" bằng cách hoàn thành một reCAPTCHA. Điều này có thể giúp giảm thiểu một số rủi ro kết hợp với sai tích cực trong những tình huống theo đó chúng tôi không hoàn toàn chắc chắn liệu một yêu cầu bắt nguồn từ một máy tính hay một con người.

Do những rủi ro liên quan đến việc cung cấp một cách cho người dùng để bỏ qua trang "Truy cập đã bị từ chối", nói chung, tôi sẽ tư vấn để không cho phép tính năng này trừ khi bạn cảm thấy nó là cần thiết phải làm như vậy. Tình huống mà nó sẽ là cần thiết: Nếu trang mạng của bạn có khách hàng hay người dùng mà cần phải có quyền truy cập vào trang mạng của bạn, và nếu điều này là một cái gì đó mà không thể được thỏa hiệp, nhưng nếu những khách hàng hay người dùng xảy ra để được kết nối từ một mạng thù địch mà có lẽ được mang giao thông không mong muốn, và ngăn chặn giao thông không mong muốn này cũng là một cái gì đó mà không thể được thỏa hiệp, trong những tình huống mà không chiến thắng này, tính năng reCAPTCHA có thể hữu ích như một phương tiện cho phép các giao thông mong muốn từ khách hàng hay người dùng, trong khi ngăn chặn các giao thông không mong muốn từ cùng một mạng. Tuy vậy, xem xét rằng mục đích của một CAPTCHA là để phân biệt giữa con người và chương trình máy tính, tính năng reCAPTCHA sẽ chỉ giúp đỡ trong những tình huống mà không chiến thắng này nếu chúng ta giả định rằng giao thông không mong muốn này là từ một chương trình máy tính (ví dụ, chương trình thư rác, công cụ cào, công cụ hack, giao thông tự động, vv), như trái ngược với giao thông không mong muốn từ người (như thế thư rác từ người, hacker, vv).

Để có được một "site key" và một "secret key" (cần thiết để sử dụng reCAPTCHA), xin truy cập vào: [https://developers.google.com/recaptcha/](https://developers.google.com/recaptcha/)

"usemode"
- Định nghĩa thế nào CIDRAM nên sử dụng reCAPTCHA.
- 0 = reCAPTCHA là hoàn toàn bị vô hiệu hóa (mặc định).
- 1 = reCAPTCHA là kích hoạt cho tất cả các chữ ký.
- 2 = reCAPTCHA là kích hoạt chỉ cho chữ ký thuộc phần đặc biệt đánh dấu để sử dụng với reCAPTCHA.
- (Bất kỳ giá trị khác sẽ được xử lý trong cùng một cách như là 0).

"lockip"
- Chỉ định liệu các băm/hash nên được khóa trên IP cụ thể. False = Cookie và băm/hash CÓ THỂ được sử dụng bởi nhiều IP (mặc định). True = Cookie và băm/hash KHÔNG THỂ được sử dụng sử dụng bởi nhiều IP (cookie và băm/hash được khóa trên các IP).
- Chú thích: Giá trị "lockip" được bỏ qua khi "lockuser" là false, bởi vì các cơ chế để nhớ "người dùng" khác nhau ơ tùy thuộc vào giá trị này.

"lockuser"
- Chỉ định liệu thành công hoàn thành của reCAPTCHA nên được khóa trên người dùng cụ thể. False = Thành công hoàn thành của reCAPTCHA sẽ cấp quyền truy cập cho tất cả các yêu cầu có nguồn gốc từ cùng một IP như được sử dụng bởi người dùng mà hoàn thành reCAPTCHA; Cookie và băm/hash không được sử dụng; Thay vào đó, một danh sách trắng IP sẽ được sử dụng. True = Thành công hoàn thành của reCAPTCHA sẽ chỉ cấp quyền truy cập cho người dùng mà hoàn thành reCAPTCHA; Cookie và băm/hash được sử dụng để nhớ người dùng; Một danh sách trắng IP sẽ không được sử dụng (mặc định).

"sitekey"
- Giá trị này nên tương ứng với "site key" cho reCAPTCHA của bạn, tìm thấy trong bảng điều khiển của reCAPTCHA.

"secret"
- Giá trị này nên tương ứng với "secret key" cho reCAPTCHA của bạn, tìm thấy trong bảng điều khiển của reCAPTCHA.

"expiry"
- Khi "lockuser" là true (mặc định), để nhớ khi một người dùng hoàn thành reCAPTCHA, cho yêu cầu trang tương lai, CIDRAM tạo ra một cookie chuẩn chứa một băm/hash tương ứng với một bản ghi nội chứa cùng băm/hash; Yêu cầu trang tương lai sẽ sử dụng các tương ứng giá trị băm/hash để xác thực mà người dùng đã hoàn thành reCAPTCHA. Khi "lockuser" là false, một danh sách trắng IP được sử dụng để xác định liệu các yêu cầu nên được chấp nhận từ các IP của các yêu; Mục được thêm vào danh sách trắng này khi reCAPTCHA được hoàn thành. Đối với bao nhiêu giờ nên các cookie, băm/hash và mục danh sách trắng vẫn còn hợp lệ? Mặc định = 720 (1 tháng).

"logfile"
- Đăng nhập tất cả các nỗ lực cho reCAPTCHA? Nếu có, ghi rõ tên để sử dụng cho các tập tin đăng nhập. Nếu không, đốn biến này.

*Mẹo hữu ích: Nếu bạn muốn, bạn có thể thêm thông tin ngày/giờ trong tên các tập tin ghi của bạn bằng cách bao gồm những trong tên: `{yyyy}` cho năm đầy, `{yy}` cho năm viết tắt, `{mm}` cho tháng, `{dd}` cho ngày, `{hh}` cho giờ.*

*Các ví dụ:*
- *`logfile='recaptcha.{yyyy}-{mm}-{dd}-{hh}.txt'`*

"signature_limit"
- Số chữ ký tối đa cho phép được kích hoạt khi một cá thể reCAPTCHA được cung cấp. Mặc định = 1. Nếu số này vượt quá cho bất kỳ yêu cầu cụ thể nào, một cá thể reCAPTCHA sẽ không được cung cấp.

"api"
- API nào để sử dụng? V2 hoặc Invisible?

*Lưu ý đối với người dùng ở Liên minh châu Âu: Khi CIDRAM được định cấu hình để sử dụng cookie (v.d., khi "lockuser" là true/đúng), cảnh báo cookie được hiển thị trên trang theo quy định của [pháp luật về cookie của EU](http://ec.europa.eu/ipg/basics/legal/cookies/index_en.htm). Tuy nhiên, khi sử dụng API invisible, CIDRAM cố gắng hoàn thành reCAPTCHA cho người dùng tự động, và khi thành công, điều này có thể dẫn đến việc trang được tải lại và một cookie được tạo ra mà không có người dùng được cho đủ thời gian để thực sự xem cảnh báo cookie. Nếu điều này đặt ra rủi ro hợp pháp cho bạn, bạn nên sử dụng API V2 thay vì API invisible (API V2 không phải là tự động và yêu cầu người dùng tự hoàn thành reCAPTCHA, do đó cung cấp cơ hội để xem cảnh báo cookie).*

#### "legal" (Thể loại)
Cấu hình mà liên quan đến các nghĩa vụ hợp pháp.

*Để biết thêm thông tin về các nghĩa vụ hợp pháp và cách nó có thể ảnh hưởng đến các nghĩa vụ cấu hình của bạn, vui lòng tham khảo phần "[THÔNG TIN HỢP PHÁP](#SECTION11)" của các tài liệu.*

"pseudonymise_ip_addresses"
- Pseudonymise địa chỉ IP khi viết các tập tin nhật ký? True = Vâng; False = Không [Mặc định].

"omit_ip"
- Bỏ qua địa chỉ IP từ nhật ký? True = Vâng; False = Không [Mặc định]. Lưu ý: "pseudonymise_ip_addresses" trở nên dư thừa khi "omit_ip" là "true".

"omit_hostname"
- Bỏ qua tên máy chủ từ nhật ký? True = Vâng; False = Không [Mặc định].

"omit_ua"
- Bỏ qua đại lý người dùng từ nhật ký? True = Vâng; False = Không [Mặc định].

"privacy_policy"
- Địa chỉ của chính sách bảo mật liên quan được hiển thị ở chân trang của bất kỳ trang nào được tạo. Chỉ định URL, hoặc để trống để vô hiệu hóa.

#### "template_data" (Thể loại)
Cấu hình cho mẫu thiết kế và chủ đề.

Liên quan đến đầu ra HTML sử dụng để tạo ra các trang "Truy cập đã bị từ chối". Nếu bạn đang sử dụng chủ đề tùy chỉnh cho CIDRAM, đầu ra HTML có nguồn gốc từ tập tin `template_custom.html`, và nếu không thì, đầu ra HTML có nguồn gốc từ tập tin `template.html`. Biến bằng văn bản cho phần này của tập tin cấu hình được xử lý để đầu ra HTML bằng cách thay thế bất kỳ tên biến được bao quanh bởi các dấu ngoặc nhọn tìm thấy trong đầu ra HTML với các dữ liệu biến tương ứng. Ví dụ, ở đâu `foo="bar"`, bất kỳ trường hợp `<p>{foo}</p>` tìm thấy trong đầu ra HTML sẽ trở thành `<p>bar</p>`.

"theme"
- Chủ đề mặc định để sử dụng cho CIDRAM.

"Magnification"
- Phóng to chữ. Mặc định = 1.

"css_url"
- Tập tin mẫu thiết kế cho chủ đề tùy chỉnh sử dụng thuộc tính CSS bên ngoài, trong khi các tập tin mẫu thiết kế cho các chủ đề mặc định sử dụng thuộc tính CSS nội bộ. Để hướng dẫn CIDRAM để sử dụng các tập tin mẫu thiết kế cho chủ đề tùy chỉnh, xác định các địa chỉ HTTP cho các tập tin CSS chủ đề tùy chỉnh của bạn sử dụng các biến số `css_url`. Nếu bạn để cho biến số này chỗ trống, CIDRAM sẽ sử dụng các tập tin mẫu thiết kế cho các chủ đề mặc định.

---


### 7. <a name="SECTION7"></a>ĐỊNH DẠNG CỦA CHỬ KÝ

*Xem thêm:*
- *["Chữ ký" là gì?](#WHAT_IS_A_SIGNATURE)*

#### 7.0 KHÁI NIỆM CƠ BẢN (ĐỐI VỚI TẬP TIN CHỮ KÝ)

Một mô tả của các định dạng và cấu trúc của chữ ký được sử dụng bởi CIDRAM có thể được tìm thấy trong văn bản thô trong bất kỳ tập tin chữ ký tùy chỉnh. Vui lòng tham khảo tài liệu hướng dẫn để tìm hiểu thêm về định dạng và cấu trúc của chữ ký của CIDRAM.

Tất cả các chữ ký IPv4 theo định dạng: `xxx.xxx.xxx.xxx/yy [Function] [Param]`.
- `xxx.xxx.xxx.xxx` đại diện cho sự khởi đầu của khối CIDR (octet của địa chỉ IP đầu tiên trong khối).
- `yy` đại diện cho kích thước khối CIDR [1-32].
- `[Function]` chỉ thị các kịch bản những gì để làm với các chữ ký (các cách chữ ký phải được coi).
- `[Param]` đại diện cho bất cứ điều gì thêm thông tin có thể được yêu cầu bởi `[Function]`.

Tất cả các chữ ký IPv6 theo định dạng: `xxxx:xxxx:xxxx:xxxx::xxxx/yy [Function] [Param]`.
- `xxxx:xxxx:xxxx:xxxx::xxxx` đại diện cho sự khởi đầu của khối CIDR (octet của địa chỉ IP đầu tiên trong khối). Ký hiệu hoàn chỉnh và ký hiệu viết tắt cả hai đều chấp nhận được (và mỗi PHẢI tuân theo các tiêu chuẩn phù hợp và liên quan của ký hiệu IPv6, nhưng với một ngoại lệ: một địa chỉ IPv6 không bao giờ có thể bắt đầu với một chữ viết tắt khi được sử dụng trong một chữ ký cho kịch bản này, bởi vì cách thức mà CIDR được xây dựng lại bởi các kịch bản; Ví dụ, `::1/128` nên được bày tỏ, khi được sử dụng trong một chữ ký, như `0::1/128`, và `::0/128` bày tỏ như `0::/128`).
- `yy` đại diện cho kích thước khối CIDR [1-128].
- `[Function]` chỉ thị các kịch bản những gì để làm với các chữ ký (các cách chữ ký phải được coi).
- `[Param]` đại diện cho bất cứ điều gì thêm thông tin có thể được yêu cầu bởi `[Function]`.

Các tập tin chữ ký cho CIDRAM NÊN sử dụng ngắt dòng trong phong cách Unix (`%0A`, hay `\n`)! Các loại / phong cách khác của ngắt dòng (ví dụ, Windows `%0D%0A` hay `\r\n`, Mac `%0D` hay `\r`, vv) CÓ THỂ được sử dụng, nhưng là KHÔNG ưa thích. Ngắt dòng không trong phong cách của Unix sẽ được bình thường như ngắt dòng trong phong cách của Unix bằng cách các kịch bản.

CIDR ký hiệu tóm lược và chính xác là cần thiết, nếu không thì các kịch bản sẽ KHÔNG công nhận các chữ ký. Ngoài ra, tất cả các chữ ký CIDR của kịch bản này PHẢI bắt đầu với một địa chỉ IP số IP có thể phân chia đồng đều vào việc phân chia khối đại diện bởi kích thước khối CIDR của nó (ví dụ, nếu bạn muốn chặn tất cả các IP từ `10.128.0.0` đến `11.127.255.255`, `10.128.0.0/8` sẽ KHÔNG được công nhận bởi các kịch bản, nhưng `10.128.0.0/9` và `11.0.0.0/9` sử dụng kết hợp, SẼ được công nhận bởi các kịch bản).

Bất cứ điều gì trong các tập tin chữ ký không được công nhận như một chữ ký cũng không phải như cú pháp chữ ký liên quan bằng cách của các kịch bản sẽ được BỎ QUA, do đó có nghĩa là bạn có thể an toàn đặt bất kỳ dữ liệu không chữ ký mà bạn muốn vào các tập tin chữ ký mà không phá vỡ chúng và mà không vi phạm các kịch bản. Ý kiến được chấp nhận trong các tập tin chữ ký, và không có định dạng đặc biệt được yêu cầu cho chúng. Shell kiểu băm cho ý kiến được ưa thích, nhưng không được thực thi; Chức năng, nó làm cho không có sự khác biệt với các kịch bản hay không bạn chọn để sử dụng băm Shell kiểu băm cho ý kiến, nhưng sử dụng băm Shell kiểu băm sẽ giúp IDE và biên tập văn bản đơn giản để làm nổi bật một cách chính xác các bộ phận khác nhau của các tập tin chữ ký (và như vậy, Shell kiểu băm có thể hỗ trợ như một trợ thị giác trong khi chỉnh sửa).

Các giá trị có thể có của `[Function]` như sau:
- Run
- Whitelist
- Greylist
- Deny

Nếu "Run" được sử dụng, khi chữ ký được kích hoạt, các kịch bản sẽ cố gắng thực hiện (sử dụng một statement / tuyên bố `require_once`) một kịch bản PHP bên ngoài, xác định bởi các giá trị `[Param]` (thư mục làm việc nên là thư mục "/vault/" của các kịch bản).

Ví dụ: `127.0.0.0/8 Run example.php`

Điều này có thể hữu ích nếu bạn muốn để thực hiện một số mã PHP cụ thể cho một số IP hay CIDR cụ thể.

Nếu "Whitelist" được sử dụng, khi chữ ký được kích hoạt, các kịch bản sẽ thiết lập lại tất cả các phát hiện (nếu có bất kỳ phát hiện đã) và phá vỡ các chức năng kiểm tra. `[Param]` bị bỏ qua. Chức năng này là tương đương với danh sách trắng một IP hay CIDR cụ thể chống bị phát hiện.

Ví dụ: `127.0.0.1/32 Whitelist`

Nếu "Greylist" được sử dụng, khi chữ ký được kích hoạt, các kịch bản sẽ thiết lập lại tất cả các phát hiện (nếu có bất kỳ phát hiện đã) và tiến hành đến các tập tin chữ ký tiếp theo để tiếp tục xử lý. `[Param]` bị bỏ qua.

Ví dụ: `127.0.0.1/32 Greylist`

Nếu "Deny" được sử dụng, khi chữ ký được kích hoạt, giả sử không có chữ ký danh sách trắng đã được kích hoạt cho các địa chỉ IP hay CIDR thích hợp, truy cập vào các trang được bảo vệ sẽ bị từ chối. "Deny" là những gì bạn sẽ muốn sử dụng để thực sự ngăn chặn một địa chỉ IP hay phạm vi CIDR. Khi bất kỳ chữ ký được kích hoạt đó làm cho sử dụng của "Deny", trang "Truy cập đã bị từ chối" của các kịch bản sẽ được tạo ra và các yêu cầu đến trang bảo vệ sẽ bị giết.

Giá trị `[Param]` chấp nhận bởi "Deny" sẽ được phân tích để đầu ra trang "Truy cập đã bị từ chối", cung cấp cho các khách hàng / người dùng như các lý do được trích dẫn cho truy cập của họ vào trang yêu cầu được bị từ chối. Nó có thể là một câu ngắn và đơn giản, giải thích lý do tại sao bạn đã chọn để ngăn chặn chúng (bất cứ điều gì là đủ, cái gì đó như "Tôi không muốn bạn trên trang mạng của tôi" sẽ đủ), hay một trong một số ít các từ viết tắt được cung cấp bởi các kịch bản, mà nếu được sử dụng, sẽ được thay thế bởi các kịch bản với một lời giải thích chuẩn bị trước lý do tại sao khách hàng / người dùng đã bị chặn.

Những lời giải thích trước khi chuẩn bị có hỗ trợ L10N và có thể được dịch bởi kịch bản dựa trên ngôn ngữ mà bạn chỉ định đến tùy chọn `lang` của các cấu hình kịch bản. Ngoài ra, bạn có thể hướng dẫn các kịch bản để bỏ qua chữ ký "Deny" dựa trên giá trị `[Param]` của họ (nếu họ đang sử dụng những từ viết tắt) thông qua các tùy chọn định bởi cấu hình kịch bản (mỗi từ viết tắt có một tùy chọn tương ứng để xử lý chữ ký tương ứng hoặc bỏ qua chúng). Các giá trị `[Param]` mà không sử dụng những từ viết tắt, tuy nhiên, không có hỗ trợ L10N và do đó KHÔNG SẼ được dịch bởi kịch bản, và do đó, không thể được kiểm soát trực tiếp bởi các cấu hình kịch bản.

Những từ viết tắt có sẵn là:
- Bogon
- Cloud
- Generic
- Proxy
- Spam
- Legal
- Malware

#### 7.1 GẮN THẺ

##### 7.1.0 GẮN THẺ PHẦN

Nếu bạn muốn chia chữ ký tùy chỉnh của bạn để các phần riêng biệt, bạn có thể xác định những phần riêng lẻ cho các kịch bản bằng cách thêm một "gắn thẻ phần" ngay sau khi có chữ ký của từng phần, với tên của phần chữ ký của bạn (xem ví dụ dưới đây).

```
# Phần 1.
1.2.3.4/32 Deny Bogon
2.3.4.5/32 Deny Cloud
4.5.6.7/32 Deny Generic
5.6.7.8/32 Deny Spam
6.7.8.9/32 Deny Proxy
Tag: Phần 1
```

Để phá vỡ gắn thẻ phần và để đảm bảo rằng các gắn thẻ không được xác định không đúng để phần chữ ký từ trước đó trong các tập tin chữ ký, chỉ cần đảm bảo rằng có ít nhất hai ngắt dòng liên tiếp giữa các gắn thẻ và phần chữ ký trước đó của bạn. Bất kỳ chữ ký không được gắn thẻ sẽ mặc định để "IPv4" hoặc "IPv6" (tùy thuộc vào loại chữ ký đang được kích hoạt).

```
1.2.3.4/32 Deny Bogon
2.3.4.5/32 Deny Cloud

4.5.6.7/32 Deny Generic
5.6.7.8/32 Deny Spam
Tag: Phần 1
```

Trong ví dụ trên `1.2.3.4/32` và `2.3.4.5/32` sẽ được xác định như "IPv4", trong khi `4.5.6.7/32` và `5.6.7.8/32` sẽ được xác định như "Phần 1".

Tương tự logic có thể được áp dụng để tách thẻ loại khác.

Gắn thẻ phần có thể rất hữu ích cho lọc lỗi khi xảy ra sai tích cực, bằng cách cung cấp một phương tiện dễ dàng để tìm ra chính xác nguồn gốc của vấn đề, và có thể rất hữu ích cho lọc các mục nhập tập tin bản ghi khi xem tập tin bản ghi qua trang bản ghi của front-end (tên phần có thể nhấp qua trang bản ghi của front-end và có thể được sử dụng làm tiêu chí lọc). Nếu gắn thẻ phần bị bỏ qua đối với một số chữ ký cụ thể, khi những chữ ký được kích hoạt, CIDRAM sử dụng tên của tập tin chữ ký cùng với loại địa chỉ IP bị chặn (IPv4 hoặc IPv6) như là một dự phòng, và do đó, các gắn thẻ phần là hoàn toàn tùy chọn. Chúng có thể được khuyên dùng trong một số trường hợp, chẳng hạn như khi tập tin chữ ký được đặt tên mơ hồ hoặc nếu không thì khó xác định được nguồn gốc của chữ ký gây ra yêu cầu bị chặn.

##### 7.1.1 GẮN THẺ HẾT HẠN

Nếu bạn muốn chữ ký hết hạn sau một thời gian, trong một cách tương tự như gắn thẻ phần, bạn có thể sử dụng một "gắn thẻ hết hạn" để chỉ định khi chữ ký nên hết hiệu lực. Gắn thẻ hết hạn sử dụng định dạng "YYYY.MM.DD" (xem ví dụ dưới đây).

```
# Phần 1.
1.2.3.4/32 Deny Generic
2.3.4.5/32 Deny Generic
Expires: 2016.12.31
```

Chữ ký hết hạn sẽ không bao giờ được kích hoạt trên bất kỳ yêu cầu, không có vấn đề gì.

##### 7.1.2 GẮN THẺ XUẤT XỨ

Nếu bạn muốn chỉ định quốc gia xuất xứ cho một số chữ ký cụ thể, bạn có thể làm như vậy bằng cách sử dụng một "gắn thẻ xuất xứ". Gắn thẻ xuất xứ chấp nhận một mã "[ISO 3166-1 Alpha-2](https://vi.wikipedia.org/wiki/ISO_3166-1_alpha-2)" tương ứng với quốc gia xuất xứ cho các chữ ký mà nó áp dụng. Các mã này phải được viết bằng chữ hoa (trường hợp thấp hoặc trường hợp hỗn hợp sẽ không hiển thị chính xác). Khi một gắn thẻ xuất xứ được sử dụng, nó được thêm vào mục nhập bản ghi "Tại sao bị chặn" cho bất kỳ yêu cầu bị chặn như là kết quả của chữ ký mà gắn thẻ được áp dụng.

Nếu cài đặt thành phần "flags CSS", khi xem các tập tin bản ghi qua trang bản ghi, thông tin được bổ sung bởi gắn thẻ xuất xứ sẽ được thay thế bằng cờ quốc gia tương ứng với thông tin đó. Thông tin này, dù ở dạng thô hoặc quốc kỳ, có thể nhấp được, và khi được nhấp, sẽ lọc các mục nhập bản ghi bằng cách các mục nhập bản ghi khác tương tự (do đó có hiệu quả cho phép các trang bản ghi để lọc theo cách của quốc gia xuất xứ).

Chú thích: Về mặt kỹ thuật, đây không phải là bất kỳ dạng geolocation, bởi vì nó không liên quan đến việc tìm nạp bất kỳ thông tin vị trí cụ thể liên quan đến IP, mà đúng hơn, nó chỉ đơn giản cho phép chúng ta xác định rõ ràng một quốc gia xuất xứ cho bất kỳ yêu cầu bị chặn bởi chữ ký cụ thể. Nhiều gắn thẻ xuất xứ được cho phép trong cùng một phần chữ ký.

Ví dụ giả thuyết:

```
1.2.3.4/32 Deny Generic
Origin: CN
2.3.4.5/32 Deny Generic
Origin: FR
4.5.6.7/32 Deny Generic
Origin: DE
6.7.8.9/32 Deny Generic
Origin: US
Tag: Foobar
```

Bất kỳ thẻ có thể được sử dụng kết hợp, và tất cả các thẻ là tùy chọn (xem ví dụ dưới đây).

```
# Phần Ví Dụ.
1.2.3.4/32 Deny Generic
Origin: US
Tag: Phần Ví Dụ
Expires: 2016.12.31
```

#### 7.2 YAML

##### 7.2.0 YAML CƠ BẢN

Một hình thức đơn giản của YAML có thể được sử dụng trong các tập tin chữ ký cho mục đích xác định các hành vi và các thiết lập cụ thể để phần chữ ký cá nhân. Điều này có thể hữu ích nếu bạn muốn giá trị của chỉ thị cấu hình của bạn để khác biệt trên cơ sở chữ ký cá nhân và phần chữ ký (ví dụ; nếu bạn muốn cung cấp một địa chỉ email cho vé hỗ trợ cho bất kỳ người dùng bị chặn bởi một chữ ký đặc biệt, nhưng không muốn cung cấp một địa chỉ email cho vé hỗ trợ cho người dùng bị chặn bởi bất kỳ chữ ký khác; nếu bạn muốn có một số chữ ký cụ thể để kích hoạt một chuyển hướng trang; nếu bạn muốn đánh dấu một phần chữ ký để sử dụng với reCAPTCHA; nếu bạn muốn ghi lại cố gắng truy cập bị chặn vào các tập tin riêng biệt trên cơ sở chữ ký cá nhân hay phần chữ ký).

Sử dụng YAML trong các tập tin chữ ký là không bắt buộc (có nghĩa là, bạn có thể sử dụng nó nếu bạn muốn làm như vậy, nhưng bạn không cần phải làm như vậy), và có thể tận dụng nhiều nhất (nhưng không phải tất cả) tùy chọn cấu hình.

Lưu ý: YAML của CIDRAM là rất đơn giản và rất hạn chế; Nó được thiết kế để đáp ứng yêu cầu cụ thể để CIDRAM trong một cách mà có sự quen thuộc với YAML, nhưng không theo cũng không tuân thủ các thông số kỹ thuật chính thức (và do đó sẽ không cư xử theo cách tương tự như một số biến thể nơi khác, và có thể không thích hợp cho các dự án khác nơi khác).

Trong CIDRAM, phân khúc YAML được xác định để kịch bản bằng ba dấu gạch ngang ("---"), và chấm dứt cùng với phần chữ ký chứa của họ bởi hai ngắt dòng. Một phân khúc YAML điển hình trong phần chữ ký bao gồm ba dấu gạch ngang trên một dòng ngay sau khi danh sách các CIDRS và bất kỳ gắn thẻ, theo sau là một danh sách cặp khóa giá trị hai chiều (chiều đầu tiên, loại tùy chọn cấu hình; chiều thứ cấp, tùy chọn cấu hình) cho những tùy chọn cấu hình mà cần được sửa đổi (và những giá trị) bất cứ khi nào một chữ ký trong đó phần chữ ký được kích hoạt (xem các ví dụ dưới đây).

```
# Foobar 1.
1.2.3.4/32 Deny Generic
2.3.4.5/32 Deny Generic
4.5.6.7/32 Deny Generic
Tag: Foobar 1
---
general:
 logfile: logfile.{yyyy}-{mm}-{dd}.txt
 logfileApache: access.{yyyy}-{mm}-{dd}.txt
 logfileSerialized: serial.{yyyy}-{mm}-{dd}.txt
 forbid_on_block: false
 emailaddr: username@domain.tld
recaptcha:
 lockip: false
 lockuser: true
 expiry: 720
 logfile: recaptcha.{yyyy}-{mm}-{dd}.txt
 enabled: true
template_data:
 css_url: http://domain.tld/cidram.css

# Foobar 2.
1.2.3.4/32 Deny Generic
2.3.4.5/32 Deny Generic
4.5.6.7/32 Deny Generic
Tag: Foobar 2
---
general:
 logfile: "logfile.Foobar2.{yyyy}-{mm}-{dd}.txt"
 logfileApache: "access.Foobar2.{yyyy}-{mm}-{dd}.txt"
 logfileSerialized: "serial.Foobar2.{yyyy}-{mm}-{dd}.txt"
 forbid_on_block: 503

# Foobar 3.
1.2.3.4/32 Deny Generic
2.3.4.5/32 Deny Generic
4.5.6.7/32 Deny Generic
Tag: Foobar 3
---
general:
 forbid_on_block: 403
 silent_mode: "http://127.0.0.1/"
```

##### 7.2.1 LÀM THẾ NÀO ĐỂ "ĐẶC BIỆT ĐÁNH DẤU" PHẦN CHỮ KÝ ĐỂ SỬ DỤNG VỚI reCAPTCHA

Khi "usemode" là 0 hay 1, phần chữ ký không cần phải được "đặc biệt đánh dấu" để sử dụng với reCAPTCHA (bởi vì họ đã sẽ hoặc sẽ không sử dụng reCAPTCHA, tùy thuộc vào tùy chọn này).

Khi "usemode" là 2, để "đặc biệt đánh dấu" phần chữ ký để sử dụng với reCAPTCHA, một mục được bao gồm trong phân khúc YAML cho rằng phần chữ ký (xem ví dụ dưới đây).

```
# Phần này sẽ sử dụng reCAPTCHA.
1.2.3.4/32 Deny Generic
2.3.4.5/32 Deny Generic
Tag: reCAPTCHA-Enabled
---
recaptcha:
 enabled: true
```

*Lưu ý: Theo mặc định, một trường hợp reCAPTCHA sẽ chỉ được cung cấp cho người dùng nếu reCAPTCHA được kích hoạt (với "usemode" như 1, hay "usemode" như 2 với "enabled" như true), và nếu chính xác MỘT chữ ký đã được kích hoạt (không nhiều hơn, không ít hơn; nếu nhiều chữ ký được kích hoạt, một trường hợp reCAPTCHA sẽ KHÔNG được cung cấp). Tuy nhiên, hành vi này có thể được sửa đổi thông qua chỉ thị "signature_limit".*

#### 7.3 PHỤ TRỢ

Ngoài ra, nếu bạn muốn CIDRAM để hoàn toàn bỏ qua một số phần cụ thể trong bất kỳ tập tin chữ ký, bạn có thể sử dụng các tập tin `ignore.dat` để xác định những phần để bỏ qua. Trên một dòng mới, viết `Ignore`, theo sau là một không gian, theo sau là tên của phần mà bạn muốn CIDRAM để bỏ qua (xem ví dụ dưới đây).

```
Ignore Phần 1
```

#### 7.4 <a name="MODULE_BASICS"></a>KHÁI NIỆM CƠ BẢN (CHO MÔ-ĐUN)

Các mô-đun có thể được sử dụng để mở rộng chức năng của CIDRAM, thực hiện các tác vụ bổ sung hay xử lý logic bổ sung. Thông thường, chúng được sử dụng khi cần thiết để chặn một yêu cho cầu lý do khác với địa chỉ IP có nguồn gốc (và như vậy, khi một chữ ký CIDR sẽ không đủ để chặn yêu cầu). Mô-đun được viết như tập tin PHP, và như vậy, thông thường, chữ ký mô-đun được viết như mã PHP.

Một số ví dụ điển hình về mô-đun CIDRAM có thể được tìm thấy ở đây:
- https://github.com/CIDRAM/CIDRAM-Extras/tree/master/modules

Bạn có thể tìm thấy khuôn mẫu để viết mô-đun mới ở đây:
- https://github.com/CIDRAM/CIDRAM-Extras/blob/master/modules/module_template.php

Bởi vì các mô-đun được viết như tập tin PHP, nếu bạn đã quen thuộc với mã nguồn CIDRAM, bạn có thể cấu trúc module của bạn tuy nhiên bạn muốn, và viết chữ ký mô-đun của bạn tuy nhiên bạn muốn (trong vòng suy luận những gì có thể với PHP). Tuy nhiên, để thuận tiện cho bạn, và vì lợi ích của hiểu rõ hơn giữa các mô-đun hiện tại và của riêng bạn, phân tích mẫu liên kết ở trên được khuyến nghị, để có thể sử dụng cấu trúc và định dạng mà nó cung cấp.

*Lưu ý: Nếu bạn không cảm thấy thoải mái khi làm việc với mã PHP, bạn không nên viết mô-đun riêng của mình.*

Một số chức năng được cung cấp bởi CIDRAM cho các mô-đun để sử dụng, để làm cho việc viết mô-đun của bạn trở nên đơn giản và dễ dàng hơn. Thông tin về chức năng này được mô tả dưới đây.

#### 7.5 CHỨC NĂNG MÔ-ĐUN

##### 7.5.0 "$Trigger"

Chữ ký mô-đun thường được viết bằng "$Trigger". Trong hầu hết các trường hợp, sự đóng này sẽ quan trọng hơn bất cứ thứ gì khác để viết mô-đun.

"$Trigger" chấp nhận 4 tham số: "$Condition", "$ReasonShort", "$ReasonLong" (không bắt buộc), và "$DefineOptions" (không bắt buộc).

Thực tế của "$Condition" được đánh giá, và nếu true/đúng, chữ ký là "kích hoạt". Nếu false/sai, chữ ký không phải là "kích hoạt". "$Condition" thường có chứa mã PHP để đánh giá một điều kiện nên làm yêu cầu bị chặn.

"$ReasonShort" được trích dẫn trong trường "Tại sao bị chặn" khi chữ ký được "kích hoạt".

"$ReasonLong" là một thông báo tùy chọn được hiển thị cho người dùng / khách hàng khi chúng bị chặn, để giải thích tại sao chúng bị chặn. Nó sử dụng thông báo "Truy cập đã bị từ chối" thông thường khi bị bỏ qua.

"$DefineOptions" là một mảng tùy chọn có chứa cặp khóa / giá trị, được sử dụng để xác định các tùy chọn cấu hình cụ thể cho trường hợp yêu cầu. Tùy chọn cấu hình sẽ được áp dụng khi chữ ký được "kích hoạt".

"$Trigger" trả về true/đúng khi chữ ký được "kích hoạt", và false/sai khi không.

Để sử dụng sự đóng này trong mô-đun của bạn, trước tiên hãy nhớ kế thừa nó từ phạm vi cha mẹ:
```PHP
$Trigger = $CIDRAM['Trigger'];
```

##### 7.5.1 "$Bypass"

Đường tránh chữ ký thường được viết bằng "$Bypass".

"$Bypass" chấp nhận 3 tham số: "$Condition", "$ReasonShort", và "$DefineOptions" (không bắt buộc).

Thực tế của "$Condition" được đánh giá, và nếu true/đúng, đường tránh là "kích hoạt". Nếu false/sai, đường tránh không phải là "kích hoạt". "$Condition" thường có chứa mã PHP để đánh giá một điều kiện *không* nên làm yêu cầu bị chặn.

"$ReasonShort" được trích dẫn trong trường "Tại sao bị chặn" khi đường tránh được "kích hoạt".

"$DefineOptions" là một mảng tùy chọn có chứa cặp khóa / giá trị, được sử dụng để xác định các tùy chọn cấu hình cụ thể cho trường hợp yêu cầu. Tùy chọn cấu hình sẽ được áp dụng khi đường tránh được "kích hoạt".

"$Bypass" trả về true/đúng khi đường tránh được "kích hoạt", và false/sai khi không.

Để sử dụng sự đóng này trong mô-đun của bạn, trước tiên hãy nhớ kế thừa nó từ phạm vi cha mẹ:
```PHP
$Bypass = $CIDRAM['Bypass'];
```

##### 7.5.2 "$CIDRAM['DNS-Reverse']"

Điều này có thể được sử dụng để lấy tên máy chủ của một địa chỉ IP. Nếu bạn muốn tạo một mô-đun để chặn tên máy chủ, sự đóng này có thể hữu ích.

Ví dụ:
```PHP
<?php
/** Inherit trigger closure (see functions.php). */
$Trigger = $CIDRAM['Trigger'];

/** Fetch hostname. */
if (empty($CIDRAM['Hostname'])) {
    $CIDRAM['Hostname'] = $CIDRAM['DNS-Reverse']($CIDRAM['BlockInfo']['IPAddr']);
}

/** Example signature. */
if ($CIDRAM['Hostname'] && $CIDRAM['Hostname'] !== $CIDRAM['BlockInfo']['IPAddr']) {
    $Trigger($CIDRAM['Hostname'] === 'www.foobar.tld', 'Foobar.tld', 'Hostname Foobar.tld is not allowed.');
}
```

#### 7.6 BIẾN MÔ-ĐUN

Mô-đun thực hiện theo phạm vi riêng của chúng, và bất kỳ biến nào được xác định bởi mô-đun, sẽ không thể truy cập vào mô-đun khác, hoặc kịch bản cha mẹ, trừ khi chúng được lưu trữ trong mảng "$CIDRAM" (mọi thứ khác được làm sạch sau khi kết thúc thực hiện mô-đun).

Dưới đây là một số biến phổ biến có thể hữu ích cho mô-đun của bạn:

Biến | Chi tiết
----|----
`$CIDRAM['BlockInfo']['DateTime']` | Ngày hiện tại và thời gian.
`$CIDRAM['BlockInfo']['IPAddr']` | Địa chỉ IP cho yêu cầu hiện tại.
`$CIDRAM['BlockInfo']['ScriptIdent']` | Phiên bản kịch bản CIDRAM.
`$CIDRAM['BlockInfo']['Query']` | Truy vấn (query) cho yêu cầu hiện tại.
`$CIDRAM['BlockInfo']['Referrer']` | Người giới thiệu (referrer) cho yêu cầu hiện tại (nếu có).
`$CIDRAM['BlockInfo']['UA']` | Đại lý người dùng (user agent) cho yêu cầu hiện tại.
`$CIDRAM['BlockInfo']['UALC']` | Đại lý người dùng (user agent) cho yêu cầu hiện tại (trong trường hợp thấp).
`$CIDRAM['BlockInfo']['ReasonMessage']` | Thông báo sẽ được hiển thị cho người dùng / khách hàng cho yêu cầu hiện tại nếu chúng bị chặn.
`$CIDRAM['BlockInfo']['SignatureCount']` | Số chữ ký kích hoạt cho yêu cầu hiện tại.
`$CIDRAM['BlockInfo']['Signatures']` | Thông tin tham khảo cho bất kỳ chữ ký nào được kích hoạt cho yêu cầu hiện tại.
`$CIDRAM['BlockInfo']['WhyReason']` | Thông tin tham khảo cho bất kỳ chữ ký nào được kích hoạt cho yêu cầu hiện tại.

---


### 8. <a name="SECTION8"></a>NHỮNG VẤN ĐỀ HỢP TƯƠNG TÍCH

Các gói và sản phẩm sau đã được tìm thấy là không tương thích với CIDRAM:
- __[Endurance Page Cache](https://github.com/CIDRAM/CIDRAM/issues/52)__

Các mô-đun đã được cung cấp để đảm bảo rằng các gói và sản phẩm sau sẽ tương thích với CIDRAM:
- __[BunnyCDN](https://github.com/CIDRAM/CIDRAM/issues/56)__

*Xem thêm: [Biểu đồ tương thích](https://maikuolan.github.io/Compatibility-Charts/).*

---


### 9. <a name="SECTION9"></a>NHỮNG CÂU HỎI THƯỜNG GẶP (FAQ)

- ["Chữ ký" là gì?](#WHAT_IS_A_SIGNATURE)
- ["CIDR" là gì?](#WHAT_IS_A_CIDR)
- ["Sai tích cực" là gì?](#WHAT_IS_A_FALSE_POSITIVE)
- [CIDRAM có thể chặn toàn bộ quốc gia?](#BLOCK_ENTIRE_COUNTRIES)
- [Tần suất cập nhật chữ ký là bao nhiêu?](#SIGNATURE_UPDATE_FREQUENCY)
- [Tôi đã gặp một vấn đề trong khi sử dụng CIDRAM và tôi không biết phải làm gì về nó! Hãy giúp tôi!](#ENCOUNTERED_PROBLEM_WHAT_TO_DO)
- [Tôi đã bị chặn bởi CIDRAM từ một trang web mà tôi muốn ghé thăm! Hãy giúp tôi!](#BLOCKED_WHAT_TO_DO)
- [Tôi muốn sử dụng CIDRAM với phiên bản PHP cũ hơn 5.4.0; Bạn có thể giúp?](#MINIMUM_PHP_VERSION)
- [Tôi có thể sử dụng một cài đặt CIDRAM để bảo vệ nhiều tên miền?](#PROTECT_MULTIPLE_DOMAINS)
- [Tôi không muốn lãng phí thời gian bằng cách cài đặt này và đảm bảo rằng nó hoạt động với trang web của tôi; Tôi có thể trả tiền cho bạn để làm điều đó cho tôi?](#PAY_YOU_TO_DO_IT)
- [Tôi có thể thuê bạn hay bất kỳ nhà phát triển nào của dự án này cho công việc riêng tư?](#HIRE_FOR_PRIVATE_WORK)
- [Tôi cần sửa đổi chuyên môn, tuỳ chỉnh, vv; Bạn có thể giúp?](#SPECIALIST_MODIFICATIONS)
- [Tôi là nhà phát triển, nhà thiết kế trang web, hay lập trình viên. Tôi có thể chấp nhận hay cung cấp các công việc liên quan đến dự án này không?](#ACCEPT_OR_OFFER_WORK)
- [Tôi muốn đóng góp cho dự án; Tôi có thể làm được điều này?](#WANT_TO_CONTRIBUTE)
- [Tôi có thể sử dụng cron để cập nhật tự động không?](#CRON_TO_UPDATE_AUTOMATICALLY)
- ["Vi phạm" là gì?](#WHAT_ARE_INFRACTIONS)
- [CIDRAM có thể chặn tên máy chủ không?](#BLOCK_HOSTNAMES)
- [Những gì tôi có thể sử dụng cho "default_dns"?](#WHAT_CAN_I_USE_FOR_DEFAULT_DNS)
- [Tôi có thể sử dụng CIDRAM để bảo vệ những thứ khác ngoài trang web (v.d., máy chủ email, FTP, SSH, IRC, vv)?](#PROTECT_OTHER_THINGS)
- [Sẽ xảy ra sự cố nếu tôi sử dụng CIDRAM cùng lúc với việc sử dụng các CDN hoặc các dịch vụ bộ nhớ đệm?](#CDN_CACHING_PROBLEMS)
- [CIDRAM có bảo vệ trang web của tôi khỏi các cuộc tấn công DDoS không?](#DDOS_ATTACKS)
- [Khi tôi kích hoạt hoặc hủy kích hoạt các mô-đun hay các tập tin chữ ký thông qua trang cập nhật, nó sắp xếp chúng theo thứ tự chữ và số trong cấu hình. Tôi có thể thay đổi cách họ được sắp xếp không?](#CHANGE_COMPONENT_SORT_ORDER)

#### <a name="WHAT_IS_A_SIGNATURE"></a>"Chữ ký" là gì?

Trong bối cảnh của CIDRAM, "chữ ký" đề cập đến dữ liệu hoạt động như một định danh cho một cái gì đó cụ thể mà chúng tôi đang tìm kiếm, thường là một địa chỉ IP hoặc CIDR, và bao gồm một số chỉ dẫn cho CIDRAM, nói với nó cách trả lời khi nó gặp những gì chúng ta đang tìm kiếm. Một chữ ký CIDRAM điển hình trông giống như thế này:

Đối với "tập tin chữ ký":

`1.2.3.4/32 Deny Generic`

Đối với "mô-đun":

```PHP
$Trigger(strpos($CIDRAM['BlockInfo']['UA'], 'Foobar') !== false, 'Foobar-UA', 'User agent "Foobar" not allowed.');
```

*Chú thích: Chữ ký cho "tập tin chữ ký", và chữ ký cho "mô-đun", không phải là cùng một điều.*

Thông thường (nhưng không phải luôn luôn), chữ ký sẽ được nhóm lại với nhau, để hình thành "phần chữ ký", thường kèm theo bình luận, đánh dấu, hay siêu dữ liệu liên quan mà có thể được sử dụng để cung cấp bối cảnh bổ sung cho chữ ký hay lệnh bổ sung.

#### <a name="WHAT_IS_A_CIDR"></a>"CIDR" là gì?

"CIDR" là một từ viết tắt cho "Classless Inter-Domain Routing" *[[1](https://en.wikipedia.org/wiki/Classless_Inter-Domain_Routing), [2](http://whatismyipaddress.com/cidr)]*, và nó là từ viết tắt này được sử dụng như là một phần của tên cho gói này, "CIDRAM", đó là một từ viết tắt cho "Classless Inter-Domain Routing Access Manager".

Tuy nhiên, trong bối cảnh của CIDRAM (như là, trong tài liệu này, trong các cuộc thảo luận liên quan đến CIDRAM, hay trong dữ liệu ngôn ngữ CIDRAM), bất cứ khi nào một "CIDR" (số ít) hoặc "CIDRs" (số nhiều) được đề cập hoặc tham khảo (và như vậy khi chúng ta sử dụng những từ này như danh từ, ngược lại với từ viết tắt), những gì được dự định và có ý nghĩa bởi đây là một subnet, bày tỏ bằng cách sử dụng ký hiệu CIDR. Lý do mà CIDR (hoặc CIDRs) được sử dụng thay vì subnet là để làm rõ mà nó là các subnet bày tỏ bằng cách sử dụng ký hiệu CIDR mà được đề cập đến (bởi vì ký hiệu CIDR chỉ là một trong một số cách khác nhau mà các subnet có thể được bày tỏ). Như vậy, CIDRAM có thể được coi là quản lý truy cập cho subnet.

Mặc dù ý nghĩa kép của "CIDR" có thể đưa ra một số sự mơ hồ trong một số trường hợp, giải thích này, cùng với bối cảnh được cung cấp, sẽ giúp giải quyết sự mơ hồ đó.

#### <a name="WHAT_IS_A_FALSE_POSITIVE"></a>"Sai tích cực" là gì?

Nghĩa của "sai tích cực" (*hay: "lỗi sai tích cực"; "báo động giả"*; Tiếng Anh: *false positive*; *false positive error*; *false alarm*), mô tả rất đơn giản, và trong một bối cảnh tổng quát, được sử dụng khi kiểm tra cho một điều kiện, để tham khảo các kết quả của bài kiểm tra, khi kết quả là tích cực (hay, điều kiện được xác định là "tích cực", hay "đúng"), nhưng dự kiến sẽ được (hay cần phải có được) tiêu cực (hay, điều kiện, thực tế, là "tiêu cực", hay "sai"). "Sai tích cực" có thể được coi là điều tương tự như "khóc sói" (theo đó các điều kiện đang được kiểm tra là liệu có con sói gần đàn, điều kiện là "sai" bởi vì không có con sói gần đàn, và điều kiện được báo cáo là "tích cực" bởi các người chăn bằng cách gọi "sói, sói"), hay tương tự như tình huống trong thử nghiệm y tế theo đó một bệnh nhân được chẩn đoán là có một số bệnh, trong khi thực tế, họ không có bất kỳ số bệnh.

Một số các từ ngữ khác sử dụng là "đúng tích cực", "đúng tiêu cực" và "sai tiêu cực". "Đúng tích cực" đề cập đến khi các kết quả kiểm tra và tình trạng thực tế của điều kiện là cả hai đúng (hay "tích cực"), và "đúng tiêu cực" đề cập đến khi các kết quả kiểm tra và tình trạng thực tế của điều kiện là cả hai sai (hay "tiêu cực"); "Đúng tích cực" hay "đúng tiêu cực" được coi là một "suy luận đúng". Các phản đề của "sai tích cực" là "sai tiêu cực"; "Sai tiêu cực" đề cập đến khi các kết quả kiểm tra là tiêu cực (hay, điều kiện được xác định là "tiêu cực", hay "sai"), nhưng dự kiến sẽ được (hay cần phải có được) tích cực (hay, điều kiện, thực tế, là "tích cực", hay "đúng").

Trong bối cảnh CIDRAM, các từ ngữ đề cập đến chữ ký của CIDRAM và các họ chặn gì/ai. Khi CIDRAM chặn địa chỉ IP bởi vì chữ ký của nó là xấu, lỗi thời hay không chính xác, nhưng không nên làm như vậy, hay khi nó làm như vậy vì những lý do sai, chúng tôi đề cập đến sự kiện này như "sai tích cực". Khi CIDRAM không chặn một địa chỉ IP đó nên đã bị chặn, bởi vì mối đe dọa khó lường, chữ ký mất tích hay thiếu sót trong chữ ký, chúng tôi đề cập đến sự kiện này như "phát hiện mất tích" (which is analogous to a "sai tiêu cực").

Điều này có thể được tóm tắt bằng bảng dưới đây:

&nbsp; | CIDRAM *KHÔNG* nên chặn một địa chỉ IP | CIDRAM *NÊN* chặn một địa chỉ IP
---|---|---
CIDRAM *KHÔNG* chặn một địa chỉ IP | Đúng tiêu cực (suy luận đúng) | Phát hiện mất tích (điều tương tự như sai tiêu cực)
CIDRAM chặn một địa chỉ IP | __Sai tích cực__ | Đúng tích cực (suy luận đúng)

#### <a name="BLOCK_ENTIRE_COUNTRIES"></a>CIDRAM có thể chặn toàn bộ quốc gia?

Vâng. Cách dễ nhất để đạt được điều này sẽ được cài đặt một số các danh sách chặn quốc gia tùy chọn được cung cấp bởi Macmathan. Điều này có thể được thực hiện với một vài cú nhấp chuột đơn giản trực tiếp từ trang cập nhật của front-end, hoặc, nếu bạn thích các front-end ở lại vô hiệu hóa, bằng cách tải chúng trực tiếp từ **[trang tải xuống cho các danh sách chặn quốc gia tùy chọn](https://macmathan.info/blocklists)**, tải chúng lên vault, và trích dẫn tên của họ trong các chỉ thị cấu hình có liên quan.

#### <a name="SIGNATURE_UPDATE_FREQUENCY"></a>Tần suất cập nhật chữ ký là bao nhiêu?

Tần suất cập nhật thay đổi tùy thuộc vào các tập tin chữ ký trong câu hỏi. Nói chung là, tất cả các người bảo trì cho các tất cả tập tin chữ ký cố gắng đảm bảo rằng chữ ký của họ được cập nhật càng nhiều càng tốt, nhưng bởi vì tất cả chúng ta đều có nhiều cam kết khác, cuộc sống của chúng ta bên ngoài dự án, và bởi vì không ai trong chúng ta được bồi thường tài chính (hay được thanh toán) cho các nỗ lực dự án của chúng tôi, Một lịch trình cập nhật chính xác không thể được đảm bảo. Nói chung là, chữ ký được cập nhật bất cứ khi nào có đủ thời gian để cập nhật chúng, và các người bảo trì cố gắng ưu tiên dựa trên sự cần thiết và dựa trên tần suất của thay đổi giữa các phạm vi. Trợ giúp luôn được đánh giá cao nếu bạn sẵn sàng cung cấp bất kỳ.

#### <a name="ENCOUNTERED_PROBLEM_WHAT_TO_DO"></a>Tôi đã gặp một vấn đề trong khi sử dụng CIDRAM và tôi không biết phải làm gì về nó! Hãy giúp tôi!

- Bạn đang sử dụng phiên bản mới nhất của phần mềm? Bạn đang sử dụng phiên bản mới nhất của tập tin chữ ký của bạn? Nếu câu trả lời cho một trong hai những câu hỏi này là không, cố gắng cập nhật mọi thứ đầu tiên, và kiểm tra nếu vấn đề vẫn còn. Nếu nó vẫn còn, tiếp tục đọc.
- Bạn đã kiểm tra tất cả các tài liệu chưa? Nếu không, xin hãy làm như vậy. Nếu vấn đề không thể giải quyết bằng cách sử dụng tài liệu, hãy tiếp tục đọc.
- Bạn đã kiểm tra các **[trang issues](https://github.com/CIDRAM/CIDRAM/issues)** chưa, để xem nếu vấn đề đã được đề cập trước đó? Nếu nó đã được đề cập trước đó, kiểm tra nếu có bất kỳ đề xuất, ý tưởng, hay giải pháp đã được cung cấp, và làm theo như là cần thiết để cố gắng giải quyết vấn đề.
- Nếu vấn đề vẫn còn, vui lòng hãy tìm sự giúp đỡ về nó bằng cách tạo ra một issue mới trên trang issues.

#### <a name="BLOCKED_WHAT_TO_DO"></a>Tôi đã bị chặn bởi CIDRAM từ một trang web mà tôi muốn ghé thăm! Hãy giúp tôi!

CIDRAM cung cấp một cách cho chủ sở hữu trang web để chặn lưu lượng không mong muốn, nhưng đó là trách nhiệm của chủ sở hữu trang web tự quyết định cách mà họ muốn sử dụng CIDRAM. Trong trường hợp của sai tích cực liên quan đến các tập tin chữ ký thường trong gói CIDRAM, đính chính có thể được thực hiện, nhưng để được bỏ chặn từ các trang web cụ thể, bạn sẽ cần phải liên hệ với chủ sở hữu của các trang web được đề cập. Trong trường hợp đính chính được thực hiện, ít nhất, họ sẽ cần phải cập nhật các tập tin chữ ký hay cài đặt của họ, và trong các trường hợp khác (chẳng hạn như, ví dụ, khi họ đã sửa đổi cài đặt của họ, đã tạo ra chữ ký riêng của họ, vv), trách nhiệm của giải quyết vấn đề của bạn hoàn toàn là của họ, và hoàn toàn nằm ngoài tầm kiểm soát của chúng tôi.

#### <a name="MINIMUM_PHP_VERSION"></a>Tôi muốn sử dụng CIDRAM với phiên bản PHP cũ hơn 5.4.0; Bạn có thể giúp?

Không. PHP 5.4.0 đạt EoL ("End of Life", hoặc sự kết thúc của cuộc sống) chính thức vào năm 2014, và hỗ trợ an ninh mở rộng đã được chấm dứt vào năm 2015. Khi viết này, nó là năm 2017, và PHP 7.1.0 đã có sẵn. Tại thời điểm này, hỗ trợ được cung cấp để sử dụng CIDRAM với PHP 5.4.0 và tất cả các phiên bản PHP có sẵn mới hơn, nhưng nếu bạn cố gắng sử dụng CIDRAM với bất kỳ phiên bản PHP lớn hơn, hỗ trợ sẽ không được cung cấp.

*Xem thêm: [Biểu đồ tương thích](https://maikuolan.github.io/Compatibility-Charts/).*

#### <a name="PROTECT_MULTIPLE_DOMAINS"></a>Tôi có thể sử dụng một cài đặt CIDRAM để bảo vệ nhiều tên miền?

Vâng. Cài đặt CIDRAM không bị khóa vào các tên miền cụ thể, và do đó có thể được sử dụng để bảo vệ nhiều tên miền. Nói chung là, chúng tôi đề cập đến cài đặt CIDRAM chỉ bảo vệ một miền như "cài đặt miền đơn" ("single-domain installations"), và chúng tôi đề cập đến cài đặt CIDRAM bảo vệ nhiều miền hay miền phụ như "cài đặt nhiều miền" ("multi-domain installations"). Nếu bạn sử dụng một cài đặt nhiều miền và cần phải sử dụng các bộ tập tin chữ ký khác nhau cho các miền khác nhau, hoặc cần CIDRAM được cấu hình khác nhau cho các miền khác nhau, điều này có thể làm được. Sau khi tải tập tin cấu hình (`config.ini`), CIDRAM sẽ kiểm tra sự tồn tại của một "tập tin ghi đè cấu hình" cụ thể cho miền được yêu cầu (`miền-được-yêu-cầu.tld.config.ini`), và nếu được tìm thấy, bất kỳ giá trị cấu hình nào được xác định bởi tập tin ghi đè cấu hình sẽ được sử dụng cho trường hợp thực hiện thay vì các giá trị cấu hình được định nghĩa bởi tập tin cấu hình. Các tập tin ghi đè cấu hình giống với tập tin cấu hình, và tùy theo quyết định của bạn, có thể chứa toàn bộ các chỉ thị cấu hình sẵn có cho CIDRAM, hoặc bất kỳ phần bắt buộc nào mà khác với các giá trị được xác định bởi tập tin cấu hình. Các tập tin ghi đè cấu hình được đặt tên theo miền mà chúng được dự định (vì vậy, ví dụ, nếu bạn cần một tập tin ghi đè cấu hình cho miền, `http://www.some-domain.tld/`, các tập tin ghi đè cấu hình của nó nên được đặt tên là `some-domain.tld.config.ini`, và nên được đặt trong vault với tập tin cấu hình, `config.ini`). Tên miền cho trường hợp thực hiện được bắt nguồn từ header (tiêu đề) `HTTP_HOST` của các yêu cầu; "www" bị bỏ qua.

#### <a name="PAY_YOU_TO_DO_IT"></a>Tôi không muốn lãng phí thời gian bằng cách cài đặt này và đảm bảo rằng nó hoạt động với trang web của tôi; Tôi có thể trả tiền cho bạn để làm điều đó cho tôi?

Có lẽ. Điều này được xem xét theo từng trường hợp cụ thể. Cho chúng tôi biết những gì bạn cần, những gì bạn đang cung cấp, và chúng tôi sẽ cho bạn biết liệu chúng tôi có thể giúp đỡ hay không.

#### <a name="HIRE_FOR_PRIVATE_WORK"></a>Tôi có thể thuê bạn hay bất kỳ nhà phát triển nào của dự án này cho công việc riêng tư?

*Xem ở trên.*

#### <a name="SPECIALIST_MODIFICATIONS"></a>Tôi cần sửa đổi chuyên môn, tuỳ chỉnh, vv; Bạn có thể giúp?

*Xem ở trên.*

#### <a name="ACCEPT_OR_OFFER_WORK"></a>Tôi là nhà phát triển, nhà thiết kế trang web, hay lập trình viên. Tôi có thể chấp nhận hay cung cấp các công việc liên quan đến dự án này không?

Vâng. Giấy phép của chúng tôi không cấm điều này.

#### <a name="WANT_TO_CONTRIBUTE"></a>Tôi muốn đóng góp cho dự án; Tôi có thể làm được điều này?

Vâng. Đóng góp cho dự án rất được hoan nghênh. Vui lòng xem "CONTRIBUTING.md" để biết thêm thông tin.

#### <a name="CRON_TO_UPDATE_AUTOMATICALLY"></a>Tôi có thể sử dụng cron để cập nhật tự động không?

Vâng. API được tích hợp trong front-end để tương tác với trang cập nhật thông qua các kịch bản bên ngoài. Một kịch bản riêng biệt, "[Cronable](https://github.com/Maikuolan/Cronable)", là có sẵn, và có thể được sử dụng bởi cron manager hay cron scheduler để tự động cập nhật gói này và gói hỗ trợ khác (kịch bản này cung cấp tài liệu riêng của nó).

#### <a name="WHAT_ARE_INFRACTIONS"></a>"Vi phạm" là gì?

"Vi phạm" xác định khi một IP không bị chặn bởi bất kỳ tập tin chữ ký cụ thể nào cũng sẽ bị chặn vì bất kỳ yêu cầu nào trong tương lai, và chúng liên quan chặt chẽ với giám sát IP. Một số chức năng và mô-đun tồn tại cho phép các yêu cầu bị chặn vì các lý do khác với IP có nguồn gốc (như là sự hiện diện của các đại lý người dùng ["user agents"] tương ứng với chương trình thư rác ["spambots"] hay công cụ hack ["hacktools"], truy vấn nguy hiểm, giả mạo DNS và vv), và khi điều này xảy ra, một "vi phạm" có thể xảy ra. Chúng cung cấp cách để nhận định địa chỉ IP tương ứng với các yêu cầu không mong muốn mà có thể chưa bị chặn bởi bất kỳ tập tin chữ ký cụ thể nào. Các vi phạm thường tương ứng với 1 đến 1 với số lần IP bị chặn, nhưng không phải luôn luôn (sự kiện nghiêm trọng chặn có thể gây ra một giá trị vi phạm lớn hơn một, và nếu "track_mode" là sai [false], vi phạm sẽ không xảy ra cho các sự kiện chặn gây ra bởi chỉ các tập tin chữ ký).

#### <a name="BLOCK_HOSTNAMES"></a>CIDRAM có thể chặn tên máy chủ không?

Vâng. Để làm điều này, bạn sẽ cần tạo tập tin mô-đun tùy chỉnh. *Xem: [KHÁI NIỆM CƠ BẢN (CHO MÔ-ĐUN)](#MODULE_BASICS)*.

#### <a name="WHAT_CAN_I_USE_FOR_DEFAULT_DNS"></a>Những gì tôi có thể sử dụng cho "default_dns"?

Nói chung, IP của bất kỳ máy chủ DNS đáng tin cậy nào sẽ đủ. Nếu bạn đang tìm kiếm đề xuất, [public-dns.info](https://public-dns.info/) và [OpenNIC](https://servers.opennic.org/) cung cấp danh sách rộng rãi các máy chủ DNS công cộng đã biết. Ngoài ra, xem bảng dưới đây:

IP | Nhà điều hành
---|---
`1.1.1.1` | [Cloudflare](https://www.cloudflare.com/learning/dns/what-is-1.1.1.1/)
`4.2.2.1`<br />`4.2.2.2`<br />`209.244.0.3`<br />`209.244.0.4` | [Level3](https://www.level3.com/en/)
`8.8.4.4`<br />`8.8.8.8`<br />`2001:4860:4860::8844`<br />`2001:4860:4860::8888` | [Google Public DNS](https://developers.google.com/speed/public-dns/)
`9.9.9.9`<br />`149.112.112.112` | [Quad9 DNS](https://www.quad9.net/)
`84.200.69.80`<br />`84.200.70.40`<br />`2001:1608:10:25::1c04:b12f`<br />`2001:1608:10:25::9249:d69b` | [DNS.WATCH](https://dns.watch/index)
`208.67.220.220`<br />`208.67.222.220`<br />`208.67.222.222` | [OpenDNS Home](https://www.opendns.com/)
`77.88.8.1`<br />`77.88.8.8`<br />`2a02:6b8::feed:0ff`<br />`2a02:6b8:0:1::feed:0ff` | [Yandex.DNS](https://dns.yandex.com/advanced/)
`8.20.247.20`<br />`8.26.56.26` | [Comodo Secure DNS](https://www.comodo.com/secure-dns/)
`216.146.35.35`<br />`216.146.36.36` | [Dyn](https://help.dyn.com/internet-guide-setup/)
`64.6.64.6`<br />`64.6.65.6` | [Verisign Public DNS](https://www.verisign.com/en_US/security-services/public-dns/index.xhtml)
`37.235.1.174`<br />`37.235.1.177` | [FreeDNS](https://freedns.zone/en/)
`156.154.70.1`<br />`156.154.71.1`<br />`2610:a1:1018::1`<br />`2610:a1:1019::1` | [Neustar Security](https://www.security.neustar/dns-services/free-recursive-dns-service)
`45.32.36.36`<br />`45.77.165.194`<br />`179.43.139.226` | [Fourth Estate](https://dns.fourthestate.co/)
`74.82.42.42` | [Hurricane Electric](https://dns.he.net/)
`195.46.39.39`<br />`195.46.39.40` | [SafeDNS](https://www.safedns.com/en/features/)
`81.218.119.11`<br />`209.88.198.133` | [GreenTeam Internet](http://www.greentm.co.uk/)
`89.233.43.71`<br />`91.239.100.100 `<br />`2001:67c:28a4::`<br />`2a01:3a0:53:53::` | [UncensoredDNS](https://blog.uncensoreddns.org/)
`208.76.50.50`<br />`208.76.51.51` | [SmartViper](http://www.markosweb.com/free-dns/)

*Chú thích: Tôi không cung cấp quả quyết hoặc đảm bảo về các chính sách bảo mật, tính bảo mật, hiệu quả, và độ tin cậy của bất kỳ dịch vụ DNS nào, được liệt kê hay cách khác. Xin vui lòng làm nghiên cứu của riêng bạn khi đưa ra quyết định về họ.*

#### <a name="PROTECT_OTHER_THINGS"></a>Tôi có thể sử dụng CIDRAM để bảo vệ những thứ khác ngoài trang web (v.d., máy chủ email, FTP, SSH, IRC, vv)?

Bạn có thể (theo nghĩa hợp pháp), nhưng không nên (theo nghĩa kỹ thuật và thực tế). Giấy phép của chúng tôi không hạn chế công nghệ nào thực hiện CIDRAM, nhưng CIDRAM là một WAF (Web Application Firewall) và luôn có ý định bảo vệ các trang web. Bởi vì nó không được thiết kế với các công nghệ khác trong tâm trí, nó không có hiệu quả hoặc cung cấp bảo vệ đáng tin cậy cho các công nghệ khác, việc thực hiện có thể là khó khăn, và nguy cơ sai tích cực và phát hiện mất tích là rất cao.

#### <a name="CDN_CACHING_PROBLEMS"></a>Sẽ xảy ra sự cố nếu tôi sử dụng CIDRAM cùng lúc với việc sử dụng các CDN hoặc các dịch vụ bộ nhớ đệm?

Có lẽ. Điều này phụ thuộc vào tính chất của dịch vụ được đề cập và cách bạn sử dụng dịch vụ. Nói chung, nếu bạn chỉ lưu trữ nội dung tĩnh (v.d., hình ảnh, CSS, vv; bất cứ điều gì không thay đổi theo thời gian), không nên có bất kỳ vấn đề. Có thể có vấn đề mặc dù, nếu bạn đang lưu trữ dữ liệu mà thông thường sẽ được tạo động nếu được yêu cầu, hoặc nếu bạn đang lưu trữ kết quả của các yêu cầu POST (điều này về cơ bản sẽ làm cho trang web của bạn và môi trường của nó như bắt buộc tĩnh, và CIDRAM không có khả năng cung cấp bất kỳ lợi ích có ý nghĩa nào trong một môi trường bắt buộc tĩnh). Cũng có thể có các yêu cầu cấu hình cụ thể cho CIDRAM, tùy thuộc vào dịch vụ bộ nhớ đệm hoặc CDN mà bạn đang sử dụng (bạn cần đảm bảo rằng CIDRAM được định cấu hình chính xác cho dịch vụ bộ nhớ đệm hoặc CDN cụ thể mà bạn đang sử dụng). Cấu hình không chính xác cho CIDRAM có thể dẫn đến vấn đề đáng kể của các sai tích cực và các sự phát hiện mất tích.

#### <a name="DDOS_ATTACKS"></a>CIDRAM có bảo vệ trang web của tôi khỏi các cuộc tấn công DDoS không?

Câu trả lời ngắn gọn: Không.

Câu trả lời hơi dài hơn: CIDRAM sẽ giúp giảm tác động mà lưu lượng không mong muốn có thể có trên trang web của bạn (do đó làm giảm chi phí băng thông của trang web của bạn), sẽ giúp giảm tác động mà lưu lượng không mong muốn có thể có trên phần cứng của bạn (ví dụ, khả năng xử lý và phân phối yêu cầu của máy chủ của bạn), và có thể giúp giảm các hiệu ứng tiêu cực tiềm năng khác liên quan đến lưu lượng không mong muốn. Tuy nhiên, có hai điều quan trọng cần nhớ để hiểu câu hỏi này.

Thứ nhất, CIDRAM là một gói PHP, và do đó hoạt động ở máy nơi PHP được cài đặt. Điều này có nghĩa là CIDRAM chỉ có thể xem và chặn một yêu cầu *sau khi* máy chủ đã nhận được nó. Thứ hai, giảm thiểu DDoS hiệu quả sẽ lọc các yêu cầu *trước khi* chúng đến được máy chủ được nhắm mục tiêu bởi cuộc tấn công DDoS. Lý tưởng nhất, các cuộc tấn công DDoS nên được phát hiện và giảm thiểu bằng các giải pháp có khả năng giảm hay định tuyến lại lưu lượng được liên kết đến cuộc tấn công, trước khi nó đến máy chủ được nhắm mục tiêu ngay từ đầu.

Điều này có thể được thực hiện bằng cách sử dụng các giải pháp phần cứng chuyên dụng có trên địa điểm, các giải pháp dựa trên đám mây như các dịch vụ giảm thiểu DDoS chuyên dụng, định tuyến DNS của tên miền thông qua mạng chống DDoS, lọc dựa trên đám mây, hoặc một số kết hợp của chúng. Tuy nhiên, trong mọi trường hợp, chủ đề này hơi phức tạp để giải thích kỹ lưỡng chỉ với một hoặc hai đoạn, vì vậy tôi khuyên bạn nên nghiên cứu thêm nếu đây là chủ đề bạn muốn theo đuổi. Khi bản chất thực sự của các cuộc tấn công DDoS được hiểu đúng, câu trả lời này sẽ có ý nghĩa hơn.

#### <a name="CHANGE_COMPONENT_SORT_ORDER"></a>Khi tôi kích hoạt hoặc hủy kích hoạt các mô-đun hay các tập tin chữ ký thông qua trang cập nhật, nó sắp xếp chúng theo thứ tự chữ và số trong cấu hình. Tôi có thể thay đổi cách họ được sắp xếp không?

Vâng. Nếu bạn cần buộc một số tập tin thực thi theo thứ tự cụ thể, bạn có thể thêm một số dữ liệu tùy ý trước tên của chúng trong chỉ thị cấu hình nơi chúng được liệt kê, được phân tách bằng dấu hai chấm. Khi trang cập nhật sau đó sắp xếp lại các tập tin, dữ liệu tùy ý được thêm này sẽ ảnh hưởng đến thứ tự sắp xếp, gây ra chúng do đó để thực hiện theo thứ tự mà bạn muốn, mà không cần phải đổi tên bất kỳ người nào trong số họ.

Ví dụ, giả sử một chỉ thị cấu hình với các tập tin được liệt kê như sau:

`file1.php,file2.php,file3.php,file4.php,file5.php`

Nếu bạn muốn `file3.php` thực hiện trước, bạn có thể thêm một cái gì đó như `aaa:` trước tên của tập tin:

`file1.php,file2.php,aaa:file3.php,file4.php,file5.php`

Sau đó, nếu một tập tin mới, `file6.php`, được kích hoạt, khi trang cập nhật sắp xếp lại tất cả, nó sẽ kết thúc như sau:

`aaa:file3.php,file1.php,file2.php,file4.php,file5.php,file6.php`

Tình huống tương tự khi một tập tin bị hủy kích hoạt. Ngược lại, nếu bạn muốn tập tin thực thi cuối cùng, bạn có thể thêm một cái gì đó như `zzz:` trước tên của tập tin. Trong mọi trường hợp, bạn sẽ không cần đổi tên tập tin đang được đề cập đến.

---


### 11. <a name="SECTION11"></a>THÔNG TIN HỢP PHÁP

#### 11.0 PHẦN MỞ ĐẦU

Phần tài liệu này nhằm mô tả các cân nhắc pháp lý có thể có về việc sử dụng và thực hiện của gói, và cung cấp một số thông tin liên quan cơ bản. Điều này có thể quan trọng đối với một số người dùng như một phương tiện để đảm bảo tuân thủ mọi yêu cầu pháp lý có thể tồn tại ở các quốc gia mà họ hoạt động, và một số người dùng có thể cần phải điều chỉnh chính sách trang web của họ theo thông tin này.

Đầu tiên và quan trọng nhất, xin vui lòng nhận ra rằng tôi (tác giả gói) không phải là luật sư, cũng không phải là một chuyên gia pháp lý đủ điều kiện. Do đó, tôi không đủ tư cách pháp lý để cung cấp tư vấn pháp lý. Ngoài ra, trong một số trường hợp, yêu cầu pháp lý chính xác có thể khác nhau giữa các quốc gia và khu vực pháp lý khác nhau, và các yêu cầu pháp lý khác nhau đôi khi có thể xung đột (chẳng hạn như, ví dụ, trong trường hợp các quốc gia mà ủng hộ [quyền riêng tư](https://vi.wikipedia.org/wiki/Quy%E1%BB%81n_%C4%91%C6%B0%E1%BB%A3c_b%E1%BA%A3o_v%E1%BB%87_%C4%91%E1%BB%9Di_t%C6%B0) và quyền bị lãng quên, so với các quốc gia mà ủng hộ luật lưu giữ dữ liệu). Cũng xem xét việc truy cập vào gói không bị giới hạn ở các quốc gia hoặc khu vực pháp lý cụ thể, và do đó, cơ sở người dùng gói có khả năng đa dạng về mặt địa lý. Những điểm này được xem xét, tôi không ở trong một vị trí để tuyên bố những gì nó có nghĩa là để "tuân thủ về mặt pháp lý" cho tất cả người dùng, trong tất cả các liên quan. Tuy nhiên, tôi hy vọng rằng thông tin trong tài liệu này sẽ giúp bạn tự quyết định về những gì bạn phải làm để duy trì tuân thủ về mặt pháp lý trong bối cảnh của gói. Nếu bạn có bất kỳ nghi ngờ hoặc quan tâm nào về thông tin ở đây, hoặc nếu bạn cần thêm trợ giúp và tư vấn từ góc độ pháp lý, tôi khuyên bạn nên tham khảo ý kiến chuyên gia pháp lý đủ điều kiện.

#### 11.1 TRÁCH NHIỆM PHÁP LÝ

Theo như đã nêu trong giấy phép gói, gói được cung cấp mà không có bất kỳ bảo hành nào. Điều này bao gồm (nhưng không giới hạn) tất cả phạm vi trách nhiệm pháp lý. Gói phần mềm được cung cấp cho bạn để thuận tiện cho bạn, với hy vọng rằng nó sẽ hữu ích, và rằng nó sẽ cung cấp một số lợi ích cho bạn. Tuy nhiên, việc sử dụng hoặc triển khai gói, là lựa chọn của riêng bạn. Bạn không bị buộc phải sử dụng hoặc triển khai gói, nhưng khi bạn làm như vậy, bạn chịu trách nhiệm về quyết định đó. Tôi và những người đóng góp gói khác, không chịu trách nhiệm pháp lý về hậu quả của các quyết định mà bạn đưa ra, bất kể trực tiếp, gián tiếp, ngụ ý, hay nói cách khác.

#### 11.2 BÊN THỨ BA

Tùy thuộc vào cấu hình và triển khai chính xác của nó, gói có thể giao tiếp và chia sẻ thông tin với bên thứ ba trong một số trường hợp. Thông tin này có thể được định nghĩa là "[thông tin nhận dạng cá nhân](http://www.pcworld.com.vn/articles/cong-nghe/an-ninh-mang/2016/05/1248000/thong-tin-ca-nhan-tai-san-rieng-cung-la-tien/)" (PII) trong một số ngữ cảnh, bởi một số khu vực pháp lý.

Thông tin này có thể được các bên thứ ba này sử dụng như thế nào, là tuân theo của chính sách của các bên thứ ba, và nằm ngoài phạm vi của tài liệu này. Tuy nhiên, trong tất cả các trường hợp như vậy, việc chia sẻ thông tin với các bên thứ ba này có thể bị vô hiệu hóa. Trong tất cả các trường hợp như vậy, nếu bạn chọn kích hoạt nó, bạn có trách nhiệm nghiên cứu bất kỳ mối lo ngại nào về sự riêng tư, bảo mật, và việc sử dụng PII của các bên thứ ba này. Nếu có bất kỳ nghi ngờ nào, hoặc nếu bạn không hài lòng với hành vi của các bên thứ ba liên quan đến PII, tốt nhất là nên vô hiệu hóa tất cả việc chia sẻ thông tin với các bên thứ ba này.

Với mục đích minh bạch, loại thông tin được chia sẻ, và với ai, được mô tả dưới đây.

##### 11.2.0 TRA CỨU TÊN MÁY CHỦ

Nếu bạn sử dụng bất kỳ tính năng hay mô-đun nào để làm việc với tên máy chủ (chẳng hạn như "mô-đun cho chặn các host xấu", "tor project exit nodes block module", hay "xác minh máy tìm kiếm", ví dụ), CIDRAM cần để có thể có được tên máy chủ của các yêu cầu gửi đến bằng cách nào đó. Thông thường, nó thực hiện điều này bằng cách yêu cầu tên máy chủ của địa chỉ IP của các yêu cầu gửi đến từ một máy chủ DNS, hoặc bằng cách yêu cầu thông tin thông qua chức năng được cung cấp bởi hệ thống nơi CIDRAM được cài đặt (điều này thường được gọi là "tra cứu tên máy chủ"). Các máy chủ DNS được xác định theo mặc định thuộc về dịch vụ [Google DNS](https://dns.google.com/) (nhưng điều này có thể dễ dàng thay đổi thông qua cấu hình). Các dịch vụ chính xác được truyền thông phụ thuộc vào cách bạn định cấu hình gói (nó có thể được cấu hình dễ dàng). Trong trường hợp sử dụng chức năng được cung cấp bởi hệ thống nơi CIDRAM được cài đặt, bạn sẽ cần phải liên hệ với quản trị viên hệ thống của bạn để xác định các tuyến đường để sử dụng cho tra cứu tên máy chủ. Tra cứu tên máy chủ có thể được ngăn chặn trong CIDRAM bằng cách tránh các mô-đun bị ảnh hưởng hay bằng cách sửa đổi cấu hình gói để phù hợp với nhu cầu của bạn.

*Chỉ thị cấu hình có liên quan:*
- `general` -> `default_dns`
- `general` -> `search_engine_verification`
- `general` -> `social_media_verification`
- `general` -> `force_hostname_lookup`
- `general` -> `allow_gethostbyaddr_lookup`

##### 11.2.1 WEBFONT

Một số chủ đề tùy chỉnh, cũng như UI chuẩn ("giao diện người dùng") cho front-end CIDRAM và trang "Truy cập đã bị từ chối", có thể sử dụng các webfont vì lý do thẩm mỹ. Các webfont được vô hiệu hóa theo mặc định, nhưng khi được kích hoạt, giao tiếp trực tiếp giữa trình duyệt của người dùng và dịch vụ lưu trữ webfont sẽ xảy ra. Điều này có thể liên quan đến việc truyền thông tin như địa chỉ IP của người dùng, đại lý người dùng, hệ điều hành, và các chi tiết khác có sẵn cho yêu cầu. Hầu hết các webfont này được lưu trữ bởi dịch vụ [Google Fonts](https://fonts.google.com/).

*Chỉ thị cấu hình có liên quan:*
- `general` -> `disable_webfonts`

##### 11.2.2 XÁC MINH MÁY TÌM KIẾM

Khi xác minh máy tìm kiếm được kích hoạt, CIDRAM cố gắng thực hiện "tra cứu DNS chuyển tiếp" để xác minh tính xác thực của các yêu cầu nói rằng bắt nguồn từ các máy tìm kiếm. Để thực hiện điều này, nó sử dụng dịch vụ [Google DNS](https://dns.google.com/) để cố gắng giải quyết các địa chỉ IP từ tên máy chủ của các yêu cầu gửi đến này (trong quá trình này, tên máy chủ của các yêu cầu gửi đến này được chia sẻ với dịch vụ).

*Chỉ thị cấu hình có liên quan:*
- `general` -> `search_engine_verification`

##### 11.2.3 GOOGLE reCAPTCHA

CIDRAM hỗ trợ tùy chọn [Google reCAPTCHA](https://www.google.com/recaptcha/), cung cấp phương tiện để người dùng bỏ qua trang "Truy cập đã bị từ chối" bằng cách hoàn thành reCAPTCHA (thông tin thêm về tính năng này được mô tả trước đó trong tài liệu, đáng chú ý nhất trong phần cấu hình). Google reCAPTCHA cần các khóa API để hoạt động chính xác và do đó bị vô hiệu hóa theo mặc định. Nó có thể được kích hoạt bằng cách xác định các khóa API cần thiết trong cấu hình gói. Khi được kích hoạt, giao tiếp trực tiếp giữa trình duyệt của người dùng và dịch vụ reCAPTCHA xảy ra. Điều này có thể liên quan đến việc truyền thông tin như địa chỉ IP của người dùng, đại lý người dùng, hệ điều hành và các chi tiết khác có sẵn cho yêu cầu. Địa chỉ IP của người dùng cũng có thể được chia sẻ trong giao tiếp giữa CIDRAM và dịch vụ reCAPTCHA khi xác minh tính hợp lệ của một cá thể reCAPTCHA và xác minh xem nó có được hoàn tất thành công hay không.

*Chỉ thị cấu hình có liên quan: Mọi thứ được liệt kê trong danh mục cấu hình "recaptcha".*

##### 11.2.4 STOP FORUM SPAM

[Stop Forum Spam](https://www.stopforumspam.com/) là một dịch vụ tuyệt vời, miễn phí có sẵn có thể giúp bảo vệ diễn đàn, blog và trang web từ chương trình thư rác. Nó thực hiện điều này bằng cách cung cấp một cơ sở dữ liệu của chương trình và những người gửi thư rác đã biết, và một API có thể được tận dụng để kiểm tra xem địa chỉ IP, tên người dùng hay địa chỉ email có được liệt kê trên cơ sở dữ liệu của nó hay không.

CIDRAM cung cấp một mô-đun tùy chọn tận dụng API này để kiểm tra xem địa chỉ IP của các yêu cầu gửi đến thuộc về một chương trình và những người gửi thư rác bị nghi ngờ hay không. Mô-đun không được cài đặt theo mặc định, nhưng nếu bạn chọn cài đặt nó, địa chỉ IP của người dùng có thể được chia sẻ với API của Stop Forum Spam theo đúng mục đích của mô-đun. Khi mô-đun được cài đặt, CIDRAM giao tiếp với API này bất cứ khi nào một yêu cầu gửi đến yêu cầu một nguồn tài nguyên mà CIDRAM nhận ra là một loại tài nguyên thường xuyên được nhắm mục tiêu bởi chương trình và những người gửi thư rác (ví dụ, trang đăng nhập, trang đăng ký, trang xác minh email, biểu mẫu nhận xét, vv).

#### 11.3 NHẬT KÝ

Nhật ký là một phần quan trọng của CIDRAM vì một số lý do. có thể khó để chẩn đoán và giải quyết các kết quả sai tích cực khi các sự kiện chặn khiến chúng không được ghi lại. Khi các sự kiện chặn không được ghi lại, có thể khó để xác định chính xác CIDRAM hoạt động tốt như thế nào trong bất kỳ ngữ cảnh cụ thể nào, và có thể khó để xác định nơi bất cập của nó, và những thay đổi nào có thể cần thiết đối với cấu hình hay chữ ký của nó, để nó có thể tiếp tục hoạt động như dự định. Bất kể, nhật ký có thể không được mong muốn cho tất cả người dùng, và vẫn hoàn toàn tùy chọn. Trong CIDRAM, ghi nhật ký bị vô hiệu hóa theo mặc định. Để kích hoạt nó, CIDRAM phải được cấu hình cho phù hợp.

Ngoài ra, việc nhật ký có được cho phép hợp pháp hay không, và trong phạm vi được cho phép hợp pháp (ví dụ, các loại thông tin có thể được nhật ký, bao lâu, và trong hoàn cảnh gì), có thể thay đổi, tùy thuộc vào thẩm quyền pháp lý và trong bối cảnh CIDRAM được triển khai (ví dụ, nếu bạn đang hoạt động như một cá nhân, như một thực thể công ty, và nếu trên cơ sở thương mại hay phi thương mại). Do đó, nó có thể hữu ích cho bạn để đọc kỹ phần này.

Có nhiều kiểu ghi nhật ký mà CIDRAM có thể thực hiện. Các loại ghi nhật ký khác nhau liên quan đến các loại thông tin khác nhau, vì các lý do khác nhau.

##### 11.3.0 SỰ KIỆN CHẶN

Loại nhật ký chính mà CIDRAM có thể thực hiện liên quan đến "sự kiện chặn". Loại nhật ký này liên quan đến khi CIDRAM chặn một yêu cầu, và có thể được cung cấp theo ba định dạng khác nhau:
- Tập tin nhật ký mà có thể được đọc bởi con người.
- Tập tin nhật ký trong kiểu Apache.
- Tập tin nhật ký được tuần tự hóa.

Sự kiện khối, được ghi vào tập tin nhật ký mà có thể được đọc bởi con người, thường trông giống như sau (ví dụ):

```
ID: 1234
Phiên bản kịch bản: CIDRAM v1.6.0
Ngày/Thời gian: Day, dd Mon 20xx hh:ii:ss +0000
Địa chỉ IP: x.x.x.x
Tên máy chủ: dns.hostname.tld
Số lượng chữ ký: 1
Tham khảo cho chữ ký: x.x.x.x/xx
Tại sao bị chặn: Dịch vụ điện toán đám mây ("Tên mạng", Lxx:Fx, [XX])!
Đại lý người dùng: Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.181 Safari/537.36
URI được xây dựng lại: http://your-site.tld/index.php
Tình trạng reCAPTCHA: Trên.
```

Cùng một sự kiện khối, được ghi vào tập tin nhật ký trong kiểu Apache, sẽ trông giống như sau:

```
x.x.x.x - - [Day, dd Mon 20xx hh:ii:ss +0000] "GET /index.php HTTP/1.1" 200 xxxx "-" "Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.181 Safari/537.36"
```

Sự kiện khối đã nhật ký thường bao gồm thông tin sau:
- Một số ID tham chiếu đến sự kiện khối.
- Phiên bản CIDRAM hiện đang được sử dụng.
- Ngày và giờ xảy ra sự kiện chặn.
- Địa chỉ IP của yêu cầu bị chặn.
- Tên máy chủ của địa chỉ IP của yêu cầu bị chặn (nếu có).
- Số lượng chữ ký được kích hoạt bởi yêu cầu.
- Tham chiếu đến chữ ký được kích hoạt.
- Tham khảo các lý do cho sự kiện khối và một số thông tin gỡ rối cơ bản liên quan.
- Đại lý người dùng của yêu cầu bị chặn (danh tính của thực thể yêu cầu).
- Việc xây dựng lại số nhận dạng cho tài nguyên được yêu cầu ban đầu.
- Trạng thái reCAPTCHA cho yêu cầu hiện tại (khi có liên quan).

Các chỉ thị cấu hình chịu trách nhiệm về loại ghi nhật ký này và cho mỗi một trong ba định dạng có sẵn:
- `general` -> `logfile`
- `general` -> `logfileApache`
- `general` -> `logfileSerialized`

Khi các chỉ thị này được để trống, loại ghi nhật ký này sẽ vẫn bị vô hiệu hóa.

##### 11.3.1 NHẬT KÝ reCAPTCHA

Loại nhật ký này liên quan cụ thể đến reCAPTCHA, và chỉ xảy ra khi người dùng cố gắng hoàn thành reCAPTCHA.

Mục nhập nhật ký reCAPTCHA chứa địa chỉ IP của người dùng đang cố gắng hoàn thành reCAPTCHA, ngày và giờ xảy ra sự cố, và trạng thái của reCAPTCHA. Mục nhập nhật ký reCAPTCHA thường trông giống như sau (ví dụ):

```
Địa chỉ IP: x.x.x.x - Ngày/Thời gian: Day, dd Mon 20xx hh:ii:ss +0000 - Tình trạng reCAPTCHA: Thành công!
```

Chỉ thị cấu hình chịu trách nhiệm cho nhật ký reCAPTCHA là:
- `recaptcha` -> `logfile`

##### 11.3.2 NHẬT KÝ FRONT-END

Loại nhật ký này liên quan đến cố gắng đăng nhập, và chỉ xảy ra khi người dùng cố gắng đăng nhập vào front-end (giả sử truy cập front-end được kích hoạt).

Mục nhập nhật ký front-end chứa địa chỉ IP của người dùng đang cố gắng đăng nhập, ngày và giờ xảy ra điều này, và kết quả của cố gắng này (đăng nhập thành công, hoặc thành công không thành công). Mục nhập nhật ký front-end thường trông giống như thế này (làm ví dụ):

```
x.x.x.x - Day, dd Mon 20xx hh:ii:ss +0000 - "admin" - Được đăng nhập.
```

Chỉ thị cấu hình chịu trách nhiệm cho nhật ký front-end là:
- `general` -> `FrontEndLog`

##### 11.3.3 XOAY VÒNG NHẬT KÝ

Bạn có thể muốn thanh lọc nhật ký sau một khoảng thời gian, hoặc có thể được yêu cầu làm như vậy theo luật pháp (khoảng thời gian được phép giữ nhật ký hợp pháp có thể bị giới hạn bởi luật pháp). Bạn có thể đạt được điều này bằng cách đưa dấu ngày/giờ vào tên tập tin nhật ký của bạn theo quy định của cấu hình gói của bạn (ví dụ, `{yyyy}-{mm}-{dd}.log`), và sau đó kích hoạt xoay vòng nhật ký (xoay vòng nhật ký cho phép bạn thực hiện một số hành động trên tập tin nhật ký khi vượt quá giới hạn được chỉ định).

Ví dụ: Nếu tôi được yêu cầu xóa nhật ký sau 30 ngày theo pháp luật, tôi có thể chỉ định `{dd}.log` trong tên của tập tin nhật ký của tôi (`{dd}` đại diện cho ngày), đặt giá trị của `log_rotation_limit` để 30, và đặt giá trị của `log_rotation_action` để `Delete`.

Ngược lại, nếu bạn được yêu cầu giữ lại nhật ký trong một khoảng thời gian dài, bạn có thể cân nhắc không sử dụng xoay vòng nhật ký, hoặc bạn có thể đặt giá trị của `log_rotation_action` để `Archive`, để nén tập tin nhật ký, do đó làm giảm tổng dung lượng đĩa mà họ chiếm.

*Chỉ thị cấu hình có liên quan:*
- `general` -> `log_rotation_limit`
- `general` -> `log_rotation_action`

##### 11.3.4 CẮT NGẮN NHẬT KÝ

Cũng có thể cắt ngắn các tập tin nhật ký riêng lẻ khi chúng vượt quá một kích thước nhất định, nếu đây bạn có thể cần hay muốn làm.

*Chỉ thị cấu hình có liên quan:*
- `general` -> `truncate`

##### 11.3.5 PSEUDONYMISATION ĐỊA CHỈ IP

Thứ nhất, nếu bạn không quen thuộc với thuật ngữ này, "pseudonymisation" đề cập đến việc xử lý dữ liệu cá nhân sao cho không thể xác định được dữ liệu cá nhân cho bất kỳ chủ đề dữ liệu cụ thể nào nữa trừ khi có thông tin bổ sung, và miễn là thông tin bổ sung đó được duy trì riêng biệt và phải chịu sự các biện pháp kỹ thuật và tổ chức để đảm bảo rằng dữ liệu cá nhân không thể được xác định cho bất kỳ người tự nhiên nào.

Trong một số trường hợp, bạn có thể được yêu cầu về mặt pháp lý để sử dụng "anonymisation" hoặc "pseudonymisation" cho bất kỳ PII nào được thu thập, xử lý hoặc lưu trữ. Mặc dù khái niệm này đã tồn tại trong một thời gian khá lâu, GDPR/DSGVO đề cập đến, và đặc biệt khuyến khích "pseudonymisation".

CIDRAM có thể sử dụng "pseudonymisation" cho các địa chỉ IP khi nhật ký chúng vào bản ghi, nếu đây bạn có thể cần hay muốn làm. Khi CIDRAM sử dụng "pseudonymisation" cho các địa chỉ IP, khi nhật ký chúng vào bản ghi, octet cuối cùng của địa chỉ IPv4, và mọi thứ sau phần thứ hai của địa chỉ IPv6 được đại diện bởi một "x" (hiệu quả làm tròn địa chỉ IPv4 đến địa chỉ đầu tiên của mạng con thứ 24 mà chúng đưa vào, và địa chỉ IPv6 đến địa chỉ đầu tiên của mạng con thứ 32 mà chúng đưa vào).

*Chú thích: Quá trình pseudonymisation địa chỉ IP của CIDRAM không ảnh hưởng đến tính năng giám sát IP của CIDRAM. Nếu đây là vấn đề với bạn, có thể tốt nhất là tắt giám sát IP hoàn toàn. Điều này có thể đạt được bằng cách đặt `track_mode` để `false` và bằng cách tránh bất kỳ mô-đun nào.*

*Chỉ thị cấu hình có liên quan:*
- `signatures` -> `track_mode`
- `legal` -> `pseudonymise_ip_addresses`

##### 11.3.6 BỎ QUA THÔNG TIN NHẬT KÝ

Nếu bạn muốn tiến thêm một bước nữa bằng cách ngăn chặn các loại thông tin cụ thể được ghi lại hoàn toàn, điều này cũng có thể thực hiện được. CIDRAM cung cấp chỉ thị cấu hình để kiểm soát xem địa chỉ IP, tên máy chủ, và đại lý người dùng có được bao gồm trong nhật ký hay không. Theo mặc định, tất cả ba trong số các điểm dữ liệu này được bao gồm trong nhật ký khi có sẵn. Việc đặt bất kỳ chỉ thị cấu hình nào thành `true` sẽ bỏ qua thông tin tương ứng từ nhật ký.

*Chú thích: Không có lý do gì để bút danh các địa chỉ IP khi bỏ qua chúng hoàn toàn từ các nhật ký.*

*Chỉ thị cấu hình có liên quan:*
- `legal` -> `omit_ip`
- `legal` -> `omit_hostname`
- `legal` -> `omit_ua`

##### 11.3.7 SỐ LIỆU THỐNG KÊ

CIDRAM có thể tùy chọn theo dõi số liệu thống kê như tổng số sự kiện chặn hay sự xuất hiện reCAPTCHA đã xảy ra kể từ một số thời điểm cụ thể. Tính năng này được vô hiệu hóa theo mặc định, nhưng có thể được kích hoạt thông qua cấu hình gói. Tính năng này chỉ theo dõi tổng số sự kiện đã xảy ra và không bao gồm bất kỳ thông tin nào về các sự kiện cụ thể (và do đó, không nên được coi là PII).

*Chỉ thị cấu hình có liên quan:*
- `general` -> `statistics`

##### 11.3.8 MÃ HÓA

CIDRAM không mã hóa bộ nhớ cache của nó hoặc bất kỳ thông tin log nào. [Mã hóa](https://vi.wikipedia.org/wiki/M%C3%A3_h%C3%B3a) bộ nhớ cache và log có thể được giới thiệu trong tương lai, nhưng hiện tại không có bất kỳ kế hoạch cụ thể nào. Nếu bạn lo lắng về các bên thứ ba không được phép truy cập vào các phần của CIDRAM có thể chứa thông tin nhận dạng cá nhân hay thông tin nhạy cảm như bộ nhớ cache hoặc nhật ký của nó, tôi khuyên bạn không nên cài đặt CIDRAM tại vị trí có thể truy cập công khai (ví dụ, cài đặt CIDRAM bên ngoài thư mục `public_html` tiêu chuẩn hoặc tương đương chúng có sẵn cho hầu hết các máy chủ web tiêu chuẩn) và các quyền hạn chế thích hợp sẽ được thực thi cho thư mục nơi nó cư trú (đặc biệt, cho thư mục vault). Nếu điều đó không đủ để giải quyết mối quan ngại của bạn, hãy định cấu hình CIDRAM để các loại thông tin gây ra mối lo ngại của bạn sẽ không được thu thập hoặc nhật ký ở địa điểm đầu tiên (ví dụ, bằng cách tắt ghi nhật ký).

#### 11.4 COOKIE

CIDRAM đặt [cookie](https://vi.wikipedia.org/wiki/Cookie_(tin_h%E1%BB%8Dc)) ở hai điểm trong cơ sở mã của nó. Thứ nhất, khi người dùng hoàn tất thành công sự xuất hiện reCAPTCHA (và giả định rằng `lockuser` được đặt thành `true`), CIDRAM đặt cookie để có thể ghi nhớ các yêu cầu tiếp theo mà người dùng đã hoàn sự xuất hiện reCAPTCHA, vì vậy để không cần liên tục yêu cầu người dùng hoàn thành một sự xuất hiện reCAPTCHA trên mỗi yêu cầu tiếp theo. Thứ hai, khi người dùng đăng nhập thành công vào front-end, CIDRAM đặt cookie để có thể nhớ người dùng cho các yêu cầu tiếp theo (cookie được sử dụng để xác thực người dùng đến phiên đăng nhập).

Trong cả hai trường hợp, cảnh báo cookie được hiển thị nổi bật (khi nó có liên quan), cảnh báo người dùng rằng cookie sẽ được đặt nếu họ tham gia vào các hành động có liên quan. Cookie không được đặt ở bất kỳ điểm nào khác trong cơ sở mã.

*Chú thích: Việc triển khai API "invisible" cụ thể của CIDRAM cho reCAPTCHA có thể không tương thích với luật cookie ở một số khu vực pháp lý, và phải được tránh bởi bất kỳ trang web nào tuân theo các luật đó. Thay vào đó, việc chọn sử dụng API "V2", hoặc chỉ đơn giản là tắt hoàn toàn reCAPTCHA, có thể thích hợp hơn.*

*Chỉ thị cấu hình có liên quan:*
- `general` -> `disable_frontend`
- `recaptcha` -> `lockuser`
- `recaptcha` -> `api`

#### 11.5 TIẾP THỊ VÀ QUẢNG CÁO

CIDRAM không thu thập hoặc xử lý bất kỳ thông tin nào cho mục đích tiếp thị hoặc quảng cáo, và không bán hoặc lợi nhuận từ bất kỳ thông tin được thu thập hoặc ghi lại nào. CIDRAM không phải là một doanh nghiệp thương mại, cũng không liên quan đến bất kỳ lợi ích thương mại nào, do đó, làm những việc này sẽ không có ý nghĩa gì cả. Đây là trường hợp kể từ khi bắt đầu dự án, và tiếp tục là trường hợp ngày hôm nay. Ngoài ra, làm những việc này sẽ phản tác dụng với tinh thần và mục đích dự định của toàn bộ dự án, và miễn là tôi tiếp tục duy trì dự án, sẽ không bao giờ xảy ra.

#### 11.6 CHÍNH SÁCH BẢO MẬT

Trong một số trường hợp, bạn có thể được yêu cầu về mặt pháp lý để hiển thị rõ ràng liên kết đến chính sách bảo mật của bạn trên tất cả các trang và phần trong trang web của bạn. Điều này có thể quan trọng như một phương tiện để đảm bảo rằng người dùng được thông báo đầy đủ về các thực tiễn bảo mật chính xác của bạn, loại PII bạn thu thập, và cách bạn định sử dụng. Để có thể bao gồm một liên kết trên trang "Truy cập đã bị từ chối" của CIDRAM, một chỉ thị cấu hình được cung cấp để chỉ định URL cho chính sách bảo mật của bạn.

*Chú thích: Tôi khuyên bạn không nên đặt trang chính sách bảo mật của bạn sau bảo vệ của CIDRAM. Nếu CIDRAM bảo vệ trang chính sách bảo mật của bạn, và người dùng bị chặn bởi CIDRAM nhấp vào liên kết đến chính sách bảo mật của bạn, chúng sẽ chỉ bị chặn lại, và sẽ không thể xem chính sách bảo mật của bạn. Lý tưởng nhất, bạn nên liên kết đến một bản sao tĩnh của chính sách bảo mật của bạn, chẳng hạn như một trang HTML hoặc tập tin văn bản thuần mà không được bảo vệ bởi CIDRAM.*

*Chỉ thị cấu hình có liên quan:*
- `legal` -> `privacy_policy`

#### 11.7 GDPR/DSGVO

Quy định bảo vệ dữ liệu chung (GDPR) là một quy định của Liên minh châu Âu, có hiệu lực kể từ 25 Tháng Năm 2018. Mục tiêu chính của quy định là cung cấp quyền kiểm soát cho công dân và cư dân EU về dữ liệu cá nhân của riêng họ, và thống nhất quy định trong EU về quyền riêng tư và dữ liệu cá nhân.

Quy định này bao gồm các điều khoản cụ thể liên quan đến việc xử lý "thông tin nhận dạng cá nhân" (PII) của bất kỳ "chủ đề dữ liệu" nào (bất kỳ người tự nhiên được xác định hoặc có thể nhận dạng được) từ hoặc trong EU. Để tuân thủ quy định, "enterprise" hoặc "doanh nghiệp" (theo quy định của quy định), và bất kỳ hệ thống và quy trình có liên quan nào phải ghi nhớ sự riêng tư ngay từ đầu, phải sử dụng cài đặt bảo mật cao nhất có thể, phải thực hiện các biện pháp bảo vệ thích hợp cho bất kỳ thông tin được lưu trữ hay xử lý nào (bao gồm nhưng không giới hạn trong việc thực hiện "pseudonymisation" hoặc "anonymisation" đầy đủ của dữ liệu), phải khai báo rõ ràng các loại dữ liệu mà họ thu thập, cách họ xử lý nó, vì lý do gì, trong bao lâu họ giữ nó, và nếu họ chia sẻ dữ liệu này với bất kỳ bên thứ ba nào, các loại dữ liệu được chia sẻ với bên thứ ba, cách, tại sao, vv.

Dữ liệu có thể không được xử lý trừ khi có cơ sở hợp pháp để làm như vậy, theo quy định của quy định. Nói chung, điều này có nghĩa là để xử lý dữ liệu của chủ đề dữ liệu trên cơ sở hợp pháp, nó phải được thực hiện theo nghĩa vụ pháp lý, hoặc chỉ được thực hiện sau khi có sự đồng ý rõ ràng và đầy đủ thông tin đã được lấy từ chủ đề dữ liệu.

Bởi vì các khía cạnh của quy định có thể phát triển trong thời gian, để tránh việc truyền bá thông tin lỗi thời, nó có thể là tốt hơn để tìm hiểu về các quy định từ một nguồn có thẩm quyền, trái ngược với việc chỉ bao gồm các thông tin có liên quan ở đây trong tài liệu gói (mà cuối cùng có thể trở nên lỗi thời khi quy định phát triển).

Một số tài nguyên được đề xuất để tìm hiểu thêm thông tin:
- [REGULATION (EU) 2016/679 OF THE EUROPEAN PARLIAMENT AND OF THE COUNCIL](https://eur-lex.europa.eu/legal-content/EN/TXT/?uri=celex:32016R0679)
- [Quy định bảo vệ dữ liệu chung](https://vi.wikipedia.org/wiki/Quy_%C4%91%E1%BB%8Bnh_b%E1%BA%A3o_v%E1%BB%87_d%E1%BB%AF_li%E1%BB%87u_chung)

---


Lần cuối cập nhật: 14 Tháng Bảy 2018 (2018.07.14).
