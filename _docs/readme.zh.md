## CIDRAM 中文（简体）文档。

### 内容
- 1. [前言](#SECTION1)
- 2. [如何安装](#SECTION2)
- 3. [如何使用](#SECTION3)
- 4. [前端管理](#SECTION4)
- 5. [文件在包](#SECTION5)
- 6. [配置选项](#SECTION6)
- 7. [签名格式](#SECTION7)
- 8. [已知的兼容问题](#SECTION8)
- 9. [常见问题（FAQ）](#SECTION9)
- 10. *保留以备将来添加到文档中。*
- 11. [法律信息](#SECTION11)

*翻译注释：如果错误（例如，​翻译差异，​错别字，​等等），​英文版这个文件是考虑了原版和权威版。​如果您发现任何错误，​您的协助纠正他们将受到欢迎。​*

---


### 1. <a name="SECTION1"></a>前言

CIDRAM （无类别域间路由访问管理器）是一个PHP脚本，​旨在保护网站途经阻止请求该从始发IP地址视为不良的流量来源，​包括（但不限于）流量该从非人类的访问端点，​云服务，​垃圾邮件发送者，​网站铲运机，​等等。​它通过计算CIDR的提供的IP地址从入站请求和试图匹配这些CIDR反对它的签名文件（这些签名文件包含CIDR的IP地址视为不良的流量来源）；如果找到匹配，​请求被阻止。

*(看到：[什么是“CIDR”？​](#WHAT_IS_A_CIDR))。​*

CIDRAM COPYRIGHT 2016 and beyond GNU/GPLv2 by Caleb M (Maikuolan)。

本脚本是基于GNU通用许可V2.0版许可协议发布的，​您可以在许可协议的允许范围内自行修改和发布，​但请遵守GNU通用许可协议。​使用脚本的过程中，​作者不提供任何担保和任何隐含担保。​更多的细节请参见GNU通用公共许可证，​下的`LICENSE.txt`文件也可从访问：
- <https://www.gnu.org/licenses/>。
- <https://opensource.org/licenses/>。

现在CIDRAM的代码文件和关联包可以从以下地址免费下载[GitHub](https://cidram.github.io/)。

---


### 2. <a name="SECTION2"></a>如何安装

#### 2.0 安装手工

1） 在阅读到这里之前，​我假设您已经下载脚本的一个副本，​已解压缩其内容并保存在您的机器的某个地方。​现在，​您要决定将脚本放在您服务器上的哪些文件夹中，​例如`/public_html/cidram/`或其他任何您觉得满意和安全的地方。​*上传完成后，​继续阅读。​。​*

2） 重命名`config.ini.RenameMe`到`config.ini`（位于内`vault`），​和如果您想（强烈推荐高级用户，​但不推荐业余用户或者新手使用这个方法），​打开它（这个文件包含所有CIDRAM的可用配置选项；以上的每一个配置选项应有一个简介来说明它是做什么的和它的具有的功能）。​按照您认为合适的参数来调整这些选项，​然后保存文件，​关闭。

3） 上传（CIDRAM和它的文件）到您选定的文件夹（不需要包括`*.txt`/`*.md`文件，​但大多数情况下，​您应上传所有的文件）。

4） 修改的`vault`文件夹权限为“755”（如果有问题，​您可以试试“777”，​但是这是不太安全）。​注意，​主文件夹也应该是该权限，​如果遇上其他权限问题，​请修改对应文件夹和文件的权限。​简而言之：为了使包正常工作，PHP需要能够在`vault`目录中读写文件。​如果PHP无法写入`vault`目录，那么很多事情（更新，记录等）都是不可能的，如果PHP无法从`vault`目录中读取，则包将无法正常工作。​但是，为了获得最佳安全性，`vault`目录不得公开访问（如果`vault`目录可公开访问，敏感信息，例如`config.ini`或`frontend.dat`包含的信息，可能会暴露给潜在的攻击者）。

5） 接下来，​您需要为您的系统或CMS设定启动CIDRAM的钩子。​有几种不同的方式为您的系统或CMS设定钩子，​最简单的是在您的系统或CMS的核心文件的开头中使用`require`或`include`命令直接包含脚本（这个方法通常会导致在有人访问时每次都加载）。​平时，​这些都是存储的在文件夹中，​例如`/includes`，​`/assets`或`/functions`等文件夹，​和将经常被命名的某物例如`init.php`，​`common_functions.php`，​`functions.php`。​这是根据您自己的情况决定的，​并不需要完全遵守；如果您遇到困难，​参观GitHub上的CIDRAM issues页面；可能其他用户或者我自己也有这个问题并且解决了（您需要让我们您在使用哪些CMS）。​为了使用`require`或`include`，​插入下面的代码行到最开始的该核心文件，​更换里面的数据引号以确切的地址的`loader.php`文件（本地地址，​不是HTTP地址；它会类似于前面提到的vault地址）。

`<?php require '/user_name/public_html/cidram/loader.php'; ?>`

保存文件，​关闭，​重新上传。

-- 或替换 --

如果您使用Apache网络服务器并且您可以访问`php.ini`，​您可以使用该`auto_prepend_file`指令为任何PHP请求创建附上的CIDRAM。​就像是：

`auto_prepend_file = "/user_name/public_html/cidram/loader.php"`

或在该`.htaccess`文件：

`php_value auto_prepend_file "/user_name/public_html/cidram/loader.php"`

6) 这就是一切！​:-)

#### 2.1 与COMPOSER安装

[CIDRAM是在Packagist上](https://packagist.org/packages/cidram/cidram)，​所以，​如果您熟悉Composer，​您可以使用Composer安装CIDRAM（您仍然需要准备配置，权限，和钩子。参考“安装手工”步骤2，4，和5）。

`composer require cidram/cidram`

#### 2.2 为WORDPRESS安装

如果要使用CIDRAM与WordPress，​您可以忽略上述所有说明。​[CIDRAM在WordPress插件数据库中注册](https://wordpress.org/plugins/cidram/)，​您可以直接从插件仪表板安装CIDRAM。​您可以像其他插件一样安装，​不需要添加步骤。​与其他安装方法相同，​您可以通过修改`config.ini`来或通过使用前端配置页面自定义您的安装。​更新CIDRAM通过前端更新页面时，​插件版本信息将自动与WordPress同步。

*警告：在插件仪表板里更新CIDRAM会导致干净的安装！​如果您已经自定义了您的安装（更改了您的配置，​安装的模块等），​在插件仪表板里进行更新时，​这些定制将会丢失！​日志文件也将丢失！​要保留日志文件和自定义，​请通过CIDRAM前端更新页面进行更新。​*

---


### 3. <a name="SECTION3"></a>如何使用

CIDRAM 应自动阻止不良的请求至您的网站，​没有任何需求除了初始安装。

您可以定制您的配置和您可以定制什么CIDR被阻止通过修改您的配置文件和/或您的签名文件.

如果您遇到任何假阳性，​请联系我让我知道这件事。 *(看到：[什么是“假阳性”？​](#WHAT_IS_A_FALSE_POSITIVE))。​*

CIDRAM可以手动或通过前端更新。​CIDRAM也可以通过Composer或WordPress更新，如果最初通过这些方式安装的话。

---


### 4. <a name="SECTION4"></a>前端管理

#### 4.0 什么是前端。

前端提供了一种方便，​轻松的方式来维护，​管理和更新CIDRAM安装。​您可以通过日志页面查看，​共享和下载日志文件，​您可以通过配置页面修改配置，​您可以通过更新页面安装和卸载组件，​和您可以通过文件管理器上传，​下载和修改文件在vault。

默认情况是禁用前端，​以防止未授权访问 （未授权访问可能会对您的网站及其安全性造成严重后果）。​启用它的说明包括在本段下面。

#### 4.1 如何启用前端。

1) 里面的`config.ini`文件，​找到指令`disable_frontend`，​并将其设置为`false` （默认值为`true`）。

2) 从浏览器访问`loader.php` （例如，​`http://localhost/cidram/loader.php`）。

3) 使用默认用户名和密码（admin/password）登录。

注意：第一次登录后，​以防止未经授权的访问前端，​您应该立即更改您的用户名和密码！​这是非常重要的，​因为它可以任意PHP代码上传到您的网站通过前端。

此外，为了获得最佳安全性，强烈建议为所有前端帐户启用“双因素身份验证”（下面提供的说明）。

#### 4.2 如何使用前端。

每个前端页面上都有说明，​用于解释正确的用法和它的预期目的。​如果您需要进一步的解释或帮助，​请联系支持。​另外，​YouTube上还有一些演示视频。

#### 4.3 2FA（双因素身份验证）

通过启用双因素身份验证，可以使前端更安全。​当登录使用2FA的帐户时，会向与该帐户关联的电子邮件地址发送电子邮件。​此电子邮件包含“2FA代码”，用户必须输入它（以及他们的用户名和密码），为了能够使用该帐户登录。​这意味着获取帐户密码不足以让任何黑客或潜在攻击者能够帐户登录，因为他们还需要访问帐户的电子邮件地址才能接收和使用会话的2FA代码（从而使前端更安全）。

首先，为了启用双因素身份验证，请使用前端更新页面来安装PHPMailer组件。​CIDRAM使用PHPMailer发送电子邮件。​注意：虽然CIDRAM本身与`PHP >= 5.4.0`兼容，但PHPMailer需要`PHP >= 5.5.0`，因此，对于PHP 5.4用户来说，无法为CIDRAM前端启用双因素身份验证。

在安装PHPMailer后，您需要通过CIDRAM配置页面或配置文件填充PHPMailer的配置指令。​有关这些配置指令的更多信息包含在本文档的配置部分中。​在填充PHPMailer配置指令后，将`Enable2FA`设置为`true`。​现在应启用双因素身份验证。

接下来，您需要让CIDRAM知道在使用该帐户登录时将2FA代码发送到何处。​为此，请使用电子邮件地址作为帐户的用户名（例如，`foo@bar.tld`），或者将电子邮件地址作为用户名的一部分包括在内，就像通常发送电子邮件一样（例如，`Foo Bar <foo@bar.tld>`）。

注意：保护您的vault免受未经授权的访问（例如，通过加强服务器的安全性和限制公共访问权限）在此非常重要，因为未经授权访问您的配置文件（存储在您的vault中）可能会暴露您的出站SMTP设置（包括SMTP用户名和密码）。​在启用双因素身份验证之前，应确保您的vault已正确保护。​如果您无法做到这一点，那么至少应该创建一个专门用于此目的的新电子邮件帐户，为了降低与暴露的SMTP设置相关的风险。

---


### 5. <a name="SECTION5"></a>文件在包
（本段文件采用的自动翻译，​因为都是一些文件描述，​参考意义不是很大，​如有疑问，​请参考英文原版）

下面是一个列表的所有的文件该应该是存在在您的存档在下载时间，​任何文件该可能创建因之的您的使用这个脚本，​包括一个简短说明的他们的目的。

文件 | 说明
----|----
/_docs/ | 笔记文件夹（包含若干文件）。
/_docs/readme.ar.md | 阿拉伯文自述文件。
/_docs/readme.de.md | 德文自述文件。
/_docs/readme.en.md | 英文自述文件。
/_docs/readme.es.md | 西班牙文自述文件。
/_docs/readme.fr.md | 法文自述文件。
/_docs/readme.id.md | 印度尼西亚文自述文件。
/_docs/readme.it.md | 意大利文自述文件。
/_docs/readme.ja.md | 日文自述文件。
/_docs/readme.ko.md | 韩文自述文件。
/_docs/readme.nl.md | 荷兰文自述文件。
/_docs/readme.pt.md | 葡萄牙文自述文件。
/_docs/readme.ru.md | 俄文自述文件。
/_docs/readme.ur.md | 乌尔都文自述文件。
/_docs/readme.vi.md | 越南文自述文件。
/_docs/readme.zh-TW.md | 中文（传统）自述文件。
/_docs/readme.zh.md | 中文（简体）自述文件。
/vault/ | 安全/保险库【Vault】文件夹（包含若干文件）。
/vault/fe_assets/ | 前端资产。
/vault/fe_assets/.htaccess | 超文本访问文件（在这种情况，​以保护敏感文件属于脚本从被访问由非授权来源）。
/vault/fe_assets/_2fa.html | 在向用户询问2FA代码时使用的HTML模板。
/vault/fe_assets/_accounts.html | 前端帐户页面的HTML模板。
/vault/fe_assets/_accounts_row.html | 前端帐户页面的HTML模板。
/vault/fe_assets/_aux.html | 前端辅助规则页面的HTML模板。
/vault/fe_assets/_cache.html | 前端缓存数据页面的HTML模板。
/vault/fe_assets/_cidr_calc.html | CIDR计算器的HTML模板。
/vault/fe_assets/_cidr_calc_row.html | CIDR计算器的HTML模板。
/vault/fe_assets/_config.html | 前端配置页面的HTML模板。
/vault/fe_assets/_config_row.html | 前端配置页面的HTML模板。
/vault/fe_assets/_files.html | 文件管理器的HTML模板。
/vault/fe_assets/_files_edit.html | 文件管理器的HTML模板。
/vault/fe_assets/_files_rename.html | 文件管理器的HTML模板。
/vault/fe_assets/_files_row.html | 文件管理器的HTML模板。
/vault/fe_assets/_home.html | 端主页的HTML模板。
/vault/fe_assets/_ip_aggregator.html | IP聚合器HTML模板。
/vault/fe_assets/_ip_test.html | IP测试页面的HTML模板。
/vault/fe_assets/_ip_test_row.html | IP测试页面的HTML模板。
/vault/fe_assets/_ip_tracking.html | IP跟踪页面的HTML模板。
/vault/fe_assets/_ip_tracking_row.html | IP跟踪页面的HTML模板。
/vault/fe_assets/_login.html | 前端登录的HTML模板。
/vault/fe_assets/_logs.html | 前端日志页面的HTML模板。
/vault/fe_assets/_nav_complete_access.html | 前端导航链接的HTML模板，​由那些与完全访问使用。
/vault/fe_assets/_nav_logs_access_only.html | 前端导航链接的HTML模板，​由那些与仅日志访问使用。
/vault/fe_assets/_range.html | 范围表的HTML模板。
/vault/fe_assets/_range_row.html | 范围表的HTML模板。
/vault/fe_assets/_sections.html | 章节列表的HTML模板。
/vault/fe_assets/_statistics.html | 前端统计页面的HTML模板。
/vault/fe_assets/_updates.html | 前端更新页面的HTML模板。
/vault/fe_assets/_updates_row.html | 前端更新页面的HTML模板。
/vault/fe_assets/frontend.css | 前端CSS样式表。
/vault/fe_assets/frontend.dat | 前端数据库（包含帐户信息，​会话信息，​和缓存；只生成如果前端是启用和使用）。
/vault/fe_assets/frontend.dat.safety | 在需要时为安全目的而生成。
/vault/fe_assets/frontend.html | 前端的主HTML模板文件。
/vault/fe_assets/icons.php | 图标处理文件（由前端文件管理器使用）。
/vault/fe_assets/pips.php | 点数处理文件（由前端文件管理器使用）。
/vault/fe_assets/scripts.js | 包含前端JavaScript数据。
/vault/lang/ | 包含CIDRAM语言数据。
/vault/lang/.htaccess | 超文本访问文件（在这种情况，​以保护敏感文件属于脚本从被访问由非授权来源）。
/vault/lang/lang.ar.cli.php | 阿拉伯文CLI语言数据。
/vault/lang/lang.ar.fe.php | 阿拉伯文前端语言数据。
/vault/lang/lang.ar.php | 阿拉伯文语言数据。
/vault/lang/lang.bn.cli.php | 孟加拉文CLI语言数据。
/vault/lang/lang.bn.fe.php | 孟加拉文前端语言数据。
/vault/lang/lang.bn.php | 孟加拉文语言数据。
/vault/lang/lang.de.cli.php | 德文CLI语言数据。
/vault/lang/lang.de.fe.php | 德文前端语言数据。
/vault/lang/lang.de.php | 德文语言数据。
/vault/lang/lang.en.cli.php | 英文CLI语言数据。
/vault/lang/lang.en.fe.php | 英文前端语言数据。
/vault/lang/lang.en.php | 英文语言数据。
/vault/lang/lang.es.cli.php | 西班牙文CLI语言数据。
/vault/lang/lang.es.fe.php | 西班牙文前端语言数据。
/vault/lang/lang.es.php | 西班牙文语言数据。
/vault/lang/lang.fr.cli.php | 法文CLI语言数据。
/vault/lang/lang.fr.fe.php | 法文前端语言数据。
/vault/lang/lang.fr.php | 法文语言数据。
/vault/lang/lang.hi.cli.php | 印地文CLI语言数据。
/vault/lang/lang.hi.fe.php | 印地文前端语言数据。
/vault/lang/lang.hi.php | 印地文语言数据。
/vault/lang/lang.id.cli.php | 印度尼西亚文CLI语言数据。
/vault/lang/lang.id.fe.php | 印度尼西亚文前端语言数据。
/vault/lang/lang.id.php | 印度尼西亚文语言数据。
/vault/lang/lang.it.cli.php | 意大利文CLI语言数据。
/vault/lang/lang.it.fe.php | 意大利文前端语言数据。
/vault/lang/lang.it.php | 意大利文语言数据。
/vault/lang/lang.ja.cli.php | 日文CLI语言数据。
/vault/lang/lang.ja.fe.php | 日文前端语言数据。
/vault/lang/lang.ja.php | 日文语言数据。
/vault/lang/lang.ko.cli.php | 韩文CLI语言数据。
/vault/lang/lang.ko.fe.php | 韩文前端语言数据。
/vault/lang/lang.ko.php | 韩文语言数据。
/vault/lang/lang.nl.cli.php | 荷兰文CLI语言数据。
/vault/lang/lang.nl.fe.php | 荷兰文前端语言数据。
/vault/lang/lang.nl.php | 荷兰文语言数据。
/vault/lang/lang.no.cli.php | 挪威文CLI语言数据。
/vault/lang/lang.no.fe.php | 挪威文前端语言数据。
/vault/lang/lang.no.php | 挪威文语言数据。
/vault/lang/lang.pt.cli.php | 葡萄牙文CLI语言数据。
/vault/lang/lang.pt.fe.php | 葡萄牙文前端语言数据。
/vault/lang/lang.pt.php | 葡萄牙文语言数据。
/vault/lang/lang.ru.cli.php | 俄文CLI语言数据。
/vault/lang/lang.ru.fe.php | 俄文前端语言数据。
/vault/lang/lang.ru.php | 俄文语言数据。
/vault/lang/lang.sv.cli.php | 瑞典文CLI语言数据。
/vault/lang/lang.sv.fe.php | 瑞典文前端语言数据。
/vault/lang/lang.sv.php | 瑞典文语言数据。
/vault/lang/lang.th.cli.php | 泰文CLI语言数据。
/vault/lang/lang.th.fe.php | 泰文前端语言数据。
/vault/lang/lang.th.php | 泰文语言数据。
/vault/lang/lang.tr.cli.php | 土耳其文CLI语言数据。
/vault/lang/lang.tr.fe.php | 土耳其文前端语言数据。
/vault/lang/lang.tr.php | 土耳其文语言数据。
/vault/lang/lang.ur.cli.php | 乌尔都文CLI语言数据。
/vault/lang/lang.ur.fe.php | 乌尔都文前端语言数据。
/vault/lang/lang.ur.php | 乌尔都文语言数据。
/vault/lang/lang.vi.cli.php | 越南文CLI语言数据。
/vault/lang/lang.vi.fe.php | 越南文前端语言数据。
/vault/lang/lang.vi.php | 越南文语言数据。
/vault/lang/lang.zh-tw.cli.php | 中文（传统）CLI语言数据。
/vault/lang/lang.zh-tw.fe.php | 中文（传统）前端语言数据。
/vault/lang/lang.zh-tw.php | 中文（传统）语言数据。
/vault/lang/lang.zh.cli.php | 中文（简体）CLI语言数据。
/vault/lang/lang.zh.fe.php | 中文（简体）前端语言数据。
/vault/lang/lang.zh.php | 中文（简体）语言数据。
/vault/.htaccess | 超文本访问文件（在这种情况，​以保护敏感文件属于脚本从被访问由非授权来源）。
/vault/.travis.php | 由Travis CI用于测试（不需要为正确经营脚本）。
/vault/.travis.yml | 由Travis CI用于测试（不需要为正确经营脚本）。
/vault/aggregator.php | IP聚合器。
/vault/auxiliary.yaml | 包含辅助规则。不包括在包中。由辅助规则页面生成。
/vault/cache.dat | 缓存数据。
/vault/cache.dat.safety | 在需要时为安全目的而生成。
/vault/cidramblocklists.dat | Macmathan的可选阻止列表的元数据文件。​由前端更新页面使用。
/vault/cli.php | CLI处理文件。
/vault/components.dat | 组件元数据文件。由前端更新页面使用。
/vault/config.ini.RenameMe | 配置文件；包含所有配置指令为CIDRAM，​告诉它什么做和怎么正确地经营（重命名为激活）。
/vault/config.php | 配置处理文件。
/vault/config.yaml | 配置默认文件；包含CIDRAM的默认配置值。
/vault/frontend.php | 前端处理文件。
/vault/frontend_functions.php | 前端功能处理文件。
/vault/functions.php | 功能处理文件（必不可少）。
/vault/hashes.dat | 包含列表接受哈希表（相关的reCAPTCHA功能；只有生成如果reCAPTCHA功能被启用）。
/vault/ignore.dat | 忽略文件（用于指定其中签名章节CIDRAM应该忽略）。
/vault/ipbypass.dat | 包含列表IP旁路（相关的reCAPTCHA功能；只有生成如果reCAPTCHA功能被启用）。
/vault/ipv4.dat | IPv4签名文件（不想要的云服务和非人终端）。
/vault/ipv4_bogons.dat | IPv4签名文件（bogon/火星CIDR）。
/vault/ipv4_custom.dat.RenameMe | IPv4定制签名文件（重命名为激活）。
/vault/ipv4_isps.dat | IPv4签名文件（危险和垃圾容易ISP）。
/vault/ipv4_other.dat | IPv4签名文件（CIDR从代理，​VPN和其他不需要服务）。
/vault/ipv6.dat | IPv6签名文件（不想要的云服务和非人终端）。
/vault/ipv6_bogons.dat | IPv6签名文件（bogon/火星CIDR）。
/vault/ipv6_custom.dat.RenameMe | IPv6定制签名文件（重命名为激活）。
/vault/ipv6_isps.dat | IPv6签名文件（危险和垃圾容易ISP）。
/vault/ipv6_other.dat | IPv6签名文件（CIDR从代理，​VPN和其他不需要服务）。
/vault/lang.php | 语言数据。
/vault/modules.dat | 模块元数据文件。由前端更新页面使用。
/vault/outgen.php | 输出发生器。
/vault/php5.4.x.php | Polyfill对于PHP 5.4.X （PHP 5.4.X 向下兼容需要它；​较新的版本可以删除它）。
/vault/recaptcha.php | reCAPTCHA模块。
/vault/rules_as6939.php | 定制规则文件为 AS6939。
/vault/rules_softlayer.php | 定制规则文件为 Soft Layer。
/vault/rules_specific.php | 定制规则文件为一些特定的CIDR。
/vault/salt.dat | 盐文件（使用由一些外围功能；只产生当必要）。
/vault/template_custom.html | 模板文件；模板为HTML输出产生通过CIDRAM输出发生器。
/vault/template_default.html | 模板文件；模板为HTML输出产生通过CIDRAM输出发生器。
/vault/themes.dat | 主题元数据文件。由前端更新页面使用。
/vault/verification.yaml | 搜索引擎和社交媒体的验证数据。
/.gitattributes | GitHub文件（不需要为正确经营脚本）。
/Changelog.txt | 记录的变化做出至脚本间不同版本（不需要为正确经营脚本）。
/composer.json | Composer/Packagist 信息（不需要为正确经营脚本）。
/CONTRIBUTING.md | 相关信息如何有助于该项目。
/LICENSE.txt | GNU/GPLv2 执照文件（不需要为正确经营脚本）。
/loader.php | 加载文件。​这个是文件您应该【钩子】（必不可少）!
/README.md | 项目概要信息。
/web.config | 一个ASP.NET配置文件（在这种情况，​以保护`/vault`文件夹从被访问由非授权来源在事件的脚本是安装在服务器根据ASP.NET技术）。

---


### 6. <a name="SECTION6"></a>配置选项
下列是一个列表的变量发现在`config.ini`配置文件的CIDRAM，​以及一个说明的他们的目的和功能。

[general](#general-类别) | [signatures](#signatures-类别) | [recaptcha](#recaptcha-类别) | [legal](#legal-类别) | [template_data](#template_data-类别)
:--|:--|:--|:--|:--
[logfile](#logfile)<br />[logfileApache](#logfileapache)<br />[logfileSerialized](#logfileserialized)<br />[truncate](#truncate)<br />[log_rotation_limit](#log_rotation_limit)<br />[log_rotation_action](#log_rotation_action)<br />[timezone](#timezone)<br />[timeOffset](#timeoffset)<br />[timeFormat](#timeformat)<br />[ipaddr](#ipaddr)<br />[forbid_on_block](#forbid_on_block)<br />[silent_mode](#silent_mode)<br />[lang](#lang)<br />[numbers](#numbers)<br />[emailaddr](#emailaddr)<br />[emailaddr_display_style](#emailaddr_display_style)<br />[disable_cli](#disable_cli)<br />[disable_frontend](#disable_frontend)<br />[max_login_attempts](#max_login_attempts)<br />[FrontEndLog](#frontendlog)<br />[ban_override](#ban_override)<br />[log_banned_ips](#log_banned_ips)<br />[default_dns](#default_dns)<br />[search_engine_verification](#search_engine_verification)<br />[social_media_verification](#social_media_verification)<br />[protect_frontend](#protect_frontend)<br />[disable_webfonts](#disable_webfonts)<br />[maintenance_mode](#maintenance_mode)<br />[default_algo](#default_algo)<br />[statistics](#statistics)<br />[force_hostname_lookup](#force_hostname_lookup)<br />[allow_gethostbyaddr_lookup](#allow_gethostbyaddr_lookup)<br />[hide_version](#hide_version)<br />[empty_fields](#empty_fields)<br /> | [ipv4](#ipv4)<br />[ipv6](#ipv6)<br />[block_cloud](#block_cloud)<br />[block_bogons](#block_bogons)<br />[block_generic](#block_generic)<br />[block_legal](#block_legal)<br />[block_malware](#block_malware)<br />[block_proxies](#block_proxies)<br />[block_spam](#block_spam)<br />[modules](#modules)<br />[default_tracktime](#default_tracktime)<br />[infraction_limit](#infraction_limit)<br />[track_mode](#track_mode)<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /> | [usemode](#usemode)<br />[lockip](#lockip)<br />[lockuser](#lockuser)<br />[sitekey](#sitekey)<br />[secret](#secret)<br />[expiry](#expiry)<br />[logfile](#logfile)<br />[signature_limit](#signature_limit)<br />[api](#api)<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /> | [pseudonymise_ip_addresses](#pseudonymise_ip_addresses)<br />[omit_ip](#omit_ip)<br />[omit_hostname](#omit_hostname)<br />[omit_ua](#omit_ua)<br />[privacy_policy](#privacy_policy)<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /> | [theme](#theme)<br />[Magnification](#magnification)<br />[css_url](#css_url)<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
[PHPMailer](#phpmailer-类别) | [rate_limiting](#rate_limiting-类别)
[EventLog](#eventlog)<br />[SkipAuthProcess](#skipauthprocess)<br />[Enable2FA](#enable2fa)<br />[Host](#host)<br />[Port](#port)<br />[SMTPSecure](#smtpsecure)<br />[SMTPAuth](#smtpauth)<br />[Username](#username)<br />[Password](#password)<br />[setFromAddress](#setfromaddress)<br />[setFromName](#setfromname)<br />[addReplyToAddress](#addreplytoaddress)<br />[addReplyToName](#addreplytoname)<br /> | [max_bandwidth](#max_bandwidth)<br />[max_requests](#max_requests)<br />[precision_ipv4](#precision_ipv4)<br />[precision_ipv6](#precision_ipv6)<br />[allowance_period](#allowance_period)<br /><br /><br /><br /><br /><br /><br /><br /><br />

#### “general” （类别）
基本CIDRAM配置。

##### “logfile”
- 人类可读文件用于记录所有被拦截的访问。​指定一个文件名，​或留空以禁用。

##### “logfileApache”
- Apache风格文件用于记录所有被拦截的访问。​指定一个文件名，​或留空以禁用。

##### “logfileSerialized”
- 连载的文件用于记录所有被拦截的访问。​指定一个文件名，​或留空以禁用。

*有用的建议：如果您想，​可以追加日期/时间信息至附加到您的日志文件的名称通过包括这些中的名称：`{yyyy}` 为今年完整，​`{yy}` 为今年缩写，​`{mm}` 为今月，​`{dd}` 为今日，​`{hh}` 为今小时。​*

*例子：*
- *`logfile='logfile.{yyyy}-{mm}-{dd}-{hh}.txt'`*
- *`logfileApache='access.{yyyy}-{mm}-{dd}-{hh}.txt'`*
- *`logfileSerialized='serial.{yyyy}-{mm}-{dd}-{hh}.txt'`*

##### “truncate”
- 截断日志文件当他们达到一定的大小吗？​值是在B/KB/MB/GB/TB，​是日志文件允许的最大大小直到它被截断。​默认值为“0KB”将禁用截断（日志文件可以无限成长）。​注意：适用于单个日志文件！​日志文件大小不被算集体的。

##### “log_rotation_limit”
- 日志轮转限制了任何时候应该存在的日志文件的数量。​当新的日志文件被创建时，如果日志文件的指定的最大数量已经超过，将执行指定的操作。​您可以在此指定所需的限制。​值为“0”将禁用日志轮转。

##### “log_rotation_action”
- 日志轮转限制了任何时候应该存在的日志文件的数量。​当新的日志文件被创建时，如果日志文件的指定的最大数量已经超过，将执行指定的操作。​您可以在此处指定所需的操作。​“Delete”=删除最旧的日志文件，直到不再超出限制。​“Archive”=首先归档，然后删除最旧的日志文件，直到不再超出限制。

*技术澄清：在这种情况下，“最旧”意味着“不是最近被修改”。*

##### “timezone”
- 这用于指定CIDRAM应用于日期/时间操作的时区。​如果您不需要它，请忽略它。​可能的值由PHP确定。​它一般建议，​而不是，​调整时区指令的文件`php.ini`，​但是有时（例如，​当利用有限的共享主机提供商）这并不总是可能做到，​所以，​此选项在这里是提供。

##### “timeOffset”
- 如果您的服务器时间不符合您的本地时间，​您可以在这里指定的偏移调整日期/时间信息该产生通过CIDRAM根据您的需要。​它一般建议，​而不是，​调整时区指令的文件`php.ini`，​但是有时（例如，​当利用有限的共享主机提供商）这并不总是可能做到，​所以，​此选项在这里是提供。​偏移量是在分钟。
- 例子（添加1小时）：`timeOffset=60`

##### “timeFormat”
- CIDRAM使用的日期符号格式。​标准 = `{Day}, {dd} {Mon} {yyyy} {hh}:{ii}:{ss} {tz}`。

##### “ipaddr”
- 在哪里可以找到连接请求IP地址？​（可以使用为服务例如Cloudflare和类似）。​标准=REMOTE_ADDR。​警告：不要修改此除非您知道什么您做着！

“ipaddr”的推荐值：

值 | 运用
---|---
`HTTP_INCAP_CLIENT_IP` | Incapsula反向代理。
`HTTP_CF_CONNECTING_IP` | Cloudflare反向代理。
`CF-Connecting-IP` | Cloudflare反向代理（替代；如果另一个不工作）。
`HTTP_X_FORWARDED_FOR` | Cloudbric反向代理。
`X-Forwarded-For` | [Squid反向代理](http://www.squid-cache.org/Doc/config/forwarded_for/)。
*由服务器配置定义。​* | [Nginx反向代理](https://www.nginx.com/resources/admin-guide/reverse-proxy/)。
`REMOTE_ADDR` | 没有反向代理（默认值）。

##### “forbid_on_block”
- 阻止请求时，CIDRAM应发送哪个HTTP状态消息？

目前支持的值：

状态码 | 状态消息 | 描述
---|---|---
`200` | `200 OK` | 默认值。​最不强大，但对用户最友善。
`403` | `403 Forbidden` | 更强大，和对用户有点友善。
`410` | `410 Gone` | 某些浏览器会缓存此状态消息，并且不会再发送请求，即使用户未被阻止。​这可能会导致很难解决假阳性。​但为减少某些特定类型的漫游器的请求的它可能比其他选项更有效。
`418` | `418 I'm a teapot` | 这实际上引用了愚人节的笑话【[RFC 2324](https://tools.ietf.org/html/rfc2324#section-6.5.14)】，而客户不太可能理解它。​提供了为娱乐和方便的，但一般不推荐。
`451` | `Unavailable For Legal Reasons` | 主要出于法律原因阻止请求时适用于上下文。​不建议在其他情况下。
`503` | `Service Unavailable` | 最强大，但对用户最不友善。

##### “silent_mode”
- CIDRAM应该默默重定向被拦截的访问而不是显示该“拒绝访问”页吗？​指定位置至重定向被拦截的访问，​或让它空将其禁用。

##### “lang”
- 指定标准CIDRAM语言。

##### “numbers”
- 指定如何显示数字。

目前支持的值：

值 | 产生 | 描述
---|---|---
`NoSep-1` | `1234567.89`
`NoSep-2` | `1234567,89`
`Latin-1` | `1,234,567.89` | 默认值。
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

*注意：​这些值在任何地方都不是标准化的，并超出包裹且可能不会相关性。​此外，支持的值可能会在未来发生变化。*

##### “emailaddr”
- 如果您希望，​您可以提供电子邮件地址这里要给予用户当他们被阻止，​他们使用作为接触点为支持和/或帮助在的情况下他们错误地阻止。​警告:您提供的任何电子邮件地址，​它肯定会被获得通过垃圾邮件机器人和铲运机，​所以，​它强烈推荐如果选择提供一个电子邮件地址这里，​您保证它是一次性的和/或不是很重要（换一种说法，​您可能不希望使用您的主电子邮件地址或您的企业电子邮件地址）。

##### “emailaddr_display_style”
- 您希望如何将电子邮件地址呈现给用户？ “default” = 可点击的链接。 “noclick” = 不可点击的文字。

##### “disable_cli”
- 关闭CLI模式吗？​CLI模式是按说激活作为标准，​但可以有时干扰某些测试工具（例如PHPUnit，​为例子）和其他基于CLI应用。​如果您没有需要关闭CLI模式，​您应该忽略这个指令。​False（假）=激活CLI模式【标准】；​True（真）=关闭CLI模式。

##### “disable_frontend”
- 关闭前端访问吗？​前端访问可以使CIDRAM更易于管理，​但也可能是潜在的安全风险。​建议管理CIDRAM通过后端只要有可能，​但前端访问提供当不可能。​保持关闭除非您需要它。​False（假）=激活前端访问；​True（真）=关闭前端访问【标准】。

##### “max_login_attempts”
- 最大登录尝试次数（前端）。​标准=5。

##### “FrontEndLog”
- 前端登录尝试的录音文件。​指定一个文件名，​或留空以禁用。

##### “ban_override”
- 覆盖“forbid_on_block”当“infraction_limit”已被超过？​当覆盖：已阻止的请求返回一个空白页（不使用模板文件）。​200 = 不要覆盖【标准】。​其他值与“forbid_on_block”的可用值相同。

##### “log_banned_ips”
- 包括IP禁止从阻止请求在日志文件吗？​True（真）=是【标准】；​False（假）=不是。

##### “default_dns”
- 以逗号分隔的DNS服务器列表，​用于主机名查找。​标准 = “8.8.8.8,8.8.4.4” (Google DNS)。​警告：不要修改此除非您知道什么您做着！

*也可以看看：​[在“default_dns”中我可以使用什么？](#WHAT_CAN_I_USE_FOR_DEFAULT_DNS)*

##### “search_engine_verification”
- 尝试验证来自搜索引擎的请求？​验证搜索引擎确保他们不会因超过违规限制而被禁止 （禁止在您的网站上使用搜索引擎通常会有产生负面影响对您的搜索引擎排名，​SEO，​等等）。​当被验证，​搜索引擎可以被阻止，​但不会被禁止。​当不被验证，​他们可以由于超过违规限制而被禁止。​另外，​搜索引擎验证提供保护针对假搜索引擎请求和针对潜在的恶意实体伪装成搜索引擎（当搜索引擎验证是启用，​这些请求将被阻止）。​True（真）=搜索引擎验证是启用【标准】；​False（假）=搜索引擎验证是禁用。

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

不兼容（导致冲突）：
- __[Mix.com](https://github.com/CIDRAM/CIDRAM/issues/80)__

##### “social_media_verification”
- 尝试验证社交媒体请求？​社交媒体验证可以防止虚假社交媒体请求（此类请求将被阻止）。​True（真）=启用社交媒体验证【标准】；​False（假）=禁用社交媒体验证。

目前支持：
- __[Pinterest](https://help.pinterest.com/en/articles/about-pinterest-crawler-0)__
- __Embedly__
- __[Grapeshot](https://www.grapeshot.com/crawler/)__
- __Twitterbot__

##### “protect_frontend”
- 指定是否应将CIDRAM通常提供的保护应用于前端。​True（真）=是【标准】；​False（假）=不是。

##### “disable_webfonts”
- 关闭网络字体吗？​True（真）=关闭【标准】；False（假）=不关闭。

##### “maintenance_mode”
- 启用维护模式？​True（真）=关闭；​False（假）=不关闭【标准】。​它停用一切以外前端。​有时候在更新CMS，框架，等时有用。

##### “default_algo”
- 定义要用于所有未来密码和会话的算法。​选项：​PASSWORD_DEFAULT（标准），​PASSWORD_BCRYPT，​PASSWORD_ARGON2I（需要PHP >= 7.2.0）。

##### “statistics”
- 跟踪CIDRAM使用情况统计？​True（真）=跟踪；False（假）=不跟踪【标准】。

##### “force_hostname_lookup”
- 强制主机名查找？​True（真）=跟踪；False（假）=不跟踪【标准】。​主机名查询通常在“根据需要”的基础上执行，但可以在所有请求上强制。​这可能会有助于提供日志文件中更详细的信息，但也可能会对性能产生轻微的负面影响。

##### “allow_gethostbyaddr_lookup”
- 当UDP不可用时允许gethostbyaddr查找？​True（真）=允许【标准】；False（假）=不允许。
- *注意：在某些32位系统上，IPv6查找可能无法正常工作。*

##### “hide_version”
- 从日志和页面输出中隐藏版本信息吗？​True（真）=关闭；False（假）=不关闭【标准】。

##### “empty_fields”
- 在记录和显示阻止事件信息时，CIDRAM如何处理空字段？ “include” = 包括空字段。 “omit” = 省略空字段【标准】。

#### “signatures” （类别）
签名配置。

##### “ipv4”
- 列表的IPv4签名文件，​CIDRAM应该尝试使用，​用逗号分隔。​您可以在这里添加条目如果您想包括其他文件在CIDRAM。

##### “ipv6”
- 列表的IPv6签名文件，​CIDRAM应该尝试使用，​用逗号分隔。​您可以在这里添加条目如果您想包括其他文件在CIDRAM。

##### “block_cloud”
- 阻止CIDR认定为属于虚拟主机或云服务吗？​如果您操作一个API服务从您的网站或如果您预计其他网站连接到您的网站，​这应该被设置为“false”（假）。​如果不，​这应该被设置为“true”（真）。

##### “block_bogons”
- 阻止bogon(“ㄅㄡㄍㄛㄋ”)/martian（“火星”）CIDR吗？​如果您希望连接到您的网站从您的本地网络/本地主机/localhost/LAN/等等，​这应该被设置为“false”（假）。​如果不，​这应该被设置为“true”（真）。

##### “block_generic”
- 阻止CIDR一般建议对于黑名单吗？​这包括签名不标记为的一章节任何其他更具体签名类别。

##### “block_legal”
- 阻止CIDR因为法律义务吗？​这个指令通常不应该有任何作用，因为CIDRAM默认情况下不会将任何CIDR与“法律义务”相关联，​但它作为一个额外的控制措施存在，以利于任何可能因法律原因而存在的自定义签名文件或模块。

##### “block_malware”
- 阻止与恶意软件相关的IP？​这包括C＆C服务器，受感染的机器，涉及恶意软件分发的机器，等等。

##### “block_proxies”
- 阻止CIDR认定为属于代理服务或VPN吗？​如果您需要该用户可以访问您的网站从代理服务和VPN，​这应该被设置为“false”（假）。​除此以外，​如果您不需要代理服务或VPN，​这应该被设置为“true”（真）作为一个方式以提高安全性。

##### “block_spam”
- 阻止高风险垃圾邮件CIDR吗？​除非您遇到问题当这样做，​通常，​这应该被设置为“true”（真）。

##### “modules”
- 模块文件要加载的列表以后检查签名IPv4/IPv6，​用逗号分隔。

##### “default_tracktime”
- 多少秒钟来跟踪模块禁止的IP。​标准 = 604800 （1周）。

##### “infraction_limit”
- 从IP最大允许违规数量之前它被禁止。​标准=10。

##### “track_mode”
- 何时应该记录违规？​False（假）=当IP被模块阻止时。​True（真）=当IP由于任何原因阻止时。

#### “recaptcha” （类别）
如果您想，​您可以为用户提供了一种方法绕过“拒绝访问”页面通过完成reCAPTCHA事件。​这有助于减轻一些风险假阳性有关，​对于当我们不能完全肯定一个请求是否源自机器或人。

由于风险相关的提供的方法为终端用户至绕过“拒绝访问”页面，​通常，​我建议不要启用此功能除非您觉得这是必要的做。​情况由此有必要：如果您的网站有客户/用户该需要具有访问权限您的网站，​而如果这一点该不能妥协的，​但如果这些客户/用户碰巧被来自敌对网络连接该可能被携带不需要的流量，​并阻断这种不需要的流量也不能妥协的，​在那些没有双赢的局面，​reCAPTCHA的功能可能是有用的作为一种手段允许需要的客户/用户，​而避开不需要的流量从同一网络。​虽然说，​鉴于一个CAPTCHA的预期目的是人类和非人类区分，​reCAPTCHA的功能只会协助在这些没有双赢的局面如果我们假设该不需要的流量是非人（例如，​垃圾邮件机器人，​网站铲运机，​黑客工具，​交通自动化），​而不是作为人的不需要的流量（如人的垃圾邮件机器人，​黑客，​等等）。

为了获得“site key”和“secret key”（需要为了使用reCAPTCHA），​请访问：[https://developers.google.com/recaptcha/](https://developers.google.com/recaptcha/)

##### “usemode”
- 它定义了如何CIDRAM应该使用reCAPTCHA。
- 0 = reCAPTCHA是完全禁用【标准】。
- 1 = reCAPTCHA是启用为所有签名。
- 2 = reCAPTCHA是启用只为签名章节被特殊标记在签名文件作为reCAPTCHA启用。
- （任何其他值将以同样的方式被视作0）。

##### “lockip”
- 指定是否哈希应锁定到特定IP地址。​False（假）=Cookie和哈希可以由多个IP地址使用【标准】。​True（真）=Cookie和哈希不能由多个IP地址使用（cookies/哈希是锁定到IP地址）。
- 注意：“lockip”值被忽略当“lockuser”是false（假），​由于该机制为记忆的“用户”可以根据这个值的变化。

##### “lockuser”
- 指定是否一个reCAPTCHA成功完成应锁定到特定用户。​False（假）=一个reCAPTCHA成功完成将授予访问为所有请求该来自同IP作为由用户使用当完成的reCAPTCHA；​Cookie和哈希不被使用；代替，​一个IP白名单将被用于。​True（真）=一个reCAPTCHA成功完成只会授予访问为用户该完成了reCAPTCHA；​Cookie和哈希是用于记住用户；一个IP白名单不被使用【标准】。

##### “sitekey”
- 该值应该对应于“site key”为您的reCAPTCHA，​该可以发现在reCAPTCHA的仪表板。

##### “secret”
- 该值应该对应于“secret key”为您的reCAPTCHA，​该可以发现在reCAPTCHA的仪表板。

##### “expiry”
- 当“lockuser”是true（真）【标准】，​为了记住当用户已经成功完成reCAPTCHA，​为未来页面请求，​CIDRAM生成一个标准的HTTP cookie含哈希对应于内部哈希记录含有相同哈希；未来页面请求将使用这些对应的哈希为了验证该用户已预先完成reCAPTCHA。​当“lockuser”是false（假），​一个IP白名单被用来确定是否请求应允许从请求的入站IP；条目添加到这个白名单当reCAPTCHA是成功完成。​这些cookies，​哈希，​和白名单条目应在多少小时内有效？​标准 = 720 （1个月）。

##### “logfile”
- 记录所有的reCAPTCHA的尝试？​要做到这一点，​指定一个文件名到使用。​如果不，​离开这个变量为空白。

*有用的建议：如果您想，​可以追加日期/时间信息至附加到您的日志文件的名称通过包括这些中的名称：`{yyyy}` 为今年完整，​`{yy}` 为今年缩写，​`{mm}` 为今月，​`{dd}` 为今日，​`{hh}` 为今小时。​*

*例子：*
- *`logfile='recaptcha.{yyyy}-{mm}-{dd}-{hh}.txt'`*

##### “signature_limit”
- 当提供reCAPTCHA实例时，允许触发最大签名数量。​标准 = 1。​如果这个数字超过了任何特定的请求，一个reCAPTCHA实例将不会被提供。

##### “api”
- 使用哪个API？V2或Invisible？

*欧盟用户须知：​当CIDRAM被配置为使用cookie时（例如，当“lockuser”是true/真时），根据[欧盟的cookie法规](https://www.cookielaw.org/the-cookie-law/)，cookie警告显示在页面上。​但是，当使用invisible API时，CIDRAM将自动为用户完成reCAPTCHA，并且当成功时，这可能导致页面被重新加载，并且创建cookie，而用户没有足够的时间来实际看到cookie警告。​如果这对您构成法律风险，那么最好使用V2 API而不使用invisible API（V2 API不是自动的，并且要求用户自己完成reCAPTCHA挑战，因此提供了一个机会来查看cookie警告）。*

#### “legal” （类别）
有关法律义务的配置。

*请参阅文档的“[法律信息](#SECTION11)”章节以获取更多有关法律义务的信息，以及它可以如何影响您的配置义务。*

##### “pseudonymise_ip_addresses”
- 编写日志文件时使用假名的IP地址吗？​True（真）=使用假名【标准】；False（假）=不使用假名。

##### “omit_ip”
- 从日志文件中排除IP地址？​True（真）=排除；False（假）=不排除【标准】。​注意：“omit_ip”为“true”时，“pseudonymise_ip_addresses”变得不必要。

##### “omit_hostname”
- 从日志文件中排除主机名？​True（真）=排除；False（假）=不排除【标准】。

##### “omit_ua”
- 从日志文件中排除用户代理？​True（真）=排除；False（假）=不排除【标准】。

##### “privacy_policy”
- 要显示在任何生成的页面的页脚中的相关隐私政策的地址。​指定一个URL，或留空以禁用。

#### “template_data” （类别）
指令和变量为模板和主题。

涉及的HTML输出用于生成该“拒绝访问”页面。​如果您使用个性化主题为CIDRAM，​HTML产量资源是从`template_custom.html`文件，​和否则，​HTML产量资源是从`template.html`文件。​变量书面在这个配置文件章节是喂在HTML产量通过更换任何变量名包围在大括号发现在HTML产量使用相应变量数据。​为例子，​哪里`foo="bar"`，​任何发生的`<p>{foo}</p>`发现在HTML产量将成为`<p>bar</p>`。

##### “theme”
- 用于CIDRAM的默认主题。

##### “Magnification”
- 字体放大。​标准 = 1。

##### “css_url”
- 模板文件为个性化主题使用外部CSS属性，​而模板文件为t标准主题使用内部CSS属性。​以指令CIDRAM使用模板文件为个性化主题，​指定公共HTTP地址的您的个性化主题的CSS文件使用`css_url`变量。​如果您离开这个变量空白，​CIDRAM将使用模板文件为默认主题。

#### “PHPMailer” （类别）
PHPMailer配置。

目前，CIDRAM仅将PHPMailer用于前端双因素身份验证。​如果不使用前端，或者如果为前端不用双因素身份验证，则可以忽略这些指令。

##### “EventLog”
- 用于记录与PHPMailer相关的所有事件的文件。​指定一个文件名，​或留空以禁用。

##### “SkipAuthProcess”
- 将此指令设置为`true`会指示PHPMailer跳过通过SMTP发送电子邮件时通常会发生的正常身份验证过程。​应该避免这种情况，因为跳过此过程可能会将出站电子邮件暴露给MITM攻击，但在此过程阻止PHPMailer连接到SMTP服务器的情况下可能是必要的。

##### “Enable2FA”
- 该指令确定是否将2FA用于前端帐户。

##### “Host”
- 用于出站电子邮件的SMTP主机。

##### “Port”
- 用于出站电子邮件的端口号。​标准=587。

##### “SMTPSecure”
- 通过SMTP发送电子邮件时使用的协议（TLS或SSL）。

##### “SMTPAuth”
- 此指令确定是否对SMTP会话进行身份验证（通常应该保持不变）。

##### “Username”
- 通过SMTP发送电子邮件时使用的用户名。

##### “Password”
- 通过SMTP发送电子邮件时使用的密码。

##### “setFromAddress”
- 通过SMTP发送电子邮件时引用的发件人地址。

##### “setFromName”
- 通过SMTP发送电子邮件时引用的发件人姓名。

##### “addReplyToAddress”
- 通过SMTP发送电子邮件时引用的回复地址。

##### “addReplyToName”
- 通过SMTP发送电子邮件时引用的回复姓名。

#### “rate_limiting” （类别）
用于速率限制的可选配置指令。

此功能已实施到CIDRAM，因为有足够的用户请求它来辩解它的实施。​但是，因为它与之无关CIDRAM的最初的预期目的，大多数用户很可能不需要它。​如果您特别需要CIDRAM来处理您网站的速率限制，此功能可能是有用给您的。​但是，您应该考虑一些重要的事情：
- 与所有其他CIDRAM功能一样，此功能仅适用于受CIDRAM保护的页面。​因此，任何未通过CIDRAM特定路由的网站资产都不能被CIDRAM限制。
- 不要忘记CIDRAM将缓存和其他数据直接写入磁盘（即，它将它的数据保存到文件中），并且它不使用任何外部数据库系统，如MySQL，PostgreSQL，Access，或类似系统。​这意味着为了跟踪速率限制的使用情况，它实际上需要为每个可能的速率限制请求写入磁盘。​这可能有助于长期降低磁盘寿命，并且不是理想的推荐。​相反，理想情况下，速率限制工具可以利用用于频繁，小型读/写操作的数据库系统，或者可以在请求之间持久保留信息，而无需在请求之间将数据写入磁盘（例如，编写为独立的服务器模块，而不是PHP包）。
- 如果您能够使用服务器模块，cPanel，或其他一些网络工具来强制执行速率限制，最好将其用于速率限制，而不是CIDRAM。
- 如果特定用户非常希望在受到限制后继续访问您的网站，在大多数情况下，他们很容易绕过速率限制（例如，如果他们改变他们的IP地址，或者如果他们使用代理或VPN，并假设您已将CIDRAM配置为不阻止代理和VPN，或者假设CIDRAM不知道他们正在使用的代理或VPN）。
- 对于真实用户来说，速率限制可能非常烦人。​如果您的可用带宽非常有限，并且如果您发现有一些特定的流量来源，尚未被阻止，并且它占用大部分可用带宽，速率限制可能是必要的。​然而，如果没有必要，应该避免它。
- 您可能偶尔冒险阻止合法用户，甚至是您自己。

如果您不需要CIDRAM来对您的网站进行速率限制，请将以下指令设置为默认值。​否则，您可以更改其值以满足您的需求。

##### “max_bandwidth”
- 在为将来的请求启用速率限制之前的最大允许带宽量。​值为0将禁用此类速率限制。​标准=0KB。

##### “max_requests”
- 在为将来的请求启用速率限制之前允许的最大请求数。​值为0将禁用此类速率限制。​标准=0。

##### “precision_ipv4”
- 监视IPv4使用时的精度。​值镜像CIDR块大小。​设置为32以获得最佳精度。​标准=32。

##### “precision_ipv6”
- 监视IPv6使用时的精度。​值镜像CIDR块大小。​设置为128以获得最佳精度。​标准=128。

##### “allowance_period”
- 监视使用情况的小时数。​标准=0。

---


### 7. <a name="SECTION7"></a>签名格式

*也可以看看：*
- *[什么是“签名”？](#WHAT_IS_A_SIGNATURE)*

#### 7.0 基本概念（对于签名文件）

所有IPv4签名遵循格式：`xxx.xxx.xxx.xxx/yy [Function] [Param]`。
- `xxx.xxx.xxx.xxx` 代表CIDR块开始（初始IP地址八比特组）。
- `yy` 代表CIDR块大小 [1-32]。
- `[Function]` 指令脚本做什么用的署名（应该怎么签名考虑）。
- `[Param]` 代表任何其他信息其可以由需要 `[Function]`。

所有IPv6签名遵循格式：`xxxx:xxxx:xxxx:xxxx::xxxx/yy [Function] [Param]`。
- `xxxx:xxxx:xxxx:xxxx::xxxx` 代表CIDR块的开始（初始IP地址八比特组）。​完整符号和缩写符号是可以接受的（和每都必须遵循相应和相关IPv6符号标准，​但有一个例外：IPv6地址不能开头是与缩写当用来在签名该脚本，​由于以何种方式CIDR是构建由脚本；例如，​当用来在签名，​`::1/128`应该这样写`0::1/128`，​和`::0/128`应该这样写`0::/128`）。
- `yy` 代表CIDR块大小 [1-128]。
- `[Function]` 指令脚本做什么用的署名（应该怎么签名考虑）。
- `[Param]` 代表任何其他信息其可以由需要 `[Function]`。

CIDRAM签名文件应该使用Unix的换行符（`%0A`，​或`\n`）！​其他换行符类型/风格（例如，​Windows `%0D%0A`或`\r\n`换行符，​Mac `%0D`或`\r`换行符，​等等） 可以被用于，​但不是优选。​非Unix的换行符将正常化至Unix的换行符由脚本。

精准无误的CIDR符号是必须的，​不会是承认签名。​另外，​所有的CIDR签名必须用一个IP地址该始于一个数在该CIDR块分割适合于它的块大小（例如，​如果您想阻止所有的IP从`10.128.0.0`到`11.127.255.255`，​`10.128.0.0/8`不会是承认由脚本，​但`10.128.0.0/9`和`11.0.0.0/9`结合使用，​将是承认由脚本）。

任何数据在签名文件不承认为一个签名也不为签名相关的语法由脚本将被忽略，​因此，​这意味着您可以放心地把任何未签名数据和任何您想要的在签名文件没有打破他们和没有打破该脚本。​注释是可以接受的在签名文件，​和没有特殊的格式需要为他们。​Shell风格的哈希注释是首选，​但并非强制；从功能的角度，​无论您是否选择使用Shell风格的哈希注释，​有没有区别为脚本，​但使用Shell风格的哈希帮助IDE和纯文本编辑器正确地突出的各个签名章节文件（所以，​Shell风格的哈希可以帮助作为视觉辅助在编辑）。

`[Function]` 可能的值如下：
- Run
- Whitelist
- Greylist
- Deny

如果“Run”是用来，​当该签名被触发，​该脚本将尝试执行（使用一个`require_once`声明）一个外部PHP脚本，​由指定的`[Param]`值（工作目录应该是“/vault/”脚本目录）。

例子：`127.0.0.0/8 Run example.php`

这可能是有用如果您想执行一些特定的PHP代码对一些特定的IP和/或CIDR。

如果“Whitelist”是用来，​当该签名被触发，​该脚本将重置所有检测（如果有过任何检测）和打破该测试功能。​`[Param]`被忽略。​此功能将白名单一个IP地址或一个CIDR。

例子：`127.0.0.1/32 Whitelist`

如果“Greylist”是用来，​当该签名被触发，​该脚本将重置所有检测（如果有过任何检测）和跳到下一个签名文件以继续处理。​`[Param]`被忽略。

例子：`127.0.0.1/32 Greylist`

如果“Deny”是用来，​当该签名被触发，​假设没有白名单签名已触发为IP地址和/或CIDR，​访问至保护的页面被拒绝。​您要使用“Deny”为实际拒绝一个IP地址和/或CIDR范围。​当任何签名利用的“Deny”被触发，​该“拒绝访问”脚本页面将生成和请求到保护的页面会被杀死。

“Deny”的`[Param]`值会被解析为“拒绝访问”页面，​提供给客户机/用户作为引原因他们访问到请求的页面被拒绝。​它可以是一个短期和简单的句子，​为解释原因（什么应该足够了；一个简单的消息像“我不想让您在我的网站”会好起来的），​或一小撮之一的短关键字供应的通过脚本。​如果使用，​它们将被替换由脚本使用预先准备的解释为什么客户机/用户已被阻止。

预先准备的解释具有多语言支持和可以翻译通过脚本根据您的语言指定的通过`lang`脚本配置指令。​另外，​您可以指令脚本忽略“Deny”签名根据他们的价`[Param]`值（如果他们使用这些短关键字）通过脚本配置指令（每短关键字有一个相应的指令到处理相应的签名或忽略它）。​`[Param]`值不使用这些短关键字，​然而，​没有多语言支持和因此不会被翻译通过脚本，​并且还，​不能直接控制由脚本配置。

可用的短关键字是：
- Bogon
- Cloud
- Generic
- Proxy
- Spam
- Legal
- Malware

#### 7.1 标签

##### 7.1.0 章节标签

如果要分割您的自定义签名成各个章节，​您可以识别这些各个章节为脚本通过加入一个章节标签立即跟着每签名章节，​伴随着签名章节名字（看下面的例子）。

```
# 章节一。
1.2.3.4/32 Deny Bogon
2.3.4.5/32 Deny Cloud
4.5.6.7/32 Deny Generic
5.6.7.8/32 Deny Spam
6.7.8.9/32 Deny Proxy
Tag: 章节一
```

为了打破章节标签和以确保标签不是确定不正确的对于签名章节从较早的在签名文件，​确保有至少有两个连续的换行符之间您的标签和您的较早的签名章节。​任何未标记签名将默认为“IPv4”或“IPv6”（取决于签名类型被触发）。

```
1.2.3.4/32 Deny Bogon
2.3.4.5/32 Deny Cloud

4.5.6.7/32 Deny Generic
5.6.7.8/32 Deny Spam
Tag: 章节一
```

在上面的例子`1.2.3.4/32`和`2.3.4.5/32`将标为“IPv4”，​而`4.5.6.7/32`和`5.6.7.8/32`将标为“章节一”.

同样逻辑也可以应用于分离其他标签类型。

特别是，当假阳性发生时，章节标签对调试非常有用，通过提供一个简单的方法来找到问题的确切来源，并当通过前端日志页面查看日志文件时，可以非常有用地过滤日志文件条目​（章节名称可通过前端日志页面进行点击，并可用作过滤标准）。​如果在一些签名章节中章节标签是省略，当这些签名被触发时，CIDRAM使用签名文件的名称以及阻止的IP地址类型（IPv4或IPv6）作为后备，因此，章节标签是完全可选的。​但是，在某些情况下，可能会推荐使用它们，例如签名文件模糊地命名，或当难以清楚地识别阻止请求的签名的来源时。

##### 7.1.1 过期标签

如果您想签名一段时间后过期，​以类似的方式来章节标签，​您可以使用一个“过期标签”来指定在签名应该不再有效。​过期标签使用格式“年年年年.月月.日日”（看下面的例子）。

```
# 章节一。
1.2.3.4/32 Deny Generic
2.3.4.5/32 Deny Generic
Expires: 2016.12.31
```

过期的签名永远不会被任何请求触发。

##### 7.1.2 原产标签

如果您想要指定某个特定签名的原产国，可以使用“原产标签”来实现。​原产标签接受与其适用的签名相对应的国家的“[ISO 3166-1 二位字母](https://zh.wikipedia.org/wiki/ISO_3166-1)”代码。​这些代码必须写成大写（小写或混合大小写不能正确显示）。​当使用原产标签时，会将其添加到“为什么被阻止”日志字段条目中，为任何请求被阻止因为签名的标签应用。

如果安装了可选的“flags CSS”组件，当通过前端日志页面查看日志文件时，由原产标签附加的信息被其相应的国旗取代。​无论是原始形式还是国旗，这些信息是可点击的，并当点击时，它将通过类似识别的日志条目来过滤日志条目（从而有效地启用日志页面按来源国过滤）。

注意：从技术上讲，这不是任何形式的地理定位，因为它不涉及从IP查找特定的位置信息，反而，它允许我们当请求被特定的签名阻止时明确指出一个原产国。​同一个签名章节允许有多个原产标签。

假设的例子：

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

任何标签都可以联合使用，所有标签都是可选（看下面的例子）。

```
# 示例章节。
1.2.3.4/32 Deny Generic
Origin: US
Tag: 示例章节
Expires: 2016.12.31
```

##### 7.1.3 延缓标签

当大量安装并主动使用的签名文件时，安装可能会变得非常复杂，并且可能存在一些重叠的签名。​在这些情况下，为了防止在阻止事件期间触发多个重叠的签名，在大量安装并主动使用的某些其他特定签名文件的情况下，延缓标签可用于延缓特定签名章节。​在某些签名比其他签名更频繁更新的情况下，这可能很有用，为了延缓较不频繁更新的签名，于赞成更频繁更新的签名。

延缓标签与其他类型的标签类似地使用。​标签的值应该是要赞成的签名文件的名称。

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

简化形式的YAML标记可以使用在签名文件用于目的定义行为和配置设置具体到个人签名章节。​这可能是有用的如果您希望您的配置指令值到变化之间的个人签名和签名章节（例如；如果您想提供一个电子邮件地址为支持票为任何用户拦截的通过一个特定的签名，​但不希望提供一个电子邮件地址为支持票为用户拦截的通过任何其他签名；如果您想一些具体的签名到触发页面重定向；如果您想标记一个签名为使用的reCAPTCHA；如果您想日志拦截的访问到单独的文件按照个人签名和/或签名章节）。

使用YAML标记在签名文件是完全可选（即，​如果您想用这个，​您可以用这个，​但您没有需要用这个），​和能够利用最的（但不所有的）配置指令。

注意：YAML标记实施在CIDRAM是很简单也很有限；它的目的是满足特定要求的CIDRAM在方式具有熟悉的YAML标记，​但不跟随也不符合规定的官方规格（因此，​将不会是相同的其他实现别处，​和可能不适合其他项目别处）。

在CIDRAM，​YAML标记段被识别到脚本通过使用三个连字符（“---”），​和终止靠他们的签名章节通过双换行符。​一个典型的YAML标记段在一个签名章节被组成的三个连字符在一行立马之后的CIDR列表和任何标签，​接着是二维表为键值对（第一维，​配置指令类别；第二维，​配置指令）为哪些配置指令应修改（和哪些值）每当一个签名内那签名章节被触发（看下面的例子）。

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

##### 7.2.1 如何“特别标记”签名章节为使用的reCAPTCHA

当“usemode”是“0”或“1”，​签名章节不需要是“特别标记”签名章节为使用的reCAPTCHA（因为他们已会或不会使用reCAPTCHA，​根据此设置）。

当“usemode”是“2”，​为“特别标记”签名章节为使用的reCAPTCHA，​一个条目是包括在YAML段为了那个签名章节（看下面的例子）。

```
# 本节将使用reCAPTCHA。
1.2.3.4/32 Deny Generic
2.3.4.5/32 Deny Generic
Tag: reCAPTCHA-Enabled
---
recaptcha:
 enabled: true
```

*注意：默认，一个reCAPTCHA将仅提供给用户如果reCAPTCHA是启用（当“usemode”是“1”，​或“usemode”是“2”和“enabled”是“true”），​和如果只有一个签名已触发（不多不少；如果多个签名被触发，​一个reCAPTCHA将不提供）。​然而，这个行为可以通过“signature_limit”指令来修改。*

#### 7.3 辅

##### 7.3.0 忽略签名章节

此外，​如果您想CIDRAM完全忽略一些具体的章节内的任何签名文件，​您可以使用`ignore.dat`文件为指定忽略哪些章节。​在新行，​写`Ignore`，​空间，​然后该名称的章节您希望CIDRAM忽略（看下面的例子）。

```
Ignore 章节一
```

这也可以通过使用CIDRAM前端的“章节列表”页面提供的接口来实现。

##### 7.3.1 辅助规则

如果您觉得编写自己的自定义签名文件或自定义模块对您来说太复杂了，更简单的替代方案可能是使用CIDRAM前端的“辅助规则”页面提供的接口。​通过选择适当的选项并指定有关特定类型的请求的详细信息，您可以指示CIDRAM如何响应这些请求。​在所有签名文件和模块已经完成执行之后执行“辅助规则”。

#### 7.4 <a name="MODULE_BASICS"></a>基本概念（对于模块）

模块可用于扩展CIDRAM的功能，执行额外的任务，或处理额外的逻辑。​通常，当除了起源IP地址之外的原因需要阻止请求时它们使用​（因此，当CIDR签名不足以阻止请求）。​模块被写为PHP文件，因此，通常，模块签名被写为PHP代码。

CIDRAM模块的一些很好的例子可以在这里找到：
- https://github.com/CIDRAM/CIDRAM-Extras/tree/master/modules

编写新模块的模板可以在这里找到：
- https://github.com/CIDRAM/CIDRAM-Extras/blob/master/modules/module_template.php

由于模块是作为PHP文件编写的，如果您对CIDRAM代码库有足够的了解，则可以根据需要构建模块，并根据需要编写模块签名​（在合理范围的什么可以用PHP来完成内）。​但是，为了您自己的方便，并为了介于存在的模块和您自己的之间好的理解，建议分析上面链接的模板，以便能够使用它提供的结构和格式。

*注意：如果您不舒服使用PHP代码，则不建议编写自己的模块。*

CIDRAM提供了一些用于模块的功能，这将使编写自己的模块变得更简单和容易。​有关此功能的信息如下所述。

#### 7.5 模块功能

##### 7.5.0 “$Trigger”

模块签名通常使用`$Trigger`编写。​在大多数情况下，为了编写模块，这个闭包比其他任何东西都重要。

`$Trigger`接受4个参数：`$Condition`、`$ReasonShort`、`$ReasonLong`（可选的）、和`$DefineOptions`（可选的）。

`$Condition`感实性被评估，和如果是true（真），签名是“触发”。​如果是false（假），签名不是“触发”。​`$Condition`通常包含PHP代码来评估应该导致请求被阻止的条件。

当签名被“触发”时，`$ReasonShort`在“为什么被阻止”字段中被引用。

`$ReasonLong`是一个可选消息，当用户/客户端被阻止时显示给用户/客户端，解释为什么他们被阻止。​省略时默认为标准的“拒绝访问”消息。

`$DefineOptions`是一个包含键/值对的可选数组，用于定义特定于请求实例的配置选项。​配置选项将在签名被“触发”时应用。

`$Trigger`当签名是“触发”时将返回true（真），当签名不是“触发”时将返回false（假）。

要在模块中使用这个闭包，首先要记住从父范围继承它：
```PHP
$Trigger = $CIDRAM['Trigger'];
```

##### 7.5.1 “$Bypass”

签名旁路通常使用`$Bypass`编写。

`$Bypass`接受3个参数：`$Condition`、`$ReasonShort`、和`$DefineOptions`（可选的）。

`$Condition`感实性被评估，和如果是true（真），旁路是“触发”。​如果是false（假），旁路不是“触发”。​`$Condition`通常包含PHP代码来评估应不该导致请求被阻止的条件。

当旁路被“触发”时，`$ReasonShort`在“为什么被阻止”字段中被引用。

`$DefineOptions`是一个包含键/值对的可选数组，用于定义特定于请求实例的配置选项。​配置选项将在旁路被“触发”时应用。

`$Bypass`当旁路是“触发”时将返回true（真），当旁路不是“触发”时将返回false（假）。

要在模块中使用这个闭包，首先要记住从父范围继承它：
```PHP
$Bypass = $CIDRAM['Bypass'];
```

##### 7.5.2 “$CIDRAM['DNS-Reverse']”

这可以用来获取IP地址的主机名。​如果您想创建一个模块来阻止主机名，这个闭包可能是有用的。

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

#### 7.6 模块变量

模块在其自己的范围内执行，并且由模块定义的任何变量将不能被其他模块访问，也不由父脚本，除非它们存储在`$CIDRAM`数组中（模块执行完成后，其他所有内容都将被擦洗）。

下面列出了一些可能对您的模块有用的常见变量：

变量 | 说明
----|----
`$CIDRAM['BlockInfo']['DateTime']` | 当前日期和时间。
`$CIDRAM['BlockInfo']['IPAddr']` | 当前请求的IP地址。
`$CIDRAM['BlockInfo']['ScriptIdent']` | CIDRAM脚本版本。
`$CIDRAM['BlockInfo']['Query']` | 当前请求的查询。
`$CIDRAM['BlockInfo']['Referrer']` | 当前请求的引用者（如果存在）。
`$CIDRAM['BlockInfo']['UA']` | 当前请求的用户代理【user agent】。
`$CIDRAM['BlockInfo']['UALC']` | 当前请求的用户代理【user agent】（小写）。
`$CIDRAM['BlockInfo']['ReasonMessage']` | 当前请求被阻止时显示给用户/客户端的消息。
`$CIDRAM['BlockInfo']['SignatureCount']` | 当前请求的触发的签名数量。
`$CIDRAM['BlockInfo']['Signatures']` | 针对当前请求触发的任何签名的参考信息。
`$CIDRAM['BlockInfo']['WhyReason']` | 针对当前请求触发的任何签名的参考信息。

---


### 8. <a name="SECTION8"></a>已知的兼容问题

下列软件包和产品被发现与CIDRAM不兼容：
- __[Endurance Page Cache](https://github.com/CIDRAM/CIDRAM/issues/52)__
- __[Mix.com](https://github.com/CIDRAM/CIDRAM/issues/80)__

已提供模块以确保以下软件包和产品与CIDRAM兼容：
- __[BunnyCDN](https://github.com/CIDRAM/CIDRAM/issues/56)__

*也可以看看：​[兼容性图表](https://maikuolan.github.io/Compatibility-Charts/)。*

---


### 9. <a name="SECTION9"></a>常见问题（FAQ）

- [什么是“签名”？](#WHAT_IS_A_SIGNATURE)
- [什么是“CIDR”？](#WHAT_IS_A_CIDR)
- [什么是“假阳性”？](#WHAT_IS_A_FALSE_POSITIVE)
- [CIDRAM可以阻止整个国家吗？](#BLOCK_ENTIRE_COUNTRIES)
- [什么是签名更新频率？](#SIGNATURE_UPDATE_FREQUENCY)
- [我在使用CIDRAM时遇到问题和我不知道该怎么办！​请帮忙！](#ENCOUNTERED_PROBLEM_WHAT_TO_DO)
- [因为CIDRAM，​我被阻止从我想访问的网站！​请帮忙！](#BLOCKED_WHAT_TO_DO)
- [我想使用CIDRAM（在v2之前）与早于5.4.0的PHP版本；​您能帮我吗？](#MINIMUM_PHP_VERSION)
- [我想使用CIDRAM（在v2期间）与早于7.2.0的PHP版本；​您能帮我吗？](#MINIMUM_PHP_VERSION_V2)
- [我可以使用单个CIDRAM安装来保护多个域吗？](#PROTECT_MULTIPLE_DOMAINS)
- [我不想浪费时间安装这个和确保它在我的网站上功能正常；我可以雇用您这样做吗？](#PAY_YOU_TO_DO_IT)
- [我可以聘请您或这个项目的任何开发者私人工作吗？](#HIRE_FOR_PRIVATE_WORK)
- [我需要专家修改，​的定制，​等等；您能帮我吗？](#SPECIALIST_MODIFICATIONS)
- [我是开发人员，​网站设计师，​或程序员。​我可以接受还是提供与这个项目有关的工作？](#ACCEPT_OR_OFFER_WORK)
- [我想为这个项目做出贡献；我可以这样做吗？](#WANT_TO_CONTRIBUTE)
- [可以使用cron自动更新吗？](#CRON_TO_UPDATE_AUTOMATICALLY)
- [什么是“违规”？](#WHAT_ARE_INFRACTIONS)
- [CIDRAM可以阻止主机名？](#BLOCK_HOSTNAMES)
- [在“default_dns”中我可以使用什么？](#WHAT_CAN_I_USE_FOR_DEFAULT_DNS)
- [我可以使用CIDRAM保护网站以外的东西吗（例如，电子邮件服务器，FTP，SSH，IRC，等）？](#PROTECT_OTHER_THINGS)
- [如果我在使用CDN或缓存服务的同时使用CIDRAM，会发生问题吗？](#CDN_CACHING_PROBLEMS)
- [CIDRAM会保护我的网站免受DDoS攻击吗？](#DDOS_ATTACKS)
- [当我通过更新页面启用或禁用模块或签名文件时，它会在配置中它们将按字母数字排序。​我可以改变他们排序的方式吗？](#CHANGE_COMPONENT_SORT_ORDER)

#### <a name="WHAT_IS_A_SIGNATURE"></a>什么是“签名”？

在CIDRAM的上下文中，​“签名”是一些数据，​它表示/识别我们正在寻找的东西，​通常是IP地址或CIDR，​并包含一些说明，​告诉CIDRAM最好的回应方法当它遇到我们正在寻找的。​CIDRAM的典型签名如下所示：

对于“签名文件”：

`1.2.3.4/32 Deny Generic`

对于“模块”：

```PHP
$Trigger(strpos($CIDRAM['BlockInfo']['UA'], 'Foobar') !== false, 'Foobar-UA', 'User agent "Foobar" not allowed.');
```

*注意：“签名文件”的签名，和“模块”的签名，不是一回事。*

经常（但不总是），​签名是捆绑在一起，​形成“签名章节”，​经常伴随评论，​标记，​和/或相关元数据。​这可以用于为签名提供附加上下文和/或附加说明。

#### <a name="WHAT_IS_A_CIDR"></a>什么是“CIDR”？

“CIDR” 是 “Classless Inter-Domain Routing” 的首字母缩写 （“无类别域间路由”） *【[1](https://zh.wikipedia.org/wiki/%E6%97%A0%E7%B1%BB%E5%88%AB%E5%9F%9F%E9%97%B4%E8%B7%AF%E7%94%B1), [2](https://whatismyipaddress.com/cidr)】*。​这个首字母缩写用于这个包的名称，​“CIDRAM”，​是 “Classless Inter-Domain Routing Access Manager” 的首字母缩写 （“无类别域间路由访问管理器”）。

然而，​在CIDRAM的上下文中（如，​在本文档中，​在CIDRAM的讨论中，​或在CIDRAM语言数据中），​当“CIDR”（单数）或“CIDRs”（复数）被提及时（因此当我们用这些词作为名词在自己的权利，​而不作为首字母缩写），​我们的意图是一个子网，​用CIDR表示法表示。​使用CIDR/CIDRs而不是子网的原因是澄清它是用CIDR表示法表示的子网是我们的意思 （因为子网可以用几种不同的方式表达）。​因此，​CIDRAM可以被认为是“子网访问管理器”。

这个双重含义可能看起来很歧义，​但这个解释并提供上下文应该有助于解决这个歧义。

#### <a name="WHAT_IS_A_FALSE_POSITIVE"></a>什么是“假阳性”？

术语“假阳性”（*或者：“假阳性错误”；“虚惊”*；英语：*false positive*； *false positive error*； *false alarm*），​很简单地描述，​和在一个广义上下文，​被用来当测试一个因子，​作为参考的测试结果，​当结果是阳性（即：因子被确定为“阳性”，​或“真”），​但预计将为（或者应该是）阴性（即：因子，​在现实中，​是“阴性”，​或“假”）。​一个“假阳性”可被认为是同样的“哭狼” (其中，​因子被测试是是否有狼靠近牛群，​因子是“假”由于该有没有狼靠近牛群，​和因子是报告为“阳性”由牧羊人通过叫喊“狼，​狼”），​或类似在医学检测情况，​当患者被诊断有一些疾病，​当在现实中，​他们没有疾病。

一些相关术语是“真阳性”，​“真阴性”和“假阴性”。​一个“真阳性”指的是当测试结果和真实因子状态都是“真”（或“阳性”），​和一个“真阴性”指的是当测试结果和真实因子状态都是“假”（或“阴性”）；一个“真阳性”或“真阴性”被认为是一个“正确的推理”。​对立面“假阳性”是一个“假阴性”；一个“假阴性”指的是当测试结果是“阴性”（即：因子被确定为“阴性”，​或“假”），​但预计将为（或者应该是）阳性（即：因子，​在现实中，​是“阳性”，​或“真”）。

在CIDRAM的上下文，​这些术语指的是CIDRAM的签名和什么/谁他们阻止。​当CIDRAM阻止一个IP地址由于恶劣的，​过时的，​或不正确的签名，​但不应该这样做，​或当它这样做为错误的原因，​我们将此事件作为一个“假阳性”。​当CIDRAM未能阻止IP地址该应该已被阻止，​由于不可预见的威胁，​缺少签名或不足签名，​我们将此事件作为一个“检测错过”（同样的“假阴性”）。

这可以通过下表来概括：

&nbsp; | CIDRAM不应该阻止IP地址 | CIDRAM应该阻止IP地址
---|---|---
CIDRAM不会阻止IP地址 | 真阴性（正确的推理） | 检测错过（同样的“假阴性”）
CIDRAM会阻止IP地址 | __假阳性__ | 真阳性（正确的推理）

#### <a name="BLOCK_ENTIRE_COUNTRIES"></a>CIDRAM可以阻止整个国家吗？

它可以。​实现这个最简单的方法是安装一些由Macmathan提供的可选的国家阻止列表。​这可以通过直接从前端更新页面的几个简单的点击完成，​或，​如果您希望前端保持停用状态，​通过直接从下载页面下载它们。​通过直接从 **[可选的国家阻止列表下载页面](https://bitbucket.org/macmathan/blocklists)** 下载它们，​上传他们到vault，​在相关配置指令中引用它们的名称。

#### <a name="SIGNATURE_UPDATE_FREQUENCY"></a>什么是签名更新频率？

更新频率根据相关的签名文件而有所不同。​所有的CIDRAM签名文件的维护者通常尽量保持他们的签名为最新，​但是因为我们所有人都有各种其他承诺，​和因为我们的生活超越了项目，​和因为我们不得到经济补偿/付款为我们的项目的努力，​无法保证精确的更新时间表。​通常，​签名被更新每当有足够的时间，​和维护者尝试根据必要性和根据范围之间的变化频率确定优先级。​帮助总是感谢，​如果您愿意提供任何。

#### <a name="ENCOUNTERED_PROBLEM_WHAT_TO_DO"></a>我在使用CIDRAM时遇到问题和我不知道该怎么办！​请帮忙！

- 您使用软件的最新版本吗？​您使用签名文件的最新版本吗？​如果这两个问题的答案是不，​尝试首先更新一切，​然后检查问题是否仍然存在。​如果它仍然存在，​继续阅读。
- 您检查过所有的文档吗？​如果没有做，​请这样做。​如果文档不能解决问题，​继续阅读。
- 您检查过[issues页面](https://github.com/CIDRAM/CIDRAM/issues)吗？​检查是否已经提到了问题。​如果已经提到了，​请检查是否提供了任何建议，​想法或解决方案。​按照需要尝试解决问题。
- 如果问题仍然存在，请通过在issues页面上创建新issue寻求帮助。

#### <a name="BLOCKED_WHAT_TO_DO"></a>因为CIDRAM，​我被阻止从我想访问的网站！​请帮忙！

CIDRAM使网站所有者能够阻止不良流量，​但网站所有者有责任为自己决定如何使用CIDRAM。​在关于默认签名文件假阳性的情况下，​可以进行校正。​但关于从特定网站解除阻止，​您需要与相关网站的所有者进行沟通。​当进行校正时，​至少，​他们需要更新他们的签名文件和/或安装。​在其他情况下（例如，​当他们修改了他们的安装，​当他们创建自己的自定义签名，​等等），​解决您的问题的责任完全是他们的，​并完全不在我们的控制之内。

#### <a name="MINIMUM_PHP_VERSION"></a>我想使用CIDRAM（在v2之前）与早于5.4.0的PHP版本；​您能帮我吗？

不能。PHP >= 5.4.0是CIDRAM < v2的最低要求。

#### <a name="MINIMUM_PHP_VERSION_V2"></a>我想使用CIDRAM（在v2期间）与早于7.2.0的PHP版本；​您能帮我吗？

不能。PHP >= 7.2.0是CIDRAM v2的最低要求。

*也可以看看：​[兼容性图表](https://maikuolan.github.io/Compatibility-Charts/)。*

#### <a name="PROTECT_MULTIPLE_DOMAINS"></a>我可以使用单个CIDRAM安装来保护多个域吗？

可以。​CIDRAM安装未绑定到特定域，​因此可以用来保护多个域。​通常，​当CIDRAM安装保护只一个域，​我们称之为“单域安装”，​和当CIDRAM安装保护多个域和/或子域，​我们称之为“多域安装”。​如果您进行多域安装并需要使用不同的签名文件为不同的域，​或需要不同配置CIDRAM为不同的域，​这可以做到。​加载配置文件后（`config.ini`），​CIDRAM将寻找“配置覆盖文件”特定于所请求的域（`xn--cjs74vvlieukn40a.tld.config.ini`），​并如果发现，​由配置覆盖文件定义的任何配置值将用于执行实例而不是由配置文件定义的配置值。​配置覆盖文件与配置文件相同，​并通过您的决定，​可能包含CIDRAM可用的所有配置指令，​或任何必需的章节当需要。​配置覆盖文件根据它们旨在的域来命名（所以，​例如，​如果您需要一个配置覆盖文件为域，​`http://www.some-domain.tld/`，​它的配置覆盖文件应该被命名`some-domain.tld.config.ini`，​和它应该放置在`vault`与配置文件，​`config.ini`）。​域名是从标题`HTTP_HOST`派生的；“www”被忽略。

#### <a name="PAY_YOU_TO_DO_IT"></a>我不想浪费时间安装这个和确保它在我的网站上功能正常；我可以雇用您这样做吗？

也许。​这是根据具体情况考虑的。​告诉我们您需要什么，​您提供什么，​和我们会告诉您是否可以帮忙。

#### <a name="HIRE_FOR_PRIVATE_WORK"></a>我可以聘请您或这个项目的任何开发者私人工作吗？

*参考上面。​*

#### <a name="SPECIALIST_MODIFICATIONS"></a>我需要专家修改，​的定制，​等等；您能帮我吗？

*参考上面。​*

#### <a name="ACCEPT_OR_OFFER_WORK"></a>我是开发人员，​网站设计师，​或程序员。​我可以接受还是提供与这个项目有关的工作？

您可以。​我们的许可证并不禁止这一点。

#### <a name="WANT_TO_CONTRIBUTE"></a>我想为这个项目做出贡献；我可以这样做吗？

您可以。​对项目的贡献是欢迎。​有关详细信息，​请参阅“CONTRIBUTING.md”。

#### <a name="CRON_TO_UPDATE_AUTOMATICALLY"></a>可以使用cron自动更新吗？

您可以。​前端有内置了API，外部脚本可以使用它与更新页面进行交互。​一个单独的脚本，“[Cronable](https://github.com/Maikuolan/Cronable)”，是可用，它可以由您的cron manager或cron scheduler程序使用于自动更新此和其他支持的包（此脚本提供自己的文档）。

#### <a name="WHAT_ARE_INFRACTIONS"></a>什么是“违规”？

“违规”决定何时还没有被任何特定签名文件阻止的IP应该开始被阻止以将来的任何请求，​他们与IP跟踪密切相关。​一些功能和模块允许请求由于起源IP以外的原因被阻塞（例如，spambot或hacktool用户代理【user agent】，危险的查询，假的DNS，等等），当发生这种情况时，可能会发生“违规”。​这提供了一种识别不需要的请求的IP地址的方法（如果被任何特定的签名文件的不被阻止已经）。​违规通常与IP被阻止的次数是1比1，但不总是（在严重事件中，可能会产生大于1的违规值，如果“track_mode”是假的【false】，对于仅由签名文件触发的阻止事件，不会发生违规）。

#### <a name="BLOCK_HOSTNAMES"></a>CIDRAM可以阻止主机名？

可以做。您将需要创建一个自定义模块文件。 *看到：[基本概念（对于模块）](#MODULE_BASICS)*.

#### <a name="WHAT_CAN_I_USE_FOR_DEFAULT_DNS"></a>在“default_dns”中我可以使用什么？

通常，任何可靠的DNS服务器的IP应该就足够了。​如果您正在寻找建议，[public-dns.info](https://public-dns.info/)和[OpenNIC](https://servers.opennic.org/)提供已知的公共DNS服务器的广泛列表。​或者，请参阅下表：

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

*注意：​我对任何列出的DNS服务或其他方式的隐私惯例，安全性，功效和可靠性不做任何声明或保证。​请在做出关于他们的决定时做您自己的研究。*

#### <a name="PROTECT_OTHER_THINGS"></a>我可以使用CIDRAM保护网站以外的东西吗（例如，电子邮件服务器，FTP，SSH，IRC，等）？

您可以（在法律意义上说），但不应该（在技术和实际意义上）。​我们的许可证不限制哪些技术实施CIDRAM，但CIDRAM是一种WAF（Web应用程序防火墙），一直旨在保护网站。​因为它没有考虑到其他技术，所以它不太可能有效或为其他技术提供可靠的保护，实施可能会很困难，并且假阳性和检测错过的风险非常高。

#### <a name="CDN_CACHING_PROBLEMS"></a>如果我在使用CDN或缓存服务的同时使用CIDRAM，会发生问题吗？

也许。​这取决于相关服务的性质以及您如何使用它。​通常，如果您只缓存静态资产（例如，图像，CSS，等；任何通常不会随时间变化的东西），则不应该有任何问题。​但是，如果您要缓存的数据通常会在请求时动态生成，或者如果您正在缓存POST请求的结果，那么可能会有问题（这基本上会使您的网站及其环境成为强制静态，并且CIDRAM不太可能在强制静态环境中提供任何有意义的好处）。​CIDRAM也可能有特定的配置要求，具体取决于您使用的CDN或缓存服务（您需要确保为您正在使用的特定CDN或缓存服务正确配置CIDRAM）。​未能正确配置CIDRAM可能会导致严重假阳性和检测错过。

#### <a name="DDOS_ATTACKS"></a>CIDRAM会保护我的网站免受DDoS攻击吗？

总之：不，它不能。

更详细地说阐述：CIDRAM将有助于减少不需要的流量的可能造成的影响，为您的网站（从而降低带宽成本），为您的硬件（例如，您的服务器处理请求的能力），并可以帮助减少各种其他潜在的负面影响。​然而，为了理解这个问题，必须记住两件重要的事情。

首先，CIDRAM是一个PHP包，因此可以在安装PHP的机器上运行。​这意味着CIDRAM只能在服务器收到请求后才能看到并阻止请求。​其次，有效的DDoS缓解应该在请求到达DDoS攻击所针对的服务器之前对其进行过滤。​理想情况下，DDoS攻击应该在能够首先到达目标服务器之前通过能够丢弃或重新路由与攻击相关的流量的解决方案来检测和缓解。

这可以使用专用的内部部署硬件解决方案，基于云的解决方案，如专用的DDoS缓解服务，通过耐DDoS网络路由域名的DNS，基于云的过滤，或者它们的一些组合实施。​无论如何，这个问题有点太复杂，不能仅仅用一到两个段落来解释，所以如果这是您想追求的主题，我会建议您做进一步的研究。​当DDoS攻击的本质被正确理解时，这个答案会更有意义。

#### <a name="CHANGE_COMPONENT_SORT_ORDER"></a>当我通过更新页面启用或禁用模块或签名文件时，它会在配置中它们将按字母数字排序。​我可以改变他们排序的方式吗？

这个有可能。​如果您需要强制某些文件以特定顺序执行，您可以在列出配置指令的位置中的在他们的名字之前添加一些任意数据，并用冒号分隔。​当更新页面随后再次对文件进行排序时，这个添加的任意数据会影响排序顺序，因此导致它们按照您想要的顺序执行，并且不需要重命名它们。

例如，假设配置指令包含如下列出的文件：

`file1.php,file2.php,file3.php,file4.php,file5.php`

如果您想首先执行`file3.php`，您可以在文件名前添加`aaa:`或类似：

`file1.php,file2.php,aaa:file3.php,file4.php,file5.php`

然后，如果启用了新文件`file6.php`，当更新页面再次对它们进行排序时，它应该像这样结束：

`aaa:file3.php,file1.php,file2.php,file4.php,file5.php,file6.php`

当文件禁用时的情况是相同的。​相反，如果您希望文件最后执行，您可以在文件名前添加`zzz:`或类似。​在任何情况下，您都不需要重命名相关文件。

---


### 11. <a name="SECTION11"></a>法律信息

#### 11.0 章节前言

本文档章节描述了有关该软件包的使用和实施的可能法律考虑事项，并提供一些基本的相关信息。​这对于一些用户来说可能很重要，作为确保遵守其运营所在国家可能存在的任何法律要求的一种手段。​一些用户可能需要根据这些信息调整他们的网站政策。

首先，请认识到我（软件包作者）不是律师或合格的法律专业人员。​因此，我无法合法提供法律建议。​此外，在某些情况下，不同国家和地区的具体法律要求可能会有所不同。​这些不同的法律要求有时可能会相互矛盾​（例如：支持[隐私权](https://zh.wikipedia.org/wiki/%E9%9A%90%E7%A7%81%E6%9D%83)和[被遺忘權](https://zh.wikipedia.org/wiki/%E8%A2%AB%E9%81%BA%E5%BF%98%E6%AC%8A)的国家，与支持扩展数据保留的国家相比）。​还要考虑到对软件包的访问不限于特定的国家或辖区，因此，软件包用户群很可能在地理上多样化。​这些观点认为，我无法说明在所有方面对所有用户“符合法律”意味着什么。​不过，我希望这里的信息能够帮助您自己决定您必须做些什么为了在软件包的上下文中符合法律。​如果您对此处的信息有任何疑问或担忧，或者您需要从法律角度提供更多帮助和建议，我会建议咨询合格的法律专业人员。

#### 11.1 法律责任

此软件包不提供任何担保（这已由包许可证提及）。​这包括（但不限于）所有责任范围。​为了您的方便，该软件包已提供给您。​希望它会有用，它会为您带来一些好处。​但是，使用或实施该软件包是您自己的选择。​您不是被迫使用或实施该软件包，但是当您这样做时，您需要对该决定负责。​我，和其他软件包贡献者，对于您的决定的后果不承担法律责任，无论是直接的，间接的，暗示的，还是其他方式。

#### 11.2 第三方

取决于其确切的配置和实施，在某些情况下，该软件包可能与第三方进行通信和共享信息。​在某些情况下，某些辖区可能会将此信息定义为“个人身份信息”（PII）。

这些信息如何被这些第三方使用，是受这些第三方制定的各种政策的约束，并且超出了本文档的范围。​但是，在所有这些情况下，与这些第三方共享信息可能被禁用。​在所有这些情况下，如果您选择启用它，则有责任研究您可能遇到的任何问题（如果您担心这些第三方的隐私，安全，和PII使用情况）。​如果存在任何疑问，或者您对PII方面的这些第三方的行为不满意，最好禁用与这些第三方分享的所有信息。

为了透明的目的，共享信息的类型，以及与谁共享，如下所述。

##### 11.2.0 主机名查找

如果您使用任何旨在与主机名配合使用的功能或模块（例如，​“坏主机阻塞模块”，​“tor project exit nodes block module”，​“搜索引擎验证”），​CIDRAM需要能够以某种获得入站请求的主机名。​通常，它通过请求来自DNS服务器的入站请求的IP地址的主机名来执行此操作，或者通过安装CIDRAM的系统提供的功能请求信息（这通常被称为“主机名查找”）。​默认定义的DNS服务器属于[Google DNS](https://dns.google.com/)服务（但可以通过配置轻松更改）。​与之交流的确切服务是可配置的，并取决于您如何配置软件包。​在使用安装CIDRAM的系统提供的功能的情况下，您需要联系您的系统管理员以确定哪些为主机名查找的路由使用。​通过避免受影响的模块或根据您的需要修改软件包配置，可以防止CIDRAM中的主机名查找。

*相关配置指令：*
- `general` -> `default_dns`
- `general` -> `search_engine_verification`
- `general` -> `social_media_verification`
- `general` -> `force_hostname_lookup`
- `general` -> `allow_gethostbyaddr_lookup`

##### 11.2.1 网络字体

一些自定义主题，以及CIDRAM前端的标准UI（“用户界面”），和“拒绝访问”页面可能出于审美原因使用网络字体。​网络字体默认是禁用，但启用后，用户的浏览器和托管网络字体的服务之间会发生直接通信。​这可能涉及传递信息，例如用户的IP地址，用户代理，操作系统，以及请求可用的其他详细信息。​大部分这些网络字体都由[Google Fonts](https://fonts.google.com/)服务托管。

*相关配置指令：*
- `general` -> `disable_webfonts`

##### 11.2.2 搜索引擎验证和社交媒体验证

当启用搜索引擎验证时，CIDRAM尝试执行“正向DNS查找”以验证声称源自搜索引擎的请求是否真实。​同样，当启用社交媒体验证时，CIDRAM对为社交媒体请求做同样的事情。​为此，它使用[Google DNS](https://dns.google.com/)服务尝试从这些入站请求的主机名解析IP地址（在这个过程中，这些入站请求的主机名与服务共享）。

*相关配置指令：*
- `general` -> `search_engine_verification`
- `general` -> `social_media_verification`

##### 11.2.3 GOOGLE reCAPTCHA

CIDRAM可选的支持Google reCAPTCHA，为用户提供了一种通过完成reCAPTCHA实例绕过“拒绝访问”页面的方式​（关于此功能的更多信息在前面的文档中有介绍，特别是在配置章节）。​Google reCAPTCHA需要API密钥才能正常工作，因此默认情况下禁用。​可以通过在包配置中定义所需的API密钥来启用它。​启用后，用户的浏览器与reCAPTCHA服务之间会进行直接通信。​这可能涉及传递信息，例如用户的IP地址，用户代理，操作系统以及请求可用的其他详细信息。​在验证reCAPTCHA实例的有效性并验证它是否成功完成时，用户的IP地址也可以在CIDRAM和reCAPTCHA服务之间的通信中共享。

*相关配置指令：在“recaptcha”配置类别下列出的任何内容。*

##### 11.2.4 STOP FORUM SPAM 【停止论坛垃圾邮件】

[Stop Forum Spam](https://www.stopforumspam.com/)是一个辉煌的，免费提供的服务，可以帮助保护论坛，博客，和网站免受垃圾邮件制造者。​它提供了一个已知垃圾邮件发送者的数据库，以及一个可用来检查数据库中是否列出IP地址，用户名或电子邮件地址的API。

CIDRAM提供了一个可选模块，它使用API来检查入站请求的IP地址是否属于可疑垃圾邮件发送者。​默认情况下该模块不是安装，但如果选择安装该模块，则可以根据模块的预期用途将用户的IP地址与Stop Forum Spam【停止论坛垃圾邮件】API共享。​安装模块时，当入站请求请求的资源是CIDRAM识别为垃圾邮件发送者经常目标的资源时（如登录页面，注册页面，电子邮件验证页面，评论表单，等等），CIDRAM就会与此API通信。

#### 11.3 日志记录

由于多种原因，日志记录是CIDRAM的重要组成部分。​当未记录导致它们的阻止事件时，可能难以诊断和解决假阳性。​当未记录阻止事件时，可能很难确定CIDRAM在某些情况下的表现如何，而且可能很难确定其不足之处，以及可能需要更改哪些配置或签名，以使其继续按预期运行。​无论如何，一些用户可能不想要记录，并且它仍然是完全可选的。​在CIDRAM中，默认情况下日志记录是禁用。​要启用它，必须相应地配置CIDRAM。

另外，如果日志记录在法律上是允许的，并且在法律允许的范围内（例如，可记录的信息类型，多长时间，在什么情况下），可以变化，具体取决于管辖区域和CIDRAM的实施上下文（例如，如果您是个人或公司实体经营，如果您在商业或非商业基础上运营，等等）。​因此，仔细阅读本节可能对您有用。

CIDRAM可以执行多种类型的日志记录。​不同类型的日志记录涉及不同类型的信息，出于各种原因。

##### 11.3.0 阻止事件

CIDRAM可以执行的主要日志记录类型与“阻止事件”有关。​当CIDRAM阻止请求时会发生这种日志记录类型，并且可以以三种不同的格式提供：
- 人类可读的日志文件。
- Apache风格的日志文件。
- 序列化日志文件。

记录到人类可读日志文件的阻止事件通常看起来像这样（作为示例）：

```
ID： 1234
脚本版本： CIDRAM v1.6.0
日期/时间： Day, dd Mon 20xx hh:ii:ss +0000
IP地址： x.x.x.x
主机名： dns.hostname.tld
签名计数： 1
签名参考： x.x.x.x/xx
为什么被阻止： 云服务 ("网络名字", Lxx:Fx, [XX])!
用户代理： Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.181 Safari/537.36
重建URI： http://your-site.tld/index.php
reCAPTCHA状态： 打开。
```

记录到Apache样式的日志文件中的同一阻止事件看起来像这样：

```
x.x.x.x - - [Day, dd Mon 20xx hh:ii:ss +0000] "GET /index.php HTTP/1.1" 200 xxxx "-" "Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.181 Safari/537.36"
```

阻止事件记录通常包括以下信息：
- 阻止事件的ID号码。
- 目前正在使用的CIDRAM版本。
- 阻止事件发生的日期和时间。
- 被阻止请求的IP地址。
- 被阻止请求的IP地址的主机名（如果可用）。
- 请求触发的签名数。
- 触发的签名引用。
- 引用阻止事件的原因和一些基本的相关调试信息。
- 被阻止请求的用户代理（即，请求实体如何向请求标识自己）。
- 重建最初请求的资源的标识符。
- 当前请求的reCAPTCHA状态（相关时）。

*负责此类日志记录的配置指令，适用于以下三种格式中的每一种：*
- `general` -> `logfile`
- `general` -> `logfileApache`
- `general` -> `logfileSerialized`

当这些指令保留为空时，这种类型的日志记录将保持禁用状态。

##### 11.3.1 reCAPTCHA日志记录

此类日志记录特定于reCAPTCHA实例，仅在用户尝试完成reCAPTCHA实例时才会发生。

reCAPTCHA日志条目包含尝试完成reCAPTCHA实例的用户的IP地址，尝试发生的日期和时间以及reCAPTCHA状态。​reCAPTCHA日志条目通常看起来像这样（作为示例）：

```
IP地址：x.x.x.x - Date/Time: Day, dd Mon 20xx hh:ii:ss +0000 - reCAPTCHA状态：成功！
```

*负责reCAPTCHA日志记录的配置指令是：*
- `recaptcha` -> `logfile`

##### 11.3.2 前端日志记录

此类日志记录涉及前端登录尝试，仅在用户尝试登录前端时才会发生（假设启用了前端访问）。

前端日志条目包含尝试登录的用户的IP地址，尝试发生的日期和时间以及的结果（登录成功或失败）。​前端日志条目通常看起来像这样（作为示例）：

```
x.x.x.x - Day, dd Mon 20xx hh:ii:ss +0000 - "admin" - 已登录。
```

*负责前端日志记录的配置指令是：*
- `general` -> `FrontEndLog`

##### 11.3.3 日志轮换

您可能希望在一段时间后清除日志，或者可能被要求依法执行（即，您在法律上允许保留日志的时间可能受法律限制）。​您可以通过在程序包配置指定的日志文件名中包含日期/时间标记（例如，`{yyyy}-{mm}-{dd}.log`），​然后启用日志轮换来实现此目的（日志轮换允许您在超出指定限制时对日志文件执行某些操作）。

例如：如果法律要求我在30天后删除日志，我可以在我的日志文件的名称中指定`{dd}.log`（`{dd}`代表天），将`log_rotation_limit`的值设置为30，并将`log_rotation_action`的值设置为`Delete`。

相反，如果您需要长时间保留日志，你可以选择完全不使用日志轮换，或者你可以将`log_rotation_action`的值设置为`Archive`，以压缩日志文件，从而减少它们占用的磁盘空间总量。

*相关配置指令：*
- `general` -> `log_rotation_limit`
- `general` -> `log_rotation_action`

##### 11.3.4 日志截断

如果这是您想要做的事情，也可以在超过特定大小时截断个别日志文件。

*相关配置指令：*
- `general` -> `truncate`

##### 11.3.5 IP地址“PSEUDONYMISATION”

首先，如果您不熟悉这个术语，“pseudonymisation”是指处理个人数据，使其不能在没有补充信息的情况下被识别为属于任何特定的“数据主体”，并规定这些补充信息分开保存，采取技术和组织措施以确保个人数据不能被识别给任何自然人。

以下资源可以帮助更详细地解释它：
- [[trust-hub.com] What is pseudonymisation?](https://www.trust-hub.com/news/what-is-pseudonymisation/)
- [[Wikipedia] Pseudonymization](https://en.wikipedia.org/wiki/Pseudonymization)

在某些情况下，您可能在法律上要求对收集，处理，或存储的任何PII进行“pseudonymise”或“anonymise”。​虽然这个概念已经存在了相当长的一段时间，但GDPR/DSGVO提到，并特别鼓励“pseudonymisation”。

当记录它们时，CIDRAM可以对IP地址进行pseudonymise，如果这是您想做的事情。​当这个情况发生时，IPv4地址的最后八位字节，以及IPv6地址的第二部分之后的所有内容，将由“x”表示（有效地将IPv4地址四舍五入到它的第24个子网因素的初始地址，和将IPv6地址四舍五入到它的第32个子网因素的初始地址）。

*注意：CIDRAM的IP地址pseudonymisation过程不会影响CIDRAM的IP跟踪功能。​如果这对您来说是个问题，最好完全禁用IP跟踪。​这可以通过将`track_mode`设置为`false`并避免使用任何模块来实现。*

*相关配置指令：*
- `signatures` -> `track_mode`
- `legal` -> `pseudonymise_ip_addresses`

##### 11.3.6 省略日志信息

如果要防止完全记录特定类型的信息，也可以这样做。​CIDRAM提供配置指令来控制IP地址，主机名，和用户代理是否包含在日志中。​默认情况下，所有这三个数据点都包含在日志中（如果可用）。​将任何这些配置指令设置为`true`将省略日志中的相应信息。

*注意：当完全从日志中省略IP地址时，没有理由对IP地址进行pseudonymise。*

*相关配置指令：*
- `legal` -> `omit_ip`
- `legal` -> `omit_hostname`
- `legal` -> `omit_ua`

##### 11.3.7 统计

CIDRAM可选择跟踪统计信息，例如自特定时间以来发生的阻止事件或reCAPTCHA实例的总数。​默认情况下此功能是禁用，但可以通过程序包配置启用此功能。​此功能仅跟踪发生的事件总数，不包括有关特定事件的任何信息（因此，不应被视为PII）。

*相关配置指令：*
- `general` -> `statistics`

##### 11.3.8 加密

CIDRAM不[加密](https://zh.wikipedia.org/wiki/%E5%8A%A0%E5%AF%86)其缓存或任何日志信息。​可能会在将来引入缓存和日志加密，但目前没有任何具体的计划。​如果您担心未经授权的第三方获取可能包含PII或敏感信息（如缓存或日志）的CIDRAM部分的访问权限，我建议不要将CIDRAM安装在可公开访问的位置（例如，在标准`public_html`或等效目录之外【可用于大多数标准网络服务器】安装CIDRAM），​也我建议对安装目录强制执行适当的限制权限（特别是对于vault目录）。​如果这还不足以解决您的疑虑，应该配置CIDRAM为不会首先收集或记录引起您关注的信息类型（例如，通过禁用日志记录）。

#### 11.4 COOKIE

CIDRAM在其代码库中的两个点设置cookie。​首先，当用户成功完成reCAPTCHA实例时（这假定`lockuser`设置为`true`），CIDRAM设置cookie，以便能够在后续请求中记住用户已经完成了reCAPTCHA实例，这样就不需要不断要求用户在后续请求中完成reCAPTCHA实例。​其次，当用户成功登录前端时，CIDRAM设置cookie以便能够在后续请求中的记住用户（即，cookie用于向登录会话验证用户身份）。

在这两种情况下，cookie警告显着显示（适用时），警告用户如果他们参与相关操作将设置cookie。 Cookie不会在代码库中的任何其他位置设置。

*注意：CIDRAM针对reCAPTCHA的“invisible”API的特定实现可能与某些司法管辖区的cookie法律不兼容，任何受这些法律约束的网站都应该避免这个API。​选择使用“V2”API，或者简单地完全禁用reCAPTCHA，可能更为可取。*

*相关配置指令：*
- `general` -> `disable_frontend`
- `recaptcha` -> `lockuser`
- `recaptcha` -> `api`

#### 11.5 市场营销和广告

CIDRAM不收集或处理任何信息用于营销或广告目的，既不销售也不从任何收集或记录的信息中获利。​CIDRAM不是商业企业，也不涉及任何商业利益，因此做这些事情没有任何意义。​自项目开始以来就一直如此，今天仍然如此。​此外，做这些事情会对整个项目的精神和预期目的产生反作用，并且只要我继续维护项目，永远不会发生。

#### 11.6 隐私政策

在某些情况下，您可能需要依法在您网站的所有页面和部分上清楚地显示您的隐私政策链接。​这可能为了确保用户充分了解您的隐私惯例，收集的个人身份信息类型以及您打算如何使用它的是很重要。​为了能够在CIDRAM的“拒绝访问”页面上包含这样的链接，提供了配置指令来指定隐私策略的URL。

*注意：​强烈建议您的隐私政策页面不放在CIDRAM的保护之后。​如果CIDRAM保护您的隐私政策页面，并且被CIDRAM阻止的用户点击隐私政策的链接，他们将再次被阻止，并且无法看到您的隐私政策。​理想情况下，您应链接到您的隐私政策的静态副本，例如HTML页面或纯文本文件，该文件不受CIDRAM保护。*

*相关配置指令：*
- `legal` -> `privacy_policy`

#### 11.7 GDPR/DSGVO

“通用数据保护条例”（GDPR）是欧盟法规，自2018年5月25日起生效。​该法规的主要目标是向欧盟公民和居民提供有关其个人数据的控制权，并统一欧盟内有关隐私和个人数据的法规。

该法规包含有关处理任何欧盟“数据主体”（任何已识别或可识别的自然人）的“个人身份信息”（PII）的具体规定。​为了符合条例，“企业”（按照法规的定义），和任何相关的系统和流程必须默认实现“隐私设计”，​必须使用尽可能高的隐私设置，​必须对任何存储或处理的信息实施必要的保护措施（数据的 pseudonymisation 或完整 anonymisation ），​必须明确无误地声明他们收集的数据类型，​他们如何处理数据，​出于何种原因，​他们保留多长时间，​以及他们是否与任何第三方分享这些数据，​与第三方共享的数据类型，​为什么，​等等。

只有按照条例有合法依据才能处理数据。​一般而言，这意味着为了在合法基础上处理数据主体的数据，必须遵守法律义务，或者仅在从数据主体获得明确，明智，明确的同意之后才进行处理。

因为条例的各个方面可能会及时演变，并为了避免过时信息的传播，从权威来源中学习可能会更好的，而不是简单地在包文档中包含相关信息（这个信息可能最终会过时）。

一些推荐的资源用于了解更多信息：
- [关于欧盟GDPR隐私合规，中国数字营销人不得不知的9大问题](http://www.adexchanger.cn/top_news/28813.html)
- [史上最严的隐私条例出台，2018年开始执行](https://zhuanlan.zhihu.com/p/20865602)
- [《欧盟数据保护条例》对中国企业的影响 —- 以阿里巴巴集团为例](https://spiegeler.com/%E3%80%8A%E6%AC%A7%E7%9B%9F%E6%95%B0%E6%8D%AE%E4%BF%9D%E6%8A%A4%E6%9D%A1%E4%BE%8B%E3%80%8B%E5%AF%B9%E4%B8%AD%E5%9B%BD%E4%BC%81%E4%B8%9A%E7%9A%84%E5%BD%B1%E5%93%8D-%E4%BB%A5%E9%98%BF%E9%87%8C/)
- [歐盟個人資料保護法 GDPR 即將上路！與電商賣家息息相關的 Google Analytics 資料保留政策，你瞭解了嗎？](https://shopline.hk/blog/google-analytics-gdpr/)
- [歐盟一般資料保護規範](https://zh.wikipedia.org/wiki/%E6%AD%90%E7%9B%9F%E4%B8%80%E8%88%AC%E8%B3%87%E6%96%99%E4%BF%9D%E8%AD%B7%E8%A6%8F%E7%AF%84)
- [REGULATION (EU) 2016/679 OF THE EUROPEAN PARLIAMENT AND OF THE COUNCIL](https://eur-lex.europa.eu/legal-content/EN/TXT/?uri=celex:32016R0679)

---


最后更新：2019年1月5日。
