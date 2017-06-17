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


### 1. <a name="SECTION1"></a>VORWORT

CIDRAM (Classless Inter-Domain Routing Access Manager) ist ein PHP-Skript gestaltete für Websites zu schützen, durch das Blockieren von IP-Adressen als Quellen von unerwünschten Verkehr angesehen, einschließlich (aber nicht beschränkt auf) Datenverkehr von nicht-menschlichen Zugang Endpunkte, Cloud-Services, spambots, Website Schaber, usw. Es tut dies durch die möglichen CIDRs von IP-Adressen zu berechnen, von eingehenden Anfragen geliefert, und dann versuchen, diese gegen ihre Signaturdateien zu entsprechen (Diese Signaturdateien enthalten Listen von CIDRs von IP-Adressen als Ursachen für unerwünschte Verkehrs angesehen); Wenn Übereinstimmungen gefunden werden, die Anfragen ist blockiert.

*(Beziehen auf: [Was ist ein "CIDR"?](#WHAT_IS_A_CIDR)).*

CIDRAM COPYRIGHT 2016 und darüber hinaus GNU/GPLv2 by Caleb M (Maikuolan).

Dieses Skript ist freie Software; Sie können Sie weitergeben und/oder modifizieren unter den Bedingungen der GNU General Public License, wie von der Free Software Foundation veröffentlicht; entweder unter Version 2 der Lizenz oder (nach Ihrer Wahl) jeder späteren Version. Dieses Skript wird in der Hoffnung verteilt, dass es nützlich sein wird, allerdings OHNE JEGLICHE GARANTIE; ohne implizite Garantien für VERMARKTUNG/VERKAUF/VERTRIEB oder FÜR EINEN BESTIMMTEN ZWECK. Lesen Sie die GNU General Public License für weitere Details, in der Datei `LICENSE.txt`, ebenfalls verfügbar auf:
- <http://www.gnu.org/licenses/>.
- <http://opensource.org/licenses/>.

Dieses Dokument und das zugehörige Paket kann von folgenden Links kostenlos heruntergeladen werden [GitHub](https://cidram.github.io/).

---


### 2. <a name="SECTION2"></a>INSTALLATION

#### 2.0 MANUELL INSTALLIEREN

1) Entpacken Sie das heruntergeladene Archiv auf Ihren lokalen PC. Erstellen Sie ein Verzeichnis, wohin Sie den Inhalt dieses Paketes auf Ihrem Host oder CMS installieren möchten. Ein Verzeichnis wie `/public_html/cidram/` o.ä. genügt, solange es Ihren Sicherheitsbedürfnissen oder persönlichen Präferenzen entspricht.

2) Die Datei `config.ini.RenameMe` (im `vault`-Verzeichnis) zu `config.ini` umbenennen, und optional (empfohlen für erfahrene Anwender, nicht empfohlen für Anwender ohne entsprechende Kenntnisse), öffnen Sie diese Datei (diese Datei beinhaltet alle funktionalen Optionen für CIDRAM; über jeder Option beschreibt ein kurzer Kommentar die Aufgabe dieser Option). Verändern Sie die Werte nach Ihren Bedürfnissen. Speichern und schließen Sie die Datei.

3) Laden Sie den kompletten Inhalt (CIDRAM und die Dateien) in das Verzeichnis hoch, für das Sie sich in Schritt 1 entschieden haben. Die Dateien `*.txt`/`*.md` müssen nicht mit hochgeladen werden.

4) Ändern Sie die Zugriffsberechtigungen des `vault`-Verzeichnisses auf "755" (wenn es Probleme gibt, Sie können "777" versuchen; Dies ist weniger sicher, obwohl). Die Berechtigungen des übergeordneten Verzeichnises, in welchem sich der Inhalt befindet (das Verzeichnis, wofür Sie sich entschieden haben), können so belassen werden, überprüfen Sie jedoch die Berechtigungen, wenn in der Vergangenheit Zugriffsprobleme aufgetreten sind (Voreinstellung "755" o.ä.).

5) Binden Sie CIDRAM in Ihr System oder CMS ein. Es gibt viele verschiedene Möglichkeiten, ein Script wie CIDRAM einzubinden, am einfachsten ist es, das Script am Anfang einer Haupt-Datei (eine Datei, die immer geladen wird, wenn irgend eine beliebige Seite Ihres Webauftritts aufgerufen wird) Ihres Systems oder CMS mit Hilfe des require- oder include-Befehls einzubinden. Üblicherweise wird eine solche Datei in Verzeichnissen wie `/includes`, `/assets` or `/functions` gespeichert und wird häufig `init.php`, `common_functions.php`, `functions.php` o.ä. genannt. Sie müssen herausfinden, welche Datei dies für Ihre Bedürfnisse ist; Wenn Sie dabei Schwierigkeiten haben das herauszufinden, besuchen Sie die CIDRAM Issues-Seiten bei GitHub und lassen Sie es uns wissen; Es ist möglich, dass entweder ich oder ein anderer Benutzer mit dem CMS, das Sie verwenden, Erfahrung hat (Sie müssen Sie mitteilen, welche CMS Sie verwenden) und möglicherweise in der Lage ist, etwas Unterstützung anzubieten. Fügen Sie in dieser Datei folgenden Code direkt am Anfang ein:

`<?php require '/user_name/public_html/cidram/loader.php'; ?>`

Ersetzen Sie den String zwischen den Anführungszeichen mit dem lokalen Pfad der Datei `loader.php`, nicht mit der HTTP-Adresse (ähnlich dem Pfad für das `vault`-Verzeichnis). Speichern und schließen Sie die Datei, laden Sie sie ggf. erneut hoch.

-- ODER ALTERNATIV --

Wenn Sie einen Apache-Webserver haben und wenn Sie Zugriff auf die `php.ini` oder eine ähnliche Datei haben, dann können Sie die `auto_prepend_file` Direktive verwenden um CIDRAM voranstellen wenn eine PHP-Anfrage erfolgt. Ungefähr so:

`auto_prepend_file = "/user_name/public_html/cidram/loader.php"`

Oder das in der `.htaccess` Datei:

`php_value auto_prepend_file "/user_name/public_html/cidram/loader.php"`

6) Das ist alles! :-)

#### 2.1 INSTALLATION MIT COMPOSER

[CIDRAM ist bei Packagist registriert](https://packagist.org/packages/cidram/cidram), und so, wenn Sie mit Composer vertraut sind, können Sie Composer verwenden, um CIDRAM zu installieren (musst Sie dennoch die Konfiguration und Hooks aber vorbereiten; Siehe "manuell installieren" der Schritte 2 und 5).

`composer require cidram/cidram`

#### 2.2 FÜR WORDPRESS INSTALLIEREN

Wenn Sie CIDRAM mit WordPress verwenden möchten, können Sie alle Anweisungen oben ignorieren. [CIDRAM ist als Plugin mit der WordPress Plugin Datenbank registriert](https://wordpress.org/plugins/cidram/), und Sie kannst CIDRAM direkt aus dem Plugins-Dashboard installieren. Sie können es in der gleichen Weise wie jedes andere Plugin installieren, und es sind keine zusätzlichen Schritte erforderlich. Genauso wie bei den anderen Installationsmethoden, Sie können Ihre Installation anpassen, indem Sie den Inhalt der `config.ini`-Datei, oder indem Sie die Front-End-Konfigurationsseite verwenden. Wenn Sie das Front-End für CIDRAM aktivieren und CIDRAM mit der Front-End-Aktualisierungen-Seite aktualisieren, dies wird automatisch mit den Plugin-Versionsinformationen synchronisiert, die im Plugins-Dashboard angezeigt werden.

*Warnung: Die Aktualisierung von CIDRAM über das Plugins-Dashboard führt zu einer sauberen Installation! Wenn Sie Ihre Installation angepasst haben (änderte deine Konfiguration, installierte Module, usw), diese Anpassungen werden bei wenn der Aktualisierung über das Plugins-Dashboard verloren! Protokolldateien werden auch bei wenn der Aktualisierung über das Plugins-Dashboard verloren! Um Protokolldateien und Anpassungen zu bewahren, aktualisieren Sie über die CIDRAM-Front-End-Aktualisierungen-Seite.*

---


### 3. <a name="SECTION3"></a>BENUTZUNG

CIDRAM sollte automatisch unerwünschte Zugriffe auf Ihre Website blockieren, ohne manuelle Unterstützung erforderlich, abgesehen von seiner Erstinstallation.

Die Aktualisierung erfolgt manuell, und Sie können Ihre Konfiguration anpassen, und Sie können welche CIDRs blockiert festlegen, durch Sie Ihre Konfigurationsdatei und/oder Signaturdateien Modifizieren.

Wenn Sie Falsch-Positivs begegnen, bitte kontaktieren Sie mich zu informieren.

---


### 4. <a name="SECTION4"></a>FRONT-END-MANAGEMENT

#### 4.0 WAS IST DAS FRONT-END.

Das Front-End bietet eine bequeme und einfache Möglichkeit, für Ihre CIDRAM-Installation zu pflegen, zu verwalten und zu aktualisieren. Sie können Protokolldateien über die Protokollseite anzeigen, teilen und herunterladen, Sie können die Konfiguration über die Konfigurationsseite ändern, Sie können Komponenten über die Updates-Seite installieren und deinstallieren, und Sie können Dateien in Ihrem vault über den Dateimanager hochladen, herunterladen und ändern.

Das Front-End ist standardmäßig deaktiviert, um unautorisiert Zugriff zu verhindern (unautorisiert Zugriff könnte erhebliche Konsequenzen für Ihre Website und ihre Sicherheit haben). Aktivieren Sie es, indem Sie die unten aufgeführten Anweisungen befolgen.

#### 4.1 WIE AKTIVIEREN SIE DAS FRONT-END.

1) Finden Sie die `disable_frontend`-Direktive in der Datei `config.ini`, und setzen Sie es auf `false` (wird es standardmäßig `true` sein).

2) Greifen Sie `loader.php` aus Ihrem Browser (z.B., `http://localhost/cidram/loader.php`).

3) Einloggen Sie sich mit dem standardmäßig Benutzernamen und Passwort an (admin/password).

Note: Nachdem Sie sich eingeloggt haben, um einen unautorisiert Zugriff auf das Front-End zu verhindern, sollten Sie sofort Ihren Benutzernamen und Ihr Passwort ändern! Dies ist sehr wichtig, weil es möglich ist, beliebigen PHP-Code auf Ihre Website über das Front-End zu hochladen.

#### 4.2 WIE MAN DAS FRONT-END BENUTZT.

Anweisungen sind auf jeder Seite des Front-Ends vorhanden, um die richtige Verwendung und den vorgesehenen Zweck zu erläutern. Wenn Sie weitere Erklärungen oder spezielle Hilfe benötigen, wenden Sie sich bitte an den Support. Alternativ gibt es einige Videos auf YouTube, die durch Demonstration helfen könnte.


---


### 5. <a name="SECTION5"></a>IM PAKET ENTHALTENE DATEIEN

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
/_docs/readme.ko.md | Koreanische Dokumentation.
/_docs/readme.nl.md | Niederländische Dokumentation.
/_docs/readme.pt.md | Portugiesische Dokumentation.
/_docs/readme.ru.md | Russische Dokumentation.
/_docs/readme.ur.md | Urdu Dokumentation.
/_docs/readme.vi.md | Vietnamesische Dokumentation.
/_docs/readme.zh-TW.md | Chinesische Dokumentation (traditionell).
/_docs/readme.zh.md | Chinesische Dokumentation (vereinfacht).
/vault/ | Vault-Verzeichnis (beinhaltet verschiedene Dateien).
/vault/fe_assets/ | Front-End-Daten.
/vault/fe_assets/.htaccess | Ein Hypertext-Access-Datei (in diesem Fall zum Schutz von sensiblen Dateien des Scripts vor einem nicht authorisierten Zugriff).
/vault/fe_assets/_accounts.html | Ein HTML-Template für das Front-End Konten-Seite.
/vault/fe_assets/_accounts_row.html | Ein HTML-Template für das Front-End Konten-Seite.
/vault/fe_assets/_cidr_calc.html | Ein HTML-Template für den CIDR-Rechner.
/vault/fe_assets/_cidr_calc_row.html | Ein HTML-Template für den CIDR-Rechner.
/vault/fe_assets/_config.html | Ein HTML-Template für die Front-End Konfiguration-Seite.
/vault/fe_assets/_config_row.html | Ein HTML-Template für die Front-End Konfiguration-Seite.
/vault/fe_assets/_files.html | Ein HTML-Template für den Dateimanager.
/vault/fe_assets/_files_edit.html | Ein HTML-Template für den Dateimanager.
/vault/fe_assets/_files_rename.html | Ein HTML-Template für den Dateimanager.
/vault/fe_assets/_files_row.html | Ein HTML-Template für den Dateimanager.
/vault/fe_assets/_home.html | Ein HTML-Template für das Front-End Startseite.
/vault/fe_assets/_ip_test.html | Ein HTML-Template für die IP-Test-Seite.
/vault/fe_assets/_ip_test_row.html | Ein HTML-Template für die IP-Test-Seite.
/vault/fe_assets/_ip_tracking.html | Ein HTML-Template für die IP-Tracking-Seite.
/vault/fe_assets/_ip_tracking_row.html | Ein HTML-Template für die IP-Tracking-Seite.
/vault/fe_assets/_login.html | Ein HTML-Template für die Front-End Einloggen-Seite.
/vault/fe_assets/_logs.html | Ein HTML-Template für die Front-End Protokolldateien-Seite.
/vault/fe_assets/_nav_complete_access.html | Ein HTML-Template für die Front-End Navigation-Links, für alle mit vollständiger Zugriff.
/vault/fe_assets/_nav_logs_access_only.html | Ein HTML-Template für die Front-End Navigation-Links, für alle mit Zugriff nur auf Protokolldateien.
/vault/fe_assets/_updates.html | Ein HTML-Template für die Front-End Aktualisierungen-Seite.
/vault/fe_assets/_updates_row.html | Ein HTML-Template für die Front-End Aktualisierungen-Seite.
/vault/fe_assets/frontend.css | CSS-Stylesheet für das Front-End.
/vault/fe_assets/frontend.dat | Datenbank für das Front-End (Enthält Kontoinformationen, Sitzungsinformationen, und dem Cache; nur erzeugt wenn das Frontend aktiviert und verwendet wird).
/vault/fe_assets/frontend.html | Die Haupt-HTML-Template-Datei für das Front-End.
/vault/fe_assets/icons.php | Ikonen-Handler (die vom Front-End-Dateimanager verwendet wird).
/vault/fe_assets/pips.php | Pips-Handler (die vom Front-End-Dateimanager verwendet wird).
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
/vault/lang/lang.hi.cli.php | Hindi Sprachdateien für CLI.
/vault/lang/lang.hi.fe.php | Hindi Sprachdateien für das Front-End.
/vault/lang/lang.hi.php | Hindi Sprachdateien.
/vault/lang/lang.id.cli.php | Indonesische Sprachdateien für CLI.
/vault/lang/lang.id.fe.php | Indonesische Sprachdateien für das Front-End.
/vault/lang/lang.id.php | Indonesische Sprachdateien.
/vault/lang/lang.it.cli.php | Italienische Sprachdateien für CLI.
/vault/lang/lang.it.fe.php | Italienische Sprachdateien für das Front-End.
/vault/lang/lang.it.php | Italienische Sprachdateien.
/vault/lang/lang.ja.cli.php | Japanische Sprachdateien für CLI.
/vault/lang/lang.ja.fe.php | Japanische Sprachdateien für das Front-End.
/vault/lang/lang.ja.php | Japanische Sprachdateien.
/vault/lang/lang.ko.cli.php | Koreanische Sprachdateien für CLI.
/vault/lang/lang.ko.fe.php | Koreanische Sprachdateien für das Front-End.
/vault/lang/lang.ko.php | Koreanische Sprachdateien.
/vault/lang/lang.nl.cli.php | Niederländische Sprachdateien für CLI.
/vault/lang/lang.nl.fe.php | Niederländische Sprachdateien für das Front-End.
/vault/lang/lang.nl.php | Niederländische Sprachdateien.
/vault/lang/lang.pt.cli.php | Portugiesische Sprachdateien für CLI.
/vault/lang/lang.pt.fe.php | Portugiesische Sprachdateien für das Front-End.
/vault/lang/lang.pt.php | Portugiesische Sprachdateien.
/vault/lang/lang.ru.cli.php | Russische Sprachdateien für CLI.
/vault/lang/lang.ru.fe.php | Russische Sprachdateien für das Front-End.
/vault/lang/lang.ru.php | Russische Sprachdateien.
/vault/lang/lang.th.cli.php | Thai Sprachdateien für CLI.
/vault/lang/lang.th.fe.php | Thai Sprachdateien für das Front-End.
/vault/lang/lang.th.php | Thai Sprachdateien.
/vault/lang/lang.tr.cli.php | Türkische Sprachdateien für CLI.
/vault/lang/lang.tr.fe.php | Türkische Sprachdateien für das Front-End.
/vault/lang/lang.tr.php | Türkische Sprachdateien.
/vault/lang/lang.ur.cli.php | Urdu Sprachdateien für CLI.
/vault/lang/lang.ur.fe.php | Urdu Sprachdateien für das Front-End.
/vault/lang/lang.ur.php | Urdu Sprachdateien.
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
/vault/cidramblocklists.dat | Enthält Informationen zu den optionalen Landblocklisten bereitgestellt von Macmathan; Wird von der Aktualisierungsfunktion bereitgestellt durch das Front-End verwendet.
/vault/cli.php | CLI-Handler.
/vault/components.dat | Enthält Informationen zu den verschiedenen Komponenten für CIDRAM; Wird von der Aktualisierungsfunktion bereitgestellt durch das Front-End verwendet.
/vault/config.ini.RenameMe | Konfigurationsdatei; Beinhaltet alle Konfigurationsmöglichkeiten von CIDRAM (umbenennen zu aktivieren).
/vault/config.php | Konfiguration-Handler.
/vault/config.yaml | Standardkonfigurationsdatei; Beinhaltet Standardkonfigurationswerte für CIDRAM.
/vault/frontend.php | Front-End-Handler.
/vault/functions.php | Funktionen-Datei.
/vault/hashes.dat | Enthält eine Liste der akzeptierten Hashes (relevant für die reCAPTCHA-Funktion; nur dann erzeugt wird, wenn die reCAPTCHA-Funktion aktiviert ist).
/vault/ignore.dat | Ignoriert Datei (zu spezifizieren welche Signatur-Sektionen CIDRAM sollte ignorieren es ist benutzt).
/vault/ipbypass.dat | Enthält eine Liste von IP-Bypässe (relevant für die reCAPTCHA-Funktion; nur dann erzeugt wird, wenn die reCAPTCHA-Funktion aktiviert ist).
/vault/ipv4.dat | IPv4 Signaturdatei (unerwünschte Cloud-Services und nicht-menschliche Endpunkte).
/vault/ipv4_bogons.dat | IPv4 Signaturdatei (Bogon/Marsmensch CIDRs).
/vault/ipv4_custom.dat.RenameMe | IPv4 benutzerdefinierte Signaturdatei (umbenennen zu aktivieren).
/vault/ipv4_isps.dat | IPv4 Signaturdatei (gefährliche und spam-anfällig ISPs).
/vault/ipv4_other.dat | IPv4 Signaturdatei (CIDRs für Proxies, VPNs und andere verschiedene unerwünschte Dienste).
/vault/ipv6.dat | IPv6 Signaturdatei (unerwünschte Cloud-Services und nicht-menschliche Endpunkte).
/vault/ipv6_bogons.dat | IPv6 Signaturdatei (Bogon/Marsmensch CIDRs).
/vault/ipv6_custom.dat.RenameMe | IPv6 benutzerdefinierte Signaturdatei (umbenennen zu aktivieren).
/vault/ipv6_isps.dat | IPv6 Signaturdatei (gefährliche und spam-anfällig ISPs).
/vault/ipv6_other.dat | IPv6 Signaturdatei (CIDRs für Proxies, VPNs und andere verschiedene unerwünschte Dienste).
/vault/lang.php | Sprachdateien.
/vault/modules.dat | Enthält Informationen zu den verschiedene Module für CIDRAM; Wird von der Aktualisierungsfunktion bereitgestellt durch das Front-End verwendet.
/vault/outgen.php | Ausgabe-Generator.
/vault/php5.4.x.php | Polyfills für PHP 5.4.X (erforderlich für Abwärtskompatibilität mit PHP 5.4.X; sicher zu löschen für neuere PHP-Versionen).
/vault/recaptcha.php | reCAPTCHA-Modul.
/vault/rules_as6939.php | Benutzerdefinierte Regeldatei für AS6939.
/vault/rules_softlayer.php | Benutzerdefinierte Regeldatei für Soft Layer.
/vault/rules_specific.php | Benutzerdefinierte Regeldatei für einige spezifische CIDRs.
/vault/salt.dat | Salz-Datei (durch einigen periphere Funktionalität verwendet; nur dann erzeugt wenn erforderlich).
/vault/template_custom.html | Template Datei; Template für die HTML-Ausgabe durch der CIDRAM Ausgabe-Generator erzeugt.
/vault/template_default.html | Template Datei; Template für die HTML-Ausgabe durch der CIDRAM Ausgabe-Generator erzeugt.
/vault/themes.dat | Themen-Datei; Wird von der Aktualisierungsfunktion bereitgestellt durch das Front-End verwendet.
/.gitattributes | Ein GitHub Projektdatei (für die korrekte Funktion des Scripts nicht notwendig).
/Changelog.txt | Eine Auflistung der Änderungen des Scripts der verschiedenen Versionen (für die korrekte Funktion des Scripts nicht notwendig).
/composer.json | Composer/Packagist Informationen (für die korrekte Funktion des Scripts nicht notwendig).
/CONTRIBUTING.md | Wie Sie dazu beitragen für das Projekt.
/LICENSE.txt | Eine Kopie der GNU/GPLv2 Lizenz (für die korrekte Funktion des Scripts nicht notwendig).
/loader.php | Loader. Diese Datei wird in Ihr CMS eingebunden (notwendig)!
/README.md | Projektübersicht.
/web.config | Eine ASP.NET-Konfigurationsdatei (in diesem Fall zum Schutz des Verzeichnisses `/vault` vor einem nicht authorisierten Zugriff, sofern das Script auf einem auf der ASP.NET-Technologie basierenden Server installiert wurde).

---


### 6. <a name="SECTION6"></a>EINSTELLUNGEN
Nachfolgend finden Sie eine Liste der Variablen in der Konfigurationsdatei `config.ini` mit einer kurzen Beschreibung ihrer Funktionen.

#### "general" (Kategorie)
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

"truncate"
- Trunkate Protokolldateien, wenn sie eine bestimmte Größe erreichen? Wert ist die maximale Größe in B/KB/MB/GB/TB, die eine Protokolldatei wachsen kann, bevor sie trunkiert wird. Der Standardwert von 0KB deaktiviert die Trunkierung (Protokolldateien können unbegrenzt wachsen). Hinweis: Gilt für einzelne Protokolldateien! Die Größe der Protokolldateien gilt nicht als kollektiv.

"timeOffset"
- Wenn Ihr Server-Zeit nicht mit Ihrer Ortszeit, Sie können einen Offset hier angeben (Dies ist das Datum/Zeit-Informationen anpassen, die durch CIDRAM erzeugt wird). Es ist in der Regel statt zur Einstellung der Zeitzone Richtlinie in Ihrer Datei `php.ini` empfohlen, aber manchmal (wie wenn Sie mit begrenzten Shared-Hosting-Provider arbeiten) dies ist nicht immer möglich zu tun, und so, ist diese Option hier zur Verfügung gestellt.
- Beispiel (eine Stunde hinzufügen): `timeOffset=60`

"timeFormat"
- Das Datumsformat verwendet von CIDRAM. Standardeinstellung = `{Day}, {dd} {Mon} {yyyy} {hh}:{ii}:{ss} {tz}`.

"ipaddr"
- Ort der IP-Adresse der aktuellen Verbindung im gesamten Datenstrom (nützlich für Cloud-Services). Standardeinstellung = REMOTE_ADDR. ACHTUNG: Ändern Sie diesen Wert nur, wenn Sie wissen, was Sie tun!

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

"ban_override"
- Überschreiben "forbid_on_block" Wenn "infraction_limit" überschritten wird? Beim überschreiben: Blockiert Anfragen geben eine leere Seite zurück (Template-Dateien werden nicht verwendet). 200 = Nicht überschreiben [Standardeinstellung]; 403 = Überschreiben mit "403 Forbidden"; 503 = Überschreiben mit "503 Service unavailable".

"log_banned_ips"
- Enthalten Sie blockierte Anfragen von verbotenen IPs in die Protokolldateien? True = Ja [Standardeinstellung]; False = Nein.

"default_dns"
- Eine durch Kommata getrennte Liste von DNS-Servern, die für Hostnamen-Lookups verwendet werden sollen. Standardeinstellung = "8.8.8.8,8.8.4.4" (Google DNS). ACHTUNG: Ändern Sie diesen Wert nur, wenn Sie wissen, was Sie tun!

"search_engine_verification"
- Versuche, Anfragen von Suchmaschinen zu überprüfen? Die Überprüfung der Suchmaschinen sorgt dafür, dass sie nicht als Folge der Maximale Anzahl von Verstöße verboten werden (Verbot der Suchmaschinen von Ihrer Website wird in der Regel einen negativen Effekt auf Ihre Suchmaschinen-Ranking, SEO und u.s.w. haben). Wenn überprüft, wie pro normal, Suchmaschinen können blockiert werden, aber sie werden nicht verboten. Wenn nicht überprüft, es ist möglich, dass sie verboten als Folge der Überschreitung der Maximale Anzahl von Verstöße werden können. Zusätzlich, Suchmaschinen-Überprüfung bietet Schutz gegen gefälschte Suchmaschinen-Anfragen und gegen potenziell böswillige Entitäten, die sich als Suchmaschinen maskieren (solche Anfragen werden blockiert, wenn die Suchmaschinen-Überprüfung aktiviert ist). True = Suchmaschinen-Überprüfung aktivieren [Standardeinstellung]; False = Suchmaschinen-Überprüfung deaktivieren.

"protect_frontend"
- Gibt an, ob die Schutzmaßnahmen normalerweise vom CIDRAM bereitgestellten auf das Frontend angewendet werden sollen. True = Ja [Standardeinstellung]; False = Nein.

"disable_webfonts"
- Web-Fonts deaktivieren? True = Ja; False = Nein [Standardeinstellung].

#### "signatures" (Kategorie)
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

"modules"
- Eine Liste der Moduldateien zu laden nach der Prüfung der IPv4/IPv6-Signaturen, durch Kommas begrenzt.

"default_tracktime"
- Wie viele Sekunden, um IPs von Modulen verboten zu verfolgen. Standardeinstellung = 604800 (1 Woche).

"infraction_limit"
- Maximale Anzahl von Verstöße, die eine IP zulassen darf, bevor sie durch IP-Tracking verboten ist. Standardeinstellung = 10.

"track_mode"
- Wann sollten Verstöße gezählt werden? False = Wenn IPs von Modulen blockiert werden. True = Wenn IPs von irgendeinem Grund blockiert werden.

#### "recaptcha" (Kategorie)
Wenn du willst, können Sie Benutzern bieten eine Möglichkeit zur umgehen der Seite "Zugriff verweigert" durch Abschluss einer reCAPTCHA-Instanz. Dies kann helfen, einige der Risiken im Zusammenhang mit Falsch-Positivs zu mildern, in diesen Fällen wodurch wir nicht ganz sicher sind ob eine Anfrage von einer Maschine oder einem Menschen stammt.

Aufgrund der Risiken im Zusammenhang mit der Bereitstellung eines Wegs um der Seite "Zugriff verweigert" zur umgehen, im Algemeinen, Ich würde raten dies zu nicht ermöglichen es sei denn, Sie empfinden es als notwendig dies zu tun. Situationen wo es notwendig könnte sein: Wenn Ihre Website hat Kunden/Benutzer, die Zugriff auf Ihre Website haben müssen, und wenn dies etwas ist das nicht ausgehandelt werden kann, aber wenn diese Kunden/Benutzer von einem feindlichen Netzwerk zu verbinden, das möglicherweise auch unerwünschten Verkehr führen könnte, und die Blockierung dieser unerwünschten Verkehr ist auch etwas das nicht ausgehandelt werden kann, in diesen Situationen, könnte das reCAPTCHA-Feature nützlich sein um die wünschenswerten Kunden/Benutzer zu ermöglichen, während der unerwünschten Verkehr aus demselben Netzwerk ausgeschlossen ist. Das sagte aber, da der beabsichtigte Zweck einer CAPTCHA ist, zwischen Menschen und Nicht-Menschen zu unterscheiden, das reCAPTCHA-Feature würde nur in diesen Situationen ohne Gewinner zu helfen, wenn wir annehmen dass dieser unerwünschten Verkehr nicht menschlich ist (z.B, Spambots, Scrapers, Hacktools, automatisierten Datenverkehr), im Gegensatz zu unerwünschtem menschlichen Verkehr (wie menschliche Spammer, Hacker, und derartige).

Um einen "site key" und einen "secret key" zu erhalten (für die Verwendung von reCAPTCHA erforderlich), bitte gehe zu: [https://developers.google.com/recaptcha/](https://developers.google.com/recaptcha/)

"usemode"
- Dies definiert wie CIDRAM sollte reCAPTCHA benutzen.
- 0 = reCAPTCHA ist komplett deaktiviert (Standardeinstellung).
- 1 = reCAPTCHA ist für alle Signaturen aktiviert.
- 2 = reCAPTCHA ist aktiviert, nur für Signaturen in Sektionen markiert als reCAPTCHA-aktiviert in den Signaturdateien.
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
- Wenn "lockuser" true ist (Standardeinstellung), um sich zu erinnern wann ein Benutzer eine reCAPTCHA-Instanz erfolgreich bestanden hat, für zukünftige Seite-Anfragen, generiert CIDRAM ein Standard-HTTP-Cookie, dass einen Hash enthält, entsprechend einem internen Datensatz, dass der denselben Hash enthält; Zukünftige Seite-Anfragen werden diese entsprechenden Hashes verwenden, zu authentifizieren dass ein Benutzer zuvor bereits eine reCAPTCHA-Instanz erfolgreich bestanden hat. Wenn "lockuser" false ist, wird eine IP-Whitelist verwendet, um festzustellen ob Anfragen aus der IP von eingehenden Anfragen, zugelassen werden sollen; Einträge werden zu dieser Whitelist hinzugefügt wenn die reCAPTCHA-Instanz erfolgreich bestanden ist. Für wie viele Stunden sollten diese Cookies, Hashes und Whitelist-Einträge gültig bleiben? Standardeinstellung = 720 (1 Monat).

"logfile"
- Protokollieren Sie alle reCAPTCHA versucht? Geben Sie einen Dateinamen an oder lassen Sie die Option zum Deaktivieren leer.

*Nützlicher Tipp: Wenn du willst, Sie können Datum/Uhrzeit-Information den Namen der Protokolldateien anhängen, durch diese im Namen einschließlich: `{yyyy}` für die komplette Jahr, `{yy}` für abgekürzte Jahr, `{mm}` für Monate, `{dd}` für Tag, `{hh}` für Stunde.*

*Beispielen:*
- *`logfile='recaptcha.{yyyy}-{mm}-{dd}-{hh}.txt'`*

#### "template_data" (Kategorie)
Anweisungen/Variablen für Templates und Themes.

Template-Daten bezieht sich auf die HTML-Ausgabe die verwendet wird, um die "Zugriff verweigert"-Nachricht Benutzern anzuzeigen, wenn eine hochgeladene Datei blockiert wird. Falls Sie benutzerdefinierte Themes für CIDRAM verwenden, wird die HTML-Ausgabe von der `template_custom.html`-Datei verwendet, ansonsten wird die HTML-Ausgabe von der `template.html`-Datei verwendet. Variablen, die in diesem Bereich der Konfigurations-Datei festgelegt werden, werden als HTML-Ausgabe geparst, indem jede Variable mit geschweiften Klammern innerhalb der HTML-Ausgabe mit den entsprechenden Variablen-Daten ersetzt wird. Zum Beispiel, wenn `foo="bar"`, dann wird jedes Exemplar mit `<p>{foo}</p>` innerhalb der HTML-Ausgabe zu `<p>bar</p>`.

"theme"
- Standard-Thema für CIDRAM verwenden.

"css_url"
- Die Template-Datei für benutzerdefinierte Themes verwendet externe CSS-Regeln, wobei die Template-Datei für das normale Theme interne CSS-Regeln verwendet. Um CIDRAM anzuweisen, die Template-Datei für benutzerdefinierte Themes zu verwenden, geben Sie die öffentliche HTTP-Adresse von den CSS-Dateien des benutzerdefinierten Themes mit der `css_url`-Variable an. Wenn Sie diese Variable leer lassen, wird CIDRAM die Template-Datei für das normale Theme verwenden.

---


### 7. <a name="SECTION7"></a>SIGNATURENFORMAT

#### 7.0 GRUNDLAGEN

Eine Beschreibung das Formats und die Struktur der Signaturen benutzt von CIDRAM kann gefunden im Klartext dokumentiert werden in der benutzerdefinierten Signaturdateien. Bitte beachten Sie zu dieser Dokumentation, um mehr über das Format und die Struktur der Signaturen für CIDRAM zu erfahren.

Alle IPv4-Signaturen folgen dem Format: `xxx.xxx.xxx.xxx/yy [Funktion] [Param]`.
- `xxx.xxx.xxx.xxx` ist der Anfang des CIDR-Block (die Oktette vom der ursprünglichen IP-Adresse in dem Block).
- `yy` ist der CIDR-Block-Größe [1-32].
- `[Funktion]` weist das Skript an, was mit der Signatur zu tun ist (wie der Signatur zu betrachten).
- `[Param]` ist für jeder zusätzliche Informationen dass für `[Funktion]` erforderlich sein können.

Alle IPv6-Signaturen folgen dem Format: `xxx.xxx.xxx.xxx/yy [Funktion] [Param]`.
- `xxxx:xxxx:xxxx:xxxx::xxxx` ist der Anfang des CIDR-Block (die Oktette vom der ursprünglichen IP-Adresse in dem Block). Komplette Notation und abgekürzt Notation sind beide akzeptabel (und jeder MUSS den entsprechenden und relevanten Standards für IPv6-Notation folgen, aber mit einer Ausnahme: Eine IPv6-Adresse darf nie mit einer Abkürzung beginnen wenn es in einer Signatur für dieses Skript verwendet wird, aufgrund der Art und Weise wie CIDRs rekonstruiert werden durch das Skript; Beispielsweise, `::1/128` ausgedrückt sollte sein, wenn sie in einer Signatur verwendet werden, als `0::1/128`, und `::0/128` ausgedrückt als `0::/128`).
- `yy` ist der CIDR-Block-Größe [1-128].
- `[Funktion]` weist das Skript an, was mit der Signatur zu tun ist (wie der Signatur zu betrachten).
- `[Param]` ist für jeder zusätzliche Informationen dass für `[Funktion]` erforderlich sein können.

Die Signaturdateien für CIDRAM SOLLTEN Unix-Stil-Zeilenumbrüche verwenden (`%0A`, oder `\n`)! Andere Arten/Stile von Zeilenumbrüchen (z.B, Windows `%0D%0A` oder `\r\n` Zeilenumbrüche, Mac `%0D` oder `\r` Zeilenumbrüche, u.s.w.) KANN verwendet werden, sind jedoch NICHT bevorzugt. Nicht-Unix-Stil-Zeilenumbrüche wird auf Unix-Stil-Zeilenumbrüche normalisiert durch das Skript.

Präzise und korrekte CIDR-Notation ist erforderlich, andernfalls das Skript wird erkennen die Signaturen NICHT. Zusätzlich, alle CIDR-Signaturen dieses Skript MUSS mit einer IP-Adresse beginnen deren IP-Nummer gleichmäßig geteilt in die Blockteilung dargestellt durch ihre CIDR-Blockgröße werden kann (z.B, wenn Sie alle IPs blockieren von `10.128.0.0` nach `11.127.255.255` wollten, `10.128.0.0/8` wurde erkennen durch das Skript NICHT, aber `10.128.0.0/9` und `11.0.0.0/9` verwendet in Verbindung, WURDE erkennen durch das Skript).

Alles in den Signaturdateien nicht als Signatur noch Signatur-bezogene Syntax anerkannt durch das Skript wird IGNORIERT, also Bedeutung, können Sie sicher alle Nicht-Signatur-Daten dass du willst in den Signaturdateien ohne sie oder das Skript zu brechen. Kommentare sind in den Signaturdateien akzeptabel, und keine spezielle Formatierung für sie erforderlich ist. Shell-Stil Hashing für Kommentare wird bevorzugt, aber ist durchgesetzt nicht; In Bezug auf Funktionalität, es macht keinen Unterschied zum Skript ob oder nicht wählen Sie Shell-Stil Hashing für Kommentare, aber Shell-Stil Hashing hilft IDEs und Plain-Text-Editoren richtig zu markieren die verschiedenen Teile der Signaturdateien (und so, Shell-Stil Hashing kann als visuelle Hilfe während der Bearbeitung helfen).

Die möglichen Werte für `[Funktion]` sind wie folgt:
- Run
- Whitelist
- Greylist
- Deny

Wenn "Run" verwendet wird, wenn die Signatur ausgelöst wird, das Skript wird (mit einer `require_once` statement) ein externes PHP-Skript ausführen, durch den `[Param]` Wert angegeben (das Arbeitsverzeichnis sollte das "/vault/"-Verzeichnis von das Skript sein).

Beispiel: `127.0.0.0/8 Run example.php`

Das kann nützlich sein wenn Sie einige spezifisch PHP-Code ausführen möchten für einige spezifische IPs und/oder CIDRs.

Wenn "Whitelist" verwendet wird, wenn die Signatur ausgelöst wird, das Skript wird alle Erkennungen zurücksetzen (wenn es irgendwelche Entdeckungen gab) und brechen Sie die Testfunktion. `[Param]` ist ignoriert. Diese Funktion entspricht dem Whitelisting einer bestimmten IP oder CIDR so dass es wird nicht erkannt werden.

Beispiel: `127.0.0.1/32 Whitelist`

Wenn "Greylist" verwendet wird, wenn die Signatur ausgelöst wird, das Skript wird alle Erkennungen zurücksetzen (wenn es irgendwelche Entdeckungen gab) und springen Sie zur nächsten Signaturdatei, um die Verarbeitung fortzusetzen. `[Param]` ist ignoriert.

Beispiel: `127.0.0.1/32 Greylist`

Wenn "Deny" verwendet wird, wenn die Signatur ausgelöst wird, vorausgesetzt dass keine Whitelist-Signatur für die angegebene IP-Adresse und/oder angegebene CIDR ausgelöst wurde, Zugriff auf die geschützte Seite wird verweigert werden. "Deny" ist was Sie verwenden möchten, um tatsächlich eine IP-Adresse und/oder CIDR zu blockieren. Wenn jeder Signaturen dass "Deny" verwenden ausgelöst werden, der "Zugriff verweigert" Seite von das Skript wird generiert und die Anforderung an die geschützte Seite getötet.

Der `[Param]`-Wert akzeptiert von "Deny" wird verarbeitet werden auf der "Zugriff verweigert" Seite, dem Kunden/Benutzer zur Verfügung gestellt als der angegebene Grund für den Zugriff auf die angeforderte Seite verweigert wurde. Es kann ein kurzer und einfacher Satz sein zu erklären, warum Sie sie zu blockieren gewählt haben (alles sollte genügen, sogar eine einfache "Ich will dich nicht auf meiner Website"), oder einer von eine kleine Handvoll von Kurzwörter von das Skript zur Verfügung gestellt dass, wenn verwendet, wird durch das Skript ersetzt werden mit einer vorbereiteten Erklärung für warum der Kunde/Benutzer blockiert wurde.

Die vorbereiteten Erklärungen haben L10N-Unterstützung und kann durch das Skript übersetzt werden Basierend auf der Sprache dass Sie in der `lang` Richtlinie von der Skript-Konfiguration angeben. Zusätzlich, können Sie auf das Skript anweisen für "Deny" Signaturen zu ignorieren, basierend auf ihrem `[Param]`-Wert (wenn sie diese Kurzwörter verwenden) über die Richtlinien spezifiziert durch der Skript-Konfiguration (jedes Kurzwort hat eine entsprechende Richtlinie für die entsprechenden Signaturen zu verarbeiten oder zu ignorieren). `[Param]`-Werte dass diese Kurzwörter nicht verwenden, jedoch, haben L10N-Unterstützung nicht, und deshalb wird NICHT durch das Skript übersetzt werden, und zusätzlich, sind nicht direkt steuerbar durch die Skript-Konfiguration.

Die verfügbaren Kurzwörter sind:
- Bogon
- Cloud
- Generic
- Proxy
- Spam

#### 7.1 TAGS

Wenn Sie Ihre benutzerdefinierten Signaturen in einzelne Sektionen aufteilen möchten, können Sie diese einzelne Sektionen zu dem Skript identifizieren durch Hinzufügen eine "Sektion-Tag" unmittelbar nach den Signaturen von jeder Sektion, zusammen mit dem Namen Ihres Signatur-Sektion (siehe Beispiel unten).

```
# Sektion 1.
1.2.3.4/32 Deny Bogon
2.3.4.5/32 Deny Cloud
4.5.6.7/32 Deny Generic
5.6.7.8/32 Deny Spam
6.7.8.9/32 Deny Proxy
Tag: Sektion 1
```

Um Sektion-Tags zu brechen und sicherzustellen dass Tags von früher in den Signaturdateien wird nicht falsch identifiziert zu Signatur-Sektionen werden, sicherstellen dass es mindestens zwei aufeinanderfolgende Zeilenumbrüche gibt, zwischen Ihrem Tag und Ihren früheren Signatur-Sektionen. Alle nicht-getaggt Signaturen wird standardmäßigen auf "IPv4" oder "IPv6" (je nachdem welche Arten von Signaturen sind ausgelöst werden).

```
1.2.3.4/32 Deny Bogon
2.3.4.5/32 Deny Cloud

4.5.6.7/32 Deny Generic
5.6.7.8/32 Deny Spam
Tag: Sektion 1
```

In dem obigen Beispiel, `1.2.3.4/32` und `2.3.4.5/32` wird als "IPv4" markiert werden, wohingegen `4.5.6.7/32` und `5.6.7.8/32` wird als "Sektion 1" markiert werden.

Wenn Sie möchten dass Signaturen nach einiger Zeit wird ablaufen, in einer Weise ähnlicher wie Sektion-Tags, können Sie ein "Ablauf-Tag" verwenden um anzugeben wann Signaturen nicht mehr gültig sind. Ablauf-Tags verwenden das Format "JJJJ.MM.TT" (siehe Beispiel unten).

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

#### 7.2 YAML

##### 7.2.0 YAML GRUNDLAGEN

Eine vereinfachte Form von YAML-Markup kann in Signaturdateien verwendet werden, um Verhalten und Einstellungen spezifisch für einzelne Signatur-Sektionen zu definieren. Dies kann nützlich sein wenn Sie der Wert von Ihrer Konfiguration-Richtlinien möchten zu variieren auf der Grundlage von individuell Signaturen und Signatur-Sektionen (beispielsweise; wenn Sie eine E-Mail-Adresse zu bieten für Support-Tickets möchten, für irgendein Benutzer dass durch eine spezifische Signatur blockiert, aber Sie eine E-Mail-Adresse zu bieten für Support-Tickets möchten nicht für Benutzer durch andere Signaturen blockiert; wenn Sie möchten dass bestimmte spezifisch Signaturen eine Seitenumleitung zu auslösen; wenn Sie einen Signatur-Sektion zu markieren für Verwendung mit reCAPTCHA möchten; wenn Sie um blockierte Zugriffsversuche zu protokollieren in einzelne Dateien auf der Grundlage von einzelnen Signaturen und/oder Signatur-Sektionen möchten).

Die Verwendung von YAML-Markup in den Signaturdateien ist völlig optional (dh, Sie können es verwenden, wenn Sie dies möchten, aber Sie sind nicht verpflichtet dies zu tun), Und ist in der Lage die meisten (aber nicht alles) Konfiguration-Richtlinien zu nutzen.

Beachten: YAML-Markup-Implementierung in CIDRAM ist sehr einfach und sehr begrenzt; Es ist beabsichtigt um Anforderungen zu erfüllen dass spezifisch für CIDRAM sind, in einer Weise dass die Vertrautheit mit YAML-Markup hat, aber weder folgt noch mit den offiziellen Spezifikationen entspricht (und wird sich daher nicht in der gleichen Weise als gründlichere Implementierungen anderswo verhalten, und möglicherweise nicht für andere Projekte anderswo geeignet werden).

In CIDRAM, YAML-Markup-Segmente dem Skript identifiziert werden durch drei Bindestriche ("---"), und neben ihren enthaltenden Signatur-Sektionen enden durch doppelte Zeilenumbrüche. Ein typisches YAML-Markup-Segment innerhalb eine Signatur-Sektion besteht aus drei Bindestrichen auf einer Linie sofort nach der Liste der CIDRs und alle Tags, gefolgt von einer zweidimensionalen Liste von Schlüssel-Wert-Paaren (erste Dimension, Konfigurations-Richtlinie-Kategorien; zweite Dimension, Konfigurations-Richtlinien) für die Konfigurations-Richtlinien dass geändert werden sollen (und auf welche Werte) wenn eine Signatur innerhalb dass Signatur-Sektion ausgelöst wird (siehe nachfolgende Beispiele).

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

##### 7.2.1 WIE MAN "SPEZIELL MARKIEREN" DEN SIGNATUR-SEKTIONEN FÜR DIE VERWENDUNG MIT reCAPTCHA

Wenn "usemode" ist 0 oder 1, Signatur-Sektionen brauchen nicht zu "besonders markiert" werden für Verwendung mit reCAPTCHA (weil um reCAPTCHA sie bereits entweder wird oder wird nicht zu verwenden, abhängig von dieser Einstellung).

Wenn "usemode" ist 2, um Signatur-Sektionen zu "besonders markiert" für Verwendung mit reCAPTCHA, ein Eintrag in der YAML-Segment für diese Signatur-Sektion enthalten ist (siehe Beispiel unten).

```
# In diese Sektion wird reCAPTCHA verwendet werden.
1.2.3.4/32 Deny Generic
2.3.4.5/32 Deny Generic
Tag: reCAPTCHA-Enabled
---
recaptcha:
 enabled: true
```

Beachten: Eine reCAPTCHA-Instanz wird NUR dem Benutzer angeboten wenn reCAPTCHA aktiviert ist (entweder mit "usemode" als 1, oder "usemode" als 2 mit "enabled" als true), und wenn genau EINE Signatur ausgelöst wurde (nicht mehr und nicht weniger; wenn mehrere Signaturen ausgelöst werden, eine reCAPTCHA-Instanz wird NICHT angeboten werden).

#### 7.3 ZUSATZINFORMATION

In Ergänzung, wenn Sie möchten dass CIDRAM wird bestimmte Sektionen innerhalb irgendein der Signaturdateien vollständig ignoriert, können Sie die Datei `ignore.dat` verwenden, um festzulegen welche Sektionen zu ignorieren. Auf einer neuen Linie, schreiben `Ignore`, gefolgt von einem Leerzeichen, gefolgt durch dem Namen das Sektion welche Sie möchten für CIDRAM zu ignorieren (siehe Beispiel unten).

```
Ignore Sektion 1
```

Wenden Sie sich an den benutzerdefinierten Signaturdateien für weitere Informationen.

---


### 8. <a name="SECTION8"></a>HÄUFIG GESTELLTE FRAGEN (FAQ)

#### Was ist eine "Signatur"?

Im Kontext von CIDRAM, eine "Signatur" bezieht sich auf Daten, die als Indikator/Identifikator fungieren, für etwas Bestimmtes dass wir suchen (normalerweise eine IP-Adresse oder CIDR), und enthält einige Anweisungen für CIDRAM, erzählen es der beste Weg zu reagieren, wenn es begegnet was wir suchen. Eine typische Signatur für CIDRAM sieht so aus:

`1.2.3.4/32 Deny Generic`

Oft (aber nicht immer), Signaturen werden in Gruppen zusammengebunden, als "Signatur-Sektionen" zu bilden, oft begleitet von Kommentaren, Markup und/oder verwandten Metadaten das kann verwendet werden, um zusätzlichen Kontext zu bieten der Signaturen und/oder weitere Anweisung.

#### <a name="WHAT_IS_A_CIDR"></a>Was ist ein "CIDR"?

"CIDR" ist ein Akronym für "Classless Inter-Domain Routing" *[[1](https://de.wikipedia.org/wiki/Classless_Inter-Domain_Routing), [2](http://whatismyipaddress.com/cidr)]*, und es ist dieses Akronym, das als Teil des Namens für dieses Paket, "CIDRAM", verwendet wird, um ein größeres Akronym zu bilden, "Classless Inter-Domain Routing Access Manager".

Aber, im Kontext von CIDRAM (sowie, in dieser Dokumentation, in Diskussionen über CIDRAM, oder in den CIDRAM-Sprachdaten), wann immer ein "CIDR" (Singular) oder "CIDRs" (Plural) ist erwähnt (und somit, wobei wir diese Wörter als Substantive in ihrem eigenen Recht verwenden, im Gegensatz zu Akronymen), was beabsichtigt und gemeint ist ein Subnetz (oder Subnetze), ausgedrückt mit CIDR-Notation. Der Grund dafür, dass CIDR (oder CIDRs) verwendet wird, anstelle von Subnetz (oder Subnetze) ist um zu klären, es ist Subnetze mit CIDR-Notation ausgedrückt auf die wir uns beziehen (weil CIDR-Notation ist nur eine von verschiedenen Möglichkeiten, wie Subnetze ausgedrückt werden können). CIDRAM könnte daher als "Subnetz Access Manager" betrachtet werden.

Obwohl diese doppelte Bedeutung von "CIDR" in einigen Fällen mehrdeutig werden kann, diese erklärung, zusammen mit dem Kontext zur Verfügung gestellt, sollte helfen diese Mehrdeutigkeit zu lösen.

#### Was ist ein "Falsch-Positiv"?

Der Begriff "Falsch-Positiv" (*Alternative: "falsch-positiv Fehler"; "falscher Alarm"*; Englisch: *false positive*; *false positive error*; *false alarm*), sehr einfach beschrieben, und in einem verallgemeinerten Kontext, verwendet wird, wenn eine Bedingung zu testen und wenn die Ergebnisse positiv sind, um die Ergebnisse dieser Tests zu entnehmen (dh, die Bedingung bestimmt wird positiv oder wahr), aber sind zu erwarten sein (oder sollte gewesen) negativ (dh, der Zustand, in Wirklichkeit, ist negativ oder falsch). Eine "Falsch-Positiv" könnte analog zu "weinen Wolf" betrachtet (wobei die Bedingung geprüft wird, ob es ein Wolf in der Nähe der Herde ist, die Bedingung "falsch" ist in dass es keinen Wolf in der Nähe der Herde, und die Bedingung wird als "positiv" berichtet durch die Schäfer durch Aufruf "Wolf, Wolf"), oder analog zu Situationen in medizinischen Tests, wobei ein Patient als mit eine Krankheit diagnostiziert, wenn sie in Wirklichkeit haben sie keine solche Krankheit.

Einige andere Begriffe verwendet: "Wahr-Positiv", "Wahr-Negativ" und "Falsch-Negativ". Eine "Wahr-Positiv" ist, wenn die Ergebnisse des Tests und der wahren Zustand beide wahr sind (oder "Positiv"), und eine "Wahr-Negativ" ist, wenn die Ergebnisse des Tests und der wahren Zustand beide falsch sind (oder "Negativ"); Eine "Wahr-Positiv" oder Eine "Wahr-Negativ" gilt als eine "korrekte Folgerung" zu sein. Der Antithese von einem "Falsch-Positiv" ist eine "Falsch-Negativ"; Eine "Falsch-Negativ" ist, wenn die Ergebnisse des Tests negativ sind (dh, die Bedingung bestimmt wird negativ oder falsch zu sein), aber sind zu erwarten sein (oder sollte gewesen) positiv (dh, der Zustand, in Wirklichkeit, ist "positiv", oder "wahr").

Im Kontext der CIDRAM, Diese Begriffe beziehen sich auf der Signaturen von CIDRAM, und was/wen sie blockieren. Wenn CIDRAM Blöcke eine IP-Adresse wegen schlechten, veraltete oder falsche Signaturen, sollte aber nicht so getan haben, oder wenn sie es tut, so aus den falschen Gründen, wir beziehen sich auf dieses Ereignis als eine "Falsch-Positiv". Wenn CIDRAM, aufgrund unvorhergesehener Bedrohungen, fehlende Signaturen oder Defizite in ihren Signaturen, versagt eine IP-Adresse zu blockieren, die blockiert werden sollte, wir beziehen sich auf dieses Ereignis als eine "verpasste Erkennung" (das entspricht einem "Falsch-Negativ").

Dies kann durch die folgende Tabelle zusammengefasst werden:

&nbsp; | CIDRAM sollte *KEINE* IP-Adresse blockieren | CIDRAM *SOLLTE* eine IP-Adresse blockieren
---|---|---
CIDRAM tut blockiert eine IP-Adresse *NICHT* | Wahr-Negativ (korrekte Folgerung) | Verpasste Erkennung (analog zu Falsch-Negativ)
CIDRAM *TUT* blockiert eine IP-Adresse | __Falsch-Positiv__ | True-Positiv (korrekte Folgerung)

#### Kann CIDRAM ganze Länder blockieren?

Ja. Der einfachste Weg für dies zu erreichen wäre einige der optionalen Landblocklisten bereitgestellt von Macmathan zu installieren. Dies kann mit einigen einfachen Klicks direkt aus der Aktualisierungsseite der Front-End erfolgen, oder, wenn du es vorziehe dass der Front-End deaktiviert bleiben, indem Sie sie direkt aus der **[optionalen Blocklisten-Download-Seite](https://macmathan.info/blocklists)** herunterladen, zum vault hochladen, und zitieren ihre Namen in den entsprechenden Konfigurations-Richtlinien.

#### Wie häufig werden Signaturen aktualisiert?

Die Aktualisierungshäufigkeit hängt von den betreffenden Signaturdateien ab. In der Regel, alle Betreuer für CIDRAM Signaturdateien versuchen ihre Signaturen so aktuell wie möglich zu halten, aber da haben wir alle anderen Verpflichtungen, unser Leben außerhalb des Projekts, und da für unsere Bemühungen um das Projekt, keiner von uns wird finanziell entschädigt (d.h., bezahlt), ein genauer Aktualisierungs-Zeitplan kann nicht garantiert werden. In der Regel, Signaturen werden aktualisiert, wann immer es genügend Zeit gibt sie zu aktualisieren, und Betreuer versuchen auf der Grundlage der Notwendigkeit und auf der Grundlage wie häufig Veränderungen unter den Bereichen auftreten zu priorisieren. Hilfe wird immer geschätzt, wenn Sie bereit bist, irgendwelche anzubieten.

#### Ich habe ein Problem bei der Verwendung von CIDRAM und ich weiß nicht was ich tun soll! Bitte helfen Sie!

- Verwenden Sie die neueste Version der Software? Verwenden Sie die neuesten Versionen Ihrer Signaturdateien? Wenn die Antwort auf eine dieser beiden Fragen nein ist, Versuchen alles zuerst zu aktualisieren, und überprüfen Sie ob das Problem weiterhin besteht. Wenn es weiterhin besteht, lesen Sie weiter.
- Haben Sie alle der Dokumentation überprüft? Wenn nicht, bitte tun Sie dies. Wenn das Problem nicht mit der Dokumentation gelöst werden kann, lesen Sie weiter.
- Haben Sie die **[Frage-Seite](https://github.com/CIDRAM/CIDRAM/issues)** überprüft, ob das Problem vorher erwähnt wurde? Wenn es vorher erwähnt wurde, überprüfen Sie ob irgendwelche Vorschläge, Ideen und/oder Lösungen zur Verfügung gestellt wurden, und folge wie nötig um das Problem zu lösen.
- Haben Sie das **[CIDRAM Support-Forum von Spambot Security](http://www.spambotsecurity.com/forum/viewforum.php?f=61)** überprüft, ob das Problem vorher erwähnt wurde? Wenn es vorher erwähnt wurde, überprüfen Sie ob irgendwelche Vorschläge, Ideen und/oder Lösungen zur Verfügung gestellt wurden, und folge wie nötig um das Problem zu lösen.
- Wenn das Problem weiterhin besteht, informieren Sie uns bitte darüber, indem Sie eine neue Diskussion auf der Frage-Seite erstellen oder eine neue Diskussion über das Support-Forum starten.

#### CIDRAM hat mich von einer Website blockiert die ich besuchen möchte! Bitte helfen Sie!

CIDRAM bietet ein Mittel für Website-Besitzer, um unerwünschten Verkehr zu blockieren, aber es liegt in der Verantwortung der Website-Besitzer, selbst zu entscheiden, wie sie CIDRAM nutzen wollen. Im Falle der falschen-Positiven in Bezug auf der Signaturdateien die normalerweise mit CIDRAM enthalten sind, Korrekturen können vorgenommen werden, aber in Bezug auf unblockiert von bestimmten Websites werden, Sie müssen mit den Besitzern der betreffenden Webseiten sprechen. In Fällen in denen Korrekturen vorgenommen werden, zumindest müssen sie ihre Signaturdateien und/oder Installation aktualisieren, und in anderen Fällen (wie zum Beispiel, wo sie ihre Installation geändert haben, wo sie ihre eigenen benutzerdefinierten Signaturen erstellt haben, usw), Verantwortung für Ihr Problem zu lösen ist ganz ihre, und ist ganz außerhalb unserer Kontrolle.

#### Ich möchte CIDRAM mit einer PHP-Version älter als 5.4.0 verwenden; Kannst du helfen?

Nein. PHP 5.4.0 erreichte offiziellen EoL ("End of Life" oder Ende des Lebens) im Jahr 2014, und Sicherheits-Unterstützung wurde im Jahr 2015 beendet. Zum Zeitpunkt des Schreibens dieses, es ist 2017 und PHP 7.1.0 ist bereits vorhanden. An dieser Zeitpunkt, Unterstützung wird für die Verwendung von CIDRAM mit PHP 5.4.0 und allen verfügbaren neueren PHP Versionen zur Verfügung, aber wenn Sie versuchen CIDRAM mit älteren PHP Versionen zu verwenden, Unterstützung wird zur Verfügung nicht.

#### Kann ich eine einzige CIDRAM-Installation verwenden, um mehrere Domains zu schützen?

Ja. CIDRAM-Installationen sind natürlich nicht auf bestimmte Domains gesperrt, und kann daher zum Schutz mehrerer Domains verwendet werden. Allgemein, wir verweisen auf CIDRAM-Installationen die nur eine Domain schützen als "Single-Domain-Installationen", und Wir verweisen auf CIDRAM-Installationen die mehrere Domains und/oder Subdomains schützen als "Multi-Domain-Installationen". Wenn Sie eine Multi-Domain-Installation betreiben und müssen verschiedene Sätze von Signaturdateien für verschiedene Domains verwenden, oder für verschiedene Domains muss unterschiedliche Konfiguration verwenden, das ist möglich. Nach dem Laden der Konfigurationsdatei (`config.ini`), CIDRAM prüft auf die Existenz einer "Konfiguration-Überschreibt Datei", die für die Domain (oder Subdomain) spezifisch angefordert ist (`die-domain-angefordert.tld.config.ini`), und wenn gefunden, alle von der Konfiguration-Überschreibt Datei definierten Konfigurationswerte wird für die Ausführungsinstanz verwendet, anstelle der von der Konfigurationsdatei definierten Konfigurationswerte. Konfiguration-Überschreibt Dateien sind identisch mit der Konfigurationsdatei, und nach eigenem Ermessen, kann entweder die Gesamtheit aller Konfigurationsrichtlinien für CIDRAM enthalten, oder was auch immer kleiner Unterabschnitt erforderlich ist die sich normalerweise von der Konfigurationsdatei definierten Konfigurationswerte unterscheidet. Konfiguration-Überschreibt Dateien werden nach der Domain für die sie bestimmt sind benannt (so zum Beispiel, wenn Sie eine Konfiguration-Überschreibt Dateien benötigen für die Domäne, `http://www.some-domain.tld/`, seine Konfiguration-Überschreibt Datei sollte benannt werden als `some-domain.tld.config.ini`, und sollte in der vault neben der Konfigurationsdatei `config.ini` platziert werden). Der Domains-Name für die Ausführungsinstanz wird aus dem `HTTP_HOST`-Header der Anforderung abgeleitet; "www" wird ignoriert.

#### Ich möchte keine Zeit damit verbringen (es zu installieren, es richtig zu ordnen, u.s.w.); Kann ich dich einfach bezahlen, um alles für mich zu tun?

Vielleicht. Dies wird von Fall zu Fall berücksichtigt. Sag uns was du brauchst, was du anbietet, und wir werden Ihnen sagen, ob wir helfen können.

#### Kann ich Sie oder einen der Entwickler dieses Projektes für private Arbeit einstellen?

*Siehe oben.*

#### Ich brauche spezialisierte Modifikationen, Anpassungen, u.s.w.; Kannst du helfen?

*Siehe oben.*

#### Ich bin ein Entwickler, Website-Designer oder Programmierer. Kann ich die Arbeit an diesem Projekt annehmen oder anbieten?

Ja. Unsere Lizenz verbietet dies nicht.

#### Ich möchte zum Projekt beitragen; Darf ich das machen?

Ja. Beiträge zum Projekt sind sehr willkommen. Bitte beachten Sie "CONTRIBUTING.md" für weitere Informationen.

#### Empfohlene Werte für "ipaddr".

Wert | Verwenden
---|---
`HTTP_INCAP_CLIENT_IP` | Incapsula Reverse Proxy
`HTTP_CF_CONNECTING_IP` | Cloudflare Reverse Proxy
`CF-Connecting-IP` | Cloudflare Reverse Proxy (Alternative; Wenn der andere Wert nicht funktioniert)
`HTTP_X_FORWARDED_FOR` | Cloudbric Reverse Proxy
`X-Forwarded-For` | [Squid Reverse Proxy](http://www.squid-cache.org/Doc/config/forwarded_for/)
*Definiert durch Server-Konfiguration.* | [Nginx Reverse Proxy](https://www.nginx.com/resources/admin-guide/reverse-proxy/)
`REMOTE_ADDR` | Kein Reverse Proxy (Standardwert).

---


Zuletzt aktualisiert: 18 Juni 2017 (2017.06.18).
