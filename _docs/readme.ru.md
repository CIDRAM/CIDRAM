## Документация для CIDRAM (Русский)

### Содержание
- 1. [ПРЕДИСЛОВИЕ](#SECTION1)
- 2. [ИНСТАЛЛЯЦИЯ](#SECTION2)
- 3. [ИСПОЛЬЗОВАНИЕ](#SECTION3)
- 4. [СОДЕРЖАНИЕ ПАКЕТА ФАЙЛОВ](#SECTION4)
- 5. [НАСТРОЙКИ](#SECTION5)
- 6. [ФОРМАТ ПОДПИСЕЙ](#SECTION6)

---


###1. <a name="SECTION1"></a>ПРЕДИСЛОВИЕ

CIDRAM/СИДРАМ (Бесклассовая Адресация Доступа Менеджер / Classless Inter-Domain Routing Access Manager) это скрипт предназначен для защиты веб-сайтов, путем блокирования запросов, исходящих из IP-адресов рассматривается как источников нежелательного трафика, в том числе (но не ограничивается) трафик с конечными точками доступа нечеловеческих, клоуд/облачные сервисы, спамботы, скребки, и т.д. Она делает это путем вычисления возможной CIDR из IP-адресов, поставляется из входящих запросов, а затем попытка сопоставить эти возможные CIDR с его подписи файлов (эти файлы сигнатур содержат списки CIDR из IP-адресов рассматривается как источников нежелательного трафика); Если совпадения найдены, запросы блокируются.

CIDRAM Авторское право 2016 года, а также GNU/GPLv2 by Caleb M (Maikuolan).

Это руководство находится в свободном доступе. Вы можете его передавать и/или модифицировать на условиях GNU General Public License, как публикует Фонд свободного программного обеспечения (Free Software Foundation); либо под второй версией лицензии, либо любой другой более поздней версией (по вашему выбору). Пособие публикуется не в целях увеличения прибыли или создания себе рекламы, а лишь в надежде принести пользу, правда, без всякой гарантии. Подробности вы можете узнать на странице GNU General Public License в разделе `LICENSE.txt`, а также на страницах:
- <http://www.gnu.org/licenses/>.
- <http://opensource.org/licenses/>.

Этот документ с относящимися к нему пакетом файлов можно бесплатно скачать на страницах [Github](https://github.com/Maikuolan/CIDRAM/).

---


###2. <a name="SECTION2"></a>ИНСТАЛЛЯЦИЯ

Уже ведутся работы по упрощению процесса инсталляции, но пока, чтобы установить CIDRAM на большинство используемых систем и систем управления содержимым (CMS), следуйте указаниям:

1) Скачайте архив на свой компьютер и откройте его. На хост-компьютере или в системе управления содержимым (CMS) создайте регистр, куда вы хотите загрузить содержимое этого пакета. Такой регистр, как `/public_html/cidram/`, будет вполне достаточным, пока он отвечает вашим требованиям к защите или личным предпочтениям.

2) Переименовать `config.ini.RenameMe` в `config.ini` (расположенный внутри `vault`), и если вы хотите (рекомендуются для опытных пользователей, имеющих соответствующие знания), открой это (этот файл содержит все параметры конфигурации для CIDRAM; краткие комментарии описывают задачи каждой параметры). Измените величины в соответствии с Вашими потребностями. Сохраните файл и закройте.

3) Скачайте всё содержимое (CIDRAM и файлы) в указанный в пункте 1 регистр, кроме файлов `*.txt`/`*.md`.

4) Право доступа `vault`-регистра поменяйте на «777». Права доступа вышестоящего регистра, в котором находится содержание (регистр, в который вы наметили занести файлы) могут остаться прежними, но всё же лучше проверить доступ (Если уже случались проблемы с доступом, когда предварительная установка была, например, «755»).

5) Скрепите CIDRAM с Вашей системой или с системой управления содержимым (CMS). Для этого есть много способов. Самым простым является способ, когда CIDRAM-руководство является началом главного файла, который будет загружаться всякий раз, когда будут заходить на ваш интернет-сайт. Этот файл нужно связать с Вашей системой или с системой управления содержимым (CMS) при помощи `require` или `include` команд. Обычно такой файл обозначается в регистре как `/includes`, `/assets` или `/functions`, и часто называется `init.php`, `common_functions.php`, `functions.php`. Вы должны найти тот файл, который соответствует Вашим требованиям. Если это трудно для Вас, то посетите страница вопросов CIDRAM на Github. Возможно я или кто-то другой уже имеет опыт работы с CMS, которую используете вы, и сможет дать Вам совет (обязательно сообщите, какой CMS Вы пользуетесь). Введите прямо в начало этого файла следующий код:

`<?php require '/user_name/public_html/cidram/loader.php'; ?>`

Строку между кавычками замените локальным путём файла в формате `loader.php`, без HTTP-адреса (подобно пути для `vault`-регистра). Сохраните файл и закройте его; Загрузите его заново.

-- АЛЬТЕРНАТИВА --

Если у Вас Apache HTTP-сервер и доступ к нему в формате `php.ini` или похожем формате, вы можете воспользоваться `auto_prepend_file`-директивой, установя приоритет CIDRAM, когда последует PHP-запрос. Примерно так:

`auto_prepend_file = "/user_name/public_html/cidram/loader.php"`

Или в `.htaccess` файлом:

`php_value auto_prepend_file "/user_name/public_html/cidram/loader.php"`

6) На этом инсталляционный процесс заканчивается. :-)

---


###3. <a name="SECTION3"></a>ИСПОЛЬЗОВАНИЕ

CIDRAM должен автоматически блокировать нежелательные запросы на свой веб-сайт, без необходимости ручного помощь, кроме его первоначальной установки.

Обновление производится вручную, и вы можете настроить конфигурацию, и настроить какие CIDRs заблокированы, путем изменения файла конфигурации и/или ваши файлы сигнатур.

Если вы обнаружили ложных результатов, пожалуйста, свяжитесь со мной чтобы сообщить мне об этом.

---


###4. <a name="SECTION4"></a>СОДЕРЖАНИЕ ПАКЕТА ФАЙЛОВ

Следующая таблица содержит все файлы, содержащиеся в скаченном архиве руководства, а также файлы, которые можно создать, используя данное руководство, с кратким описанием этих файлов.

Файл | Описание
----|----
/.gitattributes | Файл из Github проекта (на эффективность данного руководства не влияет).
/Changelog.txt | Перечень внесённых в руководство изменений и его различные версии (на эффективность данного руководства не влияет).
/composer.json | Composer/Packagist информация (на эффективность данного руководства не влияет).
/LICENSE.txt | Копия лицензии GNU/GPLv2 (на эффективность данного руководства не влияет).
/loader.php | Загрузчик. Этот файл будет связан с Вашей системой управления содержимым (обязательно!).
/README.md | Резюме информации о проекте.
/web.config | Файл с ASP.NET-конфигурации. Этот файл необходим для защиты `/vault` регистров от неавторизованного доступа, когда руководство инсталлируется на один из серверов, базирующихся на ASP.NET-технологиях.
/_docs/ | Регистр с документами (содержит различные файлы).
/_docs/readme.ar.md | Документация на Арабском языке.
/_docs/readme.de.md | Документация на Немецком языке.
/_docs/readme.en.md | Документация на Английском языке.
/_docs/readme.es.md | Документация на Испанском языке.
/_docs/readme.fr.md | Документация на Французском языке.
/_docs/readme.id.md | Документация на Индонезийском языке.
/_docs/readme.it.md | Документация на Итальянском языке.
/_docs/readme.ja.md | Документация на Японском языке.
/_docs/readme.nl.md | Документация на Нидерланском языке.
/_docs/readme.pt.md | Документация на Португальском языке.
/_docs/readme.ru.md | Документация на Русском языке.
/_docs/readme.vi.md | Документация на Вьетнамском язык.
/_docs/readme.zh-TW.md | Документация на Китайском традиционный.
/_docs/readme.zh.md | Документация на Китайском упрощенный.
/vault/ | Vault-регистр (содержит различные файлы)
/vault/.htaccess | Гипертекст доступа файл (в этом случае защищает от неавторизованного доступа чувствительные файлы данного руководства).
/vault/cache.dat | Cache-данные.
/vault/cli.php | Обработчик CLI (Способ Командных Строк).
/vault/config.ini.RenameMe | Файл с конфигурации. Содержит всевозможные конфигурации CIDRAM (переименовать чтобы активировать).
/vault/config.php | Обработчик конфигурации.
/vault/functions.php | Функции файла (обязательно).
/vault/hashes.dat | Содержит список принятых хэшей (связана с функция reCAPTCHA; генерируется только если функция reCAPTCHA активирована).
/vault/ipbypass.dat | Содержит список IP байпасов (связана с функция reCAPTCHA; генерируется только если функция reCAPTCHA активирована).
/vault/ipv4.dat | IPv4 подписи файла.
/vault/ipv4_custom.dat.RenameMe | IPv4 пользовательские подписи файлов (переименовать чтобы активировать).
/vault/ipv6.dat | IPv6 подписи файла.
/vault/ipv6_custom.dat.RenameMe | IPv6 пользовательские подписи файлов (переименовать чтобы активировать).
/vault/lang.php | Язык обработчика.
/vault/lang/ | Содержит CIDRAM файлы на разных языках.
/vault/lang/.htaccess | Гипертекст доступа файл (в этом случае защищает от неавторизованного доступа чувствительные файлы данного руководства).
/vault/lang/lang.ar.cli.php | Арабском языковые файлы для CLI (Способ Командных Строк).
/vault/lang/lang.ar.php | Арабском языковые файлы.
/vault/lang/lang.de.cli.php | Немецком языковые файлы для CLI (Способ Командных Строк).
/vault/lang/lang.de.php | Немецком языковые файлы.
/vault/lang/lang.en.cli.php | Английском языковые файлы для CLI (Способ Командных Строк).
/vault/lang/lang.en.php | Английском языковые файлы.
/vault/lang/lang.es.cli.php | Испанском языковые файлы для CLI (Способ Командных Строк).
/vault/lang/lang.es.php | Испанском языковые файлы.
/vault/lang/lang.fr.cli.php | Французском языковые файлы для CLI (Способ Командных Строк).
/vault/lang/lang.fr.php | Французском языковые файлы.
/vault/lang/lang.id.cli.php | Индонезийском языковые файлы для CLI (Способ Командных Строк).
/vault/lang/lang.id.php | Индонезийском языковые файлы.
/vault/lang/lang.it.cli.php | Итальянском языковые файлы для CLI (Способ Командных Строк).
/vault/lang/lang.it.php | Итальянском языковые файлы.
/vault/lang/lang.ja.cli.php | Японском языковые файлы для CLI (Способ Командных Строк).
/vault/lang/lang.ja.php | Японском языковые файлы.
/vault/lang/lang.nl.cli.php | Нидерланском языковые файлы для CLI (Способ Командных Строк).
/vault/lang/lang.nl.php | Нидерланском языковые файлы.
/vault/lang/lang.pt.cli.php | Португальском языковые файлы для CLI (Способ Командных Строк).
/vault/lang/lang.pt.php | Португальском языковые файлы.
/vault/lang/lang.ru.cli.php | Русском языковые файлы для CLI (Способ Командных Строк).
/vault/lang/lang.ru.php | Русском языковые файлы.
/vault/lang/lang.vi.cli.php | Вьетнамском языковые файлы для CLI (Способ Командных Строк).
/vault/lang/lang.vi.php | Вьетнамском языковые файлы.
/vault/lang/lang.zh-cli.TW.php | Китайском традиционный языковые файлы для CLI (Способ Командных Строк).
/vault/lang/lang.zh-TW.php | Китайском традиционный языковые файлы.
/vault/lang/lang.zh.cli.php | Китайском упрощенный языковые файлы для CLI (Способ Командных Строк).
/vault/lang/lang.zh.php | Китайском упрощенный языковые файлы.
/vault/outgen.php | Выход генератора.
/vault/recaptcha.php | Модуль reCAPTCHA.
/vault/rules_as6939.php | Пользовательские правила файл для AS6939.
/vault/rules_softlayer.php | Пользовательские правила файл для Soft Layer.
/vault/rules_specific.php | Пользовательские правила файл для некоторые специфические CIDRs.
/vault/salt.dat | Соль файл (используется некоторыми периферического функциональностью; генерируется только при необходимости).
/vault/template.html | Шаблонный файл. Шаблон для HTML-формата сообщений, сообщающий о том, что загрузка файла была заблокирована CIDRAM (сообщение, которое будет показано пользователю).
/vault/template_custom.html | Шаблонный файл. Шаблон для HTML-формата сообщений, сообщающий о том, что загрузка файла была заблокирована CIDRAM (сообщение, которое будет показано пользователю).

---


###5. <a name="SECTION5"></a>НАСТРОЙКИ
Ниже представлен список переменных данных в файле конфигурации `config.ini`, а также краткое описание их функций.

####"general" (Категория)
Генеральная конфигурация от CIDRAM.

"logfile"
- Файл разборчивыми для людей, для регистрации всех заблокированных попыток несанкционированного доступа. Задайте имя файлу, или оставьте пустым чтобы деактивировать опцию.

"logfileApache"
- Apache-стиль файл для регистрации всех заблокированных попыток несанкционированного доступа. Задайте имя файлу, или оставьте пустым чтобы деактивировать опцию.

"logfileSerialized"
- Сериализованная файл для регистрации всех заблокированных попыток несанкционированного доступа. Задайте имя файлу, или оставьте пустым чтобы деактивировать опцию.

*Полезный совет: Если ты хочешь, вы можете добавить информацию о дате/времени к именам файлов журналов путем включения их во имя: `{yyyy}` для полный год, `{yy}` для сокращенный год, `{mm}` для месяц, `{dd}` для день, `{hh}` для час.*

*Примеры:*
- *`logfile='logfile.{yyyy}-{mm}-{dd}-{hh}.txt'`*
- *`logfileApache='access.{yyyy}-{mm}-{dd}-{hh}.txt'`*
- *`logfileSerialized='serial.{yyyy}-{mm}-{dd}-{hh}.txt'`*

"timeOffset"
- Если ваш сервер времени не соответствует вашему местному времени, вы можете указать смещение здесь для корректировки информации о дате/времени генерируется от CIDRAM в соответствии с вашими потребностями. Обычно рекомендуется вместо того, чтобы настроить директиву о зоне времени в файле `php.ini`, но иногда (например, при работе с общими хостинг-провайдеров, которые ограничены) это не всегда возможно, и так, эта опция представлена здесь. Смещение описывается как минут.
- Пример (добавить один час): `timeOffset=60`

"ipaddr"
- Место IP-адреса актуального соединения в общем потоке данных (полезно для Cloud-сервиса). Стандарт = REMOTE_ADDR. Внимание! Изменяйте это значение только в том случае, если вы уверены в своих действиях!

"forbid_on_block"
- Какой тип заголовка должен CIDRAM ответить при блокировке запросов? False/200 = 200 OK (Хорошо) [Стандарт]; True = 403 Forbidden (Запрещено); 503 = Service unavailable (Сервис недоступен).

"silent_mode"
- Should CIDRAM silently redirect blocked access attempts instead of displaying the "Access Denied" page? If yes, specify the location to redirect blocked access attempts to. If no, leave this variable blank.

"lang"
- Задаёт CIDRAM стандарт языка.

"emailaddr"
- If you wish, you can supply an email address here to be given to users when they're blocked, for them to use as a point of contact for support and/or assistance for in the event of them being blocked mistakenly or in error. WARNING: Whatever email address you supply here will most certainly be acquired by spambots and scrapers during the course of its being used here, and so, it's strongly recommended that if you choose to supply an email address here, that you ensure that the email address you supply here is a disposable address and/or an address that you don't mind being spammed (in other words, you probably don't want to use your primary personal or primary business email addresses).

"disable_cli"
- Деактивировать ли CLI-модус? Обычно CLI-модус активирован. Однако иногда он может отрицательно влиять на определённые тестовые программы (например на PHPUnit) и другие приложения, базирующиеся на CLI. Если CLI-модус нельзя деактивировать, то эту команду нужно игнорировать. False = CLI-модус активирован [Стандарт]; True = CLI-модус деактивирован.

####"signatures" (Категория)
Конфигурация подписями.

"ipv4"
- A list of the IPv4 signature files that CIDRAM should attempt to parse, delimited by commas. You can add entries here if you want to include additional IPv4 signature files into CIDRAM.

"ipv6"
- A list of the IPv6 signature files that CIDRAM should attempt to parse, delimited by commas. You can add entries here if you want to include additional IPv6 signature files into CIDRAM.

"block_cloud"
- Block CIDRs identified as belonging to webhosting/cloud services? If you operate an API service from your website or if you expect other websites to connect to your website, this should be set to false. If you don't, then, this directive should be set to true.

"block_bogons"
- Block bogon/martian CIDRs? If you expect connections to your website from within your local network, from localhost, or from your LAN, this directive should be set to false. If you don't expect these such connections, this directive should be set to true.

"block_generic"
- Block CIDRs generally recommended for blacklisting? This covers any signatures that aren't marked as being part of any of the other more specific signature categories.

"block_proxies"
- Block CIDRs identified as belonging to proxy services? If you require that users be able to access your website from anonymous proxy services, this should be set to false. Otherwise, if you don't require anonymous proxies, this directive should be set to true as a means of improving security.

"block_spam"
- Block CIDRs identified as being high-risk for spam? Unless you experience problems when doing so, generally, this should always be set to true.

####"recaptcha" (Категория)
Optionally, you can provide users with a way to bypass the "Доступ Закрыт" page by way of completing a reCAPTCHA instance, if you want to do so. This can help to mitigate some of the risks associated with false positives in those situations where we're not entirely sure whether a request has originated from a machine or a human.

Due to the risks associated with providing a way for end-users to bypass the "Доступ Закрыт" page, generally, I would advise against enabling this feature unless you feel it to be necessary to do so. Situations where it could be necessary: If your website has customers/users that need to have access to your website, and if this is something that can't be compromised on, but if those customers/users happen to be connecting from a hostile network that could potentially also be carrying undesirable traffic, and blocking this undesirable traffic is also something that can't be compromised on, in those particular no-win situations, the reCAPTCHA feature could come in handy as a means of allowing the desirable customers/users, while keeping out the undesirable traffic from the same network. That said though, given that the intended purpose of a CAPTCHA is to distinguish between humans and non-humans, the reCAPTCHA feature would only assist in these no-win situations if we're to assume that this undesirable traffic is non-human (eg, spambots, scrapers, hacktools, automated traffic), as opposed to being undesirable human traffic (such as human spammers, hackers, et al).

To obtain a "site key" and a "secret key" (required for using reCAPTCHA), please go to: [https://developers.google.com/recaptcha/](https://developers.google.com/recaptcha/)

"usemode"
- Defines how CIDRAM should use reCAPTCHA.
- 0 = reCAPTCHA is completely disabled (default).
- 1 = reCAPTCHA is enabled for all signatures.
- 2 = reCAPTCHA is enabled only for signatures belonging to sections specially marked as reCAPTCHA-enabled within the signature files.
- (Any other value will be treated in the same way as 0).

"lockip"
- Specifies whether hashes should be locked to specific IPs. False = Cookies and hashes CAN be used across multiple IPs (default). True = Cookies and hashes CAN'T be used across multiple IPs (cookies/hashes are locked to IPs).
- Note: "lockip" value is ignored when "lockuser" is false, due to that the mechanism for remembering "users" differs depending on this value.

"lockuser"
- Specifies whether successful completion of a reCAPTCHA instance should be locked to specific users. False = Successful completion of a reCAPTCHA instance will grant access to all requests originating from the same IP as that used by the user completing the reCAPTCHA instance; Cookies and hashes aren't used; Instead, an IP whitelist will be used. True = Successful completion of a reCAPTCHA instance will only grant access to the user completing the reCAPTCHA instance; Cookies and hashes are used to remember the user; An IP whitelist is not used (default).

"sitekey"
- This value should correspond to the "site key" for your reCAPTCHA, which can be found within the reCAPTCHA dashboard.

"secret"
- This value should correspond to the "secret key" for your reCAPTCHA, which can be found within the reCAPTCHA dashboard.

"expiry"
- When "lockuser" is true (default), in order to remember when a user has successfully passed a reCAPTCHA instance, for future page requests, CIDRAM generates a standard HTTP cookie containing a hash which corresponds to an internal record containing that same hash; Future page requests will use these corresponding hashes to authenticate that a user has previously already passed a reCAPTCHA instance. When "lockuser" is false, an IP whitelist is used to determine whether requests should be permitted from the IP of inbound requests; Entries are added to this whitelist when the reCAPTCHA instance is successfully passed. For how many hours should these cookies, hashes and whitelist entries remain valid? Default = 720 (1 month).

"logfile"
- Записывать все попытки reCAPTCHA? Если да, указать имя использовать для файла журнала. Если нет, оставьте эту переменную пустым.

*Полезный совет: Если ты хочешь, вы можете добавить информацию о дате/времени к именам файлов журналов путем включения их во имя: `{yyyy}` для полный год, `{yy}` для сокращенный год, `{mm}` для месяц, `{dd}` для день, `{hh}` для час.*

*Примеры:*
- *`logfile='recaptcha.{yyyy}-{mm}-{dd}-{hh}.txt'`*

####"template_data" (Категория)
Директивы/Переменные для шаблоны и темы.

Шаблонный данных относится к HTML-вывода, используемый для генерации "Доступ Закрыт" сообщения отображается для пользователей когда/на загрузки/уплоад файлов блокируются. Если вы используете персонализированные темы для CIDRAM, HTML-выход получены из `template_custom.html` файл, а в противном случае, HTML-выход получены из `template.html` файл. Переменные, записанные в этом разделе файл конфигурации обрабатываются на HTML-выход в виде заменой любой имена переменных в окружении фигурных скобках найденный в HTML-вывода с соответствующей переменных данных. Например, где `foo="bar"`, любое вхождение `<p>{foo}</p>` найденный в HTML-вывода станет `<p>bar</p>`.

"css_url"
- Шаблонный файл для персонализированные темы использует внешние CSS свойства и шаблонный файл для стандарт тема использует внутренние CSS свойства. Поручить CIDRAM использовать персонализированные темы шаблонный файл, указать адрес публичного HTTP в CSS файлов вашей темы используя `css_url` переменная. Если оставить это переменная пустым, CIDRAM будет использовать шаблонный файл для стандарт тема.

---


###6. <a name="SECTION6"></a>ФОРМАТ ПОДПИСЕЙ

A description of the format and structure of the signatures used by CIDRAM can be found documented in plain-text within either of the two custom signature files. Please refer to that documentation to learn more about the format and structure of the signatures of CIDRAM.

All IPv4 signatures follow the format: `xxx.xxx.xxx.xxx/yy [Function] [Param]`.
- `xxx.xxx.xxx.xxx` represents the beginning of the CIDR block (the octets of the initial IP address in the block).
- `yy` represents the CIDR block size [1-32].
- `[Function]` instructs the script what to do with the signature (how the signature should be regarded).
- `[Param]` represents whatever additional information may be required by `[Function]`.

All IPv6 signatures follow the format: `xxxx:xxxx:xxxx:xxxx::xxxx/yy [Function] [Param]`.
- `xxxx:xxxx:xxxx:xxxx::xxxx` represents the beginning of the CIDR block (the octets of the initial IP address in the block). Complete notation and abbreviated notation are both acceptable (and each MUST follow the appropriate and relevant standards of IPv6 notation, but with one exception: an IPv6 address can never begin with an abbreviation when used in a signature for this script, due to the way in which CIDRs are reconstructed by the script; For example, `::1/128` should be expressed, when used in a signature, as `0::1/128`, and `::0/128` expressed as `0::/128`).
- `yy` represents the CIDR block size [1-128].
- `[Function]` instructs the script what to do with the signature (how the signature should be regarded).
- `[Param]` represents whatever additional information may be required by `[Function]`.

The signature files for CIDRAM SHOULD use Unix-style linebreaks (`%0A`, or `\n`)! Other types/styles of linebreaks (eg, Windows` %0D%0A` or `\r\n` linebreaks, Mac `%0D` or `\r` linebreaks, etc) MAY be used, but are NOT preferred. Non-Unix-style linebreaks will be normalised to Unix-style linebreaks by the script.

Precise and correct CIDR notation is required, otherwise the script will NOT recognise the signatures. Additionally, all the CIDR signatures of this script MUST begin with an IP address whose IP number can divide evenly into the block division represented by its CIDR block size (eg, if you wanted to block all IPs from `10.128.0.0` to `11.127.255.255`, `10.128.0.0/8` would NOT be recognised by the script, but `10.128.0.0/9` and `11.0.0.0/9` used in conjunction, WOULD be recognised by the script).

Anything in the signature files not recognised as a signature nor as signature-related syntax by the script will be IGNORED, therefore meaning that you can safely put any non-signature data that you want into the signature files without breaking them and without breaking the script. Comments are acceptable in the signature files, and no special formatting is required for them. Shell-style hashing for comments is preferred, but not enforced; Functionally, it makes no difference to the script whether or not you choose to use Shell-style hashing for comments, but using Shell-style hashing helps IDEs and plain-text editors to correctly highlight the various parts of the signature files (and so, Shell-style hashing can assist as a visual aid while editing).

The possible values of `[Function]` are as follows:
- Run
- Whitelist
- Greylist
- Deny

If "Run" is used, when the signature is triggered, the script will attempt to execute (using a `require_once` statement) an external PHP script, specified by the `[Param]` value (the working directory should be the "/vault/" directory of the script).

Примеры: `127.0.0.0/8 Run example.php`

This can be useful if you want to execute some specific PHP code for some specific IPs and/or CIDRs.

If "Whitelist" is used, when the signature is triggered, the script will reset all detections (if there's been any detections) and break the test function. `[Param]` is ignored. This function is the equivalent of whitelisting a particular IP or CIDR from being detected.

Примеры: `127.0.0.1/32 Whitelist`

If "Greylist" is used, when the signature is triggered, the script will reset all detections (if there's been any detections) and skip to the next signature file to continue processing. `[Param]` is ignored.

Примеры: `127.0.0.1/32 Greylist`

If "Deny" is used, when the signature is triggered, assuming no whitelist signature has been triggered for the given IP address and/or given CIDR, access to the protected page will be denied. "Deny" is what you'll want to use to actually block an IP address and/or CIDR range. When any signatures are triggered that make use of "Deny", the "Access Denied" page of the script will be generated and the request to the protected page killed.

The `[Param]` value accepted by "Deny" will be parsed to the "Access Denied" page output, supplied to the client/user as the cited reason for their access to the requested page being denied. It can be either a short and simple sentence, explaining why you've chosen to block them (anything should suffice, even a simple "I don't want you on my website"), or one of a small handful of shorthand words supplied by the script, that if used, will be replaced by the script with a pre-prepared explanation of why the client/user has been blocked.

The pre-prepared explanations have i18n support and can be translated by the script based upon the language you specify to the `lang` directive of the script configuration. Additionally, you can instruct the script to ignore "Deny" signatures based upon their `[Param]` value (if they're using these shorthand words) via the directives specified by the script configuration (each shorthand word has a corresponding directive to either process the corresponding signatures or to ignore them). `[Param]` values that don't use these shorthand words, however, don't have i18n support and therefore WON'T be translated by the script, and additionally, aren't directly controllable by the script configuration.

The available shorthand words are:
- Bogon
- Cloud
- Generic
- Proxy
- Spam

Optional: If you want to split your custom signatures into individual sections, you can identify these individual sections to the script by adding a "Tag:" label immediately after the signatures of each section, along with the name of your signature section.

Примеры:
```
# "Section 1."
1.2.3.4/32 Deny Bogon
2.3.4.5/32 Deny Cloud
4.5.6.7/32 Deny Generic
5.6.7.8/32 Deny Spam
6.7.8.9/32 Deny Proxy
Tag: Section 1
```

To break section tagging and to ensure that tags aren't incorrectly identified to signature sections from earlier in the signature files, simply ensure that there are at least two consecutive linebreaks between your tag and your earlier signature sections. Any untagged signatures will default to either "IPv4" or "IPv6" (depending on which types of signatures are being triggered).

Примеры:
```
1.2.3.4/32 Deny Bogon
2.3.4.5/32 Deny Cloud

4.5.6.7/32 Deny Generic
5.6.7.8/32 Deny Spam
Tag: Section 1
```

In the above example `1.2.3.4/32` and `2.3.4.5/32` will be tagged as "IPv4", whereas `4.5.6.7/32` and `5.6.7.8/32` will be tagged as "Section 1".

In addition, if you want CIDRAM to completely ignore some specific sections within any of the signature files, you can use the `ignore.dat` file to specify which sections to ignore. On a new line, write `Ignore`, followed by a space, followed by the name of the section that you want CIDRAM to ignore.

Примеры:
```
Ignore Section 1
```

Смотрите пользовательские подписи файлов для получения дополнительной информации.

---


Последнее обновление: 24 Август 2016 (2016.08.24).
