[![v1: PHP >= 5.4](https://img.shields.io/badge/v1-PHP%20%3E%3D%205.4-8892bf.svg)](https://maikuolan.github.io/Compatibility-Charts/)
[![v2~v3: PHP >= 7.2](https://img.shields.io/badge/v2%7Ev3-PHP%20%3E%3D%207.2-8892bf.svg)](https://maikuolan.github.io/Compatibility-Charts/)
[![License: GPL v2](https://img.shields.io/badge/License-GPL%20v2-blue.svg)](https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html)
[![PRs Welcome](https://img.shields.io/badge/PRs-Welcome-brightgreen.svg)](http://makeapullrequest.com)

## **What is CIDRAM?**

CIDRAM (Classless Inter-Domain Routing Access Manager) is a PHP script designed to protect websites by blocking requests originating from IP addresses regarded as being sources of undesirable traffic, including (but not limited to) traffic from non-human access endpoints, cloud services, spambots, scrapers, etc. It does this by calculating the possible CIDRs of the IP addresses supplied from inbound requests and then attempting to match these possible CIDRs against its signature files (these signature files contain lists of CIDRs of IP addresses regarded as being sources of undesirable traffic); If matches are found, the requests are blocked.

---


### Features:
- Licensed as [GNU General Public License version 2.0](https://github.com/CIDRAM/CIDRAM/blob/v2/LICENSE.txt) (GPLv2).
- Easy to install, easy to customise, easy to use.
- Works for any system with PHP+PCRE installed, regardless of OS (PHP+PCRE required).
- Fully configurable based on your needs.
- Ideal solution for websites and forum systems using shared hosting services.
- Does NOT require shell access.
- Does NOT require administrative privileges.
- Good, strong, stable support base.

---


### Documentation:
- **[English](https://github.com/CIDRAM/Docs/blob/master/v2-and-earlier/readme.en.md)**
- **[العربية](https://github.com/CIDRAM/Docs/blob/master/v2-and-earlier/readme.ar.md)**
- **[Deutsch](https://github.com/CIDRAM/Docs/blob/master/v2-and-earlier/readme.de.md)**
- **[Español](https://github.com/CIDRAM/Docs/blob/master/v2-and-earlier/readme.es.md)**
- **[Français](https://github.com/CIDRAM/Docs/blob/master/v2-and-earlier/readme.fr.md)**
- **[Bahasa Indonesia](https://github.com/CIDRAM/Docs/blob/master/v2-and-earlier/readme.id.md)**
- **[Italiano](https://github.com/CIDRAM/Docs/blob/master/v2-and-earlier/readme.it.md)**
- **[日本語](https://github.com/CIDRAM/Docs/blob/master/v2-and-earlier/readme.ja.md)**
- **[한국어](https://github.com/CIDRAM/Docs/blob/master/v2-and-earlier/readme.ko.md)**
- **[Nederlandse](https://github.com/CIDRAM/Docs/blob/master/v2-and-earlier/readme.nl.md)**
- **[Português](https://github.com/CIDRAM/Docs/blob/master/v2-and-earlier/readme.pt.md)**
- **[Русский](https://github.com/CIDRAM/Docs/blob/master/v2-and-earlier/readme.ru.md)**
- **[اردو](https://github.com/CIDRAM/Docs/blob/master/v2-and-earlier/readme.ur.md)**
- **[Tiếng Việt](https://github.com/CIDRAM/Docs/blob/master/v2-and-earlier/readme.vi.md)**
- **[中文（简体）](https://github.com/CIDRAM/Docs/blob/master/v2-and-earlier/readme.zh-Hans.md)**
- **[中文（傳統）](https://github.com/CIDRAM/Docs/blob/master/v2-and-earlier/readme.zh-Hant.md)**

[\[CONTRIBUTING.md\] **Want to help?**](https://github.com/CIDRAM/.github/blob/master/CONTRIBUTING.md)

---


Last Updated: 1 July 2024 (2024.07.01).
