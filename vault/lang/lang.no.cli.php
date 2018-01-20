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
 * This file: Norwegian language data for CLI (last modified: 2018.01.20).
 */

/** Prevents execution from outside of CIDRAM. */
if (!defined('CIDRAM')) {
    die('[CIDRAM] This should not be accessed directly.');
}

$CIDRAM['lang']['CLI_H'] = "
 CIDRAM CLI-modus hjelp.

 Bruk:
 php.exe /cidram/loader.php -Flagg (Input)

 Flagg: -h Vis denne hjelpsinformasjonen.
        -c Sjekk om en IP-adresse er blokkert av CIDRAM signaturfiler.
        -g Generer CIDRer fra en IP-adresse.
        -v Valider en signaturfil.
        -f Reparer en signaturfil

 Eksempler:
 php.exe /cidram/loader.php -c 192.168.0.0
 php.exe /cidram/loader.php -c 2001:db8::
 php.exe /cidram/loader.php -g 1.2.3.4
 php.exe /cidram/loader.php -g ::1
 php.exe /cidram/loader.php -f signatures.dat
 php.exe /cidram/loader.php -v signatures.dat

";

$CIDRAM['lang']['CLI_Bad_IP'] = ' Den spesifisert IP-adressen, "{IP}", er ikke en gyldig IPv4 eller IPv6 IP-adresse!';
$CIDRAM['lang']['CLI_IP_Blocked'] = ' Den spesifisert IP-adressen, "{IP}", *ER* blokkert av en eller flere av CIDRAM signaturfiler.';
$CIDRAM['lang']['CLI_IP_Not_Blocked'] = ' Den spesifisert IP-adressen, "{IP}", er *IKKE* blokkert av noen av CIDRAM signaturfiler.';

$CIDRAM['lang']['CLI_F_Finished'] = 'Signaturfikseren er ferdig, med %s endringer over %s operasjoner (%s).';
$CIDRAM['lang']['CLI_F_Started'] = 'Signaturfikseren har startet (%s).';
$CIDRAM['lang']['CLI_VF_Empty'] = 'Spesifisert signaturfil er tom eller eksisterer ikke.';
$CIDRAM['lang']['CLI_VF_Level_0'] = 'Notis';
$CIDRAM['lang']['CLI_VF_Level_1'] = 'Advarsel';
$CIDRAM['lang']['CLI_VF_Level_2'] = 'Feil';
$CIDRAM['lang']['CLI_VF_Level_3'] = 'Fatal Feil';

$CIDRAM['lang']['CLI_V_CRLF'] = 'Oppdaget CR/CRLF i signaturfil; Disse er tillatelige og vil ikke forårsake problemer, men LF er å foretrekke.';
$CIDRAM['lang']['CLI_V_Finished'] = 'Signaturvalideringen er ferdig (%s). Hvis ingen advarsler eller feil har oppstått, er signaturfilen din *sannsynligvis* greit. :-)';
$CIDRAM['lang']['CLI_V_LineByLine'] = 'Linje-for-linje validering har startet.';
$CIDRAM['lang']['CLI_V_Started'] = 'Signaturvalideringen har startet (%s).';
$CIDRAM['lang']['CLI_V_Terminal_LF'] = 'Signaturfiler bør avslutte med en LF linjeskift.';

$CIDRAM['lang']['CLI_VL_CC'] = 'L%s: Kontrollerte tegn registrert; Dette kan tyde på korrupsjon og bør undersøkes.';
$CIDRAM['lang']['CLI_VL_Duplicated'] = 'L%s: Signatur "%s" er duplisert (%s teller)!';
$CIDRAM['lang']['CLI_VL_Expiry'] = 'L%s: Utløpslabel inneholder ikke en gyldig ISO 8601-dato/tid!';
$CIDRAM['lang']['CLI_VL_Invalid'] = 'L%s: "%s" er ikke en gyldig IPv4 eller IPv6 IP-adresse!';
$CIDRAM['lang']['CLI_VL_L120'] = 'L%s: Linjelengden er større enn 120 byte; Linjelengden bør begrenses til 120 byte for optimal lesbarhet.';
$CIDRAM['lang']['CLI_VL_Mergeable'] = 'L%s og L%s er identiske, og kan slås sammen.';
$CIDRAM['lang']['CLI_VL_Missing'] = 'L%s: Mangler [Function]; Signaturen ser ut til å være ufullstendig.';
$CIDRAM['lang']['CLI_VL_Nontriggerable'] = 'L%s: "%s" vil aldri matche! Basen sin stemmer ikke overens med begynnelsen av sitt utvalg! Prøv å erstatte den med "%s".';
$CIDRAM['lang']['CLI_VL_Nontriggerable_Range'] = 'L%s: "%s" vil aldri matche! "%s" er ikke et gyldig område!';
$CIDRAM['lang']['CLI_VL_Origin'] = 'L%s: Opprinnelseslabel inneholder ikke en gyldig ISO 3166-1 Alpha-2-kode!';
$CIDRAM['lang']['CLI_VL_Subordinate'] = 'L%s: "%s" er underordnet den allerede eksisterende "%s" signaturen.';
$CIDRAM['lang']['CLI_VL_Superset'] = 'L%s: "%s" er en superset til den allerede eksisterende "%s" signaturen.';
$CIDRAM['lang']['CLI_VL_Syntax'] = 'L%s: Ikke syntaktisk presis.';
$CIDRAM['lang']['CLI_VL_Tabs'] = 'L%s: Tabeller oppdaget; Mellomnøkler er foretrukket i forhold til tabeller for optimal lesbarhet.';
$CIDRAM['lang']['CLI_VL_Tags'] = 'L%s: Seksjonslabel er større enn 20 byte; Seksjonslabeler skal være klare og konsise.';
$CIDRAM['lang']['CLI_VL_Unrecognised'] = 'L%s: Ukjent [Function]; Signaturen kan bli ødelagt.';
$CIDRAM['lang']['CLI_VL_Whitespace'] = 'L%s: Overdreven bakrommet oppdaget på denne linjen.';
$CIDRAM['lang']['CLI_VL_YAML'] = 'L%s: YAML-lignende data oppdaget, men kunne ikke behandle det.';
