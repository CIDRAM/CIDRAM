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
 * This file: Portuguese language data for CLI (last modified: 2018.01.18).
 */

/** Prevents execution from outside of CIDRAM. */
if (!defined('CIDRAM')) {
    die('[CIDRAM] This should not be accessed directly.');
}

$CIDRAM['lang']['CLI_H'] = "
 Modo CLI ajuda para CIDRAM.

 Uso:
 /diretório/para/php/php.exe /diretório/para/cidram/loader.php -Flag (Input)

 Flags: -h  Exibir essa informação ajuda.
        -c  Verifique se um endereço IP está bloqueado pelas assinaturas do CIDRAM.
        -g  Gerar CIDRs de um endereço IP.

 Input: Pode ser qualquer endereço IPv4 ou IPv6 válido.

 Exemplos:
        -c  192.168.0.0/16
        -c  127.0.0.1/32
        -c  2001:db8::/32
        -c  2002::1/128

";

$CIDRAM['lang']['CLI_Bad_IP'] = ' O endereço IP especificado, "{IP}", não é um endereço válido!';
$CIDRAM['lang']['CLI_IP_Blocked'] = ' O endereço IP especificado, "{IP}", é bloqueado por um ou mais das assinaturas.';
$CIDRAM['lang']['CLI_IP_Not_Blocked'] = ' O endereço IP especificado, "{IP}", *NÃO* é bloqueado por qualquer um das assinaturas.';

$CIDRAM['lang']['CLI_F_Finished'] = 'Fixador das assinaturas foi terminada, com %s mudanças feitas ao longo de %s operações (%s).';
$CIDRAM['lang']['CLI_F_Started'] = 'Fixador das assinaturas foi iniciada (%s).';
$CIDRAM['lang']['CLI_VF_Empty'] = 'Arquivo de assinaturas especificado está vazia ou não existe.';
$CIDRAM['lang']['CLI_VF_Level_0'] = 'Aviso Prévio';
$CIDRAM['lang']['CLI_VF_Level_1'] = 'Aviso';
$CIDRAM['lang']['CLI_VF_Level_2'] = 'Erro';
$CIDRAM['lang']['CLI_VF_Level_3'] = 'Erro Fatal';

$CIDRAM['lang']['CLI_V_CRLF'] = 'CR/CRLF detectados no arquivo de assinaturas; Estes são permissíveis e não vai causar problemas, mas LF é preferível.';
$CIDRAM['lang']['CLI_V_Finished'] = 'Validador das assinaturas foi terminada (%s). Se não avisos ou erros aparecem, seu arquivo de assinaturas é *provavelmente* bem. :-)';
$CIDRAM['lang']['CLI_V_LineByLine'] = 'Linha por linha validação foi iniciada.';
$CIDRAM['lang']['CLI_V_Started'] = 'Validador das assinaturas foi iniciada (%s).';
$CIDRAM['lang']['CLI_V_Terminal_LF'] = 'Arquivos de assinaturas deve terminar com uma quebra de linha LF.';

$CIDRAM['lang']['CLI_VL_CC'] = 'L%s: Caracteres de controle detectado; Isso poderia indicar a corrupção e deve ser investigado.';
$CIDRAM['lang']['CLI_VL_Duplicated'] = 'L%s: Assinatura "%s" é duplicada (%s contagens)!';
$CIDRAM['lang']['CLI_VL_Expiry'] = 'L%s: Etiqueta de expiração não contém um válido ISO 8601 data/hora!';
$CIDRAM['lang']['CLI_VL_Invalid'] = 'L%s: "%s" *NÃO* é um endereço IPv4 ou IPv6 válido!';
$CIDRAM['lang']['CLI_VL_L120'] = 'L%s: Comprimento da linha é maior que 120 bytes; Comprimento da linha deve ser limitado a 120 bytes para facilitar a leitura ótimo.';
$CIDRAM['lang']['CLI_VL_Mergeable'] = 'L%s e L%s são idênticos, e assim, mesclável.';
$CIDRAM['lang']['CLI_VL_Missing'] = 'L%s: Ausência de [Function]; Assinatura parece estar incompleto.';
$CIDRAM['lang']['CLI_VL_Nontriggerable'] = 'L%s: "%s" não pode ser desencadeada! Sua base não coincide com o início da sua gama! Tente substituindo-o por "%s".';
$CIDRAM['lang']['CLI_VL_Nontriggerable_Range'] = 'L%s: "%s" não pode ser desencadeada! "%s" não é uma gama válida!';
$CIDRAM['lang']['CLI_VL_Origin'] = 'L%s: Etiqueta de origem não contém um código ISO 3166-1 alfa-2 válido!';
$CIDRAM['lang']['CLI_VL_Subordinate'] = 'L%s: "%s" é subordinado ao já existente assinatura de "%s".';
$CIDRAM['lang']['CLI_VL_Superset'] = 'L%s: "%s" é um superconjunto ao já existente assinatura de "%s".';
$CIDRAM['lang']['CLI_VL_Syntax'] = 'L%s: Não sintaticamente precisa.';
$CIDRAM['lang']['CLI_VL_Tabs'] = 'L%s: Tabs detectados; Espaços têm preferência sobre tabs para facilitar a leitura ótimo.';
$CIDRAM['lang']['CLI_VL_Tags'] = 'L%s: Etiqueta da seção é maior que 20 bytes; Etiquetas da seções deve ser claro e conciso.';
$CIDRAM['lang']['CLI_VL_Unrecognised'] = 'L%s: [Function] não reconhecido; Assinatura poderia ser quebrado.';
$CIDRAM['lang']['CLI_VL_Whitespace'] = 'L%s: Excesso de espaços em branco detectado à direita desta linha.';
$CIDRAM['lang']['CLI_VL_YAML'] = 'L%s: Dados semelhantes a YAML detectado, mas não pôde processá-la.';
