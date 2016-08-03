## CIDRAMのドキュメンテーション（日本語）。

### 目次
- 1. [序文](#SECTION1)
- 2. [インストール方法](#SECTION2)
- 3. [使用方法](#SECTION3)
- 4. [本パッケージに含まれるファイル](#SECTION4)
- 5. [設定オプション](#SECTION5)
- 6. [署名（シグニチャ）フォーマット](#SECTION6)

---


###1. <a name="SECTION1"></a>序文

CIDRAM (Classless Inter-Domain Routing Access Manager) is a PHP script designed to protect websites by blocking requests originating from IP addresses regarded as being sources of undesirable traffic, including (but not limited to) traffic from non-human access endpoints, cloud services, spambots, scrapers, etc. It does this by calculating the possible CIDRs of the IP addresses supplied from inbound requests and then attempting to match these possible CIDRs against its signature files (these signature files contain lists of CIDRs of IP addresses regarded as being sources of undesirable traffic); If matches are found, the requests are blocked. @TranslateMe@

CIDRAM著作権2016とGNU一般公衆ライセンスv2を超える権利について: Caleb M (Maikuolan)著。

本スクリプトはフリーウェアです。フリーソフトウェア財団発行のGNU一般公衆ライセンス・バージョン２（またはそれ以降のバージョン）に従い、再配布ならびに加工が可能です。配布の目的は、役に立つことを願ってのものですが、『保証はなく、また商品性や特定の目的に適合するのを示唆するものでもありません』。"LICENSE.txt" にあるGNU General Public License（一般ライセンス）を参照して下さい。 以下のURLからも閲覧できます：
- <http://www.gnu.org/licenses/>。
- <http://opensource.org/licenses/>。

本ドキュメントならびに関連パッケージ、[Github](https://github.com/Maikuolan/CIDRAM/)からダウンロードできます。

---


###2A. <a name="SECTION2A"></a>インストール方法（ウェブサーバー編）

近い将来にはインストーラーを作成しインストールの簡素化を図りたいと考えていますが、現状では以下のインストラクションに従ってCIDRAMをインストールして下さい。少数の例外はあるものの、大多数*のシステムおよびCMSで機能します。

1) 本項を読んでいるということから、アーカイブ・スクリプトのローカルマシンへのダウンロードと解凍は終了していると考えます。ホストあるいはCMSに`/public_html/cidram/`のようなディレクトリを作り、ローカルマシンからそこにコンテンツをアップロードするのが次のステップです。アップロード先のディレクトリ名や場所については、安全でさえあれば、もちろん制約などはありませんので、自由に決めて下さい。

2) Rename `config.ini.RenameMe` to `config.ini` (located inside `vault`), and optionally (strongly recommended for advanced users, but not recommended for beginners or for the inexperienced), open it (this file contains all the directives available for CIDRAM; above each option should be a brief comment describing what it does and what it's for). Adjust these directives as you see fit, as per whatever is appropriate for your particular setup. Save file, close. @TranslateMe@

3) コンテンツ（CIDRAM本体とファイル）を先に定めたディレクトリにアップロードします。（`*.txt`や`*.md`ファイルはアップロードの必要はありませんが、大抵は全てをアップロードしてもらって構いません）。

4) `vault`ディレクトリは`777`にアクセス権変更します。コンテンツをアップロードしたディレクトリそのものは、通常特に何もする必要ありませんが、過去にパーミッションで問題があった場合、CHMODのステータスは確認しておくと良いでしょう。（デフォルトでは`755`が一般的です）。

5) 次に、システム内あるいはCMSにCIDRAMをフックします。方法はいくつかありますが、最も容易なのは、`require`や`include`でスクリプトをシステム内／CMCのコアファイルの最初の部分に記載する方法です。（コアファイルとは、サイト内のどのページにアクセスがあっても必ずロードされるファイルのことです）。一般的には、`/includes`や`/assets`や`/functions`のようなディレクトリ内のファイルで、`init.php`、`common_functions.php`、`functions.php`といったファイル名が付けられています。実際にどのファイルなのかは、見つけてもうらう必要があります。よく分からない場合は、CIDRAMサポートフォーラムを参照するか、またはGithubのでCIDRAMの問題のページ、あるいはお知らせください（CMS情報必須）。私自身を含め、ユーザーの中に類似のCMSを扱った経験があれば、何かしらのサポートを提供できます。コアファイルが見つかったなら、「`require`か`include`を使って」以下のコードをファイルの先頭に挿入して下さい。ただし、クォーテーションマークで囲まれた部分は`loader.php`ファイルの正確なアドレス（HTTPアドレスでなく、ローカルなアドレス。前述のvaultのアドレスに類似）に置き換えます。

`<?php require '/user_name/public_html/cidram/loader.php'; ?>`

ファイルを保存して閉じ、再アップロードします。

-- 他の手法 --

Apacheウェブサーバーを利用していて、かつ`php.ini`を編集できるようであれば、`auto_prepend_file`ディレクティブを使って、PHPリクエストがあった場合にはいつもphpMusselを先頭に追加するようにすることも可能です。以下に例を挙げます。

`auto_prepend_file = "/user_name/public_html/cidram/loader.php"`

あるいは、`.htaccess` において：

`php_value auto_prepend_file "/user_name/public_html/cidram/loader.php"`

6) インストールは完了しました。 :-)

---


###3. <a name="SECTION3"></a>使用方法

CIDRAM should automatically block undesirable requests to your website without requiring any manual assistance, aside from its initial installation. @TranslateMe@

Updating is done manually, and you can customise your configuration and customise which CIDRs are blocked by modifying your configuration file and/or your signature files. @TranslateMe@

If you encounter any false positives, please contact me to let me know about it. @TranslateMe@

---


###5. <a name="SECTION5"></a>本パッケージに含まれるファイル

以下はアーカイブから一括ダウンロードされるファイルのリスト、ならびにスクリプト使用により作成されるファイルとこれらのファイルが何のためかという簡単な説明です。

ファイル | 説明
----|----
/.gitattributes | Githubのプロジェクトファイル（機能には関係のないファイルです）。
/Changelog.txt | バージョンによる違いを記録したものです（機能には関係のないファイルです）。
/composer.json | Composer/Packagist情報（機能には関係のないファイルです）。
/LICENSE.txt | GNU/GPLv2のライセンスのコピー（機能には関係のないファイルです）。
/loader.php | ローダー・ファイルです。主要スクリプトのロード、アップロード等を行います。フックするのはまさにこれです（本質的ファイル）！
/README.md | プロジェクト概要情報。
/web.config | ASP.NET設定ファイルです（スクリプトがASP.NET テクノロジーを基礎とするサーバーにインストールされた時に`/vault`ディレクトリを権限のないソースによるアクセスから保護するためです）。
/_docs/ | ドキュメンテーション用のディレクトリです（様々なファイルを含みます）。
/_docs/readme.ar.md | アラビア語ドキュメンテーション。
/_docs/readme.de.md | ドイツ語ドキュメンテーション。
/_docs/readme.en.md | 英語ドキュメンテーション。
/_docs/readme.es.md | スペイン語ドキュメンテーション。
/_docs/readme.fr.md | フランス語ドキュメンテーション。
/_docs/readme.id.md | インドネシア語ドキュメンテーション。
/_docs/readme.it.md | 伊語ドキュメンテーション。
/_docs/readme.ja.md | 日本語ドキュメンテーション。
/_docs/readme.nl.md | オランダ語ドキュメンテーション。
/_docs/readme.pt.md | ポルトガル語ドキュメンテーション。
/_docs/readme.ru.md | ロシア語ドキュメンテーション。
/_docs/readme.vi.md | ベトナム語ドキュメンテーション。
/_docs/readme.zh-TW.md | 繁体字中国語ドキュメンテーション。
/_docs/readme.zh.md | 簡体字中国語ドキュメンテーション。
/vault/ | Vault directory (contains various files).
/vault/.htaccess | A hypertext access file (in this instance, to protect sensitive files belonging to the script from being accessed by non-authorised sources).
/vault/cache.dat | Cache data.
/vault/cli.php | CLI handler.
/vault/config.ini.RenameMe | Configuration file; Contains all the 設定オプション of CIDRAM, telling it what to do and how to operate correctly (rename to activate).
/vault/config.php | Configuration handler.
/vault/functions.php | Functions file (essential).
/vault/ipv4.dat | IPv4 signatures file.
/vault/ipv4_custom.dat.RenameMe | IPv4 custom signatures file (rename to activate).
/vault/ipv6.dat | IPv6 signatures file.
/vault/ipv6_custom.dat.RenameMe | IPv6 custom signatures file (rename to activate).
/vault/lang.php | Language handler.
/vault/lang/ | Contains CIDRAM language data.
/vault/lang/.htaccess | A hypertext access file (in this instance, to protect sensitive files belonging to the script from being accessed by non-authorised sources).
/vault/lang/lang.ar.cli.php | Arabic language data for CLI.
/vault/lang/lang.ar.php | Arabic language data.
/vault/lang/lang.de.cli.php | German language data for CLI.
/vault/lang/lang.de.php | German language data.
/vault/lang/lang.en.cli.php | English language data for CLI.
/vault/lang/lang.en.php | English language data.
/vault/lang/lang.es.cli.php | Spanish language data for CLI.
/vault/lang/lang.es.php | Spanish language data.
/vault/lang/lang.fr.cli.php | French language data for CLI.
/vault/lang/lang.fr.php | French language data.
/vault/lang/lang.id.cli.php | Indonesian language data for CLI.
/vault/lang/lang.id.php | Indonesian language data.
/vault/lang/lang.it.cli.php | Italian language data for CLI.
/vault/lang/lang.it.php | Italian language data.
/vault/lang/lang.ja.cli.php | Japanese language data for CLI.
/vault/lang/lang.ja.php | Japanese language data.
/vault/lang/lang.nl.cli.php | Dutch language data for CLI.
/vault/lang/lang.nl.php | Dutch language data.
/vault/lang/lang.pt.cli.php | Portuguese language data for CLI.
/vault/lang/lang.pt.php | Portuguese language data.
/vault/lang/lang.ru.cli.php | Russian language data for CLI.
/vault/lang/lang.ru.php | Russian language data.
/vault/lang/lang.vi.cli.php | Vietnamese language data for CLI.
/vault/lang/lang.vi.php | Vietnamese language data.
/vault/lang/lang.zh-tw.cli.php | Chinese (traditional) language data for CLI.
/vault/lang/lang.zh-tw.php | Chinese (traditional) language data.
/vault/lang/lang.zh.cli.php | Chinese (simplified) language data for CLI.
/vault/lang/lang.zh.php | Chinese (simplified) language data.
/vault/outgen.php | Output generator.
/vault/template.html | Template file; Template for HTML output produced by the CIDRAM output generator.
/vault/template_custom.html | Template file; Template for HTML output produced by the CIDRAM output generator.
/vault/rules_as6939.php | Custom rules file for AS6939.
/vault/rules_softlayer.php | Custom rules file for Soft Layer.
/vault/rules_specific.php | Custom rules file for some specific CIDRs.

---


###5. <a name="SECTION5"></a>設定オプション
The following is a list of the directives available to CIDRAM in the `config.ini` configuration file, along with a description of the purpose of these directives.

####"general" (Category)
General CIDRAM configuration.

"logfile"
- Human readable file for logging all blocked access attempts. Specify a filename, or leave blank to disable.

"logfileApache"
- Apache-style file for logging all blocked access attempts. Specify a filename, or leave blank to disable.

"logfileSerialized"
- Serialised file for logging all blocked access attempts. Specify a filename, or leave blank to disable.

*Useful tip: If you want, you can append date/time information to the names of your logfiles by including these in the name: `{yyyy}` for complete year, `{yy}` for abbreviated year, `{mm}` for month, `{dd}` for day, `{hh}` for hour.*

*Examples:*
- *`logfile='logfile.{yyyy}-{mm}-{dd}-{hh}.txt'`*
- *`logfileApache='access.{yyyy}-{mm}-{dd}-{hh}.txt'`*
- *`logfileSerialized='serial.{yyyy}-{mm}-{dd}-{hh}.txt'`*

"timeOffset"
- If your server time doesn't match your local time, you can specify an offset here to adjust the date/time information generated by CIDRAM according to your needs. It's generally recommended instead to adjust the timezone directive in your `php.ini` file, but sometimes (such as when working with limited shared hosting providers) this isn't always possible to do, and so, this option is provided here. Offset is in minutes.
- Example (to add one hour): `timeOffset=60`

"ipaddr"
- Where to find the IP address of connecting requests? (Useful for services such as Cloudflare and the likes) Default = REMOTE_ADDR. WARNING: Don't change this unless you know what you're doing!

"forbid_on_block"
- Should CIDRAM respond with 403 headers to blocked requests, or stick with the usual 200 OK? False/200 = No (200) [Default]; True = Yes (403); 503 = Service unavailable (503).

"silent_mode"
- Should CIDRAM silently redirect blocked access attempts instead of displaying the "Access Denied" page? If yes, specify the location to redirect blocked access attempts to. If no, leave this variable blank.

"lang"
- Specify the default language for CIDRAM.

"emailaddr"
- If you wish, you can supply an email address here to be given to users when they're blocked, for them to use as a point of contact for support and/or assistance for in the event of them being blocked mistakenly or in error. WARNING: Whatever email address you supply here will most certainly be acquired by spambots and scrapers during the course of its being used here, and so, it's strongly recommended that if you choose to supply an email address here, that you ensure that the email address you supply here is a disposable address and/or an address that you don't mind being spammed (in other words, you probably don't want to use your primary personal or primary business email addresses).

"disable_cli"
- Disable CLI mode? CLI mode is enabled by default, but can sometimes interfere with certain testing tools (such as PHPUnit, for example) and other CLI-based applications. If you don't need to disable CLI mode, you should ignore this directive. False = Enable CLI mode [Default]; True = Disable CLI mode.

####"signatures" (Category)
Signatures configuration.

"ipv4"
- A list of the IPv4 signature files that CIDRAM should attempt to parse, delimited by commas. You can add entries here if you want to include additional IPv4 signature files into CIDRAM.

"ipv6"
- A list of the IPv6 signature files that CIDRAM should attempt to parse, delimited by commas. You can add entries here if you want to include additional IPv6 signature files into CIDRAM.

"block_cloud"
- Block CIDRs identified as belonging to webhosting/cloud services? If you operate an API service from your website or if you expect other websites to connect to your website, this should be set to false. If you don't, then, this directive should be set to true.

"block_bogons"
- Block bogon/martian CIDRs? If you expect connections to your website from within your local network, from localhost, or from your LAN, this directive should be set to false. If you don't expect these such connections, this directive should be set to true.

"block_generic"
- Block CIDRs generally recommended for blacklisting? This covers any signatures that aren't marked as being part of any of the other more specific signature categories.

"block_proxies"
- Block CIDRs identified as belonging to proxy services? If you require that users be able to access your website from anonymous proxy services, this should be set to false. Otherwise, if you don't require anonymous proxies, this directive should be set to true as a means of improving security.

"block_spam"
- Block CIDRs identified as being high-risk for spam? Unless you experience problems when doing so, generally, this should always be set to true.

####"template_data" (Category)
Directives/Variables for templates and themes.

Relates to the HTML output used to generate the "Access Denied" page. If you're using custom themes for CIDRAM, HTML output is sourced from the `template_custom.html` file, and otherwise, HTML output is sourced from the `template.html` file. Variables written to this section of the configuration file are parsed to the HTML output by way of replacing any variable names circumfixed by curly brackets found within the HTML output with the corresponding variable data. For example, where `foo="bar"`, any instance of `<p>{foo}</p>` found within the HTML output will become `<p>bar</p>`.

"css_url"
- The template file for custom themes utilises external CSS properties, whereas the template file for the default theme utilises internal CSS properties. To instruct CIDRAM to use the template file for custom themes, specify the public HTTP address of your custom theme's CSS files using the `css_url` variable. If you leave this variable blank, CIDRAM will use the template file for the default theme.

---


###6. <a name="SECTION6"></a>署名（シグニチャ）フォーマット

A description of the format and structure of the signatures used by CIDRAM can be found documented in plain-text within either of the two custom signature files. Please refer to that documentation to learn more about the format and structure of the signatures of CIDRAM.

All IPv4 signatures follow the format: `xxx.xxx.xxx.xxx/yy [Function] [Param]`.
- `xxx.xxx.xxx.xxx` represents the beginning of the CIDR block (the octets of the initial IP address in the block).
- `yy` represents the CIDR block size [1-32].
- `[Function]` instructs the script what to do with the signature (how the signature should be regarded).
- `[Param]` represents whatever additional information may be required by `[Function]`.

All IPv6 signatures follow the format: `xxxx:xxxx:xxxx:xxxx::xxxx/yy [Function] [Param]`.
- `xxxx:xxxx:xxxx:xxxx::xxxx` represents the beginning of the CIDR block (the octets of the initial IP address in the block). Complete notation and abbreviated notation are both acceptable (and each MUST follow the appropriate and relevant standards of IPv6 notation, but with one exception: an IPv6 address can never begin with an abbreviation when used in a signature for this script, due to the way in which CIDRs are reconstructed by the script; For example, `::1/128` should be expressed, when used in a signature, as `0::1/128`, and `::0/128` expressed as `0::/128`).
- `yy` represents the CIDR block size [1-128].
- `[Function]` instructs the script what to do with the signature (how the signature should be regarded).
- `[Param]` represents whatever additional information may be required by `[Function]`.

The signature files for CIDRAM SHOULD use Unix-style linebreaks (`%0A`, or `\n`)! Other types/styles of linebreaks (eg, Windows` %0D%0A` or `\r\n` linebreaks, Mac `%0D` or `\r` linebreaks, etc) MAY be used, but are NOT preferred. Non-Unix-style linebreaks will be normalised to Unix-style linebreaks by the script.

Precise and correct CIDR notation is required, otherwise the script will NOT recognise the signatures. Additionally, all the CIDR signatures of this script MUST begin with an IP address whose IP number can divide evenly into the block division represented by its CIDR block size (eg, if you wanted to block all IPs from `10.128.0.0` to `11.127.255.255`, `10.128.0.0/8` would NOT be recognised by the script, but `10.128.0.0/9` and `11.0.0.0/9` used in conjunction, WOULD be recognised by the script).

Anything in the signature files not recognised as a signature nor as signature-related syntax by the script will be IGNORED, therefore meaning that you can safely put any non-signature data that you want into the signature files without breaking them and without breaking the script. Comments are acceptable in the signature files, and no special formatting is required for them. Shell-style hashing for comments is preferred, but not enforced; Functionally, it makes no difference to the script whether or not you choose to use Shell-style hashing for comments, but using Shell-style hashing helps IDEs and plain-text editors to correctly highlight the various parts of the signature files (and so, Shell-style hashing can assist as a visual aid while editing).

The possible values of `[Function]` are as follows:
- Run
- Whitelist
- Greylist
- Deny

If "Run" is used, when the signature is triggered, the script will attempt to execute (using a `require_once` statement) an external PHP script, specified by the `[Param]` value (the working directory should be the "/vault/" directory of the script).

Example: `127.0.0.0/8 Run example.php`

This can be useful if you want to execute some specific PHP code for some specific IPs and/or CIDRs.

If "Whitelist" is used, when the signature is triggered, the script will reset all detections (if there's been any detections) and break the test function. `[Param]` is ignored. This function is the equivalent of whitelisting a particular IP or CIDR from being detected.

Example: `127.0.0.1/32 Whitelist`

If "Greylist" is used, when the signature is triggered, the script will reset all detections (if there's been any detections) and skip to the next signature file to continue processing. `[Param]` is ignored.

Example: `127.0.0.1/32 Greylist`

If "Deny" is used, when the signature is triggered, assuming no whitelist signature has been triggered for the given IP address and/or given CIDR, access to the protected page will be denied. "Deny" is what you'll want to use to actually block an IP address and/or CIDR range. When any signatures are triggered that make use of "Deny", the "Access Denied" page of the script will be generated and the request to the protected page killed.

The `[Param]` value accepted by "Deny" will be parsed to the "Access Denied" page output, supplied to the client/user as the cited reason for their access to the requested page being denied. It can be either a short and simple sentence, explaining why you've chosen to block them (anything should suffice, even a simple "I don't want you on my website"), or one of a small handful of shorthand words supplied by the script, that if used, will be replaced by the script with a pre-prepared explanation of why the client/user has been blocked.

The pre-prepared explanations have i18n support and can be translated by the script based upon the language you specify to the `lang` directive of the script configuration. Additionally, you can instruct the script to ignore "Deny" signatures based upon their `[Param]` value (if they're using these shorthand words) via the directives specified by the script configuration (each shorthand word has a corresponding directive to either process the corresponding signatures or to ignore them). `[Param]` values that don't use these shorthand words, however, don't have i18n support and therefore WON'T be translated by the script, and additionally, aren't directly controllable by the script configuration.

The available shorthand words are:
- Bogon
- Cloud
- Generic
- Proxy
- Spam

Optional: If you want to split your custom signatures into individual sections, you can identify these individual sections to the script by adding a "Tag:" label immediately after the signatures of each section, along with the name of your signature section.

Example:
```
# "Section 1."
1.2.3.4/32 Deny Bogon
2.3.4.5/32 Deny Cloud
4.5.6.7/32 Deny Generic
5.6.7.8/32 Deny Spam
6.7.8.9/32 Deny Proxy
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

In addition, if you want CIDRAM to completely ignore some specific sections within any of the signature files, you can use the `ignore.dat` file to specify which sections to ignore. On a new line, write `Ignore`, followed by a space, followed by the name of the section that you want CIDRAM to ignore.

Example:
```
Ignore Section 1
```

Refer to the custom signature files for more information.

---


最終アップデート： 2016年08月03日。
