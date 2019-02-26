## Documentatie voor CIDRAM (Nederlandse).

### Inhoud
- 1. [PREAMBULE](#SECTION1)
- 2. [HOE TE INSTALLEREN](#SECTION2)
- 3. [HOE TE GEBRUIKEN](#SECTION3)
- 4. [FRONTEND MANAGEMENT](#SECTION4)
- 5. [BESTANDEN IN DIT PAKKET](#SECTION5)
- 6. [CONFIGURATIE-OPTIES](#SECTION6)
- 7. [SIGNATURE FORMAAT](#SECTION7)
- 8. [BEKENDE COMPATIBILITEITSPROBLEMEN](#SECTION8)
- 9. [VEELGESTELDE VRAGEN (FAQ)](#SECTION9)
- 10. *Gereserveerd voor toekomstige toevoegingen aan de documentatie.*
- 11. [LEGALE INFORMATIE](#SECTION11)

*Opmerking over vertalingen: In geval van fouten (b.v., verschillen tussen vertalingen, typefouten, enz), de Engels versie van de README wordt beschouwd als het origineel en gezaghebbende versie. Als u vinden elke fouten, uw hulp bij het corrigeren van hen zou worden toegejuicht.*

---


### 1. <a name="SECTION1"></a>PREAMBULE

CIDRAM (Classless Inter-Domain Routing Access Manager) is een PHP-script ontworpen om websites te beschermen door het blokkeren van verzoeken afkomstig van IP-adressen beschouwd als bronnen van ongewenste verkeer, inclusief (maar niet gelimiteerd tot) het verkeer van niet-menselijke toegang eindpunten, cloud-diensten, spambots, schrapers/scrapers, enz. Het doet dit door het berekenen van de mogelijke CIDR's van de IP-adressen geleverde van binnenkomende verzoeken en dan het vergelijken van deze mogelijke CIDR's tegen zijn signatuurbestanden (deze signatuurbestanden bevatten lijsten van CIDR's van IP-adressen beschouwd als bronnen van ongewenste verkeer); Als overeenkomsten worden gevonden, de verzoeken worden geblokkeerd.

*(Zien: [Wat is een "CIDR"?](#WHAT_IS_A_CIDR)).*

[CIDRAM](https://cidram.github.io/) COPYRIGHT 2016 en verder GNU/GPLv2 van [Caleb M (Maikuolan)](https://github.com/Maikuolan).

Dit script is gratis software; u kunt, onder de voorwaarden van de GNU General Public License zoals gepubliceerd door de Free Software Foundation, herdistribueren en/of wijzigen dit; ofwel versie 2 van de Licentie, of (naar uw keuze) enige latere versie. Dit script wordt gedistribueerd in de hoop dat het nuttig zal zijn, maar ZONDER ENIGE GARANTIE; zonder zelfs de impliciete garantie van VERKOOPBAARHEID of GESCHIKTHEID VOOR EEN BEPAALD DOEL. Zie de GNU General Public License voor meer informatie, gelegen in het `LICENSE.txt` bestand en ook beschikbaar uit:
- <https://www.gnu.org/licenses/>.
- <https://opensource.org/licenses/>.

Dit document en de bijbehorende pakket kunt gedownload gratis zijn van:
- [GitHub](https://github.com/CIDRAM/CIDRAM).
- [Bitbucket](https://bitbucket.org/Maikuolan/cidram).
- [SourceForge](https://sourceforge.net/projects/cidram/).

---


### 2. <a name="SECTION2"></a>HOE TE INSTALLEREN

#### 2.0 HANDMATIG INSTALLEREN

1) Omdat u zijn lezen dit, ik ben ervan uit u al gedownload een gearchiveerde kopie van het script, uitgepakt zijn inhoud en heeft het ergens op uw lokale computer. Vanaf hier, u nodig te bepalen waar op uw host of CMS die inhoud te plaatsen. Een bestandsmap zoals `/public_html/cidram/` of soortgelijk (hoewel, het is niet belangrijk welke u kiest, zolang het is iets veilig en iets waar u blij mee bent) zal volstaan. *Voordat u het uploaden begint, lees verder..*

2) Hernoemen `config.ini.RenameMe` naar `config.ini` (gelegen binnen `vault`), en facultatief (sterk aanbevolen voor ervaren gebruikers, maar niet aan te raden voor beginners of voor de onervaren), open het (dit bestand bevat alle beschikbare CIDRAM configuratie-opties; boven elke optie moet een korte opmerking te beschrijven wat het doet en wat het voor). Pas deze opties als het u past, volgens welke geschikt is voor uw configuratie. Sla het bestand, sluiten.

3) Upload de inhoud (CIDRAM en zijn bestanden) naar het bestandsmap die u zou op eerder besloten (u nodig niet de `*.txt`/`*.md` bestanden opgenomen, maar meestal, u moeten uploaden alles).

4) CHMOD het `vault`-bestandsmap naar "755" (als er problemen, u kan proberen "777"; dit is minder veilig, hoewel). De belangrijkste bestandsmap opslaan van de inhoud (degene die u eerder koos), gewoonlijk, kunt worden genegeerd, maar CHMOD-status moet worden gecontroleerd als u machtigingen problemen heeft in het verleden met uw systeem (standaard, moet iets zijn als "755"). In het kort: Om het pakket goed te laten werken, PHP moet bestanden in de `vault`-bestandsmap kunnen lezen en schrijven. Veel dingen (updaten, loggen, enz) zullen niet mogelijk zijn, als PHP niet naar de `vault`-bestandsmap kan schrijven, en het pakket zal helemaal niet werken als PHP niet kan lezen vanuit de `vault`-bestandsmap. Voor de beste beveiliging echter de `vault`-bestandsmap mag NIET publiek toegankelijk zijn (gevoelige informatie, zoals de informatie in `config.ini` of `frontend.dat`, kan worden blootgesteld aan potentiële aanvallers als de `vault`-bestandsmap publiek toegankelijk is).

5) Volgende, u nodig om "haak" CIDRAM om uw systeem of CMS. Er zijn verschillende manieren waarop u kunt "haak" scripts zoals CIDRAM om uw systeem of CMS, maar het makkelijkste is om gewoon omvatten voor het script aan het begin van een kern bestand van uw systeem of CMS (een die het algemeen altijd zal worden geladen wanneer iemand heeft toegang tot een pagina in uw website) met behulp van een `require` of `include` opdracht. Meestal is dit wel iets worden opgeslagen in een bestandsmap zoals `/includes`, `/assets` of `/functions`, en zal vaak zijn vernoemd iets als `init.php`, `common_functions.php`, `functions.php` of soortgelijk. U nodig om te bepalen welk bestand dit is voor uw situatie; Als u problemen ondervindt bij het bepalen van dit voor uzelf, ga naar de CIDRAM issues pagina op GitHub voor assistentie. Om dit te doen [te gebruiken `require` of `include`], plaatst u de volgende regel code aan het begin op die kern bestand, vervangen van de string die binnen de aanhalingstekens met het exacte adres van het `loader.php` bestand (lokaal adres, niet het HTTP-adres; zal vergelijkbaar zijn met de eerder genoemde vault adres).

`<?php require '/user_name/public_html/cidram/loader.php'; ?>`

Opslaan bestand, sluiten, heruploaden.

-- OF ALTERNATIEF --

Als u gebruik een Apache webserver en als u heeft toegang om `php.ini`, u kunt gebruiken de `auto_prepend_file` richtlijn naar prepend CIDRAM wanneer een PHP verzoek wordt gemaakt. Zoiets als:

`auto_prepend_file = "/user_name/public_html/cidram/loader.php"`

Of dit in het `.htaccess` bestand:

`php_value auto_prepend_file "/user_name/public_html/cidram/loader.php"`

6) Dat is alles! :-)

#### 2.1 INSTALLEREN MET COMPOSER

[CIDRAM is geregistreerd bij Packagist](https://packagist.org/packages/cidram/cidram), en dus, als u bekend bent met Composer, kunt u Composer gebruiken om CIDRAM installeren (u zult nog steeds nodig om de configuratie, rechten en haken te bereiden niettemin; zie "handmatig installeren" stappen 2, 4, en 5).

`composer require cidram/cidram`

#### 2.2 INSTALLEREN VOOR WORDPRESS

Als u wilt CIDRAM gebruiken met WordPress, u kunt alle bovenstaande instructies negeren. [CIDRAM is geregistreerd als een plugin met de WordPress plugins databank](https://wordpress.org/plugins/cidram/), en u kunt CIDRAM direct vanaf het plugin-dashboard installeren. U kunt het op dezelfde manier installeren als elke andere plugin, en geen toevoeging stappen nodig zijn. Net als bij de andere installatiemethoden, u kunt uw installatie aanpassen door het wijzigen van de inhoud van de `config.ini` bestand of door de frontend configuratie pagina. Als u de CIDRAM frontend activeren en bijwerken met behulp van de frontend updates pagina, dit zal automatisch synchroniseren met de plugin versie-informatie wordt weergegeven in het plugin-dashboard.

*Waarschuwing: Het bijwerken van CIDRAM via het plugin-dashboard resulteert in een schone installatie! Als u uw installatie hebt aangepast (verander uw configuratie, geïnstalleerde modules, enz), deze aanpassingen worden verloren bij het updaten via het plugin-dashboard! Logbestanden worden ook verloren bij het updaten via het plugin-dashboard! Om de logbestanden en aanpassingen te bewaren, bijwerken via de CIDRAM frontend updates pagina.*

---


### 3. <a name="SECTION3"></a>HOE TE GEBRUIKEN

CIDRAM moet blokkeren ongewenste verzoeken naar uw website automatisch zonder enige handmatige hulp, afgezien van de eerste installatie.

U kunt aanpassen uw configuratie en aanpassen de CIDR's dat zal worden geblokkeerd door het modificeren van het configuratiebestand en/of uw signatuurbestanden.

Als u tegenkomen een valse positieven, neem dan contact met mij op om me te laten weten. *(Zien: [Wat is een "vals positieve"?](#WHAT_IS_A_FALSE_POSITIVE)).*

CIDRAM kan handmatig of via de frontend worden bijgewerkt. CIDRAM kan ook worden bijgewerkt via Composer of WordPress, indien oorspronkelijk via die middelen geïnstalleerd.

---


### 4. <a name="SECTION4"></a>FRONTEND MANAGEMENT

#### 4.0 WAT IS DE FRONTEND.

De frontend biedt een gemakkelijke en eenvoudige manier te onderhouden, beheren en updaten van uw CIDRAM installatie. U kunt bekijken, delen en downloaden log bestanden via de pagina logs, u kunt de configuratie wijzigen via de configuratiepagina, u kunt installeren en verwijderen/desinstalleren van componenten via de pagina updates, en u kunt uploaden, downloaden en wijzigen bestanden in uw vault via de bestandsbeheer.

De frontend is standaard uitgeschakeld om ongeautoriseerde toegang te voorkomen (ongeautoriseerde toegang kan belangrijke gevolgen hebben voor uw website en de beveiliging hebben). Instructies voor het inschakelen van deze zijn hieronder deze paragraaf opgenomen.

#### 4.1 HOE DE FRONTEND TE INSCHAKELEN.

1) Vind de `disable_frontend` richtlijn in `config.ini`, en stel dat het `false` (deze is `true` door standaard).

2) Toegang tot `loader.php` vanuit uw browser (b.v., `http://localhost/cidram/loader.php`).

3) Inloggen u aan met de standaard gebruikersnaam en wachtwoord (admin/password).

Notitie: Nadat u hebt ingelogd voor de eerste keer, om ongeautoriseerde toegang tot de frontend te voorkomen, moet u onmiddellijk veranderen uw gebruikersnaam en wachtwoord! Dit is zeer belangrijk, want het is mogelijk om willekeurige PHP-code te uploaden naar uw website via de frontend.

Voor optimale beveiliging wordt het ten zeerste aanbevolen om "twee-factor authenticatie" voor alle frontend accounts in te schakelen (onderstaande instructies).

#### 4.2 HOE DE FRONTEND GEBRUIKEN.

Instructies worden op elke pagina van de frontend, om uit te leggen hoe het te gebruiken en het beoogde doel. Als u meer uitleg of een speciale hulp nodig hebben, neem dan contact op met ondersteuning. Als alternatief, zijn er een aantal video's op YouTube die zouden kunnen helpen door middel van een demonstratie.

#### 4.3 TWEE-FACTOR AUTHENTICATIE

Het is mogelijk om de frontend veiliger te maken door twee-factor authenticatie ("2FA") in te schakelen. Bij inloggen met een account waarvoor 2FA is ingeschakeld, een e-mail wordt verzonden naar het e-mailadres dat aan dat account is gekoppeld. Deze e-mail bevat een "2FA-code", die de gebruiker vervolgens moet invoeren, in aanvulling op de gebruikersnaam en het wachtwoord, om te kunnen inloggen met dat account. Dit betekent dat het verkrijgen van een accountwachtwoord niet genoeg is voor een hacker of potentiële aanvaller om zich bij dat account te kunnen aanmelden, omdat ze ook al toegang moeten hebben tot het e-mailadres dat aan dat account is gekoppeld om de 2FA-code die aan de sessie is gekoppeld te kunnen ontvangen en gebruiken, daarmee het frontend veiliger maken.

Ten eerste, om twee-factor authenticatie in te schakelen, gebruikt u de frontend-updates-pagina om de PHPMailer-component te installeren. CIDRAM gebruikt PHPMailer voor het verzenden van e-mails. Notitie: Hoewel CIDRAM op zichzelf compatibel met >= 5.4.0 is, PHPMailer heeft nodig PHP >= 5.5.0, daarom is twee-factor authenticatie voor de frontend van CIDRAM niet mogelijk voor PHP 5.4-gebruikers.

Nadat u PHPMailer heeft geïnstalleerd, moet u de configuratie-richtlijnen voor PHPMailer invullen via de configuratiepagina of het configuratiebestand van CIDRAM. Meer informatie over deze configuratie-richtlijnen is opgenomen in de configuratiesectie van dit document. Nadat u de PHPMailer-configuratie-richtlijnen hebt ingevuld, stelt u `Enable2FA` in op `true`. Twee-factor authenticatie moet nu worden ingeschakeld.

Volgende, u moet een e-mailadres koppelen aan een account, zodat CIDRAM weet waar 2FA-codes moeten worden verzonden wanneer hij zich aanmeldt met dat account. Om dit te doen, gebruik het e-mailadres als de gebruikersnaam voor het account (b.v., `foo@bar.tld`), of neem het e-mailadres op als onderdeel van de gebruikersnaam op dezelfde manier als bij het normaal verzenden van een e-mail (b.v., `Foo Bar <foo@bar.tld>`).

Notitie: Het beschermen van uw vault tegen ongeautoriseerde toegang (b.v., door de beveiliging van uw server en openbare toegangsrechten te verbeteren), is hier bijzonder belangrijk, vanwege deze ongeautoriseerde toegang tot uw configuratiebestand (dat is opgeslagen in uw vault), kan het risico lopen dat uw uitgaande SMTP-instellingen (inclusief SMTP gebruikersnaam en wachtwoord) worden weergegeven. U moet ervoor zorgen dat uw vault correct is beveiligd voordat u twee-factor authenticatie inschakelt. Als u dit niet kunt doen, moet u op z'n minst een nieuw e-mailaccount maken, speciaal voor dit doel, om de risico's van blootgestelde SMTP-instellingen te verminderen.

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
/_docs/readme.ur.md | Urdu documentatie.
/_docs/readme.vi.md | Vietnamees documentatie.
/_docs/readme.zh-TW.md | Chinees (traditioneel) documentatie.
/_docs/readme.zh.md | Chinees (vereenvoudigd) documentatie.
/vault/ | Vault bestandsmap (bevat verschillende bestanden).
/vault/classes/ | Klasse bestandsmap. Bevat verschillende klassen die worden gebruikt door CIDRAM.
/vault/classes/Maikuolan/ | Klasse bestandsmap. Bevat verschillende klassen die worden gebruikt door CIDRAM.
/vault/classes/Maikuolan/L10N.php | L10N-handler.
/vault/classes/Maikuolan/YAML.php | YAML-handler.
/vault/classes/.htaccess | Een hypertext toegang bestand (in dit geval, om gevoelige bestanden die behoren tot het script te beschermen tegen toegang door niet-geautoriseerde bronnen).
/vault/classes/Aggregator.php | IP-aggregator.
/vault/fe_assets/ | Frontend data/gegevens.
/vault/fe_assets/.htaccess | Een hypertext toegang bestand (in dit geval, om gevoelige bestanden die behoren tot het script te beschermen tegen toegang door niet-geautoriseerde bronnen).
/vault/fe_assets/_2fa.html | Een HTML sjabloon die wordt gebruikt wanneer de gebruiker om een 2FA-code wordt gevraagd.
/vault/fe_assets/_accounts.html | Een HTML sjabloon voor de frontend accounts pagina.
/vault/fe_assets/_accounts_row.html | Een HTML sjabloon voor de frontend accounts pagina.
/vault/fe_assets/_aux.html | Een HTML sjabloon voor de frontend aanvullende regels pagina.
/vault/fe_assets/_cache.html | Een HTML sjabloon voor de frontend cachegegevens pagina.
/vault/fe_assets/_cidr_calc.html | Een HTML sjabloon voor de CIDR calculator.
/vault/fe_assets/_cidr_calc_row.html | Een HTML sjabloon voor de CIDR calculator.
/vault/fe_assets/_config.html | Een HTML sjabloon voor de frontend configuratie pagina.
/vault/fe_assets/_config_row.html | Een HTML sjabloon voor de frontend configuratie pagina.
/vault/fe_assets/_files.html | Een HTML sjabloon voor de bestandsbeheer.
/vault/fe_assets/_files_edit.html | Een HTML sjabloon voor de bestandsbeheer.
/vault/fe_assets/_files_rename.html | Een HTML sjabloon voor de bestandsbeheer.
/vault/fe_assets/_files_row.html | Een HTML sjabloon voor de bestandsbeheer.
/vault/fe_assets/_home.html | Een HTML sjabloon voor de frontend startpagina.
/vault/fe_assets/_ip_aggregator.html | Een HTML sjabloon voor de IP-aggregator.
/vault/fe_assets/_ip_test.html | Een HTML sjabloon voor de IP test pagina.
/vault/fe_assets/_ip_test_row.html | Een HTML sjabloon voor de IP test pagina.
/vault/fe_assets/_ip_tracking.html | Een HTML sjabloon voor de IP-Tracking pagina.
/vault/fe_assets/_ip_tracking_row.html | Een HTML sjabloon voor de IP-Tracking pagina.
/vault/fe_assets/_login.html | Een HTML sjabloon voor de frontend inlogpagina.
/vault/fe_assets/_logs.html | Een HTML sjabloon voor de frontend logbestanden pagina.
/vault/fe_assets/_nav_complete_access.html | Een HTML sjabloon voor de frontend navigatie-links, voor degenen met volledige toegang.
/vault/fe_assets/_nav_logs_access_only.html | Een HTML sjabloon voor de frontend navigatie-links, voor degenen met logbestanden toegang alleen.
/vault/fe_assets/_range.html | Een HTML sjabloon voor de frontend reeks tafels pagina.
/vault/fe_assets/_range_row.html | Een HTML sjabloon voor de frontend reeks tafels pagina.
/vault/fe_assets/_sections.html | Een HTML sjabloon voor de sectielijst.
/vault/fe_assets/_statistics.html | Een HTML sjabloon voor de frontend statistieken pagina.
/vault/fe_assets/_updates.html | Een HTML sjabloon voor de frontend updates pagina.
/vault/fe_assets/_updates_row.html | Een HTML sjabloon voor de frontend updates pagina.
/vault/fe_assets/frontend.css | CSS-stijlblad voor de frontend.
/vault/fe_assets/frontend.dat | Database voor de frontend (bevat accounts informatie, sessies informatie, en de cache; alleen gegenereerd als de frontend geactiveerd en gebruikt).
/vault/fe_assets/frontend.dat.safety | Gegenereerd als veiligheidsmechanisme indien nodig.
/vault/fe_assets/frontend.html | De belangrijkste HTML-template-bestand voor de frontend.
/vault/fe_assets/icons.php | Icons-handler (door de frontend bestandsbeheer gebruikt).
/vault/fe_assets/pips.php | Pitten-handler (door de frontend bestandsbeheer gebruikt).
/vault/fe_assets/scripts.js | Bevat frontend JavaScript-gegevens.
/vault/lang/ | Bevat CIDRAM lokalisaties.
/vault/lang/.htaccess | Een hypertext toegang bestand (in dit geval, om gevoelige bestanden die behoren tot het script te beschermen tegen toegang door niet-geautoriseerde bronnen).
/vault/lang/lang.ar.cli.php | Arabisch lokalisatie voor CLI.
/vault/lang/lang.ar.fe.php | Arabisch lokalisatie voor het frontend.
/vault/lang/lang.ar.php | Arabisch lokalisatie.
/vault/lang/lang.bn.cli.php | Bengalees lokalisatie voor CLI.
/vault/lang/lang.bn.fe.php | Bengalees lokalisatie voor het frontend.
/vault/lang/lang.bn.php | Bengalees lokalisatie.
/vault/lang/lang.de.cli.php | Duitse lokalisatie voor CLI.
/vault/lang/lang.de.fe.php | Duitse lokalisatie voor het frontend.
/vault/lang/lang.de.php | Duitse lokalisatie.
/vault/lang/lang.en.cli.php | Engels lokalisatie voor CLI.
/vault/lang/lang.en.fe.php | Engels lokalisatie voor het frontend.
/vault/lang/lang.en.php | Engels lokalisatie.
/vault/lang/lang.es.cli.php | Spaanse lokalisatie voor CLI.
/vault/lang/lang.es.fe.php | Spaanse lokalisatie voor het frontend.
/vault/lang/lang.es.php | Spaanse lokalisatie.
/vault/lang/lang.fr.cli.php | Franse lokalisatie voor CLI.
/vault/lang/lang.fr.fe.php | Franse lokalisatie voor het frontend.
/vault/lang/lang.fr.php | Franse lokalisatie.
/vault/lang/lang.hi.cli.php | Hindi lokalisatie voor CLI.
/vault/lang/lang.hi.fe.php | Hindi lokalisatie voor het frontend.
/vault/lang/lang.hi.php | Hindi lokalisatie.
/vault/lang/lang.id.cli.php | Indonesisch lokalisatie voor CLI.
/vault/lang/lang.id.fe.php | Indonesisch lokalisatie voor het frontend.
/vault/lang/lang.id.php | Indonesisch lokalisatie.
/vault/lang/lang.it.cli.php | Italiaanse lokalisatie voor CLI.
/vault/lang/lang.it.fe.php | Italiaanse lokalisatie voor het frontend.
/vault/lang/lang.it.php | Italiaanse lokalisatie.
/vault/lang/lang.ja.cli.php | Japanse lokalisatie voor CLI.
/vault/lang/lang.ja.fe.php | Japanse lokalisatie voor het frontend.
/vault/lang/lang.ja.php | Japanse lokalisatie.
/vault/lang/lang.ko.cli.php | Koreaanse lokalisatie voor CLI.
/vault/lang/lang.ko.fe.php | Koreaanse lokalisatie voor het frontend.
/vault/lang/lang.ko.php | Koreaanse lokalisatie.
/vault/lang/lang.nl.cli.php | Nederlandse lokalisatie voor CLI.
/vault/lang/lang.nl.fe.php | Nederlandse lokalisatie voor het frontend.
/vault/lang/lang.nl.php | Nederlandse lokalisatie.
/vault/lang/lang.no.cli.php | Noorse lokalisatie voor CLI.
/vault/lang/lang.no.fe.php | Noorse lokalisatie voor het frontend.
/vault/lang/lang.no.php | Noorse lokalisatie.
/vault/lang/lang.pt.cli.php | Portugees lokalisatie voor CLI.
/vault/lang/lang.pt.fe.php | Portugees lokalisatie voor het frontend.
/vault/lang/lang.pt.php | Portugees lokalisatie.
/vault/lang/lang.ru.cli.php | Russische lokalisatie voor CLI.
/vault/lang/lang.ru.fe.php | Russische lokalisatie voor het frontend.
/vault/lang/lang.ru.php | Russische lokalisatie.
/vault/lang/lang.sv.cli.php | Zweedse lokalisatie voor CLI.
/vault/lang/lang.sv.fe.php | Zweedse lokalisatie voor het frontend.
/vault/lang/lang.sv.php | Zweedse lokalisatie.
/vault/lang/lang.th.cli.php | Thaise lokalisatie voor CLI.
/vault/lang/lang.th.fe.php | Thaise lokalisatie voor het frontend.
/vault/lang/lang.th.php | Thaise lokalisatie.
/vault/lang/lang.tr.cli.php | Turks lokalisatie voor CLI.
/vault/lang/lang.tr.fe.php | Turks lokalisatie voor het frontend.
/vault/lang/lang.tr.php | Turks lokalisatie.
/vault/lang/lang.ur.cli.php | Urdu lokalisatie voor CLI.
/vault/lang/lang.ur.fe.php | Urdu lokalisatie voor het frontend.
/vault/lang/lang.ur.php | Urdu lokalisatie.
/vault/lang/lang.vi.cli.php | Vietnamees lokalisatie voor CLI.
/vault/lang/lang.vi.fe.php | Vietnamees lokalisatie voor het frontend.
/vault/lang/lang.vi.php | Vietnamees lokalisatie.
/vault/lang/lang.zh-tw.cli.php | Chinees (traditioneel) lokalisatie voor CLI.
/vault/lang/lang.zh-tw.fe.php | Chinees (traditioneel) lokalisatie voor het frontend.
/vault/lang/lang.zh-tw.php | Chinees (traditioneel) lokalisatie.
/vault/lang/lang.zh.cli.php | Chinees (vereenvoudigd) lokalisatie voor CLI.
/vault/lang/lang.zh.fe.php | Chinees (vereenvoudigd) lokalisatie voor het frontend.
/vault/lang/lang.zh.php | Chinees (vereenvoudigd) lokalisatie.
/vault/.htaccess | Een hypertext toegang bestand (in dit geval, om gevoelige bestanden die behoren tot het script te beschermen tegen toegang door niet-geautoriseerde bronnen).
/vault/.travis.php | Gebruikt door Travis CI voor het testen (niet vereist voor een goede werking van het script).
/vault/.travis.yml | Gebruikt door Travis CI voor het testen (niet vereist voor een goede werking van het script).
/vault/auxiliary.yaml | Bevat aanvullende regels. Niet inbegrepen in het pakket. Gegenereerd op de hulpregels pagina.
/vault/cache.dat | Cache data/gegevens.
/vault/cache.dat.safety | Gegenereerd als veiligheidsmechanisme indien nodig.
/vault/cidramblocklists.dat | Metadata bestand voor de optionele blokkaderlijsten van Macmathan; Gebruikt door de frontend updates pagina.
/vault/cli.php | CLI-handler.
/vault/components.dat | Componenten metadata bestand; Gebruikt door de frontend updates pagina.
/vault/config.ini.RenameMe | Configuratiebestand; Bevat alle configuratie-opties van CIDRAM, het vertellen wat te doen en hoe om te werken correct (hernoemen om te activeren).
/vault/config.php | Configuratie-handler.
/vault/config.yaml | Configuratie standaardwaarden bestand; Bevat standaardwaarden voor de CIDRAM configuratie.
/vault/frontend.php | Frontend-handler.
/vault/frontend_functions.php | Frontend-functies bestand.
/vault/functions.php | Functies bestand (essentieel).
/vault/hashes.dat | Bevat een lijst met geaccepteerde hashes (relevant zijn voor de reCAPTCHA functie; alleen gegenereerd als de reCAPTCHA functie is ingeschakeld).
/vault/ignore.dat | Genegeerd file (gebruikt om aan te geven welke signatuursecties CIDRAM moeten negeren).
/vault/ipbypass.dat | Bevat een lijst met IP rondwegen (relevant zijn voor de reCAPTCHA functie; alleen gegenereerd als de reCAPTCHA functie is ingeschakeld).
/vault/ipv4.dat | IPv4 signatures bestand (ongewenste cloud-diensten en niet-menselijke eindpunten).
/vault/ipv4_bogons.dat | IPv4 signatures bestand (bogon/martian CIDR's).
/vault/ipv4_custom.dat.RenameMe | IPv4 aangepaste signatures bestand (hernoemen om te activeren).
/vault/ipv4_isps.dat | IPv4 signatures bestand (gevaarlijk en spammy ISP's).
/vault/ipv4_other.dat | IPv4 signatures bestand (CIDR's voor proxies, VPN's, en diverse andere ongewenste diensten).
/vault/ipv6.dat | IPv6 signatures bestand (ongewenste cloud-diensten en niet-menselijke eindpunten).
/vault/ipv6_bogons.dat | IPv6 signatures bestand (bogon/martian CIDR's).
/vault/ipv6_custom.dat.RenameMe | IPv6 aangepaste signatures bestand (hernoemen om te activeren).
/vault/ipv6_isps.dat | IPv6 signatures bestand (gevaarlijk en spammy ISP's).
/vault/ipv6_other.dat | IPv6 signatures bestand (CIDR's voor proxies, VPN's, en diverse andere ongewenste diensten).
/vault/lang.php | Taal-handler.
/vault/modules.dat | Modules metadata bestand; Gebruikt door de frontend updates pagina.
/vault/outgen.php | Uitvoer generator.
/vault/php5.4.x.php | Polyfills voor PHP 5.4.X (nodig voor PHP 5.4.X achterwaartse compatibiliteit; veilig te verwijderen voor nieuwere PHP-versies).
/vault/recaptcha.php | reCAPTCHA module.
/vault/rules_as6939.php | Aangepaste regels bestand voor AS6939.
/vault/rules_softlayer.php | Aangepaste regels bestand voor Soft Layer.
/vault/rules_specific.php | Aangepaste regels bestand voor sommige specifiek CIDR's.
/vault/salt.dat | Zout bestand (gebruikt door sommige perifere functionaliteit; alleen gegenereerd indien nodig).
/vault/template_custom.html | Sjabloonbestand; Sjabloon voor HTML-uitvoer geproduceerd door de CIDRAM uitvoer generator.
/vault/template_default.html | Sjabloonbestand; Sjabloon voor HTML-uitvoer geproduceerd door de CIDRAM uitvoer generator.
/vault/themes.dat | Thema's metadata bestand; Gebruikt door de frontend updates pagina.
/vault/verification.yaml | Verificatiegegevens voor zoekmachines en sociale media.
/.gitattributes | Een GitHub project bestand (niet vereist voor een goede werking van het script).
/Changelog.txt | Een overzicht van wijzigingen in het script tussen verschillende versies (niet vereist voor een goede werking van het script).
/composer.json | Composer/Packagist informatie (niet vereist voor een goede werking van het script).
/CONTRIBUTING.md | Informatie over hoe bij te dragen aan het project.
/LICENSE.txt | Een kopie van de GNU/GPLv2 licentie (niet vereist voor een goede werking van het script).
/loader.php | Lader. Dit is wat u zou moeten worden inhaken in (essentieel)!
/README.md | Project beknopte informatie.
/web.config | Een ASP.NET-configuratiebestand (in dit geval, naar het bestandsmap "vault" te beschermen tegen toegang door niet-geautoriseerde bronnen indien het script is geïnstalleerd op een server op basis van ASP.NET technologieën).

---


### 6. <a name="SECTION6"></a>CONFIGURATIE-OPTIES
Het volgende is een lijst van variabelen die in de `config.ini` configuratiebestand van CIDRAM, samen met een beschrijving van hun doel en functie.

[general](#general-categorie) | [signatures](#signatures-categorie) | [recaptcha](#recaptcha-categorie) | [legal](#legal-categorie) | [template_data](#template_data-categorie)
:--|:--|:--|:--|:--
[logfile](#logfile)<br />[logfileApache](#logfileapache)<br />[logfileSerialized](#logfileserialized)<br />[truncate](#truncate)<br />[log_rotation_limit](#log_rotation_limit)<br />[log_rotation_action](#log_rotation_action)<br />[timezone](#timezone)<br />[timeOffset](#timeoffset)<br />[timeFormat](#timeformat)<br />[ipaddr](#ipaddr)<br />[forbid_on_block](#forbid_on_block)<br />[silent_mode](#silent_mode)<br />[lang](#lang)<br />[numbers](#numbers)<br />[emailaddr](#emailaddr)<br />[emailaddr_display_style](#emailaddr_display_style)<br />[disable_cli](#disable_cli)<br />[disable_frontend](#disable_frontend)<br />[max_login_attempts](#max_login_attempts)<br />[FrontEndLog](#frontendlog)<br />[ban_override](#ban_override)<br />[log_banned_ips](#log_banned_ips)<br />[default_dns](#default_dns)<br />[search_engine_verification](#search_engine_verification)<br />[social_media_verification](#social_media_verification)<br />[protect_frontend](#protect_frontend)<br />[disable_webfonts](#disable_webfonts)<br />[maintenance_mode](#maintenance_mode)<br />[default_algo](#default_algo)<br />[statistics](#statistics)<br />[force_hostname_lookup](#force_hostname_lookup)<br />[allow_gethostbyaddr_lookup](#allow_gethostbyaddr_lookup)<br />[hide_version](#hide_version)<br />[empty_fields](#empty_fields)<br /> | [ipv4](#ipv4)<br />[ipv6](#ipv6)<br />[block_cloud](#block_cloud)<br />[block_bogons](#block_bogons)<br />[block_generic](#block_generic)<br />[block_legal](#block_legal)<br />[block_malware](#block_malware)<br />[block_proxies](#block_proxies)<br />[block_spam](#block_spam)<br />[modules](#modules)<br />[default_tracktime](#default_tracktime)<br />[infraction_limit](#infraction_limit)<br />[track_mode](#track_mode)<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /> | [usemode](#usemode)<br />[lockip](#lockip)<br />[lockuser](#lockuser)<br />[sitekey](#sitekey)<br />[secret](#secret)<br />[expiry](#expiry)<br />[logfile](#logfile)<br />[signature_limit](#signature_limit)<br />[api](#api)<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /> | [pseudonymise_ip_addresses](#pseudonymise_ip_addresses)<br />[omit_ip](#omit_ip)<br />[omit_hostname](#omit_hostname)<br />[omit_ua](#omit_ua)<br />[privacy_policy](#privacy_policy)<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /> | [theme](#theme)<br />[Magnification](#magnification)<br />[css_url](#css_url)<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
[PHPMailer](#phpmailer-categorie) | [rate_limiting](#rate_limiting-categorie)
[EventLog](#eventlog)<br />[SkipAuthProcess](#skipauthprocess)<br />[Enable2FA](#enable2fa)<br />[Host](#host)<br />[Port](#port)<br />[SMTPSecure](#smtpsecure)<br />[SMTPAuth](#smtpauth)<br />[Username](#username)<br />[Password](#password)<br />[setFromAddress](#setfromaddress)<br />[setFromName](#setfromname)<br />[addReplyToAddress](#addreplytoaddress)<br />[addReplyToName](#addreplytoname)<br /> | [max_bandwidth](#max_bandwidth)<br />[max_requests](#max_requests)<br />[precision_ipv4](#precision_ipv4)<br />[precision_ipv6](#precision_ipv6)<br />[allowance_period](#allowance_period)<br /><br /><br /><br /><br /><br /><br /><br /><br />

#### "general" (Categorie)
Algemene configuratie voor CIDRAM.

##### "logfile"
- Mensen leesbare bestand om alle geblokkeerde toegang pogingen te loggen. Geef een bestandsnaam, of laat leeg om uit te schakelen.

##### "logfileApache"
- Apache-stijl bestand om alle geblokkeerde toegang pogingen te loggen. Geef een bestandsnaam, of laat leeg om uit te schakelen.

##### "logfileSerialized"
- Geserialiseerd bestand om alle geblokkeerde toegang pogingen te loggen. Geef een bestandsnaam, of laat leeg om uit te schakelen.

*Handige tip: Als u wil, u kunt datum/tijd informatie toevoegen om de namen van uw logbestanden door deze op in naam inclusief: `{yyyy}` voor volledige jaar, `{yy}` voor verkorte jaar, `{mm}` voor maand, `{dd}` voor dag, `{hh}` voor het uur.*

*Voorbeelden:*
- *`logfile='logfile.{yyyy}-{mm}-{dd}-{hh}.txt'`*
- *`logfileApache='access.{yyyy}-{mm}-{dd}-{hh}.txt'`*
- *`logfileSerialized='serial.{yyyy}-{mm}-{dd}-{hh}.txt'`*

##### "truncate"
- Trunceren logbestanden wanneer ze een bepaalde grootte bereiken? Waarde is de maximale grootte in B/KB/MB/GB/TB dat een logbestand kan groeien tot voordat het wordt getrunceerd. De standaardwaarde van 0KB schakelt truncatie uit (logbestanden kunnen onbepaald groeien). Notitie: Van toepassing op individuele logbestanden! De grootte van de logbestanden wordt niet collectief beschouwd.

##### "log_rotation_limit"
- Logrotatie beperkt het aantal logbestanden dat op elk moment zou moeten bestaan. Wanneer nieuwe logbestanden worden gemaakt en het totale aantal logbestanden de opgegeven limiet overschrijdt, wordt de opgegeven actie uitgevoerd. U kunt hier de gewenste limiet opgeven. Een waarde van 0 zal logrotatie uitschakelen.

##### "log_rotation_action"
- Logrotatie beperkt het aantal logbestanden dat op elk moment zou moeten bestaan. Wanneer nieuwe logbestanden worden gemaakt en het totale aantal logbestanden de opgegeven limiet overschrijdt, wordt de opgegeven actie uitgevoerd. U kunt hier de gewenste actie opgeven. Delete = Verwijder de oudste logbestanden, totdat de limiet niet langer wordt overschreden. Archive = Eerst archiveer en verwijder vervolgens de oudste logbestanden, totdat de limiet niet langer wordt overschreden.

*Technische verduidelijking: In deze context, de "oudste" betekent de minste recentelijk gewijzigd.*

##### "timezone"
- Dit wordt gebruikt om op te geven welke tijdzone CIDRAM moet gebruiken voor de datum/tijd-bewerkingen. Als je het niet nodig hebt, negeer het. Mogelijke waarden worden bepaald door PHP. Het is in het algemeen in plaats aanbevolen de tijdzone richtlijn in uw bestand `php.ini` aan te passen, maar somtijds (zoals bij het werken met beperkte shared hosting providers) dit is niet altijd mogelijk om te voldoen, en dus, Dit optie is hier voorzien.

##### "timeOffset"
- Als uw server tijd niet overeenkomt met uw lokale tijd, u kunt opgeven hier een offset om de datum/tijd informatie gegenereerd door CIDRAM aan te passen volgens uw behoeften. Het is in het algemeen in plaats aanbevolen de tijdzone richtlijn in uw bestand `php.ini` aan te passen, maar somtijds (zoals bij het werken met beperkte shared hosting providers) dit is niet altijd mogelijk om te voldoen, en dus, Dit optie is hier voorzien. Offset is in een minuten.
- Voorbeeld (een uur toe te voegen): `timeOffset=60`

##### "timeFormat"
- De datum notatie gebruikt door CIDRAM. Standaard = `{Day}, {dd} {Mon} {yyyy} {hh}:{ii}:{ss} {tz}`.

##### "ipaddr"
- Waar het IP-adres van het aansluiten verzoek te vinden? (Handig voor diensten zoals Cloudflare en dergelijke). Standaard = REMOTE_ADDR. WAARSCHUWING: Verander dit niet tenzij u weet wat u doet!

Aanbevolen waarden voor "ipaddr":

Waarde | Gebruik makend van
---|---
`HTTP_INCAP_CLIENT_IP` | Incapsula reverse proxy.
`HTTP_CF_CONNECTING_IP` | Cloudflare reverse proxy.
`CF-Connecting-IP` | Cloudflare reverse proxy (alternatief; als bovenstaande niet werkt).
`HTTP_X_FORWARDED_FOR` | Cloudbric reverse proxy.
`X-Forwarded-For` | [Squid reverse proxy](http://www.squid-cache.org/Doc/config/forwarded_for/).
*Definieerd door de server configuratie.* | [Nginx reverse proxy](https://www.nginx.com/resources/admin-guide/reverse-proxy/).
`REMOTE_ADDR` | Geen reverse proxy (standaardwaarde).

##### "forbid_on_block"
- Welk HTTP-statusbericht moet CIDRAM verzenden bij het blokkeren van verzoeken?

Momenteel ondersteunde waarden:

Status code | Status bericht | Beschrijving
---|---|---
`200` | `200 OK` | Standaardwaarde. Minst robuust, maar meest gebruiksvriendelijk.
`403` | `403 Forbidden` | Robuuster, maar minder gebruikersvriendelijk.
`410` | `410 Gone` | Kan problemen veroorzaken bij pogingen om valse positieven op te lossen, omdat sommige browsers dit statusbericht in de cache opslaan en geen volgende verzoeken verzenden, zelfs niet na het deblokkeren van gebruikers. Kan echter nuttiger zijn dan andere opties om aanvragen van bepaalde, zeer specifieke typen bots te verminderen.
`418` | `418 I'm a teapot` | Verwijst eigenlijk naar de grap van April Fools [[RFC 2324](https://tools.ietf.org/html/rfc2324#section-6.5.14)] en het is onwaarschijnlijk dat de klant het begrijpt. Voorzien voor amusement en gemak, maar over het algemeen niet aanbevolen.
`451` | `Unavailable For Legal Reasons` | Geschikt voor situaties waarin verzoeken voornamelijk om juridische redenen worden geblokkeerd. Niet aanbevolen in andere contexten.
`503` | `Service Unavailable` | Meest robuust, maar minst gebruiksvriendelijk.

##### "silent_mode"
- Moet CIDRAM stilletjes redirect geblokkeerd toegang pogingen in plaats van het weergeven van de "Toegang Geweigerd" pagina? Als ja, geef de locatie te redirect geblokkeerd toegang pogingen. Als nee, verlaat deze variabele leeg.

##### "lang"
- Geef de standaardtaal voor CIDRAM.

##### "numbers"
- Specificeert hoe u nummers wilt weergeven.

Momenteel ondersteunde waarden:

Waarde | Produceert | Beschrijving
---|---|---
`NoSep-1` | `1234567.89`
`NoSep-2` | `1234567,89`
`Latin-1` | `1,234,567.89` | Standaardwaarde.
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

*Notitie: Deze waarden zijn nergens gestandaardiseerd, en zullen waarschijnlijk niet relevant zijn buiten het pakket. Ook kunnen ondersteunde waarden in de toekomst veranderen.*

##### "emailaddr"
- Indien u wenst, u kunt een e-mailadres op hier te geven te geven aan de gebruikers als ze geblokkeerd, voor hen te gebruiken als aanspreekpunt voor steun en/of assistentie in het geval dat ze worden onrechte geblokkeerd. WAARSCHUWING: Elke e-mailadres u leveren hier zal zeker worden overgenomen met spambots en schrapers in de loop van zijn wezen die hier gebruikt, en dus, het wordt ten zeerste aanbevolen als u ervoor kiest om een e-mailadres hier te leveren, dat u ervoor zorgen dat het e-mailadres dat u hier leveren is een wegwerp-adres en/of een adres dat u niet de zorg over wordt gespamd (met andere woorden, u waarschijnlijk niet wilt om uw primaire persoonlijk of primaire zakelijke e-mailadressen te gebruik).

##### "emailaddr_display_style"
- Hoe zou u het e-mailadres voor gebruikers willen aanbieden? "default" = Klikbare link. "noclick" = Niet-klikbare tekst.

##### "disable_cli"
- Uitschakelen CLI-modus? CLI-modus is standaard ingeschakeld, maar kunt somtijds interfereren met bepaalde testtools (zoals PHPUnit bijvoorbeeld) en andere CLI-gebaseerde applicaties. Als u niet hoeft te uitschakelen CLI-modus, u moeten om dit richtlijn te negeren. False = Inschakelen CLI-modus [Standaard]; True = Uitschakelen CLI-modus.

##### "disable_frontend"
- Uitschakelen frontend toegang? Frontend toegang kan CIDRAM beter beheersbaar te maken, maar kan ook een potentieel gevaar voor de veiligheid zijn. Het is aan te raden om CIDRAM te beheren via het backend wanneer mogelijk, maar frontend toegang is hier voorzien voor wanneer het niet mogelijk is. Hebben het uitgeschakeld tenzij u het nodig hebt. False = Inschakelen frontend toegang; True = Uitschakelen frontend toegang [Standaard].

##### "max_login_attempts"
- Maximum aantal inlogpogingen (frontend). Standaard = 5.

##### "FrontEndLog"
- Bestand om de frontend login pogingen te loggen. Geef een bestandsnaam, of laat leeg om uit te schakelen.

##### "ban_override"
- Overrijden "forbid_on_block" wanneer "infraction_limit" wordt overschreden? Wanneer het overrijdt: Geblokkeerde verzoeken retourneert een lege pagina (template bestanden worden niet gebruikt). 200 = Niet overrijden [Standaard]. Andere waarden zijn hetzelfde als de beschikbare waarden voor "forbid_on_block".

##### "log_banned_ips"
- Omvatten geblokkeerde verzoeken van verboden IP-adressen in de logbestanden? True = Ja [Standaard]; False = Nee.

##### "default_dns"
- Een door komma's gescheiden lijst met DNS-servers te gebruiken voor de hostnaam lookups. Standaard = "8.8.8.8,8.8.4.4" (Google DNS). WAARSCHUWING: Verander dit niet tenzij u weet wat u doet!

*Zie ook: [Wat kan ik gebruiken voor "default_dns"?](#WHAT_CAN_I_USE_FOR_DEFAULT_DNS)*

##### "search_engine_verification"
- Poging om aanvragen van zoekmachines te bevestigen? Verificatie van zoekmachines zorgt ervoor dat ze niet zullen worden verboden als gevolg van het overschrijden van de overtreding limiet (verbod op zoekmachines van uw website zal meestal een negatief effect hebben op uw zoekmachine ranking, SEO, enz). Wanneer geverifieerd, zoekmachines kunnen worden geblokkeerd als per normaal, maar zal niet worden verboden. Wanneer niet geverifieerd, het is mogelijk dat zij worden verboden ten gevolge van het overschrijden van de overtreding limiet. Bovendien, het verifiëren van zoekmachines biedt bescherming tegen nep-zoekmachine aanvragen en tegen de mogelijk schadelijke entiteiten vermomd als zoekmachines (dergelijke aanvragen zal worden geblokkeerd wanneer het verifiëren van zoekmachines is ingeschakeld). True = Inschakelen het verifiëren van zoekmachines [Standaard]; False = Uitschakelen het verifiëren van zoekmachines.

Momenteel ondersteund:
- __[Google](https://support.google.com/webmasters/answer/80553?hl=en)__
- __[Bing](https://blogs.bing.com/webmaster/2012/08/31/how-to-verify-that-bingbot-is-bingbot)__
- __Yahoo__
- __[Baidu (百度)](https://help.baidu.com/question?prod_en=master&class=Baiduspider)__
- __Sogou (搜狗)__
- __Youdao (有道)__
- __[Applebot](https://discussions.apple.com/thread/7090135)__
- __[Yandex (Яндекс)](https://yandex.com/support/webmaster/robot-workings/check-yandex-robots.xml)__
- __[DuckDuckGo](https://duckduckgo.com/duckduckbot)__
- __[SeznamBot](https://napoveda.seznam.cz/en/full-text-search/seznambot-crawler/)__

Niet compatibel (veroorzaakt conflicten):
- __[Mix.com](https://github.com/CIDRAM/CIDRAM/issues/80)__

##### "social_media_verification"
- Poging om aanvragen voor sociale media te verifiëren? Verificatie van sociale media biedt bescherming tegen nep-aanvragen voor sociale media (dergelijke aanvragen worden geblokkeerd). True = Inschakelen verificatie van sociale media [Standaard]; False = Uitschakelen verificatie van sociale media.

Momenteel ondersteund:
- __[Pinterest](https://help.pinterest.com/en/articles/about-pinterest-crawler-0)__
- __Embedly__
- __[Grapeshot](https://www.grapeshot.com/crawler/)__
- __Twitterbot__

##### "protect_frontend"
- Geeft aan of de bescherming die gewoonlijk door CIDRAM is voorzien moet worden toegepast op de frontend. True = Ja [Standaard]; False = Nee.

##### "disable_webfonts"
- Uitschakelen webfonts? True = Ja [Standaard]; False = Nee.

##### "maintenance_mode"
- Inschakelen de onderhoudsmodus? True = Ja; False = Nee [Standaard]. Schakelt alles anders dan het frontend uit. Soms nuttig bij het bijwerken van uw CMS, frameworks, enz.

##### "default_algo"
- Definieert welk algoritme u wilt gebruiken voor alle toekomstige wachtwoorden en sessies. Opties: PASSWORD_DEFAULT (standaard), PASSWORD_BCRYPT, PASSWORD_ARGON2I (vereist PHP >= 7.2.0).

##### "statistics"
- Track CIDRAM gebruiksstatistieken? True = Ja; False = Nee [Standaard].

##### "force_hostname_lookup"
- Hostname-opzoekingen afdwingen? True = Ja; False = Nee [Standaard]. Hostname-opzoekingen worden normaal uitgevoerd op basis van noodzaak, maar kan voor alle verzoeken worden gedwongen. Dit kan nuttig zijn als een middel om meer gedetailleerde informatie in de logbestanden te verstrekken, maar kan ook een licht negatief effect hebben op de prestaties.

##### "allow_gethostbyaddr_lookup"
- Zoeken op gethostbyaddr toestaan als UDP niet beschikbaar is? True = Ja [Standaard]; False = Nee.
- *Notitie: IPv6-lookup werkt mogelijk niet correct op sommige 32-bits systemen.*

##### "hide_version"
- Versleutelingsinformatie uit logs en pagina-uitvoer verbergen? True = Ja; False = Nee [Standaard].

##### "empty_fields"
- Hoe moet CIDRAM lege velden afhandelen bij het loggen en weergeven van informatie over blokgebeurtenissen? "include" = Voeg lege velden in. "omit" = Sluit lege velden uit [standaard].

#### "signatures" (Categorie)
Configuratie voor signatures.

##### "ipv4"
- Een lijst van de IPv4 signatuurbestanden dat CIDRAM moet proberen om te gebruiken, afgebakend door komma's. U kunt items hier toevoegen Als u wilt meer IPv4 signature files in CIDRAM bevatten.

##### "ipv6"
- Een lijst van de IPv6 signatuurbestanden dat CIDRAM moet proberen om te gebruiken, afgebakend door komma's. U kunt items hier toevoegen Als u wilt meer IPv6 signature files in CIDRAM bevatten.

##### "block_cloud"
- Blokkeren CIDR's geïdentificeerd als behorend tot webhosting/cloud-diensten? Als u een api te bedienen vanaf uw website of als u verwacht dat andere websites aan te sluiten op uw website, dit richtlijn moet worden ingesteld op false. Als u niet, dan, dit richtlijn moet worden ingesteld op true.

##### "block_bogons"
- Blokkeren bogon/martian CIDR's? Als u verwacht aansluitingen om uw website vanuit uw lokale netwerk, vanuit localhost, of vanuit uw LAN, dit richtlijn moet worden ingesteld op false. Als u niet verwacht deze aansluitingen, dit richtlijn moet worden ingesteld op true.

##### "block_generic"
- Blokkeren CIDR's algemeen aanbevolen voor blacklisting? Dit omvat alle signatures die niet zijn gemarkeerd als onderdeel van elke van de andere, meer specifieke signature categorieën.

##### "block_legal"
- Blokkeren CIDR's als reactie op wettelijke verplichtingen? Dit richtlijn zou normaal gesproken geen effect moeten hebben, omdat CIDRAM als standaard geen CIDR's met "wettelijke verplichtingen" associeert, maar het bestaat niettemin als een extra beheersmaatregel ten behoeve van eventuele aangepaste signatuurbestanden of modules die mogelijk bestaan om wettelijke redenen.

##### "block_malware"
- Blokkeren IP's die zijn gekoppeld aan malware? Dit omvat C&C-servers, geïnfecteerde machines, machines die betrokken zijn bij de distributie van malware, enz.

##### "block_proxies"
- Blokkeren CIDR's geïdentificeerd als behorend tot proxy-services of VPN's? Als u vereisen dat gebruikers kan toegang tot uw website van proxy-services en VPN's, dit richtlijn moet worden ingesteld op false. Anders, als u geen proxy-services of VPN's nodig, dit richtlijn moet worden ingesteld op true als een middel ter verbetering van de beveiliging.

##### "block_spam"
- Blokkeren CIDR's geïdentificeerd als zijnde hoog risico voor spam? Tenzij u problemen ondervindt wanneer u dit doet, in algemeen, dit moet altijd worden ingesteld op true.

##### "modules"
- Een lijst van module bestanden te laden na verwerking van de IPv4/IPv6 signatures, afgebakend door komma's.

##### "default_tracktime"
- Hoeveel seconden om IP's verboden door modules te volgen. Standaard = 604800 (1 week).

##### "infraction_limit"
- Maximum aantal overtredingen een IP mag worden gesteld voordat hij wordt verboden door IP-tracking. Standaard = 10.

##### "track_mode"
- Wanneer moet overtredingen worden gerekend? False = Wanneer IP's geblokkeerd door modules worden. True = Wanneer IP's om welke reden geblokkeerd worden.

#### "recaptcha" (Categorie)
Optioneel, u kan uw gebruikers te voorzien van een manier om de "Toegang Geweigerd" pagina te omzeilen, door middel van het invullen van een reCAPTCHA instantie, als u wilt om dit te doen. Dit kan helpen om een aantal van de risico's die samenhangen met valse positieven te beperken, in die situaties waar we niet helemaal zeker of er een verzoek is voortgekomen uit een machine of een mens.

Vanwege de risico's die samenhangen met het verstrekken van een manier voor eindgebruikers om de "Toegang Geweigerd" pagina te omzeilen, algemeen, ik zou adviseren tegen het inschakelen van deze functie tenzij u voelt het om nodig om dit te doen. Situaties waarin het nodig zou zijn: Als uw website heeft klanten/gebruikers die moeten toegang hebben tot uw website, en als dit is iets dat niet kan worden gecompromitteerd, maar als deze klanten/gebruikers deze verbinding maakt vanuit een vijandig netwerk dat mogelijk ook zou kunnen dragen ongewenste verkeer, en het blokkeren van deze ongewenste verkeer is ook iets dat niet kan worden gecompromitteerd, in deze bijzondere no-win situaties, de functie reCAPTCHA kan van pas komen als een middel van het toestaan van de wenselijke klanten/gebruikers, terwijl het vermijden van het het ongewenste verkeer vanaf hetzelfde netwerk. Dat gezegd hebbende hoewel, gezien het feit dat de bestemming van een CAPTCHA is om onderscheid te maken tussen mensen en niet-mensen, de functie reCAPTCHA zou alleen helpen in deze no-win situaties als zou veronderstellen dat deze ongewenste verkeer is niet-humaan (b.v., spambots, schrapers, hack gereedschappen, geautomatiseerde verkeer), in tegenstelling tot ongewenst menselijk verkeer (zoals menselijke spammers, hackers, c.s.).

Om een "site key" en een "secret key" te verkrijgen (vereist voor het gebruik van reCAPTCHA), ga naar: [https://developers.google.com/recaptcha/](https://developers.google.com/recaptcha/)

##### "usemode"
- Bepaalt hoe CIDRAM reCAPTCHA moet gebruiken.
- 0 = reCAPTCHA is volledig uitgeschakeld (standaard).
- 1 = reCAPTCHA is ingeschakeld voor alle signatures.
- 2 = reCAPTCHA is ingeschakeld alleen voor signatures die behoren tot secties speciaal gemarkeerde binnen de signatuurbestanden.
- (Een andere waarde wordt op dezelfde wijze als 0 behandeld).

##### "lockip"
- Geeft aan of hashes moeten worden vergrendeld om specifieke IP's. False = Cookies en hashes KAN worden gebruikt voor meerdere IP-adressen (standaard). True = Cookies en hashes kan NIET worden gebruikt voor meerdere IP-adressen (cookies/hashes worden vergrendeld om IP's).
- Notitie: "lockip" waarde wordt genegeerd als "lockuser" is false, te wijten aan dat het mechanisme voor het onthouden van "gebruikers" verschilt afhankelijk van deze waarde.

##### "lockuser"
- Geeft aan of het succesvol afronden van een reCAPTCHA instantie moet worden vergrendeld om specifieke gebruikers. False = Succesvolle afronding van een reCAPTCHA instantie zal het verlenen van de toegang tot alle verzoeken die afkomstig zijn van hetzelfde IP als die gebruikt wordt door de gebruiker het invullen van de reCAPTCHA instantie; Cookies en hashes worden niet gebruikt; In plaats daarvan zal een IP whitelist worden gebruikt. True = Succesvolle afronding van een reCAPTCHA instantie zal alleen toegang tot de gebruiker te verlenen het invullen van de reCAPTCHA instantie; Cookies en hashes worden gebruikt om de gebruiker te herinneren; Een IP whitelist wordt niet gebruikt (standaard).

##### "sitekey"
- Deze waarde moet overeenkomen met de "site key" voor uw reCAPTCHA, die kan worden gevonden binnen de reCAPTCHA dashboard.

##### "secret"
- Deze waarde moet overeenkomen met de "secret key" voor uw reCAPTCHA, die kan worden gevonden binnen de reCAPTCHA dashboard.

##### "expiry"
- Wanneer "lockuser" is true (standaard), om te onthouden wanneer een gebruiker met succes heeft doorstaan een reCAPTCHA instantie, voor pagina verzoeken, CIDRAM genereert een standaard cookie bevat een hash die overeenkomt met een intern register met dezelfde hash; Toekomstige pagina verzoeken zullen deze overeenkomstige hashes gebruiken om te verifiëren dat een gebruiker eerder al heeft gepasseerd een reCAPTCHA instantie. Wanneer "lockuser" is false, een IP whitelist wordt gebruikt om te bepalen of verzoeken moeten worden toegestaan van het IP-adres van inkomende verzoeken; Inzendingen worden toegevoegd aan deze whitelist wanneer de reCAPTCHA instantie met succes heeft doorstaan. Hoeveel uren moeten deze cookies, hashes en whitelist inzendingen blijven geldig? Standaard = 720 (1 maand).

##### "logfile"
- Log alle reCAPTCHA pogingen? Zo ja, geef de naam te gebruiken voor het logbestand. Zo nee, laat u deze variabele leeg.

*Handige tip: Als u wil, u kunt datum/tijd informatie toevoegen om de namen van uw logbestanden door deze op in naam inclusief: `{yyyy}` voor volledige jaar, `{yy}` voor verkorte jaar, `{mm}` voor maand, `{dd}` voor dag, `{hh}` voor het uur.*

*Voorbeelden:*
- *`logfile='recaptcha.{yyyy}-{mm}-{dd}-{hh}.txt'`*

##### "signature_limit"
- Maximale aantal signatures dat kan worden getriggerd wanneer een reCAPTCHA-instantie wordt aangeboden. Standaard = 1. Als dit aantal wordt overschreden voor een bepaald verzoek, wordt er geen reCAPTCHA-instantie aangeboden.

##### "api"
- Welke API gebruiken? V2 of invisible?

*Opmerking voor gebruikers in de Europese Unie: Wanneer CIDRAM is geconfigureerd om cookies te gebruiken (b.v., wanneer "lockuser" true/waar is), een cookie-waarschuwing wordt prominent op de pagina weergegeven volgens de vereisten van de [EU-cookiewetgeving](https://www.cookielaw.org/the-cookie-law/). Maar, bij het gebruik van de invisible API probeert CIDRAM automatisch de reCAPTCHA voor de gebruiker te voltooien, en wanneer dit lukt, kan dit ertoe leiden dat de pagina opnieuw wordt geladen en een cookie wordt gemaakt zonder dat de gebruiker voldoende tijd krijgt om de cookie-waarschuwing daadwerkelijk te zien. Als dit een juridisch risico voor u oplevert, is het misschien beter om de V2 API te gebruiken in plaats van de invisible API (de V2 API is niet geautomatiseerd en vereist dat de gebruiker de reCAPTCHA-uitdaging zelf voltooit, waardoor de mogelijkheid wordt geboden om de cookie-waarschuwing te zien).*

#### "legal" (Category)
Configuratie met betrekking tot wettelijke vereisten.

*Voor meer informatie over wettelijke vereisten en hoe dit uw configuratie-eisen kan beïnvloeden, zie het sectie "[LEGALE INFORMATIE](#SECTION11)" van de documentatie.*

##### "pseudonymise_ip_addresses"
- Pseudonimiseren de IP-adressen bij het schrijven van logbestanden? True = Ja [Standaard]; False = Nee.

##### "omit_ip"
- IP-adressen uit logbestanden weglaten? True = Ja; False = Nee [Standaard]. Opmerking: "pseudonymise_ip_addresses" wordt overbodig zijn wanneer "omit_ip" "true" is.

##### "omit_hostname"
- Hostnamen uit logbestanden weglaten? True = Ja; False = Nee [Standaard].

##### "omit_ua"
- Gebruikersagenten uit logbestanden weglaten? True = Ja; False = Nee [Standaard].

##### "privacy_policy"
- Het adres van een relevant privacybeleid dat moet worden weergegeven in de voettekst van eventuele gegenereerde pagina's. Geef een URL, of laat leeg om uit te schakelen.

#### "template_data" (Categorie)
Richtlijnen/Variabelen voor sjablonen en thema's.

Betreft de HTML-uitvoer gebruikt om de "Toegang Geweigerd" pagina te genereren. Als u gebruik aangepaste thema's voor CIDRAM, HTML-uitvoer is afkomstig van de `template_custom.html` bestand, en alternatief, HTML-uitvoer is afkomstig van de `template.html` bestand. Variabelen geschreven om dit sectie van het configuratiebestand worden geïnterpreteerd aan de HTML-uitvoer door middel van het vervangen van variabelennamen omringd door accolades gevonden binnen de HTML-uitvoer met de bijbehorende variabele gegevens. Bijvoorbeeld, waar `foo="bar"`, elk geval van `<p>{foo}</p>` gevonden binnen de HTML-uitvoer `<p>bar</p>` zal worden.

##### "theme"
- Standaard thema om te gebruiken voor CIDRAM.

##### "Magnification"
- Lettergrootte vergroting. Standaard = 1.

##### "css_url"
- De sjabloonbestand voor aangepaste thema's maakt gebruik van externe CSS-eigenschappen, terwijl de sjabloonbestand voor het standaardthema maakt gebruik van interne CSS-eigenschappen. Om CIDRAM instrueren om de sjabloonbestand voor aangepaste thema's te gebruiken, geef het openbare HTTP-adres van uw aangepaste thema's CSS-bestanden via de `css_url` variabele. Als u dit variabele leeg laat, CIDRAM zal de sjabloonbestand voor de standaardthema te gebruiken.

#### "PHPMailer" (Categorie)
PHPMailer-configuratie.

Op dit moment gebruikt CIDRAM alleen PHPMailer voor front-end two-factor authenticatie. Als u de front-end niet gebruikt, of als u geen twee-factorenauthenticatie gebruikt voor de front-end, kunt u deze richtlijnen negeren.

##### "EventLog"
- Een bestand voor het loggen van alle evenementen met betrekking tot PHPMailer. Geef een bestandsnaam, of laat leeg om uit te schakelen.

##### "SkipAuthProcess"
- Wanneer `true`, geeft PHPMailer opdracht om het verificatieproces over te slaan dat normaal optreedt bij het verzenden van e-mail via SMTP. Dit moet worden vermeden, omdat bij het overslaan van dit verificatieproces uitgaande e-mail aan MITM-aanvallen kan worden blootgesteld, maar kan nodig zijn in gevallen waarin dit verificatieproces verhindert dat PHPMailer verbinding maakt met een SMTP-server.

##### "Enable2FA"
- Deze richtlijn bepaalt of 2FA wordt gebruikt voor frontend-accounts.

##### "Host"
- De SMTP-host dat moet worden gebruikt voor uitgaande e-mail.

##### "Port"
- Het poortnummer dat moet worden gebruikt voor uitgaande e-mail. Standaard = 587.

##### "SMTPSecure"
- Het protocol voor het verzenden van e-mail via SMTP (TLS of SSL).

##### "SMTPAuth"
- Deze richtlijn bepaalt of SMTP-sessies moeten worden geverifieerd (moet meestal alleen worden gelaten).

##### "Username"
- De gebruikersnaam voor het verzenden van e-mail via SMTP.

##### "Password"
- Het wachtwoord voor het verzenden van e-mail via SMTP.

##### "setFromAddress"
- Het afzenderadres voor het verzenden van e-mail via SMTP.

##### "setFromName"
- De naam van de afzender voor het verzenden van e-mail via SMTP.

##### "addReplyToAddress"
- Het antwoordadres voor het verzenden van e-mail via SMTP.

##### "addReplyToName"
- De antwoordnaam voor het verzenden van e-mail via SMTP.

#### "rate_limiting" (Categorie)
Optionele configuratie-instructies voor tarieflimiet.

Deze functie is geïmplementeerd in CIDRAM omdat deze door voldoende gebruikers is aangevraagd om de implementatie te rechtvaardigen. Omdat het echter enigszins buiten de reikwijdte van het oorspronkelijk bedoelde doel voor CIDRAM, zal het waarschijnlijk niet nodig zijn voor de meeste gebruikers. Als u specifiek CIDRAM nodig hebt om de tarieflimiet voor uw website te handelen, kan deze functie nuttig voor u zijn. Er zijn echter enkele belangrijke zaken die u moet overwegen:
- Deze functie werkt, net als alle andere CIDRAM-functies, alleen voor pagina's die worden beschermd door CIDRAM. Daarom kunnen website assets die niet specifiek via CIDRAM worden gerouteerd, niet worden beperkt door CIDRAM.
- Vergeet niet dat CIDRAM het cache en andere gegevens rechtstreeks naar de schijf schrijft (d.w.z. slaat zijn gegevens op in bestanden), en gebruikt geen extern databasesysteem zoals MySQL, PostgreSQL, Access, of iets dergelijks. Dit betekent dus dat het voor het volgen van het gebruik voor tarieflimiet effectief naar schijf moet schrijven voor elk mogelijk potentieel beperkt verzoek. Dit zou kunnen bijdragen aan een lagere levensduur van de schijflevensduur op de lange termijn en wordt niet ideaal aanbevolen. Idealiter zou een tool die wordt gebruikt voor tarieflimiet een databasesysteem kunnen gebruiken dat bedoeld is voor veelvuldige, kleine lees/schrijf-bewerkingen, of kon informatie blijven behouden in alle verzoeken, zonder de noodzaak om gegevens tussen aanvragen naar schijf te schrijven (b.v., geschreven als een onafhankelijke servermodule, in plaats van een PHP-pakket).
- Als u een servermodule, cPanel, of een andere netwerktool kunt gebruiken om tarieflimiet af te dwingen, is het beter om die te gebruiken voor tarieflimiet in plaats van CIDRAM.
- Als een bepaalde gebruiker graag toegang blijft houden tot uw website nadat hij tarieflimiet is, in de meeste gevallen is het voor hen heel gemakkelijk om tarieflimiet te omzeilen (b.v., als ze hun IP-adres wijzigen, of als ze een proxy of VPN gebruiken, en ervan uitgaande dat u CIDRAM hebt geconfigureerd om geen proxy's en VPN's te blokkeren, of dat CIDRAM niet op de hoogte is van de proxy of VPN die ze gebruiken).
- Tarieflimiet kan erg vervelend zijn voor echte, eindgebruikers. Het kan nodig zijn als uw beschikbare bandbreedte zeer beperkt is, en als u ontdekt dat er bepaalde specifieke verkeersbronnen zijn, die niet al op andere wijze zijn geblokkeerd, die het grootste deel van uw beschikbare bandbreedte verbruiken. Als dit echter niet nodig is, moet dit waarschijnlijk worden vermeden.
- U loopt af en toe het risico legitieme gebruikers of uzelf te blokkeren.

Als u vindt dat u CIDRAM niet nodig hebt om tarieflimiet voor uw website af te dwingen, houd dan de onderstaande richtlijnen als hun standaardwaarden. Anders kunt u hun waarden aanpassen aan uw behoeften.

##### "max_bandwidth"
- De maximale hoeveelheid toegestane bandbreedte binnen de toeslagperiode voordat tarieflimiet voor toekomstige verzoeken wordt ingeschakeld. Een waarde van 0 schakelt dit type tarieflimiet uit. Standaard = 0KB.

##### "max_requests"
- Het maximale aantal toegestane verzoeken binnen de toeslagperiode voordat de tarieflimiet voor toekomstige verzoeken wordt ingeschakeld. Een waarde van 0 schakelt dit type tarieflimiet uit. Standaard = 0.

##### "precision_ipv4"
- De precisie om te gebruiken bij het monitoren op het gebruik van IPv4. Waarde weerspiegelt de CIDR-blokgrootte. Stel in op 32 voor de beste precisie. Standaard = 32.

##### "precision_ipv6"
- De precisie om te gebruiken bij het monitoren op het gebruik van IPv6. Waarde weerspiegelt de CIDR-blokgrootte. Stel in op 128 voor de beste precisie. Standaard = 128.

##### "allowance_period"
- Het aantal uren om het gebruik te controleren. Standaard = 0.

---


### 7. <a name="SECTION7"></a>SIGNATURE FORMAAT

*Zie ook:*
- *[Wat is een "signature"?](#WHAT_IS_A_SIGNATURE)*

#### 7.0 BASICS (VOOR SIGNATUURBESTANDEN)

Alle IPv4 signatures volgt het formaat: `xxx.xxx.xxx.xxx/yy [Function] [Param]`.
- `xxx.xxx.xxx.xxx` vertegenwoordigt het begin van het CIDR blok (de octetten van de eerste IP-adres in het blok).
- `yy` vertegenwoordigt het CIDR blokgrootte [1-32].
- `[Function]` instrueert het script wat te doen met de signature (hoe de signature moet worden beschouwd).
- `[Param]` vertegenwoordigt alle aanvullende informatie dat kan worden verlangd door `[Function]`.

Alle IPv6 signatures volgt het formaat: `xxxx:xxxx:xxxx:xxxx::xxxx/yy [Function] [Param]`.
- `xxxx:xxxx:xxxx:xxxx::xxxx` vertegenwoordigt het begin van het CIDR blok (de octetten van de eerste IP-adres in het blok). Compleet notatie en verkorte notatie zijn beide aanvaardbaar (en ieder moet volg de juiste en relevante normen van IPv6-notatie, maar met één uitzondering: een IPv6-adres kan nooit beginnen met een afkorting wanneer het wordt gebruikt in een signature voor dit script, vanwege de manier waarop CIDR's door het script zijn gereconstrueerd; Bijvoorbeeld, `::1/128` moet worden uitgedrukt, bij gebruik in een signature, als `0::1/128`, en `::0/128` uitgedrukt als `0::/128`).
- `yy` vertegenwoordigt het CIDR blokgrootte [1-128].
- `[Function]` instrueert het script wat te doen met de signature (hoe de signature moet worden beschouwd).
- `[Param]` vertegenwoordigt alle aanvullende informatie dat kan worden verlangd door `[Function]`.

De signatuurbestanden voor CIDRAM MOET gebruiken Unix-stijl regeleinden (`%0A`, or `\n`)! Andere soorten/stijlen van regeleinden (b.v., Windows `%0D%0A` of `\r\n` regeleinden, Mac `%0D` of `\r` regeleinden, enz) KAN worden gebruikt, maar zijn NIET voorkeur. Non-Unix-stijl regeleinden wordt genormaliseerd naar Unix-stijl regeleinden door het script.

Nauwkeurig en correct CIDR-notatie is vereist, anders zal het script NIET de signatures herkennen. Tevens, alle CIDR signatures van dit script MOET beginnen met een IP-adres waarvan het IP-nummer kan gelijkmatig in het blok divisie vertegenwoordigd door haar CIDR blokgrootte verdelen (b.v., als u wilde alle IP-adressen van `10.128.0.0` naar `11.127.255.255` te blokkeren, `10.128.0.0/8` zou door het script NIET worden herkend, maar `10.128.0.0/9` en `11.0.0.0/9` in combinatie, ZOU door het script worden herkend).

Alles wat in de signatuurbestanden niet herkend als een signature noch als signature-gerelateerde syntaxis door het script worden GENEGEERD, daarom dit betekent dat om veilig alle niet-signature gegevens die u wilt in de signatuurbestanden u kunnen zetten zonder verbreking van de signatuurbestanden of de script. Reacties zijn in de signatuurbestanden aanvaardbare, en geen speciale opmaak of formaat is vereist voor hen. Shell-stijl hashing voor commentaar heeft de voorkeur, maar is niet afgedwongen; Functioneel, het maakt geen verschil voor het script ongeacht of u kiest voor Shell-stijl hashing om commentaar te gebruiken, maar gebruik van Shell-stijl hashing helpt IDE's en platte tekst editors om correct te markeren de verschillende delen van de signatuurbestanden (en dus, Shell-stijl hashing kan helpen als een visueel hulpmiddel tijdens het bewerken).

Mogelijke waarden van `[Function]` zijn als volgt:
- Run
- Whitelist
- Greylist
- Deny

Als "Run" wordt gebruikt, als de signature wordt getriggerd, het script zal proberen (gebruiken een `require_once` statement) om een externe PHP-script uit te voeren, gespecificeerd door de `[Param]` waarde (de werkmap moet worden de "/vault/" map van het script).

Voorbeeld: `127.0.0.0/8 Run example.php`

Dit kan handig zijn als u wilt, voor enige specifieke IP's en/of CIDR's, om specifieke PHP-code uit te voeren.

Als "Whitelist" wordt gebruikt, als de signature wordt getriggerd, het script zal alle detecties resetten (als er is al enige detecties) en breek de testfunctie. `[Param]` worden genegeerd. Deze functie werkt als een whitelist, om te voorkomen dat bepaalde IP-adressen en/of CIDR's van wordt gedetecteerd.

Voorbeeld: `127.0.0.1/32 Whitelist`

Als "Greylist" wordt gebruikt, als de signature wordt getriggerd, het script zal alle detecties resetten (als er is al enige detecties) en doorgaan naar de volgende signatuurbestand te gaan met verwerken. `[Param]` worden genegeerd.

Voorbeeld: `127.0.0.1/32 Greylist`

Als "Deny" wordt gebruikt, als de signature wordt getriggerd, veronderstelling dat er worden geen whitelist-signature getriggerd voor het opgegeven IP-adres en/of opgegeven CIDR, toegang tot de beveiligde pagina wordt ontzegd. "Deny" is wat u wilt gebruiken om een IP-adres en/of CIDR range te daadwerkelijk blokkeren. Wanneer enige signatures zijn getriggerd er dat gebruik "Deny", de "Toegang Geweigerd" pagina van het script zal worden gegenereerd en het verzoek naar de beveiligde pagina wordt gedood.

De `[Param]` waarde geaccepteerd door "Deny" zal worden parsed aan de "Toegang Geweigerd" pagina-uitgang, geleverd aan de klant/gebruiker als de genoemde reden voor hun toegang tot de gevraagde pagina worden geweigerd. Het kan een korte en eenvoudige zin zijn, uit te leggen waarom u hebt gekozen om ze te blokkeren (iets moeten volstaan, zelfs een simpele "Ik wil je niet op mijn website"), of een van het handjevol korte woorden geleverd door het script, dat als gebruikt, wordt vervangen door het script met een voorbereide toelichting waarom de klant/gebruiker is geblokkeerd.

De voorbereide toelichtingen hebben L10N-ondersteuning en kan worden vertaald door het script op basis van de taal die u opgeeft naar de `lang` richtlijn van het script-configuratie. Tevens, u kunt het script instrueren om "Deny"-signatures te negeren op basis van hun `[Param]` waarde (als ze gebruik maken van deze korte woorden) via de richtlijnen gespecificeerd door het script-configuratie (elk kort woord heeft een overeenkomstige richtlijn te verwerken overeenkomende signatures of te negeren hen). `[Param]` waarden dat niet gebruiken deze korte woorden, echter, hebben geen L10N-ondersteuning en daarom zal NIET worden vertaald door het script, en tevens, en zijn niet direct controleerbaar door het script-configuratie.

De beschikbare korte woorden zijn:
- Bogon
- Cloud
- Generic
- Proxy
- Spam
- Legal
- Malware

#### 7.1 ETIKETTEN

##### 7.1.0 SECTIE ETIKETTEN

Als u wilt uw aangepaste signatures te splitsen in afzonderlijke secties, u kunt deze individuele secties te identificeren om het script door toevoeging van een "sectie etiket" onmiddellijk na de signatures van elke sectie, samen met de naam van uw signature-sectie (zie het onderstaande voorbeeld).

```
# Sectie 1.
1.2.3.4/32 Deny Bogon
2.3.4.5/32 Deny Cloud
4.5.6.7/32 Deny Generic
5.6.7.8/32 Deny Spam
6.7.8.9/32 Deny Proxy
Tag: Sectie 1
```

Om sectie etiketteren te breken en zodat de etiketten zijn niet onjuist geïdentificeerd met signatuursecties uit eerder in de signatuurbestanden, gewoon ervoor zorgen dat er ten minste twee opeenvolgende regeleinden tussen uw etiket en uw eerdere signatuursecties. Een ongeëtiketteerd signatures wordt standaard om "IPv4" of "IPv6" (afhankelijk van welke soorten signatures worden getriggerd).

```
1.2.3.4/32 Deny Bogon
2.3.4.5/32 Deny Cloud

4.5.6.7/32 Deny Generic
5.6.7.8/32 Deny Spam
Tag: Sectie 1
```

In het bovenstaande voorbeeld `1.2.3.4/32` en `2.3.4.5/32` zal worden geëtiketteerd als "IPv4", terwijl `4.5.6.7/32` en `5.6.7.8/32` zal worden geëtiketteerd als "Sectie 1".

Dezelfde logica kan ook worden toegepast voor het scheiden van andere typen etiketten.

In het bijzonder kunnen sectie etiketten erg handig zijn voor foutopsporing wanneer valse positieven optreden, door een eenvoudige manier te voorzien om de exacte oorzaak van het probleem te vinden, en kunnen erg handig zijn om logbestanditems te filteren bij het bekijken van logbestanden via de frontend logbestanden pagina (sectienamen kunnen worden aangeklikt via de frontend logs pagina en kunnen worden gebruikt als filtercriteria). Als sectie etiketten zijn weggelaten voor bepaalde signatures, wanneer de signatures worden getriggerd, gebruikt CIDRAM de naam van het signatuurbestand samen met het type IP-adres geblokkeerd (IPv4 of IPv6) als een fallback, en dus zijn sectie etiketten volledig optioneel. In sommige gevallen kunnen ze echter worden aanbevolen, zoals wanneer signatuurbestanden vaag worden genoemd of wanneer het anders moeilijk zou zijn om de bron van de signatures duidelijk te identificeren waardoor een verzoek wordt geblokkeerd.

##### 7.1.1 VERVALTIJD ETIKETTEN

Als u wilt signatures te vervallen na verloop van tijd, op soortgelijke wijze als sectie etiketten, u kan een "vervaltijd etiket" gebruikt om aan te geven wanneer signatures moet niet meer geldig. Vervaltijd etiketten gebruiken het formaat "JJJJ.MM.DD" (zie het onderstaande voorbeeld).

```
# Sectie 1.
1.2.3.4/32 Deny Generic
2.3.4.5/32 Deny Generic
Expires: 2016.12.31
```

Verlopen signatures zullen nooit worden getriggerd bij een aanvraag, wat er ook gebeurt.

##### 7.1.2 HERKOMST ETIKETTEN

Als u het land van herkomst voor een bepaalde signature wilt opgeven, kunt u dit doen met behulp van een "herkomst etiket". Een herkomst etiket accepteert een "[ISO 3166-1 2-letterig](https://nl.wikipedia.org/wiki/ISO_3166-1)"-code die overeenkomt met het land van herkomst voor de signatures waarop deze van toepassing is. Deze codes moeten in hoofd-letters worden geschreven (kleine-letters worden niet correct weergegeven). Wanneer een herkomst etiket wordt gebruikt, wordt deze toegevoegd aan het logveld "Waarom Geblokkeerd" voor alle verzoeken die zijn geblokkeerd als gevolg van de signatures waarop de etiket is toegepast.

Als de optionele component "flags CSS" is geïnstalleerd, wanneer u de logbestanden bekijkt via de frontend, wordt informatie dat toegevoegd door herkomst etiketten vervangen met de vlag van het land dat overeenkomt met die informatie. Deze informatie, in ruwe vorm of als de vlag van een land, kan worden aangeklikt, en wanneer erop wordt geklikt, filteren log-invoeren door middel van andere, soortgelijk identificerende log-invoeren (waardoor effectief degenen die de logs pagina openen te filteren op basis van land van herkomst).

Notitie: Technisch gezien is dit geen vorm van geolocatie, vanwege het feit dat het niet gaat om het opzoeken van specifieke informatie met betrekking tot inkomende IP's, maar in plaats stelt ons in staat om expliciet een land van herkomst opgeven voor verzoeken die worden geblokkeerd door specifieke signatures. Meerdere herkomst etiketten zijn toegestaan binnen dezelfde signature sectie.

Hypothetisch voorbeeld:

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

Alle etiketten kunnen samen worden gebruikt en alle etiketten zijn optioneel (zie het onderstaande voorbeeld).

```
# Voorbeeld Sectie.
1.2.3.4/32 Deny Generic
Origin: US
Tag: Voorbeeld Sectie.
Expires: 2016.12.31
```

##### 7.1.3 UITSTEL ETIKETTEN

Wanneer grote aantallen signatuurbestanden zijn geïnstalleerd en actief worden gebruikt, installaties kunnen behoorlijk ingewikkeld worden, en er kunnen signatures zijn die elkaar overlappen. In deze gevallen om te voorkomen dat meerdere, overlappende signatures worden geactiveerd tijdens blokgebeurtenissen, uitstel etiketten kunnen worden gebruikt om specifieke signature secties uit te stellen in gevallen waarin een ander specifiek signatuurbestand is geïnstalleerd en actief wordt gebruikt. Dit kan handig zijn in gevallen waarin sommige signatures vaker worden bijgewerkt dan andere, om de minder vaak bijgewerkte signatures uit te stellen ten gunste van de meer frequent bijgewerkte signatures.

Uitstel etiketten worden op dezelfde manier gebruikt als andere typen etiketten. De waarde van de etiket moet overeenkomen met een geïnstalleerd en actief gebruikt signatuurbestand dat moet worden uitgesteld.

Voorbeeld:

```
1.2.3.4/32 Deny Generic
Origin: AA
2.3.4.5/32 Deny Generic
Origin: BB
Defers to: preferred_signatures.dat
```

#### 7.2 YAML

##### 7.2.0 YAML BASICS

Een vereenvoudigde vorm van YAML markup kan worden gebruikt in signatuurbestanden voor het bepalen van gedragingen en specifieke instellingen voor afzonderlijke signatuursecties. Dit kan handig zijn als u de waarde van uw configuratie richtlijnen willen afwijken op basis van individuele signatures en signatuursecties (bijvoorbeeld; als u wilt om een e-mailadres te leveren voor support tickets voor alle gebruikers geblokkeerd door een bepaalde signature, maar wil niet om een e-mailadres te leveren voor support tickets voor de gebruikers geblokkeerd door andere signatures; als u wilt een specifieke signatures te leiden tot een pagina redirect; als u wilt een signature-sectie voor gebruik met reCAPTCHA te markeren; als u wilt om geblokkeerde toegang pogingen te loggen in afzonderlijke bestanden op basis van individuele signatures en/of signatuursecties).

Het gebruik van YAML markup in de signatuurbestanden is volledig optioneel (d.w.z., u kan het gebruiken als u wenst te doen, maar u bent niet verplicht om dit te doen), en is in staat om de meeste (maar niet alle) configuratie richtlijnen hefboomeffect.

Notitie: YAML markup implementatie in CIDRAM is zeer simplistisch en zeer beperkt; Het is bedoeld om de specifieke eisen van CIDRAM te voldoen op een manier dat heeft de vertrouwdheid van YAML markup, maar noch volgt noch voldoet aan de officiële specificaties (en zal daarom niet zich op dezelfde wijze als grondiger implementaties elders, en is misschien niet geschikt voor alle andere projecten elders).

In CIDRAM, YAML markup segmenten worden geïdentificeerd aan het script door drie streepjes ("---"), en eindigen naast hun bevattende signatuursecties door dubbel-regeleinden. Een typische YAML markup segment binnen een signature-sectie bestaat uit drie streepjes op een lijn onmiddellijk na de lijst van CIDR's en elke etiketten, gevolgd door een tweedimensionale lijst van sleutel-waarde paren (eerste dimensie, configuratie richtlijn categorieën; tweede dimensie, configuratie richtlijnen) voor welke configuratie richtlijnen moeten worden gewijzigd (en om welke waarden) wanneer een signature in die signature-sectie wordt getriggerd (zie de onderstaande voorbeelden).

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

##### 7.2.1 HOE OM SIGNATUURSECTIES TE MARKEREN VOOR GEBRUIK MET reCAPTCHA

Als "usemode" is 0 of 1, signatuursecties hoeven niet voor gebruik met reCAPTCHA te markeren (omdat ze al wil of wil niet gebruik reCAPTCHA, afhankelijk van deze instelling).

Als "usemode" is 2, om signatuursecties te markeren voor gebruik met reCAPTCHA, een invoer wordt opgenomen in het YAML segment voor dat signature-sectie (zie het onderstaande voorbeeld).

```
# Deze sectie zal reCAPTCHA te gebruiken.
1.2.3.4/32 Deny Generic
2.3.4.5/32 Deny Generic
Tag: reCAPTCHA-Enabled
---
recaptcha:
 enabled: true
```

*Notitie: Als standaard, een reCAPTCHA instantie zal ALLEEN worden aangeboden aan de gebruiker als reCAPTCHA is ingeschakeld (met "usemode" als 1, of "usemode" als 2 met "enabled" als true), en als precies ÉÉN signature is getriggerd (niet meer, niet minder; als er meerdere signatures worden getriggerd, een reCAPTCHA instantie zal NIET worden aangeboden). Echter, dit gedrag kan worden gewijzigd via de "signature_limit" richtlijn.*

#### 7.3 EXTRA INFORMATIE

##### 7.3.0 SIGNATUURSECTIES TE NEGEREN

Bovendien, als u wilt CIDRAM om enkele specifieke secties in iedereen van de signatuurbestanden te negeren, kunt u het `ignore.dat` bestand gebruiken om specificeren welke secties te negeren. Op een nieuwe regel, schrijven `Ignore`, gevolgd door een spatie, gevolgd door de naam van de sectie die u wilt CIDRAM te negeren (zie het onderstaande voorbeeld).

```
Ignore Sectie 1
```

Dit kan ook worden bereikt door de interface te gebruiken die wordt geboden door de pagina "sectielijst" van de frontend van CIDRAM.

##### 7.3.1 HULPREGELS

Als u vindt dat het schrijven van uw eigen aangepaste signatuurbestanden of aangepaste modules te ingewikkeld voor u is, kan een eenvoudiger alternatief zijn om de interface te gebruiken die wordt geboden door de "hulpregels"-pagina van de front-end van CIDRAM. Door de juiste opties te selecteren en details over specifieke soorten verzoeken op te geven, kunt u CIDRAM instrueren hoe op die verzoeken moet worden gereageerd. "Hulpregels" worden uitgevoerd nadat alle signatuurbestanden en modules al zijn uitgevoerd.

#### 7.4 <a name="MODULE_BASICS"></a>BASICS (VOOR MODULES)

Modules kunnen worden gebruikt om de functionaliteit van CIDRAM uit te breiden, extra taken uit te voeren, of aanvullende logica te verwerken. Meestal worden ze gebruikt wanneer een verzoek op een andere manier dan het oorspronkelijke IP-adres moet worden geblokkeerd (en dus, wanneer een CIDR-signature niet voldoende is om de aanvraag te blokkeren). Modules worden geschreven als PHP-bestanden, en dus worden module-signatures doorgaans geschreven als PHP-code.

Enkele goede voorbeelden van CIDRAM-modules zijn hier te vinden:
- https://github.com/CIDRAM/CIDRAM-Extras/tree/master/modules

Een sjabloon voor het schrijven van nieuwe modules vindt u hier:
- https://github.com/CIDRAM/CIDRAM-Extras/blob/master/modules/module_template.php

Vanwege dat modules worden geschreven als PHP-bestanden, als je voldoende bekend bent met de CIDRAM-codebase, kun je je modules structureren zoals u wilt, en schrijf uw module-signatures zoals u dat wilt (op grond van wat mogelijk is met PHP). Voor uw gemak echter, en voor een betere wederzijdse verstaanbaarheid tussen bestaande modules en uw eigen modules, het is aan te raden de bovenstaande sjabloon te analyseren om de structuur en het formaat die het biedt te kunnen gebruiken.

*Notitie: Als u het niet prettig vindt om met PHP-code te werken, wordt het niet aanbevolen om uw eigen modules te schrijven.*

CIDRAM biedt een aantal functies voor het gebruik van modules, waardoor het eenvoudiger en gemakkelijker is om uw eigen modules te schrijven. Informatie over deze functionaliteit wordt hieronder beschreven.

#### 7.5 MODULE FUNCTIONALITEIT

##### 7.5.0 "$Trigger"

Module-signatures worden meestal geschreven met `$Trigger`. In de meeste gevallen zal deze closure belangrijker zijn dan iets anders om modules te schrijven.

`$Trigger` accepteert 4 parameters: `$Condition`, `$ReasonShort`, `$ReasonLong` (optioneel), en `$DefineOptions` (optioneel).

De waarachtigheid van `$Condition` wordt geëvalueerd, en indien true/waar, wordt de signature "getriggerd". Indien deze false/vals is, wordt de signature *niet* "getriggerd". `$Condition` bevat meestal PHP-code om een voorwaarde te evalueren die ervoor moet zorgen dat een verzoek wordt geblokkeerd.

`$ReasonShort` wordt vermeld in het "Waarom Geblokkeerd" veld wanneer de signature wordt "getriggerd".

`$ReasonLong` is een optioneel bericht dat aan de gebruiker/client moet worden weergegeven voor wanneer ze zijn geblokkeerd, om uit te leggen waarom ze zijn geblokkeerd. Standaard ingesteld op het standaardbericht "Toegang Geweigerd" wanneer dit wordt weggelaten.

`$DefineOptions` is een optionele array met sleutel/waarde-paren, die wordt gebruikt om configuratie-opties te definiëren die specifiek zijn voor de instantie. Configuratie-opties worden toegepast wanneer de signature "getriggerd" is.

`$Trigger` retourneert true/waar als de signature "getriggerd" is, en false/vals als dat niet het geval is.

Als u deze closure in uw module wilt gebruiken, moet u deze eerst erven het van de bovenliggende-scope:
```PHP
$Trigger = $CIDRAM['Trigger'];
```

##### 7.5.1 "$Bypass"

Signature-bypasses worden meestal geschreven met `$Bypass`.

`$Bypass` accepteert 3 parameters: `$Condition`, `$ReasonShort`, en `$DefineOptions` (optioneel).

De waarachtigheid van `$Condition` wordt geëvalueerd, en indien true/waar, wordt de bypass "getriggerd". Indien deze false/vals is, wordt de bypass *niet* "getriggerd". `$Condition` bevat meestal PHP-code om een voorwaarde te evalueren die ervoor moet zorgen dat een verzoek wordt *niet* geblokkeerd.

`$ReasonShort` wordt vermeld in het "Waarom Geblokkeerd" veld wanneer de bypass wordt "getriggerd".

`$DefineOptions` is een optionele array met sleutel/waarde-paren, die wordt gebruikt om configuratie-opties te definiëren die specifiek zijn voor de instantie. Configuratie-opties worden toegepast wanneer de bypass "getriggerd" is.

`$Bypass` retourneert true/waar als de bypass "getriggerd" is, en false/vals als dat niet het geval is.

Als u deze closure in uw module wilt gebruiken, moet u deze eerst erven het van de bovenliggende-scope:
```PHP
$Bypass = $CIDRAM['Bypass'];
```

##### 7.5.2 "$CIDRAM['DNS-Reverse']"

Dit kan worden gebruikt om de hostnaam van een IP-adres op te halen. Als u een module wilt maken om hostnamen te blokkeren, kan deze closure nuttig zijn.

Voorbeeld:
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

#### 7.6 MODULE VARIABELEN

Modules worden in hun eigen bereik uitgevoerd, en alle variabelen gedefinieerd door een module, zullen niet toegankelijk zijn voor andere modules, of naar het bovenliggende script, tenzij ze zijn opgeslagen in de array `$CIDRAM` (al het andere wordt gespoeld nadat de module-uitvoering is voltooid).

Hieronder vindt u enkele veelvoorkomende variabelen die handig kunnen zijn voor uw module:

Variabele | Beschrijving
----|----
`$CIDRAM['BlockInfo']['DateTime']` | De huidige datum en tijd.
`$CIDRAM['BlockInfo']['IPAddr']` | IP-adres voor het huidige verzoek.
`$CIDRAM['BlockInfo']['ScriptIdent']` | CIDRAM-scriptversie.
`$CIDRAM['BlockInfo']['Query']` | De vraag voor het huidige verzoek.
`$CIDRAM['BlockInfo']['Referrer']` | De verwijzer voor het huidige verzoek (indien aanwezig).
`$CIDRAM['BlockInfo']['UA']` | De user-agent voor het huidige verzoek.
`$CIDRAM['BlockInfo']['UALC']` | De user-agent voor het huidige verzoek (in kleine letters).
`$CIDRAM['BlockInfo']['ReasonMessage']` | Het bericht dat moet worden weergegeven aan de gebruiker/client voor het huidige verzoek als ze zijn geblokkeerd.
`$CIDRAM['BlockInfo']['SignatureCount']` | Het aantal signatures dat is getriggerd voor het huidige verzoek.
`$CIDRAM['BlockInfo']['Signatures']` | Referentie-informatie voor alle signatures die zijn getriggerd voor het huidige verzoek.
`$CIDRAM['BlockInfo']['WhyReason']` | Referentie-informatie voor alle signatures die zijn getriggerd voor het huidige verzoek.

---


### 8. <a name="SECTION8"></a>BEKENDE COMPATIBILITEITSPROBLEMEN

De volgende pakketten en producten zijn incompatibel met CIDRAM:
- __[Endurance Page Cache](https://github.com/CIDRAM/CIDRAM/issues/52)__
- __[Mix.com](https://github.com/CIDRAM/CIDRAM/issues/80)__

Modules zijn beschikbaar gemaakt om ervoor te zorgen dat de volgende pakketten en producten compatibel zijn met CIDRAM:
- __[BunnyCDN](https://github.com/CIDRAM/CIDRAM/issues/56)__

*Zie ook: [Compatibiliteitskaarten](https://maikuolan.github.io/Compatibility-Charts/).*

---


### 9. <a name="SECTION9"></a>VEELGESTELDE VRAGEN (FAQ)

- [Wat is een "signature"?](#WHAT_IS_A_SIGNATURE)
- [Wat is een "CIDR"?](#WHAT_IS_A_CIDR)
- [Wat is een "vals positieve"?](#WHAT_IS_A_FALSE_POSITIVE)
- [Kan CIDRAM blok hele landen?](#BLOCK_ENTIRE_COUNTRIES)
- [Hoe vaak worden signatures bijgewerkt?](#SIGNATURE_UPDATE_FREQUENCY)
- [Ik heb een fout tegengekomen tijdens het gebruik van CIDRAM en ik weet niet wat te doen! Help alstublieft!](#ENCOUNTERED_PROBLEM_WHAT_TO_DO)
- [Ik ben geblokkeerd door CIDRAM van een website die ik wil bezoeken! Help alstublieft!](#BLOCKED_WHAT_TO_DO)
- [Ik wil CIDRAM (voorafgaand aan v2) gebruiken met een PHP-versie ouder dan 5.4.0; Kan u helpen?](#MINIMUM_PHP_VERSION)
- [Ik wil CIDRAM (v2) gebruiken met een PHP-versie ouder dan 7.2.0; Kan u helpen?](#MINIMUM_PHP_VERSION_V2)
- [Kan ik een enkele CIDRAM-installatie gebruiken om meerdere domeinen te beschermen?](#PROTECT_MULTIPLE_DOMAINS)
- [Ik wil niet tijd verspillen met het installeren van dit en om het te laten werken met mijn website; Kan ik u betalen om het te doen?](#PAY_YOU_TO_DO_IT)
- [Kan ik u of een van de ontwikkelaars van dit project voor privéwerk huren?](#HIRE_FOR_PRIVATE_WORK)
- [Ik heb speciale modificaties en aanpassingen nodig; Kan u helpen?](#SPECIALIST_MODIFICATIONS)
- [Ik ben een ontwikkelaar, website ontwerper, of programmeur. Kan ik werken aan dit project accepteren of aanbieden?](#ACCEPT_OR_OFFER_WORK)
- [Ik wil bijdragen aan het project; Kan ik dit doen?](#WANT_TO_CONTRIBUTE)
- [Kan ik cron gebruiken om automatisch bij te werken?](#CRON_TO_UPDATE_AUTOMATICALLY)
- [Wat zijn "overtredingen"?](#WHAT_ARE_INFRACTIONS)
- [Kan CIDRAM hostnamen blokkeren?](#BLOCK_HOSTNAMES)
- [Wat kan ik gebruiken voor "default_dns"?](#WHAT_CAN_I_USE_FOR_DEFAULT_DNS)
- [Kan ik CIDRAM gebruiken om andere dingen dan websites te beschermen (b.v., e-mailservers, FTP, SSH, IRC, enz)?](#PROTECT_OTHER_THINGS)
- [Zullen er problemen optreden als ik CIDRAM tegelijk gebruik met CDN's of cacheservices?](#CDN_CACHING_PROBLEMS)
- [Zal CIDRAM mijn website beschermen tegen DDoS-aanvallen?](#DDOS_ATTACKS)
- [Wanneer ik modules of signatuurbestanden activeer of deactiveer via de updates-pagina, sorteert deze ze alfanumeriek in de configuratie. Kan ik de manier wijzigen waarop ze worden gesorteerd?](#CHANGE_COMPONENT_SORT_ORDER)

#### <a name="WHAT_IS_A_SIGNATURE"></a>Wat is een "signature"?

In het context van CIDRAM, een "signature" verwijst naar gegevens die als een indicator/identifier werken, voor iets specifiek waarnaar we op zoek zijn, gewoonlijk een IP-adres of CIDR, en bevat een aantal instructies voor CIDRAM, vertelt het de beste manier om wanneer het ontmoet waar we naar op zoek zijn te reageren. Een typische signature voor CIDRAM ziet er als volgt uit:

Voor "signatuurbestanden":

`1.2.3.4/32 Deny Generic`

Voor "modules":

```PHP
$Trigger(strpos($CIDRAM['BlockInfo']['UA'], 'Foobar') !== false, 'Foobar-UA', 'User agent "Foobar" not allowed.');
```

*Notitie: Signatures voor "signatuurbestanden", en signatures voor "modules", zijn niet hetzelfde.*

Vaak (maar niet altijd), signatures bundelen samen in groepen, dat vormen van "signatuursecties", vaak vergezeld van opmerkingen, markup, en/of gerelateerde metadata die kunnen om extra context voor de signatures en/of om verdere instructies worden gebruikt.

#### <a name="WHAT_IS_A_CIDR"></a>Wat is een "CIDR"?

"CIDR" is een acroniem voor "Classless Inter-Domain Routing" *[[1](https://nl.wikipedia.org/wiki/Classless_Inter-Domain_Routing), [2](https://whatismyipaddress.com/cidr)]*, en het is dit acroniem dat gebruikt wordt als onderdeel van de naam voor dit pakket, "CIDRAM", waarvoor is een acroniem voor "Classless Inter-Domain Routing Access Manager".

Echter, in het context van CIDRAM (zoals, binnen deze documentatie, binnen discussies met betrekking tot CIDRAM, of binnen de CIDRAM lokalisaties), wanneer een "CIDR" (enkelvoud) of "CIDR's" (meervoud) wordt genoemd of bedoeld (en dus waarbij we deze woorden als zelfstandige naamwoorden gebruiken, in tegenstelling tot als acroniemen), wat hier bedoeld is, is een subnet (of subnetten), uitgedrukt met CIDR notatie. De reden dat CIDR (of CIDR's) wordt gebruikt in plaats van subnet (of subnetten) is om duidelijk te maken dat het specifiek subnets wordt uitgedrukt met CIDR notatie waarnaar wordt verwezen (omdat CIDR notatie slechts één van de verschillende manieren waarop subnetten uitgedrukt kunnen worden). CIDRAM kan daarom beschouwd worden als een "subnet access manager".

Hoewel deze dubbele betekenis van "CIDR" in sommige gevallen dubbelzinnig kan zijn, deze uitleg, samen met de context die wordt verstrekt, zou moeten helpen om dubbelzinnigheid te heffen.

#### <a name="WHAT_IS_A_FALSE_POSITIVE"></a>Wat is een "vals positieve"?

De term "vals positieve" (*alternatief: "vals positieve fout"; "vals alarm"*; Engels: *false positive*; *false positive error*; *false alarm*), zeer eenvoudig beschreven, en een algemene context, wordt gebruikt bij het testen voor een toestand, om verwijst naar om de resultaten van die test, wanneer de resultaten positief zijn (d.w.z, de toestand wordt vastgesteld als "positief"), maar wordt verwacht "negatief" te zijn (d.w.z, de toestand in werkelijkheid is "negatief"). Een "vals positieve" analoog aan "huilende wolf" kan worden beschouwd (waarin de toestand wordt getest, is of er een wolf in de buurt van de kudde, de toestand is "vals" in dat er geen wolf in de buurt van de kudde, en de toestand wordt gerapporteerd als "positief" door de herder door middel van schreeuwen "wolf, wolf"), of analoog aan situaties in medische testen waarin een patiënt gediagnosticeerd als met een ziekte of aandoening, terwijl het in werkelijkheid, hebben ze geen ziekte of aandoening.

Enkele andere termen die worden gebruikt zijn "waar positieve", "waar negatieve" en "vals negatieve". Een "waar positieve" verwijst naar wanneer de resultaten van de test en de huidige staat van de toestand zijn beide waar (of "positief"), and a "waar negatieve" verwijst naar wanneer de resultaten van de test en de huidige staat van de toestand zijn beide vals (of "negatief"); En "waar positieve" of en "waar negatieve" wordt beschouwd als een "correcte gevolgtrekking" zijn. De antithese van een "vals positieve" is een "vals negatieve"; Een "vals negatieve" verwijst naar wanneer de resultaten van de test is negatief (d.w.z, de aandoening wordt vastgesteld als "negatief"), maar wordt verwacht "positief" te zijn (d.w.z, de toestand in werkelijkheid is "positief").

In de context van CIDRAM, deze termen verwijzen naar de signatures van CIDRAM en wat/wie ze blokkeren. Wanneer CIDRAM blokkeert een IP-adres, als gevolg van slechte, verouderde of onjuiste signature, maar moet niet hebben gedaan, of wanneer het doet om de verkeerde redenen, we verwijzen naar deze gebeurtenis als een "vals positieve". Wanneer CIDRAM niet in slaagt te blokkeren om een IP-adres dat had moeten worden geblokkeerd, als gevolg van onvoorziene bedreigingen, ontbrekende signatures of tekorten in zijn signatures, we verwijzen naar deze gebeurtenis als een "gemiste detectie" (dat is analoog aan een "vals negatieve").

Dit kan worden samengevat in de onderstaande tabel:

&nbsp; | CIDRAM moet *NIET* een IP-adres te blokkeren | CIDRAM *MOET* een IP-adres te blokkeren
---|---|---
CIDRAM *NIET* doet blokkeren van een IP-adres | Waar negatieve (correcte gevolgtrekking) | Gemiste detectie (analoog aan vals negatieve)
CIDRAM *DOET* blokkeren van een IP-adres | __Vals positieve__ | Waar positieve (correcte gevolgtrekking)

#### <a name="BLOCK_ENTIRE_COUNTRIES"></a>Kan CIDRAM blok hele landen?

Ja. De eenvoudigste manier om dit te bereiken zou zijn om sommige van de optionele land blocklists door Macmathan te installeren. Dit kan gedaan worden met een paar simpele muisklikken direct vanaf de frontend updates pagina, of, als u liever voor de frontend te blijven uitgeschakeld, door ze rechtstreeks downloaden van de **[optionele land blocklists downloads pagina](https://bitbucket.org/macmathan/blocklists)**, uploaden van hen om de vault, en citeren van hun namen in de desbetreffende configuratie richtlijnen.

#### <a name="SIGNATURE_UPDATE_FREQUENCY"></a>Hoe vaak worden signatures bijgewerkt?

Bijwerkfrequentie varieert afhankelijk van de signatuurbestanden betrokken. Alle de onderhouders voor CIDRAM signatuurbestanden algemeen proberen om hun signatures regelmatig bijgewerkt te houden, maar als gevolg van dat ieder van ons hebben verschillende andere verplichtingen, ons leven buiten het project, en zijn niet financieel gecompenseerd (d.w.z., betaald) voor onze inspanningen aan het project, een nauwkeurige updateschema kan niet worden gegarandeerd. In het algemeen, signatures zullen worden bijgewerkt wanneer er genoeg tijd om dit te doen, en in het algemeen, onderhouders proberen om prioriteiten te stellen op basis van noodzaak en van hoe vaak veranderingen optreden tussen ranges. Het verlenen van bijstand wordt altijd gewaardeerde als u bent bereid om dat te doen.

#### <a name="ENCOUNTERED_PROBLEM_WHAT_TO_DO"></a>Ik heb een fout tegengekomen tijdens het gebruik van CIDRAM en ik weet niet wat te doen! Help alstublieft!

- Gebruikt u de nieuwste versie van de software? Gebruikt u de nieuwste versies van uw signatuurbestanden? Indien het antwoord op een van deze twee vragen is nee, probeer eerst om alles te bijwerken, en controleer of het probleem zich blijft voordoen. Als dit aanhoudt, lees verder.
- Hebt u door alle documentatie gecontroleerd? Zo niet, doe dat dan. Als het probleem niet kan worden opgelost met behulp van de documentatie, lees verder.
- Hebt u de **[issues pagina](https://github.com/CIDRAM/CIDRAM/issues)** gecontroleerd, om te zien of het probleem al eerder is vermeld? Als het eerder vermeld, controleer of eventuele suggesties, ideeën en/of oplossingen werden verstrekt, en volg als per nodig om te proberen het probleem op te lossen.
- Als het probleem blijft bestaan, zoek hulp door een nieuw issue op de issues pagina te maken.

#### <a name="BLOCKED_WHAT_TO_DO"></a>Ik ben geblokkeerd door CIDRAM van een website die ik wil bezoeken! Help alstublieft!

CIDRAM biedt een manier voor website-eigenaren om ongewenst verkeer te blokkeren, maar het is de verantwoordelijkheid van de website-eigenaren om zelf te beslissen hoe ze willen CIDRAM gebruiken. In het geval van de valse positieven met betrekking tot de signatuurbestanden normaal meegeleverd met CIDRAM, correcties kunnen worden gemaakt, maar met betrekking tot het wordt gedeblokkeerd van specifieke websites, u nodig hebt om te communiceren met de eigenaren van de websites in kwestie. In gevallen waarin correcties worden gemaakt, op zijn minst, zullen ze nodig hebben om hun signatuurbestanden en/of installatie bij te werken, en in andere gevallen (zoals bijvoorbeeld, waarin ze hun installatie hebt gewijzigd, creëerden hun eigen aangepaste signatures, enz), het is hun verantwoordelijkheid om uw probleem op te lossen, en is geheel buiten onze controle.

#### <a name="MINIMUM_PHP_VERSION"></a>Ik wil CIDRAM (voorafgaand aan v2) gebruiken met een PHP-versie ouder dan 5.4.0; Kan u helpen?

Nee. PHP >= 5.4.0 is een minimale vereiste voor CIDRAM < v2.

#### <a name="MINIMUM_PHP_VERSION_V2"></a>Ik wil CIDRAM (v2) gebruiken met een PHP-versie ouder dan 7.2.0; Kan u helpen?

Nee. PHP >= 7.2.0 is een minimale vereiste voor CIDRAM v2.

*Zie ook: [Compatibiliteitskaarten](https://maikuolan.github.io/Compatibility-Charts/).*

#### <a name="PROTECT_MULTIPLE_DOMAINS"></a>Kan ik een enkele CIDRAM-installatie gebruiken om meerdere domeinen te beschermen?

Ja. CIDRAM-installaties zijn niet van nature gebonden naar specifieke domeinen, en kan daarom worden gebruikt om meerdere domeinen te beschermen. Algemeen, wij verwijzen naar CIDRAM installaties die slechts één domein beschermen als "single-domain installaties", en wij verwijzen naar CIDRAM installaties die meerdere domeinen en/of subdomeinen beschermen als "multi-domain installaties". Als u een multi-domain installaties werken en nodig om verschillende signatuurbestanden voor verschillende domeinen te gebruiken, of nodig om CIDRAM anders geconfigureerd voor verschillende domeinen te zijn, het is mogelijk om dit te doen. Nadat het configuratiebestand hebt geladen (`config.ini`), CIDRAM controleert het bestaan van een "configuratie overschrijdend bestand" specifiek voor het domein (of sub-domein) dat wordt aangevraagd (`het-domein-dat-wordt-aangevraagd.tld.config.ini`), en als gevonden, elke configuratie waarden gedefinieerd door het configuratie overschrijdend bestand zal worden gebruikt in plaats van de configuratie waarden die zijn gedefinieerd door het configuratiebestand. Het configuratie overschrijdende bestanden zijn identiek aan het configuratiebestand, en naar eigen goeddunken, kan de volledige van alle configuratie richtlijnen beschikbaar voor CIDRAM bevatten, of wat dan ook kleine subsectie dat nodig is die afwijkt van de waarden die normaal door het configuratiebestand worden gedefinieerd. Het configuratie overschrijdende bestanden worden genoemd volgens het domein waaraan ze bestemd zijn (dus, bijvoorbeeld, als u een configuratie overschrijdend bestand voor het domein `http://www.some-domain.tld/` nodig hebt, het configuratie overschrijdende bestanden moeten worden genoemd als `some-domain.tld.config.ini`, en moeten naast het configuratiebestand, `config.ini`, in de vault geplaatst worden). De domeinnaam is afgeleid van de koptekst `HTTP_HOST` van het verzoek; "www" wordt genegeerd.

#### <a name="PAY_YOU_TO_DO_IT"></a>Ik wil niet tijd verspillen met het installeren van dit en om het te laten werken met mijn website; Kan ik u betalen om het te doen?

Misschien. Dit wordt per geval beoordeeld. Laat ons weten wat u nodig hebt, wat u aanbiedt, en wij laten u weten of we kunnen helpen.

#### <a name="HIRE_FOR_PRIVATE_WORK"></a>Kan ik u of een van de ontwikkelaars van dit project voor privéwerk huren?

*Zie hierboven.*

#### <a name="SPECIALIST_MODIFICATIONS"></a>Ik heb speciale modificaties en aanpassingen nodig; Kan u helpen?

*Zie hierboven.*

#### <a name="ACCEPT_OR_OFFER_WORK"></a>Ik ben een ontwikkelaar, website ontwerper, of programmeur. Kan ik werken aan dit project accepteren of aanbieden?

Ja. Onze licentie verbiedt dit niet.

#### <a name="WANT_TO_CONTRIBUTE"></a>Ik wil bijdragen aan het project; Kan ik dit doen?

Ja. Bijdragen aan het project zijn zeer welkom. Zie voor meer informatie "CONTRIBUTING.md".

#### <a name="CRON_TO_UPDATE_AUTOMATICALLY"></a>Kan ik cron gebruiken om automatisch bij te werken?

Ja. Een API is ingebouwd in het frontend voor interactie met de updates pagina via externe scripts. Een apart script, "[Cronable](https://github.com/Maikuolan/Cronable)", is beschikbaar, en kan door uw cron manager of cron scheduler gebruikt worden om deze en andere ondersteunde pakketten automatisch te updaten (dit script biedt zijn eigen documentatie).

#### <a name="WHAT_ARE_INFRACTIONS"></a>Wat zijn "overtredingen"?

"Overtredingen" bepalen wanneer een IP die nog niet is geblokkeerd door specifieke signatuurbestanden, moet worden geblokkeerd voor toekomstige verzoeken, en ze zijn nauw verbonden met IP-tracking. Sommige functionaliteit en modules bestaan die toestaan dat verzoeken om andere redenen dan het herkomst van IP worden geblokkeerd (zoals de aanwezigheid van user agents die overeenkomen met spambots of hacktools, gevaarlijke zoekopdrachten, vervalste DNS enzovoort), en wanneer dit gebeurt, kan een "overtreding" optreden. Ze bieden een manier om IP-adressen te identificeren die overeenkomen met ongewenste verzoeken die nog niet door specifieke signatuurbestanden worden geblokkeerd. Overtredingen komen meestal 1-op-1 overeen met het aantal keren dat een IP wordt geblokkeerd, maar niet altijd (ernstige blokgebeurtenissen kunnen een overtredingen-waarde groter dan één oplopen, en als "track_mode" false is, er zullen geen overtredingen plaatsvinden voor blokgebeurtenissen die alleen door signatuurbestanden worden getriggerd).

#### <a name="BLOCK_HOSTNAMES"></a>Kan CIDRAM hostnamen blokkeren?

Ja. Hiervoor moet u een aangepast modulebestand maken. *Zien: [BASICS (VOOR MODULES)](#MODULE_BASICS)*.

#### <a name="WHAT_CAN_I_USE_FOR_DEFAULT_DNS"></a>Wat kan ik gebruiken voor "default_dns"?

Over het algemeen zou het IP van een betrouwbare DNS-server voldoende moeten zijn. Als u op zoek bent naar suggesties, bieden [public-dns.info](https://public-dns.info/) en [OpenNIC](https://servers.opennic.org/) uitgebreide lijsten met bekende, openbare DNS-servers. Of, kijk in de onderstaande tabel:

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

*Notitie: Ik maak geen claims of garanties met betrekking tot de privacypraktijken, beveiliging, werkzaamheid of betrouwbaarheid van enige DNS-services die worden vermeld of anderszins. Doe uw eigen onderzoek als u beslissingen over hun neemt.*

#### <a name="PROTECT_OTHER_THINGS"></a>Kan ik CIDRAM gebruiken om andere dingen dan websites te beschermen (b.v., e-mailservers, FTP, SSH, IRC, enz)?

Je kan (in juridische zin), maar je zou dit niet moeten doen (in een technische en praktische zin). Onze licentie beperkt niet welke technologieën CIDRAM implementeren, maar CIDRAM is een WAF (Web Application Firewall) en is altijd bedoeld om websites te beschermen. Omdat het niet is ontworpen met andere technologieën in het achterhoofd, is het onwaarschijnlijk dat het effectief is of een betrouwbare bescherming biedt voor andere technologieën, implementatie zal waarschijnlijk moeilijk zijn, en het risico op valse positieven en gemiste detecties is zeer hoog.

#### <a name="CDN_CACHING_PROBLEMS"></a>Zullen er problemen optreden als ik CIDRAM tegelijk gebruik met CDN's of cacheservices?

Mogelijk. Dit is afhankelijk van de aard van de service in kwestie en hoe u deze gebruikt. Over het algemeen zijn er geen problemen als u alleen statische items (afbeeldingen, CSS, enz) in het cache plaatst. Er kunnen echter problemen zijn, als u gegevens in de cache opslaat die anders normaliter dynamisch zouden worden gegenereerd wanneer daarom wordt gevraagd, of als u de resultaten van POST-verzoeken in cache opslaat (dit zou in wezen uw website en zijn omgeving als verplicht statisch maken, en het is onwaarschijnlijk dat CIDRAM een zinvol voordeel biedt in een verplichte statische omgeving). Er kunnen ook specifieke configuratievereisten voor CIDRAM zijn, afhankelijk van welke CDN of cacheservice u gebruikt (je moet ervoor zorgen dat CIDRAM correct is geconfigureerd voor de specifieke CDN of cacheservice die u gebruikt). Het niet correct configureren van CIDRAM kan leiden tot aanzienlijk problematische valse positieven en gemiste detecties.

#### <a name="DDOS_ATTACKS"></a>Zal CIDRAM mijn website beschermen tegen DDoS-aanvallen?

Kort antwoord: Nee.

Een iets langer antwoord: CIDRAM helpt de impact van ongewenst verkeer op uw website te verminderen (waardoor de bandbreedtekosten van uw website worden geminderd), helpt de impact van ongewenst verkeer op uw hardware te verminderen (b.v., de mogelijkheid van uw server om verzoeken te verwerken en te serveren), en kan helpen om verschillende andere mogelijke negatieve effecten geassocieerd met ongewenst verkeer te verminderen. Er zijn echter twee belangrijke dingen die onthouden moeten worden om deze vraag te begrijpen.

Ten eerste, CIDRAM is een PHP-pakket en werkt daarom op de computer waarop PHP is geïnstalleerd. Dit betekent dat CIDRAM een verzoek alleen kan zien en blokkeren *nadat* de server het al heeft ontvangen. Ten tweede, effectieve DDoS-mitigatie moet aanvragen filteren *voordat* ze de server bereikt waarop de DDoS-aanval gericht is. In het ideale geval moeten DDoS-aanvallen worden gedetecteerd en beperkt door oplossingen die in staat zijn om verkeer dat is gekoppeld aan aanvallen te laten vallen of omleiden, voordat het in de eerste plaats de gerichte server bereikt.

Dit kan worden geïmplementeerd met behulp van speciale hardware-oplossingen op locatie, en/of cloudgebaseerde oplossingen zoals speciale DDoS-mitigatie diensten, routering van de DNS van een domein via DDoS-resistente netwerken, cloudgebaseerde filteren, of een combinatie daarvan. In elk geval is dit onderwerp echter een beetje te ingewikkeld om grondig met slechts een paar alinea's uit te leggen, dus ik zou aanraden verder onderzoek te doen als dit een onderwerp is dat u wilt nastreven. Wanneer de ware aard van DDoS-aanvallen goed wordt begrepen, zal dit antwoord logischer zijn.

#### <a name="CHANGE_COMPONENT_SORT_ORDER"></a>Wanneer ik modules of signatuurbestanden activeer of deactiveer via de updates-pagina, sorteert deze ze alfanumeriek in de configuratie. Kan ik de manier wijzigen waarop ze worden gesorteerd?

Ja. Als u bepaalde bestanden wilt dwingen om in een specifieke volgorde uit te voeren, kunt u enkele willekeurige gegevens vóór hun naam toevoegen in de configuratie richtlijn waar ze worden vermeld, gescheiden door een dubbele punt. Wanneer de updates-pagina vervolgens de bestanden opnieuw sorteert, heeft deze toegevoegde willekeurige gegevens invloed op de sorteervolgorde, waardoor ze vervolgens in de gewenste volgorde worden uitgevoerd, zonder dat ze hoeven te hernoemen.

Stel dat u bijvoorbeeld een configuratie richtlijn aanneemt met de volgende bestanden:

`file1.php,file2.php,file3.php,file4.php,file5.php`

Als u wilt dat `file3.php` het eerst uitvoert, u zou iets als `aaa:` kunnen toevoegen voor de naam van het bestand:

`file1.php,file2.php,aaa:file3.php,file4.php,file5.php`

Als dan een nieuw bestand, `file6.php`, is geactiveerd, als de updates-pagina ze allemaal opnieuw sorteert, het zou zo moeten eindigen:

`aaa:file3.php,file1.php,file2.php,file4.php,file5.php,file6.php`

Dezelfde situatie wanneer een bestand is gedeactiveerd. Omgekeerd, als u wilde dat het bestand als laatste werd uitgevoerd, u zou iets als `zzz:` kunnen toevoegen voor de naam van het bestand. In elk geval hoeft u het betreffende bestand niet te hernoemen.

---


### 11. <a name="SECTION11"></a>LEGALE INFORMATIE

#### 11.0 SECTIE PREAMBULE

Dit sectie van de documentatie is bedoeld om mogelijke juridische overwegingen met betrekking tot het gebruik en de implementatie van het pakket te beschrijven, en om wat basisgerelateerde informatie te verstrekken. Dit kan voor sommige gebruikers belangrijk zijn om naleving van eventuele wettelijke vereisten in de landen waarin zij actief zijn te waarborgen, en sommige gebruikers moeten hun website-beleid mogelijk aanpassen in overeenstemming met deze informatie.

Eerst en vooral, realiseer je alstublieft dat ik (de auteur van het pakket) geen advocaat en geen gekwalificeerde juridische professional van welke aard. Daarom ben ik niet juridisch gekwalificeerd om juridisch advies te geven. Ook in sommige gevallen, exacte wettelijke vereisten kunnen verschillen tussen verschillende landen en rechtsgebieden, en deze variërende wettelijke vereisten kunnen soms conflicteren (zoals bijvoorbeeld, in het geval van landen die voorrang geven aan [privacyrechten](https://nl.wikipedia.org/wiki/Privacy) en het [recht om te worden vergeten](https://nl.wikipedia.org/wiki/Recht_om_vergeten_te_worden), versus landen die de voorrang geven aan uitgebreide [dataretentie](https://nl.wikipedia.org/wiki/Dataretentie)). Overweeg ook dat toegang tot het pakket niet beperkt is tot specifieke landen of rechtsgebieden, en daarom is de gebruikersbasis van het pakket waarschijnlijk geografisch divers. Gezien deze punten, ben ik niet in de positie om aan te geven wat het betekent om "in overeenstemming met de wetgeving" te zijn voor alle gebruikers, in alle opzichten. Ik hoop echter dat de informatie hierin u zal helpen om zelf tot een beslissing te komen over wat u moet doen om wettelijk compatibel te blijven in de context van het pakket. Als u twijfels of zorgen hebt met betrekking tot de informatie hierin, of als u aanvullende hulp en advies nodig hebt vanuit een juridisch perspectief, ik zou aanraden een gekwalificeerde juridische professional te raadplegen.

#### 11.1 AANSPRAKELIJKHEID EN VERANTWOORDELIJKHEID

Zoals al aangegeven door de pakketlicentie, wordt het pakket geleverd zonder enige garantie. Dit omvat (maar is niet beperkt tot) alle reikwijdte van aansprakelijkheid. Het pakket wordt u aangeboden voor uw gemak, in de hoop dat dit nuttig zal zijn, en dat het u enig voordeel oplevert. Echter, of u het pakket gebruikt of implementeert, is uw eigen keuze. U bent niet gedwongen om het pakket te gebruiken of te implementeren, maar wanneer u dat doet, bent u verantwoordelijk voor dat besluit. Noch ik, noch andere bijdragers aan het pakket, zijn juridisch aansprakelijk voor de gevolgen van de beslissingen die u neemt, ongeacht of het direct, indirect, impliciet, of anderszins is.

#### 11.2 DERDEN

Afhankelijk van de precieze configuratie en implementatie, kan het pakket in sommige gevallen communiceren en informatie delen met derden. Deze informatie kan in sommige contexten door sommige rechtsgebieden worden gedefinieerd als "[persoonsgegevens](https://nl.wikipedia.org/wiki/Persoonsgegevens)".

Hoe deze informatie door deze derden kan worden gebruikt, is onderworpen aan de verschillende beleidsregels uiteengezet door deze derden, en valt buiten het bestek van deze documentatie. In al dergelijke gevallen echter, het delen van informatie met deze derden kan worden uitgeschakeld. In al dergelijke gevallen, als u ervoor kiest om het in te schakelen, is het uw verantwoordelijkheid om eventuele zorgen die u heeft met betrekking tot de privacy, veiligheid, en gebruik van persoonsgegevens door deze derden te onderzoeken. Als er twijfels bestaan, of als u niet tevreden bent met het gedrag van deze derden met betrekking tot persoonsgegevens, is het wellicht het beste om het delen van informatie met deze derden uit te schakelen.

Met het oog op transparantie wordt het type informatie dat wordt gedeeld en met wie, hieronder beschreven.

##### 11.2.0 HOSTNAAM LOOKUPS

Als u functies of modules gebruikt die bedoeld zijn om met hostnamen te werken (zoals de "slechte hosts blokker module", "tor project exit nodes block module", of "zoekmachine verificatie", bijvoorbeeld), moet CIDRAM in staat zijn om de hostnaam van inkomende verzoeken op de een of andere manier. Dit gebeurt meestal door de hostnaam van het IP-adres van inkomende verzoeken van een DNS-server op te vragen, of door de informatie op te vragen via functionaliteit die wordt geboden door het systeem waarop CIDRAM is geïnstalleerd (dit wordt meestal een "hostnaam lookup" verwezen naar). De DNS-servers die standaard worden gedefinieerd, behoren tot de [Google DNS](https://dns.google.com/)-service (maar dit kan eenvoudig via de configuratie worden gewijzigd). De exacte services waarmee wordt gecommuniceerd, kunnen worden geconfigureerd en zijn afhankelijk van de manier waarop u het pakket configureert. In het geval dat u functionaliteit gebruikt die wordt geboden door het systeem waarop CIDRAM is geïnstalleerd, moet u contact opnemen met uw systeembeheerder om te bepalen naar welke routes hostnaam lookups moeten worden gebruikt. Hostnaam lookups kunnen worden voorkomen in CIDRAM door de betreffende modules te vermijden of door de pakketconfiguratie aan te passen aan uw behoeften.

*Relevante configuratie-opties:*
- `general` -> `default_dns`
- `general` -> `search_engine_verification`
- `general` -> `social_media_verification`
- `general` -> `force_hostname_lookup`
- `general` -> `allow_gethostbyaddr_lookup`

##### 11.2.1 WEBFONTS

Sommige aangepaste thema's, evenals de standaard UI ("gebruikersinterface") voor de frontend van CIDRAM en de pagina "Toegang Geweigerd", kunnen webfonts gebruiken om esthetische redenen. Webfonts zijn standaard uitgeschakeld, maar indien ingeschakeld, vindt directe communicatie plaats tussen de browser van de gebruiker en de service die de webfonts host. Dit kan mogelijk inhouden dat informatie wordt doorgegeven zoals het IP-adres van de gebruiker, user agent, besturingssysteem, en andere details die beschikbaar zijn voor het verzoek. De meeste van deze webfonts worden gehost door de [Google Fonts](https://fonts.google.com/)-service.

*Relevante configuratie-opties:*
- `general` -> `disable_webfonts`

##### 11.2.2 VERIFICATIE VAN ZOEKMACHINES EN SOCIALE MEDIA

Wanneer verificatie van zoekmachines is ingeschakeld, probeert CIDRAM "forward DNS-lookups" uit te voeren om te verifiëren of verzoeken die claimen afkomstig te zijn van zoekmachines authentiek zijn. Hetzelfde, wanneer verificatie van sociale media is ingeschakeld, CIDRAM doet hetzelfde voor schijnbare verzoeken van sociale media. Hiertoe gebruikt het de [Google DNS](https://dns.google.com/)-service om IP-adressen van de hostnamen van deze inkomende verzoeken op te lossen (in dit proces worden de hostnamen van deze inkomende verzoeken gedeeld met de service).

*Relevante configuratie-opties:*
- `general` -> `search_engine_verification`
- `general` -> `social_media_verification`

##### 11.2.3 GOOGLE reCAPTCHA

CIDRAM ondersteunt optioneel [Google reCAPTCHA](https://www.google.com/recaptcha/), waardoor gebruikers de mogelijkheid hebben om de pagina "Toegang Geweigerd" te omzeilen door een reCAPTCHA-instantie te voltooien (meer informatie over deze functie wordt eerder in de documentatie beschreven, met name in de configuratiesectie). Google reCAPTCHA vereist API-sleutels om correct te kunnen werken, en is daarom standaard uitgeschakeld. Dit kan worden ingeschakeld door de vereiste API-sleutels in de pakketconfiguratie te definiëren. Indien ingeschakeld, vindt directe communicatie tussen de browser van de gebruiker en de reCAPTCHA-service plaats. Dit kan mogelijk inhouden dat informatie wordt doorgegeven zoals het IP-adres van de gebruiker, user agent, besturingssysteem, en andere details die beschikbaar zijn voor het verzoek. Het IP-adres van de gebruiker kan ook worden gedeeld in communicatie tussen CIDRAM en de reCAPTCHA-service bij het verifiëren van de geldigheid van een reCAPTCHA-instantie en het controleren of deze met succes is voltooid.

*Relevante configuratie-opties: Alles vermeld onder de configuratiecategorie "recaptcha".*

##### 11.2.4 STOP FORUM SPAM

[Stop Forum Spam](https://www.stopforumspam.com/) is een fantastische, vrij beschikbare service die kan helpen forums, blogs, en websites van spammers te beschermen. Het doet dit door een database van bekende spammers aan te bieden, en een API die kan worden gebruikt om te controleren of een IP-adres, gebruikersnaam, of e-mailadres in de database wordt vermeld.

CIDRAM biedt een optionele module die gebruikmaakt van deze API om te controleren of het IP-adres van inkomende verzoeken bij een verdachte spammer hoort. De module is niet standaard geïnstalleerd, maar als u ervoor kiest om deze te installeren, kunnen de gebruikers IP-adressen worden gedeeld met de Stop Forum Spam API in overeenstemming met het beoogde doel van de module. Wanneer de module is geïnstalleerd, communiceert CIDRAM met deze API wanneer een inkomende aanvraag een resource aanvraagt die door CIDRAM wordt herkend als een type resource dat vaak wordt getarget door spammers (zoals inlogpagina's, registratiepagina's, e-mailverificatiepagina's, opmerkingenformulieren, enz).

#### 11.3 LOGGEN

Te loggen is om een aantal redenen een belangrijk onderdeel van CIDRAM. Het kan moeilijk zijn om valse positieven te diagnosticeren en op te lossen wanneer de blokgebeurtenissen die deze veroorzaken niet worden vastgelegd. Zonder blokgebeurtenissen te loggen, kan het moeilijk zijn om precies vast te stellen hoe performant CIDRAM zich in een bepaalde context bevindt, en het kan moeilijk zijn om te bepalen waar zijn tekortkomingen kunnen zijn, en welke veranderingen nodig kunnen zijn voor de configuratie of signatures dienovereenkomstig, zodat het blijft functioneren zoals bedoeld. Ongeacht, loggen is misschien niet wenselijk voor alle gebruikers, en blijft volledig optioneel. In CIDRAM te loggen is standaard uitgeschakeld. Om dit in te schakelen, moet CIDRAM dienovereenkomstig worden geconfigureerd.

Ook, als te loggen wettelijk toegestaan is, en voor zover dat wettelijk toegestaan is (bijvoorbeeld, de soorten informatie die kan worden vastgelegd, voor hoelang, en onder welke omstandigheden), kan variëren, afhankelijk van het rechtsgebied en de context waarin CIDRAM wordt geïmplementeerd (bijvoorbeeld, of u als een individu, als een bedrijf, en op commerciële of niet-commerciële basis opereert). Het kan daarom nuttig zijn om dit gedeelte zorgvuldig door te lezen.

CIDRAM kan informatie op verschillende manieren loggen, wat verschillende soorten informatie inhoudt, om verschillende redenen.

##### 11.3.0 BLOKGEBEURTENISSEN

Het primaire type loggen dat CIDRAM kan uitvoeren, heeft betrekking op "blokgebeurtenissen". Dit type loggen heeft betrekking op wanneer CIDRAM een aanvraag blokkeert, en kan in drie verschillende indelingen worden aangeboden:
- Door mensen leesbare logbestanden.
- Apache-stijl logbestanden.
- Geserialiseerde logbestanden.

Een blokgebeurtenis, vastgelegd in een door mensen leesbaar logbestand, ziet er meestal als volgt uit (bijvoorbeeld):

```
ID: 1234
Script Versie: CIDRAM v1.6.0
Datum/Tijd: Day, dd Mon 20xx hh:ii:ss +0000
IP-Adres: x.x.x.x
Hostname: dns.hostname.tld
Signatures Tellen: 1
Signatures Verwijzing: x.x.x.x/xx
Waarom Geblokkeerd: Cloud Service ("Netwerknaam", Lxx:Fx, [XX])!
User Agent: Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.181 Safari/537.36
Gereconstrueerde URI: http://your-site.tld/index.php
reCAPTCHA State: Enabled.
```

Diezelfde blokgebeurtenis, geregistreerd in een Apache-stijl logbestand, ziet er ongeveer zo uit:

```
x.x.x.x - - [Day, dd Mon 20xx hh:ii:ss +0000] "GET /index.php HTTP/1.1" 200 xxxx "-" "Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.181 Safari/537.36"
```

Een geregistreerde blokgebeurtenis bevat meestal de volgende informatie:
- Een ID-nummer dat verwijst naar de blokgebeurtenis.
- De versie van CIDRAM die momenteel wordt gebruikt.
- De datum en tijd waarop de blokgebeurtenis plaatsvond.
- Het IP-adres van het geblokkeerde verzoek.
- De hostnaam van het IP-adres van het geblokkeerde verzoek (indien beschikbaar).
- Het aantal signatures dat door het verzoek is geactiveerd.
- Verwijzingen naar de geactiveerd signatures.
- Verwijzingen naar de redenen voor de blokgebeurtenis, en enkele standaard, gerelateerde foutopsporingsinformatie.
- De user agent van het geblokkeerde verzoek (d.w.z., hoe de verzoekende entiteit zichzelf identificeerde bij het verzoek).
- Een reconstructie van de identifier voor de oorspronkelijk aangevraagde bron.
- De reCAPTCHA state voor het huidige verzoek (indien relevant).

*De configuratie richtlijnen die verantwoordelijk zijn voor dit type loggen, en voor elk van de drie beschikbare indelingen, zijn:*
- `general` -> `logfile`
- `general` -> `logfileApache`
- `general` -> `logfileSerialized`

Wanneer deze richtlijnen leeg worden gelaten, blijft dit type logboek uitgeschakeld.

##### 11.3.1 reCAPTCHA LOGGEN

Dit type loggen heeft specifiek betrekking op reCAPTCHA-instanties, en gebeurt alleen op wanneer een gebruiker een reCAPTCHA-instantie probeert te voltooien.

Een reCAPTCHA-logsinvoer bevat het IP-adres van de gebruiker die probeert een reCAPTCHA-instantie te voltooien, de datum en tijd waarop de poging heeft plaatsgevonden, en de reCAPTCHA state. Een reCAPTCHA-logsinvoer ziet er meestal als volgt uit (bijvoorbeeld):

```
IP-Adres: x.x.x.x - Datum/Tijd: Day, dd Mon 20xx hh:ii:ss +0000 - reCAPTCHA State: Succes!
```

*De configuratie richtlijn die verantwoordelijk is voor reCAPTCHA loggen is:*
- `recaptcha` -> `logfile`

##### 11.3.2 FRONTEND LOGGEN

Dit type loggen is bedoeld voor pogingen om bij de frontend in te loggen, en gebeurt alleen op wanneer een gebruiker zich probeert in te loggen bij de frontend (ervan uitgaande dat de frontend-toegang is ingeschakeld).

Een frontend logsinvoer bevat het IP-adres van de gebruiker die probeert in te loggen, de datum en tijd waarop de poging heeft plaatsgevonden, en de resultaten van de poging (ingelogd succesvol of niet). Een frontend logsinvoer ziet er meestal als volgt uit (bijvoorbeeld):

```
x.x.x.x - Day, dd Mon 20xx hh:ii:ss +0000 - "admin" - Ingelogd.
```

*De configuratie-optie die verantwoordelijk is voor de frontend toegang te loggen is:*
- `general` -> `FrontEndLog`

##### 11.3.3 LOGROTATIE

Mogelijk wilt u logs na een bepaalde periode opschonen, of mogelijk bent u wettelijk verplicht (d.w.z., de hoeveelheid tijd die het wettelijk toelaatbaar is om logs te bewaren, kan bij wet beperkt zijn). U kunt dit bereiken door datum/tijd-markeringen op te nemen in de namen van uw logbestanden, zoals gespecificeerd door uw pakketconfiguratie (b.v., `{yyyy}-{mm}-{dd}.log`), en vervolgens logrotatie in te schakelen (logrotatie stelt u in staat om enige actie in logbestanden uit te voeren wanneer de gespecificeerde limieten worden overschreden).

Bijvoorbeeld: Als ik wettelijk verplicht was om logs na 30 dagen te verwijderen, kon ik `{dd}.log` opgeven in de namen van mijn logbestanden (`{dd}` verwijst naar de dagen), de waarde van `log_rotation_limit` op 30 zetten, en de waarde van `log_rotation_action` op `Delete` zetten.

Omgekeerd, als u verplicht bent om logs gedurende langere tijd te bewaren, kunt u überhaupt geen logrotatie gebruiken, of kunt u de waarde van `log_rotation_action` op `Archive` zetten, om logbestanden te comprimeren, waardoor de totale hoeveelheid schijfruimte die ze innemen, wordt verminderd.

*Relevante configuratie-opties:*
- `general` -> `log_rotation_limit`
- `general` -> `log_rotation_action`

##### 11.3.4 LOGTRUNCATIE

Het is ook mogelijk om afzonderlijke logbestanden af te kappen als ze een bepaalde grootte overschrijden, als dit iets is dat u misschien nodig heeft, of zou willen doen.

*Relevante configuratie-opties:*
- `general` -> `truncate`

##### 11.3.5 IP-ADRES PSEUDONIMISATIE

Ten eerste, als u niet bekend bent met de term, "pseudonimisatie" verwijst naar de verwerking van persoonsgegevens als zodanig, zodat deze niet meer kan worden geïdentificeerd aan een specifieke persoon zonder aanvullende informatie, en op voorwaarde dat dergelijke aanvullende informatie afzonderlijk wordt bijgehouden en onderworpen wordt aan technische en organisatorische maatregelen om ervoor te zorgen dat persoonsgegevens niet kunnen worden geïdentificeerd aan een natuurlijke persoon.

De volgende bronnen kunnen helpen om het in meer detail uit te leggen:
- [[privacycompany.eu] Wat is het verschil tussen pseudonimiseren en anonimiseren van persoonsgegevens en wat zijn de gevolgen?](https://www.privacycompany.eu/blog-wat-is-het-verschil-tussen-pseudonimiseren-en-anonimiseren-van-persoonsgegevens-en-wat-zijn-de-gevolgen/)
- [[nen.nl] Pseudonimisatie](https://www.nen.nl/Normontwikkeling/Pseudonimisatie.htm)
- [[considerati.com] Anonimiseren en pseudonimiseren: wat is het verschil en wat is het belang ervan?](https://www.considerati.com/nl/publicaties/blog/anonimiseren-en-pseudonimiseren-wat-is-het-verschil-en-wat-is-het-belang-ervan/)
- [[Wikipedia] Pseudonimiseren](https://nl.wikipedia.org/wiki/Pseudonimiseren)

In sommige omstandigheden kan het wettelijk verplicht zijn om PII die is verzameld, verwerkt, of opgeslagen, te anonimiseren of te pseudonimiseren. Hoewel dit concept al geruime tijd bestaat, GDPR/DSGVO vermeldt, en moedigt specifiek "pseudonimisatie".

CIDRAM kan IP-adressen pseudonimiseren wanneer ze worden geregistreerd, als dit iets is dat u misschien nodig heeft, of zou willen doen. Wanneer CIDRAM IP-adressen pseudonimiseert, wanneer geregistreerd, het laatste octet van IPv4-adressen, en alles na het tweede deel van IPv6-adressen wordt weergegeven door een "x" (effectief afronding van IPv4-adressen naar het initiële adres van het 24e subnet waar ze in factoreren, en IPv6-adressen naar het initiële adres van het 32e subnet waar ze in factoreren).

*Notitie: Het pseudonimisatieproces van het IP-adres van CIDRAM heeft geen invloed op de IP-trackingfunctie van CIDRAM. Als dit een probleem voor u is, is het misschien het beste om IP-tracking volledig uit te schakelen. Dit kan worden bereikt door `track_mode` in te stellen op `false` en door modules te vermijden.*

*Relevante configuratie-opties:*
- `signatures` -> `track_mode`
- `legal` -> `pseudonymise_ip_addresses`

##### 11.3.6 HET WEGLATEN VAN LOGINFORMATIE

Als u nog een stap verder wilt gaan door te voorkomen dat specifieke soorten informatie volledig worden vastgelegd, is dit ook mogelijk. CIDRAM biedt configuratie-opties om te bepalen of IP-adressen, hostnamen, en user agents in logs zijn opgenomen. Standaard worden alle drie deze gegevenspunten opgenomen in logs wanneer deze beschikbaar zijn. Als u een van deze configuratie-opties instelt op `true`, wordt de overeenkomstige informatie uit logs weggelaten.

*Notitie: Er is geen reden om IP-adressen te pseudonimiseren wanneer ze volledig uit logs worden weggelaten.*

*Relevante configuratie-opties:*
- `legal` -> `omit_ip`
- `legal` -> `omit_hostname`
- `legal` -> `omit_ua`

##### 11.3.7 STATISTIEKEN

CIDRAM is optioneel in staat om statistieken bij te houden, zoals het totale aantal blokgebeurtenissen of reCAPTCHA-instanties die zijn opgetreden sinds een bepaald tijdstip. Deze functie is standaard uitgeschakeld, maar kan worden ingeschakeld via de pakketconfiguratie. Deze functie houdt alleen het totale aantal opgetreden gebeurtenissen bij en bevat geen informatie over specifieke gebeurtenissen (en moet daarom niet als PII worden beschouwd).

*Relevante configuratie-opties:*
- `general` -> `statistics`

##### 11.3.8 ENCRYPTIE

CIDRAM codeert de cache of logboekinformatie niet. [Encryptie](https://nl.wikipedia.org/wiki/Encryptie) voor de cache en logs kunnen in de toekomst worden geïntroduceerd, maar er zijn momenteel geen specifieke plannen voor. Als u zich zorgen maakt over ongeautoriseerde derden die toegang krijgen tot delen van CIDRAM die mogelijk PII of gevoelige informatie bevatten, zoals de cache of logbestanden, raad ik CIDRAM aan niet te installeren op een openbare locatie (b.v., installeer CIDRAM buiten de standaard `public_html` directory of gelijkwaardig daarvan beschikbaar voor de meeste standaard webservers) en dat de juiste beperkende machtigingen worden afgedwongen voor de directory waar deze zich bevindt (in het bijzonder, voor de vault directory). Als dat niet voldoende is om uw zorgen weg te nemen, configureer dan CIDRAM als zodanig dat de soorten informatie die uw zorgen veroorzaken, niet zullen worden verzameld of ingelogd (zoals door loggen uit te schakelen).

#### 11.4 COOKIES

CIDRAM zet [cookies](https://nl.wikipedia.org/wiki/Cookie_(internet)) op twee punten in zijn codebase. Ten eerste, wanneer een gebruiker een reCAPTCHA-instantie met succes voltooit (en ervan uitgaande dat `lockuser` is ingesteld op `true`), CIDRAM stelt een cookie in om te kunnen onthouden voor volgende verzoeken dat de gebruiker al een reCAPTCHA-instantie heeft voltooid, zodat het niet nodig zal zijn om de gebruiker continu te vragen een reCAPTCHA-instantie bij volgende aanvragen in te vullen. Ten tweede, wanneer een gebruiker zich met succes ingelogd bij de frontend, stelt CIDRAM een cookie in om de gebruiker te kunnen onthouden voor volgende aanvragen (d.w.z., cookies worden gebruikt om de gebruiker te authenticeren voor een login-sessie).

In beide gevallen worden cookiewaarschuwingen prominent weergegeven (als het relevant is), waardoor de gebruiker wordt gewaarschuwd dat cookies worden ingesteld als deze zich bezighouden met de relevante acties. Cookies zijn niet ingesteld op andere punten in de codebase.

*Notitie: De specifieke implementatie door CIDRAM van de "invisible" API voor reCAPTCHA kan incompatibel zijn met cookiewetten in sommige rechtsgebieden, en moet worden vermeden door websites die onder dergelijke wetgeving vallen. Kiezen om in plaats daarvan de "V2" API te gebruiken, of eenvoudig reCAPTCHA volledig uit te schakelen, kan de voorkeur verdienen.*

*Relevante configuratie-opties:*
- `general` -> `disable_frontend`
- `recaptcha` -> `lockuser`
- `recaptcha` -> `api`

#### 11.5 MARKETING EN ADVERTEREN

CIDRAM verzamelt of verwerkt geen informatie voor marketing of advertentie doeleinden, en verkoopt of profiteert niet van verzamelde of geregistreerde informatie. CIDRAM is geen commerciële onderneming, en houdt geen verband met commerciële belangen, dus het zou geen zin hebben om deze dingen te doen. Dit is sinds het begin van het project het geval geweest, en is nog steeds het geval. Bovendien zou het doen van deze dingen contraproductief zijn ten opzichte van de geest en het beoogde doel van het project als geheel, en zolang ik het project blijf onderhouden, zal het nooit gebeuren.

#### 11.6 PRIVACYBELEID

In sommige omstandigheden kan het wettelijk verplicht zijn om duidelijk een link naar uw privacybeleid te tonen op alle pagina's en secties van uw website. Dit kan belangrijk zijn als middel om ervoor te zorgen dat gebruikers goed geïnformeerd zijn over uw exacte privacypraktijken, de soorten PII die u verzamelt, en hoe u van plan bent om het te gebruiken. Om een dergelijke link op de pagina "Toegang Geweigerd" van CIDRAM te kunnen opnemen, wordt een configuratie-optie verstrekt om de URL van uw privacybeleid op te geven.

*Notitie: Het wordt ten zeerste aanbevolen dat uw pagina met privacybeleid niet achter de bescherming van CIDRAM wordt geplaatst. Als CIDRAM uw privacybeleid pagina beschermt, en een door CIDRAM geblokkeerde gebruiker op de koppeling naar uw privacybeleid klikt, worden ze gewoon opnieuw geblokkeerd, en kunnen ze uw privacybeleid niet zien. Idealiter moet u een koppeling maken naar een statische kopie van uw privacybeleid, zoals een HTML-pagina of een niet-gecodeerd bestand dat niet door CIDRAM wordt beschermd.*

*Relevante configuratie-opties:*
- `legal` -> `privacy_policy`

#### 11.7 GDPR/DSGVO/AVG

De Algemene Verordening Gegevensbescherming (AVG, of GDPR/DSGVO) is een verordening van de Europese Unie, die met ingang van 25 Mei 2018 in werking treedt. Het primaire doel van de verordening is om burgers en inwoners van de EU controle te geven over hun eigen persoonsgegevens, en om regelgeving binnen de EU te verenigen met betrekking tot privacy en persoonsgegevens.

De verordening bevat specifieke bepalingen met betrekking tot de verwerking van "[persoonsgegevens](https://nl.wikipedia.org/wiki/Persoonsgegevens)" (PII) van alle "betrokkenen" (elke geïdentificeerde of identificeerbare natuurlijke persoon) vanuit of binnen de EU. Om aan de regelgeving te voldoen, moeten "ondernemingen" (zoals bepaald door de verordening), en alle relevante systemen en processen moeten standaard "[privacy by design](https://autoriteitpersoonsgegevens.nl/nl/zelf-doen/privacycheck/privacy-design)" implementeren, moet de hoogst mogelijke privacy-instellingen gebruiken, moet de nodige waarborgen implementeren voor alle opgeslagen of verwerkte informatie (inclusief, maar niet beperkt tot, de implementatie van pseudonimisering of volledige anonimisering van gegevens), moet duidelijk en ondubbelzinnig verklaren welke soorten gegevens zij verzamelen, hoe zij deze verwerken, om welke redenen, hoe lang zij deze bewaren, en of zij deze gegevens delen met derden, de soorten gegevens die met derden worden gedeeld, hoe, waarom, enzovoort.

Gegevens worden mogelijk niet verwerkt tenzij er een wettelijke basis is om dit te doen, zoals bepaald door de verordening. In het algemeen betekent dit dat om de gegevens van een betrokkene op een wettige basis te verwerken, dit moet worden gedaan in overeenstemming met wettelijke verplichtingen, of alleen moet worden gedaan nadat de betrokkene expliciete, goed geïnformeerde, ondubbelzinnige toestemming heeft verkregen.

Omdat aspecten van de verordening in de loop van de tijd kunnen evolueren, om de verspreiding van verouderde informatie te voorkomen, is het wellicht beter om de verordening te leren van een gezaghebbende bron, in tegenstelling tot het simpelweg opnemen van de relevante informatie hier in de documentatie van het pakket (die uiteindelijk verouderd kan raken naarmate de regelgeving evolueert).

[EUR-Lex](https://eur-lex.europa.eu/) (een deel van de officiële website van de Europese Unie dat informatie biedt over EU-wetgeving) biedt uitgebreide informatie over GDPR/DSGVO/AVG, beschikbaar in 24 verschillende talen (op het moment dat dit wordt geschreven), en beschikbaar om te downloaden in PDF-formaat. Ik zou zeker aanraden om de informatie die ze bieden te lezen, om meer te leren over GDPR/DSGVO/AVG:
- [VERORDENING (EU) 2016/679 VAN HET EUROPEES PARLEMENT EN DE RAAD](https://eur-lex.europa.eu/legal-content/NL/TXT/?uri=celex:32016R0679)

Als alternatief is er een kort (niet-gezaghebbende) overzicht van GDPR/DSGVO/AVG beschikbaar op Wikipedia:
- [Algemene verordening gegevensbescherming](https://nl.wikipedia.org/wiki/Algemene_verordening_gegevensbescherming)

---


Laatste Bijgewerkt: 23 Februari 2019 (2019.02.23).
