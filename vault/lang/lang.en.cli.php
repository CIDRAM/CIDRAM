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
 * This file: English language data for CLI (last modified: 2016.04.12).
 */

/** Prevents execution from outside of CIDRAM. */
if (!defined('CIDRAM')) {
    die('[CIDRAM] This should not be accessed directly.');
}

$CIDRAM['lang']['CLI_H'] = "
 CIDRAM CLI-mode help.

 Usage:
 /path/to/php/php.exe /path/to/cidram/loader.php -Flag (Input)

 Flags: -h  Display this help information.
        -c  Check if an IP address is blocked by the CIDRAM signature files.
        -g  Generate CIDRs from an IP address.

 Input: Can be any valid IPv4 or IPv6 IP address.

 Examples:
        -c  192.168.0.0/16
        -c  127.0.0.1/32
        -c  2001:db8::/32
        -c  2002::1/128

";

$CIDRAM['lang']['CLI_Bad_IP'] = ' The specified IP address, "{IP}", is not a valid IPv4 or IPv6 IP address!';
$CIDRAM['lang']['CLI_IP_Blocked'] = ' The specified IP address, "{IP}", *IS* blocked by one or more of the CIDRAM signature files.';
$CIDRAM['lang']['CLI_IP_Not_Blocked'] = ' The specified IP address, "{IP}", is *NOT* blocked by any of the CIDRAM signature files.';

$CIDRAM['lang']['CLI_F_Finished'] = 'Signature fixer has finished, with %s changes made over %s operations (%s).';
$CIDRAM['lang']['CLI_F_Started'] = 'Signature fixer has started (%s).';
$CIDRAM['lang']['CLI_VF_Empty'] = 'Specified signature file is empty or doesn\'t exist.';
$CIDRAM['lang']['CLI_VF_Level_0'] = 'Notice';
$CIDRAM['lang']['CLI_VF_Level_1'] = 'Warning';
$CIDRAM['lang']['CLI_VF_Level_2'] = 'Error';
$CIDRAM['lang']['CLI_VF_Level_3'] = 'Fatal Error';

$CIDRAM['lang']['CLI_V_CRLF'] = 'Detected CR/CRLF in signature file; These are permissible and won\'t cause problems, but LF is preferable.';
$CIDRAM['lang']['CLI_V_Finished'] = 'Signature validator has finished (%s). If no warnings or errors have appeared, your signature file is *probably* okay. :-)';
$CIDRAM['lang']['CLI_V_LineByLine'] = 'Line-by-line validation has started.';
$CIDRAM['lang']['CLI_V_Started'] = 'Signature validator has started (%s).';
$CIDRAM['lang']['CLI_V_Terminal_LF'] = 'Signature files should terminate with an LF linebreak.';

$CIDRAM['lang']['CLI_VL_CC'] = 'L%s: Control characters detected; This could indicate corruption and should be investigated.';
$CIDRAM['lang']['CLI_VL_Duplicated'] = 'L%s: Signature "%s" is duplicated (%s counts)!';
$CIDRAM['lang']['CLI_VL_Expiry'] = 'L%s: Expiry tag doesn\'t contain a valid ISO 8601 date/time!';
$CIDRAM['lang']['CLI_VL_Invalid'] = 'L%s: "%s" is *NOT* a valid IPv4 or IPv6 address!';
$CIDRAM['lang']['CLI_VL_L120'] = 'L%s: Line length is greater than 120 bytes; Line length should be limited to 120 bytes for optimal readability.';
$CIDRAM['lang']['CLI_VL_Mergeable'] = 'L%s and L%s are identical, and thus, mergeable.';
$CIDRAM['lang']['CLI_VL_Missing'] = 'L%s: Missing %Function%; Signature appears to be incomplete.';
$CIDRAM['lang']['CLI_VL_Nontriggerable'] = 'L%s: "%s" is non-triggerable! Its base doesn\'t match the beginning of its range! Try replacing it with "%s".';
$CIDRAM['lang']['CLI_VL_Subordinate'] = 'L%s: "%s" is subordinate to the already existing "%s" signature.';
$CIDRAM['lang']['CLI_VL_Superset'] = 'L%s: "%s" is a superset to the already existing "%s" signature.';
$CIDRAM['lang']['CLI_VL_Syntax'] = 'L%s: Not syntactically precise.';
$CIDRAM['lang']['CLI_VL_Tabs'] = 'L%s: Tabs detected; Spaces are preferred over tabs for optimal readability.';
$CIDRAM['lang']['CLI_VL_Tags'] = 'L%s: Section tag is greater than 20 bytes; Section tags should be clear and concise.';
$CIDRAM['lang']['CLI_VL_Unrecognised'] = 'L%s: Unrecognised %Function%; Signature could be broken.';
$CIDRAM['lang']['CLI_VL_Whitespace'] = 'L%s: Excess trailing whitespace detected on this line.';
