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
 * This file: Spanish language data for the front-end (last modified: 2017.10.15).
 */

/** Prevents execution from outside of CIDRAM. */
if (!defined('CIDRAM')) {
    die('[CIDRAM] This should not be accessed directly.');
}

$CIDRAM['lang']['bNav_home_logout'] = '<a href="?">Página Principal</a> | <a href="?cidram-page=logout">Cerrar Sesión</a>';
$CIDRAM['lang']['bNav_logout'] = '<a href="?cidram-page=logout">Cerrar Sesión</a>';
$CIDRAM['lang']['config_general_ban_override'] = 'Anular "forbid_on_block" cuando "infraction_limit" es excedido? Cuando se anula: Las solicitudes bloqueadas devuelven una página en blanco (los archivos templates no se utilizan). 200 = No anular [Predefinido]; 403 = Anular con "403 Forbidden"; 503 = Anular con "503 Service unavailable".';
$CIDRAM['lang']['config_general_default_algo'] = 'Define qué algoritmo utilizar para todas las contraseñas y sesiones en el futuro. Opciones: PASSWORD_DEFAULT (predefinido), PASSWORD_BCRYPT, PASSWORD_ARGON2I (requiere PHP >= 7.2.0).';
$CIDRAM['lang']['config_general_default_dns'] = 'Una lista delimitada por comas de los servidores DNS que se utilizarán para las búsquedas de nombres del host. Predefinido = "8.8.8.8,8.8.4.4" (Google DNS). AVISO: No cambie esto a menos que sepas lo que estás haciendo!';
$CIDRAM['lang']['config_general_disable_cli'] = '¿Desactivar CLI modo? CLI modo está activado por predefinido, pero a veces puede interferir con ciertas herramientas de prueba (tal como PHPUnit, por ejemplo) y otras aplicaciones basadas en CLI. Si no es necesario desactivar CLI modo, usted debe ignorar esta directiva. False = Activar CLI modo [Predefinido]; True = Desactivar CLI modo.';
$CIDRAM['lang']['config_general_disable_frontend'] = '¿Desactivar el acceso front-end? El acceso front-end puede hacer CIDRAM más manejable, pero también puede ser un riesgo de seguridad. Se recomienda administrar CIDRAM a través del back-end cuando sea posible, pero el acceso front-end se proporciona para cuando no es posible. Mantenerlo desactivado a menos que lo necesite. False = Activar el acceso front-end; True = Desactivar el acceso front-end [Predefinido].';
$CIDRAM['lang']['config_general_disable_webfonts'] = '¿Desactivar webfonts? True = Sí; False = No [Predefinido].';
$CIDRAM['lang']['config_general_emailaddr'] = 'Si deseado, usted puede suministrar una dirección de email aquí que se dará a los usuarios cuando ellos están bloqueadas, para ellos utilizar como un punto de contacto para soporte y/o asistencia para el caso de ellos están bloqueadas por error. ADVERTENCIA: Cualquiera que sea la dirección de email que usted suministrar aquí, que será sin duda adquirida por spambots y raspadores/scrapers durante el curso de su siendo utilizar aquí, y entonces, se recomienda encarecidamente que si eliges para suministrar una dirección de email aquí, que se asegura de que la dirección de email usted suministrar aquí es una dirección desechable y/o una dirección que usted no se preocupan por para ser bombardeado por correo (en otras palabras, es probable que usted no quiere utilizar sus correos electrónicos personal principal o comercio principal).';
$CIDRAM['lang']['config_general_emailaddr_display_style'] = '¿Cómo prefieres que la dirección de email sea presentada a los usuarios?';
$CIDRAM['lang']['config_general_forbid_on_block'] = 'Cual cabeceras debe CIDRAM responder con cuando bloquear acceso?';
$CIDRAM['lang']['config_general_FrontEndLog'] = 'Archivo para registrar intentos de login al front-end. Especificar el nombre del archivo, o dejar en blanco para desactivar.';
$CIDRAM['lang']['config_general_ipaddr'] = 'Dónde encontrar el IP dirección de la conectando request? (Útil para servicios como Cloudflare y tales). Predefinido = REMOTE_ADDR. AVISO: No cambie esto a menos que sepas lo que estás haciendo!';
$CIDRAM['lang']['config_general_lang'] = 'Especifique la predefinido del lenguaje para CIDRAM.';
$CIDRAM['lang']['config_general_logfile'] = 'Un archivo legible por humanos para el registro de todos los intentos de acceso bloqueados. Especificar el nombre del archivo, o dejar en blanco para desactivar.';
$CIDRAM['lang']['config_general_logfileApache'] = 'Un archivo en el estilo de Apache para el registro de todos los intentos de acceso bloqueados. Especificar el nombre del archivo, o dejar en blanco para desactivar.';
$CIDRAM['lang']['config_general_logfileSerialized'] = 'Un archivo serializado para el registro de todos los intentos de acceso bloqueados. Especificar el nombre del archivo, o dejar en blanco para desactivar.';
$CIDRAM['lang']['config_general_log_banned_ips'] = '¿Incluir las solicitudes bloqueadas de IPs prohibidos en los archivos de registro? True = Sí [Predefinido]; False = No.';
$CIDRAM['lang']['config_general_maintenance_mode'] = '¿Activar modo de mantenimiento? True = Sí; False = No [Predefinido]. Desactiva todo lo que no sea el front-end. A veces útil para la actualización de su CMS, frameworks, etc.';
$CIDRAM['lang']['config_general_max_login_attempts'] = 'Número máximo de intentos de login.';
$CIDRAM['lang']['config_general_numbers'] = '¿Cómo prefieres los números que se muestran? Seleccione el ejemplo que le parezca más correcto.';
$CIDRAM['lang']['config_general_protect_frontend'] = 'Especifica si las protecciones normalmente proporcionadas por CIDRAM deben aplicarse al front-end. True = Sí [Predefinido]; False = No.';
$CIDRAM['lang']['config_general_search_engine_verification'] = '¿Intentar verificar las solicitudes de los motores de búsqueda? La verificación de los motores de búsqueda asegura que no serán prohibidos como resultado de exceder el número máximo de infracciones (la prohibición de los motores de búsqueda de su sitio web por lo general tendrán un efecto negativo sobre su ranking de motores de búsqueda, SEO, etc). Cuando se verifica, los motores de búsqueda se pueden bloquear como de costumbre, pero no se prohibirá. Cuando no se verifica, es posible que se les prohíba como resultado de exceder el número máximo de infracciones. Adicionalmente, la verificación de motores de búsqueda proporciona protección contra las solicitudes de motor de búsqueda falsas y contra entidades potencialmente maliciosas disfrazadas de motores de búsqueda (tales solicitudes serán bloqueadas cuando la verificación del motor de búsqueda esté habilitada). True = Activar la verificación del motores de búsqueda [Predefinido]; False = Desactivar la verificación del motores de búsqueda.';
$CIDRAM['lang']['config_general_silent_mode'] = 'Debería CIDRAM silencio redirigir los intentos de acceso bloqueados en lugar de mostrar la página "Acceso Denegado"? En caso afirmativo, especifique la ubicación para redirigir los intentos de acceso bloqueados. Si no, dejar esta variable en blanco.';
$CIDRAM['lang']['config_general_statistics'] = '¿Seguir las estadísticas de uso de CIDRAM? True = Sí; False = No [Predefinido].';
$CIDRAM['lang']['config_general_timeFormat'] = 'El formato de notación de fecha/hora usado por CIDRAM. Se pueden añadir opciones adicionales bajo petición.';
$CIDRAM['lang']['config_general_timeOffset'] = 'Desplazamiento del huso horario en minutos.';
$CIDRAM['lang']['config_general_timezone'] = 'Tu zona horaria.';
$CIDRAM['lang']['config_general_truncate'] = '¿Truncar archivos de registro cuando alcanzan cierto tamaño? Valor es el tamaño máximo en B/KB/MB/GB/TB que un archivo de registro puede crecer antes de ser truncado. El valor predeterminado de 0KB deshabilita el truncamiento (archivos de registro pueden crecer indefinidamente). Nota: ¡Se aplica a archivos de registro individuales! El tamaño de los archivos de registro no se considera colectivamente.';
$CIDRAM['lang']['config_recaptcha_expiry'] = 'Número de horas para recordar instancias de reCAPTCHA.';
$CIDRAM['lang']['config_recaptcha_lockip'] = 'Ligar reCAPTCHA a los IPs?';
$CIDRAM['lang']['config_recaptcha_lockuser'] = 'Ligar reCAPTCHA a los usuarios?';
$CIDRAM['lang']['config_recaptcha_logfile'] = 'Registrar todos los intentos de reCAPTCHA? En caso afirmativo, especifique el nombre que se utilizará para el archivo de registro. Si no, dejar esta variable en blanco.';
$CIDRAM['lang']['config_recaptcha_secret'] = 'Este valor debe corresponder a la "secret key" para su reCAPTCHA, que se puede encontrar en el panel de control de reCAPTCHA.';
$CIDRAM['lang']['config_recaptcha_sitekey'] = 'Este valor debe corresponder a la "site key" para su reCAPTCHA, que se puede encontrar en el panel de control de reCAPTCHA.';
$CIDRAM['lang']['config_recaptcha_usemode'] = 'Define cómo CIDRAM debe utilizar reCAPTCHA (ver documentación).';
$CIDRAM['lang']['config_signatures_block_bogons'] = 'Bloquear CIDRs identificados como bogons/martians? Si usted espera conexiones a su sitio web desde dentro de su red local, desde localhost, o desde su LAN, esta directiva debe ser establecido para false. Si usted no espera estos tipos de conexiones, esta directiva debe ser establecido para true.';
$CIDRAM['lang']['config_signatures_block_cloud'] = 'Bloquear CIDRs identificados como pertenecientes de servicios de webhosting o servicios en la nube? Si usted operar un servicio de un API desde su sitio web o si usted espera otros sitios web para conectarse a su sitio web, esta directiva debe ser establecido para false. Si usted no espera esta, esta directiva debe ser establecido para true.';
$CIDRAM['lang']['config_signatures_block_generic'] = 'Bloquear CIDRs recomendado generalmente para las listas negras? Esto abarca todos las firmas que no están marcadas como parte de cualquiera de los otros mas especifico categorías de firmas.';
$CIDRAM['lang']['config_signatures_block_proxies'] = 'Bloquear CIDRs identificados como pertenecientes de servicios de proxy? Si requiere que los usuarios puedan acceder a su sitio web a partir de los servicios de proxy anónimos, esta directiva debe ser establecido para false. Alternativamente, Si usted no requiere proxies anónimos, esta directiva debe ser establecido para true como un medio para mejorar la seguridad.';
$CIDRAM['lang']['config_signatures_block_spam'] = 'Bloquear CIDRs identificado como siendo de alto riesgo para el spam? A menos que experimentar problemas cuando hacerlo, en general, esto siempre debe establecerse para true.';
$CIDRAM['lang']['config_signatures_default_tracktime'] = '¿Cuántos segundos para realizar el seguimiento de las IP prohibidas por los módulos? Predefinida = 604800 (1 semana).';
$CIDRAM['lang']['config_signatures_infraction_limit'] = 'Número máximo de infracciones a las que un IP puede incurrir antes de ser prohibido por el seguimiento de IP. Predefinida = 10.';
$CIDRAM['lang']['config_signatures_ipv4'] = 'Una lista de los archivos de firmas IPv4 que CIDRAM debe tratar de utilizar, delimitado por comas.';
$CIDRAM['lang']['config_signatures_ipv6'] = 'Una lista de los archivos de firmas IPv6 que CIDRAM debe tratar de utilizar, delimitado por comas.';
$CIDRAM['lang']['config_signatures_modules'] = 'Una lista de archivos módulo a cargar después de comprobar las firmas IPv4/IPv6, delimitado por comas.';
$CIDRAM['lang']['config_signatures_track_mode'] = '¿Cuándo se deben contar las infracciones? False = Cuando los IPs están bloqueados por módulos. True = Cuando los IP están bloqueados por cualquier razón.';
$CIDRAM['lang']['config_template_data_css_url'] = 'URL del archivo CSS para temas personalizados.';
$CIDRAM['lang']['config_template_data_Magnification'] = 'Ampliación de fuente. Predefinido = 1.';
$CIDRAM['lang']['config_template_data_theme'] = 'Tema predefinido a utilizar para CIDRAM.';
$CIDRAM['lang']['Extended Description: Bypasses'] = 'Los archivos para bypass de firmas estándar normalmente se incluyen con el paquete principal.';
$CIDRAM['lang']['Extended Description: CIDRAM'] = 'El paquete principal (menos las firmas, la documentación, y la configuración).';
$CIDRAM['lang']['field_activate'] = 'Activar';
$CIDRAM['lang']['field_banned'] = 'Prohibido';
$CIDRAM['lang']['field_blocked'] = 'Bloqueado';
$CIDRAM['lang']['field_clear'] = 'Anular';
$CIDRAM['lang']['field_clear_all'] = 'Anular todo';
$CIDRAM['lang']['field_clickable_link'] = 'Enlace que se puede hacer clic';
$CIDRAM['lang']['field_component'] = 'Componente';
$CIDRAM['lang']['field_create_new_account'] = 'Crear Nueva Cuenta';
$CIDRAM['lang']['field_deactivate'] = 'Desactivar';
$CIDRAM['lang']['field_delete_account'] = 'Eliminar Cuenta';
$CIDRAM['lang']['field_delete_file'] = 'Borrar';
$CIDRAM['lang']['field_download_file'] = 'Descargar';
$CIDRAM['lang']['field_edit_file'] = 'Editar';
$CIDRAM['lang']['field_expiry'] = 'Expiración';
$CIDRAM['lang']['field_false'] = 'False (Falso)';
$CIDRAM['lang']['field_file'] = 'Archivo';
$CIDRAM['lang']['field_filename'] = 'Nombre del archivo: ';
$CIDRAM['lang']['field_filetype_directory'] = 'Directorio';
$CIDRAM['lang']['field_filetype_info'] = '{EXT} Archivo';
$CIDRAM['lang']['field_filetype_unknown'] = 'Desconocido';
$CIDRAM['lang']['field_first_seen'] = 'Cuando se vio por primera vez';
$CIDRAM['lang']['field_infractions'] = 'Infracciones';
$CIDRAM['lang']['field_install'] = 'Instalar';
$CIDRAM['lang']['field_ip_address'] = 'Dirección IP';
$CIDRAM['lang']['field_latest_version'] = 'Ultima Versión';
$CIDRAM['lang']['field_log_in'] = 'Iniciar Sesión';
$CIDRAM['lang']['field_new_name'] = 'Nuevo nombre:';
$CIDRAM['lang']['field_nonclickable_text'] = 'Texto que no se puede hacer clic';
$CIDRAM['lang']['field_ok'] = 'OK';
$CIDRAM['lang']['field_options'] = 'Opciones';
$CIDRAM['lang']['field_password'] = 'Contraseña';
$CIDRAM['lang']['field_permissions'] = 'Permisos';
$CIDRAM['lang']['field_range'] = 'Alcance (Primero – Final)';
$CIDRAM['lang']['field_rename_file'] = 'Cambiar el nombre';
$CIDRAM['lang']['field_reset'] = 'Reiniciar';
$CIDRAM['lang']['field_set_new_password'] = 'Crear Nueva Contraseña';
$CIDRAM['lang']['field_size'] = 'Tamaño Total: ';
$CIDRAM['lang']['field_size_bytes'] = ['byte', 'bytes'];
$CIDRAM['lang']['field_size_GB'] = 'GB';
$CIDRAM['lang']['field_size_KB'] = 'KB';
$CIDRAM['lang']['field_size_MB'] = 'MB';
$CIDRAM['lang']['field_size_TB'] = 'TB';
$CIDRAM['lang']['field_status'] = 'Estado';
$CIDRAM['lang']['field_system_timezone'] = 'Usar la zona horaria predeterminada del sistema.';
$CIDRAM['lang']['field_tracking'] = 'Seguimiento';
$CIDRAM['lang']['field_true'] = 'True (Verdadero)';
$CIDRAM['lang']['field_uninstall'] = 'Desinstalar';
$CIDRAM['lang']['field_update'] = 'Actualizar';
$CIDRAM['lang']['field_update_all'] = 'Actualizar todo';
$CIDRAM['lang']['field_upload_file'] = 'Subir un nuevo archivo';
$CIDRAM['lang']['field_username'] = 'Usuario';
$CIDRAM['lang']['field_your_version'] = 'Tu Versión';
$CIDRAM['lang']['header_login'] = 'Por favor iniciar sesión para continuar.';
$CIDRAM['lang']['label_active_config_file'] = 'Archivo de configuración activo: ';
$CIDRAM['lang']['label_banned'] = 'Solicitudes prohibidas';
$CIDRAM['lang']['label_blocked'] = 'Solicitudes bloqueadas';
$CIDRAM['lang']['label_branch'] = 'Branch más nuevo estable:';
$CIDRAM['lang']['label_cidram'] = 'Versión CIDRAM utilizada:';
$CIDRAM['lang']['label_false_positive_risk'] = 'Riesgo de falsos positivos: ';
$CIDRAM['lang']['label_fmgr_cache_data'] = 'Datos de caché y archivos temporales';
$CIDRAM['lang']['label_fmgr_disk_usage'] = 'Uso del disco por CIDRAM: ';
$CIDRAM['lang']['label_fmgr_free_space'] = 'Espacio en disco libre: ';
$CIDRAM['lang']['label_fmgr_total_disk_usage'] = 'Uso del disco total: ';
$CIDRAM['lang']['label_fmgr_total_space'] = 'Espacio en disco total: ';
$CIDRAM['lang']['label_fmgr_updates_metadata'] = 'Componente actualiza metadatos';
$CIDRAM['lang']['label_hide'] = 'Esconder';
$CIDRAM['lang']['label_os'] = 'Sistema operativo utilizada:';
$CIDRAM['lang']['label_other'] = 'Otro';
$CIDRAM['lang']['label_other-ActiveIPv4'] = 'Archivos de firmas IPv4 activas';
$CIDRAM['lang']['label_other-ActiveIPv6'] = 'Archivos de firmas IPv6 activas';
$CIDRAM['lang']['label_other-ActiveModules'] = 'Módulos activos';
$CIDRAM['lang']['label_other-Since'] = 'Fecha de inicio';
$CIDRAM['lang']['label_php'] = 'Versión PHP utilizada:';
$CIDRAM['lang']['label_reCAPTCHA'] = 'Intentos de reCAPTCHA';
$CIDRAM['lang']['label_results'] = 'Resultados (%s en – %s rechazado – %s aceptado – %s fusionado – %s fuera):';
$CIDRAM['lang']['label_sapi'] = 'SAPI utilizada:';
$CIDRAM['lang']['label_show'] = 'Mostrar';
$CIDRAM['lang']['label_stable'] = 'Más nuevo estable:';
$CIDRAM['lang']['label_sysinfo'] = 'Información del sistema:';
$CIDRAM['lang']['label_total'] = 'Total';
$CIDRAM['lang']['label_unstable'] = 'Más nuevo inestable:';
$CIDRAM['lang']['link_accounts'] = 'Cuentas';
$CIDRAM['lang']['link_cidr_calc'] = 'Calculadora CIDR';
$CIDRAM['lang']['link_config'] = 'Configuración';
$CIDRAM['lang']['link_documentation'] = 'Documentación';
$CIDRAM['lang']['link_file_manager'] = 'Administración de Archivos';
$CIDRAM['lang']['link_home'] = 'Página Principal';
$CIDRAM['lang']['link_ip_aggregator'] = 'Agregador IP';
$CIDRAM['lang']['link_ip_test'] = 'Prueba IP';
$CIDRAM['lang']['link_ip_tracking'] = 'Seguimiento de IP';
$CIDRAM['lang']['link_logs'] = 'Archivos de Registro';
$CIDRAM['lang']['link_statistics'] = 'Estadística';
$CIDRAM['lang']['link_textmode'] = 'Formato de texto: <a href="%1$sfalse">Simple</a> – <a href="%1$strue">Lujoso</a>';
$CIDRAM['lang']['link_updates'] = 'Actualizaciones';
$CIDRAM['lang']['logs_logfile_doesnt_exist'] = '¡Archivo de registro seleccionado no existe!';
$CIDRAM['lang']['logs_no_logfiles_available'] = 'Ningún archivos de registro disponibles.';
$CIDRAM['lang']['logs_no_logfile_selected'] = 'Ningún archivo de registro seleccionado.';
$CIDRAM['lang']['max_login_attempts_exceeded'] = 'Número máximo de intentos de login excedido; Acceso denegado.';
$CIDRAM['lang']['Name: Bypasses'] = 'Bypasses de firmas estándar.';
$CIDRAM['lang']['Name: IPv4'] = 'Archivo de firmas por IPv4 (servicios en la nube no deseados y puntos finales no humanos).';
$CIDRAM['lang']['Name: IPv4-Bogons'] = 'Archivo de firmas por IPv4 (bogon/marciano CIDRs).';
$CIDRAM['lang']['Name: IPv4-ISPs'] = 'Archivo de firmas por IPv4 (ISPs peligroso y propenso a spam).';
$CIDRAM['lang']['Name: IPv4-Other'] = 'Archivo de firmas por IPv4 (CIDRs para proxies, VPNs y otros servicios misceláneos no deseados).';
$CIDRAM['lang']['Name: IPv6'] = 'Archivo de firmas por IPv6 (servicios en la nube no deseados y puntos finales no humanos).';
$CIDRAM['lang']['Name: IPv6-Bogons'] = 'Archivo de firmas por IPv6 (bogon/marciano CIDRs).';
$CIDRAM['lang']['Name: IPv6-ISPs'] = 'Archivo de firmas por IPv6 (ISPs peligroso y propenso a spam).';
$CIDRAM['lang']['Name: IPv6-Other'] = 'Archivo de firmas por IPv6 (CIDRs para proxies, VPNs y otros servicios misceláneos no deseados).';
$CIDRAM['lang']['previewer_days'] = 'Días';
$CIDRAM['lang']['previewer_hours'] = 'Horas';
$CIDRAM['lang']['previewer_minutes'] = 'Minutos';
$CIDRAM['lang']['previewer_months'] = 'Meses';
$CIDRAM['lang']['previewer_seconds'] = 'Segundos';
$CIDRAM['lang']['previewer_weeks'] = 'Semanas';
$CIDRAM['lang']['previewer_years'] = 'Años';
$CIDRAM['lang']['response_accounts_already_exists'] = '¡Una cuenta con ese nombre ya existe!';
$CIDRAM['lang']['response_accounts_created'] = '¡Cuenta creada con éxito!';
$CIDRAM['lang']['response_accounts_deleted'] = '¡Cuenta eliminada con éxito!';
$CIDRAM['lang']['response_accounts_doesnt_exist'] = 'Esa cuenta no existe.';
$CIDRAM['lang']['response_accounts_password_updated'] = 'Contraseña actualizado con éxito!';
$CIDRAM['lang']['response_activated'] = 'Se ha activado correctamente.';
$CIDRAM['lang']['response_activation_failed'] = '¡No se pudo activar!';
$CIDRAM['lang']['response_checksum_error'] = 'Error de suma de comprobación! Archivo rechazado!';
$CIDRAM['lang']['response_component_successfully_installed'] = 'Componente instalado con éxito.';
$CIDRAM['lang']['response_component_successfully_uninstalled'] = 'Componente desinstalado con éxito.';
$CIDRAM['lang']['response_component_successfully_updated'] = 'Componente actualizado con éxito.';
$CIDRAM['lang']['response_component_uninstall_error'] = 'Se ha producido un error al intentar desinstalar el componente.';
$CIDRAM['lang']['response_configuration_updated'] = 'Configuración actualizado con éxito.';
$CIDRAM['lang']['response_deactivated'] = 'Se ha desactivado correctamente.';
$CIDRAM['lang']['response_deactivation_failed'] = '¡No se pudo desactivar!';
$CIDRAM['lang']['response_delete_error'] = '¡No se pudo eliminar!';
$CIDRAM['lang']['response_directory_deleted'] = '¡Directorio eliminado con éxito!';
$CIDRAM['lang']['response_directory_renamed'] = '¡El nombre del directorio cambiado con éxito!';
$CIDRAM['lang']['response_error'] = 'Error';
$CIDRAM['lang']['response_failed_to_install'] = '¡No se pudo instalar!';
$CIDRAM['lang']['response_failed_to_update'] = '¡No se pudo actualizar!';
$CIDRAM['lang']['response_file_deleted'] = '¡Archivo eliminado con éxito!';
$CIDRAM['lang']['response_file_edited'] = '¡Archivo modificado con éxito!';
$CIDRAM['lang']['response_file_renamed'] = '¡El nombre del archivo cambiado con éxito!';
$CIDRAM['lang']['response_file_uploaded'] = '¡Archivo subido con éxito!';
$CIDRAM['lang']['response_login_invalid_password'] = '¡Error al iniciar sesión – Contraseña invalida!';
$CIDRAM['lang']['response_login_invalid_username'] = '¡Error al iniciar sesión – El usuario no existe!';
$CIDRAM['lang']['response_login_password_field_empty'] = '¡La entrada de contraseña estaba vacío!';
$CIDRAM['lang']['response_login_username_field_empty'] = '¡La entrada de usuario estaba vacío!';
$CIDRAM['lang']['response_no'] = 'No';
$CIDRAM['lang']['response_rename_error'] = '¡No se pudo cambiar el nombre!';
$CIDRAM['lang']['response_statistics_cleared'] = 'Estadística anulado.';
$CIDRAM['lang']['response_tracking_cleared'] = 'Seguimiento anulado.';
$CIDRAM['lang']['response_updates_already_up_to_date'] = 'Ya está actualizado.';
$CIDRAM['lang']['response_updates_not_installed'] = '¡El componente no se instala!';
$CIDRAM['lang']['response_updates_not_installed_php'] = '¡El componente no se instala (requiere PHP {V})!';
$CIDRAM['lang']['response_updates_outdated'] = '¡Anticuado!';
$CIDRAM['lang']['response_updates_outdated_manually'] = '¡Anticuado (por favor, actualizar manualmente)!';
$CIDRAM['lang']['response_updates_outdated_php_version'] = '¡Anticuado (requiere PHP {V})!';
$CIDRAM['lang']['response_updates_unable_to_determine'] = 'Incapaz de determinar.';
$CIDRAM['lang']['response_upload_error'] = '¡No se pudo subir!';
$CIDRAM['lang']['response_yes'] = 'Sí';
$CIDRAM['lang']['state_complete_access'] = 'Acceso completo';
$CIDRAM['lang']['state_component_is_active'] = 'Componente está activo.';
$CIDRAM['lang']['state_component_is_inactive'] = 'Componente está inactivo.';
$CIDRAM['lang']['state_component_is_provisional'] = 'Componente está provisional.';
$CIDRAM['lang']['state_default_password'] = '¡Advertencia: Usando la contraseña estándar!';
$CIDRAM['lang']['state_loadtime'] = 'Solicitud de página completada en <span class="txtRd">%s</span> segundos.';
$CIDRAM['lang']['state_logged_in'] = 'Conectado.';
$CIDRAM['lang']['state_logs_access_only'] = 'Acceso de registros solamente';
$CIDRAM['lang']['state_maintenance_mode'] = 'Advertencia: ¡El modo de mantenimiento está activado!';
$CIDRAM['lang']['state_password_not_valid'] = '¡Advertencia: Esta cuenta no está utilizando una contraseña válida!';
$CIDRAM['lang']['state_risk_high'] = 'Alto';
$CIDRAM['lang']['state_risk_low'] = 'Bajo';
$CIDRAM['lang']['state_risk_medium'] = 'Medio';
$CIDRAM['lang']['state_tracking'] = ['Actualmente siguiendo %s IP.', 'Actualmente siguiendo %s IPs.'];
$CIDRAM['lang']['switch-hide-non-outdated-set-false'] = 'No ocultar no anticuado';
$CIDRAM['lang']['switch-hide-non-outdated-set-true'] = 'Ocultar no anticuado';
$CIDRAM['lang']['switch-hide-unused-set-false'] = 'No ocultar no utilizado';
$CIDRAM['lang']['switch-hide-unused-set-true'] = 'Ocultar no utilizado';
$CIDRAM['lang']['switch-tracking-blocked-already-set-false'] = 'No comparar con los archivos de firmas';
$CIDRAM['lang']['switch-tracking-blocked-already-set-true'] = 'Comparar con los archivos de firmas';
$CIDRAM['lang']['switch-tracking-hide-banned-blocked-set-false'] = 'No ocultar IPs prohibidos/bloqueados';
$CIDRAM['lang']['switch-tracking-hide-banned-blocked-set-true'] = 'Ocultar IPs prohibidos/bloqueados';
$CIDRAM['lang']['tip_accounts'] = 'Hola, {username}.<br />La página de cuentas permite controlar controlar quién puede acceder al CIDRAM front-end.';
$CIDRAM['lang']['tip_cidr_calc'] = 'Hola, {username}.<br />La calculadora CIDR le permite calcular los CIDR a los que una dirección IP es un factor.';
$CIDRAM['lang']['tip_config'] = 'Hola, {username}.<br />La página de configuración permite modificar la configuración para CIDRAM desde el front-end.';
$CIDRAM['lang']['tip_donate'] = 'CIDRAM se ofrece de forma gratuita, pero si quieres donar al proyecto, puede hacerlo haciendo clic en el botón donar.';
$CIDRAM['lang']['tip_enter_ips_here'] = 'Ingrese IPs aquí.';
$CIDRAM['lang']['tip_enter_ip_here'] = 'Ingrese un IP aquí.';
$CIDRAM['lang']['tip_file_manager'] = 'Hola, {username}.<br />El administración de archivos permite eliminar, editar, subir y descargar de archivos. Utilizar con precaución (podría romper su instalación con esto).';
$CIDRAM['lang']['tip_home'] = 'Hola, {username}.<br />Esta es la página principal para el front-end de CIDRAM. Seleccione un enlace en el menú de navegación de la izquierda para continuar.';
$CIDRAM['lang']['tip_ip_aggregator'] = 'Hola, {username}.<br />El agregador IP le permite expresar IPs y CIDRs en la forma más pequeña posible. Introduzca los datos que desea agregar y pulse "OK".';
$CIDRAM['lang']['tip_ip_test'] = 'Hola, {username}.<br />La página para prueba IP permite pruebar si las direcciones IP están bloqueadas por las firmas actualmente instaladas.';
$CIDRAM['lang']['tip_ip_tracking'] = 'Hola, {username}.<br />La página de seguimiento de IP le permite comprobar el estado de seguimiento de las direcciones IP, para comprobar cuál de ellos ha sido prohibido, y anular el seguimiento de ellos si desea hacerlo.';
$CIDRAM['lang']['tip_login'] = 'El usuario estándar: <span class="txtRd">admin</span> – La contraseña estándar: <span class="txtRd">password</span>';
$CIDRAM['lang']['tip_logs'] = 'Hola, {username}.<br />Seleccionar un archivo de registro de la lista siguiente para ver el contenido de ese archivo de registro.';
$CIDRAM['lang']['tip_see_the_documentation'] = 'Ver la <a href="https://github.com/CIDRAM/CIDRAM/blob/master/_docs/readme.es.md#SECTION6">documentación</a> para obtener información sobre las distintas directivas de la configuración y sus propósitos.';
$CIDRAM['lang']['tip_statistics'] = 'Hola, {username}.<br />Esta página muestra algunas estadísticas de uso básicas relacionadas con la instalación de CIDRAM.';
$CIDRAM['lang']['tip_statistics_disabled'] = 'Nota: El seguimiento de estadísticas está actualmente deshabilitado, pero se puede habilitar a través de la página de configuración.';
$CIDRAM['lang']['tip_updates'] = 'Hola, {username}.<br />La página de actualizaciones permite instalar, desinstalar y actualizar los diversos componentes de CIDRAM (el paquete básico, firmas, archivos de L10N, etc).';
$CIDRAM['lang']['title_accounts'] = 'CIDRAM – Cuentas';
$CIDRAM['lang']['title_cidr_calc'] = 'CIDRAM – Calculadora CIDR';
$CIDRAM['lang']['title_config'] = 'CIDRAM – Configuración';
$CIDRAM['lang']['title_file_manager'] = 'CIDRAM – Administración de Archivos';
$CIDRAM['lang']['title_home'] = 'CIDRAM – Página Principal';
$CIDRAM['lang']['title_ip_aggregator'] = 'CIDRAM – Agregador IP';
$CIDRAM['lang']['title_ip_test'] = 'CIDRAM – Prueba IP';
$CIDRAM['lang']['title_ip_tracking'] = 'CIDRAM – Seguimiento de IP';
$CIDRAM['lang']['title_login'] = 'CIDRAM – Login';
$CIDRAM['lang']['title_logs'] = 'CIDRAM – Archivos de Registro';
$CIDRAM['lang']['title_statistics'] = 'CIDRAM – Estadística';
$CIDRAM['lang']['title_updates'] = 'CIDRAM – Actualizaciones';
$CIDRAM['lang']['warning'] = 'Advertencias:';
$CIDRAM['lang']['warning_php_1'] = '¡Su versión de PHP no es apoyado activamente más! Se recomienda actualizar!';
$CIDRAM['lang']['warning_php_2'] = '¡Su versión de PHP es muy vulnerable! Se recomienda encarecidamente actualizar!';
$CIDRAM['lang']['warning_signatures_1'] = '¡No hay archivos de firma activos!';

$CIDRAM['lang']['info_some_useful_links'] = 'Algunos enlaces útiles:<ul>
            <li><a href="https://github.com/CIDRAM/CIDRAM/issues">Problemas de CIDRAM @ GitHub</a> – Página de problemas para CIDRAM (apoyo, asistencia, etc).</li>
            <li><a href="http://www.spambotsecurity.com/forum/viewforum.php?f=61">CIDRAM @ Spambot Security</a> – Foro de discusión para CIDRAM (apoyo, asistencia, etc).</li>
            <li><a href="https://wordpress.org/plugins/cidram/">CIDRAM @ WordPress.org</a> – WordPress plugin para CIDRAM.</li>
            <li><a href="https://sourceforge.net/projects/cidram/">CIDRAM @ SourceForge</a> – Alternative download mirror for CIDRAM.</li>
            <li><a href="https://websectools.com/">WebSecTools.com</a> – Una colección de sencillas herramientas de webmaster para proteger sitios web.</li>
            <li><a href="https://macmathan.info/blocklists">Bloques de rangos @MacMathan.info</a> – Contiene bloques de rangos opcionales que se pueden agregar a CIDRAM para bloquear cualquier país no deseado de acceder a su sitio web.</li>
            <li><a href="https://www.facebook.com/groups/2204685680/">Global PHP Group @ Facebook</a> – PHP recursos de aprendizaje y discusión.</li>
            <li><a href="https://php.earth/">PHP.earth</a> – PHP recursos de aprendizaje y discusión.</li>
            <li><a href="http://bgp.he.net/">Hurricane Electric BGP Toolkit</a> – Obtener CIDRs de ASNs, Determinar las relaciones de las ASNs, Descubre los ASNs basados en nombres de redes, etc.</li>
            <li><a href="https://www.stopforumspam.com/forum/">Forum @ Stop Forum Spam</a> – Útil foro de discusión sobre detener spam de foro.</li>
            <li><a href="https://radar.qrator.net/">Radar por Qrator</a> – Útil herramienta para comprobar la conectividad de los ASNs así como para otras informaciones sobre los ASNs.</li>
            <li><a href="http://www.ipdeny.com/ipblocks/">Bloques de países @ IPdeny IP</a> – Un servicio fantástico y preciso para generar firmas a nivel nacional.</li>
            <li><a href="https://www.google.com/transparencyreport/safebrowsing/malware/">Google Malware Dashboard</a> – Muestra informes sobre las tasas de infección de malware para los ASNs.</li>
            <li><a href="https://www.spamhaus.org/statistics/botnet-asn/">El Proyecto Spamhaus</a> – Muestra informes sobre las tasas de infección por botnets para los ASNs.</li>
            <li><a href="https://www.abuseat.org/public/asn.html">Lista de bloqueo compuesto @ Abuseat.org</a> – Muestra informes sobre las tasas de infección por botnets para los ASNs.</li>
            <li><a href="https://abuseipdb.com/">AbuseIPDB</a> – Mantiene una base de datos de IPs abusivos conocidos; Proporciona una API para comprobar e informar IPs.</li>
            <li><a href="https://www.megarbl.net/index.php">MegaRBL.net</a> – Mantiene listados de spammers conocidos; Útil para comprobar las actividades de spam por IP/ASN.</li>
            <li><a href="https://maikuolan.github.io/Vulnerability-Charts/">Listas de vulnerabilidades</a> – Lista las versiones seguras/inseguras de varios paquetes (PHP, HHVM, etc).</li>
            <li><a href="https://maikuolan.github.io/Compatibility-Charts/">Listas de compatibilidades</a> – Lista información de compatibilidad para varios paquetes (CIDRAM, phpMussel, etc).</li>
        </ul>';
