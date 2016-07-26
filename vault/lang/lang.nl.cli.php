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
 * This file: Dutch language data for CLI (last modified: 2016.07.26).
 */

/** Prevents execution from outside of CIDRAM. */
if (!defined('CIDRAM')) {
    die('[CIDRAM] This should not be accessed directly.');
}

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

$CIDRAM['lang']['CLI_F_Finished'] = 'Handtekening fixeer heeft voltooid, met %s veranderingen in %s operaties (%s).';
$CIDRAM['lang']['CLI_F_Started'] = 'Handtekening fixeer heeft begonnen (%s).';
$CIDRAM['lang']['CLI_VF_Empty'] = 'Het opgegeven handtekening bestand is leeg of niet bestaat.';
$CIDRAM['lang']['CLI_VF_Level_0'] = 'Kennisgeving';
$CIDRAM['lang']['CLI_VF_Level_1'] = 'Waarschuwing';
$CIDRAM['lang']['CLI_VF_Level_2'] = 'Fout';
$CIDRAM['lang']['CLI_VF_Level_3'] = 'Fatale Fout';

$CIDRAM['lang']['CLI_V_CRLF'] = 'Gedetecteerd CR/CRLF in de handtekening bestand; Deze zijn toegestaan en zal geen problemen veroorzaken, maar de LF heeft de voorkeur.';
$CIDRAM['lang']['CLI_V_Finished'] = 'Handtekening validatie heeft voltooid (%s). Als er geen waarschuwingen of fouten, uw handtekening bestand is *waarschijnlijk* goed. :-)';
$CIDRAM['lang']['CLI_V_LineByLine'] = 'Lijn-bij-lijn validatie heeft begonnen.';
$CIDRAM['lang']['CLI_V_Started'] = 'Handtekening validatie heeft begonnen (%s).';
$CIDRAM['lang']['CLI_V_Terminal_LF'] = 'Handtekening bestanden moet eindigen met een LF regeleinde.';

$CIDRAM['lang']['CLI_VL_CC'] = 'L%s: Controle karakters gedetecteerd; Dit kan duiden corruptie en moet worden onderzocht.';
$CIDRAM['lang']['CLI_VL_Duplicated'] = 'L%s: Handtekening "%s" is gedupliceerd (%s tellingen)!';
$CIDRAM['lang']['CLI_VL_Expiry'] = 'L%s: Vervaltijd label bevat geen geldig ISO 8601 datum/tijd bevatten!';
$CIDRAM['lang']['CLI_VL_Invalid'] = 'L%s: "%s" is *GEEN* geldig IPv4 of IPv6 adres!';
$CIDRAM['lang']['CLI_VL_L120'] = 'L%s: Lijnlengte is groter dan 120 bytes; Lijnlengte moet worden beperkt tot 120 bytes voor optimale leesbaarheid.';
$CIDRAM['lang']['CLI_VL_Mergeable'] = 'L%s en L%s zijn identiek, en daarom, kan worden samengevoegd.';
$CIDRAM['lang']['CLI_VL_Missing'] = 'L%s: Ontbrekende [Function]; Handtekening lijkt onvolledig.';
$CIDRAM['lang']['CLI_VL_Nontriggerable'] = 'L%s: "%s" kan niet worden geactiveerd! Haar basis niet overeen met het begin van de series! Probeer te vervangen het door "%s".';
$CIDRAM['lang']['CLI_VL_Nontriggerable_Range'] = 'L%s: "%s" kan niet worden geactiveerd! "%s" is niet een geldig series!';
$CIDRAM['lang']['CLI_VL_Subordinate'] = 'L%s: "%s" is ondergeschikt aan de reeds bestaande "%s" handtekening.';
$CIDRAM['lang']['CLI_VL_Superset'] = 'L%s: "%s" is een superset aan de reeds bestaande "%s" handtekening.';
$CIDRAM['lang']['CLI_VL_Syntax'] = 'L%s: Niet syntactisch precies.';
$CIDRAM['lang']['CLI_VL_Tabs'] = 'L%s: Tabs gedetecteerd; Spaces voorkeur boven tabs voor optimale leesbaarheid.';
$CIDRAM['lang']['CLI_VL_Tags'] = 'L%s: Sectie label is groter dan 20 bytes; Sectie labels moeten duidelijk en beknopt zijn.';
$CIDRAM['lang']['CLI_VL_Unrecognised'] = 'L%s: [Function] is niet herkend; Handtekening misschien gebroken.';
$CIDRAM['lang']['CLI_VL_Whitespace'] = 'L%s: Overmaat afsluitende witruimte gedetecteerd op dit lijn.';
$CIDRAM['lang']['CLI_VL_YAML'] = 'L%s: YAML-achtige data gedetecteerd, maar kon het niet verwerken.';
