<?php
/**
 * This file is a part of the CIDRAM package, and can be downloaded for free
 * from {@link https://github.com/Maikuolan/CIDRAM/ GitHub}.
 *
 * CIDRAM COPYRIGHT 2016 and beyond by Caleb Mazalevskis (Maikuolan).
 *
 * License: GNU/GPLv2
 * @see LICENSE.txt
 *
 * This file: French language data for the front-end (last modified: 2017.04.21).
 */

/** Prevents execution from outside of CIDRAM. */
if (!defined('CIDRAM')) {
    die('[CIDRAM] This should not be accessed directly.');
}

$CIDRAM['lang']['bNav_home_logout'] = '<a href="?">Page d\'Accueil</a> | <a href="?cidram-page=logout">Déconnecter</a>';
$CIDRAM['lang']['bNav_logout'] = '<a href="?cidram-page=logout">Déconnecter</a>';
$CIDRAM['lang']['config_general_ban_override'] = 'Remplacer "forbid_on_block" lorsque "infraction_limit" est dépassé? En cas de remplacement: Les demandes bloquées renvoient une page blanche (les fichiers modèles ne sont pas utilisés). 200 = Ne pas remplacer [Défaut]; 403 = Remplacer par "403 Forbidden"; 503 = Remplacer par "503 Service unavailable".';
$CIDRAM['lang']['config_general_default_dns'] = 'Une liste délimitée par des virgules de serveurs DNS à utiliser pour les recherches de noms d\'hôtes. Par Défaut = "8.8.8.8,8.8.4.4" (Google DNS). AVERTISSEMENT: Ne pas changer si vous ne sais pas ce que vous faites!';
$CIDRAM['lang']['config_general_disable_cli'] = 'Désactiver le mode CLI?';
$CIDRAM['lang']['config_general_disable_frontend'] = 'Désactiver l\'accès frontal?';
$CIDRAM['lang']['config_general_disable_webfonts'] = 'Désactiver les webfonts? True = Oui; False = Non [Défaut].';
$CIDRAM['lang']['config_general_emailaddr'] = 'Adresse e-mail pour le support.';
$CIDRAM['lang']['config_general_forbid_on_block'] = 'Quels têtes devrait CIDRAM répondre avec lors de bloquer les demandes?';
$CIDRAM['lang']['config_general_FrontEndLog'] = 'Fichier pour l\'enregistrement des tentatives de connexion à l\'accès frontal. Spécifier un fichier, ou laisser vide à désactiver.';
$CIDRAM['lang']['config_general_ipaddr'] = 'Où trouver l\'adresse IP de demandes de connexion? (Utile pour services tels que Cloudflare et similaires). Par Défaut = REMOTE_ADDR. AVERTISSEMENT: Ne pas changer si vous ne sais pas ce que vous faites!';
$CIDRAM['lang']['config_general_lang'] = 'Spécifiez la langue défaut pour CIDRAM.';
$CIDRAM['lang']['config_general_logfile'] = 'Un fichier lisible par l\'homme pour enregistrement de toutes les tentatives d\'accès bloquées. Spécifier un fichier, ou laisser vide à désactiver.';
$CIDRAM['lang']['config_general_logfileApache'] = 'Un fichier dans le style d\'Apache pour enregistrement de toutes les tentatives d\'accès bloquées. Spécifier un fichier, ou laisser vide à désactiver.';
$CIDRAM['lang']['config_general_logfileSerialized'] = 'Un fichier sérialisé pour enregistrement de toutes les tentatives d\'accès bloquées. Spécifier un fichier, ou laisser vide à désactiver.';
$CIDRAM['lang']['config_general_log_banned_ips'] = 'Inclure les demandes bloquées provenant d\'IP interdites dans les fichiers journaux? True = Oui [Défaut]; False = Non.';
$CIDRAM['lang']['config_general_max_login_attempts'] = 'Nombre maximal de tentatives de connexion.';
$CIDRAM['lang']['config_general_protect_frontend'] = 'Spécifie si les protections normalement fournies par CIDRAM doivent être appliquées à l\'accès frontal. True = Oui [Défaut]; False = Non.';
$CIDRAM['lang']['config_general_search_engine_verification'] = 'Essayez de vérifier les moteurs de recherche? Vérification des moteurs de recherche assure qu\'ils ne seront pas interdits en raison de dépassement de la limite d\'infraction (l\'interdiction des moteurs de recherche de votre site web aura généralement un effet négatif sur votre moteur de recherche classement, SEO, etc). Lorsqu\'ils sont vérifiés, les moteurs de recherche peuvent être bloqués comme d\'habitude, mais ne seront pas interdits. Lorsqu\'ils ne sont pas vérifiés, il est possible qu\'ils soient interdits en raison du dépassement de la limite d\'infraction. Aussi, la vérification des moteurs de recherche offre une protection contre les fausses demandes des moteurs de recherche et contre les entités potentiellement malveillantes masquer comme moteurs de recherche (ces requêtes seront bloquées lorsque la vérification du moteur de recherche est activée). True = Activer la vérification du moteurs de recherche [Défaut]; False = Désactiver la vérification du moteurs de recherche.';
$CIDRAM['lang']['config_general_silent_mode'] = 'Devrait CIDRAM rediriger silencieusement les tentatives d\'accès bloquées à la place de l\'affichage de la page "Accès Refusé"? Si oui, spécifiez l\'emplacement pour rediriger les tentatives d\'accès bloquées. Si non, laisser cette variable vide.';
$CIDRAM['lang']['config_general_timeFormat'] = 'Le format de notation de la date/heure utilisé par CIDRAM. Des options supplémentaires peuvent être ajoutées sur demande.';
$CIDRAM['lang']['config_general_timeOffset'] = 'Décalage horaire en minutes.';
$CIDRAM['lang']['config_recaptcha_expiry'] = 'Nombre d\'heures à retenir des instances reCAPTCHA.';
$CIDRAM['lang']['config_recaptcha_lockip'] = 'Verrouiller reCAPTCHA aux adresses IP?';
$CIDRAM['lang']['config_recaptcha_lockuser'] = 'Verrouiller reCAPTCHA aux les utilisateurs?';
$CIDRAM['lang']['config_recaptcha_logfile'] = 'Enregistrez toutes les tentatives du reCAPTCHA? Si oui, indiquez le nom à utiliser pour le fichier d\'enregistrement. Si non, laisser vide ce variable.';
$CIDRAM['lang']['config_recaptcha_secret'] = 'Cette valeur devrait correspondre à la "secret key" pour votre reCAPTCHA, qui se trouve dans le tableau de bord reCAPTCHA.';
$CIDRAM['lang']['config_recaptcha_sitekey'] = 'Cette valeur devrait correspondre à la "site key" pour votre reCAPTCHA, qui se trouve dans le tableau de bord reCAPTCHA.';
$CIDRAM['lang']['config_recaptcha_usemode'] = 'Définit comment CIDRAM doit utiliser reCAPTCHA (voir documentation).';
$CIDRAM['lang']['config_signatures_block_bogons'] = 'Bloquer CIDRs bogon/martian? Si vous attendre connexions à votre website à partir de dans votre réseau local, à partir de localhost, ou à partir de votre LAN, cette directive devrait être fixé sur false. Si vous ne attendez pas à ces telles connexions, cette directive doit être fixé comme true.';
$CIDRAM['lang']['config_signatures_block_cloud'] = 'Bloquer CIDRs identifié comme appartenant à hébergement/cloud services? Si vous utilisez un service d\'API à partir de votre website ou si vous attendez d\'autres sites à connecter avec votre website, cette directive devrait être fixé sur false. Si vous ne pas, puis, cette directive doit être fixé comme true.';
$CIDRAM['lang']['config_signatures_block_generic'] = 'Bloquer CIDRs recommandé en généralement pour les listes noires? Cela couvre toutes les signatures qui ne sont pas marqué comme étant partie de l\'autre plus spécifique catégories de signatures.';
$CIDRAM['lang']['config_signatures_block_proxies'] = 'Bloquer CIDRs identifié comme appartenant à services de proxy? Si vous avez besoin que les utilisateurs puissent accéder à votre site web à partir des services de proxy anonymes, cette directive devrait être fixé sur false. Autrement, si vous n\'avez besoin pas de proxies anonymes, cette directive devrait être fixé sur true comme moyen d\'améliorer la sécurité.';
$CIDRAM['lang']['config_signatures_block_spam'] = 'Bloquer CIDRs identifié comme étant risque élevé pour le spam? Sauf si vous rencontrez des problèmes quand vous faire, en généralement, cette directive devrait toujours être fixé comme true.';
$CIDRAM['lang']['config_signatures_default_tracktime'] = 'Combien de secondes pour suivre les IP interdites par les modules. Défaut = 604800 (1 semaine).';
$CIDRAM['lang']['config_signatures_infraction_limit'] = 'Nombre maximal d\'infractions qu\'une IP est autorisée à engager avant d\'être interdite par la surveillance des IPs. Défaut = 10.';
$CIDRAM['lang']['config_signatures_ipv4'] = 'Une liste des fichiers du signatures IPv4 que CIDRAM devrait tenter d\'utiliser, délimité par des virgules.';
$CIDRAM['lang']['config_signatures_ipv6'] = 'Une liste des fichiers du signatures IPv6 que CIDRAM devrait tenter d\'utiliser, délimité par des virgules.';
$CIDRAM['lang']['config_signatures_modules'] = 'Une liste des fichiers modules à charger après exécuter des signatures IPv4/IPv6, délimité par des virgules.';
$CIDRAM['lang']['config_signatures_track_mode'] = 'Quand faut-il compter les infractions? False = Quand les adresses IP sont bloquées par des modules. True = Quand les adresses IP sont bloquées pour une raison quelconque.';
$CIDRAM['lang']['config_template_data_css_url'] = 'URL de fichier CSS pour les thèmes personnalisés.';
$CIDRAM['lang']['field_activate'] = 'Activer';
$CIDRAM['lang']['field_banned'] = 'Interdit';
$CIDRAM['lang']['field_blocked'] = 'Bloqué';
$CIDRAM['lang']['field_clear'] = 'Annuler';
$CIDRAM['lang']['field_component'] = 'Composant';
$CIDRAM['lang']['field_create_new_account'] = 'Créer un nouveau compte';
$CIDRAM['lang']['field_deactivate'] = 'Désactiver';
$CIDRAM['lang']['field_delete_account'] = 'Supprimer le compte';
$CIDRAM['lang']['field_delete_file'] = 'Supprimer';
$CIDRAM['lang']['field_download_file'] = 'Télécharger';
$CIDRAM['lang']['field_edit_file'] = 'Modifier';
$CIDRAM['lang']['field_expiry'] = 'Expiration';
$CIDRAM['lang']['field_file'] = 'Fichier';
$CIDRAM['lang']['field_filename'] = 'Nom de fichier: ';
$CIDRAM['lang']['field_filetype_directory'] = 'Répertoire';
$CIDRAM['lang']['field_filetype_info'] = '{EXT} Fichier';
$CIDRAM['lang']['field_filetype_unknown'] = 'Inconnu';
$CIDRAM['lang']['field_infractions'] = 'Infractions';
$CIDRAM['lang']['field_install'] = 'Installer';
$CIDRAM['lang']['field_ip_address'] = 'Adresse IP';
$CIDRAM['lang']['field_latest_version'] = 'Dernière Version';
$CIDRAM['lang']['field_log_in'] = 'Connecter';
$CIDRAM['lang']['field_new_name'] = 'Nouveau nom:';
$CIDRAM['lang']['field_ok'] = 'D\'accord';
$CIDRAM['lang']['field_options'] = 'Options';
$CIDRAM['lang']['field_password'] = 'Mot de Passe';
$CIDRAM['lang']['field_permissions'] = 'Autorisations';
$CIDRAM['lang']['field_range'] = 'Gamma (Première – Final)';
$CIDRAM['lang']['field_rename_file'] = 'Renommer';
$CIDRAM['lang']['field_reset'] = 'Réinitialiser';
$CIDRAM['lang']['field_set_new_password'] = 'Définir nouveau mot de passe';
$CIDRAM['lang']['field_size'] = 'Taille totale: ';
$CIDRAM['lang']['field_size_bytes'] = 'octets';
$CIDRAM['lang']['field_size_GB'] = 'Go';
$CIDRAM['lang']['field_size_KB'] = 'Ko';
$CIDRAM['lang']['field_size_MB'] = 'Mo';
$CIDRAM['lang']['field_size_TB'] = 'To';
$CIDRAM['lang']['field_status'] = 'Statut';
$CIDRAM['lang']['field_tracking'] = 'Surveillance';
$CIDRAM['lang']['field_uninstall'] = 'Désinstaller';
$CIDRAM['lang']['field_update'] = 'Mettre à Jour';
$CIDRAM['lang']['field_update_all'] = 'Tout mettre à jour';
$CIDRAM['lang']['field_upload_file'] = 'Télécharger un nouveau fichier';
$CIDRAM['lang']['field_username'] = 'Nom d\'Utilisateur';
$CIDRAM['lang']['field_your_version'] = 'Votre Version';
$CIDRAM['lang']['header_login'] = 'Merci de vous connecter pour continuer.';
$CIDRAM['lang']['label_cidram'] = 'Version CIDRAM utilisée:';
$CIDRAM['lang']['label_os'] = 'Système opérateur utilisée:';
$CIDRAM['lang']['label_php'] = 'Version PHP utilisée:';
$CIDRAM['lang']['label_sapi'] = 'SAPI utilisée:';
$CIDRAM['lang']['label_sysinfo'] = 'Informations sur le système:';
$CIDRAM['lang']['link_accounts'] = 'Comptes';
$CIDRAM['lang']['link_cidr_calc'] = 'Calculatrice CIDR';
$CIDRAM['lang']['link_config'] = 'Configuration';
$CIDRAM['lang']['link_documentation'] = 'Documentation';
$CIDRAM['lang']['link_file_manager'] = 'Gestionnaire de Fichiers';
$CIDRAM['lang']['link_home'] = 'Page d\'Accueil';
$CIDRAM['lang']['link_ip_test'] = 'IP Test';
$CIDRAM['lang']['link_ip_tracking'] = 'Surveillance IP';
$CIDRAM['lang']['link_logs'] = 'Fichiers Journaux';
$CIDRAM['lang']['link_updates'] = 'Mises à Jour';
$CIDRAM['lang']['logs_logfile_doesnt_exist'] = 'Le fichier journal sélectionné n\'existe pas!';
$CIDRAM['lang']['logs_no_logfiles_available'] = 'Aucun fichiers journaux disponibles.';
$CIDRAM['lang']['logs_no_logfile_selected'] = 'Aucun fichier journal sélectionné.';
$CIDRAM['lang']['max_login_attempts_exceeded'] = 'Nombre maximal de tentatives de connexion excédée; Accès refusé.';
$CIDRAM['lang']['response_accounts_already_exists'] = 'Un compte avec ce nom d\'utilisateur existe déjà!';
$CIDRAM['lang']['response_accounts_created'] = 'Compte créé avec succès!';
$CIDRAM['lang']['response_accounts_deleted'] = 'Compte supprimé avec succès!';
$CIDRAM['lang']['response_accounts_doesnt_exist'] = 'Ce compte n\'existe pas.';
$CIDRAM['lang']['response_accounts_password_updated'] = 'Mot de passe mis à jour avec succès!';
$CIDRAM['lang']['response_activated'] = 'Activé avec succès.';
$CIDRAM['lang']['response_activation_failed'] = 'Échec de l\'activation!';
$CIDRAM['lang']['response_component_successfully_installed'] = 'Composant installé avec succès.';
$CIDRAM['lang']['response_component_successfully_uninstalled'] = 'Composant désinstallé avec succès.';
$CIDRAM['lang']['response_component_successfully_updated'] = 'Composant mise à jour avec succès.';
$CIDRAM['lang']['response_component_uninstall_error'] = 'Une erreur est survenue lors de la désinstallation du composant.';
$CIDRAM['lang']['response_component_update_error'] = 'Une erreur est survenue lors de la mise à jour du composant.';
$CIDRAM['lang']['response_configuration_updated'] = 'Configuration mis à jour avec succès.';
$CIDRAM['lang']['response_deactivated'] = 'Désactivé avec succès.';
$CIDRAM['lang']['response_deactivation_failed'] = 'Échec de la désactivation!';
$CIDRAM['lang']['response_delete_error'] = 'Échec du suppriment!';
$CIDRAM['lang']['response_directory_deleted'] = 'Répertoire supprimé avec succès!';
$CIDRAM['lang']['response_directory_renamed'] = 'Répertoire renommé avec succès!';
$CIDRAM['lang']['response_error'] = 'Erreur';
$CIDRAM['lang']['response_file_deleted'] = 'Fichier supprimé avec succès!';
$CIDRAM['lang']['response_file_edited'] = 'Fichier modifié avec succès!';
$CIDRAM['lang']['response_file_renamed'] = 'Fichier renommé avec succès!';
$CIDRAM['lang']['response_file_uploaded'] = 'Fichier téléchargé avec succès!';
$CIDRAM['lang']['response_login_invalid_password'] = 'Erreur de connexion! Mot de passe incorrect!';
$CIDRAM['lang']['response_login_invalid_username'] = 'Erreur de connexion! Nom d\'utilisateur n\'existe pas!';
$CIDRAM['lang']['response_login_password_field_empty'] = 'Mot de passe entrée était vide!';
$CIDRAM['lang']['response_login_username_field_empty'] = 'Nom d\'utilisateur entrée était vide!';
$CIDRAM['lang']['response_no'] = 'Non';
$CIDRAM['lang']['response_rename_error'] = 'Échec du renomment!';
$CIDRAM['lang']['response_tracking_cleared'] = 'Surveillance annulée.';
$CIDRAM['lang']['response_updates_already_up_to_date'] = 'Déjà mise à jour.';
$CIDRAM['lang']['response_updates_not_installed'] = 'Composant pas installé!';
$CIDRAM['lang']['response_updates_not_installed_php'] = 'Composant pas installé (il nécessite PHP {V})!';
$CIDRAM['lang']['response_updates_outdated'] = 'Dépassé!';
$CIDRAM['lang']['response_updates_outdated_manually'] = 'Dépassé (s\'il vous plaît mettre à jour manuellement)!';
$CIDRAM['lang']['response_updates_outdated_php_version'] = 'Dépassé (il nécessite PHP {V})!';
$CIDRAM['lang']['response_updates_unable_to_determine'] = 'Incapable de déterminer.';
$CIDRAM['lang']['response_upload_error'] = 'Échec du téléchargement!';
$CIDRAM['lang']['response_yes'] = 'Oui';
$CIDRAM['lang']['state_complete_access'] = 'Accès complet';
$CIDRAM['lang']['state_component_is_active'] = 'Le composant est actif.';
$CIDRAM['lang']['state_component_is_inactive'] = 'Le composant est inactif.';
$CIDRAM['lang']['state_component_is_provisional'] = 'Le composant est provisoire.';
$CIDRAM['lang']['state_default_password'] = 'Attention: Utilisant le mot de passe défaut!';
$CIDRAM['lang']['state_logged_in'] = 'Connecté.';
$CIDRAM['lang']['state_logs_access_only'] = 'Accès aux fichiers journaux seulement';
$CIDRAM['lang']['state_password_not_valid'] = 'Attention: Ce compte n\'utilise un mot de passe valide!';
$CIDRAM['lang']['switch-hide-non-outdated-set-false'] = 'Ne masquer pas non dépassé';
$CIDRAM['lang']['switch-hide-non-outdated-set-true'] = 'Masquer non dépassé';
$CIDRAM['lang']['switch-hide-unused-set-false'] = 'Ne masquer pas inutilisé';
$CIDRAM['lang']['switch-hide-unused-set-true'] = 'Masquer inutilisé';
$CIDRAM['lang']['tip_accounts'] = 'Bonjour, {username}.<br />La page des comptes vous permet de contrôler qui peut accéder l\'accès frontal de CIDRAM.';
$CIDRAM['lang']['tip_cidr_calc'] = 'Bonjour, {username}.<br />Le calculatrice CIDR vous permet de calculer les CIDR auxquels une adresse IP appartient.';
$CIDRAM['lang']['tip_config'] = 'Bonjour, {username}.<br />La page de configuration vous permet de modifier la configuration pour CIDRAM à l\'accès frontal.';
$CIDRAM['lang']['tip_donate'] = 'CIDRAM est offert gratuitement, mais si vous voulez faire un don au projet, vous pouvez le faire en cliquant sur le bouton don.';
$CIDRAM['lang']['tip_enter_ips_here'] = 'Entrez les adresses IP ici.';
$CIDRAM['lang']['tip_enter_ip_here'] = 'Entrez ici l\'adresse IP.';
$CIDRAM['lang']['tip_file_manager'] = 'Bonjour, {username}.<br />Le gestionnaire de fichiers vous permet de supprimer, éditer et télécharger des fichiers. Utiliser avec précaution (vous pourriez casser votre installation avec ceci).';
$CIDRAM['lang']['tip_home'] = 'Bonjour, {username}.<br />C\'est la page d\'accueil de l\'accès frontal de CIDRAM. Sélectionnez un lien dans le menu de navigation à gauche pour continuer.';
$CIDRAM['lang']['tip_ip_test'] = 'Bonjour, {username}.<br />La page des tests IP vous permet de tester si les adresses IP sont bloquées par les signatures actuellement installées.';
$CIDRAM['lang']['tip_ip_tracking'] = 'Bonjour, {username}.<br />La page de surveillance IP vous permet de vérifier l\'état de surveillance des adresses IP, pour vérifier lesquels d\'entre eux ont été interdits, et d\'annuler la surveillance si vous voulez le faire.';
$CIDRAM['lang']['tip_login'] = 'Nom d\'utilisateur défaut: <span class="txtRd">admin</span> – Mot de passe défaut: <span class="txtRd">password</span>';
$CIDRAM['lang']['tip_logs'] = 'Bonjour, {username}.<br />Sélectionnez un fichier journal dans la liste ci-dessous pour afficher le contenu de ce fichier journal.';
$CIDRAM['lang']['tip_see_the_documentation'] = 'Voir la <a href="https://github.com/Maikuolan/CIDRAM/blob/master/_docs/readme.fr.md#SECTION6">documentation</a> pour information sur les différentes directives de la configuration et leurs objectifs.';
$CIDRAM['lang']['tip_updates'] = 'Bonjour, {username}.<br />La page des mises à jour vous permet d\'installer, de désinstaller et de mettre à jour les différentes composantes de CIDRAM (le paquet de base, signatures, fichiers de L10N, etc).';
$CIDRAM['lang']['title_accounts'] = 'CIDRAM – Comptes';
$CIDRAM['lang']['title_cidr_calc'] = 'CIDRAM – Calculatrice CIDR';
$CIDRAM['lang']['title_config'] = 'CIDRAM – Configuration';
$CIDRAM['lang']['title_file_manager'] = 'CIDRAM – Gestionnaire de Fichiers';
$CIDRAM['lang']['title_home'] = 'CIDRAM – Page d\'Accueil';
$CIDRAM['lang']['title_ip_test'] = 'CIDRAM – IP Test';
$CIDRAM['lang']['title_ip_tracking'] = 'CIDRAM – Surveillance IP';
$CIDRAM['lang']['title_login'] = 'CIDRAM – Connexion';
$CIDRAM['lang']['title_logs'] = 'CIDRAM – Fichiers Journaux';
$CIDRAM['lang']['title_updates'] = 'CIDRAM – Mises à Jour';

$CIDRAM['lang']['info_some_useful_links'] = 'Quelques liens utiles:<ul>
            <li><a href="https://github.com/Maikuolan/CIDRAM/issues">CIDRAM Issues @ GitHub</a> – Page de problèmes pour CIDRAM (soutien, assistance, etc).</li>
            <li><a href="http://www.spambotsecurity.com/forum/viewforum.php?f=61">CIDRAM @ Spambot Security</a> – Forum de discussion pour CIDRAM (soutien, assistance, etc).</li>
            <li><a href="https://wordpress.org/plugins/cidram/">CIDRAM @ WordPress.org</a> – WordPress plugin pour CIDRAM.</li>
            <li><a href="https://sourceforge.net/projects/cidram/">CIDRAM @ SourceForge</a> – Alternative download mirror for CIDRAM.</li>
            <li><a href="https://websectools.com/">WebSecTools.com</a> – Une collection de simples outils webmaster pour sécuriser les sites Web.</li>
            <li><a href="https://macmathan.info/blocklists">MacMathan.info Range Blocks</a> – Contient des blocs de gamme en option qui peuvent être ajoutés au CIDRAM pour bloquer les pays indésirables d\'accéder à votre site Web.</li>
            <li><a href="https://www.facebook.com/groups/2204685680/">International PHP Group @ Facebook</a> – Ressources d\'apprentissage PHP et discussion.</li>
            <li><a href="https://wwphp-fb.github.io/">International PHP Group @ GitHub</a> – Ressources d\'apprentissage PHP et discussion.</li>
            <li><a href="http://bgp.he.net/">Hurricane Electric BGP Toolkit</a> – Obtenir les CIDR des ASNs, Déterminer les relations entre ASNs, découvrez les ASN basés sur les noms de réseaux, etc.</li>
            <li><a href="https://www.stopforumspam.com/forum/">Forum @ Stop Forum Spam</a> – Forum de discussion utile sur l\'arrêt de spam forum.</li>
            <li><a href="https://www.stopforumspam.com/aggregate">IP Aggregator @ Stop Forum Spam</a> – Outil d\'agrégation utile pour IPs IPv4.</li>
            <li><a href="https://radar.qrator.net/">Radar by Qrator</a> – Outil utile pour vérifier la connectivité des ASN ainsi pour diverses autres informations sur les ASNs.</li>
            <li><a href="http://www.ipdeny.com/ipblocks/">IPdeny IP country blocks</a> – Un service fantastique et précis pour générer des signatures à l\'échelle du pays.</li>
            <li><a href="https://www.google.com/transparencyreport/safebrowsing/malware/">Google Malware Dashboard</a> – Affiche les rapports concernant les taux d\'infection par les logiciels malveillants pour les ASNs.</li>
            <li><a href="https://www.spamhaus.org/statistics/botnet-asn/">The Spamhaus Project</a> – Affiche les rapports concernant les taux d\'infection par botnet pour les ASN.</li>
            <li><a href="http://www.abuseat.org/asn.html">Abuseat.org\'s Composite Blocking List</a> – Affiche les rapports concernant les taux d\'infection par botnet pour les ASN.</li>
            <li><a href="https://abuseipdb.com/">AbuseIPDB</a> – Maintient une base de données des IPs abusives connues; Fournit une API pour vérifier et signaler les IPs.</li>
            <li><a href="https://www.megarbl.net/index.php">MegaRBL.net</a> – Maintient les listes des spammeurs connus; Utile pour vérifier les activités de spam des IPs/ASNs.</li>
        </ul>';
