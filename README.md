[![Join the chat at https://gitter.im/CIDRAM/Lobby](https://badges.gitter.im/CIDRAM/Lobby.svg)](https://gitter.im/CIDRAM/Lobby)
[![v1: PHP >= 5.4.0](https://img.shields.io/badge/v1-PHP%20%3E%3D%205.4.0-8892bf.svg)](https://maikuolan.github.io/Compatibility-Charts/)
[![v2: PHP >= 7.2.0](https://img.shields.io/badge/v2-PHP%20%3E%3D%207.2.0-8892bf.svg)](https://maikuolan.github.io/Compatibility-Charts/)
[![License: GPL v2](https://img.shields.io/badge/License-GPL%20v2-blue.svg)](https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html)
[![PRs Welcome](https://img.shields.io/badge/PRs-Welcome-brightgreen.svg)](http://makeapullrequest.com)

## **What is CIDRAM?**

CIDRAM (Classless Inter-Domain Routing Access Manager) is a PHP script designed to protect websites by blocking requests originating from IP addresses regarded as being sources of undesirable traffic, including (but not limited to) traffic from non-human access endpoints, cloud services, spambots, scrapers, etc. It does this by calculating the possible CIDRs of the IP addresses supplied from inbound requests and then attempting to match these possible CIDRs against its signature files (these signature files contain lists of CIDRs of IP addresses regarded as being sources of undesirable traffic); If matches are found, the requests are blocked.

---


### Features:
- Licensed as [GNU General Public License version 2.0](https://github.com/CIDRAM/CIDRAM/blob/v1/LICENSE.txt) (GPLv2).
- Easy to install, easy to customise, easy to use.
- Works for any system with PHP+PCRE installed, regardless of OS (PHP+PCRE required).
- Fully configurable based on your needs.
- Ideal solution for websites and forum systems using shared hosting services.
- Does NOT require shell access.
- Does NOT require administrative privileges.
- Good, strong, stable support base.

---


### Documentation:
- **[English](https://github.com/CIDRAM/Docs/blob/master/readme.en.md)**
- **[العربية](https://github.com/CIDRAM/Docs/blob/master/readme.ar.md)**
- **[Deutsch](https://github.com/CIDRAM/Docs/blob/master/readme.de.md)**
- **[Español](https://github.com/CIDRAM/Docs/blob/master/readme.es.md)**
- **[Français](https://github.com/CIDRAM/Docs/blob/master/readme.fr.md)**
- **[Bahasa Indonesia](https://github.com/CIDRAM/Docs/blob/master/readme.id.md)**
- **[Italiano](https://github.com/CIDRAM/Docs/blob/master/readme.it.md)**
- **[日本語](https://github.com/CIDRAM/Docs/blob/master/readme.ja.md)**
- **[한국어](https://github.com/CIDRAM/Docs/blob/master/readme.ko.md)**
- **[Nederlandse](https://github.com/CIDRAM/Docs/blob/master/readme.nl.md)**
- **[Português](https://github.com/CIDRAM/Docs/blob/master/readme.pt.md)**
- **[Русский](https://github.com/CIDRAM/Docs/blob/master/readme.ru.md)**
- **[اردو](https://github.com/CIDRAM/Docs/blob/master/readme.ur.md)**
- **[Tiếng Việt](https://github.com/CIDRAM/Docs/blob/master/readme.vi.md)**
- **[中文（简体）](https://github.com/CIDRAM/Docs/blob/master/readme.zh.md)**
- **[中文（傳統）](https://github.com/CIDRAM/Docs/blob/master/readme.zh-tw.md)**

[\[CONTRIBUTING.md\] **Want to help?**](https://github.com/CIDRAM/.github/blob/master/CONTRIBUTING.md)

---


### Current major version development status:

⬇Stage reached⬇ ➡Major version➡ | v0 | v1-v2 | v3
:--|:-:|:-:|:-:
Pre-Alpha<em><br />- Exploring early concepts/ideas. No code written/available yet.</em> | ✔ | ✔ | ✔
Alpha<em><br />- Branched, but unstable. Not production-ready (high risk if used).</em> | ✔ | ✔
Beta<em><br />- Branched, but unstable. Not production-ready (low risk if used).</em> | ✔ | ✔
Stable<em><br />- First production-ready version has been tagged/released.</em> | ✔ | ✔
Mature<em><br />- Multiple stable versions/releases exist.</em> | ✔ | ✔
Locked<em><br />- Still maintained, but new features won't be implemented anymore.</em> | ✔
EoL/Dead<em><br />- Not maintained anymore. If possible, stop using, and update ASAP.</em> | ✔

---


Last Updated: 10 June 2020 (2020.06.10).
