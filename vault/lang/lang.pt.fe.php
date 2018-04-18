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
 * This file: Portuguese language data for the front-end (last modified: 2018.04.17).
 */

/** Prevents execution from outside of CIDRAM. */
if (!defined('CIDRAM')) {
    die('[CIDRAM] This should not be accessed directly.');
}

foreach (['IPv4', 'IPv6'] as $CIDRAM['IPvX']) {
    $CIDRAM['Pre'] = 'Assinaturas ' . $CIDRAM['IPvX'] . ' padrão normalmente incluído com o pacote principal. ';
    $CIDRAM['lang']['Extended Description: ' . $CIDRAM['IPvX']] = $CIDRAM['Pre'] . 'Bloqueia serviços em nuvem indesejados e pontos de extremidade não-humanos.';
    $CIDRAM['lang']['Extended Description: ' . $CIDRAM['IPvX'] . '-Bogons'] = $CIDRAM['Pre'] . 'Bloqueia bogon/marciano CIDRs.';
    $CIDRAM['lang']['Extended Description: ' . $CIDRAM['IPvX'] . '-ISPs'] = $CIDRAM['Pre'] . 'Bloqueia ISPs perigosos e propensos a spam.';
    $CIDRAM['lang']['Extended Description: ' . $CIDRAM['IPvX'] . '-Other'] = $CIDRAM['Pre'] . 'Bloqueia CIDRs para proxies, VPNs e outros diversos serviços indesejados.';
    $CIDRAM['Pre'] = 'Arquivo de assinaturas para ' . $CIDRAM['IPvX'] . ' (%s).';
    $CIDRAM['lang']['Name: ' . $CIDRAM['IPvX']] = sprintf($CIDRAM['Pre'], 'serviços em nuvem indesejados e pontos finais não-humanos');
    $CIDRAM['lang']['Name: ' . $CIDRAM['IPvX'] . '-Bogons'] = sprintf($CIDRAM['Pre'], 'bogon/marciano CIDRs');
    $CIDRAM['lang']['Name: ' . $CIDRAM['IPvX'] . '-ISPs'] = sprintf($CIDRAM['Pre'], 'ISPs perigosos e propensos a spam');
    $CIDRAM['lang']['Name: ' . $CIDRAM['IPvX'] . '-Other'] = sprintf($CIDRAM['Pre'], 'CIDRs para proxies, VPNs e outros diversos serviços indesejados');
}
unset($CIDRAM['Pre'], $CIDRAM['IPvX']);

$CIDRAM['lang']['Extended Description: Bypasses'] = 'Os arquivos para bypass das assinaturas padrão normalmente incluído com o pacote principal.';
$CIDRAM['lang']['Extended Description: CIDRAM'] = 'O pacote principal (menos as assinaturas, documentação e configuração).';
$CIDRAM['lang']['Extended Description: module_badhosts.php'] = 'Bloqueia hosts frequentemente usados por spammers, hackers e outras entidades nefastas.';
$CIDRAM['lang']['Extended Description: module_badhosts_isps.php'] = 'Bloqueia hosts pertencentes a ISPs freqüentemente usados por spammers, hackers e outras entidades nefastas.';
$CIDRAM['lang']['Extended Description: module_badtlds.php'] = 'Bloqueia hosts pertencentes a ISPs freqüentemente usados por spammers, hackers e outras entidades nefastas.';
$CIDRAM['lang']['Extended Description: module_cookies.php'] = 'Fornece algumas proteções limitadas contra cookies perigosos.';
$CIDRAM['lang']['Extended Description: module_extras.php'] = 'Fornece algumas proteções limitadas contra vários vectores de ataque comumente usado em solicitações.';
$CIDRAM['lang']['Extended Description: module_sfs.php'] = 'Protege as páginas de registro e login contra IPs listados pelo SFS.';
$CIDRAM['lang']['Name: Bypasses'] = 'Bypasses das assinaturas padrão.';
$CIDRAM['lang']['Name: module_badhosts.php'] = 'Módulo bloqueador de hosts perigosos';
$CIDRAM['lang']['Name: module_badhosts_isps.php'] = 'Módulo bloqueador de hosts perigosos (ISPs)';
$CIDRAM['lang']['Name: module_badtlds.php'] = 'Módulo bloqueador de TLDs perigosos';
$CIDRAM['lang']['Name: module_baidublocker.php'] = 'Módulo bloqueador de Baidu';
$CIDRAM['lang']['Name: module_cookies.php'] = 'Módulo opcional para análise de cookies';
$CIDRAM['lang']['Name: module_extras.php'] = 'Módulo de extras de segurança opcional';
$CIDRAM['lang']['Name: module_sfs.php'] = 'Módulo Stop Forum Spam';
$CIDRAM['lang']['Name: module_yandexblocker.php'] = 'Módulo bloqueador de Yandex';
$CIDRAM['lang']['bNav_home_logout'] = '<a href="?">Página Principal</a> | <a href="?cidram-page=logout">Sair</a>';
$CIDRAM['lang']['bNav_logout'] = '<a href="?cidram-page=logout">Sair</a>';
$CIDRAM['lang']['config_general_FrontEndLog'] = 'Arquivo para registrar tentativas de login ao front-end. Especifique o nome de um arquivo, ou deixe em branco para desabilitar.';
$CIDRAM['lang']['config_general_allow_gethostbyaddr_lookup'] = 'Permitir pesquisas gethostbyaddr quando o UDP não está disponível? True = Sim [Padrão]; False = Não.';
$CIDRAM['lang']['config_general_ban_override'] = 'Sobrepor "forbid_on_block" quando "infraction_limit" é excedido? Quando sobrepõe: As solicitações bloqueadas retornam uma página em branco (os arquivos de modelo não são usados). 200 = Não sobrepor [Padrão]; 403 = Sobrepor com "403 Forbidden"; 503 = Sobrepor com "503 Service unavailable".';
$CIDRAM['lang']['config_general_default_algo'] = 'Define qual algoritmo usar para todas as futuras senhas e sessões. Opções: PASSWORD_DEFAULT (padrão), PASSWORD_BCRYPT, PASSWORD_ARGON2I (requer PHP &gt;= 7.2.0).';
$CIDRAM['lang']['config_general_default_dns'] = 'Uma lista delimitada por vírgulas de servidores DNS a serem usados para pesquisas de nomes de host. Padrão = "8.8.8.8,8.8.4.4" (Google DNS). ATENÇÃO: Não mude isso a menos que você saiba o que está fazendo!';
$CIDRAM['lang']['config_general_disable_cli'] = 'Desativar o CLI modo? CLI modo é ativado por padrão, mas às vezes pode interferir com certas testes ferramentas (tal como PHPUnit, por exemplo) e outras aplicações baseadas em CLI. Se você não precisa desativar o CLI modo, você deve ignorar esta directiva. False = Ativar o CLI modo [Padrão]; True = Desativar o CLI modo.';
$CIDRAM['lang']['config_general_disable_frontend'] = 'Desativar o acesso front-end? Acesso front-end pode fazer CIDRAM mais manejável, mas também pode ser um risco de segurança potencial, também. É recomendado para gerenciar CIDRAM através do back-end, sempre que possível, mas o acesso front-end é proporcionada para quando não é possível. Mantê-lo desativado, a menos que você precisar. False = Ativar o acesso front-end; True = Desativar o acesso front-end [Padrão].';
$CIDRAM['lang']['config_general_disable_webfonts'] = 'Desativar webfonts? True = Sim; False = Não [Padrão].';
$CIDRAM['lang']['config_general_emailaddr'] = 'Se você desejar, você pode fornecer um endereço de e-mail aqui a ser dado para os usuários quando eles estão bloqueadas, para eles para usar como um ponto de contato para suporte e/ou assistência no caso de eles sendo bloqueado por engano ou em erro. AVISO: Qualquer endereço de e-mail que você fornecer aqui certamente vai ser adquirido por spambots e raspadores/scrapers durante o curso de seu ser usada aqui, e assim, é fortemente recomendado que, se você optar por fornecer um endereço de e-mail aqui, que você garantir que o endereço de email você fornecer aqui é um endereço descartável e/ou um endereço que você não é importante (em outras palavras, você provavelmente não quer usar seu pessoal principal ou negócio principal endereço de e-mail).';
$CIDRAM['lang']['config_general_emailaddr_display_style'] = 'Como você prefere que o endereço de e-mail seja apresentado aos usuários?';
$CIDRAM['lang']['config_general_forbid_on_block'] = 'Quais cabeçalhos deve CIDRAM responder com quando bloqueando solicitações?';
$CIDRAM['lang']['config_general_force_hostname_lookup'] = 'Forçar pesquisas de nome de anfitrião? True = Sim; False = Não [Padrão]. As pesquisas de nome de anfitrião normalmente são realizadas com base na necessidade, mas pode ser forçado para todos os pedidos. Isso pode ser útil como forma de fornecer informações mais detalhadas nos arquivos de log, mas também pode ter um efeito ligeiramente negativo sobre o desempenho.';
$CIDRAM['lang']['config_general_hide_version'] = 'Ocultar informações da versão dos logs e da saída da página? True = Sim; False = Não [Padrão].';
$CIDRAM['lang']['config_general_ipaddr'] = 'Onde encontrar o IP endereço das solicitações? (Útil por serviços como o Cloudflare e tal). Padrão = REMOTE_ADDR. ATENÇÃO: Não mude isso a menos que você saiba o que está fazendo!';
$CIDRAM['lang']['config_general_lang'] = 'Especificar o padrão da linguagem por CIDRAM.';
$CIDRAM['lang']['config_general_log_banned_ips'] = 'Incluir solicitações bloqueadas de IPs banidas nos arquivos de log? True = Sim [Padrão]; False = Não.';
$CIDRAM['lang']['config_general_logfile'] = 'Um arquivo legível por humanos para registrar todas as tentativas de acesso bloqueadas. Especifique o nome de um arquivo, ou deixe em branco para desabilitar.';
$CIDRAM['lang']['config_general_logfileApache'] = 'Um arquivo no estilo da Apache para registrar todas as tentativas de acesso bloqueadas. Especifique o nome de um arquivo, ou deixe em branco para desabilitar.';
$CIDRAM['lang']['config_general_logfileSerialized'] = 'Um arquivo serializado para registrar todas as tentativas de acesso bloqueadas. Especifique o nome de um arquivo, ou deixe em branco para desabilitar.';
$CIDRAM['lang']['config_general_maintenance_mode'] = 'Ativar o modo de manutenção? True = Sim; False = Não [Padrão]. Desativa tudo além do front-end. Às vezes útil para quando atualiza seu CMS, frameworks, etc.';
$CIDRAM['lang']['config_general_max_login_attempts'] = 'Número máximo de tentativas de login.';
$CIDRAM['lang']['config_general_numbers'] = 'Como você prefere que os números sejam exibidos? Selecione o exemplo que parece mais correto para você.';
$CIDRAM['lang']['config_general_protect_frontend'] = 'Especifica se as proteções normalmente fornecidas pelo CIDRAM devem ser aplicadas ao front-end. True = Sim [Padrão]; False = Não.';
$CIDRAM['lang']['config_general_search_engine_verification'] = 'Tentativa de verificar pedidos dos motores de busca? Verificando os motores de busca garante que eles não serão banidos como resultado de exceder o limite de infrações (proibindo motores de busca de seu site normalmente terá um efeito negativo sobre o seu motor de busca ranking, SEO, etc). Quando verificado, os motores de busca podem ser bloqueados como por normal, mas não serão banidos. Quando não verificado, é possível que eles serão banidos como resultado de ultrapassar o limite de infrações. Também, a verificação dos motores de busca fornece proteção contra falsos pedidos de motores de busca e contra entidades potencialmente mal-intencionadas mascarando como motores de busca (tais pedidos serão bloqueados quando a verificação dos motores de busca estiver ativada). True = Ativar a verificação dos motores de busca [Padrão]; False = Desativar a verificação dos motores de busca.';
$CIDRAM['lang']['config_general_silent_mode'] = 'Deve CIDRAM silenciosamente redirecionar as tentativas de acesso bloqueadas em vez de exibir o "Acesso Negado" página? Se sim, especificar o local para redirecionar as tentativas de acesso bloqueadas para. Se não, deixe esta variável em branco.';
$CIDRAM['lang']['config_general_statistics'] = 'Monitorar as estatísticas de uso do CIDRAM? True = Sim; False = Não [Padrão].';
$CIDRAM['lang']['config_general_timeFormat'] = 'O formato de notação de data/tempo utilizado pelo CIDRAM. Opções adicionais podem ser adicionadas a pedido.';
$CIDRAM['lang']['config_general_timeOffset'] = 'Deslocamento do fuso horário em minutos.';
$CIDRAM['lang']['config_general_timezone'] = 'O seu fuso horário.';
$CIDRAM['lang']['config_general_truncate'] = 'Truncar arquivos de log quando atingem um determinado tamanho? Valor é o tamanho máximo em B/KB/MB/GB/TB que um arquivo de log pode crescer antes de ser truncado. O valor padrão de 0KB desativa o truncamento (arquivos de log podem crescer indefinidamente). Nota: Aplica-se a arquivos de log individuais! O tamanho dos arquivos de log não é considerado coletivamente.';
$CIDRAM['lang']['config_recaptcha_api'] = 'Qual API usar? V2 ou Invisible?';
$CIDRAM['lang']['config_recaptcha_expiry'] = 'Número de horas para lembrar instâncias reCAPTCHA.';
$CIDRAM['lang']['config_recaptcha_lockip'] = 'Ligar reCAPTCHA para IPs?';
$CIDRAM['lang']['config_recaptcha_lockuser'] = 'Ligar reCAPTCHA para os usuários?';
$CIDRAM['lang']['config_recaptcha_logfile'] = 'Registrar todas as tentativas de reCAPTCHA? Se sim, especificar o nome a ser usado para o arquivo de registro. Se não, deixe esta variável em branco.';
$CIDRAM['lang']['config_recaptcha_secret'] = 'Este valor deve corresponder ao "secret key" para o seu reCAPTCHA, que pode ser encontrado dentro do painel de reCAPTCHA.';
$CIDRAM['lang']['config_recaptcha_signature_limit'] = 'O número máximo de assinaturas que podem ser desencadeado quando uma instância de reCAPTCHA deve ser oferecida. Padrão = 1. Se este número for excedido para qualquer solicitação específica, uma instância de reCAPTCHA não será oferecida.';
$CIDRAM['lang']['config_recaptcha_sitekey'] = 'Este valor deve corresponder ao "site key" para o seu reCAPTCHA, que pode ser encontrado dentro do painel de reCAPTCHA.';
$CIDRAM['lang']['config_recaptcha_usemode'] = 'Define como CIDRAM deve usar reCAPTCHA (ver documentação).';
$CIDRAM['lang']['config_signatures_block_bogons'] = 'Bloquear bogon/martian CIDRs? Se você espera conexões para o seu site de dentro de sua rede local, de localhost, ou de seu LAN, esta directiva deve ser definido como false. Se você não esperar que esses tais conexões, esta directiva deve ser definido como true.';
$CIDRAM['lang']['config_signatures_block_cloud'] = 'Bloquear CIDRs identificado como pertencente a webhosting e/ou serviços em nuvem? Se você operar um serviço de API a partir do seu site ou se você espera outros sites para se conectar para o seu site, este deve ser definido como false. Se não, este deve ser definido como true.';
$CIDRAM['lang']['config_signatures_block_generic'] = 'Bloquear CIDRs geralmente recomendado para a lista negra? Isso abrange todas as assinaturas que não são marcados como sendo parte de qualquer um dos outros mais categorias de assinaturas mais específica.';
$CIDRAM['lang']['config_signatures_block_proxies'] = 'Bloquear CIDRs identificado como pertencente a serviços de proxy? Se você precisar que os usuários poderão acessar seu site dos serviços de proxy anônimos, este deve ser definido como false. De outra forma, se você não precisa de proxies anônimos, este deve ser definido como true como um meio de melhorar a segurança.';
$CIDRAM['lang']['config_signatures_block_spam'] = 'Bloquear CIDRs identificado como sendo de alto risco para spam? A menos que você tiver problemas ao fazê-lo, geralmente, esta deve sempre ser definido como true.';
$CIDRAM['lang']['config_signatures_default_tracktime'] = 'Quantos segundos para rastrear IPs banidos por módulos. Padrão = 604800 (1 semana).';
$CIDRAM['lang']['config_signatures_infraction_limit'] = 'Número máximo de infrações que um IP pode incorrer antes de ser banido por monitoração IP. Padrão = 10.';
$CIDRAM['lang']['config_signatures_ipv4'] = 'Uma lista dos arquivos de assinaturas IPv4 que CIDRAM deve tentar usar, delimitado por vírgulas.';
$CIDRAM['lang']['config_signatures_ipv6'] = 'Uma lista dos arquivos de assinaturas IPv6 que CIDRAM deve tentar usar, delimitado por vírgulas.';
$CIDRAM['lang']['config_signatures_modules'] = 'Uma lista de arquivos módulo a carregar depois de processamento as assinaturas IPv4/IPv6, delimitado por vírgulas.';
$CIDRAM['lang']['config_signatures_track_mode'] = 'Quando as infrações devem ser contadas? False = Quando os IPs são bloqueados por módulos. True = Quando os IPs são bloqueados por qualquer motivo.';
$CIDRAM['lang']['config_template_data_Magnification'] = 'Ampliação de fonte. Padrão = 1.';
$CIDRAM['lang']['config_template_data_css_url'] = 'URL de arquivo CSS para temas personalizados.';
$CIDRAM['lang']['config_template_data_theme'] = 'Tema padrão a ser usado para CIDRAM.';
$CIDRAM['lang']['field_activate'] = 'Ativar';
$CIDRAM['lang']['field_banned'] = 'Banido';
$CIDRAM['lang']['field_blocked'] = 'Bloqueado';
$CIDRAM['lang']['field_clear'] = 'Cancelar';
$CIDRAM['lang']['field_clear_all'] = 'Cancelar tudo';
$CIDRAM['lang']['field_clickable_link'] = 'Link clicável';
$CIDRAM['lang']['field_component'] = 'Componente';
$CIDRAM['lang']['field_create_new_account'] = 'Criar Nova Conta';
$CIDRAM['lang']['field_deactivate'] = 'Desativar';
$CIDRAM['lang']['field_delete_account'] = 'Deletar Conta';
$CIDRAM['lang']['field_delete_file'] = 'Deletar';
$CIDRAM['lang']['field_download_file'] = 'Descarregar';
$CIDRAM['lang']['field_edit_file'] = 'Editar';
$CIDRAM['lang']['field_expiry'] = 'Expiração';
$CIDRAM['lang']['field_false'] = 'False (Falso)';
$CIDRAM['lang']['field_file'] = 'Arquivo';
$CIDRAM['lang']['field_filename'] = 'Nome do arquivo: ';
$CIDRAM['lang']['field_filetype_directory'] = 'Diretório';
$CIDRAM['lang']['field_filetype_info'] = '{EXT} Arquivo';
$CIDRAM['lang']['field_filetype_unknown'] = 'Desconhecido';
$CIDRAM['lang']['field_first_seen'] = 'Visto pela primeira vez';
$CIDRAM['lang']['field_infractions'] = 'Infrações';
$CIDRAM['lang']['field_install'] = 'Instalar';
$CIDRAM['lang']['field_ip_address'] = 'Endereço IP';
$CIDRAM['lang']['field_latest_version'] = 'Última Versão';
$CIDRAM['lang']['field_log_in'] = 'Entrar';
$CIDRAM['lang']['field_new_name'] = 'Novo nome:';
$CIDRAM['lang']['field_nonclickable_text'] = 'Texto não-clicável';
$CIDRAM['lang']['field_ok'] = 'OK';
$CIDRAM['lang']['field_options'] = 'Opções';
$CIDRAM['lang']['field_password'] = 'Senha';
$CIDRAM['lang']['field_permissions'] = 'Permissões';
$CIDRAM['lang']['field_range'] = 'Alcance (Primeiro – Final)';
$CIDRAM['lang']['field_rename_file'] = 'Renomear';
$CIDRAM['lang']['field_reset'] = 'Reiniciar';
$CIDRAM['lang']['field_set_new_password'] = 'Definir Nova Senha';
$CIDRAM['lang']['field_size'] = 'Tamanho Total: ';
$CIDRAM['lang']['field_size_GB'] = 'GB';
$CIDRAM['lang']['field_size_KB'] = 'KB';
$CIDRAM['lang']['field_size_MB'] = 'MB';
$CIDRAM['lang']['field_size_TB'] = 'TB';
$CIDRAM['lang']['field_size_bytes'] = ['byte', 'bytes'];
$CIDRAM['lang']['field_status'] = 'Estado';
$CIDRAM['lang']['field_system_timezone'] = 'Usar o fuso horário padrão do sistema.';
$CIDRAM['lang']['field_tracking'] = 'Monitoração';
$CIDRAM['lang']['field_true'] = 'True (Verdadeiro)';
$CIDRAM['lang']['field_uninstall'] = 'Desinstalar';
$CIDRAM['lang']['field_update'] = 'Atualizar';
$CIDRAM['lang']['field_update_all'] = 'Atualize tudo';
$CIDRAM['lang']['field_upload_file'] = 'Carregar um novo ficheiro';
$CIDRAM['lang']['field_username'] = 'Nome de Usuário';
$CIDRAM['lang']['field_verify'] = 'Verifique isso';
$CIDRAM['lang']['field_verify_all'] = 'Verifique tudo';
$CIDRAM['lang']['field_your_version'] = 'Sua Versão';
$CIDRAM['lang']['header_login'] = 'Por favor faça o login para continuar.';
$CIDRAM['lang']['label_active_config_file'] = 'Arquivo de configuração ativo: ';
$CIDRAM['lang']['label_banned'] = 'Solicitações banidas';
$CIDRAM['lang']['label_blocked'] = 'Solicitações bloqueadas';
$CIDRAM['lang']['label_branch'] = 'Mais novo de estável de branch:';
$CIDRAM['lang']['label_check_modules'] = 'Também teste contra módulos.';
$CIDRAM['lang']['label_cidram'] = 'Versão do CIDRAM usada:';
$CIDRAM['lang']['label_clientinfo'] = 'Informação ao cliente:';
$CIDRAM['lang']['label_displaying'] = ['Exibindo <span class="txtRd">%s</span> entrada.', 'Exibindo <span class="txtRd">%s</span> entradas.'];
$CIDRAM['lang']['label_displaying_that_cite'] = ['Exibindo <span class="txtRd">%1$s</span> entrada que cita "%2$s".', 'Exibindo <span class="txtRd">%1$s</span> entradas que citam "%2$s".'];
$CIDRAM['lang']['label_expires'] = 'Expirará: ';
$CIDRAM['lang']['label_false_positive_risk'] = 'Risco de falsos positivos: ';
$CIDRAM['lang']['label_fmgr_cache_data'] = 'Dados de cache e arquivos temporários';
$CIDRAM['lang']['label_fmgr_disk_usage'] = 'Uso do disco pelo CIDRAM: ';
$CIDRAM['lang']['label_fmgr_free_space'] = 'Espaço livre no disco: ';
$CIDRAM['lang']['label_fmgr_total_disk_usage'] = 'Uso total do disco: ';
$CIDRAM['lang']['label_fmgr_total_space'] = 'Espaço total no disco: ';
$CIDRAM['lang']['label_fmgr_updates_metadata'] = 'Metadados para atualização de componentes';
$CIDRAM['lang']['label_hide'] = 'Ocultar';
$CIDRAM['lang']['label_never'] = 'Nunca';
$CIDRAM['lang']['label_os'] = 'Sistema operacional usada:';
$CIDRAM['lang']['label_other'] = 'Outros';
$CIDRAM['lang']['label_other-ActiveIPv4'] = 'Arquivos de assinaturas IPv4 ativos';
$CIDRAM['lang']['label_other-ActiveIPv6'] = 'Arquivos de assinaturas IPv6 ativos';
$CIDRAM['lang']['label_other-ActiveModules'] = 'Módulos ativos';
$CIDRAM['lang']['label_other-Since'] = 'Data de início';
$CIDRAM['lang']['label_php'] = 'Versão do PHP usada:';
$CIDRAM['lang']['label_reCAPTCHA'] = 'reCAPTCHA tentativas';
$CIDRAM['lang']['label_results'] = 'Resultados (%s entrada – %s rejeitado – %s aceito – %s mesclado – %s saída):';
$CIDRAM['lang']['label_sapi'] = 'SAPI usada:';
$CIDRAM['lang']['label_show'] = 'Mostrar';
$CIDRAM['lang']['label_signature_type'] = 'Tipo de assinatura:';
$CIDRAM['lang']['label_stable'] = 'Mais novo de estável:';
$CIDRAM['lang']['label_sysinfo'] = 'Informação do sistema:';
$CIDRAM['lang']['label_tests'] = 'Testes:';
$CIDRAM['lang']['label_total'] = 'Total';
$CIDRAM['lang']['label_unstable'] = 'Mais novo de instável:';
$CIDRAM['lang']['label_used_with'] = 'Usado com: ';
$CIDRAM['lang']['label_your_ip'] = 'Seu IP:';
$CIDRAM['lang']['label_your_ua'] = 'Seu UA:';
$CIDRAM['lang']['link_accounts'] = 'Contas';
$CIDRAM['lang']['link_cache_data'] = 'Dados de Cache';
$CIDRAM['lang']['link_cidr_calc'] = 'Calculadora CIDR';
$CIDRAM['lang']['link_config'] = 'Configuração';
$CIDRAM['lang']['link_documentation'] = 'Documentação';
$CIDRAM['lang']['link_file_manager'] = 'Gerenciador de Arquivos';
$CIDRAM['lang']['link_home'] = 'Página Principal';
$CIDRAM['lang']['link_ip_aggregator'] = 'Agregador IP';
$CIDRAM['lang']['link_ip_test'] = 'Teste IP';
$CIDRAM['lang']['link_ip_tracking'] = 'Monitoração IP';
$CIDRAM['lang']['link_logs'] = 'Arquivos de Registro';
$CIDRAM['lang']['link_range'] = 'Tabelas de Alcance';
$CIDRAM['lang']['link_sections_list'] = 'Lista de Seções';
$CIDRAM['lang']['link_statistics'] = 'Estatisticas';
$CIDRAM['lang']['link_textmode'] = 'Formatação de texto: <a href="%1$sfalse%2$s">Simples</a> – <a href="%1$strue%2$s">Chique</a> – <a href="%1$stally%2$s">Contagem</a>';
$CIDRAM['lang']['link_updates'] = 'Atualizações';
$CIDRAM['lang']['logs_logfile_doesnt_exist'] = 'Arquivo de registro selecionado não existe!';
$CIDRAM['lang']['logs_no_logfile_selected'] = 'Não qualquer arquivo de registro selecionado.';
$CIDRAM['lang']['logs_no_logfiles_available'] = 'Não quaisquer arquivos de registro disponíveis.';
$CIDRAM['lang']['max_login_attempts_exceeded'] = 'Número máximo de tentativas de login foi excedido; Acesso negado.';
$CIDRAM['lang']['previewer_days'] = 'Dias';
$CIDRAM['lang']['previewer_hours'] = 'Horas';
$CIDRAM['lang']['previewer_minutes'] = 'Minutos';
$CIDRAM['lang']['previewer_months'] = 'Meses';
$CIDRAM['lang']['previewer_seconds'] = 'Segundos';
$CIDRAM['lang']['previewer_weeks'] = 'Semanas';
$CIDRAM['lang']['previewer_years'] = 'Anos';
$CIDRAM['lang']['response_accounts_already_exists'] = 'Uma conta com esse nome já existe!';
$CIDRAM['lang']['response_accounts_created'] = 'Conta criada com sucesso!';
$CIDRAM['lang']['response_accounts_deleted'] = 'Conta deletada com sucesso!';
$CIDRAM['lang']['response_accounts_doesnt_exist'] = 'Essa conta não existe.';
$CIDRAM['lang']['response_accounts_password_updated'] = 'Senha atualizada com sucesso!';
$CIDRAM['lang']['response_activated'] = 'Ativado com sucesso.';
$CIDRAM['lang']['response_activation_failed'] = 'Falha ao ativar!';
$CIDRAM['lang']['response_checksum_error'] = 'Erro de checksum! Arquivo rejeitado!';
$CIDRAM['lang']['response_component_successfully_installed'] = 'Componente instalado com sucesso.';
$CIDRAM['lang']['response_component_successfully_uninstalled'] = 'Componente desinstalado com sucesso.';
$CIDRAM['lang']['response_component_successfully_updated'] = 'Componente atualizado com sucesso.';
$CIDRAM['lang']['response_component_uninstall_error'] = 'Ocorreu um erro ao tentar desinstalar o componente.';
$CIDRAM['lang']['response_configuration_updated'] = 'Configuração atualizada com sucesso.';
$CIDRAM['lang']['response_deactivated'] = 'Desativado com sucesso.';
$CIDRAM['lang']['response_deactivation_failed'] = 'Falha ao desativar!';
$CIDRAM['lang']['response_delete_error'] = 'Falha ao deletar!';
$CIDRAM['lang']['response_directory_deleted'] = 'Diretório deletado com sucesso!';
$CIDRAM['lang']['response_directory_renamed'] = 'Diretório renomeado com sucesso!';
$CIDRAM['lang']['response_error'] = 'Erro';
$CIDRAM['lang']['response_failed_to_install'] = 'Instalação falhada!';
$CIDRAM['lang']['response_failed_to_update'] = 'Atualização falhada!';
$CIDRAM['lang']['response_file_deleted'] = 'Arquivo deletado com sucesso!';
$CIDRAM['lang']['response_file_edited'] = 'Arquivo modificado com sucesso!';
$CIDRAM['lang']['response_file_renamed'] = 'Arquivo renomeado com sucesso!';
$CIDRAM['lang']['response_file_uploaded'] = 'Arquivo carregado com sucesso!';
$CIDRAM['lang']['response_login_invalid_password'] = 'Falha no login! Senha inválida!';
$CIDRAM['lang']['response_login_invalid_username'] = 'Falha no login! Esse usuário não existe!';
$CIDRAM['lang']['response_login_password_field_empty'] = 'Entrada de senha vazio!';
$CIDRAM['lang']['response_login_username_field_empty'] = 'Entrada de nome de usuário vazio!';
$CIDRAM['lang']['response_login_wrong_endpoint'] = 'Ponto final errado!';
$CIDRAM['lang']['response_no'] = 'Não';
$CIDRAM['lang']['response_possible_problem_found'] = 'Possível problema encontrado.';
$CIDRAM['lang']['response_rename_error'] = 'Falha ao renomear!';
$CIDRAM['lang']['response_statistics_cleared'] = 'Estatísticas apagadas.';
$CIDRAM['lang']['response_tracking_cleared'] = 'Monitoração cancelado.';
$CIDRAM['lang']['response_updates_already_up_to_date'] = 'Já atualizado.';
$CIDRAM['lang']['response_updates_not_installed'] = 'Componente não instalado!';
$CIDRAM['lang']['response_updates_not_installed_php'] = 'Componente não instalado (requer PHP &gt;= {V})!';
$CIDRAM['lang']['response_updates_outdated'] = 'Desatualizado!';
$CIDRAM['lang']['response_updates_outdated_manually'] = 'Desatualizado (por favor atualize manualmente)!';
$CIDRAM['lang']['response_updates_outdated_php_version'] = 'Desatualizado (requer PHP &gt;= {V})!';
$CIDRAM['lang']['response_updates_unable_to_determine'] = 'Não foi possível determinar.';
$CIDRAM['lang']['response_upload_error'] = 'Falha ao carregar!';
$CIDRAM['lang']['response_verification_failed'] = 'Verificação falhada! Componente pode estar corrompido.';
$CIDRAM['lang']['response_verification_success'] = 'Verificação sucesso! Não foram encontrados problemas.';
$CIDRAM['lang']['response_yes'] = 'Sim';
$CIDRAM['lang']['state_async_deny'] = 'Permissões não adequadas para executar solicitações assíncronas. Tente fazer o login novamente.';
$CIDRAM['lang']['state_cache_is_empty'] = 'O cache está vazio.';
$CIDRAM['lang']['state_complete_access'] = 'Acesso completo';
$CIDRAM['lang']['state_component_is_active'] = 'Componente está ativo.';
$CIDRAM['lang']['state_component_is_inactive'] = 'Componente está inativo.';
$CIDRAM['lang']['state_component_is_provisional'] = 'Componente está provisório.';
$CIDRAM['lang']['state_default_password'] = 'Atenção: Usando senha padrão!';
$CIDRAM['lang']['state_ignored'] = 'Ignorado';
$CIDRAM['lang']['state_loading'] = 'Carregando...';
$CIDRAM['lang']['state_loadtime'] = 'Pedido de página concluído em <span class="txtRd">%s</span> segundos.';
$CIDRAM['lang']['state_logged_in'] = 'Conectado.';
$CIDRAM['lang']['state_logs_access_only'] = 'Acesso aos arquivos de registro somente';
$CIDRAM['lang']['state_maintenance_mode'] = 'Atenção: O modo de manutenção está ativado!';
$CIDRAM['lang']['state_password_not_valid'] = 'Atenção: Esta conta não está usando uma senha válida!';
$CIDRAM['lang']['state_risk_high'] = 'Alto';
$CIDRAM['lang']['state_risk_low'] = 'Baixo';
$CIDRAM['lang']['state_risk_medium'] = 'Médio';
$CIDRAM['lang']['state_sl_totals'] = 'Totais (Assinaturas: <span class="txtRd">%s</span> – Seções de assinatura: <span class="txtRd">%s</span> – Arquivos de assinatura: <span class="txtRd">%s</span>).';
$CIDRAM['lang']['state_tracking'] = ['Atualmente monitorando %s IP.', 'Atualmente monitorando %s IPs.'];
$CIDRAM['lang']['switch-hide-non-outdated-set-false'] = 'Não ocultar não desatualizado';
$CIDRAM['lang']['switch-hide-non-outdated-set-true'] = 'Ocultar não desatualizado';
$CIDRAM['lang']['switch-hide-unused-set-false'] = 'Não ocultar não utilizado';
$CIDRAM['lang']['switch-hide-unused-set-true'] = 'Ocultar não utilizado';
$CIDRAM['lang']['switch-tracking-blocked-already-set-false'] = 'Não verifique contra arquivos de assinatura';
$CIDRAM['lang']['switch-tracking-blocked-already-set-true'] = 'Verifique contra arquivos de assinatura';
$CIDRAM['lang']['switch-tracking-hide-banned-blocked-set-false'] = 'Não ocultar IPs banidos/bloqueados';
$CIDRAM['lang']['switch-tracking-hide-banned-blocked-set-true'] = 'Ocultar IPs banidos/bloqueados';
$CIDRAM['lang']['tip_accounts'] = 'Olá, {username}.<br />A página de contas permite que você controle quem pode acessar o CIDRAM front-end.';
$CIDRAM['lang']['tip_cache_data'] = 'Olá, {username}.<br />Aqui você pode rever o conteúdo do cache.';
$CIDRAM['lang']['tip_cidr_calc'] = 'Olá, {username}.<br />A calculadora CIDR permite calcular quais CIDRs um endereço IP pertence.';
$CIDRAM['lang']['tip_config'] = 'Olá, {username}.<br />A página de configuração permite que você modifique a configuração do CIDRAM a partir do front-end.';
$CIDRAM['lang']['tip_custom_ua'] = 'Digite o agente do usuário (user agent) aqui (opcional).';
$CIDRAM['lang']['tip_donate'] = 'CIDRAM é oferecido gratuitamente, mas se você quiser doar para o projeto, você pode fazê-lo clicando no botão doar.';
$CIDRAM['lang']['tip_enter_ip_here'] = 'Insira o IP aqui.';
$CIDRAM['lang']['tip_enter_ips_here'] = 'Insira os IPs aqui.';
$CIDRAM['lang']['tip_fe_cookie_warning'] = 'Nota: CIDRAM usa um cookie para autenticar logins. Ao efetuar o login, você dá seu consentimento para que um cookie seja criado e armazenado pelo seu navegador.';
$CIDRAM['lang']['tip_file_manager'] = 'Olá, {username}.<br />O gerenciador de arquivos permite deletar, editar, carregar e descarregar arquivos. Use com cuidado (você poderia quebrar sua instalação com este).';
$CIDRAM['lang']['tip_home'] = 'Olá, {username}.<br />Esta é a página principal do CIDRAM front-end. Selecione um link no menu de navegação à esquerda para continuar.';
$CIDRAM['lang']['tip_ip_aggregator'] = 'Olá, {username}.<br />O agregador IP permite que você expresse IPs e CIDRs da maneira mais pequena possível. Insira os dados a serem agregados e pressione "OK".';
$CIDRAM['lang']['tip_ip_test'] = 'Olá, {username}.<br />A página para teste IP permite que você teste se os endereços IP são bloqueados pelas assinaturas atualmente instaladas.';
$CIDRAM['lang']['tip_ip_test_module_switch'] = '(Quando não selecionado, somente os arquivos de assinatura serão testados contra).';
$CIDRAM['lang']['tip_ip_tracking'] = 'Olá, {username}.<br />A página de monitoração IP permite verificar o status de monitoração de endereços IP, para verificar quais deles foram banidos, e para cancelar monitoração para eles se você quiser fazê-lo.';
$CIDRAM['lang']['tip_login'] = 'Nome de usuário padrão: <span class="txtRd">admin</span> – Senha padrão: <span class="txtRd">password</span>';
$CIDRAM['lang']['tip_logs'] = 'Olá, {username}.<br />Selecionar um arquivo de registro da lista abaixo para visualizar o conteúdo do arquivo de registro.';
$CIDRAM['lang']['tip_range'] = 'Olá, {username}.<br />Esta página mostra algumas informações estatísticas básicas sobre os IPs abrangidos pelos arquivos de assinatura ativos no momento.';
$CIDRAM['lang']['tip_sections_list'] = 'Olá, {username}.<br />Esta página lista quais seções existem nos arquivos de assinatura atualmente ativos.';
$CIDRAM['lang']['tip_see_the_documentation'] = 'Consulte a <a href="https://github.com/CIDRAM/CIDRAM/blob/master/_docs/readme.pt.md#SECTION6">documentação</a> para obter informações sobre as várias diretrizes de configuração e seus objetivos.';
$CIDRAM['lang']['tip_statistics'] = 'Olá, {username}.<br />Esta página mostra algumas estatísticas de uso básicas relativas à instalação do CIDRAM.';
$CIDRAM['lang']['tip_statistics_disabled'] = 'Nota: O monitoramento de estatísticas está desativado atualmente, mas pode ser ativado através da página de configuração.';
$CIDRAM['lang']['tip_updates'] = 'Olá, {username}.<br />A página de atualizações permite que você instale, desinstale, e atualize os vários componentes do CIDRAM (o pacote principal, assinaturas, arquivos de L10N, etc).';
$CIDRAM['lang']['title_accounts'] = 'CIDRAM – Contas';
$CIDRAM['lang']['title_cache_data'] = 'CIDRAM – Dados de Cache';
$CIDRAM['lang']['title_cidr_calc'] = 'CIDRAM – Calculadora CIDR';
$CIDRAM['lang']['title_config'] = 'CIDRAM – Configuração';
$CIDRAM['lang']['title_file_manager'] = 'CIDRAM – Gerenciador de Arquivos';
$CIDRAM['lang']['title_home'] = 'CIDRAM – Página Principal';
$CIDRAM['lang']['title_ip_aggregator'] = 'CIDRAM – Agregador IP';
$CIDRAM['lang']['title_ip_test'] = 'CIDRAM – Teste IP';
$CIDRAM['lang']['title_ip_tracking'] = 'CIDRAM – Monitoração IP';
$CIDRAM['lang']['title_login'] = 'CIDRAM – Login';
$CIDRAM['lang']['title_logs'] = 'CIDRAM – Arquivos de Registro';
$CIDRAM['lang']['title_range'] = 'CIDRAM – Tabelas de Alcance';
$CIDRAM['lang']['title_sections_list'] = 'CIDRAM – Lista de Seções';
$CIDRAM['lang']['title_statistics'] = 'CIDRAM – Estatisticas';
$CIDRAM['lang']['title_updates'] = 'CIDRAM – Atualizações';
$CIDRAM['lang']['warning'] = 'Atenção:';
$CIDRAM['lang']['warning_php_1'] = 'O sua versão PHP não está mais ativamente apoiada! Atualizando é recomendado!';
$CIDRAM['lang']['warning_php_2'] = 'O sua versão PHP é severamente vulnerável! Atualizando é fortemente recomendado!';
$CIDRAM['lang']['warning_signatures_1'] = 'Nenhum arquivo de assinaturas está ativo!';

$CIDRAM['lang']['info_some_useful_links'] = 'Alguns links úteis:<ul>
            <li><a href="https://github.com/CIDRAM/CIDRAM/issues">Problemas de CIDRAM @ GitHub</a> – Página de problemas para CIDRAM (apoio, assistência, etc).</li>
            <li><a href="https://wordpress.org/plugins/cidram/">CIDRAM @ WordPress.org</a> – WordPress plugin para CIDRAM.</li>
            <li><a href="https://sourceforge.net/projects/cidram/">CIDRAM @ SourceForge</a> – Espelho de download alternativo para CIDRAM.</li>
            <li><a href="https://websectools.com/">WebSecTools.com</a> – Uma coleção de ferramentas de webmaster simples para proteger sites.</li>
            <li><a href="https://macmathan.info/blocklists">Listas de bloqueios @ MacMathan.info</a> – Contém listas de bloqueios opcionais que podem ser adicionados ao CIDRAM para bloquear quaisquer países indesejados de acessar seu site.</li>
            <li><a href="https://www.facebook.com/groups/2204685680/">Global PHP Group @ Facebook</a> – PHP recursos de aprendizagem e discussão.</li>
            <li><a href="https://php.earth/">PHP.earth</a> – PHP recursos de aprendizagem e discussão.</li>
            <li><a href="http://bgp.he.net/">Hurricane Electric BGP Toolkit</a> – Obter CIDRs de ASNs, Determinar relações de ASNs, descobrir ASNs com base em nomes de rede, etc.</li>
            <li><a href="https://www.stopforumspam.com/forum/">Fórum @ Stop Forum Spam</a> – Fórum de discussão útil sobre como parar o spam do fórum.</li>
            <li><a href="https://radar.qrator.net/">Radar por Qrator</a> – Ferramenta útil para verificar a conectividade de ASNs bem como para várias outras informações sobre ASNs.</li>
            <li><a href="http://www.ipdeny.com/ipblocks/">IP países blocos @ IPdeny</a> – Um serviço fantástico e preciso para gerar assinaturas para os países.</li>
            <li><a href="https://www.google.com/transparencyreport/safebrowsing/malware/">Google Malware Dashboard</a> – Exibe relatórios sobre as taxas de infecção de malware para ASNs.</li>
            <li><a href="https://www.spamhaus.org/statistics/botnet-asn/">O Projeto Spamhaus</a> – Exibe relatórios sobre taxas de infecção por botnets para ASNs.</li>
            <li><a href="https://www.abuseat.org/public/asn.html">Lista de Bloqueio Composto @ Abuseat.org</a> – Exibe relatórios sobre taxas de infecção por botnets para ASNs.</li>
            <li><a href="https://abuseipdb.com/">AbuseIPDB</a> – Mantém um banco de dados de IPs abusivos conhecidos; Fornece uma API para verificar e reportar IPs.</li>
            <li><a href="https://www.megarbl.net/index.php">MegaRBL.net</a> – Mantém listagens de spammers conhecidos; Útil para verificar as atividades de spam por IPs/ASNs.</li>
            <li><a href="https://maikuolan.github.io/Vulnerability-Charts/">Mapas de Vulnerabilidade</a> – Lista as versões seguras/inseguras de vários pacotes (PHP, HHVM, etc).</li>
            <li><a href="https://maikuolan.github.io/Compatibility-Charts/">Mapas de Compatibilidade</a> – Lista informações de compatibilidade para vários pacotes (CIDRAM, phpMussel, etc).</li>
        </ul>';
