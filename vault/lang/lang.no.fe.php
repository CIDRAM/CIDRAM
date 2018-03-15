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
 * This file: Norwegian language data for the front-end (last modified: 2018.03.15).
 */

/** Prevents execution from outside of CIDRAM. */
if (!defined('CIDRAM')) {
    die('[CIDRAM] This should not be accessed directly.');
}

$CIDRAM['lang']['Extended Description: Bypasses'] = 'Standard-signatur-bypass-filene som normalt følger med hovedpakken.';
$CIDRAM['lang']['Extended Description: CIDRAM'] = 'Hovedpakken (minus signaturene, dokumentasjonen og konfigurasjonen).';
$CIDRAM['lang']['Name: Bypasses'] = 'Standard signatur bypasses.';
$CIDRAM['lang']['Name: IPv4'] = 'IPv4 signaturfil (uønskede sky-tjenester og ikke-menneskelige endepunkter).';
$CIDRAM['lang']['Name: IPv4-Bogons'] = 'IPv4 signaturfil (bogon/martian CIDRer).';
$CIDRAM['lang']['Name: IPv4-ISPs'] = 'IPv4 signaturfil (farlige og spam-fylte ISPer).';
$CIDRAM['lang']['Name: IPv4-Other'] = 'IPv4 signaturfil (CIDRer for proxyer, VPN og andre uønskede tjenester).';
$CIDRAM['lang']['Name: IPv6'] = 'IPv6 signaturfil (uønskede sky-tjenester og ikke-menneskelige endepunkter).';
$CIDRAM['lang']['Name: IPv6-Bogons'] = 'IPv6 signaturfil (bogon/martian CIDRer).';
$CIDRAM['lang']['Name: IPv6-ISPs'] = 'IPv6 signaturfil (farlige og spam-fylte ISPer).';
$CIDRAM['lang']['Name: IPv6-Other'] = 'IPv6 signaturfil (CIDRer for proxyer, VPN og andre uønskede tjenester).';
$CIDRAM['lang']['bNav_home_logout'] = '<a href="?">Hjem</a> | <a href="?cidram-page=logout">Logg Ut</a>';
$CIDRAM['lang']['bNav_logout'] = '<a href="?cidram-page=logout">Logg Ut</a>';
$CIDRAM['lang']['config_general_FrontEndLog'] = 'Fil for å logge innloggingsforsøk på frontenden. Angi et filnavn, eller la det være tomt for å deaktivere.';
$CIDRAM['lang']['config_general_allow_gethostbyaddr_lookup'] = 'Tillat gethostbyaddr oppslag når UDP er utilgjengelig? True = Ja [Standardverdi]; False = Nei.';
$CIDRAM['lang']['config_general_ban_override'] = 'Overstyr "forbid_on_block" når "infraction_limit" overskrides? Når overstyrende: Blokkerte forespørsler returnerer en tom side (malfiler blir ikke brukt). 200 = Ikke overstyre [Standardverdi]; 403 = Overstyr med "403 Forbidden"; 503 = Overstyr med "503 Service unavailable".';
$CIDRAM['lang']['config_general_default_algo'] = 'Definerer hvilken algoritme som skal brukes for alle fremtidige passord og økter. Alternativer: PASSWORD_DEFAULT (standardverdi), PASSWORD_BCRYPT, PASSWORD_ARGON2I (krever PHP >= 7.2.0).';
$CIDRAM['lang']['config_general_default_dns'] = 'En kommaseparert liste over DNS-servere som skal brukes til vertsnavn-oppslag. Standardverdi = "8.8.8.8,8.8.4.4" (Google DNS). ADVARSEL: Ikke endre dette med mindre du vet hva du gjør!';
$CIDRAM['lang']['config_general_disable_cli'] = 'Deaktiver CLI-modus? CLI-modus er aktivert som standard, men kan noen ganger forstyrre visse testverktøy (for eksempel; PHPUnit) og andre CLI-baserte applikasjoner. Hvis du ikke trenger å deaktivere CLI-modus, bør du ignorere dette direktivet. False = Aktiver CLI-modus [Standardverdi]; True = Deaktiver CLI-modus.';
$CIDRAM['lang']['config_general_disable_frontend'] = 'Deaktiver tilgang til frontenden? Frontend tilgang kan gjøre CIDRAM mer overskuelig, men kan også være en potensiell sikkerhetsrisiko også. Det anbefales å administrere CIDRAM via bakenden når det er mulig, men frontend tilgang er gitt for når det ikke er mulig. Hold den deaktivert med mindre du trenger det. False = Aktiver tilgang til frontenden; True = Deaktiver tilgang til frontenden [Standardverdi].';
$CIDRAM['lang']['config_general_disable_webfonts'] = 'Deaktiver webfonter? True = Ja; False = Nei [Standardverdi].';
$CIDRAM['lang']['config_general_emailaddr'] = 'Hvis du vil, du kan levere en e-postadresse her for å bli gitt til brukerne når de er blokkert, for dem å bruke som et kontaktpunkt for støtte og/eller assistanse i tilfelle de blir blokkert feilaktig eller i feil. ADVARSEL: Uansett hvilken e-postadresse du leverer her, det vil helt sikkert bli ervervet av spamboter og skraper i løpet av bruken her, og så, det anbefales sterkt at hvis du velger å levere en e-postadresse her, at du sørger for at e-postadressen du oppgir her er en engangsadresse og/eller en adresse som du ikke bryr deg om å bli spammet (dvs., du vil sannsynligvis ikke bruke dine primære personlige eller primære virksomhets e-postadresser).';
$CIDRAM['lang']['config_general_emailaddr_display_style'] = 'Hvordan foretrekker du e-postadressen som skal presenteres for brukerne?';
$CIDRAM['lang']['config_general_forbid_on_block'] = 'Hvilke overskrifter skal CIDRAM svare med når du blokkerer forespørsler?';
$CIDRAM['lang']['config_general_force_hostname_lookup'] = 'Håndheve vertsnavn-oppslag? True = Ja; False = Nei [Standardverdi]. Vertsnavn-oppslag utføres normalt på en "etter behov" basis, men kan håndheves for alle forespørsler. Å gjøre det kan være nyttig som et middel til å gi mer detaljert informasjon i loggfilene, men kan også ha en litt negativ effekt på ytelsen.';
$CIDRAM['lang']['config_general_hide_version'] = 'Skjul versjoninformasjon fra logger og sideutdata? True = Ja; False = Nei [Standardverdi].';
$CIDRAM['lang']['config_general_ipaddr'] = 'Hvor finner jeg IP-adressen til å koble til forespørsler? (Nyttig for tjenester som Cloudflare og så videre). Standardverdi = REMOTE_ADDR. ADVARSEL: Ikke endre dette med mindre du vet hva du gjør!';
$CIDRAM['lang']['config_general_lang'] = 'Angi standardspråket for CIDRAM.';
$CIDRAM['lang']['config_general_log_banned_ips'] = 'Inkluder blokkerte forespørsler fra utestengt IP-adresser i loggfilene? True = Ja [Standardverdi]; False = Nei.';
$CIDRAM['lang']['config_general_logfile'] = 'Menneskelig-lesbar fil for å logge av alle blokkerte tilgangsforsøk. Angi et filnavn, eller la det være tomt for å deaktivere.';
$CIDRAM['lang']['config_general_logfileApache'] = 'Apache-stil fil for å logge alle blokkerte tilgangsforsøk. Angi et filnavn, eller la det være tomt for å deaktivere.';
$CIDRAM['lang']['config_general_logfileSerialized'] = 'Serialisert fil å logge av alle blokkerte tilgangsforsøk. Angi et filnavn, eller la det være tomt for å deaktivere.';
$CIDRAM['lang']['config_general_maintenance_mode'] = 'Aktiver vedlikeholdsmodus? True = Ja; False = Nei [Standardverdi]. Deaktiverer alt annet enn frontenden. Noen ganger nyttig for når du oppdaterer CMS, rammeverker, osv.';
$CIDRAM['lang']['config_general_max_login_attempts'] = 'Maks antall innloggingsforsøk.';
$CIDRAM['lang']['config_general_numbers'] = 'Hvordan foretrekker du at tall vises? Velg eksempelet som passer best for deg.';
$CIDRAM['lang']['config_general_protect_frontend'] = 'Angir om beskyttelsen som normalt leveres av CIDRAM, skal brukes på frontenden. True = Ja [Standardverdi]; False = Nei.';
$CIDRAM['lang']['config_general_search_engine_verification'] = 'Forsøk å bekrefte forespørsler fra søkemotorer? Verifiserende søkemotorer sikrer at de ikke vil bli utestengt som et resultat av å overskride infraksjonsgrensen (utestenging mot søkemotorer fra nettstedet ditt vil vanligvis ha en negativ effekt på søkemotorrangeringen din, SEO, osv). Når verifisert, kan søkemotorer bli blokkert som normalt, men vil ikke bli utestengt. Når det ikke er verifisert, er det mulig for dem å bli utestengt som et resultat av å overskride infraksjonsgrensen. Dess, søkemotor verifisering gir beskyttelse mot falske søkemotor forespørsler og mot potensielt skadelige enheter som later til å være søkemotorer (slike forespørsler vil bli blokkert når søkemotor verifisering er aktivert). True = Aktiver søkemotor verifisering [Standardverdi]; False = Deaktiver søkemotor verifisering.';
$CIDRAM['lang']['config_general_silent_mode'] = 'Bør CIDRAM stille omdirigere blokkerte tilgangsforsøk i stedet for å vise "Tilgang Nektet" siden? Hvis ja, spesifiser stedet for å omdirigere blokkerte tilgangsforsøk til. Hvis nei, la denne variablen være tom.';
$CIDRAM['lang']['config_general_statistics'] = 'Spor CIDRAM bruksstatistikk? True = Ja; False = Nei [Standardverdi].';
$CIDRAM['lang']['config_general_timeFormat'] = 'Dato/tid notasjonsformat som brukes av CIDRAM. Ytterligere alternativer kan legges på forespørsel.';
$CIDRAM['lang']['config_general_timeOffset'] = 'Tidssone forskjøvet i minutter.';
$CIDRAM['lang']['config_general_timezone'] = 'Din tidssone.';
$CIDRAM['lang']['config_general_truncate'] = 'Skjær loggfiler når de kommer til en viss størrelse? Verdi er den maksimale størrelsen i B/KB/MB/GB/TB som en loggfil kan vokse til før den blir avkortet. Standardverdi på 0KB deaktiverer avkorting (loggfilene kan vokse på ubestemt tid). Merk: Gjelder for individuelle loggfiler! Størrelsen på loggfiler anses ikke kollektivt.';
$CIDRAM['lang']['config_recaptcha_api'] = 'Hvilken API skal du bruke? V2 eller invisible?';
$CIDRAM['lang']['config_recaptcha_expiry'] = 'Antall timer for å huske reCAPTCHA-forekomster.';
$CIDRAM['lang']['config_recaptcha_lockip'] = 'Lås reCAPTCHA til IP-adresser?';
$CIDRAM['lang']['config_recaptcha_lockuser'] = 'Lås reCAPTCHA til brukere?';
$CIDRAM['lang']['config_recaptcha_logfile'] = 'Logg på alle reCAPTCHA-forsøk? Hvis ja, spesifiser navnet som skal brukes til loggfilen. Hvis nei, la denne variablen være tom.';
$CIDRAM['lang']['config_recaptcha_secret'] = 'Denne verdien bør samsvare med den "secret key" for reCAPTCHA, som du finner i reCAPTCHA-dashbordet.';
$CIDRAM['lang']['config_recaptcha_signature_limit'] = 'Maksimalt antall signaturer som kan utløses når en reCAPTCHA-forekomst skal tilbys. Standardverdi = 1. Hvis dette nummeret overskrides for en bestemt forespørsel, vil ikke en reCAPTCHA-forekomst bli tilbudt.';
$CIDRAM['lang']['config_recaptcha_sitekey'] = 'Denne verdien bør samsvare med den "site key" for reCAPTCHA, som du finner i reCAPTCHA-dashbordet.';
$CIDRAM['lang']['config_recaptcha_usemode'] = 'Definerer hvordan CIDRAM skal bruke reCAPTCHA (se dokumentasjon).';
$CIDRAM['lang']['config_signatures_block_bogons'] = 'Blokker bogon/martian CIDRer? Hvis du forventer tilkoblinger til nettstedet ditt fra ditt lokale nettverk, fra localhost eller fra ditt LAN, bør dette direktivet settes til false. Hvis du ikke forventer slike tilkoblinger, bør dette direktivet settes til true.';
$CIDRAM['lang']['config_signatures_block_cloud'] = 'Blokker CIDRer identifisert som tilhørende web-verter og sky-tjenester? Hvis du driver en API-tjeneste fra nettstedet ditt eller hvis du forventer at andre nettsteder skal koble til nettstedet ditt, bør dette settes til false. Hvis du ikke gjør det, da bør dette direktivet settes til true.';
$CIDRAM['lang']['config_signatures_block_generic'] = 'Blokker CIDRer anbefales vanligvis for svarteliste? Dette dekker alle signaturer som ikke er merket som en del av noen av de andre mer spesifikke signaturkategoriene.';
$CIDRAM['lang']['config_signatures_block_proxies'] = 'Blokker CIDRer som er identifisert som tilhørende proxy-tjenester? Hvis du krever at brukerne skal kunne få tilgang til nettstedet ditt fra anonyme proxy-tjenester, bør dette settes til feil. Ellers, hvis du ikke trenger anonyme proxyer, bør dette direktivet settes til true som et middel til å forbedre sikkerheten.';
$CIDRAM['lang']['config_signatures_block_spam'] = 'Blokker CIDRer som er utsatt for høy risiko for spam? Med mindre du opplever problemer når du gjør det, bør dette vanligvis alltid settes til true.';
$CIDRAM['lang']['config_signatures_default_tracktime'] = 'Hvor mange sekunder å spore IPer som er utestengt av moduler. Standardverdi = 604800 (1 uke).';
$CIDRAM['lang']['config_signatures_infraction_limit'] = 'Maks antall infraksjoner som en IP har lov til å pådra seg før den er utestengt av IP-sporing. Standardverdi = 10.';
$CIDRAM['lang']['config_signatures_ipv4'] = 'En liste over IPv4 signaturfiler som CIDRAM skal forsøke å analysere, separert av kommaer.';
$CIDRAM['lang']['config_signatures_ipv6'] = 'En liste over IPv6 signaturfiler som CIDRAM skal forsøke å analysere, separert av kommaer.';
$CIDRAM['lang']['config_signatures_modules'] = 'En liste over modulfiler som skal lastes etter at du har sjekket IPv4/IPv6-signaturer, separert av kommaer.';
$CIDRAM['lang']['config_signatures_track_mode'] = 'Når skal infraksjoner regnes? False = Når IPer blokkert av moduler. True = Når IPer er blokkert av en eller annen grunn.';
$CIDRAM['lang']['config_template_data_Magnification'] = 'Font forstørrelse. Standardverdi = 1.';
$CIDRAM['lang']['config_template_data_css_url'] = 'URL for CSS-fil for tilpassede temaer.';
$CIDRAM['lang']['config_template_data_theme'] = 'Standard tema som skal brukes til CIDRAM.';
$CIDRAM['lang']['field_activate'] = 'Aktiver';
$CIDRAM['lang']['field_banned'] = 'Utestengt';
$CIDRAM['lang']['field_blocked'] = 'Blokkert';
$CIDRAM['lang']['field_clear'] = 'Rydde';
$CIDRAM['lang']['field_clear_all'] = 'Rydde alt';
$CIDRAM['lang']['field_clickable_link'] = 'Klikkbar lenke';
$CIDRAM['lang']['field_component'] = 'Komponent';
$CIDRAM['lang']['field_create_new_account'] = 'Opprette ny konto';
$CIDRAM['lang']['field_deactivate'] = 'Deaktiver';
$CIDRAM['lang']['field_delete_account'] = 'Slett konto';
$CIDRAM['lang']['field_delete_file'] = 'Slett';
$CIDRAM['lang']['field_download_file'] = 'Nedlasting';
$CIDRAM['lang']['field_edit_file'] = 'Rediger';
$CIDRAM['lang']['field_expiry'] = 'Utløp';
$CIDRAM['lang']['field_false'] = 'False (Falsk)';
$CIDRAM['lang']['field_file'] = 'Fil';
$CIDRAM['lang']['field_filename'] = 'Filnavn: ';
$CIDRAM['lang']['field_filetype_directory'] = 'Filkatalog';
$CIDRAM['lang']['field_filetype_info'] = '{EXT}-Fil';
$CIDRAM['lang']['field_filetype_unknown'] = 'Ukjent';
$CIDRAM['lang']['field_first_seen'] = 'Først Sett';
$CIDRAM['lang']['field_infractions'] = 'Infraksjoner';
$CIDRAM['lang']['field_install'] = 'Installer';
$CIDRAM['lang']['field_ip_address'] = 'IP-Adresse';
$CIDRAM['lang']['field_latest_version'] = 'Siste Versjon';
$CIDRAM['lang']['field_log_in'] = 'Logg Inn';
$CIDRAM['lang']['field_new_name'] = 'Nytt navn:';
$CIDRAM['lang']['field_nonclickable_text'] = 'Ikke-klikkbar tekst';
$CIDRAM['lang']['field_ok'] = 'OK';
$CIDRAM['lang']['field_options'] = 'Alternativer';
$CIDRAM['lang']['field_password'] = 'Passord';
$CIDRAM['lang']['field_permissions'] = 'Tillatelser';
$CIDRAM['lang']['field_range'] = 'Område (Først – Siste)';
$CIDRAM['lang']['field_rename_file'] = 'Gi nytt navn';
$CIDRAM['lang']['field_reset'] = 'Tilbakestille';
$CIDRAM['lang']['field_set_new_password'] = 'Sett inn nytt passord';
$CIDRAM['lang']['field_size'] = 'Total størrelse: ';
$CIDRAM['lang']['field_size_GB'] = 'GB';
$CIDRAM['lang']['field_size_KB'] = 'KB';
$CIDRAM['lang']['field_size_MB'] = 'MB';
$CIDRAM['lang']['field_size_TB'] = 'TB';
$CIDRAM['lang']['field_size_bytes'] = 'byte';
$CIDRAM['lang']['field_status'] = 'Status';
$CIDRAM['lang']['field_system_timezone'] = 'Bruk systemets standard tidssone.';
$CIDRAM['lang']['field_tracking'] = 'Sporing';
$CIDRAM['lang']['field_true'] = 'True (Ekte)';
$CIDRAM['lang']['field_uninstall'] = 'Avinstaller';
$CIDRAM['lang']['field_update'] = 'Oppdater';
$CIDRAM['lang']['field_update_all'] = 'Oppdater alt';
$CIDRAM['lang']['field_upload_file'] = 'Last opp ny fil';
$CIDRAM['lang']['field_username'] = 'Brukernavn';
$CIDRAM['lang']['field_verify'] = 'Verifisere';
$CIDRAM['lang']['field_verify_all'] = 'Verifisere alt';
$CIDRAM['lang']['field_your_version'] = 'Din Versjon';
$CIDRAM['lang']['header_login'] = 'Vennligst logg inn for å fortsette.';
$CIDRAM['lang']['label_active_config_file'] = 'Aktiv konfigurasjonsfil: ';
$CIDRAM['lang']['label_banned'] = 'Forespørsler utestengt';
$CIDRAM['lang']['label_blocked'] = 'Forespørsler blokkert';
$CIDRAM['lang']['label_branch'] = 'Branch siste stabile:';
$CIDRAM['lang']['label_check_modules'] = 'Test også mot moduler.';
$CIDRAM['lang']['label_cidram'] = 'CIDRAM versjon brukt:';
$CIDRAM['lang']['label_displaying'] = ['<span class="txtRd">%s</span> oppføring vises.', '<span class="txtRd">%s</span> oppføringer vises.'];
$CIDRAM['lang']['label_displaying_that_cite'] = ['<span class="txtRd">%1$s</span> oppføring vises som citerer "%2$s".', '<span class="txtRd">%1$s</span> oppføringer vises som citerer "%2$s".'];
$CIDRAM['lang']['label_expires'] = 'Utgår: ';
$CIDRAM['lang']['label_false_positive_risk'] = 'Falsk positiv risiko: ';
$CIDRAM['lang']['label_fmgr_cache_data'] = 'Cache data og midlertidige filer';
$CIDRAM['lang']['label_fmgr_disk_usage'] = 'CIDRAM diskbruk: ';
$CIDRAM['lang']['label_fmgr_free_space'] = 'Ledig diskplass: ';
$CIDRAM['lang']['label_fmgr_total_disk_usage'] = 'Totalt diskbruk: ';
$CIDRAM['lang']['label_fmgr_total_space'] = 'Totalt diskplass: ';
$CIDRAM['lang']['label_fmgr_updates_metadata'] = 'Komponentoppdateringsmetadata';
$CIDRAM['lang']['label_hide'] = 'Gjem';
$CIDRAM['lang']['label_never'] = 'Aldri';
$CIDRAM['lang']['label_os'] = 'Operativsystem brukt:';
$CIDRAM['lang']['label_other'] = 'Annen';
$CIDRAM['lang']['label_other-ActiveIPv4'] = 'Aktive IPv4 signaturfiler';
$CIDRAM['lang']['label_other-ActiveIPv6'] = 'Aktive IPv6 signaturfiler';
$CIDRAM['lang']['label_other-ActiveModules'] = 'Aktive moduler';
$CIDRAM['lang']['label_other-Since'] = 'Startdato';
$CIDRAM['lang']['label_php'] = 'PHP versjon brukt:';
$CIDRAM['lang']['label_reCAPTCHA'] = 'reCAPTCHA forsøk';
$CIDRAM['lang']['label_results'] = 'Resultater (%s i – %s avvist – %s akseptert – %s sammensmeltet – %s ut):';
$CIDRAM['lang']['label_sapi'] = 'SAPI brukt:';
$CIDRAM['lang']['label_show'] = 'Vis';
$CIDRAM['lang']['label_stable'] = 'Siste stabile:';
$CIDRAM['lang']['label_sysinfo'] = 'Systeminformasjon:';
$CIDRAM['lang']['label_tests'] = 'Tester:';
$CIDRAM['lang']['label_total'] = 'Total';
$CIDRAM['lang']['label_unstable'] = 'Siste ustabil:';
$CIDRAM['lang']['link_accounts'] = 'Kontoer';
$CIDRAM['lang']['link_cache_data'] = 'Cache Data';
$CIDRAM['lang']['link_cidr_calc'] = 'CIDR-Kalkulator';
$CIDRAM['lang']['link_config'] = 'Konfigurasjon';
$CIDRAM['lang']['link_documentation'] = 'Dokumentasjon';
$CIDRAM['lang']['link_file_manager'] = 'Filbehandler';
$CIDRAM['lang']['link_home'] = 'Hjem';
$CIDRAM['lang']['link_ip_aggregator'] = 'IP-Aggregator';
$CIDRAM['lang']['link_ip_test'] = 'IP-Test';
$CIDRAM['lang']['link_ip_tracking'] = 'IP-Sporing';
$CIDRAM['lang']['link_logs'] = 'Logger';
$CIDRAM['lang']['link_sections_list'] = 'Seksjonsliste';
$CIDRAM['lang']['link_statistics'] = 'Statistikk';
$CIDRAM['lang']['link_textmode'] = 'Tekstformatering: <a href="%1$sfalse%2$s">Enkel</a> – <a href="%1$strue%2$s">Fancy</a> – <a href="%1$stally%2$s">Tally</a>';
$CIDRAM['lang']['link_updates'] = 'Oppdateringer';
$CIDRAM['lang']['logs_logfile_doesnt_exist'] = 'Utvalgte loggfilen finnes ikke!';
$CIDRAM['lang']['logs_no_logfile_selected'] = 'Ingen loggfil valgt.';
$CIDRAM['lang']['logs_no_logfiles_available'] = 'Ingen loggfiler tilgjengelig.';
$CIDRAM['lang']['max_login_attempts_exceeded'] = 'Maks antall påloggingsforsøk er overskredet; Tilgang nektet.';
$CIDRAM['lang']['previewer_days'] = 'Dager';
$CIDRAM['lang']['previewer_hours'] = 'Timer';
$CIDRAM['lang']['previewer_minutes'] = 'Minutter';
$CIDRAM['lang']['previewer_months'] = 'Måneder';
$CIDRAM['lang']['previewer_seconds'] = 'Sekunder';
$CIDRAM['lang']['previewer_weeks'] = 'Uker';
$CIDRAM['lang']['previewer_years'] = 'År';
$CIDRAM['lang']['response_accounts_already_exists'] = 'En konto med det brukernavnet eksisterer allerede!';
$CIDRAM['lang']['response_accounts_created'] = 'Kontoen opprettet!';
$CIDRAM['lang']['response_accounts_deleted'] = 'Kontoen slettet!';
$CIDRAM['lang']['response_accounts_doesnt_exist'] = 'Den kontoen eksisterer ikke.';
$CIDRAM['lang']['response_accounts_password_updated'] = 'Passordet oppdatert!';
$CIDRAM['lang']['response_activated'] = 'Aktivering vellykket.';
$CIDRAM['lang']['response_activation_failed'] = 'Aktivering mislyktes!';
$CIDRAM['lang']['response_checksum_error'] = 'Checksum feil! Fil avvist!';
$CIDRAM['lang']['response_component_successfully_installed'] = 'Komponent installert.';
$CIDRAM['lang']['response_component_successfully_uninstalled'] = 'Komponent avinstallert.';
$CIDRAM['lang']['response_component_successfully_updated'] = 'Komponent oppdatert.';
$CIDRAM['lang']['response_component_uninstall_error'] = 'Det oppsto en feil under forsøk på å avinstallere komponenten.';
$CIDRAM['lang']['response_configuration_updated'] = 'Konfigurasjon oppdatert.';
$CIDRAM['lang']['response_deactivated'] = 'Deaktivering vellykket.';
$CIDRAM['lang']['response_deactivation_failed'] = 'Deaktivering mislyktes!';
$CIDRAM['lang']['response_delete_error'] = 'Sletting mislyktes!';
$CIDRAM['lang']['response_directory_deleted'] = 'Filkatalog slettet!';
$CIDRAM['lang']['response_directory_renamed'] = 'Filkatalog omdøpt!';
$CIDRAM['lang']['response_error'] = 'Feil';
$CIDRAM['lang']['response_failed_to_install'] = 'Installasjon mislyktes!';
$CIDRAM['lang']['response_failed_to_update'] = 'Oppdatering mislyktes!';
$CIDRAM['lang']['response_file_deleted'] = 'Fil slettet!';
$CIDRAM['lang']['response_file_edited'] = 'Fil modifisert!';
$CIDRAM['lang']['response_file_renamed'] = 'Fil omdøpt!';
$CIDRAM['lang']['response_file_uploaded'] = 'Fil opplastet!';
$CIDRAM['lang']['response_login_invalid_password'] = 'Innlogging mislyktes! Ugyldig passord!';
$CIDRAM['lang']['response_login_invalid_username'] = 'Innlogging mislyktes! Brukernavnet eksisterer ikke!';
$CIDRAM['lang']['response_login_password_field_empty'] = 'Passord-felt tomt!';
$CIDRAM['lang']['response_login_username_field_empty'] = 'Brukernavn-felt tomt!';
$CIDRAM['lang']['response_login_wrong_endpoint'] = 'Feil sluttpunkt!';
$CIDRAM['lang']['response_no'] = 'Nei';
$CIDRAM['lang']['response_possible_problem_found'] = 'Mulig problem funnet.';
$CIDRAM['lang']['response_rename_error'] = 'Omdøpe mislyktes!';
$CIDRAM['lang']['response_statistics_cleared'] = 'Statistikk fjernet.';
$CIDRAM['lang']['response_tracking_cleared'] = 'Sporing fjernet.';
$CIDRAM['lang']['response_updates_already_up_to_date'] = 'Allerede oppdatert.';
$CIDRAM['lang']['response_updates_not_installed'] = 'Komponent ikke installert!';
$CIDRAM['lang']['response_updates_not_installed_php'] = 'Komponent ikke installert (krever PHP {V})!';
$CIDRAM['lang']['response_updates_outdated'] = 'Utdatert!';
$CIDRAM['lang']['response_updates_outdated_manually'] = 'Utdatert (vennligst oppdatere manuelt)!';
$CIDRAM['lang']['response_updates_outdated_php_version'] = 'Utdatert (krever PHP {V})!';
$CIDRAM['lang']['response_updates_unable_to_determine'] = 'Kan ikke fastslå.';
$CIDRAM['lang']['response_upload_error'] = 'Opplasting mislyktes!';
$CIDRAM['lang']['response_verification_failed'] = 'Verifisering mislyktes! Komponent kan bli skadet.';
$CIDRAM['lang']['response_verification_success'] = 'Verifisering suksess! Ingen problemer funnet.';
$CIDRAM['lang']['response_yes'] = 'Ja';
$CIDRAM['lang']['state_async_deny'] = 'Tillatelser ikke tilstrekkelig til å utføre asynkrone forespørsler. Prøv å logge inn igjen.';
$CIDRAM['lang']['state_cache_is_empty'] = 'Cachen er tom.';
$CIDRAM['lang']['state_complete_access'] = 'Komplett tilgang';
$CIDRAM['lang']['state_component_is_active'] = 'Komponent er aktiv.';
$CIDRAM['lang']['state_component_is_inactive'] = 'Komponent er inaktiv.';
$CIDRAM['lang']['state_component_is_provisional'] = 'Komponent er foreløpig.';
$CIDRAM['lang']['state_default_password'] = 'Advarsel: Bruker standard passordet';
$CIDRAM['lang']['state_ignored'] = 'Ignorert';
$CIDRAM['lang']['state_loading'] = 'Laster ...';
$CIDRAM['lang']['state_loadtime'] = 'Sideforespørsel avsluttet om <span class="txtRd">%s</span> sekunder.';
$CIDRAM['lang']['state_logged_in'] = 'Logget inn.';
$CIDRAM['lang']['state_logs_access_only'] = 'Bare logger tilgang';
$CIDRAM['lang']['state_maintenance_mode'] = 'Advarsel: Vedlikeholdsmodus er aktivert!';
$CIDRAM['lang']['state_password_not_valid'] = 'Advarsel: Denne kontoen bruker ikke et gyldig passord!';
$CIDRAM['lang']['state_risk_high'] = 'Høy';
$CIDRAM['lang']['state_risk_low'] = 'Lav';
$CIDRAM['lang']['state_risk_medium'] = 'Medium';
$CIDRAM['lang']['state_sl_totals'] = 'Totals (Signaturer: <span class="txtRd">%s</span> – Signaturseksjoner: <span class="txtRd">%s</span> – Signaturfiler: <span class="txtRd">%s</span>).';
$CIDRAM['lang']['state_tracking'] = ['Foreløpig sporer %s IP-adresse.', 'Foreløpig sporer %s IP-adresser.'];
$CIDRAM['lang']['switch-hide-non-outdated-set-false'] = 'Ikke skjul hvis ikke utdatert';
$CIDRAM['lang']['switch-hide-non-outdated-set-true'] = 'Skjul hvis ikke utdatert';
$CIDRAM['lang']['switch-hide-unused-set-false'] = 'Ikke skjul ubrukte';
$CIDRAM['lang']['switch-hide-unused-set-true'] = 'Skjul ubrukte';
$CIDRAM['lang']['switch-tracking-blocked-already-set-false'] = 'Ikke sjekk mot signaturfiler';
$CIDRAM['lang']['switch-tracking-blocked-already-set-true'] = 'Sjekk mot signaturfiler';
$CIDRAM['lang']['switch-tracking-hide-banned-blocked-set-false'] = 'Ikke skjul utestengt/blokkert IP-adresser';
$CIDRAM['lang']['switch-tracking-hide-banned-blocked-set-true'] = 'Skjul utestengt/blokkert IP-adresser';
$CIDRAM['lang']['tip_accounts'] = 'Hallo, {username}.<br />Du kan kontrollere hvem som kan få tilgang til CIDRAM-frontenden ved hjelp av kontosiden.';
$CIDRAM['lang']['tip_cache_data'] = 'Hallo, {username}.<br />Her kan du se innholdet i cachen.';
$CIDRAM['lang']['tip_cidr_calc'] = 'Hallo, {username}.<br />CIDR-kalkulatoren lar deg beregne hvilke CIDRer en IP-adresse er en faktor av.';
$CIDRAM['lang']['tip_config'] = 'Hallo, {username}.<br />Konfigurasjonssiden lar deg endre konfigurasjonen for CIDRAM fra frontenden.';
$CIDRAM['lang']['tip_custom_ua'] = 'Skriv inn brukeragent (user agent) her (valgfritt).';
$CIDRAM['lang']['tip_donate'] = 'CIDRAM tilbys gratis, men hvis du vil donere til prosjektet, du kan gjøre det ved å klikke på donate-knappen.';
$CIDRAM['lang']['tip_enter_ip_here'] = 'Skriv inn IP her.';
$CIDRAM['lang']['tip_enter_ips_here'] = 'Skriv inn IPer her.';
$CIDRAM['lang']['tip_fe_cookie_warning'] = 'Merk: CIDRAM bruker en informasjonskapsel for å godkjenne innlogginger. Ved å logge inn, gir du ditt samtykke til at en informasjonskapsel skal opprettes og lagres av nettleseren din.';
$CIDRAM['lang']['tip_file_manager'] = 'Hallo, {username}.<br />Filbehandleren lar deg slette, redigere, laste opp og laste ned filer. Bruk med forsiktighet (du kan ødelegge installasjonen din med dette).';
$CIDRAM['lang']['tip_home'] = 'Hallo, {username}.<br />Dette er hjemmesiden for frontenden av CIDRAM. Velg en lenke fra navigasjonsmenyen til venstre for å fortsette.';
$CIDRAM['lang']['tip_ip_aggregator'] = 'Hallo, {username}.<br />IP-aggregatoren lar deg uttrykke IPer og CIDRer på den minste mulige måten. Skriv inn dataene som skal aggregeres og trykk "OK".';
$CIDRAM['lang']['tip_ip_test'] = 'Hallo, {username}.<br />IP-testsiden lar deg teste om IP-adresser er blokkert av signaturene som er installert.';
$CIDRAM['lang']['tip_ip_test_module_switch'] = '(Når ikke valgt, blir bare signaturfiler testet mot).';
$CIDRAM['lang']['tip_ip_tracking'] = 'Hallo, {username}.<br />IP-sporing-siden lar deg sjekke sporingsstatus for IP-adresser, for å sjekke hvilken av dem som er blitt utestengt, og å oppheve utestengt status eller slutte å spore hvis du vil gjøre det.';
$CIDRAM['lang']['tip_login'] = 'Standard brukernavn: <span class="txtRd">admin</span> – Standard passord: <span class="txtRd">password</span>';
$CIDRAM['lang']['tip_logs'] = 'Hallo, {username}.<br />Velg en loggfil fra listen nedenfor for å se innholdet i den loggfilen.';
$CIDRAM['lang']['tip_sections_list'] = 'Hallo, {username}.<br />Denne siden viser hvilke seksjoner som finnes i de aktive signaturfilene.';
$CIDRAM['lang']['tip_see_the_documentation'] = 'Se <a href="https://github.com/CIDRAM/CIDRAM/blob/master/_docs/readme.en.md#SECTION6">dokumentasjonen</a> for informasjon om de ulike konfigurasjonsdirektiver og deres formål.';
$CIDRAM['lang']['tip_statistics'] = 'Hallo, {username}.<br />Denne siden viser noen grunnleggende bruksstatistikk angående CIDRAM-installasjonen.';
$CIDRAM['lang']['tip_statistics_disabled'] = 'Merk: Statistikksporing er for øyeblikket deaktivert, men kan aktiveres via konfigurasjonssiden.';
$CIDRAM['lang']['tip_updates'] = 'Hallo, {username}.<br />Oppdateringssiden lar deg installere, avinstallere og oppdatere de forskjellige komponentene i CIDRAM (kjernepakken, signaturer, L10N filer, osv).';
$CIDRAM['lang']['title_accounts'] = 'CIDRAM – Kontoer';
$CIDRAM['lang']['title_cache_data'] = 'CIDRAM – Cache Data';
$CIDRAM['lang']['title_cidr_calc'] = 'CIDRAM – CIDR-Kalkulator';
$CIDRAM['lang']['title_config'] = 'CIDRAM – Konfigurasjon';
$CIDRAM['lang']['title_file_manager'] = 'CIDRAM – Filbehandler';
$CIDRAM['lang']['title_home'] = 'CIDRAM – Hjem';
$CIDRAM['lang']['title_ip_aggregator'] = 'CIDRAM – IP-Aggregator';
$CIDRAM['lang']['title_ip_test'] = 'CIDRAM – IP-Test';
$CIDRAM['lang']['title_ip_tracking'] = 'CIDRAM – IP-Sporing';
$CIDRAM['lang']['title_login'] = 'CIDRAM – Innloggingssiden';
$CIDRAM['lang']['title_logs'] = 'CIDRAM – Logger';
$CIDRAM['lang']['title_sections_list'] = 'CIDRAM – Seksjonsliste';
$CIDRAM['lang']['title_statistics'] = 'CIDRAM – Statistikk';
$CIDRAM['lang']['title_updates'] = 'CIDRAM – Oppdateringer';
$CIDRAM['lang']['warning'] = 'Advarsler:';
$CIDRAM['lang']['warning_php_1'] = 'Din PHP-versjon støttes ikke lenger lenger! Oppdatering anbefales!';
$CIDRAM['lang']['warning_php_2'] = 'Din PHP-versjon er farlig! Oppdatering anbefales på det sterkeste!';
$CIDRAM['lang']['warning_signatures_1'] = 'Ingen signaturfiler er aktive!';

$CIDRAM['lang']['info_some_useful_links'] = 'Noen nyttige lenker:<ul>
            <li><a href="https://github.com/CIDRAM/CIDRAM/issues">CIDRAM Issues @ GitHub</a> – Issues side for CIDRAM (støtte, assistanse, osv).</li>
            <li><a href="https://wordpress.org/plugins/cidram/">CIDRAM @ WordPress.org</a> – WordPress plugin for CIDRAM.</li>
            <li><a href="https://sourceforge.net/projects/cidram/">CIDRAM @ SourceForge</a> – Alternativt nedlastingsspeil for CIDRAM.</li>
            <li><a href="https://websectools.com/">WebSecTools.com</a> – En samling av enkle webmaster-verktøy for å sikre nettsteder.</li>
            <li><a href="https://macmathan.info/blocklists">MacMathan.info Rekkevidde-Blokker</a> – Inneholder valgfrie rekkevidde-blokker som kan legges til CIDRAM for å blokkere uønskede land fra å få tilgang til nettstedet ditt.</li>
            <li><a href="https://www.facebook.com/groups/2204685680/">Global PHP Group @ Facebook</a> – PHP læringsressurser og diskusjon.</li>
            <li><a href="https://php.earth/">PHP.earth</a> – PHP læringsressurser og diskusjon.</li>
            <li><a href="http://bgp.he.net/">Hurricane Electric BGP Toolkit</a> – Få CIDRer fra ASNer, fastslå ASN-relasjoner, oppdag ASN basert på nettverksnavn, osv.</li>
            <li><a href="https://www.stopforumspam.com/forum/">Forum @ Stop Forum Spam</a> – Nyttig diskusjonsforum om å stoppe forum spam.</li>
            <li><a href="https://radar.qrator.net/">Radar by Qrator</a> – Nyttig verktøy for å sjekke ASN-tilkobling og annen informasjon relatert til ASNer.</li>
            <li><a href="http://www.ipdeny.com/ipblocks/">IPdeny IP country blocks</a> – En fantastisk og nøyaktig tjeneste for å generere landsdækkende signaturer.</li>
            <li><a href="https://www.google.com/transparencyreport/safebrowsing/malware/">Google Malware Dashboard</a> – Viser rapporter om malwareinfeksjonsrater for ASNer.</li>
            <li><a href="https://www.spamhaus.org/statistics/botnet-asn/">The Spamhaus Project</a> – Viser rapporter om botnets infeksjonsrate for ASNer.</li>
            <li><a href="https://www.abuseat.org/public/asn.html">Abuseat.org\'s Composite Blocking List</a> – Viser rapporter om botnets infeksjonsrate for ASNer.</li>
            <li><a href="https://abuseipdb.com/">AbuseIPDB</a> – Opprettholder en database med kjente farlige IPer; Gir en API for å sjekke og rapportere IPer.</li>
            <li><a href="https://www.megarbl.net/index.php">MegaRBL.net</a> – Opprettholder oppføringer av kjente spammere; Nyttig for å sjekke IP/ASN spam aktiviteter.</li>
            <li><a href="https://maikuolan.github.io/Vulnerability-Charts/">Sårbarhetskart</a> – Viser sikker/usikker versjon av ulike pakker (PHP, HHVM, osv).</li>
            <li><a href="https://maikuolan.github.io/Compatibility-Charts/">Kompatibilitetskart</a> – Viser kompatibilitetsinformasjon for ulike pakker (CIDRAM, phpMussel, osv).</li>
        </ul>';
