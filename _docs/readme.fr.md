## Documentation pour CIDRAM (Français).

### Contenu
- 1. [PRÉAMBULE](#SECTION1)
- 2. [COMMENT INSTALLER](#SECTION2)
- 3. [COMMENT UTILISER](#SECTION3)
- 4. [FICHIERS INCLUS DANS CETTE PAQUET](#SECTION4)
- 5. [OPTIONS DE CONFIGURATION](#SECTION5)
- 6. [FORMATS DE SIGNATURES](#SECTION6)

---


###1. <a name="SECTION1"></a>PRÉAMBULE

CIDRAM (Classless Inter-Domain Routing Access Manager) est un script PHP conçu pour la protection des websites par bloquant les demandes de page produit à partir de adresses IP considéré comme étant sources de trafic indésirable, comprenant (mais pas limité a) le trafic de terminaux d'accès non humains, services de cloud computing, spambots, scrapers, etc. Elle le fait en calculant les CIDRs possibles des adresses IP fournie par les demandes entrantes puis essayant pour correspondre à ces CIDRs possibles contre ses fichiers de signatures (ces fichiers de signatures contenir des listes de CIDRs d'adresses IP considéré comme étant sources de trafic indésirable); Si des correspondances sont trouvées, les demandes sont bloquées.

CIDRAM COPYRIGHT 2016 et au-delà GNU/GPLv2 par Caleb M (Maikuolan).

Ce script est un logiciel libre; vous pouvez redistribuer et/ou le modifier selon les termes de la GNU General Public License telle que publiée par la Free Software Foundation; soit la version 2 de la Licence, ou (à votre choix) toute version ultérieure. Ce script est distribué dans l'espoir qu'il sera utile, mais SANS AUCUNE GARANTIE, sans même la implicite garantie de COMMERCIALISATION ou D'ADAPTATION À UN PARTICULIER USAGE. Voir la GNU General Public License pour plus de détails, situé dans le `LICENSE.txt` fichier et disponible également à partir de:
- <http://www.gnu.org/licenses/>.
- <http://opensource.org/licenses/>.

Ce document et son associé paquet peuvent être téléchargé gratuitement à sans frais à partir de [GitHub](https://github.com/Maikuolan/CIDRAM/).

---


###2. <a name="SECTION2"></a>COMMENT INSTALLER

J'ai l'intention de simplifier ce processus par la création d'un programme d'installation à l'avenir, mais en attendant, suivez ces instructions pour la correcte fonction de CIDRAM sur la majorité de systèmes et CMS:

1) Parce que vous lisez ceci, je suppose que vous avez déjà téléchargé une archivée copie du script, décompressé son contenu et l'ont assis sur votre locale machine. Maintenant, vous devez déterminer la approprié emplacement sur votre hôte ou CMS à mettre ces contenus. Un répertoire comme `/public_html/cidram/` ou similaire (cependant, il n'est pas question que vous choisissez, à condition que c'est quelque part de sûr et quelque part que vous êtes heureux avec) sera suffira. *Vous avant commencer téléchargement au serveur, continuer lecture..*

2) Renommer `config.ini.RenameMe` à `config.ini` (situé à l'intérieur de `vault`), et facultativement (fortement recommandé pour les utilisateurs avancés, mais pas recommandé pour les débutants ou pour les novices), l'ouvrir (ce fichier contient toutes les directives disponible pour CIDRAM; au-dessus de chaque option devrait être un bref commentaire décrivant ce qu'il fait et ce qu'il est pour). Réglez ces options comme bon vous semble, selon ce qui est approprié pour votre particulière configuration. Enregistrer le fichier, et fermer.

3) Télécharger les contenus (CIDRAM et ses fichiers) à le répertoire vous aviez décidé plus tôt (vous n'avez pas besoin les `*.txt`/`*.md` fichiers, mais surtout, vous devriez télécharger tous les fichiers sur le serveur).

4) CHMOD la `vault` répertoire à "777". Le principal répertoire qui est stocker le contenu (celui que vous avez choisi plus tôt), généralement, peut être laissé seul, mais CHMOD état devrait être vérifié si vous avez eu problèmes d'autorisations dans le passé sur votre système (par défaut, devrait être quelque chose comme "755").

5) Suivant, vous aurez besoin de l'attacher CIDRAM à votre système ou CMS. Il est plusieurs façons vous pouvez attacher CIDRAM à votre système ou CMS, mais le plus simple est à simplement inclure le script au début d'un fichier de la base de données de votre système ou CMS (un qui va généralement toujours être chargé lorsque quelqu'un accède à n'importe quelle page sur votre website) utilisant un `require` ou `include` déclaration. Généralement, ce sera quelque chose de stocké dans un répertoire comme `/includes`, `/assets` ou `/functions`, et il sera souvent nommé quelque chose comme `init.php`, `common_functions.php`, `functions.php` ou similaire. Vous sera besoin à déterminer qui est le fichier c'est pour votre situation; Si vous rencontrez des difficultés dans déterminer de ce pour vous-même, visiter les CIDRAM issues page dans GitHub. Pour ce faire [à utiliser `require` ou `include`], insérez la ligne de code suivante au début de ce le noyau fichier et remplacer la string contenue à l'intérieur des guillemets avec l'exacte adresse le fichier `loader.php` (l'adresse locale, pas l'adresse HTTP; il ressemblera l'adresse de `vault` mentionné précédemment).

`<?php require '/user_name/public_html/cidram/loader.php'; ?>`

Enregistrer le fichier, fermer, rétélécharger.

-- OU ALTERNATIVEMENT --

Si vous utilisez un Apache serveur web et si vous avez accès à `php.ini`, vous pouvez utiliser la `auto_prepend_file` directive à préfixer CIDRAM chaque fois qu'une demande de PHP est faite. Quelque chose comme:

`auto_prepend_file = "/user_name/public_html/cidram/loader.php"`

Ou cette dans le `.htaccess` fichier:

`php_value auto_prepend_file "/user_name/public_html/cidram/loader.php"`

6) C'est tout! :-)

---


###3. <a name="SECTION3"></a>COMMENT UTILISER

CIDRAM devrait bloquer automatiquement les demandes indésirables à votre website sans nécessitant aucune intervention manuelle, en dehors de son installation initiale.

Réactualisant se fait manuellement, et vous pouvez personnaliser votre configuration et personnaliser les CIDRs sont bloqués par modifiant le fichier de configuration et/ou vos fichiers de signatures.

Si vous rencontrez des faux positifs, s'il vous plaît, contactez moi et parle moi de ça.

---


###4. <a name="SECTION4"></a>FICHIERS INCLUS DANS CETTE PAQUET

Voici une liste de tous les fichiers inclus dans CIDRAM dans son natif état, tous les fichiers qui peuvent être potentiellement créées à la suite de l'utilisation de ce script, avec une brève description de ce que tous ces fichiers sont pour.

Fichier | Description
----|----
/.gitattributes | Un fichier du GitHub projet (pas nécessaire pour le bon fonctionnement du script).
/Changelog.txt | Un enregistrement des modifications apportées au script entre les différentes versions (pas nécessaire pour le bon fonctionnement du script).
/composer.json | Composer/Packagist information (pas nécessaire pour le bon fonctionnement du script).
/LICENSE.txt | Une copie de la GNU/GPLv2 license (pas nécessaire pour le bon fonctionnement du script).
/loader.php | Chargeur/Loader. C'est ce que vous êtes censé être attacher dans à (essentiel)!
/README.md | Sommaire de l'information du projet.
/web.config | Un ASP.NET fichier de configuration (dans ce cas, pour protéger de la `/vault` répertoire contre d'être consulté par des non autorisée sources dans le cas où le script est installé sur un serveur basé sur les ASP.NET technologies).
/_docs/ | Documentation répertoire (contient divers fichiers).
/_docs/readme.de.md | Documentation en Allemand.
/_docs/readme.en.md | Documentation en Anglais.
/_docs/readme.es.md | Documentation en Espagnol.
/_docs/readme.fr.md | Documentation en Français.
/_docs/readme.id.md | Documentation en Indonésien.
/_docs/readme.it.md | Documentation en Italien.
/_docs/readme.nl.md | Documentation en Néerlandais.
/_docs/readme.pt.md | Documentation en Portugais.
/_docs/readme.zh-TW.md | Documentation en Chinois (traditionnel).
/_docs/readme.zh.md | Documentation en Chinois (simplifié).
/vault/ | Voûte répertoire (contient divers fichiers).
/vault/.htaccess | Un hypertexte accès fichier (dans ce cas, pour protéger les sensibles fichiers appartenant au script contre être consulté par non autorisées sources).
/vault/cache.dat | Données du cache.
/vault/cli.php | Module de CLI.
/vault/config.ini.RenameMe | Fichier de configuration; Contient toutes les options de configuration pour CIDRAM, pour comment fonctionner correctement (renommer pour activer).
/vault/config.php | Module de configuration.
/vault/functions.php | Fichier de fonctions (essentiel).
/vault/ipv4.dat | Fichier de signatures pour IPv4.
/vault/ipv4_custom.dat.RenameMe | Fichier de signatures pour IPv4 personnalisés (renommer pour activer).
/vault/ipv6.dat | Fichier de signatures pour IPv6.
/vault/ipv6_custom.dat.RenameMe | Fichier de signatures pour IPv6 personnalisés (renommer pour activer).
/vault/lang.php | Module de linguistiques.
/vault/lang/ | Contient linguistiques données.
/vault/lang/.htaccess | Un hypertexte accès fichier (dans ce cas, pour protéger les sensibles fichiers appartenant au script contre être consulté par non autorisées sources).
/vault/lang/lang.ar.cli.php | Linguistiques données en Arabe pour CLI.
/vault/lang/lang.ar.php | Linguistiques données en Arabe.
/vault/lang/lang.de.cli.php | Linguistiques données en Allemand pour CLI.
/vault/lang/lang.de.php | Linguistiques données en Allemand.
/vault/lang/lang.en.cli.php | Linguistiques données en Anglais pour CLI.
/vault/lang/lang.en.php | Linguistiques données en Anglais.
/vault/lang/lang.es.cli.php | Linguistiques données en Espagnol pour CLI.
/vault/lang/lang.es.php | Linguistiques données en Espagnol.
/vault/lang/lang.fr.cli.php | Linguistiques données en Français pour CLI.
/vault/lang/lang.fr.php | Linguistiques données en Français.
/vault/lang/lang.id.cli.php | Linguistiques données en Indonésien pour CLI.
/vault/lang/lang.id.php | Linguistiques données en Indonésien.
/vault/lang/lang.it.cli.php | Linguistiques données en Italien pour CLI.
/vault/lang/lang.it.php | Linguistiques données en Italien.
/vault/lang/lang.ja.cli.php | Linguistiques données en Japonais pour CLI.
/vault/lang/lang.ja.php | Linguistiques données en Japonais.
/vault/lang/lang.nl.cli.php | Linguistiques données en Néerlandais pour CLI.
/vault/lang/lang.nl.php | Linguistiques données en Néerlandais.
/vault/lang/lang.pt.cli.php | Linguistiques données en Portugais pour CLI.
/vault/lang/lang.pt.php | Linguistiques données en Portugais.
/vault/lang/lang.ru.cli.php | Linguistiques données en Russe pour CLI.
/vault/lang/lang.ru.php | Linguistiques données en Russe.
/vault/lang/lang.vi.cli.php | Linguistiques données en Vietnamien pour CLI.
/vault/lang/lang.vi.php | Linguistiques données en Vietnamien.
/vault/lang/lang.zh-tw.cli.php | Linguistiques données en Chinois (traditionnel) pour CLI.
/vault/lang/lang.zh-tw.php | Linguistiques données en Chinois (traditionnel).
/vault/lang/lang.zh.cli.php | Linguistiques données en Chinois (simplifié) pour CLI.
/vault/lang/lang.zh.php | Linguistiques données en Chinois (simplifié).
/vault/outgen.php | Générateur de sortie.
/vault/template.html | Modèle fichier; Modèle pour l'HTML sortie produit par CIDRAM pour son bloqués fichiers téléchargement message (le message vu par l'envoyeur).
/vault/template_custom.html | Modèle fichier; Modèle pour l'HTML sortie produit par CIDRAM pour son bloqués fichiers téléchargement message (le message vu par l'envoyeur).
/vault/rules_as6939.php | Fichier de règles personnalisés pour AS6939.
/vault/rules_softlayer.php | Fichier de règles personnalisés pour Soft Layer.
/vault/rules_specific.php | Fichier de règles personnalisés pour certains CIDRs spécifiques.

---


###5. <a name="SECTION5"></a>OPTIONS DE CONFIGURATION
Ce qui suit est une liste des directives disponibles pour CIDRAM dans le `config.ini` fichier de configuration, avec une description de leur objectif et leur fonction.

####"general" (Catégorie)
Configuration générale pour CIDRAM.

"logfile"
- Un fichier lisible par l'homme pour enregistrement de toutes les tentatives d'accès bloquées. Spécifier un fichier, ou laisser vide à désactiver.

"logfileApache"
- Un fichier dans le style d'Apache pour enregistrement de toutes les tentatives d'accès bloquées. Spécifier un fichier, ou laisser vide à désactiver.

"logfileSerialized"
- Un fichier sérialisé pour enregistrement de toutes les tentatives d'accès bloquées. Spécifier un fichier, ou laisser vide à désactiver.

*Conseil utile: Si vous souhaitez, vous pouvez ajouter l'information pour la date/l'heure à les noms de vos fichiers pour enregistrement par des incluant ceux-ci au nom: `{yyyy}` pour l'année complète, `{yy}` pour l'année abrégée, `{mm}` pour mois, `{dd}` pour le jour, `{hh}` pour l'heure.*

*Exemples:*
- *`logfile='logfile.{yyyy}-{mm}-{dd}-{hh}.txt'`*
- *`logfileApache='access.{yyyy}-{mm}-{dd}-{hh}.txt'`*
- *`logfileSerialized='serial.{yyyy}-{mm}-{dd}-{hh}.txt'`*

"timeOffset"
- Si votre temps serveur ne correspond pas à votre temps locale, vous pouvez spécifier un offset ici pour régler l'information en date/temps généré par CIDRAM selon vos besoins. Il est généralement recommandé à la place pour ajuster la directive de fuseau horaire dans votre fichier `php.ini`, mais parfois (tels que lorsque l'on travaille avec des fournisseurs d'hébergement partagé limitées) ce n'est pas toujours possible de faire, et donc, cette option est disponible ici. Offset est en minutes.
- Exemple (à ajouter une heure): `timeOffset=60`

"ipaddr"
- Où trouver l'adresse IP de demandes de connexion? (Utile pour services tels que Cloudflare et similaires) Par Défaut = REMOTE_ADDR. AVERTISSEMENT: Ne pas changer si vous ne sais pas ce que vous faites!

"forbid_on_block"
- Devrait CIDRAM envoyer 403 headers avec demandes bloquées, ou rester avec l'habituel 200 OK? False/200 = Non (200) [Défaut]; True = Oui (403); 503 = Service indisponible (503).

"silent_mode"
- Devrait CIDRAM rediriger silencieusement les tentatives d'accès bloquées à la place de l'affichage de la page "Accès Refusé"? Si oui, spécifiez l'emplacement pour rediriger les tentatives d'accès bloquées. Si non, laisser cette variable vide.

"lang"
- Spécifiez la langue défaut pour CIDRAM.

"emailaddr"
- Si vous souhaitez, vous pouvez fournir une adresse e-mail ici à donner aux utilisateurs quand ils sont bloqués, pour qu'ils utilisent comme un point de contact pour soutien et/ou assistance dans le cas d'eux étant bloqué par erreur. AVERTISSEMENT: Tout de l'adresse e-mail vous fournissez ici sera très certainement être acquis par les robots des spammeurs et voleurs de contenu au cours de son être utilisés ici, et donc, il est recommandé fortement que si vous choisissez pour fournir une adresse e-mail ici, de vous assurer que l'adresse e-mail que vous fournissez ici est une adresse jetable et/ou une adresse que ne vous dérange pas d'être spammé (en d'autres termes, vous ne voulez probablement pas d'utiliser votre adresses e-mail personnel primaire ou d'affaires primaire).

"disable_cli"
- Désactiver le mode CLI? Le mode CLI est activé par défaut, mais peut parfois interférer avec certains test outils (comme PHPUnit, par exemple) et d'autres applications basées sur CLI. Si vous n'avez pas besoin désactiver le mode CLI, vous devrait ignorer cette directive. False = Activer le mode CLI [Défaut]; True = Désactiver le mode CLI.

####"signatures" (Catégorie)
Configuration pour les signatures.

"ipv4"
- Une liste des fichiers du signatures IPv4 que CIDRAM devrait tenter d'utiliser, délimité par des virgules. Vous pouvez ajouter des entrées ici si vous voulez inclure des fichiers supplémentaires dans CIDRAM.

"ipv6"
- Une liste des fichiers du signatures IPv6 que CIDRAM devrait tenter d'utiliser, délimité par des virgules. Vous pouvez ajouter des entrées ici si vous voulez inclure des fichiers supplémentaires dans CIDRAM.

"block_cloud"
- Bloquer CIDRs identifié comme appartenant à hébergement/cloud services? Si vous utilisez un service d'API à partir de votre website ou si vous attendez d'autres sites à connecter avec votre website, cette directive devrait être fixé sur false. Si vous ne pas, puis, cette directive doit être fixé comme true.

"block_bogons"
- Bloquer CIDRs bogon/martian? Si vous attendre connexions à votre website à partir de dans votre réseau local, à partir de localhost, ou à partir de votre LAN, cette directive devrait être fixé sur false. Si vous ne attendez pas à ces telles connexions, cette directive doit être fixé comme true.

"block_generic"
- Bloquer CIDRs recommandé en généralement pour les listes noires? Cela couvre toutes les signatures qui ne sont pas marqué comme étant partie de l'autre plus spécifique catégories de signatures.

"block_proxies"
- Bloquer CIDRs identifié comme appartenant à services de proxy? Si vous avez besoin que les utilisateurs puissent accéder à votre site web à partir des services de proxy anonymes, cette directive devrait être fixé sur false. Autrement, si vous n'avez besoin pas de proxies anonymes, cette directive devrait être fixé sur true comme moyen d'améliorer la sécurité.

"block_spam"
- Bloquer CIDRs identifié comme étant risque élevé pour le spam? Sauf si vous rencontrez des problèmes quand vous faire, en généralement, cette directive devrait toujours être fixé comme true.

####"template_data" (Catégorie)
Directives/Variables pour les modèles et thèmes.

Correspond à la sortie HTML utilisé pour générer la page "Accès Refusé". Si vous utilisez des thèmes personnalisés pour CIDRAM, sortie HTML provient du `template_custom.html` fichier, et sinon, sortie HTML provient du `template.html` fichier. Variables écrites à cette section du fichier de configuration sont préparé pour la sortie HTML par voie de remplacer tous les noms de variables circonfixé par accolades trouvés dans la sortie HTML avec les variables données correspondant. Par exemple, où `foo="bar"`, toute instance de `<p>{foo}</p>` trouvés dans la sortie HTML deviendra `<p>bar</p>`.

"css_url"
- Le modèle fichier pour des thèmes personnalisés utilise les propriétés CSS externes, tandis que le modèle fichier pour le défaut thème utilise les propriétés CSS internes. Pour instruire CIDRAM d'utiliser le modèle fichier pour des thèmes personnalisés, spécifier l'adresse HTTP public de votre thèmes personnalisés CSS fichiers utilisant le `css_url` variable. Si vous laissez cette variable vide, CIDRAM va utiliser le modèle fichier pour le défaut thème.

---


###6. <a name="SECTION6"></a>FORMATS DE SIGNATURES

Une description du format et de la structure du signatures utilisé par CIDRAM peut être trouvée documentée en plain-text dans les deux fichiers de signatures personnalisées. S'il vous plaît référez à cette documentation pour apprendre plus sur le format et la structure du signatures de CIDRAM.

Toutes les signatures IPv4 suivre le format: `xxx.xxx.xxx.xxx/yy %Function% %Param%`.
- `xxx.xxx.xxx.xxx` représente le début du bloc (les octets de l'adresse IP initiale dans le bloc).
- `yy` représente la taille du bloc [1-32].
- `%Function%` instruit le script ce qu'il faut faire avec la signature (la façon dont la signature doit être considérée).
- `%Param%` représente les informations complémentaires qui peuvent être exigés par `%Function%`.

Toutes les signatures IPv6 follow the format: `xxxx:xxxx:xxxx:xxxx::xxxx/yy %Function% %Param%`.
- `xxxx:xxxx:xxxx:xxxx::xxxx` représente le début du bloc (les octets de l'adresse IP initiale dans le bloc). Notation complète et notation abrégée sont à la fois acceptable (et chacun DOIT suivre les normes appropriées et pertinentes de la notation d'IPv6, mais avec une exception: une adresse IPv6 ne peut jamais commencer par une abréviation quand il est utilisé dans une signature pour ce script, en raison de la façon dont les CIDRs sont reconstruits par le script; Par exemple, `::1/128` doit être exprimée, quand il est utilisé dans une signature, comme `0::1/128`, et `::0/128` exprimée comme `0::/128`).
- `yy` représente la taille du bloc [1-128].
- `%Function%` instruit le script ce qu'il faut faire avec la signature (la façon dont la signature doit être considérée).
- `%Param%` représente les informations complémentaires qui peuvent être exigés par `%Function%`.

Les fichiers de signatures pour CIDRAM DEVRAIT utiliser les sauts de ligne de type Unix (`%0A`, or `\n`)! D'autres types/styles de sauts de ligne (par exemple, Windows `%0D%0A` ou `\r\n` sauts de ligne, Mac `%0D` ou `\r` sauts de ligne, etc) PEUT être utilisé, mais ne sont PAS préférés. Ceux sauts de ligne qui ne sont pas du type Unix sera normalisé à sauts de ligne de type Unix par le script.

Notation précise et correcte pour CIDRs est exigée, autrement le script ne sera PAS reconnaître les signatures. En outre, toutes les signatures CIDR de ce script DOIT commencer avec une adresse IP dont le numéro IP peut diviser uniformément dans la division du bloc représenté par la taille du bloc (par exemple, si vous voulez bloquer toutes les adresses IP a partir de `10.128.0.0` jusqu'à `11.127.255.255`, `10.128.0.0/8` ne serait PAS reconnu par le script, mais `10.128.0.0/9` et `11.0.0.0/9` utilisé en conjonction, SERAIT reconnu par le script).

Tout dans les fichiers de signatures non reconnu comme une signature ni comme liées à la syntaxe par le script seront IGNORÉS, donc ce qui signifie que vous pouvez mettre toutes les données non-signature que vous voulez dans les fichiers de signatures sans risque, sans les casser et sans casser le script. Les commentaires sont acceptables dans les fichiers de signatures, et aucun formatage spécial est nécessaire pour eux. Hachage dans le style de Shell pour les commentaires est préféré, mais pas forcée; Fonctionnellement, il ne fait aucune différence pour le script si vous choisissez d'utiliser hachage dans le style de Shell pour les commentaires, mais d'utilisation du hachage dans le style de Shell est utile pour IDEs et éditeurs de texte brut de mettre en surligner correctement les différentes parties des fichiers de signatures (et donc, hachage dans le style de Shell peut aider comme une aide visuelle lors de l'édition).

Les valeurs possibles de `%Function%` sont les suivants:
- Run
- Whitelist
- Deny

Si "Run" est utilisé, quand la signature est déclenchée, le script tentera d'exécuter (utilisant un statement `require_once`) un script PHP externe, spécifié par la valeur de `%Param%` (le répertoire de travail devrait être le répertoire "/vault/" du script).

Exemple: `127.0.0.0/8 Run example.php`

Cela peut être utile si vous voulez exécuter du code PHP spécifique pour certaines adresses IP et/ou CIDRs spécifiques.

Si "Whitelist" est utilisé, quand la signature est déclenchée, le script réinitialise toutes les détections (s'il y a eu des détections) et de briser la fonction du test. `%Param%` est ignorée. Cette fonction est l'équivalent de mettre une adresse IP ou CIDR particulière sur un whitelist pour empêcher la détection.

Exemple: `127.0.0.1/32 Whitelist`

Si "Deny" est utilisé, quand la signature est déclenchée, en supposant qu'aucune signature whitelist a été déclenchée pour l'adresse IP donnée et/ou CIDR donnée, accès à la page protégée sera refusée. "Deny" est ce que vous aurez envie d'utiliser d'effectivement bloquer une adresse IP et/ou CIDR. Quand quelconque les signatures sont déclenchées que faire usage de "Deny", la page "Access Denied" du script seront générés et la demande à la page protégée tué.

La valeur de `%Param%` accepté par "Deny" seront traitées au la sortie de la page "Accès Refusé", fourni au client/utilisateur comme la raison invoquée pour leur accès à la page demandée étant refusée. Il peut être une phrase courte et simple, expliquant pourquoi vous avez choisi de les bloquer (quoi que ce soit devrait suffire, même une simple "Je ne veux tu pas sur mon site"), ou l'un d'une petite poignée de mots courts fourni par le script, que si elle est utilisée, sera remplacé par le script avec une explication pré-préparés des raisons pour lesquelles le client/utilisateur a été bloqué.

Les explications pré-préparés avoir le soutien de i18n et peut être traduit par le script sur la base de la langue que vous spécifiez à la directive `lang` de la configuration du script. En outre, vous pouvez demander le script d'ignorer signatures de "Deny" sur la base de leur valeur de `%Param%` (s'ils utilisent ces mots courts) par les directives indiquées par la configuration du script (chaque mot court a une directive correspondante à traiter les signatures correspondant ou d'ignorer les). Les valeurs de `%Param%` qui ne pas utiliser ces mots courts, toutefois, n'avoir pas le soutien de i18n et donc ne seront PAS traduits par le script, et en outre, ne sont pas directement contrôlables par la configuration du script.

Les mots courts disponibles sont:
- Bogon
- Cloud
- Generic
- Proxy
- Spam

Optionnel: Si vous voulez partager vos signatures personnalisées en sections individuelles, vous pouvez identifier ces sections individuelles au script par ajoutant un label "Tag:" immédiatement après les signatures de chaque section, inclus avec le nom de votre section de signatures.

Exemple:
```
# "Section 1."
1.2.3.4/32 Deny Bogon
2.3.4.5/32 Deny Cloud
4.5.6.7/32 Deny Generic
5.6.7.8/32 Deny Spam
6.7.8.9/32 Deny Proxy
Tag: Section 1
```

Pour briser les tagues de sections et assurer que les tagues ne sont pas identifié incorrectement pour les sections de signatures à partir de plus tôt dans les fichiers, assurez-vous simplement qu'il ya au moins deux sauts de ligne consécutifs entre votre tague et vos sections précédent. Toutes les signatures non tagué sera par défaut soit "IPv4" ou "IPv6" (en fonction de quels types de signatures sont déclenchés).

Exemple:
```
1.2.3.4/32 Deny Bogon
2.3.4.5/32 Deny Cloud

4.5.6.7/32 Deny Generic
5.6.7.8/32 Deny Spam
Tag: Section 1
```

Dans l'exemple ci-dessus `1.2.3.4/32` et `2.3.4.5/32` seront tagués comme "IPv4", tandis que `4.5.6.7/32` et `5.6.7.8/32` seront tagués comme "Section 1".

Reportez-vous aux fichiers de signatures personnalisées pour plus d'informations.

---


Dernière Réactualisé: 28 Mai 2016 (2016.05.28).
