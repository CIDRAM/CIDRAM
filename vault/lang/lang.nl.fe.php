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
 * This file: Dutch language data for the front-end (last modified: 2018.06.10).
 */

/** Prevents execution from outside of CIDRAM. */
if (!defined('CIDRAM')) {
    die('[CIDRAM] This should not be accessed directly.');
}

foreach (['IPv4', 'IPv6'] as $CIDRAM['IPvX']) {
    $CIDRAM['Pre'] = 'Standaard ' . $CIDRAM['IPvX'] . ' signatures normaal opgenomen met de primaire pakket. ';
    $CIDRAM['lang']['Extended Description: ' . $CIDRAM['IPvX']] = $CIDRAM['Pre'] . 'Blokkeert ongewenste cloud-diensten en niet-menselijke eindpunten.';
    $CIDRAM['lang']['Extended Description: ' . $CIDRAM['IPvX'] . '-Bogons'] = $CIDRAM['Pre'] . 'Blokkeert bogon/martian CIDR\'s.';
    $CIDRAM['lang']['Extended Description: ' . $CIDRAM['IPvX'] . '-ISPs'] = $CIDRAM['Pre'] . 'Blokkeert gevaarlijk en spammy ISP\'s.';
    $CIDRAM['lang']['Extended Description: ' . $CIDRAM['IPvX'] . '-Other'] = $CIDRAM['Pre'] . 'Blokkeert CIDR\'s voor proxies, VPN\'s, en diverse andere ongewenste diensten.';
    $CIDRAM['Pre'] = $CIDRAM['IPvX'] . ' signatures bestand (%s).';
    $CIDRAM['lang']['Name: ' . $CIDRAM['IPvX']] = sprintf($CIDRAM['Pre'], 'ongewenste cloud-diensten en niet-menselijke eindpunten');
    $CIDRAM['lang']['Name: ' . $CIDRAM['IPvX'] . '-Bogons'] = sprintf($CIDRAM['Pre'], 'bogon/martian CIDR\'s');
    $CIDRAM['lang']['Name: ' . $CIDRAM['IPvX'] . '-ISPs'] = sprintf($CIDRAM['Pre'], 'gevaarlijk en spammy ISP\'s');
    $CIDRAM['lang']['Name: ' . $CIDRAM['IPvX'] . '-Other'] = sprintf($CIDRAM['Pre'], 'CIDR\'s voor proxies, VPN\'s, en diverse andere ongewenste diensten');
}
unset($CIDRAM['Pre'], $CIDRAM['IPvX']);

$CIDRAM['lang']['Extended Description: Bypasses'] = 'De standaard signature rondwegen normaal opgenomen met de primaire pakket.';
$CIDRAM['lang']['Extended Description: CIDRAM'] = 'De primaire pakket (zonder de signatures, documentatie en configuratie).';
$CIDRAM['lang']['Extended Description: module_badhosts.php'] = 'Blokkeert hosts die vaak worden gebruikt door spammers, hackers en andere kwaadwillige entiteiten.';
$CIDRAM['lang']['Extended Description: module_badhosts_isps.php'] = 'Blokkeert hosts van ISP\'s die vaak worden gebruikt door spammers, hackers en andere kwaadwillige entiteiten.';
$CIDRAM['lang']['Extended Description: module_badtlds.php'] = 'Blokkeert hosts van TLD\'s die vaak worden gebruikt door spammers, hackers en andere kwaadwillige entiteiten.';
$CIDRAM['lang']['Extended Description: module_cookies.php'] = 'Biedt een beperkte bescherming tegen gevaarlijke cookies.';
$CIDRAM['lang']['Extended Description: module_extras.php'] = 'Biedt een beperkte bescherming tegen verschillende aanvalsvectoren vaak gebruikt in aanvragen.';
$CIDRAM['lang']['Extended Description: module_sfs.php'] = 'Beschermt registratie en login tegen IP\'s die door SFS worden vermeld.';
$CIDRAM['lang']['Name: Bypasses'] = 'Standaard signature rondwegen.';
$CIDRAM['lang']['Name: module_badhosts.php'] = 'Slechte hosts blokker module';
$CIDRAM['lang']['Name: module_badhosts_isps.php'] = 'Slechte hosts blokker module (ISP\'s)';
$CIDRAM['lang']['Name: module_badtlds.php'] = 'Slechte TLD\'s blokker module';
$CIDRAM['lang']['Name: module_baidublocker.php'] = 'Baidu blokker module';
$CIDRAM['lang']['Name: module_cookies.php'] = 'Optionele cookie-scanner module';
$CIDRAM['lang']['Name: module_extras.php'] = 'Optionele beveiliging extra module';
$CIDRAM['lang']['Name: module_sfs.php'] = 'Stop Forum Spam module';
$CIDRAM['lang']['Name: module_yandexblocker.php'] = 'Yandex blokker module';
$CIDRAM['lang']['bNav_home_logout'] = '<a href="?">Startpagina</a> | <a href="?cidram-page=logout">Uitloggen</a>';
$CIDRAM['lang']['bNav_logout'] = '<a href="?cidram-page=logout">Uitloggen</a>';
$CIDRAM['lang']['config_general_FrontEndLog'] = 'Bestand om de frontend login pogingen te loggen. Geef een bestandsnaam, of laat leeg om uit te schakelen.';
$CIDRAM['lang']['config_general_allow_gethostbyaddr_lookup'] = 'Zoeken op gethostbyaddr toestaan als UDP niet beschikbaar is? True = Ja [Standaard]; False = Nee.';
$CIDRAM['lang']['config_general_ban_override'] = 'Overrijden "forbid_on_block" wanneer "infraction_limit" wordt overschreden? Wanneer het overrijdt: Geblokkeerde verzoeken retourneert een lege pagina (template bestanden worden niet gebruikt). 200 = Niet overrijden [Standaard]. Andere waarden zijn hetzelfde als de beschikbare waarden voor "forbid_on_block".';
$CIDRAM['lang']['config_general_default_algo'] = 'Definieert welk algoritme u wilt gebruiken voor alle toekomstige wachtwoorden en sessies. Opties: PASSWORD_DEFAULT (standaard), PASSWORD_BCRYPT, PASSWORD_ARGON2I (vereist PHP &gt;= 7.2.0).';
$CIDRAM['lang']['config_general_default_dns'] = 'Een door komma\'s gescheiden lijst met DNS-servers te gebruiken voor de hostnaam lookups. Standaard = "8.8.8.8,8.8.4.4" (Google DNS). WAARSCHUWING: Verander dit niet tenzij u weet wat u doet!';
$CIDRAM['lang']['config_general_disable_cli'] = 'Uitschakelen CLI-modus? CLI-modus is standaard ingeschakeld, maar kunt somtijds interfereren met bepaalde testtools (zoals PHPUnit bijvoorbeeld) en andere CLI-gebaseerde applicaties. Als u niet hoeft te uitschakelen CLI-modus, u moeten om dit richtlijn te negeren. False = Inschakelen CLI-modus [Standaard]; True = Uitschakelen CLI-modus.';
$CIDRAM['lang']['config_general_disable_frontend'] = 'Uitschakelen frontend toegang? frontend toegang kan CIDRAM beter beheersbaar te maken, maar kan ook een potentieel gevaar voor de veiligheid zijn. Het is aan te raden om CIDRAM te beheren via het backend wanneer mogelijk, maar frontend toegang is hier voorzien voor wanneer het niet mogelijk is. Hebben het uitgeschakeld tenzij u het nodig hebt. False = Inschakelen frontend toegang; True = Uitschakelen frontend toegang [Standaard].';
$CIDRAM['lang']['config_general_disable_webfonts'] = 'Uitschakelen webfonts? True = Ja [Standaard]; False = Nee.';
$CIDRAM['lang']['config_general_emailaddr'] = 'Indien u wenst, u kunt een e-mailadres op hier te geven te geven aan de gebruikers als ze geblokkeerd, voor hen te gebruiken als aanspreekpunt voor steun en/of assistentie in het geval dat ze worden onrechte geblokkeerd. WAARSCHUWING: Elke e-mailadres u leveren hier zal zeker worden overgenomen met spambots en schrapers in de loop van zijn wezen die hier gebruikt, en dus, het wordt ten zeerste aanbevolen als u ervoor kiest om een e-mailadres hier te leveren, dat u ervoor zorgen dat het e-mailadres dat u hier leveren is een wegwerp-adres en/of een adres dat u niet de zorg over wordt gespamd (met andere woorden, u waarschijnlijk niet wilt om uw primaire persoonlijk of primaire zakelijke e-mailadressen te gebruik).';
$CIDRAM['lang']['config_general_emailaddr_display_style'] = 'Hoe zou u het e-mailadres voor gebruikers willen aanbieden?';
$CIDRAM['lang']['config_general_forbid_on_block'] = 'Welk HTTP-statusbericht moet CIDRAM verzenden bij het blokkeren van verzoeken? (Raadpleeg de documentatie voor meer informatie).';
$CIDRAM['lang']['config_general_force_hostname_lookup'] = 'Hostname-opzoekingen afdwingen? True = Ja; False = Nee [Standaard]. Hostname-opzoekingen worden normaal uitgevoerd op basis van noodzaak, maar kan voor alle verzoeken worden gedwongen. Dit kan nuttig zijn als een middel om meer gedetailleerde informatie in de logbestanden te verstrekken, maar kan ook een licht negatief effect hebben op de prestaties.';
$CIDRAM['lang']['config_general_hide_version'] = 'Versleutelingsinformatie uit logs en pagina-uitvoer verbergen? True = Ja; False = Nee [Standaard].';
$CIDRAM['lang']['config_general_ipaddr'] = 'Waar het IP-adres van het aansluiten verzoek te vinden? (Handig voor diensten zoals Cloudflare en dergelijke). Standaard = REMOTE_ADDR. WAARSCHUWING: Verander dit niet tenzij u weet wat u doet!';
$CIDRAM['lang']['config_general_lang'] = 'Geef de standaardtaal voor CIDRAM.';
$CIDRAM['lang']['config_general_log_banned_ips'] = 'Omvatten geblokkeerde verzoeken van verboden IP-adressen in de logbestanden? True = Ja [Standaard]; False = Nee.';
$CIDRAM['lang']['config_general_log_rotation_action'] = 'Logrotatie beperkt het aantal logbestanden dat op elk moment zou moeten bestaan. Wanneer nieuwe logbestanden worden gemaakt en het totale aantal logbestanden de opgegeven limiet overschrijdt, wordt de opgegeven actie uitgevoerd. U kunt hier de gewenste actie opgeven. Delete = Verwijder de oudste logbestanden, totdat de limiet niet langer wordt overschreden. Archive = Eerst archiveer en verwijder vervolgens de oudste logbestanden, totdat de limiet niet langer wordt overschreden.';
$CIDRAM['lang']['config_general_log_rotation_limit'] = 'Logrotatie beperkt het aantal logbestanden dat op elk moment zou moeten bestaan. Wanneer nieuwe logbestanden worden gemaakt en het totale aantal logbestanden de opgegeven limiet overschrijdt, wordt de opgegeven actie uitgevoerd. U kunt hier de gewenste limiet opgeven. Een waarde van 0 zal logrotatie uitschakelen.';
$CIDRAM['lang']['config_general_logfile'] = 'Mensen leesbare bestand om alle geblokkeerde toegang pogingen te loggen. Geef een bestandsnaam, of laat leeg om uit te schakelen.';
$CIDRAM['lang']['config_general_logfileApache'] = 'Apache-stijl bestand om alle geblokkeerde toegang pogingen te loggen. Geef een bestandsnaam, of laat leeg om uit te schakelen.';
$CIDRAM['lang']['config_general_logfileSerialized'] = 'Geserialiseerd bestand om alle geblokkeerde toegang pogingen te loggen. Geef een bestandsnaam, of laat leeg om uit te schakelen.';
$CIDRAM['lang']['config_general_maintenance_mode'] = 'Inschakelen de onderhoudsmodus? True = Ja; False = Nee [Standaard]. Schakelt alles anders dan het frontend uit. Soms nuttig bij het bijwerken van uw CMS, frameworks, enz.';
$CIDRAM['lang']['config_general_max_login_attempts'] = 'Maximum aantal inlogpogingen.';
$CIDRAM['lang']['config_general_numbers'] = 'Hoe verkiest u nummers die worden weergegeven? Selecteer het voorbeeld dat het meest correct voor u lijkt.';
$CIDRAM['lang']['config_general_protect_frontend'] = 'Geeft aan of de bescherming die gewoonlijk door CIDRAM is voorzien moet worden toegepast op de frontend. True = Ja [Standaard]; False = Nee.';
$CIDRAM['lang']['config_general_search_engine_verification'] = 'Poging om verzoeken van zoekmachines te bevestigen? Het verifiëren van zoekmachines zorgt ervoor dat ze niet zullen worden verboden als gevolg van het overschrijden van de overtreding limiet (verbod op zoekmachines van uw website zal meestal een negatief effect hebben op uw zoekmachine ranking, SEO, enz). Wanneer geverifieerd, zoekmachines kunnen worden geblokkeerd als per normaal, maar zal niet worden verboden. Wanneer niet geverifieerd, het is mogelijk dat zij worden verboden ten gevolge van het overschrijden van de overtreding limiet. Bovendien, het verifiëren van zoekmachines biedt bescherming tegen nep-zoekmachine aanvragen en tegen de mogelijk schadelijke entiteiten vermomd als zoekmachines (dergelijke verzoeken zal worden geblokkeerd wanneer het verifiëren van zoekmachines is ingeschakeld). True = Inschakelen het verifiëren van zoekmachines [Standaard]; False = Uitschakelen het verifiëren van zoekmachines.';
$CIDRAM['lang']['config_general_silent_mode'] = 'Moet CIDRAM stilletjes redirect geblokkeerd toegang pogingen in plaats van het weergeven van de "Toegang Geweigerd" pagina? Als ja, geef de locatie te redirect geblokkeerd toegang pogingen. Als nee, verlaat deze variabele leeg.';
$CIDRAM['lang']['config_general_statistics'] = 'Track CIDRAM gebruiksstatistieken? True = Ja; False = Nee [Standaard].';
$CIDRAM['lang']['config_general_timeFormat'] = 'De datum notatie gebruikt door CIDRAM. Extra opties kunnen worden toegevoegd op aanvraag.';
$CIDRAM['lang']['config_general_timeOffset'] = 'Tijdzone offset in minuten.';
$CIDRAM['lang']['config_general_timezone'] = 'Uw tijdzone.';
$CIDRAM['lang']['config_general_truncate'] = 'Trunceren logbestanden wanneer ze een bepaalde grootte bereiken? Waarde is de maximale grootte in B/KB/MB/GB/TB dat een logbestand kan groeien tot voordat het wordt getrunceerd. De standaardwaarde van 0KB schakelt truncatie uit (logbestanden kunnen onbepaald groeien). Notitie: Van toepassing op individuele logbestanden! De grootte van de logbestanden wordt niet collectief beschouwd.';
$CIDRAM['lang']['config_legal_omit_hostname'] = 'Hostnamen uit logbestanden weglaten? True = Ja; False = Nee [Standaard].';
$CIDRAM['lang']['config_legal_omit_ip'] = 'IP-adressen uit logbestanden weglaten? True = Ja; False = Nee [Standaard]. Opmerking: "pseudonymise_ip_addresses" wordt overbodig zijn wanneer "omit_ip" "true" is.';
$CIDRAM['lang']['config_legal_omit_ua'] = 'Gebruikersagenten uit logbestanden weglaten? True = Ja; False = Nee [Standaard].';
$CIDRAM['lang']['config_legal_privacy_policy'] = 'Het adres van een relevant privacybeleid dat moet worden weergegeven in de voettekst van eventuele gegenereerde pagina\'s. Geef een URL, of laat leeg om uit te schakelen.';
$CIDRAM['lang']['config_legal_pseudonymise_ip_addresses'] = 'Pseudonimiseren de IP-adressen bij het schrijven van logbestanden? True = Ja; False = Nee [Standaard].';
$CIDRAM['lang']['config_recaptcha_api'] = 'Welke API gebruiken? V2 of invisible?';
$CIDRAM['lang']['config_recaptcha_expiry'] = 'Aantal uren om reCAPTCHA instanties herinneren.';
$CIDRAM['lang']['config_recaptcha_lockip'] = 'Binden reCAPTCHA om IP\'s?';
$CIDRAM['lang']['config_recaptcha_lockuser'] = 'Binden reCAPTCHA om gebruikers?';
$CIDRAM['lang']['config_recaptcha_logfile'] = 'Log alle reCAPTCHA pogingen? Zo ja, geef de naam te gebruiken voor het logbestand. Zo nee, laat u deze variabele leeg.';
$CIDRAM['lang']['config_recaptcha_secret'] = 'Deze waarde moet overeenkomen met de "secret key" voor uw reCAPTCHA, die kan worden gevonden binnen de reCAPTCHA dashboard.';
$CIDRAM['lang']['config_recaptcha_signature_limit'] = 'Maximale aantal signatures dat kan worden veroorzaakt wanneer een reCAPTCHA-instantie wordt aangeboden. Standaard = 1. Als dit aantal wordt overschreden voor een bepaald verzoek, wordt er geen reCAPTCHA-instantie aangeboden.';
$CIDRAM['lang']['config_recaptcha_sitekey'] = 'Deze waarde moet overeenkomen met de "site key" voor uw reCAPTCHA, die kan worden gevonden binnen de reCAPTCHA dashboard.';
$CIDRAM['lang']['config_recaptcha_usemode'] = 'Bepaalt hoe CIDRAM reCAPTCHA moet gebruiken (raadpleeg de documentatie).';
$CIDRAM['lang']['config_signatures_block_bogons'] = 'Blokkeren bogon/martian CIDR\'s? Als u verwacht aansluitingen om uw website vanuit uw lokale netwerk, vanuit localhost, of vanuit uw LAN, dit richtlijn moet worden ingesteld op false. Als u niet verwacht deze aansluitingen, dit richtlijn moet worden ingesteld op true.';
$CIDRAM['lang']['config_signatures_block_cloud'] = 'Blokkeren CIDR\'s geïdentificeerd als behorend tot webhosting/cloud-diensten? Als u een api te bedienen vanaf uw website of als u verwacht dat andere websites aan te sluiten op uw website, dit richtlijn moet worden ingesteld op false. Als u niet, dan, dit richtlijn moet worden ingesteld op true.';
$CIDRAM['lang']['config_signatures_block_generic'] = 'Blokkeren CIDR\'s algemeen aanbevolen voor blacklisting? Dit omvat alle signatures die niet zijn gemarkeerd als onderdeel van elke van de andere, meer specifieke signature categorieën.';
$CIDRAM['lang']['config_signatures_block_legal'] = 'Blokkeren CIDR\'s als reactie op wettelijke verplichtingen? Dit richtlijn zou normaal gesproken geen effect moeten hebben, omdat CIDRAM als standaard geen CIDR\'s met "wettelijke verplichtingen" associeert, maar het bestaat niettemin als een extra beheersmaatregel ten behoeve van eventuele aangepaste signatures bestanden of  modules die mogelijk bestaan om wettelijke redenen.';
$CIDRAM['lang']['config_signatures_block_malware'] = 'Blokkeren IP\'s die zijn gekoppeld aan malware? Dit omvat C&C-servers, geïnfecteerde machines, machines die betrokken zijn bij de distributie van malware, enz.';
$CIDRAM['lang']['config_signatures_block_proxies'] = 'Blokkeren CIDR\'s geïdentificeerd als behorend tot proxy-services of VPN\'s? Als u vereisen dat gebruikers kan toegang tot uw website van proxy-services en VPN\'s, dit richtlijn moet worden ingesteld op false. Anders, als u geen proxy-services of VPN\'s nodig, dit richtlijn moet worden ingesteld op true als een middel ter verbetering van de beveiliging.';
$CIDRAM['lang']['config_signatures_block_spam'] = 'Blokkeren CIDR\'s geïdentificeerd als zijnde hoog risico voor spam? Tenzij u problemen ondervindt wanneer u dit doet, in algemeen, dit moet altijd worden ingesteld op true.';
$CIDRAM['lang']['config_signatures_default_tracktime'] = 'Hoeveel seconden om IP\'s verboden door modules te volgen. Standaard = 604800 (1 week).';
$CIDRAM['lang']['config_signatures_infraction_limit'] = 'Maximum aantal overtredingen een IP mag worden gesteld voordat hij wordt verboden door IP-tracking. Standaard = 10.';
$CIDRAM['lang']['config_signatures_ipv4'] = 'Een lijst van de IPv4 signature bestanden dat CIDRAM moet proberen om te gebruiken, afgebakend door komma\'s.';
$CIDRAM['lang']['config_signatures_ipv6'] = 'Een lijst van de IPv6 signature bestanden dat CIDRAM moet proberen om te gebruiken, afgebakend door komma\'s.';
$CIDRAM['lang']['config_signatures_modules'] = 'Een lijst van module bestanden te laden na verwerking van de IPv4/IPv6 signatures, afgebakend door komma\'s.';
$CIDRAM['lang']['config_signatures_track_mode'] = 'Wanneer moet overtredingen worden gerekend? False = Wanneer IP\'s geblokkeerd door modules worden. True = Wanneer IP\'s om welke reden geblokkeerd worden.';
$CIDRAM['lang']['config_template_data_Magnification'] = 'Lettergrootte vergroting. Standaard = 1.';
$CIDRAM['lang']['config_template_data_css_url'] = 'CSS-bestand URL voor aangepaste thema\'s.';
$CIDRAM['lang']['config_template_data_theme'] = 'Standaard thema om te gebruiken voor CIDRAM.';
$CIDRAM['lang']['confirm_action'] = 'Weet u zeker dat u wilt "%s"?';
$CIDRAM['lang']['field_activate'] = 'Activeren';
$CIDRAM['lang']['field_banned'] = 'Verboden';
$CIDRAM['lang']['field_blocked'] = 'Geblokkeerd';
$CIDRAM['lang']['field_clear'] = 'Annuleer';
$CIDRAM['lang']['field_clear_all'] = 'Annuleer alles';
$CIDRAM['lang']['field_clickable_link'] = 'Klikbare link';
$CIDRAM['lang']['field_component'] = 'Component';
$CIDRAM['lang']['field_create_new_account'] = 'Nieuw Account Creëren';
$CIDRAM['lang']['field_deactivate'] = 'Deactiveren';
$CIDRAM['lang']['field_delete_account'] = 'Account Verwijderen';
$CIDRAM['lang']['field_delete_file'] = 'Verwijder';
$CIDRAM['lang']['field_download_file'] = 'Download';
$CIDRAM['lang']['field_edit_file'] = 'Bewerk';
$CIDRAM['lang']['field_expiry'] = 'Vervaltijd';
$CIDRAM['lang']['field_false'] = 'False (Vals)';
$CIDRAM['lang']['field_file'] = 'Bestand';
$CIDRAM['lang']['field_filename'] = 'Bestandsnaam: ';
$CIDRAM['lang']['field_filetype_directory'] = 'Bestandsmap';
$CIDRAM['lang']['field_filetype_info'] = '{EXT}-Bestand';
$CIDRAM['lang']['field_filetype_unknown'] = 'Onbekend';
$CIDRAM['lang']['field_infractions'] = 'Overtredingen';
$CIDRAM['lang']['field_install'] = 'Installeren';
$CIDRAM['lang']['field_ip_address'] = 'IP-Adres';
$CIDRAM['lang']['field_latest_version'] = 'Laatste Versie';
$CIDRAM['lang']['field_log_in'] = 'Inloggen';
$CIDRAM['lang']['field_new_name'] = 'Nieuwe naam:';
$CIDRAM['lang']['field_nonclickable_text'] = 'Niet-klikbare tekst';
$CIDRAM['lang']['field_ok'] = 'OK';
$CIDRAM['lang']['field_options'] = 'Opties';
$CIDRAM['lang']['field_password'] = 'Wachtwoord';
$CIDRAM['lang']['field_permissions'] = 'Machtigingen';
$CIDRAM['lang']['field_range'] = 'Bereik (Eerste – Laatste)';
$CIDRAM['lang']['field_rename_file'] = 'Naam veranderen';
$CIDRAM['lang']['field_reset'] = 'Resetten';
$CIDRAM['lang']['field_set_new_password'] = 'Stel Nieuw Wachtwoord';
$CIDRAM['lang']['field_size'] = 'Totale Grootte: ';
$CIDRAM['lang']['field_size_GB'] = 'GB';
$CIDRAM['lang']['field_size_KB'] = 'KB';
$CIDRAM['lang']['field_size_MB'] = 'MB';
$CIDRAM['lang']['field_size_TB'] = 'TB';
$CIDRAM['lang']['field_size_bytes'] = ['byte', 'bytes'];
$CIDRAM['lang']['field_status'] = 'Toestand';
$CIDRAM['lang']['field_system_timezone'] = 'Gebruik de systeem standaard tijdzone.';
$CIDRAM['lang']['field_tracking'] = 'Tracking';
$CIDRAM['lang']['field_true'] = 'True (Waar)';
$CIDRAM['lang']['field_uninstall'] = 'Verwijderen';
$CIDRAM['lang']['field_update'] = 'Bijwerken';
$CIDRAM['lang']['field_update_all'] = 'Bijwerken alles';
$CIDRAM['lang']['field_upload_file'] = 'Nieuw bestand uploaden';
$CIDRAM['lang']['field_username'] = 'Gebruikersnaam';
$CIDRAM['lang']['field_verify'] = 'Verifiëren';
$CIDRAM['lang']['field_verify_all'] = 'Verifiëren alles';
$CIDRAM['lang']['field_your_version'] = 'Uw Versie';
$CIDRAM['lang']['header_login'] = 'Inloggen om verder te gaan.';
$CIDRAM['lang']['label_active_config_file'] = 'Actief configuratiebestand: ';
$CIDRAM['lang']['label_actual'] = 'Actueel';
$CIDRAM['lang']['label_backup_location'] = 'Repository backup locaties (in geval van nood, of als al het andere faalt):';
$CIDRAM['lang']['label_banned'] = 'Verzoeken verboden';
$CIDRAM['lang']['label_blocked'] = 'Verzoeken geblokkeerd';
$CIDRAM['lang']['label_branch'] = 'Branch laatste stabiele:';
$CIDRAM['lang']['label_check_modules'] = 'Test ook tegen modules.';
$CIDRAM['lang']['label_cidram'] = 'CIDRAM versie gebruikt:';
$CIDRAM['lang']['label_clientinfo'] = 'Gebruikers informatie:';
$CIDRAM['lang']['label_displaying'] = ['<span class="txtRd">%s</span> item weergeven.', '<span class="txtRd">%s</span> items weergeven.'];
$CIDRAM['lang']['label_displaying_that_cite'] = ['<span class="txtRd">%1$s</span> item weergeven dat "%2$s" citeert.', '<span class="txtRd">%1$s</span> items weergeven dat "%2$s" citeren.'];
$CIDRAM['lang']['label_expected'] = 'Verwacht';
$CIDRAM['lang']['label_expires'] = 'Verloopt: ';
$CIDRAM['lang']['label_false_positive_risk'] = 'Vals positieve risico: ';
$CIDRAM['lang']['label_fmgr_cache_data'] = 'Cache data en tijdelijke bestanden';
$CIDRAM['lang']['label_fmgr_disk_usage'] = 'CIDRAM-schijfgebruik: ';
$CIDRAM['lang']['label_fmgr_free_space'] = 'Vrije schijfruimte: ';
$CIDRAM['lang']['label_fmgr_total_disk_usage'] = 'Totaal schijfgebruik: ';
$CIDRAM['lang']['label_fmgr_total_space'] = 'Totale schijfruimte: ';
$CIDRAM['lang']['label_fmgr_updates_metadata'] = 'Component updates metadata';
$CIDRAM['lang']['label_hide'] = 'Verbergen';
$CIDRAM['lang']['label_hide_hash_table'] = 'Verberg hash-tabel';
$CIDRAM['lang']['label_never'] = 'Nooit';
$CIDRAM['lang']['label_os'] = 'Besturingssysteem gebruikt:';
$CIDRAM['lang']['label_other'] = 'Anders';
$CIDRAM['lang']['label_other-ActiveIPv4'] = 'Actieve IPv4 signature bestanden';
$CIDRAM['lang']['label_other-ActiveIPv6'] = 'Actieve IPv6 signature bestanden';
$CIDRAM['lang']['label_other-ActiveModules'] = 'Actieve modules';
$CIDRAM['lang']['label_other-Since'] = 'Begin datum';
$CIDRAM['lang']['label_php'] = 'PHP versie gebruikt:';
$CIDRAM['lang']['label_reCAPTCHA'] = 'reCAPTCHA pogingen';
$CIDRAM['lang']['label_results'] = 'Resultaten (%s in – %s verworpen – %s aanvaard – %s samengevoegd – %s uit):';
$CIDRAM['lang']['label_sapi'] = 'SAPI gebruikt:';
$CIDRAM['lang']['label_show'] = 'Zien';
$CIDRAM['lang']['label_show_hash_table'] = 'Toon hash-tabel';
$CIDRAM['lang']['label_signature_type'] = 'Signature type:';
$CIDRAM['lang']['label_stable'] = 'Laatste stabiele:';
$CIDRAM['lang']['label_sysinfo'] = 'Systeem informatie:';
$CIDRAM['lang']['label_tests'] = 'Testen:';
$CIDRAM['lang']['label_total'] = 'Totaal';
$CIDRAM['lang']['label_unstable'] = 'Laatste onstabiele:';
$CIDRAM['lang']['label_used_with'] = 'Gebruikt met: ';
$CIDRAM['lang']['label_your_ip'] = 'Je IP:';
$CIDRAM['lang']['label_your_ua'] = 'Je UA:';
$CIDRAM['lang']['link_accounts'] = 'Accounts';
$CIDRAM['lang']['link_cache_data'] = 'Cachegegevens';
$CIDRAM['lang']['link_cidr_calc'] = 'CIDR Calculator';
$CIDRAM['lang']['link_config'] = 'Configuratie';
$CIDRAM['lang']['link_documentation'] = 'Documentatie';
$CIDRAM['lang']['link_file_manager'] = 'Bestandsbeheer';
$CIDRAM['lang']['link_home'] = 'Startpagina';
$CIDRAM['lang']['link_ip_aggregator'] = 'IP-Aggregator';
$CIDRAM['lang']['link_ip_test'] = 'IP Test';
$CIDRAM['lang']['link_ip_tracking'] = 'IP-Tracking';
$CIDRAM['lang']['link_logs'] = 'Logbestanden';
$CIDRAM['lang']['link_range'] = 'Reeks Tafels';
$CIDRAM['lang']['link_sections_list'] = 'Sectielijst';
$CIDRAM['lang']['link_statistics'] = 'Statistieken';
$CIDRAM['lang']['link_textmode'] = 'Tekstformaat: <a href="%1$sfalse%2$s">Eenvoudig</a> – <a href="%1$strue%2$s">Geformatteerde</a> – <a href="%1$stally%2$s">Telling</a>';
$CIDRAM['lang']['link_updates'] = 'Updates';
$CIDRAM['lang']['logs_logfile_doesnt_exist'] = 'Geselecteerde logbestand bestaat niet!';
$CIDRAM['lang']['logs_no_logfile_selected'] = 'Geen logbestand geselecteerd.';
$CIDRAM['lang']['logs_no_logfiles_available'] = 'Geen logbestanden beschikbaar.';
$CIDRAM['lang']['max_login_attempts_exceeded'] = 'Maximum aantal inlogpogingen overschreden; Toegang geweigerd.';
$CIDRAM['lang']['previewer_days'] = 'Dagen';
$CIDRAM['lang']['previewer_hours'] = 'Uur';
$CIDRAM['lang']['previewer_minutes'] = 'Minuten';
$CIDRAM['lang']['previewer_months'] = 'Maanden';
$CIDRAM['lang']['previewer_seconds'] = 'Seconden';
$CIDRAM['lang']['previewer_weeks'] = 'Weken';
$CIDRAM['lang']['previewer_years'] = 'Jaren';
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
$CIDRAM['lang']['response_configuration_updated'] = 'Configuratie succesvol gewijzigd.';
$CIDRAM['lang']['response_deactivated'] = 'Succesvol gedeactiveerd.';
$CIDRAM['lang']['response_deactivation_failed'] = 'Mislukt om te deactiveren!';
$CIDRAM['lang']['response_delete_error'] = 'Mislukt om te verwijderen!';
$CIDRAM['lang']['response_directory_deleted'] = 'Bestandsmap succesvol verwijderd!';
$CIDRAM['lang']['response_directory_renamed'] = 'De naam van de bestandsmap met succes veranderd!';
$CIDRAM['lang']['response_error'] = 'Fout';
$CIDRAM['lang']['response_failed_to_install'] = 'Installatie mislukt!';
$CIDRAM['lang']['response_failed_to_update'] = 'Update mislukt!';
$CIDRAM['lang']['response_file_deleted'] = 'Bestand succesvol verwijderd!';
$CIDRAM['lang']['response_file_edited'] = 'Bestand succesvol gewijzigd!';
$CIDRAM['lang']['response_file_renamed'] = 'De naam van de bestand met succes veranderd!';
$CIDRAM['lang']['response_file_uploaded'] = 'Bestand succesvol uploadet!';
$CIDRAM['lang']['response_login_invalid_password'] = 'Inloggen mislukt! Ongeldig wachtwoord!';
$CIDRAM['lang']['response_login_invalid_username'] = 'Inloggen mislukt! Gebruikersnaam bestaat niet!';
$CIDRAM['lang']['response_login_password_field_empty'] = 'Wachtwoord veld leeg!';
$CIDRAM['lang']['response_login_username_field_empty'] = 'Gebruikersnaam veld leeg!';
$CIDRAM['lang']['response_login_wrong_endpoint'] = 'Verkeerd eindpunt!';
$CIDRAM['lang']['response_no'] = 'Nee';
$CIDRAM['lang']['response_possible_problem_found'] = 'Mogelijk probleem gevonden.';
$CIDRAM['lang']['response_rename_error'] = 'Mislukt om de naam te veranderen!';
$CIDRAM['lang']['response_sanity_1'] = 'Bestand bevat onverwachte inhoud! Bestand afgewezen!';
$CIDRAM['lang']['response_statistics_cleared'] = 'Statistieken geannuleerd.';
$CIDRAM['lang']['response_tracking_cleared'] = 'Tracking geannuleerd.';
$CIDRAM['lang']['response_updates_already_up_to_date'] = 'Al bijgewerkt.';
$CIDRAM['lang']['response_updates_not_installed'] = 'Component niet geïnstalleerd!';
$CIDRAM['lang']['response_updates_not_installed_php'] = 'Component niet geïnstalleerd (heeft nodig PHP &gt;= {V})!';
$CIDRAM['lang']['response_updates_outdated'] = 'Verouderd!';
$CIDRAM['lang']['response_updates_outdated_manually'] = 'Verouderd (neem handmatig bijwerken)!';
$CIDRAM['lang']['response_updates_outdated_php_version'] = 'Verouderd (heeft nodig PHP &gt;= {V})!';
$CIDRAM['lang']['response_updates_unable_to_determine'] = 'Onbepaald.';
$CIDRAM['lang']['response_upload_error'] = 'Mislukt om te uploaden!';
$CIDRAM['lang']['response_verification_failed'] = 'Verificatie mislukt! Component kan beschadigd zijn.';
$CIDRAM['lang']['response_verification_success'] = 'Verificatie succes! Geen problemen gevonden.';
$CIDRAM['lang']['response_yes'] = 'Ja';
$CIDRAM['lang']['state_async_deny'] = 'Machtigingen niet geschikt om asynchrone verzoeken uit te voeren. Probeer opnieuw in te loggen.';
$CIDRAM['lang']['state_cache_is_empty'] = 'De cache is leeg.';
$CIDRAM['lang']['state_complete_access'] = 'Volledige toegang';
$CIDRAM['lang']['state_component_is_active'] = 'Component is actief.';
$CIDRAM['lang']['state_component_is_inactive'] = 'Component is inactief.';
$CIDRAM['lang']['state_component_is_provisional'] = 'Component is voorlopig.';
$CIDRAM['lang']['state_default_password'] = 'Waarschuwing: Gebruikt de standaard wachtwoord!';
$CIDRAM['lang']['state_ignored'] = 'Genegeerd';
$CIDRAM['lang']['state_loading'] = 'Bezig met laden...';
$CIDRAM['lang']['state_loadtime'] = 'Paginaverzoek voltooid in <span class="txtRd">%s</span> seconden.';
$CIDRAM['lang']['state_logged_in'] = 'Ingelogd.';
$CIDRAM['lang']['state_logs_access_only'] = 'Logbestanden toegang alleen';
$CIDRAM['lang']['state_maintenance_mode'] = 'Waarschuwing: De onderhoudsmodus is ingeschakeld!';
$CIDRAM['lang']['state_password_not_valid'] = 'Waarschuwing: Dit account is niet gebruikt van een geldig wachtwoord!';
$CIDRAM['lang']['state_risk_high'] = 'Hoog';
$CIDRAM['lang']['state_risk_low'] = 'Laag';
$CIDRAM['lang']['state_risk_medium'] = 'Middelgroot';
$CIDRAM['lang']['state_sl_totals'] = 'Totalen (Signatures: <span class="txtRd">%s</span> – Signature secties: <span class="txtRd">%s</span> – Signature bestanden: <span class="txtRd">%s</span>).';
$CIDRAM['lang']['state_tracking'] = ['Momenteel controleren %s IP.', 'Momenteel controleren %s IP\'s.'];
$CIDRAM['lang']['switch-hide-non-outdated-set-false'] = 'De al bijgewerkt niet verbergen';
$CIDRAM['lang']['switch-hide-non-outdated-set-true'] = 'De al bijgewerkt verbergen';
$CIDRAM['lang']['switch-hide-unused-set-false'] = 'De ongebruikte niet verbergen';
$CIDRAM['lang']['switch-hide-unused-set-true'] = 'De ongebruikte verbergen';
$CIDRAM['lang']['switch-tracking-blocked-already-set-false'] = 'Controleer niet tegen signature bestanden';
$CIDRAM['lang']['switch-tracking-blocked-already-set-true'] = 'Controleer tegen signature bestanden';
$CIDRAM['lang']['switch-tracking-hide-banned-blocked-set-false'] = 'Verberg verboden/geblokkeerde IP\'s niet';
$CIDRAM['lang']['switch-tracking-hide-banned-blocked-set-true'] = 'Verberg verboden/geblokkeerde IP\'s';
$CIDRAM['lang']['tip_accounts'] = 'Hallo, {username}.<br />De accounts pagina stelt u in staat om te bepalen wie toegang heeft tot de CIDRAM frontend.';
$CIDRAM['lang']['tip_cache_data'] = 'Hallo, {username}.<br />Hier kunt u de inhoud van de cache bekijken.';
$CIDRAM['lang']['tip_cidr_calc'] = 'Hallo, {username}.<br />De CIDR calculator stelt u in staat om te berekenen welke CIDR\'s een IP-adres is een factor.';
$CIDRAM['lang']['tip_config'] = 'Hallo, {username}.<br />De configuratie pagina stelt u in staat om de configuratie voor CIDRAM te modificeren vanaf de frontend.';
$CIDRAM['lang']['tip_custom_ua'] = 'Voer hier user agent in (optioneel).';
$CIDRAM['lang']['tip_donate'] = 'CIDRAM wordt gratis aangeboden, maar als u wilt doneren aan het project, kan u dit doen door te klikken op de knop doneren.';
$CIDRAM['lang']['tip_enter_ip_here'] = 'Vul hier een IP.';
$CIDRAM['lang']['tip_enter_ips_here'] = 'Voer hier de IP\'s.';
$CIDRAM['lang']['tip_fe_cookie_warning'] = 'Notitie: CIDRAM gebruikt een cookie om aanmeldingen te verifiëren. Door in te loggen, geeft u uw toestemming voor het maken en opslaan van een cookie door uw browser.';
$CIDRAM['lang']['tip_file_manager'] = 'Hallo, {username}.<br />De bestandsbeheer stelt u in staat om te verwijderen, bewerken, uploaden en downloaden van bestanden. Gebruik met voorzichtigheid (kon u uw installatie breken met deze).';
$CIDRAM['lang']['tip_home'] = 'Hallo, {username}.<br />Dit is de startpagina van de CIDRAM frontend. Selecteer een link in het navigatiemenu aan de linkerkant om door te gaan.';
$CIDRAM['lang']['tip_ip_aggregator'] = 'Hallo, {username}.<br />Met de IP-aggregator kunt u IP\'s en CIDR\'s zo de kleinste mogelijke weg uitdrukken. Voer de gegevens in die moeten worden geaggregeerd en druk op "OK".';
$CIDRAM['lang']['tip_ip_test'] = 'Hallo, {username}.<br />De IP test pagina stelt u in staat om te testen of IP-adressen door de geïnstalleerde signatures worden geblokkeerd.';
$CIDRAM['lang']['tip_ip_test_module_switch'] = '(Wanneer niet geselecteerd, worden alleen de signature bestanden getest tegen).';
$CIDRAM['lang']['tip_ip_tracking'] = 'Hallo, {username}.<br />Met het IP-Tracking pagina, is het mogelijk om de tracking status van IP-adressen te controleren, en u kunt zien welke zijn verboden, en om te annuleren de tracking van hen als u wilt doen.';
$CIDRAM['lang']['tip_login'] = 'Standaard gebruikersnaam: <span class="txtRd">admin</span> – Standaard wachtwoord: <span class="txtRd">password</span>';
$CIDRAM['lang']['tip_logs'] = 'Hallo, {username}.<br />Selecteer een logbestand uit de onderstaande lijst om de inhoud van de logbestand te bekijken.';
$CIDRAM['lang']['tip_range'] = 'Hallo, {username}.<br />Deze pagina toont enige statistische basisinformatie over de IP-reeksen die worden bestreken door de actieve signature bestanden.';
$CIDRAM['lang']['tip_sections_list'] = 'Hallo, {username}.<br />Deze pagina geeft een overzicht van de secties die bestaan in de actieve signature bestanden.';
$CIDRAM['lang']['tip_see_the_documentation'] = 'Zie de <a href="https://github.com/CIDRAM/CIDRAM/blob/master/_docs/readme.nl.md#SECTION6">documentatie</a> voor informatie over de verschillende configuratie richtlijnen en hun doeleinden.';
$CIDRAM['lang']['tip_statistics'] = 'Hallo, {username}.<br />Deze pagina bevat een aantal basisgebruiksstatistieken voor uw CIDRAM-installatie.';
$CIDRAM['lang']['tip_statistics_disabled'] = 'Notitie: Statistische tracking is momenteel uitgeschakeld, maar kan via de configuratiepagina worden ingeschakeld.';
$CIDRAM['lang']['tip_updates'] = 'Hallo, {username}.<br />De updates pagina stelt u in staat om de verschillende CIDRAM componenten te installeren, verwijderen, en actualiseren (de core pakket, signatures, L10N bestanden, ezv).';
$CIDRAM['lang']['title_accounts'] = 'CIDRAM – Accounts';
$CIDRAM['lang']['title_cache_data'] = 'CIDRAM – Cachegegevens';
$CIDRAM['lang']['title_cidr_calc'] = 'CIDRAM – CIDR Calculator';
$CIDRAM['lang']['title_config'] = 'CIDRAM – Configuratie';
$CIDRAM['lang']['title_file_manager'] = 'CIDRAM – Bestandsbeheer';
$CIDRAM['lang']['title_home'] = 'CIDRAM – Startpagina';
$CIDRAM['lang']['title_ip_aggregator'] = 'CIDRAM – IP-Aggregator';
$CIDRAM['lang']['title_ip_test'] = 'CIDRAM – IP Test';
$CIDRAM['lang']['title_ip_tracking'] = 'CIDRAM – IP-Tracking';
$CIDRAM['lang']['title_login'] = 'CIDRAM – Inloggen';
$CIDRAM['lang']['title_logs'] = 'CIDRAM – Logbestanden';
$CIDRAM['lang']['title_range'] = 'CIDRAM – Reeks Tafels';
$CIDRAM['lang']['title_sections_list'] = 'CIDRAM – Sectielijst';
$CIDRAM['lang']['title_statistics'] = 'CIDRAM – Statistieken';
$CIDRAM['lang']['title_updates'] = 'CIDRAM – Updates';
$CIDRAM['lang']['warning'] = 'Waarschuwingen:';
$CIDRAM['lang']['warning_php_1'] = 'Uw PHP versie wordt niet meer actief ondersteund! Het bijwerken is aanbevolen!';
$CIDRAM['lang']['warning_php_2'] = 'Uw PHP versie is ernstig kwetsbaar! Het bijwerken is sterk aanbevolen!';
$CIDRAM['lang']['warning_signatures_1'] = 'Geen signature bestanden zijn actief!';

$CIDRAM['lang']['info_some_useful_links'] = 'Enkele nuttige links:<ul>
            <li><a href="https://github.com/CIDRAM/CIDRAM/issues">CIDRAM Kwesties @ GitHub</a> – Kwesties pagina voor CIDRAM (steun, hulp, ezv).</li>
            <li><a href="https://wordpress.org/plugins/cidram/">CIDRAM @ WordPress.org</a> – WordPress plugin voor CIDRAM.</li>
            <li><a href="https://websectools.com/">WebSecTools.com</a> – Een verzameling van eenvoudige webmaster tools waarmee websites te beveiligen.</li>
            <li><a href="https://bitbucket.org/macmathan/blocklists">macmathan/blocklists</a> – Bevat optionele blokkeerlijsten en modules voor CIDRAM, zoals voor het blokkeren van gevaarlijke bots, ongewenste landen, verouderde browsers, enz.</li>
            <li><a href="https://www.facebook.com/groups/2204685680/">Global PHP Group @ Facebook</a> – PHP leermiddelen en discussie.</li>
            <li><a href="https://php.earth/">PHP.earth</a> – PHP leermiddelen en discussie.</li>
            <li><a href="http://bgp.he.net/">Hurricane Electric BGP Toolkit</a> – Krijg CIDR\'s van ASN, bepalen ASN relaties, ontdek ASN\'s op basis van netwerknamen, ezv.</li>
            <li><a href="https://www.stopforumspam.com/forum/">Forum @ Stop Forum Spam</a> – Nuttig discussieforum over het stoppen forum spam.</li>
            <li><a href="https://radar.qrator.net/">Radar van Qrator</a> – Handig hulpmiddel voor het controleren van de connectiviteit van ASN\'s en ook voor diverse andere informatie over ASN\'s.</li>
            <li><a href="http://www.ipdeny.com/ipblocks/">IPdeny IP-landblokken</a> – Een fantastische en accurate service voor het genereren van de signatures voor landen.</li>
            <li><a href="https://www.google.com/transparencyreport/safebrowsing/malware/">Google Malware Dashboard</a> – Geeft rapporten over malware-infectie tarieven voor ASN.</li>
            <li><a href="https://www.spamhaus.org/statistics/botnet-asn/">Het Spamhaus Project</a> – Geeft rapporten over botnet infectie tarieven voor ASN.</li>
            <li><a href="https://www.abuseat.org/public/asn.html">Composite Blocking List @ Abuseat.org</a> – Geeft rapporten over botnet infectie tarieven voor ASN.</li>
            <li><a href="https://abuseipdb.com/">AbuseIPDB</a> – Onderhoudt een database van bekende beledigend IP\'s; Biedt een API voor het controleren en rapporteren van IP\'s.</li>
            <li><a href="https://www.megarbl.net/index.php">MegaRBL.net</a> – Onderhoudt lijsten van bekende spammers; Handig voor het controleren van IP/ASN spam activiteiten.</li>
            <li><a href="https://maikuolan.github.io/Vulnerability-Charts/">Kwetsbaarheidstabellen</a> – Hiermee worden veilige/onveilige versies van verschillende pakketten weergegeven (HHVM, PHP, phpMyAdmin, Python, ezv).</li>
            <li><a href="https://maikuolan.github.io/Compatibility-Charts/">Compatibiliteitstabellen</a> – Hiermee worden informatie over compatibiliteit voor verschillende pakketten weergegeven (CIDRAM, phpMussel, ezv).</li>
        </ul>';
