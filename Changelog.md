## CIDRAM (Classless Inter-Domain Routing Access Manager) Changelog.

*CIDRAM adopts and adheres to [Semantic Versioning](https://semver.org/).*

### v3.0.0

*CIDRAM v3 branches from v2 at v2.8.0 (2022.02.14).*

*At the time of branching, CIDRAM's changelog was more than 2,300 lines long and was becoming difficult to read due to both its length and its format. To improve readability, I've decided to clear out the old changelogs from the v3 branch, and to switch from plain-text format to markdown format from v3 onward. The old changelogs will continue at previous branches and remain accessible from there.*

*v3.0.0 major release tagged/released 2023.01.24.*

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
- Moved all the signature files and modules to their own dedicated directories.
- Front-end configuration directives split off to their own category, and it's now possible to set the default themes/templates for the front-end and other generated output (e.g., block event page) separately.
- Removed some of the backwards-compatibility code for older themes/templates.
- URLs for remotes are now specified by the configuration, instead of by the components metadata. New configuration directive `remotes` added accordingly.
- All available CIDRAM themes are to be bundled with CIDRAM as of v3 onward.
- CIDRAM won't have predefined entrypoints anymore. From v3 onward, you can specify your entrypoints wherever and however you want (details about how to do this will be included in the documentation and applicable installation instructions).
- The `disable_frontend` and `protect_frontend` configuration directives have been removed. These directives would be irrelevant for v3, because whether the front-end is "disabled" or "protected" for v3 onward would depend entirely on how you build your entrypoints.
- Configuration directive `config_imports` has been renamed to `imports`, and along with the configuration directives `ipv4`, `ipv6`, `modules`, and `events`, has been moved to a new configuration category, `components`.
- The `default_dns` configuration directive, and all `components` configuration directives (`ipv4`, `ipv6`, `modules`, `imports`, `events`), now delimit entries by newlines (\x0A), no longer delimiting by commas, thus having them behave more naturally as "lists".
- Component supplementary configuration can no longer be loaded implicitly, and must now be listed explicitly as an import entry in order to be loaded.
- Component type can no longer be implicitly discerned from its description, and must now be declared explicitly by its metadata.
- The overall structure of the CIDRAM codebase has been rearranged, made more classful, and namespaced in entirety, its file structure completely rewritten, no more functions files or similar, said parts of the codebase now existing as traits or classes wherever appropriate in order to better facilitate entrypoint changes and a better API experience.
- The updater and its internal workings, as well as the overall structure of components metadata, have been completely reworked and rewritten (although with very limited UI changes). Those supplying remotes to the public will need to update their formatting accordingly (the general userbase outside of that won't need to be concerned about these changes).
- Configuration directive `enable_two_factor` has been moved from the `phpmailer` configuration category to the `frontend` configuration category.
- PHPMailer further decoupled from the main CIDRAM codebase. Various hooks and bridges between CIDRAM and PHPMailer are now handled by event handlers rather than through hardcoding.
- Moved all the event handlers and imports to their own dedicated directories.
- Most (but not all) available CIDRAM modules are to be bundled with CIDRAM as of v3 onward.
- There's no longer any need for an external API or CLI script for CIDRAM, as these are both bundled into CIDRAM itself as of v3 onward.
- Configuration directives `max_login_attempts` and `signatures_update_event_log` have been moved from the `general` configuration category to the `frontend` configuration category.
- Configuration directives `standard_log`, `apache_style_log`, `serialised_log`, `error_log`, `truncate`, `log_rotation_limit`, `log_rotation_action`, `log_banned_ips`, and `log_sanitisation` have been moved to a new configuration category, `logging`.
- Configuration directives for CAPTCHA logging have been renamed.
- Configuration directives `search_engines`, `social_media`, and `other` have been moved to a new configuration category, `verification`.
- Configuration directives `block_attacks`, `block_cloud`, `block_bogons`, `block_generic`, `block_legal`, `block_malware`, `block_proxies`, and `block_spam` have been replaced by a new configuration directive, `shorthand`. The new configuration directive is capable of providing the same functionality provided previously, as well as some other, new, related functionality.
- Configuration can't be injected directly via globals anymore. Instead, paths to files containing any configuration external to CIDRAM's own configuration files can now be specified via the Core's constructor.

#### Bugs fixed.
- Some specific files were being misclassified by the file manager; Fixed.
- HCaptcha class was sending invalid headers when generating output; Fixed (#293).
- Wrong CSP headers being set by the HCaptcha class; Fixed (#294).
- Fixed a bottleneck caused by the ReadFile closure (since v3, the readFile method).
- The `nonblocked_status_code` configuration directive wasn't displaying as intended at the front-end configuration page; Fixed.
- Instead of "GMT", the "Last modified" header given for front-end assets specified "+0000", which some browsers don't understand properly; Fixed.
- When using the front-end IP test page, hostnames sometimes weren't looked up properly under some conditions; Fixed (#313).
- Reconstructed URIs didn't account for port number; Fixed.
- Detection for "sensitive pages" sometimes wouldn't detect contact pages; Fixed (#351).
- Compatibility issue when using Firefox to access some of the options provided at the front-end auxiliary rules page; Fixed (#356).
- The reset button at the front-end configuration page wasn't resetting the hidden "other" field; Fixed.
- Redis shouldn't set EXPIRE for TTLs less than 1; Fixed (#369).
- Added `ob_get_level()` guard for `ob_end_flush()` call at registered shutdown closure for in case of missing buffer data (e.g., due to prior calls at the implementation) to resolve related notice (#367).
- Decimals in the names, choice keys, and labels keys of configuration directives were being coerced by PHP to underscores; Fixed.

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
- The aggregator has been decoupled from its internal references to the CIDRAM working data, bringing it more in line with its stand-alone counterpart.
- Added the copy SVG to the front-end signature file fixer page.
- Improved log identification strategy.
- Following symlinks for RecursiveDirectoryIterator instances enabled.
- Slightly improved RTL support.
- Added the ability to enable/disable auxiliary rules (#318).
- The path to the cache file can now be customised.
- Made IPs at the IP test page searchable.
- At the updater, when a checksum error occurs, the difference between the actual and the expected will be displayed now.
- Confirmation is now sought before engaging an attempt to delete an auxiliary rule, and the option moved to the far right to reduce the risk of engaging by accident (#333).
- Added the ability to reset specific parts of the configuration back to their defaults (#331).
- Added L10N support for some additional languages.
- IPs can now be added to tracking manually (#310).
- Custom headers/footers for front-end pages, CAPTCHAs, and the access denied page can now be set directly via configuration (#312, #328).
- Added warnings to the edit file feature of the file manager regarding editing PHP files directly via browsers and regarding editing files which belong to components.
- Hardened some configuration constraints.
- Reworked duration-based configuration.
- Reworked how the configuration page deals with volume-based configuration.
- Added a mechanism to perform adjustments for when in the context of verification (e.g., to prevent CAPTCHAs being served for blocked requests which failed verification).
- Added a new configuration directive, `segregate`, to specify whether the data for rate limiting should be segregated based on domain/hostname or shared (#345).
- Added a new page to the front-end for the purpose of viewing current rate limit quotas, useful for confirming whether rate limiting is working correctly at installations (#345).
- Added a new configuration directive, `report_log`, for logging when reports are submitted to external APIs.
- Avoid packaging unnecessary files into dist.
- Adjusted minimum value for some port directives from 1 to 0.
- Added a new configuration directive, `sensitive`, to allow users to specify which paths should be regarded as sensitive pages.
- Added a new module for IP-API (#373).
- Protocol is now recognised as a source for auxiliary rules and as a block event field.

### v3.1.0

[2023.01.26; Bug-fix; Maikuolan]: When authenticating a completed CAPTCHA instance, depending on the exact configuration, in some cases, salts were being munged after fetching the instance hash but prior to verifying the hash, resulting in an infinite loop of needing to recomplete CAPTCHA instances; Fixed (#389).

[2023.01.27; Maikuolan]: Display proper status message when updating configuration fails.

[2023.01.28; New Feature; Maikuolan]: Added a new page to the front-end for the importing/exporting of configuration, auxiliary rules, etc.

[2023.01.28; Bug-fix; Maikuolan]: A database system incorrectly casting an integer to a string when attempting to fetch all cache entries could, in theory, cause the front-end IP tracking page to be unable to correctly display the expiry information for any current tracking entries, due to an `is_int()` guard in the IP tracking page code. Changed it to `is_numeric()` to resolve the problem.

[2023.01.28; Bug-fix; Maikuolan]: Cache handler potentially generating unexpected exceptions in some situations; Fixed (#388).

[2023.01.29; Maikuolan]: Sticky the names of auxiliary rules while scrolling in edit mode to help reduce confusion over which rule's data is currently being edited.

[2023.01.29; Bug-fix; Maikuolan]: At the auxiliary rules page, when attempting to submit form data while the number of input fields displayed on the page exceeds the `php.ini` value defined for `max_input_vars` (such a thing could happen if, e.g., more than several dozen auxiliary rules already exist, or some auxiliary rules have a very large number of conditions attached), a warning would be generated, and PHP would arbitrarily drop post values from the submitted form data, the end result being that auxiliary rules would be arbitrarily munged or dropped; Fixed (#384).

[2023.02.01; Bug-fix; Maikuolan]: Undefined index warning occasionally generated at resetBypassFlags with specific IP ranges; Fixed.

[2023.02.01; Maikuolan]: Better controls for the type of visitor for the Project Honeypot module (#396).

[2023.02.03; Bug-fix; Maikuolan]: Cronable's localUpdate() broken for CIDRAM v3; Fixed (Maikuolan/Cronable#9).

[2023.02.05; Maikuolan]: Slightly improved the lookup API (#401).

[2023.02.05; Bug-fix; Maikuolan]: Lookup API wasn't honouring auxiliary rules or verification parameters; Fixed (#401).

[2023.02.05; Maikuolan]: Added the ability to export individual auxiliary rules to the auxiliary rules page.

[2023.02.06; Maikuolan]: Shifted the auxiliary rules view mode options to an expandable menu.

[2023.02.12; Maikuolan]: Apply single-hit bypasses to API lookups (#410).

[2023.02.13; Maikuolan]: Slightly improved performance for when using PDO (#393).

[2023.02.14; Maikuolan]: Slightly improved client-specified language overrides.

[2023.02.16; Bug-fix; Maikuolan]: Typo in the readFile call for the channels data; Fixed (#414).

[2023.02.18; Bug-fix; Maikuolan]: Tally mode at the front-end logs page wouldn't always correctly delimit entry fields when reading log files with mixed content (e.g., block events and CAPTCHA events in the same file); Fixed.

[2023.02.21; Maikuolan]: Replaced the emojis at the front-end navigation menu with SVGs.

[2023.02.21; Bug-fix; Maikuolan]: When using the cache handler with PDO, if the size of a cache entry exceeds 64KB, in order to avoid exceeding the 64KB upper limit of the TEXT data type, the cache handler will now attempt to compress the data prior to executing the query. This should resolve #415.

[2023.02.22; Maikuolan]: Improved CIDRAM's ability to integrate with other systems (e.g., WordPress). Affects #135, #419.

### v3.1.1

[2023.02.24; Bug-fix; Maikuolan]: The cache handler's incEntry and decEntry methods weren't handling non-expiring values correctly when using flatfile caching; Fixed (#328).

[2023.02.28; Maikuolan]: Added verification support for Snapchat (#422). Added search engine verification support for Neeva.

[2023.02.28; Maikuolan]: Adjusted the eTaggable method, increasing expiries from 1 month to 6 months to further reduce superfluous requests for static files, and explicitly removing the Cache-Control header due to some implementations setting the Cache-Control header within their own code, prior to calling CIDRAM, thus preventing browsers from being able to properly cache CIDRAM's static assets (explicitly removing the header should ensure that static assets can be cached as expected).

[2023.03.01; Maikuolan]: Logs sort order now also affects the list of log files (#424).

[2023.03.06; Bug-fix; Maikuolan]: Type-casting bug discovered in the code for auxiliary rules direct string comparison condition matching; Fixed (#429).

[2023.03.06; Documentation; Maikuolan]: Due to changes implemented by GitHub in how it handles anchors in markdown files, a vast majority of the internal links within the documentation were broken, as well as most links within the L10N data which pointed to said documentation; Fixed.

[2023.03.08; Maikuolan]: Optimised nav icons, pip icons, and related assets (#431).

[2023.03.09; Maikuolan]: The signature file fixer is now capable of detecting and removing extraneous spaces from signatures (#435).

[2023.03.09; Maikuolan]: When an auxiliary rule is triggered, show the actual rule name in the signature reference, not just the line number for where in the code the trigger can be found.

### v3.2.0

[2023.03.13; Bug-fix; Maikuolan]: Typo found in CIDRAM's WordPress update hook; Fixed.

[2023.03.13; Bug-fix; Maikuolan]: If a logs link cache entry for the logs page has already been set, IP tracking entries shown at the IP tracking page would sometimes be munged by the copy to clipboard SVGs; Fixed.

[2023.03.15~16; New Feature; Maikuolan]: Added support for macros to the updates page (#418).

[2023.03.18; Bug-fix; Maikuolan]: Bug discovered in the code for dealing with type casting/coercion in auxiliary rules; Fixed (#442).

[2023.03.19; Bug-fix; Maikuolan]: Type error found in the updates executor; Fixed (#442).

[2023.03.23; Bug-fix; Maikuolan]: Some executor calls at the updates page could never be triggered due to relying on downstream metadata which mightn't exist at the time of the call; Switched to the upstream for such instances to resolve the problem.

[2023.03.24; New Feature; Maikuolan]: Added the ability to log outbound requests (#445).

[2023.03.24; Maikuolan]: Report logs now include the IP address of the report.

[2023.03.25; Bug-fix; Maikuolan]: Non-blocked CAPTCHAs broken since v3.1; Fixed.

[2023.03.25; Maikuolan]: Added the ability to include release notes for new versions to the updates page.

[2023.03.25; Bug-fix; Maikuolan]: Some entries missing from the isLogFile event handler; Fixed.
