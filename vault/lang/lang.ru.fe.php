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
 * This file: Russian language data for the front-end (last modified: 2018.02.05).
 */

/** Prevents execution from outside of CIDRAM. */
if (!defined('CIDRAM')) {
    die('[CIDRAM] This should not be accessed directly.');
}

$CIDRAM['lang']['Extended Description: Bypasses'] = 'Стандартная сигнатуры байпасы, обычно включены в основной пакет.';
$CIDRAM['lang']['Extended Description: CIDRAM'] = 'Основной пакет (минус сигнатуры, документация, и конфигурация).';
$CIDRAM['lang']['Name: Bypasses'] = 'Стандартная сигнатуры байпасы.';
$CIDRAM['lang']['Name: IPv4'] = 'IPv4 файла сигнатур (нежелательные облачные сервисы и нечеловеческие конечные точки).';
$CIDRAM['lang']['Name: IPv4-Bogons'] = 'IPv4 файла сигнатур (bogon/марсианин CIDRs).';
$CIDRAM['lang']['Name: IPv4-ISPs'] = 'IPv4 файла сигнатур (опасно и спам-склонным интернет-провайдеры).';
$CIDRAM['lang']['Name: IPv4-Other'] = 'IPv4 файла сигнатур (CIDRs для прокси-серверов, виртуальных частных сетей и различных других нежелательных услуг).';
$CIDRAM['lang']['Name: IPv6'] = 'IPv6 файла сигнатур (нежелательные облачные сервисы и нечеловеческие конечные точки).';
$CIDRAM['lang']['Name: IPv6-Bogons'] = 'IPv6 файла сигнатур (bogon/марсианин CIDRs).';
$CIDRAM['lang']['Name: IPv6-ISPs'] = 'IPv6 файла сигнатур (опасно и спам-склонным интернет-провайдеры).';
$CIDRAM['lang']['Name: IPv6-Other'] = 'IPv6 файла сигнатур (CIDRs для прокси-серверов, виртуальных частных сетей и различных других нежелательных услуг).';
$CIDRAM['lang']['bNav_home_logout'] = '<a href="?">Домашняя Страница</a> | <a href="?cidram-page=logout">Выйдите</a>';
$CIDRAM['lang']['bNav_logout'] = '<a href="?cidram-page=logout">Выйдите</a>';
$CIDRAM['lang']['config_general_FrontEndLog'] = 'Файл для запись всех попыток входа в фронтенд. Задайте имя файлу, или оставьте пустым чтобы деактивировать опцию.';
$CIDRAM['lang']['config_general_allow_gethostbyaddr_lookup'] = 'Разрешить поиск gethostbyaddr, когда UDP недоступен? True = Да [Стандарт]; False = Нет.';
$CIDRAM['lang']['config_general_ban_override'] = 'Переопределить «forbid_on_block» когда «infraction_limit» превысило? Когда переопределении: Блокированные запросы возвращают пустую страницу (файлы шаблонов не используются). 200 = Не переопределить [Стандарт]; 403 = Переопределить с «403 Forbidden»; 503 = Переопределить с «503 Service unavailable».';
$CIDRAM['lang']['config_general_default_algo'] = 'Определяет, какой алгоритм использовать для всех будущих паролей и сеансов. Опции: PASSWORD_DEFAULT (стандарт), PASSWORD_BCRYPT, PASSWORD_ARGON2I (требует PHP >= 7.2.0).';
$CIDRAM['lang']['config_general_default_dns'] = 'Разделенный запятыми список DNS-серверов, чтобы использовать для имен хостов поиска. Стандарт = «8.8.8.8,8.8.4.4» (Google DNS). ВНИМАНИЕ: Изменяйте это значение только в том случае, если Вы уверены в своих действиях!';
$CIDRAM['lang']['config_general_disable_cli'] = 'Деактивировать ли CLI-модус? Обычно CLI-модус активирован. Однако иногда он может отрицательно влиять на определённые тестовые программы (например на PHPUnit) и другие приложения, базирующиеся на CLI. Если CLI-модус нельзя деактивировать, то эту команду нужно игнорировать. False = CLI-модус активирован [Стандарт]; True = CLI-модус деактивирован.';
$CIDRAM['lang']['config_general_disable_frontend'] = 'Деактивировать доступ к фронтенд? Доступ к фронтенд может сделать CIDRAM более управляемым, но также может быть потенциальный риск безопасности. Рекомендуется чтобы управлять CIDRAM через back-end когда возможно, но доступ к фронтенд предоставлен для того когда это не возможно. Держите его деактивирована за исключением того если вам это нужно. False = Активировать доступ к фронтенд; True = Деактивировать доступ к фронтенд [Стандарт].';
$CIDRAM['lang']['config_general_disable_webfonts'] = 'Отключить веб-шрифты? True = Да; False = Нет [Стандарт].';
$CIDRAM['lang']['config_general_emailaddr'] = 'Если Вы хотите, Вы можете предоставить адрес электронной почты здесь чтобы дать пользователям, когда они заблокированы, для их использования в качестве точки контакта за поддержку и/или помощь ибо в случае их блокирования по ошибке. ПРЕДУПРЕЖДЕНИЕ: Любой адрес электронной почты Вы предоставляете здесь наверняка будет найдены по спамботами и скребки во время используется здесь, и так, это настоятельно рекомендуется, если Вы решите добавить адрес электронной почты здесь, что Вы убедитесь что адрес электронной почты что Вы предоставляете здесь одноразовый адрес и/или адрес который Вы не против чтобы быть спамом (другими словами, Вы вероятно не хотите использовать ваши основные личные электронной почты или первичные бизнес электронной почты).';
$CIDRAM['lang']['config_general_emailaddr_display_style'] = 'Как Вы предпочитаете, чтобы адрес электронной почты был представлен пользователям?';
$CIDRAM['lang']['config_general_forbid_on_block'] = 'Какой тип заголовка должен CIDRAM ответить при блокировке запросов?';
$CIDRAM['lang']['config_general_force_hostname_lookup'] = 'Поиск имен хостов для всех запросов? True = Да; False = Нет [Стандарт]. Поиск имен хостов обычно выполняется по принципу «по мере необходимости», но может быть принудительно для всех запросов. Это может быть полезно в качестве средства предоставления более подробной информации в логфайлах, но может также иметь слегка отрицательный эффект на производительность.';
$CIDRAM['lang']['config_general_ipaddr'] = 'Место IP-адреса актуального соединения в общем потоке данных (полезно для Cloud-сервиса). Стандарт = REMOTE_ADDR. ВНИМАНИЕ: Изменяйте это значение только в том случае, если Вы уверены в своих действиях!';
$CIDRAM['lang']['config_general_lang'] = 'Задаёт CIDRAM стандарт языка.';
$CIDRAM['lang']['config_general_log_banned_ips'] = 'Включить заблокированные запросы от запрещенных IP-адресов в лог-файлы? True = Да [Стандарт]; False = Нет.';
$CIDRAM['lang']['config_general_logfile'] = 'Файл разборчивыми для людей, для регистрации всех заблокированных попыток несанкционированного доступа. Задайте имя файлу, или оставьте пустым чтобы деактивировать опцию.';
$CIDRAM['lang']['config_general_logfileApache'] = 'Apache-стиль файл для регистрации всех заблокированных попыток несанкционированного доступа. Задайте имя файлу, или оставьте пустым чтобы деактивировать опцию.';
$CIDRAM['lang']['config_general_logfileSerialized'] = 'Сериализованная файл для регистрации всех заблокированных попыток несанкционированного доступа. Задайте имя файлу, или оставьте пустым чтобы деактивировать опцию.';
$CIDRAM['lang']['config_general_maintenance_mode'] = 'Включить режим обслуживания? True = Да; False = Нет [Стандарт]. Отключает все, кроме фронтенд. Иногда полезно при обновлении CMS, фреймворков и т.д.';
$CIDRAM['lang']['config_general_max_login_attempts'] = 'Максимальное количество попыток входа в систему.';
$CIDRAM['lang']['config_general_numbers'] = 'Как Вы предпочитаете номера для отображения? Выберите пример, который выглядит наиболее правильным для вас.';
$CIDRAM['lang']['config_general_protect_frontend'] = 'Определяет, будут ли средства защиты как правило предоставляемые CIDRAM должны быть применены к фронтенд. True = Да [Стандарт]; False = Нет.';
$CIDRAM['lang']['config_general_search_engine_verification'] = 'Попытка проверить запросы от поисковых систем? Верификация поисковых систем гарантирует, что они не будут запрещены в результате превышения предела нарушение (запрет на поисковые системы с вашего сайта, как правило, оказывают негативное влияние на вашей поисковой системы рейтинга, SEO, и т.д.). Когда проверяется, поисковые системы могут быть заблокированы, но не запрещено. Когда не проверяется, это возможно для них должны быть запрещены в результате превышения лимита нарушений. Дополнительно, верификация поисковых систем обеспечивает защиту от подделки запросов поисковой системы и вредоносные источники маскируются как поисковые системы (такие запросы будут заблокированы при проверке поисковой активируется). True = Активировать верификация поисковых систем [Стандарт]; False = Деактивировать верификация поисковых систем.';
$CIDRAM['lang']['config_general_silent_mode'] = 'Должен CIDRAM молча перенаправить заблокированные попытки доступа вместо отображения страницы «Доступ Закрыт»? Если да, указать местоположение для перенаправления блокировал попытки доступа. Если нет, оставить эту переменную пустым.';
$CIDRAM['lang']['config_general_statistics'] = 'Отслеживать статистику использования CIDRAM? True = Да; False = Нет [Стандарт].';
$CIDRAM['lang']['config_general_timeFormat'] = 'Формат нотации даты, используемый CIDRAM. Дополнительные опции могут быть добавлены по запросу.';
$CIDRAM['lang']['config_general_timeOffset'] = 'Смещение часового пояса в минут.';
$CIDRAM['lang']['config_general_timezone'] = 'Ваш часовой пояс.';
$CIDRAM['lang']['config_general_truncate'] = 'Усекать лог-файлы, когда они достигают определенного размера? Значение это максимальный размер в Б/КБ/МБ/ГБ/ТБ, до которого файл журнала может увеличиться до усечения. Стандартное значение 0КБ отключает усечение (лог-файлы может расти неограниченно). Примечание: относится к отдельным лог-файлы! Размер файлов журнала не учитывается совместно.';
$CIDRAM['lang']['config_recaptcha_api'] = 'Какой API использовать? V2 или Invisible?';
$CIDRAM['lang']['config_recaptcha_expiry'] = 'Количество часов, чтобы вспомнить инстанция reCAPTCHA.';
$CIDRAM['lang']['config_recaptcha_lockip'] = 'Свяжите reCAPTCHA к IP-адреса?';
$CIDRAM['lang']['config_recaptcha_lockuser'] = 'Свяжите reCAPTCHA к пользователи?';
$CIDRAM['lang']['config_recaptcha_logfile'] = 'Записывать все попытки reCAPTCHA? Если да, указать имя использовать для файла журнала. Если нет, оставьте эту переменную пустым.';
$CIDRAM['lang']['config_recaptcha_secret'] = 'Это значение должно соответствовать «secret key» для вашего reCAPTCHA, которые можно найти в dashboard (панели управления) reCAPTCHA.';
$CIDRAM['lang']['config_recaptcha_signature_limit'] = 'Максимальное количество сигнатурей разрешено срабатывает, когда будет предлагаться инстанция reCAPTCHA. Стандарт = 1. Если это число превышено для любого конкретного запроса, инстанция reCAPTCHA не будет предлагаться.';
$CIDRAM['lang']['config_recaptcha_sitekey'] = 'Это значение должно соответствовать «site key» для вашего reCAPTCHA, которые можно найти в dashboard (панели управления) reCAPTCHA.';
$CIDRAM['lang']['config_recaptcha_usemode'] = 'Определяет как CIDRAM должны использовать reCAPTCHA (обратитесь к документации).';
$CIDRAM['lang']['config_signatures_block_bogons'] = 'Блокировать марсианин/bogon CIDRs? Если Вы ожидаете посетителей на свой веб-сайт из вашей локальной сети или из локальной сети, эта директива должна быть false. В противном случае, эта директива должна быть true.';
$CIDRAM['lang']['config_signatures_block_cloud'] = 'Блокировать CIDRs идентифицирован как принадлежащий услуг веб-хостинга / облачные сервисы? Если Вы управляете службы API с вашего сайта, или если Вы ожидать что другие веб-сайты чтобы подключиться к ваш веб-сайт, эта директива должна быть false. Если Вы этого не сделаете, эта директива должна быть true.';
$CIDRAM['lang']['config_signatures_block_generic'] = 'Блокировать CIDRs рекомендуется для черных списков? Это охватывает любые сигнатур, которые не помечены как часть какой-либо из других более конкретных категорий сигнатур.';
$CIDRAM['lang']['config_signatures_block_proxies'] = 'Блокировать CIDRs идентифицированы как принадлежащие к прокси-серверов? Если вам требуется, чтобы пользователи смогли получить доступ к вашему веб-сайт от анонимных прокси-серверов, эта директива должна быть false. В противном случае, если вам не нужны анонимных прокси-серверов, эта директива должна быть true как средство повышения безопасности.';
$CIDRAM['lang']['config_signatures_block_spam'] = 'Блокировать CIDRs которые были определены как высокого риска для спама? Если Вы не испытываете проблем при этом, как правило, эта директива должна быть true.';
$CIDRAM['lang']['config_signatures_default_tracktime'] = 'Сколько секунд чтобы отслеживать IP-адреса, запрещенные модулями. Стандарт = 604800 (1 неделя).';
$CIDRAM['lang']['config_signatures_infraction_limit'] = 'Максимальное число нарушений IP-разрешено брать на себя, прежде чем она запрещена отслеживание IP. Стандарт = 10.';
$CIDRAM['lang']['config_signatures_ipv4'] = 'Список сигнатур IPv4 файлы, которые CIDRAM должен попытаться обработать, разделенных запятыми.';
$CIDRAM['lang']['config_signatures_ipv6'] = 'Список сигнатур IPv6 файлы, которые CIDRAM должен попытаться обработать, разделенных запятыми.';
$CIDRAM['lang']['config_signatures_modules'] = 'Список модуль файлы для загрузки после обработки сигнатуры IPv4/IPv6, разделенных запятыми.';
$CIDRAM['lang']['config_signatures_track_mode'] = 'Когда следует учитывать нарушения? False = Когда IP-адреса блокируются из модулями. True = Когда IP-адреса блокируются по какой-либо причине.';
$CIDRAM['lang']['config_template_data_Magnification'] = 'Увеличение шрифта. Стандарт = 1.';
$CIDRAM['lang']['config_template_data_css_url'] = 'URL файла CSS для пользовательских тем.';
$CIDRAM['lang']['config_template_data_theme'] = 'Стандартная тема для CIDRAM.';
$CIDRAM['lang']['field_activate'] = 'Активировать';
$CIDRAM['lang']['field_banned'] = 'Запрещенный';
$CIDRAM['lang']['field_blocked'] = 'Блокированный';
$CIDRAM['lang']['field_clear'] = 'Очистить';
$CIDRAM['lang']['field_clear_all'] = 'Очистить все';
$CIDRAM['lang']['field_clickable_link'] = 'Ссылки кликабельны';
$CIDRAM['lang']['field_component'] = 'Компонент';
$CIDRAM['lang']['field_create_new_account'] = 'Создать Новый Аккаунт';
$CIDRAM['lang']['field_deactivate'] = 'Дезактивировать';
$CIDRAM['lang']['field_delete_account'] = 'Удалить Аккаунт';
$CIDRAM['lang']['field_delete_file'] = 'Удалить';
$CIDRAM['lang']['field_download_file'] = 'Скачать';
$CIDRAM['lang']['field_edit_file'] = 'Редактировать';
$CIDRAM['lang']['field_expiry'] = 'Истечение срока';
$CIDRAM['lang']['field_false'] = 'False (Ложный)';
$CIDRAM['lang']['field_file'] = 'Файл';
$CIDRAM['lang']['field_filename'] = 'Имя файла: ';
$CIDRAM['lang']['field_filetype_directory'] = 'Каталог';
$CIDRAM['lang']['field_filetype_info'] = 'Файл {EXT}';
$CIDRAM['lang']['field_filetype_unknown'] = 'Неизвестный';
$CIDRAM['lang']['field_first_seen'] = 'Впервые Увиденный';
$CIDRAM['lang']['field_infractions'] = 'Нарушений';
$CIDRAM['lang']['field_install'] = 'Устанавливать';
$CIDRAM['lang']['field_ip_address'] = 'IP-Адрес';
$CIDRAM['lang']['field_latest_version'] = 'Последняя Версия';
$CIDRAM['lang']['field_log_in'] = 'Войдите';
$CIDRAM['lang']['field_new_name'] = 'Новое имя:';
$CIDRAM['lang']['field_nonclickable_text'] = 'Текст не кликабельны';
$CIDRAM['lang']['field_ok'] = 'ОК';
$CIDRAM['lang']['field_options'] = 'Опции';
$CIDRAM['lang']['field_password'] = 'Пароль';
$CIDRAM['lang']['field_permissions'] = 'Разрешения';
$CIDRAM['lang']['field_range'] = 'Диапазон (Первый – Последний)';
$CIDRAM['lang']['field_rename_file'] = 'Переименовывать';
$CIDRAM['lang']['field_reset'] = 'Сбросить';
$CIDRAM['lang']['field_set_new_password'] = 'Установить новый пароль';
$CIDRAM['lang']['field_size'] = 'Общий Размер: ';
$CIDRAM['lang']['field_size_GB'] = 'ГБ';
$CIDRAM['lang']['field_size_KB'] = 'КБ';
$CIDRAM['lang']['field_size_MB'] = 'МБ';
$CIDRAM['lang']['field_size_TB'] = 'ТБ';
$CIDRAM['lang']['field_size_bytes'] = ['байт', 'байта', 'байтов'];
$CIDRAM['lang']['field_status'] = 'Статус';
$CIDRAM['lang']['field_system_timezone'] = 'Использовать часовой пояс по умолчанию.';
$CIDRAM['lang']['field_tracking'] = 'Отслеживания';
$CIDRAM['lang']['field_true'] = 'True (Правда)';
$CIDRAM['lang']['field_uninstall'] = 'Удалить';
$CIDRAM['lang']['field_update'] = 'Обновить';
$CIDRAM['lang']['field_update_all'] = 'Обновить все';
$CIDRAM['lang']['field_upload_file'] = 'Загрузить новый файл';
$CIDRAM['lang']['field_username'] = 'Имя Пользователя';
$CIDRAM['lang']['field_verify'] = 'Проверить';
$CIDRAM['lang']['field_verify_all'] = 'Проверить все';
$CIDRAM['lang']['field_your_version'] = 'Ваша Версия';
$CIDRAM['lang']['header_login'] = 'Пожалуйста войдите чтобы продолжить.';
$CIDRAM['lang']['label_active_config_file'] = 'Активный файл конфигурации: ';
$CIDRAM['lang']['label_banned'] = 'Запросы запрещенный';
$CIDRAM['lang']['label_blocked'] = 'Запросы блокированный';
$CIDRAM['lang']['label_branch'] = 'Ветвь последние стабильный:';
$CIDRAM['lang']['label_check_modules'] = 'Также протестируйте модули.';
$CIDRAM['lang']['label_cidram'] = 'Используемая версия CIDRAM:';
$CIDRAM['lang']['label_displaying'] = ['Отображение <span class="txtRd">%s</span> запись.', 'Отображение <span class="txtRd">%s</span> записи.', 'Отображение <span class="txtRd">%s</span> записей.'];
$CIDRAM['lang']['label_displaying_that_cite'] = ['Отображение <span class="txtRd">%1$s</span> запись, которая ссылается на «%2$s».', 'Отображение <span class="txtRd">%1$s</span> записи, которые ссылаются на «%2$s».', 'Отображение <span class="txtRd">%1$s</span> записей, которые ссылаются на «%2$s».'];
$CIDRAM['lang']['label_false_positive_risk'] = 'Риск ложноположительный: ';
$CIDRAM['lang']['label_fmgr_cache_data'] = 'Данные кэша и временные файлы';
$CIDRAM['lang']['label_fmgr_disk_usage'] = 'CIDRAM Использование диска: ';
$CIDRAM['lang']['label_fmgr_free_space'] = 'Свободное место на диске: ';
$CIDRAM['lang']['label_fmgr_total_disk_usage'] = 'Все использование диска: ';
$CIDRAM['lang']['label_fmgr_total_space'] = 'Емкость диска: ';
$CIDRAM['lang']['label_fmgr_updates_metadata'] = 'Метаданные обновлений компонентов';
$CIDRAM['lang']['label_hide'] = 'Скрывать';
$CIDRAM['lang']['label_os'] = 'Используемая операционная система:';
$CIDRAM['lang']['label_other'] = 'Другие';
$CIDRAM['lang']['label_other-ActiveIPv4'] = 'Активные файлы сигнатур IPv4';
$CIDRAM['lang']['label_other-ActiveIPv6'] = 'Активные файлы сигнатур IPv6';
$CIDRAM['lang']['label_other-ActiveModules'] = 'Активные модули';
$CIDRAM['lang']['label_other-Since'] = 'Дата начала';
$CIDRAM['lang']['label_php'] = 'Используемая версия PHP:';
$CIDRAM['lang']['label_reCAPTCHA'] = 'Попытки reCAPTCHA';
$CIDRAM['lang']['label_results'] = 'Результаты (%s вход – %s отвергнуто – %s принято – %s слиты – %s вывод):';
$CIDRAM['lang']['label_sapi'] = 'Используемая SAPI:';
$CIDRAM['lang']['label_show'] = 'Показать';
$CIDRAM['lang']['label_stable'] = 'Последние стабильный:';
$CIDRAM['lang']['label_sysinfo'] = 'Системная информация:';
$CIDRAM['lang']['label_tests'] = 'Испытания:';
$CIDRAM['lang']['label_total'] = 'Всего';
$CIDRAM['lang']['label_unstable'] = 'Последние нестабильный:';
$CIDRAM['lang']['link_accounts'] = 'Учетными Записями';
$CIDRAM['lang']['link_cidr_calc'] = 'Калькулятор CIDR';
$CIDRAM['lang']['link_config'] = 'Конфигурация';
$CIDRAM['lang']['link_documentation'] = 'Документация';
$CIDRAM['lang']['link_file_manager'] = 'Файл Менеджер';
$CIDRAM['lang']['link_home'] = 'Домашняя Страница';
$CIDRAM['lang']['link_ip_aggregator'] = 'Агрегатор IP';
$CIDRAM['lang']['link_ip_test'] = 'Тест IP';
$CIDRAM['lang']['link_ip_tracking'] = 'Отслеживания IP';
$CIDRAM['lang']['link_logs'] = 'Лог-Файлы';
$CIDRAM['lang']['link_sections_list'] = 'Списка Секций';
$CIDRAM['lang']['link_statistics'] = 'Статистика';
$CIDRAM['lang']['link_textmode'] = 'Форматирование текста: <a href="%1$sfalse%2$s">Просто</a> – <a href="%1$strue%2$s">Маскарадный</a> – <a href="%1$stally%2$s">Подсчет</a>';
$CIDRAM['lang']['link_updates'] = 'Обновления';
$CIDRAM['lang']['logs_logfile_doesnt_exist'] = 'Выбранный лог-файл не существует!';
$CIDRAM['lang']['logs_no_logfile_selected'] = 'Не лог-файлы Выбранный.';
$CIDRAM['lang']['logs_no_logfiles_available'] = 'Не лог-файлы доступны.';
$CIDRAM['lang']['max_login_attempts_exceeded'] = 'Максимальное количество попыток входа в систему был превышен; Доступ закрыт.';
$CIDRAM['lang']['previewer_days'] = 'Дни';
$CIDRAM['lang']['previewer_hours'] = 'Часы';
$CIDRAM['lang']['previewer_minutes'] = 'Минуты';
$CIDRAM['lang']['previewer_months'] = 'Месяцы';
$CIDRAM['lang']['previewer_seconds'] = 'Секунды';
$CIDRAM['lang']['previewer_weeks'] = 'Недели';
$CIDRAM['lang']['previewer_years'] = 'Лет';
$CIDRAM['lang']['response_accounts_already_exists'] = 'Аккаунт с таким именем уже существует!';
$CIDRAM['lang']['response_accounts_created'] = 'Аккаунт успешно создан!';
$CIDRAM['lang']['response_accounts_deleted'] = 'Аккаунт успешно удален!';
$CIDRAM['lang']['response_accounts_doesnt_exist'] = 'Эта аккаунт не существует.';
$CIDRAM['lang']['response_accounts_password_updated'] = 'Пароль успешно обновлено!';
$CIDRAM['lang']['response_activated'] = 'Успешно активирован.';
$CIDRAM['lang']['response_activation_failed'] = 'Не удалось активировать!';
$CIDRAM['lang']['response_checksum_error'] = 'Ошибка контрольной суммы! Файл отклонен!';
$CIDRAM['lang']['response_component_successfully_installed'] = 'Компонент успешно установлен.';
$CIDRAM['lang']['response_component_successfully_uninstalled'] = 'Компонент успешно удален.';
$CIDRAM['lang']['response_component_successfully_updated'] = 'Компонент успешно обновлено.';
$CIDRAM['lang']['response_component_uninstall_error'] = 'Произошла ошибка при попытке удалить компонент.';
$CIDRAM['lang']['response_configuration_updated'] = 'Конфигурация успешно обновлено.';
$CIDRAM['lang']['response_deactivated'] = 'Успешно деактивирован.';
$CIDRAM['lang']['response_deactivation_failed'] = 'Не удалось дезактивировать!';
$CIDRAM['lang']['response_delete_error'] = 'Не удалось удалить!';
$CIDRAM['lang']['response_directory_deleted'] = 'Каталог успешно удален!';
$CIDRAM['lang']['response_directory_renamed'] = 'Каталог успешно переименован!';
$CIDRAM['lang']['response_error'] = 'Ошибка';
$CIDRAM['lang']['response_failed_to_install'] = 'Не удалось установить!';
$CIDRAM['lang']['response_failed_to_update'] = 'Не удалось обновить!';
$CIDRAM['lang']['response_file_deleted'] = 'Файл успешно удален!';
$CIDRAM['lang']['response_file_edited'] = 'Файл успешно изменен!';
$CIDRAM['lang']['response_file_renamed'] = 'Файл успешно переименован!';
$CIDRAM['lang']['response_file_uploaded'] = 'Файл успешно загружен!';
$CIDRAM['lang']['response_login_invalid_password'] = 'Войти провал! Неверный пароль!';
$CIDRAM['lang']['response_login_invalid_username'] = 'Войти провал! Имя пользователя не существует!';
$CIDRAM['lang']['response_login_password_field_empty'] = 'Пароль пусто!';
$CIDRAM['lang']['response_login_username_field_empty'] = 'Имя пользователя пусто!';
$CIDRAM['lang']['response_login_wrong_endpoint'] = 'Неправильная конечная точка!';
$CIDRAM['lang']['response_no'] = 'Нет';
$CIDRAM['lang']['response_possible_problem_found'] = 'Возможная проблема.';
$CIDRAM['lang']['response_rename_error'] = 'Не удалось переименовать!';
$CIDRAM['lang']['response_statistics_cleared'] = 'Статистика очищена.';
$CIDRAM['lang']['response_tracking_cleared'] = 'Отслеживание отменен.';
$CIDRAM['lang']['response_updates_already_up_to_date'] = 'Уже обновлено.';
$CIDRAM['lang']['response_updates_not_installed'] = 'Компонент не установлен!';
$CIDRAM['lang']['response_updates_not_installed_php'] = 'Компонент не установлен (требует PHP {V})!';
$CIDRAM['lang']['response_updates_outdated'] = 'Устаревший!';
$CIDRAM['lang']['response_updates_outdated_manually'] = 'Устаревший (пожалуйста обновить вручную)!';
$CIDRAM['lang']['response_updates_outdated_php_version'] = 'Устаревший (требует PHP {V})!';
$CIDRAM['lang']['response_updates_unable_to_determine'] = 'Невозможно определить.';
$CIDRAM['lang']['response_upload_error'] = 'Не удалось загрузить!';
$CIDRAM['lang']['response_verification_failed'] = 'Ошибка проверки! Компонент может быть поврежден.';
$CIDRAM['lang']['response_verification_success'] = 'Успех проверки! Нет проблем.';
$CIDRAM['lang']['response_yes'] = 'Да';
$CIDRAM['lang']['state_complete_access'] = 'Полный доступ';
$CIDRAM['lang']['state_component_is_active'] = 'Компонент активен.';
$CIDRAM['lang']['state_component_is_inactive'] = 'Компонент неактивен.';
$CIDRAM['lang']['state_component_is_provisional'] = 'Компонент иногда активен.';
$CIDRAM['lang']['state_default_password'] = 'Предупреждение: Использует стандартный пароль!';
$CIDRAM['lang']['state_ignored'] = 'Игнорируется';
$CIDRAM['lang']['state_loadtime'] = 'Запрос страницы завершен через <span class="txtRd">%s</span> секунд.';
$CIDRAM['lang']['state_logged_in'] = 'В настоящее время вошли в систему.';
$CIDRAM['lang']['state_logs_access_only'] = 'Доступ только к лог-файлы';
$CIDRAM['lang']['state_maintenance_mode'] = 'Предупреждение: Включен режим обслуживания!';
$CIDRAM['lang']['state_password_not_valid'] = 'Предупреждение: Эта аккаунт не использует правильный пароль!';
$CIDRAM['lang']['state_risk_high'] = 'Высокий';
$CIDRAM['lang']['state_risk_low'] = 'Низкий';
$CIDRAM['lang']['state_risk_medium'] = 'Средний';
$CIDRAM['lang']['state_sl_totals'] = 'Сумма (Сигнатуры: <span class="txtRd">%s</span> – Секции сигнатуры: <span class="txtRd">%s</span> – Файлы сигнатуры: <span class="txtRd">%s</span>).';
$CIDRAM['lang']['state_tracking'] = ['В настоящее время отслеживание %s IP-адрес.', 'В настоящее время отслеживание %s IP-адреса.', 'В настоящее время отслеживание %s IP-адресов.'];
$CIDRAM['lang']['switch-hide-non-outdated-set-false'] = 'Не скрывают не-устаревший';
$CIDRAM['lang']['switch-hide-non-outdated-set-true'] = 'Скрывают не-устаревший';
$CIDRAM['lang']['switch-hide-unused-set-false'] = 'Не скрывают не-установлена';
$CIDRAM['lang']['switch-hide-unused-set-true'] = 'Скрывают не-установлена';
$CIDRAM['lang']['switch-tracking-blocked-already-set-false'] = 'Не проверять файлы сигнатуры';
$CIDRAM['lang']['switch-tracking-blocked-already-set-true'] = 'Проверять файлы сигнатуры';
$CIDRAM['lang']['switch-tracking-hide-banned-blocked-set-false'] = 'Не скрывайте запрещенные/заблокированные IP-адреса';
$CIDRAM['lang']['switch-tracking-hide-banned-blocked-set-true'] = 'Скрывайте запрещенные/заблокированные IP-адреса';
$CIDRAM['lang']['tip_accounts'] = 'Привет, {username}.<br />Учетными записями страница позволяет контролировать, кто может получить доступ к CIDRAM фронтенд.';
$CIDRAM['lang']['tip_cidr_calc'] = 'Привет, {username}.<br />Калькулятор CIDR позволяет рассчитать которые CIDRs IP-адрес принадлежит.';
$CIDRAM['lang']['tip_config'] = 'Привет, {username}.<br />Конфигурация страница позволяет изменять конфигурацию для CIDRAM от фронтенд.';
$CIDRAM['lang']['tip_custom_ua'] = 'Введите здесь пользовательский агент (user agent) – необязательно.';
$CIDRAM['lang']['tip_donate'] = 'CIDRAM предлагается бесплатно, но если Вы хотите пожертвовать на проект, Вы можете сделать это, нажав на кнопку пожертвовать.';
$CIDRAM['lang']['tip_enter_ip_here'] = 'Введите IP здесь.';
$CIDRAM['lang']['tip_enter_ips_here'] = 'Введите IP-адреса здесь.';
$CIDRAM['lang']['tip_fe_cookie_warning'] = 'Заметка: CIDRAM использует cookie для аутентификации логинов. Войдя в систему, Вы даете свое согласие на создание и сохранение файла cookie вашим браузером.';
$CIDRAM['lang']['tip_file_manager'] = 'Привет, {username}.<br />Файл менеджер позволяет удалять, редактировать, загружать и скачивать файлы. Используйте с осторожностью (Вы могли бы нарушить вашу установку с этим).';
$CIDRAM['lang']['tip_home'] = 'Привет, {username}.<br />Это домашняя страница для CIDRAM фронтенд. Выберите ссылку в меню навигации слева чтобы продолжить.';
$CIDRAM['lang']['tip_ip_aggregator'] = 'Привет, {username}.<br />Агрегатор IP позволяет вам выражать IP-адреса и CIDR как можно меньше. Введите данные для агрегирования и нажмите «ОК».';
$CIDRAM['lang']['tip_ip_test'] = 'Привет, {username}.<br />Тест IP страница позволяет проверить если IP-адреса заблокированы по установленных сигнатуры.';
$CIDRAM['lang']['tip_ip_test_module_switch'] = '(Если не выбрано, будут проверяться только файлы сигнатуры).';
$CIDRAM['lang']['tip_ip_tracking'] = 'Привет, {username}.<br />Страница отслеживания IP позволяет проверять состояние отслеживание IP-адресов, чтобы проверить какие из них были запрещены, и отменить отслеживание за ними, если вы хотите чтобы сделать это.';
$CIDRAM['lang']['tip_login'] = 'Стандартный имя пользователя: <span class="txtRd">admin</span> – Стандартный пароль: <span class="txtRd">password</span>';
$CIDRAM['lang']['tip_logs'] = 'Привет, {username}.<br />Выберите лог-файл из приведенного ниже списка чтобы прочитать содержимое лог-файл.';
$CIDRAM['lang']['tip_sections_list'] = 'Привет, {username}.<br />На этой странице перечислены секций, которые существуют в активных файлах сигнатур.';
$CIDRAM['lang']['tip_see_the_documentation'] = 'Просмотреть <a href="https://github.com/CIDRAM/CIDRAM/blob/master/_docs/readme.ru.md#SECTION6">документации</a> для получения информации о различных директив конфигурации и их целей.';
$CIDRAM['lang']['tip_statistics'] = 'Привет, {username}.<br />На этой странице показаны основные статистические данные об использовании вашей CIDRAM-инсталляция.';
$CIDRAM['lang']['tip_statistics_disabled'] = 'Заметка: Отслеживание статистики в настоящее время отключено, но может быть включено через страницу конфигурации.';
$CIDRAM['lang']['tip_updates'] = 'Привет, {username}.<br />Обновления страница позволяет устанавливать, удалить и обновления для различных компонентов CIDRAM (пакет ядра, сигнатуры, L10N файлы, итд).';
$CIDRAM['lang']['title_accounts'] = 'CIDRAM – Учетными Записями';
$CIDRAM['lang']['title_cidr_calc'] = 'CIDRAM – Калькулятор CIDR';
$CIDRAM['lang']['title_config'] = 'CIDRAM – Конфигурация';
$CIDRAM['lang']['title_file_manager'] = 'CIDRAM – Файл Менеджер';
$CIDRAM['lang']['title_home'] = 'CIDRAM – Домашняя Страница';
$CIDRAM['lang']['title_ip_aggregator'] = 'CIDRAM – Агрегатор IP';
$CIDRAM['lang']['title_ip_test'] = 'CIDRAM – Тест IP';
$CIDRAM['lang']['title_ip_tracking'] = 'CIDRAM – Отслеживания IP';
$CIDRAM['lang']['title_login'] = 'CIDRAM – Войти';
$CIDRAM['lang']['title_logs'] = 'CIDRAM – Лог-Файлы';
$CIDRAM['lang']['title_sections_list'] = 'CIDRAM – Списка Секций';
$CIDRAM['lang']['title_statistics'] = 'CIDRAM – Статистика';
$CIDRAM['lang']['title_updates'] = 'CIDRAM – Обновления';
$CIDRAM['lang']['warning'] = 'Предупреждения:';
$CIDRAM['lang']['warning_php_1'] = 'Ваша версия PHP больше не поддерживается! Рекомендуется обновление!';
$CIDRAM['lang']['warning_php_2'] = 'Ваша версия PHP сильно уязвима! Настоятельно рекомендуется обновление!';
$CIDRAM['lang']['warning_signatures_1'] = 'Активные файлы сигнатуры не активны!';

$CIDRAM['lang']['info_some_useful_links'] = 'Некоторые полезные ссылки:<ul>
            <li><a href="https://github.com/CIDRAM/CIDRAM/issues">CIDRAM Вопросы @ GitHub</a> – Страница вопросы для CIDRAM (поддержка, помощь, и т.д.).</li>
            <li><a href="https://wordpress.org/plugins/cidram/">CIDRAM @ WordPress.org</a> – WordPress плагин для CIDRAM.</li>
            <li><a href="https://sourceforge.net/projects/cidram/">CIDRAM @ SourceForge</a> – Альтернативное скачать зеркало для CIDRAM.</li>
            <li><a href="https://websectools.com/">WebSecTools.com</a> – Коллекция простых инструментов для веб-мастеров для защиты веб-сайтов.</li>
            <li><a href="https://macmathan.info/blocklists">Диапазонные блоки @ MacMathan.info</a> – Содержит дополнительные список блоки, которые могут быть добавлены к CIDRAM чтобы блокировать любые нежелательные страны получить доступ к вашему веб-сайт.</li>
            <li><a href="https://www.facebook.com/groups/2204685680/">Global PHP Group @ Facebook</a> – PHP учебных ресурсов и обсуждение.</li>
            <li><a href="https://php.earth/">PHP.earth</a> – PHP учебных ресурсов и обсуждение.</li>
            <li><a href="http://bgp.he.net/">Hurricane Electric BGP Toolkit</a> – Получить CIDRs от ASNs, определить ASN отношения, обнаружить ASNs основанный на сетевых имен, и т.д.</li>
            <li><a href="https://www.stopforumspam.com/forum/">Форум @ Stop Forum Spam</a> – Полезное Форум о останавливая форум спам.</li>
            <li><a href="https://radar.qrator.net/">Radar от Qrator</a> – Полезный инструмент для проверки связности ASNs а также для различных других сведений о ASNs.</li>
            <li><a href="http://www.ipdeny.com/ipblocks/">Страновые блоки IP @ IPdeny</a> – Фантастический и точный сервис для создания сигнатуры в масштабах всей страны.</li>
            <li><a href="https://www.google.com/transparencyreport/safebrowsing/malware/">Google Malware Dashboard</a> – Отображает отчеты о вредоносных программ уровень инфицирования для ASNs.</li>
            <li><a href="https://www.spamhaus.org/statistics/botnet-asn/">Проект Spamhaus</a> – Отображает отчеты о ботнет ставок инфекции для ASNs.</li>
            <li><a href="https://www.abuseat.org/public/asn.html">Список составных блокировок @ Abuseat.org</a> – Отображает отчеты о ботнет ставок инфекции для ASNs.</li>
            <li><a href="https://abuseipdb.com/">AbuseIPDB</a> – Ведение базы данных известных оскорбительных IP-адресов; Предоставляет API для проверки и отчетности IP-адресов.</li>
            <li><a href="https://www.megarbl.net/index.php">MegaRBL.net</a> – Поддерживает списки известных спамеров; Полезно для проверки спама деятельности IP/ASN.</li>
            <li><a href="https://maikuolan.github.io/Vulnerability-Charts/">Таблицы Уязвимостей</a> – Список безопасных/небезопасных версий различных пакетов (PHP, HHVM, и т.д.).</li>
            <li><a href="https://maikuolan.github.io/Compatibility-Charts/">Таблицы Совместимости</a> – Перечисляет информацию о совместимости для различных пакетов (CIDRAM, phpMussel, и т.д.).</li>
        </ul>';
