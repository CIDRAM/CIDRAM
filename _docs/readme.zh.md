## CIDRAM 中文（简体）文档。

### 内容
- 1. [前言](#SECTION1)
- 2. [如何安装](#SECTION2)
- 3. [如何使用](#SECTION3)
- 4. [前端管理](#SECTION4)
- 5. [文件在包](#SECTION5)
- 6. [配置选项](#SECTION6)
- 7. [签名格式](#SECTION7)
- 8. [常见问题（FAQ）](#SECTION8)

*翻译注释：如果错误（例如，翻译差异，错别字，等等），英文版这个文件是考虑了原版和权威版。如果您发现任何错误，您的协助纠正他们将受到欢迎。*

---


### 1. <a name="SECTION1"></a>前言

CIDRAM （无类别域间路由访问管理器）是一个PHP脚本，旨在保护网站途经阻止请求该从始发IP地址视为不良的流量来源，包括（但不限于）流量该从非人类的访问端点，云服务，垃圾邮件发送者，网站铲运机，等等。它通过计算CIDR的提供的IP地址从入站请求和试图匹配这些CIDR反对它的签名文件（这些签名文件包含CIDR的IP地址视为不良的流量来源）；如果找到匹配，请求被阻止。

*(看到： [什么是“CIDR”？](#WHAT_IS_A_CIDR))。*

CIDRAM COPYRIGHT 2016 and beyond GNU/GPLv2 by Caleb M (Maikuolan)。

本脚本是基于GNU通用许可V2.0版许可协议发布的，您可以在许可协议的允许范围内自行修改和发布，但请遵守GNU通用许可协议。使用脚本的过程中，作者不提供任何担保和任何隐含担保。更多的细节请参见GNU通用公共许可证，下的`LICENSE.txt`文件也可从访问：
- <http://www.gnu.org/licenses/>。
- <http://opensource.org/licenses/>。

现在CIDRAM的代码文件和关联包可以从以下地址免费下载[GitHub](https://cidram.github.io/)。

---


### 2. <a name="SECTION2"></a>如何安装

#### 2.0 安装手工

1） 在阅读到这里之前，我假设您已经下载脚本的一个副本，已解压缩其内容并保存在您的机器的某个地方。现在，您要决定将脚本放在您服务器上的哪些文件夹中，例如`/public_html/cidram/`或其他任何您觉得满意和安全的地方。*上传完成后，继续阅读。。*

2） 重命名`config.ini.RenameMe`到`config.ini`（位于内`vault`），和如果您想（强烈推荐高级用户，但不推荐业余用户或者新手使用这个方法），打开它（这个文件包含所有CIDRAM的可用配置选项；以上的每一个配置选项应有一个简介来说明它是做什么的和它的具有的功能）。按照您认为合适的参数来调整这些选项，然后保存文件，关闭。

3） 上传（CIDRAM和它的文件）到您选定的文件夹（不需要包括`*.txt`/`*.md`文件，但大多数情况下，您应上传所有的文件）。

4） 修改的`vault`文件夹权限为“755”（如果有问题，您可以试试“777”，但是这是不太安全）。注意，主文件夹也应该是该权限，如果遇上其他权限问题，请修改对应文件夹和文件的权限。

5） 接下来，您需要为您的系统或CMS设定启动CIDRAM的钩子。有几种不同的方式为您的系统或CMS设定钩子，最简单的是在您的系统或CMS的核心文件的开头中使用`require`或`include`命令直接包含脚本（这个方法通常会导致在有人访问时每次都加载）。平时，这些都是存储的在文件夹中，例如`/includes`，`/assets`或`/functions`等文件夹，和将经常被命名的某物例如`init.php`，`common_functions.php`，`functions.php`。这是根据您自己的情况决定的，并不需要完全遵守；如果您遇到困难，参观GitHub上的CIDRAM问题页面；可能其他用户或者我自己也有这个问题并且解决了（您需要让我们您在使用哪些CMS）。为了使用`require`或`include`，插入下面的代码行到最开始的该核心文件，更换里面的数据引号以确切的地址的`loader.php`文件（本地地址，不是HTTP地址；它会类似于前面提到的vault地址）。

`<?php require '/user_name/public_html/cidram/loader.php'; ?>`

保存文件，关闭，重新上传。

-- 或替换 --

如果您使用Apache网络服务器并且您可以访问`php.ini`，您可以使用该`auto_prepend_file`指令为任何PHP请求创建附上的CIDRAM。就像是：

`auto_prepend_file = "/user_name/public_html/cidram/loader.php"`

或在该`.htaccess`文件：

`php_value auto_prepend_file "/user_name/public_html/cidram/loader.php"`

6) 这就是一切！ :-)

#### 2.1 与COMPOSER安装

[CIDRAM是在Packagist上](https://packagist.org/packages/cidram/cidram)，所以，如果您熟悉Composer，您可以使用Composer安装CIDRAM（您仍然需要准备配置和钩子；参考“安装手工”步骤2和5）。

`composer require cidram/cidram`

#### 2.2 为WORDPRESS安装

如果要使用CIDRAM与WordPress，您可以忽略上述所有说明。[CIDRAM在WordPress插件数据库中注册](https://wordpress.org/plugins/cidram/)，您可以直接从插件仪表板安装CIDRAM。您可以像其他插件一样安装，不需要添加步骤。与其他安装方法相同，您可以通过修改`config.ini`来或通过使用前端配置页面自定义您的安装。更新CIDRAM通过前端更新页面时，插件版本信息将自动与WordPress同步。

---


### 3. <a name="SECTION3"></a>如何使用

CIDRAM 应自动阻止不良的请求至您的网站，没有任何需求除了初始安装。

更新是手工完成，和您可以定制您的配置和您可以定制什么CIDR被阻止通过修改您的配置文件和/或您的签名文件.

如果您遇到任何假阳性，请联系我让我知道这件事。

---


### 4. <a name="SECTION4"></a>前端管理

#### 4.0 什么是前端。

前端提供了一种方便，轻松的方式来维护，管理和更新CIDRAM安装。 您可以通过日志页面查看，共享和下载日志文件，您可以通过配置页面修改配置，您可以通过更新页面安装和卸载组件，和您可以通过文件管理器上传，下载和修改文件在vault。

默认情况是禁用前端，以防止未授权访问 （未授权访问可能会对您的网站及其安全性造成严重后果）。 启用它的说明包括在本段下面。

#### 4.1 如何启用前端。

1) 里面的`config.ini`文件，找到指令`disable_frontend`，并将其设置为`false` （默认值为`true`）。

2) 从浏览器访问`loader.php` （例如，`http://localhost/cidram/loader.php`）。

3) 使用默认用户名和密码（admin/password）登录。

注意： 第一次登录后，以防止未经授权的访问前端，您应该立即更改您的用户名和密码！ 这是非常重要的，因为它可以任意PHP代码上传到您的网站通过前端。

#### 4.2 如何使用前端。

每个前端页面上都有说明，用于解释正确的用法和它的预期目的。 如果您需要进一步的解释或帮助，请联系支持。 另外，YouTube上还有一些演示视频。


---


### 5. <a name="SECTION5"></a>文件在包
（本段文件采用的自动翻译，因为都是一些文件描述，参考意义不是很大，如有疑问，请参考英文原版）

下面是一个列表的所有的文件该应该是存在在您的存档在下载时间，任何文件该可能创建因之的您的使用这个脚本，包括一个简短说明的他们的目的。

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
/vault/ | 安全/保险库｢Vault｣文件夹（包含若干文件）。
/vault/fe_assets/ | 前端资产。
/vault/fe_assets/.htaccess | 超文本访问文件（在这种情况，以保护敏感文件属于脚本从被访问由非授权来源）。
/vault/fe_assets/_accounts.html | 前端账户页面的HTML模板。
/vault/fe_assets/_accounts_row.html | 前端账户页面的HTML模板。
/vault/fe_assets/_cidr_calc.html | CIDR计算器的HTML模板。
/vault/fe_assets/_cidr_calc_row.html | CIDR计算器的HTML模板。
/vault/fe_assets/_config.html | 前端配置页面的HTML模板。
/vault/fe_assets/_config_row.html | 前端配置页面的HTML模板。
/vault/fe_assets/_files.html | 文件管理器的HTML模板。
/vault/fe_assets/_files_edit.html | 文件管理器的HTML模板。
/vault/fe_assets/_files_rename.html | 文件管理器的HTML模板。
/vault/fe_assets/_files_row.html | 文件管理器的HTML模板。
/vault/fe_assets/_home.html | 端主页的HTML模板。
/vault/fe_assets/_ip_test.html | IP测试页面的HTML模板。
/vault/fe_assets/_ip_test_row.html | IP测试页面的HTML模板。
/vault/fe_assets/_ip_tracking.html | IP跟踪页面的HTML模板。
/vault/fe_assets/_ip_tracking_row.html | IP跟踪页面的HTML模板。
/vault/fe_assets/_login.html | 前端登录的HTML模板。
/vault/fe_assets/_logs.html | 前端日志页面的HTML模板。
/vault/fe_assets/_nav_complete_access.html | 前端导航链接的HTML模板，由那些与完全访问使用。
/vault/fe_assets/_nav_logs_access_only.html | 前端导航链接的HTML模板，由那些与仅日志访问使用。
/vault/fe_assets/_updates.html | 前端更新页面的HTML模板。
/vault/fe_assets/_updates_row.html | 前端更新页面的HTML模板。
/vault/fe_assets/frontend.css | 前端CSS样式表。
/vault/fe_assets/frontend.dat | 前端数据库（包含账户信息，会话信息，和缓存；只生成如果前端是启用和使用）。
/vault/fe_assets/frontend.html | 前端的主HTML模板文件。
/vault/fe_assets/icons.php | 图标处理文件（由前端文件管理器使用）。
/vault/fe_assets/pips.php | 点数处理文件（由前端文件管理器使用）。
/vault/lang/ | 包含CIDRAM语言数据。
/vault/lang/.htaccess | 超文本访问文件（在这种情况，以保护敏感文件属于脚本从被访问由非授权来源）。
/vault/lang/lang.ar.cli.php | 阿拉伯文CLI语言数据。
/vault/lang/lang.ar.fe.php | 阿拉伯文前端语言数据。
/vault/lang/lang.ar.php | 阿拉伯文语言数据。
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
/vault/lang/lang.pt.cli.php | 葡萄牙文CLI语言数据。
/vault/lang/lang.pt.fe.php | 葡萄牙文前端语言数据。
/vault/lang/lang.pt.php | 葡萄牙文语言数据。
/vault/lang/lang.ru.cli.php | 俄文CLI语言数据。
/vault/lang/lang.ru.fe.php | 俄文前端语言数据。
/vault/lang/lang.ru.php | 俄文语言数据。
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
/vault/.htaccess | 超文本访问文件（在这种情况，以保护敏感文件属于脚本从被访问由非授权来源）。
/vault/cache.dat | 缓存数据。
/vault/cidramblocklists.dat | 包含的相关信息关于由Macmathan提供的可选的国家阻止列表；由更新功能使用由前端提供。
/vault/cli.php | CLI处理文件。
/vault/components.dat | 包含的相关信息关于CIDRAM的各种组件；它使用通过更新功能从前端。
/vault/config.ini.RenameMe | 配置文件；包含所有配置指令为CIDRAM，告诉它什么做和怎么正确地经营（重命名为激活）。
/vault/config.php | 配置处理文件。
/vault/config.yaml | 配置默认文件；包含CIDRAM的默认配置值。
/vault/frontend.php | 前端处理文件。
/vault/functions.php | 功能处理文件（必不可少）。
/vault/hashes.dat | 包含列表接受哈希表（相关的reCAPTCHA功能；只有生成如果reCAPTCHA功能被启用）。
/vault/ignore.dat | 忽略文件（用于指定其中签名章节CIDRAM应该忽略）。
/vault/ipbypass.dat | 包含列表IP旁路（相关的reCAPTCHA功能；只有生成如果reCAPTCHA功能被启用）。
/vault/ipv4.dat | IPv4签名文件（不想要的云服务和非人终端）。
/vault/ipv4_bogons.dat | IPv4签名文件（bogon/火星CIDR）。
/vault/ipv4_custom.dat.RenameMe | IPv4定制签名文件（重命名为激活）。
/vault/ipv4_isps.dat | IPv4签名文件（危险和垃圾容易ISP）。
/vault/ipv4_other.dat | IPv4签名文件（CIDR从代理，VPN和其他不需要服务）。
/vault/ipv6.dat | IPv6签名文件（不想要的云服务和非人终端）。
/vault/ipv6_bogons.dat | IPv6签名文件（bogon/火星CIDR）。
/vault/ipv6_custom.dat.RenameMe | IPv6定制签名文件（重命名为激活）。
/vault/ipv6_isps.dat | IPv6签名文件（危险和垃圾容易ISP）。
/vault/ipv6_other.dat | IPv6签名文件（CIDR从代理，VPN和其他不需要服务）。
/vault/lang.php | 语言数据。
/vault/modules.dat | 包含的相关信息关于CIDRAM的模块；它使用通过更新功能从前端。
/vault/outgen.php | 输出发生器。
/vault/php5.4.x.php | Polyfill对于PHP 5.4.X （PHP 5.4.X 向下兼容需要它； 较新的版本可以删除它）。
/vault/recaptcha.php | reCAPTCHA模块。
/vault/rules_as6939.php | 定制规则文件为 AS6939。
/vault/rules_softlayer.php | 定制规则文件为 Soft Layer。
/vault/rules_specific.php | 定制规则文件为一些特定的CIDR。
/vault/salt.dat | 盐文件（使用由一些外围功能；只产生当必要）。
/vault/template_custom.html | 模板文件；模板为HTML输出产生通过CIDRAM输出发生器。
/vault/template_default.html | 模板文件；模板为HTML输出产生通过CIDRAM输出发生器。
/vault/themes.dat | 主题文件；它使用通过更新功能从前端。
/.gitattributes | GitHub文件（不需要为正确经营脚本）。
/Changelog.txt | 记录的变化做出至脚本间不同版本（不需要为正确经营脚本）。
/composer.json | Composer/Packagist 信息（不需要为正确经营脚本）。
/CONTRIBUTING.md | 相关信息如何有助于该项目。
/LICENSE.txt | GNU/GPLv2 执照文件（不需要为正确经营脚本）。
/loader.php | 加载文件。这个是文件您应该｢钩子｣（必不可少）!
/README.md | 项目概要信息。
/web.config | 一个ASP.NET配置文件（在这种情况，以保护`/vault`文件夹从被访问由非授权来源在事件的脚本是安装在服务器根据ASP.NET技术）。

---


### 6. <a name="SECTION6"></a>配置选项
下列是一个列表的变量发现在`config.ini`配置文件的CIDRAM，以及一个说明的他们的目的和功能。

#### “general” （类别）
基本CIDRAM配置。

“logfile”
- 人类可读文件用于记录所有被拦截的访问。指定一个文件名，或留空以禁用。

“logfileApache”
- Apache风格文件用于记录所有被拦截的访问。指定一个文件名，或留空以禁用。

“logfileSerialized”
- 连载的文件用于记录所有被拦截的访问。指定一个文件名，或留空以禁用。

*有用的建议：如果您想，可以追加日期/时间信息至附加到您的日志文件的名称通过包括这些中的名称： `{yyyy}` 为今年完整， `{yy}` 为今年缩写， `{mm}` 为今月， `{dd}` 为今日， `{hh}` 为今小时。*

*例子：*
- *`logfile='logfile.{yyyy}-{mm}-{dd}-{hh}.txt'`*
- *`logfileApache='access.{yyyy}-{mm}-{dd}-{hh}.txt'`*
- *`logfileSerialized='serial.{yyyy}-{mm}-{dd}-{hh}.txt'`*

“truncate”
- 截断日志文件当他们达到一定的大小吗？ 值是在B/KB/MB/GB/TB，是日志文件允许的最大大小直到它被截断。 默认值为“0KB”将禁用截断（日志文件可以无限成长）。 注意：适用于单个日志文件！日志文件大小不被算集体的。

“timeOffset”
- 如果您的服务器时间不符合您的本地时间，您可以在这里指定的偏移调整日期/时间信息该产生通过CIDRAM根据您的需要。它一般建议，而不是，调整时区指令的文件`php.ini`，但是有时（例如，当利用有限的共享主机提供商）这并不总是可能做到，所以，此选项在这里是提供。偏移量是在分钟。
- 例子（添加1小时）： `timeOffset=60`

“timeFormat”
- CIDRAM使用的日期符号格式。 标准 = `{Day}, {dd} {Mon} {yyyy} {hh}:{ii}:{ss} {tz}`。

“ipaddr”
- 在哪里可以找到连接请求IP地址？ （可以使用为服务例如Cloudflare和类似）。 标准 = REMOTE_ADDR。 警告： 不要修改此除非您知道什么您做着！

“forbid_on_block”
- 什么头CIDRAM应该应对当申请是拒绝？ False/200 = 200 OK 【标准】； True/403 = 403 Forbidden （被禁止）； 503 = 503 Service unavailable （服务暂停）。

“silent_mode”
- CIDRAM应该默默重定向被拦截的访问而不是显示该“拒绝访问”页吗？指定位置至重定向被拦截的访问，或让它空将其禁用。

“lang”
- 指定标准CIDRAM语言。

“emailaddr”
- 如果您希望，您可以提供电子邮件地址这里要给予用户当他们被阻止，他们使用作为接触点为支持和/或帮助在的情况下他们错误地阻止。警告:您提供的任何电子邮件地址，它肯定会被获得通过垃圾邮件机器人和铲运机，所以，它强烈推荐如果选择提供一个电子邮件地址这里，您保证它是一次性的和/或不是很重要（换一种说法，您可能不希望使用您的主电子邮件地址或您的企业电子邮件地址）。

“disable_cli”
- 关闭CLI模式吗？ CLI模式是按说激活作为标准，但可以有时干扰某些测试工具（例如PHPUnit，为例子）和其他基于CLI应用。如果您没有需要关闭CLI模式，您应该忽略这个指令。 False（假） = 激活CLI模式【标准】； True（真） = 关闭CLI模式。

“disable_frontend”
- 关闭前端访问吗？ 前端访问可以使CIDRAM更易于管理，但也可能是潜在的安全风险。建议管理CIDRAM通过后端只要有可能，但前端访问提供当不可能。保持关闭除非您需要它。 False（假） = 激活前端访问； True（真） = 关闭前端访问【标准】。

“max_login_attempts”
- 最大登录尝试次数（前端）。 标准 = 5。

“FrontEndLog”
- 前端登录尝试的录音文件。 指定一个文件名，或留空以禁用。

“ban_override”
- 覆盖“forbid_on_block”当“infraction_limit”已被超过？ 当覆盖：已阻止的请求返回一个空白页（不使用模板文件）。 200 = 不要覆盖【标准】； 403 = 使用“403 Forbidden”覆盖； 503 = 使用“503 Service unavailable”覆盖。

“log_banned_ips”
- 包括IP禁止从阻止请求在日志文件吗？ True（真） = 是【标准】； False（假） = 不是。

“default_dns”
- 以逗号分隔的DNS服务器列表，用于主机名查找。 标准 = “8.8.8.8,8.8.4.4” (Google DNS)。 警告： 不要修改此除非您知道什么您做着！

“search_engine_verification”
- 尝试验证来自搜索引擎的请求？ 验证搜索引擎确保他们不会因超过违规限制而被禁止 （禁止在您的网站上使用搜索引擎通常会有产生负面影响对您的搜索引擎排名，SEO，等等）。 当被验证，搜索引擎可以被阻止，但不会被禁止。 当不被验证，他们可以由于超过违规限制而被禁止。 另外，搜索引擎验证提供保护针对假搜索引擎请求和针对潜在的恶意实体伪装成搜索引擎（当搜索引擎验证是启用，这些请求将被阻止）。 True（真） = 搜索引擎验证是启用【标准】； False（假） = 搜索引擎验证是禁用。

“protect_frontend”
- 指定是否应将CIDRAM通常提供的保护应用于前端。 True（真） = 是【标准】； False（假） = 不是。

“disable_webfonts”
- 关闭网络字体吗？ True（真） = 关闭； False（假） = 不关闭【标准】。

#### “signatures” （类别）
签名配置。

“ipv4”
- 列表的IPv4签名文件，CIDRAM应该尝试使用，用逗号分隔。您可以在这里添加条目如果您想包括其他文件在CIDRAM。

“ipv6”
- 列表的IPv6签名文件，CIDRAM应该尝试使用，用逗号分隔。您可以在这里添加条目如果您想包括其他文件在CIDRAM。

“block_cloud”
- 阻止CIDR认定为属于虚拟主机或云服务吗？如果您操作一个API服务从您的网站或如果您预计其他网站连接到您的网站，这应该被设置为“false”（假）。如果不，这应该被设置为“true”（真）。

“block_bogons”
- 阻止bogon(“ㄅㄡㄍㄛㄋ”)/martian（“火星”）CIDR吗？如果您希望连接到您的网站从您的本地网络/本地主机/localhost/LAN/等等，这应该被设置为“false”（假）。如果不，这应该被设置为“true”（真）。

“block_generic”
- 阻止CIDR一般建议对于黑名单吗？这包括签名不标记为的一章节任何其他更具体签名类别。

"block_proxies"
- 阻止CIDR认定为属于代理服务吗？如果您需要该用户可以访问您的网站从匿名代理服务，这应该被设置为“false”（假）。除此以外，如果您不需要匿名代理服务，这应该被设置为“true”（真）作为一个方式以提高安全性。

“block_spam”
- 阻止高风险垃圾邮件CIDR吗？除非您遇到问题当这样做，通常，这应该被设置为“true”（真）。

“modules”
- 模块文件要加载的列表以后检查签名IPv4/IPv6，用逗号分隔。

“default_tracktime”
- 多少秒钟来跟踪模块禁止的IP。 标准 = 604800 （1周）。

“infraction_limit”
- 从IP最大允许违规数量之前它被禁止。 标准 = 10。

“track_mode”
- 什么时候应该对违规行为进行计数？ False（假） = 当IP被模块阻止时。 True（真） = 当IP由于任何原因阻止时。

#### “recaptcha” （类别）
如果您想，您可以为用户提供了一种方法绕过“拒绝访问”页面通过完成reCAPTCHA事件。这有助于减轻一些风险假阳性有关，对于当我们不能完全肯定一个请求是否源自机器或人。

由于风险相关的提供的方法为终端用户至绕过“拒绝访问”页面，通常，我建议不要启用此功能除非您觉得这是必要的做。情况由此有必要：如果您的网站有客户/用户该需要具有访问权限您的网站，而如果这一点该不能妥协的，但如果这些客户/用户碰巧被来自敌对网络连接该可能被携带不需要的流量，并阻断这种不需要的流量也不能妥协的，在那些没有双赢的局面，reCAPTCHA的功能可能是有用的作为一种手段允许需要的客户/用户，而避开不需要的流量从同一网络。虽然说，鉴于一个CAPTCHA的预期目的是人类和非人类区分，reCAPTCHA的功能只会协助在这些没有双赢的局面如果我们假设该不需要的流量是非人（例如，垃圾邮件机器人，网站铲运机，黑客工具，交通自动化），而不是作为人的不需要的流量（如人的垃圾邮件机器人，黑客，等等）。

为了获得“site key”和“secret key”（需要为了使用reCAPTCHA），请访问： [https://developers.google.com/recaptcha/](https://developers.google.com/recaptcha/)

“usemode”
- 它定义了如何CIDRAM应该使用reCAPTCHA。
- 0 = reCAPTCHA是完全禁用【标准】。
- 1 = reCAPTCHA是启用为所有签名。
- 2 = reCAPTCHA是启用只为签名章节被特殊标记在签名文件作为reCAPTCHA启用。
- （任何其他值将以同样的方式被视作0）。

“lockip”
- 指定是否哈希应锁定到特定IP地址。 False（假） = Cookie和哈希可以由多个IP地址使用【标准】。 True（真） = Cookie和哈希不能由多个IP地址使用（cookies/哈希是锁定到IP地址）。
- 注意：“lockip”值被忽略当“lockuser”是false（假），由于该机制为记忆的“用户”可以根据这个值的变化。

“lockuser”
- 指定是否一个reCAPTCHA成功完成应锁定到特定用户。 False（假） = 一个reCAPTCHA成功完成将授予访问为所有请求该来自同IP作为由用户使用当完成的reCAPTCHA； Cookie和哈希不被使用；代替，一个IP白名单将被用于。 True（真） = 一个reCAPTCHA成功完成只会授予访问为用户该完成了reCAPTCHA； Cookie和哈希是用于记住用户；一个IP白名单不被使用【标准】。

“sitekey”
- 该值应该对应于“site key”为您的reCAPTCHA，该可以发现在reCAPTCHA的仪表板。

“secret”
- 该值应该对应于“secret key”为您的reCAPTCHA，该可以发现在reCAPTCHA的仪表板。

“expiry”
- 当“lockuser”是true（真）【标准】，为了记住当用户已经成功完成reCAPTCHA，为未来页面请求，CIDRAM生成一个标准的HTTP cookie含哈希对应于内部哈希记录含有相同哈希；未来页面请求将使用这些对应的哈希为了验证该用户已预先完成reCAPTCHA。当“lockuser”是false（假），一个IP白名单被用来确定是否请求应允许从请求的入站IP；条目添加到这个白名单当reCAPTCHA是成功完成。这些cookies，哈希，和白名单条目应在多少小时内有效？ 标准 = 720 （1个月）。

“logfile”
- 记录所有的reCAPTCHA的尝试？要做到这一点，指定一个文件名到使用。如果不，离开这个变量为空白。

*有用的建议：如果您想，可以追加日期/时间信息至附加到您的日志文件的名称通过包括这些中的名称： `{yyyy}` 为今年完整， `{yy}` 为今年缩写， `{mm}` 为今月， `{dd}` 为今日， `{hh}` 为今小时。*

*例子：*
- *`logfile='recaptcha.{yyyy}-{mm}-{dd}-{hh}.txt'`*

#### “template_data” （类别）
指令和变量为模板和主题。

涉及的HTML输出用于生成该“拒绝访问”页面。如果您使用个性化主题为CIDRAM，HTML产量资源是从`template_custom.html`文件，和否则，HTML产量资源是从`template.html`文件。变量书面在这个配置文件章节是喂在HTML产量通过更换任何变量名包围在大括号发现在HTML产量使用相应变量数据。为例子，哪里`foo="bar"`，任何发生的`<p>{foo}</p>`发现在HTML产量将成为`<p>bar</p>`。

“theme”
- 用于CIDRAM的默认主题。

“css_url”
- 模板文件为个性化主题使用外部CSS属性，而模板文件为t标准主题使用内部CSS属性。以指令CIDRAM使用模板文件为个性化主题，指定公共HTTP地址的您的个性化主题的CSS文件使用`css_url`变量。如果您离开这个变量空白，CIDRAM将使用模板文件为默认主题。

---


### 7. <a name="SECTION7"></a>签名格式

#### 7.0 基本概念

CIDRAM签名格式和结构描述可以被发现记录在纯文本在自定义签名文件。请参阅该文档了解更多有关CIDRAM签名格式和结构。

所有IPv4签名遵循格式： `xxx.xxx.xxx.xxx/yy [Function] [Param]`。
- `xxx.xxx.xxx.xxx` 代表CIDR块开始（初始IP地址八比特组）。
- `yy` 代表CIDR块大小 [1-32]。
- `[Function]` 指令脚本做什么用的署名（应该怎么签名考虑）。
- `[Param]` 代表任何其他信息其可以由需要 `[Function]`。

所有IPv6签名遵循格式： `xxxx:xxxx:xxxx:xxxx::xxxx/yy [Function] [Param]`。
- `xxxx:xxxx:xxxx:xxxx::xxxx` 代表CIDR块的开始（初始IP地址八比特组）。完整符号和缩写符号是可以接受的（和每都必须遵循相应和相关IPv6符号标准，但有一个例外：IPv6地址不能开头是与缩写当用来在签名该脚本，由于以何种方式CIDR是构建由脚本；例如，当用来在签名，`::1/128`应该这样写`0::1/128`，和`::0/128`应该这样写`0::/128`）。
- `yy` 代表CIDR块大小 [1-128]。
- `[Function]` 指令脚本做什么用的署名（应该怎么签名考虑）。
- `[Param]` 代表任何其他信息其可以由需要 `[Function]`。

CIDRAM签名文件应该使用Unix的换行符（`%0A`，或`\n`）！其他换行符类型/风格（例如，Windows `%0D%0A`或`\r\n`换行符，Mac `%0D`或`\r`换行符，等等） 可以被用于，但不是优选。非Unix的换行符将正常化至Unix的换行符由脚本。

精准无误的CIDR符号是必须的，不会是承认签名。另外，所有的CIDR签名必须用一个IP地址该始于一个数在该CIDR块分割适合于它的块大小（例如，如果您想阻止所有的IP从`10.128.0.0`到`11.127.255.255`，`10.128.0.0/8`不会是承认由脚本，但`10.128.0.0/9`和`11.0.0.0/9`结合使用，将是承认由脚本）。

任何数据在签名文件不承认为一个签名也不为签名相关的语法由脚本将被忽略，因此，这意味着您可以放心地把任何未签名数据和任何您想要的在签名文件没有打破他们和没有打破该脚本。注释是可以接受的在签名文件，和没有特殊的格式需要为他们。Shell风格的哈希注释是首选，但并非强制；从功能的角度，无论您是否选择使用Shell风格的哈希注释，有没有区别为脚本，但使用Shell风格的哈希帮助IDE和纯文本编辑器正确地突出的各个签名章节文件（所以，Shell风格的哈希可以帮助作为视觉辅助在编辑）。

`[Function]` 可能的值如下：
- Run
- Whitelist
- Greylist
- Deny

如果“Run”是用来，当该签名被触发，该脚本将尝试执行（使用一个`require_once`声明）一个外部PHP脚本，由指定的`[Param]`值（工作目录应该是“/vault/”脚本目录）。

例子：`127.0.0.0/8 Run example.php`

这可能是有用如果您想执行一些特定的PHP代码对一些特定的IP和/或CIDR。

如果“Whitelist”是用来，当该签名被触发，该脚本将重置所有检测（如果有过任何检测）和打破该测试功能。`[Param]`被忽略。此功能将白名单一个IP地址或一个CIDR。

例子：`127.0.0.1/32 Whitelist`

如果“Greylist”是用来，当该签名被触发，该脚本将重置所有检测（如果有过任何检测）和跳到下一个签名文件以继续处理。`[Param]`被忽略。

例子：`127.0.0.1/32 Greylist`

如果“Deny”是用来，当该签名被触发，假设没有白名单签名已触发为IP地址和/或CIDR，访问至保护的页面被拒绝。您要使用“Deny”为实际拒绝一个IP地址和/或CIDR范围。当任何签名利用的“Deny”被触发，该“拒绝访问”脚本页面将生成和请求到保护的页面会被杀死。

“Deny”的`[Param]`值会被解析为“拒绝访问”页面，提供给客户机/用户作为引原因他们访问到请求的页面被拒绝。它可以是一个短期和简单的句子，为解释原因（什么应该足够了；一个简单的消息像“我不想让您在我的网站”会好起来的），或一小撮之一的短关键字供应的通过脚本。如果使用，它们将被替换由脚本使用预先准备的解释为什么客户机/用户已被阻止。

预先准备的解释具有多语言支持和可以翻译通过脚本根据您的语言指定的通过`lang`脚本配置指令。另外，您可以指令脚本忽略“Deny”签名根据他们的价`[Param]`值（如果他们使用这些短关键字）通过脚本配置指令（每短关键字有一个相应的指令到处理相应的签名或忽略它）。`[Param]`值不使用这些短关键字，然而，没有多语言支持和因此不会被翻译通过脚本，并且还，不能直接控制由脚本配置。

可用的短关键字是：
- Bogon
- Cloud
- Generic
- Proxy
- Spam

#### 7.1 标签

如果要分割您的自定义签名成各个章节，您可以识别这些各个章节为脚本通过加入一个章节标签立即跟着每签名章节，伴随着签名章节名字（看下面的例子）。

```
# 部分一。
1.2.3.4/32 Deny Bogon
2.3.4.5/32 Deny Cloud
4.5.6.7/32 Deny Generic
5.6.7.8/32 Deny Spam
6.7.8.9/32 Deny Proxy
Tag: 部分一
```

为了打破章节标签和以确保标签不是确定不正确的对于签名章节从较早的在签名文件，确保有至少有两个连续的换行符之间您的标签和您的较早的签名章节。任何未标记签名将默认为“IPv4”或“IPv6”（取决于签名类型被触发的）。

```
1.2.3.4/32 Deny Bogon
2.3.4.5/32 Deny Cloud

4.5.6.7/32 Deny Generic
5.6.7.8/32 Deny Spam
Tag: 部分一
```

在上面的例子`1.2.3.4/32`和`2.3.4.5/32`将标为“IPv4”，而`4.5.6.7/32`和`5.6.7.8/32`将标为“部分一”.

如果您想签名一段时间后过期，以类似的方式来章节标签，您可以使用一个“过期标签”来指定在签名应该不再有效。过期标签使用格式“年年年年.月月.日日”（看下面的例子）。

```
# 部分一。
1.2.3.4/32 Deny Generic
2.3.4.5/32 Deny Generic
Expires: 2016.12.31
```

章节标签和过期标签可以结合使用，又都是可选（看下面的例子）。

```
# 示例部分。
1.2.3.4/32 Deny Generic
Tag: 示例部分
Expires: 2016.12.31
```

#### 7.2 YAML

##### 7.2.0 YAML基本概念

简化形式的YAML标记可以使用在签名文件用于目的定义行为和配置设置具体到个人签名章节。这可能是有用的如果您希望您的配置指令值到变化之间的个人签名和签名章节（例如；如果您想提供一个电子邮件地址为支持票为任何用户拦截的通过一个特定的签名，但不希望提供一个电子邮件地址为支持票为用户拦截的通过任何其他签名；如果您想一些具体的签名到触发页面重定向；如果您想标记一个签名为使用的reCAPTCHA；如果您想日志拦截的访问到单独的文件按照个人签名和/或签名章节）。

使用YAML标记在签名文件是完全可选（即，如果您想用这个，您可以用这个，但您没有需要用这个），和能够利用最的（但不所有的）配置指令。

注意：YAML标记实施在CIDRAM是很简单也很有限；它的目的是满足特定要求的CIDRAM在方式具有熟悉的YAML标记，但不跟随也不符合规定的官方规格（因此，将不会是相同的其他实现别处，和可能不适合其他项目别处）。

在CIDRAM，YAML标记段被识别到脚本通过使用三个连字符（“---”），和终止靠他们的签名章节通过双换行符。一个典型的YAML标记段在一个签名章节被组成的三个连字符在一行立马之后的CIDR列表和任何标签，接着是二维表为键值对（第一维，配置指令类别；第二维，配置指令）为哪些配置指令应修改（和哪些值）每当一个签名内那签名章节被触发（看下面的例子）。

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

当“usemode”是“0”或“1”，签名章节不需要是“特别标记”签名章节为使用的reCAPTCHA（因为他们已会或不会使用reCAPTCHA，根据此设置）。

当“usemode”是“2”，为“特别标记”签名章节为使用的reCAPTCHA，一个条目是包括在YAML段为了那个签名章节（看下面的例子）。

```
# 本节将使用reCAPTCHA。
1.2.3.4/32 Deny Generic
2.3.4.5/32 Deny Generic
Tag: reCAPTCHA-Enabled
---
recaptcha:
 enabled: true
```

注意：一个reCAPTCHA将仅提供给用户如果reCAPTCHA是启用（当“usemode”是“1”，或“usemode”是“2”和“enabled”是“true”），和如果只有一个签名已触发（不多不少；如果多个签名被触发，一个reCAPTCHA将不提供）。

#### 7.3 辅

此外，如果您想CIDRAM完全忽略一些具体的章节内的任何签名文件，您可以使用`ignore.dat`文件为指定忽略哪些章节。在新行，写`Ignore`，空间，然后该名称的章节您希望CIDRAM忽略（看下面的例子）。

```
Ignore 部分一
```

参考定制签名文件了解更多信息。

---


### 8. <a name="SECTION8"></a>常见问题（FAQ）

#### 什么是“签名”？

在CIDRAM的上下文中，“签名”是一些数据，它表示/识别我们正在寻找的东西，通常是IP地址或CIDR，并包含一些说明，告诉CIDRAM最好的回应方法当它遇到我们正在寻找的。 CIDRAM的典型签名如下所示：

`1.2.3.4/32 Deny Generic`

经常（但不总是），签名是捆绑在一起，形成“签名章节”，经常伴随评论，标记，和/或相关元数据。 这可以用于为签名提供附加上下文和/或附加说明。

#### <a name="WHAT_IS_A_CIDR"></a>什么是“CIDR”？

“CIDR” 是 “Classless Inter-Domain Routing” 的首字母缩写 （“无类别域间路由”） *【[1](https://zh.wikipedia.org/wiki/%E6%97%A0%E7%B1%BB%E5%88%AB%E5%9F%9F%E9%97%B4%E8%B7%AF%E7%94%B1), [2](http://whatismyipaddress.com/cidr)】*。 这个首字母缩写用于这个包的名称， “CIDRAM”， 是 “Classless Inter-Domain Routing Access Manager” 的首字母缩写 （“无类别域间路由访问管理器”）。

然而，在CIDRAM的上下文中（如，在本文档中，在CIDRAM的讨论中，或在CIDRAM语言数据中），当“CIDR”（单数）或“CIDRs”（复数）被提及时（因此当我们用这些词作为名词在自己的权利，而不作为首字母缩写），我们的意图是一个子网，用CIDR表示法表示。 使用CIDR/CIDRs而不是子网的原因是澄清它是用CIDR表示法表示的子网是我们的意思 （因为子网可以用几种不同的方式表达）。 因此，CIDRAM可以被认为是“子网访问管理器”。

这个双重含义可能看起来很歧义，但这个解释并提供上下文应该有助于解决这个歧义。

#### 什么是“假阳性”？

术语“假阳性”（*或者：“假阳性错误”；“虚惊”*；英语：*false positive*; *false positive error*; *false alarm*），很简单地描述，和在一个广义上下文，被用来当测试一个因子，作为参考的测试结果，当结果是阳性（即：因子被确定为“阳性”，或“真”），但预计将为（或者应该是）阴性（即：因子，在现实中，是“阴性”，或“假”）。一个“假阳性”可被认为是同样的“哭狼” (其中，因子被测试是是否有狼靠近牛群，因子是“假”由于该有没有狼靠近牛群，和因子是报告为“阳性”由牧羊人通过叫喊“狼，狼”），或类似在医学检测情况，当患者被诊断有一些疾病，当在现实中，他们没有疾病。

一些相关术语是“真阳性”，“真阴性”和“假阴性”。一个“真阳性”指的是当测试结果和真实因子状态都是“真”（或“阳性”），和一个“真阴性”指的是当测试结果和真实因子状态都是“假”（或“阴性”）；一个“真阳性”或“真阴性”被认为是一个“正确的推理”。对立面“假阳性”是一个“假阴性”；一个“假阴性”指的是当测试结果是“阴性”（即：因子被确定为“阴性”，或“假”），但预计将为（或者应该是）阳性（即：因子，在现实中，是“阳性”，或“真”）。

在CIDRAM的上下文，这些术语指的是CIDRAM的签名和什么/谁他们阻止。当CIDRAM阻止一个IP地址由于恶劣的，过时的，或不正确的签名，但不应该这样做，或当它这样做为错误的原因，我们将此事件作为一个“假阳性”。当CIDRAM未能阻止IP地址该应该已被阻止，由于不可预见的威胁，缺少签名或不足签名，我们将此事件作为一个“检测错过”（同样的“假阴性”）。

这可以通过下表来概括：

&nbsp; | CIDRAM不应该阻止IP地址 | CIDRAM应该阻止IP地址
---|---|---
CIDRAM不会阻止IP地址 | 真阴性（正确的推理） | 检测错过（同样的“假阴性”）
CIDRAM会阻止IP地址 | __假阳性__ | 真阳性（正确的推理）

#### CIDRAM可以阻止整个国家吗？

它可以。实现这个最简单的方法是安装一些由Macmathan提供的可选的国家阻止列表。这可以通过直接从前端更新页面的几个简单的点击完成，或，如果您希望前端保持停用状态，通过直接从下载页面下载它们。通过直接从 **[可选的国家阻止列表下载页面](https://macmathan.info/blocklists)** 下载它们，上传他们到vault，在相关配置指令中引用它们的名称。

#### 什么是签名更新频率？

更新频率根据相关的签名文件而有所不同。所有的CIDRAM签名文件的维护者通常尽量保持他们的签名为最新，但是因为我们所有人都有各种其他承诺，和因为我们的生活超越了项目，和因为我们不得到经济补偿/付款为我们的项目的努力，无法保证精确的更新时间表。通常，签名被更新每当有足够的时间，和维护者尝试根据必要性和根据范围之间的变化频率确定优先级。帮助总是感谢，如果你愿意提供任何。

#### 我在使用CIDRAM时遇到问题和我不知道该怎么办！ 请帮忙！

- 您使用软件的最新版本吗？您使用签名文件的最新版本吗？如果这两个问题的答案是不，尝试首先更新一切，然后检查问题是否仍然存在。如果它仍然存在，继续阅读。
- 您检查过所有的文档吗？如果没有做，请这样做。如果文档不能解决问题，继续阅读。
- 您检查过 **[问题页面](https://github.com/CIDRAM/CIDRAM/issues)** 吗？ 检查是否已经提到了问题。如果已经提到了，请检查是否提供了任何建议，想法或解决方案。按照需要尝试解决问题。
- 您检查过 **[由Spambot Security提供的CIDRAM支持论坛](http://www.spambotsecurity.com/forum/viewforum.php?f=61)** 吗？ 检查是否已经提到了问题。如果已经提到了，请检查是否提供了任何建议，想法或解决方案。按照需要尝试解决问题。
- 如果问题仍然存在，请让我们知道；在问题页面或支持论坛上开始新的讨论。

#### 因为CIDRAM，我被阻止从我想访问的网站！ 请帮忙！

CIDRAM使网站所有者能够阻止不良流量，但网站所有者有责任为自己决定如何使用CIDRAM。在关于默认签名文件假阳性的情况下，可以进行校正。但关于从特定网站解除阻止，您需要与相关网站的所有者进行沟通。当进行校正时，至少，他们需要更新他们的签名文件和/或安装。在其他情况下（例如，当他们修改了他们的安装，当他们创建自己的自定义签名，等等），解决您的问题的责任完全是他们的，并完全不在我们的控制之内。

#### 我想使用CIDRAM与早于5.4.0的PHP版本； 您能帮我吗？

不能。PHP 5.4.0于2014年达到官方EoL（“生命终止”）。延长的安全支持在2015年终止。这时候目前，它是2017年，和PHP 7.1.0已经可用。目前，有支持使用CIDRAM与PHP 5.4.0和所有可用的较新的PHP版本，但不有支持使用CIDRAM与任何以前的PHP版本。

#### 我可以使用单个CIDRAM安装来保护多个域吗？

可以。CIDRAM安装未绑定到特定域，因此可以用来保护多个域。通常，当CIDRAM安装保护只一个域，我们称之为“单域安装”，和当CIDRAM安装保护多个域和/或子域，我们称之为“多域安装”。如果您进行多域安装并需要使用不同的签名文件为不同的域，或需要不同配置CIDRAM为不同的域，这可以做到。加载配置文件后（`config.ini`），CIDRAM将寻找“配置覆盖文件”特定于所请求的域（`xn--cjs74vvlieukn40a.tld.config.ini`），并如果发现，由配置覆盖文件定义的任何配置值将用于执行实例而不是由配置文件定义的配置值。配置覆盖文件与配置文件相同，并通过您的决定，可能包含CIDRAM可用的所有配置指令，或任何必需的部分当需要。配置覆盖文件根据它们旨在的域来命名（所以，例如，如果您需要一个配置覆盖文件为域，`http://www.some-domain.tld/`，它的配置覆盖文件应该被命名`some-domain.tld.config.ini`，和它应该放置在`vault`与配置文件，`config.ini`）。域名是从标题`HTTP_HOST`派生的；“www”被忽略。

#### 我不想浪费时间安装这个和确保它在我的网站上功能正常；我可以雇用您这样做吗？

也许。这是根据具体情况考虑的。告诉我们您需要什么，您提供什么，和我们会告诉您是否可以帮忙。

#### 我可以聘请您或这个项目的任何开发者私人工作吗？

*参考上面。*

#### 我需要专家修改，的定制，等等；您能帮我吗？

*参考上面。*

#### 我是开发人员，网站设计师，或程序员。我可以接受还是提供与这个项目有关的工作？

您可以。我们的许可证并不禁止这一点。

#### 我想为这个项目做出贡献；我可以这样做吗？

您可以。对项目的贡献是欢迎。有关详细信息，请参阅“CONTRIBUTING.md”。

#### “ipaddr”的推荐值。

值 | 运用
---|---
`HTTP_INCAP_CLIENT_IP` | Incapsula反向代理
`HTTP_CF_CONNECTING_IP` | Cloudflare反向代理
`CF-Connecting-IP` | Cloudflare反向代理（替代；如果另一个不工作）
`X-Forwarded-For` | [Squid反向代理](http://www.squid-cache.org/Doc/config/forwarded_for/)
*由服务器配置定义。* | [Nginx反向代理](https://www.nginx.com/resources/admin-guide/reverse-proxy/)
`REMOTE_ADDR` | 没有反向代理（默认值）。

---


最后更新：2017年6月4日。
