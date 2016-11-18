## Documentation pour CIDRAM (Français).

### Contenu
- 1. [PRÉAMBULE](#SECTION1)
- 2. [COMMENT INSTALLER](#SECTION2)
- 3. [COMMENT UTILISER](#SECTION3)
- 4. [GESTION FRONT-END](#SECTION4)
- 5. [FICHIERS INCLUS DANS CETTE PAQUET](#SECTION5)
- 6. [OPTIONS DE CONFIGURATION](#SECTION6)
- 7. [FORMATS DE SIGNATURES](#SECTION7)
- 8. [QUESTIONS FRÉQUEMMENT POSÉES (FAQ)](#SECTION8)

*Note concernant les traductions: En cas d'erreurs (par exemple, différences entre les traductions, fautes de frappe, etc), la version Anglaise du README est considérée comme la version originale et faisant autorité. Si vous trouvez des erreurs, votre aide pour les corriger serait bienvenue.*

---


###1. <a name="SECTION1"></a>PRÉAMBULE

CIDRAM (Classless Inter-Domain Routing Access Manager) est un script PHP conçu pour la protection des websites par bloquant les demandes de page produit à partir de adresses IP considéré comme étant sources de trafic indésirable, comprenant (mais pas limité a) le trafic de terminaux d'accès non humains, services de cloud computing, spambots, scrapers, etc. Elle le fait en calculant les CIDRs possibles des adresses IP fournie par les demandes entrantes puis essayant pour correspondre à ces CIDRs possibles contre ses fichiers de signatures (ces fichiers de signatures contenir des listes de CIDRs d'adresses IP considéré comme étant sources de trafic indésirable); Si des correspondances sont trouvées, les demandes sont bloquées.

CIDRAM COPYRIGHT 2016 et au-delà GNU/GPLv2 par Caleb M (Maikuolan).

Ce script est un logiciel libre; vous pouvez redistribuer et/ou le modifier selon les termes de la GNU General Public License telle que publiée par la Free Software Foundation; soit la version 2 de la Licence, ou (à votre choix) toute version ultérieure. Ce script est distribué dans l'espoir qu'il sera utile, mais SANS AUCUNE GARANTIE, sans même la implicite garantie de COMMERCIALISATION ou D'ADAPTATION À UN PARTICULIER USAGE. Voir la GNU General Public License pour plus de détails, situé dans le `LICENSE.txt` fichier et disponible également à partir de:
- <http://www.gnu.org/licenses/>.
- <http://opensource.org/licenses/>.

Ce document et son associé paquet peuvent être téléchargé gratuitement à sans frais à partir de [Github](https://github.com/Maikuolan/CIDRAM/).

---


###2. <a name="SECTION2"></a>COMMENT INSTALLER

J'ai l'intention de simplifier ce processus par la création d'un programme d'installation à l'avenir, mais en attendant, suivez ces instructions pour la correcte fonction de CIDRAM sur la majorité de systèmes et CMS:

1) Parce que vous lisez ceci, je suppose que vous avez déjà téléchargé une archivée copie du script, décompressé son contenu et l'ont assis sur votre locale machine. Maintenant, vous devez déterminer la approprié emplacement sur votre hôte ou CMS à mettre ces contenus. Un répertoire comme `/public_html/cidram/` ou similaire (cependant, il n'est pas question que vous choisissez, à condition que c'est quelque part de sûr et quelque part que vous êtes heureux avec) sera suffira. *Vous avant commencer téléchargement au serveur, continuer lecture..*

2) Renommer `config.ini.RenameMe` à `config.ini` (situé à l'intérieur de `vault`), et facultativement (fortement recommandé pour les utilisateurs avancés, mais pas recommandé pour les débutants ou pour les novices), l'ouvrir (ce fichier contient toutes les directives disponible pour CIDRAM; au-dessus de chaque option devrait être un bref commentaire décrivant ce qu'il fait et ce qu'il est pour). Réglez ces options comme bon vous semble, selon ce qui est approprié pour votre particulière configuration. Enregistrer le fichier, et fermer.

3) Télécharger les contenus (CIDRAM et ses fichiers) à le répertoire vous aviez décidé plus tôt (vous n'avez pas besoin les `*.txt`/`*.md` fichiers, mais surtout, vous devriez télécharger tous les fichiers sur le serveur).

4) CHMOD la `vault` répertoire à "755" (s'il y a des problèmes, vous pouvez essayer "777", mais c'est moins sûr). Le principal répertoire qui est stocker le contenu (celui que vous avez choisi plus tôt), généralement, peut être laissé seul, mais CHMOD état devrait être vérifié si vous avez eu problèmes d'autorisations dans le passé sur votre système (par défaut, devrait être quelque chose comme "755").

5) Suivant, vous aurez besoin de l'attacher CIDRAM à votre système ou CMS. Il est plusieurs façons vous pouvez attacher CIDRAM à votre système ou CMS, mais le plus simple est à simplement inclure le script au début d'un fichier de la base de données de votre système ou CMS (un qui va généralement toujours être chargé lorsque quelqu'un accède à n'importe quelle page sur votre website) utilisant un `require` ou `include` déclaration. Généralement, ce sera quelque chose de stocké dans un répertoire comme `/includes`, `/assets` ou `/functions`, et il sera souvent nommé quelque chose comme `init.php`, `common_functions.php`, `functions.php` ou similaire. Vous sera besoin à déterminer qui est le fichier c'est pour votre situation; Si vous rencontrez des difficultés pour la détermination de ce par vous-même, à l'aide, visitez la page des problèmes/issues pour CIDRAM à Github. Pour ce faire [à utiliser `require` ou `include`], insérez la ligne de code suivante au début de ce le noyau fichier et remplacer la string contenue à l'intérieur des guillemets avec l'exacte adresse le fichier `loader.php` (l'adresse locale, pas l'adresse HTTP; il ressemblera l'adresse de `vault` mentionné précédemment).

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

La mise à jour se fait manuellement, et vous pouvez personnaliser votre configuration et personnaliser les CIDRs sont bloqués par modifiant le fichier de configuration et/ou vos fichiers de signatures.

Si vous rencontrez des faux positifs, s'il vous plaît, contactez moi et parle moi de ça.

---


###4. <a name="SECTION4"></a>GESTION FRONT-END

@TODO@

---


###5. <a name="SECTION5"></a>FICHIERS INCLUS DANS CETTE PAQUET

Voici une liste de tous les fichiers inclus dans CIDRAM dans son natif état, tous les fichiers qui peuvent être potentiellement créées à la suite de l'utilisation de ce script, avec une brève description de ce que tous ces fichiers sont pour.

Fichier | Description
----|----
/_docs/ | Documentation répertoire (contient divers fichiers).
/_docs/readme.ar.md | Documentation en Arabe.
/_docs/readme.de.md | Documentation en Allemand.
/_docs/readme.en.md | Documentation en Anglais.
/_docs/readme.es.md | Documentation en Espagnol.
/_docs/readme.fr.md | Documentation en Français.
/_docs/readme.id.md | Documentation en Indonésien.
/_docs/readme.it.md | Documentation en Italien.
/_docs/readme.ja.md | Documentation en Japonaise.
/_docs/readme.nl.md | Documentation en Néerlandais.
/_docs/readme.pt.md | Documentation en Portugais.
/_docs/readme.ru.md | Documentation en Russe.
/_docs/readme.vi.md | Documentation en Vietnamien.
/_docs/readme.zh-TW.md | Documentation en Chinois (traditionnel).
/_docs/readme.zh.md | Documentation en Chinois (simplifié).
/vault/ | Voûte répertoire (contient divers fichiers).
/vault/fe_assets/ | Les fichiers de l'accès frontal.
/vault/fe_assets/.htaccess | Un hypertexte accès fichier (dans ce cas, pour protéger les sensibles fichiers appartenant au script contre être consulté par non autorisées sources).
/vault/fe_assets/_accounts.html | Un modèle HTML pour la page des comptes de l'accès frontal.
/vault/fe_assets/_accounts_row.html | Un modèle HTML pour la page des comptes de l'accès frontal.
/vault/fe_assets/_config.html | Un modèle HTML pour la page de configuration de l'accès frontal.
/vault/fe_assets/_home.html | Un modèle HTML pour la page d'accueil de l'accès frontal.
/vault/fe_assets/_ip_test.html | Un modèle HTML pour la page pour tester IPs.
/vault/fe_assets/_ip_test_row.html | Un modèle HTML pour la page pour tester IPs.
/vault/fe_assets/_login.html | Un modèle HTML pour la page pour la connexion de l'accès frontal.
/vault/fe_assets/_logs.html | Un modèle HTML pour la page pour les fichiers journaux de l'accès frontal.
/vault/fe_assets/_nav_complete_access.html | Un modèle HTML pour les liens de navigation de l'accès frontal, pour ceux qui ont accès complet.
/vault/fe_assets/_nav_logs_access_only.html | Un modèle HTML pour le front-end navigation links, pour ceux qui ont accès aux fichiers journaux seulement.
/vault/fe_assets/_updates.html | Un modèle HTML pour la page des mises à jour de l'accès frontal.
/vault/fe_assets/_updates_row.html | Un modèle HTML pour la page des mises à jour de l'accès frontal.
/vault/fe_assets/frontend.css | Feuille de style CSS pour l'accès frontal.
/vault/fe_assets/frontend.dat | Base de données pour l'accès frontal (contient des informations sur les comptes, informations sur les sessions, et le cache; généré seulement si l'accès frontal est activé et utilisé).
/vault/fe_assets/frontend.html | Le fichier modèle HTML principal pour l'accès frontal.
/vault/lang/ | Contient linguistiques données.
/vault/lang/.htaccess | Un hypertexte accès fichier (dans ce cas, pour protéger les sensibles fichiers appartenant au script contre être consulté par non autorisées sources).
/vault/lang/lang.ar.cli.php | Linguistiques données en Arabe pour CLI.
/vault/lang/lang.ar.fe.php | Linguistiques données en Arabe pour le front-end.
/vault/lang/lang.ar.php | Linguistiques données en Arabe.
/vault/lang/lang.de.cli.php | Linguistiques données en Allemand pour CLI.
/vault/lang/lang.de.fe.php | Linguistiques données en Allemand pour le front-end.
/vault/lang/lang.de.php | Linguistiques données en Allemand.
/vault/lang/lang.en.cli.php | Linguistiques données en Anglais pour CLI.
/vault/lang/lang.en.fe.php | Linguistiques données en Anglais pour le front-end.
/vault/lang/lang.en.php | Linguistiques données en Anglais.
/vault/lang/lang.es.cli.php | Linguistiques données en Espagnol pour CLI.
/vault/lang/lang.es.fe.php | Linguistiques données en Espagnol pour le front-end.
/vault/lang/lang.es.php | Linguistiques données en Espagnol.
/vault/lang/lang.fr.cli.php | Linguistiques données en Français pour CLI.
/vault/lang/lang.fr.fe.php | Linguistiques données en Français pour le front-end.
/vault/lang/lang.fr.php | Linguistiques données en Français.
/vault/lang/lang.id.cli.php | Linguistiques données en Indonésien pour CLI.
/vault/lang/lang.id.fe.php | Linguistiques données en Indonésien pour le front-end.
/vault/lang/lang.id.php | Linguistiques données en Indonésien.
/vault/lang/lang.it.cli.php | Linguistiques données en Italien pour CLI.
/vault/lang/lang.it.fe.php | Linguistiques données en Italien pour le front-end.
/vault/lang/lang.it.php | Linguistiques données en Italien.
/vault/lang/lang.ja.cli.php | Linguistiques données en Japonais pour CLI.
/vault/lang/lang.ja.fe.php | Linguistiques données en Japonais pour le front-end.
/vault/lang/lang.ja.php | Linguistiques données en Japonais.
/vault/lang/lang.nl.cli.php | Linguistiques données en Néerlandais pour CLI.
/vault/lang/lang.nl.fe.php | Linguistiques données en Néerlandais pour le front-end.
/vault/lang/lang.nl.php | Linguistiques données en Néerlandais.
/vault/lang/lang.pt.cli.php | Linguistiques données en Portugais pour CLI.
/vault/lang/lang.pt.fe.php | Linguistiques données en Portugais pour le front-end.
/vault/lang/lang.pt.php | Linguistiques données en Portugais.
/vault/lang/lang.ru.cli.php | Linguistiques données en Russe pour CLI.
/vault/lang/lang.ru.fe.php | Linguistiques données en Russe pour le front-end.
/vault/lang/lang.ru.php | Linguistiques données en Russe.
/vault/lang/lang.vi.cli.php | Linguistiques données en Vietnamien pour CLI.
/vault/lang/lang.vi.fe.php | Linguistiques données en Vietnamien pour le front-end.
/vault/lang/lang.vi.php | Linguistiques données en Vietnamien.
/vault/lang/lang.zh-tw.cli.php | Linguistiques données en Chinois (traditionnel) pour CLI.
/vault/lang/lang.zh-tw.fe.php | Linguistiques données en Chinois (traditionnel) pour le front-end.
/vault/lang/lang.zh-tw.php | Linguistiques données en Chinois (traditionnel).
/vault/lang/lang.zh.cli.php | Linguistiques données en Chinois (simplifié) pour CLI.
/vault/lang/lang.zh.fe.php | Linguistiques données en Chinois (simplifié) pour le front-end.
/vault/lang/lang.zh.php | Linguistiques données en Chinois (simplifié).
/vault/.htaccess | Un hypertexte accès fichier (dans ce cas, pour protéger les sensibles fichiers appartenant au script contre être consulté par non autorisées sources).
/vault/cache.dat | Données du cache.
/vault/cidramblocklists.dat | Contient des informations relatives aux listes facultatives pour les pays bloquants fourni par Macmathan; Utilisé par la page des mises à jour fournies par de l'accès frontal.
/vault/cli.php | Module de CLI.
/vault/components.dat | Contient des informations relatives aux divers composants de CIDRAM; Utilisé par la page des mises à jour fournies par de l'accès frontal.
/vault/config.ini.RenameMe | Fichier de configuration; Contient toutes les options de configuration pour CIDRAM, pour comment fonctionner correctement (renommer pour activer).
/vault/config.yaml | Fichier pour les valeurs par défaut de la configuration; Contient les valeurs par défaut de la configuration pour CIDRAM.
/vault/config.php | Module de configuration.
/vault/frontend.php | Module de l'accès frontal.
/vault/functions.php | Fichier de fonctions (essentiel).
/vault/hashes.dat | Contient une liste de hashes acceptées (pertinentes pour la fonction reCAPTCHA; seulement généré si la fonction reCAPTCHA est activée).
/vault/ignore.dat | Fichier de ignores (utilisé pour spécifier la signature sections CIDRAM devrait ignorer).
/vault/ipbypass.dat | Contient une liste de contournements IP (pertinentes pour la fonction reCAPTCHA; seulement généré si la fonction reCAPTCHA est activée).
/vault/ipv4.dat | Fichier de signatures pour IPv4.
/vault/ipv4_custom.dat.RenameMe | Fichier de signatures pour IPv4 personnalisés (renommer pour activer).
/vault/ipv6.dat | Fichier de signatures pour IPv6.
/vault/ipv6_custom.dat.RenameMe | Fichier de signatures pour IPv6 personnalisés (renommer pour activer).
/vault/lang.php | Module de linguistiques.
/vault/outgen.php | Générateur de sortie.
/vault/recaptcha.php | Module reCAPTCHA.
/vault/rules_as6939.php | Fichier de règles personnalisés pour AS6939.
/vault/rules_softlayer.php | Fichier de règles personnalisés pour Soft Layer.
/vault/rules_specific.php | Fichier de règles personnalisés pour certains CIDRs spécifiques.
/vault/salt.dat | Fichier de sel (utilisé par certaine fonctionnalité périphérique; seulement généré si nécessaire).
/vault/template.html | Modèle fichier; Modèle pour l'HTML sortie produit par CIDRAM pour son bloqués fichiers téléchargement message (le message vu par l'envoyeur).
/vault/template_custom.html | Modèle fichier; Modèle pour l'HTML sortie produit par CIDRAM pour son bloqués fichiers téléchargement message (le message vu par l'envoyeur).
/.gitattributes | Un fichier du Github projet (pas nécessaire pour le bon fonctionnement du script).
/Changelog.txt | Un enregistrement des modifications apportées au script entre les différentes versions (pas nécessaire pour le bon fonctionnement du script).
/composer.json | Composer/Packagist information (pas nécessaire pour le bon fonctionnement du script).
/LICENSE.txt | Une copie de la GNU/GPLv2 license (pas nécessaire pour le bon fonctionnement du script).
/loader.php | Chargeur/Loader. C'est ce que vous êtes censé être attacher dans à (essentiel)!
/README.md | Sommaire de l'information du projet.
/web.config | Un ASP.NET fichier de configuration (dans ce cas, pour protéger de la `/vault` répertoire contre d'être consulté par des non autorisée sources dans le cas où le script est installé sur un serveur basé sur les ASP.NET technologies).

---


###6. <a name="SECTION6"></a>OPTIONS DE CONFIGURATION
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
- Quels têtes devrait CIDRAM répondre avec lors de bloquer les demandes? False/200 = 200 OK [Défaut]; True/403 = 403 Forbidden (Interdit); 503 = 503 Service unavailable (Service indisponible).

"silent_mode"
- Devrait CIDRAM rediriger silencieusement les tentatives d'accès bloquées à la place de l'affichage de la page "Accès Refusé"? Si oui, spécifiez l'emplacement pour rediriger les tentatives d'accès bloquées. Si non, laisser cette variable vide.

"lang"
- Spécifiez la langue défaut pour CIDRAM.

"emailaddr"
- Si vous souhaitez, vous pouvez fournir une adresse e-mail ici à donner aux utilisateurs quand ils sont bloqués, pour qu'ils utilisent comme un point de contact pour soutien et/ou assistance dans le cas d'eux étant bloqué par erreur. AVERTISSEMENT: Tout de l'adresse e-mail vous fournissez ici sera très certainement être acquis par les robots des spammeurs et voleurs de contenu au cours de son être utilisés ici, et donc, il est recommandé fortement que si vous choisissez pour fournir une adresse e-mail ici, de vous assurer que l'adresse e-mail que vous fournissez ici est une adresse jetable et/ou une adresse que ne vous dérange pas d'être spammé (en d'autres termes, vous ne voulez probablement pas d'utiliser votre adresses e-mail personnel primaire ou d'affaires primaire).

"disable_cli"
- Désactiver le mode CLI? Le mode CLI est activé par défaut, mais peut parfois interférer avec certains test outils (comme PHPUnit, par exemple) et d'autres applications basées sur CLI. Si vous n'avez pas besoin désactiver le mode CLI, vous devrait ignorer cette directive. False = Activer le mode CLI [Défaut]; True = Désactiver le mode CLI.

"disable_frontend"
- Désactiver l'accès frontal? L'accès frontal peut rendre CIDRAM plus facile à gérer, mais peut aussi être un risque potentiel pour la sécurité. Il est recommandé de gérer CIDRAM via le back-end chaque fois que possible, mais l'accès frontal est prévu pour quand il est impossible. Seulement activer si vous avez besoin. False = Activer l'accès frontal; True = Désactiver l'accès frontal [Défaut].

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

####"recaptcha" (Catégorie)
Si vous souhaitez, vous pouvez fournir aux utilisateurs un moyen de contourner la page de «Accès Refusé» par voie de complétant d'une instance reCAPTCHA. Cela peut aider à atténuer certains risques associés à des faux positifs dans les situations où nous ne sommes pas tout à fait sûr si une demande est à l'origine d'une machine ou d'un être humain.

En raison des risques associés à la fourniture d'une façon pour les utilisateurs pour contourner la page «Accès Refusé», généralement, je déconseille d'activer cette fonctionnalité à moins que vous sentez qu'il soit nécessaire de le faire. Situations dans lesquelles il serait nécessaire: Si vous avez des clients/utilisateurs pour votre site web qui ont besoin d'avoir accès, et si cela est quelque chose qui ne peut être pas compromise, mais si elles se connectant à partir d'un réseau hostile qui pourrait potentiellement être aussi transporter le trafic indésirable, et le blocage de ce trafic indésirable est aussi quelque chose qui ne peut pas être compromise, dans ces situations sans victoire, la fonctionnalité reCAPTCHA pourrait être utile un moyen de permettre aux clients/utilisateurs désirables, et en même temps tenir à l'écart le trafic indésirable à partir du même réseau. Cela dit, étant donné que l'objectif d'un CAPTCHA est pour la distinction entre les humains et les nonhumains, la fonctionnalité reCAPTCHA serait seulement aider dans ces situations sans victoire si nous voulons supposer que ce trafic indésirable est nonhumain (par exemple, spambots, scrapers, outils de piratage, trafic automatisé), plutôt d'être le trafic indésirable des humains (tels que les spammeurs humains, hackers, et al).

Pour obtenir une "site key" et une "secret key" (nécessaires à l'utilisation de reCAPTCHA), s'il vous plaît allez à: [https://developers.google.com/recaptcha/](https://developers.google.com/recaptcha/)

"usemode"
- Définit comment CIDRAM doit utiliser reCAPTCHA.
- 0 = reCAPTCHA est complètement désactivé (défaut).
- 1 = reCAPTCHA est activé pour toutes les signatures.
- 2 = reCAPTCHA est activé seulement pour les signatures appartenant à des sections spécialement marquées dans les fichiers de signatures.
- (Toute autre valeur sera traitée de la même manière que 0).

"lockip"
- Indique si hachages devrait être verrouillé à des IPs spécifiques. False = Cookies et hachages PEUVENT être utilisés sur plusieurs IPs (défaut). True = Cookies et hachages NE PEUVENT PAS être utilisés sur plusieurs IPs (cookies/hachages sont verrouillés à IPs).
- Note: La valeur de "lockip" est ignoré lorsque "lockuser" est false, en raison de ce que le mécanisme pour se souvenir de «utilisateurs» varie en fonction de cette valeur.

"lockuser"
- Indique si le succès d'une instance de reCAPTCHA devrait être verrouillé à des utilisateurs spécifiques. False = Le succès d'une instance de reCAPTCHA donnera accès à toutes les demandes provenant de la même adresse IP que celui utilisé par l'utilisateur de remplir l'instance du reCAPTCHA; Cookies et hachages ne sont pas utilisés; Au lieu, une liste blanche IP sera utilisé. True = Le succès d'une instance de reCAPTCHA donnera accès seulement à l'utilisateur remplissant l'instance du reCAPTCHA; Cookies et hachages sont utilisés pour mémoriser l'utilisateur; Une liste blanche IP n'est pas utilisé (défaut).

"sitekey"
- Cette valeur devrait correspondre à la "site key" pour votre reCAPTCHA, qui se trouve dans le tableau de bord reCAPTCHA.

"secret"
- Cette valeur devrait correspondre à la "secret key" pour votre reCAPTCHA, qui se trouve dans le tableau de bord reCAPTCHA.

"expiry"
- Quand "lockuser" est true (défaut), afin de se souvenir quand un utilisateur a passé avec succès une instance de reCAPTCHA, pour les futures demandes de page, CIDRAM génère un cookie HTTP standard contenant un hachage qui correspond à un enregistrement interne contenant ce même hachage; Les futures demandes de page utilisera ces hachage correspondant pour authentifier qu'un utilisateur a préalablement déjà passé une instance de reCAPTCHA. Quand "lockuser" est false, une liste blanche IP est utilisé pour déterminer si les demandes devraient être autorisée à partir de l'adresse IP de demandes entrantes; Les entrées sont ajoutées à cette liste blanche lorsque l'instance de reCAPTCHA est passé avec succès. Pour combien d'heures devrait ces cookies, hachages et les entrées du liste blanche rester valables? Défaut = 720 (1 mois).

"logfile"
- Enregistrez toutes les tentatives du reCAPTCHA? Si oui, indiquez le nom à utiliser pour le fichier d'enregistrement. Si non, laisser vide ce variable.

*Conseil utile: Si vous souhaitez, vous pouvez ajouter l'information pour la date/l'heure à les noms de vos fichiers pour enregistrement par des incluant ceux-ci au nom: `{yyyy}` pour l'année complète, `{yy}` pour l'année abrégée, `{mm}` pour mois, `{dd}` pour le jour, `{hh}` pour l'heure.*

*Exemples:*
- *`logfile='recaptcha.{yyyy}-{mm}-{dd}-{hh}.txt'`*

####"template_data" (Catégorie)
Directives/Variables pour les modèles et thèmes.

Correspond à la sortie HTML utilisé pour générer la page "Accès Refusé". Si vous utilisez des thèmes personnalisés pour CIDRAM, sortie HTML provient du `template_custom.html` fichier, et sinon, sortie HTML provient du `template.html` fichier. Variables écrites à cette section du fichier de configuration sont préparé pour la sortie HTML par voie de remplacer tous les noms de variables circonfixé par accolades trouvés dans la sortie HTML avec les variables données correspondant. Par exemple, où `foo="bar"`, toute instance de `<p>{foo}</p>` trouvés dans la sortie HTML deviendra `<p>bar</p>`.

"css_url"
- Le modèle fichier pour des thèmes personnalisés utilise les propriétés CSS externes, tandis que le modèle fichier pour le défaut thème utilise les propriétés CSS internes. Pour instruire CIDRAM d'utiliser le modèle fichier pour des thèmes personnalisés, spécifier l'adresse HTTP public de votre thèmes personnalisés CSS fichiers utilisant le `css_url` variable. Si vous laissez cette variable vide, CIDRAM va utiliser le modèle fichier pour le défaut thème.

---


###7. <a name="SECTION7"></a>FORMATS DE SIGNATURES

####7.0 BASES

Une description du format et de la structure du signatures utilisé par CIDRAM peut être trouvée documentée en plain-text dans les deux fichiers de signatures personnalisées. S'il vous plaît référez à cette documentation pour apprendre plus sur le format et la structure du signatures de CIDRAM.

Toutes les signatures IPv4 suivre le format: `xxx.xxx.xxx.xxx/yy [Function] [Param]`.
- `xxx.xxx.xxx.xxx` représente le début du bloc (les octets de l'adresse IP initiale dans le bloc).
- `yy` représente la taille du bloc [1-32].
- `[Function]` instruit le script ce qu'il faut faire avec la signature (la façon dont la signature doit être considérée).
- `[Param]` représente les informations complémentaires qui peuvent être exigés par `[Function]`.

Toutes les signatures IPv6 follow the format: `xxxx:xxxx:xxxx:xxxx::xxxx/yy [Function] [Param]`.
- `xxxx:xxxx:xxxx:xxxx::xxxx` représente le début du bloc (les octets de l'adresse IP initiale dans le bloc). Notation complète et notation abrégée sont à la fois acceptable (et chacun DOIT suivre les normes appropriées et pertinentes de la notation d'IPv6, mais avec une exception: une adresse IPv6 ne peut jamais commencer par une abréviation quand il est utilisé dans une signature pour ce script, en raison de la façon dont les CIDRs sont reconstruits par le script; Par exemple, `::1/128` doit être exprimée, quand il est utilisé dans une signature, comme `0::1/128`, et `::0/128` exprimée comme `0::/128`).
- `yy` représente la taille du bloc [1-128].
- `[Function]` instruit le script ce qu'il faut faire avec la signature (la façon dont la signature doit être considérée).
- `[Param]` représente les informations complémentaires qui peuvent être exigés par `[Function]`.

Les fichiers de signatures pour CIDRAM DEVRAIT utiliser les sauts de ligne de type Unix (`%0A`, or `\n`)! D'autres types/styles de sauts de ligne (par exemple, Windows `%0D%0A` ou `\r\n` sauts de ligne, Mac `%0D` ou `\r` sauts de ligne, etc) PEUT être utilisé, mais ne sont PAS préférés. Ceux sauts de ligne qui ne sont pas du type Unix sera normalisé à sauts de ligne de type Unix par le script.

Notation précise et correcte pour CIDRs est exigée, autrement le script ne sera PAS reconnaître les signatures. En outre, toutes les signatures CIDR de ce script DOIT commencer avec une adresse IP dont le numéro IP peut diviser uniformément dans la division du bloc représenté par la taille du bloc (par exemple, si vous voulez bloquer toutes les adresses IP a partir de `10.128.0.0` jusqu'à `11.127.255.255`, `10.128.0.0/8` ne serait PAS reconnu par le script, mais `10.128.0.0/9` et `11.0.0.0/9` utilisé en conjonction, SERAIT reconnu par le script).

Tout dans les fichiers de signatures non reconnu comme une signature ni comme liées à la syntaxe par le script seront IGNORÉS, donc ce qui signifie que vous pouvez mettre toutes les données non-signature que vous voulez dans les fichiers de signatures sans risque, sans les casser et sans casser le script. Les commentaires sont acceptables dans les fichiers de signatures, et aucun formatage spécial est nécessaire pour eux. Hachage dans le style de Shell pour les commentaires est préféré, mais pas forcée; Fonctionnellement, il ne fait aucune différence pour le script si vous choisissez d'utiliser hachage dans le style de Shell pour les commentaires, mais d'utilisation du hachage dans le style de Shell est utile pour IDEs et éditeurs de texte brut de mettre en surligner correctement les différentes parties des fichiers de signatures (et donc, hachage dans le style de Shell peut aider comme une aide visuelle lors de l'édition).

Les valeurs possibles de `[Function]` sont les suivants:
- Run
- Whitelist
- Greylist
- Deny

Si "Run" est utilisé, quand la signature est déclenchée, le script tentera d'exécuter (utilisant un statement `require_once`) un script PHP externe, spécifié par la valeur de `[Param]` (le répertoire de travail devrait être le répertoire "/vault/" du script).

Exemple: `127.0.0.0/8 Run example.php`

Cela peut être utile si vous voulez exécuter du code PHP spécifique pour certaines adresses IP et/ou CIDRs spécifiques.

Si "Whitelist" est utilisé, quand la signature est déclenchée, le script réinitialise toutes les détections (s'il y a eu des détections) et de briser la fonction du test. `[Param]` est ignorée. Cette fonction est l'équivalent de mettre une adresse IP ou CIDR particulière sur un whitelist pour empêcher la détection.

Exemple: `127.0.0.1/32 Whitelist`

Si "Greylist" est utilisé, quand la signature est déclenchée, le script réinitialise toutes les détections (s'il y a eu des détections) et passer au fichier de signatures suivant pour continuer le traitement. `[Param]` est ignorée.

Exemple: `127.0.0.1/32 Greylist`

Si "Deny" est utilisé, quand la signature est déclenchée, en supposant qu'aucune signature whitelist a été déclenchée pour l'adresse IP donnée et/ou CIDR donnée, accès à la page protégée sera refusée. "Deny" est ce que vous aurez envie d'utiliser d'effectivement bloquer une adresse IP et/ou CIDR. Quand quelconque les signatures sont déclenchées que faire usage de "Deny", la page "Access Denied" du script seront générés et la demande à la page protégée tué.

La valeur de `[Param]` accepté par "Deny" seront traitées au la sortie de la page "Accès Refusé", fourni au client/utilisateur comme la raison invoquée pour leur accès à la page demandée étant refusée. Il peut être une phrase courte et simple, expliquant pourquoi vous avez choisi de les bloquer (quoi que ce soit devrait suffire, même une simple "Je ne veux tu pas sur mon site"), ou l'un d'une petite poignée de mots courts fourni par le script, que si elle est utilisée, sera remplacé par le script avec une explication pré-préparés des raisons pour lesquelles le client/utilisateur a été bloqué.

Les explications pré-préparés avoir le soutien de L10N et peut être traduit par le script sur la base de la langue que vous spécifiez à la directive `lang` de la configuration du script. En outre, vous pouvez demander le script d'ignorer signatures de "Deny" sur la base de leur valeur de `[Param]` (s'ils utilisent ces mots courts) par les directives indiquées par la configuration du script (chaque mot court a une directive correspondante à traiter les signatures correspondant ou d'ignorer les). Les valeurs de `[Param]` qui ne pas utiliser ces mots courts, toutefois, n'avoir pas le soutien de L10N et donc ne seront PAS traduits par le script, et en outre, ne sont pas directement contrôlables par la configuration du script.

Les mots courts disponibles sont:
- Bogon
- Cloud
- Generic
- Proxy
- Spam

####7.1 BALISES

Si vous voulez partager vos signatures personnalisées en sections individuelles, vous pouvez identifier ces sections individuelles au script par ajoutant une "balise de section" immédiatement après les signatures de chaque section, inclus avec le nom de votre section de signatures (voir l'exemple ci-dessous).

```
# Section 1.
1.2.3.4/32 Deny Bogon
2.3.4.5/32 Deny Cloud
4.5.6.7/32 Deny Generic
5.6.7.8/32 Deny Spam
6.7.8.9/32 Deny Proxy
Tag: Section 1
```

Pour briser les balises de section et assurer que les balises ne sont pas identifié incorrectement pour les sections de signatures à partir de plus tôt dans les fichiers, assurez-vous simplement qu'il ya au moins deux sauts de ligne consécutifs entre votre balise et vos sections précédent. Toutes les signatures non balisé sera par défaut soit "IPv4" ou "IPv6" (en fonction de quels types de signatures sont déclenchés).

```
1.2.3.4/32 Deny Bogon
2.3.4.5/32 Deny Cloud

4.5.6.7/32 Deny Generic
5.6.7.8/32 Deny Spam
Tag: Section 1
```

Dans l'exemple ci-dessus `1.2.3.4/32` et `2.3.4.5/32` seront balisés comme "IPv4", tandis que `4.5.6.7/32` et `5.6.7.8/32` seront balisés comme "Section 1".

Si vous voulez des signatures expirent après un certain temps, d'une manière similaire aux les balises de section, vous pouvez utiliser une "balise d'expiration" à spécifier quand les signatures doivent cesser d'être valide. Les balises d'expiration utilisent le format "AAAA.MM.JJ" (voir l'exemple ci-dessous).

```
# Section 1.
1.2.3.4/32 Deny Generic
2.3.4.5/32 Deny Generic
Expires: 2016.12.31
```

Les balises de section et les balises d'expiration peuvent être utilisés en conjonction, et les deux sont optionnel (voir l'exemple ci-dessous).

```
# Section Exemple.
1.2.3.4/32 Deny Generic
Tag: Section Exemple
Expires: 2016.12.31
```

####7.2 YAML

#####7.2.0 BASES DE YAML

Une forme simplifiée de YAML peut être utilisé dans les fichiers de signature dans le but de définir des comportements et des paramètres spécifiques aux différentes sections de signatures. Cela peut être utile si vous voulez que la valeur de vos directives de configuration différer sur la base des signatures individuelles et des sections de signature (par exemple; si vous voulez fournir une adresse e-mail pour les tickets de support pour tous les utilisateurs bloqués par une signature particulière, mais ne veulent pas fournir une adresse e-mail pour les tickets de support pour les utilisateurs bloqués par d'autres signatures; si vous voulez des signatures spécifiques pour déclencher une redirection de page; si vous voulez marquer une section de signature pour l'utilisation avec reCAPTCHA; si vous voulez enregistrer les tentatives d'accès bloquées à des fichiers séparés sur la base des signatures individuelles et/ou des sections de signatures).

L'utilisation de YAML dans les fichiers de signature est entièrement facultative (c'est à dire, vous pouvez l'utiliser si vous le souhaitez, mais vous n'êtes pas obligé de le faire), et est capable d'affecter la plupart (mais pas tout) les directives de configuration.

Note: L'implémentation de YAML dans CIDRAM est très simpliste et très limitée; L'intention est de satisfaire aux exigences spécifiques à CIDRAM d'une manière qui a la familiarité de YAML, mais ne suit pas et ne sont pas conformes aux spécifications officielles (et ne sera donc pas se comporter de la même manière que des implémentations plus approfondies ailleurs, et peuvent ne pas convenir à d'autres projets ailleurs).

Dans CIDRAM, segments YAML sont identifiés au script par trois tirets ("---"), et terminer aux côtés de leur contenant sections de signature par sauts de ligne double. Un segment YAML typique dans une section de signatures se compose de trois tirets sur une ligne immédiatement après la liste des CIDRs et des balises, suivi d'une liste de bidimensionnelle paires clé-valeur (première dimension, catégories de directives de configuration; deuxième dimension, directives de configuration) pour les directives de configuration que doivent être modifiés (et pour quelles valeurs) chaque fois qu'une signature dans cette section de signatures est déclenchée (voir les exemples ci-dessous).

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

#####7.2.1 COMMENT "SPÉCIALEMENT MARQUER" LES SECTIONS DE SIGNATURE POUR L'UTILISATION AVEC reCAPTCHA

Quand "usemode" est 0 ou 1, les sections de signature ne doivent pas être "spécialement marqué" pour l'utilisation avec reCAPTCHA (parce qu'ils déjà seront ou non utiliser reCAPTCHA, en fonction de ce paramètre).

Quand "usemode" est 2, à "spécialement marquer" les sections de signature pour l'utilisation avec reCAPTCHA, une entrée est incluse dans le segment de YAML pour cette section de signatures (voir l'exemple ci-dessous).

```
# Cette section utilisera reCAPTCHA.
1.2.3.4/32 Deny Generic
2.3.4.5/32 Deny Generic
Tag: reCAPTCHA-Enabled
---
recaptcha:
 enabled: true
```

Note: Une instance de reCAPTCHA sera SEULEMENT présenté à l'utilisateur si reCAPTCHA est activé (soit avec "usemode" comme 1, ou "usemode" comme 2 avec "enabled" comme true), et si exactement UNE signature a été déclenchée (ni plus ni moins; si plusieurs signatures sont déclenchées, une instance de reCAPTCHA NE SERA PAS présenté).

####7.3 AUXILIAIRE

En addition, si vous voulez CIDRAM à ignorer complètement certaines sections spécifiques dans aucun des fichiers de signatures, vous pouvez utiliser le fichier `ignore.dat` pour spécifier les sections à ignorer. Sur une nouvelle ligne, écrire `Ignore`, suivi d'un espace, suivi du nom de la section que vous souhaitez CIDRAM à ignorer (voir l'exemple ci-dessous).

```
Ignore Section 1
```

Reportez-vous aux fichiers de signatures personnalisées pour plus d'informations.

---


###8. <a name="SECTION8"></a>QUESTIONS FRÉQUEMMENT POSÉES (FAQ)

####Qu'est-ce qu'un «faux positif»?

Le terme «faux positif» (*alternativement: «erreur faux positif»; «fausse alarme»*; Anglais: *false positive*; *false positive error*; *false alarm*), décrit très simplement, et dans un contexte généralisé, est utilisé lors de tester pour une condition, de se référer aux résultats de ce test, lorsque les résultats sont positifs (c'est à dire, lorsque la condition est déterminée comme étant «positif», ou «vrai»), mais ils devraient être (ou aurait dû être) négatif (c'est à dire, lorsque la condition, en réalité, est «négatif», ou «faux»). Un «faux positif» pourrait être considérée comme analogue à «crier au loup» (où la condition testée est de savoir s'il y a un loup près du troupeau, la condition est «faux» en ce que il n'y a pas de loup près du troupeau, et la condition est signalé comme «positif» par le berger par voie de crier "loup, loup"), ou analogues à des situations dans des tests médicaux dans lequel un patient est diagnostiqué comme ayant une maladie, alors qu'en réalité, ils ont pas une telle maladie.

Résultats connexes lors de tester pour une condition peut être décrit en utilisant les termes «vrai positif», «vrai négatif» et «faux négatif». Un «vrai positif» se réfère à quand les résultats du test et l'état actuel de la condition sont tous deux vrai (or «positif»), and a «vrai négatif» se réfère à quand les résultats du test et l'état actuel de la condition sont tous deux faux (ou «négatif»); Un «vrai positif» ou «vrai négatif» est considéré comme une «inférence correcte». L'antithèse d'un «faux positif» est un «faux négatif»; Un «faux négatif» se réfère à quand les résultats du test are négatif (c'est à dire, la condition est déterminée comme étant «négatif», ou «faux»), mais ils devraient être (ou aurait dû être) positif (c'est à dire, la condition, en réalité, est «positif», ou «vrai»).

Dans le contexte de CIDRAM, ces termes réfèrent à les signatures de CIDRAM et que/qui ils bloquent. Quand CIDRAM bloque une adresse IP en raison du mauvais, obsolète ou signatures incorrectes, mais ne devrait pas l'avoir fait, ou quand il le fait pour les mauvaises raisons, nous référons à cet événement comme un «faux positif». Quand CIDRAM ne parvient pas à bloquer une adresse IP qui aurait dû être bloqué, en raison de menaces imprévues, signatures manquantes ou déficits dans ses signatures, nous référons à cet événement comme un «détection manquée» ou «missed detection» (qui est analogue à un «faux négatif»).

Ceci peut être résumé par le tableau ci-dessous:

&nbsp; | CIDRAM ne devrait *PAS* bloquer une adresse IP | CIDRAM *DEVRAIT* bloquer une adresse IP
---|---|---
CIDRAM ne bloque *PAS* une adresse IP | Vrai négatif (inférence correcte) | Détection manquée (analogue à faux négatif)
CIDRAM bloque une adresse IP | __Faux positif__ | Vrai positif (inférence correcte)

---


Dernière Mise à Jour: 18 Novembre 2016 (2016.11.18).
