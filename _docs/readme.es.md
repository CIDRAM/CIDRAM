## Documentación para CIDRAM (Español).

### Contenidos
- 1. [PREÁMBULO](#SECTION1)
- 2. [CÓMO INSTALAR](#SECTION2)
- 3. [CÓMO USO](#SECTION3)
- 4. [ARCHIVOS INCLUIDOS EN ESTE PAQUETE](#SECTION4)
- 5. [OPCIONES DE CONFIGURACIÓN](#SECTION5)
- 6. [FORMATO DE FIRMAS](#SECTION6)

---


###1. <a name="SECTION1"></a>PREÁMBULO

CIDRAM (Classless Inter-Domain Routing Access Manager) es un script PHP diseñado para proteger sitios web por bloqueando solicitudes desde direcciones IP considerado como siendo fuentes de tráfico no deseado, incluso (pero no limitado a) tráfico desde no humano punto de acceso, servicios en la nube, spambots, raspadores/scrapers, etc. Esto se hace por calculando la posible CIDRs de las direcciones IP suministrada desde solicitudes entrantes e intentando para coincida estos CIDRs posibles en contra de sus archivos de firmas (estos archivos de firmas contener listas de CIDRs de direcciones IP considerado como siendo fuentes de tráfico no deseado); Si coincidencias se encuentran, las solicitudes están bloqueados.

CIDRAM COPYRIGHT 2016 y más allá GNU/GPLv2 por Caleb M (Maikuolan).

Este script es gratis software; puede redistribuirlo y/o modificarlo según los términos de la GNU General Pública Licencia como publicada por la Free Software Foundation; versión 2 de la licencia, o cualquier posterior versión. Este script se distribuye con la esperanza de que será útil, pero SIN NINGUNA GARANTÍA; también, sin ninguna implícita garantía de COMERCIALIZACIÓN o IDONEIDAD PARA UN PARTICULAR PROPÓSITO. Vea la GNU General Pública Licencia para más detalles, ubicado en el `LICENSE.txt` archivo y disponible también de:
- <http://www.gnu.org/licenses/>.
- <http://opensource.org/licenses/>.

Este documento y su paquete asociado puede ser descargado de forma gratuita desde [GitHub](https://github.com/Maikuolan/CIDRAM/).

---


###2. <a name="SECTION2"></a>CÓMO INSTALAR

Espero para agilizar este proceso al hacer un instalador en algún momento en un futuro no muy lejano, pero hasta entonces, siga estas instrucciones para ha CIDRAM functione en *mayoría de sistemas y CMS:

1) Con tu leyendo esto, estoy asumiendo que usted ha descargado una copia de la script, descomprimido y tenerlo en algún lugar en su computer. Desde aquí, usted querrá averiguar dónde en el host o CMS que desea para colocar el contenido. Un directorio como `/public_html/cidram/` o similar (aunque, no importa que usted elija, a condición de que se algo que estés satisfecho con) será suficiente. *Antes usted enviar archivos a su host, seguir leyendo..*

2) Opcionalmente (muy recomendable para avanzados usuarios, pero no se recomienda para los principiantes o para los inexpertos), abrir `config.ini` (situado en el interior del `vault`) - Este archivo contiene todas las disponibles operativas opciones para CIDRAM. Por encima de cada opción debe ser un breve comentario que describe lo que hace y para lo qué sirve. Ajuste estas opciones según sus necesidades, según lo que sea apropiado para su particular configuración. Guardar archivo, cerrar.

3) Cargar contenidos (CIDRAM y sus archivos) al directorio que habías decidido sobre más temprano (los `*.txt`/`*.md` archivos no son necesarios, pero, en su mayoría, usted debe cargar todos).

4) CHMOD al `vault` directorio a "777". La principal directorio de almacenamiento de los contenidos (el uno decidió desde antes), en general, puede dejar solos, pero CHMOD estado debe ser comprobado si ha tenido problemas de permisos en el pasado en su sistema (predefinido, debería ser algo como "755").

5) Luego, tendrás que CIDRAM "gancho" para el sistema o CMS. Hay varias maneras en que usted puede "gancho" scripts como CIDRAM a su sistema o CMS, pero lo más fácil es simplemente incluir el script al principio de un núcleo archivo de su sistema o CMS (uno que va en general siempre sera cargado cuando alguien accede cualquier página a través de su website) utilizando un `require` o `include` declaración. Por lo general, esto sera algo almacenado en un directorio como `/includes`, `/assets` o `/functions`, y será menudo llamado algo así como `init.php`, `common_functions.php`, `functions.php` o similar. Vas a tener que averiguar qué archivo se por su situación; Si se encuentra con dificultades en la determinación de esto por ti mismo, visite las CIDRAM issues página en GitHub. Para ello [utilizar `require` o `include`], inserte la siguiente línea de código al principio de ese núcleo archivo, con sustitución de la string contenida dentro las comillas con la exacta dirección del `loader.php` archivo (local dirección, no la HTTP dirección; que será similar a la `vault` dirección mencionó anteriormente).

`<?php require '/user_name/public_html/cidram/loader.php'; ?>`

Guardar archivo, cerrarla, resubir.

-- O ALTERNATIVAMENTE --

Si está utilizando un Apache web servidor y si usted tiene acceso a `php.ini`, puede utilizar la `auto_prepend_file` directiva para anteponer CIDRAM cuando cualquier PHP solicitud se recibe. Algo como:

`auto_prepend_file = "/user_name/public_html/cidram/loader.php"`

O esto en el `.htaccess` archivo:

`php_value auto_prepend_file "/user_name/public_html/cidram/loader.php"`

6) ¡Eso es todo! :-)

---


###3. <a name="SECTION3"></a>HOW TO USE

CIDRAM debe bloquear automáticamente las solicitudes indeseables a su website sin requiriendo intervención manual, aparte de sus instalación inicial.

Actualización se realiza manual, y usted puede personalizar su configuración y personalizar cuál de los CIDRs son bloqueados por modificando su archivo de configuración y/o su archivos de firmas.

Si tiene algún falsos positivos, por favor contacto conmigo para decirme.

---


###4. <a name="SECTION4"></a>ARCHIVOS INCLUIDOS EN ESTE PAQUETE

La siguiente es una lista de todos los archivos que debería haberse incluido en la copia de este script cuando descargado, todos los archivos que pueden ser potencialmente creados como resultado de su uso de este script, junto con una breve descripción de lo que todos estos archivos son para.

Archivo | Descripción
----|----
/.gitattributes | Un archivo de la GitHub proyecto (no se requiere para usar la script).
/Changelog.txt | Un registro de los cambios realizados en la principal script entre las diferentes versiones (no se requiere para usar la script).
/composer.json | Composer/Packagist información (no se requiere para usar la script).
/LICENSE.txt | Una copia de la GNU/GPLv2 licencia (no se requiere para usar la script).
/loader.php | Cargador. Esto es lo que se supone debe enganchando (esencial)!
/README.md | Sumario información del proyecto.
/web.config | Un ASP.NET configuración archivo (en este caso, para proteger la `/vault` directorio contra el acceso de fuentes no autorizadas en el caso de que la script está instalado en un servidor basado en ASP.NET tecnologías).
/_docs/ | Documentación directorio (contiene varios archivos).
/_docs/readme.de.md | Documentación Alemán.
/_docs/readme.en.md | Documentación Inglés.
/_docs/readme.es.md | Documentación Español.
/_docs/readme.fr.md | Documentación Francés.
/_docs/readme.id.md | Documentación Indonesio.
/_docs/readme.it.md | Documentación Italiano.
/_docs/readme.nl.md | Documentación Holandés.
/_docs/readme.pt.md | Documentación Portugués.
/_docs/readme.zh-TW.md | Documentación Chino (Tradicional).
/_docs/readme.zh.md | Documentación Chino (Simplificado).
/vault/ | Vault directorio (contiene varios archivos).
/vault/.htaccess | Un hipertexto acceso archivo (en este caso, para proteger confidenciales archivos perteneciente a la script contra el acceso de fuentes no autorizadas).
/vault/cache.dat | Cache data.
/vault/cli.php | Módulo de la CLI.
/vault/config.ini | Archivo de configuración; Contiene todas las opciones de configuración para CIDRAM, instruyendo para qué hacer y cómo operar correctamente (esencial)!
/vault/config.php | Módulo de configuración.
/vault/functions.php | Archivo de funciones (esencial).
/vault/ipv4.dat | Archivo de firmas por IPv4.
/vault/ipv4_custom.dat.RenameMe | Archivo de firmas por IPv4 personalizado (cambiar el nombre para activar).
/vault/ipv6.dat | Archivo de firmas por IPv6.
/vault/ipv6_custom.dat.RenameMe | Archivo de firmas por IPv6 personalizado (cambiar el nombre para activar).
/vault/lang.php | Lingüísticos datos.
/vault/lang/ | Contiene lingüísticos datos.
/vault/lang/.htaccess | Un hipertexto acceso archivo (en este caso, para proteger confidenciales archivos perteneciente a la script contra el acceso de fuentes no autorizadas).
/vault/lang/lang.en.php | Lingüísticos datos Inglés.
/vault/lang/lang.es.php | Lingüísticos datos Español.
/vault/lang/lang.fr.php | Lingüísticos datos Francés.
/vault/lang/lang.id.php | Lingüísticos datos Indonesio.
/vault/lang/lang.it.php | Lingüísticos datos Italiano.
/vault/lang/lang.nl.php | Lingüísticos datos Holandés.
/vault/lang/lang.pt.php | Lingüísticos datos Portugués.
/vault/lang/lang.zh-TW.php | Lingüísticos datos Chino (tradicional).
/vault/lang/lang.zh.php | Lingüísticos datos Chino (simplificado).
/vault/outgen.php | Generador de salida.
/vault/template.html | Template archivo; Plantilla para HTML salida producida por la CIDRAM generador de salida.
/vault/template_custom.html | Template archivo; Plantilla para HTML salida producida por la CIDRAM generador de salida.
/vault/rules_as6939.php | Archivo de reglas personalizado para AS6939.
/vault/rules_softlayer.php | Archivo de reglas personalizado para Soft Layer.

---


###5. <a name="SECTION5"></a>OPCIONES DE CONFIGURACIÓN
La siguiente es una lista de variables encuentran en la `config.ini` configuración archivo de CIDRAM, junto con una descripción de sus propósito y función.

####"general" (Categoría)
General configuración para CIDRAM.

"logfile"
- Nombre del archivo para registrando todos los intentos de acceso bloqueados. Especificar el nombre del archivo, o dejar en blanco para desactivar.

"ipaddr"
- Dónde encontrar el IP dirección de la conectando request? (Útil para servicios como Cloudflare y tales) Predefinido = REMOTE_ADDR. AVISO: No cambie esto a menos que sepas lo que estás haciendo!

"forbid_on_block"
- Debería CIDRAM responder con 403 headers a solicitudes bloqueadas, o seguir con la habitual 200 OK? False = No (200) [Predefinido]; True = Sí (403).

"lang"
- Especifique la predefinido del lenguaje para CIDRAM.

"emailaddr"
- Si deseado, usted puede suministrar una dirección de email aquí que se dará a los usuarios cuando ellos están bloqueadas, para ellos utilizar como un punto de contacto para soporte y/o asistencia para el caso de ellos están bloqueadas por error. ADVERTENCIA: Cualquiera que sea la dirección de email que usted suministrar aquí, que será sin duda adquirida por spambots y raspadores/scrapers durante el curso de su siendo utilizar aquí, y entonces, se recomienda encarecidamente que si eliges para suministrar una dirección de email aquí, que se asegura de que la dirección de email usted suministrar aquí es una dirección desechable y/o una dirección que usted no se preocupan por para ser bombardeado por correo (en otras palabras, es probable que usted no quiere utilizar sus correos electrónicos personal principal o comercio principal).

"disable_cli"
- Desactivar CLI modo? CLI modo está activado por predefinido, pero a veces puede interferir con ciertas herramientas de prueba (tal como PHPUnit, por ejemplo) y otras aplicaciones basadas en CLI. Si no es necesario desactivar CLI modo, usted debe ignorar esta directiva. False = Activar CLI modo [Predefinido]; True = Desactivar CLI modo.

####"signatures" (Categoría)
Configuración de firmas.

"block_cloud"
- Bloquear CIDRs identificados como pertenecientes de servicios de webhosting o servicios en la nube? Si usted operar un servicio de un API desde su sitio web o si usted espera otros sitios web para conectarse a su sitio web, esta directiva debe ser establecido para false. Si usted no espera esta, esta directiva debe ser establecido para true.

"block_bogons"
- Bloquear CIDRs identificados como bogons/martians? Si usted espera conexiones a su sitio web desde dentro de su red local, desde localhost, o desde su LAN, esta directiva debe ser establecido para false. Si usted no espera estos tipos de conexiones, esta directiva debe ser establecido para true.

"block_generic"
- Bloquear CIDRs recomendado generalmente para las listas negras? Esto abarca todos las firmas que no están marcadas como parte de cualquiera de los otros mas especifico categorías de firmas.

"block_spam"
- Bloquear CIDRs identificado como siendo de alto riesgo para el spam? A menos que experimentar problemas cuando hacerlo, en general, esto siempre debe establecerse para true.

---


###6. <a name="SECTION6"></a>FORMATO DE FIRMAS

Una descripción del formato y la estructura de las firmas utilizado por CIDRAM pueden encontrar documentado en texto plano dentro cualquiera de los dos archivos de firmas personalizadas. Por favor refiérase a la documentación para aprender más sobre el formato y la estructura de las firmas de CIDRAM.

All IPv4 signatures follow the format: `xxx.xxx.xxx.xxx/yy %Function% %Param%`.
- `xxx.xxx.xxx.xxx` represents the beginning of the CIDR block (the octets of the initial IP address in the block).
- `yy` represents the CIDR block size [1-32].
- `%Function%` instructs the script what to do with the signature (how the signature should be regarded).
- `%Param%` represents whatever additional information may be required by `%Function%`.

All IPv6 signatures follow the format: `xxxx:xxxx:xxxx:xxxx::xxxx/yy %Function% %Param%`.
- `xxxx:xxxx:xxxx:xxxx::xxxx` represents the beginning of the CIDR block (the octets of the initial IP address in the block). Complete notation and abbreviated notation are both acceptable (and each MUST follow the appropriate and relevant standards of IPv6 notation, but with one exception: an IPv6 address can never begin with an abbreviation when used in a signature for this script, due to the way in which CIDRs are reconstructed by the script; For example, `::1/128` should be expressed, when used in a signature, as `0::1/128`, and `::0/128` expressed as `0::/128`).
- `yy` represents the CIDR block size [1-128].
- `%Function%` instructs the script what to do with the signature (how the signature should be regarded).
- `%Param%` represents whatever additional information may be required by `%Function%`.

The signature files for CIDRAM SHOULD use Unix-style linebreaks (`%0A`, or `\n`)! Other types/styles of linebreaks (eg, Windows` %0D%0A` or `\r\n` linebreaks, Mac `%0D` or `\r` linebreaks, etc) MAY be used, but are NOT preferred. Non-Unix-style linebreaks will be normalised to Unix-style linebreaks by the script.

Precise and correct CIDR notation is required, otherwise the script will NOT recognise the signatures. Additionally, all the CIDR signatures of this script MUST begin with an IP address whose IP number can divide evenly into the block division represented by its CIDR block size (eg, if you wanted to block all IPs from `10.128.0.0` to `11.127.255.255`, `10.128.0.0/8` would NOT be recognised by the script, but `10.128.0.0/9` and `11.0.0.0/9` used in conjunction, WOULD be recognised by the script).

Anything in the signature files not recognised as a signature nor as signature-related syntax by the script will be IGNORED, therefore meaning that you can safely put any non-signature data that you want into the signature files without breaking them and without breaking the script. Comments are acceptable in the signature files, and no special formatting is required for them. Shell-style hashing for comments is preferred, but not enforced; Functionally, it makes no difference to the script whether or not you choose to use shell-style hashing for comments, but using shell-style hashing helps IDEs and plain-text editors to correctly highlight the various parts of the signature files (and so, shell-style hashing can assist as a visual aid while editing).

The possible values of `%Function%` are as follows:
- Run
- Whitelist
- Deny

If "Run" is used, when the signature is triggered, the script will attempt to execute (using a `require_once` statement) an external PHP script, specified by the `%Param%` value (the working directory should be the "/vault/" directory of the script).

Example: `127.0.0.0/8 Run example.php`

This can be useful if you want to execute some specific PHP code for some specific IPs and/or CIDRs.

If "Whitelist" is used, when the signature is triggered, the script will reset all detections (if there's been any detections) and break the test function. `%Param%` is ignored. This function is the equivalent of whitelisting a particular IP or CIDR from being detected.

Example: `127.0.0.1/32 Whitelist`

If "Deny" is used, when the signature is triggered, assuming no whitelist signature has been triggered for the given IP address and/or given CIDR, access to the protected page will be denied. "Deny" is what you'll want to use to actually block an IP address and/or CIDR range. When any signatures are triggered that make use of "Deny", the "Access Denied" page of the script will be generated and the request to the protected page killed.

The `%Param%` value accepted by "Deny" will be parsed to the "Access Denied" page output, supplied to the client/user as the cited reason for their access to the requested page being denied. It can be either a short and simple sentence, explaining why you've chosen to block them (anything should suffice, even a simple "I don't want you on my website"), or one of a small handful of shorthand words supplied by the script, that if used, will be replaced by the script with a pre-prepared explanation of why the client/user has been blocked.

The pre-prepared explanations have i18n support and can be translated by the script based upon the language you specify to the `lang` directive of the script configuration. Additionally, you can instruct the script to ignore "Deny" signatures based upon their `%Param%` value (if they're using these shorthand words) via the directives specified by the script configuration (each shorthand word has a corresponding directive to either process the corresponding signatures or to ignore them). `%Param%` values that don't use these shorthand words, however, don't have i18n support and therefore WON'T be translated by the script, and additionally, aren't directly controllable by the script configuration.

The available shorthand words are:
- Bogon
- Cloud
- Generic
- Spam

Optional: If you want to split your custom signatures into individual sections, you can identify these individual sections to the script by adding a "Tag:" label immediately after the signatures of each section, along with the name of your signature section.

Example:
```
# "Section 1."
1.2.3.4/32 Deny Bogon
2.3.4.5/32 Deny Cloud
4.5.6.7/32 Deny Generic
5.6.7.8/32 Deny Spam
Tag: Section 1
```

To break section tagging and to ensure that tags aren't incorrectly identified to signature sections from earlier in the signature files, simply ensure that there are at least two consecutive linebreaks between your tag and your earlier signature sections. Any untagged signatures will default to either "IPv4" or "IPv6" (depending on which types of signatures are being triggered).

Example:
```
1.2.3.4/32 Deny Bogon
2.3.4.5/32 Deny Cloud

4.5.6.7/32 Deny Generic
5.6.7.8/32 Deny Spam
Tag: Section 1
```

In the above example `1.2.3.4/32` and `2.3.4.5/32` will be tagged as "IPv4", whereas `4.5.6.7/32` and `5.6.7.8/32` will be tagged as "Section 1".

Refer to the custom signature files for more information.

---


Última Actualización: 31 Marzo 2016 (2016.03.31).
