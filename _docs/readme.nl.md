## Documentatie voor CIDRAM (Nederlandse).

### Inhoud
- 1. [PREAMBULE](#SECTION1)
- 2. [HOE TE INSTALLEREN](#SECTION2)
- 3. [HOE TE GEBRUIKEN](#SECTION3)
- 4. [BESTANDEN IN DIT PAKKET](#SECTION4)
- 5. [CONFIGURATIEOPTIES](#SECTION5)
- 6. [HANDTEKENINGFORMAAT](#SECTION6)

---


###1. <a name="SECTION1"></a>PREAMBULE

CIDRAM (Classless Inter-Domain Routing Access Manager) is een PHP-script ontworpen om websites te beschermen door het blokkeren van verzoeken afkomstig van IP-adressen beschouwd als bronnen van ongewenste verkeer, inclusief (maar niet gelimiteerd tot) het verkeer van niet-menselijke toegang eindpunten, cloud-diensten, spambots, schrapers/scrapers, ezv. Het doet dit door het berekenen van de mogelijke CIDRs van de IP-adressen geleverde van binnenkomende verzoeken en dan het vergelijken van deze mogelijke CIDRs tegen zijn handtekening bestanden (deze handtekening bestanden bevatten lijsten van CIDRs van IP-adressen beschouwd als bronnen van ongewenste verkeer); Als overeenkomsten worden gevonden, de verzoeken worden geblokkeerd.

CIDRAM COPYRIGHT 2016 en verder GNU/GPLv2 van Caleb M (Maikuolan).

Dit script is gratis software; u kunt, onder de voorwaarden van de GNU General Public License zoals gepubliceerd door de Free Software Foundation, herdistribueren en/of wijzigen dit; ofwel versie 2 van de Licentie, of (naar uw keuze) enige latere versie. Dit script wordt gedistribueerd in de hoop dat het nuttig zal zijn, maar ZONDER ENIGE GARANTIE; zonder zelfs de impliciete garantie van VERKOOPBAARHEID of GESCHIKTHEID VOOR EEN BEPAALD DOEL. Zie de GNU General Public License voor meer informatie, gelegen in het `LICENSE.txt` bestand en ook beschikbaar uit:
- <http://www.gnu.org/licenses/>.
- <http://opensource.org/licenses/>.

Dit document en de bijbehorende pakket kunt gedownload gratis zijn van [GitHub](https://github.com/Maikuolan/CIDRAM/).

---


###2. <a name="SECTION2"></a>HOE TE INSTALLEREN

Ik hoop te stroomlijnen dit proces door maken een installateur op een bepaald punt in de niet al te verre toekomst, maar tot die tijd, volg deze instructies te werken CIDRAM om meeste systemen en CMS:

1) Omdat u zijn lezen dit, ik ben ervan uit u al gedownload een gearchiveerde kopie van het script, uitgepakt zijn inhoud en heeft het ergens op uw lokale computer. Vanaf hier, u nodig te bepalen waar op uw host of CMS die inhoud te plaatsen. Een bestandsmap zoals `/public_html/cidram/` of soortgelijk (hoewel, het is niet belangrijk welke u kiest, zolang het is iets veilig en iets waar u blij mee bent) zal volstaan. *Voordat u het uploaden begint, lees verder..*

2) Hernoemen `config.ini.RenameMe` naar `config.ini` (gelegen binnen `vault`), en facultatief (sterk aanbevolen voor ervaren gebruikers, maar niet aan te raden voor beginners of voor de onervaren), open het (dit bestand bevat alle beschikbare CIDRAM configuratie opties; boven elke optie moet een korte opmerking te beschrijven wat het doet en wat het voor). Pas deze opties als het u past, volgens welke geschikt is voor uw configuratie. Sla het bestand, sluiten.

3) Upload de inhoud (CIDRAM en zijn bestanden) naar het bestandsmap die u zou op eerder besloten (u nodig niet de `*.txt`/`*.md` bestanden opgenomen, maar meestal, u moeten uploaden alles).

4) CHMOD het bestandsmap `vault` naar "777". De belangrijkste bestandsmap opslaan van de inhoud (degene die u eerder koos), gewoonlijk, kunt worden genegeerd, maar CHMOD-status moet worden gecontroleerd als u machtigingen problemen heeft in het verleden met uw systeem (standaard, moet iets zijn als "755").

5) Volgende, u nodig om "haak" CIDRAM om uw systeem of CMS. Er zijn verschillende manieren waarop u kunt "haak" scripts zoals CIDRAM om uw systeem of CMS, maar het makkelijkste is om gewoon omvatten voor het script aan het begin van een kern bestand van uw systeem of CMS (een die het algemeen altijd zal worden geladen wanneer iemand heeft toegang tot een pagina in uw website) met behulp van een `require` of `include` opdracht. Meestal is dit wel iets worden opgeslagen in een bestandsmap zoals `/includes`, `/assets` of `/functions`, en zal vaak zijn vernoemd iets als `init.php`, `common_functions.php`, `functions.php` of soortgelijk. U nodig om te bepalen welk bestand dit is voor uw situatie; Als u problemen ondervindt in het werken dit uit voor uzelf, bezoek de CIDRAM kwesties (issues) pagina op GitHub. Om dit te doen [te gebruiken `require` of `include`], plaatst u de volgende regel code aan het begin op die kern bestand, vervangen van de string die binnen de aanhalingstekens met het exacte adres van het `loader.php` bestand (lokaal adres, niet het HTTP-adres; zal vergelijkbaar zijn met de eerder genoemde vault adres).

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
/.gitattributes | Een GitHub project bestand (niet vereist voor een goede werking van het script).
/Changelog.txt | Een overzicht van wijzigingen in het script tussen verschillende versies (niet vereist voor een goede werking van het script).
/composer.json | Composer/Packagist informatie (niet vereist voor een goede werking van het script).
/LICENSE.txt | Een kopie van de GNU/GPLv2 licentie (niet vereist voor een goede werking van het script).
/loader.php | Lader. Dit is wat u zou moeten worden inhaken in (essentieel)!
/README.md | Project beknopte informatie.
/web.config | Een ASP.NET-configuratiebestand (in dit geval, naar het bestandsmap "vault" te beschermen tegen toegang door niet-geautoriseerde bronnen indien het script is geïnstalleerd op een server op basis van ASP.NET technologieën).
/_docs/ | Documentatie bestandsmap (bevat verschillende bestanden).
/_docs/readme.de.md | Duitse documentatie.
/_docs/readme.en.md | Engels documentatie.
/_docs/readme.es.md | Spaanse documentatie.
/_docs/readme.fr.md | Franse documentatie.
/_docs/readme.id.md | Indonesisch documentatie.
/_docs/readme.it.md | Italiaanse documentatie.
/_docs/readme.nl.md | Nederlandse documentatie.
/_docs/readme.pt.md | Portugees documentatie.
/_docs/readme.zh-TW.md | Chinees (Traditioneel) documentatie.
/_docs/readme.zh.md | Chinees (Vereenvoudigd) documentatie.
/vault/ | Vault bestandsmap (bevat verschillende bestanden).
/vault/.htaccess | Een hypertext toegang bestand (in dit geval, om gevoelige bestanden die behoren tot het script te beschermen tegen toegang door niet-geautoriseerde bronnen).
/vault/cache.dat | Cache data/gegevens.
/vault/cli.php | CLI-handler.
/vault/config.ini.RenameMe | Configuratiebestand; Bevat alle configuratieopties van CIDRAM, het vertellen wat te doen en hoe om te werken correct (hernoemen om te activeren).
/vault/config.php | Configuratie-handler.
/vault/functions.php | Functies bestand (essentieel).
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
/vault/template.html | Sjabloonbestand; Sjabloon voor HTML-uitvoer geproduceerd door de CIDRAM uitvoer generator.
/vault/template_custom.html | Sjabloonbestand; Sjabloon voor HTML-uitvoer geproduceerd door de CIDRAM uitvoer generator.
/vault/rules_as6939.php | Aangepaste regels bestand voor AS6939.
/vault/rules_softlayer.php | Aangepaste regels bestand voor Soft Layer.
/vault/rules_specific.php | Aangepaste regels bestand voor sommige specifiek CIDRs.

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

*Handige tip: Als u wil, U kunt datum/tijd informatie toevoegen om de namen van uw logbestanden door deze op in naam inclusief: `{yyyy}` voor volledige jaar, `{yy}` voor verkorte jaar, `{mm}` voor maand, `{dd}` voor dag, `{hh}` voor het uur.*

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
- Moet CIDRAM reageren met 403 headers voor geblokkeerde verzoeken, of blijven met de gebruikelijke 200 OK? False/200 = Nee (200) [Standaard]; True = Ja (403); 503 = Service onbeschikbaar (503).

"silent_mode"
- Moet CIDRAM stilletjes redirect geblokkeerd toegang pogingen in plaats van het weergeven van de "Toegang Geweigerd" pagina? Als ja, geef de locatie te redirect geblokkeerd toegang pogingen. Als nee, verlaat deze variabele leeg.

"lang"
- Geef de standaardtaal voor CIDRAM.

"emailaddr"
- Indien u wenst, u kunt een e-mailadres op hier te geven te geven aan de gebruikers als ze geblokkeerd, voor hen te gebruiken als aanspreekpunt voor steun en/of assistentie in het geval dat ze worden onrechte geblokkeerd. WAARSCHUWING: Elke e-mailadres u leveren hier zal zeker worden overgenomen met spambots en schrapers in de loop van zijn wezen die hier gebruikt, en dus, het wordt ten zeerste aanbevolen als u ervoor kiest om een e-mailadres hier te leveren, dat u ervoor zorgen dat het e-mailadres dat u hier leveren is een wegwerp-adres en/of een adres dat u niet de zorg over wordt gespamd (met andere woorden, u waarschijnlijk niet wilt om uw primaire persoonlijk of primaire zakelijke e-mailadressen te gebruik).

"disable_cli"
- Uitschakelen CLI-modus? CLI-modus is standaard ingeschakeld, maar kunt somtijds interfereren met bepaalde testtools (zoals PHPUnit bijvoorbeeld) en andere CLI-gebaseerde applicaties. Als u niet hoeft te uitschakelen CLI-modus, u moeten om dit richtlijn te negeren. False = Inschakelen CLI-modus [Standaard]; True = Uitschakelen CLI-modus.

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

####"template_data" (Categorie)
Richtlijnen/Variabelen voor sjablonen en thema's.

Betreft de HTML-uitvoer gebruikt om de "Toegang Geweigerd" pagina te genereren. Als u gebruik aangepaste thema's voor CIDRAM, HTML-uitvoer is afkomstig van de `template_custom.html` bestand, en alternatief, HTML-uitvoer is afkomstig van de `template.html` bestand. Variabelen geschreven om dit sectie van het configuratiebestand worden geïnterpreteerd aan de HTML-uitvoer door middel van het vervangen van variabelennamen omringd door accolades gevonden binnen de HTML-uitvoer met de bijbehorende variabele gegevens. Bijvoorbeeld, waar `foo="bar"`, elk geval van `<p>{foo}</p>` gevonden binnen de HTML-uitvoer `<p>bar</p>` zal worden.

"css_url"
- De sjabloonbestand voor aangepaste thema's maakt gebruik van externe CSS-eigenschappen, terwijl de sjabloonbestand voor het standaardthema maakt gebruik van interne CSS-eigenschappen. Om CIDRAM instrueren om de sjabloonbestand voor aangepaste thema's te gebruiken, geef het openbare HTTP-adres van uw aangepaste thema's CSS-bestanden via de `css_url` variabele. Als u dit variabele leeg laat, CIDRAM zal de sjabloonbestand voor de standaardthema te gebruiken.

---


###6. <a name="SECTION6"></a>HANDTEKENINGFORMAAT

Een beschrijving van het formaat en de structuur van de handtekeningen gebruikt door CIDRAM kan gevonden worden gedocumenteerd in platte tekst binnen een van de twee aangepaste handtekeningen bestanden. Raadpleeg de documentatie om meer te leren over het formaat en de structuur van de handtekeningen van CIDRAM.

Alle IPv4 handtekeningen volgt het formaat: `xxx.xxx.xxx.xxx/yy %Function% %Param%`.
- `xxx.xxx.xxx.xxx` vertegenwoordigt het begin van het CIDR blok (de octetten van de eerste IP-adres in het blok).
- `yy` vertegenwoordigt het CIDR blokgrootte [1-32].
- `%Function%` instrueert het script wat te doen met de handtekening (hoe de handtekening moet worden beschouwd).
- `%Param%` vertegenwoordigt alle aanvullende informatie dat kan worden verlangd door `%Function%`.

Alle IPv6 handtekeningen volgt het formaat: `xxxx:xxxx:xxxx:xxxx::xxxx/yy %Function% %Param%`.
- `xxxx:xxxx:xxxx:xxxx::xxxx` vertegenwoordigt het begin van het CIDR blok (de octetten van de eerste IP-adres in het blok). Compleet notatie en verkorte notatie zijn beide aanvaardbaar (en ieder moet volg de juiste en relevante normen van IPv6-notatie, maar met één uitzondering: een IPv6-adres kan nooit beginnen met een afkorting wanneer het wordt gebruikt in een handtekening voor dit script, vanwege de manier waarop CIDRs door het script zijn gereconstrueerd; Bijvoorbeeld, `::1/128` moet worden uitgedrukt, bij gebruik in een handtekening, als `0::1/128`, en `::0/128` uitgedrukt als `0::/128`).
- `yy` vertegenwoordigt het CIDR blokgrootte [1-128].
- `%Function%` instrueert het script wat te doen met de handtekening (hoe de handtekening moet worden beschouwd).
- `%Param%` vertegenwoordigt alle aanvullende informatie dat kan worden verlangd door `%Function%`.

De handtekening bestanden voor CIDRAM MOET gebruiken Unix-stijl regeleinden (`%0A`, or `\n`)! Andere soorten/stijlen van regeleinden (bv, Windows `%0D%0A` of `\r\n` regeleinden, Mac `%0D` of `\r` regeleinden, ezv) KAN worden gebruikt, maar zijn NIET voorkeur. Non-Unix-stijl regeleinden wordt genormaliseerd naar Unix-stijl regeleinden door het script.

Nauwkeurig en correct CIDR-notatie is vereist, anders zal het script NIET de handtekeningen herkennen. Tevens, alle CIDR handtekeningen van dit script MOET beginnen met een IP-adres waarvan het IP-nummer kan gelijkmatig in het blok divisie vertegenwoordigd door haar CIDR blokgrootte verdelen (bv, als u wilde alle IP-adressen van `10.128.0.0` naar `11.127.255.255` te blokkeren, `10.128.0.0/8` zou door het script NIET worden herkend, maar `10.128.0.0/9` en `11.0.0.0/9` in combinatie, ZOU door het script worden herkend).

Alles wat in de handtekening bestanden niet herkend als een handtekening noch als handtekening-gerelateerde syntaxis door het script worden GENEGEERD, daarom dit betekent dat om veilig alle niet-handtekening gegevens die u wilt in de handtekening bestanden u kunnen zetten zonder verbreking van de handtekening bestanden of de script. Reacties zijn in de handtekening bestanden aanvaardbare, en geen speciale opmaak of formaat is vereist voor hen. Shell-stijl hashing voor commentaar heeft de voorkeur, maar is niet afgedwongen; Functioneel, het maakt geen verschil voor het script ongeacht of u kiest voor Shell-stijl hashing om commentaar te gebruiken, maar gebruik van Shell-stijl hashing helpt IDE's en platte tekst editors om correct te markeren de verschillende delen van de handtekening bestanden (en dus, Shell-stijl hashing kan helpen als een visueel hulpmiddel tijdens het bewerken).

Mogelijke waarden van `%Function%` zijn als volgt:
- Run
- Whitelist
- Deny

Als "Run" wordt gebruikt, als de handtekening wordt geactiveerd, het script zal proberen (gebruiken een `require_once` statement) om een externe PHP-script uit te voeren, gespecificeerd door de `%Param%` waarde (de werkmap moet worden de "/vault/" map van het script).

Voorbeeld: `127.0.0.0/8 Run example.php`

Dit kan handig zijn als u wilt, voor enige specifieke IPs en/of CIDRs, om specifieke PHP-code uit te voeren.

Als "Whitelist" wordt gebruikt, als de handtekening wordt geactiveerd, het script zal alle detecties resetten (als er is al enige detecties) en breek de testfunctie. `%Param%` worden genegeerd. Deze functie werkt als een whitelist, om te voorkomen dat bepaalde IP-adressen en/of CIDRs van wordt gedetecteerd.

Voorbeeld: `127.0.0.1/32 Whitelist`

Als "Deny" wordt gebruikt, als de handtekening wordt geactiveerd, veronderstelling dat er geen whitelist handtekening is geactiveerd voor het opgegeven IP-adres en/of opgegeven CIDR, toegang tot de beveiligde pagina wordt ontzegd. "Deny" is wat u wilt gebruiken om een IP-adres en/of CIDR range te daadwerkelijk blokkeren. Wanneer enige handtekeningen zijn geactiveerd er dat gebruik "Deny", de "Toegang Geweigerd" pagina van het script zal worden gegenereerd en het verzoek naar de beveiligde pagina wordt gedood.

De `%Param%` waarde geaccepteerd door "Deny" zal worden parsed aan de "Toegang Geweigerd" pagina-uitgang, geleverd aan de klant/gebruiker als de genoemde reden voor hun toegang tot de gevraagde pagina worden geweigerd. Het kan een korte en eenvoudige zin zijn, uit te leggen waarom u hebt gekozen om ze te blokkeren (iets moeten volstaan, zelfs een simpele "Ik wil je niet op mijn website"), of een van het handjevol korte woorden geleverd door het script, dat als gebruikt, wordt vervangen door het script met een voorbereide toelichting waarom de klant/gebruiker is geblokkeerd.

De voorbereide toelichtingen hebben i18n ondersteuning en kan worden vertaald door het script op basis van de taal die u opgeeft naar de `lang` richtlijn van het script configuratie. Tevens, u kunt het script instrueren om "Deny" handtekeningen te negeren op basis van hun `%Param%` waarde (als ze gebruik maken van deze korte woorden) via de richtlijnen gespecificeerd door het script configuratie (elk kort woord heeft een overeenkomstige richtlijn te verwerken overeenkomende handtekeningen of te negeren hen). `%Param%` waarden dat niet gebruiken deze korte woorden, echter, hebben geen i18n ondersteuning en daarom zal NIET worden vertaald door het script, en tevens, en zijn niet direct controleerbaar door het script configuratie.

De beschikbare korte woorden zijn:
- Bogon
- Cloud
- Generic
- Proxy
- Spam

Facultatief: Als u wilt uw aangepaste handtekeningen te splitsen in afzonderlijke secties, u kunt deze individuele secties te identificeren om het script door toevoeging van een "Tag:" etiket onmiddellijk na de handtekeningen van elke sectie, samen met de naam van uw handtekening sectie.

Voorbeeld:
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

Voorbeeld:
```
1.2.3.4/32 Deny Bogon
2.3.4.5/32 Deny Cloud

4.5.6.7/32 Deny Generic
5.6.7.8/32 Deny Spam
Tag: Section 1
```

In het bovenstaande voorbeeld `1.2.3.4/32` en `2.3.4.5/32` zal worden geëtiketteerd als "IPv4", terwijl `4.5.6.7/32` en `5.6.7.8/32` zal worden geëtiketteerd als "Section 1".

Bovendien, als u wilt CIDRAM om enkele specifieke secties in iedereen van de handtekening bestanden te negeren, kunt u het `ignore.dat` bestand gebruiken om specificeren welke secties te negeren. Op een nieuwe regel, schrijven `Ignore`, gevolgd door een spatie, gevolgd door de naam van de sectie die u wilt CIDRAM te negeren.

Voorbeeld:
```
Ignore Section 1
```

Raadpleeg de aangepaste handtekening bestanden voor meer informatie.

---


Laatste Bijgewerkt: 13 Juni 2016 (2016.06.13).
