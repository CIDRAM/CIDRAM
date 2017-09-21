## Documentación para CIDRAM (Español).

### Contenidos
- 1. [PREÁMBULO](#SECTION1)
- 2. [CÓMO INSTALAR](#SECTION2)
- 3. [CÓMO USAR](#SECTION3)
- 4. [GESTIÓN DEL FRONT-END](#SECTION4)
- 5. [ARCHIVOS INCLUIDOS EN ESTE PAQUETE](#SECTION5)
- 6. [OPCIONES DE CONFIGURACIÓN](#SECTION6)
- 7. [FORMATO DE FIRMAS](#SECTION7)
- 8. [PREGUNTAS MÁS FRECUENTES (FAQ)](#SECTION8)

*Nota relativa a las traducciones: En caso de errores (por ejemplo, discrepancias entre traducciones, errores tipográficos, etc), la versión en Inglés del README se considera la versión original y autorizada. Si encuentra algún error, su ayuda para corregirlo sera bienvenida.*

---


### 1. <a name="SECTION1"></a>PREÁMBULO

CIDRAM (Classless Inter-Domain Routing Access Manager) es un script PHP diseñado para proteger sitios web bloqueando solicitudes desde direcciones IP consideradas como fuentes de tráfico no deseado, incluyendo (pero no limitado a) tráfico desde puntos de acceso inhumanos, servicios en la nube, spambots, scrapers, etc. Esto se hace calculando las posibles CIDRs de las direcciones IP suministradas desde solicitudes entrantes e intentando hacer coincidir estos posibles CIDRs en contra de sus archivos de firmas (estos archivos de firmas contienen listas de CIDRs de direcciones IP consideradas como fuentes de tráfico no deseado); Si se encuentran coincidencias, las solicitudes son bloqueadas.

*(Ver: [¿Qué es un "CIDR"?](#WHAT_IS_A_CIDR)).*

CIDRAM COPYRIGHT 2016 y más allá GNU/GPLv2 por Caleb M (Maikuolan).

Este script es un software gratuito; puede redistribuirlo y/o modificarlo según los términos de la GNU General Public License, publicada por la Free Software Foundation; tanto la versión 2 de la licencia como cualquier versión posterior. Este script es distribuido con la esperanza de que será útil, pero SIN NINGUNA GARANTÍA; también, sin ninguna implícita garantía de COMERCIALIZACIÓN o IDONEIDAD PARA UN PARTICULAR PROPÓSITO. Vea la GNU General Public License para más detalles, ubicada en el `LICENSE.txt` archivo también disponible en:
- <http://www.gnu.org/licenses/>.
- <http://opensource.org/licenses/>.

Este documento y su paquete asociado puede ser descargado de forma gratuita desde [GitHub](https://cidram.github.io/).

---


### 2. <a name="SECTION2"></a>CÓMO INSTALAR

#### 2.0 INSTALACIÓN MANUAL

1) Dado el hecho que estas leiendo esto, asumo que ya ha descargado y guardado una copia del script, descomprimido sus contenidos, teniendolo en algún lugar en su ordenador. Ahora, usted querrá averiguar en que parte del host o CMS desea colocar estos contenidos. Un directorio como `/public_html/cidram/` o similar (aunque, no importa el que usted elija, siempre y cuando sea algo seguro y con lo que estas satisfecho) será suficiente. *Antes de empezar a subir archivos, continue leyendo...*

2) Cambiar el nombre del archivo `config.ini.RenameMe` a `config.ini` (situado en el interior del `vault`), y opcionalmente (muy recomendable para usuarios avanzados, pero no recomendado para los usuarios principiantes o inexpertos), abre el archivo (este archivo contiene todas las directrizes disponibles para CIDRAM; encima de cada opción debe haber un breve comentario que describe lo que hace y para lo qué sirve). Ajuste estas opciones según sus necesidades, según lo que sea apropiado para su particular configuración. Guardar archivo, cerrar.

3) Subir los contenidos (CIDRAM y sus archivos) al directorio que habías decidido previamente (no necessitas incluir los archivos `*.txt`/`*.md` , pero deberias subir el resto).

4) CHMOD al `vault` directorio "755" (si tienes problemas, puede intentar "777"; aunque es menos seguro). El principal directorio de almacenamiento de los contenidos (el que escogio antes), en general, puede dejarlo solo, pero el estado del CHMOD deberia estar revisado si ha tenido problemas de permisos en su sistema en el pasado (predefinido, debería ser algo como "755").

5) Luego, tendrás que "enganchar" CIDRAM a tu sistema o CMS. Hay varias maneras en que usted puede "enganchar" scripts como CIDRAM a su sistema o CMS, pero el más fácil es simplemente incluir el script al principio de un archivo central de su sistema o CMS (uno que en general siempre sea cargado cuando alguien accede a cualquier página a través de su web) utilizando un `require` o `include` declaración. Por lo general, esto sera algo almacenado en un directorio como `/includes`, `/assets` o `/functions`, y será menudo llamado algo así como `init.php`, `common_functions.php`, `functions.php` o similar. Vas a tener que averiguar qué archivo es por su situación; Si encuentra dificultades para resolver esto, visite la página de problemas/issues CIDRAM en GitHub. Para ello [utilizar `require` o `include`], inserte la siguiente línea de código al principio de ese núcleo archivo, reemplazando la cuerda contenida dentro de las comillas con la exacta dirección del `loader.php` archivo (dirección local, no la dirección HTTP; será similar a la dirreción `vault` mencionada anteriormente).

`<?php require '/user_name/public_html/cidram/loader.php'; ?>`

Guardar archivo, cerrarlo, subir otra vez.

-- O ALTERNATIVAMENTE --

Si está utilizando un servidor Apache y si usted tiene acceso a `php.ini`, puede utilizar la `auto_prepend_file` dirección para anteponer CIDRAM cuando cualquier solicitud PHP sea realizada. Algo como:

`auto_prepend_file = "/user_name/public_html/cidram/loader.php"`

O esto en el archivo `.htaccess`:

`php_value auto_prepend_file "/user_name/public_html/cidram/loader.php"`

6) ¡Eso es todo! :-)

#### 2.1 INSTALACIÓN CON COMPOSER

[CIDRAM está registrado con Packagist](https://packagist.org/packages/cidram/cidram), y por lo tanto, si está familiarizado con Composer, puede utilizar Composer para instalar CIDRAM (sin embargo, usted todavía necesitará preparar la configuración y los ganchos; consulte "INSTALACIÓN MANUAL" pasos 2 y 5).

`composer require cidram/cidram`

#### 2.2 INSTALACIÓN PARA WORDPRESS

Si desea utilizar CIDRAM con WordPress, puede ignorar todas las instrucciones anteriores. [CIDRAM está registrado como un plugin con la base de datos de plugins de WordPress](https://wordpress.org/plugins/cidram/), y puede instalar CIDRAM directamente desde el panel de plugins. Puede instalarlo de la misma manera que cualquier otro plugin, y no se requieren pasos adicionales. Al igual que con los otros métodos de instalación, puede personalizar su instalación modificando el contenido del archivo `config.ini` o utilizando el interfaz de usuario en la página de configuración. Si habilita el interfaz de CIDRAM y actualiza CIDRAM usando la página de actualizacione del interfaz, esto se sincronizará automáticamente con la información de la versión plugin mostrada en el panel de plugins.

*¡Advertencia: Actualizar CIDRAM a través del panel de plugins da como resultado una instalación limpia! Si ha personalizado su instalación (cambiado su configuración, módulos instalados, etc), estas personalizaciones se perderán al actualizar a través del panel de plugins! Los archivos de registro también se perderán al actualizar a través del panel de plugins! Para conservar los archivos de registro y las personalizaciones, actualice a través de la página de actualizaciones del front-end de CIDRAM.*

---


### 3. <a name="SECTION3"></a>CÓMO USAR

CIDRAM debe bloquear automáticamente las solicitudes indeseables a su website sin requiriendo intervención manual, aparte de sus instalación inicial.

Actualización se realiza manual, y usted puede personalizar su configuración y personalizar cuál de los CIDRs son bloqueados por modificando su archivo de configuración y/o su archivos de firmas.

Si tiene algún falsos positivos, por favor contacto conmigo para decirme. *(Ver: [¿Qué es un "falso positivo"?](#WHAT_IS_A_FALSE_POSITIVE)).*

---


### 4. <a name="SECTION4"></a>GESTIÓN DEL FRONT-END

#### 4.0 CUÁL ES EL FRONT-END.

El front-end proporciona una manera cómoda y fácil de mantener, administrar y actualizar la instalación de CIDRAM. Puede ver, compartir y descargar archivos de registro a través de la página de registros, puede modificar la configuración a través de la página de configuración, puede instalar y desinstalar componentes a través de la página de actualizaciones, y puede cargar, descargar y modificar archivos en su vault a través del administración de archivos.

El front-end está desactivado de forma predeterminada para evitar el acceso no autorizado (el acceso no autorizado podría tener consecuencias significativas para su sitio web y su seguridad). Las instrucciones para habilitarlo se incluyen debajo de este párrafo.

#### 4.1 CÓMO HABILITAR EL FRONT-END.

1) Localizar la directiva `disable_frontend` dentro `config.ini`, y establézcalo en `false` (será predefinido como `true`).

2) Accesar `loader.php` desde tu navegador (p.ej., `http://localhost/cidram/loader.php`).

3) Inicie sesión con el nombre del usuario y la contraseña predeterminados (admin/password).

Nota: Después de iniciar la sesión por primera vez, con el fin de impedir el acceso no autorizado al front-end, usted debe cambiar inmediatamente su nombre de usuario y su contraseña! Esto es muy importante, ya que es posible subir código arbitrario de PHP a su sitio web a través del front-end.

#### 4.2 CÓMO UTILIZAR EL FRONT-END.

Las instrucciones se proporcionan en cada página del front-end, para explicar la manera correcta de usarlo y su propósito. Si necesita más explicaciones o cualquier ayuda especial, póngase en contacto con el soporte. Alternativamente, hay algunos videos disponibles en YouTube que podrían ayudar a modo de demostración.


---


### 5. <a name="SECTION5"></a>ARCHIVOS INCLUIDOS EN ESTE PAQUETE

La siguiente es una lista de todos los archivos que debería haberse incluido en la copia de este script cuando descargado, todos los archivos que pueden ser potencialmente creados como resultado de su uso de este script, junto con una breve descripción de lo que todos estos archivos son para.

Archivo | Descripción
----|----
/_docs/ | Documentación directorio (contiene varios archivos).
/_docs/readme.ar.md | Documentación Árabe.
/_docs/readme.de.md | Documentación Alemán.
/_docs/readme.en.md | Documentación Inglés.
/_docs/readme.es.md | Documentación Español.
/_docs/readme.fr.md | Documentación Francés.
/_docs/readme.id.md | Documentación Indonesio.
/_docs/readme.it.md | Documentación Italiano.
/_docs/readme.ja.md | Documentación Japonés.
/_docs/readme.ko.md | Documentación Koreano.
/_docs/readme.nl.md | Documentación Holandés.
/_docs/readme.pt.md | Documentación Portugués.
/_docs/readme.ru.md | Documentación Ruso.
/_docs/readme.ur.md | Documentación Urdu.
/_docs/readme.vi.md | Documentación Vietnamita.
/_docs/readme.zh-TW.md | Documentación Chino (tradicional).
/_docs/readme.zh.md | Documentación Chino (simplificado).
/vault/ | Vault directorio (contiene varios archivos).
/vault/fe_assets/ | Archivos del front-end.
/vault/fe_assets/.htaccess | Un hipertexto acceso archivo (en este caso, para proteger confidenciales archivos perteneciente a la script contra el acceso de fuentes no autorizadas).
/vault/fe_assets/_accounts.html | Un archivo HTML para el front-end página de cuentas.
/vault/fe_assets/_accounts_row.html | Un archivo HTML para el front-end página de cuentas.
/vault/fe_assets/_cidr_calc.html | Un archivo HTML para la calculadora CIDR.
/vault/fe_assets/_cidr_calc_row.html | Un archivo HTML para la calculadora CIDR.
/vault/fe_assets/_config.html | Un archivo HTML para el front-end página de configuración.
/vault/fe_assets/_config_row.html | Un archivo HTML para el front-end página de configuración.
/vault/fe_assets/_files.html | Un archivo HTML para el administración de archivos.
/vault/fe_assets/_files_edit.html | Un archivo HTML para el administración de archivos.
/vault/fe_assets/_files_rename.html | Un archivo HTML para el administración de archivos.
/vault/fe_assets/_files_row.html | Un archivo HTML para el administración de archivos.
/vault/fe_assets/_home.html | Un archivo HTML para el front-end página principal.
/vault/fe_assets/_ip_aggregator.html | Un archivo HTML para el agregador IP.
/vault/fe_assets/_ip_test.html | Un archivo HTML para la página para pruebas de IPs.
/vault/fe_assets/_ip_test_row.html | Un archivo HTML para la página para pruebas de IPs.
/vault/fe_assets/_ip_tracking.html | Un archivo HTML para la página de seguimiento de IP.
/vault/fe_assets/_ip_tracking_row.html | Un archivo HTML para la página de seguimiento de IP.
/vault/fe_assets/_login.html | Un archivo HTML para el front-end página de login.
/vault/fe_assets/_logs.html | Un archivo HTML para el front-end página de los archivos de registro.
/vault/fe_assets/_nav_complete_access.html | Un archivo HTML para el menú de navegación del front-end, para aquellos con acceso completo.
/vault/fe_assets/_nav_logs_access_only.html | Un archivo HTML para el menú de navegación del front-end, para aquellos con acceso de registros solamente.
/vault/fe_assets/_updates.html | Un archivo HTML para el front-end página de actualizaciones.
/vault/fe_assets/_updates_row.html | Un archivo HTML para el front-end página de actualizaciones.
/vault/fe_assets/frontend.css | Hoja de estilo CSS para el front-end.
/vault/fe_assets/frontend.dat | Base de datos para el front-end (contiene información de cuentas, información de sesiones, y la memoria caché; sólo se genera si el front-end está activado y utilizado).
/vault/fe_assets/frontend.html | El archivo HTML principal para el front-end.
/vault/fe_assets/icons.php | Archivo de iconos (utilizado por el administración de archivos del front-end).
/vault/fe_assets/pips.php | Archivo de pips (utilizado por el administración de archivos del front-end).
/vault/lang/ | Contiene lingüísticos datos.
/vault/lang/.htaccess | Un hipertexto acceso archivo (en este caso, para proteger confidenciales archivos perteneciente a la script contra el acceso de fuentes no autorizadas).
/vault/lang/lang.ar.cli.php | Lingüísticos datos Árabe para CLI.
/vault/lang/lang.ar.fe.php | Lingüísticos datos Árabe para el front-end.
/vault/lang/lang.ar.php | Lingüísticos datos Árabe.
/vault/lang/lang.de.cli.php | Lingüísticos datos Alemán para CLI.
/vault/lang/lang.de.fe.php | Lingüísticos datos Alemán para el front-end.
/vault/lang/lang.de.php | Lingüísticos datos Alemán.
/vault/lang/lang.en.cli.php | Lingüísticos datos Inglés para CLI.
/vault/lang/lang.en.fe.php | Lingüísticos datos Inglés para el front-end.
/vault/lang/lang.en.php | Lingüísticos datos Inglés.
/vault/lang/lang.es.cli.php | Lingüísticos datos Español para CLI.
/vault/lang/lang.es.fe.php | Lingüísticos datos Español para el front-end.
/vault/lang/lang.es.php | Lingüísticos datos Español.
/vault/lang/lang.fr.cli.php | Lingüísticos datos Francés para CLI.
/vault/lang/lang.fr.fe.php | Lingüísticos datos Francés para el front-end.
/vault/lang/lang.fr.php | Lingüísticos datos Francés.
/vault/lang/lang.hi.cli.php | Lingüísticos datos Hindi para CLI.
/vault/lang/lang.hi.fe.php | Lingüísticos datos Hindi para el front-end.
/vault/lang/lang.hi.php | Lingüísticos datos Hindi.
/vault/lang/lang.id.cli.php | Lingüísticos datos Indonesio para CLI.
/vault/lang/lang.id.fe.php | Lingüísticos datos Indonesio para el front-end.
/vault/lang/lang.id.php | Lingüísticos datos Indonesio.
/vault/lang/lang.it.cli.php | Lingüísticos datos Italiano para CLI.
/vault/lang/lang.it.fe.php | Lingüísticos datos Italiano para el front-end.
/vault/lang/lang.it.php | Lingüísticos datos Italiano.
/vault/lang/lang.ja.cli.php | Lingüísticos datos Japonés para CLI.
/vault/lang/lang.ja.fe.php | Lingüísticos datos Japonés para el front-end.
/vault/lang/lang.ja.php | Lingüísticos datos Japonés.
/vault/lang/lang.ko.cli.php | Lingüísticos datos Koreano para CLI.
/vault/lang/lang.ko.fe.php | Lingüísticos datos Koreano para el front-end.
/vault/lang/lang.ko.php | Lingüísticos datos Koreano.
/vault/lang/lang.nl.cli.php | Lingüísticos datos Holandés para CLI.
/vault/lang/lang.nl.fe.php | Lingüísticos datos Holandés para el front-end.
/vault/lang/lang.nl.php | Lingüísticos datos Holandés.
/vault/lang/lang.pt.cli.php | Lingüísticos datos Portugués para CLI.
/vault/lang/lang.pt.fe.php | Lingüísticos datos Portugués para el front-end.
/vault/lang/lang.pt.php | Lingüísticos datos Portugués.
/vault/lang/lang.ru.cli.php | Lingüísticos datos Ruso para CLI.
/vault/lang/lang.ru.fe.php | Lingüísticos datos Ruso para el front-end.
/vault/lang/lang.ru.php | Lingüísticos datos Ruso.
/vault/lang/lang.th.cli.php | Lingüísticos datos Tailandés para CLI.
/vault/lang/lang.th.fe.php | Lingüísticos datos Tailandés para el front-end.
/vault/lang/lang.th.php | Lingüísticos datos Tailandés.
/vault/lang/lang.tr.cli.php | Lingüísticos datos Turco para CLI.
/vault/lang/lang.tr.fe.php | Lingüísticos datos Turco para el front-end.
/vault/lang/lang.tr.php | Lingüísticos datos Turco.
/vault/lang/lang.ur.cli.php | Lingüísticos datos Urdu para CLI.
/vault/lang/lang.ur.fe.php | Lingüísticos datos Urdi para el front-end.
/vault/lang/lang.ur.php | Lingüísticos datos Urdu.
/vault/lang/lang.vi.cli.php | Lingüísticos datos Vietnamita para CLI.
/vault/lang/lang.vi.fe.php | Lingüísticos datos Vietnamita para el front-end.
/vault/lang/lang.vi.php | Lingüísticos datos Vietnamita.
/vault/lang/lang.zh-tw.cli.php | Lingüísticos datos Chino (tradicional) para CLI.
/vault/lang/lang.zh-tw.fe.php | Lingüísticos datos Chino (tradicional) para el front-end.
/vault/lang/lang.zh-tw.php | Lingüísticos datos Chino (tradicional).
/vault/lang/lang.zh.cli.php | Lingüísticos datos Chino (simplificado) para CLI.
/vault/lang/lang.zh.fe.php | Lingüísticos datos Chino (simplificado) para el front-end.
/vault/lang/lang.zh.php | Lingüísticos datos Chino (simplificado).
/vault/.htaccess | Un hipertexto acceso archivo (en este caso, para proteger confidenciales archivos perteneciente a la script contra el acceso de fuentes no autorizadas).
/vault/.travis.php | Utilizado por Travis CI para pruebas (no se requiere para usar la script).
/vault/.travis.yml | Utilizado por Travis CI para pruebas (no se requiere para usar la script).
/vault/aggregator.php | Agregador IP.
/vault/cache.dat | Cache data.
/vault/cidramblocklists.dat | Contiene información relativa a las listas opcionales para bloquear países proporcionadas por Macmathan; Utilizado por la página de actualizaciones proporcionada por el front-end.
/vault/cli.php | Módulo de la CLI.
/vault/components.dat | Contiene información relativa a los diversos componentes de CIDRAM; Utilizado por la página de actualizaciones proporcionada por el front-end.
/vault/config.ini.RenameMe | Archivo de configuración; Contiene todas las opciones de configuración para CIDRAM, instruyendo para qué hacer y cómo operar correctamente (cambiar el nombre para activar).
/vault/config.php | Módulo de configuración.
/vault/config.yaml | Archivo de valores predefinidos para la configuración; Contiene valores predefinidos para la configuración de CIDRAM.
/vault/frontend.php | Módulo del front-end.
/vault/functions.php | Archivo de funciones (esencial).
/vault/hashes.dat | Contiene una lista de hashes aceptadas (pertinente a la función de reCAPTCHA; sólo se genera si la función de reCAPTCHA está habilitada).
/vault/ignore.dat | Ignorar archivo (se utiliza para especificar qué secciones de firmas que CIDRAM debe ignorar).
/vault/ipbypass.dat | Contiene una lista de bypasses IP (pertinente a la función de reCAPTCHA; sólo se genera si la función de reCAPTCHA está habilitada).
/vault/ipv4.dat | Archivo de firmas por IPv4 (servicios en la nube no deseados y puntos finales no humanos).
/vault/ipv4_bogons.dat | Archivo de firmas por IPv4 (bogon/marciano CIDRs).
/vault/ipv4_custom.dat.RenameMe | Archivo de firmas por IPv4 personalizado (cambiar el nombre para activar).
/vault/ipv4_isps.dat | Archivo de firmas por IPv4 (ISPs peligroso y propenso a spam).
/vault/ipv4_other.dat | Archivo de firmas por IPv4 (CIDRs para proxies, VPNs y otros servicios misceláneos no deseados).
/vault/ipv6.dat | Archivo de firmas por IPv6 (servicios en la nube no deseados y puntos finales no humanos).
/vault/ipv6_bogons.dat | Archivo de firmas por IPv6 (bogon/marciano CIDRs).
/vault/ipv6_custom.dat.RenameMe | Archivo de firmas por IPv6 personalizado (cambiar el nombre para activar).
/vault/ipv6_isps.dat | Archivo de firmas por IPv6 (ISPs peligroso y propenso a spam).
/vault/ipv6_other.dat | Archivo de firmas por IPv6 (CIDRs para proxies, VPNs y otros servicios misceláneos no deseados).
/vault/lang.php | Lingüísticos datos.
/vault/modules.dat | Contiene información relativa a los diversos módulos para CIDRAM; Utilizado por la página de actualizaciones proporcionada por el front-end.
/vault/outgen.php | Generador de salida.
/vault/php5.4.x.php | Polyfills para PHP 5.4.X (necesario para la retrocompatibilidad de PHP 5.4.X; seguro para eliminar por versiones más recientes de PHP).
/vault/recaptcha.php | Módulo de reCAPTCHA.
/vault/rules_as6939.php | Archivo de reglas personalizado para AS6939.
/vault/rules_softlayer.php | Archivo de reglas personalizado para Soft Layer.
/vault/rules_specific.php | Archivo de reglas personalizado para algunos CIDRs específicos.
/vault/salt.dat | Archivo de sal (utilizado por algunas funciones periférico; solamente generada si es necesario).
/vault/template_custom.html | Template archivo; Plantilla para HTML salida producida por la CIDRAM generador de salida.
/vault/template_default.html | Template archivo; Plantilla para HTML salida producida por la CIDRAM generador de salida.
/vault/themes.dat | Archivo de temas; Utilizado por la página de actualizaciones proporcionada por el front-end.
/.gitattributes | Un archivo de la GitHub proyecto (no se requiere para usar la script).
/Changelog.txt | Un registro de los cambios realizados en la principal script entre las diferentes versiones (no se requiere para usar la script).
/composer.json | Composer/Packagist información (no se requiere para usar la script).
/CONTRIBUTING.md | Información en respecto a cómo contribuir al proyecto.
/LICENSE.txt | Una copia de la GNU/GPLv2 licencia (no se requiere para usar la script).
/loader.php | Cargador. Esto es lo que se supone debe enganchando (esencial)!
/README.md | Sumario información del proyecto.
/web.config | Un ASP.NET configuración archivo (en este caso, para proteger la `/vault` directorio contra el acceso de fuentes no autorizadas en el caso de que la script está instalado en un servidor basado en ASP.NET tecnologías).

---


### 6. <a name="SECTION6"></a>OPCIONES DE CONFIGURACIÓN
La siguiente es una lista de variables encuentran en la `config.ini` configuración archivo de CIDRAM, junto con una descripción de sus propósito y función.

#### "general" (Categoría)
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

"truncate"
- ¿Truncar archivos de registro cuando alcanzan cierto tamaño? Valor es el tamaño máximo en B/KB/MB/GB/TB que un archivo de registro puede crecer antes de ser truncado. El valor predeterminado de 0KB deshabilita el truncamiento (archivos de registro pueden crecer indefinidamente). Nota: ¡Se aplica a archivos de registro individuales! El tamaño de los archivos de registro no se considera colectivamente.

"timeOffset"
- Si el tiempo del servidor no coincide con la hora local, se puede especificar un offset aquí para ajustar la información de fecha/hora generado por CIDRAM de acuerdo a sus necesidades. Generalmente, se recomienda en lugar para ajustar la directiva de zona horaria en el archivo `php.ini`, pero a veces (por ejemplo, cuando se trabaja con proveedores de hosting compartido limitados) esto no siempre es posible hacer, y entonces, esta opción se proporciona aquí. El offset es en minutos.
- Ejemplo (para añadir una hora): `timeOffset=60`

"timeFormat"
- El formato de notación de fecha/hora usado por CIDRAM. Predefinido = `{Day}, {dd} {Mon} {yyyy} {hh}:{ii}:{ss} {tz}`.

"ipaddr"
- Dónde encontrar el IP dirección de la conectando request? (Útil para servicios como Cloudflare y tales). Predefinido = REMOTE_ADDR. AVISO: No cambie esto a menos que sepas lo que estás haciendo!

"forbid_on_block"
- Cual cabeceras debe CIDRAM responder con cuando bloquear acceso? False/200 = 200 OK [Predefinido]; True/403 = 403 Forbidden (Prohibido); 503 = 503 Service unavailable (Servicio no disponible).

"silent_mode"
- Debería CIDRAM silencio redirigir los intentos de acceso bloqueados en lugar de mostrar la página "Acceso Denegado"? En caso afirmativo, especifique la ubicación para redirigir los intentos de acceso bloqueados. Si no, dejar esta variable en blanco.

"lang"
- Especifique la predefinido del lenguaje para CIDRAM.

"numbers"
- Especifica cómo mostrar números.

"emailaddr"
- Si deseado, usted puede suministrar una dirección de email aquí que se dará a los usuarios cuando ellos están bloqueadas, para ellos utilizar como un punto de contacto para soporte y/o asistencia para el caso de ellos están bloqueadas por error. ADVERTENCIA: Cualquiera que sea la dirección de email que usted suministrar aquí, que será sin duda adquirida por spambots y raspadores/scrapers durante el curso de su siendo utilizar aquí, y entonces, se recomienda encarecidamente que si eliges para suministrar una dirección de email aquí, que se asegura de que la dirección de email usted suministrar aquí es una dirección desechable y/o una dirección que usted no se preocupan por para ser bombardeado por correo (en otras palabras, es probable que usted no quiere utilizar sus correos electrónicos personal principal o comercio principal).

"emailaddr_display_style"
- ¿Cómo prefieres que la dirección de email sea presentada a los usuarios? "default" = Enlace que se puede hacer clic. "noclick" = Texto que no se puede hacer clic.

"disable_cli"
- ¿Desactivar CLI modo? CLI modo está activado por predefinido, pero a veces puede interferir con ciertas herramientas de prueba (tal como PHPUnit, por ejemplo) y otras aplicaciones basadas en CLI. Si no es necesario desactivar CLI modo, usted debe ignorar esta directiva. False = Activar CLI modo [Predefinido]; True = Desactivar CLI modo.

"disable_frontend"
- ¿Desactivar el acceso front-end? El acceso front-end puede hacer CIDRAM más manejable, pero también puede ser un riesgo de seguridad. Se recomienda administrar CIDRAM a través del back-end cuando sea posible, pero el acceso front-end se proporciona para cuando no es posible. Mantenerlo desactivado a menos que lo necesite. False = Activar el acceso front-end; True = Desactivar el acceso front-end [Predefinido].

"max_login_attempts"
- Número máximo de intentos de login (front-end). Predefinido = 5.

"FrontEndLog"
- Archivo para registrar intentos de login al front-end. Especificar el nombre del archivo, o dejar en blanco para desactivar.

"ban_override"
- Anular "forbid_on_block" cuando "infraction_limit" es excedido? Cuando se anula: Las solicitudes bloqueadas devuelven una página en blanco (los archivos templates no se utilizan). 200 = No anular [Predefinido]; 403 = Anular con "403 Forbidden"; 503 = Anular con "503 Service unavailable".

"log_banned_ips"
- ¿Incluir las solicitudes bloqueadas de IPs prohibidos en los archivos de registro? True = Sí [Predefinido]; False = No.

"default_dns"
- Una lista delimitada por comas de los servidores DNS que se utilizarán para las búsquedas de nombres del host. Predefinido = "8.8.8.8,8.8.4.4" (Google DNS). AVISO: No cambie esto a menos que sepas lo que estás haciendo!

"search_engine_verification"
- ¿Intentar verificar las solicitudes de los motores de búsqueda? La verificación de los motores de búsqueda asegura que no serán prohibidos como resultado de exceder el número máximo de infracciones (la prohibición de los motores de búsqueda de su sitio web por lo general tendrán un efecto negativo sobre su ranking de motores de búsqueda, SEO, etc). Cuando se verifica, los motores de búsqueda se pueden bloquear como de costumbre, pero no se prohibirá. Cuando no se verifica, es posible que se les prohíba como resultado de exceder el número máximo de infracciones. Adicionalmente, la verificación de motores de búsqueda proporciona protección contra las solicitudes de motor de búsqueda falsas y contra entidades potencialmente maliciosas disfrazadas de motores de búsqueda (tales solicitudes serán bloqueadas cuando la verificación del motor de búsqueda esté habilitada). True = Activar la verificación del motores de búsqueda [Predefinido]; False = Desactivar la verificación del motores de búsqueda.

"protect_frontend"
- Especifica si las protecciones normalmente proporcionadas por CIDRAM deben aplicarse al front-end. True = Sí [Predefinido]; False = No.

"disable_webfonts"
- ¿Desactivar webfonts? True = Sí; False = No [Predefinido].

"maintenance_mode"
- ¿Activar modo de mantenimiento? True = Sí; False = No [Predefinido]. Desactiva todo lo que no sea el front-end. A veces útil para la actualización de su CMS, frameworks, etc.

"default_algo"
- Define qué algoritmo utilizar para todas las contraseñas y sesiones en el futuro. Opciones: PASSWORD_DEFAULT (predefinido), PASSWORD_BCRYPT, PASSWORD_ARGON2I (requiere PHP >= 7.2.0).

#### "signatures" (Categoría)
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

"modules"
- Una lista de archivos módulo a cargar después de comprobar las firmas IPv4/IPv6, delimitado por comas.

"default_tracktime"
- ¿Cuántos segundos para realizar el seguimiento de las IP prohibidas por los módulos? Predefinida = 604800 (1 semana).

"infraction_limit"
- Número máximo de infracciones a las que un IP puede incurrir antes de ser prohibido por el seguimiento de IP. Predefinida = 10.

"track_mode"
- ¿Cuándo se deben contar las infracciones? False = Cuando los IPs están bloqueados por módulos. True = Cuando los IP están bloqueados por cualquier razón.

#### "recaptcha" (Categoría)
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

#### "template_data" (Categoría)
Directivas/Variables para las plantillas y temas.

Relacionado a la salida HTML utilizado generar la página "Acceso Denegado". Si utiliza temas personalizados para CIDRAM, HTML se obtiene a partir del `template_custom.html` archivo, y para de otra manera, HTML se obtiene a partir del `template.html` archivo. Variables escritas a esta sección de la configuración archivo se procesado para el HTML a través de la sustitución de los nombres de variables circunfijo por llaves que se encuentran dentro del HTML con el variable datos correspondiente. Por ejemplo, dónde `foo="bar"`, cualquier instancias de `<p>{foo}</p>` que se encuentran dentro del HTML se convertirá `<p>bar</p>`.

"theme"
- Tema predefinido a utilizar para CIDRAM.

"Magnification"
- Ampliación de fuente. Predefinido = 1.

"css_url"
- El plantilla archivo para los temas personalizados utiliza externas CSS propiedades, mientras que el plantilla archivo para el predefinida tema utiliza internas CSS propiedades. Para instruir CIDRAM de utilizar el plantilla archivo para temas personalizados, especificar el público HTTP dirección de sus temas personalizados CSS archivos utilizando la `css_url` variable. Si lo deja en blanco la variable, CIDRAM utilizará el plantilla archivo para el predefinida tema.

---


### 7. <a name="SECTION7"></a>FORMATO DE FIRMAS

*Ver también:*
- *[¿Qué es una "firma"?](#WHAT_IS_A_SIGNATURE)*

#### 7.0 LOS FUNDAMENTOS

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

Las explicaciones pre-preparadas tienen soporte para L10N y puede ser traducido por la script basado en el idioma que especifique a la directiva `lang` de la configuración de la script. Adicionalmente, puede instruir a la script ignorar las firmas "Deny" basado en el valor de su `[Param]` (si están usando estas palabras abreviadas) a través de las directrivas especificadas por la configuración de la script (cada palabra abreviada tiene una directiva correspondiente ya sea para procesar las firmas correspondientes o para ignorarlos). Los valores de `[Param]` que no utilizan estas palabras abreviadas, sin embargo, no tienen soporte para L10N y por lo tanto NO será traducido por la script, y además, no son controlable directamente por la configuración de la script.

Las palabras abreviadas disponibles son:
- Bogon
- Cloud
- Generic
- Proxy
- Spam

#### 7.1 ETIQUETAS

Si desea dividir sus firmas personalizadas en secciones individuales, se puede identificar estas secciones individuales a la script mediante la adición de una "etiqueta de sección" inmediatamente después de las firmas de cada sección, junto con el nombre de su sección de firmas (vea el ejemplo siguiente).

```
# Sección 1.
1.2.3.4/32 Deny Bogon
2.3.4.5/32 Deny Cloud
4.5.6.7/32 Deny Generic
5.6.7.8/32 Deny Spam
6.7.8.9/32 Deny Proxy
Tag: Sección 1
```

Para romper las etiquetas de secciones y para asegurar que las etiquetas no son identificado incorrectamente a las secciones de firmas más temprano en los archivos de firmas, Simplemente asegúrese de que hay al menos dos saltos de línea consecutivos entre su etiqueta y su sección de firmas de más temprano. Cualquier firmas que no son etiquetados será predefinida a ya sea "IPv4" o "IPv6" (dependiendo de qué tipos de firmas se activan).

```
1.2.3.4/32 Deny Bogon
2.3.4.5/32 Deny Cloud

4.5.6.7/32 Deny Generic
5.6.7.8/32 Deny Spam
Tag: Sección 1
```

En el ejemplo anterior `1.2.3.4/32` y `2.3.4.5/32` será etiquetado como "IPv4", mientras `4.5.6.7/32` y `5.6.7.8/32` será etiquetado como "Sección 1".

Si desea firmas para expiran después de un tiempo, de una manera similar a las etiquetas de sección, se puede utilizar una "etiqueta de expiración" para especificar cuándo deben firmas dejarán de ser válidas. Etiquetas de expiración utilizan el formato "AAAA.MM.DD" (vea el ejemplo siguiente).

```
# Sección 1.
1.2.3.4/32 Deny Generic
2.3.4.5/32 Deny Generic
Expires: 2016.12.31
```

Etiquetas de sección y etiquetas de expiración se pueden utilizar en conjunción, y ambos son opcionales (vea el ejemplo siguiente).

```
# Sección Ejemplo.
1.2.3.4/32 Deny Generic
Tag: Sección Ejemplo
Expires: 2016.12.31
```

#### 7.2 YAML

##### 7.2.0 LOS FUNDAMENTOS DE YAML

Una forma simplificada de YAML markup se puede utilizar en los archivos de firmas con el propósito de definir los comportamientos y configuraciones específicas para las secciones de firmas individuales. Esto puede ser útil si desea que el valor de sus directivas de configuración diferir sobre la base de las firmas individuales y las secciones de firmas (por ejemplo; si desea proporcionar una dirección de email para los tickets de soporte para cualquier usuario bloqueadas por una firma particular, pero no desea proporcionar una dirección de email para tickets de soporte para usuarios bloqueados por cualquier otro firmas; si desea por algunas firmas específicas para desencadenar una redirección de página; si desea marcar una sección de firmas para usar con reCAPTCHA; si desea registrar los intentos de acceso bloqueados para archivos separados sobre la base de firmas individuales y/o secciones de firmas).

El uso de YAML markup en los archivos de firma es totalmente opcional (es decir, usted puede utilizarlo si desea hacerlo, pero no está obligado a hacerlo), y es capaz de aprovechar la mayoria (pero no todos) de las directivas de configuración.

Nota: La implementación de YAML markup en CIDRAM es muy simplista y muy limitado; Se tiene la intención de cumplir con los requisitos específicos para CIDRAM de una manera que tiene la familiaridad de YAML markup, pero no se sigue o cumple con las especificaciones oficiales (y por lo tanto no se comportará de la misma manera que las implementaciones más a fondo en otros lugares, y puede no ser apropiado para otros proyectos en otros lugares).

En CIDRAM, segmentos de YAML markup se identifican a la script a modo de tres guiones ("---"), y terminar junto a las secciones de firmas que acompañas vía doble saltos de línea. Un segmento típico de YAML markup dentro de una sección de firmas consiste en tres guiones en una línea inmediatamente después de la lista de CIDRs y cualquier etiquetas, seguido de una lista bidimensional de pares valores-clave (primera dimensión, categorías de directivas de configuración; segunda dimensión, directivas de configuración) de las directivas de configuración que deben modificarse (y para cual los valores) siempre que una firma dentro de esa sección de firmas se desencadena (ver los ejemplos siguientes).

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

##### 7.2.1 CÓMO "ESPECIALMENTE MARCAR" SECCIONES DE FIRMAS PARA USAR CON reCAPTCHA

Cuando "usemode" es 0 o 1, secciones de firmas no tienen que ser "especialmente marcado" para usar con reCAPTCHA (ya sea porque ya va utilizar o no va utilizar reCAPTCHA, dependiendo de esta directiva).

Cuando "usemode" es 2, para "especialmente marcar" secciones de firmas para usar con reCAPTCHA, una entrada está incluida en el segmento de YAML para que esa sección de firmas (vea el ejemplo siguiente).

```
# Esta sección utilizará reCAPTCHA.
1.2.3.4/32 Deny Generic
2.3.4.5/32 Deny Generic
Tag: reCAPTCHA-Enabled
---
recaptcha:
 enabled: true
```

Nota: Un instancia de reCAPTCHA sólo se ofrecerán al usuario si reCAPTCHA está activado (ya sea con "usemode" como 1, o "usemode" como 2 con "enabled" como true), y si exactamente UNA firma se desencadena (ni mas, ni menos; si múltiples firmas se desencadena, una instancia de reCAPTCHA NO se ofrecerán).

#### 7.3 AUXILIAR

En adición, si quieres CIDRAM ignorar completamente algunas secciones específicas dentro de cualquiera de los archivos de firmas, puede utilizar el archivo `ignore.dat` para especificar qué secciones por ignorar. En una línea nueva, escribir `Ignore`, seguido de un espacio, seguido del nombre de la sección que desea CIDRAM ignorar (vea el ejemplo siguiente).

```
Ignore Sección 1
```

Consulte los archivos de firmas personalizadas para obtener más información.

---


### 8. <a name="SECTION8"></a>PREGUNTAS MÁS FRECUENTES (FAQ)

#### <a name="WHAT_IS_A_SIGNATURE"></a>¿Qué es una "firma"?

En el contexto de CIDRAM, una "firma" se refiere a datos que actúan como un indicador/identificador para algo específico que estamos buscando, normalmente una dirección IP o CIDR, e incluye algunas instrucciones para CIDRAM, diciéndole la mejor manera de responder cuando encuentra lo que estamos buscando. Una firma típica para CIDRAM se parece a esto:

`1.2.3.4/32 Deny Generic`

A menudo (pero no siempre), las firmas se agruparán en grupos, formando "secciones de firmas", a menudo acompañado de comentarios, markup, y/o metadatos relacionados que se pueden utilizar para proporcionar contexto adicional para las firmas y/o instrucción adicional.

#### <a name="WHAT_IS_A_CIDR"></a>¿Qué es un "CIDR"?

"CIDR" es un acrónimo para "Classless Inter-Domain Routing" (o "enrutamiento entre dominios sin clases") *[[1](https://es.wikipedia.org/wiki/Classless_Inter-Domain_Routing), [2](http://whatismyipaddress.com/cidr)]*, y es este acrónimo que se utiliza como parte del nombre de este paquete, "CIDRAM", que es un acrónimo de "Classless Inter-Domain Routing Access Manager".

Aunque, en el contexto de CIDRAM (tal como, dentro de esta documentación, dentro de los discusiones relacionados con CIDRAM, o dentro de los lingüísticos datos para CIDRAM), en cualquier momento que un "CIDR" (singular) o "CIDRs" (plural) se menciona o se hace referencia a (y por lo tanto, mediante el cual utilizamos estas palabras como sustantivos por derecho propio, en contraposición a como acrónimos), lo que se pretende y quiere decir con esto es una subred (o subredes), expresado usando la notación CIDR. La razón por la que CIDR (o CIDRs) se utiliza en lugar de subred (o subredes) es dejar claro que se trata específicamente de subredes expresadas mediante la notación CIDR a la que se hace referencia (porque la notación CIDR es sólo una de varias maneras diferentes que las subredes se pueden expresar). Por lo tanto, CIDRAM podría considerarse un "gestor de acceso para subredes".

Aunque este doble significado de "CIDR" puede presentar alguna ambigüedad en algunos casos, esta explicacion, junto con el contexto proporcionado, debe ayudar a resolver esa ambigüedad.

#### <a name="WHAT_IS_A_FALSE_POSITIVE"></a>¿Qué es un "falso positivo"?

El término "falso positivo" (*alternativamente: "error falso positivo"; "falsa alarma"*; Inglés: *false positive*; *false positive error*; *false alarm*), descrito muy simplemente, y en un contexto generalizado, se utiliza cuando se prueba para una condición, para referirse a los resultados de esa prueba, cuando los resultados son positivos (es decir, la condición se determina como "positivo", o "verdadero"), pero se espera que sean (o debería haber sido) negativo (es decir, la condición, en realidad, es "negativo", o "falso"). Un "falso positivo" podría considerarse análoga a "llorando lobo" (donde la condición que se está probando es si hay un lobo cerca de la manada, la condición es "falso" en el que no hay lobo cerca de la manada, y la condición se reporta como "positiva" por el pastor a modo de llamando "lobo, lobo"), o análogos a situaciones en las pruebas médicas donde un paciente es diagnosticado con alguna enfermedad o dolencia, cuando en realidad, no tienen tal enfermedad o dolencia.

Algunos términos relacionados para cuando se prueba para un condición son "verdadero positivo", "verdadero negativo" y "falso negativo". Un "verdadero positivo" se refiere a cuando los resultados de la prueba y el estado real de la condición son ambas verdaderas (o "positivas"), y un "verdadero negativo" se refiere a cuando los resultados de la prueba y el estado real de la condición son ambas falsas (o "negativas"); Un "verdadero positivo" o "negativo verdadero" se considera que es una "inferencia correcta". La antítesis de un "falso positivo" es un "falso negativo"; Un "falso negativo" se refiere a cuando los resultados de la prueba son negativos (es decir, la condición se determina como "negativo", o "falso"), pero se espera que sean (o debería haber sido) positivo (es decir, la condición, en realidad, es "positivo", o "verdadero").

En el contexto de CIDRAM, estos términos se refieren a las firmas de CIDRAM y lo qué/quién se bloquean. Cuando CIDRAM se bloquean una dirección IP debido al mal, obsoleta o firmas incorrectas, pero no debería haber hecho, o cuando lo hace por las razones equivocadas, nos referimos a este evento como un "falso positivo". Cuando CIDRAM no puede bloquear una dirección IP que debería haber sido bloqueado, debido a las amenazas imprevistas, firmas perdidas o déficit en sus firmas, nos referimos a este evento como una "detección perdida" o "missed detection" (que es análogo a un "falso negativo").

Esto se puede resumir en la siguiente tabla:

&nbsp; | CIDRAM *NO* debe bloquear una dirección IP | CIDRAM *DEBE* bloquear una dirección IP
---|---|---
CIDRAM *NO* hace bloquear una dirección IP | Verdadero negativo (inferencia correcta) | Detección perdida (análogo a un falso negativo)
CIDRAM *HACE* bloquear una dirección IP | __Falso positivo__ | Verdadero positivo (inferencia correcta)

#### ¿Puede CIDRAM bloquear países enteros?

Sí. La forma más fácil de lograr esto sería instalar algunas de las listas opcionales para bloquear países proporcionadas por Macmathan. Esto se puede hacer con unos simples clics directamente desde la página de actualizaciones del front-end, o, si prefiere que el front-end permanezca desactivado, con descargándolas directamente desde la **[página de descargar las listas opcionales para bloquear países](https://macmathan.info/blocklists)**, subirlos a la vault, y citando sus nombres en las directivas de configuración pertinentes.

#### ¿Con qué frecuencia se actualizan las firmas?

La frecuencia de actualización varía dependiendo de los archivos de firma en cuestión. Todos los mantenedores de los archivos de firma para CIDRAM generalmente tratan de mantener sus firmas tan actualizadas como sea posible, pero como todos nosotros tenemos varios otros compromisos, nuestras vidas fuera del proyecto, y como ninguno de nosotros es financieramente compensado (o pagado) por nuestros esfuerzos en el proyecto, no se puede garantizar un calendario de actualización preciso. Generalmente, las firmas se actualizan siempre que haya suficiente tiempo para actualizarlas, y generalmente, los mantenedores tratan de priorizar basándose en la necesidad y en la frecuencia con la que ocurren cambios entre rangos. La ayuda siempre es apreciada si usted está dispuesto a ofrecer cualquiera.

#### ¡He encontrado un problema mientras uso CIDRAM y no sé qué hacer al respecto! ¡Por favor ayuda!

- ¿Está utilizando la última versión del software? ¿Está utilizando las últimas versiones de sus archivos de firma? Si la respuesta a cualquiera de estas dos preguntas es no, intente actualizar todo primero, y compruebe si el problema persiste. Si persiste, continúe leyendo.
- ¿Ha revisado toda la documentación? Si no, por favor, hágalo. Si el problema no puede resolverse utilizando la documentación, continúe leyendo.
- ¿Ha revisado la **[página de problemas](https://github.com/CIDRAM/CIDRAM/issues)**, para ver si el problema ha sido mencionado antes? Si se ha mencionado antes, compruebe si se han proporcionado sugerencias, ideas y/o soluciones, y siga según sea necesario para tratar de resolver el problema.
- ¿Ha consultado el **[foro de soporte para CIDRAM proporcionado por Spambot Security](http://www.spambotsecurity.com/forum/viewforum.php?f=61)**, para ver si el problema ha sido mencionado antes? Si se ha mencionado antes, compruebe si se han proporcionado sugerencias, ideas y/o soluciones, y siga según sea necesario para tratar de resolver el problema.
- Si el problema persiste, comuníquenoslo creando un nuevo discusión en la página de problemas o en el foro de soporte.

#### ¡He sido bloqueado por CIDRAM desde un sitio web que quiero visitar! ¡Por favor ayuda!

CIDRAM proporciona un medio para que los propietarios de sitios web bloqueen tráfico indeseable, pero es responsabilidad de los propietarios de sitios web decidir por sí mismos cómo quieren usar CIDRAM. En el caso de los falsos positivos relativos a los archivos de firma normalmente incluidos en CIDRAM, correcciones pueden hacerse, Pero en lo que respecta a ser desbloqueado de sitios web específicos, usted tendrá que tomar eso con los propietarios de los sitios web en cuestión. En los casos en que se realizan correcciones, por lo menos, tendrán que actualizar sus archivos de firma y/o instalación, y en otros casos (tales como, por ejemplo, donde han modificado su instalación, crearon sus propias firmas personalizadas, etc), la responsabilidad de resolver su problema es enteramente suya, y está totalmente fuera de nuestro control.

#### Quiero usar CIDRAM con una versión de PHP más vieja que 5.4.0; ¿Puede usted ayudar?

No. PHP 5.4.0 llegó a EoL oficial ("End of Life", o fin de la vida) en 2014, y el soporte extendido de la seguridad fue terminado en 2015. Al escribir esto, es 2017, y PHP 7.1.0 ya está disponible. En este momento, se proporciona soporte para el uso de CIDRAM con PHP 5.4.0 y todas las nuevas versiones PHP disponibles, pero si intenta usar CIDRAM con versiones anteriores de PHP, no se proporcionará soporte.

*Ver también: [Gráficos de Compatibilidad](https://maikuolan.github.io/Compatibility-Charts/).*

#### ¿Puedo usar una sola instalación de CIDRAM para proteger múltiples dominios?

Sí. Las instalaciones de CIDRAM no están ligados naturalmente en dominios específicos, y por lo tanto puede ser utilizado para proteger múltiples dominios. En general, nos referimos a las instalaciones de CIDRAM que protegen solo un dominio como "instalaciones solo-dominio" ("single-domain installations"), y nos referimos a las instalaciones de CIDRAM que protegen múltiples dominios y/o subdominios como "instalaciones multi-dominio" ("multi-domain installations"). Si utiliza una instalación multi-dominio y es necesario utilizar diferentes conjuntos de archivos de firmas para diferentes dominios, o si CIDRAM debe configurarse de manera diferente para diferentes dominios, es posible hacer esto. Después de cargar el archivo de configuración (`config.ini`), CIDRAM comprobará la existencia de un "archivo de sustitución para configuración" específico del dominio (o subdominio) que se solicita (`el-dominio-que-se-solicita.tld.config.ini`), y si se encuentra, cualquier valor de configuración definido por el archivo de sustitución para configuración se utilizará para la instancia de ejecución en lugar de los valores de configuración definidos por el archivo de configuración. Los archivos de sustitución para configuración son idénticos al archivo de configuración, ya su discreción, puede contener la totalidad de todas las directivas de configuración disponibles para CIDRAM, o lo que sea subsección necesaria que difiera de los valores normalmente definidos por el archivo de configuración. Los archivos de sustitución para configuración se nombran de acuerdo con el dominio al que están destinados (así por ejemplo, si se requiere un archivo de sustitución para configuración para el dominio, `http://www.some-domain.tld/`, su archivo de sustitución para configuración debe ser nombrado como `some-domain.tld.config.ini`, y debe colocarse dentro de la vault junto con el archivo de configuración, `config.ini`). El nombre del dominio para la instancia de ejecución se deriva del encabezado `HTTP_HOST` de la solicitud; "www" se ignora.

#### No quiero molestarme con la instalación de este y conseguir que funcione con mi sitio web; ¿Puedo pagarte por hacer todo por mí?

Quizás. Esto se considera caso por caso. Háganos saber lo que necesita, lo que está ofreciendo y le diremos si podemos ayudar.

#### ¿Puedo contratar a usted oa cualquiera de los desarrolladores de este proyecto para el trabajo privado?

*Ver la respuesta anterior.*

#### Necesito modificaciones especiales, personalizaciones, etc; ¿Puede usted ayudar?

*Ver la respuesta anterior.*

#### Soy desarrollador, diseñador de sitios web o programador. ¿Puedo aceptar u ofrecer trabajos relacionados con este proyecto?

Sí. Nuestra licencia no lo prohíbe.

#### Quiero contribuir al proyecto; ¿Puedo hacer esto?

Sí. Las contribuciones al proyecto son muy bienvenidas. Consulte "CONTRIBUTING.md" para obtener más información.

#### Valores recomendados para "ipaddr".

Valor | Utilizando
---|---
`HTTP_INCAP_CLIENT_IP` | Proxy inverso Incapsula.
`HTTP_CF_CONNECTING_IP` | Proxy inverso Cloudflare.
`CF-Connecting-IP` | Proxy inverso Cloudflare (alternativa; si lo anterior no funciona).
`HTTP_X_FORWARDED_FOR` | Proxy inverso Cloudbric.
`X-Forwarded-For` | [Proxy inverso Squid](http://www.squid-cache.org/Doc/config/forwarded_for/).
*Definido por la configuración del servidor.* | [Proxy inverso Nginx](https://www.nginx.com/resources/admin-guide/reverse-proxy/).
`REMOTE_ADDR` | Sin proxy inverso (valor predefinido).

---


Última Actualización: 21 Septiembre 2017 (2017.09.21).
