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
 * This file: Dutch language data (last modified: 2016.04.11).
 */

/** Prevents execution from outside of CIDRAM. */
if (!defined('CIDRAM')) {
    die('[CIDRAM] This should not be accessed directly.');
}

$CIDRAM['lang']['click_here'] = 'klik hier';
$CIDRAM['lang']['denied'] = 'Toegang Geweigerd!';
$CIDRAM['lang']['Error_WriteCache'] = 'Kan niet schrijven naar de cache! Controleer uw CHMOD permissies!';
$CIDRAM['lang']['field_datetime'] = 'Datum/Tijd: ';
$CIDRAM['lang']['field_id'] = 'ID: ';
$CIDRAM['lang']['field_ipaddr'] = 'IP-Adres: ';
$CIDRAM['lang']['field_query'] = 'Query: ';
$CIDRAM['lang']['field_referrer'] = 'Verwijzer: ';
$CIDRAM['lang']['field_rURI'] = 'Gereconstrueerde URI: ';
$CIDRAM['lang']['field_scriptversion'] = 'Script Versie: ';
$CIDRAM['lang']['field_sigcount'] = 'Handtekeningen Tellen: ';
$CIDRAM['lang']['field_sigref'] = 'Handtekeningen Referentie: ';
$CIDRAM['lang']['field_ua'] = 'User Agent: ';
$CIDRAM['lang']['field_whyreason'] = 'Waarom Geblokkeerde: ';
$CIDRAM['lang']['generated_by'] = 'Gegenereerd door';
$CIDRAM['lang']['preamble'] = '-- Einde van preambule. Voeg uw vragen of opmerkingen na dit lijn. --';
$CIDRAM['lang']['ReasonMessage_BadIP'] = 'Uw toegang tot dit pagina is geweigerd omdat u geprobeerd om toegang tot dit pagina met behulp van een ongeldig IP-adres.';
$CIDRAM['lang']['ReasonMessage_Bogon'] = 'Uw toegang tot dit pagina is geweigerd omdat uw IP-adres wordt herkend als bogon adres, en het verbinden van bogons naar dit website is niet toegestaan door de eigenaar van dit website.';
$CIDRAM['lang']['ReasonMessage_Cloud'] = 'Uw toegang tot dit pagina is geweigerd omdat uw IP-adres wordt herkend als behorend tot een cloud service, en het verbinden van cloud service naar dit website is niet toegestaan door de eigenaar van dit website.';
$CIDRAM['lang']['ReasonMessage_Generic'] = 'Uw toegang tot dit pagina is geweigerd omdat uw IP-adres behoort tot een netwerk dat is op een zwarte lijst gebruikt door dit website.';
$CIDRAM['lang']['ReasonMessage_Spam'] = 'Uw toegang tot dit pagina is geweigerd omdat uw IP-adres behoort tot een netwerk beschouwd als een hoog risico op spam.';
$CIDRAM['lang']['Short_BadIP'] = 'Ongeldig IP!';
$CIDRAM['lang']['Short_Bogon'] = 'Bogon IP';
$CIDRAM['lang']['Short_Cloud'] = 'Cloud Service';
$CIDRAM['lang']['Short_Generic'] = 'Algemeen';
$CIDRAM['lang']['Short_Spam'] = 'Spam risico';
$CIDRAM['lang']['Support_Email'] = 'Als je denkt dat dit een fout, of om hulp te zoeken, {ClickHereLink} om een email support ticket te sturen naar de webmaster van dit site (alsjeblieft niet wijzigen de preambule of de onderwerpregel van de email).';

$CIDRAM['lang']['CLI_H'] = "
 CIDRAM CLI-modus hulp.

 Gebruik:
 /pad/naar/php/php.exe /pad/naar/cidram/loader.php -Vlag (Invoer)

 Vlaggen:   -h  Geeft dit help informatie.
            -c  Controleer of een IP-adres is geblokkeerd door de CIDRAM
                handtekening bestanden.
            -g  Genereer CIDRs van een IP-adres.

 Invoer: Kan ieder geldig IP-adres zijn.

 Voorbeelden:
        -c  192.168.0.0/16
        -c  127.0.0.1/32
        -c  2001:db8::/32
        -c  2002::1/128

";

$CIDRAM['lang']['CLI_Bad_IP'] = ' Het opgegeven IP-adres, "{IP}", is geen geldig IPv4 of IPv6 IP-adres!';
$CIDRAM['lang']['CLI_IP_Blocked'] = ' Het opgegeven IP-adres, "{IP}", *IS* geblokkeerd door een of meer van de CIDRAM handtekening bestanden.';
$CIDRAM['lang']['CLI_IP_Not_Blocked'] = ' Het opgegeven IP-adres, "{IP}", is *NIET* geblokkeerd door een van de CIDRAM handtekening bestanden.';

$CIDRAM['lang']['CLI_F_Finished'] = 'Signature fixer has finished, with %s changes made over %s operations (%s).';
$CIDRAM['lang']['CLI_F_Started'] = 'Signature fixer has started (%s).';
$CIDRAM['lang']['CLI_VF_Empty'] = 'Specified signature file is empty or doesn\'t exist.';
$CIDRAM['lang']['CLI_VF_Level_0'] = 'Kennisgeving';
$CIDRAM['lang']['CLI_VF_Level_1'] = 'Waarschuwing';
$CIDRAM['lang']['CLI_VF_Level_2'] = 'Fout';
$CIDRAM['lang']['CLI_VF_Level_3'] = 'Fatale Fout';

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
