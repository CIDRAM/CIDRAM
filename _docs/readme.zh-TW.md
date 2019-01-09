## CIDRAM 中文（傳統）文檔。

### 內容
- 1. [前言](#SECTION1)
- 2. [如何安裝](#SECTION2)
- 3. [如何使用](#SECTION3)
- 4. [前端管理](#SECTION4)
- 5. [文件在包](#SECTION5)
- 6. [配置選項](#SECTION6)
- 7. [簽名格式](#SECTION7)
- 8. [已知的兼容問題](#SECTION8)
- 9. [常見問題（FAQ）](#SECTION9)
- 10. *保留以備將來添加到文檔中。*
- 11. [法律信息](#SECTION11)

*翻譯註釋：如果錯誤（例如，​翻譯差異，​錯別字，​等等），​英文版這個文件是考慮了原版和權威版。​如果您發現任何錯誤，​您的協助糾正他們將受到歡迎。​*

---


### 1. <a name="SECTION1"></a>前言

CIDRAM （無類別域間路由訪問管理器）是一個PHP腳本，​旨在保護網站途經阻止請求該從始發IP地址視為不良的流量來源，​包括（但不限於）流量該從非人類的訪問端點，​雲服務，​垃圾郵件發送者，​網站鏟運機，​等等。​它通過計算CIDR的提供的IP地址從入站請求和試圖匹配這些CIDR反對它的簽名文件（這些簽名文件包含CIDR的IP地址視為不良的流量來源）；如果找到匹配，​請求被阻止。

*(看到：[什麼是『CIDR』？​](#WHAT_IS_A_CIDR))。​*

CIDRAM COPYRIGHT 2016 and beyond GNU/GPLv2 by Caleb M (Maikuolan)。

本腳本是基於GNU通用許可V2.0版許可協議發布的，​您可以在許可協議的允許範圍內自行修改和發布，​但請遵守GNU通用許可協議。​使用腳本的過程中，​作者不提供任何擔保和任何隱含擔保。​更多的細節請參見GNU通用公共許可證，​下的`LICENSE.txt`文件也可從訪問：
- <https://www.gnu.org/licenses/>。
- <https://opensource.org/licenses/>。

現在CIDRAM的代碼文件和關聯包可以從以下地址免費下載[GitHub](https://cidram.github.io/)。

---


### 2. <a name="SECTION2"></a>如何安裝

#### 2.0 安裝手工

1） 在閱讀到這里之前，​我假設您已經下載腳本的一個副本，​已解壓縮其內容並保存在您的機器的某個地方。​現在，​您要決定將腳本放在您服務器上的哪些文件夾中，​例如`/public_html/cidram/`或其他任何您覺得滿意和安全的地方。​*上傳完成後，​繼續閱讀。​。​*

2） 重命名`config.ini.RenameMe`到`config.ini`（位於內`vault`），​和如果您想（強烈推薦高級用戶，​但不推薦業餘用戶或者新手使用這個方法），​打開它（這個文件包含所有CIDRAM的可用配置選項；以上的每一個配置選項應有一個簡介來說明它是做什麼的和它的具有的功能）。​按照您認為合適的參數來調整這些選項，​然後保存文件，​關閉。

3） 上傳（CIDRAM和它的文件）到您選定的文件夾（不需要包括`*.txt`/`*.md`文件，​但大多數情況下，​您應上傳所有的文件）。

4） 修改的`vault`文件夾權限為『755』（如果有問題，​您可以試試『777』，​但是這是不太安全）。​注意，​主文件夾也應該是該權限，​如果遇上其他權限問題，​請修改對應文件夾和文件的權限。​簡而言之：為了使包正常工作，PHP需要能夠在`vault`目錄中讀寫文件。​如果PHP無法寫入`vault`目錄，那麼很多事情（更新，記錄等）都是不可能的，如果PHP無法從`vault`目錄中讀取，則包將無法正常工作。​但是，為了獲得最佳安全性，`vault`目錄不得公開訪問（如果`vault`目錄可公開訪問，敏感信息，例如`config.ini`或`frontend.dat`包含的信息，可能會暴露給潛在的攻擊者）。

5） 接下來，​您需要為您的系統或CMS設定啟動CIDRAM的鉤子。​有幾種不同的方式為您的系統或CMS設定鉤子，​最簡單的是在您的系統或CMS的核心文件的開頭中使用`require`或`include`命令直接包含腳本（這個方法通常會導致在有人訪問時每次都加載）。​平時，​這些都是存儲的在文件夾中，​例如`/includes`，​`/assets`或`/functions`等文件夾，​和將經常被命名的某物例如`init.php`，​`common_functions.php`，​`functions.php`。​這是根據您自己的情況決定的，​並不需要完全遵守；如果您遇到困難，​參觀GitHub上的CIDRAM issues頁面；可能其他用戶或者我自己也有這個問題並且解決了（您需要讓我們您在使用哪些CMS）。​為了使用`require`或`include`，​插入下面的代碼行到最開始的該核心文件，​更換裡面的數據引號以確切的地址的`loader.php`文件（本地地址，​不是HTTP地址；它會類似於前面提到的vault地址）。

`<?php require '/user_name/public_html/cidram/loader.php'; ?>`

保存文件，​關閉，​重新上傳。

-- 或替換 --

如果您使用Apache網絡服務器並且您可以訪問`php.ini`，​您可以使用該`auto_prepend_file`指令為任何PHP請求創建附上的CIDRAM。​就像是：

`auto_prepend_file = "/user_name/public_html/cidram/loader.php"`

或在該`.htaccess`文件：

`php_value auto_prepend_file "/user_name/public_html/cidram/loader.php"`

6) 這就是一切！​:-)

#### 2.1 與COMPOSER安裝

[CIDRAM是在Packagist上](https://packagist.org/packages/cidram/cidram)，​所以，​如果您熟悉Composer，​您可以使用Composer安裝CIDRAM（您仍然需要準備配置，權限，和鉤子。參考『安裝手工』步驟2，4，和5）。

`composer require cidram/cidram`

#### 2.2 為WORDPRESS安裝

如果要使用CIDRAM與WordPress，​您可以忽略上述所有說明。​[CIDRAM在WordPress插件數據庫中註冊](https://wordpress.org/plugins/cidram/)，​您可以直接從插件儀表板安裝CIDRAM。​您可以像其他插件一樣安裝，​不需要添加步驟。​與其他安裝方法相同，​您可以通過修改`config.ini`來或通過使用前端配置頁面自定義您的安裝。​更新CIDRAM通過前端更新頁面時，​插件版本信息將自動與WordPress同步。

*警告：在插件儀表板裡更新CIDRAM會導致乾淨的安裝！​如果您已經自定義了您的安裝（更改了您的配置，​安裝的模塊等），​在插件儀表板裡進行更新時，​這些定制將會丟失！​日誌文件也將丟失！​要保留日誌文件和自定義，​請通過CIDRAM前端更新頁面進行更新。​*

---


### 3. <a name="SECTION3"></a>如何使用

CIDRAM 應自動阻止不良的請求至您的網站，​沒有任何需求除了初始安裝。

您可以定制您的配置和您可以定制什麼CIDR被阻止通過修改您的配置文件和/或您的簽名文件.

如果您遇到任何假陽性，​請聯繫我讓我知道這件事。 *(看到：[什麼是『假陽性』？​](#WHAT_IS_A_FALSE_POSITIVE))。​*

CIDRAM可以手動或通過前端更新。​CIDRAM也可以通過Composer或WordPress更新，如果最初通過這些方式安裝的話。

---


### 4. <a name="SECTION4"></a>前端管理

#### 4.0 什麼是前端。

前端提供了一種方便，​輕鬆的方式來維護，​管理和更新CIDRAM安裝。​您可以通過日誌頁面查看，​共享和下載日誌文件，​您可以通過配置頁面修改配置，​您可以通過更新頁面安裝和卸載組件，​和您可以通過文件管理器上傳，​下載和修改文件在vault。

默認情況是禁用前端，​以防止未授權訪問 （未授權訪問可能會對您的網站及其安全性造成嚴重後果）。​啟用它的說明包括在本段下面。

#### 4.1 如何啟用前端。

1) 裡面的`config.ini`文件，​找到指令`disable_frontend`，​並將其設置為`false` （默認值為`true`）。

2) 從瀏覽器訪問`loader.php` （例如，​`http://localhost/cidram/loader.php`）。

3) 使用默認用戶名和密碼（admin/password）登錄。

注意：第一次登錄後，​以防止未經授權的訪問前端，​您應該立即更改您的用戶名和密碼！​這是非常重要的，​因為它可以任意PHP代碼上傳到您的網站通過前端。

此外，為了獲得最佳安全性，強烈建議為所有前端帳戶啟用『雙因素身份驗證』（下面提供的說明）。

#### 4.2 如何使用前端。

每個前端頁面上都有說明，​用於解釋正確的用法和它的預期目的。​如果您需要進一步的解釋或幫助，​請聯繫支持。​另外，​YouTube上還有一些演示視頻。

#### 4.3 2FA（雙因素身份驗證）

通過啟用雙因素身份驗證，可以使前端更安全。​當登錄使用2FA的帳戶時，會向與該帳戶關聯的電子郵件地址發送電子郵件。​此電子郵件包含『2FA代碼』，用戶必須輸入它（以及他們的用戶名和密碼），為了能夠使用該帳戶登錄。​這意味著獲取帳戶密碼不足以讓任何黑客或潛在攻擊者能夠帳戶登錄，因為他們還需要訪問帳戶的電子郵件地址才能接收和使用會話的2FA代碼（從而使前端更安全）。

首先，為了啟用雙因素身份驗證，請使用前端更新頁面來安裝PHPMailer組件。​CIDRAM使用PHPMailer發送電子郵件。​注意：雖然CIDRAM本身與`PHP >= 5.4.0`兼容，但PHPMailer需要`PHP >= 5.5.0`，因此，對於PHP 5.4用戶來說，無法為CIDRAM前端啟用雙因素身份驗證。

在安裝PHPMailer後，您需要通過CIDRAM配置頁面或配置文件填充PHPMailer的配置指令。​有關這些配置指令的更多信息包含在本文檔的配置部分中。​在填充PHPMailer配置指令後，將`Enable2FA`設置為`true`。​現在應啟用雙因素身份驗證。

接下來，您需要讓CIDRAM知道在使用該帳戶登錄時將2FA代碼發送到何處。​為此，請使用電子郵件地址作為帳戶的用戶名（例如，`foo@bar.tld`），或者將電子郵件地址作為用戶名的一部分包括在內，就像通常發送電子郵件一樣（例如，`Foo Bar <foo@bar.tld>`）。

注意：保護您的vault免受未經授權的訪問（例如，通過加強服務器的安全性和限制公共訪問權限）在此非常重要，因為未經授權訪問您的配置文件（存儲在您的vault中）可能會暴露您的出站SMTP設置（包括SMTP用戶名和密碼）。​在啟用雙因素身份驗證之前，應確保您的vault已正確保護。​如果您無法做到這一點，那麼至少應該創建一個專門用於此目的的新電子郵件帳戶，為了降低與暴露的SMTP設置相關的風險。

---


### 5. <a name="SECTION5"></a>文件在包
（本段文件採用的自動翻譯，​因為都是一些文件描述，​參考意義不是很大，​如有疑問，​請參考英文原版）

下面是一個列表的所有的文件該應該是存在在您的存檔在下載時間，​任何文件該可能創建因之的您的使用這個腳本，​包括一個簡短說明的他們的目的。

文件 | 說明
----|----
/_docs/ | 筆記文件夾（包含若干文件）。
/_docs/readme.ar.md | 阿拉伯文自述文件。
/_docs/readme.de.md | 德文自述文件。
/_docs/readme.en.md | 英文自述文件。
/_docs/readme.es.md | 西班牙文自述文件。
/_docs/readme.fr.md | 法文自述文件。
/_docs/readme.id.md | 印度尼西亞文自述文件。
/_docs/readme.it.md | 意大利文自述文件。
/_docs/readme.ja.md | 日文自述文件。
/_docs/readme.ko.md | 韓文自述文件。
/_docs/readme.nl.md | 荷蘭文自述文件。
/_docs/readme.pt.md | 葡萄牙文自述文件。
/_docs/readme.ru.md | 俄文自述文件。
/_docs/readme.ur.md | 烏爾都文自述文件。
/_docs/readme.vi.md | 越南文自述文件。
/_docs/readme.zh-TW.md | 中文（簡體）自述文件。
/_docs/readme.zh.md | 中文（簡體）自述文件。
/vault/ | 安全/保險庫【Vault】文件夾（包含若干文件）。
/vault/fe_assets/ | 前端資產。
/vault/fe_assets/.htaccess | 超文本訪問文件（在這種情況，​以保護敏感文件屬於腳本從被訪問由非授權來源）。
/vault/fe_assets/_2fa.html | 在向用戶詢問2FA代碼時使用的HTML模板。
/vault/fe_assets/_accounts.html | 前端帳戶頁面的HTML模板。
/vault/fe_assets/_accounts_row.html | 前端帳戶頁面的HTML模板。
/vault/fe_assets/_aux.html | 前端輔助規則頁面的HTML模板。
/vault/fe_assets/_cache.html | 前端緩存數據頁面的HTML模板。
/vault/fe_assets/_cidr_calc.html | CIDR計算器的HTML模板。
/vault/fe_assets/_cidr_calc_row.html | CIDR計算器的HTML模板。
/vault/fe_assets/_config.html | 前端配置頁面的HTML模板。
/vault/fe_assets/_config_row.html | 前端配置頁面的HTML模板。
/vault/fe_assets/_files.html | 文件管理器的HTML模板。
/vault/fe_assets/_files_edit.html | 文件管理器的HTML模板。
/vault/fe_assets/_files_rename.html | 文件管理器的HTML模板。
/vault/fe_assets/_files_row.html | 文件管理器的HTML模板。
/vault/fe_assets/_home.html | 端主頁的HTML模板。
/vault/fe_assets/_ip_aggregator.html | IP聚合器HTML模板。
/vault/fe_assets/_ip_test.html | IP測試頁面的HTML模板。
/vault/fe_assets/_ip_test_row.html | IP測試頁面的HTML模板。
/vault/fe_assets/_ip_tracking.html | IP跟踪頁面的HTML模板。
/vault/fe_assets/_ip_tracking_row.html | IP跟踪頁面的HTML模板。
/vault/fe_assets/_login.html | 前端登錄的HTML模板。
/vault/fe_assets/_logs.html | 前端日誌頁面的HTML模板。
/vault/fe_assets/_nav_complete_access.html | 前端導航鏈接的HTML模板，​由那些與完全訪問使用。
/vault/fe_assets/_nav_logs_access_only.html | 前端導航鏈接的HTML模板，​由那些與僅日誌訪問使用。
/vault/fe_assets/_range.html | 範圍表的HTML模板。
/vault/fe_assets/_range_row.html | 範圍表的HTML模板。
/vault/fe_assets/_sections.html | 章節列表的HTML模板。
/vault/fe_assets/_statistics.html | 前端統計頁面的HTML模板。
/vault/fe_assets/_updates.html | 前端更新頁面的HTML模板。
/vault/fe_assets/_updates_row.html | 前端更新頁面的HTML模板。
/vault/fe_assets/frontend.css | 前端CSS樣式表。
/vault/fe_assets/frontend.dat | 前端數據庫（包含帳戶信息，​會話信息，​和緩存；只生成如果前端是啟用和使用）。
/vault/fe_assets/frontend.dat.safety | 在需要時為安全目的而生成。
/vault/fe_assets/frontend.html | 前端的主HTML模板文件。
/vault/fe_assets/icons.php | 圖標處理文件（由前端文件管理器使用）。
/vault/fe_assets/pips.php | 點數處理文件（由前端文件管理器使用）。
/vault/fe_assets/scripts.js | 包含前端JavaScript數據。
/vault/lang/ | 包含CIDRAM語言數據。
/vault/lang/.htaccess | 超文本訪問文件（在這種情況，​以保護敏感文件屬於腳本從被訪問由非授權來源）。
/vault/lang/lang.ar.cli.php | 阿拉伯文CLI語言數據。
/vault/lang/lang.ar.fe.php | 阿拉伯文前端語言數據。
/vault/lang/lang.ar.php | 阿拉伯文語言數據。
/vault/lang/lang.bn.cli.php | 孟加拉文CLI語言數據。
/vault/lang/lang.bn.fe.php | 孟加拉文前端語言數據。
/vault/lang/lang.bn.php | 孟加拉文語言數據。
/vault/lang/lang.de.cli.php | 德文CLI語言數據。
/vault/lang/lang.de.fe.php | 德文前端語言數據。
/vault/lang/lang.de.php | 德文語言數據。
/vault/lang/lang.en.cli.php | 英文CLI語言數據。
/vault/lang/lang.en.fe.php | 英文前端語言數據。
/vault/lang/lang.en.php | 英文語言數據。
/vault/lang/lang.es.cli.php | 西班牙文CLI語言數據。
/vault/lang/lang.es.fe.php | 西班牙文前端語言數據。
/vault/lang/lang.es.php | 西班牙文語言數據。
/vault/lang/lang.fr.cli.php | 法文CLI語言數據。
/vault/lang/lang.fr.fe.php | 法文前端語言數據。
/vault/lang/lang.fr.php | 法文語言數據。
/vault/lang/lang.hi.cli.php | 印地文CLI語言數據。
/vault/lang/lang.hi.fe.php | 印地文前端語言數據。
/vault/lang/lang.hi.php | 印地文語言數據。
/vault/lang/lang.id.cli.php | 印度尼西亞文CLI語言數據。
/vault/lang/lang.id.fe.php | 印度尼西亞文前端語言數據。
/vault/lang/lang.id.php | 印度尼西亞文語言數據。
/vault/lang/lang.it.cli.php | 意大利文CLI語言數據。
/vault/lang/lang.it.fe.php | 意大利文前端語言數據。
/vault/lang/lang.it.php | 意大利文語言數據。
/vault/lang/lang.ja.cli.php | 日文CLI語言數據。
/vault/lang/lang.ja.fe.php | 日文前端語言數據。
/vault/lang/lang.ja.php | 日文語言數據。
/vault/lang/lang.ko.cli.php | 韓文CLI語言數據。
/vault/lang/lang.ko.fe.php | 韓文前端語言數據。
/vault/lang/lang.ko.php | 韓文語言數據。
/vault/lang/lang.nl.cli.php | 荷蘭文CLI語言數據。
/vault/lang/lang.nl.fe.php | 荷蘭文前端語言數據。
/vault/lang/lang.nl.php | 荷蘭文語言數據。
/vault/lang/lang.no.cli.php | 挪威文CLI語言數據。
/vault/lang/lang.no.fe.php | 挪威文前端語言數據。
/vault/lang/lang.no.php | 挪威文語言數據。
/vault/lang/lang.pt.cli.php | 葡萄牙文CLI語言數據。
/vault/lang/lang.pt.fe.php | 葡萄牙文前端語言數據。
/vault/lang/lang.pt.php | 葡萄牙文語言數據。
/vault/lang/lang.ru.cli.php | 俄文CLI語言數據。
/vault/lang/lang.ru.fe.php | 俄文前端語言數據。
/vault/lang/lang.ru.php | 俄文語言數據。
/vault/lang/lang.sv.cli.php | 瑞典文CLI語言數據。
/vault/lang/lang.sv.fe.php | 瑞典文前端語言數據。
/vault/lang/lang.sv.php | 瑞典文語言數據。
/vault/lang/lang.th.cli.php | 泰文CLI語言數據。
/vault/lang/lang.th.fe.php | 泰文前端語言數據。
/vault/lang/lang.th.php | 泰文語言數據。
/vault/lang/lang.tr.cli.php | 土耳其文CLI語言數據。
/vault/lang/lang.tr.fe.php | 土耳其文前端語言數據。
/vault/lang/lang.tr.php | 土耳其文語言數據。
/vault/lang/lang.ur.cli.php | 烏爾都文CLI語言數據。
/vault/lang/lang.ur.fe.php | 烏爾都文前端語言數據。
/vault/lang/lang.ur.php | 烏爾都文語言數據。
/vault/lang/lang.vi.cli.php | 越南文CLI語言數據。
/vault/lang/lang.vi.fe.php | 越南文前端語言數據。
/vault/lang/lang.vi.php | 越南文語言數據。
/vault/lang/lang.zh-tw.cli.php | 中文（傳統）CLI語言數據。
/vault/lang/lang.zh-tw.fe.php | 中文（傳統）前端語言數據。
/vault/lang/lang.zh-tw.php | 中文（傳統）語言數據。
/vault/lang/lang.zh.cli.php | 中文（簡體）CLI語言數據。
/vault/lang/lang.zh.fe.php | 中文（簡體）前端語言數據。
/vault/lang/lang.zh.php | 中文（簡體）語言數據。
/vault/.htaccess | 超文本訪問文件（在這種情況，​以保護敏感文件屬於腳本從被訪問由非授權來源）。
/vault/.travis.php | 由Travis CI用於測試（不需要為正確經營腳本）。
/vault/.travis.yml | 由Travis CI用於測試（不需要為正確經營腳本）。
/vault/auxiliary.yaml | 包含輔助規則。不包括在包中。由輔助規則頁面生成。
/vault/cache.dat | 緩存數據。
/vault/cache.dat.safety | 在需要時為安全目的而生成。
/vault/cidramblocklists.dat | Macmathan的可選阻止列表的元數據文件。​由前端更新頁面使用。
/vault/classes/ | 類目錄。包含CIDRAM使用的各種類。
/vault/classes/.htaccess | 超文本訪問文件（在這種情況，​以保護敏感文件屬於腳本從被訪問由非授權來源）。
/vault/classes/Aggregator.php | IP聚合器。
/vault/cli.php | CLI處理文件。
/vault/components.dat | 組件元數據文件。由前端更新頁面使用。
/vault/config.ini.RenameMe | 配置文件；包含所有配置指令為CIDRAM，​告訴它什麼做和怎麼正確地經營（重命名為激活）。
/vault/config.php | 配置處理文件。
/vault/config.yaml | 配置默認文件；包含CIDRAM的默認配置值。
/vault/frontend.php | 前端處理文件。
/vault/frontend_functions.php | 前端功能處理文件。
/vault/functions.php | 功能處理文件（必不可少）。
/vault/hashes.dat | 包含列表接受哈希表（相關的reCAPTCHA功能；只有生成如果reCAPTCHA功能被啟用）。
/vault/ignore.dat | 忽略文件（用於指定其中簽名章節CIDRAM應該忽略）。
/vault/ipbypass.dat | 包含列表IP旁路（相關的reCAPTCHA功能；只有生成如果reCAPTCHA功能被啟用）。
/vault/ipv4.dat | IPv4簽名文件（不想要的雲服務和非人終端）。
/vault/ipv4_bogons.dat | IPv4簽名文件（bogon/火星CIDR）。
/vault/ipv4_custom.dat.RenameMe | IPv4定制簽名文件（重命名為激活）。
/vault/ipv4_isps.dat | IPv4簽名文件（危險和垃圾容易ISP）。
/vault/ipv4_other.dat | IPv4簽名文件（CIDR從代理，​VPN和其他不需要服務）。
/vault/ipv6.dat | IPv6簽名文件（不想要的雲服務和非人終端）。
/vault/ipv6_bogons.dat | IPv6簽名文件（bogon/火星CIDR）。
/vault/ipv6_custom.dat.RenameMe | IPv6定制簽名文件（重命名為激活）。
/vault/ipv6_isps.dat | IPv6簽名文件（危險和垃圾容易ISP）。
/vault/ipv6_other.dat | IPv6簽名文件（CIDR從代理，​VPN和其他不需要服務）。
/vault/lang.php | 語音數據。
/vault/modules.dat | 模塊元數據文件。由前端更新頁面使用。
/vault/outgen.php | 輸出發生器。
/vault/php5.4.x.php | Polyfill對於PHP 5.4.X （PHP 5.4.X 向下兼容需要它；​較新的版本可以刪除它）。
/vault/recaptcha.php | reCAPTCHA模塊。
/vault/rules_as6939.php | 定制規則文件為 AS6939。
/vault/rules_softlayer.php | 定制規則文件為 Soft Layer。
/vault/rules_specific.php | 定制規則文件為一些特定的CIDR。
/vault/salt.dat | 鹽文件（使用由一些外圍功能；只產生當必要）。
/vault/template_custom.html | 模板文件；模板為HTML輸出產生通過CIDRAM輸出發生器。
/vault/template_default.html | 模板文件；模板為HTML輸出產生通過CIDRAM輸出發生器。
/vault/themes.dat | 主題元數據文件。由前端更新頁面使用。
/vault/verification.yaml | 搜索引擎和社交媒體的驗證數據。
/.gitattributes | GitHub文件（不需要為正確經營腳本）。
/Changelog.txt | 記錄的變化做出至腳本間不同版本（不需要為正確經營腳本）。
/composer.json | Composer/Packagist 信息（不需要為正確經營腳本）。
/CONTRIBUTING.md | 相關信息如何有助於該項目。
/LICENSE.txt | GNU/GPLv2 執照文件（不需要為正確經營腳本）。
/loader.php | 加載文件。​這個是文件您應該【鉤子】（必不可少）!
/README.md | 項目概要信息。
/web.config | 一個ASP.NET配置文件（在這種情況，​以保護`/vault`文件夾從被訪問由非授權來源在事件的腳本是安裝在服務器根據ASP.NET技術）。

---


### 6. <a name="SECTION6"></a>配置選項
下列是一個列表的變量發現在`config.ini`配置文件的CIDRAM，​以及一個說明的他們的目的和功能。

[general](#general-類別) | [signatures](#signatures-類別) | [recaptcha](#recaptcha-類別) | [legal](#legal-類別) | [template_data](#template_data-類別)
:--|:--|:--|:--|:--
[logfile](#logfile)<br />[logfileApache](#logfileapache)<br />[logfileSerialized](#logfileserialized)<br />[truncate](#truncate)<br />[log_rotation_limit](#log_rotation_limit)<br />[log_rotation_action](#log_rotation_action)<br />[timezone](#timezone)<br />[timeOffset](#timeoffset)<br />[timeFormat](#timeformat)<br />[ipaddr](#ipaddr)<br />[forbid_on_block](#forbid_on_block)<br />[silent_mode](#silent_mode)<br />[lang](#lang)<br />[numbers](#numbers)<br />[emailaddr](#emailaddr)<br />[emailaddr_display_style](#emailaddr_display_style)<br />[disable_cli](#disable_cli)<br />[disable_frontend](#disable_frontend)<br />[max_login_attempts](#max_login_attempts)<br />[FrontEndLog](#frontendlog)<br />[ban_override](#ban_override)<br />[log_banned_ips](#log_banned_ips)<br />[default_dns](#default_dns)<br />[search_engine_verification](#search_engine_verification)<br />[social_media_verification](#social_media_verification)<br />[protect_frontend](#protect_frontend)<br />[disable_webfonts](#disable_webfonts)<br />[maintenance_mode](#maintenance_mode)<br />[default_algo](#default_algo)<br />[statistics](#statistics)<br />[force_hostname_lookup](#force_hostname_lookup)<br />[allow_gethostbyaddr_lookup](#allow_gethostbyaddr_lookup)<br />[hide_version](#hide_version)<br />[empty_fields](#empty_fields)<br /> | [ipv4](#ipv4)<br />[ipv6](#ipv6)<br />[block_cloud](#block_cloud)<br />[block_bogons](#block_bogons)<br />[block_generic](#block_generic)<br />[block_legal](#block_legal)<br />[block_malware](#block_malware)<br />[block_proxies](#block_proxies)<br />[block_spam](#block_spam)<br />[modules](#modules)<br />[default_tracktime](#default_tracktime)<br />[infraction_limit](#infraction_limit)<br />[track_mode](#track_mode)<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /> | [usemode](#usemode)<br />[lockip](#lockip)<br />[lockuser](#lockuser)<br />[sitekey](#sitekey)<br />[secret](#secret)<br />[expiry](#expiry)<br />[logfile](#logfile)<br />[signature_limit](#signature_limit)<br />[api](#api)<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /> | [pseudonymise_ip_addresses](#pseudonymise_ip_addresses)<br />[omit_ip](#omit_ip)<br />[omit_hostname](#omit_hostname)<br />[omit_ua](#omit_ua)<br />[privacy_policy](#privacy_policy)<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /> | [theme](#theme)<br />[Magnification](#magnification)<br />[css_url](#css_url)<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
[PHPMailer](#phpmailer-類別) | [rate_limiting](#rate_limiting-類別)
[EventLog](#eventlog)<br />[SkipAuthProcess](#skipauthprocess)<br />[Enable2FA](#enable2fa)<br />[Host](#host)<br />[Port](#port)<br />[SMTPSecure](#smtpsecure)<br />[SMTPAuth](#smtpauth)<br />[Username](#username)<br />[Password](#password)<br />[setFromAddress](#setfromaddress)<br />[setFromName](#setfromname)<br />[addReplyToAddress](#addreplytoaddress)<br />[addReplyToName](#addreplytoname)<br /> | [max_bandwidth](#max_bandwidth)<br />[max_requests](#max_requests)<br />[precision_ipv4](#precision_ipv4)<br />[precision_ipv6](#precision_ipv6)<br />[allowance_period](#allowance_period)<br /><br /><br /><br /><br /><br /><br /><br /><br />

#### 『general』 （類別）
基本CIDRAM配置。

##### 『logfile』
- 人類可讀文件用於記錄所有被攔截的訪問。​指定一個文件名，​或留空以禁用。

##### 『logfileApache』
- Apache風格文件用於記錄所有被攔截的訪問。​指定一個文件名，​或留空以禁用。

##### 『logfileSerialized』
- 連載的文件用於記錄所有被攔截的訪問。​指定一個文件名，​或留空以禁用。

*有用的建議：如果您想，​可以追加日期/時間信息及附加到您的日誌文件的名稱通過包括這些中的名稱：`{yyyy}` 為今年完整，​`{yy}` 為今年縮寫，​`{mm}` 為今月，​`{dd}` 為今日，​`{hh}` 為今小時。​*

*例子：*
- *`logfile='logfile.{yyyy}-{mm}-{dd}-{hh}.txt'`*
- *`logfileApache='access.{yyyy}-{mm}-{dd}-{hh}.txt'`*
- *`logfileSerialized='serial.{yyyy}-{mm}-{dd}-{hh}.txt'`*

##### 『truncate』
- 截斷日誌文件當他們達到一定的大小嗎？​值是在B/KB/MB/GB/TB，​是日誌文件允許的最大大小直到它被截斷。​默認值為『0KB』將禁用截斷（日誌文件可以無限成長）。​注意：適用於單個日誌文件！​日誌文件大小不被算集體的。

##### 『log_rotation_limit』
- 日誌輪轉限制了任何時候應該存在的日誌文件的數量。​當新的日誌文件被創建時，如果日誌文件的指定的最大數量已經超過，將執行指定的操作。​您可以在此指定所需的限制。​值為『0』將禁用日誌輪轉。

##### 『log_rotation_action』
- 日誌輪轉限制了任何時候應該存在的日誌文件的數量。​當新的日誌文件被創建時，如果日誌文件的指定的最大數量已經超過，將執行指定的操作。​您可以在此處指定所需的操作。​『Delete』=刪除最舊的日誌文件，直到不再超出限制。​『Archive』=首先歸檔，然後刪除最舊的日誌文件，直到不再超出限制。

*技術澄清：在這種情況下，『最舊』意味著『不是最近被修改』。*

##### 『timezone』
- 這用於指定CIDRAM應用於日期/時間操作的時區。​如果您不需要它，請忽略它。​可能的值由PHP確定。​它一般建議，​而不是，​調整時區指令的文件`php.ini`，​但是有時（例如，​當利用有限的共享主機提供商）這並不總是可能做到，​所以，​此選項在這裡是提供。

##### 『timeOffset』
- 如果您的服務器時間不符合您的本地時間，​您可以在這裡指定的偏移調整日期/時間信息該產生通過CIDRAM根據您的需要。​它一般建議，​而不是，​調整時區指令的文件`php.ini`，​但是有時（例如，​當利用有限的共享主機提供商）這並不總是可能做到，​所以，​此選項在這裡是提供。​偏移量是在分鐘。
- 例子（添加1小時）：`timeOffset=60`

##### 『timeFormat』
- CIDRAM使用的日期符號格式。​標準 = `{Day}, {dd} {Mon} {yyyy} {hh}:{ii}:{ss} {tz}`。

##### 『ipaddr』
- 在哪裡可以找到連接請求IP地址？​（可以使用為服務例如Cloudflare和類似）。​標準=REMOTE_ADDR。​警告：不要修改此除非您知道什麼您做著！

『ipaddr』的推薦值：

值 | 運用
---|---
`HTTP_INCAP_CLIENT_IP` | Incapsula反向代理。
`HTTP_CF_CONNECTING_IP` | Cloudflare反向代理。
`CF-Connecting-IP` | Cloudflare反向代理（替代；如果另一個不工作）。
`HTTP_X_FORWARDED_FOR` | Cloudbric反向代理。
`X-Forwarded-For` | [Squid反向代理](http://www.squid-cache.org/Doc/config/forwarded_for/)。
*由服務器配置定義。​* | [Nginx反向代理](https://www.nginx.com/resources/admin-guide/reverse-proxy/)。
`REMOTE_ADDR` | 沒有反向代理（默認值）。

##### 『forbid_on_block』
- 阻止請求時，CIDRAM應發送哪個HTTP狀態消息？

目前支持的值：

狀態碼 | 狀態消息 | 描述
---|---|---
`200` | `200 OK` | 默認值。​最不強大，但對用戶最友善。
`403` | `403 Forbidden` | 更強大，和對用戶有點友善。
`410` | `410 Gone` | 某些瀏覽器會緩存此狀態消息，並且不會再發送請求，即使用戶未被阻止。​這可能會導致很難解決假陽性。​但為減少某些特定類型的漫遊器的請求的它可能比其他選項更有效。
`418` | `418 I'm a teapot` | 這實際上引用了愚人節的笑話【[RFC 2324](https://tools.ietf.org/html/rfc2324#section-6.5.14)】，而客戶不太可能理解它。​提供了為娛樂和方便的，但一般不推薦。
`451` | `Unavailable For Legal Reasons` | 主要出於法律原因阻止請求時適用於上下文。​不建議在其他情況下。
`503` | `Service Unavailable` | 最強大，但對用戶最不友善。

##### 『silent_mode』
- CIDRAM應該默默重定向被攔截的訪問而不是顯示該『拒絕訪問』頁嗎？​指定位置至重定向被攔截的訪問，​或讓它空將其禁用。

##### 『lang』
- 指定標準CIDRAM語言。

##### 『numbers』
- 指定如何顯示數字。

目前支持的值：

值 | 產生 | 描述
---|---|---
`NoSep-1` | `1234567.89`
`NoSep-2` | `1234567,89`
`Latin-1` | `1,234,567.89` | 默認值。
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

*注意：​這些值在任何地方都不是標準化的，並超出包裹且可能不會相關性。​此外，支持的值可能會在未來發生變化。*

##### 『emailaddr』
- 如果您希望，​您可以提供電子郵件地址這裡要給予用戶當他們被阻止，​他們使用作為接觸點為支持和/或幫助在的情況下他們錯誤地阻止。​警告:您提供的任何電子郵件地址，​它肯定會被獲得通過垃圾郵件機器人和鏟運機，​所以，​它強烈推薦如果選擇提供一個電子郵件地址這裡，​您保證它是一次性的和/或不是很重要（換一種說法，​您可能不希望使用您的主電子郵件地址或您的企業電子郵件地址）。

##### 『emailaddr_display_style』
- 您希望如何將電子郵件地址呈現給用戶？ 『default』 = 可點擊的鏈接。 『noclick』 = 不可點擊的文字。

##### 『disable_cli』
- 關閉CLI模式嗎？​CLI模式是按說激活作為標準，​但可以有時干擾某些測試工具（例如PHPUnit，​為例子）和其他基於CLI應用。​如果您沒有需要關閉CLI模式，​您應該忽略這個指令。​False（假）=激活CLI模式【標準】；​True（真）=關閉CLI模式。

##### 『disable_frontend』
- 關閉前端訪問嗎？​前端訪問可以使CIDRAM更易於管理，​但也可能是潛在的安全風險。​建議管理CIDRAM通過後端只要有可能，​但前端訪問提供當不可能。​保持關閉除非您需要它。​False（假）=激活前端訪問；​True（真）=關閉前端訪問【標準】。

##### 『max_login_attempts』
- 最大登錄嘗試次數（前端）。​標準=5。

##### 『FrontEndLog』
- 前端登錄嘗試的錄音文件。​指定一個文件名，​或留空以禁用。

##### 『ban_override』
- 覆蓋『forbid_on_block』當『infraction_limit』已被超過？​當覆蓋：已阻止的請求返回一個空白頁（不使用模板文件）。​200 = 不要覆蓋【標準】。​其他值與『forbid_on_block』的可用值相同。

##### 『log_banned_ips』
- 包括IP禁止從阻止請求在日誌文件嗎？​True（真）=是【標準】；​False（假）=不是。

##### 『default_dns』
- 以逗號分隔的DNS服務器列表，​用於主機名查找。​標準 = 『8.8.8.8,8.8.4.4』 (Google DNS)。​警告：不要修改此除非您知道什麼您做著！

*也可以看看：​[在『default_dns』中我可以使用什麼？](#WHAT_CAN_I_USE_FOR_DEFAULT_DNS)*

##### 『search_engine_verification』
- 嘗試驗證來自搜索引擎的請求？​驗證搜索引擎確保他們不會因超過違規限製而被禁止 （禁止在您的網站上使用搜索引擎通常會有產生負面影響對您的搜索引擎排名，​SEO，​等等）。​當被驗證，​搜索引擎可以被阻止，​但不會被禁止。​當不被驗證，​他們可以由於超過違規限製而被禁止。​另外，​搜索引擎驗證提供保護針對假搜索引擎請求和針對潛在的惡意實體偽裝成搜索引擎（當搜索引擎驗證是啟用，​這些請求將被阻止）。​True（真）=搜索引擎驗證是啟用【標準】；​False（假）=搜索引擎驗證是禁用。

目前支持：
- __[Google (谷歌)](https://support.google.com/webmasters/answer/80553?hl=en)__
- __[Bing](https://blogs.bing.com/webmaster/2012/08/31/how-to-verify-that-bingbot-is-bingbot)__
- __Yahoo (雅虎)__
- __[Baidu (百度)](https://help.baidu.com/question?prod_en=master&class=Baiduspider)__
- __Sogou (搜狗)__
- __Youdao (有道)__
- __[Applebot](https://discussions.apple.com/thread/7090135)__
- __[Yandex (Яндекс)](https://yandex.com/support/webmaster/robot-workings/check-yandex-robots.xml)__
- __[DuckDuckGo](https://duckduckgo.com/duckduckbot)__

不兼容（導致衝突）：
- __[Mix.com](https://github.com/CIDRAM/CIDRAM/issues/80)__

##### 『social_media_verification』
- 嘗試驗證社交媒體請求？​社交媒體驗證可以防止虛假社交媒體請求（此類請求將被阻止）。​True（真）=啟用社交媒體驗證【標準】；​False（假）=禁用社交媒體驗證。

目前支持：
- __[Pinterest](https://help.pinterest.com/en/articles/about-pinterest-crawler-0)__
- __Embedly__
- __[Grapeshot](https://www.grapeshot.com/crawler/)__
- __Twitterbot__

##### 『protect_frontend』
- 指定是否應將CIDRAM通常提供的保護應用於前端。​True（真）=是【標準】；​False（假）=不是。

##### 『disable_webfonts』
- 關閉網絡字體嗎？​True（真）=關閉【標準】；False（假）=不關閉。

##### 『maintenance_mode』
- 啟用維護模式？​True（真）=關閉；​False（假）=不關閉【標準】。​它停用一切以外前端。​有時候在更新CMS，框架，等時有用。

##### 『default_algo』
- 定義要用於所有未來密碼和會話的算法。​選項：​PASSWORD_DEFAULT（標準），​PASSWORD_BCRYPT，​PASSWORD_ARGON2I（需要PHP >= 7.2.0）。

##### 『statistics』
- 跟踪CIDRAM使用情況統計？​True（真）=跟踪；False（假）=不跟踪【標準】。

##### 『force_hostname_lookup』
- 強制主機名查找？​True（真）=跟踪；False（假）=不跟踪【標準】。​主機名查詢通常在『根據需要』的基礎上執行，但可以在所有請求上強制。​這可能會有助於提供日誌文件中更詳細的信息，但也可能會對性能產生輕微的負面影響。

##### 『allow_gethostbyaddr_lookup』
- 當UDP不可用時允許gethostbyaddr查找？​True（真）=允許【標準】；False（假）=不允許。
- *注意：在某些32位系統上，IPv6查找可能無法正常工作。*

##### 『hide_version』
- 從日誌和頁面輸出中隱藏版本信息嗎？​True（真）=關閉；False（假）=不關閉【標準】。

##### 『empty_fields』
- 在記錄和顯示阻止事件信息時，CIDRAM如何處理空字段？ 『include』 = 包括空字段。 『omit』 = 省略空字段【標準】。

#### 『signatures』 （類別）
簽名配置。

##### 『ipv4』
- 列表的IPv4簽名文件，​CIDRAM應該嘗試使用，​用逗號分隔。​您可以在這裡添加條目如果您想包括其他文件在CIDRAM。

##### 『ipv6』
- 列表的IPv6簽名文件，​CIDRAM應該嘗試使用，​用逗號分隔。​您可以在這裡添加條目如果您想包括其他文件在CIDRAM。

##### 『block_cloud』
- 阻止CIDR認定為屬於虛擬主機或云服務嗎？​如果您操作一個API服務從您的網站或如果您預計其他網站連接到您的網站，​這應該被設置為『false』（假）。​如果不，​這應該被設置為『true』（真）。

##### 『block_bogons』
- 阻止bogon(『ㄅㄡㄍㄛㄋ』)/martian（​『火星』）CIDR嗎？​如果您希望連接到您的網站從您的本地網絡/本地主機/localhost/LAN/等等，​這應該被設置為『false』（假）。​如果不，​這應該被設置為『true』（真）。

##### 『block_generic』
- 阻止CIDR一般建議對於黑名單嗎？​這包括簽名不標記為的一章节任何其他更具體簽名類別。

##### 『block_legal』
- 阻止CIDR因為法律義務嗎？​這個指令通常不應該有任何作用，因為CIDRAM默認情況下不會將任何CIDR與『法律義務』相關聯，​但它作為一個額外的控制措施存在，以利於任何可能因法律原因而存在的自定義簽名文件或模塊。

##### 『block_malware』
- 阻止與惡意軟件相關的IP？​這包括C＆C服務器，受感染的機器，涉及惡意軟件分發的機器，等等。

##### 『block_proxies』
- 阻止CIDR認定為屬於代理服務或VPN嗎？​如果您需要該用戶可以訪問您的網站從代理服務和VPN，​這應該被設置為『false』（假）。​除此以外，​如果您不需要代理服務或VPN，​這應該被設置為『true』（真）作為一個方式以提高安全性。

##### 『block_spam』
- 阻止高風險垃圾郵件CIDR嗎？​除非您遇到問題當這樣做，​通常，​這應該被設置為『true』（真）。

##### 『modules』
- 模塊文件要加載的列表以後檢查簽名IPv4/IPv6，​用逗號分隔。

##### 『default_tracktime』
- 多少秒鐘來跟踪模塊禁止的IP。​標準 = 604800 （1週）。

##### 『infraction_limit』
- 從IP最大允許違規數量之前它被禁止。​標準=10。

##### 『track_mode』
- 何時應該記錄違規？​False（假）=當IP被模塊阻止時。​True（真）=當IP由於任何原因阻止時。

#### 『recaptcha』 （類別）
如果您想，​您可以為用戶提供了一種方法繞過『拒絕訪問』頁面通過完成reCAPTCHA事件。​這有助於減輕一些風險假陽性有關，​對於當我們不能完全肯定一個請求是否源自機器或人。

由於風險相關的提供的方法為終端用戶至繞過『拒絕訪問』頁面，​通常，​我建議不要啟用此功能除非您覺得這是必要的做。​情況由此有必要：如果您的網站有客戶/用戶該需要具有訪問權限您的網站，​而如果這一點該不能妥協的，​但如果這些客戶/用戶碰巧被來自敵對網絡連接該可能被攜帶不需要的流量，​並阻斷這種不需要的流量也不能妥協的，​在那些沒有雙贏的局面，​reCAPTCHA的功能可能是有用的作為一種手段允許需要的客戶/用戶，​而避開不需要的流量從同一網絡。​雖然說，​鑑於一個CAPTCHA的預期目的是人類和非人類區分，​reCAPTCHA的功能只會協助在這些沒有雙贏的局面如果我們假設該不需要的流量是非人（例如，​垃圾郵件機器人，​網站鏟運機，​黑客工具，​交通自動化），​而不是作為人的不需要的流量（如人的垃圾郵件機器人，​黑客，​等等）。

為了獲得『site key』和『secret key』（需要為了使用reCAPTCHA），​請訪問：[https://developers.google.com/recaptcha/](https://developers.google.com/recaptcha/)

##### 『usemode』
- 它定義瞭如何CIDRAM應該使用reCAPTCHA。
- 0 = reCAPTCHA是完全禁用【標準】。
- 1 = reCAPTCHA是啟用為所有簽名。
- 2 = reCAPTCHA是啟用只為簽名章節被特殊標記在簽名文件作為reCAPTCHA啟用。
- （任何其他值將以同樣的方式被視作0）。

##### 『lockip』
- 指定是否哈希應鎖定到特定IP地址。​False（假）=Cookie和哈希可以由多個IP地址使用【標準】。​True（真）=Cookie和哈希不能由多個IP地址使用（cookies/哈希是鎖定到IP地址）。
- 注意：『lockip』值被忽略當『lockuser』是`false`（假），​由於該機制為記憶的『用戶』可以根據這個值的變化。

##### 『lockuser』
- 指定是否一個reCAPTCHA成功完成應鎖定到特定用戶。​False（假）=一個reCAPTCHA成功完成將授予訪問為所有請求該來自同IP作為由用戶使用當完成的reCAPTCHA；​Cookie和哈希不被使用；代替，​一個IP白名單將被用於。​True（真）=一個reCAPTCHA成功完成只會授予訪問為用戶該完成了reCAPTCHA；​Cookie和哈希是用於記住用戶；一個IP白名單不被使用【標準】。

##### 『sitekey』
- 該值應該對應於『site key』為您的reCAPTCHA，​該可以發現在reCAPTCHA的儀表板。

##### 『secret』
- 該值應該對應於『secret key』為您的reCAPTCHA，​該可以發現在reCAPTCHA的儀表板。

##### 『expiry』
- 當『lockuser』是true（真）【標準】，​為了記住當用戶已經成功完成reCAPTCHA，​為未來頁面請求，​CIDRAM生成一個標準的HTTP cookie含哈希對應於內部哈希記錄含有相同哈希；未來頁面請求將使用這些對應的哈希為了驗證該用戶已預先完成reCAPTCHA。​當『lockuser』是false（假），​一個IP白名單被用來確定是否請求應允許從請求的入站IP；條目添加到這個白名單當reCAPTCHA是成功完成。​這些cookies，​哈希，​和白名單條目應在多少小時內有效？​標準 = 720 （1個月）。

##### 『logfile』
- 記錄所有的reCAPTCHA的嘗試？​要做到這一點，​指定一個文件名到使用。​如果不，​離開這個變量為空白。

*有用的建議：如果您想，​可以追加日期/時間信息及附加到您的日誌文件的名稱通過包括這些中的名稱：`{yyyy}` 為今年完整，​`{yy}` 為今年縮寫，​`{mm}` 為今月，​`{dd}` 為今日，​`{hh}` 為今小時。​*

*例子：*
- *`logfile='recaptcha.{yyyy}-{mm}-{dd}-{hh}.txt'`*

##### 『signature_limit』
- 當提供reCAPTCHA實例時，允許觸發最大簽名數量。​標準 = 1。​如果這個數字超過了任何特定的請求，一個reCAPTCHA實例將不會被提供。

##### 『api』
- 使用哪個API？V2或Invisible？

*歐盟用戶須知：​當CIDRAM被配置為使用cookie時（例如，當『lockuser』是true/真時），根據[歐盟的cookie法規](https://www.cookielaw.org/the-cookie-law/)，cookie警告顯示在頁面上。​但是，當使用invisible API時，CIDRAM將自動為用戶完成reCAPTCHA，並且當成功時，這可能導致頁面被重新加載，並且創建cookie，而用戶沒有足夠的時間來實際看到cookie警告。​如果這對您構成法律風險，那麼最好使用V2 API而不使用invisible API（V2 API不是自動的，並且要求用戶自己完成reCAPTCHA挑戰，因此提供了一個機會來查看cookie警告）。*

#### 『legal』 （類別）
有關法律義務的配置。

*請參閱文檔的『[法律信息](#SECTION11)』章節以獲取更多有關法律義務的信息，以及它可以如何影響您的配置義務。*

##### 『pseudonymise_ip_addresses』
- 編寫日誌文件時使用假名的IP地址嗎？​True（真）=使用假名【標準】；False（假）=不使用假名。

##### 『omit_ip』
- 從日誌文件中排除IP地址？​True（真）=排除；False（假）=不排除【標準】。​注意：『omit_ip』為『true』時，『pseudonymise_ip_addresses』變得不必要。

##### 『omit_hostname』
- 從日誌文件中排除主機名？​True（真）=排除；False（假）=不排除【標準】。

##### 『omit_ua』
- 從日誌文件中排除用戶代理？​True（真）=排除；False（假）=不排除【標準】。

##### 『privacy_policy』
- 要顯示在任何生成的頁面的頁腳中的相關隱私政策的地址。​指定一個URL，或留空以禁用。

#### 『template_data』 （類別）
指令和變量為模板和主題。

涉及的HTML輸出用於生成該『拒絕訪問』頁面。​如果您使用個性化主題為CIDRAM，​HTML產量資源是從`template_custom.html`文件，​和否則，​HTML產量資源是從`template.html`文件。​變量書面在這個配置文件章节是餵在HTML產量通過更換任何變量名包圍在大括號發現在HTML產量使用相應變量數據。​為例子，​哪里`foo="bar"`，​任何發生的`<p>{foo}</p>`發現在HTML產量將成為`<p>bar</p>`。

##### 『theme』
- 用於CIDRAM的默認主題。

##### 『Magnification』
- 字體放大。​標準 = 1。

##### 『css_url』
- 模板文件為個性化主題使用外部CSS屬性，​而模板文件為t標準主題使用內部CSS屬性。​以指令CIDRAM使用模板文件為個性化主題，​指定公共HTTP地址的您的個性化主題的CSS文件使用`css_url`變量。​如果您離開這個變量空白，​CIDRAM將使用模板文件為默認主題。

#### 『PHPMailer』 （類別）
PHPMailer配置。

目前，CIDRAM僅將PHPMailer用於前端雙因素身份驗證。​如果不使用前端，或者如果為前端不用雙因素身份驗證，則可以忽略這些指令。

##### 『EventLog』
- 用於記錄與PHPMailer相關的所有事件的文件。​指定一個文件名，​或留空以禁用。

##### 『SkipAuthProcess』
- 將此指令設置為`true`會指示PHPMailer跳過通過SMTP發送電子郵件時通常會發生的正常身份驗證過程。​應該避免這種情況，因為跳過此過程可能會將出站電子郵件暴露給MITM攻擊，但在此過程阻止PHPMailer連接到SMTP服務器的情況下可能是必要的。

##### 『Enable2FA』
- 該指令確定是否將2FA用於前端帳戶。

##### 『Host』
- 用於出站電子郵件的SMTP主機。

##### 『Port』
- 用於出站電子郵件的端口號。​標準=587。

##### 『SMTPSecure』
- 通過SMTP發送電子郵件時使用的協議（TLS或SSL）。

##### 『SMTPAuth』
- 此指令確定是否對SMTP會話進行身份驗證（通常應該保持不變）。

##### 『Username』
- 通過SMTP發送電子郵件時使用的用戶名。

##### 『Password』
- 通過SMTP發送電子郵件時使用的密碼。

##### 『setFromAddress』
- 通過SMTP發送電子郵件時引用的發件人地址。

##### 『setFromName』
- 通過SMTP發送電子郵件時引用的發件人姓名。

##### 『addReplyToAddress』
- 通過SMTP發送電子郵件時引用的回复地址。

##### 『addReplyToName』
- 通過SMTP發送電子郵件時引用的回複姓名。

#### 『rate_limiting』 （類別）
用於速率限制的可選配置指令。

此功能已實施到CIDRAM，因為有足夠的用戶請求它來辯解它的實施。​但是，因為它與之無關CIDRAM的最初的預期目的，大多數用戶很可能不需要它。​如果您特別需要CIDRAM來處理您網站的速率限制，此功能可能是有用給您的。​但是，您應該考慮一些重要的事情：
- 與所有其他CIDRAM功能一樣，此功能僅適用於受CIDRAM保護的頁面。​因此，任何未通過CIDRAM特定路由的網站資產都不能被CIDRAM限制。
- 不要忘記CIDRAM將緩存和其他數據直接寫入磁盤（即，它將它的數據保存到文件中），並且它不使用任何外部數據庫系統，如MySQL，PostgreSQL，Access，或類似系統。​這意味著為了跟踪速率限制的使用情況，它實際上需要為每個可能的速率限制請求寫入磁盤。​這可能有助於長期降低磁盤壽命，並且不是理想的推薦。​相反，理想情況下，速率限制工具可以利用用於頻繁，小型讀/寫操作的數據庫系統，或者可以在請求之間持久保留信息，而無需在請求之間將數據寫入磁盤（例如，編寫為獨立的服務器模塊，而不是PHP包）。
- 如果您能夠使用服務器模塊，cPanel，或其他一些網絡工具來強制執行速率限制，最好將其用於速率限制，而不是CIDRAM。
- 如果特定用戶非常希望在受到限制後繼續訪問您的網站，在大多數情況下，他們很容易繞過速率限制（例如，如果他們改變他們的IP地址，或者如果他們使用代理或VPN，並假設您已將CIDRAM配置為不阻止代理和VPN，或者假設CIDRAM不知道他們正在使用的代理或VPN）。
- 對於真實用戶來說，速率限制可能非常煩人。​如果您的可用帶寬非常有限，並且如果您發現有一些特定的流量來源，尚未被阻止，並且它佔用大部分可用帶寬，速率限制可能是必要的。​然而，如果沒有必要，應該避免它。
- 您可能偶爾冒險阻止合法用戶，甚至是您自己。

如果您不需要CIDRAM來對您的網站進行速率限制，請將以下指令設置為默認值。​否則，您可以更改其值以滿足您的需求。

##### 『max_bandwidth』
- 在為將來的請求啟用速率限制之前的最大允許帶寬量。​值為0將禁用此類速率限制。​標準=0KB。

##### 『max_requests』
- 在為將來的請求啟用速率限制之前允許的最大請求數。​值為0將禁用此類速率限制。​標準=0。

##### 『precision_ipv4』
- 監視IPv4使用時的精度。​值鏡像CIDR塊大小。​設置為32以獲得最佳精度。​標準=32。

##### 『precision_ipv6』
- 監視IPv6使用時的精度。​值鏡像CIDR塊大小。​設置為128以獲得最佳精度。​標準=128。

##### 『allowance_period』
- 監視使用情況的小時數。​標準=0。

---


### 7. <a name="SECTION7"></a>簽名格式

*也可以看看：*
- *[什麼是『簽名』？](#WHAT_IS_A_SIGNATURE)*

#### 7.0 基本概念（對於簽名文件）

所有IPv4簽名遵循格式：`xxx.xxx.xxx.xxx/yy [Function] [Param]`。
- `xxx.xxx.xxx.xxx` 代表CIDR塊開始（初始IP地址八比特組）。
- `yy` 代表CIDR塊大小 [1-32]。
- `[Function]` 指令腳本做什麼用的署名（應該怎麼簽名考慮）。
- `[Param]` 代表任何其他信息其可以由需要 `[Function]`。

所有IPv6簽名遵循格式：`xxxx:xxxx:xxxx:xxxx::xxxx/yy [Function] [Param]`。
- `xxxx:xxxx:xxxx:xxxx::xxxx` 代表CIDR塊的開始（初始IP地址八比特組）。​完整符號和縮寫符號是可以接受的（和每都必須遵循相應和相關IPv6符號標準，​但有一個例外：IPv6地址不能開頭是與縮寫當用來在簽名該腳本，​由於以何種方式CIDR是構建由腳本；例如，​當用來在簽名，​`::1/128`應該這樣寫`0::1/128`，​和`::0/128`應該這樣寫`0::/128`)。
- `yy` 代表CIDR塊大小 [1-128]。
- `[Function]` 指令腳本做什麼用的署名（應該怎麼簽名考慮）。
- `[Param]` 代表任何其他信息其可以由需要 `[Function]`。

CIDRAM簽名文件應該使用Unix的換行符（`%0A`，​或`\n`）！​其他換行符類型/風格（例如，​Windows `%0D%0A`或`\r\n`換行符，​Mac `%0D`或`\r`換行符，​等等） 可以被用於，​但不是優選。​非Unix的換行符將正常化至Unix的換行符由腳本。

精準無誤的CIDR符號是必須的，​不會是承認簽名。​另外，​所有的CIDR簽名必須用一個IP地址該始於一個數在該CIDR塊分割適合於它的塊大小（例如，​如果您想阻止所有的IP從`10.128.0.0`到`11.127.255.255`，​`10.128.0.0/8`不會是承認由腳本，​但`10.128.0.0/9`和`11.0.0.0/9`結合使用，​將是承認由腳本）。

任何數據在簽名文件不承認為一個簽名也不為簽名相關的語法由腳本將被忽略，​因此，​這意味著您可以放心地把任何未簽名數據和任何您想要的在簽名文件沒有打破他們和沒有打破該腳本。​註釋是可以接受的在簽名文件，​和沒有特殊的格式需要為他們。​Shell風格的哈希註釋是首選，​但並非強制；從功能的角度，​無論您是否選擇使用Shell風格的哈希註釋，​有沒有區別為腳本，​但使用Shell風格的哈希幫助IDE和純文本編輯器正確地突出的各個章节簽名文件（所以，​Shell風格的哈希可以幫助作為視覺輔助在編輯）。

`[Function]` 可能的值如下：
- Run
- Whitelist
- Greylist
- Deny

如果『Run』是用來，​當該簽名被觸發，​該腳本將嘗試執行（使用一個`require_once`聲明）一個外部PHP腳本，​由指定的`[Param]`值（工作目錄應該是『/vault/』腳本目錄）。

例子：`127.0.0.0/8 Run example.php`

這可能是有用如果您想執行一些特定的PHP代碼對一些特定的IP和/或CIDR。

如果『Whitelist』是用來，​當該簽名被觸發，​該腳本將重置所有檢測（如果有過任何檢測）和打破該測試功能。​`[Param]`被忽略。​此功能將白名單一個IP地址或一個CIDR。

例子：`127.0.0.1/32 Whitelist`

如果『Greylist』是用來，​當該簽名被觸發，​該腳本將重置所有檢測（如果有過任何檢測）和跳到下一個簽名文件以繼續處理。​`[Param]`被忽略。

例子：`127.0.0.1/32 Greylist`

如果『Deny』是用來，​當該簽名被觸發，​假設沒有白名單簽名已觸發為IP地址和/或CIDR，​訪問至保護的頁面被拒絕。​您要使用『Deny』為實際拒絕一個IP地址和/或CIDR範圍。​當任何簽名利用的『Deny』被觸發，​該『拒絕訪問』腳本頁面將生成和請求到保護的頁面會被殺死。

『Deny』的`[Param]`值會被解析為『拒絕訪問』頁面，​提供給客戶機/用戶作為引原因他們訪問到請求的頁面被拒絕。​它可以是一個短期和簡單的句子，​為解釋原因（什麼應該足夠了；一個簡單的消息像『我不想讓您在我的網站』會好起來的），​或一小撮之一的短關鍵字供應的通過腳本。​如果使用，​它們將被替換由腳本使用預先準備的解釋為什麼客戶機/用戶已被阻止。

預先準備的解釋具有多語言支持和可以翻譯通過腳本根據您的語言指定的通過`lang`腳本配置指令。​另外，​您可以指令腳本忽略『Deny』簽名根據他們的價`[Param]`值（如果他們使用這些短關鍵字）通過腳本配置指令（每短關鍵字有一個相應的指令到處理相應的簽名或忽略它）。​`[Param]`值不使用這些短關鍵字，​然而，​沒有多語言支持和因此不會被翻譯通過腳本，​並且還，​不能直接控制由腳本配置。

可用的短關鍵字是：
- Bogon
- Cloud
- Generic
- Proxy
- Spam
- Legal
- Malware

#### 7.1 標籤

##### 7.1.0 章節標籤

如果要分割您的自定義簽名成各個章節，​您可以識別這些各個章節為腳本通過加入一個章節標籤立即跟著每簽名章節，​伴隨著簽名章節名字（看下面的例子）。

```
# 章節一。
1.2.3.4/32 Deny Bogon
2.3.4.5/32 Deny Cloud
4.5.6.7/32 Deny Generic
5.6.7.8/32 Deny Spam
6.7.8.9/32 Deny Proxy
Tag: 章節一
```

為了打破章節標籤和以確保標籤不是確定不正確的對於簽名章節從較早的在簽名文件，​確保有至少有兩個連續的換行符之間您的標籤和您的較早的簽名章節。​任何未標記簽名將默認為『IPv4』或『IPv6』（取決於簽名類型被觸發）。

```
1.2.3.4/32 Deny Bogon
2.3.4.5/32 Deny Cloud

4.5.6.7/32 Deny Generic
5.6.7.8/32 Deny Spam
Tag: 章節一
```

在上面的例子`1.2.3.4/32`和`2.3.4.5/32`將標為『IPv4』，​而`4.5.6.7/32`和`5.6.7.8/32`將標為『章節一』.

同樣邏輯也可以應用於分離其他標籤類型。

特別是，當假陽性發生時，章節標籤對調試非常有用，通過提供一個簡單的方法來找到問題的確切來源，並當通過前端日誌頁面查看日誌文件時，可以非常有用地過濾日誌文件條目​（章節名稱可通過前端日誌頁面進行點擊，並可用作過濾標準）。​如果在一些簽名章節中章節標籤是省略，當這些簽名被觸發時，CIDRAM使用簽名文件的名稱以及阻止的IP地址類型（IPv4或IPv6）作為後備，因此，章節標籤是完全可選的。​但是，在某些情況下，可能會推薦使用它們，例如簽名文件模糊地命名，或當難以清楚地識別阻止請求的簽名的來源時。

##### 7.1.1 過期標籤

如果您想簽名一段時間後過期，​以類似的方式來章節標籤，​您可以使用一個『過期標籤』來指定在簽名應該不再有效。​過期標籤使用格式『年年年年.月月.日日』（看下面的例子）。

```
# 章節一。
1.2.3.4/32 Deny Generic
2.3.4.5/32 Deny Generic
Expires: 2016.12.31
```

過期的簽名永遠不會被任何請求觸發。

##### 7.1.2 起源標籤

如果您想要指定某個特定簽名的原產國，可以使用『原產標籤』來實現。​原產標籤接受與其適用的簽名相對應的國家的『[ISO 3166-1 二位字母](https://zh.wikipedia.org/wiki/ISO_3166-1)』代碼。​這些代碼必須寫成大寫（小寫或混合大小寫不能正確顯示）。​當使用原產標籤時，會將其添加到『為什麼被阻止』日誌字段條目中，為任何請求被阻止因為簽名的標籤應用。

如果安裝了可選的『flags CSS』組件，當通過前端日誌頁面查看日誌文件時，由原產標籤附加的信息被其相應的國旗取代。​無論是原始形式還是國旗，這些信息是可點擊的，並當點擊時，它將通過類似識別的日誌條目來過濾日誌條目（從而有效地啟用日誌頁面按來源國過濾）。

注意：從技術上講，這不是任何形式的地理定位，因為它不涉及從IP查找特定的位置信息，反而，它允許我們當請求被特定的簽名阻止時明確指出一個原產國。​同一個簽名章節允許有多個原產標籤。

假設的例子：

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

任何標籤都可以聯合使用，所有標籤都是可選（看下面的例子）。

```
# 示例章節。
1.2.3.4/32 Deny Generic
Origin: US
Tag: 示例章節
Expires: 2016.12.31
```

##### 7.1.3 延緩標籤

當大量安裝並主動使用的簽名文件時，安裝可能會變得非常複雜，並且可能存在一些重疊的簽名。​在這些情況下，為了防止在阻止事件期間觸發多個重疊的簽名，在大量安裝並主動使用的某些其他特定簽名文件的情況下，延緩標籤可用於延緩特定簽名章節。​在某些簽名比其他簽名更頻繁更新的情況下，這可能很有用，為了延緩較不頻繁更新的簽名，於贊成更頻繁更新的簽名。

延緩標籤與其他類型的標籤類似地使用。​標籤的值應該是要贊成的簽名文件的名稱。

例子：

```
1.2.3.4/32 Deny Generic
Origin: AA
2.3.4.5/32 Deny Generic
Origin: BB
Defers to: preferred_signatures.dat
```

#### 7.2 YAML

##### 7.2.0 YAML基本概念

簡化形式的YAML標記可以使用在簽名文件用於目的定義行為和配置設置具體到個人簽名章節。​這可能是有用的如果您希望您的配置指令值到變化之間的個人簽名和簽名章節（例如；如果您想提供一個電子郵件地址為支持票為任何用戶攔截的通過一個特定的簽名，​但不希望提供一個電子郵件地址為支持票為用戶攔截的通過任何其他簽名；如果您想一些具體的簽名到觸發頁面重定向；如果您想標記一個簽名為使用的reCAPTCHA；如果您想日誌攔截的訪問到單獨的文件按照個人簽名和/或簽名章節）。

使用YAML標記在簽名文件是完全可選（即，​如果您想用這個，​您可以用這個，​但您沒有需要用這個），​和能夠利用最的（但不所有的）配置指令。

注意：YAML標記實施在CIDRAM是很簡單也很有限；它的目的是滿足特定要求的CIDRAM在方式具有熟悉的YAML標記，​但不跟隨也不符合規定的官方規格（因此，​將不會是相同的其他實現別處，​和可能不適合其他項目別處）。

在CIDRAM，​YAML標記段被識別到腳本通過使用三個連字符（『---』），​和終止靠他們的簽名章節通過雙換行符。​一個典型的YAML標記段在一個簽名章節被組成的三個連字符在一行立馬之後的CIDR列表和任何標籤，​接著是二維表為鍵值對（第一維，​配置指令類別；第二維，​配置指令）為哪些配置指令應修改（和哪些值）每當一個簽名內那簽名章節被觸發（看下面的例子）。

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

##### 7.2.1 如何『特別標記』簽名章節為使用的reCAPTCHA

當『usemode』是『0』或『1』，​簽名章節不需要是『特別標記』簽名章節為使用的reCAPTCHA（因為他們也會或不會使用reCAPTCHA，​根據此設置）。

當『usemode』是『2』，​為『特別標記』簽名章節為使用的reCAPTCHA，​一個條目是包括在YAML段為了那個簽名章節（看下面的例子）。

```
# 本節將使用reCAPTCHA。
1.2.3.4/32 Deny Generic
2.3.4.5/32 Deny Generic
Tag: reCAPTCHA-Enabled
---
recaptcha:
 enabled: true
```

*注意：默認，一個reCAPTCHA將僅提供給用戶如果reCAPTCHA是啟用（當『usemode』是『1』，​或『usemode』是『2』和『enabled』是『true』），​和如果只有一個簽名已觸發（不多不少；如果多個簽名被觸發，​一個reCAPTCHA將不提供）。​然而，這個行為可以通過『signature_limit』指令來修改。*

#### 7.3 輔

##### 7.3.0 忽略簽名章節

此外，​如果您想CIDRAM完全忽略一些具體的章節內的任何簽名文件，​您可以使用`ignore.dat`文件為指定忽略哪些章節。​在新行，​寫`Ignore`，​空間，​然後該名稱的章節您希望CIDRAM忽略（看下面的例子）。

```
Ignore 章節一
```

這也可以通過使用CIDRAM前端的『章節列表』頁面提供的接口來實現。

##### 7.3.1 輔助規則

如果您覺得編寫自己的自定義簽名文件或自定義模塊對您來說太複雜了，更簡單的替代方案可能是使用CIDRAM前端的『輔助規則』頁面提供的接口。​通過選擇適當的選項並指定有關特定類型的請求的詳細信息，您可以指示CIDRAM如何響應這些請求。​在所有簽名文件和模塊已經完成執行之後執行『輔助規則』。

#### 7.4 <a name="MODULE_BASICS"></a>基本概念（對於模塊）

模塊可用於擴展CIDRAM的功能，執行額外的任務，或處理額外的邏輯。​通常，當除了起源IP地址之外的原因需要阻止請求時它們使用​（因此，當CIDR簽名不足以阻止請求）。​模塊被寫為PHP文件，因此，通常，模塊簽名被寫為PHP代碼。

CIDRAM模塊的一些很好的例子可以在這裡找到：
- https://github.com/CIDRAM/CIDRAM-Extras/tree/master/modules

編寫新模塊的模板可以在這裡找到：
- https://github.com/CIDRAM/CIDRAM-Extras/blob/master/modules/module_template.php

由於模塊是作為PHP文件編寫的，如果您對CIDRAM代碼庫有足夠的了解，則可以根據需要構建模塊，並根據需要編寫模塊簽名​（在合理範圍的什麼可以用PHP來完成內）。​但是，為了您自己的方便，並為了介於存在的模塊和您自己的之間好的理解，建議分析上面鏈接的模板，以便能夠使用它提供的結構和格式。

*注意：如果您不舒服使用PHP代碼，則不建議編寫自己的模塊。*

CIDRAM提供了一些用於模塊的功能，這將使編寫自己的模塊變得更簡單和容易。​有關此功能的信息如下所述。

#### 7.5 模塊功能

##### 7.5.0 『$Trigger』

模塊簽名通常使用`$Trigger`編寫。​在大多數情況下，為了編寫模塊，這個閉包比其他任何東西都重要。

`$Trigger`接受4個參數：`$Condition`、`$ReasonShort`、`$ReasonLong`（可選的）、和`$DefineOptions`（可選的）。

`$Condition`感實性被評估，和如果是true（真），簽名是『觸發』。​如果是false（假），簽名不是『觸發』。​`$Condition`通常包含PHP代碼來評估應該導致請求被阻止的條件。

當簽名被『觸發』時，`$ReasonShort`在『為什麼被阻止』字段中被引用。

`$ReasonLong`是一個可選消息，當用戶/客戶端被阻止時顯示給用戶/客戶端，解釋為什麼他們被阻止。​省略時默認為標準的『拒絕訪問』消息。

`$DefineOptions`是一個包含鍵/值對的可選數組，用於定義特定於請求實例的配置選項。​配置選項將在簽名被『觸發』時應用。

`$Trigger`當簽名是『觸發』時將返回true（真），當簽名不是『觸發』時將返回false（假）。

要在模塊中使用這個閉包，首先要記住從父範圍繼承它：
```PHP
$Trigger = $CIDRAM['Trigger'];
```

##### 7.5.1 『$Bypass』

簽名旁路通常使用`$Bypass`編寫。

`$Bypass`接受3個參數：`$Condition`、`$ReasonShort`、和`$DefineOptions`（可選的）。

`$Condition`感實性被評估，和如果是true（真），旁路是『觸發』。​如果是false（假），旁路不是『觸發』。​`$Condition`通常包含PHP代碼來評估應不該導致請求被阻止的條件。

當旁路被『觸發』時，`$ReasonShort`在『為什麼被阻止』字段中被引用。

`$DefineOptions`是一個包含鍵/值對的可選數組，用於定義特定於請求實例的配置選項。​配置選項將在旁路被『觸發』時應用。

`$Bypass`當旁路是『觸發』時將返回true（真），當旁路不是『觸發』時將返回false（假）。

要在模塊中使用這個閉包，首先要記住從父範圍繼承它：
```PHP
$Bypass = $CIDRAM['Bypass'];
```

##### 7.5.2 『$CIDRAM['DNS-Reverse']』

這可以用來獲取IP地址的主機名。​如果您想創建一個模塊來阻止主機名，這個閉包可能是有用的。

例子：
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

#### 7.6 模塊變量

模塊在其自己的範圍內執行，並且由模塊定義的任何變量將不能被其他模塊訪問，也不由父腳本，除非它們存儲在`$CIDRAM`數組中（模塊執行完成後，其他所有內容都將被擦洗）。

下面列出了一些可能對您的模塊有用的常見變量：

變量 | 說明
----|----
`$CIDRAM['BlockInfo']['DateTime']` | 當前日期和時間。
`$CIDRAM['BlockInfo']['IPAddr']` | 當前請求的IP地址。
`$CIDRAM['BlockInfo']['ScriptIdent']` | CIDRAM腳本版本。
`$CIDRAM['BlockInfo']['Query']` | 當前請求的查詢。
`$CIDRAM['BlockInfo']['Referrer']` | 當前請求的引用者（如果存在）。
`$CIDRAM['BlockInfo']['UA']` | 當前請求的用戶代理【user agent】。
`$CIDRAM['BlockInfo']['UALC']` | 當前請求的用戶代理【user agent】（小寫）。
`$CIDRAM['BlockInfo']['ReasonMessage']` | 當前請求被阻止時顯示給用戶/客戶端的消息。
`$CIDRAM['BlockInfo']['SignatureCount']` | 當前請求的觸發的簽名數量。
`$CIDRAM['BlockInfo']['Signatures']` | 針對當前請求觸發的任何簽名的參考信息。
`$CIDRAM['BlockInfo']['WhyReason']` | 針對當前請求觸發的任何簽名的參考信息。

---


### 8. <a name="SECTION8"></a>已知的兼容問題

下列軟件包和產品被發現與CIDRAM不兼容：
- __[Endurance Page Cache](https://github.com/CIDRAM/CIDRAM/issues/52)__
- __[Mix.com](https://github.com/CIDRAM/CIDRAM/issues/80)__

已提供模塊以確保以下軟件包和產品與CIDRAM兼容：
- __[BunnyCDN](https://github.com/CIDRAM/CIDRAM/issues/56)__

*也可以看看：​[兼容性圖表](https://maikuolan.github.io/Compatibility-Charts/)。*

---


### 9. <a name="SECTION9"></a>常見問題（FAQ）

- [什麼是『簽名』？](#WHAT_IS_A_SIGNATURE)
- [什麼是『CIDR』？](#WHAT_IS_A_CIDR)
- [什麼是『假陽性』？](#WHAT_IS_A_FALSE_POSITIVE)
- [CIDRAM可以阻止整個國家嗎？](#BLOCK_ENTIRE_COUNTRIES)
- [什麼是簽名更新頻率？](#SIGNATURE_UPDATE_FREQUENCY)
- [我在使用CIDRAM時遇到問題和我不知道該怎麼辦！​請幫忙！](#ENCOUNTERED_PROBLEM_WHAT_TO_DO)
- [因為CIDRAM，​我被阻止從我想訪問的網站！​請幫忙！](#BLOCKED_WHAT_TO_DO)
- [我想使用CIDRAM（在v2之前）與早於5.4.0的PHP版本；​您能幫我嗎？](#MINIMUM_PHP_VERSION)
- [我想使用CIDRAM（在v2期间）與早於7.2.0的PHP版本；​您能幫我嗎？](#MINIMUM_PHP_VERSION_V2)
- [我可以使用單個CIDRAM安裝來保護多個域嗎？](#PROTECT_MULTIPLE_DOMAINS)
- [我不想浪費時間安裝這個和確保它在我的網站上功能正常；我可以僱用您這樣做嗎？](#PAY_YOU_TO_DO_IT)
- [我可以聘請您或這個項目的任何開發者私人工作嗎？](#HIRE_FOR_PRIVATE_WORK)
- [我需要專家修改，​的定制，​等等；您能幫我嗎？](#SPECIALIST_MODIFICATIONS)
- [我是開發人員，​網站設計師，​或程序員。​我可以接受還是提供與這個項目有關的工作？](#ACCEPT_OR_OFFER_WORK)
- [我想為這個項目做出貢獻；我可以這樣做嗎？](#WANT_TO_CONTRIBUTE)
- [可以使用cron自動更新嗎？](#CRON_TO_UPDATE_AUTOMATICALLY)
- [什麼是『違規』？](#WHAT_ARE_INFRACTIONS)
- [CIDRAM可以阻止主機名？](#BLOCK_HOSTNAMES)
- [在『default_dns』中我可以使用什麼？](#WHAT_CAN_I_USE_FOR_DEFAULT_DNS)
- [我可以使用CIDRAM保護網站以外的東西嗎（例如，電子郵件服務器，FTP，SSH，IRC，等）？](#PROTECT_OTHER_THINGS)
- [如果我在使用CDN或緩存服務的同時使用CIDRAM，會發生問題嗎？](#CDN_CACHING_PROBLEMS)
- [CIDRAM會保護我的網站免受DDoS攻擊嗎？](#DDOS_ATTACKS)
- [當我通過更新頁面啟用或禁用模塊或簽名文件時，它會在配置中它們將按字母數字排序。​我可以改變他們排序的方式嗎？](#CHANGE_COMPONENT_SORT_ORDER)

#### <a name="WHAT_IS_A_SIGNATURE"></a>什麼是『簽名』？

在CIDRAM的上下文中，​『簽名』是一些數據，​它表示/識別我們正在尋找的東西，​通常是IP地址或CIDR，​並包含一些說明，​告訴CIDRAM最好的回應方法當它遇到我們正在尋找的。​CIDRAM的典型簽名如下所示：

對於『簽名文件』：

`1.2.3.4/32 Deny Generic`

對於『模塊』：

```PHP
$Trigger(strpos($CIDRAM['BlockInfo']['UA'], 'Foobar') !== false, 'Foobar-UA', 'User agent "Foobar" not allowed.');
```

*注意：『簽名文件』的簽名，和『模塊』的簽名，不是一回事。*

經常（但不總是），​簽名是捆綁在一起，​形成『簽名章節』，​經常伴隨評論，​標記，​和/或相關元數據。​這可以用於為簽名提供附加上下文和/或附加說明。

#### <a name="WHAT_IS_A_CIDR"></a>什麼是『CIDR』？

『CIDR』 是 『Classless Inter-Domain Routing』 的首字母縮寫 （『無類別域間路由』） *【[1](https://zh.wikipedia.org/wiki/%E6%97%A0%E7%B1%BB%E5%88%AB%E5%9F%9F%E9%97%B4%E8%B7%AF%E7%94%B1), [2](https://whatismyipaddress.com/cidr)】*。​這個首字母縮寫用於這個包的名稱，​『CIDRAM』，​是 『Classless Inter-Domain Routing Access Manager』 的首字母縮寫 （『無類別域間路由訪問管理器』）。

然而，​在CIDRAM的上下文中（如，​在本文檔中，​在CIDRAM的討論中，​或在CIDRAM語言數據中），​當『CIDR』（單數）或『CIDRs』（複數）被提及時（因此當我們用這些詞作為名詞在自己的權利，​而不作為首字母縮寫），​我們的意圖是一個子網，​用CIDR表示法表示。​使用CIDR/CIDRs而不是子網的原因是澄清它是用CIDR表示法表示的子網是我們的意思 （因為子網可以用幾種不同的方式表達）。​因此，​CIDRAM可以被認為是『子網訪問管理器』。

這個雙重含義可能看起來很歧義，​但這個解釋並提供上下文應該有助於解決這個歧義。

#### <a name="WHAT_IS_A_FALSE_POSITIVE"></a>什麼是『假陽性』？

術語『假陽性』（*或者：『假陽性錯誤』；『虛驚』*；英語：*false positive*； *false positive error*； *false alarm*），​很簡單地描述，​和在一個廣義上下文，​被用來當測試一個因子，​作為參考的測試結果，​當結果是陽性（即：因子被確定為『陽性』，​或『真』），​但預計將為（或者應該是）陰性（即：因子，​在現實中，​是『陰性』，​或『假』）。​一個『假陽性』可被認為是同樣的『哭狼』 (其中，​因子被測試是是否有狼靠近牛群，​因子是『假』由於該有沒有狼靠近牛群，​和因子是報告為『陽性』由牧羊人通過叫喊『狼，​狼』），​或類似在醫學檢測情況，​當患者被診斷有一些疾病，​當在現實中，​他們沒有疾病。

一些相關術語是『真陽性』，​『真陰性』和『假陰性』。​一個『真陽性』指的是當測試結果和真實因子狀態都是『真』（或『陽性』），​和一個『真陰性』指的是當測試結果和真實因子狀態都是『假』（或『陰性』）；一個『真陽性』或『真陰性』被認為是一個『正確的推理』。​對立面『假陽性』是一個『假陰性』；一個『假陰性』指的是當測試結果是『陰性』（即：因子被確定為『陰性』，​或『假』），​但預計將為（或者應該是）陽性（即：因子，​在現實中，​是『陽性』，​或『真』）。

在CIDRAM的上下文，​這些術語指的是CIDRAM的簽名和什麼/誰他們阻止。​當CIDRAM阻止一個IP地址由於惡劣的，​過時的，​或不正確的簽名，​但不應該這樣做，​或當它這樣做為錯誤的原因，​我們將此事件作為一個『假陽性』。​當CIDRAM未能阻止IP地址該應該已被阻止，​由於不可預見的威脅，​缺少簽名或不足簽名，​我們將此事件作為一個『檢測錯過』（同樣的『假陰性』）。

這可以通過下表來概括：

&nbsp; | CIDRAM不應該阻止IP地址 | CIDRAM應該阻止IP地址
---|---|---
CIDRAM不會阻止IP地址 | 真陰性（正確的推理） | 檢測錯過（同樣的『假陰性』）
CIDRAM會阻止IP地址 | __假陽性__ | 真陽性（正確的推理）

#### <a name="BLOCK_ENTIRE_COUNTRIES"></a>CIDRAM可以阻止整個國家嗎？

它可以。​實現這個最簡單的方法是安裝一些由Macmathan提供的可選的國家阻止列表。​這可以通過直接從前端更新頁面的幾個簡單的點擊完成，​或，​如果您希望前端保持停用狀態，​通過直接從下載頁面下載它們。​通過直接從 **[可選的國家阻止列表下載頁面](https://bitbucket.org/macmathan/blocklists)** 下載它們，​上傳他們到vault，​在相關配置指令中引用它們的名稱。

#### <a name="SIGNATURE_UPDATE_FREQUENCY"></a>什麼是簽名更新頻率？

更新頻率根據相關的簽名文件而有所不同。​所有的CIDRAM簽名文件的維護者通常盡量保持他們的簽名為最新，​但是因為我們所有人都有各種其他承諾，​和因為我們的生活超越了項目，​和因為我們不得到經濟補償/付款為我們的項目的努力，​無法保證精確的更新時間表。​通常，​簽名被更新每當有足夠的時間，​和維護者嘗試根據必要性和根據范圍之間的變化頻率確定優先級。​幫助總是感謝，​如果您願意提供任何。

#### <a name="ENCOUNTERED_PROBLEM_WHAT_TO_DO"></a>我在使用CIDRAM時遇到問題和我不知道該怎麼辦！​請幫忙！

- 您使用軟件的最新版本嗎？​您使用簽名文件的最新版本嗎？​如果這兩個問題的答案是不，​嘗試首先更新一切，​然後檢查問題是否仍然存在。​如果它仍然存在，​繼續閱讀。
- 您檢查過所有的文檔嗎？​如果沒有做，​請這樣做。​如果文檔不能解決問題，​繼續閱讀。
- 您檢查過[issues頁面](https://github.com/CIDRAM/CIDRAM/issues)嗎？​檢查是否已經提到了問題。​如果已經提到了，​請檢查是否提供了任何建議，​想法或解決方案。​按照需要嘗試解決問題。
- 如果問題仍然存在，請通過在issues頁面上創建新issue尋求幫助。

#### <a name="BLOCKED_WHAT_TO_DO"></a>因為CIDRAM，​我被阻止從我想訪問的網站！​請幫忙！

CIDRAM使網站所有者能夠阻止不良流量，​但網站所有者有責任為自己決定如何使用CIDRAM。​在關於默認簽名文件假陽性的情況下，​可以進行校正。​但關於從特定網站解除阻止，​您需要與相關網站的所有者進行溝通。​當進行校正時，​至少，​他們需要更新他們的簽名文件和/或安裝。​在其他情況下（例如，​當他們修改了他們的安裝，​當他們創建自己的自定義簽名，​等等），​解決您的問題的責任完全是他們的，​並完全不在我們的控制之內。

#### <a name="MINIMUM_PHP_VERSION"></a>我想使用CIDRAM（在v2之前）與早於5.4.0的PHP版本；​您能幫我嗎？

不能。PHP >= 5.4.0是CIDRAM < v2的最低要求。

#### <a name="MINIMUM_PHP_VERSION_V2"></a>我想使用CIDRAM（在v2期间）與早於7.2.0的PHP版本；​您能幫我嗎？

不能。PHP >= 7.2.0是CIDRAM v2的最低要求。

*也可以看看：​[兼容性圖表](https://maikuolan.github.io/Compatibility-Charts/)。*

#### <a name="PROTECT_MULTIPLE_DOMAINS"></a>我可以使用單個CIDRAM安裝來保護多個域嗎？

可以。​CIDRAM安裝未綁定到特定域，​因此可以用來保護多個域。​通常，​當CIDRAM安裝保護只一個域，​我們稱之為『單域安裝』，​和當CIDRAM安裝保護多個域和/或子域，​我們稱之為『多域安裝』。​如果您進行多域安裝並需要使用不同的簽名文件為不同的域，​或需要不同配置CIDRAM為不同的域，​這可以做到。​加載配置文件後（`config.ini`），​CIDRAM將尋找『配置覆蓋文件』特定於所請求的域（`xn--cjs74vvlieukn40a.tld.config.ini`），​並如果發現，​由配置覆蓋文件定義的任何配置值將用於執行實例而不是由配置文件定義的配置值。​配置覆蓋文件與配置文件相同，​並通過您的決定，​可能包含CIDRAM可用的所有配置指令，​或任何必需的章節當需要。​配置覆蓋文件根據它們旨在的域來命名（所以，​例如，​如果您需要一個配置覆蓋文件為域，​`http://www.some-domain.tld/`，​它的配置覆蓋文件應該被命名`some-domain.tld.config.ini`，​和它應該放置在`vault`與配置文件，​`config.ini`）。​域名是從標題`HTTP_HOST`派生的；『www』被忽略。

#### <a name="PAY_YOU_TO_DO_IT"></a>我不想浪費時間安裝這個和確保它在我的網站上功能正常；我可以僱用您這樣做嗎？

也許。​這是根據具體情況考慮的。​告訴我們您需要什麼，​您提供什麼，​和我們會告訴您是否可以幫忙。

#### <a name="HIRE_FOR_PRIVATE_WORK"></a>我可以聘請您或這個項目的任何開發者私人工作嗎？

*參考上面。​*

#### <a name="SPECIALIST_MODIFICATIONS"></a>我需要專家修改，​的定制，​等等；您能幫我嗎？

*參考上面。​*

#### <a name="ACCEPT_OR_OFFER_WORK"></a>我是開發人員，​網站設計師，​或程序員。​我可以接受還是提供與這個項目有關的工作？

您可以。​我們的許可證並不禁止這一點。

#### <a name="WANT_TO_CONTRIBUTE"></a>我想為這個項目做出貢獻；我可以這樣做嗎？

您可以。​對項目的貢獻是歡迎。​有關詳細信息，​請參閱『CONTRIBUTING.md』。

#### <a name="CRON_TO_UPDATE_AUTOMATICALLY"></a>可以使用cron自動更新嗎？

您可以。​前端有內置了API，外部腳本可以使用它與更新頁面進行交互。​一個單獨的腳本，『[Cronable](https://github.com/Maikuolan/Cronable)』，是可用，它可以由您的cron manager或cron scheduler程序使用於自動更新此和其他支持的包（此腳本提供自己的文檔）。

#### <a name="WHAT_ARE_INFRACTIONS"></a>什麼是『違規』？

『違規』決定何時還沒有被任何特定簽名文件阻止的IP應該開始被阻止以將來的任何請求，​他們與IP跟踪密切相關。​一些功能和模塊允許請求由於起源IP以外的原因被阻塞（例如，spambot或hacktool用戶代理【user agent】，危險的查詢，假的DNS，等等），當發生這種情況時，可能會發生『違規』。​這提供了一種識別不需要的請求的IP地址的方法（如果被任何特定的簽名文件的不被阻止已經）。​違規通常與IP被阻止的次數是1比1，但不總是（在嚴重事件中，可能會產生大於1的違規值，如果『track_mode』是假的【false】，對於僅由簽名文件觸發的阻止事件，不會發生違規）。

#### <a name="BLOCK_HOSTNAMES"></a>CIDRAM可以阻止主機名？

可以做。您將需要創建一個自定義模塊文件。 *看到：[基本概念（對於模塊）](#MODULE_BASICS)*.

#### <a name="WHAT_CAN_I_USE_FOR_DEFAULT_DNS"></a>在『default_dns』中我可以使用什麼？

通常，任何可靠的DNS服務器的IP應該就足夠了。​如果您正在尋找建議，[public-dns.info](https://public-dns.info/)和[OpenNIC](https://servers.opennic.org/)提供已知的公共DNS服務器的廣泛列表。​或者，請參閱下表：

IP | 操作者
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

*注意：​我對任何列出的DNS服務或其他方式的隱私慣例，安全性，功效和可靠性不做任何聲明或保證。​請在做出關於他們的決定時做您自己的研究。*

#### <a name="PROTECT_OTHER_THINGS"></a>我可以使用CIDRAM保護網站以外的東西嗎（例如，電子郵件服務器，FTP，SSH，IRC，等）？

您可以（在法律意義上說），但不應該（在技術和實際意義上）。​我們的許可證不限制哪些技術實施CIDRAM，但CIDRAM是一種WAF（Web應用程序防火牆），一直旨在保護網站。​因為它沒有考慮到其他技術，所以它不太可能有效或為其他技術提供可靠的保護，實施可能會很困難，並且假陽性和檢測錯過的風險非常高。

#### <a name="CDN_CACHING_PROBLEMS"></a>如果我在使用CDN或緩存服務的同時使用CIDRAM，會發生問題嗎？

也許。​這取決於相關服務的性質以及您如何使用它。​通常，如果您只緩存靜態資產（例如，圖像，CSS，等；任何通常不會隨時間變化的東西），則不應該有任何問題。​但是，如果您要緩存的數據通常會在請求時動態生成，或者如果您正在緩存POST請求的結果，那麼可能會有問題（這基本上會使您的網站及其環境成為強制靜態，並且CIDRAM不太可能在強制靜態環境中提供任何有意義的好處）。​CIDRAM也可能有特定的配置要求，具體取決於您使用的CDN或緩存服務（您需要確保為您正在使用的特定CDN或緩存服務正確配置CIDRAM）。​未能正確配置CIDRAM可能會導致嚴重假陽性和檢測錯過。

#### <a name="DDOS_ATTACKS"></a>CIDRAM會保護我的網站免受DDoS攻擊嗎？

總之：不，它不能。

更詳細地說闡述：CIDRAM將有助於減少不需要的流量的可能造成的影響，為您的網站（從而降低帶寬成本），為您的硬件（例如，您的服務器處理請求的能力），並可以幫助減少各種其他潛在的負面影響。​然而，為了理解這個問題，必須記住兩件重要的事情。

首先，CIDRAM是一個PHP包，因此可以在安裝PHP的機器上運行。​這意味著CIDRAM只能在服務器收到請求後才能看到並阻止請求。​其次，有效的DDoS緩解應該在請求到達DDoS攻擊所針對的服務器之前對其進行過濾。​理想情況下，DDoS攻擊應該在能夠首先到達目標服務器之前通過能夠丟棄或重新路由與攻擊相關的流量的解決方案來檢測和緩解。

這可以使用專用的內部部署硬件解決方案，基於雲的解決方案，如專用的DDoS緩解服務，通過耐DDoS網絡路由域名的DNS，基於雲的過濾，或者它們的一些組合實施。​無論如何，這個問題有點太複雜，不能僅僅用一到兩個段落來解釋，所以如果這是您想追求的主題，我會建議您做進一步的研究。​當DDoS攻擊的本質被正確理解時，這個答案會更有意義。

#### <a name="CHANGE_COMPONENT_SORT_ORDER"></a>當我通過更新頁面啟用或禁用模塊或簽名文件時，它會在配置中它們將按字母數字排序。​我可以改變他們排序的方式嗎？

這個有可能。​如果您需要強制某些文件以特定順序執行，您可以在列出配置指令的位置中的在他們的名字之前添加一些任意數據，並用冒號分隔。​當更新頁面隨後再次對文件進行排序時，這個添加的任意數據會影響排序順序，因此導致它們按照您想要的順序執行，並且不需要重命名它們。

例如，假设配置指令包含如下列出的文件：

`file1.php,file2.php,file3.php,file4.php,file5.php`

如果您想首先執行`file3.php`，您可以在文件名前添加`aaa:`或類似：

`file1.php,file2.php,aaa:file3.php,file4.php,file5.php`

然後，如果啟用了新文件`file6.php`，當更新頁面再次對它們進行排序時，它應該像這樣結束：

`aaa:file3.php,file1.php,file2.php,file4.php,file5.php,file6.php`

當文件禁用時的情況是相同的。​相反，如果您希望文件最後執行，您可以在文件名前添加`zzz:`或類似。​在任何情況下，您都不需要重命名相關文件。

---


### 11. <a name="SECTION11"></a>法律信息

#### 11.0 章節前言

本文檔章節描述了有關該軟件包的使用和實施的可能法律考慮事項，並提供一些基本的相關信息。​這對於一些用戶來說可能很重要，作為確保遵守其運營所在國家可能存在的任何法律要求的一種手段。​一些用戶可能需要根據這些信息調整他們的網站政策。

首先，請認識到我（軟件包作者）不是律師或合格的法律專業人員。​因此，我無法合法提供法律建議。​此外，在某些情況下，不同國家和地區的具體法律要求可能會有所不同。​這些不同的法律要求有時可能會相互矛盾​（例如：支持[隱私權](https://zh.wikipedia.org/wiki/%E9%9A%B1%E7%A7%81%E6%AC%8A_(%E8%87%BA%E7%81%A3))和[被遺忘權](https://zh.wikipedia.org/wiki/%E8%A2%AB%E9%81%BA%E5%BF%98%E6%AC%8A)的國家，與支持擴展數據保留的國家相比）。​還要考慮到對軟件包的訪問不限於特定的國家或轄區，因此，軟件包用戶群很可能在地理上多樣化。​這些觀點認為，我無法說明在所有方面對所有用戶『符合法律』意味著什麼。​不過，我希望這裡的信息能夠幫助您自己決定您必須做些什麼為了在軟件包的上下文中符合法律。​如果您對此處的信息有任何疑問或擔憂，或者您需要從法律角度提供更多幫助和建議，我會建議諮詢合格的法律專業人員。

#### 11.1 法律責任

此軟件包不提供任何擔保（這已由包許可證提及）。​這包括（但不限於）所有責任範圍。​為了您的方便，該軟件包已提供給您。​希望它會有用，它會為您帶來一些好處。​但是，使用或實施該軟件包是您自己的選擇。​您不是被迫使用或實施該軟件包，但是當您這樣做時，您需要對該決定負責。​我，和其他軟件包貢獻者，對於您的決定的後果不承擔法律責任，無論是直接的，間接的，暗示的，還是其他方式。

#### 11.2 第三方

取決於其確切的配置和實施，在某些情況下，該軟件包可能與第三方進行通信和共享信息。​在某些情況下，某些轄區可能會將此信息定義為『個人身份信息』（PII）。

這些信息如何被這些第三方使用，是受這些第三方制定的各種政策的約束，並且超出了本文檔的範圍。​但是，在所有這些情況下，與這些第三方共享信息可能被禁用。​在所有這些情況下，如果您選擇啟用它，則有責任研究您可能遇到的任何問題（如果您擔心這些第三方的隱私，安全，和PII使用情況）。​如果存在任何疑問，或者您對PII方面的這些第三方的行為不滿意，最好禁用與這些第三方分享的所有信息。

為了透明的目的，共享信息的類型，以及與誰共享，如下所述。

##### 11.2.0 主機名查找

如果您使用任何旨在與主機名配合使用的功能或模塊（例如，​『壞主機阻塞模塊』，​『tor project exit nodes block module』，​『搜索引擎驗證』），​CIDRAM需要能夠以某種獲得入站請求的主機名。​通常，它通過請求來自DNS服務器的入站請求的IP地址的主機名來執行此操作，或者通過安裝CIDRAM的系統提供的功能請求信息（這通常被稱為『主機名查找』）。​默認定義的DNS服務器屬於[Google DNS](https://dns.google.com/)服務（但可以通過配置輕鬆更改）。​與之交流的確切服務是可配置的，並取決於您如何配置軟件包。​在使用安裝CIDRAM的系統提供的功能的情況下，您需要聯繫您的系統管理員以確定哪些為主機名查找的路由使用。​通過避免受影響的模塊或根據您的需要修改軟件包配置，可以防止CIDRAM中的主機名查找。

*相關配置指令：*
- `general` -> `default_dns`
- `general` -> `search_engine_verification`
- `general` -> `social_media_verification`
- `general` -> `force_hostname_lookup`
- `general` -> `allow_gethostbyaddr_lookup`

##### 11.2.1 網絡字體

一些自定義主題，以及CIDRAM前端的標準UI（『用戶界面』），和『拒絕訪問』頁面可能出於審美原因使用網絡字體。​網絡字體默認是禁用，但啟用後，用戶的瀏覽器和託管網絡字體的服務之間會發生直接通信。​這可能涉及傳遞信息，例如用戶的IP地址，用戶代理，操作系統，以及請求可用的其他詳細信息。​大部分這些網絡字體都由[Google Fonts](https://fonts.google.com/)服務託管。

*相關配置指令：*
- `general` -> `disable_webfonts`

##### 11.2.2 搜索引擎驗證和社交媒體驗證

當啟用搜索引擎驗證時，CIDRAM嘗試執行『正向DNS查找』以驗證聲稱源自搜索引擎的請求是否真實。​同樣，當啟用社交媒體驗證時，CIDRAM對為社交媒體請求做同樣的事情。​為此，它使用[Google DNS](https://dns.google.com/)服務嘗試從這些入站請求的主機名解析IP地址（在這個過程中，這些入站請求的主機名與服務共享）。

*相關配置指令：*
- `general` -> `search_engine_verification`
- `general` -> `social_media_verification`

##### 11.2.3 GOOGLE reCAPTCHA

CIDRAM可選的支持Google reCAPTCHA，為用戶提供了一種通過完成reCAPTCHA實例繞過『拒絕訪問』頁面的方式​（關於此功能的更多信息在前面的文檔中有介紹，特別是在配置章節）。​Google reCAPTCHA需要API密鑰才能正常工作，因此默認情況下禁用。​可以通過在包配置中定義所需的API密鑰來啟用它。​啟用後，用戶的瀏覽器與reCAPTCHA服務之間會進行直接通信。​這可能涉及傳遞信息，例如用戶的IP地址，用戶代理，操作系統以及請求可用的其他詳細信息。​在驗證reCAPTCHA實例的有效性並驗證它是否成功完成時，用戶的IP地址也可以在CIDRAM和reCAPTCHA服務之間的通信中共享。

*相關配置指令：在『recaptcha』配置類別下列出的任何內容。*

##### 11.2.4 STOP FORUM SPAM 【停止論壇垃圾郵件】

[Stop Forum Spam](https://www.stopforumspam.com/)是一個輝煌的，免費提供的服務，可以幫助保護論壇，博客，和網站免受垃圾郵件製造者。​它提供了一個已知垃圾郵件發送者的數據庫，以及一個可用來檢查數據庫中是否列出IP地址，用戶名或電子郵件地址的API。

CIDRAM提供了一個可選模塊，它使用API​來檢查入站請求的IP地址是否屬於可疑垃圾郵件發送者。​默認情況下該模塊不是安裝，但如果選擇安裝該模塊，則可以根據模塊的預期用途將用戶的IP地址與Stop Forum Spam【停止論壇垃圾郵件】API共享。​安裝模塊時，當入站請求請求的資源是CIDRAM識別為垃圾郵件發送者經常目標的資源時（如登錄頁面，註冊頁面，電子郵件驗證頁面，評論表單，等等），CIDRAM就會與此API通信。

#### 11.3 日誌記錄

由於多種原因，日誌記錄是CIDRAM的重要組成部分。​當未記錄導致它們的阻止事件時，可能難以診斷和解決假陽性。​當未記錄阻止事件時，可能很難確定CIDRAM在某些情況下的表現如何，而且可能很難確定其不足之處，以及可能需要更改哪些配置或簽名，以使其繼續按預期運行。​無論如何，一些用戶可能不想要記錄，並且它仍然是完全可選的。​在CIDRAM中，默認情況下日誌記錄是禁用。​要啟用它，必須相應地配置CIDRAM。

另外，如果日誌記錄在法律上是允許的，並且在法律允許的範圍內（例如，可記錄的信息類型，多長時間，在什麼情況下），可以變化，具體取決於管轄區域和CIDRAM的實施上下文（例如，如果您是個人或公司實體經營，如果您在商業或非商業基礎上運營，等等）。​因此，仔細閱讀本節可能對您有用。

CIDRAM可以執行多種類型的日誌記錄。​不同類型的日誌記錄涉及不同類型的信息，出於各種原因。

##### 11.3.0 阻止事件

CIDRAM可以執行的主要日誌記錄類型與『阻止事件』有關。​當CIDRAM阻止請求時會發生這種日誌記錄類型，並且可以以三種不同的格式提供：
- 人類可讀的日誌文件。
- Apache風格的日誌文件。
- 序列化日誌文件。

記錄到人類可讀日誌文件的阻止事件通常看起來像這樣（作為示例）：

```
ID： 1234
腳本版本： CIDRAM v1.6.0
日期/時間： Day, dd Mon 20xx hh:ii:ss +0000
IP地址： x.x.x.x
主機名： dns.hostname.tld
簽名計數： 1
簽名參考： x.x.x.x/xx
為什麼被阻止： 雲服務 ("網絡名字", Lxx:Fx, [XX])!
用戶代理： Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.181 Safari/537.36
重建URI： http://your-site.tld/index.php
reCAPTCHA狀態： 打開。
```

記錄到Apache樣式的日誌文件中的同一阻止事件看起來像這樣：

```
x.x.x.x - - [Day, dd Mon 20xx hh:ii:ss +0000] "GET /index.php HTTP/1.1" 200 xxxx "-" "Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.181 Safari/537.36"
```

阻止事件記錄通常包括以下信息：
- 阻止事件的ID號碼。
- 目前正在使用的CIDRAM版本。
- 阻止事件發生的日期和時間。
- 被阻止請求的IP地址。
- 被阻止請求的IP地址的主機名（如果可用）。
- 請求觸發的簽名數。
- 觸發的簽名引用。
- 引用阻止事件的原因和一些基本的相關調試信息。
- 被阻止請求的用戶代理（即，請求實體如何向請求標識自己）。
- 重建最初請求的資源的標識符。
- 當前請求的reCAPTCHA狀態（相關時）。

*負責此類日誌記錄的配置指令，適用於以下三種格式中的每一種：*
- `general` -> `logfile`
- `general` -> `logfileApache`
- `general` -> `logfileSerialized`

當這些指令保留為空時，這種類型的日誌記錄將保持禁用狀態。

##### 11.3.1 reCAPTCHA日誌記錄

此類日誌記錄特定於reCAPTCHA實例，僅在用戶嘗試完成reCAPTCHA實例時才會發生。

reCAPTCHA日誌條目包含嘗試完成reCAPTCHA實例的用戶的IP地址，嘗試發生的日期和時間以及reCAPTCHA狀態。​reCAPTCHA日誌條目通常看起來像這樣（作為示例）：

```
IP地址：x.x.x.x - Date/Time: Day, dd Mon 20xx hh:ii:ss +0000 - reCAPTCHA狀態：成功！
```

*負責reCAPTCHA日誌記錄的配置指令是：*
- `recaptcha` -> `logfile`

##### 11.3.2 前端日誌記錄

此類日誌記錄涉及前端登錄嘗試，僅在用戶嘗試登錄前端時才會發生（假設啟用了前端訪問）。

前端日誌條目包含嘗試登錄的用戶的IP地址，嘗試發生的日期和時間以及的結果（登錄成功或失敗）。​前端日誌條目通常看起來像這樣（作為示例）：

```
x.x.x.x - Day, dd Mon 20xx hh:ii:ss +0000 - "admin" - 已登錄。
```

*負責前端日誌記錄的配置指令是：*
- `general` -> `FrontEndLog`

##### 11.3.3 日誌輪換

您可能希望在一段時間後清除日誌，或者可能被要求依法執行（即，您在法律上允許保留日誌的時間可能受法律限制）。​您可以通過在程序包配置指定的日誌文件名中包含日期/時間標記（例如，`{yyyy}-{mm}-{dd}.log`），​然後啟用日誌輪換來實現此目的（日誌輪換允許您在超出指定限制時對日誌文件執行某些操作）。

例如：如果法律要求我在30天后刪除日誌，我可以在我的日誌文件的名稱中指定`{dd}.log`（`{dd}`代表天），將`log_rotation_limit`的值設置為30，並將`log_rotation_action`的值設置為`Delete`。

相反，如果您需要長時間保留日誌，你可以選擇完全不使用日誌輪換，或者你可以將`log_rotation_action`的值設置為`Archive`，以壓縮日誌文件，從而減少它們佔用的磁盤空間總量。

*相關配置指令：*
- `general` -> `log_rotation_limit`
- `general` -> `log_rotation_action`

##### 11.3.4 日誌截斷

如果這是您想要做的事情，也可以在超過特定大小時截斷個別日誌文件。

*相關配置指令：*
- `general` -> `truncate`

##### 11.3.5 IP地址『PSEUDONYMISATION』

首先，如果您不熟悉這個術語，『pseudonymisation』是指處理個人數據，使其不能在沒有補充信息的情況下被識別為屬於任何特定的『數據主體』，並規定這些補充信息分開保存，採取技術和組織措施以確保個人數據不能被識別給任何自然人。

以下資源可以幫助更詳細地解釋它：
- [[trust-hub.com] What is pseudonymisation?](https://www.trust-hub.com/news/what-is-pseudonymisation/)
- [[Wikipedia] Pseudonymization](https://en.wikipedia.org/wiki/Pseudonymization)

在某些情況下，您可能在法律上要求對收集，處理，或存儲的任何PII進行『pseudonymise』或『anonymise』。​雖然這個概念已經存在了相當長的一段時間，但GDPR/DSGVO提到，並特別鼓勵『pseudonymisation』。

當記錄它們時，CIDRAM可以對IP地址進行pseudonymise，如果這是您想做的事情。​當這個情況發生時，IPv4地址的最後八位字節，以及IPv6地址的第二部分之後的所有內容，將由『x』表示（有效地將IPv4地址四捨五入到它的第24個子網因素的初始地址，和將IPv6地址四捨五入到它的第32個子網因素的初始地址）。

*注意：CIDRAM的IP地址pseudonymisation過程不會影響CIDRAM的IP跟踪功能。​如果這對您來說是個問題，最好完全禁用IP跟踪。​這可以通過將`track_mode`設置為`false`並避免使用任何模塊來實現。*

*相關配置指令：*
- `signatures` -> `track_mode`
- `legal` -> `pseudonymise_ip_addresses`

##### 11.3.6 省略日誌信息

如果要防止完全記錄特定類型的信息，也可以這樣做。​CIDRAM提供配置指令來控制IP地址，主機名，和用戶代理是否包含在日誌中。​默認情況下，所有這三個數據點都包含在日誌中（如果可用）。​將任何這些配置指令設置為`true`將省略日誌中的相應信息。

*注意：當完全從日誌中省略IP地址時，沒有理由對IP地址進行pseudonymise。*

*相關配置指令：*
- `legal` -> `omit_ip`
- `legal` -> `omit_hostname`
- `legal` -> `omit_ua`

##### 11.3.7 統計

CIDRAM可選擇跟踪統計信息，例如自特定時間以來發生的阻止事件或reCAPTCHA實例的總數。​默認情況下此功能是禁用，但可以通過程序包配置啟用此功能。​此功能僅跟踪發生的事件總數，不包括有關特定事件的任何信息（因此，不應被視為PII）。

*相關配置指令：*
- `general` -> `statistics`

##### 11.3.8 加密

CIDRAM不[加密](https://zh.wikipedia.org/wiki/%E5%8A%A0%E5%AF%86)其緩存或任何日誌信息。​可能會在將來引入緩存和日誌加密，但目前沒有任何具體的計劃。​如果您擔心未經授權的第三方獲取可能包含PII或敏感信息（如緩存或日誌）的CIDRAM部分的訪問權限，我建議不要將CIDRAM安裝在可公開訪問的位置（例如，在標準`public_html`或等效目錄之外【可用於大多數標準網絡服務器】安裝CIDRAM），​也我建議對安裝目錄強制執行適當的限制權限（特別是對於vault目錄）。​如果這還不足以解決您的疑慮，應該配置CIDRAM為不會首先收集或記錄引起您關注的信息類型（例如，通過禁用日誌記錄）。

#### 11.4 COOKIE

CIDRAM在其代碼庫中的兩個點設置cookie。​首先，當用戶成功完成reCAPTCHA實例時（這假定`lockuser`設置為`true`），CIDRAM設置cookie，以便能夠在後續請求中記住用戶已經完成了reCAPTCHA實例，這樣就不需要不斷要求用戶在後續請求中完成reCAPTCHA實例。​其次，當用戶成功登錄前端時，CIDRAM設置cookie以便能夠在後續請求中的記住用戶（即，cookie用於向登錄會話驗證用戶身份）。

在這兩種情況下，cookie警告顯著顯示（適用時），警告用戶如果他們參與相關操作將設置cookie。 Cookie不會在代碼庫中的任何其他位置設置。

*注意：CIDRAM針對reCAPTCHA的『invisible』API的特定實現可能與某些司法管轄區的cookie法律不兼容，任何受這些法律約束的網站都應該避免這個API。​選擇使用『V2』API，或者簡單地完全禁用reCAPTCHA，可能更為可取。*

*相關配置指令：*
- `general` -> `disable_frontend`
- `recaptcha` -> `lockuser`
- `recaptcha` -> `api`

#### 11.5 市場營銷和廣告

CIDRAM不收集或處理任何信息用於營銷或廣告目的，既不銷售也不從任何收集或記錄的信息中獲利。​CIDRAM不是商業企業，也不涉及任何商業利益，因此做這些事情沒有任何意義。​自項目開始以來就一直如此，今天仍然如此。​此外，做這些事情會對整個項目的精神和預期目的產生反作用，並且只要我繼續維護項目，永遠不會發生。

#### 11.6 隱私政策

在某些情況下，您可能需要依法在您網站的所有頁面和部分上清楚地顯示您的隱私政策鏈接。​這可能為了確保用戶充分了解您的隱私慣例，收集的個人身份信息類型以及您打算如何使用它的是很重要。​為了能夠在CIDRAM的『拒絕訪問』頁面上包含這樣的鏈接，提供了配置指令來指定隱私策略的URL。

*注意：​強烈建議您的隱私政策頁面不放在CIDRAM的保護之後。​如果CIDRAM保護您的隱私政策頁面，並且被CIDRAM阻止的用戶點擊隱私政策的鏈接，他們將再次被阻止，並且無法看到您的隱私政策。​理想情況下，您應鏈接到您的隱私政策的靜態副本，例如HTML頁面或純文本文件，該文件不受CIDRAM保護。*

*相關配置指令：*
- `legal` -> `privacy_policy`

#### 11.7 GDPR/DSGVO

『通用數據保護條例』（GDPR）是歐盟法規，自2018年5月25日起生效。​該法規的主要目標是向歐盟公民和居民提供有關其個人數據的控制權，並統一歐盟內有關隱私和個人數據的法規。

該法規包含有關處理任何歐盟『數據主體』（任何已識別或可識別的自然人）的『個人身份信息』（PII）的具體規定。​為了符合條例，『企業』（按照法規的定義），和任何相關的系統和流程必須默認實現『隱私設計』，​必須使用盡可能高的隱私設置，​必須對任何存儲或處理的信息實施必要的保護措施（數據的 pseudonymisation 或完整 anonymisation ），​必須明確無誤地聲明他們收集的數據類型，​他們如何處理數據，​出於何種原因，​他們保留多長時間，​以及他們是否與任何第三方分享這些數據，​與第三方共享的數據類型，​為什麼，​等等。

只有按照條例有合法依據才能處理數據。​一般而言，這意味著為了在合法基礎上處理數據主體的數據，必須遵守法律義務，或者僅在從數據主體獲得明確，明智，明確的同意之後才進行處理。

因為條例的各個方面可能會及時演變，並為了避免過時信息的傳播，從權威來源中學習可能會更好的，而不是簡單地在包文檔中包含相關信息（這個信息可能最終會過時）。

一些推薦的資源用於了解更多信息：
- [关于欧盟GDPR隐私合规，中国数字营销人不得不知的9大问题](http://www.adexchanger.cn/top_news/28813.html)
- [史上最严的隐私条例出台，2018年开始执行](https://zhuanlan.zhihu.com/p/20865602)
- [《欧盟数据保护条例》对中国企业的影响 —- 以阿里巴巴集团为例](https://spiegeler.com/%E3%80%8A%E6%AC%A7%E7%9B%9F%E6%95%B0%E6%8D%AE%E4%BF%9D%E6%8A%A4%E6%9D%A1%E4%BE%8B%E3%80%8B%E5%AF%B9%E4%B8%AD%E5%9B%BD%E4%BC%81%E4%B8%9A%E7%9A%84%E5%BD%B1%E5%93%8D-%E4%BB%A5%E9%98%BF%E9%87%8C/)
- [歐盟個人資料保護法 GDPR 即將上路！與電商賣家息息相關的 Google Analytics 資料保留政策，你瞭解了嗎？](https://shopline.hk/blog/google-analytics-gdpr/)
- [歐盟一般資料保護規範](https://zh.wikipedia.org/wiki/%E6%AD%90%E7%9B%9F%E4%B8%80%E8%88%AC%E8%B3%87%E6%96%99%E4%BF%9D%E8%AD%B7%E8%A6%8F%E7%AF%84)
- [REGULATION (EU) 2016/679 OF THE EUROPEAN PARLIAMENT AND OF THE COUNCIL](https://eur-lex.europa.eu/legal-content/EN/TXT/?uri=celex:32016R0679)

---


最後更新：2019年1月9日。
