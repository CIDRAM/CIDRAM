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

CIDRAM（シドラム、クラスレス・ドメイン間・ルーティング 「Classless Inter-Domain Routing Access Manager」）は、PHPスクリプトです。ウェブサイトを保護するように設計されて、IPアドレス（望ましくないトラフィックのあるソースとみなします）から、発信要求をブロックすることによって（ヒト以外のアクセスエンドポイント、クラウドサービス、スパムロボット、スクレーパー、等）。IPアドレスの可能CIDRを計算することにより、CIDRは、その署名ファイルと比較することができます（これらの署名ファイルは不要なIPアドレスに対応するCIDRのリストが含まれています）；一致が見つかった場合、要求はブロックされます。

CIDRAM著作権2016とGNU一般公衆ライセンスv2を超える権利について： Caleb M (Maikuolan)著。

本スクリプトはフリーウェアです。フリーソフトウェア財団発行のGNU一般公衆ライセンス・バージョン２（またはそれ以降のバージョン）に従い、再配布ならびに加工が可能です。配布の目的は、役に立つことを願ってのものですが、『保証はなく、また商品性や特定の目的に適合するのを示唆するものでもありません』。"LICENSE.txt" にあるGNU General Public License（一般ライセンス）を参照して下さい。 以下のURLからも閲覧できます：
- <http://www.gnu.org/licenses/>。
- <http://opensource.org/licenses/>。

本ドキュメントならびに関連パッケージ、[Github](https://github.com/Maikuolan/CIDRAM/)からダウンロードできます。

---


###2A. <a name="SECTION2A"></a>インストール方法（ウェブサーバー編）

近い将来にはインストーラーを作成しインストールの簡素化を図りたいと考えていますが、現状では以下のインストラクションに従ってCIDRAMをインストールして下さい。少数の例外はあるものの、大多数*のシステムおよびCMSで機能します。

1) 本項を読んでいるということから、アーカイブ・スクリプトのローカルマシンへのダウンロードと解凍は終了していると考えます。ホストあるいはCMSに`/public_html/cidram/`のようなディレクトリを作り、ローカルマシンからそこにコンテンツをアップロードするのが次のステップです。アップロード先のディレクトリ名や場所については、安全でさえあれば、もちろん制約などはありませんので、自由に決めて下さい。

2) `config.ini`に`config.ini.RenameMe`の名前を変更します（`vault`の内側に位置する）。オプションの修正のため（初心者には推奨できませんが、経験が豊富なユーザーには強く推奨します）、それを開いて下さい（本ファイルはCIDRAMが利用可能なディレクティブを含んでおり、それぞれのオプションについての機能と目的に関した簡単な説明があります）。セットアップ環境にあわせて、適当な修正を行いファイルを保存して下さい。

3) コンテンツ（CIDRAM本体とファイル）を先に定めたディレクトリにアップロードします。（`*.txt`や`*.md`ファイルはアップロードの必要はありませんが、大抵は全てをアップロードしてもらって構いません）。

4) `vault`ディレクトリは`777`にアクセス権変更します。コンテンツをアップロードしたディレクトリそのものは、通常特に何もする必要ありませんが、過去にパーミッションで問題があった場合、CHMODのステータスは確認しておくと良いでしょう。（デフォルトでは`755`が一般的です）。

5) 次に、システム内あるいはCMSにCIDRAMをフックします。方法はいくつかありますが、最も容易なのは、`require`や`include`でスクリプトをシステム内／CMCのコアファイルの最初の部分に記載する方法です。（コアファイルとは、サイト内のどのページにアクセスがあっても必ずロードされるファイルのことです）。一般的には、`/includes`や`/assets`や`/functions`のようなディレクトリ内のファイルで、`init.php`、`common_functions.php`、`functions.php`といったファイル名が付けられています。実際にどのファイルなのかは、見つけてもうらう必要があります。よく分からない場合は、CIDRAMサポートフォーラムを参照するか、またはGithubのでCIDRAMの問題のページ、あるいはお知らせください（CMS情報必須）。私自身を含め、ユーザーの中に類似のCMSを扱った経験があれば、何かしらのサポートを提供できます。コアファイルが見つかったなら、「`require`か`include`を使って」以下のコードをファイルの先頭に挿入して下さい。ただし、クォーテーションマークで囲まれた部分は`loader.php`ファイルの正確なアドレス（HTTPアドレスでなく、ローカルなアドレス。前述のvaultのアドレスに類似）に置き換えます。

`<?php require '/user_name/public_html/cidram/loader.php'; ?>`

ファイルを保存して閉じ、再アップロードします。

-- 他の手法 --

Apacheウェブサーバーを利用していて、かつ`php.ini`を編集できるようであれば、`auto_prepend_file`ディレクティブを使って、PHPリクエストがあった場合にはいつもCIDRAMを先頭に追加するようにすることも可能です。以下に例を挙げます。

`auto_prepend_file = "/user_name/public_html/cidram/loader.php"`

あるいは、`.htaccess` において：

`php_value auto_prepend_file "/user_name/public_html/cidram/loader.php"`

6) インストールは完了しました。 :-)

---


###3. <a name="SECTION3"></a>使用方法

CIDRAMは自動的に望ましくない要求をブロックする必要があります；支援が必要とされていません（インストールを除きます）。

更新（アップデイト）は手動で行われています。あなたの設定ファイルを変更することによって、構成設定をカスタマイズすることができます。あなたの署名ファイルを変更することによって、CIDRsがブロックされて変更することができます。

誤検出や新種の疑わしきものに遭遇した、関することについては何でもお知らせ下さい。

---


###4. <a name="SECTION4"></a>本パッケージに含まれるファイル

以下はアーカイブから一括ダウンロードされるファイルのリスト、ならびにスクリプト使用により作成されるファイルとこれらのファイルが何のためかという簡単な説明です。

ファイル | 説明
----|----
/.gitattributes | Githubのプロジェクトファイル（機能には関係のないファイルです）。
/Changelog.txt | バージョンによる違いを記録したものです（機能には関係のないファイルです）。
/composer.json | Composer/Packagist情報（機能には関係のないファイルです）。
/LICENSE.txt | GNU/GPLv2のライセンスのコピー（機能には関係のないファイルです）。
/loader.php | ローダー・ファイルです。主要スクリプトのロード、アップロード等を行います。フックするのはまさにこれです（本質的ファイル）！
/README.md | プロジェクト概要情報。
/web.config | ASP.NET設定ファイルです（スクリプトがASP.NETテクノロジーを基礎とするサーバーにインストールされた時に`/vault`ディレクトリを権限のないソースによるアクセスから保護するためです）。
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
/vault/ | ヴォルト・ディレクトリ（様々なファイルを含んでいます）。
/vault/.htaccess | ハイパーテキスト・アクセスファイル（この場合、本スクリプトの重要なファイルを権限のないソースのアクセスから保護するためです）。
/vault/cache.dat | キャッシュ・データ。
/vault/cli.php | CLIハンドラ。
/vault/config.ini.RenameMe | CIDRAM設定ファイル；CIDRAMの全オプション設定を記載しています。それぞれのオプションの機能と動作手法の説明です（アクティブにするために名前を変更します）。
/vault/config.php | コンフィギュレーション・ハンドラ。
/vault/functions.php | 関数ファイル（本質的ファイル）。
/vault/hashes.dat | 受け入れられているハッシュのリスト（reCAPTCHAの機能に関連します；のみreCAPTCHAの機能が有効になっている場合に生成）。
/vault/ipbypass.dat | IPバイパスの一覧（reCAPTCHAの機能に関連します；のみreCAPTCHAの機能が有効になっている場合に生成）。
/vault/ipv4.dat | IPv4のシグネチャファイル。
/vault/ipv4_custom.dat.RenameMe | IPv4のカスタムシグネチャファイル（アクティブにするために名前を変更します）。
/vault/ipv6.dat | IPv6のシグネチャファイル。
/vault/ipv6_custom.dat.RenameMe | IPv6のカスタムシグネチャファイル（アクティブにするために名前を変更します）。
/vault/lang.php | 言語・ハンドラ。
/vault/lang/ | CIDRAMの言語データを含んでいます。
/vault/lang/.htaccess | ハイパーテキスト・アクセスファイル（この場合、本スクリプトの重要なファイルを権限のないソースのアクセスから保護するためです）。
/vault/lang/lang.ar.cli.php | CLIのアラビア語言語データ。
/vault/lang/lang.ar.php | アラビア語言語データ。
/vault/lang/lang.de.cli.php | CLIのドイツ語言語データ。
/vault/lang/lang.de.php | ドイツ語言語データ。
/vault/lang/lang.en.cli.php | CLIの英語言語データ。
/vault/lang/lang.en.php | 英語言語データ。
/vault/lang/lang.es.cli.php | CLIのスペイン語言語データ。
/vault/lang/lang.es.php | スペイン語言語データ。
/vault/lang/lang.fr.cli.php | CLIのフランス語言語データ。
/vault/lang/lang.fr.php | フランス語言語データ。
/vault/lang/lang.id.cli.php | CLIのインドネシア語言語データ。
/vault/lang/lang.id.php | インドネシア語言語データ。
/vault/lang/lang.it.cli.php | CLIの伊語言語データ。
/vault/lang/lang.it.php | 伊語言語データ。
/vault/lang/lang.ja.cli.php | CLIの日本語言語データ。
/vault/lang/lang.ja.php | 日本語言語データ。
/vault/lang/lang.nl.cli.php | CLIのオランダ語言語データ。
/vault/lang/lang.nl.php | オランダ語言語データ。
/vault/lang/lang.pt.cli.php | CLIのポルトガル語言語データ。
/vault/lang/lang.pt.php | ポルトガル語言語データ。
/vault/lang/lang.ru.cli.php | CLIのロシア語言語データ。
/vault/lang/lang.ru.php | ロシア語言語データ。
/vault/lang/lang.vi.cli.php | CLIのベトナム語言語データ。
/vault/lang/lang.vi.php | ベトナム語言語データ。
/vault/lang/lang.zh-TW.cli.php | CLIの繁体字中国語言語データ。
/vault/lang/lang.zh-TW.php | 繁体字中国語言語データ。
/vault/lang/lang.zh.cli.php | CLIの簡体字中国語言語データ。
/vault/lang/lang.zh.php | 簡体字中国語言語データ。
/vault/outgen.php | 出力発生器。
/vault/recaptcha.php | reCAPTCHAのモジュール。
/vault/rules_as6939.php | カスタムルールは、AS6939のためのファイル。
/vault/rules_softlayer.php | カスタムルールは、Soft Layerのためのファイル。
/vault/rules_specific.php | カスタムルールは、いくつかの特定のCIDRのためのファイル。
/vault/salt.dat | ソルトファイル（一部の周辺機能によって使用されます；必要な場合にのみ生成）。
/vault/template.html | CIDRAMテンプレートファイル；CIDRAMがファイルアップロードをブロックした際に作成されるメッセージのHTML出力用テンプレート（アップローダーが表示するメッセージ）。
/vault/template_custom.html | CIDRAMテンプレートファイル；CIDRAMがファイルアップロードをブロックした際に作成されるメッセージのHTML出力用テンプレート（アップローダーが表示するメッセージ）。

---


###5. <a name="SECTION5"></a>設定オプション
以下は`config.ini`設定ファイルにある変数ならびにその目的と機能のリストです。

####"general" （全般、カテゴリー）
全般的な設定。

"logfile" （ログ・ファイル）
- アクセス試行阻止の記録、人間によって読み取り可能。ファイル名指定するか、無効にしたい場合は空白のままにして下さい。

"logfileApache" （ログ・ファイル・アパッチ）
- アクセス試行阻止の記録、Apacheスタイル。ファイル名指定するか、無効にしたい場合は空白のままにして下さい。

"logfileSerialized" （ログ・ファイル・シリアライズ）
- アクセス試行阻止の記録、シリアル化されました。ファイル名指定するか、無効にしたい場合は空白のままにして下さい。

*有用な先端： あなたがしたい場合は、ログファイルの名前に日付/時刻情報を付加することができます、名前にこれらを含めることで:完全な年のため`{yyyy}`、省略された年のため`{yy}`、月`{mm}`、日`{dd}`、時間`{hh}`。*

*例:*
- *`logfile='logfile.{yyyy}-{mm}-{dd}-{hh}.txt'`*
- *`logfileApache='access.{yyyy}-{mm}-{dd}-{hh}.txt'`*
- *`logfileSerialized='serial.{yyyy}-{mm}-{dd}-{hh}.txt'`*

"timeOffset" （タイム・オフセット）
- お使いのサーバの時刻は、ローカル時刻と一致しない場合、あなたのニーズに応じて、時間を調整するために、あなたはここにオフセットを指定することができます。しかし、その代わりに、一般的にタイムゾーンディレクティブ（あなたの`php.ini`ファイルで）を調整ーることをお勧めします、でも時々（といった、限ら共有ホスティングプロバイダでの作業時）これは何をすることは必ずしも可能ではありません、したがって、このオプションは、ここで提供されています。オフセット分であります。
- 例（１時間を追加します）：`timeOffset=60`

"ipaddr" （アイピーアドレス）
- 接続要求のIPアドレスをどこで見つけるべきかについて（Cloudflareのようなサービスに対して有効）。 Default（デフォルト設定） = REMOTE_ADDR。
- 注意：変更には最新の注意が必要です。

"forbid_on_block" （フォービッド・オン・ブロック）
- 何ヘッダー使用する必要がありますか（要求をブロックしたとき）？ `false`（偽）/200 = 200 OK 「Default（デフォルルト）」； `true`（真） = 403 Forbidden （４０３禁止されている）； 503 = 503 Service unavailable （５０３サービス利用不可）。

"silent_mode" （サイレント・モード）
- Should CIDRAM silently redirect blocked access attempts instead of displaying the "Access Denied" page? If yes, specify the location to redirect blocked access attempts to. If no, leave this variable blank.

"lang" （ラング）
- CIDRAMのデフォルト言語を設定します。

"emailaddr" （Eメール・アドレス）
- If you wish, you can supply an email address here to be given to users when they're blocked, for them to use as a point of contact for support and/or assistance for in the event of them being blocked mistakenly or in error. WARNING: Whatever email address you supply here will most certainly be acquired by spambots and scrapers during the course of its being used here, and so, it's strongly recommended that if you choose to supply an email address here, that you ensure that the email address you supply here is a disposable address and/or an address that you don't mind being spammed (in other words, you probably don't want to use your primary personal or primary business email addresses).

"disable_cli" （ディスエイブル・シーエルアイ）
- CLIモードを無効にするか？CLIモードはデフォルトでは有効になっていますが、テストツール(PHPUnit等)やCLIベースのアプリケーションと干渉しあう可能性が無いとは言い切れません。CLIモードを無効にする必要がなければ、このデレクティブは無視してもらって結構です。`false`（偽） = Enable CLIモード（CLIモード有効） 「Default（デフォルルト）」； `true`（真） = Disable CLIモード（CLI モード無効）。

####"signatures" （シグニチャーズ、カテゴリ）
署名（シグニチャ）の設定。

"ipv4" （アイピーブイ４）
- A list of the IPv4 signature files that CIDRAM should attempt to parse, delimited by commas. You can add entries here if you want to include additional IPv4 signature files into CIDRAM.

"ipv6" （アイピーブイ６）
- A list of the IPv6 signature files that CIDRAM should attempt to parse, delimited by commas. You can add entries here if you want to include additional IPv6 signature files into CIDRAM.

"block_cloud" （ブロック・クラウド）
- クラウドサービスからのCIDRをブロックする必要がありますか？ あなたのウェブサイトからのAPIサービスを操作する場合、または、あなたがウェブサイトツーサイト接続が予想される場合、これはfalseに設定する必要があります。ない場合は、これをtrueに設定する必要があります。

"block_bogons" （ブロック・ぼごん）
- 火星の\ぼごんからのCIDRをブロックする必要がありますか？ あなたがローカルホストから、またはお使いのLANから、ローカルネットワーク内からの接続を受信した場合、これはfalseに設定する必要があります。ない場合は、これをtrueに設定する必要があります。

"block_generic" （ブロック・ジェネリック）
- 一般的なCIDRをブロックする必要がありますか？ （他のオプションに固有ではないもの）。

"block_proxies" （ブロック・プロキシ）
- プロキシサービスからのCIDRをブロックする必要がありますか？ 匿名プロキシサービスが必要な場合は、これをfalseに設定する必要があります。ない場合は、セキュリティを向上させるために、これをtrueに設定する必要があります。

"block_spam" （ブロック・スパム）
- スパムのため、CIDRをブロックする必要がありますか？ 問題がある場合を除き、一般的には、これをtrueに設定する必要があります。

####"recaptcha" （リーキャプチャ、カテゴリ）
Optionally, you can provide users with a way to bypass the "アクセス拒否" page by way of completing a reCAPTCHA instance, if you want to do so. This can help to mitigate some of the risks associated with false positives in those situations where we're not entirely sure whether a request has originated from a machine or a human.

Due to the risks associated with providing a way for end-users to bypass the "アクセス拒否" page, generally, I would advise against enabling this feature unless you feel it to be necessary to do so. Situations where it could be necessary: If your website has customers/users that need to have access to your website, and if this is something that can't be compromised on, but if those customers/users happen to be connecting from a hostile network that could potentially also be carrying undesirable traffic, and blocking this undesirable traffic is also something that can't be compromised on, in those particular no-win situations, the reCAPTCHA feature could come in handy as a means of allowing the desirable customers/users, while keeping out the undesirable traffic from the same network. That said though, given that the intended purpose of a CAPTCHA is to distinguish between humans and non-humans, the reCAPTCHA feature would only assist in these no-win situations if we're to assume that this undesirable traffic is non-human (eg, spambots, scrapers, hacktools, automated traffic), as opposed to being undesirable human traffic (such as human spammers, hackers, et al).

「site key」および「secret key」を得るために（reCAPTCHAのを使用するために必要）、このリンクをクリックしてください： [https://developers.google.com/recaptcha/](https://developers.google.com/recaptcha/)

"usemode" （ユース・モード）
- Defines how CIDRAM should use reCAPTCHA.
- 0 = reCAPTCHA is completely disabled (default).
- 1 = reCAPTCHA is enabled for all signatures.
- 2 = reCAPTCHA is enabled only for signatures belonging to sections specially marked as reCAPTCHA-enabled within the signature files.
- (Any other value will be treated in the same way as 0).

"lockip" （ロックIP）
- Specifies whether hashes should be locked to specific IPs. False = Cookies and hashes CAN be used across multiple IPs (default). True = Cookies and hashes CAN'T be used across multiple IPs (cookies/hashes are locked to IPs).
- 注意："lockip" value is ignored when "lockuser" is false, due to that the mechanism for remembering "users" differs depending on this value.

"lockuser" （ロック・ユーザー）
- Specifies whether successful completion of a reCAPTCHA instance should be locked to specific users. False = Successful completion of a reCAPTCHA instance will grant access to all requests originating from the same IP as that used by the user completing the reCAPTCHA instance; Cookies and hashes aren't used; Instead, an IP whitelist will be used. True = Successful completion of a reCAPTCHA instance will only grant access to the user completing the reCAPTCHA instance; Cookies and hashes are used to remember the user; An IP whitelist is not used (default).

"sitekey" （サイト・キー）
- この値は、あなたのreCAPTCHAのための「site key」に対応している必要があり；これは、reCAPTCHAのダッシュボードの中に見つけることができます。

"secret" （シークレット）
- この値は、あなたのreCAPTCHAのための「secret key」に対応している必要があり；これは、reCAPTCHAのダッシュボードの中に見つけることができます。

"expiry"
- When "lockuser" is true (default), in order to remember when a user has successfully passed a reCAPTCHA instance, for future page requests, CIDRAM generates a standard HTTP cookie containing a hash which corresponds to an internal record containing that same hash; Future page requests will use these corresponding hashes to authenticate that a user has previously already passed a reCAPTCHA instance. When "lockuser" is false, an IP whitelist is used to determine whether requests should be permitted from the IP of inbound requests; Entries are added to this whitelist when the reCAPTCHA instance is successfully passed. For how many hours should these cookies, hashes and whitelist entries remain valid? Default = 720 (1 month).

"logfile" （ログ・ファイル）
- reCAPTCHA試行の記録。ファイル名指定するか、無効にしたい場合は空白のままにして下さい。

*有用な先端： あなたがしたい場合は、ログファイルの名前に日付/時刻情報を付加することができます、名前にこれらを含めることで:完全な年のため`{yyyy}`、省略された年のため`{yy}`、月`{mm}`、日`{dd}`、時間`{hh}`。*

*例:*
- *`logfile='recaptcha.{yyyy}-{mm}-{dd}-{hh}.txt'`*

####"template_data" （テンプレート・データ、カテゴリ）
テンプレートとテーマ用のディレクティブ／変数。

テンプレートのデータは、ユーザーに向けてアクセス拒否のメッセージをHTML形式でアウトプットする際に使用されます。カスタムテーマを使っている場合は`template_custom.html`を使用して、そうでない場合は`template.html`を使用してHTMLアウトプットが生成されます。設定ファイル内にあるこのセクション用の変数は、HTMLアウトプットのために解析され、で囲まれた変数名は対応する変数データに置き換えられます。例えば`foo="bar"`とすると、HTMLアウトプット内の`<p>{foo}</p>`は`<p>bar</p>`となります。

"css_url" （シーエスエス・ユーアールエル）
- カスタムテーマ用のテンプレートファイルは、外部CSSプロパティーを使っています。一方、デフォルトテーマは内部CSSです。カスタムテーマを適用するためには、CSSファイルのパブリック HTTPアドレスを"css_url"変数を使って指定して下さい。この変数が空白であれば、デフォルトテーマが適用されます。

---


###6. <a name="SECTION6"></a>署名（シグニチャ）フォーマット

####6.0 基本原則

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

「Function」の可能な値：
- Run
- Whitelist
- Greylist
- Deny

If "Run" is used, when the signature is triggered, the script will attempt to execute (using a `require_once` statement) an external PHP script, specified by the `[Param]` value (the working directory should be the "/vault/" directory of the script).

例c`127.0.0.0/8 Run example.php`

This can be useful if you want to execute some specific PHP code for some specific IPs and/or CIDRs.

If "Whitelist" is used, when the signature is triggered, the script will reset all detections (if there's been any detections) and break the test function. `[Param]` is ignored. This function is the equivalent of whitelisting a particular IP or CIDR from being detected.

例：`127.0.0.1/32 Whitelist`

If "Greylist" is used, when the signature is triggered, the script will reset all detections (if there's been any detections) and skip to the next signature file to continue processing. `[Param]` is ignored.

例：`127.0.0.1/32 Greylist`

If "Deny" is used, when the signature is triggered, assuming no whitelist signature has been triggered for the given IP address and/or given CIDR, access to the protected page will be denied. "Deny" is what you'll want to use to actually block an IP address and/or CIDR range. When any signatures are triggered that make use of "Deny", the "Access Denied" page of the script will be generated and the request to the protected page killed.

The `[Param]` value accepted by "Deny" will be parsed to the "Access Denied" page output, supplied to the client/user as the cited reason for their access to the requested page being denied. It can be either a short and simple sentence, explaining why you've chosen to block them (anything should suffice, even a simple "I don't want you on my website"), or one of a small handful of shorthand words supplied by the script, that if used, will be replaced by the script with a pre-prepared explanation of why the client/user has been blocked.

The pre-prepared explanations have i18n support and can be translated by the script based upon the language you specify to the `lang` directive of the script configuration. Additionally, you can instruct the script to ignore "Deny" signatures based upon their `[Param]` value (if they're using these shorthand words) via the directives specified by the script configuration (each shorthand word has a corresponding directive to either process the corresponding signatures or to ignore them). `[Param]` values that don't use these shorthand words, however, don't have i18n support and therefore WON'T be translated by the script, and additionally, aren't directly controllable by the script configuration.

略語：
- Bogon
- Cloud
- Generic
- Proxy
- Spam

####6.1 タグ

If you want to split your custom signatures into individual sections, you can identify these individual sections to the script by adding a "section tag" immediately after the signatures of each section, along with the name of your signature section （以下の例を参照してください）。

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

```
1.2.3.4/32 Deny Bogon
2.3.4.5/32 Deny Cloud

4.5.6.7/32 Deny Generic
5.6.7.8/32 Deny Spam
Tag: Section 1
```

上記の例で、`1.2.3.4/32`と`2.3.4.5/32`は、「IPv4」でタグ付けされま；す一方、`4.5.6.7/32`と`5.6.7.8/32`は、「Section 1」でタグ付けされま。

If you want signatures to expire after some time, in a similar manner to section tags, you can use an "expiry tag" to specify when signatures should cease to be valid. 期限切れのタグがこの形式を使用します： 「年年年年.月月.日日」 （以下の例を参照してください）。

```
# "Section 1."
1.2.3.4/32 Deny Generic
2.3.4.5/32 Deny Generic
Expires: 2016.12.31
```

セクションタグと期限切れタグを組み合わせて使用することができ；両方はオプションです（以下の例を参照してください）。

```
# "Example Section."
1.2.3.4/32 Deny Generic
Tag: Example Section
Expires: 2016.12.31
```

####6.2 YAML

#####6.2.0 YAML基本原則

A simplified form of YAML markup may be used in signature files for the purpose of defining behaviours and settings specific to individual signature sections. This may be useful if you want the value of your configuration directives to differ on the basis of individual signatures and signature sections (for example; if you want to supply an email address for support tickets for any users blocked by one particular signature, but don't want to supply an email address for support tickets for users blocked by any other signatures; if you want some specific signatures to trigger a page redirect; if you want to mark a signature section for use with reCAPTCHA; if you want to log blocked access attempts to separate files on the basis of individual signatures and/or signature sections).

Use of YAML markup in the signature files is entirely optional (ie, you may use it if you wish to do so, but you are not required to do so), and is able to leverage most (but not all) configuration directives.

注意：YAML markup implementation in CIDRAM is very simplistic and very limited; It is intended to fulfill requirements specific to CIDRAM in a manner that has the familiarity of YAML markup, but neither follows nor complies with official specifications (and therefore won't behave in the same way as more thorough implementations elsewhere, and may not be appropriate for other projects elsewhere).

In CIDRAM, YAML markup segments are identified to the script by three dashes ("---"), and terminate alongside their containing signature sections by double-linebreaks. A typical YAML markup segment within a signature section consists of three dashes on a line immediately after the list of CIDRS and any tags, followed by a two dimensional list of key-value pairs (first dimension, configuration directive categories; second dimension, configuration directives) for which configuration directives should be modified (and to which values) whenever a signature within that signature section is triggered （以下の例を参照してください）。

```
# "Foobar 1."
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

# "Foobar 2."
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

# "Foobar 3."
1.2.3.4/32 Deny Generic
2.3.4.5/32 Deny Generic
4.5.6.7/32 Deny Generic
Tag: Foobar 3
---
general:
 forbid_on_block: 403
 silent_mode: "http://127.0.0.1/"
```

#####6.2.1 HOW TO "SPECIALLY MARK" SIGNATURE SECTIONS FOR USE WITH reCAPTCHA

When "usemode" is 0 or 1, signature sections don't need to be "specially marked" for use with reCAPTCHA (because they already either will or won't use reCAPTCHA, depending on this setting).

When "usemode" is 2, to "specially mark" signature sections for use with reCAPTCHA, an entry is included in the YAML segment for that signature section （以下の例を参照してください）。

```
# This section will use reCAPTCHA.
1.2.3.4/32 Deny Generic
2.3.4.5/32 Deny Generic
Tag: reCAPTCHA-Enabled
---
recaptcha:
 enabled: true
```

注意：A reCAPTCHA instance will ONLY be offered to the user if reCAPTCHA is enabled (either with "usemode" as 1, or "usemode" as 2 with "enabled" as true), and if exactly ONE signature has been triggered (no more, no less; if multiple signatures are triggered, a reCAPTCHA instance will NOT be offered).

####6.3 補助

In addition, if you want CIDRAM to completely ignore some specific sections within any of the signature files, you can use the `ignore.dat` file to specify which sections to ignore. On a new line, write `Ignore`, followed by a space, followed by the name of the section that you want CIDRAM to ignore （以下の例を参照してください）。

```
Ignore Section 1
```

詳細については、カスタムシグネチャファイルを参照してください。

---


最終アップデート： 2016年9月3日。
