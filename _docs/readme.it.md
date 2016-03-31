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
/LICENSE.txt | Una copia della GNU/GPLv2 licenza (non richiesto per il corretto funzionamento dello script).
/loader.php | Caricatore/Loader. Questo è il file si collegare alla vostra sistema (essenziale)!
/README.md | Informazioni di riepilogo del progetto.
/web.config | Un ASP.NET file di configurazione (in questo caso, a proteggere la `/vault` cartella da l'acceso di non autorizzate origini nel caso che lo script è installato su un server basata su ASP.NET tecnologie).
/_docs/ | Documentazione cartella (contiene vari file).
/_docs/readme.de.md | Tedesco documentazione.
/_docs/readme.en.md | Inglese documentazione.
/_docs/readme.es.md | Spagnolo documentazione.
/_docs/readme.fr.md | Francese documentazione.
/_docs/readme.id.md | Indonesiano documentazione.
/_docs/readme.it.md | Italiano documentazione.
/_docs/readme.nl.md | Olandese documentazione.
/_docs/readme.pt.md | Portoghese documentazione.
/_docs/readme.zh-TW.md | Cinese (Tradizionale) documentazione.
/_docs/readme.zh.md | Cinese (Semplificato) documentazione.
/vault/ | La vault cartella (contiene vari file).
/vault/.htaccess | Un ipertesto accesso file (in questo caso, a proteggere di riservati file appartenente allo script da l'acceso di non autorizzate origini).
/vault/cache.dat | Cache data.
/vault/cli.php | Gestore di CLI.
/vault/config.ini | File di configurazione; Contiene tutte l'opzioni di configurazione per CIDRAM, dicendogli cosa fare e come operare correttamente (essenziale)!
/vault/config.php | Gestore di configurazione.
/vault/functions.php | File di funzioni.
/vault/ipv4.dat | File di firme per IPv4.
/vault/ipv4_custom.dat.RenameMe | File di firme per IPv4 personalizzato (rinomina per attivare).
/vault/ipv6.dat | File di firme per IPv6.
/vault/ipv6_custom.dat.RenameMe | File di firme per IPv6 personalizzato (rinomina per attivare).
/vault/lang.php | Linguistici dati.
/vault/lang/ | Contiene linguistici dati.
/vault/lang/.htaccess | Un ipertesto accesso file (in questo caso, a proteggere di riservati file appartenente allo script da l'acceso di non autorizzate origini).
/vault/lang/lang.en.php | Linguistici dati Inglese.
/vault/lang/lang.es.php | Linguistici dati Spagnola.
/vault/lang/lang.fr.php | Linguistici dati Francese.
/vault/lang/lang.id.php | Linguistici dati Indonesiana.
/vault/lang/lang.it.php | Linguistici dati Italiana.
/vault/lang/lang.nl.php | Linguistici dati Olandese.
/vault/lang/lang.pt.php | Linguistici dati Portoghese.
/vault/lang/lang.zh-TW.php | Linguistici dati Cinese (tradizionale).
/vault/lang/lang.zh.php | Linguistici dati Cinese (semplificata).
/vault/outgen.php | Generatore di output.
/vault/template.html | File di modello; Modello per l'output HTML prodotto dal generatore di output per CIDRAM.
/vault/template_custom.html | File di modello; Modello per l'output HTML prodotto dal generatore di output per CIDRAM.
/vault/rules_as6939.php | File di regole personalizzate per AS6939.
/vault/rules_softlayer.php | File di regole personalizzate per Soft Layer.

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

"disable_cli"
- Disabilita CLI? Modalità CLI è abilitato per predefinito, ma a volte può interferire con alcuni strumenti di test (come PHPUnit, per esempio) e altre applicazioni basate su CLI. Se non è necessario disattivare la modalità CLI, si dovrebbe ignorare questa direttiva. False = Abilita CLI [Predefinito]; True = Disabilita CLI.

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

All IPv4 signatures follow the format: `xxx.xxx.xxx.xxx/yy %Function% %Param%`.
- `xxx.xxx.xxx.xxx` represents the beginning of the CIDR block (the octets of the initial IP address in the block).
- `yy` represents the CIDR block size [1-32].
- `%Function%` instructs the script what to do with the signature (how the signature should be regarded).
- `%Param%` represents whatever additional information may be required by `%Function%`.

All IPv6 signatures follow the format: `xxxx:xxxx:xxxx:xxxx::xxxx/yy %Function% %Param%`.
- `xxxx:xxxx:xxxx:xxxx::xxxx` represents the beginning of the CIDR block (the octets of the initial IP address in the block). Complete notation and abbreviated notation are both acceptable (and each MUST follow the appropriate and relevant standards of IPv6 notation, but with one exception: an IPv6 address can never begin with an abbreviation when used in a signature for this script, due to the way in which CIDRs are reconstructed by the script; For example, `::1/128` should be expressed, when used in a signature, as `0::1/128`, and `::0/128` expressed as `0::/128`).
- `yy` represents the CIDR block size [1-128].
- `%Function%` instructs the script what to do with the signature (how the signature should be regarded).
- `%Param%` represents whatever additional information may be required by `%Function%`.

The signature files for CIDRAM SHOULD use Unix-style linebreaks (`%0A`, or `\n`)! Other types/styles of linebreaks (eg, Windows` %0D%0A` or `\r\n` linebreaks, Mac `%0D` or `\r` linebreaks, etc) MAY be used, but are NOT preferred. Non-Unix-style linebreaks will be normalised to Unix-style linebreaks by the script.

Precise and correct CIDR notation is required, otherwise the script will NOT recognise the signatures. Additionally, all the CIDR signatures of this script MUST begin with an IP address whose IP number can divide evenly into the block division represented by its CIDR block size (eg, if you wanted to block all IPs from `10.128.0.0` to `11.127.255.255`, `10.128.0.0/8` would NOT be recognised by the script, but `10.128.0.0/9` and `11.0.0.0/9` used in conjunction, WOULD be recognised by the script).

Anything in the signature files not recognised as a signature nor as signature-related syntax by the script will be IGNORED, therefore meaning that you can safely put any non-signature data that you want into the signature files without breaking them and without breaking the script. Comments are acceptable in the signature files, and no special formatting is required for them. Shell-style hashing for comments is preferred, but not enforced; Functionally, it makes no difference to the script whether or not you choose to use shell-style hashing for comments, but using shell-style hashing helps IDEs and plain-text editors to correctly highlight the various parts of the signature files (and so, shell-style hashing can assist as a visual aid while editing).

The possible values of `%Function%` are as follows:
- Run
- Whitelist
- Deny

If "Run" is used, when the signature is triggered, the script will attempt to execute (using a `require_once` statement) an external PHP script, specified by the `%Param%` value (the working directory should be the "/vault/" directory of the script).

Example: `127.0.0.0/8 Run example.php`

This can be useful if you want to execute some specific PHP code for some specific IPs and/or CIDRs.

If "Whitelist" is used, when the signature is triggered, the script will reset all detections (if there's been any detections) and break the test function. `%Param%` is ignored. This function is the equivalent of whitelisting a particular IP or CIDR from being detected.

Example: `127.0.0.1/32 Whitelist`

If "Deny" is used, when the signature is triggered, assuming no whitelist signature has been triggered for the given IP address and/or given CIDR, access to the protected page will be denied. "Deny" is what you'll want to use to actually block an IP address and/or CIDR range. When any signatures are triggered that make use of "Deny", the "Access Denied" page of the script will be generated and the request to the protected page killed.

The `%Param%` value accepted by "Deny" will be parsed to the "Access Denied" page output, supplied to the client/user as the cited reason for their access to the requested page being denied. It can be either a short and simple sentence, explaining why you've chosen to block them (anything should suffice, even a simple "I don't want you on my website"), or one of a small handful of shorthand words supplied by the script, that if used, will be replaced by the script with a pre-prepared explanation of why the client/user has been blocked.

The pre-prepared explanations have i18n support and can be translated by the script based upon the language you specify to the `lang` directive of the script configuration. Additionally, you can instruct the script to ignore "Deny" signatures based upon their `%Param%` value (if they're using these shorthand words) via the directives specified by the script configuration (each shorthand word has a corresponding directive to either process the corresponding signatures or to ignore them). `%Param%` values that don't use these shorthand words, however, don't have i18n support and therefore WON'T be translated by the script, and additionally, aren't directly controllable by the script configuration.

The available shorthand words are:
- Bogon
- Cloud
- Generic
- Spam

Optional: If you want to split your custom signatures into individual sections, you can identify these individual sections to the script by adding a "Tag:" label immediately after the signatures of each section, along with the name of your signature section.

Example:
```
# "Section 1."
1.2.3.4/32 Deny Bogon
2.3.4.5/32 Deny Cloud
4.5.6.7/32 Deny Generic
5.6.7.8/32 Deny Spam
Tag: Section 1
```

To break section tagging and to ensure that tags aren't incorrectly identified to signature sections from earlier in the signature files, simply ensure that there are at least two consecutive linebreaks between your tag and your earlier signature sections. Any untagged signatures will default to either "IPv4" or "IPv6" (depending on which types of signatures are being triggered).

Example:
```
1.2.3.4/32 Deny Bogon
2.3.4.5/32 Deny Cloud

4.5.6.7/32 Deny Generic
5.6.7.8/32 Deny Spam
Tag: Section 1
```

In the above example `1.2.3.4/32` and `2.3.4.5/32` will be tagged as "IPv4", whereas `4.5.6.7/32` and `5.6.7.8/32` will be tagged as "Section 1".

Refer to the custom signature files for more information.

---


Ultimo Aggiornamento: 31 Marzo 2016 (2016.03.31).
