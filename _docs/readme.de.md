## Dokumentation für CIDRAM (Deutsch).

### Inhalt
- 1. [VORWORT](#SECTION1)
- 2. [INSTALLATION](#SECTION2)
- 3. [BENUTZUNG](#SECTION3)
- 4. [FRONT-END-MANAGEMENT](#SECTION4)
- 5. [IM PAKET ENTHALTENE DATEIEN](#SECTION5)
- 6. [EINSTELLUNGEN](#SECTION6)
- 7. [SIGNATURENFORMAT](#SECTION7)
- 8. [HÄUFIG GESTELLTE FRAGEN (FAQ)](#SECTION8)

*Hinweis für Übersetzungen: Im Falle von Fehlern (z.B, Diskrepanzen zwischen den Übersetzungen, Tippfehler, u.s.w.), die Englische Version des README als die ursprüngliche und maßgebliche Version ist betrachtet. Wenn Sie irgendwelche Fehler finden, ihre Hilfe bei der Korrektur wäre willkommen.*

---


###1. <a name="SECTION1"></a>VORWORT

CIDRAM (Classless Inter-Domain Routing Access Manager) ist ein PHP-Skript gestaltete für Websites zu schützen, durch das Blockieren von IP-Adressen als Quellen von unerwünschten Verkehr angesehen, einschließlich (aber nicht beschränkt auf) Datenverkehr von nicht-menschlichen Zugang Endpunkte, Cloud-Services, spambots, Website Schaber, usw. Es tut dies durch die möglichen CIDRs von IP-Adressen zu berechnen, von eingehenden Anfragen geliefert, und dann versuchen, diese gegen ihre Signaturdateien zu entsprechen (Diese Signaturdateien enthalten Listen von CIDRs von IP-Adressen als Ursachen für unerwünschte Verkehrs angesehen); Wenn Übereinstimmungen gefunden werden, die Anfragen ist blockiert.

CIDRAM COPYRIGHT 2016 und darüber hinaus GNU/GPLv2 by Caleb M (Maikuolan).

Dieses Skript ist freie Software; Sie können Sie weitergeben und/oder modifizieren unter den Bedingungen der GNU General Public License, wie von der Free Software Foundation veröffentlicht; entweder unter Version 2 der Lizenz oder (nach Ihrer Wahl) jeder späteren Version. Dieses Skript wird in der Hoffnung verteilt, dass es nützlich sein wird, allerdings OHNE JEGLICHE GARANTIE; ohne implizite Garantien für VERMARKTUNG/VERKAUF/VERTRIEB oder FÜR EINEN BESTIMMTEN ZWECK. Lesen Sie die GNU General Public License für weitere Details, in der Datei `LICENSE.txt`, ebenfalls verfügbar auf:
- <http://www.gnu.org/licenses/>.
- <http://opensource.org/licenses/>.

Besonderer Dank geht an [ClamAV](http://www.clamav.net/) für die Inspiration und die Signaturen, die dieses Script benutzt, ohne die dieses Script wahrscheinlich nicht existieren würde oder bestenfalls einen sehr begrenzten Wert hätte.

Dieses Dokument und das zugehörige Paket kann von folgenden Links kostenlos heruntergeladen werden [Github](https://github.com/Maikuolan/CIDRAM/).

---


###2. <a name="SECTION2"></a>INSTALLATION

Zufünftig wird dieser Prozess mit einem Installationsmanager vereinfacht, bis dahin folgen Sie den Anweisungen, um CIDRAM auf den *meisten Systemen und CMS zu installieren:

1) Entpacken Sie das heruntergeladene Archiv auf Ihren lokalen PC. Erstellen Sie ein Verzeichnis, wohin Sie den Inhalt dieses Paketes auf Ihrem Host oder CMS installieren möchten. Ein Verzeichnis wie `/public_html/cidram/` o.ä. genügt, solange es Ihren Sicherheitsbedürfnissen oder persönlichen Präferenzen entspricht.

2) Die Datei `config.ini.RenameMe` (im `vault`-Verzeichnis) zu `config.ini` umbenennen, und optional (empfohlen für erfahrene Anwender, nicht empfohlen für Anwender ohne entsprechende Kenntnisse), öffnen Sie diese Datei (diese Datei beinhaltet alle funktionalen Optionen für CIDRAM; über jeder Option beschreibt ein kurzer Kommentar die Aufgabe dieser Option). Verändern Sie die Werte nach Ihren Bedürfnissen. Speichern und schließen Sie die Datei.

3) Laden Sie den kompletten Inhalt (CIDRAM und die Dateien) in das Verzeichnis hoch, für das Sie sich in Schritt 1 entschieden haben. Die Dateien `*.txt`/`*.md` müssen nicht mit hochgeladen werden.

4) Ändern Sie die Zugriffsberechtigungen des `vault`-Verzeichnisses auf "755" (wenn es Probleme gibt, Sie können "777" versuchen; Dies ist weniger sicher, obwohl). Die Berechtigungen des übergeordneten Verzeichnises, in welchem sich der Inhalt befindet (das Verzeichnis, wofür Sie sich entschieden haben), können so belassen werden, überprüfen Sie jedoch die Berechtigungen, wenn in der Vergangenheit Zugriffsprobleme aufgetreten sind (Voreinstellung "755" o.ä.).

5) Binden Sie CIDRAM in Ihr System oder CMS ein. Es gibt viele verschiedene Möglichkeiten, ein Script wie CIDRAM einzubinden, am einfachsten ist es, das Script am Anfang einer Haupt-Datei (eine Datei, die immer geladen wird, wenn irgend eine beliebige Seite Ihres Webauftritts aufgerufen wird) Ihres Systems oder CMS mit Hilfe des require- oder include-Befehls einzubinden. Üblicherweise wird eine solche Datei in Verzeichnissen wie `/includes`, `/assets` or `/functions` gespeichert und wird häufig `init.php`, `common_functions.php`, `functions.php` o.ä. genannt. Sie müssen herausfinden, welche Datei dies für Ihre Bedürfnisse ist; Wenn Sie dabei Schwierigkeiten haben das herauszufinden, besuchen Sie die CIDRAM Issues-Seiten bei Github und lassen Sie es uns wissen; Es ist möglich, dass entweder ich oder ein anderer Benutzer mit dem CMS, das Sie verwenden, Erfahrung hat (Sie müssen Sie mitteilen, welche CMS Sie verwenden) und möglicherweise in der Lage ist, etwas Unterstützung anzubieten. Fügen Sie in dieser Datei folgenden Code direkt am Anfang ein:

`<?php require '/user_name/public_html/cidram/loader.php'; ?>`

Ersetzen Sie den String zwischen den Anführungszeichen mit dem lokalen Pfad der Datei `loader.php`, nicht mit der HTTP-Adresse (ähnlich dem Pfad für das `vault`-Verzeichnis). Speichern und schließen Sie die Datei, laden Sie sie ggf. erneut hoch.

-- ODER ALTERNATIV --

Wenn Sie einen Apache-Webserver haben und wenn Sie Zugriff auf die `php.ini` oder eine ähnliche Datei haben, dann können Sie die `auto_prepend_file` Direktive verwenden um CIDRAM voranstellen wenn eine PHP-Anfrage erfolgt. Ungefähr so:

`auto_prepend_file = "/user_name/public_html/cidram/loader.php"`

Oder das in der `.htaccess` Datei:

`php_value auto_prepend_file "/user_name/public_html/cidram/loader.php"`

6) Das ist alles! :-)

---


###3. <a name="SECTION3"></a>BENUTZUNG

CIDRAM sollte automatisch unerwünschte Zugriffe auf Ihre Website blockieren, ohne manuelle Unterstützung erforderlich, abgesehen von seiner Erstinstallation.

Die Aktualisierung erfolgt manuell, und Sie können Ihre Konfiguration anpassen, und Sie können welche CIDRs blockiert festlegen, durch Sie Ihre Konfigurationsdatei und/oder Signaturdateien Modifizieren.

Wenn Sie Falsch-Positivs begegnen, bitte kontaktieren Sie mich zu informieren.

---


###4. <a name="SECTION4"></a>FRONT-END-MANAGEMENT

@TODO@

---


###5. <a name="SECTION5"></a>IM PAKET ENTHALTENE DATEIEN

Die folgende Liste beinhaltet alle Dateien, die im heruntergeladenen Archiv des Scripts enthalten sind und Dateien, die durch die Benutzung des Scripts eventuell erstellt werden, inkl. einer kurzen Beschreibung.

Datei | Beschreibung
----|----
/_docs/ | Verzeichnis für die Dokumentationen (beinhaltet verschiedene Dateien).
/_docs/readme.ar.md | Arabische Dokumentation.
/_docs/readme.de.md | Deutsche Dokumentation.
/_docs/readme.en.md | Englische Dokumentation.
/_docs/readme.es.md | Spanische Dokumentation.
/_docs/readme.fr.md | Französische Dokumentation.
/_docs/readme.id.md | Indonesische Dokumentation.
/_docs/readme.it.md | Italienische Dokumentation.
/_docs/readme.ja.md | Japanische Dokumentation.
/_docs/readme.nl.md | Niederländische Dokumentation.
/_docs/readme.pt.md | Portugiesische Dokumentation.
/_docs/readme.ru.md | Russische Dokumentation.
/_docs/readme.vi.md | Vietnamesische Dokumentation.
/_docs/readme.zh-TW.md | Chinesische Dokumentation (traditionell).
/_docs/readme.zh.md | Chinesische Dokumentation (vereinfacht).
/vault/ | Vault-Verzeichnis (beinhaltet verschiedene Dateien).
/vault/fe_assets/ | Front-End-Daten.
/vault/fe_assets/.htaccess | Ein Hypertext-Access-Datei (in diesem Fall zum Schutz von sensiblen Dateien des Scripts vor einem nicht authorisierten Zugriff).
/vault/fe_assets/_accounts.html | Eine HTML-Template für das Front-End Konten-Seite.
/vault/fe_assets/_accounts_row.html | Eine HTML-Template für das Front-End Konten-Seite.
/vault/fe_assets/_config.html | Eine HTML-Template für das Front-End Konfiguration-Seite.
/vault/fe_assets/_config_row.html | Eine HTML-Template für das Front-End Konfiguration-Seite.
/vault/fe_assets/_files.html | Eine HTML-Template für den Dateimanager.
/vault/fe_assets/_files_edit.html | Eine HTML-Template für den Dateimanager.
/vault/fe_assets/_files_row.html | Eine HTML-Template für den Dateimanager.
/vault/fe_assets/_home.html | Eine HTML-Template für das Front-End Startseite.
/vault/fe_assets/_ip_test.html | Eine HTML-Template für das IP-Test-Seite.
/vault/fe_assets/_ip_test_row.html | Eine HTML-Template für das IP-Test-Seite.
/vault/fe_assets/_login.html | Eine HTML-Template für das Front-End Einloggen-Seite.
/vault/fe_assets/_logs.html | Eine HTML-Template für das Front-End Protokolldateien-Seite.
/vault/fe_assets/_nav_complete_access.html | Eine HTML-Template für das Front-End Navigation-Links, für alle mit vollständiger Zugriff.
/vault/fe_assets/_nav_logs_access_only.html | Eine HTML-Template für das Front-End Navigation-Links, für alle mit Zugriff nur auf Protokolldateien.
/vault/fe_assets/_updates.html | Eine HTML-Template für das Front-End Aktualisierungen-Seite.
/vault/fe_assets/_updates_row.html | Eine HTML-Template für das Front-End Aktualisierungen-Seite.
/vault/fe_assets/frontend.css | CSS-Stylesheet für das Front-End.
/vault/fe_assets/frontend.dat | Datenbank für das Front-End (Enthält Kontoinformationen, Sitzungsinformationen, und dem Cache; nur erzeugt wenn das Frontend aktiviert und verwendet wird).
/vault/fe_assets/frontend.html | Die Haupt-HTML-Template-Datei für das Front-End.
/vault/lang/ | Enthält Sprachdaten für CIDRAM.
/vault/lang/.htaccess | Ein Hypertext-Access-Datei (in diesem Fall zum Schutz von sensiblen Dateien des Scripts vor einem nicht authorisierten Zugriff).
/vault/lang/lang.ar.cli.php | Arabische Sprachdateien für CLI.
/vault/lang/lang.ar.fe.php | Arabische Sprachdateien für das Front-End.
/vault/lang/lang.ar.php | Arabische Sprachdateien.
/vault/lang/lang.de.cli.php | Deutsche Sprachdateien für CLI.
/vault/lang/lang.de.fe.php | Deutsche Sprachdateien für das Front-End.
/vault/lang/lang.de.php | Deutsche Sprachdateien.
/vault/lang/lang.en.cli.php | Englische Sprachdateien für CLI.
/vault/lang/lang.en.fe.php | Englische Sprachdateien für das Front-End.
/vault/lang/lang.en.php | Englische Sprachdateien.
/vault/lang/lang.es.cli.php | Spanische Sprachdateien für CLI.
/vault/lang/lang.es.fe.php | Spanische Sprachdateien für das Front-End.
/vault/lang/lang.es.php | Spanische Sprachdateien.
/vault/lang/lang.fr.cli.php | Französische Sprachdateien für CLI.
/vault/lang/lang.fr.fe.php | Französische Sprachdateien für das Front-End.
/vault/lang/lang.fr.php | Französische Sprachdateien.
/vault/lang/lang.id.cli.php | Indonesische Sprachdateien für CLI.
/vault/lang/lang.id.fe.php | Indonesische Sprachdateien für das Front-End.
/vault/lang/lang.id.php | Indonesische Sprachdateien.
/vault/lang/lang.it.cli.php | Italienische Sprachdateien für CLI.
/vault/lang/lang.it.fe.php | Italienische Sprachdateien für das Front-End.
/vault/lang/lang.it.php | Italienische Sprachdateien.
/vault/lang/lang.ja.cli.php | Japanische Sprachdateien für CLI.
/vault/lang/lang.ja.fe.php | Japanische Sprachdateien für das Front-End.
/vault/lang/lang.ja.php | Japanische Sprachdateien.
/vault/lang/lang.nl.cli.php | Niederländische Sprachdateien für CLI.
/vault/lang/lang.nl.fe.php | Niederländische Sprachdateien für das Front-End.
/vault/lang/lang.nl.php | Niederländische Sprachdateien.
/vault/lang/lang.pt.cli.php | Portugiesische Sprachdateien für CLI.
/vault/lang/lang.pt.fe.php | Portugiesische Sprachdateien für das Front-End.
/vault/lang/lang.pt.php | Portugiesische Sprachdateien.
/vault/lang/lang.ru.cli.php | Russische Sprachdateien für CLI.
/vault/lang/lang.ru.fe.php | Russische Sprachdateien für das Front-End.
/vault/lang/lang.ru.php | Russische Sprachdateien.
/vault/lang/lang.vi.cli.php | Vietnamesische Sprachdateien für CLI.
/vault/lang/lang.vi.fe.php | Vietnamesische Sprachdateien für das Front-End.
/vault/lang/lang.vi.php | Vietnamesische Sprachdateien.
/vault/lang/lang.zh-tw.cli.php | Chinesische Sprachdateien (traditionell) für CLI.
/vault/lang/lang.zh-tw.fe.php | Chinesische Sprachdateien (traditionell) für das Front-End.
/vault/lang/lang.zh-tw.php | Chinesische Sprachdateien (traditionell).
/vault/lang/lang.zh.cli.php | Chinesische Sprachdateien (vereinfacht) für CLI.
/vault/lang/lang.zh.fe.php | Chinesische Sprachdateien (vereinfacht) für das Front-End.
/vault/lang/lang.zh.php | Chinesische Sprachdateien (vereinfacht).
/vault/.htaccess | Ein Hypertext-Access-Datei (in diesem Fall zum Schutz von sensiblen Dateien des Scripts vor einem nicht authorisierten Zugriff).
/vault/cache.dat | Cache-Daten.
/vault/cidramblocklists.dat | Enthält Informationen zu den optionalen Länderblocklisten bereitgestellt von Macmathan; Wird von der Aktualisierungsfunktion bereitgestellt durch das Front-End verwendet.
/vault/cli.php | CLI-Handler.
/vault/components.dat | Enthält Informationen zu den verschiedenen Komponenten für CIDRAM; Wird von der Aktualisierungsfunktion bereitgestellt durch das Front-End verwendet.
/vault/config.ini.RenameMe | Konfigurationsdatei; Beinhaltet alle Konfigurationsmöglichkeiten von CIDRAM (umbenennen zu aktivieren).
/vault/config.yaml | Standardkonfigurationsdatei; Beinhaltet Standardkonfigurationswerte für CIDRAM.
/vault/config.php | Konfiguration-Handler.
/vault/frontend.php | Front-End-Handler.
/vault/functions.php | Funktionen-Datei.
/vault/hashes.dat | Enthält eine Liste der akzeptierten Hashes (relevant für die reCAPTCHA-Funktion; nur dann erzeugt wird, wenn die reCAPTCHA-Funktion aktiviert ist).
/vault/icons.php | Ikonen-Handler (die vom Front-End-Dateimanager verwendet wird).
/vault/ignore.dat | Ignoriert Datei (zu spezifizieren welche Signatur-Sektionen CIDRAM sollte ignorieren es ist benutzt).
/vault/ipbypass.dat | Enthält eine Liste von IP-Bypässe (relevant für die reCAPTCHA-Funktion; nur dann erzeugt wird, wenn die reCAPTCHA-Funktion aktiviert ist).
/vault/ipv4.dat | IPv4 Signaturdatei.
/vault/ipv4_custom.dat.RenameMe | IPv4 benutzerdefinierte Signaturdatei (umbenennen zu aktivieren).
/vault/ipv6.dat | IPv6 Signaturdatei.
/vault/ipv6_custom.dat.RenameMe | IPv6 benutzerdefinierte Signaturdatei (umbenennen zu aktivieren).
/vault/lang.php | Sprachdateien.
/vault/outgen.php | Ausgabe-Generator.
/vault/recaptcha.php | reCAPTCHA-Modul.
/vault/rules_as6939.php | Benutzerdefinierte Regeldatei für AS6939.
/vault/rules_softlayer.php | Benutzerdefinierte Regeldatei für Soft Layer.
/vault/rules_specific.php | Benutzerdefinierte Regeldatei für einige spezifische CIDRs.
/vault/salt.dat | Salz-Datei (durch einigen periphere Funktionalität verwendet; nur dann erzeugt wenn erforderlich).
/vault/template.html | Template Datei; Template für die HTML-Ausgabe durch der CIDRAM Ausgabe-Generator erzeugt.
/vault/template_custom.html | Template Datei; Template für die HTML-Ausgabe durch der CIDRAM Ausgabe-Generator erzeugt.
/.gitattributes | Ein Github Projektdatei (für die korrekte Funktion des Scripts nicht notwendig).
/Changelog.txt | Eine Auflistung der Änderungen des Scripts der verschiedenen Versionen (für die korrekte Funktion des Scripts nicht notwendig).
/composer.json | Composer/Packagist Informationen (für die korrekte Funktion des Scripts nicht notwendig).
/LICENSE.txt | Eine Kopie der GNU/GPLv2 Lizenz (für die korrekte Funktion des Scripts nicht notwendig).
/loader.php | Loader. Diese Datei wird in Ihr CMS eingebunden (notwendig)!
/README.md | Projektübersicht.
/web.config | Eine ASP.NET-Konfigurationsdatei (in diesem Fall zum Schutz des Verzeichnisses `/vault` vor einem nicht authorisierten Zugriff, sofern das Script auf einem auf der ASP.NET-Technologie basierenden Server installiert wurde).

---


###6. <a name="SECTION6"></a>EINSTELLUNGEN
Nachfolgend finden Sie eine Liste der Variablen in der Konfigurationsdatei `config.ini` mit einer kurzen Beschreibung ihrer Funktionen.

####"general" (Kategorie)
Generelle Konfiguration von CIDRAM.

"logfile"
- Name einer Datei für Menschen lesbar zu protokollieren alle blockierten Zugriffsversuche. Geben Sie einen Dateinamen an oder lassen Sie die Option zum Deaktivieren leer.

"logfileApache"
- Name einer Apache-Stil-Datei zu protokollieren alle blockierten Zugriffsversuche. Geben Sie einen Dateinamen an oder lassen Sie die Option zum Deaktivieren leer.

"logfileSerialized"
- Name einer Datei zu protokollieren alle blockierten Zugriffsversuche (Format ist serialisiert). Geben Sie einen Dateinamen an oder lassen Sie die Option zum Deaktivieren leer.

*Nützlicher Tipp: Wenn du willst, Sie können Datum/Uhrzeit-Information den Namen der Protokolldateien anhängen, durch diese im Namen einschließlich: `{yyyy}` für die komplette Jahr, `{yy}` für abgekürzte Jahr, `{mm}` für Monate, `{dd}` für Tag, `{hh}` für Stunde.*

*Beispielen:*
- *`logfile='logfile.{yyyy}-{mm}-{dd}-{hh}.txt'`*
- *`logfileApache='access.{yyyy}-{mm}-{dd}-{hh}.txt'`*
- *`logfileSerialized='serial.{yyyy}-{mm}-{dd}-{hh}.txt'`*

"timeOffset"
- Wenn Ihr Server-Zeit nicht mit Ihrer Ortszeit, Sie können einen Offset hier angeben (Dies ist das Datum/Zeit-Informationen anpassen, die durch CIDRAM erzeugt wird). Es ist in der Regel statt zur Einstellung der Zeitzone Richtlinie in Ihrer Datei `php.ini` empfohlen, aber manchmal (wie wenn Sie mit begrenzten Shared-Hosting-Provider arbeiten) dies ist nicht immer möglich zu tun, und so, ist diese Option hier zur Verfügung gestellt.
- Beispiel (eine Stunde hinzufügen): `timeOffset=60`

"ipaddr"
- Ort der IP-Adresse der aktuellen Verbindung im gesamten Datenstrom (nützlich für Cloud-Services). Standardeinstellung = REMOTE_ADDR. Achtung: Ändern Sie diesen Wert nur, wenn Sie wissen, was Sie tun!

"forbid_on_block"
- Welche Header sollte CIDRAM reagieren mit, wenn Anfragen blockiert? False/200 = 200 OK [Standardeinstellung]; True/403 = 403 Forbidden (Verboten); 503 = 503 Service unavailable (Service nicht verfügbar).

"silent_mode"
- Anstatt die "Zugriff verweigert", sollte CIDRAM leise blockiert Zugriffsversuche umleiten? Wenn ja, geben Sie den Speicherort auf den blockierten Zugriffsversuche umleiten. Wenn nein, diese Variable leer lassen.

"lang"
- Gibt die Standardsprache für CIDRAM an.

"emailaddr"
- Wenn Sie möchten, können Sie hier eine E-Mail-Adresse angeben, geben auf den Benutzern wenn sie blockiert, für Unterstützung für den Fall dass sie ist blockiert versehentlich oder im fehler. WARNUNG: Jede E-Mail-Adresse die Sie hier angeben wird sicherlich durch Spambots erworben werden im Zuge ihrer Verwendung hier, und so, es wird dringend empfohlen, wenn Sie hier eine E-Mail-Adresse angeben, dass die E-Mail-Adresse die Sie hier angeben, eine Einwegadresse ist, und/oder eine Adresse die Sie nichts dagegen haben Spam (mit anderen Worten, möchten Sie wahrscheinlich nicht Ihre primären persönlichen oder primären geschäftlichen E-Mail-Adressen verwenden).

"disable_cli"
- CLI-Modus deaktivieren? CLI-Modus ist standardmäßig aktiviert, kann aber manchmal bestimmte Test-Tools (PHPUnit zum Beispiel) und andere CLI-basierte Anwendungen beeinträchtigen. Wenn du den CLI-Modus nicht deaktiveren musst, solltest du diese Anweisung ignorieren. False = CLI-Modus aktivieren [Standardeinstellung]; True = CLI-Modus deaktivieren.

"disable_frontend"
- Front-End-Access deaktivieren? Front-End-Access kann machen CIDRAM einfacher zu handhaben, aber es kann auch ein potentielles Sicherheitsrisiko sein. Es wird empfohlen, wenn möglich, CIDRAM über die Back-End-Access zu verwalten, aber Front-End-Access vorgesehen ist, für wenn es nicht möglich ist. Halten Sie es deaktiviert außer wenn Sie es brauchen. False = Front-End-Access aktivieren; True = Front-End-Access deaktivieren [Standardeinstellung].

"max_login_attempts"
- Maximale Anzahl der Versucht zu einloggen (Front-End). Standardeinstellung = 5.

"FrontEndLog"
- Datei für die Protokollierung von Front-End Einloggen-Versuchen. Geben Sie einen Dateinamen an oder lassen Sie die Option zum Deaktivieren leer.

####"signatures" (Kategorie)
Konfiguration der Signaturen.

"ipv4"
- Eine Liste der IPv4-Signaturdateien dass CIDRAM zu verarbeiten soll, durch Kommas begrenzt. Hier können Sie Einträge hinzufügen wenn Sie zusätzliche IPv4-Signaturdateien in CIDRAM hinzufügen möchten.

"ipv6"
- Eine Liste der IPv6-Signaturdateien dass CIDRAM zu verarbeiten soll, durch Kommas begrenzt. Hier können Sie Einträge hinzufügen wenn Sie zusätzliche IPv6-Signaturdateien in CIDRAM hinzufügen möchten.

"block_cloud"
- Blockieren Sie CIDRs identifiziert als zu Web-Hosting/Cloud-Services gehören? Wenn Sie einen API-Dienst von Ihrer Website aus betreiben, oder wenn Sie erwarten dass andere Websites eine Verbindung zu Ihrer Website herstellen, dies auf false sollte gesetzt werden. Wenn Sie nicht, dann, dies auf true sollte gesetzt werden.

"block_bogons"
- Blockieren Sie Bogon/Martian CIDRs? Wenn Sie Verbindungen zu Ihrer Website von localhost, von Ihrem LAN, oder von innerhalb Ihres lokalen Netzwerks erwarten, diese Richtlinie auf false sollte gesetzt werden. Wenn Sie diese Verbindungen nicht erwarten, dies auf true sollte gesetzt werden.

"block_generic"
- Blockieren Sie CIDRs allgemein empfohlen für eine schwarze Liste? Dies gilt für alle Signaturen, die nicht als Teil einer der anderen spezifischen Signaturkategorien markiert sind.

"block_proxies"
- Blockieren Sie CIDRs identifiziert als zu Proxy-Dienste gehören? Wenn Sie benötigen dass Benutzer auf Ihre Website von anonymen Proxy-Diensten zugreifen können, dies auf false sollte gesetzt werden. Andernfalls, Wenn Sie anonyme Proxies nicht benötigen, diese Richtlinie auf true sollte gesetzt werden, als Mittel zur Verbesserung der Sicherheit.

"block_spam"
- Blockieren Sie CIDRs identifiziert als ein hohem Risiko für Spam? Solange Sie keine Probleme haben während Sie dies tun, allgemein, dies immer auf true sollte gesetzt sein.

####"recaptcha" (Kategorie)
Wenn du willst, können Sie Benutzern bieten eine Möglichkeit zur umgehen der Seite "Zugriff verweigert" durch Abschluß einer reCAPTCHA-Instanz. Dies kann helfen, einige der Risiken im Zusammenhang mit Falsch-Positivs zu mildern, in diesen Fällen wodurch wir nicht ganz sicher sind ob eine Anfrage von einer Maschine oder einem Menschen stammt.

Aufgrund der Risiken im Zusammenhang mit der Bereitstellung eines Wegs um der Seite "Zugriff verweigert" zur umgehen, im Algemeinen, Ich würde raten dies zu nicht ermöglichen es sei denn, Sie empfinden es als notwendig dies zu tun. Situationen wo es notwendig könnte sein: Wenn Ihre Website hat Kunden/Benutzer, die Zugriff auf Ihre Website haben müssen, und wenn dies etwas ist das nicht ausgehandelt werden kann, aber wenn diese Kunden/Benutzer von einem feindlichen Netzwerk zu verbinden, das möglicherweise auch unerwünschten Verkehr führen könnte, und die Blockierung dieser unerwünschten Verkehr ist auch etwas das nicht ausgehandelt werden kann, in diesen Situationen, könnte das reCAPTCHA-Feature nützlich sein um die wünschenswerten Kunden/Benutzer zu ermöglichen, während der unerwünschten Verkehr aus demselben Netzwerk ausgeschlossen ist. Das sagte aber, da der beabsichtigte Zweck einer CAPTCHA ist, zwischen Menschen und Nicht-Menschen zu unterscheiden, das reCAPTCHA-Feature würde nur in diesen Situationen ohne Gewinner zu helfen, wenn wir annehmen dass dieser unerwünschten Verkehr nicht menschlich ist (z.B, Spambots, Scrapers, Hacktools, automatisierten Datenverkehr), im Gegensatz zu unerwünschtem menschlichen Verkehr (wie menschliche Spammer, Hacker, und derartige).

Um einen "site key" und einen "secret key" zu erhalten (für die Verwendung von reCAPTCHA erforderlich), bitte gehe zu: [https://developers.google.com/recaptcha/](https://developers.google.com/recaptcha/)

"usemode"
- Dies definiert wie CIDRAM sollte reCAPTCHA benutzen.
- 0 = reCAPTCHA ist komplett deaktiviert (Standardeinstellung).
- 1 = reCAPTCHA ist für alle Signaturen aktiviert.
- 2 = reCAPTCHA ist aktiviert, nur für Signaturen die zu Abschnitten besonders markiert als reCAPTCHA-aktiviert in den Signaturdateien gehören.
- (Jeder andere Wert wird auf die gleiche Weise behandelt wie 0).

"lockip"
- Gibt an ob Hashes an bestimmte IPs gebunden werden sollen. False = Cookies und Hashes KÖNNEN über mehrere IPs verwendet werden (Standardeinstellung). True = Cookies und Hashes können NICHT über mehrere IPs verwendet werden (Cookies/Hashes sind an IPs gebunden).
- Beachten: Der Wert für "lockip" wird ignoriert, wenn "lockuser" false ist, aufgrund der Tatsache dass der Mechanismus zum Erinnern "Benutzer" unterscheidet sich je nach diesem Wert.

"lockuser"
- Gibt an ob der erfolgreiche Abschluss eines reCAPTCHA-Instanz an bestimmte Benutzer gebunden werden sollen. False = Der erfolgreiche Abschluss eines reCAPTCHA-Instanz, auf alle Anfragen die von derselben IP stammen die von dem Benutzer die die reCAPTCHA-Instanz ausfüllt wird Zugriff gewährt; Cookies und Hashes werden nicht verwendet; Stattdessen wird eine IP-Whitelist verwendet. True = Der erfolgreiche Abschluss eines reCAPTCHA-Instanz nur dem Benutzer dass die reCAPTCHA-Instanz ausfüllt wird Zugriff gewährt; Cookies und Hashes werden verwendet, um den Benutzer zu merken; Eine IP-Whitelist wird nicht verwendet (Standardeinstellung).

"sitekey"
- Dieser Wert sollte dem "site key" für Ihre reCAPTCHA entsprechen, sich innerhalb des reCAPTCHA Dashboard befindet.

"secret"
- Dieser Wert sollte dem "secret key" für Ihre reCAPTCHA entsprechen, sich innerhalb des reCAPTCHA Dashboard befindet.

"expiry"
- When "lockuser" is true (Standardeinstellung), in order to remember when a user has successfully passed a reCAPTCHA-Instanz, for future page requests, CIDRAM generates a standard HTTP cookie containing a hash which corresponds to an internal record containing that same hash; Future page requests will use these corresponding hashes to authenticate that a user has previously already passed a reCAPTCHA-Instanz. When "lockuser" is false, an IP whitelist is used to determine whether requests should be permitted from the IP of inbound requests; Entries are added to this whitelist when the reCAPTCHA-Instanz is successfully passed. For how many hours should these cookies, hashes and whitelist entries remain valid? Default = 720 (1 month). @TranslateMe@

"logfile"
- Protokollieren Sie alle reCAPTCHA versucht? Geben Sie einen Dateinamen an oder lassen Sie die Option zum Deaktivieren leer.

*Nützlicher Tipp: Wenn du willst, Sie können Datum/Uhrzeit-Information den Namen der Protokolldateien anhängen, durch diese im Namen einschließlich: `{yyyy}` für die komplette Jahr, `{yy}` für abgekürzte Jahr, `{mm}` für Monate, `{dd}` für Tag, `{hh}` für Stunde.*

*Beispielen:*
- *`logfile='recaptcha.{yyyy}-{mm}-{dd}-{hh}.txt'`*

####"template_data" (Kategorie)
Anweisungen/Variablen für Templates und Themes.

Template-Daten bezieht sich auf die HTML-Ausgabe die verwendet wird, um die "Zugriff verweigert"-Nachricht Benutzern anzuzeigen, wenn eine hochgeladene Datei blockiert wird. Falls Sie benutzerdefinierte Themes für CIDRAM verwenden, wird die HTML-Ausgabe von der `template_custom.html`-Datei verwendet, ansonsten wird die HTML-Ausgabe von der `template.html`-Datei verwendet. Variablen, die in diesem Bereich der Konfigurations-Datei festgelegt werden, werden als HTML-Ausgabe geparst, indem jede Variable mit geschweiften Klammern innerhalb der HTML-Ausgabe mit den entsprechenden Variablen-Daten ersetzt wird. Zum Beispiel, wenn `foo="bar"`, dann wird jedes Exemplar mit `<p>{foo}</p>` innerhalb der HTML-Ausgabe zu `<p>bar</p>`.

"css_url"
- Die Template-Datei für benutzerdefinierte Themes verwendet externe CSS-Regeln, wobei die Template-Datei für das normale Theme interne CSS-Regeln verwendet. Um CIDRAM anzuweisen, die Template-Datei für benutzerdefinierte Themes zu verwenden, geben Sie die öffentliche HTTP-Adresse von den CSS-Dateien des benutzerdefinierten Themes mit der `css_url`-Variable an. Wenn Sie diese Variable leer lassen, wird CIDRAM die Template-Datei für das normale Theme verwenden.

---


###7. <a name="SECTION7"></a>SIGNATURENFORMAT

####7.0 GRUNDLAGEN

A description of the format and structure of the signatures used by CIDRAM can be found documented in plain-text within either of the two custom signature files. Please refer to that documentation to learn more about the format and structure of the signatures of CIDRAM.

Alle IPv4 Signaturen folgen dem Format: `xxx.xxx.xxx.xxx/yy [Funktion] [Param]`.
- `xxx.xxx.xxx.xxx` represents the beginning of the CIDR block (the octets of the initial IP address in the block).
- `yy` represents the CIDR block size [1-32].
- `[Function]` instructs the script what to do with the signature (how the signature should be regarded).
- `[Param]` represents whatever additional information may be required by `[Function]`.

Alle IPv6 Signaturen folgen dem Format: `xxx.xxx.xxx.xxx/yy [Funktion] [Param]`.
- `xxxx:xxxx:xxxx:xxxx::xxxx` represents the beginning of the CIDR block (the octets of the initial IP address in the block). Complete notation and abbreviated notation are both acceptable (and each MUST follow the appropriate and relevant standards of IPv6 notation, but with one exception: an IPv6 address can never begin with an abbreviation when used in a signature for this script, due to the way in which CIDRs are reconstructed by the script; For example, `::1/128` should be expressed, when used in a signature, as `0::1/128`, and `::0/128` expressed as `0::/128`).
- `yy` represents the CIDR block size [1-128].
- `[Function]` instructs the script what to do with the signature (how the signature should be regarded).
- `[Param]` represents whatever additional information may be required by `[Function]`.

The signature files for CIDRAM SHOULD use Unix-style linebreaks (`%0A`, or `\n`)! Other types/styles of linebreaks (eg, Windows `%0D%0A` or `\r\n` linebreaks, Mac `%0D` or `\r` linebreaks, etc) MAY be used, but are NOT preferred. Non-Unix-style linebreaks will be normalised to Unix-style linebreaks by the script.

Precise and correct CIDR notation is required, otherwise the script will NOT recognise the signatures. Additionally, all the CIDR signatures of this script MUST begin with an IP address whose IP number can divide evenly into the block division represented by its CIDR block size (eg, if you wanted to block all IPs from `10.128.0.0` to `11.127.255.255`, `10.128.0.0/8` would NOT be recognised by the script, but `10.128.0.0/9` and `11.0.0.0/9` used in conjunction, WOULD be recognised by the script).

Anything in the signature files not recognised as a signature nor as signature-related syntax by the script will be IGNORED, therefore meaning that you can safely put any non-signature data that you want into the signature files without breaking them and without breaking the script. Comments are acceptable in the signature files, and no special formatting is required for them. Shell-style hashing for comments is preferred, but not enforced; Functionally, it makes no difference to the script whether or not you choose to use Shell-style hashing for comments, but using Shell-style hashing helps IDEs and plain-text editors to correctly highlight the various parts of the signature files (and so, Shell-style hashing can assist as a visual aid while editing).

Die möglichen Werte von `[Funktion]` sind wie folgt:
- Run
- Whitelist
- Greylist
- Deny

If "Run" is used, when the signature is triggered, the script will attempt to execute (using a `require_once` statement) an external PHP script, specified by the `[Param]` value (the working directory should be the "/vault/" directory of the script).

Beispiel: `127.0.0.0/8 Run example.php`

This can be useful if you want to execute some specific PHP code for some specific IPs and/or CIDRs.

If "Whitelist" is used, when the signature is triggered, the script will reset all detections (if there's been any detections) and break the test function. `[Param]` is ignored. This function is the equivalent of whitelisting a particular IP or CIDR from being detected.

Beispiel: `127.0.0.1/32 Whitelist`

If "Greylist" is used, when the signature is triggered, the script will reset all detections (if there's been any detections) and skip to the next signature file to continue processing. `[Param]` is ignored.

Beispiel: `127.0.0.1/32 Greylist`

If "Deny" is used, when the signature is triggered, assuming no whitelist signature has been triggered for the given IP address and/or given CIDR, access to the protected page will be denied. "Deny" is what you'll want to use to actually block an IP address and/or CIDR range. When any signatures are triggered that make use of "Deny", the "Access Denied" page of the script will be generated and the request to the protected page killed.

The `[Param]` value accepted by "Deny" will be parsed to the "Access Denied" page output, supplied to the client/user as the cited reason for their access to the requested page being denied. It can be either a short and simple sentence, explaining why you've chosen to block them (anything should suffice, even a simple "I don't want you on my website"), or one of a small handful of shorthand words supplied by the script, that if used, will be replaced by the script with a pre-prepared explanation of why the client/user has been blocked.

The pre-prepared explanations have L10N support and can be translated by the script based upon the language you specify to the `lang` directive of the script configuration. Additionally, you can instruct the script to ignore "Deny" signatures based upon their `[Param]` value (if they're using these shorthand words) via the directives specified by the script configuration (each shorthand word has a corresponding directive to either process the corresponding signatures or to ignore them). `[Param]` values that don't use these shorthand words, however, don't have L10N support and therefore WON'T be translated by the script, and additionally, aren't directly controllable by the script configuration.

The available shorthand words are:
- Bogon
- Cloud
- Generic
- Proxy
- Spam

####7.1 TAGS

If you want to split your custom signatures into individual sections, you can identify these individual sections to the script by adding a "section tag" immediately after the signatures of each section, along with the name of your signature section (siehe Beispiel unten).

```
# Sektion 1.
1.2.3.4/32 Deny Bogon
2.3.4.5/32 Deny Cloud
4.5.6.7/32 Deny Generic
5.6.7.8/32 Deny Spam
6.7.8.9/32 Deny Proxy
Tag: Sektion 1
```

To break section tagging and to ensure that tags aren't incorrectly identified to signature sections from earlier in the signature files, simply ensure that there are at least two consecutive linebreaks between your tag and your earlier signature sections. Any untagged signatures will default to either "IPv4" or "IPv6" (depending on which types of signatures are being triggered).

```
1.2.3.4/32 Deny Bogon
2.3.4.5/32 Deny Cloud

4.5.6.7/32 Deny Generic
5.6.7.8/32 Deny Spam
Tag: Sektion 1
```

In dem obigen Beispiel `1.2.3.4/32` und `2.3.4.5/32` wird markiert als "IPv4", wohingegen `4.5.6.7/32` und `5.6.7.8/32` wird markiert als "Sektion 1".

If you want signatures to expire after some time, in a similar manner to section tags, you can use an "expiry tag" to specify when signatures should cease to be valid. Expiry tags use the format "JJJJ.MM.TT" (siehe Beispiel unten).

```
# Sektion 1.
1.2.3.4/32 Deny Generic
2.3.4.5/32 Deny Generic
Expires: 2016.12.31
```

Sektion-Tags und Ablauf-Tags kann zusammen verwendet werden, und beide sind optional (siehe Beispiel unten).

```
# Beispiel Sektion.
1.2.3.4/32 Deny Generic
Tag: Beispiel Sektion
Expires: 2016.12.31
```

####7.2 YAML

#####7.2.0 YAML GRUNDLAGEN

A simplified form of YAML markup may be used in signature files for the purpose of defining behaviours and settings specific to individual signature sections. This may be useful if you want the value of your configuration directives to differ on the basis of individual signatures and signature sections (for example; if you want to supply an email address for support tickets for any users blocked by one particular signature, but don't want to supply an email address for support tickets for users blocked by any other signatures; if you want some specific signatures to trigger a page redirect; if you want to mark a signature section for use with reCAPTCHA; if you want to log blocked access attempts to separate files on the basis of individual signatures and/or signature sections).

Use of YAML markup in the signature files is entirely optional (dh, you may use it if you wish to do so, but you are not required to do so), and is able to leverage most (but not all) configuration directives.

Note: YAML markup implementation in CIDRAM is very simplistic and very limited; It is intended to fulfill requirements specific to CIDRAM in a manner that has the familiarity of YAML markup, but neither follows nor complies with official specifications (and therefore won't behave in the same way as more thorough implementations elsewhere, and may not be appropriate for other projects elsewhere).

In CIDRAM, YAML markup segments are identified to the script by three dashes ("---"), and terminate alongside their containing signature sections by double-linebreaks. A typical YAML markup segment within a signature section consists of three dashes on a line immediately after the list of CIDRS and any tags, followed by a two dimensional list of key-value pairs (first dimension, configuration directive categories; second dimension, configuration directives) for which configuration directives should be modified (and to which values) whenever a signature within that signature section is triggered (see the examples below).

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

#####7.2.1 WIE MAN "SPEZIELL MARKIEREN" DEN SIGNATUR-SEKTIONEN FÜR DIE VERWENDUNG MIT reCAPTCHA

When "usemode" is 0 or 1, signature sections don't need to be "specially marked" for use with reCAPTCHA (because they already either will or won't use reCAPTCHA, depending on this setting).

When "usemode" is 2, to "specially mark" signature sections for use with reCAPTCHA, an entry is included in the YAML segment for that signature section (see the example below).

```
# In diesem Abschnitt wird reCAPTCHA verwendet.
1.2.3.4/32 Deny Generic
2.3.4.5/32 Deny Generic
Tag: reCAPTCHA-Enabled
---
recaptcha:
 enabled: true
```

Note: A reCAPTCHA-Instanz will ONLY be offered to the user if reCAPTCHA is enabled (either with "usemode" as 1, or "usemode" as 2 with "enabled" as true), and if exactly ONE signature has been triggered (no more, no less; if multiple signatures are triggered, a reCAPTCHA-Instanz will NOT be offered).

####7.3 ZUSATZINFORMATION

In addition, if you want CIDRAM to completely ignore some specific sections within any of the signature files, you can use the `ignore.dat` file to specify which sections to ignore. On a new line, write `Ignore`, followed by a space, followed by the name of the section that you want CIDRAM to ignore (siehe Beispiel unten).

```
Ignore Sektion 1
```

Wenden Sie sich an den benutzerdefinierten Signaturdateien für weitere Informationen.

---


###8. <a name="SECTION8"></a>HÄUFIG GESTELLTE FRAGEN (FAQ)

####Was ist ein "Falsch-Positiv"?

Der Begriff "Falsch-Positiv" (*Alternative: "falsch-positiv Fehler"; "falscher Alarm"*; Englisch: *false positive*; *false positive error*; *false alarm*), sehr einfach beschrieben, und in einem verallgemeinerten Kontext, verwendet wird, wenn eine Bedingung zu testen und wenn die Ergebnisse positiv sind, um die Ergebnisse dieser Tests zu entnehmen (dh, die Bedingung bestimmt wird positiv oder wahr), aber sind zu erwarten sein (oder sollte gewesen) negativ (dh, der Zustand, in Wirklichkeit, ist negativ oder falsch). Eine "Falsch-Positiv" könnte analog zu "weinen Wolf" betrachtet (wobei die Bedingung geprüft wird, ob es ein Wolf in der Nähe der Herde ist, die Bedingung "falsch" ist in dass es keinen Wolf in der Nähe der Herde, und die Bedingung wird als "positiv" berichtet durch die Schäfer durch Aufruf "Wolf, Wolf"), oder analog zu Situationen in medizinischen Tests, wobei ein Patient als mit eine Krankheit diagnostiziert, wenn sie in Wirklichkeit haben sie keine solche Krankheit.

Einige andere Begriffe verwendet: "Wahr-Positiv", "Wahr-Negativ" und "Falsch-Negativ". Eine "Wahr-Positiv" ist, wenn die Ergebnisse des Tests und der wahren Zustand beide wahr sind (oder "Positiv"), und eine "Wahr-Negativ" ist, wenn die Ergebnisse des Tests und der wahren Zustand beide falsch sind (oder "Negativ"); Eine "Wahr-Positiv" oder Eine "Wahr-Negativ" gilt als eine "korrekte Folgerung" zu sein. Der Antithese von einem "Falsch-Positiv" ist eine "Falsch-Negativ"; Eine "Falsch-Negativ" ist, wenn die Ergebnisse des Tests negativ sind (dh, die Bedingung bestimmt wird negativ oder falsch zu sein), aber sind zu erwarten sein (oder sollte gewesen) positiv (dh, der Zustand, in Wirklichkeit, ist "positiv", oder "wahr").

Im Kontext der CIDRAM, Diese Begriffe beziehen sich auf der Signaturen von CIDRAM, und was/wen sie blockieren. Wenn CIDRAM Blöcke eine IP-Adresse wegen schlechten, veraltete oder falsche Signaturen, sollte aber nicht so getan haben, oder wenn sie es tut, so aus den falschen Gründen, wir beziehen sich auf dieses Ereignis als eine "Falsch-Positiv". Wenn CIDRAM, aufgrund unvorhergesehener Bedrohungen, fehlende Signaturen oder Defizite in ihren Signaturen, versagt eine IP-Adresse zu blockieren, die blockiert werden sollte, wir beziehen sich auf dieses Ereignis als eine "verpasste Erkennung" (das entspricht einem "Falsch-Negativ").

Dies kann durch die folgende Tabelle zusammengefasst werden:

&nbsp; | CIDRAM sollte *KEINE* IP-Adresse blockieren | CIDRAM *SOLLTE* eine IP-Adresse blockieren
---|---|---
CIDRAM tut blockiert eine IP-Adresse *NICHT* | Wahr-Negativ (korrekte Folgerung) | Verpasste Erkennung (analog zu Falsch-Negativ)
CIDRAM *TUT* blockiert eine IP-Adresse | __Falsch-Positiv__ | True-Positiv (korrekte Folgerung)

---


Zuletzt aktualisiert: 28. November 2016 (2016.11.28).
