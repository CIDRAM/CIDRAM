<?php
/**
 * This file is a part of the CIDRAM package.
 * Homepage: https://cidram.github.io/
 *
 * CIDRAM COPYRIGHT 2016 and beyond by Caleb Mazalevskis (Maikuolan).
 *
 * License: GNU/GPLv2
 * @see LICENSE.txt
 *
 * This file: German language data for the front-end (last modified: 2018.03.03).
 */

/** Prevents execution from outside of CIDRAM. */
if (!defined('CIDRAM')) {
    die('[CIDRAM] This should not be accessed directly.');
}

$CIDRAM['lang']['Extended Description: Bypasses'] = 'Die Standard-Signatur-Bypass-Dateien, die normalerweise im Hauptpaket enthalten sind.';
$CIDRAM['lang']['Extended Description: CIDRAM'] = 'Das Hauptpaket (Abzüglich der Unterschriften, Dokumentation, und Konfiguration).';
$CIDRAM['lang']['Name: Bypasses'] = 'Standard-Signatur-Bypässe.';
$CIDRAM['lang']['Name: IPv4'] = 'IPv4 Signaturdatei (unerwünschte Cloud-Services und nicht-menschliche Endpunkte).';
$CIDRAM['lang']['Name: IPv4-Bogons'] = 'IPv4 Signaturdatei (Bogon/Marsmensch CIDRs).';
$CIDRAM['lang']['Name: IPv4-ISPs'] = 'IPv4 Signaturdatei (gefährliche und spam-anfällig ISPs).';
$CIDRAM['lang']['Name: IPv4-Other'] = 'IPv4 Signaturdatei (CIDRs für Proxies, VPNs und andere verschiedene unerwünschte Dienste).';
$CIDRAM['lang']['Name: IPv6'] = 'IPv6 Signaturdatei (unerwünschte Cloud-Services und nicht-menschliche Endpunkte).';
$CIDRAM['lang']['Name: IPv6-Bogons'] = 'IPv6 Signaturdatei (Bogon/Marsmensch CIDRs).';
$CIDRAM['lang']['Name: IPv6-ISPs'] = 'IPv6 Signaturdatei (gefährliche und spam-anfällig ISPs).';
$CIDRAM['lang']['Name: IPv6-Other'] = 'IPv6 Signaturdatei (CIDRs für Proxies, VPNs und andere verschiedene unerwünschte Dienste).';
$CIDRAM['lang']['bNav_home_logout'] = '<a href="?">Startseite</a> | <a href="?cidram-page=logout">Ausloggen</a>';
$CIDRAM['lang']['bNav_logout'] = '<a href="?cidram-page=logout">Ausloggen</a>';
$CIDRAM['lang']['config_general_FrontEndLog'] = 'Datei für die Protokollierung von Front-End Einloggen-Versuchen. Geben Sie einen Dateinamen an oder lassen Sie die Option zum Deaktivieren leer.';
$CIDRAM['lang']['config_general_allow_gethostbyaddr_lookup'] = 'gethostbyaddr-Suche erlauben, wenn UDP nicht verfügbar ist? True = Ja [Standardeinstellung]; False = Nein.';
$CIDRAM['lang']['config_general_ban_override'] = 'Überschreiben "forbid_on_block" Wenn "infraction_limit" überschritten wird? Beim überschreiben: Blockiert Anfragen geben eine leere Seite zurück (Template-Dateien werden nicht verwendet). 200 = Nicht überschreiben [Standardeinstellung]; 403 = Überschreiben mit "403 Forbidden"; 503 = Überschreiben mit "503 Service unavailable".';
$CIDRAM['lang']['config_general_default_algo'] = 'Definiert den Algorithmus für alle zukünftigen Passwörter und Sitzungen. Optionen: PASSWORD_DEFAULT (Standardeinstellung), PASSWORD_BCRYPT, PASSWORD_ARGON2I (erfordert PHP >= 7.2.0).';
$CIDRAM['lang']['config_general_default_dns'] = 'Eine durch Kommata getrennte Liste von DNS-Servern, die für Hostnamen-Lookups verwendet werden sollen. Standardeinstellung = "8.8.8.8,8.8.4.4" (Google DNS). ACHTUNG: Ändern Sie diesen Wert nur, wenn Sie wissen, was Sie tun!';
$CIDRAM['lang']['config_general_disable_cli'] = 'CLI-Modus deaktivieren? CLI-Modus ist standardmäßig aktiviert, kann aber manchmal bestimmte Test-Tools (PHPUnit zum Beispiel) und andere CLI-basierte Anwendungen beeinträchtigen. Wenn du den CLI-Modus nicht deaktiveren musst, solltest du diese Anweisung ignorieren. False = CLI-Modus aktivieren [Standardeinstellung]; True = CLI-Modus deaktivieren.';
$CIDRAM['lang']['config_general_disable_frontend'] = 'Front-End-Access deaktivieren? Front-End-Access kann machen CIDRAM einfacher zu handhaben, aber es kann auch ein potentielles Sicherheitsrisiko sein. Es wird empfohlen, wenn möglich, CIDRAM über die Back-End-Access zu verwalten, aber Front-End-Access vorgesehen ist, für wenn es nicht möglich ist. Halten Sie es deaktiviert außer wenn Sie es brauchen. False = Front-End-Access aktivieren; True = Front-End-Access deaktivieren [Standardeinstellung].';
$CIDRAM['lang']['config_general_disable_webfonts'] = 'Web-Fonts deaktivieren? True = Ja; False = Nein [Standardeinstellung].';
$CIDRAM['lang']['config_general_emailaddr'] = 'Wenn Sie möchten, können Sie hier eine E-Mail-Adresse angeben, geben auf den Benutzern wenn sie blockiert, für Unterstützung für den Fall dass sie ist blockiert versehentlich oder im fehler. WARNUNG: Jede E-Mail-Adresse die Sie hier angeben wird sicherlich durch Spambots erworben werden im Zuge ihrer Verwendung hier, und so, es wird dringend empfohlen, wenn Sie hier eine E-Mail-Adresse angeben, dass die E-Mail-Adresse die Sie hier angeben, eine Einwegadresse ist, und/oder eine Adresse die Sie nichts dagegen haben Spam (mit anderen Worten, möchten Sie wahrscheinlich nicht Ihre primären persönlichen oder primären geschäftlichen E-Mail-Adressen verwenden).';
$CIDRAM['lang']['config_general_emailaddr_display_style'] = 'Wie möchten Sie die E-Mail-Adresse für die Nutzer vorstellen?';
$CIDRAM['lang']['config_general_forbid_on_block'] = 'Welche Header sollte CIDRAM reagieren mit, wenn Anfragen blockiert?';
$CIDRAM['lang']['config_general_force_hostname_lookup'] = 'Erzwinge Hostnamen-Suche? True = Ja; False = Nein [Standardeinstellung]. Hostnamen-Suchen werden normalerweise auf einer "wie benötigt"-Basis durchgeführt, können jedoch für alle Anforderungen erzwungen werden. Dies kann nützlich sein, um detailliertere Informationen in der Protokolldateien bereitzustellen, aber auch kann sich leicht negativ auf die Performance auswirken.';
$CIDRAM['lang']['config_general_ipaddr'] = 'Ort der IP-Adresse der aktuellen Verbindung im gesamten Datenstrom (nützlich für Cloud-Services). Standardeinstellung = REMOTE_ADDR. ACHTUNG: Ändern Sie diesen Wert nur, wenn Sie wissen, was Sie tun!';
$CIDRAM['lang']['config_general_lang'] = 'Gibt die Standardsprache für CIDRAM an.';
$CIDRAM['lang']['config_general_log_banned_ips'] = 'Enthalten Sie blockierte Anfragen von verbotenen IPs in die Protokolldateien? True = Ja [Standardeinstellung]; False = Nein.';
$CIDRAM['lang']['config_general_logfile'] = 'Name einer Datei für Menschen lesbar zu protokollieren alle blockierten Zugriffsversuche. Geben Sie einen Dateinamen an oder lassen Sie die Option zum Deaktivieren leer.';
$CIDRAM['lang']['config_general_logfileApache'] = 'Name einer Apache-Stil-Datei zu protokollieren alle blockierten Zugriffsversuche. Geben Sie einen Dateinamen an oder lassen Sie die Option zum Deaktivieren leer.';
$CIDRAM['lang']['config_general_logfileSerialized'] = 'Name einer Datei zu protokollieren alle blockierten Zugriffsversuche (Format ist serialisiert). Geben Sie einen Dateinamen an oder lassen Sie die Option zum Deaktivieren leer.';
$CIDRAM['lang']['config_general_maintenance_mode'] = 'Wartungsmodus aktivieren? True = Ja; False = Nein [Standardeinstellung]. Deaktiviert alles andere als das Front-End. Manchmal nützlich für die Aktualisierung Ihrer CMS, Frameworks, usw.';
$CIDRAM['lang']['config_general_max_login_attempts'] = 'Maximale Anzahl der Versucht zu einloggen.';
$CIDRAM['lang']['config_general_numbers'] = 'Wie willst du Nummern anzeigen? Wählen Sie das Beispiel aus, das Ihnen am besten entspricht.';
$CIDRAM['lang']['config_general_protect_frontend'] = 'Gibt an, ob die Schutzmaßnahmen normalerweise vom CIDRAM bereitgestellten auf das Frontend angewendet werden sollen. True = Ja [Standardeinstellung]; False = Nein.';
$CIDRAM['lang']['config_general_search_engine_verification'] = 'Versuche, Anfragen von Suchmaschinen zu überprüfen? Die Überprüfung der Suchmaschinen sorgt dafür, dass sie nicht als Folge der Maximale Anzahl von Verstöße verboten werden (Verbot der Suchmaschinen von Ihrer Website wird in der Regel einen negativen Effekt auf Ihre Suchmaschinen-Ranking, SEO und u.s.w. haben). Wenn überprüft, wie pro normal, Suchmaschinen können blockiert werden, aber sie werden nicht verboten. Wenn nicht überprüft, es ist möglich, dass sie verboten als Folge der Überschreitung der Maximale Anzahl von Verstöße werden können. Zusätzlich, Suchmaschinen-Überprüfung bietet Schutz gegen gefälschte Suchmaschinen-Anfragen und gegen potenziell böswillige Entitäten, die sich als Suchmaschinen maskieren (solche Anfragen werden blockiert, wenn die Suchmaschinen-Überprüfung aktiviert ist). True = Suchmaschinen-Überprüfung aktivieren [Standardeinstellung]; False = Suchmaschinen-Überprüfung deaktivieren.';
$CIDRAM['lang']['config_general_silent_mode'] = 'Anstatt die "Zugriff verweigert", sollte CIDRAM leise blockiert Zugriffsversuche umleiten? Wenn ja, geben Sie den Speicherort auf den blockierten Zugriffsversuche umleiten. Wenn nein, diese Variable leer lassen.';
$CIDRAM['lang']['config_general_statistics'] = 'CIDRAM-Nutzungsstatistiken verfolgen? True = Ja; False = Nein [Standardeinstellung].';
$CIDRAM['lang']['config_general_timeFormat'] = 'Das Datumsformat verwendet von CIDRAM. Zusätzliche Optionen können auf Anfrage hinzugefügt werden.';
$CIDRAM['lang']['config_general_timeOffset'] = 'Zeitzonenversatz in Minuten.';
$CIDRAM['lang']['config_general_timezone'] = 'Ihre Zeitzone.';
$CIDRAM['lang']['config_general_truncate'] = 'Trunkate Protokolldateien, wenn sie eine bestimmte Größe erreichen? Wert ist die maximale Größe in B/KB/MB/GB/TB, die eine Protokolldatei wachsen kann, bevor sie trunkiert wird. Der Standardwert von 0KB deaktiviert die Trunkierung (Protokolldateien können unbegrenzt wachsen). Hinweis: Gilt für einzelne Protokolldateien! Die Größe der Protokolldateien gilt nicht als kollektiv.';
$CIDRAM['lang']['config_recaptcha_api'] = 'Welche API soll verwendet werden? V2 oder Invisible?';
$CIDRAM['lang']['config_recaptcha_expiry'] = 'Anzahl der Stunden an die sich reCAPTCHA-Instanzen erinnern sollten.';
$CIDRAM['lang']['config_recaptcha_lockip'] = 'Sperren Sie reCAPTCHA auf IPs?';
$CIDRAM['lang']['config_recaptcha_lockuser'] = 'Sperren Sie reCAPTCHA auf Benutzer?';
$CIDRAM['lang']['config_recaptcha_logfile'] = 'Protokollieren Sie alle reCAPTCHA versucht? Geben Sie einen Dateinamen an oder lassen Sie die Option zum Deaktivieren leer.';
$CIDRAM['lang']['config_recaptcha_secret'] = 'Dieser Wert sollte dem "secret key" für Ihre reCAPTCHA entsprechen, sich innerhalb des reCAPTCHA Dashboard befindet.';
$CIDRAM['lang']['config_recaptcha_signature_limit'] = 'Maximale Anzahl von Signaturen, die ausgelöst werden dürfen, wenn eine reCAPTCHA-Instanz angeboten werden soll. Standardeinstellung = 1. Wenn diese Anzahl für eine bestimmte Anfrage überschritten wird, wird keine reCAPTCHA-Instanz angeboten.';
$CIDRAM['lang']['config_recaptcha_sitekey'] = 'Dieser Wert sollte dem "site key" für Ihre reCAPTCHA entsprechen, sich innerhalb des reCAPTCHA Dashboard befindet.';
$CIDRAM['lang']['config_recaptcha_usemode'] = 'Dies definiert wie CIDRAM sollte reCAPTCHA benutzen (siehe Dokumentation).';
$CIDRAM['lang']['config_signatures_block_bogons'] = 'Blockieren Sie Bogon/Martian CIDRs? Wenn Sie Verbindungen zu Ihrer Website von localhost, von Ihrem LAN, oder von innerhalb Ihres lokalen Netzwerks erwarten, diese Richtlinie auf false sollte gesetzt werden. Wenn Sie diese Verbindungen nicht erwarten, dies auf true sollte gesetzt werden.';
$CIDRAM['lang']['config_signatures_block_cloud'] = 'Blockieren Sie CIDRs identifiziert als zu Web-Hosting/Cloud-Services gehören? Wenn Sie einen API-Dienst von Ihrer Website aus betreiben, oder wenn Sie erwarten dass andere Websites eine Verbindung zu Ihrer Website herstellen, dies auf false sollte gesetzt werden. Wenn Sie nicht, dann, dies auf true sollte gesetzt werden.';
$CIDRAM['lang']['config_signatures_block_generic'] = 'Blockieren Sie CIDRs allgemein empfohlen für eine schwarze Liste? Dies gilt für alle Signaturen, die nicht als Teil einer der anderen spezifischen Signaturkategorien markiert sind.';
$CIDRAM['lang']['config_signatures_block_proxies'] = 'Blockieren Sie CIDRs identifiziert als zu Proxy-Dienste gehören? Wenn Sie benötigen dass Benutzer auf Ihre Website von anonymen Proxy-Diensten zugreifen können, dies auf false sollte gesetzt werden. Andernfalls, Wenn Sie anonyme Proxies nicht benötigen, diese Richtlinie auf true sollte gesetzt werden, als Mittel zur Verbesserung der Sicherheit.';
$CIDRAM['lang']['config_signatures_block_spam'] = 'Blockieren Sie CIDRs identifiziert als ein hohem Risiko für Spam? Solange Sie keine Probleme haben während Sie dies tun, allgemein, dies immer auf true sollte gesetzt sein.';
$CIDRAM['lang']['config_signatures_default_tracktime'] = 'Wie viele Sekunden, um IPs von Modulen verboten zu verfolgen. Standardeinstellung = 604800 (1 Woche).';
$CIDRAM['lang']['config_signatures_infraction_limit'] = 'Maximale Anzahl von Verstöße, die eine IP zulassen darf, bevor sie durch IP-Tracking verboten ist. Standardeinstellung = 10.';
$CIDRAM['lang']['config_signatures_ipv4'] = 'Eine Liste der IPv4-Signaturdateien dass CIDRAM zu verarbeiten soll, durch Kommas begrenzt.';
$CIDRAM['lang']['config_signatures_ipv6'] = 'Eine Liste der IPv6-Signaturdateien dass CIDRAM zu verarbeiten soll, durch Kommas begrenzt.';
$CIDRAM['lang']['config_signatures_modules'] = 'Eine Liste der Moduldateien zu laden nach der Prüfung der IPv4/IPv6-Signaturen, durch Kommas begrenzt.';
$CIDRAM['lang']['config_signatures_track_mode'] = 'Wann sollten Verstöße gezählt werden? False = Wenn IPs von Modulen blockiert werden. True = Wenn IPs von irgendeinem Grund blockiert werden.';
$CIDRAM['lang']['config_template_data_Magnification'] = 'Schriftvergrößerung. Standardeinstellung = 1.';
$CIDRAM['lang']['config_template_data_css_url'] = 'CSS-Datei URL für benutzerdefinierte Themen.';
$CIDRAM['lang']['config_template_data_theme'] = 'Standard-Thema für CIDRAM verwenden.';
$CIDRAM['lang']['field_activate'] = 'Aktivieren';
$CIDRAM['lang']['field_banned'] = 'Verboten';
$CIDRAM['lang']['field_blocked'] = 'Blockiert';
$CIDRAM['lang']['field_clear'] = 'Löschen';
$CIDRAM['lang']['field_clear_all'] = 'Alles löschen';
$CIDRAM['lang']['field_clickable_link'] = 'Klickbarer Link';
$CIDRAM['lang']['field_component'] = 'Komponente';
$CIDRAM['lang']['field_create_new_account'] = 'Neuen Konto erstellen';
$CIDRAM['lang']['field_deactivate'] = 'Deaktivieren';
$CIDRAM['lang']['field_delete_account'] = 'Konto löschen';
$CIDRAM['lang']['field_delete_file'] = 'Löschen';
$CIDRAM['lang']['field_download_file'] = 'Herunterladen';
$CIDRAM['lang']['field_edit_file'] = 'Bearbeiten';
$CIDRAM['lang']['field_expiry'] = 'Endzeit';
$CIDRAM['lang']['field_false'] = 'False (Falsch)';
$CIDRAM['lang']['field_file'] = 'Datei';
$CIDRAM['lang']['field_filename'] = 'Dateiname: ';
$CIDRAM['lang']['field_filetype_directory'] = 'Verzeichnis';
$CIDRAM['lang']['field_filetype_info'] = '{EXT}-Datei';
$CIDRAM['lang']['field_filetype_unknown'] = 'Unbekannt';
$CIDRAM['lang']['field_first_seen'] = 'Wenn zuerst gesehen';
$CIDRAM['lang']['field_infractions'] = 'Verstöße';
$CIDRAM['lang']['field_install'] = 'Installieren';
$CIDRAM['lang']['field_ip_address'] = 'IP-Adresse';
$CIDRAM['lang']['field_latest_version'] = 'Letzte Version';
$CIDRAM['lang']['field_log_in'] = 'Einloggen';
$CIDRAM['lang']['field_new_name'] = 'Neuer Name:';
$CIDRAM['lang']['field_nonclickable_text'] = 'Nicht klickbarer Text';
$CIDRAM['lang']['field_ok'] = 'OK';
$CIDRAM['lang']['field_options'] = 'Optionen';
$CIDRAM['lang']['field_password'] = 'Passwort';
$CIDRAM['lang']['field_permissions'] = 'Berechtigungen';
$CIDRAM['lang']['field_range'] = 'Umfang (Erste – Letzte)';
$CIDRAM['lang']['field_rename_file'] = 'Umbenennen';
$CIDRAM['lang']['field_reset'] = 'Zurücksetzen';
$CIDRAM['lang']['field_set_new_password'] = 'Neues Passwort eingeben';
$CIDRAM['lang']['field_size'] = 'Gesamtgröße: ';
$CIDRAM['lang']['field_size_GB'] = 'GB';
$CIDRAM['lang']['field_size_KB'] = 'KB';
$CIDRAM['lang']['field_size_MB'] = 'MB';
$CIDRAM['lang']['field_size_TB'] = 'TB';
$CIDRAM['lang']['field_size_bytes'] = ['Byte', 'Bytes'];
$CIDRAM['lang']['field_status'] = 'Status';
$CIDRAM['lang']['field_system_timezone'] = 'System Standard-Zeitzone verwenden.';
$CIDRAM['lang']['field_tracking'] = 'Tracking';
$CIDRAM['lang']['field_true'] = 'True (Wahr)';
$CIDRAM['lang']['field_uninstall'] = 'Deinstallieren';
$CIDRAM['lang']['field_update'] = 'Aktualisieren';
$CIDRAM['lang']['field_update_all'] = 'Alle aktualisieren';
$CIDRAM['lang']['field_upload_file'] = 'Neue Datei hochladen';
$CIDRAM['lang']['field_username'] = 'Benutzername';
$CIDRAM['lang']['field_verify'] = 'Verifizieren';
$CIDRAM['lang']['field_verify_all'] = 'Verifizieren alle';
$CIDRAM['lang']['field_your_version'] = 'Ihre Version';
$CIDRAM['lang']['header_login'] = 'Bitte einloggen zum Fortfahren.';
$CIDRAM['lang']['label_active_config_file'] = 'Aktive Konfigurationsdatei: ';
$CIDRAM['lang']['label_banned'] = 'Anfragen verboten';
$CIDRAM['lang']['label_blocked'] = 'Anfragen blockiert';
$CIDRAM['lang']['label_branch'] = 'Branch neueste stabil:';
$CIDRAM['lang']['label_check_modules'] = 'Testen Sie auch gegen Module.';
$CIDRAM['lang']['label_cidram'] = 'CIDRAM-Version verwendet:';
$CIDRAM['lang']['label_displaying'] = ['<span class="txtRd">%s</span> Eintrag angezeigt.', '<span class="txtRd">%s</span> Einträge angezeigt.'];
$CIDRAM['lang']['label_displaying_that_cite'] = ['<span class="txtRd">%1$s</span> Eintrag mit "%2$s" angezeigt.', '<span class="txtRd">%1$s</span> Einträge mit "%2$s" angezeigt.'];
$CIDRAM['lang']['label_expires'] = 'Läuft ab: ';
$CIDRAM['lang']['label_false_positive_risk'] = 'Falsch-Positive Risiko: ';
$CIDRAM['lang']['label_fmgr_cache_data'] = 'Cache-Daten und temporäre Dateien';
$CIDRAM['lang']['label_fmgr_disk_usage'] = 'CIDRAM Speicherplatz verwendet: ';
$CIDRAM['lang']['label_fmgr_free_space'] = 'Speicherplatz verfügbar: ';
$CIDRAM['lang']['label_fmgr_total_disk_usage'] = 'Speicherplatz verwendet insgesamt: ';
$CIDRAM['lang']['label_fmgr_total_space'] = 'Speicherplatz insgesamt: ';
$CIDRAM['lang']['label_fmgr_updates_metadata'] = 'Komponente aktualisiert Metadaten';
$CIDRAM['lang']['label_hide'] = 'Verstecke';
$CIDRAM['lang']['label_never'] = 'Noch nie';
$CIDRAM['lang']['label_os'] = 'Betriebssystem verwendet:';
$CIDRAM['lang']['label_other'] = 'Andere';
$CIDRAM['lang']['label_other-ActiveIPv4'] = 'Aktive IPv4 Signaturdateien';
$CIDRAM['lang']['label_other-ActiveIPv6'] = 'Aktive IPv6 Signaturdateien';
$CIDRAM['lang']['label_other-ActiveModules'] = 'Aktive Module';
$CIDRAM['lang']['label_other-Since'] = 'Anfangsdatum';
$CIDRAM['lang']['label_php'] = 'PHP-Version verwendet:';
$CIDRAM['lang']['label_reCAPTCHA'] = 'reCAPTCHA versucht';
$CIDRAM['lang']['label_results'] = 'Ergebnisse (%s eingegeben – %s abgelehnt – %s akzeptiert – %s fusionierte – %s ausgegeben):';
$CIDRAM['lang']['label_sapi'] = 'SAPI verwendet:';
$CIDRAM['lang']['label_show'] = 'Zeig';
$CIDRAM['lang']['label_stable'] = 'Neueste stabil:';
$CIDRAM['lang']['label_sysinfo'] = 'System Information:';
$CIDRAM['lang']['label_tests'] = 'Tests:';
$CIDRAM['lang']['label_total'] = 'Gesamt';
$CIDRAM['lang']['label_unstable'] = 'Neueste instabil:';
$CIDRAM['lang']['link_accounts'] = 'Konten';
$CIDRAM['lang']['link_cache_data'] = 'Datencache';
$CIDRAM['lang']['link_cidr_calc'] = 'CIDR-Rechner';
$CIDRAM['lang']['link_config'] = 'Konfiguration';
$CIDRAM['lang']['link_documentation'] = 'Dokumentation';
$CIDRAM['lang']['link_file_manager'] = 'Dateimanager';
$CIDRAM['lang']['link_home'] = 'Startseite';
$CIDRAM['lang']['link_ip_aggregator'] = 'IP-Aggregator';
$CIDRAM['lang']['link_ip_test'] = 'IP-Test';
$CIDRAM['lang']['link_ip_tracking'] = 'IP-Tracking';
$CIDRAM['lang']['link_logs'] = 'Protokolldateien';
$CIDRAM['lang']['link_sections_list'] = 'Sektionsliste';
$CIDRAM['lang']['link_statistics'] = 'Statistiken';
$CIDRAM['lang']['link_textmode'] = 'Textformatierung: <a href="%1$sfalse%2$s">Einfach</a> – <a href="%1$strue%2$s">Schick</a> – <a href="%1$stally%2$s">Tally</a>';
$CIDRAM['lang']['link_updates'] = 'Aktualisierungen';
$CIDRAM['lang']['logs_logfile_doesnt_exist'] = 'Ausgewählte Protokolldatei existiert nicht!';
$CIDRAM['lang']['logs_no_logfile_selected'] = 'Keine Protokolldatei ausgewählt.';
$CIDRAM['lang']['logs_no_logfiles_available'] = 'Keine Protokolldateien vorhanden.';
$CIDRAM['lang']['max_login_attempts_exceeded'] = 'Maximale Anzahl der Versucht zu einloggen überschritten; Zugriff verweigert.';
$CIDRAM['lang']['previewer_days'] = 'Tage';
$CIDRAM['lang']['previewer_hours'] = 'Stunden';
$CIDRAM['lang']['previewer_minutes'] = 'Minuten';
$CIDRAM['lang']['previewer_months'] = 'Monate';
$CIDRAM['lang']['previewer_seconds'] = 'Sekunden';
$CIDRAM['lang']['previewer_weeks'] = 'Wochen';
$CIDRAM['lang']['previewer_years'] = 'Jahre';
$CIDRAM['lang']['response_accounts_already_exists'] = 'Ein Konto mit diesem Benutzernamen ist bereits vorhanden!';
$CIDRAM['lang']['response_accounts_created'] = 'Konto erfolgreich erstellt!';
$CIDRAM['lang']['response_accounts_deleted'] = 'Konto erfolgreich gelöscht!';
$CIDRAM['lang']['response_accounts_doesnt_exist'] = 'Dieses Konto existiert nicht.';
$CIDRAM['lang']['response_accounts_password_updated'] = 'Passwort erfolgreich aktualisiert!';
$CIDRAM['lang']['response_activated'] = 'Erfolgreich aktiviert.';
$CIDRAM['lang']['response_activation_failed'] = 'Konnte nicht aktivieren!';
$CIDRAM['lang']['response_checksum_error'] = 'Prüfsummenfehler! Datei abgelehnt!';
$CIDRAM['lang']['response_component_successfully_installed'] = 'Komponente erfolgreich installiert.';
$CIDRAM['lang']['response_component_successfully_uninstalled'] = 'Komponente erfolgreich deinstalliert.';
$CIDRAM['lang']['response_component_successfully_updated'] = 'Komponente erfolgreich aktualisiert.';
$CIDRAM['lang']['response_component_uninstall_error'] = 'Beim Deinstallieren der Komponente ist ein Fehler aufgetreten.';
$CIDRAM['lang']['response_configuration_updated'] = 'Konfiguration erfolgreich aktualisiert.';
$CIDRAM['lang']['response_deactivated'] = 'Erfolgreich deaktiviert.';
$CIDRAM['lang']['response_deactivation_failed'] = 'Konnte nicht deaktivieren!';
$CIDRAM['lang']['response_delete_error'] = 'Löschung-Fehler!';
$CIDRAM['lang']['response_directory_deleted'] = 'Verzeichnis erfolgreich gelöscht!';
$CIDRAM['lang']['response_directory_renamed'] = 'Verzeichnis erfolgreich umbenannt!';
$CIDRAM['lang']['response_error'] = 'Fehler';
$CIDRAM['lang']['response_failed_to_install'] = 'Installation fehlgeschlagen!';
$CIDRAM['lang']['response_failed_to_update'] = 'Aktualisierung fehlgeschlagen!';
$CIDRAM['lang']['response_file_deleted'] = 'Datei erfolgreich gelöscht!';
$CIDRAM['lang']['response_file_edited'] = 'Datei erfolgreich geändert!';
$CIDRAM['lang']['response_file_renamed'] = 'Datei erfolgreich umbenannt!';
$CIDRAM['lang']['response_file_uploaded'] = 'Datei erfolgreich hochgeladen!';
$CIDRAM['lang']['response_login_invalid_password'] = 'Einloggen-Fehler! Ungültiges Passwort!';
$CIDRAM['lang']['response_login_invalid_username'] = 'Einloggen-Fehler! Benutzername existiert nicht!';
$CIDRAM['lang']['response_login_password_field_empty'] = 'Passwort-Feld leer!';
$CIDRAM['lang']['response_login_username_field_empty'] = 'Benutzername-Feld leer!';
$CIDRAM['lang']['response_login_wrong_endpoint'] = 'Falscher Endpunkt!';
$CIDRAM['lang']['response_no'] = 'Nein';
$CIDRAM['lang']['response_possible_problem_found'] = 'Mögliches Problem gefunden.';
$CIDRAM['lang']['response_rename_error'] = 'Umbenennung-Fehler!';
$CIDRAM['lang']['response_statistics_cleared'] = 'Statistiken gelöscht.';
$CIDRAM['lang']['response_tracking_cleared'] = 'Tracking gelöscht.';
$CIDRAM['lang']['response_updates_already_up_to_date'] = 'Schon aktuell.';
$CIDRAM['lang']['response_updates_not_installed'] = 'Komponente nicht installiert!';
$CIDRAM['lang']['response_updates_not_installed_php'] = 'Komponente nicht installiert (erfordert PHP {V})!';
$CIDRAM['lang']['response_updates_outdated'] = 'Veraltet!';
$CIDRAM['lang']['response_updates_outdated_manually'] = 'Veraltet (bitte manuell aktualisieren)!';
$CIDRAM['lang']['response_updates_outdated_php_version'] = 'Veraltet (erfordert PHP {V})!';
$CIDRAM['lang']['response_updates_unable_to_determine'] = 'Kann nicht ermittelt werden.';
$CIDRAM['lang']['response_upload_error'] = 'Hochladen-Fehler!';
$CIDRAM['lang']['response_verification_failed'] = 'Verifizierung fehlgeschlagen! Komponente könnte beschädigt sein.';
$CIDRAM['lang']['response_verification_success'] = 'Verifizierung war Erfolg! Keine Probleme gefunden.';
$CIDRAM['lang']['response_yes'] = 'Ja';
$CIDRAM['lang']['state_async_deny'] = 'Berechtigungen sind nicht ausreichend, um asynchrone Anforderungen auszuführen. Versuchen Sie sich erneut anzumelden.';
$CIDRAM['lang']['state_cache_is_empty'] = 'Der Cache ist leer.';
$CIDRAM['lang']['state_complete_access'] = 'Vollständiger Zugriff';
$CIDRAM['lang']['state_component_is_active'] = 'Komponente ist aktiv.';
$CIDRAM['lang']['state_component_is_inactive'] = 'Komponente ist inaktiv.';
$CIDRAM['lang']['state_component_is_provisional'] = 'Komponente ist vorläufig.';
$CIDRAM['lang']['state_default_password'] = 'Warnung: Verwendet das Standard-Passwort!';
$CIDRAM['lang']['state_ignored'] = 'Ignoriert';
$CIDRAM['lang']['state_loading'] = 'Wird geladen...';
$CIDRAM['lang']['state_loadtime'] = 'Seite-Anfrage in <span class="txtRd">%s</span> Sekunden abgeschlossen.';
$CIDRAM['lang']['state_logged_in'] = 'Eingeloggt.';
$CIDRAM['lang']['state_logs_access_only'] = 'Zugriff nur auf Protokolldateien';
$CIDRAM['lang']['state_maintenance_mode'] = 'Warnung: Wartungsmodus ist aktiviert!';
$CIDRAM['lang']['state_password_not_valid'] = 'Warnung: Dieses Konto verwendet kein gültiges Passwort!';
$CIDRAM['lang']['state_risk_high'] = 'Hohes';
$CIDRAM['lang']['state_risk_low'] = 'Niedriges';
$CIDRAM['lang']['state_risk_medium'] = 'Mittleres';
$CIDRAM['lang']['state_sl_totals'] = 'Summen (Signaturen: <span class="txtRd">%s</span> – Signatur-Sektionen: <span class="txtRd">%s</span> – Signaturdateien: <span class="txtRd">%s</span>).';
$CIDRAM['lang']['state_tracking'] = ['Tracking von %s IP-Adresse in diesem Moment.', 'Tracking von %s IP-Adressen in diesem Moment.'];
$CIDRAM['lang']['switch-hide-non-outdated-set-false'] = 'Nicht verstecken nicht veraltet';
$CIDRAM['lang']['switch-hide-non-outdated-set-true'] = 'Verstecken nicht veraltet';
$CIDRAM['lang']['switch-hide-unused-set-false'] = 'Nicht verstecken unbenutzt';
$CIDRAM['lang']['switch-hide-unused-set-true'] = 'Verstecken unbenutzt';
$CIDRAM['lang']['switch-tracking-blocked-already-set-false'] = 'Nicht gegen Signaturdateien überprüfen';
$CIDRAM['lang']['switch-tracking-blocked-already-set-true'] = 'Gegen Signaturdateien überprüfen';
$CIDRAM['lang']['switch-tracking-hide-banned-blocked-set-false'] = 'Verbergen Sie nicht verbotene/blockiert IP-Adressen';
$CIDRAM['lang']['switch-tracking-hide-banned-blocked-set-true'] = 'Verbergen Sie verbotene/blockiert IP-Adressen';
$CIDRAM['lang']['tip_accounts'] = 'Hallo, {username}.<br />Das Kontenseite macht es möglich zu kontrollieren, wer kann Zugriff auf der CIDRAM Front-End haben.';
$CIDRAM['lang']['tip_cache_data'] = 'Hallo, {username}.<br />Hier können Sie den Inhalt des Cache überprüfen.';
$CIDRAM['lang']['tip_cidr_calc'] = 'Hallo, {username}.<br />Mit dem CIDR-Rechner können Sie berechnen, zu welchen CIDRs eine IP-Adresse gehört.';
$CIDRAM['lang']['tip_config'] = 'Hallo, {username}.<br />Das Konfigurationsseite macht es möglich zu ändern das Konfiguration für CIDRAM von der Front-End.';
$CIDRAM['lang']['tip_custom_ua'] = 'Geben Sie hier der User Agent (optional).';
$CIDRAM['lang']['tip_donate'] = 'CIDRAM wird kostenlos angeboten, aber wenn Sie für das Projekt spenden möchten, können Sie dies tun indem Klicken Sie auf die Spenden-Schaltfläche.';
$CIDRAM['lang']['tip_enter_ip_here'] = 'Geben Sie hier eine IP.';
$CIDRAM['lang']['tip_enter_ips_here'] = 'Geben Sie hier IPs.';
$CIDRAM['lang']['tip_fe_cookie_warning'] = 'Hinweis: CIDRAM verwendet einen Cookie zum Authentifizieren von Logins. Mit der Einloggen, Sie sich damit einverstanden, dass ein Cookie von Ihrem Browser erstellt und gespeichert wird.';
$CIDRAM['lang']['tip_file_manager'] = 'Hallo, {username}.<br />Mit dem Dateimanager können Sie Dateien löschen, bearbeiten, hochladen und herunterladen. Mit Vorsicht verwenden (Können Sie Ihre Installation mit diesem brechen).';
$CIDRAM['lang']['tip_home'] = 'Hallo, {username}.<br />Dies ist die Homepage der CIDRAM Front-End. Wählen Sie einen Link aus dem Navigationsmenü auf der linken um fortzufahren.';
$CIDRAM['lang']['tip_ip_aggregator'] = 'Hallo, {username}.<br />Mit dem IP-Aggregator können Sie IPs und CIDRs auf kleinstmögliche Weise ausdrücken. Geben Sie die zu aggregierenden Daten und drücken Sie "OK".';
$CIDRAM['lang']['tip_ip_test'] = 'Hallo, {username}.<br />Das IP-Test-Seite macht es möglich zu prüfen ob IP-Adressen blockiert durch die aktuell installierten Signaturen sind.';
$CIDRAM['lang']['tip_ip_test_module_switch'] = '(Wenn nicht ausgewählt, werden nur die Signaturdateien getestet).';
$CIDRAM['lang']['tip_ip_tracking'] = 'Hallo, {username}.<br />Auf der Seite IP-Tracking können Sie den Tracking-Status für IP-Adressen überprüfen, welche von ihnen verboten wurden, und Sie können das Tracking für sie widerrufen, wenn Sie dies tun möchten.';
$CIDRAM['lang']['tip_login'] = 'Standard-Benutzername: <span class="txtRd">admin</span> – Standard-Passwort: <span class="txtRd">password</span>';
$CIDRAM['lang']['tip_logs'] = 'Hallo, {username}.<br />Wählen Sie eine Protokolldatei aus der folgenden Liste um den Inhalt dieser Protokolldatei anzuzeigen.';
$CIDRAM['lang']['tip_sections_list'] = 'Hallo, {username}.<br />Auf dieser Seite wird aufgelistet, welche Sektionen in den derzeit aktiven Signaturdateien vorhanden sind.';
$CIDRAM['lang']['tip_see_the_documentation'] = 'Siehe die <a href="https://github.com/CIDRAM/CIDRAM/blob/master/_docs/readme.de.md#SECTION6">Dokumentation</a> für Informationen über den verschiedenen Konfigurationseinstellungen und ihren Zwecken.';
$CIDRAM['lang']['tip_statistics'] = 'Hallo, {username}.<br />Diese Seite zeigt einige grundlegende Nutzungsstatistiken zu Ihrer CIDRAM-Installation.';
$CIDRAM['lang']['tip_statistics_disabled'] = 'Hinweis: Die Statistikverfolgung ist derzeit deaktiviert, aber kann über die Konfigurationsseite aktiviert werden.';
$CIDRAM['lang']['tip_updates'] = 'Hallo, {username}.<br />Das Aktualisierungsseite macht es möglich für Sie zu installieren, zu deinstallieren und zu aktualisieren die verschiedenen Komponenten von CIDRAM (das Kernpaket, Signaturen, L10N-Dateien, u.s.w.).';
$CIDRAM['lang']['title_accounts'] = 'CIDRAM – Konten';
$CIDRAM['lang']['title_cache_data'] = 'CIDRAM – Datencache';
$CIDRAM['lang']['title_cidr_calc'] = 'CIDRAM – CIDR-Rechner';
$CIDRAM['lang']['title_config'] = 'CIDRAM – Konfiguration';
$CIDRAM['lang']['title_file_manager'] = 'CIDRAM – Dateimanager';
$CIDRAM['lang']['title_home'] = 'CIDRAM – Startseite';
$CIDRAM['lang']['title_ip_aggregator'] = 'CIDRAM – IP-Aggregator';
$CIDRAM['lang']['title_ip_test'] = 'CIDRAM – IP-Test';
$CIDRAM['lang']['title_ip_tracking'] = 'CIDRAM – IP-Tracking';
$CIDRAM['lang']['title_login'] = 'CIDRAM – Einloggen';
$CIDRAM['lang']['title_logs'] = 'CIDRAM – Protokolldateien';
$CIDRAM['lang']['title_sections_list'] = 'CIDRAM – Sektionsliste';
$CIDRAM['lang']['title_statistics'] = 'CIDRAM – Statistiken';
$CIDRAM['lang']['title_updates'] = 'CIDRAM – Aktualisierungen';
$CIDRAM['lang']['warning'] = 'Warnungen:';
$CIDRAM['lang']['warning_php_1'] = 'Ihre PHP-Version wird nicht mehr aktiv unterstützt! Aktualisierung wird empfohlen!';
$CIDRAM['lang']['warning_php_2'] = 'Ihre PHP-Version ist schwer verwundbar! Aktualisierung wird dringend empfohlen!';
$CIDRAM['lang']['warning_signatures_1'] = 'Keine Signaturdateien sind aktiv!';

$CIDRAM['lang']['info_some_useful_links'] = 'Einige nützliche Links:<ul>
            <li><a href="https://github.com/CIDRAM/CIDRAM/issues">CIDRAM Fragen @ GitHub</a> – Fragen-Seite für CIDRAM (Unterstützung, u.s.w.).</li>
            <li><a href="https://wordpress.org/plugins/cidram/">CIDRAM @ WordPress.org</a> – WordPress Plugin für CIDRAM.</li>
            <li><a href="https://sourceforge.net/projects/cidram/">CIDRAM @ SourceForge</a> – Alternative download spiegel für CIDRAM.</li>
            <li><a href="https://websectools.com/">WebSecTools.com</a> – Eine Sammlung von einfachen Webmaster-Tools, um Websites zu sichern.</li>
            <li><a href="https://macmathan.info/blocklists">MacMathan.info Bereichsblöcke</a> – Enthält optionale Bereichsblöcke, die dem CIDRAM hinzugefügt werden können, um unerwünschte Länder von dem Zugriff auf Ihre Website zu blockieren.</li>
            <li><a href="https://www.facebook.com/groups/2204685680/">Global PHP Group @ Facebook</a> – PHP Lernressourcen und Diskussion.</li>
            <li><a href="https://php.earth/">PHP.earth</a> – PHP Lernressourcen und Diskussion.</li>
            <li><a href="http://bgp.he.net/">Hurricane Electric BGP Toolkit</a> – Erhalten Sie CIDRs von ASNs, bestimmen Sie ASN-Beziehungen, entdecken Sie ASNs basierend auf Netzwerknamen, usw.</li>
            <li><a href="https://www.stopforumspam.com/forum/">Forum @ Stop Forum Spam</a> – Nützliches Diskussionsforum zum Stoppen von Forumspam.</li>
            <li><a href="https://radar.qrator.net/">Radar by Qrator</a> – Nützliches Werkzeug für die Überprüfung der Konnektivität von ASNs sowie für verschiedene andere Informationen über ASNs.</li>
            <li><a href="http://www.ipdeny.com/ipblocks/">IPdeny IP Landblöcke</a> – Ein fantastischer und genauer Service für die Erstellung landesweiter Signaturen.</li>
            <li><a href="https://www.google.com/transparencyreport/safebrowsing/malware/">Google Malware Dashboard</a> – Zeigt Berichte über Malware-Infektionsraten für ASNs.</li>
            <li><a href="https://www.spamhaus.org/statistics/botnet-asn/">The Spamhaus Project</a> – Zeigt Berichte über Botnet-Infektionsraten für ASNs.</li>
            <li><a href="https://www.abuseat.org/public/asn.html">Abuseat.org\'s Composite-Blockierliste</a> – Zeigt Berichte über Botnet-Infektionsraten für ASNs.</li>
            <li><a href="https://abuseipdb.com/">AbuseIPDB</a> – Verwaltet eine Datenbank für bekannter missbräuchlicher IPs; Bietet eine API zum Überprüfen und Melden IPs.</li>
            <li><a href="https://www.megarbl.net/index.php">MegaRBL.net</a> – Verwaltet Listen von bekannten Spammern; Nützlich für die Überprüfung von IP/ASN-Spam-Aktivitäten.</li>
            <li><a href="https://maikuolan.github.io/Vulnerability-Charts/">Sicherheitskarten</a> – Listet sichere/unsichere Versionen verschiedener Pakete auf (PHP, HHVM, usw).</li>
            <li><a href="https://maikuolan.github.io/Compatibility-Charts/">Kompatibilitätskarten</a> – Listet Kompatibilitätsinformationen für verschiedene Pakete auf (CIDRAM, phpMussel, usw).</li>
        </ul>';
