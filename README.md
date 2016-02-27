## **What is CIDRAM?**

CIDRAM (Classless Inter-Domain Routing Access Manager) is a PHP script designed to protect websites by blocking requests originating from IP addresses regarded as being sources of undesirable traffic, including (but not limited to) traffic from non-human access endpoints, cloud services, spambots, scrapers, etc. It does this by calculating the possible CIDRs of the IP addresses supplied from inbound requests and then attempting to match these possible CIDRs against its signature files (these signature files contain lists of CIDRs of IP addresses regarded as being sources of undesirable traffic); If matches are found, the requests are blocked.

---

### Features:
- Licensed as [GNU General Public License version 2.0](https://github.com/Maikuolan/CIDRAM/blob/master/LICENSE.txt) (GPLv2).
- Easy to install, easy to customise, easy to use.
- Works for any system with PHP+PCRE installed, regardless of OS (PHP+PCRE required).
- Fully configurable based on your needs.
- Ideal solution for websites and forum systems using shared hosting services.
- Does NOT require shell access.
- Does NOT require administrative privileges.
- Good, strong, stable support base.

---

### Documentation:
- **[English](https://github.com/Maikuolan/CIDRAM/blob/master/_docs/readme.en.md)**

*Translations of the documentation to other languages coming soon.*

---

*This file, "README.md", last edited: 27th February 2016 (2016.02.27).*
