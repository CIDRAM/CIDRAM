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
 * This file: Swedish language data for the front-end (last modified: 2018.01.14).
 */

/** Prevents execution from outside of CIDRAM. */
if (!defined('CIDRAM')) {
    die('[CIDRAM] This should not be accessed directly.');
}

$CIDRAM['lang']['Extended Description: Bypasses'] = 'Standard bypass-filen som normalt ingår i huvudpaketet.';
$CIDRAM['lang']['Extended Description: CIDRAM'] = 'Huvudpaketet (minus signaturerna, dokumentationen och konfigurationen).';
$CIDRAM['lang']['Name: Bypasses'] = 'Standard signatur bypasses.';
$CIDRAM['lang']['Name: IPv4'] = 'IPv4-signaturfil (oönskade molntjänster och icke-mänskliga ändpunkter).';
$CIDRAM['lang']['Name: IPv4-Bogons'] = 'IPv4-signaturfil (bogon/martian CIDRer).';
$CIDRAM['lang']['Name: IPv4-ISPs'] = 'IPv4-signaturfil (farliga och spammiga internetleverantörer).';
$CIDRAM['lang']['Name: IPv4-Other'] = 'IPv4-signaturfil (CIDRer för proxy, VPNer och andra diverse oönskade tjänster).';
$CIDRAM['lang']['Name: IPv6'] = 'IPv6-signaturfil (oönskade molntjänster och icke-mänskliga ändpunkter).';
$CIDRAM['lang']['Name: IPv6-Bogons'] = 'IPv6-signaturfil (bogon/martian CIDRer).';
$CIDRAM['lang']['Name: IPv6-ISPs'] = 'IPv6-signaturfil (farliga och spammiga internetleverantörer).';
$CIDRAM['lang']['Name: IPv6-Other'] = 'IPv6-signaturfil (CIDRer för proxy, VPNer och andra diverse oönskade tjänster).';
$CIDRAM['lang']['bNav_home_logout'] = '<a href="?">Hem</a> | <a href="?cidram-page=logout">Logga Ut</a>';
$CIDRAM['lang']['bNav_logout'] = '<a href="?cidram-page=logout">Logga Ut</a>';
$CIDRAM['lang']['config_general_FrontEndLog'] = 'Fil för att logga in alla försök till inloggningar på front-end. Ange ett filnamn, eller lämna tomt för att inaktivera.';
$CIDRAM['lang']['config_general_allow_gethostbyaddr_lookup'] = 'Tillåt gethostbyaddr-uppslag när UDP är otillgänglig? True = Ja [Standard]; False = Nej.';
$CIDRAM['lang']['config_general_ban_override'] = 'Åsidosätta "forbid_on_block" när "infraction_limit" överskrids? När det åsidosätter: Blockerade förfrågningar returnerar en tom sida (mallfiler används inte). 200 = Åsidosätta inte [Standard]; 403 = Åsidosätt med "403 Forbidden"; 503 = Åsidosätt med "503 Service unavailable".';
$CIDRAM['lang']['config_general_default_algo'] = 'Definierar vilken algoritm som ska användas för alla framtida lösenord och sessioner. Alternativ: PASSWORD_DEFAULT (standard), PASSWORD_BCRYPT, PASSWORD_ARGON2I (kräver PHP >= 7.2.0).';
$CIDRAM['lang']['config_general_default_dns'] = 'En komma-avgränsad lista över DNS-servrar som ska användas för värdnamnssökningar. Standard = "8.8.8.8,8.8.4.4" (Google DNS). VARNING: Ändra inte detta om du inte vet vad du gör!';
$CIDRAM['lang']['config_general_disable_cli'] = 'Inaktivera CLI-läge? CLI-läge är som standard aktiverat, men kan ibland störa vissa testverktyg (t.ex., PHPUnit) och andra CLI-baserade applikationer. Om du inte behöver avaktivera CLI-läge, bör du ignorera detta direktiv. False = Aktivera CLI-läge [Standard]; True = Inaktivera CLI-läge.';
$CIDRAM['lang']['config_general_disable_frontend'] = 'Inaktivera front-end tillgång? Front-end tillgång kan göra CIDRAM mer hanterbar, men kan också vara en potentiell säkerhetsrisk. Det rekommenderas att hantera CIDRAM via back-end när det är möjligt, men front-end tillgång tillhandahålls när det inte är möjligt. Håll det inaktiverat om du inte behöver det. False = Aktivera front-end tillgång; True = Inaktivera front-end tillgång [Standard].';
$CIDRAM['lang']['config_general_disable_webfonts'] = 'Inaktivera webbfonter? True = Ja; False = Nej [Standard].';
$CIDRAM['lang']['config_general_emailaddr'] = 'Om du vill, kan du ange en e-postadress här för att kunna ges till användare när de är blockerade, för att de ska kunna användas som kontaktpunkt för stöd och/eller hjälp till om de blockeras av misstag eller fel. VARNING: Alla e-postadresser du tillhandahåller här kommer säkert att förvärvas av spamrobotar och skrapor medan de används här, och så, det rekommenderas starkt att om du väljer att tillhandahålla en e-postadress här, så ser du till att e-postadressen du tillhandahåller här är en engångsadress och/eller en adress som du inte har något emot att bli spamad (med andra ord, vill du antagligen inte använda dina primära personliga eller primära företags e-postadresser).';
$CIDRAM['lang']['config_general_emailaddr_display_style'] = 'Hur skulle du föredra att e-postadressen ska presenteras för användarna?';
$CIDRAM['lang']['config_general_forbid_on_block'] = 'Vilka rubriker ska CIDRAM reagera med vid blockering av förfrågningar?';
$CIDRAM['lang']['config_general_force_hostname_lookup'] = 'Tvinga värdnamnssökningar? True = Ja; False = Nej [Standard]. Värdnamnssökningar utförs normalt på grundval av nödvändighet, men kan tvingas för alla förfrågningar. Att göra det kan vara användbart som ett medel för att ge mer detaljerad information i loggarna, men kan också ha en något negativ effekt på prestanda.';
$CIDRAM['lang']['config_general_ipaddr'] = 'Var hittar du IP-adressen för anslutningsförfrågningar? (Användbar för tjänster som Cloudflare och liknande). Standard = REMOTE_ADDR. VARNING: Ändra inte detta om du inte vet vad du gör!';
$CIDRAM['lang']['config_general_lang'] = 'Ange standardspråk för CIDRAM.';
$CIDRAM['lang']['config_general_log_banned_ips'] = 'Inkludera blockerade förfrågningar från förbjudna IP-adresser i loggfilerna? True = Ja [Standard]; False = Nej.';
$CIDRAM['lang']['config_general_logfile'] = 'Mänsklig läsbar fil för att logga in alla blockerade åtkomstförsök. Ange ett filnamn, eller lämna tomt för att inaktivera.';
$CIDRAM['lang']['config_general_logfileApache'] = 'Apache-stil fil för att logga in alla blockerade åtkomstförsök. Ange ett filnamn, eller lämna tomt för att inaktivera.';
$CIDRAM['lang']['config_general_logfileSerialized'] = 'Serialiserad fil för att logga alla blockerade åtkomstförsök. Ange ett filnamn, eller lämna tomt för att inaktivera.';
$CIDRAM['lang']['config_general_maintenance_mode'] = 'Aktivera underhållsläge? True = Ja; False = Nej [Standard]. Inaktiverar allt annat än front-end. Ibland användbar för när du uppdaterar dina CMS, frameworks, osv.';
$CIDRAM['lang']['config_general_max_login_attempts'] = 'Maximalt antal inloggningsförsök.';
$CIDRAM['lang']['config_general_numbers'] = 'Hur föredrar du nummer som ska visas? Välj det exempel som är mest korrekt för dig.';
$CIDRAM['lang']['config_general_protect_frontend'] = 'Anger om de skydd som normalt tillhandahålls av CIDRAM ska tillämpas på front-end. True = Ja [Standard]; False = Nej.';
$CIDRAM['lang']['config_general_search_engine_verification'] = 'Försök att verifiera förfrågningar från sökmotorer? Verifierande sökmotorer ser till att de inte kommer att förbjudas till följd av att infraktionsgräns överskrids (förbjuda sökmotorer från din webbplats kommer vanligtvis att ha en negativ effekt på din sökmotor ranking, SEO, osv). När verifieras kan sökmotorer blockeras som normalt, men kommer inte att förbjudas. När det inte är verifierat, är det möjligt för dem att bli förbjudna till följd av att infraktionsgräns överskrids. Dessutom, sökmotorverifiering ger skydd mot falska sökmotorförfrågningar och mot potentiellt skadliga enheter som maskerar som sökmotorer (sådana förfrågningar kommer att blockeras när sökmotorverifiering är aktiverad). True = Aktivera sökmotorverifiering [Standard]; False = Inaktivera sökmotorverifiering.';
$CIDRAM['lang']['config_general_silent_mode'] = 'Ska CIDRAM utföra tyst omdirigering av blockerade åtkomstförsök istället för att visa sidan "Nekat Tillgång"? Om ja, ange platsen för omdirigering av blockerade åtkomstförsök. Om nej, lämna denna variabel tom.';
$CIDRAM['lang']['config_general_statistics'] = 'Spåra statistik för CIDRAM? True = Ja; False = Nej [Standard].';
$CIDRAM['lang']['config_general_timeFormat'] = 'Datum/tid notationsformat som används av CIDRAM. Ytterligare alternativ kan läggas till på begäran.';
$CIDRAM['lang']['config_general_timeOffset'] = 'Tidszonen kompenseras i minuter.';
$CIDRAM['lang']['config_general_timezone'] = 'Din tidszon.';
$CIDRAM['lang']['config_general_truncate'] = 'Avkorta loggfiler när de når en viss storlek? Värdet är den maximala storleken i B/KB/MB/GB/TB som en loggfil kan växa till innan den trunkeras. Standardvärdet på 0KB inaktiverar avkortning (loggfiler kan växa i obestämd tid). Notera: Gäller enskilda loggfiler! Loggfiler storlek anses inte kollektivt.';
$CIDRAM['lang']['config_recaptcha_api'] = 'Vilket API ska användas? V2 eller Invisible?';
$CIDRAM['lang']['config_recaptcha_expiry'] = 'Antal timmar för att komma ihåg reCAPTCHA-instanser.';
$CIDRAM['lang']['config_recaptcha_lockip'] = 'Låsa reCAPTCHA till IP-adresser?';
$CIDRAM['lang']['config_recaptcha_lockuser'] = 'Låsa reCAPTCHA till användare?';
$CIDRAM['lang']['config_recaptcha_logfile'] = 'Logga in alla reCAPTCHA-försök? Om ja, ange det namn som ska användas för loggfilen. Om nej, lämna denna variabel tom.';
$CIDRAM['lang']['config_recaptcha_secret'] = 'Detta värde bör motsvara den "secret key" för din reCAPTCHA, som finns i reCAPTCHA-instrumentpanelen.';
$CIDRAM['lang']['config_recaptcha_signature_limit'] = 'Maximalt antal signaturer får utlösas när en reCAPTCHA-instans ska erbjudas. Standard = 1. Om detta nummer överskrids för en viss begäran, kommer inte en reCAPTCHA-instans att erbjudas.';
$CIDRAM['lang']['config_recaptcha_sitekey'] = 'Detta värde bör motsvara den "site key" för din reCAPTCHA, som finns i reCAPTCHA-instrumentpanelen.';
$CIDRAM['lang']['config_recaptcha_usemode'] = 'Definierar hur CIDRAM ska använda reCAPTCHA (se dokumentation).';
$CIDRAM['lang']['config_signatures_block_bogons'] = 'Blockera bogon/martian CIDRer? Om du förväntar dig anslutningar till din webbplats från ditt lokala nätverk, från localhost eller från ditt LAN, detta direktiv borde vara false. Om du inte förväntar dig sådana anslutningar, detta direktiv borde vara true.';
$CIDRAM['lang']['config_signatures_block_cloud'] = 'Blockera CIDRer identifierade som tillhör webbhotell/molntjänster? Om du använder en API-tjänst från din webbplats eller om du förväntar dig att andra webbplatser ska ansluta till din webbplats, detta direktiv borde vara false. Annars, detta direktiv borde vara true.';
$CIDRAM['lang']['config_signatures_block_generic'] = 'Blockera CIDRer rekommenderas generellt för svartlistning? Detta omfattar alla signaturer som inte är markerade som en del av någon av de andra mer specifika signaturkategorierna.';
$CIDRAM['lang']['config_signatures_block_proxies'] = 'Blockera CIDRer identifierade som tillhör proxytjänster? Om du behöver att användare ska kunna komma åt din webbplats från anonyma proxytjänster, detta direktiv borde vara false. Annars, om du inte behöver anonyma proxies, detta direktiv borde vara true som ett sätt att förbättra säkerheten.';
$CIDRAM['lang']['config_signatures_block_spam'] = 'Blockera CIDRer identifierade som högrisk för spam? Om du inte upplever problem när du gör det, bör det alltid vara true.';
$CIDRAM['lang']['config_signatures_default_tracktime'] = 'Hur många sekunder spåra IP-adresser som är förbjudna av moduler. Standard = 604800 (1 vecka).';
$CIDRAM['lang']['config_signatures_infraction_limit'] = 'Maximalt antal infraktions som en IP får medföra innan den är förbjuden av IP-spårning. Standard = 10.';
$CIDRAM['lang']['config_signatures_ipv4'] = 'En lista över de IPv4 signaturfiler som CIDRAM ska försöka analysera, avgränsas av kommatecken.';
$CIDRAM['lang']['config_signatures_ipv6'] = 'En lista över de IPv6 signaturfiler som CIDRAM ska försöka analysera, avgränsas av kommatecken.';
$CIDRAM['lang']['config_signatures_modules'] = 'En lista över modulfiler som ska laddas efter att ha kontrollerat IPv4/IPv6-signaturerna, avgränsas av kommatecken.';
$CIDRAM['lang']['config_signatures_track_mode'] = 'När ska infraktions räknas? False = När IP-adresser blockeras av moduler. True = När IP-adresser blockeras av någon anledning.';
$CIDRAM['lang']['config_template_data_Magnification'] = 'Font förstoring. Standard = 1.';
$CIDRAM['lang']['config_template_data_css_url'] = 'CSS-filadress för anpassade teman.';
$CIDRAM['lang']['config_template_data_theme'] = 'Standardtema som ska användas för CIDRAM.';
$CIDRAM['lang']['field_activate'] = 'Aktivera';
$CIDRAM['lang']['field_banned'] = 'Förbjudna';
$CIDRAM['lang']['field_blocked'] = 'Blockerad';
$CIDRAM['lang']['field_clear'] = 'Rensa';
$CIDRAM['lang']['field_clear_all'] = 'Rensa alla';
$CIDRAM['lang']['field_clickable_link'] = 'Klickbar länk';
$CIDRAM['lang']['field_component'] = 'Komponent';
$CIDRAM['lang']['field_create_new_account'] = 'Skapa nytt konto';
$CIDRAM['lang']['field_deactivate'] = 'Inaktivera';
$CIDRAM['lang']['field_delete_account'] = 'Radera konto';
$CIDRAM['lang']['field_delete_file'] = 'Radera';
$CIDRAM['lang']['field_download_file'] = 'Ladda ner';
$CIDRAM['lang']['field_edit_file'] = 'Redigera';
$CIDRAM['lang']['field_expiry'] = 'Upphörande';
$CIDRAM['lang']['field_false'] = 'False (Falsk)';
$CIDRAM['lang']['field_file'] = 'Fil';
$CIDRAM['lang']['field_filename'] = 'Filnamn: ';
$CIDRAM['lang']['field_filetype_directory'] = 'Katalog';
$CIDRAM['lang']['field_filetype_info'] = '{EXT}-Fil';
$CIDRAM['lang']['field_filetype_unknown'] = 'Okänd';
$CIDRAM['lang']['field_first_seen'] = 'Först sett';
$CIDRAM['lang']['field_infractions'] = 'Infraktions';
$CIDRAM['lang']['field_install'] = 'Installera';
$CIDRAM['lang']['field_ip_address'] = 'IP-Adress';
$CIDRAM['lang']['field_latest_version'] = 'Senaste versionen';
$CIDRAM['lang']['field_log_in'] = 'Logga in';
$CIDRAM['lang']['field_new_name'] = 'Nytt namn:';
$CIDRAM['lang']['field_nonclickable_text'] = 'Icke-klickbar text';
$CIDRAM['lang']['field_ok'] = 'OK';
$CIDRAM['lang']['field_options'] = 'Alternativ';
$CIDRAM['lang']['field_password'] = 'Lösenord';
$CIDRAM['lang']['field_permissions'] = 'Behörigheter';
$CIDRAM['lang']['field_range'] = 'Räckvidd (Först – Sista)';
$CIDRAM['lang']['field_rename_file'] = 'Byt namn';
$CIDRAM['lang']['field_reset'] = 'Återställa';
$CIDRAM['lang']['field_set_new_password'] = 'Ange nytt lösenord';
$CIDRAM['lang']['field_size'] = 'Total storlek: ';
$CIDRAM['lang']['field_size_GB'] = 'GB';
$CIDRAM['lang']['field_size_KB'] = 'KB';
$CIDRAM['lang']['field_size_MB'] = 'MB';
$CIDRAM['lang']['field_size_TB'] = 'TB';
$CIDRAM['lang']['field_size_bytes'] = 'byte';
$CIDRAM['lang']['field_status'] = 'Status';
$CIDRAM['lang']['field_system_timezone'] = 'Använd systemets standardtidzon.';
$CIDRAM['lang']['field_tracking'] = 'Spårning';
$CIDRAM['lang']['field_true'] = 'True (Sant)';
$CIDRAM['lang']['field_uninstall'] = 'Avinstallera';
$CIDRAM['lang']['field_update'] = 'Uppdatera';
$CIDRAM['lang']['field_update_all'] = 'Uppdatera alla';
$CIDRAM['lang']['field_upload_file'] = 'Ladda upp ny fil';
$CIDRAM['lang']['field_username'] = 'Användarnamn';
$CIDRAM['lang']['field_verify'] = 'Verifiera';
$CIDRAM['lang']['field_verify_all'] = 'Verifiera allt';
$CIDRAM['lang']['field_your_version'] = 'Din version';
$CIDRAM['lang']['header_login'] = 'Snälla logga in för att fortsätta.';
$CIDRAM['lang']['label_active_config_file'] = 'Aktiv konfigurationsfil: ';
$CIDRAM['lang']['label_banned'] = 'Förfrågningar förbjudna';
$CIDRAM['lang']['label_blocked'] = 'Förfrågningar blockerade';
$CIDRAM['lang']['label_branch'] = 'Branch senaste stabila:';
$CIDRAM['lang']['label_check_modules'] = 'Test även mot moduler.';
$CIDRAM['lang']['label_cidram'] = 'CIDRAM-version som används:';
$CIDRAM['lang']['label_displaying'] = ['<span class="txtRd">%s</span> föremål visas.', '<span class="txtRd">%s</span> föremålen visas.'];
$CIDRAM['lang']['label_displaying_that_cite'] = ['<span class="txtRd">%1$s</span> föremål visas som citerar "%2$s".', '<span class="txtRd">%1$s</span> föremålen visas som citerar "%2$s".'];
$CIDRAM['lang']['label_false_positive_risk'] = 'Falsk positiv risk: ';
$CIDRAM['lang']['label_fmgr_cache_data'] = 'Cacherdata och tillfälliga filer';
$CIDRAM['lang']['label_fmgr_disk_usage'] = 'CIDRAM-diskanvändning: ';
$CIDRAM['lang']['label_fmgr_free_space'] = 'Gratis diskutrymme: ';
$CIDRAM['lang']['label_fmgr_total_disk_usage'] = 'Totalt diskanvändning: ';
$CIDRAM['lang']['label_fmgr_total_space'] = 'Totalt diskutrymme: ';
$CIDRAM['lang']['label_fmgr_updates_metadata'] = 'Komponentuppdateringsmetadata';
$CIDRAM['lang']['label_hide'] = 'Dölja';
$CIDRAM['lang']['label_os'] = 'Operativsystem som används:';
$CIDRAM['lang']['label_other'] = 'Andra';
$CIDRAM['lang']['label_other-ActiveIPv4'] = 'Aktiva IPv4-signaturfiler';
$CIDRAM['lang']['label_other-ActiveIPv6'] = 'Aktiva IPv6-signaturfiler';
$CIDRAM['lang']['label_other-ActiveModules'] = 'Aktiva moduler';
$CIDRAM['lang']['label_other-Since'] = 'Start datum';
$CIDRAM['lang']['label_php'] = 'PHP-version som används:';
$CIDRAM['lang']['label_reCAPTCHA'] = 'reCAPTCHA försöken';
$CIDRAM['lang']['label_results'] = 'Resultat (%s i – %s avvisade – %s accepterad – %s kombinerad – %s ut):';
$CIDRAM['lang']['label_sapi'] = 'SAPI som används:';
$CIDRAM['lang']['label_show'] = 'Visa';
$CIDRAM['lang']['label_stable'] = 'Senaste stabila:';
$CIDRAM['lang']['label_sysinfo'] = 'Systeminformation:';
$CIDRAM['lang']['label_tests'] = 'Tester:';
$CIDRAM['lang']['label_total'] = 'Totalt';
$CIDRAM['lang']['label_unstable'] = 'Senaste instabil:';
$CIDRAM['lang']['link_accounts'] = 'Konton';
$CIDRAM['lang']['link_cidr_calc'] = 'CIDR-Kalkylator';
$CIDRAM['lang']['link_config'] = 'Konfiguration';
$CIDRAM['lang']['link_documentation'] = 'Dokumentation';
$CIDRAM['lang']['link_file_manager'] = 'Filhanterare';
$CIDRAM['lang']['link_home'] = 'Hem';
$CIDRAM['lang']['link_ip_aggregator'] = 'IP-Aggregator';
$CIDRAM['lang']['link_ip_test'] = 'IP-Test';
$CIDRAM['lang']['link_ip_tracking'] = 'IP-Spårning';
$CIDRAM['lang']['link_logs'] = 'Loggfiler';
$CIDRAM['lang']['link_sections_list'] = 'Sektionslista';
$CIDRAM['lang']['link_statistics'] = 'Statistik';
$CIDRAM['lang']['link_textmode'] = 'Textformatering: <a href="%1$sfalse%2$s">Enkel</a> – <a href="%1$strue%2$s">Fint</a> – <a href="%1$stally%2$s">Tally</a>';
$CIDRAM['lang']['link_updates'] = 'Uppdateringar';
$CIDRAM['lang']['logs_logfile_doesnt_exist'] = 'Vald loggfil existerar inte!';
$CIDRAM['lang']['logs_no_logfile_selected'] = 'Ingen loggfil vald.';
$CIDRAM['lang']['logs_no_logfiles_available'] = 'Inga loggfiler finns tillgängliga.';
$CIDRAM['lang']['max_login_attempts_exceeded'] = 'Maximalt antal inloggningsförsök överskreds; Nekat tillgång.';
$CIDRAM['lang']['previewer_days'] = 'Dagar';
$CIDRAM['lang']['previewer_hours'] = 'Timmar';
$CIDRAM['lang']['previewer_minutes'] = 'Minuter';
$CIDRAM['lang']['previewer_months'] = 'Månader';
$CIDRAM['lang']['previewer_seconds'] = 'Sekunder';
$CIDRAM['lang']['previewer_weeks'] = 'Veckor';
$CIDRAM['lang']['previewer_years'] = 'År';
$CIDRAM['lang']['response_accounts_already_exists'] = 'Ett konto med det användarnamnet existerar redan!';
$CIDRAM['lang']['response_accounts_created'] = 'Konto har skapats framgångsrikt!';
$CIDRAM['lang']['response_accounts_deleted'] = 'Konto har raderats framgångsrikt!';
$CIDRAM['lang']['response_accounts_doesnt_exist'] = 'Det kontot existerar inte.';
$CIDRAM['lang']['response_accounts_password_updated'] = 'Lösenordet har uppdaterats framgångsrikt!';
$CIDRAM['lang']['response_activated'] = 'Aktiverad framgångsrikt.';
$CIDRAM['lang']['response_activation_failed'] = 'Misslyckades med att aktivera!';
$CIDRAM['lang']['response_checksum_error'] = 'Checksumfel! Fil avslogs!';
$CIDRAM['lang']['response_component_successfully_installed'] = 'Komponent har installerats framgångsrikt.';
$CIDRAM['lang']['response_component_successfully_uninstalled'] = 'Komponent har avinstallerats framgångsrikt.';
$CIDRAM['lang']['response_component_successfully_updated'] = 'Komponent har uppdaterats framgångsrikt.';
$CIDRAM['lang']['response_component_uninstall_error'] = 'Ett fel uppstod när försöker avinstallera komponenten.';
$CIDRAM['lang']['response_configuration_updated'] = 'Konfigurationen har uppdaterats framgångsrikt.';
$CIDRAM['lang']['response_deactivated'] = 'Inaktiverad framgångsrikt.';
$CIDRAM['lang']['response_deactivation_failed'] = 'Misslyckades med att inaktivera!';
$CIDRAM['lang']['response_delete_error'] = 'Misslyckades med att radera!';
$CIDRAM['lang']['response_directory_deleted'] = 'Katalog har raderats framgångsrikt!';
$CIDRAM['lang']['response_directory_renamed'] = 'Katalognamn har ändrats framgångsrikt!';
$CIDRAM['lang']['response_error'] = 'Fel';
$CIDRAM['lang']['response_failed_to_install'] = 'Misslyckades med att installera!';
$CIDRAM['lang']['response_failed_to_update'] = 'Misslyckades med att uppdatera!';
$CIDRAM['lang']['response_file_deleted'] = 'Fil har raderats framgångsrikt!';
$CIDRAM['lang']['response_file_edited'] = 'Fil har ändrats framgångsrikt!';
$CIDRAM['lang']['response_file_renamed'] = 'Filnamn har ändrats framgångsrikt!';
$CIDRAM['lang']['response_file_uploaded'] = 'Fil har laddats upp framgångsrikt!';
$CIDRAM['lang']['response_login_invalid_password'] = 'Inloggningsfel! Felaktigt lösenord!';
$CIDRAM['lang']['response_login_invalid_username'] = 'Inloggningsfel! Användarnamnet existerar inte!';
$CIDRAM['lang']['response_login_password_field_empty'] = 'Lösenord fältet tomt!';
$CIDRAM['lang']['response_login_username_field_empty'] = 'Användarnamn fältet tomt!';
$CIDRAM['lang']['response_login_wrong_endpoint'] = 'Felaktig slutpunkt!';
$CIDRAM['lang']['response_no'] = 'Nej';
$CIDRAM['lang']['response_possible_problem_found'] = 'Möjligt problem hittades.';
$CIDRAM['lang']['response_rename_error'] = 'Misslyckades med att byta namn!';
$CIDRAM['lang']['response_statistics_cleared'] = 'Statistik rensas.';
$CIDRAM['lang']['response_tracking_cleared'] = 'Spårning rensas.';
$CIDRAM['lang']['response_updates_already_up_to_date'] = 'Redan uppdaterad.';
$CIDRAM['lang']['response_updates_not_installed'] = 'Komponenten inte installerad!';
$CIDRAM['lang']['response_updates_not_installed_php'] = 'Komponenten inte installerad (kräver PHP {V})!';
$CIDRAM['lang']['response_updates_outdated'] = 'Föråldrad!';
$CIDRAM['lang']['response_updates_outdated_manually'] = 'Föråldrad (vänligen uppdatera manuellt)!';
$CIDRAM['lang']['response_updates_outdated_php_version'] = 'Föråldrad (kräver PHP {V})!';
$CIDRAM['lang']['response_updates_unable_to_determine'] = 'Kan inte bestämma.';
$CIDRAM['lang']['response_upload_error'] = 'Misslyckades med att ladda upp!';
$CIDRAM['lang']['response_verification_failed'] = 'Verifiering misslyckades! Komponenten kan vara skadad.';
$CIDRAM['lang']['response_verification_success'] = 'Verifiering succes! Inga problem hittades.';
$CIDRAM['lang']['response_yes'] = 'Ja';
$CIDRAM['lang']['state_complete_access'] = 'Fullständig tillgång';
$CIDRAM['lang']['state_component_is_active'] = 'Komponenten är aktiv.';
$CIDRAM['lang']['state_component_is_inactive'] = 'Komponenten är inaktiv.';
$CIDRAM['lang']['state_component_is_provisional'] = 'Komponenten är provisorisk.';
$CIDRAM['lang']['state_default_password'] = 'Varning: Använder standardlösenordet!';
$CIDRAM['lang']['state_loadtime'] = 'Sidförfrågan färdigställd om <span class="txtRd">%s</span> sekunder.';
$CIDRAM['lang']['state_logged_in'] = 'Inloggad.';
$CIDRAM['lang']['state_logs_access_only'] = 'Kan bara komma åt loggfilerna';
$CIDRAM['lang']['state_maintenance_mode'] = 'Varning: Underhållsläget är aktiverat!';
$CIDRAM['lang']['state_password_not_valid'] = 'Varning: Det här kontot använder inte ett giltigt lösenord!';
$CIDRAM['lang']['state_risk_high'] = 'Hög';
$CIDRAM['lang']['state_risk_low'] = 'Låg';
$CIDRAM['lang']['state_risk_medium'] = 'Medium';
$CIDRAM['lang']['state_sl_totals'] = 'Totals (Signaturer: <span class="txtRd">%s</span> – Signatur sektioner: <span class="txtRd">%s</span> – Signaturfiler: <span class="txtRd">%s</span>).';
$CIDRAM['lang']['state_tracking'] = ['Spårar för närvarande %s IP-adress.', 'Spårar för närvarande %s IP-adresser.'];
$CIDRAM['lang']['switch-hide-non-outdated-set-false'] = 'Göm inte icke-föråldrade';
$CIDRAM['lang']['switch-hide-non-outdated-set-true'] = 'Göm icke-föråldrade';
$CIDRAM['lang']['switch-hide-unused-set-false'] = 'Göm inte oanvänd';
$CIDRAM['lang']['switch-hide-unused-set-true'] = 'Göm oanvänd';
$CIDRAM['lang']['switch-tracking-blocked-already-set-false'] = 'Kontrollera inte signaturfiler';
$CIDRAM['lang']['switch-tracking-blocked-already-set-true'] = 'Kontrollera signaturfiler';
$CIDRAM['lang']['switch-tracking-hide-banned-blocked-set-false'] = 'Göm inte förbjudna/blockerade IP-adresser';
$CIDRAM['lang']['switch-tracking-hide-banned-blocked-set-true'] = 'Göm förbjudna/blockerade IP-adresser';
$CIDRAM['lang']['tip_accounts'] = 'Hallå, {username}.<br />Du kan styra vem som har tillgång till CIDRAM front-end på kontosidan.';
$CIDRAM['lang']['tip_cidr_calc'] = 'Hallå, {username}.<br />CIDR-kalkylatorn kan beräkna CIDR-faktorerna för en IP-adress.';
$CIDRAM['lang']['tip_config'] = 'Hallå, {username}.<br />Du kan ändra konfigurationen för CIDRAM från front-enden konfigurationssida.';
$CIDRAM['lang']['tip_custom_ua'] = 'Ange användaragent (user agent) här (valfritt).';
$CIDRAM['lang']['tip_donate'] = 'CIDRAM erbjuds gratis, men om du vill donera till projektet, du kan göra det genom att klicka på donera-knappen.';
$CIDRAM['lang']['tip_enter_ip_here'] = 'Ange IP-adress här.';
$CIDRAM['lang']['tip_enter_ips_here'] = 'Ange IP-adresser här.';
$CIDRAM['lang']['tip_fe_cookie_warning'] = 'Notera: CIDRAM använder en cookie för att autentisera inloggningar. Genom att logga in, ger du ditt samtycke till att en cookie ska skapas och lagras av din webbläsare.';
$CIDRAM['lang']['tip_file_manager'] = 'Hallå, {username}.<br />Du kan radera, redigera, ladda upp och ladda ner filer med hjälp av filhanteraren. Använd med försiktighet (du kan bryta din installation med detta).';
$CIDRAM['lang']['tip_home'] = 'Hallå, {username}.<br />Detta är hemsidan för CIDRAM front-end. Välj en länk från navigeringsmenyn till vänster för att fortsätta.';
$CIDRAM['lang']['tip_ip_aggregator'] = 'Hallå, {username}.<br />Du kan aggregera IP-adresser och subnät till deras minsta möjliga uttryck med hjälp av IP-aggregatorn. Ange data som ska aggregeras och tryck på "OK".';
$CIDRAM['lang']['tip_ip_test'] = 'Hallå, {username}.<br />Du kan testa om IP-adresser blockeras av nuvarande installerade signaturer med hjälp av IP-testsidan.';
$CIDRAM['lang']['tip_ip_test_module_switch'] = '(När den inte är vald, endast signaturfiler kommer att testas mot).';
$CIDRAM['lang']['tip_ip_tracking'] = 'Hallå, {username}.<br />Med hjälp av IP-spårningssidan, du kan kontrollera vilka IP-adresser som spåras, vilka har blivit förbjudna, och du kan återkalla spårning och förbud om du vill.';
$CIDRAM['lang']['tip_login'] = 'Standard användarnamn: <span class="txtRd">admin</span> – Standard lösenord: <span class="txtRd">password</span>';
$CIDRAM['lang']['tip_logs'] = 'Hallå, {username}.<br />Välj en loggfil från listan nedan för att se innehållet i den loggfilen.';
$CIDRAM['lang']['tip_sections_list'] = 'Hallå, {username}.<br />Den här sidan visar vilka sektioner som finns i de aktuella aktiva signaturfilerna.';
$CIDRAM['lang']['tip_see_the_documentation'] = 'Se <a href="https://github.com/CIDRAM/CIDRAM/blob/master/_docs/readme.en.md#SECTION6">dokumentationen</a> för information om de olika konfigurationsdirektiven och deras ändamål.';
$CIDRAM['lang']['tip_statistics'] = 'Hallå, {username}.<br />Den här sidan visar en viss användarstatistik för din CIDRAM-installation.';
$CIDRAM['lang']['tip_statistics_disabled'] = 'Notera: Statistikspårning är för närvarande inaktiverad, men kan aktiveras via konfigurationssidan.';
$CIDRAM['lang']['tip_updates'] = 'Hallå, {username}.<br />Du kan installera, avinstallera och uppdatera de olika komponenterna i CIDRAM (kärnpaketet, signaturer, L10N-filer, osv) med hjälp av uppdateringssidan.';
$CIDRAM['lang']['title_accounts'] = 'CIDRAM – Konton';
$CIDRAM['lang']['title_cidr_calc'] = 'CIDRAM – CIDR-Kalkylator';
$CIDRAM['lang']['title_config'] = 'CIDRAM – Konfiguration';
$CIDRAM['lang']['title_file_manager'] = 'CIDRAM – Filhanterare';
$CIDRAM['lang']['title_home'] = 'CIDRAM – Hem';
$CIDRAM['lang']['title_ip_aggregator'] = 'CIDRAM – IP-Aggregator';
$CIDRAM['lang']['title_ip_test'] = 'CIDRAM – IP-Test';
$CIDRAM['lang']['title_ip_tracking'] = 'CIDRAM – IP-Spårning';
$CIDRAM['lang']['title_login'] = 'CIDRAM – Inloggning';
$CIDRAM['lang']['title_logs'] = 'CIDRAM – Loggfiler';
$CIDRAM['lang']['title_sections_list'] = 'CIDRAM – Sektionslista';
$CIDRAM['lang']['title_statistics'] = 'CIDRAM – Statistik';
$CIDRAM['lang']['title_updates'] = 'CIDRAM – Uppdateringar';
$CIDRAM['lang']['warning'] = 'Varningar:';
$CIDRAM['lang']['warning_php_1'] = 'Din PHP-version stöds inte aktivt längre! Uppdatering rekommenderas!';
$CIDRAM['lang']['warning_php_2'] = 'Din PHP-version är allvarligt sårbar! Uppdatering rekommenderas starkt!';
$CIDRAM['lang']['warning_signatures_1'] = 'Inga signaturfiler är aktiva!';

$CIDRAM['lang']['info_some_useful_links'] = 'Några användbara länkar:<ul>
            <li><a href="https://github.com/CIDRAM/CIDRAM/issues">CIDRAM Issues @ GitHub</a> – Issues sida för CIDRAM (stöd, hjälp, osv).</li>
            <li><a href="http://www.spambotsecurity.com/forum/viewforum.php?f=61">CIDRAM @ Spambot Security</a> – Diskussionsforum för CIDRAM (stöd, hjälp, osv).</li>
            <li><a href="https://wordpress.org/plugins/cidram/">CIDRAM @ WordPress.org</a> – WordPress plugin för CIDRAM.</li>
            <li><a href="https://sourceforge.net/projects/cidram/">CIDRAM @ SourceForge</a> – Alternativ nedladdningsspegel för CIDRAM.</li>
            <li><a href="https://websectools.com/">WebSecTools.com</a> – En samling enkla verktyg för webbansvariga för att säkra webbplatser.</li>
            <li><a href="https://macmathan.info/blocklists">MacMathan.info Range Blocks</a> – Innehåller valfria range blocks som kan läggas till CIDRAM för att blockera oönskade länder från att komma åt din webbplats.</li>
            <li><a href="https://www.facebook.com/groups/2204685680/">Global PHP Group @ Facebook</a> – PHP lärande resurser och diskussion.</li>
            <li><a href="https://php.earth/">PHP.earth</a> – PHP lärande resurser och diskussion.</li>
            <li><a href="http://bgp.he.net/">Hurricane Electric BGP Toolkit</a> – Få CIDRer från ASNer, bestäm ASN-relationer, upptäck ASNer baserat på nätverksnamn, osv.</li>
            <li><a href="https://www.stopforumspam.com/forum/">Forum @ Stop Forum Spam</a> – Användbart diskussionsforum om att stoppa forums spam.</li>
            <li><a href="https://radar.qrator.net/">Radar av Qrator</a> – Användbart verktyg för att kontrollera ASN-anslutningar samt för annan information om ASNer.</li>
            <li><a href="http://www.ipdeny.com/ipblocks/">IPdeny IP country blocks</a> – En fantastisk och exakt tjänst för att generera landsdäcks signaturer.</li>
            <li><a href="https://www.google.com/transparencyreport/safebrowsing/malware/">Google Malware Dashboard</a> – Visar rapporter om malwareinfektionshastigheter för ASNer.</li>
            <li><a href="https://www.spamhaus.org/statistics/botnet-asn/">Spamhausprojektet</a> – Visar rapporter om botnätinfektionshastigheter för ASNer.</li>
            <li><a href="https://www.abuseat.org/public/asn.html">Abuseat.orgs Sammansatta Blockeringslista</a> – Visar rapporter om botnätinfektionshastigheter för ASNer.</li>
            <li><a href="https://abuseipdb.com/">AbuseIPDB</a> – Upprätthåller en databas med kända kränkande IP-adresser; Ger ett API för kontroll och rapportering av IP-adresser.</li>
            <li><a href="https://www.megarbl.net/index.php">MegaRBL.net</a> – Upprätthåller listor över kända spammare; Användbar för att kontrollera IP/ASN-spamaktiviteter.</li>
            <li><a href="https://maikuolan.github.io/Vulnerability-Charts/">Sårbarhetstabeller</a> – Listar säkra/osäkra versioner av olika paket (PHP, HHVM, osv).</li>
            <li><a href="https://maikuolan.github.io/Compatibility-Charts/">Kompatibilitetstabeller</a> – Visar kompatibilitetsinformation för olika paket (CIDRAM, phpMussel, osv).</li>
        </ul>';
