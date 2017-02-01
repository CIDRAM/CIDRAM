<?php
/**
 * This file is a part of the CIDRAM package, and can be downloaded for free
 * from {@link https://github.com/Maikuolan/CIDRAM/ GitHub}.
 *
 * CIDRAM COPYRIGHT 2016 and beyond by Caleb Mazalevskis (Maikuolan).
 *
 * License: GNU/GPLv2
 * @see LICENSE.txt
 *
 * This file: Japanese language data for the front-end (last modified: 2017.01.31).
 */

/** Prevents execution from outside of CIDRAM. */
if (!defined('CIDRAM')) {
    die('[CIDRAM] This should not be accessed directly.');
}

$CIDRAM['lang']['bNav_home_logout'] = '<a href="?">ホーム</a> | <a href="?cidram-page=logout">ログアウト</a>';
$CIDRAM['lang']['bNav_logout'] = '<a href="?cidram-page=logout">ログアウト</a>';
$CIDRAM['lang']['config_general_ban_override'] = '「infraction_limit」を超えたときに「forbid_on_block」を上書きしますか？ 上書きするとき：ブロックされた要求は空白のページを返します（テンプレートファイルは使用されません）。 ２００ = 上書きしない（Default/デフォルルト）； ４０３ = 「403 Forbidden」で上書きする； ５０３ = 「503 Service unavailable」で上書きする。';
$CIDRAM['lang']['config_general_default_dns'] = 'ホスト名検索に使用する、DNS（ドメイン・ネーム・システム）サーバーのカンマ区切りリスト。 Default（デフォルルト） = "8.8.8.8,8.8.4.4" （Google DNS）。 注意： あなたが何をしているのか、分からない限り、これを変更しないでください。';
$CIDRAM['lang']['config_general_disable_cli'] = 'CLIモードを無効にするか？';
$CIDRAM['lang']['config_general_disable_frontend'] = 'フロントエンドへのアクセスを無効にするか？';
$CIDRAM['lang']['config_general_emailaddr'] = 'サポートのためのＥメールアドレス。';
$CIDRAM['lang']['config_general_forbid_on_block'] = '何ヘッダー使用する必要がありますか（要求をブロックしたとき）？';
$CIDRAM['lang']['config_general_FrontEndLog'] = 'フロントエンド・ログインの試みを記録するためのファイル。ファイル名指定するか、無効にしたい場合は空白のままにして下さい。';
$CIDRAM['lang']['config_general_ipaddr'] = '接続要求のIPアドレスをどこで見つけるべきかについて（Cloudflareのようなサービスに対して有効）。 Default（デフォルト設定） = REMOTE_ADDR。 注意： あなたが何をしているのか、分からない限り、これを変更しないでください。';
$CIDRAM['lang']['config_general_lang'] = 'CIDRAMのデフォルト言語を設定します。';
$CIDRAM['lang']['config_general_logfile'] = 'アクセス試行阻止の記録、人間によって読み取り可能。ファイル名指定するか、無効にしたい場合は空白のままにして下さい。';
$CIDRAM['lang']['config_general_logfileApache'] = 'アクセス試行阻止の記録、Apacheスタイル。ファイル名指定するか、無効にしたい場合は空白のままにして下さい。';
$CIDRAM['lang']['config_general_logfileSerialized'] = 'アクセス試行阻止の記録、シリアル化されました。ファイル名指定するか、無効にしたい場合は空白のままにして下さい。';
$CIDRAM['lang']['config_general_log_banned_ips'] = '禁止されたIPからブロックされた要求をログファイルに含めますか？ True = はい （Default/デフォルルト）； False = いいえ。';
$CIDRAM['lang']['config_general_max_login_attempts'] = 'ログイン試行の最大回数。';
$CIDRAM['lang']['config_general_protect_frontend'] = 'CIDRAMによって通常提供される保護をフロントエンドに適用するかどうかを指定します。 True = はい （Default/デフォルルト）； False = いいえ。';
$CIDRAM['lang']['config_general_silent_mode'] = '「アクセス拒否」ページを表示する代わりに、CIDRAMはブロックされたアクセス試行を自動的にリダイレクトする必要がありますか？はいの場合は、リダイレクトの場所を指定します。いいえの場合は、この変数を空白のままにします。';
$CIDRAM['lang']['config_general_timeOffset'] = 'タイムゾーンオフセット（分）。';
$CIDRAM['lang']['config_recaptcha_expiry'] = 'reCAPTCHAインスタンスを覚えておく時間数。';
$CIDRAM['lang']['config_recaptcha_lockip'] = 'reCAPTCHAをIPにロックしますか？';
$CIDRAM['lang']['config_recaptcha_lockuser'] = 'reCAPTCHAをユーザーにロックしますか？';
$CIDRAM['lang']['config_recaptcha_logfile'] = 'reCAPTCHA試行の記録。ファイル名指定するか、無効にしたい場合は空白のままにして下さい。';
$CIDRAM['lang']['config_recaptcha_secret'] = 'この値は、あなたのreCAPTCHAのための「secret key」に対応している必要があり；これは、reCAPTCHAのダッシュボードの中に見つけることができます。';
$CIDRAM['lang']['config_recaptcha_sitekey'] = 'この値は、あなたのreCAPTCHAのための「site key」に対応している必要があり；これは、reCAPTCHAのダッシュボードの中に見つけることができます。';
$CIDRAM['lang']['config_recaptcha_usemode'] = 'reCAPTCHAをCIDRAMで使用する方法（ドキュメントを参照してください）。';
$CIDRAM['lang']['config_signatures_block_bogons'] = '火星の\ぼごんからのCIDRをブロックする必要がありますか？ あなたがローカルホストから、またはお使いのLANから、ローカルネットワーク内からの接続を受信した場合、これはfalseに設定する必要があります。ない場合は、これをtrueに設定する必要があります。';
$CIDRAM['lang']['config_signatures_block_cloud'] = 'クラウドサービスからのCIDRをブロックする必要がありますか？ あなたのウェブサイトからのAPIサービスを操作する場合、または、あなたがウェブサイトツーサイト接続が予想される場合、これはfalseに設定する必要があります。ない場合は、これをtrueに設定する必要があります。';
$CIDRAM['lang']['config_signatures_block_generic'] = '一般的なCIDRをブロックする必要がありますか？ （他のオプションに固有ではないもの）。';
$CIDRAM['lang']['config_signatures_block_proxies'] = 'プロキシサービスからのCIDRをブロックする必要がありますか？ 匿名プロキシサービスが必要な場合は、これをfalseに設定する必要があります。ない場合は、セキュリティを向上させるために、これをtrueに設定する必要があります。';
$CIDRAM['lang']['config_signatures_block_spam'] = 'スパムのため、CIDRをブロックする必要がありますか？ 問題がある場合を除き、一般的には、これをtrueに設定する必要があります。';
$CIDRAM['lang']['config_signatures_default_tracktime'] = 'モジュールによって禁止されているIPを追跡する秒数。 Default（デフォルト設定） = ６０４８００（１週間）。';
$CIDRAM['lang']['config_signatures_infraction_limit'] = 'IPがIPトラッキングによって禁止される前に発生することが、許される違反の最大数。 Default（デフォルト設定） = １０。';
$CIDRAM['lang']['config_signatures_ipv4'] = 'IPv4のシグネチャファイルのリスト（CIDRAMは、これを使用します）。これは、カンマで区切られています。';
$CIDRAM['lang']['config_signatures_ipv6'] = 'IPv6のシグネチャファイルのリスト（CIDRAMは、これを使用します）。これは、カンマで区切られています。';
$CIDRAM['lang']['config_signatures_modules'] = 'IPv4/IPv6シグネチャをチェックした後にロードするモジュールファイルのリスト。これは、カンマで区切られています。';
$CIDRAM['lang']['config_signatures_track_mode'] = '違反はいつカウントされるべきですか？ False = IPがモジュールによってブロックされている場合。 True = なんでもの理由でIPがブロックされた場合。';
$CIDRAM['lang']['config_template_data_css_url'] = 'カスタムテーマのCSSファイルURL。';
$CIDRAM['lang']['field_blocked'] = 'ブロックされましたか？';
$CIDRAM['lang']['field_component'] = 'コンポーネント';
$CIDRAM['lang']['field_create_new_account'] = '新しいアカウントを作成する';
$CIDRAM['lang']['field_delete_account'] = 'アカウントを削除する';
$CIDRAM['lang']['field_delete_file'] = '削除';
$CIDRAM['lang']['field_download_file'] = 'ダウンロード';
$CIDRAM['lang']['field_edit_file'] = '編集';
$CIDRAM['lang']['field_file'] = 'ファイル';
$CIDRAM['lang']['field_filename'] = 'ファイル名： ';
$CIDRAM['lang']['field_filetype_directory'] = 'ディレクトリ';
$CIDRAM['lang']['field_filetype_info'] = '{EXT}ファイル';
$CIDRAM['lang']['field_filetype_unknown'] = '不明です';
$CIDRAM['lang']['field_install'] = 'インストール';
$CIDRAM['lang']['field_ip_address'] = 'IPアドレス';
$CIDRAM['lang']['field_latest_version'] = '最新バージョン';
$CIDRAM['lang']['field_log_in'] = 'ログイン';
$CIDRAM['lang']['field_new_name'] = '新しい名前：';
$CIDRAM['lang']['field_ok'] = 'OK';
$CIDRAM['lang']['field_options'] = 'オプション';
$CIDRAM['lang']['field_password'] = 'パスワード';
$CIDRAM['lang']['field_permissions'] = 'パーミッション';
$CIDRAM['lang']['field_range'] = '範囲 （最初 – 最後）';
$CIDRAM['lang']['field_rename_file'] = '名前を変更する';
$CIDRAM['lang']['field_reset'] = 'リセット';
$CIDRAM['lang']['field_set_new_password'] = '新しいパスワードを設定します';
$CIDRAM['lang']['field_size'] = '合計サイズ： ';
$CIDRAM['lang']['field_size_bytes'] = 'バイト';
$CIDRAM['lang']['field_size_GB'] = 'GB';
$CIDRAM['lang']['field_size_KB'] = 'KB';
$CIDRAM['lang']['field_size_MB'] = 'MB';
$CIDRAM['lang']['field_size_TB'] = 'TB';
$CIDRAM['lang']['field_status'] = '状態';
$CIDRAM['lang']['field_uninstall'] = 'アンインストール';
$CIDRAM['lang']['field_update'] = 'アップデート';
$CIDRAM['lang']['field_upload_file'] = '新しいファイルをアップロードする';
$CIDRAM['lang']['field_username'] = 'ユーザー名';
$CIDRAM['lang']['field_your_version'] = 'お使いのバージョン';
$CIDRAM['lang']['header_login'] = '継続するには、ログインしてください。';
$CIDRAM['lang']['link_accounts'] = 'アカウント';
$CIDRAM['lang']['link_cidr_calc'] = 'CIDR計算機';
$CIDRAM['lang']['link_config'] = 'コンフィギュレーション';
$CIDRAM['lang']['link_documentation'] = 'ドキュメンテーション';
$CIDRAM['lang']['link_file_manager'] = 'ファイル・マネージャー';
$CIDRAM['lang']['link_home'] = 'ホーム';
$CIDRAM['lang']['link_ip_test'] = 'IPテスト';
$CIDRAM['lang']['link_logs'] = 'ロゴス';
$CIDRAM['lang']['link_updates'] = 'アップデート';
$CIDRAM['lang']['logs_logfile_doesnt_exist'] = '選択したログは存在しません！';
$CIDRAM['lang']['logs_no_logfiles_available'] = 'いいえログが利用可能。';
$CIDRAM['lang']['logs_no_logfile_selected'] = 'ログが選択されていません。';
$CIDRAM['lang']['max_login_attempts_exceeded'] = 'ログイン試行の最大回数を超えました；アクセス拒否。';
$CIDRAM['lang']['response_accounts_already_exists'] = 'そのアカウントはすでに存在します！';
$CIDRAM['lang']['response_accounts_created'] = 'アカウントを作成に成功しました！';
$CIDRAM['lang']['response_accounts_deleted'] = 'アカウントを削除が成功しました！';
$CIDRAM['lang']['response_accounts_doesnt_exist'] = 'そのアカウントは存在しません。';
$CIDRAM['lang']['response_accounts_password_updated'] = 'パスワードの更新が成功しました！';
$CIDRAM['lang']['response_component_successfully_installed'] = 'コンポーネントのインストールに成功しました。';
$CIDRAM['lang']['response_component_successfully_uninstalled'] = 'コンポーネントのアンインストールは成功しました。';
$CIDRAM['lang']['response_component_successfully_updated'] = 'コンポーネントのアップデートに成功しました！';
$CIDRAM['lang']['response_component_uninstall_error'] = 'コンポーネントのアンインストール中にエラーが発生しました。';
$CIDRAM['lang']['response_component_update_error'] = 'コンポーネントのアップデート中にエラーが発生しました。';
$CIDRAM['lang']['response_configuration_updated'] = 'コンフィギュレーションの更新が成功しました。';
$CIDRAM['lang']['response_delete_error'] = '削除に失敗しました！';
$CIDRAM['lang']['response_directory_deleted'] = 'ディレクトリが正常に削除されました！';
$CIDRAM['lang']['response_directory_renamed'] = 'ディレクトリの名前が変更されました！';
$CIDRAM['lang']['response_error'] = 'エラー';
$CIDRAM['lang']['response_file_deleted'] = 'ファイルを削除が成功しました！';
$CIDRAM['lang']['response_file_edited'] = 'ファイルは正常に変更されました！';
$CIDRAM['lang']['response_file_renamed'] = 'ファイルの名前が変更されました！';
$CIDRAM['lang']['response_file_uploaded'] = 'ファイルは正常にアップロードされました！';
$CIDRAM['lang']['response_login_invalid_password'] = 'ログイン失敗！無効なパスワード！';
$CIDRAM['lang']['response_login_invalid_username'] = 'ログイン失敗！ユーザー名は存在しません！';
$CIDRAM['lang']['response_login_password_field_empty'] = 'パスワード入力は空です！';
$CIDRAM['lang']['response_login_username_field_empty'] = 'ユーザー名入力は空です！';
$CIDRAM['lang']['response_no'] = 'いいえ';
$CIDRAM['lang']['response_rename_error'] = '名前を変更できませんでした！';
$CIDRAM['lang']['response_updates_already_up_to_date'] = 'すでに最新の状態です。';
$CIDRAM['lang']['response_updates_not_installed'] = 'コンポーネントのインストールされていません！';
$CIDRAM['lang']['response_updates_not_installed_php'] = 'コンポーネントのインストールされていません（PHP {V}が必要です）！';
$CIDRAM['lang']['response_updates_outdated'] = '時代遅れです！';
$CIDRAM['lang']['response_updates_outdated_manually'] = '時代遅れです（手動でアップデートしてください）！';
$CIDRAM['lang']['response_updates_outdated_php_version'] = '時代遅れです（PHP {V}が必要です）！';
$CIDRAM['lang']['response_updates_unable_to_determine'] = '決定することができません。';
$CIDRAM['lang']['response_upload_error'] = 'アップロードに失敗しました！';
$CIDRAM['lang']['response_yes'] = 'はい';
$CIDRAM['lang']['state_complete_access'] = '完全なアクセス';
$CIDRAM['lang']['state_component_is_active'] = 'コンポーネントがアクティブです。';
$CIDRAM['lang']['state_component_is_inactive'] = 'コンポーネントが非アクティブです。';
$CIDRAM['lang']['state_component_is_provisional'] = 'コンポーネントが暫定的です。';
$CIDRAM['lang']['state_default_password'] = '警告：デフォルトのパスワードを使用して！';
$CIDRAM['lang']['state_logged_in'] = 'ログインしています。';
$CIDRAM['lang']['state_logs_access_only'] = 'ログのみにアクセス';
$CIDRAM['lang']['state_password_not_valid'] = '警告：このアカウントには有効なパスワードを使用していません！';
$CIDRAM['lang']['switch-hide-non-outdated-set-false'] = '非時代遅れを隠さないで';
$CIDRAM['lang']['switch-hide-non-outdated-set-true'] = '非時代遅れを隠す';
$CIDRAM['lang']['switch-hide-unused-set-false'] = '未使用を隠さないで';
$CIDRAM['lang']['switch-hide-unused-set-true'] = '未使用を隠す';
$CIDRAM['lang']['tip_accounts'] = 'こんにちは、{username}。<br />アカウント・ページは、CIDRAMフロントエンドにアクセスできるユーザーを制御できます。';
$CIDRAM['lang']['tip_cidr_calc'] = 'こんにちは、{username}。<br />CIDR計算機では、IPアドレスがどのCIDRに属しているかを計算できます。';
$CIDRAM['lang']['tip_config'] = 'こんにちは、{username}。<br />コンフィグレーション・ページは、フロントエンドからCIDRAMの設定を変更することができます。';
$CIDRAM['lang']['tip_donate'] = 'CIDRAMは無料で提供されています、しかし、あなたがしたい場合、寄付ボタンをクリックすると、プロジェクトに寄付することができます。';
$CIDRAM['lang']['tip_enter_ips_here'] = 'ここにIPを入力してください。';
$CIDRAM['lang']['tip_enter_ip_here'] = 'ここにIPを入力してください。';
$CIDRAM['lang']['tip_file_manager'] = 'こんにちは、{username}。<br />ファイル・マネージャを使用する、ファイルを削除、編集、アップロード、ダウンロードができます。慎重に使用する（これを使って、インストールを壊すことができます）。';
$CIDRAM['lang']['tip_home'] = 'こんにちは、{username}。<br />これはCIDRAMフロントエンドのホームページです。続行するには、左側のナビゲーションメニューからリンクを選択します。';
$CIDRAM['lang']['tip_ip_test'] = 'こんにちは、{username}。<br />IPテスト・ページは、IPアドレスがブロックされているかどうかをテストできます。';
$CIDRAM['lang']['tip_login'] = 'デフォルト・ユーザ名： <span class="txtRd">admin</span> – デフォルト・パスワード： <span class="txtRd">password</span>';
$CIDRAM['lang']['tip_logs'] = 'こんにちは、{username}。<br />そのログの内容を表示するために、次のリストからログを選択します。';
$CIDRAM['lang']['tip_see_the_documentation'] = '設定ディレクティブの詳細については、<a href="https://github.com/Maikuolan/CIDRAM/blob/master/_docs/readme.ja.md#SECTION6">ドキュメント</a>を参照してください。';
$CIDRAM['lang']['tip_updates'] = 'こんにちは、{username}。<br />アップデート・ページは、CIDRAMのさまざまなコンポーネントはインストール、アンインストール、更新が可能です（コアパッケージ、シグネチャ、L10Nファイル、等）。';
$CIDRAM['lang']['title_accounts'] = 'CIDRAM – アカウント';
$CIDRAM['lang']['title_cidr_calc'] = 'CIDRAM – CIDR計算機';
$CIDRAM['lang']['title_config'] = 'CIDRAM – コンフィギュレーション';
$CIDRAM['lang']['title_file_manager'] = 'CIDRAM – ファイル・マネージャー';
$CIDRAM['lang']['title_home'] = 'CIDRAM – ホーム';
$CIDRAM['lang']['title_ip_test'] = 'CIDRAM – IPテスト';
$CIDRAM['lang']['title_login'] = 'CIDRAM – ログイン';
$CIDRAM['lang']['title_logs'] = 'CIDRAM – ロゴス';
$CIDRAM['lang']['title_updates'] = 'CIDRAM – アップデート';

$CIDRAM['lang']['info_some_useful_links'] = '役に立つリンク：<ul>
            <li><a href="https://github.com/Maikuolan/CIDRAM/issues">CIDRAM Issues @ GitHub</a> – CIDRAMの問題ページ（サポート、援助、など）。</li>
            <li><a href="http://www.spambotsecurity.com/forum/viewforum.php?f=61">CIDRAM @ Spambot Security</a> – CIDRAMのディスカッションフォーラム（サポート、援助、など）。</li>
            <li><a href="https://wordpress.org/plugins/cidram/">CIDRAM @ Wordpress.org</a> – CIDRAMのWordpressプラグイン。</li>
            <li><a href="https://sourceforge.net/projects/cidram/">CIDRAM @ SourceForge</a> – CIDRAMの代替ダウンロードミラー。</li>
            <li><a href="https://websectools.com/">WebSecTools.com</a> – ウェブサイトを保護するための簡単なウェブマスターツールのコレクション。</li>
            <li><a href="https://macmathan.info/blocklists">MacMathan.info Range Blocks</a> – 不要な国があなたのウェブサイトにアクセスするのをブロックのために、CIDRAMに追加できるオプションの範囲ブロックが含まれています。</li>
            <li><a href="https://www.facebook.com/groups/2204685680/">International PHP Group @ Facebook</a> – PHP学習リソースとディスカッション。</li>
            <li><a href="https://wwphp-fb.github.io/">International PHP Group @ GitHub</a> – PHP学習リソースとディスカッション。</li>
            <li><a href="http://bgp.he.net/">Hurricane Electric BGP Toolkit</a> – ASNからCIDRを取得する、ASN関係を決定する、ネットワーク名に基づいてASNを検出する、等。</li>
            <li><a href="https://www.stopforumspam.com/forum/">Forum @ Stop Forum Spam</a> – フォーラムスパムの停止に関する便利なディスカッションフォーラム。</li>
            <li><a href="https://www.stopforumspam.com/aggregate">IP Aggregator @ Stop Forum Spam</a> – IPv4 IPのための有用な集約ツール。</li>
            <li><a href="https://radar.qrator.net/">Radar by Qrator</a> – ASNの接続性をチェックするのに便利なツール；ASNに関するその他の様々な情報。</li>
            <li><a href="http://www.ipdeny.com/ipblocks/">IPdeny IP country blocks</a> – 国全体のシグネチャを生成するための素晴らしい、正確なサービス。</li>
            <li><a href="https://www.google.com/transparencyreport/safebrowsing/malware/">Google Malware Dashboard</a> – ASNのマルウェア感染率に関するレポートを表示します。</li>
            <li><a href="https://www.spamhaus.org/statistics/botnet-asn/">The Spamhaus Project</a> – ASNのボットネット感染率に関するレポートを表示します。</li>
            <li><a href="http://www.abuseat.org/asn.html">Abuseat.org\'s Composite Blocking List</a> – ASNのボットネット感染率に関するレポートを表示します。</li>
            <li><a href="https://abuseipdb.com/">AbuseIPDB</a> – 既知危険なIPアドレスのデータベースを維持します；IPアドレスを確認と報告するためのAPIを提供します。</li>
            <li><a href="https://www.megarbl.net/index.php">MegaRBL.net</a> – 既知のスパマーリストを維持する；IP/ASNスパム活動のチェックに役立ちます。</li>
        </ul>';
