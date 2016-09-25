## Documentazione per CIDRAM (Italiano).

### Contenuti
- 1. [PREAMBOLO](#SECTION1)
- 2. [COME INSTALLARE](#SECTION2)
- 3. [COME USARE](#SECTION3)
- 4. [FILE INCLUSI IN QUESTO PACCHETTO](#SECTION4)
- 5. [OPZIONI DI CONFIGURAZIONE](#SECTION5)
- 6. [FIRMA FORMATO](#SECTION6)
- 7. [DOMANDE FREQUENTI (FAQ)](#SECTION7)

---


###1. <a name="SECTION1"></a>PREAMBOLO

CIDRAM (Classless Inter-Domain Routing Access Manager) è uno script PHP progettato per proteggere i siti web da bloccando le richieste provenienti da indirizzi IP considerati come fonti di traffico indesiderato, includendo (ma non limitato a) il traffico proveniente da punto d'accesso non umani, servizi cloud, spambots, raschietti/scrapers, ecc. Questo è fatto da calcolando le possibili CIDR degli indirizzi IP forniti da richieste in entrata e poi confrontando questi possibili CIDR contro i suoi file di firme (queste file di firme contengono liste di CIDR di indirizzi IP considerati come fonti di traffico indesiderato); Se le partite sono trovati, le richieste sono bloccate.

CIDRAM COPYRIGHT 2016 e oltre GNU/GPLv2 Caleb M (Maikuolan).

Questo script è libero software; è possibile ridistribuirlo e/o modificarlo sotto i termini della GNU General Public License come pubblicato dalla Free Software Foundation; o la versione 2 della licenza, o (a propria scelta) una versione successiva. Questo script è distribuito nella speranza che possa essere utile, Ma SENZA ALCUNA GARANZIA; senza neppure la implicita garanzia di COMMERCIABILITÀ o IDONEITÀ PER UN PARTICOLARE SCOPO. Vedere la GNU General Public License per ulteriori dettagli, situato nella `LICENSE.txt` file e disponibili anche da:
- <http://www.gnu.org/licenses/>.
- <http://opensource.org/licenses/>.

Questo documento ed il pacchetto associtato ad esso possono essere scaricati liberamente da [Github](https://github.com/Maikuolan/CIDRAM/).

---


###2. <a name="SECTION2"></a>COME INSTALLARE

Spero di semplificare questo processo tramite un installatore ad un certo punto in un futuro non troppo lontano, Ma fino ad allora, seguire queste istruzioni per avere CIDRAM funzionale sulla maggior parte dei sistemi e CMS:

1) Con la vostra lettura di questo, sto supponendo che hai già scaricato una archiviata copia dello script, decompresso il contenuto e lo hanno seduto da qualche parte sul suo locale macchina. Da qui, ti consigliamo di determinare dove sulla macchina o CMS si desidera inserire quei contenuti. Una cartella come `/public_html/cidram/` o simile (però, è non importa quale si sceglie, purché sia qualcosa sicuro e qualcosa si è soddisfatti) sarà sufficiente. *Prima di iniziare il caricamento, continua a leggere..*

2) Rinomina `config.ini.RenameMe` a `config.ini` (situato della `vault`), e facoltativamente (fortemente consigliata per gli avanzati utenti, Ma non è consigliata per i principianti o per gli inesperti), aprirlo (questo file contiene tutte le direttive disponibili per CIDRAM; sopra ogni opzione dovrebbe essere un breve commento che descrive ciò che fa e ciò che è per). Regolare queste opzioni come meglio credi, come per ciò che è appropriato per la vostre particolare configurazione. Salvare il file, chiudere.

3) Carica i contenuti (CIDRAM e le sue file) nella cartella che ci deciso in precedenza (non è necessario includere i `*.txt`/`*.md` file, ma altrimenti, si dovrebbe caricare tutto).

4) CHMOD la cartella `vault` a "755" (se ci sono problemi, si può provare "777", ma questo è meno sicura). La principale cartella che memorizzare il contenuti (quello scelto in precedenza), solitamente, può essere lasciato solo, Ma lo CHMOD stato dovrebbe essere controllato se hai avuto problemi di autorizzazioni in passato sul vostro sistema (per predefinita, dovrebbe essere qualcosa simile a "755").

5) Successivamente, sarà necessario collegare CIDRAM al vostro sistema o CMS. Ci sono diversi modi in cui è possibile collegare script come CIDRAM al vostre sistema o CMS, Ma il più semplice è di inserire lo script all'inizio di un file del vostre sistema o CMS (quello che sarà generalmente sempre essere caricato quando qualcuno accede a una pagina attraverso il vostro sito) utilizzando un `require` o `include` comando. Solitamente, questo sarà qualcosa memorizzate in una cartella, ad esempio `/includes`, `/assets` o `/functions`, e spesso essere chiamato qualcosa come `init.php`, `common_functions.php`, `functions.php` o simili. Avrete bisogno determinare quale file è per la vostra situazione; In caso di difficoltà nel determinare questo per te, per assistenza, visitare la pagina di problemi/issues per CIDRAM. Per fare questo [utilizzare `require` o `include`], inserire la seguente riga di codice all'inizio di quel core file, sostituendo la stringa contenuta all'interno delle virgolette con l'indirizzo esatto del file `loader.php` (l'indirizzo locale, non l'indirizzo HTTP; sarà simile all'indirizzo citato in precedenza).

`<?php require '/user_name/public_html/cidram/loader.php'; ?>`

Salvare il file, chiudere, caricare di nuovo.

-- IN ALTERNATIVA --

Se stai usando un Apache web server e se si ha accesso a `php.ini`, è possibile utilizzare il `auto_prepend_file` direttiva per precarico CIDRAM ogni volta che qualsiasi richiesta di PHP è fatto. Qualcosa come:

`auto_prepend_file = "/user_name/public_html/cidram/loader.php"`

O questo nel `.htaccess` file:

`php_value auto_prepend_file "/user_name/public_html/cidram/loader.php"`

6) Questo è tutto! :-)

---


###3. <a name="SECTION3"></a>COME USARE

CIDRAM dovrebbe bloccare automaticamente le richieste indesiderate al suo sito senza richiedendo alcun assistenza manuale, a parte la sua installazione iniziale.

L'aggiornamento avviene manualmente, ed è possibile personalizzare il suo configurazione e personalizzare i quali CIDR sono bloccati da modificando il suo file di configurazione e/o file di firme.

Se si incontrano qualsiasi falsi positivi, si prega di contattare me per farmi sapere su di esso.

---


###4. <a name="SECTION4"></a>FILE INCLUSI IN QUESTO PACCHETTO

Il seguente è un elenco di tutti i file che dovrebbero essere incluso nella archiviato copia di questo script quando si scaricalo, qualsiasi di file che potrebbero potenzialmente essere creato come risultato della vostra utilizzando questo script, insieme con una breve descrizione di ciò che tutti questi file sono per.

File | Descrizione
----|----
/.gitattributes | Un file del Github progetto (non richiesto per il corretto funzionamento dello script).
/Changelog.txt | Un record delle modifiche apportate allo script tra diverse versioni (non richiesto per il corretto funzionamento dello script).
/composer.json | Composer/Packagist informazioni (non richiesto per il corretto funzionamento dello script).
/LICENSE.txt | Una copia della GNU/GPLv2 licenza (non richiesto per il corretto funzionamento dello script).
/loader.php | Caricatore/Loader. Questo è il file si collegare alla vostra sistema (essenziale)!
/README.md | Informazioni di riepilogo del progetto.
/web.config | Un ASP.NET file di configurazione (in questo caso, a proteggere la `/vault` cartella da l'acceso di non autorizzate origini nel caso che lo script è installato su un server basata su ASP.NET tecnologie).
/_docs/ | Documentazione cartella (contiene vari file).
/_docs/readme.ar.md | Documentazione Arabo.
/_docs/readme.de.md | Documentazione Tedesco.
/_docs/readme.en.md | Documentazione Inglese.
/_docs/readme.es.md | Documentazione Spagnolo.
/_docs/readme.fr.md | Documentazione Francese.
/_docs/readme.id.md | Documentazione Indonesiano.
/_docs/readme.it.md | Documentazione Italiano.
/_docs/readme.ja.md | Documentazione Giapponese.
/_docs/readme.nl.md | Documentazione Olandese.
/_docs/readme.pt.md | Documentazione Portoghese.
/_docs/readme.ru.md | Documentazione Russo.
/_docs/readme.vi.md | Documentazione Vietnamita.
/_docs/readme.zh-TW.md | Documentazione Cinese (tradizionale).
/_docs/readme.zh.md | Documentazione Cinese (semplificato).
/vault/ | La vault cartella (contiene vari file).
/vault/.htaccess | Un ipertesto accesso file (in questo caso, a proteggere di riservati file appartenente allo script da l'acceso di non autorizzate origini).
/vault/cache.dat | Cache data.
/vault/cli.php | Gestore di CLI.
/vault/config.ini.RenameMe | File di configurazione; Contiene tutte l'opzioni di configurazione per CIDRAM, dicendogli cosa fare e come operare correttamente (rinomina per attivare).
/vault/config.php | Gestore di configurazione.
/vault/functions.php | File di funzioni.
/vault/hashes.dat | Contiene una lista di hash accettati (pertinente alla funzione di reCAPTCHA; solo generato se la funzione di reCAPTCHA è abilitato).
/vault/ignore.dat | File ignorati (utilizzato per specificare quali sezioni firma CIDRAM dovrebbe ignorare).
/vault/ipbypass.dat | Contiene un elenco di bypass IP (pertinente alla funzione di reCAPTCHA; solo generato se la funzione di reCAPTCHA è abilitato).
/vault/ipv4.dat | File di firme per IPv4.
/vault/ipv4_custom.dat.RenameMe | File di firme per IPv4 personalizzato (rinomina per attivare).
/vault/ipv6.dat | File di firme per IPv6.
/vault/ipv6_custom.dat.RenameMe | File di firme per IPv6 personalizzato (rinomina per attivare).
/vault/lang.php | Linguistici dati.
/vault/lang/ | Contiene linguistici dati.
/vault/lang/.htaccess | Un ipertesto accesso file (in questo caso, a proteggere di riservati file appartenente allo script da l'acceso di non autorizzate origini).
/vault/lang/lang.ar.cli.php | Linguistici dati Araba per CLI.
/vault/lang/lang.ar.php | Linguistici dati Araba.
/vault/lang/lang.de.cli.php | Linguistici dati Tedesca per CLI.
/vault/lang/lang.de.php | Linguistici dati Tedesca.
/vault/lang/lang.en.cli.php | Linguistici dati Inglese per CLI.
/vault/lang/lang.en.php | Linguistici dati Inglese.
/vault/lang/lang.es.cli.php | Linguistici dati Spagnola per CLI.
/vault/lang/lang.es.php | Linguistici dati Spagnola.
/vault/lang/lang.fr.cli.php | Linguistici dati Francese per CLI.
/vault/lang/lang.fr.php | Linguistici dati Francese.
/vault/lang/lang.id.cli.php | Linguistici dati Indonesiana per CLI.
/vault/lang/lang.id.php | Linguistici dati Indonesiana.
/vault/lang/lang.it.cli.php | Linguistici dati Italiana per CLI.
/vault/lang/lang.it.php | Linguistici dati Italiana.
/vault/lang/lang.ja.cli.php | Linguistici dati Giapponese per CLI.
/vault/lang/lang.ja.php | Linguistici dati Giapponese.
/vault/lang/lang.nl.cli.php | Linguistici dati Olandese per CLI.
/vault/lang/lang.nl.php | Linguistici dati Olandese.
/vault/lang/lang.pt.cli.php | Linguistici dati Portoghese per CLI.
/vault/lang/lang.pt.php | Linguistici dati Portoghese.
/vault/lang/lang.ru.cli.php | Linguistici dati Russa per CLI.
/vault/lang/lang.ru.php | Linguistici dati Russa.
/vault/lang/lang.vi.cli.php | Linguistici dati Vietnamita per CLI.
/vault/lang/lang.vi.php | Linguistici dati Vietnamita.
/vault/lang/lang.zh-tw.cli.php | Linguistici dati Cinese (tradizionale) per CLI.
/vault/lang/lang.zh-tw.php | Linguistici dati Cinese (tradizionale).
/vault/lang/lang.zh.cli.php | Linguistici dati Cinese (semplificata) per CLI.
/vault/lang/lang.zh.php | Linguistici dati Cinese (semplificata).
/vault/outgen.php | Generatore di output.
/vault/recaptcha.php | Modulo reCAPTCHA.
/vault/rules_as6939.php | File di regole personalizzate per AS6939.
/vault/rules_softlayer.php | File di regole personalizzate per Soft Layer.
/vault/rules_specific.php | File di regole personalizzate per alcune CIDR specifiche.
/vault/salt.dat | File di salt (usato da alcune funzionalità periferica; solo generato se richiesto).
/vault/template.html | File di modello; Modello per l'output HTML prodotto dal generatore di output per CIDRAM.
/vault/template_custom.html | File di modello; Modello per l'output HTML prodotto dal generatore di output per CIDRAM.

---


###5. <a name="SECTION5"></a>OPZIONI DI CONFIGURAZIONE
Il seguente è un elenco di variabili trovate nelle `config.ini` file di configurazione di CIDRAM, insieme con una descrizione del loro scopo e funzione.

####"general" (Categoria)
Generale configurazione per CIDRAM.

"logfile"
- Un file leggibile dagli umani per la registrazione di tutti i tentativi di accesso bloccati. Specificare un nome di file, o lasciare vuoto per disabilitare.

"logfileApache"
- Un file nello stile di apache per la registrazione di tutti i tentativi di accesso bloccati. Specificare un nome di file, o lasciare vuoto per disabilitare.

"logfileSerialized"
- Un file serializzato per la registrazione di tutti i tentativi di accesso bloccati. Specificare un nome di file, o lasciare vuoto per disabilitare.

*Consiglio utile: Se vuoi, è possibile aggiungere data/ora informazioni per i nomi dei file per la registrazione par includendo queste nel nome: `{yyyy}` per l'anno completo, `{yy}` per l'anno abbreviato, `{mm}` per mese, `{dd}` per giorno, `{hh}` per ora.*

*Esempi:*
- *`logfile='logfile.{yyyy}-{mm}-{dd}-{hh}.txt'`*
- *`logfileApache='access.{yyyy}-{mm}-{dd}-{hh}.txt'`*
- *`logfileSerialized='serial.{yyyy}-{mm}-{dd}-{hh}.txt'`*

"timeOffset"
- Se il tempo del server non corrisponde l'ora locale, è possibile specificare un offset qui per regolare le informazioni di data/tempo generato da CIDRAM in base alle proprie esigenze. È generalmente raccomandato invece, regolare à la direttiva fuso orario nel file `php.ini`, ma a volte (come ad esempio quando si lavora con i fornitori di hosting condiviso limitati) questo non è sempre possibile fare, e così, questa opzione è fornito qui. Offset è in minuti.
- Esempio (per aggiungere un'ora): `timeOffset=60`

"ipaddr"
- Dove trovare l'indirizzo IP di collegamento richiesta? (Utile per servizi come Cloudflare e simili) Predefinito = REMOTE_ADDR. AVVISO: Non modificare questa se non sai quello che stai facendo!

"forbid_on_block"
- Quale intestazioni dovrebbe CIDRAM rispondere con quando bloccano le richieste? False/200 = 200 OK [Predefinito]; True = 403 Forbidden (Proibito); 503 = 503 Service unavailable (Servizio non disponibile).

"silent_mode"
- CIDRAM dovrebbe reindirizzare silenziosamente tutti i tentativi di accesso bloccati invece di visualizzare la pagina "Accesso Negato"? Se si, specificare la localizzazione di reindirizzare i tentativi di accesso bloccati. Se no, lasciare questo variabile vuoto.

"lang"
- Specifica la lingua predefinita per CIDRAM.

"emailaddr"
- Se si desidera, è possibile fornire un indirizzo email qui a dare utenti quando sono bloccati, per loro di utilizzare come punto di contatto per supporto e/o assistenza per il caso di che vengano bloccate per errore. AVVERTIMENTO: Qualunque sia l'indirizzo email si fornisce qui sarà certamente acquisito dal spambots e raschietti/scrapers nel corso del suo essere usato qui, e così, è fortemente raccomandato che se si sceglie di fornire un indirizzo email qui, che si assicurare che l'indirizzo email si fornisce qui è un indirizzo monouso e/o un indirizzo che si non ti dispiace essere spammato (in altre parole, probabilmente si non vuole usare il personale primaria o commerciale primaria indirizzi email).

"disable_cli"
- Disabilita CLI? Modalità CLI è abilitato per predefinito, ma a volte può interferire con alcuni strumenti di test (come PHPUnit, per esempio) e altre applicazioni basate su CLI. Se non è necessario disattivare la modalità CLI, si dovrebbe ignorare questa direttiva. False = Abilita CLI [Predefinito]; True = Disabilita CLI.

####"signatures" (Categoria)
Configurazione per firme.

"ipv4"
- Un elenco dei file di firma IPv4 che CIDRAM dovrebbe tentare di utilizzare, delimitati da virgole. È possibile aggiungere voci qui se si desidera includere ulteriori file di firma IPv4 per CIDRAM.

"ipv6"
- Un elenco dei file di firma IPv6 che CIDRAM dovrebbe tentare di utilizzare, delimitati da virgole. È possibile aggiungere voci qui se si desidera includere ulteriori file di firma IPv6 per CIDRAM.

"block_cloud"
- Bloccare CIDRs identificato come appartenente alla servizi webhosting/cloud? Se si utilizza un servizio di API dal suo sito o se si aspetta altri siti a collegare al suo sito, questa direttiva deve essere impostata su false. Se non, questa direttiva deve essere impostata su true.

"block_bogons"
- Bloccare bogone/marziano CIDRs? Se aspetta i collegamenti al suo sito dall'interno della rete locale, da localhost, o dalla LAN, questa direttiva deve essere impostata su false. Se si non aspetta queste tali connessioni, questa direttiva deve essere impostata su true.

"block_generic"
- Bloccare CIDRs generalmente consigliato per la lista nera? Questo copre qualsiasi firme che non sono contrassegnate come parte del qualsiasi delle altre più specifiche categorie di firme.

"block_proxies"
- Bloccare CIDRs identificato come appartenente alla servizi proxy? Se si richiede che gli utenti siano in grado di accedere al suo sito web dai servizi di proxy anonimi, questa direttiva deve essere impostata su false. Altrimenti, se non si richiede proxy anonimi, questa direttiva deve essere impostata su true come un mezzo per migliorare la sicurezza.

"block_spam"
- Bloccare CIDRs identificati come alto rischio per spam? A meno che si sperimentare problemi quando si fa così, generalmente, questo dovrebbe essere sempre impostata su true.

####"recaptcha" (Categoria)
Se vuoi, è possibile fornire agli utenti un modo per bypassare la pagina di "Accesso Negato" attraverso il completamento di un'istanza di reCAPTCHA. Questo può aiutare a mitigare alcuni dei rischi associati con i falsi positivi in quelle situazioni in cui non siamo del tutto sicuri se una richiesta ha avuto origine da una macchina o di un essere umano.

A causa dei rischi connessi con fornendo un modo per gli utenti di bypassare la pagina di "Accesso Negato", generalmente, vorrei consigliare contro l'attivazione di questa funzione a meno che si sente che sia necessario farlo. Situazioni in cui sarebbe necessario: Se il vostro sito ha clienti/utenti che hanno bisogno di avere accesso al vostro sito web, e se questo è qualcosa che non può essere compromessa sulla, ma se quei clienti/utenti capita di essere di collegamento da una rete ostile che potenzialmente potrebbero essere anche trasportare il traffico indesiderato, e bloccando il traffico indesiderato è anche qualcosa che non può essere compromessa sulla, in quelle particolari situazioni senza possibilità di vittoria, la funzione di reCAPTCHA potrebbe rivelarsi utile come mezzo di permettere ai clienti/utenti desiderabili, mentre tenendo fuori il traffico indesiderato dalla stessa rete. Detto questo, però, dato che la destinazione di un CAPTCHA è quello di distinguere tra esseri umani e non-umani, la funzione di reCAPTCHA aiuterebbe solo in queste situazioni senza possibilità di vittoria se vogliamo supporre che questo traffico indesiderato è non-umano (per esempio, spambots, raschietti, incidere strumenti, traffico automatizzato), invece di essere il traffico umano indesiderato (come ad esempio gli spammer umani, hackers, e altri).

Per ottenere una "site key" e una "secret key" (necessaria per l'utilizzo di reCAPTCHA), vai al: [https://developers.google.com/recaptcha/](https://developers.google.com/recaptcha/)

"usemode"
- Definisce come CIDRAM dovrebbe usare reCAPTCHA.
- 0 = reCAPTCHA è completamente disabilitata (predefinito).
- 1 = reCAPTCHA è abilitato per tutte le firme.
- 2 = reCAPTCHA è abilitata solo per le firme appartenenti alle sezioni appositamente contrassegnati come reCAPTCHA abilitati all'interno dei file di firma.
- (Qualsiasi altro valore sarà trattata nello stesso modo come 0).

"lockip"
- Specifica se hash dovrebbero essere obbligati a specifici indirizzi IP. False = I cookie e gli hash POSSONO essere utilizzati di più indirizzi IP (predefinito). True = I cookie e gli hash NON possono essere utilizzati di più indirizzi IP (cookie/hash sono obbligati a l'IP).
- Nota: Il valore di "lockip" viene ignorato quando "lockuser" è false, a causa che il meccanismo per ricordare "utenti" differisce a seconda di questo valore.

"lockuser"
- Specifica se il completamento di un'istanza di reCAPTCHA deve essere obbligati a utenti specifici. False = Il completamento con successo di un'istanza di reCAPTCHA concederà l'accesso a tutte le richieste provenienti dallo stesso IP come quello utilizzato dall'utente completando l'istanza di reCAPTCHA; I cookie e gli hash non sono utilizzati; Invece, un IP whitelist verrà utilizzata. True = Il completamento con successo di un'istanza di reCAPTCHA sarà solo concedere l'accesso all'utente completando l'istanza di reCAPTCHA; I cookie e gli hash vengono utilizzati per ricordare all'utente; Un IP whitelist non viene utilizzato (predefinito).

"sitekey"
- Questo valore deve corrispondere alla "site key" per il vostro reCAPTCHA, che può essere trovato all'interno del cruscotto di reCAPTCHA.

"secret"
- Questo valore deve corrispondere alla "secret key" per il vostro reCAPTCHA, che può essere trovato all'interno del cruscotto di reCAPTCHA.

"expiry"
- Quando "lockuser" è true (predefinito), al fine di ricordare quando un utente ha superato con successo un'istanza di reCAPTCHA, per richieste di pagina futuri, CIDRAM genera un cookie HTTP standard contenente un hash che corrisponde ad un record interno contenente lo stesso hash; Future richieste per pagine utilizzerà questi hash corrispondenti per autenticare che un utente ha precedentemente già superato un'istanza di reCAPTCHA. Quando "lockuser" è false, un IP whitelist viene utilizzato per stabilire se le richieste dovrebbero essere autorizzate dall'IP di richieste in entrata; IP sono aggiunti a questa whitelist quando l'istanza di reCAPTCHA è superato con successo. Per quante ore dovrebbe questi cookies, hash e gli articoli della whitelist rimane valida? Predefinito = 720 (1 mese).

"logfile"
- Registrare tutti i tentativi per reCAPTCHA? Se sì, specificare il nome da usare per il file di registrazione. Se non, lasciare questo variabile vuoto.

*Consiglio utile: Se vuoi, è possibile aggiungere data/ora informazioni per i nomi dei file per la registrazione par includendo queste nel nome: `{yyyy}` per l'anno completo, `{yy}` per l'anno abbreviato, `{mm}` per mese, `{dd}` per giorno, `{hh}` per ora.*

*Esempi:*
- *`logfile='recaptcha.{yyyy}-{mm}-{dd}-{hh}.txt'`*

####"template_data" (Categoria)
Direttive/Variabili per modelli e temi.

Si riferisce al HTML utilizzato per generare la pagina "Accesso Negato". Se stai usando temi personalizzati per CIDRAM, prodotti HTML è provenienti da file `template_custom.html`, e altrimenti, prodotti HTML è provenienti da file `template.html`. Variabili scritte a questa sezione del file di configurazione sono parsato per il prodotti HTML per mezzo di sostituendo tutti i nomi di variabili circondati da parentesi graffe trovato all'interno il prodotti HTML con la corrispondente dati di quelli variabili. Per esempio, dove `foo="bar"`, qualsiasi istanza di `<p>{foo}</p>` trovato all'interno il prodotti HTML diventerà `<p>bar</p>`.

"css_url"
- Il modello file per i temi personalizzati utilizzi esterni CSS proprietà, mentre il modello file per i temi personalizzati utilizzi interni CSS proprietà. Per istruire CIDRAM di utilizzare il modello file per i temi personalizzati, specificare l'indirizzo pubblico HTTP dei CSS file dei suoi tema personalizzato utilizzando la variabile `css_url`. Se si lascia questo variabile come vuoto, CIDRAM utilizzerà il modello file per il predefinito tema.

---


###6. <a name="SECTION6"></a>FIRMA FORMATO

####6.0 NOZIONI DI BASE

Una descrizione del formato e la struttura delle firme utilizzate da CIDRAM può essere trovato documentato in testo semplice entro una delle due file di firma personalizzati. Si prega di fare riferimento a tale documentazione per saperne di più sul formato e la struttura delle firme di CIDRAM.

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

Le spiegazioni pre-preparati hanno il supporto i18n e può essere tradotto dallo script in base alla lingua specificata alla direttiva di configurazione dello script, `lang`. Inoltre, è possibile indicare lo script di ignorare le firme "Deny" in base al loro valore di `[Param]` (se si sta utilizzando queste brevi parole) tramite le direttive specificata dalla configurazione dello script (ogni parola breve ha un corrispondente direttiva al elaborare le firme corrispondenti o di ignorarle). I valori di `[Param]` che non utilizzare questi brevi parole, però, non hanno il supporto i18n e quindi NON sarà tradotto dallo script, e inoltre, non sono controllabili direttamente dalla configurazione dello script.

Le parole brevi disponibili sono:
- Bogon
- Cloud
- Generic
- Proxy
- Spam

####6.1 ETICHETTE

Se si desidera dividere le vostre firme personalizzate in singole sezioni, è possibile identificare queste singole sezioni per lo script per aggiungendo un "etichetta sezione" subito dopo le firme di ogni sezione, insieme con il nome della sezione di firme (vedere l'esempio cui seguito).

```
# "Section 1."
1.2.3.4/32 Deny Bogon
2.3.4.5/32 Deny Cloud
4.5.6.7/32 Deny Generic
5.6.7.8/32 Deny Spam
6.7.8.9/32 Deny Proxy
Tag: Section 1
```

Per rompere l'etichetta della sezione e per assicurare che l'etichetta non sono identificati erroneamente alle sezioni di firme da prima nelle file di firme, semplicemente assicurare che ci sono almeno due interruzioni di riga consecutivi tra l'etichetta e le sezioni di firme precedenti. Qualsiasi firme senza un'etichetta saranno etichettato come "IPv4" o "IPv6" per predefinito (dipendente sui quali tipi di firme vengono attivati).

```
1.2.3.4/32 Deny Bogon
2.3.4.5/32 Deny Cloud

4.5.6.7/32 Deny Generic
5.6.7.8/32 Deny Spam
Tag: Section 1
```

Nell'esempio sopra `1.2.3.4/32` e `2.3.4.5/32` saranno etichettato come "IPv4", mentre `4.5.6.7/32` e `5.6.7.8/32` saranno etichettato come "Section 1".

Se si desidera firme per scadono dopo un certo tempo, in modo analogo all'etichette sezione, è possibile utilizzare un "etichetta scadenza" per specificare quando le firme dovrebbero cessano di essere validi. Etichette scadenza usano il formato "AAAA.MM.GG" (vedere l'esempio cui seguito).

```
# "Section 1."
1.2.3.4/32 Deny Generic
2.3.4.5/32 Deny Generic
Expires: 2016.12.31
```

Etichette sezione ed etichette scadenza possono essere utilizzati insieme, ed entrambi sono opzionali (vedere l'esempio cui seguito).

```
# "Example Section."
1.2.3.4/32 Deny Generic
Tag: Example Section
Expires: 2016.12.31
```

####6.2 YAML

#####6.2.0 YAML BASI

Una forma semplificata di YAML markup può essere utilizzato in file di firma al fine di definire comportamenti e le impostazioni specifiche per singole sezioni di firma. Questo può essere utile se si desidera che il valore delle vostre direttive di configurazione di differire sulla base delle singole firme e sezioni di firma (per esempio; se si desidera fornire un indirizzo e-mail per i biglietti di supporto per tutti gli utenti bloccati da una firma particolare, ma non desidera fornire un indirizzo e-mail per i biglietti di supporto per utenti bloccati con qualsiasi altro firme; se si desidera che alcune firme specifiche per innescare una reindirizzamento di pagina; se si desidera contrassegnare una sezione di firma per l'utilizzo con reCAPTCHA; se si desidera registrare i tentativi di accesso bloccati in file separati sulla base delle singole firme e/o sezioni di firma).

L'utilizzo di YAML markup nei file di firma è del tutto facoltativo (cioè, si può usare se si desidera farlo, ma non è richiesto a farlo), ed è in grado di sfruttare la maggior parte (ma non tutto) delle direttive di configurazione.

Nota: Implementazione di YAML markup in CIDRAM è molto semplice e molto limitato; Esso è destinato a soddisfare i requisiti specifici per CIDRAM in modo che ha la familiarità di YAML markup, ma non segue né è conforme alle specifiche tecniche (e quindi non si comporterà nello stesso modo come implementazioni più approfonditi altrove, e potrebbe non essere appropriato per altri progetti altrove).

In CIDRAM, segmenti di YAML markup vengono identificati allo script da tre trattini ("---"), e terminare al fianco dei loro contenenti sezioni di firma mediante due interruzioni di riga. Un tipico segmento di YAML markup all'interno di una sezione di firme consiste di tre trattini su una riga subito dopo l'elenco dei CIDRs e qualsiasi etichette, seguito da una lista bidimensionale delle chiave-valore coppie (prima dimensione, categorie di direttive di configurazione; seconda dimensione, direttive di configurazione) per i quali direttive di configurazione devono essere modificati (e in cui valori) ogniqualvolta una firma all'interno di tale sezione di firme viene innescato (vedere gli esempi qui sotto).

```
# "Foobar 1."
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

# "Foobar 2."
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

# "Foobar 3."
1.2.3.4/32 Deny Generic
2.3.4.5/32 Deny Generic
4.5.6.7/32 Deny Generic
Tag: Foobar 3
---
general:
 forbid_on_block: 403
 silent_mode: "http://127.0.0.1/"
```

#####6.2.1 COME "APPOSITAMENTE CONTRASSEGNARE" SEZIONI DI FIRMA PER L'UTILIZZO CON reCAPTCHA

Quando "usemode" è 0 o 1, sezioni di firma non hanno bisogno di essere "appositamente contrassegnato" per l'utilizzo con reCAPTCHA (perché già userà o non userà reCAPTCHA, (dipende da questa impostazione).

Quando "usemode" è 2, a "appositamente contrassegnare" sezioni di firma per l'utilizzo con, una voce è incluso nel segmento di YAML per tale sezione di firme (vedere l'esempio cui seguito).

```
# This section will use reCAPTCHA.
1.2.3.4/32 Deny Generic
2.3.4.5/32 Deny Generic
Tag: reCAPTCHA-Enabled
---
recaptcha:
 enabled: true
```

Nota: Un istanza di reCAPTCHA sarà solo essere offerto all'utente se reCAPTCHA è attivato (sia con "usemode" come 1, o "usemode" come 2 con "enabled" come true), e se esattamente UN firma è stato attivato (né più, né meno; se più firme sono attivati, un'istanza di reCAPTCHA NON sarà offerto).

####6.3 AUSILIARIO

In aggiunta, se si desidera CIDRAM di ignorare completamente alcune sezioni specifiche in qualsiasi una delle file di firma, è possibile utilizzare il file `ignore.dat` per specificare quali sezioni a ignorare. In una nuova riga, scivere `Ignore`, seguito da uno spazio, seguito dal nome della sezione che si desidera CIDRAM a ignorare (vedere l'esempio cui seguito).

```
Ignore Section 1
```

Fare riferimento ai file di firme personalizzati per ulteriori informazioni.

---


###7. <a name="SECTION7"></a>DOMANDE FREQUENTI (FAQ)

####Che cosa è un "falso positivo"?

Il termine "falso positivo" (*in alternativa: "errore di falso positivo"; "falso allarme"*; Inglese: *false positive*; *false positive error*; *false alarm*), descritto molto semplicemente, e in un contesto generalizzato, viene utilizzato quando si analizza una condizione, per riferirsi ai risultati di tale analisi, quando i risultati sono positivi (cioè, la condizione è determinata a essere "positivo", o "vero"), ma dovrebbero essere (o avrebbe dovuto essere) negativo (cioè, la condizione, in realtà, è "negativo", o "falso"). Un "falso positivo" potrebbe essere considerato analogo a "piangendo lupo" (dove la condizione di essere analizzato è se c'è un lupo nei pressi della mandria, la condizione è "falso" in che non c'è nessun lupo nei pressi della mandria, e la condizione viene segnalato come "positivo" dal pastore per mezzo di chiamando "lupo, lupo"), o analogo a situazioni di test medici dove un paziente viene diagnosticato una malattia, quando in realtà, non hanno qualsiasi malattia.

Risultati correlati quando si analizza una condizione può essere descritto utilizzando i termini "vero positivo", "vero negativo" e "falso negativo". Un "vero positivo" si riferisce a quando i risultati dell'analisi e lo stato attuale della condizione sono entrambi vero (o "positivo"), e un "vero negativo" si riferisce a quando i risultati dell'analisi e lo stato attuale della condizione sono entrambe falso (o "negativo"); Un "vero positivo" o un "vero negativo" è considerato una "inferenza corretta". L'antitesi di un "falso positivo" è un "falso negativo"; Un "falso negativo" si riferisce a quando i risultati dell'analisi sono negativo (cioè, la condizione è determinata a essere "negativo", o "falso"), ma dovrebbero essere (o avrebbe dovuto essere) positivo (cioè, la condizione, in realtà, è "positivo", or "vero").

Nel contesto di CIDRAM, questi termini si riferiscono alle firme di CIDRAM e che cosa/chi bloccano. Quando CIDRAM si blocca un indirizzo IP a causa di firme male, obsoleti o errati, ma non avrebbe dovuto fare così, o quando lo fa per le ragioni sbagliate, ci riferiamo a questo evento come un "falso positivo". Quando CIDRAM non riesce a bloccare un indirizzo IP che avrebbe dovuto essere bloccato, a causa delle minacce impreviste, firme mancante o carenze nelle sue firme, ci riferiamo a questo evento come una "rivelazione mancante" o "missed detection" (che è analoga ad un "falso negativo").

Questo può essere riassunta dalla seguente tabella:

&nbsp; | CIDRAM *NON* dovrebbe bloccare un indirizzo IP | CIDRAM *DOVREBBE* bloccare un indirizzo IP
---|---|---
CIDRAM *NON* bloccare un indirizzo IP | Vero negativo (inferenza corretta) | Rivelazione mancante (analogous to falso negativo)
CIDRAM *FA* bloccare un indirizzo IP | __Falso positivo__ | Vero positivo (inferenza corretta)

---


Ultimo Aggiornamento: 25 Settembre 2016 (2016.09.25).
