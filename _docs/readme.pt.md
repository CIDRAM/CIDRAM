## Documentação para CIDRAM (Português).

### Conteúdo
- 1. [PREÂMBULO](#SECTION1)
- 2. [COMO INSTALAR](#SECTION2)
- 3. [COMO USAR](#SECTION3)
- 4. [GESTÃO DE FRONT-END](#SECTION4)
- 5. [ARQUIVOS INCLUÍDOS NESTE PACOTE](#SECTION5)
- 6. [OPÇÕES DE CONFIGURAÇÃO](#SECTION6)
- 7. [FORMATO DE ASSINATURAS](#SECTION7)
- 8. [PROBLEMAS DE COMPATIBILIDADE CONHECIDOS](#SECTION8)
- 9. [PERGUNTAS MAIS FREQUENTES (FAQ)](#SECTION9)
- 10. *Reservado para futuras adições à documentação.*
- 11. [INFORMAÇÃO LEGAL](#SECTION11)

*Nota relativa às traduções: Em caso de erros (por exemplo, discrepâncias entre as traduções, erros de digitação, etc), a versão em inglês do README é considerada a versão original e autorizada. Se você encontrar algum erro, a sua ajuda em corrigi-los seria bem-vinda.*

---


### 1. <a name="SECTION1"></a>PREÂMBULO

CIDRAM (Classless Inter-Domain Routing Access Manager) é um script PHP projetados para proteger sites por bloqueando solicitações provenientes de endereços IP considerado como sendo fontes de tráfego indesejável, incluindo (mas não limitado a) o tráfego dos pontos de acesso não-humanos, serviços em nuvem, spambots, raspadores/scrapers, etc. Ele faz isso via calculando as possíveis CIDRs dos endereços IP fornecida a partir de solicitações de entrada e em seguida tentando comparar estas possíveis CIDRs contra os seus arquivos de assinaturas (estas arquivos de assinaturas contêm listas de CIDRs de endereços IP considerado como sendo fontes de tráfego indesejável); Se forem encontradas correspondências, as solicitações estão bloqueadas.

*(Vejo: [O que é um "CIDR"?](#WHAT_IS_A_CIDR)).*

CIDRAM COPYRIGHT 2016 e além GNU/GPLv2 através do Caleb M (Maikuolan).

Este script é livre software; você pode redistribuí-lo e/ou modificá-lo de acordo com os termos da GNU General Public License como publicada pela Free Software Foundation; tanto a versão 2 da Licença, ou (em sua opção) qualquer versão posterior. Este script é distribuído na esperança que possa ser útil, mas SEM QUALQUER GARANTIA; sem mesmo a implícita garantia de COMERCIALIZAÇÃO ou ADEQUAÇÃO A UM DETERMINADO FIM. Consulte a GNU General Public License para obter mais detalhes, localizado no `LICENSE.txt` arquivo e disponível também desde:
- <http://www.gnu.org/licenses/>.
- <http://opensource.org/licenses/>.

Este documento e seu associado pacote pode ser baixado gratuitamente de [GitHub](https://cidram.github.io/).

---


### 2. <a name="SECTION2"></a>COMO INSTALAR

#### 2.0 INSTALANDO MANUALMENTE

1) Por o seu lendo isso, eu estou supondo que você já tenha baixado uma cópia arquivada do script, descomprimido seu conteúdo e tê-lo sentado em algum lugar em sua máquina local. A partir daqui, você vai querer determinar onde no seu host ou CMS pretende colocar esses conteúdos. Um diretório como `/public_html/cidram/` ou semelhante (porém, está não importa qual você escolher, assumindo que é seguro e algo você esteja feliz com) vai bastará.

2) Renomear `config.ini.RenameMe` para `config.ini` (localizado dentro `vault`), e opcionalmente (fortemente recomendado para avançados usuários, mas não recomendado para iniciantes ou para os inexperientes), abri-lo (este arquivo contém todas as directivas disponíveis para CIDRAM; acima de cada opção deve ser um breve comentário descrevendo o que faz e para que serve). Ajuste essas opções de como você vê o ajuste, conforme o que for apropriado para sua configuração específica. Salve o arquivo, fechar.

3) Carregar os conteúdos (CIDRAM e seus arquivos) para o diretório que você tinha decidido anteriormente (você não requerer os `*.txt`/`*.md` arquivos incluídos, mas principalmente, você deve carregar tudo).

4) CHMOD o `vault` diretório para "755" (se houver problemas, você pode tentar "777"; isto é menos seguro, embora). O principal diretório armazenar o conteúdo (o que você escolheu anteriormente), geralmente, pode ser deixado sozinho, mas o CHMOD status deve ser verificado se você já teve problemas de permissões no passado no seu sistema (por padrão, deve ser algo como "755").

5) Seguida, você vai precisar "enganchar" CIDRAM ao seu sistema ou CMS. Existem várias diferentes maneiras em que você pode "enganchar" scripts como CIDRAM ao seu sistema ou CMS, mas o mais fácil é simplesmente incluir o script no início de um núcleo arquivo de seu sistema ou CMS (uma que vai geralmente sempre ser carregado quando alguém acessa qualquer página através de seu site) utilizando um `require` ou `include` comando. Normalmente, isso vai ser algo armazenado em um diretório como `/includes`, `/assets` ou `/functions`, e muitas vezes, ser nomeado algo como `init.php`, `common_functions.php`, `functions.php` ou semelhante. Você precisará determinar qual arquivo isso é para a sua situação; Se você encontrar dificuldades em determinar isso por si mesmo, para assistência, visite a página de issues CIDRAM no GitHub. Para fazer isso [usar `require` ou `include`], insira a seguinte linha de código para o início desse núcleo arquivo, substituindo a string contida dentro das aspas com o exato endereço do `loader.php` arquivo (endereço local, não o endereço HTTP; será semelhante ao vault endereço mencionado anteriormente).

`<?php require '/user_name/public_html/cidram/loader.php'; ?>`

Salve o arquivo, fechar, recarregar-lo.

-- OU ALTERNATIVAMENTE --

Se você é usando um Apache web servidor e se você tem acesso a `php.ini`, você pode usar o `auto_prepend_file` directiva para pré-carga CIDRAM sempre que qualquer solicitação para PHP é feito. Algo como:

`auto_prepend_file = "/user_name/public_html/cidram/loader.php"`

Ou isso no `.htaccess` arquivo:

`php_value auto_prepend_file "/user_name/public_html/cidram/loader.php"`

6) Isso é tudo! :-)

#### 2.1 INSTALANDO COM COMPOSER

[CIDRAM está registrado no Packagist](https://packagist.org/packages/cidram/cidram), e entao, se você estiver familiarizado com o Composer, poderá usar o Composer para instalar o CIDRAM (você ainda precisará preparar a configuração e ganchos embora; consulte "instalando manualmente" as etapas 2 e 5).

`composer require cidram/cidram`

#### 2.2 INSTALANDO PARA WORDPRESS

Se você quiser usar o CIDRAM com o WordPress, você pode ignorar todas as instruções acima. [CIDRAM está registrado como um plugin com o banco de dados de plugins do WordPress](https://wordpress.org/plugins/cidram/), e você pode instalar CIDRAM diretamente do painel de plugins. Você pode instalá-lo da mesma maneira que qualquer outro plugin, e nenhuma etapa de adição é necessária. Assim como com os outros métodos de instalação, você pode personalizar sua instalação por meio de modificando o conteúdo do `config.ini` arquivo ou usando o front-end página de atualizações. Se você ativar o front-end e atualizar CIDRAM usando a página de atualizações, isso será sincronizado automaticamente com as informações de versão do plugin exibidas no painel de plugins.

*Atenção: A atualização do CIDRAM através do painel de plugins resulta em uma instalação limpa! Se você personalizou sua instalação (mudou sua configuração, instalados módulos, etc), estas personalizações serão perdidas quando atualizando através do painel de plugins! Os arquivos de log também serão perdidos ao atualizar através do painel de plugins! Para preservar arquivos de log e personalizações, atualize através da página de atualizações de front-end do CIDRAM.*

---


### 3. <a name="SECTION3"></a>COMO USAR

CIDRAM deve bloquear automaticamente as solicitações indesejáveis para o seu site sem necessidade de qualquer assistência manual, Além de sua instalação inicial.

Você pode personalizar a sua configuração e personalizar quais CIDRs são bloqueados por modificando seu arquivo de configuração e/ou seus arquivos de assinaturas.

Se você encontrar quaisquer falsos positivos, entre em contato comigo para me informar sobre isso. *(Vejo: [O que é um "falso positivo"?](#WHAT_IS_A_FALSE_POSITIVE)).*

CIDRAM pode ser atualizado manualmente ou através do front-end. CIDRAM também pode ser atualizado via Composer ou WordPress, se originalmente instalado por esses meios.

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
/vault/fe_assets/_cache.html | Um modelo HTML para o front-end página do dados de cache.
/vault/fe_assets/_cidr_calc.html | Um modelo HTML para a calculadora CIDR.
/vault/fe_assets/_cidr_calc_row.html | Um modelo HTML para a calculadora CIDR.
/vault/fe_assets/_config.html | Um modelo HTML para o front-end página de configuração.
/vault/fe_assets/_config_row.html | Um modelo HTML para o front-end página de configuração.
/vault/fe_assets/_files.html | Um modelo HTML para o gerenciador de arquivos.
/vault/fe_assets/_files_edit.html | Um modelo HTML para o gerenciador de arquivos.
/vault/fe_assets/_files_rename.html | Um modelo HTML para o gerenciador de arquivos.
/vault/fe_assets/_files_row.html | Um modelo HTML para o gerenciador de arquivos.
/vault/fe_assets/_home.html | Um modelo HTML para o front-end página principal.
/vault/fe_assets/_ip_aggregator.html | Um modelo HTML para o agregador IP.
/vault/fe_assets/_ip_test.html | Um modelo HTML para a página para testar IPs.
/vault/fe_assets/_ip_test_row.html | Um modelo HTML para a página para testar IPs.
/vault/fe_assets/_ip_tracking.html | Um modelo HTML para a página de monitoração IP.
/vault/fe_assets/_ip_tracking_row.html | Um modelo HTML para a página de monitoração IP.
/vault/fe_assets/_login.html | Um modelo HTML para o front-end página login.
/vault/fe_assets/_logs.html | Um modelo HTML para o front-end página para os arquivos de registro.
/vault/fe_assets/_nav_complete_access.html | Um modelo HTML para os links de navegação para o front-end, para aqueles com acesso completo.
/vault/fe_assets/_nav_logs_access_only.html | Um modelo HTML para os links de navegação para o front-end, para aqueles com acesso aos arquivos de registro somente.
/vault/fe_assets/_range.html | Um modelo HTML para as tabelas de alcance.
/vault/fe_assets/_range_row.html | Um modelo HTML para as tabelas de alcance.
/vault/fe_assets/_sections.html | Um modelo HTML para a lista de seções.
/vault/fe_assets/_sections_row.html | Um modelo HTML para a lista de seções.
/vault/fe_assets/_statistics.html | Um modelo HTML para o front-end página de estatísticas.
/vault/fe_assets/_updates.html | Um modelo HTML para o front-end página de atualizações.
/vault/fe_assets/_updates_row.html | Um modelo HTML para o front-end página de atualizações.
/vault/fe_assets/frontend.css | Folha de estilo CSS para o front-end.
/vault/fe_assets/frontend.dat | Banco de dados para o front-end (contém informações de contas, informações de sessões, eo cache; gerado só se o front-end está habilitado e usado).
/vault/fe_assets/frontend.html | O arquivo modelo HTML principal para o front-end.
/vault/fe_assets/icons.php | Módulo de ícones (usado pelo gerenciador de arquivos do front-end).
/vault/fe_assets/pips.php | Módulo de pips (usado pelo gerenciador de arquivos do front-end).
/vault/fe_assets/scripts.js | Contém dados de JavaScript do front-end.
/vault/lang/ | Contém dados lingüísticos.
/vault/lang/.htaccess | Um hipertexto acesso arquivo (neste caso, para proteger confidenciais arquivos pertencentes ao script contra serem acessados por fontes não autorizadas).
/vault/lang/lang.ar.cli.php | Dados lingüísticos Árabe para CLI.
/vault/lang/lang.ar.fe.php | Dados lingüísticos Árabe para o front-end.
/vault/lang/lang.ar.php | Dados lingüísticos Árabe.
/vault/lang/lang.bn.cli.php | Dados lingüísticos Bangla para CLI.
/vault/lang/lang.bn.fe.php | Dados lingüísticos Bangla para o front-end.
/vault/lang/lang.bn.php | Dados lingüísticos Bangla.
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
/vault/lang/lang.no.cli.php | Dados lingüísticos Norueguês para CLI.
/vault/lang/lang.no.fe.php | Dados lingüísticos Norueguês para o front-end.
/vault/lang/lang.no.php | Dados lingüísticos Norueguês.
/vault/lang/lang.pt.cli.php | Dados lingüísticos Português para CLI.
/vault/lang/lang.pt.fe.php | Dados lingüísticos Português para o front-end.
/vault/lang/lang.pt.php | Dados lingüísticos Português.
/vault/lang/lang.ru.cli.php | Dados lingüísticos Russo para CLI.
/vault/lang/lang.ru.fe.php | Dados lingüísticos Russo para o front-end.
/vault/lang/lang.ru.php | Dados lingüísticos Russo.
/vault/lang/lang.sv.cli.php | Dados lingüísticos Sueco para CLI.
/vault/lang/lang.sv.fe.php | Dados lingüísticos Sueco para o front-end.
/vault/lang/lang.sv.php | Dados lingüísticos Sueco.
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
/vault/.travis.php | Usado pela Travis CI para testes (não é necessário para o correto funcionamento do script).
/vault/.travis.yml | Usado pela Travis CI para testes (não é necessário para o correto funcionamento do script).
/vault/aggregator.php | Agregador IP.
/vault/cache.dat | Dados de cache.
/vault/cidramblocklists.dat | Arquivo de metadados para as listas de bloqueio opcionais da Macmathan; Usado pela página de atualizações do front-end.
/vault/cli.php | Módulo de CLI.
/vault/components.dat | Arquivo de metadados de componentes; Usado pela página de atualizações do front-end.
/vault/config.ini.RenameMe | Arquivo de configuração; Contém todas as opções de configuração para CIDRAM, dizendo-lhe o que fazer e como operar corretamente (renomear para ativar).
/vault/config.php | Módulo de configuração.
/vault/config.yaml | Arquivo de valores padrão para a configuração; Contém valores padrão para a configuração de CIDRAM.
/vault/frontend.php | Módulo do front-end.
/vault/frontend_functions.php | Arquivo de funções do front-end.
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
/vault/modules.dat | Arquivo de metadados de módulos; Usado pela página de atualizações do front-end.
/vault/outgen.php | Gerador de saída.
/vault/php5.4.x.php | Polyfills para PHP 5.4.X (necessário para compatibilidade reversa com PHP 5.4.X; seguro para deletar por versões de PHP mais recentes).
/vault/recaptcha.php | Módulo reCAPTCHA.
/vault/rules_as6939.php | Arquivo de regras personalizadas para AS6939.
/vault/rules_softlayer.php | Arquivo de regras personalizadas para Soft Layer.
/vault/rules_specific.php | Arquivo de regras personalizadas alguns CIDRs específicos.
/vault/salt.dat | Arquivo de sal (utilizada por algumas funcionalidades periférica; gerado apenas se necessário).
/vault/template_custom.html | Arquivo de modelo; Modelo para a saída de HTML produzido pela gerador de saída para CIDRAM.
/vault/template_default.html | Arquivo de modelo; Modelo para a saída de HTML produzido pela gerador de saída para CIDRAM.
/vault/themes.dat | Arquivo de metadados de temas; Usado pela página de atualizações do front-end.
/vault/verification.yaml | Dados de verificação para motores de busca e mídias sociais.
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

##### "logfile"
- Um arquivo legível por humanos para registrar todas as tentativas de acesso bloqueadas. Especifique o nome de um arquivo, ou deixe em branco para desabilitar.

##### "logfileApache"
- Um arquivo no estilo da Apache para registrar todas as tentativas de acesso bloqueadas. Especifique o nome de um arquivo, ou deixe em branco para desabilitar.

##### "logfileSerialized"
- Um arquivo serializado para registrar todas as tentativas de acesso bloqueadas. Especifique o nome de um arquivo, ou deixe em branco para desabilitar.

*Dica útil: Se você quiser, você pode acrescentar informações tempo/hora aos nomes dos seus arquivos de registro através incluir estas em nome: `{yyyy}` para o ano completo, `{yy}` para o ano abreviado, `{mm}` por mês, `{dd}` por dia, `{hh}` por hora.*

*Exemplos:*
- *`logfile='logfile.{yyyy}-{mm}-{dd}-{hh}.txt'`*
- *`logfileApache='access.{yyyy}-{mm}-{dd}-{hh}.txt'`*
- *`logfileSerialized='serial.{yyyy}-{mm}-{dd}-{hh}.txt'`*

##### "truncate"
- Truncar arquivos de log quando atingem um determinado tamanho? Valor é o tamanho máximo em B/KB/MB/GB/TB que um arquivo de log pode crescer antes de ser truncado. O valor padrão de 0KB desativa o truncamento (arquivos de log podem crescer indefinidamente). Nota: Aplica-se a arquivos de log individuais! O tamanho dos arquivos de log não é considerado coletivamente.

##### "log_rotation_limit"
- A rotação de log limita o número de arquivos de log que devem existir a qualquer momento. Quando novos arquivos de log são criados, se o número total de arquivos de log exceder o limite especificado, a ação especificada será executada. Você pode especificar o limite desejado aqui. Um valor de 0 desativará a rotação de log.

##### "log_rotation_action"
- A rotação de log limita o número de arquivos de log que devem existir a qualquer momento. Quando novos arquivos de log são criados, se o número total de arquivos de log exceder o limite especificado, a ação especificada será executada. Você pode especificar a ação desejada aqui. Delete = Deletar os arquivos de log mais antigos, até o limite não seja mais excedido. Archive = Primeiramente arquivar, e então deletar os arquivos de log mais antigos, até o limite não seja mais excedido.

*Esclarecimento técnico: Neste contexto, "mais antigo" significa modificado menos recentemente.*

##### "timeOffset"
- Se o tempo do servidor não coincide com sua hora local, você pode especificar aqui um offset para ajustar as informações de data/tempo gerado por CIDRAM de acordo com as suas necessidades. É geralmente recomendado no lugar para ajustar a directiva fuso horário no seu arquivo `php.ini`, mas às vezes (tais como quando se trabalha com provedores de hospedagem compartilhada e limitados) isto não é sempre possível fazer, e entao, esta opção é fornecido aqui. Offset é em minutos.
- Exemplo (para adicionar uma hora): `timeOffset=60`

##### "timeFormat"
- O formato de notação de data/tempo utilizado pelo CIDRAM. Padrão = `{Day}, {dd} {Mon} {yyyy} {hh}:{ii}:{ss} {tz}`.

##### "ipaddr"
- Onde encontrar o IP endereço das solicitações? (Útil por serviços como o Cloudflare e tal). Padrão = REMOTE_ADDR. ATENÇÃO: Não mude isso a menos que você saiba o que está fazendo!

Valores recomendados para "ipaddr":

Valor | Usando
---|---
`HTTP_INCAP_CLIENT_IP` | Proxy reverso Incapsula.
`HTTP_CF_CONNECTING_IP` | Proxy reverso Cloudflare.
`CF-Connecting-IP` | Proxy reverso Cloudflare (alternativa; se o acima não funcionar).
`HTTP_X_FORWARDED_FOR` | Proxy reverso Cloudbric.
`X-Forwarded-For` | [Proxy reverso Squid](http://www.squid-cache.org/Doc/config/forwarded_for/).
*Definido pela configuração do servidor.* | [Proxy reverso Nginx](https://www.nginx.com/resources/admin-guide/reverse-proxy/).
`REMOTE_ADDR` | Nenhum proxy reverso (valor padrão).

##### "forbid_on_block"
- Qual mensagem de status HTTP deve enviar o CIDRAM ao bloquear solicitações?

Valores atualmente suportados:

Código de status | Mensagem de status | Descrição
---|---|---
`200` | `200 OK` | Valor padrão. Menos robusto, mas mais amigável para os usuários.
`403` | `403 Forbidden` | Um pouco mais robusto, mas um pouco menos amigável para os usuários.
`410` | `410 Gone` | Pode causar problemas ao tentar resolver falsos positivos, pois alguns navegadores armazenam em cache essa mensagem de status e não enviam solicitações subsequentes, mesmo depois de desbloquear os usuários. Pode ser mais útil do que outras opções para reduzir solicitações de certos, muito específicos tipos de bots.
`418` | `418 I'm a teapot` | Na verdade, faz referência a uma piada do primeiro de abril [[RFC 2324](https://tools.ietf.org/html/rfc2324#section-6.5.14)] e é improvável que seja entendida pelo cliente. Fornecido por diversão e conveniência, mas geralmente não é recomendado.
`451` | `Unavailable For Legal Reasons` | Apropriado para contextos em que as solicitações são bloqueadas principalmente por motivos legais. Não recomendado em outros contextos.
`503` | `Service Unavailable` | Mais robusto, mas menos amigável para os usuários.

##### "silent_mode"
- Deve CIDRAM silenciosamente redirecionar as tentativas de acesso bloqueadas em vez de exibir o "Acesso Negado" página? Se sim, especificar o local para redirecionar as tentativas de acesso bloqueadas para. Se não, deixe esta variável em branco.

##### "lang"
- Especificar o padrão da linguagem por CIDRAM.

##### "numbers"
- Especifica como exibir números.

Valores atualmente suportados:

Valor | Produz | Descrição
---|---|---
`NoSep-1` | `1234567.89`
`NoSep-2` | `1234567,89`
`Latin-1` | `1,234,567.89` | Valor padrão.
`Latin-2` | `1 234 567.89`
`Latin-3` | `1.234.567,89`
`Latin-4` | `1 234 567,89`
`Latin-5` | `1,234,567·89`
`China-1` | `123,4567.89`
`India-1` | `12,34,567.89`
`India-2` | `१२,३४,५६७.८९`
`Bengali-1` | `১২,৩৪,৫৬৭.৮৯`
`Arabic-1` | `١٢٣٤٥٦٧٫٨٩`
`Arabic-2` | `١٬٢٣٤٬٥٦٧٫٨٩`
`Thai-1` | `๑,๒๓๔,๕๖๗.๘๙`

*Nota: Esses valores não são padronizados em nenhum lugar, e provavelmente não serão relevantes além do pacote. Adicionalmente, os valores suportados podem mudar no futuro.*

##### "emailaddr"
- Se você desejar, você pode fornecer um endereço de e-mail aqui a ser dado para os usuários quando eles estão bloqueadas, para eles para usar como um ponto de contato para suporte e/ou assistência no caso de eles sendo bloqueado por engano ou em erro. AVISO: Qualquer endereço de e-mail que você fornecer aqui certamente vai ser adquirido por spambots e raspadores/scrapers durante o curso de seu ser usada aqui, e assim, é fortemente recomendado que, se você optar por fornecer um endereço de e-mail aqui, que você garantir que o endereço de email você fornecer aqui é um endereço descartável e/ou um endereço que você não é importante (em outras palavras, você provavelmente não quer usar seu pessoal principal ou negócio principal endereço de e-mail).

##### "emailaddr_display_style"
- Como você prefere que o endereço de e-mail seja apresentado aos usuários? "default" = Link clicável. "noclick" = Texto não-clicável.

##### "disable_cli"
- Desativar o modo CLI? O modo CLI é ativado por padrão, mas às vezes pode interferir com certas testes ferramentas (tal como PHPUnit, por exemplo) e outras aplicações baseadas em CLI. Se você não precisa desativar o modo CLI, você deve ignorar esta directiva. False = Ativar o modo CLI [Padrão]; True = Desativar o modo CLI.

##### "disable_frontend"
- Desativar o acesso front-end? Acesso front-end pode fazer CIDRAM mais manejável, mas também pode ser um risco de segurança potencial, também. É recomendado para gerenciar CIDRAM através do back-end, sempre que possível, mas o acesso front-end é proporcionada para quando não é possível. Mantê-lo desativado, a menos que você precisar. False = Ativar o acesso front-end; True = Desativar o acesso front-end [Padrão].

##### "max_login_attempts"
- Número máximo de tentativas de login (front-end). Padrão = 5.

##### "FrontEndLog"
- Arquivo para registrar tentativas de login ao front-end. Especifique o nome de um arquivo, ou deixe em branco para desabilitar.

##### "ban_override"
- Sobrepor "forbid_on_block" quando "infraction_limit" é excedido? Quando sobrepõe: As solicitações bloqueadas retornam uma página em branco (os arquivos de modelo não são usados). 200 = Não sobrepor [Padrão]. Outros valores são os mesmos que os valores disponíveis para "forbid_on_block".

##### "log_banned_ips"
- Incluir solicitações bloqueadas de IPs banidas nos arquivos de log? True = Sim [Padrão]; False = Não.

##### "default_dns"
- Uma lista delimitada por vírgulas de servidores DNS a serem usados para pesquisas de nomes de host. Padrão = "8.8.8.8,8.8.4.4" (Google DNS). ATENÇÃO: Não mude isso a menos que você saiba o que está fazendo!

*Veja também: [O que posso usar para "default_dns"?](#WHAT_CAN_I_USE_FOR_DEFAULT_DNS)*

##### "search_engine_verification"
- Tentativa de verificar solicitações dos motores de busca? Verificando os motores de busca garante que eles não serão banidos como resultado de exceder o limite de infrações (proibindo motores de busca de seu site normalmente terá um efeito negativo sobre o seu motor de busca ranking, SEO, etc). Quando verificado, os motores de busca podem ser bloqueados como por normal, mas não serão banidos. Quando não verificado, é possível que eles serão banidos como resultado de ultrapassar o limite de infrações. Também, a verificação dos motores de busca fornece proteção contra falsos solicitações de motores de busca e contra entidades potencialmente mal-intencionadas mascarando como motores de busca (tais solicitações serão bloqueados quando a verificação dos motores de busca estiver ativada). True = Ativar a verificação dos motores de busca [Padrão]; False = Desativar a verificação dos motores de busca.

Atualmente suportado:
- __[Google](https://support.google.com/webmasters/answer/80553?hl=en)__
- __[Bing](https://blogs.bing.com/webmaster/2012/08/31/how-to-verify-that-bingbot-is-bingbot)__
- __Yahoo__
- __[Baidu (百度)](https://help.baidu.com/question?prod_en=master&class=Baiduspider)__
- __Sogou (搜狗)__
- __Youdao (有道)__
- __[Applebot](https://discussions.apple.com/thread/7090135)__
- __[Yandex (Яндекс)](https://yandex.com/support/webmaster/robot-workings/check-yandex-robots.xml)__
- __[DuckDuckGo](https://duckduckgo.com/duckduckbot)__

Não compatível (causa conflitos):
- __[Mix.com](https://github.com/CIDRAM/CIDRAM/issues/80)__

##### "social_media_verification"
- Tentativa de verificar solicitações de mídia social? A verificação de mídia social fornece proteção contra solicitações falsas de mídia social (essas solicitações serão bloqueadas). True = Ativar a verificação de mídia social [Padrão]; False = Desativar a verificação de mídia social.

Atualmente suportado:
- __[Pinterest](https://help.pinterest.com/en/articles/about-pinterest-crawler-0)__
- __Embedly__
- __[Grapeshot](https://www.grapeshot.com/crawler/)__
- __Twitterbot__

##### "protect_frontend"
- Especifica se as proteções normalmente fornecidas pelo CIDRAM devem ser aplicadas ao front-end. True = Sim [Padrão]; False = Não.

##### "disable_webfonts"
- Desativar webfonts? True = Sim [Padrão]; False = Não.

##### "maintenance_mode"
- Ativar o modo de manutenção? True = Sim; False = Não [Padrão]. Desativa tudo além do front-end. Às vezes útil para quando atualiza seu CMS, frameworks, etc.

##### "default_algo"
- Define qual algoritmo usar para todas as futuras senhas e sessões. Opções: PASSWORD_DEFAULT (padrão), PASSWORD_BCRYPT, PASSWORD_ARGON2I (requer PHP >= 7.2.0).

##### "statistics"
- Monitorar as estatísticas de uso do CIDRAM? True = Sim; False = Não [Padrão].

##### "force_hostname_lookup"
- Forçar pesquisas de nome de anfitrião? True = Sim; False = Não [Padrão]. As pesquisas de nome de anfitrião normalmente são realizadas com base na necessidade, mas pode ser forçado para todos os solicitações. Isso pode ser útil como forma de fornecer informações mais detalhadas nos arquivos de log, mas também pode ter um efeito ligeiramente negativo sobre o desempenho.

##### "allow_gethostbyaddr_lookup"
- Permitir pesquisas gethostbyaddr quando o UDP não está disponível? True = Sim [Padrão]; False = Não.
- *Nota: A pesquisa do IPv6 pode não funcionar corretamente em alguns sistemas de 32-bits.*

##### "hide_version"
- Ocultar informações da versão dos logs e da saída da página? True = Sim; False = Não [Padrão].

#### "signatures" (Categoria)
Configuração por assinaturas.

##### "ipv4"
- Uma lista dos arquivos de assinaturas IPv4 que CIDRAM deve tentar usar, delimitado por vírgulas. Você pode adicionar entradas aqui Se você quiser incluir arquivos adicionais em CIDRAM.

##### "ipv6"
- Uma lista dos arquivos de assinaturas IPv6 que CIDRAM deve tentar usar, delimitado por vírgulas. Você pode adicionar entradas aqui Se você quiser incluir arquivos adicionais em CIDRAM.

##### "block_cloud"
- Bloquear CIDRs identificado como pertencente a webhosting e/ou serviços em nuvem? Se você operar um serviço de API a partir do seu site ou se você espera outros sites para se conectar para o seu site, este deve ser definido como false. Se não, este deve ser definido como true.

##### "block_bogons"
- Bloquear bogon/martian CIDRs? Se você espera conexões para o seu site de dentro de sua rede local, de localhost, ou de seu LAN, esta directiva deve ser definido como false. Se você não esperar que esses tais conexões, esta directiva deve ser definido como true.

##### "block_generic"
- Bloquear CIDRs geralmente recomendado para a lista negra? Isso abrange todas as assinaturas que não são marcados como sendo parte de qualquer um dos outros mais categorias de assinaturas mais específica.

##### "block_legal"
- Bloquear CIDRs em resposta a obrigações legais? Esta diretiva normalmente não deve ter qualquer efeito, porque o CIDRAM não associa nenhum CIDR com "obrigações legais" por padrão, mas existe, no entanto, como uma medida de controle adicional para o benefício de quaisquer arquivos de assinatura ou módulos personalizados que possam existir por motivos legais.

##### "block_malware"
- Bloquear IPs associados ao malware? Isso inclui servidores C&C, máquinas infectadas, máquinas envolvidas na distribuição de malware, etc.

##### "block_proxies"
- Bloquear CIDRs identificado como pertencente a serviços de proxy ou VPNs? Se você precisar que os usuários poderão acessar seu site dos serviços de proxy e VPNs, este deve ser definido como false. De outra forma, se você não precisa de serviços de proxy ou VPNs, este deve ser definido como true como um meio de melhorar a segurança.

##### "block_spam"
- Bloquear CIDRs identificado como sendo de alto risco para spam? A menos que você tiver problemas ao fazê-lo, geralmente, esta deve sempre ser definido como true.

##### "modules"
- Uma lista de arquivos módulo a carregar depois de processamento as assinaturas IPv4/IPv6, delimitado por vírgulas.

##### "default_tracktime"
- Quantos segundos para rastrear IPs banidos por módulos. Padrão = 604800 (1 semana).

##### "infraction_limit"
- Número máximo de infrações que um IP pode incorrer antes de ser banido por monitoração IP. Padrão = 10.

##### "track_mode"
- Quando as infrações devem ser contadas? False = Quando os IPs são bloqueados por módulos. True = Quando os IPs são bloqueados por qualquer motivo.

#### "recaptcha" (Categoria)
Opcionalmente, você pode fornecer aos usuários uma maneira de contornar a página de "Acesso Negado" por meio de completar uma instância de reCAPTCHA, se você quiser fazê-lo. Isso pode ajudar a mitigar alguns dos riscos associados com falsos positivos nas situações em que não estamos inteiramente certo se uma solicitação tem originado a partir de uma máquina ou um ser humano.

Devido aos riscos associados com fornecimento de uma maneira para os usuários a ignorar a página de "Acesso Negado", geralmente, gostaria de aconselhar contra habilitação deste recurso a menos que você sente que isso é necessário para fazê-lo. Situações em que poderia ser necessário: Se seu site tem clientes/usuários que precisam ter acesso ao seu site, e se essa é algo que não pode ser comprometida em, mas se os clientes/usuários acontecer a ser conectando a partir de uma rede hostil que poderiam ser também carregando de tráfego indesejável, e bloqueando este tráfego indesejável também é algo que não pode ser comprometida em, nessas situações particulares sem vitória, o recurso reCAPTCHA poderia ser útil como um meio de permitir que os clientes/usuários desejáveis, enquanto mantendo fora o tráfego indesejável a partir da mesma rede. Dito isto, considerando que o propósito de um CAPTCHA é distinguir entre humanos e não-humanos, o recurso reCAPTCHA só iria ajudar nestas situações sem vitória se fosse para supor que este tráfego indesejável é não-humano (por exemplo, spambots, raspadores, ferramentas de hackers, tráfego automatizado), ao contrário de ser tráfego humano indesejável (tais como spammer humanos, hackers, et ai).

Para obter uma "site key" e uma "secret key" (necessário para usando reCAPTCHA), por favor vá a: [https://developers.google.com/recaptcha/](https://developers.google.com/recaptcha/)

##### "usemode"
- Define como CIDRAM deve usar reCAPTCHA.
- 0 = reCAPTCHA é completamente desativado (padrão).
- 1 = reCAPTCHA é ativado para todas as assinaturas.
- 2 = reCAPTCHA é ativado apenas para assinaturas pertencentes a seções especialmente marcados dentro dos arquivos de assinatura.
- (Qualquer outro valor será tratado da mesma maneira como 0).

##### "lockip"
- Especifica se hashes deve ser ligado para IPs específicos. False = Cookies e hashes PODE ser usado por vários IPs (padrão). True = Cookies e hashes NÃO pode ser usado por vários IPs (cookies/hashes não estão ligados para IPs).
- Notar: O valor de "lockip" é ignorado quando "lockuser" é false, devido a que o mecanismo para lembrar "usuários" varia de acordo com este valor.

##### "lockuser"
- Especifica se a conclusão bem sucedida de uma instância de reCAPTCHA deve ser ligado a usuários específicos. False = A conclusão bem sucedida de uma instância de reCAPTCHA irá conceder acesso a todos as solicitações provenientes do mesmo IP como aquilo utilizado pelo utilizador completar a instância de reCAPTCHA; Cookies e hashes não são usados; Em vez disso, um IP whitelist será usado. True = A conclusão bem sucedida de uma instância de reCAPTCHA só irá conceder acesso para o usuário completar a instância de reCAPTCHA; Cookies e hashes são usados para lembrar o usuário; Um IP whitelist não é usado (padrão).

##### "sitekey"
- Este valor deve corresponder ao "site key" para o seu reCAPTCHA, que pode ser encontrado dentro do painel de reCAPTCHA.

##### "secret"
- Este valor deve corresponder ao "secret key" para o seu reCAPTCHA, que pode ser encontrado dentro do painel de reCAPTCHA.

##### "expiry"
- Quando "lockuser" é true (padrão), a fim de lembrar quando um usuário tenha já passou com êxito uma instância de reCAPTCHA, para solicitações de página no futuro, CIDRAM gera um cookie HTTP norma contendo um hash que corresponde a um registro interno que contém a mesma hash; Solicitações de página no futuro vai usar esses hashes correspondentes para autenticar que o usuário tenha previamente já passou com êxito uma instância de reCAPTCHA. Quando "lockuser" é false, um IP whitelist é usado para determinar se as solicitações devem ser permitidas do IP de solicitações de entrada; As entradas são adicionadas a esta whitelist quando a instância de reCAPTCHA passou com êxito. Por quantas horas devem estes cookies, hashes e entradas de o whitelist permanecem válidos? Padrão = 720 (1 mês).

##### "logfile"
- Registrar todas as tentativas de reCAPTCHA? Se sim, especificar o nome a ser usado para o arquivo de registro. Se não, deixe esta variável em branco.

*Dica útil: Se você quiser, você pode acrescentar informações tempo/hora aos nomes dos seus arquivos de registro através incluir estas em nome: `{yyyy}` para o ano completo, `{yy}` para o ano abreviado, `{mm}` por mês, `{dd}` por dia, `{hh}` por hora.*

*Exemplos:*
- *`logfile='recaptcha.{yyyy}-{mm}-{dd}-{hh}.txt'`*

##### "signature_limit"
- O número máximo de assinaturas que podem ser desencadeado quando uma instância de reCAPTCHA deve ser oferecida. Padrão = 1. Se este número for excedido para qualquer solicitação específica, uma instância de reCAPTCHA não será oferecida.

##### "api"
- Qual API usar? V2 ou Invisible?

*Nota para usuários na União Européia: Quando o CIDRAM está configurado para usar cookies (por exemplo, quando "lockuser" é true/verdadeiro), um aviso de cookie é exibido de forma proeminente na página de acordo com os requisitos da [legislação comunitária sobre cookies](http://ec.europa.eu/ipg/basics/legal/cookies/index_en.htm). Mas, ao usar a API invisible, o CIDRAM tenta completar o reCAPTCHA para o usuário automaticamente, e quando bem-sucedido, isso pode resultar na recarga da página e criar um cookie sem que o usuário tenha dado tempo suficiente para realmente ver o aviso de cookie. Se isso representa um risco legal para você, talvez seja melhor usar a API V2 em vez da API invisible (a API V2 não é automatizada e exige que o usuário complete o desafio reCAPTCHA, proporcionando assim uma oportunidade para ver o aviso de cookie).*

#### "legal" (Categoria)
Configuração relacionada aos requisitos legais.

*Para obter mais informações sobre requisitos legais e como isso pode afetar seus requisitos de configuração, consulte a seção "[INFORMAÇÃO LEGAL](#SECTION11)" da documentação.*

##### "pseudonymise_ip_addresses"
- Pseudonimiza endereços IP ao escrever os arquivos de log? True = Sim; False = Não [Padrão].

##### "omit_ip"
- Omitir endereços IP de logs? True = Sim; False = Não [Padrão]. Nota: "pseudonymise_ip_addresses" se torna redundante quando "omit_ip" é "true".

##### "omit_hostname"
- Omitir nomes de host de logs? True = Sim; False = Não [Padrão].

##### "omit_ua"
- Omitir agentes de usuários de logs? True = Sim; False = Não [Padrão].

##### "privacy_policy"
- O endereço de uma política de privacidade relevante a ser exibida no rodapé de qualquer página gerada. Especifique um URL, ou deixe em branco para desabilitar.

#### "template_data" (Categoria)
Directivas/Variáveis para modelos e temas.

Relaciona-se com a saída HTML usado para gerar a página "Acesso Negado". Se você estiver usando temas personalizados para CIDRAM, HTML é originado a partir do `template_custom.html` arquivo, e caso contrário, HTML é originado a partir do `template.html` arquivo. Variáveis escritas para esta seção do configuração arquivo são processado ao HTML via substituição de quaisquer nomes de variáveis cercado por colchetes encontrado dentro do HTML com os variáveis dados correspondentes. Por exemplo, onde `foo="bar"`, qualquer instância de `<p>{foo}</p>` encontrado dentro do HTML tornará `<p>bar</p>`.

##### "theme"
- Tema padrão a ser usado para CIDRAM.

##### "Magnification"
- Ampliação de fonte. Padrão = 1.

##### "css_url"
- O template arquivo para temas personalizados utiliza CSS propriedades externos, enquanto que o template arquivo para o padrão tema utiliza CSS propriedades internos. Para instruir CIDRAM para usar o template arquivo para temas personalizados, especificar o endereço HTTP pública do seu temas personalizados CSS arquivos usando a `css_url` variável. Se você deixar essa variável em branco, CIDRAM usará o template arquivo para o padrão tema.

---


### 7. <a name="SECTION7"></a>FORMATO DE ASSINATURAS

*Veja também:*
- *[O que é uma "assinatura"?](#WHAT_IS_A_SIGNATURE)*

#### 7.0 NOÇÕES BÁSICAS (PARA ARQUIVOS DE ASSINATURA)

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
- Legal
- Malware

#### 7.1 ETIQUETAS

##### 7.1.0 ETIQUETAS DE SEÇÃO

Se você quiser dividir suas assinaturas personalizadas em seções individuais, você pode identificar estas seções individuais para o script por adição de uma "etiqueta de seção" imediatamente após as assinaturas de cada seção, juntamente com o nome de sua seção de assinaturas (veja o exemplo abaixo).

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

A mesma lógica pode ser aplicada para separar outros tipos de etiquetas, também.

Em particular, as etiquetas de seção podem ser muito úteis para depuração quando ocorrem falsos positivos, fornecendo um meio fácil de encontrar a fonte exata do problema, e pode ser muito útil para filtrar entradas de arquivos de log ao visualizar arquivos de log por meio da página de logs do front-end (os nomes das seções são clicáveis através da página de logs do front-end e podem ser usados como critérios de filtragem). Se as etiquetas de seção forem omitidas para algumas assinaturas específicas, quando essas assinaturas são desencadeadas, o CIDRAM usa o nome do arquivo de assinatura juntamente com o tipo de endereço IP bloqueado (IPv4 ou IPv6) como um retorno, e portanto, as etiquetas de seção são inteiramente opcionais. Embora, eles podem ser recomendados em alguns casos, como quando os arquivos de assinatura são nomeados vagamente ou quando pode ser difícil identificar claramente a origem das assinaturas fazendo com que um pedido seja bloqueado.

##### 7.1.1 ETIQUETAS DE EXPIRAÇÃO

Se você quiser assinaturas para expirar depois de algum tempo, de um modo semelhante para etiquetas de seção, você pode usar um "etiqueta de expiração" para especificar quando as assinaturas devem deixar de ser válida. Etiquetas de expiração usam o formato "AAAA.MM.DD" (veja o exemplo abaixo).

```
# Seção 1.
1.2.3.4/32 Deny Generic
2.3.4.5/32 Deny Generic
Expires: 2016.12.31
```

As assinaturas expiradas nunca serão desencadeadas em qualquer pedido, não importa o que.

##### 7.1.2 ETIQUETAS DE ORIGEM

Se você deseja especificar o país de origem para alguma assinatura específica, você pode fazer isso usando uma "etiqueta de origem". Uma etiqueta de origem aceita um código "[ISO 3166-1 alfa-2](https://pt.wikipedia.org/wiki/ISO_3166-1_alfa-2)" correspondente ao país de origem para as assinaturas às quais se aplica. Esses códigos devem ser escritos em maiúsculas (minúsculas não serão processadas corretamente). Quando uma etiqueta de origem é usada, ela é adicionada à campo "Razão Bloqueada" do entrada de log para quaisquer solicitações bloqueadas como resultado das assinaturas às quais a etiqueta é aplicada.

Se o componente opcional "flags CSS" estiver instalado, ao visualizar arquivos de log na página de logs do front-end, as informações anexadas pelas etiquetas de origem são substituídas pela bandeira do país correspondente a essa informação. Esta informação, seja em sua forma bruta ou como uma bandeira de país, é clicável, e quando clicada, irá filtrar entradas de log por meio de outras entradas de log de identificação semelhante (efetivamente permitindo que aqueles que acessem a página de logs sejam filtrados por país de origem).

Nota: Tecnicamente, esta não é uma forma de geolocalização, pela razão de que isso não envolve a pesquisa de informações específicas relativas a IPs de entrada, mas sim, simplesmente nos permite declarar explicitamente um país de origem para quaisquer solicitações bloqueados por assinaturas específicas. As etiquetas de origem múltipla são permitidas dentro da mesma seção de assinatura.

Exemplo hipotético:

```
1.2.3.4/32 Deny Generic
Origin: CN
2.3.4.5/32 Deny Generic
Origin: FR
4.5.6.7/32 Deny Generic
Origin: DE
6.7.8.9/32 Deny Generic
Origin: US
Tag: Foobar
```

Todas as etiquetas podem ser usadas em conjunto e todas as etiquetas são opcionais (veja o exemplo abaixo).

```
# Seção Exemplo.
1.2.3.4/32 Deny Generic
Origin: US
Tag: Seção Exemplo
Expires: 2016.12.31
```

##### 7.1.3 ETIQUETAS DE DEFERÊNCIA

Quando um grande número de arquivos de assinatura é instalado e usado ativamente, as instalações podem se tornar bastante complexas, e pode haver algumas assinaturas que se sobrepõem. Nestes casos, a fim de evitar que várias assinaturas sobrepostas sejam desencadeados durante eventos de bloco, etiquetas de deferência podem ser usadas para diferir seções de assinatura específicas nos casos em que algum outro arquivo de assinatura específico é instalado e usado ativamente. Isso pode ser útil nos casos em que algumas assinaturas são atualizadas com mais freqüência do que outras, a fim de diferir as assinaturas atualizadas com menos frequência em favor das assinaturas atualizadas com maior frequência.

As etiquetas de deferência são usadas de forma semelhante a outros tipos de etiquetas. O valor da etiqueta deve corresponder a um arquivo de assinatura instalado e usado ativamente para ser diferido.

Exemplo:

```
1.2.3.4/32 Deny Generic
Origin: AA
2.3.4.5/32 Deny Generic
Origin: BB
Defers to: preferred_signatures.dat
```

#### 7.2 YAML

##### 7.2.0 YAML BÁSICOS

Uma forma simplificada de marcação YAML pode ser usado em arquivos de assinatura com a finalidade de definir comportamentos e configurações específicas para as seções de assinaturas individuais. Isto pode ser útil se você quiser que o valor de suas diretivas de configuração para diferir na base de assinaturas individuais e seções de assinatura (por exemplo; se você quiser fornecer um endereço de e-mail para tickets de suporte para quaisquer usuários bloqueados por uma assinatura específica, mas não quer fornecer um endereço de e-mail para tickets de suporte para usuários bloqueados por quaisquer outras assinaturas; se você quiser algumas assinaturas específicas para provocar um redirecionamento página; se você quiser marcar uma seção de assinaturas para uso com reCAPTCHA; Se você quiser registrar tentativas de acesso bloqueadas para separar arquivos na base de assinaturas individuais e/ou seções de assinatura).

Uso de marcação YAML nos arquivos de assinatura é totalmente opcional (isto é, você pode usá-lo se desejar fazê-lo, mas você não é obrigado a fazê-lo), e é capaz de alavancar mais (mas nem todos) das diretivas de configuração.

Nota: Implementação de marcação YAML em CIDRAM é muito simplista e muito limitado; Destina-se a cumprir as exigências específicas para CIDRAM de uma maneira que tem a familiaridade de marcação YAML, mas nem segue nem está de acordo com as especificações oficiais (e portanto, não se comporta da mesma forma como outros implementações mais completas, e pode não ser apropriado para outros projetos).

Em CIDRAM, segmentos de marcação YAML são identificados para o script por três hífens ("---"), e terminar ao lado de seus contendo seções de assinatura por quebras de linha dupla. Um segmento típico de marcação YAML dentro de uma seção de assinaturas consiste de três hífens em uma linha imediatamente após a lista de CIDRs e todas as etiquetas, seguido por uma lista bidimensional de pares chave-valor (primeira dimensão, categorias das diretivas de configuração; segunda dimensão, as diretivas de configuração) para as quais diretivas de configuração deve ser modificada (e em qual valores) sempre que uma assinatura em nisso seção de assinaturas é desencadeada (veja os exemplos abaixo).

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

Quando "usemode" é 2, para "marcar especialmente" seções de assinatura para uso com reCAPTCHA, uma entrada está incluído no segmento de YAML para que a seção de assinatura (veja o exemplo abaixo).

```
# Esta seção usará reCAPTCHA.
1.2.3.4/32 Deny Generic
2.3.4.5/32 Deny Generic
Tag: reCAPTCHA-Enabled
---
recaptcha:
 enabled: true
```

*Nota: Por padrão, uma instância de reCAPTCHA vai SOMENTE ser oferecido ao usuário se reCAPTCHA está habilitado (quer com "usemode" como 1, ou "usemode" como 2 com "enabled" como true), e se exatamente UMA assinatura foi desencadeada (nem mais, nem menos; se assinaturas múltiplas são desencadeadas, uma instância de reCAPTCHA NÃO será oferecida). Contudo, esse comportamento pode ser modificado através da diretiva "signature_limit".*

#### 7.3 AUXILIAR

Em suplemento, se você quiser CIDRAM para ignorar completamente algumas seções específicas dentro de qualquer um dos arquivos de assinatura, você pode usar o arquivo `ignore.dat` para especificar quais seções para ignorar. Em uma nova linha, escreva `Ignore`, seguido por um espaço, seguido do nome da seção que você quer CIDRAM para ignorar (veja o exemplo abaixo).

```
Ignore Seção 1
```

#### 7.4 <a name="MODULE_BASICS"></a>NOÇÕES BÁSICAS (PARA MÓDULOS)

Os módulos podem ser usados para ampliar a funcionalidade do CIDRAM, executar tarefas adicionais, ou processar lógica adicional. Tipicamente, eles são usados quando é necessário bloquear um pedido em uma base diferente do endereço IP de origem (portanto, quando uma assinatura do CIDR não será suficiente para bloquear o pedido). Os módulos são escritos como arquivos PHP e portanto, tipicamente, as assinaturas dos módulos são escritas como código PHP.

Alguns bons exemplos de módulos do CIDRAM podem ser encontrados aqui:
- https://github.com/CIDRAM/CIDRAM-Extras/tree/master/modules

Um modelo para escrever novos módulos pode ser encontrado aqui:
- https://github.com/CIDRAM/CIDRAM-Extras/blob/master/modules/module_template.php

Devido a que os módulos são escritos como arquivos PHP, se você estiver adequadamente familiarizado com a base de códigos CIDRAM, você pode estruturar seus módulos e escreva as assinaturas do módulo, como quiser (em razão do que é possível com o PHP). Mas, para sua própria conveniência, e por uma melhor inteligibilidade mútua entre os módulos existentes e os seus próprios, é recomendável analisar o modelo acima, para poder usar a estrutura e o formato que ele fornece.

*Nota: Se você não está confortável trabalhando com o código PHP, não é recomendável escrever seus próprios módulos.*

Algumas funcionalidades são fornecidas pelo CIDRAM para os módulos a serem usados, o que deve tornar mais simples e fácil de escrever seus próprios módulos. A informação sobre esta funcionalidade é descrita abaixo.

#### 7.5 FUNCIONALIDADE DO MÓDULO

##### 7.5.0 "$Trigger"

As assinaturas do módulo geralmente são escritas com "$Trigger". Na maioria dos casos, esse closure será mais importante do que qualquer outra coisa com a finalidade de escrever módulos.

"$Trigger" aceita 4 parâmetros: "$Condition", "$ReasonShort", "$ReasonLong" (opcional), e "$DefineOptions" (opcional).

A verdade de "$Condition" é avaliada, e se for true/verdade, a assinatura é "desencadeada". Se false/falso, a assinatura *não* é "desencadeada". "$Condition" tipicamente contém código PHP para avaliar uma condição que deve causar um pedido para ser bloqueado.

"$ReasonShort" é citado no campo "Razão Bloqueada" quando a assinatura é "desencadeada".

"$ReasonLong" é uma mensagem opcional a ser exibida para o usuário/cliente para quando eles estão bloqueados, para explicar por que eles foram bloqueados. Usa a mensagem padrão "Acesso Negado" quando omitido.

"$DefineOptions" é uma array opcional contendo pares de chave/valor, usada para definir opções de configuração específicas para a instância de solicitação. As opções de configuração serão aplicadas quando a assinatura é "desencadeada".

"$Trigger" retorna true/verdadeiro quando a assinatura é "desencadeada", e false/falsa quando não é.

Para usar esse closure em seu módulo, lembre-se de herdá-lo do escopo pai:
```PHP
$Trigger = $CIDRAM['Trigger'];
```

##### 7.5.1 "$Bypass"

Os bypass de assinatura geralmente são escritos com "$Bypass".

"$Bypass" aceita 3 parâmetros: "$Condition", "$ReasonShort", e "$DefineOptions" (opcional).

A verdade de "$Condition" é avaliada, e se for true/verdade, o bypass é "desencadeado". Se false/falso, o bypass *não* é "desencadeado". "$Condition" tipicamente contém código PHP para avaliar uma condição *não* que deve causar um pedido para ser bloqueado.

"$ReasonShort" é citado no campo "Razão Bloqueada" quando o bypass é "desencadeado".

"$DefineOptions" é uma array opcional contendo pares de chave/valor, usada para definir opções de configuração específicas para a instância de solicitação. As opções de configuração serão aplicadas quando o bypass é "desencadeado".

"$Bypass" retorna true/verdadeiro quando o bypass é "desencadeado", e false/falsa quando não é.

Para usar esse closure em seu módulo, lembre-se de herdá-lo do escopo pai:
```PHP
$Bypass = $CIDRAM['Bypass'];
```

##### 7.5.2 "$CIDRAM['DNS-Reverse']"

Isso pode ser usado para buscar o nome do host de um endereço IP. Se você quiser criar um módulo para bloquear nomes de host, este closure pode ser útil.

Exemplo:
```PHP
<?php
/** Inherit trigger closure (see functions.php). */
$Trigger = $CIDRAM['Trigger'];

/** Fetch hostname. */
if (empty($CIDRAM['Hostname'])) {
    $CIDRAM['Hostname'] = $CIDRAM['DNS-Reverse']($CIDRAM['BlockInfo']['IPAddr']);
}

/** Example signature. */
if ($CIDRAM['Hostname'] && $CIDRAM['Hostname'] !== $CIDRAM['BlockInfo']['IPAddr']) {
    $Trigger($CIDRAM['Hostname'] === 'www.foobar.tld', 'Foobar.tld', 'Hostname Foobar.tld is not allowed.');
}
```

#### 7.6 VARIABLES DE MÓDULO

Os módulos executam dentro do seu próprio escopo, e quaisquer variáveis definidas por um módulo, não serão acessíveis a outros módulos, nem ao script pai, a menos que estejam armazenados na array "$CIDRAM" (tudo o resto é liberado após a conclusão do módulo).

Abaixo estão algumas das variáveis comuns que podem ser úteis para o seu módulo:

Variável | Descrição
----|----
`$CIDRAM['BlockInfo']['DateTime']` | A data e a hora atuais.
`$CIDRAM['BlockInfo']['IPAddr']` | O endereço IP para a pedido atual.
`$CIDRAM['BlockInfo']['ScriptIdent']` | Versão do CIDRAM.
`$CIDRAM['BlockInfo']['Query']` | A consulta para o pedido atual.
`$CIDRAM['BlockInfo']['Referrer']` | O referente para o pedido atual (se houver).
`$CIDRAM['BlockInfo']['UA']` | O agente do usuário (user agent) para o pedido atual.
`$CIDRAM['BlockInfo']['UALC']` | O agente do usuário (user agent) para o pedido atual (em minúsculas).
`$CIDRAM['BlockInfo']['ReasonMessage']` | A mensagem a ser exibida para o usuário/cliente do pedido atual se eles estiverem bloqueados.
`$CIDRAM['BlockInfo']['SignatureCount']` | O número de assinaturas desencadeadas para o pedido atual.
`$CIDRAM['BlockInfo']['Signatures']` | Informações de referência para qualquer assinatura desencadeada para o pedido atual.
`$CIDRAM['BlockInfo']['WhyReason']` | Informações de referência para qualquer assinatura desencadeada para o pedido atual.

---


### 8. <a name="SECTION8"></a>CONHECIDOS COMPATIBILIDADE PROBLEMAS

Os seguintes pacotes e produtos foram considerados incompatíveis com o CIDRAM:
- __[Endurance Page Cache](https://github.com/CIDRAM/CIDRAM/issues/52)__
- __[Mix.com](https://github.com/CIDRAM/CIDRAM/issues/80)__

Os módulos foram disponibilizados para garantir que os seguintes pacotes e produtos sejam compatíveis com o CIDRAM:
- __[BunnyCDN](https://github.com/CIDRAM/CIDRAM/issues/56)__

*Veja também: [Gráficos de Compatibilidade](https://maikuolan.github.io/Compatibility-Charts/).*

---


### 9. <a name="SECTION9"></a>PERGUNTAS MAIS FREQUENTES (FAQ)

- [O que é uma "assinatura"?](#WHAT_IS_A_SIGNATURE)
- [O que é um "CIDR"?](#WHAT_IS_A_CIDR)
- [O que é um "falso positivo"?](#WHAT_IS_A_FALSE_POSITIVE)
- [CIDRAM pode bloquear países inteiros?](#BLOCK_ENTIRE_COUNTRIES)
- [Com que frequência as assinaturas são atualizadas?](#SIGNATURE_UPDATE_FREQUENCY)
- [Eu encontrei um problema ao usar CIDRAM e eu não sei o que fazer sobre isso! Ajude-me!](#ENCOUNTERED_PROBLEM_WHAT_TO_DO)
- [Eu fui bloqueado pelo CIDRAM de um site que eu quero visitar! Ajude-me!](#BLOCKED_WHAT_TO_DO)
- [Eu quero usar CIDRAM com uma versão PHP mais velha do que 5.4.0; Você pode ajudar?](#MINIMUM_PHP_VERSION)
- [Posso usar uma única instalação do CIDRAM para proteger vários domínios?](#PROTECT_MULTIPLE_DOMAINS)
- [Eu não quero mexer com a instalação deste e fazê-lo funcionar com o meu site; Posso pagar-te para fazer tudo por mim?](#PAY_YOU_TO_DO_IT)
- [Posso contratar você ou qualquer um dos desenvolvedores deste projeto para o trabalho privado?](#HIRE_FOR_PRIVATE_WORK)
- [Preciso de modificações especializadas, customizações, etc; Você pode ajudar?](#SPECIALIST_MODIFICATIONS)
- [Eu sou um desenvolvedor, designer de site, ou programador. Posso aceitar ou oferecer trabalho relacionado a este projeto?](#ACCEPT_OR_OFFER_WORK)
- [Quero contribuir para o projeto; Posso fazer isso?](#WANT_TO_CONTRIBUTE)
- [Posso usar o cron para atualizar automaticamente?](#CRON_TO_UPDATE_AUTOMATICALLY)
- [O que são "infrações"?](#WHAT_ARE_INFRACTIONS)
- [O CIDRAM pode bloquear nomes de host?](#BLOCK_HOSTNAMES)
- [O que posso usar para "default_dns"?](#WHAT_CAN_I_USE_FOR_DEFAULT_DNS)
- [Posso usar o CIDRAM para proteger outras coisas além de sites (por exemplo, servidores de e-mail, FTP, SSH, IRC, etc)?](#PROTECT_OTHER_THINGS)
- [Ocorrerão problemas se eu usar o CIDRAM ao mesmo tempo que usando CDNs ou serviços de cache?](#CDN_CACHING_PROBLEMS)
- [A CIDRAM protegerá meu site contra ataques DDoS?](#DDOS_ATTACKS)
- [Quando eu ativar ou desativar os módulos ou os arquivos de assinatura através da página de atualizações, eles os classificam alfanumericamente na configuração. Posso mudar a maneira como eles são classificados?](#CHANGE_COMPONENT_SORT_ORDER)

#### <a name="WHAT_IS_A_SIGNATURE"></a>O que é uma "assinatura"?

No contexto do CIDRAM, uma "assinatura" refere-se a dados que actuam como um indicador/identificador para algo específico que estamos procurando, geralmente um endereço IP ou CIDR, e inclui algumas instruções para CIDRAM, dizendo-lhe a melhor maneira de responder quando encontrar o que estamos procurando. Uma assinatura típica para o CIDRAM é algo como isto:

Para "arquivos de assinatura":

`1.2.3.4/32 Deny Generic`

Para "módulos":

```PHP
$Trigger(strpos($CIDRAM['BlockInfo']['UA'], 'Foobar') !== false, 'Foobar-UA', 'User agent "Foobar" not allowed.');
```

*Nota: Assinaturas para "arquivos de assinatura", e assinaturas para "módulos", não são a mesma coisa.*

Muitas vezes (mas nem sempre), as assinaturas serão agrupadas em grupos, formando "seções de assinaturas", freqüentemente acompanhado de comentários, marcação e/ou metadados relacionados que podem ser usados para fornecer contexto adicional para as assinaturas e/ou instrução adicional.

#### <a name="WHAT_IS_A_CIDR"></a>O que é um "CIDR"?

"CIDR" é um acrônimo para "Classless Inter-Domain Routing" *[[1](https://pt.wikipedia.org/wiki/CIDR), [2](http://whatismyipaddress.com/cidr)]*, e é este acrônimo que é usado como parte do nome para este pacote, "CIDRAM", que é um acrônimo para "Classless Inter-Domain Routing Access Manager".

No entanto, no contexto do CIDRAM (tal como, dentro desta documentação, dentro das discussões relativas ao CIDRAM, ou dentro dos dados lingüísticos para CIDRAM), sempre que um "CIDR" (singular) ou "CIDRs" (plural) seja mencionado ou referido (e assim por meio do qual usamos essas palavras como substantivos em seu próprio direito, ao contrário de como acrônimos), o que é pretendido e significado por isto é uma sub-rede (ou sub-redes), expresso usando a notação CIDR. A razão que CIDR (ou CIDRs) é usado em vez de sub-rede (ou sub-redes) é deixar claro que é especificamente sub-redes expressas usando a notação CIDR que está sendo referida (pois a notação CIDR é apenas uma das várias maneiras pelas quais as sub-redes podem ser expressas). O CIDRAM poderia, portanto, ser considerado um "gerenciador de acesso para sub-redes".

Embora este duplo significado de "CIDR" possa apresentar alguma ambigüidade em alguns casos, esta explicação, juntamente com o contexto, deve ajudar a resolver essa ambiguidade.

#### <a name="WHAT_IS_A_FALSE_POSITIVE"></a>O que é um "falso positivo"?

O termo "falso positivo" (*alternativamente: "erro de falso positivo"; "alarme falso"*; Inglês: *false positive*; *false positive error*; *false alarm*), descrita de maneira muito simples, e num contexto generalizado, são usadas quando testando para uma condição, para se referir aos resultados desse teste, quando os resultados são positivos (isto é, a condição é determinada para ser "positivo", ou "verdadeiro"), mas espera-se que seja (ou deveria ter sido) negativo (isto é, a condição, na realidade, é "negativo", ou "falso"). Um "falso positivo" pode ser considerado análogo ao "chorando lobo" (em que a condição que está sendo testada é se existe um lobo perto do rebanho, a condição é "falso" em que não há nenhum lobo perto do rebanho, ea condição é relatada como "positivo" pelo pastor por meio de gritando "lobo, lobo"), ou análoga a situações em exames médicos em que um paciente é diagnosticado como tendo alguma doença quando, na realidade, eles não têm essa doença.

Os resultados relacionados a quando testando para uma condição pode ser descrito usando os termos "verdadeiro positivo", "verdadeiro negativo" e "falso negativo". Um "verdadeiro positivo" refere-se a quando os resultados do teste ea real situação da condição são ambos verdadeiros (ou "positivos"), e um "verdadeiro negativo" refere-se a quando os resultados do teste ea real situação da condição são ambos falsos (ou "negativos"); Um "verdadeiro positivo" ou um "verdadeiro negativo" é considerado como sendo uma "inferência correcta". A antítese de um "falso positivo" é um "falso negativo"; Um "falso negativo" refere-se a quando os resultados do teste are negativo (isto é, a condição é determinada para ser "negativo", ou "falso"), mas espera-se que seja (ou deveria ter sido) positivo (isto é, a condição, na realidade, é "positivo", ou "verdadeiro").

No contexto da CIDRAM, estes termos referem-se as assinaturas de CIDRAM eo que/quem eles bloqueiam. Quando CIDRAM bloquear um endereço IP devido ao mau, desatualizados ou incorretos assinatura, mas não deveria ter feito isso, ou quando ele faz isso pelas razões erradas, nos referimos a este evento como um "falso positivo". Quando CIDRAM não consegue bloquear um endereço IP que deveria ter sido bloqueado, devido a ameaças imprevistas, assinaturas em falta ou déficits em suas assinaturas, nos referimos a este evento como um "detecção em falta" ou "missing detection" (que é análogo a um "falso negativo").

Isto pode ser resumido pela seguinte tabela:

&nbsp; | CIDRAM *NÃO* deve bloquear um endereço IP | CIDRAM *DEVE* bloquear um endereço IP
---|---|---
CIDRAM *NÃO* bloquear um endereço IP | Verdadeiro negativo (inferência correcta) | Detecção em falta (análogo a um falso negativo)
CIDRAM *FAZ* bloquear um endereço IP | __Falso positivo__ | Verdadeiro positivo (inferência correcta)

#### <a name="BLOCK_ENTIRE_COUNTRIES"></a>CIDRAM pode bloquear países inteiros?

Sim. A maneira mais fácil de conseguir isso seria instalar algumas das listas opcionais para bloqueando países fornecido por Macmathan. Isso pode ser feito com alguns cliques simples diretamente do front-end página de atualizações, ou, se você preferir o front-end para permanecer desativado, através da baixando-os diretamente da **[página para baixar as listas opcionais para bloqueando países](https://macmathan.info/blocklists)**, carregá-los para o vault, e citando seus nomes nas diretivas de configuração relevantes.

#### <a name="SIGNATURE_UPDATE_FREQUENCY"></a>Com que frequência as assinaturas são atualizadas?

A frequência das atualizações varia de acordo com os arquivos de assinatura em questão. Todos os mantenedores dos arquivos de assinatura de CIDRAM geralmente tentam manter suas assinaturas atualizadas como é possível, mas devido a que todos nós temos vários outros compromissos, nossas vidas fora do projeto, e devido a que nenhum de nós é financeiramente compensado (ou pago) para nossos esforços no projeto, um cronograma de atualização preciso não pode ser garantido. Geralmente, as assinaturas são atualizadas sempre que há tempo suficiente para atualizá-las, e geralmente, os mantenedores tentam priorizar com base na necessidade e na frequência com que as mudanças ocorrem entre gamas. Assistência é sempre apreciada se você estiver disposto a oferecer qualquer.

#### <a name="ENCOUNTERED_PROBLEM_WHAT_TO_DO"></a>Eu encontrei um problema ao usar CIDRAM e eu não sei o que fazer sobre isso! Ajude-me!

- Você está usando a versão mais recente do software? Você está usando as versões mais recentes de seus arquivos de assinatura? Se a resposta a qualquer destas duas perguntas é não, tente atualizar tudo primeiro, e verifique se o problema persiste. Se persistir, continue lendo.
- Você já examinou toda a documentação? Se não, por favor, faça isso. Se o problema não puder ser resolvido usando a documentação, continue lendo.
- Você já examinou a **[página de issues](https://github.com/CIDRAM/CIDRAM/issues)**, para ver se o problema foi mencionado antes? Se já foi mencionado antes, verificar se foram fornecidas sugestões, ideias e/ou soluções, e siga conforme necessário para tentar resolver o problema.
- Se o problema ainda persistir, por favor procure ajuda sobre isso através de criando um novo issue na página de issues.

#### <a name="BLOCKED_WHAT_TO_DO"></a>Eu fui bloqueado pelo CIDRAM de um site que eu quero visitar! Ajude-me!

CIDRAM fornece um meio para proprietários de sites para bloquear tráfego indesejável, mas é da responsabilidade dos proprietários do site decidir por si mesmos como eles querem usar CIDRAM. No caso dos falsos positivos relativos aos arquivos de assinatura normalmente incluídos no CIDRAM, correções podem ser feitas, mas no que diz respeito a ser desbloqueado a partir de sites específicos, você precisará tomar isso com os proprietários dos sites em questão. Nos casos em que são feitas correções, pelo menos, eles precisarão atualizar seus arquivos de assinatura e/ou instalação, e em outros casos (tais como, por exemplo, onde eles modificaram sua instalação, criaram suas próprias assinaturas personalizadas, etc), a responsabilidade de resolver o seu problema é inteiramente deles, e está inteiramente fora de nosso controle.

#### <a name="MINIMUM_PHP_VERSION"></a>Eu quero usar CIDRAM com uma versão PHP mais velha do que 5.4.0; Você pode ajudar?

Não. PHP 5.4.0 chegou ao EoL ("End of Life", ou Fim da Vida) oficial em 2014, e suporte de segurança estendido foi terminado em 2015. Como de escrever isso, é 2017, e PHP 7.1.0 já está disponível. Neste momento, suporte é oferecido para o uso do CIDRAM com PHP 5.4.0 e todas as versões PHP mais recentes disponíveis, mas se você tentar usar o CIDRAM com versões mais antigas do PHP, o suporte não será fornecido.

*Veja também: [Gráficos de Compatibilidade](https://maikuolan.github.io/Compatibility-Charts/).*

#### <a name="PROTECT_MULTIPLE_DOMAINS"></a>Posso usar uma única instalação do CIDRAM para proteger vários domínios?

Sim. As instalações do CIDRAM não estão naturalmente atado com domínios específicos, e pode, portanto, ser usado para proteger vários domínios. Geralmente, referimo-nos a instalações do CIDRAM que protegem apenas um domínio como "instalações de singular-domínio", e referimo-nos a instalações do CIDRAM que protegem vários domínios e/ou subdomínios como "instalações multi-domínio". Se você operar uma instalação multi-domínio e precisa usar conjuntos diferentes de arquivos de assinaturas para domínios diferentes, ou precisam CIDRAM para ser configurado de forma diferente para domínios diferentes, é possível fazer isso. Depois de carregar o arquivo de configuração (`config.ini`), o CIDRAM verificará a existência de um "arquivo de sobreposição para a configuração" específico para o domínio (ou subdomínio) que está sendo solicitado (`o-domínio-que-está-sendo-solicitado.tld.config.ini`), e se encontrado, quaisquer valores de configuração definidos pelo arquivo de sobreposição para a configuração serão usados para a instância de execução em vez dos valores de configuração definidos pelo arquivo de configuração. Os arquivos de sobreposição para a configuração são idênticos ao arquivo de configuração, e a seu critério, pode conter a totalidade de todas as diretivas de configuração disponíveis para o CIDRAM, ou qualquer subseção menor necessária que difere dos valores normalmente definidos pelo arquivo de configuração. Os arquivos de sobreposição para a configuração são nomeados de acordo com o domínio que eles são destinados para (por exemplo, se você precisar de um arquivo de sobreposição para a configuração para o domínio, `http://www.some-domain.tld/`, o seu arquivo de sobreposição para a configuração deve ser nomeado como `some-domain.tld.config.ini`, e deve ser colocado dentro da vault ao lado do arquivo de configuração, `config.ini`). O nome de domínio para a instância de execução é derivado do cabeçalho `HTTP_HOST` do pedido; "www" é ignorado.

#### <a name="PAY_YOU_TO_DO_IT"></a>Eu não quero mexer com a instalação deste e fazê-lo funcionar com o meu site; Posso pagar-te para fazer tudo por mim?

Talvez. Isso é considerado caso a caso. Deixe-nos saber do que você precisa, o que você está oferecendo, e nós vamos deixar você saber se podemos ajudar.

#### <a name="HIRE_FOR_PRIVATE_WORK"></a>Posso contratar você ou qualquer um dos desenvolvedores deste projeto para o trabalho privado?

*Veja acima.*

#### <a name="SPECIALIST_MODIFICATIONS"></a>Preciso de modificações especializadas, customizações, etc; Você pode ajudar?

*Veja acima.*

#### <a name="ACCEPT_OR_OFFER_WORK"></a>Eu sou um desenvolvedor, designer de site, ou programador. Posso aceitar ou oferecer trabalho relacionado a este projeto?

Sim. Nossa licença não proíbe isso.

#### <a name="WANT_TO_CONTRIBUTE"></a>Quero contribuir para o projeto; Posso fazer isso?

Sim. As contribuições para o projeto são muito bem-vindas. Consulte "CONTRIBUTING.md" para obter mais informações.

#### <a name="CRON_TO_UPDATE_AUTOMATICALLY"></a>Posso usar o cron para atualizar automaticamente?

Sim. Uma API é integrada no front-end para interagir com a página de atualizações por meio de scripts externos. Um script separado, "[Cronable](https://github.com/Maikuolan/Cronable)", está disponível, e pode ser usado pelo seu cron manager ou cron scheduler para atualizar este e outros pacotes suportados automaticamente (este script fornece sua própria documentação).

#### <a name="WHAT_ARE_INFRACTIONS"></a>O que são "infrações"?

"Infrações" determinam quando um IP que ainda não está bloqueado por qualquer arquivo de assinatura específico deve começar a ser bloqueado para quaisquer solicitações futuros, e estão intimamente associados ao monitoração IP. Existem algumas funcionalidades e módulos que permitem que os solicitações sejam bloqueados por motivos diferentes do IP de origem (tais como a presença de agentes de usuários [user agents] correspondentes a spambots ou hacktools, solicitações perigosas, DNS falsificado e assim por diante), e quando isso acontece, uma "infração" pode ocorrer. Eles fornecem uma maneira de identificar endereços IP que correspondem a solicitações indesejadas que podem ainda não ser bloqueadas por arquivos de assinatura específicos. Infrações geralmente correspondem 1-a-1 com o número de vezes que um IP está bloqueado, mas nem sempre (eventos de bloqueio severo podem ter um valor de infração maior do que um, e se "track_mode" for false, infrações não ocorrerão para eventos de bloco desencadeados exclusivamente por arquivos de assinatura).

#### <a name="BLOCK_HOSTNAMES"></a>O CIDRAM pode bloquear nomes de host?

Sim. Para fazer isso, você precisará criar um arquivo de módulo personalizado. *Vejo: [NOÇÕES BÁSICAS (PARA MÓDULOS)](#MODULE_BASICS)*.

#### <a name="WHAT_CAN_I_USE_FOR_DEFAULT_DNS"></a>O que posso usar para "default_dns"?

Geralmente, o IP de qualquer servidor DNS confiável deve ser suficiente. Se você está procurando sugestões, [public-dns.info](https://public-dns.info/) e [OpenNIC](https://servers.opennic.org/) fornecem listas extensas de servidores DNS públicos conhecidos. Alternativamente, veja a tabela abaixo:

IP | Operador
---|---
`1.1.1.1` | [Cloudflare](https://www.cloudflare.com/learning/dns/what-is-1.1.1.1/)
`4.2.2.1`<br />`4.2.2.2`<br />`209.244.0.3`<br />`209.244.0.4` | [Level3](https://www.level3.com/en/)
`8.8.4.4`<br />`8.8.8.8`<br />`2001:4860:4860::8844`<br />`2001:4860:4860::8888` | [Google Public DNS](https://developers.google.com/speed/public-dns/)
`9.9.9.9`<br />`149.112.112.112` | [Quad9 DNS](https://www.quad9.net/)
`84.200.69.80`<br />`84.200.70.40`<br />`2001:1608:10:25::1c04:b12f`<br />`2001:1608:10:25::9249:d69b` | [DNS.WATCH](https://dns.watch/index)
`208.67.220.220`<br />`208.67.222.220`<br />`208.67.222.222` | [OpenDNS Home](https://www.opendns.com/)
`77.88.8.1`<br />`77.88.8.8`<br />`2a02:6b8::feed:0ff`<br />`2a02:6b8:0:1::feed:0ff` | [Yandex.DNS](https://dns.yandex.com/advanced/)
`8.20.247.20`<br />`8.26.56.26` | [Comodo Secure DNS](https://www.comodo.com/secure-dns/)
`216.146.35.35`<br />`216.146.36.36` | [Dyn](https://help.dyn.com/internet-guide-setup/)
`64.6.64.6`<br />`64.6.65.6` | [Verisign Public DNS](https://www.verisign.com/en_US/security-services/public-dns/index.xhtml)
`37.235.1.174`<br />`37.235.1.177` | [FreeDNS](https://freedns.zone/en/)
`156.154.70.1`<br />`156.154.71.1`<br />`2610:a1:1018::1`<br />`2610:a1:1019::1` | [Neustar Security](https://www.security.neustar/dns-services/free-recursive-dns-service)
`45.32.36.36`<br />`45.77.165.194`<br />`179.43.139.226` | [Fourth Estate](https://dns.fourthestate.co/)
`74.82.42.42` | [Hurricane Electric](https://dns.he.net/)
`195.46.39.39`<br />`195.46.39.40` | [SafeDNS](https://www.safedns.com/en/features/)
`81.218.119.11`<br />`209.88.198.133` | [GreenTeam Internet](http://www.greentm.co.uk/)
`89.233.43.71`<br />`91.239.100.100 `<br />`2001:67c:28a4::`<br />`2a01:3a0:53:53::` | [UncensoredDNS](https://blog.uncensoreddns.org/)
`208.76.50.50`<br />`208.76.51.51` | [SmartViper](http://www.markosweb.com/free-dns/)

*Nota: Eu não faço reivindicações ou garantias em relação às práticas de privacidade, segurança, eficácia ou confiabilidade de quaisquer serviços de DNS, listados ou não. Por favor, faça sua própria pesquisa ao tomar decisões sobre eles.*

#### <a name="PROTECT_OTHER_THINGS"></a>Posso usar o CIDRAM para proteger outras coisas além de sites (por exemplo, servidores de e-mail, FTP, SSH, IRC, etc)?

Você pode (legalmente), mas não deveria (tecnicamente; praticamente). Nossa licença não limita quais tecnologias implementam o CIDRAM, mas o CIDRAM é um WAF (Web Application Firewall) e sempre foi destinado a proteger sites. Porque não foi projetado com outras tecnologias em mente, é improvável que seja eficaz ou fornecer proteção confiável para outras tecnologias, é provável que a implementação seja difícil, e o risco de falsos positivos e de detecções perdidas é muito alto.

#### <a name="CDN_CACHING_PROBLEMS"></a>Ocorrerão problemas se eu usar o CIDRAM ao mesmo tempo que usando CDNs ou serviços de cache?

Talvez. Isso depende da natureza do serviço em questão e de como você o utiliza. Geralmente, se você estiver armazenando apenas recursos estáticos em cache (imagens, CSS, etc; qualquer coisa que geralmente não muda com o tempo), não haverá problemas. Mas, pode haver problemas, se você estiver armazenando dados em cache que normalmente seriam gerados dinamicamente quando solicitados, ou se você estiver armazenando em cache os resultados de solicitações POST (isso essencialmente tornaria seu site e seu ambiente como obrigatoriamente estáticos, e é improvável que a CIDRAM forneça qualquer benefício significativo em um ambiente obrigatoriamente estático). Também pode haver requisitos de configuração específicos para o CIDRAM, dependendo de qual CDN ou serviço de cache você está usando (você precisará garantir que o CIDRAM esteja configurado corretamente para o CDN específico ou serviço de cache que você está usando). A falha em configurar o CIDRAM corretamente pode levar a falsos positivos e a detecções perdidas.

#### <a name="DDOS_ATTACKS"></a>A CIDRAM protegerá meu site contra ataques DDoS?

Resposta curta: Não.

Resposta ligeiramente mais longa: O CIDRAM ajudará a reduzir o impacto que o tráfego indesejado pode ter em seu website (reduzindo assim os custos de largura de banda do seu site), ajudará a reduzir o impacto que o tráfego indesejado pode causar em seu hardware (por exemplo, a capacidade do seu servidor de processar e atender solicitações), e poderá ajudar a reduzir vários outros possíveis efeitos negativos associados ao tráfego indesejado. No entanto, há duas coisas importantes que devem ser lembradas para entender essa questão.

Em primeiro lugar, o CIDRAM é um pacote PHP e, portanto, opera na máquina em que o PHP está instalado. Isso significa que o CIDRAM só pode ver e bloquear uma solicitação *após* o servidor já ter recebido. Em segundo lugar, a [mitigação de DDoS](https://pt.wikipedia.org/wiki/Mitiga%C3%A7%C3%A3o_de_ataques_DDoS) eficaz deve filtrar as solicitações *antes* que elas atinjam o servidor alvo do ataque DDoS. Idealmente, os ataques DDoS devem ser detectados e mitigados por soluções capazes de eliminar ou reencaminhar o tráfego associado a ataques, antes de atingir o servidor de destino em primeiro lugar.

Isso pode ser implementado usando soluções de hardware dedicadas no local, e/ou soluções baseadas em nuvem, como serviços dedicados de mitigação de DDoS, roteamento de DNS de um domínio por meio de redes resistentes a DDoS, filtragem baseada em nuvem, ou alguma combinação dos mesmos. Em todo caso, esse assunto é um pouco complexo demais para ser explicado com apenas um ou dois parágrafos, então eu recomendaria fazer mais pesquisas se este é um assunto que você deseja seguir. Quando a verdadeira natureza dos ataques DDoS é entendida corretamente, essa resposta fará mais sentido.

#### <a name="CHANGE_COMPONENT_SORT_ORDER"></a>Quando eu ativar ou desativar os módulos ou os arquivos de assinatura através da página de atualizações, eles os classificam alfanumericamente na configuração. Posso mudar a maneira como eles são classificados?

Sim. Se você precisar forçar alguns arquivos a serem executados em uma ordem específica, você pode adicionar alguns dados arbitrários antes de seus nomes na diretiva de configuração, onde eles estão listados, separados por dois pontos. Quando a página de atualizações subseqüentemente classifica os arquivos novamente, esses dados arbitrários adicionados afetarão a ordem de classificação, fazendo com que eles sejam executados na ordem que você deseja, sem precisar renomear nenhum deles.

Por exemplo, assumindo uma diretiva de configuração com arquivos listados da seguinte maneira:

`file1.php,file2.php,file3.php,file4.php,file5.php`

Se você queria `file3.php` para executar primeiro, você poderia adicionar algo como `aaa:` antes do nome do arquivo:

`file1.php,file2.php,aaa:file3.php,file4.php,file5.php`

Então, se um novo arquivo, `file6.php`, estiver ativado, quando a página de atualizações classifica os novamente, ele deve terminar assim:

`aaa:file3.php,file1.php,file2.php,file4.php,file5.php,file6.php`

Mesma situação quando um arquivo é desativado. Por outro lado, se você quiser que o arquivo seja executado por último, você poderia adicionar algo como `zzz:` antes do nome do arquivo. Em qualquer caso, você não precisará renomear o arquivo em questão.

---


### 11. <a name="SECTION11"></a>INFORMAÇÃO LEGAL

#### 11.0 PREÂMBULO DE SEÇÃO

Esta seção da documentação destina-se a descrever possíveis considerações legais em relação ao uso e implementação do pacote, e fornecer algumas informações básicas relacionadas. Isso pode ser importante para alguns usuários como um meio de garantir a conformidade com quaisquer requisitos legais que possam existir nos países nos quais eles operam, e alguns usuários podem precisar ajustar as políticas do site de acordo com essas informações.

Em primeiro lugar por favor, perceba que eu (o autor do pacote) não sou advogado, nem profissional legal qualificado de qualquer tipo. Portanto, não estou legalmente qualificado para fornecer aconselhamento jurídico. Além disso, em alguns casos, exigências legais exatas podem variar entre diferentes países e jurisdições, e estes requisitos legais variados podem, às vezes, conflitar (como, por exemplo, no caso de países que defendem os [direitos de privacidade](https://pt.wikipedia.org/wiki/Direito_%C3%A0_privacidade) e o [direito de ser esquecido](https://pt.wikipedia.org/wiki/Direito_ao_esquecimento), versus países que favorecem a retenção prolongada de dados). Considere também que o acesso ao pacote não está restrito a países ou jurisdições específicos e, portanto, o pacote userbase é provável que seja geograficamente diverso. Estes pontos considerados, eu não estou em posição de afirmar o que significa ser "legalmente compatível" para todos os usuários, em todos os aspectos. No entanto, espero que as informações aqui contidas o ajudem a chegar a uma decisão sobre o que você deve fazer para permanecer legalmente conforme no contexto do pacote. Se tiver alguma dúvida ou preocupação em relação às informações aqui contidas, ou se você precisar de ajuda e conselhos adicionais de uma perspectiva legal, eu recomendaria consultar um profissional legal qualificado.

#### 11.1 RESPONSABILIDADE

Conforme já declarado pela licença do pacote, o pacote é fornecido sem qualquer garantia. Isso inclui (mas não está limitado a) todo o escopo de responsabilidade. O pacote é fornecido a você para sua conveniência, na esperança de que seja útil e que traga algum benefício para você. Entretanto, se você usa ou implementa o pacote, é sua própria escolha. Você não é forçado a usar ou implementar o pacote, mas, quando o faz, você é responsável por essa decisão. Nem eu, nem qualquer outro colaborador do pacote, somos legalmente responsáveis pelas consequências das decisões que você toma, independentemente de ser direto, indireto, implícito, ou de outra forma.

#### 11.2 TERCEIROS

Dependendo de sua configuração e implementação exatas, o pacote pode se comunicar e compartilhar informações com terceiros em alguns casos. Essas informações podem ser definidas como "[informação pessoalmente identificável](https://pt.wikipedia.org/wiki/Informa%C3%A7%C3%A3o_pessoalmente_identific%C3%A1vel)" (PII) em alguns contextos, por algumas jurisdições.

Como esta informação pode ser usada por estes terceiros, está sujeita às várias políticas estabelecidas por esses terceiros, e está fora do escopo desta documentação. Contudo, em todos esses casos, o compartilhamento de informações com esses terceiros pode ser desativado. Em todos esses casos, se você optar por ativá-lo, é sua responsabilidade pesquisar quaisquer preocupações que você possa ter com relação à privacidade, segurança, e uso de PII por esses terceiros. Se houver alguma dúvida, ou se você estiver insatisfeito com a conduta desses terceiros em relação a PII, talvez seja melhor desativar todo o compartilhamento de informações com esses terceiros.

Para fins de transparência, o tipo de informação compartilhada e com quem está descrito abaixo.

##### 11.2.0 PESQUISA DE NOME DE HOST

Se você usar quaisquer funções ou módulos destinados a trabalhar com nomes de host (tais como o "módulo bloqueador de hosts perigosos", o "tor project exit nodes block module", ou "verificação do motores de busca", por exemplo), o CIDRAM precisa ser capaz de obter o nome do host de solicitações de entrada de alguma forma. Tipicamente, ele faz isso solicitando o nome do host do endereço IP das solicitações de entrada de um servidor DNS ou solicitando as informações por meio da funcionalidade fornecida pelo sistema em que o CIDRAM está instalado (isso é tipicamente referido como um "pesquisa de nome de host"). Os servidores DNS definidos por padrão pertencem ao serviço [Google DNS](https://dns.google.com/) (mas isso pode ser facilmente alterado por meio da configuração). Os serviços exatos comunicados com são configuráveis, e dependem de como você configura o pacote. No caso de usar a funcionalidade fornecida pelo sistema em que o CIDRAM está instalado, você precisará entrar em contato com o administrador do sistema para determinar quais rotas as pesquisas de nome de host usam. Pesquisas de nome de host podem ser evitadas no CIDRAM, evitando os módulos afetados ou modificando a configuração do pacote de acordo com suas necessidades.

*Diretivas de configuração relevantes:*
- `general` -> `default_dns`
- `general` -> `search_engine_verification`
- `general` -> `social_media_verification`
- `general` -> `force_hostname_lookup`
- `general` -> `allow_gethostbyaddr_lookup`

##### 11.2.1 WEBFONTS

Alguns temas personalizados, bem como a interface de usuário ("UI") padrão para o front-end do CIDRAM e a página "Acesso Negado", podem usar webfonts por motivos estéticos. Os webfonts são desabilitados por padrão, mas, quando habilitados, ocorre comunicação direta entre o navegador do usuário e o serviço que hospeda o webfonts. Isso pode envolver informações de comunicação, tal como o endereço IP do usuário, o agente do usuário, o sistema operacional, e outros detalhes disponíveis para a solicitação. A maioria desses webfonts é hospedada pelo serviço [Google Fonts](https://fonts.google.com/).

*Diretivas de configuração relevantes:*
- `general` -> `disable_webfonts`

##### 11.2.2 VERIFICAÇÃO DOS MOTORES DE BUSCA E MÍDIAS SOCIAIS

Quando a verificação dos motores de busca é ativada, o CIDRAM tenta executar pesquisas direta DNS para verificar se as solicitações que dizem ter origem nos motores de busca são autênticas. Da mesma forma, quando a verificação de mídia social é ativada, o CIDRAM faz o mesmo para solicitações aparentes de mídia social. Para fazer isso, ele usa o serviço [Google DNS](https://dns.google.com/) para tentar resolver endereços IP dos nomes de host dessas solicitações de entrada (nesse processo, os nomes de host dessas solicitações de entrada são compartilhados com o serviço).

*Diretivas de configuração relevantes:*
- `general` -> `search_engine_verification`
- `general` -> `social_media_verification`

##### 11.2.3 GOOGLE reCAPTCHA

O CIDRAM suporta opcionalmente o [Google reCAPTCHA](https://www.google.com/recaptcha/), fornecendo um meio para os usuários ignorarem a página "Acesso Negado", concluindo uma instância de reCAPTCHA (mais informações sobre essa funcionalidade são descritas anteriormente na documentação, mais notavelmente na seção de configuração). O Google reCAPTCHA requer chaves de API para funcionar corretamente e, portanto, é desativado por padrão. Pode ser ativado definindo as chaves da API necessárias na configuração do pacote. Quando ativado, ocorre comunicação direta entre o navegador do usuário e o serviço reCAPTCHA. Isso pode envolver informações de comunicação, como o endereço IP do usuário, o agente do usuário, o sistema operacional, e outros detalhes disponíveis para a solicitação. O endereço IP do usuário também pode ser compartilhado na comunicação entre o CIDRAM e o serviço reCAPTCHA ao verificar a validade de uma instância do reCAPTCHA e verificar se ela foi concluída com êxito.

*Diretivas de configuração relevantes: Qualquer coisa listada na categoria de configuração "recaptcha".*

##### 11.2.4 STOP FORUM SPAM

O [Stop Forum Spam](https://www.stopforumspam.com/) é um serviço fantástico, disponível gratuitamente, que pode ajudar a proteger fóruns, blogs, e sites de spammers. Ele faz isso fornecendo um banco de dados de spammers conhecidos, e uma API que pode ser aproveitada para verificar se um endereço IP, nome de usuário, ou um endereço de e-mail está listado em seu banco de dados.

O CIDRAM fornece um módulo opcional que aproveita essa API para verificar se o endereço IP de solicitações de entrada pertence a um spammer suspeito. O módulo não é instalado por padrão, mas se você optar por instalá-lo, os endereços IP do usuário podem ser compartilhados com a API do Stop Forum Spam de acordo com a finalidade do módulo. Quando o módulo é instalado, o CIDRAM se comunica com essa API sempre que uma solicitação de entrada solicita um recurso que o CIDRAM reconhece como um tipo de recurso frequentemente alvejado por spammers (tais como páginas de login, páginas de registro, páginas de verificação de e-mail, formulários de comentários, etc).

#### 11.3 REGISTRO

O registro é uma parte importante do CIDRAM por vários motivos. Pode ser difícil diagnosticar e resolver falsos positivos quando os eventos de bloqueio que os causam não são registrados. Sem o registro de eventos de bloqueio, pode ser difícil determinar exatamente o quão bem o CIDRAM funciona em qualquer contexto específico, e pode ser difícil determinar onde suas deficiências podem ser, e quais mudanças podem ser necessárias para sua configuração ou assinaturas de acordo, para que ele continue funcionando como pretendido. Não obstante, o registro pode não ser desejável para todos os usuários e permanece totalmente opcional. No CIDRAM, o registro está desabilitado por padrão. Para ativá-lo, o CIDRAM deve ser configurado de acordo.

Adicionalmente, se o registro é legalmente permissível, e na medida em que é legalmente permissível (por exemplo, os tipos de informações que podem ser registradas, por quanto tempo, e sob quais circunstâncias), pode variar, dependendo da jurisdição e do contexto onde a CIDRAM é implementada (por exemplo, se você está operando como indivíduo, como entidade corporativa, e se está em uma base comercial ou não comercial). Portanto, pode ser útil que você leia atentamente essa seção.

Existem vários tipos de registro que o CIDRAM pode executar. Diferentes tipos de registro envolvem diferentes tipos de informações, por diferentes razões.

##### 11.3.0 OS EVENTOS DE BLOQUEIO

O principal tipo de registro que o CIDRAM pode realizar refere-se a "eventos de bloqueio". Esse tipo do registro está relacionado a quando o CIDRAM bloqueia uma solicitação, e pode ser fornecido em três formatos diferentes:
- Arquivos de log legíveis para humanos.
- Arquivos de log no estilo Apache.
- Arquivos de log serializados.

Um evento de bloqueio, registrado em um arquivo de log legível, normalmente se parece com isso (como um exemplo):

```
ID: 1234
Versão do script: CIDRAM v1.6.0
Data/Hora: Day, dd Mon 20xx hh:ii:ss +0000
Endereço IP: x.x.x.x
Nome do host: dns.hostname.tld
Contagem da assinaturas: 1
Assinaturas referência: x.x.x.x/xx
Razão bloqueada: Serviço de nuvem ("Nome da rede", Lxx:Fx, [XX])!
Agente do usuário: Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.181 Safari/537.36
Reconstruído URI: http://your-site.tld/index.php
Estado reCAPTCHA: Ativado.
```

Esse mesmo evento de bloqueio, registrado em um arquivo de log no estilo Apache, normalmente se parece com isso:

```
x.x.x.x - - [Day, dd Mon 20xx hh:ii:ss +0000] "GET /index.php HTTP/1.1" 200 xxxx "-" "Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.181 Safari/537.36"
```

Um evento de bloqueio registrado geralmente inclui as seguintes informações:
- Um número de ID que referencia o evento de bloqueio.
- A versão do CIDRAM atualmente em uso.
- A data e hora em que o evento de bloqueio ocorreu.
- O endereço IP da solicitação bloqueada.
- O nome do host do endereço IP da solicitação bloqueada (quando disponível).
- O número de assinaturas desencadeadas pela solicitação.
- Referências às assinaturas desencadeadas.
- Referências aos motivos do evento de bloqueio e algumas informações básicas de depuração relacionadas.
- O agente do usuário da solicitação bloqueada (ou seja, como a entidade solicitante se identificou com a solicitação).
- Uma reconstrução do identificador para o recurso originalmente solicitado.
- O estado reCAPTCHA para a solicitação atual (quando relevante).

*As diretivas de configuração responsáveis por esse tipo de log, e para cada um dos três formatos disponíveis, são:*
- `general` -> `logfile`
- `general` -> `logfileApache`
- `general` -> `logfileSerialized`

Quando essas diretivas são deixadas vazias, esse tipo de log permanecerá desabilitado.

##### 11.3.1 REGISTRO DE reCAPTCHA

Esse tipo de registro refere-se especificamente a instâncias de reCAPTCHA, e ocorre apenas quando um usuário tenta concluir uma instância de reCAPTCHA.

Uma entrada de registro reCAPTCHA contém o endereço IP do usuário que está tentando concluir uma instância de reCAPTCHA, a data e a hora em que a tentativa ocorreu, e o estado reCAPTCHA. Uma entrada de registro reCAPTCHA normalmente se parece com isso (como um exemplo):

```
Endereço IP: x.x.x.x - Data/Hora: Day, dd Mon 20xx hh:ii:ss +0000 - Estado reCAPTCHA: Sucesso!
```

*A diretiva de configuração responsável pelo registro de reCAPTCHA é:*
- `recaptcha` -> `logfile`

##### 11.3.2 REGISTRO DO FRONT-END

Esse tipo de registro está associado a tentativas de login no front-end, e ocorre apenas quando um usuário tenta efetuar login no front-end (supondo que o acesso ao front-end esteja ativado).

Uma entrada de registro do front-end contém o endereço IP do usuário que está tentando efetuar login, a data e a hora em que a tentativa ocorreu, e os resultados da tentativa (se teve sucesso ou não). Uma entrada de registro do front-end geralmente se parece com isso (como um exemplo):

```
x.x.x.x - Day, dd Mon 20xx hh:ii:ss +0000 - "admin" - Conectado.
```

*A diretiva de configuração responsável pelo registro do front-end é:*
- `general` -> `FrontEndLog`

##### 11.3.3 ROTAÇÃO DE REGISTRO

Você pode querer purgar os registros após um período de tempo, ou pode ser obrigado a fazê-lo por lei (ou seja, a quantidade de tempo permitida legalmente para você manter registros pode ser limitada por lei). Você pode conseguir isso incluindo marcadores de data/hora nos nomes de seus arquivos de registro conforme especificado pela sua configuração de pacote (por exemplo, `{yyyy}-{mm}-{dd}.log`) e, em seguida, ativando a rotação de registro (a rotação de registro permite que você execute alguma ação nos arquivos de registro quando os limites especificados são excedidos).

Por exemplo: Se eu fosse legalmente obrigado a deletar registros após 30 dias, eu poderia especificar `{dd}.log` nos nomes dos meus arquivos de registro (`{dd}` representa dias), definir o valor de `log_rotation_limit` para 30 e, em seguida, definir o valor de `log_rotation_action` para `Delete`.

Por outro lado, se você precisar reter o registros por um longo período de tempo, você poderia optar por não usar a rotação de registro em tudo, ou você pode definir o valor de `log_rotation_action` para `Archive`, para compactar arquivos de registro, reduzindo assim a quantidade total de espaço em disco que eles ocupam.

*Diretivas de configuração relevantes:*
- `general` -> `log_rotation_limit`
- `general` -> `log_rotation_action`

##### 11.3.4 TRUNCAMENTO DE REGISTRO

Também é possível truncar arquivos de registro individuais quando eles excedem um certo tamanho, se isso for algo que você possa precisar ou desejar fazer.

*Diretivas de configuração relevantes:*
- `general` -> `truncate`

##### 11.3.5 PSEUDONIMIZAÇÃO DE ENDEREÇOS IP

Em primeiro lugar, se você não estiver familiarizado com o termo, "pseudonimização" refere-se ao processamento de dados pessoais como tal que não pode ser identificado a nenhuma pessoa específica sem informações suplementares, e desde que tais informações suplementares sejam mantidas separadamente e sujeitas a medidas técnicas e organizacionais para assegurar que os dados pessoais não possam ser identificados a nenhuma pessoa natural.

Em algumas circunstâncias, você pode ser legalmente obrigado a anonimizar ou pseudonimizar qualquer PII coletada, processada ou armazenada. Embora este conceito já existe há algum tempo, o GDPR/DSGVO menciona notavelmente, e especificamente incentiva a "pseudonimização".

O CIDRAM é capaz de pseudonimizar endereços IP ao registrá-los, se isso for algo que você possa precisar ou desejar fazer. Quando o CIDRAM pseudonimiza os endereços IP, quando registrado, o octeto final dos endereços IPv4, e tudo após a segunda parte dos endereços IPv6 é representado por um "x" (efetivamente arredondando endereços IPv4 para o endereço inicial da 24ª sub-rede em que eles são fatorados em, e endereços IPv6 para o endereço inicial da 32ª sub-rede em que eles são fatorados em).

*Nota: O processo de pseudonimização de endereços IP no CIDRAM não afeta a funcionalidade de monitoração IP no CIDRAM. Se isso for um problema para você, talvez seja melhor desabilitar totalmente o monitoramento de IP. Isto pode ser conseguido ajustando `track_mode` para `false` e evitando quaisquer módulos.*

*Diretivas de configuração relevantes:*
- `signatures` -> `track_mode`
- `legal` -> `pseudonymise_ip_addresses`

##### 11.3.6 OMITINDO INFORMAÇÕES DE REGISTRO

Se você quiser dar um passo adiante, impedindo que tipos específicos de informação sejam registrados inteiramente, isso também é possível. O CIDRAM fornece diretivas de configuração para controlar se os endereços IP, os nomes de host, e os agentes do usuário estão incluídos nos registros. Por padrão, todos esses três pontos de dados são incluídos nos registros quando disponíveis. Definir qualquer uma dessas diretivas de configuração como `true` omitirá as informações correspondentes dos registros.

*Nota: Não há motivo para pseudônimo de endereços IP quando omití-los totalmente dos registros.*

*Diretivas de configuração relevantes:*
- `legal` -> `omit_ip`
- `legal` -> `omit_hostname`
- `legal` -> `omit_ua`

##### 11.3.7 ESTATISTICAS

O CIDRAM é opcionalmente capaz de rastrear estatísticas como o número total de eventos de bloqueio ou instâncias de reCAPTCHA que ocorreram desde algum momento específico no tempo. Esta funcionalidade está desativada por padrão, mas pode ser ativada através da configuração do pacote. Essa funcionalidade rastreia apenas o número total de eventos ocorridos e não inclui informações sobre eventos específicos (e, portanto, não deve ser considerado como PII).

*Diretivas de configuração relevantes:*
- `general` -> `statistics`

##### 11.3.8 ENCRIPTAÇÃO

CIDRAM não criptografa seu cache ou qualquer informação de registro. A [encriptação](https://pt.wikipedia.org/wiki/Encripta%C3%A7%C3%A3o) de cache e registro pode ser introduzida no futuro, mas não há planos específicos para ela atualmente. Se você estiver preocupado com o acesso de terceiros não autorizados a partes do CIDRAM que possam conter PII ou informações confidenciais, como cache ou logs, recomendo que o CIDRAM não seja instalado em um local de acesso público (por exemplo, instale o CIDRAM fora do diretório `public_html` padrão ou seu equivalente disponível para a maioria dos servidores web padrão) e que as permissões apropriadamente restritivas sejam impostas para o diretório em que ele reside (em particular, para o diretório do vault). Se isso não for suficiente para resolver suas preocupações, configure o CIDRAM para que os tipos de informações que causam suas preocupações não sejam coletados ou registrados em primeiro lugar (tal como desabilitar o registro em log).

#### 11.4 COOKIES

O CIDRAM define [cookies](https://pt.wikipedia.org/wiki/Cookie_(inform%C3%A1tica)) em dois pontos em sua base de código. Em primeiro lugar, quando um usuário concluir com êxito uma instância de reCAPTCHA (e supondo que `lockuser` esteja definido como `true`), O CIDRAM define um cookie para poder lembrar, para solicitações subsequentes, que o usuário já concluiu uma instância de reCAPTCHA, de modo que não será necessário pedir continuamente ao usuário para concluir uma instância de reCAPTCHA em solicitações subsequentes. Em segundo lugar, quando um usuário efetua login com êxito no front-end, o CIDRAM define um cookie para poder lembrar o usuário das solicitações subsequentes (isto é, os cookies são usados para autenticar o usuário em uma sessão de login).

Em ambos os casos, os avisos de cookie são exibidos de forma proeminente (quando aplicável), avisando ao usuário que os cookies serão definidos se eles se envolverem nas ações relevantes. Os cookies não são definidos em nenhum outro ponto da base de código.

*Nota: A implementação específica da API "invisible" para o reCAPTCHA na CIDRAM pode ser incompatível com as leis de cookies em algumas jurisdições, e deve ser evitada por quaisquer websites sujeitos a essas leis. Optar por usar a API "V2", ou simplesmente desabilitar totalmente o reCAPTCHA, pode ser preferível.*

*Diretivas de configuração relevantes:*
- `general` -> `disable_frontend`
- `recaptcha` -> `lockuser`
- `recaptcha` -> `api`

#### 11.5 MARKETING E PUBLICIDADE

A CIDRAM não coleta ou processa qualquer informação para fins de marketing ou publicidade, e nem vende nem lucra com qualquer informação coletada ou registrada. A CIDRAM não é uma empresa comercial, nem está relacionada a nenhum interesse comercial, portanto, fazer essas coisas não faria sentido. Este tem sido o caso desde o início do projeto, e continua sendo o caso hoje. Além disso, fazer essas coisas seria contraproducente para o espírito e propósito do projeto como um todo, e enquanto eu continuar a manter o projeto, nunca acontecerá.

#### 11.6 POLÍTICA DE PRIVACIDADE

Em algumas circunstâncias, você pode ser legalmente obrigado a exibir claramente um link para sua política de privacidade em todas as páginas e seções do seu site. Isso pode ser importante como um meio de garantir que os usuários estejam bem informados sobre suas práticas de privacidade exatas, os tipos de PII que você coletar, e como você pretende usá-lo. Para poder incluir esse link na página "Acesso Negado" do CIDRAM, é fornecida uma diretiva de configuração para especificar o URL da sua política de privacidade.

*Nota: É altamente recomendável que sua página de política de privacidade não seja colocada atrás da proteção do CIDRAM. Se o CIDRAM proteger sua página de política de privacidade, e um usuário bloqueado pelo CIDRAM clicar no link para sua política de privacidade, ele será bloqueado novamente e não poderá ver sua política de privacidade. O ideal é vincular a uma cópia estática de sua política de privacidade, como uma página HTML ou um arquivo de texto simples que não esteja protegido pelo CIDRAM.*

*Diretivas de configuração relevantes:*
- `legal` -> `privacy_policy`

#### 11.7 GDPR/DSGVO

O Regulamento Geral sobre a Proteção de Dados (GDPR) é um regulamento da União Europeia, que entra em vigor em 25 de Maio, 2018. O principal objectivo do regulamento é dar controlo aos cidadãos e residentes da UE relativamente aos seus próprios dados pessoais, e unificar a regulação na UE em matéria de privacidade e dados pessoais.

O regulamento contém disposições específicas relativas ao tratamento de "[informações pessoalmente identificáveis](https://pt.wikipedia.org/wiki/Informa%C3%A7%C3%A3o_pessoalmente_identific%C3%A1vel)" (PII) de quaisquer "titulares de dados" (qualquer identificada ou identificável pessoa natural) da UE ou dentro da mesma. Para estar em conformidade com o regulamento, "empresas" (conforme definido pelo regulamento), e quaisquer sistemas e processos relevantes devem implementar "[privacidade desde a concepção](https://pt.wikipedia.org/wiki/Privacidade_desde_a_concep%C3%A7%C3%A3o)" por padrão, devem usar as configurações de privacidade mais altas possíveis, devem implementar as proteções necessárias para qualquer informação armazenada ou processada (incluindo, mas não limitado a, a implementação de pseudonimização ou anonimização completa de dados), devem declarar clara e inequivocamente os tipos de dados que coletam, como os processam, por quais motivos, por quanto tempo eles o retêm, e se compartilham esses dados com terceiros, os tipos de dados compartilhados com terceiros, como, porque, e assim por diante.

Os dados não podem ser processados a menos que haja uma base legal para isso, conforme definido pelo regulamento. Geralmente, isso significa que, para processar os dados de um titular de dados de forma legal, ele deve ser feito em conformidade com obrigações legais, ou feito somente após o consentimento explícito, bem informado, e inequívoco ter sido obtido do titular dos dados.

Como os aspectos da regulamentação podem evoluir no tempo, a fim de evitar a propagação de informações desatualizadas, pode ser melhor aprender sobre a regulamentação a partir de uma fonte oficial, em vez de simplesmente incluir as informações relevantes aqui na documentação do pacote (o que pode eventualmente desatualizado à medida que a regulamentação evolui).

[EUR-Lex](https://eur-lex.europa.eu/) (uma parte do site oficial da União Europeia que fornece informações sobre a legislação da UE) fornece informações abrangentes sobre o GDPR/DSGVO, disponível em 24 idiomas diferentes (no momento da escrita deste), e disponível para download em formato PDF. Eu recomendaria definitivamente ler as informações que eles fornecem, a fim de aprender mais sobre GDPR/DSGVO:
- [REGULAMENTO (UE) 2016/679 DO PARLAMENTO EUROPEU E DO CONSELHO](https://eur-lex.europa.eu/legal-content/PT/TXT/?uri=celex:32016R0679)

Alternativamente, há uma breve visão geral (não autoritativa) do GDPR/DSGVO disponível na Wikipedia:
- [Regulamento Geral sobre a Proteção de Dados](https://pt.wikipedia.org/wiki/Regulamento_Geral_sobre_a_Prote%C3%A7%C3%A3o_de_Dados)

---


Última Atualização: 10 Agosto de 2018 (2018.08.10).
