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
 * This file: Dutch language data for the front-end (last modified: 2017.06.21).
 */

/** Prevents execution from outside of CIDRAM. */
if (!defined('CIDRAM')) {
    die('[CIDRAM] This should not be accessed directly.');
}

$CIDRAM['lang']['bNav_home_logout'] = '<a href="?">Startpagina</a> | <a href="?cidram-page=logout">Uitloggen</a>';
$CIDRAM['lang']['bNav_logout'] = '<a href="?cidram-page=logout">Uitloggen</a>';
$CIDRAM['lang']['config_general_ban_override'] = 'Overrijden "forbid_on_block" wanneer "infraction_limit" wordt overschreden? Wanneer het overrijdt: Geblokkeerde verzoeken retourneert een lege pagina (template bestanden worden niet gebruikt). 200 = Niet overrijden [Standaard]; 403 = Overrijden met "403 Forbidden"; 503 = Overrijden met "503 Service unavailable".';
$CIDRAM['lang']['config_general_default_dns'] = 'Een door komma\'s gescheiden lijst met DNS-servers te gebruiken voor de hostnaam lookups. Standaard = "8.8.8.8,8.8.4.4" (Google DNS). WAARSCHUWING: Verander dit niet tenzij u weet wat u doet!';
$CIDRAM['lang']['config_general_disable_cli'] = 'Uitschakelen CLI-modus?';
$CIDRAM['lang']['config_general_disable_frontend'] = 'Uitschakelen frontend toegang?';
$CIDRAM['lang']['config_general_disable_webfonts'] = 'Uitschakelen webfonts? True = Ja; False = Nee [Standaard].';
$CIDRAM['lang']['config_general_emailaddr'] = 'E-mailadres voor ondersteuning.';
$CIDRAM['lang']['config_general_forbid_on_block'] = 'Welke headers moet CIDRAM reageren met bij het blokkeren van verzoeken?';
$CIDRAM['lang']['config_general_FrontEndLog'] = 'Bestand om de front-end login pogingen te loggen. Geef een bestandsnaam, of laat leeg om uit te schakelen.';
$CIDRAM['lang']['config_general_ipaddr'] = 'Waar het IP-adres van het aansluiten verzoek te vinden? (Handig voor diensten zoals Cloudflare en dergelijke). Standaard = REMOTE_ADDR. WAARSCHUWING: Verander dit niet tenzij u weet wat u doet!';
$CIDRAM['lang']['config_general_lang'] = 'Geef de standaardtaal voor CIDRAM.';
$CIDRAM['lang']['config_general_logfile'] = 'Mensen leesbare bestand om alle geblokkeerde toegang pogingen te loggen. Geef een bestandsnaam, of laat leeg om uit te schakelen.';
$CIDRAM['lang']['config_general_logfileApache'] = 'Apache-stijl bestand om alle geblokkeerde toegang pogingen te loggen. Geef een bestandsnaam, of laat leeg om uit te schakelen.';
$CIDRAM['lang']['config_general_logfileSerialized'] = 'Geserialiseerd bestand om alle geblokkeerde toegang pogingen te loggen. Geef een bestandsnaam, of laat leeg om uit te schakelen.';
$CIDRAM['lang']['config_general_log_banned_ips'] = 'Omvatten geblokkeerde verzoeken van verboden IP-adressen in de logbestanden? True = Ja [Standaard]; False = Nee.';
$CIDRAM['lang']['config_general_max_login_attempts'] = 'Maximum aantal inlogpogingen.';
$CIDRAM['lang']['config_general_protect_frontend'] = 'Geeft aan of de bescherming die gewoonlijk door CIDRAM is voorzien moet worden toegepast op de front-end. True = Ja [Standaard]; False = Nee.';
$CIDRAM['lang']['config_general_search_engine_verification'] = 'Poging om verzoeken van zoekmachines te bevestigen? Het verifiëren van zoekmachines zorgt ervoor dat ze niet zullen worden verboden als gevolg van het overschrijden van de overtreding limiet (verbod op zoekmachines van uw website zal meestal een negatief effect hebben op uw zoekmachine ranking, SEO, enz). Wanneer geverifieerd, zoekmachines kunnen worden geblokkeerd als per normaal, maar zal niet worden verboden. Wanneer niet geverifieerd, het is mogelijk dat zij worden verboden ten gevolge van het overschrijden van de overtreding limiet. Bovendien, het verifiëren van zoekmachines biedt bescherming tegen nep-zoekmachine aanvragen en tegen de mogelijk schadelijke entiteiten vermomd als zoekmachines (dergelijke verzoeken zal worden geblokkeerd wanneer het verifiëren van zoekmachines is ingeschakeld). True = Inschakelen het verifiëren van zoekmachines [Standaard]; False = Uitschakelen het verifiëren van zoekmachines.';
$CIDRAM['lang']['config_general_silent_mode'] = 'Moet CIDRAM stilletjes redirect geblokkeerd toegang pogingen in plaats van het weergeven van de "Toegang Geweigerd" pagina? Als ja, geef de locatie te redirect geblokkeerd toegang pogingen. Als nee, verlaat deze variabele leeg.';
$CIDRAM['lang']['config_general_timeFormat'] = 'De datum notatie gebruikt door CIDRAM. Extra opties kunnen worden toegevoegd op aanvraag.';
$CIDRAM['lang']['config_general_timeOffset'] = 'Tijdzone offset in minuten.';
$CIDRAM['lang']['config_general_timezone'] = 'Uw tijdzone.';
$CIDRAM['lang']['config_general_truncate'] = 'Trunceren logbestanden wanneer ze een bepaalde grootte bereiken? Waarde is de maximale grootte in B/KB/MB/GB/TB dat een logbestand kan groeien tot voordat het wordt getrunceerd. De standaardwaarde van 0KB schakelt truncatie uit (logbestanden kunnen onbepaald groeien). Notitie: Van toepassing op individuele logbestanden! De grootte van de logbestanden wordt niet collectief beschouwd.';
$CIDRAM['lang']['config_recaptcha_expiry'] = 'Aantal uren om reCAPTCHA instanties herinneren.';
$CIDRAM['lang']['config_recaptcha_lockip'] = 'Binden reCAPTCHA om IPs?';
$CIDRAM['lang']['config_recaptcha_lockuser'] = 'Binden reCAPTCHA om gebruikers?';
$CIDRAM['lang']['config_recaptcha_logfile'] = 'Log alle reCAPTCHA pogingen? Zo ja, geef de naam te gebruiken voor het logbestand. Zo nee, laat u deze variabele leeg.';
$CIDRAM['lang']['config_recaptcha_secret'] = 'Deze waarde moet overeenkomen met de "secret key" voor uw reCAPTCHA, die kan worden gevonden binnen de reCAPTCHA dashboard.';
$CIDRAM['lang']['config_recaptcha_sitekey'] = 'Deze waarde moet overeenkomen met de "site key" voor uw reCAPTCHA, die kan worden gevonden binnen de reCAPTCHA dashboard.';
$CIDRAM['lang']['config_recaptcha_usemode'] = 'Bepaalt hoe CIDRAM reCAPTCHA moet gebruiken (raadpleeg de documentatie).';
$CIDRAM['lang']['config_signatures_block_bogons'] = 'Blokkeren bogon/martian CIDRs? Als u verwacht aansluitingen om uw website vanuit uw lokale netwerk, vanuit localhost, of vanuit uw LAN, dit richtlijn moet worden ingesteld op false. Als u niet verwacht deze aansluitingen, dit richtlijn moet worden ingesteld op true.';
$CIDRAM['lang']['config_signatures_block_cloud'] = 'Blokkeren CIDRs geïdentificeerd als behorend tot webhosting/cloud-diensten? Als u een api te bedienen vanaf uw website of als u verwacht dat andere websites aan te sluiten op uw website, dit richtlijn moet worden ingesteld op false. Als u niet, dan, dit richtlijn moet worden ingesteld op true.';
$CIDRAM['lang']['config_signatures_block_generic'] = 'Blokkeren CIDRs algemeen aanbevolen voor blacklisting? Dit omvat alle signatures die niet zijn gemarkeerd als onderdeel van elke van de andere, meer specifieke signature categorieën.';
$CIDRAM['lang']['config_signatures_block_proxies'] = 'Blokkeren CIDRs geïdentificeerd als behorend tot proxy-services? Als u vereisen dat gebruikers kan toegang tot uw website van anonieme proxy-services, dit richtlijn moet worden ingesteld op false. Anders, als u niet nodig anonieme proxies, dit richtlijn moet worden ingesteld op true als een middel ter verbetering van de beveiliging.';
$CIDRAM['lang']['config_signatures_block_spam'] = 'Blokkeren CIDRs geïdentificeerd als zijnde hoog risico voor spam? Tenzij u problemen ondervindt wanneer u dit doet, in algemeen, dit moet altijd worden ingesteld op true.';
$CIDRAM['lang']['config_signatures_default_tracktime'] = 'Hoeveel seconden om IPs verboden door modules te volgen. Standaard = 604800 (1 week).';
$CIDRAM['lang']['config_signatures_infraction_limit'] = 'Maximum aantal overtredingen een IP mag worden gesteld voordat hij wordt verboden door IP-tracking. Standaard = 10.';
$CIDRAM['lang']['config_signatures_ipv4'] = 'Een lijst van de IPv4 signature bestanden dat CIDRAM moet proberen om te gebruiken, afgebakend door komma\'s.';
$CIDRAM['lang']['config_signatures_ipv6'] = 'Een lijst van de IPv6 signature bestanden dat CIDRAM moet proberen om te gebruiken, afgebakend door komma\'s.';
$CIDRAM['lang']['config_signatures_modules'] = 'Een lijst van module bestanden te laden na verwerking van de IPv4/IPv6 signatures, afgebakend door komma\'s.';
$CIDRAM['lang']['config_signatures_track_mode'] = 'Wanneer moet overtredingen worden gerekend? False = Wanneer IPs geblokkeerd door modules worden. True = Wanneer IPs om welke reden geblokkeerd worden.';
$CIDRAM['lang']['config_template_data_css_url'] = 'CSS-bestand URL voor aangepaste thema\'s.';
$CIDRAM['lang']['config_template_data_theme'] = 'Standaard thema om te gebruiken voor CIDRAM.';
$CIDRAM['lang']['field_activate'] = 'Activeren';
$CIDRAM['lang']['field_banned'] = 'Verboden';
$CIDRAM['lang']['field_blocked'] = 'Geblokkeerd';
$CIDRAM['lang']['field_clear'] = 'Annuleer';
$CIDRAM['lang']['field_component'] = 'Component';
$CIDRAM['lang']['field_create_new_account'] = 'Nieuw Account Creëren';
$CIDRAM['lang']['field_deactivate'] = 'Deactiveren';
$CIDRAM['lang']['field_delete_account'] = 'Account Verwijderen';
$CIDRAM['lang']['field_delete_file'] = 'Verwijder';
$CIDRAM['lang']['field_download_file'] = 'Download';
$CIDRAM['lang']['field_edit_file'] = 'Bewerk';
$CIDRAM['lang']['field_expiry'] = 'Vervaltijd';
$CIDRAM['lang']['field_file'] = 'Bestand';
$CIDRAM['lang']['field_filename'] = 'Bestandsnaam: ';
$CIDRAM['lang']['field_filetype_directory'] = 'Bestandsmap';
$CIDRAM['lang']['field_filetype_info'] = '{EXT}-Bestand';
$CIDRAM['lang']['field_filetype_unknown'] = 'Onbekend';
$CIDRAM['lang']['field_first_seen'] = 'Eerst Gezien';
$CIDRAM['lang']['field_infractions'] = 'Overtredingen';
$CIDRAM['lang']['field_install'] = 'Installeren';
$CIDRAM['lang']['field_ip_address'] = 'IP-Adres';
$CIDRAM['lang']['field_latest_version'] = 'Laatste Versie';
$CIDRAM['lang']['field_log_in'] = 'Inloggen';
$CIDRAM['lang']['field_new_name'] = 'Nieuwe naam:';
$CIDRAM['lang']['field_ok'] = 'OK';
$CIDRAM['lang']['field_options'] = 'Opties';
$CIDRAM['lang']['field_password'] = 'Wachtwoord';
$CIDRAM['lang']['field_permissions'] = 'Machtigingen';
$CIDRAM['lang']['field_range'] = 'Bereik (Eerste – Laatste)';
$CIDRAM['lang']['field_rename_file'] = 'Naam veranderen';
$CIDRAM['lang']['field_reset'] = 'Resetten';
$CIDRAM['lang']['field_set_new_password'] = 'Stel Nieuw Wachtwoord';
$CIDRAM['lang']['field_size'] = 'Totale Grootte: ';
$CIDRAM['lang']['field_size_bytes'] = 'bytes';
$CIDRAM['lang']['field_size_GB'] = 'GB';
$CIDRAM['lang']['field_size_KB'] = 'KB';
$CIDRAM['lang']['field_size_MB'] = 'MB';
$CIDRAM['lang']['field_size_TB'] = 'TB';
$CIDRAM['lang']['field_status'] = 'Toestand';
$CIDRAM['lang']['field_system_timezone'] = 'Gebruik de systeem standaard tijdzone.';
$CIDRAM['lang']['field_tracking'] = 'Tracking';
$CIDRAM['lang']['field_uninstall'] = 'Verwijderen';
$CIDRAM['lang']['field_update'] = 'Bijwerken';
$CIDRAM['lang']['field_update_all'] = 'Bijwerken Alles';
$CIDRAM['lang']['field_upload_file'] = 'Nieuw Bestand Uploaden';
$CIDRAM['lang']['field_username'] = 'Gebruikersnaam';
$CIDRAM['lang']['field_your_version'] = 'Uw Versie';
$CIDRAM['lang']['header_login'] = 'Inloggen om verder te gaan.';
$CIDRAM['lang']['label_active_config_file'] = 'Actief configuratiebestand: ';
$CIDRAM['lang']['label_cidram'] = 'CIDRAM versie gebruikt:';
$CIDRAM['lang']['label_os'] = 'Besturingssysteem gebruikt:';
$CIDRAM['lang']['label_php'] = 'PHP versie gebruikt:';
$CIDRAM['lang']['label_sapi'] = 'SAPI gebruikt:';
$CIDRAM['lang']['label_sysinfo'] = 'Systeem informatie:';
$CIDRAM['lang']['link_accounts'] = 'Accounts';
$CIDRAM['lang']['link_cidr_calc'] = 'CIDR Calculator';
$CIDRAM['lang']['link_config'] = 'Configuratie';
$CIDRAM['lang']['link_documentation'] = 'Documentatie';
$CIDRAM['lang']['link_file_manager'] = 'Bestandsbeheer';
$CIDRAM['lang']['link_home'] = 'Startpagina';
$CIDRAM['lang']['link_ip_test'] = 'IP Test';
$CIDRAM['lang']['link_ip_tracking'] = 'IP-Tracking';
$CIDRAM['lang']['link_logs'] = 'Logbestanden';
$CIDRAM['lang']['link_updates'] = 'Updates';
$CIDRAM['lang']['logs_logfile_doesnt_exist'] = 'Geselecteerde logbestand bestaat niet!';
$CIDRAM['lang']['logs_no_logfiles_available'] = 'Geen logbestanden beschikbaar.';
$CIDRAM['lang']['logs_no_logfile_selected'] = 'Geen logbestand geselecteerd.';
$CIDRAM['lang']['max_login_attempts_exceeded'] = 'Maximum aantal inlogpogingen overschreden; Toegang geweigerd.';
$CIDRAM['lang']['previewer_days'] = 'Dagen';
$CIDRAM['lang']['previewer_hours'] = 'Uur';
$CIDRAM['lang']['previewer_minutes'] = 'Minuten';
$CIDRAM['lang']['previewer_months'] = 'Maanden';
$CIDRAM['lang']['previewer_seconds'] = 'Seconden';
$CIDRAM['lang']['previewer_weeks'] = 'Weken';
$CIDRAM['lang']['previewer_years'] = 'Jaren';
$CIDRAM['lang']['punct_decimals'] = ',';
$CIDRAM['lang']['punct_thousand'] = '.';
$CIDRAM['lang']['response_accounts_already_exists'] = 'Een account bij die gebruikersnaam bestaat al!';
$CIDRAM['lang']['response_accounts_created'] = 'Account succesvol aangemaakt!';
$CIDRAM['lang']['response_accounts_deleted'] = 'Account succesvol verwijderd!';
$CIDRAM['lang']['response_accounts_doesnt_exist'] = 'Die account bestaat niet.';
$CIDRAM['lang']['response_accounts_password_updated'] = 'Wachtwoord succesvol gewijzigd!';
$CIDRAM['lang']['response_activated'] = 'Succesvol geactiveerd.';
$CIDRAM['lang']['response_activation_failed'] = 'Mislukt om te activeren!';
$CIDRAM['lang']['response_checksum_error'] = 'Checksum error! Bestand afgewezen!';
$CIDRAM['lang']['response_component_successfully_installed'] = 'Component succesvol geïnstalleerd.';
$CIDRAM['lang']['response_component_successfully_uninstalled'] = 'Component succesvol verwijderd.';
$CIDRAM['lang']['response_component_successfully_updated'] = 'Component succesvol gewijzigd.';
$CIDRAM['lang']['response_component_uninstall_error'] = 'Er is een fout opgetreden tijdens een poging om de component te verwijderen.';
$CIDRAM['lang']['response_component_update_error'] = 'Er is een fout opgetreden tijdens een poging om de component te bijwerken.';
$CIDRAM['lang']['response_configuration_updated'] = 'Configuratie succesvol gewijzigd.';
$CIDRAM['lang']['response_deactivated'] = 'Succesvol gedeactiveerd.';
$CIDRAM['lang']['response_deactivation_failed'] = 'Mislukt om te deactiveren!';
$CIDRAM['lang']['response_delete_error'] = 'Mislukt om te verwijderen!';
$CIDRAM['lang']['response_directory_deleted'] = 'Bestandsmap succesvol verwijderd!';
$CIDRAM['lang']['response_directory_renamed'] = 'De naam van de bestandsmap met succes veranderd!';
$CIDRAM['lang']['response_error'] = 'Fout';
$CIDRAM['lang']['response_file_deleted'] = 'Bestand succesvol verwijderd!';
$CIDRAM['lang']['response_file_edited'] = 'Bestand succesvol gewijzigd!';
$CIDRAM['lang']['response_file_renamed'] = 'De naam van de bestand met succes veranderd!';
$CIDRAM['lang']['response_file_uploaded'] = 'Bestand succesvol uploadet!';
$CIDRAM['lang']['response_login_invalid_password'] = 'Inloggen mislukt! Ongeldig wachtwoord!';
$CIDRAM['lang']['response_login_invalid_username'] = 'Inloggen mislukt! Gebruikersnaam bestaat niet!';
$CIDRAM['lang']['response_login_password_field_empty'] = 'Wachtwoord veld leeg!';
$CIDRAM['lang']['response_login_username_field_empty'] = 'Gebruikersnaam veld leeg!';
$CIDRAM['lang']['response_no'] = 'Nee';
$CIDRAM['lang']['response_rename_error'] = 'Mislukt om de naam te veranderen!';
$CIDRAM['lang']['response_tracking_cleared'] = 'Tracking geannuleerd.';
$CIDRAM['lang']['response_updates_already_up_to_date'] = 'Al bijgewerkt.';
$CIDRAM['lang']['response_updates_not_installed'] = 'Component niet geïnstalleerd!';
$CIDRAM['lang']['response_updates_not_installed_php'] = 'Component niet geïnstalleerd (heeft nodig PHP {V})!';
$CIDRAM['lang']['response_updates_outdated'] = 'Verouderd!';
$CIDRAM['lang']['response_updates_outdated_manually'] = 'Verouderd (neem handmatig bijwerken)!';
$CIDRAM['lang']['response_updates_outdated_php_version'] = 'Verouderd (heeft nodig PHP {V})!';
$CIDRAM['lang']['response_updates_unable_to_determine'] = 'Onbepaald.';
$CIDRAM['lang']['response_upload_error'] = 'Mislukt om te uploaden!';
$CIDRAM['lang']['response_yes'] = 'Ja';
$CIDRAM['lang']['state_complete_access'] = 'Volledige toegang';
$CIDRAM['lang']['state_component_is_active'] = 'Component is actief.';
$CIDRAM['lang']['state_component_is_inactive'] = 'Component is inactief.';
$CIDRAM['lang']['state_component_is_provisional'] = 'Component is voorlopig.';
$CIDRAM['lang']['state_default_password'] = 'Waarschuwing: Gebruikt de standaard wachtwoord!';
$CIDRAM['lang']['state_logged_in'] = 'Ingelogd.';
$CIDRAM['lang']['state_logs_access_only'] = 'Logbestanden toegang alleen';
$CIDRAM['lang']['state_password_not_valid'] = 'Waarschuwing: Dit account is niet gebruikt van een geldig wachtwoord!';
$CIDRAM['lang']['switch-hide-non-outdated-set-false'] = 'De al bijgewerkt niet verbergen';
$CIDRAM['lang']['switch-hide-non-outdated-set-true'] = 'De al bijgewerkt verbergen';
$CIDRAM['lang']['switch-hide-unused-set-false'] = 'De ongebruikte niet verbergen';
$CIDRAM['lang']['switch-hide-unused-set-true'] = 'De ongebruikte verbergen';
$CIDRAM['lang']['tip_accounts'] = 'Hallo, {username}.<br />De accounts pagina stelt u in staat om te bepalen wie toegang heeft tot de CIDRAM frontend.';
$CIDRAM['lang']['tip_cidr_calc'] = 'Hallo, {username}.<br />De CIDR calculator stelt u in staat om te berekenen welke CIDRs een IP-adres is een factor.';
$CIDRAM['lang']['tip_config'] = 'Hallo, {username}.<br />De configuratie pagina stelt u in staat om de configuratie voor CIDRAM te modificeren vanaf de frontend.';
$CIDRAM['lang']['tip_donate'] = 'CIDRAM wordt gratis aangeboden, maar als u wilt doneren aan het project, kan u dit doen door te klikken op de knop doneren.';
$CIDRAM['lang']['tip_enter_ips_here'] = 'Voer hier de IP\'s.';
$CIDRAM['lang']['tip_enter_ip_here'] = 'Vul hier een IP.';
$CIDRAM['lang']['tip_file_manager'] = 'Hallo, {username}.<br />De bestandsbeheer stelt u in staat om te verwijderen, bewerken, uploaden en downloaden van bestanden. Gebruik met voorzichtigheid (kon u uw installatie breken met deze).';
$CIDRAM['lang']['tip_home'] = 'Hallo, {username}.<br />Dit is de startpagina van de CIDRAM frontend. Selecteer een link in het navigatiemenu aan de linkerkant om door te gaan.';
$CIDRAM['lang']['tip_ip_test'] = 'Hallo, {username}.<br />De IP test pagina stelt u in staat om te testen of IP-adressen door de geïnstalleerde signatures worden geblokkeerd.';
$CIDRAM['lang']['tip_ip_tracking'] = 'Hallo, {username}.<br />Met het IP-Tracking pagina, is het mogelijk om de tracking status van IP-adressen te controleren, en u kunt zien welke zijn verboden, en om te annuleren de tracking van hen als u wilt doen.';
$CIDRAM['lang']['tip_login'] = 'Standaard gebruikersnaam: <span class="txtRd">admin</span> – Standaard wachtwoord: <span class="txtRd">password</span>';
$CIDRAM['lang']['tip_logs'] = 'Hallo, {username}.<br />Selecteer een logbestand uit de onderstaande lijst om de inhoud van de logbestand te bekijken.';
$CIDRAM['lang']['tip_see_the_documentation'] = 'Zie de <a href="https://github.com/CIDRAM/CIDRAM/blob/master/_docs/readme.nl.md#SECTION6">documentatie</a> voor informatie over de verschillende configuratie richtlijnen en hun doeleinden.';
$CIDRAM['lang']['tip_updates'] = 'Hallo, {username}.<br />De updates pagina stelt u in staat om de verschillende CIDRAM componenten te installeren, verwijderen, en actualiseren (de core pakket, signatures, L10N bestanden, ezv).';
$CIDRAM['lang']['title_accounts'] = 'CIDRAM – Accounts';
$CIDRAM['lang']['title_cidr_calc'] = 'CIDRAM – CIDR Calculator';
$CIDRAM['lang']['title_config'] = 'CIDRAM – Configuratie';
$CIDRAM['lang']['title_file_manager'] = 'CIDRAM – Bestandsbeheer';
$CIDRAM['lang']['title_home'] = 'CIDRAM – Startpagina';
$CIDRAM['lang']['title_ip_test'] = 'CIDRAM – IP Test';
$CIDRAM['lang']['title_ip_tracking'] = 'CIDRAM – IP-Tracking';
$CIDRAM['lang']['title_login'] = 'CIDRAM – Inloggen';
$CIDRAM['lang']['title_logs'] = 'CIDRAM – Logbestanden';
$CIDRAM['lang']['title_updates'] = 'CIDRAM – Updates';

$CIDRAM['lang']['info_some_useful_links'] = 'Enkele nuttige links:<ul>
            <li><a href="https://github.com/CIDRAM/CIDRAM/issues">CIDRAM Kwesties @ GitHub</a> – Kwesties pagina voor CIDRAM (steun, hulp, ezv).</li>
            <li><a href="http://www.spambotsecurity.com/forum/viewforum.php?f=61">CIDRAM @ Spambot Security</a> – Discussieforum voor CIDRAM (steun, hulp, ezv).</li>
            <li><a href="https://wordpress.org/plugins/cidram/">CIDRAM @ WordPress.org</a> – WordPress plugin voor CIDRAM.</li>
            <li><a href="https://sourceforge.net/projects/cidram/">CIDRAM @ SourceForge</a> – Een alternatieve download-spiegel voor CIDRAM.</li>
            <li><a href="https://websectools.com/">WebSecTools.com</a> – Een verzameling van eenvoudige webmaster tools waarmee websites te beveiligen.</li>
            <li><a href="https://macmathan.info/blocklists">MacMathan.info Range Blokken</a> – Bevat optionele range blokken die naar CIDRAM kunnen worden toegevoegd om de toegang van ongewenste landen tot uw website te blokkeren.</li>
            <li><a href="https://www.facebook.com/groups/2204685680/">Global PHP Group @ Facebook</a> – PHP leermiddelen en discussie.</li>
            <li><a href="https://php.earth/">PHP.earth</a> – PHP leermiddelen en discussie.</li>
            <li><a href="http://bgp.he.net/">Hurricane Electric BGP Toolkit</a> – Krijg CIDRs van ASN, bepalen ASN relaties, ontdek ASN\'s op basis van netwerknamen, ezv.</li>
            <li><a href="https://www.stopforumspam.com/forum/">Forum @ Stop Forum Spam</a> – Nuttig discussieforum over het stoppen forum spam.</li>
            <li><a href="https://www.stopforumspam.com/aggregate">IP Aggregator @ Stop Forum Spam</a> – Nuttig aggregatie tool voor IPv4 IPs.</li>
            <li><a href="https://radar.qrator.net/">Radar van Qrator</a> – Handig hulpmiddel voor het controleren van de connectiviteit van ASN\'s en ook voor diverse andere informatie over ASN\'s.</li>
            <li><a href="http://www.ipdeny.com/ipblocks/">IPdeny IP-landblokken</a> – Een fantastische en accurate service voor het genereren van de signatures voor landen.</li>
            <li><a href="https://www.google.com/transparencyreport/safebrowsing/malware/">Google Malware Dashboard</a> – Geeft rapporten over malware-infectie tarieven voor ASN.</li>
            <li><a href="https://www.spamhaus.org/statistics/botnet-asn/">Het Spamhaus Project</a> – Geeft rapporten over botnet infectie tarieven voor ASN.</li>
            <li><a href="http://www.abuseat.org/asn.html">Composite Blocking List @ Abuseat.org</a> – Geeft rapporten over botnet infectie tarieven voor ASN.</li>
            <li><a href="https://abuseipdb.com/">AbuseIPDB</a> – Onderhoudt een database van bekende beledigend IPs; Biedt een API voor het controleren en rapporteren van IPs.</li>
            <li><a href="https://www.megarbl.net/index.php">MegaRBL.net</a> – Onderhoudt lijsten van bekende spammers; Handig voor het controleren van IP/ASN spam activiteiten.</li>
        </ul>';
