## CIDRAM (Classless Inter-Domain Routing Access Manager) Changelog.

*CIDRAM adopts and adheres to [Semantic Versioning](https://semver.org/).*

### v3.0.0

*CIDRAM v3 branches from v2 at v2.8.0 (2022.02.14).*

*At the time of branching, CIDRAM's changelog was more than 2,300 lines long and was becoming difficult to read due to both its length and its format. To improve readability, I've decided to clear out the old changelogs from the v3 branch, and to switch from plain-text format to markdown format from v3 onward. The old changelogs will continue at previous branches and remain accessible from there.*

#### Backwards-incompatible changes.
- Removed support for webfonts (this expands upon other changes made in the past in response to potential legal concerns; #66).
- Removed support for INI files in favour of using just YAML files instead (this is necessary because of changes to the configuration directives available made for v3 and how those changes are intended to work for v3).
- Reorganised how CIDRAM handles L10N data.
- Configuration directives `hide_version`, `empty_fields`, `omit_ip`, `omit_hostname`, and `omit_ua` have been replaced by a new configuration directive, `fields`. The new configuration directive is capable of providing the same functionality provided previously, as well as some other, new, related functionality.
- Configuration directives `error_log_stages` and `track_mode` have been replaced by a new configuration directive, `stages`. The new configuration directive is capable of providing the same functionality provided previously, as well as some other, new, related functionality (#208, #211).
- Configuration directive `statistics` has been changed from a boolean to a checkbox, to enable users to specify exactly *which* statistics they want to track. It's also now possible to track non-blocked requests (#204).
- Configuration directive `maintenance_mode` removed. "Maintenance mode" is now implicit (determined by which execution stages are enabled), rather than explicit (determined by its own configuration directive).
- Configuration directive `forbid_on_block` has been renamed to `http_response_header_code` (#136, #139).
- Default value for `http_response_header_code` has been changed to `403`.
- Reorganised CIDRAM's file structure, non-executable assets separated into their own directory (front-end and core alike) plus various other small structural changes.
- Flags.css is now bundled as part of the front-end and thus installed by default, instead of being its own component and not installed by default as was the case before.
- Caching has been unified. Instead of the front-end having its own, separate cache file (frontend.dat), it now just uses CIDRAM's main cache system, and the "frontend.dat" and "frontend.dat.safety" files don't exist anymore.
- Completely overhauled the login, sessions, and accounts management system. Account information is now stored within the CIDRAM configuration file, and session information is now handled by CIDRAM's main cache system.
- Front-end configuration directives split off to their own category, and it's now possible to set the default themes/templates for the front-end and other generated output (e.g., block event page) separately.
- Removed some of the backwards-compatibility code for older themes/templates.

#### Bugs fixed.
- Some specific files were being misclassified by the file manager; Fixed.
- HCaptcha class was sending invalid headers when generating output; Fixed (#293).
- Wrong CSP headers being set by the HCaptcha class; Fixed (#294).
- Fixed a bottleneck caused by the ReadFile closure.

#### Other changes.
- Improved IP address resolution strategy (#286).
- Changed the `enable_apcu` default value to `true` and the `prefix` default value to `CIDRAM_`.
- Checkbox configuration directives are now delimited in the configuration by newlines instead of commas.
- The `Output` stage of the execution chain has been split into four distinct, separate stages for easier configurability and control.
- Added a mechanism to the front-end IP test and IP tracking pages to enable the copying of IPs displayed there.
- Added a copy mechanism for the output of all range-based pages.
- Added two new configuration directives, `block_event_title` and `captcha_title`, allowing users to customise the page title used for block events and CAPTCHA requests (#216).
- Added a "dry run mode" (determined by which execution stages are enabled). While in dry run mode, requests are still checked and logged as block events as per usual, but nothing is blocked (#98, #221).
- Added warnings for when the IP tests, modules, or page termination stages are disabled, and for when there aren't any active signature files (as long as the IP tests stage is enabled) or any active modules (as long as the modules stage is enabled).
- The calculator (previously, the "CIDR calculator") now shows both CIDRs and netmasks.
- At the range tables page, show the IPv4/IPv6 totals side by side, for easier comparison between the two.
- Removed some unused file manager icons and slightly simplified its logic.
- Added a JavaScript warning to the front-end login.
- Front-end warnings have been hidden from non-logged in users.
- Made the warnings/notices at the front-end accounts page slightly smaller.
