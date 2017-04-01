## Documentatie voor CIDRAM (Nederlandse).

### Inhoud
- 1. [PREAMBULE](#SECTION1)
- 2. [HOE TE INSTALLEREN](#SECTION2)
- 3. [HOE TE GEBRUIKEN](#SECTION3)
- 4. [FRONTEND MANAGEMENT](#SECTION4)
- 5. [BESTANDEN IN DIT PAKKET](#SECTION5)
- 6. [CONFIGURATIEOPTIES](#SECTION6)
- 7. [SIGNATURE FORMAAT](#SECTION7)
- 8. [VEELGESTELDE VRAGEN (FAQ)](#SECTION8)

*Opmerking over vertalingen: In geval van fouten (bv, verschillen tussen vertalingen, typefouten, ezv), de Engels versie van de README wordt beschouwd als het origineel en gezaghebbende versie. Als u vinden elke fouten, uw hulp bij het corrigeren van hen zou worden toegejuicht.*

---


### 1. <a name="SECTION1"></a>PREAMBULE

CIDRAM (Classless Inter-Domain Routing Access Manager) is een PHP-script ontworpen om websites te beschermen door het blokkeren van verzoeken afkomstig van IP-adressen beschouwd als bronnen van ongewenste verkeer, inclusief (maar niet gelimiteerd tot) het verkeer van niet-menselijke toegang eindpunten, cloud-diensten, spambots, schrapers/scrapers, ezv. Het doet dit door het berekenen van de mogelijke CIDRs van de IP-adressen geleverde van binnenkomende verzoeken en dan het vergelijken van deze mogelijke CIDRs tegen zijn signature bestanden (deze signature bestanden bevatten lijsten van CIDRs van IP-adressen beschouwd als bronnen van ongewenste verkeer); Als overeenkomsten worden gevonden, de verzoeken worden geblokkeerd.

CIDRAM COPYRIGHT 2016 en verder GNU/GPLv2 van Caleb M (Maikuolan).

Dit script is gratis software; u kunt, onder de voorwaarden van de GNU General Public License zoals gepubliceerd door de Free Software Foundation, herdistribueren en/of wijzigen dit; ofwel versie 2 van de Licentie, of (naar uw keuze) enige latere versie. Dit script wordt gedistribueerd in de hoop dat het nuttig zal zijn, maar ZONDER ENIGE GARANTIE; zonder zelfs de impliciete garantie van VERKOOPBAARHEID of GESCHIKTHEID VOOR EEN BEPAALD DOEL. Zie de GNU General Public License voor meer informatie, gelegen in het `LICENSE.txt` bestand en ook beschikbaar uit:
- <http://www.gnu.org/licenses/>.
- <http://opensource.org/licenses/>.

Dit document en de bijbehorende pakket kunt gedownload gratis zijn van [Github](https://github.com/Maikuolan/CIDRAM/).

---


### 2. <a name="SECTION2"></a>HOE TE INSTALLEREN

#### 2.0 HANDMATIG INSTALLEREN

1) Omdat u zijn lezen dit, ik ben ervan uit u al gedownload een gearchiveerde kopie van het script, uitgepakt zijn inhoud en heeft het ergens op uw lokale computer. Vanaf hier, u nodig te bepalen waar op uw host of CMS die inhoud te plaatsen. Een bestandsmap zoals `/public_html/cidram/` of soortgelijk (hoewel, het is niet belangrijk welke u kiest, zolang het is iets veilig en iets waar u blij mee bent) zal volstaan. *Voordat u het uploaden begint, lees verder..*

2) Hernoemen `config.ini.RenameMe` naar `config.ini` (gelegen binnen `vault`), en facultatief (sterk aanbevolen voor ervaren gebruikers, maar niet aan te raden voor beginners of voor de onervaren), open het (dit bestand bevat alle beschikbare CIDRAM configuratie opties; boven elke optie moet een korte opmerking te beschrijven wat het doet en wat het voor). Pas deze opties als het u past, volgens welke geschikt is voor uw configuratie. Sla het bestand, sluiten.

3) Upload de inhoud (CIDRAM en zijn bestanden) naar het bestandsmap die u zou op eerder besloten (u nodig niet de `*.txt`/`*.md` bestanden opgenomen, maar meestal, u moeten uploaden alles).

4) CHMOD het bestandsmap `vault` naar "755" (als er problemen, u kan proberen "777"; dit is minder veilig, hoewel). De belangrijkste bestandsmap opslaan van de inhoud (degene die u eerder koos), gewoonlijk, kunt worden genegeerd, maar CHMOD-status moet worden gecontroleerd als u machtigingen problemen heeft in het verleden met uw systeem (standaard, moet iets zijn als "755").

5) Volgende, u nodig om "haak" CIDRAM om uw systeem of CMS. Er zijn verschillende manieren waarop u kunt "haak" scripts zoals CIDRAM om uw systeem of CMS, maar het makkelijkste is om gewoon omvatten voor het script aan het begin van een kern bestand van uw systeem of CMS (een die het algemeen altijd zal worden geladen wanneer iemand heeft toegang tot een pagina in uw website) met behulp van een `require` of `include` opdracht. Meestal is dit wel iets worden opgeslagen in een bestandsmap zoals `/includes`, `/assets` of `/functions`, en zal vaak zijn vernoemd iets als `init.php`, `common_functions.php`, `functions.php` of soortgelijk. U nodig om te bepalen welk bestand dit is voor uw situatie; Als u problemen ondervindt bij het bepalen van dit voor uzelf, ga naar de CIDRAM kwesties pagina op Github voor assistentie. Om dit te doen [te gebruiken `require` of `include`], plaatst u de volgende regel code aan het begin op die kern bestand, vervangen van de string die binnen de aanhalingstekens met het exacte adres van het `loader.php` bestand (lokaal adres, niet het HTTP-adres; zal vergelijkbaar zijn met de eerder genoemde vault adres).

`<?php require '/user_name/public_html/cidram/loader.php'; ?>`

Opslaan bestand, sluiten, heruploaden.

-- OF ALTERNATIEF --

Als u gebruik een Apache webserver en als u heeft toegang om `php.ini`, u kunt gebruiken de `auto_prepend_file` richtlijn naar prepend CIDRAM wanneer een PHP verzoek wordt gemaakt. Zoiets als:

`auto_prepend_file = "/user_name/public_html/cidram/loader.php"`

Of dit in het `.htaccess` bestand:

`php_value auto_prepend_file "/user_name/public_html/cidram/loader.php"`

6) Dat is alles! :-)

#### 2.1 INSTALLEREN MET COMPOSER

[CIDRAM is geregistreerd bij Packagist](https://packagist.org/packages/maikuolan/cidram), en dus, als u bekend bent met Composer, kunt u Composer gebruiken om CIDRAM installeren (u zult nog steeds nodig om de configuratie en haken te bereiden niettemin; zie "handmatig installeren" stappen 2 en 5).

`composer require maikuolan/cidram`

#### 2.2 INSTALLEREN VOOR WORDPRESS

Als u wilt CIDRAM gebruiken met WordPress, u kunt alle bovenstaande instructies negeren. [CIDRAM is geregistreerd als een plugin met de WordPress plugins databank](https://WordPress.org/plugins/cidram/), en u kunt CIDRAM direct vanaf het plugin-dashboard installeren. U kunt het op dezelfde manier installeren als elke andere plugin, en geen toevoeging stappen nodig zijn. Net als bij de andere installatiemethoden, u kunt uw installatie aanpassen door het wijzigen van de inhoud van de `config.ini` bestand of door de frontend configuratie pagina. Als u de CIDRAM frontend activeren en bijwerken met behulp van de frontend updates pagina, dit zal automatisch synchroniseren met de plugin versie-informatie wordt weergegeven in het plugin-dashboard.

---


### 3. <a name="SECTION3"></a>HOE TE GEBRUIKEN

CIDRAM moet blokkeren ongewenste verzoeken naar uw website automatisch zonder enige handmatige hulp, afgezien van de eerste installatie.

Updaten gebeurt met de hand, en u kunt aanpassen uw configuratie en aanpassen de CIDRs dat zal worden geblokkeerd door het modificeren van het configuratiebestand en/of uw signature bestanden.

Als u tegenkomen een valse positieven, neem dan contact met mij op om me te laten weten.

---


### 4. <a name="SECTION4"></a>FRONTEND MANAGEMENT

#### 4.0 WAT IS DE FRONT-END.

De front-end biedt een gemakkelijke en eenvoudige manier te onderhouden, beheren en updaten van uw CIDRAM installatie. U kunt bekijken, delen en downloaden log bestanden via de pagina logs, u kunt de configuratie wijzigen via de configuratiepagina, u kunt installeren en verwijderen/desinstalleren van componenten via de pagina updates, en u kunt uploaden, downloaden en wijzigen bestanden in uw vault via de bestandsbeheer.

De front-end is standaard uitgeschakeld om ongeautoriseerde toegang te voorkomen (ongeautoriseerde toegang kan belangrijke gevolgen hebben voor uw website en de beveiliging hebben). Instructies voor het inschakelen van deze zijn hieronder deze paragraaf opgenomen.

#### 4.1 HOE DE FRONTEND TE INSCHAKELEN.

1) Vind de `disable_frontend` richtlijn in `config.ini`, en stel dat het true (deze is false door standaard).

2) Toegang tot `loader.php` vanuit uw browser (bijv., `http://localhost/cidram/loader.php`).

3) Inloggen u aan met de standaard gebruikersnaam en wachtwoord (admin/password).

Notitie: Nadat u hebt ingelogd voor de eerste keer, om ongeautoriseerde toegang tot de frontend te voorkomen, moet u onmiddellijk veranderen uw gebruikersnaam en wachtwoord! Dit is zeer belangrijk, want het is mogelijk om willekeurige PHP-code te uploaden naar uw website via de front-end.

#### 4.2 HOE DE FRONTEND GEBRUIKEN.

Instructies worden op elke pagina van de frontend, om uit te leggen hoe het te gebruiken en het beoogde doel. Als u meer uitleg of een speciale hulp nodig hebben, neem dan contact op met ondersteuning. Als alternatief, zijn er een aantal video's op YouTube die zouden kunnen helpen door middel van een demonstratie.


---


### 5. <a name="SECTION5"></a>BESTANDEN IN DIT PAKKET

Het volgende is een lijst van alle bestanden die moeten worden opgenomen in de gearchiveerde kopie van dit script als u gedownload het, alle bestanden die kunt mogelijk worden gemaakt als resultaat van uw gebruik van dit script, samen met een korte beschrijving van wat al deze bestanden zijn voor.

Bestand | Beschrijving
----|----
/_docs/ | Documentatie bestandsmap (bevat verschillende bestanden).
/_docs/readme.ar.md | Arabisch documentatie.
/_docs/readme.de.md | Duitse documentatie.
/_docs/readme.en.md | Engels documentatie.
/_docs/readme.es.md | Spaanse documentatie.
/_docs/readme.fr.md | Franse documentatie.
/_docs/readme.id.md | Indonesisch documentatie.
/_docs/readme.it.md | Italiaanse documentatie.
/_docs/readme.ja.md | Japanse documentatie.
/_docs/readme.ko.md | Koreaanse documentatie.
/_docs/readme.nl.md | Nederlandse documentatie.
/_docs/readme.pt.md | Portugees documentatie.
/_docs/readme.ru.md | Russische documentatie.
/_docs/readme.vi.md | Vietnamees documentatie.
/_docs/readme.zh-TW.md | Chinees (traditioneel) documentatie.
/_docs/readme.zh.md | Chinees (vereenvoudigd) documentatie.
/vault/ | Vault bestandsmap (bevat verschillende bestanden).
/vault/fe_assets/ | Frontend data/gegevens.
/vault/fe_assets/.htaccess | Een hypertext toegang bestand (in dit geval, om gevoelige bestanden die behoren tot het script te beschermen tegen toegang door niet-geautoriseerde bronnen).
/vault/fe_assets/_accounts.html | Een HTML sjabloon voor de frontend accounts pagina.
/vault/fe_assets/_accounts_row.html | Een HTML sjabloon voor de frontend accounts pagina.
/vault/fe_assets/_cidr_calc.html | Een HTML sjabloon voor de CIDR calculator.
/vault/fe_assets/_cidr_calc_row.html | Een HTML sjabloon voor de CIDR calculator.
/vault/fe_assets/_config.html | Een HTML sjabloon voor de frontend configuratie pagina.
/vault/fe_assets/_config_row.html | Een HTML sjabloon voor de frontend configuratie pagina.
/vault/fe_assets/_files.html | Een HTML sjabloon voor de bestandsbeheer.
/vault/fe_assets/_files_edit.html | Een HTML sjabloon voor de bestandsbeheer.
/vault/fe_assets/_files_rename.html | Een HTML sjabloon voor de bestandsbeheer.
/vault/fe_assets/_files_row.html | Een HTML sjabloon voor de bestandsbeheer.
/vault/fe_assets/_home.html | Een HTML sjabloon voor de frontend startpagina.
/vault/fe_assets/_ip_test.html | Een HTML sjabloon voor de IP test pagina.
/vault/fe_assets/_ip_test_row.html | Een HTML sjabloon voor de IP test pagina.
/vault/fe_assets/_ip_tracking.html | Een HTML sjabloon voor de IP-Tracking pagina.
/vault/fe_assets/_ip_tracking_row.html | Een HTML sjabloon voor de IP-Tracking pagina.
/vault/fe_assets/_login.html | Een HTML sjabloon voor de frontend inlogpagina.
/vault/fe_assets/_logs.html | Een HTML sjabloon voor de frontend logbestanden pagina.
/vault/fe_assets/_nav_complete_access.html | Een HTML sjabloon voor de frontend navigatie-links, voor degenen met volledige toegang.
/vault/fe_assets/_nav_logs_access_only.html | Een HTML sjabloon voor de frontend navigatie-links, voor degenen met logbestanden toegang alleen.
/vault/fe_assets/_updates.html | Een HTML sjabloon voor de frontend updates pagina.
/vault/fe_assets/_updates_row.html | Een HTML sjabloon voor de frontend updates pagina.
/vault/fe_assets/frontend.css | CSS-stijlblad voor de frontend.
/vault/fe_assets/frontend.dat | Database voor de frontend (bevat accounts informatie, sessies informatie, en de cache; alleen gegenereerd als de frontend geactiveerd en gebruikt).
/vault/fe_assets/frontend.html | De belangrijkste HTML-template-bestand voor de frontend.
/vault/lang/ | Bevat CIDRAM taaldata/taalgegevens.
/vault/lang/.htaccess | Een hypertext toegang bestand (in dit geval, om gevoelige bestanden die behoren tot het script te beschermen tegen toegang door niet-geautoriseerde bronnen).
/vault/lang/lang.ar.cli.php | Arabisch taaldata/taalgegevens voor CLI.
/vault/lang/lang.ar.fe.php | Arabisch taaldata/taalgegevens voor het frontend.
/vault/lang/lang.ar.php | Arabisch taaldata/taalgegevens.
/vault/lang/lang.de.cli.php | Duitse taaldata/taalgegevens voor CLI.
/vault/lang/lang.de.fe.php | Duitse taaldata/taalgegevens voor het frontend.
/vault/lang/lang.de.php | Duitse taaldata/taalgegevens.
/vault/lang/lang.en.cli.php | Engels taaldata/taalgegevens voor CLI.
/vault/lang/lang.en.fe.php | Engels taaldata/taalgegevens voor het frontend.
/vault/lang/lang.en.php | Engels taaldata/taalgegevens.
/vault/lang/lang.es.cli.php | Spaanse taaldata/taalgegevens voor CLI.
/vault/lang/lang.es.fe.php | Spaanse taaldata/taalgegevens voor het frontend.
/vault/lang/lang.es.php | Spaanse taaldata/taalgegevens.
/vault/lang/lang.fr.cli.php | Franse taaldata/taalgegevens voor CLI.
/vault/lang/lang.fr.fe.php | Franse taaldata/taalgegevens voor het frontend.
/vault/lang/lang.fr.php | Franse taaldata/taalgegevens.
/vault/lang/lang.id.cli.php | Indonesisch taaldata/taalgegevens voor CLI.
/vault/lang/lang.id.fe.php | Indonesisch taaldata/taalgegevens voor het frontend.
/vault/lang/lang.id.php | Indonesisch taaldata/taalgegevens.
/vault/lang/lang.it.cli.php | Italiaanse taaldata/taalgegevens voor CLI.
/vault/lang/lang.it.fe.php | Italiaanse taaldata/taalgegevens voor het frontend.
/vault/lang/lang.it.php | Italiaanse taaldata/taalgegevens.
/vault/lang/lang.ja.cli.php | Japanse taaldata/taalgegevens voor CLI.
/vault/lang/lang.ja.fe.php | Japanse taaldata/taalgegevens voor het frontend.
/vault/lang/lang.ja.php | Japanse taaldata/taalgegevens.
/vault/lang/lang.ko.cli.php | Koreaanse taaldata/taalgegevens voor CLI.
/vault/lang/lang.ko.fe.php | Koreaanse taaldata/taalgegevens voor het frontend.
/vault/lang/lang.ko.php | Koreaanse taaldata/taalgegevens.
/vault/lang/lang.nl.cli.php | Nederlandse taaldata/taalgegevens voor CLI.
/vault/lang/lang.nl.fe.php | Nederlandse taaldata/taalgegevens voor het frontend.
/vault/lang/lang.nl.php | Nederlandse taaldata/taalgegevens.
/vault/lang/lang.pt.cli.php | Portugees taaldata/taalgegevens voor CLI.
/vault/lang/lang.pt.fe.php | Portugees taaldata/taalgegevens voor het frontend.
/vault/lang/lang.pt.php | Portugees taaldata/taalgegevens.
/vault/lang/lang.ru.cli.php | Russische taaldata/taalgegevens voor CLI.
/vault/lang/lang.ru.fe.php | Russische taaldata/taalgegevens voor het frontend.
/vault/lang/lang.ru.php | Russische taaldata/taalgegevens.
/vault/lang/lang.vi.cli.php | Vietnamees taaldata/taalgegevens voor CLI.
/vault/lang/lang.vi.fe.php | Vietnamees taaldata/taalgegevens voor het frontend.
/vault/lang/lang.vi.php | Vietnamees taaldata/taalgegevens.
/vault/lang/lang.zh-tw.cli.php | Chinees (traditioneel) taaldata/taalgegevens voor CLI.
/vault/lang/lang.zh-tw.fe.php | Chinees (traditioneel) taaldata/taalgegevens voor het frontend.
/vault/lang/lang.zh-tw.php | Chinees (traditioneel) taaldata/taalgegevens.
/vault/lang/lang.zh.cli.php | Chinees (vereenvoudigd) taaldata/taalgegevens voor CLI.
/vault/lang/lang.zh.fe.php | Chinees (vereenvoudigd) taaldata/taalgegevens voor het frontend.
/vault/lang/lang.zh.php | Chinees (vereenvoudigd) taaldata/taalgegevens.
/vault/.htaccess | Een hypertext toegang bestand (in dit geval, om gevoelige bestanden die behoren tot het script te beschermen tegen toegang door niet-geautoriseerde bronnen).
/vault/cache.dat | Cache data/gegevens.
/vault/cidramblocklists.dat | Bevat informatie met betrekking tot de optionele land blocklists door Macmathan; Gebruikt door de updates functie verzorgd door de frontend.
/vault/cli.php | CLI-handler.
/vault/components.dat | Bevat informatie met betrekking tot de verschillende CIDRAM componenten; Gebruikt door de updates functie verzorgd door de frontend.
/vault/config.ini.RenameMe | Configuratiebestand; Bevat alle configuratieopties van CIDRAM, het vertellen wat te doen en hoe om te werken correct (hernoemen om te activeren).
/vault/config.php | Configuratie-handler.
/vault/config.yaml | Configuratie standaardwaarden bestand; Bevat standaardwaarden voor de CIDRAM configuratie.
/vault/frontend.php | Frontend-handler.
/vault/functions.php | Functies bestand (essentieel).
/vault/hashes.dat | Bevat een lijst met geaccepteerde hashes (relevant zijn voor de reCAPTCHA functie; alleen gegenereerd als de reCAPTCHA functie is ingeschakeld).
/vault/icons.php | Icons-handler (door de frontend bestandsbeheer gebruikt).
/vault/ignore.dat | Genegeerd file (gebruikt om aan te geven welke signature secties CIDRAM moeten negeren).
/vault/ipbypass.dat | Bevat een lijst met IP rondwegen (relevant zijn voor de reCAPTCHA functie; alleen gegenereerd als de reCAPTCHA functie is ingeschakeld).
/vault/ipv4.dat | IPv4 signatures bestand (ongewenste cloud-diensten en niet-menselijke eindpunten).
/vault/ipv4_bogons.dat | IPv4 signatures bestand (bogon/martian CIDRs).
/vault/ipv4_custom.dat.RenameMe | IPv4 aangepaste signatures bestand (hernoemen om te activeren).
/vault/ipv4_isps.dat | IPv4 signatures bestand (gevaarlijk en spammy ISPs).
/vault/ipv4_other.dat | IPv4 signatures bestand (CIDRs voor proxies, VPN's, en diverse andere ongewenste diensten).
/vault/ipv6.dat | IPv6 signatures bestand (ongewenste cloud-diensten en niet-menselijke eindpunten).
/vault/ipv6_bogons.dat | IPv6 signatures bestand (bogon/martian CIDRs).
/vault/ipv6_custom.dat.RenameMe | IPv6 aangepaste signatures bestand (hernoemen om te activeren).
/vault/ipv6_isps.dat | IPv6 signatures bestand (gevaarlijk en spammy ISPs).
/vault/ipv6_other.dat | IPv6 signatures bestand (CIDRs voor proxies, VPN's, en diverse andere ongewenste diensten).
/vault/lang.php | Taal-handler.
/vault/modules.dat | Bevat informatie met betrekking tot de CIDRAM modules; Gebruikt door de updates functie verzorgd door de frontend.
/vault/outgen.php | Uitvoer generator.
/vault/php5.4.x.php | Polyfills voor PHP 5.4.X (nodig voor PHP 5.4.X achterwaartse compatibiliteit; veilig te verwijderen voor nieuwere PHP-versies).
/vault/recaptcha.php | reCAPTCHA module.
/vault/rules_as6939.php | Aangepaste regels bestand voor AS6939.
/vault/rules_softlayer.php | Aangepaste regels bestand voor Soft Layer.
/vault/rules_specific.php | Aangepaste regels bestand voor sommige specifiek CIDRs.
/vault/salt.dat | Zout bestand (gebruikt door sommige perifere functionaliteit; alleen gegenereerd indien nodig).
/vault/template.html | Sjabloonbestand; Sjabloon voor HTML-uitvoer geproduceerd door de CIDRAM uitvoer generator.
/vault/template_custom.html | Sjabloonbestand; Sjabloon voor HTML-uitvoer geproduceerd door de CIDRAM uitvoer generator.
/.gitattributes | Een Github project bestand (niet vereist voor een goede werking van het script).
/Changelog.txt | Een overzicht van wijzigingen in het script tussen verschillende versies (niet vereist voor een goede werking van het script).
/composer.json | Composer/Packagist informatie (niet vereist voor een goede werking van het script).
/CONTRIBUTING.md | Informatie over hoe bij te dragen aan het project.
/LICENSE.txt | Een kopie van de GNU/GPLv2 licentie (niet vereist voor een goede werking van het script).
/loader.php | Lader. Dit is wat u zou moeten worden inhaken in (essentieel)!
/README.md | Project beknopte informatie.
/web.config | Een ASP.NET-configuratiebestand (in dit geval, naar het bestandsmap "vault" te beschermen tegen toegang door niet-geautoriseerde bronnen indien het script is geïnstalleerd op een server op basis van ASP.NET technologieën).

---


### 6. <a name="SECTION6"></a>CONFIGURATIEOPTIES
Het volgende is een lijst van variabelen die in de `config.ini` configuratiebestand van CIDRAM, samen met een beschrijving van hun doel en functie.

#### "general" (Categorie)
Algemene configuratie voor CIDRAM.

"logfile"
- Mensen leesbare bestand om alle geblokkeerde toegang pogingen te loggen. Geef een bestandsnaam, of laat leeg om uit te schakelen.

"logfileApache"
- Apache-stijl bestand om alle geblokkeerde toegang pogingen te loggen. Geef een bestandsnaam, of laat leeg om uit te schakelen.

"logfileSerialized"
- Geserialiseerd bestand om alle geblokkeerde toegang pogingen te loggen. Geef een bestandsnaam, of laat leeg om uit te schakelen.

*Handige tip: Als u wil, u kunt datum/tijd informatie toevoegen om de namen van uw logbestanden door deze op in naam inclusief: `{yyyy}` voor volledige jaar, `{yy}` voor verkorte jaar, `{mm}` voor maand, `{dd}` voor dag, `{hh}` voor het uur.*

*Voorbeelden:*
- *`logfile='logfile.{yyyy}-{mm}-{dd}-{hh}.txt'`*
- *`logfileApache='access.{yyyy}-{mm}-{dd}-{hh}.txt'`*
- *`logfileSerialized='serial.{yyyy}-{mm}-{dd}-{hh}.txt'`*

"timeOffset"
- Als uw server tijd niet overeenkomt met uw lokale tijd, u kunt opgeven hier een offset om de datum/tijd informatie gegenereerd door CIDRAM aan te passen volgens uw behoeften. Het is in het algemeen in plaats aanbevolen de tijdzone richtlijn in uw bestand `php.ini` aan te passen, maar somtijds (zoals bij het werken met beperkte shared hosting providers) dit is niet altijd mogelijk om te voldoen, en dus, Dit optie is hier voorzien. Offset is in een minuten.
- Voorbeeld (een uur toe te voegen): `timeOffset=60`

"ipaddr"
- Waar het IP-adres van het aansluiten verzoek te vinden? (Handig voor diensten zoals Cloudflare en dergelijke). Standaard = REMOTE_ADDR. WAARSCHUWING: Verander dit niet tenzij u weet wat u doet!

"forbid_on_block"
- Welke headers moet CIDRAM reageren met bij het blokkeren van verzoeken? False/200 = 200 OK [Standaard]; True/403 = 403 Forbidden (Verboden); 503 = 503 Service unavailable (Service onbeschikbaar).

"silent_mode"
- Moet CIDRAM stilletjes redirect geblokkeerd toegang pogingen in plaats van het weergeven van de "Toegang Geweigerd" pagina? Als ja, geef de locatie te redirect geblokkeerd toegang pogingen. Als nee, verlaat deze variabele leeg.

"lang"
- Geef de standaardtaal voor CIDRAM.

"emailaddr"
- Indien u wenst, u kunt een e-mailadres op hier te geven te geven aan de gebruikers als ze geblokkeerd, voor hen te gebruiken als aanspreekpunt voor steun en/of assistentie in het geval dat ze worden onrechte geblokkeerd. WAARSCHUWING: Elke e-mailadres u leveren hier zal zeker worden overgenomen met spambots en schrapers in de loop van zijn wezen die hier gebruikt, en dus, het wordt ten zeerste aanbevolen als u ervoor kiest om een e-mailadres hier te leveren, dat u ervoor zorgen dat het e-mailadres dat u hier leveren is een wegwerp-adres en/of een adres dat u niet de zorg over wordt gespamd (met andere woorden, u waarschijnlijk niet wilt om uw primaire persoonlijk of primaire zakelijke e-mailadressen te gebruik).

"disable_cli"
- Uitschakelen CLI-modus? CLI-modus is standaard ingeschakeld, maar kunt somtijds interfereren met bepaalde testtools (zoals PHPUnit bijvoorbeeld) en andere CLI-gebaseerde applicaties. Als u niet hoeft te uitschakelen CLI-modus, u moeten om dit richtlijn te negeren. False = Inschakelen CLI-modus [Standaard]; True = Uitschakelen CLI-modus.

"disable_frontend"
- Uitschakelen frontend toegang? frontend toegang kan CIDRAM beter beheersbaar te maken, maar kan ook een potentieel gevaar voor de veiligheid zijn. Het is aan te raden om CIDRAM te beheren via het backend wanneer mogelijk, maar frontend toegang is hier voorzien voor wanneer het niet mogelijk is. Hebben het uitgeschakeld tenzij u het nodig hebt. False = Inschakelen frontend toegang; True = Uitschakelen frontend toegang [Standaard].

"max_login_attempts"
- Maximum aantal inlogpogingen (frontend). Standaard = 5.

"FrontEndLog"
- Bestand om de frontend login pogingen te loggen. Geef een bestandsnaam, of laat leeg om uit te schakelen.

"ban_override"
- Overrijden "forbid_on_block" wanneer "infraction_limit" wordt overschreden? Wanneer het overrijdt: Geblokkeerde verzoeken retourneert een lege pagina (template bestanden worden niet gebruikt). 200 = Niet overrijden [Standaard]; 403 = Overrijden met "403 Forbidden"; 503 = Overrijden met "503 Service unavailable".

"log_banned_ips"
- Omvatten geblokkeerde verzoeken van verboden IP-adressen in de logbestanden? True = Ja [Standaard]; False = Nee.

"default_dns"
- Een door komma's gescheiden lijst met DNS-servers te gebruiken voor de hostnaam lookups. Standaard = "8.8.8.8,8.8.4.4" (Google DNS). WAARSCHUWING: Verander dit niet tenzij u weet wat u doet!

"search_engine_verification"
- Poging om verzoeken van zoekmachines te bevestigen? Het verifiëren van zoekmachines zorgt ervoor dat ze niet zullen worden verboden als gevolg van het overschrijden van de overtreding limiet (verbod op zoekmachines van uw website zal meestal een negatief effect hebben op uw zoekmachine ranking, SEO, enz). Wanneer geverifieerd, zoekmachines kunnen worden geblokkeerd als per normaal, maar zal niet worden verboden. Wanneer niet geverifieerd, het is mogelijk dat zij worden verboden ten gevolge van het overschrijden van de overtreding limiet. Bovendien, het verifiëren van zoekmachines biedt bescherming tegen nep-zoekmachine aanvragen en tegen de mogelijk schadelijke entiteiten vermomd als zoekmachines (dergelijke verzoeken zal worden geblokkeerd wanneer het verifiëren van zoekmachines is ingeschakeld). True = Inschakelen het verifiëren van zoekmachines [Standaard]; False = Uitschakelen het verifiëren van zoekmachines.

"protect_frontend"
- Geeft aan of de bescherming die gewoonlijk door CIDRAM is voorzien moet worden toegepast op de frontend. True = Ja [Standaard]; False = Nee.

"disable_webfonts"
- Uitschakelen webfonts? True = Ja; False = Nee [Standaard].

#### "signatures" (Categorie)
Configuratie voor signatures.

"ipv4"
- Een lijst van de IPv4 signature bestanden dat CIDRAM moet proberen om te gebruiken, afgebakend door komma's. U kunt items hier toevoegen Als u wilt meer IPv4 signature files in CIDRAM bevatten.

"ipv6"
- Een lijst van de IPv6 signature bestanden dat CIDRAM moet proberen om te gebruiken, afgebakend door komma's. U kunt items hier toevoegen Als u wilt meer IPv6 signature files in CIDRAM bevatten.

"block_cloud"
- Blokkeren CIDRs geïdentificeerd als behorend tot webhosting/cloud-diensten? Als u een api te bedienen vanaf uw website of als u verwacht dat andere websites aan te sluiten op uw website, dit richtlijn moet worden ingesteld op false. Als u niet, dan, dit richtlijn moet worden ingesteld op true.

"block_bogons"
- Blokkeren bogon/martian CIDRs? Als u verwacht aansluitingen om uw website vanuit uw lokale netwerk, vanuit localhost, of vanuit uw LAN, dit richtlijn moet worden ingesteld op false. Als u niet verwacht deze aansluitingen, dit richtlijn moet worden ingesteld op true.

"block_generic"
- Blokkeren CIDRs algemeen aanbevolen voor blacklisting? Dit omvat alle signatures die niet zijn gemarkeerd als onderdeel van elke van de andere, meer specifieke signature categorieën.

"block_proxies"
- Blokkeren CIDRs geïdentificeerd als behorend tot proxy-services? Als u vereisen dat gebruikers kan toegang tot uw website van anonieme proxy-services, dit richtlijn moet worden ingesteld op false. Anders, als u niet nodig anonieme proxies, dit richtlijn moet worden ingesteld op true als een middel ter verbetering van de beveiliging.

"block_spam"
- Blokkeren CIDRs geïdentificeerd als zijnde hoog risico voor spam? Tenzij u problemen ondervindt wanneer u dit doet, in algemeen, dit moet altijd worden ingesteld op true.

"modules"
- Een lijst van module bestanden te laden na verwerking van de IPv4/IPv6 signatures, afgebakend door komma\'s.

"default_tracktime"
- Hoeveel seconden om IPs verboden door modules te volgen. Standaard = 604800 (1 week).

"infraction_limit"
- Maximum aantal overtredingen een IP mag worden gesteld voordat hij wordt verboden door IP-tracking. Standaard = 10.

"track_mode"
- Wanneer moet overtredingen worden gerekend? False = Wanneer IPs geblokkeerd door modules worden. True = Wanneer IPs om welke reden geblokkeerd worden.

#### "recaptcha" (Categorie)
Optioneel, u kan uw gebruikers te voorzien van een manier om de "Toegang Geweigerd" pagina te omzeilen, door middel van het invullen van een reCAPTCHA instantie, als u wilt om dit te doen. Dit kan helpen om een aantal van de risico's die samenhangen met valse positieven te beperken, in die situaties waar we niet helemaal zeker of er een verzoek is voortgekomen uit een machine of een mens.

Vanwege de risico's die samenhangen met het verstrekken van een manier voor eindgebruikers om de "Toegang Geweigerd" pagina te omzeilen, algemeen, ik zou adviseren tegen het inschakelen van deze functie tenzij u voelt het om nodig om dit te doen. Situaties waarin het nodig zou zijn: Als uw website heeft klanten/gebruikers die moeten toegang hebben tot uw website, en als dit is iets dat niet kan worden gecompromitteerd, maar als deze klanten/gebruikers deze verbinding maakt vanuit een vijandig netwerk dat mogelijk ook zou kunnen dragen ongewenste verkeer, en het blokkeren van deze ongewenste verkeer is ook iets dat niet kan worden gecompromitteerd, in deze bijzondere no-win situaties, de functie reCAPTCHA kan van pas komen als een middel van het toestaan van de wenselijke klanten/gebruikers, terwijl het vermijden van het het ongewenste verkeer vanaf hetzelfde netwerk. Dat gezegd hebbende hoewel, gezien het feit dat de bestemming van een CAPTCHA is om onderscheid te maken tussen mensen en niet-mensen, de functie reCAPTCHA zou alleen helpen in deze no-win situaties als zou veronderstellen dat deze ongewenste verkeer is niet-humaan (b.v., spambots, schrapers, hack gereedschappen, geautomatiseerde verkeer), in tegenstelling tot ongewenst menselijk verkeer (zoals menselijke spammers, hackers, c.s.).

Om een "site key" en een "secret key" te verkrijgen (vereist voor het gebruik van reCAPTCHA), ga naar: [https://developers.google.com/recaptcha/](https://developers.google.com/recaptcha/)

"usemode"
- Bepaalt hoe CIDRAM reCAPTCHA moet gebruiken.
- 0 = reCAPTCHA is volledig uitgeschakeld (standaard).
- 1 = reCAPTCHA is ingeschakeld voor alle signatures.
- 2 = reCAPTCHA is ingeschakeld alleen voor signatures die behoren tot secties speciaal gemarkeerde binnen de signature bestanden.
- (Een andere waarde wordt op dezelfde wijze als 0 behandeld).

"lockip"
- Geeft aan of hashes moeten worden vergrendeld om specifieke IPs. False = Cookies en hashes KAN worden gebruikt voor meerdere IP-adressen (standaard). True = Cookies en hashes kan NIET worden gebruikt voor meerdere IP-adressen (cookies/hashes worden vergrendeld om IPs).
- Notitie: "lockip" waarde wordt genegeerd als "lockuser" is false, te wijten aan dat het mechanisme voor het onthouden van "gebruikers" verschilt afhankelijk van deze waarde.

"lockuser"
- Geeft aan of het succesvol afronden van een reCAPTCHA instantie moet worden vergrendeld om specifieke gebruikers. False = Succesvolle afronding van een reCAPTCHA instantie zal het verlenen van de toegang tot alle verzoeken die afkomstig zijn van hetzelfde IP als die gebruikt wordt door de gebruiker het invullen van de reCAPTCHA instantie; Cookies en hashes worden niet gebruikt; In plaats daarvan zal een IP whitelist worden gebruikt. True = Succesvolle afronding van een reCAPTCHA instantie zal alleen toegang tot de gebruiker te verlenen het invullen van de reCAPTCHA instantie; Cookies en hashes worden gebruikt om de gebruiker te herinneren; Een IP whitelist wordt niet gebruikt (standaard).

"sitekey"
- Deze waarde moet overeenkomen met de "site key" voor uw reCAPTCHA, die kan worden gevonden binnen de reCAPTCHA dashboard.

"secret"
- Deze waarde moet overeenkomen met de "secret key" voor uw reCAPTCHA, die kan worden gevonden binnen de reCAPTCHA dashboard.

"expiry"
- Wanneer "lockuser" is true (standaard), om te onthouden wanneer een gebruiker met succes heeft doorstaan een reCAPTCHA instantie, voor pagina verzoeken, CIDRAM genereert een standaard cookie bevat een hash die overeenkomt met een intern register met dezelfde hash; Toekomstige pagina verzoeken zullen deze overeenkomstige hashes gebruiken om te verifiëren dat een gebruiker eerder al heeft gepasseerd een reCAPTCHA instantie. Wanneer "lockuser" is false, een IP whitelist wordt gebruikt om te bepalen of verzoeken moeten worden toegestaan van het IP-adres van inkomende verzoeken; Inzendingen worden toegevoegd aan deze whitelist wanneer de reCAPTCHA instantie met succes heeft doorstaan. Hoeveel uren moeten deze cookies, hashes en whitelist inzendingen blijven geldig? Standaard = 720 (1 maand).

"logfile"
- Log alle reCAPTCHA pogingen? Zo ja, geef de naam te gebruiken voor het logbestand. Zo nee, laat u deze variabele leeg.

*Handige tip: Als u wil, u kunt datum/tijd informatie toevoegen om de namen van uw logbestanden door deze op in naam inclusief: `{yyyy}` voor volledige jaar, `{yy}` voor verkorte jaar, `{mm}` voor maand, `{dd}` voor dag, `{hh}` voor het uur.*

*Voorbeelden:*
- *`logfile='recaptcha.{yyyy}-{mm}-{dd}-{hh}.txt'`*

#### "template_data" (Categorie)
Richtlijnen/Variabelen voor sjablonen en thema's.

Betreft de HTML-uitvoer gebruikt om de "Toegang Geweigerd" pagina te genereren. Als u gebruik aangepaste thema's voor CIDRAM, HTML-uitvoer is afkomstig van de `template_custom.html` bestand, en alternatief, HTML-uitvoer is afkomstig van de `template.html` bestand. Variabelen geschreven om dit sectie van het configuratiebestand worden geïnterpreteerd aan de HTML-uitvoer door middel van het vervangen van variabelennamen omringd door accolades gevonden binnen de HTML-uitvoer met de bijbehorende variabele gegevens. Bijvoorbeeld, waar `foo="bar"`, elk geval van `<p>{foo}</p>` gevonden binnen de HTML-uitvoer `<p>bar</p>` zal worden.

"css_url"
- De sjabloonbestand voor aangepaste thema's maakt gebruik van externe CSS-eigenschappen, terwijl de sjabloonbestand voor het standaardthema maakt gebruik van interne CSS-eigenschappen. Om CIDRAM instrueren om de sjabloonbestand voor aangepaste thema's te gebruiken, geef het openbare HTTP-adres van uw aangepaste thema's CSS-bestanden via de `css_url` variabele. Als u dit variabele leeg laat, CIDRAM zal de sjabloonbestand voor de standaardthema te gebruiken.

---


### 7. <a name="SECTION7"></a>SIGNATURE FORMAAT

#### 7.0 BASICS

Een beschrijving van het formaat en de structuur van de signatures gebruikt door CIDRAM kan gevonden worden gedocumenteerd in platte tekst binnen een van de twee aangepaste signatures bestanden. Raadpleeg de documentatie om meer te leren over het formaat en de structuur van de signatures van CIDRAM.

Alle IPv4 signatures volgt het formaat: `xxx.xxx.xxx.xxx/yy [Function] [Param]`.
- `xxx.xxx.xxx.xxx` vertegenwoordigt het begin van het CIDR blok (de octetten van de eerste IP-adres in het blok).
- `yy` vertegenwoordigt het CIDR blokgrootte [1-32].
- `[Function]` instrueert het script wat te doen met de signature (hoe de signature moet worden beschouwd).
- `[Param]` vertegenwoordigt alle aanvullende informatie dat kan worden verlangd door `[Function]`.

Alle IPv6 signatures volgt het formaat: `xxxx:xxxx:xxxx:xxxx::xxxx/yy [Function] [Param]`.
- `xxxx:xxxx:xxxx:xxxx::xxxx` vertegenwoordigt het begin van het CIDR blok (de octetten van de eerste IP-adres in het blok). Compleet notatie en verkorte notatie zijn beide aanvaardbaar (en ieder moet volg de juiste en relevante normen van IPv6-notatie, maar met één uitzondering: een IPv6-adres kan nooit beginnen met een afkorting wanneer het wordt gebruikt in een signature voor dit script, vanwege de manier waarop CIDRs door het script zijn gereconstrueerd; Bijvoorbeeld, `::1/128` moet worden uitgedrukt, bij gebruik in een signature, als `0::1/128`, en `::0/128` uitgedrukt als `0::/128`).
- `yy` vertegenwoordigt het CIDR blokgrootte [1-128].
- `[Function]` instrueert het script wat te doen met de signature (hoe de signature moet worden beschouwd).
- `[Param]` vertegenwoordigt alle aanvullende informatie dat kan worden verlangd door `[Function]`.

De signature bestanden voor CIDRAM MOET gebruiken Unix-stijl regeleinden (`%0A`, or `\n`)! Andere soorten/stijlen van regeleinden (bv, Windows `%0D%0A` of `\r\n` regeleinden, Mac `%0D` of `\r` regeleinden, ezv) KAN worden gebruikt, maar zijn NIET voorkeur. Non-Unix-stijl regeleinden wordt genormaliseerd naar Unix-stijl regeleinden door het script.

Nauwkeurig en correct CIDR-notatie is vereist, anders zal het script NIET de signatures herkennen. Tevens, alle CIDR signatures van dit script MOET beginnen met een IP-adres waarvan het IP-nummer kan gelijkmatig in het blok divisie vertegenwoordigd door haar CIDR blokgrootte verdelen (bv, als u wilde alle IP-adressen van `10.128.0.0` naar `11.127.255.255` te blokkeren, `10.128.0.0/8` zou door het script NIET worden herkend, maar `10.128.0.0/9` en `11.0.0.0/9` in combinatie, ZOU door het script worden herkend).

Alles wat in de signature bestanden niet herkend als een signature noch als signature-gerelateerde syntaxis door het script worden GENEGEERD, daarom dit betekent dat om veilig alle niet-signature gegevens die u wilt in de signature bestanden u kunnen zetten zonder verbreking van de signature bestanden of de script. Reacties zijn in de signature bestanden aanvaardbare, en geen speciale opmaak of formaat is vereist voor hen. Shell-stijl hashing voor commentaar heeft de voorkeur, maar is niet afgedwongen; Functioneel, het maakt geen verschil voor het script ongeacht of u kiest voor Shell-stijl hashing om commentaar te gebruiken, maar gebruik van Shell-stijl hashing helpt IDE's en platte tekst editors om correct te markeren de verschillende delen van de signature bestanden (en dus, Shell-stijl hashing kan helpen als een visueel hulpmiddel tijdens het bewerken).

Mogelijke waarden van `[Function]` zijn als volgt:
- Run
- Whitelist
- Greylist
- Deny

Als "Run" wordt gebruikt, als de signature wordt geactiveerd, het script zal proberen (gebruiken een `require_once` statement) om een externe PHP-script uit te voeren, gespecificeerd door de `[Param]` waarde (de werkmap moet worden de "/vault/" map van het script).

Voorbeeld: `127.0.0.0/8 Run example.php`

Dit kan handig zijn als u wilt, voor enige specifieke IPs en/of CIDRs, om specifieke PHP-code uit te voeren.

Als "Whitelist" wordt gebruikt, als de signature wordt geactiveerd, het script zal alle detecties resetten (als er is al enige detecties) en breek de testfunctie. `[Param]` worden genegeerd. Deze functie werkt als een whitelist, om te voorkomen dat bepaalde IP-adressen en/of CIDRs van wordt gedetecteerd.

Voorbeeld: `127.0.0.1/32 Whitelist`

Als "Greylist" wordt gebruikt, als de signature wordt geactiveerd, het script zal alle detecties resetten (als er is al enige detecties) en doorgaan naar de volgende signature bestand te gaan met verwerken. `[Param]` worden genegeerd.

Voorbeeld: `127.0.0.1/32 Greylist`

Als "Deny" wordt gebruikt, als de signature wordt geactiveerd, veronderstelling dat er geen whitelist signature is geactiveerd voor het opgegeven IP-adres en/of opgegeven CIDR, toegang tot de beveiligde pagina wordt ontzegd. "Deny" is wat u wilt gebruiken om een IP-adres en/of CIDR range te daadwerkelijk blokkeren. Wanneer enige signatures zijn geactiveerd er dat gebruik "Deny", de "Toegang Geweigerd" pagina van het script zal worden gegenereerd en het verzoek naar de beveiligde pagina wordt gedood.

De `[Param]` waarde geaccepteerd door "Deny" zal worden parsed aan de "Toegang Geweigerd" pagina-uitgang, geleverd aan de klant/gebruiker als de genoemde reden voor hun toegang tot de gevraagde pagina worden geweigerd. Het kan een korte en eenvoudige zin zijn, uit te leggen waarom u hebt gekozen om ze te blokkeren (iets moeten volstaan, zelfs een simpele "Ik wil je niet op mijn website"), of een van het handjevol korte woorden geleverd door het script, dat als gebruikt, wordt vervangen door het script met een voorbereide toelichting waarom de klant/gebruiker is geblokkeerd.

De voorbereide toelichtingen hebben L10N ondersteuning en kan worden vertaald door het script op basis van de taal die u opgeeft naar de `lang` richtlijn van het script configuratie. Tevens, u kunt het script instrueren om "Deny" signatures te negeren op basis van hun `[Param]` waarde (als ze gebruik maken van deze korte woorden) via de richtlijnen gespecificeerd door het script configuratie (elk kort woord heeft een overeenkomstige richtlijn te verwerken overeenkomende signatures of te negeren hen). `[Param]` waarden dat niet gebruiken deze korte woorden, echter, hebben geen L10N ondersteuning en daarom zal NIET worden vertaald door het script, en tevens, en zijn niet direct controleerbaar door het script configuratie.

De beschikbare korte woorden zijn:
- Bogon
- Cloud
- Generic
- Proxy
- Spam

#### 7.1 ETIKETTEN

Als u wilt uw aangepaste signatures te splitsen in afzonderlijke secties, u kunt deze individuele secties te identificeren om het script door toevoeging van een "sectie etiket" onmiddellijk na de signatures van elke sectie, samen met de naam van uw signature sectie (zie het onderstaande voorbeeld).

```
# Sectie 1.
1.2.3.4/32 Deny Bogon
2.3.4.5/32 Deny Cloud
4.5.6.7/32 Deny Generic
5.6.7.8/32 Deny Spam
6.7.8.9/32 Deny Proxy
Tag: Sectie 1
```

Om sectie etiketteren te breken en zodat de etiketten zijn niet onjuist geïdentificeerd met signature secties uit eerder in de signature bestanden, gewoon ervoor zorgen dat er ten minste twee opeenvolgende regeleinden tussen uw etiket en uw eerdere signature secties. Een ongeëtiketteerd signatures wordt standaard om "IPv4" of "IPv6" (afhankelijk van welke soorten signatures worden geactiveerd).

```
1.2.3.4/32 Deny Bogon
2.3.4.5/32 Deny Cloud

4.5.6.7/32 Deny Generic
5.6.7.8/32 Deny Spam
Tag: Sectie 1
```

In het bovenstaande voorbeeld `1.2.3.4/32` en `2.3.4.5/32` zal worden geëtiketteerd als "IPv4", terwijl `4.5.6.7/32` en `5.6.7.8/32` zal worden geëtiketteerd als "Sectie 1".

Als u wilt signatures te vervallen na verloop van tijd, op soortgelijke wijze als sectie etiketten, u kan een "vervaltijd etiket" gebruikt om aan te geven wanneer signatures moet niet meer geldig. Vervaltijd etiketten gebruiken het formaat "JJJJ.MM.DD" (zie het onderstaande voorbeeld).

```
# Sectie 1.
1.2.3.4/32 Deny Generic
2.3.4.5/32 Deny Generic
Expires: 2016.12.31
```

Sectie etiketten en vervaltijd etiketten kunnen worden gebruikt in combinatie, en beide zijn optioneel (zie het onderstaande voorbeeld).

```
# Voorbeeld Sectie.
1.2.3.4/32 Deny Generic
Tag: Voorbeeld Sectie.
Expires: 2016.12.31
```

#### 7.2 YAML

##### 7.2.0 YAML BASICS

Een vereenvoudigde vorm van YAML markup kan worden gebruikt in signature bestanden voor het bepalen van gedragingen en specifieke instellingen voor afzonderlijke signature secties. Dit kan handig zijn als u de waarde van uw configuratie richtlijnen willen afwijken op basis van individuele signatures en signature secties (bijvoorbeeld; als u wilt om een e-mailadres te leveren voor support tickets voor alle gebruikers geblokkeerd door een bepaalde signature, maar wil niet om een e-mailadres te leveren voor support tickets voor de gebruikers geblokkeerd door andere signatures; als u wilt een specifieke signatures te leiden tot een pagina redirect; als u wilt een signature sectie voor gebruik met reCAPTCHA te markeren; als u wilt om geblokkeerde toegang pogingen te loggen in afzonderlijke bestanden op basis van individuele signatures en/of signature secties).

Het gebruik van YAML markup in de signature bestanden is volledig optioneel (d.w.z., u kan het gebruiken als u wenst te doen, maar u bent niet verplicht om dit te doen), en is in staat om de meeste (maar niet alle) configuratie richtlijnen hefboomeffect.

Notitie: YAML markup implementatie in CIDRAM is zeer simplistisch en zeer beperkt; Het is bedoeld om de specifieke eisen van CIDRAM te voldoen op een manier dat heeft de vertrouwdheid van YAML markup, maar noch volgt noch voldoet aan de officiële specificaties (en zal daarom niet zich op dezelfde wijze als grondiger implementaties elders, en is misschien niet geschikt voor alle andere projecten elders).

In CIDRAM, YAML markup segmenten worden geïdentificeerd aan het script door drie streepjes ("---"), en eindigen naast hun bevattende signature secties door dubbel-regeleinden. Een typische YAML markup segment binnen een signature sectie bestaat uit drie streepjes op een lijn onmiddellijk na de lijst van CIDRs en elke etiketten, gevolgd door een tweedimensionale lijst van sleutel-waarde paren (eerste dimensie, configuratie richtlijn categorieën; tweede dimensie, configuratie richtlijnen) voor welke configuratie richtlijnen moeten worden gewijzigd (en om welke waarden) wanneer een signature in die signature sectie wordt geactiveerd (zie de onderstaande voorbeelden).

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

##### 7.2.1 HOE OM SIGNATURE SECTIES TE MARKEREN VOOR GEBRUIK MET reCAPTCHA

Als "usemode" is 0 of 1, signature secties hoeven niet voor gebruik met reCAPTCHA te markeren (omdat ze al wil of wil niet gebruik reCAPTCHA, afhankelijk van deze instelling).

Als "usemode" is 2, om signature secties te markeren voor gebruik met reCAPTCHA, een invoer wordt opgenomen in het YAML segment voor dat signature sectie (zie het onderstaande voorbeeld).

```
# Deze sectie zal reCAPTCHA te gebruiken.
1.2.3.4/32 Deny Generic
2.3.4.5/32 Deny Generic
Tag: reCAPTCHA-Enabled
---
recaptcha:
 enabled: true
```

Notitie: Een reCAPTCHA instantie zal ALLEEN worden aangeboden aan de gebruiker als reCAPTCHA is ingeschakeld (met "usemode" als 1, of "usemode" als 2 met "enabled" als true), en als precies ÉÉN signature is geactiveerd (niet meer, niet minder; als er meerdere signatures worden geactiveerd, een reCAPTCHA instantie zal NIET worden aangeboden).

#### 7.3 EXTRA INFORMATIE

Bovendien, als u wilt CIDRAM om enkele specifieke secties in iedereen van de signature bestanden te negeren, kunt u het `ignore.dat` bestand gebruiken om specificeren welke secties te negeren. Op een nieuwe regel, schrijven `Ignore`, gevolgd door een spatie, gevolgd door de naam van de sectie die u wilt CIDRAM te negeren (zie het onderstaande voorbeeld).

```
Ignore Sectie 1
```

Raadpleeg de aangepaste signature bestanden voor meer informatie.

---


### 8. <a name="SECTION8"></a>VEELGESTELDE VRAGEN (FAQ)

#### Wat is een "vals positieve"?

De term "vals positieve" (*alternatief: "vals positieve fout"; "vals alarm"*; Engels: *false positive*; *false positive error*; *false alarm*), zeer eenvoudig beschreven, en een algemene context, wordt gebruikt bij het testen voor een toestand, om verwijst naar om de resultaten van die test, wanneer de resultaten positief zijn (d.w.z, de toestand wordt vastgesteld als "positief"), maar wordt verwacht "negatief" te zijn (d.w.z, de toestand in werkelijkheid is "negatief"). Een "vals positieve" analoog aan "huilende wolf" kan worden beschouwd (waarin de toestand wordt getest, is of er een wolf in de buurt van de kudde, de toestand is "vals" in dat er geen wolf in de buurt van de kudde, en de toestand wordt gerapporteerd als "positief" door de herder door middel van schreeuwen "wolf, wolf"), of analoog aan situaties in medische testen waarin een patiënt gediagnosticeerd als met een ziekte of aandoening, terwijl het in werkelijkheid, hebben ze geen ziekte of aandoening.

Enkele andere termen die worden gebruikt zijn "waar positieve", "waar negatieve" en "vals negatieve". Een "waar positieve" verwijst naar wanneer de resultaten van de test en de huidige staat van de toestand zijn beide waar (of "positief"), and a "waar negatieve" verwijst naar wanneer de resultaten van de test en de huidige staat van de toestand zijn beide vals (of "negatief"); En "waar positieve" of en "waar negatieve" wordt beschouwd als een "correcte gevolgtrekking" zijn. De antithese van een "vals positieve" is een "vals negatieve"; Een "vals negatieve" verwijst naar wanneer de resultaten van de test is negatief (d.w.z, de aandoening wordt vastgesteld als "negatief"), maar wordt verwacht "positief" te zijn (d.w.z, de toestand in werkelijkheid is "positief").

In de context van CIDRAM, deze termen verwijzen naar de signatures van CIDRAM en wat/wie ze blokkeren. Wanneer CIDRAM blokkeert een IP-adres, als gevolg van slechte, verouderde of onjuiste signature, maar moet niet hebben gedaan, of wanneer het doet om de verkeerde redenen, we verwijzen naar deze gebeurtenis als een "vals positieve". Wanneer CIDRAM niet in slaagt te blokkeren om een IP-adres dat had moeten worden geblokkeerd, als gevolg van onvoorziene bedreigingen, ontbrekende signatures of tekorten in zijn signatures, we verwijzen naar deze gebeurtenis als een "gemiste detectie" (dat is analoog aan een "vals negatieve").

Dit kan worden samengevat in de onderstaande tabel:

&nbsp; | CIDRAM moet *NIET* een IP-adres te blokkeren | CIDRAM *MOET* een IP-adres te blokkeren
---|---|---
CIDRAM *NIET* doet blokkeren van een IP-adres | Waar negatieve (correcte gevolgtrekking) | Gemiste detectie (analoog aan vals negatieve)
CIDRAM *DOET* blokkeren van een IP-adres | __Vals positieve__ | Waar positieve (correcte gevolgtrekking)

#### Kan CIDRAM blok hele landen?

Ja. De eenvoudigste manier om dit te bereiken zou zijn om sommige van de optionele land blocklists door Macmathan te installeren. Dit kan gedaan worden met een paar simpele muisklikken direct vanaf de frontend updates pagina, of, als u liever voor de frontend te blijven uitgeschakeld, door ze rechtstreeks downloaden van de **[optionele land blocklists downloads pagina](https://macmathan.info/blocklists)**, uploaden van hen om de vault, en citeren van hun namen in de desbetreffende configuratie richtlijnen.

#### Hoe vaak worden signatures bijgewerkt?

Bijwerkfrequentie varieert afhankelijk van de signature bestanden betrokken. Alle de onderhouders voor CIDRMA signature bestanden algemeen proberen om hun signatures regelmatig bijgewerkt te houden, maar als gevolg van dat ieder van ons hebben verschillende andere verplichtingen, ons leven buiten het project, en zijn niet financieel gecompenseerd (d.w.z., betaald) voor onze inspanningen aan het project, een nauwkeurige updateschema kan niet worden gegarandeerd. In het algemeen, signatures zullen worden bijgewerkt wanneer er genoeg tijd om dit te doen, en in het algemeen, onderhouders proberen om prioriteiten te stellen op basis van noodzaak en van hoe vaak veranderingen optreden tussen ranges. Het verlenen van bijstand wordt altijd gewaardeerde als u bent bereid om dat te doen.

#### Ik heb een fout tegengekomen tijdens het gebruik van CIDRAM en ik weet niet wat te doen! Help alstublieft!

- Gebruikt u de nieuwste versie van de software? Gebruikt u de nieuwste versies van uw signature bestanden? Indien het antwoord op een van deze twee vragen is nee, probeer eerst om alles te bijwerken, en controleer of het probleem zich blijft voordoen. Als dit aanhoudt, lees verder.
- Hebt u door alle documentatie gecontroleerd? Zo niet, doe dat dan. Als het probleem niet kan worden opgelost met behulp van de documentatie, lees verder.
- Hebt u de **[kwesties pagina](https://github.com/Maikuolan/CIDRAM/issues)** gecontroleerd, om te zien of het probleem al eerder is vermeld? Als het eerder vermeld, controleer of eventuele suggesties, ideeën en/of oplossingen werden verstrekt, en volg als per nodig om te proberen het probleem op te lossen.
- Hebt u de **[CIDRAM support forum van Spambot Security](http://www.spambotsecurity.com/forum/viewforum.php?f=61)** gecontroleerd, om te zien of het probleem al eerder is vermeld? Als het eerder vermeld, controleer of eventuele suggesties, ideeën en/of oplossingen werden verstrekt, en volg als per nodig om te proberen het probleem op te lossen.
- Als het probleem blijft bestaan, laat het ons dan weten door het creëren van een nieuwe vraag op de kwesties pagina of door het starten van een nieuwe discussie over het support forum.

#### Ik ben geblokkeerd door CIDRAM van een website die ik wil bezoeken! Help alstublieft!

CIDRAM biedt een manier voor website-eigenaren om ongewenst verkeer te blokkeren, maar het is de verantwoordelijkheid van de website-eigenaren om zelf te beslissen hoe ze willen CIDRAM gebruiken. In het geval van de valse positieven met betrekking tot de signature bestanden normaal meegeleverd met CIDRAM, correcties kunnen worden gemaakt, maar met betrekking tot het wordt gedeblokkeerd van specifieke websites, u nodig hebt om te communiceren met de eigenaren van de websites in kwestie. In gevallen waarin correcties worden gemaakt, op zijn minst, zullen ze nodig hebben om hun signature bestanden en/of installatie bij te werken, en in andere gevallen (zoals bijvoorbeeld, waarin ze hun installatie hebt gewijzigd, creëerden hun eigen aangepaste signatures, ezv), het is hun verantwoordelijkheid om uw probleem op te lossen, en is geheel buiten onze controle.

#### Ik wil CIDRAM gebruiken met een PHP-versie ouder dan 5.4.0; Kan u helpen?

Nee. PHP 5.4.0 bereikte officiële EoL ("End of Life", of eind van het leven) in 2014, en verlengd veiligheid ondersteuning werd beëindigd in 2015. Met ingang van het schrijven van dit, het is 2017, en PHP 7.1.0 is al beschikbaar. Momenteel, ondersteuning wordt verleend voor het gebruik van CIDRAM met PHP 5.4.0 en alle beschikbare nieuwere PHP-versies, maar als u probeert te CIDRAM gebruiken met een oudere PHP-versies, steun zal niet worden verstrekt.

---


Laatste Bijgewerkt: 31 Maart 2017 (2017.03.31).
