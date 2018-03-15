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
 * This file: Italian language data for the front-end (last modified: 2018.03.15).
 */

/** Prevents execution from outside of CIDRAM. */
if (!defined('CIDRAM')) {
    die('[CIDRAM] This should not be accessed directly.');
}

$CIDRAM['lang']['Extended Description: Bypasses'] = 'Un file per bypassare alcune delle firme predefinite normalmente inclusi nel pacchetto principale.';
$CIDRAM['lang']['Extended Description: CIDRAM'] = 'Il pacchetto principale (senza le firme, la documentazione, e la configurazione).';
$CIDRAM['lang']['Name: Bypasses'] = 'I firme predefinite bypass.';
$CIDRAM['lang']['Name: IPv4'] = 'File di firme per IPv4 (servizi cloud indesiderate e punti finali non umani).';
$CIDRAM['lang']['Name: IPv4-Bogons'] = 'File di firme per IPv4 (bogon/marziano CIDRs).';
$CIDRAM['lang']['Name: IPv4-ISPs'] = 'File di firme per IPv4 (ISP pericolosi e spam incline).';
$CIDRAM['lang']['Name: IPv4-Other'] = 'File di firme per IPv4 (CIDRs per i proxy, VPN e altri vari servizi indesiderati).';
$CIDRAM['lang']['Name: IPv6'] = 'File di firme per IPv6 (servizi cloud indesiderate e punti finali non umani).';
$CIDRAM['lang']['Name: IPv6-Bogons'] = 'File di firme per IPv6 (bogon/marziano CIDRs).';
$CIDRAM['lang']['Name: IPv6-ISPs'] = 'File di firme per IPv6 (ISP pericolosi e spam incline).';
$CIDRAM['lang']['Name: IPv6-Other'] = 'File di firme per IPv6 (CIDRs per i proxy, VPN e altri vari servizi indesiderati).';
$CIDRAM['lang']['bNav_home_logout'] = '<a href="?">Pagina Principale</a> | <a href="?cidram-page=logout">Disconnettersi</a>';
$CIDRAM['lang']['bNav_logout'] = '<a href="?cidram-page=logout">Disconnettersi</a>';
$CIDRAM['lang']['config_general_FrontEndLog'] = 'File per la registrazione di l\'accesso front-end tentativi di accesso. Specificare un nome di file, o lasciare vuoto per disabilitare.';
$CIDRAM['lang']['config_general_allow_gethostbyaddr_lookup'] = 'Consenti ricerche gethostbyaddr quando UDP non è disponibile? True = Sì [Predefinito]; False = No.';
$CIDRAM['lang']['config_general_ban_override'] = 'Sostituire "forbid_on_block" quando "infraction_limit" è superato? Quando si sostituisce: Richieste bloccate restituire una pagina vuota (file di modello non vengono utilizzati). 200 = Non sostituire [Predefinito]; 403 = Sostituire con "403 Forbidden"; 503 = Sostituire con "503 Service unavailable".';
$CIDRAM['lang']['config_general_default_algo'] = 'Definisce quale algoritmo da utilizzare per tutte le password e le sessioni in futuro. Opzioni: PASSWORD_DEFAULT (predefinito), PASSWORD_BCRYPT, PASSWORD_ARGON2I (richiede PHP >= 7.2.0).';
$CIDRAM['lang']['config_general_default_dns'] = 'Un elenco delimitato con virgole di server DNS da utilizzare per le ricerche dei nomi di host. Predefinito = "8.8.8.8,8.8.4.4" (Google DNS). AVVISO: Non modificare questa se non sai quello che stai facendo!';
$CIDRAM['lang']['config_general_disable_cli'] = 'Disabilita CLI? Modalità CLI è abilitato per predefinito, ma a volte può interferire con alcuni strumenti di test (come PHPUnit, per esempio) e altre applicazioni basate su CLI. Se non è necessario disattivare la modalità CLI, si dovrebbe ignorare questa direttiva. False = Abilita CLI [Predefinito]; True = Disabilita CLI.';
$CIDRAM['lang']['config_general_disable_frontend'] = 'Disabilita l\'accesso front-end? L\'accesso front-end può rendere CIDRAM più gestibile, ma può anche essere un potenziale rischio per la sicurezza. Si consiglia di gestire CIDRAM attraverso il back-end, quando possibile, ma l\'accesso front-end è previsto per quando non è possibile. Mantenerlo disabilitato tranne se hai bisogno. False = Abilita l\'accesso front-end; True = Disabilita l\'accesso front-end [Predefinito].';
$CIDRAM['lang']['config_general_disable_webfonts'] = 'Disabilita webfonts? True = Sì; False = No [Predefinito].';
$CIDRAM['lang']['config_general_emailaddr'] = 'Se si desidera, è possibile fornire un indirizzo email qui a dare utenti quando sono bloccati, per loro di utilizzare come punto di contatto per supporto e/o assistenza per il caso di che vengano bloccate per errore. AVVERTIMENTO: Qualunque sia l\'indirizzo email si fornisce qui sarà certamente acquisito dal spambots e raschietti/scrapers nel corso del suo essere usato qui, e così, è fortemente raccomandato che se si sceglie di fornire un indirizzo email qui, che si assicurare che l\'indirizzo email si fornisce qui è un indirizzo monouso e/o un indirizzo che si non ti dispiace essere spammato (in altre parole, probabilmente si non vuole usare il personale primaria o commerciale primaria indirizzi email).';
$CIDRAM['lang']['config_general_emailaddr_display_style'] = 'Come preferisci che l\'indirizzo email venga presentato agli utenti?';
$CIDRAM['lang']['config_general_forbid_on_block'] = 'Quale intestazioni dovrebbe CIDRAM rispondere con quando bloccano le richieste?';
$CIDRAM['lang']['config_general_force_hostname_lookup'] = 'Forzare la ricerca degli nome di host? True = Sì; False = No [Predefinito]. Le ricerche di nome di host vengono normalmente eseguite su base della necessità, ma può essere forzato a tutte le richieste. Ciò può essere utile come mezzo per fornire informazioni più dettagliate nei file di log, ma può anche avere un effetto leggermente negativo sulle prestazioni.';
$CIDRAM['lang']['config_general_hide_version'] = 'Nascondi informazioni sulla versione dai registri e l\'output della pagina? True = Sì; False = No [Predefinito].';
$CIDRAM['lang']['config_general_ipaddr'] = 'Dove trovare l\'indirizzo IP di collegamento richiesta? (Utile per servizi come Cloudflare e simili). Predefinito = REMOTE_ADDR. AVVISO: Non modificare questa se non sai quello che stai facendo!';
$CIDRAM['lang']['config_general_lang'] = 'Specifica la lingua predefinita per CIDRAM.';
$CIDRAM['lang']['config_general_log_banned_ips'] = 'Includi richieste bloccate da IP vietati nei file di log? True = Sì [Predefinito]; False = No.';
$CIDRAM['lang']['config_general_logfile'] = 'Un file leggibile dagli umani per la registrazione di tutti i tentativi di accesso bloccati. Specificare un nome di file, o lasciare vuoto per disabilitare.';
$CIDRAM['lang']['config_general_logfileApache'] = 'Un file nello stile di apache per la registrazione di tutti i tentativi di accesso bloccati. Specificare un nome di file, o lasciare vuoto per disabilitare.';
$CIDRAM['lang']['config_general_logfileSerialized'] = 'Un file serializzato per la registrazione di tutti i tentativi di accesso bloccati. Specificare un nome di file, o lasciare vuoto per disabilitare.';
$CIDRAM['lang']['config_general_maintenance_mode'] = 'Abilita la modalità di manutenzione? True = Sì; False = No [Predefinito]. Disattiva tutto tranne il front-end. A volte utile per l\'aggiornamento del CMS, dei framework, ecc.';
$CIDRAM['lang']['config_general_max_login_attempts'] = 'Numero massimo di tentativi di accesso.';
$CIDRAM['lang']['config_general_numbers'] = 'Come preferisci che i numeri siano visualizzati? Seleziona l\'esempio che ti sembra più corretto.';
$CIDRAM['lang']['config_general_protect_frontend'] = 'Specifica se le protezioni normalmente fornite da CIDRAM devono essere applicati al front-end. True = Sì [Predefinito]; False = No.';
$CIDRAM['lang']['config_general_search_engine_verification'] = 'Tentativo di verificare le richieste dai motori di ricerca? Verifica motori di ricerca assicura che non saranno vietate a seguito del superamento del limite infrazione (vieta dei motori di ricerca dal vostro sito web di solito hanno un effetto negativo sul vostro posizionamento sui motori di ricerca, SEO, ecc). Quando verificato, i motori di ricerca possono essere bloccati come al solito, ma non saranno vietate. Quando non verificato, è possibile per loro di essere vietate a seguito del superamento del limite infrazione. Inoltre, verifica dei motori di ricerca fornisce una protezione contro le richieste dei motori di ricerca falso e contro le entità potenzialmente dannosi mascherato da motori di ricerca (tali richieste verranno bloccate quando la verifica dei motori di ricerca è attivato). True = Attiva la verifica dei motori di ricerca [Predefinito]; False = Disattiva la verifica dei motori di ricerca.';
$CIDRAM['lang']['config_general_silent_mode'] = 'CIDRAM dovrebbe reindirizzare silenziosamente tutti i tentativi di accesso bloccati invece di visualizzare la pagina "Accesso Negato"? Se si, specificare la localizzazione di reindirizzare i tentativi di accesso bloccati. Se no, lasciare questo variabile vuoto.';
$CIDRAM['lang']['config_general_statistics'] = 'Monitorare le statistiche di utilizzo di CIDRAM? True = Sì; False = No [Predefinito].';
$CIDRAM['lang']['config_general_timeFormat'] = 'Il formato della data/ora di notazione usata da CIDRAM. Ulteriori opzioni possono essere aggiunti su richiesta.';
$CIDRAM['lang']['config_general_timeOffset'] = 'Fuso orario offset in minuti.';
$CIDRAM['lang']['config_general_timezone'] = 'Il vostro fuso orario.';
$CIDRAM['lang']['config_general_truncate'] = 'Troncare i file di log quando raggiungono una determinata dimensione? Il valore è la dimensione massima in B/KB/MB/GB/TB che un file di log può crescere prima di essere troncato. Il valore predefinito di 0KB disattiva il troncamento (i file di log possono crescere indefinitamente). Nota: Si applica ai singoli file di log! La dimensione dei file di log non viene considerata collettivamente.';
$CIDRAM['lang']['config_recaptcha_api'] = 'Quale API usare? V2 o Invisible?';
$CIDRAM['lang']['config_recaptcha_expiry'] = 'Numero di ore per ricordare le istanze reCAPTCHA.';
$CIDRAM['lang']['config_recaptcha_lockip'] = 'Legare reCAPTCHA per IP?';
$CIDRAM['lang']['config_recaptcha_lockuser'] = 'Legare reCAPTCHA per gli utenti?';
$CIDRAM['lang']['config_recaptcha_logfile'] = 'Registrare tutti i tentativi per reCAPTCHA? Se sì, specificare il nome da usare per il file di registrazione. Se non, lasciare questo variabile vuoto.';
$CIDRAM['lang']['config_recaptcha_secret'] = 'Questo valore deve corrispondere alla "secret key" per il vostro reCAPTCHA, che può essere trovato all\'interno del cruscotto di reCAPTCHA.';
$CIDRAM['lang']['config_recaptcha_signature_limit'] = 'Il numero massimo di firme consentito per essere innescato quando viene offerta un\'istanza di reCAPTCHA. Predefinito = 1. Se questo numero viene superato per una particolare richiesta, non verrà offerta un\'istanza di reCAPTCHA.';
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
$CIDRAM['lang']['config_template_data_Magnification'] = 'Ingrandimento del carattere. Predefinito = 1.';
$CIDRAM['lang']['config_template_data_css_url'] = 'URL del file CSS per i temi personalizzati.';
$CIDRAM['lang']['config_template_data_theme'] = 'Tema predefinito da utilizzare per CIDRAM.';
$CIDRAM['lang']['field_activate'] = 'Attivarlo';
$CIDRAM['lang']['field_banned'] = 'Vietato';
$CIDRAM['lang']['field_blocked'] = 'Bloccato';
$CIDRAM['lang']['field_clear'] = 'Revocarlo';
$CIDRAM['lang']['field_clear_all'] = 'Revoca tutto';
$CIDRAM['lang']['field_clickable_link'] = 'Link cliccabile';
$CIDRAM['lang']['field_component'] = 'Componente';
$CIDRAM['lang']['field_create_new_account'] = 'Crea un nuovo account';
$CIDRAM['lang']['field_deactivate'] = 'Disattivarlo';
$CIDRAM['lang']['field_delete_account'] = 'Elimina un account';
$CIDRAM['lang']['field_delete_file'] = 'Eliminare';
$CIDRAM['lang']['field_download_file'] = 'Scaricare';
$CIDRAM['lang']['field_edit_file'] = 'Modificare';
$CIDRAM['lang']['field_expiry'] = 'Scadenza';
$CIDRAM['lang']['field_false'] = 'False (Falso)';
$CIDRAM['lang']['field_file'] = 'File';
$CIDRAM['lang']['field_filename'] = 'Nome del file: ';
$CIDRAM['lang']['field_filetype_directory'] = 'Elenco';
$CIDRAM['lang']['field_filetype_info'] = '{EXT} File';
$CIDRAM['lang']['field_filetype_unknown'] = 'Sconosciuto';
$CIDRAM['lang']['field_first_seen'] = 'Prima Visto';
$CIDRAM['lang']['field_infractions'] = 'Infrazioni';
$CIDRAM['lang']['field_install'] = 'Installarlo';
$CIDRAM['lang']['field_ip_address'] = 'Indirizzo IP';
$CIDRAM['lang']['field_latest_version'] = 'Ultima Versione';
$CIDRAM['lang']['field_log_in'] = 'Accedi';
$CIDRAM['lang']['field_new_name'] = 'Nuovo nome:';
$CIDRAM['lang']['field_nonclickable_text'] = 'Testo non cliccabile';
$CIDRAM['lang']['field_ok'] = 'OK';
$CIDRAM['lang']['field_options'] = 'Opzioni';
$CIDRAM['lang']['field_password'] = 'Password';
$CIDRAM['lang']['field_permissions'] = 'Permessi';
$CIDRAM['lang']['field_range'] = 'Gamma (Primo – Finale)';
$CIDRAM['lang']['field_rename_file'] = 'Rinominare';
$CIDRAM['lang']['field_reset'] = 'Azzerare';
$CIDRAM['lang']['field_set_new_password'] = 'Imposta una nuova password';
$CIDRAM['lang']['field_size'] = 'Dimensione Totale: ';
$CIDRAM['lang']['field_size_GB'] = 'GB';
$CIDRAM['lang']['field_size_KB'] = 'KB';
$CIDRAM['lang']['field_size_MB'] = 'MB';
$CIDRAM['lang']['field_size_TB'] = 'TB';
$CIDRAM['lang']['field_size_bytes'] = 'byte';
$CIDRAM['lang']['field_status'] = 'Status';
$CIDRAM['lang']['field_system_timezone'] = 'Utilizza il fuso orario predefinito del sistema.';
$CIDRAM['lang']['field_tracking'] = 'Monitoraggio';
$CIDRAM['lang']['field_true'] = 'True (Vero)';
$CIDRAM['lang']['field_uninstall'] = 'Disinstallarlo';
$CIDRAM['lang']['field_update'] = 'Aggiornarlo';
$CIDRAM['lang']['field_update_all'] = 'Aggiorna tutto';
$CIDRAM['lang']['field_upload_file'] = 'Carica nuovo file';
$CIDRAM['lang']['field_username'] = 'Nome Utente';
$CIDRAM['lang']['field_verify'] = 'Verificarlo';
$CIDRAM['lang']['field_verify_all'] = 'Verifica tutto';
$CIDRAM['lang']['field_your_version'] = 'La Vostra Versione';
$CIDRAM['lang']['header_login'] = 'Per favore accedi per continuare.';
$CIDRAM['lang']['label_active_config_file'] = 'File di configurazione attivo: ';
$CIDRAM['lang']['label_banned'] = 'Richieste vietate';
$CIDRAM['lang']['label_blocked'] = 'Richieste bloccate';
$CIDRAM['lang']['label_branch'] = 'Branch più recente stabile:';
$CIDRAM['lang']['label_check_modules'] = 'Prova anche contro i moduli.';
$CIDRAM['lang']['label_cidram'] = 'Versione CIDRAM utilizzata:';
$CIDRAM['lang']['label_displaying'] = ['Visualizzazione di <span class="txtRd">%s</span> voce.', 'Visualizzazione di <span class="txtRd">%s</span> voci.'];
$CIDRAM['lang']['label_displaying_that_cite'] = ['Visualizzazione di <span class="txtRd">%1$s</span> voce che cita "%2$s".', 'Visualizzazione di <span class="txtRd">%1$s</span> voci che citano "%2$s".'];
$CIDRAM['lang']['label_expires'] = 'Scade: ';
$CIDRAM['lang']['label_false_positive_risk'] = 'Rischio di falsi positivi: ';
$CIDRAM['lang']['label_fmgr_cache_data'] = 'Dati di cache e file temporanei';
$CIDRAM['lang']['label_fmgr_disk_usage'] = 'Utilizzo del disco da parte di CIDRAM: ';
$CIDRAM['lang']['label_fmgr_free_space'] = 'Spazio libero su disco: ';
$CIDRAM['lang']['label_fmgr_total_disk_usage'] = 'Utilizzo del disco totale: ';
$CIDRAM['lang']['label_fmgr_total_space'] = 'Spazio totale su disco: ';
$CIDRAM['lang']['label_fmgr_updates_metadata'] = 'Componente aggiorna metadati';
$CIDRAM['lang']['label_hide'] = 'Nascondere';
$CIDRAM['lang']['label_never'] = 'Mai';
$CIDRAM['lang']['label_os'] = 'Sistema operativo utilizzata:';
$CIDRAM['lang']['label_other'] = 'Altro';
$CIDRAM['lang']['label_other-ActiveIPv4'] = 'File di firme IPv4 attivi';
$CIDRAM['lang']['label_other-ActiveIPv6'] = 'File di firme IPv6 attivi';
$CIDRAM['lang']['label_other-ActiveModules'] = 'Moduli attivi';
$CIDRAM['lang']['label_other-Since'] = 'Data d\'inizio';
$CIDRAM['lang']['label_php'] = 'Versione PHP utilizzata:';
$CIDRAM['lang']['label_reCAPTCHA'] = 'Tentativi di reCAPTCHA';
$CIDRAM['lang']['label_results'] = 'Risultati (%s in – %s respinto – %s accettato – %s combinato – %s su):';
$CIDRAM['lang']['label_sapi'] = 'SAPI utilizzata:';
$CIDRAM['lang']['label_show'] = 'Mostrare';
$CIDRAM['lang']['label_stable'] = 'Più recente stabile:';
$CIDRAM['lang']['label_sysinfo'] = 'Informazioni sul sistema:';
$CIDRAM['lang']['label_tests'] = 'Test:';
$CIDRAM['lang']['label_total'] = 'Totale';
$CIDRAM['lang']['label_unstable'] = 'Più recente instabile:';
$CIDRAM['lang']['link_accounts'] = 'Utenti';
$CIDRAM['lang']['link_cache_data'] = 'Dati della Cache';
$CIDRAM['lang']['link_cidr_calc'] = 'Calcolatrice CIDR';
$CIDRAM['lang']['link_config'] = 'Configurazione';
$CIDRAM['lang']['link_documentation'] = 'Documentazione';
$CIDRAM['lang']['link_file_manager'] = 'File Manager';
$CIDRAM['lang']['link_home'] = 'Pagina Principale';
$CIDRAM['lang']['link_ip_aggregator'] = 'Aggregatore IP';
$CIDRAM['lang']['link_ip_test'] = 'Test di IP';
$CIDRAM['lang']['link_ip_tracking'] = 'Monitoraggio IP';
$CIDRAM['lang']['link_logs'] = 'File di Log';
$CIDRAM['lang']['link_sections_list'] = 'Lista delle Sezioni';
$CIDRAM['lang']['link_statistics'] = 'Statistiche';
$CIDRAM['lang']['link_textmode'] = 'Formattazione del testo: <a href="%1$sfalse%2$s">Semplice</a> – <a href="%1$strue%2$s">Formattato</a> – <a href="%1$stally%2$s">Conteggio</a>';
$CIDRAM['lang']['link_updates'] = 'Aggiornamenti';
$CIDRAM['lang']['logs_logfile_doesnt_exist'] = 'Log selezionato non esiste!';
$CIDRAM['lang']['logs_no_logfile_selected'] = 'Nessun file di log selezionato.';
$CIDRAM['lang']['logs_no_logfiles_available'] = 'Nessun file di log disponibili.';
$CIDRAM['lang']['max_login_attempts_exceeded'] = 'Numero massimo di tentativi di accesso superato; Accesso negato.';
$CIDRAM['lang']['previewer_days'] = 'Giorni';
$CIDRAM['lang']['previewer_hours'] = 'Ore';
$CIDRAM['lang']['previewer_minutes'] = 'Minuti';
$CIDRAM['lang']['previewer_months'] = 'Mesi';
$CIDRAM['lang']['previewer_seconds'] = 'Secondi';
$CIDRAM['lang']['previewer_weeks'] = 'Settimane';
$CIDRAM['lang']['previewer_years'] = 'Anni';
$CIDRAM['lang']['response_accounts_already_exists'] = 'Un account con quel nome utente esiste già!';
$CIDRAM['lang']['response_accounts_created'] = 'Account creato con successo!';
$CIDRAM['lang']['response_accounts_deleted'] = 'Account eliminato con successo!';
$CIDRAM['lang']['response_accounts_doesnt_exist'] = 'Questo account non esiste.';
$CIDRAM['lang']['response_accounts_password_updated'] = 'Password aggiornato con successo!';
$CIDRAM['lang']['response_activated'] = 'Attivato con successo.';
$CIDRAM['lang']['response_activation_failed'] = 'Non poteva essere attivato!';
$CIDRAM['lang']['response_checksum_error'] = 'Errore di checksum! File respinto!';
$CIDRAM['lang']['response_component_successfully_installed'] = 'Componente installato con successo.';
$CIDRAM['lang']['response_component_successfully_uninstalled'] = 'Componente disinstallato con successo.';
$CIDRAM['lang']['response_component_successfully_updated'] = 'Componente aggiornato con successo.';
$CIDRAM['lang']['response_component_uninstall_error'] = 'C\'è stato un errore durante il tentativo di disinstallare il componente.';
$CIDRAM['lang']['response_configuration_updated'] = 'Configurazione aggiornato con successo.';
$CIDRAM['lang']['response_deactivated'] = 'Disattivato con successo.';
$CIDRAM['lang']['response_deactivation_failed'] = 'Non poteva essere disattivato!';
$CIDRAM['lang']['response_delete_error'] = 'Non riuscito a eliminare!';
$CIDRAM['lang']['response_directory_deleted'] = 'Elenco eliminato con successo!';
$CIDRAM['lang']['response_directory_renamed'] = 'Elenco rinominato con successo!';
$CIDRAM['lang']['response_error'] = 'Errore';
$CIDRAM['lang']['response_failed_to_install'] = 'Non è riuscito ad installare!';
$CIDRAM['lang']['response_failed_to_update'] = 'Non è riuscito ad aggiornare!';
$CIDRAM['lang']['response_file_deleted'] = 'File eliminato con successo!';
$CIDRAM['lang']['response_file_edited'] = 'File modificato con successo!';
$CIDRAM['lang']['response_file_renamed'] = 'File rinominato con successo!';
$CIDRAM['lang']['response_file_uploaded'] = 'File caricato con successo!';
$CIDRAM['lang']['response_login_invalid_password'] = 'Accedi non riuscito! Password non valida!';
$CIDRAM['lang']['response_login_invalid_username'] = 'Accedi non riuscito! Nome utente non esiste!';
$CIDRAM['lang']['response_login_password_field_empty'] = 'L\'input password era vuoto!';
$CIDRAM['lang']['response_login_username_field_empty'] = 'L\'input nome utente era vuoto!';
$CIDRAM['lang']['response_login_wrong_endpoint'] = 'Punto finale errato!';
$CIDRAM['lang']['response_no'] = 'No';
$CIDRAM['lang']['response_possible_problem_found'] = 'Possibile problema riscontrato.';
$CIDRAM['lang']['response_rename_error'] = 'Non riuscito a rinominare!';
$CIDRAM['lang']['response_statistics_cleared'] = 'Statistiche revocate.';
$CIDRAM['lang']['response_tracking_cleared'] = 'Monitoraggio revocato.';
$CIDRAM['lang']['response_updates_already_up_to_date'] = 'Aggiornato già.';
$CIDRAM['lang']['response_updates_not_installed'] = 'Componente non installato!';
$CIDRAM['lang']['response_updates_not_installed_php'] = 'Componente non installato (richiede PHP {V})!';
$CIDRAM['lang']['response_updates_outdated'] = 'Non aggiornato!';
$CIDRAM['lang']['response_updates_outdated_manually'] = 'Non aggiornato (si prega di aggiornare manualmente)!';
$CIDRAM['lang']['response_updates_outdated_php_version'] = 'Non aggiornato (richiede PHP {V})!';
$CIDRAM['lang']['response_updates_unable_to_determine'] = 'Incapace di determinare.';
$CIDRAM['lang']['response_upload_error'] = 'Non riuscito a caricare!';
$CIDRAM['lang']['response_verification_failed'] = 'Non è riuscito a verificare! Componente potrebbe essere danneggiato.';
$CIDRAM['lang']['response_verification_success'] = 'Verificato con successo! Nessun problema trovato.';
$CIDRAM['lang']['response_yes'] = 'Sì';
$CIDRAM['lang']['state_async_deny'] = 'Autorizzazioni non adeguate per eseguire richieste asincrone. Prova ad accedere di nuovo.';
$CIDRAM['lang']['state_cache_is_empty'] = 'La cache è vuota.';
$CIDRAM['lang']['state_complete_access'] = 'Accesso completo';
$CIDRAM['lang']['state_component_is_active'] = 'Componente è attivo.';
$CIDRAM['lang']['state_component_is_inactive'] = 'Componente è inattivo.';
$CIDRAM['lang']['state_component_is_provisional'] = 'Componente è provvisorio.';
$CIDRAM['lang']['state_default_password'] = 'Avvertimento: Utilizzando la password predefinita!';
$CIDRAM['lang']['state_ignored'] = 'Ignorato';
$CIDRAM['lang']['state_loading'] = 'Caricamento in corso...';
$CIDRAM['lang']['state_loadtime'] = 'Richiesta di pagina completata in <span class="txtRd">%s</span> secondi.';
$CIDRAM['lang']['state_logged_in'] = 'Connesso.';
$CIDRAM['lang']['state_logs_access_only'] = 'Accesso solo per i log';
$CIDRAM['lang']['state_maintenance_mode'] = 'Attenzione: La modalità di manutenzione è abilitata!';
$CIDRAM['lang']['state_password_not_valid'] = 'Avvertimento: Questo account non utilizzando una password valida!';
$CIDRAM['lang']['state_risk_high'] = 'Alto';
$CIDRAM['lang']['state_risk_low'] = 'A basso';
$CIDRAM['lang']['state_risk_medium'] = 'Medio';
$CIDRAM['lang']['state_sl_totals'] = 'Totali (Firme: <span class="txtRd">%s</span> – Sezioni di firma: <span class="txtRd">%s</span> – File di firma: <span class="txtRd">%s</span>).';
$CIDRAM['lang']['state_tracking'] = 'Attualmente monitoraggio di %s IP.';
$CIDRAM['lang']['switch-hide-non-outdated-set-false'] = 'Non nascondere l\'aggiornato';
$CIDRAM['lang']['switch-hide-non-outdated-set-true'] = 'Nascondere l\'aggiornato';
$CIDRAM['lang']['switch-hide-unused-set-false'] = 'Non nascondere il inutilizzato';
$CIDRAM['lang']['switch-hide-unused-set-true'] = 'Nascondere il inutilizzato';
$CIDRAM['lang']['switch-tracking-blocked-already-set-false'] = 'Non controllare i file di firme';
$CIDRAM['lang']['switch-tracking-blocked-already-set-true'] = 'Controllare i file di firme';
$CIDRAM['lang']['switch-tracking-hide-banned-blocked-set-false'] = 'Non nascondere gli IP vietati/bloccati';
$CIDRAM['lang']['switch-tracking-hide-banned-blocked-set-true'] = 'Nascondere gli IP vietati/bloccati';
$CIDRAM['lang']['tip_accounts'] = 'Salve, {username}.<br />La pagina di conti permette di controllare chi può accedere il front-end di CIDRAM.';
$CIDRAM['lang']['tip_cache_data'] = 'Salve, {username}.<br />Qui puoi rivedere il contenuto della cache.';
$CIDRAM['lang']['tip_cidr_calc'] = 'Salve, {username}.<br />La calcolatrice CIDR permette di calcolare che cosa CIDR un indirizzo IP appartiene.';
$CIDRAM['lang']['tip_config'] = 'Salve, {username}.<br />La pagina di configurazione permette di modificare la configurazione per CIDRAM dal front-end.';
$CIDRAM['lang']['tip_custom_ua'] = 'Inserisci l\'agente utente (user agent) qui (facoltativo).';
$CIDRAM['lang']['tip_donate'] = 'CIDRAM è offerto gratuitamente, ma se si vuole donare al progetto, è possibile farlo facendo clic sul pulsante donare.';
$CIDRAM['lang']['tip_enter_ip_here'] = 'Inserire IP qui.';
$CIDRAM['lang']['tip_enter_ips_here'] = 'Inserisci IP qui.';
$CIDRAM['lang']['tip_fe_cookie_warning'] = 'Nota: CIDRAM utilizza un cookie per autenticare gli accessi. Accedendo, ti dà il tuo consenso per creare e memorizzare un cookie dal tuo browser.';
$CIDRAM['lang']['tip_file_manager'] = 'Salve, {username}.<br />Il file manager consente di eliminare, modificare, caricare e scaricare file. Usare con cautela (si potrebbe rompere l\'installazione di questo).';
$CIDRAM['lang']['tip_home'] = 'Salve, {username}.<br />Questa è la pagina principale per il front-end di CIDRAM. Selezionare un collegamento dal menu di navigazione a sinistra per continuare.';
$CIDRAM['lang']['tip_ip_aggregator'] = 'Salve, {username}.<br />L\'aggregatore IP consente di esprimere IP e CIDR nel modo più piccolo possibile. Inserire i dati da aggregare e premere "OK".';
$CIDRAM['lang']['tip_ip_test'] = 'Salve, {username}.<br />La pagina di per il test di IP permette di testare se gli indirizzi IP sono bloccati dalle firme attualmente installati.';
$CIDRAM['lang']['tip_ip_test_module_switch'] = '(Quando non è selezionato, solo i file delle firme verranno testati).';
$CIDRAM['lang']['tip_ip_tracking'] = 'Salve, {username}.<br />La pagina di monitoraggio IP consente di verificare lo stato del monitoraggio degli indirizzi IP, per verificare quali di essi sono stati vietati, e di revocare il monitoraggio loro se si vuole farlo.';
$CIDRAM['lang']['tip_login'] = 'Nome utente predefinito: <span class="txtRd">admin</span> – Password predefinita: <span class="txtRd">password</span>';
$CIDRAM['lang']['tip_logs'] = 'Salve, {username}.<br />Selezionare un file di log dall\'elenco sottostante per visualizzare il contenuto di tale file di log.';
$CIDRAM['lang']['tip_sections_list'] = 'Salve, {username}.<br />Questa pagina elenca le sezioni presenti nei file di firma attualmente attivi.';
$CIDRAM['lang']['tip_see_the_documentation'] = 'Vedere la <a href="https://github.com/CIDRAM/CIDRAM/blob/master/_docs/readme.it.md#SECTION6">documentazione</a> per informazioni sulle varie direttive di configurazione ed i loro scopi.';
$CIDRAM['lang']['tip_statistics'] = 'Salve, {username}.<br />Questa pagina mostra alcune statistiche di utilizzo relative all\'installazione di CIDRAM.';
$CIDRAM['lang']['tip_statistics_disabled'] = 'Nota: Il monitoraggio delle statistiche è attualmente disattivato, ma può essere attivato tramite la pagina di configurazione.';
$CIDRAM['lang']['tip_updates'] = 'Salve, {username}.<br />La pagina degli aggiornamenti permette di installare, disinstallare e aggiornare i vari componenti di CIDRAM (il pacchetto di base, le firme, file per L10N, ecc).';
$CIDRAM['lang']['title_accounts'] = 'CIDRAM – Utenti';
$CIDRAM['lang']['title_cache_data'] = 'CIDRAM – Dati della Cache';
$CIDRAM['lang']['title_cidr_calc'] = 'CIDRAM – Calcolatrice CIDR';
$CIDRAM['lang']['title_config'] = 'CIDRAM – Configurazione';
$CIDRAM['lang']['title_file_manager'] = 'CIDRAM – File Manager';
$CIDRAM['lang']['title_home'] = 'CIDRAM – Pagina Principale';
$CIDRAM['lang']['title_ip_aggregator'] = 'CIDRAM – Aggregatore IP';
$CIDRAM['lang']['title_ip_test'] = 'CIDRAM – Test di IP';
$CIDRAM['lang']['title_ip_tracking'] = 'CIDRAM – Monitoraggio IP';
$CIDRAM['lang']['title_login'] = 'CIDRAM – Accedi';
$CIDRAM['lang']['title_logs'] = 'CIDRAM – File di Log';
$CIDRAM['lang']['title_sections_list'] = 'CIDRAM – Lista delle Sezioni';
$CIDRAM['lang']['title_statistics'] = 'CIDRAM – Statistiche';
$CIDRAM['lang']['title_updates'] = 'CIDRAM – Aggiornamenti';
$CIDRAM['lang']['warning'] = 'Avvertimenti:';
$CIDRAM['lang']['warning_php_1'] = 'La vostra versione di PHP non è più supportata attivamente! Si consiglia di aggiornarlo!';
$CIDRAM['lang']['warning_php_2'] = 'La vostra versione PHP è severamente vulnerabile! Si consiglia fortemente di aggiornarlo!';
$CIDRAM['lang']['warning_signatures_1'] = 'Non ci sono file di firme attivi!';

$CIDRAM['lang']['info_some_useful_links'] = 'Alcuni link utili:<ul>
            <li><a href="https://github.com/CIDRAM/CIDRAM/issues">Problemi di CIDRAM @ GitHub</a> – Pagina dei problemi per CIDRAM (supporto, assistenza, ecc).</li>
            <li><a href="https://wordpress.org/plugins/cidram/">CIDRAM @ WordPress.org</a> – Plugin per WordPress per CIDRAM.</li>
            <li><a href="https://sourceforge.net/projects/cidram/">CIDRAM @ SourceForge</a> – Una scarica specchio alternativa per CIDRAM.</li>
            <li><a href="https://websectools.com/">WebSecTools.com</a> – Una collezione di semplici strumenti per i webmaster per sicurezza del sito Web.</li>
            <li><a href="https://macmathan.info/blocklists">Blocchi raggio di MacMathan.info Range Blocks</a> – Contiene blocchi raggio opzionali che possono essere aggiunti a CIDRAM per bloccare qualsiasi paesi indesiderati di accedere al sito web.</li>
            <li><a href="https://www.facebook.com/groups/2204685680/">Global PHP Group @ Facebook</a> – Risorse di apprendimento e discussione per PHP.</li>
            <li><a href="https://php.earth/">PHP.earth</a> – Risorse di apprendimento e discussione per PHP.</li>
            <li><a href="http://bgp.he.net/">Hurricane Electric BGP Toolkit</a> – Ottenere CIDRs da ASNs, determinare le relazioni di ASNs, scopri ASNs basata su nomi di rete, ecc.</li>
            <li><a href="https://www.stopforumspam.com/forum/">Forum @ Stop Forum Spam</a> – Forum di discussione utile circa l\'arresto di forum spam.</li>
            <li><a href="https://radar.qrator.net/">Radar da Qrator</a> – Strumento utile per verificare la connettività di ASN nonché per varie altre informazioni utili riguardo ASNs.</li>
            <li><a href="http://www.ipdeny.com/ipblocks/">IP blocchi di paesi da IPdeny</a> – Un servizio fantastico e preciso per la generazione di firme per i paesi.</li>
            <li><a href="https://www.google.com/transparencyreport/safebrowsing/malware/">Google Malware Dashboard</a> – Visualizza i rapporti per quanto riguarda ai tassi di infezione da malware per ASN.</li>
            <li><a href="https://www.spamhaus.org/statistics/botnet-asn/">Il progetto Spamhaus</a> – Visualizza i rapporti per quanto riguarda ai tassi di infezione di botnet per ASN.</li>
            <li><a href="https://www.abuseat.org/public/asn.html">Lista blocco composito da Abuseat.org</a> – Visualizza i rapporti per quanto riguarda ai tassi di infezione di botnet per ASN.</li>
            <li><a href="https://abuseipdb.com/">AbuseIPDB</a> – Mantiene un database di conosciuti indirizzi IP abusivi; Fornisce una API per il controllo e la segnalazione di IP.</li>
            <li><a href="https://www.megarbl.net/index.php">MegaRBL.net</a> – Mantiene liste di spammer conosciuti; Utile per il controllo delle attività di spam dalla IP/ASN.</li>
            <li><a href="https://maikuolan.github.io/Vulnerability-Charts/">Mappe di Vulnerabilità</a> – Elenca le versioni sicure e non sicure di varie pacchetti (PHP, HHVM, ecc).</li>
            <li><a href="https://maikuolan.github.io/Compatibility-Charts/">Mappe di Compatibilità</a> – Elenca le informazioni sulla compatibilità per vari pacchetti (CIDRAM, phpMussel, ecc).</li>
        </ul>';
