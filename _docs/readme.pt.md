## Documentação para CIDRAM (Português).

### Conteúdo
- 1. [PREÂMBULO](#SECTION1)
- 2. [COMO INSTALAR](#SECTION2)
- 3. [COMO USAR](#SECTION3)
- 4. [ARQUIVOS INCLUÍDOS NESTE PACOTE](#SECTION4)
- 5. [OPÇÕES DE CONFIGURAÇÃO](#SECTION5)
- 6. [FORMATO DE ASSINATURAS](#SECTION6)

---


###1. <a name="SECTION1"></a>PREÂMBULO

CIDRAM (Classless Inter-Domain Routing Access Manager) é um script PHP projetados para proteger sites por bloqueando solicitações provenientes de endereços IP considerado como sendo fontes de tráfego indesejável, incluindo (mas não limitado a) o tráfego dos pontos de acesso não-humanos, serviços em nuvem, spambots, raspadores/scrapers, etc. Ele faz isso via calculando as possíveis CIDRs dos endereços IP fornecida a partir de solicitações de entrada e em seguida tentando comparar estas possíveis CIDRs contra os seus arquivos de assinaturas (estas arquivos de assinaturas contêm listas de CIDRs de endereços IP considerado como sendo fontes de tráfego indesejável); Se forem encontradas correspondências, os solicitações estão bloqueadas.

CIDRAM COPYRIGHT 2016 e além GNU/GPLv2 através do Caleb M (Maikuolan).

Este script é livre software; você pode redistribuí-lo e/ou modificá-lo de acordo com os termos da GNU General Public License como publicada pela Free Software Foundation; tanto a versão 2 da Licença, ou (em sua opção) qualquer versão posterior. Este script é distribuído na esperança que possa ser útil, mas SEM QUALQUER GARANTIA; sem mesmo a implícita garantia de COMERCIALIZAÇÃO ou ADEQUAÇÃO A UM DETERMINADO FIM. Consulte a GNU General Public License para obter mais detalhes, localizado no `LICENSE.txt` arquivo e disponível também desde:
- <http://www.gnu.org/licenses/>.
- <http://opensource.org/licenses/>.

Este documento e seu associado pacote pode ser baixado gratuitamente de [GitHub](https://github.com/Maikuolan/CIDRAM/).

---


###2. <a name="SECTION2"></a>COMO INSTALAR

Espero para agilizar este processo via fazendo um instalado em algum momento no não muito distante futuro, mas até então, siga estas instruções para trabalhar CIDRAM na maioria dos sistemas e CMS:

1) Por o seu lendo isso, eu estou supondo que você já tenha baixado uma cópia arquivada do script, descomprimido seu conteúdo e tê-lo sentado em algum lugar em sua máquina local. A partir daqui, você vai querer determinar onde no seu host ou CMS pretende colocar esses conteúdos. Um diretório como `/public_html/cidram/` ou semelhante (porém, está não importa qual você escolher, assumindo que é seguro e algo você esteja feliz com) vai bastará.

2) Opcionalmente (fortemente recomendado para avançados usuários, mas não recomendado para iniciantes ou para os inexperientes), abrir `config.ini` (localizado dentro `vault`) - Este arquivo contém todas as directivas disponíveis para CIDRAM. Acima de cada opção deve ser um breve comentário descrevendo o que faz e para que serve. Ajuste essas opções de como você vê o ajuste, conforme o que for apropriado para sua configuração específica. Salve o arquivo, fechar.

3) Carregar os conteúdos (CIDRAM e seus arquivos) para o diretório que você tinha decidido anteriormente (você não requerer os `*.txt`/`*.md` arquivos incluídos, mas principalmente, você deve carregar tudo).

4) CHMOD o `vault` diretório para "777". O principal diretório armazenar o conteúdo (o que você escolheu anteriormente), geralmente, pode ser deixado sozinho, mas o CHMOD status deve ser verificado se você já teve problemas de permissões no passado no seu sistema (por padrão, deve ser algo como "755").

5) Seguida, você vai precisar "enganchar" CIDRAM ao seu sistema ou CMS. Existem várias diferentes maneiras em que você pode "enganchar" scripts como CIDRAM ao seu sistema ou CMS, mas o mais fácil é simplesmente incluir o script no início de um núcleo arquivo de seu sistema ou CMS (uma que vai geralmente sempre ser carregado quando alguém acessa qualquer página através de seu site) utilizando um `require` ou `include` comando. Normalmente, isso vai ser algo armazenado em um diretório como `/includes`, `/assets` ou `/functions`, e muitas vezes, ser nomeado algo como `init.php`, `common_functions.php`, `functions.php` ou semelhante. Você precisará determinar qual arquivo isso é para a sua situação; Se você encontrar dificuldades em determinar isso por si mesmo, visite as página dos problemas (issues) de CIDRAM no GitHub. Para fazer isso [usar `require` ou `include`], insira a seguinte linha de código para o início desse núcleo arquivo, substituindo a string contida dentro das aspas com o exato endereço do `loader.php` arquivo (endereço local, não o endereço HTTP; será semelhante ao vault endereço mencionado anteriormente).

`<?php require '/user_name/public_html/cidram/loader.php'; ?>`

Salve o arquivo, fechar, recarregar-lo.

-- OU ALTERNATIVAMENTE --

Se você é usando um Apache web servidor e se você tem acesso a `php.ini`, você pode usar o `auto_prepend_file` directiva para pré-carga CIDRAM sempre que qualquer pedido de PHP é feito. Algo como:

`auto_prepend_file = "/user_name/public_html/cidram/loader.php"`

Ou isso no `.htaccess` arquivo:

`php_value auto_prepend_file "/user_name/public_html/cidram/loader.php"`

6) Isso é tudo! :-)

---


###3. <a name="SECTION3"></a>COMO USAR

CIDRAM deve bloquear automaticamente as solicitações indesejáveis para o seu site sem necessidade de qualquer assistência manual, Além de sua instalação inicial.

A atualização é feita manualmente, e você pode personalizar a sua configuração e personalizar quais CIDRs são bloqueados por modificando seu arquivo de configuração e/ou seus arquivos de assinaturas.

Se você encontrar quaisquer falsos positivos, entre em contato comigo para me informar sobre isso.

---


###4. <a name="SECTION4"></a>ARQUIVOS INCLUÍDOS NESTE PACOTE

O seguinte está uma lista de todos os arquivos que deveria sido incluídos na arquivada cópia desse script quando você baixado-lo, todos os arquivos que podem ser potencialmente criados como resultado de seu uso deste script, juntamente com uma breve descrição do que todos esses arquivos são por.

Arquivo | Descrição
----|----
/.gitattributes | Um arquivo do GitHub projeto (não é necessário para o correto funcionamento do script).
/Changelog.txt | Um registro das mudanças feitas para o script entre o diferentes versões (não é necessário para o correto funcionamento do script).
/composer.json | Composer/Packagist informação (não é necessário para o correto funcionamento do script).
/LICENSE.txt | Uma cópia da GNU/GPLv2 licença.
/loader.php | Carregador/Loader. Isto é o que você deveria ser enganchando em (essencial)!
/README.md | Informações do projeto em sumário.
/web.config | Um arquivo de configuração para ASP.NET (neste caso, para protegendo o`/vault` diretório contra serem acessado por fontes não autorizadas em caso que o script está instalado em um servidor baseado em ASP.NET tecnologias).
/_docs/ | Documentação diretório (contém vários arquivos).
/_docs/readme.de.md | Documentação Alemão.
/_docs/readme.en.md | Documentação Inglês.
/_docs/readme.es.md | Documentação Espanhol.
/_docs/readme.fr.md | Documentação Francesa.
/_docs/readme.id.md | Documentação Indonésio.
/_docs/readme.it.md | Documentação Italiano.
/_docs/readme.nl.md | Documentação Holandês.
/_docs/readme.pt.md | Documentação Português.
/_docs/readme.zh-TW.md | Documentação Chinês (Tradicional).
/_docs/readme.zh.md | Documentação Chinês (Simplificado).
/vault/ | Vault diretório (contém vários arquivos).
/vault/.htaccess | Um hipertexto acesso arquivo (neste caso, para proteger confidenciais arquivos pertencentes ao script contra serem acessados por fontes não autorizadas).
/vault/cache.dat | Dados de cache.
/vault/cli.php | Módulo de CLI.
/vault/config.ini | Arquivo de configuração; Contém todas as opções de configuração para CIDRAM, dizendo-lhe o que fazer e como operar corretamente (essencial)!
/vault/config.php | Módulo de configuração.
/vault/functions.php | Arquivo de funções.
/vault/ipv4.dat | Arquivo de assinaturas para IPv4.
/vault/ipv4_custom.dat | Arquivo de assinaturas personalizadas para IPv4.
/vault/ipv6.dat | Arquivo de assinaturas para IPv6.
/vault/ipv6_custom.dat | Arquivo de assinaturas personalizadas para IPv6.
/vault/lang.php | Linguagem dados.
/vault/lang/ | Contém linguagem dados.
/vault/lang/.htaccess | Um hipertexto acesso arquivo (neste caso, para proteger confidenciais arquivos pertencentes ao script contra serem acessados por fontes não autorizadas).
/vault/lang/lang.en.php | Linguagem dados Inglês.
/vault/lang/lang.es.php | Linguagem dados Espanhol.
/vault/lang/lang.fr.php | Linguagem dados Francesa.
/vault/lang/lang.id.php | Linguagem dados Indonésio.
/vault/lang/lang.it.php | Linguagem dados Italiano.
/vault/lang/lang.nl.php | Linguagem dados Holandês.
/vault/lang/lang.pt.php | Linguagem dados Português.
/vault/lang/lang.zh-TW.php | Linguagem dados Chinês (tradicional).
/vault/lang/lang.zh.php | Linguagem dados Chinês (simplificado).
/vault/outgen.php | Gerador de saída.
/vault/template.html | Arquivo de modelo; Modelo para a saída de HTML produzido pela gerador de saída para CIDRAM.
/vault/template_custom.html | Arquivo de modelo; Modelo para a saída de HTML produzido pela gerador de saída para CIDRAM.
/vault/rules_as6939.php | Arquivo de regras personalizadas para AS6939.
/vault/rules_softlayer.php | Arquivo de regras personalizadas para Soft Layer.

---


###5. <a name="SECTION5"></a>OPÇÕES DE CONFIGURAÇÃO
O seguinte é uma lista de variáveis encontradas no `config.ini` arquivo de configuração para CIDRAM, juntamente com uma descrição de sua propósito e função.

####"general" (Categoria)
Configuração geral por CIDRAM.

"logfile"
- Nome do arquivo para registrar todas as tentativas de acesso bloqueadas. Especifique um nome de arquivo, ou deixe em branco para desabilitar.

"ipaddr"
- Onde encontrar o IP endereço dos pedidos? (Útil por serviços como o Cloudflare e tal) Padrão = REMOTE_ADDR. ATENÇÃO: Não mude isso a menos que você saiba o que está fazendo!

"forbid_on_block"
- Deve CIDRAM respondem com 403 headers para solicitações bloqueadas, ou ficar com o habitual 200 OK? False = Não (200) [Padrão]; True = Sim (403).

"lang"
- Especificar o padrão da linguagem por CIDRAM.

"emailaddr"
- Se você desejar, você pode fornecer um endereço de e-mail aqui a ser dado para os usuários quando eles estão bloqueadas, para eles para usar como um ponto de contato para suporte e/ou assistência no caso de eles sendo bloqueado por engano ou em erro. AVISO: Qualquer endereço de e-mail que você fornecer aqui certamente vai ser adquirido por spambots e raspadores/scrapers durante o curso de seu ser usada aqui, e assim, é fortemente recomendado que, se você optar por fornecer um endereço de e-mail aqui, que você garantir que o endereço de email você fornecer aqui é um endereço descartável e/ou um endereço que você não é importante (em outras palavras, você provavelmente não quer usar seu pessoal principal ou negócio principal endereço de e-mail).

"disable_cli"
- Desativar o CLI modo? CLI modo é ativado por padrão, mas às vezes pode interferir com certas testes ferramentas (tal como PHPUnit, por exemplo) e outras aplicações baseadas em CLI. Se você não precisa desativar o CLI modo, você deve ignorar esta directiva. False = Ativar o CLI modo [Padrão]; True = Desativar o CLI modo.

####"signatures" (Categoria)
Configuração por assinaturas.

"block_cloud"
- Bloquear CIDRs identificado como pertencente a webhosting e/ou serviços em nuvem? Se você operar um serviço de API a partir do seu site ou se você espera outros sites para se conectar para o seu site, este deve ser definido como false. Se não, este deve ser definido como true.

"block_bogons"
- Bloquear bogon/martian CIDRs? Se você espera conexões para o seu site de dentro de sua rede local, de localhost, ou de seu LAN, esta directiva deve ser definido como false. Se você não esperar que esses tais conexões, esta directiva deve ser definido como true.

"block_generic"
- Bloquear CIDRs geralmente recomendado para a lista negra? Isso abrange todas as assinaturas que não são marcados como sendo parte de qualquer um dos outros mais categorias de assinaturas mais específica.

"block_spam"
- Bloquear CIDRs identificado como sendo de alto risco para spam? A menos que você tiver problemas ao fazê-lo, geralmente, esta deve sempre ser definido como true.

---


###6. <a name="SECTION6"></a>FORMATO DE ASSINATURAS

A descrição do formato e estrutura das assinaturas utilizadas por CIDRAM pode ser encontrada documentado em texto simples dentro de qualquer um dos dois arquivos de assinaturas personalizados. Por favor, consulte que a documentação para saber mais sobre o formato e estrutura das assinaturas de CIDRAM.

---


Última Atualização: 20 Março 2016 (2016.03.20).
