## Documentación para CIDRAM (Español).

### Contenidos
- 1. [PREÁMBULO](#SECTION1)
- 2. [CÓMO INSTALAR](#SECTION2)
- 3. [CÓMO USO](#SECTION3)
- 4. [ARCHIVOS INCLUIDOS EN ESTE PAQUETE](#SECTION4)
- 5. [OPCIONES DE CONFIGURACIÓN](#SECTION5)
- 6. [FORMATO DE FIRMAS](#SECTION6)
- 7. [PREGUNTAS MÁS FRECUENTES (FAQ)](#SECTION7)

---


###1. <a name="SECTION1"></a>PREÁMBULO

CIDRAM (Classless Inter-Domain Routing Access Manager) es un script PHP diseñado para proteger sitios web por bloqueando solicitudes desde direcciones IP considerado como siendo fuentes de tráfico no deseado, incluso (pero no limitado a) tráfico desde no humano punto de acceso, servicios en la nube, spambots, raspadores/scrapers, etc. Esto se hace por calculando la posible CIDRs de las direcciones IP suministrada desde solicitudes entrantes e intentando para coincida estos CIDRs posibles en contra de sus archivos de firmas (estos archivos de firmas contener listas de CIDRs de direcciones IP considerado como siendo fuentes de tráfico no deseado); Si coincidencias se encuentran, las solicitudes están bloqueados.

CIDRAM COPYRIGHT 2016 y más allá GNU/GPLv2 por Caleb M (Maikuolan).

Este script es gratis software; puede redistribuirlo y/o modificarlo según los términos de la GNU General Pública Licencia como publicada por la Free Software Foundation; versión 2 de la licencia, o cualquier posterior versión. Este script se distribuye con la esperanza de que será útil, pero SIN NINGUNA GARANTÍA; también, sin ninguna implícita garantía de COMERCIALIZACIÓN o IDONEIDAD PARA UN PARTICULAR PROPÓSITO. Vea la GNU General Pública Licencia para más detalles, ubicado en el `LICENSE.txt` archivo y disponible también de:
- <http://www.gnu.org/licenses/>.
- <http://opensource.org/licenses/>.

Este documento y su paquete asociado puede ser descargado de forma gratuita desde [Github](https://github.com/Maikuolan/CIDRAM/).

---


###2. <a name="SECTION2"></a>CÓMO INSTALAR

Espero para agilizar este proceso al hacer un instalador en algún momento en un futuro no muy lejano, pero hasta entonces, siga estas instrucciones para ha CIDRAM functione en *mayoría de sistemas y CMS:

1) Con tu leyendo esto, estoy asumiendo que usted ha descargado una copia de la script, descomprimido y tenerlo en algún lugar en su computer. Desde aquí, usted querrá averiguar dónde en el host o CMS que desea para colocar el contenido. Un directorio como `/public_html/cidram/` o similar (aunque, no importa que usted elija, a condición de que se algo que estés satisfecho con) será suficiente. *Antes usted enviar archivos a su host, seguir leyendo..*

2) Cambiar el nombre del archivo desde `config.ini.RenameMe` a `config.ini` (situado en el interior del `vault`), y opcionalmente (muy recomendable para avanzados usuarios, pero no se recomienda para los principiantes o para los inexpertos), abrirlo (este archivo contiene todas las disponibles operativas opciones para CIDRAM; por encima de cada opción debe ser un breve comentario que describe lo que hace y para lo qué sirve). Ajuste estas opciones según sus necesidades, según lo que sea apropiado para su particular configuración. Guardar archivo, cerrar.

3) Subir el contenidos (CIDRAM y sus archivos) al directorio que habías decidido sobre más temprano (los `*.txt`/`*.md` archivos no son necesarios, pero, en su mayoría, usted debe cargar todos).

4) CHMOD al `vault` directorio a "755" (si hay algún problema, puede intentar "777"; esto es menos segura, aunque). La principal directorio de almacenamiento de los contenidos (el uno decidió desde antes), en general, puede dejar solos, pero CHMOD estado debe ser comprobado si ha tenido problemas de permisos en el pasado en su sistema (predefinido, debería ser algo como "755").

5) Luego, tendrás que CIDRAM "gancho" para el sistema o CMS. Hay varias maneras en que usted puede "gancho" scripts como CIDRAM a su sistema o CMS, pero lo más fácil es simplemente incluir la script al principio de un núcleo archivo de su sistema o CMS (uno que va en general siempre sera cargado cuando alguien accede cualquier página a través de su website) utilizando un `require` o `include` declaración. Por lo general, esto sera algo almacenado en un directorio como `/includes`, `/assets` o `/functions`, y será menudo llamado algo así como `init.php`, `common_functions.php`, `functions.php` o similar. Vas a tener que averiguar qué archivo se por su situación; Si se encuentra con dificultades en la determinación de esto por ti mismo, para asistencia, visitar la página de problemas/issues CIDRAM en Github. Para ello [utilizar `require` o `include`], inserte la siguiente línea de código al principio de ese núcleo archivo, con sustitución de la string contenida dentro las comillas con la exacta dirección del `loader.php` archivo (local dirección, no la HTTP dirección; que será similar a la `vault` dirección mencionó anteriormente).

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
/.gitattributes | Un archivo de la Github proyecto (no se requiere para usar la script).
/Changelog.txt | Un registro de los cambios realizados en la principal script entre las diferentes versiones (no se requiere para usar la script).
/composer.json | Composer/Packagist información (no se requiere para usar la script).
/LICENSE.txt | Una copia de la GNU/GPLv2 licencia (no se requiere para usar la script).
/loader.php | Cargador. Esto es lo que se supone debe enganchando (esencial)!
/README.md | Sumario información del proyecto.
/web.config | Un ASP.NET configuración archivo (en este caso, para proteger la `/vault` directorio contra el acceso de fuentes no autorizadas en el caso de que la script está instalado en un servidor basado en ASP.NET tecnologías).
/_docs/ | Documentación directorio (contiene varios archivos).
/_docs/readme.ar.md | Documentación Árabe.
/_docs/readme.de.md | Documentación Alemán.
/_docs/readme.en.md | Documentación Inglés.
/_docs/readme.es.md | Documentación Español.
/_docs/readme.fr.md | Documentación Francés.
/_docs/readme.id.md | Documentación Indonesio.
/_docs/readme.it.md | Documentación Italiano.
/_docs/readme.ja.md | Documentación Japonés.
/_docs/readme.nl.md | Documentación Holandés.
/_docs/readme.pt.md | Documentación Portugués.
/_docs/readme.ru.md | Documentación Ruso.
/_docs/readme.vi.md | Documentación Vietnamita.
/_docs/readme.zh-TW.md | Documentación Chino (tradicional).
/_docs/readme.zh.md | Documentación Chino (simplificado).
/vault/ | Vault directorio (contiene varios archivos).
/vault/.htaccess | Un hipertexto acceso archivo (en este caso, para proteger confidenciales archivos perteneciente a la script contra el acceso de fuentes no autorizadas).
/vault/cache.dat | Cache data.
/vault/cli.php | Módulo de la CLI.
/vault/config.ini.RenameMe | Archivo de configuración; Contiene todas las opciones de configuración para CIDRAM, instruyendo para qué hacer y cómo operar correctamente (cambiar el nombre para activar).
/vault/config.php | Módulo de configuración.
/vault/functions.php | Archivo de funciones (esencial).
/vault/hashes.dat | Contiene una lista de hashes aceptadas (pertinente a la función de reCAPTCHA; sólo se genera si la función de reCAPTCHA está habilitada).
/vault/ignore.dat | Ignorar archivo (se utiliza para especificar qué secciones de firmas que CIDRAM debe ignorar).
/vault/ipbypass.dat | Contiene una lista de bypasses IP (pertinente a la función de reCAPTCHA; sólo se genera si la función de reCAPTCHA está habilitada).
/vault/ipv4.dat | Archivo de firmas por IPv4.
/vault/ipv4_custom.dat.RenameMe | Archivo de firmas por IPv4 personalizado (cambiar el nombre para activar).
/vault/ipv6.dat | Archivo de firmas por IPv6.
/vault/ipv6_custom.dat.RenameMe | Archivo de firmas por IPv6 personalizado (cambiar el nombre para activar).
/vault/lang.php | Lingüísticos datos.
/vault/lang/ | Contiene lingüísticos datos.
/vault/lang/.htaccess | Un hipertexto acceso archivo (en este caso, para proteger confidenciales archivos perteneciente a la script contra el acceso de fuentes no autorizadas).
/vault/lang/lang.ar.cli.php | Lingüísticos datos Árabe para CLI.
/vault/lang/lang.ar.php | Lingüísticos datos Árabe.
/vault/lang/lang.de.cli.php | Lingüísticos datos Alemán para CLI.
/vault/lang/lang.de.php | Lingüísticos datos Alemán.
/vault/lang/lang.en.cli.php | Lingüísticos datos Inglés para CLI.
/vault/lang/lang.en.php | Lingüísticos datos Inglés.
/vault/lang/lang.es.cli.php | Lingüísticos datos Español para CLI.
/vault/lang/lang.es.php | Lingüísticos datos Español.
/vault/lang/lang.fr.cli.php | Lingüísticos datos Francés para CLI.
/vault/lang/lang.fr.php | Lingüísticos datos Francés.
/vault/lang/lang.id.cli.php | Lingüísticos datos Indonesio para CLI.
/vault/lang/lang.id.php | Lingüísticos datos Indonesio.
/vault/lang/lang.it.cli.php | Lingüísticos datos Italiano para CLI.
/vault/lang/lang.it.php | Lingüísticos datos Italiano.
/vault/lang/lang.ja.cli.php | Lingüísticos datos Japonés para CLI.
/vault/lang/lang.ja.php | Lingüísticos datos Japonés.
/vault/lang/lang.nl.cli.php | Lingüísticos datos Holandés para CLI.
/vault/lang/lang.nl.php | Lingüísticos datos Holandés.
/vault/lang/lang.pt.cli.php | Lingüísticos datos Portugués para CLI.
/vault/lang/lang.pt.php | Lingüísticos datos Portugués.
/vault/lang/lang.ru.cli.php | Lingüísticos datos Ruso para CLI.
/vault/lang/lang.ru.php | Lingüísticos datos Ruso.
/vault/lang/lang.vi.cli.php | Lingüísticos datos Vietnamita para CLI.
/vault/lang/lang.vi.php | Lingüísticos datos Vietnamita.
/vault/lang/lang.zh-tw.cli.php | Lingüísticos datos Chino (tradicional) para CLI.
/vault/lang/lang.zh-tw.php | Lingüísticos datos Chino (tradicional).
/vault/lang/lang.zh.cli.php | Lingüísticos datos Chino (simplificado) para CLI.
/vault/lang/lang.zh.php | Lingüísticos datos Chino (simplificado).
/vault/outgen.php | Generador de salida.
/vault/recaptcha.php | Módulo de reCAPTCHA.
/vault/rules_as6939.php | Archivo de reglas personalizado para AS6939.
/vault/rules_softlayer.php | Archivo de reglas personalizado para Soft Layer.
/vault/rules_specific.php | Archivo de reglas personalizado para algunos CIDRs específicos.
/vault/salt.dat | Archivo de sal (utilizado por algunas funciones periférico; solamente generada si es necesario).
/vault/template.html | Template archivo; Plantilla para HTML salida producida por la CIDRAM generador de salida.
/vault/template_custom.html | Template archivo; Plantilla para HTML salida producida por la CIDRAM generador de salida.

---


###5. <a name="SECTION5"></a>OPCIONES DE CONFIGURACIÓN
La siguiente es una lista de variables encuentran en la `config.ini` configuración archivo de CIDRAM, junto con una descripción de sus propósito y función.

####"general" (Categoría)
General configuración para CIDRAM.

"logfile"
- Un archivo legible por humanos para el registro de todos los intentos de acceso bloqueados. Especificar el nombre del archivo, o dejar en blanco para desactivar.

"logfileApache"
- Un archivo en el estilo de Apache para el registro de todos los intentos de acceso bloqueados. Especificar el nombre del archivo, o dejar en blanco para desactivar.

"logfileSerialized"
- Un archivo serializado para el registro de todos los intentos de acceso bloqueados. Especificar el nombre del archivo, o dejar en blanco para desactivar.

*Consejo útil: Si usted quieres, puede añadir información en fecha/hora a los nombres de los archivos de registro mediante la inclusión de éstos en el nombre: `{yyyy}` para el año completo, `{yy}` para el año abreviada, `{mm}` por mes, `{dd}` por día, `{hh}` para la hora.*

*Ejemplos:*
- *`logfile='logfile.{yyyy}-{mm}-{dd}-{hh}.txt'`*
- *`logfileApache='access.{yyyy}-{mm}-{dd}-{hh}.txt'`*
- *`logfileSerialized='serial.{yyyy}-{mm}-{dd}-{hh}.txt'`*

"timeOffset"
- Si el tiempo del servidor no coincide con la hora local, se puede especificar un offset aquí para ajustar la información de fecha/hora generado por CIDRAM de acuerdo a sus necesidades. Generalmente, se recomienda en lugar para ajustar la directiva de zona horaria en el archivo `php.ini`, pero a veces (por ejemplo, cuando se trabaja con proveedores de hosting compartido limitados) esto no siempre es posible hacer, y entonces, esta opción se proporciona aquí. El offset es en minutos.
- Ejemplo (para añadir una hora): `timeOffset=60`

"ipaddr"
- Dónde encontrar el IP dirección de la conectando request? (Útil para servicios como Cloudflare y tales) Predefinido = REMOTE_ADDR. AVISO: No cambie esto a menos que sepas lo que estás haciendo!

"forbid_on_block"
- Cual cabeceras debe CIDRAM responder con cuando bloquear acceso? False/200 = 200 OK [Predefinido]; True = 403 Forbidden (Prohibido); 503 = 503 Service unavailable (Servicio no disponible).

"silent_mode"
- Debería CIDRAM silencio redirigir los intentos de acceso bloqueados en lugar de mostrar la página "Acceso Denegado"? En caso afirmativo, especifique la ubicación para redirigir los intentos de acceso bloqueados. Si no, dejar esta variable en blanco.

"lang"
- Especifique la predefinido del lenguaje para CIDRAM.

"emailaddr"
- Si deseado, usted puede suministrar una dirección de email aquí que se dará a los usuarios cuando ellos están bloqueadas, para ellos utilizar como un punto de contacto para soporte y/o asistencia para el caso de ellos están bloqueadas por error. ADVERTENCIA: Cualquiera que sea la dirección de email que usted suministrar aquí, que será sin duda adquirida por spambots y raspadores/scrapers durante el curso de su siendo utilizar aquí, y entonces, se recomienda encarecidamente que si eliges para suministrar una dirección de email aquí, que se asegura de que la dirección de email usted suministrar aquí es una dirección desechable y/o una dirección que usted no se preocupan por para ser bombardeado por correo (en otras palabras, es probable que usted no quiere utilizar sus correos electrónicos personal principal o comercio principal).

"disable_cli"
- Desactivar CLI modo? CLI modo está activado por predefinido, pero a veces puede interferir con ciertas herramientas de prueba (tal como PHPUnit, por ejemplo) y otras aplicaciones basadas en CLI. Si no es necesario desactivar CLI modo, usted debe ignorar esta directiva. False = Activar CLI modo [Predefinido]; True = Desactivar CLI modo.

"disable_frontend"
- Desactivar el acceso front-end? El acceso front-end puede hacer CIDRAM más manejable, pero también puede ser un riesgo de seguridad. Se recomienda administrar CIDRAM a través del back-end cuando sea posible, pero el acceso front-end se proporciona para cuando no es posible. Mantenerlo desactivado a menos que lo necesite. False = Activar el acceso front-end; True = Desactivar el acceso front-end [Predefinido].

####"signatures" (Categoría)
Configuración de firmas.

"ipv4"
- Una lista de los archivos de firmas IPv4 que CIDRAM debe tratar de utilizar, delimitado por comas. Puede agregar entradas aquí si desea incluir archivos de firmas IPv4 adicionales dentro CIDRAM.

"ipv6"
- Una lista de los archivos de firmas IPv6 que CIDRAM debe tratar de utilizar, delimitado por comas. Puede agregar entradas aquí si desea incluir archivos de firmas IPv6 adicionales dentro CIDRAM.

"block_cloud"
- Bloquear CIDRs identificados como pertenecientes de servicios de webhosting o servicios en la nube? Si usted operar un servicio de un API desde su sitio web o si usted espera otros sitios web para conectarse a su sitio web, esta directiva debe ser establecido para false. Si usted no espera esta, esta directiva debe ser establecido para true.

"block_bogons"
- Bloquear CIDRs identificados como bogons/martians? Si usted espera conexiones a su sitio web desde dentro de su red local, desde localhost, o desde su LAN, esta directiva debe ser establecido para false. Si usted no espera estos tipos de conexiones, esta directiva debe ser establecido para true.

"block_generic"
- Bloquear CIDRs recomendado generalmente para las listas negras? Esto abarca todos las firmas que no están marcadas como parte de cualquiera de los otros mas especifico categorías de firmas.

"block_proxies"
- Bloquear CIDRs identificados como pertenecientes de servicios de proxy? Si requiere que los usuarios puedan acceder a su sitio web a partir de los servicios de proxy anónimos, esta directiva debe ser establecido para false. Alternativamente, Si usted no requiere proxies anónimos, esta directiva debe ser establecido para true como un medio para mejorar la seguridad.

"block_spam"
- Bloquear CIDRs identificado como siendo de alto riesgo para el spam? A menos que experimentar problemas cuando hacerlo, en general, esto siempre debe establecerse para true.

####"recaptcha" (Categoría)
Opcionalmente, puede proporcionar a los usuarios una manera de evitar la página "Acceso Denegado" a modo de completar una instancia de reCAPTCHA, si desea hacerlo. Esto puede ayudar a mitigar algunos de los riesgos asociados con los falsos positivos en aquellas situaciones por donde no estamos del todo seguro de si una solicitud ha originado a partir de una máquina o un ser humano.

Debido a los riesgos asociados con la provisión de un medio para que los usuarios pueden evitar la página de "Acceso Denegado", en general, yo aconsejaría contra activar esta función a menos que sienta que sea necesario hacerlo. Situaciones en las que sería necesario: Si su sitio web tiene los clientes/usuarios que necesitan tener acceso a su sitio web, y si esto es algo que no puede verse comprometida en, pero si esos clientes/usuarios pasar a ser conectando de una red hostil que podría, potencialmente, también se lleva a tráfico no deseado, y el bloqueo de este tráfico no deseado también es algo que no se puede comprometer en, en aquellas situaciones sin salida, la función de reCAPTCHA podría ser útil como un medio de permitir que los clientes/usuarios deseables, mientras se mantiene fuera el tráfico indeseado de la misma red. Dicho esto, dado que el propósito previsto de un CAPTCHA es distinguir entre los humanos y los no humanos, la función de reCAPTCHA sólo ayudaría en estas situaciones sin salida si estamos asumiendo que este tráfico indeseado no es humana (eg, spambots, raspadores, hacktools, tráfico automatizado), en lugar de ser el tráfico de humanos indeseables (tales como spammers humanos, hackers, et al).

Para obtener una "site key" y una "secret key" (requerida para utilizar reCAPTCHA), por favor vaya a: [https://developers.google.com/recaptcha/](https://developers.google.com/recaptcha/)

"usemode"
- Define cómo CIDRAM debe utilizar reCAPTCHA.
- 0 = reCAPTCHA se desactiva completamente (predefinida).
- 1 = reCAPTCHA se activa para todas las firmas.
- 2 = reCAPTCHA se activa sólo para las firmas pertenecientes a las secciones especialmente marcados como reCAPTCHA habilitado dentro de los archivos de firma.
- (Cualquier otro valor será tratada de la misma manera que 0).

"lockip"
- Especifica si los hashes deben ser ligados para direcciones IP específicas. False = Las cookies y los hashes se PUEDEN utilizar por varias direcciones IP (predefinido). True = Las cookies y los hashes NO se pueden utilizar por varias direcciones IP (cookies/hashes están ligados a direcciones IP específicas).
- Notar: El valor de "lockip" se ignora cuando "lockuser" es false, debido a que el mecanismo para recordar "usuarios" varía dependiendo de este valor.

"lockuser"
- Especifica si la completar con éxito una instancia de reCAPTCHA debe estar ligados a usuarios específicos. False = La completar con éxito de una instancia de reCAPTCHA otorgará acceso a todas las solicitudes procedentes de la misma IP que es utilizado por el usuario que es completar la instancia de reCAPTCHA; Las cookies y los hashes no se utilizan; En lugar de ello, se utilizará una lista blanca IP. True = La completar con éxito de una instancia de reCAPTCHA sólo se autorizará el acceso al usuario completar la instancia de reCAPTCHA; Las cookies y los hashes se utilizan para recordar al usuario; Una lista blanca de IP no se utiliza (predefinida).

"sitekey"
- Este valor debe corresponder a la "site key" para su reCAPTCHA, que se puede encontrar en el panel de control de reCAPTCHA.

"secret"
- Este valor debe corresponder a la "secret key" para su reCAPTCHA, que se puede encontrar en el panel de control de reCAPTCHA.

"expiry"
- Cuando "lockuser" es true (predefinido), con el fin de recordar cuándo un usuario ha completado reCAPTCHA con éxito, para solicitudes de páginas en el futuro, CIDRAM genera una cookie HTTP estándar que contiene un hash que corresponde a un registro interno que contiene ese mismo hash; Solicitudes de páginas en el futuro utilizará estos hashes correspondientes para autenticar que un usuario ha previamente ya completado una instancia de reCAPTCHA. Cuando "lockuser" es false, una lista blanca de IP se utiliza para determinar si las solicitudes se debe permitir de la IP de solicitudes entrantes; Las entradas se añaden a esta lista blanca cuando la instancia de reCAPTCHA se completa con éxito. Por cuántas horas deben estas cookies, hashes y entradas de lista blanca siendo válido? Predefinido = 720 (1 mes).

"logfile"
- Registrar todos los intentos de reCAPTCHA? En caso afirmativo, especifique el nombre que se utilizará para el archivo de registro. Si no, dejar esta variable en blanco.

*Consejo útil: Si usted quieres, puede añadir información en fecha/hora a los nombres de los archivos de registro mediante la inclusión de éstos en el nombre: `{yyyy}` para el año completo, `{yy}` para el año abreviada, `{mm}` por mes, `{dd}` por día, `{hh}` para la hora.*

*Ejemplos:*
- *`logfile='recaptcha.{yyyy}-{mm}-{dd}-{hh}.txt'`*

####"template_data" (Categoría)
Directivas/Variables para las plantillas y temas.

Relacionado a la salida HTML utilizado generar la página "Acceso Denegado". Si utiliza temas personalizados para CIDRAM, HTML se obtiene a partir del `template_custom.html` archivo, y para de otra manera, HTML se obtiene a partir del `template.html` archivo. Variables escritas a esta sección de la configuración archivo se procesado para el HTML a través de la sustitución de los nombres de variables circunfijo por llaves que se encuentran dentro del HTML con el variable datos correspondiente. Por ejemplo, dónde `foo="bar"`, cualquier instancias de `<p>{foo}</p>` que se encuentran dentro del HTML se convertirá `<p>bar</p>`.

"css_url"
- El plantilla archivo para los temas personalizados utiliza externas CSS propiedades, mientras que el plantilla archivo para el predefinida tema utiliza internas CSS propiedades. Para instruir CIDRAM de utilizar el plantilla archivo para temas personalizados, especificar el público HTTP dirección de sus temas personalizados CSS archivos utilizando la `css_url` variable. Si lo deja en blanco la variable, CIDRAM utilizará el plantilla archivo para el predefinida tema.

---


###6. <a name="SECTION6"></a>FORMATO DE FIRMAS

####6.0 LOS FUNDAMENTOS

Una descripción del formato y la estructura de las firmas utilizado por CIDRAM pueden encontrar documentado en texto plano dentro cualquiera de los dos archivos de firmas personalizadas. Por favor refiérase a la documentación para aprender más sobre el formato y la estructura de las firmas de CIDRAM.

Todas las firmas IPv4 siguen el formato: `xxx.xxx.xxx.xxx/yy [Function] [Param]`.
- `xxx.xxx.xxx.xxx` representa el comienzo del bloque de CIDRs (los octetos de la dirección IP inicial en el bloque).
- `yy` representa el tamaño del bloque de CIDRs [1-32].
- `[Function]` se instruir a la script de qué hacer con la firma (cómo la firma debe considerado).
- `[Param]` representa cualquier información adicional que puede ser necesario por `[Function]`.

Todas las firmas IPv6 siguen el formato: `xxxx:xxxx:xxxx:xxxx::xxxx/yy [Function] [Param]`.
- `xxxx:xxxx:xxxx:xxxx::xxxx` representa el comienzo del bloque de CIDRs (los octetos de la dirección IP inicial en el bloque). Notación completa y notación abreviada son ambos aceptables (y cada uno DEBE seguir las normas apropiadas y pertinentes of IPv6 notation, pero con una excepción: una dirección IPv6 no puede comenzar con una abreviatura cuando utilizada en una firma para este script, debido a la manera en que la CIDRs se reconstruyen por la script; Por ejemplo, `::1/128` deben expresarse, cuando utilizada en una firma, como `0::1/128`, y `::0/128` expresado como `0::/128`).
- `yy` representa el tamaño del bloque de CIDRs [1-128].
- `[Function]` se instruir a la script de qué hacer con la firma (cómo la firma debe considerado).
- `[Param]` representa cualquier información adicional que puede ser necesario por `[Function]`.

Los archivos de firmas para CIDRAM DEBERÍAN utilizar saltos de línea en el estilo de Unix (`%0A`, o `\n`)! Otros tipos/estilos de saltos de línea (por ejemplo, Windows `%0D%0A` o `\r\n` saltos de línea, Mac `%0D` o `\r` saltos de línea, etc) PUEDE ser usado, pero NO son preferidas. Saltos de línea que no en el estilo de Unix será normalizado a saltos de línea en el estilo de Unix por la script.

Notación CIDR precisa y correcta se requiere, de lo contrario la script no reconocerá las firmas. Adicionalmente, todas las firmas de este script DEBE comenzar con una dirección IP cuyo número IP puede dividir de una manera uniforme dentro la división del bloque representada por el tamaño de sus bloque CIDR (por ejemplo, si desea bloquear todas las IPs de `10.128.0.0` a `11.127.255.255`, `10.128.0.0/8` NO sería reconocido por la script, pero `10.128.0.0/9` y `11.0.0.0/9` utilizado junto, SERÍA reconocido por la script).

Cualquier cosa en los archivos de firmas no reconocido como una firma ni como sintaxis relacionados con la firmas por la script se ignorará, y por lo tanto significa que usted puede poner con seguridad cualquier datos que desea en los archivos de firmas sin romperlos y sin romper la script. Los comentarios son aceptables en los archivos de firmas, y no formato especial se requiere para ellos. Hash en el estilo de Shell para comentarios se prefiere, pero no forzada; Funcionalmente, no hace ninguna diferencia a la script independientemente de si usted elige utilizar hash en el estilo de Shell para comentarios, pero utilizar hash en el estilo de Shell ayuda IDEs y editores de texto sin formato resaltar correctamente las diversas partes de los archivos de firmas (y entonces, hash en el estilo de Shell puede ayudar como ayuda visual durante la edición).

Los valores posibles de `[Function]` son las siguientes:
- Run
- Whitelist
- Greylist
- Deny

Si "Run" es utilizada, cuando la firma es activada, la script intentará ejecutar (usando una instrucción `require_once`) un script PHP externa, especificado por el valor de `[Param]` (el directorio de trabajo debe ser el directorio "/vault/" de la script).

Ejemplo: `127.0.0.0/8 Run example.php`

Esto puede ser útil si se desea ejecutar alguna código PHP específica para algunas direcciones IP específicas y/o CIDRs.

Si "Whitelist" es utilizada, cuando la firma es activada, la script se reinicializará todas las detecciones (si ha habido alguna detecciones) y romper la función para prueba. `[Param]` se ignora. Esta función es el equivalente de poner en una lista blanca un IP o CIDR particular, evitando que sea detectado.

Ejemplo: `127.0.0.1/32 Whitelist`

Si "Greylist" es utilizada, cuando la firma es activada, la script se reinicializará todas las detecciones (si ha habido alguna detecciones) y saltar al siguiente archivo de firmas para continuar su procesamiento. `[Param]` se ignora.

Ejemplo: `127.0.0.1/32 Greylist`

Si "Deny" es utilizada, cuando la firma es activada, suponiendo que no firma lista blanca se ha activada para la dirección IP dada y/o CIDR dada, el acceso a la página protegida será denegada. "Deny" es lo que usted desea utilizar para bloquear efectivamente una dirección IP y/o CIDR. Cuando cualquier firmas son activadas que hacen uso de "Deny", el "Acceso Denegado" página de la script se generará y la solicitud a la página protegida será matado.

El valor de `[Param]` aceptado por "Deny" será dado a la salida del "Acceso Denegado" página, suministrado al cliente/usuario como la razón citada para su acceso a la página solicitada siendo denegado. Puede ser una frase corta y simple, explicar por qué ha elegido para bloquearlos (cualquier cosa debería ser suficiente, incluso un simple "yo no te quiero en mi sitio"), o uno de un pequeño puñado de palabras abreviadas suministrado por la script, que si utilizada, será reemplazado por la script con una explicación pre-preparada de por qué el cliente/usuario ha sido bloqueado.

Las explicaciones pre-preparadas tienen soporte para i18n y puede ser traducido por la script basado en el idioma que especifique a la directiva `lang` de la configuración de la script. Adicionalmente, puede instruir a la script ignorar las firmas "Deny" basado en el valor de su `[Param]` (si están usando estas palabras abreviadas) a través de las directrivas especificadas por la configuración de la script (cada palabra abreviada tiene una directiva correspondiente ya sea para procesar las firmas correspondientes o para ignorarlos). Los valores de `[Param]` que no utilizan estas palabras abreviadas, sin embargo, no tienen soporte para i18n y por lo tanto NO será traducido por la script, y además, no son controlable directamente por la configuración de la script.

Las palabras abreviadas disponibles son:
- Bogon
- Cloud
- Generic
- Proxy
- Spam

####6.1 ETIQUETAS

Si desea dividir sus firmas personalizadas en secciones individuales, se puede identificar estas secciones individuales a la script mediante la adición de una "etiqueta de sección" inmediatamente después de las firmas de cada sección, junto con el nombre de su sección de firmas (vea el ejemplo siguiente).

```
# "Section 1."
1.2.3.4/32 Deny Bogon
2.3.4.5/32 Deny Cloud
4.5.6.7/32 Deny Generic
5.6.7.8/32 Deny Spam
6.7.8.9/32 Deny Proxy
Tag: Section 1
```

Para romper las etiquetas de secciones y para asegurar que las etiquetas no son identificado incorrectamente a las secciones de firmas más temprano en los archivos de firmas, Simplemente asegúrese de que hay al menos dos saltos de línea consecutivos entre su etiqueta y su sección de firmas de más temprano. Cualquier firmas que no son etiquetados será predefinida a ya sea "IPv4" o "IPv6" (dependiendo de qué tipos de firmas se activan).

```
1.2.3.4/32 Deny Bogon
2.3.4.5/32 Deny Cloud

4.5.6.7/32 Deny Generic
5.6.7.8/32 Deny Spam
Tag: Section 1
```

En el ejemplo anterior `1.2.3.4/32` y `2.3.4.5/32` será etiquetado como "IPv4", mientras `4.5.6.7/32` y `5.6.7.8/32` será etiquetado como "Section 1".

Si desea firmas para expiran después de un tiempo, de una manera similar a las etiquetas de sección, se puede utilizar una "etiqueta de expiración" para especificar cuándo deben firmas dejarán de ser válidas. Etiquetas de expiración utilizan el formato "AAAA.MM.DD" (vea el ejemplo siguiente).

```
# "Section 1."
1.2.3.4/32 Deny Generic
2.3.4.5/32 Deny Generic
Expires: 2016.12.31
```

Etiquetas de sección y etiquetas de expiración se pueden utilizar en conjunción, y ambos son opcionales (vea el ejemplo siguiente).

```
# "Example Section."
1.2.3.4/32 Deny Generic
Tag: Example Section
Expires: 2016.12.31
```

####6.2 YAML

#####6.2.0 LOS FUNDAMENTOS DE YAML

Una forma simplificada de YAML markup se puede utilizar en los archivos de firmas con el propósito de definir los comportamientos y configuraciones específicas para las secciones de firmas individuales. Esto puede ser útil si desea que el valor de sus directivas de configuración diferir sobre la base de las firmas individuales y las secciones de firmas (por ejemplo; si desea proporcionar una dirección de email para los tickets de soporte para cualquier usuario bloqueadas por una firma particular, pero no desea proporcionar una dirección de email para tickets de soporte para usuarios bloqueados por cualquier otro firmas; si desea por algunas firmas específicas para desencadenar una redirección de página; si desea marcar una sección de firmas para usar con reCAPTCHA; si desea registrar los intentos de acceso bloqueados para archivos separados sobre la base de firmas individuales y/o secciones de firmas).

El uso de YAML markup en los archivos de firma es totalmente opcional (es decir, usted puede utilizarlo si desea hacerlo, pero no está obligado a hacerlo), y es capaz de aprovechar la mayoria (pero no todos) de las directivas de configuración.

Nota: La implementación de YAML markup en CIDRAM es muy simplista y muy limitado; Se tiene la intención de cumplir con los requisitos específicos para CIDRAM de una manera que tiene la familiaridad de YAML markup, pero no se sigue o cumple con las especificaciones oficiales (y por lo tanto no se comportará de la misma manera que las implementaciones más a fondo en otros lugares, y puede no ser apropiado para otros proyectos en otros lugares).

En CIDRAM, segmentos de YAML markup se identifican a la script a modo de tres guiones ("---"), y terminar junto a las secciones de firmas que acompañas vía doble saltos de línea. Un segmento típico de YAML markup dentro de una sección de firmas consiste en tres guiones en una línea inmediatamente después de la lista de CIDRs y cualquier etiquetas, seguido de una lista bidimensional de pares valores-clave (primera dimensión, categorías de directivas de configuración; segunda dimensión, directivas de configuración) de las directivas de configuración que deben modificarse (y para cual los valores) siempre que una firma dentro de esa sección de firmas se desencadena (ver los ejemplos siguientes).

```
# "Foobar 1."
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

# "Foobar 2."
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

# "Foobar 3."
1.2.3.4/32 Deny Generic
2.3.4.5/32 Deny Generic
4.5.6.7/32 Deny Generic
Tag: Foobar 3
---
general:
 forbid_on_block: 403
 silent_mode: "http://127.0.0.1/"
```

#####6.2.1 CÓMO "ESPECIALMENTE MARCAR" SECCIONES DE FIRMAS PARA USAR CON reCAPTCHA

Cuando "usemode" es 0 o 1, secciones de firmas no tienen que ser "especialmente marcado" para usar con reCAPTCHA (ya sea porque ya va utilizar o no va utilizar reCAPTCHA, dependiendo de esta directiva).

Cuando "usemode" es 2, para "especialmente marcar" secciones de firmas para usar con reCAPTCHA, una entrada está incluida en el segmento de YAML para que esa sección de firmas (vea el ejemplo siguiente).

```
# This section will use reCAPTCHA.
1.2.3.4/32 Deny Generic
2.3.4.5/32 Deny Generic
Tag: reCAPTCHA-Enabled
---
recaptcha:
 enabled: true
```

Nota: Un instancia de reCAPTCHA sólo se ofrecerán al usuario si reCAPTCHA está activado (ya sea con "usemode" como 1, o "usemode" como 2 con "enabled" como true), y si exactamente UNA firma se desencadena (ni mas, ni menos; si múltiples firmas se desencadena, una instancia de reCAPTCHA NO se ofrecerán).

####6.3 AUXILIAR

En adición, si quieres CIDRAM ignorar completamente algunas secciones específicas dentro de cualquiera de los archivos de firmas, puede utilizar el archivo `ignore.dat` para especificar qué secciones por ignorar. En una línea nueva, escribir `Ignore`, seguido de un espacio, seguido del nombre de la sección que desea CIDRAM ignorar (vea el ejemplo siguiente).

```
Ignore Section 1
```

Consulte los archivos de firmas personalizadas para obtener más información.

---


###7. <a name="SECTION7"></a>PREGUNTAS MÁS FRECUENTES (FAQ)

####¿Qué es un "falso positivo"?

El término "falso positivo" (*alternativamente: "error falso positivo"; "falsa alarma"*; Inglés: *false positive*; *false positive error*; *false alarm*), descrito muy simplemente, y en un contexto generalizado, se utiliza cuando se prueba para una condición, para referirse a los resultados de esa prueba, cuando los resultados son positivos (es decir, la condición se determina como "positivo", o "verdadero"), pero se espera que sean (o debería haber sido) negativo (es decir, la condición, en realidad, es "negativo", o "falso"). Un "falso positivo" podría considerarse análoga a "llorando lobo" (donde la condición que se está probando es si hay un lobo cerca de la manada, la condición es "falso" en el que no hay lobo cerca de la manada, y la condición se reporta como "positiva" por el pastor a modo de llamando "lobo, lobo"), o análogos a situaciones en las pruebas médicas donde un paciente es diagnosticado con alguna enfermedad o dolencia, cuando en realidad, no tienen tal enfermedad o dolencia.

Algunos términos relacionados para cuando se prueba para un condición son "verdadero positivo", "verdadero negativo" y "falso negativo". Un "verdadero positivo" se refiere a cuando los resultados de la prueba y el estado real de la condición son ambas verdaderas (o "positivas"), y un "verdadero negativo" se refiere a cuando los resultados de la prueba y el estado real de la condición son ambas falsas (o "negativas"); Un "verdadero positivo" o "negativo verdadero" se considera que es una "inferencia correcta". La antítesis de un "falso positivo" es un "falso negativo"; Un "falso negativo" se refiere a cuando los resultados de la prueba son negativos (es decir, la condición se determina como "negativo", o "falso"), pero se espera que sean (o debería haber sido) positivo (es decir, la condición, en realidad, es "positivo", o "verdadero").

En el contexto de CIDRAM, estos términos se refieren a las firmas de CIDRAM y lo qué/quién se bloquean. Cuando CIDRAM se bloquean una dirección IP debido al mal, obsoleta o firmas incorrectas, pero no debería haber hecho, o cuando lo hace por las razones equivocadas, nos referimos a este evento como un "falso positivo". Cuando CIDRAM no puede bloquear una dirección IP que debería haber sido bloqueado, debido a las amenazas imprevistas, firmas perdidas o déficit en sus firmas, nos referimos a este evento como una "detección perdida" o "missed detection" (que es análogo a un "falso negativo").

Esto se puede resumir en la siguiente tabla:

&nbsp; | CIDRAM *NO* debe bloquear una dirección IP | CIDRAM *DEBE* bloquear una dirección IP
---|---|---
CIDRAM *NO* hace bloquear una dirección IP | Verdadero negativo (inferencia correcta) | Detección perdida (análogo a un falso negativo)
CIDRAM *HACE* bloquear una dirección IP | __Falso positivo__ | Verdadero positivo (inferencia correcta)

---


Última Actualización: 11 Octubre 2016 (2016.10.11).
