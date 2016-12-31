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
 * This file: German language data for the front-end (last modified: 2016.12.31).
 */

/** Prevents execution from outside of CIDRAM. */
if (!defined('CIDRAM')) {
    die('[CIDRAM] This should not be accessed directly.');
}

$CIDRAM['lang']['bNav_home_logout'] = '<a href="?">Startseite</a> | <a href="?cidram-page=logout">Ausloggen</a>';
$CIDRAM['lang']['bNav_logout'] = '<a href="?cidram-page=logout">Ausloggen</a>';
$CIDRAM['lang']['config_general_disable_cli'] = 'CLI-Modus deaktivieren?';
$CIDRAM['lang']['config_general_disable_frontend'] = 'Front-End-Access deaktivieren?';
$CIDRAM['lang']['config_general_emailaddr'] = 'E-Mail-Adresse für die Unterstützung.';
$CIDRAM['lang']['config_general_forbid_on_block'] = 'Welche Header sollte CIDRAM reagieren mit, wenn Anfragen blockiert?';
$CIDRAM['lang']['config_general_FrontEndLog'] = 'Datei für die Protokollierung von Front-End Einloggen-Versuchen. Geben Sie einen Dateinamen an oder lassen Sie die Option zum Deaktivieren leer.';
$CIDRAM['lang']['config_general_ipaddr'] = 'Ort der IP-Adresse der aktuellen Verbindung im gesamten Datenstrom (nützlich für Cloud-Services).';
$CIDRAM['lang']['config_general_lang'] = 'Gibt die Standardsprache für CIDRAM an.';
$CIDRAM['lang']['config_general_logfile'] = 'Name einer Datei für Menschen lesbar zu protokollieren alle blockierten Zugriffsversuche. Geben Sie einen Dateinamen an oder lassen Sie die Option zum Deaktivieren leer.';
$CIDRAM['lang']['config_general_logfileApache'] = 'Name einer Apache-Stil-Datei zu protokollieren alle blockierten Zugriffsversuche. Geben Sie einen Dateinamen an oder lassen Sie die Option zum Deaktivieren leer.';
$CIDRAM['lang']['config_general_logfileSerialized'] = 'Name einer Datei zu protokollieren alle blockierten Zugriffsversuche (Format ist serialisiert). Geben Sie einen Dateinamen an oder lassen Sie die Option zum Deaktivieren leer.';
$CIDRAM['lang']['config_general_max_login_attempts'] = 'Maximale Anzahl der Versucht zu einloggen.';
$CIDRAM['lang']['config_general_silent_mode'] = 'Anstatt die "Zugriff verweigert", sollte CIDRAM leise blockiert Zugriffsversuche umleiten? Wenn ja, geben Sie den Speicherort auf den blockierten Zugriffsversuche umleiten. Wenn nein, diese Variable leer lassen.';
$CIDRAM['lang']['config_general_timeOffset'] = 'Zeitzonenversatz in Minuten.';
$CIDRAM['lang']['config_recaptcha_expiry'] = 'Anzahl der Stunden an die sich reCAPTCHA-Instanzen erinnern sollten.';
$CIDRAM['lang']['config_recaptcha_lockip'] = 'Sperren Sie reCAPTCHA auf IPs?';
$CIDRAM['lang']['config_recaptcha_lockuser'] = 'Sperren Sie reCAPTCHA auf Benutzer?';
$CIDRAM['lang']['config_recaptcha_logfile'] = 'Protokollieren Sie alle reCAPTCHA versucht? Geben Sie einen Dateinamen an oder lassen Sie die Option zum Deaktivieren leer.';
$CIDRAM['lang']['config_recaptcha_secret'] = 'Dieser Wert sollte dem "secret key" für Ihre reCAPTCHA entsprechen, sich innerhalb des reCAPTCHA Dashboard befindet.';
$CIDRAM['lang']['config_recaptcha_sitekey'] = 'Dieser Wert sollte dem "site key" für Ihre reCAPTCHA entsprechen, sich innerhalb des reCAPTCHA Dashboard befindet.';
$CIDRAM['lang']['config_recaptcha_usemode'] = 'Dies definiert wie CIDRAM sollte reCAPTCHA benutzen (siehe Dokumentation).';
$CIDRAM['lang']['config_signatures_block_bogons'] = 'Blockieren Sie Bogon/Martian CIDRs? Wenn Sie Verbindungen zu Ihrer Website von localhost, von Ihrem LAN, oder von innerhalb Ihres lokalen Netzwerks erwarten, diese Richtlinie auf false sollte gesetzt werden. Wenn Sie diese Verbindungen nicht erwarten, dies auf true sollte gesetzt werden.';
$CIDRAM['lang']['config_signatures_block_cloud'] = 'Blockieren Sie CIDRs identifiziert als zu Web-Hosting/Cloud-Services gehören? Wenn Sie einen API-Dienst von Ihrer Website aus betreiben, oder wenn Sie erwarten dass andere Websites eine Verbindung zu Ihrer Website herstellen, dies auf false sollte gesetzt werden. Wenn Sie nicht, dann, dies auf true sollte gesetzt werden.';
$CIDRAM['lang']['config_signatures_block_generic'] = 'Blockieren Sie CIDRs allgemein empfohlen für eine schwarze Liste? Dies gilt für alle Signaturen, die nicht als Teil einer der anderen spezifischen Signaturkategorien markiert sind.';
$CIDRAM['lang']['config_signatures_block_proxies'] = 'Blockieren Sie CIDRs identifiziert als zu Proxy-Dienste gehören? Wenn Sie benötigen dass Benutzer auf Ihre Website von anonymen Proxy-Diensten zugreifen können, dies auf false sollte gesetzt werden. Andernfalls, Wenn Sie anonyme Proxies nicht benötigen, diese Richtlinie auf true sollte gesetzt werden, als Mittel zur Verbesserung der Sicherheit.';
$CIDRAM['lang']['config_signatures_block_spam'] = 'Blockieren Sie CIDRs identifiziert als ein hohem Risiko für Spam? Solange Sie keine Probleme haben während Sie dies tun, allgemein, dies immer auf true sollte gesetzt sein.';
$CIDRAM['lang']['config_signatures_ipv4'] = 'Eine Liste der IPv4-Signaturdateien dass CIDRAM zu verarbeiten soll, durch Kommas begrenzt.';
$CIDRAM['lang']['config_signatures_ipv6'] = 'Eine Liste der IPv6-Signaturdateien dass CIDRAM zu verarbeiten soll, durch Kommas begrenzt.';
$CIDRAM['lang']['config_signatures_modules'] = 'Eine Liste der Moduldateien zu laden nach der Prüfung der IPv4/IPv6-Signaturen, durch Kommas begrenzt.';
$CIDRAM['lang']['config_template_data_css_url'] = 'CSS-Datei URL für benutzerdefinierte Themen.';
$CIDRAM['lang']['field_blocked'] = 'Blockiert';
$CIDRAM['lang']['field_component'] = 'Komponente';
$CIDRAM['lang']['field_create_new_account'] = 'Neuen Konto erstellen';
$CIDRAM['lang']['field_delete_account'] = 'Konto löschen';
$CIDRAM['lang']['field_delete_file'] = 'Löschen';
$CIDRAM['lang']['field_download_file'] = 'Herunterladen';
$CIDRAM['lang']['field_edit_file'] = 'Bearbeiten';
$CIDRAM['lang']['field_file'] = 'Datei';
$CIDRAM['lang']['field_filename'] = 'Dateiname: ';
$CIDRAM['lang']['field_filetype_directory'] = 'Verzeichnis';
$CIDRAM['lang']['field_filetype_info'] = '{EXT}-Datei';
$CIDRAM['lang']['field_filetype_unknown'] = 'Unbekannt';
$CIDRAM['lang']['field_install'] = 'Installieren';
$CIDRAM['lang']['field_ip_address'] = 'IP-Adresse';
$CIDRAM['lang']['field_latest_version'] = 'Letzte Version';
$CIDRAM['lang']['field_log_in'] = 'Einloggen';
$CIDRAM['lang']['field_new_name'] = 'Neuer Name:';
$CIDRAM['lang']['field_ok'] = 'OK';
$CIDRAM['lang']['field_options'] = 'Optionen';
$CIDRAM['lang']['field_password'] = 'Passwort';
$CIDRAM['lang']['field_permissions'] = 'Berechtigungen';
$CIDRAM['lang']['field_rename_file'] = 'Umbenennen';
$CIDRAM['lang']['field_reset'] = 'Zurücksetzen';
$CIDRAM['lang']['field_set_new_password'] = 'Neues Passwort eingeben';
$CIDRAM['lang']['field_size'] = 'Gesamtgröße: ';
$CIDRAM['lang']['field_size_bytes'] = 'Byte';
$CIDRAM['lang']['field_size_GB'] = 'GB';
$CIDRAM['lang']['field_size_KB'] = 'KB';
$CIDRAM['lang']['field_size_MB'] = 'MB';
$CIDRAM['lang']['field_size_TB'] = 'TB';
$CIDRAM['lang']['field_status'] = 'Status';
$CIDRAM['lang']['field_uninstall'] = 'Deinstallieren';
$CIDRAM['lang']['field_update'] = 'Aktualisieren';
$CIDRAM['lang']['field_upload_file'] = 'Neue Datei hochladen';
$CIDRAM['lang']['field_username'] = 'Benutzername';
$CIDRAM['lang']['field_your_version'] = 'Ihre Version';
$CIDRAM['lang']['header_login'] = 'Bitte einloggen zum Fortfahren.';
$CIDRAM['lang']['link_accounts'] = 'Konten';
$CIDRAM['lang']['link_config'] = 'Konfiguration';
$CIDRAM['lang']['link_documentation'] = 'Dokumentation';
$CIDRAM['lang']['link_file_manager'] = 'Dateimanager';
$CIDRAM['lang']['link_home'] = 'Startseite';
$CIDRAM['lang']['link_ip_test'] = 'IP-Test';
$CIDRAM['lang']['link_logs'] = 'Protokolldateien';
$CIDRAM['lang']['link_updates'] = 'Aktualisierungen';
$CIDRAM['lang']['logs_logfile_doesnt_exist'] = 'Ausgewählte Protokolldatei existiert nicht!';
$CIDRAM['lang']['logs_no_logfiles_available'] = 'Keine Protokolldateien vorhanden.';
$CIDRAM['lang']['logs_no_logfile_selected'] = 'Keine Protokolldatei ausgewählt.';
$CIDRAM['lang']['max_login_attempts_exceeded'] = 'Maximale Anzahl der Versucht zu einloggen überschritten; Zugriff verweigert.';
$CIDRAM['lang']['response_accounts_already_exists'] = 'Ein Konto mit diesem Benutzernamen ist bereits vorhanden!';
$CIDRAM['lang']['response_accounts_created'] = 'Konto erfolgreich erstellt!';
$CIDRAM['lang']['response_accounts_deleted'] = 'Konto erfolgreich gelöscht!';
$CIDRAM['lang']['response_accounts_doesnt_exist'] = 'Dieses Konto existiert nicht.';
$CIDRAM['lang']['response_accounts_password_updated'] = 'Passwort erfolgreich aktualisiert!';
$CIDRAM['lang']['response_component_successfully_installed'] = 'Komponente erfolgreich installiert.';
$CIDRAM['lang']['response_component_successfully_uninstalled'] = 'Komponente erfolgreich deinstalliert.';
$CIDRAM['lang']['response_component_successfully_updated'] = 'Komponente erfolgreich aktualisiert.';
$CIDRAM['lang']['response_component_uninstall_error'] = 'Beim Deinstallieren der Komponente ist ein Fehler aufgetreten.';
$CIDRAM['lang']['response_component_update_error'] = 'Beim Aktualisieren der Komponente ist ein Fehler aufgetreten.';
$CIDRAM['lang']['response_configuration_updated'] = 'Konfiguration erfolgreich aktualisiert.';
$CIDRAM['lang']['response_delete_error'] = 'Löschung-Fehler!';
$CIDRAM['lang']['response_directory_deleted'] = 'Verzeichnis erfolgreich gelöscht!';
$CIDRAM['lang']['response_directory_renamed'] = 'Verzeichnis erfolgreich umbenannt!';
$CIDRAM['lang']['response_error'] = 'Fehler';
$CIDRAM['lang']['response_file_deleted'] = 'Datei erfolgreich gelöscht!';
$CIDRAM['lang']['response_file_edited'] = 'Datei erfolgreich geändert!';
$CIDRAM['lang']['response_file_renamed'] = 'Datei erfolgreich umbenannt!';
$CIDRAM['lang']['response_file_uploaded'] = 'Datei erfolgreich hochgeladen!';
$CIDRAM['lang']['response_login_invalid_password'] = 'Einloggen-Fehler! Ungültiges Passwort!';
$CIDRAM['lang']['response_login_invalid_username'] = 'Einloggen-Fehler! Benutzername existiert nicht!';
$CIDRAM['lang']['response_login_password_field_empty'] = 'Passwort-Feld leer!';
$CIDRAM['lang']['response_login_username_field_empty'] = 'Benutzername-Feld leer!';
$CIDRAM['lang']['response_no'] = 'Nein';
$CIDRAM['lang']['response_rename_error'] = 'Umbenennung-Fehler!';
$CIDRAM['lang']['response_updates_already_up_to_date'] = 'Schon aktuell.';
$CIDRAM['lang']['response_updates_not_installed'] = 'Komponente nicht installiert!';
$CIDRAM['lang']['response_updates_not_installed_php'] = 'Komponente nicht installiert (erfordert PHP {V})!';
$CIDRAM['lang']['response_updates_outdated'] = 'Veraltet!';
$CIDRAM['lang']['response_updates_outdated_manually'] = 'Veraltet (bitte manuell aktualisieren)!';
$CIDRAM['lang']['response_updates_outdated_php_version'] = 'Veraltet (erfordert PHP {V})!';
$CIDRAM['lang']['response_updates_unable_to_determine'] = 'Kann nicht ermittelt werden.';
$CIDRAM['lang']['response_upload_error'] = 'Hochladen-Fehler!';
$CIDRAM['lang']['response_yes'] = 'Ja';
$CIDRAM['lang']['state_complete_access'] = 'Vollständiger Zugriff';
$CIDRAM['lang']['state_component_is_active'] = 'Komponente ist aktiv.';
$CIDRAM['lang']['state_component_is_inactive'] = 'Komponente ist inaktiv.';
$CIDRAM['lang']['state_component_is_provisional'] = 'Komponente ist vorläufig.';
$CIDRAM['lang']['state_default_password'] = 'Warnung: Verwendet das Standard-Passwort!';
$CIDRAM['lang']['state_logged_in'] = 'Eingeloggt.';
$CIDRAM['lang']['state_logs_access_only'] = 'Zugriff nur auf Protokolldateien';
$CIDRAM['lang']['state_password_not_valid'] = 'Warnung: Dieses Konto verwendet kein gültiges Passwort!';
$CIDRAM['lang']['switch-hide-non-outdated-set-false'] = 'Nicht verstecken nicht veraltet';
$CIDRAM['lang']['switch-hide-non-outdated-set-true'] = 'Verstecken nicht veraltet';
$CIDRAM['lang']['switch-hide-unused-set-false'] = 'Nicht verstecken unbenutzt';
$CIDRAM['lang']['switch-hide-unused-set-true'] = 'Verstecken unbenutzt';
$CIDRAM['lang']['tip_accounts'] = 'Hallo, {username}.<br />Das Kontenseite macht es möglich zu kontrollieren, wer kann Zugriff auf der CIDRAM Front-End haben.';
$CIDRAM['lang']['tip_config'] = 'Hallo, {username}.<br />Das Konfigurationsseite macht es möglich zu ändern das Konfiguration für CIDRAM von der Front-End.';
$CIDRAM['lang']['tip_donate'] = 'CIDRAM wird kostenlos angeboten, aber wenn Sie für das Projekt spenden möchten, können Sie dies tun indem Klicken Sie auf die Spenden-Schaltfläche.';
$CIDRAM['lang']['tip_enter_ips_here'] = 'Geben Sie hier IPs.';
$CIDRAM['lang']['tip_file_manager'] = 'Hallo, {username}.<br />Mit dem Dateimanager können Sie Dateien löschen, bearbeiten, hochladen und herunterladen. Mit Vorsicht verwenden (Können Sie Ihre Installation mit diesem brechen).';
$CIDRAM['lang']['tip_home'] = 'Hallo, {username}.<br />Dies ist die Homepage der CIDRAM Front-End. Wählen Sie einen Link aus dem Navigationsmenü auf der linken um fortzufahren.';
$CIDRAM['lang']['tip_ip_test'] = 'Hallo, {username}.<br />Das IP-Test-Seite macht es möglich zu prüfen ob IP-Adressen blockiert durch die aktuell installierten Signaturen sind.';
$CIDRAM['lang']['tip_login'] = 'Standard-Benutzername: <span class="txtRd">admin</span> – Standard-Passwort: <span class="txtRd">password</span>';
$CIDRAM['lang']['tip_logs'] = 'Hallo, {username}.<br />Wählen Sie eine Protokolldatei aus der folgenden Liste um den Inhalt dieser Protokolldatei anzuzeigen.';
$CIDRAM['lang']['tip_see_the_documentation'] = 'Siehe die <a href="https://github.com/Maikuolan/CIDRAM/blob/master/_docs/readme.de.md#SECTION6">Dokumentation</a> für Informationen über den verschiedenen Konfigurationseinstellungen und ihren Zwecken.';
$CIDRAM['lang']['tip_updates'] = 'Hallo, {username}.<br />Das Aktualisierungsseite macht es möglich für Sie zu installieren, zu deinstallieren und zu aktualisieren die verschiedenen Komponenten von CIDRAM (das Kernpaket, Signaturen, L10N-Dateien, u.s.w.).';
$CIDRAM['lang']['title_accounts'] = 'CIDRAM – Konten';
$CIDRAM['lang']['title_config'] = 'CIDRAM – Konfiguration';
$CIDRAM['lang']['title_file_manager'] = 'CIDRAM – Dateimanager';
$CIDRAM['lang']['title_home'] = 'CIDRAM – Startseite';
$CIDRAM['lang']['title_ip_test'] = 'CIDRAM – IP-Test';
$CIDRAM['lang']['title_login'] = 'CIDRAM – Einloggen';
$CIDRAM['lang']['title_logs'] = 'CIDRAM – Protokolldateien';
$CIDRAM['lang']['title_updates'] = 'CIDRAM – Aktualisierungen';

$CIDRAM['lang']['info_some_useful_links'] = 'Einige nützliche Links:<ul>
            <li><a href="https://github.com/Maikuolan/CIDRAM/issues">CIDRAM Issues @ GitHub</a> – Problemseite für CIDRAM (Unterstützung, u.s.w.).</li>
            <li><a href="http://www.spambotsecurity.com/forum/viewforum.php?f=61">CIDRAM @ Spambot Security</a> – Diskussionsforum für CIDRAM (Unterstützung, u.s.w.).</li>
            <li><a href="https://wordpress.org/plugins/cidram/">CIDRAM @ Wordpress.org</a> – Wordpress Plugin für CIDRAM.</li>
            <li><a href="https://sourceforge.net/projects/cidram/">CIDRAM @ SourceForge</a> – Alternative download spiegel für CIDRAM.</li>
            <li><a href="https://websectools.com/">WebSecTools.com</a> – Eine Sammlung von einfachen Webmaster-Tools, um Websites zu sichern.</li>
            <li><a href="https://macmathan.info/zbblock-range-blocks">MacMathan.info Range Blocks</a> – Enthält optionale Bereichsblöcke, die dem CIDRAM hinzugefügt werden können, um unerwünschte Länder von dem Zugriff auf Ihre Website zu blockieren.</li>
            <li><a href="https://www.facebook.com/groups/2204685680/">International PHP Group @ Facebook</a> – PHP Lernressourcen und Diskussion.</li>
            <li><a href="https://wwphp-fb.github.io/">International PHP Group @ GitHub</a> – PHP Lernressourcen und Diskussion.</li>
            <li><a href="http://bgp.he.net/">Hurricane Electric BGP Toolkit</a> – Erhalten Sie CIDRs von ASNs, bestimmen Sie ASN-Beziehungen, entdecken Sie ASNs basierend auf Netzwerknamen, usw.</li>
            <li><a href="https://www.stopforumspam.com/forum/">Forum @ Stop Forum Spam</a> – Nützliches Diskussionsforum zum Stoppen von Forumspam.</li>
            <li><a href="https://www.stopforumspam.com/aggregate">IP Aggregator @ Stop Forum Spam</a> – Nützliches Aggregationswerkzeug für IPv4-IPs.</li>
            <li><a href="https://radar.qrator.net/">Radar by Qrator</a> – Nützliches Werkzeug für die Überprüfung der Konnektivität von ASNs sowie für verschiedene andere Informationen über ASNs.</li>
            <li><a href="http://www.ipdeny.com/ipblocks/">IPdeny IP country blocks</a> – Ein fantastischer und genauer Service für die Erstellung landesweiter Signaturen.</li>
            <li><a href="https://www.google.com/transparencyreport/safebrowsing/malware/">Google Malware Dashboard</a> – Zeigt Berichte über Malware-Infektionsraten für ASNs.</li>
            <li><a href="https://www.spamhaus.org/statistics/botnet-asn/">The Spamhaus Project</a> – Zeigt Berichte über Botnet-Infektionsraten für ASNs.</li>
            <li><a href="http://www.abuseat.org/asn.html">Abuseat.org\'s Composite Blocking List</a> – Zeigt Berichte über Botnet-Infektionsraten für ASNs.</li>
            <li><a href="https://www.megarbl.net/index.php">MegaRBL.net</a> – Pflegt Listen von bekannten Spammern; Nützlich für die Überprüfung von IP/ASN-Spam-Aktivitäten.</li>
        </ul>';
