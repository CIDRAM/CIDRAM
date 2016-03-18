## CIDRAM 中文（简体）文档。

### 内容
- 1. [前言](#SECTION1)
- 2. [如何安装](#SECTION2)
- 3. [如何使用](#SECTION3)
- 4. [文件在包](#SECTION4)
- 5. [配置选项](#SECTION5)
- 6. [签名格式](#SECTION6)

---


###1. <a name="SECTION1"></a>前言

CIDRAM （无类别域间路由访问管理器）是一个PHP脚本，旨在保护网站途经阻止请求该从始发IP地址视为不良的流量来源，包括（但不限于）流量该从非人类的访问端点，云服务，垃圾邮件发送者，网站铲运机，等等。它通过计算CIDR的提供的IP地址从入站请求和试图匹配这些CIDR反对它的签名文件（这些签名文件包含CIDR的IP地址视为不良的流量来源）；如果找到匹配，请求被阻止。

CIDRAM COPYRIGHT 2013 and beyond GNU/GPLv2 by Caleb M (Maikuolan)。

本脚本是基于GNU通用许可V2.0版许可协议发布的，您可以在许可协议的允许范围内自行修改和发布，但请遵守GNU通用许可协议。使用脚本的过程中，作者不提供任何担保和任何隐含担保。更多的细节请参见GNU通用公共许可证，下的`LICENSE.txt`文件也可从访问：
- <http://www.gnu.org/licenses/>。
- <http://opensource.org/licenses/>。

谢谢[ClamAV](http://www.clamav.net/)为本脚本提供文件签名库访问许可。没有它，这个脚本很可能不会存在，或者其价值有限。

谢谢Sourceforge和GitHub开通了，[Spambot Security](http://www.spambotsecurity.com/forum/viewforum.php?f=55)的CIDRAM的讨论论坛，谢谢为CIDRAM提供签名文件的：[SecuriteInfo.com](http://www.securiteinfo.com/)，[PhishTank](http://www.phishtank.com/)，[NLNetLabs](http://nlnetlabs.nl/)，还有更多的我忘了提及的人（抱歉，语文水平有限，这句话实在不知道怎么翻译才通顺）。

现在CIDRAM的代码文件和关联包可以从以下地址免费下载[GitHub](https://github.com/Maikuolan/CIDRAM/)。

---


###2. <a name="SECTION2"></a>如何安装

我可能在将来会创建一个安装程序来简化安装过程，但在之前，按照下面的这些安装说明能在大多数的系统和CMS上成功安装：

1） 在阅读到这里之前，我假设您已经下载脚本的一个副本，已解压缩其内容并保存在您的机器的某个地方。现在，您要决定将脚本放在您服务器上的哪些文件夹中，例如`/public_html/cidram/`或其他任何您觉得满意和安全的地方。*上传完成后，继续阅读。。*

2） 自定义（强烈推荐高级用户，但不推荐业余用户或者新手使用这个方法），打开`config.ini`（位于内`vault`） - 这个文件包含所有CIDRAM的可用配置选项。以上的每一个配置选项应有一个简介来说明它是做什么的和它的具有的功能。按照您认为合适的参数来调整这些选项，然后保存文件，关闭。

3） 上传（CIDRAM和它的文件）到您选定的文件夹（不需要包括`*.txt`/`*.md`文件，但大多数情况下，您应上传所有的文件）。

4） 修改的`vault`文件夹权限为“755”。注意，主文件夹也应该是该权限，如果遇上其他权限问题，请修改对应文件夹和文件的权限。

5） 接下来，您需要为您的系统或CMS设定启动CIDRAM的钩子。有几种不同的方式为您的系统或CMS设定钩子，最简单的是在您的系统或CMS的核心文件的开头中使用`require`或`include`命令直接包含脚本（这个方法通常会导致在有人访问时每次都加载）。平时，这些都是存储的在文件夹中，例如`/includes`，`/assets`或`/functions`等文件夹，和将经常被命名的某物例如`init.php`，`common_functions.php`，`functions.php`。这是根据您自己的情况决定的，并不需要完全遵守；如果您遇到困难，访问CIDRAM支持论坛和发送问题；可能其他用户或者我自己也有这个问题并且解决了（您需要让我们您在使用哪些CMS）。为了使用`require`或`include`，插入下面的代码行到最开始的该核心文件，更换里面的数据引号以确切的地址的`loader.php`文件（本地地址，不是HTTP地址；它会类似于前面提到的vault地址）。（注意，本人不是PHP程序员，关于这一段仅仅是直译，如有错误，请在对应项目上提交问题更正）。

`<?php require '/user_name/public_html/cidram/loader.php'; ?>`

保存文件，关闭，重新上传。

-- 或替换 --

如果您使用Apache网络服务器并且您可以访问`php.ini`，您可以使用该`auto_prepend_file`指令为任何PHP请求创建附上的CIDRAM。就像是：

`auto_prepend_file = "/user_name/public_html/cidram/loader.php"`

或在该`.htaccess`文件：

`php_value auto_prepend_file "/user_name/public_html/cidram/loader.php"`

6) 这就是一切！ :-)

---


###3. <a name="SECTION3"></a>如何使用

CIDRAM 应自动阻止不良的请求至您的网站，没有任何需求除了初始安装。

更新是手工完成，和您可以定制您的配置和您可以定制什么CIDR被阻止通过修改您的配置文件和/或您的签名文件.

如果您遇到任何假阳性，请联系我让我知道这件事。

---


###4. <a name="SECTION4"></a>文件在包
（本段文件采用的自动翻译，因为都是一些文件描述，参考意义不是很大，如有疑问，请参考英文原版）

下面是一个列表的所有的文件该应该是存在在您的存档在下载时间，任何文件该可能创建因之的您的使用这个脚本，包括一个简短说明的他们的目的。

文件 | 说明
----|----
/.gitattributes | GitHub文件（不需要为正确经营脚本）。
/Changelog.txt | 记录的变化做出至脚本间不同版本（不需要为正确经营脚本）。
/composer.json | Composer/Packagist 信息（不需要为正确经营脚本）。
/LICENSE.txt | GNU/GPLv2 执照文件。
/loader.php | 加载文件。这个是文件您应该｢钩子｣（必不可少）!
/README.md | 项目概要信息。
/web.config | 一个ASP.NET配置文件（在这种情况，以保护`/vault`文件夹从被访问由非授权来源在事件的脚本是安装在服务器根据ASP.NET技术）。
/_docs/ | 笔记文件夹（包含若干文件）。
/_docs/readme.de.md | 德文自述文件。
/_docs/readme.en.md | 英文自述文件。
/_docs/readme.es.md | 西班牙文自述文件。
/_docs/readme.fr.md | 法文自述文件。
/_docs/readme.id.md | 印度尼西亚文自述文件。
/_docs/readme.it.md | 意大利文自述文件。
/_docs/readme.nl.md | 荷兰文自述文件。
/_docs/readme.pt.md | 葡萄牙文自述文件。
/_docs/readme.zh-TW.md | 中文（传统）自述文件。
/_docs/readme.zh.md | 中文（简体）自述文件。
/vault/ | 安全／保险库｢Vault｣文件夹（包含若干文件）。
/vault/.htaccess | 超文本访问文件（在这种情况，以保护敏感文件属于脚本从被访问由非授权来源）。
/vault/cache.dat | 缓存数据。
/vault/cli.php | CLI处理文件。
/vault/config.ini | 配置文件；包含所有配置指令为CIDRAM，告诉它什么做和怎么正确地经营（必不可少）！
/vault/config.php | 配置处理文件。
/vault/functions.php | 功能处理文件（必不可少）。
/vault/ipv4.dat | IPv4签名文件。
/vault/ipv4_custom.dat | IPv4定制签名文件。
/vault/ipv6.dat | IPv6签名文件。
/vault/ipv6_custom.dat | IPv6定制签名文件。
/vault/lang.php | 语言数据。
/vault/lang/ | 包含CIDRAM语言数据。
/vault/lang/.htaccess | 超文本访问文件（在这种情况，以保护敏感文件属于脚本从被访问由非授权来源）。
/vault/lang/lang.en.php | 英文语言数据。
/vault/lang/lang.es.php | 西班牙文语言数据。
/vault/lang/lang.fr.php | 法文语言数据。
/vault/lang/lang.id.php | 印度尼西亚文语言数据。
/vault/lang/lang.it.php | 意大利文语言数据。
/vault/lang/lang.nl.php | 荷兰文语言数据。
/vault/lang/lang.pt.php | 葡萄牙文语言数据。
/vault/lang/lang.zh-TW.php | 中文（传统）语言数据。
/vault/lang/lang.zh.php | 中文（简体）语言数据。
/vault/outgen.php | 输出发生器。
/vault/template.html | 模板文件；模板为HTML输出产生通过CIDRAM输出发生器。
/vault/template_custom.html | 模板文件；模板为HTML输出产生通过CIDRAM输出发生器。
/vault/rules_as6939.php | 定制规则文件为 AS6939。
/vault/rules_softlayer.php | 定制规则文件为 Soft Layer。

---


###5. <a name="SECTION5"></a>配置选项
下列是一个列表的变量发现在`config.ini`配置文件的CIDRAM，以及一个说明的他们的目的和功能。

####"general" （类别）
基本CIDRAM配置。

"logfile"
- 文件为记录所有被拦截的访问。指定一个文件名，或留空以禁用。

“ipaddr”
- 在哪里可以找到连接请求IP地址？（可以使用为服务例如Cloudflare和类似）标准是`REMOTE_ADDR`。警告！不要修改此除非您知道什么您做着！

"forbid_on_block"
- CIDRAM 应该响应以 “403 Forbidden” 到被阻止的请求，或 “200 OK”？ False = 200 [Default]; True = 403。

“lang”
- 指定标准CIDRAM语言。

"emailaddr"
- 如果您希望，您可以提供电子邮件地址这里要给予用户当他们被阻止，他们使用作为接触点为支持和/或帮助在的情况下他们错误地阻止。警告:您提供的任何电子邮件地址，它肯定会被获得通过垃圾邮件机器人和铲运机，所以，它强烈推荐如果选择提供一个电子邮件地址这里，您保证它是一次性的和/或不是很重要（换一种说法，您可能不希望使用您的主电子邮件地址或您的企业电子邮件地址）。

####"signatures" （类别）
签名配置。

"block_cloud"
- 阻止CIDR认定为属于虚拟主机或云服务吗？如果您操作一个API服务从您的网站或如果您预计其他网站连接到您的网站，这应该被设置为“false”（假）。如果不，这应该被设置为“true”（真）。

"block_bogons"
- 阻止bogon(“ㄅㄡㄍㄛㄋ”)/martian（“火星”）CIDR吗？如果您希望连接到您的网站从您的本地网络/本地主机/localhost/LAN/等等，这应该被设置为“false”（假）。如果不，这应该被设置为“true”（真）。

"block_generic"
- 阻止CIDR一般建议对于黑名单吗？这包括签名不标记为的一部分任何其他更具体签名类别。

"block_spam"
- 阻止高风险垃圾邮件CIDR吗？除非您遇到问题当这样做，通常，这应该被设置为“true”（真）。

---


###6. <a name="SECTION6"></a>签名格式

CIDRAM签名格式和结构描述可以被发现记录在纯文本在自定义签名文件。请参阅该文档了解更多有关CIDRAM签名格式和结构。

---


最后更新：2016年3月18日。
