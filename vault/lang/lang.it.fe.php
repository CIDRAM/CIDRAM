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
 * This file: Italian language data for the front-end (last modified: 2017.03.27).
 */

/** Prevents execution from outside of CIDRAM. */
if (!defined('CIDRAM')) {
    die('[CIDRAM] This should not be accessed directly.');
}

$CIDRAM['lang']['bNav_home_logout'] = '<a href="?">Pagina Principale</a> | <a href="?cidram-page=logout">Disconnettersi</a>';
$CIDRAM['lang']['bNav_logout'] = '<a href="?cidram-page=logout">Disconnettersi</a>';
$CIDRAM['lang']['config_general_ban_override'] = 'Sostituire "forbid_on_block" quando "infraction_limit" è superato? Quando si sostituisce: Richieste bloccate restituire una pagina vuota (file di modello non vengono utilizzati). 200 = Non sostituire [Predefinito]; 403 = Sostituire con "403 Forbidden"; 503 = Sostituire con "503 Service unavailable".';
$CIDRAM['lang']['config_general_default_dns'] = 'Un elenco delimitato con virgole di server DNS da utilizzare per le ricerche dei nomi di host. Predefinito = "8.8.8.8,8.8.4.4" (Google DNS). AVVISO: Non modificare questa se non sai quello che stai facendo!';
$CIDRAM['lang']['config_general_disable_cli'] = 'Disabilita CLI?';
$CIDRAM['lang']['config_general_disable_frontend'] = 'Disabilita l\'accesso front-end?';
$CIDRAM['lang']['config_general_disable_webfonts'] = 'Disabilita webfonts? True = Sì; False = No [Predefinito].';
$CIDRAM['lang']['config_general_emailaddr'] = 'Indirizzo e-mail per il supporto.';
$CIDRAM['lang']['config_general_forbid_on_block'] = 'Quale intestazioni dovrebbe CIDRAM rispondere con quando bloccano le richieste?';
$CIDRAM['lang']['config_general_FrontEndLog'] = 'File per la registrazione di l\'accesso front-end tentativi di accesso. Specificare un nome di file, o lasciare vuoto per disabilitare.';
$CIDRAM['lang']['config_general_ipaddr'] = 'Dove trovare l\'indirizzo IP di collegamento richiesta? (Utile per servizi come Cloudflare e simili). Predefinito = REMOTE_ADDR. AVVISO: Non modificare questa se non sai quello che stai facendo!';
$CIDRAM['lang']['config_general_lang'] = 'Specifica la lingua predefinita per CIDRAM.';
$CIDRAM['lang']['config_general_logfile'] = 'Un file leggibile dagli umani per la registrazione di tutti i tentativi di accesso bloccati. Specificare un nome di file, o lasciare vuoto per disabilitare.';
$CIDRAM['lang']['config_general_logfileApache'] = 'Un file nello stile di apache per la registrazione di tutti i tentativi di accesso bloccati. Specificare un nome di file, o lasciare vuoto per disabilitare.';
$CIDRAM['lang']['config_general_logfileSerialized'] = 'Un file serializzato per la registrazione di tutti i tentativi di accesso bloccati. Specificare un nome di file, o lasciare vuoto per disabilitare.';
$CIDRAM['lang']['config_general_log_banned_ips'] = 'Includi richieste bloccate da IP vietati nei file di log? True = Sì [Predefinito]; False = No.';
$CIDRAM['lang']['config_general_max_login_attempts'] = 'Numero massimo di tentativi di accesso.';
$CIDRAM['lang']['config_general_protect_frontend'] = 'Specifica se le protezioni normalmente fornite da CIDRAM devono essere applicati al front-end. True = Sì [Predefinito]; False = No.';
$CIDRAM['lang']['config_general_search_engine_verification'] = 'Tentativo di verificare le richieste dai motori di ricerca? Verifica motori di ricerca assicura che non saranno vietate a seguito del superamento del limite infrazione (vieta dei motori di ricerca dal vostro sito web di solito hanno un effetto negativo sul vostro posizionamento sui motori di ricerca, SEO, ecc). Quando verificato, i motori di ricerca possono essere bloccati come al solito, ma non saranno vietate. Quando non verificato, è possibile per loro di essere vietate a seguito del superamento del limite infrazione. Inoltre, verifica dei motori di ricerca fornisce una protezione contro le richieste dei motori di ricerca falso e contro le entità potenzialmente dannosi mascherato da motori di ricerca (tali richieste verranno bloccate quando la verifica dei motori di ricerca è attivato). True = Attiva la verifica dei motori di ricerca [Predefinito]; False = Disattiva la verifica dei motori di ricerca.';
$CIDRAM['lang']['config_general_silent_mode'] = 'CIDRAM dovrebbe reindirizzare silenziosamente tutti i tentativi di accesso bloccati invece di visualizzare la pagina "Accesso Negato"? Se si, specificare la localizzazione di reindirizzare i tentativi di accesso bloccati. Se no, lasciare questo variabile vuoto.';
$CIDRAM['lang']['config_general_timeOffset'] = 'Fuso orario offset in minuti.';
$CIDRAM['lang']['config_recaptcha_expiry'] = 'Numero di ore per ricordare le istanze reCAPTCHA.';
$CIDRAM['lang']['config_recaptcha_lockip'] = 'Legare reCAPTCHA per IP?';
$CIDRAM['lang']['config_recaptcha_lockuser'] = 'Legare reCAPTCHA per gli utenti?';
$CIDRAM['lang']['config_recaptcha_logfile'] = 'Registrare tutti i tentativi per reCAPTCHA? Se sì, specificare il nome da usare per il file di registrazione. Se non, lasciare questo variabile vuoto.';
$CIDRAM['lang']['config_recaptcha_secret'] = 'Questo valore deve corrispondere alla "secret key" per il vostro reCAPTCHA, che può essere trovato all\'interno del cruscotto di reCAPTCHA.';
$CIDRAM['lang']['config_recaptcha_sitekey'] = 'Questo valore deve corrispondere alla "site key" per il vostro reCAPTCHA, che può essere trovato all\'interno del cruscotto di reCAPTCHA.';
$CIDRAM['lang']['config_recaptcha_usemode'] = 'Definisce come CIDRAM dovrebbe usare reCAPTCHA (consultare la documentazione).';
$CIDRAM['lang']['config_signatures_block_bogons'] = 'Bloccare bogone/marziano CIDRs? Se aspetta i collegamenti al suo sito dall\'interno della rete locale, da localhost, o dalla LAN, questa direttiva deve essere impostata su false. Se si non aspetta queste tali connessioni, questa direttiva deve essere impostata su true.';
$CIDRAM['lang']['config_signatures_block_cloud'] = 'Bloccare CIDRs identificato come appartenente alla servizi webhosting/cloud? Se si utilizza un servizio di API dal suo sito o se si aspetta altri siti a collegare al suo sito, questa direttiva deve essere impostata su false. Se non, questa direttiva deve essere impostata su true.';
$CIDRAM['lang']['config_signatures_block_generic'] = 'Bloccare CIDRs generalmente consigliato per la lista nera? Questo copre qualsiasi firme che non sono contrassegnate come parte del qualsiasi delle altre più specifiche categorie di firme.';
$CIDRAM['lang']['config_signatures_block_proxies'] = 'Bloccare CIDRs identificato come appartenente alla servizi proxy? Se si richiede che gli utenti siano in grado di accedere al suo sito web dai servizi di proxy anonimi, questa direttiva deve essere impostata su false. Altrimenti, se non si richiede proxy anonimi, questa direttiva deve essere impostata su true come un mezzo per migliorare la sicurezza.';
$CIDRAM['lang']['config_signatures_block_spam'] = 'Bloccare CIDRs identificati come alto rischio per spam? A meno che si sperimentare problemi quando si fa così, generalmente, questo dovrebbe essere sempre impostata su true.';
$CIDRAM['lang']['config_signatures_default_tracktime'] = 'Quanti secondi per monitorare IP vietati dai moduli. Predefinito = 604800 (1 settimana).';
$CIDRAM['lang']['config_signatures_infraction_limit'] = 'Numero massimo di infrazioni un IP è permesso di incorrere prima di essere vietato dal monitoraggio IP. Predefinito = 10.';
$CIDRAM['lang']['config_signatures_ipv4'] = 'Un elenco dei file di firma IPv4 che CIDRAM dovrebbe tentare di utilizzare, delimitati da virgole.';
$CIDRAM['lang']['config_signatures_ipv6'] = 'Un elenco dei file di firma IPv6 che CIDRAM dovrebbe tentare di utilizzare, delimitati da virgole.';
$CIDRAM['lang']['config_signatures_modules'] = 'Un elenco di file moduli da caricare dopo l\'esecuzione delle firme IPv4/IPv6, delimitati da virgole.';
$CIDRAM['lang']['config_signatures_track_mode'] = 'Quando devono infrazioni essere contati? False = Quando IP sono bloccati da moduli. True = Quando IP sono bloccati per qualsiasi motivo.';
$CIDRAM['lang']['config_template_data_css_url'] = 'URL del file CSS per i temi personalizzati.';
$CIDRAM['lang']['field_activate'] = 'Attivarlo';
$CIDRAM['lang']['field_banned'] = 'Vietato';
$CIDRAM['lang']['field_blocked'] = 'Bloccato';
$CIDRAM['lang']['field_clear'] = 'Revocarlo';
$CIDRAM['lang']['field_component'] = 'Componente';
$CIDRAM['lang']['field_create_new_account'] = 'Crea un nuovo account';
$CIDRAM['lang']['field_deactivate'] = 'Disattivarlo';
$CIDRAM['lang']['field_delete_account'] = 'Elimina un account';
$CIDRAM['lang']['field_delete_file'] = 'Eliminare';
$CIDRAM['lang']['field_download_file'] = 'Scaricare';
$CIDRAM['lang']['field_edit_file'] = 'Modificare';
$CIDRAM['lang']['field_expiry'] = 'Scadenza';
$CIDRAM['lang']['field_file'] = 'File';
$CIDRAM['lang']['field_filename'] = 'Nome del file: ';
$CIDRAM['lang']['field_filetype_directory'] = 'Elenco';
$CIDRAM['lang']['field_filetype_info'] = '{EXT} File';
$CIDRAM['lang']['field_filetype_unknown'] = 'Sconosciuto';
$CIDRAM['lang']['field_infractions'] = 'Infrazioni';
$CIDRAM['lang']['field_install'] = 'Installarlo';
$CIDRAM['lang']['field_ip_address'] = 'Indirizzo IP';
$CIDRAM['lang']['field_latest_version'] = 'Ultima Versione';
$CIDRAM['lang']['field_log_in'] = 'Accedi';
$CIDRAM['lang']['field_new_name'] = 'Nuovo nome:';
$CIDRAM['lang']['field_ok'] = 'OK';
$CIDRAM['lang']['field_options'] = 'Opzioni';
$CIDRAM['lang']['field_password'] = 'Password';
$CIDRAM['lang']['field_permissions'] = 'Permessi';
$CIDRAM['lang']['field_range'] = 'Gamma (Primo – Finale)';
$CIDRAM['lang']['field_rename_file'] = 'Rinominare';
$CIDRAM['lang']['field_reset'] = 'Azzerare';
$CIDRAM['lang']['field_set_new_password'] = 'Imposta una nuova password';
$CIDRAM['lang']['field_size'] = 'Dimensione Totale: ';
$CIDRAM['lang']['field_size_bytes'] = 'byte';
$CIDRAM['lang']['field_size_GB'] = 'GB';
$CIDRAM['lang']['field_size_KB'] = 'KB';
$CIDRAM['lang']['field_size_MB'] = 'MB';
$CIDRAM['lang']['field_size_TB'] = 'TB';
$CIDRAM['lang']['field_status'] = 'Status';
$CIDRAM['lang']['field_tracking'] = 'Monitoraggio';
$CIDRAM['lang']['field_uninstall'] = 'Disinstallarlo';
$CIDRAM['lang']['field_update'] = 'Aggiornarlo';
$CIDRAM['lang']['field_upload_file'] = 'Carica Nuovo File';
$CIDRAM['lang']['field_username'] = 'Nome Utente';
$CIDRAM['lang']['field_your_version'] = 'La Vostra Versione';
$CIDRAM['lang']['header_login'] = 'Per favore accedi per continuare.';
$CIDRAM['lang']['link_accounts'] = 'Utenti';
$CIDRAM['lang']['link_cidr_calc'] = 'Calcolatrice CIDR';
$CIDRAM['lang']['link_config'] = 'Configurazione';
$CIDRAM['lang']['link_documentation'] = 'Documentazione';
$CIDRAM['lang']['link_file_manager'] = 'File Manager';
$CIDRAM['lang']['link_home'] = 'Pagina Principale';
$CIDRAM['lang']['link_ip_test'] = 'Test di IP';
$CIDRAM['lang']['link_ip_tracking'] = 'Monitoraggio IP';
$CIDRAM['lang']['link_logs'] = 'File di Log';
$CIDRAM['lang']['link_updates'] = 'Aggiornamenti';
$CIDRAM['lang']['logs_logfile_doesnt_exist'] = 'Log selezionato non esiste!';
$CIDRAM['lang']['logs_no_logfiles_available'] = 'Nessun file di log disponibili.';
$CIDRAM['lang']['logs_no_logfile_selected'] = 'Nessun file di log selezionato.';
$CIDRAM['lang']['max_login_attempts_exceeded'] = 'Numero massimo di tentativi di accesso superato; Accesso negato.';
$CIDRAM['lang']['response_accounts_already_exists'] = 'Un account con quel nome utente esiste già!';
$CIDRAM['lang']['response_accounts_created'] = 'Account creato con successo!';
$CIDRAM['lang']['response_accounts_deleted'] = 'Account eliminato con successo!';
$CIDRAM['lang']['response_accounts_doesnt_exist'] = 'Questo account non esiste.';
$CIDRAM['lang']['response_accounts_password_updated'] = 'Password aggiornato con successo!';
$CIDRAM['lang']['response_activated'] = 'Attivato con successo.';
$CIDRAM['lang']['response_activation_failed'] = 'Non poteva essere attivato!';
$CIDRAM['lang']['response_component_successfully_installed'] = 'Componente installato con successo.';
$CIDRAM['lang']['response_component_successfully_uninstalled'] = 'Componente disinstallato con successo.';
$CIDRAM['lang']['response_component_successfully_updated'] = 'Componente aggiornato con successo.';
$CIDRAM['lang']['response_component_uninstall_error'] = 'C\'è stato un errore durante il tentativo di disinstallare il componente.';
$CIDRAM['lang']['response_component_update_error'] = 'C\'è stato un errore durante il tentativo di aggiornare il componente.';
$CIDRAM['lang']['response_configuration_updated'] = 'Configurazione aggiornato con successo.';
$CIDRAM['lang']['response_deactivated'] = 'Disattivato con successo.';
$CIDRAM['lang']['response_deactivation_failed'] = 'Non poteva essere disattivato!';
$CIDRAM['lang']['response_delete_error'] = 'Non riuscito a eliminare!';
$CIDRAM['lang']['response_directory_deleted'] = 'Elenco eliminato con successo!';
$CIDRAM['lang']['response_directory_renamed'] = 'Elenco rinominato con successo!';
$CIDRAM['lang']['response_error'] = 'Errore';
$CIDRAM['lang']['response_file_deleted'] = 'File eliminato con successo!';
$CIDRAM['lang']['response_file_edited'] = 'File modificato con successo!';
$CIDRAM['lang']['response_file_renamed'] = 'File rinominato con successo!';
$CIDRAM['lang']['response_file_uploaded'] = 'File caricato con successo!';
$CIDRAM['lang']['response_login_invalid_password'] = 'Accedi non riuscito! Password non valida!';
$CIDRAM['lang']['response_login_invalid_username'] = 'Accedi non riuscito! Nome utente non esiste!';
$CIDRAM['lang']['response_login_password_field_empty'] = 'L\'input password era vuoto!';
$CIDRAM['lang']['response_login_username_field_empty'] = 'L\'input nome utente era vuoto!';
$CIDRAM['lang']['response_no'] = 'No';
$CIDRAM['lang']['response_rename_error'] = 'Non riuscito a rinominare!';
$CIDRAM['lang']['response_tracking_cleared'] = 'Monitoraggio revocato.';
$CIDRAM['lang']['response_updates_already_up_to_date'] = 'Aggiornato già.';
$CIDRAM['lang']['response_updates_not_installed'] = 'Componente non installato!';
$CIDRAM['lang']['response_updates_not_installed_php'] = 'Componente non installato (richiede PHP {V})!';
$CIDRAM['lang']['response_updates_outdated'] = 'Non aggiornato!';
$CIDRAM['lang']['response_updates_outdated_manually'] = 'Non aggiornato (si prega di aggiornare manualmente)!';
$CIDRAM['lang']['response_updates_outdated_php_version'] = 'Non aggiornato (richiede PHP {V})!';
$CIDRAM['lang']['response_updates_unable_to_determine'] = 'Incapace di determinare.';
$CIDRAM['lang']['response_upload_error'] = 'Non riuscito a caricare!';
$CIDRAM['lang']['response_yes'] = 'Sì';
$CIDRAM['lang']['state_complete_access'] = 'Accesso completo';
$CIDRAM['lang']['state_component_is_active'] = 'Componente è attivo.';
$CIDRAM['lang']['state_component_is_inactive'] = 'Componente è inattivo.';
$CIDRAM['lang']['state_component_is_provisional'] = 'Componente è provvisorio.';
$CIDRAM['lang']['state_default_password'] = 'Avvertimento: Utilizzando la password predefinita!';
$CIDRAM['lang']['state_logged_in'] = 'Connesso.';
$CIDRAM['lang']['state_logs_access_only'] = 'Accesso solo per i log';
$CIDRAM['lang']['state_password_not_valid'] = 'Avvertimento: Questo account non utilizzando una password valida!';
$CIDRAM['lang']['switch-hide-non-outdated-set-false'] = 'Non nascondere l\'aggiornato';
$CIDRAM['lang']['switch-hide-non-outdated-set-true'] = 'Nascondere l\'aggiornato';
$CIDRAM['lang']['switch-hide-unused-set-false'] = 'Non nascondere il inutilizzato';
$CIDRAM['lang']['switch-hide-unused-set-true'] = 'Nascondere il inutilizzato';
$CIDRAM['lang']['tip_accounts'] = 'Salve, {username}.<br />La pagina di conti permette di controllare chi può accedere il front-end di CIDRAM.';
$CIDRAM['lang']['tip_cidr_calc'] = 'Salve, {username}.<br />La calcolatrice CIDR permette di calcolare che cosa CIDR un indirizzo IP appartiene.';
$CIDRAM['lang']['tip_config'] = 'Salve, {username}.<br />La pagina di configurazione permette di modificare la configurazione per CIDRAM dal front-end.';
$CIDRAM['lang']['tip_donate'] = 'CIDRAM è offerto gratuitamente, ma se si vuole donare al progetto, è possibile farlo facendo clic sul pulsante donare.';
$CIDRAM['lang']['tip_enter_ips_here'] = 'Inserisci IP qui.';
$CIDRAM['lang']['tip_enter_ip_here'] = 'Inserire IP qui.';
$CIDRAM['lang']['tip_file_manager'] = 'Salve, {username}.<br />Il file manager consente di eliminare, modificare, caricare e scaricare file. Usare con cautela (si potrebbe rompere l\'installazione di questo).';
$CIDRAM['lang']['tip_home'] = 'Salve, {username}.<br />Questa è la pagina principale per il front-end di CIDRAM. Selezionare un collegamento dal menu di navigazione a sinistra per continuare.';
$CIDRAM['lang']['tip_ip_test'] = 'Salve, {username}.<br />La pagina di per il test di IP permette di testare se gli indirizzi IP sono bloccati dalle firme attualmente installati.';
$CIDRAM['lang']['tip_ip_tracking'] = 'Salve, {username}.<br />La pagina di monitoraggio IP consente di verificare lo stato del monitoraggio degli indirizzi IP, per verificare quali di essi sono stati vietati, e di revocare il monitoraggio loro se si vuole farlo.';
$CIDRAM['lang']['tip_login'] = 'Nome utente predefinito: <span class="txtRd">admin</span> – Password predefinita: <span class="txtRd">password</span>';
$CIDRAM['lang']['tip_logs'] = 'Salve, {username}.<br />Selezionare un file di log dall\'elenco sottostante per visualizzare il contenuto di tale file di log.';
$CIDRAM['lang']['tip_see_the_documentation'] = 'Vedere la <a href="https://github.com/Maikuolan/CIDRAM/blob/master/_docs/readme.it.md#SECTION6">documentazione</a> per informazioni sulle varie direttive di configurazione ed i loro scopi.';
$CIDRAM['lang']['tip_updates'] = 'Salve, {username}.<br />La pagina degli aggiornamenti permette di installare, disinstallare e aggiornare i vari componenti di CIDRAM (il pacchetto di base, le firme, file per L10N, ecc).';
$CIDRAM['lang']['title_accounts'] = 'CIDRAM – Utenti';
$CIDRAM['lang']['title_cidr_calc'] = 'CIDRAM – Calcolatrice CIDR';
$CIDRAM['lang']['title_config'] = 'CIDRAM – Configurazione';
$CIDRAM['lang']['title_file_manager'] = 'CIDRAM – File Manager';
$CIDRAM['lang']['title_home'] = 'CIDRAM – Pagina Principale';
$CIDRAM['lang']['title_ip_test'] = 'CIDRAM – Test di IP';
$CIDRAM['lang']['title_ip_tracking'] = 'CIDRAM – Monitoraggio IP';
$CIDRAM['lang']['title_login'] = 'CIDRAM – Accedi';
$CIDRAM['lang']['title_logs'] = 'CIDRAM – File di Log';
$CIDRAM['lang']['title_updates'] = 'CIDRAM – Aggiornamenti';

$CIDRAM['lang']['info_some_useful_links'] = 'Alcuni link utili:<ul>
            <li><a href="https://github.com/Maikuolan/CIDRAM/issues">CIDRAM Issues @ GitHub</a> – Pagina dei problemi per CIDRAM (supporto, assistenza, ecc).</li>
            <li><a href="http://www.spambotsecurity.com/forum/viewforum.php?f=61">CIDRAM @ Spambot Security</a> – Forum di discussione per CIDRAM (supporto, assistenza, ecc).</li>
            <li><a href="https://wordpress.org/plugins/cidram/">CIDRAM @ Wordpress.org</a> – Plugin per Wordpress per CIDRAM.</li>
            <li><a href="https://sourceforge.net/projects/cidram/">CIDRAM @ SourceForge</a> – Una scarica specchio alternativa per CIDRAM.</li>
            <li><a href="https://websectools.com/">WebSecTools.com</a> – Una collezione di semplici strumenti per i webmaster per sicurezza del sito Web.</li>
            <li><a href="https://macmathan.info/blocklists">MacMathan.info Range Blocks</a> – Contiene blocchi raggio opzionali che possono essere aggiunti a CIDRAM per bloccare qualsiasi paesi indesiderati di accedere al sito web.</li>
            <li><a href="https://www.facebook.com/groups/2204685680/">International PHP Group @ Facebook</a> – Risorse di apprendimento e discussione per PHP.</li>
            <li><a href="https://wwphp-fb.github.io/">International PHP Group @ GitHub</a> – Risorse di apprendimento e discussione per PHP.</li>
            <li><a href="http://bgp.he.net/">Hurricane Electric BGP Toolkit</a> – Ottenere CIDRs da ASNs, determinare le relazioni di ASNs, scopri ASNs basata su nomi di rete, ecc.</li>
            <li><a href="https://www.stopforumspam.com/forum/">Forum @ Stop Forum Spam</a> – Forum di discussione utile circa l\'arresto di forum spam.</li>
            <li><a href="https://www.stopforumspam.com/aggregate">IP Aggregator @ Stop Forum Spam</a> – Strumento di aggregazione utile per IPv4 IPs.</li>
            <li><a href="https://radar.qrator.net/">Radar by Qrator</a> – Strumento utile per verificare la connettività di ASN nonché per varie altre informazioni utili riguardo ASNs.</li>
            <li><a href="http://www.ipdeny.com/ipblocks/">IPdeny IP country blocks</a> – Un servizio fantastico e preciso per la generazione di firme per i paesi.</li>
            <li><a href="https://www.google.com/transparencyreport/safebrowsing/malware/">Google Malware Dashboard</a> – Visualizza i rapporti per quanto riguarda ai tassi di infezione da malware per ASN.</li>
            <li><a href="https://www.spamhaus.org/statistics/botnet-asn/">The Spamhaus Project</a> – Visualizza i rapporti per quanto riguarda ai tassi di infezione di botnet per ASN.</li>
            <li><a href="http://www.abuseat.org/asn.html">Abuseat.org\'s Composite Blocking List</a> – Visualizza i rapporti per quanto riguarda ai tassi di infezione di botnet per ASN.</li>
            <li><a href="https://abuseipdb.com/">AbuseIPDB</a> – Mantiene un database di conosciuti indirizzi IP abusivi; Fornisce una API per il controllo e la segnalazione di IP.</li>
            <li><a href="https://www.megarbl.net/index.php">MegaRBL.net</a> – Mantiene liste di spammer conosciuti; Utile per il controllo delle attività di spam dalla IP/ASN.</li>
        </ul>';
