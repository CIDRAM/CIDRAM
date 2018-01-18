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
 * This file: Swedish language data for CLI (last modified: 2018.01.18).
 */

/** Prevents execution from outside of CIDRAM. */
if (!defined('CIDRAM')) {
    die('[CIDRAM] This should not be accessed directly.');
}

$CIDRAM['lang']['CLI_H'] = "
 Hjälp för CIDRAM CLI-läge.

 Användande:
 /väg/till/php/php.exe /väg/till/cidram/loader.php -Flagga (Input)

 Flaggor: -h Visa denna hjälpinformation.
          -c Kontrollera om en IP-adress blockeras av CIDRAM-signaturfilerna.
          -g Generera CIDRer från en IP-adress.

 Input: Kan vara någon giltig IPv4 eller IPv6 IP-adress.

 Exempel:
        -c  192.168.0.0/16
        -c  127.0.0.1/32
        -c  2001:db8::/32
        -c  2002::1/128

";

$CIDRAM['lang']['CLI_Bad_IP'] = ' Den angivna IP-adressen, "{IP}", är inte en giltig IPv4 eller IPv6 IP-adress!';
$CIDRAM['lang']['CLI_IP_Blocked'] = ' Den angivna IP-adressen, "{IP}", är blockeras av en eller flera av CIDRAM-signaturfilerna.';
$CIDRAM['lang']['CLI_IP_Not_Blocked'] = ' Den angivna IP-adressen, "{IP}", är blockeras inte av någon av CIDRAM-signaturfilerna.';

$CIDRAM['lang']['CLI_F_Finished'] = 'Signaturfixaren är klar, med %s ändringar gjorda över %s operationer (%s).';
$CIDRAM['lang']['CLI_F_Started'] = 'Signaturfixaren har börjat (%s).';
$CIDRAM['lang']['CLI_VF_Empty'] = 'Den angivna signaturfilen är tom eller existerar inte.';
$CIDRAM['lang']['CLI_VF_Level_0'] = 'Meddelande';
$CIDRAM['lang']['CLI_VF_Level_1'] = 'Varning';
$CIDRAM['lang']['CLI_VF_Level_2'] = 'Fel';
$CIDRAM['lang']['CLI_VF_Level_3'] = 'Allvarligt Fel';

$CIDRAM['lang']['CLI_V_CRLF'] = 'Upptäckt CR/CRLF i signaturfilen; Dessa är tillåtna och kommer inte att orsaka problem, men LF är att föredra.';
$CIDRAM['lang']['CLI_V_Finished'] = 'Signaturvalideraren är klar (%s). Om inga varningar eller fel har uppstått, din signaturfil *troligtvis* är okej. :-)';
$CIDRAM['lang']['CLI_V_LineByLine'] = 'Validering av linje för rad har börjat.';
$CIDRAM['lang']['CLI_V_Started'] = 'Signaturvalideraren har börjat (%s).';
$CIDRAM['lang']['CLI_V_Terminal_LF'] = 'Signaturfiler bör avslutas med en LF.';

$CIDRAM['lang']['CLI_VL_CC'] = 'L%s: Kontrolltecken identifierade; Detta kan indikera korruption och bör undersökas.';
$CIDRAM['lang']['CLI_VL_Duplicated'] = 'L%s: Signaturen "%s" dupliceras (%s instanser)!';
$CIDRAM['lang']['CLI_VL_Expiry'] = 'L%s: Expiry tagg innehåller inte ett giltigt ISO 8601 datum/tid!';
$CIDRAM['lang']['CLI_VL_Invalid'] = 'L%s: "%s" är INTE en giltig IPv4 eller IPv6 IP-adress!';
$CIDRAM['lang']['CLI_VL_L120'] = 'L%s: Linjelängden är större än 120 byte; Linjelängden bör begränsas till 120 byte för optimal läsbarhet.';
$CIDRAM['lang']['CLI_VL_Mergeable'] = 'L%s och L%s är identiska, och därmed, sammanfogningsbara.';
$CIDRAM['lang']['CLI_VL_Missing'] = 'L%s: Saknad [Function]; Signaturen verkar vara ofullständig.';
$CIDRAM['lang']['CLI_VL_Nontriggerable'] = 'L%s: "%s" är inte utlösbar! Dess bas motsvarar inte början på sortimentet! Försök att byta ut det med "%s".';
$CIDRAM['lang']['CLI_VL_Nontriggerable_Range'] = 'L%s: "%s" är inte utlösbar! "%s" är inte ett giltigt range!';
$CIDRAM['lang']['CLI_VL_Origin'] = 'L%s: Ursprungs tagg innehåller inte en giltig ISO 3166-1 Alpha-2-kod!';
$CIDRAM['lang']['CLI_VL_Subordinate'] = 'L%s: "%s" är underordnad den redan existerande signaturen "%s".';
$CIDRAM['lang']['CLI_VL_Superset'] = 'L%s: "%s" är en superset till den redan existerande signaturen "%s".';
$CIDRAM['lang']['CLI_VL_Syntax'] = 'L%s: Inte syntaktiskt exakt.';
$CIDRAM['lang']['CLI_VL_Tabs'] = 'L%s: Flikar detekteras; Mellanslag föredras över flikar för optimal läsbarhet.';
$CIDRAM['lang']['CLI_VL_Tags'] = 'L%s: Sektions tagg är större än 20 byte; Sektionstaggar ska vara klara och koncisa.';
$CIDRAM['lang']['CLI_VL_Unrecognised'] = 'L%s: Okänd [Function]; Signaturen kan vara trasig.';
$CIDRAM['lang']['CLI_VL_Whitespace'] = 'L%s: Överdriven efterföljande blankutrymme detekteras på denna rad.';
$CIDRAM['lang']['CLI_VL_YAML'] = 'L%s: YAML-liknande data upptäcktes, men kunde inte bearbeta den.';
