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

[2023.03.13~14; New Feature; Maikuolan]: A new block event field (optional, disabled by default) has been added: Conditions inspection. This new field is intended only for logging (i.e., it can't be displayed at the "access denied" page). Conditions inspection can be used to confirm exactly which conditions for which auxiliary rules were/weren't satisfied for any given block event, which may sometimes be needed when attempting to debug problematic auxiliary rules (#430, #438).

[2023.03.15~16; New Feature; Maikuolan]: Added support for macros to the updates page (#418).

[2023.03.18; Bug-fix; Maikuolan]: Bug discovered in the code for dealing with type casting/coercion in auxiliary rules; Fixed (#442).

[2023.03.19; Bug-fix; Maikuolan]: Type error found in the updates executor; Fixed (#442).

[2023.03.23; Bug-fix; Maikuolan]: Some executor calls at the updates page could never be triggered due to relying on downstream metadata which mightn't exist at the time of the call; Switched to the upstream for such instances to resolve the problem.

[2023.03.24; New Feature; Maikuolan]: Added the ability to log outbound requests (#445).

[2023.03.24; Maikuolan]: Report logs now include the IP address of the report.

[2023.03.25; Bug-fix; Maikuolan]: Non-blocked CAPTCHAs broken since v3.1; Fixed.

[2023.03.25; Maikuolan]: Added the ability to include release notes for new versions to the updates page.

[2023.03.25; Bug-fix; Maikuolan]: Some entries missing from the isLogFile event handler; Fixed.

[2023.03.31; Maikuolan]: Eliminated a low-risk potential performance bottleneck at the loadL10N method.

[2023.04.01; Maikuolan]: Reworked the loadL10N method, how it handles HTTP_ACCEPT_LANGUAGE, improved rule assignment, added some assumptions for supported L10N, and added the ability to defer non-supported L10N to supported L10N where sufficiently similar to be acceptable.

[2023.04.02; Bug-fix; Maikuolan]: Infractions not decrementing upon bypass; Fixed (#449).

[2023.04.03~04; Bug-fix; Maikuolan]: Some front-end pages were calling L10N data directly (which means, no opportunity for fetching fallback data) instead of invoking it via getString (getString tries to fetch fallback L10N data when primary L10N data isn't available); Fixed.

[2023.04.06; Bug-fix; Maikuolan]: Exit code 1 responses generated via run parameters in signatures weren't being honoured (although nothing in CIDRAM currently actually returns exit code 1 anyway, so this doesn't have any practical bearing); Fixed.

[2023.04.06; Bug-fix; Maikuolan]: Yandex verification broken since v3.0; Fixed.

[2023.04.06; Maikuolan]: Default signature bypasses updated.

[2023.04.07; Maikuolan]: Reworked the IP test page.

[2023.04.08; Maikuolan]: Colourised CIDRAM's CLI mode and improved some of its L10N.

[2023.04.10; Maikuolan]: Added warning about iMessage false positives due to Twitterbot verification to the configuration page and turned Twitterbot verification off by default.

[2023.04.18; Maikuolan]: Added warnings for when it's detected that access to the APIs used by various modules appear to be being rate limited (#453).

[2023.04.23; Maikuolan]: Added a checkbox to the IP testing page to toggle whether to test as a sensitive request (#453).

### v3.2.1

[2023.04.25; Bug-fix; Maikuolan]: Explicitly defining the region for the configured L10N where deferment may be available but the region for the corresponding listed configuration choice not included could've caused the L10N loader to simply use fallbacks instead; Fixed.

[2023.04.25; Maikuolan]: Split the existing L10N for Portuguese into two variants, Brazilian and European.

[2023.04.25; Maikuolan]: Added an option to the rate limiting exceptions to enable requests to the CIDRAM front-end to be exempted from rate limiting (#457).

[2023.05.03; Bug-fix; Maikuolan]: Under specific circumstances, the front-end updater would sometimes attempt to (de/re)activate non-activable components; Fixed (#462).

[2023.05.06; Maikuolan]: Slightly improved some peripheral updater functionality.

[2023.05.07; Maikuolan]: Merged the "suppress logging" and "don't log" internal flags, assigned forced IP tracking status its own flags, slightly restructured block event simulation, and updated the IP testing page to properly describe all currently supported auxiliary rules options and special flags, IP tracking status, and banned status (#464).

[2023.05.08; Maikuolan]: Results at the IP test page now include the HTTP status code which could be expected (#464).

[2023.05.08; Maikuolan]: Results at the IP test page now include information about redirects via silent mode and auxiliary rules (#464).

[2023.05~06; Maikuolan]: Added L10N for Bulgarian and Czech.

[2023.05.22; Maikuolan]: For the BGPView module and the IP-API module, whether to perform lookups for all requests or just for requests for sensitive pages only, can now be configured (#467).

[2023.05.22; Maikuolan]: Improved the log sorting strategy (#463).

[2023.05.23; Bug-fix; Maikuolan]: Single-digit h/i/s date/time placeholders weren't being honoured; Fixed.

### v3.3.0

[2023.06.13; New Feature; Maikuolan]: The HTTP response code used when silent mode is invoked is now configurable.

[2023.06.13; Maikuolan]: 302 now permitted as an auxiliary rule HTTP status code override.

[2023.06.13; Maikuolan]: The number of reports, both successful and failed, which CIDRAM attempted to submit to external APIs (at this time, namely, just AbuseIPDB's API) can now be tracked via the front-end statistics page.

[2023.06.14; Bug-fix; Maikuolan]: Copied text not rendering properly at the front-end calculator page; Fixed.

[2023.06.15; Bug-fix; Maikuolan]: Wrong MIME type returned for custom ICO favicons; Fixed.

[2023.06.16; Maikuolan]: Slightly adjusted the favicon.

[2023.06.19; Bug-fixes; Maikuolan]: When paginating unfiltered mixed log data, seeking the final block's needle position could, in some circumstances, cause fatal errors to occur; Fixed. Needles containing non-alphanumeric characters which weren't URL-encoded could potentially break pagination ordering; Fixed. Pagination progress bar could float beyond the page confines when filtered search parameters returned exactly one result; Fixed.

[2023.06.20; Maikuolan]: Improved pagination progress bar precision. Adjusted the pagination information displayed at the logs page to make it easier for the user to track their progress within the pagination (#386).

[2023.07.14; Maikuolan]: Added CSS for selection pseudo-element.

[2023.07.14; Bug-fix; Maikuolan]: When needed version information is missing from component metadata, uncaught type errors could potentially occur at the updates page in some cases; Fixed.

[2023.06~07; Maikuolan]: Added L10N for Punjabi.

### v3.4.0

[2023.08.01; Maikuolan]: Report logging entries now include whether the submission was okay or failed. Improved the labelling L10N at the auxiliary rules page.

[2023.08.02; New Feature; Maikuolan]: Statistics can now be tracked for how many times an auxiliary rule is triggered and when (#374).

[2023.08.02; Maikuolan]: The statistics page start date now includes relative time.

[2023.08.03; Bug-fix; Maikuolan]: When the names of auxiliary rules, cache entries, or statistic entries contained certain kinds of characters, JavaScript which used those names could sometimes break due to insufficient escaping; Fixed.

[2023.08.03; Bug-fix; neufeind]: The aggregator's constructTables method was constructing the wrong IPv6 netmask for the last octet; Fixed.

[2023.08.06; New Feature; Maikuolan]: Added the ability to instruct CIDRAM to detect the method for testing auxiliary rules conditions automatically (#371).

[2023.08.08; Maikuolan]: Conjunctive reporting and profiling improved.

[2023.08.10; Maikuolan]: Successfully verified requests are now profiled. Numerous additional webshells and backdoors are now blocked. Some modules refactored. Slightly adjusted the favicon again. Added support for verifying (or blocking) ChatGPT-User and GPTBot.

[2023.08.12; Maikuolan]: Updated the flags CSS.

[2023.08.16; Bug-fix; Maikuolan]: When at the very beginning of a signature file, without any preceding linebreaks, signatures based on IP addresses rather than CIDRs (i.e., without a CIDR block size specified) weren't being correctly recognised by CIDRAM; Fixed (#499).

[2023.08.20; Maikuolan]: Added operator hints to the auxiliary rules page.

[2023.08.22; New Feature; Maikuolan]: The front-end backup page can now import/export statistics and IP tracking data (#500).

[2023.08.23; Maikuolan]: Added rateLimited event trigger to rate limiting. Pushed the reporting stage of the execution chain down to after CAPTCHAs but before statistics. The AbuseIPDB module's report_back directive has been changed from a boolean to an integer, and reporting behaviour can now be configured to account for whether the request is blocked (previously, reports would be processed as long as report_back was set to true and some reports existed in the queue, which could be arguably described as a bug, but may possibly be desirable to some users).

[2023.08.24; Maikuolan]: Added support for bulk reporting to the AbuseIPDB module.

[2023.08.26; Maikuolan]: Better flexible widths for the condition text inputs on the front-end auxiliary rules page (#505).

### v3.4.1

[2023.09.15; Bug-fixes; Maikuolan]: Auxiliary rules automatic method detection failed to detect for numeric comparison; Fixed. Auxiliary rules numeric comparison could accept non-numeric values under certain conditions; Fixed.

[2023.09.16~18; Maikuolan]: Significantly refactored all L10N data.

[2023.09.18; Maikuolan]: Better resource guarding (#516).

[2023.09.19~20; Maikuolan]: BunnyCDN and IP-API modules can now populate profiles.

[2023.09.20; Maikuolan]: The front-end IP testing interface can now switch between focusing primarily on IP addresses versus focusing primarily on user agents (#464).

[2023.09.20; Bug-fix; Maikuolan]: Added horizontal scrollbars for rows generated by IP testing which exceed the normal width of the page to prevent page overflow (#447).

[2023.09.20; Security; Maikuolan]: Self-inflicted XSS was possible at the front-end IP testing page when user agents, queries, or referrers which contain valid HTML tags (e.g., JavaScript) were entered for testing; Fixed.

[2023.09.21; Maikuolan]: Replaced all emoji at the auxiliary rules page with SVGs. Replaced the delete emoji at the cache data page with an SVG.

[2023.10.05; Bug-fix; Maikuolan]: Stop Forum Spam module found to have been broken since v3.0; Fixed.

[2023.09~10; Maikuolan]: Added L10N for Afrikaans and Romanian.

### v3.4.2

[2023.10.13; Bug-fix; Maikuolan]: Some calls to the executor were missing a necessary parameter, and when to engage queuing wasn't always being decided correctly; Fixed.

[2023.10.16; Maikuolan]: Added decorative SVGs to the front-end backup page.

[2023.10.21; Bug-fix; Maikuolan]: The label for the CAPTCHA submit button wasn't rendering properly; Fixed (#526).

[2023.11.10; Maikuolan]: Better missing class guarding.

[2023.11.17; Maikuolan]: Added an icon for entries at the IP tracking page to go directly to the AbuseIPDB reporting page for those entries.

[2023.11.18; Maikuolan]: Improved dynamic icons, extended the AbuseIPDB reporting page icon to the logs page, and added an IP testing page icon to IP tracking and logs page entries.

[2023.11.19; Maikuolan]: Restyled file inputs.

[2023.11.21; Bug-fix; Maikuolan]: The YAML handler's unescape method wasn't unescaping correctly when escaped backslashes preceded other escapable symbols; Fixed (#532).

[2023.11.26; Bug-fix; Maikuolan]: The repair option at the updates page sometimes not offered when it should be; Fixed.

[2023.11.27; Bug-fix; Maikuolan]: The isSensitive method would sometimes fail to correctly identify regular expressions; Fixed.

[2023.11.27; Maikuolan]: Added a new option for the usemode configuration directives: To offer CAPTCHAs only when not blocked, at sensitive page requests.

### v3.5.0

[2023.12.01; Maikuolan]: Improved escaping. Added support for specifying a Redis database number to the supplementary cache options (#540).

[2023.12.02; Bug-fix; Maikuolan]: Two-factor authentication found to be broken since v3.0.0-beta1; Fixed.

[2023.12.03; Bug-fixes; Maikuolan]: When an installed component was outdated, but the version constraints of the update's dependencies weren't met, the update shouldn't be being included in the list of outdated components for updating all at once, but was; Fixed. At the page for entering a 2FA code when logging into a 2FA-enabled account, no logout button was displayed, preventing the user from logging out easily, which may be needed in the event of not receiving any 2FA code; Fixed.

[2023.12.08; Maikuolan]: Improved resource guards for the auxiliary rules file.

[2023.12.08; Bug-fix; Maikuolan]: Not escaping keys when reconstructing YAML data could prevent successful reprocessing of those keys if said keys contained any hashes or backslashes. The solution is to enforce escaping of keys when such bytes are detected, regardless of how the property for quoting keys is defined. Accordingly, that's been done, and a new method added for that purpose (#547).

[2023.12.12; Security; Maikuolan]: Added a method to check whether a name is reserved, and applied it as a guard at the points where signature files, modules, and events are read in, and where files are required via Run commands. Attempting to perform file operations on reserved names under Windows and some other operating systems could cause the underlying file system to attempt to communicate with a serial port instead of the intended file. PHP is likely to then wait indefinitely for a response it's unlikely to ever receive, thus locking up the process and preventing further requests unless the process is restarted. Although it's infinitesimally unlikely that a user would actually want to use a reserved name for one of their signature files, modules, events, or run commands, as the solution is exceedingly simple, with no particular performance impact, I've implemented it accordingly.

[2023.12.12; Maikuolan]: Split the code for most of the various front-end pages, which the view method was responsible for, into their own distinct files. Worked it in such a way that it should now be possible to create custom front-end pages. Renamed some assets.

[2023.12.15; New Feature; Maikuolan]: Capture groups from regular expression auxiliary rule matches can now be reflected into an auxiliary rule's name or block reason (#524).

[2023.12.15~26; New Feature; Maikuolan]: Built an integrated reporting page to be able to manually report IP addresses to AbuseIPDB directly from the CIDRAM Front-End, or to be able to delete previous reports in the event that an IP address has been reported in error (#338).

[2023.12.26; Maikuolan]: Refactored the page greeting and some theme assets.

[2023.12.29; Bug-fix; Maikuolan]: Some of the more unusual available number formatting choices (e.g., choices not using base-10 or Arabic numerals) didn't mesh well with the JavaScript code responsible for using them; Fixed.

### v3.5.1

[2024.02.20; Maikuolan]: The internal AbuseIPDB reporting page now includes a field to specify the exact time of attack for reports.

[2024.04.04; Bug-fix; Maikuolan]: Removed the minimum parameter from the input fields for the start and expiry dates at the auxiliary rules page, as it inteferred with a user's ability to modify any auxiliary rules when such a field preceded the current date (#572).

### v3.6.0

[2024.04.13; Bug-fix; Maikuolan]: The ban check at the beginning of the execution chain would only occur if signature file checks were enabled and the inbound IP address confirmed to be valid. As such, if an IP address had sufficient infractions that it should be banned, but signature file tests were disabled, the IP address wouldn't be treated as banned. Such guarding was useful in the past, back before individual stages of the execution chain could be enabled/disabled via the configuration, but due to various changes made to the codebase since that time, as well as now posing a bug, isn't particularly useful anymore. As such, the ban check has been separated out and pushed to the top of the execution chain, thus fixing the bug. (The ban check currently shares configuration checkboxes with IP tracking in order to avoid BC breaks between minor/patch releases, but will get its own configuration checkboxes with v4). Possibly related to #576.
