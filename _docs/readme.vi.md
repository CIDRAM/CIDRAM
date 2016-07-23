## Tài liệu của CIDRAM (Tiếng Việt).

### Nội dung
- 1. [LỜI GIỚI THIỆU](#SECTION1)
- 2. [CÁCH CÀI ĐẶT](#SECTION2)
- 3. [CÁCH SỬ DỤNG](#SECTION3)
- 4. [TẬP TIN BAO GỒM TRONG GÓI NÀY](#SECTION4)
- 5. [TÙY CHỌN CHO CẤU HÌNH](#SECTION5)
- 6. [ĐỊNH DẠNG CỦA CHỬ KÝ](#SECTION6)

---


###1. <a name="SECTION1"></a>LỜI GIỚI THIỆU

CIDRAM (Classless Inter-Domain Routing Access Manager) là một kịch bản PHP được thiết kế để bảo vệ các trang web bằng cách ngăn chặn các yêu cầu có nguồn gốc từ các địa chỉ IP coi như là nguồn của lưu lượng không mong muốn, bao gồm (nhưng không giới hạn) giao thông từ thiết bị đầu cuối truy cập không phải con người, dịch vụ điện toán đám mây, chương trình gửi thư rác, công cụ cào, vv. Nó làm điều này bằng cách tính toán CIDR có thể các địa chỉ IP cung cấp từ các yêu cầu gửi đến và sau đó cố gắng để phù hợp với những CIDR có thể chống lại các tập tin chữ ký của nó (các tập tin chữ ký chứa danh sách các CIDR các địa chỉ IP coi như là nguồn của lưu lượng không mong muốn); Nếu trận đấu được tìm thấy, các yêu cầu được chặn.

BẢN QUYỀN CIDRAM 2016 và hơn GNU/GPLv2 by Caleb M (Maikuolan).

Bản này là chương trình miễn phí; bạn có thể phân phối lại hoạc sửa đổi dưới điều kiện của GNU Giấy Phép Công Cộng xuất bản bởi Free Software Foundation; một trong giấy phép phần hai, hoạc (tùy theo sự lựa chọn của bạn) bất kỳ phiên bản nào sau này. Bản này được phân phối với hy vọng rằng nó sẽ có hữu ích, nhưng mà KHÔNG CÓ BẢO HÀNH; ngay cả những bảo đảm ngụ ý KHẢ NĂNG BÁN HÀNG hoạc PHÙ HỢP VỚI MỤC ĐÍT VÀO. Hảy xem GNU Giấy Phép Công Cộng để biết them chi tiết, nằm trong tập tin `LICENSE.txt`, và kho chứa của tập tin này có thể tiềm đước tại:
- <http://www.gnu.org/licenses/>.
- <http://opensource.org/licenses/>.

Tài liệu này và các gói liên quan của nó có thể được tải về miễn phí từ [GitHub](https://github.com/Maikuolan/CIDRAM/).

---


###2. <a name="SECTION2"></a>CÁCH CÀI ĐẶT

Tôi hy vọng sẽ giản hóa quá trình này bằng cách thực hiện một cài đặt tại một thời điểm nào trong tương lai không quá xa, nhưng cho đến lúc đó, bạn hảy làm theo hướng dẫn để có thể cho CIDRAM làm việc trên hầu hết các hệ thống và CMS:

1) Nếu bạn đang đọc cái này thì tôi hy vọng là bạn đã tải về một bản sao kho lưu trữ của bản, giải nén nội dung của nó và nó đang nằm ở một nơi nào đó trên máy tính của bạn. Từ đây, bạn sẽ muốn đặt nội dung ở một nơi trên máy chủ hoặc CMS của bạn. Một thư mục chẳng hạn như `/public_html/cidram/` hay tương tự (mặc dù sự lựa chọn của bạn không quan trọng, miễn là nó an toàn và bạn hài lòng với sự lựa chọn) sẽ đủ.. *Trước khi bạn bắt đầu tải lên, hảy tiếp tục đọc..*

2) Đổi tên `config.ini.RenameMe` đến `config.ini` (nằm bên trong `vault`), và nếu bạn muốn (đề nghị mạnh mẽ cho người dùng cao cấp, nhưng không đề nghị cho người mới bắt đầu hay cho người thiếu kinh nghiệm), mở nó (tập tin này bao gồm tất cả các tùy chọn có sẵn cho CIDRAM; trên mỗi tùy chọn nên có một nhận xét ngắn gọn mô tả những gì nó làm và những gì nó cho). Điều chỉnh các tùy chọn như bạn thấy phù hợp, theo bất cứ điều gì là thích hợp cho tập hợp cụ thể của bạn lên. Lưu tập tin, đóng.

3) Tải nội dung lên (CIDRAM và tập tin của nó) vào thư mục bạn đã chọn trước (bạn không cần phải dùng tập tin `*.txt`/`*.md`, nhưng chủ yếu, bạn nên tải lên tất cả mọi thứ).

4) CHMOD thư mục `vault` thành "777". Các thư mục chính kho lưu trữ các nội dung (một trong những cái bạn đã chọn trước), bình thường, có thể riêng, nhưng tình hình CHMOD nên kiểm tra, nếu bạn đã có vấn đề cho phép trong quá khứ về hệ thống của bạn (theo mặc định, nên giống như "755").

5) Tiếp theo, bạn sẽ cần "nối" CIDRAM vào hệ thống của bạn hay CMS. Có một số cách mà bạn có thể "nối" bản chẳng hạn như CIDRAM vào hệ thống hoạc CMS, nhưng cách đơn giản nhất là cần có bản vào cốt lõi ở đầu của tập tin hoạc hệ thống hay CMS của bạn (một mà thường sẽ luôn luôn được nạp khi ai đó truy cập bất kỳ trang nào trên trang web của bạn) bằng cách sử dụng một lời chỉ thị `require` hoạc `include`. Thường, cái nàu sẽ được lưu trong một thư mục như `/includes`, `/assets` hoạc `/functions`, và sẽ thường được gọi là `init.php`, `common_functions.php`, `functions.php` hoạc tương tự. Bạn sẽ cần tiềm ra tập tin nào cho trường hợp của bạn; Nếu bạn gặp khó khăn trong việc này ra cho chính mình, thăm các trang issues (vấn đề) cho CIDRAM trên GitHub. Để làm chuyện này [sử dụng `require` họac `include`], đánh các dòng mã sao đây vào đầu của cốt lõi của tập tin, thay thế các dây chứa bên trong các dấu ngoặc kép với địa chỉ chính xác của tập tin `CIDRAM.php` (địa chỉ địa phương, chứ không phải địa chỉ HTTP; nó sẽ nhình gióng địa chỉ kho nói ở trên).

`<?php require '/user_name/public_html/cidram/loader.php'; ?>`

Lưu tập tin, đóng lại, tải lên lại.

-- CÁCH KHÁC --

Nếu bạn đang sử dụng trang chủ Apache và nếu bạn có thể truy cập `php.ini`, bạn có thể sử dụng `auto_prepend_file` chỉ thị để thêm vào trước CIDRAM bất cứ khi nào bất kỳ yêu cầu PHP được xin. Gióng như:

`auto_prepend_file = "/user_name/public_html/cidram/loader.php"`

Hoạc cái này trong tập tin `.htaccess`:

`php_value auto_prepend_file "/user_name/public_html/cidram/loader.php"`

6) Đó là tất cả mọi thứ! :-)

---


###3. <a name="SECTION3"></a>CÁCH SỬ DỤNG

CIDRAM nên tự động chặn các yêu cầu không mong muốn để trang web của bạn mà không cần bất kỳ hỗ trợ bằng tay, trừ cài đặt.

Đang cập nhật được thực hiện bằng tay, và bạn có thể tùy chỉnh cấu hình của bạn và tùy chỉnh mà CIDR bị chặn bằng cách sửa đổi tập tin cấu hình hay tập tin chữ ký của bạn.

Nếu bạn gặp bất kỳ sai tích cực, xin vui lòng liên hệ với tôi để cho tôi biết về nó.

---


###4. <a name="SECTION4"></a>TẬP TIN BAO GỒM TRONG GÓI NÀY

Sau đây là một danh sách tất cả các tập tin mà cần phải có được bao gồm trong bản sao lưu của kịch bản này khi bạn tải về nó, cùng với một mô tả ngắn cho những gì tất cả những tập tin này là dành cho.

Tập tin | Chi tiết
----|----
/.gitattributes | Tập tin dự án cho GitHub (không cần thiết cho chức năng phù hợp của kịch bản).
/Changelog.txt | Kỷ lục của những sự thay đổi được thực hiện cho các kịch bản khác nhau giữa các phiên bản (không cần thiết cho chức năng phù hợp của kịch bản).
/composer.json | Thông tin về dự án cho Composer/Packagist (không cần thiết cho chức năng phù hợp của kịch bản).
/LICENSE.txt | Bản sao của giấy phép GNU/GPLv2.
/loader.php | Tập tin cho tải. Đây là điều bạn cần nối vào (cần thiết)!
/README.md | Thông tin tóm tắt dự án.
/web.config | Tập tin cấu hình của ASP.NET (trong trường hợp này, để bảo vệ `/vault` thư mực khỏi bị truy cập bởi những nguồn không có quền trong trường hợp bản được cài trên serever chạy trên công nghệ ASP.NET).
/_docs/ | Thư mực cho tài liệu.
/_docs/readme.de.md | Tài liệu tiếng Đức.
/_docs/readme.en.md | Tài liệu tiếng Anh.
/_docs/readme.es.md | Tài liệu tiếng Tây Ban Nha.
/_docs/readme.fr.md | Tài liệu tiếng Pháp.
/_docs/readme.id.md | Tài liệu tiếng Indonesia.
/_docs/readme.it.md | Tài liệu tiếng Ý.
/_docs/readme.nl.md | Tài liệu tiếng Hà Lan.
/_docs/readme.pt.md | Tài liệu tiếng Bồ Đào Nha.
/_docs/readme.vi.md | Tài liệu tiếng Việt.
/_docs/readme.zh-TW.md | Tài liệu tiếng Trung Quốc (truyền thống).
/_docs/readme.zh.md | Tài liệu tiếng Trung Quốc (giản thể).
/vault/ | Vault thư mục (chứa các tập tin khác nhau).
/vault/.htaccess | Tập tin "hypertext access" / tập tin truy cập siêu văn bản (bảo vệ tập tin bí mật khỏi bị truy cập bởi nguồn không được ủy quyền).
/vault/cache.dat | Dữ liệu bộ nhớ cache.
/vault/cli.php | Tập tin cho xử lý CLI.
/vault/config.ini.RenameMe | Tập tin cho cấu hình; Chứa tất cả các tùy chọn cho cấu hình của CIDRAM, nói cho nó biết phải làm gì và làm thế nào để hoạt động (đổi tên để kích hoạt).
/vault/config.php | Tập tin cho xử lý cấu hình.
/vault/functions.php | Tập tin cho chức năng.
/vault/ipv4.dat | Tập tin chữ ký IPv4.
/vault/ipv4_custom.dat.RenameMe | Tập tin chữ ký IPv4 tùy chỉnh (đổi tên để kích hoạt).
/vault/ipv6.dat | Tập tin chữ ký IPv6.
/vault/ipv6_custom.dat.RenameMe | Tập tin chữ ký IPv6 tùy chỉnh (đổi tên để kích hoạt).
/vault/lang.php | Dữ liệu tiếng.
/vault/lang/ | Chứa dữ liệu tiếng cho CIDRAM.
/vault/lang/.htaccess | Tập tin "hypertext access" / tập tin truy cập siêu văn bản (bảo vệ tập tin bí mật khỏi bị truy cập bởi nguồn không được ủy quyền).
/vault/lang/lang.ar.cli.php | Dữ liệu tiếng Ả Rập cho CLI.
/vault/lang/lang.ar.php | Dữ liệu tiếng Ả Rập.
/vault/lang/lang.de.cli.php | Dữ liệu tiếng Đức cho CLI.
/vault/lang/lang.de.php | Dữ liệu tiếng Đức.
/vault/lang/lang.en.cli.php | Dữ liệu tiếng Anh cho CLI.
/vault/lang/lang.en.php | Dữ liệu tiếng Anh.
/vault/lang/lang.es.cli.php | Dữ liệu tiếng Tây Ban Nha cho CLI.
/vault/lang/lang.es.php | Dữ liệu tiếng Tây Ban Nha.
/vault/lang/lang.fr.cli.php | Dữ liệu tiếng Pháp cho CLI.
/vault/lang/lang.fr.php | Dữ liệu tiếng Pháp.
/vault/lang/lang.id.cli.php | Dữ liệu tiếng Indonesia cho CLI.
/vault/lang/lang.id.php | Dữ liệu tiếng Indonesia.
/vault/lang/lang.it.cli.php | Dữ liệu tiếng Ý cho CLI.
/vault/lang/lang.it.php | Dữ liệu tiếng Ý.
/vault/lang/lang.ja.cli.php | Dữ liệu tiếng Nhật cho CLI.
/vault/lang/lang.ja.php | Dữ liệu tiếng Nhật.
/vault/lang/lang.nl.cli.php | Dữ liệu tiếng Hà Lan cho CLI.
/vault/lang/lang.nl.php | Dữ liệu tiếng Hà Lan.
/vault/lang/lang.pt.cli.php | Dữ liệu tiếng Bồ Đào Nha cho CLI.
/vault/lang/lang.pt.php | Dữ liệu tiếng Bồ Đào Nha.
/vault/lang/lang.ru.cli.php | Dữ liệu tiếng Nga cho CLI.
/vault/lang/lang.ru.php | Dữ liệu tiếng Nga.
/vault/lang/lang.vi.cli.php | Dữ liệu tiếng Việt cho CLI.
/vault/lang/lang.vi.php | Dữ liệu tiếng Việt.
/vault/lang/lang.zh-tw.cli.php | Dữ liệu tiếng Trung Quốc (truyền thống) cho CLI.
/vault/lang/lang.zh-TW.php | Dữ liệu tiếng Trung Quốc (truyền thống).
/vault/lang/lang.zh.cli.php | Dữ liệu tiếng Trung Quốc (giản thể) cho CLI.
/vault/lang/lang.zh.php | Dữ liệu tiếng Trung Quốc (giản thể).
/vault/outgen.php | Máy phát đầu ra.
/vault/template.html | Tập tin mẫu; Mẫu cho HTML sản xuất bởi các máy phát đầu ra của CIDRAM.
/vault/template_custom.html | Tập tin mẫu; Mẫu cho HTML sản xuất bởi các máy phát đầu ra của CIDRAM.
/vault/rules_as6939.php | Tập tin quy tắc tùy chỉnh cho AS6939.
/vault/rules_softlayer.php | Tập tin quy tắc tùy chỉnh cho Soft Layer.
/vault/rules_specific.php | Tập tin quy tắc tùy chỉnh cho một số CIDR cụ thể.

---


###5. <a name="SECTION5"></a>TÙY CHỌN CHO CẤU HÌNH
Sau đây là danh sách các biến tìm thấy trong tập tin cấu hình cho CIDRAM `config.ini`, cùng với một mô tả về mục đích và chức năng của chúng.

####"general" (Thể loại)
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

"timeOffset"
- Nếu thời gian máy chủ của bạn không phù hợp với thời gian địa phương của bạn, bạn có thể chỉ định một bù đắp đây để điều chỉnh thông tin ngày/giờ được tạo ra bởi CIDRAM theo yêu cầu của bạn. Nó thường được đề nghị thay vì để điều chỉnh các chỉ thị múi giờ trong tập tin `php.ini` của bạn, nhưng đôi khi (như ví dụ, khi làm việc với giới hạn cung cấp lưu trữ chia sẻ) đây không phải là luôn luôn có thể làm, và như vậy, tùy chọn này được cung cấp ở đây. Bù đắp được đo bằng phút.
- Ví dụ (để thêm một giờ): `timeOffset=60`

"ipaddr"
- Nơi để tìm địa chỉ IP của các yêu cầu kết nối? (Hữu ích cho các dịch vụ như CloudFlare và vv) Mặc định = REMOTE_ADDR. CẢNH BÁO: Không thay đổi này, trừ khi bạn biết những gì bạn đang làm!

"forbid_on_block"
- CIDRAM nên đáp lại với các header 403 để yêu cầu bị chặn, hoặc dính vào bình thường 200 OK? False/200 = Không (200) [Mặc định]; True = Vâng (403); 503 = Service unavailable / Dịch vụ không có sẵn (503).

"silent_mode"
- CIDRAM nên âm thầm chuyển hướng cố gắng truy cập bị chặn thay vì hiển thị trang "Truy cập bị từ chối"? Nếu vâng, xác định vị trí để chuyển hướng cố gắng truy cập bị chặn để. Nếu không, để cho biến này được trống.

"lang"
- Xác định tiếng mặc định cho CIDRAM.

"emailaddr"
- Nếu bạn muốn, bạn có thể cung cấp một địa chỉ email ở đây để được trao cho người dùng khi họ đang bị chặn, cho họ để sử dụng như một điểm tiếp xúc cho hỗ trợ hay giúp đở cho trong trường hợp họ bị chặn bởi nhầm hay lỗi. CẢNH BÁO: Bất kỳ địa chỉ email mà bạn cung cấp ở đây sẽ chắc chắn nhất được mua lại bởi chương trình thư rác và cái nạo trong quá trình con của nó được sử dụng ở đây, và như vậy, nó khuyên rằng nếu bạn chọn để cung cấp một địa chỉ email ở đây, mà bạn đảm bảo rằng địa chỉ email bạn cung cấp ở đây là một địa chỉ dùng một lần hay một địa chỉ mà bạn không nhớ được thư rác (nói cách khác, có thể bạn không muốn sử dụng một cá nhân chính hay kinh doanh chính địa chỉ email).

"disable_cli"
- Vô hiệu hóa chế độ CLI? Chế độ CLI được kích hoạt theo mặc định, nhưng đôi khi có thể gây trở ngại cho công cụ kiểm tra nhất định (như PHPUnit, cho ví dụ) và khác ứng dụng mà CLI dựa trên. Nếu bạn không cần phải vô hiệu hóa chế độ CLI, bạn nên bỏ qua tùy chọn này. False = Kích hoạt chế độ CLI [Mặc định]; True = Vô hiệu hóa chế độ CLI.

####"signatures" (Thể loại)
Cấu hình cho chữ ký.

"ipv4"
- Một danh sách các tập tin chữ ký IPv4 mà CIDRAM nên cố gắng để phân tích, ngăn cách bởi dấu phẩy. Bạn có thể thêm các mục ở đây nếu bạn muốn bao gồm thêm các tập tin chữ ký IPv4 trong CIDRAM.

"ipv6"
- Một danh sách các tập tin chữ ký IPv6 mà CIDRAM nên cố gắng để phân tích, ngăn cách bởi dấu phẩy. Bạn có thể thêm các mục ở đây nếu bạn muốn bao gồm thêm các tập tin chữ ký IPv6 trong CIDRAM.

"block_cloud"
- Chặn CIDR xác định là thuộc về các dịch vụ lưu trữ web hay dịch vụ điện toán đám mây? Nếu bạn điều hành một dịch vụ API từ trang web của bạn hay nếu bạn mong đợi các trang web khác để kết nối với trang web của bạn, điều này cần được thiết lập để false. Nếu bạn không, sau đó, tùy chọn này cần được thiết lập để true.

"block_bogons"
- Chặn CIDR bogon/martian? Nếu bạn mong đợi các kết nối đến trang web của bạn từ bên trong mạng nội bộ của bạn, từ localhost, hay từ LAN của bạn, tùy chọn này cần được thiết lập để false. Nếu bạn không mong đợi những kết nối như vậy, tùy chọn này cần được thiết lập để true.

"block_generic"
- Chặn CIDR thường được khuyến cáo cho danh sách đen? Điều này bao gồm bất kỳ chữ ký không được đánh dấu như một phần của bất kỳ các loại chữ ký cụ thể khác.

"block_proxies"
- Chặn CIDR xác định là thuộc về các dịch vụ proxy? Nếu bạn yêu cầu mà người dùng có thể truy cập trang web của bạn từ các dịch vụ proxy ẩn danh, điều này cần được thiết lập để false. Nếu không thì, nếu bạn không yêu cầu proxy vô danh, tùy chọn này cần được thiết lập để true như một phương tiện để cải thiện an ninh.

"block_spam"
- Chặn CIDR xác định như có nguy cơ cao đối được thư rác? Trừ khi bạn gặp vấn đề khi làm như vậy, nói chung, điều này cần phải luôn được true.

####"template_data" (Thể loại)
Cấu hình cho mẫu thiết kế và chủ đề.

Liên quan đến đầu ra HTML sử dụng để tạo ra các trang "Truy cập bị từ chối". Nếu bạn đang sử dụng chủ đề tùy chỉnh cho CIDRAM, đầu ra HTML có nguồn gốc từ tập tin `template_custom.html`, và nếu không thì, đầu ra HTML có nguồn gốc từ tập tin `template.html`. Biến bằng văn bản cho phần này của tập tin cấu hình được xử lý để đầu ra HTML bằng cách thay thế bất kỳ tên biến được bao quanh bởi các dấu ngoặc nhọn tìm thấy trong đầu ra HTML với các dữ liệu biến tương ứng. Ví dụ, ở đâu `foo="bar"`, bất kỳ trường hợp `<p>{foo}</p>` tìm thấy trong đầu ra HTML sẽ trở thành `<p>bar</p>`.

"css_url"
- Tập tin mẫu thiết kế cho chủ đề tùy chỉnh sử dụng thuộc tính CSS bên ngoài, trong khi các tập tin mẫu thiết kế cho các chủ đề mặc định sử dụng thuộc tính CSS nội bộ. Để hướng dẫn CIDRAM để sử dụng các tập tin mẫu thiết kế cho chủ đề tùy chỉnh, xác định các địa chỉ HTTP cho các tập tin CSS chủ đề tùy chỉnh của bạn sử dụng các biến số `css_url`. Nếu bạn để cho biến số này chỗ trống, CIDRAM sẽ sử dụng các tập tin mẫu thiết kế cho các chủ đề mặc định.

---


###6. <a name="SECTION6"></a>ĐỊNH DẠNG CỦA CHỬ KÝ

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

Các tập tin chữ ký cho CIDRAM NÊN sử dụng ngắt dòng trong phong cách Unix (`%0A`, hay `\n`)! Các loại / phong cách khác của ngắt dòng (ví dụ, Windows` %0D%0A` hay `\r\n`, Mac `%0D` hay `\r`, vv) CÓ THỂ được sử dụng, nhưng là KHÔNG ưa thích. Ngắt dòng không trong phong cách của Unix sẽ được bình thường như ngắt dòng trong phong cách của Unix bằng cách các kịch bản.

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

Nếu "Deny" được sử dụng, khi chữ ký được kích hoạt, giả sử không có chữ ký danh sách trắng đã được kích hoạt cho các địa chỉ IP hay CIDR thích hợp, truy cập vào các trang được bảo vệ sẽ bị từ chối. "Deny" là những gì bạn sẽ muốn sử dụng để thực sự ngăn chặn một địa chỉ IP hay phạm vi CIDR. Khi bất kỳ chữ ký được kích hoạt đó làm cho sử dụng của "Deny", trang "Truy cập bị từ chối" của các kịch bản sẽ được tạo ra và các yêu cầu đến trang bảo vệ sẽ bị giết.

Giá trị `[Param]` chấp nhận bởi "Deny" sẽ được phân tích để đầu ra trang "Truy cập bị từ chối", cung cấp cho các khách hàng / người dùng như các lý do được trích dẫn cho truy cập của họ vào trang yêu cầu được bị từ chối. Nó có thể là một câu ngắn và đơn giản, giải thích lý do tại sao bạn đã chọn để ngăn chặn chúng (bất cứ điều gì là đủ, cái gì đó như "Tôi không muốn bạn trên trang web của tôi" sẽ đủ), hay một trong một số ít các từ viết tắt được cung cấp bởi các kịch bản, mà nếu được sử dụng, sẽ được thay thế bởi các kịch bản với một lời giải thích chuẩn bị trước lý do tại sao khách hàng / người dùng đã bị chặn.

Những lời giải thích trước khi chuẩn bị có hỗ trợ i18n và có thể được dịch bởi kịch bản dựa trên ngôn ngữ mà bạn chỉ định đến tùy chọn `lang` của các cấu hình kịch bản. Ngoài ra, bạn có thể hướng dẫn các kịch bản để bỏ qua chữ ký "Deny" dựa trên giá trị `[Param]` của họ (nếu họ đang sử dụng những từ viết tắt) thông qua các tùy chọn định bởi cấu hình kịch bản (mỗi từ viết tắt có một tùy chọn tương ứng để xử lý chữ ký tương ứng hoặc bỏ qua chúng). Các giá trị `[Param]` mà không sử dụng những từ viết tắt, tuy nhiên, không có hỗ trợ i18n và do đó KHÔNG SẼ được dịch bởi kịch bản, và do đó, không thể được kiểm soát trực tiếp bởi các cấu hình kịch bản.

Những từ viết tắt có sẵn là:
- Bogon
- Cloud
- Generic
- Proxy
- Spam

Không bắt buộc: Nếu bạn muốn chia chữ ký tùy chỉnh của bạn để các phần riêng biệt, bạn có thể xác định những phần riêng lẻ cho các kịch bản bằng cách thêm một gắn thẻ "Tag:" ngay sau khi có chữ ký của từng phần, với tên của phần chữ ký của bạn.

Ví dụ:
```
# "Section 1."
1.2.3.4/32 Deny Bogon
2.3.4.5/32 Deny Cloud
4.5.6.7/32 Deny Generic
5.6.7.8/32 Deny Spam
6.7.8.9/32 Deny Proxy
Tag: Section 1
```

Để phá vỡ gắn thẻ phần và để đảm bảo rằng các gắn thẻ không được xác định không đúng để phần chữ ký từ trước đó trong các tập tin chữ ký, chỉ cần đảm bảo rằng có ít nhất hai ngắt dòng liên tiếp giữa các gắn thẻ và phần chữ ký trước đó của bạn. Bất kỳ chữ ký không được gắn thẻ sẽ mặc định để "IPv4" hoặc "IPv6" (tùy thuộc vào loại chữ ký đang được kích hoạt).

Ví dụ:
```
1.2.3.4/32 Deny Bogon
2.3.4.5/32 Deny Cloud

4.5.6.7/32 Deny Generic
5.6.7.8/32 Deny Spam
Tag: Section 1
```

Trong ví dụ trên `1.2.3.4/32` và `2.3.4.5/32` sẽ được xác định như "IPv4", trong khi `4.5.6.7/32` và `5.6.7.8/32` sẽ được xác định như "Section 1".

Ngoài ra, nếu bạn muốn CIDRAM để hoàn toàn bỏ qua một số phần cụ thể trong bất kỳ tập tin chữ ký, bạn có thể sử dụng các tập tin `ignore.dat` để xác định những phần để bỏ qua. Trên một dòng mới, viết `Ignore`, theo sau là một không gian, theo sau là tên của phần mà bạn muốn CIDRAM để bỏ qua.

Ví dụ:
```
Ignore Section 1
```

Tham khảo các tập tin chữ ký tùy chỉnh để biết thêm thông tin.

---


Lần cuối cập nhật: 23 Tháng Bảy 2016 (2016.07.23).
