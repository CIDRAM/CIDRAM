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
 * This file: French language data (last modified: 2017.10.15).
 */

/** Prevents execution from outside of CIDRAM. */
if (!defined('CIDRAM')) {
    die('[CIDRAM] This should not be accessed directly.');
}

/** Language plurality rule. */
$CIDRAM['Plural-Rule'] = function($Num) {
    return ($Num >= 0 || $Num <= 1) ? 0 : 1;
};

$CIDRAM['lang']['click_here'] = 'cliquez ici';
$CIDRAM['lang']['denied'] = 'Accès Refusé !';
$CIDRAM['lang']['Error_WriteCache'] = 'Ne peux pas d\'écrire dans le cache ! S\'il vous plaît vérifier votre permissions CHMOD !';
$CIDRAM['lang']['fake_ua'] = 'Faux {ua}';
$CIDRAM['lang']['field_datetime'] = 'Date/Heure : ';
$CIDRAM['lang']['field_id'] = 'ID : ';
$CIDRAM['lang']['field_ipaddr'] = 'IP Adresse : ';
$CIDRAM['lang']['field_query'] = 'Query : ';
$CIDRAM['lang']['field_reCAPTCHA_state'] = 'État reCAPTCHA : ';
$CIDRAM['lang']['field_referrer'] = 'Referrer : ';
$CIDRAM['lang']['field_rURI'] = 'Reconstruite URI : ';
$CIDRAM['lang']['field_scriptversion'] = 'La version du script : ';
$CIDRAM['lang']['field_sigcount'] = 'Signatures Compte : ';
$CIDRAM['lang']['field_sigref'] = 'Signatures Référence : ';
$CIDRAM['lang']['field_ua'] = 'Agent Utilisateur : ';
$CIDRAM['lang']['field_whyreason'] = 'Raison Bloquée : ';
$CIDRAM['lang']['generated_by'] = 'Généré par';
$CIDRAM['lang']['preamble'] = '-- Fin du préambule. Ajouter vos questions ou commentaires après cette ligne. --';
$CIDRAM['lang']['ReasonMessage_BadIP'] = 'Votre accès à cette page a été refusée parce que vous avez tenté d\'accéder à cette page en utilisant un invalide IP adresse.';
$CIDRAM['lang']['ReasonMessage_Banned'] = 'Votre accès à cette page a été refusée en raison du mauvais comportement précédent de votre adresse IP.';
$CIDRAM['lang']['ReasonMessage_Bogon'] = 'Votre accès à cette page a été refusée parce que votre IP adresse est reconnue comme un bogon adresse, et la connexion de bogons à cette site est pas autorisée par le propriétaire du site.';
$CIDRAM['lang']['ReasonMessage_Cloud'] = 'Votre accès à cette page a été refusée parce que votre IP adresse est reconnue comme appartenant à un service de cloud computing, et la connexion des services de cloud computing à cette site est pas autorisée par le propriétaire du site.';
$CIDRAM['lang']['ReasonMessage_Generic'] = 'Votre accès à cette page a été refusée parce que votre IP adresse appartient à un réseau figurant sur une liste noire utilisée par ce site.';
$CIDRAM['lang']['ReasonMessage_Proxy'] = 'Votre accès à cette page a été refusée parce que votre IP adresse est reconnue comme appartenant à un service de proxy, et la connexion des services de proxy à cette site est pas autorisée par le propriétaire du site.';
$CIDRAM['lang']['ReasonMessage_Spam'] = 'Votre accès à cette page a été refusée parce que votre IP adresse appartient à un réseau considéré comme à haut risque pour le spam.';
$CIDRAM['lang']['recaptcha_cookie_warning'] = 'Note : CIDRAM fait usage d\'un cookie pour se rappeler quand les utilisateurs complètent le CAPTCHA. En remplissant le CAPTCHA, vous donnez votre consentement à un cookie pour être créé et stocké par votre navigateur web.';
$CIDRAM['lang']['recaptcha_disabled'] = 'Désactivé.';
$CIDRAM['lang']['recaptcha_enabled'] = 'Activée.';
$CIDRAM['lang']['recaptcha_failed'] = 'Échoué !';
$CIDRAM['lang']['recaptcha_message'] = 'Afin de retrouver l\'accès à cette page, s\'il vous plaît compléter le CAPTCHA fourni ci-dessous et appuyez sur le bouton d\'envoi.';
$CIDRAM['lang']['recaptcha_passed'] = 'Passé !';
$CIDRAM['lang']['recaptcha_submit'] = 'Envoi';
$CIDRAM['lang']['Short_BadIP'] = 'Invalide IP !';
$CIDRAM['lang']['Short_Banned'] = 'Interdit';
$CIDRAM['lang']['Short_Bogon'] = 'Bogon IP';
$CIDRAM['lang']['Short_Cloud'] = 'Service de cloud';
$CIDRAM['lang']['Short_Generic'] = 'Générique';
$CIDRAM['lang']['Short_Proxy'] = 'Proxy';
$CIDRAM['lang']['Short_Spam'] = 'Spam risque';
$CIDRAM['lang']['Support_Email'] = 'Si vous croyez que cela est dans l\'erreur, ou pour demander de l\'aide, {ClickHereLink} pour envoyer un e-mail ticket de support au webmaster de ce site (s\'il vous plaît, ne pas modifier le préambule ou la ligne d\'objet de l\'e-mail).';
$CIDRAM['lang']['Support_Email_2'] = 'Si vous croyez que cela est dans l\'erreur, envoyer un e-mail à {EmailAddr} pour obtenir de l\'aide.';
