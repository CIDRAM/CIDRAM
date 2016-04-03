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

2） 自定义（强烈推荐高级用户，但不推荐业余用户或者新手使用这个方法），重命名`config.ini.RenameMe`到`config.ini`（位于内`vault`），并打开它（这个文件包含所有CIDRAM的可用配置选项；以上的每一个配置选项应有一个简介来说明它是做什么的和它的具有的功能）。按照您认为合适的参数来调整这些选项，然后保存文件，关闭。

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
/LICENSE.txt | GNU/GPLv2 执照文件（不需要为正确经营脚本）。
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
/vault/config.ini.RenameMe | 配置文件；包含所有配置指令为CIDRAM，告诉它什么做和怎么正确地经营（重命名为激活）。
/vault/config.php | 配置处理文件。
/vault/functions.php | 功能处理文件（必不可少）。
/vault/ipv4.dat | IPv4签名文件。
/vault/ipv4_custom.dat.RenameMe | IPv4定制签名文件（重命名为激活）。
/vault/ipv6.dat | IPv6签名文件。
/vault/ipv6_custom.dat.RenameMe | IPv6定制签名文件（重命名为激活）。
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

####“general” （类别）
基本CIDRAM配置。

“logfile”
- 人类可读文件用于记录所有被拦截的访问。指定一个文件名，或留空以禁用。

“logfileApache”
- Apache风格文件用于记录所有被拦截的访问。指定一个文件名，或留空以禁用。

“logfileSerialized”
- 连载的文件用于记录所有被拦截的访问。指定一个文件名，或留空以禁用。

“ipaddr”
- 在哪里可以找到连接请求IP地址？（可以使用为服务例如Cloudflare和类似）标准是`REMOTE_ADDR`。警告！不要修改此除非您知道什么您做着！

“forbid_on_block”
- CIDRAM 应该响应以 “403 Forbidden” 到被阻止的请求，或 “200 OK”？ False/200 = 200 [默认]; True = 403; 503 = 服务不可用（503）。

“silent_mode”
- CIDRAM 应该默默重定向被拦截的访问而不是显示该“拒绝访问”页吗？指定位置至重定向被拦截的访问，或让它空将其禁用。

“lang”
- 指定标准CIDRAM语言。

“emailaddr”
- 如果您希望，您可以提供电子邮件地址这里要给予用户当他们被阻止，他们使用作为接触点为支持和/或帮助在的情况下他们错误地阻止。警告:您提供的任何电子邮件地址，它肯定会被获得通过垃圾邮件机器人和铲运机，所以，它强烈推荐如果选择提供一个电子邮件地址这里，您保证它是一次性的和/或不是很重要（换一种说法，您可能不希望使用您的主电子邮件地址或您的企业电子邮件地址）。

“disable_cli”
- 关闭CLI模式吗？CLI模式是按说激活作为标准，但可以有时干扰某些测试工具（例如PHPUnit，为例子）和其他基于CLI应用。如果您没有需要关闭CLI模式，您应该忽略这个指令。 False = 激活CLI模式【标准】； True = 关闭CLI模式。

####“signatures” （类别）
签名配置。

“block_cloud”
- 阻止CIDR认定为属于虚拟主机或云服务吗？如果您操作一个API服务从您的网站或如果您预计其他网站连接到您的网站，这应该被设置为“false”（假）。如果不，这应该被设置为“true”（真）。

“block_bogons”
- 阻止bogon(“ㄅㄡㄍㄛㄋ”)/martian（“火星”）CIDR吗？如果您希望连接到您的网站从您的本地网络/本地主机/localhost/LAN/等等，这应该被设置为“false”（假）。如果不，这应该被设置为“true”（真）。

“block_generic”
- 阻止CIDR一般建议对于黑名单吗？这包括签名不标记为的一部分任何其他更具体签名类别。

“block_spam”
- 阻止高风险垃圾邮件CIDR吗？除非您遇到问题当这样做，通常，这应该被设置为“true”（真）。

####“template_data” （类别）
指令和变量为模板和主题。

涉及的HTML输出用于生成该“拒绝访问”页。如果您使用个性化主题为CIDRAM，HTML产量资源是从`template_custom.html`文件，和否则，HTML产量资源是从`template.html`文件。变量书面在这个配置文件部分是喂在HTML产量通过更换任何变量名包围在大括号发现在HTML产量使用相应变量数据。为例子，哪里`foo="bar"`，任何发生的`<p>{foo}</p>`发现在HTML产量将成为`<p>bar</p>`。

“css_url”
- 模板文件为个性化主题使用外部CSS属性，而模板文件为t标准主题使用内部CSS属性。以指示CIDRAM使用模板文件为个性化主题，指定公共HTTP地址的您的个性化主题的CSS文件使用`css_url`变量。如果您离开这个变量空白，CIDRAM将使用模板文件为默认主题。

---


###6. <a name="SECTION6"></a>签名格式

CIDRAM签名格式和结构描述可以被发现记录在纯文本在自定义签名文件。请参阅该文档了解更多有关CIDRAM签名格式和结构。

All IPv4 signatures follow the format: `xxx.xxx.xxx.xxx/yy %Function% %Param%`.
- `xxx.xxx.xxx.xxx` represents the beginning of the CIDR block (the octets of the initial IP address in the block).
- `yy` represents the CIDR block size [1-32].
- `%Function%` instructs the script what to do with the signature (how the signature should be regarded).
- `%Param%` represents whatever additional information may be required by `%Function%`.

All IPv6 signatures follow the format: `xxxx:xxxx:xxxx:xxxx::xxxx/yy %Function% %Param%`.
- `xxxx:xxxx:xxxx:xxxx::xxxx` represents the beginning of the CIDR block (the octets of the initial IP address in the block). Complete notation and abbreviated notation are both acceptable (and each MUST follow the appropriate and relevant standards of IPv6 notation, but with one exception: an IPv6 address can never begin with an abbreviation when used in a signature for this script, due to the way in which CIDRs are reconstructed by the script; For example, `::1/128` should be expressed, when used in a signature, as `0::1/128`, and `::0/128` expressed as `0::/128`).
- `yy` represents the CIDR block size [1-128].
- `%Function%` instructs the script what to do with the signature (how the signature should be regarded).
- `%Param%` represents whatever additional information may be required by `%Function%`.

The signature files for CIDRAM SHOULD use Unix-style linebreaks (`%0A`, or `\n`)! Other types/styles of linebreaks (eg, Windows `%0D%0A` or `\r\n` linebreaks, Mac `%0D` or `\r` linebreaks, etc) MAY be used, but are NOT preferred. Non-Unix-style linebreaks will be normalised to Unix-style linebreaks by the script.

Precise and correct CIDR notation is required, otherwise the script will NOT recognise the signatures. Additionally, all the CIDR signatures of this script MUST begin with an IP address whose IP number can divide evenly into the block division represented by its CIDR block size (eg, if you wanted to block all IPs from `10.128.0.0` to `11.127.255.255`, `10.128.0.0/8` would NOT be recognised by the script, but `10.128.0.0/9` and `11.0.0.0/9` used in conjunction, WOULD be recognised by the script).

Anything in the signature files not recognised as a signature nor as signature-related syntax by the script will be IGNORED, therefore meaning that you can safely put any non-signature data that you want into the signature files without breaking them and without breaking the script. Comments are acceptable in the signature files, and no special formatting is required for them. Shell-style hashing for comments is preferred, but not enforced; Functionally, it makes no difference to the script whether or not you choose to use shell-style hashing for comments, but using shell-style hashing helps IDEs and plain-text editors to correctly highlight the various parts of the signature files (and so, shell-style hashing can assist as a visual aid while editing).

The possible values of `%Function%` are as follows:
- Run
- Whitelist
- Deny

If "Run" is used, when the signature is triggered, the script will attempt to execute (using a `require_once` statement) an external PHP script, specified by the `%Param%` value (the working directory should be the "/vault/" directory of the script).

Example: `127.0.0.0/8 Run example.php`

This can be useful if you want to execute some specific PHP code for some specific IPs and/or CIDRs.

If "Whitelist" is used, when the signature is triggered, the script will reset all detections (if there's been any detections) and break the test function. `%Param%` is ignored. This function is the equivalent of whitelisting a particular IP or CIDR from being detected.

Example: `127.0.0.1/32 Whitelist`

If "Deny" is used, when the signature is triggered, assuming no whitelist signature has been triggered for the given IP address and/or given CIDR, access to the protected page will be denied. "Deny" is what you'll want to use to actually block an IP address and/or CIDR range. When any signatures are triggered that make use of "Deny", the "Access Denied" page of the script will be generated and the request to the protected page killed.

The `%Param%` value accepted by "Deny" will be parsed to the "Access Denied" page output, supplied to the client/user as the cited reason for their access to the requested page being denied. It can be either a short and simple sentence, explaining why you've chosen to block them (anything should suffice, even a simple "I don't want you on my website"), or one of a small handful of shorthand words supplied by the script, that if used, will be replaced by the script with a pre-prepared explanation of why the client/user has been blocked.

The pre-prepared explanations have i18n support and can be translated by the script based upon the language you specify to the `lang` directive of the script configuration. Additionally, you can instruct the script to ignore "Deny" signatures based upon their `%Param%` value (if they're using these shorthand words) via the directives specified by the script configuration (each shorthand word has a corresponding directive to either process the corresponding signatures or to ignore them). `%Param%` values that don't use these shorthand words, however, don't have i18n support and therefore WON'T be translated by the script, and additionally, aren't directly controllable by the script configuration.

The available shorthand words are:
- Bogon
- Cloud
- Generic
- Spam

Optional: If you want to split your custom signatures into individual sections, you can identify these individual sections to the script by adding a "Tag:" label immediately after the signatures of each section, along with the name of your signature section.

Example:
```
# "Section 1."
1.2.3.4/32 Deny Bogon
2.3.4.5/32 Deny Cloud
4.5.6.7/32 Deny Generic
5.6.7.8/32 Deny Spam
Tag: Section 1
```

To break section tagging and to ensure that tags aren't incorrectly identified to signature sections from earlier in the signature files, simply ensure that there are at least two consecutive linebreaks between your tag and your earlier signature sections. Any untagged signatures will default to either "IPv4" or "IPv6" (depending on which types of signatures are being triggered).

Example:
```
1.2.3.4/32 Deny Bogon
2.3.4.5/32 Deny Cloud

4.5.6.7/32 Deny Generic
5.6.7.8/32 Deny Spam
Tag: Section 1
```

In the above example `1.2.3.4/32` and `2.3.4.5/32` will be tagged as "IPv4", whereas `4.5.6.7/32` and `5.6.7.8/32` will be tagged as "Section 1".

Refer to the custom signature files for more information.

---


最后更新：2016年4月3日。
