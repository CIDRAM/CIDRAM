## Documentazione per CIDRAM (Italiano).

### Contenuti
- 1. [PREAMBOLO](#SECTION1)
- 2. [COME INSTALLARE](#SECTION2)
- 3. [COME USARE](#SECTION3)
- 4. [GESTIONE FRONT-END](#SECTION4)
- 5. [FILE INCLUSI IN QUESTO PACCHETTO](#SECTION5)
- 6. [OPZIONI DI CONFIGURAZIONE](#SECTION6)
- 7. [FIRMA FORMATO](#SECTION7)
- 8. [CONOSCIUTI COMPATIBILITÀ PROBLEMI](#SECTION8)
- 9. [DOMANDE FREQUENTI (FAQ)](#SECTION9)
- 10. *Riservato per future aggiunte alla documentazione.*
- 11. [INFORMAZIONE LEGALE](#SECTION11)

*Nota per quanto riguarda le traduzioni: In caso di errori (per esempio, discrepanze tra le traduzioni, errori di battitura, ecc), la versione Inglese del README è considerata la versione originale e autorevole. Se trovate errori, il vostro aiuto a correggerli sarebbe il benvenuto.*

---


### 1. <a name="SECTION1"></a>PREAMBOLO

CIDRAM (Classless Inter-Domain Routing Access Manager) è uno script PHP progettato per proteggere i siti web bloccando le richieste provenienti da indirizzi IP considerati come fonti di traffico indesiderato, includendo (ma non limitato a) il traffico proveniente da punti d'accesso non umani, servizi cloud, spambots, scrapers, ecc. Questo è possibile calcolando i possibili CIDR degli indirizzi IP forniti da richieste in entrata e poi confrontando questi possibili CIDR contro i suoi file di firme (queste file di firme contengono liste di CIDR di indirizzi IP considerati come fonti di traffico indesiderato); Se vengono trovati riscontri, le richieste sono bloccate.

*(Vedere: [Che cos'è un "CIDR"?](#WHAT_IS_A_CIDR)).*

CIDRAM COPYRIGHT 2016 e oltre GNU/GPLv2 Caleb M (Maikuolan).

Questo script è un software "libero"; è possibile ridistribuirlo e/o modificarlo sotto i termini della GNU General Public License come pubblicato dalla Free Software Foundation; o la versione 2 della licenza, o (a propria scelta) una versione successiva. Questo script è distribuito nella speranza che possa essere utile, ma SENZA ALCUNA GARANZIA; senza neppure la implicita garanzia di COMMERCIABILITÀ o IDONEITÀ PER UN PARTICOLARE SCOPO. Vedere la GNU General Public License per ulteriori dettagli, situato nella `LICENSE.txt` file e disponibili anche da:
- <http://www.gnu.org/licenses/>.
- <http://opensource.org/licenses/>.

Questo documento ed il pacchetto associato ad esso possono essere scaricati liberamente da [GitHub](https://cidram.github.io/).

---


### 2. <a name="SECTION2"></a>COME INSTALLARE

#### 2.0 INSTALLAZIONE MANUALE

1) Continuando la lettura, si suppone che hai già scaricato una copia dello script, decompresso il contenuto e lo hai collocato da qualche parte sul tuo terminale. Da qui, ti consigliamo di determinare dove sulla macchina o CMS si desidera inserire quei contenuti. Una cartella come `/public_html/cidram/` o simile (sebbene, non è importante quale si sceglie, purché sia qualcosa di sicuro e che ti soddisfi) sarà sufficiente. *Prima di iniziare il caricamento, continua a leggere..*

2) Rinomina `config.ini.RenameMe` in `config.ini` (situato nella cartella `vault`), e facoltativamente (fortemente consigliata per gli utenti avanzati, ma non è consigliata per i principianti o per gli inesperti) aprirlo e impostare le opzioni come meglio si crede, in qualsiasi modo risulti appropriato per la vostra particolare configurazione (questo file contiene tutte le direttive disponibili per CIDRAM; sopra ogni opzione dovrebbe esserci un breve commento di documentazione che descrive ciò che fa e ciò a cui serve). Salvare il file, chiudere.

3) Carica i contenuti (CIDRAM e i suoi file) nella cartella che avete scelto in precedenza (non è necessario includere i file `*.txt`/`*.md`, ma in generale, si dovrebbe caricare tutto).

4) Impostate i permessi (CHMOD) della cartella `vault` a "755" (se ci sono problemi si può provare "777", ma questo è meno sicuro). La cartella principale che comprende i contenuti (quella scelta in precedenza), solitamente, può essere lasciato solo, ma lo stato CHMOD dovrebbe essere controllato se hai avuto problemi di autorizzazioni in passato sul vostro sistema (di default, dovrebbe essere qualcosa simile a "755"). In breve: Perché il pacchetto funzioni correttamente, PHP deve essere in grado di leggere e scrivere file all'interno della cartella `vault`. Molte cose (aggiornamento, registrazione, ecc) non saranno possibili, se PHP non può scrivere nella cartella `vault`, e il pacchetto non funzionerà affatto se PHP non può leggere dalla cartella `vault`. Tuttavia, per una sicurezza ottimale, la cartella `vault` NON deve essere accessibile al pubblico (informazioni sensibili, come le informazioni contenute da `config.ini` o `frontend.dat`, potrebbero essere esposte a potenziali aggressori se la cartella `vault` è pubblicamente accessibile).

5) Successivamente, sarà necessario "collegare" CIDRAM al vostro sistema o CMS. Ci sono diversi modi in cui è possibile collegare script come CIDRAM al vostro sistema o CMS, ma il più semplice è di inserire lo script all'inizio di un file del vostro sistema o CMS (quello che generalmente sarà caricato ogni volta che qualcuno accede a una pagina attraverso il vostro sito) utilizzando un comando `require` o `include`. Solitamente, questo file è memorizzato in una cartella, ad esempio `/includes`, `/assets` o `/functions`, e spesso può essere chiamato come `init.php`, `common_functions.php`, `functions.php` o simili. Scegliete accuratamente questo file tra quelli che compongono il vostro sistema o CMS; nel caso in cui trovate difficoltà nel determinare questo file, visitate la pagina di problemi/issues di CIDRAM per ricevere assistenza. Per fare ciò [utilizzare `require` o `include`], inserire la seguente riga di codice in cima al core file identificato in precedenza, sostituendo la stringa contenuta all'interno delle virgolette con l'indirizzo esatto del file `loader.php` (l'indirizzo locale, non l'indirizzo HTTP; sarà simile all'indirizzo citato in precedenza).

`<?php require '/user_name/public_html/cidram/loader.php'; ?>`

Salvare il file, chiudere, caricare di nuovo.

-- IN ALTERNATIVA --

Se stai usando un web server Apache e se si ha accesso al file `php.ini`, è possibile utilizzare la direttiva `auto_prepend_file` per pre-caricare CIDRAM ogni volta che viene effettuata una qualsiasi richiesta PHP. Qualcosa come:

`auto_prepend_file = "/user_name/public_html/cidram/loader.php"`

O questo nel `.htaccess` file:

`php_value auto_prepend_file "/user_name/public_html/cidram/loader.php"`

6) Questo è tutto! :-)

#### 2.1 INSTALLARE CON COMPOSER

[CIDRAM è registrata con Packagist](https://packagist.org/packages/cidram/cidram), e così, se si ha familiarità con Composer, è possibile utilizzare Composer per l'installazione di CIDRAM (è comunque necessario intervenire sul file di configurazione e includere `loader.php`; vedere "Installazione manuale" passi 2 e 5).

`composer require cidram/cidram`

#### 2.2 INSTALLARE PER WORDPRESS

Se si desidera utilizzare CIDRAM con WordPress, è possibile ignorare tutte le istruzioni di cui sopra. [CIDRAM è registrato come un plugin con il database dei plugin di WordPress](https://wordpress.org/plugins/cidram/), ed è possibile installare CIDRAM direttamente dalla pagina dei Plugin. È possibile installarlo nello stesso modo di qualsiasi altro plugin, e non sono necessari altri interventi. Proprio come con gli altri metodi di installazione, è possibile personalizzare l'installazione modificando il contenuto del file `config.ini` o utilizzando la pagina di configurazione del front-end. Se si attiva il front-end per CIDRAM e si aggiorna CIDRAM utilizzando la pagina degli aggiornamenti, questo si sincronizza automaticamente con le informazioni sulla versione del plugin visualizzate nella pagina dei plugin.

*Avvertimento: L'aggiornamento di CIDRAM tramite il dashboard dei plugin provoca un'installazione pulita! Se hai personalizzato l'installazione (cambiato la tua configurazione, installati i moduli, ecc), queste personalizzazioni verranno perse quando si aggiorna tramite la pagina dei plugin! I file di registro verranno persi anche quando vengono aggiornati tramite la pagina dei plugin! Per preservare i file di registro e le personalizzazioni, aggiorna tramite la pagina di aggiornamenti del front-end per CIDRAM.*

---


### 3. <a name="SECTION3"></a>COME USARE

CIDRAM dovrebbe bloccare automaticamente le richieste indesiderate al suo sito senza richiedere alcuna assistenza manuale, a parte la sua installazione iniziale.

È possibile personalizzare la sua configurazione e quali CIDR devono essere bloccati, modificando il vostro file di configurazione e/o file di firme.

Se si incontrano dei falsi positivi, per favore, contattatemi e fatemelo sapere. *(Vedere: [Che cosa è un "falso positivo"?](#WHAT_IS_A_FALSE_POSITIVE)).*

CIDRAM può essere aggiornato manualmente o tramite il front-end. CIDRAM può anche essere aggiornato tramite Composer o WordPress, se originariamente installato tramite tali mezzi.

---


### 4. <a name="SECTION4"></a>GESTIONE FRONT-END

#### 4.0 QUAL È IL FRONT-END.

Il front-end fornisce un modo conveniente e facile da mantenere, gestire e aggiornare l'installazione CIDRAM. È possibile visualizzare, condividere e scaricare file di log attraverso la pagina di log, è possibile modificare la configurazione attraverso la pagina di configurazione, è possibile installare e disinstallare i componenti attraverso la pagina degli aggiornamenti, e si può caricare, scaricare e modificare i file nel vault tramite il file manager.

Il front-end è disabilitato per impostazione predefinita al fine di prevenire l'accesso non autorizzato (l'accesso non autorizzato potrebbe avere conseguenze significative per il vostro sito e la sua sicurezza). Istruzioni per l'abilitazione si sono compresi sotto di questo paragrafo.

#### 4.1 COME ATTIVARE IL FRONT-END.

1) Trova la direttiva `disable_frontend` dentro `config.ini`, e impostarlo su `false` (sarà `true` per impostazione predefinita).

2) Accedi `loader.php` dal browser (per esempio, `http://localhost/cidram/loader.php`).

3) Accedi con il nome utente e la password predefinita (admin/password).

Nota: Dopo aver effettuato l'accesso per la prima volta, al fine di impedire l'accesso non autorizzato al front-end, si dovrebbe cambiare immediatamente il nome utente e la password! Questo è molto importante, perché è possibile caricare codice PHP arbitrario al suo sito web attraverso il front-end.

Inoltre, per una sicurezza ottimale, si consiglia vivamente di abilitare "l'autenticazione a due fattori" per tutti i conti front-end (istruzioni fornite di seguito).

#### 4.2 COME UTILIZZARE IL FRONT-END.

Le istruzioni sono fornite su ciascuna pagina del front-end, per spiegare il modo corretto di usarlo e la sua destinazione. Se avete bisogno di ulteriori spiegazioni o qualsiasi assistenza speciale, si prega di contattare il supporto. In alternativa, ci sono alcuni video disponibili su YouTube, che potrebbero aiutare per mezzo di dimostrazione.

#### 4.3 AUTENTICAZIONE A DUE FATTORI

È possibile rendere il front-end più sicuro attivando l'autenticazione a due fattori ("2FA"). Quando si accede a un account attivato per 2FA, viene inviata una posta elettronica all'indirizzo di posta elettronica associato a tale account. Questo indirizzo di posta elettronica contiene un "codice 2FA", che l'utente deve quindi inserire, inoltre al nome utente e alla password, per poter accedere utilizzando tale account. Ciò significa che l'ottenimento di una password dell'account non sarebbe sufficiente per consentire a qualsiasi hacker o potenziale utente malintenzionato di accedere a tale account, in quanto avrebbe anche bisogno di avere accesso all'indirizzo di posta elettronica associato a tale account per poter ricevere e utilizzare il codice 2FA associato alla sessione, rendendo così il front-end più sicuro.

Innanzitutto, per attivare l'autenticazione a due fattori, utilizzando la pagina degli aggiornamenti front-end, installare il componente PHPMailer. CIDRAM utilizza PHPMailer per l'invio di posta elettronica. Va notato che sebbene CIDRAM, di per sé, sia compatibile con PHP >= 5.4.0, PHPMailer richiede PHP >= 5.5.0, e pertanto, l'attivazione dell'autenticazione a due fattori per il front-end CIDRAM non sarà possibile per gli utenti di PHP 5.4.

Dopo aver installato PHPMailer, dovrai compilare le direttive di configurazione per PHPMailer tramite la pagina di configurazione CIDRAM o il file di configurazione. Ulteriori informazioni su queste direttive di configurazione sono incluse nella sezione di configurazione di questo documento. Dopo aver compilato le direttive di configurazione di PHPMailer, imposta da `Enable2FA` x `true`. L'autenticazione a due fattori dovrebbe ora essere attivata.

Successivamente, dovrai associare un indirizzo di posta elettronica a un account, in modo che CIDRAM sappia dove inviare i codici 2FA quando accede con quell'account. Per fare ciò, usa l'indirizzo di posta elettronica come nome utente per l'account (come `foo@bar.tld`), o includere l'indirizzo di posta elettronica come parte del nome utente nello stesso modo in cui si farebbe quando si invia una posta elettronica normalmente (come `Foo Bar <foo@bar.tld>`).

Nota: Proteggere il tuo vault dall'accesso non autorizzato (per esempio, per mezzo di rafforzando le autorizzazioni di sicurezza e di accesso pubblico del tuo server), è particolarmente importante qui, a causa di tale accesso non autorizzato al file di configurazione (che è memorizzato nel vault), potrebbe rischiare di esporre le impostazioni SMTP in uscita (incluso il nome utente e la password per il tuo SMTP). È meglio assicurarsi che il vault sia correttamente protetto prima di attivare l'autenticazione a due fattori. Se non sei in grado di farlo, almeno, dovresti creare un nuovo account di posta elettronica, dedicato a questo scopo, in quanto tale per ridurre i rischi associati alle impostazioni SMTP esposte.

---


### 5. <a name="SECTION5"></a>FILE INCLUSI IN QUESTO PACCHETTO

Il seguente è un elenco di tutti i file che dovrebbero essere incluso nella archiviato copia di questo script quando si scaricalo, qualsiasi di file che potrebbero potenzialmente essere creato come risultato della vostra utilizzando questo script, insieme con una breve descrizione di ciò che tutti questi file sono per.

File | Descrizione
----|----
/_docs/ | Documentazione cartella (contiene vari file).
/_docs/readme.ar.md | Documentazione Arabo.
/_docs/readme.de.md | Documentazione Tedesco.
/_docs/readme.en.md | Documentazione Inglese.
/_docs/readme.es.md | Documentazione Spagnolo.
/_docs/readme.fr.md | Documentazione Francese.
/_docs/readme.id.md | Documentazione Indonesiano.
/_docs/readme.it.md | Documentazione Italiano.
/_docs/readme.ja.md | Documentazione Giapponese.
/_docs/readme.ko.md | Documentazione Coreana.
/_docs/readme.nl.md | Documentazione Olandese.
/_docs/readme.pt.md | Documentazione Portoghese.
/_docs/readme.ru.md | Documentazione Russo.
/_docs/readme.ur.md | Documentazione Urdu.
/_docs/readme.vi.md | Documentazione Vietnamita.
/_docs/readme.zh-TW.md | Documentazione Cinese (tradizionale).
/_docs/readme.zh.md | Documentazione Cinese (semplificato).
/vault/ | La vault cartella (contiene vari file).
/vault/fe_assets/ | Dati front-end.
/vault/fe_assets/.htaccess | Un ipertesto accesso file (in questo caso, a proteggere di riservati file appartenente allo script da l'acceso di non autorizzate origini).
/vault/fe_assets/_2fa.html | Un modello HTML utilizzato quando si richiede all'utente un codice 2FA.
/vault/fe_assets/_accounts.html | Un modello HTML per il front-end pagina utenti.
/vault/fe_assets/_accounts_row.html | Un modello HTML per il front-end pagina utenti.
/vault/fe_assets/_cache.html | Un modello HTML per il front-end pagina di dati della cache.
/vault/fe_assets/_cidr_calc.html | Un modello HTML per la calcolatrice CIDR.
/vault/fe_assets/_cidr_calc_row.html | Un modello HTML per la calcolatrice CIDR.
/vault/fe_assets/_config.html | Un modello HTML per il front-end pagina di configurazione.
/vault/fe_assets/_config_row.html | Un modello HTML per il front-end pagina di configurazione.
/vault/fe_assets/_files.html | Un modello HTML per il file manager.
/vault/fe_assets/_files_edit.html | Un modello HTML per il file manager.
/vault/fe_assets/_files_rename.html | Un modello HTML per il file manager.
/vault/fe_assets/_files_row.html | Un modello HTML per il file manager.
/vault/fe_assets/_home.html | Un modello HTML per il front-end pagina principale.
/vault/fe_assets/_ip_aggregator.html | Un modello HTML per l'aggregatore IP.
/vault/fe_assets/_ip_test.html | Un modello HTML per la pagina per il test IP.
/vault/fe_assets/_ip_test_row.html | Un modello HTML per la pagina per il test IP.
/vault/fe_assets/_ip_tracking.html | Un modello HTML pagina per il tracciamento IP.
/vault/fe_assets/_ip_tracking_row.html | Un modello HTML pagina per il tracciamento IP.
/vault/fe_assets/_login.html | Un modello HTML per il front-end pagina di accedi.
/vault/fe_assets/_logs.html | Un modello HTML per il front-end pagina per i file di log.
/vault/fe_assets/_nav_complete_access.html | Un modello HTML per i link di navigazione del front-end, per quelli con accesso completo.
/vault/fe_assets/_nav_logs_access_only.html | Un modello HTML per i link di navigazione del front-end, per quelli con accesso solo per i log.
/vault/fe_assets/_range.html | Un modello HTML per il front-end pagina per le tabelle della gamma.
/vault/fe_assets/_range_row.html | Un modello HTML per il front-end pagina per le tabelle della gamma.
/vault/fe_assets/_sections.html | Un modello HTML per la lista delle sezioni.
/vault/fe_assets/_statistics.html | Un modello HTML per il front-end pagina delle statistiche.
/vault/fe_assets/_updates.html | Un modello HTML per il front-end pagina degli aggiornamenti.
/vault/fe_assets/_updates_row.html | Un modello HTML per il front-end pagina degli aggiornamenti.
/vault/fe_assets/frontend.css | Foglio di stile CSS per il front-end.
/vault/fe_assets/frontend.dat | Database per il front-end (contiene informazioni utenti, informazioni sessioni, e la cache; generato solo se il front-end è attivata e utilizzata).
/vault/fe_assets/frontend.html | Il file modello HTML principale per il front-end.
/vault/fe_assets/icons.php | Gestore dell'icone (utilizzata dal file manager del front-end).
/vault/fe_assets/pips.php | Gestore delle pips (utilizzata dal file manager del front-end).
/vault/fe_assets/scripts.js | Contiene dati JavaScript per il front-end.
/vault/lang/ | Contiene dati linguistici.
/vault/lang/.htaccess | Un ipertesto accesso file (in questo caso, a proteggere di riservati file appartenente allo script da l'acceso di non autorizzate origini).
/vault/lang/lang.ar.cli.php | Dati linguistici Araba per CLI.
/vault/lang/lang.ar.fe.php | Dati linguistici Araba per il front-end.
/vault/lang/lang.ar.php | Dati linguistici Araba.
/vault/lang/lang.bn.cli.php | Dati linguistici Bengalese per CLI.
/vault/lang/lang.bn.fe.php | Dati linguistici Bengalese per il front-end.
/vault/lang/lang.bn.php | Dati linguistici Bengalese.
/vault/lang/lang.de.cli.php | Dati linguistici Tedesca per CLI.
/vault/lang/lang.de.fe.php | Dati linguistici Tedesca per il front-end.
/vault/lang/lang.de.php | Dati linguistici Tedesca.
/vault/lang/lang.en.cli.php | Dati linguistici Inglese per CLI.
/vault/lang/lang.en.fe.php | Dati linguistici Inglese per il front-end.
/vault/lang/lang.en.php | Dati linguistici Inglese.
/vault/lang/lang.es.cli.php | Dati linguistici Spagnola per CLI.
/vault/lang/lang.es.fe.php | Dati linguistici Spagnola per il front-end.
/vault/lang/lang.es.php | Dati linguistici Spagnola.
/vault/lang/lang.fr.cli.php | Dati linguistici Francese per CLI.
/vault/lang/lang.fr.fe.php | Dati linguistici Francese per il front-end.
/vault/lang/lang.fr.php | Dati linguistici Francese.
/vault/lang/lang.hi.cli.php | Dati linguistici Hindi per CLI.
/vault/lang/lang.hi.fe.php | Dati linguistici Hindi per il front-end.
/vault/lang/lang.hi.php | Dati linguistici Hindi.
/vault/lang/lang.id.cli.php | Dati linguistici Indonesiana per CLI.
/vault/lang/lang.id.fe.php | Dati linguistici Indonesiana per il front-end.
/vault/lang/lang.id.php | Dati linguistici Indonesiana.
/vault/lang/lang.it.cli.php | Dati linguistici Italiana per CLI.
/vault/lang/lang.it.fe.php | Dati linguistici Italiana per il front-end.
/vault/lang/lang.it.php | Dati linguistici Italiana.
/vault/lang/lang.ja.cli.php | Dati linguistici Giapponese per CLI.
/vault/lang/lang.ja.fe.php | Dati linguistici Giapponese per il front-end.
/vault/lang/lang.ja.php | Dati linguistici Giapponese.
/vault/lang/lang.ko.cli.php | Dati linguistici Coreana per CLI.
/vault/lang/lang.ko.fe.php | Dati linguistici Coreana per il front-end.
/vault/lang/lang.ko.php | Dati linguistici Coreana.
/vault/lang/lang.nl.cli.php | Dati linguistici Olandese per CLI.
/vault/lang/lang.nl.fe.php | Dati linguistici Olandese per il front-end.
/vault/lang/lang.nl.php | Dati linguistici Olandese.
/vault/lang/lang.no.cli.php | Dati linguistici Norvegese per CLI.
/vault/lang/lang.no.fe.php | Dati linguistici Norvegese per il front-end.
/vault/lang/lang.no.php | Dati linguistici Norvegese.
/vault/lang/lang.pt.cli.php | Dati linguistici Portoghese per CLI.
/vault/lang/lang.pt.fe.php | Dati linguistici Portoghese per il front-end.
/vault/lang/lang.pt.php | Dati linguistici Portoghese.
/vault/lang/lang.ru.cli.php | Dati linguistici Russa per CLI.
/vault/lang/lang.ru.fe.php | Dati linguistici Russa per il front-end.
/vault/lang/lang.ru.php | Dati linguistici Russa.
/vault/lang/lang.sv.cli.php | Dati linguistici Svedese per CLI.
/vault/lang/lang.sv.fe.php | Dati linguistici Svedese per il front-end.
/vault/lang/lang.sv.php | Dati linguistici Svedese.
/vault/lang/lang.th.cli.php | Dati linguistici Tailandese per CLI.
/vault/lang/lang.th.fe.php | Dati linguistici Tailandese per il front-end.
/vault/lang/lang.th.php | Dati linguistici Tailandese.
/vault/lang/lang.tr.cli.php | Dati linguistici Turco per CLI.
/vault/lang/lang.tr.fe.php | Dati linguistici Turco per il front-end.
/vault/lang/lang.tr.php | Dati linguistici Turco.
/vault/lang/lang.ur.cli.php | Dati linguistici Urdu per CLI.
/vault/lang/lang.ur.fe.php | Dati linguistici Urdu per il front-end.
/vault/lang/lang.ur.php | Dati linguistici Urdu.
/vault/lang/lang.vi.cli.php | Dati linguistici Vietnamita per CLI.
/vault/lang/lang.vi.fe.php | Dati linguistici Vietnamita per il front-end.
/vault/lang/lang.vi.php | Dati linguistici Vietnamita.
/vault/lang/lang.zh-tw.cli.php | Dati linguistici Cinese (tradizionale) per CLI.
/vault/lang/lang.zh-tw.fe.php | Dati linguistici Cinese (tradizionale) per il front-end.
/vault/lang/lang.zh-tw.php | Dati linguistici Cinese (tradizionale).
/vault/lang/lang.zh.cli.php | Dati linguistici Cinese (semplificata) per CLI.
/vault/lang/lang.zh.fe.php | Dati linguistici Cinese (semplificata) per il front-end.
/vault/lang/lang.zh.php | Dati linguistici Cinese (semplificata).
/vault/.htaccess | Un ipertesto accesso file (in questo caso, a proteggere di riservati file appartenente allo script da l'acceso di non autorizzate origini).
/vault/.travis.php | Utilizzato da Travis CI per il test (non richiesto per il corretto funzionamento dello script).
/vault/.travis.yml | Utilizzato da Travis CI per il test (non richiesto per il corretto funzionamento dello script).
/vault/aggregator.php | Aggregatore IP.
/vault/cache.dat | Cache data.
/vault/cidramblocklists.dat | File di metadati per gli elenchi di blocchi opzionali di Macmathan; Utilizzato dalla pagina degli aggiornamenti del front-end.
/vault/cli.php | Gestore di CLI.
/vault/components.dat | File di metadati dei componenti; Utilizzato dalla pagina degli aggiornamenti del front-end.
/vault/config.ini.RenameMe | File di configurazione; Contiene tutte l'opzioni di configurazione per CIDRAM, dicendogli cosa fare e come operare correttamente (rinomina per attivare).
/vault/config.php | Gestore di configurazione.
/vault/config.yaml | File di valori predefiniti per la configurazione; Contiene valori predefiniti per la configurazione di CIDRAM.
/vault/frontend.php | Gestore del front-end.
/vault/frontend_functions.php | File di funzioni del front-end.
/vault/functions.php | File di funzioni.
/vault/hashes.dat | Contiene una lista di hash accettati (pertinente alla funzione di reCAPTCHA; solo generato se la funzione di reCAPTCHA è abilitato).
/vault/ignore.dat | File ignorati (utilizzato per specificare quali sezioni firma CIDRAM dovrebbe ignorare).
/vault/ipbypass.dat | Contiene un elenco di bypass IP (pertinente alla funzione di reCAPTCHA; solo generato se la funzione di reCAPTCHA è abilitato).
/vault/ipv4.dat | File di firme per IPv4 (servizi cloud indesiderate e punti finali non umani).
/vault/ipv4_bogons.dat | File di firme per IPv4 (bogon/marziano CIDRs).
/vault/ipv4_custom.dat.RenameMe | File di firme per IPv4 personalizzato (rinomina per attivare).
/vault/ipv4_isps.dat | File di firme per IPv4 (ISP pericolosi e spam incline).
/vault/ipv4_other.dat | File di firme per IPv4 (CIDRs per i proxy, VPN e altri vari servizi indesiderati).
/vault/ipv6.dat | File di firme per IPv6 (servizi cloud indesiderate e punti finali non umani).
/vault/ipv6_bogons.dat | File di firme per IPv6 (bogon/marziano CIDRs).
/vault/ipv6_custom.dat.RenameMe | File di firme per IPv6 personalizzato (rinomina per attivare).
/vault/ipv6_isps.dat | File di firme per IPv6 (ISP pericolosi e spam incline).
/vault/ipv6_other.dat | File di firme per IPv6 (CIDRs per i proxy, VPN e altri vari servizi indesiderati).
/vault/lang.php | Dati linguistici.
/vault/modules.dat | File di metadati dei moduli; Utilizzato dalla pagina degli aggiornamenti del front-end.
/vault/outgen.php | Generatore di output.
/vault/php5.4.x.php | Polyfills per PHP 5.4.X (necessaria per la retrocompatibilità di PHP 5.4.X; è sicuro di cancellare per le versioni più recenti di PHP).
/vault/recaptcha.php | Modulo reCAPTCHA.
/vault/rules_as6939.php | File di regole personalizzate per AS6939.
/vault/rules_softlayer.php | File di regole personalizzate per Soft Layer.
/vault/rules_specific.php | File di regole personalizzate per alcune CIDR specifiche.
/vault/salt.dat | File di salt (usato da alcune funzionalità periferica; solo generato se richiesto).
/vault/template_custom.html | File di modello; Modello per l'output HTML prodotto dal generatore di output per CIDRAM.
/vault/template_default.html | File di modello; Modello per l'output HTML prodotto dal generatore di output per CIDRAM.
/vault/themes.dat | File di metadati di temi; Utilizzato dalla pagina degli aggiornamenti del front-end.
/vault/verification.yaml | Dati di verifica per motori di ricerca e social media.
/.gitattributes | Un file del GitHub progetto (non richiesto per il corretto funzionamento dello script).
/Changelog.txt | Un record delle modifiche apportate allo script tra diverse versioni (non richiesto per il corretto funzionamento dello script).
/composer.json | Composer/Packagist informazioni (non richiesto per il corretto funzionamento dello script).
/CONTRIBUTING.md | Informazioni su come contribuire al progetto.
/LICENSE.txt | Una copia della GNU/GPLv2 licenza (non richiesto per il corretto funzionamento dello script).
/loader.php | Caricatore/Loader. Questo è il file si collegare alla vostra sistema (essenziale)!
/README.md | Informazioni di riepilogo del progetto.
/web.config | Un ASP.NET file di configurazione (in questo caso, a proteggere la `/vault` cartella da l'acceso di non autorizzate origini nel caso che lo script è installato su un server basata su ASP.NET tecnologie).

---


### 6. <a name="SECTION6"></a>OPZIONI DI CONFIGURAZIONE
Il seguente è un elenco di variabili trovate nelle `config.ini` file di configurazione di CIDRAM, insieme con una descrizione del loro scopo e funzione.

#### "general" (Categoria)
Generale configurazione per CIDRAM.

##### "logfile"
- Un file leggibile dagli umani per la registrazione di tutti i tentativi di accesso bloccati. Specificare un nome di file, o lasciare vuoto per disabilitare.

##### "logfileApache"
- Un file nello stile di apache per la registrazione di tutti i tentativi di accesso bloccati. Specificare un nome di file, o lasciare vuoto per disabilitare.

##### "logfileSerialized"
- Un file serializzato per la registrazione di tutti i tentativi di accesso bloccati. Specificare un nome di file, o lasciare vuoto per disabilitare.

*Consiglio utile: Se vuoi, è possibile aggiungere data/ora informazioni per i nomi dei file per la registrazione par includendo queste nel nome: `{yyyy}` per l'anno completo, `{yy}` per l'anno abbreviato, `{mm}` per mese, `{dd}` per giorno, `{hh}` per ora.*

*Esempi:*
- *`logfile='logfile.{yyyy}-{mm}-{dd}-{hh}.txt'`*
- *`logfileApache='access.{yyyy}-{mm}-{dd}-{hh}.txt'`*
- *`logfileSerialized='serial.{yyyy}-{mm}-{dd}-{hh}.txt'`*

##### "truncate"
- Troncare i file di log quando raggiungono una determinata dimensione? Il valore è la dimensione massima in B/KB/MB/GB/TB che un file di log può crescere prima di essere troncato. Il valore predefinito di 0KB disattiva il troncamento (i file di log possono crescere indefinitamente). Nota: Si applica ai singoli file di log! La dimensione dei file di log non viene considerata collettivamente.

##### "log_rotation_limit"
- La rotazione dei log limita il numero di file di log che dovrebbero esistere in qualsiasi momento. Quando vengono creati nuovi file di log, se il numero totale di file di log supera il limite specificato, verrà eseguita l'azione specificata. Qui puoi specificare il limite desiderato. Un valore di 0 disabiliterà la rotazione dei log.

##### "log_rotation_action"
- La rotazione dei log limita il numero di file di log che dovrebbero esistere in qualsiasi momento. Quando vengono creati nuovi file di log, se il numero totale di file di log supera il limite specificato, verrà eseguita l'azione specificata. Qui puoi specificare l'azione desiderato. Delete = Elimina i file di log più vecchi, finché il limite non viene più superato. Archive = In primo luogo archiviare e quindi, eliminare i file di log più vecchi, finché il limite non viene più superato.

*Chiarimento tecnico: In questo contesto, "più vecchi" significa modificato meno di recente.*

##### "timeOffset"
- Se il tempo del server non corrisponde l'ora locale, è possibile specificare un offset qui per regolare le informazioni di data/tempo generato da CIDRAM in base alle proprie esigenze. È generalmente raccomandato invece, regolare à la direttiva fuso orario nel file `php.ini`, ma a volte (come ad esempio quando si lavora con i fornitori di hosting condiviso limitati) questo non è sempre possibile fare, e così, questa opzione è fornito qui. Offset è in minuti.
- Esempio (per aggiungere un'ora): `timeOffset=60`

##### "timeFormat"
- Il formato della data/ora di notazione usata da CIDRAM. Predefinito = `{Day}, {dd} {Mon} {yyyy} {hh}:{ii}:{ss} {tz}`.

##### "ipaddr"
- Dove trovare l'indirizzo IP di collegamento richiesta? (Utile per servizi come Cloudflare e simili). Predefinito = REMOTE_ADDR. AVVISO: Non modificare questa se non sai quello che stai facendo!

Valori consigliati per "ipaddr":

Valore | Utilizzando
---|---
`HTTP_INCAP_CLIENT_IP` | Proxy inverso Incapsula.
`HTTP_CF_CONNECTING_IP` | Proxy inverso Cloudflare.
`CF-Connecting-IP` | Proxy inverso Cloudflare (alternativa; se il precedente non funziona).
`HTTP_X_FORWARDED_FOR` | Proxy inverso Cloudbric.
`X-Forwarded-For` | [Proxy inverso Squid](http://www.squid-cache.org/Doc/config/forwarded_for/).
*Definito dalla configurazione del server.* | [Proxy inverso Nginx](https://www.nginx.com/resources/admin-guide/reverse-proxy/).
`REMOTE_ADDR` | Nessun proxy inverso (valore predefinito).

##### "forbid_on_block"
- Quale messaggio di stato HTTP dovrebbe inviare CIDRAM durante il blocco delle richieste?

Valori attualmente supportati:

Codice di stato | Messaggio di stato | Descrizione
---|---|---
`200` | `200 OK` | Il valore predefinito. Meno robusto, ma più amichevole per gli utenti.
`403` | `403 Forbidden` | Un po' più robusto, ma un po' meno amichevole per gli utenti.
`410` | `410 Gone` | Potrebbe causare problemi quando si tenta di risolvere i falsi positivi, a causa di ciò alcuni browser memorizzeranno nella cache questo messaggio di stato e non invieranno richieste successive, anche dopo aver sbloccato gli utenti. Può essere più utile di altre opzioni per ridurre le richieste da determinati tipi di robot molto specifici.
`418` | `418 I'm a teapot` | In realtà fa riferimento a uno scherzo di April Fools [[RFC 2324](https://tools.ietf.org/html/rfc2324#section-6.5.14)] ed è improbabile che venga compreso dal cliente. Fornito per divertimento e convenienza, ma generalmente non raccomandato.
`451` | `Unavailable For Legal Reasons` | Appropriato per i contesti in cui le richieste vengono bloccate principalmente per motivi legali. Non raccomandato in altri contesti.
`503` | `Service Unavailable` | Più robusto, ma meno amichevole per gli utenti.

##### "silent_mode"
- CIDRAM dovrebbe reindirizzare silenziosamente tutti i tentativi di accesso bloccati invece di visualizzare la pagina "Accesso Negato"? Se si, specificare la localizzazione di reindirizzare i tentativi di accesso bloccati. Se no, lasciare questo variabile vuoto.

##### "lang"
- Specifica la lingua predefinita per CIDRAM.

##### "numbers"
- Specifica come visualizzare i numeri.

Valori attualmente supportati:

Valore | Produce | Descrizione
---|---|---
`NoSep-1` | `1234567.89`
`NoSep-2` | `1234567,89`
`Latin-1` | `1,234,567.89` | Il valore predefinito.
`Latin-2` | `1 234 567.89`
`Latin-3` | `1.234.567,89`
`Latin-4` | `1 234 567,89`
`Latin-5` | `1,234,567·89`
`China-1` | `123,4567.89`
`India-1` | `12,34,567.89`
`India-2` | `१२,३४,५६७.८९`
`Bengali-1` | `১২,৩৪,৫৬৭.৮৯`
`Arabic-1` | `١٢٣٤٥٦٧٫٨٩`
`Arabic-2` | `١٬٢٣٤٬٥٦٧٫٨٩`
`Thai-1` | `๑,๒๓๔,๕๖๗.๘๙`

*Nota: Questi valori non sono standardizzati dovunque, e probabilmente non saranno rilevanti oltre il pacchetto. Inoltre, i valori supportati potrebbero cambiare in futuro.*

##### "emailaddr"
- Se si desidera, è possibile fornire un indirizzo di posta elettronica qui a dare utenti quando sono bloccati, per loro di utilizzare come punto di contatto per supporto e/o assistenza per il caso di che vengano bloccate per errore. AVVERTIMENTO: Qualunque sia l'indirizzo di posta elettronica si fornisce qui sarà certamente acquisito dal spambots e raschietti/scrapers nel corso del suo essere usato qui, e così, è fortemente raccomandato che se si sceglie di fornire un indirizzo di posta elettronica qui, che si assicurare che l'indirizzo di posta elettronica si fornisce qui è un indirizzo monouso e/o un indirizzo che si non ti dispiace essere spammato (in altre parole, probabilmente si non vuole usare il personale primaria o commerciale primaria indirizzi di posta elettronica).

##### "emailaddr_display_style"
- Come preferisci che l'indirizzo di posta elettronica venga presentato agli utenti? "default" = Link cliccabile. "noclick" = Testo non cliccabile.

##### "disable_cli"
- Disabilita CLI? Modalità CLI è abilitato per predefinito, ma a volte può interferire con alcuni strumenti di test (come PHPUnit, per esempio) e altre applicazioni basate su CLI. Se non è necessario disattivare la modalità CLI, si dovrebbe ignorare questa direttiva. False = Abilita CLI [Predefinito]; True = Disabilita CLI.

##### "disable_frontend"
- Disabilita l'accesso front-end? L'accesso front-end può rendere CIDRAM più gestibile, ma può anche essere un potenziale rischio per la sicurezza. Si consiglia di gestire CIDRAM attraverso il back-end, quando possibile, ma l'accesso front-end è previsto per quando non è possibile. Mantenerlo disabilitato tranne se hai bisogno. False = Abilita l'accesso front-end; True = Disabilita l'accesso front-end [Predefinito].

##### "max_login_attempts"
- Numero massimo di tentativi di accesso (front-end). Predefinito = 5.

##### "FrontEndLog"
- File per la registrazione di l'accesso front-end tentativi di accesso. Specificare un nome di file, o lasciare vuoto per disabilitare.

##### "ban_override"
- Sostituire "forbid_on_block" quando "infraction_limit" è superato? Quando si sostituisce: Richieste bloccate restituire una pagina vuota (file di modello non vengono utilizzati). 200 = Non sostituire [Predefinito]. Altri valori sono uguali ai valori disponibili per "forbid_on_block".

##### "log_banned_ips"
- Includi richieste bloccate da IP vietati nei file di log? True = Sì [Predefinito]; False = No.

##### "default_dns"
- Un elenco delimitato con virgole di server DNS da utilizzare per le ricerche dei nomi di host. Predefinito = "8.8.8.8,8.8.4.4" (Google DNS). AVVISO: Non modificare questa se non sai quello che stai facendo!

*Guarda anche: [Cosa posso usare per "default_dns"?](#WHAT_CAN_I_USE_FOR_DEFAULT_DNS)*

##### "search_engine_verification"
- Tentativo di verificare le richieste dai motori di ricerca? La verifica dei motori di ricerca assicura che non saranno vietate a seguito del superamento del limite infrazione (vieta dei motori di ricerca dal vostro sito web di solito hanno un effetto negativo sul vostro posizionamento sui motori di ricerca, SEO, ecc). Quando verificato, i motori di ricerca possono essere bloccati come al solito, ma non saranno vietate. Quando non verificato, è possibile per loro di essere vietate a seguito del superamento del limite infrazione. Inoltre, verifica dei motori di ricerca fornisce una protezione contro le richieste dei motori di ricerca falso e contro le entità potenzialmente dannosi mascherato da motori di ricerca (tali richieste verranno bloccate quando la verifica dei motori di ricerca è attivato). True = Attiva la verifica dei motori di ricerca [Predefinito]; False = Disattiva la verifica dei motori di ricerca.

Attualmente supportato:
- __[Google](https://support.google.com/webmasters/answer/80553?hl=en)__
- __[Bing](https://blogs.bing.com/webmaster/2012/08/31/how-to-verify-that-bingbot-is-bingbot)__
- __Yahoo__
- __[Baidu (百度)](https://help.baidu.com/question?prod_en=master&class=Baiduspider)__
- __Sogou (搜狗)__
- __Youdao (有道)__
- __[Applebot](https://discussions.apple.com/thread/7090135)__
- __[Yandex (Яндекс)](https://yandex.com/support/webmaster/robot-workings/check-yandex-robots.xml)__
- __[DuckDuckGo](https://duckduckgo.com/duckduckbot)__

Non compatibile (causa conflitti):
- __[Mix.com](https://github.com/CIDRAM/CIDRAM/issues/80)__

##### "social_media_verification"
- Tentativo di verificare le richieste dei social media? La verifica dei social media fornisce protezione contro le false richieste dei social media (tali richieste saranno bloccate). True = Attiva la verifica dei social media [Predefinito]; False = Disattiva la verifica dei social media.

Attualmente supportato:
- __[Pinterest](https://help.pinterest.com/en/articles/about-pinterest-crawler-0)__
- __Embedly__
- __[Grapeshot](https://www.grapeshot.com/crawler/)__
- __Twitterbot__

##### "protect_frontend"
- Specifica se le protezioni normalmente fornite da CIDRAM devono essere applicati al front-end. True = Sì [Predefinito]; False = No.

##### "disable_webfonts"
- Disabilita webfonts? True = Sì [Predefinito]; False = No.

##### "maintenance_mode"
- Abilita la modalità di manutenzione? True = Sì; False = No [Predefinito]. Disattiva tutto tranne il front-end. A volte utile per l'aggiornamento del CMS, dei framework, ecc.

##### "default_algo"
- Definisce quale algoritmo da utilizzare per tutte le password e le sessioni in futuro. Opzioni: PASSWORD_DEFAULT (predefinito), PASSWORD_BCRYPT, PASSWORD_ARGON2I (richiede PHP >= 7.2.0).

##### "statistics"
- Monitorare le statistiche di utilizzo di CIDRAM? True = Sì; False = No [Predefinito].

##### "force_hostname_lookup"
- Forzare la ricerca degli nome di host? True = Sì; False = No [Predefinito]. Le ricerche di nome di host vengono normalmente eseguite su base della necessità, ma può essere forzato a tutte le richieste. Ciò può essere utile come mezzo per fornire informazioni più dettagliate nei file di log, ma può anche avere un effetto leggermente negativo sulle prestazioni.

##### "allow_gethostbyaddr_lookup"
- Consenti ricerche gethostbyaddr quando UDP non è disponibile? True = Sì [Predefinito]; False = No.
- *Nota: La ricerca di IPv6 potrebbe non funzionare correttamente su alcuni sistemi a 32-bit.*

##### "hide_version"
- Nascondi informazioni sulla versione dai registri e l'output della pagina? True = Sì; False = No [Predefinito].

##### "empty_fields"
- Come dovrebbe CIDRAM gestire i campi vuoti durante la registrazione e la visualizzazione delle informazioni sugli eventi di blocco? "include" = Includi campi vuoti. "omit" = Ometti campi vuoti [predefiniti].

#### "signatures" (Categoria)
Configurazione per firme.

##### "ipv4"
- Un elenco dei file di firma IPv4 che CIDRAM dovrebbe tentare di utilizzare, delimitati da virgole. È possibile aggiungere voci qui se si desidera includere ulteriori file di firma IPv4 per CIDRAM.

##### "ipv6"
- Un elenco dei file di firma IPv6 che CIDRAM dovrebbe tentare di utilizzare, delimitati da virgole. È possibile aggiungere voci qui se si desidera includere ulteriori file di firma IPv6 per CIDRAM.

##### "block_cloud"
- Blocca i CIDR identificati come appartenente alla servizi webhosting/cloud? Se si utilizza un servizio di API dal suo sito o se si aspetta altri siti a collegare al suo sito, questa direttiva deve essere impostata su false. Se non, questa direttiva deve essere impostata su true.

##### "block_bogons"
- Blocca i CIDR bogone/marziano? Se aspetta i collegamenti al suo sito dall'interno della rete locale, da localhost, o dalla LAN, questa direttiva deve essere impostata su false. Se si non aspetta queste tali connessioni, questa direttiva deve essere impostata su true.

##### "block_generic"
- Blocca i CIDR generalmente consigliati per la lista nera? Questo copre qualsiasi firme che non sono contrassegnate come parte del qualsiasi delle altre più specifiche categorie di firme.

##### "block_legal"
- Blocca i CIDR in risposta agli obblighi legali? Normalmente, questa direttiva non dovrebbe avere alcun effetto, poiché CIDRAM non associa alcun CIDR a "obblighi legali" per impostazione predefinita, ma esiste comunque come misura di controllo aggiuntiva a vantaggio di eventuali file di firme o moduli personalizzati che potrebbero esistere per motivi legali.

##### "block_malware"
- Blocca gli IP associati al malware? Ciò include server C&C, macchine infette, macchine coinvolte nella distribuzione di malware, ecc.

##### "block_proxies"
- Blocca i CIDR identificati come appartenente a servizi proxy o VPN? Se si richiede che gli utenti siano in grado di accedere al suo sito web dai servizi proxy o VPN, questa direttiva deve essere impostata su false. Altrimenti, se non hanno bisogno di servizi proxy o VPN, questa direttiva deve essere impostata su true come un mezzo per migliorare la sicurezza.

##### "block_spam"
- Blocca i CIDR identificati come alto rischio per spam? A meno che si sperimentare problemi quando si fa così, generalmente, questo dovrebbe essere sempre impostata su true.

##### "modules"
- Un elenco di file moduli da caricare dopo l'esecuzione delle firme IPv4/IPv6, delimitati da virgole.

##### "default_tracktime"
- Quanti secondi per tracciare IP vietati dai moduli. Predefinito = 604800 (1 settimana).

##### "infraction_limit"
- Numero massimo di infrazioni un IP è permesso di incorrere prima di essere vietato dalle tracciamento IP. Predefinito = 10.

##### "track_mode"
- Quando devono infrazioni essere contati? False = Quando IP sono bloccati da moduli. True = Quando IP sono bloccati per qualsiasi motivo.

#### "recaptcha" (Categoria)
Se vuoi, è possibile fornire agli utenti un modo per bypassare la pagina di "Accesso Negato" attraverso il completamento di un'istanza di reCAPTCHA. Questo può aiutare a mitigare alcuni dei rischi associati con i falsi positivi in quelle situazioni in cui non siamo del tutto sicuri se una richiesta ha avuto origine da una macchina o di un essere umano.

A causa dei rischi connessi con fornendo un modo per gli utenti di bypassare la pagina di "Accesso Negato", generalmente, vorrei consigliare contro l'attivazione di questa funzione a meno che si sente che sia necessario farlo. Situazioni in cui sarebbe necessario: Se il vostro sito ha clienti/utenti che hanno bisogno di avere accesso al vostro sito web, e se questo è qualcosa che non può essere compromessa sulla, ma se quei clienti/utenti capita di essere di collegamento da una rete ostile che potenzialmente potrebbero essere anche trasportare il traffico indesiderato, e bloccando il traffico indesiderato è anche qualcosa che non può essere compromessa sulla, in quelle particolari situazioni senza possibilità di vittoria, la funzione di reCAPTCHA potrebbe rivelarsi utile come mezzo di permettere ai clienti/utenti desiderabili, mentre tenendo fuori il traffico indesiderato dalla stessa rete. Detto questo, però, dato che la destinazione di un CAPTCHA è quello di distinguere tra esseri umani e non-umani, la funzione di reCAPTCHA aiuterebbe solo in queste situazioni senza possibilità di vittoria se vogliamo supporre che questo traffico indesiderato è non-umano (per esempio, spambots, raschietti, incidere strumenti, traffico automatizzato), invece di essere il traffico umano indesiderato (come ad esempio gli spammer umani, hackers, e altri).

Per ottenere una "site key" e una "secret key" (necessaria per l'utilizzo di reCAPTCHA), vai al: [https://developers.google.com/recaptcha/](https://developers.google.com/recaptcha/)

##### "usemode"
- Definisce come CIDRAM dovrebbe usare reCAPTCHA.
- 0 = reCAPTCHA è completamente disabilitata (predefinito).
- 1 = reCAPTCHA è abilitato per tutte le firme.
- 2 = reCAPTCHA è abilitata solo per le firme appartenenti alle sezioni appositamente contrassegnati come reCAPTCHA abilitati all'interno dei file di firma.
- (Qualsiasi altro valore sarà trattata nello stesso modo come 0).

##### "lockip"
- Specifica se hash dovrebbero essere obbligati a specifici indirizzi IP. False = I cookie e gli hash POSSONO essere utilizzati di più indirizzi IP (predefinito). True = I cookie e gli hash NON possono essere utilizzati di più indirizzi IP (cookie/hash sono obbligati a l'IP).
- Nota: Il valore di "lockip" viene ignorato quando "lockuser" è false, a causa che il meccanismo per ricordare "utenti" differisce a seconda di questo valore.

##### "lockuser"
- Specifica se il completamento di un'istanza di reCAPTCHA deve essere obbligati a utenti specifici. False = Il completamento con successo di un'istanza di reCAPTCHA concederà l'accesso a tutte le richieste provenienti dallo stesso IP come quello utilizzato dall'utente completando l'istanza di reCAPTCHA; I cookie e gli hash non sono utilizzati; Invece, un IP whitelist verrà utilizzata. True = Il completamento con successo di un'istanza di reCAPTCHA sarà solo concedere l'accesso all'utente completando l'istanza di reCAPTCHA; I cookie e gli hash vengono utilizzati per ricordare all'utente; Un IP whitelist non viene utilizzato (predefinito).

##### "sitekey"
- Questo valore deve corrispondere alla "site key" per il vostro reCAPTCHA, che può essere trovato all'interno del cruscotto di reCAPTCHA.

##### "secret"
- Questo valore deve corrispondere alla "secret key" per il vostro reCAPTCHA, che può essere trovato all'interno del cruscotto di reCAPTCHA.

##### "expiry"
- Quando "lockuser" è true (predefinito), al fine di ricordare quando un utente ha superato con successo un'istanza di reCAPTCHA, per richieste di pagina futuri, CIDRAM genera un cookie HTTP standard contenente un hash che corrisponde ad un record interno contenente lo stesso hash; Future richieste per pagine utilizzerà questi hash corrispondenti per autenticare che un utente ha precedentemente già superato un'istanza di reCAPTCHA. Quando "lockuser" è false, un IP whitelist viene utilizzato per stabilire se le richieste dovrebbero essere autorizzate dall'IP di richieste in entrata; IP sono aggiunti a questa whitelist quando l'istanza di reCAPTCHA è superato con successo. Per quante ore dovrebbe questi cookies, hash e gli articoli della whitelist rimane valida? Predefinito = 720 (1 mese).

##### "logfile"
- Registrare tutti i tentativi per reCAPTCHA? Se sì, specificare il nome da usare per il file di registrazione. Se non, lasciare questo variabile vuoto.

*Consiglio utile: Se vuoi, è possibile aggiungere data/ora informazioni per i nomi dei file per la registrazione par includendo queste nel nome: `{yyyy}` per l'anno completo, `{yy}` per l'anno abbreviato, `{mm}` per mese, `{dd}` per giorno, `{hh}` per ora.*

*Esempi:*
- *`logfile='recaptcha.{yyyy}-{mm}-{dd}-{hh}.txt'`*

##### "signature_limit"
- Il numero massimo di firme consentito per essere innescato quando viene offerta un'istanza di reCAPTCHA. Predefinito = 1. Se questo numero viene superato per una particolare richiesta, non verrà offerta un'istanza di reCAPTCHA.

##### "api"
- Quale API usare? V2 o Invisible?

*Nota per gli utenti nell'Unione europea: Quando CIDRAM è configurato per utilizzare i cookie (ad es., quando "lockuser" è true/vero), un avviso sui cookie viene visualizzato nella pagina in base ai requisiti della [legislazione sui cookie dell'UE](http://ec.europa.eu/ipg/basics/legal/cookies/index_en.htm). Tuttavia, quando si utilizza l'API invisible, CIDRAM tenta di completare automaticamente reCAPTCHA per l'utente, e in caso di successo, ciò potrebbe comportare il ricaricamento della pagina e la creazione di un cookie senza che all'utente venga dato il tempo necessario per visualizzare effettivamente l'avviso sui cookie. Se ciò rappresenta un rischio legale per te, potrebbe essere preferibile utilizzare l'API V2 anziché l'API invisible (l'API V2 non è automatizzata e richiede che l'utente completi la stessa sfida reCAPTCHA, fornendo così l'opportunità di vedere l'avvertimento sui cookie).*

#### "legal" (Categoria)
Configurazione relativa ai requisiti legali.

*Per ulteriori informazioni sui requisiti legali e su come ciò potrebbe influire sui requisiti di configurazione, si prega di fare riferimento alla sezione "[INFORMAZIONE LEGALE](#SECTION11)" della documentazione.*

##### "pseudonymise_ip_addresses"
- Pseudonimizzare gli indirizzi IP durante la scrivono i file di registro? True = Sì; False = No [Predefinito].

##### "omit_ip"
- Ometti gli indirizzi IP dai log? True = Sì; False = No [Predefinito]. Nota: "pseudonymise_ip_addresses" diventa ridondante quando "omit_ip" è "true".

##### "omit_hostname"
- Ometti nomi host dai log? True = Sì; False = No [Predefinito].

##### "omit_ua"
- Ometti l'agente utente dai log? True = Sì; False = No [Predefinito].

##### "privacy_policy"
- L'indirizzo di una politica sulla privacy pertinente da visualizzare nel piè di pagina delle pagine generate. Specificare un URL, o lasciare vuoto per disabilitare.

#### "template_data" (Categoria)
Direttive/Variabili per modelli e temi.

Si riferisce al HTML utilizzato per generare la pagina "Accesso Negato". Se stai usando temi personalizzati per CIDRAM, prodotti HTML è provenienti da file `template_custom.html`, e altrimenti, prodotti HTML è provenienti da file `template.html`. Variabili scritte a questa sezione del file di configurazione sono parsato per il prodotti HTML per mezzo di sostituendo tutti i nomi di variabili circondati da parentesi graffe trovato all'interno il prodotti HTML con la corrispondente dati di quelli variabili. Per esempio, dove `foo="bar"`, qualsiasi istanza di `<p>{foo}</p>` trovato all'interno il prodotti HTML diventerà `<p>bar</p>`.

##### "theme"
- Tema predefinito da utilizzare per CIDRAM.

##### "Magnification"
- Ingrandimento del carattere. Predefinito = 1.

##### "css_url"
- Il modello file per i temi personalizzati utilizzi esterni CSS proprietà, mentre il modello file per i temi personalizzati utilizzi interni CSS proprietà. Per istruire CIDRAM di utilizzare il modello file per i temi personalizzati, specificare l'indirizzo pubblico HTTP dei CSS file dei suoi tema personalizzato utilizzando la variabile `css_url`. Se si lascia questo variabile come vuoto, CIDRAM utilizzerà il modello file per il predefinito tema.

#### "PHPMailer" (Categoria)
Configurazione PHPMailer.

##### "EventLog"
- Un file per registrare tutti gli eventi in relazione a PHPMailer. Specificare un nome di file, o lasciare vuoto per disabilitare.

##### "SkipAuthProcess"
- Impostando questa direttiva su `true`, PHPMailer salta il normale processo di autenticazione che normalmente si verifica quando si invia una posta elettronica via SMTP. Questo dovrebbe essere evitato, perché saltare questo processo potrebbe esporre la posta elettronica in uscita agli attacchi MITM, ma potrebbe essere necessario nei casi in cui questo processo impedisce a PHPMailer di connettersi a un server SMTP.

##### "Enable2FA"
- Questa direttiva determina se utilizzare 2FA per gli account front-end.

##### "Host"
- L'host SMTP per utilizzare per la posta elettronica in uscita.

##### "Port"
- Il numero di porta per utilizzare per la posta elettronica in uscita. Predefinito = 587.

##### "SMTPSecure"
- Il protocollo per utilizzare per l'invio di posta elettronica tramite SMTP (TLS o SSL).

##### "SMTPAuth"
- Questa direttiva determina se autenticare le sessioni SMTP (di solito dovrebbe essere lasciato solo).

##### "Username"
- Il nome utente per utilizzare per l'invio di posta elettronica tramite SMTP.

##### "Password"
- La password per utilizzare per l'invio di posta elettronica tramite SMTP.

##### "setFromAddress"
- L'indirizzo del mittente per citare quando si invia una posta elettronica tramite SMTP.

##### "setFromName"
- Il nome del mittente per citare quando si invia una posta elettronica tramite SMTP.

##### "addReplyToAddress"
- L'indirizzo di risposta per citare quando si invia una posta elettronica tramite SMTP.

##### "addReplyToName"
- Il nome per la risposta per citare quando si invia una posta elettronica tramite SMTP.

---


### 7. <a name="SECTION7"></a>FIRMA FORMATO

*Guarda anche:*
- *[Che cosa è una "firma"?](#WHAT_IS_A_SIGNATURE)*

#### 7.0 NOZIONI DI BASE (PER FILE DI FIRMA)

Tutte le firme IPv4 seguono il formato: `xxx.xxx.xxx.xxx/yy [Function] [Param]`.
- `xxx.xxx.xxx.xxx` rappresenta l'inizio del blocco CIDR (gli ottetti dell'indirizzo IP iniziale nel blocco).
- `yy` rappresenta la dimensione del blocco CIDR [1-32].
- `[Function]` indica al script di cosa fare con la firma (come la firma dovrebbe essere considerata).
- `[Param]` rappresenta qualsiasi ulteriore informazione può essere richiesta di `[Function]`.

Tutte le firme IPv6 seguono il formato: `xxxx:xxxx:xxxx:xxxx::xxxx/yy [Function] [Param]`.
- `xxxx:xxxx:xxxx:xxxx::xxxx` rappresenta l'inizio del blocco CIDR (gli ottetti dell'indirizzo IP iniziale nel blocco). Notazione completa e la notazione abbreviata sono entrambi accettabili (e ognuno DEVE seguire gli standard adeguati e pertinenti di notazione IPv6, ma con una sola eccezione: un indirizzo IPv6 non può mai iniziare con l'abbreviazione quando usato in una firma per questo script, dovuto al modo in cui CIDRs vengono ricostruite dallo script; Per esempio, `::1/128` dovrebbe essere espresso, quando utilizzato in una firma, come `0::1/128`, e `::0/128` espresso come `0::/128`).
- `yy` rappresenta la dimensione del blocco CIDR [1-128].
- `[Function]` indica al script di cosa fare con la firma (come la firma dovrebbe essere considerata).
- `[Param]` rappresenta qualsiasi ulteriore informazione può essere richiesta di `[Function]`.

I file di firma per CIDRAM DOVREBBE utilizzare interruzioni di riga in stile Unix (`%0A`, o `\n`)! Altri tipi / stili di interruzioni di riga (per esempio, Windows `%0D%0A` o `\r\n` interruzioni di riga, Mac `%0D` o `\r` interruzioni di riga, ecc) PUÒ essere usato, ma NON sono da preferire. Interruzioni di riga che non sono in stile Unix sarà normalizzato a interruzioni di riga in stile Unix dallo script.

Precisa e corretta notazione CIDR è richiesta, altrimenti lo script non riconoscerà le firme. Inoltre, tutte le firme CIDR di questo script DEVE iniziare con un indirizzo IP il cui numero IP può dividere in modo uniforme nella divisione blocco rappresentato dal suo dimensione del blocco CIDR (per esempio, se si desidera bloccare tutti gli IP da `10.128.0.0` a `11.127.255.255`, `10.128.0.0/8` NON sarebbe riconosciuta dallo script, ma `10.128.0.0/9` e `11.0.0.0/9` usato insieme, SAREBBE riconosciuta dallo script).

Qualsiasi cosa ciò che nei file di firme non riconosciuto come firma né come sintassi connessi alle firme dallo script saranno IGNORATI, significa quindi che si può tranquillamente inserire qualsiasi dati che si desidera nei file di firme senza romperle e senza rompere lo script. I commenti sono accettabili nei file di firme, senza qualsiasi formattazione speciale richiesto per loro. Hashing in stile di Shell per i commenti è preferito, ma non forzata; Funzionalmente, non fa alcuna differenza per lo script anche se non si sceglie di utilizzare hashing in stile di Shell per i commenti, ma usando hashing in stile di Shell aiuta IDE ed editor di testo normale ad evidenziare correttamente le varie parti dei file di firme (e così, hashing in stile di Shell può aiutare come un aiuto visivo durante la modifica).

I valori possibili di `[Function]` sono le seguenti:
- Run
- Whitelist
- Greylist
- Deny

Se viene utilizzato "Run", quando la firma viene attivato, lo script tenterà di eseguire (utilizzando un statement `require_once`) uno script PHP esterna, specificato dal valore di `[Param]` (la directory di lavoro dovrebbe essere la directory dello script, "/vault/").

Esempio: `127.0.0.0/8 Run example.php`

Questo può essere utile se si desidera eseguire del codice PHP specifiche per alcuni IP e/o CIDR specifici.

Se viene utilizzato "Whitelist", quando la firma viene attivato, lo script si resetta tutti i rilevamenti (se c'è stato rilevamenti) e rompere la funzione di test. `[Param]` viene ignorato. Questa funzione garantisce che un particolare IP o CIDR non sarà rilevato.

Esempio: `127.0.0.1/32 Whitelist`

Se viene utilizzato "Greylist", quando la firma viene attivato, lo script si resetta tutti i rilevamenti (se c'è stato rilevamenti) e passare al file di firme successivo per continuare l'elaborazione. `[Param]` viene ignorato.

Esempio: `127.0.0.1/32 Greylist`

Se viene utilizzato "Deny", quando la firma viene attivato, supponendo che non firma whitelist è stato attivato per il dato indirizzo IP e/o dato CIDR, l'accesso alla pagina protetta sarà negato. "Deny" xxx è ciò che si desidera utilizzare per bloccare effettivamente un indirizzo IP e/o gamma CIDR. Quando qualsiasi firme vengono attivati che fanno uso di "Deny", il "Accesso Negato" pagina dello script sarà generato e la richiesta alla pagina protetta sarà ucciso.

Il valore di `[Param]` accettato da "Deny" sarà parsato per l'output della "Accesso Negato" pagina, fornito al cliente/utente come la ragione citata per il loro accesso alla pagina richiesta essere negata. Può essere una frase breve e semplice, spiegando il motivo per cui hai scelto di bloccarli (qualsiasi cosa dovrebbe essere sufficiente, anche un semplice "io non ti voglio sul mio sito"), o uno di una piccola manciata di parole brevi fornita dallo script, che se usato, sarà sostituito dallo script con una spiegazione pre-preparati del perché il cliente/utente è stato bloccato.

Le spiegazioni pre-preparati hanno il supporto L10N e può essere tradotto dallo script in base alla lingua specificata alla direttiva di configurazione dello script, `lang`. Inoltre, è possibile indicare lo script di ignorare le firme "Deny" in base al loro valore di `[Param]` (se si sta utilizzando queste brevi parole) tramite le direttive specificata dalla configurazione dello script (ogni parola breve ha un corrispondente direttiva al elaborare le firme corrispondenti o di ignorarle). I valori di `[Param]` che non utilizzare questi brevi parole, però, non hanno il supporto L10N e quindi NON sarà tradotto dallo script, e inoltre, non sono controllabili direttamente dalla configurazione dello script.

Le parole brevi disponibili sono:
- Bogon
- Cloud
- Generic
- Proxy
- Spam
- Legal
- Malware

#### 7.1 ETICHETTE

##### 7.1.0 ETICHETTE DI SEZIONE

Se si desidera dividere le vostre firme personalizzate in singole sezioni, è possibile identificare queste singole sezioni per lo script per aggiungendo un "etichetta di sezione" subito dopo le firme di ogni sezione, insieme con il nome della sezione di firme (vedere l'esempio cui seguito).

```
# Sezione 1.
1.2.3.4/32 Deny Bogon
2.3.4.5/32 Deny Cloud
4.5.6.7/32 Deny Generic
5.6.7.8/32 Deny Spam
6.7.8.9/32 Deny Proxy
Tag: Sezione 1
```

Per rompere l'etichetta della sezione e per assicurare che l'etichetta non sono identificati erroneamente alle sezioni di firme da prima nelle file di firme, semplicemente assicurare che ci sono almeno due interruzioni di riga consecutivi tra l'etichetta e le sezioni di firme precedenti. Qualsiasi firme senza un'etichetta saranno etichettato come "IPv4" o "IPv6" per predefinito (dipendente sui quali tipi di firme vengono attivati).

```
1.2.3.4/32 Deny Bogon
2.3.4.5/32 Deny Cloud

4.5.6.7/32 Deny Generic
5.6.7.8/32 Deny Spam
Tag: Sezione 1
```

Nell'esempio sopra `1.2.3.4/32` e `2.3.4.5/32` saranno etichettato come "IPv4", mentre `4.5.6.7/32` e `5.6.7.8/32` saranno etichettato come "Sezione 1".

La stessa logica può essere applicata anche per separare altri tipi di etichette.

In particolare, l'etichette di sezione possono essere molto utili per il debug quando si verificano falsi positivi, fornendo un facile mezzo di trovare l'esatta fonte del problema, e può essere molto utili per filtrare le voci del file di registro durante la visualizzazione dei file di registro tramite il front-end pagina per i file di log (i nomi delle sezioni sono selezionabili tramite la pagina dei front-end pagina per i file di log e possono essere utilizzati come criteri di filtro). Se l'etichetta della sezione vengono omessi per alcune firme particolari, quando tali firme vengono innescate, CIDRAM utilizza il nome del file di firma insieme al tipo di indirizzo IP bloccato (IPv4 o IPv6) come ripiego, e quindi, l'etichette di sezione sono completamente opzionali. In alcuni casi, tuttavia, possono essere raccomandati, ad esempio quando i file delle firme sono nominati vagamente o quando potrebbe essere difficile identificare chiaramente la fonte delle firme che causano il blocco di una richiesta.

##### 7.1.1 ETICHETTE DI SCADENZA

Se si desidera firme per scadono dopo un certo tempo, in modo analogo all'etichette sezione, è possibile utilizzare un "etichetta di scadenza" per specificare quando le firme dovrebbero cessano di essere validi. Etichette scadenza usano il formato "AAAA.MM.GG" (vedere l'esempio cui seguito).

```
# Sezione 1.
1.2.3.4/32 Deny Generic
2.3.4.5/32 Deny Generic
Expires: 2016.12.31
```

Le firme scadute non verranno mai innescate su nessuna richiesta, non importa quale.

##### 7.1.2 ETICHETTE DI ORIGINE

Se si desidera specificare il paese di origine per alcune firme particolari, è possibile farlo utilizzando un "etichette di origine". Un etichette di origine accetta un codice "[ISO 3166-1 Alpha-2](https://it.wikipedia.org/wiki/ISO_3166-1_alpha-2)" corrispondente al paese di origine per le firme a cui si applica. Questi codici devono essere scritti in maiuscolo (minuscole non verranno visualizzate correttamente). Quando viene utilizzato un etichette di origine, viene aggiunto alla voce del campo di registro "Perché Bloccato" per qualsiasi richieste bloccate a seguito delle firme a cui il tag viene applicato.

Se è installato il componente opzionale "flags CSS", quando si visualizzano i file di registro tramite il front-end pagina per i file di log, le informazioni aggiunte dai etichette di origine vengono sostituite con la bandiera del paese corrispondente a tali informazioni. Questa informazione, sia nella sua forma grezza o come bandiera di un paese, è cliccabile e, quando viene cliccato, filtra le voci di registro tramite altre voci di registro che identificano in modo simile (abilitante effettivamente agli utenti che accedono alla pagina dei registri di filtrare in base al paese di origine).

Nota: Tecnicamente, questa non è una forma di geolocalizzazione, in quanto non implica la ricerca di alcuna informazione specifica relativa agli IP in entrata, ma piuttosto, semplicemente ci consente di dichiarare esplicitamente un paese di origine per qualsiasi richieste bloccate da firme specifiche. Sono consentiti più tag di origine all'interno della stessa sezione di firma.

Esempio ipotetico:

```
1.2.3.4/32 Deny Generic
Origin: CN
2.3.4.5/32 Deny Generic
Origin: FR
4.5.6.7/32 Deny Generic
Origin: DE
6.7.8.9/32 Deny Generic
Origin: US
Tag: Foobar
```

Qualsiasi tag possono essere utilizzati insieme e tutti i tag sono opzionali (vedere l'esempio cui seguito).

```
# Sezione Esempio.
1.2.3.4/32 Deny Generic
Origin: US
Tag: Sezione Esempio
Expires: 2016.12.31
```

##### 7.1.3 ETICHETTE DI DEFERENZA

Quando vengono installati e utilizzati attivamente un numero elevato di file di firma, le installazioni possono diventare piuttosto complesse, e potrebbero esserci alcune firme che si sovrappongono. In questi casi, al fine di evitare l'innescando di più firme sovrapposte durante gli eventi di blocco, i etichette di deferenza possono essere utilizzati per differire sezioni di firma specifiche nei casi in cui è installato e utilizzato attivamente un altro file di firma specifico. Questo può essere utile nei casi in cui alcune firme vengono aggiornate più frequentemente di altre, al fine di differire le firme meno frequentemente aggiornate a favore delle firme più frequentemente aggiornate.

I etichette di deferenza sono utilizzati in modo simile ad altri tipi di etichette. Il valore dell'etichetta deve corrispondere a un file di firma installato e utilizzato attivamente a cui essere differite.

Esempio:

```
1.2.3.4/32 Deny Generic
Origin: AA
2.3.4.5/32 Deny Generic
Origin: BB
Defers to: preferred_signatures.dat
```

#### 7.2 YAML

##### 7.2.0 YAML BASI

Una forma semplificata di YAML markup può essere utilizzato in file di firma al fine di definire comportamenti e le impostazioni specifiche per singole sezioni di firma. Questo può essere utile se si desidera che il valore delle vostre direttive di configurazione di differire sulla base delle singole firme e sezioni di firma (per esempio; se si desidera fornire un indirizzo di posta elettronica per i biglietti di supporto per tutti gli utenti bloccati da una firma particolare, ma non desidera fornire un indirizzo di posta elettronica per i biglietti di supporto per utenti bloccati con qualsiasi altro firme; se si desidera che alcune firme specifiche per innescare una reindirizzamento di pagina; se si desidera contrassegnare una sezione di firma per l'utilizzo con reCAPTCHA; se si desidera registrare i tentativi di accesso bloccati in file separati sulla base delle singole firme e/o sezioni di firma).

L'utilizzo di YAML markup nei file di firma è del tutto facoltativo (cioè, si può usare se si desidera farlo, ma non è richiesto a farlo), ed è in grado di sfruttare la maggior parte (ma non tutto) delle direttive di configurazione.

Nota: Implementazione di YAML markup in CIDRAM è molto semplice e molto limitato; Esso è destinato a soddisfare i requisiti specifici per CIDRAM in modo che ha la familiarità di YAML markup, ma non segue né è conforme alle specifiche tecniche (e quindi non si comporterà nello stesso modo come implementazioni più approfonditi altrove, e potrebbe non essere appropriato per altri progetti altrove).

In CIDRAM, segmenti di YAML markup vengono identificati allo script da tre trattini ("---"), e terminare al fianco dei loro contenenti sezioni di firma mediante due interruzioni di riga. Un tipico segmento di YAML markup all'interno di una sezione di firme consiste di tre trattini su una riga subito dopo l'elenco dei CIDRs e qualsiasi etichette, seguito da una lista bidimensionale delle chiave-valore coppie (prima dimensione, categorie di direttive di configurazione; seconda dimensione, direttive di configurazione) per i quali direttive di configurazione devono essere modificati (e in cui valori) ogniqualvolta una firma all'interno di tale sezione di firme viene innescato (vedere gli esempi qui sotto).

```
# Foobar 1.
1.2.3.4/32 Deny Generic
2.3.4.5/32 Deny Generic
4.5.6.7/32 Deny Generic
Tag: Foobar 1
---
general:
 logfile: logfile.{yyyy}-{mm}-{dd}.txt
 logfileApache: access.{yyyy}-{mm}-{dd}.txt
 logfileSerialized: serial.{yyyy}-{mm}-{dd}.txt
 forbid_on_block: false
 emailaddr: username@domain.tld
recaptcha:
 lockip: false
 lockuser: true
 expiry: 720
 logfile: recaptcha.{yyyy}-{mm}-{dd}.txt
 enabled: true
template_data:
 css_url: http://domain.tld/cidram.css

# Foobar 2.
1.2.3.4/32 Deny Generic
2.3.4.5/32 Deny Generic
4.5.6.7/32 Deny Generic
Tag: Foobar 2
---
general:
 logfile: "logfile.Foobar2.{yyyy}-{mm}-{dd}.txt"
 logfileApache: "access.Foobar2.{yyyy}-{mm}-{dd}.txt"
 logfileSerialized: "serial.Foobar2.{yyyy}-{mm}-{dd}.txt"
 forbid_on_block: 503

# Foobar 3.
1.2.3.4/32 Deny Generic
2.3.4.5/32 Deny Generic
4.5.6.7/32 Deny Generic
Tag: Foobar 3
---
general:
 forbid_on_block: 403
 silent_mode: "http://127.0.0.1/"
```

##### 7.2.1 COME "APPOSITAMENTE CONTRASSEGNARE" SEZIONI DI FIRMA PER L'UTILIZZO CON reCAPTCHA

Quando "usemode" è 0 o 1, sezioni di firma non hanno bisogno di essere "appositamente contrassegnato" per l'utilizzo con reCAPTCHA (perché già userà o non userà reCAPTCHA, (dipende da questa impostazione).

Quando "usemode" è 2, a "appositamente contrassegnare" sezioni di firma per l'utilizzo con, una voce è incluso nel segmento di YAML per tale sezione di firme (vedere l'esempio cui seguito).

```
# Questa sezione userà reCAPTCHA.
1.2.3.4/32 Deny Generic
2.3.4.5/32 Deny Generic
Tag: reCAPTCHA-Enabled
---
recaptcha:
 enabled: true
```

*Nota: Di default, un'istanza di reCAPTCHA sarà solo essere offerto all'utente se reCAPTCHA è attivato (sia con "usemode" come 1, o "usemode" come 2 con "enabled" come true), e se esattamente UN firma è stato attivato (né più, né meno; se più firme sono attivati, un'istanza di reCAPTCHA NON sarà offerto). Però, questo comportamento può essere modificato tramite la direttiva "signature_limit".*

#### 7.3 AUSILIARIO

In aggiunta, se si desidera CIDRAM di ignorare completamente alcune sezioni specifiche in qualsiasi una delle file di firma, è possibile utilizzare il file `ignore.dat` per specificare quali sezioni a ignorare. In una nuova riga, scivere `Ignore`, seguito da uno spazio, seguito dal nome della sezione che si desidera CIDRAM a ignorare (vedere l'esempio cui seguito).

```
Ignore Sezione 1
```

#### 7.4 <a name="MODULE_BASICS"></a>NOZIONI DI BASE (PER MODULI)

I moduli possono essere utilizzati per estendere la funzionalità di CIDRAM, eseguire attività aggiuntive o elaborare una logica aggiuntiva. Tipicamente, vengono utilizzati quando è necessario bloccare una richiesta su una base diversa dal suo indirizzo IP di origine (e quindi, quando una firma CIDR non è sufficiente per bloccare la richiesta). I moduli sono scritti come file PHP e quindi, in genere, le firme dei moduli sono scritte come codice PHP.

Alcuni buoni esempi di moduli CIDRAM possono essere trovati qui:
- https://github.com/CIDRAM/CIDRAM-Extras/tree/master/modules

Un modello per scrivere nuovi moduli può essere trovato qui:
- https://github.com/CIDRAM/CIDRAM-Extras/blob/master/modules/module_template.php

Dato che i moduli sono scritti come file PHP, se hai familiarità con il codice CIDRAM, puoi strutturare i tuoi moduli come vuoi, e scrivi le tue firme del modulo come preferisci (entro i limiti di ciò che è possibile in PHP). Tuttavia, per tua comodità, e per una migliore intelligibilità reciproca tra i moduli esistenti e il tuo, è consigliabile analizzare il modello collegato sopra, per poter utilizzare la struttura e il formato che fornisce.

*Nota: Se non ti piace lavorare con il codice PHP, non è consigliabile scrivere i tuoi moduli.*

Alcune funzionalità sono fornite da CIDRAM per essere utilizzato dai moduli, il che dovrebbe rendere più semplice e facile scrivere i propri moduli. Le informazioni su questa funzionalità sono descritte di seguito.

#### 7.5 FUNZIONALITÀ DEL MODULO

##### 7.5.0 "$Trigger"

Le firme dei moduli sono scritte tipicamente con "$Trigger". Nella maggior parte dei casi, questa closure sarà più importante di ogni altra cosa allo scopo di scrivere moduli.

"$Trigger" accetta 4 parametri: "$Condition", "$ReasonShort", "$ReasonLong" (opzionale), e "$DefineOptions" (opzionale).

La veridicità di "$Condition" è valutata e, se è true/vera, la firma è "innescato". Se false, la firma *non* è "innescato". "$Condition" contiene tipicamente codice PHP per valutare una condizione che dovrebbe causa una richiesta essere bloccare.

"$ReasonShort" è citato nel campo "Perché Bloccato" quando la firma è "innescata".

"$ReasonLong" è un messaggio opzionale da mostrare all'utente/cliente per quando sono bloccati, per spiegare perché sono stati bloccati. Utilizza il messaggio standard "Accesso Negato" quando omesso.

"$DefineOptions" è un array opzionale contenente coppie chiave/valore, utilizzato per definire le opzioni di configurazione specifiche dell'istanza della richiesta. Le opzioni di configurazione verranno applicate quando la firma è "innescata".

"$Trigger" restituisce true quando la firma è "innescata", e false quando non lo è.

Per utilizzare questa closure nel modulo, ricorda innanzitutto di ereditarlo dall'ambito principale:
```PHP
$Trigger = $CIDRAM['Trigger'];
```

##### 7.5.1 "$Bypass"

I bypass di firma sono scritte tipicamente con "$Bypass".

"$Bypass" accetta 3 parametri: "$Condition", "$ReasonShort", e "$DefineOptions" (opzionale).

La veridicità di "$Condition" è valutata e, se è true/vera, il bypass è "innescato". Se false, il bypass *non* è "innescato". "$Condition" contiene tipicamente codice PHP per valutare una condizione che *non* dovrebbe causa una richiesta essere bloccare.

"$ReasonShort" è citato nel campo "Perché Bloccato" quando il bypass è "innescata".

"$DefineOptions" è un array opzionale contenente coppie chiave/valore, utilizzato per definire le opzioni di configurazione specifiche dell'istanza della richiesta. Le opzioni di configurazione verranno applicate quando il bypass è "innescata".

"$Bypass" restituisce true quando il bypass è "innescata", e false quando non lo è.

Per utilizzare questa closure nel modulo, ricorda innanzitutto di ereditarlo dall'ambito principale:
```PHP
$Bypass = $CIDRAM['Bypass'];
```

##### 7.5.2 "$CIDRAM['DNS-Reverse']"

Questo può essere usato per recuperare il nome host di un indirizzo IP. Se vuoi creare un modulo per bloccare i nomi degli host, questa closure potrebbe essere utile.

Esempio:
```PHP
<?php
/** Inherit trigger closure (see functions.php). */
$Trigger = $CIDRAM['Trigger'];

/** Fetch hostname. */
if (empty($CIDRAM['Hostname'])) {
    $CIDRAM['Hostname'] = $CIDRAM['DNS-Reverse']($CIDRAM['BlockInfo']['IPAddr']);
}

/** Example signature. */
if ($CIDRAM['Hostname'] && $CIDRAM['Hostname'] !== $CIDRAM['BlockInfo']['IPAddr']) {
    $Trigger($CIDRAM['Hostname'] === 'www.foobar.tld', 'Foobar.tld', 'Hostname Foobar.tld is not allowed.');
}
```

#### 7.6 VARIABILI DEL MODULO

I moduli eseguiti all'interno del proprio ambito, e qualsiasi variabile definita da un modulo, non saranno accessibili ad altri moduli, o allo script principale, tranne se sono memorizzati nella array "$CIDRAM" (tutto il resto viene svuotato al termine dell'esecuzione del modulo).

Di seguito sono elencate alcune variabili comuni che potrebbero essere utili per il tuo modulo:

Variabile | Descrizione
----|----
`$CIDRAM['BlockInfo']['DateTime']` | La data e l'ora correnti.
`$CIDRAM['BlockInfo']['IPAddr']` | L'indirizzo IP per la richiesta corrente.
`$CIDRAM['BlockInfo']['ScriptIdent']` | Versione di script CIDRAM.
`$CIDRAM['BlockInfo']['Query']` | La query per la richiesta corrente.
`$CIDRAM['BlockInfo']['Referrer']` | Il referrer per la richiesta corrente (se esiste).
`$CIDRAM['BlockInfo']['UA']` | L'agente utente (user agent) per la richiesta corrente.
`$CIDRAM['BlockInfo']['UALC']` | L'agente utente (user agent) per la richiesta corrente (in minuscolo).
`$CIDRAM['BlockInfo']['ReasonMessage']` | Il messaggio visualizzato all'utente/cliente per la richiesta corrente se sono bloccati.
`$CIDRAM['BlockInfo']['SignatureCount']` | Il numero di firme innescati per la richiesta corrente.
`$CIDRAM['BlockInfo']['Signatures']` | Informazioni di riferimento per eventuali firme innescati per la richiesta corrente.
`$CIDRAM['BlockInfo']['WhyReason']` | Informazioni di riferimento per eventuali firme innescati per la richiesta corrente.

---


### 8. <a name="SECTION8"></a>CONOSCIUTI COMPATIBILITÀ PROBLEMI

I seguenti pacchetti e prodotti sono stati trovati incompatibili con CIDRAM:
- __[Endurance Page Cache](https://github.com/CIDRAM/CIDRAM/issues/52)__
- __[Mix.com](https://github.com/CIDRAM/CIDRAM/issues/80)__

I moduli sono stati resi disponibili per garantire che i seguenti pacchetti e prodotti siano compatibili con CIDRAM:
- __[BunnyCDN](https://github.com/CIDRAM/CIDRAM/issues/56)__

*Guarda anche: [Grafici di Compatibilità](https://maikuolan.github.io/Compatibility-Charts/).*

---


### 9. <a name="SECTION9"></a>DOMANDE FREQUENTI (FAQ)

- [Che cos'è una "firma"?](#WHAT_IS_A_SIGNATURE)
- [Che cos'è un "CIDR"?](#WHAT_IS_A_CIDR)
- [Che cosa è un "falso positivo"?](#WHAT_IS_A_FALSE_POSITIVE)
- [Può CIDRAM blocchi interi paesi?](#BLOCK_ENTIRE_COUNTRIES)
- [Con quale frequenza vengono aggiornate le firme?](#SIGNATURE_UPDATE_FREQUENCY)
- [Ho incontrato un problema durante l'utilizzo CIDRAM e non so che cosa fare al riguardo! Aiutami!](#ENCOUNTERED_PROBLEM_WHAT_TO_DO)
- [CIDRAM mi ha bloccato da un sito web che voglio visitare! Aiutami!](#BLOCKED_WHAT_TO_DO)
- [Voglio usare CIDRAM con una versione di PHP più vecchio di 5.4.0; Puoi aiutami?](#MINIMUM_PHP_VERSION)
- [Posso utilizzare un'installazione singola di CIDRAM per proteggere più domini?](#PROTECT_MULTIPLE_DOMAINS)
- [Non voglio perdere tempo con l'installazione di questo e farlo funzionare con il mio sito web; Posso pagarti per farlo per me?](#PAY_YOU_TO_DO_IT)
- [Posso assumere voi o uno degli sviluppatori di questo progetto per lavori privati?](#HIRE_FOR_PRIVATE_WORK)
- [Ho bisogno di modifiche specialistiche, personalizzazioni, ecc; Puoi aiutare?](#SPECIALIST_MODIFICATIONS)
- [Sono uno sviluppatore, un designer di siti web o un programmatore. Posso accettare o offrire lavori relativi a questo progetto?](#ACCEPT_OR_OFFER_WORK)
- [Voglio contribuire al progetto; Posso farlo?](#WANT_TO_CONTRIBUTE)
- [Posso utilizzare il cron per aggiornare automaticamente?](#CRON_TO_UPDATE_AUTOMATICALLY)
- [Cosa sono le "infrazioni"?](#WHAT_ARE_INFRACTIONS)
- [CIDRAM può bloccare i nomi degli host?](#BLOCK_HOSTNAMES)
- [Cosa posso usare per "default_dns"?](#WHAT_CAN_I_USE_FOR_DEFAULT_DNS)
- [Posso usare CIDRAM per proteggere cose diverse dai siti web (per esempio, server di posta elettronica, FTP, SSH, IRC, ecc)?](#PROTECT_OTHER_THINGS)
- [Avrò problemi se utilizzo CIDRAM contemporaneamente all'utilizzo di CDN o servizi di cache?](#CDN_CACHING_PROBLEMS)
- [CIDRAM proteggerà il mio sito Web dagli attacchi DDoS?](#DDOS_ATTACKS)
- [Quando si attivano o disattivano moduli o file di firma tramite la pagina degli aggiornamenti, li ordina in ordine alfanumerico nella configurazione. Posso cambiare il modo in cui vengono ordinati?](#CHANGE_COMPONENT_SORT_ORDER)

#### <a name="WHAT_IS_A_SIGNATURE"></a>Che cos'è una "firma"?

Nel contesto di CIDRAM, una "firma" si riferisce a dati che fungono da indicatore/identificatore per qualcosa di specifico che stiamo cercando, di solito un indirizzo IP o CIDR, e include alcune istruzioni per CIDRAM, dicendogli il modo migliore per rispondere quando incontra quello che stiamo cercando. Una firma tipica per CIDRAM sembra qualcosa di simile:

Per "file di firma":

`1.2.3.4/32 Deny Generic`

Per "moduli":

```PHP
$Trigger(strpos($CIDRAM['BlockInfo']['UA'], 'Foobar') !== false, 'Foobar-UA', 'User agent "Foobar" not allowed.');
```

*Nota: Le firme per "file di firma", e le firme per "moduli", non sono la stessa cosa.*

Spesso (ma non sempre), le firme verranno raggruppate in gruppi, formando "sezioni di firma", spesso accompagnati da commenti, markup e/o metadati correlati che possono essere utilizzati per fornire contesto aggiuntivo per le firme e/o ulteriori istruzioni.

#### <a name="WHAT_IS_A_CIDR"></a>Che cos'è un "CIDR"?

"CIDR" è un acronimo di "Classless Inter-Domain Routing" *[[1](https://it.wikipedia.org/wiki/Supernetting#CIDR), [2](http://whatismyipaddress.com/cidr)]* (talvolta noto come "supernetting"), ed è questo acronimo che viene utilizzato come parte del nome di questo pacchetto, "CIDRAM", di cui che è un acronimo di "Classless Inter-Domain Routing Access Manager".

Tuttavia, nel contesto di CIDRAM (ad esempio, all'interno di questa documentazione, nelle discussioni relative a CIDRAM, o all'interno dei dati linguistici di CIDRAM), ogni volta che viene menzionato o riferito un "CIDR" (singolare) o "CIDRs" (plurale), e quindi per cui noi usiamo queste parole come nomi a loro diritto (al contrario di come acronimi), ciò che è inteso e significato da questa è una sottorete/sottoreti (o subnet/subnets), espresso utilizzando la notazione CIDR. Il motivo per cui vengono utilizzati CIDR/CIDRs anziché sottorete/sottoreti (o subnet/subnets) è quello di rendere chiaro che è specificamente le sottoreti espresse utilizzando la notazione CIDR a cui si fa riferimento (perché la notazione CIDR è solo uno dei diversi modi in cui le sottoreti possono essere espresse). CIDRAM potrebbe quindi essere considerato un "subnet access manager" o un "gestore di accesso per le sottoreti".

Anche se questo duplice significato di "CIDR" può presentare qualche ambiguità in alcuni casi, questa spiegazione, insieme al contesto fornito, dovrebbe contribuire a risolvere tale ambiguità.

#### <a name="WHAT_IS_A_FALSE_POSITIVE"></a>Che cosa è un "falso positivo"?

Il termine "falso positivo" (*in alternativa: "errore di falso positivo"; "falso allarme"*; Inglese: *false positive*; *false positive error*; *false alarm*), descritto molto semplicemente, e in un contesto generalizzato, viene utilizzato quando si analizza una condizione, per riferirsi ai risultati di tale analisi, quando i risultati sono positivi (cioè, la condizione è determinata a essere "positivo", o "vero"), ma dovrebbero essere (o avrebbe dovuto essere) negativo (cioè, la condizione, in realtà, è "negativo", o "falso"). Un "falso positivo" potrebbe essere considerato analogo a "piangendo lupo" (dove la condizione di essere analizzato è se c'è un lupo nei pressi della mandria, la condizione è "falso" in che non c'è nessun lupo nei pressi della mandria, e la condizione viene segnalato come "positivo" dal pastore per mezzo di chiamando "lupo, lupo"), o analogo a situazioni di test medici dove un paziente viene diagnosticato una malattia, quando in realtà, non hanno qualsiasi malattia.

Risultati correlati quando si analizza una condizione può essere descritto utilizzando i termini "vero positivo", "vero negativo" e "falso negativo". Un "vero positivo" si riferisce a quando i risultati dell'analisi e lo stato attuale della condizione sono entrambi vero (o "positivo"), e un "vero negativo" si riferisce a quando i risultati dell'analisi e lo stato attuale della condizione sono entrambe falso (o "negativo"); Un "vero positivo" o un "vero negativo" è considerato una "inferenza corretta". L'antitesi di un "falso positivo" è un "falso negativo"; Un "falso negativo" si riferisce a quando i risultati dell'analisi sono negativo (cioè, la condizione è determinata a essere "negativo", o "falso"), ma dovrebbero essere (o avrebbe dovuto essere) positivo (cioè, la condizione, in realtà, è "positivo", o "vero").

Nel contesto di CIDRAM, questi termini si riferiscono alle firme di CIDRAM e che cosa/chi bloccano. Quando CIDRAM si blocca un indirizzo IP a causa di firme male, obsoleti o errati, ma non avrebbe dovuto fare così, o quando lo fa per le ragioni sbagliate, ci riferiamo a questo evento come un "falso positivo". Quando CIDRAM non riesce a bloccare un indirizzo IP che avrebbe dovuto essere bloccato, a causa delle minacce impreviste, firme mancante o carenze nelle sue firme, ci riferiamo a questo evento come una "rivelazione mancante" o "missed detection" (che è analoga ad un "falso negativo").

Questo può essere riassunta dalla seguente tabella:

&nbsp; | CIDRAM *NON* dovrebbe bloccare un indirizzo IP | CIDRAM *DOVREBBE* bloccare un indirizzo IP
---|---|---
CIDRAM *NON* bloccare un indirizzo IP | Vero negativo (inferenza corretta) | Rivelazione mancante (analogous to falso negativo)
CIDRAM *FA* bloccare un indirizzo IP | __Falso positivo__ | Vero positivo (inferenza corretta)

#### <a name="BLOCK_ENTIRE_COUNTRIES"></a>Può CIDRAM blocchi interi paesi?

Sì. Il modo più semplice per raggiungere questo obiettivo sarebbe quella di installare alcune delle liste opzionali per il bloccando di paesi forniti da Macmathan. Questo può essere fatto direttamente dalla pagina degli aggiornamenti situato nel front-end, o, se si preferisce per il front-end di rimanere disabile, da scaricandoli direttamente dalla **[pagina per il scaricando delle liste opzionali per il bloccando di paesi](https://bitbucket.org/macmathan/blocklists)**, caricandoli alla vault, e citando i loro nomi nelle direttive di configurazione rilevanti.

#### <a name="SIGNATURE_UPDATE_FREQUENCY"></a>Con quale frequenza vengono aggiornate le firme?

Frequenza di aggiornamento varia a seconda delle file di firma in questione. Tutti i manutentori per i file di firma per CIDRAM in genere cercano di mantenere i loro firme aggiornato il più possibile, ma a causa di tutti noi abbiamo diversi altri impegni, la nostra vita al di fuori del progetto, e a causa di nessuno di noi sono finanziariamente compensato (o pagato) per i nostri sforzi sul progetto, un calendario di aggiornamento preciso non può essere garantita. In genere, le firme vengono aggiornati ogni volta che c'è abbastanza tempo per aggiornarli, e generalmente, manutentori cercano di dare la priorità sulla base di necessità e su come spesso i cambiamenti si verificano tra le gamme. L'assistenza è sempre apprezzato se siete disposti a offrire qualsiasi.

#### <a name="ENCOUNTERED_PROBLEM_WHAT_TO_DO"></a>Ho incontrato un problema durante l'utilizzo CIDRAM e non so che cosa fare al riguardo! Aiutami!

- Si sta utilizzando la versione più recente del software? Si sta utilizzando le ultime versioni dei file di firma? Se la risposta a una di queste due domande è no, provare ad aggiornare tutto prima, e verificare se il problema persiste. Se persiste, continuare a leggere.
- Hai controllato attraverso tutta la documentazione? In caso non fatto, si prega di farlo. Se il problema non può essere risolto utilizzando la documentazione, continuare a leggere.
- Hai controllato la **[pagina dei issues](https://github.com/CIDRAM/CIDRAM/issues)**, per vedere se il problema è stato accennato prima? Se è stato accennato prima, verificare se sono stati forniti qualsiasi suggerimenti, idee, e/o soluzioni, e seguire come necessario per cercare di risolvere il problema.
- Se il problema persiste, si prega di cercare aiuto su di esso per mezzo di creando un nuovo issue nella pagina dei issues.

#### <a name="BLOCKED_WHAT_TO_DO"></a>CIDRAM mi ha bloccato da un sito web che voglio visitare! Aiutami!

CIDRAM fornisce un mezzo per proprietari di siti web per bloccare il traffico indesiderato, ma è la responsabilità dei proprietari di siti web di decidere per se stessi come vogliono usare CIDRAM. Nel caso dei falsi positivi relativi alla firma file normalmente incluso con CIDRAM, correzioni possono essere fatte, ma per essere sbloccato da siti web specifici, è necessario prendere quella con i proprietari dei siti web in questione. Nei casi in cui vengono effettuate correzioni, almeno, avranno bisogno di aggiornare i propri file di firma e/o installazione, e in altri casi (come ad esempio, dove hanno modificato il loro installazione, creato le proprie firme personalizzate, ecc), la responsabilità di risolvere il problema è tutto loro, ed è completamente al di fuori del nostro controllo.

#### <a name="MINIMUM_PHP_VERSION"></a>Voglio usare CIDRAM con una versione di PHP più vecchio di 5.4.0; Puoi aiutami?

No. PHP 5.4.0 raggiunto EoL ("End of Life", o fine della vita) ufficiale nel 2014, e il supporto di sicurezza esteso è stato terminato nel 2015. Come della stesura di questo, è il 2017, e PHP 7.1.0 è già disponibile. In questo momento, il supporto è fornito per l'utilizzo di CIDRAM con PHP 5.4.0 e tutte le versioni di PHP più recenti disponibili, ma se si tenta di utilizzare CIDRAM con le versioni di PHP più vecchie, supporto non sarà fornito.

*Guarda anche: [Grafici di Compatibilità](https://maikuolan.github.io/Compatibility-Charts/).*

#### <a name="PROTECT_MULTIPLE_DOMAINS"></a>Posso utilizzare un'installazione singola di CIDRAM per proteggere più domini?

Sì. Le installazioni di CIDRAM non sono naturalmente legato a domini specifici, e quindi possono essere utilizzati per proteggere più domini. Generalmente, ci riferiamo alle installazioni di CIDRAM che proteggono un solo dominio come "installazioni per singolo dominio", e ci riferiamo a installazioni di CIDRAM che proteggono più domini e/o sottodomini come "installazioni per più domini". Se si esegue un'installazione per più domini e bisogno utilizzare diversi set di file di firma per diversi domini, o bisogno che CIDRAM essere configurato in modo diverso per diversi domini, è possibile farlo. Dopo aver caricato il file di configurazione (`config.ini`), CIDRAM verifica l'esistenza di un "file di sovrascrittura per la configurazione" specifico del dominio (o sottodominio) che viene richiesto (`il-dominio-che-viene-richiesto.tld.config.ini`), e se trovati, tutti i valori di configurazione definiti dal file di sovrascrittura per la configurazione verranno utilizzati per l'istanza di esecuzione invece dei valori di configurazione definiti dal file di configurazione. I file di sovrascrittura per la configurazione sono identiche al file di configurazione, e a vostra discrezione, può contenere l'insieme di tutte le direttive di configurazione disponibili a CIDRAM, o qualsiasi piccola sottosezione richiesta che differisca dai valori normalmente definiti dal file di configurazione. I file di sovrascrittura per la configurazione sono chiamati in base al dominio a cui sono destinati (così, per esempio, se hai bisogno di un file di sovrascrittura per la configurazione per il dominio, `http://www.some-domain.tld/`, la sua file di sovrascrittura per la configurazione deve essere denominato come `some-domain.tld.config.ini`, e deve essere collocato all'interno della vault insieme al file di configurazione, `config.ini`). Il nome di dominio per l'istanza di esecuzione è derivato dall'intestazione `HTTP_HOST` della richiesta; "www" viene ignorato.

#### <a name="PAY_YOU_TO_DO_IT"></a>Non voglio perdere tempo con l'installazione di questo e farlo funzionare con il mio sito web; Posso pagarti per farlo per me?

Forse. Ciò è considerato caso per caso. Dicci cosa hai bisogno, quello che stai offrendo, e ti dirà se possiamo aiutare.

#### <a name="HIRE_FOR_PRIVATE_WORK"></a>Posso assumere voi o uno degli sviluppatori di questo progetto per lavori privati?

*Vedi sopra.*

#### <a name="SPECIALIST_MODIFICATIONS"></a>Ho bisogno di modifiche specialistiche, personalizzazioni, ecc; Puoi aiutare?

*Vedi sopra.*

#### <a name="ACCEPT_OR_OFFER_WORK"></a>Sono uno sviluppatore, un designer di siti web o un programmatore. Posso accettare o offrire lavori relativi a questo progetto?

Sì. La nostra licenza non vieta questo.

#### <a name="WANT_TO_CONTRIBUTE"></a>Voglio contribuire al progetto; Posso farlo?

Sì. I contributi al progetto sono molto graditi. Per ulteriori informazioni, vedere "CONTRIBUTING.md".

#### <a name="CRON_TO_UPDATE_AUTOMATICALLY"></a>Posso utilizzare il cron per aggiornare automaticamente?

Sì. Una API è incorporata nel front-end per interagire con la pagina degli aggiornamenti tramite script esterni. È disponibile uno script separato, "[Cronable](https://github.com/Maikuolan/Cronable)", e può essere utilizzato dal tuo cron manager o cron scheduler per aggiornare automaticamente questo e altri pacchetti supportati (questo script fornisce la propria documentazione).

#### <a name="WHAT_ARE_INFRACTIONS"></a>Cosa sono le "infrazioni"?

Le "infrazioni" determinano quando un IP che non è ancora bloccato da uno specifico file di firma dovrebbe iniziare a essere bloccato per eventuali richieste future, e sono strettamente associati al tracciamento IP. Esistono alcune funzionalità e moduli che consentono di bloccare le richieste per motivi diversi dall'IP di origine (ad esempio la presenza di agenti utente [user agents] corrispondenti a spambots o hacktools, richieste pericolose, DNS falsificato e così via), e quando ciò accade, può verificarsi una "infrazione". Forniscono un modo per identificare gli indirizzi IP che corrispondono a richieste indesiderate che potrebbero non essere ancora bloccate da alcun file di firma specifico. Le infrazioni di solito corrispondono 1-a-1 con il numero di volte in cui un IP è bloccato, ma non sempre (eventi di blocco gravi possono incorrere in un valore di infrazione maggiore di uno, e se "track_mode" è false, le infrazioni non si verificano per gli eventi di blocco innescati esclusivamente dai file delle firme).

#### <a name="BLOCK_HOSTNAMES"></a>CIDRAM può bloccare i nomi degli host?

Sì. Per fare ciò, dovrai creare un file modulo personalizzato. *Vedere: [NOZIONI DI BASE (PER MODULI)](#MODULE_BASICS)*.

#### <a name="WHAT_CAN_I_USE_FOR_DEFAULT_DNS"></a>Cosa posso usare per "default_dns"?

Se stai cercando suggerimenti, [public-dns.info](https://public-dns.info/) e [OpenNIC](https://servers.opennic.org/) forniscono elenchi di server DNS pubblici noti. In alternativa, vedi la tabella seguente:

IP | Operatore
---|---
`1.1.1.1` | [Cloudflare](https://www.cloudflare.com/learning/dns/what-is-1.1.1.1/)
`4.2.2.1`<br />`4.2.2.2`<br />`209.244.0.3`<br />`209.244.0.4` | [Level3](https://www.level3.com/en/)
`8.8.4.4`<br />`8.8.8.8`<br />`2001:4860:4860::8844`<br />`2001:4860:4860::8888` | [Google Public DNS](https://developers.google.com/speed/public-dns/)
`9.9.9.9`<br />`149.112.112.112` | [Quad9 DNS](https://www.quad9.net/)
`84.200.69.80`<br />`84.200.70.40`<br />`2001:1608:10:25::1c04:b12f`<br />`2001:1608:10:25::9249:d69b` | [DNS.WATCH](https://dns.watch/index)
`208.67.220.220`<br />`208.67.222.220`<br />`208.67.222.222` | [OpenDNS Home](https://www.opendns.com/)
`77.88.8.1`<br />`77.88.8.8`<br />`2a02:6b8::feed:0ff`<br />`2a02:6b8:0:1::feed:0ff` | [Yandex.DNS](https://dns.yandex.com/advanced/)
`8.20.247.20`<br />`8.26.56.26` | [Comodo Secure DNS](https://www.comodo.com/secure-dns/)
`216.146.35.35`<br />`216.146.36.36` | [Dyn](https://help.dyn.com/internet-guide-setup/)
`64.6.64.6`<br />`64.6.65.6` | [Verisign Public DNS](https://www.verisign.com/en_US/security-services/public-dns/index.xhtml)
`37.235.1.174`<br />`37.235.1.177` | [FreeDNS](https://freedns.zone/en/)
`156.154.70.1`<br />`156.154.71.1`<br />`2610:a1:1018::1`<br />`2610:a1:1019::1` | [Neustar Security](https://www.security.neustar/dns-services/free-recursive-dns-service)
`45.32.36.36`<br />`45.77.165.194`<br />`179.43.139.226` | [Fourth Estate](https://dns.fourthestate.co/)
`74.82.42.42` | [Hurricane Electric](https://dns.he.net/)
`195.46.39.39`<br />`195.46.39.40` | [SafeDNS](https://www.safedns.com/en/features/)
`81.218.119.11`<br />`209.88.198.133` | [GreenTeam Internet](http://www.greentm.co.uk/)
`89.233.43.71`<br />`91.239.100.100 `<br />`2001:67c:28a4::`<br />`2a01:3a0:53:53::` | [UncensoredDNS](https://blog.uncensoreddns.org/)
`208.76.50.50`<br />`208.76.51.51` | [SmartViper](http://www.markosweb.com/free-dns/)

*Nota: Non faccio reclami o garanzie riguardo alle pratiche di privacy, sicurezza, efficacia, né affidabilità di alcun servizio DNS, elencati o altrimenti. Per favore fai le tue ricerche quando prendi decisioni su di loro.*

#### <a name="PROTECT_OTHER_THINGS"></a>Posso usare CIDRAM per proteggere cose diverse dai siti web (per esempio, server di posta elettronica, FTP, SSH, IRC, ecc)?

Puoi (legalmente), ma non dovresti (tecnicamente; praticamente). La nostra licenza non limita le tecnologie che implementano CIDRAM, ma CIDRAM è un WAF (Web Application Firewall) ed è sempre stato concepito per proteggere i siti web. Poiché non è stato progettato pensando ad altre tecnologie, è improbabile che sia efficace o fornisca una protezione affidabile per altre tecnologie, l'implementazione è probabile che sia difficile, e il rischio di falsi positivi e rilevazioni mancate è molto alto.

#### <a name="CDN_CACHING_PROBLEMS"></a>Avrò problemi se utilizzo CIDRAM contemporaneamente all'utilizzo di CDN o servizi di cache?

Possibilmente. Questo dipende dalla natura del servizio in questione e da come lo stai usando. In genere, se si memorizzano nella cache solo risorse statiche (immagini, CSS, ecc; tutto ciò che generalmente non cambia nel tempo), non dovrebbero esserci problemi. Tuttavia, potrebbero verificarsi problemi se si memorizzano nella cache dati che altrimenti verrebbe generati in modo dinamico quando richiesto, o se stai memorizzando nella cache i risultati delle richieste POST (questo renderebbe essenzialmente statico il tuo sito web e il suo ambiente, e CIDRAM non è probabile di fornire alcun beneficio significativo in un ambiente statico obbligatorio). Potrebbero inoltre esserci requisiti di configurazione specifici per CIDRAM, basato sul servizio CDN o servizio cache che stai utilizzando (dovrai assicurarti che CIDRAM sia configurato correttamente per il servizio specifico di CDN o cache che stai utilizzando). La configurazione scorretto per CIDRAM può causare falsi positivi e rilevamenti mancati in modo significativo.

#### <a name="DDOS_ATTACKS"></a>CIDRAM proteggerà il mio sito Web dagli attacchi DDoS?

Risposta breve: No.

Risposta leggermente più lunga: CIDRAM aiuterà a ridurre l'impatto che il traffico indesiderato può avere sul tuo sito web (riducendo così i costi della larghezza di banda del tuo sito web), aiuterà a ridurre l'impatto che il traffico indesiderato può avere sul tuo hardware (ad esempio, la capacità del tuo server di elaborare e servire richieste), e può aiutare a ridurre vari altri potenziali effetti negativi associati al traffico indesiderato. Tuttavia, ci sono due cose importanti che devono essere ricordate per capire questa domanda.

In primo luogo, CIDRAM è un pacchetto PHP e pertanto opera nella macchina su cui è installato PHP. Ciò significa che CIDRAM può vedere e bloccare una richiesta solo *dopo* che il server l'ha già ricevuta. In secondo luogo, l'efficace mitigazione DDoS dovrebbe filtrare le richieste *prima* che raggiungano il server bersaglio dell'attacco DDoS. Idealmente, gli attacchi DDoS dovrebbero essere rilevati e mitigati da soluzioni in grado di rilasciare o reindirizzare il traffico associato agli attacchi, prima che raggiunga il server di destinazione in primo luogo.

Questo può essere implementato utilizzando soluzioni hardware dedicati on-premise, e/o soluzioni basate su cloud come servizi di attenuazione DDoS dedicati, indirizzare il DNS di un dominio attraverso reti resistenti agli attacchi DDoS, filtraggio basato su cloud, o qualche combinazione di questi. In ogni caso, questo argomento è un po 'troppo complicato da spiegare a fondo con un semplice paragrafo o due, quindi consiglierei di fare ulteriori ricerche se questo è un argomento che si desidera perseguire. Quando la vera natura degli attacchi DDoS è correttamente compresa, questa risposta avrà più senso.

#### <a name="CHANGE_COMPONENT_SORT_ORDER"></a>Quando si attivano o disattivano moduli o file di firma tramite la pagina degli aggiornamenti, li ordina in ordine alfanumerico nella configurazione. Posso cambiare il modo in cui vengono ordinati?

Sì. Se è necessario forzare alcuni file per l'esecuzione in un ordinamento specifico, è possibile aggiungere alcuni dati arbitrari prima dei loro nomi nella direttiva di configurazione in cui sono elencati, separati da due punti. Quando la pagina degli aggiornamenti ordina di nuovo i file, questi dati arbitrari aggiunti influenzeranno l'ordinamento, causandoli di conseguenza nell'ordinamento che si desidera, senza dover rinominare nessuno di essi.

Ad esempio, assumendo una direttiva di configurazione con file elencati come segue:

`file1.php,file2.php,file3.php,file4.php,file5.php`

Se si desidera che `file3.php` esegua prima, potresti aggiungere qualcosa come `aaa:` prima del nome del file:

`file1.php,file2.php,aaa:file3.php,file4.php,file5.php`

Quindi, se un nuovo file, `file6.php`, viene attivato, quando la pagina degli aggiornamenti li ordina di nuovo tutti, dovrebbe finire in questo modo:

`aaa:file3.php,file1.php,file2.php,file4.php,file5.php,file6.php`

Stessa situazione quando un file è disattivato. Al contrario, se si desidera che il file venga eseguito per ultimo, è possibile aggiungere qualcosa come `zzz:` prima del nome del file. In ogni caso, non sarà necessario rinominare il file in questione.

---


### 11. <a name="SECTION11"></a>INFORMAZIONE LEGALE

#### 11.0 SEZIONE PREAMBOLO

Questa sezione della documentazione ha lo scopo di descrivere possibili considerazioni legali riguardanti l'uso e l'implementazione del pacchetto, e fornire alcune informazioni di base relative. Questo può essere importante per alcuni utenti come mezzo per garantire la conformità con eventuali requisiti legali che possono esistere nei paesi in cui operano, e alcuni utenti potrebbero aver bisogno di modificare le loro politiche del sito web in conformità con queste informazioni.

Innanzitutto per favore, renditi conto che io (l'autore del pacchetto) non sono un avvocato, né un professionista legale qualificato di alcun tipo. Pertanto, non sono legalmente qualificato per fornire consulenza legale. Inoltre, in alcuni casi, i requisiti legali esatti possono variare a seconda dei paesi e delle giurisdizioni, e questi varie requisiti legali possono a volte entrare in conflitto (come, per esempio, nel caso di paesi che favoriscono i [diritti alla privacy](https://it.wikipedia.org/wiki/Privacy) e il [diritto all'oblio](https://it.wikipedia.org/wiki/Diritto_all%27oblio), contro paesi che favoriscono la [conservazione estesa dei dati](https://it.wikipedia.org/wiki/Direttiva_sulla_conservazione_dei_dati)). Considera inoltre che l'accesso al pacchetto non è limitato a specifici paesi o giurisdizioni, e quindi, la base utente del pacchetto è probabilmente geograficamente diversa. Considerando questi punti, non sono in grado di affermare cosa significa essere "legalmente conformi" per tutti gli utenti, in ogni aspetto. Tuttavia, spero che le informazioni qui contenute ti aiutino a prendere una decisione autonomamente riguardo a ciò che devi fare per rimanere legalmente conforme nel contesto del pacchetto. In caso di dubbi o preoccupazioni in merito alle informazioni qui contenute, o se hai bisogno di aiuto e consigli aggiuntivi da un punto di vista legale, consiglierei di consultare un professionista legale qualificato.

#### 11.1 RESPONSABILITÀ

Come già indicato dalla licenza del pacchetto, il pacchetto è fornito senza alcuna garanzia. Questo include (ma non è limitato a) tutti gli ambiti di responsabilità. Il pacchetto ti è stato fornito per vostra comodità, nella speranza che sia utile e che ti fornisca alcuni vantaggi. Tuttavia, se si utilizza o si implementa il pacchetto, è una scelta personale. Non sei obbligato a utilizzare o implementare il pacchetto, ma quando lo fai, sei responsabile di tale decisione. Né io né alcun altro contributore al pacchetto siamo legalmente responsabili delle conseguenze delle decisioni che prendete, indipendentemente dal fatto che siano dirette, indirette, implicite, o meno.

#### 11.2 TERZE PARTI

A seconda della sua esatta configurazione e implementazione, in alcuni casi il pacchetto può comunicare e condividere informazioni con terze parti. Questa informazione può essere definita come "[dati personali](https://it.wikipedia.org/wiki/Dati_personali)" (PII) in alcuni contesti, da alcune giurisdizioni.

Il modo in cui queste informazioni possono essere utilizzate da queste terze parti, è soggetto alle varie politiche stabilite da queste terze parti e non rientra nell'ambito di questa documentazione. Tuttavia, in tutti questi casi, la condivisione di informazioni con queste terze parti può essere disabilitata. In tutti questi casi, se si sceglie di abilitarlo, è vostra responsabilità ricercare eventuali eventuali dubbi relativi alla privacy, alla sicurezza e all'utilizzo delle PII da parte di queste terze parti. In caso di dubbi, o se non sei soddisfatto della condotta di queste terze parti in merito alle PII, è meglio disabilitare tutte le informazioni condivise con queste terze parti.

Ai fini della trasparenza, il tipo di informazioni condivise e con chi è descritto di seguito.

##### 11.2.0 RICERCA DEL NOME HOST

Se si utilizzano funzioni o moduli destinati a funzionare con nomi host (come ad esempio il "modulo bloccante di cattivi host", "tor project exit nodes block module", o la "verifica dei motori di ricerca"), CIDRAM deve essere in grado di ottenere in qualche modo il nome host delle richieste in entrata. In genere, lo fa richiedendo il nome host dell'indirizzo IP delle richieste in entrata da un server DNS o richiedendo le informazioni tramite la funzionalità fornita dal sistema su cui è installato CIDRAM (questo in genere viene definito come un "ricerca del nome host"). I server DNS definiti per impostazione predefinita appartengono al servizio di [Google DNS](https://dns.google.com/) (ma possono essere facilmente modificati tramite la configurazione). I servizi esatti comunicati sono configurabili, e dipendono dalla modalità di configurazione del pacchetto. Nel caso in cui si utilizzi la funzionalità fornita dal sistema in cui è installato CIDRAM, è necessario contattare l'amministratore di sistema per determinare quali percorsi utilizzano le ricerche del nome host. Le ricerche del nome host possono essere prevenute in CIDRAM evitando i moduli interessati o modificando la configurazione del pacchetto in base alle proprie esigenze.

*Direttive di configurazione rilevanti:*
- `general` -> `default_dns`
- `general` -> `search_engine_verification`
- `general` -> `social_media_verification`
- `general` -> `force_hostname_lookup`
- `general` -> `allow_gethostbyaddr_lookup`

##### 11.2.1 WEBFONTS

Alcuni temi personalizzati, nonché l'interfaccia utente standard ("UI") per il front-end CIDRAM, e la pagina "Accesso Negato", possono utilizzare i webfonts per motivi estetici. I webfonts sono disabilitati per impostazione predefinita, ma quando abilitati, avviene una comunicazione diretta tra il browser dell'utente e il servizio che ospita i webfonts. Ciò potrebbe implicare la comunicazione di informazioni quali l'indirizzo IP dell'utente, l'agente utente, il sistema operativo, e altri dettagli disponibili per la richiesta. La maggior parte di questi webfonts è ospitata dal servizio [Google Fonts](https://fonts.google.com/).

*Direttive di configurazione rilevanti:*
- `general` -> `disable_webfonts`

##### 11.2.2 VERIFICA DEI MOTORI DI RICERCA E DEI SOCIAL MEDIA

Quando la verifica dei motori di ricerca è abilitata, CIDRAM tenta di eseguire "inoltrare ricerche DNS" per verificare se le richieste che provengono presumibilmente dai motori di ricerca sono autentiche. Allo stesso modo, quando la verifica dei social media è abilitata, CIDRAM fa lo stesso per le apparenti richieste di social media. Per fare ciò, utilizza il servizio [Google DNS](https://dns.google.com/) per tentare di risolvere gli indirizzi IP dai nomi host di queste richieste in entrata (in questo processo, i nomi host di queste richieste in entrata sono condivisi con il servizio).

*Direttive di configurazione rilevanti:*
- `general` -> `search_engine_verification`
- `general` -> `social_media_verification`

##### 11.2.3 GOOGLE reCAPTCHA

CIDRAM supporta facoltativamente [Google reCAPTCHA](https://www.google.com/recaptcha/), fornendo agli utenti un mezzo per aggirare la pagina "Accesso Negato" completando un'istanza di reCAPTCHA (ulteriori informazioni su questa funzione sono descritte in precedenza nella documentazione, in particolare nella sezione di configurazione). Google reCAPTCHA richiede le chiavi API per funzionare correttamente, ed è quindi disabilitata per impostazione predefinita. Può essere abilitato definendo le chiavi API richieste nella configurazione del pacchetto. Se abilitato, si verifica una comunicazione diretta tra il browser dell'utente e il servizio reCAPTCHA. Ciò potrebbe implicare la comunicazione di informazioni quali l'indirizzo IP dell'utente, l'agente utente, il sistema operativo, e altri dettagli disponibili per la richiesta. L'indirizzo IP dell'utente può anche essere condiviso nelle comunicazioni tra CIDRAM e il servizio reCAPTCHA quando si verifica la validità di un'istanza di reCAPTCHA e si verifica se è stata completata correttamente.

*Direttive di configurazione rilevanti: Qualsiasi cosa elencata nella categoria di configurazione "recaptcha".*

##### 11.2.4 STOP FORUM SPAM

[Stop Forum Spam](https://www.stopforumspam.com/) è un servizio fantastico, disponibile gratuitamente che può aiutare a proteggere forum, blog, e siti Web dagli spammer. Lo fa fornendo un database di spammer conosciuti e un'API che può essere sfruttata per verificare se un indirizzo IP, un nome utente, o un indirizzo di posta elettronica sono elencati nel suo database.

CIDRAM fornisce un modulo opzionale che sfrutta questa API per verificare se l'indirizzo IP delle richieste in entrata appartiene a un sospetto spammer. Il modulo non è installato per impostazione predefinita, ma se si sceglie di installarlo, gli indirizzi IP dell'utente possono essere condivisi con l'API Stop Forum Spam in conformità con lo scopo previsto del modulo. Quando il modulo è installato, CIDRAM comunica con questa API ogni volta che una richiesta in entrata richiede una risorsa riconosciuta da CIDRAM come un tipo di risorsa spesso bersagliato dagli spammer (come pagine di login, pagine di registrazione, pagine di verifica di posta elettronica, moduli di commento, ecc).

#### 11.3 REGISTRAZIONE

La registrazione è una parte importante di CIDRAM per una serie di motivi. Potrebbe essere difficile diagnosticare e risolvere i falsi positivi quando gli eventi di blocco che li causano non vengono registrati. Senza registrare gli eventi di blocco, potrebbe essere difficile accertare esattamente quanto è performante CIDRAM in un particolare contesto, e potrebbe essere difficile determinare dove potrebbero essere le sue carenze, e quali modifiche potrebbero essere richieste alla sua configurazione o alle sue firme di conseguenza, affinché possa continuare a funzionare come previsto. Ciò nonostante, la registrazione potrebbe non essere auspicabile per tutti gli utenti, e rimane del tutto facoltativa. In CIDRAM, la registrazione è disabilitata per impostazione predefinita. Per abilitarlo, CIDRAM deve essere configurato di conseguenza.

Inoltre, se la registrazione è legalmente ammissibile, e nella misura in cui è legalmente ammissibile (ad esempio, i tipi di informazioni che possono essere registrati, per quanto tempo, e in quali circostanze), può variare, a seconda della giurisdizione e del contesto in cui è implementata la CIDRAM (ad esempio, se stai operando come individuo, come entità aziendale, e se commerciale o non commerciale). Potrebbe quindi essere utile leggere attentamente questa sezione.

Esistono diversi tipi di registrazione che CIDRAM può eseguire. Diversi tipi di registrazione coinvolgono diversi tipi di informazioni, per diversi motivi.

##### 11.3.0 EVENTI DI BLOCCO

Il tipo principale di registrazione che CIDRAM può eseguire correla agli "eventi di blocco". Questo tipo di registrazione si riferisce a quando CIDRAM blocca una richiesta, e può essere fornita in tre formati diversi:
- File di log leggibili dall'uomo.
- File di log in stile Apache.
- File di log serializzati.

Un evento di blocco, registrato in un file di log leggibile dall'uomo, in genere assomiglia a qualcosa come questo (ad esempio):

```
ID: 1234
Versione dello script: CIDRAM v1.6.0
Data/Tempo: Day, dd Mon 20xx hh:ii:ss +0000
Indirizzo IP: x.x.x.x
Nome host: dns.hostname.tld
Firme Conteggio: 1
Firme Riferimento: x.x.x.x/xx
Perché Bloccato: Servizio cloud ("Nome della rete", Lxx:Fx, [XX])!
User Agent: Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.181 Safari/537.36
URI Ricostruito: http://your-site.tld/index.php
Stato reCAPTCHA: Attivato.
```

Lo stesso evento di blocco, registrato in un file di log in stile Apache, sarebbe simile a questo:

```
x.x.x.x - - [Day, dd Mon 20xx hh:ii:ss +0000] "GET /index.php HTTP/1.1" 200 xxxx "-" "Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.181 Safari/537.36"
```

Un evento di blocco registrato include in genere le seguenti informazioni:
- Un numero ID che fa riferimento all'evento di blocco.
- La versione di CIDRAM attualmente in uso.
- La data e l'ora in cui si è verificato l'evento di blocco.
- L'indirizzo IP della richiesta bloccata.
- Il nome host dell'indirizzo IP della richiesta bloccata (se disponibile).
- Il numero di firme innescate dalla richiesta.
- Riferimenti alle firme innescate.
- Riferimenti ai motivi dell'evento di blocco e alcune informazioni di debug di base correlate.
- L'agente utente della richiesta bloccata (cioè, come l'entità richiedente si è identificata nella richiesta).
- Una ricostruzione dell'identificatore per la risorsa originariamente richiesta.
- Lo stato reCAPTCHA per la richiesta corrente (se pertinente).

*Le direttive di configurazione responsabili di questo tipo di registrazione, e per ciascuno dei tre formati disponibili sono:*
- `general` -> `logfile`
- `general` -> `logfileApache`
- `general` -> `logfileSerialized`

Quando queste direttive vengono lasciate vuote, questo tipo di registrazione rimarrà disabilitato.

##### 11.3.1 REGISTRI DI reCAPTCHA

Questo tipo di registrazione è specifico per le istanze reCAPTCHA, e si verifica solo quando un utente tenta di completare un'istanza di reCAPTCHA.

Una voce di registro reCAPTCHA contiene l'indirizzo IP dell'utente che tenta di completare un'istanza di reCAPTCHA, la data e l'ora in cui si è verificato il tentativo, e lo stato reCAPTCHA. Una voce di registro reCAPTCHA in genere assomiglia a qualcosa come questo (ad esempio):

```
Indirizzo IP: x.x.x.x - Date/Time: Day, dd Mon 20xx hh:ii:ss +0000 - Stato reCAPTCHA: Successo!
```

*La direttiva di configurazione responsabile delle registri di reCAPTCHA è:*
- `recaptcha` -> `logfile`

##### 11.3.2 REGISTRI DEL FRONT-END

Questo tipo di registrazione si riferisce ai tenta di accedere al front-end, e si verifica solo quando un utente tenta di accedere al front-end (supponendo che l'accesso front-end sia abilitato).

Una voce di registro front-end contiene l'indirizzo IP dell'utente che tenta di accedere, la data e l'ora in cui si è verificato il tentativo, e i risultati del tentativo (se il tentativo è fallito o è riuscito). Una voce di registro front-end in genere assomiglia a qualcosa come questo (ad esempio):

```
x.x.x.x - Day, dd Mon 20xx hh:ii:ss +0000 - "admin" - Connesso.
```

*La direttiva di configurazione responsabile delle registri di front-end è:*
- `general` -> `FrontEndLog`

##### 11.3.3 ROTAZIONE DEL REGISTRO

Forse vuoi eliminare i log dopo un certo periodo di tempo, o forse sei obbligato a farlo per legge (cioè, la quantità di tempo per cui è legalmente ammissibile per te conservare i log può essere limitata dalla legge). È possibile ottenere ciò includendo indicatori di data/ora nei nomi dei file di log come specificato dalla configurazione del pacchetto (per esempio, `{yyyy}-{mm}-{dd}.log`), e quindi abilitando la rotazione del registro (la rotazione del registro permette di eseguire alcune azioni sui file di log quando vengono superati i limiti specificati).

Per esempio: Se dovessi legalmente richiesto di eliminare i log dopo 30 giorni, potrei specificare `{dd}.log` nei nomi dei miei file di log (`{dd}` rappresenta i giorni), impostare il valore di `log_rotation_limit` su 30, e impostare il valore di `log_rotation_action` su `Delete`.

Al contrario, se è necessario conservare i log per un lungo periodo di tempo, potresti scegliere di non utilizzare la rotazione del registro affatto, oppure puoi impostare il valore di `log_rotation_action` su `Archive`, per comprimere i file di log, riducendo in tal modo la quantità totale di spazio su disco che occupano.

*Direttive di configurazione rilevanti:*
- `general` -> `log_rotation_limit`
- `general` -> `log_rotation_action`

##### 11.3.4 TRONCAMENTO DEL REGISTRO

È anche possibile troncare i singoli file di registro quando superano una dimensione predeterminata, se questo è qualcosa che potrebbe essere necessario o desiderare.

*Direttive di configurazione rilevanti:*
- `general` -> `truncate`

##### 11.3.5 PSEUDONIMIZZAZIONE DELL'INDIRIZZO IP

Innanzitutto, se non hai familiarità con il termine, "pseudonimizzazione" si riferisce al trattamento di dati personali in quanto tali che non può più essere identificato con alcun interessato specifico senza informazioni supplementari, e a condizione che tali informazioni supplementari siano mantenute separatamente e soggette a misure tecniche e organizzative per garantire che i dati personali non possano essere identificati da alcuna persona naturale.

Le seguenti risorse possono aiutare a spiegarlo in modo più dettagliato:
- [[ipsoa.it] Crittografia e pseudonimizzazione nel GDPR](http://www.ipsoa.it/documents/lavoro-e-previdenza/rapporto-di-lavoro/quotidiano/2018/03/17/crittografia-pseudonimizzazione-gdpr)

In alcune circostanze, potrebbe essere richiesto per legge di anonimizzare o pseudonimizzare qualsiasi informazione personale raccolta, elaborata, o memorizzata. Sebbene questo concetto sia esistito già da un po' di tempo, GDPR/DSGVO menziona in particolare, e in particolare incoraggia la "pseudonimizzazione".

CIDRAM è in grado di pseudonimizzare gli indirizzi IP durante la registrazione, se questo è qualcosa che potresti aver bisogno o vuoi fare. Quando gli indirizzi IP sono pseudonimizzati da CIDRAM, quando registrati, l'ottetto finale degli indirizzi IPv4 e tutto ciò che segue la seconda parte degli indirizzi IPv6 è rappresentato da una "x" (arrotondare efficacemente gli indirizzi IPv4 all'indirizzo iniziale della 24a sottorete in cui fanno fattore e gli indirizzi IPv6 all'indirizzo iniziale della 32a sottorete a cui fanno fattore).

*Nota: Il processo di pseudonimizzazione degli indirizzi IP in CIDRAM non influisce sulla funzionalità di tracciamento IP in CIDRAM. Se questo è un problema per te, potrebbe essere meglio disabilitare completamente il tracciamento IP. Questo può essere ottenuto impostando `track_mode` su `false` ed evitando qualsiasi modulo.*

*Direttive di configurazione rilevanti:*
- `signatures` -> `track_mode`
- `legal` -> `pseudonymise_ip_addresses`

##### 11.3.6 OMETTENDO LE INFORMAZIONI DEL REGISTRO

Se si desidera fare un ulteriore passo avanti impedendo che determinati tipi di informazioni vengano registrati interamente, è anche possibile farlo. CIDRAM fornisce direttive di configurazione per controllare se gli indirizzi IP, i nomi host, e gli agenti utente sono inclusi nei registri. Per impostazione predefinita, tutti e tre questi punti dati sono inclusi nei registri, se disponibili. L'impostazione di una qualsiasi di queste direttive di configurazione su `true` ometterà le informazioni corrispondenti dai registri.

*Nota: Non c'è motivo di pseudonimizzare gli indirizzi IP quando li si omette completamente dai log.*

*Direttive di configurazione rilevanti:*
- `legal` -> `omit_ip`
- `legal` -> `omit_hostname`
- `legal` -> `omit_ua`

##### 11.3.7 STATISTICA

CIDRAM è facoltativamente in grado di tracciare statistiche come il numero totale di eventi di blocco o istanze di reCAPTCHA che si sono verificati da un certo punto nel tempo. Questa funzione è disabilitata per impostazione predefinita, ma può essere abilitata tramite la configurazione del pacchetto. Questa funzione traccia solo il numero totale di eventi verificatisi e non include alcuna informazione su eventi specifici (e quindi non dovrebbe essere considerata come PII).

*Direttive di configurazione rilevanti:*
- `general` -> `statistics`

##### 11.3.8 CRITTOGRAFIA

CIDRAM non crittografa la sua cache o alcuna informazione di registro. La [crittografia](https://it.wikipedia.org/wiki/Crittografia) della cache e del registro potrebbe essere introdotta in futuro, ma al momento non sono previsti piani specifici. Se sei preoccupato per le terze parti non autorizzate che accedono a parti di CIDRAM che potrebbero contenere informazioni personali o riservate quali la cache o i registri, ti consiglio di non installare CIDRAM in una posizione accessibile al pubblico (per esempio, installare CIDRAM al di fuori della cartella `public_html` standard o equivalente di quella disponibile per la maggior parte dei server Web standard) e che le autorizzazioni appropriatamente restrittive siano applicate per la cartella in cui risiede (in particolare, per la cartella del vault). Se ciò non è sufficiente per risolvere i tuoi dubbi, allora configura CIDRAM in modo tale che i tipi di informazioni che causano i tuoi dubbi non saranno raccolti o registrati in primo luogo (ad esempio, di disabilitando la registrazione).

#### 11.4 COOKIE

CIDRAM imposta i [cookie](https://it.wikipedia.org/wiki/Cookie) in due punti nella sua base di codice. Innanzitutto, quando un utente completa con successo un'istanza di reCAPTCHA (e supponendo che `lockuser` sia impostato su `true`), CIDRAM imposta un cookie per essere in grado di ricordare per le richieste successive che l'utente ha già completato un'istanza di reCAPTCHA, in modo che non sia necessario chiedere continuamente all'utente di completare un'istanza di reCAPTCHA sulle richieste successive. In secondo luogo, quando un utente accede con successo al front-end, CIDRAM imposta un cookie per poter ricordare all'utente le richieste successive (cioè, i cookie vengono utilizzati per autenticare l'utente in una sessione di accesso).

In entrambi i casi, gli avvertimenti sui cookie vengono visualizzati in modo prominenti (se applicabile), avvisando l'utente che i cookie verranno impostati se si impegnano nell'azioni in questione. I cookie non sono impostati in altri punti della base di codice.

*Nota: La particolare implementazione dell'API "invisible" per reCAPTCHA in CIDRAM potrebbe essere incompatibile con le leggi sui cookie in alcune giurisdizioni, e dovrebbe essere evitato da qualsiasi sito web soggetto a tali leggi. Si potrebbe preferire l'utilizzo dell'API "V2", o semplicemente la disattivazione completa di reCAPTCHA.*

*Direttive di configurazione rilevanti:*
- `general` -> `disable_frontend`
- `recaptcha` -> `lockuser`
- `recaptcha` -> `api`

#### 11.5 MARKETING E PUBBLICITÀ

CIDRAM non raccoglie né elabora alcuna informazione per scopi di marketing o pubblicitari, e non vende né guadagna da alcuna informazione raccolta o registrata. CIDRAM non è un'impresa commerciale, né è collegata ad alcun interesse commerciale, quindi fare queste cose non avrebbe alcun senso. Questo è stato il caso dall'inizio del progetto e continua ad esserlo oggi. Inoltre, fare queste cose sarebbe controproducente per lo spirito e lo scopo del progetto nel suo insieme e, finché continuerò a mantenere il progetto, non accadrà mai.

#### 11.6 POLITICA SULLA PRIVACY

In alcune circostanze, potresti essere legalmente obbligato a mostrare chiaramente un link alla tua politica sulla privacy su tutte le pagine e sezioni del tuo sito web. Questo può essere importante come mezzo per garantire che gli utenti siano ben informati delle tue esatte pratiche sulla privacy, i tipi di Informazioni personali che raccogli, e come intendi usarli. Per poter includere tale link nella pagina "Accesso Negato" di CIDRAM, viene fornita una direttiva di configurazione per specificare l'URL della tua politica sulla privacy.

*Nota: Si raccomanda vivamente che la pagina della politica sulla privacy non sia posizionata dietro la protezione di CIDRAM. Se CIDRAM protegge la tua politica sulla privacy, e un utente bloccato da CIDRAM fa clic sul link alla tua politica sulla privacy, verrà semplicemente bloccato di nuovo e non sarà in grado di vedere la tua politica sulla privacy. Idealmente, dovresti collegare a una copia statica della tua politica sulla privacy, come una pagina HTML o un file di testo semplice che non è protetto da CIDRAM.*

*Direttive di configurazione rilevanti:*
- `legal` -> `privacy_policy`

#### 11.7 GDPR/DSGVO

Il regolamento generale sulla protezione dei dati (GDPR) è un regolamento dell'Unione europea, che entrerà in vigore il 25 maggio 2018. L'obiettivo principale del regolamento è di dare il controllo dei cittadini e dei residenti dell'UE sui propri dati personali, e di unificare il regolamento all'interno dell'UE in materia di privacy e dati personali.

Il regolamento contiene disposizioni specifiche relative al trattamento di "[dati personali](https://it.wikipedia.org/wiki/Dati_personali)" (PII) di qualsiasi "interessato" (qualsiasi persona naturale identificata o identificabile) dall'UE o all'interno dell'UE. Per essere conformi al regolamento, le "imprese" (come definite dal regolamento), e tutti i sistemi e processi rilevanti devono implementare "[privacy by design](https://it.wikipedia.org/wiki/Privacy_by_design)" per impostazione predefinita, deve utilizzare le impostazioni di privacy più alte possibili, deve implementare le necessarie salvaguardie per qualsiasi informazione archiviata o elaborata (inclusa, ma non limitata a, l'implementazione della pseudonimizzazione o la completa anonimizzazione dei dati), deve dichiarare in modo chiaro e inequivocabile i tipi di dati che raccolgono, come li elaborano, per quali ragioni, per quanto tempo lo conservano, e se condividono questi dati con terze parti, i tipi di dati condivisi con terze parti, come, perché, e così via.

I dati non possono essere elaborati a meno che non vi sia una base legale per farlo, come definito dal regolamento. In generale, ciò significa che, al fine di elaborare i dati di un interessati su base legale, deve essere fatto in conformità con gli obblighi legali, o fatto solo dopo il consenso esplicito, ben informato, e non ambiguo è stato ottenuto dall'interessato.

Poiché gli aspetti del regolamento possono evolversi nel tempo, al fine di evitare la propagazione di informazioni obsolete, potrebbe essere meglio conoscere il regolamento da una fonte autorevole, al contrario di includere semplicemente le informazioni rilevanti qui nella documentazione del pacchetto (che potrebbe diventare obsoleto con l'evoluzione del regolamento).

[EUR-Lex](https://eur-lex.europa.eu/) (una parte del sito web ufficiale dell'Unione europea che fornisce informazioni sul diritto dell'UE) fornisce ampie informazioni su GDPR/DSGVO, disponibile in 24 lingue diverse (al momento della stesura di questo documento), e disponibile per il download in formato PDF. Consiglio vivamente di leggere le informazioni che forniscono, per saperne di più su GDPR/DSGVO:
- [REGOLAMENTO (UE) 2016/679 DEL PARLAMENTO EUROPEO E DEL CONSIGLIO](https://eur-lex.europa.eu/legal-content/IT/TXT/?uri=celex:32016R0679)

In alternativa, è disponibile una breve panoramica (non autorevole) di GDPR/DSGVO su Wikipedia:
- [Regolamento generale sulla protezione dei dati](https://it.wikipedia.org/wiki/Regolamento_generale_sulla_protezione_dei_dati)

---


Ultimo Aggiornamento: 2 Settembre 2018 (2018.09.02).
