<?php
/**
 * This file is a part of the CIDRAM package, and can be downloaded for free
 * from {@link https://github.com/Maikuolan/CIDRAM/ GitHub}.
 *
 * CIDRAM COPYRIGHT 2016 and beyond by Caleb Mazalevskis (Maikuolan).
 *
 * License: GNU/GPLv2
 * @see LICENSE.txt
 *
 * This file: Spanish language data for the front-end (last modified: 2016.11.18).
 */

/** Prevents execution from outside of CIDRAM. */
if (!defined('CIDRAM')) {
    die('[CIDRAM] This should not be accessed directly.');
}

$CIDRAM['lang']['bNav_home_logout'] = '<a href="?">Página Principal</a> | <a href="?cidram-page=logout">Cerrar Sesión</a>';
$CIDRAM['lang']['bNav_logout'] = '<a href="?cidram-page=logout">Cerrar Sesión</a>';
$CIDRAM['lang']['config_general_disable_cli'] = 'Desactivar CLI modo?';
$CIDRAM['lang']['config_general_disable_frontend'] = 'Desactivar el acceso front-end?';
$CIDRAM['lang']['config_general_emailaddr'] = 'Dirección email para soporte.';
$CIDRAM['lang']['config_general_forbid_on_block'] = 'Cual cabeceras debe CIDRAM responder con cuando bloquear acceso?';
$CIDRAM['lang']['config_general_ipaddr'] = 'Dónde encontrar el IP dirección de la conectando request?';
$CIDRAM['lang']['config_general_lang'] = 'Especifique la predefinido del lenguaje para CIDRAM.';
$CIDRAM['lang']['config_general_logfile'] = 'Un archivo legible por humanos para el registro de todos los intentos de acceso bloqueados. Especificar el nombre del archivo, o dejar en blanco para desactivar.';
$CIDRAM['lang']['config_general_logfileApache'] = 'Un archivo en el estilo de Apache para el registro de todos los intentos de acceso bloqueados. Especificar el nombre del archivo, o dejar en blanco para desactivar.';
$CIDRAM['lang']['config_general_logfileSerialized'] = 'Un archivo serializado para el registro de todos los intentos de acceso bloqueados. Especificar el nombre del archivo, o dejar en blanco para desactivar.';
$CIDRAM['lang']['config_general_silent_mode'] = 'Debería CIDRAM silencio redirigir los intentos de acceso bloqueados en lugar de mostrar la página "Acceso Denegado"? En caso afirmativo, especifique la ubicación para redirigir los intentos de acceso bloqueados. Si no, dejar esta variable en blanco.';
$CIDRAM['lang']['config_general_timeOffset'] = 'Desplazamiento del huso horario en minutos.';
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
$CIDRAM['lang']['config_signatures_ipv4'] = 'Una lista de los archivos de firmas IPv4 que CIDRAM debe tratar de utilizar, delimitado por comas.';
$CIDRAM['lang']['config_signatures_ipv6'] = 'Una lista de los archivos de firmas IPv6 que CIDRAM debe tratar de utilizar, delimitado por comas.';
$CIDRAM['lang']['config_template_data_css_url'] = 'URL del archivo CSS para temas personalizados.';
$CIDRAM['lang']['field_blocked'] = 'Bloqueado';
$CIDRAM['lang']['field_component'] = 'Componente';
$CIDRAM['lang']['field_create_new_account'] = 'Crear Nueva Cuenta';
$CIDRAM['lang']['field_delete_account'] = 'Eliminar Cuenta';
$CIDRAM['lang']['field_filename'] = 'Nombre del archivo: ';
$CIDRAM['lang']['field_install'] = 'Instalar';
$CIDRAM['lang']['field_ip_address'] = 'Dirección IP';
$CIDRAM['lang']['field_latest_version'] = 'Ultima Versión';
$CIDRAM['lang']['field_log_in'] = 'Iniciar Sesión';
$CIDRAM['lang']['field_ok'] = 'OK';
$CIDRAM['lang']['field_options'] = 'Opciones';
$CIDRAM['lang']['field_password'] = 'Contraseña';
$CIDRAM['lang']['field_permissions'] = 'Permisos';
$CIDRAM['lang']['field_set_new_password'] = 'Crear Nueva Contraseña';
$CIDRAM['lang']['field_size'] = 'Tamaño Total: ';
$CIDRAM['lang']['field_size_bytes'] = 'bytes';
$CIDRAM['lang']['field_size_GB'] = 'GB';
$CIDRAM['lang']['field_size_KB'] = 'KB';
$CIDRAM['lang']['field_size_MB'] = 'MB';
$CIDRAM['lang']['field_size_TB'] = 'TB';
$CIDRAM['lang']['field_status'] = 'Estado';
$CIDRAM['lang']['field_uninstall'] = 'Desinstalar';
$CIDRAM['lang']['field_update'] = 'Actualizar';
$CIDRAM['lang']['field_username'] = 'Usuario';
$CIDRAM['lang']['field_your_version'] = 'Tu Versión';
$CIDRAM['lang']['header_login'] = 'Por favor iniciar sesión para continuar.';
$CIDRAM['lang']['link_accounts'] = 'Cuentas';
$CIDRAM['lang']['link_config'] = 'Configuración';
$CIDRAM['lang']['link_documentation'] = 'Documentación';
$CIDRAM['lang']['link_home'] = 'Página Principal';
$CIDRAM['lang']['link_ip_test'] = 'Prueba IP';
$CIDRAM['lang']['link_logs'] = 'Archivos de Registro';
$CIDRAM['lang']['link_updates'] = 'Actualizaciones';
$CIDRAM['lang']['logs_logfile_doesnt_exist'] = '¡Archivo de registro seleccionado no existe!';
$CIDRAM['lang']['logs_no_logfiles_available'] = 'Ningún archivos de registro disponibles.';
$CIDRAM['lang']['logs_no_logfile_selected'] = 'Ningún archivo de registro seleccionado.';
$CIDRAM['lang']['response_accounts_already_exists'] = '¡Una cuenta con ese nombre ya existe!';
$CIDRAM['lang']['response_accounts_created'] = '¡Cuenta creada con éxito!';
$CIDRAM['lang']['response_accounts_deleted'] = '¡Cuenta eliminada con éxito!';
$CIDRAM['lang']['response_accounts_doesnt_exist'] = 'Esa cuenta no existe.';
$CIDRAM['lang']['response_accounts_password_updated'] = 'Contraseña actualizado con éxito!';
$CIDRAM['lang']['response_component_successfully_installed'] = 'Componente instalado con éxito.';
$CIDRAM['lang']['response_component_successfully_uninstalled'] = 'Componente desinstalado con éxito.';
$CIDRAM['lang']['response_component_successfully_updated'] = 'Componente actualizado con éxito.';
$CIDRAM['lang']['response_component_uninstall_error'] = 'Se ha producido un error al intentar desinstalar el componente.';
$CIDRAM['lang']['response_component_update_error'] = 'Se ha producido un error al intentar actualizar el componente.';
$CIDRAM['lang']['response_configuration_updated'] = 'Configuración actualizado con éxito.';
$CIDRAM['lang']['response_error'] = 'Error';
$CIDRAM['lang']['response_login_invalid_password'] = '¡Error al iniciar sesión - Contraseña invalida!';
$CIDRAM['lang']['response_login_invalid_username'] = '¡Error al iniciar sesión - El usuario no existe!';
$CIDRAM['lang']['response_login_password_field_empty'] = '¡La entrada de contraseña estaba vacío!';
$CIDRAM['lang']['response_login_username_field_empty'] = '¡La entrada de usuario estaba vacío!';
$CIDRAM['lang']['response_no'] = 'No';
$CIDRAM['lang']['response_updates_already_up_to_date'] = 'Ya está actualizado.';
$CIDRAM['lang']['response_updates_not_installed'] = '¡El componente no se instala!';
$CIDRAM['lang']['response_updates_outdated'] = '¡Anticuado!';
$CIDRAM['lang']['response_updates_outdated_manually'] = '¡Anticuado (por favor, actualizar manualmente)!';
$CIDRAM['lang']['response_updates_unable_to_determine'] = 'Incapaz de determinar.';
$CIDRAM['lang']['response_yes'] = 'Sí';
$CIDRAM['lang']['state_complete_access'] = 'Acceso completo';
$CIDRAM['lang']['state_component_is_active'] = 'Componente está activo.';
$CIDRAM['lang']['state_component_is_inactive'] = 'Componente está inactivo.';
$CIDRAM['lang']['state_component_is_provisional'] = 'Componente está provisional.';
$CIDRAM['lang']['state_default_password'] = '¡Advertencia: Usando la contraseña estándar!';
$CIDRAM['lang']['state_logged_in'] = 'Conectado';
$CIDRAM['lang']['state_logs_access_only'] = 'Acceso de registros solamente';
$CIDRAM['lang']['state_password_not_valid'] = '¡Advertencia: Esta cuenta no está utilizando una contraseña válida!';
$CIDRAM['lang']['switch-hide-non-outdated-set-false'] = 'No ocultar no anticuado';
$CIDRAM['lang']['switch-hide-non-outdated-set-true'] = 'Ocultar no anticuado';
$CIDRAM['lang']['switch-hide-unused-set-false'] = 'No ocultar no utilizado';
$CIDRAM['lang']['switch-hide-unused-set-true'] = 'Ocultar no utilizado';
$CIDRAM['lang']['tip_accounts'] = 'Hola, {username}.<br />La página de cuentas permite controlar controlar quién puede acceder al CIDRAM front-end.';
$CIDRAM['lang']['tip_config'] = 'Hola, {username}.<br />La página de configuración permite modificar la configuración para CIDRAM desde el front-end.';
$CIDRAM['lang']['tip_enter_ips_here'] = 'Ingrese IPs aquí.';
$CIDRAM['lang']['tip_home'] = 'Hola, {username}.<br />Esta es la página principal para el front-end de CIDRAM. Seleccione un enlace en el menú de navegación de la izquierda para continuar.';
$CIDRAM['lang']['tip_ip_test'] = 'Hola, {username}.<br />La página para prueba IP permite pruebar si las direcciones IP están bloqueadas por las firmas actualmente instaladas.';
$CIDRAM['lang']['tip_login'] = 'El usuario estándar: <span class="txtRd">admin</span> – La contraseña estándar: <span class="txtRd">password</span>';
$CIDRAM['lang']['tip_logs'] = 'Hola, {username}.<br />Seleccionar un archivo de registro de la lista siguiente para ver el contenido de ese archivo de registro.';
$CIDRAM['lang']['tip_see_the_documentation'] = 'Ver la <a href="https://github.com/Maikuolan/CIDRAM/blob/master/_docs/readme.es.md#SECTION6">documentación</a> para obtener información sobre las distintas directivas de la configuración y sus propósitos.';
$CIDRAM['lang']['tip_updates'] = 'Hola, {username}.<br />La página de actualizaciones permite instalar, desinstalar y actualizar los diversos componentes de CIDRAM (el paquete básico, firmas, archivos de L10N, etc).';
$CIDRAM['lang']['title_accounts'] = 'CIDRAM – Cuentas';
$CIDRAM['lang']['title_config'] = 'CIDRAM – Configuración';
$CIDRAM['lang']['title_home'] = 'CIDRAM – Página Principal';
$CIDRAM['lang']['title_ip_test'] = 'CIDRAM – Prueba IP';
$CIDRAM['lang']['title_login'] = 'CIDRAM – Login';
$CIDRAM['lang']['title_logs'] = 'CIDRAM – Archivos de Registro';
$CIDRAM['lang']['title_updates'] = 'CIDRAM – Actualizaciones';
