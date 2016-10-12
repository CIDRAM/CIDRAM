## Documentatie voor CIDRAM (Nederlandse).

### Inhoud
- 1. [PREAMBULE](#SECTION1)
- 2. [HOE TE INSTALLEREN](#SECTION2)
- 3. [HOE TE GEBRUIKEN](#SECTION3)
- 4. [BESTANDEN IN DIT PAKKET](#SECTION4)
- 5. [CONFIGURATIEOPTIES](#SECTION5)
- 6. [HANDTEKENINGFORMAAT](#SECTION6)
- 7. [VEELGESTELDE VRAGEN (FAQ)](#SECTION7)

---


###1. <a name="SECTION1"></a>PREAMBULE

CIDRAM (Classless Inter-Domain Routing Access Manager) is een PHP-script ontworpen om websites te beschermen door het blokkeren van verzoeken afkomstig van IP-adressen beschouwd als bronnen van ongewenste verkeer, inclusief (maar niet gelimiteerd tot) het verkeer van niet-menselijke toegang eindpunten, cloud-diensten, spambots, schrapers/scrapers, ezv. Het doet dit door het berekenen van de mogelijke CIDRs van de IP-adressen geleverde van binnenkomende verzoeken en dan het vergelijken van deze mogelijke CIDRs tegen zijn handtekening bestanden (deze handtekening bestanden bevatten lijsten van CIDRs van IP-adressen beschouwd als bronnen van ongewenste verkeer); Als overeenkomsten worden gevonden, de verzoeken worden geblokkeerd.

CIDRAM COPYRIGHT 2016 en verder GNU/GPLv2 van Caleb M (Maikuolan).

Dit script is gratis software; u kunt, onder de voorwaarden van de GNU General Public License zoals gepubliceerd door de Free Software Foundation, herdistribueren en/of wijzigen dit; ofwel versie 2 van de Licentie, of (naar uw keuze) enige latere versie. Dit script wordt gedistribueerd in de hoop dat het nuttig zal zijn, maar ZONDER ENIGE GARANTIE; zonder zelfs de impliciete garantie van VERKOOPBAARHEID of GESCHIKTHEID VOOR EEN BEPAALD DOEL. Zie de GNU General Public License voor meer informatie, gelegen in het `LICENSE.txt` bestand en ook beschikbaar uit:
- <http://www.gnu.org/licenses/>.
- <http://opensource.org/licenses/>.

Dit document en de bijbehorende pakket kunt gedownload gratis zijn van [Github](https://github.com/Maikuolan/CIDRAM/).

---


###2. <a name="SECTION2"></a>HOE TE INSTALLEREN

Ik hoop te stroomlijnen dit proces door maken een installateur op een bepaald punt in de niet al te verre toekomst, maar tot die tijd, volg deze instructies te werken CIDRAM om meeste systemen en CMS:

1) Omdat u zijn lezen dit, ik ben ervan uit u al gedownload een gearchiveerde kopie van het script, uitgepakt zijn inhoud en heeft het ergens op uw lokale computer. Vanaf hier, u nodig te bepalen waar op uw host of CMS die inhoud te plaatsen. Een bestandsmap zoals `/public_html/cidram/` of soortgelijk (hoewel, het is niet belangrijk welke u kiest, zolang het is iets veilig en iets waar u blij mee bent) zal volstaan. *Voordat u het uploaden begint, lees verder..*

2) Hernoemen `config.ini.RenameMe` naar `config.ini` (gelegen binnen `vault`), en facultatief (sterk aanbevolen voor ervaren gebruikers, maar niet aan te raden voor beginners of voor de onervaren), open het (dit bestand bevat alle beschikbare CIDRAM configuratie opties; boven elke optie moet een korte opmerking te beschrijven wat het doet en wat het voor). Pas deze opties als het u past, volgens welke geschikt is voor uw configuratie. Sla het bestand, sluiten.

3) Upload de inhoud (CIDRAM en zijn bestanden) naar het bestandsmap die u zou op eerder besloten (u nodig niet de `*.txt`/`*.md` bestanden opgenomen, maar meestal, u moeten uploaden alles).

4) CHMOD het bestandsmap `vault` naar "755" (als er problemen, u kan proberen "777"; dit is minder veilig, hoewel). De belangrijkste bestandsmap opslaan van de inhoud (degene die u eerder koos), gewoonlijk, kunt worden genegeerd, maar CHMOD-status moet worden gecontroleerd als u machtigingen problemen heeft in het verleden met uw systeem (standaard, moet iets zijn als "755").

5) Volgende, u nodig om "haak" CIDRAM om uw systeem of CMS. Er zijn verschillende manieren waarop u kunt "haak" scripts zoals CIDRAM om uw systeem of CMS, maar het makkelijkste is om gewoon omvatten voor het script aan het begin van een kern bestand van uw systeem of CMS (een die het algemeen altijd zal worden geladen wanneer iemand heeft toegang tot een pagina in uw website) met behulp van een `require` of `include` opdracht. Meestal is dit wel iets worden opgeslagen in een bestandsmap zoals `/includes`, `/assets` of `/functions`, en zal vaak zijn vernoemd iets als `init.php`, `common_functions.php`, `functions.php` of soortgelijk. U nodig om te bepalen welk bestand dit is voor uw situatie; Als u problemen ondervindt bij het bepalen van dit voor uzelf, ga naar de CIDRAM kwesties/issues pagina op Github voor assistentie. Om dit te doen [te gebruiken `require` of `include`], plaatst u de volgende regel code aan het begin op die kern bestand, vervangen van de string die binnen de aanhalingstekens met het exacte adres van het `loader.php` bestand (lokaal adres, niet het HTTP-adres; zal vergelijkbaar zijn met de eerder genoemde vault adres).

`<?php require '/user_name/public_html/cidram/loader.php'; ?>`

Opslaan bestand, sluiten, heruploaden.

-- OF ALTERNATIEF --

Als u gebruik een Apache webserver en als u heeft toegang om `php.ini`, u kunt gebruiken de `auto_prepend_file` richtlijn naar prepend CIDRAM wanneer een PHP verzoek wordt gemaakt. Zoiets als:

`auto_prepend_file = "/user_name/public_html/cidram/loader.php"`

Of dit in het `.htaccess` bestand:

`php_value auto_prepend_file "/user_name/public_html/cidram/loader.php"`

6) Dat is alles! :-)

---


###3. <a name="SECTION3"></a>HOE TE GEBRUIKEN

CIDRAM moet blokkeren ongewenste verzoeken naar uw website automatisch zonder enige handmatige hulp, afgezien van de eerste installatie.

Updaten gebeurt met de hand, en u kunt aanpassen uw configuratie en aanpassen de CIDRs dat zal worden geblokkeerd door het modificeren van het configuratiebestand en/of uw handtekening bestanden.

Als u tegenkomen een valse positieven, neem dan contact met mij op om me te laten weten.

---


###4. <a name="SECTION4"></a>BESTANDEN IN DIT PAKKET

Het volgende is een lijst van alle bestanden die moeten worden opgenomen in de gearchiveerde kopie van dit script als u gedownload het, alle bestanden die kunt mogelijk worden gemaakt als resultaat van uw gebruik van dit script, samen met een korte beschrijving van wat al deze bestanden zijn voor.

Bestand | Beschrijving
----|----
/.gitattributes | Een Github project bestand (niet vereist voor een goede werking van het script).
/Changelog.txt | Een overzicht van wijzigingen in het script tussen verschillende versies (niet vereist voor een goede werking van het script).
/composer.json | Composer/Packagist informatie (niet vereist voor een goede werking van het script).
/LICENSE.txt | Een kopie van de GNU/GPLv2 licentie (niet vereist voor een goede werking van het script).
/loader.php | Lader. Dit is wat u zou moeten worden inhaken in (essentieel)!
/README.md | Project beknopte informatie.
/web.config | Een ASP.NET-configuratiebestand (in dit geval, naar het bestandsmap "vault" te beschermen tegen toegang door niet-geautoriseerde bronnen indien het script is geïnstalleerd op een server op basis van ASP.NET technologieën).
/_docs/ | Documentatie bestandsmap (bevat verschillende bestanden).
/_docs/readme.ar.md | Arabisch documentatie.
/_docs/readme.de.md | Duitse documentatie.
/_docs/readme.en.md | Engels documentatie.
/_docs/readme.es.md | Spaanse documentatie.
/_docs/readme.fr.md | Franse documentatie.
/_docs/readme.id.md | Indonesisch documentatie.
/_docs/readme.it.md | Italiaanse documentatie.
/_docs/readme.ja.md | Japanse documentatie.
/_docs/readme.nl.md | Nederlandse documentatie.
/_docs/readme.pt.md | Portugees documentatie.
/_docs/readme.ru.md | Russische documentatie.
/_docs/readme.vi.md | Vietnamees documentatie.
/_docs/readme.zh-TW.md | Chinees (traditioneel) documentatie.
/_docs/readme.zh.md | Chinees (vereenvoudigd) documentatie.
/vault/ | Vault bestandsmap (bevat verschillende bestanden).
/vault/.htaccess | Een hypertext toegang bestand (in dit geval, om gevoelige bestanden die behoren tot het script te beschermen tegen toegang door niet-geautoriseerde bronnen).
/vault/cache.dat | Cache data/gegevens.
/vault/cli.php | CLI-handler.
/vault/config.ini.RenameMe | Configuratiebestand; Bevat alle configuratieopties van CIDRAM, het vertellen wat te doen en hoe om te werken correct (hernoemen om te activeren).
/vault/config.php | Configuratie-handler.
/vault/functions.php | Functies bestand (essentieel).
/vault/hashes.dat | Bevat een lijst met geaccepteerde hashes (relevant zijn voor de reCAPTCHA functie; alleen gegenereerd als de reCAPTCHA functie is ingeschakeld).
/vault/ignore.dat | Genegeerd file (gebruikt om aan te geven welke handtekening secties CIDRAM moeten negeren).
/vault/ipbypass.dat | Bevat een lijst met IP rondwegen (relevant zijn voor de reCAPTCHA functie; alleen gegenereerd als de reCAPTCHA functie is ingeschakeld).
/vault/ipv4.dat | IPv4 handtekeningen bestand.
/vault/ipv4_custom.dat.RenameMe | IPv4 aangepaste handtekeningen bestand (hernoemen om te activeren).
/vault/ipv6.dat | IPv6 handtekeningen bestand.
/vault/ipv6_custom.dat.RenameMe | IPv6 aangepaste handtekeningen bestand (hernoemen om te activeren).
/vault/lang.php | Taal-handler.
/vault/lang/ | Bevat CIDRAM taaldata/taalgegevens.
/vault/lang/.htaccess | Een hypertext toegang bestand (in dit geval, om gevoelige bestanden die behoren tot het script te beschermen tegen toegang door niet-geautoriseerde bronnen).
/vault/lang/lang.ar.cli.php | Arabisch taaldata/taalgegevens voor CLI.
/vault/lang/lang.ar.php | Arabisch taaldata/taalgegevens.
/vault/lang/lang.de.cli.php | Duitse taaldata/taalgegevens voor CLI.
/vault/lang/lang.de.php | Duitse taaldata/taalgegevens.
/vault/lang/lang.en.cli.php | Engels taaldata/taalgegevens voor CLI.
/vault/lang/lang.en.php | Engels taaldata/taalgegevens.
/vault/lang/lang.es.cli.php | Spaanse taaldata/taalgegevens voor CLI.
/vault/lang/lang.es.php | Spaanse taaldata/taalgegevens.
/vault/lang/lang.fr.cli.php | Franse taaldata/taalgegevens voor CLI.
/vault/lang/lang.fr.php | Franse taaldata/taalgegevens.
/vault/lang/lang.id.cli.php | Indonesisch taaldata/taalgegevens voor CLI.
/vault/lang/lang.id.php | Indonesisch taaldata/taalgegevens.
/vault/lang/lang.it.cli.php | Italiaanse taaldata/taalgegevens voor CLI.
/vault/lang/lang.it.php | Italiaanse taaldata/taalgegevens.
/vault/lang/lang.ja.cli.php | Japanse taaldata/taalgegevens voor CLI.
/vault/lang/lang.ja.php | Japanse taaldata/taalgegevens.
/vault/lang/lang.nl.cli.php | Nederlandse taaldata/taalgegevens voor CLI.
/vault/lang/lang.nl.php | Nederlandse taaldata/taalgegevens.
/vault/lang/lang.pt.cli.php | Portugees taaldata/taalgegevens voor CLI.
/vault/lang/lang.pt.php | Portugees taaldata/taalgegevens.
/vault/lang/lang.ru.cli.php | Russische taaldata/taalgegevens voor CLI.
/vault/lang/lang.ru.php | Russische taaldata/taalgegevens.
/vault/lang/lang.vi.cli.php | Vietnamees taaldata/taalgegevens voor CLI.
/vault/lang/lang.vi.php | Vietnamees taaldata/taalgegevens.
/vault/lang/lang.zh-tw.cli.php | Chinees (traditioneel) taaldata/taalgegevens voor CLI.
/vault/lang/lang.zh-tw.php | Chinees (traditioneel) taaldata/taalgegevens.
/vault/lang/lang.zh.cli.php | Chinees (vereenvoudigd) taaldata/taalgegevens voor CLI.
/vault/lang/lang.zh.php | Chinees (vereenvoudigd) taaldata/taalgegevens.
/vault/outgen.php | Uitvoer generator.
/vault/recaptcha.php | reCAPTCHA module.
/vault/rules_as6939.php | Aangepaste regels bestand voor AS6939.
/vault/rules_softlayer.php | Aangepaste regels bestand voor Soft Layer.
/vault/rules_specific.php | Aangepaste regels bestand voor sommige specifiek CIDRs.
/vault/salt.dat | Zout bestand (gebruikt door sommige perifere functionaliteit; alleen gegenereerd indien nodig).
/vault/template.html | Sjabloonbestand; Sjabloon voor HTML-uitvoer geproduceerd door de CIDRAM uitvoer generator.
/vault/template_custom.html | Sjabloonbestand; Sjabloon voor HTML-uitvoer geproduceerd door de CIDRAM uitvoer generator.

---


###5. <a name="SECTION5"></a>CONFIGURATIEOPTIES
Het volgende is een lijst van variabelen die in de `config.ini` configuratiebestand van CIDRAM, samen met een beschrijving van hun doel en functie.

####"general" (Categorie)
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
- Waar het IP-adres van het aansluiten verzoek te vinden? (Handig voor diensten zoals Cloudflare en dergelijke) Standaard = REMOTE_ADDR. WAARSCHUWING: Verander dit niet tenzij u weet wat u doet!

"forbid_on_block"
- Welke headers moet CIDRAM reageren met bij het blokkeren van verzoeken? False/200 = 200 OK [Standaard]; True = 403 Forbidden (Verboden); 503 = 503 Service unavailable (Service onbeschikbaar).

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

####"signatures" (Categorie)
Configuratie voor handtekeningen.

"ipv4"
- Een lijst van de IPv4 handtekening bestanden dat CIDRAM moet proberen om te gebruiken, afgebakend door komma's. U kunt items hier toevoegen Als u wilt meer IPv4 signature files in CIDRAM bevatten.

"ipv6"
- Een lijst van de IPv6 handtekening bestanden dat CIDRAM moet proberen om te gebruiken, afgebakend door komma's. U kunt items hier toevoegen Als u wilt meer IPv6 signature files in CIDRAM bevatten.

"block_cloud"
- Blokkeren CIDRs geïdentificeerd als behorend tot webhosting/cloud-diensten? Als u een api te bedienen vanaf uw website of als u verwacht dat andere websites aan te sluiten op uw website, dit richtlijn moet worden ingesteld op false. Als u niet, dan, dit richtlijn moet worden ingesteld op true.

"block_bogons"
- Blokkeren bogon/martian CIDRs? Als u verwacht aansluitingen om uw website vanuit uw lokale netwerk, vanuit localhost, of vanuit uw LAN, dit richtlijn moet worden ingesteld op false. Als u niet verwacht deze aansluitingen, dit richtlijn moet worden ingesteld op true.

"block_generic"
- Blokkeren CIDRs algemeen aanbevolen voor blacklisting? Dit omvat alle handtekeningen die niet zijn gemarkeerd als onderdeel van elke van de andere, meer specifieke handtekening categorieën.

"block_proxies"
- Blokkeren CIDRs geïdentificeerd als behorend tot proxy-services? Als u vereisen dat gebruikers kan toegang tot uw website van anonieme proxy-services, dit richtlijn moet worden ingesteld op false. Anders, als u niet nodig anonieme proxies, dit richtlijn moet worden ingesteld op true als een middel ter verbetering van de beveiliging.

"block_spam"
- Blokkeren CIDRs geïdentificeerd als zijnde hoog risico voor spam? Tenzij u problemen ondervindt wanneer u dit doet, in algemeen, dit moet altijd worden ingesteld op true.

####"recaptcha" (Categorie)
Optioneel, u kan uw gebruikers te voorzien van een manier om de "Toegang Geweigerd" pagina te omzeilen, door middel van het invullen van een reCAPTCHA instantie, als u wilt om dit te doen. Dit kan helpen om een aantal van de risico's die samenhangen met valse positieven te beperken, in die situaties waar we niet helemaal zeker of er een verzoek is voortgekomen uit een machine of een mens.

Vanwege de risico's die samenhangen met het verstrekken van een manier voor eindgebruikers om de "Toegang Geweigerd" pagina te omzeilen, algemeen, ik zou adviseren tegen het inschakelen van deze functie tenzij u voelt het om nodig om dit te doen. Situaties waarin het nodig zou zijn: Als uw website heeft klanten/gebruikers die moeten toegang hebben tot uw website, en als dit is iets dat niet kan worden gecompromitteerd, maar als deze klanten/gebruikers deze verbinding maakt vanuit een vijandig netwerk dat mogelijk ook zou kunnen dragen ongewenste verkeer, en het blokkeren van deze ongewenste verkeer is ook iets dat niet kan worden gecompromitteerd, in deze bijzondere no-win situaties, de functie reCAPTCHA kan van pas komen als een middel van het toestaan van de wenselijke klanten/gebruikers, terwijl het vermijden van het het ongewenste verkeer vanaf hetzelfde netwerk. Dat gezegd hebbende hoewel, gezien het feit dat de bestemming van een CAPTCHA is om onderscheid te maken tussen mensen en niet-mensen, de functie reCAPTCHA zou alleen helpen in deze no-win situaties als zou veronderstellen dat deze ongewenste verkeer is niet-humaan (b.v., spambots, schrapers, hack gereedschappen, geautomatiseerde verkeer), in tegenstelling tot ongewenst menselijk verkeer (zoals menselijke spammers, hackers, c.s.).

Om een "site key" en een "secret key" te verkrijgen (vereist voor het gebruik van reCAPTCHA), ga naar: [https://developers.google.com/recaptcha/](https://developers.google.com/recaptcha/)

"usemode"
- Bepaalt hoe CIDRAM reCAPTCHA moet gebruiken.
- 0 = reCAPTCHA is volledig uitgeschakeld (standaard).
- 1 = reCAPTCHA is ingeschakeld voor alle handtekeningen.
- 2 = reCAPTCHA is ingeschakeld alleen voor handtekeningen die behoren tot secties speciaal gemarkeerde binnen de handtekening bestanden.
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

####"template_data" (Categorie)
Richtlijnen/Variabelen voor sjablonen en thema's.

Betreft de HTML-uitvoer gebruikt om de "Toegang Geweigerd" pagina te genereren. Als u gebruik aangepaste thema's voor CIDRAM, HTML-uitvoer is afkomstig van de `template_custom.html` bestand, en alternatief, HTML-uitvoer is afkomstig van de `template.html` bestand. Variabelen geschreven om dit sectie van het configuratiebestand worden geïnterpreteerd aan de HTML-uitvoer door middel van het vervangen van variabelennamen omringd door accolades gevonden binnen de HTML-uitvoer met de bijbehorende variabele gegevens. Bijvoorbeeld, waar `foo="bar"`, elk geval van `<p>{foo}</p>` gevonden binnen de HTML-uitvoer `<p>bar</p>` zal worden.

"css_url"
- De sjabloonbestand voor aangepaste thema's maakt gebruik van externe CSS-eigenschappen, terwijl de sjabloonbestand voor het standaardthema maakt gebruik van interne CSS-eigenschappen. Om CIDRAM instrueren om de sjabloonbestand voor aangepaste thema's te gebruiken, geef het openbare HTTP-adres van uw aangepaste thema's CSS-bestanden via de `css_url` variabele. Als u dit variabele leeg laat, CIDRAM zal de sjabloonbestand voor de standaardthema te gebruiken.

---


###6. <a name="SECTION6"></a>HANDTEKENINGFORMAAT

####6.0 BASICS

Een beschrijving van het formaat en de structuur van de handtekeningen gebruikt door CIDRAM kan gevonden worden gedocumenteerd in platte tekst binnen een van de twee aangepaste handtekeningen bestanden. Raadpleeg de documentatie om meer te leren over het formaat en de structuur van de handtekeningen van CIDRAM.

Alle IPv4 handtekeningen volgt het formaat: `xxx.xxx.xxx.xxx/yy [Function] [Param]`.
- `xxx.xxx.xxx.xxx` vertegenwoordigt het begin van het CIDR blok (de octetten van de eerste IP-adres in het blok).
- `yy` vertegenwoordigt het CIDR blokgrootte [1-32].
- `[Function]` instrueert het script wat te doen met de handtekening (hoe de handtekening moet worden beschouwd).
- `[Param]` vertegenwoordigt alle aanvullende informatie dat kan worden verlangd door `[Function]`.

Alle IPv6 handtekeningen volgt het formaat: `xxxx:xxxx:xxxx:xxxx::xxxx/yy [Function] [Param]`.
- `xxxx:xxxx:xxxx:xxxx::xxxx` vertegenwoordigt het begin van het CIDR blok (de octetten van de eerste IP-adres in het blok). Compleet notatie en verkorte notatie zijn beide aanvaardbaar (en ieder moet volg de juiste en relevante normen van IPv6-notatie, maar met één uitzondering: een IPv6-adres kan nooit beginnen met een afkorting wanneer het wordt gebruikt in een handtekening voor dit script, vanwege de manier waarop CIDRs door het script zijn gereconstrueerd; Bijvoorbeeld, `::1/128` moet worden uitgedrukt, bij gebruik in een handtekening, als `0::1/128`, en `::0/128` uitgedrukt als `0::/128`).
- `yy` vertegenwoordigt het CIDR blokgrootte [1-128].
- `[Function]` instrueert het script wat te doen met de handtekening (hoe de handtekening moet worden beschouwd).
- `[Param]` vertegenwoordigt alle aanvullende informatie dat kan worden verlangd door `[Function]`.

De handtekening bestanden voor CIDRAM MOET gebruiken Unix-stijl regeleinden (`%0A`, or `\n`)! Andere soorten/stijlen van regeleinden (bv, Windows `%0D%0A` of `\r\n` regeleinden, Mac `%0D` of `\r` regeleinden, ezv) KAN worden gebruikt, maar zijn NIET voorkeur. Non-Unix-stijl regeleinden wordt genormaliseerd naar Unix-stijl regeleinden door het script.

Nauwkeurig en correct CIDR-notatie is vereist, anders zal het script NIET de handtekeningen herkennen. Tevens, alle CIDR handtekeningen van dit script MOET beginnen met een IP-adres waarvan het IP-nummer kan gelijkmatig in het blok divisie vertegenwoordigd door haar CIDR blokgrootte verdelen (bv, als u wilde alle IP-adressen van `10.128.0.0` naar `11.127.255.255` te blokkeren, `10.128.0.0/8` zou door het script NIET worden herkend, maar `10.128.0.0/9` en `11.0.0.0/9` in combinatie, ZOU door het script worden herkend).

Alles wat in de handtekening bestanden niet herkend als een handtekening noch als handtekening-gerelateerde syntaxis door het script worden GENEGEERD, daarom dit betekent dat om veilig alle niet-handtekening gegevens die u wilt in de handtekening bestanden u kunnen zetten zonder verbreking van de handtekening bestanden of de script. Reacties zijn in de handtekening bestanden aanvaardbare, en geen speciale opmaak of formaat is vereist voor hen. Shell-stijl hashing voor commentaar heeft de voorkeur, maar is niet afgedwongen; Functioneel, het maakt geen verschil voor het script ongeacht of u kiest voor Shell-stijl hashing om commentaar te gebruiken, maar gebruik van Shell-stijl hashing helpt IDE's en platte tekst editors om correct te markeren de verschillende delen van de handtekening bestanden (en dus, Shell-stijl hashing kan helpen als een visueel hulpmiddel tijdens het bewerken).

Mogelijke waarden van `[Function]` zijn als volgt:
- Run
- Whitelist
- Greylist
- Deny

Als "Run" wordt gebruikt, als de handtekening wordt geactiveerd, het script zal proberen (gebruiken een `require_once` statement) om een externe PHP-script uit te voeren, gespecificeerd door de `[Param]` waarde (de werkmap moet worden de "/vault/" map van het script).

Voorbeeld: `127.0.0.0/8 Run example.php`

Dit kan handig zijn als u wilt, voor enige specifieke IPs en/of CIDRs, om specifieke PHP-code uit te voeren.

Als "Whitelist" wordt gebruikt, als de handtekening wordt geactiveerd, het script zal alle detecties resetten (als er is al enige detecties) en breek de testfunctie. `[Param]` worden genegeerd. Deze functie werkt als een whitelist, om te voorkomen dat bepaalde IP-adressen en/of CIDRs van wordt gedetecteerd.

Voorbeeld: `127.0.0.1/32 Whitelist`

Als "Greylist" wordt gebruikt, als de handtekening wordt geactiveerd, het script zal alle detecties resetten (als er is al enige detecties) en doorgaan naar de volgende handtekening bestand te gaan met verwerken. `[Param]` worden genegeerd.

Voorbeeld: `127.0.0.1/32 Greylist`

Als "Deny" wordt gebruikt, als de handtekening wordt geactiveerd, veronderstelling dat er geen whitelist handtekening is geactiveerd voor het opgegeven IP-adres en/of opgegeven CIDR, toegang tot de beveiligde pagina wordt ontzegd. "Deny" is wat u wilt gebruiken om een IP-adres en/of CIDR range te daadwerkelijk blokkeren. Wanneer enige handtekeningen zijn geactiveerd er dat gebruik "Deny", de "Toegang Geweigerd" pagina van het script zal worden gegenereerd en het verzoek naar de beveiligde pagina wordt gedood.

De `[Param]` waarde geaccepteerd door "Deny" zal worden parsed aan de "Toegang Geweigerd" pagina-uitgang, geleverd aan de klant/gebruiker als de genoemde reden voor hun toegang tot de gevraagde pagina worden geweigerd. Het kan een korte en eenvoudige zin zijn, uit te leggen waarom u hebt gekozen om ze te blokkeren (iets moeten volstaan, zelfs een simpele "Ik wil je niet op mijn website"), of een van het handjevol korte woorden geleverd door het script, dat als gebruikt, wordt vervangen door het script met een voorbereide toelichting waarom de klant/gebruiker is geblokkeerd.

De voorbereide toelichtingen hebben i18n ondersteuning en kan worden vertaald door het script op basis van de taal die u opgeeft naar de `lang` richtlijn van het script configuratie. Tevens, u kunt het script instrueren om "Deny" handtekeningen te negeren op basis van hun `[Param]` waarde (als ze gebruik maken van deze korte woorden) via de richtlijnen gespecificeerd door het script configuratie (elk kort woord heeft een overeenkomstige richtlijn te verwerken overeenkomende handtekeningen of te negeren hen). `[Param]` waarden dat niet gebruiken deze korte woorden, echter, hebben geen i18n ondersteuning en daarom zal NIET worden vertaald door het script, en tevens, en zijn niet direct controleerbaar door het script configuratie.

De beschikbare korte woorden zijn:
- Bogon
- Cloud
- Generic
- Proxy
- Spam

####6.1 ETIKETTEN

Als u wilt uw aangepaste handtekeningen te splitsen in afzonderlijke secties, u kunt deze individuele secties te identificeren om het script door toevoeging van een "sectie etiket" onmiddellijk na de handtekeningen van elke sectie, samen met de naam van uw handtekening sectie (zie het onderstaande voorbeeld).

```
# "Section 1."
1.2.3.4/32 Deny Bogon
2.3.4.5/32 Deny Cloud
4.5.6.7/32 Deny Generic
5.6.7.8/32 Deny Spam
6.7.8.9/32 Deny Proxy
Tag: Section 1
```

Om sectie etiketteren te breken en zodat de etiketten zijn niet onjuist geïdentificeerd met handtekening secties uit eerder in de handtekening bestanden, gewoon ervoor zorgen dat er ten minste twee opeenvolgende regeleinden tussen uw etiket en uw eerdere handtekening secties. Een ongeëtiketteerd handtekeningen wordt standaard om "IPv4" of "IPv6" (afhankelijk van welke soorten handtekeningen worden geactiveerd).

```
1.2.3.4/32 Deny Bogon
2.3.4.5/32 Deny Cloud

4.5.6.7/32 Deny Generic
5.6.7.8/32 Deny Spam
Tag: Section 1
```

In het bovenstaande voorbeeld `1.2.3.4/32` en `2.3.4.5/32` zal worden geëtiketteerd als "IPv4", terwijl `4.5.6.7/32` en `5.6.7.8/32` zal worden geëtiketteerd als "Section 1".

Als u wilt handtekeningen te vervallen na verloop van tijd, op soortgelijke wijze als sectie etiketten, u kan een "vervaltijd etiket" gebruikt om aan te geven wanneer handtekeningen moet niet meer geldig. Vervaltijd etiketten gebruiken het formaat "JJJJ.MM.DD" (zie het onderstaande voorbeeld).

```
# "Section 1."
1.2.3.4/32 Deny Generic
2.3.4.5/32 Deny Generic
Expires: 2016.12.31
```

Sectie etiketten en vervaltijd etiketten kunnen worden gebruikt in combinatie, en beide zijn optioneel (zie het onderstaande voorbeeld).

```
# "Example Section."
1.2.3.4/32 Deny Generic
Tag: Example Section
Expires: 2016.12.31
```

####6.2 YAML

#####6.2.0 YAML BASICS

Een vereenvoudigde vorm van YAML markup kan worden gebruikt in handtekening bestanden voor het bepalen van gedragingen en specifieke instellingen voor afzonderlijke handtekening secties. Dit kan handig zijn als u de waarde van uw configuratie richtlijnen willen afwijken op basis van individuele handtekeningen en handtekening secties (bijvoorbeeld; als u wilt om een e-mailadres te leveren voor support tickets voor alle gebruikers geblokkeerd door een bepaalde handtekening, maar wil niet om een e-mailadres te leveren voor support tickets voor de gebruikers geblokkeerd door andere handtekeningen; als u wilt een specifieke handtekeningen te leiden tot een pagina redirect; als u wilt een handtekening sectie voor gebruik met reCAPTCHA te markeren; als u wilt om geblokkeerde toegang pogingen te loggen in afzonderlijke bestanden op basis van individuele handtekeningen en/of handtekening secties).

Het gebruik van YAML markup in de handtekening bestanden is volledig optioneel (d.w.z., u kan het gebruiken als u wenst te doen, maar u bent niet verplicht om dit te doen), en is in staat om de meeste (maar niet alle) configuratie richtlijnen hefboomeffect.

Notitie: YAML markup implementatie in CIDRAM is zeer simplistisch en zeer beperkt; Het is bedoeld om de specifieke eisen van CIDRAM te voldoen op een manier dat heeft de vertrouwdheid van YAML markup, maar noch volgt noch voldoet aan de officiële specificaties (en zal daarom niet zich op dezelfde wijze als grondiger implementaties elders, en is misschien niet geschikt voor alle andere projecten elders).

In CIDRAM, YAML markup segmenten worden geïdentificeerd aan het script door drie streepjes ("---"), en eindigen naast hun bevattende handtekening secties door dubbel-regeleinden. Een typische YAML markup segment binnen een handtekening sectie bestaat uit drie streepjes op een lijn onmiddellijk na de lijst van CIDRs en elke etiketten, gevolgd door een tweedimensionale lijst van sleutel-waarde paren (eerste dimensie, configuratie richtlijn categorieën; tweede dimensie, configuratie richtlijnen) voor welke configuratie richtlijnen moeten worden gewijzigd (en om welke waarden) wanneer een handtekening in die handtekening sectie wordt geactiveerd (zie de onderstaande voorbeelden).

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

#####6.2.1 HOE OM HANDTEKENING SECTIES TE MARKEREN VOOR GEBRUIK MET reCAPTCHA

Als "usemode" is 0 of 1, handtekening secties hoeven niet voor gebruik met reCAPTCHA te markeren (omdat ze al wil of wil niet gebruik reCAPTCHA, afhankelijk van deze instelling).

Als "usemode" is 2, om handtekening secties te markeren voor gebruik met reCAPTCHA, een invoer wordt opgenomen in het YAML segment voor dat handtekening sectie (zie het onderstaande voorbeeld).

```
# This section will use reCAPTCHA.
1.2.3.4/32 Deny Generic
2.3.4.5/32 Deny Generic
Tag: reCAPTCHA-Enabled
---
recaptcha:
 enabled: true
```

Notitie: Een reCAPTCHA instantie zal ALLEEN worden aangeboden aan de gebruiker als reCAPTCHA is ingeschakeld (met "usemode" als 1, of "usemode" als 2 met "enabled" als true), en als precies ÉÉN handtekening is geactiveerd (niet meer, niet minder; als er meerdere handtekeningen worden geactiveerd, een reCAPTCHA instantie zal NIET worden aangeboden).

####6.3 EXTRA INFORMATIE

Bovendien, als u wilt CIDRAM om enkele specifieke secties in iedereen van de handtekening bestanden te negeren, kunt u het `ignore.dat` bestand gebruiken om specificeren welke secties te negeren. Op een nieuwe regel, schrijven `Ignore`, gevolgd door een spatie, gevolgd door de naam van de sectie die u wilt CIDRAM te negeren (zie het onderstaande voorbeeld).

```
Ignore Section 1
```

Raadpleeg de aangepaste handtekening bestanden voor meer informatie.

---


###7. <a name="SECTION7"></a>VEELGESTELDE VRAGEN (FAQ)

####Wat is een "vals positieve"?

De term "vals positieve" (*alternatief: "vals positieve fout"; "vals alarm"*; Engels: *false positive*; *false positive error*; *false alarm*), zeer eenvoudig beschreven, en een algemene context, wordt gebruikt bij het testen voor een toestand, om verwijst naar om de resultaten van die test, wanneer de resultaten positief zijn (d.w.z, de toestand wordt vastgesteld als "positief"), maar wordt verwacht "negatief" te zijn (d.w.z, de toestand in werkelijkheid is "negatief"). Een "vals positieve" analoog aan "huilende wolf" kan worden beschouwd (waarin de toestand wordt getest, is of er een wolf in de buurt van de kudde, de toestand is "vals" in dat er geen wolf in de buurt van de kudde, en de toestand wordt gerapporteerd als "positief" door de herder door middel van schreeuwen "wolf, wolf"), of analoog aan situaties in medische testen waarin een patiënt gediagnosticeerd als met een ziekte of aandoening, terwijl het in werkelijkheid, hebben ze geen ziekte of aandoening.

Enkele andere termen die worden gebruikt zijn "waar positieve", "waar negatieve" en "vals negatieve". Een "waar positieve" verwijst naar wanneer de resultaten van de test en de huidige staat van de toestand zijn beide waar (of "positief"), and a "waar negatieve" verwijst naar wanneer de resultaten van de test en de huidige staat van de toestand zijn beide vals (of "negatief"); En "waar positieve" of en "waar negatieve" wordt beschouwd als een "correcte gevolgtrekking" zijn. De antithese van een "vals positieve" is een "vals negatieve"; Een "vals negatieve" verwijst naar wanneer de resultaten van de test is negatief (d.w.z, de aandoening wordt vastgesteld als "negatief"), maar wordt verwacht "positief" te zijn (d.w.z, de toestand in werkelijkheid is "positief").

In de context van CIDRAM, deze termen verwijzen naar de handtekeningen van CIDRAM en wat/wie ze blokkeren. Wanneer CIDRAM blokkeert een IP-adres, als gevolg van slechte, verouderde of onjuiste handtekening, maar moet niet hebben gedaan, of wanneer het doet om de verkeerde redenen, we verwijzen naar deze gebeurtenis als een "vals positieve". Wanneer CIDRAM niet in slaagt te blokkeren om een IP-adres dat had moeten worden geblokkeerd, als gevolg van onvoorziene bedreigingen, ontbrekende handtekeningen of tekorten in zijn handtekeningen, we verwijzen naar deze gebeurtenis als een "gemiste detectie" (dat is analoog aan een "vals negatieve").

Dit kan worden samengevat in de onderstaande tabel:

&nbsp; | CIDRAM moet *NIET* een IP-adres te blokkeren | CIDRAM *MOET* een IP-adres te blokkeren
---|---|---
CIDRAM *NIET* doet blokkeren van een IP-adres | Waar negatieve (correcte gevolgtrekking) | Gemiste detectie (analoog aan vals negatieve)
CIDRAM *DOET* blokkeren van een IP-adres | __Vals positieve__ | Waar positieve (correcte gevolgtrekking)

---


Laatste Bijgewerkt: 11 Oktober 2016 (2016.10.11).
