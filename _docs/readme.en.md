## Documentation for CIDRAM (English).

### Contents
- 1. [PREAMBLE](#SECTION1)
- 2. [HOW TO INSTALL](#SECTION2)
- 3. [HOW TO USE](#SECTION3)
- 4. [FILES INCLUDED IN THIS PACKAGE](#SECTION4)
- 5. [CONFIGURATION OPTIONS](#SECTION5)
- 6. [SIGNATURE FORMAT](#SECTION6)

---


###1. <a name="SECTION1"></a>PREAMBLE

CIDRAM (Classless Inter-Domain Routing Access Manager) is a PHP script designed to protect websites by blocking requests originating from IP addresses regarded as being sources of undesirable traffic, including (but not limited to) traffic from non-human access endpoints, cloud services, spambots, scrapers, etc. It does this by calculating the possible CIDRs of the IP addresses supplied from inbound requests and then attempting to match these possible CIDRs against its signature files (these signature files contain lists of CIDRs of IP addresses regarded as being sources of undesirable traffic); If matches are found, the requests are blocked.

CIDRAM COPYRIGHT 2016 and beyond GNU/GPLv2 by Caleb M (Maikuolan).

This script is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version. This script is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details, located in the `LICENSE.txt` file and available also from:
- <http://www.gnu.org/licenses/>.
- <http://opensource.org/licenses/>.

This document and its associated package can be downloaded for free from [GitHub](https://github.com/Maikuolan/CIDRAM/).

---


###2. <a name="SECTION2"></a>HOW TO INSTALL

I hope to streamline this process by making an installer at some point in the not too distant future, but until then, follow these instructions to get CIDRAM working on *most systems and CMS:

1) By your reading this, I'm assuming you've already downloaded an archived copy of the script, decompressed its contents and have it sitting somewhere on your local machine. From here, you'll want to work out where on your host or CMS you want to place those contents. A directory such as `/public_html/cidram/` or similar (though, it doesn't matter which you choose, so long as it's something secure and something you're happy with) will suffice. *Before you begin uploading, read on..*

2) Optionally (strongly recommended for advanced users, but not recommended for beginners or for the inexperienced), open `config.ini` (located inside `vault`) - This file contains all the directives available for CIDRAM. Above each option should be a brief comment describing what it does and what it's for. Adjust these options as you see fit, as per whatever is appropriate for your particular setup. Save file, close.

3) Upload the contents (CIDRAM and its files) to the directory you'd decided on earlier (you don't need to include the `*.txt`/`*.md` files, but mostly, you should upload everything).

4) CHMOD the `vault` directory to "777". The main directory storing the contents (the one you chose earlier), usually, can be left alone, but CHMOD status should be checked if you've had permissions issues in the past on your system (by default, should be something like "755").

5) Next, you'll need to "hook" CIDRAM to your system or CMS. There are several different ways you can "hook" scripts such as CIDRAM to your system or CMS, but the easiest is to simply include the script at the beginning of a core file of your system or CMS (one that'll generally always be loaded when someone accesses any page across your website) using a `require` or `include` statement. Usually, this'll be something stored in a directory such as `/includes`, `/assets` or `/functions`, and will often be named something like `init.php`, `common_functions.php`, `functions.php` or similar. You'll have to work out which file this is for your situation; If you encounter difficulties in working this out for yourself, visit the CIDRAM issues page on GitHub. To do this [to use `require` or `include`], insert the following line of code to the very beginning of that core file, replacing the string contained inside the quotation marks with the exact address of the `loader.php` file (local address, not the HTTP address; it'll look similar to the vault address mentioned earlier).

`<?php require '/user_name/public_html/cidram/loader.php'; ?>`

Save file, close, reupload.

-- OR ALTERNATIVELY --

If you're using an Apache webserver and if you have access to `php.ini`, you can use the `auto_prepend_file` directive to prepend CIDRAM whenever any PHP request is made. Something like:

`auto_prepend_file = "/user_name/public_html/cidram/loader.php"`

Or this in the `.htaccess` file:

`php_value auto_prepend_file "/user_name/public_html/cidram/loader.php"`

6) That's everything! :-)

---


###3. <a name="SECTION3"></a>HOW TO USE

CIDRAM should automatically block undesirable requests to your website without requiring any manual assistance, aside from its initial installation.

Updating is done manually, and you can customise your configuration and customise which CIDRs are blocked by modifying your configuration file and/or your signature files.

If you encounter any false positives, please contact me to let me know about it.

---


###5. <a name="SECTION4"></a>FILES INCLUDED IN THIS PACKAGE

The following is a list of all of the files that should have been included in the archived copy of this script when you downloaded it, along with a short description of the purpose of these files.

File | Description
----|----
/.gitattributes | A GitHub project file (not required for proper function of script).
/Changelog.txt | A record of changes made to the script between different versions (not required for proper function of script).
/composer.json | Composer/Packagist information (not required for proper function of script).
/LICENSE.txt | A copy of the GNU/GPLv2 license.
/loader.php | Loader. This is what you're supposed to be hooking into (essential)!
/README.md | Project summary information.
/web.config | An ASP.NET configuration file (in this instance, to protect the `/vault` directory from being accessed by non-authorised sources in the event that the script is installed on a server based upon ASP.NET technologies).
/_docs/ | Documentation directory (contains various files).
/_docs/readme.en.md | English documentation.
/_docs/readme.es.md | Spanish documentation.
/_docs/readme.fr.md | French documentation.
/_docs/readme.id.md | Indonesian documentation.
/vault/ | Vault directory (contains various files).
/vault/.htaccess | A hypertext access file (in this instance, to protect sensitive files belonging to the script from being accessed by non-authorised sources).
/vault/cache.dat | Cache data.
/vault/cli.inc | CLI handler.
/vault/config.inc | Configuration handler.
/vault/config.ini | Configuration file; Contains all the configuration options of CIDRAM, telling it what to do and how to operate correctly (essential)!
/vault/functions.inc | Functions file (essential).
/vault/ipv4.dat | IPv4 signatures file.
/vault/ipv4_custom.dat | IPv4 custom signatures file.
/vault/ipv6.dat | IPv6 signatures file.
/vault/ipv6_custom.dat | IPv6 custom signatures file.
/vault/lang.inc | Language handler.
/vault/lang/ | Contains CIDRAM language data.
/vault/lang/.htaccess | A hypertext access file (in this instance, to protect sensitive files belonging to the script from being accessed by non-authorised sources).
/vault/lang/lang.en.inc | English language data.
/vault/lang/lang.es.inc | Spanish language data.
/vault/lang/lang.fr.inc | French language data.
/vault/lang/lang.id.inc | Indonesian language data.
/vault/lang/lang.it.inc | Italian language data.
/vault/lang/lang.nl.inc | Dutch language data.
/vault/lang/lang.pt.inc | Portuguese language data.
/vault/lang/lang.zh-TW.inc | Chinese (Traditional) language data.
/vault/lang/lang.zh.inc | Chinese (Simplified) language data.
/vault/outgen.inc | Output generator.
/vault/template.html | Template file; Template for HTML output produced by the CIDRAM output generator.
/vault/template_custom.html | Template file; Template for HTML output produced by the CIDRAM output generator.
/vault/rules_as6939.inc | Custom rules file for AS6939.
/vault/rules_softlayer.inc | Custom rules file for Soft Layer.

---


###6. <a name="SECTION5"></a>CONFIGURATION OPTIONS
The following is a list of the directives available to CIDRAM in the `config.ini` configuration file, along with a description of the purpose of these directives.

####"general" (Category)
General CIDRAM configuration.

"logfile"
- Filename of file to log all blocked access attempts to. Specify a filename, or leave blank to disable.

"ipaddr"
- Where to find the IP address of connecting requests? (Useful for services such as Cloudflare and the likes) Default = REMOTE_ADDR. WARNING: Don't change this unless you know what you're doing!

"forbid_on_block"
- Should CIDRAM respond with 403 headers to blocked requests, or stick with the usual 200 OK? False = No (200) [Default]; True = Yes (403).

"lang"
- Specify the default language for CIDRAM.

"emailaddr"
- If you wish, you can supply an email address here to be given to users when they're blocked, for them to use as a point of contact for support and/or assistance for in the event of them being blocked mistakenly or in error. WARNING: Whatever email address you supply here will most certainly be acquired by spambots and scrapers during the course of its being used here, and so, it's strongly recommended that if you choose to supply an email address here, that you ensure that the email address you supply here is a disposable address and/or an address that you don't mind being spammed (in other words, you probably don't want to use your primary personal or primary business email addresses).

####"signatures" (Category)
Signatures configuration.

"block_cloud"
- Block CIDRs identified as belonging to webhosting/cloud services? If you operate an API service from your website or if you expect other websites to connect to your website, this should be set to false. If you don't, then, this directive should be set to true.

"block_bogons"
- Block bogon/martian CIDRs? If you expect connections to your website from within your local network, from localhost, or from your LAN, this directive should be set to false. If you don't expect these such connections, this directive should be set to true.

"block_generic"
- Block CIDRs generally recommended for blacklisting? This covers any signatures that aren't marked as being part of any of the other more specific signature categories.

"block_spam"
- Block CIDRs identified as being high-risk for spam? Unless you experience problems when doing so, generally, this should always be set to true.

---


###7. <a name="SECTION6"></a>SIGNATURE FORMAT

A description of the format and structure of the signatures used by CIDRAM can be found documented in plain-text within either of the two custom signature files. Please refer to that documentation to learn more about the format and structure of the signatures of CIDRAM.

---


Last Updated: 2nd March 2016 (2016.03.02).
