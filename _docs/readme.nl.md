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

2) Facultatief (sterk aanbevolen voor ervaren gebruikers, maar niet aan te raden voor beginners of voor de onervaren), open `config.ini` (gelegen binnen `vault`) - Dit bestand bevat alle beschikbare CIDRAM configuratie opties. Boven elke optie moet een korte opmerking te beschrijven wat het doet en wat het voor. Pas deze opties als het u past, volgens welke geschikt is voor uw configuratie. Sla het bestand, sluiten.

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
/LICENSE.txt | Een kopie van de GNU/GPLv2 licentie.
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
/vault/cli.inc | CLI-handler.
/vault/config.inc | Configuratie-handler.
/vault/config.ini | Configuratiebestand; Bevat alle configuratieopties van CIDRAM, het vertellen wat te doen en hoe om te werken correct (essentiële)!
/vault/functions.inc | Functies bestand (essentieel).
/vault/ipv4.dat | IPv4 handtekeningen bestand.
/vault/ipv4_custom.dat | IPv4 aangepaste handtekeningen bestand.
/vault/ipv6.dat | IPv6 handtekeningen bestand.
/vault/ipv6_custom.dat | IPv6 aangepaste handtekeningen bestand.
/vault/lang.inc | Taal-handler.
/vault/lang/ | Bevat CIDRAM taaldata/taalgegevens.
/vault/lang/.htaccess | Een hypertext toegang bestand (in dit geval, om gevoelige bestanden die behoren tot het script te beschermen tegen toegang door niet-geautoriseerde bronnen).
/vault/lang/lang.en.inc | Engels taaldata/taalgegevens.
/vault/lang/lang.es.inc | Spaanse taaldata/taalgegevens.
/vault/lang/lang.fr.inc | Franse taaldata/taalgegevens.
/vault/lang/lang.id.inc | Indonesisch taaldata/taalgegevens.
/vault/lang/lang.it.inc | Italiaanse taaldata/taalgegevens.
/vault/lang/lang.nl.inc | Nederlandse taaldata/taalgegevens.
/vault/lang/lang.pt.inc | Portugees taaldata/taalgegevens.
/vault/lang/lang.zh-TW.inc | Chinees (traditioneel) taaldata/taalgegevens.
/vault/lang/lang.zh.inc | Chinees (vereenvoudigd) taaldata/taalgegevens.
/vault/outgen.inc | Uitvoer generator.
/vault/template.html | Sjabloonbestand; Sjabloon voor HTML-uitvoer geproduceerd door de CIDRAM uitvoer generator.
/vault/template_custom.html | Sjabloonbestand; Sjabloon voor HTML-uitvoer geproduceerd door de CIDRAM uitvoer generator.
/vault/rules_as6939.inc | Aangepaste regels bestand voor AS6939.
/vault/rules_softlayer.inc | Aangepaste regels bestand voor Layer.

---


###5. <a name="SECTION5"></a>CONFIGURATIEOPTIES
Het volgende is een lijst van variabelen die in de `config.ini` configuratiebestand van CIDRAM, samen met een beschrijving van hun doel en functie.

####"general" (Categorie)
Algemene configuratie voor CIDRAM.

"logfile"
- Bestandsnaam van het bestand om alle geblokkeerde toegang pogingen te loggen in. Geef een bestandsnaam, of laat leeg om uit te schakelen.

"ipaddr"
- Waar het IP-adres van het aansluiten verzoek te vinden? (Handig voor diensten zoals Cloudflare en dergelijke) Standaard = REMOTE_ADDR. WAARSCHUWING: Verander dit niet tenzij u weet wat u doet!

"forbid_on_block"
- Moet CIDRAM reageren met 403 headers voor geblokkeerde verzoeken, of blijven met de gebruikelijke 200 OK? False = Nee (200) [Standaard]; True = Ja (403).

"lang"
- Geef de standaardtaal voor CIDRAM.

"emailaddr"
- If you wish, you can supply an email address here to be given to users when they're blocked, for them to use as a point of contact for support and/or assistance for in the event of them being blocked mistakenly or in error. WARNING: Whatever email address you supply here will most certainly be acquired by spambots and scrapers during the course of its being used here, and so, it's strongly recommended that if you choose to supply an email address here, that you ensure that the email address you supply here is a disposable address and/or an address that you don't mind being spammed (in other words, you probably don't want to use your primary personal or primary business email addresses).

####"signatures" (Categorie)
Configuratie voor handtekeningen.

"block_cloud"
- Blokkeren CIDRs geïdentificeerd als behorend tot webhosting/cloud-diensten? Als u een api te bedienen vanaf uw website of als u verwacht dat andere websites aan te sluiten op uw website, dit richtlijn moet worden ingesteld op false. Als u niet, dan, dit richtlijn moet worden ingesteld op true.

"block_bogons"
- Blokkeren bogon/martian CIDRs? Als u verwacht aansluitingen om uw website vanuit uw lokale netwerk, vanuit localhost, of vanuit uw LAN, dit richtlijn moet worden ingesteld op false. Als u niet verwacht deze aansluitingen, dit richtlijn moet worden ingesteld op true.

"block_generic"
- Blokkeren CIDRs algemeen aanbevolen voor blacklisting? Dit omvat alle handtekeningen die niet zijn gemarkeerd als onderdeel van elke van de andere, meer specifieke handtekening categorieën.

"block_spam"
- Blokkeren CIDRs geïdentificeerd als zijnde hoog risico voor spam? Tenzij u problemen ondervindt wanneer u dit doet, in algemeen, dit moet altijd worden ingesteld op true.

---


###6. <a name="SECTION6"></a>HANDTEKENINGFORMAAT

Een beschrijving van het formaat en de structuur van de handtekeningen gebruikt door CIDRAM kan gevonden worden gedocumenteerd in platte tekst binnen een van de twee aangepaste handtekeningen bestanden. Raadpleeg de documentatie om meer te leren over het formaat en de structuur van de handtekeningen van CIDRAM.

---


Laatste Bijgewerkt: 8 Maart 2016 (2016.03.08).
