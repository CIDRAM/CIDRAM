## Documentation for CIDRAM (English).

### Contents
- 1. [PREAMBLE](#SECTION1)
- 2. [HOW TO INSTALL](#SECTION2)
- 3. [HOW TO USE](#SECTION3)
- 4. [FRONT-END MANAGEMENT](#SECTION4)
- 5. [FILES INCLUDED IN THIS PACKAGE](#SECTION5)
- 6. [CONFIGURATION OPTIONS](#SECTION6)
- 7. [SIGNATURE FORMAT](#SECTION7)
- 8. [FREQUENTLY ASKED QUESTIONS (FAQ)](#SECTION8)

*Note regarding translations: In the event of errors (e.g., discrepancies between translations, typos, etc), the English version of the README is considered the original and authoritative version. If you find any errors, your assistance in correcting them would be welcomed.*

---


### 1. <a name="SECTION1"></a>PREAMBLE

CIDRAM (Classless Inter-Domain Routing Access Manager) is a PHP script designed to protect websites by blocking requests originating from IP addresses regarded as being sources of undesirable traffic, including (but not limited to) traffic from non-human access endpoints, cloud services, spambots, scrapers, etc. It does this by calculating the possible CIDRs of the IP addresses supplied from inbound requests and then attempting to match these possible CIDRs against its signature files (these signature files contain lists of CIDRs of IP addresses regarded as being sources of undesirable traffic); If matches are found, the requests are blocked.

*(See: [What is a "CIDR"?](#WHAT_IS_A_CIDR)).*

CIDRAM COPYRIGHT 2016 and beyond GNU/GPLv2 by Caleb M (Maikuolan).

This script is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version. This script is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details, located in the `LICENSE.txt` file and available also from:
- <http://www.gnu.org/licenses/>.
- <http://opensource.org/licenses/>.

This document and its associated package can be downloaded for free from [GitHub](https://cidram.github.io/).

---


### 2. <a name="SECTION2"></a>HOW TO INSTALL

#### 2.0 INSTALLING MANUALLY

1) By your reading this, I'm assuming you've already downloaded an archived copy of the script, decompressed its contents and have it sitting somewhere on your local machine. From here, you'll want to work out where on your host or CMS you want to place those contents. A directory such as `/public_html/cidram/` or similar (though, it doesn't matter which you choose, so long as it's something secure and something you're happy with) will suffice. *Before you begin uploading, read on..*

2) Rename `config.ini.RenameMe` to `config.ini` (located inside `vault`), and optionally (strongly recommended for advanced users, but not recommended for beginners or for the inexperienced), open it (this file contains all the directives available for CIDRAM; above each option should be a brief comment describing what it does and what it's for). Adjust these directives as you see fit, as per whatever is appropriate for your particular setup. Save file, close.

3) Upload the contents (CIDRAM and its files) to the directory you'd decided on earlier (you don't need to include the `*.txt`/`*.md` files, but mostly, you should upload everything).

4) CHMOD the `vault` directory to "755" (if there are problems, you can try "777"; this is less secure, though). The main directory storing the contents (the one you chose earlier), usually, can be left alone, but CHMOD status should be checked if you've had permissions issues in the past on your system (by default, should be something like "755").

5) Next, you'll need to "hook" CIDRAM to your system or CMS. There are several different ways you can "hook" scripts such as CIDRAM to your system or CMS, but the easiest is to simply include the script at the beginning of a core file of your system or CMS (one that'll generally always be loaded when someone accesses any page across your website) using a `require` or `include` statement. Usually, this'll be something stored in a directory such as `/includes`, `/assets` or `/functions`, and will often be named something like `init.php`, `common_functions.php`, `functions.php` or similar. You'll have to work out which file this is for your situation; If you encounter difficulties in working this out for yourself, visit the CIDRAM issues page on GitHub. To do this [to use `require` or `include`], insert the following line of code to the very beginning of that core file, replacing the string contained inside the quotation marks with the exact address of the `loader.php` file (local address, not the HTTP address; it'll look similar to the vault address mentioned earlier).

`<?php require '/user_name/public_html/cidram/loader.php'; ?>`

Save file, close, reupload.

-- OR ALTERNATIVELY --

If you're using an Apache webserver and if you have access to `php.ini`, you can use the `auto_prepend_file` directive to prepend CIDRAM whenever any PHP request is made. Something like:

`auto_prepend_file = "/user_name/public_html/cidram/loader.php"`

Or this in the `.htaccess` file:

`php_value auto_prepend_file "/user_name/public_html/cidram/loader.php"`

6) That's everything! :-)

#### 2.1 INSTALLING WITH COMPOSER

[CIDRAM is registered with Packagist](https://packagist.org/packages/cidram/cidram), and so, if you're familiar with Composer, you can use Composer to install CIDRAM (you'll still need to prepare the configuration and hooks though; see "installing manually" steps 2 and 5).

`composer require cidram/cidram`

#### 2.2 INSTALLING FOR WORDPRESS

If you want to use CIDRAM with WordPress, you can ignore all the instructions above. [CIDRAM is registered as a plugin with the WordPress plugins database](https://wordpress.org/plugins/cidram/), and you can install CIDRAM directly from the plugins dashboard. You can install it in the same manner as any other plugin, and no addition steps are required. Just as with the other installation methods, you can customise your installation by modifying the contents of the `config.ini` file or by using the front-end configuration page. If you enable the CIDRAM front-end and update CIDRAM using the front-end updates page, this will automatically sync with the plugin version information displayed in the plugins dashboard.

*Warning: Updating CIDRAM via the plugins dashboard results in a clean installation! If you've customised your installation (changed your configuration, installed modules, etc), these customisations will be lost when updating via the plugins dashboard! Logfiles will also be lost when updating via the plugins dashboard! To preserve logfiles and customisations, update via the CIDRAM front-end updates page.*

---


### 3. <a name="SECTION3"></a>HOW TO USE

CIDRAM should automatically block undesirable requests to your website without requiring any manual assistance, aside from its initial installation.

Updating is done manually, and you can customise your configuration and customise which CIDRs are blocked by modifying your configuration file and/or your signature files.

If you encounter any false positives, please contact me to let me know about it.

---


### 4. <a name="SECTION4"></a>FRONT-END MANAGEMENT

#### 4.0 WHAT IS THE FRONT-END.

The front-end provides a convenient and easy way to maintain, manage, and update your CIDRAM installation. You can view, share, and download logfiles via the logs page, you can modify configuration via the configuration page, you can install and uninstall components via the updates page, and you can upload, download, and modify files in your vault via the file manager.

The front-end is disabled by default in order to prevent unauthorised access (unauthorised access could have significant consequences for your website and its security). Instructions for enabling it are included below this paragraph.

#### 4.1 HOW TO ENABLE THE FRONT-END.

1) Locate the `disable_frontend` directive inside `config.ini`, and set it to `false` (it will be `true` by default).

2) Access `loader.php` from your browser (e.g., `http://localhost/cidram/loader.php`).

3) Log in with the default username and password (admin/password).

Note: After you've logged in for the first time, in order to prevent unauthorised access to the front-end, you should immediately change your username and password! This is very important, because it's possible to upload arbitrary PHP code to your website via the front-end.

#### 4.2 HOW TO USE THE FRONT-END.

Instructions are provided on each page of the front-end, to explain the correct way to use it and its intended purpose. If you need further explanation or any special assistance, please contact support. Alternatively, there are some videos available on YouTube which could help by way of demonstration.


---


### 5. <a name="SECTION5"></a>FILES INCLUDED IN THIS PACKAGE

The following is a list of all of the files that should have been included in the archived copy of this script when you downloaded it, along with a short description of the purpose of these files.

File | Description
----|----
/_docs/ | Documentation directory (contains various files).
/_docs/readme.ar.md | Arabic documentation.
/_docs/readme.de.md | German documentation.
/_docs/readme.en.md | English documentation.
/_docs/readme.es.md | Spanish documentation.
/_docs/readme.fr.md | French documentation.
/_docs/readme.id.md | Indonesian documentation.
/_docs/readme.it.md | Italian documentation.
/_docs/readme.ja.md | Japanese documentation.
/_docs/readme.ko.md | Korean documentation.
/_docs/readme.nl.md | Dutch documentation.
/_docs/readme.pt.md | Portuguese documentation.
/_docs/readme.ru.md | Russian documentation.
/_docs/readme.ur.md | Urdu documentation.
/_docs/readme.vi.md | Vietnamese documentation.
/_docs/readme.zh-TW.md | Chinese (traditional) documentation.
/_docs/readme.zh.md | Chinese (simplified) documentation.
/vault/ | Vault directory (contains various files).
/vault/fe_assets/ | Front-end assets.
/vault/fe_assets/.htaccess | A hypertext access file (in this instance, to protect sensitive files belonging to the script from being accessed by non-authorised sources).
/vault/fe_assets/_accounts.html | An HTML template for the front-end accounts page.
/vault/fe_assets/_accounts_row.html | An HTML template for the front-end accounts page.
/vault/fe_assets/_cidr_calc.html | An HTML template for the CIDR calculator.
/vault/fe_assets/_cidr_calc_row.html | An HTML template for the CIDR calculator.
/vault/fe_assets/_config.html | An HTML template for the front-end configuration page.
/vault/fe_assets/_config_row.html | An HTML template for the front-end configuration page.
/vault/fe_assets/_files.html | An HTML template for the file manager.
/vault/fe_assets/_files_edit.html | An HTML template for the file manager.
/vault/fe_assets/_files_rename.html | An HTML template for the file manager.
/vault/fe_assets/_files_row.html | An HTML template for the file manager.
/vault/fe_assets/_home.html | An HTML template for the front-end homepage.
/vault/fe_assets/_ip_test.html | An HTML template for the IP test page.
/vault/fe_assets/_ip_test_row.html | An HTML template for the IP test page.
/vault/fe_assets/_ip_tracking.html | An HTML template for the IP tracking page.
/vault/fe_assets/_ip_tracking_row.html | An HTML template for the IP tracking page.
/vault/fe_assets/_login.html | An HTML template for the front-end login.
/vault/fe_assets/_logs.html | An HTML template for the front-end logs page.
/vault/fe_assets/_nav_complete_access.html | An HTML template for the front-end navigation links, for those with complete access.
/vault/fe_assets/_nav_logs_access_only.html | An HTML template for the front-end navigation links, for those with logs access only.
/vault/fe_assets/_updates.html | An HTML template for the front-end updates page.
/vault/fe_assets/_updates_row.html | An HTML template for the front-end updates page.
/vault/fe_assets/frontend.css | CSS style-sheet for the front-end.
/vault/fe_assets/frontend.dat | Database for the front-end (contains account information, session information, and the cache; only generated if the front-end is enabled and used).
/vault/fe_assets/frontend.html | The main HTML template file for the front-end.
/vault/fe_assets/icons.php | Icons handler (used by the front-end file manager).
/vault/fe_assets/pips.php | Pips handler (used by the front-end file manager).
/vault/lang/ | Contains CIDRAM language data.
/vault/lang/.htaccess | A hypertext access file (in this instance, to protect sensitive files belonging to the script from being accessed by non-authorised sources).
/vault/lang/lang.ar.cli.php | Arabic language data for CLI.
/vault/lang/lang.ar.fe.php | Arabic language data for the front-end.
/vault/lang/lang.ar.php | Arabic language data.
/vault/lang/lang.de.cli.php | German language data for CLI.
/vault/lang/lang.de.fe.php | German language data for the front-end.
/vault/lang/lang.de.php | German language data.
/vault/lang/lang.en.cli.php | English language data for CLI.
/vault/lang/lang.en.fe.php | English language data for the front-end.
/vault/lang/lang.en.php | English language data.
/vault/lang/lang.es.cli.php | Spanish language data for CLI.
/vault/lang/lang.es.fe.php | Spanish language data for the front-end.
/vault/lang/lang.es.php | Spanish language data.
/vault/lang/lang.fr.cli.php | French language data for CLI.
/vault/lang/lang.fr.fe.php | French language data for the front-end.
/vault/lang/lang.fr.php | French language data.
/vault/lang/lang.hi.cli.php | Hindi language data for CLI.
/vault/lang/lang.hi.fe.php | Hindi language data for the front-end.
/vault/lang/lang.hi.php | Hindi language data.
/vault/lang/lang.id.cli.php | Indonesian language data for CLI.
/vault/lang/lang.id.fe.php | Indonesian language data for the front-end.
/vault/lang/lang.id.php | Indonesian language data.
/vault/lang/lang.it.cli.php | Italian language data for CLI.
/vault/lang/lang.it.fe.php | Italian language data for the front-end.
/vault/lang/lang.it.php | Italian language data.
/vault/lang/lang.ja.cli.php | Japanese language data for CLI.
/vault/lang/lang.ja.fe.php | Japanese language data for the front-end.
/vault/lang/lang.ja.php | Japanese language data.
/vault/lang/lang.ko.cli.php | Korean language data for CLI.
/vault/lang/lang.ko.fe.php | Korean language data for the front-end.
/vault/lang/lang.ko.php | Korean language data.
/vault/lang/lang.nl.cli.php | Dutch language data for CLI.
/vault/lang/lang.nl.fe.php | Dutch language data for the front-end.
/vault/lang/lang.nl.php | Dutch language data.
/vault/lang/lang.pt.cli.php | Portuguese language data for CLI.
/vault/lang/lang.pt.fe.php | Portuguese language data for the front-end.
/vault/lang/lang.pt.php | Portuguese language data.
/vault/lang/lang.ru.cli.php | Russian language data for CLI.
/vault/lang/lang.ru.fe.php | Russian language data for the front-end.
/vault/lang/lang.ru.php | Russian language data.
/vault/lang/lang.th.cli.php | Thai language data for CLI.
/vault/lang/lang.th.fe.php | Thai language data for the front-end.
/vault/lang/lang.th.php | Thai language data.
/vault/lang/lang.tr.cli.php | Turkish language data for CLI.
/vault/lang/lang.tr.fe.php | Turkish language data for the front-end.
/vault/lang/lang.tr.php | Turkish language data.
/vault/lang/lang.ur.cli.php | Urdu language data for CLI.
/vault/lang/lang.ur.fe.php | Urdu language data for the front-end.
/vault/lang/lang.ur.php | Urdu language data.
/vault/lang/lang.vi.cli.php | Vietnamese language data for CLI.
/vault/lang/lang.vi.fe.php | Vietnamese language data for the front-end.
/vault/lang/lang.vi.php | Vietnamese language data.
/vault/lang/lang.zh-tw.cli.php | Chinese (traditional) language data for CLI.
/vault/lang/lang.zh-tw.fe.php | Chinese (traditional) language data for the front-end.
/vault/lang/lang.zh-tw.php | Chinese (traditional) language data.
/vault/lang/lang.zh.cli.php | Chinese (simplified) language data for CLI.
/vault/lang/lang.zh.fe.php | Chinese (simplified) language data for the front-end.
/vault/lang/lang.zh.php | Chinese (simplified) language data.
/vault/.htaccess | A hypertext access file (in this instance, to protect sensitive files belonging to the script from being accessed by non-authorised sources).
/vault/.travis.php | Used by Travis CI for testing (not required for proper function of the script).
/vault/.travis.yml | Used by Travis CI for testing (not required for proper function of the script).
/vault/cache.dat | Cache data.
/vault/cidramblocklists.dat | Contains information relating to the optional country blocklists provided by Macmathan; Used by the updates feature provided by the front-end.
/vault/cli.php | CLI handler.
/vault/components.dat | Contains information relating to the various components of CIDRAM; Used by the updates feature provided by the front-end.
/vault/config.ini.RenameMe | Configuration file; Contains all the configuration options of CIDRAM, telling it what to do and how to operate correctly (rename to activate).
/vault/config.php | Configuration handler.
/vault/config.yaml | Configuration defaults file; Contains default configuration values for CIDRAM.
/vault/frontend.php | Front-end handler.
/vault/functions.php | Functions file (essential).
/vault/hashes.dat | Contains a list of accepted hashes (pertinent to the reCAPTCHA feature; only generated if the reCAPTCHA feature is enabled).
/vault/ignore.dat | Ignores file (used to specify which signature sections CIDRAM should ignore).
/vault/ipbypass.dat | Contains a list of IP bypasses (pertinent to the reCAPTCHA feature; only generated if the reCAPTCHA feature is enabled).
/vault/ipv4.dat | IPv4 signatures file (unwanted cloud services and non-human endpoints).
/vault/ipv4_bogons.dat | IPv4 signatures file (bogon/martian CIDRs).
/vault/ipv4_custom.dat.RenameMe | IPv4 custom signatures file (rename to activate).
/vault/ipv4_isps.dat | IPv4 signatures file (dangerous and spammy ISPs).
/vault/ipv4_other.dat | IPv4 signatures file (CIDRs for proxies, VPNs, and other miscellaneous unwanted services).
/vault/ipv6.dat | IPv6 signatures file (unwanted cloud services and non-human endpoints).
/vault/ipv6_bogons.dat | IPv6 signatures file (bogon/martian CIDRs).
/vault/ipv6_custom.dat.RenameMe | IPv6 custom signatures file (rename to activate).
/vault/ipv6_isps.dat | IPv6 signatures file (dangerous and spammy ISPs).
/vault/ipv6_other.dat | IPv6 signatures file (CIDRs for proxies, VPNs, and other miscellaneous unwanted services).
/vault/lang.php | Language handler.
/vault/modules.dat | Contains information relating to the CIDRAM modules; Used by the updates feature provided by the front-end.
/vault/outgen.php | Output generator.
/vault/php5.4.x.php | Polyfills for PHP 5.4.X (required for PHP 5.4.X backwards compatibility; safe to delete for newer PHP versions).
/vault/recaptcha.php | reCAPTCHA module.
/vault/rules_as6939.php | Custom rules file for AS6939.
/vault/rules_softlayer.php | Custom rules file for Soft Layer.
/vault/rules_specific.php | Custom rules file for some specific CIDRs.
/vault/salt.dat | Salt file (used by some peripheral functionality; only generated if required).
/vault/template_custom.html | Template file; Template for HTML output produced by the CIDRAM output generator.
/vault/template_default.html | Template file; Template for HTML output produced by the CIDRAM output generator.
/vault/themes.dat | Themes file; Used by the updates feature provided by the front-end.
/.gitattributes | A GitHub project file (not required for proper function of the script).
/Changelog.txt | A record of changes made to the script between different versions (not required for proper function of the script).
/composer.json | Composer/Packagist information (not required for proper function of the script).
/CONTRIBUTING.md | Information about how to contribute to the project.
/LICENSE.txt | A copy of the GNU/GPLv2 license (not required for proper function of the script).
/loader.php | Loader. This is what you're supposed to be hooking into (essential)!
/README.md | Project summary information.
/web.config | An ASP.NET configuration file (in this instance, to protect the `/vault` directory from being accessed by non-authorised sources in the event that the script is installed on a server based upon ASP.NET technologies).

---


### 6. <a name="SECTION6"></a>CONFIGURATION OPTIONS
The following is a list of the directives available to CIDRAM in the `config.ini` configuration file, along with a description of the purpose of these directives.

#### "general" (Category)
General CIDRAM configuration.

"logfile"
- Human readable file for logging all blocked access attempts. Specify a filename, or leave blank to disable.

"logfileApache"
- Apache-style file for logging all blocked access attempts. Specify a filename, or leave blank to disable.

"logfileSerialized"
- Serialised file for logging all blocked access attempts. Specify a filename, or leave blank to disable.

*Useful tip: If you want, you can append date/time information to the names of your logfiles by including these in the name: `{yyyy}` for complete year, `{yy}` for abbreviated year, `{mm}` for month, `{dd}` for day, `{hh}` for hour.*

*Examples:*
- *`logfile='logfile.{yyyy}-{mm}-{dd}-{hh}.txt'`*
- *`logfileApache='access.{yyyy}-{mm}-{dd}-{hh}.txt'`*
- *`logfileSerialized='serial.{yyyy}-{mm}-{dd}-{hh}.txt'`*

"truncate"
- Truncate logfiles when they reach a certain size? Value is the maximum size in B/KB/MB/GB/TB that a logfile may grow to before being truncated. The default value of 0KB disables truncation (logfiles can grow indefinitely). Note: Applies to individual logfiles! The size of logfiles is not considered collectively.

"timeOffset"
- If your server time doesn't match your local time, you can specify an offset here to adjust the date/time information generated by CIDRAM according to your needs. It's generally recommended instead to adjust the timezone directive in your `php.ini` file, but sometimes (such as when working with limited shared hosting providers) this isn't always possible to do, and so, this option is provided here. Offset is in minutes.
- Example (to add one hour): `timeOffset=60`

"timeFormat"
- The date/time notation format used by CIDRAM. Default = `{Day}, {dd} {Mon} {yyyy} {hh}:{ii}:{ss} {tz}`.

"ipaddr"
- Where to find the IP address of connecting requests? (Useful for services such as Cloudflare and the likes). Default = REMOTE_ADDR. WARNING: Don't change this unless you know what you're doing!

"forbid_on_block"
- Which headers should CIDRAM respond with when blocking requests? False/200 = 200 OK [Default]; True/403 = 403 Forbidden; 503 = 503 Service unavailable.

"silent_mode"
- Should CIDRAM silently redirect blocked access attempts instead of displaying the "Access Denied" page? If yes, specify the location to redirect blocked access attempts to. If no, leave this variable blank.

"lang"
- Specify the default language for CIDRAM.

"emailaddr"
- If you wish, you can supply an email address here to be given to users when they're blocked, for them to use as a point of contact for support and/or assistance for in the event of them being blocked mistakenly or in error. WARNING: Whatever email address you supply here will most certainly be acquired by spambots and scrapers during the course of its being used here, and so, it's strongly recommended that if you choose to supply an email address here, that you ensure that the email address you supply here is a disposable address and/or an address that you don't mind being spammed (in other words, you probably don't want to use your primary personal or primary business email addresses).

"disable_cli"
- Disable CLI mode? CLI mode is enabled by default, but can sometimes interfere with certain testing tools (such as PHPUnit, for example) and other CLI-based applications. If you don't need to disable CLI mode, you should ignore this directive. False = Enable CLI mode [Default]; True = Disable CLI mode.

"disable_frontend"
- Disable front-end access? Front-end access can make CIDRAM more manageable, but can also be a potential security risk, too. It's recommended to manage CIDRAM via the back-end whenever possible, but front-end access is provided for when it isn't possible. Keep it disabled unless you need it. False = Enable front-end access; True = Disable front-end access [Default].

"max_login_attempts"
- Maximum number of login attempts (front-end). Default = 5.

"FrontEndLog"
- File for logging front-end login attempts. Specify a filename, or leave blank to disable.

"ban_override"
- Override "forbid_on_block" when "infraction_limit" is exceeded? When overriding: Blocked requests return a blank page (template files aren't used). 200 = Don't override [Default]; 403 = Override with "403 Forbidden"; 503 = Override with "503 Service unavailable".

"log_banned_ips"
- Include blocked requests from banned IPs in the logfiles? True = Yes [Default]; False = No.

"default_dns"
- A comma delimited list of DNS servers to use for hostname lookups. Default = "8.8.8.8,8.8.4.4" (Google DNS). WARNING: Don't change this unless you know what you're doing!

"search_engine_verification"
- Attempt to verify requests from search engines? Verifying search engines ensures that they won't be banned as a result of exceeding the infraction limit (banning search engines from your website will usually have a negative effect upon your search engine ranking, SEO, etc). When verified, search engines can be blocked as per normal, but won't be banned. When not verified, it's possible for them to be banned as a result of exceeding the infraction limit. Additionally, search engine verification provides protection against fake search engine requests and against potentially malicious entities masquerading as search engines (such requests will be blocked when search engine verification is enabled). True = Enable search engine verification [Default]; False = Disable search engine verification.

"protect_frontend"
- Specifies whether the protections normally provided by CIDRAM should be applied to the front-end. True = Yes [Default]; False = No.

"disable_webfonts"
- Disable webfonts? True = Yes; False = No [Default].

#### "signatures" (Category)
Signatures configuration.

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

"modules"
- A list of module files to load after checking the IPv4/IPv6 signatures, delimited by commas.

"default_tracktime"
- How many seconds to track IPs banned by modules. Default = 604800 (1 week).

"infraction_limit"
- Maximum number of infractions an IP is allowed to incur before it is banned by IP tracking. Default = 10.

"track_mode"
- When should infractions be counted? False = When IPs are blocked by modules. True = When IPs are blocked for any reason.

#### "recaptcha" (Category)
Optionally, you can provide users with a way to bypass the "Access Denied" page by way of completing a reCAPTCHA instance, if you want to do so. This can help to mitigate some of the risks associated with false positives in those situations where we're not entirely sure whether a request has originated from a machine or a human.

Due to the risks associated with providing a way for end-users to bypass the "Access Denied" page, generally, I would advise against enabling this feature unless you feel it to be necessary to do so. Situations where it could be necessary: If your website has customers/users that need to have access to your website, and if this is something that can't be compromised on, but if those customers/users happen to be connecting from a hostile network that could potentially also be carrying undesirable traffic, and blocking this undesirable traffic is also something that can't be compromised on, in those particular no-win situations, the reCAPTCHA feature could come in handy as a means of allowing the desirable customers/users, while keeping out the undesirable traffic from the same network. That said though, given that the intended purpose of a CAPTCHA is to distinguish between humans and non-humans, the reCAPTCHA feature would only assist in these no-win situations if we're to assume that this undesirable traffic is non-human (e.g., spambots, scrapers, hacktools, automated traffic), as opposed to being undesirable human traffic (such as human spammers, hackers, et al).

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
- Log all reCAPTCHA attempts? If yes, specify the name to use for the logfile. If no, leave this variable blank.

*Useful tip: If you want, you can append date/time information to the names of your logfiles by including these in the name: `{yyyy}` for complete year, `{yy}` for abbreviated year, `{mm}` for month, `{dd}` for day, `{hh}` for hour.*

*Examples:*
- *`logfile='recaptcha.{yyyy}-{mm}-{dd}-{hh}.txt'`*

#### "template_data" (Category)
Directives/Variables for templates and themes.

Relates to the HTML output used to generate the "Access Denied" page. If you're using custom themes for CIDRAM, HTML output is sourced from the `template_custom.html` file, and otherwise, HTML output is sourced from the `template.html` file. Variables written to this section of the configuration file are parsed to the HTML output by way of replacing any variable names circumfixed by curly brackets found within the HTML output with the corresponding variable data. For example, where `foo="bar"`, any instance of `<p>{foo}</p>` found within the HTML output will become `<p>bar</p>`.

"theme"
- Default theme to use for CIDRAM.

"css_url"
- The template file for custom themes utilises external CSS properties, whereas the template file for the default theme utilises internal CSS properties. To instruct CIDRAM to use the template file for custom themes, specify the public HTTP address of your custom theme's CSS files using the `css_url` variable. If you leave this variable blank, CIDRAM will use the template file for the default theme.

---


### 7. <a name="SECTION7"></a>SIGNATURE FORMAT

#### 7.0 BASICS

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

The signature files for CIDRAM SHOULD use Unix-style linebreaks (`%0A`, or `\n`)! Other types/styles of linebreaks (e.g., Windows `%0D%0A` or `\r\n` linebreaks, Mac `%0D` or `\r` linebreaks, etc) MAY be used, but are NOT preferred. Non-Unix-style linebreaks will be normalised to Unix-style linebreaks by the script.

Precise and correct CIDR notation is required, otherwise the script will NOT recognise the signatures. Additionally, all the CIDR signatures of this script MUST begin with an IP address whose IP number can divide evenly into the block division represented by its CIDR block size (e.g., if you wanted to block all IPs from `10.128.0.0` to `11.127.255.255`, `10.128.0.0/8` would NOT be recognised by the script, but `10.128.0.0/9` and `11.0.0.0/9` used in conjunction, WOULD be recognised by the script).

Anything in the signature files not recognised as a signature nor as signature-related syntax by the script will be IGNORED, therefore meaning that you can safely put any non-signature data that you want into the signature files without breaking them and without breaking the script. Comments are acceptable in the signature files, and no special formatting is required for them. Shell-style hashing for comments is preferred, but not enforced; Functionally, it makes no difference to the script whether or not you choose to use Shell-style hashing for comments, but using Shell-style hashing helps IDEs and plain-text editors to correctly highlight the various parts of the signature files (and so, Shell-style hashing can assist as a visual aid while editing).

The possible values of `[Function]` are as follows:
- Run
- Whitelist
- Greylist
- Deny

If "Run" is used, when the signature is triggered, the script will attempt to execute (using a `require_once` statement) an external PHP script, specified by the `[Param]` value (the working directory should be the "/vault/" directory of the script).

Example: `127.0.0.0/8 Run example.php`

This can be useful if you want to execute some specific PHP code for some specific IPs and/or CIDRs.

If "Whitelist" is used, when the signature is triggered, the script will reset all detections (if there's been any detections) and break the test function. `[Param]` is ignored. This function is the equivalent of whitelisting a particular IP or CIDR from being detected.

Example: `127.0.0.1/32 Whitelist`

If "Greylist" is used, when the signature is triggered, the script will reset all detections (if there's been any detections) and skip to the next signature file to continue processing. `[Param]` is ignored.

Example: `127.0.0.1/32 Greylist`

If "Deny" is used, when the signature is triggered, assuming no whitelist signature has been triggered for the given IP address and/or given CIDR, access to the protected page will be denied. "Deny" is what you'll want to use to actually block an IP address and/or CIDR range. When any signatures are triggered that make use of "Deny", the "Access Denied" page of the script will be generated and the request to the protected page killed.

The `[Param]` value accepted by "Deny" will be parsed to the "Access Denied" page output, supplied to the client/user as the cited reason for their access to the requested page being denied. It can be either a short and simple sentence, explaining why you've chosen to block them (anything should suffice, even a simple "I don't want you on my website"), or one of a small handful of shorthand words supplied by the script, that if used, will be replaced by the script with a pre-prepared explanation of why the client/user has been blocked.

The pre-prepared explanations have L10N support and can be translated by the script based upon the language you specify to the `lang` directive of the script configuration. Additionally, you can instruct the script to ignore "Deny" signatures based upon their `[Param]` value (if they're using these shorthand words) via the directives specified by the script configuration (each shorthand word has a corresponding directive to either process the corresponding signatures or to ignore them). `[Param]` values that don't use these shorthand words, however, don't have L10N support and therefore WON'T be translated by the script, and additionally, aren't directly controllable by the script configuration.

The available shorthand words are:
- Bogon
- Cloud
- Generic
- Proxy
- Spam

#### 7.1 TAGS

If you want to split your custom signatures into individual sections, you can identify these individual sections to the script by adding a "section tag" immediately after the signatures of each section, along with the name of your signature section (see the example below).

```
# Section 1.
1.2.3.4/32 Deny Bogon
2.3.4.5/32 Deny Cloud
4.5.6.7/32 Deny Generic
5.6.7.8/32 Deny Spam
6.7.8.9/32 Deny Proxy
Tag: Section 1
```

To break section tagging and to ensure that tags aren't incorrectly identified to signature sections from earlier in the signature files, simply ensure that there are at least two consecutive linebreaks between your tag and your earlier signature sections. Any untagged signatures will default to either "IPv4" or "IPv6" (depending on which types of signatures are being triggered).

```
1.2.3.4/32 Deny Bogon
2.3.4.5/32 Deny Cloud

4.5.6.7/32 Deny Generic
5.6.7.8/32 Deny Spam
Tag: Section 1
```

In the above example `1.2.3.4/32` and `2.3.4.5/32` will be tagged as "IPv4", whereas `4.5.6.7/32` and `5.6.7.8/32` will be tagged as "Section 1".

If you want signatures to expire after some time, in a similar manner to section tags, you can use an "expiry tag" to specify when signatures should cease to be valid. Expiry tags use the format "YYYY.MM.DD" (see the example below).

```
# Section 1.
1.2.3.4/32 Deny Generic
2.3.4.5/32 Deny Generic
Expires: 2016.12.31
```

Section tags and expiry tags may be used in conjunction, and both are optional (see the example below).

```
# Example Section.
1.2.3.4/32 Deny Generic
Tag: Example Section
Expires: 2016.12.31
```

#### 7.2 YAML

##### 7.2.0 YAML BASICS

A simplified form of YAML markup may be used in signature files for the purpose of defining behaviours and settings specific to individual signature sections. This may be useful if you want the value of your configuration directives to differ on the basis of individual signatures and signature sections (for example; if you want to supply an email address for support tickets for any users blocked by one particular signature, but don't want to supply an email address for support tickets for users blocked by any other signatures; if you want some specific signatures to trigger a page redirect; if you want to mark a signature section for use with reCAPTCHA; if you want to log blocked access attempts to separate files on the basis of individual signatures and/or signature sections).

Use of YAML markup in the signature files is entirely optional (i.e., you may use it if you wish to do so, but you are not required to do so), and is able to leverage most (but not all) configuration directives.

Note: YAML markup implementation in CIDRAM is very simplistic and very limited; It is intended to fulfill requirements specific to CIDRAM in a manner that has the familiarity of YAML markup, but neither follows nor complies with official specifications (and therefore won't behave in the same way as more thorough implementations elsewhere, and may not be appropriate for other projects elsewhere).

In CIDRAM, YAML markup segments are identified to the script by three dashes ("---"), and terminate alongside their containing signature sections by double-linebreaks. A typical YAML markup segment within a signature section consists of three dashes on a line immediately after the list of CIDRS and any tags, followed by a two dimensional list of key-value pairs (first dimension, configuration directive categories; second dimension, configuration directives) for which configuration directives should be modified (and to which values) whenever a signature within that signature section is triggered (see the examples below).

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

##### 7.2.1 HOW TO "SPECIALLY MARK" SIGNATURE SECTIONS FOR USE WITH reCAPTCHA

When "usemode" is 0 or 1, signature sections don't need to be "specially marked" for use with reCAPTCHA (because they already either will or won't use reCAPTCHA, depending on this setting).

When "usemode" is 2, to "specially mark" signature sections for use with reCAPTCHA, an entry is included in the YAML segment for that signature section (see the example below).

```
# This section will use reCAPTCHA.
1.2.3.4/32 Deny Generic
2.3.4.5/32 Deny Generic
Tag: reCAPTCHA-Enabled
---
recaptcha:
 enabled: true
```

Note: A reCAPTCHA instance will ONLY be offered to the user if reCAPTCHA is enabled (either with "usemode" as 1, or "usemode" as 2 with "enabled" as true), and if exactly ONE signature has been triggered (no more, no less; if multiple signatures are triggered, a reCAPTCHA instance will NOT be offered).

#### 7.3 AUXILIARY

In addition, if you want CIDRAM to completely ignore some specific sections within any of the signature files, you can use the `ignore.dat` file to specify which sections to ignore. On a new line, write `Ignore`, followed by a space, followed by the name of the section that you want CIDRAM to ignore (see the example below).

```
Ignore Section 1
```

Refer to the custom signature files for more information.

---


### 8. <a name="SECTION8"></a>FREQUENTLY ASKED QUESTIONS (FAQ)

#### What is a "signature"?

In the context of CIDRAM, a "signature" refers to data that acts as an indicator/identifier for something specific that we're looking for, usually an IP address or CIDR, and includes some instruction for CIDRAM, telling it the best way to respond when it encounters what we're looking for. A typical signature for CIDRAM looks something like this:

`1.2.3.4/32 Deny Generic`

Often (but not always), signatures will bundled together in groups, forming "signature sections", often accompanied by comments, markup, and/or related metadata that can be used to provide additional context for the signatures and/or further instruction.

#### <a name="WHAT_IS_A_CIDR"></a>What is a "CIDR"?

"CIDR" is an acronym for "Classless Inter-Domain Routing" *[[1](https://en.wikipedia.org/wiki/Classless_Inter-Domain_Routing), [2](http://whatismyipaddress.com/cidr)]*, and it's this acronym that's used as part of the name for this package, "CIDRAM", which is an acronym for "Classless Inter-Domain Routing Access Manager".

However, in the context of CIDRAM (such as, within this documentation, within discussions relating to CIDRAM, or within the CIDRAM language data), whenever a "CIDR" (singular) or "CIDRs" (plural) is mentioned or referred to (and thus whereby we use these words as nouns in their own right, as opposed to as acronyms), what's intended and meant by this is a subnet (or subnets), expressed using CIDR notation. The reason that CIDR (or CIDRs) is used instead of subnet (or subnets) is to make it clear that it's specifically subnets expressed using CIDR notation that's being referred to (because CIDR notation is just one of several different ways that subnets can be expressed). CIDRAM could, therefore, be considered a "subnet access manager".

Although this dual meaning of "CIDR" may present some ambiguity in some cases, this explanation, along with the context provided, should help to resolve such ambiguity.

#### What is a "false positive"?

The term "false positive" (*alternatively: "false positive error"; "false alarm"*), described very simply, and in a generalised context, is used when testing for a condition, to refer to the results of that test, when the results are positive (i.e., the condition is determined to be "positive", or "true"), but are expected to be (or should have been) negative (i.e., the condition, in reality, is "negative", or "false"). A "false positive" could be considered analogous to "crying wolf" (wherein the condition being tested is whether there's a wolf near the herd, the condition is "false" in that there's no wolf near the herd, and the condition is reported as "positive" by the shepherd by way of calling "wolf, wolf"), or analogous to situations in medical testing wherein a patient is diagnosed as having some illness or disease, when in reality, they have no such illness or disease.

Related outcomes when testing for a condition can be described using the terms "true positive", "true negative" and "false negative". A "true positive" refers to when the results of the test and the actual state of the condition are both true (or "positive"), and a "true negative" refers to when the results of the test and the actual state of the condition are both false (or "negative"); A "true positive" or a "true negative" is considered to be a "correct inference". The antithesis of a "false positive" is a "false negative"; A "false negative" refers to when the results of the test are negative (i.e., the condition is determined to be "negative", or "false"), but are expected to be (or should have been) positive (i.e., the condition, in reality, is "positive", or "true").

In the context of CIDRAM, these terms refer to the signatures of CIDRAM and what/whom they block. When CIDRAM blocks an IP address due to bad, outdated or incorrect signatures, but shouldn't have done so, or when it does so for the wrong reasons, we refer to this event as a "false positive". When CIDRAM fails to block an IP address that should have been blocked, due to unforeseen threats, missing signatures or shortfalls in its signatures, we refer to this event as a "missed detection" (which is analogous to a "false negative").

This can be summarised by the table below:

&nbsp; | CIDRAM should *NOT* block an IP address | CIDRAM *SHOULD* block an IP address
---|---|---
CIDRAM does *NOT* block an IP address | True negative (correct inference) | Missed detection (analogous to false negative)
CIDRAM *DOES* block an IP address | __False positive__ | True positive (correct inference)

#### Can CIDRAM block entire countries?

Yes. The easiest way to achieve this would be to install some of the optional country blocklists provided by Macmathan. This can be done with a few simple clicks directly from the front-end updates page, or, if you'd prefer for the front-end to remain disabled, by downloading them directly from the **[optional blocklists download page](https://macmathan.info/blocklists)**, uploading them to the vault, and citing their names in the relevant configuration directives.

#### How frequently are signatures updated?

Update frequency varies depending on the signature files in question. All maintainers for CIDRAM signature files generally try to keep their signatures as up-to-date as is possible, but as all of us have various other commitments, our lives outside the project, and as none of us are financially compensated (i.e., paid) for our efforts on the project, a precise update schedule can't be guaranteed. Generally, signatures are updated whenever there's enough time to update them, and generally, maintainers try to prioritise based on necessity and on how frequently changes occur among ranges. Assistance is always appreciated if you're willing to offer any.

#### I've encountered a problem while using CIDRAM and I don't know what to do about it! Please help!

- Are you using the latest version of the software? Are you using the latest versions of your signature files? If the answer to either of these two questions is no, try to update everything first, and check whether the problem persists. If it persists, continue reading.
- Have you checked through all the documentation? If not, please do so. If the problem can't be solved using the documentation, continue reading.
- Have you checked the **[issues page](https://github.com/CIDRAM/CIDRAM/issues)**, to see whether the problem has been mentioned before? If it's been mentioned before, check whether any suggestions, ideas, and/or solutions were provided, and follow as per necessary to try to resolve the problem.
- Have you checked the **[CIDRAM support forum provided by Spambot Security](http://www.spambotsecurity.com/forum/viewforum.php?f=61)**, to see whether the problem has been mentioned before? If it's been mentioned before, check whether any suggestions, ideas, and/or solutions were provided, and follow as per necessary to try to resolve the problem.
- If the problem still persists, please let us know about it by creating a new issue on the issues page or by starting a new discussion on the support forum.

#### I've been blocked by CIDRAM from a website that I want to visit! Please help!

CIDRAM provides a means for website owners to block undesirable traffic, but it's the responsibility of website owners to decide for themselves how they want to use CIDRAM. In case of the false positives relating to the signature files normally included with CIDRAM, corrections can be made, but in regards to being unblocked from specific websites, you'll need to take that up with the owners of the websites in question. In cases where corrections are made, at the very least, they'll need to update their signature files and/or installation, and in other cases (such as, for example, where they've modified their installation, created their own custom signatures, etc), the responsibility to solve your problem is entirely theirs, and is entirely outside our control.

#### I want to use CIDRAM with a PHP version older than 5.4.0; Can you help?

No. PHP 5.4.0 reached official EoL ("End of Life") in 2014, and extended security support was terminated in 2015. As of writing this, it is 2017, and PHP 7.1.0 is already available. At this time, support is provided for using CIDRAM with PHP 5.4.0 and all available newer PHP versions, but if you try to use CIDRAM with any older PHP versions, support won't be provided.

*See also: [Compatibility Charts](https://maikuolan.github.io/Compatibility-Charts/).*

#### Can I use a single CIDRAM installation to protect multiple domains?

Yes. CIDRAM installations are not naturally locked to specific domains, and can therefore be used to protect multiple domains. Generally, we refer to CIDRAM installations protecting only one domain as "single-domain installations", and we refer to CIDRAM installations protecting multiple domains and/or sub-domains as "multi-domain installations". If you operate a multi-domain installation and need to use different sets of signature files for different domains, or need CIDRAM to be configured differently for different domains, it's possible to do this. After loading the configuration file (`config.ini`), CIDRAM will check for the existence of a "configuration overrides file" specific to the domain (or sub-domain) being requested (`the-domain-being-requested.tld.config.ini`), and if found, any configuration values defined by the configuration overrides file will be used for the execution instance instead of the configuration values defined by the configuration file. Configuration overrides files are identical to the configuration file, and at your discretion, may contain either the entirety of all configuration directives available to CIDRAM, or whichever small subsection required which differs from the values normally defined by the configuration file. Configuration overrides files are named according to the domain that they are intended for (so, for example, if you need a configuration overrides file for the domain, `http://www.some-domain.tld/`, its configuration overrides file should be named as `some-domain.tld.config.ini`, and should be placed within the vault alongside the configuration file, `config.ini`). The domain name for the execution instance is derived from the `HTTP_HOST` header of the request; "www" is ignored.

#### I don't want to mess around with installing this and getting it to work with my website; Can I just pay you to do it all for me?

Maybe. This is considered on a case-by-case basis. Let us know what you need, what you're offering, and we'll let you know whether we can help.

#### Can I hire you or any of the developers of this project for private work?

*See above.*

#### I need specialist modifications, customisations, etc; Can you help?

*See above.*

#### I'm a developer, website designer, or programmer. Can I accept or offer work relating to this project?

Yes. Our license does not prohibit this.

#### I want to contribute to the project; Can I do this?

Yes. Contributions to the project are very welcome. Please see "CONTRIBUTING.md" for more information.

#### Recommended values for "ipaddr".

Value | Using
---|---
`HTTP_INCAP_CLIENT_IP` | Incapsula reverse proxy.
`HTTP_CF_CONNECTING_IP` | Cloudflare reverse proxy.
`CF-Connecting-IP` | Cloudflare reverse proxy (alternative; if the above doesn't work).
`HTTP_X_FORWARDED_FOR` | Cloudbric reverse proxy.
`X-Forwarded-For` | [Squid reverse proxy](http://www.squid-cache.org/Doc/config/forwarded_for/).
*Defined by server configuration.* | [Nginx reverse proxy](https://www.nginx.com/resources/admin-guide/reverse-proxy/).
`REMOTE_ADDR` | No reverse proxy (default value).

---


Last Updated: 22 July 2017 (2017.07.22).
