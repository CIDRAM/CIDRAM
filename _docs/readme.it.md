## Documentazione per CIDRAM (Italiano).

### Contenuti
- 1. [PREAMBOLO](#SECTION1)
- 2. [COME INSTALLARE](#SECTION2)
- 3. [COME USARE](#SECTION3)
- 4. [FILE INCLUSI IN QUESTO PACCHETTO](#SECTION4)
- 5. [OPZIONI DI CONFIGURAZIONE](#SECTION5)
- 6. [FIRMA FORMATO](#SECTION6)

---


###1. <a name="SECTION1"></a>PREAMBOLO

CIDRAM (Classless Inter-Domain Routing Access Manager) è uno script PHP progettato per proteggere i siti web da bloccando le richieste provenienti da indirizzi IP considerati come fonti di traffico indesiderato, includendo (ma non limitato a) il traffico proveniente da punto d'accesso non umani, servizi cloud, spambots, raschietti/scrapers, ecc. Questo è fatto da calcolando le possibili CIDR degli indirizzi IP forniti da richieste in entrata e poi confrontando questi possibili CIDR contro i suoi file di firme (queste file di firme contengono liste di CIDR di indirizzi IP considerati come fonti di traffico indesiderato); Se le partite sono trovati, le richieste sono bloccate.

CIDRAM COPYRIGHT 2016 e oltre GNU/GPLv2 Caleb M (Maikuolan).

Questo script è libero software; è possibile ridistribuirlo e/o modificarlo sotto i termini della GNU General Public License come pubblicato dalla Free Software Foundation; o la versione 2 della licenza, o (a propria scelta) una versione successiva. Questo script è distribuito nella speranza che possa essere utile, Ma SENZA ALCUNA GARANZIA; senza neppure la implicita garanzia di COMMERCIABILITÀ o IDONEITÀ PER UN PARTICOLARE SCOPO. Vedere la GNU General Public License per ulteriori dettagli, situato nella `LICENSE.txt` file e disponibili anche da:
- <http://www.gnu.org/licenses/>.
- <http://opensource.org/licenses/>.

Questo documento ed il pacchetto associtato ad esso possono essere scaricati liberamente da [GitHub](https://github.com/Maikuolan/CIDRAM/).

---


###2. <a name="SECTION2"></a>COME INSTALLARE

Spero di semplificare questo processo tramite un installatore ad un certo punto in un futuro non troppo lontano, Ma fino ad allora, seguire queste istruzioni per avere CIDRAM funzionale sulla maggior parte dei sistemi e CMS:

1) Con la vostra lettura di questo, sto supponendo che hai già scaricato una archiviata copia dello script, decompresso il contenuto e lo hanno seduto da qualche parte sul suo locale macchina. Da qui, ti consigliamo di determinare dove sulla macchina o CMS si desidera inserire quei contenuti. Una cartella come `/public_html/cidram/` o simile (però, è non importa quale si sceglie, purché sia qualcosa sicuro e qualcosa si è soddisfatti) sarà sufficiente. *Prima di iniziare il caricamento, continua a leggere..*

2) Facoltativamente (fortemente consigliata per gli avanzati utenti, Ma non è consigliata per i principianti o per gli inesperti), apri `config.ini` (situato della `vault`) - Questo file contiene tutte le direttive disponibili per CIDRAM. Sopra ogni opzione dovrebbe essere un breve commento che descrive ciò che fa e ciò che è per. Regolare queste opzioni come meglio credi, come per ciò che è appropriato per la vostre particolare configurazione. Salvare il file, chiudere.

3) Carica i contenuti (CIDRAM e le sue file) nella cartella che ci deciso in precedenza (non è necessario includere i `*.txt`/`*.md` file, ma altrimenti, si dovrebbe caricare tutto).

4) CHMOD la cartella `vault` a "777". La principale cartella che memorizzare il contenuti (quello scelto in precedenza), solitamente, può essere lasciato solo, Ma lo CHMOD stato dovrebbe essere controllato se hai avuto problemi di autorizzazioni in passato sul vostro sistema (per predefinita, dovrebbe essere qualcosa simile a "755").

5) Successivamente, sarà necessario collegare CIDRAM al vostro sistema o CMS. Ci sono diversi modi in cui è possibile collegare script come CIDRAM al vostre sistema o CMS, Ma il più semplice è di inserire lo script all'inizio di un file del vostre sistema o CMS (quello che sarà generalmente sempre essere caricato quando qualcuno accede a una pagina attraverso il vostro sito) utilizzando un `require` o `include` comando. Solitamente, questo sarà qualcosa memorizzate in una cartella, ad esempio `/includes`, `/assets` o `/functions`, e spesso essere chiamato qualcosa come `init.php`, `common_functions.php`, `functions.php` o simili. Avrete bisogno determinare quale file è per la vostra situazione; In caso di difficoltà nel determinare questo per te, visitare la pagina dei problemi (issues) di CIDRAM su GitHub. Per fare questo [utilizzare `require` o `include`], inserire la seguente linea di codice all'inizio di quel core file, sostituendo la stringa contenuta all'interno delle virgolette con l'indirizzo esatto della "CIDRAM" file (l'indirizzo locale, non l'indirizzo HTTP; sarà simile all'indirizzo citato in precedenza).

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
/.gitattributes | Un file del GitHub progetto (non richiesto per il corretto funzionamento dello script).
/Changelog.txt | Un record delle modifiche apportate allo script tra diverse versioni (non richiesto per il corretto funzionamento dello script).
/composer.json | Composer/Packagist informazioni (non richiesto per il corretto funzionamento dello script).
/LICENSE.txt | Una copia della GNU/GPLv2 licenza.
/loader.php | Caricatore/Loader. Questo è il file si collegare alla vostra sistema (essenziale)!
/README.md | Informazioni di riepilogo del progetto.
/web.config | Un ASP.NET file di configurazione (in questo caso, a proteggere la `/vault` cartella da l'acceso di non autorizzate origini nel caso che lo script è installato su un server basata su ASP.NET tecnologie).
/_docs/ | Documentazione cartella (contiene vari file).
/_docs/readme.en.md | Inglese documentazione.
/_docs/readme.es.md | Spagnolo documentazione.
/_docs/readme.fr.md | Francese documentazione.
/_docs/readme.id.md | Indonesiano documentazione.
/_docs/readme.it.md | Italiano documentazione.
/_docs/readme.nl.md | Olandese documentazione.
/_docs/readme.pt.md | Portoghese documentazione.
/vault/ | La vault cartella (contiene vari file).
/vault/.htaccess | Un ipertesto accesso file (in questo caso, a proteggere di riservati file appartenente allo script da l'acceso di non autorizzate origini).
/vault/cache.dat | Cache data.
/vault/cli.inc | Gestore di CLI.
/vault/config.inc | Gestore di configurazione.
/vault/config.ini | File di configurazione; Contiene tutte l'opzioni di configurazione per CIDRAM, dicendogli cosa fare e come operare correttamente (essenziale)!
/vault/functions.inc | File di funzioni.
/vault/ipv4.dat | File di firme per IPv4.
/vault/ipv4_custom.dat | File di firme per IPv4 personalizzato.
/vault/ipv6.dat | File di firme per IPv6.
/vault/ipv6_custom.dat | File di firme per IPv6 personalizzato.
/vault/lang.inc | Linguistici dati.
/vault/lang/ | Contiene linguistici dati.
/vault/lang/.htaccess | Un ipertesto accesso file (in questo caso, a proteggere di riservati file appartenente allo script da l'acceso di non autorizzate origini).
/vault/lang/lang.en.inc | Linguistici dati Inglese.
/vault/lang/lang.es.inc | Linguistici dati Spagnola.
/vault/lang/lang.fr.inc | Linguistici dati Francese.
/vault/lang/lang.id.inc | Linguistici dati Indonesiana.
/vault/lang/lang.it.inc | Linguistici dati Italiana.
/vault/lang/lang.nl.inc | Linguistici dati Olandese.
/vault/lang/lang.pt.inc | Linguistici dati Portoghese.
/vault/lang/lang.zh-TW.inc | Linguistici dati Cinese (tradizionale).
/vault/lang/lang.zh.inc | Linguistici dati Cinese (semplificata).
/vault/outgen.inc | Generatore di output.
/vault/template.html | File di modello; Modello per l'output HTML prodotto dal generatore di output per CIDRAM.
/vault/template_custom.html | File di modello; Modello per l'output HTML prodotto dal generatore di output per CIDRAM.
/vault/rules_as6939.inc | File di regole personalizzate per AS6939.
/vault/rules_softlayer.inc | File di regole personalizzate per Soft Layer.

---


###5. <a name="SECTION5"></a>OPZIONI DI CONFIGURAZIONE
Il seguente è un elenco di variabili trovate nelle `config.ini` file di configurazione di CIDRAM, insieme con una descrizione del loro scopo e funzione.

####"general" (Categoria)
Generale configurazione per CIDRAM.

"logfile"
- Nome del file per registrando tutti di tentativi di accesso bloccati. Specificare un nome di file, o lasciare vuoto per disabilitare.

"ipaddr"
- Dove trovare l'indirizzo IP di collegamento richiesta? (Utile per servizi come Cloudflare e simili) Predefinito = REMOTE_ADDR. AVVISO: Non modificare questa se non sai quello che stai facendo!

"forbid_on_block"
- CIDRAM dovrebbe rispondere con 403 header alle richieste bloccate, o rimanere con il solito 200 OK? False = No (200) [Predefinito]; True = Sì (403).

"lang"
- Specifica la lingua predefinita per CIDRAM.

"emailaddr"
- Se si desidera, è possibile fornire un indirizzo email qui a dare utenti quando sono bloccati, per loro di utilizzare come punto di contatto per supporto e/o assistenza per il caso di che vengano bloccate per errore. AVVERTIMENTO: Qualunque sia l'indirizzo email si fornisce qui sarà certamente acquisito dal spambots e raschietti/scrapers nel corso del suo essere usato qui, e così, è fortemente raccomandato che se si sceglie di fornire un indirizzo email qui, che si assicurare che l'indirizzo email si fornisce qui è un indirizzo monouso e/o un indirizzo che si non ti dispiace essere spammato (in altre parole, probabilmente si non vuole usare il personale primaria o commerciale primaria indirizzi email).

####"signatures" (Categoria)
Configurazione per firme.

"block_cloud"
- Bloccare CIDRs identificato come appartenente alla servizi webhosting/cloud? Se si utilizza un servizio di API dal suo sito o se si aspetta altri siti a collegare al suo sito, questa direttiva deve essere impostata su false. Se non, questa direttiva deve essere impostata su true.

"block_bogons"
- Bloccare bogone/marziano CIDRs? Se aspetta i collegamenti al suo sito dall'interno della rete locale, da localhost, o dalla LAN, questa direttiva deve essere impostata su false. Se si non aspetta queste tali connessioni, questa direttiva deve essere impostata su true.

"block_generic"
- Bloccare CIDRs generalmente consigliato per la lista nera? Questo copre qualsiasi firme che non sono contrassegnate come parte del qualsiasi delle altre più specifiche categorie di firme.

"block_spam"
- Bloccare CIDRs identificati come alto rischio per spam? A meno che si sperimentare problemi quando si fa così, generalmente, questo dovrebbe essere sempre impostata su true.

---


###6. <a name="SECTION6"></a>FIRMA FORMATO

Una descrizione del formato e la struttura delle firme utilizzate da CIDRAM può essere trovato documentato in testo semplice entro una delle due file di firma personalizzati. Si prega di fare riferimento a tale documentazione per saperne di più sul formato e la struttura delle firme di CIDRAM.

---


Ultimo Aggiornamento: 6 Marzo 2016 (2016.03.06).
