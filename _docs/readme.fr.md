## Documentation pour CIDRAM (Français).

### Contenu
- 1. [PRÉAMBULE](#SECTION1)
- 2. [COMMENT INSTALLER](#SECTION2)
- 3. [COMMENT UTILISER](#SECTION3)
- 4. [GESTION L'ACCÈS FRONTAL](#SECTION4)
- 5. [FICHIERS INCLUS DANS CETTE PAQUET](#SECTION5)
- 6. [OPTIONS DE CONFIGURATION](#SECTION6)
- 7. [FORMATS DE SIGNATURES](#SECTION7)
- 8. [PROBLÈMES DE COMPATIBILITÉ CONNUS](#SECTION8)
- 9. [QUESTIONS FRÉQUEMMENT POSÉES (FAQ)](#SECTION9)
- 10. *Réservé pour les ajouts futurs à la documentation.*
- 11. [INFORMATION LÉGALE](#SECTION11)

*Note concernant les traductions : En cas d'erreurs (par exemple, différences entre les traductions, fautes de frappe, etc), la version Anglaise du README est considérée comme la version originale et faisant autorité. Si vous trouvez des erreurs, votre aide pour les corriger serait bienvenue.*

---


### 1. <a name="SECTION1"></a>PRÉAMBULE

CIDRAM (Classless Inter-Domain Routing Access Manager) est un script PHP conçu pour la protection des sites web par bloquant les requêtes de page produit à partir d'adresses IP considéré comme étant sources de trafic indésirable, comprenant (mais pas limité a) le trafic de terminaux d'accès non humains, services de cloud computing, spambots, scrapers, etc. Elle le fait en calculant les CIDRs possibles des adresses IP fournie par les requêtes entrantes puis essayant pour correspondre à ces CIDRs possibles contre ses fichiers de signatures (ces fichiers de signatures contenir des listes de CIDRs d'adresses IP considéré comme étant sources de trafic indésirable) ; Si des correspondances sont trouvées, les requêtes sont bloquées.

*(Voir : [Qu'est-ce qu'un « CIDR » ?](#WHAT_IS_A_CIDR)).*

CIDRAM COPYRIGHT 2016 et au-delà GNU/GPLv2 par Caleb M (Maikuolan).

Ce script est un logiciel libre ; vous pouvez redistribuer et/ou le modifier selon les termes de la GNU General Public License telle que publiée par la Free Software Foundation ; soit la version 2 de la Licence, ou (à votre choix) toute version ultérieure. Ce script est distribué dans l'espoir qu'il sera utile, mais SANS AUCUNE GARANTIE, sans même l'implicite garantie de COMMERCIALISATION ou D'ADAPTATION À UN PARTICULIER USAGE. Voir la GNU General Public License pour plus de détails, situé dans le `LICENSE.txt` fichier et disponible également à partir de :
- <http://www.gnu.org/licenses/>.
- <http://opensource.org/licenses/>.

Ce document et son associé paquet peuvent être téléchargé gratuitement à sans frais à partir de [GitHub](https://cidram.github.io/).

---


### 2. <a name="SECTION2"></a>COMMENT INSTALLER

#### 2.0 INSTALLATION MANUELLE

1) Parce que vous lisez ceci, je suppose que vous avez déjà téléchargé une archivée copie du script, décompressé son contenu et l'ont assis sur votre locale machine. Maintenant, vous devez déterminer l'approprié emplacement sur votre hôte ou CMS à mettre ces contenus. Un répertoire comme `/public_html/cidram/` ou similaire (cependant, il n'est pas question que vous choisissez, à condition que c'est quelque part de sûr et quelque part que vous êtes heureux avec) sera suffira. *Vous avant commencer téléchargement au serveur, continuer lecture..*

2) Renommer `config.ini.RenameMe` à `config.ini` (situé à l'intérieur de `vault`), et facultativement (fortement recommandé pour les utilisateurs avancés, mais pas recommandé pour les débutants ou pour les novices), l'ouvrir (ce fichier contient toutes les directives disponible pour CIDRAM ; au-dessus de chaque option devrait être un bref commentaire décrivant ce qu'il fait et ce qu'il est pour). Réglez ces options comme bon vous semble, selon ce qui est approprié pour votre particulière configuration. Enregistrer le fichier, et fermer.

3) Télécharger les contenus (CIDRAM et ses fichiers) à le répertoire vous aviez décidé plus tôt (vous n'avez pas besoin les `*.txt`/`*.md` fichiers, mais surtout, vous devriez télécharger tous les fichiers sur le serveur).

4) CHMOD la `vault` répertoire à « 755 » (s'il y a des problèmes, vous pouvez essayer « 777 », mais c'est moins sûr). Le principal répertoire qui est stocker le contenu (celui que vous avez choisi plus tôt), généralement, peut être laissé seul, mais CHMOD état devrait être vérifié si vous avez eu problèmes d'autorisations dans le passé sur votre système (par défaut, devrait être quelque chose comme « 755 »). En bref : Pour que le paquet fonctionne correctement, PHP doit pouvoir lire et écrire des fichiers dans le répertoire `vault`. Beaucoup de choses (mise à jour, journalisation, etc) ne seront pas possibles si PHP ne peut pas écrire dans le répertoire `vault`, et le paquet ne fonctionnera pas du tout si PHP ne peut pas lire le répertoire `vault`. Cependant, pour une sécurité optimale, le répertoire `vault` ne doit PAS être accessible au public (des informations sensibles, telles que les informations contenues dans `config.ini` ou `frontend.dat`, pourraient être exposées à des attaquants potentiels si le répertoire `vault` était accessible au public).

5) Suivant, vous aurez besoin de l'attacher CIDRAM à votre système ou CMS. Il est plusieurs façons vous pouvez attacher CIDRAM à votre système ou CMS, mais le plus simple est à simplement inclure le script au début d'un fichier de la base de données de votre système ou CMS (un qui va généralement toujours être chargé lorsque quelqu'un accède à n'importe quelle page sur votre site web) utilisant un `require` ou `include` déclaration. Généralement, ce sera quelque chose de stocké dans un répertoire comme `/includes`, `/assets` ou `/functions`, et il sera souvent nommé quelque chose comme `init.php`, `common_functions.php`, `functions.php` ou similaire. Vous sera besoin à déterminer qui est le fichier c'est pour votre situation ; Si vous rencontrez des difficultés pour la détermination de ce par vous-même, à l'aide, visitez la page des issues pour CIDRAM à GitHub. Pour ce faire [à utiliser `require` ou `include`], insérez la ligne de code suivante au début de ce le noyau fichier et remplacer la string contenue à l'intérieur des guillemets avec l'exacte adresse le fichier `loader.php` (l'adresse locale, pas l'adresse HTTP ; il ressemblera l'adresse de `vault` mentionné précédemment).

`<?php require '/user_name/public_html/cidram/loader.php'; ?>`

Enregistrer le fichier, fermer, rétélécharger.

-- OU ALTERNATIVEMENT --

Si vous utilisez un Apache serveur web et si vous avez accès à `php.ini`, vous pouvez utiliser la `auto_prepend_file` directive à préfixer CIDRAM chaque fois qu'une requête de PHP est faite. Quelque chose comme :

`auto_prepend_file = "/user_name/public_html/cidram/loader.php"`

Ou cette dans le `.htaccess` fichier :

`php_value auto_prepend_file "/user_name/public_html/cidram/loader.php"`

6) C'est tout ! :-)

#### 2.1 INSTALLATION AVEC COMPOSER

[CIDRAM est enregistré avec Packagist](https://packagist.org/packages/cidram/cidram), et donc, si vous êtes familier avec Composer, vous pouvez utiliser Composer pour installer CIDRAM (vous devrez néanmoins préparer la configuration et les attaches ; voir « installation manuelle » les étapes 2 et 5).

`composer require cidram/cidram`

#### 2.2 INSTALLATION POUR WORDPRESS

Si vous souhaitez utiliser CIDRAM avec WordPress, vous pouvez ignorer toutes les instructions ci-dessus. [CIDRAM est enregistré comme un plugin avec la base de données des plugins WordPress](https://wordpress.org/plugins/cidram/), et vous pouvez installer CIDRAM directement à partir du tableau de bord des plugins. Vous pouvez l'installer de la même manière que n'importe quel autre plugin, et aucune étape supplémentaire n'est requise. Tout comme pour les autres méthodes d'installation, vous pouvez personnaliser votre installation en modifiant le contenu du fichier `config.ini` ou en utilisant la page de configuration de l'accès frontal. Si vous activez de l'accès frontal de CIDRAM et mettez à jour le CIDRAM à l'aide de la page des mises à jour de l'accès frontal, cela se synchronisera automatiquement avec les informations de version du plugin affichées dans le tableau de bord des plugins.

*Avertissement : La mise à jour de CIDRAM via le tableau de bord des plugins entraîne une installation propre. Si vous avez personnalisé votre installation (modifié votre configuration, installés modules, etc), ces personnalisations seront perdues lors de la mise à jour via le tableau de bord des plugins ! Les fichiers journaux seront également perdus lors de la mise à jour via le tableau de bord des plugins ! Pour conserver les fichiers journaux et les personnalisations, mettez à jour via la page de mise à jour de l'accès frontal de CIDRAM.*

---


### 3. <a name="SECTION3"></a>COMMENT UTILISER

CIDRAM devrait bloquer automatiquement les requêtes indésirables à votre site web sans nécessitant aucune intervention manuelle, en dehors de son installation initiale.

Vous pouvez personnaliser votre configuration et personnaliser les CIDRs sont bloqués par modifiant le fichier de configuration et/ou vos fichiers de signatures.

Si vous rencontrez des faux positifs, s'il vous plaît, contactez moi et parle moi de ça. *(Voir : [Qu'est-ce qu'un « faux positif » ?](#WHAT_IS_A_FALSE_POSITIVE)).*

CIDRAM peut être mis à jour manuellement ou via le frontal. CIDRAM peut également être mis à jour via Composer ou WordPress, si initialement installé via ces moyens.

---


### 4. <a name="SECTION4"></a>GESTION L'ACCÈS FRONTAL

#### 4.0 CE QUI EST L'ACCÈS FRONTAL.

L'accès frontal fournit un moyen pratique et facile de gérer, de maintenir et de mettre à jour votre installation de CIDRAM. Vous pouvez afficher, partager et télécharger des fichiers journaux via la page des journaux, vous pouvez modifier la configuration via la page de configuration, vous pouvez installer et désinstaller des composants via la page des mises à jour, et vous pouvez télécharger et modifier des fichiers dans votre vault via le gestionnaire de fichiers.

L'accès frontal est désactivée par défaut afin d'empêcher tout accès non autorisé (l'accès non autorisé pourrait avoir des conséquences importantes pour votre site web et sa sécurité). Les instructions pour l'activer sont incluses ci-dessous.

#### 4.1 COMMENT ACTIVER L'ACCÈS FRONTAL.

1) Localiser la directive `disable_frontend` à l'intérieur de `config.ini`, et réglez-le sur `false` (il sera `true` par défaut).

2) Accéder `loader.php` à partir de votre navigateur (par exemple, `http://localhost/cidram/loader.php`).

3) Connectez-vous avec le nom d'utilisateur et le mot de passe défaut (admin/password).

Remarque : Après vous être connecté pour la première fois, afin d'empêcher l'accès frontal non autorisé, vous devez immédiatement changer votre nom d'utilisateur et votre mot de passe ! C'est très important, car il est possible de télécharger du code PHP arbitraire à votre site Web via l'accès frontal.

Aussi, pour une sécurité optimale, il est fortement recommandé d'activer « l'authentification à deux facteurs » pour tous les comptes frontaux (instructions fournies ci-dessous).

#### 4.2 COMMENT UTILISER L'ACCÈS FRONTAL.

Des instructions sont fournies sur chaque page de l'accès frontal, pour expliquer la manière correcte de l'utiliser et son but. Si vous avez besoin d'autres explications ou d'une assistance spéciale, veuillez contacter le support technique. Alternativement, il ya quelques vidéos disponibles sur YouTube qui pourraient aider par voie de démonstration.

#### 4.3 AUTHENTIFICATION À DEUX FACTEURS

Il est possible de sécuriser l'accès frontal en activant l'authentification à deux facteurs (« 2FA »). Lors de la connexion à l'aide d'un compte sur lequel 2FA est activé, un e-mail est envoyé à l'adresse électronique associée à ce compte. Cet e-mail contient un « code 2FA », que l'utilisateur doit alors entrer, en plus du nom d'utilisateur et du mot de passe, afin de pouvoir authentifier ce compte. Cela signifie que l'obtention d'un mot de passe d'un compte ne serait pas suffisant pour qu'un attaquant potentiel puisse authentifier ce compte, comme ils auraient également besoin d'avoir déjà accès à l'adresse électronique associée à ce compte afin de pouvoir recevoir et utiliser le code 2FA associé à la session, rendant ainsi l'accès frontal plus sécurisé.

Avant toute chose, pour activer l'authentification à deux facteurs, à l'aide de la page des mises à jour frontales, installez le composant PHPMailer. CIDRAM utilise PHPMailer pour envoyer des emails. Il convient de noter que bien que CIDRAM, en soi, est compatible avec PHP >= 5.4.0, PHPMailer a besoin de PHP >= 5.5.0, ce qui signifie que l'activation de l'authentification à deux facteurs pour l'accès frontal sur CIDRAM ne sera pas possible pour les utilisateurs de PHP 5.4.

Après avoir installé PHPMailer, vous devez renseigner les directives de configuration de PHPMailer via la page de configuration ou le fichier de configuration de CIDRAM. Plus d'informations sur ces directives de configuration sont incluses dans la section de configuration de ce document. Après avoir rempli les directives de configuration de PHPMailer, mettre `Enable2FA` à `true`. L'authentification à deux facteurs devrait maintenant être activée.

Ensuite, vous devrez associer une adresse e-mail à un compte afin que CIDRAM sache où envoyer les codes 2FA lors de la connexion via ce compte. Pour ce faire, utilisez l'adresse e-mail comme nom d'utilisateur pour le compte (comme `foo@bar.tld`), ou inclure l'adresse e-mail dans le nom d'utilisateur de la même manière que lorsqu'un e-mail est envoyé normalement (comme `Foo Bar <foo@bar.tld>`).

Remarque : Protéger votre vault contre les accès non autorisés (par exemple, en renforçant la sécurité de votre serveur et les autorisations d'accès public), est particulièrement important ici, en raison de cet accès non autorisé à votre fichier de configuration (qui est stocké dans votre vault), risque d'exposer vos paramètres SMTP sortants (qui comprend le nom d'utilisateur et le mot de passe pour votre SMTP). Vous devez vous assurer que votre vault est correctement sécurisé avant de activer l'authentification à deux facteurs. Si vous ne pouvez pas le faire, vous devez au moins créer un nouveau compte e-mail, dédié à cet effet, afin de réduire les risques associés aux paramètres SMTP exposés.

---


### 5. <a name="SECTION5"></a>FICHIERS INCLUS DANS CETTE PAQUET

Voici une liste de tous les fichiers inclus dans CIDRAM dans son natif état, tous les fichiers qui peuvent être potentiellement créées à la suite de l'utilisation de ce script, avec une brève description de ce que tous ces fichiers sont pour.

Fichier | Description
----|----
/_docs/ | Documentation répertoire (contient divers fichiers).
/_docs/readme.ar.md | Documentation en Arabe.
/_docs/readme.de.md | Documentation en Allemande.
/_docs/readme.en.md | Documentation en Anglais.
/_docs/readme.es.md | Documentation en Espagnol.
/_docs/readme.fr.md | Documentation en Français.
/_docs/readme.id.md | Documentation en Indonésienne.
/_docs/readme.it.md | Documentation en Italienne.
/_docs/readme.ja.md | Documentation en Japonaise.
/_docs/readme.ko.md | Documentation en Coréenne.
/_docs/readme.nl.md | Documentation en Néerlandaise.
/_docs/readme.pt.md | Documentation en Portugaise.
/_docs/readme.ru.md | Documentation en Russe.
/_docs/readme.ur.md | Documentation en Urdu.
/_docs/readme.vi.md | Documentation en Vietnamienne.
/_docs/readme.zh-TW.md | Documentation en Chinois (traditionnel).
/_docs/readme.zh.md | Documentation en Chinois (simplifié).
/vault/ | Voûte répertoire (contient divers fichiers).
/vault/fe_assets/ | Les fichiers de l'accès frontal.
/vault/fe_assets/.htaccess | Un hypertexte accès fichier (dans ce cas, pour protéger les sensibles fichiers appartenant au script contre être consulté par non autorisées sources).
/vault/fe_assets/_2fa.html | Un modèle HTML utilisé pour demander à l'utilisateur un code 2FA.
/vault/fe_assets/_accounts.html | Un modèle HTML pour la page des comptes de l'accès frontal.
/vault/fe_assets/_accounts_row.html | Un modèle HTML pour la page des comptes de l'accès frontal.
/vault/fe_assets/_cache.html | Un modèle HTML pour la page del données de cache de l'accès frontal.
/vault/fe_assets/_cidr_calc.html | Un modèle HTML pour le calculatrice CIDR.
/vault/fe_assets/_cidr_calc_row.html | Un modèle HTML pour le calculatrice CIDR.
/vault/fe_assets/_config.html | Un modèle HTML pour la page de configuration de l'accès frontal.
/vault/fe_assets/_config_row.html | Un modèle HTML pour la page de configuration de l'accès frontal.
/vault/fe_assets/_files.html | Un modèle HTML pour le gestionnaire de fichiers.
/vault/fe_assets/_files_edit.html | Un modèle HTML pour le gestionnaire de fichiers.
/vault/fe_assets/_files_rename.html | Un modèle HTML pour le gestionnaire de fichiers.
/vault/fe_assets/_files_row.html | Un modèle HTML pour le gestionnaire de fichiers.
/vault/fe_assets/_home.html | Un modèle HTML pour la page d'accueil de l'accès frontal.
/vault/fe_assets/_ip_aggregator.html | Un modèle HTML pour pour l'agrégateur IP.
/vault/fe_assets/_ip_test.html | Un modèle HTML pour la page pour tester IPs.
/vault/fe_assets/_ip_test_row.html | Un modèle HTML pour la page pour tester IPs.
/vault/fe_assets/_ip_tracking.html | Un modèle HTML pour la page de surveillance IP.
/vault/fe_assets/_ip_tracking_row.html | Un modèle HTML pour la page de surveillance IP.
/vault/fe_assets/_login.html | Un modèle HTML pour la page pour la connexion de l'accès frontal.
/vault/fe_assets/_logs.html | Un modèle HTML pour la page pour les fichiers journaux de l'accès frontal.
/vault/fe_assets/_nav_complete_access.html | Un modèle HTML pour les liens de navigation de l'accès frontal, pour ceux qui ont accès complet.
/vault/fe_assets/_nav_logs_access_only.html | Un modèle HTML pour les liens de navigation de l'accès frontal, pour ceux qui ont accès aux fichiers journaux seulement.
/vault/fe_assets/_range.html | Un modèle HTML pour la page pour les tableaux de gamme de l'accès frontal.
/vault/fe_assets/_range_row.html | Un modèle HTML pour la page pour les tableaux de gamme de l'accès frontal.
/vault/fe_assets/_sections.html | Un modèle HTML pour la liste des sections.
/vault/fe_assets/_sections_row.html | Un modèle HTML pour la liste des sections.
/vault/fe_assets/_statistics.html | Un modèle HTML pour la page de statistiques de l'accès frontal.
/vault/fe_assets/_updates.html | Un modèle HTML pour la page des mises à jour de l'accès frontal.
/vault/fe_assets/_updates_row.html | Un modèle HTML pour la page des mises à jour de l'accès frontal.
/vault/fe_assets/frontend.css | Feuille de style CSS pour l'accès frontal.
/vault/fe_assets/frontend.dat | Base de données pour l'accès frontal (contient des informations sur les comptes, informations sur les sessions, et le cache ; généré seulement si l'accès frontal est activé et utilisé).
/vault/fe_assets/frontend.html | Le fichier modèle HTML principal pour l'accès frontal.
/vault/fe_assets/icons.php | Gestionnaire d'icônes (utilisé par le gestionnaire de fichiers de l'accès frontal).
/vault/fe_assets/pips.php | Gestionnaire de pips (utilisé par le gestionnaire de fichiers de l'accès frontal).
/vault/fe_assets/scripts.js | Contient des données JavaScript pour l'accès frontal.
/vault/lang/ | Contient données linguistiques.
/vault/lang/.htaccess | Un hypertexte accès fichier (dans ce cas, pour protéger les sensibles fichiers appartenant au script contre être consulté par non autorisées sources).
/vault/lang/lang.ar.cli.php | Données linguistiques en Arabe pour CLI.
/vault/lang/lang.ar.fe.php | Données linguistiques en Arabe pour l'accès frontal.
/vault/lang/lang.ar.php | Données linguistiques en Arabe.
/vault/lang/lang.bn.cli.php | Données linguistiques en Bangla pour CLI.
/vault/lang/lang.bn.fe.php | Données linguistiques en Bangla pour l'accès frontal.
/vault/lang/lang.bn.php | Données linguistiques en Bangla.
/vault/lang/lang.de.cli.php | Données linguistiques en Allemande pour CLI.
/vault/lang/lang.de.fe.php | Données linguistiques en Allemande pour l'accès frontal.
/vault/lang/lang.de.php | Données linguistiques en Allemande.
/vault/lang/lang.en.cli.php | Données linguistiques en Anglais pour CLI.
/vault/lang/lang.en.fe.php | Données linguistiques en Anglais pour l'accès frontal.
/vault/lang/lang.en.php | Données linguistiques en Anglais.
/vault/lang/lang.es.cli.php | Données linguistiques en Espagnol pour CLI.
/vault/lang/lang.es.fe.php | Données linguistiques en Espagnol pour l'accès frontal.
/vault/lang/lang.es.php | Données linguistiques en Espagnol.
/vault/lang/lang.fr.cli.php | Données linguistiques en Français pour CLI.
/vault/lang/lang.fr.fe.php | Données linguistiques en Français pour l'accès frontal.
/vault/lang/lang.fr.php | Données linguistiques en Français.
/vault/lang/lang.hi.cli.php | Données linguistiques en Hindi pour CLI.
/vault/lang/lang.hi.fe.php | Données linguistiques en Hindi pour l'accès frontal.
/vault/lang/lang.hi.php | Données linguistiques en Hindi.
/vault/lang/lang.id.cli.php | Données linguistiques en Indonésienne pour CLI.
/vault/lang/lang.id.fe.php | Données linguistiques en Indonésienne pour l'accès frontal.
/vault/lang/lang.id.php | Données linguistiques en Indonésienne.
/vault/lang/lang.it.cli.php | Données linguistiques en Italienne pour CLI.
/vault/lang/lang.it.fe.php | Données linguistiques en Italienne pour l'accès frontal.
/vault/lang/lang.it.php | Données linguistiques en Italienne.
/vault/lang/lang.ja.cli.php | Données linguistiques en Japonaise pour CLI.
/vault/lang/lang.ja.fe.php | Données linguistiques en Japonaise pour l'accès frontal.
/vault/lang/lang.ja.php | Données linguistiques en Japonaise.
/vault/lang/lang.ko.cli.php | Données linguistiques en Coréenne pour CLI.
/vault/lang/lang.ko.fe.php | Données linguistiques en Coréenne pour l'accès frontal.
/vault/lang/lang.ko.php | Données linguistiques en Coréenne.
/vault/lang/lang.nl.cli.php | Données linguistiques en Néerlandaise pour CLI.
/vault/lang/lang.nl.fe.php | Données linguistiques en Néerlandaise pour l'accès frontal.
/vault/lang/lang.nl.php | Données linguistiques en Néerlandaise.
/vault/lang/lang.no.cli.php | Données linguistiques en Norvégienne pour CLI.
/vault/lang/lang.no.fe.php | Données linguistiques en Norvégienne pour l'accès frontal.
/vault/lang/lang.no.php | Données linguistiques en Norvégienne.
/vault/lang/lang.pt.cli.php | Données linguistiques en Portugaise pour CLI.
/vault/lang/lang.pt.fe.php | Données linguistiques en Portugaise pour l'accès frontal.
/vault/lang/lang.pt.php | Données linguistiques en Portugaise.
/vault/lang/lang.ru.cli.php | Données linguistiques en Russe pour CLI.
/vault/lang/lang.ru.fe.php | Données linguistiques en Russe pour l'accès frontal.
/vault/lang/lang.ru.php | Données linguistiques en Russe.
/vault/lang/lang.sv.cli.php | Données linguistiques en Suédois pour CLI.
/vault/lang/lang.sv.fe.php | Données linguistiques en Suédois pour l'accès frontal.
/vault/lang/lang.sv.php | Données linguistiques en Suédois.
/vault/lang/lang.th.cli.php | Données linguistiques en Thai pour CLI.
/vault/lang/lang.th.fe.php | Données linguistiques en Thai pour l'accès frontal.
/vault/lang/lang.th.php | Données linguistiques en Thai.
/vault/lang/lang.tr.cli.php | Données linguistiques en Turc pour CLI.
/vault/lang/lang.tr.fe.php | Données linguistiques en Turc pour l'accès frontal.
/vault/lang/lang.tr.php | Données linguistiques en Turc.
/vault/lang/lang.ur.cli.php | Données linguistiques en Urdu pour CLI.
/vault/lang/lang.ur.fe.php | Données linguistiques en Urdu pour l'accès frontal.
/vault/lang/lang.ur.php | Données linguistiques en Urdu.
/vault/lang/lang.vi.cli.php | Données linguistiques en Vietnamienne pour CLI.
/vault/lang/lang.vi.fe.php | Données linguistiques en Vietnamienne pour l'accès frontal.
/vault/lang/lang.vi.php | Données linguistiques en Vietnamienne.
/vault/lang/lang.zh-tw.cli.php | Données linguistiques en Chinois (traditionnel) pour CLI.
/vault/lang/lang.zh-tw.fe.php | Données linguistiques en Chinois (traditionnel) pour l'accès frontal.
/vault/lang/lang.zh-tw.php | Données linguistiques en Chinois (traditionnel).
/vault/lang/lang.zh.cli.php | Données linguistiques en Chinois (simplifié) pour CLI.
/vault/lang/lang.zh.fe.php | Données linguistiques en Chinois (simplifié) pour l'accès frontal.
/vault/lang/lang.zh.php | Données linguistiques en Chinois (simplifié).
/vault/.htaccess | Un hypertexte accès fichier (dans ce cas, pour protéger les sensibles fichiers appartenant au script contre être consulté par non autorisées sources).
/vault/.travis.php | Utilisé par Travis CI pour le tester (pas nécessaire pour le bon fonctionnement du script).
/vault/.travis.yml | Utilisé par Travis CI pour le tester (pas nécessaire pour le bon fonctionnement du script).
/vault/aggregator.php | Agrégateur IP.
/vault/cache.dat | Données du cache.
/vault/cidramblocklists.dat | Fichier de métadonnées pour les listes de blocage facultatives de Macmathan ; Contient les valeurs par défaut de la configuration pour CIDRAM.
/vault/cli.php | Module de CLI.
/vault/components.dat | Fichier de métadonnées de composants ; Utilisé par la page des mises à jour frontales.
/vault/config.ini.RenameMe | Fichier de configuration ; Contient toutes les options de configuration pour CIDRAM, pour comment fonctionner correctement (renommer pour activer).
/vault/config.php | Module de configuration.
/vault/config.yaml | Fichier pour les valeurs par défaut de la configuration ; Contient les valeurs par défaut de la configuration pour CIDRAM.
/vault/frontend.php | Module de l'accès frontal.
/vault/frontend_functions.php | Fichier de fonctions de l'accès frontal.
/vault/functions.php | Fichier de fonctions (essentiel).
/vault/hashes.dat | Contient une liste de hashes acceptées (pertinentes pour la fonction reCAPTCHA ; seulement généré si la fonction reCAPTCHA est activée).
/vault/ignore.dat | Fichier de ignores (utilisé pour spécifier la signature sections CIDRAM devrait ignorer).
/vault/ipbypass.dat | Contient une liste de contournements IP (pertinentes pour la fonction reCAPTCHA ; seulement généré si la fonction reCAPTCHA est activée).
/vault/ipv4.dat | Fichier de signatures pour IPv4 (les services cloud indésirables et les noeuds finaux non humains).
/vault/ipv4_bogons.dat | Fichier de signatures pour IPv4 (bogon/martien CIDRs).
/vault/ipv4_custom.dat.RenameMe | Fichier de signatures pour IPv4 personnalisés (renommer pour activer).
/vault/ipv4_isps.dat | Fichier de signatures pour IPv4 (ISPs dangereux et sujets au spam).
/vault/ipv4_other.dat | Fichier de signatures pour IPv4 (CIDRs pour les proxies, VPN et autres services indésirables).
/vault/ipv6.dat | Fichier de signatures pour IPv6 (les services cloud indésirables et les noeuds finaux non humains).
/vault/ipv6_bogons.dat | Fichier de signatures pour IPv6 (bogon/martien CIDRs).
/vault/ipv6_custom.dat.RenameMe | Fichier de signatures pour IPv6 personnalisés (renommer pour activer).
/vault/ipv6_isps.dat | Fichier de signatures pour IPv6 (ISPs dangereux et sujets au spam).
/vault/ipv6_other.dat | Fichier de signatures pour IPv6 (CIDRs pour les proxies, VPN et autres services indésirables).
/vault/lang.php | Module de linguistiques.
/vault/modules.dat | Fichier de métadonnées de modules ; Utilisé par la page des mises à jour frontales.
/vault/outgen.php | Générateur de sortie.
/vault/php5.4.x.php | Polyfills pour PHP 5.4.X (Requis pour la compatibilité descendante de PHP 5.4.X ; safe à supprimer pour les versions plus récentes de PHP).
/vault/recaptcha.php | Module reCAPTCHA.
/vault/rules_as6939.php | Fichier de règles personnalisés pour AS6939.
/vault/rules_softlayer.php | Fichier de règles personnalisés pour Soft Layer.
/vault/rules_specific.php | Fichier de règles personnalisés pour certains CIDRs spécifiques.
/vault/salt.dat | Fichier de sel (utilisé par certaine fonctionnalité périphérique ; seulement généré si nécessaire).
/vault/template_custom.html | Modèle fichier ; Modèle pour l'HTML sortie produit par CIDRAM pour son bloqués fichiers téléchargement message (le message vu par l'envoyeur).
/vault/template_default.html | Modèle fichier ; Modèle pour l'HTML sortie produit par CIDRAM pour son bloqués fichiers téléchargement message (le message vu par l'envoyeur).
/vault/themes.dat | Fichier de métadonnées de thèmes ; Utilisé par la page des mises à jour frontales.
/vault/verification.yaml | Données de vérification pour les moteurs de recherche et les médias sociaux.
/.gitattributes | Un fichier du GitHub projet (pas nécessaire pour le bon fonctionnement du script).
/Changelog.txt | Un enregistrement des modifications apportées au script entre les différentes versions (pas nécessaire pour le bon fonctionnement du script).
/composer.json | Composer/Packagist information (pas nécessaire pour le bon fonctionnement du script).
/CONTRIBUTING.md | Informations sur la façon de contribuer au projet.
/LICENSE.txt | Une copie de la GNU/GPLv2 license (pas nécessaire pour le bon fonctionnement du script).
/loader.php | Chargeur/Loader. C'est ce que vous êtes censé être attacher dans à (essentiel) !
/README.md | Sommaire de l'information du projet.
/web.config | Un ASP.NET fichier de configuration (dans ce cas, pour protéger de la `/vault` répertoire contre d'être consulté par des non autorisée sources dans le cas où le script est installé sur un serveur basé sur les ASP.NET technologies).

---


### 6. <a name="SECTION6"></a>OPTIONS DE CONFIGURATION
Ce qui suit est une liste des directives disponibles pour CIDRAM dans le `config.ini` fichier de configuration, avec une description de leur objectif et leur fonction.

#### « general » (Catégorie)
Configuration générale pour CIDRAM.

##### « logfile »
- Un fichier lisible par l'homme pour enregistrement de toutes les tentatives d'accès bloquées. Spécifier un fichier, ou laisser vide à désactiver.

##### « logfileApache »
- Un fichier dans le style d'Apache pour enregistrement de toutes les tentatives d'accès bloquées. Spécifier un fichier, ou laisser vide à désactiver.

##### « logfileSerialized »
- Un fichier sérialisé pour enregistrement de toutes les tentatives d'accès bloquées. Spécifier un fichier, ou laisser vide à désactiver.

*Conseil utile : Si vous souhaitez, vous pouvez ajouter l'information pour la date/l'heure à les noms de vos fichiers pour enregistrement par des incluant ceux-ci au nom : `{yyyy}` pour l'année complète, `{yy}` pour l'année abrégée, `{mm}` pour mois, `{dd}` pour le jour, `{hh}` pour l'heure.*

*Exemples :*
- *`logfile='logfile.{yyyy}-{mm}-{dd}-{hh}.txt'`*
- *`logfileApache='access.{yyyy}-{mm}-{dd}-{hh}.txt'`*
- *`logfileSerialized='serial.{yyyy}-{mm}-{dd}-{hh}.txt'`*

##### « truncate »
- Tronquer les fichiers journaux lorsqu'ils atteignent une certaine taille ? La valeur est la taille maximale en o/Ko/Mo/Go/To qu'un fichier journal peut croître avant d'être tronqué. La valeur par défaut de 0Ko désactive la troncature (les fichiers journaux peuvent croître indéfiniment). Remarque : S'applique aux fichiers journaux individuels ! La taille des fichiers journaux n'est pas considérée collectivement.

##### « log_rotation_limit »
- La rotation du journal limite le nombre de fichiers journaux qui doivent exister à un moment donné. Lorsque de nouveaux fichiers journaux sont créés, si le nombre total de fichiers journaux dépasse la limite spécifiée, l'action spécifiée sera effectuée. Vous pouvez spécifier la limite souhaitée ici. Une valeur de 0 désactivera la rotation du journal.

##### « log_rotation_action »
- La rotation du journal limite le nombre de fichiers journaux qui doivent exister à un moment donné. Lorsque de nouveaux fichiers journaux sont créés, si le nombre total de fichiers journaux dépasse la limite spécifiée, l'action spécifiée sera effectuée. Vous pouvez spécifier l'action souhaitée ici. Delete = Supprimez les fichiers journaux les plus anciens, jusqu'à ce que la limite ne soit plus dépassée. Archive = Tout d'abord archiver, puis supprimez les fichiers journaux les plus anciens, jusqu'à ce que la limite ne soit plus dépassée.

*Clarification technique : Dans ce contexte, « plus ancien » signifie moins récemment modifié.*

##### « timeOffset »
- Si votre temps serveur ne correspond pas à votre temps locale, vous pouvez spécifier un offset ici pour régler l'information en date/temps généré par CIDRAM selon vos besoins. Il est généralement recommandé à la place pour ajuster la directive de fuseau horaire dans votre fichier `php.ini`, mais parfois (tels que lorsque l'on travaille avec des fournisseurs d'hébergement partagé limitées) ce n'est pas toujours possible de faire, et donc, cette option est disponible ici. Offset est en minutes.
- Exemple (à ajouter une heure) : `timeOffset=60`

##### « timeFormat »
- Le format de notation de la date/heure utilisé par CIDRAM. Défaut = `{Day}, {dd} {Mon} {yyyy} {hh}:{ii}:{ss} {tz}`.

##### « ipaddr »
- Où trouver l'adresse IP de requêtes ? (Utile pour services tels que Cloudflare et similaires). Par Défaut = REMOTE_ADDR. AVERTISSEMENT : Ne pas changer si vous ne sais pas ce que vous faites !

Valeurs recommandées pour « ipaddr » :

Valeur | En utilisant
---|---
`HTTP_INCAP_CLIENT_IP` | Proxy inversé Incapsula.
`HTTP_CF_CONNECTING_IP` | Proxy inversé Cloudflare.
`CF-Connecting-IP` | Proxy inversé Cloudflare (alternative ; si ce qui précède ne fonctionne pas).
`HTTP_X_FORWARDED_FOR` | Proxy inversé Cloudbric.
`X-Forwarded-For` | [Proxy inversé Squid](http://www.squid-cache.org/Doc/config/forwarded_for/).
*Défini par la configuration du serveur.* | [Proxy inversé Nginx](https://www.nginx.com/resources/admin-guide/reverse-proxy/).
`REMOTE_ADDR` | Pas de proxy inversé (valeur par défaut).

##### « forbid_on_block »
- Quel message d'état HTTP devrait être envoyé par CIDRAM lors du blocage des requêtes ?

Valeurs actuellement supportées :

Code d'état | Message d'état | Description
---|---|---
`200` | `200 OK` | Valeur par défaut. Le moins robuste, mais le plus convivial.
`403` | `403 Forbidden` | Plus robuste, mais moins convivial.
`410` | `410 Gone` | Cela peut provoquer des problèmes lors de la tentative de résolution de faux positifs, car certains navigateurs mettent en cache ce message d'état et n'envoient pas de requêtes ultérieures, même après avoir débloqué des utilisateurs. Peut être cependant plus utile que d'autres options pour réduire les requêtes de certains types de bots très spécifiques.
`418` | `418 I'm a teapot` | Fait référence à une blague d'un poisson d'avril [[RFC 2324](https://tools.ietf.org/html/rfc2324#section-6.5.14)] et est peu susceptible d'être compris par le client. Fourni pour le divertissement et la commodité, mais pas généralement recommandé.
`451` | `Unavailable For Legal Reasons` | Approprié pour les contextes où les requêtes sont bloquées principalement pour des raisons juridiques. Non recommandé dans d'autres contextes.
`503` | `Service Unavailable` | Le plus robuste, mais le moins convivial.

##### « silent_mode »
- Devrait CIDRAM rediriger silencieusement les tentatives d'accès bloquées à la place de l'affichage de la page « Accès Refusé » ? Si oui, spécifiez l'emplacement pour rediriger les tentatives d'accès bloquées. Si non, laisser cette variable vide.

##### « lang »
- Spécifiez la langue défaut pour CIDRAM.

##### « numbers »
- Spécifie comment afficher les nombres.

Valeurs actuellement supportées :

Valeur | Produit | Description
---|---|---
`NoSep-1` | `1234567.89`
`NoSep-2` | `1234567,89`
`Latin-1` | `1,234,567.89` | Valeur par défaut.
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

*Remarque : Ces valeurs ne sont standardisées nulle part, et ne seront probablement pas pertinentes au-delà du package. Aussi, les valeurs supportées peuvent changer à l'avenir.*

##### « emailaddr »
- Si vous souhaitez, vous pouvez fournir une adresse e-mail ici à donner aux utilisateurs quand ils sont bloqués, pour qu'ils utilisent comme un point de contact pour support et/ou assistance dans le cas d'eux étant bloqué par erreur. AVERTISSEMENT : Tout de l'adresse e-mail vous fournissez ici sera très certainement être acquis par les robots des spammeurs et voleurs de contenu au cours de son être utilisés ici, et donc, il est recommandé fortement que si vous choisissez pour fournir une adresse e-mail ici, de vous assurer que l'adresse e-mail que vous fournissez ici est une adresse jetable et/ou une adresse que ne vous dérange pas d'être spammé (en d'autres termes, vous ne voulez probablement pas d'utiliser votre adresses e-mail personnel primaire ou d'affaires primaire).

##### « emailaddr_display_style »
- Comment préférez-vous que l'adresse électronique soit présentée aux utilisateurs ? « default » = Lien cliquable. « noclick » = Texte non-cliquable.

##### « disable_cli »
- Désactiver le mode CLI ? Le mode CLI est activé par défaut, mais peut parfois interférer avec certains test outils (comme PHPUnit, par exemple) et d'autres applications basées sur CLI. Si vous n'avez pas besoin désactiver le mode CLI, vous devrait ignorer cette directive. False = Activer le mode CLI [Défaut] ; True = Désactiver le mode CLI.

##### « disable_frontend »
- Désactiver l'accès frontal ? L'accès frontal peut rendre CIDRAM plus facile à gérer, mais peut aussi être un risque potentiel pour la sécurité. Il est recommandé de gérer CIDRAM via le back-end chaque fois que possible, mais l'accès frontal est prévu pour quand il est impossible. Seulement activer si vous avez besoin. False = Activer l'accès frontal ; True = Désactiver l'accès frontal [Défaut].

##### « max_login_attempts »
- Nombre maximal de tentatives de connexion (l'accès frontal). Défaut = 5.

##### « FrontEndLog »
- Fichier pour l'enregistrement des tentatives de connexion à l'accès frontal. Spécifier un fichier, ou laisser vide à désactiver.

##### « ban_override »
- Remplacer « forbid_on_block » lorsque « infraction_limit » est dépassé ? En cas de remplacement : Les requêtes bloquées renvoient une page blanche (les fichiers modèles ne sont pas utilisés). 200 = Ne pas remplacer [Défaut]. Les autres valeurs sont les mêmes que les valeurs disponibles pour « forbid_on_block ».

##### « log_banned_ips »
- Inclure les requêtes bloquées provenant d'IP interdites dans les fichiers journaux ? True = Oui [Défaut] ; False = Non.

##### « default_dns »
- Une liste délimitée par des virgules de serveurs DNS à utiliser pour les recherches de noms d'hôtes. Par Défaut = « 8.8.8.8,8.8.4.4 » (Google DNS). AVERTISSEMENT : Ne pas changer si vous ne sais pas ce que vous faites !

*Voir également : [Que puis-je utiliser pour « default_dns » ?](#WHAT_CAN_I_USE_FOR_DEFAULT_DNS)*

##### « search_engine_verification »
- Essayez de vérifier les moteurs de recherche ? Vérification des moteurs de recherche assure qu'ils ne seront pas interdits en raison de dépassement de la limite d'infraction (l'interdiction des moteurs de recherche de votre site web aura généralement un effet négatif sur votre moteur de recherche classement, SEO, etc). Lorsqu'ils sont vérifiés, les moteurs de recherche peuvent être bloqués comme d'habitude, mais ne seront pas interdits. Lorsqu'ils ne sont pas vérifiés, il est possible qu'ils soient interdits en raison du dépassement de la limite d'infraction. Aussi, la vérification des moteurs de recherche offre une protection contre les fausses requêtes des moteurs de recherche et contre les entités potentiellement malveillantes masquer comme moteurs de recherche (ces requêtes seront bloquées lorsque la vérification des moteurs de recherche est activée). True = Activer la vérification du moteurs de recherche [Défaut] ; False = Désactiver la vérification du moteurs de recherche.

Supporté actuellement :
- __[Google](https://support.google.com/webmasters/answer/80553?hl=en)__
- __[Bing](https://blogs.bing.com/webmaster/2012/08/31/how-to-verify-that-bingbot-is-bingbot)__
- __Yahoo__
- __[Baidu (百度)](https://help.baidu.com/question?prod_en=master&class=Baiduspider)__
- __Sogou (搜狗)__
- __Youdao (有道)__
- __[Applebot](https://discussions.apple.com/thread/7090135)__
- __[Yandex (Яндекс)](https://yandex.com/support/webmaster/robot-workings/check-yandex-robots.xml)__
- __[DuckDuckGo](https://duckduckgo.com/duckduckbot)__

N'est pas compatible (provoque des conflits) :
- __[Mix.com](https://github.com/CIDRAM/CIDRAM/issues/80)__

##### « social_media_verification »
- Essayez de vérifier des requêtes de médias sociaux ? La vérification des médias sociaux offre une protection contre les fausses requêtes de médias sociaux (ces requêtes seront bloquées). True = Activer la vérification des médias sociaux [Défaut] ; False = Désactiver la vérification des médias sociaux.

Supporté actuellement :
- __[Pinterest](https://help.pinterest.com/en/articles/about-pinterest-crawler-0)__
- __Embedly__
- __[Grapeshot](https://www.grapeshot.com/crawler/)__
- __Twitterbot__

##### « protect_frontend »
- Spécifie si les protections normalement fournies par CIDRAM doivent être appliquées à l'accès frontal. True = Oui [Défaut] ; False = Non.

##### « disable_webfonts »
- Désactiver les webfonts ? True = Oui [Défaut] ; False = Non.

##### « maintenance_mode »
- Activer le mode de maintenance ? True = Oui ; False = Non [Défaut]. Désactive tout autre que l'accès frontal. Parfois utile pour la mise à jour de votre CMS, des frameworks, etc.

##### « default_algo »
- Définit quel algorithme utiliser pour tous les mots de passe et les sessions à l'avenir. Options : PASSWORD_DEFAULT (défaut), PASSWORD_BCRYPT, PASSWORD_ARGON2I (nécessite PHP >= 7.2.0).

##### « statistics »
- Suivre les statistiques d'utilisation pour CIDRAM ? True = Oui ; False = Non [Défaut].

##### « force_hostname_lookup »
- Forcer les recherches de nom d'hôte ? True = Oui ; False = Non [Défaut]. Les recherches de nom d'hôte sont normalement effectuées « au besoin », mais peuvent être forcées pour toutes les requêtes. Cela peut être utile pour fournir des informations plus détaillées dans les fichiers journaux, mais peut également avoir un effet légèrement négatif sur les performances.

##### « allow_gethostbyaddr_lookup »
- Autoriser les recherches par gethostbyaddr lorsque UDP est indisponible ? True = Yes [Default]; False = No.
- *Remarque : La recherche de IPv6 peut ne pas fonctionner correctement sur certains systèmes 32-bits.*

##### « hide_version »
- Masquer les informations de version à partir des journaux et de la sortie de la page ? True = Oui ; False = Non [Défaut].

#### « signatures » (Catégorie)
Configuration pour les signatures.

##### « ipv4 »
- Une liste des fichiers du signatures IPv4 que CIDRAM devrait tenter d'utiliser, délimité par des virgules. Vous pouvez ajouter des entrées ici si vous voulez inclure des fichiers supplémentaires dans CIDRAM.

##### « ipv6 »
- Une liste des fichiers du signatures IPv6 que CIDRAM devrait tenter d'utiliser, délimité par des virgules. Vous pouvez ajouter des entrées ici si vous voulez inclure des fichiers supplémentaires dans CIDRAM.

##### « block_cloud »
- Bloquer les CIDRs identifié comme appartenant aux services d'hébergement/cloud ? Si vous utilisez un service d'API à partir de votre site web ou si vous attendez d'autres sites à connecter avec votre site web, cette directive devrait être fixé sur false. Si vous ne pas, puis, cette directive doit être fixé comme true.

##### « block_bogons »
- Bloquer les CIDRs bogon/martian ? Si vous attendre connexions à votre site web à partir de dans votre réseau local, à partir de localhost, ou à partir de votre LAN, cette directive devrait être fixé sur false. Si vous ne attendez pas à ces telles connexions, cette directive doit être fixé comme true.

##### « block_generic »
- Bloquer les CIDRs recommandé en généralement pour les listes noires ? Cela couvre toutes les signatures qui ne sont pas marqué comme étant partie de l'autre plus spécifique catégories de signatures.

##### « block_legal »
- Bloquer les CIDRs en réponse à des obligations légales ? Cette directive ne devrait normalement pas avoir d'effet, car CIDRAM n'associe aucun CIDR avec des « obligations légales » par défaut, mais il existe néanmoins comme une mesure de contrôle supplémentaire au profit de tous les fichiers de signatures personnalisées ou des modules qui pourraient exister pour des raisons juridiques.

##### « block_malware »
- Bloquer les adresses IP associées à des logiciels malveillants ? Cela inclut les serveurs C&C, les machines infectées, les machines impliquées dans la distribution de logiciels malveillants, etc.

##### « block_proxies »
- Bloquer les CIDRs identifié comme appartenant aux services proxy ou VPNs ? Si vous avez besoin que les utilisateurs puissent accéder à votre site web à partir des services de proxy ou des VPNs, cette directive devrait être fixé sur false. Autrement, si vous n'avez besoin pas des services de proxy ou des VPNs, cette directive devrait être fixé sur true comme moyen d'améliorer la sécurité.

##### « block_spam »
- Bloquer les CIDRs identifié comme étant risque élevé pour le spam ? Sauf si vous rencontrez des problèmes quand vous faire, en généralement, cette directive devrait toujours être fixé comme true.

##### « modules »
- Une liste des fichiers modules à charger après exécuter des signatures IPv4/IPv6, délimité par des virgules.

##### « default_tracktime »
- Combien de secondes pour suivre les IP interdites par les modules. Défaut = 604800 (1 semaine).

##### « infraction_limit »
- Nombre maximal d'infractions qu'une IP est autorisée à engager avant d'être interdite par la surveillance des IPs. Défaut = 10.

##### « track_mode »
- Quand faut-il compter les infractions ? False = Quand les adresses IP sont bloquées par des modules. True = Quand les adresses IP sont bloquées pour une raison quelconque.

#### « recaptcha » (Catégorie)
Si vous souhaitez, vous pouvez fournir aux utilisateurs un moyen de contourner la page de « Accès Refusé » par voie de complétant d'une instance reCAPTCHA. Cela peut aider à atténuer certains risques associés à des faux positifs dans les situations où nous ne sommes pas tout à fait sûr si une requête est à l'origine d'une machine ou d'un être humain.

En raison des risques associés à la fourniture d'une façon pour les utilisateurs pour contourner la page « Accès Refusé », généralement, je déconseille d'activer cette fonctionnalité à moins que vous sentez qu'il soit nécessaire de le faire. Situations dans lesquelles il serait nécessaire : Si vous avez des clients/utilisateurs pour votre site web qui ont besoin d'avoir accès, et si cela est quelque chose qui ne peut être pas compromise, mais si elles se connectant à partir d'un réseau hostile qui pourrait potentiellement être aussi transporter le trafic indésirable, et le blocage de ce trafic indésirable est aussi quelque chose qui ne peut pas être compromise, dans ces situations sans victoire, la fonctionnalité reCAPTCHA pourrait être utile un moyen de permettre aux clients/utilisateurs désirables, et en même temps tenir à l'écart le trafic indésirable à partir du même réseau. Cela dit, étant donné que l'objectif d'un CAPTCHA est pour la distinction entre les humains et les nonhumains, la fonctionnalité reCAPTCHA serait seulement aider dans ces situations sans victoire si nous voulons supposer que ce trafic indésirable est nonhumain (par exemple, spambots, scrapers, outils de piratage, trafic automatisé), plutôt d'être le trafic indésirable des humains (tels que les spammeurs humains, hackers, et al).

Pour obtenir une « site key » et une « secret key » (nécessaires à l'utilisation de reCAPTCHA), s'il vous plaît allez à : [https://developers.google.com/recaptcha/](https://developers.google.com/recaptcha/)

##### « usemode »
- Définit comment CIDRAM doit utiliser reCAPTCHA.
- 0 = reCAPTCHA est complètement désactivé (défaut).
- 1 = reCAPTCHA est activé pour toutes les signatures.
- 2 = reCAPTCHA est activé seulement pour les signatures appartenant à des sections spécialement marquées dans les fichiers de signatures.
- (Toute autre valeur sera traitée de la même manière que 0).

##### « lockip »
- Indique si hachages devrait être verrouillé à des IPs spécifiques. False = Cookies et hachages PEUVENT être utilisés sur plusieurs IPs (défaut). True = Cookies et hachages NE PEUVENT PAS être utilisés sur plusieurs IPs (cookies/hachages sont verrouillés à IPs).
- Note : La valeur de « lockip » est ignoré lorsque « lockuser » est false, en raison de ce que le mécanisme pour se souvenir de « utilisateurs » varie en fonction de cette valeur.

##### « lockuser »
- Indique si le succès d'une instance de reCAPTCHA devrait être verrouillé à des utilisateurs spécifiques. False = Le succès d'une instance de reCAPTCHA donnera accès à toutes les requêtes provenant de la même adresse IP que celui utilisé par l'utilisateur de remplir l'instance du reCAPTCHA ; Cookies et hachages ne sont pas utilisés ; Au lieu, une liste blanche IP sera utilisé. True = Le succès d'une instance de reCAPTCHA donnera accès seulement à l'utilisateur remplissant l'instance du reCAPTCHA ; Cookies et hachages sont utilisés pour mémoriser l'utilisateur ; Une liste blanche IP n'est pas utilisé (défaut).

##### « sitekey »
- Cette valeur devrait correspondre à la « site key » pour votre reCAPTCHA, qui se trouve dans le tableau de bord reCAPTCHA.

##### « secret »
- Cette valeur devrait correspondre à la « secret key » pour votre reCAPTCHA, qui se trouve dans le tableau de bord reCAPTCHA.

##### « expiry »
- Quand « lockuser » est true (défaut), afin de se souvenir quand un utilisateur a passé avec succès une instance de reCAPTCHA, pour les futures requêtes de page, CIDRAM génère un cookie HTTP standard contenant un hachage qui correspond à un enregistrement interne contenant ce même hachage ; Les futures requêtes de page utilisera ces hachage correspondant pour authentifier qu'un utilisateur a préalablement déjà passé une instance de reCAPTCHA. Quand « lockuser » est false, une liste blanche IP est utilisé pour déterminer si les requêtes devraient être autorisée à partir de l'adresse IP de requêtes entrantes ; Les entrées sont ajoutées à cette liste blanche lorsque l'instance de reCAPTCHA est passé avec succès. Pour combien d'heures devrait ces cookies, hachages et les entrées du liste blanche rester valables ? Défaut = 720 (1 mois).

##### « logfile »
- Enregistrez toutes les tentatives du reCAPTCHA ? Si oui, indiquez le nom à utiliser pour le fichier d'enregistrement. Si non, laisser vide ce variable.

*Conseil utile : Si vous souhaitez, vous pouvez ajouter l'information pour la date/l'heure à les noms de vos fichiers pour enregistrement par des incluant ceux-ci au nom : `{yyyy}` pour l'année complète, `{yy}` pour l'année abrégée, `{mm}` pour mois, `{dd}` pour le jour, `{hh}` pour l'heure.*

*Exemples :*
- *`logfile='recaptcha.{yyyy}-{mm}-{dd}-{hh}.txt'`*

##### « signature_limit »
- Nombre maximum de signatures autorisées à être déclenchées lorsqu'une instance de reCAPTCHA doit être présentée. Défaut = 1. Si ce nombre est dépassé pour une requête particulière, une instance de reCAPTCHA ne sera pas présentée.

##### « api »
- Quelle API utiliser ? V2 ou Invisible ?

*Note pour les utilisateurs de l'Union européenne : Lorsque CIDRAM est configuré pour utiliser des cookies (par exemple, lorsque « lockuser » est true/vrai), un avertissement de cookie est affiché en évidence sur la page conformément aux exigences de la [législation européenne sur les cookies](http://ec.europa.eu/ipg/basics/legal/cookies/index_en.htm). Cependant, lorsque vous utilisez l'API invisible, CIDRAM tente de compléter automatiquement le reCAPTCHA pour l'utilisateur, et en cas de succès, cela pourrait entraîner le rechargement de la page et la création d'un cookie sans que l'utilisateur ait suffisamment de temps pour voir l'avertissement de cookie. Si cela pose un risque juridique pour vous, il peut être préférable d'utiliser l'API V2 au lieu de l'API invisible (l'API V2 n'est pas automatisée et nécessite que l'utilisateur complete le défi reCAPTCHA eux-mêmes, fournissant ainsi une occasion de voir l'avertissement de cookie).*

#### « legal » (Catégorie)
Configuration relative aux exigences légales.

*Pour plus d'informations sur les exigences légales et comment cela peut affecter vos exigences de configuration, veuillez vous référer à la section « [INFORMATION LÉGALE](#SECTION11) » de la documentation.*

##### « pseudonymise_ip_addresses »
- Pseudonymiser les adresses IP lors de la journalisation ? True = Oui ; False = Non [Défaut].

##### « omit_ip »
- Omettre les adresses IP de la journalisation ? True = Oui ; False = Non [Défaut]. Remarque : « pseudonymise_ip_addresses » devient redondant lorsque « omit_ip » est « true ».

##### « omit_hostname »
- Omettre les noms d'hôtes de la journalisation ? True = Oui ; False = Non [Défaut].

##### « omit_ua »
- Omettre les agents utilisateurs de la journalisation ? True = Oui ; False = Non [Défaut].

##### « privacy_policy »
- L'adresse d'une politique de confidentialité pertinente à afficher dans le pied de page des pages générées. Spécifier une URL, ou laisser vide à désactiver.

#### « template_data » (Catégorie)
Directives/Variables pour les modèles et thèmes.

Correspond à la sortie HTML utilisé pour générer la page « Accès Refusé ». Si vous utilisez des thèmes personnalisés pour CIDRAM, sortie HTML provient du `template_custom.html` fichier, et sinon, sortie HTML provient du `template.html` fichier. Variables écrites à cette section du fichier de configuration sont préparé pour la sortie HTML par voie de remplacer tous les noms de variables circonfixé par accolades trouvés dans la sortie HTML avec les variables données correspondant. Par exemple, où `foo="bar"`, toute instance de `<p>{foo}</p>` trouvés dans la sortie HTML deviendra `<p>bar</p>`.

##### « theme »
- Le thème à utiliser par défaut pour CIDRAM.

##### « Magnification »
- Grossissement des fontes. Défaut = 1.

##### « css_url »
- Le modèle fichier pour des thèmes personnalisés utilise les propriétés CSS externes, tandis que le modèle fichier pour le défaut thème utilise les propriétés CSS internes. Pour instruire CIDRAM d'utiliser le modèle fichier pour des thèmes personnalisés, spécifier l'adresse HTTP public de votre thèmes personnalisés CSS fichiers utilisant le `css_url` variable. Si vous laissez cette variable vide, CIDRAM va utiliser le modèle fichier pour le défaut thème.

#### "PHPMailer" (Category)
PHPMailer configuration.

##### "EventLog"
- @todo@

##### "SkipAuthProcess"
- @todo@

##### "Enable2FA"
- @todo@

##### "Host"
- @todo@

##### "Port"
- @todo@

##### "SMTPSecure"
- @todo@

##### "SMTPAuth"
- @todo@

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


### 7. <a name="SECTION7"></a>FORMATS DE SIGNATURES

*Voir également :*
- *[Qu'est-ce qu'une « signature » ?](#WHAT_IS_A_SIGNATURE)*

#### 7.0 BASES (POUR LES FICHIERS DE SIGNATURE)

Une description du format et de la structure du signatures utilisé par CIDRAM peut être trouvée documentée en plain-text dans les deux fichiers de signatures personnalisées. S'il vous plaît référez à cette documentation pour apprendre plus sur le format et la structure du signatures de CIDRAM.

Toutes les signatures IPv4 suivre le format : `xxx.xxx.xxx.xxx/yy [Function] [Param]`.
- `xxx.xxx.xxx.xxx` représente le début du bloc (les octets de l'adresse IP initiale dans le bloc).
- `yy` représente la taille du bloc [1-32].
- `[Function]` instruit le script ce qu'il faut faire avec la signature (la façon dont la signature doit être considérée).
- `[Param]` représente les informations complémentaires qui peuvent être exigés par `[Function]`.

Toutes les signatures IPv6 follow the format : `xxxx:xxxx:xxxx:xxxx::xxxx/yy [Function] [Param]`.
- `xxxx:xxxx:xxxx:xxxx::xxxx` représente le début du bloc (les octets de l'adresse IP initiale dans le bloc). Notation complète et notation abrégée sont à la fois acceptable (et chacun DOIT suivre les normes appropriées et pertinentes de la notation d'IPv6, mais avec une exception : une adresse IPv6 ne peut jamais commencer par une abréviation quand il est utilisé dans une signature pour ce script, en raison de la façon dont les CIDRs sont reconstruits par le script ; Par exemple, `::1/128` doit être exprimée, quand il est utilisé dans une signature, comme `0::1/128`, et `::0/128` exprimée comme `0::/128`).
- `yy` représente la taille du bloc [1-128].
- `[Function]` instruit le script ce qu'il faut faire avec la signature (la façon dont la signature doit être considérée).
- `[Param]` représente les informations complémentaires qui peuvent être exigés par `[Function]`.

Les fichiers de signatures pour CIDRAM DEVRAIT utiliser les sauts de ligne de type Unix (`%0A`, or `\n`) ! D'autres types/styles de sauts de ligne (par exemple, Windows `%0D%0A` ou `\r\n` sauts de ligne, Mac `%0D` ou `\r` sauts de ligne, etc) PEUT être utilisé, mais ne sont PAS préférés. Ceux sauts de ligne qui ne sont pas du type Unix sera normalisé à sauts de ligne de type Unix par le script.

Notation précise et correcte pour CIDRs est exigée, autrement le script ne sera PAS reconnaître les signatures. En outre, toutes les signatures CIDR de ce script DOIT commencer avec une adresse IP dont le numéro IP peut diviser uniformément dans la division du bloc représenté par la taille du bloc (par exemple, si vous voulez bloquer toutes les adresses IP a partir de `10.128.0.0` jusqu'à `11.127.255.255`, `10.128.0.0/8` ne serait PAS reconnu par le script, mais `10.128.0.0/9` et `11.0.0.0/9` utilisé en conjonction, SERAIT reconnu par le script).

Tout dans les fichiers de signatures non reconnu comme une signature ni comme liées à la syntaxe par le script seront IGNORÉS, donc ce qui signifie que vous pouvez mettre toutes les données non-signature que vous voulez dans les fichiers de signatures sans risque, sans les casser et sans casser le script. Les commentaires sont acceptables dans les fichiers de signatures, et aucun formatage spécial est nécessaire pour eux. Hachage dans le style de Shell pour les commentaires est préféré, mais pas forcée ; Fonctionnellement, il ne fait aucune différence pour le script si vous choisissez d'utiliser hachage dans le style de Shell pour les commentaires, mais d'utilisation du hachage dans le style de Shell est utile pour IDEs et éditeurs de texte brut de mettre en surligner correctement les différentes parties des fichiers de signatures (et donc, hachage dans le style de Shell peut aider comme une aide visuelle lors de l'édition).

Les valeurs possibles de `[Function]` sont les suivants :
- Run
- Whitelist
- Greylist
- Deny

Si « Run » est utilisé, quand la signature est déclenchée, le script tentera d'exécuter (utilisant un statement `require_once`) un script PHP externe, spécifié par la valeur de `[Param]` (le répertoire de travail devrait être le répertoire « /vault/ » du script).

Exemple : `127.0.0.0/8 Run example.php`

Cela peut être utile si vous voulez exécuter du code PHP spécifique pour certaines adresses IP et/ou CIDRs spécifiques.

Si « Whitelist » est utilisé, quand la signature est déclenchée, le script réinitialise toutes les détections (s'il y a eu des détections) et de briser la fonction du test. `[Param]` est ignorée. Cette fonction est l'équivalent de mettre une adresse IP ou CIDR particulière sur un whitelist pour empêcher la détection.

Exemple : `127.0.0.1/32 Whitelist`

Si « Greylist » est utilisé, quand la signature est déclenchée, le script réinitialise toutes les détections (s'il y a eu des détections) et passer au fichier de signatures suivant pour continuer le traitement. `[Param]` est ignorée.

Exemple : `127.0.0.1/32 Greylist`

Si « Deny » est utilisé, quand la signature est déclenchée, en supposant qu'aucune signature whitelist a été déclenchée pour l'adresse IP donnée et/ou CIDR donnée, accès à la page protégée sera refusée. « Deny » est ce que vous aurez envie d'utiliser d'effectivement bloquer une adresse IP et/ou CIDR. Quand quelconque les signatures sont déclenchées que faire usage de « Deny », la page « Access Denied » du script seront générés et la requête à la page protégée tué.

La valeur de `[Param]` accepté par « Deny » seront traitées au la sortie de la page « Accès Refusé », fourni au client/utilisateur comme la raison invoquée pour leur accès à la page requêtée étant refusée. Il peut être une phrase courte et simple, expliquant pourquoi vous avez choisi de les bloquer (quoi que ce soit devrait suffire, même une simple « Je ne veux tu pas sur mon site »), ou l'un d'une petite poignée de mots courts fourni par le script, que si elle est utilisée, sera remplacé par le script avec une explication pré-préparés des raisons pour lesquelles le client/utilisateur a été bloqué.

Les explications pré-préparés avoir le support de L10N et peut être traduit par le script sur la base de la langue que vous spécifiez à la directive `lang` de la configuration du script. En outre, vous pouvez demander le script d'ignorer signatures de « Deny » sur la base de leur valeur de `[Param]` (s'ils utilisent ces mots courts) par les directives indiquées par la configuration du script (chaque mot court a une directive correspondante à traiter les signatures correspondant ou d'ignorer les). Les valeurs de `[Param]` qui ne pas utiliser ces mots courts, toutefois, n'avoir pas le support de L10N et donc ne seront PAS traduits par le script, et en outre, ne sont pas directement contrôlables par la configuration du script.

Les mots courts disponibles sont :
- Bogon
- Cloud
- Generic
- Proxy
- Spam
- Legal
- Malware

#### 7.1 ÉTIQUETTES

##### 7.1.0 ÉTIQUETTE DE SECTION

Si vous voulez partager vos signatures personnalisées en sections individuelles, vous pouvez identifier ces sections individuelles au script par ajoutant une « étiquette de section » immédiatement après les signatures de chaque section, inclus avec le nom de votre section de signatures (voir l'exemple ci-dessous).

```
# Section 1.
1.2.3.4/32 Deny Bogon
2.3.4.5/32 Deny Cloud
4.5.6.7/32 Deny Generic
5.6.7.8/32 Deny Spam
6.7.8.9/32 Deny Proxy
Tag: Section 1
```

Pour briser les étiquettes de section et assurer que les étiquettes ne sont pas identifié incorrectement pour les sections de signatures à partir de plus tôt dans les fichiers, assurez-vous simplement qu'il ya au moins deux sauts de ligne consécutifs entre votre étiquette et vos sections précédent. Toutes les signatures non balisé sera par défaut soit « IPv4 » ou « IPv6 » (en fonction de quels types de signatures sont déclenchés).

```
1.2.3.4/32 Deny Bogon
2.3.4.5/32 Deny Cloud

4.5.6.7/32 Deny Generic
5.6.7.8/32 Deny Spam
Tag: Section 1
```

Dans l'exemple ci-dessus `1.2.3.4/32` et `2.3.4.5/32` seront balisés comme « IPv4 », tandis que `4.5.6.7/32` et `5.6.7.8/32` seront balisés comme « Section 1 ».

La même logique peut également être appliquée pour séparer d'autres types d'étiquettes.

En particulier, les étiquettes de section peuvent être très utiles pour le débogage lorsque des faux positifs se produisent, en fournissant un moyen facile de trouver la source exacte du problème, et peut être très utiles pour filtrer les entrées du les fichiers journaux lors de l'affichage des fichiers journaux via la page des journaux frontaux (les noms de section sont cliquables via la page des journaux frontaux et peuvent être utilisés comme critères de filtrage). Si les étiquettes de section sont omises pour certaines signatures particulières, lorsque ces signatures sont déclenchées, CIDRAM utilise le nom du fichier de signature avec le type d'adresse IP bloqué (IPv4 ou IPv6) comme solution de repli, et donc, les étiquettes de section sont entièrement facultatives. Cependant, ils peuvent être recommandés dans certains cas, par exemple lorsque les fichiers de signature sont nommés de façon vague ou lorsqu'il peut être difficile d'identifier clairement la source des signatures provoquant le blocage d'une requête.

##### 7.1.1 ÉTIQUETTES D'EXPIRATION

Si vous voulez des signatures expirent après un certain temps, d'une manière similaire aux les étiquettes de section, vous pouvez utiliser une « étiquette d'expiration » à spécifier quand les signatures doivent cesser d'être valide. Les étiquettes d'expiration utilisent le format « AAAA.MM.JJ » (voir l'exemple ci-dessous).

```
# Section 1.
1.2.3.4/32 Deny Generic
2.3.4.5/32 Deny Generic
Expires: 2016.12.31
```

Les signatures expirées ne seront jamais déclenchées sur n'importe quelle requête, quoi qu'il arrive.

##### 7.1.2 ÉTIQUETTES D'ORIGINE

Si vous souhaitez spécifier le pays d'origine pour une signature particulière, vous pouvez le faire en utilisant une « étiquette d'origine ». Une étiquette d'origine accepte un code « [ISO 3166-1 Alpha-2](https://fr.wikipedia.org/wiki/ISO_3166-1) » correspondant au pays d'origine pour les signatures auxquelles elle s'applique. Ces codes doivent être écrits en majuscules (les minuscules ne seront pas rendus correctement). Lorsqu'une étiquette d'origine est utilisée, elle est ajoutée à le champ de l'entrée du fichier journal « Raison Bloquée » pour toutes les requêtes bloquées suite aux signatures auxquelles l'étiquette est appliquée.

Si le composant facultatif « flags CSS » est installé, lors de l'affichage des fichiers journaux via la page des journaux frontaux, les informations ajoutées par les étiquettes d'origine sont remplacées par le drapeau du pays correspondant à ces informations. Cette information, sous sa forme brute ou sous forme de drapeau de pays, est cliquable et, lorsqu'elle est cliquée, filtre les entrées du journal à l'aide d'autres entrées de journal d'identification similaire (permettant effectivement à ceux qui accèdent à la page des journaux de filtrer par le biais du pays d'origine).

Remarque : Techniquement, il ne s'agit pas d'une forme de géolocalisation, car cela ne nécessite pas de rechercher des informations spécifiques relatives aux adresses IP entrantes, mais plutôt, nous permet simplement d'indiquer explicitement un pays d'origine pour toutes les requêtes bloquées par des signatures spécifiques. Les étiquettes d'origine multiples sont permises dans la même section de signature.

Exemple hypothétique :

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

Toutes les étiquettes peuvent être utilisées conjointement et toutes les étiquettes sont facultatives (voir l'exemple ci-dessous).

```
# Section Exemple.
1.2.3.4/32 Deny Generic
Origin: US
Tag: Section Exemple
Expires: 2016.12.31
```

##### 7.1.3 ÉTIQUETTES DE DÉFÉRENCE

Lorsque de nombreux fichiers de signatures sont installés et utilisés activement, les installations peuvent devenir très complexes et certaines signatures peuvent se chevaucher. Dans ces cas, afin d'éviter que plusieurs signatures qui se chevauchent soient déclenchées pendant des événements de blocage, des étiquettes de déférence peuvent être utilisées pour différer des sections de signature spécifiques dans les cas où un autre fichier de signature spécifique est installé et utilisé activement. Cela peut être utile dans les cas où certaines signatures sont mises à jour plus fréquemment que d'autres, afin de différer les signatures moins fréquemment mises à jour en faveur des signatures les plus fréquemment mises à jour.

Les étiquettes de déférence sont utilisées de la même manière que les autres types d'étiquettes. La valeur de l'étiquette doit correspondre à un fichier de signature installé et utilisé activement pour y être différé.

Exemple :

```
1.2.3.4/32 Deny Generic
Origin: AA
2.3.4.5/32 Deny Generic
Origin: BB
Defers to: preferred_signatures.dat
```

#### 7.2 YAML

##### 7.2.0 BASES DE YAML

Une forme simplifiée de YAML peut être utilisé dans les fichiers de signature dans le but de définir des comportements et des paramètres spécifiques aux différentes sections de signatures. Cela peut être utile si vous voulez que la valeur de vos directives de configuration différer sur la base des signatures individuelles et des sections de signature (par exemple : si vous voulez fournir une adresse e-mail pour les tickets de support pour tous les utilisateurs bloqués par une signature particulière, mais ne veulent pas fournir une adresse e-mail pour les tickets de support pour les utilisateurs bloqués par d'autres signatures ; si vous voulez des signatures spécifiques pour déclencher une redirection de page ; si vous voulez marquer une section de signature pour l'utilisation avec reCAPTCHA ; si vous voulez enregistrer les tentatives d'accès bloquées à des fichiers séparés sur la base des signatures individuelles et/ou des sections de signatures).

L'utilisation de YAML dans les fichiers de signature est entièrement facultative (c'est à dire, vous pouvez l'utiliser si vous le souhaitez, mais vous n'êtes pas obligé de le faire), et est capable d'affecter la plupart (mais pas tout) les directives de configuration.

Note : L'implémentation de YAML dans CIDRAM est très simpliste et très limitée ; L'intention est de satisfaire aux exigences spécifiques à CIDRAM d'une manière qui a la familiarité de YAML, mais ne suit pas et ne sont pas conformes aux spécifications officielles (et ne sera donc pas se comporter de la même manière que des implémentations plus approfondies ailleurs, et peuvent ne pas convenir à d'autres projets ailleurs).

Dans CIDRAM, segments YAML sont identifiés au script par trois tirets (« --- »), et terminer aux côtés de leur contenant sections de signature par sauts de ligne double. Un segment YAML typique dans une section de signatures se compose de trois tirets sur une ligne immédiatement après la liste des CIDRs et des étiquettes, suivi d'une liste de bidimensionnelle paires clé-valeur (première dimension, catégories de directives de configuration ; deuxième dimension, directives de configuration) pour les directives de configuration que doivent être modifiés (et pour quelles valeurs) chaque fois qu'une signature dans cette section de signatures est déclenchée (voir les exemples ci-dessous).

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

##### 7.2.1 COMMENT « SPÉCIALEMENT MARQUER » LES SECTIONS DE SIGNATURE POUR L'UTILISATION AVEC reCAPTCHA

Quand « usemode » est 0 ou 1, les sections de signature ne doivent pas être « spécialement marqué » pour l'utilisation avec reCAPTCHA (parce qu'ils déjà seront ou non utiliser reCAPTCHA, en fonction de ce paramètre).

Quand « usemode » est 2, à « spécialement marquer » les sections de signature pour l'utilisation avec reCAPTCHA, une entrée est incluse dans le segment de YAML pour cette section de signatures (voir l'exemple ci-dessous).

```
# Cette section utilisera reCAPTCHA.
1.2.3.4/32 Deny Generic
2.3.4.5/32 Deny Generic
Tag: reCAPTCHA-Enabled
---
recaptcha:
 enabled: true
```

*Note : Par défaut, une instance de reCAPTCHA sera SEULEMENT présenté à l'utilisateur si reCAPTCHA est activé (soit avec « usemode » comme 1, ou « usemode » comme 2 avec « enabled » comme true), et si exactement UNE signature a été déclenchée (ni plus ni moins ; si plusieurs signatures sont déclenchées, une instance de reCAPTCHA NE SERA PAS présenté). Toutefois, ce comportement peut être modifié via la directive « signature_limit ».*

#### 7.3 AUXILIAIRE

En addition, si vous voulez CIDRAM à ignorer complètement certaines sections spécifiques dans aucun des fichiers de signatures, vous pouvez utiliser le fichier `ignore.dat` pour spécifier les sections à ignorer. Sur une nouvelle ligne, écrire `Ignore`, suivi d'un espace, suivi du nom de la section que vous souhaitez CIDRAM à ignorer (voir l'exemple ci-dessous).

```
Ignore Section 1
```

#### 7.4 <a name="MODULE_BASICS"></a>BASES (POUR LES MODULES)

Les modules peuvent être utilisés pour étendre les fonctionnalités de CIDRAM, effectuer des tâches supplémentaires ou traiter les logiques supplémentaires. Typiquement, ils sont utilisés lorsqu'il est nécessaire de bloquer une requête sur une base autre que son adresse IP d'origine (et donc, quand une signature CIDR ne suffira pas à bloquer la requête). Les modules sont écrits en tant que fichiers PHP, et donc, typiquement, les signatures de module sont écrites en tant que code PHP.

Quelques bons exemples de modules pour CIDRAM peuvent être trouvés ici :
- https://github.com/CIDRAM/CIDRAM-Extras/tree/master/modules

Un modèle pour écrire de nouveaux modules peut être trouvé ici :
- https://github.com/CIDRAM/CIDRAM-Extras/blob/master/modules/module_template.php

En raison de ce que les modules sont écrits en tant que fichiers PHP, si vous connaissez bien la base de code pour CIDRAM, vous pouvez structurer vos modules comme vous le souhaitez, et écrivez vos signatures de module comme vous le souhaitez (en raison de ce qui est possible avec PHP). Cependant, pour votre propre commodité, et dans l'intérêt d'une meilleure intelligibilité mutuelle entre les modules existants et les vôtres, l'analyse du modèle lié ci-dessus est recommandée, afin de pouvoir utiliser la structure et le format qu'il fournit.

*Remarque : Si vous n'êtes pas à l'aise de travailler avec du code PHP, il n'est pas recommandé d'écrire vos propres modules.*

Certaines fonctionnalités sont fournies par CIDRAM pour les modules à utiliser, ce qui devrait simplifier et faciliter l'écriture de vos propres modules. Des informations sur cette fonctionnalité sont décrites ci-dessous.

#### 7.5 MODULE FONCTIONNALITÉ

##### 7.5.0 « $Trigger »

Les signatures de module sont typiquement écrites avec « $Trigger ». Dans la plupart des cas, cette closure sera plus importante que toute autre chose dans le but d'écrire des modules.

« $Trigger » accepte 4 paramètres : « $Condition », « $ReasonShort », « $ReasonLong » (optionnel), et « $DefineOptions » (optionnel).

La véracité de « $Condition » est évaluée, et si elle est true/vraie, la signature est « déclenchée ». Si false/faux, la signature *n'est pas* « déclenchée ». « $Condition » contient typiquement du code PHP pour évaluer une condition qui devrait entraîner le blocage d'une requête.

« $ReasonShort » est cité dans le champ « Raison Bloquée » lorsque la signature est « déclenchée ».

« $ReasonLong » est un message optionnel à afficher à l'utilisateur/client pour quand ils sont bloqués, pour expliquer pourquoi ils ont été bloqués. Utilise le message standard « Accès Refusé » lorsqu'il est omis.

« $DefineOptions » est un tableau optionnel contenant des paires clé/valeur, utilisé pour définir les options de configuration spécifiques à l'instance de requête. Les options de configuration seront appliquées lorsque la signature est « déclenchée ».

« $Trigger » retourne true/vrai lorsque la signature est « déclenchée », et false/faux quand ce n'est pas le cas.

Pour utiliser cette closure dans votre module, souvenez-vous tout d'abord de l'hériter de la portée parent :
```PHP
$Trigger = $CIDRAM['Trigger'];
```

##### 7.5.1 « $Bypass »

Les contournements pour les signatures sont typiquement écrits avec « $Bypass ».

« $Bypass » accepte 3 paramètres : « $Condition », « $ReasonShort », et « $DefineOptions » (optionnel).

La véracité de « $Condition » est évaluée, et si elle est true/vraie, la signature est « déclenchée ». Si false/faux, la signature *n'est pas* « déclenchée ». « $Condition » contient typiquement du code PHP pour évaluer une condition qui ne devrait *pas* entraîner le blocage d'une requête.

« $ReasonShort » est cité dans le champ « Raison Bloquée » lorsque le contournement est « déclenchée ».

« $DefineOptions » est un tableau optionnel contenant des paires clé/valeur, utilisé pour définir les options de configuration spécifiques à l'instance de requête. Les options de configuration seront appliquées lorsque la signature est « déclenchée ».

« $Bypass » retourne true/vrai lorsque le contournement est « déclenchée », et false/faux quand ce n'est pas le cas.

Pour utiliser cette closure dans votre module, souvenez-vous tout d'abord de l'hériter de la portée parent :
```PHP
$Bypass = $CIDRAM['Bypass'];
```

##### 7.5.2 « $CIDRAM['DNS-Reverse'] »

Cela peut être utilisé pour récupérer le nom de l'hôte d'une adresse IP. Si vous voulez créer un module pour bloquer les noms d'hôtes, cette closure pourrait être utile.

Exemple :
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

#### 7.6 MODULES VARIABLES

Les modules s'exécutent dans leur propre portée, et toutes les variables définies par un module ne seront pas accessibles aux autres modules, ni au script parent, sauf s'ils sont stockés dans le tableau « $CIDRAM » (tout le reste est vidé après la fin de l'exécution du module).

Voici quelques variables communes qui pourraient être utiles pour votre module :

Variable | Description
----|----
`$CIDRAM['BlockInfo']['DateTime']` | La date et l'heure actuelles.
`$CIDRAM['BlockInfo']['IPAddr']` | L'adresse IP pour la requête actuelle.
`$CIDRAM['BlockInfo']['ScriptIdent']` | Version de CIDRAM.
`$CIDRAM['BlockInfo']['Query']` | La « query » pour la requête actuelle.
`$CIDRAM['BlockInfo']['Referrer']` | Le référent pour la requête actuelle (s'il existe).
`$CIDRAM['BlockInfo']['UA']` | L'agent utilisateur (user agent) pour la requête actuelle.
`$CIDRAM['BlockInfo']['UALC']` | L'agent utilisateur (user agent) pour la requête actuelle (en minuscules).
`$CIDRAM['BlockInfo']['ReasonMessage']` | Le message à afficher à l'utilisateur/client pour la requête actuelle s'ils sont bloqués.
`$CIDRAM['BlockInfo']['SignatureCount']` | Le nombre de signatures déclenchées pour la requête actuelle.
`$CIDRAM['BlockInfo']['Signatures']` | Informations de référence pour toutes les signatures déclenchées pour la requête actuelle.
`$CIDRAM['BlockInfo']['WhyReason']` | Informations de référence pour toutes les signatures déclenchées pour la requête actuelle.

---


### 8. <a name="SECTION8"></a>PROBLÈMES DE COMPATIBILITÉ CONNUS

Les paquets et produits suivants ont été jugés incompatibles avec CIDRAM :
- __[Endurance Page Cache](https://github.com/CIDRAM/CIDRAM/issues/52)__
- __[Mix.com](https://github.com/CIDRAM/CIDRAM/issues/80)__

Des modules ont été mis à disposition pour garantir que les packages et produits suivants seront compatibles avec CIDRAM:
- __[BunnyCDN](https://github.com/CIDRAM/CIDRAM/issues/56)__

*Voir également : [Tableaux de Compatibilité](https://maikuolan.github.io/Compatibility-Charts/).*

---


### 9. <a name="SECTION9"></a>QUESTIONS FRÉQUEMMENT POSÉES (FAQ)

- [Qu'est-ce qu'une « signature » ?](#WHAT_IS_A_SIGNATURE)
- [Qu'est-ce qu'un « CIDR » ?](#WHAT_IS_A_CIDR)
- [Qu'est-ce qu'un « faux positif » ?](#WHAT_IS_A_FALSE_POSITIVE)
- [CIDRAM peut-il bloquer des pays entiers ?](#BLOCK_ENTIRE_COUNTRIES)
- [À quelle fréquence les signatures sont-elles mises à jour ?](#SIGNATURE_UPDATE_FREQUENCY)
- [J'ai rencontré un problème lors de l'utilisation de CIDRAM et je ne sais pas quoi faire à ce sujet ! Aidez-moi !](#ENCOUNTERED_PROBLEM_WHAT_TO_DO)
- [J'ai été bloqué par CIDRAM d'un site Web que je veux visiter ! Aidez-moi !](#BLOCKED_WHAT_TO_DO)
- [Je veux utiliser CIDRAM avec une version PHP plus ancienne que 5.4.0 ; Pouvez-vous m'aider ?](#MINIMUM_PHP_VERSION)
- [Puis-je utiliser une seule installation de CIDRAM pour protéger plusieurs domaines ?](#PROTECT_MULTIPLE_DOMAINS)
- [Je ne veux pas déranger avec l'installation de cela et le faire fonctionner avec mon site ; Puis-je vous payer pour tout faire pour moi ?](#PAY_YOU_TO_DO_IT)
- [Puis-je vous embaucher ou à l'un des développeurs de ce projet pour un travail privé ?](#HIRE_FOR_PRIVATE_WORK)
- [J'ai besoin de modifications spécialisées, de personnalisations, etc ; Êtes-vous en mesure d'aider ?](#SPECIALIST_MODIFICATIONS)
- [Je suis un développeur, un concepteur de site Web ou un programmeur. Puis-je accepter ou offrir des travaux relatifs à ce projet ?](#ACCEPT_OR_OFFER_WORK)
- [Je veux contribuer au projet ; Puis-je faire cela ?](#WANT_TO_CONTRIBUTE)
- [Puis-je utiliser cron pour mettre à jour automatiquement ?](#CRON_TO_UPDATE_AUTOMATICALLY)
- [Quelles sont les « infractions » ?](#WHAT_ARE_INFRACTIONS)
- [Est-ce que CIDRAM peut bloquer les noms d'hôtes ?](#BLOCK_HOSTNAMES)
- [Que puis-je utiliser pour « default_dns » ?](#WHAT_CAN_I_USE_FOR_DEFAULT_DNS)
- [Puis-je utiliser CIDRAM pour protéger des éléments autres que des sites Web (par exemple, serveurs de messagerie, FTP, SSH, IRC, etc) ?](#PROTECT_OTHER_THINGS)
- [Des problèmes surviendront-ils si j'utilise CIDRAM en même temps que des CDN ou des services de cache ?](#CDN_CACHING_PROBLEMS)
- [Est-ce que CIDRAM va protéger mon site web contre les attaques DDoS ?](#DDOS_ATTACKS)
- [Lorsque j'activer ou désactiver des modules ou des fichiers de signatures via la page des mises à jour, il les trie de manière alphanumérique dans la configuration. Puis-je changer la façon dont ils sont triés ?](#CHANGE_COMPONENT_SORT_ORDER)

#### <a name="WHAT_IS_A_SIGNATURE"></a>Qu'est-ce qu'une « signature » ?

Dans le contexte du CIDRAM, une « signature » désigne les données qui servent d'indicateur ou d'identifiant pour quelque chose de spécifique que nous chercher, habituellement une adresse IP ou CIDR, et inclures des instructions pour CIDRAM, indiquant la meilleure façon de répondre quand il rencontre ce que nous chercher. Une signature typique pour CIDRAM ressemble à ceci :

Pour les « fichiers de signature » :

`1.2.3.4/32 Deny Generic`

Pour les « modules » :

```PHP
$Trigger(strpos($CIDRAM['BlockInfo']['UA'], 'Foobar') !== false, 'Foobar-UA', 'User agent "Foobar" not allowed.');
```

*Remarque : Les signatures pour les « fichiers de signature », et les signatures pour les « modules », ne sont pas la même chose.*

Souvent (mais pas toujours), les signatures seront regroupées en groupes, formant des « sections de signatures », souvent accompagné de commentaires, de balisage et/ou de métadonnées connexes qui peuvent être utilisées pour fournir un contexte supplémentaire pour les signatures et/ou d'autres instructions.

#### <a name="WHAT_IS_A_CIDR"></a>Qu'est-ce qu'un « CIDR » ?

« CIDR » est un acronyme pour « Classless Inter-Domain Routing » *[[1](https://en.wikipedia.org/wiki/Classless_Inter-Domain_Routing), [2](http://whatismyipaddress.com/cidr)]*, et c'est l'acronyme utilisé dans le nom de ce paquet, « CIDRAM », qui est un acronyme pour « Classless Inter-Domain Routing Access Manager ».

Toutefois, dans le contexte du CIDRAM (tel que, au sein de cette documentation, dans les discussions relatives au CIDRAM, ou dans les données linguistiques para CIDRAM), chaque fois qu'un « CIDR » (singulier) ou « CIDRs » (pluriel) est mentionné (et ainsi, par lequel nous utilisons ces mots comme noms dans leur propre droit, par opposition aux acronymes), ce que l'on veut dire signifie un sous-réseau (ou sous-réseaux), exprimé en utilisant la notation CIDR. La raison pour laquelle CIDR (ou CIDRs) est utilisé à la place du sous-réseau (ou sous-réseaux) est de préciser qu'il s'agit spécifiquement de sous-réseaux exprimés à l'aide de la notation CIDR à laquelle on se réfère (parce que la notation CIDR n'est qu'une des différentes façons dont les sous-réseaux peuvent être exprimés). CIDRAM pourrait donc être considéré comme un « gestionnaire d'accès au sous-réseaux ».

Bien que cette double signification de « CIDR » puisse présenter une certaine ambiguïté dans certains cas, cette explication, accompagné par le contexte fourni, devrait aider à résoudre une telle ambiguïté.

#### <a name="WHAT_IS_A_FALSE_POSITIVE"></a>Qu'est-ce qu'un « faux positif » ?

Le terme « faux positif » (*alternativement : « erreur faux positif » ; « fausse alarme »* ; Anglais : *false positive* ; *false positive error* ; *false alarm*), décrit très simplement, et dans un contexte généralisé, est utilisé lors de tester pour une condition, de se référer aux résultats de ce test, lorsque les résultats sont positifs (c'est à dire, lorsque la condition est déterminée comme étant « positif », ou « vrai »), mais ils devraient être (ou aurait dû être) négatif (c'est à dire, lorsque la condition, en réalité, est « négatif », ou « faux »). Un « faux positif » pourrait être considérée comme analogue à « crier au loup » (où la condition testée est de savoir s'il y a un loup près du troupeau, la condition est « faux » en ce que il n'y a pas de loup près du troupeau, et la condition est signalé comme « positif » par le berger par voie de crier « loup ! loup ! »), ou analogues à des situations dans des tests médicaux dans lequel un patient est diagnostiqué comme ayant une maladie, alors qu'en réalité, ils ont pas une telle maladie.

Résultats connexes lors de tester pour une condition peut être décrit en utilisant les termes « vrai positif », « vrai négatif » et « faux négatif ». Un « vrai positif » se réfère à quand les résultats du test et l'état actuel de la condition sont tous deux vrai (ou « positif »), and a « vrai négatif » se réfère à quand les résultats du test et l'état actuel de la condition sont tous deux faux (ou « négatif ») ; Un « vrai positif » ou « vrai négatif » est considéré comme une « inférence correcte ». L'antithèse d'un « faux positif » est un « faux négatif » ; Un « faux négatif » se réfère à quand les résultats du test are négatif (c'est à dire, la condition est déterminée comme étant « négatif », ou « faux »), mais ils devraient être (ou aurait dû être) positif (c'est à dire, la condition, en réalité, est « positif », ou « vrai »).

Dans le contexte de CIDRAM, ces termes réfèrent à les signatures de CIDRAM et que/qui ils bloquent. Quand CIDRAM bloque une adresse IP en raison du mauvais, obsolète ou signatures incorrectes, mais ne devrait pas l'avoir fait, ou quand il le fait pour les mauvaises raisons, nous référons à cet événement comme un « faux positif ». Quand CIDRAM ne parvient pas à bloquer une adresse IP qui aurait dû être bloqué, en raison de menaces imprévues, signatures manquantes ou déficits dans ses signatures, nous référons à cet événement comme un « détection manquée » ou « missed detection » (qui est analogue à un « faux négatif »).

Ceci peut être résumé par le tableau ci-dessous :

&nbsp; | CIDRAM ne devrait *PAS* bloquer une adresse IP | CIDRAM *DEVRAIT* bloquer une adresse IP
---|---|---
CIDRAM ne bloque *PAS* une adresse IP | Vrai négatif (inférence correcte) | Détection manquée (analogue à faux négatif)
CIDRAM bloque une adresse IP | __Faux positif__ | Vrai positif (inférence correcte)

#### <a name="BLOCK_ENTIRE_COUNTRIES"></a>CIDRAM peut-il bloquer des pays entiers ?

Oui. La meilleure façon d'y parvenir serait d'installer certaines des listes facultatives pour les pays bloquants fournies par Macmathan. Cela peut être fait avec quelques clics simples directement à partir de la page des mises à jour de l'accès frontal, ou, si vous préférez que l'accès frontal reste désactivé, en les téléchargeant directement depuis la **[page de téléchargement des listes facultatives pour les pays bloquants](https://macmathan.info/blocklists)**, en les téléchargeant dans le vault, et en citant leurs noms dans les directives de configuration appropriées.

#### <a name="SIGNATURE_UPDATE_FREQUENCY"></a>À quelle fréquence les signatures sont-elles mises à jour ?

La fréquence de mise à jour varie selon les fichiers de signature en question. Tous les mainteneurs des fichiers de signature pour CIDRAM tentent généralement de conserver leurs signatures aussi à jour que possible, mais comme nous avons tous divers autres engagements, nos vies en dehors du projet, et comme aucun de nous n'est rémunéré financièrement (ou payé) pour nos efforts sur le projet, un planning de mise à jour précis ne peut être garanti. Généralement, les signatures sont mises à jour chaque fois qu'il y a suffisamment de temps pour les mettre à jour, et généralement, les mainteneurs tentent de prioriser basé sur la nécessité et la fréquence à laquelle des changements se produisent entre les gammes. L'assistance est toujours appréciée si vous êtes prêt à en offrir.

#### <a name="ENCOUNTERED_PROBLEM_WHAT_TO_DO"></a>J'ai rencontré un problème lors de l'utilisation de CIDRAM et je ne sais pas quoi faire à ce sujet ! Aidez-moi !

- Utilisez-vous la dernière version du logiciel ? Utilisez-vous les dernières versions de vos fichiers de signature ? Si la réponse à l'une ou l'autre de ces deux est non, essayez de tout mettre à jour tout d'abord, et vérifier si le problème persiste. Si elle persiste, continuez à lire.
- Avez-vous vérifié toute la documentation ? Si non, veuillez le faire. Si le problème ne peut être résolu en utilisant la documentation, continuez à lire.
- Avez-vous vérifié la **[page des issues](https://github.com/CIDRAM/CIDRAM/issues)**, pour voir si le problème a été mentionné avant ? Si on l'a mentionné avant, vérifier si des suggestions, des idées et/ou des solutions ont été fournies, et suivez comme nécessaire pour essayer de résoudre le problème.
- Si le problème persiste, s'il vous plaît demander de l'aide à ce sujet en créant un nouveau issue sur la page des issues.

#### <a name="BLOCKED_WHAT_TO_DO"></a>J'ai été bloqué par CIDRAM d'un site Web que je veux visiter ! Aidez-moi !

CIDRAM fournit un moyen pour les propriétaires de sites Web de bloquer le trafic indésirable, mais c'est la responsabilité des propriétaires de sites Web de décider eux-mêmes comment ils veulent utiliser CIDRAM. Dans le cas des faux positifs relatifs aux fichiers de signature normalement inclus dans CIDRAM, des corrections peuvent être apportées, mais en ce qui concerne d'être débloqué à partir de sites Web spécifiques, vous devrez contacter les propriétaires des sites Web en question. Dans les cas où des corrections sont apportées, à tout le moins, ils devront mettre à jour leurs fichiers de signature et/ou d'installation, et dans d'autres cas (tels que, par exemple, où ils ont modifié leur installation, créé leurs propres signatures personnalisées, etc), la responsabilité de résoudre votre problème est entièrement à eux, et est entièrement hors de notre contrôle.

#### <a name="MINIMUM_PHP_VERSION"></a>Je veux utiliser CIDRAM avec une version PHP plus ancienne que 5.4.0 ; Pouvez-vous m'aider ?

Non. PHP 5.4.0 a atteint officiellement l'EoL (« End of Life », ou fin de vie) en 2014, et le support étendu en matière de sécurité a pris fin en 2015. À la date d'écriture, il est 2017, et PHP 7.1.0 est déjà disponible. À l'heure actuelle, le support est fourni pour l'utilisation de CIDRAM avec PHP 5.4.0 et toutes les nouvelles versions PHP disponibles, mais si vous essayez d'utiliser CIDRAM avec les anciennes versions PHP, le support ne sera pas fourni.

*Voir également : [Tableaux de Compatibilité](https://maikuolan.github.io/Compatibility-Charts/).*

#### <a name="PROTECT_MULTIPLE_DOMAINS"></a>Puis-je utiliser une seule installation de CIDRAM pour protéger plusieurs domaines ?

Oui. Les installations CIDRAM ne sont pas naturellement verrouillées dans des domaines spécifiques, et peut donc être utilisé pour protéger plusieurs domaines. Généralement, nous référons aux installations CIDRAM protégeant un seul domaine comme « installations à un seul domaine » (« single-domain installations »), et nous référons aux installations CIDRAM protégeant plusieurs domaines et/ou sous-domaines comme « installations multi-domaines » (« multi-domain installations »). Si vous utilisez une installation multi-domaine et besoin d'utiliser différents ensembles de fichiers de signature pour différents domaines, ou besoin de CIDRAM pour être configuré différemment pour différents domaines, il est possible de le faire. Après avoir chargé le fichier de configuration (`config.ini`), CIDRAM vérifiera l'existence d'un « fichier de substitution de configuration » spécifique au domaine (ou sous-domaine) requêté (`le-domaine-requêté.tld.config.ini`), et si trouvé, les valeurs de configuration définies par le fichier de substitution de configuration sera utilisé pour l'instance d'exécution au lieu des valeurs de configuration définies par le fichier de configuration. Les fichiers de substitution de configuration sont identiques au fichier de configuration, et à votre discrétion, peut contenir l'intégralité de toutes les directives de configuration disponibles pour CIDRAM, ou quelle que soit la petite sous-section requise qui diffère des valeurs normalement définies par le fichier de configuration. Les fichiers de substitution de configuration sont nommée selon le domaine auquel elle est destinée (donc, par exemple, si vous avez besoin d'une fichier de substitution de configuration pour le domaine, `http://www.some-domain.tld/`, sa fichier de substitution de configuration doit être nommé comme `some-domain.tld.config.ini`, et devrait être placé dans la vault à côté du fichier de configuration, `config.ini`). Le nom de domaine pour l'instance d'exécution dérive de l'en-tête `HTTP_HOST` de la requête ; « www » est ignoré.

#### <a name="PAY_YOU_TO_DO_IT"></a>Je ne veux pas déranger avec l'installation de cela et le faire fonctionner avec mon site ; Puis-je vous payer pour tout faire pour moi ?

Peut-être. Ceci est considéré au cas par cas. Faites-nous savoir ce dont vous avez besoin, ce que vous offrez, et nous vous informerons si nous pouvons vous aider.

#### <a name="HIRE_FOR_PRIVATE_WORK"></a>Puis-je vous embaucher ou à l'un des développeurs de ce projet pour un travail privé ?

*Voir au dessus.*

#### <a name="SPECIALIST_MODIFICATIONS"></a>J'ai besoin de modifications spécialisées, de personnalisations, etc ; Êtes-vous en mesure d'aider ?

*Voir au dessus.*

#### <a name="ACCEPT_OR_OFFER_WORK"></a>Je suis un développeur, un concepteur de site Web ou un programmeur. Puis-je accepter ou offrir des travaux relatifs à ce projet ?

Oui. Notre licence ne l'interdit pas.

#### <a name="WANT_TO_CONTRIBUTE"></a>Je veux contribuer au projet ; Puis-je faire cela ?

Oui. Les contributions au projet sont les bienvenues. Voir « CONTRIBUTING.md » pour plus d'informations.

#### <a name="CRON_TO_UPDATE_AUTOMATICALLY"></a>Puis-je utiliser cron pour mettre à jour automatiquement ?

Oui. Une API est intégrée dans le frontal pour interagir avec la page des mises à jour via des scripts externes. Un script séparé, « [Cronable](https://github.com/Maikuolan/Cronable) », est disponible, et peut être utilisé par votre gestionnaire de cron ou cron scheduler pour mettre à jour ce paquet et d'autres paquets supportés automatiquement (ce script fournit sa propre documentation).

#### <a name="WHAT_ARE_INFRACTIONS"></a>Quelles sont les « infractions » ?

« Infractions » détermine quand une adresse IP qui n'est pas bloquée par des fichiers de signatures spécifiques devrait être bloqué pour les requêtes futures, et ils sont étroitement associés au surveillance des adresses IP. Certaines fonctionnalités et certains modules permettent de bloquer les requêtes pour des raisons autres que l'adresse IP d'origine (tels que la présence d'agents utilisateurs [user agents] correspondant à spambots ou hacktools, requêtes dangereuses, DNS usurpé et ainsi de suite), et quand cela arrive, une « infraction » peut survenir. Ils fournissent un moyen d'identifier les adresses IP qui correspondent à des requêtes non désirées qui ne sont pas bloquées par des fichiers de signatures spécifiques. Les infractions correspondent généralement 1-à-1 avec le nombre de fois qu'une IP est bloquée, mais pas toujours (les événements de bloc graves peuvent entraîner une valeur d'infraction supérieure à un, et si « track_mode » est false, les infractions ne se produiront pas pour les événements de bloc déclenchés uniquement par des fichiers de signatures).

#### <a name="BLOCK_HOSTNAMES"></a>Est-ce que CIDRAM peut bloquer les noms d'hôtes ?

Oui. Pour ce faire, vous devez créer un fichier de module personnalisé. *Voir : [BASES (POUR LES MODULES)](#MODULE_BASICS)*.

#### <a name="WHAT_CAN_I_USE_FOR_DEFAULT_DNS"></a>Que puis-je utiliser pour « default_dns » ?

Généralement, l'adresse IP de tout serveur DNS fiable devrait suffire. Si vous recherchez des suggestions, [public-dns.info](https://public-dns.info/) et [OpenNIC](https://servers.opennic.org/) fournissent des listes détaillées de serveurs DNS publics connus. Alternativement, voir le tableau ci-dessous :

IP | Opérateur
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

*Remarque : Je ne fais aucune réclamation ou garantie concernant les pratiques de confidentialité, la sécurité, l'efficacité, ou la fiabilité de tous les services DNS, listés ou non. S'il vous plaît faites votre propre recherche lorsque vous prenez des décisions à leur sujet.*

#### <a name="PROTECT_OTHER_THINGS"></a>Puis-je utiliser CIDRAM pour protéger des éléments autres que des sites web (par exemple, serveurs de messagerie, FTP, SSH, IRC, etc) ?

Vous pouvez (légalement), mais ne devriez pas (techniquement et pratiquement). Notre licence ne limite pas les technologies implémentant de CIDRAM, mais CIDRAM est un WAF (Web Application Firewall) et a toujours été conçu pour protéger les sites web. Parce qu'il n'a pas été conçu pour d'autres technologies, il est improbable qu'il soit efficace ou qu'il offre une protection fiable pour d'autres technologies, la mise en œuvre est susceptible d'être difficile, et le risque de faux positifs et de détections manquées est très élevé.

#### <a name="CDN_CACHING_PROBLEMS"></a>Des problèmes surviendront-ils si j'utilise CIDRAM en même temps que des CDN ou des services de cache ?

Peut être. Cela dépend de la nature du service en question et de la façon dont vous l'utilisez. Généralement, si vous mettez en cache seulement des ressources statiques (images, CSS, etc ; tout ce qui ne change généralement pas au fil du temps), il ne devrait pas y avoir de problèmes. Mais, il peut y avoir des problèmes, si vous mettez en cache des données qui seraient normalement générées dynamiquement à la requête, ou si vous mettez en cache les résultats des requêtes POST (cela rendrait essentiellement votre site web et son environnement aussi obligatoirement statiques, et CIDRAM est peu susceptible de fournir un bénéfice significatif dans un environnement obligatoirement statique). Il peut également exister des exigences de configuration spécifiques pour CIDRAM, selon le service CDN ou le service de mise en cache que vous utilisez (vous devez vous assurer que CIDRAM est correctement configuré pour le service CDN ou le service de mise en cache spécifique que vous utilisez). Si vous ne configurez correctement pas CIDRAM, vous risquez d'obtenir des faux positifs significativement problématiques et des détections manquées.

#### <a name="DDOS_ATTACKS"></a>Est-ce que CIDRAM va protéger mon site web contre les attaques DDoS ?

Réponse courte : Non.

Réponse légèrement plus longue : CIDRAM vous aidera à réduire l'impact que le trafic indésirable peut avoir sur votre site Web (réduisant ainsi les coûts de bande passante de votre site Web), à réduire l'impact que le trafic indésirable peut avoir sur votre matériel (par exemple, la capacité de votre serveur à traiter et à servir des requêtes), et peut aider à réduire les autres effets négatifs potentiels associés au trafic indésirable. Cependant, il y a deux choses importantes dont il faut se souvenir pour comprendre cette question.

Premièrement, CIDRAM est un paquet PHP, et fonctionne donc sur la machine où PHP est installé. Cela signifie que CIDRAM peut seulement voir et bloquer une requête *après* que le serveur l'a déjà reçu. Deuxièmement, une [atténuation des attaques DDoS](https://fr.wikipedia.org/wiki/Mitigation_de_DDoS) efficace devrait filtrer les requêtes *avant* qu'elles n'atteignent le serveur ciblé par l'attaque DDoS. Idéalement, les attaques DDoS devraient être détectées et atténuées par des solutions capables de supprimer ou de réacheminer le trafic associé aux attaques, avant même qu'elles n'atteignent le serveur ciblé.

Cela peut être mis en œuvre en utilisant des solutions matérielles sur site dédiées, et/ou des solutions basées sur le cloud, telles que des services dédiés d'atténuation DDoS, acheminer le DNS d'un domaine via des réseaux résistants aux attaques DDoS, filtrage basé sur le cloud, ou une combinaison de ceux-ci. En tout cas, ce sujet est un peu trop complexe à expliquer en détail avec juste un paragraphe ou deux, donc je recommanderais de faire d'autres recherches si c'est un sujet que vous voulez poursuivre. Lorsque la véritable nature des attaques DDoS est bien comprise, cette réponse aura plus de sens.

#### <a name="CHANGE_COMPONENT_SORT_ORDER"></a>Lorsque j'activer ou désactiver des modules ou des fichiers de signatures via la page des mises à jour, il les trie de manière alphanumérique dans la configuration. Puis-je changer la façon dont ils sont triés ?

Oui. Si vous devez forcer l'exécution de certains fichiers dans un ordre spécifique, vous pouvez ajouter des données arbitraires avant leurs noms dans la directive de configuration où elles sont listées, séparées par un signe deux-points. Lorsque la page des mises à jour trie à nouveau les fichiers, ces données arbitraires ajoutées affectent l'ordre de tri, en leur faisant par conséquent exécuter dans l'ordre que vous voulez, sans avoir besoin de renommer l'un d'entre eux.

Par exemple, en supposant une directive de configuration avec des fichiers listés comme suit :

`file1.php,file2.php,file3.php,file4.php,file5.php`

Si vous voulez que `file3.php` s'exécute en premier, vous pouvez ajouter quelque chose comme `aaa:` avant le nom du fichier :

`file1.php,file2.php,aaa:file3.php,file4.php,file5.php`

Ensuite, si un nouveau fichier, `file6.php`, est activé, lorsque la page des mises à jour les trie à nouveau, elle devrait se terminer comme suit :

`aaa:file3.php,file1.php,file2.php,file4.php,file5.php,file6.php`

Inversement, si vous voulez que le fichier s'exécute en dernier, vous pouvez ajouter quelque chose comme `zzz:` avant le nom du fichier. Dans tous les cas, vous n'aurez pas besoin de renommer le fichier en question.

---


### 11. <a name="SECTION11"></a>INFORMATION LÉGALE

#### 11.0 PRÉAMBULE DE LA SECTION

Cette section de la documentation est destinée à décrire les considérations juridiques possibles concernant l'utilisation et la mise en œuvre du paquet, et de fournir quelques informations de base connexes. Cela peut être important pour certains utilisateurs afin de garantir le respect des exigences légales qui peuvent exister dans les pays où ils opèrent, et certains utilisateurs peuvent avoir besoin d'ajuster leurs politiques de site Web conformément à cette information.

Tout d'abord, s'il vous plaît se rendre compte que je (l'auteur du paquet) ne suis pas un avocat, ni un professionnel juridique qualifié de toute nature. Par conséquent, je ne suis pas légalement qualifié pour fournir des conseils juridiques. Aussi, dans certains cas, les exigences légales peuvent varier selon les pays et les juridictions, et ces différentes exigences juridiques peuvent parfois entrer en conflit (comme, par exemple, dans le cas des pays qui favorisent le droit à la [vie privée](https://fr.wikipedia.org/wiki/Vie_priv%C3%A9e) et le [droit à l'oubli](https://fr.wikipedia.org/wiki/Droit_%C3%A0_l%27oubli), par rapport aux pays qui favorisent la [conversation des données](https://fr.wikipedia.org/wiki/Conservation_des_donn%C3%A9es) étendue). Considérons également que l'accès au paquet n'est pas limité à des pays ou des juridictions spécifiques, et par conséquent, la base d'utilisateurs du paquet est susceptible de la diversité géographique. Ces points pris en compte, je ne suis pas en mesure de dire ce que cela signifie d'être « conforme à la loi » pour tous les utilisateurs, à tous égards. Cependant, j'espère que les informations contenues dans le présent document vous aideront à prendre vous-même une décision concernant ce que vous devez faire pour rester juridiquement conforme dans le cadre du paquet. Si vous avez des doutes ou des préoccupations concernant les informations contenues dans le présent document, ou si vous avez besoin d'aide supplémentaire et de conseils d'un point de vue juridique, je recommande de consulter un professionnel du droit qualifié.

#### 11.1 RESPONSABILITÉ

Comme déjà indiqué par la licence de paquet, le paquet est fourni sans aucune garantie. Cela inclut (mais n'est pas limité à) toute la portée de la responsabilité. Le paquet est fourni pour votre commodité, dans l'espoir qu'il vous sera utile, et qu'il vous apportera un certain avantage. Cependant, que vous utilisiez ou implémentiez le package, vous avez le choix. Vous n'êtes pas obligé d'utiliser ou de mettre en œuvre le package, mais lorsque vous le faites, vous êtes responsable de cette décision. Ni moi, ni aucun autre contributeur au paquet, ne sommes légalement responsables des conséquences des décisions que vous prenez, qu'elles soient directes, indirectes, implicites ou autres.

#### 11.2 TIERS

En fonction de sa configuration et de son implémentation exactes, le paquet peut communiquer et partager des informations avec des tiers dans certains cas. Ces informations peuvent être définies comme des « [données personnelles](https://fr.wikipedia.org/wiki/Donn%C3%A9es_personnelles) » (PII) dans certains contextes, par certaines juridictions.

La manière dont ces informations peuvent être utilisées par ces tiers est soumise aux différentes politiques énoncées par ces tiers et ne relève pas de cette documentation. Cependant, dans tous ces cas, le partage d'informations avec ces tiers peut être désactivé. Dans tous ces cas, si vous choisissez de l'activer, vous êtes responsable de rechercher toute préoccupation que vous pourriez avoir concernant la confidentialité, la sécurité, et l'utilisation des informations personnelles par ces tiers. Si des doutes existent, ou si vous n'êtes pas satisfait de la conduite de ces tiers en ce qui concerne les PII, il peut être préférable de désactiver tout partage d'informations avec ces tiers.

Dans un souci de transparence, le type d'informations partagées, et avec qui, est décrit ci-dessous.

##### 11.2.0 RECHERCHE DE NOM D'HÔTE

Si vous utilisez des fonctionnalités ou des modules destinés à fonctionner avec des noms d'hôte (tel que le « module de blocage pour les hôtes mauvais », « tor project exit nodes block module », ou la « vérification des moteurs de recherche », par exemple), CIDRAM doit pouvoir obtenir le nom d'hôte des requêtes entrantes en quelque sorte. Généralement, il le fait en demandant le nom d'hôte de l'adresse IP des requêtes entrantes provenant d'un serveur DNS, ou en demandant l'information via la fonctionnalité fournie par le système sur lequel CIDRAM est installé (ceci est généralement appelé « recherche de nom d'hôte »). Les serveurs DNS définis par défaut appartiennent au [service DNS de Google](https://dns.google.com/) (mais cela peut être facilement modifié via la configuration). Les services précis communiqués sont configurables et dépendent de la configuration du package. Dans le cas de l'utilisation des fonctionnalités fournies par le système sur lequel CIDRAM est installé, vous devrez contacter votre administrateur système pour déterminer les routes qu'utilisent les recherches de nom d'hôte. Les recherches de noms d'hôtes peuvent être évitées dans CIDRAM en évitant les modules affectés ou en modifiant la configuration du package en fonction de vos besoins.

*Directives de configuration pertinentes :*
- `general` -> `default_dns`
- `general` -> `search_engine_verification`
- `general` -> `social_media_verification`
- `general` -> `force_hostname_lookup`
- `general` -> `allow_gethostbyaddr_lookup`

##### 11.2.1 WEBFONTS

Certains thèmes personnalisés, et aussi l'interface utilisateur standard pour l'accès frontal de CIDRAM, et la page « Accès Refusé », peuvent utiliser des webfonts pour des raisons esthétiques. Les webfonts sont désactivées par défaut, mais lorsqu'elles sont activées, la communication directe entre le navigateur de l'utilisateur et le service hébergeant les webfonts produit. Cela peut éventuellement impliquer la communication d'informations telles que l'adresse IP de l'utilisateur, l'agent utilisateur, le système d'exploitation et d'autres informations disponibles à la requête. La plupart de ces webfonts sont hébergées par le service [Google Fonts](https://fonts.google.com/).

*Directives de configuration pertinentes :*
- `general` -> `disable_webfonts`

##### 11.2.2 VÉRIFICATION DES MOTEURS DE RECHERCHE ET DES MÉDIAS SOCIAUX

Lorsque la vérification des moteurs de recherche est activée, CIDRAM tente d'effectuer des « recherches DNS directes » pour vérifier si les requêtes qui prétendent provenir des moteurs de recherche sont authentiques. Également, lorsque la vérification des médias sociaux est activée, CIDRAM fait de même pour les requêtes apparentes de médias sociaux. Pour ce faire, il utilise le service [Google DNS](https://dns.google.com/) pour tenter de résoudre les adresses IP à partir des noms d'hôte de ces requêtes entrantes (dans ce processus, les noms d'hôte de ces requêtes entrantes sont partagés avec le service).

*Directives de configuration pertinentes :*
- `general` -> `search_engine_verification`
- `general` -> `social_media_verification`

##### 11.2.3 GOOGLE reCAPTCHA

Facultativement, CIDRAM prend en charge [Google reCAPTCHA](https://www.google.com/recaptcha/), ce qui permet aux utilisateurs de contourner la page « Accès Refusé » en complétant une instance de reCAPTCHA (plus d'informations sur cette fonctionnalité sont décrites plus haut dans la documentation, notamment dans la section de configuration). Google reCAPTCHA nécessite des clés API pour fonctionner correctement, et est par conséquent désactivé par défaut. Il peut être activé en définissant les clés API requises dans la configuration du package. Lorsqu'elle est activée, la communication directe entre le navigateur de l'utilisateur et le service reCAPTCHA se produit. Cela peut éventuellement impliquer la communication d'informations telles que l'adresse IP de l'utilisateur, l'agent utilisateur, le système d'exploitation et d'autres informations disponibles à la requête. L'adresse IP de l'utilisateur peut également être partagée dans la communication entre CIDRAM et le service reCAPTCHA lors de la vérification de la validité d'une instance de reCAPTCHA et de la vérification de sa réussite.

*Directives de configuration pertinentes : Tout ce qui est listé dans la catégorie de configuration "recaptcha".*

##### 11.2.4 STOP FORUM SPAM

[Stop Forum Spam](https://www.stopforumspam.com/) est un service fantastique et gratuit qui peut aider à protéger les forums, les blogs et les sites Web contre les spammeurs. Il le fait en fournissant une base de données de spammeurs connus, et une API qui peut être utilisée pour vérifier si une adresse IP, un nom d'utilisateur, ou une adresse e-mail est répertorié dans sa base de données.

CIDRAM fournit un module facultatif qui exploite cette API pour vérifier si l'adresse IP des requêtes entrantes appartient à un spammeur suspecté. Le module n'est pas installé par défaut, mais si vous choisissez de l'installer, les adresses IP des utilisateurs peuvent être partagées avec l'API Stop Forum Spam conformément à l'usage prévu du module. Lorsque le module est installé, CIDRAM communique avec cette API chaque fois qu'une requête entrante requête une ressource que CIDRAM reconnaît comme un type de ressource fréquemment ciblée par les spammeurs (tels que les pages de connexion, les pages d'enregistrement, les pages de vérification par courriel, les formulaires de commentaires, etc).

#### 11.3 JOURNALISATION

La journalisation est une partie importante de CIDRAM pour un certain nombre de raisons. Il peut être difficile de diagnostiquer et de résoudre les faux positifs lorsque les événements de blocage qui les provoquent ne sont pas journalisés. Sans journaliser les événements de blocage, il peut être difficile de déterminer exactement comment CIDRAM est performant dans un contexte particulier, et il peut être difficile de déterminer où ses lacunes peuvent être, et quels changements peuvent être nécessaires à sa configuration ou à ses signatures en conséquence, afin de continuer à fonctionner comme prévu. Quoi qu'il en soit, la journalisation peut ne pas être souhaitable pour tous les utilisateurs, et reste entièrement facultative. Dans CIDRAM, la journalisation est désactivée par défaut. Pour l'activer, CIDRAM doit être configuré en accord.

Aditionellement, si la journalisation est légalement autorisée, et dans la mesure où elle est légalement permise (par exemple, les types d'informations pouvant être journalisées, pendant combien de temps, et dans quelles circonstances), peut varier, selon la juridiction et le contexte dans lequel CIDRAM est mis en œuvre (par exemple, si vous opérez en tant qu'individu, en tant qu'entreprise, et si sur une base commerciale ou non-commerciale). Il peut donc être utile pour que vous lisiez attentivement cette section.

Il existe plusieurs types de journalisation que CIDRAM peut effectuer. Différents types de journalisation impliquent différents types d'informations, pour différentes raisons.

##### 11.3.0 ÉVÉNEMENTS DE BLOCAGE

Le principal type de journalisation que CIDRAM peut effectuer concerne les « événements de blocage ». Ce type de journalisation concerne le moment où CIDRAM bloque une requête, et peut être fourni sous trois formats différents :
- Fichiers journaux lisibles par l'homme.
- Fichiers journaux de style Apache.
- Fichiers journaux sérialisés.

Un événement de blocage, journalisé sur un fichier journal lisible par un humain, ressemble généralement à ceci (à titre d'exemple) :

```
ID : 1234
La version du script : CIDRAM v1.6.0
Date/Heure : Day, dd Mon 20xx hh:ii:ss +0000
IP Adresse : x.x.x.x
Nom d'hôte : dns.hostname.tld
Signatures Compte : 1
Signatures Référence : x.x.x.x/xx
Raison Bloquée : Service de cloud ("Nom de réseau", Lxx:Fx, [XX])!
Agent Utilisateur : Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.181 Safari/537.36
Reconstruite URI : http://your-site.tld/index.php
État reCAPTCHA : Activée.
```

Ce même événement de blocage, journalisé à un fichier journal de style Apache, ressemblerait à ceci :

```
x.x.x.x - - [Day, dd Mon 20xx hh:ii:ss +0000] "GET /index.php HTTP/1.1" 200 xxxx "-" "Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.181 Safari/537.36"
```

Un événement blocage journalisé inclut généralement les informations suivantes :
- Un numéro d'identification référençant l'événement de blocage.
- La version de CIDRAM actuellement utilisée.
- La date et l'heure auxquelles l'événement de blocage s'est produit.
- L'adresse IP de la requête bloquée.
- Le nom d'hôte de l'adresse IP de la requête bloquée (si disponible).
- Le nombre de signatures déclenchées par la requête.
- Références aux signatures déclenchées.
- Références aux raisons de l'événement de blocage et à certaines informations de débogage de base liées.
- L'agent utilisateur de la requête bloquée (comment l'entité requérante s'est identifiée à la requête).
- Une reconstruction de l'identifiant de la ressource initialement requêtée.
- L'état reCAPTCHA pour la requête en cours (le cas échéant).

*Les directives de configuration responsables de ce type de journalisation, et pour chacun des trois formats disponibles, sont :*
- `general` -> `logfile`
- `general` -> `logfileApache`
- `general` -> `logfileSerialized`

Lorsque ces directives sont laissées vides, ce type de journalisation reste désactivé.

##### 11.3.1 JOURNALISATION reCAPTCHA

Ce type de journalisation concerne spécifiquement les instances reCAPTCHA, et se produit uniquement lorsqu'un utilisateur tente de terminer une instance reCAPTCHA.

Une entrée de journal reCAPTCHA contient l'adresse IP de l'utilisateur qui tente de terminer une instance reCAPTCHA, la date et l'heure auxquelles la tentative s'est produite, et l'état reCAPTCHA. Une entrée de journal reCAPTCHA ressemble généralement à ceci (à titre d'exemple) :

```
IP Adresse : x.x.x.x - Date/Heure : Day, dd Mon 20xx hh:ii:ss +0000 - État reCAPTCHA : Passé !
```

*La directive de configuration responsable de la journalisation reCAPTCHA est :*
- `recaptcha` -> `logfile`

##### 11.3.2 JOURNALISATION FRONTALE

Ce type de journalisation concerne les tentatives de connexion frontale, et se produit uniquement lorsqu'un utilisateur tente de se connecter à l'accès frontal (en supposant que l'accès frontal est activé).

Une entrée de journal frontal contient l'adresse IP de l'utilisateur qui tente de se connecter, la date et l'heure de la tentative, et les résultats de la tentative (connecté avec succès ou sans succès). Une entrée de journal frontal ressemble généralement à ceci (à titre d'exemple) :

```
x.x.x.x - Day, dd Mon 20xx hh:ii:ss +0000 - "admin" - Connecté.
```

*La directive de configuration responsable de la journalisation frontale est :*
- `general` -> `FrontEndLog`

##### 11.3.3 ROTATION DES JOURNAUX

Vous voudrez peut-être purger les journaux après un certain temps, ou peut être requis de le faire par la loi (c'est à dire, la durée légale de la conservation des journaux peut être limitée par la loi). Vous pouvez y parvenir en incluant des marqueurs de date/heure dans les noms de vos fichiers journaux (par exemple, `{yyyy}-{mm}-{dd}.log`), conformément à la configuration de votre package, puis en activant la rotation des journaux (la rotation des journaux vous permet d'effectuer des actions sur les fichiers journaux lorsque les limites spécifiées sont dépassées).

Par exemple : Si j'étais légalement requis de supprimer les journaux après 30 jours, je pourrais spécifier `{dd}.log` dans les noms de mes fichiers journaux (`{dd}` représente les jours), définir la valeur de `log_rotation_limit` à 30, et définir la valeur de `log_rotation_action` à `Delete`.

À l'inverse, si vous devez conserver les journaux pendant une période prolongée, vous ne pouvez pas utiliser la rotation des journaux, ou vous pouvez définir la valeur de `log_rotation_action` à `Archive`, pour compresser les fichiers journaux, réduisant ainsi la quantité totale d'espace disque qu'ils occupent.

*Directives de configuration pertinentes :*
- `general` -> `log_rotation_limit`
- `general` -> `log_rotation_action`

##### 11.3.4 TRONCATION DES JOURNAUX

Il est également possible de tronquer des fichiers journaux individuels lorsqu'ils dépassent une certaine taille, si c'est quelque chose que vous pourriez avoir besoin ou que vous voulez faire.

*Directives de configuration pertinentes :*
- `general` -> `truncate`

##### 11.3.5 PSEUDONYMISATION D'ADRESSE IP

Premièrement, si vous n'êtes pas familier avec le terme, « pseudonymisation » se réfère au traitement des données personnelles en tant que tel, il ne peut plus être identifié à une personne concernée sans information supplémentaire, et à condition que ces informations supplémentaires soient conservées séparément, et soumis à des mesures techniques et organisationnelles pour s'assurer que les données personnelles ne peuvent être identifiées à aucune personnes naturelles.

Les ressources suivantes peuvent aider à l'expliquer plus en détail :
- [[les-infostrateges.com] RGPD : entre anonymisation et pseudonymisation](http://www.les-infostrateges.com/actu/18012505/rgpd-entre-anonymisation-et-pseudonymisation)
- [[Wikipedia] Pseudonymisation](https://fr.wikipedia.org/wiki/Pseudonymisation)

Dans certaines circonstances, vous pouvez être légalement requis d'anonymiser ou de pseudonymiser toute PII collectée, traitée, ou stockée. Bien que ce concept existe depuis longtemps, le GDPR/DSGVO mentionne notamment, et encourage spécifiquement la « pseudonymisation ».

CIDRAM est capable de pseudonymiser les adresses IP lors de la connexion, si c'est quelque chose que vous pourriez avoir besoin ou que vous voulez faire. Lorsque CIDRAM pseudonymise les adresses IP, lorsqu'il est connecté, l'octet final des adresses IPv4, et tout ce qui suit la deuxième partie des adresses IPv6 est représenté par un « x » (arrondir efficacement les adresses IPv4 à l'adresse initiale du 24ème sous-réseau dans lequel elles sont factorisées, et les adresses IPv6 à l'adresse initiale du 32ème sous-réseau dans lequel elles sont factorisées).

*Remarque : Le processus dans CIDRAM pour la pseudonymisation des adresses IP n'affecte pas la fonction de suivi des adresses IP dans CIDRAM. Si cela pour vous pose un problème, il est préférable de désactiver entièrement le suivi des adresses IP. Cela peut être réalisé en mettre la valeur de `track_mode` à `false`, et en évitant les modules.*

*Directives de configuration pertinentes :*
- `signatures` -> `track_mode`
- `legal` -> `pseudonymise_ip_addresses`

##### 11.3.6 OMETTRE DES INFORMATIONS DE JOURNAL

Si vous voulez aller plus loin en empêchant la journalisation complète de certains types d'informations, c'est également possible. CIDRAM fournit des directives de configuration pour contrôler si les adresses IP, les noms d'hôtes, et les agents utilisateurs sont inclus dans les journaux. Par défaut, ces trois points de données sont inclus dans les journaux lorsqu'ils sont disponibles. La définition de l'une de ces directives de configuration sur `true` omettra les informations correspondantes des journaux.

*Remarque : Il n'y a aucune raison de pseudonymiser les adresses IP quand vous les omettez complètement dans les journaux.*

*Directives de configuration pertinentes :*
- `legal` -> `omit_ip`
- `legal` -> `omit_hostname`
- `legal` -> `omit_ua`

##### 11.3.7 STATISTIQUES

CIDRAM est facultativement capable de suivre des statistiques telles que le nombre total d'événements de blocage ou les instances de reCAPTCHA qui ont eu lieu depuis un certain moment. Cette fonctionnalité est désactivée par défaut, mais peut être activée via la configuration du package. Cette fonctionnalité suit uniquement le nombre total d'événements survenus et n'inclut aucune information sur des événements spécifiques (et par conséquent, ne devrait pas être considéré comme les PII).

*Directives de configuration pertinentes :*
- `general` -> `statistics`

##### 11.3.8 CRYPTAGE

CIDRAM ne crypte pas son cache ou aucune information de journal. Le [cryptage](https://fr.wikipedia.org/wiki/Chiffrement) des cache et des journaux peuvent être introduits à l'avenir, mais il n'existe actuellement aucun plan spécifique. Si vous craignez que des tiers non autorisés puissent accéder à des parties de CIDRAM pouvant contenir des informations personnelles/sensibles telles que son cache ou ses journaux, je recommanderais que CIDRAM ne soit pas installé dans un endroit accessible au public (par exemple, installer CIDRAM en dehors du répertoire `public_html` standard ou équivalent disponible pour la plupart des serveurs web standard) et et que des autorisations appropriées restrictives soient appliquées pour le répertoire où il réside (en particulier, pour le répertoire vault). Si ce n'est pas suffisant pour répondre à vos préoccupations, configurez CIDRAM de telle sorte que les types d'informations à l'origine de vos préoccupations ne soient pas collectées ou journalisées en premier lieu (tel que en désactivant la journalisation).

#### 11.4 COOKIES

CIDRAM définit les [cookies](https://fr.wikipedia.org/wiki/Cookie_(informatique)) à deux points dans son code. Premièrement, lorsqu'un utilisateur termine avec succès une instance de reCAPTCHA (et en supposant que `lockuser` est mis à `true`), CIDRAM définit un cookie afin de pouvoir se souvenir pour les requêtes suivantes que l'utilisateur a déjà terminé une instance de reCAPTCHA, de sorte qu'il n'aura pas besoin de requêter continuellement à l'utilisateur de terminer une instance de reCAPTCHA lors des requêtes suivantes. Deuxièmement, lorsqu'un utilisateur se connecte avec succès à l'accès frontal, CIDRAM définit un cookie afin de pouvoir se souvenir de l'utilisateur pour les requêtes suivantes (c'est à dire, les cookies sont utilisés pour authentifier l'utilisateur à une session de connexion).

Dans les deux cas, les avertissements de cookie sont affichés en évidence (le cas échéant), avertissant l'utilisateur que les cookies seront définis s'ils s'engagent dans les actions correspondantes. Les cookies ne sont définis à aucun autre endroit du code.

*Remarque : La mise en œuvre particulière par CIDRAM de l'API « invisible » pour reCAPTCHA pourrait être incompatible avec les lois sur les cookies dans certaines juridictions, et devrait être évitée par tous les sites web soumis à ces lois. Opter pour l'utilisation de l'API « V2 » à la place, ou simplement désactiver complètement reCAPTCHA, peut être préférable.*

*Directives de configuration pertinentes :*
- `general` -> `disable_frontend`
- `recaptcha` -> `lockuser`
- `recaptcha` -> `api`

#### 11.5 COMMERCIALISATION ET PUBLICITÉ

CIDRAM ni collecte ni traite aucune information à des fins de commercialisation ou de publicité, et ni vend ni profite d'aucune information collectée ou journalisée. CIDRAM n'est pas une entreprise commerciale, et n'est pas lié à des intérêts commerciaux, donc faire ces choses n'aurait aucun sens. Cela a été le cas depuis le début du projet, et continue d'être le cas aujourd'hui. Aditionellement, faire ces choses serait contre-productif à l'esprit et à l'objectif du projet dans son ensemble, et aussi longtemps que je continuerai à maintenir le projet, cela n'arrivera jamais.

#### 11.6 POLITIQUE DE CONFIDENTIALITÉ

Dans certaines circonstances, vous pouvez être légalement tenu d'afficher clairement un lien vers votre politique de confidentialité sur toutes les pages et sections de votre site Web. Cela peut être important pour s'assurer que les utilisateurs sont bien informés de vos pratiques exactes de confidentialité, les types de PII que vous collectez, et comment vous avez l'intention de l'utiliser. Afin de pouvoir inclure un lien sur la page « Accès Refusé » de CIDRAM, une directive de configuration est fournie pour spécifier l'URL de votre politique de confidentialité.

*Remarque : Il est fortement recommandé que votre page de politique de confidentialité ne soit pas placée derrière la protection de CIDRAM. Si CIDRAM protège votre page de politique de confidentialité, et qu'un utilisateur bloqué par CIDRAM clique sur le lien de votre politique de confidentialité, il sera simplement à nouveau bloqué et ne pourra pas voir votre politique de confidentialité. Idéalement, vous devez créer un lien vers une copie statique de votre politique de confidentialité, telle qu'une page HTML, ou un fichier en texte brut qui n'est pas protégé par CIDRAM.*

*Directives de configuration pertinentes :*
- `legal` -> `privacy_policy`

#### 11.7 GDPR/DSGVO

Le règlement général sur la protection des données (GDPR) est un règlement de l'Union européenne qui entrera en vigueur le 25 Mai 2018. L'objectif principal de la réglementation est de permettre aux citoyens et aux résidents de l'UE de contrôler leurs propres données personnelles et d'unifier la réglementation au sein de l'UE en matière de vie privée et de données personnelles.

Le règlement contient des dispositions spécifiques relatives au traitement [des informations personnelles identifiables](https://fr.wikipedia.org/wiki/Donn%C3%A9es_personnelles) de toute « personne concernée » (toute personne physique identifiée ou identifiable) provenant ou provenant de l'UE. Pour être conforme à la réglementation, les « entreprises » (telles que définies par la réglementation) et tous les systèmes et processus pertinents doivent implémenter « [protection de la vie privée dès la conception](https://fr.wikipedia.org/wiki/Protection_de_la_vie_priv%C3%A9e_d%C3%A8s_la_conception) » par défaut, doivent utiliser les paramètres de confidentialité les plus élevés possibles, doivent mettre en œuvre les garanties nécessaires pour toute information stockée ou traitée (y compris, mais sans s'y limiter, la mise en œuvre de la pseudonymisation ou l'anonymisation complète des données), doivent déclarer clairement et sans ambiguïté les types de données qu'ils collectent, comment ils les traitent, pour quelles raisons, pour combien de temps ils les conservent, et s'ils partagent ces données avec des tiers, les types de données partagées avec des tiers, comment, pourquoi, et ainsi de suite.

Les données ne peuvent pas être traitées à moins qu'il y ait une base légale pour le faire, tel que défini par le règlement. En général, cela signifie que pour traiter les données d'une personne concernée sur une base légale, cela doit être fait conformément aux obligations légales, ou seulement après qu'un consentement explicite, bien informé et sans ambiguïté a été obtenu de la personne concernée.

Étant donné que certains aspects de la réglementation peuvent évoluer dans le temps, afin d'éviter la propagation d'informations périmées, il peut être préférable de connaître la réglementation auprès d'une source autorisée, par opposition à simplement inclure les informations pertinentes ici dans la documentation du paquet (dont peut éventuellement devenir obsolète à mesure que la réglementation évolue).

[EUR-Lex](https://eur-lex.europa.eu/) (une partie du site officiel de l'Union européenne qui fournit des informations sur le droit de l'UE) fournit des informations détaillées sur GDPR/DSGVO, disponible en 24 langues différentes (au moment de la rédaction de ce document), et disponible en téléchargement au format PDF. Je recommande vivement de lire les informations qu'ils fournissent, afin d'en savoir plus sur GDPR/DSGVO :
- [RÈGLEMENT (UE) 2016/679 DU PARLEMENT EUROPÉEN ET DU CONSEIL](https://eur-lex.europa.eu/legal-content/FR/TXT/?uri=celex:32016R0679)

Alternativement, il y a un bref aperçu (non autorisé) de GDPR/DSGVO disponible sur Wikipedia :
- [Règlement général sur la protection des données](https://fr.wikipedia.org/wiki/R%C3%A8glement_g%C3%A9n%C3%A9ral_sur_la_protection_des_donn%C3%A9es)

---


Dernière mise à jour : 10 Août 2018 (2018.08.10).
