<?php
/**
 * This file is a part of the CIDRAM package.
 * Homepage: https://cidram.github.io/
 *
 * CIDRAM COPYRIGHT 2016 and beyond by Caleb Mazalevskis (Maikuolan).
 *
 * License: GNU/GPLv2
 * @see LICENSE.txt
 *
 * This file: French language data for CLI last modified: 2018.01.20).
 */

/** Prevents execution from outside of CIDRAM. */
if (!defined('CIDRAM')) {
    die('[CIDRAM] This should not be accessed directly.');
}

$CIDRAM['lang']['CLI_H'] = "
 Aide pour le mode CLI de CIDRAM.

 Usage :
 php.exe /cidram/loader.php -Flag (Input)

 Flags : -h Afficher cette information d'aide.
         -c Vérifiez si une adresse IP est bloquée par les fichiers de CIDRAM.
         -g Générer des CIDRs à partir d'une adresse IP.
         -v Validez un fichier de signature.
         -f Corrigez un fichier de signature.

 Exemples :
 php.exe /cidram/loader.php -c 192.168.0.0
 php.exe /cidram/loader.php -c 2001:db8::
 php.exe /cidram/loader.php -g 1.2.3.4
 php.exe /cidram/loader.php -g ::1
 php.exe /cidram/loader.php -f signatures.dat
 php.exe /cidram/loader.php -v signatures.dat

";

$CIDRAM['lang']['CLI_Bad_IP'] = ' L\'adresse IP spécifiée, « {IP} », n\'est pas une adresse IPv4 ou IPv6 valide !';
$CIDRAM['lang']['CLI_IP_Blocked'] = ' L\'adresse IP spécifiée, « {IP} », *EST* bloqué par un ou plusieurs du signatures de CIDRAM.';
$CIDRAM['lang']['CLI_IP_Not_Blocked'] = ' L\'adresse IP spécifiée, « {IP} », n\'est *PAS* bloqué par les signatures de CIDRAM.';

$CIDRAM['lang']['CLI_F_Finished'] = 'Signature fixateur a terminé, avec %s changements effectués sur %s opérations (%s).';
$CIDRAM['lang']['CLI_F_Started'] = 'Signature fixateur a commencé (%s).';
$CIDRAM['lang']['CLI_VF_Empty'] = 'Fichier de signatures spécifié est vide ou il n\'existe pas.';
$CIDRAM['lang']['CLI_VF_Level_0'] = 'Notice';
$CIDRAM['lang']['CLI_VF_Level_1'] = 'Avertissement';
$CIDRAM['lang']['CLI_VF_Level_2'] = 'Erreur';
$CIDRAM['lang']['CLI_VF_Level_3'] = 'Erreur Fatale';

$CIDRAM['lang']['CLI_V_CRLF'] = 'CR/CRLF détecté dans le fichier de signatures ; Ceux-ci sont permissible et ne causera pas de problèmes, mais LF est préférable.';
$CIDRAM['lang']['CLI_V_Finished'] = 'Validation de signatures a terminé (%s). Si aucun avertissement et aucun erreurs apparaissent, votre fichier de signatures est *probablement* bien. :-)';
$CIDRAM['lang']['CLI_V_LineByLine'] = 'Validation ligne par ligne a commencé.';
$CIDRAM['lang']['CLI_V_Started'] = 'Validation de signatures a commencé (%s).';
$CIDRAM['lang']['CLI_V_Terminal_LF'] = 'Fichiers de signatures devrait terminer avec un saut de ligne LF.';

$CIDRAM['lang']['CLI_VL_CC'] = 'L%s : Caractères de contrôle détectés ; Cela pourrait indiquer la corruption et devrait être étudiée.';
$CIDRAM['lang']['CLI_VL_Duplicated'] = 'L%s : Signature « %s » est dupliqué (%s comptes) !';
$CIDRAM['lang']['CLI_VL_Expiry'] = 'L%s : Tag d\'expiration ne contient pas un ISO 8601 date/heure !';
$CIDRAM['lang']['CLI_VL_Invalid'] = 'L%s : « %s » n\'est *PAS* une adresse IPv4 ou IPv6 valide !';
$CIDRAM['lang']['CLI_VL_L120'] = 'L%s : La longueur de la ligne est supérieure à 120 octets ; La longueur de la ligne devrait être limitée à 120 octets pour lisibilité optimale.';
$CIDRAM['lang']['CLI_VL_Mergeable'] = 'L%s et L%s sont identiques, et ainsi, fusionnables.';
$CIDRAM['lang']['CLI_VL_Missing'] = 'L%s : [Function] absent ; Signature semble être incomplètes.';
$CIDRAM['lang']['CLI_VL_Nontriggerable'] = 'L%s : « %s » est non déclenchable ! Sa base ne correspond pas au début de sa gamme ! Essayez de remplacer avec « %s ».';
$CIDRAM['lang']['CLI_VL_Nontriggerable_Range'] = 'L%s : « %s » est non déclenchable ! « %s » n\'est pas une gamme valide !';
$CIDRAM['lang']['CLI_VL_Origin'] = 'L%s : Étiquette d\'origine ne contient pas un code ISO 3166-1 Alpha-2 valide !';
$CIDRAM['lang']['CLI_VL_Subordinate'] = 'L%s : « %s » est subordonnée à l\'existant signature « %s ».';
$CIDRAM['lang']['CLI_VL_Superset'] = 'L%s : « %s » est un superset du déjà existant signature « %s ».';
$CIDRAM['lang']['CLI_VL_Syntax'] = 'L%s : Non syntaxiquement précis.';
$CIDRAM['lang']['CLI_VL_Tabs'] = 'L%s : Onglets détectés ; Les espaces sont préférables aux tabs/onglets pour lisibilité optimale.';
$CIDRAM['lang']['CLI_VL_Tags'] = 'L%s : Tag de section est supérieure à 20 octets ; Les tags de section doivent être claires et concises.';
$CIDRAM['lang']['CLI_VL_Unrecognised'] = 'L%s : Inconnu [Function] ; Signature pourrait être rompu.';
$CIDRAM['lang']['CLI_VL_Whitespace'] = 'L%s : Excès d\'espace blanc terminant sur cette ligne détecté.';
$CIDRAM['lang']['CLI_VL_YAML'] = 'L%s : Certaines données comme YAML détecté, mais ne pouvait pas traiter.';
