## Dokumentation für CIDRAM (Deutsch).

### Inhalt
- 1. [VORWORT](#SECTION1)
- 2. [INSTALLATION](#SECTION2)
- 3. [BENUTZUNG](#SECTION3)
- 4. [IM PAKET ENTHALTENE DATEIEN](#SECTION4)
- 5. [EINSTELLUNGEN](#SECTION5)
- 6. [SIGNATURENFORMAT](#SECTION6)

---


###1. <a name="SECTION1"></a>VORWORT

CIDRAM (Classless Inter-Domain Routing Access Manager) is a PHP script designed to protect websites by blocking requests originating from IP addresses regarded as being sources of undesirable traffic, including (but not limited to) traffic from non-human access endpoints, cloud services, spambots, scrapers, etc. It does this by calculating the possible CIDRs of the IP addresses supplied from inbound requests and then attempting to match these possible CIDRs against its signature files (these signature files contain lists of CIDRs of IP addresses regarded as being sources of undesirable traffic); If matches are found, the requests are blocked.

CIDRAM COPYRIGHT 2013 und darüber hinaus GNU/GPLv2 by Caleb M (Maikuolan).

Dieses Skript ist freie Software; Sie können Sie weitergeben und/oder modifizieren unter den Bedingungen der GNU General Public License, wie von der Free Software Foundation veröffentlicht; entweder unter Version 2 der Lizenz oder (nach Ihrer Wahl) jeder späteren Version. Dieses Skript wird in der Hoffnung verteilt, dass es nützlich sein wird, allerdings OHNE JEGLICHE GARANTIE; ohne implizite Garantien für VERMARKTUNG/VERKAUF/VERTRIEB oder FÜR EINEN BESTIMMTEN ZWECK. Lesen Sie die GNU General Public License für weitere Details, in der Datei `LICENSE.txt`, ebenfalls verfügbar auf:
- <http://www.gnu.org/licenses/>.
- <http://opensource.org/licenses/>.

Besonderer Dank geht an [ClamAV](http://www.clamav.net/) für die Inspiration und die Signaturen, die dieses Script benutzt, ohne die dieses Script wahrscheinlich nicht existieren würde oder bestenfalls einen sehr begrenzten Wert hätte.

Besonderer Dank geht auch an Sourceforge und GitHub für das Hosten der Projektdateien, an [Spambot Security](http://www.spambotsecurity.com/forum/viewforum.php?f=55) für die CIDRAM Diskussionforen, und an die weiteren Quellen einiger von CIDRAM verwendeten Signaturen: [SecuriteInfo.com](http://www.securiteinfo.com/), [PhishTank](http://www.phishtank.com/), [NLNetLabs](http://nlnetlabs.nl/) und andere, und Besonderer Dank geht an alle diejenigen die das Projekt unterstützen werden, an andere nicht erwähnte Personen, und an Sie, für die Verwendung des Scripts.

Dieses Dokument und das zugehörige Paket kann von folgenden Links kostenlos heruntergeladen werden [GitHub](https://github.com/Maikuolan/CIDRAM/).

---


###2. <a name="SECTION2"></a>INSTALLATION

Zufünftig wird dieser Prozess mit einem Installationsmanager vereinfacht, bis dahin folgen Sie den Anweisungen, um CIDRAM auf den *meisten Systemen und CMS zu installieren:

1) Entpacken Sie das heruntergeladene Archiv auf Ihren lokalen PC. Erstellen Sie ein Verzeichnis, wohin Sie den Inhalt dieses Paketes auf Ihrem Host oder CMS installieren möchten. Ein Verzeichnis wie `/public_html/CIDRAM/` o.ä. genügt, solange es Ihren Sicherheitsbedürfnissen oder persönlichen Präferenzen entspricht.

2) Optional (empfohlen für erfahrene Anwender, nicht empfohlen für Anwender ohne entsprechende Kenntnisse), öffnen Sie die Datei `CIDRAM.ini` im `vault`-Verzeichnis) - Diese Datei beinhaltet alle funktionalen Optionen für CIDRAM. Über jeder Option beschreibt ein kurzer Kommentar die Aufgabe dieser Option. Verändern Sie die Werte nach Ihren Bedürfnissen. Speichern und schließen Sie die Datei.

3) Laden Sie den kompletten Inhalt (CIDRAM und die Dateien) in das Verzeichnis hoch, für das Sie sich in Schritt 1 entschieden haben. Die Dateien `*.txt`/`*.md` müssen nicht mit hochgeladen werden.

4) Ändern Sie die Zugriffsberechtigungen des `vault`-Verzeichnisses auf "777". Die Berechtigungen des übergeordneten Verzeichnises, in welchem sich der Inhalt befindet (das Verzeichnis, wofür Sie sich entschieden haben), können so belassen werden, überprüfen Sie jedoch die Berechtigungen, wenn in der Vergangenheit Zugriffsprobleme aufgetreten sind (Voreinstellung "755" o.ä.).

5) Binden Sie CIDRAM in Ihr System oder CMS ein. Es gibt viele verschiedene Möglichkeiten, ein Script wie CIDRAM einzubinden, am einfachsten ist es, das Script am Anfang einer Haupt-Datei (eine Datei, die immer geladen wird, wenn irgend eine beliebige Seite Ihres Webauftritts aufgerufen wird) Ihres Systems oder CMS mit Hilfe des require- oder include-Befehls einzubinden. Üblicherweise wird eine solche Datei in Verzeichnissen wie `/includes`, `/assets` or `/functions` gespeichert und wird häufig `init.php`, `common_functions.php`, `functions.php` o.ä. genannt. Sie müssen herausfinden, welche Datei dies für Ihre Bedürfnisse ist; Wenn Sie dabei Schwierigkeiten haben das herauszufinden, besuchen Sie die CIDRAM Support-Foren und lassen Sie es uns wissen; Es ist möglich, dass entweder ich oder ein anderer Benutzer mit dem CMS, das Sie verwenden, Erfahrung hat (Sie müssen Sie mitteilen, welche CMS Sie verwenden) und möglicherweise in der Lage ist, etwas Unterstützung anzubieten. Fügen Sie in dieser Datei folgenden Code direkt am Anfang ein:

`<?php require '/user_name/public_html/CIDRAM/loader.php'; ?>`

Ersetzen Sie den String zwischen den Anführungszeichen mit dem lokalen Pfad der Datei `loader.php`, nicht mit der HTTP-Adresse (ähnlich dem Pfad für das `vault`-Verzeichnis). Speichern und schließen Sie die Datei, laden Sie sie ggf. erneut hoch.

-- ODER ALTERNATIV --

Wenn Sie einen Apache-Webserver haben und wenn Sie Zugriff auf die `php.ini` oder eine ähnliche Datei haben, dann können Sie die `auto_prepend_file` Direktive verwenden um CIDRAM voranstellen wenn eine PHP-Anfrage erfolgt. Ungefähr so:

`auto_prepend_file = "/user_name/public_html/CIDRAM/loader.php"`

Oder das in der `.htaccess` Datei:

`php_value auto_prepend_file "/user_name/public_html/CIDRAM/loader.php"`

6) Das ist alles! :-)

---


###3. <a name="SECTION3"></a>BENUTZUNG

CIDRAM should automatically block undesirable requests to your website without requiring any manual assistance, aside from its initial installation.

Updating is done manually, and you can customise your configuration and customise which CIDRs are blocked by modifying your configuration file and/or your signature files.

If you encounter any false positives, please contact me to let me know about it.

---


###4. <a name="SECTION4"></a>IM PAKET ENTHALTENE DATEIEN

Die folgende Liste beinhaltet alle Dateien, die im heruntergeladenen Archiv des Scripts enthalten sind und Dateien, die durch die Benutzung des Scripts eventuell erstellt werden, inkl. einer kurzen Beschreibung.

Datei | Beschreibung
----|----
/.gitattributes | Ein GitHub Projektdatei (für die korrekte Funktion des Scripts nicht notwendig).
/Changelog.txt | Eine Auflistung der Änderungen des Scripts der verschiedenen Versionen (für die korrekte Funktion des Scripts nicht notwendig).
/composer.json | Composer/Packagist Informationen (für die korrekte Funktion des Scripts nicht notwendig).
/LICENSE.txt | Eine Kopie der GNU/GPLv2 Lizenz.
/loader.php | Loader. Diese Datei wird in Ihr CMS eingebunden (notwendig)!
/README.md | Projektübersicht.
/web.config | Eine ASP.NET-Konfigurationsdatei (in diesem Fall zum Schutz des Verzeichnisses `/vault` vor einem nicht authorisierten Zugriff, sofern das Script auf einem auf der ASP.NET-Technologie basierenden Server installiert wurde).
/_docs/ | Verzeichnis für die Dokumentationen (beinhaltet verschiedene Dateien).
/_docs/readme.de.md | Deutsche Dokumentation.
/_docs/readme.en.md | Englische Dokumentation.
/_docs/readme.es.md | Spanische Dokumentation.
/_docs/readme.fr.md | Französische Dokumentation.
/_docs/readme.id.md | Indonesische Dokumentation.
/_docs/readme.it.md | Italienische Dokumentation.
/_docs/readme.nl.md | Niederländische Dokumentation.
/_docs/readme.pt.md | Portugiesische Dokumentation.
/_docs/readme.zh-TW.md | Chinesische Dokumentation (traditionell).
/_docs/readme.zh.md | Chinesische Dokumentation (vereinfacht).
/vault/ | Vault-Verzeichnis (beinhaltet verschiedene Dateien).
/vault/.htaccess | Ein Hypertext-Access-Datei (in diesem Fall zum Schutz von sensiblen Dateien des Scripts vor einem nicht authorisierten Zugriff).
/vault/cache.dat | Cache-Daten.
/vault/cli.inc | CLI-Handler.
/vault/config.inc | Konfiguration-Handler.
/vault/config.ini | Konfigurationsdatei; Beinhaltet alle Konfigurationsmöglichkeiten von CIDRAM (absolut notwendig)!
/vault/functions.inc | Funktionen-Datei.
/vault/ipv4.dat | IPv4 Signaturdatei.
/vault/ipv4_custom.dat | IPv4 benutzerdefinierte Signaturdatei.
/vault/ipv6.dat | IPv6 Signaturdatei.
/vault/ipv6_custom.dat | IPv6 benutzerdefinierte Signaturdatei.
/vault/lang.inc | Sprachdateien.
/vault/lang/ | Enthält Sprachdaten für CIDRAM.
/vault/lang/.htaccess | Ein Hypertext-Access-Datei (in diesem Fall zum Schutz von sensiblen Dateien des Scripts vor einem nicht authorisierten Zugriff).
/vault/lang/lang.en.inc | Englische Sprachdateien.
/vault/lang/lang.es.inc | Spanische Sprachdateien.
/vault/lang/lang.fr.inc | Französische Sprachdateien.
/vault/lang/lang.id.inc | Indonesische Sprachdateien.
/vault/lang/lang.it.inc | Italienische Sprachdateien.
/vault/lang/lang.nl.inc | Niederländische Sprachdateien.
/vault/lang/lang.pt.inc | Portugiesische Sprachdateien.
/vault/lang/lang.zh-TW.inc | Chinesische Sprachdateien (traditionell).
/vault/lang/lang.zh.inc | Chinesische Sprachdateien (vereinfacht).
/vault/outgen.inc | Ausgabe-Generator.
/vault/template.html | Template Datei; Template für die HTML-Ausgabe durch der CIDRAM Ausgabe-Generator erzeugt.
/vault/template_custom.html | Template Datei; Template für die HTML-Ausgabe durch der CIDRAM Ausgabe-Generator erzeugt.
/vault/rules_as6939.inc | Benutzerdefinierte Regeldatei für AS6939.
/vault/rules_softlayer.inc | Benutzerdefinierte Regeldatei für Soft Layer.

---


###5. <a name="SECTION5"></a>EINSTELLUNGEN
Nachfolgend finden Sie eine Liste der Variablen in der Konfigurationsdatei `CIDRAM.ini` mit einer kurzen Beschreibung ihrer Funktionen.

####"general" (Kategorie)
Generelle Konfiguration von CIDRAM.

"logfile"
- Filename of file to log all blocked access attempts to. Specify a filename, or leave blank to disable.

"ipaddr"
- Ort der IP-Adresse der aktuellen Verbindung im gesamten Datenstrom (nützlich für Cloud-Services) Standardeinstellung = REMOTE_ADDR. Achtung: Ändern Sie diesen Wert nur, wenn Sie wissen, was Sie tun!

"forbid_on_block"
- Should CIDRAM respond with 403 headers to blocked requests, or stick with the usual 200 OK? False = No (200) [Default]; True = Yes (403).

"lang"
- Gibt die Standardsprache für CIDRAM an.

"emailaddr"
- If you wish, you can supply an email address here to be given to users when they're blocked, for them to use as a point of contact for support and/or assistance for in the event of them being blocked mistakenly or in error. WARNING: Whatever email address you supply here will most certainly be acquired by spambots and scrapers during the course of its being used here, and so, it's strongly recommended that if you choose to supply an email address here, that you ensure that the email address you supply here is a disposable address and/or an address that you don't mind being spammed (in other words, you probably don't want to use your primary personal or primary business email addresses).

####"signatures" (Kategorie)
Konfiguration der Signaturen.

"block_cloud"
- Block CIDRs identified as belonging to webhosting/cloud services? If you operate an API service from your website or if you expect other websites to connect to your website, this should be set to false. If you don't, then, this directive should be set to true.

"block_bogons"
- Block bogon/martian CIDRs? If you expect connections to your website from within your local network, from localhost, or from your LAN, this directive should be set to false. If you don't expect these such connections, this directive should be set to true.

"block_generic"
- Block CIDRs generally recommended for blacklisting? This covers any signatures that aren't marked as being part of any of the other more specific signature categories.

"block_spam"
- Block CIDRs identified as being high-risk for spam? Unless you experience problems when doing so, generally, this should always be set to true.

---


###6. <a name="SECTION6"></a>SIGNATURENFORMAT

A description of the format and structure of the signatures used by CIDRAM can be found documented in plain-text within either of the two custom signature files. Please refer to that documentation to learn more about the format and structure of the signatures of CIDRAM.

---


Zuletzt aktualisiert: 9. März 2016 (2016.03.09).
