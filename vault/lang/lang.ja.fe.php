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
 * This file: Japanese language data for the front-end (last modified: 2016.11.18).
 */

/** Prevents execution from outside of CIDRAM. */
if (!defined('CIDRAM')) {
    die('[CIDRAM] This should not be accessed directly.');
}

$CIDRAM['lang']['bNav_home_logout'] = '<a href="?">ホーム</a> | <a href="?cidram-page=logout">ログアウト</a>';
$CIDRAM['lang']['bNav_logout'] = '<a href="?cidram-page=logout">ログアウト</a>';
$CIDRAM['lang']['config_general_disable_cli'] = 'CLIモードを無効にするか？';
$CIDRAM['lang']['config_general_disable_frontend'] = 'フロントエンドへのアクセスを無効にするか？';
$CIDRAM['lang']['config_general_emailaddr'] = 'サポートのためのEメールアドレス。';
$CIDRAM['lang']['config_general_forbid_on_block'] = '何ヘッダー使用する必要がありますか（要求をブロックしたとき）？';
$CIDRAM['lang']['config_general_ipaddr'] = '接続要求のIPアドレスをどこで見つけるべきかについて（Cloudflareのようなサービスに対して有効）。';
$CIDRAM['lang']['config_general_lang'] = 'CIDRAMのデフォルト言語を設定します。';
$CIDRAM['lang']['config_general_logfile'] = 'アクセス試行阻止の記録、人間によって読み取り可能。ファイル名指定するか、無効にしたい場合は空白のままにして下さい。';
$CIDRAM['lang']['config_general_logfileApache'] = 'アクセス試行阻止の記録、Apacheスタイル。ファイル名指定するか、無効にしたい場合は空白のままにして下さい。';
$CIDRAM['lang']['config_general_logfileSerialized'] = 'アクセス試行阻止の記録、シリアル化されました。ファイル名指定するか、無効にしたい場合は空白のままにして下さい。';
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
$CIDRAM['lang']['config_signatures_ipv4'] = 'IPv4のシグネチャファイルのリスト（CIDRAMは、これを使用します）。これは、カンマで区切られています。';
$CIDRAM['lang']['config_signatures_ipv6'] = 'IPv6のシグネチャファイルのリスト（CIDRAMは、これを使用します）。これは、カンマで区切られています。';
$CIDRAM['lang']['config_template_data_css_url'] = 'カスタムテーマのCSSファイルURL。';
$CIDRAM['lang']['field_blocked'] = 'ブロックされましたか？';
$CIDRAM['lang']['field_component'] = 'コンポーネント';
$CIDRAM['lang']['field_create_new_account'] = '新しいアカウントを作成する';
$CIDRAM['lang']['field_delete_account'] = 'アカウントを削除する';
$CIDRAM['lang']['field_filename'] = 'ファイル名： ';
$CIDRAM['lang']['field_install'] = 'インストール';
$CIDRAM['lang']['field_ip_address'] = 'IPアドレス';
$CIDRAM['lang']['field_latest_version'] = '最新バージョン';
$CIDRAM['lang']['field_log_in'] = 'ログイン';
$CIDRAM['lang']['field_ok'] = 'OK';
$CIDRAM['lang']['field_options'] = 'オプション';
$CIDRAM['lang']['field_password'] = 'パスワード';
$CIDRAM['lang']['field_permissions'] = 'パーミッション';
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
$CIDRAM['lang']['field_username'] = 'ユーザー名';
$CIDRAM['lang']['field_your_version'] = 'お使いのバージョン';
$CIDRAM['lang']['header_login'] = '継続するには、ログインしてください。';
$CIDRAM['lang']['link_accounts'] = 'アカウント';
$CIDRAM['lang']['link_config'] = 'コンフィギュレーション';
$CIDRAM['lang']['link_documentation'] = 'ドキュメンテーション';
$CIDRAM['lang']['link_home'] = 'ホーム';
$CIDRAM['lang']['link_ip_test'] = 'IPテスト';
$CIDRAM['lang']['link_logs'] = 'ロゴス';
$CIDRAM['lang']['link_updates'] = 'アップデート';
$CIDRAM['lang']['logs_logfile_doesnt_exist'] = '選択したログは存在しません！';
$CIDRAM['lang']['logs_no_logfiles_available'] = 'いいえログが利用可能。';
$CIDRAM['lang']['logs_no_logfile_selected'] = 'ログが選択されていません。';
$CIDRAM['lang']['response_accounts_already_exists'] = 'そのアカウントはすでに存在します！';
$CIDRAM['lang']['response_accounts_created'] = 'アカウントの作成に成功しました！';
$CIDRAM['lang']['response_accounts_deleted'] = 'アカウントの削除が成功しました！';
$CIDRAM['lang']['response_accounts_doesnt_exist'] = 'そのアカウントは存在しません。';
$CIDRAM['lang']['response_accounts_password_updated'] = 'パスワードの更新が成功しました！';
$CIDRAM['lang']['response_component_successfully_installed'] = 'コンポーネントのインストールに成功しました。';
$CIDRAM['lang']['response_component_successfully_uninstalled'] = 'コンポーネントのアンインストールは成功しました。';
$CIDRAM['lang']['response_component_successfully_updated'] = 'コンポーネントのアップデートに成功しました！';
$CIDRAM['lang']['response_component_uninstall_error'] = 'コンポーネントのアンインストール中にエラーが発生しました。';
$CIDRAM['lang']['response_component_update_error'] = 'コンポーネントのアップデート中にエラーが発生しました。';
$CIDRAM['lang']['response_configuration_updated'] = 'コンフィギュレーションの更新が成功しました。';
$CIDRAM['lang']['response_error'] = 'エラー';
$CIDRAM['lang']['response_login_invalid_password'] = 'ログイン失敗！無効なパスワード！';
$CIDRAM['lang']['response_login_invalid_username'] = 'ログイン失敗！ユーザー名は存在しません！';
$CIDRAM['lang']['response_login_password_field_empty'] = 'パスワード入力は空です！';
$CIDRAM['lang']['response_login_username_field_empty'] = 'ユーザー名入力は空です！';
$CIDRAM['lang']['response_no'] = 'いいえ';
$CIDRAM['lang']['response_updates_already_up_to_date'] = 'すでに最新の状態です。';
$CIDRAM['lang']['response_updates_not_installed'] = 'コンポーネントのインストールされていません！';
$CIDRAM['lang']['response_updates_outdated'] = '時代遅れです！';
$CIDRAM['lang']['response_updates_outdated_manually'] = '時代遅れです（手動でアップデートしてください）！';
$CIDRAM['lang']['response_updates_unable_to_determine'] = '決定することができません。';
$CIDRAM['lang']['response_yes'] = 'はい';
$CIDRAM['lang']['state_complete_access'] = '完全なアクセス';
$CIDRAM['lang']['state_component_is_active'] = 'コンポーネントがアクティブです。';
$CIDRAM['lang']['state_component_is_inactive'] = 'コンポーネントが非アクティブです。';
$CIDRAM['lang']['state_component_is_provisional'] = 'コンポーネントが暫定的です。';
$CIDRAM['lang']['state_default_password'] = '警告：デフォルトのパスワードを使用して！';
$CIDRAM['lang']['state_logged_in'] = 'ログインしています';
$CIDRAM['lang']['state_logs_access_only'] = 'ログのみにアクセス';
$CIDRAM['lang']['state_password_not_valid'] = '警告：このアカウントには有効なパスワードを使用していません！';
$CIDRAM['lang']['switch-hide-non-outdated-set-false'] = '非時代遅れを隠さないで';
$CIDRAM['lang']['switch-hide-non-outdated-set-true'] = '非時代遅れを隠す';
$CIDRAM['lang']['switch-hide-unused-set-false'] = '未使用を隠さないで';
$CIDRAM['lang']['switch-hide-unused-set-true'] = '未使用を隠す';
$CIDRAM['lang']['tip_accounts'] = 'こんにちは、{username}。<br />アカウント・ページは、CIDRAMフロントエンドにアクセスできるユーザーを制御できます。';
$CIDRAM['lang']['tip_config'] = 'こんにちは、{username}。<br />コンフィグレーション・ページは、フロントエンドからCIDRAMの設定を変更することができます。';
$CIDRAM['lang']['tip_enter_ips_here'] = 'ここにIPアドレスを入力してください。';
$CIDRAM['lang']['tip_home'] = 'こんにちは、{username}。<br />これはCIDRAMフロントエンドのホームページです。続行するには、左側のナビゲーションメニューからリンクを選択します。';
$CIDRAM['lang']['tip_ip_test'] = 'こんにちは、{username}。<br />IPテスト・ページは、IPアドレスがブロックされているかどうかをテストできます。';
$CIDRAM['lang']['tip_login'] = 'デフォルト・ユーザ名： <span class="txtRd">admin</span> – デフォルト・パスワード： <span class="txtRd">password</span>';
$CIDRAM['lang']['tip_logs'] = 'こんにちは、{username}。<br />そのログの内容を表示するために、次のリストからログを選択します。';
$CIDRAM['lang']['tip_see_the_documentation'] = '設定ディレクティブの詳細については、<a href="https://github.com/Maikuolan/CIDRAM/blob/master/_docs/readme.ja.md#SECTION6">ドキュメント</a>を参照してください。';
$CIDRAM['lang']['tip_updates'] = 'こんにちは、{username}。<br />アップデート・ページは、CIDRAMのさまざまなコンポーネントはインストール、アンインストール、更新が可能です（コアパッケージ、署名、L10Nファイル、等）。';
$CIDRAM['lang']['title_accounts'] = 'CIDRAM – アカウント';
$CIDRAM['lang']['title_config'] = 'CIDRAM – コンフィギュレーション';
$CIDRAM['lang']['title_home'] = 'CIDRAM – ホーム';
$CIDRAM['lang']['title_ip_test'] = 'CIDRAM – IPテスト';
$CIDRAM['lang']['title_login'] = 'CIDRAM – ログイン';
$CIDRAM['lang']['title_logs'] = 'CIDRAM – ロゴス';
$CIDRAM['lang']['title_updates'] = 'CIDRAM – アップデート';
