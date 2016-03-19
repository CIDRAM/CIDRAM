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

2) Facultativement (fortement recommandé pour les utilisateurs avancés, mais pas recommandé pour les débutants ou pour les novices), ouvrir `config.ini` (situé à l'intérieur de `vault`) - Ce fichier contient toutes les directives disponible pour CIDRAM. Au-dessus de chaque option devrait être un bref commentaire décrivant ce qu'il fait et ce qu'il est pour. Réglez ces options comme bon vous semble, selon ce qui est approprié pour votre particulière configuration. Enregistrer le fichier, fermer.

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
/LICENSE.txt | Une copie de la GNU/GPLv2 license.
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
/vault/config.ini | Fichier de configuration; Contient toutes les options de configuration pour CIDRAM, pour comment fonctionner correctement (essentiel)!
/vault/config.php | Module de configuration.
/vault/functions.php | Fichier de fonctions (essentiel).
/vault/ipv4.dat | Fichier de signatures pour IPv4.
/vault/ipv4_custom.dat | Fichier de signatures pour IPv4 personnalisés.
/vault/ipv6.dat | Fichier de signatures pour IPv6.
/vault/ipv6_custom.dat | Fichier de signatures pour IPv6 personnalisés.
/vault/lang.php | Module de linguistiques.
/vault/lang/ | Contient linguistiques données.
/vault/lang/.htaccess | Un hypertexte accès fichier (dans ce cas, pour protéger les sensibles fichiers appartenant au script contre être consulté par non autorisées sources).
/vault/lang/lang.en.php | Linguistiques données en Anglais.
/vault/lang/lang.es.php | Linguistiques données en Espagnol.
/vault/lang/lang.fr.php | Linguistiques données en Français.
/vault/lang/lang.id.php | Linguistiques données en Indonésien.
/vault/lang/lang.it.php | Linguistiques données en Italien.
/vault/lang/lang.nl.php | Linguistiques données en Néerlandais.
/vault/lang/lang.pt.php | Linguistiques données en Portugais.
/vault/lang/lang.zh-TW.php | Linguistiques données en Chinois (traditionnel).
/vault/lang/lang.zh.php | Linguistiques données en Chinois (simplifié).
/vault/outgen.php | Générateur de sortie.
/vault/template.html | Modèle fichier; Modèle pour l'HTML sortie produit par CIDRAM pour son bloqués fichiers téléchargement message (le message vu par l'envoyeur).
/vault/template_custom.html | Modèle fichier; Modèle pour l'HTML sortie produit par CIDRAM pour son bloqués fichiers téléchargement message (le message vu par l'envoyeur).
/vault/rules_as6939.php | Fichier de règles personnalisés pour AS6939.
/vault/rules_softlayer.php | Fichier de règles personnalisés pour Soft Layer.

---


###5. <a name="SECTION5"></a>OPTIONS DE CONFIGURATION
Ce qui suit est une liste des directives disponibles pour CIDRAM dans le `config.ini` fichier de configuration, avec une description de leur objectif et leur fonction.

####"general" (Catégorie)
Configuration générale pour CIDRAM.

"logfile"
- Nom du fichier pour enregistrement de toutes les tentatives d'accès bloquées. Spécifier un fichier, ou laisser vide à désactiver.

"ipaddr"
- Où trouver l'adresse IP de demandes de connexion? (Utile pour services tels que Cloudflare et similaires) Par Défaut = REMOTE_ADDR. AVERTISSEMENT: Ne pas changer si vous ne sais pas ce que vous faites!

"forbid_on_block"
- Devrait CIDRAM envoyer 403 headers avec demandes bloquées, ou rester avec l'habituel 200 OK? False = Non (200) [Défaut]; True = Oui (403).

"lang"
- Spécifiez la langue défaut pour CIDRAM.

"emailaddr"
- Si vous souhaitez, vous pouvez fournir une adresse e-mail ici à donner aux utilisateurs quand ils sont bloqués, pour qu'ils utilisent comme un point de contact pour soutien et/ou assistance dans le cas d'eux étant bloqué par erreur. AVERTISSEMENT: Tout de l'adresse e-mail vous fournissez ici sera très certainement être acquis par les robots des spammeurs et voleurs de contenu au cours de son être utilisés ici, et donc, il est recommandé fortement que si vous choisissez pour fournir une adresse e-mail ici, de vous assurer que l'adresse e-mail que vous fournissez ici est une adresse jetable et/ou une adresse que ne vous dérange pas d'être spammé (en d'autres termes, vous ne voulez probablement pas d'utiliser votre adresses e-mail personnel primaire ou d'affaires primaire).

"disable_cli"
- Désactiver le mode CLI? Le mode CLI est activé par défaut, mais peut parfois interférer avec certains test outils (comme PHPUnit, par exemple) et d'autres applications basées sur CLI. Si vous n'avez pas besoin désactiver le mode CLI, vous devrait ignorer cette directive. False = Activer le mode CLI [Défaut]; True = Désactiver le mode CLI.

####"signatures" (Catégorie)
Configuration pour les signatures.

"block_cloud"
- Bloquer CIDRs identifié comme appartenant à hébergement/cloud services? Si vous utilisez un service d'API à partir de votre website ou si vous attendez d'autres sites à connecter avec votre website, cette directive devrait être fixé sur false. Si vous ne pas, puis, cette directive doit être fixé comme true.

"block_bogons"
- Bloquer CIDRs bogon/martian? Si vous attendre connexions à votre website à partir de dans votre réseau local, à partir de localhost, ou à partir de votre LAN, cette directive devrait être fixé sur false. Si vous ne attendez pas à ces telles connexions, cette directive doit être fixé comme true.

"block_generic"
- Bloquer CIDRs recommandé en généralement pour les listes noires? Cela couvre toutes les signatures qui ne sont pas marqué comme étant partie de l'autre plus spécifique catégories de signatures.

"block_spam"
- Bloquer CIDRs identifié comme étant risque élevé pour le spam? Sauf si vous rencontrez des problèmes quand vous faire, en généralement, cette directive devrait toujours être fixé comme true.

---


###6. <a name="SECTION6"></a>FORMATS DE SIGNATURES

Une description du format et de la structure du signatures utilisé par CIDRAM peut être trouvée documentée en plain-text dans les deux fichiers de signatures personnalisées. S'il vous plaît référez à cette documentation pour apprendre plus sur le format et la structure du signatures de CIDRAM.

---


Dernière Réactualisé: 20 Mars 2016 (2016.03.20).
