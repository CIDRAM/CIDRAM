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
 * This file: Spanish language data for CLI (last modified: 2016.07.26).
 */

/** Prevents execution from outside of CIDRAM. */
if (!defined('CIDRAM')) {
    die('[CIDRAM] This should not be accessed directly.');
}

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

$CIDRAM['lang']['CLI_F_Finished'] = 'Fijador de la firmas se terminado, con %s cambios hacen a través de %s operaciones (%s).';
$CIDRAM['lang']['CLI_F_Started'] = 'Fijador de la firmas se iniciado (%s).';
$CIDRAM['lang']['CLI_VF_Empty'] = 'Archivo de firmas especificada esta vacio o no existe.';
$CIDRAM['lang']['CLI_VF_Level_0'] = 'Nota';
$CIDRAM['lang']['CLI_VF_Level_1'] = 'Advertencia';
$CIDRAM['lang']['CLI_VF_Level_2'] = 'Error';
$CIDRAM['lang']['CLI_VF_Level_3'] = 'Error Fatal';

$CIDRAM['lang']['CLI_V_CRLF'] = 'Detectado CR/CRLF en el archivo de firmas; Estos son permisibles y no causará problemas, pero LF es preferible.';
$CIDRAM['lang']['CLI_V_Finished'] = 'Validador de la firmas se terminado (%s). Si no han aparecido cualquier advertencias o errores, su archivo de firmas es *probablemente* bueno. :-)';
$CIDRAM['lang']['CLI_V_LineByLine'] = 'Linea por linea validación se iniciado.';
$CIDRAM['lang']['CLI_V_Started'] = 'Validador de la firmas se iniciado (%s).';
$CIDRAM['lang']['CLI_V_Terminal_LF'] = 'Archivos de firmas debe terminar con un salto de línea LF.';

$CIDRAM['lang']['CLI_VL_CC'] = 'L%s: Caracteres control detectados; Esto podría indicar la corrupción y debe ser investigado.';
$CIDRAM['lang']['CLI_VL_Duplicated'] = 'L%s: La firma "%s" está duplicada (%s conteos)!';
$CIDRAM['lang']['CLI_VL_Expiry'] = 'L%s: Etiqueta de expiración no contiene una norma ISO 8601 fecha/hora válida!';
$CIDRAM['lang']['CLI_VL_Invalid'] = 'L%s: "%s" *NO* es una dirección IPv4 o IPv6 válida!';
$CIDRAM['lang']['CLI_VL_L120'] = 'L%s: Longitud de la línea es mayor que 120 bytes; Longitud de la línea debe limitarse a 120 bytes para la legibilidad óptima.';
$CIDRAM['lang']['CLI_VL_Mergeable'] = 'L%s y L%s son identicos, y por lo tanto, pueden fusionarse.';
$CIDRAM['lang']['CLI_VL_Missing'] = 'L%s: [Function] es ausente; Firma parece estar incompletos.';
$CIDRAM['lang']['CLI_VL_Nontriggerable'] = 'L%s: "%s" no es activable! Su base no coincide con el comienzo de su rango! Intente reemplazarlo con "%s".';
$CIDRAM['lang']['CLI_VL_Nontriggerable_Range'] = 'L%s: "%s" no es activable! "%s" no es un rango válido!';
$CIDRAM['lang']['CLI_VL_Subordinate'] = 'L%s: "%s" está subordinada a la ya existente firma "%s".';
$CIDRAM['lang']['CLI_VL_Superset'] = 'L%s: "%s" es un superconjunto a la ya existente firma "%s".';
$CIDRAM['lang']['CLI_VL_Syntax'] = 'L%s: No sintácticamente precisa.';
$CIDRAM['lang']['CLI_VL_Tabs'] = 'L%s: Tabulaciones detectadas; Espacios son preferibles a las tabulaciones para la legibilidad óptima.';
$CIDRAM['lang']['CLI_VL_Tags'] = 'L%s: Etiqueta de sección es mayor que 20 bytes; Etiquetas de secciones debe ser clara y concisa.';
$CIDRAM['lang']['CLI_VL_Unrecognised'] = 'L%s: [Function] no reconocido; Firma podía ser roto.';
$CIDRAM['lang']['CLI_VL_Whitespace'] = 'L%s: Exceso de espacios en blanco detectado en el extremo de esta línea.';
$CIDRAM['lang']['CLI_VL_YAML'] = 'L%s: Datos similares a YAML detectados, pero no podía procesarlo.';
