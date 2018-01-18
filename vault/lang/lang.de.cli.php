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
 * This file: German language data for CLI (last modified: 2018.01.18).
 */

/** Prevents execution from outside of CIDRAM. */
if (!defined('CIDRAM')) {
    die('[CIDRAM] This should not be accessed directly.');
}

$CIDRAM['lang']['CLI_H'] = "
 CIDRAM CLI-Modus Hilfe.

 Verwendung:
 /Pfad/zu/php/php.exe /Pfad/zu/cidram/loader.php -Flagge (Eingabe)

 Flaggen:   -h  Anzeige dieser Hilfe-Informationen.
            -c  Überprüfen Sie ob eine IP-Adresse blockiert wird.
            -g  Generieren Sie CIDRs von einer IP-Adresse.

 Eingabe: Kann jede gültige IPv4 oder IPv6 IP-Adresse sein.

 Beispiele:
        -c  192.168.0.0/16
        -c  127.0.0.1/32
        -c  2001:db8::/32
        -c  2002::1/128

";

$CIDRAM['lang']['CLI_Bad_IP'] = ' Die angegebene IP-Adresse, "{IP}", ist keine gültige IPv4 oder IPv6 IP-Adresse!';
$CIDRAM['lang']['CLI_IP_Blocked'] = ' Die angegebene IP-Adresse, "{IP}", *IST* wird durch eine oder mehrere der CIDRAM Signaturdateien blockiert.';
$CIDRAM['lang']['CLI_IP_Not_Blocked'] = ' Die angegebene IP-Adresse, "{IP}", *NICHT* ist wird durch eine oder mehrere der CIDRAM Signaturdateien blockiert.';

$CIDRAM['lang']['CLI_F_Finished'] = 'Signature Fixierer abgeschlossen ist, mit %s Änderungen über %s Operationen (%s).';
$CIDRAM['lang']['CLI_F_Started'] = 'Signature Fixierer hat begonnen (%s).';
$CIDRAM['lang']['CLI_VF_Empty'] = 'Die angegebene Signaturdatei leer ist oder nicht existiert.';
$CIDRAM['lang']['CLI_VF_Level_0'] = 'Achtung';
$CIDRAM['lang']['CLI_VF_Level_1'] = 'Warnung';
$CIDRAM['lang']['CLI_VF_Level_2'] = 'Fehler';
$CIDRAM['lang']['CLI_VF_Level_3'] = 'Fataler Fehler';

$CIDRAM['lang']['CLI_V_CRLF'] = 'Erkannt CR/CRLF in Signaturdatei; Diese sind zulässig und werden nicht zu Problemen führen, aber LF bevorzugt ist.';
$CIDRAM['lang']['CLI_V_Finished'] = 'Signatur Validator abgeschlossen ist (%s). Wenn keine Warnungen oder Fehler erschienen, Ihre Signaturdatei ist *wahrscheinlich* in Ordnung. :-)';
$CIDRAM['lang']['CLI_V_LineByLine'] = 'Zeile für Zeile Validierung hat begonnen.';
$CIDRAM['lang']['CLI_V_Started'] = 'Signatur Validator hat begonnen (%s).';
$CIDRAM['lang']['CLI_V_Terminal_LF'] = 'Signaturdateien sollten mit einem LF Zeilenumbruch beenden.';

$CIDRAM['lang']['CLI_VL_CC'] = 'L%s: Steuerzeichen entdeckt; Dies könnte darauf hindeuten Korruption und sollte untersucht werden.';
$CIDRAM['lang']['CLI_VL_Duplicated'] = 'L%s: Signatur "%s" ist dupliziert (%s zählt)!';
$CIDRAM['lang']['CLI_VL_Expiry'] = 'L%s: Ablauf-Tag enthält keinen gültigen ISO 8601 Datum/Zeit!';
$CIDRAM['lang']['CLI_VL_Invalid'] = 'L%s: "%s" ist keine gültige IPv4 oder IPv6 Adresse!';
$CIDRAM['lang']['CLI_VL_L120'] = 'L%s: Leitungslänge ist größer als 120 Byte; Für optimale Lesbarkeit, Leitungslänge sollte auf 120 Bytes beschränkt werden.';
$CIDRAM['lang']['CLI_VL_Mergeable'] = 'L%s und L%s sind identisch, und somit, zusammenführbar.';
$CIDRAM['lang']['CLI_VL_Missing'] = 'L%s: Fehlende [Function]; Signatur scheint unvollständig zu sein.';
$CIDRAM['lang']['CLI_VL_Nontriggerable'] = 'L%s: "%s" kann nicht ausgelöst werden! Seine Basis passt nicht auf den Beginn seiner Reichweite! Versuchen Sie es mit "%s" ersetzen.';
$CIDRAM['lang']['CLI_VL_Nontriggerable_Range'] = 'L%s: "%s" kann nicht ausgelöst werden! "%s" ist kein gültiges Reichweite!';
$CIDRAM['lang']['CLI_VL_Origin'] = 'L%s: Ursprungs-Tag enthält keinen gültigen ISO 3166-1 Alpha-2-Code!';
$CIDRAM['lang']['CLI_VL_Subordinate'] = 'L%s: "%s" ist eine Teilmenge den bereits bestehenden "%s" Signatur.';
$CIDRAM['lang']['CLI_VL_Superset'] = 'L%s: "%s" ist eine Obermenge zu den bereits bestehenden "%s" Signatur.';
$CIDRAM['lang']['CLI_VL_Syntax'] = 'L%s: Nicht syntaktisch präzise.';
$CIDRAM['lang']['CLI_VL_Tabs'] = 'L%s: Tabs entdeckt; Leerzeichen sind über Laschen für eine optimale Lesbarkeit bevorzugt.';
$CIDRAM['lang']['CLI_VL_Tags'] = 'L%s: Sektion-Tag größer als 20 Byte; Sektion-Tags sollten klar und prägnant sein.';
$CIDRAM['lang']['CLI_VL_Unrecognised'] = 'L%s: Unerkannt [Function]; Signatur könnte gebrochen werden.';
$CIDRAM['lang']['CLI_VL_Whitespace'] = 'L%s: Überschüssiges nachfolgende Leerzeichen auf dieser Linie entdeckt.';
$CIDRAM['lang']['CLI_VL_YAML'] = 'L%s: YAML-ähnliche Daten entdeckt, aber nicht verarbeitet werden konnte.';
