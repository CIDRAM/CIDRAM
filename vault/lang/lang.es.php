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
 * This file: Spanish language data (last modified: 2016.04.11).
 */

/** Prevents execution from outside of CIDRAM. */
if (!defined('CIDRAM')) {
    die('[CIDRAM] This should not be accessed directly.');
}

$CIDRAM['lang']['click_here'] = 'clic aquí';
$CIDRAM['lang']['denied'] = '¡Acceso Denegado!';
$CIDRAM['lang']['Error_WriteCache'] = 'No se puede escribir a la caché! Compruebe sus CHMOD permisos!';
$CIDRAM['lang']['field_datetime'] = 'Fecha/Hora: ';
$CIDRAM['lang']['field_id'] = 'ID: ';
$CIDRAM['lang']['field_ipaddr'] = 'Dirección IP: ';
$CIDRAM['lang']['field_query'] = 'Query: ';
$CIDRAM['lang']['field_referrer'] = 'Referente: ';
$CIDRAM['lang']['field_rURI'] = 'Reconstruida URI: ';
$CIDRAM['lang']['field_scriptversion'] = 'Guión Versión: ';
$CIDRAM['lang']['field_sigcount'] = 'Firmas Cuentas: ';
$CIDRAM['lang']['field_sigref'] = 'Firmas Referencias: ';
$CIDRAM['lang']['field_ua'] = 'Agente de Usuario: ';
$CIDRAM['lang']['field_whyreason'] = 'Razón Bloqueado: ';
$CIDRAM['lang']['generated_by'] = 'Generado por';
$CIDRAM['lang']['preamble'] = '-- Fin de la preámbulo. Añadir sus preguntas o comentarios después de esta línea. --';
$CIDRAM['lang']['ReasonMessage_BadIP'] = 'Su acceso a esta página se negó porque porque ha intentado acceder esta página utilizando una dirección IP no válida.';
$CIDRAM['lang']['ReasonMessage_Bogon'] = 'Su acceso a esta página se negó porque su dirección IP se reconoce como una dirección bogon, y la conexión de bogons a este sitio web no está permitido por el propietario del sitio web.';
$CIDRAM['lang']['ReasonMessage_Cloud'] = 'Su acceso a esta página se negó porque su dirección IP se reconoce como perteneciente a un servicio en la nube, y la conexión de servicios en la nube a este sitio web no está permitido por el propietario del sitio web.';
$CIDRAM['lang']['ReasonMessage_Generic'] = 'Su acceso a esta página se negó porque su dirección IP pertenece a una red figuran en una lista negra utilizada por este sitio web.';
$CIDRAM['lang']['ReasonMessage_Spam'] = 'Su acceso a esta página se negó porque su dirección IP pertenece a una red considerados de alto riesgo de spam.';
$CIDRAM['lang']['Short_BadIP'] = 'IP no válida!';
$CIDRAM['lang']['Short_Bogon'] = 'Bogon IP';
$CIDRAM['lang']['Short_Cloud'] = 'Servicio en la nube';
$CIDRAM['lang']['Short_Generic'] = 'Genérico';
$CIDRAM['lang']['Short_Spam'] = 'Riesgo de spam';
$CIDRAM['lang']['Support_Email'] = 'Si considera que este es un error, o para buscar ayuda, {ClickHereLink} para enviar un correo electrónico ticket de soporte al webmaster de esta web (por favor, no cambie el preámbulo o la línea de asunto del correo electrónico).';

$CIDRAM['lang']['CLI_H'] = "
 Ayuda para el modo CLI para CIDRAM.

 Uso:
 /directorio/para/php/php.exe /directorio/para/cidram/loader.php -Flag (Input)

 Flags: -h  Mostrar la información para la ayuda.
        -c  Comprobar si una dirección IP está bloqueada por los firmas de CIDRAM.
        -g  Generar CIDRs desde una dirección IP.

 Input: Puede ser cualquier dirección IPv4 o IPv6 válida.

 Ejemplos:
        -c  192.168.0.0/16
        -c  127.0.0.1/32
        -c  2001:db8::/32
        -c  2002::1/128

";

$CIDRAM['lang']['CLI_Bad_IP'] = ' La dirección IP especificada, "{IP}", no es una dirección IPv4 o IPv6 IP válida!';
$CIDRAM['lang']['CLI_IP_Blocked'] = ' La dirección IP especificada, "{IP}", *ESTÁ* bloqueada por uno o más de las firmas de CIDRAM.';
$CIDRAM['lang']['CLI_IP_Not_Blocked'] = ' La dirección IP especificada, "{IP}", *NO* está bloqueada por cualquiera de las firmas de CIDRAM.';

$CIDRAM['lang']['CLI_F_Finished'] = 'Signature fixer has finished, with %s changes made over %s operations (%s).';
$CIDRAM['lang']['CLI_F_Started'] = 'Signature fixer has started (%s).';
$CIDRAM['lang']['CLI_VF_Empty'] = 'Specified signature file is empty or doesn\'t exist.';
$CIDRAM['lang']['CLI_VF_Level_0'] = 'Nota';
$CIDRAM['lang']['CLI_VF_Level_1'] = 'Advertencia';
$CIDRAM['lang']['CLI_VF_Level_2'] = 'Error';
$CIDRAM['lang']['CLI_VF_Level_3'] = 'Error Fatal';

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
