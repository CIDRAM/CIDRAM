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
/LICENSE.txt | Una copia de la GNU/GPLv2 licencia.
/loader.php | Cargador. Esto es lo que se supone debe enganchando (esencial)!
/README.md | Sumario información del proyecto.
/web.config | Un ASP.NET configuración archivo (en este caso, para proteger la `/vault` directorio contra el acceso de fuentes no autorizadas en el caso de que la script está instalado en un servidor basado en ASP.NET tecnologías).
/_docs/ | Documentación directorio (contiene varios archivos).
/_docs/readme.en.md | Documentación Inglés.
/_docs/readme.es.md | Documentación Español.
/_docs/readme.fr.md | Documentación Francés.
/_docs/readme.id.md | Documentación Indonesio.
/_docs/readme.it.md | Documentación Italiano.
/_docs/readme.pt.md | Documentación Portugués.
/vault/ | Vault directorio (contiene varios archivos).
/vault/.htaccess | Un hipertexto acceso archivo (en este caso, para proteger confidenciales archivos perteneciente a la script contra el acceso de fuentes no autorizadas).
/vault/cache.dat | Cache data.
/vault/cli.inc | Módulo de la CLI.
/vault/config.inc | Módulo de configuración.
/vault/config.ini | Archivo de configuración; Contiene todas las opciones de configuración para CIDRAM, instruyendo para qué hacer y cómo operar correctamente (esencial)!
/vault/functions.inc | Archivo de funciones (esencial).
/vault/ipv4.dat | Archivo de firmas por IPv4.
/vault/ipv4_custom.dat | Archivo de firmas por IPv4 personalizado.
/vault/ipv6.dat | Archivo de firmas por IPv6.
/vault/ipv6_custom.dat | Archivo de firmas por IPv6 personalizado.
/vault/lang.inc | Lingüísticos datos.
/vault/lang/ | Contiene lingüísticos datos.
/vault/lang/.htaccess | Un hipertexto acceso archivo (en este caso, para proteger confidenciales archivos perteneciente a la script contra el acceso de fuentes no autorizadas).
/vault/lang/lang.en.inc | Lingüísticos datos Inglés.
/vault/lang/lang.es.inc | Lingüísticos datos Español.
/vault/lang/lang.fr.inc | Lingüísticos datos Francés.
/vault/lang/lang.id.inc | Lingüísticos datos Indonesio.
/vault/lang/lang.it.inc | Lingüísticos datos Italiano.
/vault/lang/lang.nl.inc | Lingüísticos datos Holandés.
/vault/lang/lang.pt.inc | Lingüísticos datos Portugués.
/vault/lang/lang.zh-TW.inc | Lingüísticos datos Chino (Tradicional).
/vault/lang/lang.zh.inc | Lingüísticos datos Chino (Simplificado).
/vault/outgen.inc | Generador de salida.
/vault/template.html | Template archivo; Plantilla para HTML salida producida por la CIDRAM generador de salida.
/vault/template_custom.html | Template archivo; Plantilla para HTML salida producida por la CIDRAM generador de salida.
/vault/rules_as6939.inc | Archivo de reglas personalizado para AS6939.
/vault/rules_softlayer.inc | Archivo de reglas personalizado para Soft Layer.

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

---


Última Actualización: 5 Marzo 2016 (2016.03.05).
