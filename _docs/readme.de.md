## Dokumentation für CIDRAM (Deutsch).

### Inhalt
- 1. [VORWORT](#SECTION1)
- 2. [INSTALLATION](#SECTION2)
- 3. [BENUTZUNG](#SECTION3)
- 4. [FRONT-END-MANAGEMENT](#SECTION4)
- 5. [IM PAKET ENTHALTENE DATEIEN](#SECTION5)
- 6. [EINSTELLUNGEN](#SECTION6)
- 7. [SIGNATURENFORMAT](#SECTION7)
- 8. [BEKANNTE KOMPATIBILITÄTSPROBLEME](#SECTION8)
- 9. [HÄUFIG GESTELLTE FRAGEN (FAQ)](#SECTION9)
- 10. *Reserviert für zukünftige Ergänzungen der Dokumentation.*
- 11. [RECHTSINFORMATION](#SECTION11)

*Hinweis für Übersetzungen: Im Falle von Fehlern (z.B, Diskrepanzen zwischen den Übersetzungen, Tippfehler, u.s.w.), die Englische Version des README als die ursprüngliche und maßgebliche Version ist betrachtet. Wenn Sie irgendwelche Fehler finden, ihre Hilfe bei der Korrektur wäre willkommen.*

---


### 1. <a name="SECTION1"></a>VORWORT

CIDRAM (Classless Inter-Domain Routing Access Manager) ist ein PHP-Skript gestaltete für Websites zu schützen, durch das Blockieren von IP-Adressen als Quellen von unerwünschten Verkehr angesehen, einschließlich (aber nicht beschränkt auf) Datenverkehr von nicht-menschlichen Zugang Endpunkte, Cloud-Services, spambots, Website Schaber, u.s.w. Es tut dies durch die möglichen CIDRs von IP-Adressen zu berechnen, von eingehenden Anfragen geliefert, und dann versuchen, diese gegen ihre Signaturdateien zu entsprechen (Diese Signaturdateien enthalten Listen von CIDRs von IP-Adressen als Ursachen für unerwünschte Verkehrs angesehen); Wenn Übereinstimmungen gefunden werden, die Anfragen ist blockiert.

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

4) Ändern Sie die Zugriffsberechtigungen des `vault`-Verzeichnisses auf "755" (wenn es Probleme gibt, Sie können "777" versuchen; Dies ist weniger sicher, obwohl). Die Berechtigungen des übergeordneten Verzeichnises, in welchem sich der Inhalt befindet (das Verzeichnis, wofür Sie sich entschieden haben), können so belassen werden, überprüfen Sie jedoch die Berechtigungen, wenn in der Vergangenheit Zugriffsprobleme aufgetreten sind (Voreinstellung "755" o.ä.). Zusammenfassend: Damit das Paket ordnungsgemäß funktioniert, muss PHP in der Lage sein, Dateien im `vault`-Verzeichnis zu lesen und zu schreiben. Viele Dinge (Aktualisierung, Protokollierung, u.s.w.) sind nicht möglich, wenn PHP nicht in das `vault`-Verzeichnis schreiben kann, und das Paket überhaupt nicht funktioniert, wenn PHP nicht aus dem `vault`-Verzeichnis lesen kann. Zur optimalen Sicherheit darf das `vault`-Verzeichnis jedoch NICHT öffentlich zugänglich sein (sensible Informationen, wie die in `config.ini` oder `frontend.dat` enthaltenen Informationen, könnten potenziellen Angreifern ausgesetzt sein, wenn das `vault`-Verzeichnis öffentlich zugänglich ist).

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

*Warnung: Die Aktualisierung von CIDRAM über das Plugins-Dashboard führt zu einer sauberen Installation! Wenn Sie Ihre Installation angepasst haben (änderte deine Konfiguration, installierte Module, u.s.w.), diese Anpassungen werden bei wenn der Aktualisierung über das Plugins-Dashboard verloren! Protokolldateien werden auch bei wenn der Aktualisierung über das Plugins-Dashboard verloren! Um Protokolldateien und Anpassungen zu bewahren, aktualisieren Sie über die CIDRAM-Front-End-Aktualisierungen-Seite.*

---


### 3. <a name="SECTION3"></a>BENUTZUNG

CIDRAM sollte automatisch unerwünschte Verkehr auf Ihre Website blockieren, ohne manuelle Unterstützung erforderlich, abgesehen von seiner Erstinstallation.

Sie können Ihre Konfiguration anpassen, und Sie können welche CIDRs blockiert festlegen, durch Sie Ihre Konfigurationsdatei und/oder Signaturdateien Modifizieren.

Wenn Sie Falsch-Positivs begegnen, bitte kontaktieren Sie mich zu informieren. *(Beziehen auf: [Was ist ein "Falsch-Positiv"?](#WHAT_IS_A_FALSE_POSITIVE)).*

CIDRAM kann manuell oder über das Front-End aktualisiert werden. CIDRAM kann auch über Composer oder WordPress aktualisiert werden, wenn es ursprünglich mit diesen Mitteln installiert wurde.

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

Für eine optimale Sicherheit wird außerdem empfohlen, die "Zwei-Faktor-Authentifizierung" für alle Front-End-Konten zu aktivieren (Anweisungen unten).

#### 4.2 WIE MAN DAS FRONT-END BENUTZT.

Anweisungen sind auf jeder Seite des Front-Ends vorhanden, um die richtige Verwendung und den vorgesehenen Zweck zu erläutern. Wenn Sie weitere Erklärungen oder spezielle Hilfe benötigen, wenden Sie sich bitte an den Support. Alternativ gibt es einige Videos auf YouTube, die durch Demonstration helfen könnte.

#### 4.3 ZWEI-FAKTOR-AUTHENTIFIZIERUNG

Es ist möglich, das Front-End sicherer zu machen, indem Sie die Zwei-Faktor-Authentifizierung ("2FA") aktivieren. Wenn Sie sich bei einem 2FA-aktivierten Konto eingeloggt, wird eine E-Mail an die mit diesem Konto verknüpfte E-Mail-Adresse gesendet. Diese E-Mail enthält einen "2FA-Code", den der Nutzer zusätzlich zum Benutzernamen und Passwort eingeben muss, um sich mit diesem Konto einloggen zu können. Das bedeutet, dass das Erlangen eines Kontopassworts nicht ausreicht, damit sich ein Hacker oder potentieller Angreifer in diesem Konto einloggen kann, da sie auch bereits Zugriff auf die mit diesem Konto verknüpfte E-Mail-Adresse haben müssen, um den mit der Sitzung verbundenen 2FA-Code empfangen und verwenden zu können, dadurch wird das Front-End sicherer.

Um die Zwei-Faktor-Authentifizierung zu aktivieren, verwenden Sie zunächst die Front-End-Aktualisierungsseite, um die PHPMailer-Komponente zu installieren. CIDRAM verwendet PHPMailer zum Senden von E-Mails. Hinweis: Obwohl CIDRAM selbst mit PHP >= 5.4.0 kompatibel ist, PHPMailer benötigt PHP >= 5.5.0. Daher ist eine Zwei-Faktor-Authentifizierung für das CIDRAM-Front-End für PHP 5.4-Benutzer nicht möglich.

Nachdem Sie PHPMailer installiert haben, müssen Sie die Konfigurationsdirektiven für PHPMailer über die CIDRAM-Konfigurationsseite oder Konfigurationsdatei auffüllen. Weitere Informationen zu diesen Konfigurationsanweisungen finden Sie im Konfigurationsabschnitt dieses Dokuments. Nachdem Sie die PHPMailer-Konfigurationsdirektiven gefüllt haben, setzen Sie `Enable2FA` auf `true`. Die Zwei-Faktor-Authentifizierung sollte jetzt aktiviert sein.

Nächster, müssen Sie eine E-Mail-Adresse mit einem Konto verknüpfen, damit CIDRAM bei der Einloggen mit diesem Konto weiß, wohin die 2FA-Codes gesendet werden müssen. Um dies zu tun, verwenden Sie die E-Mail-Adresse als Nutzername für das Konto (wie `foo@bar.tld`), oder fügen Sie die E-Mail-Adresse als Teil des Benutzernamens genauso ein wie beim normalen Senden einer E-Mail (wie `Foo Bar <foo@bar.tld>`).

Hinweis: Besonders wichtig ist hier der Schutz Ihres Vault vor unbefugtem Zugriff (z.B., durch die Stärkung der Sicherheit Ihres Servers und die Einschränkung der öffentlichen Zugriffsrechte), da ein unbefugter Zugriff auf Ihre Konfigurationsdatei (die in Ihrem Vault gespeichert ist), Ihre Einstellungen für ausgehenden SMTP (einschließlich SMTP-Benutzername und Passwort) gefährden könnte. Sie sollten sicherstellen, dass Ihr Vault ordnungsgemäß gesichert ist, bevor Sie die Zwei-Faktor-Authentifizierung aktivieren. Wenn dies nicht möglich ist, sollten Sie zumindest ein neues E-Mail-Konto erstellen, das speziell für diesen Zweck vorgesehen ist, um die mit freiliegenden SMTP-Einstellungen verbundenen Risiken zu verringern.

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
/vault/fe_assets/_2fa.html | Ein HTML-Template die verwendet wird wenn der Benutzer nach einem 2FA-Code gefragt wird.
/vault/fe_assets/_accounts.html | Ein HTML-Template für das Front-End Konten-Seite.
/vault/fe_assets/_accounts_row.html | Ein HTML-Template für das Front-End Konten-Seite.
/vault/fe_assets/_cache.html | Ein HTML-Template für die Front-End Datencache-Seite.
/vault/fe_assets/_cidr_calc.html | Ein HTML-Template für den CIDR-Rechner.
/vault/fe_assets/_cidr_calc_row.html | Ein HTML-Template für den CIDR-Rechner.
/vault/fe_assets/_config.html | Ein HTML-Template für die Front-End Konfiguration-Seite.
/vault/fe_assets/_config_row.html | Ein HTML-Template für die Front-End Konfiguration-Seite.
/vault/fe_assets/_files.html | Ein HTML-Template für den Dateimanager.
/vault/fe_assets/_files_edit.html | Ein HTML-Template für den Dateimanager.
/vault/fe_assets/_files_rename.html | Ein HTML-Template für den Dateimanager.
/vault/fe_assets/_files_row.html | Ein HTML-Template für den Dateimanager.
/vault/fe_assets/_home.html | Ein HTML-Template für das Front-End Startseite.
/vault/fe_assets/_ip_aggregator.html | Ein HTML-Template für den IP-Aggregator.
/vault/fe_assets/_ip_test.html | Ein HTML-Template für die IP-Test-Seite.
/vault/fe_assets/_ip_test_row.html | Ein HTML-Template für die IP-Test-Seite.
/vault/fe_assets/_ip_tracking.html | Ein HTML-Template für die IP-Tracking-Seite.
/vault/fe_assets/_ip_tracking_row.html | Ein HTML-Template für die IP-Tracking-Seite.
/vault/fe_assets/_login.html | Ein HTML-Template für die Front-End Einloggen-Seite.
/vault/fe_assets/_logs.html | Ein HTML-Template für die Front-End Protokolldateien-Seite.
/vault/fe_assets/_nav_complete_access.html | Ein HTML-Template für die Front-End Navigation-Links, für alle mit vollständiger Zugriff.
/vault/fe_assets/_nav_logs_access_only.html | Ein HTML-Template für die Front-End Navigation-Links, für alle mit Zugriff nur auf Protokolldateien.
/vault/fe_assets/_range.html | Ein HTML-Template für die Front-End Bereichstische-Seite.
/vault/fe_assets/_range_row.html | Ein HTML-Template für die Front-End Bereichstische-Seite.
/vault/fe_assets/_sections.html | Eine HTML-Template für die Sektionsliste.
/vault/fe_assets/_statistics.html | Ein HTML-Template für die Front-End Statistikseite.
/vault/fe_assets/_updates.html | Ein HTML-Template für die Front-End Aktualisierungen-Seite.
/vault/fe_assets/_updates_row.html | Ein HTML-Template für die Front-End Aktualisierungen-Seite.
/vault/fe_assets/frontend.css | CSS-Stylesheet für das Front-End.
/vault/fe_assets/frontend.dat | Datenbank für das Front-End (Enthält Kontoinformationen, Sitzungsinformationen, und dem Cache; nur erzeugt wenn das Frontend aktiviert und verwendet wird).
/vault/fe_assets/frontend.html | Die Haupt-HTML-Template-Datei für das Front-End.
/vault/fe_assets/icons.php | Ikonen-Handler (die vom Front-End-Dateimanager verwendet wird).
/vault/fe_assets/pips.php | Pips-Handler (die vom Front-End-Dateimanager verwendet wird).
/vault/fe_assets/scripts.js | Enthält Front-End-JavaScript-Daten.
/vault/lang/ | Enthält Sprachdaten für CIDRAM.
/vault/lang/.htaccess | Ein Hypertext-Access-Datei (in diesem Fall zum Schutz von sensiblen Dateien des Scripts vor einem nicht authorisierten Zugriff).
/vault/lang/lang.ar.cli.php | Arabische Sprachdateien für CLI.
/vault/lang/lang.ar.fe.php | Arabische Sprachdateien für das Front-End.
/vault/lang/lang.ar.php | Arabische Sprachdateien.
/vault/lang/lang.bn.cli.php | Bangla Sprachdateien für CLI.
/vault/lang/lang.bn.fe.php | Bangla Sprachdateien für das Front-End.
/vault/lang/lang.bn.php | Bangla Sprachdateien.
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
/vault/lang/lang.no.cli.php | Norwegische Sprachdateien für CLI.
/vault/lang/lang.no.fe.php | Norwegische Sprachdateien für das Front-End.
/vault/lang/lang.no.php | Norwegische Sprachdateien.
/vault/lang/lang.pt.cli.php | Portugiesische Sprachdateien für CLI.
/vault/lang/lang.pt.fe.php | Portugiesische Sprachdateien für das Front-End.
/vault/lang/lang.pt.php | Portugiesische Sprachdateien.
/vault/lang/lang.ru.cli.php | Russische Sprachdateien für CLI.
/vault/lang/lang.ru.fe.php | Russische Sprachdateien für das Front-End.
/vault/lang/lang.ru.php | Russische Sprachdateien.
/vault/lang/lang.sv.cli.php | Schwedische Sprachdateien für CLI.
/vault/lang/lang.sv.fe.php | Schwedische Sprachdateien für das Front-End.
/vault/lang/lang.sv.php | Schwedische Sprachdateien.
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
/vault/.travis.php | Wird von Travis CI zum Testen verwendet (für die korrekte Funktion des Scripts nicht notwendig).
/vault/.travis.yml | Wird von Travis CI zum Testen verwendet (für die korrekte Funktion des Scripts nicht notwendig).
/vault/aggregator.php | IP-Aggregator.
/vault/cache.dat | Cache-Daten.
/vault/cidramblocklists.dat | Metadaten-Datei für die optionalen Blocklisten von Macmathan; Wird von der Front-End-Updates-Seite verwendet.
/vault/cli.php | CLI-Handler.
/vault/components.dat | Komponenten-Metadaten-Datei; Wird von der Front-End-Updates-Seite verwendet.
/vault/config.ini.RenameMe | Konfigurationsdatei; Beinhaltet alle Konfigurationsmöglichkeiten von CIDRAM (umbenennen zu aktivieren).
/vault/config.php | Konfiguration-Handler.
/vault/config.yaml | Standardkonfigurationsdatei; Beinhaltet Standardkonfigurationswerte für CIDRAM.
/vault/frontend.php | Front-End-Handler.
/vault/frontend_functions.php | Front-End-Funktionen-Datei.
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
/vault/modules.dat | Modul-Metadaten-Datei; Wird von der Front-End-Updates-Seite verwendet.
/vault/outgen.php | Ausgabe-Generator.
/vault/php5.4.x.php | Polyfills für PHP 5.4.X (erforderlich für Abwärtskompatibilität mit PHP 5.4.X; sicher zu löschen für neuere PHP-Versionen).
/vault/recaptcha.php | reCAPTCHA-Modul.
/vault/rules_as6939.php | Benutzerdefinierte Regeldatei für AS6939.
/vault/rules_softlayer.php | Benutzerdefinierte Regeldatei für Soft Layer.
/vault/rules_specific.php | Benutzerdefinierte Regeldatei für einige spezifische CIDRs.
/vault/salt.dat | Salz-Datei (durch einigen periphere Funktionalität verwendet; nur dann erzeugt wenn erforderlich).
/vault/template_custom.html | Template Datei; Template für die HTML-Ausgabe durch der CIDRAM Ausgabe-Generator erzeugt.
/vault/template_default.html | Template Datei; Template für die HTML-Ausgabe durch der CIDRAM Ausgabe-Generator erzeugt.
/vault/themes.dat | Themes-Metadaten-Datei; Wird von der Front-End-Updates-Seite verwendet.
/vault/verification.yaml | Verifikationsdaten für Suchmaschinen und Social Media.
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

##### "logfile"
- Name einer Datei für Menschen lesbar zu protokollieren alle blockierten Zugriffsversuche. Geben Sie einen Dateinamen an oder lassen Sie die Option zum Deaktivieren leer.

##### "logfileApache"
- Name einer Apache-Stil-Datei zu protokollieren alle blockierten Zugriffsversuche. Geben Sie einen Dateinamen an oder lassen Sie die Option zum Deaktivieren leer.

##### "logfileSerialized"
- Name einer Datei zu protokollieren alle blockierten Zugriffsversuche (Format ist serialisiert). Geben Sie einen Dateinamen an oder lassen Sie die Option zum Deaktivieren leer.

*Nützlicher Tipp: Wenn du willst, Sie können Datum/Uhrzeit-Information den Namen der Protokolldateien anhängen, durch diese im Namen einschließlich: `{yyyy}` für die komplette Jahr, `{yy}` für abgekürzte Jahr, `{mm}` für Monate, `{dd}` für Tag, `{hh}` für Stunde.*

*Beispielen:*
- *`logfile='logfile.{yyyy}-{mm}-{dd}-{hh}.txt'`*
- *`logfileApache='access.{yyyy}-{mm}-{dd}-{hh}.txt'`*
- *`logfileSerialized='serial.{yyyy}-{mm}-{dd}-{hh}.txt'`*

##### "truncate"
- Trunkate Protokolldateien, wenn sie eine bestimmte Größe erreichen? Wert ist die maximale Größe in B/KB/MB/GB/TB, die eine Protokolldatei wachsen kann, bevor sie trunkiert wird. Der Standardwert von 0KB deaktiviert die Trunkierung (Protokolldateien können unbegrenzt wachsen). Beachten: Gilt für einzelne Protokolldateien! Die Größe der Protokolldateien gilt nicht als kollektiv.

##### "log_rotation_limit"
- Die Protokollrotation begrenzt die Anzahl der Protokolldateien, die gleichzeitig vorhanden sein sollten. Wenn neue Protokolldateien erstellt werden, und wenn die Gesamtzahl der Protokolldateien den angegebenen Limit überschreitet, wird die angegebene Aktion ausgeführt. Sie können hier das gewünschte Limit angeben. Ein Wert von 0 deaktiviert die Protokollrotation.

##### "log_rotation_action"
- Die Protokollrotation begrenzt die Anzahl der Protokolldateien, die gleichzeitig vorhanden sein sollten. Wenn neue Protokolldateien erstellt werden, und wenn die Gesamtzahl der Protokolldateien den angegebenen Limit überschreitet, wird die angegebene Aktion ausgeführt. Sie können hier die gewünschte Aktion angeben. Delete = Löschen Sie die ältesten Protokolldateien, bis das Limit nicht mehr überschritten wird. Archive = Zuerst archivieren, und dann löschen Sie die ältesten Protokolldateien, bis das Limit nicht mehr überschritten wird.

*Technische Erläuterung: "Ältesten" bedeutet, in diesem Zusammenhang, am wenigsten kurzem geändert.*

##### "timeOffset"
- Wenn Ihr Server-Zeit nicht mit Ihrer Ortszeit, Sie können einen Offset hier angeben (Dies ist das Datum/Zeit-Informationen anpassen, die durch CIDRAM erzeugt wird). Es ist in der Regel statt zur Einstellung der Zeitzone Richtlinie in Ihrer Datei `php.ini` empfohlen, aber manchmal (wie wenn Sie mit begrenzten Shared-Hosting-Provider arbeiten) dies ist nicht immer möglich zu tun, und so, ist diese Option hier zur Verfügung gestellt.
- Beispiel (eine Stunde hinzufügen): `timeOffset=60`

##### "timeFormat"
- Das Datumsformat verwendet von CIDRAM. Standardeinstellung = `{Day}, {dd} {Mon} {yyyy} {hh}:{ii}:{ss} {tz}`.

##### "ipaddr"
- Ort der IP-Adresse der aktuellen Verbindung im gesamten Datenstrom (nützlich für Cloud-Services). Standardeinstellung = REMOTE_ADDR. ACHTUNG: Ändern Sie diesen Wert nur, wenn Sie wissen, was Sie tun!

Empfohlene Werte für "ipaddr":

Wert | Verwenden
---|---
`HTTP_INCAP_CLIENT_IP` | Incapsula Reverse Proxy.
`HTTP_CF_CONNECTING_IP` | Cloudflare Reverse Proxy.
`CF-Connecting-IP` | Cloudflare Reverse Proxy (Alternative; Wenn der andere Wert nicht funktioniert).
`HTTP_X_FORWARDED_FOR` | Cloudbric Reverse Proxy.
`X-Forwarded-For` | [Squid Reverse Proxy](http://www.squid-cache.org/Doc/config/forwarded_for/).
*Definiert durch Server-Konfiguration.* | [Nginx Reverse Proxy](https://www.nginx.com/resources/admin-guide/reverse-proxy/).
`REMOTE_ADDR` | Kein Reverse Proxy (Standardwert).

##### "forbid_on_block"
- Welche HTTP-Status-Message sollte CIDRAM senden, wenn Anfragen blockiert?

Derzeit unterstützte Werte:

Status-Code | Status-Message | Beschreibung
---|---|---
`200` | `200 OK` | Standardwert. Am wenigsten robust, aber am benutzerfreundlichsten.
`403` | `403 Forbidden` | Robuster, aber weniger benutzerfreundlich.
`410` | `410 Gone` | Bei dem Versuch, falsch positive Ergebnisse zu beheben, können Probleme auftreten, weil einige Browser diese Status-Message zwischenspeichern und keine nachfolgenden Anfragen senden, selbst nachdem die Benutzer unblockiert wurden. Kann jedoch nützlicher sein als andere Optionen, um Anfragen von bestimmten, sehr spezifischen Arten von Bots zu reduzieren.
`418` | `418 I'm a teapot` | Bezieht sich eigentlich auf einen Aprilscherz [[RFC 2324](https://tools.ietf.org/html/rfc2324#section-6.5.14)] und wird vom Client wahrscheinlich nicht verstanden. Zur Unterhaltung und Bequemlichkeit zur Verfügung gestellt, aber nicht allgemein empfohlen.
`451` | `Unavailable For Legal Reasons` | Geeignet für Kontexte, in denen Anfragen hauptsächlich aus rechtlichen Gründen blockiert werden. Nicht in anderen Kontexten empfohlen.
`503` | `Service Unavailable` | Am robustesten, aber am wenigsten benutzerfreundlich.

##### "silent_mode"
- Anstatt die "Zugriff verweigert", sollte CIDRAM leise blockiert Zugriffsversuche umleiten? Wenn ja, geben Sie den Speicherort auf den blockierten Zugriffsversuche umleiten. Wenn nein, diese Variable leer lassen.

##### "lang"
- Gibt die Standardsprache für CIDRAM an.

##### "numbers"
- Gibt an, wie die Nummern angezeigt werden sollen.

Derzeit unterstützte Werte:

Wert | Produziert | Beschreibung
---|---|---
`NoSep-1` | `1234567.89`
`NoSep-2` | `1234567,89`
`Latin-1` | `1,234,567.89` | Standardwert.
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

*Beachten: Diese Werte sind nirgends standardisiert und werden wahrscheinlich nicht über das Paket hinaus relevant sein. Auch, unterstützte Werte können sich in Zukunft ändern.*

##### "emailaddr"
- Wenn Sie möchten, können Sie hier eine E-Mail-Adresse angeben, geben auf den Benutzern wenn sie blockiert, für Unterstützung für den Fall dass sie ist blockiert versehentlich oder im fehler. WARNUNG: Jede E-Mail-Adresse die Sie hier angeben wird sicherlich durch Spambots erworben werden im Zuge ihrer Verwendung hier, und so, es wird dringend empfohlen, wenn Sie hier eine E-Mail-Adresse angeben, dass die E-Mail-Adresse die Sie hier angeben, eine Einwegadresse ist, und/oder eine Adresse die Sie nichts dagegen haben Spam (mit anderen Worten, möchten Sie wahrscheinlich nicht Ihre primären persönlichen oder primären geschäftlichen E-Mail-Adressen verwenden).

##### "emailaddr_display_style"
- Wie möchten Sie die E-Mail-Adresse für die Nutzer vorstellen? "default" = Klickbarer Link. "noclick" = Nicht klickbarer Text.

##### "disable_cli"
- CLI-Modus deaktivieren? CLI-Modus ist standardmäßig aktiviert, kann aber manchmal bestimmte Test-Tools (PHPUnit zum Beispiel) und andere CLI-basierte Anwendungen beeinträchtigen. Wenn du den CLI-Modus nicht deaktiveren musst, solltest du diese Anweisung ignorieren. False = CLI-Modus aktivieren [Standardeinstellung]; True = CLI-Modus deaktivieren.

##### "disable_frontend"
- Front-End-Access deaktivieren? Front-End-Access kann machen CIDRAM einfacher zu handhaben, aber es kann auch ein potentielles Sicherheitsrisiko sein. Es wird empfohlen, wenn möglich, CIDRAM über die Back-End-Access zu verwalten, aber Front-End-Access vorgesehen ist, für wenn es nicht möglich ist. Halten Sie es deaktiviert außer wenn Sie es brauchen. False = Front-End-Access aktivieren; True = Front-End-Access deaktivieren [Standardeinstellung].

##### "max_login_attempts"
- Maximale Anzahl der Versucht zu einloggen (Front-End). Standardeinstellung = 5.

##### "FrontEndLog"
- Datei für die Protokollierung von Front-End Einloggen-Versuchen. Geben Sie einen Dateinamen an oder lassen Sie die Option zum Deaktivieren leer.

##### "ban_override"
- Überschreiben "forbid_on_block" Wenn "infraction_limit" überschritten wird? Beim überschreiben: Blockiert Anfragen geben eine leere Seite zurück (Template-Dateien werden nicht verwendet). 200 = Nicht überschreiben [Standardeinstellung]. Andere Werte entsprechen den verfügbaren Werten für "forbid_on_block".

##### "log_banned_ips"
- Enthalten Sie blockierte Anfragen von verbotenen IPs in die Protokolldateien? True = Ja [Standardeinstellung]; False = Nein.

##### "default_dns"
- Eine durch Kommata getrennte Liste von DNS-Servern, die für Hostnamen-Lookups verwendet werden sollen. Standardeinstellung = "8.8.8.8,8.8.4.4" (Google DNS). ACHTUNG: Ändern Sie diesen Wert nur, wenn Sie wissen, was Sie tun!

*Siehe auch: [Was kann ich für "default_dns" verwenden?](#WHAT_CAN_I_USE_FOR_DEFAULT_DNS)*

##### "search_engine_verification"
- Versuche, Anfragen von Suchmaschinen zu überprüfen? Die Überprüfung der Suchmaschinen sorgt dafür, dass sie nicht als Folge der Maximale Anzahl von Verstöße verboten werden (Verbot der Suchmaschinen von Ihrer Website wird in der Regel einen negativen Effekt auf Ihre Suchmaschinen-Ranking, SEO und u.s.w. haben). Wenn überprüft, wie pro normal, Suchmaschinen können blockiert werden, aber sie werden nicht verboten. Wenn nicht überprüft, es ist möglich, dass sie verboten als Folge der Überschreitung der Maximale Anzahl von Verstöße werden können. Zusätzlich, Suchmaschinen-Verifizierung bietet Schutz gegen gefälschte Suchmaschinen-Anfragen und gegen potenziell böswillige Entitäten, die sich als Suchmaschinen maskieren (solche Anfragen werden blockiert, wenn die Suchmaschinen-Verifizierung aktiviert ist). True = Suchmaschinen-Verifizierung aktivieren [Standardeinstellung]; False = Suchmaschinen-Verifizierung deaktivieren.

Derzeit unterstützt:
- __[Google](https://support.google.com/webmasters/answer/80553?hl=en)__
- __[Bing](https://blogs.bing.com/webmaster/2012/08/31/how-to-verify-that-bingbot-is-bingbot)__
- __Yahoo__
- __[Baidu (百度)](https://help.baidu.com/question?prod_en=master&class=Baiduspider)__
- __Sogou (搜狗)__
- __Youdao (有道)__
- __[Applebot](https://discussions.apple.com/thread/7090135)__
- __[Yandex (Яндекс)](https://yandex.com/support/webmaster/robot-workings/check-yandex-robots.xml)__
- __[DuckDuckGo](https://duckduckgo.com/duckduckbot)__

Nicht kompatibel (verursacht Konflikte):
- __[Mix.com](https://github.com/CIDRAM/CIDRAM/issues/80)__

##### "social_media_verification"
- Versuche, Social Media Anfragen zu verifizieren? Die Verifizierung von Social Media bietet Schutz vor gefälschten Social Media Anfragen (solche Anfragen werden blockiert). True = Die Verifizierung von Social Media aktivieren [Standardeinstellung]; False = Die Verifizierung von Social Media deaktivieren.

Derzeit unterstützt:
- __[Pinterest](https://help.pinterest.com/en/articles/about-pinterest-crawler-0)__
- __Embedly__
- __[Grapeshot](https://www.grapeshot.com/crawler/)__
- __Twitterbot__

##### "protect_frontend"
- Gibt an, ob die Schutzmaßnahmen normalerweise vom CIDRAM bereitgestellten auf das Frontend angewendet werden sollen. True = Ja [Standardeinstellung]; False = Nein.

##### "disable_webfonts"
- Web-Fonts deaktivieren? True = Ja [Standardeinstellung]; False = Nein.

##### "maintenance_mode"
- Wartungsmodus aktivieren? True = Ja; False = Nein [Standardeinstellung]. Deaktiviert alles andere als das Front-End. Manchmal nützlich für die Aktualisierung Ihrer CMS, Frameworks, u.s.w.

##### "default_algo"
- Definiert den Algorithmus für alle zukünftigen Passwörter und Sitzungen. Optionen: PASSWORD_DEFAULT (Standardeinstellung), PASSWORD_BCRYPT, PASSWORD_ARGON2I (erfordert PHP >= 7.2.0).

##### "statistics"
- CIDRAM-Nutzungsstatistiken verfolgen? True = Ja; False = Nein [Standardeinstellung].

##### "force_hostname_lookup"
- Erzwinge Hostnamen-Suche? True = Ja; False = Nein [Standardeinstellung]. Hostnamen-Suchen werden normalerweise auf einer "wie benötigt"-Basis durchgeführt, können jedoch für alle Anforderungen erzwungen werden. Dies kann nützlich sein, um detailliertere Informationen in der Protokolldateien bereitzustellen, aber auch kann sich leicht negativ auf die Performance au.s.w.irken.

##### "allow_gethostbyaddr_lookup"
- gethostbyaddr-Suche erlauben, wenn UDP nicht verfügbar ist? True = Ja [Standardeinstellung]; False = Nein.
- *Hinweis: Bei einigen 32-Bit-Systemen funktioniert die IPv6-Suche möglicherweise nicht ordnungsgemäß.*

##### "hide_version"
- Versionsinformationen aus Protokollen und Seitenausgabe ausblenden? True = Ja; False = Nein [Standardeinstellung].

##### "empty_fields"
- Wie sollte CIDRAM leere Felder behandeln, wenn Blockereignisinformationen protokolliert und angezeigt werden? "include" = Leere Felder einlassen. "omit" = Leere Felder auslassen [Standardeinstellung].

#### "signatures" (Kategorie)
Konfiguration der Signaturen.

##### "ipv4"
- Eine Liste der IPv4-Signaturdateien dass CIDRAM zu verarbeiten soll, durch Kommas begrenzt. Hier können Sie Einträge hinzufügen wenn Sie zusätzliche IPv4-Signaturdateien in CIDRAM hinzufügen möchten.

##### "ipv6"
- Eine Liste der IPv6-Signaturdateien dass CIDRAM zu verarbeiten soll, durch Kommas begrenzt. Hier können Sie Einträge hinzufügen wenn Sie zusätzliche IPv6-Signaturdateien in CIDRAM hinzufügen möchten.

##### "block_cloud"
- Blockieren Sie CIDRs identifiziert als zu Web-Hosting/Cloud-Services gehören? Wenn Sie einen API-Dienst von Ihrer Website aus betreiben, oder wenn Sie erwarten dass andere Websites eine Verbindung zu Ihrer Website herstellen, dies auf false sollte gesetzt werden. Wenn Sie nicht, dann, dies auf true sollte gesetzt werden.

##### "block_bogons"
- Blockieren Sie Bogon/Martian CIDRs? Wenn Sie Verbindungen zu Ihrer Website von localhost, von Ihrem LAN, oder von innerhalb Ihres lokalen Netzwerks erwarten, diese Richtlinie auf false sollte gesetzt werden. Wenn Sie diese Verbindungen nicht erwarten, dies auf true sollte gesetzt werden.

##### "block_generic"
- Blockieren Sie CIDRs allgemein empfohlen für eine schwarze Liste? Dies gilt für alle Signaturen, die nicht als Teil einer der anderen spezifischen Signaturkategorien markiert sind.

##### "block_legal"
- Blockieren Sie CIDRs als Antwort auf gesetzliche Verpflichtungen? Diese Richtlinie sollte normalerweise keine Wirkung haben, da CIDRAM standardmäßig keine CIDRs mit "gesetzliche Verpflichtungen" assoziiert, aber es existiert dennoch als zusätzliche Kontrollmaßnahme für den Vorteil von benutzerdefinierten Signaturdateien oder Modulen, die aus gesetzlichen Gründen existieren könnten.

##### "block_malware"
- Blockieren Sie IP-Adressen in Verbindung mit Malware? Dazu gehören C&C-Server, infizierte Computer, Malware-Verteilung beteiligte Computer, u.s.w.

##### "block_proxies"
- Blockieren Sie CIDRs identifiziert als zu Proxy-Dienste oder VPNs gehören? Wenn Sie benötigen dass Benutzer auf Ihre Website von Proxy-Dienste und VPNs zugreifen können, diese Richtlinie auf false sollte gesetzt werden. Andernfalls, Wenn Sie Proxy-Dienste oder VPNs nicht benötigen, diese Richtlinie auf true sollte gesetzt werden, als Mittel zur Verbesserung der Sicherheit.

##### "block_spam"
- Blockieren Sie CIDRs identifiziert als ein hohem Risiko für Spam? Solange Sie keine Probleme haben während Sie dies tun, allgemein, dies immer auf true sollte gesetzt sein.

##### "modules"
- Eine Liste der Moduldateien zu laden nach der Prüfung der IPv4/IPv6-Signaturen, durch Kommas begrenzt.

##### "default_tracktime"
- Wie viele Sekunden, um IPs von Modulen verboten zu verfolgen. Standardeinstellung = 604800 (1 Woche).

##### "infraction_limit"
- Maximale Anzahl von Verstöße, die eine IP zulassen darf, bevor sie durch IP-Tracking verboten ist. Standardeinstellung = 10.

##### "track_mode"
- Wann sollten Verstöße gezählt werden? False = Wenn IPs von Modulen blockiert werden. True = Wenn IPs von irgendeinem Grund blockiert werden.

#### "recaptcha" (Kategorie)
Wenn du willst, können Sie Benutzern bieten eine Möglichkeit zur umgehen der Seite "Zugriff verweigert" durch Abschluss einer reCAPTCHA-Instanz. Dies kann helfen, einige der Risiken im Zusammenhang mit Falsch-Positivs zu mildern, in diesen Fällen wodurch wir nicht ganz sicher sind ob eine Anfrage von einer Maschine oder einem Menschen stammt.

Aufgrund der Risiken im Zusammenhang mit der Bereitstellung eines Wegs um der Seite "Zugriff verweigert" zur umgehen, im Algemeinen, Ich würde raten dies zu nicht ermöglichen es sei denn, Sie empfinden es als notwendig dies zu tun. Situationen wo es notwendig könnte sein: Wenn Ihre Website hat Kunden/Benutzer, die Zugriff auf Ihre Website haben müssen, und wenn dies etwas ist das nicht ausgehandelt werden kann, aber wenn diese Kunden/Benutzer von einem feindlichen Netzwerk zu verbinden, das möglicherweise auch unerwünschten Verkehr führen könnte, und die Blockierung dieser unerwünschten Verkehr ist auch etwas das nicht ausgehandelt werden kann, in diesen Situationen, könnte das reCAPTCHA-Feature nützlich sein um die wünschenswerten Kunden/Benutzer zu ermöglichen, während der unerwünschten Verkehr aus demselben Netzwerk ausgeschlossen ist. Das sagte aber, da der beabsichtigte Zweck einer CAPTCHA ist, zwischen Menschen und Nicht-Menschen zu unterscheiden, das reCAPTCHA-Feature würde nur in diesen Situationen ohne Gewinner zu helfen, wenn wir annehmen dass dieser unerwünschten Verkehr nicht menschlich ist (z.B, Spambots, Scrapers, Hacktools, automatisierten Datenverkehr), im Gegensatz zu unerwünschtem menschlichen Verkehr (wie menschliche Spammer, Hacker, und derartige).

Um einen "site key" und einen "secret key" zu erhalten (für die Verwendung von reCAPTCHA erforderlich), bitte gehe zu: [https://developers.google.com/recaptcha/](https://developers.google.com/recaptcha/)

##### "usemode"
- Dies definiert wie CIDRAM sollte reCAPTCHA benutzen.
- 0 = reCAPTCHA ist komplett deaktiviert (Standardeinstellung).
- 1 = reCAPTCHA ist für alle Signaturen aktiviert.
- 2 = reCAPTCHA ist aktiviert, nur für Signaturen in Sektionen markiert als reCAPTCHA-aktiviert in den Signaturdateien.
- (Jeder andere Wert wird auf die gleiche Weise behandelt wie 0).

##### "lockip"
- Gibt an ob Hashes an bestimmte IPs gebunden werden sollen. False = Cookies und Hashes KÖNNEN über mehrere IPs verwendet werden (Standardeinstellung). True = Cookies und Hashes können NICHT über mehrere IPs verwendet werden (Cookies/Hashes sind an IPs gebunden).
- Beachten: Der Wert für "lockip" wird ignoriert, wenn "lockuser" false ist, aufgrund der Tatsache dass der Mechanismus zum Erinnern "Benutzer" unterscheidet sich je nach diesem Wert.

##### "lockuser"
- Gibt an ob der erfolgreiche Abschluss eines reCAPTCHA-Instanz an bestimmte Benutzer gebunden werden sollen. False = Der erfolgreiche Abschluss eines reCAPTCHA-Instanz, auf alle Anfragen die von derselben IP stammen die von dem Benutzer die die reCAPTCHA-Instanz ausfüllt wird Zugriff gewährt; Cookies und Hashes werden nicht verwendet; Stattdessen wird eine IP-Whitelist verwendet. True = Der erfolgreiche Abschluss eines reCAPTCHA-Instanz nur dem Benutzer dass die reCAPTCHA-Instanz ausfüllt wird Zugriff gewährt; Cookies und Hashes werden verwendet, um den Benutzer zu merken; Eine IP-Whitelist wird nicht verwendet (Standardeinstellung).

##### "sitekey"
- Dieser Wert sollte dem "site key" für Ihre reCAPTCHA entsprechen, sich innerhalb des reCAPTCHA Dashboard befindet.

##### "secret"
- Dieser Wert sollte dem "secret key" für Ihre reCAPTCHA entsprechen, sich innerhalb des reCAPTCHA Dashboard befindet.

##### "expiry"
- Wenn "lockuser" true ist (Standardeinstellung), um sich zu erinnern wann ein Benutzer eine reCAPTCHA-Instanz erfolgreich bestanden hat, für zukünftige Seite-Anfragen, generiert CIDRAM ein Standard-HTTP-Cookie, dass einen Hash enthält, entsprechend einem internen Datensatz, dass der denselben Hash enthält; Zukünftige Seite-Anfragen werden diese entsprechenden Hashes verwenden, zu authentifizieren dass ein Benutzer zuvor bereits eine reCAPTCHA-Instanz erfolgreich bestanden hat. Wenn "lockuser" false ist, wird eine IP-Whitelist verwendet, um festzustellen ob Anfragen aus der IP von eingehenden Anfragen, zugelassen werden sollen; Einträge werden zu dieser Whitelist hinzugefügt wenn die reCAPTCHA-Instanz erfolgreich bestanden ist. Für wie viele Stunden sollten diese Cookies, Hashes und Whitelist-Einträge gültig bleiben? Standardeinstellung = 720 (1 Monat).

##### "logfile"
- Protokollieren Sie alle reCAPTCHA versucht? Geben Sie einen Dateinamen an oder lassen Sie die Option zum Deaktivieren leer.

*Nützlicher Tipp: Wenn du willst, Sie können Datum/Uhrzeit-Information den Namen der Protokolldateien anhängen, durch diese im Namen einschließlich: `{yyyy}` für die komplette Jahr, `{yy}` für abgekürzte Jahr, `{mm}` für Monate, `{dd}` für Tag, `{hh}` für Stunde.*

*Beispielen:*
- *`logfile='recaptcha.{yyyy}-{mm}-{dd}-{hh}.txt'`*

##### "signature_limit"
- Maximale Anzahl von Signaturen, die ausgelöst werden dürfen, wenn eine reCAPTCHA-Instanz angeboten werden soll. Standardeinstellung = 1. Wenn diese Anzahl für eine bestimmte Anfrage überschritten wird, wird keine reCAPTCHA-Instanz angeboten.

##### "api"
- Welche API soll verwendet werden? V2 oder Invisible?

*Hinweis für Benutzer in der Europäischen Union: Wenn CIDRAM für die Verwendung von Cookies konfiguriert ist (z.B. wenn "lockuser" true/wahr ist), auf der Seite wird gemäß den Anforderungen der [EU-Cookie-Gesetzgebung](http://ec.europa.eu/ipg/basics/legal/cookies/index_en.htm) eine Cookie-Warnung angezeigt. Jedoch, bei Verwendung der invisible API versucht CIDRAM, den reCAPTCHA für den Benutzer automatisch abzuschließen, und wenn dies erfolgreich ist, könnte dies dazu führen, dass die Seite erneut geladen wird und ein Cookie erstellt wird, ohne dass dem Benutzer ausreichend Zeit gegeben wird, die Cookie-Warnung tatsächlich zu sehen. Wenn dies für Sie ein rechtliches Risiko darstellt, ist es möglicherweise besser, die V2 API anstelle der invisible API zu verwenden (die V2 API ist nicht automatisiert und erfordert, dass der Benutzer die reCAPTCHA-Aufgabe selbst abschließt; dies bietet die Möglichkeit, die Cookie-Warnung zu sehen).*

#### "legal" (Kategorie)
Konfiguration für gesetzliche Anforderungen.

*Für weitere Informationen zu gesetzlichen Anforderungen und wie sich dies auf Ihre Konfiguration-Anforderungen auswirken könnte, bitte beachten Sie den Sektion "[RECHTSINFORMATION](#SECTION11)" der Dokumentation.*

##### "pseudonymise_ip_addresses"
- Pseudonymisieren IP-Adressen beim Schreiben der Protokolldateien? True = Ja; False = Nein [Standardeinstellung].

##### "omit_ip"
- IP-Adressen aus Protokollen auslassen? True = Ja; False = Nein [Standardeinstellung]. Hinweis: "pseudonymise_ip_addresses" wird überflüssig, wenn "omit_ip" "true" ist.

##### "omit_hostname"
- Hostnamen aus Protokollen auslassen? True = Ja; False = Nein [Standardeinstellung].

##### "omit_ua"
- Benutzeragenten aus Protokollen auslassen? True = Ja; False = Nein [Standardeinstellung].

##### "privacy_policy"
- Die Adresse einer relevanten Datenschutz-Bestimmungen, die in der Fußzeile aller generierten Seiten angezeigt werden soll. Geben Sie eine URL, oder lassen Sie sie leer, um sie zu deaktivieren.

#### "template_data" (Kategorie)
Anweisungen/Variablen für Templates und Themes.

Template-Daten bezieht sich auf die HTML-Ausgabe die verwendet wird, um die "Zugriff verweigert"-Nachricht Benutzern anzuzeigen, wenn eine hochgeladene Datei blockiert wird. Falls Sie benutzerdefinierte Themes für CIDRAM verwenden, wird die HTML-Ausgabe von der `template_custom.html`-Datei verwendet, ansonsten wird die HTML-Ausgabe von der `template.html`-Datei verwendet. Variablen, die in diesem Bereich der Konfigurations-Datei festgelegt werden, werden als HTML-Ausgabe geparst, indem jede Variable mit geschweiften Klammern innerhalb der HTML-Ausgabe mit den entsprechenden Variablen-Daten ersetzt wird. Zum Beispiel, wenn `foo="bar"`, dann wird jedes Exemplar mit `<p>{foo}</p>` innerhalb der HTML-Ausgabe zu `<p>bar</p>`.

##### "theme"
- Standard-Thema für CIDRAM verwenden.

##### "Magnification"
- Schriftvergrößerung. Standardeinstellung = 1.

##### "css_url"
- Die Template-Datei für benutzerdefinierte Themes verwendet externe CSS-Regeln, wobei die Template-Datei für das normale Theme interne CSS-Regeln verwendet. Um CIDRAM anzuweisen, die Template-Datei für benutzerdefinierte Themes zu verwenden, geben Sie die öffentliche HTTP-Adresse von den CSS-Dateien des benutzerdefinierten Themes mit der `css_url`-Variable an. Wenn Sie diese Variable leer lassen, wird CIDRAM die Template-Datei für das normale Theme verwenden.

#### "PHPMailer" (Kategorie)
PHPMailer Konfiguration.

##### "EventLog"
- Eine Datei zum Protokollieren aller Ereignisse in Bezug auf PHPMailer. Geben Sie einen Dateinamen an oder lassen Sie die Option zum Deaktivieren leer.

##### "SkipAuthProcess"
- Wenn Sie diese Direktive auf `true` setzen, wird PHPMailer angewiesen, den normalen Authentifizierungsprozess zu überspringen, der normalerweise beim Senden von E-Mails über SMTP auftritt. Dies sollte vermieden werden, da das Überspringen dieses Prozesses ausgehende E-Mails an MITM-Angriffe aussetzen kann. Dies kann jedoch in Fällen erforderlich sein, in denen dieser Prozess die Verbindung von PHPMailer zu einem SMTP-Server verhindert.

##### "Enable2FA"
- Diese Direktive bestimmt, ob 2FA für Front-End-Konten verwendet werden soll.

##### "Host"
- Der SMTP-Host zum Senden von ausgehende E-Mails.

##### "Port"
- Die Portnummer zum Senden von ausgehende E-Mails. Standardeinstellung = 587.

##### "SMTPSecure"
- Das Protokoll zum Senden von E-Mails über SMTP (TLS oder SSL).

##### "SMTPAuth"
- Diese Direktive bestimmt, ob SMTP-Sitzungen authentifiziert werden sollen (sollte normalerweise in Ruhe gelassen werden).

##### "Username"
- @todo@

##### "Password"
- @todo@

##### "setFromAddress"
- @todo@

##### "setFromName"
- @todo@

##### "addReplyToAddress"
- @todo@

##### "addReplyToName"
- @todo@

---


### 7. <a name="SECTION7"></a>SIGNATURENFORMAT

*Siehe auch:*
- *[Was ist eine "Signatur"?](#WHAT_IS_A_SIGNATURE)*

#### 7.0 GRUNDLAGEN (FÜR SIGNATURDATEIEN)

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
- Legal
- Malware

#### 7.1 TAGS

##### 7.1.0 SEKTION-TAGS

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

Die gleiche Logik kann auch zum Trennen anderer Arten von Tags angewendet werden.

Insbesondere, Sektion-Tags können beim Debuggen sehr nützlich sein, wenn Falsche-Positives auftreten, durch Bereitstellung einer Möglichkeit, die genaue Ursache des Problems zu finden, und können beim Filtern von Protokolleinträgen beim Anzeigen von Protokolldateien über die Front-End-Protokollseite sehr nützlich sein (Sektionsnamen sind über die Front-End-Protokollseite anklickbar und können als Filterkriterien verwendet werden). Wenn für bestimmte Signaturen Sektion-Tags weggelassen werden, verwendet CIDRAM beim Auslösen dieser Signaturen den Namen der Signaturdatei zusammen mit dem Typ der blockierten IP-Adresse (IPv4 oder IPv6) als Fallback, daher sind Sektion-Tags völlig optional. Sie können jedoch in einigen Fällen empfohlen werden, z.B. wenn die Signaturdateien vage benannt werden oder wenn es sonst schwierig ist, die Quelle der Signaturen eindeutig zu identifizieren, die eine gesperrte Anforderung verursachen.

##### 7.1.1 ABLAUF-TAGS

Wenn Sie möchten dass Signaturen nach einiger Zeit wird ablaufen, in einer Weise ähnlicher wie Sektion-Tags, können Sie ein "Ablauf-Tag" verwenden um anzugeben wann Signaturen nicht mehr gültig sind. Ablauf-Tags verwenden das Format "JJJJ.MM.TT" (siehe Beispiel unten).

```
# Sektion 1.
1.2.3.4/32 Deny Generic
2.3.4.5/32 Deny Generic
Expires: 2016.12.31
```

Abgelaufene Signaturen werden niemals auf irgendeine Anfrage ausgelöst, egal was passiert.

##### 7.1.2 URSPRUNGS-TAGS

Wenn Sie das Herkunftsland für eine bestimmte Signatur angeben möchten, können Sie dies mit einem "Ursprungs-Tag" tun. Ein Ursprungs-Tag akzeptiert einen "[ISO 3166-1 Alpha-2](https://de.wikipedia.org/wiki/ISO-3166-1-Kodierliste)"-Code, der dem Ursprungsland für die Signaturen entspricht, auf die es angewendet wird. Diese Codes müssen in Großbuchstaben geschrieben werden (Kleinbuchstaben oder Groß-/Kleinschreibung werden nicht korrekt dargestellt). Wenn ein Ursprungs-Tag verwendet wird, wird es zum Log-Feld "Warum blockierte" hinzugefügt für alle Anfragen, die aufgrund der Signaturen blockiert wurden, auf die das Tag angewendet wurde.

Wenn die optionale Komponente "flags CSS" installiert ist, werden beim Anzeigen von Protokolldateien über die Front-End-Protokollseite Informationen, die durch Ursprungs-Tags angehängt sind, durch das Flag des Landes ersetzt, das dieser Information entspricht. Diese Informationen, ob in roher Form oder als Länderflagge, sind anklickbar und, nach dem Anklicken, filtern Log-Einträge durch andere, ähnlich identifizierende Log-Einträge (somit erlauben denjenigen, die auf die Protokollseite zugreifen, nach Herkunftsland zu filtern).

Hinweis: Technisch gesehen ist, dies keine Form von Geolokalisierung, weil es keine spezifischen Informationen sucht in Bezug auf eingehende IPs, aber stattdessen, erlaubt uns einfach, ein Herkunftsland für alle Anfragen anzugeben, die durch bestimmte Signaturen blockiert werden. Innerhalb desselben Signatur-Sektionen sind mehrere Ursprungs-Tags zulässig.

Hypothetisches Beispiel:

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

Alle Tags können zusammen verwendet werden, und alle Tags sind optional (siehe Beispiel unten).

```
# Beispiel Sektion.
1.2.3.4/32 Deny Generic
Origin: US
Tag: Beispiel Sektion
Expires: 2016.12.31
```

##### 7.1.3 ABWERTUNGS-TAGS

Wenn eine große Anzahl von Signaturdateien installiert und aktiv verwendet wird, können Installationen sehr komplex werden, und es können einige Signaturen vorhanden sein, die sich überlappen. Um in diesen Fällen zu verhindern, dass während Blockereignissen mehrere überlappende Signaturen ausgelöst werden, können Abwertungs-Tags verwendet werden, um bestimmte Signatur-Sektionen in Fällen zu verschieben, in denen eine andere spezifische Signaturdatei installiert und aktiv verwendet wird. Dies kann nützlich sein, wenn einige Signaturen häufiger als andere aktualisiert werden, um die weniger häufig aktualisierten Signaturen zugunsten der häufiger aktualisierten Signaturen zu verschieben.

Abwertungs-Tags werden ähnlich wie andere Tags verwendet. Der Wert des Tags sollte mit einer installierten und aktiv genutzten Signaturdatei übereinstimmen, auf die der Tag verschieben werden soll.

Beispiel:

```
1.2.3.4/32 Deny Generic
Origin: AA
2.3.4.5/32 Deny Generic
Origin: BB
Defers to: preferred_signatures.dat
```

#### 7.2 YAML

##### 7.2.0 YAML GRUNDLAGEN

Eine vereinfachte Form von YAML-Markup kann in Signaturdateien verwendet werden, um Verhalten und Einstellungen spezifisch für einzelne Signatur-Sektionen zu definieren. Dies kann nützlich sein wenn Sie der Wert von Ihrer Konfiguration-Richtlinien möchten zu variieren auf der Grundlage von individuell Signaturen und Signatur-Sektionen (beispielsweise; wenn Sie eine E-Mail-Adresse zu bieten für Support-Tickets möchten, für irgendein Benutzer dass durch eine spezifische Signatur blockiert, aber Sie eine E-Mail-Adresse zu bieten für Support-Tickets möchten nicht für Benutzer durch andere Signaturen blockiert; wenn Sie möchten dass bestimmte spezifisch Signaturen eine Seitenumleitung zu auslösen; wenn Sie einen Signatur-Sektion zu markieren für Verwendung mit reCAPTCHA möchten; wenn Sie um blockierte Zugriffsversuche zu protokollieren in einzelne Dateien auf der Grundlage von einzelnen Signaturen und/oder Signatur-Sektionen möchten).

Die Verwendung von YAML-Markup in den Signaturdateien ist völlig optional (dh, Sie können es verwenden, wenn Sie dies möchten, aber Sie sind nicht verpflichtet dies zu tun), Und ist in der Lage die meisten (aber nicht alles) Konfiguration-Richtlinien zu nutzen.

Beachten: YAML-Markup-Implementierung in CIDRAM ist sehr einfach und sehr begrenzt; Es ist beabsichtigt um Anforderungen zu erfüllen dass spezifisch für CIDRAM sind, in einer Weise dass die Vertrautheit mit YAML-Markup hat, aber weder folgt noch mit den offiziellen Spezifikationen entspricht (und wird sich daher nicht in der gleichen Weise als gründlichere Implementierungen anderswo verhalten, und möglicherweise nicht für andere Projekte anderswo geeignet werden).

In CIDRAM, YAML-Markup-Segmente dem Skript identifiziert werden durch drei Bindestriche ("---"), und neben ihren enthaltenden Signatur-Sektionen enden durch doppelte Zeilenumbrüche. Ein typisches YAML-Markup-Segment innerhalb eine Signatur-Sektion besteht aus drei Bindestrichen auf einer Linie sofort nach der Liste der CIDRs und alle Tags, gefolgt von einer zweidimensionalen Liste von Schlüssel-Wert-Paaren (erste Dimension, Konfigurationsdirektivenkategorien; zweite Dimension, Konfigurationsdirektiven) für die Konfigurationsdirektiven dass geändert werden sollen (und auf welche Werte) wenn eine Signatur innerhalb dass Signatur-Sektion ausgelöst wird (siehe nachfolgende Beispiele).

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

*Beachten: Standardmäßig, eine reCAPTCHA-Instanz wird NUR dem Benutzer angeboten wenn reCAPTCHA aktiviert ist (entweder mit "usemode" als 1, oder "usemode" als 2 mit "enabled" als true), und wenn genau EINE Signatur ausgelöst wurde (nicht mehr und nicht weniger; wenn mehrere Signaturen ausgelöst werden, eine reCAPTCHA-Instanz wird NICHT angeboten werden). Jedoch, dieses Verhalten kann über die Anweisung "signature_limit" geändert werden.*

#### 7.3 ZUSATZINFORMATION

In Ergänzung, wenn Sie möchten dass CIDRAM wird bestimmte Sektionen innerhalb irgendein der Signaturdateien vollständig ignoriert, können Sie die Datei `ignore.dat` verwenden, um festzulegen welche Sektionen zu ignorieren. Auf einer neuen Linie, schreiben `Ignore`, gefolgt von einem Leerzeichen, gefolgt durch dem Namen das Sektion welche Sie möchten für CIDRAM zu ignorieren (siehe Beispiel unten).

```
Ignore Sektion 1
```

#### 7.4 <a name="MODULE_BASICS"></a>GRUNDLAGEN (FÜR MODULE)

Module können verwendet werden, um die Funktionalität von CIDRAM zu erweitern, zusätzliche Aufgaben auszuführen oder zusätzliche Logik zu verarbeiten. Werden sie verwendet typischerweise, wenn eine Anforderung auf einer anderen Basis als der ursprünglichen IP-Adresse blockiert werden muss (und daher, wenn eine CIDR-Signatur nicht ausreicht, um die Anfrage zu blockieren). Module werden als PHP-Dateien geschrieben, und daher werden Modul-Signaturen typischerweise als PHP-Code geschrieben.

Einige gute Beispiele für CIDRAM-Module finden Sie hier:
- https://github.com/CIDRAM/CIDRAM-Extras/tree/master/modules

Eine Vorlage zum Schreiben neuer Module finden Sie hier:
- https://github.com/CIDRAM/CIDRAM-Extras/blob/master/modules/module_template.php

Da Module als PHP-Dateien geschrieben werden, können Sie Ihre Module beliebig strukturieren und schreiben Sie Ihre Modul-Signaturen wie Sie wollen (im Rahmen des Zumutbaren für was mit PHP möglich ist), wenn Sie mit der CIDRAM-Codebasis ausreichend vertraut sind. Jedoch, zu Ihrer eigenen Bequemlichkeit und aus Gründen der besseren gegenseitigen Verständlichkeit zwischen vorhandenen Modulen und Ihren eigenen, es wird empfohlen, die oben verlinkte Vorlage zu analysieren, um die von ihr bereitgestellte Struktur und das Format verwenden zu können.

*Beachten: Wenn Sie nicht komfortabel mit PHP-Code arbeiten, wird das Schreiben eigener Module nicht empfohlen.*

Einige Funktionen werden von CIDRAM für Module zur Verfügung gestellt, die es einfacher machen sollten eigene Module zu schreiben. Informationen zu dieser Funktionalität werden im Folgenden beschrieben.

#### 7.5 MODUL-FUNKTIONALITÄT

##### 7.5.0 "$Trigger"

Modul-Signaturen werden typischerweise mit "$Trigger" geschrieben. In den meisten Fällen ist diese Closure für das Schreiben von Modulen wichtiger als alles andere Closure.

"$Trigger" akzeptiert 4 Parameter: "$Condition", "$ReasonShort", "$ReasonLong" (optional), und "$DefineOptions" (optional).

Die Wahrheit von "$Condition" wird ausgewertet, und wenn dies wahr/true, wird die Signatur "ausgelöst". Wenn falsch/false, wird die Signatur *nicht* "ausgelöst". "$Condition" enthält typischerweise PHP-Code, um eine Bedingung auszuwerten, die dazu führen sollte, dass eine Anfrage blockiert wird.

"$ReasonShort" wird im Feld "Warum blockierte" angegeben, wenn die Signatur "ausgelöst" wird.

"$ReasonLong" ist eine optionale Nachricht, die dem Benutzer/Client angezeigt wird, wenn sie blockiert sind, um zu erklären, warum sie blockiert wurden. Verwendet die Standardmeldung "Zugriff verweigert", wenn sie weggelassen wird.

"$DefineOptions" ist ein optionales Array, das Schlüssel/Wert-Paare enthält, mit denen Konfigurationsoptionen definiert werden, die für die Anforderungsinstanz spezifisch sind. Konfigurationsoptionen werden angewendet, wenn die Signatur "ausgelöst" wird.

"$Trigger" gibt wahr/true zurück, wenn die Signatur "ausgelöst" wird, und falsch/false, wenn dies nicht der Fall ist.

Um diese Closure in Ihrem Modul zu verwenden, denken Sie daran sie zuerst vom übergeordneten Geltungsbereich zu übernehmen:
```PHP
$Trigger = $CIDRAM['Trigger'];
```

##### 7.5.1 "$Bypass"

Signatur-Bypass werden normalerweise mit "$Bypass" geschrieben.

"$Bypass" akzeptiert 3 Parameter: "$Condition", "$ReasonShort", und "$DefineOptions" (optional).

Die Wahrheit von "$Condition" wird ausgewertet, und wenn dies wahr/true, wird die Signatur "ausgelöst". Wenn falsch/false, wird die Signatur *nicht* "ausgelöst". "$Condition" enthält typischerweise PHP-Code, um eine Bedingung auszuwerten, die dazu führen sollte *nicht*, dass eine Anfrage blockiert wird.

"$ReasonShort" wird im Feld "Warum blockierte" angegeben, wenn der Bypass "ausgelöst" wird.

"$DefineOptions" ist ein optionales Array, das Schlüssel/Wert-Paare enthält, mit denen Konfigurationsoptionen definiert werden, die für die Anforderungsinstanz spezifisch sind. Konfigurationsoptionen werden angewendet, wenn die Bypass "ausgelöst" wird.

"$Bypass" gibt wahr/true zurück, wenn die Bypass "ausgelöst" wird, und falsch/false, wenn dies nicht der Fall ist.

Um diese Closure in Ihrem Modul zu verwenden, denken Sie daran sie zuerst vom übergeordneten Geltungsbereich zu übernehmen:
```PHP
$Bypass = $CIDRAM['Bypass'];
```

##### 7.5.2 "$CIDRAM['DNS-Reverse']"

Dies kann verwendet werden, um den Hostnamen einer IP-Adresse abzurufen. Wenn Sie ein Modul zum Blockieren von Hostnamen erstellen möchten, könnte diese Closure nützlich sein.

Beispiel:
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

#### 7.6 MODUL VARIABLEN

Module werden in ihrem eigenen Umfang ausgeführt, und alle Variablen, die von einem Modul definiert werden, sind für andere Module oder das übergeordnete Skript nicht zugänglich, es sei denn, sie sind im Array "$CIDRAM" gespeichert (alles andere wird gelöscht, nachdem die Modulausführung abgeschlossen ist).

Im Folgenden finden Sie einige allgemeine Variablen, die für Ihr Modul nützlich sein könnten:

Variable | Beschreibung
----|----
`$CIDRAM['BlockInfo']['DateTime']` | Das aktuelle Datum und die Uhrzeit.
`$CIDRAM['BlockInfo']['IPAddr']` | IP-Adresse für die aktuelle Anfrage.
`$CIDRAM['BlockInfo']['ScriptIdent']` | CIDRAM Script-Version.
`$CIDRAM['BlockInfo']['Query']` | Die Abfrage (query) für die aktuelle Anfrage.
`$CIDRAM['BlockInfo']['Referrer']` | Der Referrer für die aktuelle Anfrage (falls vorhanden).
`$CIDRAM['BlockInfo']['UA']` | Der Benutzeragent (user agent) für die aktuelle Anfrage.
`$CIDRAM['BlockInfo']['UALC']` | Der Benutzeragent (user agent) für die aktuelle Anfrage (als Kleinbuchstaben).
`$CIDRAM['BlockInfo']['ReasonMessage']` | Die Nachricht, die dem Benutzer/Client für die aktuelle Anfrage angezeigt wird, wenn sie blockiert sind.
`$CIDRAM['BlockInfo']['SignatureCount']` | Die Anzahl der Signaturen, die für die aktuelle Anfrage ausgelöst wurden.
`$CIDRAM['BlockInfo']['Signatures']` | Referenzinformationen für alle Signaturen, die für die aktuelle Anforderung ausgelöst wurden.
`$CIDRAM['BlockInfo']['WhyReason']` | Referenzinformationen für alle Signaturen, die für die aktuelle Anforderung ausgelöst wurden.

---


### 8. <a name="SECTION8"></a>BEKANNTE KOMPATIBILITÄTSPROBLEME

Die folgenden Pakete und Produkte haben sich als inkompatibel mit CIDRAM erwiesen:
- __[Endurance Page Cache](https://github.com/CIDRAM/CIDRAM/issues/52)__
- __[Mix.com](https://github.com/CIDRAM/CIDRAM/issues/80)__

Module wurden zur Verfügung gestellt, um sicherzustellen, dass die folgenden Pakete und Produkte mit CIDRAM kompatibel sind:
- __[BunnyCDN](https://github.com/CIDRAM/CIDRAM/issues/56)__

*Siehe auch: [Kompatibilitätstabellen](https://maikuolan.github.io/Compatibility-Charts/).*

---


### 9. <a name="SECTION9"></a>HÄUFIG GESTELLTE FRAGEN (FAQ)

- [Was ist eine "Signatur"?](#WHAT_IS_A_SIGNATURE)
- [Was ist ein "CIDR"?](#WHAT_IS_A_CIDR)
- [Was ist ein "Falsch-Positiv"?](#WHAT_IS_A_FALSE_POSITIVE)
- [Kann CIDRAM ganze Länder blockieren?](#BLOCK_ENTIRE_COUNTRIES)
- [Wie häufig werden Signaturen aktualisiert?](#SIGNATURE_UPDATE_FREQUENCY)
- [Ich habe ein Problem bei der Verwendung von CIDRAM und ich weiß nicht was ich tun soll! Bitte helfen Sie!](#ENCOUNTERED_PROBLEM_WHAT_TO_DO)
- [CIDRAM hat mich von einer Website blockiert die ich besuchen möchte! Bitte helfen Sie!](#BLOCKED_WHAT_TO_DO)
- [Ich möchte CIDRAM mit einer PHP-Version älter als 5.4.0 verwenden; Kannst du helfen?](#MINIMUM_PHP_VERSION)
- [Kann ich eine einzige CIDRAM-Installation verwenden, um mehrere Domains zu schützen?](#PROTECT_MULTIPLE_DOMAINS)
- [Ich möchte keine Zeit damit verbringen (es zu installieren, es richtig zu ordnen, u.s.w.); Kann ich dich einfach bezahlen, um alles für mich zu tun?](#PAY_YOU_TO_DO_IT)
- [Kann ich Sie oder einen der Entwickler dieses Projektes für private Arbeit einstellen?](#HIRE_FOR_PRIVATE_WORK)
- [Ich brauche spezialisierte Modifikationen, Anpassungen, u.s.w.; Kannst du helfen?](#SPECIALIST_MODIFICATIONS)
- [Ich bin ein Entwickler, Website-Designer oder Programmierer. Kann ich die Arbeit an diesem Projekt annehmen oder anbieten?](#ACCEPT_OR_OFFER_WORK)
- [Ich möchte zum Projekt beitragen; Darf ich das machen?](#WANT_TO_CONTRIBUTE)
- [Kann ich cron verwenden, um automatisch zu aktualisieren?](#CRON_TO_UPDATE_AUTOMATICALLY)
- [Was sind "Verstöße"?](#WHAT_ARE_INFRACTIONS)
- [Kann CIDRAM Hostnamen blockieren?](#BLOCK_HOSTNAMES)
- [Was kann ich für "default_dns" verwenden?](#WHAT_CAN_I_USE_FOR_DEFAULT_DNS)
- [Kann ich CIDRAM verwenden, um andere Dinge als Websites zu schützen (z.B. E-Mail-Server, FTP, SSH, IRC u.s.w.)?](#PROTECT_OTHER_THINGS)
- [Werden Probleme auftreten, wenn ich CIDRAM gleichzeitig mit CDNs oder Caching-Diensten verwende?](#CDN_CACHING_PROBLEMS)
- [Wird CIDRAM meine Website vor DDoS-Angriffen schützen?](#DDOS_ATTACKS)
- [Wenn ich Module oder Signaturdateien über die Update-Seite aktiviere oder deaktiviere, sortiert sie diese alphanumerisch in der Konfiguration. Kann ich die Art der Sortierung ändern?](#CHANGE_COMPONENT_SORT_ORDER)

#### <a name="WHAT_IS_A_SIGNATURE"></a>Was ist eine "Signatur"?

Im Kontext von CIDRAM, eine "Signatur" bezieht sich auf Daten, die als Indikator/Identifikator fungieren, für etwas Bestimmtes dass wir suchen (normalerweise eine IP-Adresse oder CIDR), und enthält einige Anweisungen für CIDRAM, erzählen es der beste Weg zu reagieren, wenn es begegnet was wir suchen. Eine typische Signatur für CIDRAM sieht so aus:

Für "Signaturdateien":

`1.2.3.4/32 Deny Generic`

Für "Modulen":

```PHP
$Trigger(strpos($CIDRAM['BlockInfo']['UA'], 'Foobar') !== false, 'Foobar-UA', 'User agent "Foobar" not allowed.');
```

*Beachten: Signaturen für "Signaturdateien", und Signaturen für "Module", sind nicht dasselbe.*

Oft (aber nicht immer), Signaturen werden in Gruppen zusammengebunden, als "Signatur-Sektionen" zu bilden, oft begleitet von Kommentaren, Markup und/oder verwandten Metadaten das kann verwendet werden, um zusätzlichen Kontext zu bieten der Signaturen und/oder weitere Anweisung.

#### <a name="WHAT_IS_A_CIDR"></a>Was ist ein "CIDR"?

"CIDR" ist ein Akronym für "Classless Inter-Domain Routing" *[[1](https://de.wikipedia.org/wiki/Classless_Inter-Domain_Routing), [2](http://whatismyipaddress.com/cidr)]*, und es ist dieses Akronym, das als Teil des Namens für dieses Paket, "CIDRAM", verwendet wird, um ein größeres Akronym zu bilden, "Classless Inter-Domain Routing Access Manager".

Aber, im Kontext von CIDRAM (sowie, in dieser Dokumentation, in Diskussionen über CIDRAM, oder in den CIDRAM-Sprachdaten), wann immer ein "CIDR" (Singular) oder "CIDRs" (Plural) ist erwähnt (und somit, wobei wir diese Wörter als Substantive in ihrem eigenen Recht verwenden, im Gegensatz zu Akronymen), was beabsichtigt und gemeint ist ein Subnetz (oder Subnetze), ausgedrückt mit CIDR-Notation. Der Grund dafür, dass CIDR (oder CIDRs) verwendet wird, anstelle von Subnetz (oder Subnetze) ist um zu klären, es ist Subnetze mit CIDR-Notation ausgedrückt auf die wir uns beziehen (weil CIDR-Notation ist nur eine von verschiedenen Möglichkeiten, wie Subnetze ausgedrückt werden können). CIDRAM könnte daher als "Subnetz Access Manager" betrachtet werden.

Obwohl diese doppelte Bedeutung von "CIDR" in einigen Fällen mehrdeutig werden kann, diese erklärung, zusammen mit dem Kontext zur Verfügung gestellt, sollte helfen diese Mehrdeutigkeit zu lösen.

#### <a name="WHAT_IS_A_FALSE_POSITIVE"></a>Was ist ein "Falsch-Positiv"?

Der Begriff "Falsch-Positiv" (*Alternative: "falsch-positiv Fehler"; "falscher Alarm"*; Englisch: *false positive*; *false positive error*; *false alarm*), sehr einfach beschrieben, und in einem verallgemeinerten Kontext, verwendet wird, wenn eine Bedingung zu testen und wenn die Ergebnisse positiv sind, um die Ergebnisse dieser Tests zu entnehmen (dh, die Bedingung bestimmt wird positiv oder wahr), aber sind zu erwarten sein (oder sollte gewesen) negativ (dh, der Zustand, in Wirklichkeit, ist negativ oder falsch). Eine "Falsch-Positiv" könnte analog zu "weinen Wolf" betrachtet (wobei die Bedingung geprüft wird, ob es ein Wolf in der Nähe der Herde ist, die Bedingung "falsch" ist in dass es keinen Wolf in der Nähe der Herde, und die Bedingung wird als "positiv" berichtet durch die Schäfer durch Aufruf "Wolf, Wolf"), oder analog zu Situationen in medizinischen Tests, wobei ein Patient als mit eine Krankheit diagnostiziert, wenn sie in Wirklichkeit haben sie keine solche Krankheit.

Einige andere Begriffe verwendet: "Wahr-Positiv", "Wahr-Negativ" und "Falsch-Negativ". Eine "Wahr-Positiv" ist, wenn die Ergebnisse des Tests und der wahren Zustand beide wahr sind (oder "Positiv"), und eine "Wahr-Negativ" ist, wenn die Ergebnisse des Tests und der wahren Zustand beide falsch sind (oder "Negativ"); Eine "Wahr-Positiv" oder Eine "Wahr-Negativ" gilt als eine "korrekte Folgerung" zu sein. Der Antithese von einem "Falsch-Positiv" ist eine "Falsch-Negativ"; Eine "Falsch-Negativ" ist, wenn die Ergebnisse des Tests negativ sind (dh, die Bedingung bestimmt wird negativ oder falsch zu sein), aber sind zu erwarten sein (oder sollte gewesen) positiv (dh, der Zustand, in Wirklichkeit, ist "positiv", oder "wahr").

Im Kontext der CIDRAM, Diese Begriffe beziehen sich auf der Signaturen von CIDRAM, und was/wen sie blockieren. Wenn CIDRAM Blöcke eine IP-Adresse wegen schlechten, veraltete oder falsche Signaturen, sollte aber nicht so getan haben, oder wenn sie es tut, so aus den falschen Gründen, wir beziehen sich auf dieses Ereignis als eine "Falsch-Positiv". Wenn CIDRAM, aufgrund unvorhergesehener Bedrohungen, fehlende Signaturen oder Defizite in ihren Signaturen, versagt eine IP-Adresse zu blockieren, die blockiert werden sollte, wir beziehen sich auf dieses Ereignis als eine "verpasste Erkennung" (das entspricht einem "Falsch-Negativ").

Dies kann durch die folgende Tabelle zusammengefasst werden:

&nbsp; | CIDRAM sollte *KEINE* IP-Adresse blockieren | CIDRAM *SOLLTE* eine IP-Adresse blockieren
---|---|---
CIDRAM tut blockiert eine IP-Adresse *NICHT* | Wahr-Negativ (korrekte Folgerung) | Verpasste Erkennung (analog zu Falsch-Negativ)
CIDRAM *TUT* blockiert eine IP-Adresse | __Falsch-Positiv__ | True-Positiv (korrekte Folgerung)

#### <a name="BLOCK_ENTIRE_COUNTRIES"></a>Kann CIDRAM ganze Länder blockieren?

Ja. Der einfachste Weg für dies zu erreichen wäre einige der optionalen Landblocklisten bereitgestellt von Macmathan zu installieren. Dies kann mit einigen einfachen Klicks direkt aus der Aktualisierungsseite der Front-End erfolgen, oder, wenn du es vorziehe dass der Front-End deaktiviert bleiben, indem Sie sie direkt aus der **[optionalen Blocklisten-Download-Seite](https://macmathan.info/blocklists)** herunterladen, zum vault hochladen, und zitieren ihre Namen in den entsprechenden Konfigurationsdirektiven.

#### <a name="SIGNATURE_UPDATE_FREQUENCY"></a>Wie häufig werden Signaturen aktualisiert?

Die Aktualisierungshäufigkeit hängt von den betreffenden Signaturdateien ab. In der Regel, alle Betreuer für CIDRAM Signaturdateien versuchen ihre Signaturen so aktuell wie möglich zu halten, aber da haben wir alle anderen Verpflichtungen, unser Leben außerhalb des Projekts, und da für unsere Bemühungen um das Projekt, keiner von uns wird finanziell entschädigt (d.h., bezahlt), ein genauer Aktualisierungs-Zeitplan kann nicht garantiert werden. In der Regel, Signaturen werden aktualisiert, wann immer es genügend Zeit gibt sie zu aktualisieren, und Betreuer versuchen auf der Grundlage der Notwendigkeit und auf der Grundlage wie häufig Veränderungen unter den Bereichen auftreten zu priorisieren. Hilfe wird immer geschätzt, wenn Sie bereit bist, irgendwelche anzubieten.

#### <a name="ENCOUNTERED_PROBLEM_WHAT_TO_DO"></a>Ich habe ein Problem bei der Verwendung von CIDRAM und ich weiß nicht was ich tun soll! Bitte helfen Sie!

- Verwenden Sie die neueste Version der Software? Verwenden Sie die neuesten Versionen Ihrer Signaturdateien? Wenn die Antwort auf eine dieser beiden Fragen nein ist, Versuchen alles zuerst zu aktualisieren, und überprüfen Sie ob das Problem weiterhin besteht. Wenn es weiterhin besteht, lesen Sie weiter.
- Haben Sie alle der Dokumentation überprüft? Wenn nicht, bitte tun Sie dies. Wenn das Problem nicht mit der Dokumentation gelöst werden kann, lesen Sie weiter.
- Haben Sie die **[Issues-Seite](https://github.com/CIDRAM/CIDRAM/issues)** überprüft, ob das Problem vorher erwähnt wurde? Wenn es vorher erwähnt wurde, überprüfen Sie ob irgendwelche Vorschläge, Ideen und/oder Lösungen zur Verfügung gestellt wurden, und folge wie nötig um das Problem zu lösen.
- Wenn das Problem weiterhin besteht, bitte fragen Sie nach Hilfe, indem Sie auf der Issues-Seite ein neues Issue erstellen.

#### <a name="BLOCKED_WHAT_TO_DO"></a>CIDRAM hat mich von einer Website blockiert die ich besuchen möchte! Bitte helfen Sie!

CIDRAM bietet ein Mittel für Website-Besitzer, um unerwünschten Verkehr zu blockieren, aber es liegt in der Verantwortung der Website-Besitzer, selbst zu entscheiden, wie sie CIDRAM nutzen wollen. Im Falle der falschen-Positiven in Bezug auf der Signaturdateien die normalerweise mit CIDRAM enthalten sind, Korrekturen können vorgenommen werden, aber in Bezug auf unblockiert von bestimmten Websites werden, Sie müssen mit den Besitzern der betreffenden Webseiten sprechen. In Fällen in denen Korrekturen vorgenommen werden, zumindest müssen sie ihre Signaturdateien und/oder Installation aktualisieren, und in anderen Fällen (wie zum Beispiel, wo sie ihre Installation geändert haben, wo sie ihre eigenen benutzerdefinierten Signaturen erstellt haben, u.s.w.), Verantwortung für Ihr Problem zu lösen ist ganz ihre, und ist ganz außerhalb unserer Kontrolle.

#### <a name="MINIMUM_PHP_VERSION"></a>Ich möchte CIDRAM mit einer PHP-Version älter als 5.4.0 verwenden; Kannst du helfen?

Nein. PHP 5.4.0 erreichte offiziellen EoL ("End of Life" oder Ende des Lebens) im Jahr 2014, und Sicherheits-Unterstützung wurde im Jahr 2015 beendet. Zum Zeitpunkt des Schreibens dieses, es ist 2017 und PHP 7.1.0 ist bereits vorhanden. An dieser Zeitpunkt, Unterstützung wird für die Verwendung von CIDRAM mit PHP 5.4.0 und allen verfügbaren neueren PHP Versionen zur Verfügung, aber wenn Sie versuchen CIDRAM mit älteren PHP Versionen zu verwenden, Unterstützung wird zur Verfügung nicht.

*Siehe auch: [Kompatibilitätstabellen](https://maikuolan.github.io/Compatibility-Charts/).*

#### <a name="PROTECT_MULTIPLE_DOMAINS"></a>Kann ich eine einzige CIDRAM-Installation verwenden, um mehrere Domains zu schützen?

Ja. CIDRAM-Installationen sind natürlich nicht auf bestimmte Domains gesperrt, und kann daher zum Schutz mehrerer Domains verwendet werden. Allgemein, wir verweisen auf CIDRAM-Installationen die nur eine Domain schützen als "Single-Domain-Installationen", und Wir verweisen auf CIDRAM-Installationen die mehrere Domains und/oder Subdomains schützen als "Multi-Domain-Installationen". Wenn Sie eine Multi-Domain-Installation betreiben und müssen verschiedene Sätze von Signaturdateien für verschiedene Domains verwenden, oder für verschiedene Domains muss unterschiedliche Konfiguration verwenden, das ist möglich. Nach dem Laden der Konfigurationsdatei (`config.ini`), CIDRAM prüft auf die Existenz einer "Konfiguration-Überschreibt Datei", die für die Domain (oder Subdomain) spezifisch angefordert ist (`die-domain-angefordert.tld.config.ini`), und wenn gefunden, alle von der Konfiguration-Überschreibt Datei definierten Konfigurationswerte wird für die Ausführungsinstanz verwendet, anstelle der von der Konfigurationsdatei definierten Konfigurationswerte. Konfiguration-Überschreibt Dateien sind identisch mit der Konfigurationsdatei, und nach eigenem Ermessen, kann entweder die Gesamtheit aller Konfigurationsdirektiven für CIDRAM enthalten, oder was auch immer kleiner Unterabschnitt erforderlich ist die sich normalerweise von der Konfigurationsdatei definierten Konfigurationswerte unterscheidet. Konfiguration-Überschreibt Dateien werden nach der Domain für die sie bestimmt sind benannt (so zum Beispiel, wenn Sie eine Konfiguration-Überschreibt Dateien benötigen für die Domäne, `http://www.some-domain.tld/`, seine Konfiguration-Überschreibt Datei sollte benannt werden als `some-domain.tld.config.ini`, und sollte in der vault neben der Konfigurationsdatei `config.ini` platziert werden). Der Domains-Name für die Ausführungsinstanz wird aus dem `HTTP_HOST`-Header der Anforderung abgeleitet; "www" wird ignoriert.

#### <a name="PAY_YOU_TO_DO_IT"></a>Ich möchte keine Zeit damit verbringen (es zu installieren, es richtig zu ordnen, u.s.w.); Kann ich dich einfach bezahlen, um alles für mich zu tun?

Vielleicht. Dies wird von Fall zu Fall berücksichtigt. Sag uns was du brauchst, was du anbietet, und wir werden Ihnen sagen, ob wir helfen können.

#### <a name="HIRE_FOR_PRIVATE_WORK"></a>Kann ich Sie oder einen der Entwickler dieses Projektes für private Arbeit einstellen?

*Siehe oben.*

#### <a name="SPECIALIST_MODIFICATIONS"></a>Ich brauche spezialisierte Modifikationen, Anpassungen, u.s.w.; Kannst du helfen?

*Siehe oben.*

#### <a name="ACCEPT_OR_OFFER_WORK"></a>Ich bin ein Entwickler, Website-Designer oder Programmierer. Kann ich die Arbeit an diesem Projekt annehmen oder anbieten?

Ja. Unsere Lizenz verbietet dies nicht.

#### <a name="WANT_TO_CONTRIBUTE"></a>Ich möchte zum Projekt beitragen; Darf ich das machen?

Ja. Beiträge zum Projekt sind sehr willkommen. Bitte beachten Sie "CONTRIBUTING.md" für weitere Informationen.

#### <a name="CRON_TO_UPDATE_AUTOMATICALLY"></a>Kann ich cron verwenden, um automatisch zu aktualisieren?

Ja. Eine API ist in das Front-End integriert, um über externe Skripte mit der Update-Seite zu interagieren. Ein separates Skript, "[Cronable](https://github.com/Maikuolan/Cronable)", ist verfügbar, und kann von Ihrem Cron-Manager oder Cron-Scheduler verwendet werden, um dieses und andere unterstützte Pakete automatisch zu aktualisieren (dieses Skript enthält eine eigene Dokumentation).

#### <a name="WHAT_ARE_INFRACTIONS"></a>Was sind "Verstöße"?

"Verstöße" bestimmen, wann eine IP-Adresse, die noch nicht von bestimmten Signaturdateien blockiert wurde, für zukünftige Anforderungen blockiert werden soll, und sie sind eng mit IP-Tracking verbunden. Es gibt einige Funktionen und Module, die es ermöglichen, dass Anfragen aus anderen Gründen als der Ursprungs-IP blockiert werden (wie die Anwesenheit von User Agents von Hacktools oder Spambots, gefährliche Anfragen, gefälschte DNS und so weiter), und wenn dies passiert, eine "Verstöß" kann auftreten. Sie bieten eine Möglichkeit für IP-Adressen zu identifizieren, die unerwünschten Anforderungen entsprechen, die möglicherweise noch nicht von bestimmten Signaturdateien blockiert wurden. Verstöße entsprechen in der Regel 1-zu-1 der Häufigkeit, mit der eine IP blockiert wird, aber nicht immer (bei schweren Blockereignissen kann es zu einem Verstoßwert größer als eins kommen, und wenn "track_mode" false ist, verstöße für Blockereignisse, die ausschließlich von Signaturdateien ausgelöst werden, werden nicht auftreten).

#### <a name="BLOCK_HOSTNAMES"></a>Kann CIDRAM Hostnamen blockieren?

Ja. Dazu müssen Sie eine benutzerdefinierte Moduldatei erstellen. *Siehe: [GRUNDLAGEN (FÜR MODULE)](#MODULE_BASICS)*.

#### <a name="WHAT_CAN_I_USE_FOR_DEFAULT_DNS"></a>Was kann ich für "default_dns" verwenden?

Die IP eines jeden zuverlässigen DNS-Servers sollte in der Regel ausreichen. Wenn Sie nach Vorschlägen suchen, bieten [public-dns.info](https://public-dns.info/) und [OpenNIC](https://servers.opennic.org/) umfangreiche Listen bekannter öffentlicher DNS-Server. Alternativ, sehen Sie die folgende Tabelle:

IP | Operator
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

*Hinweis: Ich mache keine Angaben oder Garantien in Bezug auf die Datenschutzpraktiken, die Sicherheit, die Wirksamkeit oder die Zuverlässigkeit von DNS-Diensten, die aufgelistet sind oder nicht. Bitte machen Sie Ihre eigene Forschung, wenn Sie Entscheidungen über sie treffen.*

#### <a name="PROTECT_OTHER_THINGS"></a>Kann ich CIDRAM verwenden, um andere Dinge als Websites zu schützen (z.B. E-Mail-Server, FTP, SSH, IRC u.s.w.)?

Sie können (rechtlich), aber du solltest nicht (technisch; praktisch). Unsere Lizenz beschränkt nicht, welche Technologien implementieren CIDRAM, aber CIDRAM ist eine WAF (Web Application Firewall) und war schon immer zum Schutz von Webseiten gedacht. Da es nicht mit anderen Technologien entwickelt wurde, ist es unwahrscheinlich, dass es mit anderen Technologien effektiv ist, oder für anderen Technologien zuverlässigen Schutz bietet, die Umsetzung dürfte schwierig sein, und das Risiko von Falsch-Positivs und verpassten Erkennungen ist sehr hoch.

#### <a name="CDN_CACHING_PROBLEMS"></a>Werden Probleme auftreten, wenn ich CIDRAM gleichzeitig mit CDNs oder Caching-Diensten verwende?

Möglicherweise. Dies hängt von der Art des Dienstes ab und davon, wie Sie es verwenden. Im Algemeinen, wenn Sie nur statische Assets im Cache speichern (Bilder, CSS, u.s.w.; alles, was sich im Laufe der Zeit nicht ändert), sollten keine Probleme auftreten. Es kann jedoch Probleme geben, wenn Sie Daten zwischenspeichern, die andernfalls, wenn angefordert, typischerweise dynamisch generiert würde, oder wenn Sie die Ergebnisse von POST-Anfragen zwischenspeichern (dies würde Ihre Website und ihre Umgebung im Wesentlichen statisch machen, und es ist unwahrscheinlich, dass CIDRAM in einer obligatorisch statischen Umgebung einen nennenswerten Nutzen bringt). Es kann auch spezifische Konfigurationsanforderungen für CIDRAM geben, abhängig davon, welchen CDN oder Caching-Service Sie verwenden (Sie müssen sicherstellen, dass CIDRAM für den bestimmten CDN oder Caching-Dienst, den Sie verwenden, korrekt konfiguriert ist). Wenn CIDRAM nicht richtig konfiguriert wird, kann dies zu erheblich problematischen Falsch-Positive und verpassten Erkennungen verursachen.

#### <a name="DDOS_ATTACKS"></a>Wird CIDRAM meine Website vor DDoS-Angriffen schützen?

Kurze Antwort: Nein.

Etwas längere Antwort: CIDRAM wird dazu beitragen, die Auswirkungen unerwünschter Verkehr auf Ihre Website (dadurch reduzieren Sie die Bandbreitenkosten Ihrer Website) und Hardware (z.B., die Fähigkeit Ihres Servers, Anfragen zu bearbeiten und zu bedienen) zu reduzieren, und es kann helfen, verschiedene andere mögliche negative Effekte zu reduzieren, die mit unerwünschtem Verkehr verbunden sind. Es gibt jedoch zwei wichtige Dinge, an die man sich erinnern muss, um diese Frage zu verstehen.

Erstens ist CIDRAM ein PHP-Paket, und arbeitet daher auf dem Rechner, auf dem PHP installiert ist. Dies bedeutet, dass CIDRAM eine Anfrage nur sehen und blockieren kann, nachdem der Server sie bereits erhalten hat. Zweitens sollte eine effektive DDoS-Mitigation Anfragen filtern, bevor sie den vom DDoS-Angriff betroffenen Server erreichen. Im Idealfall sollten DDoS-Angriffe durch Lösungen erkannt und gemildert werden, die in der Lage sind, den mit Angriffen verbundenen Datenverkehr zu löschen oder umzuleiten, bevor er den Zielserver überhaupt erreicht.

Dies kann mit dedizierten Vor-Ort-Hardware-Lösungen, und/oder Cloud-basierte Lösungen wie dedizierte DDoS-Mitigationsdienste, routing des DNS einer Domain über DDoS-resistente Netzwerke, Cloud-basierte Filterung, oder eine Kombination davon implementiert werden. Auf jeden Fall ist dieses Thema ein wenig zu komplex, um es mit nur ein oder zwei Paragraphen gründlich zu erklären, also würde ich empfehlen, weitere Nachforschungen anzustellen, wenn dies ein Thema ist, dem Sie nachgehen wollen. Wenn die wahre Natur von DDoS-Angriffen richtig verstanden wird, wird diese Antwort mehr Sinn machen.

#### <a name="CHANGE_COMPONENT_SORT_ORDER"></a>Wenn ich Module oder Signaturdateien über die Update-Seite aktiviere oder deaktiviere, sortiert sie diese alphanumerisch in der Konfiguration. Kann ich die Art der Sortierung ändern?

Ja. Wenn Sie einige Dateien zwingen müssen, in einer bestimmten Reihenfolge ausgeführt zu werden, Sie können einige beliebige Daten vor ihren Namen in der Konfigurationsdirektive in der sie aufgeführt sind hinzufügen, durch einen Doppelpunkt getrennt. Wenn die Updates-Seite anschließend die Dateien erneut sortiert, diese zusätzlichen Daten wirken sich auf die Sortierreihenfolge aus und führen dazu, dass sie in der von Ihnen gewünschten Reihenfolge ausgeführt werden, ohne sie umbenennen zu müssen.

z.B., angenommen dass eine Konfigurationsdirektive mit den folgenden Dateien ist aufgeführt:

`file1.php,file2.php,file3.php,file4.php,file5.php`

Wenn Sie `file3.php` zuerst ausführen möchten, Sie könnten etwas wie `aaa:` vor dem Namen der Datei hinzufügen:

`file1.php,file2.php,aaa:file3.php,file4.php,file5.php`

Wenn dann eine neue Datei `file6.php` aktiviert wird, wenn die Updates-Seite sie alle wieder sortiert, sollte es so enden:

`aaa:file3.php,file1.php,file2.php,file4.php,file5.php,file6.php`

Gleiche Situation, wenn eine Datei deaktiviert ist. Umgekehrt, wenn Sie möchten, dass die Datei zuletzt ausgeführt wird, Sie könnten etwas wie `zzz:` vor dem Namen der Datei hinzufügen. In jedem Fall müssen Sie die betreffende Datei nicht umbenennen.

---


### 11. <a name="SECTION11"></a>RECHTSINFORMATION

#### 11.0 ABSCHNITT VORWORT

Dieser Abschnitt der Dokumentation beschreibt mögliche rechtliche Überlegungen zur Verwendung und Implementierung des Pakets, und um einige grundlegende verwandte Informationen zur Verfügung zu stellen. Dies kann für einige Benutzer wichtig sein, um sicherzustellen, dass die gesetzlichen Anforderungen in den Ländern eingehalten werden, in denen sie tätig sind, und einige Benutzer müssen möglicherweise ihre Website-Richtlinien in Übereinstimmung mit diesen Informationen anpassen.

Zuallererst, bitte beachten Sie, dass ich (der Autor des Pakets) weder Rechtsanwalt noch qualifizierter Jurist bin. Daher bin ich rechtlich nicht zur Rechtsberatung qualifiziert. Auch, in einigen Fällen können die genauen rechtlichen Anforderungen zwischen verschiedenen Ländern und Rechtsordnungen variieren, und diese unterschiedlichen rechtlichen Anforderungen können sich manchmal widersprechen (wie zum Beispiel, in Ländern, die [Privatsphäre](https://de.wikipedia.org/wiki/Privatsph%C3%A4re) und das [Recht auf Vergessenwerden bevorzugen](https://de.wikipedia.org/wiki/Recht_auf_Vergessenwerden), gegenüber Ländern, die eine erweiterte [Vorratsdatenspeicherung](https://de.wikipedia.org/wiki/Vorratsdatenspeicherung) bevorzugen). Berücksichtigen Sie auch, dass der Zugriff auf das Paket nicht auf bestimmte Länder oder Gerichtsbarkeiten beschränkt ist und daher die Paket-Nutzerbasis wahrscheinlich geografisch-vielfältig ist. Nach diesen Punkten kann ich nicht sagen, was es heißt, in allen Belangen für alle Nutzer "rechtskonform" zu sein. Jedoch, ich hoffe, dass die hier enthaltenen Informationen Ihnen helfen, selbst zu einer Entscheidung zu kommen, was Sie tun müssen, um im Kontext des Pakets rechtskonform zu bleiben. Wenn Sie Zweifel oder Bedenken hinsichtlich der hierin enthaltenen Informationen haben, oder wenn Sie aus rechtlicher Sicht zusätzliche Hilfe und Rat benötigen, würde ich Ihnen empfehlen, einen qualifizierten Rechtsberater zu konsultieren.

#### 11.1 HAFTUNG UND VERANTWORTUNG

Wie bereits in der Paketlizenz angegeben, wird das Paket ohne jegliche Gewährleistung bereitgestellt. Dies beinhaltet (aber ist nicht beschränkt auf) den gesamten Umfang der Haftung. Das Paket wird Ihnen zu Ihrer Bequemlichkeit zur Verfügung gestellt, in der Hoffnung, dass es nützlich sein wird, und dass es Ihnen einen Vorteil bringen wird. Sie das Paket verwenden oder implementieren, ist jedoch Ihre eigene Entscheidung. Sie sind nicht gezwungen, das Paket zu verwenden oder zu implementieren, aber wenn Sie dies tun, sind Sie für diese Entscheidung verantwortlich. Weder ich noch andere Mitwirkende des Pakets sind rechtlich verantwortlich für die Folgen der Entscheidungen, die Sie treffen, unabhängig davon, ob sie direkt, indirekt, implizit oder anderweitig sind.

#### 11.2 DRITTE

Abhängig von seiner genauen Konfiguration und Implementierung kann das Paket in einigen Fällen mit Dritten kommunizieren und Informationen teilen. Diese Informationen können in einigen Kontexten von einigen Gerichtsbarkeiten als "[personenbezogene Daten](https://de.wikipedia.org/wiki/Personenbezogene_Daten)" (oder "PII") definiert werden.

Wie diese Informationen von diesen Dritten verwendet werden können, unterliegt den verschiedenen Richtlinien, die von diesen Dritten festgelegt wurden, und liegt außerhalb des Anwendungsbereichs dieser Dokumentation. In allen diesen Fällen jedoch kann der Austausch von Informationen mit diesen Dritten deaktiviert werden. In allen diesen Fällen liegt es in Ihrer Verantwortung, wenn Sie sich dafür entscheiden, Ihre Bedenken hinsichtlich der Vertraulichkeit, Sicherheit, und Verwendung von personenbezogenen Daten durch diese Dritten zu untersuchen. Wenn irgendwelche Zweifel bestehen oder wenn Sie mit dem Verhalten dieser Dritten in Bezug auf PII nicht zufrieden sind, ist es möglicherweise am besten, den gesamten Informationsaustausch mit diesen Dritten zu deaktivieren.

Aus Gründen der Transparenz wird im Folgenden beschrieben, welche Art von Informationen, und mit wem, geteilt werden.

##### 11.2.0 HOSTNAME-SUCHE

Wenn Sie Funktionen oder Module verwenden, die mit Hostnamen arbeiten (z.B., "Schlechte Hosts Blocker-Modul", "tor project exit nodes block module", oder "Suchmaschinen-Verifizierung", u.s.w.), CIDRAM muss in der Lage sein, den Hostnamen eingehender Anfragen irgendwie zu erhalten. Typischerweise dazu wird der Hostname der IP-Adresse eingehender Anforderungen von einem DNS-Server angefordert, oder die Informationen über die Funktionalität angefordert, die vom System bereitgestellt werden, auf dem CIDRAM installiert ist (dies wird typischerweise als "Hostname-Suche" bezeichnet). Die standardmäßig definierten DNS-Server gehören zu [Google DNS](https://dns.google.com/) (dies kann jedoch einfach über die Konfiguration geändert werden). Die genauen Dienste, mit denen kommuniziert wird, sind konfigurierbar und hängen von der Konfiguration des Pakets ab. Wenn Sie Funktionen verwenden, die von dem System bereitgestellt werden, auf dem CIDRAM installiert ist, müssen Sie sich an Ihren Systemadministrator wenden, um zu ermitteln, welche Routen Hostnamen-Lookups verwenden. Hostnamen-Suchen können in CIDRAM verhindert werden, indem die betroffenen Module vermieden oder die Paketkonfiguration entsprechend Ihren Anforderungen geändert wird.

*Relevante Konfigurationsdirektiven:*
- `general` -> `default_dns`
- `general` -> `search_engine_verification`
- `general` -> `social_media_verification`
- `general` -> `force_hostname_lookup`
- `general` -> `allow_gethostbyaddr_lookup`

##### 11.2.1 WEBFONTS

Einige benutzerdefinierte Themen sowie die Standard-UI (oder Benutzerschnittstelle) für das CIDRAM-Front-End und die Seite "Zugriff verweigert" können Webfonts aus ästhetischen Gründen verwenden. Webfonts sind standardmäßig deaktiviert. Wenn sie jedoch aktiviert sind, erfolgt eine direkte Kommunikation zwischen dem Browser des Benutzers und dem Dienst, der die Webfonts hostet. Dies kann möglicherweise die Übermittlung von Informationen wie die IP-Adresse des Benutzers, den Benutzeragenten, das Betriebssystem und andere Details zur Anfrage verfügbar. Die meisten dieser Webfonts werden vom [Google Fonts](https://fonts.google.com/)-Service gehostet.

*Relevante Konfigurationsdirektiven:*
- `general` -> `disable_webfonts`

##### 11.2.2 VERIFIZIERUNG VON SUCHMASCHINEN UND SOCIAL MEDIA

Wenn die Verifizierung von Suchmaschinen aktiviert ist, versucht CIDRAM "Forward-DNS-Lookups" durchzuführen, um zu überprüfen, ob Anfragen, die behaupten, von Suchmaschinen stammen, authentisch sind. Gleichfalls, wenn die Verifizierung von Social Media aktiviert ist, CIDRAM macht das gleiche für scheinbare Social-Media-Anfragen. Um dies zu tun, es verwendet den [Google DNS](https://dns.google.com/)-Dienst, um IP-Adressen aus den Hostnamen dieser eingehenden Anfragen aufzulösen (in diesem Prozess werden die Hostnamen dieser eingehenden Anfragen für den Dienst freigegeben).

*Relevante Konfigurationsdirektiven:*
- `general` -> `search_engine_verification`
- `general` -> `social_media_verification`

##### 11.2.3 GOOGLE reCAPTCHA

CIDRAM unterstützt [Google reCAPTCHA](https://www.google.com/recaptcha/) als Option, die Bereitstellung einer Möglichkeit für Benutzer, die Seite "Zugriff verweigert" zu umgehen, indem sie eine reCAPTCHA-Instanz abschließen (weitere Informationen zu dieser Funktion finden Sie weiter oben in der Dokumentation, vor allem im Konfigurationsabschnitt). Google reCAPTCHA benötigt API-Schlüssel, um ordnungsgemäß funktionieren zu können, und ist daher standardmäßig deaktiviert. Es kann aktiviert werden, indem die erforderlichen API-Schlüssel in der Paketkonfiguration definiert werden. Wenn diese Option aktiviert ist, erfolgt eine direkte Kommunikation zwischen dem Browser des Benutzers und dem Dienst reCAPTCHA. Dies kann möglicherweise die Übermittlung von Informationen wie die IP-Adresse des Benutzers, den Benutzeragenten, das Betriebssystem und andere Details zur Anfrage verfügbar. Die IP-Adresse des Benutzers kann auch für die Kommunikation zwischen CIDRAM und dem Dienst reCAPTCHA freigegeben werden, wenn die Gültigkeit einer reCAPTCHA-Instanz überprüft und überprüft wird, ob sie erfolgreich abgeschlossen wurde.

*Relevante Konfigurationsdirektiven: Alles, was unter der Konfigurationskategorie "recaptcha" aufgeführt ist.*

##### 11.2.4 STOP FORUM SPAM

[Stop Forum Spam](https://www.stopforumspam.com/) ist ein fantastischer, frei verfügbarer Dienst, mit dem Sie Foren, Blogs und Websites vor Spammern schützen können. Dies geschieht durch Bereitstellung einer Datenbank bekannter Spammer und einer API, mit der überprüft werden kann, ob eine IP-Adresse, ein Benutzername oder eine E-Mail-Adresse in der Datenbank aufgeführt ist.

CIDRAM bietet ein optionales Modul, das diese API nutzt, um zu prüfen, ob die IP-Adresse eingehender Anfragen zu einem mutmaßlichen Spammer gehört. Das Modul wird nicht standardmäßig installiert, aber wenn Sie es installieren, können Benutzer-IP-Adressen mit der Stop Forum Spam API in Übereinstimmung mit dem beabsichtigten Zweck des Moduls geteilt werden. Wenn das Modul installiert wird, kommuniziert CIDRAM mit dieser API immer dann, wenn eine eingehende Anfrage eine Ressource anfordert, die von CIDRAM als eine Art von Ressource erkannt wird, die häufig von Spammern angegriffen wird (wie Einloggen-Seiten, Registrierungsseiten, E-Mail-Verifizierungsseiten, Kommentarformulare, u.s.w.).

#### 11.3 PROTOKOLLIERUNG

Protokollierung ist aus verschiedenen Gründen ein wichtiger Teil von CIDRAM. Es kann schwierig sein, falsche Positive zu diagnostizieren und zu beheben, wenn die Blockereignisse, die sie verursachen, nicht protokolliert werden. Ohne Blockereignisse zu protokollieren, kann es schwierig sein, exakt festzustellen, wie gut CIDRAM in einem bestimmten Kontext funktioniert, und es kann schwierig sein zu bestimmen, wo seine Defizite liegen und welche Änderungen möglicherweise an seiner Konfiguration oder den Signatures vorgenommen werden müssen, damit es weiterhin wie beabsichtigt funktioniert. Ungeachtet, die Protokollierung ist möglicherweise nicht für alle Benutzer wünschenswert und bleibt vollständig optional. In CIDRAM ist die Protokollierung standardmäßig deaktiviert. Um es zu aktivieren, muss CIDRAM entsprechend konfiguriert werden.

Zusätzlich, ob Protokollierung rechtlich zulässig ist, und in welchem Umfang es rechtlich zulässig ist (z.B., die Arten von Informationen, die protokolliert werden können, für wie lange und unter welchen Umständen), kann je nach Rechtssprechung und Kontext (z.B., ob Sie als Einzelperson, als juristische Person tätig sind, und ob auf kommerzieller oder nichtkommerzieller Basis), in dem CIDRAM implementiert wird, variieren. Es kann daher sinnvoll sein, diesen Abschnitt sorgfältig durchzulesen.

Es gibt mehrere Arten der Protokollierung, die CIDRAM ausführen kann. Verschiedene Arten der Protokollierung beinhalten verschiedene Arten von Informationen, aus verschiedenen Gründen.

##### 11.3.0 BLOCKEREIGNISSE

Der primäre Art der Protokollierung, den CIDRAM ausführen kann, bezieht sich auf "Blockereignisse". Diese Art der Protokollierung bezieht sich auf das Blockieren einer Anforderung durch CIDRAM und kann in drei verschiedenen Formaten bereitgestellt werden:
- Menschen lesbar oder benutzerfreundliche Protokolldateien.
- Apache-Stil Protokolldateien.
- Serialisierte Protokolldateien.

Ein Blockereignis, das in einer für Menschen lesbaren Protokolldatei protokolliert wird, sieht in etwa wie folgt aus (als Beispiel):

```
ID: 1234
Script-Version: CIDRAM v1.6.0
Datum/Uhrzeit: Day, dd Mon 20xx hh:ii:ss +0000
IP-Adresse: x.x.x.x
Hostname: dns.hostname.tld
Zählung den Signaturen: 1
Referenz für den Signaturen: x.x.x.x/xx
Warum blockierte: Cloud-Service ("Netzwerkname", Lxx:Fx, [XX])!
Benutzeragent: Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.181 Safari/537.36
Rekonstruierte URI: http://your-site.tld/index.php
Status der reCAPTCHA: Aktiviert.
```

Das gleiche Blockereignis, das in einer Protokolldatei im Apache-Stil protokolliert wird, würde ungefähr so aussehen:

```
x.x.x.x - - [Day, dd Mon 20xx hh:ii:ss +0000] "GET /index.php HTTP/1.1" 200 xxxx "-" "Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.181 Safari/537.36"
```

Ein protokolliertes Blockereignis enthält normalerweise die folgenden Informationen:
- Eine ID-Nummer, die auf das Blockereignis verweist.
- Die derzeit verwendete Version von CIDRAM.
- Datum und Uhrzeit des Auftretens des Blockereignisses.
- Die IP-Adresse der gesperrten Anfrage.
- Der Hostname der IP-Adresse der gesperrten Anfrage (wenn verfügbar).
- Die Anzahl der Signaturen, die von der Anfrage ausgelöst wurden.
- Verweise auf die ausgelösten Signaturen.
- Verweise auf die Gründe für das Blockereignis und einige grundlegende Debuginformationen.
- Der Benutzeragent der gesperrten Anfrage (d.h., wie sich die anfragende Einheit gegenüber der Anfrage identifiziert hat).
- Eine Rekonstruktion der Identifizierung für die ursprünglich angeforderte Ressource.
- Der reCAPTCHA-Status für die aktuelle Anfrage (falls relevant).

*Die Konfigurationsdirektiven, die für diese Art der Protokollierung und für jedes der drei verfügbaren Formate verantwortlich sind:*
- `general` -> `logfile`
- `general` -> `logfileApache`
- `general` -> `logfileSerialized`

Wenn diese Direktiven leer bleiben, bleibt diese Art der Protokollierung deaktiviert.

##### 11.3.1 reCAPTCHA PROTOKOLLIERUNG

Diese Art der Protokollierung bezieht sich speziell auf reCAPTCHA-Instanzen und tritt nur auf, wenn ein Benutzer versucht, eine reCAPTCHA-Instanz abzuschließen.

Ein reCAPTCHA-Protokolleintrag enthält die IP-Adresse des Benutzers, der versucht, eine reCAPTCHA-Instanz abzuschließen, das Datum und die Uhrzeit des Versuchs, und der Status der reCAPTCHA. Ein reCAPTCHA-Protokolleintrag sieht in etwa wie folgt aus (als Beispiel):

```
IP-Adresse: x.x.x.x - Date/Time: Day, dd Mon 20xx hh:ii:ss +0000 - Status der reCAPTCHA: Gelungen!
```

*Die für die reCAPTCHA-Protokollierung verantwortliche Konfigurationsdirektiven lautet:*
- `recaptcha` -> `logfile`

##### 11.3.2 FRONT-END PROTOKOLLIERUNG

Diese Art der Protokollierung bezieht sich auf Front-End-Einloggen-Versuchen und tritt nur auf, wenn ein Benutzer versucht, sich am Front-End anzumelden (vorausgesetzt, der Front-End-Zugriff ist aktiviert).

Ein Front-End-Protokolleintrag enthält die IP-Adresse des Benutzers, der sich anzumelden versucht, das Datum und die Uhrzeit des Versuchs, und die Ergebnisse des Versuchs (erfolgreich eingeloggt oder könnte sich nicht einloggen). Ein Front-End-Protokolleintrag sieht in etwa wie folgt aus (als Beispiel):

```
x.x.x.x - Day, dd Mon 20xx hh:ii:ss +0000 - "admin" - Eingeloggt.
```

*Die für die Front-End-Protokollierung verantwortliche Konfigurationsdirektiven lautet:*
- `general` -> `FrontEndLog`

##### 11.3.3 PROTOKOLLROTATION

Möglicherweise möchten Sie Protokolle nach einer gewissen Zeit löschen, oder müssen dies gesetzlich tun (d.h., die Zeitspanne, die für die Aufbewahrung von Protokolldateien gesetzlich zulässig ist, kann gesetzlich beschränkt sein). Sie können dies erreichen, indem Sie Datums/Zeitmarkierungen in die Namen Ihrer Protokolldateien einfügen, die in Ihrer Paketkonfiguration festgelegt sind (z.B., `{yyyy}-{mm}-{dd}.log`), und dann Aktivieren der Protokollrotation (Protokollrotation ermöglicht es Ihnen, einige Aktionen in Protokolldateien durchzuführen, wenn bestimmte Limits überschritten werden).

Beispielsweise: Wenn ich gesetzlich dazu verpflichtet wäre, Protokolldateien nach 30 Tagen zu löschen, könnte ich `{dd}.log` in den Namen meiner Protokolldateien angeben (`{dd}` steht für Tage), setze den Wert von `log_rotation_limit` auf 30, und setze den Wert von `log_rotation_action` auf `Delete`.

Umgekehrt, wenn Sie Protokolldateien für einen längeren Zeitraum aufbewahren müssen, Sie könnten entweder überhaupt keine Protokollrotation verwenden, oder Sie könnten den Wert von `log_rotation_action` auf `Archive` setzen, um Protokolldateien zu komprimieren, wodurch der Gesamtbetrag des belegten Speicherplatzes reduziert wird.

*Relevante Konfigurationsdirektiven:*
- `general` -> `log_rotation_limit`
- `general` -> `log_rotation_action`

##### 11.3.4 PROTOKOLL-TRUNKIERUNG

Es ist auch möglich, um einzelne Protokolldateien zu trunkieren, wenn sie eine bestimmte Größe überschreiten, falls Sie dies benötigen oder tun möchten.

*Relevante Konfigurationsdirektiven:*
- `general` -> `truncate`

##### 11.3.5 IP-ADRESSE PSEUDONYMISIERUNG

Erstens, wenn Sie mit dem Begriff nicht vertraut sind, "Pseudonymisierung" bezieht sich auf die Verarbeitung personenbezogener Daten, so dass sie ohne zusätzliche Informationen nicht mehr für eine bestimmte Person identifiziert werden können, und vorausgesetzt, dass diese zusätzlichen Informationen getrennt aufbewahrt werden, und vorbehaltlich technischer und organisatorischer Maßnahmen, um sicherzustellen, dass personenbezogene Daten für keine natürliche Person identifiziert werden können.

Die folgenden Ressourcen können helfen, es genauer zu erklären:
- [[johner-institut.de] Anonymisierung und Pseudonymisierung](https://www.johner-institut.de/blog/gesundheitswesen/anonymisierung-und-pseudonymisierung/)
- [[datenschutzbeauftragter-info.de] Pseudonymisierung – was ist das eigentlich?](https://www.datenschutzbeauftragter-info.de/pseudonymisierung-was-ist-das-eigentlich/)
- [[Wikipedia] Anonymisierung und Pseudonymisierung](https://de.wikipedia.org/wiki/Anonymisierung_und_Pseudonymisierung)

Unter gewissen Umständen sind Sie gesetzlich dazu verpflichtet, alle PII, die gesammelt, verarbeitet oder gespeichert werden, zu anonymisieren oder zu pseudonymisieren. Obwohl dieses Konzept schon seit einiger Zeit besteht, erwähnt und ermutigt die GDPR/DSGVO ausdrücklich die "Pseudonymisierung" der PII.

CIDRAM ist in der Lage, IP-Adressen zu pseudonymisieren, wenn Sie sie protokollieren, wenn Sie dies benötigen oder tun möchten. Wenn CIDRAM IP-Adressen pseudonymisiert, wird das letzte Oktett von IPv4-Adressen und alles nach dem zweiten Teil von IPv6-Adressen durch ein "x" dargestellt (runden effektiv IPv4-Adressen in Subnetz 24 und IPv6-Adressen in Subnetz 32 ab).

*Hinweis: Der IP-Adress-Pseudonymisierungsprozess von CIDRAM hat keinen Einfluss auf die IP-Tracking-Funktion von CIDRAM. Wenn dies ein Problem für Sie darstellt, ist es möglicherweise am besten, das IP-Tracking vollständig zu deaktivieren. Dies kann durch Setzen von `track_mode` auf `false` und durch Vermeiden von Modulen erreicht werden.*

*Relevante Konfigurationsdirektiven:*
- `signatures` -> `track_mode`
- `legal` -> `pseudonymise_ip_addresses`

##### 11.3.6 WEGLASSUNG VON PROTOKOLLINFORMATIONEN

Wenn Sie einen Schritt weiter gehen wollen, indem Sie verhindern, dass bestimmte Arten von Informationen vollständig protokolliert werden, ist dies ebenfalls möglich. CIDRAM stellt Konfigurationsdirektiven bereit, um zu steuern, ob IP-Adressen, Hostnamen und Benutzeragenten in Protokolldateien enthalten sind. Standardmäßig sind alle drei Datenpunkte in den Protokollen enthalten, wenn sie verfügbar sind. Wenn Sie eine dieser Konfigurationsdirektiven auf `true` setzen, werden die entsprechenden Informationen aus den Protokollen weggelassen.

*Hinweis: Es gibt keinen Grund, um IP-Adressen zu pseudonymisieren, wenn sie vollständig aus den Protokollen entfernt werden.*

*Relevante Konfigurationsdirektiven:*
- `legal` -> `omit_ip`
- `legal` -> `omit_hostname`
- `legal` -> `omit_ua`

##### 11.3.7 STATISTIKEN

CIDRAM ist optional in der Lage, Statistiken wie die Gesamtzahl der seit einem bestimmten Zeitpunkt aufgetretenen Blockereignisse oder reCAPTCHA-Instanzen zu verfolgen. Diese Funktion ist standardmäßig deaktiviert, kann jedoch über die Paketkonfiguration aktiviert werden. Diese Funktion verfolgt nur die Gesamtzahl der aufgetretenen Ereignisse, und enthält keine Informationen zu bestimmten Ereignissen (und sollten daher nicht als PII betrachtet werden).

*Relevante Konfigurationsdirektiven:*
- `general` -> `statistics`

##### 11.3.8 KRYPTOGRAPHIE

CIDRAM verwendet keine [Kryptografie](https://de.wikipedia.org/wiki/Kryptographie) zum den Cache oder Protokollierung. Kryptographie für den Cache oder Protokollierung kann in Zukunft eingeführt werden, aber es gibt derzeit keine konkreten Pläne dafür. Wenn Sie befürchten, dass unbefugte Dritte Zugang zu Teilen von CIDRAM erhalten, die PII oder vertrauliche Informationen wie Cache oder Protokolle enthalten, würde ich empfehlen, CIDRAM nicht an einem öffentlich zugänglichen Ort zu installieren (z.B., installieren Sie CIDRAM außerhalb des Standard-Verzeichnisses `public_html` oder eines entsprechenden Verzeichnisses, das für die meisten Standard-Webserver verfügbar ist) und dass entsprechend restriktive Berechtigungen für das Verzeichnis erzwungen werden, in dem sie sich befinden (insbesondere für das vault verzeichnis). Wenn dies nicht ausreicht, um Ihre Bedenken auszuräumen, konfigurieren Sie CIDRAM so, dass die Arten von Informationen, die Ihre Bedenken verursachen, nicht erfasst oder protokolliert werden (z.B. durch Deaktivieren der Protokollierung).

#### 11.4 COOKIES

CIDRAM setzt [Cookies](https://de.wikipedia.org/wiki/HTTP-Cookie) an zwei Stellen in seiner Codebasis. Erstens, wenn ein Benutzer eine reCAPTCHA-Instanz erfolgreich abgeschlossen hat (und angenommen, dass `lockuser` auf `true` gesetzt ist), CIDRAM setzt einen Cookie, um sich bei nachfolgenden Anfragen daran erinnern zu können, dass der Benutzer bereits eine reCAPTCHA-Instanz abgeschlossen hat, damit muss der Benutzer nicht ständig aufgefordert werden, eine reCAPTCHA-Instanz bei nachfolgenden Anfragen zu vervollständigen. Zweitens, wenn sich ein Benutzer erfolgreich am Front-End eingeloggt, CIDRAM setzt eine einen Cookie, um sich den Benutzer für nachfolgende Anfragen merken zu können (d.h., Cookies dienen zur Authentifizierung des Benutzers bei einer Einloggen-Sitzung).

In beiden Fällen werden Cookie-Warnungen angezeigt (falls zutreffend), die den Benutzer warnen, dass Cookies gesetzt werden, wenn sie die relevanten Aktionen ausführt. An anderen Stellen in der Codebasis werden keine Cookies gesetzt.

*Hinweis: Die Art und Weise, wie CIDRAM die "invisible" reCAPTCHA-API implementiert, könnte in einigen Ländern mit den Cookie-Gesetzen nicht kompatibel sein, und sollte von Websites, die solchen Gesetzen unterliegen, vermieden werden. Es könnte besser sein, stattdessen die API "V2" zu verwenden, oder reCAPTCHA vollständig deaktivieren.*

*Relevante Konfigurationsdirektiven:*
- `general` -> `disable_frontend`
- `recaptcha` -> `lockuser`
- `recaptcha` -> `api`

#### 11.5 VERMARKTUNG UND WERBUNG

CIDRAM sammelt und verarbeitet keine Informationen für der Zweck des Vermarktung oder Werbung, und weder verkauft noch profitiert von gesammelten oder protokolliert Informationen. CIDRAM ist kein kommerzielles Unternehmen, noch bezieht es sich auf irgendwelche kommerziellen Interessen, daher macht es keinen Sinn, diese Dinge zu tun. Dies ist seit Beginn des Projekts der Fall und ist auch heute noch der Fall. Außerdem, diese Dinge wären kontraproduktiv für den Geist und den beabsichtigten Zweck des gesamten Projekts, und so lange ich das Projekt weiterführen, wird nie passieren.

#### 11.6 DATENSCHUTZERKLÄRUNG

Unter bestimmten Umständen können Sie gesetzlich dazu verpflichtet sein, auf allen Seiten und Abschnitten Ihrer Website einen Link zu Ihrer Datenschutzerklärung deutlich anzuzeigen. Dies kann wichtig sein, um sicherzustellen, dass die Benutzer genau über Ihre genauen Datenschutzpraktiken, die Arten von personenbezogenen Daten, die Sie sammeln, und über Ihre beabsichtigte Verwendung informiert sind. Um einen solchen Link auf der Seite "Zugriff verweigert" von CIDRAM einzubinden, wird eine Konfigurationsdirektive bereitgestellt, um die URL zu Ihrer Datenschutzerklärung anzugeben.

*Hinweis: Es wird dringend empfohlen, dass Ihre Datenschutzerklärung nicht hinter dem Schutz von CIDRAM steht. Wenn CIDRAM Ihre Datenschutzerklärung schützt und ein von CIDRAM blockierter Benutzer auf den Link zu Ihrer Datenschutzerklärung klickt, sie werden nur erneut blockiert, und können Ihre Datenschutzerklärung nicht sehen. Idealerweise sollten Sie eine Link zu einer statischen Kopie Ihrer Datenschutzerklärung erstellen, z.B. eine HTML-Seite oder eine Nur-Text-Datei, die nicht durch CIDRAM geschützt ist.*

*Relevante Konfigurationsdirektiven:*
- `legal` -> `privacy_policy`

#### 11.7 GDPR/DSGVO

Die Datenschutz-Grundverordnung (DSGVO) ist eine Verordnung der Europäischen Union, die am 25. Mai 2018 in Kraft tritt. Das Hauptziel der Verordnung besteht darin, den EU-Bürgern und EU-Anwohnern die Kontrolle über ihre eigenen personenbezogenen Daten zu ermöglichen und die Regulierung innerhalb der EU in Bezug auf Privatsphäre und personenbezogene Daten zu vereinheitlichen.

Die Verordnung enthält spezifische Bestimmungen für die Verarbeitung "[personenbezogenen Daten](https://de.wikipedia.org/wiki/Personenbezogene_Daten)" (PII) von "betroffenen Personen" (jede identifizierte oder identifizierbare natürliche Person) aus oder innerhalb der EU. Um der Verordnung zu entsprechen, müssen "Unternehmen" (gemäß der Definition in der Verordnung) und alle relevanten Systeme und Prozesse "[Datenschutz durch Design](https://digitalcourage.de/blog/2015/was-ist-privacy-design)" standardmäßig implementieren, müssen die höchstmögliche Privatsphäre Einstellungen verwenden, müssen die erforderlichen Sicherheitsmaßnahmen für gespeicherte oder verarbeitete Informationen implementieren (einschließlich, aber nicht beschränkt auf die Durchführung der Pseudonymisierung oder vollständigen Anonymisierung von Daten), müssen die Art der Daten, die sie sammeln, eindeutig und eindeutig angeben, aus welchen Gründen, für wie lange sie diese Daten speichern und ob sie diese Daten an Dritte weitergeben, die Arten von Daten, die mit Dritten geteilt werden, wie, warum, u.s.w.

Daten dürfen nicht verarbeitet werden, es sei denn, es gibt eine gesetzliche Grundlage dafür, wie in der Verordnung definiert. Im Allgemeinen bedeutet dies, dass die Verarbeitung der Daten eines Datensubjekts auf gesetzlicher Grundlage gemäß den gesetzlichen Verpflichtungen oder nur nach ausdrücklicher, gut informierter und eindeutiger Zustimmung der betroffenen Person erfolgen muss.

Da sich Aspekte der Verordnung mit der Zeit weiterentwickeln können, um die Verbreitung veralteter Informationen zu vermeiden, ist es möglicherweise besser, die Vorschrift von einer autoritativen Quelle zu erfahren, als einfach die relevanten Informationen hier in die Paketdokumentation einzubeziehen (was eventuell mit der Entwicklung der Verordnung veraltet).

[EUR-Lex](https://eur-lex.europa.eu/) (ein Teil der offiziellen Website der Europäischen Union, die Informationen über EU-Recht bietet) bietet umfangreiche Informationen zu GDPR/DSGVO, die zum Zeitpunkt der Erstellung in 24 Sprachen verfügbar sind, und im PDF-Format heruntergeladen werden können. Ich würde definitiv empfehlen, die Informationen zu lesen, die sie zur Verfügung stellen, um mehr über GDPR/DSGVO zu erfahren:
- [VERORDNUNG (EU) 2016/679 DES EUROPÄISCHEN PARLAMENTS UND DES RATES](https://eur-lex.europa.eu/legal-content/DE/TXT/?uri=celex:32016R0679)

[Intersoft Consulting](https://www.intersoft-consulting.de/) bietet auch umfassende Informationen über DSGVO, die in deutscher und englischer Sprache verfügbar sind und die nützlich sein könnten, um mehr über GDPR/DSGVO zu erfahren:
- [Datenschutz-Grundverordnung (EU-DSGVO) als übersichtliche Website](https://dsgvo-gesetz.de/)

Alternativ gibt es einen kurzen (nicht autoritativen) Überblick über die GDPR/DSGVO bei Wikipedia:
- [Datenschutz-Grundverordnung](https://de.wikipedia.org/wiki/Datenschutz-Grundverordnung)

---


Zuletzt aktualisiert: 10 August 2018 (2018.08.10).
