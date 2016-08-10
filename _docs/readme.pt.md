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

Este documento e seu associado pacote pode ser baixado gratuitamente de [Github](https://github.com/Maikuolan/CIDRAM/).

---


###2. <a name="SECTION2"></a>COMO INSTALAR

Espero para agilizar este processo via fazendo um instalado em algum momento no não muito distante futuro, mas até então, siga estas instruções para trabalhar CIDRAM na maioria dos sistemas e CMS:

1) Por o seu lendo isso, eu estou supondo que você já tenha baixado uma cópia arquivada do script, descomprimido seu conteúdo e tê-lo sentado em algum lugar em sua máquina local. A partir daqui, você vai querer determinar onde no seu host ou CMS pretende colocar esses conteúdos. Um diretório como `/public_html/cidram/` ou semelhante (porém, está não importa qual você escolher, assumindo que é seguro e algo você esteja feliz com) vai bastará.

2) Renomear `config.ini.RenameMe` para `config.ini` (localizado dentro `vault`), e opcionalmente (fortemente recomendado para avançados usuários, mas não recomendado para iniciantes ou para os inexperientes), abri-lo (este arquivo contém todas as directivas disponíveis para CIDRAM; acima de cada opção deve ser um breve comentário descrevendo o que faz e para que serve). Ajuste essas opções de como você vê o ajuste, conforme o que for apropriado para sua configuração específica. Salve o arquivo, fechar.

3) Carregar os conteúdos (CIDRAM e seus arquivos) para o diretório que você tinha decidido anteriormente (você não requerer os `*.txt`/`*.md` arquivos incluídos, mas principalmente, você deve carregar tudo).

4) CHMOD o `vault` diretório para "777". O principal diretório armazenar o conteúdo (o que você escolheu anteriormente), geralmente, pode ser deixado sozinho, mas o CHMOD status deve ser verificado se você já teve problemas de permissões no passado no seu sistema (por padrão, deve ser algo como "755").

5) Seguida, você vai precisar "enganchar" CIDRAM ao seu sistema ou CMS. Existem várias diferentes maneiras em que você pode "enganchar" scripts como CIDRAM ao seu sistema ou CMS, mas o mais fácil é simplesmente incluir o script no início de um núcleo arquivo de seu sistema ou CMS (uma que vai geralmente sempre ser carregado quando alguém acessa qualquer página através de seu site) utilizando um `require` ou `include` comando. Normalmente, isso vai ser algo armazenado em um diretório como `/includes`, `/assets` ou `/functions`, e muitas vezes, ser nomeado algo como `init.php`, `common_functions.php`, `functions.php` ou semelhante. Você precisará determinar qual arquivo isso é para a sua situação; Se você encontrar dificuldades em determinar isso por si mesmo, para assistência, visite a página de problemas/issues CIDRAM no Github. Para fazer isso [usar `require` ou `include`], insira a seguinte linha de código para o início desse núcleo arquivo, substituindo a string contida dentro das aspas com o exato endereço do `loader.php` arquivo (endereço local, não o endereço HTTP; será semelhante ao vault endereço mencionado anteriormente).

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
/.gitattributes | Um arquivo do Github projeto (não é necessário para o correto funcionamento do script).
/Changelog.txt | Um registro das mudanças feitas para o script entre o diferentes versões (não é necessário para o correto funcionamento do script).
/composer.json | Composer/Packagist informação (não é necessário para o correto funcionamento do script).
/LICENSE.txt | Uma cópia da GNU/GPLv2 licença (não é necessário para o correto funcionamento do script).
/loader.php | Carregador/Loader. Isto é o que você deveria ser enganchando em (essencial)!
/README.md | Informações do projeto em sumário.
/web.config | Um arquivo de configuração para ASP.NET (neste caso, para protegendo o`/vault` diretório contra serem acessado por fontes não autorizadas em caso que o script está instalado em um servidor baseado em ASP.NET tecnologias).
/_docs/ | Documentação diretório (contém vários arquivos).
/_docs/readme.ar.md | Documentação Árabe.
/_docs/readme.de.md | Documentação Alemão.
/_docs/readme.en.md | Documentação Inglês.
/_docs/readme.es.md | Documentação Espanhol.
/_docs/readme.fr.md | Documentação Francesa.
/_docs/readme.id.md | Documentação Indonésio.
/_docs/readme.it.md | Documentação Italiano.
/_docs/readme.ja.md | Documentação Japonesa.
/_docs/readme.nl.md | Documentação Holandês.
/_docs/readme.pt.md | Documentação Português.
/_docs/readme.ru.md | Documentação Russo.
/_docs/readme.vi.md | Documentação Vietnamita.
/_docs/readme.zh-TW.md | Documentação Chinês (tradicional).
/_docs/readme.zh.md | Documentação Chinês (simplificado).
/vault/ | Vault diretório (contém vários arquivos).
/vault/.htaccess | Um hipertexto acesso arquivo (neste caso, para proteger confidenciais arquivos pertencentes ao script contra serem acessados por fontes não autorizadas).
/vault/cache.dat | Dados de cache.
/vault/cli.php | Módulo de CLI.
/vault/config.ini.RenameMe | Arquivo de configuração; Contém todas as opções de configuração para CIDRAM, dizendo-lhe o que fazer e como operar corretamente (renomear para ativar).
/vault/config.php | Módulo de configuração.
/vault/functions.php | Arquivo de funções.
/vault/ipv4.dat | Arquivo de assinaturas para IPv4.
/vault/ipv4_custom.dat.RenameMe | Arquivo de assinaturas personalizadas para IPv4 (renomear para ativar).
/vault/ipv6.dat | Arquivo de assinaturas para IPv6.
/vault/ipv6_custom.dat.RenameMe | Arquivo de assinaturas personalizadas para IPv6 (renomear para ativar).
/vault/lang.php | Linguagem dados.
/vault/lang/ | Contém linguagem dados.
/vault/lang/.htaccess | Um hipertexto acesso arquivo (neste caso, para proteger confidenciais arquivos pertencentes ao script contra serem acessados por fontes não autorizadas).
/vault/lang/lang.ar.cli.php | Linguagem dados Árabe para CLI.
/vault/lang/lang.ar.php | Linguagem dados Árabe.
/vault/lang/lang.de.cli.php | Linguagem dados Alemão para CLI.
/vault/lang/lang.de.php | Linguagem dados Alemão.
/vault/lang/lang.en.cli.php | Linguagem dados Inglês para CLI.
/vault/lang/lang.en.php | Linguagem dados Inglês.
/vault/lang/lang.es.cli.php | Linguagem dados Espanhol para CLI.
/vault/lang/lang.es.php | Linguagem dados Espanhol.
/vault/lang/lang.fr.cli.php | Linguagem dados Francesa para CLI.
/vault/lang/lang.fr.php | Linguagem dados Francesa.
/vault/lang/lang.id.cli.php | Linguagem dados Indonésio para CLI.
/vault/lang/lang.id.php | Linguagem dados Indonésio.
/vault/lang/lang.it.cli.php | Linguagem dados Italiano para CLI.
/vault/lang/lang.it.php | Linguagem dados Italiano.
/vault/lang/lang.ja.cli.php | Linguagem dados Japonês para CLI.
/vault/lang/lang.ja.php | Linguagem dados Japonês.
/vault/lang/lang.nl.cli.php | Linguagem dados Holandês para CLI.
/vault/lang/lang.nl.php | Linguagem dados Holandês.
/vault/lang/lang.pt.cli.php | Linguagem dados Português para CLI.
/vault/lang/lang.pt.php | Linguagem dados Português.
/vault/lang/lang.ru.cli.php | Linguagem dados Russo para CLI.
/vault/lang/lang.ru.php | Linguagem dados Russo.
/vault/lang/lang.vi.cli.php | Linguagem dados Vietnamita para CLI.
/vault/lang/lang.vi.php | Linguagem dados Vietnamita.
/vault/lang/lang.zh-tw.cli.php | Linguagem dados Chinês (tradicional) para CLI.
/vault/lang/lang.zh-tw.php | Linguagem dados Chinês (tradicional).
/vault/lang/lang.zh.cli.php | Linguagem dados Chinês (simplificado) para CLI.
/vault/lang/lang.zh.php | Linguagem dados Chinês (simplificado).
/vault/outgen.php | Gerador de saída.
/vault/template.html | Arquivo de modelo; Modelo para a saída de HTML produzido pela gerador de saída para CIDRAM.
/vault/template_custom.html | Arquivo de modelo; Modelo para a saída de HTML produzido pela gerador de saída para CIDRAM.
/vault/rules_as6939.php | Arquivo de regras personalizadas para AS6939.
/vault/rules_softlayer.php | Arquivo de regras personalizadas para Soft Layer.
/vault/rules_specific.php | Arquivo de regras personalizadas alguns CIDRs específicos.

---


###5. <a name="SECTION5"></a>OPÇÕES DE CONFIGURAÇÃO
O seguinte é uma lista de variáveis encontradas no `config.ini` arquivo de configuração para CIDRAM, juntamente com uma descrição de sua propósito e função.

####"general" (Categoria)
Configuração geral por CIDRAM.

"logfile"
- Um arquivo legível por humanos para registrar todas as tentativas de acesso bloqueadas. Especifique o nome de um arquivo, ou deixe em branco para desabilitar.

"logfileApache"
- Um arquivo no estilo da Apache para registrar todas as tentativas de acesso bloqueadas. Especifique o nome de um arquivo, ou deixe em branco para desabilitar.

"logfileSerialized"
- Un arquivo serializado para registrar todas as tentativas de acesso bloqueadas. Especifique o nome de um arquivo, ou deixe em branco para desabilitar.

*Dica útil: Se você quiser, você pode acrescentar informações tempo/hora aos nomes dos seus arquivos de registro através incluir estas em nome: `{yyyy}` para o ano completo, `{yy}` para o ano abreviado, `{mm}` por mês, `{dd}` por dia, `{hh}` por hora.*

*Exemplos:*
- *`logfile='logfile.{yyyy}-{mm}-{dd}-{hh}.txt'`*
- *`logfileApache='access.{yyyy}-{mm}-{dd}-{hh}.txt'`*
- *`logfileSerialized='serial.{yyyy}-{mm}-{dd}-{hh}.txt'`*

"timeOffset"
- Se o tempo do servidor não coincide com sua hora local, você pode especificar aqui um offset para ajustar as informações de data/tempo gerado por CIDRAM de acordo com as suas necessidades. É geralmente recomendado no lugar para ajustar a directiva fuso horário no seu arquivo `php.ini`, mas às vezes (tais como quando se trabalha com provedores de hospedagem compartilhada e limitados) isto não é sempre possível fazer, e entao, esta opção é fornecido aqui. Offset é em minutos.
- Exemplo (para adicionar uma hora): `timeOffset=60`

"ipaddr"
- Onde encontrar o IP endereço dos pedidos? (Útil por serviços como o Cloudflare e tal) Padrão = REMOTE_ADDR. ATENÇÃO: Não mude isso a menos que você saiba o que está fazendo!

"forbid_on_block"
- Quais cabeçalhos deve CIDRAM responder com quando bloqueando solicitações? False/200 = 200 OK [Padrão]; True = 403 Forbidden (Proibido); 503 = 503 Service unavailable (Serviço indisponível).

"silent_mode"
- Deve CIDRAM silenciosamente redirecionar as tentativas de acesso bloqueadas em vez de exibir o "Acesso Negado" página? Se sim, especificar o local para redirecionar as tentativas de acesso bloqueadas para. Se não, deixe esta variável em branco.

"lang"
- Especificar o padrão da linguagem por CIDRAM.

"emailaddr"
- Se você desejar, você pode fornecer um endereço de e-mail aqui a ser dado para os usuários quando eles estão bloqueadas, para eles para usar como um ponto de contato para suporte e/ou assistência no caso de eles sendo bloqueado por engano ou em erro. AVISO: Qualquer endereço de e-mail que você fornecer aqui certamente vai ser adquirido por spambots e raspadores/scrapers durante o curso de seu ser usada aqui, e assim, é fortemente recomendado que, se você optar por fornecer um endereço de e-mail aqui, que você garantir que o endereço de email você fornecer aqui é um endereço descartável e/ou um endereço que você não é importante (em outras palavras, você provavelmente não quer usar seu pessoal principal ou negócio principal endereço de e-mail).

"disable_cli"
- Desativar o CLI modo? CLI modo é ativado por padrão, mas às vezes pode interferir com certas testes ferramentas (tal como PHPUnit, por exemplo) e outras aplicações baseadas em CLI. Se você não precisa desativar o CLI modo, você deve ignorar esta directiva. False = Ativar o CLI modo [Padrão]; True = Desativar o CLI modo.

####"signatures" (Categoria)
Configuração por assinaturas.

"ipv4"
- A lista dos arquivos de assinaturas IPv4 que CIDRAM deve tentar usar, delimitado por vírgulas. Você pode adicionar entradas aqui Se você quiser incluir arquivos adicionais em CIDRAM.

"ipv6"
- A lista dos arquivos de assinaturas IPv6 que CIDRAM deve tentar usar, delimitado por vírgulas. Você pode adicionar entradas aqui Se você quiser incluir arquivos adicionais em CIDRAM.

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

####"template_data" (Categoria)
Directivas/Variáveis para modelos e temas.

Relaciona-se com a saída HTML usado para gerar a página "Acesso Negado". Se você estiver usando temas personalizados para CIDRAM, HTML é originado a partir do `template_custom.html` arquivo, e caso contrário, HTML é originado a partir do `template.html` arquivo. Variáveis escritas para esta seção do configuração arquivo são processado ao HTML via substituição de quaisquer nomes de variáveis cercado por colchetes encontrado dentro do HTML com os variáveis dados correspondentes. Por exemplo, onde `foo="bar"`, qualquer instância de `<p>{foo}</p>` encontrado dentro do HTML tornará `<p>bar</p>`.

"css_url"
- O template arquivo para temas personalizados utiliza CSS propriedades externos, enquanto que o template arquivo para o padrão tema utiliza CSS propriedades internos. Para instruir CIDRAM para usar o template arquivo para temas personalizados, especificar o endereço HTTP pública do seu temas personalizados CSS arquivos usando a `css_url` variável. Se você deixar essa variável em branco, CIDRAM usará o template arquivo para o padrão tema.

---


###6. <a name="SECTION6"></a>FORMATO DE ASSINATURAS

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

Se "Deny" é utilizado, quando a assinatura é desencadeada, assumindo que não assinatura whitelist foi desencadeado para o dado endereço IP e/ou dado CIDR, acesso à página protegida será negado. "Deny" é o que você deseja usar para realmente bloquear um endereço IP e/ou gama CIDR. Quando qualquer as assinaturas usando "Deny" são desencadeados, o "Acesso Negado" página do script será gerado e o pedido para a página protegida será morto.

O valor da `[Param]` aceita por "Deny" será processado com o saída da "Acesso Negado" página, fornecido ao cliente/utilizador como a razão citada para o seu acesso à página solicitada ser negada. Pode ser uma curta e simples frase, explicando o motivo de ter escolhido para bloqueá-los (qualquer coisa deve ser suficiente, até mesmo uma "eu não quero você no meu site"), ou um de um pequeno punhado de palavras curtas fornecidas pelo script que, se usadas, será substituído pelo script com uma explicação pré-preparado de porque o cliente/usuário foi bloqueado.

As explicações pré-preparados têm suporte i18n e pode ser traduzido pelo script com base no idioma que você especificar com a directiva da configuração do script, `lang`. Além disso, você pode instruir o script para ignorar assinaturas "Deny" com base em sua valor de `[Param]` (se eles estão usando essas palavras curtas) através das directivas especificados pelo configuração do script (cada palavra curta tem uma directiva correspondente para processar as assinaturas correspondentes ou ignorá-los). Valores de `[Param]` que não usar essas palavras curtas, contudo, não tem suporte i18n e por conseguinte NÃO será traduzido pelo script, e adicionalmente, não podem ser controlados directamente pelo configuração do script.

As palavras curtas disponíveis são:
- Bogon
- Cloud
- Generic
- Proxy
- Spam

Opcional: Se você quiser dividir suas assinaturas personalizadas em seções individuais, você pode identificar estas secções individuais para o script por adição de uma etiqueta "Tag:" imediatamente após as assinaturas de cada secção, juntamente com o nome de sua seção de assinaturas.

Exemplo:
```
# "Section 1."
1.2.3.4/32 Deny Bogon
2.3.4.5/32 Deny Cloud
4.5.6.7/32 Deny Generic
5.6.7.8/32 Deny Spam
6.7.8.9/32 Deny Proxy
Tag: Section 1
```

Para quebrar etiquetas de seção e assegurar que os etiquetas não são identificados incorretamente a seções de assinaturas de mais cedo nos arquivos de assinaturas, simplesmente assegurar que há pelo menos dois quebras de linha consecutivas entre o sua etiqueta e suas seções de assinaturas anteriores. Quaisquer assinaturas não etiquetados será padrão para qualquer "IPv4" ou "IPv6" (dependendo de quais tipos de assinaturas estão sendo desencadeados).

Exemplo:
```
1.2.3.4/32 Deny Bogon
2.3.4.5/32 Deny Cloud

4.5.6.7/32 Deny Generic
5.6.7.8/32 Deny Spam
Tag: Section 1
```

No exemplo acima, `1.2.3.4/32` e `2.3.4.5/32` será etiquetadas como "IPv4", enquanto que `4.5.6.7/32` e `5.6.7.8/32` será etiquetadas como "Section 1".

Em suplemento, se você quiser CIDRAM para ignorar completamente algumas seções específicas dentro de qualquer um dos ficheiros de assinatura, você pode usar o arquivo `ignore.dat` para especificar quais seções para ignorar. Em uma nova linha, escreva `Ignore`, seguido por um espaço, seguido do nome da seção que você quer CIDRAM para ignorar.

Exemplo:
```
Ignore Section 1
```

Consulte os arquivos de assinaturas personalizadas para obter mais informações.

---


Última Atualização: 10 Agosto 2016 (2016.08.10).
