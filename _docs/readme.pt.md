## Documentação para CIDRAM (Português).

### Conteúdo
- 1. [PREÂMBULO](#SECTION1)
- 2. [COMO INSTALAR](#SECTION2)
- 3. [COMO USAR](#SECTION3)
- 4. [GESTÃO DE FRONT-END](#SECTION4)
- 5. [ARQUIVOS INCLUÍDOS NESTE PACOTE](#SECTION5)
- 6. [OPÇÕES DE CONFIGURAÇÃO](#SECTION6)
- 7. [FORMATO DE ASSINATURAS](#SECTION7)
- 8. [PERGUNTAS MAIS FREQUENTES (FAQ)](#SECTION8)

*Nota relativa às traduções: Em caso de erros (por exemplo, discrepâncias entre as traduções, erros de digitação, etc), a versão em inglês do README é considerada a versão original e autorizada. Se você encontrar algum erro, sua ajuda em corrigi-los seria bem-vinda.*

---


### 1. <a name="SECTION1"></a>PREÂMBULO

CIDRAM (Classless Inter-Domain Routing Access Manager) é um script PHP projetados para proteger sites por bloqueando solicitações provenientes de endereços IP considerado como sendo fontes de tráfego indesejável, incluindo (mas não limitado a) o tráfego dos pontos de acesso não-humanos, serviços em nuvem, spambots, raspadores/scrapers, etc. Ele faz isso via calculando as possíveis CIDRs dos endereços IP fornecida a partir de solicitações de entrada e em seguida tentando comparar estas possíveis CIDRs contra os seus arquivos de assinaturas (estas arquivos de assinaturas contêm listas de CIDRs de endereços IP considerado como sendo fontes de tráfego indesejável); Se forem encontradas correspondências, os solicitações estão bloqueadas.

*(Vejo: [O que é um "CIDR"?](#WHAT_IS_A_CIDR)).*

CIDRAM COPYRIGHT 2016 e além GNU/GPLv2 através do Caleb M (Maikuolan).

Este script é livre software; você pode redistribuí-lo e/ou modificá-lo de acordo com os termos da GNU General Public License como publicada pela Free Software Foundation; tanto a versão 2 da Licença, ou (em sua opção) qualquer versão posterior. Este script é distribuído na esperança que possa ser útil, mas SEM QUALQUER GARANTIA; sem mesmo a implícita garantia de COMERCIALIZAÇÃO ou ADEQUAÇÃO A UM DETERMINADO FIM. Consulte a GNU General Public License para obter mais detalhes, localizado no `LICENSE.txt` arquivo e disponível também desde:
- <http://www.gnu.org/licenses/>.
- <http://opensource.org/licenses/>.

Este documento e seu associado pacote pode ser baixado gratuitamente de [GitHub](https://github.com/Maikuolan/CIDRAM/).

---


### 2. <a name="SECTION2"></a>COMO INSTALAR

#### 2.0 INSTALANDO MANUALMENTE

1) Por o seu lendo isso, eu estou supondo que você já tenha baixado uma cópia arquivada do script, descomprimido seu conteúdo e tê-lo sentado em algum lugar em sua máquina local. A partir daqui, você vai querer determinar onde no seu host ou CMS pretende colocar esses conteúdos. Um diretório como `/public_html/cidram/` ou semelhante (porém, está não importa qual você escolher, assumindo que é seguro e algo você esteja feliz com) vai bastará.

2) Renomear `config.ini.RenameMe` para `config.ini` (localizado dentro `vault`), e opcionalmente (fortemente recomendado para avançados usuários, mas não recomendado para iniciantes ou para os inexperientes), abri-lo (este arquivo contém todas as directivas disponíveis para CIDRAM; acima de cada opção deve ser um breve comentário descrevendo o que faz e para que serve). Ajuste essas opções de como você vê o ajuste, conforme o que for apropriado para sua configuração específica. Salve o arquivo, fechar.

3) Carregar os conteúdos (CIDRAM e seus arquivos) para o diretório que você tinha decidido anteriormente (você não requerer os `*.txt`/`*.md` arquivos incluídos, mas principalmente, você deve carregar tudo).

4) CHMOD o `vault` diretório para "755" (se houver problemas, você pode tentar "777"; isto é menos seguro, embora). O principal diretório armazenar o conteúdo (o que você escolheu anteriormente), geralmente, pode ser deixado sozinho, mas o CHMOD status deve ser verificado se você já teve problemas de permissões no passado no seu sistema (por padrão, deve ser algo como "755").

5) Seguida, você vai precisar "enganchar" CIDRAM ao seu sistema ou CMS. Existem várias diferentes maneiras em que você pode "enganchar" scripts como CIDRAM ao seu sistema ou CMS, mas o mais fácil é simplesmente incluir o script no início de um núcleo arquivo de seu sistema ou CMS (uma que vai geralmente sempre ser carregado quando alguém acessa qualquer página através de seu site) utilizando um `require` ou `include` comando. Normalmente, isso vai ser algo armazenado em um diretório como `/includes`, `/assets` ou `/functions`, e muitas vezes, ser nomeado algo como `init.php`, `common_functions.php`, `functions.php` ou semelhante. Você precisará determinar qual arquivo isso é para a sua situação; Se você encontrar dificuldades em determinar isso por si mesmo, para assistência, visite a página de problemas CIDRAM no GitHub. Para fazer isso [usar `require` ou `include`], insira a seguinte linha de código para o início desse núcleo arquivo, substituindo a string contida dentro das aspas com o exato endereço do `loader.php` arquivo (endereço local, não o endereço HTTP; será semelhante ao vault endereço mencionado anteriormente).

`<?php require '/user_name/public_html/cidram/loader.php'; ?>`

Salve o arquivo, fechar, recarregar-lo.

-- OU ALTERNATIVAMENTE --

Se você é usando um Apache web servidor e se você tem acesso a `php.ini`, você pode usar o `auto_prepend_file` directiva para pré-carga CIDRAM sempre que qualquer solicitação para PHP é feito. Algo como:

`auto_prepend_file = "/user_name/public_html/cidram/loader.php"`

Ou isso no `.htaccess` arquivo:

`php_value auto_prepend_file "/user_name/public_html/cidram/loader.php"`

6) Isso é tudo! :-)

#### 2.1 INSTALANDO COM COMPOSER

[CIDRAM está registrado no Packagist](https://packagist.org/packages/maikuolan/cidram), e entao, se você estiver familiarizado com o Composer, poderá usar o Composer para instalar o CIDRAM (você ainda precisará preparar a configuração e ganchos embora; consulte "instalando manualmente" as etapas 2 e 5).

`composer require maikuolan/cidram`

#### 2.2 INSTALANDO PARA WORDPRESS

Se você quiser usar o CIDRAM com o WordPress, você pode ignorar todas as instruções acima. [CIDRAM está registrado como um plugin com o banco de dados de plugins do WordPress](https://wordpress.org/plugins/cidram/), e você pode instalar CIDRAM diretamente do painel de plugins. Você pode instalá-lo da mesma maneira que qualquer outro plugin, e nenhuma etapa de adição é necessária. Assim como com os outros métodos de instalação, você pode personalizar sua instalação por meio de modificando o conteúdo do `config.ini` arquivo ou usando o front-end página de atualizações. Se você ativar o front-end e atualizar CIDRAM usando a página de atualizações, isso será sincronizado automaticamente com as informações de versão do plugin exibidas no painel de plugins.

---


### 3. <a name="SECTION3"></a>COMO USAR

CIDRAM deve bloquear automaticamente as solicitações indesejáveis para o seu site sem necessidade de qualquer assistência manual, Além de sua instalação inicial.

A atualização é feita manualmente, e você pode personalizar a sua configuração e personalizar quais CIDRs são bloqueados por modificando seu arquivo de configuração e/ou seus arquivos de assinaturas.

Se você encontrar quaisquer falsos positivos, entre em contato comigo para me informar sobre isso.

---


### 4. <a name="SECTION4"></a>GESTÃO DE FRONT-END

#### 4.0 O QUE É O FRONT-END.

O front-end fornece uma maneira conveniente e fácil de manter, gerenciar e atualizar sua instalação CIDRAM. Você pode visualizar, compartilhar e baixar arquivos de log através da página de logs, você pode modificar a configuração através da página de configuração, você pode instalar e desinstalar componentes através da página de atualizações, e você pode carregar, baixar e modificar arquivos no seu vault através do gerenciador de arquivos.

O front-end é desativado por padrão para evitar acesso não autorizado (acesso não autorizado pode ter consequências significativas para o seu site e para a sua segurança). Instruções para habilitá-lo estão incluídas abaixo deste parágrafo.

#### 4.1 COMO HABILITAR O FRONT-END.

1) Localize a directiva `disable_frontend` dentro `config.ini`, e defini-lo como `false` (ele será `true` por padrão).

2) Acesse o `loader.php` do seu navegador (p.e., `http://localhost/cidram/loader.php`).

3) Faça login com o nome de usuário e a senha padrão (admin/password).

Nota: Depois de efetuar login pela primeira vez, a fim de impedir o acesso não autorizado ao front-end, você deve imediatamente alterar seu nome de usuário e senha! Isto é muito importante, porque é possível fazer upload de código PHP arbitrário para o seu site através do front-end.

#### 4.2 COMO USAR O FRONT-END.

As instruções são fornecidas em cada página do front-end, para explicar a maneira correta de usá-lo e sua finalidade pretendida. Se precisar de mais explicações ou qualquer assistência especial, entre em contato com o suporte. Alternativamente, existem alguns vídeos disponíveis no YouTube que podem ajudar por meio de demonstração.


---


### 5. <a name="SECTION5"></a>ARQUIVOS INCLUÍDOS NESTE PACOTE

O seguinte está uma lista de todos os arquivos que deveria sido incluídos na arquivada cópia desse script quando você baixado-lo, todos os arquivos que podem ser potencialmente criados como resultado de seu uso deste script, juntamente com uma breve descrição do que todos esses arquivos são por.

Arquivo | Descrição
----|----
/_docs/ | Documentação diretório (contém vários arquivos).
/_docs/readme.ar.md | Documentação Árabe.
/_docs/readme.de.md | Documentação Alemão.
/_docs/readme.en.md | Documentação Inglês.
/_docs/readme.es.md | Documentação Espanhol.
/_docs/readme.fr.md | Documentação Francesa.
/_docs/readme.id.md | Documentação Indonésio.
/_docs/readme.it.md | Documentação Italiano.
/_docs/readme.ja.md | Documentação Japonesa.
/_docs/readme.ko.md | Documentação Coreana.
/_docs/readme.nl.md | Documentação Holandês.
/_docs/readme.pt.md | Documentação Português.
/_docs/readme.ru.md | Documentação Russo.
/_docs/readme.ur.md | Documentação Urdu.
/_docs/readme.vi.md | Documentação Vietnamita.
/_docs/readme.zh-TW.md | Documentação Chinês (tradicional).
/_docs/readme.zh.md | Documentação Chinês (simplificado).
/vault/ | Vault diretório (contém vários arquivos).
/vault/fe_assets/ | Dados front-end.
/vault/fe_assets/.htaccess | Um hipertexto acesso arquivo (neste caso, para proteger confidenciais arquivos pertencentes ao script contra serem acessados por fontes não autorizadas).
/vault/fe_assets/_accounts.html | Um modelo HTML para o front-end página de contas.
/vault/fe_assets/_accounts_row.html | Um modelo HTML para o front-end página de contas.
/vault/fe_assets/_cidr_calc.html | Um modelo HTML para a calculadora CIDR.
/vault/fe_assets/_cidr_calc_row.html | Um modelo HTML para a calculadora CIDR.
/vault/fe_assets/_config.html | Um modelo HTML para o front-end página de configuração.
/vault/fe_assets/_config_row.html | Um modelo HTML para o front-end página de configuração.
/vault/fe_assets/_files.html | Um modelo HTML para o gerenciador de arquivos.
/vault/fe_assets/_files_edit.html | Um modelo HTML para o gerenciador de arquivos.
/vault/fe_assets/_files_rename.html | Um modelo HTML para o gerenciador de arquivos.
/vault/fe_assets/_files_row.html | Um modelo HTML para o gerenciador de arquivos.
/vault/fe_assets/_home.html | Um modelo HTML para o front-end página principal.
/vault/fe_assets/_ip_test.html | Um modelo HTML para a página para testar IPs.
/vault/fe_assets/_ip_test_row.html | Um modelo HTML para a página para testar IPs.
/vault/fe_assets/_ip_tracking.html | Um modelo HTML para a página de monitoração IP.
/vault/fe_assets/_ip_tracking_row.html | Um modelo HTML para a página de monitoração IP.
/vault/fe_assets/_login.html | Um modelo HTML para o front-end página login.
/vault/fe_assets/_logs.html | Um modelo HTML para o front-end página para os arquivos de registro.
/vault/fe_assets/_nav_complete_access.html | Um modelo HTML para os links de navegação para o front-end, para aqueles com acesso completo.
/vault/fe_assets/_nav_logs_access_only.html | Um modelo HTML para os links de navegação para o front-end, para aqueles com acesso aos arquivos de registro somente.
/vault/fe_assets/_updates.html | Um modelo HTML para o front-end página de atualizações.
/vault/fe_assets/_updates_row.html | Um modelo HTML para o front-end página de atualizações.
/vault/fe_assets/frontend.css | Folha de estilo CSS para o front-end.
/vault/fe_assets/frontend.dat | Banco de dados para o front-end (contém informações de contas, informações de sessões, eo cache; gerado só se o front-end está habilitado e usado).
/vault/fe_assets/frontend.html | O arquivo modelo HTML principal para o front-end.
/vault/fe_assets/icons.php | Módulo de ícones (usado pelo gerenciador de arquivos do front-end).
/vault/fe_assets/pips.php | Módulo de pips (usado pelo gerenciador de arquivos do front-end).
/vault/lang/ | Contém dados lingüísticos.
/vault/lang/.htaccess | Um hipertexto acesso arquivo (neste caso, para proteger confidenciais arquivos pertencentes ao script contra serem acessados por fontes não autorizadas).
/vault/lang/lang.ar.cli.php | Dados lingüísticos Árabe para CLI.
/vault/lang/lang.ar.fe.php | Dados lingüísticos Árabe para o front-end.
/vault/lang/lang.ar.php | Dados lingüísticos Árabe.
/vault/lang/lang.de.cli.php | Dados lingüísticos Alemão para CLI.
/vault/lang/lang.de.fe.php | Dados lingüísticos Alemão para o front-end.
/vault/lang/lang.de.php | Dados lingüísticos Alemão.
/vault/lang/lang.en.cli.php | Dados lingüísticos Inglês para CLI.
/vault/lang/lang.en.fe.php | Dados lingüísticos Inglês para o front-end.
/vault/lang/lang.en.php | Dados lingüísticos Inglês.
/vault/lang/lang.es.cli.php | Dados lingüísticos Espanhol para CLI.
/vault/lang/lang.es.fe.php | Dados lingüísticos Espanhol para o front-end.
/vault/lang/lang.es.php | Dados lingüísticos Espanhol.
/vault/lang/lang.fr.cli.php | Dados lingüísticos Francesa para CLI.
/vault/lang/lang.fr.fe.php | Dados lingüísticos Francesa para o front-end.
/vault/lang/lang.fr.php | Dados lingüísticos Francesa.
/vault/lang/lang.hi.cli.php | Dados lingüísticos Hindi para CLI.
/vault/lang/lang.hi.fe.php | Dados lingüísticos Hindi para o front-end.
/vault/lang/lang.hi.php | Dados lingüísticos Hindi.
/vault/lang/lang.id.cli.php | Dados lingüísticos Indonésio para CLI.
/vault/lang/lang.id.fe.php | Dados lingüísticos Indonésio para o front-end.
/vault/lang/lang.id.php | Dados lingüísticos Indonésio.
/vault/lang/lang.it.cli.php | Dados lingüísticos Italiano para CLI.
/vault/lang/lang.it.fe.php | Dados lingüísticos Italiano para o front-end.
/vault/lang/lang.it.php | Dados lingüísticos Italiano.
/vault/lang/lang.ja.cli.php | Dados lingüísticos Japonês para CLI.
/vault/lang/lang.ja.fe.php | Dados lingüísticos Japonês para o front-end.
/vault/lang/lang.ja.php | Dados lingüísticos Japonês.
/vault/lang/lang.ko.cli.php | Dados lingüísticos Coreano para CLI.
/vault/lang/lang.ko.fe.php | Dados lingüísticos Coreano para o front-end.
/vault/lang/lang.ko.php | Dados lingüísticos Coreano.
/vault/lang/lang.nl.cli.php | Dados lingüísticos Holandês para CLI.
/vault/lang/lang.nl.fe.php | Dados lingüísticos Holandês para o front-end.
/vault/lang/lang.nl.php | Dados lingüísticos Holandês.
/vault/lang/lang.pt.cli.php | Dados lingüísticos Português para CLI.
/vault/lang/lang.pt.fe.php | Dados lingüísticos Português para o front-end.
/vault/lang/lang.pt.php | Dados lingüísticos Português.
/vault/lang/lang.ru.cli.php | Dados lingüísticos Russo para CLI.
/vault/lang/lang.ru.fe.php | Dados lingüísticos Russo para o front-end.
/vault/lang/lang.ru.php | Dados lingüísticos Russo.
/vault/lang/lang.th.cli.php | Dados lingüísticos Tailandês para CLI.
/vault/lang/lang.th.fe.php | Dados lingüísticos Tailandês para o front-end.
/vault/lang/lang.th.php | Dados lingüísticos Tailandês.
/vault/lang/lang.tr.cli.php | Dados lingüísticos Turco para CLI.
/vault/lang/lang.tr.fe.php | Dados lingüísticos Turco para o front-end.
/vault/lang/lang.tr.php | Dados lingüísticos Turco.
/vault/lang/lang.ur.cli.php | Dados lingüísticos Urdu para CLI.
/vault/lang/lang.ur.fe.php | Dados lingüísticos Urdu para o front-end.
/vault/lang/lang.ur.php | Dados lingüísticos Urdu.
/vault/lang/lang.vi.cli.php | Dados lingüísticos Vietnamita para CLI.
/vault/lang/lang.vi.fe.php | Dados lingüísticos Vietnamita para o front-end.
/vault/lang/lang.vi.php | Dados lingüísticos Vietnamita.
/vault/lang/lang.zh-tw.cli.php | Dados lingüísticos Chinês (tradicional) para CLI.
/vault/lang/lang.zh-tw.fe.php | Dados lingüísticos Chinês (tradicional) para o front-end.
/vault/lang/lang.zh-tw.php | Dados lingüísticos Chinês (tradicional).
/vault/lang/lang.zh.cli.php | Dados lingüísticos Chinês (simplificado) para CLI.
/vault/lang/lang.zh.fe.php | Dados lingüísticos Chinês (simplificado) para o front-end.
/vault/lang/lang.zh.php | Dados lingüísticos Chinês (simplificado).
/vault/.htaccess | Um hipertexto acesso arquivo (neste caso, para proteger confidenciais arquivos pertencentes ao script contra serem acessados por fontes não autorizadas).
/vault/cache.dat | Dados de cache.
/vault/cidramblocklists.dat | Contém informações relativas às listas opcionais para bloqueando países fornecido por Macmathan; Usado pelo recurso atualizações fornecidas pelo front-end.
/vault/cli.php | Módulo de CLI.
/vault/components.dat | Contém informações relativas aos vários componentes de CIDRAM; Usado pelo recurso atualizações fornecidas pelo front-end.
/vault/config.ini.RenameMe | Arquivo de configuração; Contém todas as opções de configuração para CIDRAM, dizendo-lhe o que fazer e como operar corretamente (renomear para ativar).
/vault/config.php | Módulo de configuração.
/vault/config.yaml | Arquivo de valores padrão para a configuração; Contém valores padrão para a configuração de CIDRAM.
/vault/frontend.php | Módulo do front-end.
/vault/functions.php | Arquivo de funções.
/vault/hashes.dat | Contém uma lista de hashes aceitos (pertinente para o funcionalidade reCAPTCHA; só gerou se a funcionalidade reCAPTCHA está habilitado).
/vault/ignore.dat | Arquivo de ignorados (usado para especificar quais seções de assinaturas CIDRAM deve ignorar).
/vault/ipbypass.dat | Contém uma lista de bypasses IP (pertinente para o funcionalidade reCAPTCHA; só gerou se a funcionalidade reCAPTCHA está habilitado).
/vault/ipv4.dat | Arquivo de assinaturas para IPv4 (serviços em nuvem indesejados e pontos de extremidade não-humanos).
/vault/ipv4_bogons.dat | Arquivo de assinaturas para IPv4 (bogon/marciano CIDRs).
/vault/ipv4_custom.dat.RenameMe | Arquivo de assinaturas personalizadas para IPv4 (renomear para ativar).
/vault/ipv4_isps.dat | Arquivo de assinaturas para IPv4 (ISPs perigosos e propensos a spam).
/vault/ipv4_other.dat | Arquivo de assinaturas para IPv4 (CIDRs para proxies, VPNs e outros diversos serviços indesejados).
/vault/ipv6.dat | Arquivo de assinaturas para IPv6 (serviços em nuvem indesejados e pontos de extremidade não-humanos).
/vault/ipv6_bogons.dat | Arquivo de assinaturas para IPv6 (bogon/marciano CIDRs).
/vault/ipv6_custom.dat.RenameMe | Arquivo de assinaturas personalizadas para IPv6 (renomear para ativar).
/vault/ipv6_isps.dat | Arquivo de assinaturas para IPv6 (ISPs perigosos e propensos a spam).
/vault/ipv6_other.dat | Arquivo de assinaturas para IPv6 (CIDRs para proxies, VPNs e outros diversos serviços indesejados).
/vault/lang.php | Dados lingüísticos.
/vault/modules.dat | Contém informações relativas os vários módulos para CIDRAM; Usado pelo recurso atualizações fornecidas pelo front-end.
/vault/outgen.php | Gerador de saída.
/vault/php5.4.x.php | Polyfills para PHP 5.4.X (necessário para compatibilidade reversa com PHP 5.4.X; seguro para deletar por versões de PHP mais recentes).
/vault/recaptcha.php | Módulo reCAPTCHA.
/vault/rules_as6939.php | Arquivo de regras personalizadas para AS6939.
/vault/rules_softlayer.php | Arquivo de regras personalizadas para Soft Layer.
/vault/rules_specific.php | Arquivo de regras personalizadas alguns CIDRs específicos.
/vault/salt.dat | Arquivo de sal (utilizada por algumas funcionalidades periférica; gerado apenas se necessário).
/vault/template_custom.html | Arquivo de modelo; Modelo para a saída de HTML produzido pela gerador de saída para CIDRAM.
/vault/template_default.html | Arquivo de modelo; Modelo para a saída de HTML produzido pela gerador de saída para CIDRAM.
/.gitattributes | Um arquivo do GitHub projeto (não é necessário para o correto funcionamento do script).
/Changelog.txt | Um registro das mudanças feitas para o script entre o diferentes versões (não é necessário para o correto funcionamento do script).
/composer.json | Composer/Packagist informação (não é necessário para o correto funcionamento do script).
/CONTRIBUTING.md | Informações sobre como contribuir para o projeto.
/LICENSE.txt | Uma cópia da GNU/GPLv2 licença (não é necessário para o correto funcionamento do script).
/loader.php | Carregador/Loader. Isto é o que você deveria ser enganchando em (essencial)!
/README.md | Informações do projeto em sumário.
/web.config | Um arquivo de configuração para ASP.NET (neste caso, para protegendo o`/vault` diretório contra serem acessado por fontes não autorizadas em caso que o script está instalado em um servidor baseado em ASP.NET tecnologias).

---


### 6. <a name="SECTION6"></a>OPÇÕES DE CONFIGURAÇÃO
O seguinte é uma lista de variáveis encontradas no `config.ini` arquivo de configuração para CIDRAM, juntamente com uma descrição de sua propósito e função.

#### "general" (Categoria)
Configuração geral por CIDRAM.

"logfile"
- Um arquivo legível por humanos para registrar todas as tentativas de acesso bloqueadas. Especifique o nome de um arquivo, ou deixe em branco para desabilitar.

"logfileApache"
- Um arquivo no estilo da Apache para registrar todas as tentativas de acesso bloqueadas. Especifique o nome de um arquivo, ou deixe em branco para desabilitar.

"logfileSerialized"
- Um arquivo serializado para registrar todas as tentativas de acesso bloqueadas. Especifique o nome de um arquivo, ou deixe em branco para desabilitar.

*Dica útil: Se você quiser, você pode acrescentar informações tempo/hora aos nomes dos seus arquivos de registro através incluir estas em nome: `{yyyy}` para o ano completo, `{yy}` para o ano abreviado, `{mm}` por mês, `{dd}` por dia, `{hh}` por hora.*

*Exemplos:*
- *`logfile='logfile.{yyyy}-{mm}-{dd}-{hh}.txt'`*
- *`logfileApache='access.{yyyy}-{mm}-{dd}-{hh}.txt'`*
- *`logfileSerialized='serial.{yyyy}-{mm}-{dd}-{hh}.txt'`*

"truncate"
- Truncar arquivos de log quando atingem um determinado tamanho? Valor é o tamanho máximo em B/KB/MB/GB/TB que um arquivo de log pode crescer antes de ser truncado. O valor padrão de 0KB desativa o truncamento (arquivos de log podem crescer indefinidamente). Nota: Aplica-se a arquivos de log individuais! O tamanho dos arquivos de log não é considerado coletivamente.

"timeOffset"
- Se o tempo do servidor não coincide com sua hora local, você pode especificar aqui um offset para ajustar as informações de data/tempo gerado por CIDRAM de acordo com as suas necessidades. É geralmente recomendado no lugar para ajustar a directiva fuso horário no seu arquivo `php.ini`, mas às vezes (tais como quando se trabalha com provedores de hospedagem compartilhada e limitados) isto não é sempre possível fazer, e entao, esta opção é fornecido aqui. Offset é em minutos.
- Exemplo (para adicionar uma hora): `timeOffset=60`

"timeFormat"
- O formato de notação de data/tempo utilizado pelo CIDRAM. Padrão = `{Day}, {dd} {Mon} {yyyy} {hh}:{ii}:{ss} {tz}`.

"ipaddr"
- Onde encontrar o IP endereço das solicitações? (Útil por serviços como o Cloudflare e tal). Padrão = REMOTE_ADDR. ATENÇÃO: Não mude isso a menos que você saiba o que está fazendo!

"forbid_on_block"
- Quais cabeçalhos deve CIDRAM responder com quando bloqueando solicitações? False/200 = 200 OK [Padrão]; True/403 = 403 Forbidden (Proibido); 503 = 503 Service unavailable (Serviço indisponível).

"silent_mode"
- Deve CIDRAM silenciosamente redirecionar as tentativas de acesso bloqueadas em vez de exibir o "Acesso Negado" página? Se sim, especificar o local para redirecionar as tentativas de acesso bloqueadas para. Se não, deixe esta variável em branco.

"lang"
- Especificar o padrão da linguagem por CIDRAM.

"emailaddr"
- Se você desejar, você pode fornecer um endereço de e-mail aqui a ser dado para os usuários quando eles estão bloqueadas, para eles para usar como um ponto de contato para suporte e/ou assistência no caso de eles sendo bloqueado por engano ou em erro. AVISO: Qualquer endereço de e-mail que você fornecer aqui certamente vai ser adquirido por spambots e raspadores/scrapers durante o curso de seu ser usada aqui, e assim, é fortemente recomendado que, se você optar por fornecer um endereço de e-mail aqui, que você garantir que o endereço de email você fornecer aqui é um endereço descartável e/ou um endereço que você não é importante (em outras palavras, você provavelmente não quer usar seu pessoal principal ou negócio principal endereço de e-mail).

"disable_cli"
- Desativar o modo CLI? O modo CLI é ativado por padrão, mas às vezes pode interferir com certas testes ferramentas (tal como PHPUnit, por exemplo) e outras aplicações baseadas em CLI. Se você não precisa desativar o modo CLI, você deve ignorar esta directiva. False = Ativar o modo CLI [Padrão]; True = Desativar o modo CLI.

"disable_frontend"
- Desativar o acesso front-end? Acesso front-end pode fazer CIDRAM mais manejável, mas também pode ser um risco de segurança potencial, também. É recomendado para gerenciar CIDRAM através do back-end, sempre que possível, mas o acesso front-end é proporcionada para quando não é possível. Mantê-lo desativado, a menos que você precisar. False = Ativar o acesso front-end; True = Desativar o acesso front-end [Padrão].

"max_login_attempts"
- Número máximo de tentativas de login (front-end). Padrão = 5.

"FrontEndLog"
- Arquivo para registrar tentativas de login ao front-end. Especifique o nome de um arquivo, ou deixe em branco para desabilitar.

"ban_override"
- Sobrepor "forbid_on_block" quando "infraction_limit" é excedido? Quando sobrepõe: As solicitações bloqueadas retornam uma página em branco (os arquivos de modelo não são usados). 200 = Não sobrepor [Padrão]; 403 = Sobrepor com "403 Forbidden"; 503 = Sobrepor com "503 Service unavailable".

"log_banned_ips"
- Incluir solicitações bloqueadas de IPs proibidos nos arquivos de log? True = Sim [Padrão]; False = Não.

"default_dns"
- Uma lista delimitada por vírgulas de servidores DNS a serem usados para pesquisas de nomes de host. Padrão = "8.8.8.8,8.8.4.4" (Google DNS). ATENÇÃO: Não mude isso a menos que você saiba o que está fazendo!

"search_engine_verification"
- Tentativa de verificar pedidos dos motores de busca? Verificando os motores de busca garante que eles não serão banidos como resultado de exceder o limite de infrações (proibindo motores de busca de seu site normalmente terá um efeito negativo sobre o seu motor de busca ranking, SEO, etc). Quando verificado, os motores de busca podem ser bloqueados como por normal, mas não serão banidos. Quando não verificado, é possível que eles serão banidos como resultado de ultrapassar o limite de infrações. Também, a verificação dos motores de busca fornece proteção contra falsos pedidos de motores de busca e contra entidades potencialmente mal-intencionadas mascarando como motores de busca (tais pedidos serão bloqueados quando a verificação dos motores de busca estiver ativada). True = Ativar a verificação dos motores de busca [Padrão]; False = Desativar a verificação dos motores de busca.

"protect_frontend"
- Especifica se as proteções normalmente fornecidas pelo CIDRAM devem ser aplicadas ao front-end. True = Sim [Padrão]; False = Não.

"disable_webfonts"
- Desativar webfonts? True = Sim; False = Não [Padrão].

#### "signatures" (Categoria)
Configuração por assinaturas.

"ipv4"
- Uma lista dos arquivos de assinaturas IPv4 que CIDRAM deve tentar usar, delimitado por vírgulas. Você pode adicionar entradas aqui Se você quiser incluir arquivos adicionais em CIDRAM.

"ipv6"
- Uma lista dos arquivos de assinaturas IPv6 que CIDRAM deve tentar usar, delimitado por vírgulas. Você pode adicionar entradas aqui Se você quiser incluir arquivos adicionais em CIDRAM.

"block_cloud"
- Bloquear CIDRs identificado como pertencente a webhosting e/ou serviços em nuvem? Se você operar um serviço de API a partir do seu site ou se você espera outros sites para se conectar para o seu site, este deve ser definido como false. Se não, este deve ser definido como true.

"block_bogons"
- Bloquear bogon/martian CIDRs? Se você espera conexões para o seu site de dentro de sua rede local, de localhost, ou de seu LAN, esta directiva deve ser definido como false. Se você não esperar que esses tais conexões, esta directiva deve ser definido como true.

"block_generic"
- Bloquear CIDRs geralmente recomendado para a lista negra? Isso abrange todas as assinaturas que não são marcados como sendo parte de qualquer um dos outros mais categorias de assinaturas mais específica.

"block_proxies"
- Bloquear CIDRs identificado como pertencente a serviços de proxy? Se você precisar que os usuários poderão acessar seu site dos serviços de proxy anônimos, este deve ser definido como false. De outra forma, se você não precisa de proxies anônimos, este deve ser definido como true como um meio de melhorar a segurança.

"block_spam"
- Bloquear CIDRs identificado como sendo de alto risco para spam? A menos que você tiver problemas ao fazê-lo, geralmente, esta deve sempre ser definido como true.

"modules"
- Uma lista de arquivos módulo a carregar depois de processamento as assinaturas IPv4/IPv6, delimitado por vírgulas.

"default_tracktime"
- Quantos segundos para rastrear IPs banidos por módulos. Padrão = 604800 (1 semana).

"infraction_limit"
- Número máximo de infrações que um IP pode incorrer antes de ser banido por monitoração IP. Padrão = 10.

"track_mode"
- Quando as infrações devem ser contadas? False = Quando os IPs são bloqueados por módulos. True = Quando os IPs são bloqueados por qualquer motivo.

#### "recaptcha" (Categoria)
Opcionalmente, você pode fornecer aos usuários uma maneira de contornar a página de "Acesso Negado" por meio de completar uma instância reCAPTCHA, se você quiser fazê-lo. Isso pode ajudar a mitigar alguns dos riscos associados com falsos positivos nas situações em que não estamos inteiramente certo se uma solicitação tem originado a partir de uma máquina ou um ser humano.

Devido aos riscos associados com fornecimento de uma maneira para os usuários a ignorar a página de "Acesso Negado", geralmente, gostaria de aconselhar contra habilitação deste recurso a menos que você sente que isso é necessário para fazê-lo. Situações em que poderia ser necessário: Se seu site tem clientes/usuários que precisam ter acesso ao seu site, e se essa é algo que não pode ser comprometida em, mas se os clientes/usuários acontecer a ser conectando a partir de uma rede hostil que poderiam ser também carregando de tráfego indesejável, e bloqueando este tráfego indesejável também é algo que não pode ser comprometida em, nessas situações particulares sem vitória, o recurso reCAPTCHA poderia ser útil como um meio de permitir que os clientes/usuários desejáveis, enquanto mantendo fora o tráfego indesejável a partir da mesma rede. Dito isto, considerando que o propósito de um CAPTCHA é distinguir entre humanos e não-humanos, o recurso reCAPTCHA só iria ajudar nestas situações sem vitória se fosse para supor que este tráfego indesejável é não-humano (por exemplo, spambots, raspadores, ferramentas de hackers, tráfego automatizado), ao contrário de ser tráfego humano indesejável (tais como spammer humanos, hackers, et ai).

Para obter uma "site key" e uma "secret key" (necessário para usando reCAPTCHA), por favor vá a: [https://developers.google.com/recaptcha/](https://developers.google.com/recaptcha/)

"usemode"
- Define como CIDRAM deve usar reCAPTCHA.
- 0 = reCAPTCHA é completamente desativado (padrão).
- 1 = reCAPTCHA é ativado para todas as assinaturas.
- 2 = reCAPTCHA é ativado apenas para assinaturas pertencentes a seções especialmente marcados dentro dos arquivos de assinatura.
- (Qualquer outro valor será tratado da mesma maneira como 0).

"lockip"
- Especifica se hashes deve ser ligado para IPs específicos. False = Cookies e hashes PODE ser usado por vários IPs (padrão). True = Cookies e hashes NÃO pode ser usado por vários IPs (cookies/hashes não estão ligados para IPs).
- Notar: O valor de "lockip" é ignorado quando "lockuser" é false, devido a que o mecanismo para lembrar "usuários" varia de acordo com este valor.

"lockuser"
- Especifica se a conclusão bem sucedida de uma instância de reCAPTCHA deve ser ligado a usuários específicos. False = A conclusão bem sucedida de uma instância de reCAPTCHA irá conceder acesso a todos os solicitações provenientes do mesmo IP como aquilo utilizado pelo utilizador completar a instância de reCAPTCHA; Cookies e hashes não são usados; Em vez disso, um IP whitelist será usado. True = A conclusão bem sucedida de uma instância de reCAPTCHA só irá conceder acesso para o usuário completar a instância de reCAPTCHA; Cookies e hashes são usados para lembrar o usuário; Um IP whitelist não é usado (padrão).

"sitekey"
- Este valor deve corresponder ao "site key" para o seu reCAPTCHA, que pode ser encontrado dentro do painel de reCAPTCHA.

"secret"
- Este valor deve corresponder ao "secret key" para o seu reCAPTCHA, que pode ser encontrado dentro do painel de reCAPTCHA.

"expiry"
- Quando "lockuser" é true (padrão), a fim de lembrar quando um usuário tenha já passou com êxito uma instância de reCAPTCHA, para solicitações de página no futuro, CIDRAM gera um cookie HTTP norma contendo um hash que corresponde a um registro interno que contém a mesma hash; Solicitações de página no futuro vai usar esses hashes correspondentes para autenticar que o usuário tenha previamente já passou com êxito uma instância de reCAPTCHA. Quando "lockuser" é false, um IP whitelist é usado para determinar se as solicitações devem ser permitidas do IP de solicitações de entrada; As entradas são adicionadas a esta whitelist quando a instância de reCAPTCHA passou com êxito. Por quantas horas devem estes cookies, hashes e entradas de o whitelist permanecem válidos? Padrão = 720 (1 mês).

"logfile"
- Registrar todas as tentativas de reCAPTCHA? Se sim, especificar o nome a ser usado para o arquivo de registro. Se não, deixe esta variável em branco.

*Dica útil: Se você quiser, você pode acrescentar informações tempo/hora aos nomes dos seus arquivos de registro através incluir estas em nome: `{yyyy}` para o ano completo, `{yy}` para o ano abreviado, `{mm}` por mês, `{dd}` por dia, `{hh}` por hora.*

*Exemplos:*
- *`logfile='recaptcha.{yyyy}-{mm}-{dd}-{hh}.txt'`*

#### "template_data" (Categoria)
Directivas/Variáveis para modelos e temas.

Relaciona-se com a saída HTML usado para gerar a página "Acesso Negado". Se você estiver usando temas personalizados para CIDRAM, HTML é originado a partir do `template_custom.html` arquivo, e caso contrário, HTML é originado a partir do `template.html` arquivo. Variáveis escritas para esta seção do configuração arquivo são processado ao HTML via substituição de quaisquer nomes de variáveis cercado por colchetes encontrado dentro do HTML com os variáveis dados correspondentes. Por exemplo, onde `foo="bar"`, qualquer instância de `<p>{foo}</p>` encontrado dentro do HTML tornará `<p>bar</p>`.

"theme"
- Tema padrão a ser usado para CIDRAM.

"css_url"
- O template arquivo para temas personalizados utiliza CSS propriedades externos, enquanto que o template arquivo para o padrão tema utiliza CSS propriedades internos. Para instruir CIDRAM para usar o template arquivo para temas personalizados, especificar o endereço HTTP pública do seu temas personalizados CSS arquivos usando a `css_url` variável. Se você deixar essa variável em branco, CIDRAM usará o template arquivo para o padrão tema.

---


### 7. <a name="SECTION7"></a>FORMATO DE ASSINATURAS

#### 7.0 NOÇÕES BÁSICAS

A descrição do formato e estrutura das assinaturas utilizadas por CIDRAM pode ser encontrada documentado em texto simples dentro de qualquer um dos dois arquivos de assinaturas personalizados. Por favor, consulte que a documentação para saber mais sobre o formato e estrutura das assinaturas de CIDRAM.

Todas as assinaturas IPv4 seguir o formato: `xxx.xxx.xxx.xxx/yy [Function] [Param]`.
- `xxx.xxx.xxx.xxx` representa o início do bloco CIDR (os octetos do endereço IP inicial no bloco).
- `yy` representa o tamanho do bloco CIDR [1-32].
- `[Function]` instrui o script o que fazer com a assinatura (como a assinatura deve ser considerada).
- `[Param]` representa qualquer informação adicional que possa ser necessária por `[Function]`.

Todas as assinaturas IPv6 seguir o formato: `xxxx:xxxx:xxxx:xxxx::xxxx/yy [Function] [Param]`.
- `xxxx:xxxx:xxxx:xxxx::xxxx` representa o início do bloco CIDR (os octetos do endereço IP inicial no bloco). Notação completa e notação abreviada são aceitáveis (e cada DEVE seguir os padrões da notação IPv6 apropriados e relevantes, mas com uma exceção: um endereço IPv6 nunca pode começar com uma abreviatura quando utilizado em uma assinatura para este script, por causa da maneira em que CIDRs são reconstruídos pelo script; Por exemplo, `::1/128` deve ser expresso, quando utilizado em uma assinatura, como `0::1/128`, e `::0/128` expresso como `0::/128`).
- `yy` representa o tamanho do bloco CIDR [1-128].
- `[Function]` instrui o script o que fazer com a assinatura (como a assinatura deve ser considerada).
- `[Param]` representa qualquer informação adicional que possa ser necessária por `[Function]`.

Os arquivos de assinaturas para CIDRAM DEVE usar quebras de linha no estilo de Unix (`%0A`, ou `\n`)! Outros tipos/estilos de quebras de linha (por exemplo, Windows `%0D%0A` ou `\r\n` quebras de linha, Mac `%0D` ou `\r` quebras de linha, etc) PODE ser usado, mas NÃO são preferidos. Quebras de linha não no estilo de Unix será normalizado para quebras de linha no estilo de Unix pelo script.

Notação CIDR precisa e correta é necessária, ou então o script NÃO irá reconhecer as assinaturas. Além disso, todas as assinaturas CIDR deste script DEVE começar com um endereço IP cujo número IP pode dividir igualmente na divisão bloco representado pelo tamanho do seu bloco CIDR (por exemplo, se você quiser bloquear todos os IPs de `10.128.0.0` para `11.127.255.255`, `10.128.0.0/8` NÃO seria reconhecido pelo script, mas `10.128.0.0/9` e `11.0.0.0/9` usado em conjunto, SERIA reconhecido pelo script).

Qualquer coisa na arquivos de assinaturas não reconhecida como uma assinatura nem como sintaxe relacionados com assinaturas pelo script será IGNORADO, que significa que você pode colocar com segurança quaisquer dados que você quer nos arquivos de assinaturas sem quebrá-los e sem quebrar o script. Comentários são aceitáveis nos arquivos de assinaturas, e nenhuma formatação especial é necessário para eles. Hashing no estilo de Shell para comentários é preferido, mas não são forçadas; Funcionalmente, não faz diferença para o script se ou não você escolher para usar hashing no estilo de Shell para comentários, mas utilizando hashing no estilo de Shell ajuda IDEs e editores de texto simples para destacar corretamente as várias partes dos arquivos de assinaturas (e entao, hashing no estilo de Shell pode ajudar como uma ajuda visual durante a edição).

Os valores possíveis de `[Function]` são as seguintes:
- Run
- Whitelist
- Greylist
- Deny

Se "Run" é utilizado, quando a assinatura é desencadeada, o script tentará executar (usando um statement `require_once`) um script PHP externa, especificado pelo valor de `[Param]` (o diretório de trabalho deve ser o diretório do script, "/vault/").

Exemplo: `127.0.0.0/8 Run example.php`

Isto pode ser útil se você quiser executar algum código PHP específica para alguns IPs específicos e/ou CIDRs.

Se "Whitelist" é utilizado, quando a assinatura é desencadeada, o script irá repor todas as detecções (se houve quaisquer detecções) e quebrar a função de teste. `[Param]` é ignorado. Esta função irá assegurar que um IP ou CIDR não será detectado.

Exemplo: `127.0.0.1/32 Whitelist`

Se "Greylist" é utilizado, quando a assinatura é desencadeada, o script irá repor todas as detecções (se houve quaisquer detecções) e pular para o próximo arquivo de assinaturas para continuar o processamento. `[Param]` é ignorado.

Exemplo: `127.0.0.1/32 Greylist`

Se "Deny" é utilizado, quando a assinatura é desencadeada, assumindo que não assinatura whitelist foi desencadeado para o dado endereço IP e/ou dado CIDR, acesso à página protegida será negado. "Deny" é o que você deseja usar para realmente bloquear um endereço IP e/ou gama CIDR. Quando qualquer as assinaturas usando "Deny" são desencadeados, o "Acesso Negado" página do script será gerado ea solicitação para a página protegida será morto.

O valor da `[Param]` aceita por "Deny" será processado com o saída da "Acesso Negado" página, fornecido ao cliente/utilizador como a razão citada para o seu acesso à página solicitada ser negada. Pode ser uma curta e simples frase, explicando o motivo de ter escolhido para bloqueá-los (qualquer coisa deve ser suficiente, até mesmo uma "eu não quero você no meu site"), ou um de um pequeno punhado de palavras curtas fornecidas pelo script que, se usadas, será substituído pelo script com uma explicação pré-preparado de porque o cliente/usuário foi bloqueado.

As explicações pré-preparados têm suporte L10N e pode ser traduzido pelo script com base no idioma que você especificar com a directiva da configuração do script, `lang`. Além disso, você pode instruir o script para ignorar assinaturas "Deny" com base em sua valor de `[Param]` (se eles estão usando essas palavras curtas) através das directivas especificados pelo configuração do script (cada palavra curta tem uma directiva correspondente para processar as assinaturas correspondentes ou ignorá-los). Valores de `[Param]` que não usar essas palavras curtas, contudo, não tem suporte L10N e por conseguinte NÃO será traduzido pelo script, e adicionalmente, não podem ser controlados directamente pelo configuração do script.

As palavras curtas disponíveis são:
- Bogon
- Cloud
- Generic
- Proxy
- Spam

#### 7.1 ETIQUETAS

Se você quiser dividir suas assinaturas personalizadas em seções individuais, você pode identificar estas seções individuais para o script por adição de uma "etiqueta de secção" imediatamente após as assinaturas de cada secção, juntamente com o nome de sua seção de assinaturas (veja o exemplo abaixo).

```
# Seção 1.
1.2.3.4/32 Deny Bogon
2.3.4.5/32 Deny Cloud
4.5.6.7/32 Deny Generic
5.6.7.8/32 Deny Spam
6.7.8.9/32 Deny Proxy
Tag: Seção 1
```

Para quebrar etiquetas de seção e assegurar que os etiquetas não são identificados incorretamente a seções de assinaturas de mais cedo nos arquivos de assinaturas, simplesmente assegurar que há pelo menos dois quebras de linha consecutivas entre o sua etiqueta e suas seções de assinaturas anteriores. Quaisquer assinaturas não etiquetados será padrão para qualquer "IPv4" ou "IPv6" (dependendo de quais tipos de assinaturas estão sendo desencadeados).

```
1.2.3.4/32 Deny Bogon
2.3.4.5/32 Deny Cloud

4.5.6.7/32 Deny Generic
5.6.7.8/32 Deny Spam
Tag: Seção 1
```

No exemplo acima, `1.2.3.4/32` e `2.3.4.5/32` será etiquetadas como "IPv4", enquanto que `4.5.6.7/32` e `5.6.7.8/32` será etiquetadas como "Seção 1".

Se você quiser assinaturas para expirar depois de algum tempo, de um modo semelhante para etiquetas de secção, você pode usar um "etiqueta de expiração" para especificar quando as assinaturas devem deixar de ser válida. Etiquetas de expiração usam o formato "AAAA.MM.DD" (veja o exemplo abaixo).

```
# Seção 1.
1.2.3.4/32 Deny Generic
2.3.4.5/32 Deny Generic
Expires: 2016.12.31
```

Etiquetas de secção and e etiquetas de expiração pode ser usado em conjunto, e ambos são opcionais (veja o exemplo abaixo).

```
# Seção Exemplo.
1.2.3.4/32 Deny Generic
Tag: Seção Exemplo
Expires: 2016.12.31
```

#### 7.2 YAML

##### 7.2.0 YAML BÁSICOS

Uma forma simplificada de marcação YAML pode ser usado em arquivos de assinatura com a finalidade de definir comportamentos e configurações específicas para as seções de assinaturas individuais. Isto pode ser útil se você quiser que o valor de suas diretivas de configuração para diferir na base de assinaturas individuais e seções de assinatura (por exemplo; se você quiser fornecer um endereço de e-mail para tickets de suporte para quaisquer usuários bloqueados por uma assinatura específica, mas não quer fornecer um endereço de e-mail para tickets de suporte para usuários bloqueados por quaisquer outras assinaturas; se você quiser algumas assinaturas específicas para provocar um redirecionamento página; se você quiser marcar uma seção de assinaturas para uso com reCAPTCHA; Se você quiser registrar tentativas de acesso bloqueadas para separar arquivos na base de assinaturas individuais e/ou seções de assinatura).

Uso de marcação YAML nos arquivos de assinatura é totalmente opcional (isto é, você pode usá-lo se desejar fazê-lo, mas você não é obrigado a fazê-lo), e é capaz de alavancar mais (mas nem todos) das diretivas de configuração.

Nota: Implementação de marcação YAML em CIDRAM é muito simplista e muito limitado; Destina-se a cumprir as exigências específicas para CIDRAM de uma maneira que tem a familiaridade de marcação YAML, mas nem segue nem está de acordo com as especificações oficiais (e portanto, não se comporta da mesma forma como outros implementações mais completas, e pode não ser apropriado para outros projetos).

Em CIDRAM, Segmentos de marcação YAML são identificados para o script por três hífens ("---"), e terminar ao lado de seus contendo seções de assinatura por quebras de linha dupla. Um segmento típico de marcação YAML dentro de uma seção de assinaturas consiste de três hífens em uma linha imediatamente após a lista de CIDRs e todas as tags, seguido por uma lista bidimensional de pares chave-valor (primeira dimensão, categorias das diretivas de configuração; segunda dimensão, as diretivas de configuração) para as quais diretivas de configuração deve ser modificada (e em qual valores) sempre que uma assinatura em nisso secção de assinaturas é desencadeada (veja os exemplos abaixo).

```
# Foobar 1.
1.2.3.4/32 Deny Generic
2.3.4.5/32 Deny Generic
4.5.6.7/32 Deny Generic
Tag: Foobar 1
---
general:
 logfile: logfile.{yyyy}-{mm}-{dd}.txt
 logfileApache: access.{yyyy}-{mm}-{dd}.txt
 logfileSerialized: serial.{yyyy}-{mm}-{dd}.txt
 forbid_on_block: false
 emailaddr: username@domain.tld
recaptcha:
 lockip: false
 lockuser: true
 expiry: 720
 logfile: recaptcha.{yyyy}-{mm}-{dd}.txt
 enabled: true
template_data:
 css_url: http://domain.tld/cidram.css

# Foobar 2.
1.2.3.4/32 Deny Generic
2.3.4.5/32 Deny Generic
4.5.6.7/32 Deny Generic
Tag: Foobar 2
---
general:
 logfile: "logfile.Foobar2.{yyyy}-{mm}-{dd}.txt"
 logfileApache: "access.Foobar2.{yyyy}-{mm}-{dd}.txt"
 logfileSerialized: "serial.Foobar2.{yyyy}-{mm}-{dd}.txt"
 forbid_on_block: 503

# Foobar 3.
1.2.3.4/32 Deny Generic
2.3.4.5/32 Deny Generic
4.5.6.7/32 Deny Generic
Tag: Foobar 3
---
general:
 forbid_on_block: 403
 silent_mode: "http://127.0.0.1/"
```

##### 7.2.1 COMO "MARCAR ESPECIALMENTE" SEÇÕES DE ASSINATURA PARA USO COM RECAPTCHA

Quando "usemode" é 0 ou 1, seções de assinatura não precisa ser "marcado especialmente" para uso com reCAPTCHA (porque eles já vão usar ou não vão usar o reCAPTCHA, dependendo essa configuração).

Quando "usemode" é 2, para "marcar especialmente" seções de assinatura para uso com reCAPTCHA, uma entrada está incluído no segmento de YAML para que a secção de assinatura (veja o exemplo abaixo).

```
# Esta seção usará reCAPTCHA.
1.2.3.4/32 Deny Generic
2.3.4.5/32 Deny Generic
Tag: reCAPTCHA-Enabled
---
recaptcha:
 enabled: true
```

Nota: Um instância de reCAPTCHA vai SOMENTE ser oferecido ao usuário se reCAPTCHA está habilitado (quer com "usemode" como 1, ou "usemode" como 2 com "enabled" como true), e se exatamente UMA assinatura foi desencadeada (nem mais, nem menos; se assinaturas múltiplas são desencadeadas, uma instância de reCAPTCHA NÃO será oferecida).

#### 7.3 AUXILIAR

Em suplemento, se você quiser CIDRAM para ignorar completamente algumas seções específicas dentro de qualquer um dos arquivos de assinatura, você pode usar o arquivo `ignore.dat` para especificar quais seções para ignorar. Em uma nova linha, escreva `Ignore`, seguido por um espaço, seguido do nome da seção que você quer CIDRAM para ignorar (veja o exemplo abaixo).

```
Ignore Seção 1
```

Consulte os arquivos de assinaturas personalizadas para obter mais informações.

---


### 8. <a name="SECTION8"></a>PERGUNTAS MAIS FREQUENTES (FAQ)

#### O que é uma "assinatura"?

No contexto do CIDRAM, uma "assinatura" refere-se a dados que actuam como um indicador/identificador para algo específico que estamos procurando, geralmente um endereço IP ou CIDR, e inclui algumas instruções para CIDRAM, dizendo-lhe a melhor maneira de responder quando encontrar o que estamos procurando. Uma assinatura típica para o CIDRAM é algo como isto:

`1.2.3.4/32 Deny Generic`

Muitas vezes (mas nem sempre), as assinaturas serão agrupadas em grupos, formando "seções de assinaturas", freqüentemente acompanhado de comentários, marcação e/ou metadados relacionados que podem ser usados para fornecer contexto adicional para as assinaturas e/ou instrução adicional.

#### <a name="WHAT_IS_A_CIDR"></a>O que é um "CIDR"?

"CIDR" é um acrônimo para "Classless Inter-Domain Routing" *[[1](https://pt.wikipedia.org/wiki/CIDR), [2](http://whatismyipaddress.com/cidr)]*, e é este acrônimo que é usado como parte do nome para este pacote, "CIDRAM", que é um acrônimo para "Classless Inter-Domain Routing Access Manager".

No entanto, no contexto do CIDRAM (tal como, dentro desta documentação, dentro das discussões relativas ao CIDRAM, ou dentro dos dados lingüísticos para CIDRAM), sempre que um "CIDR" (singular) ou "CIDRs" (plural) seja mencionado ou referido (e assim por meio do qual usamos essas palavras como substantivos em seu próprio direito, ao contrário de como acrônimos), o que é pretendido e significado por isto é uma sub-rede (ou sub-redes), expresso usando a notação CIDR. A razão que CIDR (ou CIDRs) é usado em vez de sub-rede (ou sub-redes) é deixar claro que é especificamente sub-redes expressas usando a notação CIDR que está sendo referida (pois a notação CIDR é apenas uma das várias maneiras pelas quais as sub-redes podem ser expressas). O CIDRAM poderia, portanto, ser considerado um "gerenciador de acesso para sub-redes".

Embora este duplo significado de "CIDR" possa apresentar alguma ambigüidade em alguns casos, esta explicação, juntamente com o contexto, deve ajudar a resolver essa ambiguidade.

#### O que é um "falso positivo"?

O termo "falso positivo" (*alternativamente: "erro de falso positivo"; "alarme falso"*; Inglês: *false positive*; *false positive error*; *false alarm*), descrita de maneira muito simples, e num contexto generalizado, são usadas quando testando para uma condição, para se referir aos resultados desse teste, quando os resultados são positivos (isto é, a condição é determinada para ser "positivo", ou "verdadeiro"), mas espera-se que seja (ou deveria ter sido) negativo (isto é, a condição, na realidade, é "negativo", ou "falso"). Um "falso positivo" pode ser considerado análogo ao "chorando lobo" (em que a condição que está sendo testada é se existe um lobo perto do rebanho, a condição é "falso" em que não há nenhum lobo perto do rebanho, ea condição é relatada como "positivo" pelo pastor por meio de gritando "lobo, lobo"), ou análoga a situações em exames médicos em que um paciente é diagnosticado como tendo alguma doença quando, na realidade, eles não têm essa doença.

Os resultados relacionados a quando testando para uma condição pode ser descrito usando os termos "verdadeiro positivo", "verdadeiro negativo" e "falso negativo". Um "verdadeiro positivo" refere-se a quando os resultados do teste ea real situação da condição são ambos verdadeiros (ou "positivos"), e um "verdadeiro negativo" refere-se a quando os resultados do teste ea real situação da condição são ambos falsos (ou "negativos"); Um "verdadeiro positivo" ou um "verdadeiro negativo" é considerado como sendo uma "inferência correcta". A antítese de um "falso positivo" é um "falso negativo"; Um "falso negativo" refere-se a quando os resultados do teste are negativo (isto é, a condição é determinada para ser "negativo", ou "falso"), mas espera-se que seja (ou deveria ter sido) positivo (isto é, a condição, na realidade, é "positivo", ou "verdadeiro").

No contexto da CIDRAM, estes termos referem-se as assinaturas de CIDRAM eo que/quem eles bloqueiam. Quando CIDRAM bloquear um endereço IP devido ao mau, desatualizados ou incorretos assinatura, mas não deveria ter feito isso, ou quando ele faz isso pelas razões erradas, nos referimos a este evento como um "falso positivo". Quando CIDRAM não consegue bloquear um endereço IP que deveria ter sido bloqueado, devido a ameaças imprevistas, assinaturas em falta ou déficits em suas assinaturas, nos referimos a este evento como um "detecção em falta" ou "missing detection" (que é análogo a um "falso negativo").

Isto pode ser resumido pela seguinte tabela:

&nbsp; | CIDRAM *NÃO* deve bloquear um endereço IP | CIDRAM *DEVE* bloquear um endereço IP
---|---|---
CIDRAM *NÃO* bloquear um endereço IP | Verdadeiro negativo (inferência correcta) | Detecção em falta (análogo a um falso negativo)
CIDRAM *FAZ* bloquear um endereço IP | __Falso positivo__ | Verdadeiro positivo (inferência correcta)

#### CIDRAM pode bloquear países inteiros?

Sim. A maneira mais fácil de conseguir isso seria instalar algumas das listas opcionais para bloqueando países fornecido por Macmathan. Isso pode ser feito com alguns cliques simples diretamente do front-end página de atualizações, ou, se você preferir o front-end para permanecer desativado, através da baixando-os diretamente da **[página para baixar as listas opcionais para bloqueando países](https://macmathan.info/blocklists)**, carregá-los para o vault, e citando seus nomes nas diretivas de configuração relevantes.

#### Com que freqüência as assinaturas são atualizadas?

A freqüência das atualizações varia de acordo com os arquivos de assinatura em questão. Todos os mantenedores dos arquivos de assinatura de CIDRAM geralmente tentam manter suas assinaturas atualizadas como é possível, mas devido a que todos nós temos vários outros compromissos, nossas vidas fora do projeto, e devido a que nenhum de nós é financeiramente compensado (ou pago) para nossos esforços no projeto, um cronograma de atualização preciso não pode ser garantido. Geralmente, as assinaturas são atualizadas sempre que há tempo suficiente para atualizá-las, e geralmente, os mantenedores tentam priorizar com base na necessidade e na freqüência com que as mudanças ocorrem entre gamas. Assistência é sempre apreciada se você estiver disposto a oferecer qualquer.

#### Eu encontrei um problema ao usar CIDRAM e eu não sei o que fazer sobre isso! Ajude-me!

- Você está usando a versão mais recente do software? Você está usando as versões mais recentes de seus arquivos de assinatura? Se a resposta a qualquer destas duas perguntas é não, tente atualizar tudo primeiro, e verifique se o problema persiste. Se persistir, continue lendo.
- Você já examinou toda a documentação? Se não, por favor, faça isso. Se o problema não puder ser resolvido usando a documentação, continue lendo.
- Você já examinou a **[página de problemas](https://github.com/Maikuolan/CIDRAM/issues)**, para ver se o problema foi mencionado antes? Se já foi mencionado antes, verificar se foram fornecidas sugestões, ideias e/ou soluções, e siga conforme necessário para tentar resolver o problema.
- Você já examinou a **[fórum de suporte do CIDRAM fornecido pela Spambot Security](http://www.spambotsecurity.com/forum/viewforum.php?f=61)**, para ver se o problema foi mencionado antes? Se já foi mencionado antes, verificar se foram fornecidas sugestões, ideias e/ou soluções, e siga conforme necessário para tentar resolver o problema.
- Se o problema ainda persistir, informe-nos através da iniciando uma nova discussão na página de problemas ou no fórum de suporte.

#### Eu fui bloqueado pelo CIDRAM de um site que eu quero visitar! Ajude-me!

CIDRAM fornece um meio para proprietários de sites para bloquear tráfego indesejável, mas é da responsabilidade dos proprietários do site decidir por si mesmos como eles querem usar CIDRAM. No caso dos falsos positivos relativos aos arquivos de assinatura normalmente incluídos no CIDRAM, correções podem ser feitas, mas no que diz respeito a ser desbloqueado a partir de sites específicos, você precisará tomar isso com os proprietários dos sites em questão. Nos casos em que são feitas correções, pelo menos, eles precisarão atualizar seus arquivos de assinatura e/ou instalação, e em outros casos (tais como, por exemplo, onde eles modificaram sua instalação, criaram suas próprias assinaturas personalizadas, etc), a responsabilidade de resolver o seu problema é inteiramente deles, e está inteiramente fora de nosso controle.

#### Eu quero usar CIDRAM com uma versão PHP mais velha do que 5.4.0; Você pode ajudar?

Não. PHP 5.4.0 chegou ao EoL ("End of Life", ou Fim da Vida) oficial em 2014, e suporte de segurança estendido foi terminado em 2015. Como de escrever isso, é 2017, e PHP 7.1.0 já está disponível. Neste momento, suporte é oferecido para o uso do CIDRAM com PHP 5.4.0 e todas as versões PHP mais recentes disponíveis, mas se você tentar usar o CIDRAM com versões mais antigas do PHP, o suporte não será fornecido.

#### Posso usar uma única instalação do CIDRAM para proteger vários domínios?

Sim. As instalações do CIDRAM não estão naturalmente atado com domínios específicos, e pode, portanto, ser usado para proteger vários domínios. Geralmente, referimo-nos a instalações do CIDRAM que protegem apenas um domínio como "instalações de singular-domínio", e referimo-nos a instalações do CIDRAM que protegem vários domínios e/ou subdomínios como "instalações multi-domínio". Se você operar uma instalação multi-domínio e precisa usar conjuntos diferentes de arquivos de assinaturas para domínios diferentes, ou precisam CIDRAM para ser configurado de forma diferente para domínios diferentes, é possível fazer isso. Depois de carregar o arquivo de configuração (`config.ini`), o CIDRAM verificará a existência de um "arquivo de sobreposição para a configuração" específico para o domínio (ou subdomínio) que está sendo solicitado (`o-domínio-que-está-sendo-solicitado.tld.config.ini`), e se encontrado, quaisquer valores de configuração definidos pelo arquivo de sobreposição para a configuração serão usados para a instância de execução em vez dos valores de configuração definidos pelo arquivo de configuração. Os arquivos de sobreposição para a configuração são idênticos ao arquivo de configuração, e a seu critério, pode conter a totalidade de todas as diretivas de configuração disponíveis para o CIDRAM, ou qualquer subseção menor necessária que difere dos valores normalmente definidos pelo arquivo de configuração. Os arquivos de sobreposição para a configuração são nomeados de acordo com o domínio que eles são destinados para (por exemplo, se você precisar de um arquivo de sobreposição para a configuração para o domínio, `http://www.some-domain.tld/`, o seu arquivo de sobreposição para a configuração deve ser nomeado como `some-domain.tld.config.ini`, e deve ser colocado dentro da vault ao lado do arquivo de configuração, `config.ini`). O nome de domínio para a instância de execução é derivado do cabeçalho `HTTP_HOST` do pedido; "www" é ignorado.

---


Última Atualização: 19 Maio 2017 (2017.05.19).
