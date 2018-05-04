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
 * This file: Portuguese language data (last modified: 2018.05.04).
 */

/** Prevents execution from outside of CIDRAM. */
if (!defined('CIDRAM')) {
    die('[CIDRAM] This should not be accessed directly.');
}

/** Language plurality rule. */
$CIDRAM['Plural-Rule'] = function ($Num) {
    return ($Num >= 0 || $Num <= 1) ? 0 : 1;
};

$CIDRAM['lang']['Error_WriteCache'] = 'Não é possível gravar para o cache! Por favor verifique suas permissões CHMOD!';
$CIDRAM['lang']['MoreInfo'] = 'Para maiores informações:';
$CIDRAM['lang']['ReasonMessage_BadIP'] = 'O seu acesso a esta página foi negado porque você tentou acessar a página usando um endereço IP inválido.';
$CIDRAM['lang']['ReasonMessage_Banned'] = 'O seu acesso a esta página foi negado devido ao mau comportamento anterior do seu endereço IP.';
$CIDRAM['lang']['ReasonMessage_Bogon'] = 'O seu acesso a esta página foi negado porque seu endereço IP é reconhecida como um endereço bogon, e conectando a partir de bogons a este site não é permitido pelo dono do site.';
$CIDRAM['lang']['ReasonMessage_Cloud'] = 'O seu acesso a esta página foi negado porque seu endereço IP é reconhecida como pertencente a um serviço de nuvem, e conectando a partir de serviços em nuvem a este site não é permitido pelo dono do site.';
$CIDRAM['lang']['ReasonMessage_Generic'] = 'O seu acesso a esta página foi negado porque seu endereço IP pertence a uma rede listados em uma lista negra usada por este site.';
$CIDRAM['lang']['ReasonMessage_Legal'] = 'O seu acesso a esta página foi negado devido a obrigações legais.';
$CIDRAM['lang']['ReasonMessage_Malware'] = 'O seu acesso a esta página foi negado devido a preocupações com malware relacionadas ao seu endereço IP.';
$CIDRAM['lang']['ReasonMessage_Proxy'] = 'O seu acesso a esta página foi negado porque seu endereço IP é reconhecida como pertencente a um serviço de proxy, e conectando a partir de um serviço de proxy a este site não é permitido pelo dono do site.';
$CIDRAM['lang']['ReasonMessage_Spam'] = 'O seu acesso a esta página foi negado porque seu endereço IP pertence a uma rede considerado de alto risco de spam.';
$CIDRAM['lang']['Short_BadIP'] = 'IP inválido!';
$CIDRAM['lang']['Short_Banned'] = 'Banido';
$CIDRAM['lang']['Short_Bogon'] = 'Bogon IP';
$CIDRAM['lang']['Short_Cloud'] = 'Serviço de nuvem';
$CIDRAM['lang']['Short_Generic'] = 'Genérico';
$CIDRAM['lang']['Short_Legal'] = 'Legais';
$CIDRAM['lang']['Short_Malware'] = 'Malware';
$CIDRAM['lang']['Short_Proxy'] = 'Proxy';
$CIDRAM['lang']['Short_Spam'] = 'Risco de spam';
$CIDRAM['lang']['Support_Email'] = 'Se você acredita que isso é um erro, ou a procurar assistência, {ClickHereLink} para enviar um e-mail ticket de suporte para o webmaster deste site (por favor, não alterar o preâmbulo ou linha de assunto do e-mail).';
$CIDRAM['lang']['Support_Email_2'] = 'Se você acredita que isso é um erro, envie um e-mail para {EmailAddr} para procurar ajuda.';
$CIDRAM['lang']['click_here'] = 'clique aqui';
$CIDRAM['lang']['denied'] = 'Acesso Negado!';
$CIDRAM['lang']['fake_ua'] = '{ua} falso';
$CIDRAM['lang']['field_datetime'] = 'Data/Hora: ';
$CIDRAM['lang']['field_hostname'] = 'Nome de anfitrião: ';
$CIDRAM['lang']['field_id'] = 'ID: ';
$CIDRAM['lang']['field_ipaddr'] = 'Endereço IP: ';
$CIDRAM['lang']['field_ipaddr_resolved'] = 'Endereço IP (Resolvido): ';
$CIDRAM['lang']['field_query'] = 'Query: ';
$CIDRAM['lang']['field_rURI'] = 'Reconstruído URI: ';
$CIDRAM['lang']['field_reCAPTCHA_state'] = 'Estado reCAPTCHA: ';
$CIDRAM['lang']['field_referrer'] = 'Referente: ';
$CIDRAM['lang']['field_scriptversion'] = 'Versão do Roteiro: ';
$CIDRAM['lang']['field_sigcount'] = 'Contagem da Assinaturas: ';
$CIDRAM['lang']['field_sigref'] = 'Assinaturas Referência: ';
$CIDRAM['lang']['field_ua'] = 'Agente de Usuário: ';
$CIDRAM['lang']['field_whyreason'] = 'Razão Bloqueada: ';
$CIDRAM['lang']['generated_by'] = 'Gerado por';
$CIDRAM['lang']['preamble'] = '-- Fim de preâmbulo. Adicionar suas perguntas ou comentários após esta linha. --';
$CIDRAM['lang']['recaptcha_cookie_warning'] = 'Nota: CIDRAM usa um cookie para lembrar quando os usuários completar o CAPTCHA. Ao completar o CAPTCHA, você dá o seu consentimento para um cookie para ser criado e armazenado pelo seu navegador.';
$CIDRAM['lang']['recaptcha_disabled'] = 'Desactivado.';
$CIDRAM['lang']['recaptcha_enabled'] = 'Ativado.';
$CIDRAM['lang']['recaptcha_failed'] = 'Falha!';
$CIDRAM['lang']['recaptcha_message'] = 'A fim de recuperar o acesso a esta página, por favor preencha o CAPTCHA fornecido abaixo e pressione o botão enviar.';
$CIDRAM['lang']['recaptcha_message_invisible'] = 'Para a maioria dos usuários, a página deve recarregar e restaurar o acesso normal. Em alguns casos, você pode ser obrigado a completar um desafio CAPTCHA.';
$CIDRAM['lang']['recaptcha_passed'] = 'Sucesso!';
$CIDRAM['lang']['recaptcha_submit'] = 'Enviar';
