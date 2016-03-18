## CIDRAM 中文（傳統）文檔。
（自動翻譯與穀歌翻譯從中文簡體至中文傳統）

### 內容
- 1. [前言](#SECTION1)
- 2. [如何安裝](#SECTION2)
- 3. [如何使用](#SECTION3)
- 4. [文件在包](#SECTION4)
- 5. [配置選項](#SECTION5)
- 6. [簽名格式](#SECTION6)

---


###1. <a name="SECTION1"></a>前言

CIDRAM （無類別域間路由訪問管理器）是一個PHP腳本，旨在保護網站途經阻止請求該從始發IP地址視為不良的流量來源，包括（但不限於）流量該從非人類的訪問端點，雲服務，垃圾郵件發送者，網站鏟運機，等等。它通過計算CIDR的提供的IP地址從入站請求和試圖匹配這些CIDR反對它的簽名文件（這些簽名文件包含CIDR的IP地址視為不良的流量來源）；如果找到匹配，請求被阻止。

CIDRAM COPYRIGHT 2013 and beyond GNU/GPLv2 by Caleb M (Maikuolan)。

本腳本是基於GNU通用許可V2.0版許可協議發布的，您可以在許可協議的允許範圍內自行修改和發布，但請遵守GNU通用許可協議。使用腳本的過程中，作者不提供任何擔保和任何隱含擔保。更多的細節請參見GNU通用公共許可證，下的`LICENSE.txt`文件也可從訪問：
- <http://www.gnu.org/licenses/>。
- <http://opensource.org/licenses/>。

謝謝[ClamAV](http://www.clamav.net/)為本腳本提供文件簽名庫訪問許可。沒有它，這個腳本很可能不會存在，或者其價值有限。

謝謝Sourceforge和GitHub開通了，[Spambot Security](http://www.spambotsecurity.com/forum/viewforum.php?f=55)的CIDRAM的討論論壇，謝謝為CIDRAM提供簽名文件的：[SecuriteInfo.com](http://www.securiteinfo.com/)，[PhishTank](http://www.phishtank.com/)，[NLNetLabs](http://nlnetlabs.nl/)，還有更多的我忘了提及的人（抱歉，語文水平有限，這句話實在不知道怎麼翻譯才通順）。

現在CIDRAM的代碼文件和關聯包可以從以下地址免費下載[GitHub](https://github.com/Maikuolan/CIDRAM/)。

---


###2. <a name="SECTION2"></a>如何安裝

我可能在將來會創建一個安裝程序來簡化安裝過程，但在之前，按照下面的這些安裝說明能在大多數的系統和CMS上成功安裝：

1） 在閱讀到這里之前，我假設您已經下載腳本的一個副本，已解壓縮其內容並保存在您的機器的某個地方。現在，您要決定將腳本放在您服務器上的哪些文件夾中，例如`/public_html/cidram/`或其他任何您覺得滿意和安全的地方。*上傳完成後，繼續閱讀。。*

2） 自定義（強烈推薦高級用戶，但不推薦業餘用戶或者新手使用這個方法），打開`config.ini`（位於內`vault`） - 這個文件包含所有CIDRAM的可用配置選項。以上的每一個配置選項應有一個簡介來說明它是做什麼的和它的具有的功能。按照您認為合適的參數來調整這些選項，然後保存文件，關閉。

3） 上傳（CIDRAM和它的文件）到您選定的文件夾（不需要包括`*.txt`/`*.md`文件，但大多數情況下，您應上傳所有的文件）。

4） 修改的`vault`文件夾權限為“755”。注意，主文件夾也應該是該權限，如果遇上其他權限問題，請修改對應文件夾和文件的權限。

5） 接下來，您需要為您的系統或CMS設定啟動CIDRAM的鉤子。有幾種不同的方式為您的系統或CMS設定鉤子，最簡單的是在您的系統或CMS的核心文件的開頭中使用`require`或`include`命令直接包含腳本（這個方法通常會導致在有人訪問時每次都加載）。平時，這些都是存儲的在文件夾中，例如`/includes`，`/assets`或`/functions`等文件夾，和將經常被命名的某物例如`init.php`，`common_functions.php`，`functions.php`。這是根據您自己的情況決定的，並不需要完全遵守；如果您遇到困難，訪問CIDRAM支持論壇和發送問題；可能其他用戶或者我自己也有這個問題並且解決了（您需要讓我們您在使用哪些CMS）。為了使用`require`或`include`，插入下面的代碼行到最開始的該核心文件，更換裡面的數據引號以確切的地址的`loader.php`文件（本地地址，不是HTTP地址；它會類似於前面提到的vault地址）。（注意，本人不是PHP程序員，關於這一段僅僅是直譯，如有錯誤，請在對應項目上提交問題更正）。

`<?php require '/user_name/public_html/cidram/loader.php'; ?>`

保存文件，關閉，重新上傳。

-- 或替換 --

如果您使用Apache網絡服務器並且您可以訪問`php.ini`，您可以使用該`auto_prepend_file`指令為任何PHP請求創建附上的CIDRAM。就像是：

`auto_prepend_file = "/user_name/public_html/cidram/loader.php"`

或在該`.htaccess`文件：

`php_value auto_prepend_file "/user_name/public_html/cidram/loader.php"`

6) 這就是一切！ :-)

---


###3. <a name="SECTION3"></a>如何使用

CIDRAM 應自動阻止不良的請求至您的網站，沒有任何需求除了初始安裝。

更新是手工完成，和您可以定制您的配置和您可以定制什麼CIDR被阻止通過修改您的配置文件和/或您的簽名文件.

如果您遇到任何假陽性，請聯繫我讓我知道這件事。

---


###4. <a name="SECTION4"></a>文件在包
（本段文件採用的自動翻譯，因為都是一些文件描述，參考意義不是很大，如有疑問，請參考英文原版）

下面是一個列表的所有的文件該應該是存在在您的存檔在下載時間，任何文件該可能創建因之的您的使用這個腳本，包括一個簡短說明的他們的目的。

文件 | 說明
----|----
/.gitattributes | GitHub文件（不需要為正確經營腳本）。
/Changelog.txt | 記錄的變化做出至腳本間不同版本（不需要為正確經營腳本）。
/composer.json | Composer/Packagist 信息（不需要為正確經營腳本）。
/LICENSE.txt | GNU/GPLv2 執照文件。
/loader.php | 加載文件。這個是文件您應該｢鉤子｣（必不可少）!
/README.md | 項目概要信息。
/web.config | 一個ASP.NET配置文件（在這種情況，以保護`/vault`文件夾從被訪問由非授權來源在事件的腳本是安裝在服務器根據ASP.NET技術）。
/_docs/ | 筆記文件夾（包含若干文件）。
/_docs/readme.de.md | 德文自述文件。
/_docs/readme.en.md | 英文自述文件。
/_docs/readme.es.md | 西班牙文自述文件。
/_docs/readme.fr.md | 法文自述文件。
/_docs/readme.id.md | 印度尼西亞文自述文件。
/_docs/readme.it.md | 意大利文自述文件。
/_docs/readme.nl.md | 荷蘭文自述文件。
/_docs/readme.pt.md | 葡萄牙文自述文件。
/_docs/readme.zh-TW.md | 中文（簡體）自述文件。
/_docs/readme.zh.md | 中文（簡體）自述文件。
/vault/ | 安全／保險庫｢Vault｣文件夾（包含若干文件）。
/vault/.htaccess | 超文本訪問文件（在這種情況，以保護敏感文件屬於腳本從被訪問由非授權來源）。
/vault/cache.dat | 緩存數據。
/vault/cli.php | CLI處理文件。
/vault/config.ini | 配置文件；包含所有配置指令為CIDRAM，告訴它什麼做和怎麼正確地經營（必不可少）！
/vault/config.php | 配置處理文件。
/vault/functions.php | 功能處理文件（必不可少）。
/vault/ipv4.dat | IPv4簽名文件。
/vault/ipv4_custom.dat | IPv4定制簽名文件。
/vault/ipv6.dat | IPv6簽名文件。
/vault/ipv6_custom.dat | IPv6定制簽名文件。
/vault/lang.php | 語音數據。
/vault/lang/ | 包含CIDRAM語言數據。
/vault/lang/.htaccess | 超文本訪問文件（在這種情況，以保護敏感文件屬於腳本從被訪問由非授權來源）。
/vault/lang/lang.en.php | 英文語言數據。
/vault/lang/lang.es.php | 西班牙文語言數據。
/vault/lang/lang.fr.php | 法文語言數據。
/vault/lang/lang.id.php | 印度尼西亞文語言數據。
/vault/lang/lang.it.php | 意大利文語言數據。
/vault/lang/lang.nl.php | 荷蘭文語言數據。
/vault/lang/lang.pt.php | 葡萄牙文語言數據。
/vault/lang/lang.zh-TW.php | 中文（傳統）語言數據。
/vault/lang/lang.zh.php | 中文（簡體）語言數據。
/vault/outgen.php | 輸出發生器。
/vault/template.html | 模板文件；模板為HTML輸出產生通過CIDRAM輸出發生器。
/vault/template_custom.html | 模板文件；模板為HTML輸出產生通過CIDRAM輸出發生器。
/vault/rules_as6939.php | 定制規則文件為 AS6939。
/vault/rules_softlayer.php | 定制規則文件為 Soft Layer。

---


###5. <a name="SECTION5"></a>配置選項
下列是一個列表的變量發現在`config.ini`配置文件的CIDRAM，以及一個說明的他們的目的和功能。

####"general" （類別）
基本CIDRAM配置。

"logfile"
- 文件為記錄所有被攔截的訪問。指定一個文件名，或留空以禁用。

“ipaddr”
- 在哪裡可以找到連接請求IP地址？（可以使用為服務例如Cloudflare和類似）標準是`REMOTE_ADDR`。警告！不要修改此除非您知道什麼您做著！

"forbid_on_block"
- CIDRAM 應該響應以 “403 Forbidden” 到被阻止的請求，或 “200 OK”？ False = 200 [Default]; True = 403。

“lang”
- 指定標準CIDRAM語言。

"emailaddr"
- 如果您希望，您可以提供電子郵件地址這裡要給予用戶當他們被阻止，他們使用作為接觸點為支持和/或幫助在的情況下他們錯誤地阻止。警告:您提供的任何電子郵件地址，它肯定會被獲得通過垃圾郵件機器人和鏟運機，所以，它強烈推薦如果選擇提供一個電子郵件地址這裡，您保證它是一次性的和/或不是很重要（換一種說法，您可能不希望使用您的主電子郵件地址或您的企業電子郵件地址）。

####"signatures" （類別）
簽名配置。

"block_cloud"
- 阻止CIDR認定為屬於虛擬主機或云服務嗎？如果您操作一個API服務從您的網站或如果您預計其他網站連接到您的網站，這應該被設置為“false”（假）。如果不，這應該被設置為“true”（真）。

"block_bogons"
- 阻止bogon(“ㄅㄡㄍㄛㄋ”)/martian（​​“火星”）CIDR嗎？如果您希望連接到您的網站從您的本地網絡/本地主機/localhost/LAN/等等，這應該被設置為“false”（假）。如果不，這應該被設置為“true”（真）。

"block_generic"
- 阻止CIDR一般建議對於黑名單嗎？這包括簽名不標記為的一部分任何其他更具體簽名類別。

"block_spam"
- 阻止高風險垃圾郵件CIDR嗎？除非您遇到問題當這樣做，通常，這應該被設置為“true”（真）。

---


###6. <a name="SECTION6"></a>簽名格式

CIDRAM簽名格式和結構描述可以被發現記錄在純文本在自定義簽名文件。請參閱該文檔了解更多有關CIDRAM簽名格式和結構。

---


最後更新：2016年3月18日。
