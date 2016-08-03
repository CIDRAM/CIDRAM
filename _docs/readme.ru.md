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

CIDRAM (Classless Inter-Domain Routing Access Manager) is a PHP script designed to protect websites by blocking requests originating from IP addresses regarded as being sources of undesirable traffic, including (but not limited to) traffic from non-human access endpoints, cloud services, spambots, scrapers, etc. It does this by calculating the possible CIDRs of the IP addresses supplied from inbound requests and then attempting to match these possible CIDRs against its signature files (these signature files contain lists of CIDRs of IP addresses regarded as being sources of undesirable traffic); If matches are found, the requests are blocked. @TranslateMe@

CIDRAM Авторское право 2016 года, а также GNU/GPLv2 by Caleb M (Maikuolan).

Это руководство находится в свободном доступе. Вы можете его передавать и/или модифицировать на условиях GNU General Public License, как публикует Фонд свободного программного обеспечения (Free Software Foundation); либо под второй версией лицензии, либо любой другой более поздней версией (по вашему выбору). Пособие публикуется не в целях увеличения прибыли или создания себе рекламы, а лишь в надежде принести пользу, правда, без всякой гарантии. Подробности вы можете узнать на странице GNU General Public License в разделе `LICENSE.txt`, а также на страницах:
- <http://www.gnu.org/licenses/>.
- <http://opensource.org/licenses/>.

Этот документ с относящимися к нему пакетом файлов можно бесплатно скачать на страницах [Github](https://github.com/Maikuolan/CIDRAM/).

---


###2. <a name="SECTION2"></a>ИНСТАЛЛЯЦИЯ

Уже ведутся работы по упрощению процесса инсталляции, но пока, чтобы установить CIDRAM на большинство используемых систем и систем управления содержимым (CMS), следуйте указаниям:

1) Скачайте архив на свой компьютер и откройте его. На хост-компьютере или в системе управления содержимым (CMS) создайте регистр, куда вы хотите загрузить содержимое этого пакета. Такой регистр, как `/public_html/cidram/`, будет вполне достаточным, пока он отвечает вашим требованиям к защите или личным предпочтениям.

2) Rename `config.ini.RenameMe` to `config.ini` (located inside `vault`), and optionally (strongly recommended for advanced users, but not recommended for beginners or for the inexperienced), open it (this file contains all the directives available for CIDRAM; above each option should be a brief comment describing what it does and what it's for). Adjust these directives as you see fit, as per whatever is appropriate for your particular setup. Save file, close. @TranslateMe@

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

CIDRAM should automatically block undesirable requests to your website without requiring any manual assistance, aside from its initial installation. @TranslateMe@

Updating is done manually, and you can customise your configuration and customise which CIDRs are blocked by modifying your configuration file and/or your signature files. @TranslateMe@

If you encounter any false positives, please contact me to let me know about it. @TranslateMe@

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
/vault/template.html | Шаблонный файл. Шаблон для HTML-формата сообщений, сообщающий о том, что загрузка файла была заблокирована CIDRAM (сообщение, которое будет показано пользователю).
/vault/template_custom.html | Шаблонный файл. Шаблон для HTML-формата сообщений, сообщающий о том, что загрузка файла была заблокирована CIDRAM (сообщение, которое будет показано пользователю).
/vault/rules_as6939.php | Пользовательские правила файл для AS6939.
/vault/rules_softlayer.php | Пользовательские правила файл для Soft Layer.
/vault/rules_specific.php | Пользовательские правила файл для некоторые специфические CIDRs.

---


###5. <a name="SECTION5"></a>НАСТРОЙКИ
Ниже представлен список переменных данных в файле конфигурации `config.ini`, а также краткое описание их функций.

####"general" (Категория)
Генеральная конфигурация от CIDRAM.

"logfile"
- Human readable file for logging all blocked access attempts. Specify a filename, or leave blank to disable.

"logfileApache"
- Apache-style file for logging all blocked access attempts. Specify a filename, or leave blank to disable.

"logfileSerialized"
- Serialised file for logging all blocked access attempts. Specify a filename, or leave blank to disable.

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
- Should CIDRAM respond with 403 headers to blocked requests, or stick with the usual 200 OK? False/200 = No (200) [Default]; True = Yes (403); 503 = Service unavailable (503).

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

####"template_data" (Категория)
Директивы/Переменные для шаблоны и темы.

Relates to the HTML output used to generate the "Access Denied" page. If you're using custom themes for CIDRAM, HTML output is sourced from the `template_custom.html` file, and otherwise, HTML output is sourced from the `template.html` file. Variables written to this section of the configuration file are parsed to the HTML output by way of replacing any variable names circumfixed by curly brackets found within the HTML output with the corresponding variable data. For example, where `foo="bar"`, any instance of `<p>{foo}</p>` found within the HTML output will become `<p>bar</p>`.

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

Refer to the custom signature files for more information.

---


Последнее обновление: 3 Август 2016 (2016.08.03).
