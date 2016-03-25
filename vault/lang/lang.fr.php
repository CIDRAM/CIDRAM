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
 * This file: French language data (last modified: 2016.03.25).
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
$CIDRAM['lang']['field_scriptversion'] = 'La version du script: ';
$CIDRAM['lang']['field_sigcount'] = 'Signatures Compte: ';
$CIDRAM['lang']['field_sigref'] = 'Signatures Référence: ';
$CIDRAM['lang']['field_ua'] = 'Agent Utilisateur: ';
$CIDRAM['lang']['generated_by'] = 'Généré par';
$CIDRAM['lang']['preamble'] = '-- Fin du préambule. Ajouter vos questions ou commentaires après cette ligne. --';
$CIDRAM['lang']['ReasonMessage_BadIP'] = 'Votre accès à cette page a été refusée parce que vous avez tenté d\'accéder à cette page en utilisant un invalide IP adresse.';
$CIDRAM['lang']['ReasonMessage_Bogon'] = 'Votre accès à cette page a été refusée parce que votre IP adresse est reconnue comme un bogon adresse, et la connexion de bogons à cette site est pas autorisée par le propriétaire du site.';
$CIDRAM['lang']['ReasonMessage_Cloud'] = 'Votre accès à cette page a été refusée parce que votre IP adresse est reconnue comme appartenant à un service de cloud computing, et la connexion de services de cloud computing à cette site est pas autorisée par le propriétaire du site.';
$CIDRAM['lang']['ReasonMessage_Generic'] = 'Votre accès à cette page a été refusée parce que votre IP adresse appartient à un réseau figurant sur une liste noire utilisée par ce site.';
$CIDRAM['lang']['ReasonMessage_Spam'] = 'Votre accès à cette page a été refusée parce que votre IP adresse appartient à un réseau considéré comme à haut risque pour le spam.';
$CIDRAM['lang']['Short_BadIP'] = 'Invalide IP!';
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
$CIDRAM['lang']['CLI_IP_Not_Blocked'] = ' L\'adresse IP spécifiée, "{IP}", n\'est *PAS* bloqué par un ou plusieurs du signatures de CIDRAM.';
