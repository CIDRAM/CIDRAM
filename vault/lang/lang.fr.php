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
 * This file: French language data (last modified: 2016.04.11).
 */

/** Prevents execution from outside of CIDRAM. */
if (!defined('CIDRAM')) {
    die('[CIDRAM] This should not be accessed directly.');
}

$CIDRAM['lang']['click_here'] = 'cliquez ici';
$CIDRAM['lang']['denied'] = 'Accès Refusé!';
$CIDRAM['lang']['Error_WriteCache'] = 'Ne peux pas d\'écrire dans le cache! S\'il vous plaît vérifier votre permissions CHMOD!';
$CIDRAM['lang']['field_datetime'] = 'Date/Heure: ';
$CIDRAM['lang']['field_id'] = 'ID: ';
$CIDRAM['lang']['field_ipaddr'] = 'IP Adresse: ';
$CIDRAM['lang']['field_query'] = 'Query: ';
$CIDRAM['lang']['field_referrer'] = 'Referrer: ';
$CIDRAM['lang']['field_rURI'] = 'Reconstruite URI: ';
$CIDRAM['lang']['field_scriptversion'] = 'La version du script: ';
$CIDRAM['lang']['field_sigcount'] = 'Signatures Compte: ';
$CIDRAM['lang']['field_sigref'] = 'Signatures Référence: ';
$CIDRAM['lang']['field_ua'] = 'Agent Utilisateur: ';
$CIDRAM['lang']['field_whyreason'] = 'Raison Bloquée: ';
$CIDRAM['lang']['generated_by'] = 'Généré par';
$CIDRAM['lang']['preamble'] = '-- Fin du préambule. Ajouter vos questions ou commentaires après cette ligne. --';
$CIDRAM['lang']['ReasonMessage_BadIP'] = 'Votre accès à cette page a été refusée parce que vous avez tenté d\'accéder à cette page en utilisant un invalide IP adresse.';
$CIDRAM['lang']['ReasonMessage_Bogon'] = 'Votre accès à cette page a été refusée parce que votre IP adresse est reconnue comme un bogon adresse, et la connexion de bogons à cette site est pas autorisée par le propriétaire du site.';
$CIDRAM['lang']['ReasonMessage_Cloud'] = 'Votre accès à cette page a été refusée parce que votre IP adresse est reconnue comme appartenant à un service de cloud computing, et la connexion de services de cloud computing à cette site est pas autorisée par le propriétaire du site.';
$CIDRAM['lang']['ReasonMessage_Generic'] = 'Votre accès à cette page a été refusée parce que votre IP adresse appartient à un réseau figurant sur une liste noire utilisée par ce site.';
$CIDRAM['lang']['ReasonMessage_Spam'] = 'Votre accès à cette page a été refusée parce que votre IP adresse appartient à un réseau considéré comme à haut risque pour le spam.';
$CIDRAM['lang']['Short_BadIP'] = 'Invalide IP!';
$CIDRAM['lang']['Short_Bogon'] = 'Bogon IP';
$CIDRAM['lang']['Short_Cloud'] = 'Service de cloud';
$CIDRAM['lang']['Short_Generic'] = 'Générique';
$CIDRAM['lang']['Short_Spam'] = 'Spam risque';
$CIDRAM['lang']['Support_Email'] = 'Si vous croyez que cela est dans l\'erreur, ou pour demander de l\'aide, {ClickHereLink} pour envoyer un e-mail ticket de support au webmaster de ce site (s\'il vous plaît, ne pas modifier le préambule ou la ligne d\'objet de l\'e-mail).';

$CIDRAM['lang']['CLI_H'] = "
 Aide pour le mode CLI de CIDRAM.

 Usage:
 /répertoire/pour/php/php.exe /répertoire/pour/cidram/loader.php -Flag (Input)

 Flags: -h  Afficher cette information d'aide.
        -c  Vérifiez si une adresse IP est bloquée par les fichiers de CIDRAM.
        -g  Générer des CIDRs à partir d'une adresse IP.

 Input: Peut être tout adresse IPv4 ou IPv6 valide.

 Exemples:
        -c  192.168.0.0/16
        -c  127.0.0.1/32
        -c  2001:db8::/32
        -c  2002::1/128

";

$CIDRAM['lang']['CLI_Bad_IP'] = ' L\'adresse IP spécifiée, "{IP}", est pas une adresse IPv4 ou IPv6 valide!';
$CIDRAM['lang']['CLI_IP_Blocked'] = ' L\'adresse IP spécifiée, "{IP}", *EST* bloqué par un ou plusieurs du signatures de CIDRAM.';
$CIDRAM['lang']['CLI_IP_Not_Blocked'] = ' L\'adresse IP spécifiée, "{IP}", n\'est *PAS* bloqué par les signatures de CIDRAM.';

$CIDRAM['lang']['CLI_F_Finished'] = 'Signature fixer has finished, with %s changes made over %s operations (%s).';
$CIDRAM['lang']['CLI_F_Started'] = 'Signature fixer has started (%s).';
$CIDRAM['lang']['CLI_VF_Empty'] = 'Specified signature file is empty or doesn\'t exist.';
$CIDRAM['lang']['CLI_VF_Level_0'] = 'Notice';
$CIDRAM['lang']['CLI_VF_Level_1'] = 'Avertissement';
$CIDRAM['lang']['CLI_VF_Level_2'] = 'Erreur';
$CIDRAM['lang']['CLI_VF_Level_3'] = 'Erreur Fatale';

$CIDRAM['lang']['CLI_V_CRLF'] = 'Detected CR/CRLF in signature file; These are permissible and won\'t cause problems, but LF is preferable.';
$CIDRAM['lang']['CLI_V_Finished'] = 'Signature validator has finished (%s). If no warnings or errors have appeared, your signature file is *probably* okay. :-)';
$CIDRAM['lang']['CLI_V_LineByLine'] = 'Line-by-line validation has started.';
$CIDRAM['lang']['CLI_V_Started'] = 'Signature validator has started (%s).';
$CIDRAM['lang']['CLI_V_Terminal_LF'] = 'Signature files should terminate with an LF linebreak.';

$CIDRAM['lang']['CLI_VL_CC'] = 'L%s: Control characters detected; This could indicate corruption and should be investigated.';
$CIDRAM['lang']['CLI_VL_Expiry'] = 'L%s: Expiry tag doesn\'t contain a valid ISO 8601 date/time!';
$CIDRAM['lang']['CLI_VL_L120'] = 'L%s: Line length is greater than 120 bytes; Line length should be limited to 120 bytes for optimal readability.';
$CIDRAM['lang']['CLI_VL_Mergeable'] = 'L%s and L%s are identical, and thus, mergeable.';
$CIDRAM['lang']['CLI_VL_Syntax'] = 'L%s: Not syntactically precise.';
$CIDRAM['lang']['CLI_VL_Tabs'] = 'L%s: Tabs detected; Spaces are preferred over tabs for optimal readability.';
$CIDRAM['lang']['CLI_VL_Tags'] = 'L%s: Section tag is greater than 20 bytes; Section tags should be clear and concise.';
$CIDRAM['lang']['CLI_VL_Whitespace'] = 'L%s: Excess trailing whitespace detected on this line.';
